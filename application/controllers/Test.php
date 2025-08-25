<?php

class Test extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    function index(){


        $this->load->library('Pdf');
        $fileQ = $this->pdf->pdfSteam('pdf/pdf_test');


//        $queryEmail[] = ['u_email' => 'feconto@gmail.com'];
//        $attactment = [[$fileQ,'pdf_beta.pdf'],[$fileQ,'morfosis.pdf']];
////        print_r($attactment);exit;
//        for($i = 0; $i < 10; $i++){
//            $this->class_security->send_mailer($queryEmail,'Nuevo Evento codigo #','emails/v_test',$attactment,[]);
//
//        }

    }

}