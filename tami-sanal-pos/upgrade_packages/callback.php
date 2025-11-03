<?php
session_start();
define('GUARD', true);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../classes/dbh.classes.php';
require_once '../../classes/Mailer.php';

$mailer = new Mailer();
$db = new Dbh();
$pdo = $db->connect();

$userId = $_SESSION['id'] ?? null;
$packageId = $_SESSION['upgrade_package_id'] ?? null;
$paymentId = $_SESSION['upgrade_order_id'] ?? null;
$price = $_SESSION['upgrade_paid_price'] ?? 0; // KDV dahil toplam

// Abonelik süresi çek
$stmt0 = $pdo->prepare("SELECT subscription_period FROM packages_lnp WHERE package_id = ? LIMIT 1");
$stmt0->execute([$packageId]);
$subscriptionData = $stmt0->fetch(PDO::FETCH_ASSOC);
$subscriptionPeriod = $subscriptionData['subscription_period'] ?? 0;

// Ay sayısı belirle (en az 1)
$monthsToAdd = max(1, (int)$subscriptionPeriod);

// KDV oranını al
$stmtSettings = $pdo->prepare("SELECT tax_rate FROM settings_lnp LIMIT 1");
$stmtSettings->execute();
$settings = $stmtSettings->fetch(PDO::FETCH_ASSOC);

$kdvRatio = isset($settings['tax_rate']) ? (float)$settings['tax_rate'] : 20.0;
$kdvRateDecimal = $kdvRatio / 100;

// KDV hesapla
$priceExclKdv = $price / (1 + $kdvRateDecimal);
$kdvAmount = $price - $priceExclKdv;

// Ödeme kaydı ekle
$stmtInsert = $pdo->prepare("
    INSERT INTO package_payments_lnp  
    (user_id, pack_id, order_no, payment_type, payment_status, pay_amount, kdv_amount, kdv_percent)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");

$stmtInsert->execute([
    $userId,
    $packageId,
    $paymentId,
    1,  // payment_type
    2,  // payment_status
    round($priceExclKdv, 2),
    round($kdvAmount, 2),
    $kdvRatio
]);

// Abonelik bitiş tarihini hesapla
$subscribedEnd = (new DateTime())->modify("+{$monthsToAdd} months")->format('Y-m-d H:i:s');

// Kullanıcı paketini güncelle
$stmtUpdate = $pdo->prepare("
    UPDATE users_lnp 
    SET package_id = ?, subscribed_end = ? 
    WHERE id = ?
");
$stmtUpdate->execute([$packageId, $subscribedEnd, $userId]);

// Mail gönderimi
$_SESSION['payment_success'] = true;
$to_email = $_SESSION['email'] ?? '';
$subject = 'Paket Bilgilendirmesi';
$body = 'Paketiniz başarıyla yükseltildi. Yeni paketinizle birlikte sunulan avantajlardan faydalanmaya başlayabilirsiniz.';

if ($to_email) {
    $mailer->send($to_email, $subject, $body);
}
$_SESSION['package_id'] = $packageId;
// Yönlendirme
echo '
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
Swal.fire({
  title: "Başarılı!",
  text: "Paketiniz başarıyla yükseltildi.",
  icon: "success",
  confirmButtonText: "Tamam"
}).then(() => {
  window.location.href = "/online/";
});
</script>
';
exit;
header("Location: /online/");
exit;
