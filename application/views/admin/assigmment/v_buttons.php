<?php
//$statusQ = (isset($status) and $status != '') ?   "" : '';
$all_status = $status;
$dataObj = json_encode($filial);
//$btn_disabled = ($status_filial != '' and $filial->f_status_actual != 13) ? "disabled" : "";
$btn_disabled =  "";
$disabled = ($disabled != '') ? "disabled" : "";
foreach($all_status As $status){
    $dataObj = json_encode(
        [
            'filial' => $filial,
            'status' => $status
        ]
    );
    echo "<div class='col-3 my-4 mb-1'><a  onclick='$(this).asignar_filial($dataObj)' aria-disabled='true'  class='{$btn_disabled} {$disabled}' data-toggle='tooltip' data-placement='top' title='{$status->s_name}' href='javascript:void(0)'><i class='{$status->i_icon} fs-1' style='color:$status->s_color'  aria-hidden='true'></i></a></div>";
}
