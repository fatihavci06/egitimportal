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
                                    <div class="card col-7">
                                        <!--begin::Card header-->

                                        <!--end::Card header-->
                                        <!--begin::Card body-->
                                        <div class="card-body  pt-0">
                                            <?php
                                            $data = new Classes();
                                            $data = $data->getSettings();
                                            ?>
                                            <h3 class="mt-4">Muhasebe Ayarları</h3>
                                            <div class="mb-3 mt-4">
                                                <label for="tax_rate" class="form-label">KDV Oranı</label>
                                                <input type="number" class="form-control" id="tax_rate" name="tax_ratio" value="<?= $data['tax_rate']; ?>" placeholder="KDV Oranı ">
                                            </div>

                                            <div class="mb-3 mt-4">
                                                <label for="discount_rate" class="form-label">Peşin Alımda İndirim Oranı</label>
                                                <input type="number" class="form-control" id="discount_rate" name="discount_ratio" value="<?= $data['discount_rate']; ?>" placeholder="KDV Oranı ">
                                            </div>
                                            <h3 class="mt-5">Bildirim Ayarları</h3>
                                            <div class="mb-3 mt-4">
                                                <label class="form-label">Aboneliği Biten Kullanıcılara Bildirim Gönderim Tipi</label>
                                                <!-- E-posta Bildirimi -->
                                                <div class="form-check">
                                                    <!-- unchecked durum için -->
                                                    <input type="hidden" name="notify_email" value="0">
                                                    <!-- checked durum için -->
                                                    <input class="form-check-input" type="checkbox" id="notify_email" name="notify_email" value="1" <?= (isset($data['notify_email']) && $data['notify_email'] == 1) ? 'checked' : '' ?>>
                                                    <label for="notify_email">E-posta</label>
                                                </div>

                                                <!-- SMS Bildirimi -->
                                                <div class="form-check mt-3">
                                                    <!-- unchecked durum için -->
                                                    <input type="hidden" name="notify_sms" value="0">
                                                    <!-- checked durum için -->
                                                    <input class="form-check-input" type="checkbox" id="notify_sms" name="notify_sms" value="1" <?= (isset($data['notify_sms']) && $data['notify_sms'] == 1) ? 'checked' : '' ?>>

                                                    <label for="notify_sms">SMS</label>
                                                </div>

                                            </div>
                                            <div class="mb-3 mt-4">
                                                <label for="sms_template" class="form-label">Bildirim Şablonu ()</label>
                                                <textarea class="form-control" id="sms_template" name="sms_template" rows="4" placeholder="Örn: Sayın {name} {surname}, aboneliğiniz bitmek üzere..."><?= $data['sms_template'] ?? '' ?></textarea>
                                                <div class="mt-2">
                                                    <p>Sürükle Bırak Parametreler : 
                                                    <span draggable="true" ondragstart="drag(event)" data-tag="{name}" class="badge bg-secondary me-2" style="font-size: 1.2rem; padding: 0.75rem 1rem;">name</span>
                                                    <span draggable="true" ondragstart="drag(event)" data-tag="{surname}" class="badge bg-secondary" style="font-size: 1.2rem; padding: 0.75rem 1rem;">surname</span>
                                                    <span draggable="true" ondragstart="drag(event)" data-tag="{subscribed_end}" class="badge bg-secondary" style="font-size: 1.2rem; padding: 0.75rem 1rem;">subscribed_end</span>
                                                </p>
                                                </div>
                                            </div>
                                            <div class="mb-3 mt-4">
                                                <label for="notification_count" class="form-label">Bildirim Sayısı</label>
                                                <input type="text" class="form-control" id="notification_count" name="notification_count" value="<?= $data['notification_count'] ?>" placeholder="Gönderim sayısı">
                                            </div>

                                            <div class="mb-3 mt-4">
                                                <label for="notification_start_day" class="form-label">Kaç gün önce bildirim gönderilmeye başlansın</label>
                                                <input type="text" class="form-control" id="notification_start_day" name="notification_start_day" value="<?= $data['notification_start_day'] ?>" placeholder="Gün sayısı">
                                            </div>
                                            <div class="mb-3 mt-4">
                                                <a href="#" id="sendTaxRateButton" class="btn btn-primary btn-sm" role="button">
                                                    Gönder
                                                </a>
                                            </div>
                                            <!--end::Table-->
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
                $('#sendTaxRateButton').on('click', function(e) {
                    e.preventDefault(); // Linkin varsayılan davranışını engelle

                    var taxRatio = $('#tax_rate').val();
                    var discountRatio = $('#discount_rate').val();
                    var notificationStartDay = $('#notification_start_day').val();
                    var notificationCount = $('#notification_count').val();
                    let notifySms = $('#notify_sms').is(':checked') ? 1 : 0;
                    let notifyEmail = $('#notify_email').is(':checked') ? 1 : 0;
                    var smsTemplate = $('#sms_template').val();


                    if (!taxRatio || taxRatio.trim() === '') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Eksik Bilgi',
                            text: 'Lütfen tüm alanları doldurunuz.'
                        });
                        return;
                    }

                    $.ajax({
                        url: 'includes/ajax.php?service=settingsUpdate',
                        type: 'POST',
                        data: {
                            taxRatio: taxRatio,
                            discountRatio: discountRatio,
                            notifySms: notifySms,
                            notifyEmail: notifyEmail,
                            notificationCount: notificationCount,
                            notificationStartDay: notificationStartDay,
                            smsTemplate:smsTemplate

                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Başarılı',
                                text: 'İşlem başarıyla kaydedildi.'
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
        <script>
            function drag(event) {
                event.dataTransfer.setData("text", event.target.getAttribute("data-tag"));
            }

            document.addEventListener('DOMContentLoaded', function() {
                const textarea = document.getElementById('sms_template');

                textarea.addEventListener('dragover', function(e) {
                    e.preventDefault();
                });

                textarea.addEventListener('drop', function(e) {
                    e.preventDefault();
                    const text = e.dataTransfer.getData("text");
                    const cursorPos = textarea.selectionStart;
                    const currentValue = textarea.value;
                    textarea.value = currentValue.substring(0, cursorPos) + text + currentValue.substring(cursorPos);
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
