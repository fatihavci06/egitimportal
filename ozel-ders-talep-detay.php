<?php
session_start();
define('GUARD', true);

// Bu sayfada artık POST isteği işlenmeyecek, bu görev process_update.php'ye taşındı.

if (isset($_SESSION['role']) and ($_SESSION['role'] == 1)) {
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";

    include_once "views/pages-head.php";
    $class = new Classes();
    $data = $class->getPrivateLessonRequestById(@$_GET['id']);

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
                                                <h3 class="fw-bold m-0">Ders Talep Detayları ve Güncelleme</h3>
                                            </div>
                                            <div class="card-toolbar">
                                                <!-- Buraya ek araç çubukları eklenebilir -->
                                            </div>
                                        </div>
                                        <div class="card-body pt-0">
                                            <!-- Güncelleme Formu -->
                                            <form id="updatePrivateLessonForm" class="p-5">
                                                <input type="hidden" name="request_id" value="<?php echo htmlspecialchars($data['id'] ?? ''); ?>">

                                                <div class="mb-5">
                                                    <label for="student_full_name" class="form-label fs-6 fw-bold mb-2">Öğrenci Adı Soyadı:</label>
                                                    <input type="text" id="student_full_name" name="student_full_name" class="form-control form-control-solid rounded-lg" value="<?php echo htmlspecialchars($data['student_full_name'] ?? ''); ?>" readonly>
                                                </div>

                                                <div class="mb-5">
                                                    <label for="class_name" class="form-label fs-6 fw-bold mb-2">Sınıf Adı:</label>
                                                    <input type="text" id="class_name" name="class_name" class="form-control form-control-solid rounded-lg" value="<?php echo htmlspecialchars($data['class_name'] ?? ''); ?>" readonly>
                                                </div>

                                                <div class="mb-5">
                                                    <label for="lesson_name" class="form-label fs-6 fw-bold mb-2">Ders Adı:</label>
                                                    <input type="text" id="lesson_name" name="lesson_name" class="form-control form-control-solid rounded-lg" value="<?php echo htmlspecialchars($data['lesson_name'] ?? ''); ?>" readonly>
                                                </div>

                                                <div class="mb-5">
                                                    <label for="unit_name" class="form-label fs-6 fw-bold mb-2">Ünite Adı:</label>
                                                    <input type="text" id="unit_name" name="unit_name" class="form-control form-control-solid rounded-lg" value="<?php echo htmlspecialchars($data['unit_name'] ?? ''); ?>" readonly>
                                                </div>

                                                <div class="mb-5">
                                                    <label for="topic_name" class="form-label fs-6 fw-bold mb-2">Konu Adı:</label>
                                                    <input type="text" id="topic_name" name="topic_name" class="form-control form-control-solid rounded-lg" value="<?php echo htmlspecialchars($data['topic_name'] ?? ''); ?>" readonly>
                                                </div>

                                                <div class="mb-5">
                                                    <label for="subtopic_name" class="form-label fs-6 fw-bold mb-2">Alt Konu Adı:</label>
                                                    <input type="text" id="subtopic_name" name="subtopic_name" class="form-control form-control-solid rounded-lg" value="<?php echo htmlspecialchars($data['subtopic_name'] ?? ''); ?>" readonly>
                                                </div>

                                                <div class="mb-5">
                                                    <label for="request_status_text" class="form-label fs-6 fw-bold mb-2">Durum:</label>
                                                    <input type="text" id="request_status_text" name="request_status_text" class="form-control form-control-solid rounded-lg" value="<?php echo htmlspecialchars($data['request_status_text'] ?? ''); ?>" readonly>
                                                </div>

                                                <div class="mb-5">
                                                    <label for="notes" class="form-label fs-6 fw-bold mb-2">Öğrenci Uygun Zamanlar:</label>
                                                    <textarea id="notes" name="notes" class="form-control form-control-solid rounded-lg" rows="4" readonly><?php echo htmlspecialchars($data['time_slot'] ?? ''); ?></textarea>
                                                </div>

                                                <div class="mb-8">
                                                    <label for="assigned_teacher_id" class="form-label fs-6 fw-bold mb-2">Öğretmen Ata:<span class="text-danger">*</span></label>
                                                    <select id="assigned_teacher_id" name="assigned_teacher_id" class="form-select form-select-solid rounded-lg" required>
                                                        <option value="">Öğretmen Seçiniz</option>
                                                        <?php if (!empty($teachers)): ?>
                                                            <?php foreach ($teachers as $teacher): ?>
                                                                <option value="<?php echo htmlspecialchars($teacher['id']); ?>"
                                                                    <?php echo (isset($data['assigned_teacher_id']) && $data['assigned_teacher_id'] == $teacher['id']) ? 'selected' : ''; ?>>
                                                                    <?php echo htmlspecialchars($teacher['name'] . ' ' . $teacher['surname']); ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <option value="" disabled>Öğretmen Bulunamadı</option>
                                                        <?php endif; ?>
                                                    </select>
                                                </div>
                                                <div class="mb-5">
                                                    <label for="desired_date" class="form-label fs-6 fw-bold mb-2">Planlanan Zaman:<span class="text-danger">*</span></label>
                                                    <input type="datetime-local" id="desired_date" name="desired_date" class="form-control form-control-solid rounded-lg" value="<?php echo htmlspecialchars($data['meet_date'] ?? ''); ?>" required>
                                                </div>

                                                <div class="d-flex justify-content-end align-items-center">
                                                    <div id="loadingSpinner" class="loading-spinner me-3"></div>
                                                    <button type="submit" class="btn btn-primary min-w-125px">Güncelle</button>
                                                </div>
                                                <div id="responseMessage" class="mt-4 text-center fw-bold"></div>
                                            </form>
                                            <!-- End Güncelleme Formu -->
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

        <!-- AJAX İsteği için özel JavaScript -->
        <script>
            // jQuery hazır olduğunda çalıştır
            $(document).ready(function() {
                const form = $('#updatePrivateLessonForm');
                const loadingSpinner = $('#loadingSpinner');
                const responseMessage = $('#responseMessage');
                const submitButton = form.find('button[type="submit"]');

                form.on('submit', function(e) {
                    e.preventDefault(); // Varsayılan form gönderme işlemini durdur

                    // Mesaj alanını temizle ve spinner'ı göster
                    responseMessage.text('').removeClass('text-success text-danger'); // Stilini sıfırla
                    loadingSpinner.show();
                    submitButton.prop('disabled', true); // Butonu devre dışı bırak

                    // Sadece belirtilen alanları al
                    const request_id = $('input[name="request_id"]').val();
                    const assigned_teacher_id = $('#assigned_teacher_id').val();
                    const desired_date_str = $('#desired_date').val(); // String olarak al

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

                    if (!desired_date_str) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Hata!',
                            text: 'Lütfen planlanan zamanı giriniz.',
                            confirmButtonText: 'Tamam'
                        });
                        loadingSpinner.hide();
                        submitButton.prop('disabled', false);
                        return; // Fonksiyonu durdur
                    }

                    // Tarih/saat kontrolü: Geçmiş bir tarih/saat girilemez
                    const desired_date = new Date(desired_date_str);
                    const now = new Date();

                    // Saniyeleri ve milisaniyeleri sıfırla ki karşılaştırma doğru olsun
                    desired_date.setSeconds(0, 0);
                    now.setSeconds(0, 0);

                    if (desired_date < now) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Hata!',
                            text: 'Planlanan zaman şu anki zamandan daha eski olamaz.',
                            confirmButtonText: 'Tamam'
                        });
                        loadingSpinner.hide();
                        submitButton.prop('disabled', false);
                        return; // Fonksiyonu durdur
                    }


                    $.ajax({
                        url: 'includes/ajax.php?service=privateLessonRequest', // AJAX isteğini işleyecek PHP dosyası
                        method: 'POST',
                        data: {
                            request_id: request_id,
                            assigned_teacher_id: assigned_teacher_id,
                            desired_date: desired_date_str // String olarak gönder
                        },
                        dataType: 'json', // Yanıtın JSON formatında olduğunu belirt
                        success: function(data) {
                            // Spinner'ı gizle ve butonu etkinleştir
                            loadingSpinner.hide();
                            submitButton.prop('disabled', false);

                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Başarılı!',
                                    text: data.message,
                                    confirmButtonText: 'Tamam'
                                });
                                responseMessage.text(data.message).addClass('text-success'); // Başarılı mesaj için yeşil renk
                                // Başarılı bir güncellemeden sonra isterseniz sayfayı yeniden yükleyebilirsiniz
                                // Örneğin: setTimeout(() => window.location.reload(), 1500);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Hata!',
                                    text: data.message,
                                    confirmButtonText: 'Tamam'
                                });
                                responseMessage.text(data.message).addClass('text-danger'); // Hata mesajı için kırmızı renk
                            }
                        },
                        error: function(xhr, status, error) {
                            // Hataları yakala
                            loadingSpinner.hide();
                            submitButton.prop('disabled', false);
                            Swal.fire({
                                icon: 'error',
                                title: 'Hata!',
                                text: 'Bir hata oluştu: ' + error,
                                confirmButtonText: 'Tamam'
                            });
                            responseMessage.text('Bir hata oluştu: ' + error).addClass('text-danger'); // Hata mesajı için kırmızı renk
                            console.error('AJAX Hatası:', status, error, xhr.responseText);
                        }
                    });
                });
            });
        </script>
        <!-- End AJAX İsteği için özel JavaScript -->
    </body>

    </html>
<?php
} else {
    header("location: index.php"); // Yetkisiz erişim durumunda index sayfasına yönlendir
    exit();
}
?>