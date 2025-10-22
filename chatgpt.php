<?php
session_start();
if (isset($_SESSION['role'])) {
    include_once "classes/dbh.classes.php";
    $pdo = new Dbh();
    $pdo = $pdo->connect();
    $stmt = $pdo->prepare("SELECT * FROM openai_lnp where id=1");
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $apiKey = $data['api_key'];

    $input = json_decode(file_get_contents("php://input"), true);
    $userMessage = trim($input['message'] ?? '');

    // Boş mesaj kontrolü
    if ($userMessage === '') {
        echo json_encode([
            'choices' => [
                ['message' => ['content' => 'Lütfen bir mesaj girin.']]
            ]
        ]);
        exit;
    }
date_default_timezone_set('Europe/Istanbul'); // Türkiye saati
$today = date('d.m.Y'); // 22.10.2025 formatında örnek tarih
    // OpenAI API verisi
    $data = [
        "model" => "gpt-3.5-turbo",
        "messages" => [
            [
            "role" => "system",
            "content" => "Bugünün tarihi: $today. Sen bu bilgiyi kullanarak sorulara yanıt ver."
        ],
            [
                "role" => "user",
                "content" => $userMessage
            ]
        ],
        "temperature" => 0.7
    ];

    // cURL ile istek
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => "https://api.openai.com/v1/chat/completions",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $apiKey",
            "Content-Type: application/json"
        ],
        CURLOPT_POSTFIELDS => json_encode($data)
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    // Yanıtı döndür
    header('Content-Type: application/json');
    echo $response;
}
