<?php
// Sayfanın en üstünde session_start() ve GUARD tanımı
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('GUARD', true);
require_once './classes/dbh.classes.php';
require_once './classes/Mailer.php';
include_once('./classes/packages.classes.php');
// require dosyalarını en başta dahil edin
require_once './tami-sanal-pos/securityHashV2.php';
require_once './tami-sanal-pos/lib/common_lib.php';
$db = new Dbh();
$pdo = $db->connect();

function getUserInfo($userId, $pdo)
{
    $stmt4 = $pdo->prepare('SELECT * from users_lnp where id=?');
    $stmt4->execute([$userId]);
    $user = $stmt4->fetch(PDO::FETCH_ASSOC);
    return $user;
}

$package = new Packages();

$vat = $package->getVat();
$vat = $vat['tax_rate'];  // %10 KDV oranı

$success = $_SESSION['payment_success'] ?? null;
$error = $_SESSION['payment_error'] ?? null;

// Oturumu temizle
unset($_SESSION['payment_success'], $_SESSION['payment_error']);

// Kullanıcı rol kontrolü, kendi sisteminize göre düzenlemelisiniz
if (isset($_SESSION['role']) && ($_SESSION['role'] == 1 || $_SESSION['role'] == 2)) {
    // Veritabanı ve sınıf dahil etme
    include_once "classes/dbh.classes.php"; // Kendi Dbh sınıf dosyanız
    include "classes/classes.classes.php"; // Kendi Classes sınıf dosyanız

    $class = new Classes();
    $tur = $_GET['tur'] ?? 'ozel-ders'; // Varsayılan olarak özel ders türü
    $data = $class->getExtraPackageList($tur); // Paket listesini çeken fonksiyon
    $package_descriptions = [
        'rehberlik' => [
            'title-1' => 'Rehberlik Paket İçeriği',
            'desc-1'  => 'Rehberlik paketi için özel olarak tasarlanmış içerikler ve danışmanlık hizmetleri ile hedeflerine ulaşmanı kolaylaştırır. Kariyer planlama ve sınav stratejileri hakkında uzman desteği alabilirsin.',
            'title-2' => 'Rehberlik Deneme Sınavları',
            'desc-2'  => 'Rehberlik odaklı deneme sınavları ile eksiklerini belirle ve öğrenme sürecini optimize et. Detaylı analizlerle güçlü ve zayıf yönlerini keşfet.',
            'title-3' => 'Kazanımları Pekiştiren Rehberlik Odaklı Sorular',
            'desc-3'  => 'Rehberlik programına özel hazırlanmış, kazanımları pekiştirmeye yönelik soru setleri ile konuları daha iyi kavra.',
            'title-4' => 'Öğrenci Koçluğu ve Mentörlük Desteği',
            'desc-4'  => 'Rehberlik öğrencilerine özel koçluk ve mentörlük desteği ile motivasyonunu artır ve düzenli takip ile hedeflerine odaklan.',
            'title-5' => 'Öğrenciye Özel Rehberlik Materyalleri',
            'desc-5'  => 'Kişiye özel rehberlik materyalleri ve çalışma planları ile verimli bir öğrenme süreci yönetimi.',
            'title-6' => 'Rehberlik Takip ve Geri Bildirim Sistemi',
            'desc-6'  => 'Rehberlik süreci boyunca düzenli takip ve geri bildirimler ile gelişimini izle ve stratejilerini sürekli iyileştir.',
            'title-7' => 'Rehberlik Sonuç Analizi',
            'desc-7'  => 'Rehberlik programının sonuçlarına yönelik detaylı analiz raporları ile performansını değerlendir ve gelecek hedeflerini belirle.'
        ],
        'ozel-ders' => [
            'title-1' => 'Özel Ders Paket İçeriği',
            'desc-1'  => 'Birebir özel derslerle konuları derinlemesine öğren ve eksiklerini kapat. Uzman eğitmenlerden kişiye özel destek alarak başarıyı yakala.',
            'title-2' => 'Birebir Deneme Sınavları',
            'desc-2'  => 'Özel ders programına entegre edilmiş deneme sınavları ile konulardaki ilerlemeni ölç ve zayıf noktalarını belirle.',
            'title-3' => 'Kişiye Özel Ders Materyalleri',
            'desc-3'  => 'Öğrenme hızına ve stilinize uygun olarak hazırlanmış ders materyalleri ile verimli bir çalışma ortamı.',
            'title-4' => 'Ders Sonrası Soru Çözüm Desteği',
            'desc-4'  => 'Özel ders sonrası çözemediğin sorular için ek destek ve çözümlerle konuları pekiştir.',
            'title-5' => 'Ders Tekrar ve Pekiştirme Seansları',
            'desc-5'  => 'Öğrenilen bilgileri pekiştirmek için ek ders tekrar seansları ve uygulamalar.',
            'title-6' => 'Öğrenci Gelişim Takip Raporları',
            'desc-6'  => 'Özel ders sürecindeki gelişimini gösteren detaylı raporlar ve performans analizleri.',
            'title-7' => 'Ders Sonrası Geri Bildirimler',
            'desc-7'  => 'Derslerin etkinliğini artırmak için düzenli geri bildirimler ve kişiye özel öneriler.'
        ],
        'kocluk' => [
            'title-1' => 'Koçluk Paket İçeriği',
            'desc-1'  => 'Profesyonel koçluk desteği ile kişisel hedeflerini belirle, motivasyonunu artır ve başarıya giden yolda emin adımlarla ilerle. Düzenli görüşmeler ve takip ile gelişimini destekle.',
            'title-2' => 'Koçluk Odaklı Çalışma Planları',
            'desc-2'  => 'Koçluk programına özel hazırlanmış, hedeflerine yönelik çalışma ve zaman yönetimi planları.',
            'title-3' => 'Hedef Belirleme ve Strateji Geliştirme',
            'desc-3'  => 'Koçunla birlikte hedeflerini netleştir, bu hedeflere ulaşmak için etkili stratejiler geliştir.',
            'title-4' => 'Motivasyon ve Disiplin Desteği',
            'desc-4'  => 'Koçluk süresince motivasyonunu yüksek tutmak ve düzenli çalışma disiplini kazanmak için destek.',
            'title-5' => 'Sınav Kaygısı ve Stres Yönetimi',
            'desc-5'  => 'Sınav kaygısını yönetme ve stresle başa çıkma teknikleri üzerine koçluk seansları.',
            'title-6' => 'Düzenli Koçluk Görüşmeleri',
            'desc-6'  => 'Haftalık veya belirlenen periyotlarda koçunla birebir görüşmelerle gelişimini takip et.',
            'title-7' => 'Koçluk Süreci Geri Bildirimleri',
            'desc-7'  => 'Koçluk sürecinin etkinliğini artırmak için düzenli geri bildirimler ve analizler.'
        ],
        // Varsayılan açıklamalar, eğer 'tur' parametresi tanımsızsa veya eşleşmiyorsa
        'default' => [
            'title-1' => 'Paket İçeriğinde Neler Var?',
            'desc-1'  => 'Derdaily deneme sınavları net odaklı soru bankası deneme avantajı yüksek net yapmanı sağlar. Deneme sınavlarımızda uzmanlar, harmanladıkları özel içeriklerimizle sana eşlik ediyor. Aynı zamanda uzman koçlarımızla kişisel gelişim ve program hazırlığına yönelik destek hizmetleri alabilirsin.',
            'title-2' => '2 Adet Deneme Sınavı Çözümü',
            'desc-2'  => 'Öğrencilerimiz için deneme sınavlarını en verimli şekilde değerlendirebilmeleri amacıyla özel olarak hazırlanan, 2 adet deneme sınavında uygulanan "öncesi ve sonrası" koçluğuyla öğrencinin motivasyonunu artırmak ve kazanımlarını pekiştirmeyi amaçlayan bir hizmettir.',
            'title-3' => 'Kazanımları Pekiştiren Tespitler',
            'desc-3'  => 'Deneme sınavlarından oluşan öğrencilerin kazanımlardaki eksiklerini tespit etmek için özel olarak tasarlanmıştır. Derdaily\'nin danışmanlık uzmanlarıyla uyumlu bir şekilde tasarlanmış bu içerikler, öğrencilerin zayıf yönlerini belirleyerek doğru bir öğrenme stratejisi oluşturmalarına yardımcı olur.',
            'title-4' => 'Öğrenci Koçluğu ve Öz Değerlendirme Seansı',
            'desc-4'  => 'Deneme uygulamaları sonrasında öğrenciye özel koçluk raporları sunulur. Öğrencinin kendi kendini analiz etme ve değerlendirme becerilerini geliştirir. Tecrübeli öğretmenlerimiz bu oturumlarda öğrenciye geri bildirim verir ve mentorluk desteğiyle hedeflerine ulaşmasında yardımcı olur.',
            'title-5' => 'Öğrenciye Özel Soru Bankası',
            'desc-5'  => 'Öğrencinin deneme sınavları için özel olarak hazırlanmış ve pekiştirmeyi amaçlayan bir soru bankası programıdır. Deneme sınavları sonuçlarında yapılan kazanım analizlerine göre öğrencinin bilgi ve kazanımlarını güçlendirir. Adı ve soyadı belirtilmez.',
            'title-6' => 'Paket Alanlara Sonra Destek!',
            'desc-6'  => 'Paket alanların sınav ve eğitim süreçlerinde yaşadığı zorlukları Derdaily danışmanları tarafından analiz ve birebir koçluk hizmeti ile desteklenir. Ek deneme sınavı için gerekli kargoluk olarak, uzman eğitmen desteğiyle yapılacak ek deneme çözümü sunulur. Deneme çözümü google meet üzerinden yapılır. Tüm süreçlerde soru çözümleri ve destek dersleri ile öğrenciye tam anlamıyla destek sağlanır.',
            'title-7' => 'Eğitmen Bilgilendirmesi',
            'desc-7'  => 'Deneme ve kişisel gelişim süreci sonrasında, öğrencinin bilgi donanımındaki eksikliklerini belirlemek amacıyla bir analiz raporu düzenlenir. Bu raporlar öğrencinin kazanımlarını ve eksiklerini net bir şekilde gösterir.'
        ]
    ];
    $current_descriptions = $package_descriptions[$tur] ?? $package_descriptions['default'];
    $package_blocks = [
        ['fa-box-open', 'title-1', 'desc-1'], // Paket İçeriği
        ['fa-file-signature', 'title-2', 'desc-2'], // Deneme Sınavı
        ['fa-lightbulb', 'title-3', 'desc-3'], // Tespitler
        ['fa-user-graduate', 'title-4', 'desc-4'], // Koçluk
        ['fa-book-open', 'title-5', 'desc-5'], // Soru Bankası
        ['fa-handshake-angle', 'title-6', 'desc-6'], // Destek
        ['fa-chalkboard-user', 'title-7', 'desc-7'] // Eğitmen Bilgilendirmesi
    ];
?>
    <!DOCTYPE html>
    <html lang="tr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ödeme Sayfası</title>

        <?php include_once "views/pages-head.php"; ?>

        <style>
            /* Genel Stil İyileştirmeleri */
            body {
                background-color: #f0f2f5;
                /* Daha yumuşak bir arka plan */
            }

            .main-card-container {
                background-color: #ffffff;
                padding: 40px;
                border-radius: 15px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                width: 100%;
                border: 1px solid #e0e0e0;
            }

            .payment-card-title {
                color: #333;
                margin-bottom: 30px;
                font-weight: 700;
                /* Daha belirgin başlık */
                text-align: center;
                font-size: 1.8rem;
                /* Daha büyük başlık */
            }

            .form-label {
                font-weight: 600;
                /* Daha belirgin etiketler */
                color: #555;
                margin-bottom: 8px;
            }

            .form-control {
                border-radius: 8px;
                padding: 12px 15px;
                border: 1px solid #ddd;
                transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            }

            .form-control:focus {
                border-color: #007bff;
                box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
            }

            .btn-primary {
                background-color: #007bff;
                border-color: #007bff;
                border-radius: 8px;
                padding: 12px 0;
                font-size: 1.1rem;
                font-weight: 600;
                transition: background-color 0.2s ease-in-out, border-color 0.2s ease-in-out;
            }

            .btn-primary:hover {
                background-color: #0056b3;
                border-color: #0056b3;
            }

            .btn-success {
                /* Yeni buton stilini özelleştirelim */
                background-color: #28a745;
                border-color: #28a745;
                border-radius: 8px;
                padding: 12px 25px;
                font-size: 1.1rem;
                font-weight: 600;
                transition: background-color 0.2s ease-in-out, border-color 0.2s ease-in-out;
            }

            .btn-success:hover {
                background-color: #218838;
                border-color: #1e7e34;
            }

            .btn-secondary {
                border-radius: 8px;
                padding: 12px 25px;
                font-size: 1.1rem;
                font-weight: 600;
            }

            /* Alert Mesajları */
            .alert-success {
                background-color: #d4edda;
                border-color: #c3e6cb;
                color: #155724;
                border-radius: 8px;
                padding: 15px;
                font-size: 1rem;
                /* Biraz büyütüldü */
                text-align: center;
                margin-bottom: 20px;
                animation: fadeIn 0.5s ease-out;
                /* Animasyon eklendi */
            }

            .alert-danger {
                background-color: #f8d7da;
                border-color: #f5c6cb;
                color: #721c24;
                border-radius: 8px;
                padding: 15px;
                font-size: 1rem;
                /* Biraz büyütüldü */
                text-align: center;
                margin-bottom: 20px;
                animation: fadeIn 0.5s ease-out;
                /* Animasyon eklendi */
            }

            .alert-warning {
                background-color: #fff3cd;
                border-color: #ffeeba;
                color: #856404;
                border-radius: 8px;
                padding: 15px;
                font-size: 1rem;
                /* Biraz büyütüldü */
                text-align: center;
                margin-bottom: 20px;
                animation: fadeIn 0.5s ease-out;
                /* Animasyon eklendi */
            }

            /* Paket Kartları */
            .package-card {
                cursor: pointer;
                transition: all 0.2s ease-in-out;
                border: 1px solid #eee;
                border-radius: 12px;
                /* Daha yuvarlak köşeler */
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                /* İçerik dikeyde eşit dağılsın */
            }

            .package-card:hover {
                box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
                /* Daha belirgin gölge */
                transform: translateY(-5px);
                /* Hafif yukarı kalkma efekti */
            }

            .package-card.selected {
                border: 2px solid #009ef7;
                box-shadow: 0 0 15px rgba(0, 158, 247, 0.2);
                transform: translateY(-3px);
                background-color: #eaf8ff;
                /* Seçili paketin arka planı */
            }

            .package-card .card-title {
                font-size: 1.4rem;
                /* Paket başlıkları daha büyük */
                color: #007bff;
                /* Mavi renk */
                margin-bottom: 15px;
            }

            .package-card .card-text strong {
                color: #333;
            }

            .package-card .text-success {
                font-size: 1.6rem;
                /* Fiyat daha büyük */
                font-weight: 700;
            }

            .package-card .form-check {
                margin-left: auto;
                /* Radio butonu sağa yasla */
            }

            /* Formların Genel Düzeni */
            /* #cardPaymentForm {
                max-width: 500px;
                /* Form genişliği artırıldı */
            /* margin: 30px auto 0 auto;
                padding: 30px;
                border-radius: 15px;
                background-color: #ffffff;
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
                border: 1px solid #e9ecef;
            } */

            /* #cardPaymentForm h4 {
                margin-bottom: 25px;
                color: #333;
            } */

            /* Ekstra Görsel İyileştirmeler */
            .input-group {
                margin-bottom: 1rem;
            }

            .input-group .form-control:first-child {
                border-top-right-radius: 0;
                border-bottom-right-radius: 0;
            }

            .input-group .form-control:last-child {
                border-top-left-radius: 0;
                border-bottom-left-radius: 0;
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

            .swal2-popup {
                /* SweetAlert stilini de kullanıcı dostu hale getirelim */
                border-radius: 15px !important;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
            }

            .swal2-title {
                color: #333 !important;
                font-weight: 700 !important;
            }

            .swal2-html-container {
                color: #555 !important;
            }

            .package-block {
                background-color: #fff;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
                padding: 20px;
                margin-bottom: 20px;
                display: flex;
                align-items: center;
            }

            .package-block .fa-icon {
                /* Font Awesome ikonları için stil */
                font-size: 3rem;
                /* İkon boyutunu ayarlayın */
                color: #ed5606 !important;
                /* İkon rengini pembe yapın */
                margin-right: 15px;
                flex-shrink: 0;
                width: 50px;
                /* İkonun kapladığı alanı sabit tutmak için */
                text-align: center;
            }

            .package-block h5 {
                color: #ed5606 !important;
                /* Pembe renk */
                font-weight: 600;
            }

            .package-block p {
                color: #555;
                line-height: 1.6;
            }
        </style>
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
                            <?php include_once "views/toolbar.php"; ?>
                            <div id="kt_app_content" class="app-content flex-column-fluid">
                                <div id="kt_app_content_container" class="app-container container-fluid">
                                    <div class="card" style="margin-top: -38px;margin-left:-30px;">
                                        <div class="card-header ">
                                            <div class="card-toolbar">
                                                <div class="d-flex justify-content-end align-items-center d-none" data-kt-customer-table-toolbar="selected">
                                                    <div class="fw-bold me-5">
                                                        <span class="me-2" data-kt-customer-table-select="selected_count"></span>Seçildi
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class="row" style="margin: auto;">
                                                <?php if ($success): ?>
                                                    <div class="alert alert-success">
                                                        ✅ Paket Satın Alınmıştır <?php /*htmlspecialchars($success)*/ ?>
                                                    </div>
                                                <?php elseif ($error): ?>
                                                    <div class="alert alert-danger">
                                                        ❌ <?= htmlspecialchars($error) ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <?php $packages = $data; ?>
                                            <div style="margin-top:-50px;">
                                              
                                                <header class="container-fluid py-3 d-flex justify-content-between align-items-center" style="
                                                    background-color: #e6e6fa !important;
                                                    margin-bottom: 40px !important;
                                                    margin-top: -20px !important;
                                                    border-top: 5px solid #d22b2b !important;
                                                    border-bottom: 5px solid #d22b2b !important;
                                                   
                                                    height:85px;
                                                ">
                                                    <div class="d-flex align-items-center">
                                                        <div class="">
                                                            <img src="assets/media/topics/konuDefault.jpg" alt="Ek Paket Detayları" class="img-fluid" style="width: 90px; height: 90px; object-fit: contain;">
                                                        </div>
                                                        <div>
                                                            <h1 class="fs-3 fw-bold text-dark mb-0 ml-2" style="margin-left: 20px;">Ek Paket Detayları</h1>
                                                        </div>
                                                    </div>
                                                </header>
                                                <?php foreach ($package_blocks as $block): ?>
                                                    <div class="package-block">
                                                        <i class="fas <?php echo htmlspecialchars($block[0]); ?> fa-icon"></i>
                                                        <div>
                                                            <h5 class="mb-1"><?php echo htmlspecialchars($current_descriptions[$block[1]]); ?></h5>
                                                            <p class="mb-0"><?php echo htmlspecialchars($current_descriptions[$block[2]]); ?></p>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>

                                            </div>
                                            <div id="packageSelectionAndPaymentContainer" class="main-card-container">
                                                <h4 class="payment-card-title">Paket Seçimi</h4>

                                                <form id="purchaseForm" class="mb-5">
                                                    <div class="row g-4">
                                                        <?php if (!empty($packages)): ?>
                                                            <?php foreach ($packages as $package):

                                                                $price = $package['price']; // Paket fiyatı
                                                                $price += $price * ($vat / 100); // KDV'yi ekle
                                                                $vatAmount = $package['price'] * ($vat / 100); // KDV tutarını hesapla

                                                            ?>
                                                                <div class="col-md-4">
                                                                    <div class="card h-100 package-card"
                                                                        data-package-id="<?= $package['id'] ?>"
                                                                        data-package-price="<?= htmlspecialchars($price) ?>">
                                                                        <div class="card-body d-flex flex-column">
                                                                            <h5 class="card-title  fw-bold" style="color: #000!important;"><?= htmlspecialchars($package['name']) ?></h5>
                                                                            <p class="card-text flex-grow-1">
                                                                                Tür: <strong><?= htmlspecialchars($package['type']) ?></strong><br>
                                                                                <?php if ($package['type'] === 'Koçluk' || $package['type'] === 'Rehberlik'): ?>
                                                                                    Süre: <strong><?= $package['limit_count'] ?> ay</strong><br>
                                                                                <?php else: ?>
                                                                                    Adet: <strong><?= $package['limit_count'] ?> ders</strong><br>
                                                                                <?php endif; ?>
                                                                                Paket Ücreti: <strong><?= number_format($package['price'], 2, ',', '.') ?> TL</strong><br>
                                                                                KDV Oranı: <strong>%<?= number_format($vat, 2, ',', '.') ?></strong><br>
                                                                                Toplam Ücret: <strong><?= number_format($price, 2, ',', '.') ?> TL</strong><br>
                                                                                <span class="text-muted small"><?= htmlspecialchars($package['description'] ?? 'Açıklama mevcut değil.') ?></span>
                                                                            </p>
                                                                            <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top">
                                                                                <h4 class="mb-0 text-success" style="color:#2b8c01!important"><strong><?= number_format($price, 2, ',', '.') ?> TL</strong></h4>
                                                                                <div class="form-check">
                                                                                    <input class="form-check-input" type="radio" name="package_id" value="<?= $package['id'] ?>" required>
                                                                                    <label class="form-check-label">Seç</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <div class="col-12">
                                                                <div class="alert alert-info text-center">
                                                                    Herhangi bir ek paket bulunamadı.
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <input type="hidden" name="user_id" value="<?= $_SESSION['id'] ?? 1 ?>">
                                                    <div class="text-end mt-4">
                                                        <button type="submit" class="btn btn-primary btn-sm" id="selectPackageButton">Seçili Paketi Onayla</button>
                                                    </div>
                                                </form>
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
        <script src="assets/js/widgets.bundle.js"></script>
        <script src="assets/js/custom/widgets.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            $(document).ready(function() {
                let selectedPackageId = null;
                let selectedPackagePrice = 0; // Seçilen paketin fiyatını saklamak için değişken

                // Paket kartlarına tıklama olayı
                $('.package-card').on('click', function() {
                    // Radio butonunu seçili hale getir ve change olayını tetikle
                    $(this).find('input[type="radio"]').prop('checked', true).trigger('change');
                });

                // Radio butonu değiştiğinde kart stilini güncelle ve fiyatı al
                $('input[name="package_id"]').on('change', function() {
                    $('.package-card').removeClass('selected'); // Tüm kartlardan 'selected' sınıfını kaldır
                    const $selectedCard = $(this).closest('.package-card');
                    $selectedCard.addClass('selected'); // Seçili karta 'selected' sınıfını ekle
                    selectedPackageId = $(this).val(); // Seçilen paketin ID'sini sakla

                    // PHP tarafından döngü içinde oluşturulmuş paket verisinden fiyatı al
                    // Bu veriyi JavaScript'e aktarmanın en iyi yolu, her paket kartına bir data attribute eklemektir.
                    selectedPackagePrice = parseFloat($selectedCard.data('package-price'));
                });

                // "Seçili Paketi Onayla" butonuna basıldığında
                $('#purchaseForm').on('submit', function(e) {
                    e.preventDefault();

                    // Seçili paketin ID'si
                    const selectedPackageId = $('input[name="package_id"]:checked').val();

                    if (!selectedPackageId) {
                        alert('Lütfen bir paket seçin!');
                        return;
                    }

                    // Aynı package-id'ye sahip div'ten fiyatı al
                    const packageCard = $('.package-card[data-package-id="' + selectedPackageId + '"]');
                    const amount = packageCard.data('package-price');

                    // İstersen user_id'yi de alabilirsiniz
                    const userId = $('input[name="user_id"]').val();

                    // Ajax isteği
                    $.ajax({
                        url: 'tami-sanal-pos/auth.php',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            amount: amount,
                            package_id: selectedPackageId,
                            user_id: userId
                        },
                        success: function(response) {
                            if (response.oneTimeToken) {
                                window.location.href = 'https://portal.tami.com.tr/hostedPaymentPage?token=' + response.oneTimeToken;
                            } else {
                                alert('Bir hata oluştu: Token alınamadı.');
                            }
                        },
                        error: function(xhr, status, error) {

                            alert('Sunucu ile iletişimde bir hata oluştu.');
                        }
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