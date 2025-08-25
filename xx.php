<?php
declare(strict_types=1);

/**
 * validate_face.php
 * Compara un descriptor recién capturado con la base `horary_employ_faceid`
 * y devuelve el mejor match por empleado (mínima distancia entre sus plantillas).
 *
 * POST:
 *  - descriptor  (string JSON array<float>)  -> requerido (captura actual)
 *  - employ_id   (int, opcional)             -> filtra por empleado
 *  - threshold   (float, opcional)           -> default 0.6
 *  - metric      (string, opcional)          -> 'l2' (default) | 'cosine'
 *
 * Respuesta JSON:
 * {
 *   ok: true,
 *   metric: "l2",
 *   threshold: 0.6,
 *   count_rows_compared: 123,
 *   count_employees_compared: 57,
 *   best_employee: {
 *     hfc_employ: 10,
 *     best_hfc_id: 345,
 *     distance: 0.4821,
 *     match: true
 *   }
 * }
 */

// ================== CONFIG ==================
const DB_DSN  = 'mysql:host=localhost;dbname=tu_db;charset=utf8mb4';
const DB_USER = 'tu_usuario';
const DB_PASS = 'tu_password';

const DEFAULT_THRESHOLD = 0.6;     // recomendación inicial con face-api.js
const DEFAULT_METRIC    = 'l2';    // 'l2' o 'cosine'

// ================== HELPERS ==================
function json_response($data, int $status = 200): void {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

function parse_descriptor(string $json): array {
    $arr = json_decode($json, true);
    if (!is_array($arr) || empty($arr)) {
        throw new InvalidArgumentException('Descriptor inválido: se esperaba JSON con array numérico.');
    }
    return array_map('floatval', $arr);
}

function l2_distance(array $a, array $b): float {
    $na = count($a); $nb = count($b);
    if ($na !== $nb) {
        throw new InvalidArgumentException('Dimensiones distintas entre descriptores.');
    }
    $sum = 0.0;
    for ($i = 0; $i < $na; $i++) {
        $d = $a[$i] - $b[$i];
        $sum += $d * $d;
    }
    return sqrt($sum);
}

// Retorna 1 - cos_sim para que "menor es mejor" igual que L2.
// (equivale a distancia de coseno)
function cosine_distance(array $a, array $b): float {
    $na = count($a); $nb = count($b);
    if ($na !== $nb) {
        throw new InvalidArgumentException('Dimensiones distintas entre descriptores.');
    }
    $dot = 0.0; $na2 = 0.0; $nb2 = 0.0;
    for ($i = 0; $i < $na; $i++) {
        $ai = $a[$i]; $bi = $b[$i];
        $dot += $ai * $bi;
        $na2 += $ai * $ai;
        $nb2 += $bi * $bi;
    }
    if ($na2 == 0.0 || $nb2 == 0.0) {
        return INF; // vector degenerado
    }
    $cosSim = $dot / (sqrt($na2) * sqrt($nb2));
    return 1.0 - $cosSim; // 0 es idéntico, 2 es opuesto
}

function get_metric_fn(string $metric): callable {
    $m = strtolower($metric);
    if ($m === 'cosine') return 'cosine_distance';
    return 'l2_distance'; // default
}

// ================== MAIN ==================
try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        json_response(['ok' => false, 'error' => 'Usa POST'], 405);
    }

    if (!isset($_POST['descriptor'])) {
        json_response(['ok' => false, 'error' => 'Falta `descriptor`'], 400);
    }

    $captured = parse_descriptor($_POST['descriptor']);
    $descLen  = count($captured);

    $threshold = isset($_POST['threshold']) ? floatval($_POST['threshold']) : DEFAULT_THRESHOLD;
    $metric    = isset($_POST['metric']) ? (string)$_POST['metric'] : DEFAULT_METRIC;
    $distFn    = get_metric_fn($metric);

    $employFilter = isset($_POST['employ_id']) && $_POST['employ_id'] !== ''
        ? (int)$_POST['employ_id']
        : null;

    // Conexión PDO
    $pdo = new PDO(DB_DSN, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => false, // stream
    ]);

    // Selecciona SOLO lo necesario
    if ($employFilter) {
        $stmt = $pdo->prepare(
            'SELECT hfc_id, hfc_employ, hfc_descriptor 
             FROM horary_employ_faceid 
             WHERE hfc_employ = :emp'
        );
        $stmt->execute([':emp' => $employFilter]);
    } else {
        $stmt = $pdo->query(
            'SELECT hfc_id, hfc_employ, hfc_descriptor 
             FROM horary_employ_faceid'
        );
    }

    // Mantenemos el mejor de CADA empleado y luego elegimos el mejor global
    $byEmployee = []; // employ_id => ['best_hfc_id'=>, 'best_dist'=>]
    $countRows  = 0;

    while ($row = $stmt->fetch()) {
        $countRows++;
        $empId = (int)$row['hfc_employ'];
        $json  = $row['hfc_descriptor'];

        if ($json === null || $json === '') continue;
        $dbDesc = json_decode($json, true);
        if (!is_array($dbDesc)) continue;
        if (count($dbDesc) !== $descLen) continue;

        // Cast a float
        $dbDesc = array_map('floatval', $dbDesc);

        // Distancia con la métrica elegida
        $dist = $distFn($captured, $dbDesc);

        if (!isset($byEmployee[$empId]) || $dist < $byEmployee[$empId]['best_dist']) {
            $byEmployee[$empId] = [
                'best_hfc_id' => (int)$row['hfc_id'],
                'best_dist'   => $dist,
            ];
        }

        // Early exit opcional si el match es casi perfecto
        if ($dist < 1e-6) break;
    }

    if (empty($byEmployee)) {
        json_response([
            'ok' => true,
            'metric' => $metric,
            'threshold' => $threshold,
            'count_rows_compared' => $countRows,
            'count_employees_compared' => 0,
            'best_employee' => null,
            'message' => 'No se encontraron descriptores válidos para comparar.'
        ]);
    }

    // Mejor global entre los mínimos por empleado
    $bestEmpId   = null;
    $bestHfcId   = null;
    $bestDist    = null;

    foreach ($byEmployee as $empId => $info) {
        if ($bestDist === null || $info['best_dist'] < $bestDist) {
            $bestDist  = $info['best_dist'];
            $bestEmpId = $empId;
            $bestHfcId = $info['best_hfc_id'];
        }
    }

    json_response([
        'ok' => true,
        'metric' => $metric,
        'threshold' => $threshold,
        'count_rows_compared' => $countRows,
        'count_employees_compared' => count($byEmployee),
        'best_employee' => [
            'hfc_employ'  => $bestEmpId,
            'best_hfc_id' => $bestHfcId,
            'distance'    => round((float)$bestDist, 6),
            'match'       => ($bestDist !== null && $bestDist <= $threshold),
        ],
    ]);

} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 400);
}
