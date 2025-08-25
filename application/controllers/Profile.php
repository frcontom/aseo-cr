<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Profile extends CI_Controller
{

    //propiedades
    private $session_id;
    private $session_token;
    private $user_data;
    private $controllName = '';
    private $controller   = 'profile/';
    public $project;
    private $result = array();

    public function __construct(){
        parent::__construct();


        $this->session_id     = $this->session->userdata('user_id');
        $this->session_token  = $this->session->userdata('user_token');
        $this->project        = $this->config->config['project'];

        //validacion de acceso
        auth();

//        //validar y hacer el llamado de todo
        $this->user_data = $this->general->get('users',array('u_id' => $this->session_id));
        $this->load->model('m_datatable','datatable');
        $this->load->helper(array('website','encriptar'));
    }

    function index() {
        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => 'Perfil de usuario',
            'user'          => $this->user_data
        );


        $data_body = array(
            'datas' => $this->general->get("users",['u_id' => $this->session_id],'array'),
            'crud' => array(
                'url_save'      => base_url("{$this->controller}save"),

            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js'));
        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('v_profile',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }

    function save(){
        if($this->input->post()){
            //validate method post
            if($this->class_security->validate_post(array('nombre','correo'))){
                 $usu      = $this->user_data->u_id;
                $nombre         = $this->class_security->data_form('nombre');
                $password       = encriptar_password($this->class_security->data_form('password'));
                $password_no    = $this->class_security->data_form('password');
                $correo        = $this->class_security->data_form('correo');
                $phone        = $this->class_security->data_form('phone');

                //validate email change other user
                //search email
                $search_email = $this->general->get('users',['u_email' => $correo],'array');
                if(isset($search_email) && !empty($search_email) and ($search_email['u_id'] != $usu)){
                        $this->result = array('success' => 2,'msg' => 'Lo Sentimos este correo no puede ser usado');
                 }else{
                    //procesar
                    if(strlen($password_no) > 1){
                        $data = array(
                            'u_name'          => $nombre,
                            'u_email'          => $correo,
                            'u_phone'          => $phone,
                            'u_password'       => $password,
                        );
                    } else {
                        $data = array(
                            'u_name'          => $nombre,
                            'u_email'         => $correo,
                            'u_phone'          => $phone,

                        );
                    }
                    $this->result =  $this->general->update('users',['u_id' => $usu],$data);
                 }

            }else{
                $this->result = array('success' => 2,'msg' => 'La contraseña es obligatoria');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }


    function notify_save(){
        if($this->input->post()){
            //validate method post
            if($this->class_security->validate_post(array('id'))){
                $id         = $this->class_security->data_form('id','str2');
                $usu        = $this->user_data->u_id;

                $search_email = $this->general->get('users',['u_id' => $usu],'array');
                if(isset($search_email) && !empty($search_email) and ($search_email['u_id'] != $usu)){
                    $this->result = array('success' => 2,'msg' => 'Lo Sentimos este Usuario no existe');
                }else{
                    //procesar
                    $this->result =  $this->general->update('users',['u_id' => $usu],['u_notify' => 2,'u_notify_code' => $id]);
                }

                //validate email change other user

            }else{
                $this->result = array('success' => 2,'msg' => 'La contraseña es obligatoria');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

}