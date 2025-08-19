<!DOCTYPE html>
<html lang="tr">

<?php

session_start();
define('GUARD', true);


if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 10001 or $_SESSION['role'] == 10002 or $_SESSION['role'] == 10005)) {

    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";
    require_once "classes/student.classes.php";

    include_once "views/pages-head.php";

    $data = new Classes();
    $studentInfo = new Student();

    if ($_SESSION['role'] == 10005) {
        $getPreSchoolStudent = $studentInfo->getPreSchoolStudentsInfoForParents($_SESSION['id']);
        $studentidsi = $getPreSchoolStudent[0]['id'];
    } else {
        $studentidsi = $_SESSION['id'];
    }

    if ($_SESSION['role'] == 10002 OR $_SESSION['role'] == 10005) {

        $contentId = $_GET['id'];
        $exists = $data->permissionControl($contentId, $studentidsi);
        if (!$exists) {
            // Kullanıcının yetkisi yoksa login sayfasına yönlendir
            header("Location: ana-okulu-icerikler");
            exit;
        }
    }

    ?>
    <?php

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
            /*  background-color: #1b84ff; */
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 600;
            transition: background-color 0.3s ease;
            margin-top: 15px;
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
            width: 60px;
            /* fixed width */
            height: 60px;
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
                <?php include_once "views/header.php"; ?>


                <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrappera">
                    <?php include_once "views/sidebar.php"; ?>

                    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                        <div class="d-flex flex-column flex-column-fluid">
                            <?php include_once "views/toolbar.php"; ?>
                            <?php

                            $data = $data->getMainSchoolContentById($_GET['id']);

                            /**
                             * Converts YouTube and Vimeo URLs to embeddable formats.
                             *
                             * @param string $url The URL of the video.
                             * @return string|null The embed URL or null if not a valid YouTube/Vimeo link.
                             */
                            function convertToEmbedUrl($url)
                            {
                                // YouTube URL'sini işleme
                                if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $matches)) {
                                    return 'https://www.youtube.com/embed/' . $matches[1];
                                }
                                // Vimeo URL'sini işleme
                                if (preg_match('/vimeo\.com\/(\d+)\/?([a-zA-Z0-9]+)?/', $url, $matches)) {
                                    $vimeoId = $matches[1];
                                    // İkinci yakalama grubunun (hash) varlığını kontrol et
                                    $vimeoHash = isset($matches[2]) && !empty($matches[2]) ? $matches[2] : '';

                                    $embedUrl = 'https://player.vimeo.com/video/' . $vimeoId;
                                    if (!empty($vimeoHash)) {
                                        $embedUrl .= '?h=' . $vimeoHash;
                                    }
                                    return $embedUrl;
                                }
                                return null; // Geçerli bir YouTube veya Vimeo linki değilse null döndür
                            }
                            ?>

                            <div id="kt_app_content" class="app-content flex-column-fluid">

                                <div id="kt_app_content_container" class="app-container2 container-fluid"
                                    style="margin-top: -40px">
                                    <div class="card-body col-12 row pt-5">
                                        <header class="container-fluid bg-custom-light py-3 d-flex justify-content-between align-items-center
                                             border-top border-bottom border-custom-red "
                                            style="border-width: 5px !important; height:85px;margin-bottom: 26px !important;">

                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-danger me-3 shadow icon-circle-lg d-flex justify-content-center align-items-center"
                                                    style="width: 65px; height: 65px;">
                                                    <i class="fas fa-bullseye fa-2x text-white"></i>
                                                </div>

                                                <h1 class="fs-3 fw-bold text-dark mb-0"><?= $data['subject']; ?></h1>
                                            </div>

                                        </header>
                                        <div class="row">
                                            <p><?= $data['content_description']; ?></p>
                                        </div>
                                        <div class="tab-content " id="myTabContent">
                                            <div class="tab-pane fade show active" id="video" role="tabpanel"
                                                aria-labelledby="video-tab">
                                                <div class="row">
                                                    <?php

                                                    if (isset($data['images']) && count($data['images']) > 0) {
                                                        foreach ($data['images'] as $img): ?>
                                                            <div class="col-md-12 mb-4">
                                                                <div class="card shadow-sm">
                                                                    <div class="card-body p-0" style="height: 700px;">
                                                                        <img src="<?= $img['file_path'] ?>" alt="Yüklenen Görsel"
                                                                            class="w-100 h-100 rounded shadow"
                                                                            style="object-fit: cover;">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endforeach;
                                                    } ?>
                                                </div>

                                                <div class="row mt-4" id="videoContent"
                                                    style="justify-content: center; margin-top: -35px !important;">
                                                    <div class="video-responsive" style="background: none; max-width: 95%;">

                                                        <?php

                                                        $videoUrl = $data['video_url'] ?? ''; // video_url'nin tanımlı olduğundan emin olun
                                                        $embedUrl = convertToEmbedUrl($videoUrl);

                                                        if ($embedUrl):
                                                            // YouTube veya Vimeo iframe özelliklerini ayarla
                                                            $iframeProperties = 'width="80%" height="600px" frameborder="0" allowfullscreen';
                                                            if (strpos($embedUrl, 'youtube.com') !== false) { // YouTube'a özgü izinler
                                                                $iframeProperties .= ' allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin"';
                                                            } elseif (strpos($embedUrl, 'vimeo.com') !== false) { // Vimeo'ya özgü izinler
                                                                $iframeProperties .= ' allow="autoplay; fullscreen; picture-in-picture"';
                                                            }
                                                            ?>
                                                            <iframe id="<?= $data['id'] ?>"
                                                                src="<?= htmlspecialchars($embedUrl, ENT_QUOTES, 'UTF-8') ?>"
                                                                title="Video player" <?= $iframeProperties ?>>
                                                            </iframe>

                                                        <?php else: ?>
                                                            <p>Video bulunamadı veya geçersiz bir video linki.</p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="row mt-4">
                                                    <?php

                                                    if (isset($data['wordwalls']) && count($data['wordwalls']) > 0) {
                                                        foreach ($data['wordwalls'] as $wordWall): ?>
                                                            <div class="col-md-12 mb-4">
                                                                <div class="card shadow-sm">
                                                                    <h3 class="card-title mt-4 mb-4">
                                                                        <?= $wordWall['wordwall_title']; ?>
                                                                    </h3>
                                                                    <div class="card-body p-0" style="height: 400px;">
                                                                        <iframe style="max-width:100%"
                                                                            src="<?= $wordWall['wordwall_url'] ?>" width="100%"
                                                                            height="100%" frameborder="0" allowfullscreen></iframe>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endforeach;
                                                    } ?>
                                                </div>
                                                <div class="row " style="font-size:17px; margin-top:35px;">
                                                    <p id="contentDescription">
                                                        <?php
                                                        if (isset($data['content'])) {
                                                            echo $data['content'];
                                                        }
                                                        ?>

                                                    </p>
                                                </div>

                                                <div class="row" id="files" role="tabpanel" aria-labelledby="files-tab">
                                                    <?php
                                                    if (!empty($data['files'])) {
                                                        ?>
                                                        <h1 style="margin-top:50px;margin-bottom:30px">Dosyalar</h1>
                                                        <?php foreach ($data['files'] as $file): ?>
                                                            <div class="col-md-12 mb-4" style="font-size: 20px;">
                                                                <div class="card shadow-sm">
                                                                    <div class="card-body">
                                                                        <h5 class="card-title">
                                                                            <a href="<?= $file['file_path'] ?>" target="_blank"
                                                                                class="text-decoration-none text-primary">
                                                                                <i class="bi bi-file-earmark"></i>
                                                                                <?= basename($file['file_path']) ?>
                                                                            </a>
                                                                        </h5>

                                                                        <p><strong>Açıklama:</strong>
                                                                            <?= htmlspecialchars($file['description'] ?? 'Açıklama mevcut değil.') ?>
                                                                        </p>

                                                                        <div class="d-flex justify-content-between">
                                                                            <a href="<?= $file['file_path'] ?>"
                                                                                class="btn btn-primary btn-sm" target="_blank">
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


        <script src="https://player.vimeo.com/api/player.js"></script>
        <script src="assets/js/custom/trackTimeOnVimeo.js"></script>
        <script src="assets/js/custom/contentTracker.js"></script>


        <script src="assets/plugins/global/plugins.bundle.js"></script>
        <script src="assets/js/scripts.bundle.js"></script>
        <script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
        <script src="assets/plugins/custom/tinymce/tinymce.bundle.js"></script>
        <!-- <script src="assets/js/custom/apps/subtopics/list/export.js"></script>
        <script src="assets/js/custom/apps/subtopics/list/list.js"></script>
        <script src="assets/js/custom/apps/subtopics/list/topicadd.js"></script>
        <script src="assets/js/custom/apps/subtopics/add.js"></script>
        <script src="assets/js/custom/apps/subtopics/create.js"></script> -->
        <script src="assets/js/widgets.bundle.js"></script>
        <script src="assets/js/custom/widgets.js"></script>
        <script src="assets/js/custom/apps/chat/chat.js"></script>
        <script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
        <script src="assets/js/custom/utilities/modals/create-account.js"></script>
        <script src="assets/js/custom/utilities/modals/create-app.js"></script>
        <script src="assets/js/custom/utilities/modals/users-search.js"></script>


    </body>

    </html>
<?php } else {
    header("location: index");
}
