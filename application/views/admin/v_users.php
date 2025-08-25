<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><?=$modulo?></h4>
                <a href='javascript:void(0)' onclick='$(this).forms_modal({"page" : "users","title" : "Usuarios"})' class="plus-icon"><i class="fa fa-plus" aria-hidden="true"></i></a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tabla_data"  class="display w-100">
                        <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Usuario</th>
                            <th>Correo</th>
                            <th>Perfil</th>
                            <th>Permiso</th>
                            <th>Area</th>
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
