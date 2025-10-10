<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 10001 or $_SESSION['role'] == 10002 or $_SESSION['role'] == 10005)) {
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";
    require_once "classes/student.classes.php";
    $pskTestList = new Classes();
    $studentInfo = new Student();
    $pskTestList = $pskTestList->getPskTestList();
    $kart_arka_plan_sinif = 'bg-light-info';
    $ikon_renk_sinif = 'text-info';
    $buton_renk_sinif = 'btn-light-info';
    $kart_sayaci = 0;
    if ($_SESSION['role'] == 10005) {
        $getPreSchoolStudent = $studentInfo->getPreSchoolStudentsInfoForParents($_SESSION['id']);
        $class_idsi = $getPreSchoolStudent[0]['class_id'];
        $studentidsi = $getPreSchoolStudent[0]['id'];
    } else {
        $class_idsi = $_SESSION['class_id'];
        $studentidsi = $_SESSION['id'];
    }



    include_once "views/pages-head.php";
?>


    <body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" class="app-default">
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
                                    <div class="card-body pt-5 ">
                                        <div class="row container-fluid" style="margin-top:-25px;">
                                            <header class="container-fluid bg-custom-light py-3 d-flex justify-content-between align-items-center
                                               border-top border-bottom border-custom-red mb-2" style="border-width: 5px !important; height:85px;margin-bottom: 26px !important;">

                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-danger me-3 shadow icon-circle-lg d-flex justify-content-center align-items-center"
                                                        style="width: 65px; height: 65px;">
                                                        <i class="fas fa-bullseye fa-2x text-white"></i>
                                                    </div>

                                                    <h1 class="fs-3 fw-bold text-dark mb-0">Psikolog Köşesi</h1>
                                                </div>
                                            </header>
                                        </div>



                                        <div class="row g-4 justify-content-center">

                                            <?php if (!empty($pskTestList) && is_array($pskTestList)): ?>
                                                <?php foreach ($pskTestList as $test):
                                                    // Test verilerini güvenli bir şekilde al
                                                    $testId = htmlspecialchars($test['id'] ?? '');
                                                    $testName = htmlspecialchars($test['name'] ?? 'İsimsiz Psikolojik Test');
                                                    // İstenen link yapısı: abc.php?id=TEST_ID
                                                    $testLink = 'pskolojik-test-detail?id=' . $testId.'&test_name='.$testName;
                                                    // Mevcut değişkenlerin varsayılan değerlerini daha modern bir görünüme uygun olarak ayarla
                                                    $kart_arka_plan_sinif = $kart_arka_plan_sinif ?? 'bg-white'; // Varsayılan beyaz kart
                                                    $ikon_renk_sinif = $ikon_renk_sinif ?? 'text-primary'; // Varsayılan ikon rengi
                                                    $buton_renk_sinif = $buton_renk_sinif ?? 'btn-primary'; // Varsayılan buton rengi
                                                ?>

                                                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                                                        <a href="<?php echo $testLink; ?>"
                                                            class="card h-100 <?php echo $kart_arka_plan_sinif; ?> shadow-lg border-0 transition-300 transform-scale-hover text-decoration-none">
                                                            <div class="card-header border-0 pt-6 pb-2 d-flex justify-content-center">
                                                                <div class="symbol symbol-80px symbol-circle bg-light-primary mb-3">
                                                                    <span class="symbol-label fs-1 fw-bold <?php echo $ikon_renk_sinif; ?> text-primary">
                                                                        <i class="ki-duotone ki-flask fs-2x">
                                                                            <span class="path1"></span>
                                                                            <span class="path2"></span>
                                                                        </i>
                                                                    </span>
                                                                </div>
                                                            </div>

                                                            <div class="card-body d-flex flex-column align-items-center justify-content-start text-center p-5 pt-0">
                                                                <h4 class="fw-bolder text-gray-800 mb-2 mt-4 fs-5 text-truncate w-100 px-3"
                                                                    title="<?php echo $testName; ?>">
                                                                    <?php echo $testName; ?>
                                                                </h4>
                                                                <p class="text-muted fs-7 mb-4">Kendini keşfetmeye hemen başla.</p>
                                                            </div>

                                                            <div class="card-footer border-0 p-4 pt-0 text-center">
                                                                <span class="btn btn-sm <?php echo $buton_renk_sinif; ?> fw-bolder px-5 py-3 text-uppercase shadow-sm">
                                                                    HEMEN BAŞLA
                                                                </span>
                                                            </div>
                                                        </a>
                                                    </div>

                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <div class="col-12">
                                                    <div class="alert alert-info text-center py-5">
                                                        <h4 class="alert-heading fw-bold">Test Bulunamadı 😞</h4>
                                                        <p class="mb-0">Henüz psikolojik test kartı bulunmamaktadır. Yeni testler yakında eklenecektir.</p>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <style>
                                            /* Kullanıcı Dostu Geliştirmeler için Özel CSS Stilleri */

                                            /* Kartların daha canlı görünmesi ve etkileşimli olması için geçişler */
                                            .transition-300 {
                                                transition: all 0.3s ease-in-out;
                                            }

                                            /* Hover efekti ile kartı hafifçe büyütme ve gölgeyi artırma */
                                            .transform-scale-hover:hover {
                                                transform: translateY(-5px);
                                                /* Hafif yukarı kaydırma */
                                                box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
                                                /* Daha belirgin gölge */
                                            }

                                            /* Karttaki linkin alt çizgiyi ve rengi kaldırması */
                                            .text-decoration-none {
                                                text-decoration: none !important;
                                            }

                                            /* Başlıkların tek satırda kalması için zorlama */
                                            .text-truncate {
                                                white-space: nowrap;
                                                overflow: hidden;
                                                text-overflow: ellipsis;
                                            }

                                            /* İkon arka planı için daha yumuşak bir renk (varsayılan primary renkten açılmış) */
                                            .bg-light-primary {
                                                background-color: #f3f6f9 !important;
                                                /* veya tema renginize uygun açık bir ton */
                                            }
                                        </style>
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

        <script src="assets/js/widgets.bundle.js"></script>
        <script src="assets/js/custom/widgets.js"></script>
        <script src="assets/js/custom/apps/chat/chat.js"></script>
        <script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
        <script src="assets/js/custom/utilities/modals/create-account.js"></script>
        <script src="assets/js/custom/utilities/modals/create-app.js"></script>
        <script src="assets/js/custom/utilities/modals/users-search.js"></script>
        <script>

        </script>

    </body>

</html>
<?php } else {
    header("location: index");
}
?>