<?php
// zoom_callback.php
session_start();

$clientId = "TMjUAkYSR6BH_Ho7RUVw";       // Kendi Client ID'nizi yazın
$clientSecret = "kLJHdd4Zf7VQAqtwUHPC7QwA72RXsSQd"; // Kendi Client Secret'ınızı yazın
$redirectUri = "http://localhost/lineup_campus/zoom_callback.php";

function curlPost($url, $postFields, $headers) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return [$httpCode, $response];
}

if (isset($_GET['code'])) {
    $code = $_GET['code'];

    // 1) Access token alma isteği
    $tokenUrl = "https://zoom.us/oauth/token";
    $headers = [
        "Authorization: Basic " . base64_encode($clientId . ":" . $clientSecret),
        "Content-Type: application/x-www-form-urlencoded"
    ];
    $postFields = http_build_query([
        "grant_type" => "authorization_code",
        "code" => $code,
        "redirect_uri" => $redirectUri
    ]);

    list($httpCode, $response) = curlPost($tokenUrl, $postFields, $headers);

    if ($httpCode == 200) {
        $tokenData = json_decode($response, true);

        // Tokenları session'a kaydet
        $_SESSION['zoom_access_token'] = $tokenData['access_token'];
        $_SESSION['zoom_refresh_token'] = $tokenData['refresh_token'];

        // 2) Toplantı oluşturma
        $accessToken = $tokenData['access_token'];
        $zoomUserId = "me"; // kendi kullanıcınız için 'me' kullanabilirsiniz

        // Toplantı zamanı (örnek: 2025-06-20 15:30 Türkiye saati)
        $meetingDate = "2025-06-20";
        $meetingTime = "15:30:00";
        $startTime = $meetingDate . "T" . $meetingTime . "+03:00";

        $meetingData = [
            "topic" => "OAuth ile Zoom Toplantısı",
            "type" => 2, // Scheduled meeting
            "start_time" => $startTime,
            "duration" => 60, // dakika
            "timezone" => "Europe/Istanbul",
            "settings" => [
                "join_before_host" => false,
                "approval_type" => 0,
                "registration_type" => 1
            ]
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.zoom.us/v2/users/$zoomUserId/meetings");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($meetingData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $accessToken",
            "Content-Type: application/json"
        ]);

        $meetingResponse = curl_exec($ch);
        $meetingHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($meetingHttpCode == 201) {
            $meeting = json_decode($meetingResponse, true);
            echo "<h2>Toplantı başarıyla oluşturuldu!</h2>";
            echo "Katılım URL'si: <a href='" . htmlspecialchars($meeting['join_url']) . "' target='_blank'>" . htmlspecialchars($meeting['join_url']) . "</a><br>";
            echo "Meeting ID: " . htmlspecialchars($meeting['id']) . "<br>";
        } else {
            echo "Toplantı oluşturulamadı. HTTP Code: $meetingHttpCode<br>";
            echo htmlspecialchars($meetingResponse);
        }

    } else {
        echo "Access token alınamadı. HTTP Code: $httpCode<br>";
        echo htmlspecialchars($response);
    }

} else {
    // Yetkilendirme kodu yoksa, auth sayfasına gitmek için link ver
    echo "Yetkilendirme kodu bulunamadı.<br>";
    echo "<a href='zoom_auth.php'>Zoom ile giriş yap ve toplantı oluştur</a>";
}
