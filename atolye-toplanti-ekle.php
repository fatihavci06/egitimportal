<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 10001)) {
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";

    // Veri çekme ve başlık ayarları (Gerekirse bu kısım yukarı taşınabilir)
    $class = new Classes();
    $mainSchoolClasses = $class->getAgeGroup();

    include_once "views/pages-head.php";
?>

    <head>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    </head>

    <body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" data-kt-app-aside-enabled="true" data-kt-app-aside-fixed="true" data-kt-app-aside-push-toolbar="true" data-kt-app-aside-push-footer="true" class="app-default">
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
                                    <div class="card-body pt-5">
                                        <form class="form" action="#" id="ContentForm">

                                            <div class="row mt-4">
                                              
                                                <div class="col-lg-6">
                                                    <label class="required fs-6 fw-semibold mb-2" for="subject">Başlık</label>
                                                    <input type="text" class="form-control" placeholder="Konu Başlığı" id="subject" name="subject" required />
                                                </div>
                                            </div>
                                             <div class="row mt-4">
                                                <div class="col-lg-6">
                                                    <label class="fs-6 fw-semibold mb-2" for="main_school_class_id">Yaş Grubu </label>
                                                    <select class="form-select" id="main_school_class_id" name="main_school_class_id[]" multiple required data-control="select2" data-placeholder="Yaş Grupları Seçiniz">
                                                        <?php foreach ($mainSchoolClasses as $c) { ?>
                                                            <option value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                              
                                            </div>

                                            <div class="row mt-4">
                                              
                                                
                                                <div class="col-lg-6">
                                                    <label class="required fs-6 fw-semibold mb-2" for="content_type">Atölye Türü</label>
                                                    <select class="form-select" id="content_type" name="content_type" required data-control="select2" data-placeholder="Tür Seçimi">
                                                        <option value="">Seçiniz</option>
                                                        <option value="Spor ve Dans Atölyesi">Spor ve Dans Atölyesi</option>
                                                        <option value="Bilim Atölyesi">Bilim Atölyesi</option>
                                                        <option value="Sanat Atölyesi">Sanat Atölyesi</option>
                                                        <option value="Puzzle Atölyesi">Puzzle Atölyesi</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row mt-4">
                                                <div class="col-lg-3">
                                                    <label class="fs-6 fw-semibold mb-2" for="zoom_date">Toplantı Tarihi</label>
                                                    <input type="text" class="form-control" placeholder="Tarih Seçiniz" id="zoom_date" name="zoom_date" />
                                                </div>
                                                <div class="col-lg-3">
                                                    <label class="fs-6 fw-semibold mb-2" for="zoom_time">Toplantı Saati</label>
                                                    <input type="text" class="form-control" placeholder="Saat Seçiniz" id="zoom_time" name="zoom_time" />
                                                </div>
                                            </div>

                                         

                                          

                                            <div class="row mt-5">
                                                <div class="col-lg-11"></div>
                                                <div class="col-lg-1">
                                                    <button type="button" id="submitForm" class="btn btn-primary btn-sm">Kaydet</button>
                                                </div>
                                            </div>
                                        </form>


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
        <script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
        <script src="assets/plugins/custom/tinymce/tinymce.bundle.js"></script>
        <script src="assets/js/widgets.bundle.js"></script>
        <script src="assets/js/custom/widgets.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


        <script>
            $(document).ready(function() {
                let fieldCount = 0;

                // ----------------------------------------------------------------------
                // YENİ EKLENEN: Flatpickr Başlatmaları (Tarih ve Saat Seçimi)
                // ----------------------------------------------------------------------
                flatpickr("#zoom_date", {
                    dateFormat: "Y-m-d", // Örn: 2025-11-05
                    allowInput: true,
                    placeholder: "Tarih Seçiniz"
                });

                flatpickr("#zoom_time", {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i", // Örn: 15:30
                    time_24hr: true,
                    allowInput: true,
                    placeholder: "Saat Seçiniz"
                });
               

                $('#submitForm').on('click', function(e) {
                    e.preventDefault();
               

                    // VALIDASYONLAR (Kısaltıldı, eski validasyonlar geçerlidir)
                    if ($('#subject').val().trim() === '') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Uyarı',
                            text: 'Lütfen konu başlığını girin.',
                            confirmButtonText: 'Tamam'
                        });
                        return;
                    }

                    if ($('#content_type').val() === '') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Uyarı',
                            text: 'Lütfen Atölye Türü alanını seçin.',
                            confirmButtonText: 'Tamam'
                        });
                        return;
                    }

                   
                   
                    
                    const selectedClassIds = $('#main_school_class_id').val();
                    if (!selectedClassIds || selectedClassIds.length === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Uyarı',
                            text: 'Lütfen en az bir Yaş Grubu seçin.',
                            confirmButtonText: 'Tamam'
                        });
                        return;
                    }

                    let formData = new FormData();
                    let isValid = true;

                 
                    if (!isValid) return;

                    // 🔽 FORMDATA EKLEMELERİ

                    // KRİTİK: ÇOKLU SINIF ID'LERİNİN TEK SÜTUNA ';' İLE EKLENMESİ
                    formData.append('class_ids', selectedClassIds.join(';'));


                    // Diğer Temel Alanlar
                    formData.append('subject', $('#subject').val());
                    formData.append('content_type', $('#content_type').val());
             

                    formData.append('zoom_date', $('#zoom_date').val()); // Yeni
                    formData.append('zoom_time', $('#zoom_time').val()); // Yeni

                    // WordWall Verileri (Dolu olanları ekler)
               

                    // AJAX gönderimi
                    $.ajax({
                        // KRİTİK: YENİ AJAX ENDPOINTİ
                        url: './includes/ajax.php?service=createAtolyeContent',
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            let res = response;

                            if (typeof response === 'string') {
                                try {
                                    res = JSON.parse(response);
                                } catch (e) {
                                    console.error("JSON Parse Hatası:", e, "Ham Yanıt:", response);
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Hata',
                                        text: 'Sunucudan gelen yanıt beklenenden farklı bir formatta. Lütfen konsolu kontrol edin.',
                                        confirmButtonText: 'Tamam'
                                    });
                                    return;
                                }
                            }

                            console.log("Sunucu Yanıt Nesnesi:", res);

                            if (res.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Başarılı',
                                    text: 'Başarıyla kaydedildi!',
                                    confirmButtonText: 'Tamam'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Hata',
                                    text: res.message || 'Beklenmeyen bir hata oluştu.',
                                    confirmButtonText: 'Tamam'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Sunucu Hatası',
                                text: 'Form gönderilirken bir sunucu hatası oluştu: ' + error,
                                confirmButtonText: 'Tamam'
                            });
                        }
                    });
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                // Select2 başlatmaları
                $('#content_type').select2({
                    placeholder: "Seçiniz",
                    allowClear: true
                });
                $('#main_school_class_id').select2({
                    placeholder: "Yaş Grupları Seçiniz",
                    allowClear: true
                });
            });
        </script>
    </body>

</html>
<?php } else {
    header("location: index");
}
?>