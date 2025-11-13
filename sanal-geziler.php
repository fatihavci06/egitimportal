<?php
session_start();
define('GUARD', true);

// Yetki Kontrolü
if (isset($_SESSION['role']) && ($_SESSION['role'] == 1 || $_SESSION['role'] == 10002 || $_SESSION['role'] == 10005)) {

    // Veritabanı ve Sınıf dosyalarını dahil et
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";

    $classes = new Classes();

    // Kullanıcının yetkili olduğu derslere ait şarkı listesini çek
    $sanalGeziler = $classes->getSanalGEziListByClassId($_SESSION['class_id']);

    include_once "views/pages-head.php";
?>

    <style>
        .text-hover-primary:hover {
            color: #1b5900 !important;
        }

        .btn-sanal-gezi {
            background-color: #ed5606;
            color: #fff;
        }

        .btn-sanal-gezi:hover {
            background-color: #1b5900;
            color: #fff;
        }

        .bg-custom-light {
            background-color: #e6e6fa;
        }
    </style>

    <body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true"
        data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true"
        data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-toolbar="true"
        data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true"
        data-kt-app-aside-enabled="true" data-kt-app-aside-fixed="true"
        data-kt-app-aside-push-toolbar="true" data-kt-app-aside-push-footer="true"
        class="app-default">

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
                                    <div class="row container-fluid" style="margin-top:-25px;">
                                        <header
                                            class="container-fluid bg-custom-light py-3 d-flex justify-content-between align-items-center border-top border-bottom border-custom-red mb-2"
                                            style="border-width: 5px !important; height:85px;margin-bottom: 26px !important;">

                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-danger me-3 shadow icon-circle-lg d-flex justify-content-center align-items-center"
                                                    style="width: 65px; height: 65px;">
                                                    <i class="fas fa-bullseye fa-2x text-white"></i>
                                                </div>

                                                <h1 class="fs-3 fw-bold text-dark mb-0">Sanal Geziler</h1>
                                            </div>
                                        </header>
                                    </div>

                                    <div class="row g-5 g-xl-8">
                                        <?php
                                        $i = 0; // Sayaç başlatılıyor
                                        if (!empty($sanalGeziler)) {
                                            foreach ($sanalGeziler as $gezi) {
                                                $i++;
                                                // Arka plan rengi: Tek satır için açık gri (#f3f6f9), Çift satır için beyaz (#ffffff)
                                                $bgColorCode = ($i % 2 == 1) ? '#f1f1f1' : '#ffffff';
                                        ?>
                                                <div class="col-12">
                                                    <div class="card card-flush" style="background-color: <?php echo $bgColorCode; ?> !important;">
                                                        <div class="card-body d-flex align-items-center justify-content-between py-5">
                                                            <div class="d-flex align-items-center flex-grow-1">
                                                                <div class="symbol symbol-40px symbol-circle me-4">
                                                                    <span class="symbol-label bg-light-primary d-flex justify-content-center align-items-center">
                                                                        <?php if (!empty($gezi['icon'])): ?>
                                                                            <img src="./uploads/icons/<?php echo htmlspecialchars($gezi['icon']); ?>"
                                                                                alt="icon" width="30" height="30" style="filter: invert(32%) sepia(72%) saturate(1819%) hue-rotate(71deg) brightness(97%) contrast(104%);">
                                                                        <?php else: ?>
                                                                            <i class="ki-duotone ki-map fs-2 text-primary text-hover-primary"></i>
                                                                        <?php endif; ?>
                                                                    </span>
                                                                </div>

                                                                <div class="fw-bold text-700 fs-7 text-hover-primary" style="color:#2b8c01">
                                                                    <?php echo htmlspecialchars($gezi['title']); ?>
                                                                </div>
                                                            </div>

                                                            <div>
                                                                <a href="<?php echo htmlspecialchars($gezi['link']); ?>" target="_blank"
                                                                    class="btn btn-sm btn-sanal-gezi fw-bold" style="min-width: 150px;">
                                                                    BAĞLANTIYA GİT
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                        <?php
                                            }
                                        } else {
                                            echo '<div class="col-12"><div class="alert alert-warning text-center">Size atanmış herhangi bir sanal gezi bulunmamaktadır.</div></div>';
                                        }
                                        ?>
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
        <script src="assets/js/custom/apps/class/list/export.js"></script>
        <script src="assets/js/custom/apps/class/list/list.js"></script>
        <script src="assets/js/widgets.bundle.js"></script>


    </body>


    </html>

<?php
} else {
    header("location: index");
}
