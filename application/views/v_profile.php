<?php
defined('BASEPATH') or exit('No direct script access allowed');

$name          = $this->class_security->validate_var($datas,'u_name');
$username      = $this->class_security->validate_var($datas,'u_username');
$email         = $this->class_security->validate_var($datas,'u_email');
$tatus         = $this->class_security->validate_var($datas,'u_status');
$phone         = $this->class_security->validate_var($datas,'u_phone');
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><?=$modulo?></h4>
            </div>
            <div class="card-body">
                <form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">
                    <div class="modal-body">

                        <div class="row mb-3">
                            <div class="form-group col">
                                <label>Nombre</label>
                                <input type="text" name="nombre" value="<?=$name?>" placeholder="Nombre Completo" autofocus required class="form-control imput_reset" autocomplete="off">
                            </div>

                            <div class="form-group col">
                                <label>Usuario</label>
                                <input type="text"  value="<?=$username?>"  placeholder="Usuario" disabled  autofocus  class="form-control imput_reset" autocomplete="off">
                            </div>


                            <div class="form-group col">
                                <label>Telefono</label>
                                <input type="text" name="phone"  value="<?=$phone?>"  placeholder="Teléfono"   autofocus  class="form-control imput_reset" autocomplete="off">
                            </div>

                        </div>

                        <div class="row mb-3">
                            <div class="form-group col">
                                <label>Correo</label>
                                <input type="email"  name="correo" value="<?=$email?>"  placeholder="Correo" autofocus required class="form-control imput_reset" autocomplete="off">
                            </div>



                            <div class="form-group col">
                                <label>Contraseña</label>
                                <input type="text" id="password" name="password" placeholder="Contraseña" autofocus  class="form-control imput_reset" autocomplete="off">
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Guardar Datos</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
