<?php

class Meeting extends CI_Controller
{

    //propiedades
    private $session_id;
    private $session_token;
    private $user_data;
    private $controllName = 'meeting';
    private $controller   = 'meeting/';
    public $project;
    private $result = array();

    public function __construct(){
        parent::__construct();


        $this->session_id     = $this->session->userdata('user_id');
        $this->session_token  = $this->session->userdata('user_token');
        $this->project        = $this->config->config['project'];

        //validacion de acceso
        auth();

        $this->user_data = $this->general->get('users',array('u_id' => $this->session_id));
    }

    function index() {
        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => 'Reunión Diaria',
            'user'          => $this->user_data
        );
        $fecha = fecha(1);
        $data_body = array(
            'fecha' => $fecha,

            'crud' => array(
                'url_modals'             => base_url("modal/"),
                'url_call'               => base_url("{$this->controller}call_meeting"),
            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/meeting.js','plugins/numero/autoNumeric.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('meeting/v_meeting_user',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }

    //admon
    function admon() {
        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => 'Reunión Diaria Administración',
            'user'          => $this->user_data
        );

        $fecha = fecha(1);
        $data_body = array(
            'results'   => $this->general->get('meeting_daily',['m_day' => $fecha],'array'),
            'days'      => $this->geetmetinginfo($fecha),
            'opcations' => $this->geetmetinOcupationginfo($fecha),
            'communication'      => $this->general->all_get('meeting_communication',['mc_date' => $fecha]),
//            'events'    => $this->general->query("select p.b_id As id,p.b_type,p.b_observation,pd.bd_id As id_day,p.b_event_name As 'name',r.r_name As 'name_room',p.b_cpax,p.b_client_name,pd.bd_description,pd.bd_day,pd.bd_houri As 'hour1',pd.bd_hourf As 'hour2' from booking As p
//     LEFT JOIN booking_events As e ON p.b_id=e.e_proforma
//    JOIN booking_days As pd ON p.b_id=pd.bd_booking
//    JOIN rooms As r ON pd.bd_room=r.r_id
//    WHERE   p.b_type IN(2,4) and pd.bd_day = '".$fecha."' and
//        (p.b_type = 2 AND p.b_status = 3) OR
//        (p.b_type = 4)
//
//    GROUP BY p.b_id,pd.bd_id"),
            'events'    => $this->general->query("
            SELECT
    p.b_id AS id,
    p.b_type,
    p.b_observation,
    pd.bd_id AS id_day,
    p.b_event_name AS 'name',
    r.r_name AS 'name_room',
    p.b_cpax,
    p.b_client_name,
    pd.bd_description,
    pd.bd_day,
    pd.bd_houri AS 'hour1',
    pd.bd_hourf AS 'hour2'
FROM
    booking AS p
LEFT JOIN
    booking_events AS e ON p.b_id = e.e_proforma
JOIN
    booking_days AS pd ON p.b_id = pd.bd_booking
JOIN
    rooms AS r ON pd.bd_room = r.r_id
WHERE
    (
        (p.b_type = 2 AND p.b_status = 3) OR
        (p.b_booking_type =2 AND p.b_type = 3 AND p.b_status = 1) OR
        (p.b_booking_type =1 AND p.b_type IN(1,2) AND p.b_status = 1) OR
        (p.b_type = 4)
       
    )
    AND pd.bd_day = '".$fecha."'
GROUP BY
    p.b_id, pd.bd_id;
"),
        'fecha' => ($fecha),
            'crud' => array(
                'url_modals'             => base_url("modal/"),
                'url_save'               => base_url("{$this->controller}save_meeting"),
                'url_send_whatsapp'      => base_url("{$this->controller}call_whatsapp"),
                'url_send_whatsapp_message'      => base_url("{$this->controller}call_whatsapp_message"),
            )
        );
//        OR (p.b_type = 1 AND p.b_status = 1) -- Incluyendo el tipo 3
        $data_foot = array('script_level' => array('cr/crud_data.js','cr/meeting.js','plugins/numero/autoNumeric.js','plugins/htmlcanva/html2canvas.js','cr/horary.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('meeting/v_meeting_admin',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }

    private function call_whatsapp_login(){
        $user = $this->project['wts'];

        $this->load->library('GuzzleHttp');
        $mts = new GuzzleHttp\Client();


        //create all JSON data
        try {
            $data =    $mts->post($this->project['wts']['endpoint_login'],
                [
                    'header' => [
                        'Content-Type' => 'application/json',
                    ],
                    'json' => [
                        'email'      => $user['username'],
                        'password'   => $user['password'],
                        'rememberMe' => false,
                    ]
                ])->getBody()->getContents();
            $body = json_decode($data, true);
            return  [
                'mts' => $mts,
                'data' => isset($body['token']) ?  $body['token'] : ''
            ];
        }catch (Exception $e){
            return  [
                'mts' => $mts,
                'data' => ''
            ];
        }
    }

    function call_whatsapp_message(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('message'))){
//
//                //campos post
                $message         = $this->class_security->data_form('message');

//                $this->result = send_message_whatsapp($message);
//                $userd[] = ['u_phone' => '50688305314'];
                $userd[] = ['u_phone' => '573128624981'];
//                sendNotification('Vora',$message,$userd);

                $job_data = array(
                    'message' => $message,
                    'data' => $userd
                );
                $this->queue->enqueue('send_whatsapp', 'Notifications', $job_data);  // Agregar trabajo a la cola


                $this->result = array('success' => 1);

            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios','data' => []);
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!','data' => []);
        }
        api($this->result);
    }


    function call_whatsapp(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('data'))){
//
//                //campos post
                $picture        = $this->class_security->data_form('data');
                $filteredData = substr($picture, strpos($picture, ",") + 1);
                $fileName = 'meeting_' . $this->class_security->random() . '.png';
                $filePath = '_files/tmp/' . $fileName;
                $decodedData = base64_decode($filteredData);
                file_put_contents($filePath, $decodedData);

                $messageWhatsapp = "*Reporte Diario* - " . fecha(2);
//                $UsersNotify[] = [
//                    'u_phone' => '50672910540'
//                ];

                $callMe =   whatsapp_login();
                $token = $callMe['data'];
                if($token != ''){

                    $dataWhs = [
                        'endpoint' => $this->project['wts']['endpoint'],
                        'token' => $token
                    ];

                    sendNotificationFormFileGroup($messageWhatsapp,$filePath,$dataWhs);
                }
                $this->result = array('success' => 1);
            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios','data' => []);
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!','data' => []);
        }
        api($this->result);
    }
    function call_meeting(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('fecha'))){

                //campos post
                $fecha        = $this->class_security->data_form('fecha','str2');

                $meetingValidate = $this->general->get('meeting_daily',['m_day' => $fecha],'array');

                //Validate if exist meeting register
                if (isset($meetingValidate) and count($meetingValidate) > 0) {

                    $events = $this->general->query("SELECT
    p.b_id AS id,
    p.b_type,
    p.b_observation,
    pd.bd_id AS id_day,
    p.b_event_name AS 'name',
    r.r_name AS 'name_room',
    p.b_cpax,
    p.b_client_name,
    pd.bd_description,
    pd.bd_day,
    pd.bd_houri AS 'hour1',
    pd.bd_hourf AS 'hour2'
FROM
    booking AS p
LEFT JOIN
    booking_events AS e ON p.b_id = e.e_proforma
JOIN
    booking_days AS pd ON p.b_id = pd.bd_booking
JOIN
    rooms AS r ON pd.bd_room = r.r_id
WHERE
    (
        (p.b_type = 2 AND p.b_status = 3) OR
        (p.b_booking_type =2 AND p.b_type = 3 AND p.b_status = 1) OR
        (p.b_booking_type =1 AND p.b_type IN(1,2) AND p.b_status = 1) OR
        (p.b_type = 4)
     
    )
    AND pd.bd_day = '".$fecha."'
GROUP BY
    p.b_id, pd.bd_id;");
//               OR    (p.b_type = 1 AND p.b_status = 1)  -- Incluyendo el tipo 3
                    $data = [
                        'meeting' => $meetingValidate,
                        'days'      => $this->geetmetinginfo($fecha),
                        'opcations' => $this->geetmetinOcupationginfo($fecha),
                        'communication'      => $this->general->all_get('meeting_communication',['mc_date' => $fecha]),
                        'events2'    => array_values(array_merge($this->class_security->filter_array_simple($events,'b_type','2'),$this->class_security->filter_array_simple($events,'b_type','1'),$this->class_security->filter_array_simple($events,'b_type','3'))),
                        'events4'    => array_values($this->class_security->filter_array_simple($events,'b_type','4')),
                    ];

                    $this->result = array('success' => 1,'data' => $data);

                }else{
                    $this->result = array('success' => 2,'msg' => 'Lo siento ese dia no tiene reuniones registradas');
                }

                //validar si ex


            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios','data' => []);
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!','data' => []);
        }
        api($this->result);
    }
    function save_meeting(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('fecha','entrada','cierre'))){

                //campos post
                $fecha           = fecha(1);
                $entrada         = $this->class_security->data_form('entrada');
                $cierre          = $this->class_security->data_form('cierre','saldo');
                $salida          = $this->class_security->data_form('salida');
                $tarifa          = $this->class_security->data_form('tarifa');
                $revpar          = $this->class_security->data_form('revpar');
                $kitcheng        = $this->class_security->data_form('kitcheng','alone');
                $opcations       = $this->class_security->data_form('opcations','alone');
                $communication   = $this->class_security->data_form('communication','alone');



                    //se valida con el dia la informacion nueva
                   $this->general->create_update('meeting_daily',['m_day' => $fecha],[
                        'm_user '                   => $this->user_data->u_id,
                        'm_day'                     => $fecha,
                        'm_entry'                   => $entrada,
                        'm_exit'                    => $salida,
                        'm_occupancy_closing'       => $cierre,
                        'm_rate_pm'                 => $tarifa,
                        'm_revpar'                  => $revpar,
                        'm_atcreate'                => fecha(2),
                    ]);


                   if(isset($opcations)){
                        foreach($opcations As $opcation){
                        $ot_day   = $opcation['day'];
                        $ot_value = $opcation['value'];
                        $data = array(
                            'mo_user'              => $this->user_data->u_id,
                            'mo_date'              => $ot_day,
                            'mo_info'              => $this->class_security->solo_numerico($ot_value),
                            'mo_atcreate'          => fecha(2),
                        );
                        $this->general->create_update('meeting_ocupation',['mo_date' => $ot_day],$data);
                    }
                   }


                    if(isset($kitcheng)){
                     foreach($kitcheng As $day){
                        $kt_day = $day['day'];
                        $kt_value = $day['value'];
                        $data = array(
                            'mk_user'              => $this->user_data->u_id,
                            'mk_date'              => $kt_day,
                            'mk_info'              => $kt_value,
                            'mk_atcreate'          => fecha(2),
                        );
                        $this->general->create_update('meeting_kitcheng',['mk_date' => $kt_day],$data);
                    }
                    }

                    $this->general->delete('meeting_communication',array('mc_date' => $fecha));//delete
                    if(isset($communication)){
                        foreach($communication As $kt_key => $kt_value){
                            $data = array(
                                'mc_user'              => $this->user_data->u_id,
                                'mc_date'              => $fecha,
                                'mc_info'              => $kt_value,
                                'mc_atcreate'          => fecha(2),
                            );
                            $this->general->create('meeting_communication',$data);
                        }
                    }

                $this->result = array('success' => 1);
            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios','data' => []);
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!','data' => []);
        }
        api($this->result);
    }

    private function geetmetinOcupationginfo($date){
        $dates = $this->class_security->getWeekFromDate($date,3);
        $datePlaceholders = "'" . implode("','", $dates) . "'";
        $sql = "SELECT mo_date, mo_info FROM meeting_ocupation WHERE mo_date IN ($datePlaceholders)";
        $datas = $this->general->query($sql);

        //recorrer el array para buscar la informacion
        $fetchedData = [];
        foreach ($datas As $row) {
            $fetchedData[$row['mo_date']] = $row['mo_info'];
        }


        $output = [];
        foreach ($dates as $date) {
//            $output[$date] = $fetchedData[$date] ?? null;
            if(isset($fetchedData[$date])){
                $output[] = [
                    'date' => $date,
                    'info' => $fetchedData[$date]
                ];
            }else{
                $output[] = [
                    'date' => $date,
                    'info' => ''
                ];
            }
        }
//        print_r($output);

        return $output;
    }

    private function geetmetinginfo($date){
        $dates = $this->class_security->getWeekFromDate($date);
        $datePlaceholders = "'" . implode("','", $dates) . "'";
        $sql = "SELECT mk_date, mk_info FROM meeting_kitcheng WHERE mk_date IN ($datePlaceholders)";
        $datas = $this->general->query($sql);

        //recorrer el array para buscar la informacion
        $fetchedData = [];
        foreach ($datas As $row) {
            $fetchedData[$row['mk_date']] = $row['mk_info'];
        }


        $output = [];
        foreach ($dates as $date) {
//            $output[$date] = $fetchedData[$date] ?? null;
            if(isset($fetchedData[$date])){
                $output[] = [
                    'date' => $date,
                    'info' => $fetchedData[$date]
                ];
            }else{
                $output[] = [
                    'date' => $date,
                    'info' => ''
                ];
            }
        }
//        print_r($output);

        return $output;
    }

}