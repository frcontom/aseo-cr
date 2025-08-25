<?php
defined('BASEPATH') or exit('No direct script access allowed');

$name            = $this->class_security->validate_var($datas,'s_name');
$status         = $this->class_security->validate_var($datas,'s_status');
?>

<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">
    <input type="hidden" name="data_id" id="data_id" value="<?=$id?>">
    <div class="modal-body">
        <div class="row mb-3">
            <div class="form-group col">
                <label>Nombre del Vendedor</label>
                <input type="text" name="name" value="<?=$name?>" placeholder="Nombre Completo" autofocus required class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-5">
                <label>Estado</label>
                <select class="form-control text-center imput_reset" required name="status"  autocomplete="off">
                    <option value=""> [ SELECCIONAR ] </option>
                    <?php
                    foreach($this->class_data->statusSimple As $e_id => $e_vl){
                        echo "<option value='$e_id' ".seleccionar_select($status,$e_id).">$e_vl[title]</option>";
                    }
                    ?>
                </select>
            </div>

        </div>

        <div class="row mb-3">
            <div class="col form-group">
                <label>Porcentaje</label>
                <select class="form-control text-center imput_reset" id="percentage" autocomplete="off">
                    <option value=""> [ SELECCIONAR ] </option>
                    <?php
                    foreach($percentages As $percentage){
                        $per_id = $percentage['wp_id'];
                        $per_name = $percentage['wp_name'];
                        $type = $this->class_data->walkin_type[$percentage['wp_type']];
                        echo "<option value='$per_id'>$per_name |  $type</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="col-2 form-group">
                <label class="d-block text-white">add</label>
                <button type="button" onclick="$(this).add_element()" class="btn btn-success"><i class="fas fa-add"></i></button>
            </div>
            
        </div>

        <div class="table-responsive">
            <table class="table text-center">
                <thead>
                <tr>
                    <th><strong>Nombre</strong></th>
                    <th><strong>Tipo</strong></th>
                    <th><strong>Eliminar</strong></th>
                </tr>
                </thead>
                <tbody id="table_list">

                <?php
                if(isset($seller_percentage)){
                    foreach($seller_percentage As $sellerp){

                        $filter = $this->class_security->filter_array_simple($percentages,'wp_id',$sellerp->sp_percentage);
                        if(isset($filter)){
                            $filter_value = array_values($filter);
                            $seller_id = $filter_value[0]['wp_id'];
                            $seller_type = $this->class_data->walkin_type[$filter_value[0]['wp_type']];
                            echo "<tr id='perc_{$seller_id}'>";
                            echo "<td><input type='hidden' name='percentage[]' value='{$seller_id}'>".$filter_value[0]['wp_name']."</td>";
                            echo "<td>".$seller_type."</td>";
                            echo "<td><a href='jascript:void(0)' onclick='$(this).delete_tr(\"{$seller_id}\")' class='btn btn-danger btn-sm'><i class='fas fa-times'></i></a></td>";
                        }

                        }
                    }

                ?>
                </tbody>
            </table>
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
        // $('.timepicker').timepicker();

        $.fn.add_element = function () {
            return this.each(function () {
                let percentage = $('#percentage');
                //percent value
                let percen_id = percentage.val();
                let percen_text = percentage.find('option:selected').text();
                let div_add = $('#table_list');

                if(percen_id != ''){
                    let per_text_exp = percen_text.split('|');
                    let template = `
                    <tr id='perc_${percen_id}'>
                        <td><input type="hidden" name="percentage[]" value="${percen_id}">${per_text_exp['0']}</td>
                        <td>${per_text_exp['1']}</td>
                        <td><a href="jascript:void(0)" onclick="$(this).delete_tr('${percen_id}')" class='btn btn-danger btn-sm'><i class="fas fa-times"></i></a></td>
                    </tr>
                    `;
                    div_add.append(template)
                }
                return;

            });
        }

        $.fn.delete_tr = function (id) {
            return this.each(function () {
                $(`#perc_${id}`).remove()
            });
        }

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

