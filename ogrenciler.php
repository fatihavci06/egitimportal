<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 3 or $_SESSION['role'] == 4 or $_SESSION['role'] == 8)) {
    include_once "classes/dbh.classes.php";
    include_once "classes/school.classes.php";
    include_once "classes/school-view.classes.php";
    include_once "classes/student.classes.php";
    include_once "classes/student-view.classes.php";
    $waitingStudents = new Student();
    $students = new ShowStudent();
    $schools = new ShowSchool();
    include_once "views/pages-head.php";
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
                                                                <div class="mb-3">
                                                                    <label for="schoolName" class="form-label">Okul Adı</label>
                                                                    <input class="form-control" type="text" id="schoolName" name="schoolName" required>
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
                                                    <input type="text" data-kt-customer-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Öğrenci Ara" />
                                                </div>
                                                <!--end::Search-->
                                            </div>
                                            <!--begin::Card title-->
                                            <!--begin::Card toolbar-->
                                            <div class="card-toolbar">
                                                <!--begin::Toolbar-->
                                                <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                                                    <!--begin::Add school-->
                                                    <div class="d-flex justify-content-end" style="margin-right: 30px;" data-kt-customer-table-toolbar="base">
                                                    <button type="button" class="btn btn-primary btn-sm mr-4" data-bs-toggle="modal" data-bs-target="#excelAktarModal">
                                                        Excel Aktar
                                                    </button>
                                                </div>
                                                    <?php if (!empty($waitingStudents->getWaitingMoneyTransfers()) AND $_SESSION['role'] == 1) { ?><a href="havale-beklenenler"><button type="button" class="btn btn-primary me-3" data-bs-toggle="modal">Havalesi Beklenen Öğrenciler</button></a><?php } ?>
                                                    <!--end::Add school-->
                                                    <!--begin::Filter-->
                                                    <?php if ($_SESSION['role'] == 1 or $_SESSION['role'] == 3 OR $_SESSION['role'] == 8) { ?>
                                                    <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                        <i class="ki-duotone ki-filter fs-2">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>Filtre</button>
                                                    <!--begin::Menu 1-->
                                                    <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true" id="kt-toolbar-filter">
                                                        <!--begin::Header-->
                                                        <div class="px-7 py-5">
                                                            <div class="fs-4 text-gray-900 fw-bold">Filtreleme</div>
                                                        </div>
                                                        <!--end::Header-->
                                                        <!--begin::Separator-->
                                                        <div class="separator border-gray-200"></div>
                                                        <!--end::Separator-->
                                                        <!--begin::Content-->
                                                        <div class="px-7 py-5">
                                                            <!--begin::Input group-->
                                                            <div class="mb-10">
                                                                <!--begin::Label-->
                                                                <label class="form-label fs-5 fw-semibold mb-3">Durum:</label>
                                                                <!--end::Label-->
                                                                <!--begin::Input-->
                                                                <select class="form-select form-select-solid fw-bold" data-kt-select2="true" data-placeholder="Durum Seçin" data-allow-clear="true" data-kt-customer-table-filter="status" data-dropdown-parent="#kt-toolbar-filter">
                                                                    <option></option>
                                                                    <option value="Aktif">Aktif</option>
                                                                    <option value="Passive">Pasif</option>
                                                                </select>
                                                                <!--end::Input-->
                                                            </div>
                                                            <?php if($_SESSION['role'] != 8){ ?>
                                                            <!--end::Input group-->
                                                            <div class="mb-10">
                                                                <!--begin::Label-->
                                                                <label class="form-label fs-5 fw-semibold mb-3">Okul:</label>
                                                                <!--end::Label-->
                                                                <!--begin::Input-->
                                                                <select class="form-select form-select-solid fw-bold" data-kt-select2="true" data-placeholder="Okul Seçin" data-allow-clear="true" data-kt-customer-table-filter="school" data-dropdown-parent="#kt-toolbar-filter">
                                                                    <option></option>
                                                                    <?php $schools->getSchoolListFilter(); ?>
                                                                </select>
                                                                <!--end::Input-->
                                                            </div>
                                                            <!--end::Input group-->
                                                            <?php } ?>
                                                            <!--begin::Input group-->
                                                            <div class="mb-10">
                                                                <!--begin::Label-->
                                                                <label class="form-label fs-5 fw-semibold mb-3">Sınıf:</label>
                                                                <!--end::Label-->
                                                                <!--begin::Options-->
                                                                <div class="d-flex flex-column flex-wrap fw-semibold" data-kt-customer-table-filter="student_class">
                                                                    <!--begin::Option-->
                                                                    <label class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                                                                        <input class="form-check-input" type="radio" name="student_class" value="all" checked="checked" />
                                                                        <span class="form-check-label text-gray-600">Tüm Sınıflar</span>
                                                                    </label>
                                                                    <!--end::Option-->
                                                                    <?php
                                                                        if($_SESSION['role'] == 8){ 
                                                                            $students->getClassListWithOutPre();
                                                                        } else {
                                                                            $students->getClassList();
                                                                        }
                                                                    ?>
                                                                </div>
                                                                <!--end::Options-->
                                                            </div>
                                                            <!--end::Input group-->
                                                            <!--begin::Actions-->
                                                            <div class="d-flex justify-content-end">
                                                                <button type="reset" class="btn btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true" data-kt-customer-table-filter="reset">Temizle</button>
                                                                <button type="submit" class="btn btn-primary" data-kt-menu-dismiss="true" data-kt-customer-table-filter="filter">Uygula</button>
                                                            </div>
                                                            <!--end::Actions-->
                                                        </div>
                                                        <!--end::Content-->
                                                    </div>
                                                    <!--end::Menu 1-->
                                                    <!--end::Filter-->
                                                    <?php } ?>
                                                    <!--begin::Add school-->
                                                    <?php if ($_SESSION['role'] != 4) { ?><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer">Öğrenci Ekle</button><?php } ?>
                                                    <!--end::Add school-->
                                                </div>
                                                <!--end::Toolbar-->
                                                <!--begin::Group actions-->
                                                <div class="d-flex justify-content-end align-items-center d-none" data-kt-customer-table-toolbar="selected">
                                                    <div class="fw-bold me-5">
                                                        <span class="me-2" data-kt-customer-table-select="selected_count"></span>Seçildi
                                                    </div>
                                                    <button type="button" class="btn btn-danger" data-kt-customer-table-select="delete_selected">Seçilenleri Pasif Yap</button>
                                                </div>
                                                <!--end::Group actions-->
                                            </div>
                                            <!--end::Card toolbar-->
                                        </div>
                                        <!--end::Card header-->
                                        <!--begin::Card body-->
                                        <div class="card-body pt-0">
                                            <!--begin::Table-->
                                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table">
                                                <thead>
                                                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                                        <th class="w-10px pe-2">
                                                            <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                                <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_customers_table .form-check-input" value="1" />
                                                            </div>
                                                        </th>
                                                        <th class="min-w-100px">Fotoğraf</th>
                                                        <th class="min-w-100px">Öğrenci Adı</th>
                                                        <th class="min-w-100px">E-posta Adresi</th>
                                                        <th class="min-w-100px">Sınıfı</th>
                                                        <th class="min-w-100px">Okulu</th>
                                                        <th class="min-w-100px">Paket Bitiş Tarihi</th>
                                                        <th class="min-w-100px">Ana Okulu</th>
                                                        <th class="min-w-100px">Durum</th>
                                                        <th class="text-end min-w-70px">İşlemler</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="fw-semibold text-gray-600">
                                                    <?php $students->getStudentList(); ?>
                                                </tbody>
                                            </table>
                                            <!--end::Table-->
                                        </div>
                                        <!--end::Card body-->
                                    </div>
                                    <!--end::Card-->
                                    <!--begin::Modals-->
                                    <!--begin::Modal - Customers - Add-->
                                    <?php if ($_SESSION['role'] == 1) {
                                        include_once "views/student/add_student.php";
                                    } else {
                                        include_once "views/student/add_student_school.php";
                                    } ?>
                                    <!--end::Modal - Customers - Add-->
                                    <!--end::Modals-->
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
        <!--begin::Modals-->
        <!--begin::Modal - Upgrade plan-->
        <div class="modal fade" id="kt_modal_upgrade_plan" tabindex="-1" aria-hidden="true">
            <!--begin::Modal dialog-->
            <div class="modal-dialog modal-xl">
                <!--begin::Modal content-->
                <div class="modal-content rounded">
                    <!--begin::Modal header-->
                    <div class="modal-header justify-content-end border-0 pb-0">
                        <!--begin::Close-->
                        <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                            <i class="ki-duotone ki-cross fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </div>
                        <!--end::Close-->
                    </div>
                    <!--end::Modal header-->
                </div>
                <!--end::Modal content-->
            </div>
            <!--end::Modal dialog-->
        </div>
        <!--end::Modal - Upgrade plan-->
        <!--end::Modals-->
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
        <script src="assets/js/custom/apps/students/list/export.js"></script>
        <script src="assets/js/custom/apps/students/list/list.js"></script>
        <script src="assets/js/custom/apps/students/add.js"></script>
        <script src="assets/js/widgets.bundle.js"></script>
        <script src="assets/js/custom/widgets.js"></script>
        <script src="assets/js/custom/apps/chat/chat.js"></script><!-- 
        <script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script> -->
        <script src="assets/js/custom/utilities/modals/create-account.js"></script>
        <script src="assets/js/custom/utilities/modals/create-app.js"></script>
        <script src="assets/js/custom/utilities/modals/users-search.js"></script>
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
        <!--end::Custom Javascript-->
        <!--end::Javascript-->
    </body>
    <!--end::Body-->

</html>
<?php } else {
    header("location: index");
} ?>