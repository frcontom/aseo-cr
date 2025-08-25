<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="row">
    <div class="col-12">
        <div class="card">

            <div class="card-header d-sm-flex d-block shadow-sm">
                <div>
                    <h4 class="fs-20 mb-0 font-w600 text-black mb-sm-0 mb-2">walk-IN Porcentaje de ventas</h4>
                </div>
                <a href="javascript:void(0)" onclick='$(this).forms_modal({"page" : "walkin_percentage","title" : "Porcentaje"})' class="plus-icon"><i class="fa fa-plus" aria-hidden="true"></i></a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">
                        <input type="hidden" name="data" value="data">
                        <input type="hidden" name="import" value="multiple">

                        <div class="modal-body">

                            <div class="row text-center">
                                <div class="form-group col-1">
                                    <label>#</label>
                                </div>

                                <div class="form-group col-6">
                                    <label>Nombre</label>
                                </div>

                                <div class="form-group col">
                                    <label>Tipo</label>
                                </div>

                                <div class="form-group col">
                                    <label>% 1 DIA</label>
                                </div>

                                <div class="form-group col">
                                    <label>% SUPERIOR 1 DIA</label>
                                </div>

                                <div class="form-group col">
                                    <label>Acción</label>
                                </div>
                            </div>

                            <?php
                                $i = 1;
                                foreach ($datas As $data){
                                    $id                  = $data['wp_id'];
                                    $idc                  = encriptar($data['wp_id']);
                                    $name                = $data['wp_name'];
                                    $type = $this->class_data->walkin_type[$data['wp_type']];
                                    $percentaje_day      = $data['wp_percentaje_day'];
                                    $percentaje_major    = $data['wp_percentaje_major'];

                                    ?>
                                    <div class="row mb-2">

                                        <div class="form-group col-1">
                                        <input type="text" readonly value="<?=$i++?>"  autofocus required class="form-control form-control-sm imput_reset text-center " autocomplete="off">
                                    </div>

                                        <div class="form-group col-6">
                                            <input type="hidden" name="percentaje[<?=$id?>][id]" value="<?=$id?>">
                                            <input type="text" readonly value="<?=$name?>"  autofocus required class="form-control form-control-sm imput_reset text-center " autocomplete="off">
                                        </div>

                                        <div class="form-group col">
                                            <input type="text"  value="<?=$type?>" readonly autofocus required class="form-control form-control-sm imput_reset text-center " autocomplete="off">
                                        </div>

                                        <div class="form-group col">
                                            <input type="text" name="percentaje[<?=$id?>][day]"  value="<?=$percentaje_day?>"  autofocus required class="form-control form-control-sm imput_reset text-center percentaje" autocomplete="off">
                                        </div>

                                        <div class="form-group col">
                                            <input type="text" name="percentaje[<?=$id?>][major]"  value="<?=$percentaje_major?>"  autofocus required class="form-control form-control-sm imput_reset text-center percentaje" autocomplete="off">
                                        </div>

                                        <div class="form-group col text-center">
                                            <button type="button" onclick='$(this).forms_modal({"page" : "walkin_percentage","data1" : "<?=$idc?>","title" : "Editar Porcentaje"})' class="btn btn-info btn-sm"><i class="fas fa-pencil"></i></button>
                                            <button type="button" onclick='$(this).dell_data("<?=$idc?>","url_delete")'  class="btn btn-danger btn-sm"><i class="fas fa-times"></i></button>
                                        </div>
                                    </div>
                            <?php
                                }
                            ?>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar Proceso</button>
                            <button type="submit" class="btn btn-primary">Guardar </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>