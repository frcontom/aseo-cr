<?php
defined('BASEPATH') or exit('No direct script access allowed');

//print_r($datasP);

$name        = $this->class_security->validate_var($datasP,'b_event_name');
$day         = $this->class_security->validate_var($datasP,'b_day');
$date2       = $this->class_security->validate_var($datasP,'pf_date2');
$user_pf     = $this->class_security->validate_var($datasP,'b_user');
$moneyId     = $this->class_security->validate_var($datasP,'b_currency');
$code        = $this->class_security->validate_var($datasP,'b_code');
$count       = $this->class_security->validate_var($datasP,'b_cpax');
$mounting    = $this->class_security->validate_var($datasP,'b_mounting_type');
$propertiesId     = $this->class_security->validate_var($datasP,'pf_propertie');

$persontype   = $this->class_security->validate_var($datasP,'b_client_type');
$nameUser     = $this->class_security->validate_var($datasP,'b_client_name');
$email        = $this->class_security->validate_var($datasP,'b_client_email');
$telephone    = $this->class_security->validate_var($datasP,'b_client_phone');
$document     = $this->class_security->validate_var($datasP,'b_client_document');

$observation  = $this->class_security->validate_var($datasP,'b_observation');
$oferta       = $this->class_security->validate_var($datasP,'b_offer_validity',8);

//FINAL



$user_name        = $this->class_security->validate_var($datasP,'b_event_name');
$user_document        = $this->class_security->validate_var($datasP,'b_client_document');
$company_name        = $this->class_security->validate_var($datas,'e_company_name');
$company_number      = $this->class_security->validate_var($datas,'e_company_number');
$manager_contact     = $this->class_security->validate_var($datas,'e_manager_contact');
$manager_key         = $this->class_security->validate_var($datas,'e_manager_key');
$type_person         = $this->class_security->validate_var($datas,'e_type_person');
$phone               = $this->class_security->validate_var($datas,'e_phone');
$address             = $this->class_security->validate_var($datas,'e_address');
$email               = $this->class_security->validate_var($datas,'e_email');
$table               = $this->class_security->validate_var($datas,'e_table');
$tableline           = $this->class_security->validate_var($datas,'e_tableline');
//$mounting            = $this->class_security->validate_var($datas,'e_mounting');
$image               = $this->class_security->validate_var($datas,'e_image');
$days                = (isset($datas_days) and count($datas_days) > 0) ? count($datas_days) : 0;

?>


