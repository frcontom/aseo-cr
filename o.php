<?php

if(isset($ValidateCode) and !empty($ValidateCode)){
    $data = $ValidateCode;
    //validar que el usuario tenga un tipo 2 ya que tiene horario
    if($data['hs_type'] == 2){

        $employ_id = $data['he_id'];
        $schedules_id = $data['hes_id'];
        //validar el si empleado ya registro horas solo son dos 1 ENgreso 2 Salida despues de esto ya no puede registrar horas

        $horaryBiometric = $this->general->query("select * from horary_employ As he JOIN horary_biometric As hb ON he.he_id=hb.hb_employ WHERE he.he_id='".$employ_id."' AND hb.hb_date='".$dates ."'",'array',false);
        $countBiometric = count($horaryBiometric);


        if(isset($horaryBiometric) and $countBiometric != 1){

            $check_value = ($countBiometric == 0) ? 1 : 2;

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
                //ENTRADA
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


//                                    $dataIn = [
//                                        'hb_employ'     => $employ_id,
//                                        'hb_schedules'  => $schedules_id,
////                                    'hb_check'      => $check_value,
//                                        'hb_date'       => fecha(1),
//                                        'hb_time_entry' => hora(),
//                                        'hb_status'     => 1,
////                                    'hb_status'     => $tracking_status,
////                                    'hb_timer'      => $trading_timer,
//                                        'hb_type' => 1,
////                                    'hb_picture'    => $fileName,
//                                        'hb_atcreate'   => fecha(2)
//                                    ];

            }

            else{
                //SALIDA
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

//                                    $dataIn = [
//                                        'hb_employ'     => $employ_id,
//                                        'hb_schedules'  => $schedules_id,
////                                    'hb_check'      => $check_value,
//                                        'hb_date'       => fecha(1),
//                                        'hb_time_exit' => hora(),
//                                        'hb_status'     => 1,
////                                    'hb_status'     => $tracking_status,
////                                    'hb_timer'      => $trading_timer,
//                                        'hb_type' => 1,
////                                    'hb_picture'    => $fileName,
//                                        'hb_atcreate'   => fecha(2)
//                                    ];
//                                    echo 'salida';
            }
//                                print_r($ValidateCode);
//                                print_r($horaryBiometric);
//                                print_r($calculehour)

            // Si la persona llega antes igual se debe registrar la hora de ingreso / hora de ingreso hora de trabajo
            $entry_exit = [
                'employ_id' => $employ_id,
                'schedules_id' => $schedules_id,
                'hb_complete' => 1,
                'type'          => 1
            ];

            $entry_exit_where = [
                'hb_employ'     => $employ_id,
                'hb_schedules'  => $schedules_id,
                'hb_date'       => fecha(1)
            ];

            $entry_exit_check = [
                'picture'  => $fileName,
                'type'     => 1,
            ];

            $this->biometric_calcule_save($check_value,$entry_exit,$entry_exit_check); //register entry



            file_put_contents($filePath, $decodedData);
            //validar que despues de 5 minutos despues de su horario es llegar tarde
            // si la persona sale despues yo  registro la hora y debo enviar un whatsapp indicando que el empleado salio mas tarde de su horario

            if($statusWhatsapp == 1){
                sendNotification('Vora',$messageWhatsapp,$UsersNotify);
            }

            $this->result = array('success' => 1,'data' => $insertQ['crypt']);

        }else{
            //cuando el empleado sale y vuelve a marcar el mismo dia se sobre entiende que se aplica las horas extras

            print_r($horaryBiometric);


            $this->result = array('success' => 2,'msg' => 'Ya Registraste tu ENTRADA y SALIDA');
        }
//                            print_r($horaryBiometric);

    }else{

        //cuando el usuario esta en alguna actividad de vacaiones u otro que no meresca al trabajador se marcara de manera especial


        /*
           * Type is = 4
        */

        //obtener siempre el ultimo horario registrado por el empleado para sacar el codigo de la actividad



        $this->result = array('success' => 2,'msg' => 'Lo Siento Hoy estas en : ' . $data['hs_value']);
    }
}
else{
    //cuando no se les asigna un horario establecido se define que es una hora extra ya que no se sabe en donde debe trabajar
    /*
     * Type is = 1 , 2, 3
     */

    $this->result = array('success' => 2,'msg' => 'No tienes Horario Asignado comunicate con la jefatura de turno');
}