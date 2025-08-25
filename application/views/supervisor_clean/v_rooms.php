<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><?=$modulo?></h4>

                <div class="d-lg-inline-flex">
                    <a href='javascript:void(0)' class="btn btn-rounded btn-outline-primary" onclick='$(this).forms_modal({"page" : "assignment_rooms_user","title" : "Asignacion de filiales"})' class="plus-icon"> <i class="fas fa-plus"></i> Asignar</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="display w-100 dataTable_es text-center">
                        <thead>
                        <tr>
                            <th>Piso</th>
                            <th>Nombre</th>
                            <th>código</th>
                            <th>Usuario L.</th>
                            <th>Estado</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if(isset($datas)){
                            foreach($datas As $data){
                                $id = encriptar($data->f_id);
                                $status     = $this->class_data->status[$data->f_status];
                                echo "<tr>";
                                echo "<td>{$data->fr_name}</td>";
                                echo "<td>{$data->f_name}</td>";
                                echo "<td>{$data->f_code}</td>";
                                echo "<td>{$data->u_name}</td>";
                                echo "<td><span class='{$status['class']}'>{$status['title']}</span></td>";
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
