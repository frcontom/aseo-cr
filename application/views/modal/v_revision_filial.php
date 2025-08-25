<?php
defined('BASEPATH') or exit('No direct script access allowed');
$user_asigne          = $this->class_security->validate_var($datas,'user_asigne');
$user_resolved          = $this->class_security->validate_var($datas,'user_resolved');
$atcreate      = $this->class_security->validate_var($datas,'hc_atcreate');
//$comment_id       = $this->class_security->validate_var($datas,'hc_id');
$comment       = $this->class_security->validate_var($datas,'hc_comment');
$f_name         = $this->class_security->validate_var($datas,'f_name');
$fr_name         = $this->class_security->validate_var($datas,'fr_name');
$status_service         = $this->class_security->validate_var($datas,'a_status_service');
$a_response_attachment         = $this->class_security->validate_var($datas,'a_response_attachment');

//Comment data
$a_id     = $this->class_security->validate_var($datas,'a_id');
$c_name      = $this->class_security->validate_var($datas,'u_name');
$c_comment   = $this->class_security->validate_var($datas,'a_observation_clean');
$c_date      = $this->class_security->validate_var($datas,'a_ending');

//timer data
$total_time      = $this->class_security->validate_var($timer_task,'total_time');



$tasks_1 = array_filter($tasks, function($item) { return $item['hcr_type'] == 1; });
$tasks_2 = array_filter($tasks, function($item) { return $item['hcr_type'] == 2; });

?>

<style>
    .completed {
        text-decoration: line-through;
        color: gray;
    }
    .form-check-input {
        width: 1.5em;
        height: 1.5em;
        margin-top: 0.2em;
    }
</style>
<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">
    <input type="hidden" name="filial" value="<?=$id?>">
    <input type="hidden" name="assignment" value="<?=$a_id?>">
    <input type="hidden" name="task_status" id="task_status">

    <div class="modal-body">


        <?php
        if(isset($comment) and $comment != '' and $comment != null):
            ?>
            <h3>Comentario Recepción</h3>

            <div class="row mb-3">

                <div class="form-group col">
                    <label>Usuario Creador</label>
                    <input type="text" disabled autofocus value="<?=$user_asigne?>" class="form-control imput_reset" autocomplete="off">
                </div>

                <div class="form-group col">
                    <label>Fecha Solicitud</label>
                    <input type="text" disabled autofocus value="<?=$atcreate?>" class="form-control imput_reset" autocomplete="off">
                </div>


            </div>

            <div class="row mb-3">
                <div class="form-group col">
                    <label>Comentario</label>
                    <textarea rows="6" disabled autofocus class="form-control imput_reset" autocomplete="off"><?=$comment?></textarea>
                </div>

            </div>

            <hr>

        <?php endif; ?>

        <div class="row my-3">
            <div class="col-12 text-center">
                <h3>Comentario Finalización de la tarea</h3>
            </div>
        </div>


        <div class="row mb-3">

            <div class="form-group col">
                <label>Mucama</label>
                <input type="text" disabled autofocus value="<?=$user_resolved?>" class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col">
                <label>Habitación</label>
                <input type="text" name="fname" readonly  autofocus value="<?=$f_name?>" class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col">
                <label>Piso</label>
                <input type="text" disabled autofocus value="<?=$fr_name?>" class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col">
                <label>Tiempo Estimado</label>
                <input type="text" disabled autofocus value="<?=$total_time?>" class="form-control imput_reset text-center" autocomplete="off">
            </div>

        </div>


            <div class="row mb-3">

                <div class="form-group col">
                    <label>Observación de la tarea Actual</label>
                    <textarea rows="6" disabled  autofocus class="form-control imput_reset" autocomplete="off"><?=$c_comment?></textarea>
                </div>

            </div>

        <?php
           if(isset($a_response_attachment) and $a_response_attachment != '' and $a_response_attachment != null):
            $a_response_attachment = base_url('_files/taks/'.$a_response_attachment);
            ?>

            <div class="row mb-3">
                <div class="form-group col">
                    <a href="<?=$a_response_attachment?>" target="_blank" class="btn btn-info btn-block">Descargar Evidencia de la tarea</a>
                </div>

            </div>

        <?php
            endif;

        if(isset($tasks) and is_array($tasks) and count($tasks) >= 1):
            ?>

            <hr>
            <div class="row">
                <div class="col-12  text-center">
                    <h3>Tareas Asignadas a completar</h3>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-6">

                    <div class="col-12 my-3">
                        <h3 class="text-danger">Tareas Asignadas</h3>
                    </div>

                    <?php
                    $i = 0;
                    foreach ($tasks_1 as $value) {

                        $task_name = $this->class_security->validate_var($value,'hcr_title');
                        $task_id = $this->class_security->validate_var($value,'hct_id');
                        $task_status = ($value['hrc_status'] == 2) ? 'checked' : '';

                        echo '<div class=" col-12"><div class="form-check custom-checkbox mb-3">';
                        echo '<input type="hidden"  name="task['.$i.'][id]" value="'.$task_id.'"> <input type="checkbox" '.$task_status.'  class="form-check-input" id="customCheckBox'.$i.'" name="task['.$i.'][tk]"  onchange="toggleTask(this)" />  <h3 class="form-check-label ml-2  completed" >'.$task_name.'</h3>';
                        echo '</div></div>';
                        $i++;
                    }
                    ?>

                </div>

                <div class="col-6">

                    <div class="col-12 my-3">
                        <h3 class="text-danger">Tareas Por Defecto</h3>
                    </div>

                    <?php
                    $i = (isset($tasks_1) and count($tasks_1) > 0) ? count($tasks_1) : 0;
                    foreach ($tasks_2 as $value) {

                        $task_name = $this->class_security->validate_var($value,'hcr_title');
                        $task_id = $this->class_security->validate_var($value,'hct_id');
                        $task_status = ($value['hrc_status'] == 2) ? 'checked' : '';

                        echo '<div class=" col-12"><div class="form-check custom-checkbox mb-3">';
                        echo '<input type="hidden"  name="task['.$i.'][id]" value="'.$task_id.'"> <input type="checkbox" '.$task_status.'  class="form-check-input" id="customCheckBox'.$i.'" name="task['.$i.'][tk]"  onchange="toggleTask(this)" />  <h3 class="form-check-label ml-2  completed">'.$task_name.'</h3>';
                        echo '</div></div>';
                        $i++;
                    }
                    ?>

                </div>



            </div>



        <?php endif; ?>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar Proceso</button>

            <button type="button" onclick="change_status_task(1)" class="btn btn-primary">Regresar Tarea</button>
            <button type="button" onclick="change_status_task(2)" class="btn btn-success">Finalizar Revision</button>
            <button type="submit" id="submits" class="btn btn-success d-none">.</button>
    </div>
</form>

<script>

    function change_status_task(type)
    {
        document.getElementById("task_status").value = type;
        document.getElementById('submits').click();
    }

    function toggleTask(checkbox) {
        const taskText = checkbox.nextElementSibling;
        if (checkbox.checked) {
            taskText.classList.add('completed');
        } else {
            taskText.classList.remove('completed');
        }
    }

    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-1000');


        $('#frm_data').validator().on('submit', function(e) {
            if (e.isDefaultPrevented()) {
                $(this).mensaje_alerta(1, "El campo es obligatorio");
                return false;
            } else {
                e.preventDefault();

                $(this).simple_call('frm_data','url_resolved_filial',false,function(data){
                    $(this).change_status(data);
                    $('#modal_principal').modal('hide');
                },true)
                return false;
            }
        })

    })
</script>

