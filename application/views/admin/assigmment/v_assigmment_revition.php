<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="d-md-flex d-block mb-3 pb-3 border-bottom">
    <div class="card-action card-tabs mb-md-0 mb-3  mr-auto">
        <ul class="nav nav-tabs tabs-lg">
            <li class="nav-item">
                <a href="#navpills-1" class="nav-link active" data-toggle="tab" aria-expanded="false"><span><?=count($filials)?></span>Todos las filiales</a>
            </li>
            <li>

            </li>
        </ul>


    </div>

    <div class="basic-form">
        <div class="form-row align-items-start">
            <div class="col-auto my-1">
                <select class="mr-sm-2 default-select" id="inlineFormCustomSelect" onchange="$(this).filter_floor()">
                    <option selected value="">[ Todos los Pisos ]</option>
                    <?php
                    foreach($floors As $floor){
                        echo "<option value='{$floor->fr_id}'>{$floor->fr_name}</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>

    <div>
        <div class="input-group search-area d-lg-inline-flex">
            <div class="input-group-append">
                <span class="input-group-text"><a href="javascript:void(0)"><i class="flaticon-381-search-2"></i></a></span>
            </div>
            <input type="text" class="form-control" placeholder="Buscar Filial..." oninput="$(this).search_house()">
        </div>
    </div>

</div>
<div class="row">
    <div class="col-xl-12 col-xxl-12 col-lg-12">
        <div class="fade show ">
            <div class="row" id="all_filials">
                <?php
                $this->load->view('admin/assigmment/filial/v_filial_revision',['filials' => $filials,'status' => '3,4'])
                ?>
            </div>
        </div>
    </div>
</div>
<div id="select2Modal"></div>