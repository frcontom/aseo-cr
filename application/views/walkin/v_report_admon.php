<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>


    <?php
//        if(isset($sellers)):
//            foreach($sellers as $seller):
    ?>
<div class="row">
    <div class="col-12">
        <div class="card">

            <div class="card-header d-sm-flex d-block shadow-sm">
                <div>
                    <h4 class="fs-20 mb-0 font-w600 text-black mb-sm-0 mb-2">Reporte de ventas</h4>
                </div>

                <a href="javascript:void(0)" onclick='$(this).forms_modal({"page" : "walkin_download","title" : "Descargar reporte de ventas"})' class="plus-icon"><i class="fa fa-download" aria-hidden="true"></i></a>


            </div>

            <div class="card-body">
                <div class="table-responsive" id="tablesContainer">
                    <table class="display w-100 text-center datatable_ajax">
                        <thead>
                            <tr>
                                <th>Vendedor</th>
                                <th>Huesped</th>
                                <th>Ingreso</th>
                                <th>Salida</th>
                                <th>Tarifa</th>
                                <th>Monto</th>
                                <th>Porcentaje</th>
                                <th>Comisión</th>
                                <th>N. reserva</th>
                                <th>Tipo</th>
                                <th>Accion</th>
                                <th>Editar</th>
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
    <?php
//            endforeach;
//        endif;
    ?>