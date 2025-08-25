<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><?=$modulo?></h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="display w-100 dataTable_es text-center">
                        <thead>
                        <tr>
                            <th>Filial</th>
                            <th>Usuario</th>
                            <th>Fecha Asignación</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Finalización</th>
                            <th>Tiempo</th>
                            <th>Estado</th>
                            <th>#</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if(isset($datas)){
                            foreach($datas As $data){
                                $id = encriptar($data->a_id);
                                $status     = $this->class_data->cleaning[$data->a_status_service];

                                $timerT = $this->timer->realtime($data->a_take,$data->a_ending);
                                echo "<tr>";
                                echo "<td>{$data->f_name}</td>";
                                echo "<td>{$data->u_name}</td>";
//                                echo "<td>{$data->a_code}</td>";
                                echo "<td>{$data->a_atcreate}</td>";
                                echo "<td>{$data->a_take}</td>";
                                echo "<td>{$data->a_ending}</td>";
                                echo "<td>{$timerT}</td>";
                                echo "<td><span class='{$status['class']}'>{$status['title']}</span></td>";
                                echo "<td><button  type='button' onclick='$(this).forms_modal({\"page\" : \"maintance_result\",\"data1\" : \"{$id}\",\"title\" : \"Detalle de Actividad\"})'  class='btn btn-primary'><i class='fas fa-eye'></i></button></td>";
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
