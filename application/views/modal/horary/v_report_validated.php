<?php
defined('BASEPATH') or exit('No direct script access allowed');
$id       = $this->class_security->validate_var($data,'employ_id');
$name       = $this->class_security->validate_var($data,'employ_name');
$category     = $this->class_security->validate_var($data,'category_name');
//$category_id       = $this->class_security->validate_var($datas,'he_category');
?>

<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">
    <input type="hidden" name="data_id" id="data_id" value="<?=$id?>">
    <div class="modal-body">

        <div class="row mb-3">
            <div class="form-group col">
                <label>Nombre</label>
                <input type="text"  value="<?=$name?>" disabled placeholder="Nombre" autofocus required class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col">
                <label>Categoria Empleado</label>
                <input type="text"  value="<?=$category?>" disabled placeholder="Télefono" autofocus required class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col">
                <label>Fecha Inicial</label>
                <input type="text"  value="<?=$date1?>" disabled placeholder="Nombre" autofocus required class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col">
                <label>Fecha Final</label>
                <input type="text"  value="<?=$date2?>" disabled placeholder="Nombre" autofocus required class="form-control imput_reset" autocomplete="off">
            </div>

        </div>

        <div class="row">
            <div class="table-responsive">
                <table class="table table-responsive-md">
                    <thead>
                    <tr>
                        <th><strong>Fecha</strong></th>
                        <th><strong>Hora I.</strong></th>
                        <th><strong>Hora T.</strong></th>
                        <th><strong>Tipo Hora</strong></th>
                        <th><strong>Horas</strong></th>
                        <th><strong>Comentario</strong></th>
                        <th><strong>Estado</strong></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        if(isset($data['dates']) and count($data['dates']) > 0):
                            $i = 0;
                            foreach($data['dates'] As $date):
                                $hb_id = $date['hb_id'];
                                $fecha = $date['fecha'];
                                $hora_tipo = $date['hora_tipo'];
                                $hora_marcado_entrada = $date['hora_marcado_entrada'];
                                $hora_marcado_salida = $date['hora_marcado_salida'];
//                                $minutos_trabajados = $date['minutos_trabajados'];
                                $minute_hour = $this->class_security->pass_minute_to_hours($date['minutos_trabajados']);

                                //in
                                $message = $date['message'];
                                $status = $date['status'];


                                echo "<tr> ";
                                echo "<td>
                                        <input type='hidden' name='hours[{$i}][hb_id]' value='{$hb_id}'>
                                        <input type='hidden' name='hours[{$i}][fecha]' value='{$fecha}'>
                                        <strong class='text-black'>{$fecha}</strong>
                                     </td>";
                                echo "<td>{$hora_marcado_entrada}</td>";
                                echo "<td>{$hora_marcado_salida}</td>";
                                echo "<td>{$hora_tipo}</td>";
                                echo "<td>{$minute_hour}</td>";
                                echo "<td><input name='hours[{$i}][comment]' class='form-control form-control-sm' type='text' placeholder='' value='{$message}'></td>";
                                echo "<td>";
                                echo "<select name='hours[{$i}][status]' class='form-control form-control-sm form-select text-center'>";
                                    foreach($this->class_data->status_hour As $k => $v):
                                        echo "<option value='{$k}' ".seleccionar_select($k,$status).">{$v}</option>";
                                    endforeach;
                                echo "</select>";
                                echo "</td>";
                                echo "</tr>";
                    ?>
                    <?php
                                $i++;
                            endforeach;
                        endif;

                    ?>
                    </tbody>
                </table>
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
        $(this).clear_modal_view('modal-1300');


        $('#frm_data').validator().on('submit', function(e) {
            if (e.isDefaultPrevented()) {
                $(this).mensaje_alerta(1, "El campo es obligatorio");
                return false;
            } else {
                e.preventDefault();
                $(this).simple_call('frm_data','url_save',false,function(response){
                    $('#modal_principal').modal('hide');
                    $(this).mensaje_alerta(2, 'Empleado Procesado Correctamente');
                },true);
                return false;
            }
        })

    })
</script>

