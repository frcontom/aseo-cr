<?php
defined('BASEPATH') or exit('No direct script access allowed');

$name       = $this->class_security->validate_var($datas,'s_name');
$color_f      = $this->class_security->validate_var($datas,'s_color_f');
$color_b      = $this->class_security->validate_var($datas,'s_color');
$icon_id      = $this->class_security->validate_var($datas,'s_icon');
$code       = $this->class_security->validate_var($datas,'s_code');
$time       = $this->class_security->validate_var($datas,'s_time');
$tatus      = $this->class_security->validate_var($datas,'s_status');
$priority      = $this->class_security->validate_var($datas,'s_priority');
?>

<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">
    <input type="hidden" name="data_id" id="data_id" value="<?=$id?>">
    <div class="modal-body">

        <div class="row mb-3">
            <div class="form-group  col">
                <label>Icono</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text icon_label"> </div>
                    </div>
                    <select class="form-control" id="icon" required name="icon" autocomplete="off" onchange="$(this).selected_icon()">
                        <option value=""> [ SELECCIONAR ] </option>
                        <?php
                        foreach($icons As $icon){
                            echo "<option  data-icon='$icon->i_icon' value='$icon->i_id' ".seleccionar_select($icon_id,$icon->i_id)."> {$icon->i_name} </option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="form-group col-4">
                <label>Prioridad</label>
                <select class="form-control text-center imput_reset" required name="priority" autocomplete="off">
                    <option value="" selected disabled> [ SELECCIONAR ] </option>
                    <?php
                    foreach($this->class_data->priority As $e_id => $e_vl){
                        echo "<option value='$e_id' ".seleccionar_select($priority,$e_id).">$e_vl[title]</option>";
                    }
                    ?>
                </select>
            </div>

        </div>

        <div class="row mb-3">
            <div class="form-group col">
                <label>Nombre Estado</label>
                <input type="text" name="name" value="<?=$name?>" placeholder="Nombre Estado" autofocus required class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col">
                <label>código</label>
                <input type="text" name="code" value="<?=$code?>" placeholder="Codigo" autofocus required class="form-control text-center imput_reset" autocomplete="off">
            </div>


        </div>

        <div class="row mb-3">

            <div class="form-group col">
                <label>C. Fuente</label>
                <input type="color" name="color_f" value="<?=$color_f?>"  placeholder="Color" autofocus required class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col">
                <label>C. Fondo</label>
                <input type="color" name="color" value="<?=$color_b?>"  placeholder="Color" autofocus required class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col">
                <label>Tiempo (Min)</label>
                <input type="text" name="time" value="<?=$time?>" maxlength="3"  placeholder="Tiempo (Min)" autofocus required class="form-control text-center imput_reset numeros" autocomplete="off">
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

