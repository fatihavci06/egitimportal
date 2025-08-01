<!DOCTYPE html>
<html lang="tr">
<?php

session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 2 or $_SESSION['role'] == 10002 or $_SESSION['role'] == 5 or $_SESSION['role'] == 10005)) {
    include_once "classes/dbh.classes.php";
    include_once "classes/addsupport.classes.php";
    include_once "classes/support-view.classes.php";
    $supportList = new ShowSupport();
    include_once "views/pages-head.php";
?>
    <!--end::Head-->
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
    <!--begin::Body-->

    <body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" data-kt-app-aside-enabled="true" data-kt-app-aside-fixed="true" data-kt-app-aside-push-toolbar="true" data-kt-app-aside-push-footer="true" class="app-default">
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
                            <!--begin::Toolbar-->
                            <?php include_once "views/toolbar.php"; ?>
                            <!--end::Toolbar-->
                            <div class="row container-fluid" style="margin-top:-19px; padding-right:0px;margin-right:0px !important;width:auto !important;">
                                <header class="container-fluid bg-custom-light py-3 d-flex justify-content-between align-items-center
                                             border-top border-bottom border-custom-red mb-2" style="    margin-top: 19px; border-width: 5px !important; height:85px;margin-bottom: 26px !important;">

                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-danger me-3 shadow icon-circle-lg d-flex justify-content-center align-items-center"
                                            style="width: 65px; height: 65px;">
                                            <i class="fa-regular fa-user fa-2x text-white"></i>
                                        </div>

                                        <h1 class="fs-3 fw-bold text-dark mb-0">Çözülmüş Destek Talepleri</h1>
                                    </div>

                                </header>
                            </div>
                            <!--begin::Content-->
                            <div id="kt_app_content" class="app-content flex-column-fluid">
                                <!--begin::Content container-->
                                <div id="kt_app_content_container" class="app-container container-fluid">
                                    <!--begin::Card-->
                                    <div class="card">
                                        <!--begin::Card body-->
                                        <div class="card-body pt-0">
                                            <!--begin::Table-->
                                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table">
                                                <thead>

                                                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                                        <th class="min-w-125px">Başlık</th>
                                                        <th class="min-w-125px">Konu</th>
                                                        <th class="min-w-125px">Son Yazan</th>
                                                        <th class="min-w-125px">Tarih - Saat</th>
                                                        <th class="text-end min-w-70px">İşlemler</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="fw-semibold text-gray-600">
                                                    <?php
                                                    if ($_SESSION['role'] == 2 or $_SESSION['role'] == 5 or $_SESSION['role'] == 10005) {
                                                        $supportList->getSupportSolvedList($_SESSION['id']);
                                                    }
                                                    if ($_SESSION['role'] == 1) {
                                                        $supportList->getSupportSolvedAdminList();
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                            <!--end::Table-->
                                        </div>
                                        <!--end::Card body-->
                                    </div>
                                    <!--end::Card-->
                                    <!--begin::Modals-->
                                    <!--begin::Modal - Customers - Add-->
                                    <?php // include_once "views/topics/add_topic.php" 
                                    ?>
                                    <!--end::Modal - Customers - Add-->
                                    <!--end::Modals-->
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
        <script src="assets/js/custom/apps/showsupport/list/export.js"></script>
        <script src="assets/js/custom/apps/showsupport/list/list.js"></script>
        <script src="assets/js/custom/apps/showsupport/add.js"></script>
        <script src="assets/js/widgets.bundle.js"></script>
        <script src="assets/js/custom/widgets.js"></script>
        <script src="assets/js/custom/apps/chat/chat.js"></script>
        <script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
        <script src="assets/js/custom/utilities/modals/create-account.js"></script>
        <script src="assets/js/custom/utilities/modals/create-app.js"></script>
        <script src="assets/js/custom/utilities/modals/users-search.js"></script>
        <!--end::Custom Javascript-->
        <!--end::Javascript-->
    </body>
    <!--end::Body-->

</html>
<?php } else {
    header("location: index");
}
