<?php
defined('BASEPATH') or exit('No direct script access allowed');
$template = "";
//print_r($filials);
foreach($filials as $filial){
    $id = encriptar($filial->f_id);
    $img = (isset($filial->f_imagen) and strlen($filial->f_imagen) > 30) ? base_url("_files/filial/{$filial->f_imagen}") :  base_url('_files/filial/default.png');
    $avilable = $this->class_security->array_data($filial->f_available,$this->class_data->available);
    $fiObj = json_encode($filial);



    $color_font       =  $filial->s_color_f;
    $color_blocked    =  $filial->s_color;
    $border_blocked   =  $filial->s_color;
    $text_blocked     = '';
    $btn_disabled     = '';
    //not bother

    if($filial->f_status == 3 or $filial->f_status_actual == 14){
        //info blocked filial
        $color_font       =  '#fff';
        $color_blocked    =  '#336b87';
        $border_blocked   = '#336b87';
        $text_blocked     = " <a href='javascript:void(0)'  style='color:{$color_font} !important'  onclick='$(this).show_ban(\"{$filial->reason}\")'> - <i class='fas fa-ban'></i> BLOQUEADA</a>";
        $btn_disabled     = 'disabled';
    }


    //no molestar

    $bother =  $this->class_security->not_bother($filial->f_id,$filial->f_status);
    $template .= "<div id='filial-id-{$filial->f_id}' data-title='{$filial->f_name}' data-id='{$filial->f_id}' data-floor='{$filial->fr_id}'  class='col-xl-3 col-xxl-6 col-sm-6 filials'>
                <div class='card contact-bx'  style='border-color:{$filial->s_color};background: {$color_blocked} !important;color:{$color_font} !important'>
                    <div class='card-body'>
                        <div class='media'>
                            <div class='media-body'>
                                <h6 class='fs-16 font-w600 mb-0'  style='color:{$color_font} !important'>{$filial->f_name} {$text_blocked}</h6>
                                <p class='fs-14 mb-0'>
                                <b>{$filial->s_name} </b><br>
                                <div>{$bother}</div>
                                <span class='status' style='color:{$filial->s_color}'>Estado Actual:  {$filial->s_name}</span>
                                </p>
                                <div class='row icons'>
                                    ".$this->load->view('admin/assigmment/v_buttons', ['filial' => $filial,'status' => $status,'status_filial' => $filial->a_status_service,'disabled' => $btn_disabled],true)."
                                </div>
                            </div>
                        </div>
                         <div class='row mt-2'>
                            <div class='col btn-group'>
                            ";

                            $template .= "<button class='btn btn-sm btn-primary w-50' {$btn_disabled} onclick='$(this).forms_modal({\"page\" : \"comment_view_filial\",\"data1\" : \"{$id}\",\"title\" : \"Comentarios\"})'><i class='fas fa-comment'></i> Comentario</button>";
//                            $template .= "<button class='btn btn-sm btn-primary w-50' onclick='$(this).assigne_comment($fiObj)' >Mi Comentario</button>";
                            if($filial->a_status_service != '' || $filial->a_status_service != null):


                                if($filial->a_status_service == 1):
                                    $template .= '<button class="btn btn-sm btn-danger w-50  disabled"><i class="fas fa-ban"></i> Cola de limpieza</button>';
                                    else:
                                     $template .= '<button class="btn btn-sm  w-50 btn-warning disabled"><i class="fas fa-check"></i> Limpieza en Progreso</button>';
                                endif;

                                 else:
                                    if($filial->f_status_actual == 5):
                                        $template .=   "<button type='button'  class='btn btn-sm w-100 btn-danger rounded-xl  disabled'>Bloqueado no molestar</button>";

                                    elseif($filial->f_available == 2):
                                        $template .=   "<button type='button' onclick='$(this).assigne_status($fiObj,1)' class='btn btn-sm w-50 btn-danger  {$btn_disabled}'>Check Out</button>";
                                    else:
                                        $template .=   "<a  onclick='$(this).assigne_status($fiObj,2)' class='btn btn-sm w-50 btn-success  {$btn_disabled}'>Check In</a>";
                                    endif;
                           endif;


    $template .= "</div></div>
                    </div>
                </div>
    </div>";
}
echo $template;
?>

