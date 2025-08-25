<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>


<div class="row">
    <div class="col">
        <div class="d-md-flex d-block mb-3 pb-3 border-bottom">
            <div class="input-group w-100 search-area d-lg-inline-flex">
                <div class="input-group-append">
                    <span class="input-group-text"><a href="javascript:void(0)"><i class="flaticon-381-search-2"></i></a></span>
                </div>
                <input type="text" class="form-control" placeholder="Buscar Filial..." oninput="$(this).search_house()">
            </div>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-xl-12 col-xxl-12 col-lg-12">
        <div class="fade show ">
            <div class="row" id="all_filials">
                <?php
                $this->load->view('security/v_filial',['filials' => $filials,'status' => $status]);
                ?>
            </div>
        </div>
    </div>
</div>
<div id="select2Modal"></div>