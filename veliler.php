<!DOCTYPE html>
<html lang="tr">
<?php
error_reporting(E_ALL); ini_set('display_errors', 1);
	session_start();
    define('GUARD', true);
    if (isset($_SESSION['role']) AND ($_SESSION['role'] == 1 OR $_SESSION['role'] == 3 OR $_SESSION['role'] == 4)) {
    include_once "classes/dbh.classes.php";
    include_once "classes/parent.classes.php";
    include_once "classes/parent-view.classes.php";
    $parents = new ShowParent();
    include_once "views/pages-head.php";
?>
<!--end::Head-->
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
                        <!--begin::Content-->
                        <div id="kt_app_content" class="app-content flex-column-fluid">
                            <!--begin::Content container-->
                            <div id="kt_app_content_container" class="app-container container-fluid">
                                <!--begin::Card-->
                                <div class="card">
                                    <!--begin::Card header-->
                                    <div class="card-header border-0 pt-6">
                                        <!--begin::Card title-->
                                        <div class="card-title">
                                            <!--begin::Search-->
                                            <div class="d-flex align-items-center position-relative my-1">
                                                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                                <input type="text" data-kt-customer-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Arama" />
                                            </div>
                                            <!--end::Search-->
                                        </div>
                                        <!--begin::Card title-->
                                        <!--begin::Card toolbar-->
                                        <div class="card-toolbar">
                                            <!--begin::Toolbar-->
                                            <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                                                
                                            </div>
                                            <!--end::Toolbar-->
                                            <!--begin::Group actions-->
                                            <div class="d-flex justify-content-end align-items-center d-none" data-kt-customer-table-toolbar="selected">
                                                <div class="fw-bold me-5">
                                                    <span class="me-2" data-kt-customer-table-select="selected_count"></span>Seçildi
                                                </div>
                                                <button type="button" class="btn btn-danger" data-kt-customer-table-select="delete_selected">Seçilenleri Pasif Yap</button>
                                            </div>
                                            <!--end::Group actions-->
                                        </div>
                                        <!--end::Card toolbar-->
                                    </div>
                                    <!--end::Card header-->
                                    <!--begin::Card body-->
                                    <div class="card-body pt-0">
                                        <!--begin::Table-->
                                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table">
                                            <thead>
                                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                                    <th class="min-w-125px">Veli Adı</th>
                                                    <th class="min-w-125px">E-posta Adresi</th>
                                                    <th class="min-w-125px">Telefon</th>
                                                    <th class="min-w-125px">Çocuğu</th>
                                                    <th class="text-end min-w-125px">Paket Bitiş Tarihi</th>
                                                </tr>
                                            </thead>
                                            <tbody class="fw-semibold text-gray-600">
                                                <?php $parents->getParentList(); ?>
                                            </tbody>
                                        </table>
                                        <!--end::Table-->
                                    </div>
                                    <!--end::Card body-->
                                </div>
                                <!--end::Card-->
                                <!--begin::Modals-->
                                <!--begin::Modal - Customers - Add-->
                                    <?php if ($_SESSION['role'] == 1){
                                            include_once "views/parent/add_parent.php";
                                        }else{
                                            include_once "views/student/add_student_school.php";
                                        } ?>
                                <!--end::Modal - Customers - Add-->
                                <!--begin::Modal - Adjust Balance-->
                                <div class="modal fade" id="kt_customers_export_modal" tabindex="-1" aria-hidden="true">
                                    <!--begin::Modal dialog-->
                                    <div class="modal-dialog modal-dialog-centered mw-650px">
                                        <!--begin::Modal content-->
                                        <div class="modal-content">
                                            <!--begin::Modal header-->
                                            <div class="modal-header">
                                                <!--begin::Modal title-->
                                                <h2 class="fw-bold">Export Customers</h2>
                                                <!--end::Modal title-->
                                                <!--begin::Close-->
                                                <div id="kt_customers_export_close" class="btn btn-icon btn-sm btn-active-icon-primary">
                                                    <i class="ki-duotone ki-cross fs-1">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </div>
                                                <!--end::Close-->
                                            </div>
                                            <!--end::Modal header-->
                                            <!--begin::Modal body-->
                                            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                                                <!--begin::Form-->
                                                <form id="kt_customers_export_form" class="form" action="#">
                                                    <!--begin::Input group-->
                                                    <div class="fv-row mb-10">
                                                        <!--begin::Label-->
                                                        <label class="fs-5 fw-semibold form-label mb-5">Select Export Format:</label>
                                                        <!--end::Label-->
                                                        <!--begin::Input-->
                                                        <select data-control="select2" data-placeholder="Select a format" data-hide-search="true" name="format" class="form-select form-select-solid">
                                                            <option value="excell">Excel</option>
                                                            <option value="pdf">PDF</option>
                                                            <option value="cvs">CVS</option>
                                                            <option value="zip">ZIP</option>
                                                        </select>
                                                        <!--end::Input-->
                                                    </div>
                                                    <!--end::Input group-->
                                                    <!--begin::Input group-->
                                                    <div class="fv-row mb-10">
                                                        <!--begin::Label-->
                                                        <label class="fs-5 fw-semibold form-label mb-5">Select Date Range:</label>
                                                        <!--end::Label-->
                                                        <!--begin::Input-->
                                                        <input class="form-control form-control-solid" placeholder="Pick a date" name="date" />
                                                        <!--end::Input-->
                                                    </div>
                                                    <!--end::Input group-->
                                                    <!--begin::Row-->
                                                    <div class="row fv-row mb-15">
                                                        <!--begin::Label-->
                                                        <label class="fs-5 fw-semibold form-label mb-5">Payment Type:</label>
                                                        <!--end::Label-->
                                                        <!--begin::Radio group-->
                                                        <div class="d-flex flex-column">
                                                            <!--begin::Radio button-->
                                                            <label class="form-check form-check-custom form-check-sm form-check-solid mb-3">
                                                                <input class="form-check-input" type="checkbox" value="1" checked="checked" name="payment_type" />
                                                                <span class="form-check-label text-gray-600 fw-semibold">All</span>
                                                            </label>
                                                            <!--end::Radio button-->
                                                            <!--begin::Radio button-->
                                                            <label class="form-check form-check-custom form-check-sm form-check-solid mb-3">
                                                                <input class="form-check-input" type="checkbox" value="2" checked="checked" name="payment_type" />
                                                                <span class="form-check-label text-gray-600 fw-semibold">Visa</span>
                                                            </label>
                                                            <!--end::Radio button-->
                                                            <!--begin::Radio button-->
                                                            <label class="form-check form-check-custom form-check-sm form-check-solid mb-3">
                                                                <input class="form-check-input" type="checkbox" value="3" name="payment_type" />
                                                                <span class="form-check-label text-gray-600 fw-semibold">Mastercard</span>
                                                            </label>
                                                            <!--end::Radio button-->
                                                            <!--begin::Radio button-->
                                                            <label class="form-check form-check-custom form-check-sm form-check-solid">
                                                                <input class="form-check-input" type="checkbox" value="4" name="payment_type" />
                                                                <span class="form-check-label text-gray-600 fw-semibold">American Express</span>
                                                            </label>
                                                            <!--end::Radio button-->
                                                        </div>
                                                        <!--end::Input group-->
                                                    </div>
                                                    <!--end::Row-->
                                                    <!--begin::Actions-->
                                                    <div class="text-center">
                                                        <button type="reset" id="kt_customers_export_cancel" class="btn btn-light me-3">Discard</button>
                                                        <button type="submit" id="kt_customers_export_submit" class="btn btn-primary">
                                                            <span class="indicator-label">Submit</span>
                                                            <span class="indicator-progress">Please wait...
                                                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                                        </button>
                                                    </div>
                                                    <!--end::Actions-->
                                                </form>
                                                <!--end::Form-->
                                            </div>
                                            <!--end::Modal body-->
                                        </div>
                                        <!--end::Modal content-->
                                    </div>
                                    <!--end::Modal dialog-->
                                </div>
                                <!--end::Modal - New Card-->
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
    <script src="assets/js/custom/apps/parents/list/export.js"></script>
    <script src="assets/js/custom/apps/parents/list/list.js"></script>
    <script src="assets/js/custom/apps/parents/add.js"></script>
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
<?php }else{
    header("location: index");
}