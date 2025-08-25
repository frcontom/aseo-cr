<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

    <div class="d-flex justify-content-end mb-3">
            <a href="<?=base_url('events/proformas')?>"  class="btn btn-info rounded mx-2"> Proformas</a>
        <a href="<?=base_url('events/package')?>"  class="btn btn-info rounded  mx-2"> Paquete</a>
            <a href="javascript:void(0)" onclick='$(this).forms_modal({"page" : "events","title" : "Evento"})'   class="btn btn-primary rounded"><i class="fa fa-user me-2 " aria-hidden="true"></i> Crear Evento</a>
    </div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Lista de <?=$modulo?></h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tabla_data"  class="display w-100">
                        <thead>
                            <tr>
                                <th>Filial</th>
                                <th>Agente</th>
                                <th>Titulo Evento</th>
                                <th>Fecha Evento</th>
                                <th>Comensales</th>
                                <th>CP. Especiales</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
