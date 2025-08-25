<?php
$money = $this->class_security->array_data($data['event']->b_currency,$this->class_data->money);

//join
//print_r($data);exit;


//echo $result;exit;
//$dateEvents = join('<br>',array_values(array_column($data['days'],'bd_day')));
//$dateEvents =  implode("<br>", array_map(function($item) {
//    return $item->bd_day. "  "  .$item->pf_houri . " - " . $item->pf_hourf;
//}, $data['days']));
//exit;
?>
<!DOCTYPE html>
<html lang="es">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<head>
    <meta charset="utf-8">
    <title>Contrato del Evento</title>
    <!-- Latest compiled and minified CSS -->
    <!--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">-->
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        @page {
            margin: 13mm; /* Ajusta este valor según tu necesidad */
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
            margin:0;
            /*margin-top: 0.1cm;*/
            /*margin-left: 1cm;*/
            /*margin-right: 1cm;*/
            /*margin-bottom: -2cm;*/

            font-family: 'Quicksand', sans-serif;
            font-size:13px;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            /*background:red;*/
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



        .col-xs-1, .col-sm-1, .col-md-1, .col-lg-1, .col-xs-2, .col-sm-2, .col-md-2, .col-lg-2, .col-xs-3, .col-sm-3, .col-md-3, .col-lg-3, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4, .col-xs-5, .col-sm-5, .col-md-5, .col-lg-5, .col-xs-6, .col-sm-6, .col-md-6, .col-lg-6, .col-xs-7, .col-sm-7, .col-md-7, .col-lg-7, .col-xs-8, .col-sm-8, .col-md-8, .col-lg-8, .col-xs-9, .col-sm-9, .col-md-9, .col-lg-9, .col-xs-10, .col-sm-10, .col-md-10, .col-lg-10, .col-xs-11, .col-sm-11, .col-md-11, .col-lg-11, .col-xs-12, .col-sm-12, .col-md-12, .col-lg-12 {
            position: relative;
            min-height: 1px;
        }
        .col-xs-1, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9, .col-xs-10, .col-xs-11, .col-xs-12 {
            float: left;
        }
        .col-xs-12 {
            width: 100%;
        }

        .col-xs-4 {
            width: 33.33333333%;
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
        .container:before,
        .container:after,
        .container-fluid:before,
        .container-fluid:after,
        .row:before,
        .row:after{
            display: table;
            content: " ";
        }
        .clearfix:after,
        .dl-horizontal dd:after,
        .container:after,
        .container-fluid:after,
        .row:after,
        .pager:after {
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



        .my-4 {
            margin-top: 1.5rem !important;
            margin-bottom: 1.5rem !important;
        }



        .mt-3 {
            margin-top: 1rem !important;
        }

        .mt-4 {
            margin-top: 1.5rem !important;
        }


        .me-2 {
            margin-right: 0.5rem !important;
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
            border-collapse:separate  !important;;
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

        .hoja1{
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

        .w-100{
            width: 100%;
        }
        .w-25{
            width: 25%;
        }
        .w-50{
            width: 50%;
        }
        .w-70{
            width: 70%;
        }


        .tableEasy td, th {border:  1px solid;
            padding: 2px 5px;
            background: white;
            box-sizing: border-box;
            text-align: left;
        }

    </style>

</head>
<body>

<main>
    <div class="hoja12">


        <div class="row2 text-center" >
            <div class="col-xs-123">
                <h3>CONTRATO PRIVADO<br>PARA USO DE SALONES DE EVENTOS Y ALIMENTACIÓN</h3>
            </div>
        </div>

        <div class="row" style="">
            <div class="col-xs-12">
                <span style="font-size: 17px">
                    ANEXO UNO
                </span>
            </div>
        </div>

         <div class="row">
            <div class="col-xs-12">
                <p style="font-size: 17px;text-decoration: underline">
                    Información General del Evento.
                </p>
            </div>
        </div>

        <div class="row" style="margin-bottom:20px">
            <div class="col-xs-122">
               <div class="table-responsive2">
                   <table class="tableEasy" style="border: 1px solid black;width:100%;border-collapse: collapse;">
                    <tbody>

                        <tr><td>
                                <div style="padding: 1px 0">
                                    <div style="width:30%;display: inline-block;vertical-align: top;font-size: 14px;font-weight: bold">Nombre de actividad:</div>
                                    <div style="width:69%;display: inline-block;vertical-align: middle;font-size: 18px"> <?=$data['event']->b_event_name?></div>
                                </div>
                            </td></tr>
                        <tr><td>
                                <div style="padding: 1px 0">
                                    <?php
                                        foreach ($data['days'] As $days):
                                            $daysChange = $this->class_security->format_date($days->bd_day,'d-m-Y');
                                            $hourIsChange = $this->class_security->format_date($days->bd_houri,'g:i A');
                                            $hourFsChange = $this->class_security->format_date($days->bd_hourf,'g:i A');
                                            ?>
                                            <div style="width:30%;display: inline-block;vertical-align: top;font-size: 14px;font-weight: bold">Fecha y hora de la actividad:</div>
                                            <div style="width:69%;display: inline-block;vertical-align: top;font-size: 16px"><?=$daysChange. "  "  .$hourIsChange . " - " . $hourFsChange?></div>
                                        <?php
                                        endforeach;
                                    ?>

                                </div>
                        </td></tr>
                        <tr><td>
                                <div style="padding: 1px 0">
                                    <div style="width:30%;display: inline-block;vertical-align: top;font-size: 14px;font-weight: bold">N° de personas:</div>
                                    <div style="width:69%;display: inline-block;vertical-align: top;font-size: 16px"> <?=$data['event']->b_cpax?></div>
                                </div>
                            </td></tr>
                        <tr><td>
                                <div style="padding: 1px 0">
                                    <div style="width:30%;display: inline-block;vertical-align: top;font-size: 14px;font-weight: bold">Mantelería:</div>
                                    <div style="width:69%;display: inline-block;vertical-align: top;font-size: 16px"> <?=$data['event']->e_tableline?></div>
                                </div>
                            </td></tr>
                        <tr><td>
                                <div style="padding: 1px 0">
                                    <div style="width:30%;display: inline-block;vertical-align: top;font-size: 14px;font-weight: bold">Montaje:</div>
                                    <div style="width:69%;display: inline-block;vertical-align: top;font-size: 16px"> <?=$data['event']->e_mounting?></div>
                                </div>
                            </td></tr>
                        <tr>
<!--                            <td>-->
<!--                                <div>-->
<!--                                    <div style="width:27%;display: inline-block;vertical-align: top;font-size: 13px;font-weight: bold">Salón:</div>-->
<!--                                    <div style="width:72%;display: inline-block;vertical-align: top;font-size: 14px"> --><?php //=$data['event']->r_name?><!--</div>-->
<!--                                </div>-->
<!--                        </td>-->
                        </tr>

                    </tbody>
                   </table>
               </div>
            </div>
        </div>
        <?php
        $op = 0;
        $total_final = 0;
        if(isset($data['package']) and count($data['package']) > 0){
       ?>
         <div class="row" style="margin-bottom:20px">
            <div class="col-xs-122">
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

                        foreach($data['package'] As $package):
                            $op += $calcule = ($package->pfp_price*$package->pfp_count);
                            echo "<tr>";
                            echo "<td>".$package->pfp_count."</td>";
                            echo "<td>".$package->p_title."</td>";
                            echo "<td><span class='description'>".$package->pfp_description."</span></td>";
                            echo "<td>".$money['code']."  ".$package->pfp_price."</td>";
                            echo "<td>".$money['code']."  ".number_format($calcule,2)."</td>";
                            echo "</tr>";
                        endforeach;
                    $total_final += $op;
                    ?>

                    <tr>
                        <td colspan="4" class="text-right" style="padding-right:15px"><span class="bold">TOTAL IVA INCLUIDO</span></td>
                        <td style="background: green;color:white"><span class="bold"><?=$money['code']?><?=number_format($op,2)?></span></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

            <?php
        }
        if(isset($data['rooms']) and count($data['rooms']) > 0){
            ?>

            <div class="row" style="margin: -10px 0">
                <div class="col-xs-122">
                    <p>
                        Datos del Hospedaje:
                    </p>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-122">
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
                                echo "<td>".$money['code']."  ".number_format($calculetotal,2)."</td>";
                                echo "</tr>";
                            endforeach;
                        }
                        $total_final += $calculetotalF;
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
                        </tbody>
                    </table>
                </div>
            </div>
            <?php
        }
        ?>

        <div class="break"></div>
        <div class="row">
            <div class="col-xs-122">
                <p style="font-size: 17px;text-decoration: underline">
                    FACTURACIÓN
                </p>
            </div>
        </div>

        <div class="row" style="margin-top: 10px" >
            <div class="col-xs-122">
                <div class="table-responsive">
                    <table class=" w-100 tableEasy" style="border: 1px solid black;border-collapse: collapse;">
                        <tbody>

                        <tr><td><b>Nombre de la Empresa: </b> <?=$data['event']->e_company_name?></td></tr>
<!--                        <tr><td><b>Número de cédula jurídica: </b> --><?php //=$data['event']->e_company_number?><!--</td></tr>-->
                        <tr><td><b>Encargado de firmar el contrato: </b> <?=$data['event']->e_manager_contact?></td></tr>
                        <tr><td><b>Nombre del cliente: </b><?=$data['event']->b_client_name?></td></tr>
                        <tr><td><b>Número de documento: </b> <?=$data['event']->b_client_document?></td></tr>
                        <tr><td><b>Teléfono: </b><?=$data['event']->b_client_phone?></td></tr>
                        <tr><td><b>Correo: </b> <?=$data['event']->b_client_email?></td></tr>
                        <tr><td><b>Dirección: </b> <?=$data['event']->e_address?></td></tr>




                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>



    <div class="break"></div>
    <div id="hoja1">
        <div class="row text-center">
            <div class="col-xs-12">
                <p style="font-size: 19px;text-decoration: underline">
                    CROQUIS
                <div style="display:block;position:relative;margin:0 auto">
                    <img src="<?=(isset($data['event']->e_image) and strlen($data['event']->e_image) > 10) ? base_url("_files/events/{$data['event']->e_image}") : base_url("_files/default.jpg")?>" style="max-width: 80%;">
                </div>
                </p>
            </div>
        </div>


        <div class="row" style="margin-top: 10px" >
            <div class="col-xs-12">
                <div class="table-responsive">
                    <table class=" w-100 tableEasy" style="border: 1px solid black;border-collapse: collapse;">
                        <thead>

                        </thead>
                        <tbody>
                        <?php
                        $i = 1;
                        $color = ['#e9ecef','#FFFFFF'];
                        foreach($data['days'] As $day):
                            $randonColor = ($i%2==0);
                            echo "
                        <tr><td  style='text-align:center !important;' colspan='4'><b>DÍAS EVENTO #{$i}</b></td></tr>
                        <tr>
                            <th>Día</th>
                            <th>Hora Inicial</th>
                            <th>Hora Final</th>
                            <th>Salon</th>
                        </tr>
                                        <tr style='font-size: 16px'>
                                            <td  style='background: {$color[$randonColor]}'>{$day->bd_day}</td>
                                            <td  style='background: {$color[$randonColor]}'>{$day->bd_houri}</td>
                                            <td  style='background: {$color[$randonColor]}'>{$day->bd_hourf}</td>
                                            <td  style='background: {$color[$randonColor]}'>{$day->r_name}</td>
                                            
                                    </tr>
                                    
                                    ";

                        echo "<tr>
                            <td colspan=2  style='padding: 0;margin: 0;vertical-align: top;'>
                                <table class=' w-100 tableEasy' style='border: none;vertical-align: top;border:border-collapse: collapse;'>
                                        <thead>
                                        <tr>
                                            <td  style='text-align:center !important;' colspan='2'>SERVICIO DE EVENTO</td>
                                        </tr>
                                        <tr>
                                            <th>Descripción</th>
                                            <th>Tiempo</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                          ";
                            foreach ($data['activitys'] as $activity):
                                if($day->bd_day == $activity->et_date):
                                    echo "<tr>
                                                <td>{$activity->et_description}</td>
                                                <td>{$activity->et_time_service}</td>
                                        </tr>";
                                endif;
                            endforeach;

                            echo "
                                        </tbody>
                                 </table>
                              </td> 
                            <td colspan=2  style='padding: 0;margin: 0;vertical-align: top;'>
                                <table class=' w-100 tableEasy' style='border: none;vertical-align: top;border:border-collapse: collapse;'>
                                        <thead>
                                        <tr>
                                            <td  style='text-align:center !important;' colspan='2'>CRONOGRAMA DE ALIMENTACIÓN</td>
                                        </tr>
                                        <tr>
                                            <th>Nombre Usuario</th>
                                            <th>Menu</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                          ";
                            foreach ($data['espcials'] as $espcials):
                                  if($day->bd_day == $espcials->es_date):
                                    echo "<tr>
                                                <td>{$espcials->es_user_name}</td>
                                                <td>{$espcials->es_menu}</td>
                                        </tr>";
                                  endif;
                            endforeach;

                            echo "
                                        </tbody>
                                 </table>
                              </td>
                              
                              }
                              </tr>";

                            echo "<tr><td colspan=4 style='text-align: center'><b>Descripción</b></tr>";
                            echo "<tr><td colspan=4 style='background: {$color[$randonColor]}'>{$day->bd_description}</td></tr><tr><td colspan=4  style='border-left:1px white solid;border-right:1px white solid'>&nbsp;</tr>";
                        $i++;
                        endforeach;
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="hoja1">
        <div class="break"></div>
        <div class="row text-center">
            <div class="col-xs-12">
                <p>
                <h2>ANEXO DOS</h2>
                <h3>‘’Cuentas Bancarias del Hotel’’</h3>
                </p>
            </div>
        </div>

        <div class="row text-left" >
            <div class="col-xs-12" style="font-size:14px">
                <p>
                    <b>Banco Nacional de Costa Rica:</b>
                    <br>IBAN Colones  CR41015100010011793793
                    <br>IBAN Dólares  CR23015100010026084157
                    <br>Cuenta en COLONES: #100-01-000-179379-3
                    <br>Cuenta en DÓLARES: #100-02--000-608415-6
                    <br>SWIFT Banco Nacional: BNCRCRSJ
                    <br>Dirección:  Avenida 1 y 3 calle 4 contiguo al correo
                    <br>ABA CODE BANCO NACIONAL
                </p>
            </div>
        </div>

        <div class="row text-left" >
            <div class="col-xs-12" style="font-size:14px">
                <p>
                    <b>Banco de San José (BAC SAN JOSÉ)</b>
                    <br>IBAN Colones                    CR35010200009049404596
                    <br>IBAN Dólares                      CR21010200009049404751
                    <br>Cuenta en COLONES:       #904940459
                    <br>Cuenta en DÓLARES:       #904940475
                </p>
            </div>
        </div>

        <div class="row text-left" >
            <div class="col-xs-12" style="font-size:14px">
                <p>
                    <b>Banco Costa Rica</b>
                    <br>IBAN Colones                   CR33015201225000454111
                    <br>IBAN Dólares                    CR06015201225000454209
                    <br>Cuenta en COLONES:       #225-0004541-1
                    <br>Cuenta en DÓLARES:       #225-0004542-0
                    <br>SWIFT Banco de Costa Rica:          BCRICRSJ
                    <br>Dirección: Avenida central y segunda calles 4 y 6 San José Costa Rica
                </p>
            </div>
        </div>

        <div class="row text-left" >
            <div class="col-xs-12" style="font-size:14px">
                <p>
                    <b>NOTAS IMPORTANTES.</b><br>
                    Los depósitos a nombre de INVERSIONES SABANA AZUL WEF, S.A. y enviar copia del mismo por correo.<br>
                    En caso de pagar en colones, el mismo se estará aplicando según el tipo de cambio de venta del día.
                </p>
            </div>
        </div>
        <div class="break"></div>
    </div>

    <div class="hoja1">
        <div class="row text-center">
            <div class="col-xs-12">
                <p>
                <h3>CONTRATO PRIVADO<br>
                    PARA USO DE SALONES DE EVENTOS Y ALIMENTACIÓN
                </h3>
                </p>
            </div>
        </div>


        <div class="row text-left" style="margin-bottom: 10px">
            <div class="col-xs-12" style="font-size:14px">
                <span>
                    Entre Nosotros, <span class="bold"> HOTEL PALMA REAL HOTEL & CASINO</span> ubicado en Sabana Norte, 200 metros norte del Instituto Costarricense de Electricidad (ICE),
                    San José, Costa Rica, representada en este acto por su Ejecutivo(a) en Ventas, la señoro(a) <?=$data['user']->u_name?> la cédula de identidad número <?=$data['user']->u_document?>  en adelante ‘’<span class="bold">El Hotel</span>’’ y,
                    Sr(a). <?=$data['event']->e_company_name?>, cédula <?=$data['event']->e_company_number?>, representante de Cédula jurídica en adelante ‘’El Cliente’’ MANIFESTAMOS:
                    De conformidad con el mutuo interés de las partes, hemos convenido en suscribir éste CONTRATO PRIVADO PARA USO DE SALONES DE EVENTOS Y ALIMENTACIÓN
                    ‘’El Contrato”, el cual se regirá bajo la normativa costarricense y los siguientes términos y condiciones.
                </span><br>
            </div>
        </div>

        <div class="row text-left" style="margin-bottom: 10px">
            <div class="col-xs-12" style="font-size:14px">
                    <span class="bold">PRIMERA: Del Objeto.</span> El Objeto del presente contrato es la prestación de servicios para el uso de Salones de eventos y alimentación
                    con el objetivo de realiza de reuniones corporativas y sociales en el Hotel, según los requerimientos del Cliente.
            </div>
        </div>

        <div class="row text-left" style="margin-bottom: 10px">
            <div class="col-xs-12" style="font-size:14px">
                    <span class="bold">SEGUNDA: De la Cotización y Precio de los Servicios.</span> El Hotel preparará, a solicitud del Cliente una cotización de los servicios elegidos por él. Dicha cotización se adjunta como
                    anexo uno al presente contrato el cual debidamente firmado por las partes forma parte integral de éste.
                    <br>
                    En la cotización indicada se establecerá toda la información del evento, cronograma, número de personas, horario de alimentación y el montaje escogido por El Cliente junto con
                    su respectivo plano, entre otra información relevante, la cual se encuentra también indicada en el anexo uno.
                <br>
            </div>
        </div>

        <div class="row text-left" style="margin-bottom: 10px">
            <div class="col-xs-12" style="font-size:14px">
                    <span class="bold">TERCERA: De la Forma de Pago de los Servicios.</span> Para realizar la reservación del salón, el Cliente el día de hoy paga por adelantado el <span class="bold">CINCUENTA POR CIENTO
                        (50%)</span> del precio de los servicios, debiendo pagar el saldo restante a más tardar <span class="bold">OCHO DÍAS</span> antes de la realización del evento contratado.
                    <br>
                    El pago deberá realizarse de forma directa en la Tesorería del Hotel cuando sea en efectivo o tarjeta, o podrá realizarse por medio de transferencia bancaria
                    a las cuentas indicadas en el anexo dos el cual forma parte integral del presente contrato.
                    <br>
                    <span class="bold">El VEINTICINCO POR CIENTO (25%) </span> pago de la reservación realizada, no se encuentra sujeto a devolución.
            </div>
        </div>

        <div class="row text-left" style="margin-bottom: 10px">
            <div class="col-xs-12" style="font-size:14px">
                <p>
                    <span class="bold">CUARTA: Cancelaciones del Evento.</span> Si el evento se cancela una vez formalizado el presente contrato, el Cliente podrá reprogramar el evento bajo disponibilidad de
                    El Hotel. En caso de que el Cliente no desee reprogramarlo, indemnizará al Hotel por concepto de daños y perjuicios según la siguiente tabla:
                </p>
            </div>
        </div>

        <div class="row" >
            <div class="col-xs-12" style="font-size:14px">
                <div class="table-responsive">
                    <table class=" w-70 tableEasy" style="border: 1px solid black;border-collapse: collapse;margin:0 auto">
                        <thead  style="text-align:center !important;">
                        <tr>
                            <td><span class="bold">Cancelación</span></td>
                            <td><span class="bold">Porcentaje del monto pagado:</span></td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>15 días antes del evento </td>
                            <td>25%</td>
                        </tr>
                        <tr>
                            <td>8 días antes del evento </td>
                            <td>50% </td>
                        </tr>
                        <tr>
                            <td>Menos de 8 días antes del evento </td>
                            <td>100% </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row text-left" >
            <div class="col-xs-12" style="font-size:14px">
                <p>
                    <span class="bold">QUINTA: Horarios y Modificaciones.</span>
                <div style="margin:0 30px">
                    <span class="me-2">i.</span>	Los horarios aquí convenidos deben cumplirse por las partes. En caso de extensión del evento, El Cliente deberá consultar previamente a la persona encargada de éste, si existe dicha posibilidad, pagando el cargo extra. En caso de no ser posible la extensión, el Cliente y sus invitados deberán abandonar el salón según el horario previamente establecido.<br>
                    <span class="me-2">ii.</span>	El uso del salón no podrá ser distinto al contratado.<br>
                    <span class="me-2">iii.</span>	Se aceptarán modificaciones relacionadas al menú, montajes, equipos y en otros servicios hasta tres días antes de la realización del evento. Una vez iniciado el evento no se aceptarán modificaciones. No obstante, en caso de solicitarse algún cambio, el Hotel a entera discreción valorará la opción de realizarlos. En ese caso el Cliente deberá cancelar los costos asociados.
                </div>
                </p>
            </div>
        </div>

        <div class="row text-left" >
            <div class="col-xs-12" style="font-size:14px">
                <p>
                    <span class="bold">  SEXTA: Cargos adicionales.</span>
                <div style="margin:0 30px">
                    <span class="me-2">i.</span>	Cualquier CONSUMO EXTRA no contemplado en el presupuesto del contrato original que se solicite durante el evento, deberá ser cancelado por el Cliente antes de su preparación, en efectivo o con garantía real de pago (voucher abierto de tarjeta de crédito pre-autorizado) o cualquier otro medio de garantía a discreción del Hotel.
                    <br><span class="me-2">ii.</span>	El cargo por hora extra del uso del salón en caso de aprobación, será del 5% del costo total.
                    <br><span class="me-2">iii.</span>	En caso de que el Cliente se exceda en el número de personas del paquete contratado para el evento, deberá pagar US$1.00 por persona que ingrese al salón.
                    <br><span class="me-2">iv.</span>	En caso de que al finalizar el evento exista mantelería deteriorada el Cliente deberá pagar US$10.00 por pieza.
                    <br><span class="me-2">v.</span>	Cualquier otro daño causado a los enseres del salón donde se realice el evento como por ejemplo: cortinas, sillas, alfombras, mesas, etc deberán ser asumidos por el Cliente al costo de su reposición conforme a la lista del Hotel y cancelados según el medio de garantía acordado.
                </div>
                </p>
            </div>
        </div>

        <div class="row text-left" >
            <div class="col-xs-12" style="font-size:14px">
                <p>
                    <span class="bold"> SÉTIMA: Condiciones establecidas por El Hotel obligatorias para El Cliente.</span> Para la realización de eventos El Cliente deberá respetar las siguientes condiciones:
                <div style="margin:0 30px">
                    <div class="mt-3">
                        <span class="bold">a)	De las instalaciones:</span>
                        <div style="margin:0 30px">
                            <span class="me-2">i.</span>	El Cliente o representantes autorizados no podrán realizar ninguna modificación en las instalaciones ni en sus bienes muebles que dañe su estructura original o cambios en la estructura original del salón.
                            <br><span class="me-2">ii.</span>	No está permitido el uso de humo, hielo seco, máquinas de espuma, fuego y globos con hidrogeno dentro de las instalaciones.
                            <br><span class="me-2">iii.</span>	El uso de equipos o instalaciones de agua, y/o fuego, los montajes eléctricos, etc deberán ser supervisados y autorizados OBLIGATORIAMENTE POR EL DEPARTAMENTO DE MANTENIMIENTO DEL HOTEL, por escrito, previa inspección de los equipos o instalaciones y del lugar donde van a ser ubicados o instalados, a fin de evitar sobrecargas en líneas eléctricas.
                            <br><span class="me-2">iv.</span>	El Cliente autoriza previamente al Hotel a cobrar cualquier daño generado.
                        </div>
                    </div>

                    <div class="mt-3">
                        <span class="bold"> b)	Sobre los alimentos.</span>
                        <div style="margin:0 30px">
                            <span class="me-2">i.</span>	Es prohibido el ingreso de alimentos y bebidas por parte de El Cliente, salvo pacto en contrario.
                            <br><span class="me-2">ii.</span>	No se permite la distribución o venta de comida o bebida por parte de terceros dentro del Hotel, con excepción de que el evento sea contratado con ese fin.
                            <br><span class="me-2">iii.</span>	Para mantener la calidad de los alimentos, las estaciones de buffet se mantendrán abiertas por dos horas para servicio de los invitados, según el horario acordado para el evento.
                            <br><span class="me-2">iv.</span>	El Cliente y sus invitados tendrán derecho a consumir a la carta, previo pago en efectivo o garantía real de pago.
                            <br><span class="me-2">v.</span>	El licor que se reciba para descorche deberá estar identificado con las etiquetas de control interno del Hotel, por cual deberá ser entregado a éste con al menos 24 horas de antelación a la realización del evento.
                            <br><span class="me-2">vi.</span>	EXCLUSIÓN DE RESPONSABILIDAD Y RENUNCIA A ACCIÓN POR RECLAMOS E INDEMNIZACIÓN El cliente declara voluntariamente haber sido debidamente informado por el Hotel respecto a la calidad e inocuidad de la alimentación contratada, la cual se ofrece y garantiza durante la realización de dicho evento, por lo cual en este acto, excluye El Cliente de cualquier responsabilidad al Hotel, su propietario, su operador, así como a sus representantes legales, apoderados, socios, dueños, colaboradores, instructores, empleados, socios comerciales, y/o patrocinadores, en adelante los “Liberados”, por la calidad y manipulación de los alimenticios y por cualquier daño que pueda provocar el consumo de dichos alimentos una vez que el Cliente los retire del Hotel al finalizar el evento. De esta manera, el Cliente sustituye y asume exclusiva responsabilidad personal sobre el uso y consumo de dichos alimentos, una vez retirados del Hotel. De la misma manera, el Cliente asume completa responsabilidad por los riesgos expuestos físicos y psicológicos con ocasión del retiro de dicha alimentos, una vez que salgan de las instalaciones del Hotel, excluyendo a los Liberados de toda responsabilidad civil, penal, contractual y extra contractual a los Liberados, y se obliga a mantenerlos incólumes por dicha exposición ante terceros, y voluntariamente renuncia a cualquier acción en cualquier sede y de cualquier naturaleza en contra de los Liberados.
                        </div>

                    </div>

                    <div class="my-4">
                        <span class="bold">c)	Servicio de Oficiales de Seguridad.</span>
                        <div style="margin:0 30px">
                            <span class="me-2">a.</span>	Si El Cliente lo desea podrá traer su propia seguridad.
                            <br><span class="me-2">b.</span>	El Hotel ofrece el servicio de seguridad privada por un costo de US$87.00 por oficial con un máximo 8 horas. Por cada hora extra del servicio el costo será de US$13.00 dólares por hora moneda de curso legal de los Estados Unidos de América.
                        </div>
                    </div>
                </div>
                </p>
            </div>
        </div>


        <div class="row text-left" >
            <div class="col-xs-12" style="font-size:14px">
                <p>
                    <span class="bold">OCTAVA: Obligaciones y Responsabilidades del Cliente.</span>
                <div style="margin:0 30px">
                    <span class="me-2">i.</span>	Realizar la cancelación del precio total del evento en las fechas convenidas.
                    <br><span class="me-2">ii.</span>	Dejar libre el acceso a todas las salidas de emergencia de los salones, antes, durante y después de finalizado el evento.
                    <br><span class="me-2">iii.</span>	Asumir la responsabilidad por los daños causados al salón, equipo e instalaciones del Hotel utilizadas durante el montaje, ejecución y desmontaje de su evento.
                    <br><span class="me-2">iv.</span>	Retirar inmediatamente todo material decorativo, licor descorchado y cualquier otro equipo o bienes que hayan sido autorizados por el Hotel para ser utilizados en el evento y que sean de su propiedad. Transcurridas dos horas después de la finalización del evento, si el Cliente no los ha retirado el Hotel no se hace responsable por ellos.
                    <br><span class="me-2">v.</span>	El Cliente debe avisar a la recepción del Hotel cuando se encuentren en ‘’descanso o break’’ para que se proceda con el cierre del salón, y éste se volverá a abrir cuando el responsable del evento lo solicite.

                </div>
                </p>
            </div>
        </div>


        <div class="row text-left" >
            <div class="col-xs-12" style="font-size:14px">
                <p>
                    <span class="bold">NOVENA: Obligaciones del Hotel.</span>
                <div style="margin:0 30px">
                    <span class="me-2">i.</span>	Realizar el evento conforme a lo acordado con El Cliente y lo plasmado en el anexo número uno del presente contrato.
                    <br><span class="me-2">ii.</span>	Velar por el cumplimiento de la normativa que regula el Turismo Sostenible en Costa Rica.

                </div>
                </p>
            </div>
        </div>


        <div class="row text-left" >
            <div class="col-xs-12" style="font-size:14px">
                <p>
                    <span class="bold">DÉCIMA:  Condiciones Generales.</span>
                <div style="margin:0 30px">

                    <span class="me-2">i.</span>	El Hotel no se hace responsable por los objetos de valor en los salones de eventos o áreas públicas del Hotel.
                    <br><span class="me-2">ii.</span> El Hotel se reserva el derecho de admisión, por lo que, no se permite el ingreso de personas que porten armas, bajo efectos de cualquier droga, sustancias prohibidas o efectos del licor y bebidas enervantes.
                    <br><span class="me-2">iii.</span>	El Hotel no se responsabiliza por los daños causados a los vehículos de los invitados del Cliente o propiedad de éste o por la sustracción de bienes dejados en el mismo.

                </div>
                </p>
            </div>
        </div>


        <div class="row text-left" >
            <div class="col-xs-12" style="font-size:14px">
                <p>
                    <span class="bold">DÉCIMA PRIMERA: Notificaciones Y Comunicaciones.</span> Cualquier comunicación relativa al presente Contrato deberá hacerse por escrito, debidamente firmada en original, por la persona o entidad de quien emane y dirigirse como sigue:
                <div style="margin:0 30px">
                    <span class="me-2">.</span>	A Hotel: con atención a (ejecutivo(a) de ventas   Sr(a). <?=$data['user']->u_name?> ) en las instalaciones ubicadas en San José, en la dirección ya indicada, con copia al correo electrónico <?=$data['user']->u_email?>
                    <br><span class="me-2">.</span>	Al Cliente: con atención a Sr.       en       , y con copia al correo electrónico

                </div>
                </p>
            </div>
        </div>

        <?php
            $operation_total = $total_final;
            $moneyCurrenty = ($money['title'] == 'CRC') ? 'Colones' : 'Dolares';
