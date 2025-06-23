<?php
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
  $failCallbackUrl = $_POST['fail_callback_url'] ?? "http://localhost/canlitest/tami-sanal-pos/callback_url.php";
  $successCallbackUrl = $_POST['success_callback_url'] ?? "http://localhost/canlitest/tami-sanal-pos/callback_url.php";
  if(isset($_POST['telephone']))
  {
    $telephone=$_POST['telephone'];
  }
  else{
    $userId = $_SESSION['id'];
    $userInfo = getUserInfo($userId, $pdo);
    $telephone=$userInfo['telephone'];
  }
  
  $orderId = getGUID();
  
 
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
