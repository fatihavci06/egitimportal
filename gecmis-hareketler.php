<?php
session_start();
define('GUARD', true);

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 2 or $_SESSION['role'] == 3 or $_SESSION['role'] == 4 or $_SESSION['role'] == 5 or $_SESSION['role'] == 8)) {
    include_once "classes/dbh.classes.php";
    include_once "classes/student.classes.php";
    include_once "classes/student-view.classes.php";
    include_once "views/pages-head.php";


    $studentObj = new Student();
    $student = new ShowStudent();
    $query_student_id = $_GET['q'];
    $parent = $studentObj->getUserById($query_student_id);
    if (isset($parent['child_id'])) {
        header("HTTP/1.0 404 Not Found");
        header("Location: 404.php");
        exit();
    }
    if (($query_student_id == $parent['child_id']) && ($_SESSION['role'] == 5)) {

        $studentModel = $studentObj->getStudentByIdAndRole($parent['child_id']);

    } elseif (($_SESSION['id'] == $query_student_id) && ($_SESSION['role'] == 2)) {
        $studentModel = $studentObj->getStudentByIdAndRole($query_student_id);

    } elseif ($_SESSION['role'] == 1 or $_SESSION['role'] == 3 or $_SESSION['role'] == 8 or $_SESSION['role'] == 8) {
        $studentModel = $studentObj->getStudentByIdAndRole($query_student_id);
    }

    $studentModel = $studentObj->getStudentByIdAndRole($query_student_id);
    if (empty($studentModel)) {
        header("HTTP/1.0 404 Not Found");
        header("Location: 404.php");
        exit();
    }

    // if ($query_student_id != $_SESSION['id']){
    //     header("HTTP/1.0 404 Not Found");
    //     header("Location: ../404.php");
    //     exit(); 
    // }
    // $viewList = $student->getStudentProgress($studentModel['id'],$studentModel['class_id']);


    ?>

    <!DOCTYPE html>
    <html lang="tr">

    <body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true"
        data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
        data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true"
        data-kt-app-aside-enabled="true" data-kt-app-aside-fixed="true" data-kt-app-aside-push-toolbar="true"
        data-kt-app-aside-push-footer="true" class="app-default">
        <!--begin::Theme mode setup on page load-->
        <script>
            var defaultThemeMode = "light";
            var themeMode;
            if (document.documentElement) {
                if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                    themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
                } else {
                    if (localStorage.getItem("data-bs-theme") !== null) {
                        themeMode = localStorage.getItem("data-bs-theme");
                    } else {
                        themeMode = defaultThemeMode;
                    }
                }
                if (themeMode === "system") {
                    themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
                }
                document.documentElement.setAttribute("data-bs-theme", themeMode);
            }
        </script>
        <!--end::Theme mode setup on page load-->
        <!--begin::App-->
        <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
            <!--begin::Page-->
            <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
                <!--begin::Header-->
                <?php include_once "views/header.php"; ?>
                <!--end::Header-->
                <!--begin::Wrapper-->
                <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                    <!--begin::Sidebar-->
                    <?php include_once "views/sidebar.php"; ?>
                    <!--end::Sidebar-->
                    <!--begin::Main-->
                    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                        <!--begin::Content wrapper-->
                        <div class="d-flex flex-column flex-column-fluid">

                            <!--begin::Content-->
                            <div id="kt_app_content" class="app-content flex-column-fluid">
                                <!--begin::Content container-->
                                <div id="kt_app_content_container" class="app-container container-fluid">
                                    <!--begin::Card-->

                                    <div class="card h-100 "  style="margin-left: -15px; border:0px !important;box-shadow: 0px 0px 0px 0px rgba(0, 0, 0, 0.00);">
                                        <div class="card-body h-100 d-flex flex-column " style="padding: 2rem 1.25rem; padding-right: 0.5rem;">
                                            <?php $student->getHeaderImageStu(); ?>

                                            <div class="flex-grow-1">

                                                <?php $student->getStudentActivitiesRow($studentModel['id'], $studentModel['class_id']); ?>

                                            </div>
                                        </div>
                                    </div>

                                    <!--end::Card-->
                                </div>
                                <!--end::Content container-->
                            </div>
                            <!--end::Content-->
                        </div>
                        <!--end::Content wrapper-->
                        <!--begin::Footer-->
                        <?php include_once "views/footer.php"; ?>
                        <!--end::Footer-->
                    </div>
                    <!--end:::Main-->
                    <!--begin::aside-->
                    <?php include_once "views/aside.php"; ?>
                    <!--end::aside-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Page-->
        </div>
        <!--end::App-->
        <!--begin::Scrolltop-->
        <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
            <i class="ki-duotone ki-arrow-up">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </div>
        <!--end::Scrolltop-->
        <!--begin::Modals-->
        <!--begin::Modal - Upgrade plan-->
        <div class="modal fade" id="kt_modal_upgrade_plan" tabindex="-1" aria-hidden="true">
            <!--begin::Modal dialog-->
            <div class="modal-dialog modal-xl">
                <!--begin::Modal content-->
                <div class="modal-content rounded">
                    <!--begin::Modal header-->
                    <div class="modal-header justify-content-end border-0 pb-0">
                        <!--begin::Close-->
                        <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                            <i class="ki-duotone ki-cross fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </div>
                        <!--end::Close-->
                    </div>
                    <!--end::Modal header-->
                </div>
                <!--end::Modal content-->
            </div>
            <!--end::Modal dialog-->
        </div>
        <!--end::Modal - Upgrade plan-->
        <!--end::Modals-->
        <!--begin::Javascript-->
        <script>
            var hostUrl = "assets/";
        </script>
        <!--begin::Global Javascript Bundle(mandatory for all pages)-->
        <script src="assets/plugins/global/plugins.bundle.js"></script>
        <script src="assets/js/scripts.bundle.js"></script>
        <!--end::Global Javascript Bundle-->
        <!--begin::Vendors Javascript(used for this page only)-->
        <script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
        <!--end::Vendors Javascript-->
        <!--begin::Custom Javascript(used for this page only)-->
        <script src="assets/js/custom/apps/onestudentsprogress/list/list.js"></script>
        <script src="assets/js/widgets.bundle.js"></script>
        <script src="assets/js/custom/widgets.js"></script>
        <!--end::Custom Javascript-->
        <!--end::Javascript-->
    </body>

    </html>
    <?php
} else {
    header("location: index.php");
    exit();
}