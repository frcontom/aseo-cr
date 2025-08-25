<?php
defined('BASEPATH') or exit('No direct script access allowed');

foreach($filials as $filial){
    $img = (isset($filial->f_imagen) and strlen($filial->f_imagen) > 30) ? base_url("_files/filial/{$filial->f_imagen}") :  base_url('_files/filial/default.png');
    $avilable = $this->class_security->array_data($filial->f_available,$this->class_data->available);
    $is_dirty = ($filial->f_status_actual == 1) ? $timer->realtime($filial->f_dirty) : '';
        echo "<div data-title='{$filial->f_name}' data-floor='{$filial->fr_id}' class='col-xl-3 col-xxl-6 col-sm-6 filials'>
                <div class='card contact-bx'>
                    <div class='card-body'>
                        <div class='media'>
                            <div class='media-body'>
                                <h6 class='fs-16 font-w600 mb-0'><a href='javascript:void(0)' class='text-black'>{$filial->f_name}</a></h6>
                                <p class='fs-14 mb-0'>
                                <b>{$avilable['title']}</b><br>
                                <b>{$filial->s_name}</b><br>
                                <b>{$is_dirty}</b>
                                </p>
                                <ul class='justify-content-start'>
                                    ".$this->load->view('admin/assigmment/v_buttons', ['filial' => $filial],true)."
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>";
}
?>

