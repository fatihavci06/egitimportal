<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and $_SESSION['role'] == 1 or $_SESSION['role'] == 3 or $_SESSION['role'] == 8) {
    include_once "classes/dbh.classes.php";
    include "classes/addcoupon.classes.php";
    // include "classes/school.classes.php";
    //include "classes/school-view.classes.php";
    include "classes/dateformat.classes.php";
    $dateFormat = new DateFormat();
    $id = $_GET['id'];
    $addCoupon = new AddCoupon();
    $coupon = $addCoupon->getCoupon($id);
    $coupon_with_users = $addCoupon->getCouponWithUsers($id);

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
                            <div id="kt_app_toolbar" class="app-toolbar pt-5">
                                <!--begin::Toolbar container-->
                                <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
                                    <!--begin::Toolbar wrapper-->
                                    <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                                        <!--begin::Page title-->
                                        <div class="page-title d-flex flex-column gap-1 me-3 mb-2">
                                            <!--begin::Breadcrumb-->
                                            <ul class="breadcrumb breadcrumb-separatorless fw-semibold mb-6">
                                                <!--begin::Item-->
                                                <li class="breadcrumb-item text-gray-700 fw-bold lh-1">
                                                    <a href="index.html" class="text-gray-500 text-hover-primary">
                                                        <i class="ki-duotone ki-home fs-3 text-gray-500 me-n1"></i>
                                                    </a>
                                                </li>
                                                <!--end::Item-->
                                                <!--begin::Item-->
                                                <li class="breadcrumb-item">
                                                    <i class="ki-duotone ki-right fs-4 text-gray-700 mx-n1"></i>
                                                </li>
                                                <!--end::Item-->
                                                <!--begin::Item-->
                                                <li class="breadcrumb-item text-gray-700 fw-bold lh-1">Kupon Detay</li>
                                                <!--end::Item-->
                                            </ul>
                                            <!--end::Breadcrumb-->
                                            <!--begin::Title-->
                                            <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bolder fs-1 lh-0">Kupon Detay</h1>
                                            <!--end::Title-->
                                        </div>
                                        <!--end::Page title-->
                                    </div>
                                    <!--end::Toolbar wrapper-->
                                </div>
                                <!--end::Toolbar container-->
                            </div>
                            <!--end::Toolbar-->
                            <!--begin::Content-->
                            <div id="kt_app_content" class="app-content flex-column-fluid">
                                <!--begin::Content container-->
                                <div id="kt_app_content_container" class="app-container container-fluid">
                                    <!--begin::Layout-->
                                    <div class="d-flex flex-column flex-xl-row">
                                        <!--begin::Sidebar-->
                                        <div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">
                                            <!--begin::Card-->
                                            <div class="card mb-5 mb-xl-8">
                                                <!--begin::Card body-->
                                                <div class="card-body pt-15">
                                                    <!--begin::Summary-->
                                                    <div class="d-flex flex-center flex-column mb-5">
                                                        <!--begin::Avatar-->
                                                        <!--<div class="symbol symbol-100px symbol-circle mb-7">
                                    <img src="assets/media/avatars/300-1.jpg" alt="image" />
                                </div>-->
                                                        <!--end::Avatar-->
                                                        <!--begin::Name-->
                                                        <?php foreach ($coupon as $key => $value): ?>
                                                            <div class="fs-3 text-gray-800 text-hover-primary fw-bold mb-1"> <?= $value['coupon_code'] ?> </div>
                                                            <!--begin::Info-->
                                                            <div class="d-flex flex-wrap flex-center">
                                                                <!--begin::Stats-->
                                                                <!-- <div class="border border-gray-300 border-dashed rounded py-3 px-3 mb-3">
                                                                    <div class="fs-4 fw-bold text-gray-700">
                                                                        <span class="w-75px">46</span>
                                                                        <i class="ki-duotone ki-arrow-up fs-3 text-success">
                                                                            <span class="path1"></span>
                                                                            <span class="path2"></span>
                                                                        </i>
                                                                    </div>
                                                                    <div class="fw-semibold text-muted">Kupon Adedi</div>
                                                                </div> -->
                                                                <!--end::Stats-->
                                                                <!--begin::Stats-->
                                                                <!-- <div class="border border-gray-300 border-dashed rounded py-3 px-3 mx-4 mb-3">
                                                                    <div class="fs-4 fw-bold text-gray-700">
                                                                        <span class="w-50px">130</span>
                                                                        <i class="ki-duotone ki-arrow-down fs-3 text-danger">
                                                                            <span class="path1"></span>
                                                                            <span class="path2"></span>
                                                                        </i>
                                                                    </div>
                                                                    <div class="fw-semibold text-muted">Kullanılabilir Kupon Adedi</div>
                                                                </div> -->
                                                                <!--end::Stats-->
                                                                <!--begin::Stats-->
                                                                <!-- <div class="border border-gray-300 border-dashed rounded py-3 px-3 mb-3">
                                                                    <div class="fs-4 fw-bold text-gray-700">
                                                                        <span class="w-50px">68</span>
                                                                        <i class="ki-duotone ki-arrow-up fs-3 text-success">
                                                                            <span class="path1"></span>
                                                                            <span class="path2"></span>
                                                                        </i>
                                                                    </div>
                                                                    <div class="fw-semibold text-muted">Kullanılan Kupon Adedi</div>
                                                                </div> -->
                                                                <!--end::Stats-->
                                                            </div>
                                                            <!--end::Info-->
                                                    </div>
                                                <?php endforeach ?>
                                                <!--end::Summary-->
                                                <!--begin::Details toggle-->
                                                <div class="d-flex flex-stack fs-4 py-3">
                                                    <div class="fw-bold rotate collapsible" data-bs-toggle="collapse" href="#kt_coupon_view_details" role="button" aria-expanded="false" aria-controls="kt_customer_view_details">Detaylar
                                                        <span class="ms-2 rotate-180">
                                                            <i class="ki-duotone ki-down fs-3"></i>
                                                        </span>
                                                    </div>
                                                    <span data-bs-toggle="tooltip" data-bs-trigger="hover" title="Kupon bilgilerini düzenle">
                                                        <a href="<?= './kupon-guncelle?id=' . $id ?>" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_update_coupon">Düzenle</a>
                                                    </span>
                                                </div>
                                                <!--end::Details toggle-->
                                                <div class="separator separator-dashed my-3"></div>
                                                <!--begin::Details content-->
                                                <div id="kt_customer_view_details" class="collapse show">
                                                    <div class="py-5 fs-6">
                                                        <!--begin::Badge-->
                                                        <!--<div class="badge badge-light-info d-inline">Premium user</div>-->
                                                        <!--end::Badge-->
                                                        <!--begin::Details item-->
                                                        <div class="fw-bold mt-5">Kupon Adedi</div>
                                                        <div class="text-gray-600">
                                                            <?= $value['coupon_quantity'] ?>
                                                        </div>
                                                        <!--end::Details item-->
                                                        <!--begin::Details item-->
                                                        <div class="fw-bold mt-5">Kullanılan Kupon Adedi</div>
                                                        <div class="text-gray-600"><?= $value['used_coupon_count'] ?></div>
                                                        <!--end::Details item-->
                                                        <!--begin::Details item-->
                                                        <div class="fw-bold mt-5">Kullanılabilir Kupon Adedi</div>
                                                        <div class="text-gray-600"><?= $value['coupon_quantity'] - $value['used_coupon_count'] ?></div>
                                                        <!--end::Details item-->
                                                        <!--begin::Details item-->
                                                        <div class="fw-bold mt-5">Kupon Geçerlilik Tarihi</div>
                                                        <div class="text-gray-600">
                                                            <?=
                                                            $dateFormat->changeDate($value['coupon_expires']) ?>
                                                        </div>
                                                        <!--end::Details item-->
                                                    </div>
                                                </div>
                                                <!--end::Details content-->
                                                </div>
                                                <!--end::Card body-->
                                            </div>
                                            <!--end::Card-->
                                        </div>
                                        <!--end::Sidebar-->
                                        <!--begin::Content-->
                                        <div class="flex-lg-row-fluid ms-lg-15">
                                            <!--begin:::Tabs-->
                                            <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8">
                                                <!--begin:::Tab item-->
                                                <!--end:::Tab item-->
                                                <!--begin:::Tab item-->
                                                <!--<li class="nav-item">
													<a class="nav-link text-active-primary pb-4" data-kt-countup-tabs="true" data-bs-toggle="tab" href="#kt_customer_view_overview_statements">Statements</a>
												</li>-->
                                                <!--end:::Tab item-->
                                                <!--begin:::Tab item-->
                                                <!--  -->
                                                <!--end:::Tab item-->
                                            </ul>
                                            <!--end:::Tabs-->
                                            <!--begin:::Tab content-->
                                            <div class="tab-content" id="myTabContent">
                                                <!--begin:::Tab pane-->
                                                <div class="tab-pane fade show active" id="kt_customer_view_overview_tab" role="tabpanel">
                                                    <!--begin::Card-->
                                                    <div class="card pt-4 mb-6 mb-xl-9">
                                                        <!--begin::Card header-->
                                                        <div class="card-header border-0">
                                                            <!--begin::Card title-->
                                                            <div class="card-title">
                                                                <h2>Kupon Kullanan Öğrenci Listesi</h2>
                                                            </div>
                                                            <!--end::Card title-->
                                                        </div>
                                                        <!--end::Card header-->
                                                        <!--begin::Card body-->
                                                        <?php if(!empty($coupon_with_users)): ?>
                                                            <div class="card-body pt-0 pb-5">
                                                                <!--begin::Table-->
                                                                <div class="table-responsive">
                                                                    <table class="table align-middle table-row-dashed gy-5" id="kt_table_customers_payment">
                                                                        <thead class="border-bottom border-gray-200 fs-7 fw-bold">
                                                                            <tr class="text-start text-muted text-uppercase gs-0">
                                                                                <th class="min-w-100px">Öğrenci Adı</th>
                                                                                <th>Öğrenci E-posta Adresi</th>
                                                                                <th>Öğrenci Telefon Numarası</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody class="fs-6 fw-semibold text-gray-600">
                                                                            <?php foreach ($coupon_with_users as $key => $value): ?>
                                                                                
                                                                                <tr>
                                                                                    <td>
                                                                                        <a href="<?= './ogrenci-detay/' . $value['user_id'] ?>" class="text-gray-600 text-hover-primary mb-1"><?= $value['name'] . ' ' . $value['surname']  ?></a>
                                                                                    </td>
                                                                                    <td>
                                                                                        <?= $value['email'] ?>
                                                                                    </td>
                                                                                    <td><?= $value['telephone'] ?></td>
                                                                                </tr>
                                                                            <?php endforeach; ?>

                                                                        </tbody>
                                                                        <!--end::Table body-->
                                                                    </table>
                                                                </div>
                                                                <!--end::Table-->

                                                            </div>
                                                        <?php else: ?>
                                                            <div class="card-body pt-0 pb-5">
                                                                <td colspan="3" class="text-center text-muted">Bu kuponu kullanan öğrenci bulunamadı.</td>
                                                            </div>
                                                        <?php endif; ?>
                                                        <!--end::Card body-->
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
        <!-- <script src="assets/js/custom/apps/customers/view/add-payment.js"></script>
        <script src="assets/js/custom/apps/customers/view/adjust-balance.js"></script>
        <script src="assets/js/custom/apps/customers/view/invoices.js"></script>
        <script src="assets/js/custom/apps/customers/view/payment-method.js"></script> -->
        <script src="assets/js/custom/apps/coupon/payment-table.js"></script><!-- 
        <script src="assets/js/custom/apps/customers/view/statement.js"></script> -->
        <!-- <script src="assets/js/custom/apps/coupon/update.js"></script> -->
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

    </body>
    <!--end::Body-->

</html>
<?php } else {
    header("location: ../index");
}
