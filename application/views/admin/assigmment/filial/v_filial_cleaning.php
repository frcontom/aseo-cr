<?php
defined('BASEPATH') or exit('No direct script access allowed');
foreach($filials as $filial){
    $img = (isset($filial->f_imagen) and strlen($filial->f_imagen) > 30) ? base_url("_files/filial/{$filial->f_imagen}") :  base_url('_files/filial/default.png');
    $avilable = $this->class_security->array_data($filial->f_available,$this->class_data->available);
    $cleaning = $this->class_security->array_data($filial->a_status_service,$this->class_data->cleaning);
    $is_dirty = ($filial->a_status_service == 1) ? $timer->realtime($filial->a_assigner) : $timer->realtime($filial->a_take);
    echo "<div data-title='{$filial->f_name}' data-floor='{$filial->fr_id}' class='col-xl-3 col-xxl-3 col-sm-6 filials'>
                <div class='card contact-bx'>
                    <div class='card-body'>
                        <div class='media'>
                           
                            <div class='media-body'>
                                <h6 class='fs-16 font-w600 mb-0'><a href='javascript:void(0)' class='text-black'>{$filial->f_name}</a></h6>
                                <p class='fs-14 mb-0'>
                                <b>{$avilable['title']}</b><br>
                                <b>{$filial->s_name}</b><br>
                                <b>{$filial->u_name}</b><br>
                                Estado: {$cleaning['title']}<br>
                                <b class='text-black-50' style='color:{$cleaning['color']} !important;'>{$is_dirty}</b></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>";
}
?>

