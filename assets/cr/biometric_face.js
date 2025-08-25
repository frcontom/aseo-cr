// === JS actualizado: sin "counter" pequeÃ±o y con cooldown que APAGA la Camara ===

    const $loading = $('#loading');
    const $error   = $('#errorMsg');
    const webcamElement   = document.getElementById('webcam');
    const videoContainer  = document.getElementById('video-container');
    const curtainEl       = document.getElementById('curtain');      // cortina de cooldown
    const curtainCounter  = document.getElementById('curtainCounter');
    const curtainTitle    = document.getElementById('curtainTitle');
    const takePictureBtn  = document.getElementById('take_picture');
    const stopPictureBtn  = document.getElementById('take_picture_stop');
    const cameraPreview   = document.getElementById('camera_preview');

    const webcam = new Webcam(webcamElement, 'user');
    const MODEL_PATH = '/models';

    let displaySize = null, canvas = null, detectionIntervalId = null;
    let isCooldown = false;           // si estÃ¡ corriendo la cortina de cooldown
    let cooldownTimer = null;         // id del setInterval de cooldown

    const SCORE_THRESHOLD = 0.85;     // umbral para disparar captura
    const DETECT_EVERY_MS = 100;      // frecuencia de detecciÃ³n
    const COOLDOWN_SEC    = 5;        // tiempo de bloqueo entre capturas

    function waitForMetadata(video) {
        return new Promise(resolve => {
            if (video.readyState >= 1) return resolve();
            video.onloadedmetadata = () => resolve();
        });
    }
    function getRenderedVideoSize() {
        const rect = webcamElement.getBoundingClientRect();
        return { width: Math.round(rect.width), height: Math.round(rect.height) };
    }
    function syncCanvasToDisplay() {
        displaySize = getRenderedVideoSize();
        if (!canvas) return;
        canvas.width = displaySize.width;
        canvas.height = displaySize.height;
        faceapi.matchDimensions(canvas, displaySize);
    }
    function showLoading(show) { $loading.toggleClass('d-none', !show); }
    function showError(msg)     { if (msg) $error.html(msg); $error.removeClass('d-none'); }
    function hideError()        { $error.addClass('d-none'); }
    function ensureCanvas() {
        if (!canvas) {
            canvas = faceapi.createCanvasFromMedia(webcamElement);
            videoContainer.appendChild(canvas);
        }
        syncCanvasToDisplay();
    }

    // ----- Cortina (cooldown) -----
    function showCurtain(seconds) {
        if (!curtainEl) return;
        curtainTitle.textContent = 'Espera para el proximo registro';
        curtainCounter.textContent = String(seconds);
        curtainEl.classList.add('show');
    }
    function hideCurtain() {
        if (!curtainEl) return;
        curtainEl.classList.remove('show');
    }

    // Pausa/Resume solo el LOOP de detecciÃ³n
    function pauseDetection() {
        if (detectionIntervalId) {
            clearInterval(detectionIntervalId);
            detectionIntervalId = null;
            console.log('⏹️ Detección pausada');
        }
    }
    function resumeDetection() {
        if (!detectionIntervalId) startDetection();
    }

    // === NUEVO FLUJO ===
    // 1) Se detecta rostro con score suficiente -> CAPTURAR inmediatamente
    // 2) Entrar a COOLdown: mostrar cortina, parar detecciÃ³n y APAGAR Camara
    // 3) Al terminar el cooldown: encender Camara de nuevo y reanudar detecciÃ³n
    async function handleCaptureAndCooldown(detection) {
        if (isCooldown) return;

        // (1) Capturar ahora (antes de apagar Camara)
        actuallyCapture(detection);

        // (2) Entrar a cooldown y apagar Camara
        isCooldown = true;
        pauseDetection();

        let secs = COOLDOWN_SEC;
        showCurtain(secs);

        try {
            await webcam.stop(); // APAGA la Camara durante el cooldown
        } catch (e) {
            console.warn('stop webcam error', e);
        }

        cooldownTimer = setInterval(async () => {
            secs -= 1;
            if (secs <= 0) {
                clearInterval(cooldownTimer);
                cooldownTimer = null;

                // (3) Encender Camara otra vez
                try {
                    await webcam.start();
                    await waitForMetadata(webcamElement);
                    ensureCanvas();
                } catch (e) {
                    console.error('Error al reiniciar la Camara:', e);
                    showError('No se pudo reiniciar la Camara.');
                }

                hideCurtain();
                isCooldown = false;
                resumeDetection();
            } else {
                curtainCounter.textContent = String(secs);
            }
        }, 1000);
    }

    // Captura imagen + descriptor y los deja en inputs/memoria
    function actuallyCapture(detection) {
        try {
            const snapshotCanvas = document.createElement('canvas');
            snapshotCanvas.width  = webcamElement.videoWidth;
            snapshotCanvas.height = webcamElement.videoHeight;
            snapshotCanvas.getContext('2d').drawImage(webcamElement, 0, 0);

            const imageData  = snapshotCanvas.toDataURL('image/jpeg');
            const descriptor = JSON.stringify(Array.from(detection.descriptor));

            const imgInput  = document.getElementById('imageData');
            const descInput = document.getElementById('descriptor');
            if (imgInput)  imgInput.value  = imageData;
            if (descInput) descInput.value = descriptor;

            window.capturedDescriptor = detection.descriptor;

            document.dispatchEvent(new CustomEvent('face:capture', {
                detail: { imageData, descriptor }
            }));

            console.log('Captura realizada. Entrando en cooldown (Camara apagada temporalmente).');



            let dataR = {
                imageData,
                descriptor
            };

            $(window).simple_call_text(dataR,'url_picture',false, function (response) {
                // alert(JSON.stringify(response));
                let data = response.response

                if(data.success == 1) {

                    if(data.result == 1) {
                        // alert('Registro exitoso');
                        $(window).mensaje_alerta(2, data.msg);

                        setTimeout(async function ()  {
                           await stopCamera();
                        },7000)

                    }else{
                        $(window).mensaje_alerta(1, data.msg);

                        setTimeout(async function ()  {
                            await stopCamera();
                        },7000)
                    }

                }else{

                }

             console.log(data);


                // window.location.reload();
            },true);




        } catch (e) {
            console.error('Error al capturar:', e);
        }
    }

    async function stopCamera() {
        try {

            stopPictureBtn.style.display = 'none';
            takePictureBtn.style.display = 'block';


            // Detener detección
            pauseDetection();

            // Detener todas las pistas
            if (webcamElement && webcamElement.srcObject) {
                webcamElement.srcObject.getTracks().forEach(track => track.stop());
                webcamElement.srcObject = null;
            }

            // Usar método de librería si existe
            if (webcam && typeof webcam.stop === 'function') {
                await webcam.stop();
            }

            // Mostrar preview, ocultar video
            const cameraPreview = document.getElementById('camera_preview');
            if (cameraPreview) cameraPreview.style.display = 'block';
            if (videoContainer) videoContainer.style.display = 'none';

            console.log('✅ Cámara y detección detenidas');
        } catch (err) {
            console.error('Error deteniendo cámara:', err);
        }
    }



    // Loop de detecciÃ³n (se pausa durante cooldown)
    function startDetection() {
        if (detectionIntervalId) clearInterval(detectionIntervalId);
        detectionIntervalId = setInterval(async () => {
            try {
                const detection = await faceapi
                    .detectSingleFace(
                        webcamElement,
                        new faceapi.TinyFaceDetectorOptions({ inputSize: 416, scoreThreshold: 0.5 })
                    )
                    .withFaceLandmarks()
                    .withFaceDescriptor();

                if (!displaySize) return;
                const ctx = canvas.getContext('2d');
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                if (detection) {
                    const resized = faceapi.resizeResults(detection, displaySize);
                    faceapi.draw.drawDetections(canvas, resized);

                    if (!isCooldown && detection.detection.score >= SCORE_THRESHOLD) {
                        await handleCaptureAndCooldown(detection);
                    }
                }
            } catch (err) {
                console.error('[face-detect] error:', err);
            }
        }, DETECT_EVERY_MS);
    }

    stopPictureBtn.addEventListener('click', async () => {
        try {
            await stopCamera();
            cameraPreview.style.display = 'block';
            videoContainer.style.display = 'none';

            //show and hide buttons
            stopPictureBtn.style.display = 'none';
            takePictureBtn.style.display = 'block';

            hideCurtain();
            isCooldown = false;

        } catch (err) {
            console.error('Error al detener la Camara:', err);
            showError('No se pudo detener la Camara.');
        }
    });

    takePictureBtn.addEventListener('click', async () => {
        try{


            cameraPreview.style.display = 'none';
            videoContainer.style.display = 'block';

            //show and hide buttons
            stopPictureBtn.style.display = 'block';
            takePictureBtn.style.display = 'none';


            showLoading(true);
            hideError();
            await webcam.start();
            await waitForMetadata(webcamElement);
            ensureCanvas();


            startDetection();
            window.addEventListener('resize', syncCanvasToDisplay);
            window.addEventListener('orientationchange', syncCanvasToDisplay);


        } catch (err) {
            document.getElementById('video-container')?.classList.add('d-none');
            showError('No se pudo iniciar la Camara o finalizar el proceso.');
        } finally {
            showLoading(false);
        }
    });

        async function init() {
        try {

            setInterval(() => {
                const f = new Date();
                document.getElementById('clock').innerHTML = `${f.toLocaleTimeString()} - ${f.toLocaleDateString()}`;
            }, 1000);



            await Promise.all([
                faceapi.nets.tinyFaceDetector.load(MODEL_PATH),
                faceapi.nets.faceLandmark68Net.load(MODEL_PATH),
                faceapi.nets.faceExpressionNet.load(MODEL_PATH),
                faceapi.nets.ageGenderNet.load(MODEL_PATH),
                faceapi.nets.faceRecognitionNet.load(MODEL_PATH)
            ]);



        } catch (err) {
            document.getElementById('video-container')?.classList.add('d-none');
            showError('No se pudo iniciar la Camara o finalizar el proceso.');
        } finally {
            showLoading(false);
        }
    }


    function prepareUI() {
        if(cameraPreview) cameraPreview.style.display = 'block';
        videoContainer.style.display = 'none';
    }



    $(document).ready(() => {
        prepareUI();
        init();
    });
