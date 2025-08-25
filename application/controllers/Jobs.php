<?php

class Jobs extends CI_Controller
{


    public $project;
    private $result = array();

    public function __construct(){
        parent::__construct();
        $this->project        = $this->config->config['project'];
    }

    public function call_box_reserved(){

        $day = $this->class_security->sumar_dias(1);
        $query_All_days = $this->general->query("select * from booking As b
    JOIN booking_days As bd ON b.b_id=bd.bd_booking
WHERE bd.bd_day = '".$day."' and bd.bd_type IN(3)
");
        if(isset($query_All_days) and count($query_All_days) >= 1){
            foreach($query_All_days as $row){
                $name = $row['b_event_name'];
                $box_type =         (isset($row['bd_type']) and $row['bd_type'] != '') ? $this->class_data->typeBox[$row['bd_type']] : '';
                $box_account = $row['bd_account'];
                $observation_int = $row['bd_description'];
                $time_fornat = $this->class_security->format_date("{$row['bd_day']} {$row['bd_houri']}","d-m-Y  g:i:s A");


                $message_wts = "EL evento: *{$name}* \nTipo: *{$box_type}* \nrequiere cantidad de: *{$box_account}* \nEntrega: *{$time_fornat}*\nObservación: *{$observation_int}*";
                send_message_whatsapp($message_wts,'endpoint_box');
            }
        }
        echo "------------------- SEND CALL BOX ------------------------";
    }


    //Biometric
//    function

}