<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  // POST ile gelinmediyse, kullanıcıyı başka bir sayfaya yönlendir
  header('Location: ../index'); // Yönlendirilecek sayfanın URL'sini buraya yazın
  exit(); // Yönlendirmeden sonra kodun çalışmasını durdur
}

require 'securityHashV2.php';
require 'lib/common_lib.php';
require_once './../classes/dbh.classes.php';
include_once('./../classes/packages.classes.php');

$db = new Dbh();
$pdo = $db->connect();
session_start();
$package = new Packages();

function getUserInfo($userId, $pdo)
{
  $stmt4 = $pdo->prepare('SELECT * from users_lnp where id=?');
  $stmt4->execute([$userId]);
  $user = $stmt4->fetch(PDO::FETCH_ASSOC);
  return $user;
}

function getPackageInfo($packageId, $pdo)
{
  $stmt4 = $pdo->prepare('SELECT * from extra_packages_lnp where id=?');
  $stmt4->execute([$packageId]);
  $package = $stmt4->fetch(PDO::FETCH_ASSOC);
  return $package;
}
header('Content-Type: application/json');

try {
  $amount = $_POST['amount'] ?? null;
  $failCallbackUrl = $_POST['fail_callback_url'] ?? "https://lineupcampus.com/online/tami-sanal-pos/extra_packages/callback2";
  $successCallbackUrl = $_POST['success_callback_url'] ?? "https://lineupcampus.com/online/tami-sanal-pos/extra_packages/callback";
  if (isset($_POST['telephone'])) {
    $telephone = $_POST['telephone'];
  } else {
    $userId = $_SESSION['id'];
    $userInfo = getUserInfo($userId, $pdo);
    $telephone = "9" . $userInfo['telephone'];
  }

  $orderId = getGUID();

  $_SESSION['extra_package_id'] = $_POST['package_id'] ?? null;
  $packageType = getPackageInfo($_POST['package_id'], $pdo)['type'];

  if ($packageType == 'Psikoloji') {
    $_SESSION['extra_package_type'] = $packageType;
  }

  if (isset($_POST['islem']) AND $_POST['islem'] == "hesap-olustur") {
    $packInfo = $package->getPackagePrice(htmlspecialchars(trim($_POST['packageId'])));
    $coupon = $package->checkCoupon($_SESSION['couponCode']);
    if ($coupon) {
      $discount_value = $coupon['discount_value'];
      $discount_type = $coupon['discount_type'];
    }
    foreach ($packInfo as $key => $value) {
      $packageName = $value['name'];
      //$price = $value['monthly_fee'] * $value['subscription_period'];
      $amount = $value['credit_card_fee'];
      if ($discount_type === 'amount') {
        $amount -= $discount_value;
      } else if ($discount_type === 'percentage') {
        $amount -= $amount * ($discount_value / 100);
      }
    }
  }


  $_SESSION['paidPrice'] = $amount;

  $_SESSION['orderId'] = $orderId;

  $body = json_encode([
    "mobilePhoneNumber" => $telephone,
    "failCallbackUrl" => $failCallbackUrl,
    "successCallbackUrl" => $successCallbackUrl,
    "orderId" => $orderId,
    "amount" => $amount
  ]);

  $securityHash = JWTSignatureGenerator::generateJWKSignature($merchantId, $terminalId, $secretKey, $body, $fixedKidValue, $fixedKValue);

  $url = $serviceEndpoint . '/hosted/create-one-time-hosted-token';

  $ch = curl_init($url);

  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POST, true);

  $formattedHeaders = [];

  foreach ($headers as $key => $value) {
    $formattedHeaders[] = "$key: $value";
  }

  curl_setopt($ch, CURLOPT_HTTPHEADER, $formattedHeaders);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

  $response = curl_exec($ch);

  if (curl_errno($ch)) {
    echo json_encode([
      'success' => false,
      'message' => 'cURL error: ' . curl_error($ch)
    ]);
    curl_close($ch);
    exit();
  }

  $responseArray = json_decode($response, true);


  if (!isset($responseArray['oneTimeToken'])) {
    echo json_encode([
      'success' => false,
      'message' => 'Token alınamadı.',
      'responseArray' => $response
    ]);
    curl_close($ch);
    exit();
  }

  $_SESSION['tami-token'] = $responseArray['oneTimeToken'];

  echo json_encode([
    'success' => true,
    'oneTimeToken' => $responseArray['oneTimeToken']
  ]);

  curl_close($ch);
  exit();
} catch (Exception $e) {
  echo json_encode([
    'success' => false,
    'message' => 'Hata: ' . $e->getMessage()
  ]);
  exit();
}
