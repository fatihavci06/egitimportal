<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);

// Kullanıcının rolünü kontrol et. Sadece admin (rol 1) erişebilir.
if (isset($_SESSION['role']) && ($_SESSION['role'] == 1)) {
    // Veritabanı bağlantı sınıfınızı ve diğer sınıflarınızı dahil edin
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";

    // Sayfa başlığını (head) dahil edin
    include_once "views/pages-head.php";

    // Classes sınıfının bir örneğini oluşturun
    $class = new Classes();
    // Gelişim paketleri listesini çekin. Bu metodun artık 'description' alanını da döndürdüğünü varsayıyoruz.
    $data = $class->getDevelopmentPackageList();
?>
    <body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" data-kt-app-aside-enabled="true" data-kt-app-aside-fixed="true" data-kt-app-aside-push-toolbar="true" data-kt-app-aside-push-footer="true" class="app-default">
        <script>
            // Temanın varsayılan modunu ayarlar (Kinetik UI kütüphanesi için)
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
                <?php include_once "views/header.php"; // Üst menü ?>
                <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                    <?php include_once "views/sidebar.php"; // Yan menü ?>
                    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                        <div class="d-flex flex-column flex-column-fluid">
                            <?php include_once "views/toolbar.php"; // Araç çubuğu (başlık vb.) ?>
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
                                                <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                                                    <button type="button" class="btn btn-primary btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#addPackageModal">
                                                        Gelişim Paketi Ekle
                                                    </button>
                                                </div>
                                                <div class="d-flex justify-content-end align-items-center d-none" data-kt-customer-table-toolbar="selected">
                                                    <div class="fw-bold me-5">
                                                        <span class="me-2" data-kt-customer-table-select="selected_count"></span>Seçildi
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0">
                                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table">
                                                <thead>
                                                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                                        <th class="w-10px pe-2">
                                                            <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                                <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_customers_table .form-check-input" />
                                                            </div>
                                                        </th>
                                                        <th class="min-w-125px">Paket Adı</th>
                                                        <th class="min-w-125px">Fiyat</th>
                                                        <th class="min-w-125px">Açıklama</th> <th class="text-end min-w-90px">İşlemler</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="fw-semibold text-gray-600">
                                                    <?php foreach ($data as $d) { ?>
                                                        <tr data-id="<?= $d['id'] ?>" data-name="<?= $d['name'] ?>" data-price="<?= $d['price'] ?>" data-description="<?= htmlspecialchars($d['description'] ?? '') ?>">
                                                            <td>
                                                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                                    <input class="form-check-input" type="checkbox" value="1" />
                                                                </div>
                                                            </td>
                                                            <td><?= $d['name'] ?></td>
                                                            <td><?= $d['price'] ?></td>
                                                            <td><?= $d['description'] ?? 'Yok' ?></td> <td class="text-end">
                                                                <button class="btn btn-primary btn-sm me-1 update-btn" data-id="<?= $d['id'] ?>">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                                <button class="btn btn-danger btn-sm delete-btn" data-id="<?= $d['id'] ?>">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>

                                            <div class="modal fade" id="addPackageModal" tabindex="-1" aria-labelledby="addPackageModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <form id="addPackageForm">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="addPackageModalLabel">Gelişim Paketi Ekle</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="addPackageName" class="form-label required">Paket Adı</label>
                                                                    <input type="text" id="addPackageName" name="addPackageName" class="form-control" required />
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="addPackagePrice" class="form-label required">Paket Fiyatı</label>
                                                                    <input type="number" inputmode="decimal" step="0.01" id="addPackagePrice" name="addPackagePrice" class="form-control" required />
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="addPackageDescription" class="form-label">Paket Açıklaması</label>
                                                                    <textarea id="addPackageDescription" name="addPackageDescription" class="form-control" rows="3"></textarea>
                                                                </div>
                                                                </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                                                                <button type="submit" class="btn btn-primary">Kaydet</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                            <div class="modal fade" id="updatePackageModal" tabindex="-1" aria-labelledby="updatePackageModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <form id="updatePackageForm">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="updatePackageModalLabel">Gelişim Paketi Güncelle</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <input type="hidden" id="updatePackageId" name="updatePackageId" />
                                                                <div class="mb-3">
                                                                    <label for="updatePackageName" class="form-label required">Paket Adı</label>
                                                                    <input type="text" id="updatePackageName" name="updatePackageName" class="form-control" required />
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="updatePackagePrice" class="form-label">Fiyat</label>
                                                                    <input type="number" step="0.01" id="updatePackagePrice" name="updatePackagePrice" class="form-control" required />
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="updatePackageDescription" class="form-label">Paket Açıklaması</label>
                                                                    <textarea id="updatePackageDescription" name="updatePackageDescription" class="form-control" rows="3"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                                                                <button type="submit" class="btn btn-primary">Güncelle</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php include_once "views/footer.php"; // Alt bilgi (footer) ?>
                    </div>
                    <?php include_once "views/aside.php"; // Sağ kenar çubuğu (aside) ?>
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
        <script src="assets/js/widgets.bundle.js"></script>
        <script src="assets/js/custom/widgets.js"></script>

        <script>
            $(document).ready(function() {
                // DataTable başlat
                const table = $('#kt_customers_table').DataTable();

                // Arama kutusunu bağla
                $('[data-kt-customer-table-filter="search"]').on('keyup', function() {
                    table.search(this.value).draw();
                });

                // Add Package form submit (Ajax)
                $('#addPackageForm').on('submit', function(e) {
                    e.preventDefault();

                    const formData = $(this).serialize();

                    $.ajax({
                        url: 'includes/ajax.php?service=addDevelopmentPackage', // Backend PHP dosyanızın yolu
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Başarılı!',
                                    text: 'Yeni gelişim paketi başarıyla eklendi.',
                                    confirmButtonText: 'Tamam'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        $('#addPackageModal').modal('hide');
                                        $('#addPackageForm')[0].reset();
                                        location.href = window.location.href; // Sayfayı yenile
                                    }
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Hata!',
                                    text: response.message || 'Bir sorun oluştu. Lütfen tekrar deneyin.',
                                    confirmButtonText: 'Tamam'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Sunucu Hatası!',
                                text: 'İstek gönderilirken bir hata oluştu.',
                                confirmButtonText: 'Tamam'
                            });
                        }
                    });
                });

                // Güncelleme butonuna tıklanınca modalı aç ve verileri doldur
                $('.update-btn').on('click', function() {
                    const tr = $(this).closest('tr');
                    const id = tr.data('id');
                    const name = tr.data('name');
                    const price = tr.data('price');
                    const description = tr.data('description'); // description verisini al

                    $('#updatePackageId').val(id);
                    $('#updatePackageName').val(name);
                    $('#updatePackagePrice').val(price);
                    $('#updatePackageDescription').val(description); // description alanını doldur

                    $('#updatePackageModal').modal('show');
                });

                // Güncelleme form submit (Ajax)
                $('#updatePackageForm').on('submit', function(e) {
                    e.preventDefault();

                    const formData = $(this).serialize();

                    $.ajax({
                        url: 'includes/ajax.php?service=updateDevelopmentPackage', // Backend PHP dosyanızın yolu
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Başarılı!',
                                    text: 'Gelişim paketi başarıyla güncellendi.',
                                    confirmButtonText: 'Tamam'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        $('#updatePackageModal').modal('hide');
                                        location.href = window.location.href; // Sayfayı yenile
                                    }
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Hata!',
                                    text: response.message || 'Paket güncellenemedi. Lütfen tekrar deneyin.',
                                    confirmButtonText: 'Tamam'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Sunucu Hatası!',
                                text: 'İstek gönderilirken bir hata oluştu.',
                                confirmButtonText: 'Tamam'
                            });
                        }
                    });
                });

                // Silme işlemi
                $('.delete-btn').on('click', function() {
                    const id = $(this).data('id');

                    Swal.fire({
                        title: 'Emin misiniz?',
                        text: 'Bu paket kalıcı olarak silinecek!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Evet, sil',
                        cancelButtonText: 'İptal',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: 'includes/ajax.php?service=deleteDevelopmentPackage', // Backend PHP dosyanızın yolu
                                type: 'POST',
                                data: {
                                    id: id
                                },
                                dataType: 'json',
                                success: function(response) {
                                    if (response.status === 'success') {
                                        Swal.fire('Silindi!', 'Gelişim paketi başarıyla silindi.', 'success');
                                        $(`tr[data-id="${id}"]`).remove();
                                        location.href = window.location.href; // Sayfayı yenile
                                    } else {
                                        Swal.fire('Hata!', response.message || 'Paket silinemedi.', 'error');
                                    }
                                },
                                error: function() {
                                    Swal.fire('Sunucu Hatası!', 'Bir hata oluştu.', 'error');
                                }
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
    // Rol yetersizse anasayfaya yönlendir
    header("location: index");
    exit(); // Yönlendirme sonrası scriptin çalışmasını durdur
}
?>