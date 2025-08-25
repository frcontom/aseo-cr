<?php
//    print_r($data);exit;
$dayName = '';
$dayDate = '';
$dayHouri = '';
$dayHourf = '';
$sum_total_final= 0;
//print_r($data['days']);exit;
if(isset($data['days']) and count($data['days']) > 0){
    foreach($data['days'] As $day){
        $dayName .= $day->r_name."<br>";
        $dayDate .= $day->bd_day."<br>";
        $dayHouri .= $day->bd_houri."<br>";
        $dayHourf .= $day->bd_hourf."<br>";
    }
}

$fecha = explode('-',fecha(1));

//money proforma
$money = $this->class_security->array_data($data['proforma']->b_currency,$this->class_data->money);
//print_r($money['code']);exit;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Proforma</title>
    <!-- Latest compiled and minified CSS -->
    <!--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">-->
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        @page {
            margin: 0px 10px 120px 0px;
        }

        header {
            position: absolute;
            top: 50px;
            left: 0px;
            right: 60px;
            height: 50px;
            font-size: 20px !important;
            /*background-color: #000;*/
            /*color: white;*/
            text-align: center;
            line-height: 35px;
        }

        body {
            margin-top: 1.4cm;
            margin-left: 1cm;
            margin-right: 1cm;
            /*margin-bottom: -2cm;*/

            font-family: 'Quicksand', sans-serif;
            font-size:13px;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            background: url("<?=base_url('assets/images/background/fondo.png')?>") no-repeat center center fixed;
        }

        hr {
            border: 0;
            height: 2px;
            background: #333;
            background-image: linear-gradient(to right, #ccc, #333, #ccc);
        }

        footer {
            line-height: 19px;
            position: fixed;
            bottom: -2.8cm;
            left: 0cm;
            right: 0cm;
            text-align:center;
            font-size: 12px;
            /*height: 50px;*/

        }


        .break {
            page-break-after: always;
            border: 0;
        }

        @media (min-width: 768px) {
            .container {
                width: 750px;
            }
        }
        @media (min-width: 992px) {
            .container {
                width: 970px;
            }
        }
        @media (min-width: 1200px) {
            .container {
                width: 1170px;
            }
        }


        .row {
            margin-right: -15px;
            margin-left: -15px;
        }
        .col-xs-1, .col-sm-1, .col-md-1, .col-lg-1, .col-xs-2, .col-sm-2, .col-md-2, .col-lg-2, .col-xs-3, .col-sm-3, .col-md-3, .col-lg-3, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4, .col-xs-5, .col-sm-5, .col-md-5, .col-lg-5, .col-xs-6, .col-sm-6, .col-md-6, .col-lg-6, .col-xs-7, .col-sm-7, .col-md-7, .col-lg-7, .col-xs-8, .col-sm-8, .col-md-8, .col-lg-8, .col-xs-9, .col-sm-9, .col-md-9, .col-lg-9, .col-xs-10, .col-sm-10, .col-md-10, .col-lg-10, .col-xs-11, .col-sm-11, .col-md-11, .col-lg-11, .col-xs-12, .col-sm-12, .col-md-12, .col-lg-12 {
            position: relative;
            min-height: 1px;
            padding-right: 15px;
            padding-left: 15px;
        }
        .col-xs-1, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9, .col-xs-10, .col-xs-11, .col-xs-12 {
            float: left;
        }
        .col-xs-12 {
            width: 100%;
        }
        .col-xs-11 {
            width: 91.66666667%;
        }
        .col-xs-10 {
            width: 83.33333333%;
        }
        .col-xs-9 {
            width: 75%;
        }
        .col-xs-8 {
            width: 66.66666667%;
        }
        .col-xs-7 {
            width: 58.33333333%;
        }
        .col-xs-6 {
            width: 50%;
        }
        .col-xs-5 {
            width: 41.66666667%;
        }
        .col-xs-4 {
            width: 33.33333333%;
        }
        .col-xs-3 {
            width: 25%;
        }
        .col-xs-2 {
            width: 16.66666667%;
        }
        .col-xs-1 {
            width: 8.33333333%;
        }
        .text-left {
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .text-justify {
            text-align: justify;
        }

        hr {
            height: 0;
            -webkit-box-sizing: content-box;
            -moz-box-sizing: content-box;
            box-sizing: content-box;
        }
        .clearfix:before,
        .clearfix:after,
        .dl-horizontal dd:before,
        .dl-horizontal dd:after,
        .container:before,
        .container:after,
        .container-fluid:before,
        .container-fluid:after,
        .row:before,
        .row:after,
        .form-horizontal .form-group:before,
        .form-horizontal .form-group:after,
        .btn-toolbar:before,
        .btn-toolbar:after,
        .btn-group-vertical > .btn-group:before,
        .btn-group-vertical > .btn-group:after,
        .nav:before,
        .nav:after,
        .navbar:before,
        .navbar:after,
        .navbar-header:before,
        .navbar-header:after,
        .navbar-collapse:before,
        .navbar-collapse:after,
        .pager:before,
        .pager:after,
        .panel-body:before,
        .panel-body:after,
        .modal-header:before,
        .modal-header:after,
        .modal-footer:before,
        .modal-footer:after {
            display: table;
            content: " ";
        }
        .clearfix:after,
        .dl-horizontal dd:after,
        .container:after,
        .container-fluid:after,
        .row:after,
        .form-horizontal .form-group:after,
        .btn-toolbar:after,
        .btn-group-vertical > .btn-group:after,
        .nav:after,
        .navbar:after,
        .navbar-header:after,
        .navbar-collapse:after,
        .pager:after,
        .panel-body:after,
        .modal-header:after,
        .modal-footer:after {
            clear: both;
        }

        table {
            border-spacing: 0;
            border-collapse: collapse;
        }
        td,
        th {
            padding: 0;
        }


        header .row{
            font-size: 12px;
            font-weight: bold;
        }


        #user {
            /*border:1px solid #454545;*/

            /*border:none !important;*/
            table-layout: fixed;
            width: 100%;

            border-collapse:separate  !important;;
            /*border-spacing:0 10px !important;*/

            text-align: center;
        }


        #user  thead{
            background: #454545;
        }

        #user  thead tr td{
            color:white;
            font-size: 13px;
            border:0.5px solid #b3b1b1;
            padding: 2px 0px;
            font-weight: bold;
        }


        #user  tr td{
            font-size: 11px;
            color: black;
            /*margin: 20px !important;*/
        }

        #user  tbody tr td{
            border:0.5px solid #b3b1b1;
            padding: 5px 0;
        }

        /*package Table*/
        #package {
            border:1px solid #454545;
            /*table-layout: fixed;*/
            width: 100%;
            border-collapse:separate  !important;
            text-align: center;
        }

        #package  thead{
            background: green;

        }

        #package  thead tr td{
            color:white;
            font-size: 13px;
            font-weight:bold;
            border:0.5px solid #b3b1b1;
            padding: 2px 0;
            /*line-height: 1.5;*/
        }

        #package  tbody tr td{
            border:0.10px solid #b3b1b1;
            padding: 5px 0;
        }

        #package p{
            /*line-height: 0.3;*/
        }

        .page-wrap{
            margin-top: 200px;
        }

        .bold{
            font-weight: bold;
        }

        #hoja1{
            line-height: 1.5;
        }

        #hoja2{
            line-height: 1.2;
        }

        mb-0{
            margin-bottom: 0px;
        }

        .description{
            /*color:red !important;*/
            /*line-height: 1.2 !important;*/
        }

        .description p{
            /*color:red !important;*/
            /*line-height: 0.5 !important;*/
        }
        #logo{
            width: 140px;
        }

    </style>

