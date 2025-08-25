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
                <div class="row mb-3">
                    <div class="form-group col has-error has-danger">
                        <label>Fecha inicial</label>
                        <input type="text" id="fecha1" value="<?=date('Y-m-01')?>" class="form-control  text-center imput_reset" autocomplete="off">
                    </div>

                    <div class="form-group col has-error has-danger">
                        <label>Fecha Final</label>
                        <input type="text" id="fecha2" value="<?=date('Y-m-28')?>" class="form-control text-center  imput_reset" autocomplete="off">
                    </div>

                    <div class="form-group col">
                        <label>Tipo</label>
                        <select class="form-control text-center imput_reset"  id="type" autocomplete="off">
                            <option value=""> [ SELECCIONAR ] </option>
                            <?php
                                foreach($this->class_data->bookingType as $typek => $typev){
                                    echo '<option value="'.$typek.'">'.$typev.'</option>';
                            }
                            ?>
                        </select>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><?=$modulo?></h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <div class="table-responsive card-table rounded table-hover fs-14">
                        <table class="table border-no display mb-4 dataTablesCard project-bx  text-center" id="datatable_ajax_export">
                            <thead>
                            <tr>
                                <th>Creado por</th>
                                <th>Tipo</th>
                                <th>Nombre Reserva</th>
                                <th>Nombre Cliente</th>
                                <th>Tipo A.</th>
                                <th>Dias E.</th>
                                <th>C. PAX</th>
                                <th>Monto</th>
<!--                                <th>C PAX.</th>-->
<!--                                <th>Reserva A?</th>-->
<!--                                <th>#</th>-->
                            </tr>
                            </thead>

                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
