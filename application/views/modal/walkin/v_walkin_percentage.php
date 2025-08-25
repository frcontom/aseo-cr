<?php
defined('BASEPATH') or exit('No direct script access allowed');

$name           = $this->class_security->validate_var($datas,'wp_name');
$type           = $this->class_security->validate_var($datas,'wp_type');
$day            = $this->class_security->validate_var($datas,'wp_percentaje_day');
$major          = $this->class_security->validate_var($datas,'wp_percentaje_major');
?>

<form role="form" data-toggle="validator" method="POST" class="frm_data_modal" id="frm_data_modal">
    <input type="hidden" name="data_id" id="data_id" value="<?=$id?>">
    <input type="hidden" name="import" value="single">
    <div class="modal-body">

        <div class="row mb-3">
            <div class="form-group col">
                <label>Tipo</label>
                <select  name="type" autofocus required class="form-control form-select text-center imput_reset" autocomplete="off">
                    <option value="" selected disabled>[ SELECCIONAR ]</option>
                    <?php
                    foreach($this->class_data->walkin_type as $t_id => $t_value){
                        echo "<option value='{$t_id}' ".seleccionar_select($t_id,$type).">{$t_value} </option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group col">
                <label>Nombre</label>
                <input type="text" name="name"  value="<?=$name?>"  autofocus required class="form-control imput_reset" autocomplete="off">
            </div>
        </div>

        <div class="row mb-3">
            <div class="form-group col">
                <label>% 1 DIA</label>
                <input type="text" name="day" value="<?=$day?>"  autofocus required class="form-control imput_reset text-center porcentaje" autocomplete="off">
            </div>

            <div class="form-group col">
                <label>% SUPERIOR 1 DIA</label>
                <input type="text" name="major" value="<?=$major?>"  autofocus required class="form-control imput_reset text-center porcentaje" autocomplete="off">
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

        $('#frm_data_modal').validator().on('submit', function(e) {
            if (e.isDefaultPrevented()) {
                $(this).mensaje_alerta(1, "El campo es obligatorio");
                return false;
            } else {
                e.preventDefault();
                $(this).simple_call('frm_data_modal','url_save');
                return false;
            }
        })

    })
</script>

