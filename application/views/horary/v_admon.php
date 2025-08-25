<?php
defined('BASEPATH') or exit('No direct script access allowed');
$CI=&get_instance();
$date_old   = encriptar($CI->class_security->obtenerSemana($dayq,-1));
$date_today = encriptar($CI->class_security->obtenerSemana(date('Y-m-d')));
$date_new   = encriptar($CI->class_security->obtenerSemana($dayq,1));
?>

<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Colaboradores</h4>

                <nav>
                    <ul class="pagination pagination-gutter pagination-primary no-bg">
                        <li class="page-item page-indicator">
                            <a class="page-link" href="<?=base_url("horary/admon_horary/{$date_old}")?>">
                                <i class="la la-angle-left"></i></a>
                        </li>

                        <li class="page-item page-indicator">
                            <a class="page-link" href="<?=base_url("horary/admon_horary/{$date_today}")?>">
                                <i class="la la-calendar"></i></a>
                        </li>

                        <li class="page-item page-indicator">
                            <a class="page-link" href="<?=base_url("horary/admon_horary/{$date_new}")?>">
                                <i class="la la-angle-right"></i></a>
                        </li>
                    </ul>
                </nav>

                <a href='javascript:void(0)' onclick='$(this).forms_modal({"page" : "horary_schedules","title" : "Horarios"})' class="plus-icon"><i class="fa fa-calendar" aria-hidden="true"></i></a>
            </div>
            <div class="card-body" id="imgWhatsapp" style="background: white">
                <div class="table-responsive">
                    <table  class="table table-bordered table-responsive-xl"  style="background: white">
                        <thead>
                        <tr  class='bg-gray-dark text-white'>
                            <th scope="col">SEMANA</th>
                            <?php
                                foreach($days AS $day){
                                    $order_d = $this->class_security->format_date($day['fecha'],'d-m-y');
                                    echo "<th scope='col' class='text-center'>".$day['dia'].' '.$order_d."</th>";
                                }
                            ?>
                        </tr>

                            <?php
                            foreach($this->class_data->horary_ocupation AS $horary_id => $horary){
                                echo " <tr>";
                                echo "<th scope='col' style='vertical-align: middle;'><span class='h5'>$horary</span></th>";
                                foreach($ocupation AS $day){
                                    switch ($horary_id) {
                                        case 1:
                                            $column = 'heo_ocupation';
                                            break;
                                        case 2:
                                            $column = 'heo_entry';
                                            break;
                                        case 3:
                                            $column = 'heo_exit';
                                            break;
                                    }
                                    $dataOcupation = $day[$column];
                                    $jsondata = json_encode(['type' => $horary_id,'day' => $day['heo_date']]);
                                    echo "<th><input type='text' class='form-control form-control-sm text-center'  onkeyup='$(this).changeDayOcupation({$jsondata})' value='{$dataOcupation}'></th>";
                                }


                                echo "</tr>";
                            }

                            ?>

                        </thead>
                        <tbody>


                        <?php
                        foreach($datas As $category){
                            echo "<tr>";
                            echo "<td colspan='8' class='h4' style='background: #EEEEEE'>".$category['cat_name']."</td>";
                            echo "</tr>";

                            foreach($category['users'] as $user){
                                echo "<tr>";
                                echo "<td  class='font-weight-bold''>".$user['name']."</td>";

                                foreach($user['dates'] AS $day){
                                    echo "<td>";
                                    echo  $CI->selectOrday($schedules,$user['id'],$day);
                                    echo "</td>";
                                }


                                echo "</tr>";
                            }

                        }

                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer d-sm-flex justify-content-between align-items-center">
                <div class="card-footer-link mb-4 mb-sm-0">
                </div>

                <a href="javascript:void(0);" onclick="$(this).createSendwhatsapp(false)" class="btn btn-primary"><i class="fas fa-message"></i> Enviar Horarios</a>
            </div>
        </div>
    </div>
</div>
