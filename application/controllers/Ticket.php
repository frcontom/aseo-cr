<?php

class Ticket extends CI_Controller
{

    private $session_id;
    private $user_data;
    private $controllName        = '';
    public $project;
    private $result = array();

    public function __construct()
    {
        parent::__construct();

        $this->session_id     = $this->session->userdata('user_id');
        $this->session_token  = $this->session->userdata('user_token');
        $this->project        = $this->config->config['project'];

        //validacion de acceso
        auth();
        $this->load->library('Timer');
        $this->user_data = $this->general->get('users',array('u_id' => $this->session_id));
    }
    function ticket_admon(){
        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => $this->controllName,
            'controller'    => 'Dashboard',
            'user'          => $this->user_data,
            'style_level'   => array()
        );

        //Validate is area is user or gerencia
        $whereOr = ($this->user_data->u_area == 0) ? " m.m_area != 0" : " m.m_area='".$this->user_data->u_area."'";

        $data_body = array(
            'status_filial'     => '',
            'timer'      => new Timer(),
            'userclean'     => $this->general->all_get('users',['u_status'=>1,'u_profile'=>2]),
            'status'        => $this->general->all_get('status',['s_type' => 2],['s_id','DESC']),
            'filials'       => $this->general->query("select m.*,s.*,e.*,a.*,u.u_name,u.u_id,t.u_name As user_assigne from  house_maintence As m
    LEFT JOIN house_maintence_employe As e ON m.m_id=e.hma_assigne and e.hma_status IN(1,2,3,4)
    LEFT JOIN status As s ON m.m_status=s.s_id
    LEFT JOIN users as u ON e.hma_user=u.u_id
    JOIN areas As a ON m.m_area=a.a_id
        LEFT JOIN (select u.u_name,u.u_id from users As u) as t ON m.m_user_request=t.u_id
    where $whereOr
HAVING e.hma_status IN(1,2,3) or e.hma_status IS NULL",'array',false),
            'crud' => array(
                'url_modals'        => base_url("modal/"),
                'url_save'          => base_url("ticket/task_admon_assigne"),
                'url_revision'      => base_url("ticket/task_admon_revision"),
//                'url_save_resolver' => base_url("maintenance/save_resolver"),
                'url_notify'        => base_url("profile/notify_save"),
            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/maintenance.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('supervisor_maintenance/v_ticket_admin_dashboard',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }

    function ticket_resolved(){

        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => $this->controllName,
            'controller'    => 'Dashboard',
            'user'          => $this->user_data,
            'style_level'   => array()
        );

        $data_body = array(
            'status_filial'     => '',
            'timer'      => new Timer(),
            'userclean'     => $this->general->all_get('users',['u_status'=>1,'u_profile'=>2]),
            'status'        => $this->general->query("select s.* from status As s WHERE s.s_type = 2 order by s.s_id DESC",'object',false),
            'filials'       => $this->general->query("
(
select m.*,s.*,e.*,u.u_name from  house_maintence As m
    JOIN house_maintence_employe  As e ON m.m_id=e.hma_assigne and e.hma_status IN(1,2)
JOIN status As s ON m.m_status=s.s_id LEFT JOIN users as u oN e.hma_user=u.u_id  WHERE e.hma_user='".$this->session_id."' and m.m_complete != 3 and e.hma_type=1)
UNION ALL
(
select m.*,s.*,e.*,u.u_name from  house_maintence As m
    JOIN house_maintence_employe  As e ON m.m_id=e.hma_assigne and e.hma_status IN(1,2)
JOIN status As s ON m.m_status=s.s_id LEFT JOIN users as u oN e.hma_user=u.u_id  WHERE e.hma_user='".$this->session_id."' and m.m_complete != 3 and e.hma_type=2
 and DATE_FORMAT(e.hma_time,'%Y-%m-%d %H:%i:%S')  < '".fecha(2)."' )

",'array',false),
            'crud' => array(
                'url_modals'        => base_url("modal/"),
                'url_save'          => base_url("ticket/task_manager_resolved"),
                'url_notify'        => base_url("profile/notify_save"),
            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/maintenance.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('maintenance/v_dashboard',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }

    //ticket create
    function ticket_create(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('area','priority','descripcion'))){

                //campos post
                $area          = $this->class_security->data_form('area');
                $priority          = $this->class_security->data_form('priority');
                $descripcion      = $this->class_security->data_form('descripcion');
                $priority_name      = $this->class_security->data_form('priority_name');
                $code            = $this->class_security->codeGenerate();
                $dateq           = fecha(2);

                $data = $this->general->create('house_maintence',[
                    'm_user_request'    => $this->session_id,
                    'm_area'            => $area,
                    'm_status'          => $priority,
                    'm_code'            => $code,
                    'm_observation'     => $descripcion,
                    'm_complete'        => 1,
                    'm_atcreated'       => $dateq,
                ]);

                $id = $data['id'];

                //files
                $file[] =  $this->class_security->upload_image('imagen1','_files/maintenance/');
                $file[] =  $this->class_security->upload_image('imagen2','_files/maintenance/');
                $file[] =  $this->class_security->upload_image('imagen3','_files/maintenance/');
                $file[] =  $this->class_security->upload_image('imagen4','_files/maintenance/');

                $attachment =[];
                foreach($file  AS $fl){
                    if(strlen($fl) > 20){
                        $this->general->create('house_maintence_files',[
                            'mf_maintence' => $id,
                            'mf_file' => $fl,
                            'mf_type' => 1,
                            'mf_atcreated' => fecha(2),
                        ]);
                        $attachment[]  = [FCPATH . "_files/maintenance/" .$fl];
                    }
                }


                //send mailer user area
                //get all users
                $queryEmail = $this->general->all_get('users',['u_area' => $area,'u_area_type != ' => 2],[],'array',[],[],'u_id,u_phone,u_email,u_name');
                $queryEmail[] =  (array) $this->user_data;

                $dataEmail = [
                    'ticket'      => $code,
                    'priority'    => $priority_name,
                    'date'        => fecha(2),
                    'observation' => $descripcion,
                ];


                $job_data = array(
                    'message' => 'Tienes un nuevo ticket #'.$code . ' Con la prioridad '.$priority_name,
                    'data' => $queryEmail
                );
                $this->queue->enqueue('send_whatsapp', 'Notifications', $job_data);  // Agregar trabajo a la cola
//                sendNotification('Vora','Tienes un nuevo ticket #'.$code . ' Con la prioridad '.$priority_name,$queryEmail);


                //job send mail
                $job_data_mail = array(
                    'data'        => $dataEmail,
                    'message'     => 'Tienes un nuevo ticket #'.$code . ' Con la prioridad '.$priority_name,
                    'template'    => 'emails/v_ticket_create',
                    'attachment'  => $attachment,
                    'emails'      => $queryEmail
                );
                $this->queue->enqueue('send_mailer', 'Mailer', $job_data_mail);  // Agregar trabajo a la cola
//                $this->class_security->send_mailer($queryEmail,'Tienes un nuevo ticket #'.$code . ' Con la prioridad '.$priority_name,'emails/v_ticket_create',$attachment,$dataEmail);

                $this->result = array('success' => 1);

            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    //assigne ticket
    function task_admon_assigne(){
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
                $ticket_data = $this->general->all_get_join('house_maintence As m',[['house_maintence_employe As me','m.m_id=me.hma_assigne','LEFT'],['status As s','m.m_status=s.s_id']],['m.m_id' => $id,'m.m_complete != ' => 4],[],'alone');
                if(empty($ticket_data)){
                    $this->result = array('success' => 2,'msg' => 'Valida el Ticket ya que no se encuentra para asignar');
                    api($this->result);
                    exit;
                }

                if($ticket_data){
                    //asignar data job
                    $this->general->update('house_maintence',['m_id' => $id],['m_observation_super' => $descripcion,'m_status' => $priority]);

                    //asignar employ job
                    $this->general->create_update('house_maintence_employe',['hma_assigne'  => $id],[
                        'hma_assigne'       => $id,
                        'hma_user'          => $usuario,
                        'hma_type'          => $type,
                        'hma_status'        => 1,
                        'hma_time'          => $time,
                        'hma_time2'         => $timeend,
                        'hma_atcreate'      => fecha(2),
                    ]);


                    //Send email ticket
                    $queryEmail = $this->general->query("
                        (select u1.u_email,u1.u_name,u1.u_phone,2 As 'type' from house_maintence As m JOIN users As u1 ON m.m_user_request=u1.u_id  where m.m_id='".$id."')
                        UNION ALL
                        (select u1.u_email,u1.u_name,u1.u_phone,2 As 'type' from  users As u1 where u1.u_area='".$ticket_data->m_area."' and u1.u_area_type IN(1,2))
                        UNION ALL
                        (select u1.u_email,u1.u_name,u1.u_phone,1 As 'type' from house_maintence As m JOIN house_maintence_employe As me ON m.m_id=me.hma_assigne JOIN users As u1 ON me.hma_user=u1.u_id where me.hma_assigne='".$id."')
                    ");


                    //all $attachment
                    $attachment_query = $this->general->all_get('house_maintence_files',['mf_maintence' => $id,'mf_type' => 1]);
                    $attachment =[];
                    if(!empty($attachment_query)){
                        foreach($attachment_query as $attach){
                            $attachment[]  = [FCPATH . "_files/maintenance/" .$attach->mf_file];
                        }
                    }

                    $code = $ticket_data->m_code;
                    $priority_name = $ticket_data->s_name;
                    $userAssigne =  array_values(array_filter($queryEmail,function($rr){  return $rr['type'] == 1;}));

                    $dataEmail = [
                        'name_user' => $userAssigne[0]['u_name'],
                        'ticket' => $code,
                        'priority' => $priority_name,
                        'date' => fecha(2),
                        'observation' => $ticket_data->m_observation,
                    ];



                    $job_data = array(
                        'message' => 'Se Asigno el ticket #'.$code . ' Con la prioridad '.$priority_name,
                        'data' => $queryEmail
                    );
                    $this->queue->enqueue('send_whatsapp', 'Notifications', $job_data);  // Agregar trabajo a la cola
//                    sendNotification('Vora','Se Asigno el ticket #'.$code . ' Con la prioridad '.$priority_name,$queryEmail);


                    //job send mail
                    $job_data_mail = array(
                        'data'        => $dataEmail,
                        'message'     => 'Se Asigno el ticket #'.$code . ' Con la prioridad '.$priority_name,
                        'template'    => 'emails/v_ticket_assigne',
                        'attachment'  => $attachment,
                        'emails'      => $queryEmail
                    );
                    $this->queue->enqueue('send_mailer', 'Mailer', $job_data_mail);  // Agregar trabajo a la cola
//                    $this->class_security->send_mailer($queryEmail,'Se Asigno el ticket #'.$code . ' Con la prioridad '.$priority_name,'emails/v_ticket_assigne',$attachment,$dataEmail);

                    $this->result = array('success' => 1);

                }else{
                    $this->result = array('success' => 2,'msg' => 'Esta tarea no se puede asignar');
                }

            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }


    //resolved ticket
    function task_manager_resolved(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('id','assigne'))){

                //campos post
                $id             = $this->class_security->data_form('id','decrypt_int');
                $assigne        = $this->class_security->data_form('assigne','decrypt_int');
                $descripcion    = $this->class_security->data_form('descripcion');

                //validate maintence
                $data = $this->general->all_get_join('house_maintence As m',[['house_maintence_employe As me','m.m_id=me.hma_assigne'],['status As s','m.m_status=s.s_id']],['m.m_id' => $id,'m.m_complete != ' => 3],[],'alone','json');
                if(empty($data)){
                    $this->result = array('success' => 2,'msg' => 'Valida el Ticket ya que no se encuentra para asignar');
                    api($this->result);
                    exit;
                }
//                print_r($data);
//                exit;
                if(in_array($data->hma_status,[1,2])) {
                    if($data->hma_status == 1) {
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

                        //all $attachment
                        $attachment =[];

                        foreach($file  AS $fl){
                            if(strlen($fl) > 20){
                                $this->general->create('house_maintence_files',[
                                    'mf_maintence' => $id,
                                    'mf_file' => $fl,
                                    'mf_type' => 2,
                                    'mf_atcreated' => fecha(2),
                                ]);

                                $attachment[]  = [FCPATH . "_files/maintenance/" .$fl];
                            }
                        }

                        //finish Task user work send mail
                        $queryEmail = $this->general->query("
                        (select u1.u_email,u1.u_name,u1.u_phone,2 As 'type' from house_maintence As m JOIN users As u1 ON m.m_user_request=u1.u_id where m.m_id='".$id."')
                        UNION ALL
                        (select u1.u_email,u1.u_name,u1.u_phone,1 As 'type' from  users As u1 where u1.u_area='".$data->m_area."' and u1.u_area_type IN(1,2))
                        UNION ALL
                        (select u1.u_email,u1.u_name,u1.u_phone,1 As 'type' from house_maintence As m JOIN house_maintence_employe As me ON m.m_id=me.hma_assigne JOIN users As u1 ON me.hma_user=u1.u_id where me.hma_assigne='".$id."')
                        ");


                        $code = $data->m_code;
                        $priority_name = $data->s_name;
                        $userAssigne =  array_values(array_filter($queryEmail,function($rr){  return $rr['type'] == 1;}));
                        $dataEmail = [
                            'name_user' => $userAssigne[0]['u_name'],
                            'ticket' => $code,
                            'priority' => $priority_name,
                            'date' => $data->hma_atcreate,
                            'observation' => $data->m_observation,
                            'observation2' => $descripcion,
                        ];


                        $job_data = array(
                            'message' => 'Se ha resuelto el ticket #'.$code . ' Con la prioridad '.$priority_name,
                            'data' => $queryEmail
                        );
                        $this->queue->enqueue('send_whatsapp', 'Notifications', $job_data);  // Agregar trabajo a la cola
//                        sendNotification('Vora',,$queryEmail);


                        //job send mail
                        $job_data_mail = array(
                            'data'        => $dataEmail,
                            'message'     => 'Se ha resuelto el ticket #'.$code . ' Con la prioridad '.$priority_name,
                            'template'    => 'emails/v_ticket_finish',
                            'attachment'  => $attachment,
                            'emails'      => $queryEmail
                        );
                        $this->queue->enqueue('send_mailer', 'Mailer', $job_data_mail);  // Agregar trabajo a la cola
//                        $this->class_security->send_mailer($queryEmail,'Se ha resuelto el ticket #'.$code . ' Con la prioridad '.$priority_name,'emails/v_ticket_finish',$attachment,$dataEmail);

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

    //resolved ticket user
    function task_admon_revision(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('task','status'))){

                //campos post
                $task          = desencriptar($this->class_security->data_form('task'));
                $job           = $this->class_security->data_form('job','int');
                $status        = $this->class_security->data_form('status','int');
                $observation   = $this->class_security->data_form('observation');

                //validate
                $data = $this->general->all_get_join('house_maintence As m',[['house_maintence_employe As me','m.m_id=me.hma_assigne','LEFT'],['status As s','m.m_status=s.s_id']],['m.m_id' => $task],[],'alone');

                if(empty($data)){
                    $this->result = array('success' => 2,'msg' => 'Valida el Ticket ya que no se encuentra para asignar');
                    api($this->result);
                    exit;
                }
                if(in_array($data->hma_status,array(1,2,3,4))) {

                    $statusQ = ($status == 1) ? 4 : 1;
                    $statusC = ($status == 2) ? 1 : '';
                    $statusCP = ($status == 2) ? 2 : 3; // 2 retornado  3 completado

                    $this->general->update('house_maintence', array('m_id' => $task), array('m_accordance' => $statusC,'m_complete' => $statusCP,'m_accordance_observation' =>  $observation));
                    $this->general->update('house_maintence_employe', array('hma_assigne' => $task), array('hma_status' =>  $statusQ));

                    //sendmail ticket finish

                    //finish Task user work send mail
                    $queryEmail = $this->general->query("
                        (select u1.u_email,u1.u_name,u1.u_phone,2 As 'type' from house_maintence As m JOIN users As u1 ON m.m_user_request=u1.u_id where m.m_id='".$task."')
                        UNION ALL
                        (select u1.u_email,u1.u_name,u1.u_phone,2 As 'type' from  users As u1 where u1.u_area='".$data->m_area."' and u1.u_area_type IN(1,2))
                        UNION ALL
                        (select u1.u_email,u1.u_name,u1.u_phone,1 As 'type' from house_maintence As m JOIN house_maintence_employe As me ON m.m_id=me.hma_assigne JOIN users As u1 ON me.hma_user=u1.u_id where me.hma_assigne='".$task."')
                        ");

                    //all $attachment
                    $attachment_query = $this->general->all_get('house_maintence_files',['mf_maintence' => $task,'mf_type' => 2]);
                    $attachment =[];
                    if(!empty($attachment_query)){
                        foreach($attachment_query as $attach){
                            $attachment[]  = [FCPATH . "_files/maintenance/" .$attach->mf_file];
                        }
                    }

                    $code = $data->m_code;
                    $priority_name = $data->s_name;
                    $userAssigne =  array_values(array_filter($queryEmail,function($rr){  return $rr['type'] == 1;}));
                    $dataEmail = [
                        'name_user' => $userAssigne[0]['u_name'],
                        'ticket' => $code,
                        'priority' => $priority_name,
                        'date' => $data->hma_atcreate,
                        'observation' => $data->m_observation,
                        'observation2' => $data->hma_observation,
                        'observation3' => $observation,
                    ];

                    if($statusCP == 2){
                        //return
                        $titleSms = 'Se Regreso el ticket #'.$code . ' Con la prioridad '.$priority_name;
                    }else{
                        $titleSms = 'Se cerro el ticket #'.$code . ' Con la prioridad '.$priority_name;
                    }




                    $job_data = array(
                        'message' => $titleSms,
                        'data' => $queryEmail
                    );
                    $this->queue->enqueue('send_whatsapp', 'Notifications', $job_data);  // Agregar trabajo a la cola
//                    sendNotification('Vora',$titleSms,$queryEmail);


                    //job send mail
                    $job_data_mail = array(
                        'data'        => $dataEmail,
                        'message'     => $titleSms,
                        'template'    => 'emails/v_ticket_complete',
                        'attachment'  => $attachment,
                        'emails'      => $queryEmail
                    );

                    $this->queue->enqueue('send_mailer', 'Mailer', $job_data_mail);  // Agregar trabajo a la cola
//                    $this->class_security->send_mailer($queryEmail,$titleSms,'emails/v_ticket_complete',$attachment,$dataEmail);

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

}