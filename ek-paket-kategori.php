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

// function getUserInfo($userId, $pdo)
// {
//     $stmt4 = $pdo->prepare('SELECT * from users_lnp where id=?');
//     $stmt4->execute([$userId]);
//     $user = $stmt4->fetch(PDO::FETCH_ASSOC);
//     return $user;
// }

// $package = new Packages();

// $vat = $package->getVat();
// $vat = $vat['tax_rate'];  // %10 KDV oranı

// $success = $_SESSION['payment_success'] ?? null;
// $error = $_SESSION['payment_error'] ?? null;

// // Oturumu temizle
// unset($_SESSION['payment_success'], $_SESSION['payment_error']);

// Kullanıcı rol kontrolü, kendi sisteminize göre düzenlemelisiniz
if (isset($_SESSION['role']) && ($_SESSION['role'] == 1 || $_SESSION['role'] == 2)) {
    // Veritabanı ve sınıf dahil etme
    include_once "classes/dbh.classes.php"; // Kendi Dbh sınıf dosyanız
    include "classes/classes.classes.php"; // Kendi Classes sınıf dosyanız

    $class = new Classes();
    $data = $class->getExtraPackageList(); // Paket listesini çeken fonksiyon



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
                background-color: #1b84ff;
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
                                        <div class="card-header border-0 pt-6">
                                            <div class="card-toolbar">
                                                <div class="d-flex justify-content-end align-items-center d-none" data-kt-customer-table-toolbar="selected">
                                                    <div class="fw-bold me-5">
                                                        <span class="me-2" data-kt-customer-table-select="selected_count"></span>Seçildi
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0" style="margin-top: -53px;">
                                            <header class="container-fluid bg-custom-light py-3 d-flex justify-content-between align-items-center
                                             border-top border-bottom border-custom-red mb-2" style="border-width: 5px !important; height:85px;margin-bottom: 26px !important;">

                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-danger me-3 shadow icon-circle-lg d-flex justify-content-center align-items-center"
                                                        style="width: 65px; height: 65px;">
                                                        <i class="fas fa-bullseye fa-2x text-white"></i>
                                                    </div>

                                                    <h1 class="fs-3 fw-bold text-dark mb-0">Ek Paket Listesi</h1>
                                                </div>

                                            </header>
                                            <!-- Kartlar için stil: boşluk azaltıldı -->
                                            <div class="col-12 mb-2">
                                                <div class="card custom-card" style="margin-bottom: 10px;">
                                                    <div class="card-body py-3 px-3">
                                                        <h3 class="card-title-custom mb-2">Özel Ders</h3>

                                                        <!-- Metin, ikon ve buton aynı satırda -->
                                                        <div class="d-flex align-items-start justify-content-between mb-2">
                                                            <div class="d-flex align-items-start flex-grow-1 me-2">
                                                                <i class="fas fa-graduation-cap icon-small me-2" style="color:#ed5606!important;"></i>
                                                                <p class="card-text-custom mb-0">
                                                                    Birebir özel derslerle eksiklerini tamamla, zorlandığın konuları aş. Uzman eğitmenlerimizle kişiye özel müfredat ve öğrenme metotlarıyla başarıya ulaş.
                                                                </p>
                                                            </div>
                                                            <div>
                                                                <a href="ek-paket-satin-al?tur=ozel-ders"
                                                                    class="btn btn-sm btn-primary"
                                                                    style="background-color: #ed5606; border-color: #ed5606;"
                                                                    onmouseover="this.style.backgroundColor='#2b8c01'; this.style.borderColor='#2b8c01';"
                                                                    onmouseout="this.style.backgroundColor='#ed5606'; this.style.borderColor='#ed5606';">
                                                                    İncele
                                                                </a>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>


                                            <!-- Rehberlik Kartı -->
                                            <!-- Rehberlik Kartı -->
                                            <div class="col-12 mb-2">
                                                <div class="card custom-card" style="margin-bottom: 10px;">
                                                    <div class="card-body py-3 px-3">
                                                        <h3 class="card-title-custom mb-2">Rehberlik</h3>
                                                        <div class="d-flex align-items-start justify-content-between mb-2">
                                                            <div class="d-flex align-items-start flex-grow-1 me-2">
                                                                <i class="fas fa-compass icon-small me-2" style="color:#ed5606!important;"></i>
                                                                <p class="card-text-custom mb-0">
                                                                    Doğru kariyer yolunu bulmana yardımcı olacak rehberlik hizmetleri. Sınav stratejileri, motivasyon ve gelecek planlama konularında destek al.
                                                                </p>
                                                            </div>
                                                            <div>
                                                                <a href="ek-paket-satin-al?tur=rehberlik"
                                                                    class="btn btn-sm btn-primary"
                                                                    style="background-color: #ed5606; border-color: #ed5606;"
                                                                    onmouseover="this.style.backgroundColor='#2b8c01'; this.style.borderColor='#2b8c01';"
                                                                    onmouseout="this.style.backgroundColor='#ed5606'; this.style.borderColor='#ed5606';">
                                                                    İncele
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Koçluk Kartı -->
                                            <div class="col-12">
                                                <div class="card custom-card" style="margin-bottom: 10px;">
                                                    <div class="card-body py-3 px-3">
                                                        <h3 class="card-title-custom mb-2">Koçluk</h3>
                                                        <div class="d-flex align-items-start justify-content-between mb-2">
                                                            <div class="d-flex align-items-start flex-grow-1 me-2">
                                                                <i class="fas fa-trophy icon-small me-2" style="color:#ed5606!important;"></i>
                                                                <p class="card-text-custom mb-0">
                                                                    Kişisel gelişim hedeflerine ulaşmak için bireysel koçluk desteği. Ders çalışma alışkanlıklarını geliştir, potansiyelini maksimize et.
                                                                </p>
                                                            </div>
                                                            <div>
                                                                <a href="ek-paket-satin-al?tur=kocluk"
                                                                    class="btn btn-sm btn-primary"
                                                                    style="background-color: #ed5606; border-color: #ed5606;"
                                                                    onmouseover="this.style.backgroundColor='#2b8c01'; this.style.borderColor='#2b8c01';"
                                                                    onmouseout="this.style.backgroundColor='#ed5606'; this.style.borderColor='#ed5606';">
                                                                    İncele
                                                                </a>
                                                            </div>
                                                        </div>
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