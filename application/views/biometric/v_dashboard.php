
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

    <link rel="manifest" href="<?=base_url('assets/'); ?>wpa/manifest.json">


<body class="body  h-100">
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
<!--                                <div style="position: relative; display: none;"  id="camera_video">-->
                                <div style="position: relative; display: inline-block;"  id="camera_video">
                                    <div class="overlay">
                                        <div class="overlay-element top-left"></div>
                                        <div class="overlay-element top-right"></div>
                                        <div class="overlay-element bottom-left"></div>
                                        <div class="overlay-element bottom-right"></div>
                                    </div>
                                    <video id="video" width="450" height="450" autoplay style="border-radius: 55px;"></video>
<!--                                    <div id="countdown">3</div>-->
                                </div>

<!--                                <img id="photo"></img>-->
<!--                                <div id="photoContainer"></div>-->
                                <img src="<?=base_url('assets/'); ?>images/log.png" style="display: none" class="img-fix bitcoin-img sd-shape7" id="camera_picture">
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6">
                            <div class="sign-in-your py-4 px-2 text-center mt-5">
<!--                                <h4 class="fs-20">Sign in your account</h4>-->
                                   <h2 class="num-text text-black font-w600">Biometric</h2>
                                   <span class="text-black font-w600 text-center">Registra tu presencia de manera rápida, segura y sin complicaciones</span>
                                   <div class="login-social ">
                                       <a href="javascript:void(0);" class="btn d-block btn-primary  my-3" style="font-size: 25px;"><i class="fa fa-clock me-2"></i> <span id="clock"></span></a>
                                   </div>
                               <div class="row mt-2">
                                   <form data-toggle="validator" role="form" method="post" id="form_biometric"  autocomplete="off">
                                       <div class="mt-3 mb-3 ">
                                           <label class="mb-1 h3"><strong>Código de Empleado</strong></label>
                                           <div class="input-group mb-3 input-primary">
                                               <input type="hidden" name="picture" id="photo_input">
                                               <input type="number" name="code" id="numberInput" placeholder="Código"  autocomplete="off"  autofocus required minlength="4" maxlength="4"  pattern="\d{4}" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" class="form-control text-center"  style="padding: 50px 10px;font-size: 80px;font-weight: 900;border-radius: 30px;color: #2953E8">
                                           </div>
                                       </div>
                                       <div class="text-center">
                                           <button type="submit" class="btn btn-primary btn-lg btn-block" style="font-size: 42px !important;">Marcar tu Hora</button>
                                       </div>
                                   </form>
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
<script src="<?=base_url('assets/'); ?>cr/biometric.js"></script>
<script src="<?=base_url(); ?>script.js"></script>
</body>
</html>