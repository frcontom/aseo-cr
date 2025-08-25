<?php
defined('BASEPATH') or exit('No direct script access allowed');

//$status_name = ($data['hma_status'] == 1) ? 'Iniciar Tarea' : 'Terminar Tarea';
$f_name   = $this->class_security->validate_var($data,'f_name');
$u_name   = $this->class_security->validate_var($data,'u_name');
$a_code   = $this->class_security->validate_var($data,'a_code');
$s_name   = $this->class_security->validate_var($data,'s_name');
$a_atcreate   = $this->class_security->validate_var($data,'a_atcreate');


$a_assigner   = $this->class_security->validate_var($data,'a_assigner');
$a_take   = $this->class_security->validate_var($data,'a_take');
$a_ending   = $this->class_security->validate_var($data,'a_ending');
$a_observation_clean = $this->class_security->validate_var($data,'a_observation_clean');
//$fecha2_job = $this->class_security->validate_var($data,'hma_ending');
//$observation_job = $this->class_security->validate_var($data,'hma_observation');

$timer_stask = $timer->realtime($a_take,$a_ending);
?>

<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">
    <div class="modal-body">



        <div class="row mb-3">

            <div class="form-group col">
                <label>Filial</label>
                <input type="text"  value="<?=$f_name?>" disabled  class="form-control imput_reset text-center" autocomplete="off">
            </div>

            <div class="form-group col">
                <label>Asignado A.</label>
                <input type="text"  value="<?=$u_name?>" disabled  class="form-control imput_reset text-center" autocomplete="off">

            </div>



            <div class="form-group col">
                <label>Codigo</label>
                <input type="text"  value="<?=$a_code?>" disabled  class="form-control imput_reset text-center" autocomplete="off">
            </div>


            <div class="form-group col">
                <label>Estado</label>
                <input type="text"  value="<?=$s_name?>" disabled  class="form-control imput_reset text-center" autocomplete="off">
            </div>


            <div class="form-group col">
                <label>Fecha Asignacion</label>
                <input type="text"  value="<?=$a_atcreate?>" disabled  class="form-control imput_reset text-center" autocomplete="off">
            </div>
        </div>




        <div class="row">
            <div class="form-group col">
                <label>Fecha Asignación</label>
                <input type="text"  value="<?=$a_assigner?>" disabled  class="form-control imput_reset text-center" autocomplete="off">
            </div>

            <div class="form-group col">
                <label>Fecha Inicio</label>
                <input type="text"  value="<?=$a_take?>" disabled  class="form-control imput_reset text-center" autocomplete="off">
            </div>

            <div class="form-group col">
                <label>Fecha Fin</label>
                <input type="text"  value="<?=$a_ending?>" disabled  class="form-control imput_reset text-center" autocomplete="off">
            </div>

<!--            <div class="form-group col">-->
<!--                <label>Tipo de Tiempo</label>-->
<!--                <input type="text"  value="--><?php //=$this->class_security->array_data($data['hma_type'],$this->class_data->type_maintenance_date,'-')?><!--" disabled  class="form-control imput_reset text-center" autocomplete="off">-->
<!--            </div>-->

            <div class="form-group col">
                <label>Tiempo tarea</label>
                <input type="text"  value="<?=$timer_stask?> Min" disabled  class="form-control imput_reset text-center" autocomplete="off">
            </div>


        </div>

        <div class="row mb-3">
            <div class="form-group col">
                <label>Observacion</label>
                <textarea  disabled  class="form-control imput_reset" rows="5" autocomplete="off"><?=$a_observation_clean?></textarea>
            </div>
        </div>






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

