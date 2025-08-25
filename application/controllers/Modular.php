<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Modular extends CI_Controller
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

     function chart(){

        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => $this->controllName,
            'controller'    => 'Dashboard',
            'user'          => $this->user_data,
            'style_level'   => array()
        );

        $data_body = array(
            'status'     => $this->general->query('select * from status where s_type= 1 and s_id IN(1,2,13,3,4,6,7,8,5)','obj'),
            'chart'      => $this->general->query("
select s.s_id,h.f_status,h.f_status_actual from status As s
JOIN house As h ON s.s_id=h.f_status_actual
where s.s_type=1 and s.s_status != 2",'Obj',false),
            'crud' => array(
                'url_modals'                 => base_url("modal/"),
            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/security.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu_security');
        $this->load->view('modulars/v_chart',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }



}