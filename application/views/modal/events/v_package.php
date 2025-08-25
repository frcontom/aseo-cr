<?php
defined('BASEPATH') or exit('No direct script access allowed');

$title            = $this->class_security->validate_var($datas,'p_title');
$price            = $this->class_security->validate_var($datas,'p_price');
$description      = $this->class_security->validate_var($datas,'p_description');
$status           = $this->class_security->validate_var($datas,'p_status');

?>

<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">
        <input type="hidden" name="data_id" id="data_id" value="<?=$id?>">
    <div class="modal-body">


        <div class="row mb-3">
            <div class="form-group col">
                <label>Nombre de Paquete</label>
                <input type="text" name="name" value="<?=$title?>" placeholder="Nombre Completo" autofocus required class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-3">
                <label>Precio</label>
                <input type="text" name="price"  autofocus required value="<?=$price?>" class="form-control numero imput_reset"  autocomplete="off">
            </div>

            <div class="form-group col-3">
                <label>Estado</label>
                <select class="form-control text-center imput_reset" required name="status" autocomplete="off">
                    <option value=""> [ SELECCIONAR ] </option>
                    <?php
                    foreach(array_slice($this->class_data->status,0,2) As $s_id => $s_name){
                        ++$s_id;
                        echo "<option value='". $s_id ."'  ".seleccionar_select($status,$s_id).">{$s_name['title']}</option>";
                    }
                    ?>
                </select>
            </div>

        </div>


        <div class="row">
            <div class="form-group col">
                <label>Detalle paquete</label>
                <textarea name="detail" id="detail" class="form-control" rows="5"><?=$description?></textarea>
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
        $(this).clear_modal_view('modal-1200');
        $(this).numeros_func('.numeric');
        $(this).select2_func('.select2');
        $(this).dinero_func('.numero');
        $(this).editorText('detail');
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

