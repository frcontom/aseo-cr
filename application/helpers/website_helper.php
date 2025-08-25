<?php

if(!function_exists('dashboard')){
    function dashboard($type){
//        $CI = &get_instance();
            $result = 'v_default';
            if($type == 1){
                //adminstrador
                $result = 'declaranet/dashboard/v_admin';
            }elseif($type == 2){
             //editor
                $result = 'declaranet/dashboard/v_editor';
            }else{
                //declarante
                $result = 'declaranet/dashboard/v_declarante';
            }
            return $result;
    }
}

if(!function_exists('fecha')){
    function fecha($tipo = 2){
        return $tipo == 1 ? date('Y-m-d') : date('Y-m-d G:i:s');
    }
}


if(!function_exists('hora')){
    function hora(){
        return date('G:i:s');
    }
}

if(!function_exists('limitar_texto')){
    function limitar_texto($palabra = '',$cantidad = 1){
            return substr($palabra,0,$cantidad);
    }
}

if(!function_exists('array_data')){
    function array_data($id = '',$data = array()){
        $result  = '';
        if(count($data) >= 1){
            if(isset($data[$id])){
                $result = $data[$id];
            }
        }
        return $result;
    }
}

if(!function_exists('validate_array')){
    function validate_array($id = '',$data = array()){
        $result  = false;
        if(count($data) >= 1){
            if(isset($data[$id])){
                $result = true;
            }
        }
        return $result;
    }
}

if(!function_exists('validate_array_permissions')){
    function validate_array_permissions($permiso,$id = ''){
        $CI = & get_instance();
        $CI->load->model('general');
        if($CI->general->exist('permisos',array('p_perfil' => $permiso,'p_modulo' => $id)) ==  false){
            redirect('declaracion/');
        }
    }
}

if(!function_exists('seleccionar_select')){
    function seleccionar_select($selected,$id){
        if($selected == $id){
            return 'selected';
        }
    }
}

if(!function_exists('seleccionar_checked')){
    function seleccionar_checked($selected,$id){
        if($selected == $id){
            return 'checked';
        }
    }
}


if(!function_exists('csrf_token')){
    function csrf_token(){
        $CI = &get_instance();
        $CI->load->helper('security');
        return "<input type='hidden' name='{$CI->security->get_csrf_token_name()}' value='{$CI->security->get_csrf_hash()}' />";

    }
}

if(!function_exists('declaracion_title')){
    function declaracion_title($id = ''){
        $CI = &get_instance();
        $CI->load->model('general');
        $result = '';
        if($id != ''){
            $result = $CI->general->query("select o.cdl_descripcion from declaracion As d JOIN conf_declaracion As o ON d.dc_tipo_declaracion=o.cdl_id where d.dc_id='".$id."'");

            if(count($result) >= 1){
                $result = $result[0]['cdl_descripcion'];
            }

        }
        return $result;
    }
}


