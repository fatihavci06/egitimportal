<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);

// GEREKLİ SINIFLAR VE BAĞLANTILAR
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 10001)) {
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";

    // ÖNEMLİ: getAgeGroup() fonksiyonunun $mainSchoolClasses değişkenini doldurması beklenir.
    include_once "views/pages-head.php";
    $contentList = new Classes();
    $contentLists = $contentList->clubContentList(); // İçerik listesini çekiyoruz
    $mainSchoolClasses = $contentList->getAgeGroup(); 

    // Yaş Grubu isimlerini çekmek için yardımcı dizin oluştur (Performans için)
    $classNamesMap = [];
    if (is_array($mainSchoolClasses)) {
        foreach ($mainSchoolClasses as $class) {
            $classNamesMap[$class['id']] = $class['name'];
        }
    }
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
                                                    <a href="konusma-kulubu-canli-ders-ekle.php" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-plus"></i>&nbsp; Canlı Ders Ekle
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0">
                                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="zoomListesi">
                                                <thead>
                                                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                                        <th class="min-w-50px">ID</th>
                                                        <th class="min-w-150px">Sınıf</th>
                                                        <th class="min-w-150px">Kulüp Adı(Tr)</th>
                                                        <th class="min-w-150px">Kulüp Adı(En)</th>
                                                        <th class="min-w-125px">Konu</th>
                                                        <th class="min-w-100px">Zoom Tarihi</th>
                                                        <th class="min-w-100px">Zoom Başlat</th> <th class="min-w-50px">Durum</th>
                                                        <th class="text-end min-w-100px">Eylem</th> </tr>
                                                </thead>
                                                <tbody class="fw-semibold text-gray-600">
                                                <?php
                                                if (!empty($contentLists) && is_array($contentLists)) {
                                                    foreach ($contentLists as $content) {
                                                        // Yaş Grubu ID'lerini isimlere dönüştürme
                                                        $class_ids_array = explode(';', $content['class_ids']);
                                                        $class_names = [];
                                                        foreach ($class_ids_array as $class_id) {
                                                            if (isset($classNamesMap[$class_id])) {
                                                                $class_names[] = '<span class="badge badge-light-primary fw-bold me-1">' . htmlspecialchars($classNamesMap[$class_id]) . '</span>';
                                                            }
                                                        }
                                                        $class_names_html = implode('', $class_names);

                                                        // Zoom Tarihi ve Saati Birleştirme ve Formatlama
                                                        $zoomDateTime = $content['zoom_date'] ? date('d.m.Y', strtotime($content['zoom_date'])) . ' ' . date('H:i', strtotime($content['zoom_time'])) : 'N/A';

                                                        $contentId = $content['id'] ?? 'N/A'; 
                                                        $currentStatus = $content['status'] ?? 0;
                                                        $zoomStartUrl = htmlspecialchars($content['zoom_start_url'] ?? '');
                                                ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($contentId) ?></td>
                                                        <td><?= $class_names_html ?></td>
                                                        <td><?= htmlspecialchars($content['name_tr'] ?? 'N/A') ?></td>
                                                        <td><?= htmlspecialchars($content['name_en'] ?? 'N/A') ?></td>
                                                        <td><?= htmlspecialchars($content['title'] ?? 'N/A') ?></td>
                                                        <td><?= $zoomDateTime ?></td>
                                                        
                                                        <td>
                                                            <?php if (!empty($zoomStartUrl)) { ?>
                                                                <a href="<?= $zoomStartUrl ?>" target="_blank" class="btn btn-sm btn-light-success btn-icon" title="Zoom Toplantısını Başlat">
                                                                    <i class="fas fa-play"></i>
                                                                </a>
                                                            <?php } else { ?>
                                                                <span class="badge badge-light-danger">URL Yok</span>
                                                            <?php } ?>
                                                        </td>
                                                        
                                                        <td>
                                                            <div class="form-check form-switch form-check-custom form-check-solid">
                                                                <input class="form-check-input h-20px w-30px" type="checkbox" id="status_<?= $contentId ?>" 
                                                                    data-content-id="<?= $contentId ?>" data-current-status="<?= $currentStatus ?>"
                                                                    onchange="changeClubContentStatus(this)"
                                                                    <?= ($currentStatus == 1) ? 'checked' : '' ?> />
                                                                <label class="form-check-label ms-3" for="status_<?= $contentId ?>">
                                                                    <span class="badge badge-light-<?= ($currentStatus == 1) ? 'success' : 'danger' ?> fw-bold status-label">
                                                                        <?= ($currentStatus == 1) ? 'Aktif' : 'Pasif' ?>
                                                                    </span>
                                                                </label>
                                                            </div>
                                                        </td>
                                                        
                                                        <td class="text-end">
                                                            <a href="konusma-kulubu-canli-ders-guncelle.php?id=<?= $contentId ?>" class="btn btn-icon btn-sm btn-light-primary me-2" title="Düzenle">
                                                                <i class="ki-duotone ki-pencil fs-4">
                                                                    <span class="path1"></span>
                                                                    <span class="path2"></span>
                                                                </i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php } 
                                                } else { ?>
                                                    <tr>
                                                        <td colspan="9" class="text-center">Kayıtlı canlı ders içeriği bulunmamaktadır.</td>
                                                    </tr>
                                                <?php } ?>
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


        <script>
            var hostUrl = "assets/";
        </script>
        <script src="assets/plugins/global/plugins.bundle.js"></script>
        <script src="assets/js/scripts.bundle.js"></script>
        <script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>

        
        <script>
            $(document).ready(function() {
                // Datatable'ı başlatma
                var table = $('#zoomListesi').DataTable({
                    "order": [
                        [0, "desc"]
                    ],
                    "columnDefs": [{
                            "targets": [7, 8], // Zoom Başlat, Durum ve Eylem kolonları
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
            
                // KULÜP DURUM DEĞİŞTİRME FONKSİYONU (Toggle Butonuna özel)
                window.changeClubContentStatus = function(checkbox) {
                    var contentId = $(checkbox).data('content-id');
                    var currentStatus = $(checkbox).data('current-status');
                    var newStatus = checkbox.checked ? 1 : 0;
                    var actionText = newStatus == 1 ? 'Aktif Yapmak' : 'Pasif Yapmak';
                    
                    // Görsel Durum Etiketini Bul
                    var $statusLabel = $(checkbox).closest('td').find('.status-label');

                    Swal.fire({
                        text: contentId + " ID'li canlı ders içeriğini " + actionText + " istediğinizden emin misiniz?",
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
                            // AJAX isteği ile durumu değiştirme
                            $.ajax({
                                url: "includes/ajax.php?service=clubContentStatusChange", // İstenen servis adı
                                type: "POST",
                                data: { id: contentId, status: newStatus },
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
                                            // Başarılıysa durumu güncelle ve etiketi değiştir
                                            $(checkbox).data('current-status', newStatus);
                                            $statusLabel.text(newStatus == 1 ? 'Aktif' : 'Pasif');
                                            $statusLabel.removeClass('badge-light-success badge-light-danger').addClass(newStatus == 1 ? 'badge-light-success' : 'badge-light-danger');
                                        } else {
                                            // Hata varsa checkbox'ı eski durumuna döndür
                                            checkbox.checked = (currentStatus == 1);
                                        }
                                    });
                                },
                                error: function(xhr, status, error) {
                                    // Hata durumunda checkbox'ı eski durumuna döndür
                                    checkbox.checked = (currentStatus == 1); 
                                    
                                    var errorMessage = "Durum değiştirilirken bir hata oluştu.";
                                    try {
                                        var response = JSON.parse(xhr.responseText);
                                        if (response.message) {
                                            errorMessage = response.message;
                                        }
                                    } catch (e) {
                                        console.error("AJAX Error: ", xhr.responseText);
                                    }

                                    Swal.fire({
                                        text: "Durum değiştirilirken bir hata oluştu: " + errorMessage,
                                        icon: "error",
                                        buttonsStyling: false,
                                        confirmButtonText: "Tamam",
                                        customClass: {
                                            confirmButton: "btn btn-primary"
                                        }
                                    });
                                }
                            });
                        } else {
                            // Kullanıcı işlemi iptal ettiyse checkbox'ı eski durumuna döndür
                            checkbox.checked = (currentStatus == 1);
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