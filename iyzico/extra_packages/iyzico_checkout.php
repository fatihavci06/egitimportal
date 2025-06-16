<?php
session_start();
require_once '../../classes/dbh.classes.php'; // PDO bağlantısı
require_once '../config.php';
$config=new Config();
$options=$config::options();
use Iyzipay\Model\Locale;
use Iyzipay\Request\CreateCheckoutFormInitializeRequest;
use Iyzipay\Model\CheckoutFormInitialize;
use Iyzipay\Model\Buyer;
use Iyzipay\Model\Address;
use Iyzipay\Model\BasketItem;
use Iyzipay\Model\BasketItemType;
use Iyzipay\Model\Currency;
$db = new Dbh();
$pdo = $db->connect();
// 1. Formdan gelen verileri al
$user_id = $_POST['user_id'] ?? null;
$package_id = $_POST['package_id'] ?? null;

if (!$user_id || !$package_id) {
    die("Geçersiz işlem.");
}

// 2. Paketi DB'den çek
$stmt = $pdo->prepare("SELECT * FROM extra_packages_lnp WHERE id = ?");
$stmt->execute([$package_id]);
$package = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$package) {
    die("Paket bulunamadı.");
}

unset($_SESSION['extra_package_id']);
$_SESSION['extra_package_id'] =$package['id'];
// 3. Sipariş oluşturma
$request = new CreateCheckoutFormInitializeRequest();
$request->setLocale(Locale::TR);
$request->setConversationId(uniqid());
$request->setPrice($package['price']);
$request->setPaidPrice($package['price']);
$request->setCurrency(Currency::TL);
$request->setBasketId("B67832");
$request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
$request->setCallbackUrl("http://localhost/lineup_campus/iyzico/extra_packages/callback.php"); // başarı sonucu
$request->setEnabledInstallments([1]);

// Buyer (test verisi, canlıda kullanıcıdan alınır)
$buyer = new Buyer();
$buyer->setId($user_id);
$buyer->setName("Fatih");
$buyer->setSurname("Avcı");
$buyer->setGsmNumber("+905350000000");
$buyer->setEmail("fatih@example.com");
$buyer->setIdentityNumber("11111111111");
$buyer->setRegistrationAddress("Kadıköy İstanbul");
$buyer->setIp($_SERVER['REMOTE_ADDR']);
$buyer->setCity("İstanbul");
$buyer->setCountry("Türkiye");
$request->setBuyer($buyer);

// Adres
$address = new Address();
$address->setContactName("Fatih Avcı");
$address->setCity("İstanbul");
$address->setCountry("Türkiye");
$address->setAddress("Kadıköy Mahallesi");

$request->setShippingAddress($address);
$request->setBillingAddress($address);

// Sepet
$basketItem = new BasketItem();
$basketItem->setId((string)$package['id']);
$basketItem->setName($package['name']);
$basketItem->setCategory1("Ek Paket");
$basketItem->setItemType(BasketItemType::VIRTUAL);
$basketItem->setPrice($package['price']);

$request->setBasketItems([$basketItem]);

// 4. Ödeme formu başlat
$checkoutFormInitialize = CheckoutFormInitialize::create($request, $options);

if ($checkoutFormInitialize->getStatus() == 'success') {
    echo '<div id="paymentIframeContainer" style="max-width:1000px; margin: auto;">';
    echo $checkoutFormInitialize->getCheckoutFormContent(); // Iframe
    echo '</div>';
    echo '
    <style>
    #paymentIframeContainer iframe {
        width: 100% !important;
        height: 700px !important;
    }
    </style>
    ';
} else {
    echo "<div class='alert alert-danger'>Ödeme başlatılamadı: " . $checkoutFormInitialize->getErrorMessage() . "</div>";
}