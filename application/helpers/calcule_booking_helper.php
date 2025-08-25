<?php
defined('BASEPATH') or exit('No direct script access allowed');

 function calcule_rooms($datas = []){
    $total = 0;
    if(!empty($datas)){
         $calculo_final_subtotal = 0;
         $calculo_final_iva = 0;
         $calculo_final_total = 0;
        foreach($datas as $data){
            $pax    = $data->pfr_pax   ?? 0;
            $size   = $data->pfr_count ?? 0;
            $price  = $data->pfr_price ?? 0;
            $iva    = $data->pfr_iva   ?? 0;


            $subtotal_siniva    =  $pax * ($size * $price);
            $calculo_iva        = (($subtotal_siniva*$iva)/100);
            $calculo_total_iva  = $subtotal_siniva+(($subtotal_siniva*$iva)/100);


//            $calculo_final_subtotal += $subtotal_siniva;
//            $calculo_final_iva += $calculo_iva;
            $calculo_final_total += $calculo_total_iva;

        }


        $total =  $calculo_final_total;
    }
    return $total;
}
 function calcule_package($datas = []){
    $total = 0;
    if(!empty($datas)){
         $calculo_final_subtotal = 0;
         $calculo_final_iva = 0;
         $calculo_final_total = 0;
        foreach($datas as $data){
            $size   = $data->pfp_count ?? 0;
            $price  = $data->pfp_price ?? 0;


            $subtotal_siniva    =  ($size * $price);


//            $calculo_final_subtotal += $subtotal_siniva;
//            $calculo_final_iva += $calculo_iva;
            $calculo_final_total += $subtotal_siniva;

        }


        $total =  $calculo_final_total;
    }
    return $total;
}