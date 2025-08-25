<?php
defined('BASEPATH') or exit('No direct script access allowed');
$msg = isset($msg) ? $msg : 'Lo siento algo salio mal';
?>
<div class="modal-body">

    <div class="big-error-w"><h1>404</h1>
        <h5>Page not Found</h5>
        <h4><?=$msg?></h4>
    </div>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-danger waves-effect" data-bs-dismiss="modal">Cerrar Ventana</button>
</div>