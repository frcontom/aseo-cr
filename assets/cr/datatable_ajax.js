$(document).ready(function() {

    if($("#tabla_data").length > 0 ){
        $(this).dataTable_ajax_es('#datatable','#tabla_data');
    }

    if($('#fecha1, #fecha2, #type').length > 0 ){
        $(this).date_range_func('#fecha1','#fecha2','yyyy-mm-dd');
    }

    if($("#datatable_ajax_export").length > 0 ){
        $(this).datatable_export_func('#datatable_ajax_export','ajax','#datatable',function(d){
            d.fecha1 = $('#fecha1').val(),
            d.fecha2 = $('#fecha2').val(),
            d.type   = $('#type').val()
        });
    }

// Detectar cambios en los filtros y recargar la tabla automáticamente
    $('#fecha1, #fecha2, #type').on('change', function () {
        $('#datatable_ajax_export').DataTable().ajax.reload();
    });


});

