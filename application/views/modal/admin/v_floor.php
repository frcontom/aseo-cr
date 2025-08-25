<?php
defined('BASEPATH') or exit('No direct script access allowed');

$name       = $this->class_security->validate_var($datas,'fr_name');
?>

<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">
    <input type="hidden" name="data_id" id="data_id" value="<?=$id?>">
    <div class="modal-body">


        <div class="row mb-3">
            <div class="form-group col">
                <label>Nombre de piso</label>
                <input type="text" name="name" value="<?=$name?>" placeholder="Nombre" autofocus required class="form-control imput_reset" autocomplete="off">
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

