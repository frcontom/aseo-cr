
<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?=$titulo; ?></title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?=base_url('assets/'); ?>images/favicon.png">
    <link href="<?=base_url('assets/'); ?>css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="<?=base_url('assets/'); ?>vendor/sweetalert2/dist/sweetalert2.min.css">
    <link href="<?=base_url('assets/'); ?>plugins/lobibox/css/lobibox.css" rel="stylesheet">
    <script>
        var project_title = "<?=$titulo; ?>";
        var url_sitio = "<?php echo base_url(); ?>";
    </script>
</head>

<body class="h-100">
<div class="authincation h-100">
    <div class="container h-100">
        <div class="row justify-content-center h-100 align-items-center">
            <div class="col-md-6">
                <div class="authincation-content">
                    <div class="row no-gutters">
                        <div class="col-xl-12">
                            <div class="auth-form">
                                <div class="text-center mb-3">
                                    <a href="<?=base_url('/'); ?>"><img src="<?=base_url('assets/'); ?>images/logo-full.png" alt=""></a>
                                </div>
                                <h4 class="text-center mb-4 text-white">Sign in your account</h4>
                                <form data-toggle="validator" role="form" method="post" id="data_login" autocomplete="off">
                                    <div class="form-group">
                                        <label class="mb-1 text-white"><strong>Usuario</strong></label>
                                        <input class="form-control"  type="text"  required=""  placeholder="Usuario" name="username">
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-1 text-white"><strong>Contraseña</strong></label>
                                        <input type="password" class="form-control" required="" placeholder="Contraseña" name="password">
                                    </div>
                                    <div class="form-row d-flex justify-content-end mt-4 mb-2">
                                        <div class="form-group">
                                            <a class="text-white" href="<?=base_url('reset'); ?>">Restablecer Contraseña?</a>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn bg-white text-primary btn-block">Ingresar al sistema</button>
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
<script src="<?=base_url('assets/'); ?>vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
<script src="<?=base_url('assets/'); ?>vendor/sweetalert2/dist/sweetalert2.min.js"></script>
<script src="<?=base_url('assets/'); ?>js/custom.min.js"></script>
<script src="<?=base_url('assets/'); ?>js/dlabnav-init.js"></script>
<script src="<?=base_url('assets/'); ?>js/validator.min.js"></script>
<script src="<?=base_url('assets/'); ?>plugins/lobibox/js/lobibox.js"></script>
<script src="<?=base_url('assets/'); ?>cr/all_scripts.js"></script>
<script src="<?=base_url('assets/'); ?>cr/login.js"></script>

</body>

</html>