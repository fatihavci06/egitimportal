<?php

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
  $stmt4 = $pdo->prepare('SELECT * from packages_lnp where id=?');
  $stmt4->execute([$packageId]);
  $package = $stmt4->fetch(PDO::FETCH_ASSOC);

  return $package;
}
header('Content-Type: application/json');

try {
  $upgradePackageId= $_GET['package_id'];
  $packageId=$_SESSION['package_id'];
  $amount=getPackageInfo($upgradePackageId,$pdo)['credit_card_fee'] - getPackageInfo($packageId,$pdo)['credit_card_fee'];

  $failCallbackUrl = $_POST['fail_callback_url'] ?? "https://lineupcampus.com/online/tami-sanal-pos/upgrade_packages/callback2";
  $successCallbackUrl = $_POST['success_callback_url'] ?? "https://lineupcampus.com/online/tami-sanal-pos/upgrade_packages/callback";
  if (isset($_POST['telephone'])) {
    $telephone = $_POST['telephone'];
  } else {
    $userId = $_SESSION['id'];
    $userInfo = getUserInfo($userId, $pdo);
    $telephone = "9" . $userInfo['telephone'];
  }

  $orderId = getGUID();

  $_SESSION['extra_package_id'] = $_POST['package_id'] ?? null;

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
