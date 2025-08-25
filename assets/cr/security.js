$(document).ready(function() {

    // $.fn.change_status = function (data) {
    //     return this.each(function () {
    //         console.log(data)
    //         Swal.fire({
    //             position: 'center-center',
    //             title: 'Ejecutar Tarea',
    //             html: `¿Está seguro de iniciar la tarea?<br>
    //                  <br>Filial: <b>${data.f_name}</b>
    //                  <br>Piso: <b>${data.fr_name}</b>
    //                  <br>Estado Actual: <b>${data.s_name}</b>
    //                  <br>Tiempo Estimado: <b>${data.a_time} Minutos</b>
    //                 `,
    //             showCancelButton: true,
    //             confirmButtonColor: '#3085d6',
    //             cancelButtonColor: '#d33',
    //             confirmButtonText: 'Iniciar',
    //             cancelButtonText: 'Cancelar',
    //
    //         }).then((result) => {
    //             if (result.value) {
    //                 console.log('inciiar proceso');
    //             }
    //         })
    //     });
    // }

    $('[data-toggle="tooltip"]').tooltip();

    // $.fn.asignar_filial = function (filial) {
    //     return this.each(function () {
    //
    //         const data = {
    //             filial: filial.filial.f_id,
    //             status:filial.status.s_id,
    //             fname:filial.filial.f_name,
    //             sname:filial.status.s_name
    //         }
    //
    //         $(this).simple_call_text(data,'url_assigne_status',false,() =>{
    //
    //             socket.emit("fills_form", data);
    //             // $.get(location.href, function(data) {
    //             //     var newContent = $(data).find('#all_filials').html();
    //             //     $('#all_filials').html(newContent);
    //             // }).fail(function() {
    //             //     console.log("Ocurrió un error al actualizar el contenido.");
    //             // });
    //         },false);
    //     });
    // }

    $.fn.assigne_status = function (filial,status) {
        return this.each(function () {
            const data = {
                filial: filial.f_id,
                status
            }
            $(this).simple_call_text(data,'url_change_status',false,(err) =>{
                window.location.reload();
                return true;
            },true);
        });
    }

    $.fn.assigne_comment = function (filial) {
        return this.each(function () {
            const data = {
                filial: filial.f_id,
            }

            let comment = (filial.hc_comment != '' && filial.hc_comment != null) ? filial.hc_comment : '';

            Swal.fire({
                position: 'center-center',
                title: 'Comentario Asignación',
                html: `¿Está seguro de agregar un comentario?<br>
                     <br>Filial: <b>${filial.f_name}</b>
                     <br>Piso: <b>${filial.fr_name}</b>
                     <textarea rows="8" class="form-control" placeholder="Comentario" id="observation">${comment}</textarea>
                    `,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Finalizar',
                cancelButtonText: 'Cancelar',
                backdrop:true,
                allowOutsideClick: false,
                // preConfirm: (status) => {
                //     console.log('status',status)
                // },

            }).then((result) => {
                if(result.isConfirmed == true){
                    const dataR = {
                        fname: filial.f_name,
                        filial: filial.f_id,
                        observation: $("#observation").val()
                    };

                    $(this).simple_call_text(dataR,'url_save',false,(err) =>{
                        window.location.reload();
                        return true;
                    },true);
                }
            })

        });
    }

    $.fn.search_house = function () {
        return this.each(function () {
            const filter = $(this).val();
            const fials = $(".filials");
            // var regex = new RegExp(`${filter}\b`, 'i');

            let re = new RegExp(filter, "i");
            // filtramos la información
            fials.hide().filter(function(){
                console.log(re.test($(this).data('title')))
                return re.test($(this).data('title'));
            }).show();
        });
    }


    // setInterval(function () {
    //     // $("#all_filials").load(location.href+" #all_filials>*","");
    //     $.get(location.href, function(data) {
    //         var newContent = $(data).find('#all_filials').html();
    //         $('#all_filials').html(newContent);
    //     }).fail(function() {
    //         console.log("Ocurrió un error al actualizar el contenido.");
    //     });
    // }, 15000);
});