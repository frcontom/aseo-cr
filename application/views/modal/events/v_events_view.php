<?php
defined('BASEPATH') or exit('No direct script access allowed');


$company_name               = $this->class_security->validate_var($datas,'e_company_name');
$company_number               = $this->class_security->validate_var($datas,'e_company_number');
$manager_contact               = $this->class_security->validate_var($datas,'e_manager_contact');
$manager_key               = $this->class_security->validate_var($datas,'e_manager_key');
$type_person               = $this->class_security->validate_var($datas,'e_type_person');
$phone               = $this->class_security->validate_var($datas,'e_phone');
$address               = $this->class_security->validate_var($datas,'e_address');
$email               = $this->class_security->validate_var($datas,'e_email');
$table               = $this->class_security->validate_var($datas,'e_table');
$tableline               = $this->class_security->validate_var($datas,'e_tableline');
$mounting               = $this->class_security->validate_var($datas,'e_mounting');
$imageq               = $this->class_security->validate_var($datas,'e_image');
$observation               = $this->class_security->validate_var($datas_proforma,'b_observation');
$days                = (isset($datas_days) and count($datas_days) > 0) ? count($datas_days) : 0;

$image = ($imageq != '') ? $imageq : '../default.jpg';

 $b_booking_type       = $this->class_security->validate_var($datas_proforma,'b_booking_type');
 $b_type               = $this->class_security->validate_var($datas_proforma,'b_type');




//booking amount
$cpax        = $this->class_security->validate_var($datas_proforma,'b_cpax');
$total       = $this->class_security->validate_var($datas_proforma,'b_total',0);

$a_cpax        = $this->class_security->validate_var($datas_proforma,'b_cpax_a',0);
$a_amount      = $this->class_security->validate_var($datas_proforma,'b_amount_a',0);

$n_cpax        = $this->class_security->validate_var($datas_proforma,'b_cpax_n',0);
$n_amount      = $this->class_security->validate_var($datas_proforma,'b_amount_n',0);

//agence
$agence_guia        = $this->class_security->validate_var($datas_proforma,'b_agence_guia',0);
$agence_amount      = $this->class_security->validate_var($datas_proforma,'b_agence_amount',0);

//print_r($datas_packages);
?>


    <div class="modal-body">


        <?php
//            if(($b_booking_type == 1 and $b_type == 4) and ($b_booking_type == 2)):
        ?>
        <div class="row" id="days_events"><div class="col"><h3>Cantidad de PAX</h3></div></div>
        <hr>

        <div class="row mb-3">

            <div class="col form-group">
                <label>Adultos</label>
                <input type="text" readonly value="<?=$a_cpax?>" id="a_cpax"  autofocus  class="form-control imput_reset text-center numeros n_cpax" autocomplete="off">
            </div>

            <div class="col form-group  hide_all">
                <label>Monto</label>
                <input type="text" readonly value="$<?=number_format($a_amount,2)?>" id="a_amount" autofocus  class="form-control imput_reset text-center  a_amount" autocomplete="off">
            </div>


            <div class="col form-group  hide_all">
                <label>Niños</label>
                <input type="text" readonly value="<?=$n_cpax?>" id="n_cpax"  autofocus  class="form-control imput_reset text-center n_cpax" autocomplete="off">
            </div>

            <div class="col form-group  hide_all">
                <label>Monto</label>
                <input type="text" readonly value="$<?=number_format($n_amount,2)?>" id="n_amount" autofocus  class="form-control imput_reset text-center  a_amount" autocomplete="off">
            </div>

            <div class="col form-group  hide_all">
                <label>C. PAX</label>
                <input type="text" readonly value="<?=$cpax?>" id="cpax" readonly  autofocus required class="form-control imput_reset text-center numeros" autocomplete="off">
            </div>

            <div class="col form-group hide_all">
                <label>Total</label>
                <input type="text" readonly id="total" readonly value="$<?=number_format($total,2)?>"  autofocus  class="form-control imput_reset text-center " autocomplete="off">
            </div>

        </div>

        <div class="row mb-3"  id="agence_div">

            <div class="col form-group">
                <label>Guia Turistico</label>
                <input type="text" readonly value="<?=$agence_guia?>" id="agence_guia"  autofocus  class="form-control imput_reset text-center numeros n_cpax" autocomplete="off">
            </div>


            <div class="col form-group  hide_all">
                <label>Monto</label>
                <input type="text" readonly value="<?=$agence_amount?>" id="agence_amount"   autofocus  class="form-control imput_reset text-center dinero a_amount" autocomplete="off">
            </div>


        </div>

        <?php
