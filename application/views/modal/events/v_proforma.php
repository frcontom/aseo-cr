<?php
defined('BASEPATH') or exit('No direct script access allowed');

$name        = $this->class_security->validate_var($datas,'b_event_name');
$day        = $this->class_security->validate_var($datas,'b_day');
$date2        = $this->class_security->validate_var($datas,'pf_date2');
$user_pf       = $this->class_security->validate_var($datas,'b_user',$user->u_id);
$moneyId       = $this->class_security->validate_var($datas,'b_currency');
$code       = $this->class_security->validate_var($datas,'b_code');
$count       = $this->class_security->validate_var($datas,'b_cpax');
$type        = $this->class_security->validate_var($datas,'b_mounting_type');
$booking_type        = $this->class_security->validate_var($datas,'b_type_sl');
$propertiesId     = $this->class_security->validate_var($datas,'pf_propertie');


$persontype   = $this->class_security->validate_var($datas,'b_client_type');
$nameUser     = $this->class_security->validate_var($datas,'b_client_name');
$email        = $this->class_security->validate_var($datas,'b_client_email');
$telephone    = $this->class_security->validate_var($datas,'b_client_phone');
$document     = $this->class_security->validate_var($datas,'b_client_document');

$observation        = $this->class_security->validate_var($datas,'b_observation');
$status        = $this->class_security->validate_var($datas,'b_status','1');
$oferta       = $this->class_security->validate_var($datas,'b_offer_validity',8);

?>
<script>
    var rooms = <?=json_encode($rooms)?>;
</script>

