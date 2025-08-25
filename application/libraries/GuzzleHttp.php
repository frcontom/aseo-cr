<?php

class GuzzleHttp
{
    private $client;
    function __construct(){
        require 'application/libraries/vendor/autoload.php';
        $this->client = new GuzzleHttp\Client();
    }

    function request($method, $url, $data=[]){
        return $this->client->request($method, $url,$data);
    }



}