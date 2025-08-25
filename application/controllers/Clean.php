<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Clean extends CI_Controller
{

    //propiedades
    private $session_id;
    private $session_token;
    private $user_data;
    private $controllName = 'clean';

    public function __construct(){
        parent::__construct();
        $this->session_id     = $this->session->userdata('user_id');
        $this->session_token  = $this->session->userdata('user_token');
        $this->project        = $this->config->config['project'];

        //validacion de acceso
        auth();

        $this->user_data = $this->general->get('users',array('u_id' => $this->session_id));
    }


    function change_status(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('name','filial','assigment','status'))){

                //campos post
                $id          = $this->class_security->data_form('filial','int');
                $assigment   = $this->class_security->data_form('assigment','int');
                $status      = $this->class_security->data_form('status','int','1');
                $fname        = $this->class_security->data_form('name');
                $comment     = $this->class_security->data_form('comment');

                $data = $this->general->get('house_assignment',['a_house' => $id,'a_id' => $assigment,'a_user' => $this->session_id],'array');
                if(isset($data) and !empty($data)){

                    if($status == 1) {
                        $actual = ['a_status_service' => 2,'a_take' => fecha(2)];
                    } else {

                        //validar el status que trae la orden de asignamiento.

//                        $status_work = $this->class_security->change_status_clean($data['a_status']);
//
//                        $actual = ['a_status_service' => 3,'a_ending' => fecha(2),'a_observation_clean' => $comment];
//                        $this->general->update('house',array('f_id' => $id),array('f_status_actual' => $status_work));
//
//                        $this->general->update('house_assignment_comment',['hc_filial' => $id,'hc_status' => 1],['hc_status' => 2]);

                        $userd = $this->general->query("
                        (select u1.u_phone from  users As u1  where u1.u_profile='6' having u1.u_phone IS NOT NULL)
                        UNION ALL
                        (select u1.u_phone from  users As u1  where u1.u_profile IN(4,14) having u1.u_phone IS NOT NULL)
                        ");

//                        sendNotification('Vora',"Limpieza finalizada en la Habitación {$fname} Proceder con la revisión",$userd);


                        $job_data = array(
                            'message' => "Limpieza finalizada en la Habitación {$fname} Proceder con la revisión",
                            'data' => $userd
                        );
                        $this->queue->enqueue('send_whatsapp', 'Notifications', $job_data);  // Agregar trabajo a la cola

                    }


                    $filial = $this->general->get('house',['f_id' => $id],'array');
                    $this->general->update('house_assignment',['a_house' => $id,'a_id' => $assigment],$actual);


//                    $users = $this->general->query("select u_phone from users where u_status = 1 and u_profile = 6 and u_notify=2",'array',false);
                    $this->result =  array('success' => 1,'filial' => $filial['f_id'],'name' => $filial['f_name']);
//                    $this->result =  array('success' => 1,'filial' => $filial['f_id'],'name' => $filial['f_name'],'tokens' => $users);
                }else{
                    $this->result = array('success' => 2,'msg' => 'No tienes permisos para realizar esta accion');
                }

            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function change_status_hand(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('filial','assigment'))){

                //campos post
                $id          = $this->class_security->data_form('filial','int');
                $assigment   = $this->class_security->data_form('assigment','int');

                if($this->general->exist('house_assignment',['a_house' => $id,'a_id' => $assigment,'a_user' => $this->session_id])){

                    /*
                     * 1 -> terminar el trabajo
                     * 2-> dejar la filial como no molestar en el estado actual
                     * */
//                    $this->general->update('house',array('f_id' => $id),array('f_status_actual' => 7,'f_dirty' => null));
                    $this->general->update('house_assignment',['a_house' => $id,'a_id' => $assigment],['a_status_service' => 7,'a_take' => fecha(2),'a_ending' => fecha(2)]);
                    $this->general->update('house',['f_id' => $id],['f_status' => 4]);
                    $this->result = array('success' => 1,'filial' => $id);

                }else{
                    $this->result = array('success' => 2,'msg' => 'No tienes permisos para realizar esta accion');
                }
            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }
    function clean_busy(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('filial','assigment'))){

                //campos post
                $id          = $this->class_security->data_form('filial','int');
                $assigment   = $this->class_security->data_form('assigment','int');

                $data = $this->general->query("select * from house_assignment As ha JOIN house As h ON ha.a_house=h.f_id  WHERE ha.a_house='{$id}' and ha.a_id='{$assigment}'",'array',true);
//                print_r($data);exit;

                if(isset($data) and count($data) >= 1) {
//
                    $status_work = $this->class_security->change_status_clean($data['f_status_actual']);

                    $this->general->update('house',array('f_id' => $id),array('f_status_actual' => $status_work));
                    $this->general->update('house_assignment',['a_house' => $id,'a_id' => $assigment],['a_status_service' => 3,'a_revision_status' => 2,'a_take' => fecha(2)]);
                    $this->result = array('success' => 1,'filial' => $id);
//
                }else{
                    $this->result = array('success' => 2,'msg' => 'No tienes permisos para realizar esta accion');
                }
            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }
    function comment_task_save(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('filial','fname'))){

                //campos post
                $id         = $this->class_security->data_form('filial','int');
                $comment    = $this->class_security->data_form('comentario');
                $fname    = $this->class_security->data_form('fname');
                $task       = $this->class_security->data_form('task','alone');
                $evidencia =  $this->class_security->upload_image('evidencia','_files/taks/');

                //vadalidate status of assignment
                $data = $this->general->query("select * from house As h JOIN house_assignment As hs ON h.f_id=hs.a_house WHERE h.f_id='{$id}' and hs.a_status_service IN(1,2) and hs.a_user='{$this->session_id}'",'array',true);
//                print_r($data);exit;
                //validate if status is in progress
                if(isset($data) and !empty($data)){
                    $assigment = $data['a_id'];
                    if($data['a_status_service'] == 1){
                        //cambiar el estado a en proceso
                        $actual = ['a_status_service' => 2,'a_take' => fecha(2),'a_revision_status' => 1];
                    }else{

                        //cuando la tarea finaliza entra en un nuevo estado llamado pre-finalizado
                        $actual = ['a_status_service' => 3,'a_revision_status' => 1,'a_ending' => fecha(2),'a_observation_clean' => $comment,'a_response_attachment' => $evidencia];

                        $this->general->create_update('house_assignment_revision',['har_filial' => $id,'har_assignment' => $data['a_id']],[
                            'har_filial'         => $id,
                            'har_user'           => $this->user_data->u_id,
                            'har_assignment'     => $data['a_id'],
                            'har_before_status' => $data['f_status_actual'],
//                            'har_after_status'  => $id
                        ]);

                        //cambiar el estado de la filial a revision
                        $this->general->update('house',array('f_id' => $id),array('f_status_actual' => 14));

                        //cambiar el estado del comentario a finalizado
                        $this->general->update('house_assignment_comment',['hc_filial' => $id,'hc_status' => 1],['hc_status' => 2,'hc_assignment' => $data['a_id']]);

                        //obtener la lista de usuarios a notificar
                        $userd = $this->general->query("
                        (select u1.u_phone from  users As u1  where u1.u_profile='6' having u1.u_phone IS NOT NULL)
                        UNION ALL
                        (select u1.u_phone from  users As u1  where u1.u_profile IN(4,14) having u1.u_phone IS NOT NULL)
                        ");

                        $job_data = array(
                            'message' => "Limpieza finalizada en la Habitación {$fname} Proceder con la revisión",
                            'data' => $userd
                        );
                        $this->queue->enqueue('send_whatsapp', 'Notifications', $job_data);  // Agregar trabajo a la cola



                        //cambiar task
                        if(isset($task) and is_array($task) and count($task) >= 1){
                            foreach ($task as $value) {
                                if(isset($value['id']) and isset($value['tk']) and $value['tk'] == 'on'){
                                    $this->general->update('house_assignment_comment_task',['hct_filial' => $id,'hrc_status' => 1],
                                        [
                                            'hct_assignment' => $data['a_id'],
                                            'hrc_status' => 2,
                                            'hcr_user' => $this->user_data->u_id,
                                            'hrc_timer_task' => fecha(2)
                                        ]);
                                }
                            }
                        }

                    }


                    //update general data
                    $this->general->update('house_assignment',['a_house' => $id,'a_id' => $assigment],$actual);
                    $this->result =  array('success' => 1,'filial' => $id);
                }else{
                    $this->result = array('success' => 2,'msg' => 'No se puede realizar un proceso sobre esta filial en el momento');
                }




                $this->result = array('success' => 1,'filial' => $id);
            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

}