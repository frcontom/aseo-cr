<?php
defined('BASEPATH') or exit('No direct script access allowed');
$name  = $this->class_security->validate_var($datas,'r_name');
$money  = $this->class_security->validate_var($datas,'r_price');
$type  = $this->class_security->validate_var($datas,'r_type',$typein);
$color  = $this->class_security->validate_var($datas,'r_color');
?>

<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">
    <input type="hidden" name="data_id" id="data_id" value="<?=$id?>">
    <input type="hidden" name="type"  value="<?=$type?>">
    <div class="modal-body">

        <div class="row mb-3">
            <div class="form-group col">
                <label>Nombre Habitacion</label>
                <input type="text" name="name" value="<?=$name?>"  autofocus required class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-2">
                <label>Color</label>
                <input type="color" name="color" value="<?=$color?>"  autofocus required class="form-control imput_reset " autocomplete="off">
            </div>
        </div>

        <div class="row mb-3">

<!--            <div class="form-group col">-->
<!--                <label>Tipo</label>-->
<!--                <select name="type" autofocus required class="form-control imput_reset text-center" autocomplete="off">-->
<!--                    <option value="">Seleccione</option>-->
<!--                    --><?php
//                        foreach($this->class_data->type_room As $key => $value){
//                            echo "<option value='{$key}' ".seleccionar_select($key,$type)." >{$value}</option>";
//                        }
//                    ?>
<!--                </select>-->
<!--            </div>-->

            <div class="form-group col">
                <label>Valor</label>
                <input type="text" name="money" value="<?=$money?>"  autofocus required class="form-control imput_reset dinero" autocomplete="off">
            </div>
        </div>


    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar Proceso</button>
        <button type="submit" class="btn btn-primary">Guardar </button>
    </div>
</form>
<id id="select2Modal"></id>

<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-600');
        $(this).dinero_func('.dinero');

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

