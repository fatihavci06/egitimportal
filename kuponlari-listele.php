<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 3 or $_SESSION['role'] == 4)) {
    include_once "classes/dbh.classes.php";
    include_once "classes/school.classes.php";
    include_once "classes/school-view.classes.php";
    include_once "classes/addcoupon.classes.php";
    $coupons = new AddCoupon();
    $data = $coupons->getAllCoupon();
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
                                                    <input type="text" data-kt-coupon-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Öğrenci Ara" />
                                                </div>
                                                <!--end::Search-->
                                            </div>
                                            <!--begin::Card title-->
                                            <!--begin::Card toolbar-->
                                            <div class="card-toolbar">
                                                <!--begin::Toolbar-->
                                                <div class="d-flex justify-content-end" data-kt-coupon-table-toolbar="base">
                                                    <!--begin::Filter-->
                                                    <button type="button" class="btn btn-light btn-sm-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                        <i class="ki-duotone ki-filter fs-2">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>Filter</button>
                                                    <!--begin::Menu 1-->
                                                    <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true" id="kt-toolbar-filter">
                                                        <!--begin::Header-->
                                                        <div class="px-7 py-5">
                                                            <div class="fs-4 text-gray-900 fw-bold">Filter Options</div>
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
                                                                <label class="form-label fs-5 fw-semibold mb-3">Month:</label>
                                                                <!--end::Label-->
                                                                <!--begin::Input-->
                                                                <select class="form-select form-select-solid fw-bold" data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true" data-kt-coupon-table-filter="month" data-dropdown-parent="#kt-toolbar-filter">
                                                                    <option></option>
                                                                    <option value="aug">August</option>
                                                                    <option value="sep">September</option>
                                                                    <option value="oct">October</option>
                                                                    <option value="nov">November</option>
                                                                    <option value="dec">December</option>
                                                                </select>
                                                                <!--end::Input-->
                                                            </div>
                                                            <!--end::Input group-->
                                                            <!--begin::Actions-->
                                                            <div class="d-flex justify-content-end">
                                                                <button type="reset" class="btn btn-light btn-sm btn-active-light-primary me-2" data-kt-menu-dismiss="true" data-kt-coupon-table-filter="reset">Reset</button>
                                                                <button type="submit" class="btn btn-primary btn-sm" data-kt-menu-dismiss="true" data-kt-coupon-table-filter="filter">Apply</button>
                                                            </div>
                                                            <!--end::Actions-->
                                                        </div>
                                                        <!--end::Content-->
                                                    </div>
                                                    <!--end::Menu 1-->
                                                    <!--end::Filter-->
                                                    <!--begin::Add school-->
                                                    <?php if ($_SESSION['role'] != 4) { ?><a href="http://localhost/lineup_campus/kupon" class="btn btn-primary btn-sm">Kupon Ekle</a><?php } ?>
                                                    <!--end::Add school-->
                                                </div>
                                                <!--end::Toolbar-->
                                                <!--begin::Group actions-->
                                                <div class="d-flex justify-content-end align-items-center d-none" data-kt-coupon-table-toolbar="selected">
                                                    <div class="fw-bold me-5">
                                                        <span class="me-2" data-kt-coupon-table-select="selected_count"></span>Seçildi
                                                    </div>
                                                    <button type="button" class="btn btn-danger btn-sm" data-kt-coupon-table-select="delete_selected">Seçilenleri Pasif Yap</button>
                                                </div>
                                                <!--end::Group actions-->
                                            </div>
                                            <!--end::Card toolbar-->
                                        </div>
                                        <!--end::Card header-->
                                        <!--begin::Card body-->
                                        <div class="card-body pt-0">
                                            <!--begin::Table-->
                                            <div class="table-responsive">
                                                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_coupons_table">
                                                    <thead>
                                                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                                            <th class="min-w-125px">Kupon Kodu</th>
                                                            <th class="min-w-125px">Kupon Tipi</th>
                                                            <th class="min-w-125px">Kupon Değeri</th>
                                                            <th class="min-w-125px">Kupon Durumu</th>
                                                            <th class="min-w-125px">Kupon Süresi</th>
                                                            <th class="min-w-125px">Kupon Adedi</th>
                                                            <th class="min-w-125px">Kullanılan Kupon Adedi</th>
                                                            <th class="text-end min-w-70px">İşlemler</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="fw-semibold text-gray-600">
                                                        <?php foreach ($data as $key => $value) : ?>
                                                            <tr>
                                                                <td>
                                                                    <a href="./kupon-detay?id=<?= $id ?>" class="text-gray-800 text-hover-primary mb-1"><?= $value['coupon_code'] ?></a>

                                                                </td>
                                                                <td>
                                                                    <?= $value['discount_type'] ?>
                                                                </td>
                                                                <td>
                                                                    <?= $value['discount_value'] ?>
                                                                </td>
                                                                <td>
                                                                    <?= $value['is_active'] ?>
                                                                </td>
                                                                <td><?= $value['coupon_expires'] ?></td>
                                                                <td><?= $value['coupon_quantity'] ?></td>
                                                                <td><?= $value['used_coupon_count'] ?></td>
                                                                <td class="text-end">
                                                                    <a class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">İşlemler
                                                                        <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                                                                    <!--begin::Menu-->
                                                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                                                        data-kt-menu="true">
                                                                        <!--begin::Menu item-->
                                                                        <div class="menu-item px-3">
                                                                            <a href="./kupon-detay?id=<?= $value['id'] ?>" class="menu-link px-3">Görüntüle</a>
                                                                        </div>
                                                                        <!--end::Menu item-->
                                                                        <!--begin::Menu item-->
                                                                        <div class="menu-item px-3">
                                                                            <a href="#" class="menu-link px-3 delete_coupon" data-id="<?= $value['id'] ?>" data-kt-coupon-table-filter="delete_row">Pasif Yap</a>
                                                                        </div>
                                                                        <!--end::Menu item-->
                                                                    </div>
                                                                    <!--end::Menu-->
                                                                </td>
                                                            </tr>
                                                        <?php endforeach ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!--end::Table-->
                                        </div>
                                        <!--end::Card body-->
                                    </div>
                                    <!--end::Card-->
                                    <!--begin::Modals-->
                                    <!--begin::Modal - Customers - Add-->
                                    <?php if ($_SESSION['role'] == 1) {
                                        include_once "views/student/add_student.php";
                                    } else {
                                        include_once "views/student/add_student_school.php";
                                    } ?>
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
        <!-- <script src="assets/js/custom/apps/students/list/export.js"></script>
        <script src="assets/js/custom/apps/students/list/list.js"></script>
        <script src="assets/js/custom/apps/students/add.js"></script> -->
        <script src="assets/js/widgets.bundle.js"></script>
        <script src="assets/js/custom/widgets.js"></script>
        <script src="assets/js/custom/apps/chat/chat.js"></script>
        <script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
        <script src="assets/js/custom/utilities/modals/create-account.js"></script>
        <script src="assets/js/custom/utilities/modals/create-app.js"></script>
        <script src="assets/js/custom/utilities/modals/users-search.js"></script>
        <!--end::Custom Javascript-->
        <!--end::Javascript-->

        <script>
            $('.delete_coupon').on('click', function(e) {
                e.preventDefault();

                const id = $(this).data('id');

                Swal.fire({
                    text: "Bu kuponu pasif yapmak istediğinizden emin misiniz?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Evet, pasif yap!",
                    cancelButtonText: "Hayır, iptal et",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(function(result) {
                    if (result.value) {

                        $.ajax({
                            type: "POST",
                            url: "includes/delete_coupon.inc.php",
                            data: {
                                id: id
                            },
                            dataType: "json",
                            success: function(response) {
                                if (response.status === "success") {

                                    Swal.fire({
                                        text: "Kupon pasif hale gelmiştir!",
                                        icon: "success",
                                        buttonsStyling: false,
                                        confirmButtonText: "Tamam, anladım!",
                                        customClass: {
                                            confirmButton: "btn btn-primary"
                                        }
                                    }).then(function(result) {
                                        location.reload();
                                        if (result.isConfirmed) {
                                            // Remove current row
                                            datatable.row($(parent)).remove().draw();

                                        }

                                    });
                                } else {
                                    Swal.fire({
                                        text: response.message,
                                        icon: "error",
                                        buttonsStyling: false,
                                        confirmButtonText: "Tamam, anladım!",
                                        customClass: {
                                            confirmButton: "btn btn-primary"
                                        },
                                    }).then(function(result) {
                                        if (result.isConfirmed) {

                                            // Enable submit button after loading
                                            submitButton.disabled = false;
                                        }
                                    });
                                }
                            },
                            error: function(xhr, status, error, response) {
                                Swal.fire({
                                    text: "Bir sorun oldu!",
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Tamam, anladım!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                }).then(function(result) {
                                    if (result.isConfirmed) {

                                        // Enable submit button after loading
                                        submitButton.disabled = false;
                                    }
                                });
                            },
                            error: function(xhr, status, error, response) {
                                Swal.fire({
                                    text: "Bir sorun oldu!",
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Tamam, anladım!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                }).then(function(result) {
                                    if (result.isConfirmed) {

                                        // Enable submit button after loading
                                        submitButton.disabled = false;
                                    }
                                });
                                //alert(status + "0");

                            },
                        });

                    } else if (result.dismiss === 'cancel') {
                        Swal.fire({
                            text: "Kupon pasif edilmedi.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }
                        });
                    }
                })
            })
        </script>
    </body>
    <!--end::Body-->

</html>
<?php } else {
    header("location: index");
} ?>