<?php
defined('BASEPATH') or exit('No direct script access allowed');
foreach($filials as $filial){
//    $statusQ = $filial['f_available'];
    $statusQ =  $filial['status'];
    $avilable = $this->class_security->array_data($filial['f_available'],$this->class_data->available);
    $is_dirty = ($filial['m_complete'] == 2) ?  'Tiempo Restante: ' .$this->class_security->countdown(fecha(2),$this->class_security->sumar_minutos($filial['m_take'],$filial['m_time'])) : '';
    $fiObj = json_encode($filial);

    echo "<div data-title='{$filial["f_name"]}'   class='col-xl-2 col-xxl-6 col-sm-6 filials'>
                <div class='card contact-bx'  style='border-color:{$filial["s_color"]}'>
                    <div class='card-body'>
                        <div class='media'>
                            <div class='media-body'>
                                <h6 class='fs-16 font-w600 mb-0'><a href='javascript:void(0)' class='text-black'>{$filial["f_name"]}</a></h6>
                                <p class='fs-14 mb-0'>
                                <b>{$avilable['title']}</b><br>
                                <span class='status' style='color:{$filial["s_color"]}'>{$filial["s_name"]}</span><br>
                                Tiempo Tarea: {$filial['m_time']} Min <br>
                                <b>{$is_dirty}</b>
                                </p> <div class='row icons'>
                                 <div class='col mt-2'>".(($filial['m_complete'] == 2) ? "<button type='button' onclick='$(this).change_status($fiObj,2)' class='btn btn-sm w-100 btn-success rounded-xl'>Terminar Tarea</button>" : "<a  onclick='$(this).change_status($fiObj,1)' class='btn btn-sm w-100 btn-danger rounded-xl'>Iniciar Tarea</a>" )."</div>

                                 </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>";

}
?>

