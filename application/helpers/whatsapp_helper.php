<?php


if(!function_exists('call_whatsapp_login')) {

     function whatsapp_login(){
        $CI = &get_instance();

        $project = $CI->config->config['project'];

        $user = $project['wts'];

        $CI->load->library('GuzzleHttp');
        $mts = new GuzzleHttp\Client();


        //create all JSON data
        try {
            $data =    $mts->post($project['wts']['endpoint_login'],
                [
                    'header' => [
                        'Content-Type' => 'application/json',
                    ],
                    'json' => [
                        'email'      => $user['username'],
                        'password'   => $user['password'],
                        'rememberMe' => false,
                    ]
                ])->getBody()->getContents();
            $body = json_decode($data, true);
            return  [
                'mts' => $mts,
                'data' => isset($body['token']) ?  $body['token'] : ''
            ];
        }catch (Exception $e){
            return  [
                'mts' => $mts,
                'data' => ''
            ];
        }
    }

    function send_message_whatsapp($message = '',$end_point = 'endpoint'){
        $CI = &get_instance();

        $callMe =   whatsapp_login(); // login whatsapp

        $project = $CI->config->config['project'];

        $mts = $callMe['mts'];
        $token = $callMe['data'];
        if($token != ''){

            try{
                echo  $mts->post($project['wts'][$end_point],
                    [
                        'headers' => [
                            'Content-Type' => 'application/json',
                            'Authorization' => 'Bearer '.$token,
                        ],
                        'json' => [
                            "read" => 1,
                            "fromMe"=>1,
                            "mediaUrl"=>"",
                            "body" => $message,
                            "quotedMsg" => "",
                            "isPrivate"=>"false"
                        ]
                    ])->getBody()->getContents();
                return array('success' => 1);

            }catch (Exception $e){
                return  array('success' => 2,'msg' => 'Error de comunicacion');
            }
        }else{
            return  array('success' => 2,'msg' => 'EL mensaje no se puedo enviar');
        }
    }
}