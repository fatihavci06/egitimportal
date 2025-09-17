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
                                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#excelAktarModal">
                                                        Excel Aktar
                                                    </button>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class="modal fade" id="excelAktarModal" tabindex="-1" aria-labelledby="excelAktarModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="excelAktarModalLabel">Excel Dosyası Yükle</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form id="excelAktarForm" enctype="multipart/form-data">
                                                                <div class="mb-3">
                                                                    <label for="excelFile" class="form-label">Excel Dosyası Seçiniz (.csv)</label>
                                                                    <input class="form-control" type="file" id="excelFile" name="excelFile" accept=".csv" required>
                                                                </div>
                                                                <div class="d-grid">
                                                                    <button type="submit" id="excelSubmitBtn" class="btn btn-primary">Aktarımı Başlat</button>
                                                                </div>
                                                            </form>
                                                            <div id="sonucAlani" class="mt-3"></div>
                                                        </div>
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
        <script src="assets/js/widgets.bundle.js"></script>
        <script src="assets/js/custom/widgets.js"></script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Form elemanlarını seçin
                const excelAktarForm = document.getElementById('excelAktarForm');
                const excelSubmitBtn = document.getElementById('excelSubmitBtn');
                const sonucAlani = document.getElementById('sonucAlani');

                // Form gönderildiğinde çalışacak olay dinleyicisi
                excelAktarForm.addEventListener('submit', function(e) {
                    e.preventDefault(); // Sayfanın yeniden yüklenmesini engelle

                    // Form verilerini FormData nesnesiyle topla
                    const formData = new FormData(this);

                    // Butonun durumunu güncelle ve yükleniyor animasyonu ekle
                    excelSubmitBtn.disabled = true;
                    excelSubmitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Yükleniyor...';
                    sonucAlani.innerHTML = ''; // Önceki sonuç mesajını temizle

                    // AJAX isteği başlat
                    fetch('./includes/ajax.php?service=anaOkuluOgrenciAktar', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => {
                            if (!response.ok) {
                                // HTTP hatası (örn. 403, 500) durumunda hata fırlat
                                return response.json().then(errorData => {
                                    throw new Error(errorData.message || 'Sunucu hatası oluştu.');
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Sunucudan dönen JSON verisini işle
                            if (data.status === 'success') {
                                sonucAlani.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                            } else if (data.status === 'warning') {
                                sonucAlani.innerHTML = `<div class="alert alert-warning">${data.message}</div>`;
                            } else {
                                sonucAlani.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                            }
                        })
                        .catch(error => {
                            // Ağ veya sunucu tarafında bir hata oluşursa
                            console.error('Hata:', error);
                            sonucAlani.innerHTML = `<div class="alert alert-danger">Bir hata oluştu: ${error.message}</div>`;
                        })
                        .finally(() => {
                            // İşlem bittiğinde butonu eski haline getir
                            excelSubmitBtn.disabled = false;
                            excelSubmitBtn.innerHTML = 'Aktarımı Başlat';
                        });
                });

                // Modal kapatıldığında formu sıfırla
                const excelAktarModal = document.getElementById('excelAktarModal');
                if (excelAktarModal) {
                    excelAktarModal.addEventListener('hidden.bs.modal', function() {
                        excelAktarForm.reset();
                        sonucAlani.innerHTML = '';
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