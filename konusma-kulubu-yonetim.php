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
    $class = new Classes();
    $contentLists = $class->getKulupList(); // İçerik listesini çekiyoruz

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
                                                            $classNames = $club['class_ids'] ? $class->getClassNamesByIds($club['class_ids']) : 'Bilinmiyor';

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

        <script>
            var hostUrl = "assets/";
        </script>
        <script src="assets/plugins/global/plugins.bundle.js"></script>
        <script src="assets/js/scripts.bundle.js"></script>
        <script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>



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
                // changeClubStatus fonksiyonunun bir örneği



            });

            function changeClubStatus(clubId, currentStatus) {
    const newStatus = currentStatus === 1 ? 0 : 1;
    const statusText = newStatus === 1 ? 'Aktif' : 'Pasif';
    const confirmText = `Kulüp ID: ${clubId} durumunu <b>${statusText}</b> yapmak istediğinize emin misiniz?`;

    Swal.fire({
        title: 'Durum Değişikliği Onayı',
        html: confirmText,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Evet, değiştir',
        cancelButtonText: 'Vazgeç',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // AJAX isteği
            $.ajax({
                url: './includes/ajax.php?service=kulupStatusChange',
                type: 'POST',
                data: {
                    club_id: clubId,
                    new_status: newStatus
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status=='success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Başarılı!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Hata!',
                            text: response.message
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Sunucu Hatası',
                        text: 'Durum değişikliği sırasında bir hata oluştu.'
                    });
                }
            });
        }
    });
}

        </script>

    </body>

</html>
<?php } else {
    header("location: index");
}
?>