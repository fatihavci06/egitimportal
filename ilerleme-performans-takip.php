<?php
session_start();
define('GUARD', true);

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 3 or $_SESSION['role'] == 2 or $_SESSION['role'] == 4 or $_SESSION['role'] == 5 or $_SESSION['role'] == 8)) {
    include_once "classes/dbh.classes.php";
    include_once "classes/school.classes.php";
    include_once "classes/school-view.classes.php";
    include_once "classes/student.classes.php";
    include_once "classes/student-view.classes.php";
    $waitingStudents = new Student();
    $students = new ShowStudent();
    $schools = new ShowSchool();
    include_once "views/pages-head.php";
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
                                        <?php if ($_SESSION['role'] == 1 or $_SESSION['role'] == 3 or $_SESSION['role'] == 8) { ?>

                                            <div class="card-header border-0 pt-6">
                                                <!--begin::Card title-->
                                                <div class="card-title">
                                                    <!--begin::Search-->
                                                    <div class="d-flex align-items-center position-relative my-1">
                                                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>
                                                        <input type="text" data-kt-customer-table-filter="search"
                                                            class="form-control form-control-solid w-250px ps-12"
                                                            placeholder="Öğrenci Ara" />
                                                    </div>
                                                    <!--end::Search-->
                                                </div>
                                                <!--begin::Card title-->
                                                <!--begin::Card toolbar-->
                                                <div class="card-toolbar">
                                                    <!--begin::Toolbar-->
                                                    <div class="d-flex justify-content-end"
                                                        data-kt-customer-table-toolbar="base">
                                                        <!--begin::Filter-->
                                                        <button type="button" class="btn btn-light-primary me-3"
                                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                            <i class="ki-duotone ki-filter fs-2">
                                                                <span class="path1"></span>
                                                                <span class="path2"></span>
                                                            </i>Filtre</button>
                                                        <!--begin::Menu 1-->
                                                        <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px"
                                                            data-kt-menu="true" id="kt-toolbar-filter">
                                                            <!--begin::Header-->
                                                            <div class="px-7 py-5">
                                                                <div class="fs-4 text-gray-900 fw-bold">Filtreleme</div>
                                                            </div>
                                                            <!--end::Header-->
                                                            <!--begin::Separator-->
                                                            <div class="separator border-gray-200"></div>
                                                            <!--end::Separator-->
                                                            <!--begin::Content-->
                                                            <div class="px-7 py-5">
                                                                <!--begin::Input group-->
                                                                <div class="mb-10">
                                                                    <!--begin::Label-->
                                                                    <label
                                                                        class="form-label fs-5 fw-semibold mb-3">Durum:</label>
                                                                    <!--end::Label-->
                                                                    <!--begin::Input-->
                                                                    <select class="form-select form-select-solid fw-bold"
                                                                        data-kt-select2="true" data-placeholder="Durum Seçin"
                                                                        data-allow-clear="true"
                                                                        data-kt-customer-table-filter="status"
                                                                        data-dropdown-parent="#kt-toolbar-filter">
                                                                        <option></option>
                                                                        <option value="Aktif">Aktif</option>
                                                                        <option value="Passive">Pasif</option>
                                                                    </select>
                                                                    <!--end::Input-->
                                                                </div>
                                                                <!--end::Input group-->
                                                                <div class="mb-10">
                                                                    <!--begin::Label-->
                                                                    <label
                                                                        class="form-label fs-5 fw-semibold mb-3">Okul:</label>
                                                                    <!--end::Label-->
                                                                    <!--begin::Input-->
                                                                    <select class="form-select form-select-solid fw-bold"
                                                                        data-kt-select2="true" data-placeholder="Okul Seçin"
                                                                        data-allow-clear="true"
                                                                        data-kt-customer-table-filter="school"
                                                                        data-dropdown-parent="#kt-toolbar-filter">
                                                                        <option></option>
                                                                        <?php $schools->getSchoolListFilter(); ?>
                                                                    </select>
                                                                    <!--end::Input-->
                                                                </div>
                                                                <!--end::Input group-->
                                                                <!--begin::Input group-->
                                                                <div class="mb-10">
                                                                    <!--begin::Label-->
                                                                    <label
                                                                        class="form-label fs-5 fw-semibold mb-3">Sınıf:</label>
                                                                    <!--end::Label-->
                                                                    <!--begin::Options-->
                                                                    <div class="d-flex flex-column flex-wrap fw-semibold"
                                                                        data-kt-customer-table-filter="student_class">
                                                                        <!--begin::Option-->
                                                                        <label
                                                                            class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                                                                            <input class="form-check-input" type="radio"
                                                                                name="student_class" value="all"
                                                                                checked="checked" />
                                                                            <span class="form-check-label text-gray-600">Tüm
                                                                                Sınıflar</span>
                                                                        </label>
                                                                        <!--end::Option-->
                                                                        <?php $students->getClassList(); ?>
                                                                    </div>
                                                                    <!--end::Options-->
                                                                </div>
                                                                <!--end::Input group-->
                                                                <!--begin::Actions-->
                                                                <div class="d-flex justify-content-end">
                                                                    <button type="reset"
                                                                        class="btn btn-light btn-active-light-primary me-2"
                                                                        data-kt-menu-dismiss="true"
                                                                        data-kt-customer-table-filter="reset">Temizle</button>
                                                                    <button type="submit" class="btn btn-primary"
                                                                        data-kt-menu-dismiss="true"
                                                                        data-kt-customer-table-filter="filter">Uygula</button>
                                                                </div>
                                                                <!--end::Actions-->
                                                            </div>
                                                            <!--end::Content-->
                                                        </div>
                                                        <!--end::Menu 1-->
                                                        <!--end::Filter-->

                                                    </div>
                                                    <!--end::Toolbar-->
                                                </div>

                                                <!--end::Card toolbar-->
                                            </div>
                                        <?php } ?>

                                        <!--end::Card header-->
                                        <!--begin::Card body-->
                                        <div class="card-body pt-0">
                                            <?php
                                            if ($_SESSION['role'] == 1 or $_SESSION['role'] == 3) { ?>
                                                <table class="table align-middle table-row-dashed fs-6 gy-5"
                                                    id="kt_customers_table">
                                                    <thead>
                                                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                                            <th class="min-w-125px">Öğrenci Adı</th>
                                                            <th class="min-w-100px">Sınıfı</th>
                                                            <th class="min-w-100px">Okulu</th>
                                                            <th class="min-w-80px text-end">Tamamlama</th>
                                                            <th class="min-w-80px text-end">Başarı</th>
                                                            <th class="min-w-120px text-end">İlerleme detayı</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody class="fw-semibold text-gray-600">
                                                        <?php $students->getStudentsProgressList(); ?>
                                                    </tbody>
                                                </table>
                                                <!--end::Table-->

                                            <?php } ?>
                                            <?php
                                            if ($_SESSION['role'] == 2 or $_SESSION['role'] == 5) { ?>
                                                <table class="table align-middle table-row-dashed fs-6 gy-5"
                                                    id="kt_customers_table">
                                                    <thead>
                                                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                                            <th class="min-w-125px">Öğrenci Adı</th>
                                                            <th class="min-w-100px">Sınıfı</th>
                                                            <th class="min-w-100px">Okulu</th>
                                                            <th class="min-w-80px text-end">Tamamlama</th>
                                                            <th class="min-w-80px text-end">Başarı</th>
                                                            <th class="min-w-120px text-end">İlerleme detayı</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody class="fw-semibold text-gray-600">
                                                        <?php
                                                        if ($_SESSION['role'] == 5) {
                                                            $students->getStudentsProgressListForParent($_SESSION['id'], true);
                                                        } elseif($_SESSION['role'] == 2) {
                                                            $students->getStudentsProgressListForParent($_SESSION['id'] ,false);
                                                        }

                                                        ?>
                                                    </tbody>
                                                </table>
                                            <?php } ?>
                                        </div>
                                        <!--end::Card body-->
                                    </div>
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
        <script src="assets/js/custom/apps/studentsprogress/list/export.js"></script>
        <script src="assets/js/custom/apps/studentsprogress/list/list.js"></script>
        <script src="assets/js/widgets.bundle.js"></script>
        <script src="assets/js/custom/widgets.js"></script>
        <script src="assets/js/custom/apps/chat/chat.js"></script>
        <!--end::Custom Javascript-->
        <!--end::Javascript-->
    </body>

    </html>
    <?php
} else {
    header("location: index.php");
    exit();
}