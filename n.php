<?php





//// Función para registrar las horas extras considerando el cruce de días y fechas
//function registrarHorasExtras($fechaEsperadaSalida, $horaEsperadaSalida, $fechaMarcadaSalida, $horaMarcadaSalida) {
//    // Convertir las horas y fechas a formato DateTime
//    $horaEsperadaSalida = new DateTime("$fechaEsperadaSalida $horaEsperadaSalida");
//    $horaMarcadaSalida = new DateTime("$fechaMarcadaSalida $horaMarcadaSalida");
//
//    // Inicializar el array de resultados
//    $resultados = [];
//
//    // Verificar si la hora marcada de salida es posterior a la hora esperada (trabajo extra)
//    if ($horaMarcadaSalida > $horaEsperadaSalida) {
//        // Registro normal hasta la hora de salida esperada
//        $horaSalidaNormal = $horaEsperadaSalida;
//        $resultados['registroNormal'] = [
//            'fecha' => $horaEsperadaSalida->format('Y-m-d'),  // Fecha original
//            'ingreso' => $horaEsperadaSalida->format('H:i:s'),
//            'salida' => $horaSalidaNormal->format('H:i:s')
//        ];
//
//        // Registrar las horas extras:
//        // La hora extra empieza 1 minuto después de la salida esperada
//        $horaExtraIngreso = clone $horaEsperadaSalida;
//        $horaExtraIngreso->modify('+1 minute'); // Hora extra empieza 1 minuto después de la salida esperada
//
//        // La fecha de entrada extra es la fecha de corte (la fecha de la salida esperada)
//        $fechaExtraEntrada = $horaEsperadaSalida->format('Y-m-d'); // Fecha de corte
//
//        // La salida extra es la hora de salida marcada por el empleado
//        $horaExtraSalida = $horaMarcadaSalida;
//
//        // La fecha de salida extra es la fecha de marcado de salida
//        $fechaExtraSalida = $horaMarcadaSalida->format('Y-m-d'); // Fecha de salida real
//
//        // Registrar las horas extras con las fechas correspondientes
//        $resultados['extra'] = [
//            0 => [
//                'data' => $fechaExtraEntrada,   // Fecha ajustada para el día de corte (la fecha de salida esperada)
//                'hour' => $horaExtraIngreso->format('H:i:s')
//            ],
//            1 => [
//                'data' => $fechaExtraSalida,   // Fecha ajustada para el día de marcado de salida
//                'hour' => $horaExtraSalida->format('H:i:s')
//            ]
//        ];
//
//    } else {
//        // Si no hay horas extras, solo se registra el horario normal
//        $resultados['registroNormal'] = [
//            'fecha' => $fechaEsperadaSalida,  // Fecha original
//            'ingreso' => $horaEsperadaSalida->format('H:i:s'),
//            'salida' => $horaMarcadaSalida->format('H:i:s')
//        ];
//    }
//
//    // Regresar los resultados
//    return $resultados;
//}
//
//// Ejemplo de uso con fechas y horas específicas
//$FechaEsperadaSalida = '2025-05-03'; // Fecha esperada de salida
//$horaEsperadaSalida = '20:00:00'; // Hora esperada de salida
//
//$FechaMarcadaSalida = '2025-05-04'; // Fecha de salida marcada (el empleado se pasó de día)
//$horaMarcadaSalida = '19:01:00'; // Hora marcada de salida
//
//// Ejecutar la función
//$resultado = registrarHorasExtras($FechaEsperadaSalida, $horaEsperadaSalida, $FechaMarcadaSalida, $horaMarcadaSalida);
//
//// Imprimir los resultados
//print_r($resultado);
//


