<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$titulo = "Vora";
//project data
$config['project']['tiulo'] = $titulo;
$config['project']['domain'] = "http:///";

//project email SMTP
$config['project']['email']['from_name']   = $titulo;
$config['project']['email']['from']        = 'ticket@gpr.cr';
$config['project']['email']['servidor']    = 'smtp.office365.com';
$config['project']['email']['username']    = 'ticket@gpr.cr';
$config['project']['email']['password']    = 'Password-3434';
$config['project']['email']['port']        = '587';
$config['project']['email']['secure']      = 'tls';

//mailtrap
//$config['project']['email']['from']        = 'ticket@gpr.cr';
//$config['project']['email']['servidor']    = 'sandbox.smtp.mailtrap.io';
//$config['project']['email']['username']    = '5e889a779e67f6';
//$config['project']['email']['password']    = '6f57d72d3a3451';
//$config['project']['email']['port']        = '587';
//$config['project']['email']['secure']      = 'tls';

//Whatsapp
$config['project']['wts']['endpoint_login']     = 'https://api.dozvox.com/auth/login';
$config['project']['wts']['messages']           = 'https://api.dozvox.com/api/messages/send';
$config['project']['wts']['endpoint']           = 'https://api.dozvox.com/messages/40';
$config['project']['wts']['endpoint_box']       = 'https://api.dozvox.com/messages/45';
$config['project']['wts']['endpoint_test']      = 'https://api.dozvox.com/messages/39';
$config['project']['wts']['username']           = 'support@gpr.cr';
$config['project']['wts']['password']           = 'qwerty123.';
//$config['project']['wts']['password']           = 'demo1234';
$config['project']['wts']['token']              = 'tkTiSkUTxdI8h3tSzW48dwFNRiWbME';



//oneSignal
$config['project']['onesignal']['endpoint']   = 'https://api.onesignal.com/notifications';
//localhost
//$config['project']['onesignal']['app_id']     = '68a0d162-8cc8-4140-b71a-9e3a6c5ecb54';
//$config['project']['onesignal']['token']      = 'YzQ1NDViZTAtMDhiNC00NjUzLTk2MTItMjZhNTJiOGM0ZGIw';
//productions
$config['project']['onesignal']['app_id']     = '3e537989-76b2-4619-a4f0-bc5a1da5e8eb';
$config['project']['onesignal']['token']      = 'OWM3M2RmMTQtNWY5Yi00OWJhLThkMDItYjkyODhlNmIyYWVk';


//version All files JS or CSS
$config['project']['version']     = '1.7';