<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and $_SESSION['role'] == 2) {
    function getLastUrlSegment(): string
    {
        // URI'den path kısmını al (örneğin /alt-konu/…)
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        // Baştaki/sondaki slash'ları kırpar, parçalarına ayırır
        $segments = explode('/', trim($path, '/'));
        // Son segmenti al
        $last = end($segments);
        // URL kodlamasını çözer ve güvenli hale getirir
        return rawurldecode($last !== false ? $last : '');
    }

    // Kullanım
    $slug = getLastUrlSegment();
    
    include_once "classes/dbh.classes.php";
    include_once "classes/topics.classes.php";
    include_once "classes/topics-view.classes.php";
    include_once "classes/addcontentstu.classes.php";
    include_once "classes/contentstu-view.classes.php";
    include_once "classes/classes.classes.php";
    $contents = new ShowContents();
    $topics = new ShowSubTopic();
    $subtopics = new ShowSubTopic();
    $class = new Classes();
    $lessons = $class->getLessonsList($_SESSION['class_id']);

    $subTopicInfo = $class->getSubTopicBySlug($slug);
  

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
                                <div id="kt_app_content_container"  style="margin-top:-20px;" class="app-container container-fluid px-0">
                                    <!--begin::Careers - List-->
                                    <div class="card">
                                        <header class="container-fluid bg-custom-light py-3 d-flex justify-content-between align-items-center
                                             border-top border-bottom border-custom-red mb-2" style="border-width: 5px !important; height:85px;margin-bottom: 26px !important;">

                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-danger me-3 shadow icon-circle-lg d-flex justify-content-center align-items-center"
                                                    style="width: 65px; height: 65px;">
                                                    <i class="fas fa-bullseye fa-2x text-white"></i>
                                                </div>

                                                <h1 class="fs-3 fw-bold text-dark mb-0"><?=$subTopicInfo['name']?></h1>
                                            </div>

                                        </header>
                                        <!--begin::Body-->
                                        <div class="card-body p-lg-17" style="margin-left:-35px;margin-top:-40px;">
                                            <!--begin::Layout-->
                                            <div class="row g-4 ">
                                                <!-- Sidebar -->
                                                <div class="col-3 col-lg-2 pe-lg-2 text-center">
                                                    <h3>Dersler</h3>
                                                </div>
                                                <div class="col-9 col-lg-10">
                                                </div>
                                            </div>
                                            <div class="row g-4 mb-17">
                                                <!-- Sidebar -->
                                                <div class="col-3 col-lg-2 pe-lg-2">
                                                    <div class="row g-2">
                                                        <?php foreach ($lessons as $l): ?>
                                                            <?php if ($l['name'] !== 'Robotik Kodlama' && $l['name'] !== 'Ders Deneme'): ?>
                                                                <div class="col-12 text-center mb-1">
                                                                    <a href="ders/<?= urlencode($l['slug']) ?>" class="d-inline-block">
                                                                        <img src="assets/media/icons/dersler/<?= htmlspecialchars($l['icons']) ?>" alt="Icon" class="img-fluid" style="width: 65px; height: 65px; object-fit: contain;" />
                                                                        <div class="mt-1"><?= htmlspecialchars($l['name']) ?></div>
                                                                    </a>
                                                                </div>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                                <!-- Content -->
                                                <div class="col-9 col-lg-10">
                                                    <div class="row g-4">
                                                        <?php $contents->getContentListForStudent(); ?>
                                                    </div>
                                                </div>
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
        <!--end::Custom Javascript-->
        <!--end::Javascript-->
    </body>
    <!--end::Body-->

</html>
<?php } else {
    header("location: ../index");
}
