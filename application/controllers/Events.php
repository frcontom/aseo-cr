<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Events extends CI_Controller
{
    //propiedades
    private $session_id;
    private $user_data;
    public $project;
    private $result = array();
    private $controllName        = 'Events';
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

    function index(){
        return redirect('/');
    }

     function package(){

        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => $this->controllName,
            'controller'    => 'Dashboard',
            'user'          => $this->user_data,
            'style_level'   => array('vendor/summernote/summernote.css')
        );

        $data_body = array(
            'datas' => $this->general->all_get('package',[],[],'array'),
            'crud'  => array(
                'url_modals'    => base_url("modal/"),
                'url_save'      => base_url("events/save_package"),
                'url_delete'      => base_url("events/delete_package"),
            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/proformas.js','plugins/numero/autoNumeric.js','vendor/summernote/js/summernote.min.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('events/v_package',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }


    //events

    private function events(){

        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => 'Eventos',
            'user'          => $this->user_data,
            'style_level'   => array()
        );

        $data_body = array(
            'timer'      => new Timer(),
//            'filials'         => $this->general->query(""),
            'crud' => array(
                'url_modals'    => base_url("modal/"),
                'url_save'      => base_url("events/save"),
                'datatable'     => base_url("events/datatable"),
            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/events.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('events/v_dashboard',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }


    //method

    function save_package(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('name','price','status','detail'))){

                //campos post
                $id            = $this->class_security->data_form('data_id','int');
                $name          = $this->class_security->data_form('name');
                $price         = $this->class_security->data_form('price','saldo');
                $status        = $this->class_security->data_form('status');
                $detail        = $this->class_security->data_form('detail','alone');

                $data = array(
                    'p_title'         => $name,
                    'p_price'         => $price,
                    'p_description'   => $detail,
                    'p_status'        => $status,
                    'p_created_at'    => fecha(2),
                );

                //validar la duplicidad del username or emailes
                $this->result =   $this->general->create_update('package',array('p_id' => $id),$data);

            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function delete_package(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){
                $id = $this->class_security->data_form('id','int');
                if(strlen($id) >= 1){
                    if($this->general->exist('package',array('p_id' => $id))){
                        $this->result =  $this->general->delete('package',array('p_id' => $id));
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

    //rooms
    function rooms(){

        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => $this->controllName,
            'controller'    => 'Dashboard',
            'user'          => $this->user_data,
            'style_level'   => array('vendor/summernote/summernote.css')
        );

        $data_body = array(
            'datas' => $this->general->all_get('rooms',[],[],'array'),
            'crud' => array(
                'url_modals'    => base_url("modal/"),
                'url_save'      => base_url("events/save_room"),
                'url_delete'      => base_url("events/delete_room"),
            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/proformas.js','plugins/numero/autoNumeric.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('events/v_rooms',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }

    function save_room(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('name','type','money','color'))){

                //campos post
                $data_id        = $this->class_security->data_form('data_id','decrypt_int');
                $name           = $this->class_security->data_form('name');
                $type           = $this->class_security->data_form('type');
                $color           = $this->class_security->data_form('color');
                $money           = $this->class_security->data_form('money','saldo');

                $data = ['r_name' => $name,'r_type' => $type,'r_price' => $money,'r_color' => $color];

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

    //proforma

    function validate_day_proforma(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('date','hour1','hour2'))){

                //campos post
                $date       = $this->class_security->data_form('date');
                $hour1      = $this->class_security->data_form('hour1');
                $hour2      = $this->class_security->data_form('hour2');

                if($date != '' && $hour1 != '' or $hour2 != ''){

                    $queryAll = $this->general->query("
                    select * from proforma_days where pf_day = '".$date."' and
                   (TIME_FORMAT(pf_houri , '%H:%i') <= TIME_FORMAT('".$hour1."', '%H:%i') or TIME_FORMAT(pf_houri , '%H:%i') >= TIME_FORMAT('".$hour1."', '%H:%i')) and
                   (TIME_FORMAT(pf_hourf , '%H:%i') >= TIME_FORMAT('".$hour2."', '%H:%i') or  TIME_FORMAT(pf_hourf , '%H:%i') <= TIME_FORMAT('".$hour2."', '%H:%i'))
                    ");



                }else{
                    $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
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