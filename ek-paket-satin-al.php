<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
$success = $_SESSION['payment_success'] ?? null;
$error = $_SESSION['payment_error'] ?? null;

// Oturumu temizle
unset($_SESSION['payment_success'], $_SESSION['payment_error']);

// Kullanıcı rol kontrolü, kendi sisteminize göre düzenlemelisiniz
if (isset($_SESSION['role']) && ($_SESSION['role'] == 1 || $_SESSION['role'] == 2)) {
    // Veritabanı ve sınıf dahil etme
    include_once "classes/dbh.classes.php"; // Kendi Dbh sınıf dosyanız
    include "classes/classes.classes.php"; // Kendi Classes sınıf dosyanız

    include_once "views/pages-head.php"; // Başlık ve meta etiketleri gibi sayfa başlığı kısmı
    $class = new Classes();
    $data = $class->getExtraPackageList(); // Paket listesini çeken fonksiyon
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ödeme Sayfası</title>
    
    <?php // include_once "views/pages-head.php"; // Eğer bu dosya sadece head içeriğini dahil ediyorsa burada kalmalı ?>

    <style>
       
        .main-card-container { /* Ana kapsayıcı */
            background-color: #ffffff;
            padding: 40px;
            border-radius: 15px; /* Daha yuvarlak köşeler */
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); /* Daha belirgin gölge */
            width: 100%;
            max-width: 900px; /* Genişlik, hem paket hem de kart formu için uygun */
            border: 1px solid #e0e0e0; /* Hafif kenarlık */
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
            border-radius: 8px; /* Input alanları için yuvarlak köşeler */
            padding: 12px 15px;
            border: 1px solid #ddd;
            transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        .form-control:focus {
            border-color: #007bff; /* Bootstrap'in birincil rengi */
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
            border: 2px solid #009ef7; /* Vurgu rengi */
            box-shadow: 0 0 15px rgba(0, 158, 247, 0.2);
            transform: translateY(-3px);
        }
        #cardPaymentForm {
            max-width: 450px; /* Kart formunu ortalamak için */
            margin: 30px auto 0 auto; /* Üstten boşluk */
        }
        #cardPaymentForm h4 {
             margin-bottom: 25px; /* Kart formu başlığı için boşluk */
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
                                                <div class="row g-4"> <?php if (!empty($packages)): ?>
                                                        <?php foreach ($packages as $package): ?>
                                                            <div class="col-md-4">
                                                                <div class="card h-100 package-card" data-package-id="<?= $package['id'] ?>">
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
                                                <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?? 1 ?>">
                                                <div class="text-end mt-4">
                                                    <button type="submit" class="btn btn-success" id="selectPackageButton">İleri ve Ödeme Bilgileri</button>
                                                </div>
                                            </form>

                                            <div id="cardPaymentForm" class="d-none">
                                                <h4 class="payment-card-title">Kart Bilgilerini Giriniz</h4>
                                              
                                                <form id="paymentDetailsForm">
                                                    <div class="mb-3">
                                                        <label for="cardNumber" class="form-label">Kart Numarası</label>
                                                        <input type="text" class="form-control" id="cardNumber" placeholder="XXXX XXXX XXXX XXXX" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="cardHolder" class="form-label">Kart Üzerindeki İsim</label>
                                                        <input type="text" class="form-control" id="cardHolder" placeholder="Ad Soyad" required>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <label for="expiryMonth" class="form-label">Son Kullanma Ayı</label>
                                                            <input type="text" class="form-control" id="expiryMonth" placeholder="MM" maxlength="2" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="expiryYear" class="form-label">Son Kullanma Yılı</label>
                                                            <input type="text" class="form-control" id="expiryYear" placeholder="YY" maxlength="2" required>
                                                        </div>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="cvv" class="form-label">CVV</label>
                                                        <input type="password" class="form-control" id="cvv" placeholder="XXX" maxlength="4" required>
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
            // Paket kartlarının tıklanabilir olması ve seçili stilini alma
            $('.package-card').on('click', function() {
                $(this).find('input[type="radio"]').prop('checked', true).trigger('change');
            });

            // Radyo butonu değiştiğinde seçili kartı vurgula
            $('input[name="package_id"]').on('change', function() {
                $('.package-card').removeClass('selected'); // Tüm kartlardan selected sınıfını kaldır
                $(this).closest('.package-card').addClass('selected'); // Seçili kartı işaretle
            });

            // "İleri ve Ödeme Bilgileri" butonuna basıldığında
            $('#purchaseForm').on('submit', function(e) {
                e.preventDefault(); // Formun varsayılan gönderimini engelle

                const selectedPackage = $('input[name="package_id"]:checked').val();
                if (!selectedPackage) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Paket Seçimi',
                        text: 'Lütfen satın almak istediğiniz bir paketi seçin.',
                        confirmButtonText: 'Tamam'
                    });
                    return; // Paket seçilmediyse işlemi durdur
                }

                // Paket seçim formunu gizle, kart formunu göster
                $('#purchaseForm').addClass('d-none');
                $('#cardPaymentForm').removeClass('d-none');
                
                // Seçilen paketin ID'sini kart formuna bir şekilde taşıyabilirsiniz,
                // örneğin bir hidden input ile veya JavaScript değişkeninde tutarak.
                // const selectedPackageId = selectedPackage; // selectedPackage değişkeninde zaten var.
            });

            // "Geri" butonuna basıldığında
            $('#backToPackageSelection').on('click', function() {
                $('#cardPaymentForm').addClass('d-none');
                $('#purchaseForm').removeClass('d-none');
            });

            // "Ödemeyi Tamamla" butonuna basıldığında (kart bilgileri formu)
            $('#paymentDetailsForm').on('submit', function(e) {
                e.preventDefault(); // Formun varsayılan gönderimini engelle

                // Burası, kart bilgilerini işleyeceğiniz yerdir.
                // TEKRAR VURGULUĞORUM: Burada asla kart bilgilerini doğrudan sunucunuza göndermeyin!
                // Güvenli bir ödeme geçidi (Payment Gateway) SDK'sı veya API'si kullanmalısınız.
                // Örneğin: Stripe.js ile token oluşturma, Iyzico'nun kendi JS kütüphanesini kullanma vb.

                const cardNumber = $('#cardNumber').val();
                const cardHolder = $('#cardHolder').val();
                const expiryMonth = $('#expiryMonth').val();
                const expiryYear = $('#expiryYear').val();
                const cvv = $('#cvv').val();

                // Sadece görsel bir geri bildirim için alert/Swal kullanıyoruz
                Swal.fire({
                    icon: 'info',
                    title: 'Ödeme Denemesi',
                    html: `
                        <p>Kart Numarası: ${cardNumber.replace(/.(?=.{4})/g, '*')}</p>
                        <p>Kart Sahibi: ${cardHolder}</p>
                        <p>SKT: ${expiryMonth}/${expiryYear}</p>
                        <p>CVV: ***</p>
                        <hr>
                        <p>Bu form sadece bir **TASARIM ÖRNEĞİDİR.**</p>
                        <p>Gerçek ödeme işlemleri için **mutlaka güvenli bir ödeme geçidi entegrasyonu** kullanmalısınız.</p>
                        <p>Kart bilgileri asla doğrudan sunucunuzda işlenmemelidir!</p>
                    `,
                    confirmButtonText: 'Anladım',
                    customClass: {
                        popup: 'swal2-responsive'
                    }
                });

                // Başarılı bir token oluşturma veya ödeme API çağrısı sonrası
                // aşağıdaki gibi bir yönlendirme veya durum güncellemesi yapılabilir.
                // Örneğin: window.location.href = 'payment_success_page.php';
            });

        });
    </script>
</body>
</html>
<?php
} else {
    // Rol yetersizse index sayfasına yönlendir
    header("location: index");
    exit(); // Yönlendirmeden sonra scriptin çalışmasını durdur
}
?>