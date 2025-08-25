<?php

class Modular extends CI_Controller
{
    //propiedades
    private $session_id;
    private $session_token;
    private $user_data;
    private $controller   = 'modular/';
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



    function tasks() {
        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => 'Tareas',
            'user'          => $this->user_data
        );


        $data_body = array(
            'datas' => $this->general->all_get('tasks',array('tk_delete' => 1)),
            'crud' => array(
                'url_modals'    => base_url("modal/"),
                'url_save'      => base_url("{$this->controller}tasks_save"),
                'url_delete'    => base_url("{$this->controller}tasks_delete"),

            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('admin/v_tasks',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }

    function tasks_save(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('name'))){

                //campos post
                $id             = $this->class_security->data_form('data_id','decrypt_int');
                $name         = $this->class_security->data_form('name');

                $this->result =   $this->general->create_update('tasks',['tk_id' => $id],['tk_name' => $name]);

            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function tasks_delete(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){
                $id = $this->class_security->data_form('id','decrypt_int');
                if(strlen($id) >= 1){
                    if($this->general->exist('tasks',array('tk_id' => $id))){
                        $this->result =  $this->general->update('tasks',array('tk_id' => $id),array('tk_delete' => 2));
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
}