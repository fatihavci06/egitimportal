<?php
// Klasör ve dosya yolu
$logDirectory = '/logs/';
if (!file_exists($logDirectory)) {
    mkdir($logDirectory, 0777, true); // klasörü oluştur
}

// Dosya adını zaman damgası ile oluştur (örnek: request_log_2025-06-23_162233.txt)
$logFile = $logDirectory . 'request_log_' . date('Y-m-d_His') . '.txt';

// Log içeriğini oluştur
$logContent = "=== GET VERİLERİ ===\n" . print_r($_GET, true);
$logContent .= "\n=== POST VERİLERİ ===\n" . print_r($_POST, true);
$logContent .= "\n=== REQUEST VERİLERİ ===\n" . print_r($_REQUEST, true);
$logContent .= "\n=== HAM GİRİŞ (php://input) ===\n" . file_get_contents('php://input');

// Log dosyasına yaz
file_put_contents($logFile, $logContent);

// İsteğe bağlı: işlem sonucu çıktı
echo "Veriler log dosyasına yazıldı: " . basename($logFile);
?>