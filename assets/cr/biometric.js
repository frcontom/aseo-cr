$(document).ready(function() {

    let countdownElement = document.getElementById("countdown");
    let videoElement = document.getElementById("video");
    // let photoElement = document.getElementById("photo");
    // let photoContainer = document.getElementById("photoContainer");

    let countdownTimer;
    let mediaStream;
    let videoTrack;

// Acceder a la cámara frontal
    async function startCamera() {
        try {
            // Solicitar acceso solo a la cámara frontal (facingMode: user)
            const constraints = {
                video: { facingMode: 'user' } // Esto asegura que se usa la cámara frontal
            };

            mediaStream = await navigator.mediaDevices.getUserMedia(constraints);
            videoElement.srcObject = mediaStream;

            // Guardamos las pistas de video y audio para poder detenerlas después
            const tracks = mediaStream.getTracks();
            tracks.forEach(track => {
                if (track.kind === 'video') {
                    videoTrack = track;
                }
            });

            // startCountdown(); // Iniciar el contador automáticamente cuando la cámara esté lista
        } catch (error) {
            document.getElementById('camera_video').style.display = 'none'
            document.getElementById('camera_picture').style.display = 'block'
            alert("Error al acceder a la cámara: " + error);
        }
    }

// Función para tomar la foto después del contador
    function capturePhoto() {
        let canvas = document.createElement("canvas");
        canvas.width = 640;
        canvas.height = 480;
        let context = canvas.getContext("2d");
        context.drawImage(videoElement, 0, 0, canvas.width, canvas.height);
        let photoURL = canvas.toDataURL("image/png");
        document.getElementById('photo_input').value = photoURL;
        console.log(photoURL)
        // photoElement.src = photoURL;

        // Mostrar la imagen en el div
        // photoContainer.style.display = "block";

        // Detener la cámara después de tomar la foto
        // stopCamera();
    }

// Función para iniciar el contador
    function startCountdown() {
        let count = 3;
        countdownElement.textContent = count;
        countdownElement.style.display = "block";

        countdownTimer = setInterval(function() {
            count--;
            countdownElement.textContent = count;

            if (count === 0) {
                clearInterval(countdownTimer);
                capturePhoto(); // Capturar la foto cuando el contador llegue a 0
                countdownElement.style.display = "none"; // Ocultar el contador
            }
        }, 1000); // Reducir el contador cada segundo
    }

// Función para detener la cámara (video y audio)
    function stopCamera() {
        if (mediaStream) {
            const tracks = mediaStream.getTracks(); // Obtener todas las pistas
            tracks.forEach(track => track.stop()); // Detener todas las pistas de video/audio
        }
    }

// Inicializar la cámara al cargar la página
    startCamera();




    $('#form_biometric').validator().on('submit', function(e) {
        if (e.isDefaultPrevented()) {
            $(this).mensaje_alerta(1, "El campo es obligatorio");
            return false;
        } else {

            Swal.fire({
                position: 'center-center',
                title: 'Procesando los datos',
                timerProgressBar: false,
                showConfirmButton: false,
                allowOutsideClick: false,
                hideOnContentClick: false,
                closeClick: false,
                helpers: {
                    overlay: { closeClick: false }
                }
            })


            capturePhoto();

            $.ajax({
                async: false,
                cache: false,
                type: 'post',
                dataType: 'text',
                data: $("#form_biometric").serialize(),
                url: $("#url_calcule").val(),
                success: function(data) {
                    Swal.close();
                    var result = JSON.parse(data);
                    $("#numberInput").val('').change();
                    if (result.response.success == 1) {

                        Swal.fire({
                            icon: "success",
                            position: 'center-center',
                            title: 'Se Registro La hora',
                            timerProgressBar: false,
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            hideOnContentClick: false,
                            closeClick: false,
                            timer: 2000,
                            helpers: {
                                overlay: { closeClick: false }
                            }
                        })
                    //     window.location.href = result.response.data;
                    }
                    else {

                        Swal.fire({
                            icon: "error",
                            position: 'center-center',
                            title: result.response.msg,
                            timerProgressBar: false,
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            hideOnContentClick: false,
                            closeClick: false,
                            timer: 2000,
                            helpers: {
                                overlay: { closeClick: false }
                            }
                        })

                    //     Swal.close();
                    //     $(this).mensaje_alerta(1, result.response.msg);
                    }
                }
            });

            return false;
        }
    })

    document.getElementById('numberInput').addEventListener('input', function (e) {
        // Asegurarse de que solo se ingresen números
        this.value = this.value.replace(/\D/g, '');
    });

    setInterval(() => {
        const f = new Date();
        document.getElementById('clock').innerHTML = `${f.toLocaleTimeString()} - ${f.toLocaleDateString()}`;
    }, 1000);

    $.fn.changeDay = function (data) {
        return this.each(function () {
            let horary = $(this).val();
            let objectSend = {
                ...data,
                horary: horary
            }
            console.log(objectSend)

            $(this).simple_call_text(objectSend,'url_schedules_day_save',false,function(){

            });

        });
    }

});

