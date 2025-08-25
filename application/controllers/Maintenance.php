<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Maintenance extends CI_Controller
{

    //propiedades
    private $session_id;
    private $session_token;
    private $user_data;
    public function __construct(){
        parent::__construct();
        $this->session_id     = $this->session->userdata('user_id');
        $this->session_token  = $this->session->userdata('user_token');
        $this->project        = $this->config->config['project'];

        //validacion de acceso
        auth();
        $this->load->library('Timer');
        $this->user_data = $this->general->get('users',array('u_id' => $this->session_id));
    }

    function my_tickets(){
        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => 'Mis Tareas Creadas',
            'controller'    => 'Dashboard',
            'user'          => $this->user_data,
            'style_level'   => array()
        );


        $data_body = array(
            'timer'      => new Timer(),
            'crud' => array(
                'url_modals'        => base_url("modal/"),
                'tabla_data'        => base_url("maintenance/datatable_my_ticket"),
            ),

        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/my_tickets.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('supervisor_maintenance/v_my_ticket',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }

    function ticket_local(){
        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => 'Log de Tareas',
            'controller'    => 'Dashboard',
            'user'          => $this->user_data,
            'style_level'   => array()
        );




        $data_body = array(
            'timer'      => new Timer(),
            'userclean'     => $this->general->all_get('users',['u_status'=>1,'u_profile'=>2]),
            'status'        => $this->general->all_get('status',['s_type' => 2],['s_id','DESC']),
            'filials'       => $this->general->query("select m.*,s.*,e.*,a.*,u.u_name,u.u_id from  house_maintence As m
    LEFT JOIN house_maintence_employe As e ON m.m_id=e.hma_assigne and e.hma_status IN(1,2,3,4)
    LEFT JOIN status As s ON m.m_status=s.s_id
    LEFT JOIN users as u ON e.hma_user=u.u_id
    JOIN areas As a ON m.m_area=a.a_id
    where m.m_area='".$this->user_data->u_area."' 
HAVING e.hma_status IN(1,2,3) or e.hma_status IS NULL",'array',false),
            'crud' => array(
                'url_modals'        => base_url("modal/"),
                'url_save'          => base_url("maintenance/save"),
                'url_revision'      => base_url("maintenance/revision"),
                'url_save_resolver' => base_url("maintenance/save_resolver"),
                'url_notify'        => base_url("profile/notify_save"),
            ),
            'menu' => [
                ['name' => 'Inicio','url' => base_url('/'),'icon' => 'fa fa-house'],
                ['name' => 'Trabajo','url' => base_url('task_local'),'icon' => 'fa fa-ticket-alt'],
                ['name' => 'Proceso','url' => base_url('job_rowk'),'icon' => 'fa fa-ticket-alt'],
                ['name' => 'Logs','url' => base_url('task'),'icon' => 'fa fa-table'],
            ],
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/maintenance.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('supervisor_maintenance/v_ticket_admin_dashboard',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }

    function task_local(){
        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => 'Log de Tareas',
            'controller'    => 'Dashboard',
            'user'          => $this->user_data,
            'style_level'   => array()
        );


        $data_body = array(
            'timer'      => new Timer(),
            'userclean'     => $this->general->all_get('users',['u_status'=>1,'u_profile'=>2]),
            'status'        => $this->general->all_get('status',['s_type' => 2],['s_id','DESC']),
            'filials' => $this->general->query("select m.*,s.*,e.*,u.u_name from  house_maintence As m
    JOIN house_maintence_employe  As e ON m.m_id=e.hma_assigne and e.hma_status IN(1,2)
    JOIN status As s ON m.m_status=s.s_id 
    LEFT JOIN users as u oN e.hma_user=u.u_id  
    WHERE e.hma_user='".$this->session_id."' and m.m_complete != 3 ",'array',false),
            'crud' => array(
                'url_modals'        => base_url("modal/"),
                'url_save'          => base_url("maintenance/task"),
                'url_notify'        => base_url("profile/notify_save"),
            ),
            'menu' => [
                ['name' => 'Inicio','url' => base_url('/'),'icon' => 'fa fa-house'],
                ['name' => 'Trabajo','url' => base_url('task_local'),'icon' => 'fa fa-ticket-alt'],
                ['name' => 'Proceso','url' => base_url('job_rowk'),'icon' => 'fa fa-ticket-alt'],
                ['name' => 'Logs','url' => base_url('task'),'icon' => 'fa fa-table'],
            ],
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/maintenance.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu_security');
        $this->load->view('supervisor_maintenance/v_ticket_user_dashboard',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }

    function clean_log(){
        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => 'Log de Tareas',
            'controller'    => 'Dashboard',
            'user'          => $this->user_data,
            'style_level'   => array()
        );


        $data_body = array(
            'crud' => array(
                'url_modals'    => base_url("modal/"),
                'tabla_data'    => base_url("maintenance/datatable_clean"),
            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/maintenance.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('maintenance/v_clean_log',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }

    function task_log(){
        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => 'Log de Tareas',
            'controller'    => 'Dashboard',
            'user'          => $this->user_data,
            'style_level'   => array()
        );


        $data_body = array(
            'crud' => array(
                'url_modals'    => base_url("modal/"),
                'tabla_data'    => base_url("maintenance/datatable"),
                'url_revision'      => base_url("ticket/task_admon_revision"),
            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/maintenance.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('maintenance/v_task_log',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }

    function job_rowk(){
        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => 'Log de Tareas',
            'controller'    => 'Dashboard',
            'user'          => $this->user_data,
            'style_level'   => array()
        );


        $data_body = array(
            'status_filial'     => '',
            'timer'      => new Timer(),
            'userclean'     => $this->general->all_get('users',['u_status'=>1,'u_profile'=>2]),
            'status'        => $this->general->all_get('status',['s_type' => 2],['s_id','DESC']),
            'filials'       => $this->general->query("select e.*,m.*,s.*,u.u_name from  house_maintence As m
    JOIN house_maintence_employe As e ON m.m_id=e.hma_assigne and e.hma_status=2
    LEFT JOIN status As s ON m.m_status=s.s_id
    LEFT JOIN users as u ON e.hma_user=u.u_id
    where m.m_complete=1 ",'array',false),
            'crud' => array(
                'url_modals'    => base_url("modal/"),
            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/maintenance.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu_security');
        $this->load->view('supervisor_maintenance/v_task_process',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }

    function save_resolver(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('filial'))){

                //campos post
                $filial          = $this->class_security->data_form('filial');
                $this->result =  $this->general->update('house_maintence',['m_id' => $filial],['m_complete' => 4]);

            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }


    function save(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('priority','data_id','time','type'))){

                //campos post
                $id            = $this->class_security->data_form('data_id');
                $priority      = $this->class_security->data_form('priority');
                $usuario       = $this->class_security->data_form('usuario','0');
                $descripcion   = $this->class_security->data_form('descripcion');
                $timeQ         = $this->class_security->data_form('time');
                $timeend       = $this->class_security->data_form('timeend');
                $type          = $this->class_security->data_form('type');
                $time          = ($type == 1) ? $timeQ : $this->class_security->format_date($timeQ,'Y-m-d H:i:s');

                //validate maintence
                if($this->general->exist('house_maintence',['m_id' => $id,'m_complete != ' => 4])){
                    //asignar data job
                    $this->general->update('house_maintence',['m_id' => $id],['m_observation_super' => $descripcion,'m_status' => $priority]);

                    //validate is user asigne job in this maintence disable y re-asign
//                    $this->general->update('house_maintence_employe',['hma_assigne' => $id,'hma_status != ' => 4],['hma_status' => 5,'hma_ending' => fecha(2)]);

                    //asignar employ job
                    $this->general->create('house_maintence_employe',[
                        'hma_assigne'       => $id,
                        'hma_user'          => $usuario,
                        'hma_type'          => $type,
                        'hma_status'        => 1,
                        'hma_time'          => $time,
                        'hma_time2'         => $timeend,
                        'hma_atcreate'      => fecha(2),
                    ]);


                    $this->result = array('success' => 1);

                }else{
                    $this->result = array('success' => 2,'msg' => 'Esta tarea no se puede asignar');
                }


//                $id = $data['id'];
//
//                //files
//               $file[] =  $this->class_security->upload_image('imagen1','_files/maintenance/');
//               $file[] =  $this->class_security->upload_image('imagen2','_files/maintenance/');
//               $file[] =  $this->class_security->upload_image('imagen3','_files/maintenance/');
//               $file[] =  $this->class_security->upload_image('imagen4','_files/maintenance/');
//
//               foreach($file  AS $fl){
//                   if(strlen($fl) > 20){
//                          $this->general->create('house_maintence_files',[
//                            'mf_maintence' => $id,
//                            'mf_file' => $fl,
//                            'mf_type' => 1,
//                            'mf_atcreated' => fecha(2),
//                          ]);
//                   }
//               }



            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function task(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('id','assigne'))){

                //campos post
                $id             = $this->class_security->data_form('id','decrypt_int');
                $assigne        = $this->class_security->data_form('assigne','decrypt_int');
                $descripcion    = $this->class_security->data_form('descripcion');

                //validate

                $data = $this->general->get('house_maintence_employe',array('hma_id' => $assigne,'hma_assigne' => $id,'hma_status != ' => 3),'array');
                if(isset($data) and count($data) > 2 and in_array($data['hma_status'],array(1,2))) {

                    if($data['hma_status'] == 1) {

                        $this->general->update('house_maintence_employe', array('hma_id' => $assigne,'hma_assigne' => $id), array('hma_status' => 2, 'hma_take' => fecha(2)));
                    }else{

                        $this->general->update('house_maintence_employe', array('hma_id' => $assigne,'hma_assigne' => $id),
                            [
                                'hma_observation' => $descripcion,
                                'hma_status' => 3,
                                'hma_ending' => fecha(2),
                            ]
                        );

                        $file[] =  $this->class_security->upload_image('imagen1','_files/maintenance/');
                        $file[] =  $this->class_security->upload_image('imagen2','_files/maintenance/');
                        $file[] =  $this->class_security->upload_image('imagen3','_files/maintenance/');
                        $file[] =  $this->class_security->upload_image('imagen4','_files/maintenance/');

                        foreach($file  AS $fl){
                            if(strlen($fl) > 20){
                                $this->general->create('house_maintence_files',[
                                    'mf_maintence' => $id,
                                    'mf_file' => $fl,
                                    'mf_type' => 2,
                                    'mf_atcreated' => fecha(2),
                                ]);
                            }
                        }
                    }

                    $this->result = array('success' => 1);
                }else{
                    $this->result = array('success' => 2,'msg' => 'Esta tarea no se puede Trabajar');
                }

            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function revision(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('task','job','status'))){

                //campos post
                $task          = desencriptar($this->class_security->data_form('task'));
                $job           = $this->class_security->data_form('job','int');
                $status        = $this->class_security->data_form('status','int');
                $observation   = $this->class_security->data_form('observation');

                //validate

                $data = $this->general->get('house_maintence_employe',array('hma_id' => $job,'hma_assigne' => $task,'hma_status != ' => 4),'array');
                if(isset($data) and count($data) > 2 and in_array($data['hma_status'],array(1,2,3))) {

                    $statusQ = ($status == 1) ? 4 : 1;
                    $statusC = ($status == 2) ? 1 : '';
                    $statusCP = ($status == 2) ? 2 : 3; // 2 retornado  3 completado

                    $this->general->update('house_maintence', array('m_id' => $task), array('m_accordance' => $statusC,'m_complete' => $statusCP,'m_accordance_observation' =>  $observation));

                    $this->general->update('house_maintence_employe', array('hma_id' => $job,'hma_assigne' => $task), array('hma_status' =>  $statusQ));
                    $this->result = array('success' => 1);
                }else{
                    $this->result = array('success' => 2,'msg' => 'Esta tarea no se puede Trabajar');
                }

            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    #logs de mantenimiento
    function datatable_clean(){
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
            $dato = '';
            $order = array();
        }
        $data = array();


        if(in_array($this->user_data->u_profile,[4,6])){
//            // perfil tecnico de mantenimiento
//            $consulta_primary =  "select h.*,em.*,e.s_color,e.s_name,ua.u_name As 'au_name',u.u_name from house_maintence As h
//    LEFT JOIN house_maintence_employe As em ON h.m_id=em.hma_assigne
//    JOIN status As e On h.m_complete=e.s_id
//    LEFT JOIN users As u ON em.hma_user=u.u_id
//    LEFT JOIN users As ua ON h.m_user_request=ua.u_id
//    where (h.m_user_request='".$this->user_data->u_id."' or em.hma_user='".$this->user_data->u_id."') and (h.m_code LIKE '%".$valor."%' or h.m_atcreated LIKE '%".$valor."%') ";
//
//        }else if($this->user_data->u_profile == 2){
//            // perfil administrador de mantenimiento
//            $consulta_primary =  "select h.*,em.*,e.s_color,e.s_name,ua.u_name As 'au_name',u.u_name from house_maintence As h
//    LEFT JOIN house_maintence_employe As em ON h.m_id=em.hma_assigne and em.hma_status IN(1,2,3,4)
//    JOIN status As e On h.m_complete=e.s_id  LEFT JOIN users As u ON em.hma_user=u.u_id LEFT JOIN users As ua ON h.m_user_request=ua.u_id where h.m_code LIKE '%".$valor."%' or h.m_atcreated LIKE '%".$valor."%' ";
//
            $consulta_primary =  "select * from house As h
    JOIN status as s ON h.f_status_actual=s.s_id
     JOIN house_assignment ha on h.f_id = ha.a_house and ha.a_status_service
        JOIN users as u ON ha.a_user = u.u_id
         WHERE  ha.a_status_service = 3  and DATE_FORMAT(ha.a_atcreate,'%Y-%m-%d') = '".fecha(1)."' and (h.f_name LIKE '%".$valor."%' or h.f_code LIKE '%".$valor."%') ";
        }
        else{
//            // todos los que han generado ticket propios
            $consulta_primary =  "select * from house As h
    JOIN status as s ON h.f_status_actual=s.s_id
     JOIN house_assignment ha on h.f_id = ha.a_house and ha.a_status_service
        JOIN users as u ON ha.a_user = u.u_id
         WHERE  ha.a_status_service = 3  and DATE_FORMAT(ha.a_atcreate,'%Y-%m-%d') = '".fecha(1)."' and ha.a_user='".$this->user_data->u_id."' and (h.f_name LIKE '%".$valor."%' or h.f_code LIKE '%".$valor."%') ";
        }
        //tabla
//        $consulta_primary =  "select * from house As h
//    JOIN status as s ON h.f_status_actual=s.s_id
//     JOIN house_assignment ha on h.f_id = ha.a_house and ha.a_status_service
//         WHERE  ha.a_status_service = 3 and ha.a_user='".$this->user_data->u_id."' and (h.f_name LIKE '%".$valor."%' or h.f_code LIKE '%".$valor."%') ";

        $dataget         = $this->general->query("{$consulta_primary}  LIMIT $start,$length",'obj');
        $query_count = $this->general->query($consulta_primary);
        $total_registros = count($query_count);


        foreach($dataget as $rows){
            $id        = encriptar($rows->a_id);
            $code      = $this->class_security->decodificar($rows->a_code);
            $filial    = $rows->f_name;
            $user      = $rows->u_name ?? 'Sin asignar';
            $timea     = ($rows->a_time) ?? '0';
            $timeType  = ' Min';

            $timee     = (isset($rows->a_ending) and (strlen($rows->a_ending) > 10) and $rows->a_ending != null) ? $this->class_security->countdown($rows->a_take,$rows->a_ending) . ' Min' :  '-';
            $fecha     = $this->class_security->substr($rows->a_atcreate,0,10);
            $status    = $this->class_security->array_data($rows->a_status_service,$this->class_data->cleaning,$this->class_data->estado_default);


            $data[]= array(
                $filial,
//                $code,
                $user,
                $rows->a_take,
                $rows->a_ending,
                $timee,
                $rows->a_atcreate,
                "<span class='{$status['class']}'>{$status['title']}</span>",
                "
                 <button type='button' onclick='$(this).forms_modal({\"page\" : \"clean_task\",\"data1\" : \"{$id}\",\"title\" : \"Detalle de Actividad\"})' class='btn btn-primary btn-sm'  data-toggle='tooltip' data-placement='top' title='Visualizar'><i class='fa fa-eye text-white'></i></button>
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

    #logs de ticket
    function datatable(){
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
            $dato = '';
            $order = array();
        }
        $data = array();


//        echo $this->user_data->u_profile;
        if($this->user_data->u_profile == 5){
            // perfil tecnico de mantenimiento
            $consulta_primary =  "select h.*,em.*,e.s_color,e.s_name,ua.u_name As 'au_name',u.u_name from house_maintence As h
    LEFT JOIN house_maintence_employe As em ON h.m_id=em.hma_assigne
    JOIN status As e On h.m_complete=e.s_id
    LEFT JOIN users As u ON em.hma_user=u.u_id
    LEFT JOIN users As ua ON h.m_user_request=ua.u_id
    where (h.m_user_request='".$this->user_data->u_id."' or em.hma_user='".$this->user_data->u_id."') and (h.m_code LIKE '%".$valor."%' or h.m_atcreated LIKE '%".$valor."%') ORDER BY em.hma_status ASC";

        }else if($this->user_data->u_profile == 12 and $this->user_data->u_area == 0){
            // perfil gerencia
            $consulta_primary =  "select h.*,em.*,e.s_color,e.s_name,ua.u_name As 'au_name',u.u_name from house_maintence As h
    LEFT JOIN house_maintence_employe As em ON h.m_id=em.hma_assigne
    JOIN status As e On h.m_complete=e.s_id
    LEFT JOIN users As u ON em.hma_user=u.u_id
    LEFT JOIN users As ua ON h.m_user_request=ua.u_id
    where em.hma_status=3 and (h.m_code LIKE '%".$valor."%' or h.m_atcreated LIKE '%".$valor."%')";

        }else if(in_array($this->user_data->u_profile,[2,6])){
            // perfil administrador de mantenimiento
            $consulta_primary =  "select h.*,em.*,e.s_color,e.s_name,ua.u_name As 'au_name',u.u_name from house_maintence As h
    LEFT JOIN house_maintence_employe As em ON h.m_id=em.hma_assigne and em.hma_status IN(1,2,3,4)
    JOIN status As e On h.m_complete=e.s_id  
    LEFT JOIN users As u ON em.hma_user=u.u_id 
    LEFT JOIN users As ua ON h.m_user_request=ua.u_id 
    where h.m_code LIKE '%".$valor."%' or h.m_atcreated LIKE '%".$valor."%'
     OR (u.u_name LIKE '%".$valor."%' or ua.u_name LIKE '%".$valor."%') ORDER BY em.hma_status ASC";

        }
        else{
            // todos los que han generado ticket propios
            $consulta_primary =  "select h.*,em.*,e.s_color,e.s_name,ua.u_name As 'au_name',u.u_name from house_maintence As h
    LEFT JOIN house_maintence_employe As em ON h.m_id=em.hma_assigne and em.hma_status IN(1,2,3,4)
    JOIN status As e On h.m_complete=e.s_id  LEFT JOIN users As u ON em.hma_user=u.u_id LEFT JOIN users As ua ON h.m_user_request=ua.u_id where h.m_user_request='".$this->user_data->u_id."' and (h.m_code LIKE '%".$valor."%' or h.m_atcreated LIKE '%".$valor."%') ORDER BY em.hma_status ASC";
        }


        $dataget         = $this->general->query("{$consulta_primary}  LIMIT $start,$length",'obj');
        $query_count = $this->general->query($consulta_primary);
        $total_registros = count($query_count);


        foreach($dataget as $rows){
            $id        = encriptar($rows->m_id);
            $code      = $this->class_security->decodificar($rows->m_code);
            $q_user    = $rows->au_name;
            $user      = $rows->u_name ?? 'Sin asignar';
            $timea     = ($rows->hma_time) ?? '';
            $ticket_date_assigne     = ($rows->hma_atcreate) ?? '';
            $timeType = ($rows->hma_type == 1) ? ' Min' : '';
            $ticketa  = $rows->m_atcreated;

            $timee     = (isset($rows->hma_ending) and (strlen($rows->hma_ending) > 10) and $rows->hma_ending != null) ? $this->class_security->countdown($rows->hma_take,$rows->hma_ending) . ' Min' :  '-';
            $status    = $this->class_security->array_data($rows->hma_status,$this->class_data->working,$this->class_data->estado_default);

            $buttonBack = (in_array($this->user_data->u_area_type,[1,3]) and $rows->hma_status == 4) ? "<button type='button' onclick='$(this).task_back(\"{$id}\")' class='btn btn-danger btn-sm'  data-toggle='tooltip' data-placement='top' title='Regresar'><i class='fa fa-rotate-left text-white'></i></button>" : '';

            $data[]= array(
                $code,
                $q_user,
                $user,
                $ticketa,
                $ticket_date_assigne,
                $timea . $timeType,
                $timee,
                "<span class='{$status['class']}'>{$status['title']}</span>",
                "
                <div class='btn-group'>
                {$buttonBack}
                 <button type='button' onclick='$(this).forms_modal({\"page\" : \"task_log\",\"data1\" : \"{$id}\",\"title\" : \"Detalle de Actividad\"})' class='btn btn-primary btn-sm'  data-toggle='tooltip' data-placement='top' title='Visualizar'><i class='fa fa-eye text-white'></i></button>
                 </div>
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
    #logs de ticket
    function datatable_my_ticket(){
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
            $dato = '';
            $order = array();
        }
        $data = array();



        $consulta_primary =  "select h.*,em.*,e.s_color,e.s_name,ar.a_name,ua.u_name As 'au_name',u.u_name from house_maintence As h
    JOIN areas As ar ON h.m_area=ar.a_id
    LEFT JOIN house_maintence_employe As em ON h.m_id=em.hma_assigne
    JOIN status As e On h.m_complete=e.s_id
    LEFT JOIN users As u ON em.hma_user=u.u_id
    LEFT JOIN users As ua ON h.m_user_request=ua.u_id
    where h.m_user_request='".$this->user_data->u_id."' and (h.m_code LIKE '%".$valor."%' or h.m_atcreated LIKE '%".$valor."%') 
    ORDER BY em.hma_status ASC
    ";


        $dataget         = $this->general->query("{$consulta_primary}  LIMIT $start,$length",'obj');
        $query_count = $this->general->query($consulta_primary);
        $total_registros = count($query_count);


        foreach($dataget as $rows){
            $id        = encriptar($rows->m_id);
            $code      = $this->class_security->decodificar($rows->m_code);
            $area    = $rows->a_name;
            $q_user    = $rows->au_name;
            $user      = $rows->u_name ?? 'Sin asignar';
            $timea     = ($rows->hma_time) ?? '0';
            $timeType = ($rows->hma_type == 1) ? ' Min' : '';

            $timee     = (isset($rows->hma_ending) and (strlen($rows->hma_ending) > 10) and $rows->hma_ending != null) ? $this->class_security->countdown($rows->hma_take,$rows->hma_ending) . ' Min' :  '-';
            $fecha     = $this->class_security->substr($rows->m_atcreated,0,10);
            $status    = $this->class_security->array_data($rows->hma_status,$this->class_data->working,$this->class_data->estado_default);


            $data[]= array(
                $code,
                $q_user,
                $area,
                $timea . $timeType,
                $timee,
                $fecha,
                "<span class='{$status['class']}'>{$status['title']}</span>",
                "
                 <button type='button' onclick='$(this).forms_modal({\"page\" : \"task_log\",\"data1\" : \"{$id}\",\"title\" : \"Detalle de Actividad\"})' class='btn btn-primary btn-sm'  data-toggle='tooltip' data-placement='top' title='Visualizar'><i class='fa fa-eye text-white'></i></button>
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


}