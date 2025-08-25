<?php
defined('BASEPATH') or exit('No direct script access allowed');

$name          = $this->class_security->validate_var($datas,'u_name');
$document          = $this->class_security->validate_var($datas,'u_document');
$username      = $this->class_security->validate_var($datas,'u_username');
$email         = $this->class_security->validate_var($datas,'u_email');
$phone         = $this->class_security->validate_var($datas,'u_phone');
$profile_id    = $this->class_security->validate_var($datas,'u_profile');
//$permissions    = $this->class_security->validate_var($datas,'u_permissions');
$tatus         = $this->class_security->validate_var($datas,'u_status');
$area_old         = $this->class_security->validate_var($datas,'u_area');
$area_type_old         = $this->class_security->validate_var($datas,'u_area_type');
?>

<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">
    <input type="hidden" name="data_id" id="data_id" value="<?=$id?>">
    <div class="modal-body">

        <div class="row mb-3">
            <div class="form-group col">
                <label>Nombre</label>
                <input type="text" name="nombre" value="<?=$name?>" placeholder="Nombre Completo" autofocus required class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col">
                <label>Usuario</label>
                <input type="text" name="usuario" value="<?=$username?>"  placeholder="Usuario" autofocus required class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col">
                <label>Contraseña</label>
                <input type="text" id="password" name="password" placeholder="Contraseña" autofocus required class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col">
                <label>Telefono</label>
                <input type="text" name="phone" value="<?=$phone?>"  placeholder="telefono" autofocus  class="form-control imput_reset" autocomplete="off">
            </div>


        </div>

        <div class="row mb-3">

            <div class="form-group col">
                <label>Correo</label>
                <input type="email"  name="correo" value="<?=$email?>"  placeholder="Correo" autofocus required class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col">
                <label>Estado</label>
                <select class="form-control text-center imput_reset" required name="estado" id="estado" autocomplete="off">
                    <option value=""> [ SELECCIONAR ] </option>
                    <?php
                    foreach($this->class_data->status As $e_id => $e_vl){
                            echo "<option value='$e_id' ".seleccionar_select($tatus,$e_id).">$e_vl[title]</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group col">
                <label>Tipo Usuario</label>
                <select class="form-control text-center imput_reset" required name="perfil" id="perfil" autocomplete="off">
                    <option value=""> [ SELECCIONAR ] </option>
                    <?php
                    foreach($profiles As $profile){
                        echo "<option value='$profile->p_id'  ".seleccionar_select($profile_id,$profile->p_id).">$profile->p_name</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group col">
                <label>Area</label>
                <select class="form-control text-center imput_reset all_required" disabled  name="area" autocomplete="off">
                    <option value=""> [ SELECCIONAR ] </option>
<!--                    <option value="0"> Gerencia </option>-->
                    <?php
                    $newArea = array_merge([[
                        'a_id' => 0,
                        'a_name' => '** Gerencia **',

                    ]],$areas);

                    if(isset($newArea)){
                        foreach($newArea As $area){
                            echo "<option value='".$area['a_id']."'".seleccionar_select($area['a_id'],$area_old).">".$area['a_name']."</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group col">
                <label>Tipo de Area</label>
                <select class="form-control text-center imput_reset all_required"   name="area_type" autocomplete="off">
                    <option value=""> [ SELECCIONAR ] </option>
                    <?php
                    if(isset($areas)){
                        foreach($this->class_data->area As $a_id => $a_vl){
                            echo "<option value='$a_id' ".seleccionar_select($a_id,$area_type_old).">$a_vl</option>";
                        }
                    }
                    ?>
                </select>
            </div>

        </div>

        <hr>
        <div class="row">
            <div class="col-12 mb-2"><h4>propietario</h4></div>
            <div class="table-responsive">
                <table class="table text-center">
                    <thead>
                    <tr>
                        <th><strong>Nombre</strong></th>
                        <th><strong>Acción</strong></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        $i = 0;
                        foreach($properties As $propertie){
                            $p_id = $propertie['pt_id'];
                            $p_validate = $propertie['validate'];
                            echo "<tr>";
                            echo "<td><input type='hidden' name='propertie[{$i}][id]' value='{$p_id}'> <label class='form-check-label' for='customCheckBox{$p_id}'>". $propertie['pt_name']."</label></td>";
                            echo "<td><div class='form-check custom-checkbox mb-3'><input type='checkbox' name='propertie[{$i}][check]' class='form-check-input' {$p_validate} id='customCheckBox{$p_id}'></div></td>";
                            echo "</tr>";
                            $i++;
                        }
                    ?>
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
        $(this).clear_modal_view('modal-1000');

        <?php
        if(strlen($id) >= 5){
            echo " $('#password').attr('required',false);";
        }
        ?>


        setTimeout(() => {
            $("form#frm_data .form-control").trigger('change');
        },1000)

        let all_required = $(".all_required");
        $('#perfil').change(function (){
            var area = $(this).val() || 1;
            if(![1].find(r => r == area)){
                all_required.attr('disabled',false).attr('required',true).trigger('change');
            }else{
                all_required.attr('disabled',true).attr('required',false).trigger('change');
            }
        })




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

