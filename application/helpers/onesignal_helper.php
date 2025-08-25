    <?php

    function sendNotification($title,$body,$subscriptors = []) {
        $CI =& get_instance();
        $CI->load->library('GuzzleHttp');
        $CI->load->config('cproject');
        $CI->load->library('class_security');
        $client = new GuzzleHttp();
        $project = $CI->config->config['project'];

        //filters all subscriptors

        $subscriptor = array_filter(array_column($subscriptors, 'u_phone'), function($email) {
            return !empty($email);
        });


        if(!empty($subscriptor)){
            try{

                $body_in  = $CI->class_security->forceUtf8($body);

                foreach($subscriptor as $phone){
                    if($phone != ''){
                        $client->request('POST', $project['wts']['messages'],
                            [
                                'headers' => [
                                    'Content-Type' => 'application/json',
                                    'Authorization' => 'Basic '.$project['wts']['token'],
                                ],
                                'body' => json_encode(
                                    [
                                        'number' =>  (string)$phone,
                                        'body' =>  $body_in,
                                    ]
                                )
                            ]);
                    }

                }

            }catch (Exception $e){

            }

        }

    }


    function sendNotificationFormFile($body,$fileName = '',$subscriptors = [],$filemine = 'image/png') {
        $CI =& get_instance();
        $CI->load->library('GuzzleHttp');
        $CI->load->config('cproject');
        $CI->load->library('class_security');
        $client = new GuzzleHttp();
        $project = $CI->config->config['project'];

        //filters all subscriptors

        $subscriptor = array_filter(array_column($subscriptors, 'u_phone'), function($email) {
            return !empty($email);
        });

        if(!empty($subscriptor)){
            try{

                $body_in  = $CI->class_security->forceUtf8($body);

                foreach($subscriptor as $phone){
                    if($phone != ''){

                        $client->request('POST', $project['wts']['messages'],
                            [
                                'headers' => [
                                    'Authorization' => 'Bearer '.$project['wts']['token'],
                                ],
                                'multipart' => [
                                    [
                                        'name' => 'medias',  // Nombre del campo de archivo en el formulario
                                        'contents' => fopen($fileName, 'r'),
                                        'filename' => basename($fileName),  // Nombre del archivo que se enviará
                                        'headers' => ['Content-Type' => $filemine]  // Ajusta el tipo de contenido si es necesario
                                    ],
                                    [
                                        'name' => 'number',  // Nombre del campo 'var1'
                                        'contents' => $phone
                                    ],
                                    [
                                        'name' => 'body',  // Nombre del campo 'var2'
                                        'contents' => $body_in
                                    ]
                                ]
                            ]);
                    }

                }

            }catch (Exception $e){
//                print_r($e->getMessage());
            }

        }

    }

    function sendNotificationFormFileGroup($body,$fileName = '',$data = []) {
        $CI =& get_instance();
        $CI->load->library('GuzzleHttp');
        $CI->load->config('cproject');
        $CI->load->library('class_security');
        $client = new GuzzleHttp();
        $project = $CI->config->config['project'];


            try{
                $client->request('POST', $data['endpoint'],
                    [
                        'headers' => [
                            'Authorization' => 'Bearer '.$data['token'],
                        ],
                        'multipart' => [
                            [
                                'name' => 'medias',  // Nombre del campo de archivo en el formulario
                                'contents' => fopen($fileName, 'r'),
                                'filename' => basename($fileName),  // Nombre del archivo que se enviará
                                'headers' => ['Content-Type' => 'image/png']  // Ajusta el tipo de contenido si es necesario
                            ],
                            [
                                'name' => 'fromMe',  // Nombre del campo 'var1'
                                'contents' => true
                            ],
                            [
                                'name' => 'isPrivate',  // Nombre del campo 'var1'
                                'contents' => false
                            ],
                            [
                                'name' => 'body',  // Nombre del campo 'var2'
                                'contents' => $CI->class_security->forceUtf8($body)
                            ]
                        ]
                    ]);

            }catch (Exception $e){
//                print_r($e->getMessage());
            }


    }