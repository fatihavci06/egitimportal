<?php
// Sayfanın en üstünde session_start() ve GUARD tanımı
session_start();

define('GUARD', true);

// Kullanıcı rol kontrolü, kendi sisteminize göre düzenlemelisiniz
if (isset($_SESSION['role']) && ($_SESSION['role'] == 1 || $_SESSION['role'] == 2 || $_SESSION['role'] == 10005 || $_SESSION['role'] == 10002)) {
    // Veritabanı ve sınıf dahil etme
    include_once "classes/dbh.classes.php"; // Kendi Dbh sınıf dosyanız
    include "classes/classes.classes.php"; // Kendi Classes sınıf dosyanız

    $class = new Classes();

    // Mevcut plan bilgisini varsayalım (örneğin session'dan veya DB'den çekilebilir)
    $currentPlan = 'Keşif Paketi'; // Varsayılan olarak Keşif Paketi mevcut kabul edildi
    $plans = [
        [
            'name' => 'Keşif Paketi',
            'price_yearly' => '5000', // Yıllık Fiyat
            'description' => 'Çocukların temel eğitimi için ilk adımı atın.',
            'features' => [
                '123 Eğitim Videosu',
                '105 Animasyon Videosu',
                '140 İnteraktif Etkinlik',
                'Konuşma Kulübünde Speaking İmkanı',
                '206 Oyun'
            ],
            'color' => 'success', // Keşif Paketi için yeşil ton (success)
            'is_current' => $currentPlan === 'Keşif Paketi'
        ],
        [
            'name' => 'Macera Paketi',
            'price_yearly' => '8000', // Yıllık Fiyat
            'description' => 'Eğitimi ve psikolojik gelişimi birleştiren geniş paket.',
            'features' => [
                '123 Eğitim Videosu',
                '105 Animasyon Videosu',
                '240 İnteraktif Etkinlik',
                '206 Oyun',
                '139 Worksheet',
                'Psikologlar Eşliğinde Çocuk Tanıma Atölyesi Testleri',
                'Speaking Kulübü',
                'Sanal Gezi',
                'Sesli Hikayeler',
                'Anlık Soru Sorma-Çok Gönder',
                'Yapay Zeka ile İlerleme Takibi',
                'E Kütüphane',
                'Mentor Öğretmen Desteği'
            ],
            'color' => 'warning', // Macera Paketi için sarı/turuncu ton (warning)
            'is_current' => $currentPlan === 'Macera Paketi'
        ],
        [
            'name' => 'Galaksi Paketi',
            'price_yearly' => '10000', // Yıllık Fiyat
            'description' => 'Tüm özelliklerin ve premium desteğin sınırsız sunulduğu paket.',
            'features' => [
                '123 Eğitim Videosu',
                '105 Animasyon Videosu',
                '139 Worksheet',
                '206 Oyun',
                'Psikologlar Eşliğinde Çocuk Tanıma Atölyesi Testleri',
                'Speaking Kulüpleri',
                'Sanal Gezi',
                'Sesli Hikayeler',
                'Anlık Soru Sorma-Çok Gönder',
                'Yapay Zeka ile İlerleme Takibi',
                'E Kütüphane',
                'Aile ve Çocuk Atölyeleri',
                '"Global Kids Events" (Online İngilizce Festivalleri)',
                'Mentor Öğretmen Desteği ile Haftalık Görevlendirme - Veli ile Birebir İletişim'
            ],
            'color' => 'danger', // Galaksi Paketi için kırmızı ton (danger)
            'is_current' => $currentPlan === 'Galaksi Paketi'
        ]
    ];
?>
    <!DOCTYPE html>
    <html lang="tr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Plan Yükseltme | Abonelik Yönetimi</title>

        <?php include_once "views/pages-head.php"; ?>

    </head>

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
                            <?php 
                            // Toolbar içeriği düzenlendi
                            $toolbar_title = "Plan Yükseltme";
                            include_once "views/toolbar.php"; 
                            ?>
                            <div id="kt_app_content" class="app-content flex-column-fluid">
                                <div id="kt_app_content_container" class="app-container container-fluid">
                                    
                                    <!-- BAŞLIK VE AÇIKLAMA -->
                                    <div class="text-center mb-10">
                                        <h1 class="text-dark fw-bold mb-3">İhtiyaçlarınıza Uygun Planı Seçin</h1>
                                        <div class="text-muted fw-semibold fs-5">
                                            Size en uygun olan planı seçerek hemen yükseltin. Tüm fiyatlar yıllık ödeme bazındadır.
                                        </div>
                                    </div>
                                    
                                    <!-- AYLIK/YILLIK TOGGLE KALDIRILDI -->
                                    <!-- FİYATLANDIRMA KARTLARI -->
                                    <div class="row g-10">
                                        
                                        <?php foreach ($plans as $plan) : ?>
                                            <div class="col-xl-4 col-md-6 mb-8">
                                                <div class="card card-flush d-flex flex-column h-md-100 <?php echo $plan['is_current'] ? 'border border-' . $plan['color'] . ' border-2 shadow-lg' : 'shadow'; ?>">
                                                    <div class="card-header border-0 pt-9">
                                                        <div class="d-flex flex-center position-relative">
                                                            <div class="me-2">
                                                                <!-- Fiyat, yıllık olarak sabitlendi -->
                                                                <span class="fs-2hx fw-bold text-dark me-2">
                                                                    <?php echo $plan['price_yearly']; ?>
                                                                </span>
                                                                <!-- Fiyat birimi Yıllık olarak değiştirildi -->
                                                                <span class="fs-4 fw-semibold text-gray-400">₺ / Yıllık</span>
                                                            </div>
                                                            <?php if ($plan['is_current']) : ?>
                                                             
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>

                                                    <!-- card-body'ye flex-grow-1 eklenerek içeriğin esnek büyümesi sağlandı -->
                                                    <div class="card-body p-0 pb-10 d-flex flex-column flex-grow-1">
                                                        <div class="d-flex flex-column align-items-center text-center">
                                                            <h2 class="fw-bold text-dark my-5"><?php echo $plan['name']; ?></h2>
                                                            <div class="fw-semibold text-gray-400 mb-6"><?php echo $plan['description']; ?></div>
                                                        </div>
                                                        
                                                        <!-- dikey boşluk azaltmak için mb-5 yerine mb-3 kullanıldı -->
                                                        <div class="d-flex flex-column px-9 mb-10"> 
                                                            <?php foreach ($plan['features'] as $feature) : ?>
                                                                <!-- mb-5 yerine mb-2 kullanılarak satır aralığı azaltıldı -->
                                                                <div class="d-flex align-items-center mb-2"> 
                                                                    <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 me-3"><?php echo $feature; ?></span>
                                                                    <i class="ki-duotone ki-check-circle fs-1 text-<?php echo $plan['color']; ?>">
                                                                        <span class="path1"></span>
                                                                        <span class="path2"></span>
                                                                    </i>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                        
                                                        <!-- mt-auto eklenerek butonun her zaman kartın dibine hizalanması sağlandı -->
                                                        <div class="d-flex flex-center mt-auto"> 
                                                            <?php if ($plan['is_current']) : ?>
                                                                <button class="btn btn-<?php echo $plan['color']; ?> disabled">
                                                                    <i class="ki-duotone ki-briefcase-2 fs-4 me-2"></i> Mevcut Planınız
                                                                </button>
                                                            <?php else : ?>
                                                                <a href="#" class="btn btn-<?php echo $plan['color']; ?>" data-kt-action="upgrade-plan" data-plan-name="<?php echo $plan['name']; ?>">
                                                                    <i class="ki-duotone ki-arrow-up fs-4 me-2"></i> Satın Al
                                                                </a>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>

                                    </div>
                                    <!-- FİYATLANDIRMA KARTLARI SONU -->
                                    
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
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        <!-- PLAN YÜKSELTME JS -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                
                // Plan Yükseltme Aksiyonu (SweetAlert ile)
                document.querySelectorAll('[data-kt-action="upgrade-plan"]').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        const planName = this.getAttribute('data-plan-name');
                        
                        Swal.fire({
                            title: `${planName} planını satın almak üzeresiniz.`,
                            text: `Toplam tutar ${planName === 'Keşif Paketi' ? '5000' : (planName === 'Macera Paketi' ? '8000' : '10000')} TL (Yıllık). Onaylıyor musunuz?`,
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Evet, Satın Al',
                            cancelButtonText: 'Vazgeç',
                            customClass: {
                                confirmButton: 'btn btn-success',
                                cancelButton: 'btn btn-danger'
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Burada AJAX çağrısı ile ödeme/yükseltme işlemini tetikleyin
                                Swal.fire({
                                    title: 'Yönlendiriliyor!',
                                    text: 'Ödeme sayfasına yönlendiriliyorsunuz...',
                                    icon: 'info',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                // window.location.href = 'payment_page.php?plan=' + planName;
                            }
                        });
                    });
                });
            });
        </script>

    </body>

    </html>
<?php
} else {
    // Eğer kullanıcı rolü uygun değilse ana sayfaya yönlendir
    header("location: index");
    exit();
}
?>
