<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and $_SESSION['role'] == 1  or $_SESSION['role'] == 2 or $_SESSION['role'] == 10002) {
    include_once "classes/dbh.classes.php";
    include_once "classes/addhomework-std.classes.php";
    include_once "classes/homework-std-view.classes.php";
    $contents = new ShowHomeworkContents();
    $slug = $_GET['q'];

    include_once "views/pages-head.php";
?>
    <!--end::Head-->
    <!--begin::Body-->

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
                            <!--begin::Toolbar-->

                            <!--end::Toolbar-->
                            <!--begin::Content-->
                            <div id="kt_app_content" class="app-content flex-column-fluid">
                                <!--begin::Content container-->
                                <div id="kt_app_content_container" class="app-container container-fluid">
                                    <!--begin::Layout-->
                                    <div class="d-flex flex-column flex-xl-row">
                                        <!--begin::Content-->
                                        <div class="flex-lg-row-fluid">
                                            <!--begin:::Tab content-->
                                            <div class="tab-content" id="myTabContent">
                                                <!--begin:::Tab pane-->
                                                <div class="tab-pane fade show active" id="konular" role="tabpanel">
                                                    <!--begin::Card-->
                                                    <div class="card pt-4 mb-6 mb-xl-9" style="margin-top:-30px;">
                                                        <header class="container-fluid bg-custom-light py-3 d-flex justify-content-between align-items-center
                                             border-top border-bottom border-custom-red" style="border-width: 5px !important;">

                                                            <div class="d-flex align-items-center">
                                                                <div class="rounded-circle bg-danger me-3 shadow icon-circle-lg d-flex justify-content-center align-items-center"
                                                                    style="width: 80px; height: 80px;">
                                                                    <i class="fas fa-calendar-check fa-2x text-white"></i>
                                                                </div>

                                                                <h1 class="fs-3 fw-bold text-dark mb-0">Ödev Detayı</h1>
                                                            </div>

                                                        </header>
                                                        <?php $contents->showHomeworkDetailForStudent($slug); ?>
                                                    </div>
                                                    <!--end::Card-->
                                                </div>
                                                <!--end:::Tab pane-->
                                            </div>
                                            <!--end:::Tab content-->
                                        </div>
                                        <!--end::Content-->
                                    </div>
                                    <!--end::Layout-->
                                    <!--begin::Modals-->
                                    <!--begin::Modal - New Address-->
                                    <div class="modal fade" id="kt_modal_update_customer" tabindex="-1" aria-hidden="true">
                                        <!--begin::Modal dialog-->
                                        <div class="modal-dialog modal-dialog-centered mw-650px">
                                            <!--begin::Modal content-->
                                            <div class="modal-content">
                                                <!--begin::Form-->
                                                <?php // $subTopics->updateOneSubTopic($slug); 
                                                ?>
                                                <!--end::Form-->
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Modal - New Address-->
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
        <!--begin::Modals-->
        <!--end::Modals-->
        <!--begin::Javascript-->
        <script>
            var hostUrl = "assets/";
        </script>
        <script src="https://player.vimeo.com/api/player.js"></script>
        <script src="assets/js/custom/trackTimeOnVimeo.js"></script>

        <!--begin::Global Javascript Bundle(mandatory for all pages)-->
        <script src="assets/plugins/global/plugins.bundle.js"></script>
        <script src="assets/js/scripts.bundle.js"></script>
        <!--end::Global Javascript Bundle-->
        <!--begin::Vendors Javascript(used for this page only)-->
        <script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
        <!--end::Vendors Javascript-->
        <!--begin::Custom Javascript(used for this page only)-->
        <!-- <script src="assets/js/custom/apps/sub-topic-details/view/add-payment.js"></script>
		<script src="assets/js/custom/apps/sub-topic-details/view/adjust-balance.js"></script> -->
        <!-- <script src="assets/js/custom/apps/sub-topic-details/view/invoices.js"></script> -->
        <!-- <script src="assets/js/custom/apps/sub-topic-details/view/payment-method.js"></script> -->
        <script src="assets/js/custom/apps/sub-topic-details/view/payment-table.js"></script>
        <script src="assets/js/custom/apps/sub-topic-details/view/teachers-table.js"></script>
        <!-- <script src="assets/js/custom/apps/sub-topic-details/guncelle.js"></script> -->
        <!-- <script src="assets/js/custom/apps/sub-topic-details/view/statement.js"></script> -->
        <!-- <script src="assets/js/custom/apps/customers/update.js"></script> -->
        <script src="assets/js/widgets.bundle.js"></script>
        <script src="assets/js/custom/widgets.js"></script>
        <script src="assets/js/custom/apps/chat/chat.js"></script>
        <script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
        <script src="assets/js/custom/utilities/modals/create-account.js"></script>
        <script src="assets/js/custom/utilities/modals/create-app.js"></script>
        <script src="assets/js/custom/utilities/modals/new-card.js"></script>
        <script src="assets/js/custom/utilities/modals/users-search.js"></script>
        <!--end::Custom Javascript-->
        <!--end::Javascript-->
        <script>
            $(document).ready(function() {
                // DataTable'ı başlatın (eğer henüz başlatılmadıysa)
                var table = $('#kt_table_customers_payment').DataTable();

                // Arama input alanını seçin
                $('[data-kt-table-customers-payment-filter="search"]').on('keyup', function() {
                    // Input alanının değerini alın
                    var searchText = $(this).val();

                    // DataTable'ın arama fonksiyonunu kullanarak tabloyu filtreleyin
                    table.search(searchText).draw();
                });
            });

            $(document).ready(function() {
                // DataTable'ı başlatın (eğer henüz başlatılmadıysa)
                var tableTeachers = $('#kt_table_teachers').DataTable();

                // Arama input alanını seçin
                $('[data-kt-table-teachers-filter="search"]').on('keyup', function() {
                    // Input alanının değerini alın
                    var searchText2 = $(this).val();

                    // DataTable'ın arama fonksiyonunu kullanarak tabloyu filtreleyin
                    tableTeachers.search(searchText2).draw();
                });
            });
        </script>
    </body>
    <!--end::Body-->

</html>
<?php } else {
    header("location: index");
}
