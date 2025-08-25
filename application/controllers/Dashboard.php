<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Dashboard extends CI_Controller
{
    //propiedades
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

    public function index() {

      echo $this->user_data->u_profile;
//        print_r($this->user_data);
        if($this->user_data->u_profile == 1){
            //administrativo
            $this->admin();
        }
        else if($this->user_data->u_profile == 12){
            //aqui recae todo lo que tiene que ver con los tickets
            $this->supervisor_maintenance();
        }
        else if($this->user_data->u_profile == 2){
            //aqui recae todo lo que tiene que ver con los tickets
            $this->supervisor_maintenance();
        }
        else if($this->user_data->u_profile == 3){
            $this->work_cleaning();
        }
        else if (in_array($this->user_data->u_profile, [4, 14])) {
            $this->reception();
        }
        else if($this->user_data->u_profile == 5){
            $this->cleam_maintenance();
        }
        else if($this->user_data->u_profile == 6){
            $this->supervisor_clean();
        }
        else if($this->user_data->u_profile == 7){
            //event build
            $this->events();
        }
        else if($this->user_data->u_profile == 8){
            //event build
            $this->kitchen();
        }

        else{
            //usuario registrante de declaraciones
//            show_404();
            $this->general();
        }

    }


    private function admin(){

        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => $this->controllName,
            'controller'    => 'Dashboard',
            'user'          => $this->user_data,
            'style_level'   => array()
        );

        $data_body = array(
            'crud' => array(
                'url_modals'                 => base_url("modal/"),
                'url_change_status'          => base_url("security/change_status"),
                'url_assigne_status'         => base_url("security/assigne_status"),
                'url_notify'                 => base_url("profile/notify_save"),
            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/security.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('dashboard/v_admin',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }

    private function reception(){

        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => $this->controllName,
            'controller'    => 'Dashboard',
            'user'          => $this->user_data,
            'style_level'   => array()
        );

        $data_body = array(
            'status'     => $this->general->query("select * from status As s JOIN icons i on s.s_icon = i.i_id WHERE s.s_status=1  and s.s_id IN(1,2,3,4,5,6,7,8,13)",'object',false),
            'timer'      => new Timer(),
            'floors'     => $this->general->all_get('floor'),
            'filials'    => $this->general->query("select h.*,s.*,f.*,ha.*,t.reason,t2.hc_comment from house As h
    JOIN status as s ON h.f_status_actual=s.s_id
    JOIN floor As f ON h.f_floor=f.fr_id
         LEFT JOIN house_assignment ha on h.f_id = ha.a_house and ha.a_status_service IN(1,2)
         LEFT JOIN (select hb.reason,hb.house_id from house_blocked As hb order by hb.id DESC LIMIT 1) As t ON h.f_id=t.house_id
         LEFT JOIN (select cm.hc_filial,cm.hc_comment from house_assignment_comment As cm where cm.hc_status=1) As t2 ON t2.hc_filial=h.f_id
         WHERE h.f_status IN(1,3,4)
         order by  h.f_name ASC,s.s_id ASC,h.f_available DESC,s.s_priority ASC
",'Obj',false),
            'crud' => array(
                'url_modals'                 => base_url("modal/"),
                'url_change_status'          => base_url("security/change_status"),
                'url_assigne_status'         => base_url("security/assigne_status"),
                'url_notify'                 => base_url("profile/notify_save"),
                'url_bother_status'          => base_url("bother_status"),
                'url_save'                   => base_url("security/comment_add"),
            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/security.js','cr/socket.js:module','cr/socket_filial.js:module'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('dashboard/v_security',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }

    private function work_cleaning(){

        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => $this->controllName,
            'controller'    => 'Dashboard',
            'user'          => $this->user_data,
            'style_level'   => array()
        );

// and h.f_user='".$this->user_data->u_id."'

//    echo    "select h.*,s.*,f.*,ha.a_id,u.u_name,i.i_icon,h.f_status_actual,ha.a_status_service,ha.a_assigner,ha.a_accordance,ha.a_take,ha.a_time from house As h
//    JOIN status as s ON h.f_status_actual=s.s_id
//    JOIN floor As f ON h.f_floor=f.fr_id
//    JOIN users As u ON h.f_user=u.u_id
//    JOIN icons i on s.s_icon = i.i_id
//     JOIN house_assignment ha on h.f_id = ha.a_house AND ha.a_status_service IN(1,2) and ha.a_user='".$this->user_data->u_id."'
//    LEFT JOIN (select cm.hc_filial,cm.hc_comment from house_assignment_comment As cm where cm.hc_status=1 order by cm.hc_id DESC LIMIT 1) As t2 ON t2.hc_filial=h.f_id
//    WHERE h.f_status = 1
//
//    ";

        $data_body = array(
            'timer'          => new Timer(),
            'floors'     => $this->general->all_get('floor'),
            'filials'    => $this->general->query("
    (select h.*,s.*,f.*,ha.a_id,u.u_name,i.i_icon,h.f_status_actual,ha.a_status_service,ha.a_assigner,ha.a_accordance,ha.a_take,ha.a_time from users As u
  JOIN house As h ON u.u_id=h.f_user
  LEFT JOIN house_assignment ha on h.f_id=ha.a_house and ha.a_status_service IN(1,2) and h.f_user=ha.a_user
     JOIN status as s ON h.f_status_actual=s.s_id
    JOIN floor As f ON h.f_floor=f.fr_id
    JOIN icons i on s.s_icon = i.i_id
    #LEFT JOIN (select cm.hc_filial,cm.hc_comment from house_assignment_comment As cm where cm.hc_status=1) As t2 ON t2.hc_filial=h.f_id
where h.f_user='".$this->user_data->u_id."' and ha.a_user iS NULL)
UNION ALL
(select h.*,s.*,f.*,ha.a_id,u.u_name,i.i_icon,h.f_status_actual,ha.a_status_service,ha.a_assigner,ha.a_accordance,ha.a_take,ha.a_time  from users As u
    JOIN house_assignment ha ON u.u_id = ha.a_user and  ha.a_status_service IN(1,2)
    JOIN house As h ON ha.a_house=h.f_id
    JOIN status as s ON h.f_status_actual=s.s_id
    JOIN floor As f ON h.f_floor=f.fr_id
    JOIN icons i on s.s_icon = i.i_id
    #LEFT JOIN (select cm.hc_filial,cm.hc_comment from house_assignment_comment As cm where cm.hc_status=1) As t2 ON t2.hc_filial=h.f_id
    where ha.a_user='".$this->user_data->u_id."')

    ",'Obj',false),
            'crud' => array(
                'url_modals'                 => base_url("modal/"),
                'url_change_status'          => base_url("clean/change_status"),
                'url_change_status_hand'     => base_url("clean/change_status_hand"),
                'url_change_clean_busy'     => base_url("clean/clean_busy"),
                'url_save_task'                 => base_url("clean/comment_task_save"),
                'url_notify'                 => base_url("profile/notify_save"),
            )
        );

//        $data_foot = array('script_level' => array('cr/crud_data.js','cr/clean.js'));
        $data_foot = array('script_level' => array('cr/crud_data.js','cr/clean.js:module','cr/socket.js:module','cr/socket_filial.js:module'));


        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('dashboard/v_clean',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }

    #Supervisor
    private function supervisor_clean(){

        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => $this->controllName,
            'controller'    => 'Dashboard',
            'user'          => $this->user_data,
            'style_level'   => array()
        );

//        "select h.*,s.*,f.*,ha.*,t.reason,t2.hc_comment,CASE
//                    WHEN ha.a_user = null THEN h.f_user
//                     WHEN h.f_user <> ha.a_user THEN ha.a_user
//                     ELSE h.f_user
//                     END as user_assigne
//    from house As h
//    JOIN status as s ON h.f_status_actual=s.s_id AND s.s_id IN(3,4,5,7,8,13)
//    JOIN floor As f ON h.f_floor=f.fr_id
//    LEFT JOIN house_assignment ha on h.f_id = ha.a_house AND ha.a_status_service IN(1,2)
//    LEFT JOIN (select hb.reason,hb.house_id from house_blocked As hb order by hb.id DESC LIMIT 1) As t ON h.f_id=t.house_id
//    LEFT JOIN (select cm.hc_filial,cm.hc_comment from house_assignment_comment As cm where cm.hc_status=1 order by cm.hc_id DESC LIMIT 1) As t2 ON t2.hc_filial=h.f_id
//    "

//        echo "select h.*,s.*,f.*,ha.*,t.reason,t2.hc_comment,CASE
//                    WHEN ha.a_user = null THEN h.f_user
//                     WHEN h.f_user <> ha.a_user THEN ha.a_user
//                     ELSE h.f_user
//                     END as user_assigne
//    from house As h
//    JOIN status as s ON h.f_status_actual=s.s_id
//    JOIN floor As f ON h.f_floor=f.fr_id
//    LEFT JOIN house_assignment ha on h.f_id = ha.a_house AND ha.a_status_service IN(1,2)
//    LEFT JOIN (select hb.reason,hb.house_id from house_blocked As hb order by hb.id DESC LIMIT 1) As t ON h.f_id=t.house_id
//    LEFT JOIN (select cm.hc_filial,cm.hc_comment from house_assignment_comment As cm where cm.hc_status=1 order by cm.hc_id DESC LIMIT 1) As t2 ON t2.hc_filial=h.f_id
//    ";
//exit;

        $data_body = array(
            'status_filial'   => '1',
            'timer'           => new Timer(),
            'floors'          => $this->general->all_get('floor'),
            'status'          => $this->general->query('select u_id,u_name from users where u_profile=3','obj'),
            'filials'         => $this->general->query("select h.*,s.*,f.*,ha.*,t.reason,t2.hc_comment,CASE
                    WHEN ha.a_user = null THEN h.f_user
                     WHEN h.f_user <> ha.a_user THEN ha.a_user
                     ELSE h.f_user
                     END as user_assigne
    from house As h
    JOIN status as s ON h.f_status_actual=s.s_id
    JOIN floor As f ON h.f_floor=f.fr_id
    LEFT JOIN house_assignment ha on h.f_id = ha.a_house AND ha.a_status_service IN(1,2,3) and ha.a_revision_status=1
    LEFT JOIN (select hb.reason,hb.house_id from house_blocked As hb order by hb.id DESC LIMIT 1) As t ON h.f_id=t.house_id
    LEFT JOIN (select cm.hc_filial,cm.hc_comment from house_assignment_comment As cm where cm.hc_status=1) As t2 ON t2.hc_filial=h.f_id
    ",'array',false),
            'crud' => array(
                'url_modals'    => base_url("modal/"),
                'url_resolved_filial'      => base_url("assignment/resolved_filial"),
                'url_save'      => base_url("assignment/save"),
                'url_change_close'      => base_url("assignment/close_status"),
                'url_filial_blocked'      => base_url("assignment/filial_blocked_status"),
                'url_notify'              => base_url("profile/notify_save"),
                'url_bother_status'       => base_url("bother_status"),
                'url_send_whatsapp_message'      => base_url("meeting/call_whatsapp_message"),
            )
        );

//        $data_foot = array('script_level' => array('cr/crud_data.js','cr/assigment.js'));
        $data_foot = array('script_level' => array('cr/crud_data.js','cr/assigment.js:module','cr/socket.js:module','cr/socket_filial.js:module'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu',['btn_mantenance' => true]);
        $this->load->view('supervisor_clean/v_dashboard',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }
    private function supervisor_maintenance(){

        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => $this->controllName,
            'controller'    => 'Dashboard',
            'user'          => $this->user_data,
            'style_level'   => array()
        );
        $validateArea = ($this->user_data->u_area == 0) ? '' : " where m.m_area='{$this->user_data->u_area}'";


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
    {$validateArea}
HAVING e.hma_status IN(1,2,3) or e.hma_status IS NULL",'array',false),
            'crud' => array(
                'url_modals'        => base_url("modal/"),
                'url_save'          => base_url("ticket/task_admon_assigne"),
                'url_revision'      => base_url("ticket/task_admon_revision"),
                'url_save_resolver' => base_url("maintenance/save_resolver"),
                'url_notify'        => base_url("profile/notify_save"),
            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/maintenance.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('supervisor_maintenance/v_ticket_admin_dashboard',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }

    private function events(){

        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => 'Eventos',
            'user'          => $this->user_data,
            'style_level'   => array()
        );

        $data_body = array(
            'timer'      => new Timer(),
//            'events'         => $this->general->all_get("proforma",['pf_status'=>2]),
            'users'              => $this->general->all_get('users',['u_profile' => 7],[],'json'),
            'crud' => array(
                'url_modals'    => base_url("modal/"),
                'url_package_get'  => base_url("events/get_package"),
                'url_save'      => base_url("events/save"),
                'datatable'     => base_url("events/datatable"),
                'url_notify'    => base_url("profile/notify_save"),
                'url_calcule'    => base_url("booking/dashboard_cacule"),
            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','vendor/chart.js/Chart.bundle.min.js','cr/dashboard_event.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('events/manager/v_dashboard',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }

    private function cleam_maintenance(){

        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => $this->controllName,
            'controller'    => 'Dashboard',
            'user'          => $this->user_data,
            'style_level'   => array()
        );

//        echo "select m.*,s.*,e.*,u.u_name from  house_maintence As m
//    JOIN house_maintence_employe  As e ON m.m_id=e.hma_assigne and e.hma_status IN(1,2)
//JOIN status As s ON m.m_status=s.s_id LEFT JOIN users as u oN e.hma_user=u.u_id  WHERE e.hma_user='".$this->session_id."' and m.m_complete != 3 ";
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
        $this->load->view('shared/template/v_menu_security');
        $this->load->view('maintenance/v_dashboard',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }

    private function kitchen(){

        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => $this->controllName,
            'controller'    => 'Dashboard',
            'user'          => $this->user_data,
            'style_level'   => array()
        );

//        echo "select m.*,s.*,e.*,u.u_name from  house_maintence As m
//    JOIN house_maintence_employe  As e ON m.m_id=e.hma_assigne and e.hma_status IN(1,2)
//JOIN status As s ON m.m_status=s.s_id LEFT JOIN users as u oN e.hma_user=u.u_id  WHERE e.hma_user='".$this->session_id."' and m.m_complete != 3 ";
        $data_body = array(
            'status_filial'     => '',
            'timer'      => new Timer(),
            'userclean'     => $this->general->all_get('users',['u_status'=>1,'u_profile'=>2]),
            'status'        => $this->general->query("select s.* from status As s WHERE s.s_type = 2 order by s.s_id DESC",'object',false),
            'filials'       => [],
            'crud' => array(
                'url_modals'        => base_url("modal/"),
                'url_save'          => base_url("ticket/task_manager_resolved"),
                'url_notify'        => base_url("profile/notify_save"),
            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/maintenance.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('dashboard/v_kitchen',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }
    private function general(){

        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => $this->controllName,
            'controller'    => 'Dashboard',
            'user'          => $this->user_data,
            'style_level'   => array()
        );

//        echo "select m.*,s.*,e.*,u.u_name from  house_maintence As m
//    JOIN house_maintence_employe  As e ON m.m_id=e.hma_assigne and e.hma_status IN(1,2)
//JOIN status As s ON m.m_status=s.s_id LEFT JOIN users as u oN e.hma_user=u.u_id  WHERE e.hma_user='".$this->session_id."' and m.m_complete != 3 ";
        $data_body = array(
            'crud' => array(
                'url_modals'        => base_url("modal/"),
                'url_save'          => base_url("ticket/task_manager_resolved"),
                'url_notify'        => base_url("profile/notify_save"),
            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/maintenance.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('dashboard/v_general',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }


}