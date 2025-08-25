<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>


<div class="row">
    <div class="col-12">
        <div class="card">

            <div class="card-header d-sm-flex d-block shadow-sm">
                <div>
                    <h4 class="fs-20 mb-0 font-w600 text-black mb-sm-0 mb-2">Lista de Paquetes</h4>
                </div>

                <a href="javascript:void(0)" onclick='$(this).forms_modal({"page" : "package","title" : "Paquetes"})' class="plus-icon"><i class="fa fa-plus" aria-hidden="true"></i></a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="display w-100 text-center dataTable_es">
                        <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Precio</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            if(isset($datas)){
                                foreach($datas as $data){

                                    $status     = $this->class_security->array_data($data['p_status'],$this->class_data->status,$this->class_data->status_default);

                                    echo "<tr>";
                                    echo "<td>{$data['p_title']}</td>";
                                    echo "<td>{$data['p_price']}</td>";
                                    echo "<td><button class='{$status['class']}'>{$status['title']}</button></td>";
                                    echo "<td>";
                                    echo "<a href='javascript:void(0)' onclick='$(this).forms_modal({\"page\" : \"package\",\"title\" : \"Paquetes\",\"data1\" : \"{$data['p_id']}\"})' class='btn btn-info btn-sm rounded' ><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>";
                                    echo "<a href='javascript:void(0)' onclick='$(this).dell_data(\"{$data['p_id']}\",\"url_delete\")' class='btn btn-danger btn-sm rounded' ><i class='fa fa-trash-o' aria-hidden='true'></i></a>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
