<?php
// Empaqueta 128 floats a Float32 LE (512 bytes)
function floatArrayToBlob(array $v): string {
    if (count($v) !== 128) throw new RuntimeException('Vector debe ser 128D');
    return pack('g*', ...$v);
}

// Desempaqueta BLOB -> array<float> (espera 512 bytes)
function blobToFloatArray(string $blob): array {
    if (strlen($blob) !== 512) throw new RuntimeException('BLOB debe ser 512 bytes');
    $arr = unpack('g128', $blob); // Float32 little-endian
    return array_values($arr);
}

// Normaliza L2
function l2Normalize(array $v): array {
    $s = 0.0; foreach ($v as $x) $s += $x*$x;
    $n = sqrt($s) ?: 1.0;
    foreach ($v as &$x) $x /= $n;
    return $v;
}

// Distancia Euclidiana con poda temprana
function l2Distance(array $a, array $b, float $earlyStop = INF): float {
    $sum = 0.0;
    for ($i = 0; $i < 128; $i++) {
        $d = $a[$i] - $b[$i];
        $sum += $d*$d;
        if ($sum > $earlyStop) return INF; // poda
    }
    return sqrt($sum);
}
