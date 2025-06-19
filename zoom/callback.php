<?php
$client_id = '6zXzyMU2SVOdy5ExzSr8ug';
$client_secret = 'iCIUVWAi47TQxvfX3I0OXDVdPmSfmZq1';
$redirect_uri = 'http://localhost/lineup_campus/zoom/callback.php';

if (!isset($_GET['code'])) {
    die('Authorization code yok!');
}

$code = $_GET['code'];

$token_url = "https://zoom.us/oauth/token";
$postFields = http_build_query([
    'grant_type' => 'authorization_code',
    'code' => $code,
    'redirect_uri' => $redirect_uri,
]);

$headers = [
    "Authorization: Basic " . base64_encode("$client_id:$client_secret"),
    "Content-Type: application/x-www-form-urlencoded"
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $token_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

if (isset($data['access_token'])) {
    // Süre sonuna 60 saniye buffer ekle
    $data['expires_at'] = time() + $data['expires_in'] - 60;
    file_put_contents('access_token.json', json_encode($data));
   header("Location: http://localhost/lineup_campus/toplanti-olustur");
} else {
    echo "Token alınamadı: " . $response;
}
