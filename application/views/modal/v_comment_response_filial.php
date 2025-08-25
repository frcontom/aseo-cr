<?php
defined('BASEPATH') or exit('No direct script access allowed');
$name          = $this->class_security->validate_var($datas,'u_name');
$atcreate      = $this->class_security->validate_var($datas,'hc_atcreate');
//$comment_id       = $this->class_security->validate_var($datas,'hc_id');
$comment       = $this->class_security->validate_var($datas,'hc_comment');
$f_name         = $this->class_security->validate_var($datas,'f_name');
$fr_name         = $this->class_security->validate_var($datas,'fr_name');
$status_service         = $this->class_security->validate_var($datas,'a_status_service');

//Comment data
$c_name      = $this->class_security->validate_var($datas,'u_name');
$c_comment   = $this->class_security->validate_var($datas,'a_observation_clean');
$c_date      = $this->class_security->validate_var($datas,'a_ending');

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

    <div class="modal-body">


        <?php
            if(isset($comment) and $comment != '' and $comment != null):
        ?>
            <h3>Comentario Recepción</h3>

            <div class="row mb-3">



                <div class="form-group col">
                    <label>Usuario Creador</label>
                    <input type="text" disabled autofocus value="<?=$name?>" class="form-control imput_reset" autocomplete="off">
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
                <h3>Asignación de Tarea</h3>
            </div>
        </div>


        <div class="row mb-3">

            <div class="form-group col">
                <label>Filial</label>
                <input type="text" name="fname" readonly  autofocus value="<?=$f_name?>" class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col">
                <label>Piso</label>
                <input type="text" disabled autofocus value="<?=$fr_name?>" class="form-control imput_reset" autocomplete="off">
            </div>

        </div>

        <?php

        if($status_service == 2):

        ?>





            <div class="row mb-3">

                <div class="form-group col">
                    <label>Observación de la tarea Actual</label>
                    <textarea rows="6" name="comentario"  autofocus class="form-control imput_reset" autocomplete="off"><?=$c_comment?></textarea>
                </div>

            </div>

            <div class="row mb-3">
            <div class="form-group col">
                <label>Evidencia Adjunta</label>
                <input type="file" name="evidencia" class="form-control imput_reset" accept="image/*" autocomplete="off">
            </div>

        </div>


            <?php

            endif;

            if(isset($tasks) and is_array($tasks) and count($tasks) >= 1):

                $operative_tasks = ( $status_service == 1) ? 'disabled' : 'required';
                ?>

            <hr>
            <div class="row">
                <div class="col-12  text-center">
                    <h3>Tareas Asignadas a completar</h3>
                </div>
            </div>

                  <div class="row mb-3">
                      <?php
                      $i = 0;
                        foreach ($tasks as $value) {

                            $task_name = $this->class_security->validate_var($value,'hcr_title');
                            $task_id = $this->class_security->validate_var($value,'hct_id');

                            echo '<div class=" col-12"><div class="form-check custom-checkbox mb-3">';
                            echo '<input type="hidden"  name="task['.$i.'][id]" value="'.$task_id.'"> <input type="checkbox" '.$operative_tasks.' class="form-check-input" id="customCheckBox'.$i.'" name="task['.$i.'][tk]"  onchange="toggleTask(this)" />  <h3 class="form-check-label ml-2" for="customCheckBox1">'.$task_name.'</h3>';
                            echo '</div></div>';


                            $i++;

                        }
                      ?>
                  </div>
            <?php endif; ?>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar Proceso</button>

        <?php
            if($status_service == 1):
                echo ' <button type="submit" class="btn btn-primary">Iniciar Tarea</button>';
            else:
                echo ' <button type="submit" class="btn btn-success">Finalizar Tarea</button>';
            endif;

        ?>




    </div>
</form>

<script>

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
                $(this).simple_call('frm_data','url_save_task',false,function(data){
                    $(this).change_status(data);
                    $('#modal_principal').modal('hide');
                },true);
                return false;
            }
        })

    })
</script>