</head>
<body>
<!--view declaracion inicial-->
<header>
    <div class="row">
        <div class="col-xs-12" style="text-align: right;font-weight: 900">
            <img id="logo" src="<?=base_url('assets/images/logoPdf.png'); ?>" />
        </div>
    </div>
</header>

<footer>
    <div class="row">
        <div class="col-xs-12">
            <p>Tel: 2290-5060 ext. 530 Fax: 2290-4160 Email: ventas@hotelpalmareal.com<br>
                Sabana Norte, Calle 50, Avenida 9. Del ICE 200 más norte, San José – Costa Rica<br>
                www.hotelpalmareal.com
            </p>
        </div>
    </div>
</footer>

<main>

    <div id="hoja1">
        <div class="row text-left" >
            <div class="col-xs-12">
                <p>San José, <?=$fecha[2]?>, <?=$fecha[1]?> del <?=$fecha[0]?><br>
                    <b class="bold"> Validez de la oferta: <?=$data['proforma']->b_offer_validity?> Días</b></p>
            </div>
        </div>

        <div class="row" style="margin: -20px -17px">
            <div class="col-xs-12">
                <p>
                    Señor(a). <?=$data['proforma']->b_client_name?><br>
                    Documento. <?=$data['proforma']->b_client_document?><br>
                    Télefono. <?=$data['proforma']->b_client_phone?><br>
                    Correo Electronico. <?=$data['proforma']->b_client_email?>
                </p>
            </div>
        </div>

        <div class="row" >
            <div class="col-xs-12">
                <p>
                    Reciban nuestro cordial saludo de <b class="bold">Palma Real Hotel San José, Urban Style</b>.<br>
                    De acuerdo a su solicitud, le detallo nuestra propuesta especial, para la realización de su evento.
                </p>
            </div>
        </div>

        <div class="row" style="margin:-10px;">
            <div class="col-xs-12">
                <p>
                    <!--                Salón: <b style="font-size:18px">--><?php //=$data['proforma']->r_name?><!--</b>-->
                </p>
            </div>
        </div>
        <?php
        if($data['proforma']->b_type_sl != 2):
        ?>
        <div class="row">
            <div class="col-xs-12">
                <table id="user">
                    <thead>
                    <tr>
                        <td>Evento</td>
                        <td>Salon</td>
                        <td>Fecha de evento</td>
                        <td>Hora Inicial</td>
                        <td>Hora Final</td>
                        <td>C. Pax</td>
                        <td>Tipo Montaje</td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><?=$data['proforma']->b_event_name?></td>
                        <td><?=$dayName?></td>
                        <td><?=$dayDate?></td>
                        <td><?=$dayHouri?></td>
                        <td><?=$dayHourf?></td>

                        <td><?=$data['proforma']->b_cpax?></td>
                        <td><?=$data['proforma']->b_mounting_type?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    <?php
    endif;
    ?>

        <!--    <div class="break"></div>-->
        <?php
        if(isset($data['package']) and count($data['package']) > 0){
            ?>
            <div class="row" style="margin: -10px 0">
                <div class="col-xs-12">
                    <p>
                        Datos del evento:
                    </p>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <table id="package">
                        <thead>
                        <tr>
                            <td style="width:90px">C. Pax</td>
                            <td style="width:170px">Paquete</td>
                            <td>Detalle Paquete</td>
                            <td  style="width:100px">Precio X U.</td>
                            <td  style="width:110px">Total</td>
                        </tr>
                        </thead>
                        <tbody style="background: rgba(255,253,253,0.85)">

                        <?php
                        
                        $op = 0;
                        foreach($data['package'] As $package):
                            $op += $calcule = ($package->pfp_price*$package->pfp_count);
                            echo "<tr>";
                            echo "<td>".$package->pfp_count."</td>";
                            echo "<td>".$package->p_title."</td>";
                            echo "<td><span class='description'>".$package->p_description."</span></td>";
                            echo "<td>". $money['code'] ." ".$package->pfp_price."</td>";
                            echo "<td>".$money['code']." ".number_format($calcule,2)."</td>";
                            echo "</tr>";
                        endforeach;
                        $sum_total_final += $op;
                        ?>

                        <tr>
                            <td colspan="4" class="text-right" style="padding-right:15px"><span class="bold">TOTAL IVA INCLUIDO</span></td>
                            <td style="background: green;color:white"><span class="bold"><?=$money['code']?><?=number_format($op,2)?></span></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="break"></div>
            <?php
        }
        if(isset($data['rooms']) and count($data['rooms']) > 0){
       ?>


            <div class="row" style="margin: -10px 0">
                <div class="col-xs-12">
                    <p>
                        Datos del Hospedaje:
                    </p>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <table id="package">
                        <thead>
                        <tr>
                            <td style="width:90px">C. Hab</td>
                            <td style="width:90px">C. Noches</td>
                            <td style="width:170px">Habitación</td>
                            <td  style="width:100px">Precio</td>
                            <td  style="width:100px">Iva</td>
                            <td  style="width:110px">Total</td>
                        </tr>
                        </thead>
                        <tbody style="background: rgba(255,253,253,0.85)">

                        <?php
                        $calculeSubF = 0;
                        $calculeIvaF = 0;
                        $calculetotalF = 0;
                        $subtotal = 0;
                        $iva = 0;
                        $op = 0;


                        if(isset($data['rooms'])){
                            foreach($data['rooms'] As $door):
                                $price = (isset($door->pfr_price) and $door->pfr_price != '') ? $door->pfr_price : 0;
                                $pax = (isset($door->pfr_pax) and $door->pfr_pax != '') ? $door->pfr_pax : 0;
                                $rcount = (isset($door->pfr_count) and $door->pfr_count != '') ? $door->pfr_count : 0;

                                $calculeSubF   += $calculetotal = ($price*$pax)*$door->pfr_count; //perfect
                                $calculeIvaF   += $calculeIva = (($price*$door->pfr_iva) / 100); //correcto
                                $calculetotalF +=$calulo_total_iva = $calculetotal + (($calculetotal*$door->pfr_iva) / 100);


                                $op += $calcule = ($door->pfr_count *  $door->pfr_price+(($door->pfr_price*$door->pfr_iva) / 100) ) * $pax;
                                $iva +=  (($door->pfr_price*$door->pfr_iva) / 100);
                                echo "<tr>";
                                echo "<td>".$door->pfr_pax."</td>";
                                echo "<td>".$door->pfr_count."</td>";
                                echo "<td>".$door->r_name."</td>";
                                echo "<td>".$money['code']."  ".$door->pfr_price."</td>";
                                echo "<td>".$door->pfr_iva."%</td>";
                                echo "<td>".$money['code']."  ".number_format($calulo_total_iva,2)."</td>";
                                echo "</tr>";
                            endforeach;
                        }
                        
                        $sum_total_final += $calculetotalF;
                        ?>


                        <tr>
                            <td colspan="5" class="text-right" style="padding-right:15px"><span class="bold">SUBTOTAL</span></td>
                            <td style="background: green;color:white"><span class="bold"><?=$money['code']?> <?=number_format($calculeSubF,2)?></span></td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-right" style="padding-right:15px"><span class="bold">IVA</span></td>
                            <td style="background: green;color:white"><span class="bold"><?=$money['code']?> <?=number_format($calculeIvaF,2)?></span></td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-right" style="padding-right:15px"><span class="bold">TOTAL IVA INCLUIDO</span></td>
                            <td style="background: green;color:white"><span class="bold"><?=$money['code']?> <?=number_format($calculetotalF,2)?></span></td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-right" style="padding-right:15px"><span class="bold">TOTAL PROFORMA</span></td>
                            <td style="background: green;color:white"><span class="bold"><?=$money['code']?> <?=number_format($sum_total_final,2)?></span></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php
        }
        ?>

    </div>


    <div class="row" style="margin: 10px 0;border: 0.2px solid black">
        <div class="col-xs-12">
            <p>
                <b>  Observaciones:</b> <span class="description"><?=$data['proforma']->b_observation?></span>
            </p>
        </div>
    </div>



    <div id="hoja2">
        <div class="break"></div>

        <div class="row text-left" style="margin-bottom: -20px">
            <div class="col-xs-12">
                <p>Adicionales</p>
            </div>
        </div>


        <div class="row"  style="margin-bottom: -10px">
            <div class="col-xs-2">
                <span>Salonero extra </span>
            </div>

            <div class="col-xs-8">
                <span class="bold">₡6000  por hora</span>
            </div>
        </div>

        <div class="row"  style="margin-bottom: -25px">
            <div class="col-xs-12">
                <p>
                    Incluye:
                </p>
            </div>
        </div>


        <div class="row">
            <div class="col-xs-12">
                <p class="text-justify">
                    Mantelería (mantel y cubre mantel), sillas, cristalería, cubertería, servicio de salones y montaje (mesas y sillas). Equipo y menaje completo de los alimentos. Servicio de saloneros y Alimentación.
                    Disponibilidad de un salón con privacidad y servicio de salonero.

                </p>
                <p class="text-justify">
                    Solicitudes especiales de clientes<br>
                    Nuestro chef estará en la mejor disposición de atender toda solicitud que se nos indique con respecto Alimentos y Bebidas.

                </p>
                <p class="text-justify">
                    Pago del servicio<br>
                    La confirmación se debe realizar con la firma del contrato y con un depósito del 50% y la cancelación del saldo restante 15 días antes del evento.

                </p>
                <p style="line-height:1">Pago a nombre de: <span class="bold">Inversiones Sabana Azul WEF S.A</span><br><span class="bold">Cédula Jurídica: 3-101-377023</span>


                </p>
            </div>
        </div>

        <div class="row"   style="margin: -15px 0">
            <div class="col-xs-12">
                <p class="text-justify bold">
                    <img src="<?=base_url('assets/images/bank.png'); ?>" />
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <p class="text-justify bold">
                    Es importante aclarar que este documento es una cotización y que Hotel Palma Real & Casino no se ha comprometido al bloqueo de salones o a la realización de ninguna actividad, hasta el pago de las mismas por medio del contrato respectivo.
                </p>
            </div>
        </div>

        <div class="row" style="margin: -15px 0">
            <div class="col-xs-12">
                <p class="text-justify ">
                    Las tarifas indicadas son válidas únicamente para este evento,
                </p>
            </div>
        </div>


    </div>

