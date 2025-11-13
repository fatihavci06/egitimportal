<?php
// PHP Sınıf Dahil Etme ve Veri Çekme İşlemleri
session_start();
define('GUARD', true);

// Rol Kontrolü 
// Yetkili roller: 1, 10001, 10002, 10005. Yetkisizse yönlendirilir.
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 10001 or $_SESSION['role'] == 10002 or $_SESSION['role'] == 10005)) {

    // Gerekli dosyaları dahil et
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";
    include_once "views/pages-head.php"; // Head içeriği

    // ----------------------------------------------------
    // KONTROL VE FİLTRELEME
    // ----------------------------------------------------
    $targetContentType = "Sanat Atölyesi";
    $pageTitle = $targetContentType;
    $pageIcon = "fas fa-bullseye"; // Kullanılan ikon

    // Kullanıcının tekil class_id'sini SESSION'dan al
    $userClassId = $_SESSION['class_id'] ?? null;

    $atolyeContents = [];
    $allWordwallLinks = [];
    $allFilesAndImages = [];
    $classesObj = null;

    if (!empty($userClassId)) {
        // Verileri çek
        $classesObj = new Classes();

        // 1. Kartlar için ana içerikleri çek (class_ids ; ile ayrılmış olsa bile filtrelenmiş olarak gelir)
        $atolyeContents = $classesObj->getAtolyeContentsByTypeAndClass($targetContentType, $userClassId);

        // 2. TÜM Wordwall linklerini çek (Liste Modal için)
        $allWordwallLinks = $classesObj->getAllWordwallLinksByContentTypeAndClass($targetContentType, $userClassId);

        // 3. TÜM Dosya/Görsel yollarını çek (Liste Modal için)
        $allFilesAndImages = $classesObj->getAllFilesAndImagesByContentTypeAndClass($targetContentType, $userClassId);
        // 3. TÜM Dosya/Görsel yollarını çek (Liste Modal için)
        $zoomDetail = $classesObj->getZoomDetail($userClassId, $targetContentType);
    }
    // ----------------------------------------------------
?>
    <!DOCTYPE html>
    <html lang="tr">

    <head>
        <style>
            /* Sadece gerekli stil tanımları bırakıldı */
            .icon-circle-lg {
                width: 65px;
                height: 65px;
                display: flex;
                justify-content: center;
                align-items: center;
                border-radius: 50%;
                background-color: #dc3545;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            }

            .ratio-16x9 {
                position: relative;
                width: 100%;
                padding-bottom: 56.25%;
                height: 0;
            }

            .ratio-16x9 iframe {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
            }

            .hover-effect {
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .hover-effect:hover {
                transform: translateY(-5px);
                box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15) !important;
            }

            .bg-custom-light {
                background-color: #f8f9fa;
            }

            .border-custom-red {
                border-color: #dc3545 !important;
            }

            .tabela-content {
                width: 100%;
                border-radius: 5px;
                overflow: hidden;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                /* margin-bottom: 20px; */
                /* Bootstrap mb-3 kullanıldığı için burayı kapattık */
                cursor: pointer;
                transition: box-shadow 0.3s;
            }

            .tabela-content:hover {
                box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
            }

            .tabela-bottom-green {
                background-color: #0ab1b9;
                padding: 5px 10px;
                text-align: left;
            }
        </style>
    </head>

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
 border-top border-bottom border-custom-red mb-2" style="border-width: 5px !important; height:85px;margin-bottom: 26px !important;background-color: #e6e6fa !important;">

                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-danger me-3 shadow icon-circle-lg d-flex justify-content-center align-items-center"
                                                        style="width: 65px; height: 65px;">
                                                        <i class="<?= $pageIcon ?> fa-2x text-white"></i>
                                                    </div>
                                                    <h1 class="fs-3 fw-bold text-dark mb-0"><?= htmlspecialchars($pageTitle) ?></h1>
                                                </div>

                                            </header>
                                        </div>
                                    </div>

                                    <div class="row container-fluid mb-5">

                                        <div class="col-md-3 mb-4">
                                            <img src="uploads/atolyeler/sanat-atolyesi.png" style="max-width: 100%; border-radius: 10px;">
                                        </div>

                                        <div class="col-md-3 mb-4">

                                            <div class="tabela-content mb-3" data-bs-toggle="modal" data-bs-target="#fileListModal">
                                                <div class="tabela-top-white" style="background-color: white; padding: 10px;">
                                                    <div class="ikon-ve-baslik" style="display: flex; align-items: center; justify-content: center; flex-wrap: wrap;">
                                                        <div style="flex-grow: 1;">
                                                            <span class="d-block fw-bold" style="font-size: 1.5rem; color: #0ab1b9;">
                                                                <i class="fa-solid fa-file-arrow-down" style="font-size: 1.5rem; color: #0ab1b9; margin-right: 10px"></i>Yazdırılabilir İçerik
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tabela-bottom-green">
                                                    <span class="text-white fw-bold" style="font-size: 1rem;">İncele (<?= count($allFilesAndImages) ?>)</span>
                                                </div>
                                            </div>

                                            <div class="tabela-content mb-3" data-bs-toggle="modal" data-bs-target="#wordwallListModal">
                                                <div class="tabela-top-white" style="background-color: white; padding: 10px;">
                                                    <div class="ikon-ve-baslik" style="display: flex; align-items: center; justify-content: center; flex-wrap: wrap;">
                                                        <div style="flex-grow: 1;">
                                                            <span class="d-block fw-bold" style="font-size: 1.5rem; color: #0ab1b9;">
                                                                <i class="fa-solid fa-chess-board" style="font-size: 1.5rem; color: #0ab1b9; margin-right: 10px"></i>Wordwall
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tabela-bottom-green">
                                                    <span class="text-white fw-bold" style="font-size: 1rem;">İncele (<?= count($allWordwallLinks) ?>)</span>
                                                </div>
                                            </div>

                                            <?php if (!empty($zoomDetail)): ?>
                                                <a class="tabela-content mb-3 d-block text-decoration-none"
                                                    href="<?php echo htmlspecialchars($zoomDetail['zoom_url'] ?? '#'); ?>"
                                                    target="_blank"
                                                    style="cursor: pointer; color: inherit;">

                                                    <div class="tabela-top-white" style="background-color: #f8f9fa; padding: 15px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                                                        <div class="ikon-ve-baslik" style="display: flex; align-items: center; flex-wrap: wrap;">
                                                            <div style="flex-grow: 1;">
                                                                <span class="d-block fw-bold" style="font-size: 1.8rem; color: #007bff; display: flex; align-items: center;">
                                                                    <i class="fa-solid fa-video me-2" style="font-size: 1.8rem; color: #007bff; margin-right: 12px;"></i>
                                                                    Canlı Ders
                                                                </span>
                                                                <hr style="border-top: 1px solid #dee2e6; margin-top: 10px; margin-bottom: 10px;">

                                                                <span class="d-block mt-2" style="font-size: 1.1rem; color: #343a40;">
                                                                    **📅 Tarih:** <?php
                                                                                    $date = $zoomDetail['zoom_date'] ?? 'Tarih Bilgisi Yok';
                                                                                    // Tarih bilgisini d-m-Y formatına çevir (Varsayım: $date Y-m-d formatında geliyor)
                                                                                    echo ($date !== 'Tarih Bilgisi Yok') ? htmlspecialchars(date('d-m-Y', strtotime($date))) : $date;
                                                                                    ?>
                                                                </span>

                                                                <span class="d-block" style="font-size: 1.1rem; color: #343a40; margin-top: 5px;">
                                                                    **🕒 Saat:** <?php echo htmlspecialchars($zoomDetail['zoom_time'] ?? 'Saat Bilgisi Yok'); ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="tabela-bottom-green" style="background-color: #dc3545; padding: 5px;">
                                                        <span class="text-white fw-bold" style="font-size: 1rem;">Derse Git</span>
                                                    </div>
                                                </a>
                                            <?php endif; ?>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="row">
                                                <?php
                                                if (!empty($atolyeContents)):
                                                    foreach ($atolyeContents as $content):

                                                        // Wordwall, Dosya/Görsel içeriklerini atla
                                                        if ($content['secim_type'] == 'wordwall' || $content['secim_type'] == 'file_path') {
                                                            continue;
                                                        }

                                                        // Varsayılan değerler
                                                        $iconClass = 'fa-solid fa-file-lines';
                                                        $defaultImage = 'uploads/contents/konuDefault.jpg';
                                                        $contentTypeText = 'İçeriği Oku';
                                                        $actionUrl = "ana-okulu-icerik-detay.php?id=" . $content['id'];
                                                        $buttonText = 'Aç';
                                                        $isEmbedModal = false;

                                                        // İçerik tipine göre aksiyonları belirle
                                                        if ($content['secim_type'] == 'video_link') {
                                                            $iconClass = 'bi-play-circle-fill';
                                                            $contentTypeText = 'Eğitim Videosu';
                                                            $buttonText = 'Video İzle';
                                                            $isEmbedModal = true;
                                                            $actionUrl = $content['video_url'] ? $content['video_url'] : "#";
                                                        } elseif ($content['secim_type'] == 'content') {
                                                            $iconClass = 'fa-solid fa-file-lines';
                                                            $contentTypeText = 'Yazılı İçerik';
                                                            $defaultImage = 'uploads/contents/textDefault.jpg';
                                                            $buttonText = 'Oku';
                                                        }

                                                        // Her kart 6 sütun kaplayarak (ana 6 sütun içinde) 2'şerli görünmesini sağlar.
                                                ?>
                                                        <div class="col-md-6 mb-4">
                                                            <?php if (!$isEmbedModal && $actionUrl != '#'): ?>
                                                                <a href="<?= htmlspecialchars($actionUrl) ?>" class="text-decoration-none d-block h-100">
                                                                <?php endif; ?>

                                                                <div class="card h-100 shadow-sm border-0 hover-effect 
 <?= $isEmbedModal ? 'openEmbedModal' : '' ?>"
                                                                    data-bs-toggle="<?= $isEmbedModal ? 'modal' : '' ?>"
                                                                    data-bs-target="<?= $isEmbedModal ? '#embedModal' : '' ?>"
                                                                    data-embed-url="<?= $isEmbedModal ? htmlspecialchars($actionUrl) : '' ?>"
                                                                    data-subject="<?= htmlspecialchars($content['subject']) ?>"
                                                                    data-type="<?= $content['secim_type'] == 'video_link' ? 'Video' : 'Content' ?>">

                                                                    <div class="d-flex justify-content-center align-items-center" style="height: 180px; 
 background-image: url('<?= htmlspecialchars($defaultImage) ?>'); 
 background-size: cover; background-position: center; 
 border-top-left-radius: .375rem; border-top-right-radius: .375rem;">
                                                                        <i class="<?= $iconClass ?> text-white" style="font-size: 50px; opacity: 0.9;"></i>
                                                                    </div>

                                                                    <div class="card-body d-flex flex-column">
                                                                        <h5 class="card-title fw-bold text-dark mb-1" style="font-size: 16px;">
                                                                            <?= htmlspecialchars($content['subject']) ?>
                                                                        </h5>
                                                                        <p class="card-text text-muted mb-3" style="font-size: 14px;"><?= $contentTypeText ?></p>

                                                                        <div class="mt-auto d-flex justify-content-start">

                                                                            <?php
                                                                            $buttonStyle = "padding: 8px 28px; font-size: 14px; border-radius: 999px; text-decoration: none; transition: background-color 0.3s; background-color: rgb(43, 140, 1); color: white !important; border: 1px solid rgb(43, 140, 1) !important;";
                                                                            $hoverStyle = "onmouseover=\"this.style.backgroundColor='#ed5606'\" onmouseout=\"this.style.backgroundColor='#2b8c01'\"";

                                                                            if ($isEmbedModal): // Video için Modal Butonu
                                                                            ?>
                                                                                <button type="button" class="btn openEmbedModal" data-bs-toggle="modal" data-bs-target="#embedModal"
                                                                                    data-embed-url="<?= htmlspecialchars($actionUrl) ?>"
                                                                                    data-subject="<?= htmlspecialchars($content['subject']) ?>"
                                                                                    data-type="Video"
                                                                                    style="<?= $buttonStyle ?>" <?= $hoverStyle ?>>
                                                                                    <?= $buttonText ?>
                                                                                </button>

                                                                            <?php else: // Link (Detay, Dosya veya Wordwall Liste)
                                                                            ?>
                                                                                <a href="<?= htmlspecialchars($actionUrl) ?>"
                                                                                    style="<?= $buttonStyle ?>" <?= $hoverStyle ?>>
                                                                                    <?= $buttonText ?>
                                                                                </a>
                                                                            <?php endif; ?>

                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <?php if (!$isEmbedModal && $actionUrl != '#'): ?>
                                                                </a>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endforeach;
                                                else: ?>
                                                    <div class="col-12">
                                                        <div class="alert alert-info text-center">
                                                            Bu atölye türüne ve sizin sınıfınıza ait henüz içerik bulunmamaktadır.
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
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

        <div class="modal fade" id="embedModal" tabindex="-1" aria-labelledby="embedModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="embedModalLabel">İçerik Görüntüleyici</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="ratio ratio-16x9">
                            <iframe id="embedFrame" src="" title="İçerik Oynatıcı" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="wordwallListModal" tabindex="-1" aria-labelledby="wordwallListModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="wordwallListModalLabel">Tüm Wordwall Oyunları</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php if (!empty($allWordwallLinks)): ?>
                            <ul class="list-group">
                                <?php foreach ($allWordwallLinks as $link): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fa-solid fa-gamepad me-2 text-danger"></i>
                                            <strong><?= htmlspecialchars($link['subject']) ?>:</strong> <?= htmlspecialchars($link['title'] ?? 'Oyun Bağlantısı') ?>
                                        </div>
                                        <button type="button"
                                            class="btn btn-sm btn-danger openWordwallEmbedInModal"
                                            data-bs-toggle="modal"
                                            data-bs-target="#embedModal"
                                            data-embed-url="<?= htmlspecialchars($link['url']) ?>"
                                            data-subject="<?= htmlspecialchars($link['subject']) ?>"
                                            data-type="Wordwall">
                                            Oyna
                                        </button>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <div class="alert alert-info">Bu sınıfa ait aktif Wordwall oyunu bulunmamaktadır.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="fileListModal" tabindex="-1" aria-labelledby="fileListModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="fileListModalLabel">Tüm Dosyalar ve Görseller</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php if (!empty($allFilesAndImages)): ?>
                            <ul class="list-group">
                                <?php foreach ($allFilesAndImages as $file): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <?php $icon = ($file['type'] == 'file') ? 'fa-solid fa-file-arrow-down' : 'fa-solid fa-image'; ?>
                                            <i class="<?= $icon ?> me-2 text-primary"></i>
                                            <strong><?= htmlspecialchars($file['subject']) ?>:</strong> <?= htmlspecialchars($file['description'] ?? basename($file['file_path'])) ?>
                                        </div>
                                        <a href="<?= htmlspecialchars($file['file_path']) ?>" target="_blank" class="btn btn-sm btn-primary"><?= ($file['type'] == 'file') ? 'İndir' : 'Görüntüle' ?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <div class="alert alert-info">Bu sınıfa ait aktif dosya veya görsel bulunmamaktadır.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="zoomDetailModal" tabindex="-1" aria-labelledby="zoomDetailModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="zoomDetailModalLabel">Canlı Ders Bilgileri</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php if (!empty($zoomDetail)): ?>
                            <ul class="list-group list-group-flush">
                                <?php if (isset($zoomDetail['title'])): ?>
                                    <li class="list-group-item"><strong>Konu:</strong> <?= htmlspecialchars($zoomDetail['title']) ?></li>
                                <?php endif; ?>
                                <?php if (isset($zoomDetail['date']) && isset($zoomDetail['time'])): ?>
                                    <li class="list-group-item"><strong>Tarih/Saat:</strong> <?= htmlspecialchars($zoomDetail['date'] . ' ' . $zoomDetail['time']) ?></li>
                                <?php endif; ?>
                                <?php if (isset($zoomDetail['meeting_id'])): ?>
                                    <li class="list-group-item"><strong>Meeting ID:</strong> <?= htmlspecialchars($zoomDetail['meeting_id']) ?></li>
                                <?php endif; ?>
                                <?php if (isset($zoomDetail['password'])): ?>
                                    <li class="list-group-item"><strong>Şifre:</strong> <?= htmlspecialchars($zoomDetail['password']) ?></li>
                                <?php endif; ?>
                                <?php if (isset($zoomDetail['zoom_link'])): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <strong>Bağlantı:</strong>
                                        <a href="<?= htmlspecialchars($zoomDetail['zoom_link']) ?>" target="_blank" class="btn btn-sm" style="background-color: #dc3545; color: white;">Derse Git</a>
                                    </li>
                                <?php endif; ?>
                                <?php if (empty($zoomDetail['zoom_link']) && empty($zoomDetail['meeting_id'])): ?>
                                    <div class="alert alert-warning mt-3">Canlı ders bilgisi mevcut ancak giriş bağlantısı veya ID'si belirtilmemiş.</div>
                                <?php endif; ?>
                            </ul>
                        <?php else: ?>
                            <div class="alert alert-info">Bu sınıfa ait aktif canlı ders bilgisi bulunmamaktadır.</div>
                        <?php endif; ?>
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
        <script>
            $(document).ready(function() {

                // Embed Modal açıldığında (Video ve Wordwall için ortak)
                $('.openEmbedModal').on('click', function() {
                    var $target = $(this).is('button') ? $(this) : $(this); // Tıklanan öğeyi bul
                    var embedUrl = $target.data('embed-url');
                    var subject = $target.data('subject');
                    var type = $target.data('type');
                    var finalUrl = embedUrl;

                    // Wordwall için URL'i direkt kullan
                    if (type === 'Wordwall') {
                        $('#embedModalLabel').text(subject + ' - Wordwall Oyunu');
                    }
                    // Video için URL'i embed formatına çevirme (Daha güvenli yöntem)
                    else {
                        var videoId = null;

                        // 1. Uzun format için Video ID'sini bul: ?v=ID
                        // Hem ?v=ID hem de &v=ID durumlarını kapsar.
                        var watchMatch = embedUrl.match(/[?&]v=([^&]+)/);
                        if (watchMatch) {
                            videoId = watchMatch[1];
                        }

                        // 2. Kısa format için Video ID'sini bul: youtu.be/ID
                        if (!videoId && embedUrl.includes("youtu.be/")) {
                            var shortMatch = embedUrl.match(/youtu\.be\/([^?&#]+)/);
                            if (shortMatch) {
                                videoId = shortMatch[1];
                            }
                        }

                        if (videoId) {
                            // Güvenli embed URL'i oluştur: https://www.youtube.com/embed/VIDEO_ID?autoplay=1
                            finalUrl = 'https://www.youtube.com/embed/' + videoId + '?autoplay=1';
                        } else {
                            // Eğer video ID'si bulunamazsa (belki zaten embed linki veya hatalı),
                            // orijinal URL'i kullan ve autoplay ekle
                            finalUrl = embedUrl;

                            if (!finalUrl.includes("?")) {
                                finalUrl += '?autoplay=1';
                            } else {
                                finalUrl += '&autoplay=1';
                            }
                        }

                        $('#embedModalLabel').text(subject + ' - Eğitim Videosu');
                    }

                    $('#embedFrame').attr('src', finalUrl); // iframe'i ayarla
                });

                // ----------------------------------------------------------------------
                // YENİ EKLEME: Wordwall Listesi içinden iframe ile açılma
                // ----------------------------------------------------------------------
                $('#wordwallListModal').on('click', '.openWordwallEmbedInModal', function() {
                    var embedUrl = $(this).data('embed-url');
                    var subject = $(this).data('subject');

                    // 1. Wordwall List Modal'ı kapat
                    var wordwallListModalElement = document.getElementById('wordwallListModal');
                    var wordwallListModal = bootstrap.Modal.getInstance(wordwallListModalElement);
                    if (wordwallListModal) {
                        wordwallListModal.hide();
                    }

                    // 2. Embed Modal içeriğini ayarla
                    $('#embedModalLabel').text(subject + ' - Wordwall Oyunu');
                    // Wordwall linkleri genellikle tam link olduğundan ek bir çevrim gerekmez.
                    $('#embedFrame').attr('src', embedUrl);

                    // 3. Embed Modal'ın açılması data-bs-toggle ile otomatik olarak tetiklenmelidir.
                });

                // Embed Modal kapandığında
                $('#embedModal').on('hidden.bs.modal', function() {
                    $('#embedFrame').attr('src', ''); // İçeriği durdur
                });
            });
        </script>
    </body>

    </html>
<?php } else {
    // Giriş yapılmamış veya yetkisizse yönlendir
    header("location: index");
    exit;
}
?>