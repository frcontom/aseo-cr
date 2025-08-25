<?php

class Process_queue extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
//        $this->load->library('queue'); // Cargar la biblioteca Queue
//        $this->load->model('Job_model'); // Cargar el modelo Job_model
    }

    function process_mailer(){
        try {

            $job = $this->queue->dequeue('send_mailer');  // Obtener el siguiente trabajo de la cola


            if(isset($job) and !empty($job)) {
                foreach ($job as $item) {

                    //variables query
                    $job_id = $item['id']; // ID del trabajo
                    $job_payload = json_decode($item['payload'], true); // Datos del trabajo
                    if(isset($job_payload['data']) and !empty($job_payload['data'])) {

                        $job_message = $job_payload['message'];
                        $job_data = $job_payload['data'];
                        $job_template = $job_payload['template'];
                        $job_attachment = $job_payload['attachment'];
                        $job_emails = $job_payload['emails'];

                        // Enviar mensaje de WhatsApp
                        $this->class_security->send_mailer($job_emails,$job_message,$job_template,$job_attachment,$job_data);


                        // Marcar el trabajo como completado
                        $this->queue->complete($job_id);
                    }
                }
            }

        }catch (Exception $e){
            //error handling
        }
    }


    function process_whatsapp(){
        try {

            $job = $this->queue->dequeue('send_whatsapp');  // Obtener el siguiente trabajo de la cola

            if(isset($job) and !empty($job)) {
                foreach ($job as $item) {

                    //variables query
                    $job_id = $item['id']; // ID del trabajo
                    $job_payload = json_decode($item['payload'], true); // Datos del trabajo
                    if(isset($job_payload['data']) and !empty($job_payload['data'])) {

                        $job_message = $job_payload['message'];
                        $job_data = $job_payload['data'];

                        // Enviar mensaje de WhatsApp
                        sendNotification('Vora', $job_message, $job_data);

                        // Marcar el trabajo como completado
                        $this->queue->complete($job_id);
                    }
                }
            }

        }catch (Exception $e){
            //error handling
        }
    }





}