

<?php
// ... Google Cloud Storage istemci kitaplığını başlatma ...

use Google\Cloud\Storage\StorageClient;

require __DIR__ . '\vendor\autoload.php';

$bucketName = 'denemevideo2';
$objectName = 'DSC_1781a.mp4';

$storage = new StorageClient(['keyFilePath' => 'nifty-harmony-377808-76fd1a86296f.json']);
$bucket = $storage->bucket($bucketName);
$object = $bucket->object($objectName);
$allowedReferer = 'https://' . $_SERVER['HTTP_HOST'] . '/'; // İzin verilen referans (sitenizin domaini)

$expiresAt = new \DateTime('+1 minutes'); // URL'nin geçerli olacağı süre
$domain = 'oznarmaden.com';
$signedUrl = $object->signedUrl($expiresAt, [
    'responseDisposition' => 'inline; filename="video.mp4"',
    'goog-referer' => $allowedReferer,
]);

// Bu URL'yi JavaScript kodunuza aktarın
echo '<script>const videoUrl = "' . $signedUrl . '";</script>';

//echo $allowedReferer;
?>

<div id="player"></div>

<script src="https://cdn.jsdelivr.net/npm/video.js@7/dist/video.min.js"></script>
<link href="https://vjs.zencdn.net/7.20.3/video-js.css" rel="stylesheet" />

<script>
    alert(videoUrl);
  var player = videojs('player', {
    controls: true,
    sources: [{
      src: videoUrl,
      type: 'video/mp4'
    }]
  });
</script>
    
<video controls width="100%" height="640px">
                                <source src="<?php echo $signedUrl; ?>" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>



