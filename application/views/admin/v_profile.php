<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><?=$modulo?></h4>
                <a href='javascript:void(0)' onclick='$(this).forms_modal({"page" : "profile","title" : "Perfil de usuario"})' class="plus-icon"><i class="fa fa-plus" aria-hidden="true"></i></a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="display w-100 dataTable_es text-center">
                        <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Usuarios</th>
                            <th>Modulos</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            foreach($datas As $data){
                                $id = encriptar($data->p_id);
                                $hide = ($data->total > 0) ? 'd-none' : '';
                                echo "<tr>";
                                echo "<td>".$data->p_name."</td>";
                                echo "<td>".$data->total."</td>";
                                echo "<td><button type='button' class='btn btn-primary btn-sm'  onclick='$(this).forms_modal({\"page\" : \"profile_module\",\"data1\" : \"{$id}\",\"data2\" : \"{$data->p_name}\",\"title\" : \"Perfil Modulos\"})'><i class='fas fa-box'></i></button></td>";
                                echo   "<td>
                 <button type='button' onclick='$(this).forms_modal({\"page\" : \"profile\",\"data1\" : \"{$id}\",\"title\" : \"Editar Perfil\"})' class='btn btn-primary  btn-sm'  data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-pencil text-white'></i></button>
                 <button type='button' onclick='$(this).dell_data(\"{$id}\",\"url_delete\")' class='btn btn-danger  btn-sm {$hide}'  data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-times text-white'></i></button></td>";

                            }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
