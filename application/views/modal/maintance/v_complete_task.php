<?php
defined('BASEPATH') or exit('No direct script access allowed');
$status_name = ($data['hma_status'] == 1) ? 'Iniciar Tarea' : 'Terminar Tarea';

$nombre   = $this->class_security->validate_var($data,'s_name');
$code   = $this->class_security->validate_var($data,'m_code');
$statusN   = $this->class_security->validate_var($data,'s_name');
$atCreate   = $this->class_security->validate_var($data,'m_atcreated');
$observation_super   = $this->class_security->validate_var($data,'m_observation_super');
$observation_super_return   = $this->class_security->validate_var($data,'m_accordance_observation');
?>

<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">
    <input type="hidden" name="id" value="<?=encriptar($data['m_id'])?>">
    <input type="hidden" name="assigne" value="<?=encriptar($data['hma_id'])?>">
    <div class="modal-body">

        <div class="row mb-3">
            <div class="form-group col">
                <label>Codigo</label>
                <input type="text"  value="<?=$code?>" disabled  class="form-control imput_reset text-center" autocomplete="off">
            </div>


            <div class="form-group col">
                <label>Estado actual</label>
                <input type="text"  value="<?=$statusN?>" disabled  class="form-control imput_reset text-center" autocomplete="off">
            </div>


            <div class="form-group col">
                <label>Fecha Asignacion</label>
                <input type="text"  value="<?=$atCreate?>" disabled  class="form-control imput_reset text-center" autocomplete="off">
            </div>
        </div>


        <div class="row">
            <div class="form-group col">
                <label>Estado actual</label>
                <input type="text"  value="<?=$data['s_name']?>" disabled  class="form-control imput_reset text-center" autocomplete="off">
            </div>

            <div class="form-group col">
                <label>Tipo de Tiempo</label>
                <input type="text"  value="<?=$this->class_data->type_maintenance_date[$data['hma_type']]?>" disabled  class="form-control imput_reset text-center" autocomplete="off">
            </div>

            <div class="form-group col">
                <label>Tiempo tarea</label>
                <input type="text"  value="<?=$data['hma_time']?> Min" disabled  class="form-control imput_reset text-center" autocomplete="off">
            </div>
        </div>

        <div class="row mb-3">
            <div class="form-group col">
                <label>Observacion</label>
                <textarea  disabled  class="form-control imput_reset" rows="5" autocomplete="off"><?=$data['m_observation']?></textarea>
            </div>
        </div>

        <div class="row mb-3">
            <div class="form-group col">
                <label>Observacion Del supervisor</label>
                <textarea disabled class="form-control imput_reset"  rows="5" autocomplete="off"><?=$observation_super?></textarea>
            </div>
        </div>



        <?php
        if($data['m_accordance'] == 1):
            ?>
        <hr>

            <div class="row mb-3">
                <div class="form-group col">
                    <label>Observacion supervisor Tarea Regresada</label>
                    <textarea disabled class="form-control imput_reset"  rows="5" autocomplete="off"><?=$observation_super_return?></textarea>
                </div>
            </div>
        <?php

        endif;
        ?>




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

        <?php

            if($data['hma_status'] == 2):
        ?>

            <div class="row">
                <div class="form-group col">
                    <label>Observación de tarea Finalizada</label>
                    <textarea name="descripcion" autofocus class="form-control imput_reset"  rows="5" autocomplete="off"></textarea>
                </div>
            </div>

            <div class="row mb-3">

                    <div class="form-group col-6">
                        <label>Imagen</label>
                        <div class="input-group custom-file-button">
                            <label class="input-group-text" for="inputGroupFile1">Seleccionar</label>
                            <input type="file" name="imagen1" accept="image/*" class="form-control" id="inputGroupFile1">
                        </div>
                    </div>

                    <div class="form-group col-6">
                        <label>Imagen</label>
                        <div class="input-group custom-file-button">
                            <label class="input-group-text" for="inputGroupFile2">Seleccionar</label>
                            <input type="file" name="imagen2" accept="image/*" class="form-control" id="inputGroupFile2">
                        </div>
                    </div>

                    <div class="form-group col-6">
                        <label>Imagen</label>
                        <div class="input-group custom-file-button">
                            <label class="input-group-text" for="inputGroupFile3">Seleccionar</label>
                            <input type="file" name="imagen3" accept="image/*" class="form-control" id="inputGroupFile3">
                        </div>
                    </div>

                    <div class="form-group col-6">
                        <label>Imagen</label>
                        <div class="input-group custom-file-button">
                            <label class="input-group-text" for="inputGroupFile4">Seleccionar</label>
                            <input type="file" name="imagen4" accept="image/*" class="form-control" id="inputGroupFile4">
                        </div>
                    </div>
                </div>


        <?php

            endif;
        ?>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar Proceso</button>
        <button type="submit" class="btn btn-primary"><?=$status_name?> </button>
    </div>
</form>
<id id="select2Modal"></id>

<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-1100');

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

