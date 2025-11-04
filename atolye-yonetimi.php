<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);

// GEREKLİ SINIFLAR VE BAĞLANTILAR
// NOT: Bu kod bloğunun çalışması için Classes.classes.php dosyanızda
// getAtolyeContentList() (içinde 'status' sütunu olmalı) ve getClassNamesByIds() fonksiyonlarının tanımlı olması gerekir.
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 10001)) {
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";

    include_once "views/pages-head.php";
    $contentList = new Classes();
    $contentLists = $contentList->getAtolyeContentList(); // İçerik listesini çekiyoruz
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
                                                    <input type="text" data-kt-customer-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="İçerik Ara" />
                                                </div>
                                            </div>
                                            <div class="card-toolbar">
                                                <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                                                    <a href="atolye-icerik-ekle" class="btn btn-primary btn-sm">İçerik Ekle</a>
                                                </div>
                                                <div class="d-flex justify-content-end align-items-center d-none" data-kt-customer-table-toolbar="selected">
                                                    <div class="fw-bold me-5">
                                                        <span class="me-2" data-kt-customer-table-select="selected_count"></span>Seçildi
                                                    </div>
                                                    <button type="button" class="btn btn-danger btn-sm" data-kt-customer-table-select="delete_selected">Seçilenleri Pasif Yap</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0">
                                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="atolyeIcerikleri">
                                                <thead>
                                                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                                        <th class="min-w-50px">ID</th>
                                                        <th class="min-w-150px">Yaş Grubu</th>
                                                        <th class="min-w-150px">Atölye Türü</th>
                                                        <th class="min-w-150px">İçerik Tipi</th>
                                                        <th class="min-w-200px">Başlık</th>
                                                        <th class="min-w-125px">Oluşturulma Tarihi</th>
                                                        <th class="min-w-100px">Durum</th>
                                                        <th class="text-end min-w-70px">İşlemler</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="fw-semibold text-gray-600">
                                                    <?php
                                                    // Verileri tabloya döngü ile basıyoruz
                                                    if (!empty($contentLists)) {
                                                        foreach ($contentLists as $content) {

                                                            // Yaş Gruplarını isimlere çevirme
                                                            $classNames = $contentList->getClassNamesByIds($content['class_ids']);

                                                            // İçerik Tipi (secim_type) değerlerini daha okunaklı hale getirme
                                                            $secimTypeMap = [
                                                                'video_link' => 'Video (URL)',
                                                                'file_path'  => 'Dosya / Görsel',
                                                                'content'    => 'Metin İçerik',
                                                                'wordwall'   => 'İnteraktif Oyun'
                                                            ];
                                                            $displaySecimType = $secimTypeMap[$content['secim_type']] ?? $content['secim_type'];

                                                            // Durum Ayarı (status: 1 = Aktif, 0 = Pasif)
                                                            $is_active = $content['status'] == 1;
                                                            $status_btn_class = $is_active ? ' btn-sm btn-active-color-danger' : 'btn-active-color-success';
                                                            $status_icon = $is_active ? 'ki-trash' : 'ki-check-circle';
                                                            $status_title = $is_active ? 'Pasif Yap' : 'Aktif Yap';
                                                            $new_status = $is_active ? 0 : 1;

                                                            // Durum Rozeti (Badge) için
                                                            $status_badge_class = $is_active ? 'badge-light-success' : 'badge-light-danger';
                                                            $status_text = $is_active ? 'Aktif' : 'Pasif';

                                                            // Tarihi düzenleme
                                                            $createdAt = (new DateTime($content['created_at']))->format('d.m.Y H:i');

                                                    ?>
                                                            <tr>
                                                                <td><?= $content['id'] ?></td>
                                                                <td data-filter="<?= htmlspecialchars($classNames) ?>"><?= htmlspecialchars($classNames) ?></td>
                                                                <td><?= htmlspecialchars($content['content_type']) ?></td>
                                                                <td><span class="badge badge-light-primary fw-bold"><?= $displaySecimType ?></span></td>
                                                                <td><?= htmlspecialchars($content['subject']) ?></td>
                                                                <td><?= $createdAt ?></td>

                                                                <td><span class="badge <?= $status_badge_class ?> fw-bold" data-filter="<?= $status_text ?>"><?= $status_text ?></span></td>

                                                                <td class="text-end">
                                                                    <a href="atolye-icerik-guncelle?id=<?= $content['id'] ?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-3" title="Düzenle">
                                                                        <i class="ki-duotone ki-pencil fs-2">
                                                                            <span class="path1"></span>
                                                                            <span class="path2"></span>
                                                                        </i>
                                                                    </a>

                                                                    <?php
                                                                    // Durum Ayarı (1=Aktif, 0=Pasif)
                                                                    $is_active = $content['status'] == 1;

                                                                    // Aktifse (Durumu 1 ise) Pasif Yap butonu gösterilir (Kırmızı renk)
                                                                    $button_class = $is_active ? 'btn-danger' : 'btn-success';
                                                                    $button_text = $is_active ? 'Pasif Yap' : 'Aktif Yap';
                                                                    $new_status = $is_active ? 0 : 1;
                                                                    ?>
                                                                    <button type="button"
                                                                        class="btn btn-icon btn-bg-light btn-active-color-primary toggle-content-status"
                                                                        data-id="<?= $content['id'] ?>"
                                                                        data-status="<?= $content['status'] ?>"
                                                                        data-new-status="<?= $new_status ?>"
                                                                        title="<?= $button_text ?>">
                                                                        <?php if ($is_active): ?>
                                                                            <i class="fas fa-toggle-on text-success fs-3"></i>
                                                                        <?php else: ?>
                                                                            <i class="fas fa-toggle-off text-muted fs-3"></i>
                                                                        <?php endif; ?>
                                                                    </button>

                                                                </td>
                                                            </tr>
                                                        <?php }
                                                    } else { ?>
                                                        <tr>
                                                            <td colspan="8" class="text-center text-muted">Henüz hiç atölye içeriği bulunmamaktadır.</td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <?php include_once "views/classes/add_important_week-view.classes.php" ?>
                                    <div class="modal fade" id="kt_customers_export_modal" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered mw-650px">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h2 class="fw-bold">Export Customers</h2>
                                                    <div id="kt_customers_export_close" class="btn btn-icon btn-sm btn-active-icon-primary">
                                                        <i class="ki-duotone ki-cross fs-1">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>
                                                    </div>
                                                </div>
                                            </div>
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
                // Datatable'ı başlatma (Bu kısmı olduğu gibi bırakıyoruz)
                var table = $('#atolyeIcerikleri').DataTable({
                    "order": [
                        [0, "desc"]
                    ],
                    "columnDefs": [{
                            "targets": [7],
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
                $('#atolyeIcerikleri').on('click', '.toggle-content-status', function(e) {
                    e.preventDefault();

                    let btn = $(this);
                    let contentId = btn.data('id');
                    let currentStatus = btn.data('status');
                    let newStatus = btn.data('new-status');

                    // Swal onay penceresi
                    Swal.fire({
                        title: newStatus == 1 ? 'İçeriği Aktif Et' : 'İçeriği Pasif Et',
                        text: newStatus == 1 ?
                            "Bu içeriği aktif hale getirmek istediğinize emin misiniz?" :
                            "Bu içeriği pasif hale getirmek istediğinize emin misiniz?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: newStatus == 1 ? 'Evet, Aktif Et' : 'Evet, Pasif Et',
                        cancelButtonText: 'İptal',
                        reverseButtons: true,
                        customClass: {
                            confirmButton: newStatus == 1 ? 'btn btn-success' : 'btn btn-danger',
                            cancelButton: 'btn btn-light'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            btn.prop('disabled', true).attr('data-kt-indicator', 'on');

                            $.ajax({
                                type: "POST",
                                url: "./includes/ajax.php?service=atolyeContentStatusChange",
                                data: {
                                    id: contentId,
                                    status: newStatus
                                },
                                dataType: "json",
                                success: function(response) {
                                    if (response.status === 'success') {
                                        Swal.fire({
                                            title: 'Başarılı!',
                                            text: response.message || 'Durum başarıyla güncellendi.',
                                            icon: 'success',
                                            timer: 1500,
                                            showConfirmButton: false
                                        });

                                        // 🔄 İkon ve durum rozetini anında güncelle (sayfa yenilemeden)
                                        const icon = btn.find('i');
                                        const statusBadge = btn.closest('tr').find('td span.badge');

                                        if (newStatus == 1) {
                                            icon.removeClass('fa-toggle-off text-muted').addClass('fa-toggle-on text-success');
                                            btn.data('status', 1).data('new-status', 0).attr('title', 'Pasif Yap');
                                            statusBadge.removeClass('badge-light-danger').addClass('badge-light-success').text('Aktif');
                                        } else {
                                            icon.removeClass('fa-toggle-on text-success').addClass('fa-toggle-off text-muted');
                                            btn.data('status', 0).data('new-status', 1).attr('title', 'Aktif Yap');
                                            statusBadge.removeClass('badge-light-success').addClass('badge-light-danger').text('Pasif');
                                        }
                                    } else {
                                        Swal.fire({
                                            title: 'Hata!',
                                            text: response.message || 'Bir hata oluştu, lütfen tekrar deneyin.',
                                            icon: 'error',
                                            confirmButtonText: 'Tamam',
                                            customClass: {
                                                confirmButton: 'btn btn-primary'
                                            },
                                            buttonsStyling: false
                                        });
                                    }
                                },
                                error: function() {
                                    Swal.fire({
                                        title: 'Hata!',
                                        text: 'Sunucu ile bağlantı kurulamadı. Lütfen tekrar deneyin.',
                                        icon: 'error',
                                        confirmButtonText: 'Tamam',
                                        customClass: {
                                            confirmButton: 'btn btn-primary'
                                        },
                                        buttonsStyling: false
                                    });
                                },
                                complete: function() {
                                    btn.prop('disabled', false).attr('data-kt-indicator', 'off');
                                }
                            });
                        }
                    });
                });


            });
        </script>

    </body>

</html>
<?php } else {
    header("location: index");
}
?>