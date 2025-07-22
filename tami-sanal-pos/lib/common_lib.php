<?php
$serviceEndpoint = "https://paymentapi.tami.com.tr";
$merchantId = "77012194";
$terminalId = "84012196";
$secretKey = "565a032e-886d-489c-bb8a-388a8d105d56";
$fixedKidValue = '00ff6ea8-3511-4d04-946c-ba569208306f';
$fixedKValue = '87919a8f-957b-427b-ae12-167622ab52b5';
$callbackUrl = "https://gbtunelemulator-d.fw.garantibbva.com.tr/secure3d";

if (!function_exists('calculateSHA256')) {
    function calculateSHA256($data)
    {
        $hash = hash('sha256', $data, true);
        $sha256Base64 = base64_encode($hash);
        return $sha256Base64;
    }
}

if (!function_exists('generateBasicAuthHeader')) {
    function generateBasicAuthHeader($username, $password)
    {
        $credentials = base64_encode($username . ':' . $password);
        return 'Basic ' . $credentials;
    }
}

if (!function_exists('getGUID')) {
    function getGUID()
    {
        if (function_exists('random_bytes')) {
            return bin2hex(random_bytes(16));
        } elseif (function_exists('mt_rand')) {
            return bin2hex(pack('H*', mt_rand()));
        } else {
            return uniqid();
        }
    }
}

$headers = [
    'Content-Type' => 'application/json',
    'Accept-Language' => 'tr',
    'PG-Api-Version' => 'v2',
    'PG-Auth-Token' => $merchantId . ":" . $terminalId. ":". calculateSHA256($merchantId.$terminalId.$secretKey),
    'correlationId' => getGUID()
];

