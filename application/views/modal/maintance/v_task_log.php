<?php
defined('BASEPATH') or exit('No direct script access allowed');

$status_name = ($data['hma_status'] == 1) ? 'Iniciar Tarea' : 'Terminar Tarea';
$au_name   = $this->class_security->validate_var($data,'u_name');
$nombre   = $this->class_security->validate_var($data,'s_name');
$code   = $this->class_security->validate_var($data,'m_code');
$statusN   = $this->class_security->validate_var($data,'s_name');
$atCreate   = $this->class_security->validate_var($data,'m_atcreated');
$observation_super   = $this->class_security->validate_var($data,'m_observation_super');
$fecha1_job = $this->class_security->validate_var($data,'hma_take');
$fecha2_job = $this->class_security->validate_var($data,'hma_ending');
$observation_job = $this->class_security->validate_var($data,'hma_observation');
$user_assigne = $this->class_security->validate_var($data,'au_name');
$status    = $this->class_security->array_data($data['hma_status'],$this->class_data->working,$this->class_data->estado_default);


?>

<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">
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
                <label>Prioridad</label>
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
                <input type="text"  value="<?=$status['title']?>" disabled  class="form-control imput_reset text-center" autocomplete="off">
            </div>

            <div class="form-group col">
                <label>Tipo de Asignación</label>
                <input type="text"  value="<?=$this->class_security->array_data($data['hma_type'],$this->class_data->type_maintenance_date,'-')?>" disabled  class="form-control imput_reset text-center" autocomplete="off">
            </div>

            <div class="form-group col">
                <label>Tiempo Inicio de tarea</label>
                <input type="text"  value="<?=($data['hma_time'] ?? '0')?> Min" disabled  class="form-control imput_reset text-center" autocomplete="off">
            </div>



            <div class="form-group col">
                <label>Tiempo de finalización</label>
                <input type="text"  value="<?=($data['hma_time2'] ?? '0')?> Min" disabled  class="form-control imput_reset text-center" autocomplete="off">
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

        <div class="row mb-3">
            <div class="col-12">
                <h5>Documentos De Evidencias</h5>
            </div>

            <?php
            if(isset($files) and count($files)){

                $oldSata = $this->class_security->filter_all($files,'mf_type','1');
                foreach($oldSata As $file):
                    $filesImg = base_url('_files/maintenance/'.$file['mf_file']);
                    echo "<div class='col-3 mb-2'><a href='{$filesImg}' target='_blank' class='btn btn-primary btn-block btn-sm'><i class='fas fa-download'></i> Descargar</a></div>";
                endforeach;
                }
            ?>
        </div>

        <hr>
        <?php

        if(isset($data['hma_status'])):
            ?>
            <div class="row mb-3">
                <div class="form-group col">
                    <label>Usuario Asignado</label>
                    <input type="text" value="<?=$user_assigne?>" disabled  class="form-control imput_reset text-center" autocomplete="off">
                </div>

                <div class="form-group col">
                    <label>Fecha Tomada</label>
                    <input type="text" value="<?=$fecha1_job?>" disabled  class="form-control imput_reset text-center" autocomplete="off">
                </div>


                <div class="form-group col">
                    <label>Fecha Terminada</label>
                    <input type="text"  value="<?=$fecha2_job?>" disabled  class="form-control imput_reset text-center" autocomplete="off">
                </div>

            </div>


            <div class="row">
                <div class="form-group col">
                    <label>Observación de tarea Finalizada</label>
                    <textarea disabled class="form-control imput_reset"  rows="5" autocomplete="off"><?=$observation_job?></textarea>
                </div>
            </div>

            <div class="row mb-3">

                <?php
                if(isset($files) and count($files)){

                    $oldSata = $this->class_security->filter_all($files,'mf_type','2');
                    foreach($oldSata As $file):
                        $filesImg = base_url('_files/maintenance/'.$file['mf_file']);
                        echo "<div class='col-3 mb-2'><a href='{$filesImg}' target='_blank' class='btn btn-primary btn-block btn-sm'><i class='fas fa-download'></i> Descargar</a></div>";
                    endforeach;
                }
                ?>
            </div>
        <?php

        endif;
        ?>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
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

