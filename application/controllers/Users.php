<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Users extends CI_Controller
{

    //propiedades
    private $session_id;
    private $session_token;
    private $user_data;
    private $controllName = 'Usuarios';
    private $controller   = 'users/';
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
            'crud' => array(
                'url_modals'    => base_url("modal/"),
                'url_save'      => base_url("{$this->controller}save"),
                'url_delete'    => base_url("{$this->controller}delete"),
                'datatable'     => base_url("{$this->controller}datatable"),

            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/datatable_ajax.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('admin/v_users',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }

    function save(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('nombre','usuario','estado','perfil'))){

                //campos post
                $id             = $this->class_security->data_form('data_id','decrypt_int');
                $nombre         = $this->class_security->data_form('nombre');
                $phone          = $this->class_security->data_form('phone');
                $usuario        = $this->class_security->data_form('usuario');
                $password       = encriptar_password($this->class_security->data_form('password'));
                $perfil         = $this->class_security->data_form('perfil','int');
                $estado         = $this->class_security->data_form('estado','int');
                $permissions    = $this->class_security->data_form('permission','int','1');
                $area           = $this->class_security->data_form('area','int');
                $area_type      = $this->class_security->data_form('area_type','int');
                $password_no    = $this->class_security->data_form('password');
                $correo         = $this->class_security->data_form('correo');
                $properties     = $this->class_security->data_form('propertie','alone');


                //procesar
                if(strlen($password_no) > 1){
                    $data = array(
                        'u_name'          => $nombre,
                        'u_username'      => $usuario,
                        'u_email'          => $correo,
                        'u_phone'          => $phone,
                        'u_password'        => $password,
                        'u_profile'          => $perfil,
//                        'u_permissions'     => $permissions,
                        'u_area'            => $area,
                        'u_area_type'       => $area_type,
                        'u_status'          => $estado,
                        'u_atcreate'        => fecha(2),
                    );
                } else {
                    $data = array(
                        'u_name'            => $nombre,
                        'u_username'        => $usuario,
                        'u_email'           => $correo,
                        'u_phone'          => $phone,
                        'u_profile'         => $perfil,
//                        'u_permissions'     => $permissions,
                        'u_area'            => $area,
                        'u_area_type'       => $area_type,
                        'u_status'          => $estado,
                        'u_atcreate'        => fecha(2),
                    );
                }



                //validar la duplicidad del username or emailes
                if(strlen($id) >= 1){
                    //user exist

                    if($this->general->exist('users',array('u_id' => $id))){
                        //exist user update data

                        $data_email = $this->general->all_get('users',array('u_username' => $usuario));
                        $data_id = array_column($data_email,'u_id');
                        $diferencia = array_diff($data_id,array($id));

                        if(count($diferencia) >= 1){
                            $this->result = array('success' => 2,'msg' => 'El Usuario ya Existe');
                        }else{
                            $this->result =   $this->general->create_update('users',array('u_id' => $id),$data);
                            $this->user_properties($properties,$this->result);
                        }

                    }else{
                        //user not exist create new user
                        $data_email = $this->general->exist('users',array('u_username' => $usuario));
                        if(!$data_email){
                            if(strlen($password_no) >= 2){
                                $this->result =   $this->general->create_update('users',array(),$data);
                                $this->user_properties($properties,$this->result);
                            }else{
                                $this->result = array('success' => 2,'msg' => 'Usuario Nuevo debes Agregar una contraseña');
                            }
                        }else{
                            $this->result = array('success' => 2,'msg' => 'El Usuario ya Existe');
                        }
                    }
                }else{
                    //create user not id
                    //validate email not exist user register
                    $data_email = $this->general->exist('users',array('u_username' => $usuario));
                    if(!$data_email){
                        if(strlen($password_no) >= 2){
                            $this->result =   $this->general->create_update('users',array(),$data);
                            $this->user_properties($properties,$this->result);
                        }else{
                            $this->result = array('success' => 2,'msg' => 'Usuario Nuevo debes Agregar una contraseña');
                        }
                    }else{
                        $this->result = array('success' => 2,'msg' => 'El usuario ya Existe');
                    }
                }


            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    private function user_properties($datas = [],$userQ = []){
        if((isset($datas) and is_array($datas) and count($datas)) and (isset($userQ) and is_array($userQ) and count($userQ))){
            if(isset($userQ['data'])){
                $user_id = $userQ['data'];
                //delete all user data
                $this->general->delete('users_properties',['up_user' => $user_id]);
                foreach($datas As $data){
                    if(isset($data['id']) and isset($data['check'])){
                        $this->general->create('users_properties',['up_user' => $user_id,'up_propertie' => $data['id']]);
                    }

                }

            }

        }

        return $userQ;
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
            $order      = $this->input->post("order");
            $dato      = $this->input->post("dato");
            $valor      =  (isset($busqueda)) ? $busqueda['value'] : '';
        }else{
            $draw = 0;
            $start = 0;
            $length = 5;
            $valor = '';
            $dato = '';
            $order = array();
        }
        $data = array();


        //tabla
        $consulta_primary =  "select u.*,a.*,p.p_name from users As u LEFT JOIN profile As p ON u.u_profile=p.p_id  LEFT JOIN areas As a ON u.u_area=a.a_id   
                        where u_name LIKE '%".$valor."%'  or  u_username LIKE '%".$valor."%'   or  u_email LIKE '%".$valor."%'  ";

        $dataget         = $this->general->query("{$consulta_primary}  LIMIT $start,$length",'obj');
        $query_count = $this->general->query($consulta_primary);
        $total_registros = count($query_count);


        foreach($dataget as $rows){
            $id         = encriptar($rows->u_id);
            $nombre     = $this->class_security->decodificar($rows->u_name);
            $user       = $this->class_security->decodificar($rows->u_username);
            $email     = $this->class_security->decodificar($rows->u_email);
            $profile     = $this->class_security->decodificar($rows->p_name);
            $area     = $this->class_security->decodificar($rows->a_name);
            $status     = $this->class_data->status[$rows->u_status];
            $permission     = $this->class_data->permissions[$rows->u_permissions];


            $data[]= array(
                $nombre,
                $user,
                $email,
                $profile,
                $area,
                $permission,
                "<span class='{$status['class']}'>{$status['title']}</span>",
                "
                 <button type='button' onclick='$(this).forms_modal({\"page\" : \"users\",\"data1\" : \"{$id}\",\"title\" : \"Usuarios\"})' class='btn btn-primary btn-sm'  data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-pencil text-white'></i></button>
                 <button type='button' onclick='$(this).dell_data(\"{$id}\",\"url_delete\")' class='btn btn-danger btn-sm'  data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-times text-white'></i></button>"
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

    //Profile

    function profile() {
        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => $this->controllName,
            'user'          => $this->user_data
        );


        $data_body = array(
            'datas' => $this->general->query('SELECT p.p_id, p.p_name, COUNT(u.u_id) AS total
FROM profile AS p LEFT JOIN users AS u ON p.p_id = u.u_profile GROUP BY p.p_id, p.p_name;','obj'),
            'crud' => array(
                'url_modals'    => base_url("modal/"),
                'url_save'      => base_url("{$this->controller}profile_save"),
                'url_save_module'      => base_url("{$this->controller}profile_save_module"),
                'url_delete'    => base_url("{$this->controller}profile_delete"),

            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/datatable_ajax.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('admin/v_profile',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }


    function profile_save(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('name'))){

                //campos post
                $id             = $this->class_security->data_form('data_id','decrypt_int');
                $name         = $this->class_security->data_form('name');

                $this->result =   $this->general->create_update('profile',['p_id' => $id],['p_name' => $name]);

            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function profile_save_module(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('data_id'))){

                //campos post

                $id             = $this->class_security->data_form('data_id','decrypt_int');
                $permission     = $this->class_security->data_form('permission','alone');

                //drop all permissons
                $this->general->delete('permissions',['p_profile' => $id]);
                foreach($permission as $row){
                    if(isset($row['menu'])){
                        $this->general->create('permissions',['p_profile' => $id,'p_menu' => $row['id']]);
                    }
                }

                $this->result = array('success' => 1);

            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function profile_delete(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){
                $id = $this->class_security->data_form('id','decrypt_int');
                if(strlen($id) >= 1){
                    if($this->general->exist('profile',array('p_id' => $id))){
                        $this->result =  $this->general->delete('profile',array('p_id' => $id));
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

    //Areas
    function area() {
        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => $this->controllName,
            'user'          => $this->user_data
        );


        $data_body = array(
            'datas' => $this->general->all_get('areas'),
            'crud' => array(
                'url_modals'    => base_url("modal/"),
                'url_save'      => base_url("{$this->controller}area_save"),
                'url_delete'    => base_url("{$this->controller}area_delete"),

            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('admin/v_areas',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }

    function area_save(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('name'))){

                //campos post
                $id             = $this->class_security->data_form('data_id','decrypt_int');
                $name         = $this->class_security->data_form('name');

                $this->result =   $this->general->create_update('areas',['a_id' => $id],['a_name' => $name]);

            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function area_delete(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){
                $id = $this->class_security->data_form('id','decrypt_int');
                if(strlen($id) >= 1){
                    if($this->general->exist('areas',array('a_id' => $id))){
                        $this->result =  $this->general->delete('areas',array('a_id' => $id));
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

    //Propiedades
    function properties() {
        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => $this->controllName,
            'user'          => $this->user_data
        );


        $data_body = array(
            'datas' => $this->general->all_get('properties'),
            'crud' => array(
                'url_modals'    => base_url("modal/"),
                'url_save'      => base_url("{$this->controller}propertie_save"),
                'url_delete'    => base_url("{$this->controller}propertie_delete"),

            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('admin/v_properties',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }

    function propertie_save(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('name'))){

                //campos post
                $id             = $this->class_security->data_form('data_id','decrypt_int');
                $name         = $this->class_security->data_form('name');

                $this->result =   $this->general->create_update('properties',['pt_id' => $id],['pt_name' => $name]);

            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function propertie_delete(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){
                $id = $this->class_security->data_form('id','decrypt_int');
                if(strlen($id) >= 1){
                    if($this->general->exist('properties',array('pt_id' => $id))){
                        $this->result =  $this->general->delete('properties',array('pt_id' => $id));
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