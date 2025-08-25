import socket from "./socket.js";

$(document).ready(function() {
    // Escuchar eventos personalizados


    // Asignar filial y emitir el evento
    $.fn.asignar_filial = function (filial) {
        return this.each(function () {
            const data = {
                filial: filial.filial.f_id,
                status: filial.status.s_id,
                fname: filial.filial.f_name,
                sname: filial.status.s_name
            }

            // console.log('XXX1' + JSON.stringify(data));

            $(this).simple_call_text(data, 'url_assigne_status', false, () => {
                // Emitir el evento después de la acción
                socket.emit("fills_form", data);
            }, false);
        });
    }


    $.fn.change_bother = function (id = '',status = '') {
        return this.each(function () {
            // let url_dell = $(`#${url_delete}`).val();
            if (id != '' && status != '') {


                $.ajax({
                    async: false,
                    cache: false,
                    type: 'post',
                    dataType: 'text', // json...just for example sake
                    data: { id, status },
                    url: $('#url_bother_status').val(),
                    success: function (data) {
                        var result = JSON.parse(data);
                        if (result.response.success == 1) {
                            socket.emit("fills_form", {'filial' : result.response.filial});
                        } else {

                            $(this).mensaje_alerta(1, "No se pudo realizar el Proceso");
                        }
                    }
                })

            }
            else {
                $(this).mensaje_alerta(1, "No se pudo realizar el Proceso");
            }
            return false;
        })
    }



    // Emitir un mensaje desde otro archivo (si es necesario)
    // socket.emit("some_event", { someData: "Aquí va un mensaje" });
});
