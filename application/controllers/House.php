<?php
defined('BASEPATH') or exit('No direct script access allowed');


class House extends CI_Controller
{

    //propiedades
    private $session_id;
    private $session_token;
    private $user_data;
    private $controllName = 'Filial';
    private $controller   = 'house/';
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
            'dataresult' => $this->general->all_get('house'),
            'crud' => array(
                'url_modals'    => base_url("modal/"),
                'url_get'       => base_url("{$this->controller}get"),
                'url_save'      => base_url("{$this->controller}save"),
                'url_delete'    => base_url("{$this->controller}delete"),
                'datatable'     => base_url("{$this->controller}datatable"),

            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/datatable_ajax.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('admin/v_house',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }

    function save(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('name','code','status','floor','user'))){

                //campos post
                $id          = $this->class_security->data_form('data_id','decrypt_int');
                $name        = $this->class_security->data_form('name');
                $code        = $this->class_security->data_form('code');
                $status      = $this->class_security->data_form('status','int');
                $floor       = $this->class_security->data_form('floor','int');
                $user        = $this->class_security->data_form('user','int');


                $data = array(
                    'f_user'           => $user,
                    'f_name'           => $name,
                    'f_code'           => $code,
                    'f_floor'          => $floor,
                    'f_status_actual'  => 1,
                    'f_available'      => 1,
                    'f_status'         => $status,
                    'f_atcreate'       => fecha(2),
                );

                //validar la duplicidad del username or emailes
                $this->result =   $this->general->create_update('house',array('f_id' => $id),$data);

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
                    if($this->general->exist('users',array('u_id' => $id))){
                        $this->result =  $this->general->delete('users',array('u_id' => $id));
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
        $consulta_primary =  "select * from house where f_name LIKE '%".$valor."%' ";

        $dataget         = $this->general->query("{$consulta_primary}  LIMIT $start,$length",'obj');
        $query_count = $this->general->query($consulta_primary);
        $total_registros = count($query_count);


        foreach($dataget as $rows){
            $id         = encriptar($rows->f_id);
            $name       = $this->class_security->decodificar($rows->f_name);
            $code       = $this->class_security->decodificar($rows->f_code);
            $status     = $this->class_data->status[$rows->f_status];

            $data[]= array(
                $name,
                $code,
                "<span class='{$status['class']}'>{$status['title']}</span>",
                "
                 <button type='button' onclick='$(this).forms_modal({\"page\" : \"house\",\"data1\" : \"{$id}\",\"title\" : \"Filial\"})' class='btn btn-primary'  data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-pencil text-white'></i></button>
                 <button type='button' onclick='$(this).dell_data(\"{$id}\",\"url_delete\")' class='btn btn-danger'  data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-times text-white'></i></button>"
            );


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