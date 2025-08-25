<?php

class WindowLogge extends CI_Controller
{

    //propiedades
    private $session_id;
    private $session_token;
    public $project;
    private $result = array();

    public function __construct(){
        parent::__construct();
        $this->project        = $this->config->config['project'];
    }


    function box_window(){
        $data_head = array(
            'titulo'        => $this->project['tiulo'],
        );

        $box_2 = $this->general->query("select * from booking As b
            JOIN booking_days As bd ON b.b_id=bd.bd_booking
            LEFT JOIN booking_box_ready As br ON b.b_id=br.br_booking AND bd.bd_id=br_booking_day
            WHERE bd.bd_day = '".$this->class_security->sumar_dias(0)."' and bd.bd_type IN(1,2)  and br.br_booking IS NULL
            ");

        $box_3 = $this->general->query("select * from booking As b
            JOIN booking_days As bd ON b.b_id=bd.bd_booking
            LEFT JOIN booking_box_ready As br ON b.b_id=br.br_booking AND bd.bd_id=br_booking_day
            WHERE bd.bd_day = '".$this->class_security->sumar_dias(1)."' and bd.bd_type IN(3)  and br.br_booking IS NULL
            ");

        $data_body = array(
            'datas' => array_merge($box_2, $box_3),
            'crud' => array(
                'url_save'        => base_url("box_event_save"),
            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','plugins/countdown/jquery.countdown.js','cr/window_box.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('single/v_box_open_window',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }


    function box_event_save(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('booking','day','title'))){

                //campos post
                $booking          = $this->class_security->data_form('booking','decrypt_int');
                $day          = $this->class_security->data_form('day','decrypt_int');
                $title          = $this->class_security->data_form('title');


                $fecha =  fecha(2);
                $data = array(
                    'br_booking'         => $booking,
                    'br_booking_day'     => $day,
                    'br_ready'           => 1,
                    'br_observation'     => '',
                    'br_atcreate'        => fecha(2),
                );

                //validar la duplicidad del username or emailes
                $this->result =   $this->general->create_update('booking_box_ready',array('br_booking' => $booking,'br_booking_day' => $day),$data);

                $message_wts = "*Se finalizo la tarea box breakfast* \n Nombre del evento: *{$title}* \nHora Finalizacion: *{$fecha}* ";
                send_message_whatsapp($message_wts,'endpoint_box');

            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

}