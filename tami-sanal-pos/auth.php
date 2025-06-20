<?php
require 'securityHashV2.php';
require 'lib/common_lib.php';



$orderId = getGUID();
$amount = 1;
$cardNumber = "4355093000777068";
$currency = "TRY";
$installmentCount = 1;

$body = '{
  "currency": "' . $currency . '",
  "installmentCount": ' . $installmentCount . ',
  "motoInd": false,
  "paymentGroup": "PRODUCT",
  "paymentChannel": "WEB",
  "card": {
    "holderName": "Mesut Sarıtaş",
    "cvv": "313",
    "expireMonth": 11,
    "expireYear": 2040,
    "number": "' . $cardNumber . '"
  },
  "billingAddress": {
    "address": "Deneme adresi",
    "city": "İstanbul",
    "companyName": "Deneme Firması",
    "country": "Türkiye",
    "district": "Maltepe",
    "contactName": "Oğuzhan Okur",
    "phoneNumber": "07505555555",
    "zipCode": "34846"
  },
  "shippingAddress": {
    "address": "Deneme adresi",
    "city": "İstanbul",
    "companyName": "Deneme Firması",
    "country": "Türkiye",
    "district": "Maltepe",
    "contactName": "Oğuzhan Okur",
    "phoneNumber": "07505555555",
    "zipCode": "34846"
  },
  "buyer": {
    "ipAddress": "127.0.0.1",
    "buyerId": "cc9be1332c6a4932ab0e9ce0a103cd75",
    "name": "Oğuzhan",
    "surName": "Okur",
    "identityNumber": 11111111111,
    "city": "İstanbul",
    "country": "Türkiye",
    "zipCode": "34846",
    "emailAddress": "destek@garantibbva.com.tr",
    "phoneNumber": "07325555555",
    "registrationAddress": "Maltepe",
    "lastLoginDate": "2023-08-04T11:52:05.151",
    "registrationDate": "2023-07-25T11:52:05.151"
  },
  "basket": {
    "basketId": "fac5e1c664fe4ebabeef949dd4c6d7d7",
    "basketItems": [
      {
        "itemId": "4388002",
        "name": "Lego",
        "itemType": "PHYSICAL",
        "category": "Oyuncak",
        "subCategory": "Çocuk Oyunu",
        "numberOfProducts": 1,
        "totalPrice": 1,
        "unitPrice": 1
      }
    ]
  },
  "orderId": "' . $orderId . '",
  "amount": "' . $amount . '"
}';

$securityHash = JWTSignatureGenerator::generateJWKSignature($merchantId, $terminalId, $secretKey, $body, $fixedKidValue, $fixedKValue);
$bodyArray = json_decode($body, true);
$bodyArray['securityHash'] = $securityHash;
$updatedBody = json_encode($bodyArray);
$url = $serviceEndpoint . '/payment/auth';

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);

// Header'ları düzenle
$formattedHeaders = [];
foreach ($headers as $key => $value) {
    $formattedHeaders[] = "$key: $value";
}
curl_setopt($ch, CURLOPT_HTTPHEADER, $formattedHeaders);

// Body verisi
curl_setopt($ch, CURLOPT_POSTFIELDS, $updatedBody);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Hata: ' . curl_error($ch);
} else {
    echo $response;
}

curl_close($ch);
