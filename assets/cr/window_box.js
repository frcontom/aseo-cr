$(document).ready(function() {


    setInterval(function(){
        location.reload(); // Recarga la página
    }, 15 * 60 * 1000); // 15 minutos en milisegundos


    $('[data-countdown]').each(function() {
        let $this = $(this), finalDate = $(this).data('countdown');
        $this.countdown(finalDate, function(event) {
            $this.html(event.strftime('%H:%M:%S<br> Tiempo Restante'));
        });
    });

    $.fn.finish_box = function (booking = '',day = '',title = '') {
        return this.each(function () {

                Swal.fire({
                    title: "Deseas Finalizar la tarea?",
                    text: "¡Al finalizar se enviara la notificación de cumpliento!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#2BC155",
                    confirmButtonText: "FINALIZAR!",
                    cancelButtonText: "CANCELAR",
                }).then((result) => {

                    if(result.value){

                        let dataR = {
                            booking,
                            day,
                            title
                        };

                        $(this).simple_call_text(dataR,'url_save');

                    }

                });


            return false;
        })
    }

})