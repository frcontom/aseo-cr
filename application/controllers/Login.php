<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Login extends CI_Controller{
    private $session_id;
    private $session_token;
    private $user_data;
    public $project;
    private $result = array();

    function __construct(){
        parent::__construct();
        //variable global
        $this->session_id     = $this->session->userdata('user_id');
        $this->session_token  = $this->session->userdata('user_token');
        $this->project        = $this->config->config['project'];

        //call models
        $this->load->model('m_login','login');
    }

    function index(){
        $this->accesso =  auth('login');
        $data_head = array(
            'titulo' => $this->project['tiulo'],
            'crud' => array(
                'url_login'       =>  base_url().'auth',
                'url_recovery'    =>  base_url().'recovery',
                'auth'            => 1,
            )
        );

        $this->load->view('auth/v_login',$data_head);
    }

    function auth(){
        if($this->input->post()){
            //validate method post
            if($this->class_security->validate_post(array('username','password'))){
                //validar
                $username   = $this->class_security->data_form('username','str');
                $password = encriptar_password($this->class_security->data_form('password','str'));
                $result_in =  $this->login->validate_login($username,$password);
                if($result_in['success'] == 1){
                    $data = $result_in['data'];
                    //generate token
                    $token = generate_token($data->u_id);

                    //save token
                    $this->login->save_token($data->u_id,$token);

                    //save session
                    $this->session->set_userdata('user_id', $data->u_id);
                    $this->session->set_userdata('user_token', $token);
                    $this->session->set_userdata('user_profile', $this->general->get('profile',array('p_id' => $data->u_profile))->p_name);

                    $result = array(
                        'success' => $result_in['success'],
                        'data' => base_url("/")
                    );
                }else{
                    $result = $result_in;
                }

            }else{
                $result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($result);
    }

    function logout() {
        unset($_SESSION);
        $this->session->sess_destroy();
        redirect('login');
    }

}