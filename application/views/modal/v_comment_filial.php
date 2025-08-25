<?php
defined('BASEPATH') or exit('No direct script access allowed');
$name       = $this->class_security->validate_var($datas,'u_name');
$atcreate   = $this->class_security->validate_var($datas,'hc_atcreate');
$comment    = $this->class_security->validate_var($datas,'hc_comment');
$fname      =  $this->class_security->validate_var($datasComment,'f_name');
$c_date     = $this->class_security->validate_var($datasComment,'a_ending');

//Comment data
$c_name      = $this->class_security->validate_var($datasComment,'u_name');
$c_comment   = $this->class_security->validate_var($datasComment,'a_observation_clean');
$c_date      = $this->class_security->validate_var($datasComment,'a_ending');
?>
    <div class="modal-body">

            <h3 class="text-center mb-3">Comentario Recepción</h3>
            <div class="row mb-3">
                <div class="form-group col">
                    <label>Filial</label>
                    <input type="text" readonly name="fname" autofocus value="<?=$fname?>" class="form-control imput_reset" autocomplete="off" />
                </div>

                <div class="form-group col">
                    <label>Fecha Solicitud</label>
                    <input type="text" disabled autofocus value="<?=$atcreate?>" class="form-control imput_reset" autocomplete="off" />
                </div>

            </div>


            <hr>


        <div class="row mb-3">
            <div class="table-responsive">
                <table class="table table-bordered table-responsive-md text-left">
                    <thead>
                    <tr>
                        <th class="width80">#</th>
                        <th><strong>Nombre de Tarea</strong></th>
                    </tr>
                    </thead>
                    <tbody class="task" id="task">
                    <?php
                    $i = 1;
                    if(isset($tasks) and is_array($tasks) and count($tasks) >= 1):
                        foreach ($tasks as $key => $value): ?>
                            <tr>
                                <td><strong class="text-black"><?=$i++;?></strong></td>
                                <td>
                                    <input type="text" readonly  class="form-control form-control-lg border-dark" value="<?=$value['hcr_title']?>" />
                                </td>
                            </tr>
                        <?php
                        endforeach;
                    endif;

                    ?>
                    </tbody>
                </table>
            </div>
        </div>


            <div class="row mb-3">
                <div class="form-group col">
                    <label>Comentario Recepción</label>
                    <textarea rows="6" disabled autofocus  class="form-control imput_reset" autocomplete="off"><?=$comment?></textarea>
                </div>
            </div>


<!--        --><?php //if($this->user_data->u_profile != 3):
            if($c_comment != ''):
        ?>
            <hr />

            <h3>Ultimo Comentario de la tarea Asignada</h3>

            <div class="row mb-3">
                <div class="form-group col">
                    <label>Usuario Creador</label>
                    <input type="text" disabled autofocus value="<?=$c_name?>" class="form-control imput_reset" autocomplete="off">
                </div>


                <div class="form-group col">
                    <label>Fecha Comentario</label>
                    <input type="text" disabled autofocus value="<?=$c_date?>" class="form-control imput_reset" autocomplete="off">
                </div>


            </div>

            <div class="row mb-3">

                <div class="form-group col">
                    <label>Comentario</label>
                    <textarea rows="6" disabled autofocus class="form-control imput_reset" autocomplete="off"><?=$c_comment?></textarea>
                </div>

            </div>



        <?php   endif;   ?>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar Proceso</button>
    </div>

<script>
    $(document).ready(function () {
        //remove size modal
        $(this).clear_modal_view("modal-1000");

    });
</script>
