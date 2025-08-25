<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>



<div class="d-md-flex d-block mb-3 pb-3 border-bottom">
    <div class="card-action card-tabs mb-md-0 mb-3  mr-auto">
        <ul class="nav nav-tabs tabs-lg">
            <li class="nav-item">
                <a href="#navpills-1" class="nav-link active" data-toggle="tab" aria-expanded="false"><span><?=count($filials)?></span>Todos las Tareas</a>
            </li>
        </ul>
    </div>

    <div class="row w-35">
        <?php
        if(isset($menu)){
            foreach($menu As $mn):
                echo "
                    <div class='col mx-1 px-0'>
                        <a href='{$mn['url']}' class='btn btn-primary btn-block'> <i class='{$mn['icon']}'></i> {$mn['name']}</a>
                    </div>
                    ";
            endforeach;
        }
        ?>
<!--        <div class="col mx-0  px-0">-->
<!--            <a href="--><?php //=base_url('/')?><!--" class="btn btn-primary btn-block"> <i class="fas fa-house"></i> Inicio</a>-->
<!--        </div>-->
<!--        <div class="col mx-0 px-1">-->
<!--            <a href="--><?php //=base_url('task')?><!--" class="btn btn-primary btn-block"> <i class="fas fa-table"></i> Logs</a>-->
<!--        </div>-->
    </div>
</div>


<div class="row">
    <?php
    foreach ($status As $statu):
        ?>

        <div class="col-xl-12 col-xxl-12 col-lg-12">
            <div class="card">
                <div class="card-header border-0 pb-0">
                    <h5 class="card-title"><?=$statu->s_name?></h5>
                </div>
                <div class="card-body">
                    <div class="fade show ">
                        <div class="row all_filials" id="all_filials" data-id="<?=$statu->s_id?>">
                            <?php $this->load->view('maintenance/v_work',['timer' => $timer, 'filials' => $this->class_security->filter_all($filials,'m_status',$statu->s_id)]); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php endforeach; ?>

</div>