<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data" enctype="multipart/form-data">
    <input type="hidden" name="data_id" id="data_id" value="<?=$id?>">
    <input type="hidden" name="core" value="<?=$status?>">
    <input type="hidden" name="code" value="<?=$code?>">
    <div class="modal-body">

        <div class="row mb-3">
            <div class="form-group col">
                <label>Nombre de Evento</label>
                <input type="text" name="name" value="<?=$name?>"  autofocus required class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-2">
                <label>Tipo.</label>
                <select name="type_event" id="money" autofocus required  class="form-control text-center imput_reset" autocomplete="off">
                    <option value=""> [SELECCIONAR] </option>
                    <?php
                    foreach($this->class_data->booking_type as $b_id => $b_title){
                        echo "<option value='{$b_id}' ".seleccionar_select($b_id,$booking_type).">{$b_title} </option>";
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
                <label>Dias o Salones</label>
                <input type="text" name="day" id="day" value="<?=$day?>" autofocus required class="form-control imput_reset alonenumbers text-center" autocomplete="off">
            </div>

            <div class="form-group text-center col-1">
                <label class="d-block">#</label>
                <button type="button" class="btn btn-primary" onclick="$(this).days_generator()"><i class="fas fa-calendar-check-o"></i></button>
            </div>


            <div class="form-group col-1">
                <label>C.Pax</label>
                <input type="text" name="count" value="<?=$count?>"  autofocus required class="form-control text-center alonenumbers imput_reset " autocomplete="off">
            </div>

        </div>

        <div class="row mb-3">

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
            <div class="form-group col-3">
                <label for="formFile" class="form-label">Criquis</label>
                <div class="custom-file">
                    <input type="file" name="imagen" class="custom-file-input" accept="image/*">
                    <label class="custom-file-label">Selecccionar Croquis</label>
                </div>
            </div>

            <div class="form-group col-3">
                <label class="text-success">Usuario</label>
                <select  name="user_query" autofocus required class="form-control form-select text-center imput_reset" autocomplete="off">
                    <option value="" selected disabled>[ SELECCIONAR ]</option>
                    <?php
                    foreach($users as $user){
                        $user_id = encriptar($user->u_id);
                            echo "<option value='{$user_id}' ".seleccionar_select($user->u_id,$user_pf).">{$user->u_name} </option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group col-2">
                <label>Validez oferta</label>
                <input type="text" name="ofert" value="<?=$oferta?>"  autofocus required class="form-control text-center imput_reset alonenumbers" autocomplete="off">
            </div>

            <div class="form-group col">
                <label>Nombre del Cliente</label>
                <input type="text" name="user" value="<?=$nameUser?>"  autofocus required class="form-control text-center imput_reset " autocomplete="off">
            </div>

<!--            <div class="form-group col">-->
<!--                <label>Nombre del salon</label>-->
<!--                <select name="salon"  autofocus required class="form-control text-center imput_reset " autocomplete="off">-->
<!--                    <option value="" selected disabled> [SELECCIONAR] </option>-->
<!--                    --><?php
//                        foreach($rooms as $salon){
//                            if($salon->r_type == 2)   echo "<option value='{$salon->r_id}' ".seleccionar_select($salon->r_id,$salon_id).">{$salon->r_name} </option>";
//                    }
//                    ?>
<!--                </select>-->
<!--            </div>-->

        </div>

        <div class="row mb-3">

            <div class="form-group col">
                <label>Tipo Montaje</label>
                <textarea name="type" rows="4" autofocus required class="form-control imput_reset" autocomplete="off"><?=$type?></textarea>
            </div>

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

        <div class="row"><div class="col"><h3>Días Evento</h3></div></div>
        <hr>

        <div class="table-responsive" id="days_events">
            <table class="table table-striped text-center">
                <thead>
                <tr>
                    <th scope="col" class="w-10">Dia</th>
                    <th style="width: 14%">Fecha</th>
                    <th style="width: 14%">Hora Inicial</th>
                    <th style="width: 14%">Hora Final</th>
                    <th style="width: 20%">Salon</th>
                    <th>Observación</th>
                    <th><i class="fas fa-check"></i></th>
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
                        <th>Descripción</th>
                        <th>Precio X unidad</th>
                        <th>Total</th>
                        <th>Del</th>
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
<did id="root-picker-outlet"></did>

<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-1300');
        $(this).select2_func('.select2');
        $(this).dinero_func('.numero');
        $(this).numeros_func('.alonenumbers');
        // $(this).moneyQ();
        // $(this).real_money();
        // $(this).tinyText('.numero');
        let isSubmit = true;
        window.resultDays = [];


        const roomJsonQ = <?=json_encode($datas_rooms)?>;
        const packageJson = <?=json_encode($datas_packages)?>;
        const datas_days = <?=json_encode($datas_days)?>;
        window.roomsJson = <?=json_encode(array_values($this->class_security->filter_array_simple($rooms,'r_type',2)))?>;

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

        // console.log(packageJson)
        if(packageJson.length > 0) {
            packageJson.forEach(function ( item) {
                $(this).add_package(item,true)
                setTimeout(() => {
                    $(`.package[data-id="${item.pfp_package}"]#size`).val(item.pfp_count).trigger('change');
                    $(this).change_price_manual(item.pfp_package);
                    $(this).change_price('.package',item.pfp_package);
                },1400)
            })
        }

        if(datas_days.length > 0) {
            $(this).days_generator()
            let i = 1;
            datas_days.forEach(function ( item) {
                $(this).days_data(item,i++);
            })
        }



        $('#frm_data').validator().on('submit', function(e) {
            if (e.isDefaultPrevented()) {
                $(this).mensaje_alerta(1, "El campo es obligatorio");
                return false;
            } else {
                e.preventDefault();

                //validate is days not empty or undefined
                    $('.days').each(function(){
                        if($(this).val() === '' ||  $(this).val() === undefined) {
                            $(this).mensaje_alerta(1, "La Fecha y Horas en los eventos es Obligatoria");
                            isSubmit = false;
                            $('#days_events').get(0).scrollIntoView({behavior: 'smooth'});
                            return false;
                        }else{
                            isSubmit = true;
                        }
                    })


                if(isSubmit){
                    $(this).simple_call('frm_data','url_save',false,function(result){
                        let resp = result.response;

                        //clear all
                        $('.all_button').removeClass('btn-success')
                        $('.all_button').addClass('disabled');//all Buttons Hiddens
                        $('.all_button').addClass('btn-dark');//all Buttons Hiddens

                        if(resp.success == 2){
                            window.resultDays = resp.data || [];

                            // console.log(window.resultDays);
                            if(window.resultDays.length == 0){
                                $('#days_events').get(0).scrollIntoView({behavior: 'smooth'});
                                $(this).mensaje_alerta(1, "Debes validar los dias de los eventos ya que falta información");

                            }else{
                                $('#days_events').get(0).scrollIntoView({behavior: 'smooth'});
                                $(this).mensaje_alerta(1, "Debes validar las fechas se encontraron coincidencia con otros Eventos");
                            }

                            $('.fecha').each(function(){
                            let days = $(this).val();
                                if(window.resultDays.some(r => r.pf_day == days)){

                                    var dataId = $(this).data('id');
                                    // Button ID
                                    //Enable All Buttons
                                    $(`#btn_${dataId}`).removeClass('disabled')
                                    $(`#btn_${dataId}`).removeClass('btn-dark');//remove all dark
                                    $(`#btn_${dataId}`).addClass('btn-success');//all Buttons Hiddens
                                }
                            })
                        }else{
                            window.location.reload();
                        }
                    },true,true);

                    // console.log(resultDays);
                }
                return false;
            }
        })

    })
</script>

