<?php
defined('BASEPATH') or exit('No direct script access allowed');
$uname       = $this->class_security->validate_var($datas,'u_name');
$code       = $this->class_security->validate_var($datas,'b_code');
$atcreate       = $this->class_security->validate_var($datas,'b_atcreate');
$name        = $this->class_security->validate_var($datas,'b_event_name');
$user        = $this->class_security->validate_var($datas,'b_client_name');

?>

<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">
    <input type="hidden" name="data_id" id="data_id" value="<?=$id?>">
    <div class="modal-body">

        <div class="row mb-3">

            <div class="form-group col">
                <label>Creador por.</label>
                <input type="text"  value="<?=$uname?>" disabled  autofocus  class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col">
                <label>Codigo</label>
                <input type="text"  value="<?=$code?>" disabled  autofocus  class="form-control text-center" autocomplete="off">
            </div>


            <div class="form-group col">
                <label>Fecha Creación</label>
                <input type="text"  value="<?=$atcreate?>" disabled  autofocus  class="form-control text-center" autocomplete="off">
            </div>

        </div>

        <div class="row mb-3">
            <div class="form-group col">
                <label>Nombre del evento</label>
                <input type="text"  value="<?=$name?>" disabled  autofocus  class="form-control imput_reset" autocomplete="off">
            </div>


            <div class="form-group col">
                <label>Cliente</label>
                <input type="text"  value="<?=$user?>" disabled  autofocus  class="form-control imput_reset" autocomplete="off">
            </div>

        </div>


        <div class="row mb-3">
            <div class="form-group col">
                <label>Observación de la cancelación</label>
                <textarea name="observation"  autofocus rows="10" required class="form-control imput_reset" autocomplete="off"></textarea>
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
        $(this).clear_modal_view('modal-1200');


        $('#frm_data').validator().on('submit', function(e) {
            if (e.isDefaultPrevented()) {
                $(this).mensaje_alerta(1, "El campo es obligatorio");
                return false;
            } else {
                e.preventDefault();
                $(this).simple_call('frm_data','url_ban',);

                return false;
            }
        })

    })
</script>

