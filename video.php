<?php 
define('GUARD', true);

?>

<video id="my-video" controls width="640" height="360">
    <source src="https://oznarmaden.com/lineup/api/video/get-2.php?video=DSC_1781as.mp4" type="video/mp4">
    Tarayıcınız bu video formatını desteklemiyor.
</video>
<!--
<script>
    // Yukarıdaki JavaScript kodunu buraya veya ayrı bir JS dosyasına ekleyebilirsiniz.
    document.addEventListener('DOMContentLoaded', function() {
        const videoPlayer = document.getElementById('my-video');
        const videoSource = videoPlayer.querySelector('source');
        const videoToLoad = 'DSC_1781as.mp4'; // Oynatmak istediğiniz video dosya adı

        fetch('https://oznarmaden.com/lineup/api/video/get-2.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded', // Form verisi göndermek için
            },
            body: new URLSearchParams({
                'video': videoToLoad
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        /*.then(data => {
            if (data && data.url) {
                videoSource.src = data.url;
                videoPlayer.load(); // Video kaynağını yükle
            } else {
                console.error('Beklenen URL bilgisi alınamadı.', data);
            }
        })*/
        .catch(error => {
            console.error('Video çekme hatası:', error);
        });
    });
</script>

<video controls width="640" height="360">
  <source src="/video-proxy.php?video=DSC_1781as.mp4" type="video/mp4">
  Tarayıcınız bu video formatını desteklemiyor.
</video>-->