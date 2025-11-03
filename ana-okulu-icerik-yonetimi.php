<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 10001)) {
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";

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
                                                    <input type="text" data-kt-customer-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="İçerik Ara" />
                                                </div>
                                                <!--end::Search-->
                                            </div>
                                            <!--begin::Card title-->
                                            <!--begin::Card toolbar-->
                                            <div class="card-toolbar">
                                                <!--begin::Toolbar-->
                                                <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                                                    <!--begin::Add school-->
                                                    <a href="ana-okulu-icerik-ekle" class="btn btn-primary btn-sm">İçerik Ekle</a>
                                                    <!--end::Add school-->

                                                </div>
                                                <!--end::Toolbar-->
                                                <!--begin::week actions-->
                                                <div class="d-flex justify-content-end align-items-center d-none" data-kt-customer-table-toolbar="selected">
                                                    <div class="fw-bold me-5">
                                                        <span class="me-2" data-kt-customer-table-select="selected_count"></span>Seçildi
                                                    </div>
                                                    <button type="button" class="btn btn-danger btn-sm" data-kt-customer-table-select="delete_selected">Seçilenleri Pasif Yap</button>
                                                </div>
                                                <!--end::week actions-->
                                            </div>
                                            <!--end::Card toolbar-->
                                        </div>
                                        <!--end::Card header-->
                                        <!--begin::Card body-->
                                        <div class="card-body pt-0">
                                            <!-- Button trigger modal -->


                                            <!-- Modal -->
                                            <!-- Button trigger modal (id değeri burada veriliyor) -->


                                            <!-- Modal -->




                                            <!--begin::Table-->
                                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="anaOkuluIcerikleri">
                                                <thead>
                                                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                                        <th class="min-w-125px">Yaş Grubu</th>
                                                        <th class="min-w-125px">Ders</th>
                                                        <th class="min-w-125px">Konu</th>
                                                        <th class="min-w-125px">Durum</th>
                                                        <th class="text-end min-w-70px">İşlemler</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="fw-semibold text-gray-600">
                                                    <?php
                                                    $class = new Classes();
                                                    if(@$_GET['week_id']){
                                                       $weekList = $class->getMainSchoolContentList($_GET['week_id']);
                                                    }else if(@$_GET['category_id']){
                                                        $weekList = $class->getMainSchoolContentList('',@$_GET['category_id']);
                                                    }
                                                    else{
                                                        $weekList = $class->getMainSchoolContentList();
                                                    }
                                            
                                                    
                                                    foreach ($weekList as $key => $value): ?>
                                                        <tr>
                                                            <td>
                                                                <?= htmlspecialchars($value['class_name']) ?>
                                                            </td>
                                                            <td>
                                                               <?= htmlspecialchars($value['lesson_name']) ?>
                                                            </td>
                                                            <td>
                                                                <a href="ana-okulu-icerik-detay.php?id=<?= $value['id'] ?>" class="text-gray-800 text-hover-primary mb-1">
                                                                    <?= htmlspecialchars($value['subject']) ?>
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <?php if ((int)$value['status'] === 0): ?>
                                                                    <span class="badge bg-danger">Pasif</span>
                                                                <?php else: ?>
                                                                    <span class="badge bg-success">Aktif</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td class="text-end">
                                                                <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                                                    data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                                    İşlemler
                                                                    <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                                                </a>
                                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                                                    data-kt-menu="true">
                                                                    
                                                                    <div class="menu-item px-3">
                                                                        <a class="menu-link px-3" href="ana-okulu-icerik-guncelle?id=<?= htmlspecialchars($value['id']) ?>">
                                                                            Güncelle
                                                                        </a>
                                                                    </div>
                                                                    <div class="menu-item px-3">
                                                                        <a href="javascript:void(0);"
                                                                            class="menu-link px-3"
                                                                            data-kt-customer-table-filter="delete_row"
                                                                            onclick="handleDelete({ id: '<?= htmlspecialchars($value['id']) ?>', url: 'includes/ajax.php?service=changeStatusMainSchoolContent' })">
                                                                            <?= ((int)$value['status'] === 1) ? 'Pasif Yap' : 'Aktif Yap'; ?>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>

                                            <!--end::Table-->
                                        </div>
                                        <!--end::Card body-->
                                    </div>
                                    <!--end::Card-->
                                    <!--begin::Modals-->
                                    <!--begin::Modal - Customers - Add-->
                                    <?php include_once "views/classes/add_important_week-view.classes.php" ?>

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
                                                        <!--begin::Input week-->
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
                                                        <!--end::Input week-->
                                                        <!--begin::Input week-->
                                                        <div class="fv-row mb-10">
                                                            <!--begin::Label-->
                                                            <label class="fs-5 fw-semibold form-label mb-5">Select Date Range:</label>
                                                            <!--end::Label-->
                                                            <!--begin::Input-->
                                                            <input class="form-control form-control-solid" placeholder="Pick a date" name="date" />
                                                            <!--end::Input-->
                                                        </div>
                                                        <!--end::Input week-->
                                                        <!--begin::Row-->
                                                        <div class="row fv-row mb-15">
                                                            <!--begin::Label-->
                                                            <label class="fs-5 fw-semibold form-label mb-5">Payment Type:</label>
                                                            <!--end::Label-->
                                                            <!--begin::Radio week-->
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
                                                            <!--end::Input week-->
                                                        </div>
                                                        <!-- Button trigger modal -->

                                                        <!--end::Row-->
                                                        <!--begin::Actions-->
                                                        <div class="text-center">
                                                            <button type="reset" id="kt_customers_export_cancel" class="btn btn-light btn-sm me-3">Discard</button>
                                                            <button type="submit" id="kt_customers_export_submit" class="btn btn-primary btn-sm">
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
        <script src="assets/js/custom/apps/class/list/export.js"></script>
        <!-- <script src="assets/js/custom/apps/class/list/list.js"></script> -->
        <script src="assets/js/custom/apps/class/add_week.js"></script>
        <script src="assets/js/widgets.bundle.js"></script>
        <script src="assets/js/custom/widgets.js"></script>
        <script src="assets/js/custom/apps/chat/chat.js"></script>
        <script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
        <script src="assets/js/custom/utilities/modals/create-account.js"></script>
        <script src="assets/js/custom/utilities/modals/create-app.js"></script>
        <script src="assets/js/custom/utilities/modals/users-search.js"></script>
        <script src="assets/js/fatih.js"></script>
        <script>
            $(document).ready(function() {
                $('#anaOkuluIcerikleri').DataTable({
                    // DataTables'ın varsayılan ayarlarını burada özelleştirebilirsiniz
                   
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
