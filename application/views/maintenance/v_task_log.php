<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="row  mt-4 pb-3 ">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><?=$modulo?></h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="table_log"  class="display w-100">
                        <thead>
                        <tr>
                            <th>código</th>
                            <th>Creado</th>
                            <th>Asignado</th>
                            <th>Fecha Creación</th>
                            <th>Fecha Asignación</th>
                            <th>Tiempo E.</th>
                            <th>Tiempo F.</th>
<!--                            <th>Fecha</th>-->
                            <th>Estado</th>
                            <th>#</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
