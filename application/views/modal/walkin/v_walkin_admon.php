<?php
defined('BASEPATH') or exit('No direct script access allowed');

$seller          = $this->class_security->validate_var($datas,'w_seller');
//$seller_name          = $this->class_security->validate_var($datas,'s_name');
$guest           = $this->class_security->validate_var($datas,'w_guest');
$entry           = $this->class_security->validate_var($datas,'w_entry');
$exit            = $this->class_security->validate_var($datas,'w_exit');
$night           = $this->class_security->validate_var($datas,'w_count_night');
$fee             = $this->class_security->validate_var($datas,'w_fee');
$amount          = $this->class_security->validate_var($datas,'w_amount');
$comission       = $this->class_security->validate_var($datas,'w_comission');
$percentage       = $this->class_security->validate_var($datas,'w_percentage');
$lock            = $this->class_security->validate_var($datas,'w_lock');
$number_booking       = $this->class_security->validate_var($datas,'w_number_booking');
?>

<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">
    <input type="hidden" name="data_id" id="data_id" value="<?=$id?>">
    <div class="modal-body">

        <div class="row mb-3">
            <div class="form-group col">
                <label>Vendedor</label>
                <select  name="seller" id="seller" onchange="$(this).calcule_days();$(this).calcule_sell()" autofocus required class="form-control form-select text-center imput_reset" autocomplete="off">
                    <?php
                    foreach($sellers as $sell){
                        echo "<option value='{$sell->s_id}' ".seleccionar_select($sell->s_id,$seller).">{$sell->s_name} </option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group col">
                <label>Huésped</label>
                <input type="text" name="guest" readonly value="<?=$guest?>"  autofocus required class="form-control imput_reset text-center" autocomplete="off">
            </div>
        </div>

        <div class="row mb-3">
            <div class="form-group col">
                <label>Ingreso</label>
                <input type="text" name="entry" value="<?=$entry?>" id="fecha1" onchange="$(this).calcule_days();$(this).calcule_sell()" autofocus required class="form-control imput_reset text-center fecha" autocomplete="off">
            </div>

            <div class="form-group col">
                <label>Salida</label>
                <input type="text" name="exit" value="<?=$exit?>" id="fecha2" onchange="$(this).calcule_days();$(this).calcule_sell()" autofocus required class="form-control imput_reset text-center fecha" autocomplete="off">
            </div>

            <div class="form-group col">
                <label>Cant. Noches</label>
                <input type="text" name="night" id="night" value="<?=$night?>" readonly autofocus required class="form-control imput_reset text-center numeros" autocomplete="off">
            </div>
        </div>

        <div class="row mb-3">
            <div class="form-group col">
                <label>Tarifa</label>
                <input type="text" name="free" id="feet" value="<?=$fee?>"  onkeyup="$(this).calcule_sell()"  autofocus required class="form-control imput_reset text-center dinero" autocomplete="off">
            </div>

            <div class="form-group col">
                <label>Monto de venta</label>
                <input type="text" name="amount" id="amount" value="<?=$amount?>" readonly autofocus required class="form-control imput_reset text-center" autocomplete="off">
            </div>

            <div class="form-group col">
                <label>Comisión</label>
                <input type="text" name="comission" id="amount_comision" value="<?=$comission?>" readonly autofocus required class="form-control imput_reset text-center " autocomplete="off">
            </div>

            <div class="form-group col">
                <label>Porcentaje %</label>
                <input type="hidden" name="edit"  id="edit" value="<?=$lock?>" required autocomplete="off">
                <input type="text" name="percentage" id="percentage" value="<?=$percentage?>" onkeyup="$('#edit').val(2);$(this).calcule_sell()"  autofocus required class="form-control imput_reset text-center porcentaje" autocomplete="off">
            </div>

        </div>

        <div class="row mb-3">
            <div class="form-group col">
                <label>Numero de reserva</label>
                <input type="text" value="<?=$number_booking?>" readonly autofocus required class="form-control imput_reset " autocomplete="off">
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar Proceso</button>
        <button type="submit" class="btn btn-primary">Guardar </button>
    </div>
</form>

<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-700');
        $(this).numeros_func('.numeros');
        $(this).dinero_func('.dinero');
        $(this).date_range_func('#fecha1','#fecha2','yyyy-mm-dd');


        //percentaje
        window.percentaje = <?=json_encode($percentaje)?>

        if ($('.porcentaje').length > 0) {
            $(".porcentaje").autoNumeric('init',{
                aSign:'%',
                pSign: 's',
                mDec: '2',
                vMax : '999.99'
            });
        }

        $('#frm_data').validator().on('submit', function(e) {
            if (e.isDefaultPrevented()) {
                $(this).mensaje_alerta(1, "El campo es obligatorio");
                return false;
            } else {
                e.preventDefault();
                $(this).simple_call('frm_data','url_save');
                return false;
            }
        })

    })
</script>

