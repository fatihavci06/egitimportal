<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 2)) {
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";

    include_once "views/pages-head.php";
    $class = new Classes();
    $data = $class->getExtraPackageMyList($_SESSION['id']);



?>
    <!--end::Head-->
    <!--begin::Body-->

    <body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" data-kt-app-aside-enabled="true" data-kt-app-aside-fixed="true" data-kt-app-aside-push-toolbar="true" data-kt-app-aside-push-footer="true" class="app-default">
        <!--begin::Theme mode setup on page load-->
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
        <!--end::Theme mode setup on page load-->
        <!--begin::App-->
        <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
            <!--begin::Page-->
            <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
                <!--begin::Header-->
                <?php include_once "views/header.php"; ?>
                <!--end::Header-->
                <!--begin::Wrapper-->
                <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                    <!--begin::Sidebar-->
                    <?php include_once "views/sidebar.php"; ?>
                    <!--end::Sidebar-->
                    <!--begin::Main-->
                    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                        <!--begin::Content wrapper-->
                        <div class="d-flex flex-column flex-column-fluid">
                            <!--begin::Toolbar-->
                            <?php include_once "views/toolbar.php"; ?>
                            <!--end::Toolbar-->
                            <!--begin::Content-->
                            <div id="kt_app_content" class="app-content flex-column-fluid">
                                <!--begin::Content container-->
                                <div id="kt_app_content_container" class="app-container container-fluid">
                                    <!--begin::Card-->
                                    <div class="card">
                                        <!--begin::Card header-->
                                        <div class="card-header border-0 pt-6">
                                            <!--begin::Card title-->
                                            <div class="card-title">
                                                <!--begin::Search-->
                                                <div class="d-flex align-items-center position-relative my-1">
                                                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                    <input type="text" data-kt-customer-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder=" Ara" />
                                                </div>
                                                <!--end::Search-->
                                            </div>
                                            <!--begin::Card title-->
                                            <!--begin::Card toolbar-->
                                            <div class="card-toolbar">
                                                <!--begin::Toolbar-->
                                                <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                                                    <!--begin::Add school-->
                                                    <button type="button" class="btn btn-primary btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#addPackageModal">
                                                        Ek Paket Ekle
                                                    </button>

                                                </div>

                                                <!--end::Toolbar-->
                                                <!--begin::week actions-->
                                                <div class="d-flex justify-content-end align-items-center d-none" data-kt-customer-table-toolbar="selected">
                                                    <div class="fw-bold me-5">
                                                        <span class="me-2" data-kt-customer-table-select="selected_count"></span>Seçildi
                                                    </div>
                                                </div>
                                                <!--end::week actions-->
                                            </div>
                                            <!--end::Card toolbar-->
                                        </div>
                                        <!--end::Card header-->
                                        <!--begin::Card body-->
                                        <div class="card-body pt-0">
                                            <!-- Button trigger modal -->


                                            <!-- Modal -->
                                            <!-- Button trigger modal (id değeri burada veriliyor) -->



                                            <!-- Tablo -->
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
                                                        <th class="min-w-125px">Bitiş Tarihi/ Kalan Limit</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="fw-semibold text-gray-600">
                                                    <?php foreach ($data as $d) { ?>
                                                        <tr>
                                                            <td>
                                                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                                    <input class="form-check-input" type="checkbox" />
                                                                </div>
                                                            </td>
                                                            <td><?= htmlspecialchars($d['name']) ?></td>
                                                            <td><?= htmlspecialchars($d['type']) ?></td>
                                                            <td><?= htmlspecialchars($d['end_date']) ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>

                                            <!-- Ekleme Modal -->
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
                                                                    <label for="addPackagePrice" class="form-label required">Paket Fiyatı</label>
                                                                    <input type="number" inputmode="decimal" step="0.01" id="addPackagePrice" name="addPackagePrice" class="form-control" required />
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
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                                                                <button type="submit" class="btn btn-primary">Kaydet</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                            <!-- Güncelleme Modal -->
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
                                                                    <label for="updatePackagePrice" class="form-label">Fiyat</label>
                                                                    <input type="number" step="0.01" id="updatePackagePrice" name="updatePackagePrice" class="form-control" required />
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
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                                                                <button type="submit" class="btn btn-primary">Güncelle</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>


                                            <!--end::Table-->
                                        </div>
                                        <!--end::Card body-->
                                    </div>

                                </div>
                                <!--end::Content container-->
                            </div>
                            <!--end::Content-->
                        </div>
                        <!--end::Content wrapper-->
                        <!--begin::Footer-->
                        <?php include_once "views/footer.php"; ?>
                        <!--end::Footer-->
                    </div>
                    <!--end:::Main-->
                    <!--begin::aside-->
                    <?php include_once "views/aside.php"; ?>
                    <!--end::aside-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Page-->
        </div>
        <!--end::App-->
        <!--begin::Scrolltop-->
        <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
            <i class="ki-duotone ki-arrow-up">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </div>
        <!--end::Scrolltop-->
        <!--begin::Javascript-->
        <script>
            var hostUrl = "assets/";
        </script>
        <!--begin::Global Javascript Bundle(mandatory for all pages)-->
        <script src="assets/plugins/global/plugins.bundle.js"></script>
        <script src="assets/js/scripts.bundle.js"></script>
        <!--end::Global Javascript Bundle-->
        <!--begin::Vendors Javascript(used for this page only)-->
        <script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
        <!--end::Vendors Javascript-->
        <!--begin::Custom Javascript(used for this page only)-->

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

                    $('#updatePackageId').val(id);
                    $('#updatePackageName').val(name);
                    $('#updatePackagePrice').val(price);
                    $('#updatePackageType').val(type).trigger('change');
                    if (type === 'Koçluk' || type === 'Rehberlik') {
                        $('#updateMonths').val(months);
                    } else if (type === 'Özel Ders') {
                        $('#updateCount').val(count);
                    }

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




        <!--end::Custom Javascript-->
        <!--end::Javascript-->
    </body>
    <!--end::Body-->

</html>
<?php } else {
    header("location: index");
}
