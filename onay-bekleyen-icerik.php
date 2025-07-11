<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
// if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or ($_SESSION['school_id'] == 1 and (($_SESSION['role'] == 3) or ($_SESSION['role'] == 8))))) {
if (isset($_SESSION['role']) or ($_SESSION['role'] == 1 or ($_SESSION['school_id'] == 1 and (($_SESSION['role'] == 3) or ($_SESSION['role'] == 8))))) {


    include_once "classes/dbh.classes.php";
    include_once "classes/addcontent.classes.php";
    include_once "classes/content-view.classes.php";
    include_once "classes/student.classes.php";
    include_once "classes/student-view.classes.php";
    include_once "views/pages-head.php";

    $students = new ShowStudent();
    $contents = new ShowContents();
    ?>
    <!--end::Head-->
    <!--begin::Body-->

    <body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true"
        data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
        data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true"
        data-kt-app-aside-enabled="true" data-kt-app-aside-fixed="true" data-kt-app-aside-push-toolbar="true"
        data-kt-app-aside-push-footer="true" class="app-default">
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
                                                    <input type="text" data-kt-customer-table-filter="search"
                                                        class="form-control form-control-solid w-250px ps-12"
                                                        placeholder="İçerik Ara" />
                                                </div>
                                                <!--end::Search-->
                                            </div>
                                            <!--begin::Card title-->
                                            <!--begin::Card toolbar-->
                                            <div class="card-toolbar">
                                                <!--begin::Toolbar-->
                                                <div class="d-flex justify-content-end"
                                                    data-kt-customer-table-toolbar="base">
                                                    <?php if ($_SESSION['role'] == 1 or $_SESSION['role'] == 3 or $_SESSION['role'] == 8) { ?>
                                                        <!--begin::Filter-->
                                                        <button type="button" class="btn btn-light-primary me-3"
                                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                            <i class="ki-duotone ki-filter fs-2">
                                                                <span class="path1"></span>
                                                                <span class="path2"></span>
                                                            </i>Filtre</button>
                                                        <!--begin::Menu 1-->
                                                        <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px"
                                                            data-kt-menu="true" id="kt-toolbar-filter">
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
                                                                <form action="" method="GET" id="filtreleme" class="form">
                                                                    <!--begin::Input group-->
                                                                    <div class="mb-10">
                                                                        <!--begin::Label-->
                                                                        <label
                                                                            class="form-label fs-5 fw-semibold mb-3">Durum:</label>
                                                                        <!--end::Label-->
                                                                        <!--begin::Input-->
                                                                        <select class="form-select form-select-solid fw-bold"
                                                                            name="durum" data-kt-select2="true"
                                                                            data-placeholder="Durum Seçin"
                                                                            data-allow-clear="true"
                                                                            data-kt-customer-table-filter="status"
                                                                            data-dropdown-parent="#kt-toolbar-filter">
                                                                            <option></option>
                                                                            <option value="aktif">Aktif</option>
                                                                            <option value="pasif">Pasif</option>
                                                                        </select>
                                                                        <!--end::Input-->
                                                                    </div>
                                                                    <!--end::Input group-->
                                                                    <!--begin::Input group-->
                                                                    <div class="mb-10">
                                                                        <!--begin::Label-->
                                                                        <label
                                                                            class="form-label fs-5 fw-semibold mb-3">Sınıf:</label>
                                                                        <!--end::Label-->
                                                                        <!--begin::Options-->
                                                                        <div class="d-flex flex-column flex-wrap fw-semibold">

                                                                            <!--begin::Input-->
                                                                            <select
                                                                                class="form-select form-select-solid fw-bold"
                                                                                id="sinif" name="sinif" data-kt-select2="true"
                                                                                data-placeholder="Sınıf" data-allow-clear="true"
                                                                                data-kt-customer-table-filter="student_class"
                                                                                data-dropdown-parent="#kt-toolbar-filter">
                                                                                <option></option>
                                                                                <?php $students->getClassDropdownListWithId(); ?>
                                                                            </select>
                                                                            <!--end::Input-->

                                                                        </div>
                                                                        <!--end::Options-->
                                                                    </div>
                                                                    <!--end::Input group-->
                                                                    <!--begin::Input group-->
                                                                    <div class="mb-10">
                                                                        <!--begin::Label-->
                                                                        <label
                                                                            class="form-label fs-5 fw-semibold mb-3">Ders:</label>
                                                                        <!--end::Label-->
                                                                        <!--begin::Input-->
                                                                        <select class="form-select form-select-solid fw-bold"
                                                                            id="ders" name="ders" data-kt-select2="true"
                                                                            data-placeholder="Ders Seçin"
                                                                            data-allow-clear="true"
                                                                            data-kt-customer-table-filter="lesson"
                                                                            data-dropdown-parent="#kt-toolbar-filter">

                                                                        </select>
                                                                        <!--end::Input-->
                                                                    </div>
                                                                    <!--end::Input group-->
                                                                    <!--begin::Input group-->
                                                                    <div class="mb-10">
                                                                        <!--begin::Label-->
                                                                        <label
                                                                            class="form-label fs-5 fw-semibold mb-3">Ünite:</label>
                                                                        <!--end::Label-->
                                                                        <!--begin::Options-->
                                                                        <div class="d-flex flex-column flex-wrap fw-semibold">

                                                                            <!--begin::Input-->
                                                                            <select
                                                                                class="form-select form-select-solid fw-bold"
                                                                                id="unite" name="unite" data-kt-select2="true"
                                                                                data-placeholder="Ünite"
                                                                                data-allow-clear="true">

                                                                            </select>
                                                                            <!--end::Input-->

                                                                        </div>
                                                                        <!--end::Options-->
                                                                    </div>
                                                                    <!--end::Input group-->
                                                                    <!--begin::Input group-->
                                                                    <div class="mb-10">
                                                                        <!--begin::Label-->
                                                                        <label
                                                                            class="form-label fs-5 fw-semibold mb-3">Konu:</label>
                                                                        <!--end::Label-->
                                                                        <!--begin::Options-->
                                                                        <div class="d-flex flex-column flex-wrap fw-semibold">

                                                                            <!--begin::Input-->
                                                                            <select
                                                                                class="form-select form-select-solid fw-bold"
                                                                                id="konu" name="konu" data-kt-select2="true"
                                                                                data-placeholder="Konu" data-allow-clear="true">
                                                                            </select>
                                                                            <!--end::Input-->

                                                                        </div>
                                                                        <!--end::Options-->
                                                                    </div>
                                                                    <!--end::Input group-->
                                                                    <!--begin::Input group-->
                                                                    <div class="mb-10">
                                                                        <!--begin::Label-->
                                                                        <label class="form-label fs-5 fw-semibold mb-3">Alt
                                                                            Konu:</label>
                                                                        <!--end::Label-->
                                                                        <!--begin::Options-->
                                                                        <div class="d-flex flex-column flex-wrap fw-semibold">

                                                                            <!--begin::Input-->
                                                                            <select
                                                                                class="form-select form-select-solid fw-bold"
                                                                                id="altkonu" name="altkonu"
                                                                                data-kt-select2="true"
                                                                                data-placeholder="Alt Konu"
                                                                                data-allow-clear="true">
                                                                            </select>
                                                                            <!--end::Input-->

                                                                        </div>
                                                                        <!--end::Options-->
                                                                    </div>
                                                                    <!--end::Input group-->
                                                                    <!--begin::Actions-->
                                                                    <div class="d-flex justify-content-end">
                                                                        <a href="icerikler"><button type="button"
                                                                                class="btn btn-light btn-active-light-primary me-2">Temizle</button></a>
                                                                        <a href=""><button type="submit"
                                                                                class="btn btn-primary">Uygula</button></a>
                                                                    </div>
                                                                    <!--end::Actions-->
                                                                </form>
                                                            </div>
                                                            <!--end::Content-->
                                                        </div>
                                                        <!--end::Menu 1-->
                                                        <!--end::Filter-->
                                                    <?php } ?>
                                                    <!--begin::Add school-->
                                                    <a href="icerikler"><button type="button" class="btn btn-primary me-3"
                                                            data-bs-toggle="modal">
                                                            Tüm İçerikler</button></a>
                                                    <!--end::Add school-->
                                                </div>
                                                <!--end::Toolbar-->
                                                <!--begin::Group actions-->
                                                <div class="d-flex justify-content-end align-items-center d-none"
                                                    data-kt-customer-table-toolbar="selected">
                                                    <div class="fw-bold me-5">
                                                        <span class="me-2"
                                                            data-kt-customer-table-select="selected_count"></span>Seçildi
                                                    </div>
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        data-kt-customer-table-select="delete_selected">Seçilenleri Pasif
                                                        Yap</button>
                                                </div>
                                                <!--end::Group actions-->
                                            </div>
                                            <!--end::Card toolbar-->
                                        </div>
                                        <!--end::Card header-->
                                        <!--begin::Card body-->
                                        <div class="card-body pt-0">
                                            <!--begin::Table-->
                                            <table class="table align-middle table-row-dashed fs-6 gy-5"
                                                id="kt_customers_table">
                                                <thead>
                                                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                                        <th class="w-10px pe-2">
                                                            <div
                                                                class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                                <input class="form-check-input" type="checkbox"
                                                                    data-kt-check="true"
                                                                    data-kt-check-target="#kt_customers_table .form-check-input"
                                                                    value="1" />
                                                            </div>
                                                        </th>
                                                        <th class="min-w-100px">Onayla</th>
                                                        <th class="min-w-180px">İçerik</th>
                                                        <th class="min-w-100px">Okul</th>
                                                        <th class="min-w-100px">Durum</th>
                                                        <th class="min-w-100px">Alt Konu</th>
                                                        <th class="min-w-100px">Konu</th>
                                                        <th class="min-w-100px">Ünite</th>
                                                        <th class="min-w-100px">Ders</th>
                                                        <th class="min-w-100px">Sınıf</th>
                                                        <th class="text-end min-w-70px">İşlemler</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="fw-semibold text-gray-600">
                                                    <?php $contents->getNotApprovedContentsList(); ?>


                                                </tbody>
                                            </table>
                                            <!--end::Table-->
                                        </div>
                                        <!--end::Card body-->
                                    </div>
                                    <!--end::Card-->
                                    <!--begin::Modals-->
                                    <!--begin::Modal - Customers - Add-->
                                    <?php // include_once "views/topics/add_topic.php" 
                                        ?>
                                    <!--end::Modal - Customers - Add-->
                                    <div class="modal fade" id="kt_modal_success" tabindex="-1"
                                        aria-labelledby="successModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header border-0 pb-0">
                                                    <h5 class="modal-title text-success" id="successModalLabel">
                                                        <i class="ki-duotone ki-check-circle fs-1 text-success me-2"></i>
                                                        Başarılı
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-center py-5">
                                                    <!-- Content will be inserted here by JavaScript -->
                                                </div>
                                                <div class="modal-footer border-0 pt-0">
                                                    <button type="button" class="btn btn-success"
                                                        data-bs-dismiss="modal">Tamam</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Error Modal -->
                                    <div class="modal fade" id="kt_modal_error" tabindex="-1"
                                        aria-labelledby="errorModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header border-0 pb-0">
                                                    <h5 class="modal-title text-danger" id="errorModalLabel">
                                                        <i class="ki-duotone ki-cross-circle fs-1 text-danger me-2"></i>
                                                        Hata
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-center py-5">
                                                    <!-- Content will be inserted here by JavaScript -->
                                                </div>
                                                <div class="modal-footer border-0 pt-0">
                                                    <button type="button" class="btn btn-danger"
                                                        data-bs-dismiss="modal">Tamam</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
        <script src="assets/js/custom/apps/contents/list/export.js"></script>
        <script src="assets/js/custom/apps/contents/list/list.js"></script>
        <!-- <script src="assets/js/custom/apps/contents/add.js"></script> -->
        <script src="assets/js/widgets.bundle.js"></script>
        <script src="assets/js/custom/widgets.js"></script>
        <script src="assets/js/custom/apps/chat/chat.js"></script>
        <script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
        <script src="assets/js/custom/utilities/modals/create-account.js"></script>
        <script src="assets/js/custom/utilities/modals/create-app.js"></script>
        <script src="assets/js/custom/utilities/modals/users-search.js"></script>
        <!--end::Custom Javascript-->
        <!--end::Javascript-->


        <script>
            "use strict";

            var KTActionHandler = function () {
                // Private variables
                var submitButton;
                var validator;
                var form;
                var modal;
                var successModal;
                var errorModal;

                var initModals = function () {
                    successModal = new bootstrap.Modal(document.getElementById('kt_modal_success'));

                    errorModal = new bootstrap.Modal(document.getElementById('kt_modal_error'));
                };

                var handleAction = function (button, itemId) {
                    button.setAttribute('data-kt-indicator', 'on');
                    button.disabled = true;

                    var data = {
                        id: itemId,
                        action: 'process_item'
                    };

                    fetch('includes/approve_content.inc.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify(data)
                    })
                        .then(response => {
                            return response.json().then(data => {
                                return {
                                    status: response.status,
                                    ok: response.ok,
                                    data: data
                                };
                            });
                        })
                        .then(result => {
                            button.removeAttribute('data-kt-indicator');
                            button.disabled = false;

                            if (result.ok && result.data.success) {
                                var statusTarget = button.getAttribute('data-status-target');
                                var statusElement = document.getElementById(statusTarget);

                                if (statusElement && result.data.new_status) {
                                    statusElement.textContent = result.data.new_status;
                                    statusElement.classList.add('text-success');
                                }

                                showSuccessModal(result.data.message);

                                button.querySelector('.indicator-label').textContent = 'Tamamlandı';
                                button.classList.remove('btn-primary');
                                button.classList.add('btn-success');

                            } else {
                                showErrorModal(result.data.message || 'Bilinmeyen bir hata oluştu');
                            }
                        })
                        .catch(error => {
                            button.removeAttribute('data-kt-indicator');
                            button.disabled = false;

                            console.error('Error:', error);
                            showErrorModal('Bağlantı hatası. Lütfen tekrar deneyin.');
                        });
                };

                var showSuccessModal = function (message) {
                    var modalBody = document.querySelector('#kt_modal_success .modal-body');
                    if (modalBody) {
                        modalBody.innerHTML = '<div class="text-center"><i class="ki-duotone ki-check-circle fs-3x text-success mb-3"></i><p class="fs-4 fw-bold text-gray-800">' + message + '</p></div>';
                    }

                    if (successModal) {
                        successModal.show();
                    }
                };

                var showErrorModal = function (message) {
                    var modalBody = document.querySelector('#kt_modal_error .modal-body');
                    if (modalBody) {
                        modalBody.innerHTML = '<div class="text-center"><i class="ki-duotone ki-cross-circle fs-3x text-danger mb-3"></i><p class="fs-4 fw-bold text-gray-800">' + message + '</p></div>';
                    }

                    if (errorModal) {
                        errorModal.show();
                    }
                };

                var initEventListeners = function () {
                    document.addEventListener('click', function (e) {
                        if (e.target.classList.contains('action-button') || e.target.closest('.action-button')) {
                            e.preventDefault();

                            var button = e.target.classList.contains('action-button') ? e.target : e.target.closest('.action-button');
                            var itemId = button.getAttribute('data-item-id');

                            if (itemId) {
                                Swal.fire({
                                    title: 'Emin misiniz?',
                                    text: 'Bu işlemi gerçekleştirmek istediğinizden emin misiniz?',
                                    icon: 'question',
                                    showCancelButton: true,
                                    confirmButtonText: 'Evet, devam et',
                                    cancelButtonText: 'İptal',
                                    customClass: {
                                        confirmButton: 'btn btn-primary',
                                        cancelButton: 'btn btn-secondary'
                                    }
                                }).then(function (result) {
                                    if (result.isConfirmed) {
                                        handleAction(button, itemId);
                                    }
                                });
                            }
                        }
                    });
                };

                return {
                    init: function () {
                        initModals();
                        initEventListeners();
                    }
                };
            }();

            KTUtil.onDOMContentLoaded(function () {
                KTActionHandler.init();
            });

            document.addEventListener('DOMContentLoaded', function () {
                if (typeof KTActionHandler !== 'undefined') {
                    KTActionHandler.init();
                }
            });

        </script>
    </body>
    <!--end::Body-->

    </html>
<?php } else {
    header("location: index");
}
