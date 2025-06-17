<!DOCTYPE html>
<html lang="tr">

<?php

session_start();
define('GUARD', true);


if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 10001 or $_SESSION['role'] == 10002)) {

    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";

    include_once "views/pages-head.php";

    $data = new Classes();
    if ($_SESSION['role'] == 10002) {

        $contentId = $_GET['id'];
        $exists = $data->permissionControl($contentId, $_SESSION['id']);
        if (!$exists) {
            // Kullanıcının yetkisi yoksa login sayfasına yönlendir
            header("Location: ana-okulu-icerikler");
            exit;
        }
    }

?>
<?php

?>
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
                                if (preg_match('/(?:www\.|player\.)?vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/\d+\/video\/|video\/|)(\d+)(?:[?&]t=\d+)?/', $url, $matches)) {
                                    return 'https://player.vimeo.com/video/' . $matches[2];
                                }
                                return null; // Geçerli bir YouTube veya Vimeo linki değilse null döndür
                            }
                            ?>

                            <div id="kt_app_content" class="app-content flex-column-fluid">

                                <div id="kt_app_content_container" class="app-container container-fluid">
                                    <div class="card-body col-10 container pt-5">
                                        <h1><?= $data['subject']; ?></h1>
                                        <div class="row">
                                            <p><?= $data['content_description']; ?></p>
                                        </div>
                                        <div class="tab-content mt-4" id="myTabContent">
                                            <div class="tab-pane fade show active" id="video" role="tabpanel" aria-labelledby="video-tab">
                                                <div class="row">
                                                    <?php

                                                    if (isset($data['images']) && count($data['images']) > 0) {
                                                        foreach ($data['images'] as $img) : ?>
                                                            <div class="col-md-12 mb-4">
                                                                <div class="card shadow-sm">
                                                                    <div class="card-body p-0" style="height: 700px;">
                                                                        <img src="<?= $img['file_path'] ?>" alt="Yüklenen Görsel" class="w-100 h-100 rounded shadow" style="object-fit: cover;">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                    <?php endforeach;
                                                    } ?>
                                                </div>
                                                <div class="row mt-4" id="videoContent">
                                                    <?php
                                                    $videoUrl = $data['video_url'] ?? ''; // video_url'nin tanımlı olduğundan emin olun
                                                    $embedUrl = convertToEmbedUrl($videoUrl);

                                                    if ($embedUrl) :
                                                        // YouTube veya Vimeo iframe özelliklerini ayarla
                                                        $iframeProperties = 'width="100%" height="400" frameborder="0" allowfullscreen';
                                                        if (strpos($embedUrl, 'youtube.com') !== false) { // YouTube'a özgü izinler
                                                            $iframeProperties .= ' allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin"';
                                                        } elseif (strpos($embedUrl, 'vimeo.com') !== false) { // Vimeo'ya özgü izinler
                                                            $iframeProperties .= ' allow="autoplay; fullscreen; picture-in-picture"';
                                                        }
                                                    ?>
                                                        <iframe src="<?= htmlspecialchars($embedUrl, ENT_QUOTES, 'UTF-8') ?>" title="Video player" <?= $iframeProperties ?>>
                                                        </iframe>
                                                    <?php else : ?>
                                                        <p>Video bulunamadı veya geçersiz bir video linki.</p>
                                                    <?php endif; ?>

                                                </div>
                                                <div class="row mt-4">
                                                    <?php

                                                    if (isset($data['wordwalls']) && count($data['wordwalls']) > 0) {
                                                        foreach ($data['wordwalls'] as $wordWall) : ?>
                                                            <div class="col-md-12 mb-4">
                                                                <div class="card shadow-sm">
                                                                    <h3 class="card-title mt-4 mb-4"><?= $wordWall['wordwall_title']; ?></h3>
                                                                    <div class="card-body p-0" style="height: 400px;">
                                                                        <iframe style="max-width:100%" src="<?= $wordWall['wordwall_url'] ?>" width="100%" height="100%" frameborder="0" allowfullscreen></iframe>
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
                                                    if (isset($data['files'])) {
                                                    ?>
                                                        <h1 style="margin-top:50px;margin-bottom:30px">Dosyalar</h1>
                                                        <?php foreach ($data['files'] as $file) : ?>
                                                            <div class="col-md-12 mb-4" style="font-size: 20px;">
                                                                <div class="card shadow-sm">
                                                                    <div class="card-body">
                                                                        <h5 class="card-title">
                                                                            <a href="<?= $file['file_path'] ?>" target="_blank" class="text-decoration-none text-primary">
                                                                                <i class="bi bi-file-earmark"></i> <?= basename($file['file_path']) ?>
                                                                            </a>
                                                                        </h5>

                                                                        <p><strong>Açıklama:</strong> <?= htmlspecialchars($file['description'] ?? 'Açıklama mevcut değil.') ?></p>

                                                                        <div class="d-flex justify-content-between">
                                                                            <a href="<?= $file['file_path'] ?>" class="btn btn-primary btn-sm" target="_blank">
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


        </body>
    </html>
<?php } else {
    header("location: index");
}