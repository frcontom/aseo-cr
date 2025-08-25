<?php
defined('BASEPATH') or exit('No direct script access allowed');

foreach($filials as $filial){
    $img = (isset($filial->f_imagen) and strlen($filial->f_imagen) > 30) ? base_url("_files/filial/{$filial->f_imagen}") :  base_url('_files/filial/default.png');
    $avilable = $this->class_security->array_data($filial->f_available,$this->class_data->available);
    $fiObj = json_encode($filial);
    echo "<div data-title='{$filial->f_name}' data-floor='{$filial->fr_id}' class='col-xl-3 col-xxl-6 col-sm-6 filials'>
                <div class='card contact-bx'>
                    <div class='card-body'>
                        <div class='media'>
                            <div class='image-bx mr-3'>
                                <img src='{$img}' alt='' class='rounded-circle' width='100' height='100'>
                                <span class='active' style='background:{$avilable['color']}'></span>
                            </div>
                            <div class='media-body'>
                                <h6 class='fs-16 font-w600 mb-0'><a href='javascript:void(0)' class='text-black'>{$filial->f_name}</a></h6>
                                <p class='fs-14 mb-0'>
                                <b>{$avilable['title']}</b><br>
                                <b>{$filial->s_name}</b><br>
                                <b>{$filial->u_name}</b><br>
                                Piso: {$filial->fr_name}
                                </p>
                                
                            </div>
                        </div>
                        <div class='row mt-2'>
                            <div class='col'><button type='button' onclick='$(this).revision_ready($fiObj)' class='btn btn-sm w-100 btn-success rounded-xl'>Finalizar Revisión</button></div>
                         </div>
                    </div>
                </div>
            </div>";
}
?>

