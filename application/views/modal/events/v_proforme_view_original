<?php
defined('BASEPATH') or exit('No direct script access allowed');
$u_name        = $this->class_security->validate_var($datas,'u_name');
$moneyId        = $this->class_security->validate_var($datas,'b_currency');
$name        = $this->class_security->validate_var($datas,'b_event_name');
$day        = $this->class_security->validate_var($datas,'b_day');
$count       = $this->class_security->validate_var($datas,'b_cpax');
$type        = $this->class_security->validate_var($datas,'b_mounting_type');
$nameUser    = $this->class_security->validate_var($datas,'b_client_name');
//$salon       = $this->class_security->validate_var($datas,'pf_salon');
$document    = $this->class_security->validate_var($datas,'b_client_document');
$persontype   = $this->class_security->validate_var($datas,'b_client_type');
$telephone   = $this->class_security->validate_var($datas,'b_client_phone');
$email       = $this->class_security->validate_var($datas,'b_client_email');
$observation        = $this->class_security->validate_var($datas,'b_observation');
$croqui        = $this->class_security->validate_var($event,'e_image');
$oferta      = $this->class_security->validate_var($datas,'b_offer_validity',8);
$type      = $this->class_security->validate_var($datas,'b_type');

//event data
$event_company_name        = $this->class_security->validate_var($event,'e_company_name');
$event_company_number      = $this->class_security->validate_var($event,'e_company_number');
$event_manager_contact     = $this->class_security->validate_var($event,'e_manager_contact');
$event_manager_key         = $this->class_security->validate_var($event,'e_manager_key');
$event_table               = $this->class_security->validate_var($event,'e_table');
$event_tableline           = $this->class_security->validate_var($event,'e_tableline');
$event_mounting            = $this->class_security->validate_var($event,'e_mounting');
$event_address             = $this->class_security->validate_var($event,'e_address');
?>
<script>
    var rooms = <?=json_encode($rooms)?>;
</script>


