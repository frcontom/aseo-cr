<?php
defined('BASEPATH') or exit('No direct script access allowed');
$entry          = $this->class_security->validate_var($results,'m_entry');
$occupancy_closing          = $this->class_security->validate_var($results,'m_occupancy_closing');
$occupancy_today          = $this->class_security->validate_var($results,'m_occupancy_today');
$occupancy_tomorrow          = $this->class_security->validate_var($results,'m_occupancy_tomorrow');
$occupancy_day          = $this->class_security->validate_var($results,'m_occupancy_day');
$exit          = $this->class_security->validate_var($results,'m_exit');
$rate_pm          = $this->class_security->validate_var($results,'m_rate_pm');
$revpar          = $this->class_security->validate_var($results,'m_revpar');

//filter events  or agency
$filtedata1_1 = $this->class_security->filter_array_simple($events,'b_type','2');
$filtedata1_2 = $this->class_security->filter_array_simple($events,'b_type','3');
$filtedata1_3 = $this->class_security->filter_array_simple($events,'b_type','1');

$filtedata2 = array_merge($filtedata1_1,$filtedata1_2,$filtedata1_3);

$filtedata4 = $this->class_security->filter_array_simple($events,'b_type','4');

?>

<div class="row">
    <div class="col-12">
        <form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data" autocomplete="off">
            <div class="card"  id="printPDF" style=" white-space: nowrap;">
                <div class="card-header">
                    <h4 class="card-title"><?=$modulo?></h4>
                </div>
                <div class="card-body" id="imgWhatsapp" style="background: white">

                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Fecha</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="fecha" readonly value="<?=$fecha?>" class="form-control form-control-sm border-dark text-center">
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row"><div class="col"><h4>Habitaciones</h4></div></div>
                            <hr>

                            <div class="row">
                                <div class="col">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Entrada</label>
                                        <div class="col-sm-9">
                                            <input type="text"  name="entrada" value="<?=$entry?>" class="form-control form-control-sm border-dark text-center">
                                        </div>
                                    </div>
                                </div>


                                <div class="col-8">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Ocupación</label>
                                        <div class="col-10">
                                            <div class="row">
                                                <div class="col">
                                                    <label class="form-label small text-muted">Cierre</label>
                                                        <input type="text" name="cierre" value="<?=$occupancy_closing?>" class="form-control border-dark text-center porcentaje">


                                                </div>
                                                <div class="col">
                                                    <label class="form-label small text-muted">Hoy</label>
                                                    <input type="hidden" name="opcations[0][day]" value="<?=$opcations['0']['date']?>">
                                                        <input type="text" id="input_1" onkeyup="$(this).meeting_calcule()" name="opcations[0][value]"  value="<?=$opcations['0']['info']?>" class="form-control form-control-sm text-center border-dark  porcentaje">

                                                </div>
                                                <div class="col">
                                                    <label class="form-label small text-muted">Mañana</label>
                                                    <input type="hidden" name="opcations[1][day]" value="<?=$opcations['1']['date']?>">
                                                        <input type="text" name="opcations[1][value]"  value="<?=$opcations['1']['info']?>" class="form-control form-control-sm text-center border-dark porcentaje">

                                                </div>
                                                <div class="col">
                                                    <label class="form-label small text-muted">2 días</label>
                                                    <input type="hidden" name="opcations[2][day]" value="<?=$opcations['2']['date']?>">
                                                        <input type="text" name="opcations[2][value]"  value="<?=$opcations['2']['info']?>" class="form-control form-control-sm text-center border-dark porcentaje">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Salidas</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="salida" value="<?=$exit?>" class="form-control form-control-sm border-dark  text-center">
                                        </div>
                                    </div>
                                </div>


                                <div class="col-8">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Tarifa Prm</label>
                                        <div class="col-10">
                                            <div class="row">
                                                <div class="col-3">
                                                    <input type="text" name="tarifa" id="input_2" onkeyup="$(this).meeting_calcule()"  value="<?=$rate_pm?>" class="form-control border-dark  text-center sin_porcentaje">
                                                </div>
                                        </div>
                                    </div>
                                </div>

                            </div>


                        </div>

                            <div class="row">
                                <div class="col"></div>

                                <div class="col-8">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">RevPAR</label>
                                        <div class="col-10">
                                            <div class="row">
                                                <div class="col-3">
                                                    <input type="text" id="input_3" name="revpar" value="<?=$revpar?>" readonly class="form-control border-dark text-center">
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                </div>


                            </div>

                            <div class="row">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-center">
                                        <thead class="thead-success">
                                        <tr>
                                            <th colspan="8">Alimentación y Bebidas</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <tr>
                                            <th rowspan="2" style="text-align: center;vertical-align: middle;">Desayuno</th>
                                            <th class="bg-success text-white">Hoy</th>
                                            <th class="bg-success text-white">Mañana</th>
                                            <th class="bg-success text-white">2 Días</th>
                                            <th class="bg-success text-white">3 Días</th>
                                            <th class="bg-success text-white">4 Días</th>
                                            <th class="bg-success text-white">5 Días</th>
                                            <th class="bg-success text-white">6 Días</th>
                                        </tr>
                                        <tr>
                                            <th class="m-0 p-0"><input type="hidden" name="kitcheng[0][day]" value="<?=$days['0']['date']?>"><input type="text" name="kitcheng[0][value]"  value="<?=$days['0']['info']?>" class="form-control form-control-sm text-center border-dark"></th>
                                            <td class="m-0 p-0"><input type="hidden" name="kitcheng[1][day]" value="<?=$days['1']['date']?>"><input type="text" name="kitcheng[1][value]"  value="<?=$days['1']['info']?>" class="form-control form-control-sm text-center border-dark"></td>
                                            <td class="m-0 p-0"><input type="hidden" name="kitcheng[2][day]" value="<?=$days['2']['date']?>"><input type="text" name="kitcheng[2][value]"  value="<?=$days['2']['info']?>" class="form-control form-control-sm text-center border-dark"></td>
                                            <td class="m-0 p-0"><input type="hidden" name="kitcheng[3][day]" value="<?=$days['3']['date']?>"><input type="text" name="kitcheng[3][value]"  value="<?=$days['3']['info']?>" class="form-control form-control-sm text-center border-dark"></td>
                                            <td class="m-0 p-0"><input type="hidden" name="kitcheng[4][day]" value="<?=$days['4']['date']?>"><input type="text" name="kitcheng[4][value]"  value="<?=$days['4']['info']?>" class="form-control form-control-sm text-center border-dark"></td>
                                            <td class="m-0 p-0"><input type="hidden" name="kitcheng[5][day]" value="<?=$days['5']['date']?>"><input type="text" name="kitcheng[5][value]"  value="<?=$days['5']['info']?>" class="form-control form-control-sm text-center border-dark"></td>
                                            <td class="m-0 p-0"><input type="hidden" name="kitcheng[6][day]" value="<?=$days['6']['date']?>"><input type="text" name="kitcheng[6][value]"  value="<?=$days['6']['info']?>" class="form-control form-control-sm text-center border-dark"></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Events -->
                            <div class="row">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-center table-bordered">
                                        <thead class="thead-success">
                                        <tr>
                                            <th class="bg-success text-white">Grupo / Eventos / Ventas</th>
                                            <th class="bg-success text-white">Pax</th>
                                            <th class="bg-success text-white">Salón</th>
                                            <th class="bg-success text-white">Desde</th>
                                            <th class="bg-success text-white">Hasta</th>
                                            <th class="bg-success text-white">P. info</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <?php
                                        if(isset($filtedata2)){
                                            foreach ($filtedata2 as $evet) {
                                                $hourIsChange = $this->class_security->format_date($evet['hour1'],'g:i A');
                                                $hourFsChange = $this->class_security->format_date($evet['hour2'],'g:i A');
                                                echo "<tr>";
                                                echo "<td>{$evet['name']}</td>";
                                                echo "<td>{$evet['b_cpax']}</td>";
                                                echo "<td>{$evet['name_room']}</td>";
                                                echo "<td>{$hourIsChange}</td>";
                                                echo "<td>{$hourFsChange}</td>";
                                                echo "<td></td>";
                                                echo "</tr>";
                                            }
                                        }
                                        ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Ingreso de grupos -->
                            <div class="row">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-center">
                                        <thead class="thead-success">
                                            <tr>
                                                <th class="bg-success text-white" colspan="4">INGRESO DE GRUPOS </th>
                                            </tr>
                                            <tr>
                                                <th class="bg-success text-white">Nombre evento</th>
                                                <th class="bg-success text-white">Nombre Agencia</th>
                                                <th class="bg-success text-white">Pax</th>
                                                <th class="bg-success text-white">Observación</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if(isset($filtedata4)){
                                            foreach ($filtedata4 as $evet) {
                                                echo "<tr>";
                                                echo "<td>{$evet['name']}</td>";
                                                echo "<td>{$evet['b_client_name']}</td>";
                                                echo "<td>{$evet['b_cpax']}</td>";
                                                echo "<td>{$evet['bd_description']}</td>";
                                                echo "</tr>";
                                            }
                                        }
                                        ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Ingreso de grupos -->
                            <div class="row">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-center">
                                        <thead class="thead-success">
                                            <tr>
                                                <th class="bg-success text-white" colspan="4"><a class="text-white" href="javascript:void(0)" onclick="$(this).add_communication()">COMUNICACIONES GENERALES <i class="fas fa-plus"></i></a></th>
                                            </tr>
                                            <tr>
                                                <th class="bg-success text-white">Comunicación</th>
                                                <th scope="col" class="bg-success text-white" style="width: 7%">#</th>
                                            </tr>
                                        </thead>
                                        <tbody id="comunication_table">
                                        <?php
                                        if(isset($communication)){
                                            foreach ($communication as $evet) {
                                                $c_id = $evet->mc_id;
                                                echo "<tr  data-id='{$c_id}'>";
                                                echo " <td class='m-0 p-0'><textarea name='communication[]' class='form-control border-dark' rows='5'>{$evet->mc_info}</textarea></td>";
                                                echo "<td><a  class='btn btn-danger btn-sm' href='javascript:void(0)' onclick=\"$(this).delete_tr('{$c_id}')\"><i class='fas fa-times'></i></a></td>";
                                                echo "</tr>";
                                            }
                                        }
                                        ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                    </div>
                <div class="card-footer d-flex justify-content-end">
                    <button type="button" id="boton1" class="btn btn-warning mr-2"  onclick='$(this).createSendwhatsapp()' ><i class="fas fa-file"></i> Whatsapp Informe</button>
<!--                    <button type="button" id="boton1" class="btn btn-info mr-2"  onclick='$(this).forms_modal({"page" : "whatsapp","title" : "Comunicación Whatsapp"})' ><i class="fas fa-message"></i> Whatsapp Mensaje</button>-->
                    <button type="button" id="boton1" class="btn btn-success mr-2" onclick="$(this).printPDF();"><i class="fas fa-file-pdf"></i> Descargar PDF</button>
                    <button type="submit" id="boton2" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Reunion</button>
                </div>
            </div>
        </form>
    </div>
</div>

