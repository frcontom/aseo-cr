<?php


class Phpmailerlib
{
    private $mailerer;


    function connectar($config){
        require_once APPPATH.'libraries/PHPMailer/Exception.php';
        require_once APPPATH.'libraries/PHPMailer/PHPMailer.php';
        require_once APPPATH.'libraries/PHPMailer/SMTP.php';

        $this->mailer = new PHPMailer\PHPMailer\PHPMailer();

        $this->mailer->isSMTP();
        $this->mailer->Host     = $config['servidor'];
        $this->mailer->Username = $config['username'];
        $this->mailer->Password = $config['password'];
        $this->mailer->Port     = $config['port'];
        $this->mailer->SMTPAuth = true;
        $this->mailer->SMTPSecure = 'tls';
//        $this->mailer->SMTPDebug = 3; //Alternative to above constant
        $this->mailer->setFrom($config['username']);

    }

    function enviar($data,$template)
    {

        // Add a recipient
        $this->mailer->addAddress($data['correo']);

        $this->mailer->Subject = $data['titulo'];

        $this->mailer->isHTML(true);
        $this->mailer->Body = $template;

        // Send email
        if($this->mailer->send()){
            $result = array('success' => 1);
        }else{
            $result = array('success' => 2, 'msg' => 'Algo salio mal con el SMTP');
        }

        return $result;
    }

}