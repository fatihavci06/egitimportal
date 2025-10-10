<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 10001 or $_SESSION['role'] == 10002 or $_SESSION['role'] == 10005)) {
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";
    require_once "classes/student.classes.php";
    $unit = new Classes();
    $studentInfo = new Student();


    if ($_SESSION['role'] == 10005) {
        $getPreSchoolStudent = $studentInfo->getPreSchoolStudentsInfoForParents($_SESSION['id']);
        $class_idsi = $getPreSchoolStudent[0]['class_id'];
        $studentidsi = $getPreSchoolStudent[0]['id'];
    } else {
        $class_idsi = $_SESSION['class_id'];
        $studentidsi = $_SESSION['id'];
    }



    include_once "views/pages-head.php";
?>


    <body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" class="app-default">
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
        <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
            <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
                <?php include_once "views/header.php"; ?>
                <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                    <?php include_once "views/sidebar.php"; ?>
                    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                        <div class="d-flex flex-column flex-column-fluid">
                            <?php include_once "views/toolbar.php"; ?>

                            <div id="kt_app_content" class="app-content flex-column-fluid">
                                <div id="kt_app_content_container" class="app-container container-fluid">
                                    <div class="card-body pt-5 ">
                                        <div class="row container-fluid" style="margin-top:-25px;">
                                            <header class="container-fluid bg-custom-light py-3 d-flex justify-content-between align-items-center
                    border-top border-bottom border-custom-red mb-2" style="border-width: 5px !important; height:85px;margin-bottom: 26px !important;">

                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-danger me-3 shadow icon-circle-lg d-flex justify-content-center align-items-center"
                                                        style="width: 65px; height: 65px;">
                                                        <i class="fas fa-bullseye fa-2x text-white"></i>
                                                    </div>

                                                    <h1 class="fs-3 fw-bold text-dark mb-0">Pskolog Köşesi</h1>
                                                </div>
                                            </header>
                                        </div>

                                        <style>
                                            /* ... (Mevcut CSS Kodları) ... */
                                            .card-psikoloji {
                                                background-color: #ff9933;
                                                color: white;
                                            }

                                            .card-tavsiye {
                                                background-color: #3cb371;
                                                color: white;
                                            }

                                            .card-atolye {
                                                background-color: #9966cc;
                                                color: white;
                                            }

                                            .custom-card {
                                                border: none;
                                                border-radius: 15px;
                                                margin-bottom: 30px;
                                                /* Boşluğu biraz artırdım */
                                                box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
                                                /* Gölgeyi biraz belirginleştirdim */
                                            }

                                            .emoji-icon {
                                                font-size: 2.5rem;
                                                /* Emoji boyutunu daha da büyüttüm */
                                                margin-right: 20px;
                                            }

                                            .custom-h2 {
                                                font-size: 1.75rem;
                                                /* Başlık metnini daha da büyüttüm */
                                                font-weight: 700;
                                            }
                                        </style>

                                        <div class="row justify-content-start">

                                            <div class="col-8">
                                                <div class="card custom-card card-psikoloji p-4">
                                                    <a href="psikolojik-test-listesi" class="text-decoration-none text-dark text-hover-primary">
                                                        <div class="d-flex align-items-center">
                                                            <span class="emoji-icon me-3">🧠✨</span>
                                                            <h2 class="custom-h2 mb-0">

                                                                Psikolojik Testler

                                                            </h2>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="col-8">
                                                <div class="card custom-card card-tavsiye p-4">
                                                    <a href="uzman-psikologdan-aileye-tavsiyeler" class="text-decoration-none text-dark text-hover-primary">
                                                    <div class="d-flex align-items-center">
                                                        <span class="emoji-icon">👨‍⚕️🗣️</span>
                                                        <h2 class="custom-h2 mb-0">Uzman Psikologtan Aileye Tavsiyeler</h2>
                                                    </div>
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="col-8">
                                                <div class="card custom-card card-atolye p-4">
                                                    <div class="d-flex align-items-center">
                                                        <span class="emoji-icon">👨‍👩‍👧‍👦🎨</span>
                                                        <h2 class="custom-h2 mb-0">Aile Atölyeleri</h2>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php include_once "views/footer.php"; ?>
                </div>
                <?php include_once "views/aside.php"; ?>
            </div>
        </div>
        </div>
        <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
            <i class="ki-duotone ki-arrow-up">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </div>
        <script>
            var hostUrl = "assets/";
        </script>
        <script src="assets/plugins/global/plugins.bundle.js"></script>
        <script src="assets/js/scripts.bundle.js"></script>

        <script src="assets/js/widgets.bundle.js"></script>
        <script src="assets/js/custom/widgets.js"></script>
        <script src="assets/js/custom/apps/chat/chat.js"></script>
        <script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
        <script src="assets/js/custom/utilities/modals/create-account.js"></script>
        <script src="assets/js/custom/utilities/modals/create-app.js"></script>
        <script src="assets/js/custom/utilities/modals/users-search.js"></script>
        <script>

        </script>

    </body>

</html>
<?php } else {
    header("location: index");
}
?>