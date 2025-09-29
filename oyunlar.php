<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 3 or $_SESSION['role'] == 8)) {
    include_once "classes/dbh.classes.php";
    include_once "classes/classes.classes.php";
    include_once "classes/games.classes.php";
    include_once "classes/games-view.classes.php";
    $games = new ShowGame();
    $classes = new Classes();
    $classList = $classes->getClassesList($_SESSION['school_id']);
    

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
                            <?php include_once "views/toolbar.php"; ?>
                            <!--end::Toolbar-->
                            <!--begin::Content-->
                            <div id="kt_app_content" class="app-content flex-column-fluid">
                                <!--begin::Content container-->
                                <div id="kt_app_content_container" class="app-container container-fluid">
                                    <!--begin::Card-->
                                    <div class="card">
                                        <!--begin::Card header-->
                                        <div class="card-header border-0 pt-6">
                                            <!--begin::Card title-->
                                            <div class="card-title d-flex align-items-center justify-content-between">
                                                <!--begin::Search-->
                                                <div class="d-flex align-items-center position-relative my-1 me-3">
                                                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                    <input type="text" data-kt-customer-table-filter="search"
                                                        class="form-control form-control-solid w-250px ps-12"
                                                        placeholder="Oyun Ara" />
                                                </div>
                                                <!--end::Search-->

                                                <!--begin::Filter-->
                                                <div class="d-flex align-items-center my-1">
                                                    <select id="classFilter" class="form-select w-300px me-2">
                                                        <option value="">Tüm Sınıflar</option>
                                                        <?php foreach ($classList as $class) { ?>
                                                            <option value="<?php echo $class['name']; ?>"><?php echo $class['name']; ?></option>
                                                        <?php } ?>
                                                        
                                                    </select>
                                                    <button type="button" id="applyFilter" class="btn btn-primary btn-sm" style="margin-left: 65px;">Filtrele</button>
                                                </div>
                                                <!--end::Filter-->
                                            </div>
                                            <!--begin::Card title-->
                                            <!--begin::Card toolbar-->
                                            <div class="card-toolbar">
                                                <!--begin::Toolbar-->
                                                <div class="d-flex justify-content-end"
                                                    data-kt-customer-table-toolbar="base">
                                                    <!--begin::Add school-->
                                                    <button type="button" class="btn btn-primary btn-sm"
                                                        data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer">Oyun
                                                        Ekle</button>
                                                    <!--end::Add school-->
                                                </div>
                                                <!--end::Toolbar-->
                                                <!--begin::Group actions-->
                                                <div class="d-flex justify-content-end align-items-center d-none"
                                                    data-kt-customer-table-toolbar="selected">
                                                    <div class="fw-bold me-5">
                                                        <span class="me-2"
                                                            data-kt-customer-table-select="selected_count"></span>Seçildi
                                                    </div>
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        data-kt-customer-table-select="delete_selected">Seçilenleri Pasif
                                                        Yap</button>
                                                </div>
                                                <!--end::Group actions-->
                                            </div>
                                            <!--end::Card toolbar-->
                                        </div>
                                        <!--end::Card header-->
                                        <!--begin::Card body-->
                                        <div class="card-body pt-0">
                                            <!--begin::Filter Select-->

                                            <!--end::Filter Select-->

                                            <!--begin::Table-->
                                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table">
                                                <thead>
                                                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                                        <th class="w-10px pe-2">
                                                            <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                                <input class="form-check-input" type="checkbox"
                                                                    data-kt-check="true"
                                                                    data-kt-check-target="#kt_customers_table .form-check-input"
                                                                    value="1" />
                                                            </div>
                                                        </th>
                                                        <th class="min-w-40px">Görsel</th>
                                                        <th class="min-w-125px">Oyun Adı</th>
                                                        <th class="min-w-40px">Durum</th>
                                                        <th class="min-w-80px">Sınıf</th>
                                                        <th class="min-w-125px">Ders</th>
                                                        <th class="text-end min-w-70px">İşlemler</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="fw-semibold text-gray-600">
                                                    <?php $games->getGameList($_SESSION['school_id']); ?>
                                                </tbody>
                                            </table>
                                            <!--end::Table-->
                                        </div>
                                        <!--end::Card body-->
                                    </div>
                                    <!--end::Card-->
                                    <!--begin::Modals-->
                                    <!--begin::Modal - Customers - Add-->
                                    <?php include_once "views/games/add_game.php" ?>
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
        <script src="assets/js/custom/apps/games/list/export.js"></script>
        <script src="assets/js/custom/apps/games/list/list.js"></script>
        <script src="assets/js/custom/apps/games/add.js"></script>
        <script src="assets/js/widgets.bundle.js"></script>
        <script src="assets/js/custom/widgets.js"></script>
        <script src="assets/js/custom/apps/chat/chat.js"></script>
        <script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
        <script src="assets/js/custom/utilities/modals/create-account.js"></script>
        <script src="assets/js/custom/utilities/modals/create-app.js"></script>
        <script src="assets/js/custom/utilities/modals/users-search.js"></script>
        <script>
            $(document).ready(function() {
                var table = $('#kt_customers_table').DataTable();

                // Filtrele butonu
                $('#applyFilter').on('click', function() {
                    var selectedClass = $('#classFilter').val();
                    table.column(4).search(selectedClass).draw(); // 4. sütun = Sınıf
                });
            });
        </script>
        <!--end::Custom Javascript-->
        <!--end::Javascript-->
    </body>
    <!--end::Body-->

</html>
<?php } else {
    header("location: index");
}
