// document.addEventListener("DOMContentLoaded", function() {


const socket = io("https://socket.chatgpr.lat/", {
        transports: ["websocket"],  // Solo WebSocket para mayor eficiencia
        cors: {
            origin: "*",  // Permite todos los orígenes
            methods: ["GET", "POST"]
        },
        reconnection: true,  // Habilitar reconexión
        reconnectionAttempts: 5,  // Intentos de reconexión
        reconnectionDelay: 1000  // Retraso entre intentos
    });



    // Mostrar mensaje en consola cuando se conecta
    socket.on("connect", () => {
        console.log("Conectado al servidor");
    });

    socket.on("disconnect", () => {
        console.log("Desconectado del servidor");
    });


    // Escuchar eventos personalizados

socket.on("fills_form", (data) => {
    console.log("Datos recibidos:", data);
    // Aquí es donde se actualiza el DOM después de recibir los datos
    // $(".all_filials").find('[data-id="'+data.filial+'"]').load(location.href+" .all_filials > [data-id='"+data.filial+"']>*","");
    // $(`#filial-id-${data.filial}`).load(location.href + ` #filial-id-${data.filial}>*`, "");


    // Verificar si existe el elemento #filial_window_all
    if ($("#filial_window_all").length) {
        // Si existe, actualizar el sector completo #all_filials
        $("#filial_window_all").load(location.href + " #filial_window_all>*", "");
    } else {
        // Si no existe, realizar la actualización basada en data.filial
        $(`#filial-id-${data.filial}`).load(location.href + ` #filial-id-${data.filial}>*`, "");
    }


    // $("#all_filials").load(location.href+" #all_filials>*","");
    // $.get(location.href, function(data) {
    //     var newContent = $(data).find('#all_filials').html();
    //     // $('#all_filials').html(newContent);
    // }).fail(function() {
    //     console.log("Ocurrió un error al actualizar el contenido.");
    // });
});

export default socket;


// });