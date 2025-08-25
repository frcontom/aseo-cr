// === JS actualizado: sin "counter" pequeÃ±o y con cooldown que APAGA la cÃ¡mara ===
(function () {
    'use strict';

    const $loading = $('#loading');
    const $error   = $('#errorMsg');
    const webcamElement   = document.getElementById('webcam');
    const videoContainer  = document.getElementById('video-container');
    const curtainEl       = document.getElementById('curtain');      // cortina de cooldown
    const curtainCounter  = document.getElementById('curtainCounter');
    const curtainTitle    = document.getElementById('curtainTitle');

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
        curtainTitle.textContent = 'Espera para el prÃ³ximo registro';
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
        }
    }
    function resumeDetection() {
        if (!detectionIntervalId) startDetection();
    }

    // === NUEVO FLUJO ===
    // 1) Se detecta rostro con score suficiente -> CAPTURAR inmediatamente
    // 2) Entrar a COOLdown: mostrar cortina, parar detecciÃ³n y APAGAR cÃ¡mara
    // 3) Al terminar el cooldown: encender cÃ¡mara de nuevo y reanudar detecciÃ³n
    async function handleCaptureAndCooldown(detection) {
        if (isCooldown) return;

        // (1) Capturar ahora (antes de apagar cÃ¡mara)
        actuallyCapture(detection);

        // (2) Entrar a cooldown y apagar cÃ¡mara
        isCooldown = true;
        pauseDetection();

        let secs = COOLDOWN_SEC;
        showCurtain(secs);

        try {
            await webcam.stop(); // APAGA la cÃ¡mara durante el cooldown
        } catch (e) {
            console.warn('stop webcam error', e);
        }

        cooldownTimer = setInterval(async () => {
            secs -= 1;
            if (secs <= 0) {
                clearInterval(cooldownTimer);
                cooldownTimer = null;

                // (3) Encender cÃ¡mara otra vez
                try {
                    await webcam.start();
                    await waitForMetadata(webcamElement);
                    ensureCanvas();
                } catch (e) {
                    console.error('Error al reiniciar la cÃ¡mara:', e);
                    showError('No se pudo reiniciar la cÃ¡mara.');
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

            console.log('Captura realizada. Entrando en cooldown (cÃ¡mara apagada temporalmente).');



            let dataR = {
                imageData,
                descriptor
            };

            $(window).simple_call_text(dataR,'url_picture',false, function (response) {
                alert(JSON.stringify(response));
            },true);

            //send the captured data to the server
            // const formData = new FormData();
            // formData.append('imageData', imageData);
            // formData.append('descriptor', descriptor);
            //
            // const urlPage = document.getElementById('url_picture').value;
            //
            // fetch(urlPage, {
            //     method: 'POST',
            //     body: formData
            // }).then(response => {
            //     if (!response.ok) {
            //         throw new Error('Error al enviar los datos de captura');
            //     }
            //     console.log('Datos de captura enviados correctamente.');
            // }).catch(error => {
            //     console.error('Error al enviar los datos de captura:', error);
            // });


        } catch (e) {
            console.error('Error al capturar:', e);
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

    async function init() {
        try {
            showLoading(true);
            hideError();
            await webcam.start();
            await waitForMetadata(webcamElement);
            ensureCanvas();
            await Promise.all([
                faceapi.nets.tinyFaceDetector.load(MODEL_PATH),
                faceapi.nets.faceLandmark68Net.load(MODEL_PATH),
                faceapi.nets.faceExpressionNet.load(MODEL_PATH),
                faceapi.nets.ageGenderNet.load(MODEL_PATH),
                faceapi.nets.faceRecognitionNet.load(MODEL_PATH)
            ]);
            startDetection();
            window.addEventListener('resize', syncCanvasToDisplay);
            window.addEventListener('orientationchange', syncCanvasToDisplay);
        } catch (err) {
            document.getElementById('video-container')?.classList.add('d-none');
            showError('No se pudo iniciar la cÃ¡mara o finalizar el proceso.');
        } finally {
            showLoading(false);
        }
    }

    $(document).ready(init);
})();