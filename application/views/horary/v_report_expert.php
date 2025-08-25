<?php
$template = '';
$daysMers = array_merge(...array_values($days));
$sizeDays = count($daysMers);
//print_r($days);
if(isset($data) and count($data) > 0){

    $calcule = [];
    $sumEmploy = 0;
    foreach($data as $d){
        $employ_id = $d['employ_id'];
        $template .= '<div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                    <div class="table-responsive">
                                        <div class="table-responsive card-table rounded table-hover fs-14">
                                            <table class="table table-bordered display mb-4  text-center" id="datatable_ajax_export">
                                    ';


        //header
        $template .= '<thead>';
        $template .= '<tr>';
        $template .= '<th colspan="'.$sizeDays+2 .'" class="text-left">'.strtoupper($d['employ_name']).'</th>';
        $template .= '</tr>';
        $template .= '</thead>';


        //BODY
        $template .= '<tbody>';

        //header tipo jornada / fecha. dia / total
        $template .= '<tr>';
        $template .= '<td rowspan="3">TIPO DE JORNADA</td>';
        $template .= '<td colspan="'.$sizeDays.'">FECHA/DIA</td>';
        $template .= '<td rowspan="3">TOTAL</td>';
        $template .= '</tr>';

        //dias jornada
        $template .= '<tr>';
        foreach($days As $day_k =>  $day):
            $count_q = count($day);
            $template .= "<td colspan='{$count_q}'>{$day_k}</td>";

        endforeach;
        $template .= '</tr>';

        $template .= '<tr>';
        foreach($daysMers As $fecha):
            $day = (new DateTime($fecha))->format('d');
            $template .= "<td>{$day}</td>";
        endforeach;
        $template .= '</tr>';


        //Types Elements


        foreach($typesHours As $types_key =>  $types):
            $template .= '<tr>';

            if(!in_array($types_key, [99])):
                $template .= "<td>{$types['descripcion']}</td>";


                //validate exist element
                if(!isset($calcule[$types_key])){
                    $calcule[$types_key] = 0;
                }


                foreach($daysMers As $day):

                    if(!isset($d['dates'][$day]['estado'])){
                        //Existe informacion estos dias

                        //validar siel tipo y el dia tienen informacion sin importar
                        $dayElement = $d['dates'][$day];
    //                    print_r($dayElement);
                        //validar cuantas horas existen si es 1 o varias
                        $tipo_hora_id_a_validar = $types_key;
                        $fecha_a_validar = $day;

    //                    print_r($dayElement);

                        $template .= "<td>";
                        foreach($dayElement As $elemenet){

                            if($elemenet['tipo_hora_id'] != '' and $elemenet['fecha'] == $fecha_a_validar){ // aplicamos que si no tiene un tipo_hora_id es por alguna razon
                                $tid = $elemenet['tipo_hora_id'];
                                $tih = $elemenet['bt_type_houry'];

                                if($elemenet['tipo_hora_id'] == $types_key){

                                    if($elemenet['bt_type_houry'] == 2){
//                                        $pago = ($elemenet['minutos_trabajados'] / 30) * 0.5;

                                        $minutosRedondeados = floor($elemenet['minutos_trabajados'] / 30) * 30;

                                        // Calcular el resultado basado en los 30 minutos
                                        $pago = ($minutosRedondeados / 30) * 0.5;


                                        $calcule[$types_key] += $pago;
    //                                    print_r($elemenet);
                                        $template .= "{$pago}";

                                    }else{
                                        $template .= "";

                                    }

                                }else{
                                    $template .= "";
                                }

    //                            $template .= "{$types_key} -- {$tid} - {$tih}<br>";

    //
    //
    //
    //
    //                            if($elemenet['tipo_hora_id'] == $tipo_hora_id_a_validar and  $elemenet['fecha'] == $fecha_a_validar){
    //                                print_r($elemenet);
    //
    ////                                //elementos 2 de horas extras
    //                                $template .= "<td>SI</td>";
    //                            }else{
    ////                                //elementos 1 de horario normal
    //                                $template .= "<td>{$tid} - {$tih}</td>";
    //                                break;
    //                            }

    //
    //                            $template .= "<td>{$tid} - {$tih}</td>";
    ////
    ////                            if($elemenet['bt_type_houry'] == 2){
    //
    //////                                if($elemenet['tipo_hora_id'] == $tipo_hora_id_a_validar){
    //////                                    $template .= "<td>SI</td>";
    //                                    break;
    //////                                }else{
    //////                                    $template .= "<td>NO1</td>";
    //////                                    break;
    //////                                }
    ////                            }else{
    ////                                $template .= "<td>-X-</td>";
    ////                                break;
    ////                            }
    //
    //
                            }else{
                                if($types_key == 1){
                                    $hora_tipo = $elemenet['prefix'];
                                    $template .= "{$hora_tipo}";

                                }else{
                                    $template .= ""; //no se muestra ya que solo quiero el primer elemento en el div

                                }
                            }

                        }
                        $template .= "</td>";


                        //validar cuantos registros existen



                        //validar si el el type existe para ese dia y tambien
    //                    $registros_filtrados = array_filter($dayElement, function($item) use ($tipo_hora_id_a_validar, $fecha_a_validar) {
    //                        return $item['tipo_hora_id'] == '' && $item['fecha'] == $fecha_a_validar;
    //                    });



                    }else{
                        //No existe informacion esos dias
                        $template .= "<td>-</td>";
                    }

                endforeach;

                $sumEmploy += $calcule[$types_key];
                $template .= "<td>{$calcule[$types_key]}</td>";




            endif;
            $template .= '</tr>';
            $calcule = [];
        endforeach;


        $template .= ' </tbody> ';

        //generate button code
        $btn_download = encriptar(json_encode([
            'employ_id' => $d['employ_id'],
            'date1' => $date1,
            'date2' => $date2,
        ]));

        if($sumEmploy > 0) {
        //footer
            $template .= '<tfoot>';
            $template .= '<tr>';
            $template .= '<td colspan="'.($sizeDays+1).'"></td>';

                $template .= "<td><a href='javascript:void(0)' onclick='$(this).forms_modal({\"page\" : \"horary_report_validation\",\"data1\" : \"{$employ_id}\",\"data2\" : \"{$date1}\",\"data3\" : \"{$date2}\",\"title\" : \"Validación de horarios\"})'  class='btn btn-danger btn-sm btn-block'>Verificar <i class='fas fa-user-check'></i>";
                $template .= '<a href="' . base_url('/horary/report_employ_pdf/' . $btn_download) . '" class="btn btn-primary btn-sm btn-block">Descargar <i class="fas fa-file-pdf"></i></td>';

            $template .= '</tr>';
            $template .= '</tfoot>';
        }

        $template .= '      
                                    </table>
                                  </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>';
        $sumEmploy = 0;

    }


}


echo $template;