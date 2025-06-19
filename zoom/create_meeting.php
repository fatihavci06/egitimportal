<?php
require 'ZoomTokenManager.php';

try {
    $zoom = new ZoomTokenManager();
    $access_token = $zoom->getAccessToken();

    $user_id = 'me'; // kendi hesabın

    $meeting_details = [
        'topic' => 'Deneme Toplantısı',
        'type' => 2, // planlanmış toplantı
        'start_time' => date('Y-m-d\TH:i:s', strtotime('+10 minutes')), // 10 dakika sonrası
        'duration' => 30, // dakika
        'timezone' => 'Europe/Istanbul',
        'agenda' => 'Test toplantısı açıklaması',
        'settings' => [
            'host_video' => true,
            'participant_video' => true,
            'join_before_host' => false,
        ],
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.zoom.us/v2/users/{$user_id}/meetings");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $access_token",
        "Content-Type: application/json",
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($meeting_details));

    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);

    if (isset($data['join_url'])) {
        echo "Toplantı başarıyla oluşturuldu!<br>";
        echo "Katılma Linki: <a href='{$data['join_url']}' target='_blank'>{$data['join_url']}</a><br>";
        echo "Meeting ID: {$data['id']}";
    } else {
        echo "Toplantı oluşturulamadı: " . $response;
    }

} catch (Exception $e) {
    echo "Hata: " . $e->getMessage();
}
