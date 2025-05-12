<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    // Sunucu Ayarları
    $mail->SMTPDebug = SMTP::DEBUG_OFF;                      // Geliştirme için detaylı hata çıktısı (DEBUG_SERVER veya DEBUG_CONNECTION)
    $mail->isSMTP();                                            // SMTP kullanarak gönder
    $mail->Host       = 'zm35.ihszimbra.com';                     // SMTP sunucunuz (örneğin, mail.alanadiniz.com)
    $mail->SMTPAuth   = true;                      // SMTP parolanız
    $mail->SMTPSecure = false;         // TLS şifrelemesi (SSL için PHPMailer::ENCRYPTION_SMTPS kullanın)
    $mail->SMTPAutoTLS = false;                                 // SMTP kimlik doğrulaması gerekli
    $mail->Username   = 'aepikman@chemitech.com.tr';                 // SMTP kullanıcı adınız
    $mail->Password   = '01051913BBo!';   
    $mail->Port       = 587;                                    // TLS için port (SSL için genellikle 465)
    $mail->CharSet    = 'UTF-8';                                // Karakter kodlaması

    // Alıcılar
    $mail->setFrom('aepikman@chemitech.com.tr', 'Gönderen Adı');
    $mail->addAddress('aepikman@gmail.com', 'Alıcı Adı');     // İsteğe bağlı olarak isim ekleyebilirsiniz
    //$mail->addCC('cc@baskaalan.com');
    //$mail->addBCC('bcc@baskaalan.com');

    // Ekler (isteğe bağlı)
    //$mail->addAttachment('/var/tmp/file.tar.gz');
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // İsteğe bağlı olarak farklı bir isimle

    // İçerik
    $mail->isHTML(true);                                  // E-posta içeriği HTML formatında mı
    $mail->Subject = 'Burada E-posta Konusu Yer Alır';
    $mail->Body    = 'Bu e-postanın <b>HTML</b> içeriğidir.';
    $mail->AltBody = 'Bu da HTML desteklemeyen istemciler için düz metin içeriğidir.';

    $mail->send();
    echo 'E-posta başarıyla gönderildi!';
} catch (Exception $e) {
    echo "E-posta gönderilirken bir hata oluştu: {$mail->ErrorInfo}";
}