//            print_r($money);
        ?>


        <div class="row text-left" >
            <div class="col-xs-12" style="font-size:14px">
                <p>
                    <span class="bold"> DÉCIMA SEGUNDA: ESTIMACIÓN.</span>  Para efectos fiscales se estima el Contrato en la suma     (<?=$money['title']?> <?=$money['code']?> <?=$this->class_security->numberformat($operation_total)?> , <?=$this->class_security->formatNumberMoney($operation_total,$moneyCurrenty)?>).
                </p>
            </div>
        </div>

        <div class="row text-left"  style="margin-bottom:70px">
            <div class="col-xs-12" style="font-size:14px">
                <p>
                    Estando plenamente conformes con todas y cada una de las cláusulas, firmamos en San José, Costa Rica, <?=$this->class_security->datehuman(fecha(1),1)?>.
                </p>
            </div>
        </div>

        <div class="row mt-3 text-center" style="margin-left:100px">
            <div class="col-xs-4" style="border-top: 1px solid black;text-align: center">
                <p>
                    <?=$data['user']->u_name?>
                    <br>
                    Ejecutivo(a) de Ventas
                    <br>
                    Hotel Palma Real.
                </p>
            </div>

            <div class="col-xs-2"> </div>

            <div class="col-xs-4" style="border-top: 1px solid black;text-align: center">
                <p>
                    <?=$data['event']->b_client_name?><br>
                    <?=$data['event']->e_company_name?>
                </p>
            </div>
        </div>

    </div>


</main>





</body>
</html>