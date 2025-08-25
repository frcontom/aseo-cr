<?php
$all_status = $this->general->query("select * from status As s JOIN icons i on s.s_icon = i.i_id WHERE s.s_status=1 AND s.s_type = 2",'object',false);
$dataObj = json_encode($filial);

foreach($all_status As $status){
    $dataObj = json_encode(
        [
            'filial' => $filial,
            'status' => $status
        ]
    );
    if($status->s_clean == 2):
        echo "<div class='col-3 mb-1'><a  onclick='$(this).asignar_filial($dataObj)' data-toggle='tooltip' data-placement='top' title='{$status->s_name}' href='javascript:void(0)'><i class='{$status->i_icon}' style='color:$status->s_color' aria-hidden='true'></i></a></div>";
    endif;
}
