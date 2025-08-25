<?php
defined('BASEPATH') or exit('No direct script access allowed');
foreach($filials as $filial){

    $statusM = $this->class_security->array_data($filial['hma_status'],$this->class_data->working,$this->class_data->estado_default);
    $taskType = $this->class_security->array_data($filial['hma_type'],$this->class_data->type_maintenance_date,'Sin Asignación');
    $user_a = (isset($filial['u_name']) and $filial['u_name'] != '') ? $filial['u_name'] : 'Sin Asignación';
    $size_div = (strlen($size) == 0) ? 'col-xl-6  col-xxl-6  col-md-4 col-sm-4' : $size;
    $id = encriptar($filial['m_id']);
    $objD = json_encode([
        'm_code' => $filial['m_code'],
        'm_id' => encriptar($filial['m_id']),
        'hma_id' => ($filial['hma_id']),
    ]);

    echo "<div data-title='{$filial["m_id"]}'   class='{$size_div}  filials'>
                <div class='card contact-bx'  style='border-color:{$filial["s_color"]}'>
                    <div class='card-body'>
                        <div class='media'>
                            <div class='media-body'>
                              <h6 class='fs-16 font-w600 mb-0'><a href='javascript:void(0)' class='text-black'>{$filial['m_code']}</a></h6>
                                <p class='fs-14 mb-0'>
                               Creado X :  <b>{$filial['user_assigne']}</b><br>
                                Asignado A :  <b>{$filial['u_name']}</b><br>
                                Tipo de tarea : <b>{$taskType}</b><br>
                                Area : <b>{$filial['a_name']}</b><br>
                                Estado: <b>{$statusM['title']}</b><br>
                                </p> <div class='row icons'>";


                                    if($filial['hma_status'] == null and !(isset($filial['u_name']) and $filial['u_name'] != '')){
                                        echo "<div class='col mt-2 px-0 btn-group'>
                                                    <button type='button' class='btn btn-primary btn-sm btn-block' onclick='$(this).asignar_filial(\"{$id}\")'> Asignar</button>
                                              </div>";
                                    }else if($filial['hma_status'] == 2){
                                        echo "<div class='col mt-2 px-0 btn-group'>
                                                <button type='button' class='btn btn-danger btn-sm btn-block2' onclick='$(this).asignar_filial(\"{$id}\")'> Re-Asinar</button>
                                                <button type='button' class='btn btn-warning btn-sm btn-block2' onclick='$(this).forms_modal({\"page\" : \"task_log\",\"data1\" : \"{$id}\",\"title\" : \"Detalle de Actividad\"})'> Seguimiento</button>
                                              </div>";
                                    }else if($filial['hma_status'] == 3){
                                        echo "<div class='col mt-2 px-0 btn-group'>
                                                <button type='button' class='btn btn-danger btn-sm btn-block2' onclick='$(this).job_end({$objD})'> Terminar</button>
                                                <button type='button' class='btn btn-warning btn-sm btn-block2' onclick='$(this).forms_modal({\"page\" : \"task_log\",\"data1\" : \"{$id}\",\"title\" : \"Detalle de Actividad\"})'> Seguimiento</button>
                                              </div>";
                                    }
                                    else{
                                        echo "<div class='col mt-2 px-0 btn-group'><button type='button' class='btn btn-danger btn-sm btn-block2 disabled' disabled> Asignado</button>
                                                <button type='button' class='btn btn-warning btn-sm btn-block2' onclick='$(this).forms_modal({\"page\" : \"task_log\",\"data1\" : \"{$id}\",\"title\" : \"Detalle de Actividad\"})'> Seguimiento</button>
                                              </div>";

                                    }

    echo   "                         </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>";

}
?>

