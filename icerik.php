<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and $_SESSION['role'] == 2) {
    include_once "classes/dbh.classes.php";
    include_once "classes/addcontentstu.classes.php";
    include_once "classes/contentstu-view.classes.php";
    $contents = new ShowContents();
    include_once "views/pages-head.php";
    include_once "classes/content-tracker.classes.php";
    $contentTrackerObj = new ContentTracker();

    $link = "$_SERVER[REQUEST_URI]";

    $active_slug = htmlspecialchars(basename($link, ".php"));

    $contentInfo = $contents->getContentIdBySlug($active_slug);
    $userId = $_SESSION['id'];
    $contentId = $contentInfo['id'];

    $contentTrackerObj->createContentVisit($userId, $contentId);

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
                        <div class="d-flex flex-column flex-column-fluid" style="margin-top:-20px;">
                            <!--begin::Toolbar-->
                            <?php include_once "views/toolbar.php"; ?>
                            <!--end::Toolbar-->
                            <!--begin::Content-->
                            <div id="kt_app_content" class="app-content flex-column-fluid">
                                <!--begin::Content container-->
                                <div id="kt_app_content_container" class="app-container container-fluid">
                                    <!--begin::Careers - List-->
                                    <div class="card">
                                        <!--begin::Body-->
                                        <div class="card-body p-lg-8">
                                            <!--begin::Hero-->
                                            <?php // $contents->getHeaderImageStu(); ?>
                                            <!--end::-->
                                            <!--begin::Layout-->
                                            <div class="flex-column flex-lg-row mb-17" style="margin-top:-30px">
                                                <!--begin::Content-->
                                                <?php $contents->showOneContent(); ?>
                                                <!--end::Content-->
                                            </div>
                                            <!--end::Layout-->
                                        </div>
                                        <!--end::Body-->
                                    </div>
                                    <!--end::Careers - List-->
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

        <script src="https://player.vimeo.com/api/player.js"></script>
        <script src="assets/js/custom/trackTimeOnVimeo.js"></script>
        <script src="assets/js/custom/contentTracker.js"></script>

        <script src="assets/js/custom/apps/topicStu/list/export.js"></script>
        <script src="assets/js/custom/apps/topicStu/list/list.js"></script>
        <script src="assets/js/custom/apps/topicStu/add.js"></script>
        <script src="assets/js/widgets.bundle.js"></script>
        <script src="assets/js/custom/widgets.js"></script>
        <script src="assets/js/custom/apps/chat/chat.js"></script>
        <script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
        <script src="assets/js/custom/utilities/modals/create-account.js"></script>
        <script src="assets/js/custom/utilities/modals/create-app.js"></script>
        <script src="assets/js/custom/utilities/modals/users-search.js"></script>
        <script>
            /*    document.addEventListener('DOMContentLoaded', function() {
                    const videoPlayer = document.getElementById('my-video');
                    const videoSource = videoPlayer.querySelector('source');
                    const videoToLoad = 'DSC_1781as.mp4'; // Çekmek istediğiniz video dosya adı

                    fetch('https://oznarmaden.com/lineup/api/video/get.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded', // Form verisi göndermek için
                            // İsteğe bağlı: 'Content-Type': 'application/json' eğer JSON gönderiyorsanız
                        },
                        body: new URLSearchParams({
                            'video': videoToLoad,
                        }),
                        mode: 'no-cors'
                    })
                    /*.then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data && data.url) {
                            videoSource.src = data.url;
                            videoPlayer.load(); // Video kaynağını yükle
                        } else {
                            console.error('Beklenen URL bilgisi alınamadı.', data);
                        }
                    })
                    .catch(error => {
                        console.error('Video çekme hatası:', error);
                    });*/
            /* });*/
        </script>
        <!--end::Custom Javascript-->
        <!--end::Javascript-->
    </body>
    <!--end::Body-->

    </html>
<?php } else {
    header("location: ../index");
}
