<?php
// Sayfanın en üstünde session_start() ve GUARD tanımı
session_start();

define('GUARD', true);

// Kullanıcı rol kontrolü
if (isset($_SESSION['role']) && ($_SESSION['role'] == 1 || $_SESSION['role'] == 2 || $_SESSION['role'] == 10005 || $_SESSION['role'] == 10002)) {

    // Veritabanı ve sınıf dahil etme
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";

    $class = new Classes();
    $packagesList = $class->getPackagesList();
   
    // 1. MEVCUT PLAN BİLGİSİNİ ALMA
    $currentPackageId = $_SESSION['package_id'] ?? 21; // Varsayılan olarak Keşif Paketi ID'si (21) kabul edildi

    // Kullanıcı ID'sini AJAX'ta kullanmak üzere değişkene al
    $loggedInUserId = $_SESSION['id'] ?? 0;
    $classId = $_SESSION['class_id'] ?? null;

    // class_id'ye göre plan id seti belirle
    switch ($classId) {
        case 10:
            $planIds = [1, 2, 3];
            break;
        case 11:
            $planIds = [21, 22, 23];
            break;
        case 12:
            $planIds = [25, 26, 27];
            break;
    }

    // 2. PLAN VERİLERİNİ OLUŞTURMA
    $prices = [];
    foreach ($packagesList as $package) {
        $prices[$package['id']] = $package['price_yearly'] ?? 0;
    }
   
    // Yardımcı formatlama fonksiyonu
    function formatPrice($price)
    {
        return number_format((float)$price, 0, ',', '.');
    }

    // 4. PLAN VERİLERİNİ OLUŞTUR
    $plans = [
        [
            'id' => $planIds[0],
            'name' => 'Keşif Paketi',
            'price_yearly' => $prices[$planIds[0]] ?? '5000',
            'description' => 'Çocukların temel eğitimi için ilk adımı atın.',
            'features' => [
                '123 Eğitim Videosu',
                '105 Animasyon Videosu',
                '140 İnteraktif Etkinlik',
                'Konuşma Kulübünde Speaking İmkanı',
                '206 Oyun'
            ],
            'color' => 'success',
            'is_current' =>  $_SESSION['package_id'] == $planIds[0],
            'price_yearly_formatted' => formatPrice($prices[$planIds[0]] ?? 5000)
        ],
        [
            'id' => $planIds[1],
            'name' => 'Macera Paketi',
            'price_yearly' => $prices[$planIds[1]] ?? '8000',
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
            'color' => 'warning',
            'is_current' =>  $_SESSION['package_id'] == $planIds[1],
            'price_yearly_formatted' => formatPrice($prices[$planIds[1]] ?? 8000)
        ],
        [
            'id' => $planIds[2],
            'name' => 'Galaksi Paketi',
            'price_yearly' => $prices[$planIds[2]] ?? '10000',
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
            'color' => 'danger',
            'is_current' =>  $_SESSION['package_id'] == $planIds[2],
            'price_yearly_formatted' => formatPrice($prices[$planIds[2]] ?? 10000)
        ]
    ];

    // Mevcut planın SweetAlert'te kullanılmak üzere verisini bulma
    $currentPlanData = array_filter($plans, function ($plan) use ($currentPackageId) {
        return $plan['id'] == $currentPackageId;
    });
    $currentPlanData = array_values($currentPlanData)[0] ?? ['name' => 'Mevcut Plan', 'price_yearly' => '0', 'price_yearly_formatted' => '0'];

?>
    <!DOCTYPE html>
    <html lang="tr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Plan Yükseltme | Abonelik Yönetimi</title>

        <?php include_once "views/pages-head.php"; ?>

        <script>
            // Mevcut ve diğer planların fiyatlarını SweetAlert için erişilebilir yapıyoruz.
            const allPlans = <?php echo json_encode($plans); ?>;
            const currentPlanId = <?php echo json_encode($currentPackageId); ?>;
            const currentPlanPrice = <?php echo json_encode($currentPlanData['price_yearly']); ?>;
            const loggedInUserId = <?php echo json_encode($loggedInUserId); ?>; // Kullanıcı ID'si eklendi
        </script>

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

                                    <div class="text-center mb-10">
                                        <h1 class="text-dark fw-bold mb-3">İhtiyaçlarınıza Uygun Planı Seçin</h1>
                                        <div class="text-muted fw-semibold fs-5">
                                            **Mevcut Planınız: <?php echo $currentPlanData['name']; ?>.** Size en uygun olan planı seçerek hemen yükseltin. Tüm fiyatlar yıllık ödeme bazındadır.
                                        </div>
                                    </div>

                                    <div class="row g-10 justify-content-center">

                                        <?php foreach ($plans as $plan) : ?>
                                            <div class="col-xl-4 col-md-6 mb-8">
                                                <div class="card card-flush d-flex flex-column h-md-100 <?php echo $plan['is_current'] ? 'border border-' . $plan['color'] . ' border-2 shadow-lg' : 'shadow'; ?>">
                                                    <div class="card-header border-0 pt-9">
                                                        <div class="d-flex flex-center position-relative">
                                                            <div class="me-2">
                                                                <span class="fs-2hx fw-bold text-dark me-2">
                                                                    <?php echo number_format((float)$plan['price_yearly'], 0, ',', '.'); ?>
                                                                </span>
                                                                <span class="fs-4 fw-semibold text-gray-400">₺ / Yıllık</span>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="card-body p-0 pb-10 d-flex flex-column flex-grow-1">
                                                        <div class="d-flex flex-column align-items-center text-center">
                                                            <h2 class="fw-bold text-dark my-5"><?php echo $plan['name']; ?></h2>
                                                            <div class="fw-semibold text-gray-400 mb-6"><?php echo $plan['description']; ?></div>
                                                        </div>

                                                        <div class="d-flex flex-column px-9 mb-10">
                                                            <?php foreach ($plan['features'] as $feature) : ?>
                                                                <div class="d-flex align-items-center mb-2">
                                                                    <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 me-3"><?php echo $feature; ?></span>
                                                                    <i class="ki-duotone ki-check-circle fs-1 text-<?php echo $plan['color']; ?>">
                                                                        <span class="path1"></span>
                                                                        <span class="path2"></span>
                                                                    </i>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>

                                                        <div class="d-flex flex-center mt-auto">
                                                            <?php if ($plan['is_current']) : ?>
                                                                <button class="btn btn-<?php echo $plan['color']; ?> disabled">
                                                                    <i class="ki-duotone ki-briefcase-2 fs-4 me-2"></i> Mevcut Planınız
                                                                </button>
                                                            <?php else : ?>
                                                                <a href="#" class="btn btn-<?php echo $plan['color']; ?>"
                                                                    data-kt-action="upgrade-plan"
                                                                    data-plan-name="<?php echo $plan['name']; ?>"
                                                                    data-plan-id="<?php echo $plan['id']; ?>"
                                                                    data-plan-price="<?php echo $plan['price_yearly']; ?>">
                                                                    <i class="ki-duotone ki-arrow-up fs-4 me-2"></i> Paket Değiştir
                                                                </a>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>

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
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {

                // Helper: Fiyatı formatlar (örn: 5000 -> 5.000)
                function formatPrice(price) {
                    return parseFloat(price).toLocaleString('tr-TR', {
                        maximumFractionDigits: 0
                    });
                }

                // Plan Yükseltme Aksiyonu (SweetAlert ve AJAX ile)
                document.querySelectorAll('[data-kt-action="upgrade-plan"]').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();

                        const planName = this.getAttribute('data-plan-name');
                        const planId = this.getAttribute('data-plan-id');
                        const upgradePrice = parseFloat(this.getAttribute('data-plan-price')); // Yükseltilmek istenen planın fiyatı

                        // Fiyat Farkı Hesaplama
                        const currentPrice = parseFloat(currentPlanPrice); // Global PHP değişkeninden alındı
                        const amountToPay = upgradePrice - currentPrice;

                        // Yükseltme kontrolü (Sadece daha pahalıya yükseltme yapılabilir)
                        if (amountToPay <= 0) {
                            Swal.fire({
                                title: 'İşlem İptal Edildi',
                                text: 'Seçtiğiniz plan, mevcut planınızdan daha ucuz veya aynı fiyattadır. Yükseltme işlemi yapılamaz.',
                                icon: 'error',
                                confirmButtonText: 'Tamam'
                            });
                            return;
                        }

                        // SweetAlert'te gösterilecek tutar
                        const amountToPayFormatted = formatPrice(amountToPay);

                        // 1. Onay Kutusu
                        Swal.fire({
                            title: `**${planName}** planına yükseltmek üzeresiniz.`,
                            html: `Mevcut planınızın (₺${formatPrice(currentPrice)}) fiyatı düşüldükten sonra ödenecek fark: <br><br>**<span class="text-danger fs-1">${amountToPayFormatted} ₺</span>** (Yıllık)`,
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Evet, Yükseltmeyi Onayla',
                            cancelButtonText: 'Vazgeç',
                            customClass: {
                                confirmButton: 'btn btn-success',
                                cancelButton: 'btn btn-danger'
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {

                                // 2. Yükleniyor Alert'i
                                Swal.fire({
                                    title: 'İşlem Başlatılıyor!',
                                    text: 'Güvenli ödeme sayfasına yönlendiriliyorsunuz, lütfen bekleyiniz.',
                                    icon: 'info',
                                    allowOutsideClick: false,
                                    showConfirmButton: false,
                                    willOpen: () => {
                                        Swal.showLoading();
                                    }
                                });

                                // AJAX İsteği: TAMI Token Alımı
                                $.ajax({
                                    url: 'tami-sanal-pos/auth_upgrade_package.php', // Token almak için kullanılan endpoint
                                    type: 'POST',
                                    dataType: 'json',
                                    data: {
                                        amount: amountToPay, // Ödenecek fark tutarı
                                        package_id: planId, // Yeni paketin ID'si
                                        user_id: loggedInUserId, // Global değişkenden alınan kullanıcı ID'si
                                        action: 'upgrade_package' // Sunucu tarafında yükseltme olduğunu belirtmek için ek parametre
                                    },
                                    success: function(response) {
                                        Swal.close(); // Yükleniyor alert'ini kapat

                                        if (response.oneTimeToken) {
                                            // Başarılı: TAMI Hosted Payment Page'e yönlendirme
                                            window.location.href = 'https://portal.tami.com.tr/hostedPaymentPage?token=' + response.oneTimeToken;
                                        } else {
                                            // Hata: Token alınamadı
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'İşlem Başarısız',
                                                text: response.message || 'Ödeme tokenı alınamadı. Lütfen tekrar deneyin.',
                                                confirmButtonText: 'Tamam'
                                            });
                                        }
                                    },
                                    error: function(xhr, status, error) {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Sunucu Hatası',
                                            text: 'Sunucu ile iletişimde bir hata oluştu. Lütfen teknik ekibe danışınız.',
                                            confirmButtonText: 'Tamam'
                                        });
                                    }
                                });
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