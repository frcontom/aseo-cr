<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="content-body" style="padding-top: 0;margin-left: 0">
    <!-- row -->
    <div class="container-fluid">

        <div class="row">

            <?php
            if(isset($datas) and count($datas) >= 1){
                foreach($datas as $box){
            ?>


            <div class="col-xl-4 col-xxl-4 col-lg-4">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card profile-card <?=($box['bd_type'] == 2) ? 'bg-danger' : 'bg-warning';?> text-white">

                            <div class="card-header border-0 pb-0 text-center">
                            <?php
                            if($box['bd_type'] == 2):
                                echo '<h1 class="card-title text-black ">Entrega Hoy</h1>';
                            else:
                                echo '<h1 class="card-title text-black ">Entrega Hoy a las 10 de la noche</h1>';
                            endif;
                                ?>

                                <br>
                            </div>
                                <?php
                                    if($box['bd_type'] == 2):
                                ?>
                                    <div class="card-body text-center mt-2" style="padding: 0.375rem">
                                        <h1><div data-countdown="<?=$box['bd_day']?> <?=$this->class_security->format_date($box['bd_houri'],"G:i")?>"></div></h1>
                                    </div>
                                <?php
                                else:        
                                ?>
                                    <div class="card-body text-center" style="padding: 0.375rem">
                                        <h1><div data-countdown="<?=fecha(1)?> 22:00"></div></h1>
                                    </div>
                                <?php
                                endif;
                                ?>


                            <div class="card-body text-center" style="padding: 0.375rem">
                                <h2 class="fs-40 mb-0 font-w600 text-black"><?=$this->class_data->typeBox[$box['bd_type']]?></h2>
                            </div>

                            <div class="card-body">
                                <ul class="list-group list-group-flush mb-4">
                                    <li class="list-group-item d-flex px-0 justify-content-between">
                                        <strong class="text-black" style="margin-right: 20px;">Nombre del evento</strong>
                                        <span class="mb-0 text-black"><?=$box['b_event_name']?></span>
                                    </li>
                                    <li class="list-group-item d-flex px-0 justify-content-between">
                                        <strong class="text-black">Fecha del evento</strong>
                                        <span class="mb-0 text-black"><?=$box['bd_day']?></span>
                                    </li>
                                    <li class="list-group-item d-flex px-0 justify-content-between">
                                        <strong class="text-black">Hora del evento</strong>
                                        <span class="mb-0 text-black"><?=$this->class_security->format_date($box['bd_houri'],"g:i A")?></span>
                                    </li>


                                    <li class="list-group-item d-flex px-0 justify-content-between">
                                        <strong class="text-black" style="margin-right: 20px;">Cantidad</strong>
                                        <span class="mb-0 text-black" style=" text-align: right;"><?=$box['bd_account']?></span>
                                    </li>
                                </ul>

                                <div class="border-top pb-4 mt-4">
                                    <p class=" mt-2"><b class="text-black font-w600 me-auto">Observación</b></p>
                                    <p class="text-black"><?=$box['bd_description']?></p>
                                </div>

                                <div class="border-top pb-4 mt-4">
                                    <p class=" mt-2"><b class="text-black font-w600 me-auto">Observación Reserva</b></p>
                                    <p class="text-black"><?=$box['b_observation']?></p>
                                </div>

                                <div class="text-center">
                                    <a class="btn btn-primary btn-block btn-lg btn-rounded mt-3 px-5" href="javascript:void(0);" onclick="$(this).finish_box('<?=encriptar($box['b_id'])?>','<?=encriptar($box['bd_id'])?>','<?=($box['b_event_name'])?>')">FINALIZAR TAREA</a>
                                </div>



                            </div>




                            <div class="card-footer pt-0 pb-0 text-center d-none">
                                <div class="row">
                                    <div class="col-4 pt-3 pb-3 border-end">
                                        <h3 class="mb-1 text-primary"><?=$box['b_cpax_a']?></h3>
                                        <span>ADULTOS</span>
                                    </div>
                                    <div class="col-4 pt-3 pb-3 border-end">
                                        <h3 class="mb-1 text-primary"><?=$box['b_cpax_n']?></h3>
                                        <span>NIÑOS</span>
                                    </div>
                                    <div class="col-4 pt-3 pb-3">
                                        <h3 class="mb-1 text-primary"><?=$box['b_cpax']?></h3>
                                        <span>TOTAL</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <?php
                }
            }else{

                //Div indicar no tiene box
            }

            ?>

        </div>

    </div>

</div>