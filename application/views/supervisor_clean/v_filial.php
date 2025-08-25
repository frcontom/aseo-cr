<?php
defined('BASEPATH') or exit('No direct script access allowed');
$template = '';

//print_r($filials);
foreach($filials as $filial){
    $avilable = $this->class_security->array_data($filial['f_available'],$this->class_data->available);
    $fiObj = json_encode([
        'id' => $filial['f_id'],
        'name' => $filial['f_name'],
        'type' => 1,
        'status' => $filial['s_id'],
        'hour' => $filial['s_time'],
        'user' => $filial['f_user']

    ]);

    $color_font       =  $filial['s_color_f'];
    $color_font       =  $filial['s_color_f'];
    $color_blocked    =  $filial['s_color'];
    $border_blocked   = $filial['s_color'];
    $text_blocked     = '';
    if($filial['f_status'] == 3){
        //info blocked filial
        $color_font       =  '#fff';
        $color_blocked    =  '#336b87';
        $border_blocked   = '#336b87';
        $text_blocked     = " <a href='javascript:void(0)'  style='color:{$color_font} !important'  onclick='$(this).show_ban(\"{$filial['reason']}\")'> - <i class='fas fa-ban'></i> BLOQUEADA</a>";
    }else{

    }


    $id = encriptar($filial['f_id']);
    $a_id = encriptar($filial['a_id'] ?? 0);
    //info blocked filial
    $comment_color =  ($filial['hc_comment'] == '' or $filial['hc_comment'] == null) ? 'btn-dark' : 'btn-primary  btn_effects';
    $title_blocked  = ($filial['f_status'] == 3) ?  'Desbl' : 'Bloq';
    $icon_blocked   = ($filial['f_status'] == 3)  ?  'fas fa-unlock' : 'fas fa-lock';
    $status_blocked = ($filial['f_status'] != 3)  ?  1 : 2;
    $btn_blocked    = ($filial['f_status'] == 3)  ?  'disabled' : '';
    $btn_close_blocked = (in_array($filial['f_status_actual'],[3,4,7,13])) ? '' : 'disabled';

    $bother_status = ($filial['f_status'] == 4)  ?  'disabled' : '';

    $bother =  $this->class_security->not_bother($filial['f_id'],$filial['f_status']);

    $template .= "<div id='filial-id-{$filial["f_id"]}' data-title='{$filial["f_name"]}' data-id='{$filial["f_id"]}'  data-floor='{$filial["fr_id"]}' class=' col-xxl-3 col-xl-2 col-sm-6 '>
                <div class='card contact-bx  {$border_blocked}' style='background: {$color_blocked};border: 1px solid {$border_blocked};color: {$color_font} !important;'>
                    <div class='card-body px-3 '>
                        <div class='media'>
                            <div class='media-body'>
                                <h6 class='fs-16 font-w600' style='color: {$color_font} !important;'>{$filial["f_name"]} {$text_blocked}</h6>
                                <p class='fs-14 mb-0'>
                                <b>{$filial["s_name"]}</b><br>
                                 <div> {$bother}</div>
                                </p>
                               
                            </div>
                        </div>
                        ";

         if(in_array($filial["a_status_service"],[2])){
            $template .= "<div class='mt-2'>
                            <button type='button' class='btn btn-danger btn-sm w-100  {$btn_blocked}'>Tarea en Ejecucición</button>
                        </div>";
        }
        else if(in_array($filial["f_status_actual"],[5])){
            $template .= "<div class='mt-2'>
                            <button type='button' onclick='$(this).filial_bloqueada(\"{$filial['f_id']}\",\"{$filial['f_name']}\")' class='btn btn-danger btn-sm w-100  {$btn_blocked}'>Pasar a Sucia</button>
                        </div>";
        }
        else if(in_array($filial["f_status_actual"],[1])){
            $template .= "<div class='d-flex  justify-content-between mt-3 text-cente'>
                                    <button type='button'  class=' btn btn-info btn-sm w-50 mx-1 disabled'>Asignado</button>
                                    <button type='button'  class='btn btn-warning btn-sm w-50 disabled'>Re-Asignar</button>
                        
                        </div>";
        }

        //la filial la tiene otro operador que debo hacer en este caso ?
        else if($filial['f_user'] != $filial['user_assigne']){

            //validar si la filial se puede trabajar porque la tiene otro operador validar si puede ser en revision



            $template .= "<div class='d-flex  justify-content-between mt-3 text-cente'>";
            $template .= " <button type='button'  class=' btn btn-info btn-sm w-50 mx-1' onclick='$(this).forms_modal({\"page\" : \"revision_filial\",\"data1\" : \"{$id}\",\"title\" : \"Finalizar Revision\"})'>Revisión1</button>";
            $template .= "<button type='button' onclick='$(this).filial_asginar({$fiObj},2)'  class='btn btn-warning btn-sm w-50  {$btn_blocked}'>Re-Asignar</button>";
            $template .= "</div>";
        }else if($filial['a_id'] != null){

            //validar el status ya que no siempre debe aparecer el boton de revision


            //validar si se muestra el boton de revision o asignado
            if($filial['a_revision_status'] == 1 and $filial['a_status_service'] == 3){
                $btn_revision_assigned = " <button type='button'  class=' btn btn-info btn-sm w-50 mx-1' onclick='$(this).forms_modal({\"page\" : \"revision_filial\",\"data1\" : \"{$id}\",\"title\" : \"Finalizar Revision\"})'>Revisión2</button>";
            }else{
                $btn_revision_assigned = " <button type='button'  class=' btn btn-info btn-sm w-50 mx-1 disabled'>Asignado</button>";
            }


            $template .= "<div class='d-flex  justify-content-between mt-3 text-center'>
                          {$btn_revision_assigned}
                           <button type='button' onclick='$(this).filial_asginar({$fiObj},2)'  class='btn btn-warning btn-sm w-50  {$btn_blocked}'>Re-Asignar</button>
                        </div>";
        }
        else{
            $template .= "<div class='d-flex  justify-content-between mt-3 text-center'>
                            <!--button type='button' onclick='$(this).filial_asginar({$fiObj},1)'  class=' btn btn-success btn-sm w-50 mx-1 {$btn_blocked}  {$bother_status}'>Por asignar</button-->
                            <button type='button' onclick='$(this).filial_asginar({$fiObj},2)'  class='btn btn-warning btn-sm w-100 {$btn_blocked} {$bother_status}'>Re-Asignar</button>
                        </div>
                        ";
        }


        $template  .= " <div class='d-flex  justify-content-between mt-2 text-center'>
                            <button type='button' onclick='$(this).forms_modal({\"page\" : \"whatsapp\",\"title\" : \"Comunicación Whatsapp\"})' class=' btn btn-warning btn-sm w-30 mx-1'> <i class='fas fa-message'></i></button>
                            <button type='button' onclick='$(this).forms_modal({\"page\" : \"comment_view_filial\",\"data1\" : \"{$id}\",\"title\" : \"Comentarios\"})' title='commentario' class=' btn {$comment_color} btn-sm w-30 mx-1'> <i class='fas fa-comment'></i></button>
                            <button type='button' onclick='$(this).filial_close(\"$id\",\"$a_id\",\"{$filial['f_name']}\")' title='Limpiar Filial' class=' btn btn-light btn-sm w-30 mx-1 {$btn_close_blocked}  {$btn_blocked}'><i class='fas fa-broom'></i></button>
                            <button type='button' onclick='$(this).filial_blocked({$fiObj},{$status_blocked})'  class=' btn btn-danger btn-sm w-100 mx-1  {$bother_status}'> <i class='{$icon_blocked}'></i> {$title_blocked}</button>
                        </div> ";

        $template  .= "</div>
                   
                </div>
            </div>";
}
echo $template;
?>

