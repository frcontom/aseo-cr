<?php
defined('BASEPATH') or exit('No direct script access allowed');

$name          = $this->class_security->validate_var($datas,'u_name');
$document          = $this->class_security->validate_var($datas,'u_document');
$username      = $this->class_security->validate_var($datas,'u_username');
$email         = $this->class_security->validate_var($datas,'u_email');
$profile_id    = $this->class_security->validate_var($datas,'u_profile');
$permissions    = $this->class_security->validate_var($datas,'u_permissions');
$tatus         = $this->class_security->validate_var($datas,'u_status');
?>

<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">
    <div class="modal-body">

        <div class="row mb-3">
            <div class="form-group col">
                <label>Limpieza</label>
                <select name="user" autofocus required style="" class="form-control form-control-lg imput_reset select2 w-100" autocomplete="off">
                    <option value="">  SELECCIONAR </option>
                    <?php
                        if(isset($users)){
                            foreach ($users as $user){
                                echo "<option value='{$user->u_id}'>{$user->u_name}</option>";
                            }
                        }
                    ?>

                </select>
            </div>

        </div>

        <div class="row mb-3">
            <div class="form-group col">
                <label>Filiales</label>
                <select name="rooms[]" required class="select2_multiple" class="form-control form-control-lg" multiple="multiple">
                    <?php
                    if(isset($datas)){
                        foreach ($datas as $data){
                            echo "<option value='{$data->f_id}'>{$data->f_name}</option>";
                        }
                    }
                    ?>

                </select>
            </div>

        </div>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar Proceso</button>
        <button type="submit" class="btn btn-primary">Guardar </button>
    </div>
</form>

<div id="select2Modal"></div>
<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-1000');
        $(this).select2_func('.select2');
        $(this).func_select2();


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