<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data" enctype="multipart/form-data">
    <input type="hidden" name="data_id" id="data_id" value="<?=$id?>">
    <div class="modal-body">

        <div class="row mb-3">
            <div class="form-group col">
                <label>Nombre de Evento</label>
                <input type="text" name="name" value="<?=$name?>"  autofocus required class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col">
                <label>Hotel</label>
                <select name="propertie" autofocus required  class="form-control imput_reset" autocomplete="off">
                    <option value=""> [SELECCIONAR] </option>
                    <?php
                    foreach($propierties as $propiertie){
                        echo "<option value='{$propiertie->pt_id}' ".seleccionar_select($propiertie->pt_id,$propertiesId).">{$propiertie->pt_name} </option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group col-2">
                <label>Moneda.</label>
                <select name="money" id="money" autofocus required  class="form-control text-center imput_reset" autocomplete="off">
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
                <input type="text" name="day" id="day" value="<?=$day?>" autofocus required class="form-control imput_reset text-center" autocomplete="off">
            </div>

            <div class="form-group text-center col-1">
                <label class="d-block">#</label>
                <button type="button" class="btn btn-primary" onclick="$(this).days_generator(false,false,true)"><i class="fas fa-calendar-check-o"></i></button>
            </div>


            <div class="form-group col-2">
                <label>Cantidad Pax</label>
                <input type="text" name="count" value="<?=$count?>"  autofocus required class="form-control text-center imput_reset numeric" autocomplete="off">
            </div>

        </div>

        <div class="row mb-3">

            <div class="form-group col">
                <label>Tipo de persona</label>
                <select name="person" autofocus required  class="form-control imput_reset" autocomplete="off">
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
                <input type="text" name="document" value="<?=$document?>"  autofocus  class="form-control text-center imput_reset " autocomplete="off">
            </div>

            <div class="form-group col">
                <label>Telefono</label>
                <input type="text" name="telephone" value="<?=$telephone?>"  autofocus required class="form-control text-center imput_reset " autocomplete="off">
            </div>

            <div class="form-group col">
                <label>Correo</label>
                <input type="email" name="email" value="<?=$email?>"  autofocus required class="form-control text-center imput_reset " autocomplete="off">
            </div>

        </div>

        <div class="row mb-3">
<!--            <div class="form-group col-3">-->
<!--                <label for="formFile" class="form-label">Imagen</label>-->
<!--                <div class="custom-file">-->
<!--                    <input type="file" name="imagen" class="custom-file-input" accept="image/*">-->
<!--                    <label class="custom-file-label">Selecccionar Croquis</label>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="form-group col-3">-->
<!--                <label class="text-success">Usuario</label>-->
<!--                <input  readonly autofocus  class="form-control form-select text-center imput_reset numeric" autocomplete="off">-->
<!--            </div>-->


            <div class="form-group col-2">
                <label>Validez oferta</label>
                <input type="text" name="ofert" value="<?=$oferta?>"  autofocus required class="form-control text-center imput_reset numeric" autocomplete="off">
            </div>

            <div class="form-group col">
                <label>Nombre de Empresa</label>
                <input type="text" name="company_name" value="<?=$company_name?>"  autofocus required class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col">
                <label>Nombre del Cliente</label>
                <input type="text" name="user" value="<?=$nameUser?>"  autofocus required class="form-control text-center imput_reset " autocomplete="off">
            </div>

        </div>

        <div class="row mb-3">

<!--            <div class="form-group col">-->
<!--                <label>Tipo Montaje</label>-->
<!--                <textarea name="type" rows="4" autofocus required class="form-control imput_reset" autocomplete="off">--><?php //=$type?><!--</textarea>-->
<!--            </div>-->

            <div class="form-group col">
                <label>Habitaciones</label>
                <div class="input-group mb-3">
                    <select  id="room" autofocus  class="form-control imput_reset" autocomplete="off">
                        <option value=""> [SELECCIONAR] </option>
                        <?php
                        foreach($rooms as $room){
                            if($room['r_type'] == 1) echo "<option value='{$room['r_id']}'>{$room['r_name']} </option>";
                        }
                        ?>
                    </select>
                    <span class="input-group-text" onclick="$(this).add_rooms('', rooms )"><i data-feather="file-text" class="fas fa-plus"></i></span>
                </div>
            </div>

            <div class="form-group col">
                <label>Paquetes</label>
                <div class="input-group mb-3">
                    <select  id="package" autofocus  class="form-control imput_reset" autocomplete="off">
                        <option value=""> [SELECCIONAR] </option>
                        <?php
                        foreach($packages as $pack){
                            echo "<option value='{$pack->p_id}'>{$pack->p_title} </option>";
                        }
                        ?>
                    </select>
                    <span class="input-group-text" onclick="$(this).add_package(null,true)"><i data-feather="file-text" class="fas fa-plus"></i></span>
                </div>
            </div>
        </div>

        <div class="row mb-3">

            <div class="form-group col">
                <label>Observación</label>
                <textarea name="observation" rows="4" autofocus  class="form-control imput_reset" autocomplete="off"><?=$observation?></textarea>
            </div>
        </div>

        <hr>

        <!--     FINAL   -->

        <div class="row mb-3">



<!--            <div class="form-group col">-->
<!--                <label>Documento</label>-->
<!--                <input type="text" name="manager_key" readonly value="--><?php //=$user_document?><!--"  autofocus required class="form-control imput_reset" autocomplete="off">-->
<!--            </div>-->

<!--            <div class="form-group col">-->
<!--                <label>Número de cédula jurídica</label>-->
<!--                <input type="text" name="company_number" value="--><?php //=$company_number?><!--"  autofocus required class="form-control imput_reset" autocomplete="off">-->
<!--            </div>-->



        </div>

        <div class="row mb-3">


<!--            <div class="form-group col">-->
<!--                <label>Tipo de persona</label>-->
<!--                <select name="type_person" autofocus  class="form-control imput_reset" autocomplete="off">-->
<!--                    <option value=""> [SELECCIONAR] </option>-->
<!--                    --><?php
//                    foreach($this->class_data->type_person as $pt_id => $pt_val){
//                        echo "<option value='{$pt_id}' ".seleccionar_select($pt_id,$type_person).">{$pt_val} </option>";
//                    }
//                    ?>
<!--                </select>-->
<!--            </div>-->

            <div class="form-group col">
                <label>Dirección</label>
                <input type="text" name="address" value="<?=$address?>"  autofocus required class="form-control imput_reset" autocomplete="off">
            </div>


            <div class="form-group col">
                <label>Encargado de firmar el contrato</label>
                <input type="text" name="manager_contact" value="<?=$manager_contact?>"  autofocus required class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-3">
                <label for="formFile" class="form-label">Imagen</label>
                <div class="custom-file">
                    <input type="file" name="imagen" class="custom-file-input" accept="image/*">
                    <label class="custom-file-label">Selecccionar Croquis</label>
                </div>
            </div>

            <div class="form-group col">
                <label># Maestra</label>
                <input type="text" name="table" value="<?=$table?>"  autofocus  class="form-control imput_reset" autocomplete="off">
            </div>

<!--            <div class="form-group col">-->
<!--                <label>Teléfono</label>-->
<!--                <input type="text" name="phone" value="--><?php //=$phone?><!--"  autofocus required class="form-control imput_reset" autocomplete="off">-->
<!--            </div>-->
<!---->
<!---->
<!--            <div class="form-group col">-->
<!--                <label>Correo</label>-->
<!--                <input type="text" name="email" value="--><?php //=$email?><!--"  autofocus required class="form-control imput_reset" autocomplete="off">-->
<!--            </div>-->
        </div>

        <div class="row mb-3">


            <div class="form-group col">
                <label>Manteleria</label>
                <textarea name="tableline" rows="10" autofocus required class="form-control imput_reset" autocomplete="off"><?=$tableline?></textarea>
            </div>
        </div>
            <div class="row mb-3">

            <div class="form-group col">
                <label>Tipo Montaje</label>
                <textarea name="mounting" rows="10" autofocus required class="form-control imput_reset" autocomplete="off"><?=$mounting?></textarea>
            </div>
        </div>





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
                    <th>Observación</th>
                </tr>
                </thead>
                <tbody id="table_days">

                </tbody>
            </table>
        </div>

        <hr>

        <div class="row"><div class="col"><h3>Habitaciones</h3></div></div>
        <hr>
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
                    <th>Del</th>
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
                        <td class="right rooms"><strong id="total" class="totalCalc">-</strong></td>
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
                    <th>Paquete</th>
                    <th width="40%">Descripción</th>
                    <th style=" width: 160px !important; ">Precio X unidad</th>
                    <th style=" width: 160px !important; ">Total</th>
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
        <button type="submit" class="btn btn-primary">Guardar </button>
    </div>
</form>
<did id="select2Modal"></did>


<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-1800');
        $(this).numeros_func('.numeric');
        $(this).select2_func('.select2');
        $(this).fecha_func('.dates');
        $(this).custom_file();
        $('.timepicker').timepicker();

        window.rooms = <?=json_encode($rooms)?>;
        const roomJsonQ = <?=json_encode($datas_rooms)?>;
        const packageJson = <?=json_encode($datas_packages)?>;
        const datas_days  = <?=json_encode($datas_days)?>;
        const days_data_time    = <?=json_encode($events_time) ?>;
        const days_data_special    = <?=json_encode($events_special) ?>;
        window.roomsJson = <?=json_encode(array_values($this->class_security->filter_array_simple($rooms,'r_type',2)))?>;

        //const packageJson = <?php //=json_encode($datas_packages)?>//;
        //const datas_days = <?php //=json_encode($datas_days)?>//;
        //window.roomsJson = <?php //=json_encode($rooms)?>//;

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
                },1400)
            })
        }

        //add generator package
        if(packageJson.length > 0) {
            packageJson.forEach(function ( item) {
                $(this).add_package(item,true)
                setTimeout(() => {
                    $(`.package[data-id="${item.pfp_package}"]#size`).val(item.pfp_count).trigger('change');
                    $(this).change_price_manual(item.pfp_package);
                    $(this).change_price('.package',item.pfp_package);
                    // $('.deleteAll').remove();//remove delete button
                },1400)
            })
        }

        if(datas_days.length > 0) {
            $(this).days_generator(false,false,true)
            let i = 1;
            datas_days.forEach(function ( item) {
                $(this).days_data(item,i);
                i++;
            })
        }

        //add generator menu special food
        if(days_data_time.length > 0 || days_data_special.length > 0) {
            //each times
            setTimeout(() => {
                const groupedByTree = days_data_time.reduce((acc, event) => {
                    (acc[event.et_tree] ||= []).push(event);
                    return acc;
                }, {});

                const groupedSpecial = days_data_special.reduce((acc, event) => {
                    (acc[event.es_tree] ||= []).push(event);
                    return acc;
                }, {});

                Object.entries(groupedByTree).forEach(([tree, items]) => {
                    items.forEach((item, i) => {
                        $(this).add_services(tree);
                        $(`.service_descripcion_${tree}`).eq(i).val(item.et_description);
                        $(`.service_time_${tree}`).eq(i).val(item.et_time_service);
                    });
                });

                Object.entries(groupedSpecial).forEach(([tree, items]) => {
                    items.forEach((item, i) => {
                        $(this).add_especial(tree);
                        $(`.special_name_${tree}`).eq(i).val(item.es_user_name);
                        $(`.special_menu_${tree}`).eq(i).val(item.es_menu);
                    });
                });
            },1000)
        }



        $('#frm_data').validator().on('submit', function(e) {
            if (e.isDefaultPrevented()) {
                $(this).mensaje_alerta(1, "El campo es obligatorio");
                return false;
            } else {
                e.preventDefault();

                if($(".description").length > 0){
                    let i = 0;
                    $(".description").each(function(){
                        $($(".txtdescription")[i]).val(this.innerHTML);
                        i++
                    });
                }

                $(this).simple_call('frm_data','url_save');
                return false;
            }
        })

    })
</script>

