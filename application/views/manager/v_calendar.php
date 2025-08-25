<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="row">
    <div class="col-xl-9 col-xxl-8">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div id="calendar" class="app-fullcalendar dashboard-calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-xxl-4">
        <div class="row">
            <div class="col-xl-12">
                <div class="card bg-primary">
                    <div class="card-body">
                        <div class="date-bx d-flex align-items-center">
                            <h2 class="mb-0 mr-3"><?=date('j')?></h2>
                            <div>
                                <p class="mb-0 text-white op7">Hoy</p>
                                <span class="fs-24 text-white font-w600"><?=$this->class_data->dia[date('N')]?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-12">
                <div class="card bg-success">
                    <div class="card-body">
                        <div class="text-center">
                            <a href="javascript:void(0)" onclick="$(this).showProformas();"><h1 class="mb-0 mr-3 text-white">Ver proformas</h1></a>
                        </div>
                    </div>
                </div>
            </div>



            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header border-0 shadow-sm">
                        <h4 class="fs-20 text-black font-w600">Eventos del día : <span id="event_value"></span></h4>
                    </div>
                    <div class="card-body" id="media">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
