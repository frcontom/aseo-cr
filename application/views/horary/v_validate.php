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
                            <label>Empleado</label>
                            <select id="employ_name"  class="form-control text-center imput_reset" autocomplete="off">
                                <option value=""> [ TODOS LOS EMPLEADOS ] </option>
                                <?php
                                foreach($employs as $employ){
                                    echo '<option value="'.$employ->he_name.'">'.$employ->he_name.' - '.$employ->he_code.'</option>';
                                }
                                ?>
                            </select>
                        </div>


                        <div class="form-group col has-error has-danger">
                            <label>Fecha inicial</label>
                            <input type="text" id="date1" value="<?=date('Y-m-01')?>" class="form-control  text-center imput_reset" autocomplete="off">
                        </div>

                        <div class="form-group col has-error has-danger">
                            <label>Fecha Final</label>
                            <input type="text" id="date2" value="<?=date('Y-m-28')?>" class="form-control text-center  imput_reset" autocomplete="off">
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-header d-sm-flex d-block shadow-sm">
                    <div>
                        <h4 class="fs-20 mb-0 font-w600 text-black mb-sm-0 mb-2">Validar reporte de horas</h4>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive" id="tablesContainer">
                        <table class="display w-100 text-center datatable_ajax">
                            <thead>
                            <tr>
                                <th>Empleado</th>
                                <th>Fecha</th>
                                <th>Hora Ingreso.</th>
                                <th>Hora Salida</th>
                                <th>Hora M. Ingreso.</th>
                                <th>Hora M. Salida</th>
                                <th>Tipo Hora</th>
                                <th>Tiempo</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
