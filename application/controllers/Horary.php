<?php

class Horary extends CI_Controller
{

    //propiedades
    private $session_id;
    private $session_token;
    private $user_data;
    private $controllName = 'Horario';
    private $controller   = 'horary/';
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

    //Admon Hours
    function admon_validate() {
        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => $this->controllName,
            'user'          => $this->user_data
        );

        $data_body = array(
            'employs' => $this->general->query("SELECT he.* 
                FROM horary_employ he
                INNER JOIN horary_category hc ON he.he_category = hc.hc_id
                JOIN users As u ON hc.hc_profile = u.u_profile AND u.u_id='".$this->user_data->u_id."'
                JOIN users_properties As up ON u.u_id=up.up_user AND hc.hc_propertie=up.up_propertie",'obj'),
            'crud' => array(
                'url_modals'    => base_url("modal/"),
                'datatable'        => base_url("{$this->controller}database_validate"),
            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','plugins/htmlcanva/html2canvas.js','cr/horary.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('horary/v_validate',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }
    function admon_horary($horary= '') {
        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => $this->controllName,
            'user'          => $this->user_data
        );
       $horaryCala = (isset($horary) and $horary != '' and preg_match('/^\d{4}-\d{2}-\d{2}$/', desencriptar($horary))) ? desencriptar($horary) : fecha(1);
        $days = $this->class_security->obtenerSemanaPorFecha($horaryCala);
        $queryData = $this->employ_calendar($this->user_data->u_id,$days);
        $data_body = array(
            'datas' => $this->orderUserHour($queryData),
            'days' => $days,
            'dayq' => $horaryCala,
            'schedules' => $this->general->all_get('horary_schedules'),
            'ocupation' => $this->employ_calendar_ocupation($days),
            'crud' => array(
                'url_modals'    => base_url("modal/"),
                'url_schedules_save'        => base_url("{$this->controller}schedules_save"),
                'url_schedules_day_save'    => base_url("{$this->controller}schedules_day_save"),
                'url_schedules_day_ocupation_save'    => base_url("{$this->controller}schedules_day_ocupaton_save"),
                'url_send_whatsapp'    => base_url("{$this->controller}send_whatsapp"),
            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','plugins/htmlcanva/html2canvas.js','cr/horary.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('horary/v_admon',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }
    function admon_report() {

        $range = $this->class_security->rango_fechas('2025-03-01','2025-03-10');
//        print_r($range);
        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => $this->controllName,
            'user'          => $this->user_data
        );
        $days = $this->class_security->obtenerSemanaPorFecha(fecha(1));
        $queryData = $this->employ_calendar($this->user_data->u_id,$days);

        $data_body = array(
            'datas' => $this->orderUserHour($queryData),
            'days' => $days,
            'departaments'  => $this->general->query("SELECT hc.* FROM  horary_category hc
JOIN users As u ON hc.hc_profile = u.u_profile AND u.u_id='".$this->user_data->u_id."'
JOIN users_properties As up ON u.u_id=up.up_user AND hc.hc_propertie=up.up_propertie",'json'),
            'employs' => $this->general->query("SELECT he.* 
                FROM horary_employ he
                INNER JOIN horary_category hc ON he.he_category = hc.hc_id
                JOIN users As u ON hc.hc_profile = u.u_profile AND u.u_id='".$this->user_data->u_id."'
                JOIN users_properties As up ON u.u_id=up.up_user AND hc.hc_propertie=up.up_propertie",'obj'),
            'crud' => array(
                'url_modals'    => base_url("modal/"),
                'url_report'        => base_url("{$this->controller}report_employ"),
                'url_save'    => base_url("{$this->controller}report_save_validated"),
//                'url_send_whatsapp'    => base_url("{$this->controller}send_whatsapp"),
            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','plugins/htmlcanva/html2canvas.js','cr/horary.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('horary/v_report',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }



    //report datatable
    function database_validate(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('date1','date2'))){

                $draw      = $this->class_security->data_form('draw',8);
                $employ_name      = $this->class_security->data_form('employ','str');
                $date1      = $this->class_security->data_form('date1');
                $date2      = $this->class_security->data_form('date2');


                $this->load->library('ReportHours');
                $reportH = new ReportHours();

                $employ = '';
                $department = '';

                $where_and = [
                    'employ' => $employ_name ?? '',
        //            'date' => 'Tatiana'
                'validated' => 'id_hour_null'
                ];
                $fecha_1 = preg_match('/^\d{4}-\d{2}-\d{2}$/', $date1) && strtotime($date1) !== false ? $date1 : date('Y-m-01');
                $fecha_2 = preg_match('/^\d{4}-\d{2}-\d{2}$/', $date2) && strtotime($date2) !== false ? $date2 : date('Y-m-31');
                $rangos_fecha = $this->class_security->rango_fechas($fecha_1,$fecha_2);




        $data = $reportH->report_employ_query($employ,$department,$rangos_fecha,$where_and);

//
        $allq =  $reportH->BackReportEmploy($data);
//
        $reporte =  $reportH->procesarRegistros($allq);
//

        $clear_data = $this->class_security->aplanarDatosOptimizado($reporte);

        $dataResult = [];

//                print_r($clear_data);

//exit;



        //tabla

        $total_registros = count($clear_data);
//
//
        foreach($clear_data as $rows){

            //timer clear

            $dataResult[]= array(
                $rows['employ_name'],
//                $rows['fecha'],
                $rows['fecha'],
                $rows['hora_entrada'],
                $rows['hora_salida'],
                $rows['hora_marcado_entrada'],
                $rows['hora_marcado_salida'],
                $rows['tipo_hora'],
                $this->class_security->minutosAHoras($rows['minutos_trabajados']),
//                $rows->w_guest,
//                $rows->w_entry,
//                $rows->w_exit,
//                '$'.number_format($rows->w_fee,2),
//                '$'.number_format($rows->w_amount,2),
//                '$'.number_format($rows->w_comission,2),
//                $rows->w_percentage.'%',
//                $rows->w_number_booking,
//                $rows->type_event,
//                "<div class='form-check form-switch'><input class='form-check-input' type='checkbox' data-id='{$id}' {$aproved} onchange='$(this).change_status(\"{$rows->type_event}\")' role='switch' id='flexSwitchCheckDefault'></div>",
//                "<button type='button' onclick='$(this).forms_modal({\"page\" : \"walkin_admon\",\"data1\" : \"{$id}\",\"data2\" : \"{$type}\",\"title\" : \"Reporte de ventas por vendedor\"})' class='btn btn-success btn-sm'  data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-pencil text-white'></i></button>"
            );
        }



     $this->result = array(
            "draw" => $draw,
            "recordsTotal" => $total_registros,
            "recordsFiltered" => $total_registros,
            "data" => $dataResult
        );

            }else{
                $this->result = array(
                    "draw" => 10,
                    "recordsFiltered" => 0,
                    "data" => []
                );
            }
        }else{
            $this->result = array(
                "draw" => 10,
                "recordsFiltered" => 0,
                "data" => []
            );
        }
        apirest($this->result);
    }


    //employ view data
    private function employ_calendar_ocupation($days){
             return       $this->general->query("
       SELECT 
    t.fecha AS heo_date,
    heo_ocupation,
    heo_entry,
    heo_exit
FROM
    (SELECT '{$days[0]['fecha']}' AS fecha UNION
     SELECT '{$days[1]['fecha']}' UNION
     SELECT '{$days[2]['fecha']}' UNION
     SELECT '{$days[3]['fecha']}' UNION
     SELECT '{$days[4]['fecha']}' UNION
     SELECT '{$days[5]['fecha']}' UNION
     SELECT '{$days[6]['fecha']}') t
LEFT JOIN
    horary_employ_ocupation heo
    ON heo.heo_date = t.fecha
ORDER BY t.fecha;
            ");
    }

    private function employ_calendar($profile,$days){
             return       $this->general->query("WITH 
            -- 1️⃣ Generamos las fechas manualmente
            week_dates AS (
                SELECT DATE('{$days[0]['fecha']}') AS hes_date UNION ALL
                SELECT DATE('{$days[1]['fecha']}') UNION ALL
                SELECT DATE('{$days[2]['fecha']}') UNION ALL
                SELECT DATE('{$days[3]['fecha']}') UNION ALL
                SELECT DATE('{$days[4]['fecha']}') UNION ALL
                SELECT DATE('{$days[5]['fecha']}') UNION ALL
                SELECT DATE('{$days[6]['fecha']}')
            ),
            
            -- 2️⃣ Seleccionamos los empleados automáticamente según su perfil en `horary_category`
            users AS (
                SELECT he.he_id AS hes_employ
                FROM horary_employ he
                INNER JOIN horary_category hc ON he.he_category = hc.hc_id
                JOIN users As u ON hc.hc_profile = u.u_profile AND u.u_id='".$profile."'
                JOIN users_properties As up ON u.u_id=up.up_user AND hc.hc_propertie=up.up_propertie
            )
            
            -- 3️⃣ Consulta principal
            SELECT
                wd.hes_date,
                u.hes_employ,
            
                -- Datos del empleado
                COALESCE(he.he_id, '') AS he_id,
                COALESCE(he.he_name, '') AS he_name,
                COALESCE(he.he_phone, '') AS he_phone,
                COALESCE(he.he_category, '') AS he_category,
                COALESCE(hc.hc_name, '') AS he_category_name,
                COALESCE(hc.hc_order, '') AS he_category_order,
                COALESCE(hc.hc_profile, '') AS he_category_profile,
                COALESCE(he.he_status, '') AS he_status,
                COALESCE(he.he_code, '') AS he_code,
            
                -- Datos del horario asignado
                COALESCE(hs.hs_id, '') AS hs_id,
                COALESCE(hs.hs_type, '') AS hs_type,
                COALESCE(hs.hs_value, '') AS hs_value,
                COALESCE(hs.hs_hour1, '') AS hs_hour1,
                COALESCE(hs.hs_hour2, '') AS hs_hour2,
                COALESCE(hs.hs_color, '') AS hs_color
            
            FROM week_dates wd
            CROSS JOIN users u
            LEFT JOIN horary_employ he  ON u.hes_employ = he.he_id
            LEFT JOIN horary_category hc  ON he.he_category = hc.hc_id
            LEFT JOIN horary_employ_schedules hes   ON wd.hes_date = hes.hes_date  AND u.hes_employ = hes.hes_employ
            LEFT JOIN horary_schedules hs   ON COALESCE(hes.hes_schedules, 0) = hs.hs_id
            ORDER BY wd.hes_date DESC, hc.hc_order ASC;
            ");
    }

    public function selectOrday($datas = [],$employ_id = '',$day = []){
       $template = '';

       $jsondata = json_encode([
           'employ' => encriptar($employ_id),
           'day' => $day['date'],
       ]);

       $template .= "<select class='form-control form-control-sm text-center' onchange='$(this).changeDay({$jsondata})'>";
//         if(isset($day['schedule_id']) and $day['schedule_id'] == ''){
             $template .= "<option value='' selected > [SELECCIONAR] </option>";
//         }
       foreach($datas as $data){
           $id = encriptar($data->hs_id);
           if($data->hs_type == 1){
               $template .= "<option value='$id'  ".seleccionar_select($day['schedule_id'],$data->hs_id).">".mb_strtoupper($data->hs_value)."</option>";

           }else{
               $template .= "<option value='$id' ".seleccionar_select($day['schedule_id'],$data->hs_id).">{$data->hs_hour1} - {$data->hs_hour2}</option>";
           }
       }
       $template .= '</select>';


       return $template;
    }

    function send_whatsapp(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('data'))){
                $this->load->library('Pdf');


                $days = $this->class_security->obtenerSemanaPorFecha(fecha(1));
                $queryData = $this->employ_calendar($this->user_data->u_id,$days);

                $datas = $this->orderUserHour($queryData);
                $users =  array_merge(...array_column($datas, 'users'));

        // Filtrar usuarios que tienen un teléfono no vacío
                $filteredUsers = array_filter($users, function($user) {
                    return !empty($user['phone']);
                });

                foreach($filteredUsers as $userq){

                    $file_folder = '_files/tmp/' . 'horary_' . $this->class_security->random() . '.pdf';
                    $this->pdf->pdfSave('horary/v_pdf_horary_employ',$userq,$file_folder);

                    $messageWhatsapp = "*Horario* ". fecha(2);
                    $UsersNotify[] = [
                        'u_phone' => $userq['phone'],
                    ];
                    sendNotificationFormFile($messageWhatsapp,$file_folder,$UsersNotify,'application/pdf');

                    unlink($file_folder);
                    $UsersNotify = [];
                }
                $this->result = array('success' => 1);
            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function schedules_day_save(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('day','employ'))){

                //campos post
                $employ     = $this->class_security->data_form('employ','decrypt_int');
                $horary     = $this->class_security->data_form('horary','decrypt_str');
                $day        = $this->class_security->data_form('day');

                if($horary == ''){
                    $this->result =   $this->general->delete('horary_employ_schedules',array('hes_employ' => $employ,'hes_date' => $day));
                }else{
                    $data = array(
                        'hes_employ'        => $employ,
                        'hes_schedules'     => $horary,
                        'hes_date'          => $day,
                        'hes_atcreate'      => fecha(2),
                    );

                    //validar la duplicidad del username or emailes
                    $this->result =   $this->general->create_update('horary_employ_schedules',['hes_employ' => $employ,'hes_date' => $day,],$data);
                }



            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }
    function schedules_day_ocupaton_save(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('day','type'))){

                //campos post
                $type        = $this->class_security->data_form('type');
                $day        = $this->class_security->data_form('day');
                $ocupation        = $this->class_security->data_form('ocupation','str');

                if(in_array($type,array(1,2,3))){

                    switch($type){
                        case 1:
                            $column = 'heo_ocupation';
                            break;
                        case 2:
                            $column = 'heo_entry';
                            break;
                        case 3:
                            $column = 'heo_exit';
                            break;
                    }


                    $data = array(
                        'heo_date'          => $day,
                        $column             => $ocupation,
                        'heo_created_at'    => fecha(2),
                    );

                    $this->result =   $this->general->create_update('horary_employ_ocupation',['heo_date' => $day,],$data);
                }else{
                    $this->result = array('success' => 2,'msg' => 'Tipo de ocupacion no valido');
                }



                $this->result = array('success' => 1);

            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function schedules_save(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('type'))){

                //campos post
                $id          = $this->class_security->data_form('data_id','int');
                $type        = $this->class_security->data_form('type');
                $name        = $this->class_security->data_form('name');
                $sigla       = $this->class_security->data_form('sigla');
                $hour1       = $this->class_security->data_form('hour1');
                $hour2       = $this->class_security->data_form('hour2');
                $color       = $this->class_security->data_form('color');

                //validation
                if($type == 1){
                    if($name == ''){
                        $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
                        api($this->result);
                    }
                }else{
                    if($hour1 == '' || $hour2 == ''){
                        $this->result = array('success' => 2,'msg' => 'Las horas son obligatorias');
                        api($this->result);
                    }
                }

                $data = array(
                    'hs_type'           => $type,
                    'hs_value'          => $name,
                    'hs_hour1'          => $hour1,
                    'hs_hour2'          => $hour2,
                    'hs_color'          => $color,
                    'hs_sigla'          => $sigla,
                );

                //validar la duplicidad del username or emailes
                $this->result =   $this->general->create_update('horary_schedules',array('hs_id' => $id),$data);

            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }


    private function orderUserHour($datas = []) {
        $result = [];

        if (!empty($datas)) {
            foreach ($datas as $value) {
                $ct_id = $value['he_category'];
                $ct_name = $value['he_category_name'];

                // Si la categoría no existe en el array, la inicializamos
                if (!isset($result[$ct_id])) {
                    $result[$ct_id] = [
                        'cat_name' => $ct_name,
                        'users' => []
                    ];
                }

                $user_id = $value['he_id'];

                // Si el usuario no está agregado aún, lo inicializamos con su información
                if (!isset($result[$ct_id]['users'][$user_id])) {
                    $result[$ct_id]['users'][$user_id] = [
                        'id' => $user_id,
                        'name' => $value['he_name'],
                        'phone' => $value['he_phone'],
                        'cat_name' => $ct_name,
                        'dates' => [] // Aquí guardamos las fechas
                    ];
                }

                // Agregamos la fecha con la información del horario
                $result[$ct_id]['users'][$user_id]['dates'][] = [
                    'date' => $value['hes_date'],
                    'schedule_id' => $value['hs_id'],
                    'schedule_type' => $value['hs_type'],
                    'schedule_value' => $value['hs_value'],
                    'hour_start' => $value['hs_hour1'],
                    'hour_end' => $value['hs_hour2'],
                    'color' => $value['hs_color']
                ];
            }

            // Convertimos los usuarios de cada categoría en un array indexado (reindexamos)
            foreach ($result as &$category) {
                // Ordenamos los usuarios por su ID
                usort($category['users'], function ($a, $b) {
                    return $a['id'] - $b['id']; // Orden ascendente por ID de usuario
                });

                // Ordenamos las fechas de forma ASCENDENTE (más antigua primero)
                foreach ($category['users'] as &$user) {
                    usort($user['dates'], function ($a, $b) {
                        return strtotime($a['date']) - strtotime($b['date']); // Orden ascendente por fecha
                    });
                }

                // Reindexamos el array de usuarios para que el índice sea consecutivo
                $category['users'] = array_values($category['users']);
            }
        }

        return $result;
    }

    //category
    function admon_people() {
        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => $this->controllName,
            'user'          => $this->user_data
        );


        $data_body = array(
            'dataCategory' => $this->general->query('select * from horary_category As c  LEFT JOIN profile as p ON c.hc_profile=p.p_id LEFT JOIN properties As po ON c.hc_propertie=po.pt_id','obj'),
            'dataEmploy' => $this->general->query('select e.*,c.*,IF(isnull(fci.hfc_employ),true,false) as faceid from horary_employ As e JOIN horary_category As c ON e.he_category=c.hc_id LEFT JOIN horary_employ_faceid As fci ON e.he_id=fci.hfc_employ','obj'),
            'crud' => array(
                'url_modals'    => base_url("modal/"),
                'url_category_save'      => base_url("{$this->controller}category_save"),
                'url_category_delete'    => base_url("{$this->controller}category_delete"),

                'url_personal_save'      => base_url("{$this->controller}personal_save"),
                'url_personal_delete'    => base_url("{$this->controller}personal_delete"),


                'url_personal_picture_save'      => base_url("{$this->controller}personal_picture_save"),


            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','plugins/face-api/face-api.min.js','plugins/webcam/webcam-easy.min.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('horary/v_personal',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }

    function category_save(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('name','profile','propertie','order'))){

                //campos post
                $id          = $this->class_security->data_form('data_id','decrypt_int');
                $name        = $this->class_security->data_form('name');
                $profile      = $this->class_security->data_form('profile');
                $propertie      = $this->class_security->data_form('propertie');
                $order        = $this->class_security->data_form('order','int');


                $data = array(
                    'hc_name'           => $name,
                    'hc_order'          => $order,
                    'hc_profile'        => $profile,
                    'hc_propertie'        => $propertie,
                );

                //validar la duplicidad del username or emailes
                $this->result =   $this->general->create_update('horary_category',array('hc_id' => $id),$data);

            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function category_delete(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){
                $id = $this->class_security->data_form('id','decrypt_int');
                if(strlen($id) >= 1){
                    if($this->general->exist('horary_category',array('hc_id' => $id))){
                        $this->result =  $this->general->delete('horary_category',array('hc_id' => $id));
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


    //personal
    function personal_picture_save(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('data_id','image','descriptor','score'))){

                //campos post
                $id           = $this->class_security->data_form('data_id','decrypt_int');
                $image        = $this->class_security->data_form('image');
                $descriptor   = $this->class_security->data_form('descriptor');
                $score        = $this->class_security->data_form('score');


                $arr = json_decode($descriptor, true);

                $this->load->helper('face');
                $vec = l2Normalize(array_map('floatval', $arr));
                $blob = floatArrayToBlob($vec);
//                print_r($blob);

                $data = array(
                    'hfc_employ'       => $id,
                    'hfc_photo'        => $image,
                    'hfc_descriptor'   => $descriptor,
                    'hfc_descriptor_blob'        => $blob,
                    'hfc_score'        => $score,
                );
//                print_r($data);
//                exit;

                //validar la duplicidad del username or emailes
                $this->result =   $this->general->create_update('horary_employ_faceid',array('hfc_employ' => $id),$data);
            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    //personal
    function personal_save(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('name','category','phone','status'))){

                //campos post
                $id          = $this->class_security->data_form('data_id','decrypt_int');
                $name        = $this->class_security->data_form('name');
                $code        = $this->class_security->data_form('code');
                $phone       = $this->class_security->data_form('phone');
                $status      = $this->class_security->data_form('status','int');
                $category    = $this->class_security->data_form('category','int');


                $data = array(
                    'he_category'      => $category,
                    'he_name'          => $name,
                    'he_phone'         => $phone,
                    'he_status'        => $status,
                    'he_code'          => $code,
                );

                //validar la duplicidad del username or emailes
                $this->result =   $this->general->create_update('horary_employ',array('he_id' => $id),$data);

            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function personal_delete(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){
                $id = $this->class_security->data_form('id','decrypt_int');
                if(strlen($id) >= 1){
                    if($this->general->exist('horary_employ',array('he_id' => $id))){
                        $this->result =  $this->general->delete('horary_employ',array('he_id' => $id));
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

    /*
     * REPORT DAYS USER
     *
     * */

    function report_employ(){
//        report_employ

        if($this->input->post()){
            if($this->class_security->validate_post(array('date1','date2','departament','employ'))){

                        $this->load->library('ReportHours');
                        $reportH = new ReportHours();
                        $fecha1 = $this->class_security->data_form('date1');
                        $fecha2 = $this->class_security->data_form('date2');
                        $employ = $this->class_security->data_form('employ');
                        $departament = $this->class_security->data_form('departament');
//                        $fecha1 = '2025-03-10';
//                        $fecha2 = '2025-04-18';
//                        $employ = '3';
//                        $departament = '';

                        $rangos_fecha = $this->class_security->rango_fechas($fecha1,$fecha2);
                        $segmento_fecha = $this->class_security->segmento_fechas($rangos_fecha);

                        //clean Days
//                        $daysEach = array_map(function($fecha) {
//                                return [
//                                    'date' => $fecha,
//                                    'day'  => (new DateTime($fecha))->format('d')
//                                ];
//                            }, $rangos_fecha);
//                        print_r($daysEach);
//print_r(array_merge(...array_values($segmento_fecha)));


                        $data = $reportH->report_employ_query($employ,$departament,$rangos_fecha);

                        $allq =  $reportH->BackReportEmploy($data);
//
                        $reporte =  $reportH->procesarRegistros($allq);


//                        print_r($reporte);exit;
//                        $filtrado = array_map(function($registros) {
//                            // Filtramos los registros donde 'bt_type_houry' == 2
//                            return array_filter($registros, function($item) {
//                                return $item['bt_type_houry'] == 2;
//                            });
//                        }, $reporte);



                        return  $this->load->view('horary/v_report_expert',['data' => $reporte,'days' => $segmento_fecha,'typesHours' => $reportH->getTiposHoras(),
                            'date1' => $fecha1,
                            'date2' => $fecha2,
                        ]);


            }else{
//                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
//            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);

    }

    function report_employ_pdf($datajson = '') {

        //variable Data
        try {
            $data_base = desencriptar($datajson);
            $decode = json_decode($data_base,true);
            if(isset($decode['employ_id']) and isset($decode['date1']) and isset($decode['date2'])){
                $employ = $decode['employ_id'];
                $date1     = $decode['date1'];
                $date2     = $decode['date2'];
                $departament = 'all';

                $this->load->library('ReportHours');
                $reportH = new ReportHours();



                $rangos_fecha = $this->class_security->rango_fechas($date1,$date2);

                $and_where = [
                    'employ' => '',
                    'employ_id' => $employ,
                    'validated' => 'employ_id_only_extra_aprroved',
                    'type_hour' => 2
                ];

                $data = $reportH->report_employ_query($employ,$departament,$rangos_fecha,$and_where);

                $allq =  $reportH->BackReportEmploy($data);

                $reporte =  array_values($reportH->procesarRegistros($allq));
//                print_r(array_values($reporte));
//                exit;

                $this->load->library('Pdf');

                $dataClean = [
                    'employ_name' => $reporte[0]['employ_name'],
                    'category_name' => $reporte[0]['category_name'],
                    'dates' => $this->class_security->aplanarDatosOptimizado($reporte),
                ];
//                print_r($dataClean);
//                exit;

                $nombre = 'admon_horary_'.date('Y-m-d');
                $pro_nombre = "hora_extras_{$nombre}.pdf";


//               $this->pdf->pdfview('pdf/pdf_proforma',$data,$pro_nombre);
//                $this->pdf->pdfview('pdf/pdf_autorizacion_extras',$dataClean,$pro_nombre);
                $this->pdf->pdfDownload('pdf/pdf_autorizacion_extras',$dataClean,$pro_nombre);


                }else{
                exit("Sorry no data Download");
            }
        }catch (Exception $e){
            exit("Error Download FIle");
        }


    }



    function report_save_validated(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('data_id'))){

                //campos post
                $employ    = $this->class_security->data_form('data_id','int');
                $hours     = $this->class_security->data_form('hours','alone');


                //Validate days
                foreach($hours as $day){
                    if((isset($day['hb_id']) and isset($day['comment']) and isset($day['status'])) AND ($day['hb_id'] != '' || $day['comment'] != '' || $day['status'] != '')){

                        $data = array(
                            'hb_message'   => $day['comment'],
                            'hb_status'    => $day['status'],
                        );

                        //validar la duplicidad del username or emailes
                        $this->general->update('horary_biometric',[
                            'hb_id' => $day['hb_id'],
                            'hb_date' => $day['fecha']
                        ],$data);
////                        $this->result = array('success' => 2,'msg' => 'Las horas son obligatorias');
////                        api($this->result);
                    }
                }

                $this->result = array('success' => 1);
            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    //report consulta



}