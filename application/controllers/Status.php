<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Status extends CI_Controller
{

    //propiedades
    private $session_id;
    private $session_token;
    private $user_data;
    private $controllName = 'Estados';
    private $controller   = 'status/';
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

    function index() {
        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => $this->controllName,
            'user'          => $this->user_data
        );


        $data_body = array(
            'dataresult' => $this->general->all_get('status'),
            'crud' => array(
                'url_modals'    => base_url("modal/"),
                'url_get'       => base_url("{$this->controller}get"),
                'url_save'      => base_url("{$this->controller}save"),
                'url_delete'    => base_url("{$this->controller}delete"),
                'datatable'     => base_url("{$this->controller}datatable"),

            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/datatable_ajax.js','cr/local.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('admin/v_status',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }

    function save(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('name','color_f','color','icon','code','time','status'))){

                //campos post
                $id          = $this->class_security->data_form('data_id','decrypt_int');
                $name        = $this->class_security->data_form('name');
                $color_f       = $this->class_security->data_form('color_f');
                $color       = $this->class_security->data_form('color');
                $icon       = $this->class_security->data_form('icon');
                $code        = $this->class_security->data_form('code');
                $time        = $this->class_security->data_form('time','int',0);
                $status      = $this->class_security->data_form('status','int');
                $priority    = $this->class_security->data_form('priority','int',3);

                $data = array(
                    's_name'        => $name,
                    's_color'       => $color,
                    's_color_f'       => $color_f,
                    's_icon'        => $icon,
                    's_code'        => $code,
                    's_time'        => $time,
                    's_priority'    => $priority,
                    's_status'      => $status,
                    's_atcreate'    => fecha(2),
                );

                //validar la duplicidad del username or emailes
                $this->result =   $this->general->create_update('status',array('s_id' => $id),$data);

            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function delete(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){
                $id = $this->class_security->data_form('id','decrypt_int');
                if(strlen($id) >= 1){
                    if($this->general->exist('status',array('s_id' => $id))){
                        $this->result =  $this->general->delete('status',array('s_id' => $id));
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

    function datatable(){
        $all_input = $this->input->post("draw");
        if(isset($all_input)){
            $draw       = intval($this->input->post("draw"));
            $start      = intval($this->input->post("start"));
            $length     = intval($this->input->post("length"));
            $busqueda   = $this->input->post("search");
            $valor      =  (isset($busqueda)) ? $busqueda['value'] : '';
        }else{
            $draw = 0;
            $start = 0;
            $length = 5;
            $valor = '';
        }
        $data = array();


        //tabla
        $consulta_primary =  "select * from status As s JOIN icons as i ON s.s_icon=i.i_id where s_name LIKE '%".$valor."%' ";

        $dataget         = $this->general->query("{$consulta_primary}  LIMIT $start,$length",'obj');
        $query_count = $this->general->query($consulta_primary);
        $total_registros = count($query_count);


        foreach($dataget as $rows){
            $id         = encriptar($rows->s_id);
            $name       = $this->class_security->decodificar($rows->s_name);
            $icon       = $this->class_security->decodificar($rows->i_icon);
            $color       = $this->class_security->decodificar($rows->s_color);
            $code       = $this->class_security->decodificar($rows->s_code);
            $time       = $this->class_security->decodificar($rows->s_time);
            $priority     = $this->class_data->priority[$rows->s_priority];
            $status     = $this->class_data->status[$rows->s_status];

            $data[]= array(
                $name,
                "<i class='{$icon} fs-24' style='color:$color'></i>",
                $code,
                $time,
                "<span class='{$priority['class']}'>{$priority['title']}</span>",
                "<span class='{$status['class']}'>{$status['title']}</span>",
                "<button type='button' onclick='$(this).forms_modal({\"page\" : \"status\",\"data1\" : \"{$id}\",\"title\" : \"Estado\"})' class='btn btn-primary'  data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-pencil text-white'></i></button>
                "
            );

//            <button type='button' onclick='$(this).dell_data(\"{$id}\",\"url_delete\")' class='btn btn-danger'  data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-times text-white'></i></button>
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $total_registros,
            "recordsFiltered" => $total_registros,
            "data" => $data
        );

        apirest($output);
        exit();
    }
}