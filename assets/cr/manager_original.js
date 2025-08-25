$(document).ready(function() {




    function formatTime(timeString) {
        const [hourString, minute] = timeString.split(":");
        const hour = +hourString % 24;
        return (hour % 12 || 12) + ":" + minute + (hour < 12 ? " AM" : " PM");
    }

    $.fn.close_event = function(id = '') {
        return this.each(function() {
            if(id == ''){
                $(this).mensaje_alerta(1, 'Lo Sentimos el evento no se puede cerrar');
            }else{
                Swal.fire({
                    title: '¿Estas seguro de cerrar el evento?',
                    text: "¡No podras revertir esto!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: 'Si, Cerrar!'
                }).then((result) => {
                    if (result.value) {

                        const dataR = {id};

                        $(this).simple_call_text(dataR,'url_event_lock',false,(err) =>{
                            window.location.reload();
                            // return true;
                        },true);

                    }
                })

            }
        })
    }

    $.fn.question_events = function(id ='')  {
        return this.each(function() {
            if(id != '') {
                Swal.fire({
                    title: "Fin del proceso",
                    text: "¡Si aceptas esto se convertira un evento!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Convertir evento",
                    cancelButtonText: "Cancelar",
                }).then((result) => {
                    console.log(result)
                    if(result.value){

                        Swal.fire({
                            position: 'center-center',
                            title: 'Se esta procesando la solicitud',
                            timerProgressBar: false,
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            hideOnContentClick: false,
                            closeClick: false,
                            helpers: {
                                overlay: { closeClick: false }
                            }
                        })
                        setTimeout(function(){
                            const dataR = {id,status: 1};
                            $(this).simple_call_text(dataR,'url_event_lock',false,(err) =>{
                                window.location.reload();
                                // return true;
                            },true);
                        },1000)


                    }
                });

            }
        })
    }

    $.fn.delete_events = function(id ='')  {
        return this.each(function() {
            if(id != '') {
                Swal.fire({
                    title: "Deseas cancelar el evento?",
                    html: `¡Si aceptas no podras modificarlo en el futuro!<br><br>
                     <textarea class="form-control" placeholder="Observación de la cancelación" id="observation" rows="5"></textarea>
                    `,
                    showCancelButton: true,
                    confirmButtonText: "Cancelar Evento",
                    cancelButtonText: "Regresar",
                }).then((result) => {
                    console.log(result)
                    if(result.value){
                        const dataR = {
                            id,
                            comment : $("#observation").val()
                        };
                        $(this).simple_call_text(dataR,'url_event_cancel',false,(err) =>{
                            window.location.reload();
                            // return true;
                        },true);
                    }
                });

            }
        })
    }

    $.fn.cancel_events = function(id ='')  {
        return this.each(function() {
            if(id != '') {
                Swal.fire({
                    title: "Deseas cancelar el evento?",
                    html: `¡Si aceptas no podras modificarlo en el futuro!<br><br>
                     <textarea class="form-control" placeholder="Observación de la cancelación" id="observation" rows="5"></textarea>
                    `,
                    showCancelButton: true,
                    confirmButtonText: "Cancelar Evento",
                    cancelButtonText: "Regresar",
                }).then((result) => {
                    console.log(result)
                    if(result.value){
                        const dataR = {
                            id,
                            comment : $("#observation").val()
                        };
                        $(this).simple_call_text(dataR,'url_event_cancel',false,(err) =>{
                            window.location.reload();
                            // return true;
                        },true);
                    }
                });

            }
        })
    }
    $.fn.cancel_events = function(id ='')  {
        return this.each(function() {
            if(id != '') {
                Swal.fire({
                    title: "Deseas cancelar el evento?",
                    html: `¡Si aceptas no podras modificarlo en el futuro!<br><br>
                     <textarea class="form-control" placeholder="Observación de la cancelación" id="observation" rows="5"></textarea>
                    `,
                    showCancelButton: true,
                    confirmButtonText: "Cancelar Evento",
                    cancelButtonText: "Regresar",
                }).then((result) => {
                    console.log(result)
                    if(result.value){
                        const dataR = {
                            id,
                            comment : $("#observation").val()
                        };
                        $(this).simple_call_text(dataR,'url_event_cancel',false,(err) =>{
                            window.location.reload();
                            // return true;
                        },true);
                    }
                });

            }
        })
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


    $.fn.calendar_event = function(data) {
        return this.each(function() {
            let html = $("#media").empty();
            let response = data.response;
            if(response.sucess = 1) {


                response.data.forEach((item) => {
                    console.log(item)
                    let f_init = convertTo12HourFormat(item.date1);
                    let f_fin = convertTo12HourFormat(item.date2);

                    let btn_color  = 'border-primary';
                    let icon_color = '#2953E8';
                    let icon_reload = '';
                    if(item.modifid == 1){
                        btn_color  = 'border-danger';
                        icon_color = '#FF4C41';
                         icon_reload = 'icon_reloading';
                    }


                    const template = `
                                <div class="media pb-3   mb-1">
                                    <div class="media-body">
                                        <p class="fs-16 mb-2"><b>Hora E.:</b> ${f_init}</p>
                                        <p class="fs-16 mb-2"><b>Creador X:</b> ${item.u_name}</p>
                                        <p class="fs-16 mb-2"><b>Fecha Creación:</b> ${item.atcreate}</p>
                                        <p class="fs-16 mb-2"><b>Tipo Evento:</b> ${item.type_event}</p>
                                        <p class="fs-16 mb-2"><b>Vendedor:</b> ${item.seller}</p>
                                        <p class="fs-16 mb-2"><b>Codigó:</b> ${item.code}</p>
                                        <p class="fs-16 mb-2"><b>C. Pax:</b> ${item.count}</p>
                                        <p class="fs-16 mb-2"><b>Salon:</b> ${item.name_room}</p>
                                        /*<p class="fs-16 mb-2"><b>Observación:</b><br> ${item.observation}</p>*/
                                        <h6 class="fs-16 font-w600"><span class="text-black">${item.name}</span></h6>
                                    </div>
                                </div>
                                 <div class="row pb-3">
                                        <div class="col btn-group">
                                            <button type="button" class="btn btn-success btn-sm"  onclick='$(this).forms_modal({"page" : "events_view","data1" : "${item.id}","data2" : "${item.id_day}","data3" : 1,"title" : "Detalle de Evento A&B"})'>A&B</button>
                                            <button type="button" class="btn btn-info btn-sm"  onclick='$(this).forms_modal({"page" : "events_view","data1" : "${item.id}","data2" : "${item.id_day}","data3" : 2,"title" : "Detalle de Evento Cocina"})'>Cocina</button>
                                        </div>
                                 </div>
                                  <div class="row pb-3   border-bottom mb-5">
                                        <div class="col">
                                            <button type="button" class="btn btn-primary w-100 btn-sm"  onclick='$(this).forms_modal({"page" : "profome_view","data1" : "${item.idc}","title" : "Detalle de Evento Recepción"})'>Recepción</button>
                                        </div>
                                 </div>
                        `;
                    html.append(template);
                });
            }
        })
    }


    if($('#calendar').length > 0) {

        var calendarEl = document.getElementById('calendar');

        window.calendar = new FullCalendar.Calendar(calendarEl, {
            editable: false,
            selectable: true,
            // buttonIcons: true,
            // weekNumbers: false,
            headerToolbar: {
                start: 'title',
                center: '',
                end: 'prevYear,prev,next,nextYear'
            },
            locale: 'es',
            eventClick: function(e, t, n) {
                // o.onEventClick(e, t, n)
                // alert("eventClick")
                let event = e.event;
                $(this).simple_call_text({id: event.id,type: e.event.groupId, day: moment(event.start).format("YYYY-MM-DD")},'url_event_day',false,(data) =>{
                    // return true;
                    $("#event_value").text(moment(event.start).format("DD-MM-YYYY"))
                    $(this).calendar_event(data);
                },true);

            },
            eventSources: [{
                url:  $("#url_event").val(),
                method: 'POST',
                extraParams: {
                    'send' : 'send'
                }
                }
            ],
            eventSourceSuccess: function (content, xhr) {
                let result = content.response;
                let data = [];
                let color = [];
                if(result.success == 1){

                    const typeE = [
                        'RE: ',
                        'RE: ',
                        'PF: ',
                        '',
                    ];


                    result.data.forEach(function (item) {

                        //clean color
                        if(color[item.r_id] == undefined) {
                            color[item.r_id] = (Math.random() * 0xFFFFFF << 0).toString(16).padStart(6, '0');
                        }
                       data.push({
                           id: item.r_id,
                           title: typeE[item.module-1] +' '+ item.r_name,
                           start: item.pf_date,
                           color: item.r_color,
                           groupId: item.module
                       });
                    });
                }
                return data;
            }
        });
        calendar.render();
    }


})