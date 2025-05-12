<?php
// Yüklenen resimlerin kaydedileceği dizin
$target_dir = "../assets/media/topics/";

// Yüklenen dosyanın adı
$target_file = $target_dir . rand(0, 99999) . '-' . basename($_FILES["file"]["name"]);

// Dosya yükleme hatalarını kontrol et
if (isset($_FILES["file"])) {
  if ($_FILES["file"]["error"] == UPLOAD_ERR_OK) {
    // Dosyayı sunucuya taşı
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
      // TinyMCE'ye geri bildirim gönder
      $start = 3;
      $newFile = substr($target_file, $start);
      echo json_encode(array('location' => $newFile));
    } else {
      http_response_code(500);
      echo json_encode(array('error' => 'Dosya yükleme hatası.'));
    }
  } else {
    http_response_code(500);
    echo json_encode(array('error' => 'Dosya yükleme hatası.'));
  }
} else {
  http_response_code(400);
  echo json_encode(array('error' => 'Dosya bulunamadı.'));
}
?>