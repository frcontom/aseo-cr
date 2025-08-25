<?php
defined('BASEPATH') or exit('No direct script access allowed');
$name       = $this->class_security->validate_var($datas,'hc_name');
$order       = $this->class_security->validate_var($datas,'hc_order');
$profile_id  = $this->class_security->validate_var($datas,'hc_profile');
$propierty_id  = $this->class_security->validate_var($datas,'hc_propertie');
?>

<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">
    <input type="hidden" name="data_id" id="data_id" value="<?=$id?>">
    <div class="modal-body">

        <div class="row mb-3">
            <div class="form-group col">
                <label>Nombre</label>
                <input type="text" name="name" value="<?=$name?>" placeholder="Nombre" autofocus required class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-4">
                <label>Orden</label>
                <input type="text" name="order" value="<?=$order?>" placeholder="Orden" autofocus required class="form-control imput_reset text-center numero" autocomplete="off">
            </div>
        </div>

        <div class="row mb-3">
            <div class="form-group col">
                <label>propietario</label>
                <select class="form-control text-center imput_reset" required name="propertie" autocomplete="off">
                    <option value=""> [ SELECCIONAR ] </option>
                    <?php
                        foreach($propierties As $propierty){
                            echo "<option value='$propierty->pt_id'  ".seleccionar_select($propierty_id,$propierty->pt_id).">$propierty->pt_name</option>";
                        }
                    ?>
                </select>
            </div>

            <div class="form-group col">
                <label>Administrador de personal</label>
                <select class="form-control text-center imput_reset" required name="profile" autocomplete="off">
                    <option value=""> [ SELECCIONAR ] </option>
                    <?php
                        foreach($profiles As $profile){
                            echo "<option value='$profile->p_id'  ".seleccionar_select($profile_id,$profile->p_id).">$profile->p_name</option>";
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
        $(this).clear_modal_view('modal-600');
        $(this).numeros_func('.numero');


        $('#frm_data').validator().on('submit', function(e) {
            if (e.isDefaultPrevented()) {
                $(this).mensaje_alerta(1, "El campo es obligatorio");
                return false;
            } else {
                e.preventDefault();
                $(this).simple_call('frm_data','url_category_save');
                return false;
            }
        })

    })
</script>

