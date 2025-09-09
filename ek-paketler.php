<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 10001)) {
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";

    include_once "views/pages-head.php";
    $class = new Classes();
    $data = $class->getExtraPackageList();
include_once "classes/packages.classes.php";
$vat=new Packages();
$tax=$vat->getVat();
$taxRate=$tax['tax_rate'];
$taxRate= $taxRate / 100 + 1;

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
                                                    <input type="text" data-kt-customer-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder=" Ara" />
                                                </div>
                                            </div>
                                            <div class="card-toolbar">
                                                <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                                                    <button type="button" class="btn btn-primary btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#addPackageModal">
                                                        Ek Paket Ekle
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
                                                        <th class="min-w-125px">Ek Paket</th>
                                                        <th class="min-w-125px">Tip</th>
                                                        <th class="min-w-125px">Aylık / Adet</th>
                                                        <th class="min-w-125px">Fiyat</th>
                                                        <th class="min-w-200px">Açıklama</th>
                                                        <th class="text-end min-w-90px">İşlemler</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="fw-semibold text-gray-600">
                                                    <?php foreach ($data as $d) { ?>
                                                        <tr data-id="<?= $d['id'] ?>" data-name="<?= $d['name'] ?>" data-type="<?= $d['type'] ?>" data-price="<?= $d['price'] ?>" data-months="<?= $d['limit_count'] ?>" data-count="<?= $d['limit_count'] ?>" data-description="<?= htmlspecialchars($d['description']) ?>">

                                                            <td>
                                                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                                    <input class="form-check-input" type="checkbox" value="1" />
                                                                </div>
                                                            </td>
                                                            <td><?= $d['name'] ?></td>
                                                            <td><?= $d['type'] ?></td>
                                                            <td><?= $d['limit_count'] ?></td>
                                                            <td><?= $d['price'] ?></td>
                                                            <td><?= $d['description'] ?></td>
                                                            <td class="text-end">
                                                                <button class="btn  btn-primary btn-sm me-1 update-btn" data-id="<?= $d['id'] ?>">
                                                                    <i class="fas fa-edit"></i> </button>
                                                                <button class="btn   btn-danger btn-sm delete-btn" data-id="<?= $d['id'] ?>">
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
                                                                <h5 class="modal-title" id="addPackageModalLabel">Ek Paket Ekle</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="addPackageName" class="form-label required">Paket Adı</label>
                                                                    <input type="text" id="addPackageName" name="addPackageName" class="form-control" required />
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="addPackagePrice" class="form-label required">Paket Fiyatı (KDV Hariç)</label>
                                                                    <input type="number" inputmode="decimal" step="0.01" id="addPackagePrice" name="addPackagePrice" class="form-control" required />
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="addPackagePriceWithTax" class="form-label required">Paket Fiyatı (KDV Dahil)</label>
                                                                    <input type="number" inputmode="decimal" step="0.01" id="addPackagePriceWithTax" name="addPackagePriceWithTax" class="form-control" required />
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="addPackageType" class="form-label required">Tip</label>
                                                                    <select id="addPackageType" name="addPackageType" class="form-select" required>
                                                                        <option value="">Seçiniz</option>
                                                                        <option value="Koçluk">Koçluk</option>
                                                                        <option value="Rehberlik">Rehberlik</option>
                                                                        <option value="Özel Ders">Özel Ders</option>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3" id="addMonthsWrapper" style="display:none;">
                                                                    <label for="addMonths" class="form-label required">Kaç Aylık</label>
                                                                    <input type="number" id="addMonths" name="addMonths" min="1" class="form-control" />
                                                                </div>
                                                                <div class="mb-3" id="addCountWrapper" style="display:none;">
                                                                    <label for="addCount" class="form-label required">Adet</label>
                                                                    <input type="number" id="addCount" name="addCount" min="1" class="form-control" />
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="addPackageDescription" class="form-label">Açıklama</label>
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
                                                                <h5 class="modal-title" id="updatePackageModalLabel">Ek Paket Güncelle</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <input type="hidden" id="updatePackageId" name="updatePackageId" />

                                                                <div class="mb-3">
                                                                    <label for="updatePackageName" class="form-label required">Paket Adı</label>
                                                                    <input type="text" id="updatePackageName" name="updatePackageName" class="form-control" required />
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="updatePackagePrice" class="form-label required">Fiyat (KDV Hariç)</label>
                                                                    <input type="number" step="0.01" id="updatePackagePrice" name="updatePackagePrice" class="form-control" required />
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="updatePackagePriceWithTax" class="form-label required">Fiyat (KDV Dahil)</label>
                                                                    <input type="number" step="0.01" id="updatePackagePriceWithTax" name="updatePackagePriceWithTax" class="form-control" required  />
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="updatePackageType" class="form-label required">Tip</label>
                                                                    <select id="updatePackageType" name="updatePackageType" class="form-select" required>
                                                                        <option value="">Seçiniz</option>
                                                                        <option value="Koçluk">Koçluk</option>
                                                                        <option value="Rehberlik">Rehberlik</option>
                                                                        <option value="Özel Ders">Özel Ders</option>
                                                                    </select>
                                                                </div>

                                                                <div class="mb-3" id="updateMonthsWrapper" style="display:none;">
                                                                    <label for="updateMonths" class="form-label required">Kaç Aylık</label>
                                                                    <input type="number" id="updateMonths" name="updateMonths" min="1" class="form-control" />
                                                                </div>

                                                                <div class="mb-3" id="updateCountWrapper" style="display:none;">
                                                                    <label for="updateCount" class="form-label required">Adet</label>
                                                                    <input type="number" id="updateCount" name="updateCount" min="1" class="form-control" />
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="updatePackageDescription" class="form-label">Açıklama</label>
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
        <script src="assets/js/widgets.bundle.js"></script>
        <script src="assets/js/custom/widgets.js"></script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const updatePriceInput = document.getElementById("updatePackagePrice"); // KDV hariç
                const updatePriceWithTaxInput = document.getElementById("updatePackagePriceWithTax"); // KDV dahil
                const taxRate = <?=$taxRate?>; // %10 KDV

                // KDV hariç fiyat girilince KDV dahil hesapla
                updatePriceInput.addEventListener("input", function() {
                    const price = parseFloat(updatePriceInput.value);
                    if (!isNaN(price)) {
                        updatePriceWithTaxInput.value = (price * taxRate).toFixed(2);
                    } else {
                        updatePriceWithTaxInput.value = "";
                    }
                });

                // KDV dahil fiyat girilince KDV hariç hesapla
                updatePriceWithTaxInput.addEventListener("input", function() {
                    const priceWithTax = parseFloat(updatePriceWithTaxInput.value);
                    if (!isNaN(priceWithTax)) {
                        updatePriceInput.value = (priceWithTax / taxRate).toFixed(2);
                    } else {
                        updatePriceInput.value = "";
                    }
                });


                const priceInput = document.getElementById("addPackagePrice"); // KDV hariç
                const priceWithTaxInput = document.getElementById("addPackagePriceWithTax"); // KDV dahil


                // KDV hariç fiyat girildiğinde otomatik dahil fiyatı doldur
                priceInput.addEventListener("input", function() {
                    const price = parseFloat(priceInput.value);
                    if (!isNaN(price)) {
                        priceWithTaxInput.value = (price * taxRate).toFixed(2);
                    } else {
                        priceWithTaxInput.value = "";
                    }
                });

                // KDV dahil fiyat girildiğinde otomatik hariç fiyatı doldur
                priceWithTaxInput.addEventListener("input", function() {
                    const priceWithTax = parseFloat(priceWithTaxInput.value);
                    if (!isNaN(priceWithTax)) {
                        priceInput.value = (priceWithTax / taxRate).toFixed(2);
                    } else {
                        priceInput.value = "";
                    }
                });
            });
            $(document).ready(function() {

                // DataTable başlat
                const table = $('#kt_customers_table').DataTable();

                // Arama kutusunu bağla
                $('[data-kt-customer-table-filter="search"]').on('keyup', function() {
                    table.search(this.value).draw();
                });

                // Add Modal: Tip seçimine göre alanları göster/gizle
                $('#addPackageType').on('change', function() {
                    if (this.value === 'Koçluk' || this.value === 'Rehberlik') {
                        $('#addMonthsWrapper').show();
                        $('#addCountWrapper').hide();
                        $('#addCount').val('');
                    } else if (this.value === 'Özel Ders') {
                        $('#addCountWrapper').show();
                        $('#addMonthsWrapper').hide();
                        $('#addMonths').val('');
                    } else {
                        $('#addMonthsWrapper').hide();
                        $('#addCountWrapper').hide();
                        $('#addMonths').val('');
                        $('#addCount').val('');
                    }
                });

                // Update Modal: Tip seçimine göre alanları göster/gizle
                $('#updatePackageType').on('change', function() {
                    if (this.value === 'Koçluk' || this.value === 'Rehberlik') {
                        $('#updateMonthsWrapper').show();
                        $('#updateCountWrapper').hide();
                        $('#updateCount').val('');
                    } else if (this.value === 'Özel Ders') {
                        $('#updateCountWrapper').show();
                        $('#updateMonthsWrapper').hide();
                        $('#updateMonths').val('');
                    } else {
                        $('#updateMonthsWrapper').hide();
                        $('#updateCountWrapper').hide();
                        $('#updateMonths').val('');
                        $('#updateCount').val('');
                    }
                });

                // Ekleme form submit (örnek Ajax gönderim)
                $('#addPackageForm').on('submit', function(e) {
                    e.preventDefault();

                    const formData = $(this).serialize();

                    $.ajax({
                        url: 'includes/ajax.php?service=addExtraPackage', // Backend PHP dosyası
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Başarılı!',
                                    text: 'Yeni paket başarıyla eklendi.',
                                    confirmButtonText: 'Tamam'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Modal kapat ve formu sıfırla
                                        $('#addPackageModal').modal('hide');
                                        $('#addPackageForm')[0].reset();

                                    }
                                    location.href = window.location.href;

                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Hata!',
                                    text: 'Bir sorun oluştu. Lütfen tekrar deneyin.',
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
                    const type = tr.data('type');
                    const price = tr.data('price');
                    const months = tr.data('months');
                    const count = tr.data('count');
                    const description = tr.data('description'); // Açıklama verisini al

                    $('#updatePackageId').val(id);
                    $('#updatePackageName').val(name);
                    $('#updatePackagePrice').val(price);
                    $('#updatePackagePriceWithTax').val((price * <?= $taxRate ?>).toFixed(2));
                    $('#updatePackageType').val(type).trigger('change');
                    if (type === 'Koçluk' || type === 'Rehberlik') {
                        $('#updateMonths').val(months);
                    } else if (type === 'Özel Ders') {
                        $('#updateCount').val(count);
                    }
                    $('#updatePackageDescription').val(description); // Açıklama alanını doldur

                    $('#updatePackageModal').modal('show');
                });

                // Güncelleme form submit (örnek Ajax)
                $('#updatePackageForm').on('submit', function(e) {
                    e.preventDefault();

                    const formData = $(this).serialize();

                    $.ajax({
                        url: 'includes/ajax.php?service=updateExtraPackage', // Güncelleme işlemini yapan PHP endpoint
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Başarılı!',
                                    text: 'Paket başarıyla güncellendi.',
                                    confirmButtonText: 'Tamam'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        $('#updatePackageModal').modal('hide');
                                        // Gerekirse tabloyu güncelle ya da yeniden yükle
                                    }
                                    location.href = window.location.href;

                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Hata!',
                                    text: 'Paket güncellenemedi. Lütfen tekrar deneyin.',
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
                                url: 'includes/ajax.php?service=deleteExtraPackage', // Silme işlemini yapan PHP endpoint
                                type: 'POST',
                                data: {
                                    action: 'delete_package',
                                    id: id
                                },
                                dataType: 'json',
                                success: function(response) {
                                    if (response.status === 'success') {
                                        Swal.fire('Silindi!', 'Paket başarıyla silindi.', 'success');
                                        $(`tr[data-id="${id}"]`).remove();
                                    } else {
                                        Swal.fire('Hata!', 'Paket silinemedi.', 'error');
                                    }
                                    location.href = window.location.href;

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
<?php } else {
    header("location: index");
}
