<?php
defined('BASEPATH') or exit('No direct script access allowed');
foreach($filials as $filial){
    $fiObj = json_encode($filial);
    $statusM = $this->class_security->array_data($filial['hma_status'],$this->class_data->cleaning,$this->class_data->estado_default);
    $user_a = (isset($filial['u_name']) and $filial['u_name'] != '') ? $filial['u_name'] : 'Sin Asignación';
    $id = encriptar($filial['m_id']);

    $is_dirty = "";
    if(isset($filial['hma_type']) and (isset($filial['hma_status'])) and ($filial['hma_type'] != "" and $filial['hma_type'] != null)){
        if($filial['hma_status'] != 1){
            $isTime = ($filial['hma_type'] == 1) ? $this->class_security->sumar_minutos($filial['hma_take'],$filial['hma_time'])  : $filial['hma_time'];
            $is_dirty = ($filial['hma_status'] == 2) ?  'Tiempo Restante: ' .$this->class_security->countdown(fecha(2),$isTime) : '';
        }
    }

    echo "<div data-title='{$filial["m_id"]}'   class='col-xl-2 col-xxl-3 col-sm-3  '>
                <div class='card contact-bx'  style='border-color:{$filial["s_color"]}'>
                    <div class='card-body'>
                        <div class='media'>
                            <div class='media-body'>
                              <h6 class='fs-16 font-w600 mb-0'><a href='javascript:void(0)' class='text-black'>{$filial['m_code']}</a></h6>
                                <p class='fs-14 mb-0'>
                                Asignado A. :  <b>{$filial['u_name']}</b><br>
                                Tipo de tarea : <b>{$statusM['title']}</b><br>
                                Estado: <b>{$statusM['title']}</b><br>
                                 <b>{$is_dirty}</b>
                                </p> <div class='row icons'>";


        echo "<div class='col mt-2'><button type='button' class='btn btn-warning btn-sm btn-block' onclick='$(this).forms_modal({\"page\" : \"task_log\",\"data1\" : \"{$id}\",\"title\" : \"Detalle de Actividad\"})'> Seguimiento</button></div>";


    echo   "                         </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>";

}
?>

