<?php
defined('BASEPATH') or exit('No direct script access allowed');

//filter task express or scheduled
$task_express = $this->class_security->filter_all($filials,'hma_type',1);
$task_program = $this->class_security->filter_all($filials,'hma_type',2);
print_r($task_program);
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
        <div class="col mx-0  px-0">
                <a href="<?=base_url('/')?>" class="btn btn-primary btn-block"> <i class="fas fa-house"></i> Inicio</a>
        </div>
        <div class="col mx-0 px-1">
            <a href="<?=base_url('job_rowk')?>" class="btn btn-primary btn-block"> <i class="fas fa-table"></i> Proceso</a>
        </div>
        <div class="col mx-0 px-1">
                <a href="<?=base_url('task')?>" class="btn btn-primary btn-block"> <i class="fas fa-table"></i> Logs</a>
        </div>
    </div>
</div>

<div class="row">
  <div class="col-xl-12">
      <div class="card">
          <div class="card-header d-block">
              <h4 class="card-title">Tareas</h4>
          </div>
          <div class="card-body">
              <div class="row">
                  <?php $this->load->view('supervisor_maintenance/v_work',['size' => 'col-md-3','timer' => $timer,'status' => $status_filial, 'filials' => $this->class_security->filter_all($filials,'hma_id','')]); ?>
              </div>
          </div>
      </div>
  </div>
</div>

<div class="row">


    <div class="col-xl-6">
        <div class="card">
            <div class="card-header d-block">
                <h4 class="card-title">Tareas Express</h4>
            </div>
            <div class="card-body">
                <!-- Default accordion -->
                <div id="accordion-one" class="accordion accordion-primary">

                    <?php
                    foreach ($status As $statu):
                        ?>

                        <div class="accordion__item">
                            <div class="accordion__header collapsed rounded-lg" data-toggle="collapse" data-target="#default_collapse<?=$statu->s_id?>">
                                <span class="accordion__header--text"> <?=$statu->s_name?></span>
                                <span class="accordion__header--indicator"></span>
                            </div>
                            <div id="default_collapse<?=$statu->s_id?>" class="collapse accordion__body " data-parent="#accordion-one">
                                <div class="accordion__body">

                                    <div class="row my-3">
                                    <?php $this->load->view('supervisor_maintenance/v_work',['size' => '','timer' => $timer,'status' => $status_filial, 'filials' => $this->class_security->filter_all($task_express,'m_status',$statu->s_id)]); ?>
                                    </div>

                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>


                </div>
            </div>
        </div>
    </div>




    <div class="col-6">
        <div class="card">
            <div class="card-header d-block">
                <h4 class="card-title">Tareas Programadas</h4>
            </div>
            <div class="card-body">
              <div class="row">
                  <?php $this->load->view('supervisor_maintenance/v_work',['size' => '','timer' => $timer,'status' => $status_filial, 'filials' => $task_program]); ?>
              </div>
            </div>
        </div>
    </div>

</div>
