<?php
defined('BASEPATH') or exit('No direct script access allowed');

?>

<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">
    <div class="modal-body">

        <div class="row mb-3">
            <div class="col">
                <label>Area</label>
                <select class="form-control" style="width: 100%;" name="area" required>
                    <option value=""> [ SELECCIONAR ] </option>
                    <?php
                    if(isset($areas)){
                        foreach($areas As $area){
                            echo "<option value='$area->a_id'>$area->a_name</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="col">
                <label>Prioridad</label>
                <input type="hidden" name="priority_name" id="priority_name">
                <select class="form-control" style="width: 100%;" name="priority"  id="priority" required onchange="">
                    <option value=""> [ SELECCIONAR ] </option>
                    <?php
                    if(isset($status)){
                        foreach($status As $statu){
                            echo "<option value='$statu->s_id'>$statu->s_name</option>";
                        }
                    }
                    ?>
                </select>
            </div>
        </div>


        <div class="row mb-3">
            <div class="form-group col">
                <label>Observación</label>
                <textarea type="text" name="descripcion" autofocus required  class="form-control imput_reset" rows="4" autocomplete="off"></textarea>
            </div>

        </div>

        <div class="row mb-3">

            <div class="form-group col-6">
                <label>Imagen</label>
                <div class="input-group custom-file-button">
                    <label class="input-group-text" for="inputGroupFile">Seleccionar Evidencia</label>
                    <input type="file" name="imagen1" accept="image/*" class="form-control" id="inputGroupFile">
                </div>
            </div>

            <div class="form-group col-6">
                <label>Imagen</label>
                <div class="input-group custom-file-button">
                    <label class="input-group-text" for="inputGroupFile2">Seleccionar Evidencia</label>
                    <input type="file" name="imagen2" accept="image/*" class="form-control" id="inputGroupFile2">
                </div>
            </div>

            <div class="form-group col-6">
                <label>Imagen</label>
                <div class="input-group custom-file-button">
                    <label class="input-group-text" for="inputGroupFile3">Seleccionar Evidencia</label>
                    <input type="file" name="imagen3" accept="image/*" class="form-control" id="inputGroupFile3">
                </div>
            </div>

            <div class="form-group col-6">
                <label>Imagen</label>
                <div class="input-group custom-file-button">
                    <label class="input-group-text" for="inputGroupFile4">Seleccionar Evidencia</label>
                    <input type="file" name="imagen4" accept="image/*" class="form-control" id="inputGroupFile4">
                </div>
            </div>
        </div>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar Proceso</button>
        <button type="submit" class="btn btn-primary">Guardar </button>
    </div>
</form>
<input type="hidden" id="url_save_task" value="<?=base_url('ticket_create')?>">

<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-1000');


        $("#priority").change(function(){
            $("#priority_name").val($("#priority option:selected").text());
        })

        $('#frm_data').validator().on('submit', function(e) {
            if (e.isDefaultPrevented()) {
                $(this).mensaje_alerta(1, "El campo es obligatorio");
                return false;
            } else {
                e.preventDefault();
                $(this).simple_call('frm_data','url_save_task');
                return false;
            }
        })

    })
</script>

