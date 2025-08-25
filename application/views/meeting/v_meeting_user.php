<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="row" id="printPDF">
    <div class="col-12">
        <div class="card"  id="printPDF" style=" white-space: nowrap;">
                <div class="card-header">
                    <h4 class="card-title"><?=$modulo?></h4>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-4">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Fecha</label>
                                <div class="col-sm-9">
                                    <input type="text"  id="fecha" value="<?=$fecha?>" onchange="$(this).generate_meeting()" class="form-control form-control-sm border-dark text-center fecha">
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
                                    <input type="text" readonly  id="entrada"  class="form-control form-control-sm border-dark text-center">
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
                                                <input type="text" readonly id="cierre" class="form-control border-dark text-center numeros">
                                        </div>
                                        <div class="col">
                                            <label class="form-label small text-muted">Hoy</label>
                                            <input type="text" readonly id="opcations_0"  class="form-control form-control-sm text-center border-dark numeros">
                                        </div>
                                        <div class="col">
                                            <label class="form-label small text-muted">Mañana</label>
                                                <input type="text" readonly id="opcations_1"   class="form-control form-control-sm text-center border-dark numeros">
                                        </div>
                                        <div class="col">
                                            <label class="form-label small text-muted">2 días</label>
                                                <input type="text" readonly id="opcations_2"   class="form-control form-control-sm text-center border-dark numeros">
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
                                    <input type="text" readonly id="salida" class="form-control form-control-sm border-dark  text-center">
                                </div>
                            </div>
                        </div>


                        <div class="col-8">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Tarifa Prm</label>
                                <div class="col-10">
                                    <div class="row">
                                        <div class="col-3">
                                            <input type="text" readonly id="tarifa" class="form-control border-dark  text-center">
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
                                            <input type="text" readonly id="revpar"  class="form-control border-dark text-center">
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
                                    <th class="m-0 p-0"><input type="text" readonly id="kitcheng_0"  class="form-control form-control-sm text-center border-dark"></th>
                                    <td class="m-0 p-0"><input type="text" readonly id="kitcheng_1"  class="form-control form-control-sm text-center border-dark"></td>
                                    <td class="m-0 p-0"><input type="text" readonly id="kitcheng_2"  class="form-control form-control-sm text-center border-dark"></td>
                                    <td class="m-0 p-0"><input type="text" readonly id="kitcheng_3"  class="form-control form-control-sm text-center border-dark"></td>
                                    <td class="m-0 p-0"><input type="text" readonly id="kitcheng_4"  class="form-control form-control-sm text-center border-dark"></td>
                                    <td class="m-0 p-0"><input type="text" readonly id="kitcheng_5"  class="form-control form-control-sm text-center border-dark"></td>
                                    <td class="m-0 p-0"><input type="text" readonly id="kitcheng_6"  class="form-control form-control-sm text-center border-dark"></td>
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
                                <tbody id="events1">

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
                                    <th class="bg-success text-white" colspan="4">INGRESO DE GRUPOS</th>
                                </tr>
                                <tr>
                                    <th class="bg-success text-white">Nombre evento</th>
                                    <th class="bg-success text-white">Nombre Agencia</th>
                                    <th class="bg-success text-white">Pax</th>
                                    <th class="bg-success text-white">Observación</th>
                                </tr>
                                </thead>
                                <tbody id="events4">


                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Ingreso de grupos -->
<!--                    <div class="row">-->
<!--                        <div class="table-responsive">-->
<!--                            <table class="table table-bordered text-center">-->
<!--                                <thead class="thead-success">-->
<!--                                <tr>-->
<!--                                    <th class="bg-success text-white" colspan="4">COMUNICACIONES GENERALES</th>-->
<!--                                </tr>-->
<!--                                <tr>-->
<!--                                    <th class="bg-success text-white">Comunicación</th>-->
<!--                                </tr>-->
<!--                                </thead>-->
<!--                                <tbody id="comunication_table">-->
<!--                                </tbody>-->
<!--                            </table>-->
<!--                        </div>-->
<!--                    </div>-->

                </div>
                <div class="card-footer d-flex justify-content-end">
                    <button type="button" class="btn btn-success mr-2" onclick="$(this).printPDF();"><i class="fas fa-file-pdf"></i>Descargar PDF</button>
                </div>
            </div>
    </div>
</div>

