import socket from "./socket.js";

$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
    const filialAssigne = [];

    // $.fn.asignar_filial = function (data) {
    //     return this.each(function () {
    //         // if(filialAssigne.some(r => r.id == data.filial.f_id)) {$(this).mensaje_alerta(1, 'La filial ya se encuentra Asignada');return;}
    //
    //         const add = {
    //             filial: data.filial.f_id,
    //             // name: data.filial.f_name,
    //             // code: data.filial.f_code,
    //             status:data.status.s_id,
    //             // status_name:data.status.s_name,
    //             // status_status:data.status.s_status,
    //             // status_icon:data.status.i_icon,
    //             // status_color:data.status.s_color,
    //             hour:data.status.s_time,
    //         }
    //         // filialAssigne.push(add);
    //         // $(this).asignar_filial_list(add);
    //
    //
    //         setTimeout(function () {
    //             $(this).simple_call_text(add,'url_save',false,(resp) =>{
    //                 const response = resp.response;
    //                 Swal.close();
    //                 if(response.success == 1){
    //                     $(this).mensaje_alerta(2, 'Se proceso el servicio correctamente');
    //                     setTimeout(function () {
    //                         window.location.reload();
    //                         return false;
    //                     }, 1000);
    //
    //                 }else{
    //                     $(this).mensaje_alerta(1, response.msg);
    //                     return false;
    //                 }
    //             },true);
    //         }, 500);
    //
    //     });
    // }

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

    $.fn.asignar_filial_list = function (data) {
        return this.each(function () {
            const filial_div = $('.timeline');
            filial_div.append(`
            <li data-id="${data.id}">
                                    <div class="timeline-panel">
                                        <div class="media mr-2">
                                            <img alt="image" class="image_thumbnail" src="${data.imagen}">
                                        </div>
                                        <div class="media-body">
                                            <h5 class="mb-1">${data.name}</h5>
                                            <small class="d-block"><i class="${data.status_icon}" style="color:${data.status_color}"></i> ${data.status_name}</small>
                                        </div>
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-primary light sharp" data-toggle="dropdown">
                                                <svg width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="5" cy="12" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="19" cy="12" r="2"/></g></svg>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#">Edit</a>
                                                <a class="dropdown-item" href="javascript:void(0)" onclick="$(this).delete_house(${data.id})">Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                </li>`)
        });
    }

    $.fn.user_clean = function () {
        return this.each(function () {
            const userInfo = $('#user_info');
           const info = $("#user_data").find(':selected');
            $("#user_name").text(info.text())
            userInfo.removeClass('d-none');
        });
    }

    $.fn.submitData = function () {
        return this.each(function () {
            if(filialAssigne.length == 0) {$(this).mensaje_alerta(1, 'Debe asignar al menos una filial');return;}
           const info = $('#user_data').find(':selected');
           const data = {
                user_id: info.val(),
                house: filialAssigne
           }


            Swal.fire({
                position: 'center-center',
                title: 'Procesando el dato',
                timerProgressBar: false,
                showConfirmButton: false,
                allowOutsideClick: false,
                hideOnContentClick: false,
                closeClick: false,
                helpers: {
                    overlay: { closeClick: false }
                }
            })


            setTimeout(function () {
                $(this).simple_call_text(data,'url_save',false,(resp) =>{
                    const response = resp.response;
                    Swal.close();
                    if(response.success == 1){
                        $(this).mensaje_alerta(2, 'Se proceso el servicio correctamente');
                        setTimeout(function () {
                            window.location.reload();
                        }, 1000);

                    }else{
                        $(this).mensaje_alerta(1, response.msg);
                    }
                },true);
            }, 1000);
        });
    }

    $.fn.revision_ready = function (data) {
        return this.each(function () {
            // console.log(data);return;
            Swal.fire({
                position: 'center-center',
                title: 'Finalizar Revision',
                html: `¿Está seguro de Finalizar la Revisión?<br>
                     <br>Filial: <b>${data.f_name}</b>
                     <br>Piso: <b>${data.fr_name}</b>
                     <input type="text" class="form-control" placeholder="Observación de la revisión" id="observation">
                    `,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Finalizar',
                cancelButtonText: 'Rechazar',
                backdrop:true,
                allowOutsideClick: false,
                preConfirm: (status) => {
                    console.log('status',status)
                },

            }).then((result) => {

                    const dataR = {
                        user: data.a_user,
                        filial: data.f_id,
                        assigment:data.a_id,
                        observation: $("#observation").val(),
                        status: (result.value == true ? 1 : 2)
                    };

                    $(this).simple_call_text(dataR,'url_final_revision',false,(err) =>{
                        window.location.reload();
                        return true;
                    },true);
            })
        });
    }

    $.fn.delete_house = function (id) {
        return this.each(function () {
            const rr = filialAssigne.filter(function (item, index) {
                if(item.id == id){
                    filialAssigne.splice(index, 1);
                    $(`.timeline li[data-id="${id}"]`).remove()
                    console.log(filialAssigne)
                }
            })
            console.log(filialAssigne)
        });
    }

    $.fn.filter_floor = function () {
        return this.each(function () {
            const filter = $(this).val();
            const fials = $(".filials");
            // var regex = new RegExp(`${filter}\b`, 'i');

            let re = new RegExp(filter, "i");
            // filtramos la información
            fials.hide().filter(function(){
                return re.test($(this).data('floor'));
            }).show();
        });
    }

    // Asignar filiales y cambiar estados
    $.fn.filial_asginar = function (obj = {},status = 1) {
        return this.each(function () {
            if(status == 1){
                //solo pidio asignar al usuario
                $(this).simple_call_text(obj,'url_save',false,(data) =>{
                    const rep = data.response;
                    if(rep.success == 1){
                        if(rep.status == 2) {
                            $(this).mensaje_alerta(2, 'Se proceso el servicio correctamente');
                            // $(this).send_notify("Asignación de Filial",`Se te ha asigno una tarea`,[rep.token]);
                        }else{
                            $(this).mensaje_alerta(2, 'Se proceso el servicio correctamente pero no se envio la notificación');
                        }
                    }else{
                        $(this).mensaje_alerta(1, rep.msg);
                    }
                    setTimeout(function () {
                        window.location.reload();
                    }, 1000);
                    return true;
                },true);
            }
            else{
                //se cambia el estado y se asigna a otro usuario
                Swal.fire({
                    title: 'Cambiar el usuario Asignado',
                    html: `¿Está seguro de Cambiar el usuario de la <br> filial <b class="text-success">${obj.name}</b>?<br>
                                <select class="form-control mt-3" id="user_data">
                                    <option value="" selected disabled>Seleccionar</option>
                                        ${users.map((item) => {
                                        return `<option value="${item.u_id}">${item.u_name}</option>`
                                    })}
                                </select>            
                        `,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Cambiar',
                    cancelButtonText: 'Cancelar',
                    // backdrop:true,
                    // allowOutsideClick: false,
                    preConfirm: (status) => {
                        console.log('status',status)
                    },

                }).then((result) => {
                    if(result.value == true){

                        let userD = $("#user_data").val();

                        if(userD == null){
                            $(this).mensaje_alerta(1, 'Vuelve a intentarlo bebes seleccionar un usuario');
                            return;
                        }

                        let data = {
                            id  : obj.id,
                            user    : userD,
                            status  : 2,
                            type  : 2,
                            hour    : obj.hour,
                            name    : obj.name
                        }
                        $(this).filial_asginar(data,1)
                    }

                })
            }

        });
    }

    $.fn.filial_blocked = function (obj,statusq  = '') {
        return this.each(function () {
            if(statusq == 1){
                Swal.fire({
                    title: 'Bloquear Filial',
                    html: `¿Está seguro de bloqear la  filial <b class="text-success">${obj.name}</b>?<br>
                                <textarea class="form-control mt-3" rows="10" id="observation"></textarea>            
                        `,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Cambiar',
                    cancelButtonText: 'Cancelar',
                    // backdrop:true,
                    // allowOutsideClick: false,
                    preConfirm: (status) => {
                        console.log('status',status)
                    },

                }).then((result) => {
                    if(result.value == true){


                        let data = {
                            id  : obj.id,
                            observation : $("#observation").val(),
                            status: statusq,
                        }
                        $(this).simple_call_text(data,'url_filial_blocked',false,(err) =>{
                            socket.emit("fills_form", {filial: obj.id});// Emitir el evento después de la acción
                            return true;
                        },true);
                    }

                })
            }else{
                let data = {
                    id  : obj.id,
                    observation : '',
                    status: statusq,
                }

                $(this).simple_call_text(data,'url_filial_blocked',false,(err) =>{
                    // window.location.reload();
                    socket.emit("fills_form", {filial: obj.id});// Emitir el evento después de la acción
                    return true;
                },true);
            }

        });
    }


    $.fn.filial_bloqueada = function (filial,name  = '') {
        return this.each(function () {
            Swal.fire({
                title: 'Desbloquear Filial',
                html: `¿Está seguro de Cambiar el estado de <b>Bloqueada</b> a <b>Sucia</b> a la filial <b class="text-success">${name}</b>?<br>`,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Cambiar',
                cancelButtonText: 'Cancelar',
                backdrop:true,
                allowOutsideClick: false,
                preConfirm: (status) => {
                    console.log('status',status)
                },

            }).then((result) => {
                if(result.value == true){

                    $(this).simple_call_text({filial},'url_change_status',false,(err) =>{
                        // window.location.reload();
                        socket.emit("fills_form", {filial: filial});// Emitir el evento después de la acción
                        return true;
                    },true);
                }
            })
        });
    }


    $.fn.filial_close = function (filial = '',assigment  = '', name = '') {
        return this.each(function () {
            Swal.fire({
                title: 'Desbloquear Filial',
                html: `¿Está seguro de Cambiar el estado a <b>Limpio</b> de la filial <b class="text-success">${name}</b>?<br>`,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Cambiar',
                cancelButtonText: 'Cancelar',
                backdrop:true,
                allowOutsideClick: false,
                preConfirm: (status) => {
                    console.log('status',status)
                },
            }).then((result) => {
                if(result.value == true){
                    $(this).simple_call_text({filial,assigment,name},'url_change_close',false,(err) =>{
                        window.location.reload();
                        return true;
                    },true);
                }
            })
        });
    }



    // $.fn.search_house = function () {
    //     return this.each(function () {
    //         const filter = $(this).val();
    //         const fials = $(".filials");
    //         // var regex = new RegExp(`${filter}\b`, 'i');
    //
    //         let re = new RegExp(filter, "i");
    //         // filtramos la información
    //         fials.hide().filter(function(){
    //             console.log(re.test($(this).data('title')))
    //             return re.test($(this).data('title'));
    //         }).show();
    //     });
    // }

    // setInterval(function () {
    //     $("#all_filials").load(location.href+" #all_filials>*","");
    // }, 500);
    //APIs
    // $.fn.get_users = function () {
    //     return this.each(function () {
    //         $(this).simple_call_text({},"url_get_users",false,(resp) =>{
    //
    //         },true);
    //     });
    // }

    // setInterval(function () {
    //     $.get(location.href, function(data) {
    //         var newContent = $(data).find('.all_filials').html();
    //         $('.all_filials').html(newContent);
    //     }).fail(function() {
    //         console.log("Ocurrió un error al actualizar el contenido.");
    //     });
    // }, 15000);
})