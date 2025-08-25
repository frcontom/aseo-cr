<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<!--<div class="d-md-flex d-block mb-3 pb-3 border-bottom">-->
<!---->
<!--    <div class="col-md-12">-->
<!--        <div class="input-group search-area d-lg-inline-flex">-->
<!--            <div class="input-group-append">-->
<!--                <span class="input-group-text"><a href="javascript:void(0)"><i class="flaticon-381-search-2"></i></a></span>-->
<!--            </div>-->
<!--            <input type="text" class="form-control" placeholder="Buscar Filial..." oninput="$(this).search_house()">-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<script>
    let users = <?=json_encode($status)?>;
</script>

<div class="row all_filials">
<?php
//print_r($status);exit;
 foreach ($status As $statu):
     /*
      * Validar que el usuario de asignacion sea null para saber que pertenece a su dueño
      * Validar que si el usuario es igual al usuario de la asignacion
      * Validar sea diferente al asignado se deja con el asignado
      *
      * */
?>

    <div class="col-xl-12">
        <div class="card">
            <div class="card-header border-0 pb-0">
                <h5 class="card-title"><?=$statu->u_name?>  </h5>
            </div>
            <div class="card-body">
                <div class="fade show ">
                    <div class="row" id="all_filials">
                        <?php $this->load->view('supervisor_clean/v_filial',['timer' => $timer,'status' => $status_filial, 'filials' => $this->class_security->filter_all($filials,'user_assigne',$statu->u_id)]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php endforeach; ?>

</div>
