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

    <style>

        /* Minimal custom style for colors not in Bootstrap's palette, 
           or for specific border widths if Bootstrap's are not enough. */
        .bg-custom-light {
            background-color: #e6e6fa;
            /* Light purple */
        }

        .border-custom-red {
            border-color: #d22b2b !important;
        }

        .text-custom-cart {
            color: #6a5acd;
            /* Slate blue for the cart */
        }

        /* For the circular icon, we'll use a larger padding or fixed size */
        .icon-circle-lg {
            width: 60px;
            /* fixed width */
            height: 60px;
            /* fixed height */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-circle-lg img {
            max-width: 100%;
            /* Ensure image scales within the circle */
            max-height: 100%;
        }
    </style>
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
                                <div id="kt_app_content_container" class="app-container container-fluid" style="    margin-top: -17px;">
                                    <!--begin::Card-->
<<<<<<< HEAD
                                    <div style="width: 100%; max-width: 1200px; margin: 0 auto; overflow: hidden;">
                                        <div style="position: relative; padding-bottom: 354%; height: 0; overflow: hidden;">
                                            <iframe
                                                src="https://lookerstudio.google.com/embed/reporting/62dddb6a-3c9c-47df-9d74-f885ce6c1c04/page/kIV1C"
                                                frameborder="0"
                                                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border:0;"
                                                allowfullscreen
                                                sandbox="allow-storage-access-by-user-activation allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox">
                                            </iframe>
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
=======
                            <div style="width: 100%; max-width: 1200px; margin: 0 auto; overflow: hidden;">
    <div style="position: relative; padding-bottom: 354%; height: 0; overflow: hidden;">
        <iframe 
            src="https://lookerstudio.google.com/embed/reporting/62dddb6a-3c9c-47df-9d74-f885ce6c1c04/page/kIV1C" 
            frameborder="0" 
            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border:0;" 
            allowfullscreen
            sandbox="allow-storage-access-by-user-activation allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox">
        </iframe>
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
>>>>>>> 4430a13068072d7918c35abf5bf148d8dd1089f6




<<<<<<< HEAD
            <!--end::Custom Javascript-->
            <!--end::Javascript-->
=======
        <!--end::Custom Javascript-->
        <!--end::Javascript-->
>>>>>>> 4430a13068072d7918c35abf5bf148d8dd1089f6
    </body>
    <!--end::Body-->

</html>
<?php } else {
    header("location: index");
}
