<?php
defined('BASEPATH') or exit('No direct script access allowed');
$name          = $this->class_security->validate_var($datas,'u_name');
$atcreate      = $this->class_security->validate_var($datas,'hc_atcreate');
$comment      = $this->class_security->validate_var($datas,'hc_comment');
?>

<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">
    <input type="text" name="data_id" value="">
    <div class="modal-body">

        <div class="row mb-3">
            <div class="form-group col">
                <label>Comentario</label>
                <textarea rows="10"  autofocus required class="form-control imput_reset" autocomplete="off"><?=$comment?></textarea>
            </div>

        </div>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar Proceso</button>
    </div>
</form>

<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-1000');



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

