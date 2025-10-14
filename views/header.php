<?php
ob_start();
if (!defined('GUARD')) {
    die('Direkt erişim yasak!');
}
$current_uri = $_SERVER['REQUEST_URI'];

?>
<script async src="https://www.googletagmanager.com/gtag/js?id=G-CJ093GJRRN"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-CJ093GJRRN');
</script>
<div id="kt_app_header" class="app-header d-flex flex-column flex-stack">
    <!--begin::Header main-->
    <div class="d-flex align-items-center flex-stack flex-grow-1">
        <div class="app-header-logo d-flex align-items-center flex-stack px-lg-10 mb-2" id="kt_app_header_logo">
            <!--begin::Sidebar mobile toggle-->
            <div class="btn btn-icon btn-active-color-primary w-35px h-35px ms-3 me-2 d-flex d-lg-none" id="kt_app_sidebar_mobile_toggle">
                <i class="ki-duotone ki-abstract-14 fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </div>
            <!--end::Sidebar mobile toggle-->
            <!--begin::Logo-->
            <a href="index" class="app-sidebar-logo">
                <img alt="Logo" src="assets/media/logos/lineup-campus-logo.jpg" class="h-70px theme-light-show" />
                <img alt="Logo" src="assets/media/logos/lineup-campus-logo.jpg" class="h-70px theme-dark-show" />
            </a>
            <!--end::Logo-->
            <!--begin::Sidebar toggle-->
            <div id="kt_app_sidebar_toggle" class="app-sidebar-toggle btn btn-sm btn-icon btn-color-warning me-n2 d-none d-lg-flex" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="app-sidebar-minimize">
                <i class="ki-duotone ki-exit-left fs-2x rotate-180">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </div>
            <!--end::Sidebar toggle-->
        </div>
        <!--begin::Navbar-->
        <div class="app-navbar flex-grow-1 justify-content-end" id="kt_app_header_navbar">
            <?php if ($_SESSION['role'] == 2) { ?>
            <div class="app-navbar-item d-flex align-items-center flex-lg-grow-1 me-2 ps-2 me-lg-0 fs-2 hosgeldin">
                <b>Hoş geldin <?php echo $_SESSION['name']; ?> haydi görevleri tamamlayarak puan toplamaya başla!</b>
            </div>
            <div class="app-navbar-item d-flex justify-content align-items-center flex-lg-grow-1 me-2 me-lg-0 maskotheader">
                <img src="assets/media/mascots/header-mascots.png" alt="Lineup Campus Maskotlar" class="h-50px theme-light-show" />
            </div>
            <?php }elseif($_SESSION['role'] == 10002 OR $_SESSION['role'] == 10005) { ?>
            <div class="app-navbar-item d-flex align-items-center flex-lg-grow-1 me-2 ps-2 me-lg-0 fs-2 hosgeldin">
                <b>Hoş geldin <?php echo $_SESSION['name']; ?>!</b>
            </div>
            <div class="app-navbar-item d-flex justify-content align-items-center flex-lg-grow-1 me-2 me-lg-0 maskotheader">
                <img src="assets/media/mascots/header-mascots.png" alt="Lineup Campus Maskotlar" class="h-75px theme-light-show" />
            </div>
            <?php } ?>
            <!--begin::Notifications-->
            <?php include "includes/views/notifications-announcements.php" ?>
            <!--end::Notifications-->
            <!--begin::Quick links-->
            <?php // include_once "views/quick_links.php" 
            ?>
            <!--end::Quick links-->
            <!--begin::User menu-->
            <?php include_once "views/user_menu.php" ?>
            <!--end::User menu-->
            <!--begin::Action-->
            <?php //include_once "views/action.php" 
            ?>
            <!--end::Action-->
            <!--begin::Header menu toggle-->
            <div class="app-navbar-item ms-3 ms-lg-4 ms-n2 me-3 d-flex d-lg-none mobilekaldir">
                <div class="btn btn-icon btn-custom btn-color-gray-600 btn-active-color-primary w-35px h-35px w-md-40px h-md-40px" id="kt_app_aside_mobile_toggle">
                    <i class="ki-duotone ki-burger-menu-2 fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                        <span class="path5"></span>
                        <span class="path6"></span>
                        <span class="path7"></span>
                        <span class="path8"></span>
                        <span class="path9"></span>
                        <span class="path10"></span>
                    </i>
                </div>
            </div>
            <!--end::Header menu toggle-->
        </div>
        <!--end::Navbar-->
    </div>
    <!--end::Header main-->
    <!--begin::Separator-->
    <div class="app-header-separator"></div>
    <!--end::Separator-->
</div>