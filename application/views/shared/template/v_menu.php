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
<!--                    --><?php
//                        if(isset($btn_mantenance) and $btn_mantenance == true):
//                    ?>
                    <li class="nav-item">
                        <a class="nav-link btn btn-danger text-white " href="javascript:void(0)"  onclick='$(this).forms_modal({"page" : "maintenance_request","title" : "Ticket"})'>
                           C.Ticket
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-danger text-white " href="<?=base_url('my_tickets')?>">
                           M.ticket
                        </a>
                    </li>
<!--                        --><?php
//                        endif;
//                        ?>

                    <li class="nav-item dropdown notification_dropdown">
                        <a class="nav-link bell bell-link" href="javascript:void(0)">
                            <i class="flaticon-381-calendar-1 icon_reloading" style="font-size: 26px;"></i>
                            <span class="badge text-white badge-primary rounded-circle"><?=$this->class_security->events_modifid()['count']?></span>
                        </a>
                    </li>


<!--                    <li class="nav-item dropdown notification_dropdown">-->
<!--                        <a class="nav-link  ai-icon" href="javascript:void(0)" role="button" data-toggle="dropdown">-->
<!--                            <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">-->
<!--                                <path d="M23.3333 19.8333H23.1187C23.2568 19.4597 23.3295 19.065 23.3333 18.6666V12.8333C23.3294 10.7663 22.6402 8.75902 21.3735 7.12565C20.1068 5.49228 18.3343 4.32508 16.3333 3.80679V3.49996C16.3333 2.88112 16.0875 2.28763 15.6499 1.85004C15.2123 1.41246 14.6188 1.16663 14 1.16663C13.3812 1.16663 12.7877 1.41246 12.3501 1.85004C11.9125 2.28763 11.6667 2.88112 11.6667 3.49996V3.80679C9.66574 4.32508 7.89317 5.49228 6.6265 7.12565C5.35983 8.75902 4.67058 10.7663 4.66667 12.8333V18.6666C4.67053 19.065 4.74316 19.4597 4.88133 19.8333H4.66667C4.35725 19.8333 4.0605 19.9562 3.84171 20.175C3.62292 20.3938 3.5 20.6905 3.5 21C3.5 21.3094 3.62292 21.6061 3.84171 21.8249C4.0605 22.0437 4.35725 22.1666 4.66667 22.1666H23.3333C23.6428 22.1666 23.9395 22.0437 24.1583 21.8249C24.3771 21.6061 24.5 21.3094 24.5 21C24.5 20.6905 24.3771 20.3938 24.1583 20.175C23.9395 19.9562 23.6428 19.8333 23.3333 19.8333Z" fill="#67636D"/>-->
<!--                                <path d="M9.98193 24.5C10.3863 25.2088 10.971 25.7981 11.6767 26.2079C12.3823 26.6178 13.1839 26.8337 13.9999 26.8337C14.816 26.8337 15.6175 26.6178 16.3232 26.2079C17.0289 25.7981 17.6136 25.2088 18.0179 24.5H9.98193Z" fill="#67636D"/>-->
<!--                            </svg>-->
<!--                            <span class="badge light text-white bg-primary rounded-circle">4</span>-->
<!--                        </a>-->
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
                                <span class="ml-2">Perfil </span>
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
    Sidebar start
***********************************-->
<div class="dlabnav">
    <div class="dlabnav-scrolly">
<!--    <div class="dlabnav-scroll">-->
        <ul class="metismenu" id="menu">
            <li><a href="<?=base_url()?>" class="ai-icon" aria-expanded="false">
                    <i class="flaticon-381-networking"></i>
                    <span class="nav-text">Dasboard</span>
                </a>
            </li>

            <?=$this->M_menu->generate($user->u_profile)?>

            <?php

             $area = $user->u_area;
             $area_type = $user->u_area_type;
            if($area_type != '' and $area_type != 0):

                echo  "<li><a class='has-arrow ai-icon' href='javascript:void(0);' aria-expanded='false'>";
                echo  "<i class='flaticon-381-networking'></i>";
                echo  "<span class='nav-text'>Tickets</span>";
                echo  "</a>";
                echo  "  <ul aria-expanded='false'>";

                if($area_type == 1){
                //admon


                if($user->u_profile != 2):
                ?>
                <li><a href="<?=base_url('ticket_admon')?>" class="ai-icon" aria-expanded="false">
                        <i class="flaticon-381-networking"></i>
                        <span class="nav-text">Admon ticket</span>
                    </a>
                </li>
            <?php
                endif;
            }elseif($area_type == 2){
                // user
                ?>
                <li><a href="<?=base_url('ticket_resolved')?>" class="ai-icon" aria-expanded="false">
                        <span class="nav-text">Ticket en proceso</span>
                    </a>
                </li>
            <?php
            }else{
                // admin user
                    if($user->u_profile != 2):
                    ?>
                <li><a href="<?=base_url('ticket_admon')?>" class="ai-icon" aria-expanded="false">
                        <span class="nav-text">Admon ticket</span>
                    </a>
                </li>
                        <?php

                    endif;
                        ?>
                <li><a href="<?=base_url('ticket_resolved')?>" class="ai-icon" aria-expanded="false">
                        <span class="nav-text">Ticket en proceso</span>
                    </a>
                </li>
                <?php
            }

               echo  "</ul></li>";


            endif;
            ?>



        </ul>

    </div>
</div>
<!--**********************************
    Sidebar end
***********************************-->
<div class="content-body">
    <!-- row -->
    <div class="container-fluid">

