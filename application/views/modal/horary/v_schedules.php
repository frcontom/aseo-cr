<?php
defined('BASEPATH') or exit('No direct script access allowed');
$value       = $this->class_security->validate_var($datas,'hs_value');
$color       = $this->class_security->validate_var($datas,'hs_color','#FFFFFF');
?>

<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">
    <input type="hidden" name="data_id" id="data_id">
    <div class="modal-body">


        <div class="row mb-3">
            <div class="form-group col">
                <label>Tipo de Horario</label>
                <select class="form-control text-center " required name="type" id="type" autocomplete="off">
                    <option value=""> [ SELECCIONAR ] </option>
                    <option value="1"> Tiempo Libre </option>
                    <option value="2"> Horario </option>
                </select>
            </div>
        </div>

        <div class="row mb-3" id="optionals"></div>



        <div class="row">
            <div class="table-responsive">
                <table class="table table-responsive-md text-center">
                    <thead>
                    <tr>
                        <th><strong>Value</strong></th>
                        <th><strong>Sigla</strong></th>
                        <th><strong>#</strong></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        foreach ($datas as $sched) {
                            $id = encriptar($sched->hs_id);
                            $sigla = $sched->hs_sigla ?? '-';
                            echo "<tr>";
                            if($sched->hs_type == 1){
                                echo "<td><button class='btn  btn-sm text-white' style='background: ".$sched->hs_color."'>".mb_strtoupper($sched->hs_value)."</button></td>";
                            }else{
                                echo "<td><b>".$sched->hs_hour1.' - '.$sched->hs_hour2."</b></td>";
                            }
                            echo "<td><b>". $sigla . "</b></td>";
                            $data = json_encode($sched);
                            echo "<td><button type='button' onclick='$(this).getAllData({$data})' class='btn btn-primary btn-sm'  data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-pencil text-white'></i></button></td>";
                            echo "</tr>";
                        }
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
        $(this).clear_modal_view('modal-600');

        let field_option = $("#type");
        let div_optional = $("#optionals");

        $.fn.getAllData = function(data) {
            return this.each(function() {

                $("#data_id").val(data.hs_id).change();
                $("#type").val(data.hs_type).change();
                if(data.hs_type == 1){
                    $("#name").val(data.hs_value).change();
                    $("#color").val(data.hs_color).change();
                    $("#sigla").val(data.hs_sigla).change();

                }else{
                    $("#hour1").val(data.hs_hour1).change();
                    $("#hour2").val(data.hs_hour2).change();

                }

                console.log(data)

            })
        }

        field_option.on('change',function () {
            div_optional.empty();
            $('.imput_reset').val('').change();
            if($(this).val() == 1){
                //simple text
                let template = `
                                    <div class="form-group col">
                                        <label>Nombre de tiempo</label>
                                        <input type="text" name="name" id="name"  autofocus required class="form-control imput_reset text-center imput_reset" autocomplete="off">
                                    </div>

                                    <div class="form-group col-3">
                                        <label>Sigla</label>
                                        <input type="text" name="sigla" id="sigla" maxlength="4"  autofocus required class="form-control imput_reset text-center imput_reset" autocomplete="off">
                                    </div>

                                    <div class="form-group col-3">
                                        <label>Color</label>
                                        <input type="color" name="color" id="color" placeholder="Codigo" autofocus  class="form-control imput_reset text-center imput_reset" autocomplete="off">
                                    </div>
                                `;
                div_optional.append(template)
            }else{
                //fields data
                let template = `
                                    <div class="form-group col">
                                        <label>Hora Ingreso.</label>
                                        <input type="time" name="hour1" id="hour1" autofocus required class="form-control imput_reset text-center imput_reset" autocomplete="off">
                                    </div>

                                    <div class="form-group col">
                                        <label>Hora Salida.</label>
                                        <input type="time" name="hour2" id="hour2" autofocus required class="form-control imput_reset text-center imput_reset" autocomplete="off">
                                    </div>
                                `;
                div_optional.append(template)
            }
            console.log()
        })


        $('#frm_data').validator().on('submit', function(e) {
            if (e.isDefaultPrevented()) {
                $(this).mensaje_alerta(1, "El campo es obligatorio");
                return false;
            } else {
                e.preventDefault();
                $(this).simple_call('frm_data','url_schedules_save',false,function(){
                    $(this).forms_modal({"page" : "horary_schedules","title" : "Horarios"})
                });
                return false;
            }
        })

    })
</script>

