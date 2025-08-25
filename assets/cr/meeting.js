$(document).ready(function() {

    if ($('.porcentaje').length > 0) {
            $(".porcentaje").autoNumeric('init',{
                aSign:'%',
                pSign: 's',
                mDec: '2',
                vMax : '999.99'
            });

    }

    if ($('.sin_porcentaje').length > 0) {
            $(".sin_porcentaje").autoNumeric('init',{
                aSign:'',
                pSign: 's',
                mDec: '2',
                vMax : '999.99'
            });
    }

    $(this).fecha_func('.fecha','yyyy-mm-dd');

    $.fn.printPDF = function () {
        return this.each(function () {

            window.jsPDF = window.jspdf.jsPDF;

            var doc = new jsPDF('l', 'mm', [215.9, 355.6]);

            // Source HTMLElement or a string containing HTML.
            var elementHTML = document.querySelector("#printPDF");
            const contenidoDiv = document.getElementById('printPDF');


            // Definir márgenes en el PDF
            const margenIzq =0;
            const margenDerecho = 0;
            const margenArriba = 0;
            const margenAbajo = 37;

            // Ancho y alto disponibles en el PDF con márgenes
            const anchoDisponible = 215.9 - margenIzq - margenDerecho;  // Oficio en horizontal
            const altoDisponible = 355.6 - margenArriba - margenAbajo;  // Oficio en vertical

            // Ajustes para el contenido responsivo
            const windowWidthSimulado = 1024; // Tamaño simulado del ancho para el PDF (ajustable según sea necesario)

            // Ajuste para el contenido con el valor de scale
            const scale = 0.34;  // Ya lo tienes ajustado a tu preferencia

            // Ocultar los botones antes de generar el PDF
            // document.getElementById("boton1").style.display = "none";
            // document.getElementById("boton2").style.display = "none";

            doc.html(contenidoDiv, {
                callback: function (doc) {
                    let contentHeight = doc.internal.pageSize.height;
                    let currentHeight = margenArriba;  // Empieza en el margen superior

                    // Verificar si el contenido con el scale se ajusta a la página
                    // Ajuste dinámico con la escala aplicada
                    let totalHeight = contenidoDiv.offsetHeight * scale;

                    // Si el contenido excede el alto disponible, agregamos una nueva página
                    if (currentHeight + totalHeight > altoDisponible) {
                        doc.addPage();  // Si no cabe, agrega una nueva página
                        currentHeight = margenArriba;  // Resetea la posición Y al margen superior
                    }


                    doc.save('meeting.pdf');

                    // document.getElementById("boton1").style.display = "inline";
                    // document.getElementById("boton2").style.display = "inline";
                },

                // margin: [2, 2, 2, 2],
                // autoPaging: 'text',
                // width: 210, //target width in the PDF document
                // windowWidth: 675 //window width in CSS pixels
                x: margenIzq,
                y: margenArriba,
                width: anchoDisponible,  // Ajusta al ancho disponible con márgenes
                height: altoDisponible,  // Ajusta al alto disponible, con márgenes
                autoSize: true,  // Permite que el contenido se ajuste automáticamente
                windowWidth: windowWidthSimulado,  // Simulamos un ancho fijo para el contenido
                html2canvas: {
                    logging: false,
                    dpi: 300,  // Mejora la calidad de las imágenes
                    letterRendering: true,  // Mejora el renderizado de las letras
                    scale: 0.33,  // Ajustamos la escala (0.5 reduce el contenido)
                    useCORS: false,  // Asegura que los recursos de imágenes se carguen correctamente
                },
            });
        });
    }

    $.fn.meeting_calcule = function () {
        return this.each(function () {

            let input_1 = $('#input_1').val();
            let input_2 = $('#input_2').val();
            let pvar =   $('#input_3')
            let result  = 0;

            let cleanNumber1 = parseFloat(input_1.replace('%', ''));
            let cleanNumber2 = parseFloat(input_2.replace('%', ''));


            if (!isNaN(cleanNumber1) && !isNaN(cleanNumber2) && cleanNumber1 > 0 && cleanNumber2 > 0) {
                result = (Math.round(cleanNumber2*cleanNumber1) / 100).toFixed(2);
            } else {
                result = 0
            }

            pvar.val(result)

        });
    }

    $.fn.generate_meeting = function () {
        return this.each(function () {
            if($('.fecha').length > 0){
                let fecha = $('.fecha').val();
                let events1 = $('#events1').empty();
                let events4 = $('#events4').empty();
                let communication = $('#comunication_table').empty();
                if(fecha.length == 10){
                    $(this).simple_call_text({fecha},'url_call',false,function(result){
                        let resp = result.response;
                        if(resp.success == 1) {
                             let data = resp.data;

                             $('#entrada').val(data.meeting.m_entry);
                             $('#salida').val(data.meeting.m_exit);
                             $('#cierre').val(data.meeting.m_occupancy_closing + '%');
                             $('#tarifa').val(data.meeting.m_rate_pm);
                             $('#revpar').val(data.meeting.m_revpar);

                             //Hoy, Mañana, 2 días
                            $('#opcations_0').val(data.opcations['0'].info + '%');
                            $('#opcations_1').val(data.opcations['1'].info + '%');
                            $('#opcations_2').val(data.opcations['2'].info + '%');


                            //kitcheng
                            $('#kitcheng_0').val(data.days['0'].info);
                            $('#kitcheng_1').val(data.days['1'].info);
                            $('#kitcheng_2').val(data.days['2'].info);
                            $('#kitcheng_3').val(data.days['3'].info);
                            $('#kitcheng_4').val(data.days['4'].info);
                            $('#kitcheng_5').val(data.days['5'].info);
                            $('#kitcheng_6').val(data.days['6'].info);


                            data.events2.forEach(function (event) {
                                let date1 = convertTo12HourFormat(event.hour1);
                                let date2 = convertTo12HourFormat(event.hour2);
                                let template = `
                                <tr>
                                    <td>${event.name}</td>
                                    <td>${event.b_cpax}</td>
                                    <td>${event.name_room}</td>
                                    <td>${date1}</td>
                                    <td>${date2}</td>
                                    <td></td>
                                </tr>
                                `;
                                events1.append(template);
                            })


                            data.events4.forEach(function (event) {
                                let template = `
                                <tr>
                                    <td>${event.name}</td>
                                    <td>${event.b_client_name}</td>
                                    <td>${event.b_cpax}</td>
                                    <td>${event.bd_description}</td>
                                </tr>
                                `;
                                events4.append(template);
                            })


                            data.communication.forEach(function (event) {
                                let template = `
                                <tr>
                                    <td> <textarea name="communication[]" class="form-control border-dark" readonly rows="10" style="font-size: 20px !important;">${event.mc_info}</textarea></td>
                                `;
                                communication.append(template);
                            })


                        }else{

                            $('#entrada').val('');
                            $('#salida').val('');
                            $('#cierre').val('');
                            $('#tarifa').val('');
                            $('#revpar').val('');

                            //Hoy, Mañana, 2 días
                            $('#opcations_0').val('');
                            $('#opcations_1').val('');
                            $('#opcations_2').val('');


                            //kitcheng
                            $('#kitcheng_0').val('');
                            $('#kitcheng_1').val('');
                            $('#kitcheng_2').val('');
                            $('#kitcheng_3').val('');
                            $('#kitcheng_4').val('');
                            $('#kitcheng_5').val('');
                            $('#kitcheng_6').val('');

                            // $(this).mensaje_alerta(1, resp.msg);
                        }

                    },true,true);
                }

            }

        });
    }

    $.fn.delete_tr = function (id) {
        return this.each(function () {
            $(`#comunication_table tr[data-id="${id}"]`).remove()
        });
    }

    $.fn.add_communication = function () {
        return this.each(function () {
            const table = $("#comunication_table");

                let id = Date.now().toString(36) + Math.random().toString(36).substr(2, 9);

                const template = `
                        <tr data-id="${id}">
                            <td class='m-0 p-0'>
                                <textarea name="communication[]" class="form-control border-dark" rows="10" style="font-size: 20px !important;"></textarea>
                            </td>
                            <td class="deleteAll"><a class="btn btn-danger btn-sm" href="javascript:void(0)" onclick="$(this).delete_tr('${id}')"><i class="fas fa-times"></i></a></td>
                        </tr>
                        `;
                table.append(template);


        });
    }


    function convertTo12HourFormat(time) {
        // Separar horas y minutos
        let [hours, minutes] = time.split(':');
        hours = parseInt(hours, 10); // Convertir a número

        // Determinar AM o PM
        const period = hours >= 12 ? 'PM' : 'AM';

        // Convertir horas al formato de 12 horas
        hours = hours % 12 || 12; // Si es 0, cambiar a 12

        // Retornar la hora formateada
        return `${String(hours).padStart(2, '0')}:${minutes} ${period}`;
    }


    //call firt day
    $(this).generate_meeting();
})