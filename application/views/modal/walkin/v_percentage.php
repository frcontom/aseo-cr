<?php
defined('BASEPATH') or exit('No direct script access allowed');

$day          = $this->class_security->validate_var($datas,'wp_day',fecha(1));
$percentaje_day    = $this->class_security->validate_var($datas,'wp_percentaje_day');
$percentaje_major    = $this->class_security->validate_var($datas,'wp_percentaje_major');
?>

<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">
    <input type="hidden" name="data_id" id="data_id" value="<?=$id?>">
    <div class="modal-body">

        <div class="row mb-3">

            <div class="form-group col">
                <label>DIA</label>
                <input type="text" name="day" value="<?=$day?>" readonly autofocus required class="form-control imput_reset text-center" autocomplete="off">
            </div>

        </div>

         <div class="row mb-3">

            <div class="form-group col">
                <label>% 1 DIA</label>
                <input type="text" name="percentaje_day" value="<?=$percentaje_day?>"  autofocus required class="form-control imput_reset text-center porcentaje" autocomplete="off">
            </div>


            <div class="form-group col">
                <label>% SUPERIOR 1 DIA</label>
                <input type="text" name="percentaje_major" value="<?=$percentaje_major?>"  autofocus required class="form-control imput_reset text-center porcentaje" autocomplete="off">
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
        $(this).clear_modal_view('modal-600');

        if ($('.porcentaje').length > 0) {
            $(".porcentaje").autoNumeric('init',{
                aSign:'%',
                pSign: 's',
                mDec: '2',
                vMax : '999.99'
            });
        }


        // $('.timepicker').timepicker();

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

