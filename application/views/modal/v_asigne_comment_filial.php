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

<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">
    <input type="hidden" name="filial" value="<?=$id?>" />
    <div class="modal-body">
        <?php
        if(in_array($this->user_data->u_profile,[4,14])): ?>
            <h3>Comentario Recepción</h3>
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
                <div class="col-12 text-center">
                    <button type="button" id="generate_task" class="btn btn-rounded btn-primary btn-lg">
                        <span class="btn-icon-left text-primary"><i class="fa fa-user-doctor"></i></span> AGREGAR TAREAS
                    </button>
                </div>
            </div>

            <div class="row mb-3">
                <div class="table-responsive">
                    <table class="table table-bordered table-responsive-md text-center">
                        <thead>
                        <tr>
                            <th><strong>Nombre de Tarea</strong></th>
                            <th class="width80">#</th>
                        </tr>
                        </thead>
                        <tbody class="task" id="task">
                        <?php

                        if(isset($tasks) and is_array($tasks) and count($tasks) >= 1):
                            foreach ($tasks as $key => $value): ?>
                                <tr>
                                    <td>
                                        <input type="text" name="task[]" class="form-control form-control-lg border-dark" value="<?=$value['hcr_title']?>" />
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-lg delete_element"><i class="fas fa-times"></i></button>
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
                    <label>Comentario</label>
                    <textarea rows="6" name="observation" autofocus  class="form-control imput_reset" autocomplete="off"><?=$comment?></textarea>
                </div>
            </div>


        <?php

        endif;

        if($this->user_data->u_profile != 3): ?>
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



        <?php
        endif;
        ?>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar Proceso</button>

        <?php
        if(in_array($this->user_data->u_profile,[4,14])): ?>
            <button type="submit" class="btn btn-primary">Guardar</button>
        <?php
        endif;
        ?>
    </div>
</form>

<script>
    $(document).ready(function () {
        //remove size modal
        $(this).clear_modal_view("modal-1000");

        document.getElementById('generate_task').addEventListener('click', function () {
            var table = document.getElementById('task');
            var newRow = document.createElement('tr');
            newRow.innerHTML =
                '<td><input type="text" name="task[]" class="form-control form-control-lg border-dark" /></td>' +
                '<td><button type="button"  class="btn btn-danger btn-lg delete_element"><i class="fas fa-times"></i></button></td>';
            table.appendChild(newRow);
        })


        document.addEventListener('click', function (e) {
            // Verifica si el clic fue en un botón con la clase 'delete_element'
            if (e.target.matches('.delete_element') || e.target.closest('.delete_element')) {
                const button = e.target.closest('.delete_element');
                const row = button.closest('tr');
                if (row) {
                    row.remove();
                }
            }
        });




        $("#frm_data")
            .validator()
            .on("submit", function (e) {
                if (e.isDefaultPrevented()) {
                    $(this).mensaje_alerta(1, "El campo es obligatorio");
                    return false;
                } else {
                    e.preventDefault();
                    $(this).simple_call("frm_data", "url_save",false,(data) =>{

                        let response = data.response;
                        if(response.success){
                            $(this).socket_comunication( response);
                            $('#modal_principal').modal('hide');
                        }
                        return false;
                    },true)
                    return false;
                }
            });
    });
</script>
