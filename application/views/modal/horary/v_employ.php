<?php
defined('BASEPATH') or exit('No direct script access allowed');
$name       = $this->class_security->validate_var($datas,'he_name');
$status     = $this->class_security->validate_var($datas,'he_status');
$phone      = $this->class_security->validate_var($datas,'he_phone');
$code       = $this->class_security->validate_var($datas,'he_code');
$category_id       = $this->class_security->validate_var($datas,'he_category');
?>

<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">
    <input type="hidden" name="data_id" id="data_id" value="<?=$id?>">
    <div class="modal-body">

        <div class="row mb-3">
            <div class="form-group col">
                <label>Nombre</label>
                <input type="text" name="name" value="<?=$name?>" placeholder="Nombre" autofocus required class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col">
                <label>Télefono</label>
                <input type="text" name="phone" value="<?=$phone?>" placeholder="Télefono" autofocus required class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-4">
                <label>Codigo</label>
                <input type="text" name="code" value="<?=$code?>" placeholder="Codigo" autofocus  class="form-control imput_reset text-center " autocomplete="off">
            </div>
        </div>

        <div class="row mb-3">
            <div class="form-group col">
                <label>Cargo</label>
                <select class="form-control text-center imput_reset" required name="category" autocomplete="off">
                    <option value=""> [ SELECCIONAR ] </option>
                    <?php
                    foreach($categorys As $category){
                        echo "<option value='$category->hc_id'  ".seleccionar_select($category_id,$category->hc_id).">$category->hc_name</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group col-4">
                <label>Estado</label>
                <select class="form-control text-center imput_reset" required name="status" autocomplete="off">
                    <option value=""> [ SELECCIONAR ] </option>
                    <?php
                    foreach($this->class_data->statusSimple As $e_id => $e_vl){
                        echo "<option value='$e_id' ".seleccionar_select($status,$e_id).">$e_vl[title]</option>";
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


        $('#frm_data').validator().on('submit', function(e) {
            if (e.isDefaultPrevented()) {
                $(this).mensaje_alerta(1, "El campo es obligatorio");
                return false;
            } else {
                e.preventDefault();
                $(this).simple_call('frm_data','url_personal_save');
                return false;
            }
        })

    })
</script>

