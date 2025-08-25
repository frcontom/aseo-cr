<?php

class Seller extends CI_Controller
{

    //propiedades
    private $session_id;
    private $session_token;
    private $user_data;
    private $controllName = 'Venedor';
    private $controller   = 'seller/';
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

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/datatable_ajax.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('seller/v_index',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }

    function save(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('name','status'))){

                //campos post
                $id          = $this->class_security->data_form('data_id','decrypt_int');
                $name        = $this->class_security->data_form('name');
                $status      = $this->class_security->data_form('status','int');
                $percentage      = $this->class_security->data_form('percentage','alone');

                $data = array(
                    's_name'        => $name,
                    's_status'      => $status
                );

                //validar la duplicidad del username or emailes
                $insert =   $this->general->create_update('seller',array('s_id' => $id),$data);

                //create percentaje
                $idS = $insert['data'];

                $this->general->delete('seller_percentage',['sp_seller' => $idS]);
                if(isset($percentage) and is_array($percentage) and count($percentage) > 0){
                    foreach($percentage As $perc_id => $perc_val){
                        $this->general->create('seller_percentage',[
                            'sp_seller' => $idS,
                            'sp_percentage' => $perc_val]
                        );
                    }
                }

                $this->result =   array('success' => 1);

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
                    if($this->general->exist('seller',array('s_id' => $id))){
                        $this->result =  $this->general->update('seller',array('s_id' => $id),['s_status' => 99]);
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
        $consulta_primary =  "select * from seller where s_status != 99 and s_name LIKE '%".$valor."%' ";

        $dataget         = $this->general->query("{$consulta_primary}  LIMIT $start,$length",'obj');
        $query_count = $this->general->query($consulta_primary);
        $total_registros = count($query_count);


        foreach($dataget as $rows){
            $id         = encriptar($rows->s_id);
            $name       = $this->class_security->decodificar($rows->s_name);
            $status     = $this->class_security->array_data($rows->s_status,$this->class_data->statusSimple,$this->class_data->status_default);

            $data[]= array(
                $name,
                "<span class='{$status['class']}'>{$status['title']}</span>",
                "<button type='button' onclick='$(this).forms_modal({\"page\" : \"seller\",\"data1\" : \"{$id}\",\"title\" : \"Vendedor\"})' class='btn btn-primary btn-sm'  data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-pencil text-white'></i></button>
                 <button type='button' onclick='$(this).dell_data(\"{$id}\",\"url_delete\")' class='btn btn-danger btn-sm'  data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-times text-white'></i></button>
                "
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