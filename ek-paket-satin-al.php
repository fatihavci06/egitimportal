<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true); // Kendi güvenlik tanımınız için bir sabit

// Frontend'de gösterilecek mesajları oturumdan çek ve temizle
$success = $_SESSION['payment_success'] ?? null;
$error = $_SESSION['payment_error'] ?? null;
unset($_SESSION['payment_success'], $_SESSION['payment_error']);

// Kullanıcı rol kontrolü (sizin sisteminize göre düzenlenmeli)
if (isset($_SESSION['role']) && ($_SESSION['role'] == 1 || $_SESSION['role'] == 2)) {
    // Veritabanı ve sınıf dosyalarınızı dahil edin
    // Bu dosyalar paketi çekmek veya güvenlik hash'i oluşturmak için gerekebilir.
    include_once "classes/dbh.classes.php"; 
    include "classes/classes.classes.php"; 
    include_once "views/pages-head.php"; 
    
    // Tami'nin SDK'sından security hash oluşturma veya diğer yardımcı fonksiyonlar için
    // Eğer Direct Post için bir hash gerekiyorsa bu dosyaları dahil edersiniz.
    // aksi takdirde bunları kaldırabilirsiniz.
    require_once './tami-sanal-pos/securityHashV2.php'; 
    require_once './tami-sanal-pos/lib/common_lib.php'; 

    $class = new Classes();
    $data = $class->getExtraPackageList(); // Paket listesini çeken fonksiyonunuz

    // Dinamik veriler için varsayılanlar veya oturumdan çekme
    $currentUserId = $_SESSION['user_id'] ?? 'default_user_id'; 
    $buyerName = $_SESSION['user_name'] ?? 'Misafir';
    $buyerSurname = $_SESSION['user_surname'] ?? 'Kullanıcı';
    $buyerEmail = $_SESSION['user_email'] ?? 'misafir@example.com';
    $buyerPhoneNumber = $_SESSION['user_phone'] ?? '05001234567';
    $buyerId = $_SESSION['user_id'] ?? 'default_buyer_id'; 
    $buyerIpAddress = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

    // Örnek sipariş bilgileri (bu kısımlar dinamik olarak seçilen pakete göre güncellenecektir)
    $orderId = "ORDER_" . uniqid(); // Her işlem için benzersiz bir sipariş ID'si
    $amount = 0.00; // Başlangıçta miktar 0, paket seçilince güncellenecek (float olarak)

    // Tami'ye bildirilecek dönüş URL'leri (kendi alan adınızla güncelleyin!)
    // Tami işlemi bitince kullanıcıyı bu URL'lere yönlendirecek
    $successUrl = "https://www.sizin-site-adresiniz.com/payment_callback.php?status=success&order_id=" . $orderId;
    $failureUrl = "https://www.sizin-site-adresiniz.com/payment_callback.php?status=failure&order_id=" . $orderId;
    $cancelUrl = "https://www.sizin-site-adresiniz.com/payment_callback.php?status=cancel&order_id=" . $orderId; // Tami destekliyorsa

    // Tami API Kimlik Bilgileri (Bu bilgileri güvenli bir yerden çekmelisiniz, sabit kodlamayın)
    // Bu değerler hidden input olarak gönderilebilir veya hash oluşturmak için kullanılabilir.
    $merchantId = "YOUR_TAMI_MERCHANT_ID"; // Tami'den aldığınız Merchant ID
    $terminalId = "YOUR_TAMI_TERMINAL_ID"; // Tami'den aldığınız Terminal ID
    $secretKey = "YOUR_TAMI_SECRET_KEY"; // Tami'den aldığınız Gizli Anahtar (Hash oluşturmak için)
    $fixedKidValue = "YOUR_FIXED_KID_VALUE"; // Eğer Tami JWT için sabit bir KID bekliyorsa
    $fixedKValue = "YOUR_FIXED_K_VALUE"; // Eğer Tami JWT için sabit bir K değeri bekliyorsa

    // Güvenlik Hash'i Oluşturma (Eğer Tami Direct Post için bunu istiyorsa)
    // Tami'nin dokümantasyonuna göre bu hash'in hangi verilerle oluşturulacağını kontrol edin.
    // Genellikle (merchantId, terminalId, amount, orderId, secretKey) gibi veriler kullanılır.
    $securityHash = '';
    // Örnek: Eğer Tami, belirli bir body JSON'unu imzalamanızı istiyorsa
    $hashBodyData = [
        "merchantId" => $merchantId,
        "terminalId" => $terminalId,
        // Diğer önemli parametreler (örn: amount, orderId) burada olmalı
        "amount" => number_format($amount, 2, '.', ''), // Tami'nin beklediği format
        "orderId" => $orderId,
        "successUrl" => $successUrl,
        "failureUrl" => $failureUrl
        // ... Tami dokümanlarındaki diğer hash inputları ...
    ];
    // JWT signature generator, bir JSON string beklediği için encode ediyoruz.
    // Bu 'hashBodyData' genellikle Direct Post'ta gönderilen form parametrelerinin bir alt kümesi veya özeti olur.
    $hashBodyJson = json_encode($hashBodyData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    
    // Yorum satırı, çünkü $amount şu an 0.00. Gerçek miktarla güncellendiğinde hash oluşturulacak.
    // $securityHash = JWTSignatureGenerator::generateJWKSignature(
    //     $merchantId, 
    //     $terminalId, 
    //     $secretKey, 
    //     $hashBodyJson, // Hash'lenecek veri
    //     $fixedKidValue, 
    //     $fixedKValue
    // );
    // **ÖNEMLİ:** Buradaki hash oluşturma mantığı, Tami'nin Direct Post için istediğiyle eşleşmeli.
    // Eğer Tami basit bir MD5 veya SHA256 istiyorsa, o algoritmayı kullanmalısınız.

?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ödeme Sayfası</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        /* Ana kapsayıcı */
        .main-card-container { 
            background-color: #ffffff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba
            (0, 0, 0, 0.1);
            width: 100%;
            max-width: 900px;
            border: 1px solid #e0e0e0;
        }
        .payment-card-title {
            color: #333;
            margin-bottom: 30px;
            font-weight: 600;
            text-align: center;
        }
        .form-label {
            font-weight: 500;
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
        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
            border-radius: 8px;
            padding: 15px;
            font-size: 0.95rem;
            text-align: center;
            margin-bottom: 20px;
        }
        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
            border-radius: 8px;
            padding: 15px;
            font-size: 0.95rem;
            text-align: center;
            margin-bottom: 20px;
        }
        .alert-warning {
            background-color: #fff3cd;
            border-color: #ffeeba;
            color: #856404;
            border-radius: 8px;
            padding: 15px;
            font-size: 0.95rem;
            text-align: center;
            margin-bottom: 20px;
        }
        .package-card {
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            border: 1px solid #eee;
        }
        .package-card:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .package-card.selected {
            border: 2px solid #009ef7;
            box-shadow: 0 0 15px rgba(0, 158, 247, 0.2);
            transform: translateY(-3px);
        }
        #cardPaymentForm {
            max-width: 450px;
            margin: 30px auto 0 auto;
        }
        #cardPaymentForm h4 {
             margin-bottom: 25px;
        }

        .card-icons {
            position: absolute;
            right: 15px;
            top: 50%; 
            transform: translateY(-50%);
            font-size: 1.5em;
            color: #ccc;
        }
        .card-icons .fab {
            display: none;
            margin-left: 5px;
        }
        .card-icons .fab.active {
            display: inline-block;
        }
        .form-group-relative {
            position: relative;
        }
        .cvv-info {
            cursor: pointer;
            color: #007bff;
            margin-left: 5px;
            font-size: 0.85em;
        }
        .cvv-info:hover {
            text-decoration: underline;
        }
        .card-number-input {
            padding-right: 60px;
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
                                <div class="card">
                                    <div class="card-header border-0 pt-6">
                                        <div class="card-toolbar">
                                            <div class="d-flex justify-content-end align-items-center d-none" data-kt-customer-table-toolbar="selected">
                                                <div class="fw-bold me-5">
                                                    <span class="me-2" data-kt-customer-table-select="selected_count"></span>Seçildi
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0">
                                        <div class="row" style="margin: auto; padding: 20px;">
                                            <?php if ($success): ?>
                                                <div class="alert alert-success">
                                                    ✅ Ödeme başarılı! Teşekkür ederiz.
                                                </div>
                                            <?php elseif ($error): ?>
                                                <div class="alert alert-danger">
                                                    ❌ Ödeme başarısız: <?= htmlspecialchars($error) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <?php
                                        $packages = $data;
                                        ?>
                                        
                                        <div id="packageSelectionAndPaymentContainer" class="main-card-container">
                                            <h4 class="payment-card-title">Paket Seçimi ve Ödeme</h4>

                                            <form id="purchaseForm" class="mb-5">
                                                <div class="row g-4"> 
                                                    <?php if (!empty($packages)): ?>
                                                        <?php foreach ($packages as $package): ?>
                                                            <div class="col-md-4">
                                                                <div class="card h-100 package-card" 
                                                                     data-package-id="<?= $package['id'] ?>" 
                                                                     data-package-price="<?= $package['price'] ?>"
                                                                     data-package-name="<?= htmlspecialchars($package['name']) ?>"
                                                                     data-package-type="<?= htmlspecialchars($package['type']) ?>"
                                                                     data-package-limit="<?= $package['limit_count'] ?>">
                                                                    <div class="card-body d-flex flex-column">
                                                                        <h5 class="card-title text-primary fw-bold"><?= htmlspecialchars($package['name']) ?></h5>
                                                                        <p class="card-text flex-grow-1">
                                                                            Tür: <strong><?= htmlspecialchars($package['type']) ?></strong><br>
                                                                            <?php if ($package['type'] === 'Koçluk' || $package['type'] === 'Rehberlik'): ?>
                                                                                Süre: <strong><?= $package['limit_count'] ?> ay</strong><br>
                                                                            <?php else: ?>
                                                                                Adet: <strong><?= $package['limit_count'] ?> ders</strong><br>
                                                                            <?php endif; ?>
                                                                        </p>
                                                                        <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top">
                                                                            <h4 class="mb-0 text-success"><strong><?= number_format($package['price'], 2, ',', '.') ?> TL</strong></h4>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="radio" name="package_id_selection" value="<?= $package['id'] ?>" required>
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
                                                <input type="hidden" name="user_id_selection" value="<?= $currentUserId ?>">
                                                <div class="text-end mt-4">
                                                    <button type="submit" class="btn btn-success" id="selectPackageButton">İleri ve Ödeme Bilgileri</button>
                                                </div>
                                            </form>

                                            <div id="cardPaymentForm" class="d-none">
                                                <h4 class="payment-card-title">Kart Bilgilerini Giriniz</h4>
                                                
                                                <div class="alert alert-info" role="alert">
                                                    <strong>Güvenli Ödeme:</strong> Kart bilgileriniz doğrudan Tami Sanal POS'un güvenli sunucularına iletilmektedir.
                                                </div>

                                                <form id="paymentDetailsForm" action="https://api.tami.com/direct_post_url" method="POST" target="_self"> 
                                                    
                                                    <input type="hidden" name="order_id" id="tamiOrderId" value="<?= htmlspecialchars($orderId) ?>">
                                                    <input type="hidden" name="amount" id="tamiAmount" value="<?= number_format($amount, 2, '.', '') ?>"> 
                                                    <input type="hidden" name="currency" value="TRY"> 
                                                    <input type="hidden" name="installmentCount" value="1"> <input type="hidden" name="motoInd" value="false">
                                                    <input type="hidden" name="paymentGroup" value="PRODUCT">
                                                    <input type="hidden" name="paymentChannel" value="WEB">

                                                    <input type="hidden" name="merchant_id" value="<?= htmlspecialchars($merchantId) ?>">
                                                    <input type="hidden" name="terminal_id" value="<?= htmlspecialchars($terminalId) ?>">
                                                    <input type="hidden" name="success_url" value="<?= htmlspecialchars($successUrl) ?>">
                                                    <input type="hidden" name="failure_url" value="<?= htmlspecialchars($failureUrl) ?>">
                                                    <input type="hidden" name="cancel_url" value="<?= htmlspecialchars($cancelUrl) ?>">
                                                    
                                                    <input type="hidden" name="buyer_id" value="<?= htmlspecialchars($buyerId) ?>">
                                                    <input type="hidden" name="buyer_name" value="<?= htmlspecialchars($buyerName) ?>">
                                                    <input type="hidden" name="buyer_surname" value="<?= htmlspecialchars($buyerSurname) ?>">
                                                    <input type="hidden" name="buyer_email" value="<?= htmlspecialchars($buyerEmail) ?>">
                                                    <input type="hidden" name="buyer_phone" value="<?= htmlspecialchars($buyerPhoneNumber) ?>">
                                                    <input type="hidden" name="buyer_ip_address" value="<?= htmlspecialchars($buyerIpAddress) ?>">
                                                    
                                                    <input type="hidden" name="security_hash" id="tamiSecurityHash" value=""> 

                                                    <div class="mb-3 form-group-relative">
                                                        <label for="cardNumber" class="form-label">Kart Numarası</label>
                                                        <input type="text" class="form-control card-number-input" id="cardNumber" name="card_number" placeholder="XXXX XXXX XXXX XXXX" maxlength="19" inputmode="numeric" autocomplete="cc-number" required>
                                                        <div class="card-icons">
                                                            <i class="fab fa-cc-visa" data-card-type="visa"></i>
                                                            <i class="fab fa-cc-mastercard" data-card-type="mastercard"></i>
                                                            <i class="fab fa-cc-amex" data-card-type="amex"></i>
                                                            <i class="fab fa-cc-discover" data-card-type="discover"></i>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="cardHolder" class="form-label">Kart Üzerindeki İsim</label>
                                                        <input type="text" class="form-control" id="cardHolder" name="card_holder_name" placeholder="Ad Soyad" autocomplete="cc-name" required>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <label for="expiryDate" class="form-label">Son Kullanma Tarihi (AA/YY)</label>
                                                            <input type="text" class="form-control" id="expiryDate" placeholder="AA/YY" maxlength="5" inputmode="numeric" autocomplete="cc-exp" required>
                                                            <input type="hidden" id="expiryMonthHidden" name="expiry_month">
                                                            <input type="hidden" id="expiryYearHidden" name="expiry_year">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="cvv" class="form-label">CVV <span class="cvv-info" data-bs-toggle="tooltip" data-bs-placement="top" title="Kartınızın arkasındaki 3 veya 4 haneli güvenlik kodu."><i class="fas fa-question-circle"></i></span></label>
                                                            <input type="password" class="form-control" id="cvv" name="cvv" placeholder="XXX" maxlength="4" inputmode="numeric" autocomplete="cc-csc" required>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <button type="button" class="btn btn-secondary" id="backToPackageSelection">Geri</button>
                                                        <button type="submit" class="btn btn-primary" id="finalizePaymentButton">Ödemeyi Tamamla</button>
                                                    </div>
                                                </form>
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
            let selectedPackagePrice = 0; // Seçilen paketin fiyatını tutmak için
            let selectedPackageName = '';

            // PHP'den gelen Order ID ve Miktar başlangıç değerleri
            const initialOrderId = $('#tamiOrderId').val();
            let currentOrderId = initialOrderId; // Sipariş ID'sini dinamik olarak takip etmek için

            // Tooltip'leri başlat (CVV bilgi ikonu için)
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Paket kartlarının tıklanabilir olması ve seçili stilini alma
            $('.package-card').on('click', function() {
                $(this).find('input[type="radio"]').prop('checked', true).trigger('change');
            });

            // Radyo butonu değiştiğinde seçili kartı vurgula ve paketin ID/fiyatını sakla
            $('input[name="package_id_selection"]').on('change', function() {
                $('.package-card').removeClass('selected');
                const $selectedCard = $(this).closest('.package-card');
                $selectedCard.addClass('selected');
                selectedPackageId = $selectedCard.data('package-id');
                selectedPackagePrice = parseFloat($selectedCard.data('package-price'));
                selectedPackageName = $selectedCard.data('package-name');
            });

            // "İleri ve Ödeme Bilgileri" butonuna basıldığında
            $('#purchaseForm').on('submit', function(e) {
                e.preventDefault();

                if (!selectedPackageId) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Paket Seçimi',
                        text: 'Lütfen satın almak istediğiniz bir paketi seçin.',
                        confirmButtonText: 'Tamam'
                    });
                    return;
                }

                // Yeni bir orderId oluştur ve inputa yaz (her ödeme denemesi için farklı olması iyi olur)
                currentOrderId = 'ORDER_' + Date.now() + Math.floor(Math.random() * 1000);
                $('#tamiOrderId').val(currentOrderId);

                // Seçilen paketin fiyatını Tami'ye gönderilecek input'a ata (0.00 formatında)
                $('#tamiAmount').val(selectedPackagePrice.toFixed(2)); 
                
                // Eğer security_hash PHP tarafından dinamik olarak oluşturuluyorsa
                // burada AJAX ile backend'e bir istek atıp hash'i almanız ve hidden input'a yazmanız gerekebilir.
                // Eğer Tami'nin Direct Post'u sadece sabit API key/Merchant ID ile çalışıyorsa, hash'e gerek kalmayabilir.

                // Örnek: Eğer güvenlik hash'i dinamik olarak PHP'de oluşturulup forma basılacaksa:
                // ŞİMDİLİK BU KISIM YORUM SATIRINDA, Tami'nin Direct Post dokümanlarına göre düzenlenmeli.
                /*
                $.ajax({
                    url: 'generate_tami_hash.php', // Bu PHP dosyası hash'i oluşturup dönecek
                    type: 'POST',
                    data: {
                        orderId: currentOrderId,
                        amount: selectedPackagePrice.toFixed(2),
                        // Diğer hash için gerekli parametreler
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success && response.securityHash) {
                            $('#tamiSecurityHash').val(response.securityHash);
                            // Tüm hazır olunca formu göster
                            $('#purchaseForm').addClass('d-none');
                            $('#cardPaymentForm').removeClass('d-none');
                        } else {
                            Swal.fire('Hata', 'Güvenlik hash\'i oluşturulamadı. Lütfen tekrar deneyin.', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Hata', 'Hash oluşturma servisine ulaşılamadı.', 'error');
                    }
                });
                */

                // Geçici olarak direkt göster (Hash dinamik oluşturulmuyorsa veya başka bir mantık varsa)
                $('#purchaseForm').addClass('d-none');
                $('#cardPaymentForm').removeClass('d-none');
            });

            // "Geri" butonuna basıldığında
            $('#backToPackageSelection').on('click', function() {
                $('#cardPaymentForm').addClass('d-none');
                $('#purchaseForm').removeClass('d-none');
            });

            // Kart Numarası Biçimlendirme ve Kart Tipi Tespiti
            $('#cardNumber').on('input', function() {
                let cardNumber = $(this).val().replace(/\D/g, ''); 
                let formattedCardNumber = '';
                const cardIcons = $('.card-icons .fab');

                let cardType = 'unknown';
                if (/^4/.test(cardNumber)) {
                    cardType = 'visa';
                } else if (/^5[1-5]/.test(cardNumber)) {
                    cardType = 'mastercard';
                } else if (/^3[47]/.test(cardNumber)) {
                    cardType = 'amex';
                } else if (/^6(?:011|5)/.test(cardNumber)) {
                    cardType = 'discover';
                }

                cardIcons.removeClass('active'); 
                $(`.card-icons .fa-cc-${cardType}`).addClass('active'); 

                for (let i = 0; i < cardNumber.length; i++) {
                    if (i > 0 && i % 4 === 0) {
                        formattedCardNumber += ' ';
                    }
                    formattedCardNumber += cardNumber[i];
                }
                $(this).val(formattedCardNumber);
            });

            // Son Kullanma Tarihi Biçimlendirme (AA/YY) ve hidden inputlara ayırma
            $('#expiryDate').on('input', function() {
                let expiryDate = $(this).val().replace(/\D/g, ''); 
                let month = '';
                let year = '';

                if (expiryDate.length > 2) {
                    month = expiryDate.substring(0, 2);
                    year = expiryDate.substring(2, 4);
                    $(this).val(month + '/' + year);
                } else {
                    month = expiryDate;
                    $(this).val(expiryDate);
                }
                
                // Hidden inputlara ay ve yıl değerlerini atama (Tami'nin beklediği format)
                $('#expiryMonthHidden').val(month);
                // Yılı 4 haneli yap (örn: 2040)
                $('#expiryYearHidden').val(year.length === 2 ? '20' + year : year); 
            });

            // CVV alanı için sadece rakam kontrolü
            $('#cvv').on('input', function() {
                $(this).val($(this).val().replace(/\D/g, ''));
            });

            // "Ödemeyi Tamamla" butonuna basıldığında (kart bilgileri formu)
            $('#paymentDetailsForm').on('submit', function(e) {
                // Frontend doğrulamaları
                const cardNumber = $('#cardNumber').val().replace(/\s/g, ''); 
                const cardHolder = $('#cardHolder').val();
                const expiryMonth = parseInt($('#expiryMonthHidden').val()); // Hidden inputtan al
                const expiryYear = parseInt($('#expiryYearHidden').val()); // Hidden inputtan al
                const cvv = $('#cvv').val();

                // Basit frontend doğrulama
                if (!cardNumber || cardNumber.length < 16 || !cardHolder || cardHolder.length === 0 || 
                    isNaN(expiryMonth) || isNaN(expiryYear) || !cvv || cvv.length < 3) {
                    e.preventDefault(); // Hata varsa submit etme
                    Swal.fire({
                        icon: 'error',
                        title: 'Hata',
                        text: 'Lütfen tüm kart bilgilerini doğru formatta doldurun.', 
                        confirmButtonText: 'Tamam'
                    });
                    return;
                }

                // Ay ve Yıl doğru aralıkta mı?
                if (expiryMonth < 1 || expiryMonth > 12) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Hata',
                        text: 'Son kullanma ayı hatalı (AA).',
                        confirmButtonText: 'Tamam'
                    });
                    return;
                }

                // Geçerlilik tarihi kontrolü
                const currentYearFull = new Date().getFullYear();
                const currentMonth = new Date().getMonth() + 1; 

                if (expiryYear < currentYearFull || (expiryYear === currentYearFull && expiryMonth < currentMonth)) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Hata',
                        text: 'Kartınızın süresi dolmuş.',
                        confirmButtonText: 'Tamam'
                    });
                    return;
                }
                
                // TÜM DOĞRULAMALAR GEÇERSE
                // Swal.fire ile loading ekranı gösterebiliriz, ancak Direct Post'ta sayfa yönleneceği için
                // bu SweetAlert tam olarak görünmeyebilir veya kullanıcı hızlıca yönlenebilir.
                // Genellikle Direct Post'ta kullanıcıya "Yönlendiriliyorsunuz..." gibi kısa bir mesaj yeterlidir.
                Swal.fire({
                    title: 'Ödeme Yönlendiriliyor...',
                    html: 'Güvenli ödeme için Tami Sanal POS sayfasına yönlendiriliyorsunuz. Lütfen bekleyiniz.',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });

                // Formun doğrudan Tami'nin "action" URL'sine POST edilmesine izin ver
                // e.preventDefault() çağrısı kaldırılır veya yukarıdaki if bloklarında kalırsa sadece hata durumunda engellenir.
            });

        });
    </script>
</body>
</html>
<?php
} else {
    // Rol yetersizse index sayfasına yönlendir
    header("location: index");
    exit(); 
}
?>