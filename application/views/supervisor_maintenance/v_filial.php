<?php
defined('BASEPATH') or exit('No direct script access allowed');
foreach($filials as $filial){
//    $statusQ = $filial['f_available'];
    $statusQ =  $filial['status'];
    $avilable = $this->class_security->array_data($filial['f_available'],$this->class_data->available);
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
                                </p> <div class='row icons'>";

                                    if($filial['s_clean'] == 1){
                                     echo    $this->load->view('supervisor_maintenance/v_buttons', ['filial' => $filial,'status' => $status],true);
                                    }
                                    elseif($filial['m_complete'] == 3){
                                        echo "<div class='col mt-2'><button type='button' class='btn btn-primary btn-sm btn-block' onclick='$(this).result_filial($fiObj)'><i class='fas fa-eye'></i> Resultado Asignacion</button></div>";
                                    }
                                    else{

                        //                echo "<div class='col mt-2'><button type='button' class='btn btn-primary btn-sm btn-block' onclick='$(this).asignar_filial($fiObj)'><i class='fas fa-eye'></i> Seguimiento</button></div>";
                                    }

    echo   "                         </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>";

}
?>

