<?php
require __DIR__ . '/../../vendor/autoload.php'; // Composer autoload dosyası

use Google\Cloud\Storage\StorageClient;

// Google Cloud Storage Ayarları
$bucketName = 'denebucket';
$objectName = $_GET['video'] ?? null; // İstemciden istenen video dosya adı
$keyFilePath = '../../nifty-harmony-377808-76fd1a86296f.json'; // Servis hesabı anahtar dosyanız
$allowedDomain = 'https://oznarmaden.com/'; // İzin verilen domain

// İstemci isteğini doğrula (Referer kontrolü örneği)
//$referer = 'https://' . $_SERVER['HTTP_HOST'] . '/' ?? null;
$referer = 'https://' . $_SERVER['HTTP_HOST'] . '/' ?? null;

if (strpos($referer, $allowedDomain) !== 0) {
    http_response_code(403);
    echo json_encode(['error' => 'Yetkisiz erişim.']);
    exit;
}

if (!$objectName) {
    http_response_code(400);
    echo json_encode(['error' => 'Video adı belirtilmedi.']);
    exit;
}

// İmzalı URL Oluşturma Fonksiyonu (önceki örnekten alınabilir)
function getSignedUrl(string $bucketName, string $objectName, string $referer, string $keyFilePath, int $expiresAfterSeconds = 3600): string
{
    $storage = new StorageClient(['keyFilePath' => $keyFilePath]);
    $bucket = $storage->bucket($bucketName);
    $object = $bucket->object($objectName);
    $expiresAt = new \DateTime(sprintf('+%d seconds', $expiresAfterSeconds));

    $signedUrl = $object->signedUrl($expiresAt, [
        'version' => 'v4',
        'queryParams' => [
            'goog-referer' => $referer,
        ],
    ]);

    return $signedUrl;
}

// İmzalı URL oluştur
$signedUrl = getSignedUrl($bucketName, $objectName, $allowedDomain, $keyFilePath);

// HTTP Yönlendirmesi (302 Redirect)
header("Location: " . $signedUrl, true, 302);
exit;

// Alternatif olarak, videoyu proxy yapabilirsiniz (daha karmaşık):
// ... Google Cloud Storage'dan videoyu çekip istemciye akış yapma kodları ...
?>