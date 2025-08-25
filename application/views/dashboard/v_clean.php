<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="row" id="filial_window_all">
                <?php
                $this->load->view('clean/v_filial',['filials' => $filials])
                ?>
</div>
