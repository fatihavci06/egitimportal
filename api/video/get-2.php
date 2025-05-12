<?php

if (!defined('GUARD')) {
    die('Direkt erişim yasak!');
}

require __DIR__ . '/../../vendor/autoload.php';

use Google\Cloud\Storage\StorageClient;

// Ayarlar
$bucketName = 'denebucket';
$keyFilePath = '/var/www/vhosts/oznarmaden.com/httpdocs/lineup/nifty-harmony-377808-76fd1a86296f.json'; // Servis hesabı anahtar dosyanız
$allowedDomain = 'https://oznarmaden.com/';
$expiresAfterSeconds = 3600; // İmzalı URL geçerlilik süresi

// İsteğin geldiği domaini kontrol et (Origin başlığını tercih ediyoruz)
$origin = $_SERVER['HTTP_ORIGIN'] ?? null;

if ($allowedDomain !== $allowedDomain) {
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Yetkisiz domainden istek.']);
    exit;
}

// İstenen video adını POST ile al
$videoName = "DSC_1781as.mp4" ?? null;

if (!$videoName) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Video adı belirtilmedi.']);
    exit;
}

// İmzalı URL Oluşturma Fonksiyonu
function getSignedUrl(string $bucketName, string $objectName, string $keyFilePath, int $expiresAfterSeconds = 3600): string
{
    $storage = new StorageClient(['keyFilePath' => $keyFilePath]);
    $bucket = $storage->bucket($bucketName);
    $object = $bucket->object($objectName);
    $expiresAt = new \DateTime(sprintf('+%d seconds', $expiresAfterSeconds));

    $signedUrl = $object->signedUrl($expiresAt, [
        'version' => 'v4',
        // İsteğe bağlı: goog-referer ekleyebilirsiniz (ek bir katman ama tek başına güvenilir değil)
        // 'queryParams' => [
        //     'goog-referer' => $allowedDomain,
        // ],
    ]);

    return $signedUrl;
}

// İmzalı URL oluştur
$signedUrl = getSignedUrl($bucketName, $videoName, $keyFilePath, $expiresAfterSeconds);

// JSON yanıtı olarak imzalı URL'yi döndür
header('Content-Type: application/json');
//echo json_encode(['url' => $signedUrl]);
//exit;

// Alternatif olarak, doğrudan yönlendirme de yapabilirsiniz:
 header("Location: " . $signedUrl, true, 302);
 exit;
?>