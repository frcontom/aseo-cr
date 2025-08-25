<?php

class Booking extends CI_Controller
{
    //propiedades
    private $session_id;
    private $session_token;
    private $user_data;
    private $controllName = 'Booking';
    private $controller   = 'booking/';

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


    //dashboard calcule
    function dashboard_cacule(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('date'))){


                $query_divs = $this->general->query("
                SELECT
                    COUNT(CASE WHEN b.b_booking_type = 1 THEN 1 ELSE NULL END) AS total_reserva,
                    COUNT(CASE WHEN b.b_booking_type = 2 THEN 1 ELSE NULL END) AS total_restaurante,
                    COUNT(CASE WHEN b.b_booking_type = 3 THEN 1 ELSE NULL END) AS total_proforma,
                    COUNT(CASE WHEN b.b_booking_type = 4 AND b.b_status != 4 THEN 1 ELSE NULL END) AS total_eventos,
                    COUNT(CASE WHEN b.b_booking_type = 4 AND b.b_status = 4 THEN 1 ELSE NULL END) AS total_canceladas
                FROM
                    booking b
                WHERE
                    DATE_FORMAT(b.b_atcreate, '%Y-%m') = '2024-11'
                ",'array',true,true);

                $query_all_amount = $this->general->query("
                WITH meses AS (
                    SELECT '2024-01' AS mes
                    UNION ALL SELECT '2024-02'
                    UNION ALL SELECT '2024-03'
                    UNION ALL SELECT '2024-04'
                    UNION ALL SELECT '2024-05'
                    UNION ALL SELECT '2024-06'
                    UNION ALL SELECT '2024-07'
                    UNION ALL SELECT '2024-08'
                    UNION ALL SELECT '2024-09'
                    UNION ALL SELECT '2024-10'
                    UNION ALL SELECT '2024-11'
                    UNION ALL SELECT '2024-12'
                )
                SELECT
                    m.mes,
                    COUNT(b.b_id) AS total_eventos
                FROM
                    meses m
                LEFT JOIN
                    booking b
                ON
                    DATE_FORMAT(b.b_atcreate, '%Y-%m') = m.mes
                    AND b.b_booking_type = 4
                    AND b.b_status != 4
                GROUP BY
                    m.mes
                ORDER BY
                    m.mes;
                ");



                $query_all_users = $this->general->query("
                SELECT
                    u.u_id AS user_id,
                    u.u_name AS user_name,
                    COUNT(CASE WHEN b.b_booking_type = 1 THEN 1 ELSE NULL END) AS total_reserva,
                    COUNT(CASE WHEN b.b_booking_type = 2 THEN 1 ELSE NULL END) AS total_restaurante,
                    COUNT(CASE WHEN b.b_booking_type = 3 THEN 1 ELSE NULL END) AS total_proforma,
                    COUNT(CASE WHEN b.b_booking_type = 4 AND b.b_status != 4 THEN 1 ELSE NULL END) AS total_eventos,
                    COUNT(CASE WHEN b.b_booking_type = 4 AND b.b_status = 4 THEN 1 ELSE NULL END) AS total_canceladas,
                    COUNT(b.b_id) As total
                FROM
                    users u
                LEFT JOIN
                    booking b ON u.u_id = b.b_user
                WHERE
                   DATE_FORMAT(b.b_atcreate,'%Y-%m')  = '2024-11'
                GROUP BY
                    u.u_id, u.u_name
                HAVING total > 0
                ORDER BY
                    u.u_id
                 ");

                $result = [
                    'all_divs' => $query_divs,
                    'all_users' => $query_all_users,
                    'all_amount' => $query_all_amount,
                ];

                $this->result = array('success' => 1,'data' => $result);

            }

        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!','data' => []);
        }
        api($this->result);
    }

    //Booking
    function reporting_events() {
        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => 'Reporte Eventos',
            'user'          => $this->user_data
        );

        $data_body = array(
            'properties' => $this->general->all_get('properties'),
            'crud' => array(
                'url_modals'             => base_url("modal/"),
                'datatable'     => base_url("{$this->controller}datatable_reporting_events"),
            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/datatable_ajax.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('booking/reporting/v_events',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }

    function reporting_booking() {
        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => 'Reporte de Reservas',
            'user'          => $this->user_data
        );

        $data_body = array(
            'crud' => array(
                'url_modals'    => base_url("modal/"),
                'datatable'     => base_url("{$this->controller}datatable_reporting_booking"),
            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/datatable_ajax.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('booking/reporting/v_booking',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }

    function datatable_reporting_events(){
        $all_input = $this->input->post("draw");
        if(isset($all_input)){
            $draw       = intval($this->input->post("draw"));
            $start      = intval($this->input->post("start"));
            $length     = intval($this->input->post("length"));
            $busqueda   = $this->input->post("search");
            $order      = $this->input->post("order");
            $dato      = $this->input->post("dato");
            $valor      =  (isset($busqueda)) ? $busqueda['value'] : '';
            $fecha1     = $this->class_security->data_form('fecha1');
            $fecha2     = $this->class_security->data_form('fecha2');
            $type       = $this->class_security->data_form('type','int');
        }else{
            $draw = 0;
            $start = 0;
            $length = 5;
            $valor = '';
            $dato = '';
            $fecha1   = '';
            $fecha2   = '';
            $type   = '';
            $order = array();
        }
        $data = array();

        //tabla
        $consulta_primary =  "select DISTINCT b_id,b_code,se.s_name,pt_name,b_event_name,b_client_name,bd_day,b_day,b_cpax  from booking  As p
      LEFT JOIN seller AS se ON p.b_seller=se.s_id
     JOIN users As u ON p.b_user=u.u_id
     JOIN booking_events As e ON p.b_id=e.e_proforma and e.e_status != 0
     LEFT JOIN booking_days As bd ON p.b_id=bd.bd_booking
    JOIN properties As pp ON p.pf_propertie=pp.pt_id
    WHERE b_event_name LIKE '%".$valor."%' 
    AND p.pf_propertie = '".$type."'
    and bd.bd_day between '".$fecha1."' AND '".$fecha2."'
    and p.b_booking_type = 4 
    and p.b_status = 3  
    GROUP BY b_id,b_code,se.s_name,pt_name,b_event_name,b_client_name,bd_day,b_day,b_cpax";
        $dataget         = $this->general->query("{$consulta_primary}  LIMIT $start,$length",'obj');
        $query_count     = $this->general->query($consulta_primary);
        $total_registros = count($query_count);

        $this->load->helper('calcule_booking');

        foreach($dataget as $rows){
            $id         = ($rows->b_id);
            $code       = $this->class_security->decodificar($rows->b_code);
            $venedor    = $this->class_security->decodificar($rows->s_name);
            $hotel    = $this->class_security->decodificar($rows->pt_name);
            $filial     = $this->class_security->decodificar($rows->b_event_name);
            $nombre     = $this->class_security->decodificar($rows->b_client_name);
            $days       = $this->class_security->decodificar($rows->b_day);
            $count      = $this->class_security->decodificar($rows->b_cpax);
            $bd_day      = $this->class_security->decodificar($rows->bd_day);




            //calcule the status
            $datas_packages = $this->general->queryDinamycal('booking_package As p',[['package As pg','p.pfp_package=pg.p_id']],['pfp_proforma' => $id],'','object',false);
            $datas_rooms    = $this->general->all_get('booking_room',['pfr_proforma' => $id],[],'json');
//            print_r($datas_packages);
            $total_rooms = calcule_rooms($datas_rooms);
            $total_package = calcule_package($datas_packages);
            $total_final = $total_rooms + $total_package;





            $data[]= array(
                $code,
                $venedor,
                $hotel,
                $filial,
                $nombre,
                $days,
                $count,
                $bd_day,
                '$'.number_format($total_final,2,'.',','),
            );

        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $total_registros,
            "recordsFiltered" => $total_registros,
            "data" => $data
        );

        apirest($output);
        exit();
    }

    function datatable_reporting_booking(){
        $all_input = $this->input->post("draw");
        if(isset($all_input)){
            $draw       = intval($this->input->post("draw"));
            $start      = intval($this->input->post("start"));
            $length     = intval($this->input->post("length"));
            $busqueda   = $this->input->post("search");
            $order      = $this->input->post("order");
            $dato       = $this->input->post("dato");
            $valor      =  (isset($busqueda)) ? $busqueda['value'] : '';
            $fecha1     = $this->class_security->data_form('fecha1');
            $fecha2     = $this->class_security->data_form('fecha2');
            $type       = $this->class_security->data_form('type','int');

        }else{
            $draw   = 0;
            $start  = 0;
            $length = 5;
            $valor  = '';
            $dato   = '';
            $fecha1   = '';
            $fecha2   = '';
            $type   = '';
            $order  = array();
        }

        $data = array();

        //tabla
        $consulta_primary =  "select p.*,u.u_name,bd.bd_day,bd.bd_houri,bd.bd_type from booking  As p
      JOIN users As u ON p.b_user=u.u_id
     LEFT JOIN booking_days As bd ON p.b_id=bd.bd_booking
        WHERE b_event_name LIKE '%".$valor."%' 
            and p.b_status = 1
            and p.b_type = '".$type."'
            and bd.bd_day between '".$fecha1."' AND '".$fecha2."'
            GROUP BY p.b_id,bd.bd_day,bd.bd_houri,bd.bd_type 
           order by p.b_id DESC
           ";

        $dataget         = $this->general->query("{$consulta_primary}  LIMIT $start,$length",'obj');
        $query_count     = $this->general->query($consulta_primary);
        $total_registros = count($query_count);


        foreach($dataget as $rows){
            $u_name         = $this->class_security->decodificar($rows->u_name);
//            $seller         = $this->class_security->decodificar($rows->b_seller);
            $event_name     = $this->class_security->decodificar($rows->b_event_name);
            $client_name    = $this->class_security->decodificar($rows->b_client_name);
            $type           = $this->class_data->bookingType[$rows->b_type];
            $type_box           = $this->class_security->array_data($rows->bd_type,$this->class_data->typeBox);


            $data[]= array(
                $u_name,
                $type,
                $event_name,
                $client_name,
                $type_box,
                $this->class_security->datehuman($rows->bd_day),
                $rows->b_cpax,
                '$ '.number_format($rows->b_total ?? 0),

            );

        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $total_registros,
            "recordsFiltered" => $total_registros,
            "data" => $data
        );

        apirest($output);
        exit();
    }


    function index() {
        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => 'Reserva',
            'user'          => $this->user_data
        );

        $data_body = array(
            'results' => $this->general->query("select b.*,u.u_name,bd.bd_day,bd.bd_houri from booking AS b  JOIN users as u ON u.u_id = b.b_user LEFT JOIN booking_days As bd ON b.b_id=bd.bd_booking
         where b.b_status = 1 and b.b_booking_type = 1  and bd.bd_day >= '".fecha(1)."' GROUP BY b.b_id,bd.bd_day,bd.bd_houri order by b.b_id DESC",'object'),
//            'results' => $this->general->query('select *,FLOOR(TIMESTAMPDIFF(MINUTE , b.b_atcreate, NOW()) / 60) As hour from booking AS b JOIN users as u ON u.u_id = b.b_user where b.b_status = 1 and b.b_booking_type != 2   HAVING hour <= 24','object'),
            'resultsR' => $this->general->query("select * from booking AS b LEFT JOIN users as u ON u.u_id = b.b_user LEFT JOIN booking_days As d ON b.b_id=d.bd_booking  where b.b_status = 1 and b_booking_type = 2 and d.bd_day >= '".fecha(1)."'" ,'object'),
            'crud' => array(
                'url_modals'             => base_url("modal/"),
                'url_save'               => base_url("{$this->controller}save_booking"),
                'url_ban'               => base_url("{$this->controller}ban_booking"),
                'url_delete'             => base_url("{$this->controller}delete_booking"),
                'url_booking_convert'    => base_url("{$this->controller}convert_booking"),
                'url_validate_date'      => base_url("{$this->controller}validate_date_event"),
            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/proformas.js','plugins/countdown/jquery.countdown.js','plugins/numero/autoNumeric.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('booking/v_booking',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }

    function save_booking(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('type','day','cpax'))){

                //campos post
                $data_id        = $this->class_security->data_form('data_id','decrypt_int');
                $name           = $this->class_security->data_form('name');
                $email          = $this->class_security->data_form('email');
                $user           = $this->class_security->data_form('user');
                $day            = $this->class_security->data_form('day');
                $type           = $this->class_security->data_form('type');
                $observation    = $this->class_security->data_form('observation');
                $phone          = $this->class_security->data_form('phone');
                $days           = $this->class_security->data_form('days','alone');
                $seller         = $this->class_security->data_form('seller');
                $cpax           = $this->class_security->data_form('cpax');
                $imagen         = $this->class_security->upload_image('imagen','_files/events/');
                //adulto
                $a_cpax           = $this->class_security->data_form('a_cpax');
                $a_amount         = $this->class_security->data_form('a_amount','saldo');
                //niño
                $n_cpax           = $this->class_security->data_form('n_cpax');
                $n_amount         = $this->class_security->data_form('n_amount','saldo');
                $total           = $this->class_security->data_form('total','saldo');

                //bill restaurant
                $bill_number         = $this->class_security->data_form('bill_number');
                $bill_amount         = $this->class_security->data_form('bill_amount','saldo');

                //bill restaurant
                $agence_guia         = $this->class_security->data_form('agence_guia');
                $agence_amount       = $this->class_security->data_form('agence_amount','saldo');


//                if(isset($days)  and count($days)  > 0){

                    //validate days
                    /*
                     * solo se debe validar cuando es un pre-evento o evento
                     * */
                    if(in_array($type,[1,2])){
                        $daysResolved = $this->class_security->day_reserved($data_id,$days);
                        if(isset($daysResolved) and count($daysResolved) > 0){
                            $this->result = array('success' => 2,'msg' => 'Debes validar los dias ya que Algunos ya se encuentran Tomados','data' => $daysResolved);
                            api($this->result);
                            exit;
                        }
                    }


                    //validar la duplicidad del username or emailes
                    $old = $this->general->get('booking',['b_id' => $data_id]);
                    $user_id = $old->b_user ?? $this->user_data->u_id;
                    $add =  $this->general->create_update('booking',['b_id' => $data_id],[
                        'b_user '               => $user_id,
                        'b_code'                => $this->class_security->generate_code(7),
                        'b_book_at'             => $type,
                        'b_day'                 => $day,
                        'b_cpax'                => $cpax,

                        'b_event_name'          => $name,
                        'b_client_name'         => $user,
                        'b_client_email'        => $email,
                        'b_client_phone'        => $phone,

                        'b_cpax_a'              => $a_cpax,
                        'b_amount_a'            => $a_amount,
                        'b_cpax_n'              => $n_cpax,
                        'b_amount_n'            => $n_amount,
                        'b_total'               => $total,

                        'b_seller'               => $seller,

                        'b_observation'         => $observation,


                        'bill_number'      => $bill_number,
                        'bill_amount'      => $bill_amount,

                        'b_agence_guia'      => $agence_guia,
                        'b_agence_amount'      => $agence_amount,

                        'b_booking_type'   => ($type == 3) ? 2 : 1,
                        'b_type'           => $type,

                        'b_status'        => 1,
                        'b_atcreate'      => fecha(2),
                    ]);
                    $id = $add['data'];


                if($imagen != ''){
                    $this->general->create_update('booking_events',['e_proforma' => $id],[
                        'e_proforma'      => $id,
                        'e_image'         => $imagen,
                        'e_status'        => 0,
                        'e_atcreate'      => fecha(2),
                    ]);
                }


                //update add element seller



                if($seller != '' and $bill_amount != ''){
                    $alone = $this->general->query("select distinct sp.sp_seller,wp.wp_percentaje_day,wp.wp_percentaje_major from seller_percentage As sp
 JOIN walk_percentaje As wp ON sp.sp_percentage=wp.wp_id  where sp.sp_seller='".$seller."' and wp.wp_type=2",'array',true);

                    $tip_cacule = 0;
                    if(isset($alone) and count($alone) > 0){

                        $percentage = $alone['wp_percentaje_day'];

                        if($percentage > 0){
                            $calcule_percentaje =  $percentage / 100;
                            $tip_cacule =  $bill_amount * $calcule_percentaje;


                        }

                        $this->general->update('booking',['b_id' => $id],[
                            'bill_percentage'    => $percentage,
                            'bill_tip'           => $tip_cacule,
                            'bill_approved'      => 0,
                        ]);

                    }
                }




                    //delete or day events clean booking
                    $this->general->delete('booking_days',['bd_booking' => $id]);


                    //delete all package of proforma
                    if(isset($days) and count($days) > 0){
                        foreach($days As $day){
                            $date_int        = $day['date'];
                            $hour_int        = $day['hour1'];
                            $observation_int = $day['observation'];
                            $box_account     = isset($day['box_account']) ? $day['box_account'] : '';
                            $box_type        = (isset($day['box_type']) and $day['box_type'] != '') ? $this->class_data->typeBox[$day['box_type']] : '';
                            $box_type_id     = (isset($day['box_type']) and $day['box_type'] != '') ? $day['box_type'] : '';
                            $data = array(
                                'bd_booking'           => $id,
                                'bd_day'                => $date_int,
                                'bd_room'               => isset($day['room'])  ? $day['room'] : '',
                                'bd_houri'              => $hour_int,
                                'bd_hourf'              => isset($day['hour2']) ? $day['hour2'] : '',
                                'bd_type'               => $box_type_id,
                                'bd_account'            => $box_account,
                                'bd_description'        => $observation_int,
                                'bd_atcreated'          => fecha(2),
                            );

                            $this->general->create('booking_days',$data);



                            if($type == 4){
                                //SendMailer
                                if(in_array($box_type_id,[2,3])){
                                    $time_fornat = $this->class_security->format_date("{$date_int} {$hour_int}","d-m-Y  g:i:s A");
                                    $message_wts = "El evento: *{$name}* \nTipo: *{$box_type}* \nrequiere cantidad de: *{$box_account}* \nEntrega: *{$time_fornat}*\nObservación: *{$observation_int}*";
                                    send_message_whatsapp($message_wts,'endpoint_box');
                                }
                            }

                        }
                    }

//                }

                $this->result = array('success' => 1);

            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios','data' => []);
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!','data' => []);
        }
        api($this->result);
    }

    function ban_booking(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('data_id','observation'))){

                //campos post
                $data_id        = $this->class_security->data_form('data_id','decrypt_int');
                $observation    = $this->class_security->data_form('observation');


                    //validar la duplicidad del username or emailes
                    $this->general->update('booking',['b_id' => $data_id],['b_status' => 88]);

                    $this->general->create_update('booking_ban',['bb_booking' => $data_id],[
                        'bb_booking' => $data_id,
                        'bb_user' => $this->user_data->u_id,
                        'bb_observation' => $observation,
                        'bb_atcreate' => fecha(2)
                    ]);

                //send email
                $data = $this->general->all_get_join('booking As b',
                    [['users as u','b.b_user=u.u_id']],
                    ['b.b_id' => $data_id],[],'alone');

                $queryEmail[] = ['u_email' => 'eventos@gpr.cr'];

                $emai_data = [
                    'create_for'    => $data->u_name,
                    'cancel_for'    => $this->user_data->u_name,
                    'code'          => $data->b_code,
                    'name_event'    => $data->b_event_name,
                    'name_client'   => $data->b_client_name,
                    'type'          => $this->class_data->bookingType[$data->b_type],
                    'observation'   => $observation,
                ];


                $job_data_mail = array(
                    'data'        => $emai_data,
                    'message'     => 'Se cancelo el evento #' . $data->b_code,
                    'template'    => 'emails/v_email_event_ban',
                    'attachment'  => [],
                    'emails'      => $queryEmail
                );
                $this->queue->enqueue('send_mailer', 'Mailer', $job_data_mail);  // Agregar trabajo a la cola
//                $this->class_security->send_mailer($queryEmail,'Se cancelo el evento #' . $data->b_code,'emails/v_email_event_ban',[],$emai_data);


                $this->result = array('success' => 1);

            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios','data' => []);
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!','data' => []);
        }
        api($this->result);
    }

    function convert_booking(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){

                //campos post
                $id        = $this->class_security->data_form('id','decrypt_int');

                $booking      = $this->general->get('booking',['b_id' => $id]);
//                $booking_days = $this->general->all_get('booking_days',['bf_booking' => $id]);

//                $status = (isset($booking->b_type) and $booking->b_type == 2) ? 1 : 2;

                //create proforma
//                $add =  $this->general->create_update('proforma',[],[
//                    'pf_user'         => $this->session_id,
//                    'pf_booking'      => $booking->b_id,
//                    'pf_code'        => $booking->b_code,
//                    'pf_name'         => $booking->b_name,
//                    'pf_days'         => $booking->b_day,
//                    'pf_person_name'  => $booking->b_user,
//                    'pf_count'        => $booking->b_cpax,
//                    'pf_email'        => $booking->b_email,
//                    'pf_telephone'    => $booking->b_phone,
//                    'pf_observation'  => $booking->b_observation,
//                    'pf_status'       => 1,
//                    'pf_created_at'   => fecha(2),
//                ]);
//
//                $id_proforma = $add['data'];
//
//                //create proforma days
//                foreach($booking_days As $day){
//
//
//                    $data = array(
//                        'pf_proforma'           => $id_proforma,
//                        'pf_day'                => $day->bf_day,
//                        'pf_room'               => $day->bf_room,
//                        'pf_houri'              => $day->bf_houri,
//                        'pf_hourf'              => $day->bf_hourf,
//                        'pf_description'        => $day->bf_description,
//                        'pf_atcreated'          => fecha(2),
//                    );
//                    $this->general->create('proforma_days',$data);
//                }

                //create or event
//                if($status == 2){
//                    $data = [
//                        'e_proforma' => $id_proforma,
//                        'e_company_name' => '',
//                        'e_company_number' => '',
//                        'e_manager_contact' => '',
//                        'e_manager_key' => '',
//                        'e_type_person' => '',
//                        'e_phone' => '',
//                        'e_address' => '',
//                        'e_email' => '',
//                        'e_table' => '',
//                        'e_tableline' => '',
//                        'e_mounting' => '',
//                        'e_image' => '',
//                        'e_status' => 2,
//                        'e_atcreate' => fecha(2)
//                    ];
//
                   $this->general->update('booking',['b_id' => $id],['b_booking_type' => 3]);
                   $this->general->create_update('booking_events',['e_proforma' => $id],['e_proforma' => $id,'e_status' => 2,'e_atcreate' => fecha(2)]);
//                }

                //update booking

                $this->result = array('success' => 1);
            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function delete_booking(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){
                $id = $this->class_security->data_form('id','decrypt_int');
                if(strlen($id) >= 1){
                    if($this->general->exist('booking',array('b_id' => $id))){
                        $this->result =  $this->general->update('booking',array('b_id' => $id),['b_status' => 99]);
                    }else{
                        $this->result = array('success' => 2,'msg' => 'Dato no existe');
                    }
                }else{
                    $this->result = array('success' => 2,'msg' => 'Dato no existe');
                }
            }else{
                $this->result = array('success' => 2,'msg' => 'Falta el dato');
            }

        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    //proforma
    function proformas(){

        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => $this->controllName,
            'controller'    => 'Dashboard',
            'user'          => $this->user_data,
            'style_level'   => array('vendor/summernote/summernote.css')
        );

        $data_body = array(
            'crud' => array(
                'url_modals'            => base_url("modal/"),
                'url_validate_date'     => base_url("{$this->controller}validate_date_event"),
                'url_save'              => base_url("{$this->controller}save_proforma"),
                'url_delete'            => base_url("{$this->controller}delete_proforma"),
                'url_package_get'       => base_url("{$this->controller}get_package"),
                'url_convert_proforma'  => base_url("{$this->controller}convert_proforma"),
                'datatable'             => base_url("{$this->controller}datatable_proforma"),
            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/proformas.js','plugins/numero/autoNumeric.js','vendor/summernote/js/summernote.min.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('events/v_proformas',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }
    function pdf_proforma($idQ = ''){

        $id = desencriptar($idQ);

        if($id != ''){
//            $proform = $this->general->queryDinamycal('proforma',['pf_id' => $id]
            $proform = $this->general->queryDinamycal('booking As p',[['booking_days As d','p.b_id = d.bd_booking','LEFT']],['p.b_id' => $id],"p.*,MIN(concat(d.bd_day,' ', d.bd_houri)) As initial,MAX(concat(d.bd_day, ' ', d.bd_hourf)) As finish",'object',true);
            $validateCast = isset($proform->b_id) ? (Array)$proform : [];
            if(count($validateCast) > 2){

                $this->load->library('Pdf');

                $data = [
                    'proforma' => $proform,
                    'package'  => $this->general->queryDinamycal('booking_package As p',[['package As pg','p.pfp_package=pg.p_id']],['pfp_proforma' => $id],'','object',false),
                    'rooms'    => $this->general->queryDinamycal('booking_room As p',[['rooms As r','p.pfr_room=r.r_id']],['pfr_proforma' => $id],'','object',false),
                    'days'     => $this->general->queryDinamycal('booking_days As d',[['rooms As r','d.bd_room=r.r_id']],['bd_booking' => $id],'','json',false),
//                    'days'     => $this->general->all_get('booking_days',['bd_booking' => $id]),
                ];
                $nombre = $data['proforma']->b_event_name;
                $pro_nombre = "proforma_{$nombre}.pdf";


//               $this->pdf->pdfview('pdf/pdf_proforma',$data,$pro_nombre);
                  $this->pdf->pdfDownload('pdf/pdf_proforma',$data,$pro_nombre);

            }else{
//                redirect(base_url('events/proformas'));
            }
        }
        exit;
    }
    function save_proforma(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('type_event','core','user_query','name','day','email','money','count'))){

                //campos post
                $data_id        = $this->class_security->data_form('data_id','decrypt_int');
                $user_query     = $this->class_security->data_form('user_query','decrypt_int');
                $propertie      = $this->class_security->data_form('propertie');
                $user_name      = $this->class_security->data_form('user');
                $type_event     = $this->class_security->data_form('type_event');
                $name           = $this->class_security->data_form('name');
                $dayq           = $this->class_security->data_form('day');
                $money           = $this->class_security->data_form('money');
                $count          = $this->class_security->data_form('count');
                $type           = $this->class_security->data_form('type');
                $ofert          = $this->class_security->data_form('ofert');
                $type_person    = $this->class_security->data_form('person');
                $document       = $this->class_security->data_form('document');
                $email          = $this->class_security->data_form('email');
                $telephone      = $this->class_security->data_form('telephone');
                $observation      = $this->class_security->data_form('observation');
                $status      = $this->class_security->data_form('core',1);
                $package        = $this->class_security->data_form('package','alone');
                $room        = $this->class_security->data_form('room','alone');
                $days        = $this->class_security->data_form('days','alone');
                $imagen              = $this->class_security->upload_image('imagen','_files/events/');
                $code        = $this->class_security->data_form('code','str',$this->class_security->generate_code(7));

//                if((isset($package) and isset($days)) and (count($package)  > 0 and count($days)  > 0)){
//                if(isset($days)  and count($days)  > 0){

                    //validate days
                    $requiredFields = ['date', 'room', 'hour1', 'hour2'];
                    if(isset($days[0]['date'])){
                        foreach ($days as $solicitud) {
                        foreach ($requiredFields as $field) {
                            if (!isset($solicitud[$field]) || empty($solicitud[$field])) {
                                // Retornar un mensaje inmediatamente si falta algún dato
                                $this->result = array('success' => 2, 'msg' => 'Debes llenar la información de los dias ya que esta es obligatorio', 'data' => []);
                                api($this->result);
                                exit;
                            }
                        }
                    }
                    }

                    $daysResolved = $this->class_security->day_reserved($data_id,$days);
                    if(isset($daysResolved) and count($daysResolved) > 0){
                        $this->result = array('success' => 2,'msg' => 'Debes validar los dias ya que Algunos ya se encuentran Tomados','data' => $daysResolved);
                        api($this->result);
                        exit;
                    }



                $old = $this->general->get('booking',['b_id' => $data_id]);
                $user_id = $old->b_user ?? $this->user_data->u_id;

                $data = array(
                        'b_user'         => $user_id,
                        'b_seller'       => $user_query,
                        'b_code'         => $code,
                        'b_type_sl'      => $type_event,
                        'b_book_at'      => 2,
                        'b_day'          => $dayq,
                        'b_cpax'         => $count,
                        'pf_propertie'   => $propertie,

                        'b_event_name'   => $name,

                        'b_client_type'  => $type_person,
                        'b_client_name'  => $user_name,
                        'b_client_email'        => $email,
                        'b_client_phone'    => $telephone,
                        'b_client_document'     => $document,

                        'b_offer_validity'        => $ofert,
                        'b_currency'           => $money,
                        'b_mounting_type'      => $type,
                        'b_type'               => 3,
                        'b_status'       => $status,

                        'b_observation ' => $observation,
                        'b_booking_type ' => 3,
                        'b_atcreate'   => fecha(2),
                    );
                    //validar la duplicidad del username or emailes
                    $add =   $this->general->create_update('booking',['b_id' => $data_id],$data);
                    $id = $add['data'];

                    $this->general->delete('booking_package',['pfp_proforma' => $id]);
                    $this->general->delete('booking_room',['pfr_proforma' => $id]);
                    $this->general->delete('booking_days',['bd_booking' => $id]);

                    if(isset($package)  and count($package) > 0){
                        //delete all package of proforma
                        for($i = 0 ; $i < count($package['id']);$i++){
                            $data = array(
                                'pfp_proforma'       => $id,
                                'pfp_package'        => $package['id'][$i],
                                'pfp_count'          => $package['size'][$i],
                                'pfp_price'          => $this->class_security->solo_numerico($package['price'][$i]),
                                'pfp_description'    => $package['description'][$i],
                            );
                            $this->general->create('booking_package',$data);
                        }
                    }

                    if(isset($room) and count($room) > 0){
                        //delete all package of proforma
                        for($i = 0 ; $i < count($room['id']);$i++){
                            $data = array(
                                'pfr_proforma'       => $id,
                                'pfr_room'           => $room['id'][$i],
                                'pfr_pax'          => $room['pax'][$i],
                                'pfr_count'          => $room['size'][$i],
                                'pfr_price'          => $this->class_security->solo_numerico($room['price'][$i]),
                                'pfr_iva'            => $room['iva'][$i],
                            );
                            $this->general->create('booking_room',$data);
                        }
                    }

                    if(isset($days) and count($days) > 0){
                        //delete all package of proforma
                        foreach($days As $day){
                            $data = array(
                                'bd_booking'        => $id,
                                'bd_day'            => $day['date'],
                                'bd_room'          => $day['room'],
                                'bd_houri'          => $day['hour1'],
                                'bd_hourf'           => $day['hour2'],
                                'bd_description'    => $day['observation'],
                                'bd_atcreated'      => fecha(2),
                            );
                            $this->general->create('booking_days',$data);
                        }
                    }


                    //create events upload file
                    if($imagen != ''){
                        $this->general->create_update('booking_events',['e_proforma' => $id],[
                            'e_proforma'      => $id,
                            'e_image'         => $imagen,
                            'e_status'        => 0,
                            'e_atcreate'      => fecha(2),
                        ]);
                    }

                    //saved update

                    $this->result = ['success' => 1];
//                }else{
//                    $this->result = array('success' => 2,'msg' => 'Debe Agregar al menos un dia');
//                }

            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }
    function convert_proforma(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){

                //campos post
                $id    = $this->class_security->data_form('id','decrypt_int');

                $data = $this->general->get('booking',['b_id' => $id]);
                if(empty((array)$data)){
                    $this->result = array('success' => 2,'msg' => 'Validar con la ADMON esta proforma ya se convirtio o no se puede convertir');
                }else{

                    $this->general->create_update('booking_events',['e_proforma' => $id],[
                        'e_proforma'           => $id,
//                        'e_type_person'        => $data->pf_person_type,
                        'e_type_person'        => $data->b_client_type,
                        'e_manager_key'        => $data->b_client_document,
                        'e_phone'              => $data->b_client_phone,
                        'e_email'              => $data->b_client_email,
                        'e_mounting'           => $data->b_mounting_type,
                        'e_status'             => 2,
                        'e_atcreate'           => fecha(2),
                    ]);

                }

                //validar la duplicidad del username or emailes
                $this->result  =   $this->general->update('booking',['b_id' => $id],['b_booking_type' => 4,'b_status' => 2]);


            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }
    function delete_proforma(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){
                $id = $this->class_security->data_form('id','decrypt_int');
                if(strlen($id) >= 1){
                    if($this->general->exist('booking',array('b_id' => $id))){
                        $this->result =  $this->general->update('booking',array('b_id' => $id),['b_status' => 99]);
                    }else{
                        $this->result = array('success' => 2,'msg' => 'Dato no existe');
                    }
                }else{
                    $this->result = array('success' => 2,'msg' => 'Dato no existe');
                }
            }else{
                $this->result = array('success' => 2,'msg' => 'Falta el dato');
            }

        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }
    function datatable_proforma(){
        $all_input = $this->input->post("draw");
        if(isset($all_input)){
            $draw       = intval($this->input->post("draw"));
            $start      = intval($this->input->post("start"));
            $length     = intval($this->input->post("length"));
            $busqueda   = $this->input->post("search");
            $order      = $this->input->post("order");
            $dato      = $this->input->post("dato");
            $valor      =  (isset($busqueda)) ? $busqueda['value'] : '';
        }else{
            $draw = 0;
            $start = 0;
            $length = 5;
            $valor = '';
        }
        $data = array();


        //tabla
        $consulta_primary =  "
select p.*,u.u_name,COALESCE(price,0) as price from booking As p
JOIN users As u ON p.b_user=u.u_id
 LEFT JOIN booking_days As bd ON p.b_id=bd.bd_booking
 LEFT JOIN (
     SELECT pk.pfp_proforma,SUM(pk.pfp_price*pk.pfp_count) As price from booking_package As pk GROUP BY pk.pfp_proforma
) As t ON p.b_id=t.pfp_proforma
WHERE p.b_booking_type=3 and p.b_status in(1,2) AND (p.b_event_name LIKE '%".$valor."%' OR p.b_client_name LIKE '%".$valor."%' OR p.b_atcreate LIKE '%".$valor."%' OR bd.bd_day LIKE '%".$valor."%' OR bd.bd_houri LIKE '%".$valor."%' OR bd.bd_hourf LIKE '%".$valor."%')
GROUP BY b_id   order by p.b_id DESC";

        $dataget         = $this->general->query("{$consulta_primary}  LIMIT $start,$length",'obj');
        $query_count     = $this->general->query($consulta_primary);
        $total_registros = count($query_count);


        foreach($dataget as $rows){
            $id             = encriptar($rows->b_id);
            $user_create    = $this->class_security->decodificar($rows->u_name);
            $user           = $this->class_security->decodificar($rows->b_client_name);
            $name           = $this->class_security->decodificar($rows->b_event_name);
            $days           = $this->class_security->decodificar($rows->b_day);
            $count          = $this->class_security->decodificar($rows->b_cpax);
            $price          = number_format(($rows->price >= 0) ? $rows->price : 0 ,2);
            $status         = $this->class_data->proforma[$rows->b_booking_type];
            $pdf            = base_url("{$this->controller}pdf_proforma/{$id}");
            $downloadpdf    = "<a  href='{$pdf}' class='btn btn-primary btn-sm'><i class='fas fa-download'></i></a>";


            if($rows->b_status == 1){
                $btn =   "
                 <button type='button' onclick='$(this).convert_proforma(\"{$id}\")' class='btn btn-primary btn-sm'  data-toggle='tooltip' data-placement='top' title='Convertir'><i class='fa fa-dice-d6 text-white'></i></button>
                 <button type='button' onclick='$(this).forms_modal({\"page\" : \"profome_view\",\"data1\" : \"{$id}\",\"title\" : \"Detalle de la Proforma\"})' class='btn btn-info btn-sm'  data-toggle='tooltip' data-placement='top' title='Visualizar'><i class='fa fa-eye text-white'></i></button>
                 <button type='button' onclick='$(this).forms_modal({\"page\" : \"proforma\",\"data1\" : \"{$id}\",\"title\" : \"Detalle de Proforma\"})' class='btn btn-primary btn-sm'  data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-pencil text-white'></i></button>
                 <button type='button' onclick='$(this).dell_data(\"{$id}\",\"url_delete\")' class='btn btn-danger btn-sm'  data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-times text-white'></i></button>";
            }
            elseif($rows->b_status == 3){
                $btn = "
                        <button type='button' onclick='$(this).forms_modal({\"page\" : \"profome_view\",\"data1\" : \"{$id}\",\"title\" : \"Detalle de la Proforma\"})' class='btn btn-info btn-sm'  data-toggle='tooltip' data-placement='top' title='Visualizar'><i class='fa fa-eye text-white'></i></button>
                       ";
            }
            else{

                $btn = '';
                $btn .=   "
                    <button type='button' onclick='$(this).forms_modal({\"page\" : \"profome_view\",\"data1\" : \"{$id}\",\"title\" : \"Detalle de la Proforma\"})' class='btn btn-info btn-sm'  data-toggle='tooltip' data-placement='top' title='Visualizar'><i class='fa fa-eye text-white'></i></button>
                    ";

                if(in_array($rows->b_status,[1,2])){
                    $btn .= "<button type='button' onclick='$(this).forms_modal({\"page\" : \"proforma\",\"data1\" : \"{$id}\",\"title\" : \"Detalle de Proforma\"})' class='btn btn-primary btn-sm'  data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-pencil text-white'></i></button>";
                }
            }

            $data[]= array(
//                $rows->b_booking_type,
//                $rows->b_status,
                $name,
                $user_create,
                $user,
                $days,
                $count,
//                $price,
                explode(' ',$rows->b_atcreate)[0],
//                "<span class='{$status['class']}'>{$status['title']}</span>",
                $downloadpdf,
                "<div class='btn-group'>{$btn}</div>"
            );

        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $total_registros,
            "recordsFiltered" => $total_registros,
            "data" => $data
        );

        apirest($output);
        exit();
    }

    //events
    function events(){

        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => 'Eventos',
            'user'          => $this->user_data,
            'style_level'   => array()
        );

        $data_body = array(
            'crud' => array(
                'url_modals'    => base_url("modal/"),
                'url_package_get'   => base_url("{$this->controller}get_package"),
                'url_event_lock'    => base_url("{$this->controller}lock_event"),
                'url_event_cancel'  => base_url("{$this->controller}cancel_event"),
                'url_save'          => base_url("{$this->controller}save_events"),
                'datatable'         => base_url("{$this->controller}datatable_events"),
            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','plugins/numero/autoNumeric.js','cr/manager.js','cr/proformas.js','cr/events.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('manager/v_events',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }

    function lock_event(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){

                //campos post
                $id   = $this->class_security->data_form('id','decrypt_int');


                //validate data query
                if($this->general->exist('booking_events',['e_proforma' => $id,'e_status' => 2])){
                    $this->general->update('booking_events',['e_proforma' => $id,'e_status' => 2],['e_status' => 3,'e_status_check' => 3]);
                    $this->general->update('booking',['b_id' => $id],['b_status' => 3]);

                    //send email
                    $data = $this->general->all_get_join('booking As p',
                        [['booking_events as e','p.b_id=e.e_proforma']],
                        ['p.b_id' => $id],[],'alone');

                    $queryEmail[] = ['u_email' => 'eventos@gpr.cr'];

                    $emai_data = [
                        'id' => encriptar($data->b_id),
                        'code'        => $data->b_code,
                        'event_name'  => $data->b_event_name,
                        'person_name' => $data->b_client_name,
                        'person_type' => $this->class_data->type_person[$data->b_client_type],
                        'document'    => $data->b_client_document,
                        'telephone'   => $data->b_client_phone,
                        'email'       => $data->b_client_email,
                    ];

                    //load attachment
                    $pdf = $this->pdf_events($data->b_id,'mail');
                    $fileAdd = [[$pdf,'evento.pdf']];

                    $job_data_mail = array(
                        'data'        => $emai_data,
                        'message'     => 'Nuevo Evento codigo #' . $data->b_code,
                        'template'    => 'emails/v_email_event_new',
                        'attachment'  => $fileAdd,
                        'emails'      => $queryEmail
                    );
                    $this->queue->enqueue('send_mailer', 'Mailer', $job_data_mail);  // Agregar trabajo a la cola
//                    $this->class_security->send_mailer($queryEmail,'Nuevo Evento codigo #' . $data->b_code,'emails/v_email_event_new',$fileAdd,$emai_data);

                    $this->result = array('success' => 1);
                }else{
                    $this->result = array('success' => 2,'msg' => 'Evento Ya validado');
                }


            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function cancel_event(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){

                //campos post
                $id        = $this->class_security->data_form('id','decrypt_int');
                $comment   = $this->class_security->data_form('comment');

                //validate data query
                $data = $this->general->get('booking_events',['e_proforma' => $id]);
                if(!empty((array)$data)){
                    $this->general->update('booking_events',['e_proforma' => $id],['e_status' => 4]);
                    $this->general->update('booking',['b_id' => $id],['b_status' => 4]);

                    //create cancel event
                    $this->general->create('booking_cancel',[
                        'ec_event' => $data->e_id,
                        'ec_proforma' => $data->e_proforma,
                        'ec_user' => $this->user_data->u_id,
                        'ec_observation' => $comment,
                        'ec_create' => fecha(2),
                    ]);

                    $this->result = array('success' => 1);
                }else{
                    $this->result = array('success' => 2,'msg' => 'Evento Ya validado');
                }


            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function save_events(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('data_id','manager_contact','person','telephone'))){

                //campos post

                $data_id        = $this->class_security->data_form('data_id','decrypt_int');
//                $user_query     = $this->class_security->data_form('user_query','decrypt_int');
                $user_name      = $this->class_security->data_form('user');
//                $salon          = $this->class_security->data_form('salon');
                $name           = $this->class_security->data_form('name');
                $propertie           = $this->class_security->data_form('propertie');
                $money           = $this->class_security->data_form('money');
                $count          = $this->class_security->data_form('count');
//                $type           = $this->class_security->data_form('type');
                $ofert          = $this->class_security->data_form('ofert');
                $type_person    = $this->class_security->data_form('person');
                $document       = $this->class_security->data_form('document');
                $email          = $this->class_security->data_form('email');
                $telephone      = $this->class_security->data_form('telephone');
                $observation      = $this->class_security->data_form('observation');

                //eachs

                $days        = $this->class_security->data_form('days','alone');
//                $status      = $this->class_security->data_form('core',1);
//                $package        = $this->class_security->data_form('package','alone');
//                $room        = $this->class_security->data_form('room','alone');
//                $days        = $this->class_security->data_form('days','alone');

//                --------------------------------


                $company_name        = $this->class_security->data_form('company_name');
                $company_number      = $this->class_security->data_form('company_number');
                $manager_contact     = $this->class_security->data_form('manager_contact');
                $manager_key         = $this->class_security->data_form('manager_key');
                $phone               = $this->class_security->data_form('phone');
                $address             = $this->class_security->data_form('address');
                $table               = $this->class_security->data_form('table');
                $tableline           = $this->class_security->data_form('tableline');
                $type = $mounting            = $this->class_security->data_form('mounting');
//                $special             = $this->class_security->data_form('special','alone');
//                $events              = $this->class_security->data_form('events','alone');

                $room        = $this->class_security->data_form('room','alone');
                $package             = $this->class_security->data_form('package','alone');
                $imagen              = $this->class_security->upload_image('imagen','_files/events/');
//
//                print_r($special);
//                echo '--------------------------------';
//                print_r($events);
////
//                exit;
                //validate data query
                if($this->general->exist('booking',['b_id' => $data_id])){

                    $imvp = $this->general->get('booking_events',['e_proforma' => $data_id]);
                    $imagen = ($imagen != '') ? $imagen : $imvp->e_image;


                    //booking edit data
                    $data_booking = array(
//                        'b_day'                 => $dayq,
                        'b_cpax'                => $count,
                        'pf_propertie'          => $propertie,

                        'b_event_name'          => $name,

                        'b_client_type'         => $type_person,
                        'b_client_name'         => $user_name,
                        'b_client_email'        => $email,
                        'b_client_phone'        => $telephone,
                        'b_client_document'     => $document,

                        'b_offer_validity'      => $ofert,
                        'b_currency'            => $money,
                        'b_mounting_type'       => $type,
                        'b_type'                => 2,

                        'b_observation '        => $observation,
                    );

                    //validar la duplicidad del username or emailes
                    $this->general->create_update('booking',['b_id' => $data_id],$data_booking);


                    //event data
                    $data = [
                        'e_proforma'        => $data_id,
                        'e_company_name'    => $company_name,
                        'e_company_number'  => $company_number,
                        'e_manager_contact' => $manager_contact,
                        'e_manager_key'     => $manager_key,
                        'e_type_person'     => $type_person,
                        'e_phone'           => $phone,
                        'e_address'         => $address,
                        'e_email'           => $email,
                        'e_table'           => $table,
                        'e_tableline'       => $tableline,
                        'e_mounting'        => $mounting,
                        'e_image'           => $imagen,
                        'e_status_check'    => 2,
                        'e_atcreate'        => fecha(2)
                    ];

                    $this->general->create_update('booking_events',['e_proforma' => $data_id],$data);
                    $getEvent = $this->general->get('booking_events',['e_proforma' => $data_id]);
                    $idE      = $getEvent->e_id;

//                    print_r($this->input->post());
//                    echo $idE;

                    //delete all data events
                    $this->general->delete('booking_room',['pfr_proforma' => $data_id]);
                    $this->general->delete('booking_package',['pfp_proforma' => $data_id]);//delete events service
                    $this->general->delete('booking_service_time',['et_event' => $idE]);//delete events service
                    $this->general->delete('booking_menu_special_people',['es_event' => $idE]);//delete events menu special
                    $this->general->delete('booking_days',['bd_booking' => $data_id]); //delete days


                    //days
                    if(isset($days) and count($days) > 0){
                        //delete all package of proforma
                        foreach($days As $day){
                            if(isset($day['room']) and $day['room'] != ''){
                                $data = array(
                                    'bd_booking'        => $data_id,
                                    'bd_day'            => $day['date'],
                                    'bd_room'           => $day['room'] ?? '',
                                    'bd_houri'          => $day['hour1'],
                                    'bd_hourf'          => $day['hour2'],
                                    'bd_description'    => $day['observation'],
                                    'bd_atcreated'      => fecha(2),
                                );
                                $this->general->create('booking_days',$data);


                                //each events
                                if(isset($day['events'])){
                                    $events = $day['events'];
                                    if((!empty($events)) and isset($events['description'][0]) ){
                                            for($i = 0 ;$i < count($events['description']);$i++){
                                                $this->general->create('booking_service_time',
                                                    [
                                                        'et_event'        => $idE,
                                                        'et_date'         => $day['date'],
                                                        'et_description'  => $events['description'][$i],
                                                        'et_time_service' => $events['time'][$i],
                                                        'et_tree'         => $events['id'][$i],
                                                    ]);
                                            }
                                    }
                                }

                                //each special
                                if(isset($day['special'])){
                                    $special = $day['special'];
                                    if((!empty($special)) and isset($special['name'][0])){
                                            for ($x = 0; $x < count($special['name']); $x++) {
                                                $this->general->create('booking_menu_special_people', [
                                                    'es_event'      => $idE,
                                                    'es_date'       => $day['date'],
                                                    'es_user_name'  => $special['name'][$x],
                                                    'es_menu'       => $special['menu'][$x],
                                                    'es_tree'       => $special['id'][$x],
                                                ]);
                                            }
                                    }

                                }//close special

                            }
                        }
                    }


                    if(isset($package['id'])){
                        //delete all package of proforma
                        for($i = 0 ; $i < count($package['id']);$i++){
                            $data = array(
                                'pfp_proforma'       => $data_id,
                                'pfp_package'        => $package['id'][$i],
                                'pfp_count'          => $package['size'][$i],
                                'pfp_price'          => $this->class_security->solo_numerico($package['price'][$i]),
                                'pfp_description'    => $package['description'][$i],
                            );
                            $this->general->create_update('booking_package',[
                                'pfp_proforma' => $data_id,
                                'pfp_package' => $package['id'][$i]],
                                $data);
                        }
                    }

                    if(isset($room['id'])){
                        //delete all package of proforma
                        for($i = 0 ; $i < count($room['id']);$i++){
                            $data = array(
                                'pfr_proforma'       => $data_id,
                                'pfr_room'           => $room['id'][$i],
                                'pfr_pax'          => $room['pax'][$i],
                                'pfr_count'          => $room['size'][$i],
                                'pfr_price'          => $this->class_security->solo_numerico($room['price'][$i]),
                                'pfr_iva'            => $room['iva'][$i],
                            );
                            $this->general->create('booking_room',$data);
                        }
                    }

                    //send email
                    if($imvp->e_status == 3){
                        //send email
                        $data = $this->general->all_get_join('booking As p',
                            [['booking_events as e','p.b_id=e.e_proforma']],
                            ['p.b_id' => $data_id],[],'alone');

                        $queryEmail[] = ['u_email' => 'eventos@gpr.cr'];
//                    $queryEmail[] = ['u_email' => 'feconto@gmail.com'];
                        $emai_data = [
                            'id' => encriptar($data->b_id),
                            'code'        => $data->b_code,
                            'event_name'  => $data->b_event_name,
                            'person_name' => $data->b_client_name,
                            'person_type' => $this->class_data->type_person[$data->b_client_type],
                            'document'    => $data->b_client_document,
                            'telephone'   => $data->b_client_phone,
                            'email'       => $data->b_client_email,
                        ];

                        //load attachment
                        $pdf = $this->pdf_events($data->b_id,'mail');
                        $fileAdd = [[$pdf,'evento.pdf']];

                        //save modifid
                        $this->general->create('booking_modifid',[
                            'em_user'       => $this->session_id,
                            'em_type'       => 2,
                            'em_pro_event'  => $data_id,
                            'em_code'       => $data->b_code,
                            'em_atcreate'   => fecha(2)
                        ]);



                        //job send mail
                        $job_data_mail = array(
                            'data'        => $emai_data,
                            'message'     => 'Se actualizo el Evento codigo #' . $data->b_code,
                            'template'    => 'emails/v_email_event_new',
                            'attachment'  => $fileAdd,
                            'emails'      => $queryEmail
                        );
                        $this->queue->enqueue('send_mailer', 'Mailer', $job_data_mail);  // Agregar trabajo a la cola
//                        $this->class_security->send_mailer($queryEmail,'Se actualizo el Evento codigo #' . $data->b_code,'emails/v_email_event_new',$fileAdd,$emai_data);
                    }


                    $this->result = array('success' => 1);

                }else{
                    $this->result = array('success' => 2,'msg' => 'Proforma no existe');
                }


            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function datatable_events(){
        $all_input = $this->input->post("draw");
        if(isset($all_input)){
            $draw       = intval($this->input->post("draw"));
            $start      = intval($this->input->post("start"));
            $length     = intval($this->input->post("length"));
            $busqueda   = $this->input->post("search");
            $order      = $this->input->post("order");
            $dato       = $this->input->post("dato");
            $valor      =  (isset($busqueda)) ? $this->class_security->limpiar_form($busqueda['value']) : '';
        }else{
            $draw = 0;
            $start = 0;
            $length = 5;
            $valor = '';
        }
        $data = array();

        //tabla
        $consulta_primary =  "SELECT p.*,e.*,u.u_name,bd.bd_day,r.r_name
FROM booking AS p
JOIN users AS u ON p.b_user = u.u_id
JOIN booking_events AS e ON p.b_id = e.e_proforma AND e.e_status != 0
LEFT JOIN booking_days AS bd ON p.b_id = bd.bd_booking
LEFT JOIN rooms AS r ON bd.bd_room = r.r_id
WHERE (b_event_name LIKE '%".$valor."%'
   OR p.b_code  LIKE '%".$valor."%'
  OR bd.bd_day LIKE '%".$valor."%')
  AND p.b_booking_type = 4
  AND p.b_status != 4
GROUP BY p.b_id,bd.bd_booking
ORDER BY p.b_id DESC";

        $dataget         = $this->general->query("{$consulta_primary}  LIMIT $start,$length",'obj');
        $query_count     = $this->general->query($consulta_primary);
        $total_registros = count($query_count);


        foreach($dataget as $rows){
            $id         = encriptar($rows->b_id);
            $code       = $this->class_security->decodificar($rows->b_code);
            $filial     = $this->class_security->decodificar($rows->b_event_name);
            $nombre     = $this->class_security->decodificar($rows->u_name);
            $days       = $this->class_security->decodificar($rows->b_day);
            $count      = $this->class_security->decodificar($rows->b_cpax);
            $bd_day      = $this->class_security->decodificar($rows->bd_day);
            $r_name      = $this->class_security->decodificar($rows->r_name);
//            $salon      = $this->class_security->decodificar($rows->r_name);
//            $status     = $this->class_data->proforma[$rows->b_booking_type];
            $pdf        = base_url("{$this->controller}pdf_events/{$id}");
            $downloadpdf = '-';
            $buttonOld = "";











//            <button type='button' onclick='$(this).forms_modal({\"page\" : \"events_view\",\"data1\" : \"{$id}\",\"title\" : \"Detalle de Evento\"})' class='btn btn-primary btn-sm'  data-toggle='tooltip' data-placement='top' title='Detalle edl Evento'><i class='fa fa-eye text-white'></i></button>

            $buttonClock = (in_array($rows->e_status_check,[1,2,3])) ? "<button type='button' onclick='$(this).cancel_events(\"".$id."\")' class='btn btn-danger btn-sm'  data-toggle='tooltip' data-placement='top' title='Cancelar Evento'><i class='fa fa-ban text-white'></i></button>" : '';
            $btn_edit = "<button type='button' onclick='$(this).forms_modal({\"page\" : \"events\",\"data1\" : \"{$id}\",\"title\" : \"Editar el Evento\"})' class='btn btn-primary btn-sm'  data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-pencil text-white'></i></button>";
            if(!isset($rows->e_status) or (in_array($rows->e_status,[1]))){
                //el evento es nuevo creado
                $buttonOld = $btn_edit;
            }

            elseif($rows->e_status == 2){
                //ya el evento fue modificado
                if($rows->e_status_check == 2){
                    //convertir a calendario
                    $buttonOld .=  "<button type='button' onclick='$(this).question_events(\"$id\")'  class='btn btn-success btn-sm'  data-toggle='tooltip' data-placement='top' title='Convertir Evento'><i class='fa fa-calendar text-white'></i></button>";
                }

                $buttonOld .= "
                    {$btn_edit}
                    {$buttonClock}
                    ";
//                <button type='button' onclick='$(this).delete_events(\"$id\")'  class='btn btn-danger btn-sm'  data-toggle='tooltip' data-placement='top' title='Eliminar Pre-evento'><i class='fa fa-trash text-white'></i></button>

            }

            elseif($rows->e_status == 3){
                //ya es un evento
                $downloadpdf =  "<a href='{$pdf}' class='btn btn-primary btn-sm'><i class='fas fa-download'></i></a>";

                $buttonOld = "
                    {$btn_edit}
                    {$buttonClock}
                    ";
            }

            else{
                //eror completo
                $buttonOld = "";
            }

            $data[]= array(
                $code,
                $filial,
                $nombre,
                $days,

                $count,
                $bd_day,
                $r_name,
//                $salon,
//                "<span class='{$status['class']}'>{$status['title']}</span>",
                $downloadpdf,
                "
                 <button type='button' onclick='$(this).forms_modal({\"page\" : \"profome_view\",\"data1\" : \"{$id}\",\"title\" : \"Detalle de Evento\"})' class='btn btn-info btn-sm'  data-toggle='tooltip' data-placement='top' title='Visualizar'><i class='fa fa-eye text-white'></i></button>
                 {$buttonOld}
                 "
            );

        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $total_registros,
            "recordsFiltered" => $total_registros,
            "data" => $data
        );

        apirest($output);
        exit();
    }

    function pdf_events($idQ = '',$method = 'down'){

        if($method == 'down'){
            $id = desencriptar($idQ);
        }else{
            $id = ($idQ); // ID sin encriptar
        }

        if($id != ''){
            $proform = $this->general->queryDinamycal('booking As p',[['booking_events As e','p.b_id=e.e_proforma'],['users As u','p.b_user=u.u_id']],['e.e_proforma' => $id],'','object',true);
            $validateCast = isset($proform->b_id) ? (Array)$proform : [];


            if(count($validateCast) > 2){

                $this->load->library('Pdf');
//                print_r($proform);exit;
                $idE = $proform->e_id;

                $data = [
                    'event'     => $proform,
                    'user'      => $this->general->get('users',['u_id' => $proform->b_user]),
                    'activitys' => $this->general->all_get('booking_service_time',['et_event' => $idE]),
                    'espcials' => $this->general->all_get('booking_menu_special_people',['es_event' => $idE]),
                    'days'      => $this->general->queryDinamycal('booking_days As d',[['rooms As r','d.bd_room=r.r_id']],['bd_booking' => $id],'','object',false),
                    'package'   => $this->general->queryDinamycal('booking_package As p',[['package As pg','p.pfp_package=pg.p_id']],['pfp_proforma' => $id],'','object',false),
                    'rooms'     => $this->general->queryDinamycal('booking_room As p',[['rooms As r','p.pfr_room=r.r_id']],['pfr_proforma' => $id],'','object',false),
                ];
//                print_r($data);exit;
                $nombre = $data['event']->b_event_name;
                $pro_nombre = "proforma_{$nombre}.pdf";

//                return  $this->pdf->pdfview('pdf/pdf_events',$data);

                if($method == 'down'){
                    $this->pdf->pdfDownload('pdf/pdf_events',$data,$pro_nombre);
                }elseif($method == 'mail'){
                    return  $this->pdf->pdfSteam('pdf/pdf_events',$data);
                }

            }else{
                redirect(base_url('events'));
            }
        }
        exit;
    }

    //calendar
    function calendar() {
        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => 'Reserva',
            'user'          => $this->user_data,
            'style_level'     => ['vendor/fullcalendar/css/fullcalendar.min.css']
        );

        $data_body = array(
            'crud' => array(
                'url_modals'      => base_url("modal/"),
                'url_event'       => base_url("{$this->controller}calendar_events"),
                'url_event_proforma'       => base_url("{$this->controller}calendar_events_proforma"),
                'url_event_day'   => base_url("{$this->controller}calendar_events_day"),
            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/manager.js','vendor/fullcalendar/js/fullcalendar.min.js','plugins/numero/autoNumeric.js','vendor/fullcalendar/js/es.js','cr/proformas.js','cr/events.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('manager/v_calendar',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }
    function calendar_events(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('send'))){

                // all calendar month
                $fecha = fecha(1);

                $datas = $this->general->query("
select DISTINCT r.r_id,r.r_name,r.r_color,pd.bd_day As 'pf_date',p.b_booking_type As module,'' As type
from booking As p
    LEFT JOIN booking_events As e ON p.b_id=e.e_proforma and e.e_status != 4
    JOIN booking_days As pd ON p.b_id=pd.bd_booking
    JOIN rooms As r ON pd.bd_room=r.r_id
WHERE p.b_status NOT IN(4,99,88) AND p.pf_propertie IN (SELECT up_propertie FROM users_properties WHERE up_user = {$this->user_data->u_id})
 AND
    (
        (p.b_booking_type IN (4))
           OR
        (p.b_type = 4 and p.b_booking_type = 1 and pd.bd_type != '')
    -- Si no es tipo 2 o 4, mostrar los datos sin filtro adicional
            OR
        p.b_booking_type IN (1,2)
    );
");




//                $datas = $this->general->query("
//select DISTINCT r.r_id,r.r_name,r.r_color,pd.bd_day As 'pf_date',p.b_booking_type As module,'' As type
//from booking As p
//    LEFT JOIN booking_events As e ON p.b_id=e.e_proforma and e.e_status != 4
//    JOIN booking_days As pd ON p.b_id=pd.bd_booking
//    JOIN rooms As r ON pd.bd_room=r.r_id
//WHERE p.b_status NOT IN(4,99) and p.b_booking_type IN(1,2,4)
//");
                $this->result = array('success' => 1,'data' => $datas);

            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    /*
     * Las reservas separan dias y no púeden se tomadas
     * los pre eventos y los eventos no pueden ser tomados
     * las proformas no separan dias pero si se valida que si esta tomado por una reserva o un evento no se puede tomar
     * */
    function calendar_events_proforma(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('send'))){

                // all calendar month
                $fecha = fecha(1);

                $datas = $this->general->query("
                select DISTINCT r.r_id,r.r_name,r.r_color,pd.bd_day As 'pf_date',p.b_booking_type As module,'PF:' As type
                from booking As p LEFT JOIN booking_events As e ON p.b_id=e.e_proforma
                    JOIN booking_days As pd ON p.b_id=pd.bd_booking
                    JOIN rooms As r ON pd.bd_room=r.r_id
                WHERE p.b_booking_type=3
                ");
                $this->result = array('success' => 1,'data' => $datas);

            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }
    function calendar_events_day(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('id','type','day'))){

                $id   = $this->class_security->data_form('id');
                $day  = $this->class_security->data_form('day');
                $type  = $this->class_security->data_form('type');

                // all calendar month
                    $typeQ = 'Evento';
                    $datas = $this->general->query("select p.b_id As id,em.em_type,pd.bd_id As id_day,p.b_code,us.u_name,p.b_event_name As 'name',r.r_name As 'name_room',p.b_cpax,p.b_type,pd.bd_description,sl.s_name,p.b_atcreate,pd.bd_houri As 'hour1',pd.bd_hourf As 'hour2',pd.bd_type from booking As p
    JOIN users As us ON  p.b_user = us.u_id
    LEFT JOIN booking_events As e ON p.b_id=e.e_proforma
    LEFT JOIN seller As sl ON p.b_seller=sl.s_id
    LEFT JOIN booking_modifid As em ON p.b_id=em.em_pro_event and em.em_type=2
    JOIN booking_days As pd ON p.b_id=pd.bd_booking
    JOIN rooms As r ON pd.bd_room=r.r_id
    WHERE p.b_booking_type='".$type."' and pd.bd_day = '".$day."' AND p.b_status NOT IN(4,99)
    GROUP BY p.b_id,em.em_type,pd.bd_id
    ORDER BY p.b_id ASC");



                $dataQ = array_map(function($xb) use ($typeQ,$day) {
                    return array(
                        'id'    => ($xb['id']),
                        'idc'    => encriptar($xb['id']),
                        'id_day' => ($xb['id_day']),
                        'u_name'  => ($xb['u_name']),
                        'code'  => ($xb['b_code']),
                        'name'  => ($xb['name']),
                        'name_room'  => ($xb['name_room']),
                        'count'  => ($xb['b_cpax']),
                        'observation'  => ($xb['bd_description']),
                        'type_event'  => $this->class_data->bookingType[$xb['b_type']],
                        'type_event_box'  => $this->class_security->array_data($xb['bd_type'],$this->class_data->typeBox),
                        'modifid'  => isset($xb['em_type']) ? 1 : 2,
                        'type'  => $typeQ,
                        'date1' => $xb['hour1'],
                        'date2' => $xb['hour2'],
                        'atcreate' => $xb['b_atcreate'],
                        'seller' => $xb['s_name'],
                        'datee' => $day
                    );
                },$datas);



                $this->result = array('success' => 1,'data' => $dataQ);

            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    //validate day
    function get_package(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){

                //campos post
                $id            = $this->class_security->data_form('id','int');


                //validar la duplicidad del username or emailes
                $query = $this->general->get('package',array('p_id' => $id));

                $this->result = (isset($query) and !empty($query)) ? array('success' => 1,'data' => $query) : array('success' => 2,'msg' => 'No se encontro el registro');

            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }
    function validate_date_event(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('day','hour1','hour2','room'))){

                //campos post
                $data_id        = $this->class_security->data_form('data_id','decrypt_int');
                $day            = $this->class_security->data_form('day');
                $hour1          = $this->class_security->data_form('hour1');
                $hour2          = $this->class_security->data_form('hour2');
                $room           = $this->class_security->data_form('room');


                $days[] = [
                    'date' => $day,
                    'room' => $room,
                    'hour1' => substr($hour1,0,5) . ':00',
                    'hour2' => substr($hour2,0,5) . ':00'
                ];

                if(isset($days)  and count($days)  > 0) {

                    //validate days
                    $daysResolved = $this->class_security->day_reserved($data_id, $days);
                    if (isset($daysResolved) and count($daysResolved) > 0) {
                        $this->result = array('success' => 2, 'msg' => 'Debes validar los dias ya que Algunos ya se encuentran Tomados', 'data' => $daysResolved);
                        api($this->result);
                        exit;
                    }else{
                        $this->result = array('success' => 1);
                    }
                }else{
                    $this->result = array('success' => 2,'msg' => 'La fecha esta incompleta');
                }


            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

}