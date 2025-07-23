<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // POST ile gelinmediyse, kullanıcıyı başka bir sayfaya yönlendir
    header('Location: ../index'); // Yönlendirilecek sayfanın URL'sini buraya yazın
    exit(); // Yönlendirmeden sonra kodun çalışmasını durdur
}

require 'securityHashV2.php';
require 'lib/common_lib.php';
require_once './../classes/dbh.classes.php';

$db = new Dbh();
$pdo = $db->connect();
session_start();

function getUserInfo($userId, $pdo)
{
  $stmt4 = $pdo->prepare('SELECT * from users_lnp where id=?');
  $stmt4->execute([$userId]);
  $user = $stmt4->fetch(PDO::FETCH_ASSOC);
  return $user;
}
header('Content-Type: application/json');

try {
  $amount = $_POST['amount'] ?? null;
  $failCallbackUrl = $_POST['fail_callback_url'] ?? "https://lineupcampus.com/online/tami-sanal-pos/extra_packages/callback2";
  $successCallbackUrl = $_POST['success_callback_url'] ?? "https://lineupcampus.com/online/tami-sanal-pos/extra_packages/callback";
  if(isset($_POST['telephone']))
  {
    $telephone=$_POST['telephone'];
  }
  else{
    $userId = $_SESSION['id'];
    $userInfo = getUserInfo($userId, $pdo);
    $telephone="9" . $userInfo['telephone'];
  }
  
  $orderId = getGUID();

  $_SESSION['extra_package_id'] = $_POST['package_id'] ?? null;
  
  $_SESSION['paidPrice'] = $amount;
  
  $_SESSION['orderId'] = $orderId;
 
  $body = json_encode([
    "mobilePhoneNumber" => $telephone,
    "failCallbackUrl" => $failCallbackUrl,
    "successCallbackUrl" =>$successCallbackUrl,
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
      'message' => 'Token alınamadı.'
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
