$(document).ready(function () {


    $.fn.clear_form = function() {
        return this.each(function() {
            $('.imput_reset').trigger("reset");
            $('.imput_reset').val('').change();
            $('.imput_reset').prop('checked',false);
            $('.imput_reset_check').prop('checked',false);
            $('.frm_data select').attr('readonly',false).val("");
            $('.select2').val("").change();
            $(".custom-control-input").prop('checked',false);
        })
    }

    $.fn.clear_keyboard = function() {
        return this.each(function() {
            var cajon_texto = $(".caja_texto");
            var cajon_input = $("#cajon_input");

            cajon_texto.html('');
            cajon_input.val('');
        })
    }

    $.fn.clear_form_modal = function() {
        return this.each(function() {
            $('#frm_modal .imput_reset_modal:input').trigger("reset");
            $('#frm_modal .imput_reset_modal:input').val("");
            $('.frm_data select2').val("").change();
            // $(".custom-control-input").prop('checked',false);
        })
    }


    if ($('.dataTable_report1').length > 0) {


        $('.dataTable_report1').DataTable(
            { "language": {"url": url_sitio +"assets/data_spanish.json"},
                "order": [[ 1, "asc" ]],
                "displayLength": 25,
                "ordering": false,
                "scrollX": true,
                "bLengthChange" : false,
                "bInfo":false,
            }
        );

        $($.fn.dataTable.tables( true ) ).css('width', '100%');
        // $($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();
    }



    if ($('.dataTable_es').length > 0) {
        $('.dataTable_es').DataTable(
            { "language": {"url": url_sitio +"assets/data_spanish.json"},
                "displayLength": 10,
                // "responsive": true,
                // "scrollX": true,
            }
        );
        $($.fn.dataTable.tables( true ) ).css('width', '100%');
    }

    if ($('.dataTable_es2').length > 0) {
        $('.dataTable_es2').DataTable(
            { "language": {"url": url_sitio +"assets/data_spanish.json"},
                "displayLength": 10,
                // "responsive": true,
                // "scrollX": true,
            }
        );
        $($.fn.dataTable.tables( true ) ).css('width', '100%');
    }

    $.fn.dataTable_multiple_ajax_es = function(url_tag,table_tag,orderT = 0) {
        return this.each(function() {

            if($(table_tag).length > 0) {

                var hide_data = $(url_tag).val();

                const tableDefaults = {
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    // columns: [
                    //     { data: 'id' },
                    //     { data: 'name' },
                    //     { data: 'sales' }
                    // ]
                };

                // Inicializar DataTables en tablas dinámicas ya existentes
                $(table_tag).each(function() {
                    const tableId = $(this).attr('id');
                    const vendedorId = tableId.split('_').pop(); // Extraer ID del vendedor
                    console.log(vendedorId)
                    $(this).DataTable({
                        responsive: true,
                        processing: true,
                        serverSide: true,
                        ajax: {
                            'data': {
                                'data_id' :  vendedorId
                            },
                            "type": "POST",
                            // url: `/api/vendedor/${vendedorId}/data` // Ruta dinámica basada en el ID
                            url: hide_data // Ruta dinámica basada en el ID
                        }
                    });
                });
                }
            })
        }


    $.fn.dataTable_search_field_ajax_es = function(url_tag,table_tag,orderT = 0) {
        return this.each(function() {
            if ($(table_tag).length > 0) {
                // var hide_data = $("#url_datatable").val();
                var hide_data = $(url_tag).val();

                $(`${table_tag} thead th`).each(function () {
                    var title = $(this).text();
                    console.log(title)
                    const Novalues = ['Tipo','Accion','Editar']
                    if(!Novalues.includes(title)){
                        $(this).html('<div style="display: flex; flex-direction: column;"><span>' + title + '</span><input type="text" class="form-control form-control-sm" placeholder="Buscar ' + title.toLowerCase() + '" style="margin-top: 5px;" /></div>');
                    }else{
                        $(this).html(title.toLowerCase());
                    }
                });

                $(table_tag).DataTable({
                    "ordering": false,
                    "displayLength": 10,
                    "processing": false,
                    "retrieve": true,
                    "serverSide": true,
                    "columnDefs": [{"className": "text-center", "targets": "_all"}],
                    "lengthMenu": [[10, 25, 50,100,1000,2000], [10, 25, 50,100,1000,2000]],
                    "language": {"url": url_sitio +"assets/data_spanish.json"},
                    "ajax": {
                        "url": hide_data,
                        "async":true,
                        "type": "POST",
                        "data": function (d) {
                            // Iterar sobre los inputs de las columnas para obtener los valores
                            $(`${table_tag} thead input`).each(function (index) {
                                var value = $(this).val(); // Captura el valor del input
                                d[`column_${index}`] = value; // Agrega cada valor como un parámetro con su índice
                            });
                        }
                    },
                    initComplete: function () {
                        this.api().columns().every(function () {
                                var that = this;
                                $('input', this.header()).on('keyup change', function () {
                                    that.search(this.value).draw();
                                });
                        });
                    }
                });
            }
        })
    }


    $.fn.dataTable_ajax_es = function(url_tag,table_tag,orderT = 0) {
        return this.each(function() {
            if ($(table_tag).length > 0) {
                // var hide_data = $("#url_datatable").val();
                var hide_data = $(url_tag).val();
                $(table_tag).DataTable({
                    // "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
                    //     "<'table-responsive'tr>" +
                    //     "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
                    "oLanguage": {
                        "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
                        "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                        "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                        "sSearchPlaceholder": "Buscar...",
                        "sLengthMenu": "Resultados :  _MENU_",
                    },
                    "order": [[0, 'desc']],
                    "ordering": false,
                    "displayLength": 10,
                    "processing": false,
                    "retrieve": true,
                    "serverSide": true,
                    "columnDefs": [{"className": "text-center", "targets": "_all"}],
                    "lengthMenu": [[10, 25, 50,100,1000,2000], [10, 25, 50,100,1000,2000]],
                    "ajax": {
                        "url": hide_data,
                        "type": "POST",
                        'data': {
                           'status' :  'ddd'
                        }
                    }
                })

            }
        })
    }

   $.fn.dataTable_ajax_reload_es = function(url_tag,table_tag) {
        return this.each(function() {
            if ($(table_tag).length > 0) {
                // var hide_data = $("#url_datatable").val();
                var hide_data = $(url_tag).val();
               var table =  $(table_tag).DataTable({
                    "language": {"url": url_sitio +"assets/data_spanish.json"},
                    "displayLength": 25,
                    "processing": true,
                    "retrieve": true,
                    "serverSide": true,
                    "columnDefs": [{"className": "text-center", "targets": "_all"}],
                    "lengthMenu": [[10, 25, 50,100,1000,2000], [10, 25, 50,100,1000,2000]],
                    "ajax": {
                        "url": hide_data,
                        "type": "POST",
                        'data': {
                            'status' :  'ddd'
                        }
                    }
                });

                setInterval(
                    function(){
                        table.ajax.reload();
                    },
                    3900
                );

            }
        })
    }

   $.fn.dataTable_ajax_params_es = function(url_tag,table_tag,params1 = '',params2 = '',params3 = '') {
        return this.each(function() {
            if ($(table_tag).length > 0) {
                // var hide_data = $("#url_datatable").val();
                var hide_data = $(url_tag).val();
                $(table_tag).DataTable({
                    "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
                        "<'table-responsive'tr>" +
                        "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
                    "oLanguage": {
                        "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
                        "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                        "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                        "sSearchPlaceholder": "Buscar...",
                        "sLengthMenu": "Resultados :  _MENU_",
                    },
                    "ordering": false,
                    "displayLength": 10,
                    "processing": true,
                    "retrieve": true,
                    "serverSide": true,
                    // scrollY: 200,
                    scrollX:        true,
                    scrollCollapse: true,
                    fixedColumns:   true,
                    // scroller: {
                    //     loadingIndicator: true
                    // },
                    "columnDefs": [{"className": "text-center", "targets": "_all"}],
                    "lengthMenu": [[10, 25, 50,100,1000,2000], [10, 25, 50,100,1000,2000]],
                    "ajax": {
                        "url": hide_data,
                        "type": "POST",
                        'data': {
                           'dato' :  params1,
                           'dato2' :  params2,
                           'dato3' :  params3
                        }
                    }
                })

                setTimeout(() => {
                    // console.log('cargo')
                    // $($.fn.dataTable.tables( true ) ).css('width', '100%');
                    // $($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();
                },600)

            }
        })
    }



    if ($('.pickadate').length > 0) {
        $('.pickadate').pickadate({
            format: 'yyyy-mm-dd',
            formatSubmit: 'yyyymmdd',
            monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            weekdaysFull: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
            selectMonths: true,
            selectYears: 100, // Puedes cambiarlo para mostrar más o menos años
            today: 'Hoy',
            clear: 'Limpiar',
            close: 'Ok',
            labelMonthNext: 'Siguiente mes',
            labelMonthPrev: 'Mes anterior',
            labelMonthSelect: 'Selecciona un mes',
            labelYearSelect: 'Selecciona un año',
        });
    }

    if ($('.fecha_input').length > 0) {
        $('.fecha_input').pickadate({
            format: 'yyyy-mm-dd',
            monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            weekdaysFull: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
            selectMonths: true,
            container: '#root-picker-outlet',
            selectYears: 100, // Puedes cambiarlo para mostrar más o menos años
            today: 'Hoy',
            clear: 'Limpiar',
            close: 'Ok',
            labelMonthNext: 'Siguiente mes',
            labelMonthPrev: 'Mes anterior',
            labelMonthSelect: 'Selecciona un mes',
            labelYearSelect: 'Selecciona un año',
        });
    }

    if ($('#picker_from').length > 0) {
    var from_$input = $('#picker_from').pickadate({
            min: 0,
            format: 'yyyy-mm-dd',
            monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            weekdaysFull: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
            today: 'Hoy',
            clear: 'Limpiar',
            close: 'Ok',
            labelMonthNext: 'Siguiente mes',
            labelMonthPrev: 'Mes anterior',
            labelMonthSelect: 'Selecciona un mes',
            labelYearSelect: 'Selecciona un año',
        }),
        from_picker = from_$input.pickadate('picker');

    var to_$input = $('#picker_to').pickadate({
            format: 'yyyy-mm-dd',
            monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            weekdaysFull: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
            today: 'Hoy',
            clear: 'Limpiar',
            close: 'Ok',
            labelMonthNext: 'Siguiente mes',
            labelMonthPrev: 'Mes anterior',
            labelMonthSelect: 'Selecciona un mes',
            labelYearSelect: 'Selecciona un año',
        }),
        to_picker = to_$input.pickadate('picker');

        if (from_picker.get('value')) {
            to_picker.set('min', from_picker.get('select'));
        }
        if (to_picker.get('value')) {
            from_picker.set('max', to_picker.get('select'));
        }

        from_picker.on('set', function (event) {
            if (event.select) {
                to_picker.set('min', from_picker.get('select'));
            } else if ('clear' in event) {
                to_picker.set('min', false);
            }
        });
        to_picker.on('set', function (event) {
            if (event.select) {
                from_picker.set('max', to_picker.get('select'));
            } else if ('clear' in event) {
                from_picker.set('max', false);
            }
        });
    }



    if ($('.select2').length > 0) {
        $(".select2").select2({
            dropdownParent: $('#select2Modal').parent(),
            placeholder: "Seleccionar",
            width: "100%",
        });
    }

    if ($('.select2_multiple').length > 0) {
        $(".select2_multiple").select2({
            tags: true,
            dropdownParent: $('#select2Modal').parent(),
            // placeholder: "Seleccionar",
            width: "100%",
        });
    }

    $.fn.func_select2 = function (input = '') {
        return this.each(function () {
            $(".select2_multiple").select2({
                tags: true,
                dropdownParent: $('#select2Modal').parent(),
                // placeholder: "Seleccionar",
                width: "100%",
            });
        })
    }


    if ($('.dinero').length > 0) {
        $(".dinero").autoNumeric('init',{
            aSign:' ',mDec: '2'
        });
    }

    $.fn.fecha_range_func = function (inicial,final,block = false) {
        return this.each(function () {
            console.log('rango de fechas')



            let configDate = {

                container: '#root-picker-outlet',
                format: 'yyyy-mm-dd',
                formatSubmit: 'yyyymmdd',
                // numberOfMonths: 3,
                // showButtonPanel: true,
                min: block,
                monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                weekdaysFull: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
                selectMonths: true,
                selectYears: 100, // Puedes cambiarlo para mostrar más o menos años
                today: 'Hoy',
                clear: 'Limpiar',
                close: 'Ok',
                labelMonthNext: 'Siguiente mes',
                labelMonthPrev: 'Mes anterior',
                labelMonthSelect: 'Selecciona un mes',
                labelYearSelect: 'Selecciona un año',
            };

            var from_$input = $(inicial).pickadate(configDate),
                from_picker = from_$input.pickadate('picker');

            var to_$input = $(final).pickadate(configDate),
                to_picker = to_$input.pickadate('picker');


            if (from_picker.get('value')) {
                to_picker.set('min', from_picker.get('select'));
            }
            if (to_picker.get('value')) {
                from_picker.set('max', to_picker.get('select'));
            }

            from_picker.on('set', function (event) {
                if (event.select) {
                    to_picker.set('min', from_picker.get('select'));
                } else if ('clear' in event) {
                    to_picker.set('min', false);
                }
            });
            to_picker.on('set', function (event) {
                if (event.select) {
                    from_picker.set('max', to_picker.get('select'));
                } else if ('clear' in event) {
                    from_picker.set('max', false);
                }
            });
        })
    }

    $.fn.date_range_func = function (inicial,final,format = 'yyyy-mm-dd') {
        return this.each(function () {
            var isUpdating = false; // Bandera para evitar bucles infinitos
            var startDatePicker = $(inicial).pickadate({
                format: format, // El formato de la fecha
                onSet: function() {
                    var startDate = startDatePicker.pickadate('get'); // Obtener la fecha de inicio seleccionada
                    var endDate = $(final).pickadate('picker').get('select'); // Obtener la fecha de fin seleccionada


                    // Si la fecha de inicio es mayor que la fecha de fin, mostrar un error
                    // console.log(endDate)
                    if (endDate != null && startDate > endDate) {
                        console.log("La fecha de inicio no puede ser mayor que la fecha de fin.");
                        isUpdating = true; // Establecer la bandera a verdadero para evitar recursión
                        $(inicial).val(''); // Limpiar el campo de fecha de inicio
                        isUpdating = false; // Restablecer la bandera
                    }

                    // Establecer la fecha mínima de la fecha de fin
                    // Establecer la fecha mínima para la fecha de fin
                    if (!isUpdating) {
                        isUpdating = true; // Evitar que la actualización dispare recursividad
                        $(final).pickadate('picker').set('min', startDate); // Establecer el mínimo
                        isUpdating = false;
                    }
                }
            });

            // Inicializar el campo de fecha de fin
            $(final).pickadate({
                format: format, // El formato de la fecha
                onSet: function() {
                    var endDate = $(final).pickadate('get'); // Obtener la fecha de fin seleccionada
                    var startDate = startDatePicker.pickadate('get'); // Obtener la fecha de inicio seleccionada
                    //
                    // console.log('startDate',startDate)
                    // console.log('endDate',endDate)
                    // // Si la fecha de fin es menor que la fecha de inicio, mostrar un error
                    if ((startDate != '' && endDate != '')  && startDate   && endDate < startDate) {
                        console.log("La fecha de fin no puede ser menor que la fecha de inicio.");
                        isUpdating = true; // Establecer la bandera a verdadero para evitar recursión
                        $(inicial).val(''); // Limpiar el campo de fecha de inicio
                        isUpdating = false; // Restablecer la bandera
                    }
                //
                //     // Establecer la fecha máxima de la fecha de inicio
                    // Establecer la fecha máxima para la fecha de inicio
                    if (!isUpdating) {
                        isUpdating = true; // Evitar que la actualización dispare recursividad
                        $(inicial).pickadate('picker').set('max', endDate); // Establecer el máximo
                        isUpdating = false;
                    }
                }
            });
        })
    }

    $.fn.fecha_func = function (input,formato = 'yyyy-mm-dd',block = false) {
        return this.each(function () {
            $(input).pickadate({
                format: formato,
                min: block,
                monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                weekdaysFull: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
                selectMonths: true,
                selectYears: 100, // Puedes cambiarlo para mostrar más o menos años
                today: 'Hoy',
                clear: 'Limpiar',
                close: 'Ok',
                labelMonthNext: 'Siguiente mes',
                labelMonthPrev: 'Mes anterior',
                labelMonthSelect: 'Selecciona un mes',
                labelYearSelect: 'Selecciona un año',
            });

        })
    }

    $.fn.selected_change = function (input,value = '') {
        return this.each(function () {
               if(value != ''){
                 setTimeout(function () {
                     $(input).val(value).change();
                     console.log(input,value);
                 }, 1500);
               }
        })
    }


    $.fn.fecha_min_func = function (input) {
        return this.each(function () {
            $(input).datepicker({
                // minDate: 0,
                // startDate: '-0m',
                autoclose: true,
                todayHighlight: true,
                format: 'yyyy-mm',
                startView: "months",
                minViewMode: "months",
                language: 'es'
            });

        })
    }

    $.fn.dinero_func = function (input) {
        return this.each(function () {
            $(input).autoNumeric('init',{
                aSign:' ',mDec: '2'
            });
        })
    }

    $.fn.asignacion_porcentaje = function (input) {
        return this.each(function () {
            if ($(input).length > 0) {
                $(input).autoNumeric('init',{
                    aSign:'%',
                    pSign: 's',
                    mDec: '2',
                    vMax : '999.99'
                });
            }

        })
    }

    $.fn.asignacion_numeric = function (input,valor,decimales = 2) {
        return this.each(function () {
            // $(input).autoNumeric('destroy');

            // $(this).setData(input,valor);
            $(input).autoNumeric('init',{
                aSign:' ',mDec: decimales,
            });
            $(input).autoNumeric('set', valor);

        })
    }

    $.fn.setData = function (input,valor) {
        return this.each(function () {
            $(input).val(valor);
        })
    }

    $.fn.editorText = function (input) {
        return this.each(function () {
            // CKEDITOR.replace('detail');
            var editor = CKEDITOR.replace(input, {
                versionCheck: false
            });



            // CKEDITOR.replace( input );

            // ClassicEditor
            //     .create( document.querySelector( input ) )
            //     .then( editor => {
            //         console.log( editor );
            //     } )
            //     .catch( error => {
            //         console.error( error );
            //     } );
            // $(input).summernote({
            //     height: 290,
            //     minHeight: null,
            //     maxHeight: null,
            //     focus: !1
            // })
        })
    }

    $.fn.tinyText = function (input) {
        return this.each(function () {

            var emailBodyConfig = {
                selector: '.tinymce-body',
                menubar: false,
                inline: true,
                plugins: [
                    'link',
                    'lists',
                    'powerpaste',
                    'autolink',
                    'tinymcespellchecker'
                ],
                toolbar: [
                    'undo redo | bold italic underline | fontselect fontsizeselect',
                    'forecolor backcolor | alignleft aligncenter alignright alignfull | numlist bullist outdent indent'
                ],
                valid_elements: 'p[style],strong,em,span[style],a[href],ul,ol,li',
                valid_styles: {
                    '*': 'font-size,font-family,color,text-decoration,text-align'
                },
                powerpaste_word_import: 'clean',
                powerpaste_html_import: 'clean',
            };

            tinymce.init(emailBodyConfig);
        })
    }

    $.fn.inputdate_old = function (input) {
        return this.each(function () {
            const today = new Date().toISOString().slice(0, 16);
            document.getElementsByName(input)[0].min = today;
        })
    }

    $.fn.datehour_func = function (input) {
        return this.each(function () {
            $(input).pickatime({
                format: 'h:i A',
                container: '#root-picker-outlet',
                today: 'Hoy',
                clear: 'Limpiar',
                close: 'Ok',
            });
        })
    }

    $.fn.numeros_func = function (input) {
        return this.each(function () {
            $(input).keypress(function (e) {
                if (e.which != 8 && e.which != 13 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    $(this).mensaje_alerta(1,"Solo se aceptan numeros");
                    return false;
                }
            });
        })
    }

    $.fn.form_json = function (form = [],save = '') {
        return this.each(function () {
            var obj = $(form).serializeToJSON({
                // serialize the form using the Associative Arrays
                associativeArrays: true,

                // convert "true" and "false" to booleans true / false
                parseBooleans: true,
                parseFloat: {

                    // the value can be a string or function
                    condition: undefined,

                    // auto detect NaN value and changes the value to zero
                    nanToZero: true,

                    // return the input value without commas
                    getInputValue: function($input){
                        return $input.val().split(",").join("");
                    }
                }
            });
            let conv = JSON.stringify(obj);
            let elemen = $(save);
            (elemen.length > 0) ? elemen.val(conv) : '';

        })
    }


    $.fn.ocultar_mostrar = function (valores_mostrar = [],id_ocultar = '',input_required = '',callback = null) {
        return this.each(function () {
            let valor = $(this).val();
            let validar = valores_mostrar.includes(valor);

            if(callback != null){
                callback();
            }

            console.log('------validar', valores_mostrar);
            console.log('------validar', validar);
            console.log('------valor', valor);
            console.log('------valor', id_ocultar);
            if(validar){
                //mostrar
                // $(id_ocultar).attr( "style", "display: inline-flex !important;" );
                //
                //validar cual de los dos elementos utilizar
                // console.log('------id_ocultar', id_ocultar)
                // if($(id_ocultar).attr("class").includes('d-none')){
                    $(id_ocultar).removeClass('d-none');
                // }else{
                //     $(id_ocultar).show();
                // }
                // $(id_ocultar).removeClass("d-none");
                (input_required != '') ? $(input_required).attr('required',true).attr('disabled',false).change() : '';

            }else{
                //ocultar
                // $(id_ocultar).attr( "style", "display: none !important;" )
                // if($(id_ocultar).attr("class").includes('d-none')){
                    $(id_ocultar).addClass("d-none");
                // }else{
                //     $(id_ocultar).hide();
                // }
                (input_required != '') ? $(input_required).attr('required',false).attr('disabled',true).change() : '';
            }

        })
    }

    $.fn.selected_func = function (input,valor) {
        return this.each(function () {
            $(input).val(valor).change();
        })
    }

    $.fn.transformar_data = function (obj) {
            // return "abc";
        // return this.each(function () {
            var jsn = {};
            $.each(obj, function() {
                if (jsn[this.name]) {
                    if (!jsn[this.name].push) {
                        jsn[this.name] = [jsn[this.name]];
                    }
                    jsn[this.name].push(this.value || '');
                } else {
                    jsn[this.name] = this.value || '';
                }
            });
            return jsn;
        // })
    }
    $.fn.reactivar_select_modal = function (select,div) {
        return this.each(function () {
            $(this).bind('hidden.bs.modal', function () {
                $(this).select2_func(select,div);
            });
        })
    }



    $.fn.select_change_func = function (valor = '') {
        return this.each(function () {
            // console.log('change events')
            // $("form#frm_data .form-control").trigger('change');

            // for(let i = 0; i < size; i++){
            //     console.log($("form#frm_data .form-control")[i]);
            //     $("form#frm_data .form-control")[i].val(1).change();
            //     $( this).change();

            // }
            // console.log(size);
            // $("form#frm_data .form-control").each(function (e) {
            //     // console.log(e)
            //         // $(this).val('').change()
            //     $( this).change();
            //     console.log('change');
            // });
           // setTimeout(function () {
           //     // console.log($(this))
           //
           //     // $(valor).each(function(){
           //     //     console.log($(this))
           //     //
           //     //     // $(this).change()
           //     // })
           // }, 1600);
                // if(valor.length > 0){
                //     valor.forEach(
                //         function (val) {
                //             $(`${val}`).change();
                //         }
                //     )
                // }
        })
    }

    $.fn.select2_func = function (input,valor = 'select2Modal') {
        return this.each(function () {
            $(input).select2({
                dropdownParent: $(`#${valor}`).parent(),
                placeholder: "Buscar...",
                allowClear: true,
                width: "100%",
            });
        })
    }

    $.fn.select2_selected_func = function (hijo,url_dell,id = '',seleccion = '') {
        return this.each(function () {
                $.ajax({
                    async: false,
                    cache: false,
                    type: 'post',
                    dataType: 'json',
                    data: "params[entidad]="+id,
                    url: url_dell,
                    success: function (result) {
                        result.unshift({id: '', text: ' [SELECCIONAR] '});
                        $(this).select2_manual_func(hijo,result);
                        let validate = result.filter(r => r.id == seleccion).length > 0;
                       setTimeout(function () {
                           if(seleccion != '' && validate){
                               $(hijo).val(seleccion).trigger('change');
                           }
                       },1000)
                    }
                })

        })
    }



    $.fn.select2_manual_func = function (input = '',dataEvent = []) {
        return this.each(function () {
            $("#municipio_alcaldia").html('').select2({data: dataEvent});
        })
    }

    $.fn.select2_ajax_func = function (input,url,param = {}) {
        return this.each(function () {
            //search all
            if ($(input).length > 0) {
                $(input).select2({
                    placeholder: "Buscar",
                    ajax: {
                        type: "post",
                        // allowClear: true,
                        // multiple: false,
                        dropdownParent: $('#select2Modal').parent(),
                        url: $(url).val(),
                        dataType: 'json',
                        // delay: 250,
                        data: function(params) {
                            return {
                                q: params.term, // search term,
                                params : {...param},
                                page: params.page
                            }

                        },
                        processResults: function (response) {
                            console.log('process')
                            return {
                                results: response
                            };
                        },
                        cache: true

                    }
                });
            }
        })
    }



    $.fn.datatable_export_file_func = function (aa,input) {
        return this.each(function () {
            $(input).DataTable({
                dom: 'Bfrt<"col-md-6 inline"i> <"col-md-6 inline"p>',


                buttons: {
                    dom: {
                        container:{
                            tag:'div',
                            className:'flexcontent'
                        },
                        buttonLiner: {
                            tag: null
                        }
                    },




                    buttons: [


                        {
                            extend:    'copyHtml5',
                            text:      '<i class="fa fa-clipboard"></i>Copiar',
                            title:'Titulo de tabla copiada',
                            titleAttr: 'Copiar',
                            className: 'btn btn-app export barras',
                            exportOptions: {
                                columns: [ 0, 1 ]
                            }
                        },

                        {
                            extend:    'pdfHtml5',
                            text:      '<i class="fa fa-file-pdf-o"></i>PDF',
                            title:'Titulo de tabla en pdf',
                            titleAttr: 'PDF',
                            className: 'btn btn-app export pdf',
                            exportOptions: {
                                columns: [ 0, 1 ]
                            },
                            customize:function(doc) {

                                doc.styles.title = {
                                    color: '#4c8aa0',
                                    fontSize: '30',
                                    alignment: 'center'
                                }
                                doc.styles['td:nth-child(2)'] = {
                                    width: '100px',
                                    'max-width': '100px'
                                },
                                    doc.styles.tableHeader = {
                                        fillColor:'#4c8aa0',
                                        color:'white',
                                        alignment:'center'
                                    },
                                    doc.content[1].margin = [ 100, 0, 100, 0 ]

                            }

                        },

                        {
                            extend:    'excelHtml5',
                            text:      '<i class="fa fa-file-excel-o"></i>Excel',
                            title:'Titulo de tabla en excel',
                            titleAttr: 'Excel',
                            className: 'btn btn-app export excel',
                            exportOptions: {
                                columns: [ 0, 1 ]
                            },
                        },
                        {
                            extend:    'csvHtml5',
                            text:      '<i class="fa fa-file-text-o"></i>CSV',
                            title:'Titulo de tabla en CSV',
                            titleAttr: 'CSV',
                            className: 'btn btn-app export csv',
                            exportOptions: {
                                columns: [ 0, 1 ]
                            }
                        },
                        {
                            extend:    'print',
                            text:      '<i class="fa fa-print"></i>Imprimir',
                            title:'Titulo de tabla en impresion',
                            titleAttr: 'Imprimir',
                            className: 'btn btn-app export imprimir',
                            exportOptions: {
                                columns: [ 0, 1 ]
                            }
                        },
                        {
                            extend:    'pageLength',
                            titleAttr: 'Registros a mostrar',
                            className: 'selectTable'
                        }
                    ]


                }
            });
            $(".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel").addClass("btn btn-primary mr-1");

        })
    }



    $.fn.datatable_func = function (taghtml,tipo = 'normal',url_tag  = '') {
        return this.each(function () {

            if(tipo == 'normal'){
            $(taghtml).DataTable({ "language": {"url": url_sitio +"assets/data_spanish.json"},
                "displayLength": 10,
                "lengthMenu": [[10, 25, 50,100,1000,2000], [10, 25, 50,100,1000,2000]],
            }
            );
            }else{
                //table ajax
                var hide_data = $(url_tag).val();
                if($(url_tag).length > 0){
                    $(taghtml).DataTable({
                        "language": {"url": url_sitio +"assets/data_spanish.json"},
                        "displayLength": 10,
                        "processing": false,
                        "retrieve": true,
                        "serverSide": true,
                        "columnDefs": [{"className": "text-center", "targets": "_all"}],
                        "lengthMenu": [[10, 25, 50,100,1000,2000], [10, 25, 50,100,1000,2000]],
                        "ajax": {
                            "url": hide_data,
                            "type": "POST",
                            'data': {
                                'status' :  'ddd'
                            }
                        }
                    })
                }

                setTimeout(() => {
                    $($.fn.dataTable.tables( true ) ).css('width', '100%');
                    $($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();
                },600)
            }
        })
    }

    $.fn.datatable_export_func = function (taghtml,tipo = 'normal',url_tag  = '', callback = {'data' : 'data'}) {
        return this.each(function () {

            if(tipo == 'normal'){
                $(taghtml).DataTable({ "language": {"url": url_sitio +"assets/data_spanish.json"},
                        "displayLength": 10,
                        "lengthMenu": [[10, 25, 50,100,1000,2000], [10, 25, 50,100,1000,2000]],
                    }
                );
            }else{
                //table ajax
                var hide_data = $(url_tag).val();
                if($(url_tag).length > 0){
                    $(taghtml).DataTable({
                        "language": {"url": url_sitio +"assets/data_spanish.json"},
                        "displayLength": 10,
                        "processing": false,
                        "retrieve": true,
                        "serverSide": true,
                        "columnDefs": [{"className": "text-center", "targets": "_all"}],
                        "lengthMenu": [[10, 25, 50,100,1000,2000], [10, 25, 50,100,1000,2000]],
                        dom: '<"dt-top-container"<l><"dt-center-in-div"B><f>r>t<"dt-filter-spacer"f><ip>',
                        buttons: ["copy", "csv", "excel", "pdf"],
                        "ajax": {
                            "url": hide_data,
                            "type": "POST",
                            'data': callback
                        }
                    })


                    setTimeout(() => {
                        $($.fn.dataTable.tables( true ) ).css('width', '100%');
                        $($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();
                    },600)

                }




            }
        })
    }
    $.fn.datatable_ajax_dinamic_fields_func = function (taghtml,url_tag  = '', callback = {'data' : 'data'}) {
        return this.each(function () {

            var hide_data = $(url_tag).val();
            if($(url_tag).length > 0){
                $(taghtml).DataTable({
                    "language": {"url": url_sitio +"assets/data_spanish.json"},
                    "displayLength": 10,
                    "processing": false,
                    "retrieve": true,
                    "serverSide": true,
                    "columnDefs": [{"className": "text-center", "targets": "_all"}],
                    "lengthMenu": [[10, 25, 50,100,1000,2000], [10, 25, 50,100,1000,2000]],
                    "ajax": {
                        "url": hide_data,
                        "type": "POST",
                        'data': callback
                    }
                })


                setTimeout(() => {
                    $($.fn.dataTable.tables( true ) ).css('width', '100%');
                    $($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();
                },600)

            }
        })
    }


    $.fn.upload_file = function () {
        return this.each(function () {
            $('.custom-file input').change(function (e) {
                var files = [];
                for (var i = 0; i < $(this)[0].files.length; i++) {
                    let nn = $(this)[0].files[i].name;
                    let nombre = (nn.length < 21) ? nn : nn.substring(0, 21) + '...';
                    files.push(nombre);
                }
                $(this).next('.custom-file-label').html(files.join(', '));
            });
        })
    }

    if ($('.panel_form').length > 0) {

            $(".panel_form").block({
                message: '<i class="fas fa-spin fa-sync text-white"></i>',
                // timeout: 2000, //unblock after 2 seconds
                overlayCSS: {
                    backgroundColor: '#000',
                    opacity: 0.5,
                    cursor: 'wait'
                },
                css: {
                    border: 0,
                    padding: 0,
                    backgroundColor: 'transparent'
                }
            });

    }


    $.fn.panel_unblock = function () {
        return this.each(function () {
            $(".panel_form").unblock();
        })
    }


    if ($('.numeros').length > 0) {
        $(".numeros").keypress(function (e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                $(this).mensaje_alerta(1,"Solo se aceptan numeros");
                return false;
            }
        });
    }


    $.fn.mensaje_alerta = function (tipo = '', descripcion = '') {
        return this.each(function () {

            if (tipo != '' && descripcion != '') {

                switch (tipo) {
                    case 1:
                        tipo = "error";
                        break;
                    case 2:
                        tipo = "success";
                        break;
                    case 3:
                        tipo = "info";
                        break;
                    case 4:
                        tipo = "warning";
                        break;
                    default:
                        tipo = "warning";
                        break;
                }
            }
            else {
                tipo = "warning";
                descripcion = "Error de Aplicacion";
            }

            Lobibox.notify("" + tipo + "", {
                title: project_title,
                msg: "" + descripcion + "",
            });
        })
    }


    // delegate calls to data-toggle="lightbox"
    $(document).delegate('*[data-toggle="lightbox"]:not([data-gallery="navigateTo"])', 'click', function(event) {
        event.preventDefault();
        return $(this).ekkoLightbox({
            onShown: function() {
                if (window.console) {
                    return console.log('Checking our the events huh?');
                }
            },
            onNavigate: function(direction, itemIndex) {
                if (window.console) {
                    return console.log('Navigating ' + direction + '. Current item: ' + itemIndex);
                }
            }
        });
    });


    $('#open-youtube').click(function(e) {
        e.preventDefault();
        $(this).ekkoLightbox();
    });
    // navigateTo
    $(document).delegate('*[data-gallery="navigateTo"]', 'click', function(event) {
        event.preventDefault();
        var lb;
        return $(this).ekkoLightbox({
            onShown: function() {
                lb = this;
                $(lb.modal_content).on('click', '.modal-footer a', function(e) {
                    e.preventDefault();
                    lb.navigateTo(2);
                });
            }
        });
    });

    $.fn.create_after_data = function(module,dataObject) {
        return this.each(function(){

            const data = {
                module
            }
            $(this).call_not_message(data,"auto_save_before",function(re){
                dataObject['data1'] = re.response.crypt
                console.log(dataObject)
                $(this).forms_modal(dataObject);
            });

            return false;
        })
    }

    $.fn.forms_modal = function(dataObject) {
        return this.each(function(){
            var url_form = $("#url_modals").val();
            //data titile modal
            if(typeof dataObject.title  !== 'undefined'){
                $("#label_modal").text(dataObject.title);
            }else{
                $("#label_modal").text('');
            }


            if(Object.keys(dataObject).length >= 1){
                $.post(url_form,dataObject,function(data){

                    // $('#modal_data').modal('hide');
                    $(".modal-body-view").empty().append(data);
                    $('#modal_principal').modal('show');
                });
            }else{
                //no se puede procesar
                $(this).mensaje_alerta(1,  "Error al cargar la vista");
            }

            return false;
        })
    }

    $.fn.forms_modal2 = function(dataObject) {
        return this.each(function(){
            var url_form = $("#url_modals").val();
            //data titile modal
            if(typeof dataObject.title  !== 'undefined'){
                $("#label_modal2").text(dataObject.title);
            }else{
                $("#label_modal2").text('');
            }


            if(Object.keys(dataObject).length >= 1){
                $.post(url_form,dataObject,function(data){

                    // $('#modal_data').modal('hide');
                    $(".modal-body-view2").empty().append(data);
                    $('#modal_principal2').modal('show');
                });
            }else{
                //no se puede procesar
                $(this).mensaje_alerta(1,  "Error al cargar la vista");
            }
            return false;
        })
    }

    $.fn.clear_modal_view = function(size = 'modal-500',tag = '.modal-dialog') {
        return this.each(function(){
            $(`${tag}`).removeClass("modal-500");
            $(`${tag}`).removeClass("modal-600");
            $(`${tag}`).removeClass("modal-700");
            $(`${tag}`).removeClass("modal-800");
            $(`${tag}`).removeClass("modal-900");
            $(`${tag}`).removeClass("modal-1000");
            $(`${tag}`).removeClass("modal-1100");
            $(`${tag}`).removeClass("modal-1200");
            $(`${tag}`).removeClass("modal-1300");
            $(`${tag}`).removeClass("modal-1400");
            $(`${tag}`).removeClass("modal-1500");
            $(`${tag}`).removeClass("modal-1600");
            $(`${tag}`).removeClass("modal-1800");
            $(`${tag}`).removeClass("modal-1900");
            $(`${tag}`).removeClass("modal-fullscreen");
            $(`${tag}`).addClass(size);
            return false;
        })
    }

    $.fn.check_disabled = function(input,disabled) {
        return this.each(function(){
            if($(input).is(":checked")){
                $(disabled).attr("disabled",false);
            }else{
                $(disabled).attr("disabled",true);
            }
            return false;
        })
    }


    $.fn.custom_file = function() {
        return this.each(function(){
        $('.custom-file input').change(function (e) {
            var files = [];
            for (var i = 0; i < $(this)[0].files.length; i++) {
                let nn = $(this)[0].files[i].name;
                let nombre = (nn.length < 21) ? nn : nn.substring(0, 21) + '...';
                files.push(nombre);
            }
            $(this).next('.custom-file-label').html(files.join(', '));
        });
      })
    }

 if($(".countdown").length > 0) {
     $('[data-countdown]').each(function () {
         var $this = $(this), finalDate = $(this).data('countdown');
         $this.countdown(finalDate, function (event) {
             $this.html(event.strftime('%H:%M:%S'));
         });
     });
 }

})



