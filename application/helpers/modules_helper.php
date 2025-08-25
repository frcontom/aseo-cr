<?php
defined('BASEPATH') or exit('No direct script access allowed');



function mod_domicilio($id = '',$module = '',$country = '',$prefix = ''){
    //validar que se haya registrado la informacion del empleo
    modal_mod_extends_domicilio('declaracion_datos_empleo_domicilio',$module,$id,$country,$prefix);
}

function mod_tercero($id = '',$module = '',$titular = '',$prefix =''){
    //validar que se haya registrado la informacion del empleo
    modal_mod_extends_tercero($module,$id,$titular,$prefix);
}

function mod_fisica_moral($id = '',$module = '',$titular = '',$prefix ='',$uniqu = ''){
    //validar que se haya registrado la informacion del empleo
    modal_mod_extends_fisica_moral($module,$id,$titular,$prefix,$uniqu);
}

function extends_transmisor($page = '',$modulo = '',$padre = ''){
    //mostrar view
    $CI = &get_instance();
    $CI->load->model('general');
    $CI->load->model('m_declaracion','declaraciones');
    $usu = $CI->session->userdata('user_id');

    $dataR = $CI->general->get('declaracion_mod_transmisores',['dt_modulo' => $modulo, 'dt_usuario' => $usu],'array');

    $id = '';
    $datas = [];

    if(isset($dataR) and !empty($dataR)){
        $id        = $dataR['dt_id'];
        $datas = $CI->declaraciones->bienes_inmuebles(desencriptar($padre),$modulo);
    }

    $CI->load->view('modal/declaracion/shared/v_transmisor',array('page' => $page,'id' => $id,'datas' => $datas,'padre' => $padre,'module' => encriptar($modulo),
        'relacion'    => $CI->general->all_get('conf_parentesco_relacion'),
        'persona'     => $CI->general->all_get('conf_tipo_persona'),
//        'view_modal' => $this,
    ));
}

function extends_tercero($page = '',$modulo = '',$padre = ''){
    //mostrar view
    $CI = &get_instance();
    $CI->load->model('general');
    $CI->load->model('m_declaracion','declaraciones');
    $usu = $CI->session->userdata('user_id');

    $dataR = $CI->general->get('declaracion_mod_tercero',['dt_modulo' => $modulo, 'dt_padre' => $padre, 'dt_usuario' => $usu],'array');

    $id = '';
    $datas = [];
    if(isset($dataR) and !empty($dataR)){
        $id        = $dataR['dt_id'];
        $datas = $CI->declaraciones->mod_terceros($padre,$modulo);
    }

    $CI->load->view('modal/declaracion/shared/v_tercero',array('page' => $page,'id' => encriptar($id),'datas' => $datas,'padre' => encriptar($padre),'module' => encriptar($modulo),
        'relacion'    => $CI->general->all_get('conf_parentesco_relacion'),
        'persona'     => $CI->general->all_get('conf_tipo_persona')
    ));

}

function extends_actividad_laboral($page = '',$modulo = '',$padre = '',$title = 'Entidad federativa'){
    //mostrar view
    //obtener la actividad principal
    $CI = &get_instance();
    $CI->load->model('general');
    $CI->load->model('m_declaracion','declaraciones');
    $usu = $CI->session->userdata('user_id');

    $dataR = $CI->general->get('declaracion_mod_actividad',($padre != '') ? ['dm_padre' => $padre,'dm_modulo' => $modulo, 'dm_usuario' => $usu] : ['dm_modulo' => $modulo, 'dm_usuario' => $usu],'array');
    $id = '';
    $actividad = '';
    $otro = '';
    $entidad = [];
    if(isset($dataR) and !empty($dataR)){
        $id        = $dataR['dm_id'];
        $actividad = $dataR['dm_entidad'];
        $otro = $dataR['dm_otro'];
        if($actividad == 1){
            $entidad = $CI->general->get('declaracion_mod_actividad_privado',['dmv_actividad' => $id],'array');
        }elseif($actividad == 2){
            $entidad = $CI->general->get('declaracion_mod_actividad_publico',['dmp_actividad' => $id],'array');
        }else{
            $entidad = $CI->general->get('declaracion_mod_actividad_otro',   ['dmo_actividad' => $id],'array');
        }
    }
    $data = [
        'actividad' => $actividad,
        'entidad'   => $entidad,
        'otro'   => $otro,
    ];


    $CI->load->view('modal/declaracion/shared/v_actividad_laboral',array('page' => $page,'title' => $title,'id' => ($id),'padre' => ($padre),'module' => encriptar($modulo),'datas' => $data,
        'actividad_laboral'   => $CI->general->all_get('conf_actividad_laboral'),
        'orden_gobierno'      => $CI->general->all_get('conf_nivel_orden_gobierno'),
        'ambito_publico'      => $CI->general->all_get('conf_ambito_publico'),
        'si_no'               => $CI->general->all_get('conf_si_no'),
        'sector'              => $CI->general->all_get('conf_sector'),
    ));
}


