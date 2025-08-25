$(document).ready(function() {

    //Alertas
    $.fn.mensaje_alerta = function (tipo, descripcion) {
        return this.each(function () {

            if (tipo.length != 0 && descripcion.length != 0) {

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


    $.fn.testing = function () {
        return this.each(function () {

            alert("Testing");

        })
    }

    $.fn.simple_call_text = function (datastring, url_data,pregunta = true,func = null,rtn = false,err = false,type = 'json',simple = false) {
        return this.each(function () {
                    let url_dell = $(`#${url_data}`).val();
                    if (url_dell != '') {
                        // var datastring = new FormData(document.getElementById(tag_data));
                        // datastring.append("clave", "valor");

                        // $("#frm_data").serialize(datastring);
                        // Swal.fire({
                        //     position: 'center-center',
                        //     title: 'Procesando el dato',
                        //     timerProgressBar: false,
                        //     showConfirmButton: false,
                        //     allowOutsideClick: false,
                        //     hideOnContentClick: false,
                        //     closeClick: false,
                        //     helpers: {
                        //         overlay: { closeClick: false }
                        //     }
                        // })
                        // $("button:submit").attr("disabled",true)
                        setTimeout(() => {
                            $.ajax({
                                async: false,
                                cache: false,
                                type: 'post',
                                dataType: type, // json...just for example sake
                                data: datastring,
                                url: url_dell,
                                success: function (result) {

                                    if(simple == true){

                                        if(rtn == true){
                                            // alert(22)
                                            func( result);
                                        }else{
                                            func();
                                        }
                                    }else{

                                            if (result.response.success == 1) {
                                                if(pregunta == true){
                                                    $(this).mensaje_alerta(2, 'Se proceso la solicitud');
                                                    window.location.reload();
                                                }else{
                                                    // Swal.close();
                                                    if(rtn == true){
                                                        func( result);
                                                    }else{
                                                        func();
                                                    }
                                                }
                                            } else {
                                                if(err == false){
                                                    $(this).mensaje_alerta(1, result.response.msg);
                                                }else{
                                                    func( result);
                                                }
                                                // Swal.close();
                                            }
                                    }
                                }
                            })
                        },600)
                    }
                    else {
                        $(this).mensaje_alerta(1, "No se pudo realizar el Proceso");
                    }
     });

    }

        $.fn.simple_call = function (tag_data, url_data, pregunta = true,func = null,rtn = false,err = false) {
        return this.each(function () {
                    let url_dell = $(`#${url_data}`).val();
                    if (url_dell != '') {
                        var datastring = new FormData(document.getElementById(tag_data));
                        datastring.append("clave", "valor");

                        $("#frm_data").serialize(datastring);
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
                        // $("button:submit").attr("disabled",true)
                        setTimeout(() => {
                            $.ajax({
                                async: true,
                                cache: false,
                                type: 'post',
                                dataType: 'json', // json...just for example sake
                                data: datastring,
                                url: url_dell,
                                contentType: false,
                                processData: false,
                                xhr: function() {
                                    var xhr = $.ajaxSettings.xhr();
                                    if(xhr.upload){
                                        xhr.upload.addEventListener('progress', function(event){
                                            var percent = 0;
                                            if (event.lengthComputable) {
                                                percent = Math.ceil(event.loaded / event.total * 100);
                                            }
                                            $('#progressbar').text('Carga '+percent+'%') ;
                                            $('#progressbar').attr('aria-valuenow',percent);
                                            $('#progressbar').css("width", percent+"%");
                                        }, false);
                                    }
                                    return xhr;
                                },
                                success: function (result) {
                                    if (result.response.success == 1) {
                                        if(pregunta == true){
                                            window.location.reload();
                                        }else{
                                            // $(this).mensaje_alerta(2, 'Se proceso la solicitud');
                                            Swal.close();
                                            if(rtn == true){
                                                func( result);
                                            }else{
                                                func();
                                            }
                                        }
                                    } else {
                                        if(err == false){
                                            $(this).mensaje_alerta(1, result.response.msg);
                                        }else{
                                            func( result);
                                        }
                                        Swal.close();
                                    }
                                }
                            })
                        },600)
                    }
                    else {
                        $(this).mensaje_alerta(1, "No se pudo realizar el Proceso");
                    }
     });

    }

    $.fn.call_not_message = function (tag_data, url_data,func = null) {
        return this.each(function () {
            let url_dell = $(`#${url_data}`).val();
            if (url_dell != '') {
                    $.ajax({
                        async: false,
                        cache: false,
                        type: 'post',
                        dataType: 'json',
                        data: tag_data,
                        url: url_dell,
                        success: function (result) {
                            console.log('result', result)
                            if(func != null){
                                func( result);
                            }
                        }
                    })
            }
            else {
                $(this).mensaje_alerta(1, "No se pudo realizar el Proceso");
            }
        });

    }


    //delete
    $.fn.dell_data = function (data, url_delete,pregunta = true,callback = null) {
        return this.each(function () {
            let url_dell = $(`#${url_delete}`).val();
            if (data != '' && url_dell != '') {

                    Swal.fire({
                        title: "Deseas Borrar este contenido?",
                        text: "¡No podrá recuperar este información despues de aceptar!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#A81D42",
                        confirmButtonText: "Si, elimínalo!",
                        cancelButtonText: "Cancelar Operación",
                    }).then((result) => {

                        if(result.value){
                            $.ajax({
                                async: false,
                                cache: false,
                                type: 'post',
                                dataType: 'text', // json...just for example sake
                                data: typeof data == 'object' ? data : { id: data },
                                url: url_dell,
                                success: function (data) {
                                    var result = JSON.parse(data);
                                    if (result.response.success == 1) {
                                        if(pregunta == true){
                                             window.location.reload();
                                        }else{
                                            $(this).mensaje_alerta(2, "Se Elimino el dato");
                                            callback();
                                        }
                                    } else {

                                        $(this).mensaje_alerta(1, "No se pudo realizar el Proceso");
                                    }
                                }
                            })
                        }

                    });

            }
            else {
                $(this).mensaje_alerta(1, "No se pudo realizar el Proceso");
            }
            return false;
        })
    }
    //delete





})