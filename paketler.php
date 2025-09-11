<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1)) {
    include_once "classes/dbh.classes.php";
    include_once "classes/classes.classes.php";
    include_once "classes/packages.classes.php";
    include_once "classes/packages-view.classes.php";
    include_once "classes/student.classes.php";
    include_once "classes/student-view.classes.php";

    $package = new ShowPackagesForAdmin();
    include_once "views/pages-head.php";

    $students = new ShowStudent();

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
                                            <div class="card-title">
                                                <!--begin::Search-->
                                                <div class="d-flex align-items-center position-relative my-1">
                                                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                    <input type="text" data-kt-customer-table-filter="search"
                                                        class="form-control form-control-solid w-250px ps-12"
                                                        placeholder="Paket Ara" />
                                                </div>
                                                <!--end::Search-->
                                            </div>
                                            <!--begin::Card title-->
                                            <!--begin::Card toolbar-->
                                            <div class="card-toolbar">
                                                <!--begin::Toolbar-->
                                                <div class="d-flex justify-content-end"
                                                    data-kt-customer-table-toolbar="base">
                                                    <button type="button" class="btn btn-primary btn-sm me-3"
                                                        data-bs-toggle="modal" data-bs-target="#packageCreate">
                                                        Paket Ekle
                                                    </button>
                                                    <button type="button" class="btn btn-light-primary btn-sm"
                                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                        <i class="ki-duotone ki-filter fs-2">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>Filtre</button>
                                                    <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px"
                                                        data-kt-menu="true" id="kt-toolbar-filter">
                                                        <div class="px-7 py-5">
                                                            <div class="fs-4 text-gray-900 fw-bold">Filtreleme</div>
                                                        </div>

                                                        <div class="separator border-gray-200"></div>

                                                        <div class="px-7 py-5">
                                                            <div class="mb-10">
                                                                <label
                                                                    class="form-label fs-5 fw-semibold mb-3">Sınıf:</label>
                                                                <div class="d-flex flex-column flex-wrap fw-semibold"
                                                                    data-kt-customer-table-filter="student_class">
                                                                    <label
                                                                        class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                                                                        <input class="form-check-input" type="radio"
                                                                            name="student_class" value="all"
                                                                            checked="checked" />
                                                                        <span class="form-check-label text-gray-600">Tüm
                                                                            Sınıflar</span>
                                                                    </label>
                                                                    <?php $students->getClassList(); ?>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex justify-content-end">
                                                                <button type="reset"
                                                                    class="btn btn-light btn-active-light-primary me-2"
                                                                    data-kt-menu-dismiss="true"
                                                                    data-kt-customer-table-filter="reset">Temizle</button>
                                                                <button type="submit" class="btn btn-primary"
                                                                    data-kt-menu-dismiss="true"
                                                                    data-kt-customer-table-filter="filter">Uygula</button>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <!-- Modal -->
                                                    <div class="modal fade" id="packageCreate" tabindex="-1"
                                                        aria-labelledby="packageCreateLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="packageCreateLabel">Paket
                                                                        Ekle</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>

                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label for="packageName" class="form-label">Paket Adı <span style="color:red">*</span></label>
                                                                        <input type="text" class="form-control" id="packageName" name="packageName"
                                                                            placeholder="Paket adını giriniz">
                                                                    </div>

                                                                    <!-- Select Box -->
                                                                    <?php
                                                                    $classes = new Classes();
                                                                    $classList = $classes->getClasses();
                                                                    ?>
                                                                    <div class="mb-3">
                                                                        <label for="packageType" class="form-label">Sınıf <span style="color:red">*</span></label>
                                                                        <select class="form-select" id="class_id" name="packageType">
                                                                            <option value="" selected disabled>Seçiniz</option>
                                                                            <?php foreach ($classList as $c) { ?>
                                                                                <option value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <label for="subscription_period" class="form-label">Paket Süresi (Ay) <span style="color:red">*</span></label>
                                                                        <input type="number" class="form-control" id="subscription_period" name="subscription_period"
                                                                            placeholder="Paket süresini ay olarak giriniz.">
                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <label for="bank_transfer_fee" class="form-label">Havale/EFT ile Paket Ücreti <span style="color:red">*</span></label>
                                                                        <input type="number" step="0.01" class="form-control" id="bank_transfer_fee" name="bank_transfer_fee"
                                                                            placeholder="Havale/EFT ücreti giriniz.">
                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <label for="credit_card_fee" class="form-label">Kredi Kartı ile Paket Ücreti <span style="color:red">*</span></label>
                                                                        <input type="number" step="0.01" class="form-control" id="credit_card_fee" name="credit_card_fee"
                                                                            placeholder="Kredi kartı ücreti giriniz.">
                                                                    </div>
                                                                </div>



                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                                                                    <button type="button" id="savePackageBtn" class="btn btn-primary">Kaydet</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!--end::Add school-->

                                                </div>
                                                <!--end::Toolbar-->
                                                <!--begin::week actions-->
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



                                            <?php
                                            $taxRate = $package->taxRate();
                                            // echo $taxRate['tax_rate'];
                                            ?>
                                            <!--begin::Table-->
                                            <table class="table align-middle table-row-dashed fs-6 gy-5"
                                                id="kt_customers_table">
                                                <thead>
                                                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                                        <th class="min-w-125px">Paket Adı </th>
                                                        <th class="min-w-125px">Aylık Ücret</th>
                                                        <th class="min-w-125px">Kaç Aylık</th>
                                                        <th class="min-w-125px">Peşin Alımda İndirim Yüzdesi</th>
                                                        <th class="min-w-125px">Hangi Sınıf</th>
                                                        <th class="text-end min-w-125px">İşlem</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="fw-semibold text-gray-600">

                                                    <?php

                                                    $package->showAllPackages(); ?>
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
        <script src="assets/js/widgets.bundle.js"></script>
        <script src="assets/js/custom/widgets.js"></script>
        <script src="assets/js/custom/apps/chat/chat.js"></script>

        <script src="assets/js/custom/apps/packages/list/filter.js"></script>
        <!-- <script src="assets/js/custom/apps/class/list/list.js"></script> -->

        <script>
            $(document).ready(function() {
                $('#savePackageBtn').on('click', function(e) {
                    e.preventDefault();

                    // Form alanlarını al
                    let packageName = $('#packageName').val().trim();
                    let classId = $('#class_id').val();
                    let subscription_period = $('#subscription_period').val();
                    let bank_transfer_fee = $('#bank_transfer_fee').val();
                    let credit_card_fee = $('#credit_card_fee').val();

                    // Alan kontrolü
                    if (!packageName || !classId || !subscription_period || !bank_transfer_fee || !credit_card_fee) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Eksik alan',
                            text: 'Lütfen tüm alanları doldurunuz!'
                        });
                        return;
                    }

                    let data = {
                        packageName: packageName,
                        class_id: classId,
                        subscription_period: subscription_period,
                        bank_transfer_fee: bank_transfer_fee,
                        credit_card_fee: credit_card_fee,
                    };

                    $.ajax({
                        url: 'includes/ajax.php?service=createPackage', // backend endpoint
                        type: 'POST',
                        data: data,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Laravel için CSRF
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Başarılı',
                                    text: response.message
                                });
                                $('#packageModal').modal('hide');
                                $('#packageForm')[0].reset();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Hata',
                                    text: response.message
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            // Backend'den gelen hata mesajı
                            let errMsg = 'Bir hata oluştu!';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errMsg = xhr.responseJSON.message;
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Hata',
                                text: errMsg
                            });
                        }
                    });
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
