<?php
defined('BASEPATH') or exit('No direct script access allowed');
//print_r($data);exit;
?>

<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">
    <input type="hidden" name="filial" value="<?=$data['m_id']?>">
    <div class="modal-body">

        <div class="row">
            <div class="form-group col">
                <label>Filial</label>
                <input type="text"  value="<?=$data['f_name']?>" disabled  class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col">
                <label>Estado actual</label>
                <input type="text"  value="<?=$data['s_name']?>" disabled  class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col">
                <label>Tiempo tarea</label>
                <input type="text"  value="<?=$data['m_time']?> Min" disabled  class="form-control imput_reset" autocomplete="off">
            </div>
        </div>

        <div class="row mb-3">
            <div class="form-group col">
                <label>Observacion</label>
                <input type="text" value="<?=$data['m_observation']?>"  disabled  class="form-control imput_reset" autocomplete="off">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12">
                <h5>Documentos De Evidencias</h5>
            </div>

            <?php
            $oldSata = $this->class_security->filter_all($files,'mf_type','1');
            foreach($files As $file):
                $filesImg = base_url('_files/maintenance/'.$file['mf_file']);
                echo "<div class='col-3'><a href='{$filesImg}' target='_blank' class='btn btn-dark btn-block btn-sm'><i class='fas fa-download'></i> Descargar</a></div>";
            endforeach;
            ?>
        </div>

   <hr>

            <div class="row">
                <div class="form-group col">
                    <label>Observación de tarea Finalizada</label>
                    <input type="text"disabled  value="<?=$data['m_accordance_observation']?>"  class="form-control imput_reset" autocomplete="off">
                </div>
            </div>

        <div class="row mb-3">
            <div class="col-12">
                <h5>Documentos De Evidencias</h5>
            </div>

            <?php
            $oldSata2 = $this->class_security->filter_all($files,'mf_type','2');
            foreach($oldSata2 As $file):
                $filesImg = base_url('_files/maintenance/'.$file['mf_file']);
                echo "<div class='col-3'><a href='{$filesImg}' target='_blank' class='btn btn-dark btn-block btn-sm'><i class='fas fa-download'></i> Descargar</a></div>";
            endforeach;
            ?>
        </div>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar Proceso</button>
        <button type="submit" class="btn btn-primary"> Guardar </button>
    </div>
</form>
<id id="select2Modal"></id>

<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-800');

        $('#frm_data').validator().on('submit', function(e) {
            if (e.isDefaultPrevented()) {
                $(this).mensaje_alerta(1, "El campo es obligatorio");
                return false;
            } else {
                e.preventDefault();
                $(this).simple_call('frm_data','url_save_resolver');
                return false;
            }
        })

    })
</script>

