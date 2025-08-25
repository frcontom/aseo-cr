$(document).ready(function() {


    $(this).dataTable_ajax_es('#datatable','#tabla_data');

    $.fn.deleteAll = function (id) {
        return this.each(function () {
            $(`#${id}`).remove();
        })
    }


    $.fn.add_services = function (id) {
        return this.each(function () {
            // console.log('geenerator days '+ id);
            // alert(daindate);
            const details_events = $(`#details_events_${id}`);
            const events = $('.events');
            const ranxd = Math.random().toString(36).substring(7);
            const evens_count = (events.length + 1) + ranxd;

            //generate code
            let timestamp = Date.now();

            // Añadir una parte aleatoria para mayor unicidad
            let numeroUnico = timestamp * 10000 + Math.floor(Math.random() * 9000 + 1000);


            const template =
                `
                 <div class="row mb-2 events" id="event_${numeroUnico}">
                     <div class="col"><input type="hidden" name="days[${id}][events][id][]" value="${id}"><input type="text" name="days[${id}][events][description][]" class="form-control form-control-sm service_descripcion_${id}"></div>
                     <div class="col-3"><input type="text" name="days[${id}][events][time][]" class="form-control form-control-sm text-center timepicker service_time_${id}"></div>
                    <div class="col-1 text-center"><a href="javascript:void(0)" onclick="$(this).deleteAll('event_${numeroUnico}')"><i class="fas fa-times fs-30"></i></a></div>
                 </div>
                 <script>$(".timepicker").timepicker();</script>
                `;

            details_events.append(template);
        })
    }

    $.fn.add_especial = function (id) {
        return this.each(function () {

            // alert(id);

            const details_events = $(`#details_especials_${id}`);
            const events = $('.specials');
            const ranxd = Math.random().toString(36).substring(7);
            const evens_count = (events.length + 1) + ranxd;
            //generate code
            let timestamp = Date.now();

            // Añadir una parte aleatoria para mayor unicidad
            let numeroUnico = timestamp * 10000 + Math.floor(Math.random() * 9000 + 1000);
            const template =
                `
                 <div class="row mb-2 specials" id="especial_${numeroUnico}">
                     <div class="col"><input type="hidden" name="days[${id}][special][id][]" value="${id}"><input type="text" name="days[${id}][special][name][]" class="form-control form-control-sm special_name_${id}"></div>
                    <div class="col"><input type="text" name="days[${id}][special][menu][]" class="form-control form-control-sm special_menu_${id}"></div>
                    <div class="col-1 text-center"><a href="javascript:void(0)" onclick="$(this).deleteAll('especial_${numeroUnico}')"><i class="fas fa-times fs-30"></i></a></div>
                </div>
                `;
            details_events.append(template);
        })
    }

});