<?php
session_start();
define('GUARD', true);

// Bu sayfa sadece adminler tarafından erişilebilir.
if (isset($_SESSION['role']) && $_SESSION['role'] == 1 OR $_SESSION['role'] == 9 OR $_SESSION['role'] == 10) {
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php"; // Bu dosya içinde Classes sınıfınız olmalı

    include_once "views/pages-head.php";

    $class = new Classes();

    // Tüm kullanıcıları çekiyoruz (toplantı düzenleyici ve katılımcı seçimi için)
    // Lütfen classes/classes.classes.php içinde getAllUsers metodunu ekleyin.
    $allUsers = $class->getCoachStudents($_SESSION['id']); // Yeni metodumuzu çağırıyoruz
    
?>
    <!DOCTYPE html>
    <html lang="tr">

    <head>
        <?php include_once "views/pages-head.php"; ?>

        <title>Yeni Toplantı Oluştur</title>

        <style>
            /* Metronic Theme ile uyumlu temel stil ayarlamaları */
            body {
                font-family: "Inter", sans-serif;
            }

            .form-control,
            .form-select {
                border-radius: 0.5rem !important;
                padding: 0.75rem 1rem !important;
                border: 1px solid #e2e8f0;
            }

            .btn-primary {
                background-color: #3667c2 !important;
                color: #fff !important;
                border-radius: 0.5rem !important;
                padding: 0.75rem 1.5rem !important;
                transition: background-color 0.3s ease;
            }

            .btn-primary:hover {
                background-color: #2e59a7 !important;
            }

            .card {
                border-radius: 1rem !important;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            .card-header {
                border-bottom: 1px solid #e2e8f0;
            }

            .input-group-text {
                background-color: #edf2f7;
                border: 1px solid #e2e8f0;
                border-radius: 0.5rem 0 0 0.5rem !important;
            }

            /* Yükleme animasyonu için basit CSS */
            .loading-spinner {
                border: 4px solid rgba(0, 0, 0, 0.1);
                border-left-color: #3667c2;
                border-radius: 50%;
                width: 24px;
                height: 24px;
                animation: spin 1s linear infinite;
                display: none;
            }

            @keyframes spin {
                0% {
                    transform: rotate(0deg);
                }

                100% {
                    transform: rotate(360deg);
                }
            }
        </style>
    </head>

    <body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" data-kt-app-aside-enabled="true" data-kt-app-aside-fixed="true" data-kt-app-aside-push-toolbar="true" data-kt-app-aside-push-footer="true" class="app-default">
        <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
            <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
                <?php include_once "views/header.php"; ?>
                <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                    <?php include_once "views/sidebar.php"; ?>
                    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                        <div class="d-flex flex-column flex-column-fluid">
                            <?php include_once "views/toolbar.php"; ?>
                            <div id="kt_app_content" class="app-content flex-column-fluid">
                                <div id="kt_app_content_container" class="app-container container-fluid">
                                    <div class="card">
                                        <div class="card-header border-0 pt-6">
                                            <div class="card-title">
                                                <h3 class="fw-bold m-0">Yeni Toplantı Oluştur (Koç öğretmen öğrencilerine oluşturur.Öğrenci listesinde sadece kendi koçluk yaptığı öğrenciler bulunur.)</h3>
                                            </div>
                                            <div class="card-toolbar">
                                                </div>
                                        </div>
                                        <div class="card-body pt-0">
                                            <form id="createMeetingForm" class="form">
                                                

                                                <div class="fv-row mb-7">
                                                    <label class="fw-semibold fs-6 mb-2">Katılımcı (Öğrenci)</label>
                                                    <select name="participant_id" id="participant_id" class="form-select form-select-solid fw-bold" data-kt-select2="true" data-placeholder="Katılımcı Seçin" data-allow-clear="true" data-kt-user-table-filter="role" data-hide-search="false">
                                                        <option value=""></option>
                                                        <?php foreach ($allUsers as $user) : ?>
                                                            <option value="<?= $user['id']; ?>">
                                                                <?= htmlspecialchars($user['name'] . ' ' . $user['surname']); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <div class="fv-row mb-7">
                                                    <label class="fw-semibold fs-6 mb-2">Toplantı Açıklaması</label>
                                                    <textarea name="description" class="form-control form-control-solid mb-3 mb-lg-0" rows="3" placeholder="Örn: 2. Sınıf Matematik Özel Ders"></textarea>
                                                </div>

                                                <div class="fv-row mb-7">
                                                    <label class="fw-semibold fs-6 mb-2">Toplantı Tarih ve Saati</label>
                                                    <input type="datetime-local" name="meeting_date" class="form-control form-control-solid" required />
                                                </div>

                                                <div class="d-flex justify-content-end">
                                                    <a href="zoom/index.php"  target="_blank" class="btn btn-success mr-2" style="margin-right:20px;">Zoom Login</a>
                                                    <button type="submit" id="submitButton" class="btn btn-primary">
                                                        <span class="indicator-label">Toplantı Oluştur</span>
                                                        <span class="indicator-progress">Lütfen bekleyin...
                                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                        </span>
                                                    </button>
                                                </div>
                                            </form>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php include_once "views/footer.php"; ?>
                    </div>
                    <?php include_once "views/aside.php"; ?>
                </div>
            </div>
        </div>
        <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
            <i class="ki-duotone ki-arrow-up">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </div>
        <script>
            var hostUrl = "assets/";
        </script>
        <script src="assets/plugins/global/plugins.bundle.js"></script>
        <script src="assets/js/scripts.bundle.js"></script>
        <script src="assets/js/widgets.bundle.js"></script>
        <script src="assets/js/custom/widgets.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            $(document).ready(function() {
                // Select2'yi başlatma
                $('[data-kt-select2="true"]').select2();

                $('#createMeetingForm').on('submit', function(e) {
                    e.preventDefault(); // Formun normal submit işlemini engelle

                    const submitButton = $('#submitButton');
                    const indicatorLabel = submitButton.find('.indicator-label');
                    const indicatorProgress = submitButton.find('.indicator-progress');

                    // Yükleme animasyonunu göster
                    indicatorLabel.hide();
                    indicatorProgress.show();
                    submitButton.attr('data-kt-indicator', 'on').prop('disabled', true);

                    // Form verilerini al
                    var formData = $(this).serialize();

                    $.ajax({
                        url: 'includes/ajax.php?service=createMeeting', // Yeni AJAX servisimiz
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        success: function(response) {
                            // Yükleme animasyonunu gizle
                            submitButton.removeAttr('data-kt-indicator').prop('disabled', false);
                            indicatorLabel.show();
                            indicatorProgress.hide();

                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Başarılı!',
                                    text: response.message,
                                    confirmButtonText: 'Tamam'
                                }).then(() => {
                                    // Formu temizle
                                    $('#createMeetingForm')[0].reset();
                                    // Select2 değerlerini sıfırla
                                    $('[data-kt-select2="true"]').val('').trigger('change');
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Hata!',
                                    text: response.message,
                                    confirmButtonText: 'Tamam'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            // Yükleme animasyonunu gizle
                            submitButton.removeAttr('data-kt-indicator').prop('disabled', false);
                            indicatorLabel.show();
                            indicatorProgress.hide();

                            console.error("AJAX Hatası:", status, error, xhr.responseText);
                            Swal.fire({
                                icon: 'error',
                                title: 'Bağlantı Hatası!',
                                text: 'Sunucuya bağlanırken bir hata oluştu: ' + error,
                                confirmButtonText: 'Tamam'
                            });
                        }
                    });
                });
            });
        </script>
    </body>

    </html>
<?php
} else {
    header("location: index.php"); // Yetkisiz erişim durumunda ana sayfaya yönlendir
    exit();
}
?>