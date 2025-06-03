<?php
session_start();
define('GUARD', true);
if (!isset($_SESSION['role'])) {
    header("HTTP/1.0 404 Not Found");
    header("Location: 404.php");
    exit();
}

require_once "classes/dbh.classes.php";
require_once "views/pages-head.php";
?>
<!DOCTYPE html>
<html lang="tr">

<body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true"
    data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
    data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true"
    data-kt-app-aside-enabled="true" data-kt-app-aside-fixed="true" data-kt-app-aside-push-toolbar="true"
    data-kt-app-aside-push-footer="true" class="app-default">
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

                        <div id="kt_app_toolbar" class="app-toolbar pt-5">
                            <div id="kt_app_toolbar_container"
                                class="app-container container-fluid d-flex align-items-stretch">
                                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                                    <div class="page-title d-flex flex-column gap-1 me-3 mb-2">
                                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold mb-6">
                                            <li class="breadcrumb-item text-gray-700 fw-bold lh-1">
                                                <a href="index.html" class="text-gray-500 text-hover-primary">
                                                    <i class="ki-duotone ki-home fs-3 text-gray-500 me-n1"></i>
                                                </a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <i class="ki-duotone ki-right fs-4 text-gray-700 mx-n1"></i>
                                            </li>
                                            <li class="breadcrumb-item text-gray-700 fw-bold lh-1">
                                                Mesaj Gönder
                                            </li>
                                        </ul>

                                        <h1
                                            class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bolder fs-1 lh-0">
                                            Mesaj Gönder
                                        </h1>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="kt_app_content" class="app-content flex-column-fluid">

                            <div id="kt_app_content_container" class="app-container container-fluid">

                                <form class="form" action="#" id="kt_modal_add_customer_form"
                                    data-kt-redirect="mesajlar">
                                    <div class="card-body pt-5">

                                        <div class="fv-row mb-7">
                                            <label class="required fs-6 fw-semibold mb-2">Kime</label>

                                            <select id="receiver" name="receiver" aria-label="Kişi Seçiniz"
                                                data-control="select2" data-placeholder="Kişi Seçiniz..."
                                                class="form-select form-select-solid fw-bold">
                                                <option value="">Alıcı Seçin</option>
                                                <?php
                                                require_once "classes/userslist.classes.php";
                                                $usersObj = new User();
                                                $users = $usersObj->getAllUsers();
                                                foreach ($users as $user) {
                                                    echo '<option value="'.$user['id'].'">'.$user['name'].' '.$user['surname'].' - '.$user['roleName'].' </option>';
                                                }
                                                ?>

                                            </select>
                                        </div>

                                        <div class="fv-row mb-7">
                                            <label class="required fs-6 fw-semibold mb-2">Başlık</label>

                                            <input type="text" id="subject" class="form-control form-control-solid"
                                                placeholder="Başlık Yazın" name="subject" />

                                        </div>

                                        <div class="fv-row mb-7">
                                            <label class="required fs-6 fw-semibold mb-2">Açıklama</label>

                                            <textarea name="body" id="body" class="form-control form-control-solid"
                                                placeholder="Açıklama Yazın" rows="5"></textarea>
                                        </div>

                                        <div class="modal-footer flex-center">
                                            <button type="submit" id="kt_modal_add_customer_submit"
                                                class="btn btn-primary btn-sm">
                                                <span class="indicator-label">Gönder</span>
                                                <span class="indicator-progress">Lütfen Bekleyin...
                                                    <span
                                                        class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                            </button>
                                        </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--begin::Footer-->
                    <?php include_once "views/footer.php"; ?>
                    <!--end::Footer-->
                </div>
                <!--begin::aside-->
                <?php include_once "views/aside.php"; ?>
                <!--end::aside-->
            </div>
        </div>
    </div>
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
    <script src="assets/plugins/custom/tinymce/tinymce.bundle.js"></script>
    <script src="assets/js/custom/apps/messages/add.js"></script>
    <script src="assets/js/widgets.bundle.js"></script>
    <script src="assets/js/custom/widgets.js"></script>

    <!--end::Custom Javascript-->
    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>