<?php



if(!function_exists('auth')){

    //validate user auth
    function auth($page = ''){
        //sessions
        $CI = & get_instance();
        $in_token    = $CI->session->userdata('user_token');
        $token       = $CI->class_security->limpiar_form($in_token) ?: '';

        if(strlen($token) >= 1){
            //validate security
            $data =   $CI->db->from('users')->where(
                array(
                    'u_token' => $token,
                    'u_status' => 1
                )
            )->get()->result();

            $count = count($data);

            if($count == 0){

                if($page != 'login'){
                    redirect(base_url('login'));
                }else{
                    //validate permissions
//                  echo $page;
                    if($page == 'login'){
                        // redirect(base_url());
                    }
                }
            }else{

                if($page == 'login'){
                    redirect(base_url());
                }

            }
        }else{
            if($page != 'login'){
                redirect(base_url('login'));
            }
        }
    }

}


if(!function_exists('validar_declaracion')){

    //validate user auth
    function validar_declaracion(){
        //sessions
        $CI          = & get_instance();
        $usuario        = $CI->session->userdata('user_id');
        $declaracion    = $CI->session->userdata('declaracion');

        if(!empty($usuario) and !empty($declaracion)){
            //validate security
            $data =   $CI->db->from('declaracion')->where(
                array(
                    'dc_id'      => $declaracion,
                    'dc_usuario' => $usuario,
                    'dc_estado'  => 1
                )
            )->get()->row_array();
            if(empty($data)) {
                redirect(base_url());
            }
        }else{
            redirect(base_url('login'));
        }
    }

}