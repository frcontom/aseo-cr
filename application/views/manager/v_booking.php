<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><?=$modulo?></h4>
                <a href='javascript:void(0)' onclick='$(this).forms_modal({"page" : "booking","title" : "Reserva"})' class="plus-icon"><i class="fa fa-plus" aria-hidden="true"></i></a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <div class="table-responsive card-table rounded table-hover fs-14">
                        <table class="table border-no display mb-4 dataTablesCard project-bx dataTable_es" id="example5">
                            <thead>
                            <tr>
                                <th>Creado por</th>
                                <th>Codigo</th>
<!--                                <th>Salon</th>-->
                                <th>Nombre Evento</th>
                                <th>Nombre Cliente</th>
                                <th>Dias E.</th>
                                <th>Reserva A?</th>
                                <th>Tiempo R.</th>
                                <th>#</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach($results As $room):
                                $id = encriptar($room->b_id);
                            ?>
                            <tr>
                                <td>
                                    <div>
                                        <h4 class="title font-w600 mb-2 text-black"><?=$room->u_name?></h4>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <h4 class="title font-w600 mb-2 text-black"><?=$room->b_code?></h4>
                                    </div>
                                </td>
<!--                                <td>-->
<!--                                    <div>-->
<!--                                        <h4 class="title font-w600 mb-2 text-black">--><?php //=$room->r_name?><!--</h4>-->
<!--                                    </div>-->
<!--                                </td>-->
                                <td>
                                    <div>
                                        <h4 class="title font-w600 mb-2 text-black"><?=$room->b_name?></h4>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <span class="text-black font-w600 text-nowrap"><?=$room->b_user?></span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <span class="text-black font-w600 text-nowrap"><?=$room->b_day?></span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <span class="text-black font-w600 text-nowrap"><?=$this->class_data->bookingType[$room->b_type]?></span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <span class="text-black font-w600 text-nowrap"><div class="countdown"  data-countdown="<?=$this->class_security->sumar_hora($room->b_atcreate,24)?>"></div></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-dark btn-sm" onclick="$(this).converter('<?=$id?>')"><i class="fas fa-rotate"></i></button>
                                    <button class="btn btn-info btn-sm" onclick="$(this).forms_modal({'page' : 'booking','data1' : '<?=$id?>','title' : 'Detalle de Reserva'})"><i class="fas fa-pencil"></i></button>
                                    <button class="btn btn-danger btn-sm" onclick="$(this).dell_data('<?=$id?>','url_delete')"><i class="fas fa-times"></i></button>
                                </td>
                            </tr>
                            <?php
                            endforeach;
                            ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php

/*

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><?=$modulo?> Restaurante</h4>
                <a href='javascript:void(0)' onclick='$(this).forms_modal({"page" : "booking","title" : "Reserva"})' class="plus-icon"><i class="fa fa-plus" aria-hidden="true"></i></a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <div class="table-responsive card-table rounded table-hover fs-14">
                        <table class="table border-no display mb-4 dataTablesCard project-bx dataTable_es" id="example5">
                            <thead>
                            <tr>
                                <th>Nombre Cliente</th>
                                <th>Correo</th>
                                <th>Telefono</th>
                                <th>Dias E.</th>
                                <th>C PAX.</th>
                                <th>Observación</th>
                                <th>Creado por </th>
                                <th>#</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach($resultsR As $room):
                                $id = encriptar($room->b_id);
                                ?>
                                <tr>

                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <span class="text-black font-w600 text-nowrap"><?=$room->b_user?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <span class="text-black font-w600 text-nowrap"><?=$room->b_email?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <span class="text-black font-w600 text-nowrap"><?=$room->b_phone?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <span class="text-black font-w600 text-nowrap"><?=$this->class_security->datehuman($room->bf_day)?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <span class="text-black font-w600 text-nowrap"><?=$room->b_cpax?></span>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <span class="text-black font-w600 text-nowrap"><?=$room->b_observation?></span>
                                            </div>
                                        </div>
                                    </td>


                                    <td>
                                        <div>
                                            <h4 class="title font-w600 mb-2 text-black"><?=$room->u_name?></h4>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-info btn-sm" onclick="$(this).forms_modal({'page' : 'booking','data1' : '<?=$id?>','title' : 'Detalle de Reserva'})"><i class="fas fa-pencil"></i></button>
                                        <button class="btn btn-danger btn-sm" onclick="$(this).dell_data('<?=$id?>','url_delete')"><i class="fas fa-times"></i></button>
                                    </td>
                                </tr>
                            <?php
                            endforeach;
                            ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
*/
?>