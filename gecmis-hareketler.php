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


    include_once "classes/units.classes.php";
    include_once "classes/units-view.classes.php";
    include_once "classes/classes.classes.php";
    $units = new ShowUnit();
    $lesson = new Classes();
    $url = $_SERVER['REQUEST_URI'];
    $parts = explode('/', trim($url, '/'));
    $slug = $parts[2] ?? null;

    $lessons = $lesson->getLessonsList($_SESSION['class_id']);
    $lessonInfo = $lesson->getLessonBySlug($slug);




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
    
    if ($query_student_id != $_SESSION['id']) {
        header("HTTP/1.0 404 Not Found");
        header("Location: ../404.php");
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

                                    <div class="card" style="margin-top: -25px;margin-left: -15px;">
                                        <!--begin::Body-->

                                        <div class="card-body p-lg-7">
                                            <!--begin::Section-->
                                            <div class="mb-19">
                                                <div class="row align-items-center mb-12">
                                                    <?php $student->getHeaderImageStu(); ?>

                                                </div>

                                                <div class="row align-items-center mb-12" style="margin-top: -30px;">
                                                    <div class="col-lg-2 col-3 d-flex ">
                                                        <h5 class="fs-2x text-gray-900 mb-0"
                                                            style="font-size:15px!important">
                                                            Dersler

                                                        </h5>
                                                    </div>
                                                    <div class="col-9 col-lg-10 d-flex justify-content-center">

                                                    </div>
                                                </div>

                                                <div class="row" style="margin-top: -30px;margin-left: -30px;">
                                                    <div class="col-3 col-lg-2">
                                                        <div class="row g-5">
                                                            <?php foreach ($lessons as $l): ?>
                                                                <?php if ($l['name'] !== 'Robotik Kodlama' && $l['name'] !== 'Ders Deneme'): ?>
                                                                    <div class="col-12 mb-4 text-center">
                                                                        <a href="ders/<?= urlencode($l['slug']) ?>">
                                                                            <img src="assets/media/icons/dersler/<?= htmlspecialchars($l['icons']) ?>"
                                                                                alt="Icon" class="img-fluid"
                                                                                style="width: 65px; height: 65px; object-fit: contain;" />
                                                                            <div class="mt-2 fw-semibold">
                                                                                <?= htmlspecialchars($l['name']) ?>
                                                                            </div>
                                                                        </a>
                                                                    </div>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>

                                                    <div class="col-9 col-lg-10">
                                                        <div class="row g-5">

                                                            <?php $student->getStudentActivitiesRow($studentModel['id'], $studentModel['class_id']); ?>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!--end::Section-->
                                        </div>
                                        <!--end::Body-->
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
            <style>
        /* Genel Stil İyileştirmeleri */

        .main-card-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            border: 1px solid #e0e0e0;
        }

        .custom-card {
            border: none;
            padding: 0px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            background-color: white;
            margin-bottom: 25px;
        }

        .card-title-custom {
            font-size: 1.2rem;
            font-weight: 700;
            color: #ed5606;
            margin-bottom: 15px;
        }

        .content-wrapper {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .icon-small {
            font-size: 50px !important;
            color: #e83e8c !important;
        }



        .btn-custom {
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 600;
            transition: background-color 0.3s ease;
            margin-top: 15px;
        }

        #myTable thead {
            display: none;
        }

        .btn-custom:hover {
            background-color: #1a9c7b;
        }

        .left-align {
            margin-left: 0;
            margin-right: auto;
        }

        .right-align {
            margin-left: auto;
            margin-right: 0;
        }

        .left-align .card-body {
            align-items: flex-start;
            text-align: left;
        }

        .left-align .content-wrapper {
            flex-direction: row;
        }

        .right-align .card-body {
            align-items: flex-end;
            text-align: right;
        }

        .right-align .content-wrapper {
            flex-direction: row-reverse;
        }

        .card-body {
            display: flex;
            flex-direction: column;
        }

        .bg-custom-light {
            background-color: #e6e6fa;
            /* Light purple */
        }

        .border-custom-red {
            border-color: #d22b2b !important;
        }

        .text-custom-cart {
            color: #6a5acd;
            /* Slate blue for the cart */
        }

        /* For the circular icon, we'll use a larger padding or fixed size */
        .icon-circle-lg {
            width: 60px;
            /* fixed width */
            height: 60px;
            /* fixed height */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-circle-lg img {
            max-width: 100%;
            /* Ensure image scales within the circle */
            max-height: 100%;
        }


        /* Animasyonlar */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
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