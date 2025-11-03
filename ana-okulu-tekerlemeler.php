<?php
session_start();
define('GUARD', true);

if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 10002 or $_SESSION['role'] == 10005)) {
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";

    $classes = new Classes();
    $tekerlemeler = $classes->getTekerlemeListByClassId($_SESSION['class_id']);

    include_once "views/pages-head.php";
?>
    <style>
        /* 🎨 Görselin sabit yüksekliği ve genişliği */
        .tekerleme-gorsel {
            height: 400px;
            width: 100%;
            object-fit: cover;
        }

        /* Overlay için */
        .card-img-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            /* Yatayda ortala */
            align-items: center;
            /* Dikeyde ortala */
            pointer-events: none;
            /* Overlay'in kendisi tıklanabilir olmasın */
            padding: 1rem;
            /* Yeni: Görselin üstünde tam ortada olmak için */
        }

        .card-img-overlay p {
            pointer-events: auto;
            /* Yazının tıklandığında etkileşimde olmasını sağla (isteğe bağlı) */
            background-color: rgba(255, 255, 255, 0.9);
            /* Yeni: Yarı saydam beyaz arka plan */
            color: #000;
            /* Yeni: Siyah yazı rengi */
            padding: 1rem 2rem;
            /* Daha geniş padding */
            border-radius: 0.5rem;
            /* Daha belirgin köşeler */
            margin: 0;
            text-align: center;
            /* Yeni: Yazı içeriğinin maksimum genişliğini ayarla */
            max-width: 80%;
            font-weight: bold;
            /* Yazıyı daha belirgin yap */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            /* Hafif gölge ekle */
        }
    </style>

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
                                    <div class="row container-fluid" style="margin-top:-25px;">
                                            <header class="container-fluid bg-custom-light py-3 d-flex justify-content-between align-items-center
                                             border-top border-bottom border-custom-red mb-2" style="border-width: 5px !important; height:85px;margin-bottom: 26px !important;">

                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-danger me-3 shadow icon-circle-lg d-flex justify-content-center align-items-center"
                                                        style="width: 65px; height: 65px;">
                                                        <i class="fas fa-bullseye fa-2x text-white"></i>
                                                    </div>

                                                    <h1 class="fs-3 fw-bold text-dark mb-0">TEKERLEMELER</h1>
                                                </div>

                                            </header>
                                        </div>
                                    <?php if (!empty($tekerlemeler)) : ?>
                                        <div class="row">
                                            <?php foreach ($tekerlemeler as $tekerleme) : ?>
                                                <div class="col-md-6 mb-4">
                                                    <div class="card h-100 position-relative">

                                                        <?php if (!empty($tekerleme['image_path'])) : ?>
                                                            <div class="position-relative">
                                                                <img src="<?= htmlspecialchars($tekerleme['image_path']) ?>" class="card-img-top tekerleme-gorsel" alt="Tekerleme Görseli">
                                                                <div class="card-img-overlay">
                                                                    <p><?= nl2br(htmlspecialchars($tekerleme['description'])) ?></p>
                                                                </div>
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="card-body">
                                                                <p class="card-text"><?= nl2br(htmlspecialchars($tekerleme['description'])) ?></p>
                                                            </div>
                                                        <?php endif; ?>

                                                        <?php if (!empty($tekerleme['sound_path'])) : ?>
                                                            <div class="card-body border-top" style="padding: 0rem 2.25rem; background-color: #f1f3f4;">
                                                                <audio controls class="w-100">
                                                                    <source src="<?= htmlspecialchars($tekerleme['sound_path']) ?>" type="audio/mpeg">
                                                                    Tarayıcınız audio öğesini desteklemiyor.
                                                                </audio>
                                                            </div>
                                                        <?php endif; ?>

                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                </div>
                            <?php else : ?>
                                <p>Henüz tekerleme bulunamadı.</p>
                            <?php endif; ?>
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

        <script src="assets/js/custom/apps/class/list/export.js"></script>
        <script src="assets/js/custom/apps/class/list/list.js"></script>
        <script src="assets/js/widgets.bundle.js"></script>
    </body>

    </html>
<?php
} else {
    header("location: index");
}
?>