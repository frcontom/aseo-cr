<?php
defined('BASEPATH') or exit('No direct script access allowed');

$seller            = $this->class_security->validate_var($datas,'s_name');
$guest             = $this->class_security->validate_var($datas,'b_client_name');

$bill_number       = $this->class_security->validate_var($datas,'bill_number');
$percentage        = $this->class_security->validate_var($datas,'bill_percentage');
$bill_tip       = $this->class_security->validate_var($datas,'bill_tip');
$bill_amount       = $this->class_security->validate_var($datas,'bill_amount');
$lock              = $this->class_security->validate_var($datas,'bill_modify');
?>

<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">
    <input type="hidden" name="data_id" id="data_id" value="<?=$id?>">
    <div class="modal-body">

        <div class="row mb-3">
            <div class="form-group col">
                <label>Vendedor</label>
                <input type="text" readonly value="<?=$seller?>"  autofocus required class="form-control imput_reset text-center" autocomplete="off">
            </div>

            <div class="form-group col">
                <label>Nombre del cliente</label>
                <input type="text"  readonly value="<?=$guest?>"  autofocus required class="form-control imput_reset text-center" autocomplete="off">
            </div>
        </div>

        <div class="row mb-3">
            <div class="form-group col">
                <label>Numero de Factura</label>
                <input type="text" value="<?=$bill_number?>" readonly  autofocus  class="form-control imput_reset" autocomplete="off">
            </div>
        </div>


        <div class="row mb-3">
            <div class="form-group col">
                <label>Porcentaje %</label>
                <input type="hidden" name="edit"  id="edit" value="<?=$lock?>" required autocomplete="off">
                <input type="text" name="percentage" id="percentage" value="<?=$percentage?>"  onkeyup="$('#edit').val(2);$(this).calcule_sell_only()" autofocus required class="form-control imput_reset text-center porcentaje" autocomplete="off">
            </div>

            <div class="form-group col">
                <label>Monto</label>
                <input type="text" id="amount" readonly value="<?=number_format($bill_amount,2)?>"  autofocus  class="form-control imput_reset text-center " autocomplete="off">
            </div>

            <div class="form-group col">
                <label>Propina</label>
                <input type="text" name="comission" readonly id="bill" value="$<?=number_format($bill_tip,2)?>"  autofocus  class="form-control imput_reset text-center " autocomplete="off">
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
        $(this).numeros_func('.numeros');
        $(this).dinero_func('.dinero');
        $(this).date_range_func('#fecha1','#fecha2','yyyy-mm-dd');

        if ($('.porcentaje').length > 0) {
            $(".porcentaje").autoNumeric('init',{
                aSign:'%',
                pSign: 's',
                mDec: '2',
                vMax : '999.99'
            });
        }

        $.fn.calcule_sell_only = function () {
            return this.each(function () {
                let percentage   = $('#percentage').val();
                let amount       = $('#amount').val();
                let bill         = $('#bill');//propina

                const formatter= Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' })

                //parse float
                let f_percentage = parseFloat(percentage.replace(/,/g, ""));
                let f_amount     = parseFloat(amount.replace(/,/g, ""));


                if(amount == '' || percentage == '') {
                    bill.val(0);
                    return;
                }

                //percentage calcule
                let percentajeC = f_percentage / 100; //get calcule percentage
                let calcules = ((f_amount*percentajeC));

                bill.val(formatter.format(calcules));
            });
        }

        $('#frm_data').validator().on('submit', function(e) {
            if (e.isDefaultPrevented()) {
                $(this).mensaje_alerta(1, "El campo es obligatorio");
                return false;
            } else {
                e.preventDefault();
                $(this).simple_call('frm_data','url_save_restaurant');
                return false;
            }
        })

    })
</script>

