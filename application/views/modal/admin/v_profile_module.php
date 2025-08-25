<?php
defined('BASEPATH') or exit('No direct script access allowed');

?>

<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">
    <input type="hidden" name="data_id" id="data_id" value="<?=$id?>">
    <div class="modal-body">


        <div class="row mb-3">
            <div class="form-group col">
                <label>Nombre de perfil</label>
                <input type="text" disabled value="<?=$name?>" placeholder="Nombre" autofocus  class="form-control imput_reset" autocomplete="off">
            </div>


        </div>

        <div class="row mb-3">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-striped table-responsive-sm">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Estado</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $i = 1;
                            foreach($datas As $data){
                                $id = $data['m_id'];
                                $is_check = ($data['has_permission'] == 1) ? 'checked' : '';

                                echo '<tr>';
                                echo '<td>'.$i.'</td>';
                                echo '<td>'.$data['m_name'].'</td>';
                                echo '<td><input type="hidden" name="permission['.$i.'][id]" value="'.$id.'"><input name="permission['.$i.'][menu]" type="checkbox" class="form-check" '.$is_check.'></td>';
                                echo '</tr>';
                                $i++;
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
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
        $(this).clear_modal_view('modal-600');


        $('#frm_data').validator().on('submit', function(e) {
            if (e.isDefaultPrevented()) {
                $(this).mensaje_alerta(1, "El campo es obligatorio");
                return false;
            } else {
                e.preventDefault();
                $(this).simple_call('frm_data','url_save_module');
                return false;
            }
        })

    })
</script>

