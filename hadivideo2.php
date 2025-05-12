<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Google\Cloud\Storage\StorageClient;

require __DIR__ . '/vendor/autoload.php';

// Google Cloud Storage ayarları
$bucketName = 'denebucket';
$objectName = 'DSC_1781as.mp4';
$allowedReferer = 'https://' . $_SERVER['HTTP_HOST'] . '/'; // İzin verilen referans (sitenizin domaini)

// İmzalı URL oluşturma
function getSignedUrl(string $bucketName, string $objectName, string $referer, int $expiresAfterSeconds = 30): string
{
$storage = new StorageClient(['keyFilePath' => 'nifty-harmony-377808-76fd1a86296f.json']);
    $bucket = $storage->bucket($bucketName);
    $object = $bucket->object($objectName);
    $expiresAt = new \DateTime(sprintf('+%d seconds', $expiresAfterSeconds));

    $signedUrl = $object->signedUrl($expiresAt, [
        'version' => 'v4',
        'queryParams' => [
            'origin' => urlencode($referer) ,
        ],
    ]);

    return $signedUrl;
}

// Kullanım örneği
$signedUrl = getSignedUrl($bucketName, $objectName, $allowedReferer);

/*echo '<video width="640" height="360" controls>
  <source src="' . $signedUrl . '" type="video/mp4">
  Tarayıcınız bu video formatını desteklemiyor.
</video>';*/

echo $signedUrl;
?>


<!DOCTYPE html>
<html>
<head>
    <title>Video Oynatıcı</title>
</head>
<body>

<h2>Video Oynatıcı</h2>

<video width="640" height="360" controls>
  <source src="<?php echo $signedUrl; ?>" type="video/mp4">
  Tarayıcınız bu video formatını desteklemiyor.
</video>

<p>
    <strong>Önemli:</strong> Videonun doğrudan açılmasını engellemek için tarayıcınızın geliştirici araçlarını kullanarak "Ağ (Network)" sekmesinde video isteğinin "Referer" başlığının <strong><?php echo $allowedReferer; ?></strong> ile eşleştiğini kontrol edin.
</p>

<p>
    <strong>Geliştirici Araçlarını Açmak İçin:</strong>
    <ul>
        <li>Google Chrome: F12 tuşuna basın veya sağ tıklayıp "İncele" seçeneğini seçin. "Ağ" sekmesine gidin.</li>
        <li>Mozilla Firefox: F12 tuşuna basın veya sağ tıklayıp "Öğeyi İncele" seçeneğini seçin. "Ağ" sekmesine gidin.</li>
        <li>Safari: "Geliştir" menüsünü etkinleştirin (Safari > Tercihler > İleri Düzey) ve ardından sağ tıklayıp "Öğeyi İncele" seçeneğini seçin. "Ağ" sekmesine gidin.</li>
    </ul>
</p>

<p>
    Video isteğini bulun (genellikle dosya adı "video.mp4" veya benzeri olacaktır) ve başlıklarını inceleyin. "Referer" başlığının değerinin <strong><?php echo $allowedReferer; ?></strong> olduğundan emin olun. Eğer bu başlık yoksa veya farklı bir değer içeriyorsa, imzalı URL'niz doğru şekilde yapılandırılmamış olabilir veya tarayıcı bazı durumlarda başlığı göndermiyor olabilir.
</p>

</body>
</html>