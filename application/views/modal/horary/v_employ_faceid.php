<?php
defined('BASEPATH') or exit('No direct script access allowed');
$name       = $this->class_security->validate_var($datas,'he_name');
$code     = $this->class_security->validate_var($datas,'he_code');
$photo      = $this->class_security->validate_var($datas_face,'hfc_photo');
?>
<style>
    /*video, canvas {*/
    /*    width: 100%;*/
    /*    height: 400px;*/
    /*    !*border: 1px solid #ccc;*!*/
    /*    !*border-radius: 4px;*!*/
    /*    background: #0a376b;*/
    /*    image-rendering: -moz-crisp-edges;    !* Firefox *!*/
    /*    image-rendering: pixelated;           !* Chrome *!*/
    /*}*/

    #webcam-container { padding: 0; }
    #video-container { position: relative; width: 100%; }
    video { background: #000; width: 100% !important; height: auto !important; display: block; }
    canvas { position: absolute; top: 0; left: 0; pointer-events: none; }
    .loading { display: flex; align-items: center; gap: .5rem; }
    #imageDataDiv, #descriptorDiv { word-break: break-all; }
</style>

<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">
    <input type="hidden" name="data_id" id="data_id" value="<?=$id?>">
    <input type="hidden" name="image" id="imageData">
    <input type="hidden" name="descriptor" id="descriptor">
    <input type="hidden" name="score" id="score">
    <div class="modal-body">

        <div class="row mb-3">
            <div class="form-group col">
                <label>Nombre</label>
                <input type="text" readonly value="<?=$name?>" placeholder="Nombre" autofocus required class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col">
                <label>Código</label>
                <input type="text" readonly value="<?=$code?>" placeholder="Télefono" autofocus required class="form-control imput_reset" autocomplete="off">
            </div>
        </div>

        <div class="row mb-3" id="photo_element">


            <div class="col-12" id="webcam-container">
<!--                <div id="loading" class="loading d-none">-->
<!--                    Cargando modelos <div class="spinner-border" role="status"></div>-->
<!--                </div>-->
                <div id="video-container">
                    <video id="webcam" autoplay muted playsinline></video>
                </div>
                <div id="errorMsg" class="col-12 alert-danger d-none mt-2 py-2 text-center h4" role="alert">
                    Falló el inicio de la cámara.<br />Permite el acceso y usa un navegador compatible.
                </div>
<!--                <div class="mt-3">-->
<!--                    <h5>Imagen scoreDiv</h5>-->
<!--                    <div id="scoreDiv" class="border p-2"></div>-->
<!--                </div>-->

<!--                <div class="mt-3">-->
<!--                    <h5>Descriptor</h5>-->
<!--                    <div id="descriptorDiv" class="border p-2"></div>-->
<!--                </div>-->
            </div>


            <?php
                if(!empty($photo)):
            ?>
            <div class="row">
                <div class="col-12 text-center mt-3">
                    <h5>Imagen capturada</h5>
                    <div id="imageDataDiv" class=""><img  src="<?=$photo?>" class="img-fluid rounded"></div>
                </div>
            </div>
            <?php endif;?>









<!--            <div class="form-group col-12">-->
<!--                <video id="video"  autoplay muted playsinline></video>-->
<!--                <canvas id="canvas" style="display:none;"></canvas>-->
<!--            </div>-->



        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar Proceso</button>
        <button type="submit" id="capture" class="btn btn-primary d-none">Capturar Rostro </button>
    </div>
</form>




<script>
    (function () {
        'use strict';

        const $loading = $('#loading');
        const $error = $('#errorMsg');
        const webcamElement = document.getElementById('webcam');
        const videoContainer = document.getElementById('video-container');
        const webcam = new Webcam(webcamElement, 'user');

        // <- Modificado según tu petición
        const MODEL_PATH = '/models';

        let displaySize = null, canvas = null, detectionIntervalId = null, hasCaptured = false;
        const SCORE_THRESHOLD = 0.85;           // umbral recomendado
        const STOP_DELAY_MS   = 1500;          // espera antes de apagar cámara para evitar frame negro

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
        function showError(msg) { if (msg) $error.html(msg); $error.removeClass('d-none'); }
        function hideError() { $error.addClass('d-none'); }
        function ensureCanvas() {
            if (!canvas) {
                canvas = faceapi.createCanvasFromMedia(webcamElement);
                videoContainer.appendChild(canvas);
            }
            syncCanvasToDisplay();
        }

        async function checkAndCapture(detection) {
            if (hasCaptured) return; // solo una foto
            if (detection && detection.detection.score >= SCORE_THRESHOLD) {
                hasCaptured = true;
                clearInterval(detectionIntervalId);

                // 1) Capturar imagen ANTES de apagar la cámara
                const snapshotCanvas = document.createElement('canvas');
                snapshotCanvas.width = webcamElement.videoWidth;
                snapshotCanvas.height = webcamElement.videoHeight;
                snapshotCanvas.getContext('2d').drawImage(webcamElement, 0, 0);
                const imageData = snapshotCanvas.toDataURL('image/jpeg');
                const descriptor = JSON.stringify(Array.from(detection.descriptor));

                // 2) Mostrar resultados
                // document.getElementById('imageDataDiv').innerHTML = `<img src="${imageData}" class="img-fluid"/>`;
                // document.getElementById('descriptorDiv').textContent = descriptor;
                // document.getElementById('scoreDiv').textContent = detection.detection.score;

                document.getElementById('imageData').value = imageData;
                document.getElementById('descriptor').value = descriptor;
                document.getElementById('score').value = detection.detection.score;


                document.getElementById('capture').click();




                // 3) Espera unos segundos y APAGA cámara
                setTimeout(() => {
                    try { webcam.stop(); } catch (e) { console.warn('stop webcam error', e); }
                }, STOP_DELAY_MS);

                console.log('Foto y descriptor capturados. Apagando cámara en', STOP_DELAY_MS, 'ms');
            }
        }

        function startDetection() {
            if (detectionIntervalId) clearInterval(detectionIntervalId);
            detectionIntervalId = setInterval(async () => {
                try {
                    const detection = await faceapi
                        .detectSingleFace(webcamElement, new faceapi.TinyFaceDetectorOptions({ inputSize: 416, scoreThreshold: 0.5 }))
                        .withFaceLandmarks()
                        .withFaceDescriptor();

                    if (!displaySize) return;
                    const ctx = canvas.getContext('2d');
                    ctx.clearRect(0, 0, canvas.width, canvas.height);

                    if (detection) {
                        const resized = faceapi.resizeResults(detection, displaySize);
                        faceapi.draw.drawDetections(canvas, resized);

                        // faceapi.draw.drawFaceLandmarks(canvas, resized)
                        checkAndCapture(detection);
                    }
                } catch (err) {
                    console.error('[face-detect] error:', err);
                }
            }, 100);
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
                // console.error('[init] Error:', err);
                document.getElementById('video-container').classList.add('d-none');
                // try { webcam.stop(); } catch (e) { console.warn('stop webcam error', e); }
                showError('No se pudo iniciar la cámara o y finalizar el proceso.');
            } finally {
                // try { webcam.stop(); } catch (e) { console.warn('stop webcam error', e); }
                showLoading(false);
            }
        }

        // window.addEventListener('load', init);
        $(document).ready(init);
    })();

</script>

<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-600');


        $('#frm_data').validator().on('submit', function(e) {
            if (e.isDefaultPrevented()) {
                $(this).mensaje_alerta(1, "El campo es obligatorio");
                return false;
            } else {
                e.preventDefault();
                $(this).simple_call('frm_data','url_personal_picture_save');
            }
        })

    })
</script>

