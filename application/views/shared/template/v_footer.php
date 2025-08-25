<?php
defined('BASEPATH') or exit('No direct script access allowed');

if(isset($crud) AND count($crud) >= 1){
    foreach($crud As $crud_key => $crud_value){
        $hidden_name = $crud_key;
        $hidden_url  = $crud_value;
        echo "<input type='hidden' id='{$hidden_name}' value='{$hidden_url}'>\n";
    }
}
?>

<div id="modal_principal" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog in_modal_primario modal-800">
        <div class="modal-content">
            <div class="modal-header header_modal">
                <h4 class="modal-title" id="myModalLabel"><?php echo $titulo; ?>  - <b id="label_modal"></b></h4>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
            </div>

            <div class="modal-body-view"></div>

        </div>
    </div>
</div>


</div>
</div>
<div class="footer">
    <div class="copyright">
        <p>Todos los derechos Reservados</p>
    </div>
</div>
<!--**********************************
    Footer end
***********************************-->

<!--**********************************
   Support ticket button start
***********************************-->
<div class="chatbox">
    <div class="chatbox-close"></div>
    <div class="custom-tab-1">
        <div class="tab-content">
                <div class="card mb-sm-3 mb-md-0 note_card">
                    <div class="card-header chat-list-header text-center">
                        <a href="javascript:void(0)"></a>
                        <div>
                            <h6 class="mb-1">Eventos Modificados</h6>
                            <p class="mb-0">Lista de eventos Modificados</p>
                        </div>
                        <a href="javascript:void(0)"></a>
                    </div>
                    <div class="card-body contacts_body p-0 dlab-scroll" id="dlab_W_Contacts_Body2">
                        <ul class="contacts">
                            <?php
                            foreach($this->class_security->events_modifid()['data'] As $modifid):
                                $type_mod = [1 => 'Proforma',2 => 'Evento'];
                                $id = encriptar($modifid['b_id']);
                                echo "<li class='active'>
                                <div class='d-flex bd-highlight'>
                                    <div class='user_info' onclick='$(this).forms_modal({\"page\" : \"profome_view\",\"data1\" : \"{$id}\",\"title\" : \"Detalle de Evento\"})' >
                                        <span>{$modifid['b_event_name']}</span>
                                        <p>{$modifid['em_atcreate']}</p>
                                    </div>
                                </div>
                            </li>";

                            endforeach;
                            ?>
                        </ul>
                    </div>
                </div>
        </div>
    </div>
</div>
<!--**********************************
   Support ticket button end
***********************************-->


</div>
<!--**********************************
    Main wrapper end
***********************************-->

<!--**********************************
    Scripts
***********************************-->
<!-- Required vendors -->
<script src="<?=base_url('assets/'); ?>vendor/global/global.min.js"></script>
<script src="<?=base_url('assets/'); ?>vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
<script src="<?=base_url('assets/'); ?>js/dlabnav-init.js"></script>
<script src="<?=base_url('assets/'); ?>vendor/datatables/js/jquery.dataTables.min.js"></script>


<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>

<script src="<?=base_url('assets/'); ?>vendor/select2/js/select2.full.min.js"></script>
<script src="<?=base_url('assets/'); ?>vendor/sweetalert2/dist/sweetalert2.min.js"></script>

<!-- Chart piety plugin files -->
<script src="<?=base_url('assets/'); ?>vendor/peity/jquery.peity.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.14.0/jquery.timepicker.js"></script>
<script src="https://demos.codexworld.com/convert-html-to-pdf-using-javascript-jspdf/js/html2canvas.min.js"></script>
<script src="https://demos.codexworld.com/3rd-party/jsPDF-2.5.1/dist/jspdf.umd.js"></script>

<!-- pickdate -->
<script src="<?=base_url('assets/'); ?>vendor/moment/moment.min.js"></script>
<script src="<?=base_url('assets/'); ?>vendor/moment/moment-timezone.min.js"></script>

<script src="<?=base_url('assets/'); ?>vendor/pickadate/picker.js"></script>
<script src="<?=base_url('assets/'); ?>vendor/pickadate/picker.time.js"></script>
<script src="<?=base_url('assets/'); ?>vendor/pickadate/picker.date.js"></script>

<!-- Apex Chart -->
<script src="<?=base_url('assets/'); ?>vendor/apexchart/apexchart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.22.1/ckeditor.js"></script>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.4.2/tinymce.min.js"></script>-->

<!-- Dashboard 1 -->
<!--<script src="--><?php //=base_url('assets/'); ?><!--js/dashboard/dashboard-1.js"></script>-->

<!-- custom -->
<script src="<?=base_url('assets/'); ?>js/validator.min.js"></script>
<script src="<?=base_url('assets/'); ?>plugins/lobibox/js/lobibox.js"></script>
<script src="<?=base_url('assets/'); ?>plugins/countdown/jquery.countdown.js"></script>
<script src="<?=base_url('assets/'); ?>cr/all_scripts.js?ver=<?=(isset($this->project['version']) ? $this->project['version'] : '1.0.0')?>"></script>
<script src="<?=base_url('assets/'); ?>cr/notify.js?ver=<?=(isset($this->project['version']) ? $this->project['version'] : '1.0.0')?>"></script>
<script src="<?=base_url('assets/'); ?>cr/modals.js?ver=<?=(isset($this->project['version']) ? $this->project['version'] : '1.0.0')?>"></script>
<script src="<?=base_url('assets/'); ?>cr/general.js?ver=<?=(isset($this->project['version']) ? $this->project['version'] : '1.0.0')?>"></script>


<script src="<?=base_url('assets/'); ?>js/custom.min.js"></script>

<?php
if(!empty($script_level))
{
    foreach($script_level As $script_lv):

        //valitate si trae :// assets/cr/level1.js
        if(strpos($script_lv, ':') !== false){
                $explorer = explode(':', $script_lv);
                    if($explorer['1'] == 'module'){
                        $url_script_level = base_url('assets/').$explorer[0].'?ver='.(isset($this->project['version']) ? $this->project['version'] : '1.0.0');
                        echo "<script src='$url_script_level' type='module'></script>
";
                    }
            }else{
            $url_script_level = base_url('assets/').$script_lv.'?ver='.(isset($this->project['version']) ? $this->project['version'] : '1.0.0');
            echo "<script src='$url_script_level'></script>
";
        }



    endforeach;
}
?>

</body>
</html>