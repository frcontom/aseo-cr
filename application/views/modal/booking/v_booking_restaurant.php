<?php
defined('BASEPATH') or exit('No direct script access allowed');
$seller         = $this->class_security->validate_var($datas,'b_seller');
$uname          = $this->class_security->validate_var($datas,'u_name', $this->user_data->u_name);
$name           = $this->class_security->validate_var($datas,'b_event_name');
$user           = $this->class_security->validate_var($datas,'b_client_name');
$email          = $this->class_security->validate_var($datas,'b_client_email');
$phone          = $this->class_security->validate_var($datas,'b_client_phone');
$day            = $this->class_security->validate_var($datas,'b_day');
$bokingtype     = $this->class_security->validate_var($datas,'b_book_at');
$observation    = $this->class_security->validate_var($datas,'b_observation');
$cpax           = $this->class_security->validate_var($datas,'b_cpax');

$a_cpax         = $this->class_security->validate_var($datas,'b_cpax_a',0);
$a_amount       = $this->class_security->validate_var($datas,'b_amount_a',0);

$n_cpax         = $this->class_security->validate_var($datas,'b_cpax_n',0);
$n_amount       = $this->class_security->validate_var($datas,'b_amount_n',0);


$bill_number    = $this->class_security->validate_var($datas,'bill_number');
$bill_amount    = $this->class_security->validate_var($datas,'bill_amount');

$total          = $this->class_security->validate_var($datas,'b_total',0);

$imageq               = $this->class_security->validate_var($datas_proforma,'e_image');
$image = ($imageq != '') ? $imageq : '';

?>

