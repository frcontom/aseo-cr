<?php

class Mailer
{
    protected $CI;
    private $to_email = '';
    private $cc_email = '';
    private $bcc_email = '';
    private $reply_to_email = '';
    private $subject_email = '';
    private $attachment_email = array();
    private $template;
    private $msg;
    private $data_template;
    private $config;

    public function __construct()
    {

        require_once APPPATH.'libraries/PHPMailer/Exception.php';
        require_once APPPATH.'libraries/PHPMailer/PHPMailer.php';
        require_once APPPATH.'libraries/PHPMailer/SMTP.php';
    }

    function initialize($parameters = array())
    {
        $this->CI =& get_instance();

        $this->config = $parameters;
        //call data
//        $this->config = $this->CI->config->config['project'];


        if (isset($parameters["to"])) {
            $this->to_email = $parameters["to"];
        }
        if (isset($parameters["cc"])) {
            $this->cc_email = $parameters["cc"];
        }
        if (isset($parameters["bcc"])) {
            $this->bcc_email = $parameters["bcc"];
        }
        if (isset($parameters["reply_to"])) {
            $this->reply_to_email = $parameters["reply_to"];
        }
        if (isset($parameters["subject"])) {
            $this->subject_email = $parameters["subject"];
        }

        if (isset($parameters["template"])) {
            $this->template = $parameters["template"];
        }
        if (isset($parameters["msg"])) {
            $this->msg = $parameters["msg"];
        }

        if (isset($parameters["attachment"])) {
            $this->attachment_email = $parameters["attachment"];
        }

        if (isset($parameters["data"])) {
            $this->data_template = $parameters["data"];
        }

    }

    function send_mail()
    {
        $flag = true;
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        try {

//           $mail->SMTPDebug = 2;                               // Enable verbose debug output
//            $mail->Debugoutput = 'html';
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->SMTPAuth = TRUE;                               // Enable SMTP authentication
            $mail->Mailer = "smtp";
            $mail->CharSet = 'UTF-8';


            //configuration
            $mail->Host = $this->config['email']['servidor'];
            $mail->Username = $this->config['email']['username'];
            $mail->Password = $this->config['email']['password'];
            $mail->Port = $this->config['email']['port'];
            $mail->SMTPSecure = $this->config['email']['secure'];

            $mail->setFrom($this->config['email']['from'], $this->config['email']['from_name']);


            if(is_array($this->to_email)){
                foreach ($this->to_email as $email) {
                    $mail->addAddress($email);
                }
            }else{
                $mail->addAddress($this->to_email);
            }

            if ($this->reply_to_email != "") {
                $mail->addReplyTo($this->reply_to_email);
            }
            if ($this->cc_email != "") {
                $mail->addCC($this->cc_email);
            }
            if ($this->bcc_email != "") {
                $mail->addBCC($this->bcc_email);
            }
//            print_r(count($this->attachment_email));exit;
            if (count($this->attachment_email) > 0) {
                foreach ($this->attachment_email as $attach) {
                    $file_path = "";
                    if(count($attach) == 1){
                        $mail->addAttachment($attach[0]);
                    }else{
                        $mail->addStringAttachment($attach[0], $attach[1]);
                    }
//                    if (isset($attach[0]) and $attach[0] != "") {
//                        $file_path = $attach[0];
//                    }
//                    $file_name = "";
//                    if (isset($attach[0]) and $attach[0] != "") {
//                        $file_name = $attach[0];
//                    }
//                    $mail->addStringAttachment($attach,'file.pdf');         // Add attachments
                }
            }
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $this->subject_email;
            if ($this->msg != '') {
                $mail->msgHTML($this->msg);
            } else {
                $mail->msgHTML($this->content_email());
            }


            if (!$mail->send()) {
                $flag = false;
            }

        } catch (Exception $e) {
            $flag = false;
        }
        return $flag;

    }

    private function content_email()
    {
        $data = array(
            'imagen' => isset($this->config['email']['logo']) ? $this->config['email']['logo'] : '',
            'data' => $this->data_template
        );
        $content = $this->CI->load->view($this->template, $data, TRUE);

        return $content;
    }

}