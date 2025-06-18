<?php
session_start();
define('GUARD', true);

// Bu sayfada artık POST isteği işlenmeyecek, bu görev process_update.php'ye taşındı.

if (isset($_SESSION['role']) and ($_SESSION['role'] == 1)) {
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";

    include_once "views/pages-head.php";
    $class = new Classes();
    $data = $class->getCoachingRequestById(@$_GET['id']);

    // Eğer veri bulunamazsa veya geçersizse yönlendir
    if (!$data) {
        header("location: index.php?error=requestnotfound");
        exit();
    }

    // Öğretmenleri çek (Bu metodun classes.classes.php dosyanızda olması beklenir)
    $teachers = $class->getTeachers();
?>
    <!DOCTYPE html>
    <html lang="tr">

    <head>
        <!-- Tailwind CSS CDN - Sayfanın estetiğini artırmak için eklenmiştir -->

        <!-- jQuery CDN - AJAX için eklendi -->

        <style>
            /* Metronic Theme ile uyumlu temel stil ayarlamaları */
            body {
                font-family: "Inter", sans-serif;
            }

            .form-control,
            .form-select {
                border-radius: 0.5rem !important;
                /* Tailwind rounded-lg ile uyumlu */
                padding: 0.75rem 1rem !important;
                border: 1px solid #e2e8f0;
                /* Tailwind border-gray-300 */
            }

            .btn-primary {
                background-color: #3667c2 !important;
                /* Metronic primary color */
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
                /* Tailwind bg-gray-100 */
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
                /* Varsayılan olarak gizli */
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
        <?php include_once "views/pages-head.php"; // Head içeriğini tekrar dahil et 
        ?>
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
                                            <h3 class="fw-bold m-0">Koçluk/Rehberlik Talep Detayları ve Güncelleme</h3>
                                        </div>
                                        <div class="card-toolbar">
                                            </div>
                                    </div>
                                    <div class="card-body pt-0">
                                        <form id="updateCoachingRequestForm" class="p-5">
                                            <input type="hidden" name="request_id" value="<?php echo htmlspecialchars($data['id'] ?? ''); ?>">

                                            <div class="mb-5">
                                                <label for="user_full_name" class="form-label fs-6 fw-bold mb-2">Öğrenci Adı Soyadı:</label>
                                                <input type="text" id="user_full_name" name="user_full_name" class="form-control form-control-solid rounded-lg" value="<?php echo htmlspecialchars($data['user_full_name'] ?? ''); ?>" readonly>
                                            </div>

                                            <div class="mb-5">
                                                <label for="request_type" class="form-label fs-6 fw-bold mb-2">Talep Türü:</label>
                                                <input type="text" id="request_type" name="request_type" class="form-control form-control-solid rounded-lg" value="<?php echo htmlspecialchars($data['request_type'] ?? ''); ?>" readonly>
                                            </div>
                                            
                                            <div class="mb-5">
                                                <label for="request_status_text" class="form-label fs-6 fw-bold mb-2">Durum:</label>
                                                <input type="text" id="request_status_text" name="request_status_text" class="form-control form-control-solid rounded-lg" value="<?php echo htmlspecialchars($data['status_text'] ?? ''); ?>" readonly>
                                            </div>

                                            <div class="mb-8">
                                                <label for="assigned_teacher_id" class="form-label fs-6 fw-bold mb-2">Öğretmen Ata:<span class="text-danger">*</span></label>
                                                <select id="assigned_teacher_id" name="assigned_teacher_id" class="form-select form-select-solid rounded-lg" required>
                                                    <option value="">Öğretmen Seçiniz</option>
                                                    <?php if (!empty($teachers)): // $teachers değişkeninin öğretmen listesini içerdiğini varsayıyoruz ?>
                                                        <?php foreach ($teachers as $teacher): ?>
                                                            <option value="<?php echo htmlspecialchars($teacher['id']); ?>"
                                                                <?php echo (isset($data['teacher_id']) && $data['teacher_id'] == $teacher['id']) ? 'selected' : ''; ?>>
                                                                <?php echo htmlspecialchars($teacher['name'] . ' ' . $teacher['surname']); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <option value="" disabled>Öğretmen Bulunamadı</option>
                                                    <?php endif; ?>
                                                </select>
                                            </div>

                                            <div class="d-flex justify-content-end align-items-center">
                                                <div id="loadingSpinner" class="loading-spinner me-3"></div>
                                                <button type="submit" class="btn btn-primary min-w-125px">Güncelle</button>
                                            </div>
                                            <div id="responseMessage" class="mt-4 text-center fw-bold"></div>
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

    <script>
        // jQuery hazır olduğunda çalıştır
        $(document).ready(function() {
            const form = $('#updateCoachingRequestForm');
            const loadingSpinner = $('#loadingSpinner');
            const responseMessage = $('#responseMessage');
            const submitButton = form.find('button[type="submit"]');

            form.on('submit', function(e) {
                e.preventDefault(); // Varsayılan form gönderme işlemini durdur

                // Mesaj alanını temizle ve spinner'ı göster
                responseMessage.text('').removeClass('text-success text-danger');
                loadingSpinner.show();
                submitButton.prop('disabled', true);

                // Sadece belirtilen alanları al
                const request_id = $('input[name="request_id"]').val();
                const assigned_teacher_id = $('#assigned_teacher_id').val();
                // assignment_date kaldırıldı

                // Zorunlu alan kontrolü
                if (!assigned_teacher_id) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Hata!',
                        text: 'Lütfen bir öğretmen seçiniz.',
                        confirmButtonText: 'Tamam'
                    });
                    loadingSpinner.hide();
                    submitButton.prop('disabled', false);
                    return; // Fonksiyonu durdur
                }

                // assignment_date için olan kontrol kaldırıldı

                $.ajax({
                    url: 'includes/ajax.php?service=updateCoachingRequest',
                    method: 'POST',
                    data: {
                        request_id: request_id,
                        assigned_teacher_id: assigned_teacher_id
                        // assignment_date kaldırıldı
                    },
                    dataType: 'json',
                    success: function(data) {
                        loadingSpinner.hide();
                        submitButton.prop('disabled', false);

                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Başarılı!',
                                text: data.message,
                                confirmButtonText: 'Tamam'
                            });
                            responseMessage.text(data.message).addClass('text-success');
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Hata!',
                                text: data.message,
                                confirmButtonText: 'Tamam'
                            });
                            responseMessage.text(data.message).addClass('text-danger');
                        }
                    },
                    error: function(xhr, status, error) {
                        loadingSpinner.hide();
                        submitButton.prop('disabled', false);
                        Swal.fire({
                            icon: 'error',
                            title: 'Hata!',
                            text: 'Bir hata oluştu: ' + error,
                            confirmButtonText: 'Tamam'
                        });
                        responseMessage.text('Bir hata oluştu: ' + error).addClass('text-danger');
                        console.error('AJAX Hatası:', status, error, xhr.responseText);
                    }
                });
            });
        });
    </script>
    </body>

    </html>
<?php
} else {
    header("location: index.php"); // Yetkisiz erişim durumunda index sayfasına yönlendir
    exit();
}
?>