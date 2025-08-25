<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pdf
{
    public $options;
    public $dompdf;
    function __construct(){

    }

    function pdfSteam($view,$data = [],$filename = 'example.pdf') {
        $CI = &get_instance();
//        $filename = 'pdf-download';
        require 'vendor/autoload.php';
        $options = new \Dompdf\Options;
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('set_paper', 'A4');
        $options->set('set_paper', 'potrait');
        $dompdf = new \Dompdf\Dompdf($options,);
        $html = $CI->load->view($view, ['data' => $data],true);
        $dompdf->loadHtml($html,'UTF-8');
        $dompdf->render();

//        header('Content-Type: application/pdf; charset=utf-8');
//        header('Content-disposition: inline; filename="' .  $filename . '.pdf"', true);

        return $dompdf->output();
    }
    function pdfview($view,$data = [],$filename = 'example.pdf') {
        $CI = &get_instance();
//        $filename = 'pdf-download';
        require 'vendor/autoload.php';
        $options = new \Dompdf\Options;
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('set_paper', 'A4');
        $options->set('set_paper', 'potrait');
        $dompdf = new \Dompdf\Dompdf($options,);
        $html = $CI->load->view($view, ['data' => $data],true);
        $dompdf->loadHtml($html,'UTF-8');
        $dompdf->render();

        header('Content-Type: application/pdf; charset=utf-8');
        header('Content-disposition: inline; filename="' .  $filename . '.pdf"', true);

        echo $dompdf->output();
    }

    function pdfDownload($view = '',$data = [],$documento = '')
    {
        $CI = &get_instance();
        $filename = 'pdf-download';
        require 'vendor/autoload.php';
        $options = new \Dompdf\Options;
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('set_paper', 'A4');
        $options->set('set_paper', 'potrait');
        $dompdf = new \Dompdf\Dompdf($options,);
        $html = $CI->load->view($view, ['data' => $data],true);
        $dompdf->loadHtml($html,'UTF-8');
        $dompdf->render();

        return $dompdf->stream($documento);
//        header('Content-Type: application/pdf; charset=utf-8');
//        header('Content-disposition: inline; filename="' .  $filename . '.pdf"', true);
//        echo $dompdf->output();
    }

    function pdfSave($view = '',$data = [],$file_folder = '')
    {
        $CI = &get_instance();
        $filename = 'pdf-download';
        require 'vendor/autoload.php';
        $options = new \Dompdf\Options;
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('set_paper', 'landscape');
        $options->set('set_paper', 'potrait');
        $dompdf = new \Dompdf\Dompdf($options,);
        $html = $CI->load->view($view, ['data' => $data],true);
        $dompdf->loadHtml($html,'UTF-8');
        $dompdf->render();

        $output = $dompdf->output();
        file_put_contents($file_folder, $output);

//        header('Content-Type: application/pdf; charset=utf-8');
//        header('Content-disposition: inline; filename="' .  $filename . '.pdf"', true);
//        echo $dompdf->output();
    }


}