</main>
<!---->
<!--<div class="container">-->
<!---->
<!--    <main>-->
<!--        <div class="row">-->
<!---->
<!--            <div class="header2">-->
<!--                <p><strong>ID DE DECLARACIÓN:</strong> --><?php //=$declaracion['dc_codigo']?><!--</p>-->
<!--                <p><strong>RFC:</strong>  --><?php //=$declaracion['dg_rcf']?><!--</p>-->
<!--                <p><strong>FECHA DE RECEPCIÓN:</strong>  --><?php //=$this->class_security->format_date($declaracion['dc_atcreate'],'Y-m-d')?><!--</p>-->
<!--            </div>-->
<!---->
<!--            <div class="title2">-->
<!--                <span>PRESENTE.</span>-->
<!--            </div>-->
<!--            <div class="message">-->
<!--                <p>BAJO PROTESTA DE DECIR VERDAD, PRESENTO A USTED MI DECLARACIÓN PATRIMONIAL Y DE INTERESES,-->
<!--                    CONFORME A LO DISPUESTO EN LOS ARTÍCULOS, 108 DE LA CONSTITUCIÓN POLÍTICA DE LOS ESTADOS-->
<!--                    UNIDOS MEXICANOS, 32 Y 33 FRACCIÓN I, DE LA LEY GENERAL DE RESPONSABILIDADES ADMINISTRATIVAS.</p>-->
<!--            </div>-->
<!--        </div>-->
<!--    </main>-->
<!---->
<!--    --><?php //=$this->declapdf->view_footer('','')?>
<!---->
<!---->
<!--</div>-->
<!--<div class="break"></div>-->
<!--view declaracion inicial-->

</body>
</html>