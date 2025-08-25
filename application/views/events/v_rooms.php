<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="row">
    <div class="col-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Habitaciones</h4>
                <a href='javascript:void(0)' onclick='$(this).forms_modal({"page" : "rooms","data1" : 1,"title" : "Habitaciones"})' class="plus-icon"><i class="fa fa-plus" aria-hidden="true"></i></a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tabla_data"  class="display w-100 text-center dataTable_es">
                        <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Color</th>
                            <th>Precio</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach($this->class_security->filter_all($datas,'r_type','1') as $room){
                            $id = encriptar($room['r_id']);
                            $type = $this->class_security->array_data($room['r_type'],$this->class_data->type_room);
                            echo "<tr>";
                            echo "<td>{$room['r_name']}</td>";
                            echo "<td>{$type}</td>";
                            echo "<td><i style='color:{$room['r_color']}' class='fas fa-home'></i></td>";
                            echo "<td>".number_format($room['r_price'])."</td>";
                            echo "<td>";
                            echo "<button type='button' onclick='$(this).forms_modal({\"page\" : \"rooms\",\"data1\" : \"{$id}\",\"title\" : \"Editar Habitación\"})' class='btn btn-primary btn-sm'  data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-pencil text-white'></i></button>
                                      <button type='button' onclick='$(this).dell_data(\"{$id}\",\"url_delete\")' class='btn btn-danger btn-sm'  data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-times text-white'></i></button>";
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

    <div class="col-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Salones</h4>
                <a href='javascript:void(0)' onclick='$(this).forms_modal({"page" : "rooms","data1" : 2,"title" : "Salones"})' class="plus-icon"><i class="fa fa-plus" aria-hidden="true"></i></a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="display w-100 text-center dataTable_es2">
                        <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Color</th>
                            <th>Precio</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach($this->class_security->filter_all($datas,'r_type','2') as $room){
                            $id = encriptar($room['r_id']);
                            $type = $this->class_security->array_data($room['r_type'],$this->class_data->type_room);
                            echo "<tr>";
                            echo "<td>{$room['r_name']}</td>";
                            echo "<td>{$type}</td>";
                            echo "<td><i style='color:{$room['r_color']}' class='fas fa-home'></i></td>";
                            echo "<td>".number_format($room['r_price'])."</td>";
                            echo "<td>";
                            echo "<button type='button' onclick='$(this).forms_modal({\"page\" : \"rooms\",\"data1\" : \"{$id}\",\"title\" : \"Editar Habitación\"})' class='btn btn-primary btn-sm'  data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-pencil text-white'></i></button>
                                      <button type='button' onclick='$(this).dell_data(\"{$id}\",\"url_delete\")' class='btn btn-danger btn-sm'  data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-times text-white'></i></button>";
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
