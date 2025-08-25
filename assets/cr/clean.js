import socket from "./socket.js";


$(document).ready(function() {

    $('[data-toggle="tooltip"]').tooltip();

    $.fn.change_status = function (data) {
        return this.each(function () {
                        const rep = data.response;
                        if(rep.success == 1){
                                $(this).mensaje_alerta(2, 'Se proceso el servicio correctamente');

                                //filters tokens
                                // const tokens = rep.tokens.map((item) => item.u_notify_code);
                                // $(this).send_notify("Sys Aseo CR",`${noty_body} ${rep.name}`,tokens);
                                socket.emit("fills_form", rep);// Emitir el evento después de la acción

                        }else{
                            $(this).mensaje_alerta(1, rep.msg);
                        }

        });
    }

    $.fn.change_status_hand = function (data) {
        return this.each(function () {


            Swal.fire({
                position: 'center-center',
                title: 'Ejecutar Tarea no molestar <i class="fas fa-hand text-danger"></i>',
                html: `Está seguro de cambiar la orden a no molestar<br>
                     <br>Filial: <b>${data.f_name}</b>
                     <br>Piso: <b>${data.fr_name}</b>
                     <br>Estado Actual: <b>${data.s_name}</b>
                `,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Registrar no molestar',
                cancelButtonText: 'Cancelar',

            }).then((result) => {
                if (result.value) {

                    const dataR = {
                        filial: data.f_id,
                        assigment:data.a_id
                    }
                    $(this).simple_call_text(dataR,'url_change_status_hand',false,(err) =>{
                        // window.location.reload();
                        const rep = err.response;
                        if(rep.success == 1) {
                            socket.emit("fills_form", rep);// Emitir el evento después de la acción
                        }
                        return true;
                    },true);
                }
            })
        });
    }

    $.fn.clean_busy = function (data) {
        return this.each(function () {


            Swal.fire({
                position: 'center-center',
                title: 'Cambiar estado de la Tarea',
                html: `Está seguro de cambiar de cambiar el estado a <b>ocupada Limpia</b><br>
                     <br>Filial: <b>${data.f_name}</b>
                     <br>Piso: <b>${data.fr_name}</b>
                     <br>Estado Actual: <b>${data.s_name}</b>
                `,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Cambiar Estado',
                cancelButtonText: 'Cancelar',

            }).then((result) => {
                if (result.value) {

                    const dataR = {
                        filial: data.f_id,
                        assigment:data.a_id
                    }
                    $(this).simple_call_text(dataR,'url_change_clean_busy',false,(err) =>{
                        // window.location.reload();
                        // socket.emit("fills_form", data);
                        const rep = err.response;
                        if(rep.success == 1) {
                            socket.emit("fills_form", rep);// Emitir el evento después de la acción
                        }
                        return true;
                    },true);
                }
            })
        });
    }

    // setInterval(function () {
    //     $("#all_filials").load(location.href+" #all_filials>*","");
    // }, 6500);
});