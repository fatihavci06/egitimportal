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
                                    <div class="card-body ">
                                        <form class="form" action="#" id="ContentForm">

                                            <div class="row ">
                                                <div class="col-lg-6">
                                                    <label class="fs-6 fw-semibold mb-2" for="main_school_class_id">Yaş Grubu </label>
                                                    <select class="form-select" id="main_school_class_id" name="main_school_class_id[]" multiple required data-control="select2" data-placeholder="Yaş Grupları Seçiniz">
                                                        <?php foreach ($mainSchoolClasses as $c) { ?>
                                                            <option value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label class="required fs-6 fw-semibold mb-2" for="subject">Başlık</label>
                                                    <input type="text" class="form-control" placeholder="Konu Başlığı" id="subject" name="subject" required />
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
                                                <label class="required fs-6 fw-semibold mb-2">İçerik Türü</label>
                                                <div class="fv-row mb-7 mt-4" id="chooseOne">
                                                    <label>
                                                        <input class="form-check-input " type="radio" name="secim" value="video_link"> Video URL
                                                    </label>
                                                    <label>
                                                        <input class="form-check-input ms-10" type="radio" name="secim" value="file_path"> Dosya / Görsel Yükle
                                                    </label>

                                                    <label>
                                                        <input class="form-check-input ms-10" type="radio" name="secim" value="wordwall"> İnteraktif Oyun
                                                    </label>
                                                    
                                                  
                                                </div>

                                                <div id="videoInput" class="mb-4" style="display:none;">
                                                    <label for="video_url">Video Link (Youtube):</label>
                                                    <input type="text" class="form-control" name="video_url" id="video_url">
                                                </div>

                                                <div id="fileInput" class="mb-4" style="display:none;">
                                                    <label for="files">Dosya ve Görsel Yükle (Çoklu Seçilebilir):</label>
                                                    <input type="file" class="form-control" name="files[]" id="files" multiple accept=".xls,.xlsx,.doc,.docx,.ppt,.pptx,.png,.jpeg,.jpg,.svg,.pdf">
                                                    <div id="fileDescriptions"></div>
                                                    <small class="text-muted">Yüklediğiniz her dosya/görsel için aşağıda bir açıklama alanı açılacaktır.</small>
                                                </div>

                                                <div id="wordwallInputs" class="mb-4" style="display:none;">
                                                    <label>WordWall Iframe Linkleri (Çoklu):</label>
                                                    <div id="dynamicFields">
                                                        <div class="input-group mb-2" data-index="0">
                                                            <input type="text" name="wordWallTitles[]" class="form-control me-2" placeholder="Başlık">
                                                            <input type="text" name="wordWallUrls[]" class="form-control me-2" placeholder="URL">
                                                            <button type="button" class="btn btn-danger removeField">Sil</button>
                                                        </div>
                                                    </div>
                                                    <button type="button" id="addField" class="btn btn-sm btn-primary mt-2">Ekle</button>
                                                </div>

                                            </div>

                                            <div id="textInput" class="mb-4" style="display:none;">
                                                <label for="mcontent">Metin İçeriği:</label>
                                                <textarea class="form-control tinymce-editor" name="mcontent" id="mcontent" rows="4"></textarea>
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
                // ----------------------------------------------------------------------
                // YENİ EKLENEN BİTİŞ
                // ----------------------------------------------------------------------

                // Wordwall Dinamik Alan Ekleme/Silme
                $('#addField').on('click', function() {
                    fieldCount++;
                    $('#dynamicFields').append(`
					<div class="input-group mb-2" data-index="${fieldCount}">
						<input type="text" name="wordWallTitles[]" class="form-control me-2" placeholder="Başlık">
						<input type="text" name="wordWallUrls[]" class="form-control me-2" placeholder="URL">
						<button type="button" class="btn btn-danger removeField">Sil</button>
					</div>
				`);
                });
                $('#dynamicFields').on('click', '.removeField', function() {
                    $(this).closest('.input-group').remove();
                });

                // TinyMCE Başlatma
                tinymce.init({
                    selector: '.tinymce-editor',
                    // diğer ayarlar...
                });

                // Dosya Yüklendiğinde Açıklama Alanlarını Oluşturma
                $('#files').on('change', function() {
                    const files = this.files;
                    const container = $('#fileDescriptions');
                    container.empty(); // Önceki açıklamaları temizle

                    for (let i = 0; i < files.length; i++) {
                        const fileName = files[i].name;
                        const descriptionField = `
						<div class="mb-3">
							<label for="description_${i}" class="form-label">"${fileName}" dosyası için açıklama:</label>
							<textarea class="form-control" name="descriptions[]" id="description_${i}" rows="2"></textarea>
						</div>
					`;
                        container.append(descriptionField);
                    }
                });

                // İçerik Türü Seçimi - GÖSTER/GİZLE İŞLEMLERİ
                $('input[name="secim"]').on('change', function() {
                    let selected = $(this).val();

                    // Tüm inputları gizle
                    $('#videoInput, #fileInput, #textInput, #wordwallInputs').hide();

                    // Seçime göre ilgili inputu göster
                    if (selected === 'video_link') {
                        $('#videoInput').show();
                    } else if (selected === 'file_path') {
                        $('#fileInput').show(); // Sadece tek bir dosya/görsel yükleme alanı gösterilir
                    } else if (selected === 'content') {
                        $('#textInput').show();
                    } else if (selected === 'wordwall') {
                        $('#wordwallInputs').show();
                    }
                });

                $('#submitForm').on('click', function(e) {
                    e.preventDefault();
                    const content = tinymce.get('mcontent').getContent();

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

                    const selectedContentType = $('input[name="secim"]:checked').val();
                   
                    
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

                    // WordWall Validasyonu (Aynı Kaldı)
                    $('#dynamicFields .input-group').each(function(index) {
                        const titleInput = $(this).find('input[name="wordWallTitles[]"]');
                        const urlInput = $(this).find('input[name="wordWallUrls[]"]');

                        const titleValue = titleInput.val().trim();
                        const urlValue = urlInput.val().trim();

                        if (urlValue !== '' && titleValue === '') {
                            isValid = false;
                            Swal.fire({
                                icon: 'warning',
                                title: 'Eksik Alan',
                                text: 'Lütfen bağlantı girilen her WordWall satırı için bir başlık yazınız.'
                            });
                            return false;
                        }
                    });

                    if (!isValid) return;

                    // 🔽 FORMDATA EKLEMELERİ

                    // KRİTİK: ÇOKLU SINIF ID'LERİNİN TEK SÜTUNA ';' İLE EKLENMESİ
                    formData.append('class_ids', selectedClassIds.join(';'));


                    // Diğer Temel Alanlar
                    formData.append('subject', $('#subject').val());
                    formData.append('content_type', $('#content_type').val());
                    formData.append('content', content);
                    formData.append('secim', selectedContentType);
                    formData.append('video_url', $('#video_url').val());

                    // YENİ EKLENEN VERİLER: ZOOM TARİH VE SAAT
                   

                    // WordWall Verileri (Dolu olanları ekler)
                    $('#dynamicFields .input-group').each(function() {
                        const title = $(this).find('input[name="wordWallTitles[]"]').val().trim();
                        const url = $(this).find('input[name="wordWallUrls[]"]').val().trim();

                        if (title !== '' && url !== '') {
                            formData.append(`wordWallTitles[]`, title);
                            formData.append(`wordWallUrls[]`, url);
                        }
                    });

                    // Dosya/Görsel Yüklemeleri (tek input 'files' ile halledildi)
                    const files = $('#files')[0].files;
                    for (let i = 0; i < files.length; i++) {
                        // PHP tarafında `$_FILES['files']` dizisine erişilecektir.
                        formData.append('files[]', files[i]);
                    }

                    // Dosya Açıklamaları
                    $("textarea[name='descriptions[]']").each(function() {
                        formData.append('descriptions[]', $(this).val());
                    });

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
                                    text: 'Atölye içeriği başarıyla kaydedildi!',
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