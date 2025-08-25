<?php

// Primer array
$array1 = [
    [
        'fecha' => '2025-03-13',
        'hora_tipo' => 'Hora Ordinaria (jornada normal)',
        'tipo_hora_id' => 1,
        'hora_entrada' => '07:00:00',
        'hora_salida' => '17:00:00',
        'hora_marcado_entrada' => '07:00:00',
        'hora_marcado_salida' => '17:00:00',
        'tipo_dia' => '',
        'tipo_hora' => 'Hora Ordinaria (jornada normal)',
        'horas_trabajadas' => 10,
        'minutos_trabajados' => 600,
        'factor_pago' => 1
    ]
];

// Segundo array
$array2 = [
    [
        'fecha' => '2025-03-13',
        'hora_tipo' => 'Hora Ordinaria (jornada normal)',
        'tipo_hora_id' => 1,
        'hora_entrada' => '',
        'hora_salida' => '',
        'hora_marcado_entrada' => '17:00:00',
        'hora_marcado_salida' => '17:30:00',
        'tipo_dia' => '',
        'tipo_hora' => 'Hora Ordinaria (jornada normal)',
        'horas_trabajadas' => 0.5,
        'minutos_trabajados' => 30,
        'factor_pago' => 0.5
    ]
];

// Función para combinar los arrays por fecha, manteniendo registros con índices numerados
function combinarArraysPorFecha($array1, $array2)
{
    $resultado = [];

    // Combinar ambos arrays
    $todosLosRegistros = array_merge($array1, $array2);

    // Agrupar los registros por fecha
    foreach ($todosLosRegistros as $registro) {
        $fecha = $registro['fecha']; // Tomamos la fecha como clave
        if (!isset($resultado[$fecha])) {
            $resultado[$fecha] = []; // Inicializamos el array para esa fecha si no existe
        }
        // Agregamos el registro al array de esa fecha
        $resultado[$fecha][] = $registro;
    }

    return $resultado;
}

// Llamamos a la función para combinar los arrays
$resultado = combinarArraysPorFecha($array1, $array2);

// Imprimir el resultado combinado
echo "<pre>";
print_r($resultado);
echo "</pre>";

?>