<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">
    <input type="hidden" name="data_id" id="data_id" value="<?=$id?>">
    <input type="hidden" name="name" value="Restaurante">
    <input type="hidden" name="email" value="-">
    <input type="hidden" name="phone" value="-">
    <input type="hidden" name="type" id="type" value="3">
    <div class="modal-body">

        <div class="row mb-3">

            <div class="form-group col-3">
                <label>Creador por.</label>
                <input type="text"  value="<?=$uname?>" disabled  autofocus  class="form-control imput_reset text-center" autocomplete="off">
            </div>

            <div class="form-group  col-3">
                <label>Vendedor</label>
                <select  name="seller" autofocus  class="form-control text-center imput_reset" autocomplete="off">
                    <option value=""> [SELECCIONAR] </option>
                    <?php
                    foreach($sellers As $selle){
                        echo "<option value='{$selle->s_id}' ".seleccionar_select($selle->s_id,$seller).">{$selle->s_name}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group col">
                <label>Nombre del cliente</label>
                <input type="text" name="user" value="<?=$user?>"  autofocus required class="form-control imput_reset" autocomplete="off">
            </div>


            <div class="form-group col-2">
                <label>Reserva A?</label>
                 <input type="text" readonly value="Restaurante" autofocus  class="form-control imput_reset text-center" autocomplete="off">
            </div>
        </div>

        <div class="row" id="days_events"><div class="col"><h3>Cantidad de PAX</h3></div></div>
        <hr>

        <div class="row mb-3">

            <div class="col form-group">
                <label>Adultos</label>
                <input type="text" name="a_cpax" value="<?=$a_cpax?>" id="a_cpax" onkeyup="$(this).calcule_values(true)"  autofocus  class="form-control imput_reset text-center numeros n_cpax" autocomplete="off">
            </div>

            <div class="col form-group d-none">
                <label>Monto</label>
                <input type="text" name="a_amount" value="<?=$a_amount?>" id="a_amount"  autofocus  class="form-control imput_reset text-center dinero a_amount" autocomplete="off">
            </div>


            <div class="col form-group">
                <label>Niños</label>
                <input type="text" name="n_cpax" value="<?=$n_cpax?>" id="n_cpax" onkeyup="$(this).calcule_values(true)"  autofocus  class="form-control imput_reset text-center numeros n_cpax" autocomplete="off">
            </div>

            <div class="col form-group d-none">
                <label>Monto</label>
                <input type="text" name="n_amount" value="<?=$n_amount?>" readonly id="n_amount"  autofocus  class="form-control imput_reset text-center dinero a_amount3" autocomplete="off">
            </div>

            <div class="col form-group">
                <label>C. PAX</label>
                <input type="text" name="cpax" value="<?=$cpax?>" id="cpax" readonly onkeyup="$(this).calcule_values(true)"  autofocus required class="form-control imput_reset text-center numeros" autocomplete="off">
            </div>

<!--            <div class="col form-group d-none">-->
<!--                <label>Total</label>-->
<!--                <input type="text" name="total" id="total3" readonly value="--><?php //=$total?><!--"  autofocus  class="form-control imput_reset text-center dinero" autocomplete="off">-->
<!--            </div>-->

        </div>

        <div class="row mb-3">

            <div class="form-group col">
                <label>Numero de Factura</label>
                <input type="text" name="bill_number" value="<?=$bill_number?>"  autofocus  class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-3">
                <label>Monto</label>
                <input type="text" name="bill_amount" value="<?=$bill_amount?>"  onkeyup="$(this).calcule_values(true)" autofocus  class="form-control imput_reset text-center a_amount dinero" autocomplete="off">
            </div>


            <div class="form-group col-3">
                <label>Total</label>
                <input type="text" name="total" id="total" readonly value="<?=$total?>"  autofocus  class="form-control imput_reset text-center dinero" autocomplete="off">
            </div>


        </div>



        <div class="row mb-3">
            <div class="form-group col-2">
                <label>Dias o Salones</label>
                <input type="text" name="day" id="day" value="<?=$day?>" autofocus required  class="form-control imput_reset text-center" autocomplete="off">
            </div>

            <div class="form-group text-center col-1">
                <label class="d-block">#</label>
                <button type="button"  class="btn btn-primary"  id="daysButton" onclick="$(this).selectReserva();"><i class="fas fa-calendar-check-o"></i></button>
            </div>

            <div class="form-group custom-file col-3">

                <label for="customFile" class="form-label">Croquis</label>
                <input type="file" id="customFile" name="imagen" class="form-control" accept="image/*">
            </div>


            <div class="form-group col">
                <label>Observación</label>
                <input type="text" name="observation" value="<?=$observation?>"  autofocus  class="form-control imput_reset" autocomplete="off">
            </div>

        </div>




        <div class="row" id="days_events"><div class="col"><h3>Días a Reservar</h3></div></div>
        <hr>

        <div class="table-responsive">
            <table class="table table-striped text-center">
                <thead>
                <tr>
                    <th scope="col" class="w-10">Dia</th>
                    <th scope="col" class="w-15">Fecha</th>
                    <th>Hora Inicial</th>
                    <th class="hiddenall">Hora Final</th>
                    <th>Salon</th>
                    <th>Observación</th>
                    <th>Validar</th>
                </tr>
                </thead>
                <tbody id="table_days">
                </tbody>
            </table>
        </div>

        <hr>

        <?php

        if($image != ''):
        ?>
        <div class="row mt-3"><div class="col"><h3>Croquis Evento</h3></div></div>
        <div class="row">
            <div class="col text-center">
                <img  src="<?=base_url('_files/events/'.$image)?>" class="img-fluid w-50" alt="">
            </div>
        </div>
        <?php
        endif;
        ?>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar Proceso</button>
        <button type="submit" class="btn btn-primary">Guardar </button>
    </div>
</form>

<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-1200');
        $(this).numeros_func('.numeros');
        $(this).dinero_func('.dinero');
        let isSubmit = true;
        window.resultDays = [];

        const datas_days = <?=json_encode($datas_days)?>;
        window.roomsJson = <?=json_encode($rooms)?>;



        $(this).selectReserva();// call days

        if($("#data_id").val() != ''){
            $(this).selectReserva()
        }

        if(datas_days.length > 0) {
            // $(this).days_generator()
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
                $(this).simple_call('frm_data','url_save',false,function(result){
                    let resp = result.response;


                    //clear all
                    $('.all_button').removeClass('btn-success')
                    $('.all_button').addClass('disabled');//all Buttons Hiddens
                    $('.all_button').addClass('btn-dark');//all Buttons Hiddens

                    if(resp.success == 2){
                        window.resultDays = resp.data;


                        $('#days_events').get(0).scrollIntoView({behavior: 'smooth'});
                        $(this).mensaje_alerta(1, "Debes validar las fechas se encontraron coincidencia con otros Eventos");



                        $('.fecha').each(function(){
                            let days = $(this).val();
                            if(window.resultDays.some(r => r.bd_day == days)){

                                var dataId = $(this).data('id');
                                // Button ID
                                //Enable All Buttons
                                console.log('existe' , dataId);
                                $(`#btn_${dataId}`).removeClass('disabled')
                                $(`#btn_${dataId}`).removeClass('btn-dark');//remove all dark
                                $(`#btn_${dataId}`).addClass('btn-success');//all Buttons Hiddens
                            }
                        })
                    }else{
                        window.location.reload();
                    }
                },true,true);

                return false;
            }
        })

    })
</script>

