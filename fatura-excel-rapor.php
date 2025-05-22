<!DOCTYPE html>
<html lang="tr">

<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) && ($_SESSION['role'] == 1 || $_SESSION['role'] == 10001 || $_SESSION['role'] == 10002)) {
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";

    include_once "views/pages-head.php";
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
                                    <div class="card-body pt-5">

                                        <div class="row mt-4">
                                            <div class="col-lg-4">
                                                <label for="start_date" class="form-label">Başlangıç Tarihi</label>
                                                <input type="date" class="form-control" id="start_date" name="start_date">
                                            </div>
                                            <div class="col-lg-4">

                                                <label for="stop_date" class="form-label">Bitiş Tarihi</label>
                                                <input type="date" class="form-control" id="stop_date" name="stop_date">
                                            </div>
                                        </div>

                                        <div class="row mt-4">
                                            <div class="mt-4 text-start">
                                                <button id="filterBtn" class="btn btn-primary">Filtrele</button>
                                                <button id="clearFilterBtn" class="btn btn-danger">Filtreyi Temizle</button>
                                                <button id="excelExportBtn" class="btn btn-success">Excel'e Aktar</button>

                                            </div>
                                        </div>

                                    </div>

                                    <div class="row mt-3">
                                        <table id="paymentList" class="table align-middle table-row-dashed fs-6 gy-5">
                                            <thead>

                                                <tr>
                                                    <th>NO</th>
                                                    <th>ÖĞRENCİ ADI SOYADI</th>
                                                    <th>ÖĞRENCİ T.C.</th>
                                                    <th>VELİ ADI SOYADI</th>
                                                    <th>VELİ T.C.</th>
                                                    <th>ADRES</th>
                                                    <th>MİKTAR</th>
                                                    <th>AÇIKLAMA</th>

                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
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
                $('#excelExportBtn').on('click', function() {
                    const startDate = $('#start_date').val();
                    const stopDate = $('#stop_date').val();
                    if (!startDate || !stopDate) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Tarih Eksik',
                            text: 'Lütfen başlangıç ve bitiş tarihini giriniz.',
                            confirmButtonText: 'Tamam'
                        });
                        return; // işlemi durdur
                    }
                    // Yeni bir form oluşturup submit ediyoruz
                    const form = $('<form>', {
                        method: 'POST',
                        action: 'kullanici-odeme-raporu-excel.php'
                    });

                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'start_date',
                        value: startDate
                    }));

                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'stop_date',
                        value: stopDate
                    }));

                    $('body').append(form);
                    form.submit();
                    form.remove(); // Temizlik
                });

                $('#filterBtn').on('click', function(e) {
                    e.preventDefault();
                    console.log($('#stop_date').val());
                    const data = {
                        stop_date: $('#stop_date').val(),
                        start_date: $('#start_date').val()
                    };

                    $.ajax({
                        url: 'includes/ajax.php?service=payment-excel',
                        method: 'POST',
                        data: data,
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success' && Array.isArray(response.data)) {
                                if ($.fn.DataTable.isDataTable('#paymentList')) {
                                    $('#paymentList').DataTable().clear().destroy();
                                }

                                $('#paymentList').DataTable({
                                    data: response.data,
                                    searching: true,
                                    columns: [{

                                            data: 'row_number',
                                            title: 'NO'
                                        },
                                        {
                                            data: 'student_fullname',
                                            title: 'ÖĞRENCİ ADI SOYAD'
                                        },
                                        {
                                            data: 'student_identity_id',
                                            title: 'ÖĞRENCİ T.C.'
                                        },
                                        {
                                            data: 'parent_fullname',
                                            title: 'VELİ ADI SOYADI'
                                        },
                                        {
                                            data: 'parent_identity_id',
                                            title: 'VELİ  T.C.'
                                        },
                                        {
                                            data: 'address',
                                            title: 'ADRES'
                                        },
                                        {
                                            data: 'pay_amount',
                                            title: 'MİKTAR'
                                        },
                                        {
                                            data: 'description',
                                            title: 'AÇIKLAMA'
                                        }
                                    ]
                                });
                            } else {
                                alert("Veri boş geldi veya status success değil.");
                            }
                        },
                        error: function(xhr, status, error) {
                            // error.message doğrudan burada undefined olabilir, bu yüzden response'dan alıyoruz
                            let errorMessage = 'Bilinmeyen hata oluştu';

                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            } else if (xhr.responseText) {
                                try {
                                    let json = JSON.parse(xhr.responseText);
                                    if (json.message) errorMessage = json.message;
                                } catch (e) {
                                    // JSON parse edilemedi, errorMessage değişmeden kalır
                                }
                            }

                            console.log(errorMessage); // Hata mesajını konsola yazdır

                            Swal.fire({
                                icon: 'error',
                                title: 'Hata',
                                text: errorMessage
                            });
                        }
                    });
                });

                $('#clearFilterBtn').on('click', function() {
                    $('#start_date').val('');
                    $('#stop_date').val('');

                    if ($.fn.DataTable.isDataTable('#paymentList')) {
                        $('#paymentList').DataTable().clear().draw();
                    }
                });
            });
        </script>

    </body>

<?php
} else {
    header("location: index");
}
?>

</html>