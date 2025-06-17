<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
$success = $_SESSION['payment_success'] ?? null;
$error = $_SESSION['payment_error'] ?? null;

// Oturumu temizle
unset($_SESSION['payment_success'], $_SESSION['payment_error']);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 10001)) {
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";

    include_once "views/pages-head.php";
    $class = new Classes();
    $data = $class->getExtraPackageList();
?>
    <style>
        #paymentIframeContainer {
            max-width: 900px;
            margin: auto;
        }

        #paymentIframeContainer iframe {
            width: 100% !important;
            height: 700px !important;
            border: none;
        }
    </style>

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
                                            <div class="card-title">
                                                <div class="d-flex align-items-center position-relative my-1">
                                                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                    <input type="text" data-kt-customer-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder=" Ara" />
                                                </div>
                                            </div>
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
                                                    <div style="padding: 20px; background-color: #d4edda; color: #155724; border-radius: 5px;">
                                                        ✅ Ödeme başarılı! Teşekkür ederiz.
                                                    </div>
                                                <?php elseif ($error): ?>
                                                    <div style="padding: 20px; background-color: #f8d7da; color: #721c24; border-radius: 5px;">
                                                        ❌ Ödeme başarısız: <?= htmlspecialchars($error) ?>
                                                    </div>

                                                <?php endif; ?>
                                            </div>
                                            <?php
                                            $packages = $data;
                                            ?>
                                            <div id="packageSelectionFormContainer">
                                                <form id="purchaseForm">
                                                    <div class="row">
                                                        <?php foreach ($packages as $package): ?>
                                                            <div class="col-md-4">
                                                                <div class="card mb-4 package-card" data-package-id="<?= $package['id'] ?>">
                                                                    <div class="card-body">
                                                                        <h5 class="card-title"><?= htmlspecialchars($package['name']) ?></h5>
                                                                        <p class="card-text">
                                                                            Tür: <?= htmlspecialchars($package['type']) ?><br>
                                                                            <?php if ($package['type'] === 'Koçluk' || $package['type'] === 'Rehberlik'): ?>
                                                                                Süre: <?= $package['limit_count'] ?> ay<br>
                                                                            <?php else: ?>
                                                                                Adet: <?= $package['limit_count'] ?> ders<br>
                                                                            <?php endif; ?>
                                                                            Fiyat: <strong><?= number_format($package['price'], 2, ',', '.') ?> TL</strong>
                                                                        </p>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio" name="package_id" value="<?= $package['id'] ?>" required>
                                                                            <label class="form-check-label">Seç</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                    <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?? 1 ?>">
                                                    <div class="text-end mt-3"> <!-- Butonu sağa yaslayan kısım -->
                                                        <button type="submit" class="btn btn-success btn-sm" id="purchaseButton">İleri ve Satın Al</button>
                                                    </div>
                                                </form>
                                            </div>

                                            <div id="paymentIframe" class="mt-4 d-none"></div>

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

        <script>
            $(document).ready(function() {
                // DataTable başlat (Eğer kullanılıyorsa, mevcut kodda bir DataTable elementi görünmüyor ama bu script dahil edilmiş)
                // document.querySelectorAll('input[name="package_id"]').forEach(radio => { ... }); yerine jQuery kullanımı
                $('input[name="package_id"]').on('change', function() {
                    $('.package-card').removeClass('selected'); // Tüm kartlardan selected sınıfını kaldır
                    $(this).closest('.package-card').addClass('selected'); // Seçili kartı işaretle
                });

                // Seçilen kartın stilini belirlemek için CSS
                $('head').append(`
                    <style>
                        .package-card.selected {
                            border: 2px solid #009ef7; /* Veya istediğiniz bir vurgu rengi */
                            box-shadow: 0 0 15px rgba(0, 158, 247, 0.2);
                        }
                    </style>
                `);

                $('#purchaseForm').on('submit', function(e) {
                    e.preventDefault(); // Formun varsayılan gönderimini engelle

                    const formData = new FormData(this);
                    const $formContainer = $('#packageSelectionFormContainer');
                    const $paymentIframeContainer = $('#paymentIframe');
                    const $purchaseButton = $('#purchaseButton');

                    // 1. Formu gizle ve yükleniyor göstergesi ekle
                    $paymentIframeContainer.removeClass('d-none').html(`
                        <div class="d-flex flex-column justify-content-center align-items-center" style="min-height: 200px;">
                            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                                <span class="visually-hidden">Yükleniyor...</span>
                            </div>
                            <p class="ms-3 mt-3 text-muted">Ödeme ekranı yükleniyor, lütfen bekleyiniz...</p>
                        </div>
                    `); // Yükleniyor spinner'ı ve mesajını göster
                    $purchaseButton.attr('disabled', true).addClass('btn-light'); // Butonu deaktif et ve stilini değiştir

                    fetch("iyzico/extra_packages/iyzico_checkout.php", {
                            method: "POST",
                            body: formData
                        })
                        .then(res => res.text())
                        .then(data => {
                            // Yükleniyor göstergesini temizle
                            $paymentIframeContainer.html('');

                            const parser = new DOMParser();
                            const doc = parser.parseFromString(data, "text/html");

                            // Gelen script içeriklerini ayrıştır ve çalıştır
                            doc.querySelectorAll("script").forEach(oldScript => {
                                const newScript = document.createElement("script");
                                if (oldScript.src) {
                                    newScript.src = oldScript.src;
                                } else {
                                    newScript.textContent = oldScript.textContent;
                                }
                                // Script'leri doğrudan paymentIframeContainer'a ekle
                                $paymentIframeContainer.append(newScript);
                            });

                            // Diğer HTML içeriklerini (varsa) ekle
                            doc.body.childNodes.forEach((node) => {
                                if (node.nodeName !== 'SCRIPT' && node.nodeType === Node.ELEMENT_NODE) {
                                    $paymentIframeContainer.append(node.cloneNode(true));
                                }
                            });
                        })
                        .catch(err => {
                            console.error("Hata:", err);
                            Swal.fire("Hata", "Ödeme başlatılırken bir sorun oluştu. Lütfen tekrar deneyin.", "error");

                            // Hata durumunda formu tekrar göster
                            $formContainer.removeClass('d-none');
                            $paymentIframeContainer.addClass('d-none').html(''); // Ödeme alanını gizle ve temizle
                            $purchaseButton.attr('disabled', false).removeClass('btn-light'); // Butonu tekrar aktif et
                        });
                });

            });
        </script>

    </body>

</html>
<?php } else {
    header("location: index");
}
?>