<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 10001)) {
    // Veritabanı ve Sınıf dosyalarını dahil et
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";

    $contentId = $_GET['id'] ?? null;

    if (empty($contentId) || !is_numeric($contentId)) {
        header("location: konusma-kulubu-canli-ders-listesi.php");
        exit;
    }

    $class = new Classes();
    $mainSchoolClasses = $class->getAgeGroup(); // Tüm yaş grupları
    $clubList = $class->getClubList(); // Tüm kulüp türleri

    // Mevcut içeriğin verilerini çekme (Classes.classes.php içinde olmalı)
    $contentData = $class->getClubContentById($contentId);
    $contentFiles = $class->getClubContentFiles($contentId);

    if (!$contentData) {
        header("location: konusma-kulubu-canli-ders-listesi.php?error=notfound");
        exit;
    }

    // Seçili yaş gruplarını ayır
    $selectedClassIds = explode(';', $contentData['class_ids'] ?? '');

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
                                    <div class="card card-flush">
                                        <div class="card-header">
                                            <h3 class="card-title fw-bold">Kulüp İçeriğini Düzenle </h3>
                                        </div>

                                        <form class="form" action="#" id="ContentForm" data-content-id="<?= $contentId ?>">
                                            <input type="hidden" name="id" value="<?= $contentId ?>">
                                            <div class="card-body" style="margin-top: -71px;">
                                                <div class="row g-9 mb-8">
                                                    <div class="col-lg-6 fv-row">
                                                        <label class="required fs-6 fw-semibold mb-2" for="subject">Başlık</label>
                                                        <input type="text" class="form-control form-control-solid" placeholder="Konu Başlığı" id="subject" name="subject" value="<?= htmlspecialchars($contentData['title'] ?? '') ?>" required />
                                                    </div>
                                                    <div class="col-lg-6 fv-row">
                                                        <label class="required fs-6 fw-semibold mb-2" for="content_type">Kulüp Türü</label>
                                                        <select class="form-select form-select-solid" id="content_type" name="content_type" required data-control="select2" data-placeholder="Tür Seçimi">
                                                            <option value="">Seçiniz</option>
                                                            <?php foreach ($clubList as $club) { ?>
                                                                <option value="<?= $club['id'] ?>" <?= ($contentData['konusma_kulup_id'] == $club['id']) ? 'selected' : '' ?>><?= htmlspecialchars($club['name_tr']) ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row g-9 mb-8">
                                                    <div class="col-lg-6 fv-row">
                                                        <label class="fs-6 fw-semibold mb-2" for="main_school_class_id">Yaş Grubu </label>
                                                        <select class="form-select form-select-solid" id="main_school_class_id" name="main_school_class_id[]" multiple required data-control="select2" data-placeholder="Yaş Grupları Seçiniz">
                                                            <?php foreach ($mainSchoolClasses as $c) { ?>
                                                                <option value="<?= $c['id'] ?>" <?= (in_array($c['id'], $selectedClassIds)) ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-6 fv-row">
                                                        <!-- <label class="fs-6 fw-semibold mb-2" for="cover_img">Kapak Resmi (Değiştirmek için seçin)</label>
                                                        <input type="file" class="form-control form-control-solid" id="cover_img" name="cover_img" accept="image/*" />
                                                        <div class="form-text text-muted">Mevcut Resim: <a href="./<?= htmlspecialchars($contentData['cover_img'] ?? '') ?>" target="_blank" class="fw-bold">Görüntüle</a> (Yeni dosya seçilmezse eski dosya kalır.)</div>
                                                    </div> -->
                                                </div>

                                                <div class="row g-9 mb-8">
                                                    <div class="col-lg-3 fv-row">
                                                        <label class="required fs-6 fw-semibold mb-2" for="zoom_date">Toplantı Tarihi</label>
                                                        <input type="text" class="form-control form-control-solid" placeholder="Tarih Seçiniz" id="zoom_date" name="zoom_date" value="<?= htmlspecialchars($contentData['zoom_date'] ?? '') ?>" required />
                                                        <div class="form-text text-muted">Tarih değiştirilirse, Zoom toplantısı API üzerinden yeniden oluşturulacaktır.</div>
                                                    </div>
                                                    <div class="col-lg-3 fv-row">
                                                        <label class="required fs-6 fw-semibold mb-2" for="zoom_time">Toplantı Saati</label>
                                                        <input type="text" class="form-control form-control-solid" placeholder="Saat Seçiniz" id="zoom_time" name="zoom_time" value="<?= htmlspecialchars(substr($contentData['zoom_time'] ?? '', 0, 5)) ?>" required />
                                                    </div>
                                                    <div class="col-lg-6 fv-row">

                                                        <div class="col-lg-6 fv-row">
                                                            <label class="fs-6 fw-semibold mb-2" for="multi_files">Ek Dosyalar (Yeni Dosya Ekle)</label>

                                                            <input
                                                                type="file"
                                                                class="form-control form-control-solid"
                                                                id="multi_files"
                                                                name="multi_files[]"
                                                                multiple
                                                                accept="image/jpeg, image/png, image/gif, .pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-powerpoint, application/vnd.openxmlformats-officedocument.presentationml.presentation" />

                                                            <div class="form-text text-muted mb-3">
                                                                Desteklenen Dosyalar: Resimler (.jpg, .png, .gif), PDF, Word (.doc, .docx), Excel (.xls, .xlsx), PowerPoint (.ppt, .pptx)
                                                            </div>

                                                            <?php if (!empty($contentFiles)) { ?>
                                                                <h5 class="mt-4">Mevcut Ek Dosyalar:</h5>
                                                                <div class="d-flex flex-wrap gap-2">
                                                                    <?php foreach ($contentFiles as $file) {
                                                                        $fullFilePath = './' . htmlspecialchars($file['file_path']);
                                                                    ?>
                                                                        <div class="d-flex align-items-center bg-light-info rounded p-2 me-2" id="file_<?= $file['id'] ?>">
                                                                            <a href="<?= $fullFilePath ?>" target="_blank" class="fs-7 me-2 fw-semibold text-gray-800">
                                                                                <?= htmlspecialchars(basename($file['file_path'])) ?>
                                                                                <i class="ki-duotone ki-link fs-6 ms-1"></i>
                                                                            </a>
                                                                            <a href="#" onclick="deleteContentFile(<?= $file['id'] ?>, '<?= htmlspecialchars(basename($file['file_path'])) ?>'); return false;" class="btn btn-icon btn-sm btn-light-danger" title="Dosyayı Sil">
                                                                                <i class="ki-duotone ki-cross fs-3">
                                                                                    <span class="path1"></span>
                                                                                    <span class="path2"></span>
                                                                                </i>
                                                                            </a>
                                                                        </div>
                                                                    <?php } ?>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="card-footer d-flex justify-content-end py-6 px-9">
                                                    <button type="button" onclick="window.location.href='konusma-kulubu-canli-ders-listesi.php'" class="btn btn-light me-3">
                                                        Vazgeç
                                                    </button>
                                                    <button type="button" id="submitForm" class="btn btn-primary" data-kt-indicator="off">
                                                        <span class="indicator-label">
                                                            Güncelle
                                                        </span>
                                                        <span class="indicator-progress">
                                                            Lütfen Bekleyiniz... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                        </span>
                                                    </button>
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
                // Select2 başlatmaları
                $('#content_type').select2({
                    placeholder: "Seçiniz",
                    allowClear: true
                });
                $('#main_school_class_id').select2({
                    placeholder: "Yaş Grupları Seçiniz",
                    allowClear: true
                });

                // Flatpickr Başlatmaları (Tarih ve Saat Seçimi)
                flatpickr("#zoom_date", {
                    dateFormat: "Y-m-d",
                    allowInput: true,
                    placeholder: "Tarih Seçiniz"
                });

                flatpickr("#zoom_time", {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    time_24hr: true,
                    allowInput: true,
                    placeholder: "Saat Seçiniz"
                });

                // ----------------------------------------------------------------------
                // GÜNCELLEME FORMU GÖNDERİMİ
                // ----------------------------------------------------------------------
                const submitButton = $('#submitForm');
                const form = $('#ContentForm');
                const contentId = form.data('content-id');

                submitButton.on('click', function(e) {
                    e.preventDefault();

                    // Temel Validasyonlar
                    if ($('#subject').val().trim() === '' || $('#content_type').val() === '') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Uyarı',
                            text: 'Lütfen zorunlu alanları (Başlık, Kulüp Türü) doldurun.',
                            confirmButtonText: 'Tamam'
                        });
                        return;
                    }
                     if ($('#zoom_date').val().trim() === '' || $('#zoom_time').val() === '') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Uyarı',
                            text: 'Lütfen zorunlu alanları (Toplantı Tarihi, Toplantı Saati) doldurun.',
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

                    // Buton durumunu "Kaydediliyor..." olarak ayarla
                    submitButton.attr("data-kt-indicator", "on").prop("disabled", true);

                    let formData = new FormData();
                    formData.append('id', contentId); // Güncellenecek ID

                    // KRİTİK: ÇOKLU SINIF ID'LERİNİN TEK SÜTUNA ';' İLE EKLENMESİ
                    formData.append('class_ids', selectedClassIds.join(';'));


                    // Diğer Temel Alanlar
                    formData.append('subject', $('#subject').val());
                    formData.append('content_type', $('#content_type').val());
                    formData.append('zoom_date', $('#zoom_date').val());
                    formData.append('zoom_time', $('#zoom_time').val());

                    // Kapak Resmini Ekle (Sadece dosya seçilmişse)
                    // if ($('#cover_img')[0].files.length > 0) {
                    //     formData.append('cover_img', $('#cover_img')[0].files[0]);
                    // }

                    // Çoklu Dosyaları Ekle (Yeni yüklenenler)
                    const multiFiles = $('#multi_files')[0].files;
                    for (let i = 0; i < multiFiles.length; i++) {
                        formData.append('multi_files[]', multiFiles[i]);
                    }


                    // AJAX gönderimi
                    $.ajax({
                        url: './includes/ajax.php?service=clubContentUpdate', // Güncelleme Servisi
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            // Buton durumunu sıfırla
                            submitButton.attr("data-kt-indicator", "off").prop("disabled", false);

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

                            if (res.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Başarılı',
                                    text: 'Başarıyla güncellendi! Yeni Zoom URL\'leri otomatik oluşturuldu.',
                                    confirmButtonText: 'Tamam'
                                }).then(() => {
                                    location.reload(); // Sayfayı yenile ve güncel verileri göster
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
                            // Buton durumunu sıfırla (hata durumunda)
                            submitButton.attr("data-kt-indicator", "off").prop("disabled", false);

                            Swal.fire({
                                icon: 'error',
                                title: 'Sunucu Hatası',
                                text: 'Form gönderilirken bir sunucu hatası oluştu: ' + error,
                                confirmButtonText: 'Tamam'
                            });
                        }
                    });
                });

                // ----------------------------------------------------------------------
                // EK DOSYA SİLME FONKSİYONU (Aynı kalacak)
                // ----------------------------------------------------------------------
                window.deleteContentFile = function(fileId, fileName) {
                    Swal.fire({
                        text: `${fileName} adlı dosyayı silmek istediğinizden emin misiniz?`,
                        icon: "warning",
                        showCancelButton: true,
                        buttonsStyling: false,
                        confirmButtonText: "Evet, Sil!",
                        cancelButtonText: "Hayır",
                        customClass: {
                            confirmButton: "btn btn-danger",
                            cancelButton: "btn btn-light"
                        }
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "includes/ajax.php?service=deleteClubContentFile",
                                type: "POST",
                                data: {
                                    file_id: fileId
                                },
                                dataType: 'json',
                                success: function(response) {
                                    if (response.status === 'success') {
                                        Swal.fire({
                                            text: "Dosya başarıyla silindi!",
                                            icon: "success"
                                        }).then(function() {
                                            $('#file_' + fileId).remove(); // HTML elementini kaldır
                                        });
                                    } else {
                                        Swal.fire({
                                            text: response.message || "Dosya silinirken hata oluştu.",
                                            icon: "error"
                                        });
                                    }
                                },
                                error: function() {
                                    Swal.fire({
                                        text: "Sunucu bağlantı hatası.",
                                        icon: "error"
                                    });
                                }
                            });
                        }
                    });
                }
            });
        </script>
    </body>

</html>
<?php } else {
    header("location: index");
}
?>