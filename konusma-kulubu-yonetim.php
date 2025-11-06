<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);

// GEREKLİ SINIFLAR VE BAĞLANTILAR
// NOT: Bu kod bloğunun çalışması için Classes.classes.php dosyanızda
// getAtolyeContentList(), getClassNamesByIds() ve getAgeGroup() fonksiyonlarının tanımlı olması gerekir.
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 10001)) {
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";

    include_once "views/pages-head.php";
    $contentList = new Classes();
    $contentLists = $contentList->getKulupList(); // İçerik listesini çekiyoruz
    
    // Sınıf listesini çekme (Çoklu seçim için)
    $class = new Classes(); 
    $mainSchoolClasses = $class->getAgeGroup(); 
?>

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
                                    <div class="card">
                                        <div class="card-header border-0 pt-6">
                                            <div class="card-title">
                                                <div class="d-flex align-items-center position-relative my-1">
                                                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                    <input type="text" data-kt-customer-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Ara" />
                                                </div>
                                            </div>
                                            <div class="card-toolbar">
                                                

                                                <div class="d-flex justify-content-end me-2" data-kt-customer-table-toolbar="base">
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_add_club" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-plus"></i>&nbsp; Kulüp Ekle
                                                    </a>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="card-body pt-0">
                                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="konusmaKulupleri">
                                                <thead>
                                                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                                        <th class="min-w-50px">ID</th>
                                                        <th class="min-w-150px">Sınıf</th>
                                                        <th class="min-w-150px">Kulüp Adı(Tr)</th>
                                                        <th class="min-w-150px">Kulüp Adı(En)</th>
                                                        <th class="min-w-125px">Oluşturulma Tarihi</th>
                                                        <th class="min-w-100px">Durum</th>
                                                        <th class="text-end min-w-100px">İşlemler</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="fw-semibold text-gray-600">
                                                <?php
                                                // TABLO İÇERİĞİ PHP DÖNGÜSÜ
                                                if (!empty($contentLists)) {
                                                    foreach ($contentLists as $club) {
                                                        // Sınıf ID'lerinden sınıf adlarını çekme (varsayılan: Bilinmiyor)
                                                        $classNames = $club['class_ids'] ? $contentList->getClassNamesByIds($club['class_ids']) : 'Bilinmiyor';
                                                        
                                                        // Durum etiketi oluşturma
                                                        $statusClass = $club['status'] == 1 ? 'badge-light-success' : 'badge-light-danger';
                                                        $statusText = $club['status'] == 1 ? 'Aktif' : 'Pasif';
                                                ?>
                                                    <tr>
                                                        <td><?php echo $club['id']; ?></td>
                                                        <td><?php echo $classNames; ?></td>
                                                        <td><?php echo htmlspecialchars($club['name_tr']); ?></td>
                                                        <td><?php echo htmlspecialchars($club['name_en']); ?></td>
                                                        <td><?php echo date('d.m.Y H:i', strtotime($club['created_at'])); ?></td>
                                                        <td>
                                                            <div class="badge <?php echo $statusClass; ?> fw-bold"><?php echo $statusText; ?></div>
                                                        </td>
                                                        <td class="text-end">
                                                            <a href="#" class="btn btn-icon btn-active-light-primary w-30px h-30px me-3" data-bs-toggle="modal" data-bs-target="#kt_modal_update_club" 
                                                                data-club-id="<?php echo $club['id']; ?>" 
                                                                data-class-ids="<?php echo $club['class_ids']; ?>" 
                                                                data-name-tr="<?php echo htmlspecialchars($club['name_tr']); ?>" 
                                                                data-name-en="<?php echo htmlspecialchars($club['name_en']); ?>" 
                                                                data-cover-img="<?php echo htmlspecialchars($club['cover_img']); ?>">
                                                                <i class="ki-duotone ki-pencil fs-3">
                                                                    <span class="path1"></span>
                                                                    <span class="path2"></span>
                                                                </i>
                                                            </a>
                                                            <button type="button" class="btn btn-icon btn-active-light-primary w-30px h-30px" onclick="changeClubStatus(<?php echo $club['id']; ?>, <?php echo $club['status']; ?>)">
                                                                <i class="fas <?php echo $club['status'] == 1 ? 'fa-toggle-on text-success' : 'fa-toggle-off text-danger'; ?>"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php
                                                    }
                                                } else {
                                                ?>
                                                    <tr>
                                                        <td colspan="7" class="text-center">Henüz tanımlı bir konuşma kulübü bulunmamaktadır.</td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                                </tbody>
                                            </table>
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

        <div class="modal fade" id="kt_modal_add_club" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <div class="modal-content">
                    <form class="form" action="#" id="kt_modal_add_club_form" enctype="multipart/form-data">
                        <div class="modal-header" id="kt_modal_add_club_header">
                            <h2 class="fw-bold">Kulüp Ekle</h2>
                            <div id="kt_modal_add_club_close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                <i class="ki-duotone ki-cross fs-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div class="modal-body py-10 px-lg-17">
                            
                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">Sınıflar</label>
                                <select name="class_ids[]" id="add_class_ids" class="form-select form-select-solid fw-bold" data-control="select2" data-placeholder="Sınıf Seçiniz (Çoklu Seçim)" multiple="multiple" required>
                                    <?php if (!empty($mainSchoolClasses)): ?>
                                        <?php foreach ($mainSchoolClasses as $schoolClass): ?>
                                            <option value="<?php echo $schoolClass['id']; ?>">
                                                <?php echo htmlspecialchars($schoolClass['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">Kulüp Adı (TR)</label>
                                <input type="text" class="form-control form-control-solid" placeholder="Türkçe Adı" name="name_tr" id="add_name_tr" required />
                            </div>
                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">Kulüp Adı (EN)</label>
                                <input type="text" class="form-control form-control-solid" placeholder="İngilizce Adı" name="name_en" id="add_name_en" required />
                            </div>
                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold mb-2">Kapak Görseli</label>
                                <input type="file" class="form-control form-control-solid" name="cover_img" id="add_cover_img" accept=".png, .jpg, .jpeg" />
                                <div class="form-text">İzin verilen dosya türleri: png, jpg, jpeg.</div>
                            </div>
                        </div>
                        <div class="modal-footer flex-center">
                            <button type="reset" id="kt_modal_add_club_cancel" class="btn btn-light me-3" data-bs-dismiss="modal">İptal</button>
                            <button type="submit" id="kt_modal_add_club_submit" class="btn btn-primary">
                                <span class="indicator-label">Kaydet</span>
                                <span class="indicator-progress">Lütfen bekleyin... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="kt_modal_update_club" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <div class="modal-content">
                    <form class="form" action="#" id="kt_modal_update_club_form" enctype="multipart/form-data">
                        <div class="modal-header" id="kt_modal_update_club_header">
                            <h2 class="fw-bold">Kulüp Güncelle</h2>
                            <div id="kt_modal_update_club_close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                <i class="ki-duotone ki-cross fs-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div class="modal-body py-10 px-lg-17">
                            <input type="hidden" name="club_id" id="update_club_id">
                            <input type="hidden" name="existing_cover_img" id="update_existing_cover_img">
                            
                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">Sınıflar</label>
                                <select name="class_ids[]" id="update_class_ids" class="form-select form-select-solid fw-bold" data-control="select2" data-placeholder="Sınıf Seçiniz (Çoklu Seçim)" multiple="multiple" required>
                                    <?php if (!empty($mainSchoolClasses)): ?>
                                        <?php foreach ($mainSchoolClasses as $schoolClass): ?>
                                            <option value="<?php echo $schoolClass['id']; ?>">
                                                <?php echo htmlspecialchars($schoolClass['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">Kulüp Adı (TR)</label>
                                <input type="text" class="form-control form-control-solid" placeholder="Türkçe Adı" name="name_tr" id="update_name_tr" required />
                            </div>
                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">Kulüp Adı (EN)</label>
                                <input type="text" class="form-control form-control-solid" placeholder="İngilizce Adı" name="name_en" id="update_name_en" required />
                            </div>
                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold mb-2">Kapak Görseli</label>
                                <div id="update_cover_img_preview" class="mb-3"></div>
                                <input type="file" class="form-control form-control-solid" name="cover_img" id="update_cover_img" accept=".png, .jpg, .jpeg" />
                                <div class="form-text">Yeni görsel yüklemek için seçiniz. Mevcut görsel güncellenir.</div>
                            </div>
                        </div>
                        <div class="modal-footer flex-center">
                            <button type="reset" id="kt_modal_update_club_cancel" class="btn btn-light me-3" data-bs-dismiss="modal">İptal</button>
                            <button type="submit" id="kt_modal_update_club_submit" class="btn btn-primary">
                                <span class="indicator-label">Güncelle</span>
                                <span class="indicator-progress">Lütfen bekleyin... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
            var hostUrl = "assets/";
        </script>
        <script src="assets/plugins/global/plugins.bundle.js"></script>
        <script src="assets/js/scripts.bundle.js"></script>
        <script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>

        <script src="assets/js/custom/apps/class/add_week.js"></script>
        <script src="assets/js/widgets.bundle.js"></script>
        <script src="assets/js/custom/widgets.js"></script>
        <script src="assets/js/custom/apps/chat/chat.js"></script>
        <script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
        <script src="assets/js/custom/utilities/modals/create-account.js"></script>
        <script src="assets/js/custom/utilities/modals/create-app.js"></script>
        <script src="assets/js/custom/utilities/modals/users-search.js"></script>
        <script src="assets/js/fatih.js"></script>
        
        <script>
            $(document).ready(function() {
                // Datatable'ı başlatma
                var table = $('#konusmaKulupleri').DataTable({
                    "order": [
                        [0, "desc"]
                    ],
                    "columnDefs": [{
                            "targets": [6],
                            "orderable": false
                        },
                        {
                            "targets": [0],
                            "orderable": true,
                        }
                    ],
                });

                // Arama kutusunu Datatable'a bağlama
                $('[data-kt-customer-table-filter="search"]').on('keyup', function() {
                    table.search(this.value).draw();
                });
            
                // Select2'yi modal açıldığında yeniden başlat (Gerekli olabilir)
                $('#kt_modal_add_club').on('shown.bs.modal', function () {
                    $('#add_class_ids').select2({dropdownParent: $('#kt_modal_add_club')});
                });
                $('#kt_modal_update_club').on('shown.bs.modal', function () {
                    $('#update_class_ids').select2({dropdownParent: $('#kt_modal_update_club')});
                });

                // KULÜP EKLEME FORM GÖNDERİMİ (FormData ve JSON Response İşleme)
                $('#kt_modal_add_club_form').on('submit', function(e) {
                    e.preventDefault();
                    
                    var submitButton = $('#kt_modal_add_club_submit');
                    submitButton.attr('data-kt-indicator', 'on').prop('disabled', true);
                    
                    // FormData nesnesi oluştur ve tüm form verilerini topla (Dosya dahil)
                    var formData = new FormData(this);
                    
                    // Select2 değerlerini al ve ; ile birleştir (10;11;12 formatı)
                    var classIdsArray = $('#add_class_ids').val();
                    var class_ids_post = classIdsArray.join(';');
                    
                    // Form Data'ya class_ids'in son halini ekle/güncelle
                    formData.set('class_ids', class_ids_post); // set() var olanı günceller/yoksa ekler
                    
                    // name="class_ids[]" olan form data girdisini kaldır (Select2'nin default gönderdiği)
                    // FormData ile çalışırken bu gerekli olmayabilir, ancak temizlik için ekleyebiliriz.

                    $.ajax({
                        url: "includes/ajax.php?service=kulupEkle",
                        type: "POST",
                        data: formData,
                        processData: false, // FormData kullanılırken zorunlu
                        contentType: false, // FormData kullanılırken zorunlu
                        dataType: 'json', // Cevabın JSON olacağını belirtiyoruz
                        success: function(response) {
                            submitButton.removeAttr('data-kt-indicator').prop('disabled', false);

                            var iconType = response.status === 'success' ? 'success' : 'error';

                            Swal.fire({
                                text: response.message,
                                icon: iconType,
                                buttonsStyling: false,
                                confirmButtonText: "Tamam",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            }).then(function() {
                                if(response.status === 'success') {
                                    $('#kt_modal_add_club').modal('hide');
                                    location.reload(); 
                                }
                            });
                        },
                        error: function(xhr, status, error) {
                            submitButton.removeAttr('data-kt-indicator').prop('disabled', false);
                            
                            // Hata durumunda bile JSON parse etmeye çalış
                            var errorMessage = "Bir hata oluştu. Sunucu yanıtını kontrol edin.";
                            try {
                                var response = JSON.parse(xhr.responseText);
                                if (response.message) {
                                    errorMessage = response.message;
                                }
                            } catch (e) {
                                console.error("AJAX Error: ", xhr.responseText);
                            }

                            Swal.fire({
                                text: "İşlem sırasında beklenmeyen bir hata oluştu: " + errorMessage,
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Tamam",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            });
                        }
                    });
                });

                // KULÜP GÜNCELLEME MODALI AÇILIRKEN VERİLERİ DOLDURMA
                $('#kt_modal_update_club').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget); 
                    var club_id = button.data('club-id');
                    var class_ids_string = button.data('class-ids'); 
                    var name_tr = button.data('name-tr');
                    var name_en = button.data('name-en');
                    var cover_img_url = button.data('cover-img'); // Mevcut görselin URL'si

                    var modal = $(this);
                    modal.find('#update_club_id').val(club_id);
                    modal.find('#update_name_tr').val(name_tr);
                    modal.find('#update_name_en').val(name_en);
                    modal.find('#update_existing_cover_img').val(cover_img_url); // Mevcut görseli hidden inputa sakla

                    // Mevcut görsel önizlemesi
                    var previewDiv = modal.find('#update_cover_img_preview');
                    previewDiv.empty();
                    if (cover_img_url) {
                        previewDiv.append('<img src="' + cover_img_url + '" class="img-fluid rounded mb-2" style="max-height: 100px;" alt="Mevcut Görsel" />');
                    }
                    
                    // Sınıf ID'lerini ayır ve Select2'de seçili yap
                    var selectedClasses = class_ids_string ? class_ids_string.toString().split(';') : [];
                    $('#update_class_ids').val(selectedClasses).trigger('change'); 
                });

                // KULÜP GÜNCELLEME FORM GÖNDERİMİ (FormData ve JSON Response İşleme)
                $('#kt_modal_update_club_form').on('submit', function(e) {
                    e.preventDefault();
                    
                    var submitButton = $('#kt_modal_update_club_submit');
                    submitButton.attr('data-kt-indicator', 'on').prop('disabled', true);

                    // FormData nesnesi oluştur
                    var formData = new FormData(this);
                    
                    // Select2 değerlerini al ve ; ile birleştir (10;11;12 formatı)
                    var classIdsArray = $('#update_class_ids').val();
                    var class_ids_post = classIdsArray.join(';');

                    // Form Data'ya class_ids'in son halini ekle/güncelle
                    formData.set('class_ids', class_ids_post);

                    $.ajax({
                        url: "includes/ajax.php?service=kulupGuncelle",
                        type: "POST",
                        data: formData,
                        processData: false, // FormData kullanılırken zorunlu
                        contentType: false, // FormData kullanılırken zorunlu
                        dataType: 'json', 
                        success: function(response) {
                            submitButton.removeAttr('data-kt-indicator').prop('disabled', false);

                            var iconType = response.status === 'success' ? 'success' : 'error';
                            
                            Swal.fire({
                                text: response.message,
                                icon: iconType,
                                buttonsStyling: false,
                                confirmButtonText: "Tamam",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            }).then(function() {
                                if(response.status === 'success') {
                                    $('#kt_modal_update_club').modal('hide');
                                    location.reload(); 
                                }
                            });
                        },
                        error: function(xhr, status, error) {
                            submitButton.removeAttr('data-kt-indicator').prop('disabled', false);
                            
                            var errorMessage = "Bir hata oluştu. Sunucu yanıtını kontrol edin.";
                            try {
                                var response = JSON.parse(xhr.responseText);
                                if (response.message) {
                                    errorMessage = response.message;
                                }
                            } catch (e) {
                                console.error("AJAX Error: ", xhr.responseText);
                            }

                            Swal.fire({
                                text: "İşlem sırasında beklenmeyen bir hata oluştu: " + errorMessage,
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Tamam",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            });
                        }
                    });
                });

                // KULÜP DURUM DEĞİŞTİRME FONKSİYONU (JSON Response İşleme)
                window.changeClubStatus = function(clubId, currentStatus) {
                    var newStatus = currentStatus == 1 ? 0 : 1;
                    var statusText = newStatus == 1 ? 'Aktif' : 'Pasif';

                    Swal.fire({
                        text: clubId + " ID'li kulübü " + statusText + " yapmak istediğinizden emin misiniz?",
                        icon: "warning",
                        showCancelButton: true,
                        buttonsStyling: false,
                        confirmButtonText: "Evet, Değiştir!",
                        cancelButtonText: "Hayır",
                        customClass: {
                            confirmButton: "btn btn-danger",
                            cancelButton: "btn btn-light"
                        }
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "includes/ajax.php?service=kulupStatusChange",
                                type: "POST",
                                data: { id: clubId, status: newStatus },
                                dataType: 'json', 
                                success: function(response) {
                                    var iconType = response.status === 'success' ? 'success' : 'error';
                                    
                                    Swal.fire({
                                        text: response.message,
                                        icon: iconType,
                                        buttonsStyling: false,
                                        confirmButtonText: "Tamam",
                                        customClass: {
                                            confirmButton: "btn btn-primary"
                                        }
                                    }).then(function() {
                                        if(response.status === 'success') {
                                            location.reload(); 
                                        }
                                    });
                                },
                                error: function(xhr, status, error) {
                                    var errorMessage = "Bir hata oluştu. Sunucu yanıtını kontrol edin.";
                                    // ... (hata mesajı yakalama)

                                    Swal.fire({
                                        text: "Durum değiştirilirken bir hata oluştu.",
                                        icon: "error",
                                        buttonsStyling: false,
                                        confirmButtonText: "Tamam",
                                        customClass: {
                                            confirmButton: "btn btn-primary"
                                        }
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