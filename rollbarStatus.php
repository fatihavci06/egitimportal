<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);

if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 3 or $_SESSION['role'] == 4)) {
    include_once "classes/dbh.classes.php";
    include_once "classes/suspicious-logs.classes.php";
    include_once "classes/suspicious-logs-view.classes.php";

    // ==========================================================
    // ⚠️ ROLLBAR API MANTIĞI BURADA BAŞLIYOR
    // ==========================================================
    
    // Güvenlik Uyarısı: Bu Jeton sadece test amaçlıdır. Canlı sistemde ENV değişkeni kullanın.
    $readAccessToken = 'e68bb67f223f4f5d859149e536a3e3345004a5020158a37de3c193efadf21e260a2c44f7a1b6c8d8bfa39034d084d794'; 
    $apiUrlBase = 'https://api.rollbar.com/api/1/items'; 
    $items = [];
    $hasMore = false; 
    $httpCode = null;

    // 2. Sayfa Numarasını Alma ve API URL'sini Oluşturma
    $currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1; 
    $limit = 20; // Hızı artırmak için 20'ye düşürüldü

    $queryParameters = http_build_query([
        'status' => 'active',                   
        'level' => ['error', 'critical'],       
        'page' => $currentPage, 
        'limit' => $limit,      
    ]);

    $fullUrl = $apiUrlBase . '?' . $queryParameters;

    // 3. cURL İsteği ve Yanıt Alma
    $ch = curl_init($fullUrl);
    $headers = [
        'Accept: application/json', 
        'X-Rollbar-Access-Token: ' . $readAccessToken, 
    ];
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); 

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch); 
    curl_close($ch);

    // 4. Yanıtı İşleme
    if (!$curlError && $httpCode === 200) {
        $data = json_decode($response, true);
        if (isset($data['result']['items']) && is_array($data['result']['items'])) {
            $items = $data['result']['items'];
            $itemCount = count($items);
            if ($itemCount >= $limit) {
                $hasMore = true;
            }
        }
    }
    
    // ==========================================================
    // ⚠️ ROLLBAR API MANTIĞI BURADA SONA ERİYOR
    // ==========================================================

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

                                    <div class="card shadow-sm">
                                        <div class="card-header border-0 pt-6">
                                            <div class="card-title">
                                                <h2>Rollbar Hata Listesi <span class="badge badge-light-primary fs-7 ms-2">Sayfa: <?php echo $currentPage; ?></span></h2>
                                            </div>
                                        </div>
                                        <div class="card-body py-4">
                                            <?php if ($curlError || $httpCode !== 200): ?>
                                                <div class="alert alert-danger d-flex align-items-center p-5 mb-10">
                                                    <i class="ki-duotone ki-cross-square fs-2hx text-danger me-4"><span class="path1"></span><span class="path2"></span></i>
                                                    <div class="d-flex flex-column">
                                                        <h4 class="mb-1 text-danger">Rollbar API Hatası (HTTP Kodu: <?php echo $httpCode; ?>)</h4>
                                                        <span>Lütfen Jetonunuzu ve Proje/Organizasyon yetkilerinizi kontrol edin.</span>
                                                        <code class="mt-2 text-dark fs-7"><?php echo htmlspecialchars($response ?? $curlError); ?></code>
                                                    </div>
                                                </div>
                                            <?php elseif (empty($items)): ?>
                                                <div class="alert alert-warning d-flex align-items-center p-5 mb-10">
                                                    <i class="ki-duotone ki-information-3 fs-2hx text-warning me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                    <div class="d-flex flex-column">
                                                        <h4 class="mb-1 text-warning">Veri Bulunamadı</h4>
                                                        <span>Aktif, hata veya kritik seviyesinde bir öğe bulunamadı.</span>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <p class="text-muted fs-6 mb-5">Toplam <span class="badge badge-light-success"><?php echo count($items); ?></span> öğe çekildi. İstek URL: <code><?php echo htmlspecialchars($fullUrl); ?></code></p>
                                                
                                                <div class="table-responsive">
                                                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_rollbar_table">
                                                        <thead>
                                                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                                                <th class="min-w-50px">#</th>
                                                                <th class="min-w-150px">Başlık / Kaynak</th> 
                                                                <th class="min-w-100px">Proje ID</th>
                                                                <th class="min-w-100px">Seviye</th>
                                                                <th class="min-w-100px">Tekrar Sayısı</th>
                                                                <th class="min-w-150px">Son Görülme</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="text-gray-600 fw-semibold">
                                                            <?php $sayac = (($currentPage - 1) * $limit) + 1; ?>
                                                            <?php foreach ($items as $item): ?>
                                                            <tr>
                                                                <td><?php echo $sayac++; ?></td>
                                                                <td>
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="d-flex justify-content-start flex-column">
                                                                            <a href="https://app.rollbar.com/a/developerepikman/fix/items?prj=780083%3F%3E" target="_blank" class="text-gray-800 fw-bold mb-1 fs-6">
                                                                                <?php echo htmlspecialchars($item['title']); ?>
                                                                            </a>
                                                                            <span class="text-muted fw-semibold text-muted d-block fs-7">
                                                                                <i class="ki-duotone ki-link fs-7 me-1"></i>
                                                                                Item ID: <?php echo htmlspecialchars($item['id']); ?>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td><?php echo htmlspecialchars($item['project_id']); ?></td>
                                                                <td>
                                                                    <span class="badge badge-light-<?php echo ($item['level'] === 'critical' ? 'danger' : 'warning'); ?>">
                                                                        <?php echo htmlspecialchars(ucfirst($item['level'])); ?>
                                                                    </span>
                                                                </td>
                                                                <td><?php echo number_format($item['total_occurrences']); ?></td>
                                                                <td><?php echo date('Y-m-d H:i:s', $item['last_occurrence_timestamp']); ?></td>
                                                            </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                
                                                <div class="d-flex justify-content-between align-items-center mt-5">
                                                    <?php 
                                                    $currentScript = htmlspecialchars($_SERVER['PHP_SELF']);
                                                    ?>
                                                    <div class="d-flex">
                                                        <?php if ($currentPage > 1): ?>
                                                            <a href="<?php echo $currentScript; ?>?page=<?php echo $currentPage - 1; ?>" class="btn btn-light-primary me-2">
                                                                <i class="ki-duotone ki-arrow-left fs-4"></i> Önceki Sayfa
                                                            </a>
                                                        <?php else: ?>
                                                            <button class="btn btn-light me-2" disabled>
                                                                <i class="ki-duotone ki-arrow-left fs-4"></i> Önceki Sayfa
                                                            </button>
                                                        <?php endif; ?>

                                                        <span class="btn btn-light fw-bold">
                                                            Sayfa: <strong><?php echo $currentPage; ?></strong>
                                                        </span>

                                                        <?php if ($hasMore): ?>
                                                            <a href="<?php echo $currentScript; ?>?page=<?php echo $currentPage + 1; ?>" class="btn btn-light-primary ms-2">
                                                                Sonraki Sayfa <i class="ki-duotone ki-arrow-right fs-4"></i>
                                                            </a>
                                                        <?php else: ?>
                                                            <button class="btn btn-light ms-2" disabled>
                                                                Sonraki Sayfa <i class="ki-duotone ki-arrow-right fs-4"></i>
                                                            </button>
                                                        <?php endif; ?>
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
        <div class="modal fade" id="kt_modal_upgrade_plan" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content rounded">
                    <div class="modal-header justify-content-end border-0 pb-0">
                        <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                            <i class="ki-duotone ki-cross fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        <script>
            var hostUrl = "assets/";
        </script>
        <script src="assets/plugins/global/plugins.bundle.js"></script>
        <script src="assets/js/scripts.bundle.js"></script>
        <script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
        
        <script src="assets/js/widgets.bundle.js"></script>
        <script src="assets/js/custom/widgets.js"></script>
        
    </body>
    </html>
<?php } else {
    header("location: index");
} ?>