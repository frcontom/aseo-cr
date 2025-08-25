$(document).ready(function() {
$('[data-toggle="tooltip"]').tooltip();

    $.fn.asignar_filial = function (id) {
    return this.each(function () {
        $(this).forms_modal({"page" : "maintance_assigne","data1" : id,"title" : "Asignacion de mantenimiento"})
        // console.log(data);return;
//         Swal.fire({
//             position: 'center-center',
//             title: 'Finalizar Revision',
//             html: `¿Asignar Mantenimiento?<br>
// <!--                     <br>Filial: <b>${data.f_name}</b>-->
// <!--                     <br>Piso: <b>${data.fr_name}</b>-->
//                      <div class="row text-left">

//                     `,
//             showCancelButton: true,
//             confirmButtonColor: '#3085d6',
//             cancelButtonColor: '#d33',
//             confirmButtonText: 'Finalizar',
//             cancelButtonText: 'Rechazar',
//             backdrop:true,
//             allowOutsideClick: false,
//             preConfirm: (status) => {
//                 console.log('status',status)
//             },
//
//         }).then((result) => {
//
//             const dataR = {
//                 user: data.a_user,
//                 filial: data.f_id,
//                 assigment:data.a_id,
//                 observation: $("#observation").val(),
//                 status: (result.value == true ? 1 : 2)
//             };
//
//             $(this).simple_call_text(dataR,'url_final_revision',false,(err) =>{
//                 window.location.reload();
//                 return true;
//             },true);
//         })
    });
}

    $.fn.task_back = function (id) {
    return this.each(function () {
        // console.log(data)
        Swal.fire({
            position: 'center-center',
            title: 'Regresar la Tarea',
            html: `¿Si regresas la actividad volvera al usuario?
            <div class="col-md-12">
               <div class="form-group">
                   <textarea class="form-control" placeholder="Observación de la revisión" id="swal_observation" rows="4"></textarea>
               </div>
            </div>
            `,
            showCancelButton: true,
            // showDenyButton: true,
            confirmButtonColor: '#ff5a00',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Retornar',
            cancelButtonText: 'Salir',
            // preConfirm: (status) => {
            //     console.log('status',status)
            // },

        }).then((result) => {
            console.log(result)
            if(result.isDismissed){
                $(this).mensaje_alerta(3, 'No se realizo ninguna acción');
                Swal.close();
            }else{
                const dataR = {
                    task: id,
                    observation: $("#swal_observation").val(),
                    status:2
                };
                // console.log(dataR)

                $(this).simple_call_text(dataR,'url_revision',false,(err) =>{
                    window.location.reload();
                    return true;
                },true);
            }

        })
    });
}

    $.fn.job_end = function (data) {
    return this.each(function () {
        // console.log(data)
        Swal.fire({
            position: 'center-center',
            title: 'Finalizar Revision',
            html: `¿Que deseas hacer con la Actividad?<br><br>Codigo Actividad: <b>${data.m_code}</b> 
            <div class="col-md-12">
               <div class="form-group">
                   <textarea class="form-control" placeholder="Observación de la revisión" id="swal_observation" rows="4"></textarea>
               </div>
            </div>
        `,
            showCancelButton: true,
            showDenyButton: true,
            confirmButtonColor: '#3085d6',
            denyButtonColor : '#ff5a00',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Finalizar',
            denyButtonText: `Retornar`,
            cancelButtonText: 'Salir',
            backdrop:true,
            allowOutsideClick: false,
            // preConfirm: (status) => {
            //     console.log('status',status)
            // },

        }).then((result) => {

            if(result.isDismissed){
                $(this).mensaje_alerta(3, 'No se realizo ninguna acción');
                Swal.close();
            }else{
                const dataR = {
                    task: data.m_id,
                    job: data.hma_id,
                    observation: $("#swal_observation").val(),
                    status: (result.isConfirmed == true ? 1 : 2)
                };

                $(this).simple_call_text(dataR,'url_revision',false,(err) =>{
                    window.location.reload();
                    return true;
                },true);
            }

        })
    });
}

    $.fn.changeType = function () {
        return this.each(function () {

            const data = $(this).val();
            const date = $("#fecha");
            const label = $("#labeldate");
            const divend = $("#work_end");
            const input_end = $("#date_input_end");
            date.val('').change().attr('readonly',false);
            if(data == 1){
                //express
                label.text('Tiempo Min.');
                date.attr('type','text');

                input_end.attr('disabled',true);
                divend.addClass('d-none');
            }else{
                label.text('Fecha inicio');
                date.attr('type','datetime-local');
                $(this).inputdate_old('time');

                input_end.attr('disabled',false);
                divend.removeClass('d-none');
            }

            // if(status == 1){
            //     //init task
            //     jtitle = 'iniciar la tarea'
            //     jbtn = 'Iniciar';
            // }else{
            //     //stop task
            //     jtitle = 'Finalizar la tarea';
            //     jbtn = 'Finalizar';
            //
            // }

        });
    }

    $.fn.change_status = function (data,status) {
        return this.each(function () {
            console.log(data)
            if(status == 1){
                //init task
                jtitle = 'iniciar la tarea'
                jbtn = 'Iniciar';
            }else{
                //stop task
                jtitle = 'Finalizar la tarea';
                jbtn = 'Finalizar';

            }
            // console.log({data,status})

            $(this).forms_modal({"page" : "maintance_task","data1" : data.m_id,"data2" : data.status,"title" : jtitle})

        });
    }

    $.fn.result_filial = function (data,status) {
        return this.each(function () {
            if(status == 1){
                //init task
                jtitle = 'iniciar la tarea'
                jbtn = 'Iniciar';
            }else{
                //stop task
                jtitle = 'Finalizar la tarea';
                jbtn = 'Finalizar';

            }

            $(this).forms_modal({"page" : "maintance_result","data1" : data.m_id,"data2" : data.status,"title" : jtitle})

        });
    }

    if($(".all_filials").length > 0){
        setInterval(function () {
            $.get(location.href + "?t=" + new Date().getTime(), function(data) {
                var newContent = $(data).find('.all_filials').html();
                if (newContent) {
                    $('.all_filials').html(newContent);
                } else {
                    console.log("No se encontró el contenido de .all_filials.");
                }
            }).fail(function() {
                console.log("Ocurrió un error al actualizar el contenido.");
            });
            // $(".all_filials").each(function (r) {
            //     const id = $(this).attr('data-id');
            //     // $(`.all_filials[data-id='${id}']`).load(location.href+` .all_filials[data-id='${id}']>*`,"");
            // })
        }, 10000);
    }

    //table task log
    if($("#table_log").length > 0){
        $(this).dataTable_ajax_es('#tabla_data','#table_log');
    }


    if($(".work_task_new").length > 0){
        // console.log('entro')
        setInterval(function () {

            console.log('actualizacion..')
            $.get(location.href + "?t=" + new Date().getTime(), function(data) {
               ['.work_task_new', '.work_express', '.work_programer'].forEach(function(className) {
                    var newContent = $(data).find(className).html();
                    if (newContent) {
                        $(className).html(newContent);
                    } else {
                        console.log("No se encontró el contenido de .work_task_new.");
                    }
                });
            }).fail(function() {
                console.log("Ocurrió un error al actualizar el contenido.");
            });

        }, 500000);


    }

});
