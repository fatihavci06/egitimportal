<?php
session_start();

if (isset($_SESSION['role'])) {
    $apiKey = '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'; // Buraya OpenAI API key'inizi yazın

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

    // OpenAI API verisi
    $data = [
        "model" => "gpt-3.5-turbo",
        "messages" => [
            [
                "role" => "system",
                "content" => "Sen yalnızca eğitimle ilgili konulara cevap veren bir asistansın. Özellikle anaokulu, ilköğretim (1. sınıftan 8. sınıfa kadar) ve ortaöğretim düzeyindeki (lise) sorulara cevap ver. Eğitim dışındaki sorulara sadece 'Bu sistem yalnızca eğitimle ilgili soruları yanıtlamaktadır.' şeklinde cevap ver."
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
} else {
    echo "Yetkisiz erişim";
    exit();
}
