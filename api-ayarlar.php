<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 10001)) {
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";
    include_once "classes/sms.classes.php";
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
                                    <div class="card col-12">
                                        <!--begin::Card header-->

                                        <!--end::Card header-->
                                        <!--begin::Card body-->
                                        <div class="card-body pt-0">
                                            <div class="row g-5 g-xl-8">
                                                <div class="col-12 col-md-6">
                                                    <div class="card card-custom h-md-100 shadow-sm border border-info">
                                                        <div class="card-header border-0">
                                                            <div class="card-title">
                                                                <h3 class="fw-bold m-0"><i class="fas fa-sms fs-2 me-2 text-primary"></i> SMS API Ayarları</h3>
                                                            </div>
                                                        </div>
                                                        <div class="card-body pt-0">
                                                            <?php
                                                            $data = new Classes();
                                                            $data = $data->getSMSApiSettings();
                                                            ?>
                                                            <div class="mb-5">
                                                                <label for="username" class="form-label">Kullanıcı Adı</label>
                                                                <input type="text" class="form-control" id="username" name="username"
                                                                    value="<?= htmlspecialchars($data['username']); ?>" placeholder="Kullanıcı Adı">
                                                            </div>
                                                            <div class="mb-5">
                                                                <label for="password" class="form-label">Şifre</label>
                                                                <input type="password" class="form-control" id="password" name="password"
                                                                    value="<?= htmlspecialchars($data['password']); ?>" placeholder="Şifre">
                                                            </div>
                                                            <div class="mb-5">
                                                                <label for="msgheader" class="form-label">SMS Başlığı</label>
                                                                <input type="text" class="form-control" id="msgheader" name="msgheader"
                                                                    value="<?= htmlspecialchars($data['msgheader']); ?>" placeholder="SMS Başlığı">
                                                            </div>
                                                            <p class="mt-2 mb-2">SMS Şablonu Ayarları için <a href="ayarlar" class="text-primary">tıklayınız</a>.</p>
                                                            <div class="d-grid">
                                                                <a href="#" id="sendSmsSettings" class="btn btn-primary btn-hover-scale" role="button">
                                                                    <i class="fas fa-save me-2"></i> Kaydet
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="card card-custom h-md-100 shadow-sm border border-info">
                                                        <div class="card-header border-0">
                                                            <div class="card-title">
                                                                <h3 class="fw-bold m-0"><i class="fas fa-credit-card fs-2 me-2 text-info"></i> Ödeme API Bilgileri</h3>
                                                            </div>
                                                        </div>
                                                        <div class="card-body pt-0">
                                                            <?php
                                                            $merchantId = "77012194";
                                                            $terminalId = "84012196";
                                                            $supportEmail = "teknikdestek@tami.com.tr";
                                                            $supportPhone = "444 0 777";
                                                            ?>
                                                            <div class="p-4 bg-light rounded-3 mb-4">
                                                                <div class="d-flex align-items-center mb-3">
                                                                    <i class="fas fa-store fs-4 text-secondary me-3"></i>
                                                                    <div class="d-flex flex-column">
                                                                        <span class="fw-bold text-muted">Merchant ID:</span>
                                                                        <span class="text-dark fs-5 fw-bolder"><?= htmlspecialchars($merchantId); ?></span>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex align-items-center mb-3">
                                                                    <i class="fas fa-terminal fs-4 text-secondary me-3"></i>
                                                                    <div class="d-flex flex-column">
                                                                        <span class="fw-bold text-muted">Terminal ID:</span>
                                                                        <span class="text-dark fs-5 fw-bolder"><?= htmlspecialchars($terminalId); ?></span>
                                                                    </div>
                                                                </div>
                                                                <hr class="border-dashed my-3">
                                                                <div class="d-flex align-items-center mb-2">
                                                                    <i class="fas fa-envelope fs-4 text-danger me-3"></i>
                                                                    <div class="d-flex flex-column">
                                                                        <span class="fw-bold text-muted">Acil Destek (E-posta):</span>
                                                                        <a href="mailto:<?= htmlspecialchars($supportEmail); ?>" class="text-danger fs-5 text-hover-underline">
                                                                            <?= htmlspecialchars($supportEmail); ?>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex align-items-center">
                                                                    <i class="fas fa-phone fs-4 text-danger me-3"></i>
                                                                    <div class="d-flex flex-column">
                                                                        <span class="fw-bold text-muted">Acil Destek (Telefon):</span>
                                                                        <a href="tel:<?= str_replace(' ', '', htmlspecialchars($supportPhone)); ?>" class="text-danger fs-5 text-hover-underline">
                                                                            <?= htmlspecialchars($supportPhone); ?>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <!--end::Card body-->
                                    </div>
                                    <!--end::Card-->
                                    <!--begin::Modals-->
                                    <!--begin::Modal - Customers - Add-->

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

        <script>
            $(document).ready(function() {
                $('#sendSmsSettings').on('click', function(e) {
                    e.preventDefault(); // Linkin varsayılan davranışını engelle

                    var username = $('#username').val();
                    var password = $('#password').val();
                    var msgheader = $('#msgheader').val();

                    if (!username || !password || !msgheader) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Eksik Bilgi',
                            text: 'Lütfen tüm alanları doldurunuz.'
                        });
                        return;
                    }

                    $.ajax({
                        url: 'includes/ajax.php?service=sms-settings',
                        type: 'POST',
                        data: {
                            username: username,
                            password: password,
                            msgheader: msgheader
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Başarılı',
                                text: 'Ayarlar başarıyla kaydedildi.'
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            let errorMessage = 'Bilinmeyen hata oluştu';

                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            } else if (xhr.responseText) {
                                try {
                                    let json = JSON.parse(xhr.responseText);
                                    if (json.message) errorMessage = json.message;
                                } catch (e) {}
                            }

                            console.log(errorMessage);

                            Swal.fire({
                                icon: 'error',
                                title: 'Hata',
                                text: errorMessage
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
