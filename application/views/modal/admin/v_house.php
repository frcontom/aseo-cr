<?php
defined('BASEPATH') or exit('No direct script access allowed');

$user_id    = $this->class_security->validate_var($datas,'f_user');
$name       = $this->class_security->validate_var($datas,'f_name');
$code       = $this->class_security->validate_var($datas,'f_code');
$tatus      = $this->class_security->validate_var($datas,'f_status');
$floor_id   = $this->class_security->validate_var($datas,'f_floor');
?>

<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">
    <input type="hidden" name="data_id" id="data_id" value="<?=$id?>">
    <div class="modal-body">

        <div class="row mb-3">
            <div class="form-group col">
                <label>Nombre Filial</label>
                <input type="text" name="name" value="<?=$name?>" placeholder="Nombre Filial" autofocus required class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group2 col custom_file_input">
                <label>Usuario</label>
                <select class="form-control text-center imput_reset" required name="user" autocomplete="off">
                    <option value="" selected disabled> [ SELECCIONAR ] </option>
                    <?php
                    foreach($users As $user){
                        echo "<option value='$user->u_id' ".seleccionar_select($user_id,$user->u_id).">$user->u_name</option>";
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="row mb-3">

            <div class="form-group col">
                <label>código</label>
                <input type="text" name="code" value="<?=$code?>"  placeholder="Codigo" autofocus required class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col">
                <label>Piso</label>
                <select class="form-control text-center imput_reset" required name="floor" autocomplete="off">
                    <option value="" selected disabled> [ SELECCIONAR ] </option>
                    <?php
                    foreach($floors As $floor){
                        echo "<option value='$floor->fr_id' ".seleccionar_select($floor_id,$floor->fr_id).">$floor->fr_name</option>";
                    }
                    ?>
                </select>
            </div>


            <div class="form-group col">
                <label>Estado</label>
                <select class="form-control text-center imput_reset" required name="status" autocomplete="off">
                    <option value=""> [ SELECCIONAR ] </option>
                    <?php
                    foreach($this->class_data->status As $e_id => $e_vl){
                        echo "<option value='$e_id' ".seleccionar_select($tatus,$e_id).">$e_vl[title]</option>";
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


<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-700');
        $(this).upload_file();

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

