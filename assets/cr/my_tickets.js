$(document).ready(function() {

    if($("#table_log").length > 0){
        $(this).dataTable_ajax_es('#tabla_data','#table_log');
    }

});