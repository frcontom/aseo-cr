$(document).ready(function() {

    $(this).asignacion_porcentaje('.percentaje');
    $(this).dataTable_search_field_ajax_es('#url_datatable','.datatable_ajax');

    $.fn.change_status = function (type = '') {
        return this.each(function () {
            let check   = $(this);
            let value   = check.is(':checked');
            let id      = check.data('id')

            const dataR = {
                value : (value) ? 1 : 2,
                type,
                id
            };

            $(this).simple_call_text(dataR,'url_save_status',false,(err) =>{
                return;
            },true);
        });
    }

    $.fn.calcule_sell = function () {
        return this.each(function () {
            let night                = $('#night').val();
            let fee                  = $('#feet').val();
            let percentage           = $('#percentage').val();
            let amount               = $('#amount');
            let amount_comision      = $('#amount_comision');
            const formatter= Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' })

            //parse float
            let f_night      = parseInt(night);
            let f_fee        = parseFloat(fee.replace(/,/g, ""));
            let f_percentage = parseFloat(percentage.replace(/,/g, ""));


            if(night == '' || fee == '' || percentage == '') {
                amount.val(0);
                amount_comision.val(0);
                return;
            }
            //calcule amount sell
            let sell_calcule = f_fee * f_night;

            //percentage calcule
            let percentajeC = f_percentage / 100; //get calcule percentage
            let calcules = ((sell_calcule*percentajeC));

            amount.val(formatter.format(sell_calcule));
            amount_comision.val(formatter.format(calcules));
        });
    }

    $.fn.calcule_days = function () {
        return this.each(function () {
            let startDate = $('#fecha1').val(); // Fecha de inicio
            let endDate   = $('#fecha2').val();   // Fecha de fin
            let night     = $('#night')

            if(startDate == '' || endDate == '') {
                night.val(0);
                return;
            }
                var start = new Date(startDate);
                var end = new Date(endDate);

                // Calcular la diferencia en milisegundos
                var diffInTime = end.getTime() - start.getTime();

                // Convertir la diferencia de milisegundos a días
                var diffInDays = diffInTime / (1000 * 3600 * 24);
                let diffInDays_c = (diffInDays == 0) ? 1 : diffInDays;
                night.val(diffInDays_c);
                $(this).show_comision();
        });
    }

    $.fn.show_comision = function () {
        return this.each(function () {
            let seller        = $('#seller').val();
            let night        = $('#night').val();
            let edit         = $('#edit').val();
            let percentage   = $('#percentage')
            let percentajeAll = percentaje;

            if(seller == '' || seller == null) {
                $(this).mensaje_alerta(1, "Debes seleccionar un Vendedor");
                return;
            }

            if(night == '') {
                night.val(0);
                return;
            }

            if(edit == 2){
                return;
            }

            if(night >= 0){
                //get data user
                let filteredData = percentajeAll.filter((item) => item.sp_seller == seller)
                const resultObject = filteredData.length > 0 ? filteredData[0] : null;
                if(resultObject == null){
                    $(this).mensaje_alerta(1, "El vendeor seleccionado no esta configurado");
                    percentage.val('');
                    return;
                }

                if(night <= 1){
                    percentage.val(resultObject.wp_percentaje_day);
                }else{
                    percentage.val(resultObject.wp_percentaje_major);
                }
            }


        });
    }



});