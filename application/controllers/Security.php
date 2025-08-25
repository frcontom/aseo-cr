<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Security extends CI_Controller
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

        $this->user_data = $this->general->get('users',array('u_id' => $this->session_id));
    }

    function assigne_status(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('filial','status'))){

                //campos post
                $id          = $this->class_security->data_form('filial','int');
                $status      = $this->class_security->data_form('status','int');
                $fname      = $this->class_security->data_form('fname');
                $sname      = $this->class_security->data_form('sname');

                //validar si la filial esta bloqueda pero la coloca libre liberar

//                $filial = $this->general->get('house',array('f_id' => $id));
//                if(isset($filial->f_status_actual) and $filial->f_status_actual == 7){
//                    $data_complete = ['f_status_actual' => 1];
//                }else{
//                    $data_complete = [
//                        'f_status_actual' => $status
//                        ,'f_dirty' => fecha(2)
//                    ];
//
//                }
//                if($status != 90):
                    //assigne clean door
//                    $fls = $this->general->get('house', array('f_id' => $id),'array');
                $fls = $this->general->query("select CASE
                    WHEN ha.a_user = null THEN h.f_user
                     WHEN h.f_user <> ha.a_user THEN ha.a_user
                     ELSE h.f_user
                     END as f_user from house As h
         LEFT JOIN house_assignment As ha On h.f_id=ha.a_house and ha.a_status_service IN(1,2) and ha.a_revision_status=1
         LEFT JOIN house_assignment_revision As hr ON ha.a_id=hr.har_assignment
         WHERE h.f_id='{$id}'",'array',true);

                    if(count($fls) >= 1) {
                        //validar la asignacion si esta ya esta ejecutada se actualiza si no se crea


                        //tener en cuenta que si la falial ya tiene una asignacion y fue re-asignada permanece el usuario actual para esa asignacion si no el nuevo


                        $data = array(
                            'a_user' => $fls['f_user'],
                            'a_house' => $id,
                            'a_status' => $status,
                            'a_status_service' => 1,
                            'a_code' => $this->class_security->generate_code(6),
                            'a_time' => 0,
                            'a_assigner' => fecha(2),
                            'a_atcreate' => fecha(2),
                        );

                        $this->general->create_update('house_assignment', [
                            'a_house' => $id,
                            'a_status_service' => 1
                        ], $data);


    //                    $userdf[] = $this->general->get('users',array('u_id' => $fls['f_user']),'array');
                        $userdf = $this->general->all_get('users',['u_id' => $fls['f_user']],[],'array',[],[],'u_id,u_phone');
                        //Queue library
                        $job_data = array(
                            'message' => "Realizar limpieza en la Habitacion {$fname}",
                            'data' => $userdf
                        );
                        $this->queue->enqueue('send_whatsapp', 'Notifications', $job_data);  // Agregar trabajo a la cola



    //                    sendNotification('Vora',"Realizar limpieza en la Habitacion {$fname}",$userdf);
                    }

                    $this->general->create('house_reception',[
                        'r_house' => $id
                        ,'r_user' => $this->session_id
                        ,'r_dirty' =>  fecha(2)
                        ,'r_status' => $status
                    ]);

                    $queryEmail = $this->general->all_get('users',['u_profile' => 6],[],'array',[],[],'u_id,u_phone');
    //                $queryEmail = [
    //                    ['u_phone' => '573128624981']
    //                ];

                    $job_data = array(
                        'message' => "La habitación {$fname} - Estado ' {$sname} ' requiere  limpieza",
                        'data' => $queryEmail
                    );
                    $this->queue->enqueue('send_whatsapp', 'Notifications', $job_data);  // Agregar trabajo a la cola

    //                sendNotification('Vora',,$queryEmail);

                    //get alls users

                    $this->result =  $this->general->update('house',array('f_id' => $id),['f_status_actual' => $status]);


//                else:
//                    $this->result = array('success' => 1,'filial' => $id,'msg' => 'La habitación ya se encuentra libre');
//            endif;


            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }
    function change_status(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('filial','status'))){

                //campos post
                $id          = $this->class_security->data_form('filial','int');
                $status      = $this->class_security->data_form('status','int');
                if(in_array($status,[1,2])){
                    if($status == 1) {
                        $this->result =  $this->general->update('house',array('f_id' => $id),array('f_status_actual' => 3,'f_available' => $status));
                    }else{
                        $this->result =  $this->general->update('house',array('f_id' => $id),array('f_status_actual' => 7,'f_available' => $status));

                    }

                }else{
                    $this->result = array('success' => 2,'msg' => 'El procedimiento que desea realizar no es valido');
                }
            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }
    function comment_add(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('fname','filial'))){

                //campos post
                $id              = $this->class_security->data_form('filial','int');
                $fname           = $this->class_security->data_form('fname');
                $task            = $this->class_security->data_form('task','alone');
                $observation     = $this->class_security->data_form('observation');
//


//
//                if(strlen($observation) == 0){
//                    //delete comment and task
//                    $this->general->delete('house_assignment_comment',['hc_filial' => $id]);
////                    $this->general->delete('house_assignment_comment_task',['hct_filial' => $id]);
//                }else{


                    $queryEmail = $this->general->all_get('users',['u_profile' => 6],[],'array',[],[],'u_id,u_phone');
//                    sendNotification('Vora',,$queryEmail);
                    if(strlen($observation) > 3){
                        $job_data = array(
                            'message' => "La Habitación {$fname} tiene un comentario",
                            'data' => $queryEmail
                        );

                        $this->queue->enqueue('send_whatsapp', 'Notifications', $job_data);  // Agregar trabajo a la cola
                    }

                    $comment =  $this->general->create_update('house_assignment_comment',['hc_filial' => $id,'hc_status' => 1],array(
                        'hc_user' => $this->session_id,
                        'hc_filial' => $id,
                        'hc_status' => 1,
                        'hc_comment' => $observation,
                        'hc_atcreate' => fecha(2),
                    ));

//                    $comnet_id  = $comment['data'];
                    $this->general->delete('house_assignment_comment_task',['hct_filial' => $id]);//Delete previous task
                    if(isset($task) and is_array($task) and count($task) >= 1){
                        foreach ($task as $key => $value){
                            if(strlen($value) > 1){
                                $this->general->create('house_assignment_comment_task',[
                                    'hct_filial'  => $id,
//                                    'hct_comment' => $comnet_id,
                                    'hcr_title'   => $value,
                                    'hrc_timer'   => fecha(2),
                                ]);
                            }
                        }
                    }
//                }
//                else{
                    $this->result = array('success' => 1,'filial' => $id,'msg' => 'Comentario Agregado');
//                }
            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

}