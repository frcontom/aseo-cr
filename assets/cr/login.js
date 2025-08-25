$(document).ready(function() {

    $('#data_login').validator().on('submit', function(e) {
        if (e.isDefaultPrevented()) {
            $(this).mensaje_alerta(1, "El campo es obligatorio");
            return false;
        } else {

                $.ajax({
                    async: false,
                    cache: false,
                    type: 'post',
                    dataType: 'text',
                    data: $("#data_login").serialize(),
                    url: $("#url_login").val(),
                    success: function(data) {
                        var result = JSON.parse(data);
                        if (result.response.success == 1) {
                                window.location.href = result.response.data;
                        }
                        else {
                            Swal.close();
                            $(this).mensaje_alerta(1, result.response.msg);
                        }
                    }
                });

            return false;
        }
    })

    $('#data_reset').validator().on('submit', function(e) {
        if (e.isDefaultPrevented()) {
            $(this).mensaje_alerta(1, "El campo es obligatorio");
            return false;
        } else {
            Swal.fire({
                position: 'center-center',
                title: 'Restableciendo Contraseña',
                timerProgressBar: true,
                showConfirmButton: false,
            })
            setTimeout(function () {
                $.ajax({
                    async: false,
                    cache: false,
                    type: 'post',
                    dataType: 'text',
                    data: $("#data_reset").serialize(),
                    url: $("#url_recovery").val(),
                    success: function(data) {
                        var result = JSON.parse(data);
                        if (result.response.success == 1) {
                            $(this).mensaje_alerta(2, 'La cuenta se a Resteblecido validar tu correo electronico');
                            $("#u_reset").val('').change();


                            $("#recoverform").fadeOut();
                            $("#loginform").slideDown();
                        }
                        else {
                            $(this).mensaje_alerta(1, result.response.msg);
                        }

                    }
                });
                Swal.close();
            },300)

            return false;
        }
    })


    $('#to-recover').on("click", function() {
        $("#loginform").slideUp();
        $("#recoverform").fadeIn();
    });

    $('#to-login').on("click", function() {
        $("#loginform").slideDown();
        $("#recoverform").fadeOut();
    });

})