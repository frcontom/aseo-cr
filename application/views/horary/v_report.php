<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><?=$modulo?></h4>
            </div>
            <div class="card-body">
                <div class="row mb-3 text-center">
                    <div class="form-group col has-error has-danger">
                        <label>Fecha inicial</label>
                        <input type="text" id="date1" value="<?=date('Y-m-01')?>" class="form-control  text-center imput_reset" autocomplete="off">
                    </div>

                    <div class="form-group col has-error has-danger">
                        <label>Fecha Final</label>
                        <input type="text" id="date2" value="<?=date('Y-m-28')?>" class="form-control text-center  imput_reset" autocomplete="off">
                    </div>

                    <div class="form-group col">
                        <label>Departamentos</label>
                        <select class="form-control text-center imput_reset"  id="departament" autocomplete="off">
                            <option value="all"> [ TODOS LOS DEPARTAMENTOS ] </option>
                            <?php
                            foreach($departaments As  $departament){
                                echo '<option value="'.$departament->hc_id.'">'.$departament->hc_name.'</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group col">
                        <label>Empleado</label>
                        <select class="form-control text-center imput_reset"  id="employ" autocomplete="off">
                            <option value="all"> [ TODOS LOS EMPLEADOS ] </option>
                            <?php
                            foreach($employs as $employ){
                                echo '<option value="'.$employ->he_id.'">'.$employ->he_name.' - '.$employ->he_code.'</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group col-2">
                        <label class="d-block text-white">.</label>
                        <button type="button" class="btn btn-primary" onclick="$(this).report_create()"> Crear Reporte </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div id="html_report"></div>
