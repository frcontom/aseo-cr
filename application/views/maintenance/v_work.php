<?php
defined('BASEPATH') or exit('No direct script access allowed');

foreach($filials as $filial){
    $statusQ =  $filial['m_status'];


    //validate type for timer
    if($filial['hma_status'] != 2){
        //time initial
        $isTime = 0;
        $is_dirty = '-';
    }else{
        //time work
        $isTime = ($filial['hma_type'] == 1) ? $this->class_security->sumar_minutos($filial['hma_take'],$filial['hma_time'])  : $filial['hma_time'];
        $is_dirty = ($filial['hma_status'] == 2) ?  'Tiempo Restante: ' .$this->class_security->countdown(fecha(2),$isTime) : '';
    }
    $typeTime = ($filial['hma_type'] == 1) ? ' Min' : '';

    $fiObj = json_encode([
        'm_id' => encriptar($filial['m_id']),
//        's_name' => $filial['s_name'],
//        'a_time' => $filial['a_time'],
    ]);

    $id = encriptar($filial['m_id']);
    $user_a = (isset($filial['u_name']) and $filial['u_name'] != '') ? $filial['u_name'] : 'Sin Asignación';

    echo "<div data-title='{$filial["m_id"]}'   class='col-xl-3 col-xxl-6 col-sm-6 filials'>
                <div class='card contact-bx'  style='border-color:{$filial["s_color"]}'>
                    <div class='card-body'>
                        <div class='media'>
                            <div class='media-body'>
                              <h6 class='fs-16 font-w600 mb-0'><a href='javascript:void(0)' class='text-black'>{$filial['m_code']}</a></h6>
                                <p class='fs-14 mb-0'>
                                <span class='status' style='color:{$filial["s_color"]}'>".substr($filial['m_observation'],0,30)."..</span><br>
                                Tiempo Finalizacion: {$filial['hma_time']} {$typeTime} <br>
                                <b>{$is_dirty}</b>
                                </p> <div class='row icons'>
                                 <div class='col mt-2'>".(($filial['hma_status'] == 2) ? "<button type='button' onclick='$(this).change_status($fiObj,2)' class='btn btn-sm w-100 btn-success rounded-xl'>Terminar Tarea</button>" : "<button  onclick='$(this).change_status($fiObj,1)' class='btn btn-sm w-100 btn-danger rounded-xl'>Iniciar Tarea</button>" )."</div>

                                 </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>";

}
?>