<div class="modal-body">
    <input type="hidden" id="type" value="<?=$type?>">
    <div class="row mb-3">

        <div class="form-group col-2">
            <label>Creador X</label>
            <input type="text" name="document"  readonly value="<?=$u_name?>"  autofocus required class="form-control text-center imput_reset " autocomplete="off">
        </div>


        <div class="form-group col">
            <label>Nombre de Evento</label>
            <input type="text" name="name" value="<?=$name?>" readonly autofocus required class="form-control imput_reset" autocomplete="off">
        </div>


        <div class="form-group col-2">
            <label>Moneda.</label>
            <select name="money" autofocus required disabled class="form-control text-center imput_reset" autocomplete="off">
                <option value=""> [SELECCIONAR] </option>
                <?php
                foreach($this->class_data->money as $money => $money_val){
                    echo "<option value='{$money}' ".seleccionar_select($money,$moneyId).">{$money_val['title']} - {$money_val['code']} </option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group col-2">
            <label>Dias E.</label>
            <input type="text" name="day" id="day" value="<?=$day?>" readonly required class="form-control imput_reset text-center" autocomplete="off">
        </div>

        <div class="form-group col-2">
            <label>Cantidad Pax</label>
            <input type="text" name="count" value="<?=$count?>" readonly autofocus required class="form-control text-center imput_reset numeric" autocomplete="off">
        </div>
    </div>

    <div class="row mb-3">



        <div class="form-group col">
            <label>Tipo de persona</label>
            <select name="person" autofocus disabled class="form-control imput_reset" autocomplete="off">
                <option value=""> [SELECCIONAR] </option>
                <?php
                foreach($this->class_data->type_person as $pt_id => $pt_val){
                    echo "<option value='{$pt_id}' ".seleccionar_select($pt_id,$persontype).">{$pt_val} </option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group col">
            <label>Documento</label>
            <input type="text" name="document"  readonly value="<?=$document?>"  autofocus required class="form-control text-center imput_reset " autocomplete="off">
        </div>


        <div class="form-group col">
            <label>Telefono</label>
            <input type="text" name="telephone" readonly value="<?=$telephone?>"  autofocus required class="form-control text-center imput_reset " autocomplete="off">
        </div>

        <div class="form-group col">
            <label>Correo</label>
            <input type="email" name="email" readonly value="<?=$email?>"  autofocus required class="form-control text-center imput_reset " autocomplete="off">
        </div>

    </div>

    <div class="row mb-3">
        <div class="form-group col-2">
            <label>Validez oferta</label>
            <input type="text" name="ofert" readonly value="<?=$oferta?>"  autofocus required class="form-control text-center imput_reset numeric" autocomplete="off">
        </div>

        <div class="form-group col">
            <label>Nombre del Cliente</label>
            <input type="text" name="user" readonly value="<?=$nameUser?>"  autofocus required class="form-control text-center imput_reset " autocomplete="off">
        </div>


        <div class="form-group col">
            <label>Encargado de firmar el contrato</label>
            <input type="text" name="manager_contact" disabled value="<?=$event_manager_contact?>"  autofocus required class="form-control imput_reset" autocomplete="off">
        </div>

        <div class="form-group col">
            <label>Tipo Montaje</label>
            <input type="text" name="type" readonly value="<?=$type?>"   autofocus required class="form-control imput_reset" autocomplete="off">
        </div>

    </div>

    <div class="row mb-3">
<!---->
<!--        <div class="form-group col">-->
<!--            <label>Nombre de la Empresa</label>-->
<!--            <input type="text" name="company_name" disabled value="--><?php //=$event_company_name?><!--"  autofocus required class="form-control imput_reset" autocomplete="off">-->
<!--        </div>-->
<!---->
<!--        <div class="form-group col">-->
<!--            <label>Número de cédula jurídica</label>-->
<!--            <input type="text" name="company_number" disabled value="--><?php //=$event_company_number?><!--"  autofocus required class="form-control imput_reset" autocomplete="off">-->
<!--        </div>-->



<!--        <div class="form-group col">-->
<!--            <label>Cédula de Identidad</label>-->
<!--            <input type="text" name="manager_key" disabled value="--><?php //=$event_manager_key?><!--"  autofocus required class="form-control imput_reset" autocomplete="off">-->
<!--        </div>-->
    </div>

    <div class="row mb-3">
        <div class="form-group col">
            <label>Dirección</label>
            <input type="text" name="address" disabled value="<?=$event_address?>"  autofocus required class="form-control imput_reset" autocomplete="off">
        </div>

        <div class="form-group col">
            <label># Maestra</label>
            <input type="text" name="table" disabled value="<?=$event_table?>"  autofocus required class="form-control imput_reset" autocomplete="off">
        </div>


        <div class="form-group col">
            <label>Nombre de empresa</label>
            <input type="text" name="table" disabled value="<?=$event_company_name?>"  autofocus required class="form-control imput_reset" autocomplete="off">
        </div>

    </div>

    <div class="row mb-3">
        <div class="form-group col">
            <label>Manteleria</label>
            <textarea name="tableline" disabled rows="10" autofocus required class="form-control imput_reset" autocomplete="off"><?=$event_tableline?></textarea>
        </div>

    </div>

    <div class="row mb-3">

        <div class="form-group col">
            <label>Montaje</label>
            <textarea name="mounting" disabled rows="10" autofocus required class="form-control imput_reset" autocomplete="off"><?=$event_mounting?></textarea>
        </div>
    </div>


    <div class="row mb-3">

        <div class="form-group col">
            <label>Observación</label>
            <textarea disabled rows="10" autofocus  class="form-control imput_reset" autocomplete="off"><?=$observation?></textarea>
        </div>
    </div>

    <hr>
    <!--  Module Service, Menu   -->


    <div class="row"><div class="col"><h3>Días Evento</h3></div></div>


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
                if($type == 4):
                    echo "<th>Tipo</th><th>Cantidad</th>";
                endif;
                ?>
                <th>Observación</th>
            </tr>
            </thead>
            <tbody id="table_days2">
            <?php
            $i = 1;
            foreach($datas_days As $dayas){
                ?>
                <tr>
                    <td><?=$i?></td>
                    <td><input type="text"  value="<?=$dayas->bd_day?>" data-id="<?=$i?>" disabled class="form-control form-control-sm text-center" required></td>
                    <td><input type="time"  value="<?=$dayas->bd_houri?>"  disabled class="form-control form-control-sm text-center"  required></td>
                    <td><input type="time"  value="<?=$dayas->bd_hourf?>"  disabled class="form-control form-control-sm text-center  "  required></td>
                    <td><input type="text"  value="<?=$dayas->r_name?>"  disabled  class="form-control form-control-sm text-center  "  required></td>

                    <?php
                        if($type == 4):
                        ?>
                            <td><input type="text"  value="<?=$this->class_data->typeBox[$dayas->bd_type]?>"  disabled class="form-control form-control-sm text-center  "  required></td>
                            <td><input type="text"  value="<?=$dayas->bd_account?>"  disabled  class="form-control form-control-sm text-center  "  required></td>
                        <?php
                        endif;
                    ?>

                    <td><input type="text"  value="<?=$dayas->bd_description?>" disabled  class="form-control form-control-sm text-center"></td>
                </tr>
                <tr><td colspan="<?=($type == 4) ? 8 : 6?>">
                        <hr>
                        <div class="row">
                            <div class="col-6  border-end">

                                <div class="row">
                                    <div class="col">
                                        <h5 class="text-center">Servicio de Eventos </h5>
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
                                            if($dayas->bd_day == $times->et_date){
                                                echo "<div class='row mb-2 events' id='event_{$ev}_{$i}'>
                                                                                <div class='col'><input type='text' name='events[{$i}][description][]' disabled class='form-control form-control-sm' value='{$times->et_description}'></div>
                                                                                <div class='col-3'><input type='text' name='events[{$i}][time][]' disabled class='form-control form-control-sm text-center timepicker' value='{$times->et_time_service}'></div>
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
                                        <h5 class="text-center">Menu Especial</h5>
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
                                            if($dayas->bd_day == $spacial->es_date) {
                                                echo "<div class='row mb-2 specials'  id='especial_{$ev}_{$i}'>
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
            </tbody>
        </table>
    </div>

    <hr>

    <div class="row"><div class="col"><h3>Croquis</h3></div></div>
    <div class="row d-flex justify-content-center">
        <div class="col-5">
            <?php
                if(strlen($croqui) > 5):
            ?>
            <img src="<?=base_url('_files/events/' . $croqui)?>" class="img-fluid">
            <?php
            endif;
            ?>
        </div>
    </div>
    
    <hr>

    <div class="row"><div class="col"><h3>Habitaciones</h3></div></div>
    <div class="table-responsive">
        <table class="table table-striped text-center">
            <thead>
            <tr>
                <th scope="col" class="w-10">C. Hab</th>
                <th scope="col" class="w-10">C. Noches</th>
                <th>Habitación</th>
                <th class="w-15">Precio</th>
                <th class="w-10">IVA</th>
                <th class="w-15">Total</th>
                <th class="deleteAll">Del</th>
            </tr>
            </thead>
            <tbody id="table_rooms" class="rooms_table">
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-lg-4 col-sm-5"> </div>
        <div class="col-lg-4 col-sm-5 ml-auto">
            <table class="table table-clear text-right">
                <tbody>
                <tr>
                    <td class="left"><strong>Subtotal</strong></td>
                    <td class="right rooms"><strong id="subtotal">-</strong></td>
                </tr>
                <tr>
                    <td class="left"><strong>iva</strong></td>
                    <td class="right rooms"><strong id="ivatotal">-</strong></td>
                </tr>
                <tr>
                    <td class="left"><strong>Total</strong></td>
                    <td class="right rooms"><strong id="total"  class="totalCalc">-</strong></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <hr>

    <div class="row"><div class="col"><h3>Paquetes</h3></div></div>
    <hr>
    <div class="table-responsive">
        <table class="table table-striped text-center">
            <thead>
            <tr>
                <th scope="col" class="w-10">C. Pax</th>
                <th style="width: 200px !important;">Paquete</th>
                <th style="width: 40%;">Descripción</th>
                <th class="w-15">Precio X unidad</th>
                <th class="w-15">Total</th>
                <th class="deleteAll">Del</th>
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
                    <td class="right package"><strong id="total"  class="totalCalc">-</strong></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <hr>
    <div class="row">
        <div class="col-lg-4 col-sm-5"> </div>
        <div class="col-lg-4 col-sm-5 ml-auto">
            <table class="table table-clear text-right">
                <tbody>
                <tr>
                    <td class="left"><strong class="text-black">Total Proforma</strong></td>
                    <td class="right"><strong id="totalP">-</strong></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>


</div>

<div class="modal-footer">
    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar Proceso</button>
</div>

<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-1800');




        const roomJsonQ = <?=json_encode($datas_rooms)?>;
        const packageJson = <?=json_encode($datas_packages)?>;
        const datas_days = <?=json_encode($datas_days)?>;
        window.typeBox = <?=json_encode($this->class_data->typeBox)?>;

        //rooms
        if(roomJsonQ.length > 0){
            roomJsonQ.forEach(function ( item) {
                $(this).add_rooms(item.pfr_room, rooms )

                setTimeout(() => {
                    $(`.rooms[data-id="${item.pfr_room}"]#pax`).val(item.pfr_pax).trigger('change');
                    $(`.rooms[data-id="${item.pfr_room}"]#size`).val(item.pfr_count).trigger('change');
                    $(`.rooms[data-id="${item.pfr_room}"]#price`).val(item.pfr_price).trigger('change');
                    $(`.rooms[data-id="${item.pfr_room}"]#iva`).val(item.pfr_iva).trigger('change');
                    $(this).change_price('.rooms',item.pfr_room);
                    $('input.rooms').attr('disabled',true);
                    $('.deleteAll').remove();
                },1400)
            })
        }


        if(packageJson.length > 0) {
            packageJson.forEach(function ( item) {
                $(this).add_package(item)

                setTimeout(() => {
                    $(`.package[data-id="${item.pfp_package}"]#size`).val(item.pfp_count).trigger('change');
                    $(this).change_price_manual(item.pfp_package);
                    $(this).change_price('.package',item.pfp_package);
                    $('input.package').attr('disabled',true);
                    $('.deleteAll').remove();

                },1400)
            })
        }

        if(datas_days.length > 0) {
            $(this).days_generator()
            let i = 1;
            datas_days.forEach(function ( item) {
                $(this).days_data(item,i++);
                $('.days').prop('disabled',true)
                $('.days2').prop('disabled',true)
            })
        }

    })
</script>

