<?php

class Biometric extends CI_Controller
{
    //propiedades
    private $session_id;
    private $session_token;
    private $user_data;
    private $controllName = 'Biometric';
    private $controller   = 'biometric/';

    private $result = array();

    public function __construct(){
        parent::__construct();

        $this->project        = $this->config->config['project'];

        $this->load->library('BioHour');

    }

    function index() {
        $data_body = array(
            'titulo'        => $this->project['tiulo'],
            'crud' => array(
                'url_calcule'     => base_url("{$this->controller}biometric_calcule"),
                'url_picture'     => base_url("{$this->controller}biometric_picture"),
            )
        );

        $this->load->view('biometric/v_dashboard',$data_body);
    }

    function faceid() {
        $data_body = array(
            'titulo'        => $this->project['tiulo'],
            'crud' => array(
                'url_picture'     => base_url("{$this->controller}biometric_picture"),
            )
        );

        $this->load->view('biometric/v_face_id',$data_body);
    }


    function biometric_picture(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('imageData','descriptor'))){

                //campos post
                $imageData    = $this->class_security->data_form('imageData');
                $descriptor    = $this->class_security->data_form('descriptor');
                $this->load->helper('face');
                $dates      = fecha(1);
                $time_actual      = fecha(2);

                $arr = json_decode($descriptor, true);
                $query = l2Normalize(array_map('floatval', $arr));

                $threshold  = 0.55;
                if ($threshold <= 0 || $threshold > 1.5) $threshold = 0.55;

                //obtener los empleados con su respectivo descriptor
                $employs = $this->general->query("select * from horary_employ As e  JOIN horary_employ_faceid As ef ON e.he_id=ef.hfc_employ WHERE e.he_status = 1 AND  ef.hfc_descriptor_blob  != ''");

                $bestDist = INF;
                $bestEmp  = null;
                $bestId   = null;

                foreach($employs as $employ){


                    $blob = $employ['hfc_descriptor_blob'];
                    // Seguridad: algunas extensiones pueden devolver NULL si hay datos corruptos
                    if ($blob === null) continue;


                    $vec = blobToFloatArray($blob); // ya debe estar normalizado en registro
                    $dist = l2Distance($query, $vec, $bestDist*$bestDist);
                    if ($dist < $bestDist) {
                        $bestDist = $dist;
                        $bestEmp  = (int)$employ['hfc_employ'];
                        $bestId   = (int)$employ['hfc_id'];
                        $phone   = (int)$employ['he_phone'];
                    }

                    // Early exit agresivo opcional
                    if ($bestDist <= 0.35) break;


                }

                if ($bestEmp !== null && $bestDist <= $threshold) {


                    //el empleado con el mejor descriptor existe


                    //enviar un mensaje de whatsapp al administrador del empleado
                    $userd = [
                        ['u_phone' => $phone]
                    ];
                    $messageWhatsapp = "*SE REGISTRO LA HORA*  \n Hora de Marcado : *{$time_actual}*";

                    sendNotification('Vora',$messageWhatsapp,$userd);//send message whatsapp

                    // Elimina el prefijo 'data:image/png;base64,' de la cadena base64
                    $filteredData = substr($imageData, strpos($imageData, ",") + 1);
                    $fileName = 'biometric_' . $this->class_security->random() . '.png';
                    $filePath = '_files/biometric/' . $fileName;
                    $decodedData = base64_decode($filteredData);


                    //validar que el codigo exista y tambien validar que el usuario tenga horario el dia actual y que tambien tenga horario y no descanso
                    $ValidateCode = $this->general->query("SELECT
    he.he_id,he.he_name,hc_profile,hes.hes_date,hs.hs_hour1,hs.hs_hour2,hes.hes_id,hs.hs_type,t2.hb_complete As 'complete_t2',t.*
FROM horary_employ AS he
        LEFT JOIN horary_employ_schedules AS hes ON he.he_id = hes.hes_employ 
        LEFT JOIN horary_schedules AS hs ON hes.hes_schedules = hs.hs_id
        LEFT JOIN horary_category AS hc ON he.he_category = hc.hc_id
        LEFT JOIN (SELECT b.hb_id,b.hb_date,b.hb_time_entry,b.hb_time_exit,b.hb_complete,b.hb_employ,b.hb_type   FROM horary_biometric AS b  ) AS t  ON he.he_id = t.hb_employ AND t.hb_type = 1 AND t.hb_complete != 2
        LEFT JOIN (SELECT b.hb_date,b.hb_complete,b.hb_employ,b.hb_type  FROM horary_biometric AS b) AS t2  ON he.he_id = t2.hb_employ AND t2.hb_type = 1 AND t2.hb_complete = 2  and t2.hb_date = '".$dates."'
WHERE he.he_id = '".$bestEmp."'  AND hes.hes_date = COALESCE(t.hb_date, '".$dates."') LIMIT 1",'array',true);



                    //validar que el usuario tenga un horario registrado
                    if(isset($ValidateCode) and !empty($ValidateCode)){
                        $data = $ValidateCode;

                        //variables Modules
                        $employ_id = $data['he_id'];
                        $schedules_id = $data['hes_id'];
                        $profile_admin = $ValidateCode['hc_profile'];
                        $employ_name = $ValidateCode['he_name'];
                        $date_entry = $ValidateCode['hes_date'];
                        $hour_entry = $ValidateCode['hs_hour1'];
                        $hour_exit  = $ValidateCode['hs_hour2'];
                        $hs_type  = $ValidateCode['hs_type'];
                        $complete_t2  = $ValidateCode['complete_t2'];
                        $time_actual = date('H:i:s');

                        //biometric values
                        $bm_id          = isset($ValidateCode['hb_id']) ? $ValidateCode['hb_id'] : '';
                        $bm_date        = isset($ValidateCode['hb_date']) ? $ValidateCode['hb_date'] : '';
                        $bm_time_entry  = isset($ValidateCode['hb_time_entry']) ? $ValidateCode['hb_time_entry'] : '';
                        $bm_time_exit   = isset($ValidateCode['hb_time_exit']) ? $ValidateCode['hb_time_exit'] : '';
                        $bm_complete    = (isset($ValidateCode['hb_complete'])) ? $ValidateCode['hb_complete'] : 0;
                        $bm_type        = isset($ValidateCode['hb_type']) ? $ValidateCode['hb_type'] : 0;;

//                        print_r($ValidateCode);

                        $time_actual = date('H:i:s');
                        $statusWhatsapp = 0;
                        $messageWhatsapp = '';
                        $UsersNotify = [];
                        //Info hour tracking
                        $tracking_status = 0;
                        $trading_timer = 0;
                        //saver si el empledo ya ha marcado o no
//                        $horaryBiometric = $this->biometric_last_register($employ_id, $dates);
//                        print_r($horaryBiometric);exit;

                        /*
                         * VALIDAR LOS SIGUIENTES ASPECTOS
                         * 1. El tipo de horario que tiene el empleado 1. Extra 2. Normal
                         * 2. Si el Empleado no ha marcado Inicia el flujo de entrada
                         * 3. Si el Empleado ya ha marcado y no ha salido, inicia el flujo de salida
                         * 4. Si el Empleado ya ha marcado y ya salio, inicia el flujo de hora extra
                         * 5. Si el Empleado ya ha marcado y ya salio y no tiene horario, inicia el flujo de hora extra
                         * */

//                        echo '<br>$bm_id----------------- ' . $bm_id . ' -----------------<br>';
//                        echo '$bm_time_exit----------------- ' . $bm_time_exit . ' -----------------<br>';
//                        echo '$bm_complete----------------- ' . $bm_complete . ' -----------------<br>';
//                        exit;
                        $dataIn = [];
                        if($hs_type == 2 and $complete_t2 != 2){
                            // ingreso y salida de horario normal
//                           echo 'hora normal';

                            //Validar si el empleado ya ha marcado
                            $check_value = (isset($bm_id) and ($bm_time_exit == '') and ($bm_complete == 0)) ? 1 : 2;//aqui se sabe si el empleado entro o va a salir

                            if($check_value == 1){
                                //validacion de entrada
//                                echo 'validacion de entrada';
                                $calculehour = $this->class_security->verificar_puntualidad($hour_entry,$time_actual,5,'entrada');//verificar la puntualidad

                                if($calculehour['status'] == 2){
                                    //send message whatsapp
                                    $statusWhatsapp = 1;

                                    $UsersNotify = (array)$this->general->all_get('users',['u_profile' => $profile_admin, 'u_phone !=' => ''],[],'array',[],[],'u_phone');
                                    $minutus_extras = $this->class_security->pass_minute_to_hours($calculehour['timer']);
                                    $messageWhatsapp = "*NOVEDAD HORA DE ENTRADA* \n Empleado : *{$employ_name}* \n Hora de Ingreso : *{$hour_entry}* \n Hora de Marcado : *{$time_actual}* \n Llegada Tarde : *{$minutus_extras}*";

                                    $tracking_status = $calculehour['status'];
                                    $trading_timer = $calculehour['timer'];
                                }

                                //data insert Array value

                                $dataIn[] = [
                                    'check_value' => $check_value,
                                    'checker' => [
                                        'date'         => fecha(1),
                                        'hour'         => hora(),
                                        'employ_id'     => $employ_id,
                                        'schedules_id'  => $schedules_id,
                                        'hb_complete'   => 1,
                                        'type'          => 1
                                    ],
                                    'where' => [
//                                        'hb_id'         => $bm_id,
                                        'hb_employ'     => $employ_id,
//                                        'hb_schedules'  => $schedules_id,
//                                        'hb_complete'   => 1,
                                    ],
                                    'picture' => [
                                        'date'         => fecha(1),
                                        'hour'         => hora(),
                                        'picture'    => $fileName,
                                        'type'       => $check_value,
                                        'time_fail'  => $trading_timer,
                                    ]
                                ];

                            }else{
                                //validacion de salida
//                                echo 'validacion de salida';
                                $calculehour = $this->class_security->verificar_puntualidad($hour_exit,$time_actual,30,'salida');//verificar la puntualidad
                                if($calculehour['status'] != 1){
                                    //send message whatsapp
                                    $statusWhatsapp = 1;

                                    $UsersNotify = (array)$this->general->all_get('users',['u_profile' => $profile_admin, 'u_phone !=' => ''],[],'array',[],[],'u_phone');
                                    $minutus_extras = $this->class_security->pass_minute_to_hours($calculehour['timer']);
                                    $messageWhatsapp = "*NOVEDAD HORA DE SALIDA* \n Empleado :  *{$employ_name}* \n Hora de Salida : *{$hour_exit}* \n Hora de Marcado : *{$time_actual}* \n Tiempo de novedad : *{$minutus_extras}*";

                                    $tracking_status = $calculehour['status'];
                                    $trading_timer = $calculehour['timer'];
                                }

                                //data insert Array value Tener en cuenta el posible corte
                                $BioHour = new BioHour();

                                $dataE = $BioHour->registrarHorasExtras($date_entry, $hour_exit, fecha(1), hora());
//                                print_r($dataE);

                                //registro salida normal
                                $dataIn[] = [
                                    'check_value' => $check_value,
                                    'checker' => [
                                        'date'         => $dataE['registroNormal']['fecha'],
                                        'hour'         => $dataE['registroNormal']['salida'],
                                        'employ_id'     => $employ_id,
                                        'schedules_id'  => $schedules_id,
                                        'hb_complete'   => 2,
                                        'type'          => 1
                                    ],
                                    'where' => [
//                                        'hb_id'         => $bm_id,
                                        'hb_employ'     => $employ_id,
//                                        'hb_schedules'  => $schedules_id,
//                                        'hb_complete'   => 1,
                                    ],
                                    'picture' => [
                                        'date'         => $dataE['registroNormal']['fecha'],
                                        'hour'         => $dataE['registroNormal']['salida'],
                                        'picture'    => $fileName,
                                        'type'       => $check_value,
                                        'time_fail'  => $trading_timer,
                                    ]
                                ];


                                //registro salida extra
                                if(isset($dataE['extra']) and count($dataE['extra']) >= 1){
                                    foreach ($dataE['extra'] as $key => $value) {
                                        $dataIn[] = [
                                            'check_value' => $value['type'],
                                            'checker' => [
                                                'date' => $value['fecha'],
                                                'hour' => $value['marcado'],
                                                'employ_id' => $employ_id,
                                                'schedules_id' => '',
                                                'hb_complete' => $value['hb_complete'],
                                                'type' => 2
                                            ],
                                            'where' => [
                                                'hb_employ' => $employ_id,
//                                                'hb_schedules' => '',
//                                                'hb_complete' => 1,
                                            ],
                                            'picture' => [
                                                'date' => $value['fecha'],
                                                'hour' => $value['marcado'],
                                                'picture' => $fileName,
                                                'type' => $value['type'],
                                                'time_fail' => 0,
                                            ]
                                        ];
                                    }
                                }

                            }

                            foreach($dataIn as $bioin) {
                                $this->biometric_calcule_save($bioin['check_value'],$bioin['checker'],$bioin['picture'],$bioin['where']); //register entry
                            }


                            //guardar la imagen localstorage
                            file_put_contents($filePath, $decodedData);

                            //se envia el mensaje por whatsapp
                            if($statusWhatsapp == 1){
                                sendNotification('Vora',$messageWhatsapp,$UsersNotify);
                            }



                        }else{
                            // ingreso y salida de horario extra

                            $validateOrExtra = $this->general->query("SELECT
                                    he.he_id,he.he_name,hc_profile,t.*
                                FROM horary_employ AS he
                                        LEFT JOIN horary_category AS hc ON he.he_category = hc.hc_id
                                        LEFT JOIN (
                                        SELECT b.hb_id,b.hb_date,b.hb_time_entry,b.hb_time_exit,b.hb_complete,b.hb_employ,b.hb_type
                                        FROM
                                            horary_biometric AS b
                                    ) AS t  ON t.hb_employ=he.he_id AND t.hb_type = 2 AND t.hb_complete != 2
                                WHERE he.he_id = '".$bestEmp."'",'array',true);
//                            print_r($validateOrExtra);

                            $bm_id          = isset($validateOrExtra['hb_id']) ? $validateOrExtra['hb_id'] : '';
                            $bm_time_exit   = isset($validateOrExtra['hb_time_exit']) ? $validateOrExtra['hb_time_exit'] : '';
                            $bm_complete    = (isset($validateOrExtra['hb_complete'])) ? $validateOrExtra['hb_complete'] : 0;

                            $check_value = (isset($bm_id) and ($bm_time_exit == '') and ($bm_complete == 0)) ? 1 : 2;//aqui se sabe si el empleado entro o va a salir


                            $dataIn[] = [
                                'check_value' => $check_value,
                                'checker' => [
                                    'date'         => fecha(1),
                                    'hour'         => hora(),
                                    'employ_id'     => $employ_id,
                                    'schedules_id'  => '',
                                    'hb_complete'   => 1,
                                    'type'          => 2
                                ],
                                'where' => [
                                    'hb_employ'     => $employ_id,
                                ],
                                'picture' => [
                                    'date'         => fecha(1),
                                    'hour'         => hora(),
                                    'picture'    => $fileName,
                                    'type'       => $check_value,
                                    'time_fail'  => '',
                                ]
                            ];


                            foreach($dataIn as $bioin) {
                                $this->biometric_calcule_save($bioin['check_value'],$bioin['checker'],$bioin['picture'],$bioin['where']); //register entry
                            }


                            //guardar la imagen localstorage
                            file_put_contents($filePath, $decodedData);
                            $this->result = array('success' => 1,'result' => 1,'msg'=> 'Se ha registrado correctamente tu hora de ingreso/salida');

                        }

                    }
                    else{
                        //-- ERRRRRRRROR NOT DATA REGISTER
                        $this->result = array('success' => 2,'result' => 2,'msg' => 'No tienes Horario Asignado comunicate con la jefatura de turno');
                    }



                } else {
//                    print_r(['ok'=>false, 'reason'=>'no_match', 'distance'=>is_finite($bestDist) ? $bestDist : null]);
                    $this->result = array('success' => 1,'result' => 2,'msg' => 'Lo Siento tu rostro aun no estas registrado en la plataforma','employ'=>null);
                }



            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }
    function biometric_calcule(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('code','picture'))){

                //campos post
                $code       = $this->class_security->data_form('code','int');
                $picture    = $this->class_security->data_form('picture');
                $dates      = fecha(1);
                if($code != '' and strlen($code) == 4){
                    // Elimina el prefijo 'data:image/png;base64,' de la cadena base64
                    $filteredData = substr($picture, strpos($picture, ",") + 1);
                    $fileName = 'biometric_' . $this->class_security->random() . '.png';
                    $filePath = '_files/biometric/' . $fileName;
                    $decodedData = base64_decode($filteredData);


                    //validar que el codigo exista y tambien validar que el usuario tenga horario el dia actual y que tambien tenga horario y no descanso
                    $ValidateCode = $this->general->query("SELECT
    he.he_id,he.he_name,hc_profile,hes.hes_date,hs.hs_hour1,hs.hs_hour2,hes.hes_id,hs.hs_type,t2.hb_complete As 'complete_t2',t.*
FROM horary_employ AS he
        LEFT JOIN horary_employ_schedules AS hes ON he.he_id = hes.hes_employ 
        LEFT JOIN horary_schedules AS hs ON hes.hes_schedules = hs.hs_id
        LEFT JOIN horary_category AS hc ON he.he_category = hc.hc_id
        LEFT JOIN (SELECT b.hb_id,b.hb_date,b.hb_time_entry,b.hb_time_exit,b.hb_complete,b.hb_employ,b.hb_type   FROM horary_biometric AS b  ) AS t  ON he.he_id = t.hb_employ AND t.hb_type = 1 AND t.hb_complete != 2
        LEFT JOIN (SELECT b.hb_date,b.hb_complete,b.hb_employ,b.hb_type  FROM horary_biometric AS b) AS t2  ON he.he_id = t2.hb_employ AND t2.hb_type = 1 AND t2.hb_complete = 2  and t2.hb_date = '".$dates."'
WHERE he.he_code = '".$code."'  AND hes.hes_date = COALESCE(t.hb_date, '".$dates."') LIMIT 1",'array',true);


                    //validar que el usuario tenga un horario registrado
                    if(isset($ValidateCode) and !empty($ValidateCode)){
                        $data = $ValidateCode;

                        //variables Modules
                        $employ_id = $data['he_id'];
                        $schedules_id = $data['hes_id'];
                        $profile_admin = $ValidateCode['hc_profile'];
                        $employ_name = $ValidateCode['he_name'];
                        $date_entry = $ValidateCode['hes_date'];
                        $hour_entry = $ValidateCode['hs_hour1'];
                        $hour_exit  = $ValidateCode['hs_hour2'];
                        $hs_type  = $ValidateCode['hs_type'];
                        $complete_t2  = $ValidateCode['complete_t2'];
                        $time_actual = date('H:i:s');

                        //biometric values
                        $bm_id          = isset($ValidateCode['hb_id']) ? $ValidateCode['hb_id'] : '';
                        $bm_date        = isset($ValidateCode['hb_date']) ? $ValidateCode['hb_date'] : '';
                        $bm_time_entry  = isset($ValidateCode['hb_time_entry']) ? $ValidateCode['hb_time_entry'] : '';
                        $bm_time_exit   = isset($ValidateCode['hb_time_exit']) ? $ValidateCode['hb_time_exit'] : '';
                        $bm_complete    = (isset($ValidateCode['hb_complete'])) ? $ValidateCode['hb_complete'] : 0;
                        $bm_type        = isset($ValidateCode['hb_type']) ? $ValidateCode['hb_type'] : 0;;

//                        print_r($ValidateCode);

                        $time_actual = date('H:i:s');
                        $statusWhatsapp = 0;
                        $messageWhatsapp = '';
                        $UsersNotify = [];
                        //Info hour tracking
                        $tracking_status = 0;
                        $trading_timer = 0;
                        //saver si el empledo ya ha marcado o no
//                        $horaryBiometric = $this->biometric_last_register($employ_id, $dates);
//                        print_r($horaryBiometric);exit;

                        /*
                         * VALIDAR LOS SIGUIENTES ASPECTOS
                         * 1. El tipo de horario que tiene el empleado 1. Extra 2. Normal
                         * 2. Si el Empleado no ha marcado Inicia el flujo de entrada
                         * 3. Si el Empleado ya ha marcado y no ha salido, inicia el flujo de salida
                         * 4. Si el Empleado ya ha marcado y ya salio, inicia el flujo de hora extra
                         * 5. Si el Empleado ya ha marcado y ya salio y no tiene horario, inicia el flujo de hora extra
                         * */

//                        echo '<br>$bm_id----------------- ' . $bm_id . ' -----------------<br>';
//                        echo '$bm_time_exit----------------- ' . $bm_time_exit . ' -----------------<br>';
//                        echo '$bm_complete----------------- ' . $bm_complete . ' -----------------<br>';
//                        exit;
                        $dataIn = [];
                        if($hs_type == 2 and $complete_t2 != 2){
                            // ingreso y salida de horario normal
//                           echo 'hora normal';

                            //Validar si el empleado ya ha marcado
                             $check_value = (isset($bm_id) and ($bm_time_exit == '') and ($bm_complete == 0)) ? 1 : 2;//aqui se sabe si el empleado entro o va a salir

                            if($check_value == 1){
                                //validacion de entrada
//                                echo 'validacion de entrada';
                                $calculehour = $this->class_security->verificar_puntualidad($hour_entry,$time_actual,5,'entrada');//verificar la puntualidad

                                if($calculehour['status'] == 2){
                                    //send message whatsapp
                                    $statusWhatsapp = 1;

                                    $UsersNotify = (array)$this->general->all_get('users',['u_profile' => $profile_admin, 'u_phone !=' => ''],[],'array',[],[],'u_phone');
                                    $minutus_extras = $this->class_security->pass_minute_to_hours($calculehour['timer']);
                                    $messageWhatsapp = "*NOVEDAD HORA DE ENTRADA* \n Empleado : *{$employ_name}* \n Hora de Ingreso : *{$hour_entry}* \n Hora de Marcado : *{$time_actual}* \n Llegada Tarde : *{$minutus_extras}*";

                                    $tracking_status = $calculehour['status'];
                                    $trading_timer = $calculehour['timer'];
                                }

                                //data insert Array value

                                $dataIn[] = [
                                    'check_value' => $check_value,
                                    'checker' => [
                                        'date'         => fecha(1),
                                        'hour'         => hora(),
                                        'employ_id'     => $employ_id,
                                        'schedules_id'  => $schedules_id,
                                        'hb_complete'   => 1,
                                        'type'          => 1
                                    ],
                                    'where' => [
//                                        'hb_id'         => $bm_id,
                                        'hb_employ'     => $employ_id,
//                                        'hb_schedules'  => $schedules_id,
//                                        'hb_complete'   => 1,
                                    ],
                                    'picture' => [
                                        'date'         => fecha(1),
                                        'hour'         => hora(),
                                        'picture'    => $fileName,
                                        'type'       => $check_value,
                                        'time_fail'  => $trading_timer,
                                    ]
                                ];

                            }else{
                                //validacion de salida
//                                echo 'validacion de salida';
                                $calculehour = $this->class_security->verificar_puntualidad($hour_exit,$time_actual,30,'salida');//verificar la puntualidad
                                if($calculehour['status'] != 1){
                                    //send message whatsapp
                                    $statusWhatsapp = 1;

                                    $UsersNotify = (array)$this->general->all_get('users',['u_profile' => $profile_admin, 'u_phone !=' => ''],[],'array',[],[],'u_phone');
                                    $minutus_extras = $this->class_security->pass_minute_to_hours($calculehour['timer']);
                                    $messageWhatsapp = "*NOVEDAD HORA DE SALIDA* \n Empleado :  *{$employ_name}* \n Hora de Salida : *{$hour_exit}* \n Hora de Marcado : *{$time_actual}* \n Tiempo de novedad : *{$minutus_extras}*";

                                    $tracking_status = $calculehour['status'];
                                    $trading_timer = $calculehour['timer'];
                                }

                                //data insert Array value Tener en cuenta el posible corte
                                $BioHour = new BioHour();

                                $dataE = $BioHour->registrarHorasExtras($date_entry, $hour_exit, fecha(1), hora());
//                                print_r($dataE);

                                //registro salida normal
                                $dataIn[] = [
                                    'check_value' => $check_value,
                                    'checker' => [
                                        'date'         => $dataE['registroNormal']['fecha'],
                                        'hour'         => $dataE['registroNormal']['salida'],
                                        'employ_id'     => $employ_id,
                                        'schedules_id'  => $schedules_id,
                                        'hb_complete'   => 2,
                                        'type'          => 1
                                    ],
                                    'where' => [
//                                        'hb_id'         => $bm_id,
                                        'hb_employ'     => $employ_id,
//                                        'hb_schedules'  => $schedules_id,
//                                        'hb_complete'   => 1,
                                    ],
                                    'picture' => [
                                        'date'         => $dataE['registroNormal']['fecha'],
                                        'hour'         => $dataE['registroNormal']['salida'],
                                        'picture'    => $fileName,
                                        'type'       => $check_value,
                                        'time_fail'  => $trading_timer,
                                    ]
                                ];


                                //registro salida extra
                                if(isset($dataE['extra']) and count($dataE['extra']) >= 1){
                                    foreach ($dataE['extra'] as $key => $value) {
                                        $dataIn[] = [
                                            'check_value' => $value['type'],
                                            'checker' => [
                                                'date' => $value['fecha'],
                                                'hour' => $value['marcado'],
                                                'employ_id' => $employ_id,
                                                'schedules_id' => '',
                                                'hb_complete' => $value['hb_complete'],
                                                'type' => 2
                                            ],
                                            'where' => [
                                                'hb_employ' => $employ_id,
//                                                'hb_schedules' => '',
//                                                'hb_complete' => 1,
                                            ],
                                            'picture' => [
                                                'date' => $value['fecha'],
                                                'hour' => $value['marcado'],
                                                'picture' => $fileName,
                                                'type' => $value['type'],
                                                'time_fail' => 0,
                                            ]
                                        ];
                                    }
                                }

                            }

                            foreach($dataIn as $bioin) {
                                $this->biometric_calcule_save($bioin['check_value'],$bioin['checker'],$bioin['picture'],$bioin['where']); //register entry
                            }


                            //guardar la imagen localstorage
                            file_put_contents($filePath, $decodedData);

                            //se envia el mensaje por whatsapp
                            if($statusWhatsapp == 1){
                                sendNotification('Vora',$messageWhatsapp,$UsersNotify);
                            }

                            $this->result = array('success' => 1);

                        }else{
                            // ingreso y salida de horario extra

                            $validateOrExtra = $this->general->query("SELECT
                                    he.he_id,he.he_name,hc_profile,t.*
                                FROM horary_employ AS he
                                        LEFT JOIN horary_category AS hc ON he.he_category = hc.hc_id
                                        LEFT JOIN (
                                        SELECT b.hb_id,b.hb_date,b.hb_time_entry,b.hb_time_exit,b.hb_complete,b.hb_employ,b.hb_type
                                        FROM
                                            horary_biometric AS b
                                    ) AS t  ON t.hb_employ=he.he_id AND t.hb_type = 2 AND t.hb_complete != 2
                                WHERE he.he_code = '".$code."'",'array',true);
//                            print_r($validateOrExtra);

                            $bm_id          = isset($validateOrExtra['hb_id']) ? $validateOrExtra['hb_id'] : '';
                            $bm_time_exit   = isset($validateOrExtra['hb_time_exit']) ? $validateOrExtra['hb_time_exit'] : '';
                            $bm_complete    = (isset($validateOrExtra['hb_complete'])) ? $validateOrExtra['hb_complete'] : 0;

                            $check_value = (isset($bm_id) and ($bm_time_exit == '') and ($bm_complete == 0)) ? 1 : 2;//aqui se sabe si el empleado entro o va a salir


                            $dataIn[] = [
                                'check_value' => $check_value,
                                'checker' => [
                                    'date'         => fecha(1),
                                    'hour'         => hora(),
                                    'employ_id'     => $employ_id,
                                    'schedules_id'  => '',
                                    'hb_complete'   => 1,
                                    'type'          => 2
                                ],
                                'where' => [
                                    'hb_employ'     => $employ_id,
                                ],
                                'picture' => [
                                    'date'         => fecha(1),
                                    'hour'         => hora(),
                                    'picture'    => $fileName,
                                    'type'       => $check_value,
                                    'time_fail'  => '',
                                ]
                            ];


                            foreach($dataIn as $bioin) {
                                $this->biometric_calcule_save($bioin['check_value'],$bioin['checker'],$bioin['picture'],$bioin['where']); //register entry
                            }


                            //guardar la imagen localstorage
                            file_put_contents($filePath, $decodedData);
                            $this->result = array('success' => 1);
                        }

                    }
                    else{
                        //-- ERRRRRRRROR NOT DATA REGISTER
                        $this->result = array('success' => 2,'msg' => 'No tienes Horario Asignado comunicate con la jefatura de turno');
                    }

                }else{
                    $this->result = array('success' => 2,'msg' => 'Lo Siento el código no es valido');
                }


            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    private function biometric_last_register($employ_id, $dates){
        return $this->general->query("select em.he_id,COALESCE(t.cantidad_check,0) As cantidad_check,COALESCE(c.complete_check,0) As complete_check,bm.* from horary_employ As em
LEFT JOIN horary_biometric AS bm ON em.he_id=bm.hb_employ AND bm.hb_complete != 2
         LEFT JOIN (
             SELECT hm.hb_employ,hm.hb_date,COUNT(*) As cantidad_check from horary_biometric As hm GROUP BY hm.hb_employ, hm.hb_date
) As t ON em.he_id=t.hb_employ AND t.hb_date= '".$dates."'
    LEFT JOIN (
             SELECT hm.hb_employ,hm.hb_date,COUNT(*) As complete_check from horary_biometric As hm where hm.hb_complete=2 GROUP BY hm.hb_employ, hm.hb_date
) As c ON em.he_id=c.hb_employ AND c.hb_date= '".$dates."'
WHERE em.he_id = '".$employ_id."'
                            ",'array',true);
    }

    private function biometric_calcule_save($check,$entry_exit,$entry_picture,$where_query = []){
        if($check == 1){
            //Entry Save INSERT
            $dataIn = [
                'hb_employ'     => $entry_exit['employ_id'],
                'hb_schedules'  => isset($entry_exit['schedules_id']) ? $entry_exit['schedules_id'] : '',
                'hb_date'       => $entry_exit['date'],
                'hb_time_entry' => $entry_exit['hour'],
                'hb_status'     => 1,
                'hb_type'       => $entry_exit['type'],
                'hb_atcreate'   => fecha(2)
            ];
        }else{
            //Exit Save UPDATE
            $dataIn = [
                'hb_time_exit' => $entry_exit['hour'],
                'hb_complete' => 2,
            ];
        }

        //obtener siempre el ultimo registro para indicar la actualizacion usuario es mas facil que solicitar un where
        $last_register = $this->general->query("select hb_id from horary_biometric WHERE hb_employ='".$where_query['hb_employ']."' and hb_complete != 2 LIMIT 1",'array',true);
        $last_where_query = [
            'hb_id'         => isset($last_register['hb_id']) ? $last_register['hb_id'] : '',
            'hb_employ'     => $where_query['hb_employ'],
            'hb_complete'   => 1,
        ];


        $insertQ = $this->general->create_update('horary_biometric',$last_where_query,$dataIn);
        $id_entry = $insertQ['data'];
        //register entry picture
        $this->general->create('horary_biometric_checker',[
            'hbc_biometric'     => $id_entry,
            'hbc_date'          => $entry_picture['date'],
            'hbc_time'          => $entry_picture['hour'],
            'hbc_time_fail'     => isset($entry_picture['time_fail']) ? $entry_picture['time_fail'] : 0,
            'hbc_picture'       => $entry_picture['picture'],
            'hbc_type_entry'    => $entry_picture['type'],
            'hbc_atcreate'      => fecha(2)
        ]);
    }



}