<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Eventconfig extends CI_Controller
{

    //propiedades
    private $session_id;
    private $session_token;
    private $user_data;
    private $controllName = 'Events';
    private $controller   = 'eventconfig/';
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

    function rooms() {
        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => $this->controllName,
            'user'          => $this->user_data
        );


        $data_body = array(
            'results' => $this->general->all_get('rooms'),
            'crud' => array(
                'url_modals'    => base_url("modal/"),
                'url_save'      => base_url("{$this->controller}save_room"),
                'url_delete'    => base_url("{$this->controller}delete_room"),

            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','plugins/numero/autoNumeric.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('eventsc/v_rooms',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }
    function save_room(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('name','money'))){

                //campos post
                $data_id        = $this->class_security->data_form('data_id','decrypt_int');
                $name           = $this->class_security->data_form('name');
                $money           = $this->class_security->data_form('money','saldo');

                $data = ['r_name' => $name,'r_price' => $money];

                //validar la duplicidad del username or emailes
                $this->result =   $this->general->create_update('rooms',['r_id' => $data_id],$data);


            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }
    function delete_room(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){
                $id = $this->class_security->data_form('id','decrypt_int');
                if(strlen($id) >= 1){
                    if($this->general->exist('rooms',array('r_id' => $id))){
                        $this->result =  $this->general->delete('rooms',array('r_id' => $id));
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



    function floor() {
        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => 'Pisos',
            'user'          => $this->user_data
        );


        $data_body = array(
            'datas' => $this->general->all_get('floor'),
            'crud' => array(
                'url_modals'    => base_url("modal/"),
                'url_save'      => base_url("{$this->controller}save_floor"),
                'url_delete'    => base_url("{$this->controller}delete_floor"),

            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('admin/v_floor',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }
    function save_floor(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('name'))){

                //campos post
                $data_id        = $this->class_security->data_form('data_id','decrypt_int');
                $name           = $this->class_security->data_form('name');

                $data = ['fr_name' => $name];

                //validar la duplicidad del username or emailes
                $this->result =   $this->general->create_update('floor',['fr_id' => $data_id],$data);


            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }
    function delete_floor(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){
                $id = $this->class_security->data_form('id','decrypt_int');
                if(strlen($id) >= 1){
                    if($this->general->exist('floor',array('fr_id' => $id))){
                        $this->result =  $this->general->delete('floor',array('fr_id' => $id));
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