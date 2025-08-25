<?php
defined('BASEPATH') or exit('No direct script access allowed');

$area              = $this->class_security->validate_var($data,'a_name');
$au_name   = $this->class_security->validate_var($data,'au_name');
$nombre              = $this->class_security->validate_var($data,'s_name');
$code                = $this->class_security->validate_var($data,'m_code');
$statusN             = $this->class_security->validate_var($data,'s_id');
$atCreate            = $this->class_security->validate_var($data,'m_atcreated');
$observation_super   = $this->class_security->validate_var($data,'m_observation_super');
//print_r($data);

//old data
$old_user = $this->class_security->validate_var($data,'m_user');
$old_type = $this->class_security->validate_var($data,'hma_type');
$old_start = $this->class_security->validate_var($data,'hma_start');
$old_end = $this->class_security->validate_var($data,'hma_ending');
?>

<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">
    <input type="hidden" name="data_id" value="<?=$filial?>">

    <div class="modal-body">

        <div class="row mb-3">

            <div class="form-group col">
                <label>Creado X.</label>
                <input type="text"  value="<?=$au_name?>" disabled  class="form-control imput_reset text-center" autocomplete="off">
            </div>


            <div class="form-group col">
                <label>Codigo</label>
                <input type="text"  value="<?=$code?>" disabled  class="form-control imput_reset text-center" autocomplete="off">
            </div>


            <div class="form-group col">
                <label>Area</label>
                <input type="text"  value="<?=$area?>" disabled  class="form-control imput_reset text-center" autocomplete="off">
            </div>




            <div class="form-group col">
                <label>Prioridad</label>
                <select name="priority" required  class="form-control form-select">
                    <?php
                    if(isset($statusAll)){
                        foreach($statusAll As $statusA){
                            echo "<option value='{$statusA->s_id}' ".seleccionar_select($statusA->s_id,$statusN).">{$statusA->s_name}</option>";
                        }
                    }
                    ?>
                </select>
            </div>


            <div class="form-group col">
                <label>Fecha Asignacion</label>
                <input type="text"  value="<?=$atCreate?>" disabled  class="form-control imput_reset text-center" autocomplete="off">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col">
                <label>Usuario</label>
                <select class="form-control" style="width: 100%;" name="usuario" required>
                    <option value=""> [ SELECCIONAR ] </option>
                    <?php
                    if(isset($users)){
                        foreach($users As $user){
                            echo "<option value='$user->u_id' ".seleccionar_select($user->u_id,$old_user).">$user->u_name</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group col">
                <label>Tipo de Tiempo</label>
                <select name="type"  class="form-control form-select" onchange="$(this).changeType()">
                    <option value="" selected disabled>[ SELECCCIONAR ]</option>
                    <?php
                    if(isset($this->class_data->type_maintenance_date)){
                        foreach($this->class_data->type_maintenance_date As $tmd_id => $tmd_name){
                            echo "<option value='{$tmd_id}'  ".seleccionar_select($tmd_id,$old_type).">{$tmd_name}</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group col">
                <label> <span id="labeldate">-</span> </label>
                    <input type="text" readonly name="time" id="fecha" value="<?=$old_start?>" required  class="form-control text-center imput_reset" autocomplete="off">
            </div>

            <div class="form-group col d-none" id="work_end">
                <label> <span id="labeldate">Fecha Final</span> </label>
                    <input type="datetime-local" disabled name="timeend" value="<?=$old_end?>"  id="date_input_end"  class="form-control text-center imput_reset" autocomplete="off">
            </div>
        </div>

        <div class="row mb-3">
            <div class="form-group col">
                <label>Observacion del Usuario Asignador</label>
                <textarea  rows="4" disabled class="form-control imput_reset" autocomplete="off"><?=$data['m_observation']?></textarea>
            </div>
        </div>

        <div class="row mb-3">
            <div class="form-group col">
                <label>Observacion Del supervisor</label>
                <textarea  rows="4" name="descripcion"  class="form-control imput_reset" autocomplete="off"><?=$observation_super?></textarea>
            </div>
        </div>


        <div class="row mb-3">
            <div class="col-12">
                <h5>Documentos De Evidencias</h5>
            </div>
            <?php
            foreach($files As $file):
                $filesImg = base_url('_files/maintenance/'.$file->mf_file);
                echo "<div class='col-3'><a href='{$filesImg}' target='_blank' class='btn btn-dark btn-block btn-sm'><i class='fas fa-download'></i> Descargar</a></div>";
            endforeach;
            ?>
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
        $(this).clear_modal_view('modal-1100');
        $(this).numeros_func('.numeric');
        $(this).select2_func('.select2');

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

