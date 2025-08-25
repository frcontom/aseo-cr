<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="row" id="all_filials">
                <?php
                $this->load->view('clean/v_filial',['filials' => $filials])
                ?>
</div>