function extends_domicilio($page = '',$modulo = '',$padre = '',$country = ''){
    //mostrar view
    $CI = &get_instance();
    $CI->load->model('general');
    $CI->load->model('m_declaracion','declaraciones');

    $usu = $CI->session->userdata('user_id');

    $id = desencriptar($padre);
    if($country == 1){
        $data = $CI->general->get('declaracion_mod_domicilio_mexico',['dm_modulo' => $modulo, 'dmm_padre' => $id, 'dmm_usuario' => $usu],'array');
        $vista = 'modal/declaracion/domicilio/v_mexico';
    }else{
        $data = $CI->general->get('declaracion_mod_domicilio_extranjero',['dme_modulo' => $modulo, 'dme_padre' => $id, 'dme_usuario' => $usu],'array');

        $vista = 'modal/declaracion/domicilio/v_exterior';
    }
    $CI->load->view($vista,array(
        'page' => $page,'id' => $padre,'module' => encriptar($modulo),'datas' => $data,'country' => encriptar($country),
        'entidad_federal'    => $CI->general->all_get('conf_entidad_federal'),
        'municipios'         => $CI->general->all_get('conf_entidad_federal_municipios'),
        'paises'             => $CI->general->all_get('conf_paises'),
    ));
}

function extends_economico($page,$vista,$data,$modal,$extra = []){
    //mostrar view
    $CI = &get_instance();
    $CI->load->view($vista,array('datas'  => $data,'modal' => $modal,'extra' => $extra));
}

//    Mod not view

function modal_mod_extends_tercero($modulo = '',$padre = '',$tipo = '',$prefix = ''){
    //mostrar view

    $CI = &get_instance();
    $CI->load->model('general');
    $CI->load->model('m_declaracion','declaraciones');
    $usu = $CI->session->userdata('user_id');

    $id = desencriptar($padre);
    if($tipo == 1){
        $data = $CI->general->get('declaracion_mod_tercero_fisica',['dtf_modulo' => $modulo, 'dtf_tercero' => $id, 'dtf_usuario' => $usu],'array');
    }else{
        $data = $CI->general->get('declaracion_mod_tercero_moral',['dtm_modulo' => $modulo, 'dtm_tercero' => $id, 'dtm_usuario' => $usu],'array');
    }

    $vista = 'modal/declaracion/shared/v_no_modal_titular';

    $CI->load->view($vista,array(
        'prefix' => $prefix,
        'id' => $padre,
        'module' => encriptar($modulo),
        'datas' => $data,
        'entidad_federal'    => $CI->general->all_get('conf_entidad_federal'),
        'municipios'         => $CI->general->all_get('conf_entidad_federal_municipios'),
        'paises'             => $CI->general->all_get('conf_paises'),
    ));
}

function modal_mod_extends_fisica_moral($modulo = '',$padre = '',$tipo = '',$prefix = '',$uniq = ''){
    //mostrar view
    $CI = &get_instance();
    $CI->load->model('general');
    $CI->load->model('m_declaracion','declaraciones');
    $id = desencriptar($padre);

    $usu = $CI->session->userdata('user_id');

    if($tipo == 1){
        $data = $CI->general->get('declaracion_mod_fm_fisica',['dtf_modulo' => $modulo, 'dtf_padre' => $id, 'dtf_usuario' => $usu],'array');
    }else{
        $data = $CI->general->get('declaracion_mod_fm_moral',['dtm_modulo' => $modulo, 'dtm_padre' => $id, 'dtm_usuario' => $usu],'array');
    }

    $vista = 'modal/declaracion/shared/v_no_modal_titular';
    $CI->load->view($vista,array(
        'uniqu' => $uniq,
        'prefix' => $prefix,
        'id' => $padre,
        'module' => encriptar($modulo),
        'datas' => $data,
        'entidad_federal'    => $CI->general->all_get('conf_entidad_federal'),
        'municipios'         => $CI->general->all_get('conf_entidad_federal_municipios'),
        'paises'             => $CI->general->all_get('conf_paises'),
    ));
}

function modal_mod_extends_domicilio($page = '',$modulo = '',$padre = '',$country = '',$prefix = ''){
    //mostrar view
    $CI = &get_instance();
    $CI->load->model('general');
    $CI->load->model('m_declaracion','declaraciones');

    $id = desencriptar($padre);
    $usu = $CI->session->userdata('user_id');
    if($country == 1){
        $data = $CI->general->get('declaracion_mod_domicilio_mexico',['dm_modulo' => $modulo, 'dmm_padre' => $id, 'dmm_usuario' => $usu],'array');
    }else{
        $data = $CI->general->get('declaracion_mod_domicilio_extranjero',['dme_modulo' => $modulo, 'dme_padre' => $id, 'dme_usuario' => $usu],'array');
    }

    $vista = 'modal/declaracion/domicilio/v_no_modal_domicilio';

    $CI->load->view($vista,array(
        'prefix'             => $prefix,
        'page'               => $page,
        'id'                 => $padre,
        'module'             => encriptar($modulo),
        'datas'              => $data,
        'entidad_federal'    => $CI ->general->all_get('conf_entidad_federal'),
        'municipios'         => $CI ->general->all_get('conf_entidad_federal_municipios'),
        'paises'             => $CI ->general->all_get('conf_paises'),
    ));
}