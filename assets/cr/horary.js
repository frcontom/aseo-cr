$(document).ready(function() {

    if($('#date1, #date2').length > 0 ){
        $(this).date_range_func('#date1','#date2','yyyy-mm-dd');
    }

    // if($('#date1, #date2, #employ_name').length > 0 ){
    //     $(this).date_range_func('#date1', '#date2', 'yyyy-mm-dd');
    //
    //     $('#date1, #date2, #employ_name').on('keypress', function () {
    //         console.log('keypress')
    //         let employ_name = $('#employ_name').val();
    //         let date1 = $('#date1').val();
    //         let date2 = $('#date2').val();
    //
    //         $(this).dataTable_ajax_params_es('#datatable', '.datatable_ajax', employ_name, date1, date2);
    //
    //         $('#datatable_ajax_export').DataTable().ajax.reload();
    //     });
    // }

    if($(".datatable_ajax").length > 0 ){
        $(this).datatable_ajax_dinamic_fields_func('.datatable_ajax','#datatable',function(d){
            d.date1 = $('#date1').val(),
            d.date2 = $('#date2').val(),
            d.employ   = $('#employ_name').val()
        });
    }

// Detectar cambios en los filtros y recargar la tabla automáticamente
    $('#date1, #date2, #employ_name').on('change keyup', function () {
        $('.datatable_ajax').DataTable().ajax.reload();
    });



    $.fn.createSendwhatsapp = function (canvaImage = true) {
        return this.each(function () {

            if(canvaImage == false){
                let objectSend = {
                    data: 'msg'
                }
                $(this).simple_call_text(objectSend,'url_send_whatsapp',false,function(){});
            }else{
                html2canvas(document.querySelector('#imgWhatsapp'), {
                    onrendered: function(canvas) {
                        // document.body.appendChild(canvas);

                        let objectSend = {
                            data: canvas.toDataURL()
                        }
                        $(this).simple_call_text(objectSend,'url_send_whatsapp',false,function(re){});
                    }
                });
            }
        });
    }

    $.fn.changeDay = function (data) {
        return this.each(function () {
            let horary = $(this).val();
            let objectSend = {
                ...data,
                horary: horary
            }
            // console.log(objectSend)

            $(this).simple_call_text(objectSend,'url_schedules_day_save',false,function(){

            });

        });
    }

    $.fn.changeDayOcupation = function (data) {
        return this.each(function () {
            let ocupation = $(this).val();
            let objectSend = {
                ...data,
                ocupation
            }
            // console.log(objectSend);return;
            $(this).simple_call_text(objectSend,'url_schedules_day_ocupation_save',false,function(){

            });

        });
    }

    $.fn.report_create = function () {
        return this.each(function () {

            //variables
            let objectSend = {
                date1 : $("#date1").val(),
                date2 : $("#date2").val(),
                employ  : $("#employ").val(),
                departament  : $("#departament").val(),
            }
            // alert(22)
            $(this).simple_call_text(objectSend,'url_report',false,function(response){
                console.log(response)
                $("#html_report").empty().html(response);
            },true,false,'text',true);
        });
    }
});