if(isset($horaryBiometric) and $horaryBiometric['cantidad_check'] <= 1 AND $horaryBiometric['complete_check'] < 1){
    //quiere decir que el empleado entro y debe salir o realmente no ha marcado

    $check_value = ($horaryBiometric['cantidad_check'] == 0) ? 1 : 2;//aqui se sabe si el empleado entro o va a salir

    //horary
    $profile_admin = $ValidateCode['hc_profile'];
    $employ_name = $ValidateCode['he_name'];
    $hour_entry = $ValidateCode['hs_hour1'];
    $hour_exit  = $ValidateCode['hs_hour2'];


    /*
        * validar si es entrada o salida
        * Validar si la persona llego a tiempo o tarde
   */


    $time_actual = date('H:i:s');
    $statusWhatsapp = 0;
    $messageWhatsapp = '';
    $UsersNotify = [];
    //Info hour tracking
    $tracking_status = 0;
    $trading_timer = 0;

    if($check_value == 1){
        //validacion de entrada
        $calculehour = $this->class_security->verificar_puntualidad($hour_entry,$time_actual,5,'entrada');

        if($calculehour['status'] == 2){
            //send message whatsapp
            $statusWhatsapp = 1;

            $UsersNotify = (array)$this->general->all_get('users',['u_profile' => $profile_admin, 'u_phone !=' => ''],[],'array',[],[],'u_phone');
            $minutus_extras = $this->class_security->pass_minute_to_hours($calculehour['timer']);
            $messageWhatsapp = "*NOVEDAD HORA DE ENTRADA* \n Empleado : *{$employ_name}* \n Hora de Ingreso : *{$hour_entry}* \n Hora de Marcado : *{$time_actual}* \n Llegada Tarde : *{$minutus_extras}*";

            $tracking_status = $calculehour['status'];
            $trading_timer = $calculehour['timer'];
        }

    }else{
        //validacion de salida

        $calculehour = $this->class_security->verificar_puntualidad($hour_exit,$time_actual,30,'salida');
        if($calculehour['status'] != 1){
            //send message whatsapp
            $statusWhatsapp = 1;

            $UsersNotify = (array)$this->general->all_get('users',['u_profile' => $profile_admin, 'u_phone !=' => ''],[],'array',[],[],'u_phone');
            $minutus_extras = $this->class_security->pass_minute_to_hours($calculehour['timer']);
            $messageWhatsapp = "*NOVEDAD HORA DE SALIDA* \n Empleado :  *{$employ_name}* \n Hora de Salida : *{$hour_exit}* \n Hora de Marcado : *{$time_actual}* \n Tiempo de novedad : *{$minutus_extras}*";

            $tracking_status = $calculehour['status'];
            $trading_timer = $calculehour['timer'];
        }
    }


    //registro y validacion de datos
    $entry_exit = [
        'employ_id'     => $employ_id,
        'schedules_id'  => $schedules_id,
        'hb_complete'   => 1,
        'type'          => 1
    ];

    $entry_exit_where = [
        'hb_id'         => $horaryBiometric['hb_id'],
        'hb_employ'     => $employ_id,
        'hb_schedules'  => $schedules_id,
        'hb_complete'   => 1,
//                                'hb_date'       => fecha(1)
    ];

    $entry_exit_check = [
        'picture'    => $fileName,
        'type'       => $check_value,
        'time_fail'  => $trading_timer,
    ];
    $this->biometric_calcule_save($check_value,$entry_exit,$entry_exit_check,$entry_exit_where); //register entry

    file_put_contents($filePath, $decodedData);
//                            //validar que despues de 5 minutos despues de su horario es llegar tarde
//                            // si la persona sale despues yo  registro la hora y debo enviar un whatsapp indicando que el empleado salio mas tarde de su horario
//
    if($statusWhatsapp == 1){
        sendNotification('Vora',$messageWhatsapp,$UsersNotify);
    }

    $this->result = array('success' => 1);

}
else{
    // el empleado ya marco y lo que viene aca seria Hora extra
//                            echo 'hora extra por defecto';

    $horaryBiometric = $this->biometric_last_register($employ_id, $dates);
    $check_value = (!isset($horaryBiometric['hb_id']) and $horaryBiometric['hb_id'] == '') ? 1 : 2;//aqui se sabe si el empleado entro o va a salir
//                            print_r($horaryBiometric);exit;
//                            echo $check_value;exit;
    $entry_exit = [
        'employ_id'     => $employ_id,
        'schedules_id'  => '',
        'hb_complete'   => 1,
        'type'          => 2
    ];

    $entry_exit_where = [
        'hb_id'         => $horaryBiometric['hb_id'],
        'hb_employ'     => $employ_id,
        'hb_schedules'  => '',
        'hb_complete'   => 1,
//                                'hb_date'       => fecha(1)
    ];

    $entry_exit_check = [
        'picture'  => $fileName,
        'type'     => $check_value,
    ];
    $this->biometric_calcule_save($check_value,$entry_exit,$entry_exit_check,$entry_exit_where); //register entry

    file_put_contents($filePath, $decodedData);

    $this->result = array('success' => 1);
}

