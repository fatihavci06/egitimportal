<?php

session_start();
define('GUARD', true);

/* if (!isset($_SESSION['parentFirstName'])) {
    header("location: index");
} */

require_once '../../classes/dbh.classes.php';
require_once '../../classes/Mailer.php';
require_once '../config.php';
$config = new Config();
$mailer = new Mailer();
$options = $config::options();
$db = new Dbh();
$pdo = $db->connect();

use Iyzipay\Model\Locale;
use Iyzipay\Request\RetrieveCheckoutFormRequest;
use Iyzipay\Model\CheckoutForm;



// 1. İyzico'dan gelen POST verisini yakala
// Genellikle İyzico sadece 'token' gönderir.
$postData = $_POST;

// Tüm POST verisini loglayabilir veya inceleyebilirsin
file_put_contents('iyzico_callback_log.txt', print_r($postData, true), FILE_APPEND);

// 2. Token al
$token = $_POST['token'] ?? null;

if (!$token) {
    die("Token bilgisi yok.");
}

// 3. Token ile ödeme detaylarını sorgula
$request = new RetrieveCheckoutFormRequest();
$request->setLocale(Locale::TR);
$request->setConversationId(""); // Eğer ödeme başlatırken uniqid set ettiysen burada da aynı olmalı
$request->setToken($token);

$checkoutForm = CheckoutForm::retrieve($request, $options);
$response = CheckoutForm::retrieve($request, $options);

// 4. Sonucu kontrol et

if ($response->getStatus() === 'success' && $response->getPaymentStatus() === 'SUCCESS') {
    $userId = $_SESSION['id'] ?? null;
    $packageId = $_SESSION['extra_package_id'] ?? null;  // Burayı tek yap

    $paymentId = $response->getPaymentId();
    $price = $response->getPaidPrice(); // KDV dahil toplam

    $stmt = $pdo->prepare("SELECT tax_rate FROM settings_lnp LIMIT 1");
    $stmt->execute();
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);

    $kdvRatio = $settings && isset($settings['tax_rate']) ? (float)$settings['tax_rate'] : 10.0;
    $kdvRateDecimal = $kdvRatio / 100;

    $priceExclKdv = $price / (1 + $kdvRateDecimal);  // KDV hariç fiyat
    $kdvAmount = $price - $priceExclKdv;            // KDV tutarı
    $totalAmount = $price;                           // KDV dahil toplam

    $stmt = $pdo->prepare("
        INSERT INTO extra_package_payments_lnp  
        (user_id, package_id, price, kdv_ratio, kdv_amount, total_amount, payment_status, payment_method, iyzico_payment_id, commission_fee, commission_rate)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $userId,
        $packageId,
        round($priceExclKdv, 2),  // KDV hariç fiyat
        $kdvRatio,
        round($kdvAmount, 2),
        round($totalAmount, 2),
        'success',
        'iyzico',
        $paymentId,               // iyzico_payment_id olarak paymentId kullan
        $response->getIyziCommissionFee(),
        $response->getIyziCommissionRateAmount()
    ]);

    $_SESSION['payment_success'] = true;
    $to_email = $_SESSION['email'];
    $subject = 'Paket Bilgilendirmesi';
    $body = 'Paketiniz kapsamında gerekli atama işlemi yapılacak olup tarafınıza bilgilendirme emaili gönderilecektir.';

    if ($mailer->send($to_email, $subject, $body)) {
        echo "Metin e-postası başarıyla gönderildi.\n";
    } else {
        echo "Metin e-postası gönderilemedi. Hata: " . $mailer->getErrorInfo() . "\n";
    }
   
} else {
    $_SESSION['payment_success'] = false;
    $_SESSION['payment_error'] = $response->getErrorMessage();
}

// Yönlendirme yap
header("Location: /lineup_campus/ek-paket-satin-al.php");
exit;
