<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>


<div class="row">
    <div class="col-12">
        <div class="card">

            <div class="card-header d-sm-flex d-block shadow-sm">
                <div>
                    <h4 class="fs-20 mb-0 font-w600 text-black mb-sm-0 mb-2">Lista de Vendedores</h4>
                </div>

                <a href="javascript:void(0)" onclick='$(this).forms_modal({"page" : "seller","title" : "Vendedor"})' class="plus-icon"><i class="fa fa-plus" aria-hidden="true"></i></a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="display w-100 text-center" id="tabla_data">
                        <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Estado</th>
                            <th>Acciones</th>
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
