<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><?=$modulo?></h4>
                <a href='javascript:void(0)' onclick='$(this).forms_modal({"page" : "floor","title" : "<?=$modulo?>"})' class="plus-icon"><i class="fa fa-plus" aria-hidden="true"></i></a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id=""  class="display w-100 text-center dataTable_es">
                        <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            foreach($datas AS $data):
                                $id = encriptar($data->fr_id);
                                echo "<tr>";
                                echo "<td>{$data->fr_name}</td>";
                                echo "<td>";
                                echo "<a href='javascript:void(0)' onclick='$(this).forms_modal({\"page\" : \"floor\",\"title\" : \"{$modulo}\",\"data1\" : \"{$id}\"})' class='btn btn-sm btn-warning' title='Editar'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>";
                                echo "<a href='javascript:void(0)' onclick='$(this).dell_data(\"{$id}\",\"url_delete\")'' class='btn btn-sm btn-danger' title='Eliminar'><i class='fa fa-trash-o' aria-hidden='true'></i></a>";
                                echo "</td>";
                                echo "</tr>";
                            endforeach;
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
