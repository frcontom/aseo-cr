<?php
defined('BASEPATH') or exit('No direct script access allowed');


class M_login extends CI_Model{

    //tables
    private $user    = 'users';

    function validate_login($username,$password){
        //validar existencia de usuario
        $getValidate = $this->general->get($this->user,array('u_username' => $username),'array');
        if(isset($getValidate) and !empty($getValidate)){
                if($this->general->exist($this->user,array('u_username' => $username,'u_password' => $password))){

                    $data = $this->general->get($this->user,array('u_username' => $username,'u_password' => $password));
                    $result = array('success' => 1,'data' => $data);

                }else{
                    $result = array('success' => 2,'msg' => 'Usuario / Contraseña incorrectos');
                }
        }else{
            //el username no existe
            $result = array('success' => 2,'msg' => 'La cuenta no se encuentra registrada.');
        }
        return $result;
    }

    function validate_user($username){
        //validar existencia de usuario
        if($this->general->exist($this->user,array('u_username' => $username))){
                //validar cuenta
                if($this->general->exist($this->user,array('u_username' => $username,'u_status' => 1))){
                        $data = $this->general->get($this->user,array('u_username' => $username));
                        $result = array('success' => 1,'data' => $data);
                }else{
                    $result = array('success' => 2,'msg' => 'Validar la cuenta con soporte');
                }

        }else{
            //el username no existe
            $result = array('success' => 2,'msg' => 'El declarante no existe');
        }
        return $result;
    }

    function save_token($id = '',$token = ''){
        if(strlen($id) >= 1){
            $this->general->update($this->user,array('u_id' => $id),array('u_token' => $token));
        }
    }


    function validate_token($id,$token){
       $user_login =  $this->db->from($this->user)
            ->where(
                array(
                    'u_id'      => $id,
                    'u_token'   => $token,
                    'u_status'  => 1,
                    'u_alta'    => 1
                )
            )
            ->count_all_results();

       if($user_login == 1):
           $this->estado = true;
        else:
            $this->estado = false;
       endif;
       return $this->estado;
    }
}