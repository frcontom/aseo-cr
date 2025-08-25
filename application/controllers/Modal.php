<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Modal extends CI_Controller
{
    //propiedades
    private $session_id;
    private $result = array();
    private $declaracion;
    private $declaracion_info;

    public $user_data;


    public function __construct(){
        parent::__construct();
        $this->session_id     = $this->session->userdata('user_id');
        $this->project        = $this->config->config['project'];
        $this->declaracion    = $this->session->userdata('declaracion');

        //validacion de acceso
        $this->user_data = $this->general->get('users',array('u_id' => $this->session_id  ?? ''));

    }


    function index(){
        $page =  $this->class_security->data_form('page', 'str');
        $data1 = $this->class_security->data_form('data1');
        $data2 = $this->class_security->data_form('data2');
        $data3 = $this->class_security->data_form('data3');
        $data4 = $this->class_security->data_form('data4');
        $data5 = $this->class_security->data_form('data5','alone');
        $usu = isset($this->user_data->u_id) ? $this->user_data->u_id : '';

        if ($usu != '') {
            if ($this->input->post()) {
                if ($this->class_security->validate_post(array('page'))) {

                    if($page == 'users') {
                        $id = desencriptar($data1);
                        $datas = $this->general->get('users',['u_id' => $id],'array');
                        $this->load->view('modal/admin/v_usuarios',array(
                            'page' => $page,
                            'id' => $data1,
                            'datas' => $datas,
                            'profiles' => $this->general->all_get('profile'),
                            'areas'       => $this->general->all_get('areas',[],[],'array'),
                            'properties' => $this->general->query("select p.*,CASE WHEN ISNULL(up.up_propertie) THEN ''ELSE 'checked' END As 'validate'
    from properties As p  LEFT JOIN users_properties up on p.pt_id = up.up_propertie and up.up_user='".$id."'")
                        ));
                    }

                    else  if($page == 'profile') {
                        $id = desencriptar($data1);
                        $this->load->view('modal/admin/v_profile',array(
                            'page' => $page,
                            'id' => $data1,
                            'datas' => $this->general->get('profile',['p_id' => $id],'array'),
                        ));
                    }

                    else  if($page == 'profile_module') {
                        $id = desencriptar($data1);
                        $name = ($data2);
                        $this->load->view('modal/admin/v_profile_module',array(
                            'page'  => $page,
                            'id'    => $data1,
                            'name'  => $name,
                            'datas' => $this->general->query("SELECT m.*,CASE WHEN p.p_menu IS NOT NULL THEN 1 ELSE 0 END AS has_permission FROM menu AS m
LEFT JOIN (SELECT p_menu FROM permissions WHERE p_profile = '".$id."') AS p ON m.m_id = p.p_menu;",'array'),
                        ));
                    }

                    else  if($page == 'user_area') {
                        $id = desencriptar($data1);
                        $this->load->view('modal/admin/v_area',array(
                            'page' => $page,
                            'id' => $data1,
                            'datas' => $this->general->get('areas',['a_id' => $id],'array'),
                        ));
                    }


                    else  if($page == 'user_properties') {
                        $id = desencriptar($data1);
                        $this->load->view('modal/admin/v_properties',array(
                            'page' => $page,
                            'id' => $data1,
                            'datas' => $this->general->get('properties',['pt_id' => $id],'array'),
                        ));
                    }

                    else  if($page == 'house') {
                        $id = desencriptar($data1);
                        $this->load->view('modal/admin/v_house',array(
                            'page' => $page,
                            'id' => $data1,
                            'datas' => $this->general->get('house',['f_id' => $id],'array'),
                            'users' => $this->general->all_get('users',['u_profile' => 3]),
                            'floors' => $this->general->all_get('floor'),
                        ));
                    }

                    else  if($page == 'status') {
                        $id = desencriptar($data1);
                        $datas = $this->general->get('status',['s_id' => $id],'array');
                        $this->load->view('modal/admin/v_status',array(
                            'page' => $page,
                            'id' => $data1,
                            'datas' => $datas,
                            'icons' => $this->general->all_get('icons'),
                        ));
                    }


                    else  if($page == 'floor') {
                        $id = desencriptar($data1);
                        $datas = $this->general->get('floor',['fr_id' => $id],'array');
                        $this->load->view('modal/admin/v_floor',array(
                            'page' => $page,
                            'id' => $data1,
                            'datas' => $datas,
                        ));
                    }

                    //Seller
                    else  if($page == 'seller') {
                        $id = desencriptar($data1);
                        $this->load->view('modal/seller/v_form',array(
                            'page' => $page,
                            'id' => $data1,
                            'datas' => $this->general->get('seller',['s_id' => $id],'array'),
                            'seller_percentage' => $this->general->all_get('seller_percentage',['sp_seller' => $id]),
                            'percentages' => $this->general->all_get('walk_percentaje',['wp_status != ' => 99],[],'array'),
                        ));
                    }

                    //Seller
                    else  if($page == 'walkin') {
                        $id = desencriptar($data1);
                                $this->load->view('modal/walkin/v_form',array(
                            'page' => $page,
                            'id' => $data1,
                            'sellers' => $this->general->all_get('seller',['s_status  !=' =>  99]),
                            'datas' => $this->general->get('walk_in',['w_id' => $id],'array'),
                            'percentaje' => $this->general->query("select distinct sp.sp_seller,wp.wp_percentaje_day,wp.wp_percentaje_major from seller_percentage As sp JOIN walk_percentaje As wp ON sp.sp_percentage=wp.wp_id  where wp.wp_type=1"),
                        ));
                    }



                    //walkin_admon
                    else  if($page == 'walkin_admon') {
                        $id = desencriptar($data1);
                         $type = desencriptar($data2);

                        if(in_array($type,['Restaurante','Venta'])){
                            if($type == 'Restaurante'){
                                $datas = $this->general->queryDinamycal('booking As b',[['seller As s','b.b_seller=s.s_id']],['b.b_id' => $id]);
                                $sellers = [];
                                $percentages = [];
                                $view = 'modal/walkin/v_walkin_admon_restaurant';
                            }else{
                                $view = 'modal/walkin/v_walkin_admon';
                                $sellers = $this->general->all_get('seller');
                                $datas = $this->general->queryDinamycal('walk_in As w',[['seller As s','w.w_seller=s.s_id']],['w.w_id' => $id]);
                                $percentages = $this->general->query("select distinct sp.sp_seller,wp.wp_percentaje_day,wp.wp_percentaje_major from seller_percentage As sp JOIN walk_percentaje As wp ON sp.sp_percentage=wp.wp_id  where wp.wp_type=1");
                            }
                        }



                        $this->load->view($view,array(
                            'page' => $page,
                            'id' => $data1,
                            'sellers'    => $sellers,
                            'percentaje' => $percentages,
                            'datas'      => $datas,
                        ));
                    }


                    //walkin_percentage
                    else  if($page == 'walkin_percentage') {
                        $id = desencriptar($data1);
                        $this->load->view('modal/walkin/v_walkin_percentage',array(
                            'page' => $page,
                            'id' => $data1,
                            'datas' => $this->general->get('walk_percentaje',['wp_id' => $id],'array'),
                        ));
                    }


                    //walkin_admon
                    else  if($page == 'walkin_download') {
                        $id = desencriptar($data1);
                        $this->load->view('modal/walkin/v_walkin_download',array(
                            'page' => $page,
                            'id' => $data1,
                            'sellers' => $this->general->all_get('seller'),
                        ));
                    }


                    //Events views



                    //Events views
                    else  if($page == 'events_view') {
                        $id          = ($data1);
                        $id_event    = ($data2);
                        $idt_ype     = ($data3);

                        $eventData = $this->general->get('booking_events',['e_proforma' => $id],'array');
                        $idE = (isset($eventData['e_id'])) ? $eventData['e_id'] : '';

                        $this->load->view('modal/events/v_events_view',array(
                            'page'           => $page,
                            'id'             => $data1,
                            'type'             => $idt_ype,

                            'events_time'        => $this->general->all_get('booking_service_time',['et_event' => $idE],[],'json'),
                            'events_special'     => $this->general->all_get('booking_menu_special_people',['es_event' => $idE],[],'json'),

                            'datas_days'         => $this->general->queryDinamycal('booking_days As d',[['rooms As r','d.bd_room=r.r_id']],['bd_id' => $id_event,'bd_booking' => $id],'','json',false),


                            'datas_packages'     => $this->general->queryDinamycal('booking_package As p',[['package As pg','p.pfp_package=pg.p_id']],['pfp_proforma' => $id],'','object',false),
                            'datas'              => $eventData,
                            'datas_proforma'     => $this->general->get('booking',['b_id' => $id],'array'),
                            'packages'           => $this->general->all_get('package',[],[],'json'),
                        ));
                    }



                    else  if($page == 'package') {
                         $id     = $this->class_security->int($data1);
                        $this->load->view('modal/events/v_package',array(
                            'page'    => $page,
                            'id'      => $id,
                            'datas'   => $this->general->get('package',['p_id' => $id],'array'),
                        ));
                    }

                    else  if($page == 'rooms') {
                        $id     = desencriptar($data1);
                        $type   = $data1;
                        $this->load->view('modal/events/v_room',array(
                            'page'    => $page,
                            'id'      => $data1,
                            'typein'    => $type,
                            'datas'   => $this->general->get('rooms',['r_id' => $id],'array'),
                        ));
                    }


                    //Maintence
                    else  if($page == 'maintenance_request') {
                        $this->load->view('modal/maintance/v_request',array(
                            'page'         => $page,
                            'status'       => $this->general->all_get('status',['s_type' => 2]),
                            'areas'       => $this->general->all_get('areas'),
                        ));
                    }




                    else  if($page == 'clean_task') {
                    echo    $id     = $this->class_security->int(desencriptar($data1));
                        $this->load->library('Timer');

                        $this->load->view('modal/clean/v_clean_tack',array(
                            'timer'        => new Timer(),
                            'page'         => $page,
                            'filial'       => $data1,
                            'status'       => $data2,
                            'data'         => $this->general->query("select h.*,em.*,e.s_color,e.s_name,u.u_name from house As h
                            LEFT JOIN house_assignment As em ON h.f_id=em.a_house
                            JOIN status As e On em.a_status_service=e.s_id
                            LEFT JOIN users As u ON h.f_user=u.u_id
                            where em.a_id='".$id."'",'array',true),
                        ));
                    }


                    //Maintence
                    else  if($page == 'maintance_assigne') {
                        $id     = $this->class_security->int(desencriptar($data1));
//                        if($this->general->exist('house_maintence',['m_id' => $id , 'm_area' => $this->user_data->u_area])){
                        $maintenance = $this->general->query("select *,ua.u_name As 'au_name' from house_maintence As m LEFT JOIN house_maintence_employe e ON m.m_id=e.hma_assigne JOIN status As s ON m.m_status=s.s_id  JOIN areas As a ON m.m_area=a.a_id LEFT JOIN users As ua ON m.m_user_request=ua.u_id WHERE m.m_id='".$id."'",'array',true);
//                        $maintenance = $this->general->query("select *,ua.u_name As 'au_name' from house_maintence As m LEFT JOIN house_maintence_employe e ON m.m_id=e.hma_assigne JOIN status As s ON m.m_status=s.s_id  JOIN areas As a ON m.m_area=a.a_id LEFT JOIN users As ua ON m.m_user_request=ua.u_id WHERE m.m_id='".$id."' and m.m_area='".$this->user_data->u_area."'",'array',true);
//                        print_r($maintenance);

                        //query user alll
                        if($this->user_data->u_profile == 12){
                            $user_all = $this->general->query("select * from users WHERE u_area_type IN(2,3)",'json');
                        }else{
                            $user_all = $this->general->query("select * from users WHERE u_area_type IN(2,3) and u_area = '".$maintenance['m_area']."' ",'json');
                        }

                            $this->load->view('modal/maintance/v_assigne',array(
                                'page'         => $page,
                                'filial'       => $id,
                                'users'        => $user_all,
                                'statusAll'    => $this->general->all_get('status',['s_type' => 2],[],'json'),
                                'data'         => $maintenance,
                                'files'        => $this->general->all_get('house_maintence_files',['mf_maintence' => $id,'mf_type' => 1]),
                            ));
//                        }else{
//                            $this->load->view('modal/v_error',['msg' => 'No se encontro el mantenimiento']);
//                        }
                    }

                    else  if($page == 'maintance_task') {
                        $id     = $this->class_security->int(desencriptar($data1));
                        $this->load->view('modal/maintance/v_complete_task',array(
                            'page'         => $page,
                            'filial'       => $data1,
                            'status'       => $data2,
                            'data'         => $this->general->query("select * from house_maintence As m
    JOIN house_maintence_employe  As e ON m.m_id=e.hma_assigne and e.hma_status IN(1,2)
    JOIN status As s ON m.m_status=s.s_id WHERE e.hma_user='".$this->session_id."' and m.m_id='".$id."'",'array',true),
                            'files'        => $this->general->all_get('house_maintence_files',['mf_maintence' => $id,'mf_type' => 1]),
                        ));
                    }

                    else  if($page == 'maintance_result') {
                        $idCrypt = (strlen($data1) > 10 ? desencriptar($data1) : $data1);
                        $id     = $this->class_security->int($idCrypt);
                        $this->load->view('modal/maintance/v_maintance_result',array(
                            'page'         => $page,
                            'data'        => $this->general->query(" select * from house As h     LEFT JOIN house_maintence As m ON h.f_id=m.m_filial and m.m_complete IN(3)     JOIN status As s ON m.m_status=s.s_id   WHERE m.m_id='".$id."'",'array',true),
                            'files'        => $this->general->all_get('house_maintence_files',['mf_maintence' => $id],[],'array'),
                        ));
                    }

                    else  if($page == 'task_detail') {
                         $id     = $this->class_security->int($data1);
                        $this->load->view('modal/maintance/v_task_detail',array(
                            'page'         => $page,
                            'data'        => $this->general->query(" select * from house As h  LEFT JOIN house_maintence As m ON h.f_id=m.m_filial and m.m_complete IN(3)  JOIN status As s ON m.m_status=s.s_id   WHERE m.m_id='".$id."'",'array',true),
                            'files'        => $this->general->all_get('house_maintence_files',['mf_maintence' => $id],[],'array'),
                        ));
                    }

                    else  if($page == 'task_log') {


                        //validate is encrypt or not
                        $idCrypt = (strlen($data1) > 10 ? desencriptar($data1) : $data1);
                        $id     = $this->class_security->int($idCrypt);


                        $this->load->view('modal/maintance/v_task_log',array(
                            'page'         => $page,
                            'data'        => $this->general->query("select h.*,em.*,e.s_color,e.s_name,ua.u_name As 'au_name',u.u_name
from house_maintence As h
    LEFT JOIN house_maintence_employe As em ON h.m_id=em.hma_assigne
    JOIN status As e On h.m_status=e.s_id
    LEFT JOIN users As u ON h.m_user_request=u.u_id
    LEFT JOIN (SELECT ua.u_id,ua.u_name from  users As ua) As ua ON em.hma_user=ua.u_id where h.m_id='".$id."'",'array',true),
                            'files'        => $this->general->all_get('house_maintence_files',['mf_maintence' => $id],[],'array'),
                        ));
                    }


                    else  if($page == 'clean_result') {
                        $idCrypt = (strlen($data1) > 10 ? desencriptar($data1) : $data1);
                        $id     = $this->class_security->int($idCrypt);
                        $this->load->view('modal/maintance/v_maintance_result',array(
                            'page'         => $page,
                            'data'        => $this->general->query(" select * from house As h     LEFT JOIN house_maintence As m ON h.f_id=m.m_filial and m.m_complete IN(3)     JOIN status As s ON m.m_status=s.s_id   WHERE m.m_id='".$id."'",'array',true),
                            'files'        => $this->general->all_get('house_maintence_files',['mf_maintence' => $id],[],'array'),
                        ));
                    }


                    //Assigment
                    else  if($page == 'assignment_rooms_user') {

                        //validate is encrypt or not
                        $id =  (strlen($data1) > 10 ? desencriptar($data1) : '');
                        $this->load->view('modal/assigment/v_rooms',array(
                            'page'         => $page,
                            'id'           => $id,
                            'users'        => $this->general->all_get("users",[ 'u_profile' => 3]),
                            'datas'        => $this->general->all_get("house"),
                        ));
                    }


                    //comment_view_filial
                    else  if($page == 'comment_response_filial') {

                        //validate is encrypt or not
                         $id =  (strlen($data1) > 10 ? desencriptar($data1) : '');
                        $this->load->view('modal/v_comment_response_filial',array(
                            'page'         => $page,
                            'id'           => $id,
                            'datas'  => $this->general->query("select u.u_name,h.f_name,fl.fr_name,ha.a_status_service,cm.hc_atcreate,cm.hc_comment 
from house As h 
    JOIN floor As fl ON h.f_floor=fl.fr_id 
    JOIN house_assignment As ha ON h.f_id=ha.a_house 
    LEFT JOIN house_assignment_comment As cm oN h.f_id = cm.hc_filial AND cm.hc_status=1  
   LEFT JOIN users As u ON cm.hc_user=u.u_id
where ha.a_house='".$id."' AND ha.a_status_service IN(1,2)",'array',true),
//                            'datas'         => $this->general->query("select * from house_assignment_comment As cm  JOIN users As u ON cm.hc_user=u.u_id where cm.hc_status=1 and cm.hc_filial='".$id."' order by cm.hc_id DESC LIMIT 1",'array',true),
                            'tasks'         => $this->general->all_get('house_assignment_comment_task',['hct_filial' => $id,'hrc_status' => 1],[],'array')
                        ));
                    }

                    else  if($page == 'revision_filial') {

                        //validate is encrypt or not
                        $id =  (strlen($data1) > 10 ? desencriptar($data1) : '');
                      $data = $this->general->query("select *
from house As h
    JOIN floor As fl ON h.f_floor=fl.fr_id
    JOIN house_assignment As ha ON h.f_id=ha.a_house
    JOIN house_assignment_revision As hr ON h.f_id=hr.har_filial and ha.a_id=hr.har_assignment and hr.har_status=1
    LEFT JOIN house_assignment_comment As cm oN ha.a_id = cm.hc_assignment
    LEFT JOIN users As u ON cm.hc_user=u.u_id
    LEFT JOIN (select u_id,u_name from users) As t ON ha.a_user=t.u_id
where h.f_id='".$id."' and h.f_status_actual=14 AND ha.a_status_service=3",'array',true);

                      $a_id = $data['a_id'];


                        $this->load->view('modal/v_revision_filial',array(
                            'page'         => $page,
                            'id'           => $id,
                            'datas'  => $data,
//                            'datas'         => $this->general->query("select * from house_assignment_comment As cm  JOIN users As u ON cm.hc_user=u.u_id where cm.hc_status=1 and cm.hc_filial='".$id."' order by cm.hc_id DESC LIMIT 1",'array',true),
                            'tasks'         => $this->general->all_get('house_assignment_comment_task',['hct_filial' => $id,'hct_assignment'=>$a_id],[],'array')
                        ));
                    }




                    else  if($page == 'comment_view_filial') {

                        //validate is encrypt or not
                        $id =  (strlen($data1) > 10 ? desencriptar($data1) : '');
                        $this->load->view('modal/v_comment_filial',array(
                            'page'         => $page,
                            'id'           => $id,
                            'datasComment'  => $this->general->query("select u.u_name,h.f_name,ha.a_observation_clean,ha.a_ending from house As h  JOIN house_assignment As ha ON h.f_id=ha.a_house  JOIN users As u ON h.f_user=u.u_id where ha.a_house='".$id."'  order by ha.a_id DESC LIMIT 1",'array',true),
                            'datas'         => $this->general->query("select * from house_assignment_comment As cm  JOIN users As u ON cm.hc_user=u.u_id where cm.hc_status=1 and cm.hc_filial='".$id."' order by cm.hc_id DESC LIMIT 1",'array',true),
                            'tasks'         => $this->general->all_get('house_assignment_comment_task',['hct_filial' => $id,'hrc_status' => 1],[],'array')
                        ));
                    }

                    //booking
                    else  if($page == 'booking') {

                        //validate is encrypt or not
                        $id =  (strlen($data1) > 10 ? desencriptar($data1) : '');
                        $this->load->view('modal/booking/v_booking',array(
                            'page'               => $page,
                            'id'                 => $data1,
                            'sellers'            => $this->general->all_get("seller",['s_status != ' => 99]),
                            'rooms'              => $this->general->all_get("rooms",['r_type' => 2],[],'array'),
                            'datas'              => $this->general->query("select * from booking As b LEFT JOIN users As u ON b.b_user=u.u_id WHERE b.b_id ='".$id."'",'array',true),
                            'datas_proforma'     => $this->general->get('booking_events',['e_proforma' => $id],'array'),
                            'datas_days'         => $this->general->all_get('booking_days',['bd_booking' => $id],[],'json'),
                        ));
                    }

                    //booking
                    else  if($page == 'booking_restaurant') {

                        //validate is encrypt or not
                        $id =  (strlen($data1) > 10 ? desencriptar($data1) : '');
                        $this->load->view('modal/booking/v_booking_restaurant',array(
                            'page'               => $page,
                            'id'                 => $data1,
                            'sellers'              => $this->general->all_get("seller",['s_status != ' => 99]),
                            'rooms'              => $this->general->all_get("rooms",['r_type' => 2],[],'array'),
                            'datas'              => $this->general->query("select * from booking As b LEFT JOIN users As u ON b.b_user=u.u_id WHERE b.b_id ='".$id."'",'array',true),
                            'datas_proforma'     => $this->general->get('booking_events',['e_proforma' => $id],'array'),
                            'datas_days'         => $this->general->all_get('booking_days',['bd_booking' => $id],[],'json'),
                        ));
                    }

                    //booking
                    else  if($page == 'booking_ban') {
                        //validate is encrypt or not
                        $id =  (strlen($data1) > 10 ? desencriptar($data1) : '');

                        $data = $this->general->query("select * from booking As b LEFT JOIN users As u ON b.b_user=u.u_id WHERE b.b_id ='".$id."'",'array',true);

                        if(isset($data)){
                            $this->load->view('modal/booking/v_booking_ban',array(
                                'page'               => $page,
                                'id'                 => $data1,
                                'datas'              => $data,
                            ));
                        }else{
                            $this->load->view('modal/v_error',['msg' => 'No se encontro el dato']);
                        }
                    }

                    else  if($page == 'proforma') {
                        $id     = desencriptar($data1);
                        $this->load->view('modal/events/v_proforma',array(
                            'page'               => $page,
                            'id'                 => $data1,
                            'user'               => $this->user_data,
                            'datas'              => $this->general->get('booking',['b_id' => $id],'array'),

                            'datas_packages'     => $this->general->queryDinamycal('booking_package As p',[['package As pg','p.pfp_package=pg.p_id']],['pfp_proforma' => $id],'','object',false),
//                            'datas_packages'     => $this->general->all_get('proforma_package',['pfp_proforma' => $id],[],'json'),
                            'datas_rooms'        => $this->general->all_get('booking_room',['pfr_proforma' => $id],[],'json'),
                            'datas_days'         => $this->general->all_get('booking_days',['bd_booking' => $id],[],'json'),
                            'packages'           => $this->general->all_get('package',[],[],'json'),
                            'rooms'              => $this->general->all_get('rooms',[],[],'array'),
                            'propierties'        => $this->general->all_get('properties',[],[],'json'),
                            'users'              => $this->general->all_get('users',['u_profile' => 7],[],'json'),
                        ));
                    }

                    else  if($page == 'events') {
                        $id     = desencriptar($data1);

                        $eventData = $this->general->get('booking_events',['e_proforma' => $id],'array');
                        $idE = (isset($eventData['e_id'])) ? $eventData['e_id'] : '';
                        $this->load->view('modal/events/v_events',array(
                            'page'           => $page,
                            'id'             => $data1,

                            'datas'              => $eventData,
                            'datasP'             => $this->general->get('booking',['b_id' => $id],'array'),
                            'events_time'        => $this->general->all_get('booking_service_time',['et_event' => $idE],[],'json'),
                            'events_special'     => $this->general->all_get('booking_menu_special_people',['es_event' => $idE],[],'json'),

                            'datas_rooms'        => $this->general->all_get('booking_room',['pfr_proforma' => $id],[],'json'),
                            'datas_days'         => $this->general->queryDinamycal('booking_days As d',[['rooms As r','d.bd_room=r.r_id']],['bd_booking' => $id],'','json',false),
                            'rooms'              => $this->general->all_get("rooms",[],[],'array'),

                            'datas_packages'     => $this->general->queryDinamycal('booking_package As p',[['package As pg','p.pfp_package=pg.p_id']],['pfp_proforma' => $id],'','object',false),
                            'packages'           => $this->general->all_get('package',[],[],'json'),
                            'propierties'        => $this->general->all_get('properties',[],[],'json'),
                        ));
                    }

                    else  if($page == 'profome_view') {
                        $id     = desencriptar($data1);

                        $eventData = $this->general->get('booking_events',['e_proforma' => $id],'array');
                        $idE = (isset($eventData['e_id'])) ? $eventData['e_id'] : '';

                        $this->load->view('modal/events/v_proforme_view',array(
                            'page'           => $page,
                            'id'             => $data1,

                            'events_time'    => $this->general->all_get('booking_service_time',['et_event' => $idE],[],'json'),
                            'events_special' => $this->general->all_get('booking_menu_special_people',['es_event' => $idE],[],'json'),

                            'datas_days'         => $this->general->queryDinamycal('booking_days As d',[['rooms As r','d.bd_room=r.r_id']],['bd_booking' => $id],'','json',false),

                            'datas_packages'     => $this->general->queryDinamycal('booking_package As p',[['package As pg','p.pfp_package=pg.p_id']],['pfp_proforma' => $id],'','object',false),
                            'datas_rooms'        => $this->general->all_get('booking_room',['pfr_proforma' => $id],[],'json'),
                            'datas'              => $this->general->queryDinamycal('booking As b',[['users As u','b.b_user = u.u_id']],['b_id' => $id],'','array',true),
                            'event'              => $eventData,
                            'packages'           => $this->general->all_get('package',[],[],'json'),
                            'rooms'              => $this->general->all_get("rooms"),
                        ));
                    }

                    else  if($page == 'whatsapp') {
                        $this->load->view('modal/meeting/v_whatsapp',array(
                            'page'           => $page,
                        ));
                    }

                    //horary
                    else  if($page == 'horary_category') {
                        $id =  desencriptar($data1);
                        $this->load->view('modal/horary/v_category',array(
                            'page'         => $page,
                            'id'           => $data1,
                            'profiles'     => $this->general->all_get('profile'),
                            'datas'        => $this->general->get('horary_category',['hc_id' => $id],'array'),
                            'propierties'        => $this->general->all_get('properties',[],[],'json'),
                        ));
                    }

                    else  if($page == 'horary_employ') {
                        $id =  desencriptar($data1);
                        $this->load->view('modal/horary/v_employ',array(
                            'page'         => $page,
                            'id'           => $data1,
                            'datas'        => $this->general->get('horary_employ',['he_id' => $id],'array'),
                            'categorys'    => $this->general->all_get("horary_category"),
                        ));
                    }

                    else  if($page == 'horary_employ_faceid') {
                        $id =  desencriptar($data1);
                        $this->load->view('modal/horary/v_employ_faceid',array(
                            'page'         => $page,
                            'id'           => $data1,
                            'datas'        => $this->general->get('horary_employ',['he_id' => $id],'array'),
                            'datas_face'        => $this->general->get('horary_employ_faceid',['hfc_employ' => $id],'array'),
                        ));
                    }

                    else  if($page == 'horary_schedules') {
                        $id =  desencriptar($data1);
                        $this->load->view('modal/horary/v_schedules',array(
                            'page'         => $page,
                            'id'           => $data1,
//                            'datas'        => $this->general->get('horary_employ',['he_id' => $id],'array'),
                            'datas'        => $this->general->all_get("horary_schedules"),
                        ));
                    }

                    else  if($page == 'horary_report_validation') {

                        if($data1 != '' and  $data2 != '' and $data3 != '') {

                            $employ = ($data1);

                            $this->load->library('ReportHours');
                            $reportH = new ReportHours();
                            $rangos_fecha = $this->class_security->rango_fechas($data2, $data3);



                            $and_where = [
                                'employ' => '',
                                'employ_id' => $employ,
                                'validated' => 'employ_id_only_extra',
                                'type_hour' => 2
                            ];

                            $data = $reportH->report_employ_query($employ, '', $rangos_fecha, $and_where);

                            $allq = $reportH->BackReportEmploy($data);

                            $reporte = array_values($reportH->procesarRegistros($allq));

//                            print_r(array_values($reporte));
//                            exit;

                            $dataQ = [
                                'employ_id' => $reporte[0]['employ_id'],
                                'employ_name' => $reporte[0]['employ_name'],
                                'category_name' => $reporte[0]['category_name'],
                                'dates' => $this->class_security->aplanarDatosOptimizado($reporte)
                            ];

                            $this->load->view('modal/horary/v_report_validated', array(
                                'page' => $page,
                                'employ' => $data1,
                                'date1' => $data2,
                                'date2' => $data3,
                                'data' => $dataQ

                            ));
                        }
                    }


                    //comment_view_filial
//                    else  if($page == 'comment_view_finish') {
//
//                        //validate is encrypt or not
//                        $id =  (strlen($data1) > 10 ? desencriptar($data1) : '');
//                         $aid =  (strlen($data2) > 10 ? desencriptar($data2) : '');
//                        $this->load->view('modal/v_comment_finish',array(
//                            'page'         => $page,
//                            'id'           => $id,
//                            'datas'         => $this->general->query("
//                            select * from house_assignment_comment As cm
//JOIN users As u ON cm.hc_user=u.u_id
//         where cm.hc_status=1 and cm.hc_filial='".$id."'
// order by cm.hc_id DESC LIMIT 1",'array',true)
//                        ));
//                    }

                }else{
                    $this->load->view('modal/v_error',['msg' => 'No se encontro el models']);
                }
            }else{
                show_404();
            }
        }else{
            //no hay vistas que mostrar
            $this->load->view('modal/v_error',['msg' => 'La solicitud no puede ser procesada en este momento']);
       }
    }

}