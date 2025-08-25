<?php
defined('BASEPATH') or exit('No direct script access allowed');

?>

<form role="form" data-toggle="validator" method="POST" class="frm_data_w" id="frm_data_w">
    <div class="modal-body">
        <div class="row">
            <div class="form-group col">
                <label>Mensaje a enviar</label>
                <textarea  name="message" rows="8" required autofocus  class="form-control imput_reset" autocomplete="off"></textarea>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar Proceso</button>
        <button type="submit" class="btn btn-primary">Enviar Mensaje </button>
    </div>
</form>

<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-800');


        $('#frm_data_w').validator().on('submit', function(e) {
            if (e.isDefaultPrevented()) {
                $(this).mensaje_alerta(1, "El campo es obligatorio");
                return false;
            } else {
                e.preventDefault();
                $(this).simple_call('frm_data_w','url_send_whatsapp_message',false,function(result){
                    let resp = result.response;



                    if(resp.success == 2){
                        window.resultDays = resp.data;


                    }else{
                        $(this).mensaje_alerta(2, "Se envio el mensaje");
                        $('#modal_principal').modal('hide');
                        // window.location.reload();
                    }
                },true,true);

                return false;
            }
        })

    })
</script>

