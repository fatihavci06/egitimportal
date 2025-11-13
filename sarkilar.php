<?php
session_start();
define('GUARD', true);

// Yetki Kontrolü
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 10002 or $_SESSION['role'] == 10005)) {
    
    // Veritabanı ve Sınıf dosyalarını dahil et
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";

    $classes = new Classes();
    // Kullanıcının yetkili olduğu derslere ait şarkı listesini çek
    $sarkilar = $classes->getSarkiListByClassId($_SESSION['class_id']);

    /**
     * YouTube URL'sinden Video ID'yi çıkarır.
     * Bu ID, hem küçük resim hem de iframe için gereklidir.
     *
     * @param string $url YouTube URL'si
     * @return string|null Video ID'si veya null
     */
    function getYoutubeId($url) {
        $parts = parse_url($url);
        if (isset($parts['query'])) {
            parse_str($parts['query'], $query);
            if (isset($query['v'])) {
                return $query['v'];
            }
        } elseif (isset($parts['path'])) {
            $path = trim($parts['path'], '/');
            // Embed veya kısa URL (youtu.be) desteği
            if (strpos($path, 'embed/') === 0) {
                $path = substr($path, 6);
            }
            $path_parts = explode('/', $path);
            if (!empty($path_parts[0])) {
                return $path_parts[0];
            }
        }
        return null;
    }

    include_once "views/pages-head.php";
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
                <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                    <?php include_once "views/sidebar.php"; ?>
                    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                        <div class="d-flex flex-column flex-column-fluid">
                            <?php include_once "views/toolbar.php"; ?>
                            <div id="kt_app_content" class="app-content flex-column-fluid">
                                <div id="kt_app_content_container" class="app-container container-fluid">
                                    <div class="row container-fluid" style="margin-top:-25px;">
                                        <header class="container-fluid bg-custom-light py-3 d-flex justify-content-between align-items-center
                                         border-top border-bottom border-custom-red mb-2" style="border-width: 5px !important; height:85px;margin-bottom: 26px !important;background-color: #e6e6fa !important;">

                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-danger me-3 shadow icon-circle-lg d-flex justify-content-center align-items-center"
                                                     style="width: 65px; height: 65px;">
                                                    <i class="fas fa-bullseye fa-2x text-white"></i>
                                                </div>

                                                <h1 class="fs-3 fw-bold text-dark mb-0">Şarkılar</h1>
                                            </div>
                                        </header>
                                    </div>
                                    
                                    <div class="row g-5 g-xl-8">
                                        <?php if (!empty($sarkilar)): ?>
                                            <?php foreach ($sarkilar as $sarki): 
                                                $youtube_id = getYoutubeId($sarki['youtube_url']);
                                                // Yüksek kaliteli küçük resim (maxresdefault)
                                                $thumbnail_url = $youtube_id ? "https://img.youtube.com/vi/{$youtube_id}/maxresdefault.jpg" : 'assets/media/misc/default-video-thumbnail.jpg';
                                                $safe_url = htmlspecialchars($sarki['youtube_url']);
                                            ?>
                                                <div class="col-md-4">
                                                    <div class="card h-100 shadow-sm border-hover">
                                                        <a href="javascript:void(0);" 
                                                           data-bs-toggle="modal" 
                                                           data-bs-target="#kt_modal_view_song" 
                                                           data-id="<?php echo $sarki['id']; ?>" 
                                                           data-url="<?php echo $safe_url; ?>" 
                                                           data-title="<?php echo htmlspecialchars($sarki['title']); ?>" 
                                                           class="song-card-link">
                                                            <div class="card-body p-0">
                                                                <div class="symbol symbol-100px symbol-200px overflow-hidden w-100 rounded-top" style="height: 200px;">
                                                                    <div class="symbol-label w-100" style="background-image:url('<?php echo $thumbnail_url; ?>'); background-size: cover; background-position: center;">
                                                                        
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                        <div class="card-footer d-flex flex-stack flex-wrap p-5">
                                                            <p style="font-size: 13px !important" class="fs-4 fw-bold text-gray-800 me-2 mb-0 text-truncate" title="<?php echo htmlspecialchars($sarki['title']); ?>">
                                                                <?php echo htmlspecialchars($sarki['title']); ?>
                                                            </p>
                                                            <a href="javascript:void(0);" 
                                                               data-bs-toggle="modal" 
                                                               data-bs-target="#kt_modal_view_song" 
                                                               data-id="<?php echo $sarki['id']; ?>" 
                                                               data-url="<?php echo $safe_url; ?>" 
                                                               data-title="<?php echo htmlspecialchars($sarki['title']); ?>" 
                                                               class="btn btn-sm btn-primary fw-bold">
                                                                AÇ
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <div class="col-12">
                                                <div class="alert alert-info text-center">
                                                    Tanımlı dersleriniz için herhangi bir şarkı bulunamadı.
                                                </div>
                                            </div>
                                        <?php endif; ?>
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

        <script src="assets/js/custom/apps/class/list/export.js"></script>
        <script src="assets/js/custom/apps/class/list/list.js"></script>
        <script src="assets/js/widgets.bundle.js"></script>
        
        <script>
            $(document).ready(function() {
                
                // YouTube URL'den ID çeken JavaScript karşılığı
                function getYoutubeIdFromUrl(url) {
                    var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
                    var match = url.match(regExp);
                    if (match && match[2].length === 11) {
                        return match[2];
                    } else {
                        return null;
                    }
                }

                // Modal açıldığında video oynatıcısını yükle
                $('#kt_modal_view_song').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget); 
                    var videoUrl = button.data('url');
                    var videoTitle = button.data('title');
                    
                    var youtubeId = getYoutubeIdFromUrl(videoUrl);
                    var embedHtml = '';
                    
                    if(youtubeId) {
                        // YouTube iframe kodunu oluştur. autoplay=1 ile otomatik oynatmayı başlatır.
                        embedHtml = '<iframe width="100%" height="400" src="https://www.youtube.com/embed/' + youtubeId + '?autoplay=1" title="' + videoTitle + '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                    } else {
                        embedHtml = '<div class="alert alert-danger p-5 text-center">Geçersiz YouTube URL formatı.</div>';
                    }

                    // Başlık ve video içeriğini modal içine yerleştir
                    $(this).find('.modal-title').text(videoTitle);
                    $(this).find('.modal-body-video').html(embedHtml);
                });

                // Modal kapandığında videonun durdurulması için iframe içeriğini temizle
                $('#kt_modal_view_song').on('hidden.bs.modal', function () {
                    $(this).find('.modal-body-video').empty();
                });
            });
        </script>
        </body>

    <div class="modal fade" id="kt_modal_view_song" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Şarkı Başlığı</h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <div class="modal-body modal-body-video p-0">
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Kapat</button>
                </div>
            </div>
        </div>
    </div>
    </html>
<?php
} else {
    // Yetki yoksa dashboard'a yönlendir
    header("location: index");
}
// Dosyanın sonunda temiz çıktı sağlamak için PHP kapanış etiketi kullanmıyoruz.