<?php
include_once "classes/dbh.classes.php";
include_once "classes/classes.classes.php";

try {
    $pdo = new Dbh;

    // Ayarları çek
    $sql = "SELECT * FROM settings_lnp";
    $stmt = $pdo->connect()->prepare($sql);
    $stmt->execute();
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$settings) {
        throw new Exception('Ayarlar bulunamadı.');
    }

    $notificationStartDay = (int)$settings['notification_start_day']; // örn 7
    $notificationCount = (int)$settings['notification_count']; // örn 3
    $notifySms = (int)$settings['notify_sms'];
    $notifyEmail = (int)$settings['notify_email'];


    // Bildirim aralığı gün olarak
    if ($notificationCount <= 0) {
        throw new Exception('Bildirim sayısı 0 veya negatif olamaz.');
    }
    $interval = $notificationStartDay / $notificationCount; // örn 7 / 3 = 2.33 gün
  

    // Kullanıcıları al, burada rol = 2 gibi filtre koyabilirsin
    $sqlUsers = "SELECT * FROM users_lnp WHERE role = 2";
    $stmtUsers = $pdo->connect()->prepare($sqlUsers);
    $stmtUsers->execute();
    $users = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);

    if (!$users) {
        echo "Bildirim için kullanıcı bulunamadı.";
        exit;
    }

    $today = new DateTime('today'); // sadece tarih kısmı önemli
    

    foreach ($users as $user) {
        if (empty($user['subscribed_end'])) {
          
            continue; // boşsa atla
        }

        $subscribedEnd = new DateTime($user['subscribed_end']);
        // Bildirim başlangıç tarihi (abonelik bitişinden geriye notificationStartDay gün)
        $notificationStartDate = clone $subscribedEnd;
        $notificationStartDate->modify("-{$notificationStartDay} days");

        

        // Eğer bugün bildirim aralığı içindeyse kontrol et
        if ($today < $notificationStartDate || $today > $subscribedEnd) {
            // Bildirim dönemi dışında
            
            continue;
        }

        // Bildirim yapılacak günleri hesapla ve bugün bunlardan biri mi?
        $notificationSent = false;
        for ($i = 0; $i < $notificationCount; $i++) {
            $notificationDay = clone $notificationStartDate;
            // interval float, tam sayıya çeviriyoruz
            $daysToAdd = (int) round($interval * $i);
            $notificationDay->modify("+{$daysToAdd} days");


            if ($notificationDay->format('Y-m-d') == $today->format('Y-m-d')) {
                
                // Bugün bildirim yapılacak
                $message = "Sayın {$user['name']} {$user['surname']}, aboneliğiniz {$subscribedEnd->format('d.m.Y')} tarihinde sona erecek.";

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
                $notificationSent = true;
                break; // bu kullanıcı için bugünkü bildirim yapıldı, döngüden çık
            }
        }

       
    }

    echo "Bildirim işlemi tamamlandı.\n";

} catch (Exception $e) {
    echo "Hata: " . $e->getMessage();
}

// SMS gönderim fonksiyonu (örnek)
function sendSms($phoneNumber, $message) {
    if (!$phoneNumber) return;
    // Burada kendi SMS API çağrını yap
    echo "SMS gönderildi: {$phoneNumber} - Mesaj: {$message}\n";
}

// Email gönderim fonksiyonu (örnek)
function sendEmail($email, $subject, $message) {
    if (!$email) return;
    // Burada mail() veya SMTP ile gönderim yap
    echo "Email gönderildi: {$email} - Konu: {$subject}\n";
}
?>
