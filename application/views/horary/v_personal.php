<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="row">
    <div class="col-md-5 col-sm-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Departamentos</h4>
                <a href='javascript:void(0)' onclick='$(this).forms_modal({"page" : "horary_category","title" : "Departamentos"})' class="plus-icon"><i class="fa fa-plus" aria-hidden="true"></i></a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table  class="display dataTable_es w-100">
                        <thead>
                        <tr>
                            <th>Nombre</th>
<!--                            <th>Orden</th>-->
                            <th>administrador</th>
                            <th>Propietario</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach($dataCategory as $data){
                            $id = encriptar($data->hc_id);
                            echo "<tr>";
                            echo "<td>$data->hc_name</td>";
//                            echo "<td>$data->hc_order</td>";
                            echo "<td>$data->p_name</td>";
                            echo "<td>$data->pt_name</td>";
                            echo "<td><div class='btn-group'>";
                            echo "<button type='button' onclick='$(this).forms_modal({\"page\" : \"horary_category\",\"title\" : \"Departamentos\",\"data1\" : \"{$id}\"})' class='btn btn-info  btn-sm'><i class='fa fa-pencil' aria-hidden='true'></i></button>";
                            echo "<button type='button' onclick='$(this).dell_data(\"$id\",\"url_category_delete\")' class='btn btn-danger btn-sm'><i class='fa fa-trash' aria-hidden='true'></i></button>";
                            echo "</div></td>";
                            echo "</tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12  col-md-7">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Colaboradores</h4>
                <a href='javascript:void(0)' onclick='$(this).forms_modal({"page" : "horary_employ","title" : "Colaborador"})' class="plus-icon"><i class="fa fa-plus" aria-hidden="true"></i></a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table  class="display dataTable_es w-100">
                        <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>código</th>
                            <th>Télefono</th>
                            <th>Departamento</th>
                            <th>Estado</th>
                            <th>FaceID</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach($dataEmploy as $data){
                            $id = encriptar($data->he_id);
                            $status     = $this->class_data->statusSimple[$data->he_status];
                            $btn_color = ($data->faceid == 0) ? 'btn-success' : 'btn-dark';
                            echo "<tr>";
                            echo "<td>$data->he_name</td>";
                            echo "<td>$data->he_code</td>";
                            echo "<td>$data->he_phone</td>";
                            echo "<td><b>$data->hc_name</b></td>";
                            echo "<td><span class='{$status['class']}'>{$status['title']}</span</td>";
                            echo "<td><button type='button' onclick='$(this).forms_modal({\"page\" : \"horary_employ_faceid\",\"data1\" : \"{$id}\",\"title\" : \"Colaborador FaceID\"})' class='btn {$btn_color} btn-sm'><i class='fa fa-camera' aria-hidden='true'></i></button></td>";
                            echo "<td><div class='btn-group'>";
                            echo "<button type='button' onclick='$(this).forms_modal({\"page\" : \"horary_employ\",\"title\" : \"Colaborador\",\"data1\" : \"{$id}\"})' class='btn btn-info  btn-sm'><i class='fa fa-pencil' aria-hidden='true'></i></button>";
                            echo "<button type='button' onclick='$(this).dell_data(\"$id\",\"url_personal_delete\")' class='btn btn-danger btn-sm'><i class='fa fa-trash' aria-hidden='true'></i></button>";
                            echo "</div></td>";
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
