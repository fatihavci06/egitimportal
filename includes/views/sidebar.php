<?php
if (!defined('GUARD')) {
    die('Erişim yasak!');
}

include_once "classes/dbh.classes.php";
include "classes/menu.classes.php";
include "classes/menu-view.classes.php";

$menu1 = new ShowMenu();

?>
<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <!--begin::Main-->
    <div class="d-flex flex-column justify-content-between h-100 hover-scroll-overlay-y my-2 mx-5 d-flex flex-column" id="kt_app_sidebar_main" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_header" data-kt-scroll-wrappers="#kt_app_main" data-kt-scroll-offset="5px">
        <!--begin::Sidebar menu-->
        <div id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false" class="flex-column-fluid menu menu-sub-indention menu-column menu-rounded menu-active-bg mb-7">
            <!--begin:Menu item-->
            <?php
            if ($_SESSION['role'] == 1) {
                $menu1->showMenuSuperAdminList();
            }
            elseif ($_SESSION['role'] == 2) {
                $menu1->showMenuSuperAdminList();
            }
            elseif ($_SESSION['role'] == 3) {
                $menu1->showMenuSchoolAdminList();
            }
            elseif ($_SESSION['role'] == 3) {
                $menu1->showMenuSchoolAdminList();
            }
            elseif ($_SESSION['role'] == 4){
                $menu1->showMenuSchoolTeacherList();
            }
            ?>
            <!--end:Menu item-->
            <?php if ($_SESSION['role'] == 1) { ?>
            <?php } ?>
        </div>
        <!--end::Sidebar menu-->
    </div>
    <!--end::Main-->
</div>