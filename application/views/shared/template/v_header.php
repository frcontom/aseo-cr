<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?=$titulo; ?></title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?=base_url('assets/'); ?>images/favicon.png">
    <link href="<?=base_url('assets/'); ?>vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?=base_url('assets/'); ?>vendor/chartist/css/chartist.min.css">
    <link rel="stylesheet" href="<?=base_url('assets/'); ?>vendor/select2/css/select2.min.css">
    <link rel="stylesheet" href="<?=base_url('assets/'); ?>vendor/sweetalert2/dist/sweetalert2.min.css">
    <link href="<?=base_url('assets/'); ?>plugins/lobibox/css/lobibox.css" rel="stylesheet">
    <!-- Vectormap -->
    <link href="<?=base_url('assets/'); ?>vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <!-- Custom Stylesheet -->
    <link href="<?=base_url('assets/'); ?>vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="<?=base_url('assets/'); ?>vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="<?=base_url('assets/'); ?>css/style.css" rel="stylesheet">
    <link href="<?=base_url('assets/'); ?>css/local.css" rel="stylesheet">
    <link href="<?=base_url('assets/'); ?>vendor/owl-carousel/owl.carousel.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.14.0/jquery.timepicker.min.css" rel="stylesheet">
    <!-- Pick date -->
    <link rel="stylesheet" href="<?=base_url('assets/'); ?>vendor/pickadate/themes/default.css">
    <link rel="stylesheet" href="<?=base_url('assets/'); ?>vendor/pickadate/themes/default.date.css">


    <!--  Socket.IO  -->
    <script src="https://cdn.socket.io/4.8.1/socket.io.min.js"></script>

    <script>
        var project_title = "<?php echo $titulo; ?>";
        var url_sitio = "<?php echo base_url(); ?>";
    </script>
    <?php
    if((isset($style_level)) AND (!empty($style_level)))
    {
        foreach($style_level As $style_lv):
            $url_style_level = base_url('assets/').$style_lv;
            echo "<link  rel='stylesheet' type='text/css'  href='$url_style_level'>
";
        endforeach;
    }

/*
    <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
    <script>
        window.OneSignal = window.OneSignal || [];

        var OneSignal = window.OneSignal || [];


        window.OneSignalDeferred = window.OneSignalDeferred || [];
        OneSignalDeferred.push(function(OneSignal) {
            OneSignal.init({
                appId: "<?=$this->project['onesignal']['app_id']?>",
                autoResubscribe:true,
                notifyButton: {
                    enable: true,
                },
                promptOptions: {
                    slidedown: {
                        enabled: true,
                        prompts: [
                            {
                                type: "push",  // Tipo de prompt
                                text: {
                                    actionMessage: "¡Permítenos enviarte notificaciones para estar al tanto de nuestras últimas novedades!",  // Mensaje principal
                                    acceptButton: "¡Claro!",  // Texto del botón de aceptación
                                    cancelButton: "No, gracias"  // Texto del botón de cancelación
                                }
                            }
                        ]
                    }
                }
            });


        });


        function pushSubscriptionChangeListener(event) {
            if (event.current.token) {
                $(this).valite_notify(event.current);
            }
        }

        OneSignalDeferred.push(function() {
            OneSignal.User.PushSubscription.addEventListener("change", pushSubscriptionChangeListener);
        });

    </script>
    */
       ?>
</head>
<body>

<!--*******************
    Preloader start
********************-->
<div id="preloader">
    <div class="sk-three-bounce">
        <div class="sk-child sk-bounce1"></div>
        <div class="sk-child sk-bounce2"></div>
        <div class="sk-child sk-bounce3"></div>
    </div>
</div>
<!--*******************
    Preloader end
********************-->

<!--**********************************
    Main wrapper start
***********************************-->
<div id="main-wrapper">
