
<!DOCTYPE html>
<html lang="en" class="h-100">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="" />
    <meta name="author" content="" />
    <meta name="robots" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no">

    <!-- PAGE TITLE HERE -->
    <title><?=$titulo; ?> | Biometric</title>
    <!-- FAVICONS ICON -->
    <link rel="shortcut icon" type="image/png" href="<?=base_url('assets/'); ?>images/favicon.png" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons"rel="stylesheet">
    <link href="<?=base_url('assets/'); ?>css/style.css" rel="stylesheet">
    <link href="<?=base_url('assets/'); ?>css/camera.css" rel="stylesheet">

    <link rel="stylesheet" href="<?=base_url('assets/'); ?>vendor/sweetalert2/dist/sweetalert2.min.css">
    <link href="<?=base_url('assets/'); ?>plugins/lobibox/css/lobibox.css" rel="stylesheet">

    <link rel="manifest" href="<?=base_url('assets/'); ?>wpa/manifest.json">
    <style>
    #webcam-container { padding: 0; }
    #video-container { position: relative; width: 100%; }
    video { background: #000; width: 100% !important; height: auto !important; display: block; }
    canvas { position: absolute; top: 0; left: 0; pointer-events: none; }
    .loading { display: flex; align-items: center; gap: .5rem; }
    #imageDataDiv, #descriptorDiv { word-break: break-all; }

    #curtain {
        position: absolute;
        inset: 0;
        display: none; /* empieza oculto */
        background: rgba(0,0,0,.85);
        color: #fff;
        align-items: center;
        justify-content: center;
        z-index: 20; /* encima de video y canvas */
        text-align: center;
        padding: 1rem;
    }

    #curtain.show {
        display: flex; /* visible y centrado */
    }
    </style>

    <script>
        var project_title = "<?=$titulo; ?>";
        var url_sitio = "<?php echo base_url(); ?>";
    </script>
</head>

<body class="body  h-100">



<div id="curtain">
    <div>
        <div id="curtainTitle" style="font:700 20px/1.2 system-ui">Espera para el próximo registro</div>
        <div id="curtainCounter" style="font:900 64px/1.2 system-ui; margin-top:.5rem">3</div>
    </div>
</div>


<div class="container h-100">
    <div class="row h-100 align-items-center justify-contain-center">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="row m-0">
                        <div class="col-xl-6 col-md-6 sign text-center">
                            <div>
                                <div class="text-center my-5">
                                    <a href="#"><img src="<?=base_url('assets/'); ?>images/logo-dark.png" alt=""></a>
                                </div>


                                <div class="row">
                                    <div class="col-12 mx-3" style="position: relative; display: inline-block;" id="webcam-container">
                                        <div id="video-container">
                                            <div class="overlay">
                                                <div class="overlay-element top-left"></div>
                                                <div class="overlay-element top-right"></div>
                                                <div class="overlay-element bottom-left"></div>
                                                <div class="overlay-element bottom-right"></div>
                                            </div>
                                            <video id="webcam" autoplay muted playsinline></video>
                                        </div>
                                        <div id="errorMsg" class="col-12 alert-danger d-none mt-2 py-2 text-center h4" role="alert">
                                            Falló el inicio de la cámara.<br />Permite el acceso y usa un navegador compatible.
                                        </div>
                                    </div>

                                    <div class="col-12" id="camera_preview">
                                        <img src="<?=base_url('assets/'); ?>images/log.png"  class="img-fix bitcoin-img sd-shape7" id="camera_picture">
                                    </div>
                                </div>



                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6">
                            <div class="sign-in-your py-4 px-2 text-center mt-5">
                                <!--                                <h4 class="fs-20">Sign in your account</h4>-->
                                <h2 class="num-text text-black font-w600">Biometric</h2>
<!--                                <span class="text-black font-w600 text-center">Registra tu presencia de manera rápida, segura y sin complicaciones</span>-->
                                <div class="login-social ">
                                    <a href="javascript:void(0);" class="btn d-block btn-primary  my-3" style="font-size: 25px;"><i class="fa fa-clock me-2"></i> <span id="clock"></span></a>
                                </div>
                                <div class="row mt-2">

                                    <div class="col-sm-12">
                                        <div class="card mb-2">
                                            <div class="card-body" style="padding: 10px;">
                                                <div class="media align-items-center">
                                                    <div class="media-body me-3">
                                                        <h4 class="num-text text-black font-w600" style="font-size: 30px;">Recomendaciones</h4>
                                                        <span class="fs-14">Para un reconocimiento facial correcto, por favor mantén tu rostro frente a la cámara, con buena iluminación y sin gafas oscuras o mascarilla</span>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                        <div class="text-center col-12">
                                            <a id="take_picture" class="btn btn-primary btn-lg btn-block" style="font-size: 42px !important;">Iniciar Marcación</a>
                                            <a id="take_picture_stop" class="btn btn-danger btn-lg btn-block" style="font-size: 42px !important;display:none">Cancelar Marcación</a>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
if(count($crud) >= 1){
    foreach($crud As $crud_key => $crud_value){
        $hidden_name = $crud_key;
        $hidden_url  = $crud_value;
        echo "<input type='hidden' id='{$hidden_name}' value='{$hidden_url}'>\n";
    }
}
?>
<!--**********************************
    Scripts
***********************************-->
<!-- Required vendors -->
<script src="<?=base_url('assets/'); ?>vendor/global/global.min.js"></script>
<script src="<?=base_url('assets/'); ?>js/custom.min.js"></script>
<script src="<?=base_url('assets/'); ?>js/dlabnav-init.js"></script>
<script src="<?=base_url('assets/'); ?>js/validator.min.js"></script>
<script src="<?=base_url('assets/'); ?>vendor/sweetalert2/dist/sweetalert2.min.js"></script>
<!--<script src="--><?php //=base_url('assets/'); ?><!--js/styleSwitcher.js"></script>-->
<script src="<?=base_url('assets/'); ?>plugins/lobibox/js/lobibox.js"></script>

<script src='<?=base_url('assets/'); ?>plugins/face-api/face-api.min.js?ver=1.6'></script>
<script src='<?=base_url('assets/'); ?>plugins/webcam/webcam-easy.min.js?ver=1.6'></script>


<!--<script src="--><?php //=base_url('assets/'); ?><!--cr/script.js--><?php //='?ver='.(isset($this->project['version']) ? $this->project['version'] : '1.0.0')?><!--"></script>-->
<script src="<?=base_url('assets/'); ?>cr/crud_data.js<?='?ver='.(isset($this->project['version']) ? $this->project['version'] : '1.0.0')?>"></script>
<script src="<?=base_url('assets/'); ?>cr/biometric_face.js<?='?ver='.(isset($this->project['version']) ? $this->project['version'] : '1.0.0')?>"></script>
</body>
</html>