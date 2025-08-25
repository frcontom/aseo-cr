<?php
defined('BASEPATH') or exit('No direct script access allowed');

function encriptar_password($password){
    $encripfullaccess = md5(sha1($password) . sha1('abcdefghijkmnloprstz'));
    $md5passnivelfinal = md5($encripfullaccess . "()?=/|\/##[]{}-_");
    return $md5passnivelfinal;
}

function generate_token($id_usuario) {
    return md5(uniqid($id_usuario, true) . sha1((bin2hex(random_bytes(10)))) . md5(date("Y-m-d h:m:s")) . md5(date(mktime(date("Y")))));
}

function encriptar($string){
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'qwertyuiopasdfghjklñzxcvbnm,789456123';
    $secret_iv = 'Oldsafe';
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
    $output = base64_encode($output);
    return $output;
}

function desencriptar($string = ''){
    $output = '';

    if(isset($string) and $string != null and  strlen($string) >= 5){
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'qwertyuiopasdfghjklñzxcvbnm,789456123';
        $secret_iv = 'Oldsafe';
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}

?>