<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);

// Rol kontrolü (Gerekli sınıfların dahil edildiği varsayılmıştır)
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 10001 or $_SESSION['role'] == 10002 or $_SESSION['role'] == 10005)) {
    // Sınıf dosyalarının dahil edildiği varsayılır. (classes/dbh.classes.php vb.)
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";
    include_once "classes/dateformat.classes.php";
    require_once "classes/student.classes.php";

    $dateFormat = new DateFormat();
    $class = new Classes();
    $studentInfo = new Student();

    // Öğrenci ID'sinin belirlenmesi
    if ($_SESSION['role'] == 10005) {
        $getPreSchoolStudent = $studentInfo->getPreSchoolStudentsInfoForParents($_SESSION['id']);
        $studentidsi = $getPreSchoolStudent[0]['id'];
    } else {
        $studentidsi = $_SESSION['id'];
    }

    // Fonksiyon Çağrısı: İçerik ve Dosyalar tek bir dizi içinde gelir (files alt anahtarı ile)
    $content = $class->getKonusmaKulubuContentById($_GET['id']);

    include_once "views/pages-head.php";
?>
    <style>
        /* Genel Stil İyileştirmeleri */

        .main-card-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            border: 1px solid #e0e0e0;
        }

        .custom-card {
            border: none;
            padding: 0px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            background-color: white;
            margin-bottom: 25px;
        }

        .card-title-custom {
            font-size: 1.2rem;
            font-weight: 700;
            color: #ed5606;
            margin-bottom: 15px;
        }

        .content-wrapper {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .icon-small {
            font-size: 50px !important;
            color: #e83e8c !important;
        }



        .btn-custom {
            /* background-color: #1b84ff; */
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 600;
            transition: background-color 0.3s ease;
            margin-top: 15px;
        }

        #myTable thead {
            display: none;
        }

        .btn-custom:hover {
            background-color: #1a9c7b;
        }

        .left-align {
            margin-left: 0;
            margin-right: auto;
        }

        .right-align {
            margin-left: auto;
            margin-right: 0;
        }

        .left-align .card-body {
            align-items: flex-start;
            text-align: left;
        }

        .left-align .content-wrapper {
            flex-direction: row;
        }

        .right-align .card-body {
            align-items: flex-end;
            text-align: right;
        }

        .right-align .content-wrapper {
            flex-direction: row-reverse;
        }

        .card-body {
            display: flex;
            flex-direction: column;
        }

        .bg-custom-light {
            background-color: #e6e6fa;
            /* Light purple */
        }

        .border-custom-red {
            border-color: #d22b2b !important;
        }

        .text-custom-cart {
            color: #6a5acd;
            /* Slate blue for the cart */
        }

        /* For the circular icon, we'll use a larger padding or fixed size */
        .icon-circle-lg {
            width: 65px;
            /* fixed width */
            height: 65px;
            /* fixed height */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-circle-lg img {
            max-width: 100%;
            /* Ensure image scales within the circle */
            max-height: 100%;
        }


        /* Animasyonlar */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

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
                                             border-top border-bottom border-custom-red mb-2" style="border-width: 5px !important; height:85px;">

                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-danger me-3 shadow icon-circle-lg d-flex justify-content-center align-items-center"
                                                        style="width: 65px; height: 65px;">
                                                        <i class="fas fa-bullseye fa-2x text-white"></i>
                                                    </div>

                                                    <h1 class="fs-3 fw-bold text-dark mb-0"><?= $_GET["page_name"] ?? '-' ?></h1>
                                                </div>

                                            </header>
                                        </div>
                                    </div>
                                    <div class="row container-fluid" style="margin-top: -10px;">
                                        <div class="col-md-5">
                                            <div class="month-block" style="
                                            /* Ana arkaplan görseli: Karakter ve ahşap tabela */
                                                background-image: url(uploads/aile-atolyeleri/baslik-sablon.png);
                                                background-repeat: no-repeat;
                                                /* position: relative; */
                                                /* min-height: 340px; */
                                                /* height: 50vh; */
                                                background-size: contain;
                                                /* background-position: center; */
                                                display: flex;
                                                justify-content: center;
                                                align-items: center;
                                                /* width: 90%; */
                                                height: 36vh;
                                                margin: 0rem auto;
                                            ">

                                                <div class="tabela-content" style="
                                                /* position: absolute; */
                                                /* top: 180px; */
                                                /* left: 50%; */
                                                /* height: auto; */
                                                /* text-align: center; */
                                                /* transform: translate(-50%, -50%); */
                                                /* width: 250px; */
                                                /* border-radius: 5px; */
                                                /* overflow: hidden; */
                                                width: 50%;
                                                max-width: 250px;
                                                height: auto;
                                                min-height: 5rem;
                                                color: white;
                                                text-align: center;
                                                padding: 1rem;
                                                font-size: 1.25rem;
                                                font-family: sans-serif;
                                                font-weight: bold;
                                                ">

                                                    <div class="tabela-top-white" style="
                                                        padding: 10px;
                                                        text-align: center;
                                                    ">
                                                        <div class="ikon-ve-baslik" style="display: flex; align-items: center; justify-content: center; flex-wrap: wrap;">

                                                            <div style="flex-grow: 1;">
                                                                <span class="d-block fw-bold" style="font-size: 1.4rem; color: #fff;">
                                                                    <?= htmlspecialchars($content['title']) ?>
                                                                </span>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>

                                            <div style="background-color: #ffbb05;display: flex;column-gap: 20px; font-size: 1.5rem; font-weight: 700;" class="rounded shadow p-3">
                                                <i class="fa-solid fa-calendar-days ps-5" style="color: #000; font-size:2rem !important"></i>
                                                <?= htmlspecialchars($dateFormat->changeDate($content['zoom_date']) . ' ' . $content['zoom_time']) ?>
                                            </div>

                                            <div style="background-color: #ffbb05;display: flex;column-gap: 20px; font-size: 1.5rem; font-weight: 700;" class="rounded shadow p-3 mt-10">
                                                <i class="fa-solid fa-hourglass-half ps-5" style="color: #000; font-size:2rem !important"></i>
                                                <div id="geri_sayim" style="font-weight: bold;">
                                                    <span id="geri_sayim_sure">Kalan Süre</span>
                                                </div>
                                            </div>

                                            <?php
                                            // PHP'de tarih + saat birleştiriliyor
                                            $bitis_tarihi = $content['zoom_date'] . ' ' . $content['zoom_time']; // örn: 2025-11-30 12:00:00
                                            ?>

                                            <script>
                                                // PHP'den gelen tarih-saat bilgisi
                                                var countDownDate = new Date("<?= $bitis_tarihi ?>").getTime();
                                            </script>
                                        </div>

                                        <div class="col-md-7" style="margin-top: 20px;">

                                            <div class="tabela-content" style="
                                                width: 50%; 
                                                border-radius: 5px; 
                                                overflow: hidden; 
                                                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
                                            ">
                                                <div class="tabela-top-white" style="
                                                    background-color: white;
                                                    padding: 10px;
                                                ">
                                                    <div class="ikon-ve-baslik" style="display: flex; align-items: center; justify-content: center; flex-wrap: wrap;">
                                                        <div style="flex-grow: 1;">
                                                            <span class="d-block fw-bold" style="font-size: 2rem; color: #0ab1b9;">
                                                                <i class="fa-solid fa-file-arrow-down" style="font-size: 2rem; color: #0ab1b9; margin-right: 10px"></i>Atölye İçeriği
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="tabela-bottom-green" style="
        background-color: #0ab1b9; 
        padding: 0; /* İç dolguyu linke taşıyoruz */
        text-align: center; /* Butonu ortalamak için */
    ">
                                                    <a href="#" class="btn btn-sm text-white fw-bold p-3 w-100"
                                                        data-bs-toggle="modal" data-bs-target="#filesModal"
                                                        style="font-size: 1rem; background: none; border: none; display: block; height: 100%;">
                                                        İncele
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="tabela-content mt-10" style="
                                                width: 50%; 
                                                border-radius: 5px; 
                                                overflow: hidden; 
                                                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
                                            ">
                                                <div class="tabela-top-white" style="
                                                    background-color: white;
                                                    padding: 10px;
                                                ">
                                                    <div class="ikon-ve-baslik" style="display: flex; align-items: center; justify-content: center; flex-wrap: wrap;">
                                                        <div style="flex-grow: 1;">
                                                            <span class="d-block fw-bold" style="font-size: 2rem; color: #0ab1b9;">
                                                                <i class="fa-solid fa-link" style="font-size: 2rem; color: #0ab1b9; margin-right: 10px"></i>Canlı Bağlantı
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="tabela-bottom-green" style="
                                                    background-color: #0ab1b9; 
                                                    padding: 0; /* İç dolguyu linke taşıyoruz */
                                                    text-align: center; /* Butonu ortalamak için */
                                                ">
                                                    <a href="<?= htmlspecialchars($content['zoom_join_url']) ?>" target="_blank"
                                                        class="btn btn-sm text-white fw-bold p-3 w-100"
                                                        style="font-size: 1rem; background: none; border: none; display: block; height: 100%;">
                                                        Katıl
                                                    </a>
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


        <div class="modal fade" id="filesModal" tabindex="-1" aria-labelledby="filesModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="filesModalLabel">📁 <?= htmlspecialchars($content['title']) ?> Atölye İçerikleri</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group list-group-flush">
                            <?php if (!empty($content['files'])) : ?>
                                <?php foreach ($content['files'] as $file) : ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <?php
                                        $filePath = $file['file_path'];
                                        $fileName = basename($filePath);
                                        $extension = pathinfo($fileName, PATHINFO_EXTENSION);

                                        // Dosya uzantısına göre ikon belirle
                                        $iconClass = 'fas fa-file me-2 text-primary';
                                        if (in_array($extension, ['pdf'])) {
                                            $iconClass = 'fas fa-file-pdf me-2 text-danger';
                                        } elseif (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                                            $iconClass = 'fas fa-file-image me-2 text-success';
                                        } elseif (in_array($extension, ['mp4', 'mov', 'avi'])) {
                                            $iconClass = 'fas fa-file-video me-2 text-warning';
                                        }
                                        ?>

                                        <span><i class="<?= $iconClass ?>"></i> <?= htmlspecialchars($fileName) ?></span>

                                        <a href="<?= htmlspecialchars($filePath) ?>"
                                            class="btn btn-sm btn-light-primary fw-bold"
                                            target="_blank">
                                            <i class="fas fa-eye"></i> Görüntüle
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <li class="list-group-item text-center text-muted">Bu atölyeye ait yüklenmiş dosya bulunmamaktadır.</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                    </div>
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
        <script>
            // Geri Sayım Scripti
            var x = setInterval(function() {
                var now = new Date().getTime();
                var distance = countDownDate - now;

                if (distance < 0) {
                    clearInterval(x);
                    document.getElementById("geri_sayim_sure").innerHTML = "Süre Doldu";
                    return; // Fonksiyondan çık
                }

                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                document.getElementById("geri_sayim_sure").innerHTML =
                    days + "g " + hours + "s " + minutes + "d " + seconds + "sn";
            }, 1000);
        </script>

    </body>

</html>
<?php } else {
    header("location: index");
}
?>