<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require __DIR__ . '/vendor/autoload.php';

use Google\Cloud\Storage\StorageClient;

// Ayarlar
$bucketName = 'denebucket';
$keyFilePath = 'nifty-harmony-377808-76fd1a86296f.json';

// İstenen video adını GET ile al
$videoName = $_GET['video'] ?? null;

if (!$videoName) {
    http_response_code(400);
    echo "Video adı belirtilmedi.";
    exit;
}

$storage = new StorageClient(['keyFilePath' => $keyFilePath]);
$bucket = $storage->bucket($bucketName);
$object = $bucket->object($videoName);

try {
    $stream = $object->downloadAsStream();
    $info = $object->info();

    // Doğru başlıkları istemciye gönder
    header('Content-Type: ' . $info['contentType']);
    header('Content-Length: ' . $info['size']);
    header('Accept-Ranges: bytes');

    // Video verisini istemciye akış yap
    while (!$stream->feof()) {
        echo $stream->read(8192); // Veriyi parça parça oku ve gönder
        flush(); // Tamponu temizle ve veriyi gönder
    }
} catch (\Google\Cloud\Core\Exception\NotFoundException $e) {
    http_response_code(404);
    echo "Video bulunamadı.";
} catch (\Exception $e) {
    http_response_code(500);
    echo "Bir hata oluştu: " . $e->getMessage();
} finally {
    if (isset($stream)) {
        $stream->close();
    }
}
?>