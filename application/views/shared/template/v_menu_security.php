<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<!--**********************************
    Nav header start
***********************************-->
<div class="nav-header">
    <a href="<?=base_url('/'); ?>" class="brand-logo">
        <img class="logo-abbr" src="<?=base_url('assets/'); ?>images/logo.png" alt="">
        <img class="logo-compact" src="<?=base_url('assets/'); ?>images/logo-text.png" alt="">
        <img class="brand-title" src="<?=base_url('assets/'); ?>images/logo-text.png" alt="">
    </a>

    <div class="nav-control">
        <div class="hamburger">
            <span class="line"></span><span class="line"></span><span class="line"></span>
        </div>
    </div>
</div>
<!--**********************************
    Nav header end
***********************************-->


<!--**********************************
    Header start
***********************************-->
<div class="header">
    <div class="header-content">
        <nav class="navbar navbar-expand">
            <div class="collapse navbar-collapse justify-content-between">
                <div class="header-left">

                </div>
                <ul class="navbar-nav header-right">
                    <li class="nav-item">
                        <a class="nav-link btn btn-danger text-white " href="<?=base_url()?>">
                            <i class="fas fa-home"></i> Inicio
                        </a>
                    </li>
                    <?php
                        if(in_array($user->u_profile,[4])):
                    ?>

                            <li class="nav-item">
                                <a class="nav-link btn btn-info text-white " href="<?=base_url('chart')?>">
                                    <i class="fas fa-area-chart"></i> Reporte
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link btn btn-info text-white " href="<?=base_url('clean')?>">
                                    <i class="fas fa-area-chart"></i> Reporte Limpieza
                                </a>
                            </li>

                    <?php
                    endif;
                    ?>



                                        <li class="nav-item">
                                            <a class="nav-link btn btn-danger text-white " href="javascript:void(0)"  onclick='$(this).forms_modal({"page" : "maintenance_request","title" : "Ticket"})'>
                                                <i class="fas fa-screwdriver-wrench"></i> Crear Ticket
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link btn btn-danger text-white " href="my_tickets">
                                                <i class="fas fa-screwdriver-wrench"></i> Mis ticket
                                            </a>
                                        </li>
                    <!--                        <div class="dropdown-menu dropdown-menu-right">-->
                    <!--                            <div id="dlab_W_Notification1" class="widget-media dlab-scroll p-3 height380">-->
                    <!--                                <ul class="timeline">-->
                    <!--                                    <li>-->
                    <!--                                        <div class="timeline-panel">-->
                    <!--                                            <div class="media mr-2">-->
                    <!--                                                <img alt="image" width="50" src="--><?php //=base_url('assets/'); ?><!--images/avatar/1.jpg">-->
                    <!--                                            </div>-->
                    <!--                                            <div class="media-body">-->
                    <!--                                                <h6 class="mb-1">Dr sultads Send you Photo</h6>-->
                    <!--                                                <small class="d-block">29 July 2020 - 02:26 PM</small>-->
                    <!--                                            </div>-->
                    <!--                                        </div>-->
                    <!--                                    </li>-->
                    <!--                                    <li>-->
                    <!--                                        <div class="timeline-panel">-->
                    <!--                                            <div class="media mr-2 media-info">-->
                    <!--                                                KG-->
                    <!--                                            </div>-->
                    <!--                                            <div class="media-body">-->
                    <!--                                                <h6 class="mb-1">Resport created successfully</h6>-->
                    <!--                                                <small class="d-block">29 July 2020 - 02:26 PM</small>-->
                    <!--                                            </div>-->
                    <!--                                        </div>-->
                    <!--                                    </li>-->
                    <!--                                    <li>-->
                    <!--                                        <div class="timeline-panel">-->
                    <!--                                            <div class="media mr-2 media-success">-->
                    <!--                                                <i class="fa fa-home"></i>-->
                    <!--                                            </div>-->
                    <!--                                            <div class="media-body">-->
                    <!--                                                <h6 class="mb-1">Reminder : Treatment Time!</h6>-->
                    <!--                                                <small class="d-block">29 July 2020 - 02:26 PM</small>-->
                    <!--                                            </div>-->
                    <!--                                        </div>-->
                    <!--                                    </li>-->
                    <!--                                    <li>-->
                    <!--                                        <div class="timeline-panel">-->
                    <!--                                            <div class="media mr-2">-->
                    <!--                                                <img alt="image" width="50" src="--><?php //=base_url('assets/'); ?><!--images/avatar/1.jpg">-->
                    <!--                                            </div>-->
                    <!--                                            <div class="media-body">-->
                    <!--                                                <h6 class="mb-1">Dr sultads Send you Photo</h6>-->
                    <!--                                                <small class="d-block">29 July 2020 - 02:26 PM</small>-->
                    <!--                                            </div>-->
                    <!--                                        </div>-->
                    <!--                                    </li>-->
                    <!--                                    <li>-->
                    <!--                                        <div class="timeline-panel">-->
                    <!--                                            <div class="media mr-2 media-danger">-->
                    <!--                                                KG-->
                    <!--                                            </div>-->
                    <!--                                            <div class="media-body">-->
                    <!--                                                <h6 class="mb-1">Resport created successfully</h6>-->
                    <!--                                                <small class="d-block">29 July 2020 - 02:26 PM</small>-->
                    <!--                                            </div>-->
                    <!--                                        </div>-->
                    <!--                                    </li>-->
                    <!--                                    <li>-->
                    <!--                                        <div class="timeline-panel">-->
                    <!--                                            <div class="media mr-2 media-primary">-->
                    <!--                                                <i class="fa fa-home"></i>-->
                    <!--                                            </div>-->
                    <!--                                            <div class="media-body">-->
                    <!--                                                <h6 class="mb-1">Reminder : Treatment Time!</h6>-->
                    <!--                                                <small class="d-block">29 July 2020 - 02:26 PM</small>-->
                    <!--                                            </div>-->
                    <!--                                        </div>-->
                    <!--                                    </li>-->
                    <!--                                </ul>-->
                    <!--                            </div>-->
                    <!--                            <a class="all-notification" href="javascript:void(0)">See all notifications <i class="ti-arrow-right"></i></a>-->
                    <!--                        </div>-->
                    <!--                    </li>-->
                    <li class="nav-item dropdown header-profile">
                        <a class="nav-link" href="javascript:void(0)" role="button" data-toggle="dropdown">
                            <img src="<?=base_url('assets/'); ?>images/profile/17.jpg" width="20" alt=""/>
                            <div class="header-info">
                                <span class="text-black"><?=$user->u_name?></span>
                                <p class="fs-12 mb-0"><?=$this->session->userdata('user_profile');?></p>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="<?=base_url('profile'); ?>" class="dropdown-item ai-icon">
                                <svg id="icon-user1" xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                <span class="ml-2">Profile </span>
                            </a>
                            <a href="<?=base_url('logout'); ?>" class="dropdown-item ai-icon">
                                <svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-danger" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                                <span class="ml-2">Desconectarse </span>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>
<!--**********************************
    Header end ti-comment-alt
***********************************-->

<!--**********************************
    Sidebar end
***********************************-->
<div class="content-body" style="margin-left:0 !important">
    <!-- row -->
    <div class="container-fluid">