<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Assignment extends CI_Controller
{


    //propiedades
    private $session_id;
    private $session_token;
    private $user_data;
    private $controllName = 'Asignación';
    private $controller   = 'assignment/';
    public $project;
    private $result = array();

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

//    function index() {
//        $data_head = array(
//            'titulo'        => $this->project['tiulo'],
//            'modulo'        => $this->controllName,
//            'user'          => $this->user_data
//        );
//
//        $data_body = array(
//            'dataresult'     => $this->general->all_get('status'),
//            'floors'         => $this->general->all_get('floor'),
//            'userclean'      => $this->general->all_get('users',['u_status'=>1,'u_profile'=>2]),
//            'timer'          => new Timer(),
//            'filials'    => $this->general->query("select * from house As h
//    JOIN status as s ON  h.f_status_actual=s.s_id JOIN floor As f ON h.f_floor=f.fr_id
//    WHERE h.f_status = 1",'Obj',false),
//            'crud' => array(
//                'url_modals'    => base_url("modal/"),
//                'url_save'      => base_url("{$this->controller}save"),
//            )
//        );
//
//        $data_foot = array('script_level' => array('cr/crud_data.js','cr/datatable_ajax.js','cr/local.js','cr/assigment.js'));
//
//        $this->load->view('shared/template/v_header',$data_head);
//        $this->load->view('shared/template/v_menu');
//        $this->load->view('admin/assigmment/v_assigmment',$data_body);
//        $this->load->view('shared/template/v_footer',$data_foot);
//    }

    function rooms() {
        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => $this->controllName,
            'user'          => $this->user_data
        );

        $data_body = array(
            'users'      => $this->general->all_get('users',['u_status'=>1,'u_profile'=>3],[],'array'),
            'datas'    => $this->general->query("select h.*,f.fr_name,u.u_name from house As h LEFT JOIN users As u ON u.u_id = h.f_user LEFT JOIN floor As f On h.f_floor = f.fr_id",'Obj',false),
            'crud' => array(
                'url_modals'    => base_url("modal/"),
                'url_save'      => base_url("{$this->controller}rooms_save"),
            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','vendor/select2/js/select2.full.min.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('supervisor_clean/v_rooms',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }

    function rooms_assign() {
        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => $this->controllName,
            'user'          => $this->user_data
        );

        $today = fecha(1);
        $data_body = array(
            'timer'    => new Timer(),
            'users'      => $this->general->all_get('users',['u_status'=>1,'u_profile'=>3],[],'array'),
            'datas'    => $this->general->query("select * from users As u
JOIN house_assignment As h ON u.u_id = h.a_user
JOIN house As h2 ON h.a_house = h2.f_id where DATE_FORMAT(a_assigner,'%Y-%m-%d') = '{$today}'
",'Obj',false),
            'crud' => array(
                'url_modals'    => base_url("modal/"),
                'url_save'      => base_url("{$this->controller}rooms_save"),
            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','vendor/select2/js/select2.full.min.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('supervisor_clean/v_rooms_assign',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }

    function rooms_save(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('user'))){

                //campos post
                $user   = $this->class_security->data_form('user','int');
                $rooms     = $this->class_security->data_form('rooms','alone');


                if($user != '' && isset($rooms) && count($rooms) >= 1) {


                    foreach ($rooms as $room){
                        $this->general->update('house',array('f_id' => $room),array('f_user' => $user));
                    }

                    $this->result = array('success' => 1);
                }else{
                    $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
                }
            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function ready_revition() {
        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => $this->controllName,
            'user'          => $this->user_data
        );

        $data_body = array(
            'dataresult'     => $this->general->all_get('status'),
            'floors'         => $this->general->all_get('floor'),
            'userclean'      => $this->general->all_get('users',['u_status'=>1,'u_profile'=>2]),
            'filials'       => $this->general->query("select h.*,s.*,f.*,u.u_name,h.f_status_actual,ha.a_user,ha.a_id,ha.a_status_service,ha.a_assigner,ha.a_take,COUNT(ha.a_id) As working from house As h
    JOIN status as s ON h.f_status_actual=s.s_id
    JOIN floor As f ON h.f_floor=f.fr_id
    JOIN users As u ON h.f_user=u.u_id
    LEFT JOIN house_assignment ha on h.f_id = ha.a_house AND ha.a_status_service IN(3)  and a_complete = 1
    WHERE h.f_status = 1 GROUP BY h.f_id,u.u_name,h.f_status_actual,ha.a_user,ha.a_id,ha.a_status_service,ha.a_assigner,ha.a_take HAVING working = 1 limit 20",'Obj',false),
            'crud' => array(
                'url_modals'    => base_url("modal/"),
                'url_final_revision'      => base_url("{$this->controller}final_revision"),

            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/datatable_ajax.js','cr/local.js','cr/assigment.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('admin/assigmment/v_assigmment_revition',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }
    function cleaning_process() {
        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => $this->controllName,
            'user'          => $this->user_data
        );

        $data_body = array(
            'timer'          => new Timer(),
            'dataresult'     => $this->general->all_get('status'),
            'floors'         => $this->general->all_get('floor'),
            'userclean'      => $this->general->all_get('users',['u_status'=>1,'u_profile'=>2]),
             'filials'       => $this->general->query("select h.*,s.*,f.*,u.u_name,h.f_status_actual,ha.a_status_service,ha.a_assigner,ha.a_take,COUNT(ha.a_id) As working from house As h
    JOIN status as s ON h.f_status_actual=s.s_id
    JOIN floor As f ON h.f_floor=f.fr_id
    JOIN users As u ON h.f_user=u.u_id
    LEFT JOIN house_assignment ha on h.f_id = ha.a_house AND ha.a_status_service IN(1,2) and a_complete = 1
    WHERE h.f_status = 1 GROUP BY h.f_id,u.u_name,h.f_status_actual,ha.a_status_service,ha.a_assigner,ha.a_take HAVING working = 1",'Obj',false),
            'crud' => array(
                'url_modals'    => base_url("modal/"),
                'url_final_revision'      => base_url("{$this->controller}final_revision"),

            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/datatable_ajax.js','cr/local.js','cr/assigment.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('admin/assigmment/v_assigmment_cleaning',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }

//    function final_revision(){
//
//        if($this->input->post()){
//            if($this->class_security->validate_post(array('filial','user','assigment','status'))){
//
//                //campos post
//                $user            = $this->class_security->data_form('user','int');
//                $filial          = $this->class_security->data_form('filial','int');
//                $assigment       = $this->class_security->data_form('assigment','int');
//                $status          = $this->class_security->data_form('status','int');
//                $observation     = $this->class_security->data_form('observation');
//
//                if($this->general->exist('house_assignment',['a_id' => $assigment,'a_house' => $filial,'a_status_service' => 3])){
//
//                    //status service
//                    $status_service = $status == 1 ? 3 : 1;
//                    $status_work = $status == 2 ? 2 : 1;
//                    $status_complete =  $status == 1 ? 2 : 1;
//                    $this->general->update('house_assignment',array('a_id' => $assigment,'a_house' => $filial),['a_status_service' => $status_service,'a_complete' => $status_complete,'a_accordance' => $status,'a_observation' => $observation]);
//                    $this->general->create('house_assignment_observation',[
//                        'a_assignment' => $assigment,
//                        'a_user'       => $user,
//                        'a_house'      => $filial,
//                        'a_status'     => $status,
//                        'a_comment'    => $observation,
//                        'a_atdate'     => fecha(2)
//                    ]);
//
//                   $this->result =  $this->general->update('house',array('f_id' => $filial),array('f_status_actual' => 1,'f_work' => $status_work,'f_dirty' => null));
//                }else{
//                    $this->result = array('success' => 2,'msg' => 'Lo siento la filial no esta en reivisión');
//                }
//
//            }else{
//                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
//            }
//        }else{
//            $this->result = array('success' => 2,'msg' => 'Que haces!');
//        }
//        api($this->result);
//    }

    function bother_status(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('id','status'))){

                //campos post
                $filial   = $this->class_security->data_form('id','decrypt_int');
                $status   = $this->class_security->data_form('status','int');


                if($filial != '' && $status != '') {

                    $fls = $this->general->get('house', array('f_id' => $filial),'array');
                    if(count($fls) >= 1){
//                        //update status filial
                        $statusC = $status == 1 ? 4 : 1;
                        $statusA_AB = $status == 1 ? 7 : 1;
                        $statusA_BA = $status == 1 ? 1 : 7;


                        $this->general->update('house',['f_id' => $filial],['f_status' => $statusC]);
                        $this->general->update('house_assignment',['a_house' => $filial,'a_status_service' => $statusA_BA],['a_status_service' => $statusA_AB]);

                        $this->result =  ['success' => 1,'filial' => $filial];

                    }else{
                        $this->result = array('success' => 2,'msg' => 'Lo siento la filial no existe');
                    }

                }else{
                    $this->result = array('success' => 2,'msg' => 'Campos Obligatorios 1');
                }
            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios 2');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function save(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('id','name','status','type','user','hour'))){

                //campos post
                $filial   = $this->class_security->data_form('id');
                $fname   = $this->class_security->data_form('name');
                $status   = $this->class_security->data_form('status','int');
                $hour     = $this->class_security->data_form('hour','int');
                $type     = $this->class_security->data_form('type','int');
                $user     = $this->class_security->data_form('user','int');


                if($filial != '' && $status != '' && $type != ''  && $hour != '') {

                    $fls = $this->general->get('house', array('f_id' => $filial),'array');
                    if(count($fls) >= 1){
                        //validar la asignacion si esta ya esta ejecutada se actualiza si no se crea
                        $data = array(
                            'a_user'    => $user,
                            'a_house'   => $filial,
                            'a_status'  => $status,
                            'a_status_service'  => 1,
                            'a_code'    => $this->class_security->generate_code(6),
                            'a_time'    => $hour,
                            'a_assigner'=> fecha(2),
                            'a_atcreate'=> fecha(2),
                        );
                        $this->general->create_update('house_assignment',[
                            'a_house' => $filial,
                            'a_status_service' => 1
                        ],$data);

//                        $userd = $this->general->get('users',array('u_id' => $user),'array');
                        $userd = $this->general->all_get('users',['u_id' => $user],[],'array',[],[],'u_id,u_phone');
//                        sendNotification('Vora',"Realizar limpieza en la Habitación {$fname}",$userd);

                        $job_data = array(
                            'message' => "Realizar limpieza en la Habitación {$fname}",
                            'data' => $userd
                        );
                        $this->queue->enqueue('send_whatsapp', 'Notifications', $job_data);  // Agregar trabajo a la cola


                        $this->result =  ['success' => 1,'status' => $userd['u_notify']];
                    }else{
                        $this->result = array('success' => 2,'msg' => 'Lo siento la filial no existe');
                    }

                }else{
                    $this->result = array('success' => 2,'msg' => 'Campos Obligatorios 1');
                }
            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios 2');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function filial_blocked_status(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('id','status'))){

                //campos post
                $filial        = $this->class_security->data_form('id');
                $status        = $this->class_security->data_form('status');
                $observation   = $this->class_security->data_form('observation','str2');

                if($filial != '') {

                    $fls = $this->general->get('house', array('f_id' => $filial),'array');
                    if(count($fls) >= 1){

                        $statusCode =  $status == 1 ? 3 : 1;

                        if($status == 1){
                            $this->general->create('house_blocked',[
                                'house_id' => $filial,
                                'user' => $this->session_id,
                                'status' => $status,
                                'reason' => $observation,
                                'created_at' => fecha(2),
                                'updated_at' => fecha(2),
                            ]);
                        }else{
                            $this->general->update('house_blocked',array('house_id' => $filial),array('status' => $status));
                        }


                        //obtener la lista de usuarios a notificar
//                        $userd = $this->general->query("
//                        (select u1.u_phone from  users As u1  where u1.u_profile='6' having u1.u_phone IS NOT NULL)
//                        UNION ALL
//                        (select u1.u_phone from  users As u1  where u1.u_profile IN(4,14) having u1.u_phone IS NOT NULL)
//                        ");
//
//                        $job_data = array(
//                            'message' => "Limpieza finalizada en la Habitación {$fname} Proceder con la revisión",
//                            'data' => $userd
//                        );
//                        $this->queue->enqueue('send_whatsapp', 'Notifications', $job_data);  // Agregar trabajo a la cola


                        $this->result =  $this->general->update('house',array('f_id' => $filial),array('f_status' => $statusCode));

                    }else{
                        $this->result = array('success' => 2,'msg' => 'Lo siento la filial no existe');
                    }

                }else{
                    $this->result = array('success' => 2,'msg' => 'Campos Obligatorios 1');
                }
            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios 2');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }
    function close_status(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('name','filial','assigment'))){
                //campos post
                 $filial   = $this->class_security->data_form('filial','decrypt_int');
                 $assigment   = $this->class_security->data_form('assigment','decrypt_int');
                 $fname   = $this->class_security->data_form('name');



                if($filial != '' || $assigment != '') {

                    $fls = $this->general->get('house', array('f_id' => $filial),'array');
                    if(count($fls) >= 1){

                        $statusF = $this->class_security->change_status_clean($fls['f_status_actual']);
                            $this->general->create('house_assignment_close',[
                                'hac_user' => $this->session_id,
                                'hac_filial' => $filial,
                                'hac_assigment' => $assigment,
                                'hac_atcreate' => fecha(2),
                            ]);


                        $queryEmail = $this->general->all_get('users',['u_profile' => 6],[],'array',[],[],'u_id,u_phone');
//                        sendNotification('Vora',"La Habitación {$fname} esta limpia",$queryEmail);

                        $job_data = array(
                            'message' => "La Habitación {$fname} esta limpia",
                            'data' => $queryEmail
                        );
                        $this->queue->enqueue('send_whatsapp', 'Notifications', $job_data);  // Agregar trabajo a la cola


                            $this->general->update('house',array('f_id' => $filial),array('f_status_actual' => $statusF));

                            if($assigment != 0){
                                $this->general->update('house_assignment',array('a_id' => $assigment,'a_house' => $filial),['a_revision_status' => 2,'a_status' => 3,'a_status_service' => 3]);
                            }
                        $this->result = array('success' => 1);

                    }else{
                        $this->result = array('success' => 2,'msg' => 'Lo siento la filial no existe');
                    }

                }else{
                    $this->result = array('success' => 2,'msg' => 'Campos Obligatorios 1');
                }
            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios 2');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function change_status(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('filial'))){

                //campos post
                $filial   = $this->class_security->data_form('filial','int');
                if($filial != '') {
                    $this->result = $this->general->update('house',array('f_id' => $filial),array('f_status_actual' => 3));
                }else{
                    $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
                }
            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function resolved_filial(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('filial','assignment','task_status'))){

                $filial         = $this->class_security->data_form('filial','int');
                $assignment     = $this->class_security->data_form('assignment','int');
                $task_status    = $this->class_security->data_form('task_status','int');
                $task    = $this->class_security->data_form('task','alone');


                //validar si existe la asignacion
                $data = $this->general->query("select * from house_assignment As ha JOIN house_assignment_revision As hr ON ha.a_id=hr.har_assignment WHERE ha.a_house='{$filial}' and ha.a_id='{$assignment}'",'array',true);
                if(isset($data) and count($data) >= 1) {

                    if($task_status == 1){
                        //regresar a limpieza

                        //regresar la filial al estado previo
                        $this->general->update('house',array('f_id' => $filial),array('f_status_actual' => $data['har_before_status']));

                        //actualizar la asignacion
                        $this->general->update('house_assignment',array('a_id' => $assignment,'a_house' => $filial),array('a_status_service' => 1));

                        //regresar el estado del comentario
                        $this->general->update('house_assignment_comment',array('hc_assignment' => $assignment),array('hc_status' => 1));

                        //actualizar la asignacion de tareas
                        if(isset($task) and is_array($task) and count($task) >= 1){
                            foreach ($task as $value) {
                                if(isset($value['id']) and  !isset($value['tk'])){
                                    $this->general->update('house_assignment_comment_task',['hct_id' => $value['id']],['hrc_status' => 1,'hcr_user' => '']);
                                }
                            }
                        }

                    }else{
                        //finalizar revision

                        //cambiar el estado de la filial al inverso
                        $status_work = $this->class_security->change_status_clean($data['har_before_status']);
                        $this->general->update('house',array('f_id' => $filial),array('f_status_actual' => $status_work));

                        //cambiar el estado de la asignacion
                        $this->general->update('house_assignment',array('a_id' => $assignment,'a_house' => $filial),array('a_revision_status' => 2,'a_complete' => 2));

                        //finalizar la revision
                        $this->general->update('house_assignment_revision',array('har_assignment' => $assignment,'har_filial' => $filial),array('har_status' => 2,'har_atcreate' => fecha(2)));

                    }
                    $this->result = array('success' => 1,'filial' =>  $filial);
                }else{
                    $this->result = array('success' => 2,'msg' => 'Lo siento la asignación no existe');
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