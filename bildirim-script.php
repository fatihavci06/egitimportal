<?php
include_once "classes/dbh.classes.php";
include_once "classes/classes.classes.php";
include_once "classes/sms.classes.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
try {
    $pdo = new Dbh();

    // Ayarları çek
    $sql = "SELECT * FROM settings_lnp";
    $stmt = $pdo->connect()->prepare($sql);
    $stmt->execute();
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$settings) {
        throw new Exception('Ayarlar bulunamadı.');
    }

    // Ayarları değişkenlere al
    $notificationStartDay = (int)$settings['notification_start_day']; // Örn: 7 (abonelik bitişinden önceki gün sayısı)
    $notificationCount = (int)$settings['notification_count'];       // Örn: 3 (toplam bildirim sayısı)
    $notifySms = (int)$settings['notify_sms'];                       // SMS bildirimi açık mı?
    $notifyEmail = (int)$settings['notify_email'];                   // Email bildirimi açık mı?

    if ($notificationCount <= 0) {
        throw new Exception('Bildirim sayısı 0 veya negatif olamaz.');
    }

    // Kullanıcıları al, örneğin rol = 2 (aboneler gibi)
    $sqlUsers = "SELECT * FROM users_lnp WHERE role = 2 or role=10002";
    $stmtUsers = $pdo->connect()->prepare($sqlUsers);
    $stmtUsers->execute();
    $users = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);

    if (!$users) {
        echo "Bildirim için kullanıcı bulunamadı.";
        exit;
    }

    $today = new DateTime('today'); // Bugünün tarihi (sadece tarih kısmı önemli)

    // Bildirim günlerini hesapla: başlangıç, ortası, bitiş
    // Örnek: notificationStartDay=7, notificationCount=3 ise bildirimler:
    // 7 gün önce, 3-4 gün önce (yarısı), 0 gün önce (bitiş günü)
    $notificationDays = [
        $notificationStartDay,
        (int) round($notificationStartDay / 2),
        0
    ];

    foreach ($users as $user) {
        if (empty($user['subscribed_end'])) {
            continue; // Abonelik bitiş tarihi yoksa atla
        }

        $subscribedEnd = new DateTime($user['subscribed_end']); // Abonelik bitiş tarihi

        // Bildirim yapılacak her gün için kontrol et
        foreach ($notificationDays as $daysBeforeEnd) {
            $notificationDay = clone $subscribedEnd;
            $notificationDay->modify("-{$daysBeforeEnd} days");

            // Eğer bugün bildirim yapılacak günse
            if ($notificationDay->format('Y-m-d') === $today->format('Y-m-d')) {
                $smsTemplate = $settings['sms_template'] ?? 'Sayın {name} {surname}, aboneliğiniz {subscribed_end} tarihinde sona erecek.';

                $placeholders = [
                    '{name}'           => $user['name'] ?? '',
                    '{surname}'        => $user['surname'] ?? '',
                    '{subscribed_end}' => $subscribedEnd->format('d.m.Y'),
                ];

                $message = str_replace(array_keys($placeholders), array_values($placeholders), $smsTemplate);
                if ($notifySms === 1 && !empty($user['telephone'])) {
                    sendSms($user['telephone'], $message);
                } else {
                    echo " --- SMS gönderimi kapalı veya telefon numarası yok.\n";
                }

                if ($notifyEmail === 1 && !empty($user['email'])) {
                    sendEmail($user['email'], 'Abonelik Bildirimi', $message);
                } else {
                    echo " --- Email gönderimi kapalı veya email adresi yok.\n";
                }

                // Bu kullanıcı için bildirim gönderildi, diğer bildirim tarihlerini kontrol etmeye gerek yok
                break;
            }
        }
    }

    echo "Bildirim işlemi tamamlandı.\n";
} catch (Exception $e) {
    echo "Hata: " . $e->getMessage();
}

// SMS gönderim fonksiyonu (örnek)
function sendSms($phoneNumber, $message)
{
    $sms=new Sms();
    $sms->sendSms($phoneNumber,$message);
    if (!$phoneNumber) return;
   
     $sms->sendSms($phoneNumber,$message);
     echo "Sms gönderildi: {$phoneNumber} - Konu: {$message}\n";
}

// Email gönderim fonksiyonu (örnek)
function sendEmail($email, $subject, $message)
{
    if (!$email) return;
    $mail = new PHPMailer(true);

    try {
        // Sunucu ayarları
        $mail->isSMTP();
        $mail->Host       = 'mail.lineupcampus.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'eposta@lineupcampus.com';    // Mail adresiniz
        $mail->Password   = 'Y6RrEZgH4mwfb!x3';                        // Şifreniz (gerçek şifreyle değiştirin)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true,
            ],
        ];
        // Gönderen bilgisi
        $mail->setFrom('eposta@lineupcampus.com', 'Lineup Campus');

        // Alıcı
        $mail->addAddress($email, 'Alıcı İsmi'); // Alıcı adresini değiştirin

        // İçerik
        $mail->isHTML(true);
        $mail->Subject = $subject ?? null;
        $mail->Body    = $message ?? null;
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';
        $mail->send();
        echo 'Mail başarıyla gönderildi.';
    } catch (Exception $e) {
        echo "Mail gönderilemedi. Hata: {$mail->ErrorInfo}";
    }
    // Burada mail() veya SMTP ile gönderim yap
    echo "Email gönderildi: {$email} - Konu: {$subject}\n";
}
