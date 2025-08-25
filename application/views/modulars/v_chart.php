<?php
defined('BASEPATH') or exit('No direct script access allowed');

$sum1 = 0;
$sum2 = 0;
$sum3 = 0;
$calc = 0;
$calc2 = 0;

$data =  [];
$data[5] = 0;
$data[1] = 0;
$data[2] = 0;
$data[13] = 0;
$data[3] = 0;
$data[4] = 0;
$data[6] = 0;
$data[7] = 0;
$data[8] = 0;
if (isset($chart)):
foreach ($chart As $sumq){
//    $data[$sumq->s_id] = 0;

    if($sumq->f_status != 3){
        if(in_array($sumq->s_id,[1,13])){
            $data[$sumq->s_id] += 1;
            $sum1 += 1;
        }


        if(in_array($sumq->s_id,[2,3,4,6,7,8])){
            $data[$sumq->s_id] += 1;
            $sum2 += 1;
        }

    }else{
        $data[5] += 1;
        $sum3 += 1;
    }



//
//    if(in_array($sumq->s_id,[5])){
//        $sum3 += $sumq->cantidad;
//    }
}
endif;

$calc = round(($sum2/($sum1+$sum2))*100,2);
$calc2 = ($sum1+$sum2);

//print_r($data);
?>



<div class="row">

    <div class="col">
        <div class="card fun">
            <div class="card-body">
                <div class="media align-items-center">
                    <div class="media-body me-3">
                        <h2 class="num-text text-black font-w600"><?=$sum1?></h2>
                        <span class="fs-14">Habitaciones Libres</span>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col">
        <div class="card fun">
            <div class="card-body">
                <div class="media align-items-center">
                    <div class="media-body me-3">
                        <h2 class="num-text text-black font-w600"><?=$sum2?></h2>
                        <span class="fs-14">Ocupacion Actual</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card fun">
            <div class="card-body">
                <div class="media align-items-center">
                    <div class="media-body me-3">
                        <h2 class="num-text text-black font-w600"><?=$sum3?></h2>
                        <span class="fs-14">Habitaciones Bloqueadas</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card fun">
            <div class="card-body">
                <div class="media align-items-center">
                    <div class="media-body me-3">
                        <h2 class="num-text text-black font-w600"><?=$calc2?></h2>
                        <span class="fs-14">Habitaciones Disponibles</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card fun">
            <div class="card-body">
                <div class="media align-items-center">
                    <div class="media-body me-3">
                        <h2 class="num-text text-black font-w600"><?=$calc?>%</h2>
                        <span class="fs-14">Porcentaje Actual</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <?php
    if(isset($status)):
        foreach($status As $ch):
            ?>
            <div class="col-4">
                <div class="card fun">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="media-body me-3">
                                <h2 class="num-text text-black font-w600"><?=number_format($data[$ch->s_id])?></h2>
                                <span class="fs-14"><?=$ch->s_name?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        endforeach;
    endif;
    ?>


</div>



