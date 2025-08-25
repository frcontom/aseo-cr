$(document).ready(function() {

    $('#frm_data').validator().on('submit', function(e) {
        if (e.isDefaultPrevented()) {
            $(this).mensaje_alerta(1, "Los campos son Obligatorios");
            return false;
        } else {
            e.preventDefault();
            $(this).simple_call('frm_data','url_save',false,function(r)  {

                setTimeout(function() {
                    window.location.reload();
                },1000)
            });

        }
    })

    $('#frm_data_reload').validator().on('submit', function(e) {
        if (e.isDefaultPrevented()) {
            $(this).mensaje_alerta(1, "Los campos son Obligatorios");
            return false;
        } else {
            e.preventDefault();
            $(this).simple_call('frm_data_reload','url_save');

        }
    })


    $.fn.show_ban = function (code) {
        return this.each(function () {

            Swal.fire({
                position: 'center-center',
                title: 'Tarea Bloqueada',
                html: `
                             <br>${code}
                            `,
                showCancelButton: false,
                confirmButtonText: 'Cerrar la ventana',

            })
        });
    }

})