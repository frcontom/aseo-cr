<?php
defined('BASEPATH') or exit('No direct script access allowed');
 $room    = $this->class_security->validate_var($datas,'b_room');
$code    = $this->class_security->validate_var($datas,'b_code');
$name    = $this->class_security->validate_var($datas,'b_name');
$user    = $this->class_security->validate_var($datas,'b_user');
$email   = $this->class_security->validate_var($datas,'b_email');
$day  = $this->class_security->validate_var($datas,'b_day');
$bokingtype  = $this->class_security->validate_var($datas,'b_type');
$observation  = $this->class_security->validate_var($datas,'b_observation');
$phone  = $this->class_security->validate_var($datas,'b_phone');
$cpax   = $this->class_security->validate_var($datas,'b_cpax');

?>

<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">
    <input type="hidden" name="data_id" id="data_id" value="<?=$id?>">
    <div class="modal-body">

        <div class="row mb-3">
            <div class="form-group col">
                <label>Nombre Evento</label>
                <input type="text" name="name" value="<?=$name?>"  autofocus  class="form-control imput_reset" autocomplete="off">
            </div>



            <div class="form-group col-4">
                <label>Reserva A?</label>
                <select  name="type"   autofocus required class="form-control text-center imput_reset" autocomplete="off" onchange="$(this).selectReserva()">
                    <option value=""> [SELECCIONAR] </option>
                    <?php
                    foreach($this->class_data->bookingType As $b_id => $b_val){
                        echo "<option value='{$b_id}' ".seleccionar_select($b_id,$bokingtype).">{$b_val}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group col-2">
                <label>C. PAX</label>
                <input type="text" name="cpax" value="<?=$cpax?>"  autofocus required class="form-control imput_reset text-center numeros" autocomplete="off">
            </div>

        </div>

        <div class="row mb-3">
            <div class="form-group col">
                <label>Nombre del cliente</label>
                <input type="text" name="user" value="<?=$user?>"  autofocus required class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col">
                <label>Correo</label>
                <input type="email" name="email" value="<?=$email?>"  autofocus required class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col">
                <label>Teléfono</label>
                <input type="text" name="phone" value="<?=$phone?>"  autofocus required class="form-control imput_reset" autocomplete="off">
            </div>
        </div>

        <div class="row mb-3">
            <div class="form-group col-3">
                <label>Dias E.</label>
                <input type="text" name="day" id="day" value="<?=$day?>" autofocus required readonly class="form-control imput_reset text-center" autocomplete="off">
            </div>

            <div class="form-group text-center col-1">
                <label class="d-block">#</label>
                <button type="button" class="btn btn-primary" disabled id="daysButton" onclick="$(this).days_generator()"><i class="fas fa-calendar-check-o"></i></button>
            </div>

            <div class="form-group col">
                <label>Observación</label>
                <input type="text" name="observation" value="<?=$observation?>"  autofocus  class="form-control imput_reset" autocomplete="off">
            </div>

        </div>


        <div class="row" id="days_events"><div class="col"><h3>Días a Reservar</h3></div></div>
        <hr>

        <div class="table-responsive">
            <table class="table table-striped text-center">
                <thead>
                <tr>
                    <th scope="col" class="w-10">Dia</th>
                    <th scope="col" class="w-15">Fecha</th>
<!--                    <th scope="col" class="w-10">Repetir</th>-->
                    <th>Hora Inicial</th>
                    <th class="hiddenall">Hora Final</th>
                    <th>Salon</th>
                    <th>Observación</th>
                    <th>Validar</th>
                </tr>
                </thead>
                <tbody id="table_days">
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
        $(this).clear_modal_view('modal-1200');
        $(this).numeros_func('.numeros');
        let isSubmit = true;
        window.resultDays = [];

        const datas_days = <?=json_encode($datas_days)?>;
        window.roomsJson = <?=json_encode($rooms)?>;

        if(datas_days.length > 0) {
            $(this).days_generator()
            let i = 0;
            datas_days.forEach(function ( item) {
                $(this).days_data(item,i++);
            })
        }

        $('#frm_data').validator().on('submit', function(e) {
            if (e.isDefaultPrevented()) {
                $(this).mensaje_alerta(1, "El campo es obligatorio");
                return false;
            } else {
                e.preventDefault();
                $(this).simple_call('frm_data','url_save',false,function(result){
                    let resp = result.response;


                    //clear all
                    $('.all_button').removeClass('btn-success')
                    $('.all_button').addClass('disabled');//all Buttons Hiddens
                    $('.all_button').addClass('btn-dark');//all Buttons Hiddens

                    if(resp.success == 2){
                        window.resultDays = resp.data;


                        $('#days_events').get(0).scrollIntoView({behavior: 'smooth'});
                        $(this).mensaje_alerta(1, "Debes validar las fechas se encontraron coincidencia con otros Eventos");



                        $('.fecha').each(function(){
                            let days = $(this).val();
                            if(window.resultDays.some(r => r.pf_day == days)){

                                var dataId = $(this).data('id');
                                // Button ID
                                //Enable All Buttons
                                console.log('existe' , dataId);
                                $(`#btn_${dataId}`).removeClass('disabled')
                                $(`#btn_${dataId}`).removeClass('btn-dark');//remove all dark
                                $(`#btn_${dataId}`).addClass('btn-success');//all Buttons Hiddens
                            }
                        })
                    }else{
                        window.location.reload();
                    }
                },true,true);

                return false;
            }
        })

    })
</script>

