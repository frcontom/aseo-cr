<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><?=$modulo?></h4>
                <a href='javascript:void(0)' onclick='$(this).forms_modal({"page" : "tasks","title" : "Tareas"})' class="plus-icon"><i class="fa fa-plus" aria-hidden="true"></i></a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tabla_data"  class="table text-center dataTable_es">
                        <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach($datas as $data){
                            $id = encriptar($data->tk_id);
                            echo "<tr>";
                            echo "<td>$data->tk_name</td>";
                            echo "<td>";
                            echo "<button type='button' onclick='$(this).forms_modal({\"page\" : \"tasks\",\"title\" : \"Editar Tarea\",\"data1\" : \"{$id}\"})' class='btn btn-info  mx-3'><i class='fa fa-pencil' aria-hidden='true'></i></button>";
                            echo "<button type='button' onclick='$(this).dell_data(\"$id\",\"url_delete\")' class='btn btn-danger'><i class='fa fa-trash' aria-hidden='true'></i></button>";
                            echo "</td>";
                            echo "</tr>";

                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
