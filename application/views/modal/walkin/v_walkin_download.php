<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">
    <input type="hidden" name="data_id" id="data_id" value="<?=$id?>">
    <div class="modal-body">

        <div class="row mb-3">
            <div class="form-group col">
                <label>Vendedor</label>
                <select type="text" name="seller" required class="form-control imput_reset text-center fecha" autocomplete="off">
                    <option value="-1">[TODOS LOS VENDEDORES]</option>
                    <?php
                        foreach ($sellers as $seller) {
                            echo "<option value='{$seller->s_id}'>{$seller->s_name}</option>";
                        }
                    ?>
                </select>
            </div>
        </div>


        <div class="row mb-3">
            <div class="form-group col">
                <label>Fecha Inicial</label>
                <input type="text" name="date1" id="fecha1" autofocus required class="form-control imput_reset text-center fecha" autocomplete="off">
            </div>

            <div class="form-group col">
                <label>Fecha Final</label>
                <input type="text" name="date2"  id="fecha2" autofocus required class="form-control imput_reset text-center fecha" autocomplete="off">
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar Proceso</button>
        <button type="submit" class="btn btn-primary">Exportar </button>
    </div>
</form>

<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-600');
        $(this).date_range_func('#fecha1','#fecha2','yyyy-mm-dd');

        $('#frm_data').validator().on('submit', function(e) {
            if (e.isDefaultPrevented()) {
                $(this).mensaje_alerta(1, "El campo es obligatorio");
                return false;
            } else {
                e.preventDefault();
                $(this).simple_call('frm_data','url_sell_export',false,function(response){
                   const data = response.response;
                   if(data.success == 1){
                       window.open(data.file,'_blank');
                   }else{
                       $(this).mensaje_alerta(1, "Lo sentimos el documento no se pudo exportar");
                   }
                },true);
                return false;
            }
        })

    })
</script>

