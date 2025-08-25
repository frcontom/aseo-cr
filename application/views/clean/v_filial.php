<?php
defined('BASEPATH') or exit('No direct script access allowed');
$volume  = array_column($filials, 'a_assigner');
array_multisort($volume, SORT_DESC, $filials);
//print_r($filials);
foreach($filials as $filial){
    $id  = encriptar($filial->f_id);
    $fiObj = json_encode($filial);
    $accordance = $this->class_security->array_data($filial->a_accordance,$this->class_data->accordance);

    $color_font       =  $filial->s_color_f;
    $color_font       =  $filial->s_color_f;
    $color_blocked    =  $filial->s_color;
    $border_blocked   =  $filial->s_color;

    //blocked filials not woking
    $blocked = ($filial->a_status_service != null or $filial->a_status_service != '') ? '' : 'disabled';
    $block_status_filial = ($filial->f_status == 3) ? 'disabled' : '';

   echo "<div class='col-xl-3 col-xxl-6 col-sm-6'  id='filial-id-{$filial->f_id}' data-title='{$filial->f_name}' data-id='{$filial->f_id}' >
                <div class='card contact-bx  {$border_blocked}' style='background: {$color_blocked};border: 1px solid {$border_blocked};color: {$color_font} !important;'>
                    <div class='card-body'>
                        <div class='media'>
                            <div class='media-body'>
                                <h6 class='fs-16 font-w600 mb-0'><a href='javascript:void(0)' class='text-black'>{$filial->f_name}</a></h6>
                                <p class='fs-14 mb-0'>Piso: {$filial->fr_name} <br>
                               Tiempo Tarea: {$filial->a_time} Min <br>
                                <i class='{$filial->i_icon}'  style='color: {$color_font} !important;'></i> Estado Actual : <b>{$filial->s_name}</b><br>
                                </p>
                                ".(($filial->a_status_service == 2) ? "<h5>{$this->class_security->countdown(fecha(2),$this->class_security->sumar_minutos($filial->a_take,$filial->a_time))}</h5>" : '')."
                            </div>
                        </div>
                        
                           <div class='row mt-2'>
                               <div class='col btn-group'>".(($filial->a_status_service == 2) ? "<button type='button'  onclick='$(this).forms_modal({\"page\" : \"comment_response_filial\",\"data1\" : \"{$id}\",\"title\" : \"Finalizar Tarea\"})' class='btn btn-sm w-100 btn-success rounded-xl'>Terminar Tarea</button>"
                                    :
                                        "
                                             <button  onclick='$(this).forms_modal({\"page\" : \"comment_response_filial\",\"data1\" : \"{$id}\",\"title\" : \"iniciar Tarea\"})' class='btn btn-sm w-100 btn-info mx-1  {$blocked} {$block_status_filial}'>Iniciar Tarea</button>
                                             <button  onclick='$(this).change_status_hand($fiObj,1)' class='btn btn-sm w-100 btn-danger mx-1  {$blocked} {$block_status_filial}'><i class='fas fa-hand'></i> No molestar</button>
                                             
                                             " )."</div>
                           </div>
                           
                           <div class='mt-2 d-flex  justify-content-between mt-2 text-center'>
                                  <button  onclick='$(this).clean_busy($fiObj)' class='btn btn-sm w-100 btn-dark mx-1  {$blocked} {$block_status_filial}'>Ocupada Limpia</button>
                           </div>
                   
                    </div>
                </div>
            </div>";
}

?>