//            endif;
        ?>



        <?php
        if(strlen($imageq) >= 7):
        ?>

        <div class="row mt-3"><div class="col"><h3>Croquis Evento</h3></div></div>
        <div class="row">
            <div class="col text-center">
                <img  src="<?=base_url('_files/events/'.$image)?>" class="img-fluid w-50" alt="">
            </div>
        </div>
        <hr>

        <?php
            endif;
        ?>

        <div class="row"><div class="col"><h3>Días Evento</h3></div></div>
        <hr>

        <div class="table-responsive">
            <table class="table table-striped text-center">
                <thead>
                <tr>
                    <th scope="col" class="w-10">Dia</th>
                    <th>Fecha</th>
                    <th>Hora Inicial</th>
                    <th>Hora Final</th>
                    <th>Salon</th>

                    <?php
                        if($b_type == 4):
                    ?>
                    <th>Tipo</th>
                    <th>Cantidad</th>
                    <?php
                        endif;
                    ?>
                 </tr>
                </thead>
                <tbody id="table_days2">
                <?php
                $i = 1;
                foreach($datas_days As $dayas){
                    ?>
                    <tr>
                        <td><?=$i?></td>
                        <td><input type="text"  value="<?=$dayas->bd_day?>" data-id="<?=$i?>" disabled class="form-control form-control-sm text-center" ></td>
                        <td><input type="text"  value="<?=$dayas->bd_houri?>"  disabled class="form-control form-control-sm text-center"  ></td>
                        <td><input type="time"  value="<?=$dayas->bd_hourf?>"  disabled class="form-control form-control-sm text-center  "  ></td>
                        <td><input type="text"  value="<?=$dayas->r_name?>"  disabled  class="form-control form-control-sm text-center  "  ></td>
                        <?php
                            if($b_type == 4):
                        ?>
                        <td><input type="text"  value="<?=(isset($dayas->bd_type) and strlen($dayas->bd_type) > 0) ? $this->class_data->typeBox[$dayas->bd_type] : ''?>"  disabled class="form-control form-control-sm text-center  "  ></td>
                        <td><input type="text"  value="<?=$dayas->bd_account?>"  disabled  class="form-control form-control-sm text-center  "  ></td>

                        <?php
                            endif;
                        ?>
                        
                      </tr>
                      
                        <tr>
                    <th colspan="7">Observación</th>
                </tr>
                </thead>
                    <tr>
                        <td colspan="7">
                            <textarea name="tableline" rows="10" autofocus disabled class="form-control imput_reset" autocomplete="off"><?=$dayas->bd_description?></textarea>
                            </td>
                    </tr>
                    <tr><td colspan="6">
                            <hr>
                            <div class="row">
                                <div class="col-6  border-end">

                                    <div class="row">
                                        <div class="col">
                                            <h5 class="text-center">Servicio de Eventos</h5>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col text-center">Descripción</div>
                                        <div class="col-3  text-center">Tiempo</div>
                                    </div>

                                    <div id="details_events_<?=$i?>">
                                        <?php
                                        if(isset($events_time)){
                                            $ev = 0;
                                            foreach($events_time  As $times){
                                                $timestamp = microtime(true);

                                                // Convertir el timestamp a un entero y añadir una parte aleatoria para mayor unicidad
                                                $numeroUnico = $timestamp * 10000 + mt_rand(1000, 9999);


                                                if($dayas->bd_day == $times->et_date){
                                                    echo "<div class='row mb-2 events' id='event_{$numeroUnico}'>
                                                                                <div class='col'><input type='text' disabled name='events[{$i}][description][]' class='form-control form-control-sm' value='{$times->et_description}'></div>
                                                                                <div class='col-3'><input type='text' disabled name='events[{$i}][time][]' class='form-control form-control-sm text-center timepicker' value='{$times->et_time_service}'></div>
                                                                            </div>";
                                                    $ev++;
                                                }
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>

                                <div class="col-6">

                                    <div class="row">
                                        <div class="col">
                                            <h5 class="text-center"> Menu Especial  </h5>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col  text-center">Nombre Usuario</div>
                                        <div class="col  text-center">Menu</div>
                                    </div>

                                    <div id="details_especials_<?=$i?>">
                                        <?php
                                        if(isset($events_special)){
                                            $esi = 0;
                                            foreach($events_special  As $spacial){
                                                $numeroUnico = $timestamp * 10000 + mt_rand(1000, 9999);
                                                if($dayas->bd_day == $spacial->es_date) {
                                                    echo "<div class='row mb-2 specials'  id='especial_{$numeroUnico}'>
                                                                    <div class='col'><input type='text' disabled name='special[{$i}][name][]' class='form-control form-control-sm' value='{$spacial->es_user_name}'></div>
                                                                    <div class='col'><input type='text' disabled name='special[{$i}][menu][]' class='form-control form-control-sm' value='{$spacial->es_menu}'></div>
                                                                </div>";
                                                    $esi++;
                                                }
                                            }
                                        }
                                        ?>
                                    </div>

                                </div>

                            </div>
                        </td></tr>
                    <?php
                    $i++;
                }
                ?>
                </tbody>
            </table>
        </div>
        <hr>

        <div class="row"><div class="col"><h3>Paquetes</h3></div></div>
        <hr>
        <div class="table-responsive">
            <table class="table table-striped text-center">
                <thead>
                <tr>
                    <th scope="col" class="w-10">C. Pax</th>
                    <th style="width:200px !important">Paquete</th>
                    <th width="40%">Descripción</th>
                    <th>Precio X unidad</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody id="table_package" class="package_table">

                </tbody>
            </table>
        </div>

        <div class="row">
            <div class="col-lg-4 col-sm-5"> </div>
            <div class="col-lg-4 col-sm-5 ml-auto">
                <table class="table table-clear text-right">
                    <tbody>
                    <tr>
                        <td class="left"><strong>Total</strong></td>
                        <td class="right package"><strong id="total" class="totalCalc">-</strong></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row mb-3">
            <div class="form-group col">
                <label>Observación del evento</label>
                <textarea name="tableline" rows="10" autofocus disabled class="form-control imput_reset" autocomplete="off"><?=$observation?></textarea>
            </div>
        </div>
        <hr>

        <?php
            if($type == 1):
        ?>



        <div class="row mb-3">
            <div class="form-group col">
                <label>Manteleria</label>
                <textarea name="tableline" rows="10" autofocus disabled class="form-control imput_reset" autocomplete="off"><?=$tableline?></textarea>
            </div>

        </div>
            <div class="row mb-3">

            <div class="form-group col">
                <label>Montaje</label>
                <textarea name="mounting" rows="10" autofocus disabled class="form-control imput_reset" autocomplete="off"><?=$mounting?></textarea>
            </div>

        </div>

        <hr>


       <?php
            endif;
        ?>



    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar Ventana</button>
    </div>

<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-1200');

        const packageJson = <?=json_encode($datas_packages)?>;

        // events data all
        window.special = <?=json_encode($events_time)?>

        if(packageJson.length > 0) {
            packageJson.forEach(function ( item) {
                console.log(item);
                $(this).add_package(item,false);
                setTimeout(() => {
                     $(`.package[data-id="${item.pfp_package}"]#size`).val(item.pfp_count).trigger('change');
                    $(this).change_price_manual(item.pfp_package);
                    $(this).change_price('.package',item.pfp_package);
                    // $('.element_hidde').remove();//remove delete button
                    $('.deleteAll').remove();//remove delete button
                    $('.package').prop('readonly',true)
                },100)
            })
        }
    })
</script>

