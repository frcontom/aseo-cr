<?php

class BioHour
{

    //biometric models
    function registrarHorasExtras($fechaEsperadaSalida, $horaEsperadaSalida, $fechaMarcadaSalida, $horaMarcadaSalida) {
        // Convertir las horas y fechas a formato DateTime
        $horaEsperadaSalida = new DateTime("$fechaEsperadaSalida $horaEsperadaSalida");
        $horaMarcadaSalida = new DateTime("$fechaMarcadaSalida $horaMarcadaSalida");

        // Inicializar el array de resultados
        $resultados = [];

        // Verificar si la hora marcada de salida es posterior a la hora esperada (trabajo extra)
        if ($horaMarcadaSalida > $horaEsperadaSalida) {
            // Registro normal hasta la hora de salida esperada
            $horaSalidaNormal = $horaEsperadaSalida;
            $resultados['registroNormal'] = [
                'fecha' => $horaEsperadaSalida->format('Y-m-d'),  // Fecha original
                'ingreso' => $horaEsperadaSalida->format('H:i:s'),
                'salida' => $horaSalidaNormal->format('H:i:s')
            ];

            // Registrar las horas extras:
            // La hora extra empieza 1 minuto después de la salida esperada
            $horaExtraIngreso = clone $horaEsperadaSalida;
            $horaExtraIngreso->modify('+1 minute'); // Hora extra empieza 1 minuto después de la salida esperada

            // La fecha de entrada extra es la fecha de corte (la fecha de la salida esperada)
            $fechaExtraEntrada = $horaEsperadaSalida->format('Y-m-d'); // Fecha de corte

            // La salida extra es la hora de salida marcada por el empleado
            $horaExtraSalida = $horaMarcadaSalida;

            // La fecha de salida extra es la fecha de marcado de salida
            $fechaExtraSalida = $horaMarcadaSalida->format('Y-m-d'); // Fecha de salida real

            // Registrar las horas extras con las fechas correspondientes
            $resultados['extra'] = [
                0 => [
                    'fecha' => $fechaExtraEntrada,   // Fecha ajustada para el día de corte (la fecha de salida esperada)
                    'marcado' => $horaExtraIngreso->format('H:i:s'),
                    'hb_complete'    => 1,
                    'type'    => 1,
                ],
                1 => [
                    'fecha' => $fechaExtraSalida,   // Fecha ajustada para el día de marcado de salida
                    'marcado' => $horaExtraSalida->format('H:i:s'),
                    'hb_complete'    => 2,
                    'type'    => 2,
                ]
            ];

        } else {
            // Si no hay horas extras, solo se registra el horario normal
            $resultados['registroNormal'] = [
                'fecha' => $fechaEsperadaSalida,  // Fecha original
                'ingreso' => $horaEsperadaSalida->format('H:i:s'),
                'salida' => $horaMarcadaSalida->format('H:i:s')
            ];
        }

        // Regresar los resultados
        return $resultados;
    }

// Ejemplo de uso con fechas y horas específicas
//$FechaEsperadaSalida = '2025-05-03'; // Fecha esperada de salida
//$horaEsperadaSalida = '20:00:00'; // Hora esperada de salida
//
//$FechaMarcadaSalida = '2025-05-03'; // Fecha de salida marcada (el empleado se pasó de día)
//$horaMarcadaSalida = '19:01:00'; // Hora marcada de salida
//
//// Ejecutar la función
//$resultado = registrarHorasExtras($FechaEsperadaSalida, $horaEsperadaSalida, $FechaMarcadaSalida, $horaMarcadaSalida);
}