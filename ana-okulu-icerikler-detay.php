<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 10001)) {
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";

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
                                    <div class="card-body pt-5">
                                        <?php
                                        $data = new Classes();
                                        $data = $data->getMainSchoolContentById($_GET['id']);
                                        ?>
                                        <div class="row">
                                            <!-- Bootstrap Tab Yapısı -->
                                            <!-- Bootstrap 5 Tab Yapısı -->
                                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link active" id="video-tab" data-bs-toggle="tab" href="#video" role="tab" aria-controls="video" aria-selected="true" style="font-size: 23px;">Video</a>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link" id="content-tab" data-bs-toggle="tab" href="#content" role="tab" aria-controls="content" aria-selected="false" style="font-size: 23px;">İçerik</a>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link" id="files-tab" data-bs-toggle="tab" href="#files" role="tab" aria-controls="files" aria-selected="false" style="font-size: 23px;">Dosyalar</a>
                                                </li>
                                            </ul>

                                            <div class="tab-content mt-4" id="myTabContent">
                                                <!-- Video Tab -->
                                                <div class="tab-pane fade show active" id="video" role="tabpanel" aria-labelledby="video-tab">

                                                    <div id="videoContent">
                                                        <?php
                                                        if (!empty($data['video_url'])) {
                                                        ?>
                                                            <iframe title="vimeo-player" width="100%" height="800"  src="https://player.vimeo.com/video/<?= $data['video_url'] ?>" width="640" height="360" frameborder="0" allowfullscreen></iframe>
                                                    </div>
                                                <?php
                                                        }
                                                ?>
                                                </div>

                                                <!-- İçerik Tab -->
                                                <div class="tab-pane fade" id="content" role="tabpanel" aria-labelledby="content-tab">
                                                    <div class="row" style="font-size:20px;">
                                                        <p id="contentDescription">
                                                            <?php
                                                            if (isset($data['content_description'])) {
                                                                echo $data['content_description'];
                                                            }
                                                            ?>



                                                        </p>
                                                    </div>
                                                </div>

                                                <!-- Dosyalar Tab -->
                                                <div class="tab-pane fade" id="files" role="tabpanel" aria-labelledby="files-tab">
                                                    <?php
                                                    if (isset($data['files'])) {
                                                    ?>
                                                        <?php foreach ($data['files'] as $file): ?>
                                                            <div class="col-md-12 mb-4" style="font-size: 20px;">
                                                                <div class="card shadow-sm">
                                                                    <div class="card-body">
                                                                        <!-- Dosya Başlığı ve Bağlantısı -->
                                                                        <h5 class="card-title">
                                                                            <a href="<?= $file['file_path'] ?>" target="_blank" class="text-decoration-none text-primary">
                                                                                <i class="bi bi-file-earmark"></i> <?= basename($file['file_path']) ?>
                                                                            </a>
                                                                        </h5>

                                                                        <!-- Dosya Açıklaması -->
                                                                        <p><strong>Açıklama:</strong> <?= htmlspecialchars($file['description'] ?? 'Açıklama mevcut değil.') ?></p>

                                                                        <!-- Dosya Yükleme veya Düzenleme Bölümü -->


                                                                        <!-- Dosya İndirme Butonu -->
                                                                        <div class="d-flex justify-content-between">
                                                                            <a href="<?= $file['file_path'] ?>" class="btn btn-primary" target="_blank">
                                                                                <i class="bi bi-download"></i> Dosyayı Görüntüle
                                                                            </a>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>

                                            </div>


                                        </div>
                                    </div>




                                </div>
                                <!--end::Card-->
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
        <script src="assets/plugins/custom/tinymce/tinymce.bundle.js"></script>
        <script src="assets/js/custom/apps/subtopics/list/export.js"></script>
        <script src="assets/js/custom/apps/subtopics/list/list.js"></script>
        <script src="assets/js/custom/apps/subtopics/list/topicadd.js"></script>
        <script src="assets/js/custom/apps/subtopics/add.js"></script>
        <script src="assets/js/custom/apps/subtopics/create.js"></script>
        <script src="assets/js/widgets.bundle.js"></script>
        <script src="assets/js/custom/widgets.js"></script>
        <script src="assets/js/custom/apps/chat/chat.js"></script>
        <script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
        <script src="assets/js/custom/utilities/modals/create-account.js"></script>
        <script src="assets/js/custom/utilities/modals/create-app.js"></script>
        <script src="assets/js/custom/utilities/modals/users-search.js"></script>


        <!--end::Custom Javascript-->
        <!--end::Javascript-->
    </body>
    <!--end::Body-->

</html>
<?php } else {
    header("location: index");
}
