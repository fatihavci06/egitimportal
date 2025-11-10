<?php
// includes/ajax.php
include_once '../classes/dbh.classes.php';
include_once '../classes/Mailer.php';
$mailer = new Mailer();
header('Content-Type: application/json');
session_start();
if (!$_SESSION['role']) {
    echo json_encode(['status' => 'error', 'message' => 'Yetkisiz erişim.']);
    exit();
}
function getTurkishDayName($date)
{
    $timestamp = strtotime($date);
    $day = date('N', $timestamp); // 1 (Pazartesi) ile 7 (Pazar) arası
    $days = [
        1 => 'Pazartesi',
        2 => 'Salı',
        3 => 'Çarşamba',
        4 => 'Perşembe',
        5 => 'Cuma',
        6 => 'Cumartesi',
        7 => 'Pazar'
    ];
    return $days[$day];
}
// Sadece POST isteğini kabul et
function cleanInput(string $data): string
{
    return htmlspecialchars(trim($data), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
function jsonResponse(int $statusCode, string $status, string $message): void
{
    http_response_code($statusCode);
    echo json_encode(['status' => $status, 'message' => $message]);
    exit();
}
function createLiveZoomMeeting($pdo, $title, $date, $userId, $classIds, $role, $contentId = null)
{
    if (!$title || !$date || !$userId || !$classIds) {
        return ['success' => false, 'message' => 'Eksik bilgi gönderildi.'];
    }

    try {
        require_once '../zoom/ZoomTokenManager.php';
        $zoom = new ZoomTokenManager();
        $access_token = $zoom->getAccessToken();

        $start_time = date('Y-m-d\TH:i:s', strtotime($date));
        $zoomUserId = 'me';

        $meeting_details = [
            'topic' => $title,
            'type' => 2,
            'start_time' => $start_time,
            'duration' => 120,
            'timezone' => 'Europe/Istanbul',
            'settings' => [
                'host_video' => true,
                'participant_video' => true,
                'auto_recording' => 'cloud',
            ],
        ];

        // Zoom API isteği
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.zoom.us/v2/users/{$zoomUserId}/meetings");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $access_token",
            "Content-Type: application/json",
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($meeting_details));

        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            error_log("Zoom API Hatası: " . $err);
            return ['success' => false, 'message' => 'Zoom bağlantısı kurulamadı.', 'zoom_error' => $err];
        }

        $zoomResponse = json_decode($response, true);
        if (!isset($zoomResponse['join_url'], $zoomResponse['start_url'])) {
            error_log("Zoom Yanıtı Geçersiz: " . $response);
            return ['success' => false, 'message' => 'Zoom toplantısı oluşturulamadı.', 'zoom_error' => $response];
        }

        $joinUrl = $zoomResponse['join_url'];
        $startUrl = $zoomResponse['start_url'];

        // Çoklu class_id desteği
        $classIdArray = array_filter(array_map('trim', explode(';', $classIds))); // örn: ['10', '11', '12']

        if (empty($classIdArray)) {
            return ['success' => false, 'message' => 'Geçerli bir sınıf ID bulunamadı.'];
        }
        if (!empty($contentId) && is_numeric($contentId)) {
            $stmt0 = $pdo->prepare("SELECT zoom_join_url FROM konusma_kulupleri_zoom_lnp WHERE id = ?");
            $stmt0->execute([$contentId]);
            $zoomJoinUrl = $stmt0->fetchColumn();

            if ($zoomJoinUrl) {
                $stmt01 = $pdo->prepare("DELETE FROM meetings_lnp WHERE zoom_join_url = ?");
                $stmt01->execute([$zoomJoinUrl]);
            }
        }


        $stmt = $pdo->prepare("
            INSERT INTO meetings_lnp 
            (organizer_id, description, meeting_date, zoom_join_url, zoom_start_url, class_id, role) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $insertedCount = 0;

        foreach ($classIdArray as $cid) {
            if (is_numeric($cid)) {
                $result = $stmt->execute([
                    $userId,
                    $title,
                    $date,
                    $joinUrl,
                    $startUrl,
                    $cid,
                    $role
                ]);
                if ($result) {
                    $insertedCount++;
                }
            }
        }

        if ($insertedCount > 0) {
            return [
                'success' => true,
                'message' => 'Canlı ders başarıyla oluşturuldu.',
                'inserted_count' => $insertedCount,
                'zoom_join_url' => $joinUrl,
                'zoom_start_url' => $startUrl
            ];
        } else {
            return ['success' => false, 'message' => 'Veritabanına kayıt yapılamadı.'];
        }
    } catch (Exception $e) {
        error_log("Canlı Video Hatası: " . $e->getMessage());
        return [
            'success' => false,
            'message' => 'Bir hata oluştu.',
            'error' => $e->getMessage()
        ];
    }
}


// Servis kontrolü
$service = $_GET['service'] ?? '';

// Veritabanı bağlantısı (örnek PDO ile)
$pdo = new Dbh();
$pdo = $pdo->connect();

// Servise göre işlem yapılacak switch case yapısı
switch ($service) {
    case 'createCategoryTitle':
        // Gelen ID kontrolü
        if (empty($_POST['title_type']) || empty($_POST['title'])) {
            echo json_encode(['status' => 'error', 'message' => 'Tüm alanlar zorunludur.']);
            exit;
        }

        $titleType = $_POST['title_type'];
        $title = $_POST['title'];
        try {
            // Silme işlemi
            $stmt = $pdo->prepare("INSERT INTO category_titles_lnp (type, title) VALUES (:type, :title)");
            $stmt->execute([
                ':type' => $titleType,
                ':title' => $title
            ]);
            echo json_encode(['status' => 'success', 'message' => 'Başlık başarıyla kaydedildi.']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
        }
        break;
    case 'passivePackage':
        // Gelen ID kontrolü
        $id = $_POST['id'] ?? null;

        if (!$id || !is_numeric($id)) {
            echo json_encode(['status' => 'error', 'message' => 'Geçersiz ID']);
            exit;
        }

        try {
            // Silme işlemi
            $stmt = $pdo->prepare("UPDATE packages_lnp SET status=0 WHERE id = ?");
            $stmt->execute([$id]);

            if ($stmt->rowCount()) {
                echo json_encode(['status' => 'success', 'message' => 'İşlem Başarılı']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Kayıt bulunamadı veya silinemedi.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
        }
        break;
    case 'activatePackage':
        // Gelen ID kontrolü
        $id = $_POST['id'] ?? null;

        if (!$id || !is_numeric($id)) {
            echo json_encode(['status' => 'error', 'message' => 'Geçersiz ID']);
            exit;
        }

        try {
            // Silme işlemi
            $stmt = $pdo->prepare("UPDATE packages_lnp SET status=1 WHERE id = ?");
            $stmt->execute([$id]);

            if ($stmt->rowCount()) {
                echo json_encode(['status' => 'success', 'message' => 'Aktifleştirildi']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Kayıt bulunamadı ']);
            }
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
        }
        break;
    case 'createPackage':

        $packageName = isset($_POST['packageName']) ? cleanInput($_POST['packageName']) : null;
        $classId = isset($_POST['class_id']) ? (int)$_POST['class_id'] : null;
        $subscriptionPeriod = isset($_POST['subscription_period']) ? (int)$_POST['subscription_period'] : null;
        $bankTransferFee = isset($_POST['bank_transfer_fee']) ? (float)$_POST['bank_transfer_fee'] : null;
        $creditCardFee = isset($_POST['credit_card_fee']) ? (float)$_POST['credit_card_fee'] : null;

        // Girdileri doğrula
        try {
            if (!$packageName) {
                throw new Exception('Paket adı boş olamaz.');
            }

            if (!$classId || $classId < 1) {
                throw new Exception('Sınıf seçimi zorunludur.');
            }

            if (
                !isset($subscriptionPeriod) ||
                !is_numeric($subscriptionPeriod) ||
                intval($subscriptionPeriod) != $subscriptionPeriod ||
                $subscriptionPeriod < 1 ||
                $subscriptionPeriod > 12
            ) {
                throw new Exception('Abonelik periyodu 1 ile 12 arasında bir tam sayı olmalıdır.');
            }

            if (!isset($bankTransferFee) || $bankTransferFee < 0) {
                throw new Exception('Havale/EFT ücreti geçerli bir sayı olmalıdır.');
            }

            if (!isset($creditCardFee) || $creditCardFee < 0) {
                throw new Exception('Kredi kartı ücreti geçerli bir sayı olmalıdır.');
            }

            // Veritabanı işlemi
            $stmt = $pdo->prepare("
            INSERT INTO packages_lnp 
                (name, class_id, subscription_period, bank_transfer_fee, credit_card_fee) 
            VALUES (?, ?, ?, ?, ?)
        ");
            $stmt->execute([$packageName, $classId, $subscriptionPeriod, $bankTransferFee, $creditCardFee]);

            if ($stmt->rowCount() > 0) {
                jsonResponse(200, 'success', 'Paket başarıyla eklendi.');
            } else {
                jsonResponse(500, 'error', 'Kayıt eklenemedi.');
            }
        } catch (Exception $e) {
            http_response_code(422);
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
            exit();
        }

        break;


    case 'settingsUpdate':
        $taxRatio = isset($_POST['taxRatio']) ? cleanInput($_POST['taxRatio']) : null;
        $discountRatio = isset($_POST['discountRatio']) ? cleanInput($_POST['discountRatio']) : null;
        $notifySms = isset($_POST['notifySms']) ? cleanInput($_POST['notifySms']) : null;
        $notifyEmail = isset($_POST['notifyEmail']) ? cleanInput($_POST['notifyEmail']) : null;
        $notificationStartDay = isset($_POST['notificationStartDay']) ? cleanInput($_POST['notificationStartDay']) : null;
        $notificationCount = isset($_POST['notificationCount']) ? cleanInput($_POST['notificationCount']) : null;
        $smsTemplate = isset($_POST['smsTemplate']) ? cleanInput($_POST['smsTemplate']) : null;

        try {
            if (!$taxRatio) {
                throw new Exception('Vergi oranı boş olamaz!');
            }
            if ($taxRatio <= 0) {
                throw new Exception('Vergi oranı 0 veya negatif olamaz!');
            }
            if ($taxRatio > 100) {
                throw new Exception('Vergi oranı 100\'den büyük olamaz!');
            }
            if (!is_numeric($notificationCount) || intval($notificationCount) < 0 || intval($notificationCount) > 5) {
                throw new Exception('Bildirim sayısı 0 ile 5 arasında bir tamsayı olmalıdır!');
            }
            if (!is_numeric($notificationStartDay) || intval($notificationStartDay) < 0 || intval($notificationStartDay) > 100) {
                throw new Exception('Bildirim başlangıç günü 0 ile 100 arasında bir tamsayı olmalıdır!');
            }


            $notificationCount = intval($notificationCount);
            $notificationStartDay = intval($notificationStartDay);

            // Önce mevcut kayıt var mı kontrol edilir
            $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM settings_lnp WHERE school_id = 1");
            $checkStmt->execute();
            $exists = $checkStmt->fetchColumn();

            if ($exists) {
                // Güncelleme
                $updateStmt = $pdo->prepare("UPDATE settings_lnp SET tax_rate = ?, discount_rate = ?,sms_template=?, notify_sms = ?, notify_email = ?, notification_start_day = ?, notification_count = ? WHERE school_id = 1");
                $updateStmt->execute([$taxRatio, $discountRatio, $smsTemplate, $notifySms, $notifyEmail, $notificationStartDay, $notificationCount]);

                jsonResponse(200, 'success', 'Ayarlar güncellendi.');
            } else {
                // Yeni kayıt ekleme
                $insertStmt = $pdo->prepare("INSERT INTO settings_lnp (school_id, tax_rate, discount_rate, notify_sms, notify_email, notification_start_day, notification_count,sms_template) VALUES (1, ?, ?, ?, ?, ?, ?,?)");
                $insertStmt->execute([$taxRatio, $discountRatio, $notifySms, $notifyEmail, $notificationStartDay, $notificationCount, $smsTemplate]);

                jsonResponse(200, 'success', 'Ayarlar kaydedildi.');
            }
        } catch (Exception $e) {
            http_response_code(422);
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
            exit();
        }
        break;

    case 'updatePackage':

        $packageName         = isset($_POST['packageName']) ? cleanInput($_POST['packageName']) : null;
        $classId             = isset($_POST['class_id']) ? (int)$_POST['class_id'] : null;
        $subscriptionPeriod  = $_POST['subscription_period'] ?? null;
        $bankTransferFee     = isset($_POST['bank_transfer_fee']) ? (float)$_POST['bank_transfer_fee'] : null;
        $creditCardFee       = isset($_POST['credit_card_fee']) ? (float)$_POST['credit_card_fee'] : null;
        $id                  = $_POST['id'] ?? null;

        try {
            if (!$packageName) {
                throw new Exception('Paket adı boş olamaz');
            }

            if (!$classId) {
                throw new Exception('Sınıf seçilmelidir.');
            }

            if (
                !isset($subscriptionPeriod) ||
                !is_numeric($subscriptionPeriod) ||
                intval($subscriptionPeriod) != $subscriptionPeriod ||
                $subscriptionPeriod < 1 ||
                $subscriptionPeriod > 12
            ) {
                throw new Exception('Abonelik periyodu 1 ile 12 arasında bir tam sayı olmalıdır.');
            }

            if (!is_float($bankTransferFee) || $bankTransferFee < 0) {
                throw new Exception('Havale/EFT ücreti geçerli bir sayı olmalıdır.');
            }

            if (!is_float($creditCardFee) || $creditCardFee < 0) {
                throw new Exception('Kredi kartı ücreti geçerli bir sayı olmalıdır.');
            }

            $stmt = $pdo->prepare("UPDATE packages_lnp 
                               SET name = ?, class_id = ?, bank_transfer_fee = ?, credit_card_fee = ?, subscription_period = ? 
                               WHERE id = ?");
            $stmt->execute([$packageName, $classId, $bankTransferFee, $creditCardFee, $subscriptionPeriod, $id]);

            if ($stmt->rowCount() > 0) {
                jsonResponse(200, 'success', 'Paket başarıyla güncellendi.');
            } else {
                jsonResponse(500, 'error', 'Paket güncellenemedi veya zaten bu veriler mevcut.');
            }
        } catch (Exception $e) {
            http_response_code(422);
            echo json_encode([
                'status'  => 'error',
                'message' => $e->getMessage()
            ]);
            exit();
        }
        break;

    case 'deleteMainGroup':
        // Gelen ID kontrolü
        $id = $_POST['id'] ?? null;

        if (!$id || !is_numeric($id)) {
            echo json_encode(['status' => 'error', 'message' => 'Geçersiz ID']);
            exit;
        }

        try {
            // Silme işlemi
            $stmt = $pdo->prepare("DELETE FROM classes_lnp WHERE id = ?");
            $stmt->execute([$id]);

            if ($stmt->rowCount()) {
                echo json_encode(['status' => 'success', 'message' => 'Grup başarıyla silindi.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Kayıt bulunamadı veya silinemedi.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
        }
        break;
    case 'groupShow':
        $id = $_GET['id'] ?? null;

        if (!$id || !is_numeric($id)) {
            echo json_encode(['status' => 'error', 'message' => 'Geçersiz ID' . $id]);
            exit;
        }

        try {
            // Silme işlemi
            $stmt = $pdo->prepare("Select name FROM classes_lnp WHERE id = ?");
            $stmt->execute([$id]);

            if ($stmt->rowCount()) {
                $data = $stmt->fetch(PDO::FETCH_ASSOC); // Tek bir kayıt için
                echo json_encode(['status' => 'success', 'data' => $data]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Kayıt bulunamadı veya silinemedi.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
        }
        break;

    case 'groupUpdate':
        $id = $_POST['id'] ?? null;
        $name = $_POST['name'] ?? null;
        if (!$id || !is_numeric($id)) {
            echo json_encode(['status' => 'error', 'message' => 'Geçersiz IwD' . $id]);
            exit;
        }

        try {
            // Silme işlemi
            $updateStmt = $pdo->prepare("UPDATE classes_lnp SET name = ? WHERE id = ?");
            $updateStmt->execute([$name, $id]);
            echo json_encode(['status' => 'success', 'message' => 'Grup adı başarıyla güncellendi.']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
        }
        break;
    case 'deleteImportantWeek':
        // Gelen ID kontrolü
        $id = $_POST['id'] ?? null;

        if (!$id || !is_numeric($id)) {
            echo json_encode(['status' => 'error', 'message' => 'Geçersiz ID']);
            exit;
        }

        try {
            // Silme işlemi
            $stmt = $pdo->prepare("DELETE FROM important_weeks_lnp WHERE id = ?");
            $stmt->execute([$id]);

            if ($stmt->rowCount()) {
                echo json_encode(['status' => 'success', 'message' => 'Kayıt başarıyla silindi.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Kayıt bulunamadı veya silinemedi.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
        }
        break;
    case 'importantWeekShow':
        $id = $_GET['id'] ?? null;

        if (!$id || !is_numeric($id)) {
            echo json_encode(['status' => 'error', 'message' => 'Geçersiz ID' . $id]);
            exit;
        }

        try {
            // Silme işlemi
            $stmt = $pdo->prepare("Select name,start_date,end_date FROM important_weeks_lnp WHERE id = ?");
            $stmt->execute([$id]);

            if ($stmt->rowCount()) {
                $data = $stmt->fetch(PDO::FETCH_ASSOC); // Tek bir kayıt için
                echo json_encode(['status' => 'success', 'data' => $data]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Kayıt bulunamadı veya silinemedi.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
        }
        break;
    case 'categoryTitleShow':
        $id = $_GET['id'] ?? null;

        if (!$id || !is_numeric($id)) {
            echo json_encode(['status' => 'error', 'message' => 'Geçersiz ID' . $id]);
            exit;
        }

        try {
            // Silme işlemi
            $stmt = $pdo->prepare("Select id,title,type FROM category_titles_lnp WHERE id = ?");
            $stmt->execute([$id]);

            if ($stmt->rowCount()) {
                $data = $stmt->fetch(PDO::FETCH_ASSOC); // Tek bir kayıt için
                echo json_encode(['status' => 'success', 'data' => $data]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Kayıt bulunamadı veya silinemedi.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
        }
        break;
    case 'titleUpdate':
        $id = $_POST['id'] ?? null;
        $type = $_POST['type'] ?? null;
        $title = $_POST['title'] ?? null;
        if (!$id || !is_numeric($id)) {
            echo json_encode(['status' => 'error', 'message' => 'Geçersiz IwD' . $id]);
            exit;
        }

        try {
            // Silme işlemi
            $updateStmt = $pdo->prepare("UPDATE category_titles_lnp SET type = ? , title=? WHERE id = ?");
            $updateStmt->execute([$type, $title, $id]);
            echo json_encode(['status' => 'success', 'message' => 'Kayıt başarıyla güncellendi.']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
        }
        break;


    case 'weekUpdate':
        $id = $_POST['id'] ?? null;
        $name = $_POST['name'] ?? null;
        $endDate = $_POST['endDate'] ?? null;
        $startDate = $_POST['startDate'] ?? null;


        $start = DateTime::createFromFormat('Y-m-d', $startDate);
        $end = DateTime::createFromFormat('Y-m-d', $endDate);

        // Tarihlerin geçerli olup olmadığını kontrol et
        if ($start && $end) {
            // endDate'in startDate'ten büyük olup olmadığını kontrol et
            if ($start > $end) {
                echo json_encode(['status' => 'error', 'message' => 'Hata: End Date, Start Dateten büyük olmalı!']);
                exit;
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Hata: Tarihler geçerli değil!']);
            exit;
            echo "Hata: Tarihler geçerli değil!";
        }



        if (!$id || !is_numeric($id)) {
            echo json_encode(['status' => 'error', 'message' => 'Geçersiz ID' . $id]);
            exit;
        }

        try {

            // Silme işlemi
            $updateStmt = $pdo->prepare("UPDATE important_weeks_lnp SET name = ?,start_date =?, end_date=? WHERE id = ?");
            $updateStmt->execute([$name, $startDate, $endDate, $id]);
            echo json_encode(['status' => 'success', 'message' => 'Kayıt başarıyla güncellendi.']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
        }
        break;
    case 'deleteMainSchoolContent': // aslında status toggle işlemi yapıyor
        $id = $_POST['id'] ?? null;

        if (!$id || !ctype_digit($id)) {
            echo json_encode(['status' => 'error', 'message' => 'Geçersiz ID']);
            exit;
        }

        try {
            // Transaction, tutarlılık için
            $pdo->beginTransaction();

            // Mevcut status'u kilitleyerek al
            $stmt = $pdo->prepare("SELECT status FROM main_school_content_lnp WHERE id = ? FOR UPDATE");
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                $pdo->rollBack();
                echo json_encode(['status' => 'error', 'message' => 'Kayıt bulunamadı.']);
                exit;
            }

            $currentStatus = (int)$row['status'];
            $newStatus = $currentStatus === 1 ? 0 : 1;

            // Güncelle
            $upd = $pdo->prepare("UPDATE main_school_content_lnp SET status = ? WHERE id = ?");
            $upd->execute([$newStatus, $id]);

            $pdo->commit();

            $actionText = $newStatus === 1 ? 'Pasif Yap' : 'Aktif Yap'; // bir sonraki yapılacak işlem
            $message = $newStatus === 1 ? 'İçerik aktif hale getirildi.' : 'İçerik pasif yapıldı.';

            echo json_encode([
                'status' => 'success',
                'message' => $message,
                'new_status' => $newStatus,
                'actionText' => $actionText,
            ]);
        } catch (PDOException $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            echo json_encode(['status' => 'error', 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
        }
        break;

    case 'deleteCategoryTitle':
        $id = $_POST['id'] ?? null;

        if (!$id || !is_numeric($id)) {
            echo json_encode(['status' => 'error', 'message' => 'Geçersiz ID']);
            exit;
        }

        try {
            // Silme işlemi
            $stmt = $pdo->prepare("DELETE FROM category_titles_lnp WHERE id = ?");
            $stmt->execute([$id]);

            if ($stmt->rowCount()) {
                echo json_encode(['status' => 'success', 'message' => 'Kayıt başarıyla silindi.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Kayıt bulunamadı veya silinemedi.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
        }
        break;
    case 'paymenttypegraphicreport':
    case 'paymenttypegraphicreport':
        function getGroupedByPeriodAndLimited($pdo, $dateFormat, $orderColumn, $limit)
        {
            $selectPeriod = "DATE_FORMAT(pp.created_at, ?) AS period";
            $groupByPeriod = "period"; // 'period' alias'ı üzerinden grupla
            $orderByClause = "";
            $params = [$dateFormat]; // Varsayılan parametre

            if ($orderColumn === 'period_date_daily') {
                $orderByClause = "ORDER BY STR_TO_DATE(DATE_FORMAT(pp.created_at, '%d-%m-%Y'), '%d-%m-%Y') DESC";
                $groupByPeriod = "DATE_FORMAT(pp.created_at, '%d-%m-%Y')"; // GROUP BY için direkt format
            } elseif ($orderColumn === 'period_date_weekly') {
                $orderByClause = "ORDER BY YEAR(pp.created_at) DESC, WEEK(pp.created_at, 1) DESC";
                $groupByPeriod = "YEAR(pp.created_at), WEEK(pp.created_at, 1)"; // GROUP BY için yıl ve hafta
                // Haftalık format için tek bir placeholder olduğu için $params değişmez.
            } elseif ($orderColumn === 'period_date_monthly') {
                // Sıralama için YYYY-MM formatını, görüntüleme için MM-YYYY formatını kullanıyoruz.
                // Bu durumda SELECT içinde iki ayrı DATE_FORMAT olacak.
                $selectPeriod = "DATE_FORMAT(pp.created_at, '%Y-%m') AS period_sort, DATE_FORMAT(pp.created_at, ?) AS period";
                $groupByPeriod = "period_sort"; // Gruplamayı period_sort üzerinden yapıyoruz
                $orderByClause = "ORDER BY period_sort DESC";
                // Parametre olarak sadece '?' için olan formatı gönderiyoruz
                $params = ['%m-%Y']; // Burada sadece 'period' için olan '?' dolduruluyor
            } elseif ($orderColumn === 'period_date_yearly') {
                $orderByClause = "ORDER BY YEAR(pp.created_at) DESC";
                $groupByPeriod = "YEAR(pp.created_at)"; // GROUP BY için yıl
                // Yıllık format için tek bir placeholder olduğu için $params değişmez.
            } else {
                // Varsayılan sıralama: created_at'a göre azalan
                $orderByClause = "ORDER BY pp.created_at DESC";
                $groupByPeriod = "period"; // Varsayılan olarak period alias'ı üzerinden grupla
            }

            $sql = "
            SELECT
                {$selectPeriod},
                pt.name AS payment_type,
                ROUND(SUM(pp.pay_amount), 2) AS total_payment,
                ROUND(SUM(pp.kdv_amount), 2) AS total_tax
            FROM package_payments_lnp pp
            LEFT JOIN payment_types_lnp pt ON pt.id = pp.payment_type
            GROUP BY {$groupByPeriod}, pt.name
            {$orderByClause}
            LIMIT {$limit}
        ";

            $stmt = $pdo->prepare($sql);
            // Parametrelerin doğru şekilde bind edildiğinden emin olalım
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        try {
            // Son 30 günlük kayıtları al (tarihe göre azalan, sonra ters çevrilir)
            $daily = getGroupedByPeriodAndLimited($pdo, '%d-%m-%Y', 'period_date_daily', 30);
            $daily = array_reverse($daily); // En eskiden en yeniye sırala

            // Son 30 haftalık kayıtları al (yıla ve haftaya göre azalan, sonra ters çevrilir)
            $weekly = getGroupedByPeriodAndLimited($pdo, '%x-HAFTA %v', 'period_date_weekly', 30);
            $weekly = array_reverse($weekly); // En eskiden en yeniye sırala

            // Son 30 aylık kayıtları al (YYYY-MM'ye göre azalan, sonra ters çevrilir)
            $monthly = getGroupedByPeriodAndLimited($pdo, '%m-%Y', 'period_date_monthly', 30);
            $monthly = array_reverse($monthly); // En eskiden en yeniye sırala

            // Son 30 yıllık kayıtları al (yıla göre azalan, sonra ters çevrilir)
            $yearly = getGroupedByPeriodAndLimited($pdo, '%Y', 'period_date_yearly', 30);
            $yearly = array_reverse($yearly); // En eskiden en yeniye sırala

            echo json_encode([
                'daily' => $daily,
                'weekly' => $weekly,
                'monthly' => $monthly,
                'yearly' => $yearly
            ]);
        } catch (Exception $e) {
            // Hata durumunda loglama veya daha detaylı hata mesajı döndürmek iyi bir uygulama olabilir.
            error_log("Payment Type Graphic Report Error: " . $e->getMessage());
            echo json_encode(['error' => 'Sunucu hatası oluştu. Lütfen yöneticinize başvurun.']);
        }
        break;
    case 'userpaymentreport':
        if ($_GET['action'] === 'kullaniciBasinaGelir') {

            $period = $_GET['period'] ?? 'daily'; // daily, weekly, monthly, yearly
            $limit = 30; // Son 30 kaydı alacağız

            // Kullanıcı rolü 2 veya 10002 olanların sayısını al
            $userCountStmt = $pdo->prepare("SELECT COUNT(*) FROM users_lnp WHERE role = 2 OR role = 10002");
            $userCountStmt->execute();
            $userCount = (int) $userCountStmt->fetchColumn();

            if ($userCount === 0) {
                echo json_encode(['data' => []]);
                exit;
            }

            // Tarih formatı, GROUP BY ifadesi ve ORDER BY ifadesi
            $dateFormat = '';
            $groupBy = '';
            $orderBy = '';
            $selectPeriodForSort = ''; // Sıralama için kullanılacak gizli sütun

            switch ($period) {
                case 'weekly':
                    $dateFormat = '%x-HAFTA %v'; // Görüntüleme formatı: Yıl-HAFTA HaftaNo
                    // Sıralama için yıl ve hafta numarası kullan
                    $selectPeriodForSort = "YEAR(pp.created_at) AS period_sort_year, WEEK(pp.created_at, 1) AS period_sort_week,";
                    $groupBy = "period_sort_year, period_sort_week";
                    $orderBy = "ORDER BY period_sort_year DESC, period_sort_week DESC";
                    break;
                case 'monthly':
                    $dateFormat = '%m-%Y'; // Görüntüleme formatı: Ay-Yıl
                    // Sıralama için YYYY-MM formatını kullan
                    $selectPeriodForSort = "DATE_FORMAT(pp.created_at, '%Y-%m') AS period_sort,";
                    $groupBy = "period_sort";
                    $orderBy = "ORDER BY period_sort DESC";
                    break;
                case 'yearly':
                    $dateFormat = '%Y'; // Görüntüleme formatı: Yıl
                    // Sıralama için yıl kullan
                    $selectPeriodForSort = "YEAR(pp.created_at) AS period_sort_year,";
                    $groupBy = "period_sort_year";
                    $orderBy = "ORDER BY period_sort_year DESC";
                    break;
                case 'daily':
                default:
                    $dateFormat = '%d-%m-%Y'; // Görüntüleme formatı: Gün-Ay-Yıl
                    // Sıralama için gerçek tarih değerini kullan
                    $selectPeriodForSort = "DATE(pp.created_at) AS period_sort_date,";
                    $groupBy = "period_sort_date";
                    $orderBy = "ORDER BY period_sort_date DESC";
                    break;
            }

            $sql = "
            SELECT
                {$selectPeriodForSort}
                DATE_FORMAT(pp.created_at, ?) AS period,
                ROUND(SUM(pp.pay_amount) / ?, 2) AS avg_payment,
                ROUND(SUM(pp.kdv_amount) / ?, 2) AS avg_tax
            FROM package_payments_lnp pp
            INNER JOIN users_lnp u ON u.id = pp.user_id AND (u.role = 2 OR u.role = 10002)
            GROUP BY $groupBy
            $orderBy
            LIMIT $limit
        ";

            $stmt = $pdo->prepare($sql);
            // Parametreleri doğru sırada gönder
            $stmt->execute([$dateFormat, $userCount, $userCount]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // JavaScript tarafında en eskiden en yeniye doğru sıralama beklediğimiz için veriyi ters çeviriyoruz
            $result = array_reverse($result);

            echo json_encode(['data' => $result]);
            exit;
        }
        break;

    case 'graphicreport':
        try {
            // Günlük veriler (son 20 gün)
            $daily = $pdo->query("
            SELECT DATE_FORMAT(created_at, '%d-%m-%Y') AS day,
                   SUM(pay_amount) AS total_payment,
                   ROUND(SUM(kdv_amount), 0) AS total_tax
            FROM package_payments_lnp
            GROUP BY day
            ORDER BY STR_TO_DATE(day, '%d-%m-%Y') DESC -- Tarih olarak azalan sıralama
            LIMIT 30
        ")->fetchAll(PDO::FETCH_ASSOC);

            // Haftalık veriler (son 20 hafta)
            $weekly = $pdo->query("
            SELECT CONCAT(YEAR(created_at), ' HAFTA ', LPAD(WEEK(created_at, 1), 2, '0')) AS week,
                   SUM(pay_amount) AS total_payment,
                   ROUND(SUM(kdv_amount), 0) AS total_tax
            FROM package_payments_lnp
            GROUP BY week
            ORDER BY YEAR(created_at) DESC, WEEK(created_at, 1) DESC -- Yıla ve haftaya göre azalan
            LIMIT 30
        ")->fetchAll(PDO::FETCH_ASSOC);

            // Aylık veriler (son 20 ay)
            $monthly = $pdo->query("
            SELECT DATE_FORMAT(created_at, '%Y-%m') AS period_sort, -- Sıralama için YYYY-MM formatı
                   DATE_FORMAT(created_at, '%m-%Y') AS period,     -- Görüntüleme için MM-YYYY formatı
                   SUM(pay_amount) AS total_payment,
                   ROUND(SUM(kdv_amount), 0) AS total_tax
            FROM package_payments_lnp
            GROUP BY period_sort
            ORDER BY period_sort DESC -- YYYY-MM'ye göre azalan sıralama
            LIMIT 30
        ")->fetchAll(PDO::FETCH_ASSOC);

            // Yıllık veriler (son 20 yıl)
            $yearly = $pdo->query("
            SELECT YEAR(created_at) AS year,
                   SUM(pay_amount) AS total_payment,
                   ROUND(SUM(kdv_amount), 0) AS total_tax
            FROM package_payments_lnp
            GROUP BY year
            ORDER BY year DESC -- Yıla göre azalan sıralama
            LIMIT 30
        ")->fetchAll(PDO::FETCH_ASSOC);

            // JavaScript tarafına gönderirken verileri tersine çeviriyoruz ki en eski en başta olsun
            // Çünkü SQL'de DESC ile en yeniyi çektik, JS'de ise artan sıralama bekliyoruz.
            echo json_encode([
                'daily' => array_reverse($daily),
                'weekly' => array_reverse($weekly),
                'monthly' => array_reverse($monthly),
                'yearly' => array_reverse($yearly)
            ]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;
    case 'payment-excel':

        try {
            if (!$_POST['start_date'] || empty($_POST['start_date'])) {
                throw new Exception('Başlangıç tarihi parametresi eksik.');
            }
            if (!$_POST['stop_date'] || empty($_POST['stop_date'])) {
                throw new Exception('Bitiş tarihi parametresi eksik.');
            }
            $startDate = $_POST['start_date'] . ' 00:00:00';
            $stopDate = $_POST['stop_date'] . ' 23:59:59';
            $sql = "SELECT 
            ROW_NUMBER() OVER (ORDER BY u.subscribed_end DESC) AS row_number,
            u.id,
            pp.pay_amount,
            CONCAT(pkg.name, ' ', cls.name, ' paketi için ödeme') AS description,
            u.identity_id AS student_identity_id,
            parent.identity_id AS parent_identity_id,
            u.parent_id,
            CONCAT(u.address, ' ', u.district, ' / ', u.city) AS address,
            CONCAT(u.name, ' ', u.surname) AS student_fullname,
            u.subscribed_end,
            u.telephone AS parent_phone,
            CONCAT(parent.name, ' ', parent.surname) AS parent_fullname
        FROM users_lnp u
        LEFT JOIN users_lnp parent ON u.parent_id = parent.id
        LEFT JOIN package_payments_lnp pp ON pp.user_id = u.id
        LEFT JOIN packages_lnp pkg ON pkg.id = pp.pack_id
        LEFT JOIN classes_lnp cls ON cls.id = pkg.class_id
        WHERE (u.role = 2  or u.role=10002)
          AND pp.created_at BETWEEN :start_date AND :stop_date
        ORDER BY u.subscribed_end DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'start_date' => $startDate,
                'stop_date' => $stopDate
            ]);

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);


            echo json_encode([
                'status' => 'success',
                'data' => $results
            ]);
        } catch (Exception $e) {
            http_response_code(422); // Veya uygun bir HTTP kodu
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
            exit();
        }
        break;
    case 'filter-expire-list':
        $startDate = $_POST['start_date'] ?? null;
        $stopDate = $_POST['stop_date'] ?? null;
        try {
            if (!$startDate || empty($startDate)) {
                throw new Exception('Başlangıç tarihi parametresi eksik.');
            }
            if (!$stopDate || empty($stopDate)) {
                throw new Exception('Bitiş tarihi parametresi eksik.');
            }

            // Sorgu hazırlanıyor
            $sql = "SELECT 
                    u.id, 
                    u.parent_id, 
                    CONCAT(u.name, ' ', u.surname) AS fullname, 
                     DATE_FORMAT(u.subscribed_end, '%d-%m-%Y') AS subscribed_end, 
                    u.telephone AS parent_phone, 
                    CONCAT(p.name, ' ', p.surname) AS parent_fullname 
                FROM users_lnp u 
                LEFT JOIN users_lnp p ON u.parent_id = p.id 
                WHERE (u.role = 2 OR u.role = 10002)
                  AND u.subscribed_end BETWEEN :start_date AND :stop_date
                ORDER BY u.subscribed_end DESC";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'start_date' => $startDate,
                'stop_date' => $stopDate
            ]);

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);


            echo json_encode([
                'status' => 'success',
                'data' => $results
            ]);
        } catch (Exception $e) {
            http_response_code(422); // Veya uygun bir HTTP kodu
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
            exit();
        }

        break;



    case 'filter-payment-report-byuser':
        $studentId = $_POST['student'];

        if (!empty($studentId)) {

            // Belirli bir öğrenci için filtrele
            $stmt = $pdo->prepare("SELECT 
            u.id as id,
            CONCAT(u.name, ' ', u.surname) as fullname,
            pp.order_no as order_no,
            pp.created_at as payment_date,
            pt.name as payment_type,
            pp.pay_amount as payment_total,
            pp.kdv_amount as tax,
            ps.name as payment_status
        FROM `package_payments_lnp` pp
        LEFT JOIN users_lnp u ON pp.user_id = u.id
        LEFT JOIN payment_types_lnp pt ON pp.payment_type = pt.id
        LEFT JOIN payment_status_lnp ps ON ps.id = pp.payment_status
        WHERE u.id = :user_id ORDER BY pp.created_at desc");

            $stmt->execute(['user_id' => $studentId]);
        } else {
            // Tüm öğrenciler için filtrele
            $stmt = $pdo->prepare("SELECT 
            u.id as id,
            CONCAT(u.name, ' ', u.surname) as fullname,
            pp.order_no as order_no,
            DATE_FORMAT(pp.created_at, '%d-%m-%Y %H:%i:%s') as payment_date,
            pt.name as payment_type,
            pp.pay_amount as payment_total,
            pp.kdv_amount as tax,
            ps.name as payment_status
        FROM `package_payments_lnp` pp
        LEFT JOIN users_lnp u ON pp.user_id = u.id
        LEFT JOIN payment_types_lnp pt ON pp.payment_type = pt.id
        LEFT JOIN payment_status_lnp ps ON ps.id = pp.payment_status ORDER BY pp.id desc");

            $stmt->execute();
        }

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'status' => 'success',
            'data' => $results
        ]);
        break;



    case 'filter-main-school-content':
        $lesson_id = isset($_POST['lesson_id']) ? $_POST['lesson_id'] : null;
        $unit_id = isset($_POST['unit_id']) ? $_POST['unit_id'] : null;
        $topic_id = isset($_POST['topic_id']) ? $_POST['topic_id'] : null;

        $month = isset($_POST['month']) ? $_POST['month'] : null;
        $week = isset($_POST['week']) ? $_POST['week'] : null;
        $activity_title = isset($_POST['activity_title']) ? $_POST['activity_title'] : null;
        $content_title = isset($_POST['content_title']) ? $_POST['content_title'] : null;
        $concept_title = isset($_POST['concept_title']) ? $_POST['concept_title'] : null;
        $main_school_class_id = isset($_POST['main_school_class_id']) ? $_POST['main_school_class_id'] : null;
        if (isset($_SESSION['class_id']) && $_SESSION['class_id'] != null) {
            $main_school_class_id = $_SESSION['class_id'];
        }
        $whereClauses = [];
        $params = [];

        // Dinamik filtreleri ekle
        if ($lesson_id !== null && $lesson_id !== '') {

            $whereClauses[] = 'lesson_id = :lesson_id';
            $params[':lesson_id'] = $lesson_id;
        }
        if ($unit_id !== null && $unit_id !== '') {

            $whereClauses[] = 'unit_id = :unit_id';
            $params[':unit_id'] = $unit_id;
        }
        if ($month !== null && $month !== '') {
            $whereClauses[] = 'month = :month';
            $params[':month'] = $month;
        }

        if ($month !== null && $month !== '') {
            $whereClauses[] = 'month = :month';
            $params[':month'] = $month;
        }

        if ($week !== null && $week !== '') {
            $whereClauses[] = 'week_id = :week';
            $params[':week'] = $week;
        }

        if ($activity_title !== null && $activity_title !== '') {
            $whereClauses[] = 'activity_title_id = :activity_title';
            $params[':activity_title'] = $activity_title;
        }

        if ($content_title !== null && $content_title !== '') {
            $whereClauses[] = 'content_title_id = :content_title';
            $params[':content_title'] = $content_title;
        }

        if ($concept_title !== null && $concept_title !== '') {
            $whereClauses[] = 'concept_title_id = :concept_title';
            $params[':concept_title'] = $concept_title;
        }
        if ($main_school_class_id !== null && $main_school_class_id !== '') {
            $whereClauses[] = 'main_school_class_id = :main_school_class_id';
            $params[':main_school_class_id'] = $main_school_class_id;
        }
        $whereClauses[] = 'status = :isactive'; // Aktif olanları filtrele
        $params[':isactive'] = 1;

        // WHERE cümlesi oluştur
        $whereSQL = '';
        if (count($whereClauses) > 0) {
            $whereSQL = 'WHERE ' . implode(' AND ', $whereClauses);
        }

        // Sorguyu hazırla ve çalıştır
        $sql = "
    SELECT 
        main_school_content_lnp.*, 
        classes_lnp.name as class_name 
    FROM 
        main_school_content_lnp
    INNER JOIN 
        classes_lnp 
        ON main_school_content_lnp.main_school_class_id = classes_lnp.id
    $whereSQL
    ORDER BY main_school_content_lnp.id DESC
";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Sonuçları yazdırmak istersen:
        echo json_encode([
            'status' => 'success',
            'data' => $data
        ]);
        break;


    case 'toppackages':
        try {
            $stmt = $pdo->query("
            SELECT 
                c.id AS class_id,
                c.name AS class_name,
                p.id AS package_id,
                p.name AS package_name,
                COUNT(DISTINCT pp.user_id) AS buyer_count
               
            FROM package_payments_lnp pp
            INNER JOIN packages_lnp p ON p.id = pp.pack_id
            INNER JOIN classes_lnp c ON c.id = p.class_id
            GROUP BY c.id, c.name, p.id, p.name
            ORDER BY buyer_count DESC
            LIMIT 10
        ");

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode([
                'status' => 'success',
                'data' => $result
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
        exit;
    case 'sms-settings':
        $username = $_POST['username'];
        $password = $_POST['password'];
        $msgheader = $_POST['msgheader'];
        try {
            if (!$username) {
                throw new Exception('username adı boş olamaz');
            }
            if (!$password) {
                throw new Exception('password boş olamaz');
            }
            if (!$msgheader) {
                throw new Exception('msgheader boş olamaz');
            }



            $check = $pdo->query("SELECT id FROM sms_settings_lnp LIMIT 1");

            if ($check->rowCount() > 0) {
                // Varsa güncelle (ilk kaydı güncelle)
                $stmt = $pdo->prepare("UPDATE sms_settings_lnp SET username = ?, password = ?, msgheader=? LIMIT 1");
                $stmt->execute([$username, $password, $msgheader]);
            } else {
                // Yoksa yeni kayıt ekle
                $stmt = $pdo->prepare("INSERT INTO sms_settings_lnp (username, password,msgheader) VALUES (?, ?,?)");
                $stmt->execute([$username, $password, $msgheader]);
            }

            if ($stmt->rowCount() > 0) {
                jsonResponse(200, 'success', 'Ayarlar başarıyla kaydedildi veya güncellendi.');
            } else {
                jsonResponse(500, 'error', 'Aynı bilgileri gönderdiniz.');
            }
        } catch (Exception $e) {
            http_response_code(422); // Veya uygun bir HTTP kodu
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
            exit();
        }

        break;
    case 'expired-user-count-report':
        try {
            $today = date('Y-m-d');

            // Günlük - Son 30 kayıt
            // Aboneliği bitiş tarihine göre tersten sıralayıp son 30 günü alırız.
            $dailyStmt = $pdo->prepare("
            SELECT DATE_FORMAT(subscribed_end, '%d-%m-%Y') AS day, COUNT(*) AS user_count
            FROM users_lnp
            WHERE (role = 2 OR role = 10002) AND subscribed_end < :today
            GROUP BY day
            ORDER BY subscribed_end DESC -- En son bitiş tarihinden eskiye doğru sırala
            LIMIT 30 -- Sadece son 30 kaydı getir
        ");
            $dailyStmt->execute(['today' => $today]);
            $daily = $dailyStmt->fetchAll(PDO::FETCH_ASSOC);

            // Haftalık - Son 30 kayıt
            $weeklyStmt = $pdo->prepare("
            SELECT CONCAT(YEAR(subscribed_end), ' HAFTA ', LPAD(WEEK(subscribed_end, 1), 2, '0')) AS week, COUNT(*) AS user_count
            FROM users_lnp
            WHERE (role = 2 OR role = 10002) AND subscribed_end < :today
            GROUP BY week
            ORDER BY YEAR(subscribed_end) DESC, WEEK(subscribed_end, 1) DESC -- En son haftadan eskiye doğru sırala
            LIMIT 30 -- Sadece son 30 kaydı getir
        ");
            $weeklyStmt->execute(['today' => $today]);
            $weekly = $weeklyStmt->fetchAll(PDO::FETCH_ASSOC);

            // Aylık - Son 30 kayıt
            $monthlyStmt = $pdo->prepare("
            SELECT DATE_FORMAT(subscribed_end, '%m-%Y') AS period, COUNT(*) AS user_count
            FROM users_lnp
            WHERE (role = 2 OR role = 10002) AND subscribed_end < :today
            GROUP BY period
            ORDER BY subscribed_end DESC -- En son aydan eskiye doğru sırala
            LIMIT 30 -- Sadece son 30 kaydı getir
        ");
            $monthlyStmt->execute(['today' => $today]);
            $monthly = $monthlyStmt->fetchAll(PDO::FETCH_ASSOC);

            // Yıllık - Son 30 kayıt
            $yearlyStmt = $pdo->prepare("
            SELECT YEAR(subscribed_end) AS year, COUNT(*) AS user_count
            FROM users_lnp
            WHERE (role = 2 OR role = 10002) AND subscribed_end < :today
            GROUP BY year
            ORDER BY year DESC -- En son yıldan eskiye doğru sırala
            LIMIT 30 -- Sadece son 30 kaydı getir
        ");
            $yearlyStmt->execute(['today' => $today]);
            $yearly = $yearlyStmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode([
                'status' => 'success',
                'daily' => $daily,
                'weekly' => $weekly,
                'monthly' => $monthly,
                'yearly' => $yearly,
            ]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;
    case 'active-user-count-report':
        try {
            $today = date('Y-m-d');

            // Günlük - Son 30 kayıt
            $daily = $pdo->query("
            SELECT DATE_FORMAT(created_at, '%d-%m-%Y') AS day, COUNT(*) AS user_count
            FROM users_lnp
            WHERE (role=2 or role=10002) AND subscribed_end > '$today'
            GROUP BY day
            ORDER BY created_at DESC -- En son tarihten eskiye doğru sırala
            LIMIT 30 -- Sadece son 30 kaydı getir
        ")->fetchAll(PDO::FETCH_ASSOC);

            // Haftalık - Son 30 kayıt
            $weekly = $pdo->query("
            SELECT CONCAT(YEAR(created_at), ' HAFTA ', LPAD(WEEK(created_at, 1), 2, '0')) AS week,
                   COUNT(*) AS user_count
            FROM users_lnp
            WHERE (role = 2 OR role = 10002) AND subscribed_end > '$today'
            GROUP BY week
            ORDER BY YEAR(created_at) DESC, WEEK(created_at, 1) DESC -- En son haftadan eskiye doğru sırala
            LIMIT 30 -- Sadece son 30 kaydı getir
        ")->fetchAll(PDO::FETCH_ASSOC);

            // Aylık - Son 30 kayıt
            $monthly = $pdo->query("
            SELECT DATE_FORMAT(created_at, '%m-%Y') AS period, COUNT(*) AS user_count
            FROM users_lnp
            WHERE (role=2 or role=10002) AND subscribed_end > '$today'
            GROUP BY period
            ORDER BY created_at DESC -- En son aydan eskiye doğru sırala
            LIMIT 30 -- Sadece son 30 kaydı getir
        ")->fetchAll(PDO::FETCH_ASSOC);

            // Yıllık - Son 30 kayıt
            $yearly = $pdo->query("
            SELECT YEAR(created_at) AS year, COUNT(*) AS user_count
            FROM users_lnp
            WHERE (role=2 or role=10002) AND subscribed_end > '$today'
            GROUP BY year
            ORDER BY year DESC -- En son yıldan eskiye doğru sırala
            LIMIT 30 -- Sadece son 30 kaydı getir
        ")->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode([
                'status' => 'success',
                'daily' => $daily,
                'weekly' => $weekly,
                'monthly' => $monthly,
                'yearly' => $yearly,
            ]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;
    case 'getClasses':
        try {
            // Bu sorgu için ek bir filtreye gerek yok, tüm ana sınıfları getiriyor
            $stmt = $pdo->prepare("SELECT id, name FROM `classes_lnp` WHERE class_type = 0 ORDER BY name ASC");
            $stmt->execute();

            $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($classes) {
                echo json_encode(['status' => 'success', 'data' => $classes]);
            } else {
                // Hata yerine bilgi mesajı dönüyoruz çünkü veri olmaması bir hata değil
                echo json_encode(['status' => 'success', 'data' => [], 'message' => 'Sınıf bulunamadı.']);
            }
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error
            echo json_encode([
                'status' => 'error',
                'message' => 'Sınıflar yüklenirken hata oluştu: ' . $e->getMessage()
            ]);
            exit();
        }
        break;

    case 'getLessonList':
        $classId = $_POST['class_id'] ?? $_GET['class_id'] ?? null;


        if (is_null($classId)) {
            http_response_code(400); // Bad Request
            echo json_encode(['status' => 'error', 'message' => 'Dersleri listelemek için sınıf ID\'si gereklidir.']);
            exit();
        }

        try {
            // class_id'ye göre dersleri getiriyoruz
            $likeClassId = "%;$classId;%";

            $stmt = $pdo->prepare("SELECT id, name FROM `lessons_lnp` WHERE CONCAT(';', class_id, ';') LIKE :class_id ORDER BY name ASC");
            $stmt->bindParam(':class_id', $likeClassId, PDO::PARAM_STR);
            $stmt->execute();
            $lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($lessons) {
                echo json_encode(['status' => 'success', 'data' => $lessons]);
            } else {
                echo json_encode(['status' => 'success', 'data' => [], 'message' => 'Bu sınıfa ait ders bulunamadı.']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Dersler yüklenirken hata oluştu: ' . $e->getMessage()]);
            exit();
        }
        break;
    case 'getMainSchoolLessonList':
        $classId = $_POST['class_id'] ?? $_GET['class_id'] ?? null;


        if (is_null($classId)) {
            http_response_code(400); // Bad Request
            echo json_encode(['status' => 'error', 'message' => 'Dersleri listelemek için sınıf ID\'si gereklidir.']);
            exit();
        }

        try {
            // class_id'ye göre dersleri getiriyoruz
            $stmt = $pdo->prepare("
        SELECT * 
        FROM `mainschool_lesson_class_id_lnp` mlc
        INNER JOIN main_school_lessons_lnp msl ON msl.id = mlc.lesson_id
        WHERE mlc.class_id = :class_id
        ORDER BY msl.name ASC
    ");
            $stmt->bindParam(':class_id', $classId, PDO::PARAM_INT);
            $stmt->execute();
            $lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($lessons) {
                echo json_encode(['status' => 'success', 'data' => $lessons]);
            } else {
                echo json_encode(['status' => 'success', 'data' => [], 'message' => 'Bu sınıfa ait ders bulunamadı.']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Dersler yüklenirken hata oluştu: ' . $e->getMessage()]);
            exit();
        }

        break;

    case 'getUnits':
        $classId = $_GET['class_id'] ?? null; // class_id'yi de alıyoruz
        $lessonId = $_GET['lesson_id'] ?? null;

        if (is_null($classId) || is_null($lessonId)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Üniteleri listelemek için sınıf ve ders ID\'leri gereklidir.']);
            exit();
        }

        try {
            // class_id ve lesson_id'ye göre üniteleri getiriyoruz
            $stmt = $pdo->prepare("SELECT id, name FROM `units_lnp` WHERE class_id = :class_id AND lesson_id = :lesson_id ORDER BY name ASC");
            $stmt->bindParam(':class_id', $classId, PDO::PARAM_INT);
            $stmt->bindParam(':lesson_id', $lessonId, PDO::PARAM_INT);
            $stmt->execute();
            $units = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($units) {
                echo json_encode(['status' => 'success', 'data' => $units]);
            } else {
                echo json_encode(['status' => 'success', 'data' => [], 'message' => 'Bu derse ait ünite bulunamadı.']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Üniteler yüklenirken hata oluştu: ' . $e->getMessage()]);
            exit();
        }
        break;

    case 'getTopics':
        $classId = $_GET['class_id'] ?? null;
        $lessonId = $_GET['lesson_id'] ?? null;
        $unitId = $_GET['unit_id'] ?? null;

        if (is_null($classId) || is_null($lessonId) || is_null($unitId)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Konuları listelemek için sınıf, ders ve ünite ID\'leri gereklidir.']);
            exit();
        }

        try {
            // class_id, lesson_id ve unit_id'ye göre konuları getiriyoruz
            $stmt = $pdo->prepare("SELECT id, name FROM `topics_lnp` WHERE class_id = :class_id AND lesson_id = :lesson_id AND unit_id = :unit_id ORDER BY name ASC");
            $stmt->bindParam(':class_id', $classId, PDO::PARAM_INT);
            $stmt->bindParam(':lesson_id', $lessonId, PDO::PARAM_INT);
            $stmt->bindParam(':unit_id', $unitId, PDO::PARAM_INT);
            $stmt->execute();
            $topics = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($topics) {
                echo json_encode(['status' => 'success', 'data' => $topics]);
            } else {
                echo json_encode(['status' => 'success', 'data' => [], 'message' => 'Bu üniteye ait konu bulunamadı.']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Konular yüklenirken hata oluştu: ' . $e->getMessage()]);
            exit();
        }
        break;

    case 'getSubtopics':
        $classId = $_GET['class_id'] ?? null;
        $lessonId = $_GET['lesson_id'] ?? null;
        $unitId = $_GET['unit_id'] ?? null;
        $topicId = $_GET['topic_id'] ?? null;

        if (is_null($classId) || is_null($lessonId) || is_null($unitId) || is_null($topicId)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Alt konuları listelemek için sınıf, ders, ünite ve konu ID\'leri gereklidir.']);
            exit();
        }

        try {
            // class_id, lesson_id, unit_id ve topic_id'ye göre alt konuları getiriyoruz
            $stmt = $pdo->prepare("SELECT id, name FROM `subtopics_lnp` WHERE class_id = :class_id AND lesson_id = :lesson_id AND unit_id = :unit_id AND topic_id = :topic_id ORDER BY name ASC");
            $stmt->bindParam(':class_id', $classId, PDO::PARAM_INT);
            $stmt->bindParam(':lesson_id', $lessonId, PDO::PARAM_INT);
            $stmt->bindParam(':unit_id', $unitId, PDO::PARAM_INT);
            $stmt->bindParam(':topic_id', $topicId, PDO::PARAM_INT);
            $stmt->execute();
            $subtopics = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($subtopics) {
                echo json_encode(['status' => 'success', 'data' => $subtopics]);
            } else {
                echo json_encode(['status' => 'success', 'data' => [], 'message' => 'Bu konuya ait alt konu bulunamadı.']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Alt konular yüklenirken hata oluştu: ' . $e->getMessage()]);
            exit();
        }
        break;
    case 'getLessonList1':
        $classId = $_POST['class_id'] ?? null;
        if (!$classId || !is_numeric($classId)) {

            echo json_encode(['status' => 'error', 'message' => 'Geçersiz sınıf ID']);
            exit;
        }
        try {
            $stmt = $pdo->prepare("
            SELECT id, name 
            FROM lessons_lnp 
            WHERE class_id = :exact
               OR class_id LIKE :start
               OR class_id LIKE :middle
               OR class_id LIKE :end
            ORDER BY name ASC
        ");

            $stmt->execute([
                ':exact'  => (string)$classId,
                ':start'  => $classId . ';%',
                ':middle' => '%;' . $classId . ';%',
                ':end'    => '%;' . $classId,
            ]);

            $data['lessons'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt2 = $pdo->prepare("
            SELECT option_count 
            FROM test_class_option_count 
            WHERE class_id = :class_id LIMIT 1
        ");
            $stmt2->execute([
                ':class_id'  => $classId
            ]);
            $optionCount = $stmt2->fetch(PDO::FETCH_ASSOC);
            $data['optionCount'] = $optionCount['option_count'] ?? 3;

            if ($data) {
                echo json_encode(['status' => 'success', 'data' => $data]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Ders bulunamadı.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
        }
        break;
    case 'getUnitList':
        $lessonId = $_POST['lesson_id'] ?? null;
        $classId = $_POST['class_id'] ?? null;
        if (!$lessonId || !is_numeric($lessonId)) {
            echo json_encode(['status' => 'error', 'message' => 'Geçersiz ders ID']);
            exit;
        }
        try {
            $stmt = $pdo->prepare("SELECT * FROM `units_lnp` where lesson_id=? and class_id=? ORDER BY name ASC");
            $stmt->execute([$lessonId, $classId]);

            $lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($lessons) {
                echo json_encode(['status' => 'success', 'data' => $lessons]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Ders bulunamadı.']);
            }
        } catch (Exception $e) {
            http_response_code(422); // Veya uygun bir HTTP kodu
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
            exit();
        }
        break;
    case 'getTopicList':
        $lessonId = $_POST['lesson_id'] ?? null;
        $classId = $_POST['class_id'] ?? null;
        $unitId = $_POST['unit_id'] ?? null;

        try {
            $stmt = $pdo->prepare("SELECT * FROM `topics_lnp` where lesson_id=? and class_id=? and unit_id=? ORDER BY name ASC");
            $stmt->execute([$lessonId, $classId, $unitId]);

            $lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($lessons) {
                echo json_encode(['status' => 'success', 'data' => $lessons]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Ders bulunamadı.']);
            }
        } catch (Exception $e) {
            http_response_code(422); // Veya uygun bir HTTP kodu
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
            exit();
        }
        break;
    case 'getSubtopicList':
        $lessonId = $_POST['lesson_id'] ?? null;
        $classId = $_POST['class_id'] ?? null;
        $unitId = $_POST['unit_id'] ?? null;
        $topicId = $_POST['topic_id'] ?? null;

        try {
            $stmt = $pdo->prepare("SELECT * FROM `subtopics_lnp` where lesson_id=? and class_id=? and unit_id=? and topic_id=? ORDER BY name ASC");
            $stmt->execute([$lessonId, $classId, $unitId, $topicId]);

            $lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($lessons) {
                echo json_encode(['status' => 'success', 'data' => $lessons]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Ders bulunamadı.']);
            }
        } catch (Exception $e) {
            http_response_code(422); // Veya uygun bir HTTP kodu
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
            exit();
        }
        break;

    case 'testAdd':
        $classId = $_POST['class_id'] ?? null;
        $lessonId = $_POST['lesson_id'] ?? null;
        $unitId = $_POST['unit_id'] ?? null;
        $topicId = $_POST['topic_id'] ?? null;
        $subtopicId = $_POST['subtopic_id'] ?? null;
        $status = $_POST['status'] ?? null;


        $title = $_POST['title'] ?? null;
        $startDate = $_POST['start_date'] ?? null;
        $endDate = $_POST['end_date'] ?? null;

        if (empty($classId)) {
            throw new Exception('Sınıf zorunludur.');
        }
        if (empty($title)) {
            throw new Exception('Başlık zorunludur.');
        }
        if (empty($startDate)) {
            throw new Exception('Başlangıç tarihi zorunludur.');
        }
        if (empty($endDate)) {
            throw new Exception('Bitiş tarihi zorunludur.');
        }

        $pdo->beginTransaction();
        try {
            // --- GENEL DOSYA VALİDASYONLARI (En Başta) ---
            $maxTotalFileSize = 2 * 1024 * 1024; // 3 MB
            $allowedImageExtensions = ['jpg', 'jpeg', 'png'];
            $totalUploadedSize = 0;

            // Geçici olarak yüklenen tüm dosyaları kontrol et
            $allFiles = [];

            // cover_img kontrolü
            if (isset($_FILES['cover_img']) && $_FILES['cover_img']['error'] !== UPLOAD_ERR_NO_FILE) {
                $allFiles[] = $_FILES['cover_img'];
            }

            // Soru görselleri kontrolü
            if (isset($_FILES['questions']['name'])) {
                foreach ($_FILES['questions']['name'] as $qIndex => $qData) {
                    if (isset($qData['images']) && is_array($qData['images'])) {
                        foreach ($qData['images'] as $i => $imgName) {
                            if ($_FILES['questions']['error'][$qIndex]['images'][$i] !== UPLOAD_ERR_NO_FILE) {
                                $allFiles[] = [
                                    'name' => $imgName,
                                    'type' => $_FILES['questions']['type'][$qIndex]['images'][$i],
                                    'tmp_name' => $_FILES['questions']['tmp_name'][$qIndex]['images'][$i],
                                    'error' => $_FILES['questions']['error'][$qIndex]['images'][$i],
                                    'size' => $_FILES['questions']['size'][$qIndex]['images'][$i],
                                ];
                            }
                        }
                    }

                    // Seçenek görselleri kontrolü
                    if (isset($qData['options'])) {
                        foreach ($qData['options'] as $optionKey => $optionData) {
                            if (isset($optionData['images']) && is_array($optionData['images'])) {
                                foreach ($optionData['images'] as $imgIndex => $optImgName) {
                                    if ($_FILES['questions']['error'][$qIndex]['options'][$optionKey]['images'][$imgIndex] !== UPLOAD_ERR_NO_FILE) {
                                        $allFiles[] = [
                                            'name' => $optImgName,
                                            'type' => $_FILES['questions']['type'][$qIndex]['options'][$optionKey]['images'][$imgIndex],
                                            'tmp_name' => $_FILES['questions']['tmp_name'][$qIndex]['options'][$optionKey]['images'][$imgIndex],
                                            'error' => $_FILES['questions']['error'][$qIndex]['options'][$optionKey]['images'][$imgIndex],
                                            'size' => $_FILES['questions']['size'][$qIndex]['options'][$optionKey]['images'][$imgIndex],
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
            }

            // Tüm dosyaların genel boyut ve tür validasyonu
            foreach ($allFiles as $file) {
                if ($file['error'] !== UPLOAD_ERR_OK) {
                    // UPLOAD_ERR_INI_SIZE veya UPLOAD_ERR_FORM_SIZE hatası zaten php.ini veya form limiti aşıldığını gösterir
                    if ($file['error'] === UPLOAD_ERR_INI_SIZE || $file['error'] === UPLOAD_ERR_FORM_SIZE) {
                        throw new Exception('Yüklenen dosyalardan biri PHP limitlerini aşıyor veya çok büyük.');
                    }
                    // Diğer yükleme hataları
                    throw new Exception('Dosya yükleme hatası oluştu: Kod ' . $file['error']);
                }

                $totalUploadedSize += $file['size'];
                $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

                // Sadece resim uzantılarını kontrol et (jpg, jpeg, png)
                if (!in_array($fileExtension, $allowedImageExtensions)) {
                    throw new Exception("Desteklenmeyen dosya türü: {$file['name']}. Sadece JPG, JPEG ve PNG resimler yüklenebilir.");
                }
            }

            // Toplam dosya boyutu kontrolü
            if ($totalUploadedSize > $maxTotalFileSize) {
                throw new Exception('Yüklenen tüm dosyaların toplam boyutu ' . ($maxTotalFileSize / (1024 * 1024)) . ' MB limitini aşıyor.');
            }
            // --- GENEL DOSYA VALİDASYONLARI SONU ---


            // Dosya işlemleri (cover_img)
            $filePath = null;
            if (isset($_FILES['cover_img']) && $_FILES['cover_img']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../uploads/test/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $fileTmpPath = $_FILES['cover_img']['tmp_name'];
                $fileName = basename($_FILES['cover_img']['name']);
                $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                // Not: Uzantı kontrolü yukarıdaki genel validasyonda yapıldı.
                // Burada ek bir kontrol gereksiz ama tutmak isterseniz ekleyebilirsiniz.

                $newFileName = uniqid('test_', true) . '.' . $extension;
                $destination = $uploadDir . $newFileName;

                if (!move_uploaded_file($fileTmpPath, $destination)) {
                    throw new Exception('Kapak resmi yüklenemedi.');
                }
                $filePath = 'uploads/test/' . $newFileName;
            }

            // Veritabanı bağlantısı
            $stmt = $pdo->prepare("
    INSERT INTO tests_lnp 
    (status, class_id, lesson_id, unit_id, topic_id, subtopic_id, test_title, start_date, end_date, cover_img, added_user_id)
    VALUES
    (:status, :class_id, :lesson_id, :unit_id, :topic_id, :subtopic_id, :title, :start_date, :end_date, :file_path, :added_user_id)
");

            $stmt->execute([
                ':status'         => $status,
                ':class_id'       => $classId,
                ':lesson_id'      => $lessonId,
                ':unit_id'        => $unitId,
                ':topic_id'       => $topicId,
                ':subtopic_id'    => $subtopicId,
                ':title'          => $title,
                ':start_date'     => $startDate,
                ':end_date'       => $endDate,
                ':file_path'      => $filePath,
                ':added_user_id'  => $addedUserId
            ]);

            $testId = $pdo->lastInsertId();

            if (!isset($_POST['questions']) || !is_array($_POST['questions']) || empty($_POST['questions'])) {
                throw new Exception('Sorular boş olamaz. Lütfen en az bir soru ekleyin.');
            }

            foreach ($_POST['questions'] as $qIndex => $question) {
                // Soru ekle
                $stmt = $pdo->prepare("
                INSERT INTO test_questions_lnp (test_id, question_text, correct_answer)
                VALUES (:test_id, :question_text, :correct_answer)
            ");
                $stmt->execute([
                    ':test_id' => $testId,
                    ':question_text' => $question['text'],
                    ':correct_answer' => $question['correct_answer'],
                ]);

                $questionId = $pdo->lastInsertId();

                // Videolar
                if (!empty($question['videos'])) {
                    $videoStmt = $pdo->prepare("
                    INSERT INTO test_question_videos_lnp (question_id, video_url)
                    VALUES (:question_id, :video_url)
                ");
                    foreach ($question['videos'] as $video) {
                        $videoStmt->execute([
                            ':question_id' => $questionId,
                            ':video_url' => $video
                        ]);
                    }
                }

                // Görseller (soruya ait görseller)
                if (isset($_FILES['questions']['name'][$qIndex]['images']) && is_array($_FILES['questions']['name'][$qIndex]['images'])) {
                    $tmpNames = $_FILES['questions']['tmp_name'][$qIndex]['images'];
                    $errors = $_FILES['questions']['error'][$qIndex]['images'];
                    $sizes = $_FILES['questions']['size'][$qIndex]['images']; // Boyutları da alalım

                    $uploadDir = __DIR__ . '/../uploads/questions/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }

                    foreach ($_FILES['questions']['name'][$qIndex]['images'] as $i => $imgName) {
                        // Genel validasyon yapıldığı için burada sadece UPLOAD_ERR_OK kontrolü yeterli.
                        // Boyut ve uzantı zaten kontrol edildi.
                        if ($errors[$i] === UPLOAD_ERR_OK) {
                            $extension = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));
                            $newFileName = uniqid('img_', true) . '.' . $extension;
                            $destination = $uploadDir . $newFileName;

                            if (!move_uploaded_file($tmpNames[$i], $destination)) {
                                throw new Exception('Soru görseli yüklenemedi: ' . $imgName);
                            }

                            $imagePath = 'uploads/questions/' . $newFileName;

                            $stmt = $pdo->prepare("
                            INSERT INTO test_question_files_lnp (question_id, file_path)
                            VALUES (:question_id, :file_path)
                        ");
                            $stmt->execute([
                                ':question_id' => $questionId,
                                ':file_path' => $imagePath
                            ]);
                        } else if ($errors[$i] !== UPLOAD_ERR_NO_FILE) {
                            // Eğer bir hata varsa (ki genel validasyonda yakalanmış olmalı)
                            throw new Exception('Soru görseli yükleme hatası: ' . $imgName . ' (Kod: ' . $errors[$i] . ')');
                        }
                    }
                }

                // Şıklar
                if (isset($question['options']) && is_array($question['options'])) {
                    foreach ($question['options'] as $optionKey => $option) {
                        $stmt = $pdo->prepare("
                        INSERT INTO test_question_options_lnp (question_id, option_key, option_text)
                        VALUES (:question_id, :option_key, :option_text)
                    ");
                        $stmt->execute([
                            ':question_id' => $questionId,
                            ':option_key' => $optionKey,
                            ':option_text' => $option['text'],
                        ]);

                        $optionId = $pdo->lastInsertId();

                        // Seçenek görselleri
                        if (isset($_FILES['questions']['name'][$qIndex]['options'][$optionKey]['images']) && is_array($_FILES['questions']['name'][$qIndex]['options'][$optionKey]['images'])) {
                            $optTmpNames = $_FILES['questions']['tmp_name'][$qIndex]['options'][$optionKey]['images'];
                            $optErrors = $_FILES['questions']['error'][$qIndex]['options'][$optionKey]['images'];
                            $optSizes = $_FILES['questions']['size'][$qIndex]['options'][$optionKey]['images']; // Boyutları da alalım

                            // $uploadDir zaten yukarıda tanımlanmış olabilir ama emin olmak için tekrar tanımlayalım
                            // veya scope'a dikkat edelim. Soru görselleriyle aynı dizine yüklenecekse burası uygun.
                            $uploadDir = __DIR__ . '/../uploads/questions/';
                            if (!is_dir($uploadDir)) {
                                mkdir($uploadDir, 0755, true);
                            }

                            foreach ($_FILES['questions']['name'][$qIndex]['options'][$optionKey]['images'] as $imgIndex => $fileName) {
                                // Genel validasyon yapıldığı için burada sadece UPLOAD_ERR_OK kontrolü yeterli.
                                // Boyut ve uzantı zaten kontrol edildi.
                                if ($optErrors[$imgIndex] === UPLOAD_ERR_OK) {
                                    $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                                    $newFileName = uniqid('optfile_', true) . '.' . $extension;
                                    $destination = $uploadDir . $newFileName;

                                    if (!move_uploaded_file($optTmpNames[$imgIndex], $destination)) {
                                        throw new Exception('Seçenek görseli yüklenemedi: ' . $fileName);
                                    }

                                    $stmt = $pdo->prepare("
                                    INSERT INTO test_question_option_files_lnp (option_id, file_path)
                                    VALUES (:option_id, :file_path)
                                ");
                                    $stmt->execute([
                                        ':option_id' => $optionId,
                                        ':file_path' => 'uploads/questions/' . $newFileName
                                    ]);
                                } else if ($optErrors[$imgIndex] !== UPLOAD_ERR_NO_FILE) {
                                    // Eğer bir hata varsa (ki genel validasyonda yakalanmış olmalı)
                                    throw new Exception('Seçenek görseli yükleme hatası: ' . $fileName . ' (Kod: ' . $optErrors[$imgIndex] . ')');
                                }
                            }
                        }
                    }
                }
            }

            $pdo->commit();
            echo json_encode([
                'status' => 'success',
                'message' => 'Test ve sorular başarıyla eklendi.'
            ]);
        } catch (Exception $e) {
            $pdo->rollBack();
            http_response_code(422);
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
        break;

    case 'getFilteredTests':
        $title = isset($_POST['title']) ? $_POST['title'] : '';
        $classId = isset($_POST['class_id']) ? $_POST['class_id'] : '';
        $lessonId = isset($_POST['lesson_id']) ? $_POST['lesson_id'] : '';
        $unitId = isset($_POST['unit_id']) ? $_POST['unit_id'] : '';
        $topicId = isset($_POST['topic_id']) ? $_POST['topic_id'] : '';
        $subtopicId = isset($_POST['subtopic_id']) ? $_POST['subtopic_id'] : '';
        try {

            $sql = "SELECT t.id, t.test_title AS test_title, t.created_at,
                       t.class_id, t.lesson_id,
                       t.unit_id, t.topic_id, t.subtopic_id
                FROM tests_lnp t
                WHERE 1=1"; // Her zaman doğru olan bir koşul ile başla

            $params = [];

            // Filtreleme koşulları (AND ile birleşir)
            if (!empty($title)) {
                $sql .= " AND t.test_title LIKE ?";
                $params[] = '%' . $title . '%';
            }
            if (!empty($classId)) {
                // Eğer class_id bir ID ise bu şekilde filtrelemeye devam edin.
                // Eğer class_name üzerinden filtreleme yapılıyorsa, o zaman t.class_name LIKE ? kullanmalısınız.
                // Mevcut yapınızda ID ile filtreleme olduğu için class_id kullanmaya devam ediyorum.
                $sql .= " AND t.class_id = ?";
                $params[] = $classId;
            }
            if (!empty($lessonId)) {
                $sql .= " AND t.lesson_id = ?";
                $params[] = $lessonId;
            }
            if (!empty($unitId)) {
                $sql .= " AND t.unit_id = ?";
                $params[] = $unitId;
            }
            if (!empty($topicId)) {
                $sql .= " AND t.topic_id = ?";
                $params[] = $topicId;
            }
            if (!empty($subtopicId)) {
                $sql .= " AND t.subtopic_id = ?";
                $params[] = $subtopicId;
            }

            // Global arama (OR kullanarak tüm ilgili sütunlarda arama)
            // Artık JOIN'lere gerek kalmadığı için doğrudan t.sutun_adi kullanıyoruz.
            if (!empty($searchValue)) {
                $sql .= " AND (t.title LIKE ? OR t.class_name LIKE ? OR t.lesson_name LIKE ? OR t.unit_name LIKE ? OR t.topic_name LIKE ? OR t.subtopic_name LIKE ?)";
                $params[] = '%' . $searchValue . '%';
                $params[] = '%' . $searchValue . '%';
                $params[] = '%' . $searchValue . '%';
                $params[] = '%' . $searchValue . '%';
                $params[] = '%' . $searchValue . '%';
                $params[] = '%' . $searchValue . '%';
            }

            $sql .= " ORDER BY t.created_at DESC";

            $stmt = $pdo->prepare($sql);

            if (!$stmt->execute($params)) {
                $stmt = null;
                error_log("Failed to fetch filtered tests (client-side): ");
                return [];
            }

            $tests = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt = null;




            if ($tests) {
                echo json_encode(['status' => 'success', 'data' => $tests]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Test bulunamadı.']);
            }
        } catch (Exception $e) {
            http_response_code(422); // Veya uygun bir HTTP kodu
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
            exit();
        }
        break;




    // Diğer servisler buraya eklenebilir
    case 'getTestDetails':
        $sql = "
SELECT 
    t.id AS test_id,
    t.status as status,
    t.test_title,
    t.school_id,
    t.teacher_id,
    t.cover_img,
    t.class_id,
    t.lesson_id,
    t.unit_id,
    t.topic_id,
    t.subtopic_id,
    t.start_date,
    t.end_date,
    t.created_at AS test_created_at,
    t.updated_at AS test_updated_at,
    tcc.option_count AS option_count,
    tq.id AS question_id,
    tq.question_text,
    tq.correct_answer,
    tq.created_at AS question_created_at,
    tq.updated_at AS question_updated_at,

    tqv.video_url,

    tqf.file_path AS question_file_path,

    tqo.id AS option_id,
    tqo.option_key,
    tqo.option_text,
    tqo.created_at AS option_created_at,
    tqo.updated_at AS option_updated_at,

    tqof.file_path AS option_file_path

FROM tests_lnp t
LEFT JOIN test_questions_lnp tq ON tq.test_id = t.id
LEFT JOIN test_question_videos_lnp tqv ON tqv.question_id = tq.id
LEFT JOIN test_question_files_lnp tqf ON tqf.question_id = tq.id
LEFT JOIN test_question_options_lnp tqo ON tqo.question_id = tq.id
LEFT JOIN test_question_option_files_lnp tqof ON tqof.option_id = tqo.id
LEFT JOIN test_class_option_count tcc ON tcc.class_id = t.class_id
WHERE t.id = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $_POST['test_id'] ?? null]);
        if ($stmt->rowCount() === 0) {
            echo json_encode(['status' => 'error', 'message' => 'Test bulunamadı.']);
            exit;
        }
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $response = null;

        foreach ($rows as $row) {
            if (!$response) {
                $response = [
                    'id' => $row['test_id'],
                    'status' => $row['status'],
                    'test_title' => $row['test_title'],
                    'school_id' => $row['school_id'],
                    'teacher_id' => $row['teacher_id'],
                    'cover_img' => $row['cover_img'],
                    'class_id' => $row['class_id'],
                    'lesson_id' => $row['lesson_id'],
                    'unit_id' => $row['unit_id'],
                    'topic_id' => $row['topic_id'],
                    'subtopic_id' => $row['subtopic_id'],
                    'start_date' => $row['start_date'],
                    'option_count' => $row['option_count'] ?? 3, // Varsayılan olarak 3 seçenek
                    'end_date' => $row['end_date'],
                    'created_at' => $row['test_created_at'],
                    'updated_at' => $row['test_updated_at'],
                    'questions' => [],
                ];
            }

            $questionId = $row['question_id'];
            $optionId = $row['option_id'];

            if ($questionId && !isset($response['questions'][$questionId])) {
                $response['questions'][$questionId] = [
                    'id' => $questionId,
                    'question_text' => $row['question_text'],
                    'correct_answer' => $row['correct_answer'],
                    'created_at' => $row['question_created_at'],
                    'updated_at' => $row['question_updated_at'],
                    'videos' => [],
                    'files' => [],
                    'options' => [],
                ];
            }

            // Video ekle
            if (!empty($row['video_url']) && !in_array($row['video_url'], $response['questions'][$questionId]['videos'])) {
                $response['questions'][$questionId]['videos'][] = $row['video_url'];
            }

            // Soru dosyası ekle
            if (!empty($row['question_file_path']) && !in_array($row['question_file_path'], $response['questions'][$questionId]['files'])) {
                $response['questions'][$questionId]['files'][] = $row['question_file_path'];
            }

            // Seçenek ekle
            if ($optionId && !isset($response['questions'][$questionId]['options'][$optionId])) {
                $response['questions'][$questionId]['options'][$optionId] = [
                    'id' => $optionId,
                    'option_key' => $row['option_key'],
                    'option_text' => $row['option_text'],
                    'created_at' => $row['option_created_at'],
                    'updated_at' => $row['option_updated_at'],
                    'files' => [],
                ];
            }

            // Seçenek dosyası ekle
            if (!empty($row['option_file_path']) && !in_array($row['option_file_path'], $response['questions'][$questionId]['options'][$optionId]['files'])) {
                $response['questions'][$questionId]['options'][$optionId]['files'][] = $row['option_file_path'];
            }
        }

        // Final formatlama
        if ($response) {
            $response['questions'] = array_values(array_map(function ($question) {
                $question['options'] = array_values($question['options']);
                return $question;
            }, $response['questions']));
            echo json_encode(['status' => 'success', 'data' => $response]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Ders bulunamadı.']);
        }
        break;
    case 'deleteTest':
        $testId = $_POST['id'] ?? null;

        if (!$testId || !is_numeric($testId)) {
            echo json_encode(['status' => 'error', 'message' => 'Geçersiz test ID']);
            exit;
        }
        $pdo->beginTransaction();
        try {
            // Testi sil
            $stmt = $pdo->prepare("DELETE FROM tests_lnp WHERE id = :test_id");
            $stmt->execute([':test_id' => $testId]);

            if ($stmt->rowCount() > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Test başarıyla silindi.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Test bulunamadı veya zaten silinmiş.']);
            }
            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollBack();
            http_response_code(422); // Veya uygun bir HTTP kodu
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
            exit();
        }
        break;
    case 'testUpdate':


        $testId = $_POST['test_id'] ?? null;
        $classId = $_POST['class_id'] ?? null;
        $lessonId = $_POST['lesson_id'] ?? null;
        $unitId = $_POST['unit_id'] ?? null;
        $topicId = $_POST['topic_id'] ?? null;
        $subtopicId = $_POST['subtopic_id'] ?? null;
        $title = $_POST['title'] ?? null;
        $startDate = $_POST['start_date'] ?? null;
        $endDate = $_POST['end_date'] ?? null;
        $status = $_POST['status'] ?? null;
        $newQuestionsData = $_POST['questions'] ?? []; // Gelen yeni soru verileri

        if (!$testId) {
            // test_id yoksa hata döndür
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Test ID is required.']);
            exit;
        }

        // Ortak dosya kontrol fonksiyonu tanımla
        function validateAndUploadFile($fileInfo, $uploadDir, $maxFileSize = 3145728, $allowedExtensions = ['jpg', 'jpeg', 'png'])
        {
            if ($fileInfo['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('Dosya yükleme hatası: ' . $fileInfo['error']);
            }

            $fileSize = $fileInfo['size'];
            $fileName = basename($fileInfo['name']);
            $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $fileTmpPath = $fileInfo['tmp_name'];

            // Boyut kontrolü
            if ($fileSize > $maxFileSize) {
                throw new Exception('Dosya boyutu ' . ($maxFileSize / 1024 / 1024) . 'MB\'ı geçemez.');
            }

            // Uzantı kontrolü
            if (!in_array($extension, $allowedExtensions)) {
                throw new Exception('İzin verilmeyen dosya uzantısı. Sadece JPG, JPEG, PNG kabul edilir.');
            }

            // Klasör kontrolü ve oluşturma
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $newFileName = uniqid('', true) . '.' . $extension; // Daha güvenli bir uniqid kullanımı
            $destination = $uploadDir . $newFileName;

            if (!move_uploaded_file($fileTmpPath, $destination)) {
                throw new Exception('Dosya sunucuya taşınamadı.');
            }

            return str_replace(__DIR__ . '/../', '', $destination); // Veritabanı için göreceli yol
        }


        try {
            $pdo->beginTransaction();

            $coverImage = null;
            if (isset($_FILES['cover_img']) && $_FILES['cover_img']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../uploads/test/';
                // cover_img için ayrı bir kural belirlemedik, genel fonksiyonu kullanıyoruz.
                // İstersen burada farklı boyut ve uzantı kısıtlamaları tanımlayabilirsin.
                $coverImage = validateAndUploadFile($_FILES['cover_img'], $uploadDir);
            }

            if ($coverImage == null) {
                $stmt = $pdo->prepare("
                UPDATE tests_lnp
                SET status=?,class_id = ?, lesson_id = ?, unit_id = ?, topic_id = ?, subtopic_id = ?, test_title = ?, start_date = ?, end_date = ?
                WHERE id = ?
            ");
                $stmt->execute([
                    $status,
                    $classId,
                    $lessonId,
                    $unitId,
                    $topicId,
                    $subtopicId,
                    $title,
                    $startDate,
                    $endDate,
                    $testId
                ]);
            } else {
                $stmt = $pdo->prepare("
                UPDATE tests_lnp
                SET cover_img=?,class_id = ?, lesson_id = ?, unit_id = ?, topic_id = ?, subtopic_id = ?, test_title = ?, start_date = ?, end_date = ?
                WHERE id = ?
            ");
                $stmt->execute([
                    $coverImage,
                    $classId,
                    $lessonId,
                    $unitId,
                    $topicId,
                    $subtopicId,
                    $title,
                    $startDate,
                    $endDate,
                    $testId
                ]);
            }

            // 2. Mevcut Soruları, Şıkları, Dosyaları ve Videoları Temizle
            $stmt = $pdo->prepare("SELECT id FROM test_questions_lnp WHERE test_id = ?");
            $stmt->execute([$testId]);
            $existingQuestionIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

            if (!empty($existingQuestionIds)) {
                $placeholders = implode(',', array_fill(0, count($existingQuestionIds), '?'));

                // Sorulara ait dosyaları ve videoları sil (önce dosya yollarını alıp sunucudan sil)
                // NOT: Sunucudaki fiziksel dosyaları silmek için bu yolları kullanmalısın.
                // Örnek: unlink(__DIR__ . '/../' . $filePath);
                $stmt = $pdo->prepare("SELECT file_path FROM test_question_files_lnp WHERE question_id IN ($placeholders)");
                $stmt->execute($existingQuestionIds);
                $filesToDelete = $stmt->fetchAll(PDO::FETCH_COLUMN);

                $stmt = $pdo->prepare("DELETE FROM test_question_files_lnp WHERE question_id IN ($placeholders)");
                $stmt->execute($existingQuestionIds);

                // Sorulara ait videoları sil
                $stmt = $pdo->prepare("DELETE FROM test_question_videos_lnp WHERE question_id IN ($placeholders)");
                $stmt->execute($existingQuestionIds);

                // Sorulara ait şıkların ID'lerini al
                $stmt = $pdo->prepare("SELECT id FROM test_question_options_lnp WHERE question_id IN ($placeholders)");
                $stmt->execute($existingQuestionIds);
                $optionsToDeleteFromQuestion = $stmt->fetchAll(PDO::FETCH_COLUMN);

                if (!empty($optionsToDeleteFromQuestion)) {
                    $optPlaceholders = implode(',', array_fill(0, count($optionsToDeleteFromQuestion), '?'));

                    // Şıklara ait dosyaları sil (önce dosya yollarını alıp sunucudan sil)
                    $stmt = $pdo->prepare("SELECT file_path FROM test_question_option_files_lnp WHERE option_id IN ($optPlaceholders)");
                    $stmt->execute($optionsToDeleteFromQuestion);
                    $optionFilesToDelete = $stmt->fetchAll(PDO::FETCH_COLUMN);

                    $stmt = $pdo->prepare("DELETE FROM test_question_option_files_lnp WHERE option_id IN ($optPlaceholders)");
                    $stmt->execute($optionsToDeleteFromQuestion);

                    // Şıkları sil
                    $stmt = $pdo->prepare("DELETE FROM test_question_options_lnp WHERE id IN ($optPlaceholders)");
                    $stmt->execute($optionsToDeleteFromQuestion);
                }

                // Ana soruları sil
                $stmt = $pdo->prepare("DELETE FROM test_questions_lnp WHERE id IN ($placeholders)");
                $stmt->execute($existingQuestionIds);
            }

            // 3. Yeni Gelen Soruları, Şıkları, Dosyaları ve Videoları Kaydet
            foreach ($newQuestionsData as $questionIndex => $questionData) {
                $questionText = $questionData['text'] ?? '';
                $correctAnswer = $questionData['correct_answer'] ?? null;

                // Yeni soruyu ekle
                $stmt = $pdo->prepare("
                INSERT INTO test_questions_lnp (test_id, question_text, correct_answer)
                VALUES (?, ?, ?)
            ");
                $stmt->execute([
                    $testId,
                    $questionText,
                    $correctAnswer
                ]);
                $newQuestionId = $pdo->lastInsertId();

                // Sorunun videolarını kaydet
                if (!empty($questionData['videos'])) {
                    foreach ($questionData['videos'] as $videoIndex => $videoPath) {
                        $stmt = $pdo->prepare("
                        INSERT INTO test_question_videos_lnp (question_id, video_url)
                        VALUES (?, ?)
                    ");
                        $stmt->execute([$newQuestionId, $videoPath]);
                    }
                }

                // Sorunun dosyalarını kaydet (resimler vb. için)
                // Bu kısım, $_FILES süper globalinden gelen çok boyutlu dizinin yapısına göre ayarlanmalı.
                // Dosya yükleme yapısında bir değişiklik varsa (örneğin 'questions[index][images][fileKey]'),
                // bu kısım da o yapıya göre güncellenmelidir.
                if (isset($_FILES['questions']['name'][$questionIndex]['images'])) {
                    foreach ($_FILES['questions']['name'][$questionIndex]['images'] as $fileKey => $fileName) {
                        $fileInfo = [
                            'name' => $_FILES['questions']['name'][$questionIndex]['images'][$fileKey],
                            'type' => $_FILES['questions']['type'][$questionIndex]['images'][$fileKey],
                            'tmp_name' => $_FILES['questions']['tmp_name'][$questionIndex]['images'][$fileKey],
                            'error' => $_FILES['questions']['error'][$questionIndex]['images'][$fileKey],
                            'size' => $_FILES['questions']['size'][$questionIndex]['images'][$fileKey],
                        ];
                        $uploadDir = __DIR__ . '/../uploads/questions/';
                        $relativePath = validateAndUploadFile($fileInfo, $uploadDir);

                        $stmt = $pdo->prepare("INSERT INTO test_question_files_lnp (question_id, file_path) VALUES (?, ?)");
                        $stmt->execute([$newQuestionId, $relativePath]);
                    }
                }
                if (isset($_POST['questions'][$questionIndex]['existing_images'])) {
                    foreach ($_POST['questions'][$questionIndex]['existing_images'] as $existingImagePath) {
                        $stmt = $pdo->prepare("INSERT INTO test_question_files_lnp (question_id, file_path) VALUES (?, ?)");
                        $stmt->execute([
                            $newQuestionId,
                            $existingImagePath
                        ]);
                    }
                }


                // Şıkları kaydet
                if (!empty($questionData['options'])) {
                    foreach ($questionData['options'] as $optionKey => $optionData) {
                        $optionText = $optionData['text'] ?? '';

                        $stmt = $pdo->prepare("
                        INSERT INTO test_question_options_lnp (question_id, option_key, option_text)
                        VALUES (?, ?, ?)
                    ");
                        $stmt->execute([$newQuestionId, $optionKey, $optionText]);
                        $newOptionId = $pdo->lastInsertId();

                        // Şık dosyalarını kaydet (resimler vb. için)
                        if (!empty($_FILES['questions']['name'][$questionIndex]['options'][$optionKey]['images'])) {
                            foreach ($_FILES['questions']['name'][$questionIndex]['options'][$optionKey]['images'] as $fileKey => $fileName) {
                                $fileInfo = [
                                    'name' => $_FILES['questions']['name'][$questionIndex]['options'][$optionKey]['images'][$fileKey],
                                    'type' => $_FILES['questions']['type'][$questionIndex]['options'][$optionKey]['images'][$fileKey],
                                    'tmp_name' => $_FILES['questions']['tmp_name'][$questionIndex]['options'][$optionKey]['images'][$fileKey],
                                    'error' => $_FILES['questions']['error'][$questionIndex]['options'][$optionKey]['images'][$fileKey],
                                    'size' => $_FILES['questions']['size'][$questionIndex]['options'][$optionKey]['images'][$fileKey],
                                ];
                                $uploadDir = __DIR__ . '/../uploads/questions/'; // Şık görselleri için de aynı klasörü kullanabiliriz
                                $relativePath = validateAndUploadFile($fileInfo, $uploadDir);

                                $stmt = $pdo->prepare("INSERT INTO test_question_option_files_lnp (option_id, file_path) VALUES (?, ?)");
                                $stmt->execute([
                                    $newOptionId,
                                    $relativePath
                                ]);
                            }
                        }

                        // VAR OLAN (existing_images) dosyaları doğrudan insert et (opsiyonel)
                        if (isset($_POST['questions'][$questionIndex]['options'][$optionKey]['existing_images'])) {
                            foreach ($_POST['questions'][$questionIndex]['options'][$optionKey]['existing_images'] as $existingImagePath) {
                                $stmt = $pdo->prepare("INSERT INTO test_question_option_files_lnp (option_id, file_path) VALUES (?, ?)");
                                $stmt->execute([
                                    $newOptionId,
                                    $existingImagePath
                                ]);
                            }
                        }
                    }
                }
            }

            $pdo->commit();

            header('Content-Type: application/json');
            echo json_encode(['status' => 'success', 'message' => 'Test başarıyla güncellendi.']);
        } catch (\Exception $e) {
            $pdo->rollBack();
            header('Content-Type: application/json', true, 500);
            echo json_encode(['status' => 'error', 'message' => 'Bir hata oluştu: ' . $e->getMessage()]);
            // Hata detaylarını loglamak genellikle iyi bir uygulamadır.
            // error_log('Test güncelleme hatası: ' . $e->getMessage() . ' Satır: ' . $e->getLine() . ' Dosya: ' . $e->getFile());
        }

        break;
    case 'getTestByStudent':
        $role = $_SESSION['role'];

        $testId = $_GET['test_id'] ?? null;
        $userId = $_SESSION['id'] ?? null;
        if ($role == 2) {

            $sql = "select * from user_grades_lnp where user_id = :user_id and test_id = :test_id and score<80";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['user_id' => $userId, 'test_id' => $testId]);
            $grade = $stmt->fetch(PDO::FETCH_ASSOC);



            if (isset($grade['fail_count']) && $grade['fail_count'] >= 3) {
                echo json_encode(['status' => 'error', 'message' => 'Bu teste 3 kez başarısız oldunuz, tekrar giremezsiniz.']);
                exit;
            }
        }
        if ($role == 1 || $role == 2 || $role == 4) {

            $sql = "
    SELECT 
        t.id AS test_id,
        t.test_title,
        t.school_id,
        t.teacher_id,
        t.cover_img,
        t.class_id,
        t.lesson_id,
        t.unit_id,
        t.topic_id,
        t.subtopic_id,
        t.start_date,
        t.end_date,
        t.created_at AS test_created_at,
        t.updated_at AS test_updated_at,

        tq.id AS question_id,
        tq.question_text,
        tq.created_at AS question_created_at,
        tq.updated_at AS question_updated_at,

        tqv.video_url,

        tqf.file_path AS question_file_path,

        tqo.id AS option_id,
        tqo.option_key,
        tqo.option_text,
        tqo.created_at AS option_created_at,
        tqo.updated_at AS option_updated_at,

        tqof.file_path AS option_file_path

    FROM tests_lnp t
    LEFT JOIN test_questions_lnp tq ON tq.test_id = t.id
    LEFT JOIN test_question_videos_lnp tqv ON tqv.question_id = tq.id
    LEFT JOIN test_question_files_lnp tqf ON tqf.question_id = tq.id
    LEFT JOIN test_question_options_lnp tqo ON tqo.question_id = tq.id
    LEFT JOIN test_question_option_files_lnp tqof ON tqof.option_id = tqo.id
    WHERE t.id = :id";

            // Eğer rol 1 veya 4 değilse, class_id filtresi ekle
            $params = ['id' => $testId ?? null];

            if (!in_array($_SESSION['role'] ?? null, [1, 4])) {
                $sql .= " AND t.class_id = :class_id";
                $params['class_id'] = $_SESSION['class_id'] ?? null;
                $sql .= " AND status = :status"; // Sadece aktif testleri getir
                $params['status'] = 1;
            }

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

            if ($stmt->rowCount() === 0) {

                echo json_encode(['status' => 'error', 'message' => 'Test bulunamadı.']);
                exit;
            }

            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $response = null;

            foreach ($rows as $row) {
                if (!$response) {
                    $response = [
                        'id' => $row['test_id'],
                        'test_title' => $row['test_title'],
                        'school_id' => $row['school_id'],
                        'teacher_id' => $row['teacher_id'],
                        'cover_img' => $row['cover_img'],
                        'class_id' => $row['class_id'],
                        'lesson_id' => $row['lesson_id'],
                        'unit_id' => $row['unit_id'],
                        'topic_id' => $row['topic_id'],
                        'subtopic_id' => $row['subtopic_id'],
                        'start_date' => $row['start_date'],
                        'end_date' => $row['end_date'],
                        'created_at' => $row['test_created_at'],
                        'updated_at' => $row['test_updated_at'],
                        'questions' => [],
                    ];
                }

                $questionId = $row['question_id'];
                $optionId = $row['option_id'];

                if ($questionId && !isset($response['questions'][$questionId])) {
                    $response['questions'][$questionId] = [
                        'id' => $questionId,
                        // HTML etiketlerini kaldırıyoruz
                        'question_text' => $row['question_text'],
                        'created_at' => $row['question_created_at'],
                        'updated_at' => $row['question_updated_at'],
                        'videos' => [],
                        'files' => [],
                        'options' => [],
                    ];
                }

                // Video ekle
                if (!empty($row['video_url']) && !in_array($row['video_url'], $response['questions'][$questionId]['videos'])) {
                    $response['questions'][$questionId]['videos'][] = $row['video_url'];
                }

                // Soru dosyası ekle
                if (!empty($row['question_file_path']) && !in_array($row['question_file_path'], $response['questions'][$questionId]['files'])) {
                    $response['questions'][$questionId]['files'][] = $row['question_file_path'];
                }

                // Seçenek ekle
                if ($optionId && !isset($response['questions'][$questionId]['options'][$optionId])) {
                    $response['questions'][$questionId]['options'][$optionId] = [
                        'id' => $optionId,
                        'option_key' => $row['option_key'],
                        // HTML etiketlerini kaldırıyoruz
                        'option_text' => $row['option_text'],
                        'created_at' => $row['option_created_at'],
                        'updated_at' => $row['option_updated_at'],
                        'files' => [],
                    ];
                }

                // Seçenek dosyası ekle
                if (!empty($row['option_file_path']) && !in_array($row['option_file_path'], $response['questions'][$questionId]['options'][$optionId]['files'])) {
                    $response['questions'][$questionId]['options'][$optionId]['files'][] = $row['option_file_path'];
                }
            }

            // Final formatlama
            if ($response) {

                $response['questions'] = array_values(array_map(function ($question) {

                    $question['options'] = array_values($question['options']);
                    return $question;
                }, $response['questions']));
                echo json_encode(['status' => 'success', 'data' => $response]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Ders bulunamadı.']);
            }
        } else {
            // Öğretmen veya yönetici için tüm sınıflar

        }
        break;
    case 'submitTestAnswers':
        $data = json_decode(file_get_contents('php://input'), true);

        $testId = $data['test_id'];
        $userAnswers = $data['user_answers'];
        $userId = $_SESSION['id'] ?? null;

        try {
            $pdo->beginTransaction();

            // Doğru cevapları ve soru bilgilerini tek seferde veritabanından çekme
            $questionIds = array_column($userAnswers, 'question_id');
            $inClause = str_repeat('?,', count($questionIds) - 1) . '?';
            $stmtGetCorrectAnswers = $pdo->prepare("SELECT id, correct_answer FROM test_questions_lnp WHERE id IN ($inClause)");
            $stmtGetCorrectAnswers->execute($questionIds);
            $correctAnswersMap = $stmtGetCorrectAnswers->fetchAll(PDO::FETCH_KEY_PAIR);

            // Cevapları kaydetme ve doğru/yanlış durumunu belirleme
            $correctCount = 0;
            $totalQuestions = count($userAnswers);
            $userAnswersWithCorrectness = [];

            $stmtInsert = $pdo->prepare("INSERT INTO test_user_answers_lnp (user_id, test_id, question_id, selected_option_key) VALUES (:user_id, :test_id, :question_id, :selected_option_key)");

            foreach ($userAnswers as $answer) {
                $questionId = $answer['question_id'];
                $selectedOption = $answer['selected_option_key'];
                $correctAnswer = $correctAnswersMap[$questionId] ?? null;

                // Cevabı kaydet
                $stmtInsert->execute([
                    ':user_id' => $userId,
                    ':test_id' => $testId,
                    ':question_id' => $questionId,
                    ':selected_option_key' => $selectedOption
                ]);

                // Doğru/yanlış kontrolü
                $isCorrect = ($correctAnswer !== null && $selectedOption !== null && $selectedOption === $correctAnswer);
                if ($isCorrect) {
                    $correctCount++;
                }

                // Yeni diziye doğru/yanlış bilgisini ve doğru cevabı ekle
                $userAnswersWithCorrectness[] = [
                    'question_id' => $questionId,
                    'selected_option_key' => $selectedOption,
                    'is_correct' => $isCorrect,
                    'correct_option_key' => $correctAnswer
                ];
            }

            // Skor hesaplama ve kaydetme
            $testInfoStmt = $pdo->prepare("SELECT end_date, class_id, lesson_id, unit_id, topic_id, subtopic_id FROM tests_lnp WHERE id = :test_id");
            $testInfoStmt->execute([':test_id' => $testId]);
            $testInfo = $testInfoStmt->fetch(PDO::FETCH_ASSOC);

            $percentageScore = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100, 2) : 0;

            // Test süresinin dolup dolmadığını kontrol et
            if (isset($testInfo['end_date']) && strtotime($testInfo['end_date']) < time()) {
                $percentageScore *= 0.95;
            }

            // Öncelik sırasına göre ID'yi ve anahtarını bulma
            $finalParentId = null;
            $finalParentKey = null;

            if (!empty($testInfo['subtopic_id'])) {
                $finalParentId = $testInfo['subtopic_id'];
                $finalParentKey = 'subtopic_id';
            } elseif (!empty($testInfo['topic_id'])) {
                $finalParentId = $testInfo['topic_id'];
                $finalParentKey = 'topic_id';
            } elseif (!empty($testInfo['unit_id'])) {
                $finalParentId = $testInfo['unit_id'];
                $finalParentKey = 'unit_id';
            } elseif (!empty($testInfo['lesson_id'])) {
                $finalParentId = $testInfo['lesson_id'];
                $finalParentKey = 'lesson_id';
            } elseif (!empty($testInfo['class_id'])) {
                $finalParentId = $testInfo['class_id'];
                $finalParentKey = 'class_id';
            }

            // Yeni eklenen kısım: school_content_lnp'den ilgili veriyi çekme
            $contentUrl = null;
            if ($finalParentId && $finalParentKey) {
                // Sütun ismini PDO'da bind edemediğimiz için, whitelisting (güvenli liste) kontrolü yapıyoruz.
                $allowedColumns = ['subtopic_id', 'topic_id', 'unit_id', 'lesson_id', 'class_id'];
                if (in_array($finalParentKey, $allowedColumns)) {
                    $stmtContent = $pdo->prepare("SELECT id, slug FROM school_content_lnp WHERE {$finalParentKey} = :id LIMIT 1");
                    $stmtContent->execute([':id' => $finalParentId]);
                    $contentInfo = $stmtContent->fetch(PDO::FETCH_ASSOC);

                    if ($contentInfo) {
                        $contentUrl = "icerik-detay/" . $contentInfo['slug'];
                    }
                }
            }

            $checkStmt = $pdo->prepare("SELECT * FROM user_grades_lnp WHERE test_id = :test_id AND user_id = :user_id");
            $checkStmt->execute([
                ':test_id' => $testId,
                ':user_id' => $userId
            ]);
            $existingGrade = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if ($existingGrade) {
                $updateStmt = $pdo->prepare("UPDATE user_grades_lnp SET score = :score, fail_count = IFNULL(fail_count, 0) + :fail_increment WHERE test_id = :test_id AND user_id = :user_id");
                $updateStmt->execute([
                    ':score' => $percentageScore,
                    ':fail_increment' => $percentageScore < 80 ? 1 : 0,
                    ':test_id' => $testId,
                    ':user_id' => $userId
                ]);
            } else {
                $insertGradeStmt = $pdo->prepare("INSERT INTO user_grades_lnp (user_id, test_id, class_id, lesson_id, unit_id, topic_id, subtopic_id, score, fail_count) VALUES (:user_id, :test_id, :class_id, :lesson_id, :unit_id, :topic_id, :subtopic_id, :score, :fail_count)");
                $insertGradeStmt->execute([
                    ':user_id' => $userId,
                    ':test_id' => $testId,
                    ':class_id' => $testInfo['class_id'],
                    ':lesson_id' => $testInfo['lesson_id'],
                    ':unit_id' => $testInfo['unit_id'],
                    ':topic_id' => $testInfo['topic_id'],
                    ':subtopic_id' => $testInfo['subtopic_id'],
                    ':score' => $percentageScore,
                    ':fail_count' => $percentageScore < 80 ? 1 : 0
                ]);
            }

            $pdo->commit();

            // Yanıt dizisini oluştur ve dinamik anahtarı ekle
            $response = [
                'status' => 'success',
                'message' => 'Cevaplar başarıyla kaydedildi ve değerlendirildi.',
                'score' => $percentageScore,
                'correct_count' => $correctCount,
                'total_questions' => $totalQuestions,
                'user_answers_with_correctness' => $userAnswersWithCorrectness
            ];

            if ($finalParentKey !== null) {
                $response[$finalParentKey] = $finalParentId;
            }

            // Oluşturulan URL'yi yanıt dizisine ekle
            if ($contentUrl !== null) {
                $response['content_url'] = $contentUrl;
            }

            echo json_encode($response);
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo json_encode([
                'status' => 'error',
                'message' => 'Veritabanı hatası: ' . $e->getMessage()
            ]);
        }
        break;
    case 'mainSchoolLessonAdd':
        $classIdArray = $_POST['class_id'] ?? [];
        $lessonName = $_POST['lesson_name'] ?? null;
        $packageType = $_POST['package_type'] ?? null;


        if (empty($classIdArray) || !$lessonName) {
            echo json_encode(['status' => 'error', 'message' => 'Yaş grubu ve ders adı gereklidir.']);
            exit;
        }

        try {
            if (!is_array($classIdArray)) {
                $classIdArray = [$classIdArray];
            }

            $pdo->beginTransaction();

            // 1. Ders tablosuna ekle
            $stmt = $pdo->prepare("INSERT INTO main_school_lessons_lnp (name,package_type) VALUES (:name,:package_type)");
            $stmt->execute([':name' => $lessonName, ':package_type' => $packageType]);

            $lessonId = $pdo->lastInsertId();

            // 2. İlişkisel tabloya her class_id için kayıt ekle
            $stmtLink = $pdo->prepare("INSERT INTO mainschool_lesson_class_id_lnp (lesson_id, class_id) VALUES (:lesson_id, :class_id)");
            foreach ($classIdArray as $classId) {
                $stmtLink->execute([
                    ':lesson_id' => $lessonId,
                    ':class_id' => $classId
                ]);
            }

            $pdo->commit();

            echo json_encode(['status' => 'success', 'message' => 'Ders başarıyla kaydedildi.']);
        } catch (Exception $e) {
            $pdo->rollBack();
            echo json_encode([
                'status' => 'error',
                'message' => 'Bir hata oluştu: ' . $e->getMessage()
            ]);
        }
        break;

    case 'deleteMainSchoolLesson':
        $lessonId = $_POST['id'] ?? null;
        if (!$lessonId || !is_numeric($lessonId)) {
            echo json_encode(['status' => 'error', 'message' => 'Geçersiz ders ID']);
            exit;
        }
        $pdo->beginTransaction();
        try {
            // Dersin varlığını kontrol et
            $stmt = $pdo->prepare("SELECT * FROM main_school_lessons_lnp WHERE id = :lesson_id");
            $stmt->execute([':lesson_id' => $lessonId]);
            if ($stmt->rowCount() === 0) {
                echo json_encode(['status' => 'error', 'message' => 'Ders bulunamadı.']);
                exit;
            }


            $stmt = $pdo->prepare("DELETE FROM mainschool_lesson_class_id_lnp WHERE lesson_id = :lesson_id");
            $stmt->execute([':lesson_id' => $lessonId]);

            // Dersin kendisini sil
            $stmt = $pdo->prepare("DELETE FROM main_school_lessons_lnp WHERE id = :lesson_id");
            $stmt->execute([':lesson_id' => $lessonId]);

            $pdo->commit();
            echo json_encode(['status' => 'success', 'message' => 'Ders başarıyla silindi.']);
        } catch (Exception $e) {
            $pdo->rollBack();
            http_response_code(422); // Veya uygun bir HTTP kodu
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
            exit();
        }
        break;
    case 'mainSchoolGetLessons':
        $classId = $_POST['class_id'] ?? null;
        if (!$classId || !is_numeric($classId)) {
            echo json_encode(['status' => 'error', 'message' => 'Sınıf ID gerekli']);
            exit;
        }
        try {
            $stmt = $pdo->prepare("
            SELECT 
                ml.id,
                ml.name,
                ml.package_type,
                mlc.class_id
            FROM main_school_lessons_lnp ml
            INNER JOIN mainschool_lesson_class_id_lnp mlc ON mlc.lesson_id = ml.id
            WHERE mlc.class_id = :class_id   AND ml.status=1
        ");
            $stmt->execute([':class_id' => $classId]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($results)) {
                echo json_encode(['status' => 'error', 'message' => 'Ders bulunamadı']);
                exit;
            }

            $response = [];
            foreach ($results as $row) {
                $response[] = [
                    'id' => (int)$row['id'],
                    'name' => $row['name'],
                    'package_type' => $row['package_type'],
                    'class_id' => (int)$row['class_id']
                ];
            }

            echo json_encode(['status' => 'success', 'data' => $response]);
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Hata oluştu: ' . $e->getMessage()
            ]);
        }
        break;
    case 'mainSchoolGetLesson':
        $lessonId = $_GET['id'] ?? null;

        if (!$lessonId) {
            echo json_encode(['status' => 'error', 'message' => 'Ders ID gerekli']);
            exit;
        }

        try {
            $stmt = $pdo->prepare("
            SELECT 
                ml.id,
                ml.name,
                mlc.class_id,
                ml.package_type
            FROM main_school_lessons_lnp ml
            INNER JOIN mainschool_lesson_class_id_lnp mlc ON mlc.lesson_id = ml.id
            WHERE ml.id = :lesson_id
        ");
            $stmt->execute([':lesson_id' => $lessonId]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($results)) {
                echo json_encode(['status' => 'error', 'message' => 'Ders bulunamadı']);
                exit;
            }

            // İlk satırdan ana bilgileri al
            $response = [
                'id' => $results[0]['id'],
                'name' => $results[0]['name'],
                'package_type' => $results[0]['package_type'],
                'classes' => []
            ];

            foreach ($results as $row) {
                $response['classes'][] = [
                    'id' => (int)$row['class_id']
                ];
            }

            echo json_encode(['status' => 'success', 'data' => $response]);
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Hata oluştu: ' . $e->getMessage()
            ]);
        }
        break;
    case 'mainSchoolLessonUpdate':
        $lessonId = $_POST['lesson_id'] ?? null;
        $classIds = $_POST['class_ids'] ?? [];
        $lessonName = $_POST['lesson_name'] ?? null;
        $packageType = $_POST['package_type'] ?? null;

        if (!$lessonId || !is_numeric($lessonId) || empty($classIds) || !$lessonName) {
            echo json_encode(['status' => 'error', 'message' => 'Geçersiz ders ID veya eksik bilgiler']);
            exit;
        }

        try {
            $pdo->beginTransaction();

            // Dersi güncelle
            $stmt = $pdo->prepare("UPDATE main_school_lessons_lnp SET name = :name, package_type=:package_type WHERE id = :id");
            $stmt->execute([
                ':name' => $lessonName,
                ':package_type' => $packageType,
                ':id' => $lessonId
            ]);

            // Eski sınıf ilişkilerini sil
            $deleteStmt = $pdo->prepare("DELETE FROM mainschool_lesson_class_id_lnp WHERE lesson_id = :lesson_id");
            $deleteStmt->execute([':lesson_id' => $lessonId]);

            // Yeni sınıf ilişkilerini ekle
            $insertStmt = $pdo->prepare("INSERT INTO mainschool_lesson_class_id_lnp (lesson_id, class_id) VALUES (:lesson_id, :class_id)");
            foreach ($classIds as $classId) {
                if (is_numeric($classId)) {
                    $insertStmt->execute([
                        ':lesson_id' => $lessonId,
                        ':class_id' => $classId
                    ]);
                }
            }

            $pdo->commit();

            echo json_encode(['status' => 'success', 'message' => 'Ders başarıyla güncellendi.']);
        } catch (Exception $e) {
            $pdo->rollBack();
            http_response_code(422);
            echo json_encode(['status' => 'error', 'message' => 'Bir hata oluştu: ' . $e->getMessage()]);
            exit;
        }

        break;
    case 'mainSchoolUnitAdd':
        $lessonId = $_POST['lesson_id'] ?? null;
        $unitName = $_POST['unit_name'] ?? null;
        $classId = $_POST['class_id'] ?? null;
        $unitOrder = $_POST['unit_order'] ?? null;
        // development_package_ids artık bir dizi olarak gelebilir
        $developmentPackageIds = $_POST['development_package_ids'] ?? [];

        // Temel zorunlu alan kontrolü
        if (!$lessonId || !$unitName || !$classId || !isset($unitOrder)) {
            echo json_encode(['status' => 'error', 'message' => 'Ders ID, birim adı, sınıf ID ve ünite sırası gereklidir.']);
            exit;
        }

        // Gelişim Paket ID'lerini noktalı virgülle birleştir
        // Eğer developmentPackageIds boş veya dizi değilse boş string olarak ayarla
        $packagesToSave = '';
        if (!empty($developmentPackageIds) && is_array($developmentPackageIds)) {
            $packagesToSave = implode(';', $developmentPackageIds);
        }

        try {
            $pdo->beginTransaction();

            // Birim tablosuna ekle
            // development_package_id sütununu da dahil ettik
            $stmt = $pdo->prepare("
                INSERT INTO main_school_units_lnp (class_id, lesson_id, name, unit_order, development_package_id) 
                VALUES (:class_id, :lesson_id, :name, :unit_order, :development_package_ids)
            ");

            $stmt->execute([
                ':class_id' => $classId,
                ':lesson_id' => $lessonId,
                ':name' => $unitName,
                ':unit_order' => $unitOrder,
                ':development_package_ids' => $packagesToSave // Birleştirilmiş paket ID'lerini kaydet
            ]);

            $pdo->commit();

            echo json_encode(['status' => 'success', 'message' => 'Ünite başarıyla kaydedildi.']);
        } catch (Exception $e) {
            $pdo->rollBack();
            error_log("Ünite kaydetme hatası: " . $e->getMessage() . " - Dosya: " . $e->getFile() . " - Satır: " . $e->getLine());
            echo json_encode([
                'status' => 'error',
                'message' => 'Bir hata oluştu: ' . $e->getMessage()
            ]);
        }

        break;
    case "deleteMainSchoolUnit":
        $id = $_POST['id'] ?? null;


        if (!$id || !is_numeric($id)) {
            echo json_encode(['status' => 'error', 'message' => 'Geçersiz ID']);
            exit;
        }

        try {
            // Silme işlemi
            $stmt = $pdo->prepare("DELETE FROM main_school_units_lnp WHERE id = ?");
            $stmt->execute([$id]);

            if ($stmt->rowCount()) {
                echo json_encode(['status' => 'success', 'message' => 'Başarıyla silindi.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Kayıt bulunamadı veya silinemedi.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
        }
        break;
    case 'mainSchoolGetUnit':
        $unitId = $_GET['id'] ?? null;
        if (!$unitId || !is_numeric($unitId)) {
            echo json_encode(['status' => 'error', 'message' => 'Geçersiz ünite ID']);
            exit;
        }
        try {
            $stmt = $pdo->prepare("select msul.id as id ,msul.name unit_name,c.id class_id,msul.unit_order,msul.development_package_id,msul.lesson_id from main_school_units_lnp msul INNER JOIN classes_lnp c on c.id=msul.class_id where msul.id=:id");
            $stmt->execute([':id' => $unitId]);
            $unit = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$unit) {
                echo json_encode(['status' => 'error', 'message' => 'Ünite bulunamadı']);
                exit;
            }

            echo json_encode(['status' => 'success', 'data' => $unit]);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
        }
        break;
    case 'mainSchoolUnitUpdate':
        $unitId = $_POST['unit_id'] ?? null;
        $unitName = $_POST['unit_name'] ?? null;
        $lessonId = $_POST['lesson_id'] ?? null;
        $classId = $_POST['class_id'] ?? null;
        $unitOrder = $_POST['unit_order'] ?? null; // Yeni: unit_order eklendi
        $developmentPackageIds = $_POST['development_package_ids'] ?? []; // Yeni: development_package_ids eklendi

        // Temel doğrulama
        if (!$unitId || !$unitName || !$lessonId || !$classId) {
            echo json_encode(['status' => 'error', 'message' => 'Ünite ,  ders  sınıf zorunludur .']);
            exit;
        }

        // development_package_ids kontrolü ve formatlama
        // Eğer development_package_ids bir dizi ise, noktalı virgülle ayrılmış bir string'e çevir.
        // Eğer null veya boş bir dizi ise, boş string olarak kaydet.
        $developmentPackageIdString = null;
        if (is_array($developmentPackageIds) && !empty($developmentPackageIds)) {
            $developmentPackageIdString = implode(';', $developmentPackageIds);
        } else {
            $developmentPackageIdString = ''; // Boş dizi veya null ise boş string olarak kaydet
        }

        try {
            $pdo->beginTransaction();

            // Üniteyi güncelle
            // development_package_id ve unit_order alanları eklendi
            $stmt = $pdo->prepare("UPDATE main_school_units_lnp SET 
                                name = :unit_name, 
                                lesson_id = :lesson_id, 
                                class_id = :class_id, 
                                unit_order = :unit_order, 
                                development_package_id = :development_package_id 
                                WHERE id = :id");

            $stmt->execute([
                ':unit_name' => $unitName,
                ':lesson_id' => $lessonId,
                ':class_id' => $classId, // class_id de eklenmeli
                ':unit_order' => $unitOrder,
                ':development_package_id' => $developmentPackageIdString, // String olarak kaydet
                ':id' => $unitId
            ]);

            $pdo->commit();

            echo json_encode(['status' => 'success', 'message' => 'Ünite başarıyla güncellendi.']);
        } catch (Exception $e) {
            $pdo->rollBack();
            echo json_encode([
                'status' => 'error',
                'message' => 'Bir hata oluştu: ' . $e->getMessage()
            ]);
        }
        break;
    case 'mainSchoolGetUnits':
        $lessonId = $_POST['lesson_id'] ?? null;
        $class_id = $_POST['class_id'] ?? null;

        if (!$lessonId || !$class_id) {
            echo json_encode(['status' => 'error', 'message' => 'Ders ID ve Sınıf ID gereklidir.']);
            exit;
        }

        try {
            $stmt = $pdo->prepare("
            SELECT *
FROM mainschool_lesson_class_id_lnp mlc
INNER JOIN main_school_units_lnp msu ON msu.lesson_id = mlc.lesson_id
WHERE mlc.lesson_id = :lesson_id AND mlc.class_id = :class_id AND msu.class_id = :class_id AND msu.status = 1
ORDER BY msu.unit_order asc
        ");

            $stmt->execute([
                ':lesson_id' => $lessonId,
                ':class_id' => $class_id
            ]);

            $units = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($units)) {
                echo json_encode(['status' => 'error', 'message' => 'Ünite bulunamadı']);
                exit;
            }

            echo json_encode(['status' => 'success', 'data' => $units]);
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Hata oluştu: ' . $e->getMessage()
            ]);
        }
        break;

    case 'mainSchoolTopicAdd':
        $unitId = $_POST['unit_id'] ?? null;
        $topicName = $_POST['topic_name'] ?? null;

        if (!$unitId || !$topicName) {
            echo json_encode(['status' => 'error', 'message' => 'Ünite ID ve konu adı gereklidir.']);
            exit;
        }
        try {
            $pdo->beginTransaction();

            // 1. Konu tablosuna ekle
            $stmt = $pdo->prepare("INSERT INTO main_school_topics_lnp (unit_id, name) VALUES (:unit_id, :name)");
            $stmt->execute([':unit_id' => $unitId, ':name' => $topicName]);

            $pdo->commit();

            echo json_encode(['status' => 'success', 'message' => 'Konu başarıyla kaydedildi.']);
        } catch (Exception $e) {
            $pdo->rollBack();
            echo json_encode([
                'status' => 'error',
                'message' => 'Bir hata oluştu: ' . $e->getMessage()
            ]);
        }

        break;
    case 'deleteMainSchoolTopic':
        $id = $_POST['id'] ?? null;
        if (!$id || !is_numeric($id)) {
            echo json_encode(['status' => 'error', 'message' => 'Geçersiz ID']);
            exit;
        }
        try {
            $stmt = $pdo->prepare("DELETE FROM main_school_topics_lnp WHERE id = ?");
            $stmt->execute([$id]);

            if ($stmt->rowCount()) {
                echo json_encode(['status' => 'success', 'message' => 'Başarıyla silindi.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Kayıt bulunamadı veya silinemedi.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
        }
        break;
    case 'mainSchoolGetTopic':
        $topicId = $_GET['id'] ?? null;

        if (!$topicId || !is_numeric($topicId)) {
            echo json_encode(['status' => 'error', 'message' => 'Geçersiz konu ID']);
            exit;
        }
        try {
            $stmt = $pdo->prepare("SELECT t.id as id,u.id as unit_id,t.name as topic_name ,u.lesson_id as lesson_id,mlsc.class_id FROM main_school_topics_lnp t INNER JOIN main_school_units_lnp u ON t.unit_id=u.id INNER JOIN main_school_lessons_lnp l on l.id=u.lesson_id INNER JOIN mainschool_lesson_class_id_lnp as mlsc on mlsc.lesson_id=l.id  WHERE t.id = :id");
            $stmt->execute([':id' => $topicId]);
            $topic = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$topic) {
                echo json_encode(['status' => 'error', 'message' => 'Konu bulunamadı']);
                exit;
            }

            echo json_encode(['status' => 'success', 'data' => $topic]);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
        }
        break;
    case 'mainSchoolTopicUpdate':
        $topicId = $_POST['topic_id'] ?? null;
        $topicName = $_POST['topic_name'] ?? null;
        $unitId = $_POST['unit_id'] ?? null;

        if (!$topicId || !$topicName || !$unitId) {
            echo json_encode(['status' => 'error', 'message' => 'Konu ID, konu adı ve ünite ID gereklidir.']);
            exit;
        }
        try {
            $pdo->beginTransaction();

            // 1. Konuyu güncelle
            $stmt = $pdo->prepare("UPDATE main_school_topics_lnp SET name = :name, unit_id = :unit_id WHERE id = :id");
            $stmt->execute([':name' => $topicName, ':unit_id' => $unitId, ':id' => $topicId]);

            $pdo->commit();

            echo json_encode(['status' => 'success', 'message' => 'Konu başarıyla güncellendi.']);
        } catch (Exception $e) {
            $pdo->rollBack();
            echo json_encode([
                'status' => 'error',
                'message' => 'Bir hata oluştu: ' . $e->getMessage()
            ]);
        }
        break;
    case 'mainSchoolGetTopics':
        $unitId = $_POST['unit_id'] ?? null;

        if (!$unitId) {
            echo json_encode(['status' => 'error', 'message' => 'Ünite ID gereklidir.']);
            exit;
        }

        try {
            $stmt = $pdo->prepare("
        SELECT t.id, t.name
        FROM main_school_topics_lnp t
        WHERE t.unit_id = :unit_id AND t.status = 1");
            $stmt->execute([':unit_id' => $unitId]);
            $topics = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($topics)) {
                echo json_encode(['status' => 'error', 'message' => 'Konu bulunamadı']);
                exit;
            }

            echo json_encode(['status' => 'success', 'data' => $topics]);
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Hata oluştu: ' . $e->getMessage()
            ]);
        }
        break;
    case 'addExtraPackage':
        try {
            $name = trim($_POST['addPackageName'] ?? '');
            $type = $_POST['addPackageType'] ?? '';
            $price = $_POST['addPackagePrice'] ?? '';
            $addMonths = $_POST['addMonths'] ?? '';
            $description = $_POST['addPackageDescription'] ?? '';
            if (empty($addMonths)) {
                $limit_count = $_POST['addCount'] ?? '';
            } else {
                $limit_count = $_POST['addMonths'] ?? '';
            }

            if ($name === '') {
                throw new Exception('Geçersiz veya eksik parametreler.');
            }

            $stmt = $pdo->prepare("INSERT INTO extra_packages_lnp (name, type, limit_count,price,description) VALUES (?, ?, ?,?,?)");
            $stmt->execute([$name, $type, $limit_count, $price, $description]);

            echo json_encode(['status' => 'success', 'message' => 'Paket başarıyla eklendi.']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'updateExtraPackage':
        try {
            $id = intval($_POST['updatePackageId'] ?? 0);
            $name = trim($_POST['updatePackageName'] ?? '');
            $type = $_POST['updatePackageType'] ?? '';
            $price = $_POST['updatePackagePrice'] ?? '';
            $addMonths = $_POST['updateMonths'] ?? '';
            $description = $_POST['updatePackageDescription'] ?? '';
            if (empty($addMonths)) {
                $limit_count = $_POST['updateCount'] ?? '';
            } else {
                $limit_count = $_POST['updateMonths'] ?? '';
            }


            if ($id <= 0 || $name === '') {
                throw new Exception('Geçersiz veya eksik parametreler.');
            }

            $stmt = $pdo->prepare("UPDATE extra_packages_lnp SET name = ?, type = ?, limit_count = ?,price=?,description=? WHERE id = ?");
            $stmt->execute([$name, $type, $limit_count, $price, $description, $id]);

            echo json_encode(['status' => 'success', 'message' => 'Paket başarıyla güncellendi.']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'deleteExtraPackage':
        try {
            $id = intval($_POST['id'] ?? 0);

            if ($id <= 0) {
                throw new Exception('Geçersiz ID.');
            }

            $stmt = $pdo->prepare("DELETE FROM extra_packages_lnp WHERE id = ?");
            $stmt->execute([$id]);

            echo json_encode(['status' => 'success', 'message' => 'Paket başarıyla silindi.']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;
    case 'teacherTimeSettings':

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = file_get_contents('php://input');
            $lessonData = json_decode($input, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(400); // Bad Request
                echo json_encode(['status' => 'error', 'message' => 'Geçersiz JSON verisi.']);
                exit();
            }

            if (!is_array($lessonData) || empty($lessonData)) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Ders verisi boş veya geçersiz.']);
                exit();
            }

            $successCount = 0;
            $errorMessages = [];

            try {
                // Veritabanı bağlantısını al
                $pdo->beginTransaction(); // İşlemi başlat (birden fazla insert olacağı için)

                foreach ($lessonData as $lesson) {
                    $date = $lesson['date'] ?? null;
                    $startTime = $lesson['start_time'] ?? null;
                    $endTime = $lesson['end_time'] ?? null;
                    $teacherId = $_GET['id'];

                    if (!$date || !$startTime || !$endTime) {
                        $errorMessages[] = 'Tarih veya zaman eksik: ' . json_encode($lesson);
                        continue;
                    }

                    $dateObj = DateTime::createFromFormat('d.m.Y', $date);
                    if (!$dateObj) {
                        $errorMessages[] = 'Geçersiz tarih formatı: ' . $date;
                        continue;
                    }
                    $formattedDate = $dateObj->format('Y-m-d');

                    // Dersin zaten var olup olmadığını kontrol et
                    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM teacher_available_times_lnp WHERE teacher_id = ? AND available_date = ? AND start_time = ? AND end_time = ?");
                    $checkStmt->execute([$teacherId, $formattedDate, $startTime, $endTime]);
                    if ($checkStmt->fetchColumn() > 0) {
                        $errorMessages[] = "Ders zaten mevcut: Tarih: {$date}, Başlangıç: {$startTime}, Bitiş: {$endTime}";
                        continue; // Mevcut dersi eklemeyi atla
                    }

                    // Veritabanına kaydetme işlemi
                    $insertStmt = $pdo->prepare("INSERT INTO teacher_available_times_lnp (teacher_id, available_date, start_time, end_time) VALUES (?, ?, ?, ?)");
                    if ($insertStmt->execute([$teacherId, $formattedDate, $startTime, $endTime])) {
                        $successCount++;
                    } else {
                        $errorMessages[] = "Ders kaydedilirken hata oluştu: Tarih: {$date}, Başlangıç: {$startTime}, Bitiş: {$endTime}";
                    }
                }
                $pdo->commit(); // İşlemi onayla

                if ($successCount > 0) {
                    echo json_encode([
                        'status' => 'success',
                        'message' => $successCount . ' adet ders başarıyla kaydedildi.',
                        'errors' => $errorMessages
                    ]);
                } else {
                    http_response_code(500);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Hiçbir ders kaydedilemedi.',
                        'errors' => $errorMessages
                    ]);
                }
            } catch (PDOException $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack(); // Hata oluşursa işlemi geri al
                }
                error_log("Ders kaydetme toplu işlemi hatası: " . $e->getMessage());
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Dersler kaydedilirken genel bir veritabanı hatası oluştu.']);
            }
            exit();
        } else {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Sadece POST istekleri kabul edilir.']);
            exit();
        }
        break;

    case 'getTeacherTimeSettings':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            try {
                $stmt = $pdo->prepare("
                SELECT 
                    id, 
                    DATE_FORMAT(available_date, '%d.%m.%Y') AS date, 
                    start_time, 
                    end_time 
                FROM teacher_available_times_lnp 
                WHERE teacher_id = ?
                    AND TIMESTAMP(available_date, start_time) >= NOW()
                ORDER BY available_date, start_time 
                LIMIT 100
            ");
                $stmt->execute([$_GET['id']]);
                $lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);

                echo json_encode(['status' => 'success', 'data' => $lessons]);
            } catch (PDOException $e) {
                error_log("Öğretmen derslerini çekme hatası: " . $e->getMessage());
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Dersler getirilirken bir hata oluştu.']);
            }
            exit();
        } else {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Sadece GET istekleri kabul edilir.']);
            exit();
        }
        break;


    case 'deleteTeacherTimeSetting':
        $lessonId = $_GET['id'] ?? null;
        $teacherId = $_GET['teacher_id'];

        if (!$lessonId) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Ders ID\'si eksik.']);
            exit();
        }

        try {


            // Güvenlik: Sadece kendi dersini silebildiğinden emin ol
            $checkOwnerStmt = $pdo->prepare("SELECT COUNT(*) FROM teacher_available_times_lnp WHERE id = ? AND teacher_id = ?");
            $checkOwnerStmt->execute([$lessonId, $teacherId]);
            if ($checkOwnerStmt->fetchColumn() === 0) {
                http_response_code(403);
                echo json_encode(['status' => 'error', 'message' => 'Bu dersi silmeye yetkiniz yok veya ders bulunamadı.']);
                exit();
            }

            $deleteStmt = $pdo->prepare("DELETE FROM teacher_available_times_lnp WHERE id = ?");
            if ($deleteStmt->execute([$lessonId])) {
                echo json_encode(['status' => 'success', 'message' => 'Ders başarıyla silindi.']);
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Ders silinirken bir hata oluştu.']);
            }
        } catch (PDOException $e) {
            error_log("Ders silme hatası: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Ders silinirken genel bir veritabanı hatası oluştu.']);
        }
        exit();

        break;
    case 'submitPrivateLessonRequest':
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            $response = ['status' => 'error', 'message' => 'Bu servis için POST isteği gereklidir.'];
            break;
        }

        $student_user_id = $_SESSION['id'];

        // $_POST ile yakalama ve manuel temizleme/doğrulama
        $class_id = isset($_POST['class_id']) && $_POST['class_id'] !== '' ? (int)$_POST['class_id'] : null;
        $lesson_id = isset($_POST['lesson_id']) && $_POST['lesson_id'] !== '' ? (int)$_POST['lesson_id'] : null;
        $unit_id = isset($_POST['unit_id']) && $_POST['unit_id'] !== '' ? (int)$_POST['unit_id'] : null;
        $topic_id = isset($_POST['topic_id']) && $_POST['topic_id'] !== '' ? (int)$_POST['topic_id'] : null;
        $subtopic_id = isset($_POST['subtopic_id']) && $_POST['subtopic_id'] !== '' ? (int)$_POST['subtopic_id'] : null;
        $time_slot = isset($_POST['time_slot']) ? htmlspecialchars(trim($_POST['time_slot']), ENT_QUOTES, 'UTF-8') : '';

        // **Debug amaçlı kontrol:** Gelen POST verilerini kontrol edin
        // error_log("submitPrivateLessonRequest - POST verileri: " . var_export($_POST, true));


        if ($lesson_id === null || $lesson_id <= 0 || empty($time_slot)) {
            $response = ['status' => 'error', 'message' => 'Ders seçimi ve uygun zaman aralığı zorunludur.'];
            break;
        }

        try {
            $sql = "INSERT INTO private_lesson_requests_lnp (
                        student_user_id,
                        class_id,
                        lesson_id,
                        unit_id,
                        topic_id,
                        subtopic_id,
                        time_slot
                    ) VALUES (
                        :student_user_id,
                        :class_id,
                        :lesson_id,
                        :unit_id,
                        :topic_id,
                        :subtopic_id,
                        :time_slot
                    )";

            $stmt = $pdo->prepare($sql);

            $stmt->bindValue(':student_user_id', $student_user_id, PDO::PARAM_INT);
            $stmt->bindValue(':class_id', $class_id, $class_id === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
            $stmt->bindValue(':lesson_id', $lesson_id, PDO::PARAM_INT);
            $stmt->bindValue(':unit_id', $unit_id, $unit_id === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
            $stmt->bindValue(':topic_id', $topic_id, $topic_id === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
            $stmt->bindValue(':subtopic_id', $subtopic_id, $subtopic_id === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
            $stmt->bindValue(':time_slot', $time_slot, PDO::PARAM_STR);

            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Ders talebiniz başarıyla gönderildi!']);
            } else {
                echo json_encode(['status' => 'success', 'message' => 'Ders talebi gönderilirken bir hata oluştu. Lütfen tekrar deneyin.']);
            }
            $mailText = "Merhaba,\n\n"
                . "Özel ders ataması yapıldıktan sonra tarafınıza bilgilendirme yapılacaktır..\n";

            $mailer->send($_SESSION['email'], 'Özel Ders Bilgilendirmesi', $mailText);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Geçersiz servis']);
        }
        break;
    case 'privateLessonRequest':
        $id = $_POST['request_id'] ?? null;
        $assigned_teacher_id = $_POST['assigned_teacher_id'] ?? null;
        $desired_date = $_POST['desired_date'] ?? null;

        if (!$id || !$assigned_teacher_id || !$desired_date) {
            echo json_encode(['success' => false, 'message' => 'Eksik bilgi gönderildi.']);
            exit();
        }

        $stmt = $pdo->prepare("UPDATE private_lesson_requests_lnp 
                           SET assigned_teacher_id = ?, meet_date = ?, request_status = ?
                           WHERE id = ?");
        $result = $stmt->execute([$assigned_teacher_id, $desired_date, 1, $id]);

        if ($result) {
            $_SESSION['payment_success'] = true;

            // 1. Gerekli bilgileri al
            $infoStmt = $pdo->prepare("
            SELECT 
                pr.student_user_id, 
                pr.assigned_teacher_id,  
                c.name AS class_name,
                l.name AS lesson_name
            FROM private_lesson_requests_lnp pr
            LEFT JOIN classes_lnp c ON c.id = pr.class_id
            LEFT JOIN lessons_lnp l ON l.id = pr.lesson_id
            WHERE pr.id = ?
        ");
            $infoStmt->execute([$id]);
            $info = $infoStmt->fetch(PDO::FETCH_ASSOC);

            if (!$info) {
                echo json_encode(['success' => false, 'message' => 'Bilgiler alınamadı.']);
                exit();
            }

            $student_id = $info['student_user_id'];
            $teacher_id = $info['assigned_teacher_id'];
            $class_name = $info['class_name'] ?? '-';
            $lesson_name = $info['lesson_name'] ?? '-';
            $meetingDescription = "{$class_name} Özel Ders";

            // Zoom Toplantısı Oluştur
            require_once '../zoom/ZoomTokenManager.php';

            try {
                $zoom = new ZoomTokenManager();
                $access_token = $zoom->getAccessToken();
                $start_time = date('Y-m-d\TH:i:s', strtotime($desired_date));
                $userId = 'me';

                $meeting_details = [
                    'topic' => $meetingDescription,
                    'type' => 2,
                    'start_time' => $start_time,
                    'duration' => 60,
                    'timezone' => 'Europe/Istanbul',
                    'settings' => [
                        'host_video' => true,
                        'participant_video' => true,
                        'auto_recording' => 'cloud',
                    ],
                ];

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://api.zoom.us/v2/users/{$userId}/meetings");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    "Authorization: Bearer $access_token",
                    "Content-Type: application/json",
                ]);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($meeting_details));

                $response = curl_exec($ch);
                $err = curl_error($ch);
                curl_close($ch);

                if ($err) {
                    error_log("Zoom API Hatası: " . $err);
                    echo json_encode(['success' => false, 'message' => 'Zoom bağlantısı kurulamadı.', 'zoom_error' => $err]);
                    exit();
                }

                $zoomResponse = json_decode($response, true);
                if (!isset($zoomResponse['join_url'], $zoomResponse['start_url'])) {
                    error_log("Zoom Yanıtı Geçersiz: " . $response);
                    echo json_encode(['success' => false, 'message' => 'Zoom toplantısı oluşturulamadı.', 'zoom_error' => $response]);
                    exit();
                }

                $joinUrl = $zoomResponse['join_url'];
                $startUrl = $zoomResponse['start_url'];

                // meetings_lnp tablosuna kaydet
                $insertMeetingStmt = $pdo->prepare("
                INSERT INTO meetings_lnp 
                (organizer_id, participant_id, description, meeting_date, zoom_join_url, zoom_start_url)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
                $meetingResult = $insertMeetingStmt->execute([
                    $teacher_id,
                    $student_id,
                    $meetingDescription,
                    $desired_date,
                    $joinUrl,
                    $startUrl
                ]);

                if (!$meetingResult) {
                    error_log('Error inserting into meetings_lnp for Zoom meeting. ID: ' . $id);
                }
            } catch (Exception $e) {
                error_log("Zoom Hatası: " . $e->getMessage());
            }

            // Kullanıcıları mail ile bilgilendir
            $studentStmt = $pdo->prepare("SELECT name, surname, email FROM users_lnp WHERE id = ?");
            $studentStmt->execute([$student_id]);
            $student = $studentStmt->fetch(PDO::FETCH_ASSOC);

            $teacherStmt = $pdo->prepare("SELECT name, surname, email FROM users_lnp WHERE id = ?");
            $teacherStmt->execute([$teacher_id]);
            $teacher = $teacherStmt->fetch(PDO::FETCH_ASSOC);

            $student_full_name = $student ? $student['name'] . ' ' . $student['surname'] : 'Bilinmiyor';
            $student_email = $student['email'] ?? null;

            $teacher_full_name = $teacher ? $teacher['name'] . ' ' . $teacher['surname'] : 'Bilinmiyor';
            $teacher_email = $teacher['email'] ?? null;

            $dt = new DateTime($desired_date);
            $formattedDate = $dt->format('d.m.Y H:i');

            $mailText = "Merhaba,\n\n"
                . "Özel ders {$formattedDate} tarihinde yapılacaktır.\n"
                . "Sınıf: {$class_name}\n"
                . "Ders: {$lesson_name}\n"
                . "Öğrenci: {$student_full_name}\n"
                . "Öğretmen: {$teacher_full_name}\n"
                . "Ders Linki: {$joinUrl}\n\n"
                . "Lütfen zamanında hazır olunuz.\n\nİyi dersler dileriz.";

            if ($student_email) {
                $mailer->send($student_email, 'Özel Ders Bilgilendirmesi', $mailText);
            }

            if ($teacher_email) {
                $mailer->send($teacher_email, 'Özel Ders Ataması', $mailText . "\n\nBaşlatmak için link: {$startUrl}");
            }

            echo json_encode(['success' => true, 'message' => 'Özel ders oluşturuldu ve Zoom toplantısı planlandı.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Güncelleme işlemi başarısız oldu.']);
        }

        break;

    case 'updateCoachingRequest':
        $id = $_POST['request_id'] ?? null;
        $assigned_teacher_id = $_POST['assigned_teacher_id'] ?? null;

        if (empty($id) || empty($assigned_teacher_id)) {
            $response['message'] = 'Eksik veya geçersiz bilgi gönderildi. (Talep ID veya Öğretmen ID)';
            echo json_encode($response);
            exit();
        }

        try {
            // 1. Talebi güncelle (Öğretmen ata ve durumu 'Atandı' yap)
            $stmt = $pdo->prepare("UPDATE coaching_guidance_requests_lnp 
                               SET 
                                   teacher_id = :teacher_id, 
                                   assignment_date = NOW(), 
                                   status = 1               
                               WHERE id = :request_id");

            $stmt->bindParam(':teacher_id', $assigned_teacher_id, PDO::PARAM_INT);
            $stmt->bindParam(':request_id', $id, PDO::PARAM_INT);
            $result = $stmt->execute();

            if ($result) {
                // YENİ EKLENEN KISIM BAŞLANGICI
                // 🔍 package_id'yi al
                $packageInfoStmt = $pdo->prepare('SELECT package_id FROM coaching_guidance_requests_lnp WHERE id = :request_id');
                $packageInfoStmt->bindParam(':request_id', $id, PDO::PARAM_INT);
                $packageInfoStmt->execute();
                $package_id_data = $packageInfoStmt->fetch(PDO::FETCH_ASSOC);
                $package_id = $package_id_data['package_id'] ?? null;

                $limit_count = 0; // Varsayılan değer

                if (!empty($package_id)) {
                    // 🔍 extra_packages_lnp tablosundan limit_count'u al
                    $limitCountStmt = $pdo->prepare('SELECT limit_count FROM extra_packages_lnp WHERE id = :package_id');
                    $limitCountStmt->bindParam(':package_id', $package_id, PDO::PARAM_INT);
                    $limitCountStmt->execute();
                    $limit_count_data = $limitCountStmt->fetch(PDO::FETCH_ASSOC);

                    $limit_count = $limit_count_data['limit_count'] ?? 0;
                    // Limit_count negatif veya çok büyük olmadığından emin ol
                    $limit_count = max(0, (int)$limit_count);
                }

                // coaching_guidance_requests_lnp tablosundaki start_date ve end_date'i güncelle
                // Sadece package_id varsa veya limit_count > 0 ise güncellenecek
                if ($limit_count > 0) {
                    $updateDatesStmt = $pdo->prepare("
                        UPDATE coaching_guidance_requests_lnp 
                        SET 
                            start_date = NOW(), 
                            end_date = DATE_ADD(NOW(), INTERVAL :limit_count MONTH) 
                        WHERE id = :request_id
                    ");
                    $updateDatesStmt->bindParam(':limit_count', $limit_count, PDO::PARAM_INT);
                    $updateDatesStmt->bindParam(':request_id', $id, PDO::PARAM_INT);
                    $updateDatesResult = $updateDatesStmt->execute();

                    if (!$updateDatesResult) {
                        error_log("start_date/end_date güncelleme başarısız: Request ID: {$id}, Package ID: {$package_id}, Limit Count: {$limit_count}");
                        // Hata mesajını response'a ekleyebilirsiniz, ancak genel başarıyı bozmayalım
                    }
                } else {
                    // Eğer paket yoksa veya limit_count 0 ise start_date ve end_date'i NULL yapabiliriz
                    $updateNullDatesStmt = $pdo->prepare("
                        UPDATE coaching_guidance_requests_lnp 
                        SET 
                            start_date = NULL, 
                            end_date = NULL 
                        WHERE id = :request_id
                    ");
                    $updateNullDatesStmt->bindParam(':request_id', $id, PDO::PARAM_INT);
                    $updateNullDatesStmt->execute();
                }
                // YENİ EKLENEN KISIM SONU

                // 🔍 2. Güncellenen taleple ilgili detayları çek (e-posta için)
                // Düzeltme: cgr.request_description buraya eklendi
                $infoStmt = $pdo->prepare("
                    SELECT 
                        cgr.user_id AS student_user_id, 
                        cgr.teacher_id AS assigned_teacher_id, 
                        cgr.request_type
                    FROM coaching_guidance_requests_lnp cgr
                    WHERE cgr.id = :request_id
                ");
                $infoStmt->bindParam(':request_id', $id, PDO::PARAM_INT);
                $infoStmt->execute();
                $info = $infoStmt->fetch(PDO::FETCH_ASSOC);

                if (!$info) {
                    $response['message'] = 'Güncellenen talep bilgileri alınamadı.';
                    echo json_encode($response);
                    exit();
                }

                $student_id = $info['student_user_id'];
                $teacher_id = $info['assigned_teacher_id'];
                $request_type = $info['request_type'] ?? 'Bilinmiyor';

                // 🔍 3. Öğrenci bilgileri
                $studentStmt = $pdo->prepare("SELECT name, surname, email FROM users_lnp WHERE id = :student_id");
                $studentStmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
                $studentStmt->execute();
                $student = $studentStmt->fetch(PDO::FETCH_ASSOC);

                // 🔍 4. Öğretmen bilgileri
                $teacherStmt = $pdo->prepare("SELECT name, surname, email FROM users_lnp WHERE id = :teacher_id");
                $teacherStmt->bindParam(':teacher_id', $teacher_id, PDO::PARAM_INT);
                $teacherStmt->execute();
                $teacher = $teacherStmt->fetch(PDO::FETCH_ASSOC);

                $student_full_name = $student ? $student['name'] . ' ' . $student['surname'] : 'Bilinmiyor';
                $student_email = $student['email'] ?? null;

                $teacher_full_name = $teacher ? $teacher['name'] . ' ' . $teacher['surname'] : 'Bilinmiyor';
                $teacher_email = $teacher['email'] ?? null;

                $current_assignment_date = (new DateTime())->format('d.m.Y H:i');

                // E-posta içeriği (Başlangıç ve bitiş tarihlerini de içerebilir)
                $mailText = "Merhaba,\n\n"
                    . "Yeni bir Koçluk/Rehberlik talebi ataması yapılmıştır.\n\n"
                    . "Talep Türü: {$request_type}\n"
                    . "Atama Tarihi: {$current_assignment_date}\n";

                if ($limit_count > 0) {
                    $start_date_obj = new DateTime();
                    $end_date_obj = (new DateTime())->modify("+{$limit_count} months");
                    $mailText .= "Paket Başlangıç Tarihi: " . $start_date_obj->format('d.m.Y') . "\n";
                    $mailText .= "Paket Bitiş Tarihi: " . $end_date_obj->format('d.m.Y') . "\n";
                }

                $mailText .= "Öğrenci: {$student_full_name}\n";
                if ($student_email) {
                    $mailText .= "Öğrenci E-posta: {$student_email}\n"; // Öğrenci e-postası da eklendi
                }
                $mailText .= "Öğretmen: {$teacher_full_name}\n";
                if ($teacher_email) {
                    $mailText .= "Öğretmen E-posta: {$teacher_email}\n"; // Düzeltme: Öğretmen e-postası eklendi
                }
                $mailText .= "\nBilgilerinize sunulur.\n\nİyi günler dileriz.";


                if ($student_email) {
                    $mailer->send($student_email, 'Koçluk/Rehberlik Talep Atama Bilgilendirmesi', $mailText);
                }

                if ($teacher_email) {
                    $mailer->send($teacher_email, 'Yeni Koçluk/Rehberlik Talebi Ataması', $mailText);
                }

                $response['success'] = true;
                echo json_encode(['success' => true, 'message' => 'Koçluk/Rehberlik talebi başarıyla güncellendi ve bilgilendirme e-postaları gönderildi.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Güncelleme işlemi başarısız oldu. Veritabanı hatası.']); // 'false' anahtarı 'message' olarak değiştirildi
            }
        } catch (PDOException $e) {
            error_log("Koçluk/Rehberlik talebi AJAX hatası: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Sunucu hatası oluştu: ' . $e->getMessage()]); // 'false' anahtarı 'message' olarak değiştirildi
        }
        break;
    case 'extraPackageGraphicReport':
        try {
            // Günlük veriler (son 30 gün)
            $daily = $pdo->query("
            SELECT DATE_FORMAT(created_at, '%d-%m-%Y') AS day,
                   SUM(total_amount) AS total_payment,   -- price yerine total_amount kullanıldı
                   ROUND(SUM(kdv_amount), 0) AS total_tax
            FROM extra_package_payments_lnp
            GROUP BY day
            ORDER BY STR_TO_DATE(day, '%d-%m-%Y') DESC
            LIMIT 30
        ")->fetchAll(PDO::FETCH_ASSOC);

            // Haftalık veriler (son 30 hafta)
            $weekly = $pdo->query("
            SELECT CONCAT(YEAR(created_at), ' HAFTA ', LPAD(WEEK(created_at, 1), 2, '0')) AS week,
                   SUM(total_amount) AS total_payment,   -- price yerine total_amount kullanıldı
                   ROUND(SUM(kdv_amount), 0) AS total_tax
            FROM extra_package_payments_lnp
            GROUP BY week
            ORDER BY YEAR(created_at) DESC, WEEK(created_at, 1) DESC
            LIMIT 30
        ")->fetchAll(PDO::FETCH_ASSOC);

            // Aylık veriler (son 30 ay)
            $monthly = $pdo->query("
            SELECT DATE_FORMAT(created_at, '%Y-%m') AS period_sort,
                   DATE_FORMAT(created_at, '%m-%Y') AS period,
                   SUM(total_amount) AS total_payment,   -- price yerine total_amount kullanıldı
                   ROUND(SUM(kdv_amount), 0) AS total_tax
            FROM extra_package_payments_lnp
            GROUP BY period_sort
            ORDER BY period_sort DESC
            LIMIT 30
        ")->fetchAll(PDO::FETCH_ASSOC);

            // Yıllık veriler (son 30 yıl)
            $yearly = $pdo->query("
            SELECT YEAR(created_at) AS year,
                   SUM(total_amount) AS total_payment,   -- price yerine total_amount kullanıldı
                   ROUND(SUM(kdv_amount), 0) AS total_tax
            FROM extra_package_payments_lnp
            GROUP BY year
            ORDER BY year DESC
            LIMIT 30
        ")->fetchAll(PDO::FETCH_ASSOC);

            // JavaScript tarafına gönderirken verileri tersine çeviriyoruz ki en eski en başta olsun
            echo json_encode([
                'daily' => array_reverse($daily),
                'weekly' => array_reverse($weekly),
                'monthly' => array_reverse($monthly),
                'yearly' => array_reverse($yearly)
            ]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;
    case 'getCalendarEvents':
        $userId = $_SESSION['id'] ?? null; // JavaScript'ten gelen user_id (null ise yönetici)

        $events = [];

        // meetings_lnp tablosundan toplantı verilerini çekiyoruz
        $sql = "
        SELECT 
            m.id, 
            m.description, 
            m.meeting_date,
            m.zoom_start_url,
            m.zoom_join_url,
            u_organizer.name AS organizer_name,
            u_organizer.surname AS organizer_surname,
            u_participant.name AS participant_name,
            u_participant.surname AS participant_surname
        FROM meetings_lnp m
        LEFT JOIN users_lnp u_organizer ON m.organizer_id = u_organizer.id
        LEFT JOIN users_lnp u_participant ON m.participant_id = u_participant.id
    ";

        $params = [];
        if ($userId !== null) { // Eğer belirli bir kullanıcı ID'si varsa, o kullanıcıyla ilgili toplantıları filtrele
            $sql .= " WHERE m.organizer_id = :userId OR m.participant_id = :userId";
            $params[':userId'] = $userId;
        }

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);


            foreach ($results as $row) {
                $events[] = [
                    'id' => 'meeting_' . $row['id'], // FullCalendar için benzersiz etkinlik ID'si
                    'title' => $row['description'],  // Toplantının açıklamasını etkinlik başlığı olarak kullan
                    'start' => $row['meeting_date'], // Toplantı tarihi ve saati
                    'zoom_start_url' => $row['zoom_start_url'], // Toplantı tarihi ve saati
                    'zoom_join_url' => $row['zoom_join_url'], // Toplantı tarihi ve saati
                    'allDay' => false, // Toplantılar genellikle tüm gün sürmez
                    'extendedProps' => [ // Etkinlik detayları için ek özellikler
                        'type' => 'Toplantı', // Etkinlik türü
                        'description' => $row['description'], // Tam açıklama metni
                        'organizerName' => $row['organizer_name'] . ' ' . $row['organizer_surname'],
                        'participantName' => $row['participant_name'] . ' ' . $row['participant_surname'],
                    ],
                    'backgroundColor' => '#007bff', // Toplantı etkinlikleri için mavi arka plan rengi
                    'borderColor' => '#007bff',      // Toplantı etkinlikleri için mavi kenarlık rengi
                ];
            }

            echo json_encode($events); // FullCalendar'a JSON formatında etkinlikleri gönder

        } catch (PDOException $e) {
            error_log("Veritabanı hatası (getCalendarEvents - meetings_lnp): " . $e->getMessage());
            echo json_encode([]); // Hata durumunda boş bir dizi döndür
        }
        break;
    case 'createMeeting':

        $organizerId = $_SESSION['id'] ?? null;
        $participantId = $_POST['participant_id'] ?? null;
        $description = $_POST['description'] ?? null;
        $meetingDate = $_POST['meeting_date'] ?? null;

        if (empty($organizerId) || empty($participantId) || empty($description) || empty($meetingDate)) {
            echo json_encode(['success' => false, 'message' => 'Lütfen tüm alanları doldurun.']);
            exit();
        }

        try {
            require_once '../zoom/ZoomTokenManager.php';
            $zoom = new ZoomTokenManager();
            $access_token = $zoom->getAccessToken();

            $start_time = date('Y-m-d\TH:i:s', strtotime($meetingDate));
            $userId = 'me';

            $meeting_details = [
                'topic' => $description,
                'type' => 2,
                'start_time' => $start_time,
                'duration' => 60,
                'timezone' => 'Europe/Istanbul',
                'settings' => [
                    'host_video' => true,
                    'participant_video' => true,
                    'auto_recording' => 'cloud',
                ],
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://api.zoom.us/v2/users/{$userId}/meetings");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer $access_token",
                "Content-Type: application/json",
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($meeting_details));

            $response = curl_exec($ch);
            $err = curl_error($ch);
            curl_close($ch);

            if ($err) {
                error_log("Zoom API Hatası: " . $err);
                echo json_encode(['success' => false, 'message' => 'Zoom API bağlantı hatası: ' . $err]);
                exit();
            }

            $zoomResponse = json_decode($response, true);

            if (!isset($zoomResponse['join_url'], $zoomResponse['start_url'])) {
                error_log("Zoom API Hatası: " . $response);
                echo json_encode(['success' => false, 'message' => 'Zoom toplantısı oluşturulamadı.', 'detail' => $response]);
                exit();
            }

            $zoomJoinUrl = $zoomResponse['join_url'];
            $zoomStartUrl = $zoomResponse['start_url'];

            // Veritabanına kaydet
            $stmt = $pdo->prepare("
            INSERT INTO meetings_lnp 
            (organizer_id, participant_id, description, meeting_date, zoom_join_url, zoom_start_url) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
            $success = $stmt->execute([
                $organizerId,
                $participantId,
                $description,
                $meetingDate,
                $zoomJoinUrl,
                $zoomStartUrl
            ]);

            if (!$success) {
                $errorInfo = $stmt->errorInfo();
                error_log("Veritabanı hatası (Zoom URL'ler dahil): " . $errorInfo[2]);
                echo json_encode(['success' => false, 'message' => 'Veritabanına kaydedilirken bir hata oluştu.']);
                exit();
            }

            // Öğrenci ve öğretmen bilgilerini al
            $studentStmt = $pdo->prepare("SELECT name, surname, email FROM users_lnp WHERE id = ?");
            $studentStmt->execute([$participantId]);
            $student = $studentStmt->fetch(PDO::FETCH_ASSOC);

            $teacherStmt = $pdo->prepare("SELECT name, surname, email FROM users_lnp WHERE id = ?");
            $teacherStmt->execute([$organizerId]);
            $teacher = $teacherStmt->fetch(PDO::FETCH_ASSOC);

            $student_full_name = $student ? $student['name'] . ' ' . $student['surname'] : 'Bilinmiyor';
            $student_email = $student['email'] ?? null;

            $teacher_full_name = $teacher ? $teacher['name'] . ' ' . $teacher['surname'] : 'Bilinmiyor';
            $teacher_email = $teacher['email'] ?? null;

            $dt = new DateTime($meetingDate);
            $formattedDate = $dt->format('d.m.Y H:i');

            $mailText = "Merhaba,\n\n"
                . "Yeni bir özel toplantı oluşturuldu.\n\n"
                . "📅 Tarih: {$formattedDate}\n"
                . "📌 Konu: {$description}\n"
                . "👩‍🏫 Öğretmen: {$teacher_full_name}\n"
                . "👨‍🎓 Öğrenci: {$student_full_name}\n"
                . "🔗 Toplantı Linki: {$zoomJoinUrl}\n\n"
                . "İyi dersler dileriz.";

            // Öğrenciye gönder
            if ($student_email) {
                $mailer->send($student_email, 'Yeni Özel Toplantı Bilgilendirmesi', $mailText);
            }

            // Öğretmene gönder (start link dahil)
            if ($teacher_email) {
                $teacherMailText = $mailText . "\n🧑‍💼 Toplantıyı Başlatmak İçin: {$zoomStartUrl}";
                $mailer->send($teacher_email, 'Yeni Atanmış Toplantı (Başlatma Linkli)', $teacherMailText);
            }

            echo json_encode([
                'success' => true,
                'message' => 'Toplantı başarıyla oluşturuldu ve bilgilendirme e-postaları gönderildi.',
                'zoom_meeting' => $zoomResponse,
            ]);
        } catch (Exception $e) {
            error_log("Genel hata (createMeeting): " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Beklenmedik hata: ' . $e->getMessage()]);
        }

        break;
    case 'getDevelopmentPackageList':
        try {
            $stmt = $pdo->prepare("SELECT * FROM development_packages_lnp");
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($data)) {
                echo json_encode(['status' => 'success', 'data' => $data]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Kayıt bulunamadı.']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }

        break;
    case 'addDevelopmentPackage':
        try {
            $name = trim($_POST['addPackageName'] ?? '');
            $price = $_POST['addPackagePrice'] ?? '';
            $description = $_POST['addPackageDescription'] ?? '';


            if ($name === '' || $price === '') {
                throw new Exception('Geçersiz veya eksik parametreler.');
            }

            $stmt = $pdo->prepare("INSERT INTO development_packages_lnp  (name, price,description) VALUES (?, ?, ?)");
            $stmt->execute([$name, $price, $description]);

            echo json_encode(['status' => 'success', 'message' => 'Paket başarıyla eklendi.']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'updateDevelopmentPackage':
        try {
            $id = intval($_POST['updatePackageId'] ?? 0);
            $name = trim($_POST['updatePackageName'] ?? '');

            $price = $_POST['updatePackagePrice'] ?? '';

            $description = $_POST['updatePackageDescription'] ?? '';



            if ($id <= 0 || $name === '' || $price === '') {
                throw new Exception('Geçersiz veya eksik parametreler.');
            }

            $stmt = $pdo->prepare("UPDATE development_packages_lnp SET name = ?, price=?,description=? WHERE id = ?");
            $stmt->execute([$name,  $price, $description, $id]);

            echo json_encode(['status' => 'success', 'message' => 'Paket başarıyla güncellendi.']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'deleteDevelopmentPackage':
        try {
            $id = intval($_POST['id'] ?? 0);

            if ($id <= 0) {
                throw new Exception('Geçersiz ID.');
            }

            $stmt = $pdo->prepare("DELETE FROM development_packages_lnp WHERE id = ?");
            $stmt->execute([$id]);

            echo json_encode(['status' => 'success', 'message' => 'Paket başarıyla silindi.']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;
    case 'updateContent':
        try {
            $pdo->beginTransaction();

            $content_id     = $_POST['content_id'] ?? null;
            $photo          = $_FILES['photo'] ?? null;
            $avatar_remove  = $_POST['avatar_remove'] ?? null;
            $name           = $_POST['name'] ?? null;
            $short_desc     = $_POST['short_desc'] ?? null;
            $classes        = $_POST['classes'] ?? null;
            $lessons        = $_POST['lessons'] ?? null;
            $units          = $_POST['units'] ?? null;
            $topics         = $_POST['topics'] ?? null;
            $sub_topics     = $_POST['sub_topics'] ?? null;
            $mcontent       = $_POST['mcontent'] ?? null;

            // Tekli veya çoklu gönderimler için array'e çevir
            $video_urls     = $_POST['video_url'] ?? [];
            if (!is_array($video_urls)) $video_urls = [$video_urls];

            $wordWallUrls   = $_POST['wordWallUrls'] ?? [];
            $wordWallTitles = $_POST['wordWallTitles'] ?? [];
            if (!is_array($wordWallUrls)) $wordWallUrls = [$wordWallUrls];
            if (!is_array($wordWallTitles)) $wordWallTitles = [$wordWallTitles];

            $file_paths         = $_FILES['file_path'] ?? [];
            $fileDescriptions   = $_POST['file_descriptions'] ?? [];
            if (!is_array($fileDescriptions)) $fileDescriptions = [$fileDescriptions];

            // Fotoğraf işlemi
            $cover_img_path = null;
            if ($photo && $photo['error'] === 0) {
                $uploadDir = '../uploads/contents/';
                $filename = uniqid() . '_' . basename($photo['name']);
                $uploadPath = $uploadDir . $filename;
                if (move_uploaded_file($photo['tmp_name'], $uploadPath)) {
                    $cover_img_path = $uploadPath;
                }
            } elseif ($avatar_remove === '1') {
                $cover_img_path = null;
            } else {
                // Mevcut fotoğrafı koru
                $stmt = $pdo->prepare("SELECT cover_img FROM school_content_lnp WHERE id = ?");
                $stmt->execute([$content_id]);
                $cover_img_path = $stmt->fetchColumn();
            }

            // content_lnp güncelleme
            $updateStmt = $pdo->prepare("UPDATE school_content_lnp SET 
            title = ?, 
            summary = ?, 
            class_id = ?, 
            lesson_id = ?, 
            unit_id = ?, 
            topic_id = ?, 
            subtopic_id = ?, 
            cover_img = ?, 
            text_content = ? 
            WHERE id = ?");
            $updateStmt->execute([
                $name,
                $short_desc,
                $classes,
                $lessons,
                $units,
                $topics,
                $sub_topics,
                $cover_img_path,
                $mcontent,
                $content_id
            ]);

            // Video URL'leri ekleme (eski veriler siliniyor, yeni ekleniyor)
            $pdo->prepare("DELETE FROM school_content_videos_url WHERE school_content_id = ?")->execute([$content_id]);
            $insertVideoStmt = $pdo->prepare("INSERT INTO school_content_videos_url (video_url, school_content_id) VALUES (?, ?)");
            foreach ($video_urls as $url) {
                if (!empty($url)) {
                    $insertVideoStmt->execute([$url, $content_id]);
                }
            }

            // WordWall ekleme
            $pdo->prepare("DELETE FROM school_content_wordwall_lnp WHERE school_content_id = ?")->execute([$content_id]);
            $insertWordWallStmt = $pdo->prepare("INSERT INTO school_content_wordwall_lnp (school_content_id, wordwall_url, wordwall_title) VALUES (?, ?, ?)");
            foreach ($wordWallUrls as $index => $url) {
                $title = $wordWallTitles[$index] ?? '';
                if (!empty($url)) {
                    $insertWordWallStmt->execute([$content_id, $url, $title]);
                }
            }

            // Dosya yükleme ve ekleme
            $uploadDir = '../uploads/contents/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            $insertFileStmt = $pdo->prepare("INSERT INTO school_content_files_lnp (school_content_id, file_path, description) VALUES (?, ?, ?)");
            foreach ($file_paths['name'] ?? [] as $index => $name) {
                if ($file_paths['error'][$index] === 0) {
                    $tmpPath = $file_paths['tmp_name'][$index];
                    $filename = uniqid() . '_' . basename($name);
                    $uploadPath = $uploadDir . $filename;
                    if (move_uploaded_file($tmpPath, $uploadPath)) {
                        $description = $fileDescriptions[$index] ?? '';
                        $insertFileStmt->execute([$content_id, $uploadPath, $description]);
                    }
                }
            }

            $pdo->commit();
            echo json_encode(['status' => 'success', 'message' => 'İçerik başarıyla güncellendi.']);
        } catch (Exception $e) {
            $pdo->rollBack();
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;


    case 'deleteContentFile':
        $id = $_POST['id'] ?? null;
        try {


            if ($id) {
                // (Opsiyonel) dosyayı sunucudan silmek istersen burada path alıp unlink() yapabilirsin
                $stmt = $pdo->prepare("DELETE FROM school_content_files_lnp WHERE id = ?");
                $stmt->execute([$id]);
                echo json_encode(['status' => 'success', 'message' => 'Dosya silindi.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Dosya ID eksik.']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Dosya silinirken hata oluştu: ' . $e->getMessage()]);
        }
        break;
    case 'updateFileDescription':
        $id = $_POST['id'] ?? null;
        $description = trim($_POST['description'] ?? '');

        if (!$id) {
            echo json_encode(['status' => 'error', 'message' => 'Geçersiz dosya ID.']);
            exit;
        }

        $stmt = $pdo->prepare("UPDATE school_content_files_lnp SET description = ? WHERE id = ?");
        if ($stmt->execute([$description, $id])) {
            echo json_encode(['status' => 'success', 'message' => 'Açıklama güncellendi.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Güncelleme başarısız.']);
        }
        exit;
        break;
    case 'canli-video':


    case 'canli-video':
        $title   = $_POST['title'] ?? null;
        $date    = $_POST['date_time'] ?? null; // frontend'den gelen tarih
        $userId  = $_SESSION['id'] ?? null;     // oturum açan kullanıcı
        $classId = $_POST['class_id'] ?? null;  // Sınıf ID'si


        if (!$title || !$date || !$userId) {
            echo json_encode(['success' => false, 'message' => 'Eksik bilgi gönderildi.']);
            exit();
        }

        try {
            // Zoom meeting oluşturma için hazırlık
            require_once '../zoom/ZoomTokenManager.php';
            $zoom = new ZoomTokenManager();
            $access_token = $zoom->getAccessToken();
            $start_time = date('Y-m-d\TH:i:s', strtotime($date));
            $zoomUserId = 'me';

            $meeting_details = [
                'topic' => $title,
                'type' => 2,
                'start_time' => $start_time,
                'duration' => 60,
                'timezone' => 'Europe/Istanbul',
                'settings' => [
                    'host_video' => true,
                    'participant_video' => true,
                    'auto_recording' => 'cloud',
                ],
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://api.zoom.us/v2/users/{$zoomUserId}/meetings");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer $access_token",
                "Content-Type: application/json",
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($meeting_details));

            $response = curl_exec($ch);
            $err = curl_error($ch);
            curl_close($ch);

            if ($err) {
                error_log("Zoom API Hatası: " . $err);
                echo json_encode(['success' => false, 'message' => 'Zoom bağlantısı kurulamadı.', 'zoom_error' => $err]);
                exit();
            }

            $zoomResponse = json_decode($response, true);
            if (!isset($zoomResponse['join_url'], $zoomResponse['start_url'])) {
                error_log("Zoom Yanıtı Geçersiz: " . $response);
                echo json_encode(['success' => false, 'message' => 'Zoom toplantısı oluşturulamadı.', 'zoom_error' => $response]);
                exit();
            }

            $joinUrl = $zoomResponse['join_url'];
            $startUrl = $zoomResponse['start_url'];

            // meetings_lnp tablosuna kaydet
            $stmt = $pdo->prepare("
            INSERT INTO meetings_lnp 
            (organizer_id, description, meeting_date, zoom_join_url, zoom_start_url,class_id,role) 
            VALUES (?, ?, ?, ?, ?,?,?)
        ");
            $result = $stmt->execute([
                $userId,
                $title,
                $date,
                $joinUrl,
                $startUrl,
                $classId,
                $_SESSION['role']
            ]);

            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Canlı ders başarıyla oluşturuldu.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Veritabanına kayıt yapılamadı.']);
            }
        } catch (Exception $e) {
            error_log("Canlı Video Hatası: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Bir hata oluştu.', 'error' => $e->getMessage()]);
        }

        break;

    case 'canli-video-delete':
        $id = $_POST['meeting_id'] ?? null;

        if (!$id) {
            echo json_encode(['status' => 'error', 'message' => 'Geçersiz ID.']);
            exit();
        }

        try {
            $stmt = $pdo->prepare("DELETE FROM meetings_lnp WHERE id = ?");
            $result = $stmt->execute([$id]);

            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Canlı ders silindi.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Silme işlemi başarısız.']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Bir hata oluştu: ' . $e->getMessage()]);
        }
        break;
    case 'canli-video-update':
        $id = $_POST['meeting_id'] ?? null;
        $title = $_POST['title'] ?? null;
        $date = $_POST['date_time'] ?? null;
        $classId = $_POST['class_id'] ?? null;

        if (!$id || !$title || !$date) {
            echo json_encode(['status' => 'error', 'message' => 'Eksik bilgi gönderildi.']);
            exit();
        }

        try {
            $stmt = $pdo->prepare("UPDATE meetings_lnp SET description = ?, meeting_date = ?, class_id = ? WHERE id = ?");
            $result = $stmt->execute([$title, $date, $classId, $id]);

            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Canlı ders güncellendi.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Güncelleme işlemi başarısız.']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Bir hata oluştu: ' . $e->getMessage()]);
        }
        break;
    case 'changeStatusMainSchoolTopic':
        $id = $_POST['id'] ?? null;

        if (!$id) {
            echo json_encode(['status' => 'error', 'message' => 'Geçersiz parametreler.']);
            exit();
        }

        try {
            // Status'ü tersine çeviriyoruz (1->0, 0->1)
            $stmt = $pdo->prepare("
            UPDATE main_school_topics_lnp 
            SET status = CASE WHEN status = 1 THEN 0 ELSE 1 END 
            WHERE id = ?
        ");
            $result = $stmt->execute([$id]);

            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Durum güncellendi.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Güncelleme işlemi başarısız.']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Bir hata oluştu: ' . $e->getMessage()]);
        }
        break;


    case 'getMainSchoolTopics':
        try {
            $stmt = $pdo->prepare("SELECT * FROM main_school_topics_lnp");
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($data)) {
                echo json_encode(['status' => 'success', 'data' => $data]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Kayıt bulunamadı.']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }

        break;
    case 'changeStatusImportantWeek':
        $id = $_POST['id'] ?? null;

        if (!$id) {
            echo json_encode(['status' => 'error', 'message' => 'Geçersiz parametreler.']);
            exit();
        }

        try {
            // Status'ü tersine çeviriyoruz (1->0, 0->1)
            $stmt = $pdo->prepare("
            UPDATE important_weeks_lnp 
            SET status = CASE WHEN status = 1 THEN 0 ELSE 1 END 
            WHERE id = ?
        ");
            $result = $stmt->execute([$id]);

            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Durum güncellendi.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Güncelleme işlemi başarısız.']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Bir hata oluştu: ' . $e->getMessage()]);
        }
        break;
    case 'changeStatusCategoryTitle':
        $id = $_POST['id'] ?? null;

        if (!$id) {
            echo json_encode(['status' => 'error', 'message' => 'Geçersiz parametreler.']);
            exit();
        }

        try {
            // Status'ü tersine çeviriyoruz (1->0, 0->1)
            $stmt = $pdo->prepare("
            UPDATE category_titles_lnp 
            SET status = CASE WHEN status = 1 THEN 0 ELSE 1 END 
            WHERE id = ?
        ");
            $result = $stmt->execute([$id]);

            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Durum güncellendi.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Güncelleme işlemi başarısız.']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Bir hata oluştu: ' . $e->getMessage()]);
        }
        break;
    case 'changeStatusMainSchoolContent':
        $id = $_POST['id'] ?? null;

        if (!$id) {
            echo json_encode(['status' => 'error', 'message' => 'Geçersiz parametreler.']);
            exit();
        }

        try {
            // Status'ü tersine çeviriyoruz (1->0, 0->1)
            $stmt = $pdo->prepare("
            UPDATE main_school_content_lnp 
            SET status = CASE WHEN status = 1 THEN 0 ELSE 1 END 
            WHERE id = ?
        ");
            $result = $stmt->execute([$id]);

            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Durum güncellendi.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Güncelleme işlemi başarısız.']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Bir hata oluştu: ' . $e->getMessage()]);
        }
        break;
    case 'changeStatusMainSchoolLesson':
        $id = $_POST['id'] ?? null;

        if (!$id) {
            echo json_encode(['status' => 'error', 'message' => 'Geçersiz parametreler.']);
            exit();
        }

        try {
            // Status'ü tersine çeviriyoruz (1->0, 0->1)
            $stmt = $pdo->prepare("
            UPDATE main_school_lessons_lnp 
            SET status = CASE WHEN status = 1 THEN 0 ELSE 1 END 
            WHERE id = ?
        ");
            $result = $stmt->execute([$id]);

            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Durum güncellendi.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Güncelleme işlemi başarısız.']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Bir hata oluştu: ' . $e->getMessage()]);
        }
        break;
    case 'changeStatusMainSchoolUnit':
        $id = $_POST['id'] ?? null;

        if (!$id) {
            echo json_encode(['status' => 'error', 'message' => 'Geçersiz parametreler.']);
            exit();
        }

        try {
            // Status'ü tersine çeviriyoruz (1->0, 0->1)
            $stmt = $pdo->prepare("
            UPDATE main_school_units_lnp 
            SET status = CASE WHEN status = 1 THEN 0 ELSE 1 END 
            WHERE id = ?
        ");
            $result = $stmt->execute([$id]);

            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Durum güncellendi.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Güncelleme işlemi başarısız.']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Bir hata oluştu: ' . $e->getMessage()]);
        }
        break;

    case 'getTopStudents':
        $schoolId = $_GET['schoolId'] ?? null;

        if (!$schoolId) {
            echo json_encode(['status' => 'error', 'message' => 'Geçersiz parametreler.']);
            exit();
        }

        try {
            // PDO bağlantısı $pdo olarak varsayılıyor
            $sql = "
        SELECT 
            u.id AS student_id,
            u.username,
            u.name,
            u.surname,
            u.photo,
            c.name AS className,
            c.slug AS classSlug,
            u.active AS userActive,
            s.name AS schoolName,
            
            COUNT(DISTINCT sc.id) AS total_content_items,
            COUNT(DISTINCT scv.id) AS total_videos,
            SUM(CASE WHEN vd.duration > 0 AND vt.max_timestamp >= (vd.duration * 0.9) THEN 1 ELSE 0 END) AS completed_videos,
            
            COUNT(DISTINCT scf.id) AS total_files,
            SUM(CASE WHEN fd.user_id IS NOT NULL THEN 1 ELSE 0 END) AS downloaded_files,
            
            COUNT(DISTINCT scw.id) AS total_wordwalls,
            SUM(CASE WHEN wv.user_id IS NOT NULL THEN 1 ELSE 0 END) AS viewed_wordwalls,
            
            SUM(CASE WHEN cv.user_id IS NOT NULL THEN 1 ELSE 0 END) AS content_visits,
            
            CASE 
                WHEN (COUNT(DISTINCT scv.id) + COUNT(DISTINCT scf.id) + COUNT(DISTINCT scw.id) + COUNT(DISTINCT sc.id)) > 0
                THEN ROUND(
                    (
                        SUM(CASE WHEN vd.duration > 0 AND vt.max_timestamp >= (vd.duration * 0.9) THEN 1 ELSE 0 END)
                        + SUM(CASE WHEN fd.user_id IS NOT NULL THEN 1 ELSE 0 END)
                        + SUM(CASE WHEN wv.user_id IS NOT NULL THEN 1 ELSE 0 END)
                        + SUM(CASE WHEN cv.user_id IS NOT NULL THEN 1 ELSE 0 END)
                    ) * 100.0 /
                    (COUNT(DISTINCT scv.id) + COUNT(DISTINCT scf.id) + COUNT(DISTINCT scw.id) + COUNT(DISTINCT sc.id)),
                    3
                )
                ELSE 0
            END AS ana_score

        FROM users_lnp u
        INNER JOIN classes_lnp c ON u.class_id = c.id
        INNER JOIN schools_lnp s ON u.school_id = s.id
        LEFT JOIN school_content_lnp sc ON sc.active = 1
        LEFT JOIN content_visits cv ON sc.id = cv.content_id AND cv.user_id = u.id
        LEFT JOIN school_content_videos_url scv ON sc.id = scv.school_content_id
        LEFT JOIN video_durations vd ON scv.id = vd.video_id
        LEFT JOIN video_timestamp_lnp vt ON scv.id = vt.video_id AND vt.user_id = u.id
        LEFT JOIN school_content_files_lnp scf ON sc.id = scf.school_content_id
        LEFT JOIN file_downloads fd ON scf.id = fd.file_id AND fd.user_id = u.id
        LEFT JOIN school_content_wordwall_lnp scw ON sc.id = scw.school_content_id
        LEFT JOIN wordwall_views wv ON scw.id = wv.wordwall_id AND wv.user_id = u.id
        WHERE u.active = 1
          AND u.role IN (?, ?)
          AND u.school_id = ?
        GROUP BY u.id
        ORDER BY ana_score DESC
        LIMIT 5
        ";

            $stmt = $pdo->prepare($sql);
            $stmt->execute(["2", "10002", $schoolId]);
            $topStudents = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(['status' => 'success', 'data' => $topStudents]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Bir hata oluştu: ' . $e->getMessage()]);
        }
        break;

    case 'anaOkuluOgrenciAktar';

        try {
            if (!isset($_FILES['excelFile']) || $_FILES['excelFile']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('Dosya yükleme hatası. Lütfen tekrar deneyin.');
            }


            // Yükleme klasörü
            $uploadDir = __DIR__ . "/../uploads/ana-okulu-ogrenci/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Dosya adı ve hedef yol
            $fileName   = basename($_FILES['excelFile']['name']);
            $targetPath = $uploadDir . $fileName;

            // Dosyayı kalıcı klasöre taşı
            if (!move_uploaded_file($_FILES['excelFile']['tmp_name'], $targetPath)) {
                throw new Exception("Dosya yüklenirken hata oluştu!");
            }
            $price = $_POST['price'] ?? null;
            if ($price === null || !is_numeric($price)) {
                throw new Exception("Öğrenci başı ücret geçerli değil.");
            }
            // Artık buradan dosya yolunu alıyoruz
            $file = $targetPath;

            // Uzantı kontrolü
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            if (strtolower($fileExtension) !== 'csv') {
                throw new Exception('Lütfen geçerli bir CSV dosyası yükleyin.');
            }
            // Frontend’den gelen okul bilgileri
            $schoolId   = $_POST['school_id']   ?? 1;
            $schoolName = $_POST['schoolName']  ?? null;

            if (empty($schoolId) || empty($schoolName)) {
                throw new Exception("Okul bilgileri eksik gönderildi.");
            }

            // Dosyayı okuma modunda aç
            $handle = fopen($file, 'r');
            if ($handle === false) {
                throw new Exception('Dosya okunamadı.');
            }

            $successCount = 0;
            $rowNumber    = 1;

            // Transaksiyonu başlat
            $pdo->beginTransaction();

            // Öğrenci SQL
            $sqlStudent = "INSERT INTO users_lnp (
            name, surname, username, email, password, role, active, telephone, birth_date, gender, identity_id,
            address, district, postcode, city, class_id, school_id, school_name, parent_id,
            subscribed_at, subscribed_end, package_id
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmtStudentInsert = $pdo->prepare($sqlStudent);

            // Veli SQL
            $sqlParent = "INSERT INTO users_lnp (
            name, surname, username, password, role, active, school_id, school_name
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmtParentInsert = $pdo->prepare($sqlParent);

            // Paket SQL
            $sqlPackage = "SELECT id, subscription_period FROM packages_lnp WHERE name LIKE ? AND class_id = ?";
            $stmtPackage = $pdo->prepare($sqlPackage);

            // CSV başlık satırını atla
            fgetcsv($handle, 1000, ';');

            // Satır satır oku
            while (($data = fgetcsv($handle, 1000, ';')) !== false) {
                $rowNumber++;

                // Beklenen minimum sütun sayısı
                if (count($data) < 15) { // Artık paket bilgisi de olduğu için 15
                    $pdo->rollBack();
                    throw new Exception("Satır " . $rowNumber . ": Yetersiz veri. Dosya formatı bozuk.");
                }

                // CSV indexleri (username yok → sola kaydı)
                $adi             = trim($data[0]);
                $soyadi          = trim($data[1]);
                $tcNo            = trim($data[2]);
                $cinsiyet        = trim($data[3]);
                $dogumTarihi     = trim($data[4]);
                $ePosta          = trim($data[5]);
                $adres           = trim($data[8]);
                $ilce            = trim($data[9]);
                $postaKodu       = trim($data[10]);
                $sehir           = trim($data[11]);
                $telefonNumarasi = trim($data[12]);
                $sinifMetin      = trim($data[13]);
                $paketMetin      = trim($data[14]);

                // Kullanıcı adı türet (TC No)
                $kullaniciAdi = $tcNo;

                // Validasyon
                $missingFields = [];
                if (empty($adi))         $missingFields[] = 'Adı';
                if (empty($soyadi))      $missingFields[] = 'Soyadı';
                if (empty($tcNo))        $missingFields[] = 'TC No';
                if (empty($sinifMetin))  $missingFields[] = 'Sınıf';
                if (empty($paketMetin))  $missingFields[] = 'Paket Adı';

                if (!empty($missingFields)) {
                    $pdo->rollBack();
                    throw new Exception("Satır " . $rowNumber . ": Eksik zorunlu veri: " . implode(", ", $missingFields));
                }

                // Sınıf dönüştürme
                $sinifId = 0;
                if (strpos($sinifMetin, '3-4') !== false) {
                    $sinifId = 10;
                } elseif (strpos($sinifMetin, '4-5') !== false) {
                    $sinifId = 11;
                } elseif (strpos($sinifMetin, '5-6') !== false) {
                    $sinifId = 12;
                }

                if ($sinifId === 0) {
                    $pdo->rollBack();
                    throw new Exception("Satır $rowNumber: Sınıf bilgisi geçersiz.");
                }

                if (!is_numeric($tcNo) || strlen($tcNo) !== 11) {
                    $pdo->rollBack();
                    throw new Exception("Satır $rowNumber: TC Kimlik Numarası geçersiz ($tcNo).");
                }

                if (!filter_var($ePosta, FILTER_VALIDATE_EMAIL)) {
                    $pdo->rollBack();
                    throw new Exception("Satır $rowNumber: E-posta adresi geçersiz ($ePosta).");
                }

                // TC kontrolü
                $stmtCheck = $pdo->prepare("SELECT id FROM users_lnp WHERE identity_id = ? LIMIT 1");
                $stmtCheck->execute([$tcNo]);
                if ($stmtCheck->fetch()) {
                    $pdo->rollBack();
                    throw new Exception("Satır $rowNumber: TC ($tcNo) zaten kayıtlı.");
                }

                // Doğum tarihi
                try {
                    $dogumTarihi = (new DateTime($dogumTarihi))->format('Y-m-d');
                } catch (Exception $e) {
                    $pdo->rollBack();
                    throw new Exception("Satır $rowNumber: Doğum tarihi hatalı ($dogumTarihi).");
                }

                // Parola (TC son 6 hane)
                $password = password_hash(substr((string)$tcNo, -6), PASSWORD_DEFAULT);

                // Paket bilgilerini sorgula
                $stmtPackage->execute(["$paketMetin%", $sinifId]);
                $packageInfo = $stmtPackage->fetch(PDO::FETCH_ASSOC);

                if (!$packageInfo) {
                    $pdo->rollBack();
                    throw new Exception("Satır $rowNumber: '$paketMetin' paketi bulunamadı veya bu sınıfa ait değil.");
                }

                $packageId = $packageInfo['id'];
                $subscriptionPeriod = $packageInfo['subscription_period'];

                // Abone başlangıç ve bitiş tarihlerini hesapla
                $subscribedAt = date('Y-m-d H:i:s');
                $subscribedEnd = (new DateTime($subscribedAt))->modify("+$subscriptionPeriod months")->format('Y-m-d H:i:s');

                // 1. Veli ekle
                $parentUsername = $kullaniciAdi . '-veli';
                $resultParent = $stmtParentInsert->execute([
                    $adi,
                    $soyadi,
                    $parentUsername,
                    $password,
                    '10005', // veli rolü
                    '1',     // aktif
                    $schoolId,
                    $schoolName
                ]);

                if (!$resultParent) {
                    $pdo->rollBack();
                    throw new Exception("Satır $rowNumber: Veli eklenirken hata.");
                }
                $parentId = $pdo->lastInsertId();

                // 2. Öğrenci ekle
                $resultStudent = $stmtStudentInsert->execute([
                    $adi,
                    $soyadi,
                    $kullaniciAdi,
                    $ePosta,
                    $password,
                    '10002', // öğrenci rolü
                    '1',
                    $telefonNumarasi,
                    $dogumTarihi,
                    $cinsiyet,
                    $tcNo,
                    $adres,
                    $ilce,
                    $postaKodu,
                    $sehir,
                    $sinifId,
                    $schoolId,
                    $schoolName,
                    $parentId,
                    $subscribedAt,
                    $subscribedEnd,
                    $packageId
                ]);

                $studentId = $pdo->lastInsertId();

                // Frontend’den gelen price
                $price = $_POST['price'] ?? null;
                if ($price === null || !is_numeric($price)) {
                    $pdo->rollBack();
                    throw new Exception("Öğrenci başı ücret geçerli değil.");
                }
                $price = (float)$price;

                // KDV ve pay_amount hesapla
                $stmtSettings = $pdo->prepare("SELECT tax_rate FROM settings_lnp LIMIT 1");
                $stmtSettings->execute();
                $settings = $stmtSettings->fetch(PDO::FETCH_ASSOC);

                if (!$settings || !isset($settings['tax_rate'])) {
                    throw new Exception("KDV oranı ayarlardan alınamadı.");
                }

                $kdv_percent = (float)$settings['tax_rate'];
                $kdv_amount  = round($price * $kdv_percent / 100, 2);
                $pay_amount  = round($price * (100 - $kdv_percent) / 100, 2);

                // package_payment_lnp tablosuna ekle
                $sqlPayment = "INSERT INTO package_payments_lnp 
    (user_id, pack_id, kdv_percent, payment_type, payment_status, kdv_amount, pay_amount, created_at) 
    VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";

                $stmtPayment = $pdo->prepare($sqlPayment);

                $resultPayment = $stmtPayment->execute([
                    $studentId,
                    $packageId,
                    $kdv_percent,
                    1,
                    2,
                    $kdv_amount,
                    $pay_amount
                ]);

                if (!$resultPayment) {
                    $pdo->rollBack();
                    throw new Exception("Satır $rowNumber: Ödeme bilgisi eklenirken hata oluştu.");
                }

                $successCount++;
            }

            fclose($handle);
            $pdo->commit();

            echo json_encode([
                'status'  => 'success',
                'message' => "$successCount öğrenci ve velisi başarıyla içe aktarıldı."
            ]);
        } catch (Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            echo json_encode([
                'status'  => 'error',
                'message' => 'Aktarım işlemi başarısız. ' . $e->getMessage()
            ]);
        }
        break;
    case 'addTodayWord':
        try {
            // Gerekli alanları kontrol et
            $required_fields = ['word', 'meaning', 'classes', 'start_date', 'end_date'];
            foreach ($required_fields as $field) {
                if (empty($_POST[$field])) {
                    echo json_encode(['success' => false, 'message' => 'Tüm zorunlu alanları doldurunuz!']);
                    exit;
                }
            }

            // Verileri al
            $word = trim($_POST['word']);
            $meaning = trim($_POST['meaning']);
            $classes = $_POST['classes']; // Array olarak gelecek
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            $status = $_POST['status'];
            $school_id = $_SESSION['school_id'] ?? 1; // Session'dan school_id al veya default 1

            // Sınıf ID'lerini string formatına çevir (noktalı virgül ile birleştir)
            $class_ids = is_array($classes) ? implode(';', $classes) : $classes;

            // Görsel yükleme işlemi
            $image_path = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = '../uploads/words/';

                // Upload dizini yoksa oluştur
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                // Dosya bilgilerini al
                $file_name = $_FILES['image']['name'];
                $file_tmp = $_FILES['image']['tmp_name'];
                $file_size = $_FILES['image']['size'];
                $file_error = $_FILES['image']['error'];

                // Dosya uzantısı kontrolü
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                if (!in_array($file_ext, $allowed_ext)) {
                    echo json_encode(['success' => false, 'message' => 'Sadece JPG, JPEG, PNG, GIF ve WEBP formatları kabul edilir!']);
                    exit;
                }

                // Dosya boyutu kontrolü (max 5MB)
                if ($file_size > 5 * 1024 * 1024) {
                    echo json_encode(['success' => false, 'message' => 'Dosya boyutu 5MB\'dan küçük olmalıdır!']);
                    exit;
                }

                // Benzersiz dosya adı oluştur
                $new_file_name = uniqid('word_', true) . '.' . $file_ext;
                $upload_path = $upload_dir . $new_file_name;

                // Dosyayı yükle
                if (move_uploaded_file($file_tmp, $upload_path)) {
                    $image_path = 'uploads/words/' . $new_file_name;
                } else {
                    echo json_encode(['success' => false, 'message' => 'Dosya yüklenirken hata oluştu!']);
                    exit;
                }
            }

            // Veritabanına ekleme işlemi


            $sql = "INSERT INTO todays_word 
            (word, body, school_id, class_id, start_date, end_date, is_active, image) 
            VALUES 
            (:word, :body, :school_id, :class_id, :start_date, :end_date, :is_active, :image)";

            $stmt = $pdo->prepare($sql);

            $result = $stmt->execute([
                ':word' => $word,
                ':body' => $meaning,
                ':school_id' => $school_id,
                ':class_id' => $class_ids,
                ':start_date' => $start_date,
                ':end_date' => $end_date,
                ':is_active' => $status,
                ':image' => $image_path
            ]);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Kelime başarıyla eklendi!',
                    'id' => $pdo->lastInsertId()
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Kelime eklenirken bir hata oluştu!'
                ]);
            }
        } catch (PDOException $e) {
            error_log("Database error in add-word.php: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Veritabanı hatası: ' . $e->getMessage()
            ]);
        } catch (Exception $e) {
            error_log("General error in add-word.php: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Bir hata oluştu: ' . $e->getMessage()
            ]);
        }

        break;
    case 'deleteTodayWord':
        $word_id = (int)$_POST['id'];

        try {


            // Önce kelimenin var olup olmadığını kontrol et
            $check_sql = "SELECT id, image FROM todays_word WHERE id = :id";
            $check_stmt = $pdo->prepare($check_sql);
            $check_stmt->execute([':id' => $word_id]);
            $word = $check_stmt->fetch(PDO::FETCH_ASSOC);

            if (!$word) {
                echo json_encode(['success' => false, 'message' => 'Kelime bulunamadı!']);
                exit;
            }

            // Kelimeyi sil
            $delete_sql = "DELETE FROM todays_word WHERE id = :id";
            $delete_stmt = $pdo->prepare($delete_sql);
            $result = $delete_stmt->execute([':id' => $word_id]);

            if ($result) {
                // Eğer kelimenin görseli varsa, dosyayı da sil
                if (!empty($word['image'])) {
                    $image_path = '../' . $word['image'];
                    if (file_exists($image_path)) {
                        unlink($image_path);
                    }
                }

                echo json_encode([
                    'success' => true,
                    'message' => 'Kelime başarıyla silindi!'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Kelime silinirken bir hata oluştu!'
                ]);
            }
        } catch (PDOException $e) {
            error_log("Database error in delete-word.php: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Veritabanı hatası: ' . $e->getMessage()
            ]);
        } catch (Exception $e) {
            error_log("General error in delete-word.php: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Bir hata oluştu: ' . $e->getMessage()
            ]);
        }
        break;
    case 'updateTodayWord':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Geçersiz istek methodu!']);
            exit;
        }

        // Yetki kontrolü
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
            echo json_encode(['success' => false, 'message' => 'Yetkiniz bulunmamaktadır!']);
            exit;
        }

        // Gerekli alanları kontrol et
        if (empty($_POST['id'])) {
            echo json_encode(['success' => false, 'message' => 'Kelime ID belirtilmedi!']);
            exit;
        }

        $required_fields = ['word', 'meaning', 'classes', 'start_date', 'end_date'];
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                echo json_encode(['success' => false, 'message' => 'Tüm zorunlu alanları doldurunuz!']);
                exit;
            }
        }

        try {
            // Verileri al
            $id = (int)$_POST['id'];
            $word = trim($_POST['word']);
            $meaning = trim($_POST['meaning']);
            $classes = $_POST['classes']; // Array olarak gelecek
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            $status = $_POST['status'];

            // Sınıf ID'lerini string formatına çevir (noktalı virgül ile birleştir)
            $class_ids = is_array($classes) ? implode(';', $classes) : $classes;

            $dbh = new Dbh();
            $pdo = $dbh->connect();

            // Önce kelimenin var olup olmadığını kontrol et
            $check_sql = "SELECT id, image FROM todays_word WHERE id = :id";
            $check_stmt = $pdo->prepare($check_sql);
            $check_stmt->execute([':id' => $id]);
            $existing_word = $check_stmt->fetch(PDO::FETCH_ASSOC);

            if (!$existing_word) {
                echo json_encode(['success' => false, 'message' => 'Güncellenecek kelime bulunamadı!']);
                exit;
            }

            // Görsel yükleme işlemi
            $image_path = $existing_word['image']; // Mevcut görseli koru

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = '../uploads/words/';

                // Upload dizini yoksa oluştur
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                // Dosya bilgilerini al
                $file_name = $_FILES['image']['name'];
                $file_tmp = $_FILES['image']['tmp_name'];
                $file_size = $_FILES['image']['size'];
                $file_error = $_FILES['image']['error'];

                // Dosya uzantısı kontrolü
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                if (!in_array($file_ext, $allowed_ext)) {
                    echo json_encode(['success' => false, 'message' => 'Sadece JPG, JPEG, PNG, GIF ve WEBP formatları kabul edilir!']);
                    exit;
                }

                // Dosya boyutu kontrolü (max 5MB)
                if ($file_size > 5 * 1024 * 1024) {
                    echo json_encode(['success' => false, 'message' => 'Dosya boyutu 5MB\'dan küçük olmalıdır!']);
                    exit;
                }

                // Eski görsel varsa sil
                if (!empty($existing_word['image'])) {
                    $old_image_path = '../' . $existing_word['image'];
                    if (file_exists($old_image_path)) {
                        unlink($old_image_path);
                    }
                }

                // Benzersiz dosya adı oluştur
                $new_file_name = uniqid('word_', true) . '.' . $file_ext;
                $upload_path = $upload_dir . $new_file_name;

                // Dosyayı yükle
                if (move_uploaded_file($file_tmp, $upload_path)) {
                    $image_path = 'uploads/words/' . $new_file_name;
                } else {
                    echo json_encode(['success' => false, 'message' => 'Dosya yüklenirken hata oluştu!']);
                    exit;
                }
            }

            // Güncelleme işlemi
            $sql = "UPDATE todays_word 
            SET word = :word, 
                body = :body, 
                class_id = :class_id, 
                start_date = :start_date, 
                end_date = :end_date, 
                is_active = :is_active, 
                image = :image 
            WHERE id = :id";

            $stmt = $pdo->prepare($sql);

            $result = $stmt->execute([
                ':word' => $word,
                ':body' => $meaning,
                ':class_id' => $class_ids,
                ':start_date' => $start_date,
                ':end_date' => $end_date,
                ':is_active' => $status,
                ':image' => $image_path,
                ':id' => $id
            ]);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Kelime başarıyla güncellendi!'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Kelime güncellenirken bir hata oluştu!'
                ]);
            }
        } catch (PDOException $e) {
            error_log("Database error in update-word.php: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Veritabanı hatası: ' . $e->getMessage()
            ]);
        } catch (Exception $e) {
            error_log("General error in update-word.php: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Bir hata oluştu: ' . $e->getMessage()
            ]);
        }
        break;

    /* Blog */


    case 'addBlog':
        try {
            // Gerekli alanları kontrol et
            $required_fields = ['title', 'content'];
            foreach ($required_fields as $field) {
                if (empty($_POST[$field])) {
                    echo json_encode(['success' => false, 'message' => 'Tüm zorunlu alanları doldurunuz!']);
                    exit;
                }
            }

            // Verileri al
            $title = trim($_POST['title']);
            $content = trim($_POST['content']);
            $status = $_POST['status'];
            $add_by = $_SESSION['id'];
            // Görsel yükleme işlemi
            $image_path = '';
            $pdf_path = '';
            $pdf_image_path = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = '../uploads/blog/';

                // Upload dizini yoksa oluştur
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                // Dosya bilgilerini al
                $file_name = $_FILES['image']['name'];
                $file_tmp = $_FILES['image']['tmp_name'];
                $file_size = $_FILES['image']['size'];
                $file_error = $_FILES['image']['error'];

                // Dosya uzantısı kontrolü
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                if (!in_array($file_ext, $allowed_ext)) {
                    echo json_encode(['success' => false, 'message' => 'Sadece JPG, JPEG, PNG, GIF ve WEBP formatları kabul edilir!']);
                    exit;
                }

                // Dosya boyutu kontrolü (max 5MB)
                if ($file_size > 5 * 1024 * 1024) {
                    echo json_encode(['success' => false, 'message' => 'Dosya boyutu 5MB\'dan küçük olmalıdır!']);
                    exit;
                }

                // Benzersiz dosya adı oluştur
                $new_file_name = uniqid('blog_', true) . '.' . $file_ext;
                $upload_path = $upload_dir . $new_file_name;

                // Dosyayı yükle
                if (move_uploaded_file($file_tmp, $upload_path)) {
                    $image_path = 'uploads/blog/' . $new_file_name;
                } else {
                    echo json_encode(['success' => false, 'message' => 'Dosya yüklenirken hata oluştu!']);
                    exit;
                }
            }

            if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = '../uploads/blog/';

                // Upload dizini yoksa oluştur
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                // Dosya bilgilerini al
                $file_name = $_FILES['pdf']['name'];
                $file_tmp = $_FILES['pdf']['tmp_name'];
                $file_size = $_FILES['pdf']['size'];
                $file_error = $_FILES['pdf']['error'];

                // Dosya uzantısı kontrolü
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                $allowed_ext = ['pdf'];

                if (!in_array($file_ext, $allowed_ext)) {
                    echo json_encode(['success' => false, 'message' => 'Sadece PDF formatları kabul edilir!']);
                    exit;
                }

                // Dosya boyutu kontrolü (max 5MB)
                if ($file_size > 5 * 1024 * 1024) {
                    echo json_encode(['success' => false, 'message' => 'Dosya boyutu 5MB\'dan küçük olmalıdır!']);
                    exit;
                }

                // Benzersiz dosya adı oluştur
                $new_file_name = uniqid('blog_', true) . '.' . $file_ext;
                $upload_path = $upload_dir . $new_file_name;

                // Dosyayı yükle
                if (move_uploaded_file($file_tmp, $upload_path)) {
                    $pdf_path = 'uploads/blog/' . $new_file_name;
                } else {
                    echo json_encode(['success' => false, 'message' => 'Dosya yüklenirken hata oluştu!']);
                    exit;
                }
            }

            if (isset($_FILES['pdf_image']) && $_FILES['pdf_image']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = '../uploads/blog/';

                // Upload dizini yoksa oluştur
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                // Dosya bilgilerini al
                $file_name = $_FILES['pdf_image']['name'];
                $file_tmp = $_FILES['pdf_image']['tmp_name'];
                $file_size = $_FILES['pdf_image']['size'];
                $file_error = $_FILES['pdf_image']['error'];

                // Dosya uzantısı kontrolü
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                if (!in_array($file_ext, $allowed_ext)) {
                    echo json_encode(['success' => false, 'message' => 'Sadece JPG, JPEG, PNG, GIF ve WEBP formatları kabul edilir!']);
                    exit;
                }

                // Dosya boyutu kontrolü (max 5MB)
                if ($file_size > 5 * 1024 * 1024) {
                    echo json_encode(['success' => false, 'message' => 'Dosya boyutu 5MB\'dan küçük olmalıdır!']);
                    exit;
                }

                // Benzersiz dosya adı oluştur
                $new_file_name = uniqid('blog_', true) . '.' . $file_ext;
                $upload_path = $upload_dir . $new_file_name;

                // Dosyayı yükle
                if (move_uploaded_file($file_tmp, $upload_path)) {
                    $pdf_image_path = 'uploads/blog/' . $new_file_name;
                } else {
                    echo json_encode(['success' => false, 'message' => 'Dosya yüklenirken hata oluştu!']);
                    exit;
                }
            }

            // Veritabanına ekleme işlemi


            $sql = "INSERT INTO psikoloji_blog_lnp 
            (title, content, add_by, image, is_active, pdf_path, pdf_image) 
            VALUES 
            (:title, :content, :add_by, :image, :is_active, :pdf_path, :pdf_image)";

            $stmt = $pdo->prepare($sql);

            $result = $stmt->execute([
                ':title' => $title,
                ':content' => $content,
                ':add_by' => $add_by,
                ':image' => $image_path,
                ':is_active' => $status,
                ':pdf_path' => $pdf_path,
                ':pdf_image' => $pdf_image_path
            ]);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Yazı başarıyla eklendi!',
                    'id' => $pdo->lastInsertId()
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Yazı eklenirken bir hata oluştu!'
                ]);
            }
        } catch (PDOException $e) {
            error_log("Database error in add-word.php: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Veritabanı hatası: ' . $e->getMessage()
            ]);
        } catch (Exception $e) {
            error_log("General error in add-word.php: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Bir hata oluştu: ' . $e->getMessage()
            ]);
        }

        break;

    case 'deleteBlog':
        $blog_id = (int)$_POST['id'];

        try {


            // Önce başlığın var olup olmadığını kontrol et
            $check_sql = "SELECT id, image, pdf_path, pdf_image FROM psikoloji_blog_lnp WHERE id = :id";
            $check_stmt = $pdo->prepare($check_sql);
            $check_stmt->execute([':id' => $blog_id]);
            $word = $check_stmt->fetch(PDO::FETCH_ASSOC);

            if (!$word) {
                echo json_encode(['success' => false, 'message' => 'Yazı bulunamadı!']);
                exit;
            }

            // Yazıyı sil
            $delete_sql = "DELETE FROM psikoloji_blog_lnp WHERE id = :id";
            $delete_stmt = $pdo->prepare($delete_sql);
            $result = $delete_stmt->execute([':id' => $blog_id]);

            if ($result) {
                // Eğer yazının görseli varsa, dosyayı da sil
                if (!empty($word['image'])) {
                    $image_path = '../' . $word['image'];
                    if (file_exists($image_path)) {
                        unlink($image_path);
                    }
                }

                // Eğer yazının pdf'si varsa, dosyayı da sil
                if (!empty($word['pdf_path'])) {
                    $pdf_path = '../' . $word['pdf_path'];
                    if (file_exists($pdf_path)) {
                        unlink($pdf_path);
                    }
                }

                // Eğer yazının pdf'si varsa, dosyayı da sil
                if (!empty($word['pdf_image'])) {
                    $pdf_image_path = '../' . $word['pdf_image'];
                    if (file_exists($pdf_image_path)) {
                        unlink($pdf_image_path);
                    }
                }

                echo json_encode([
                    'success' => true,
                    'message' => 'Yazı başarıyla silindi!'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Yazı silinirken bir hata oluştu!'
                ]);
            }
        } catch (PDOException $e) {
            error_log("Database error in delete-word.php: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Veritabanı hatası: ' . $e->getMessage()
            ]);
        } catch (Exception $e) {
            error_log("General error in delete-word.php: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Bir hata oluştu: ' . $e->getMessage()
            ]);
        }
        break;


    case 'updateBlog':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Geçersiz istek methodu!']);
            exit;
        }

        // Yetki kontrolü
        if (!isset($_SESSION['role']) && ($_SESSION['role'] != 1  || $_SESSION['role'] != 20001)) {
            echo json_encode(['success' => false, 'message' => 'Yetkiniz bulunmamaktadır!']);
            exit;
        }

        // Gerekli alanları kontrol et
        if (empty($_POST['id'])) {
            echo json_encode(['success' => false, 'message' => 'Kelime ID belirtilmedi!']);
            exit;
        }

        $required_fields = ['title', 'content'];
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                echo json_encode(['success' => false, 'message' => 'Tüm zorunlu alanları doldurunuz!']);
                exit;
            }
        }

        try {
            // Verileri al
            $id = (int)$_POST['id'];
            $title = trim($_POST['title']);
            $content = trim($_POST['content']);
            $status = $_POST['status'];

            $dbh = new Dbh();
            $pdo = $dbh->connect();

            // Önce yazının var olup olmadığını kontrol et
            $check_sql = "SELECT id, image, pdf_path, pdf_image FROM psikoloji_blog_lnp WHERE id = :id";
            $check_stmt = $pdo->prepare($check_sql);
            $check_stmt->execute([':id' => $id]);
            $existing_word = $check_stmt->fetch(PDO::FETCH_ASSOC);

            if (!$existing_word) {
                echo json_encode(['success' => false, 'message' => 'Güncellenecek yazı bulunamadı!']);
                exit;
            }

            // Görsel yükleme işlemi
            $image_path = $existing_word['image']; // Mevcut görseli koru

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = '../uploads/blog/';

                // Upload dizini yoksa oluştur
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                // Dosya bilgilerini al
                $file_name = $_FILES['image']['name'];
                $file_tmp = $_FILES['image']['tmp_name'];
                $file_size = $_FILES['image']['size'];
                $file_error = $_FILES['image']['error'];

                // Dosya uzantısı kontrolü
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                if (!in_array($file_ext, $allowed_ext)) {
                    echo json_encode(['success' => false, 'message' => 'Sadece JPG, JPEG, PNG, GIF ve WEBP formatları kabul edilir!']);
                    exit;
                }

                // Dosya boyutu kontrolü (max 5MB)
                if ($file_size > 5 * 1024 * 1024) {
                    echo json_encode(['success' => false, 'message' => 'Dosya boyutu 5MB\'dan küçük olmalıdır!']);
                    exit;
                }

                // Eski görsel varsa sil
                if (!empty($existing_word['image'])) {
                    $old_image_path =  '../' . $existing_word['image'];
                    if (file_exists($old_image_path)) {
                        unlink($old_image_path);
                    }
                }

                // Benzersiz dosya adı oluştur
                $new_file_name = uniqid('blog_', true) . '.' . $file_ext;
                $upload_path = $upload_dir . $new_file_name;

                // Dosyayı yükle
                if (move_uploaded_file($file_tmp, $upload_path)) {
                    $image_path = 'uploads/blog/' . $new_file_name;
                } else {
                    echo json_encode(['success' => false, 'message' => 'Dosya yüklenirken hata oluştu!']);
                    exit;
                }
            }

            // PDF yükleme işlemi
            $pdf_path = $existing_word['pdf_path']; // Mevcut görseli koru

            if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = '../uploads/blog/';

                // Upload dizini yoksa oluştur
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                // Dosya bilgilerini al
                $file_name = $_FILES['pdf']['name'];
                $file_tmp = $_FILES['pdf']['tmp_name'];
                $file_size = $_FILES['pdf']['size'];
                $file_error = $_FILES['pdf']['error'];

                // Dosya uzantısı kontrolü
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                $allowed_ext = ['pdf'];

                if (!in_array($file_ext, $allowed_ext)) {
                    echo json_encode(['success' => false, 'message' => 'Sadece PDF formatları kabul edilir!']);
                    exit;
                }

                // Dosya boyutu kontrolü (max 5MB)
                if ($file_size > 5 * 1024 * 1024) {
                    echo json_encode(['success' => false, 'message' => 'Dosya boyutu 5MB\'dan küçük olmalıdır!']);
                    exit;
                }

                // Eski görsel varsa sil
                if (!empty($existing_word['pdf_path'])) {
                    $old_image_path = '../' . $existing_word['pdf_path'];
                    if (file_exists($old_image_path)) {
                        unlink($old_image_path);
                    }
                }

                // Benzersiz dosya adı oluştur
                $new_file_name = uniqid('blog_', true) . '.' . $file_ext;
                $upload_path = $upload_dir . $new_file_name;

                // Dosyayı yükle
                if (move_uploaded_file($file_tmp, $upload_path)) {
                    $pdf_path = 'uploads/blog/' . $new_file_name;
                } else {
                    echo json_encode(['success' => false, 'message' => 'Dosya yüklenirken hata oluştu!']);
                    exit;
                }
            }

            // PDF görseli yükleme işlemi
            $pdf_image_path = $existing_word['pdf_image']; // Mevcut görseli koru

            if (isset($_FILES['pdf_image']) && $_FILES['pdf_image']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = '../uploads/blog/';

                // Upload dizini yoksa oluştur
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                // Dosya bilgilerini al
                $file_name = $_FILES['pdf_image']['name'];
                $file_tmp = $_FILES['pdf_image']['tmp_name'];
                $file_size = $_FILES['pdf_image']['size'];
                $file_error = $_FILES['pdf_image']['error'];

                // Dosya uzantısı kontrolü
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                if (!in_array($file_ext, $allowed_ext)) {
                    echo json_encode(['success' => false, 'message' => 'Sadece JPG, JPEG, PNG, GIF ve WEBP formatları kabul edilir!']);
                    exit;
                }

                // Dosya boyutu kontrolü (max 5MB)
                if ($file_size > 5 * 1024 * 1024) {
                    echo json_encode(['success' => false, 'message' => 'Dosya boyutu 5MB\'dan küçük olmalıdır!']);
                    exit;
                }

                // Eski görsel varsa sil
                if (!empty($existing_word['pdf_image'])) {
                    $old_image_path = '../' . $existing_word['pdf_image'];
                    if (file_exists($old_image_path)) {
                        unlink($old_image_path);
                    }
                }

                // Benzersiz dosya adı oluştur
                $new_file_name = uniqid('blog_', true) . '.' . $file_ext;
                $upload_path = $upload_dir . $new_file_name;

                // Dosyayı yükle
                if (move_uploaded_file($file_tmp, $upload_path)) {
                    $pdf_image_path = 'uploads/blog/' . $new_file_name;
                } else {
                    echo json_encode(['success' => false, 'message' => 'Dosya yüklenirken hata oluştu!']);
                    exit;
                }
            }

            // Güncelleme işlemi
            $sql = "UPDATE psikoloji_blog_lnp 
            SET title = :title, 
                content = :content, 
                is_active = :is_active, 
                image = :image, 
                pdf_path = :pdf_path, 
                pdf_image = :pdf_image_path  
            WHERE id = :id";

            $stmt = $pdo->prepare($sql);

            $result = $stmt->execute([
                ':title' => $title,
                ':content' => $content,
                ':is_active' => $status,
                ':image' => $image_path,
                ':id' => $id,
                ':pdf_path' => $pdf_path,
                ':pdf_image_path' => $pdf_image_path
            ]);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Yazı başarıyla güncellendi!'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Yazı güncellenirken bir hata oluştu!'
                ]);
            }
        } catch (PDOException $e) {
            error_log("Database error in update-word.php: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Veritabanı hatası: ' . $e->getMessage()
            ]);
        } catch (Exception $e) {
            error_log("General error in update-word.php: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Bir hata oluştu: ' . $e->getMessage()
            ]);
        }
        break;


    /* Blog Son */


    case 'deleteDoyouKnow':
        $word_id = (int)$_POST['id'];

        try {


            // Önce kelimenin var olup olmadığını kontrol et
            $check_sql = "SELECT id, image FROM doyouknow WHERE id = :id";
            $check_stmt = $pdo->prepare($check_sql);
            $check_stmt->execute([':id' => $word_id]);
            $word = $check_stmt->fetch(PDO::FETCH_ASSOC);

            if (!$word) {
                echo json_encode(['success' => false, 'message' => 'Bilgi bulunamadı!']);
                exit;
            }

            // Kelimeyi sil
            $delete_sql = "DELETE FROM doyouknow WHERE id = :id";
            $delete_stmt = $pdo->prepare($delete_sql);
            $result = $delete_stmt->execute([':id' => $word_id]);

            if ($result) {
                // Eğer kelimenin görseli varsa, dosyayı da sil
                if (!empty($word['image'])) {
                    $image_path = '../' . $word['image'];
                    if (file_exists($image_path)) {
                        unlink($image_path);
                    }
                }

                echo json_encode([
                    'success' => true,
                    'message' => 'Bilgi başarıyla silindi!'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Bilgi silinirken bir hata oluştu!'
                ]);
            }
        } catch (PDOException $e) {
            error_log("Database error in delete-word.php: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Veritabanı hatası: ' . $e->getMessage()
            ]);
        } catch (Exception $e) {
            error_log("General error in delete-word.php: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Bir hata oluştu: ' . $e->getMessage()
            ]);
        }
        break;
    case 'updateDoyouKnow':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Geçersiz istek methodu!']);
            exit;
        }

        // Yetki kontrolü
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
            echo json_encode(['success' => false, 'message' => 'Yetkiniz bulunmamaktadır!']);
            exit;
        }

        // Gerekli alanları kontrol et
        if (empty($_POST['id'])) {
            echo json_encode(['success' => false, 'message' => 'Bilgi ID belirtilmedi!']);
            exit;
        }

        $required_fields = ['classes', 'start_date', 'end_date'];
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                echo json_encode(['success' => false, 'message' => 'Tüm zorunlu alanları doldurunuz!']);
                exit;
            }
        }

        try {
            // Verileri al
            $id = (int)$_POST['id'];
            $meaning = trim($_POST['meaning']);
            $classes = $_POST['classes']; // Array olarak gelecek
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            $status = $_POST['status'];

            // Sınıf ID'lerini string formatına çevir (noktalı virgül ile birleştir)
            $class_ids = is_array($classes) ? implode(';', $classes) : $classes;

            $dbh = new Dbh();
            $pdo = $dbh->connect();

            // Önce kelimenin var olup olmadığını kontrol et
            $check_sql = "SELECT id, image FROM doyouknow WHERE id = :id";
            $check_stmt = $pdo->prepare($check_sql);
            $check_stmt->execute([':id' => $id]);
            $existing_word = $check_stmt->fetch(PDO::FETCH_ASSOC);

            if (!$existing_word) {
                echo json_encode(['success' => false, 'message' => 'Güncellenecek bilgi bulunamadı!']);
                exit;
            }

            // Görsel yükleme işlemi
            $image_path = $existing_word['image']; // Mevcut görseli koru

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = '../uploads/doyouknow/';

                // Upload dizini yoksa oluştur
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                // Dosya bilgilerini al
                $file_name = $_FILES['image']['name'];
                $file_tmp = $_FILES['image']['tmp_name'];
                $file_size = $_FILES['image']['size'];
                $file_error = $_FILES['image']['error'];

                // Dosya uzantısı kontrolü
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                if (!in_array($file_ext, $allowed_ext)) {
                    echo json_encode(['success' => false, 'message' => 'Sadece JPG, JPEG, PNG, GIF ve WEBP formatları kabul edilir!']);
                    exit;
                }

                // Dosya boyutu kontrolü (max 5MB)
                if ($file_size > 5 * 1024 * 1024) {
                    echo json_encode(['success' => false, 'message' => 'Dosya boyutu 5MB\'dan küçük olmalıdır!']);
                    exit;
                }

                // Eski görsel varsa sil
                if (!empty($existing_word['image'])) {
                    $old_image_path = '../' . $existing_word['image'];
                    if (file_exists($old_image_path)) {
                        unlink($old_image_path);
                    }
                }

                // Benzersiz dosya adı oluştur
                $new_file_name = uniqid('doyouknow_', true) . '.' . $file_ext;
                $upload_path = $upload_dir . $new_file_name;

                // Dosyayı yükle
                if (move_uploaded_file($file_tmp, $upload_path)) {
                    $image_path = 'uploads/doyouknow/' . $new_file_name;
                } else {
                    echo json_encode(['success' => false, 'message' => 'Dosya yüklenirken hata oluştu!']);
                    exit;
                }
            }

            // Güncelleme işlemi
            $sql = "UPDATE doyouknow 
            SET 
                body = :body, 
                class_id = :class_id, 
                start_date = :start_date, 
                end_date = :end_date, 
                is_active = :is_active, 
                image = :image 
            WHERE id = :id";

            $stmt = $pdo->prepare($sql);

            $result = $stmt->execute([

                ':body' => $meaning,
                ':class_id' => $class_ids,
                ':start_date' => $start_date,
                ':end_date' => $end_date,
                ':is_active' => $status,
                ':image' => $image_path,
                ':id' => $id
            ]);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Bilgi başarıyla güncellendi!'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Bilgi güncellenirken bir hata oluştu!'
                ]);
            }
        } catch (PDOException $e) {
            error_log("Database error in update-word.php: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Veritabanı hatası: ' . $e->getMessage()
            ]);
        } catch (Exception $e) {
            error_log("General error in update-word.php: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Bir hata oluştu: ' . $e->getMessage()
            ]);
        }
        break;



    case 'addDoyouKnow':
        try {
            // Gerekli alanları kontrol et
            $required_fields = ['classes', 'start_date', 'end_date'];
            foreach ($required_fields as $field) {
                if (empty($_POST[$field])) {
                    echo json_encode(['success' => false, 'message' => 'Tüm zorunlu alanları doldurunuz!']);
                    exit;
                }
            }

            // Verileri al

            $meaning = trim($_POST['meaning']);
            $classes = $_POST['classes']; // Array olarak gelecek
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            $status = $_POST['status'];
            $school_id = $_SESSION['school_id'] ?? 1; // Session'dan school_id al veya default 1

            // Sınıf ID'lerini string formatına çevir (noktalı virgül ile birleştir)
            $class_ids = is_array($classes) ? implode(';', $classes) : $classes;

            // Görsel yükleme işlemi
            $image_path = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = '../uploads/doyouknow/';

                // Upload dizini yoksa oluştur
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                // Dosya bilgilerini al
                $file_name = $_FILES['image']['name'];
                $file_tmp = $_FILES['image']['tmp_name'];
                $file_size = $_FILES['image']['size'];
                $file_error = $_FILES['image']['error'];

                // Dosya uzantısı kontrolü
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                if (!in_array($file_ext, $allowed_ext)) {
                    echo json_encode(['success' => false, 'message' => 'Sadece JPG, JPEG, PNG, GIF ve WEBP formatları kabul edilir!']);
                    exit;
                }

                // Dosya boyutu kontrolü (max 5MB)
                if ($file_size > 5 * 1024 * 1024) {
                    echo json_encode(['success' => false, 'message' => 'Dosya boyutu 5MB\'dan küçük olmalıdır!']);
                    exit;
                }

                // Benzersiz dosya adı oluştur
                $new_file_name = uniqid('word_', true) . '.' . $file_ext;
                $upload_path = $upload_dir . $new_file_name;

                // Dosyayı yükle
                if (move_uploaded_file($file_tmp, $upload_path)) {
                    $image_path = 'uploads/doyouknow/' . $new_file_name;
                } else {
                    echo json_encode(['success' => false, 'message' => 'Dosya yüklenirken hata oluştu!']);
                    exit;
                }
            }

            // Veritabanına ekleme işlemi


            $sql = "INSERT INTO doyouknow 
            ( body, school_id, class_id, start_date, end_date, is_active, image) 
            VALUES 
            (:body, :school_id, :class_id, :start_date, :end_date, :is_active, :image)";

            $stmt = $pdo->prepare($sql);

            $result = $stmt->execute([

                ':body' => $meaning,
                ':school_id' => $school_id,
                ':class_id' => $class_ids,
                ':start_date' => $start_date,
                ':end_date' => $end_date,
                ':is_active' => $status,
                ':image' => $image_path
            ]);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Bilgi başarıyla eklendi!',
                    'id' => $pdo->lastInsertId()
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Bilgi eklenirken bir hata oluştu!'
                ]);
            }
        } catch (PDOException $e) {
            error_log("Database error in add-word.php: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Veritabanı hatası: ' . $e->getMessage()
            ]);
        } catch (Exception $e) {
            error_log("General error in add-word.php: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Bir hata oluştu: ' . $e->getMessage()
            ]);
        }

        break;
    case 'upload_test':
        // Frontend'den gelen veriler
        $testName = trim($_POST['test_name'] ?? '');
        $testType = $_POST['test_type'] ?? 'pdf'; // Yeni: test_type (pdf veya link)
        $externalLink = trim($_POST['external_link'] ?? ''); // Yeni: Harici bağlantı
        $coverImagePath = null; // Kapak resmi yolu için başlangıç değeri

        // 1. Veri Doğrulama (Test Adı)
        if (empty($testName)) {
            echo json_encode(['status' => 'error', 'message' => 'Lütfen test adını girin.']);
            exit();
        }

        $dbFilePath = ''; // Veritabanına kaydedilecek yol/link

        // --- TİP KONTROLÜ VE DOSYA/LİNK İŞLEMLERİ ---
        if ($testType === 'pdf') {
            // PDF YÜKLEME İŞLEMİ

            // 2. PDF Dosyası Doğrulama
            if (!isset($_FILES['pdf_file']) || $_FILES['pdf_file']['error'] !== UPLOAD_ERR_OK) {
                echo json_encode(['status' => 'error', 'message' => 'Lütfen bir PDF dosyası seçin veya dosya hatasını kontrol edin.']);
                exit();
            }

            $pdfFile = $_FILES['pdf_file'];
            $allowedMimeTypes = ['application/pdf'];
            $maxFileSize = 5 * 1024 * 1024; // 5MB

            if (!in_array($pdfFile['type'], $allowedMimeTypes)) {
                echo json_encode(['status' => 'error', 'message' => 'Sadece PDF dosyaları yüklenebilir.']);
                exit();
            }

            if ($pdfFile['size'] > $maxFileSize) {
                echo json_encode(['status' => 'error', 'message' => 'PDF dosya boyutu 5MB\'ı geçemez.']);
                exit();
            }

            // 4. PDF Dosya Yükleme İşlemi
            $pdfUploadDir = '../uploads/tests/';
            if (!is_dir($pdfUploadDir)) {
                mkdir($pdfUploadDir, 0755, true);
            }

            $pdfFileName = 'test_' . uniqid() . '_' . time() . '.pdf';
            $filePath = $pdfUploadDir . $pdfFileName; // Sunucudaki tam yol
            $dbFilePath = 'uploads/tests/' . $pdfFileName; // Veritabanı için göreli yol

            if (!move_uploaded_file($pdfFile['tmp_name'], $filePath)) {
                echo json_encode(['status' => 'error', 'message' => 'PDF dosyası sunucuya yüklenirken bir sorun oluştu.']);
                exit();
            }
        } elseif ($testType === 'link') {
            // HARİCİ BAĞLANTI İŞLEMİ
            if (empty($externalLink)) {
                echo json_encode(['status' => 'error', 'message' => 'Lütfen harici bağlantı adresini girin.']);
                exit();
            }
            // Basit URL doğrulama
            if (!filter_var($externalLink, FILTER_VALIDATE_URL)) {
                echo json_encode(['status' => 'error', 'message' => 'Geçersiz bağlantı formatı.']);
                exit();
            }
            $dbFilePath = $externalLink; // Harici bağlantıyı doğrudan file_path sütununa kaydet
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Geçersiz test tipi seçimi.']);
            exit();
        }
        // --- TİP KONTROLÜ SONU ---

        // 3. Kapak Resmi (cover_img) İşlemi (Opsiyonel)
        if (isset($_FILES['cover_img']) && $_FILES['cover_img']['error'] === UPLOAD_ERR_OK) {
            $imgFile = $_FILES['cover_img'];
            $allowedImgMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];

            if (!in_array($imgFile['type'], $allowedImgMimeTypes) && !empty($imgFile['type'])) {
                echo json_encode(['status' => 'error', 'message' => 'Kapak resmi için sadece JPEG, PNG veya WEBP dosyaları yüklenebilir.']);
                // Resim hatası durumunda PDF dosyası yüklendiyse silinmelidir!
                if ($testType === 'pdf' && isset($filePath) && file_exists($filePath)) {
                    unlink($filePath);
                }
                exit();
            }

            $imgUploadDir = '../uploads/tests/covers/';
            if (!is_dir($imgUploadDir)) {
                mkdir($imgUploadDir, 0755, true);
            }

            $imgExtension = pathinfo($imgFile['name'], PATHINFO_EXTENSION);
            $imgFileName = 'cover_' . uniqid() . '_' . time() . '.' . $imgExtension;
            $imgFilePath = $imgUploadDir . $imgFileName;
            $dbCoverPath = 'uploads/tests/covers/' . $imgFileName;

            if (move_uploaded_file($imgFile['tmp_name'], $imgFilePath)) {
                $coverImagePath = $dbCoverPath;
            } else {
                error_log("Kapak resmi yüklenirken bir sorun oluştu: " . $imgFile['name']);
            }
        }


        // 5. Veritabanı Kaydı (name, file_path, cover_img_path ve test_type sütunları kullanıldı)
        $sql = "INSERT INTO pskolojik_test_lnp (name, file_path, cover_img_path, test_type) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);


        if ($stmt->execute([$testName, $dbFilePath, $coverImagePath, $testType])) {
            echo json_encode(['status' => 'success', 'message' => 'Test başarıyla yüklendi/bağlantı kaydedildi.']);
        } else {
            // Veritabanı hatası durumunda PDF ve resmi sil
            if ($testType === 'pdf' && isset($filePath) && file_exists($filePath)) {
                unlink($filePath);
            }
            if ($coverImagePath && file_exists('../' . $coverImagePath)) {
                unlink('../' . $coverImagePath);
            }

            echo json_encode(['status' => 'error', 'message' => 'Veritabanı kayıt hatası.']);
        }
        break;

    // --- LİSTELEME (FETCH) İŞLEMİ ---
    case 'fetch_tests':
        // Yeni sütun adı eklendi: test_type
        $sql = "SELECT id, name, file_path, created_at, test_type FROM pskolojik_test_lnp ORDER BY created_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        if ($stmt) {
            $tests = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['status' => 'success', 'data' => $tests]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Test listesi çekilirken veritabanı hatası.']);
        }
        break;

    // --- SİLME (DELETE) İŞLEMİ ---
    case 'delete_test':
        // Frontend'den gelen 'test_id', 'id' sütununa karşılık gelir.
        $testId = $_POST['test_id'] ?? null;

        if (empty($testId)) {
            echo json_encode(['status' => 'error', 'message' => 'Silinecek test ID\'si eksik.']);
            exit();
        }

        // 1. Dosya yolunu ve tipini al (id kullanıldı)
        $sql = "SELECT file_path, test_type, cover_img_path FROM pskolojik_test_lnp WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$testId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            echo json_encode(['status' => 'error', 'message' => 'Silinecek test bulunamadı.']);
            exit();
        }

        $filePath = $result['file_path'];
        $testType = $result['test_type'];
        $coverImgPath = $result['cover_img_path'];


        // 2. Veritabanından kaydı sil (id kullanıldı)
        $sqlDelete = "DELETE FROM pskolojik_test_lnp WHERE id = ?";
        $stmtDelete = $pdo->prepare($sqlDelete);

        if ($stmtDelete->execute([$testId])) {

            // 3. Dosyaları sil (Sadece PDF ise dosya silinir, link ise silinmez)
            $message = "Test başarıyla silindi (Kayıt silindi).";

            // Root yolunu bulma
            $rootPath = dirname(__DIR__, 2) . '/';

            // PDF Dosyasını Sil
            if ($testType === 'pdf' && !empty($filePath)) {
                $fullPath = $rootPath . $filePath;
                if (file_exists($fullPath) && strpos($filePath, 'uploads/tests/') === 0) {
                    if (unlink($fullPath)) {
                        $message = "Test başarıyla silindi (Kayıt ve PDF dosyası silindi).";
                    } else {
                        $message = "Test başarıyla silindi (Kayıt silindi, **PDF dosyası silinemedi**).";
                        error_log("PDF Dosya silme hatası: {$fullPath}");
                    }
                }
            }

            // Kapak Resmini Sil (Varsa)
            if (!empty($coverImgPath)) {
                $fullCoverPath = $rootPath . $coverImgPath;
                if (file_exists($fullCoverPath) && strpos($coverImgPath, 'uploads/tests/covers/') === 0) {
                    if (!unlink($fullCoverPath)) {
                        error_log("Kapak Resmi silme hatası: {$fullCoverPath}");
                    }
                }
            }


            echo json_encode(['status' => 'success', 'message' => $message]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Silme hatası: Veritabanı işlemi başarısız.']);
        }
        break;

    // --- DÜZENLEME (UPDATE) İŞLEMİ ---
    case 'update_test':
        // Frontend'den gelen 'test_id', 'id' sütununa karşılık gelir.
        $testId = $_POST['test_id'] ?? null;
        // Frontend'den gelen 'test_name', 'name' sütununa karşılık gelir.
        $newName = trim($_POST['test_name'] ?? '');

        if (empty($testId) || empty($newName)) {
            echo json_encode(['status' => 'error', 'message' => 'Test ID veya yeni ad boş olamaz.']);
            exit();
        }

        // name ve id sütunları kullanıldı, updated_at otomatik güncellenecektir.
        $sql = "UPDATE pskolojik_test_lnp SET name = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);


        if ($stmt->execute([$newName, $testId])) {
            echo json_encode(['status' => 'success', 'message' => 'Test adı başarıyla güncellendi.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Güncelleme hatası: Veritabanı işlemi başarısız.']);
        }
        break;
    case 'pskTestDownload':
        // Gerekli verileri al
        $userId = $_POST['student_id'] ?? 0;
        // Varsayılan school_id (pskTestUpload'daki gibi)
        $schoolId = $_SESSION['school_id'] ?? 1;
        $testId = filter_input(INPUT_POST, 'test_id', FILTER_VALIDATE_INT);
        $filePath = $_POST['file_path'] ?? '';

        // Temel Geçerlilik Kontrolleri
        if (!$userId || $userId === 0) {
            echo json_encode(['success' => false, 'message' => 'Kullanıcı oturumu bulunamadı.']);
            exit;
        }

        if (!$testId || $testId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Geçersiz Test ID.']);
            exit;
        }

        if (empty($filePath) || $filePath === '#') {
            echo json_encode(['success' => false, 'message' => 'Test dosya yolu bulunamadı.']);
            exit;
        }

        // 1. Kullanıcının TOPLAMDA bu testler için daha önce ÜCRETSİZ indirme hakkını KULLANIP KULLANMADIĞINI KONTROL ET
        // ÖNEMLİ DEĞİŞİKLİK: Sorgudan test_id filtresi KALDIRILDI. user_id'ye ait herhangi bir is_free=1 kaydı var mı diye bakılıyor.
        $sqlCntrl = "SELECT id FROM psikolojik_test_sonuc_lnp WHERE user_id = :user_id AND is_free = 1 LIMIT 1";
        $stmt2 = $pdo->prepare($sqlCntrl);
        $stmt2->execute([
            'user_id' => $userId
        ]);
        // Eğer sonuç gelirse, bu kullanıcı ücretsiz hakkını daha önce kullanmıştır.
        $isFreeUsed = $stmt2->fetch(PDO::FETCH_ASSOC);

        if ($isFreeUsed) {
            // Ücretsiz hak DAHA ÖNCE HERHANGİ BİR TEST İÇİN kullanılmış, ikinci indirme talebi: paket kontrolü yap
            $sqlPackage = "SELECT id FROM psikolojik_test_paketleri_user WHERE user_id = :user_id AND kullanim_durumu = 0 ORDER BY id ASC LIMIT 1";
            $stmt3 = $pdo->prepare($sqlPackage);
            $stmt3->execute([
                'user_id' => $userId
            ]);
            $package = $stmt3->fetch(PDO::FETCH_ASSOC);

            if (!$package) {
                // Paket yoksa indirmeye izin verme (Harici link olsa bile engellenir)
                echo json_encode([
                    'success' => false,
                    'message' => 'Toplam ücretsiz hakkınızı kullandınız. Bu indirme paket gereklidir. Lütfen paket alınız. Paket almak için <a href="ek-paket-satin-al" >buraya tıklayın</a>.'
                ]);
                exit;
            }

            // Paket varsa, Transaction (İşlem Grubu) başlat
            try {
                $pdo->beginTransaction();

                // 1. Paketi kullanıldı olarak işaretle
                $stmt5 = $pdo->prepare("
                UPDATE psikolojik_test_paketleri_user
                SET kullanim_durumu = 1
                WHERE id = :package_id
            ");
                $stmt5->execute([
                    'package_id' => $package['id'],
                    'test_id' => $testId
                ]);

                // 2. İşlemi onayla
                $pdo->commit();

                // İndirme işlemi için başarı mesajı gönder (ve indirme yolunu döndür)
                echo json_encode(['success' => true, 'message' => 'Paketiniz kullanıldı, indirme başlatılıyor.', 'download_link' => $filePath]);
                exit;
            } catch (PDOException $e) {
                $pdo->rollBack();
                // Hata durumunda
                echo json_encode(['success' => false, 'message' => 'Paket güncelleme hatası. Lütfen tekrar deneyin.']);
                exit;
            }
        } else {
            // Ücretsiz hak HENÜZ KULLANILMAMIŞ (Toplamda ilk indirme), indirme izni ver ve is_free=1 olarak KAYDET (Bu test için)
            try {
                // Kayıt var mı diye kontrol edelim (Yani, kullanıcı daha önce bu test için yükleme yapmış olabilir, ama indirme yapmamış olabilir)
                $sqlCheckExisting = "SELECT id FROM psikolojik_test_sonuc_lnp WHERE user_id = :user_id AND test_id = :test_id";
                $stmtCheck = $pdo->prepare($sqlCheckExisting);
                $stmtCheck->execute(['user_id' => $userId, 'test_id' => $testId]);
                $existingRecord = $stmtCheck->fetch(PDO::FETCH_ASSOC);

                if ($existingRecord) {
                    // Kayıt zaten var (muhtemelen daha önce yükleme yapılmış), is_free'yi 1 yap
                    $stmt4 = $pdo->prepare("UPDATE psikolojik_test_sonuc_lnp SET is_free = 1 WHERE id = :id");
                    $stmt4->execute(['id' => $existingRecord['id']]);
                } else {
                    // İlk defa indirme yapılıyor, is_free=1 olarak yeni bir sonuç kaydı oluştur
                    $sql = "INSERT INTO psikolojik_test_sonuc_lnp (test_id, user_id, school_id, is_free) 
                         VALUES (?, ?, ?, 1)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$testId, $userId, $schoolId]);
                }

                // İndirme işlemi için başarı mesajı gönder (ve indirme yolunu döndür)
                echo json_encode(['success' => true, 'message' => 'TOPLAMDAKİ ilk ücretsiz indirme hakkınız kullanıldı, indirme başlatılıyor.', 'download_link' => $filePath]);
                exit;
            } catch (PDOException $e) {
                // Hata durumunda
                echo json_encode(['success' => false, 'message' => 'Veritabanı bağlantı hatası: ' . $e->getMessage()]);
                exit;
            }
        }

        break;
    case 'pskTestUpload':
        // 1. Değişkenler
        $userId = $_POST['student_id'] ?? 0;
        $schoolId = $_SESSION['school_id'] ?? 1; // school_id'nin varsayılan 1 olması ayarını uyguladık

        // Formdan gelen veriler
        $testId = filter_input(INPUT_POST, 'test_id', FILTER_VALIDATE_INT);
        $file = $_FILES['cevap_dosyasi'] ?? null;

        // 2. Temel Geçerlilik Kontrolleri
        if (!$userId || $userId === 0) {
            echo json_encode(['success' => false, 'message' => 'Kullanıcı oturumu bulunamadı.']);
            exit;
        }

        if (!$testId || $testId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Geçersiz Test ID.']);
            exit;
        }

        // **ÖNEMLİ:** Buradaki paket kontrol mantığı KALDIRILMIŞTIR!
        // Paket kontrolü artık 'pskTestDownload' case'inde yapılmaktadır.


        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['success' => false, 'message' => 'Dosya yüklenemedi veya geçersiz.']);
            exit;
        }

        // 3. Dosya Tipi ve Boyut Kontrolü
        $allowedMimeTypes = [
            'application/pdf',
            'image/jpeg',
            'image/png',
            'application/zip',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' // .docx
        ];
        $maxFileSize = 10 * 1024 * 1024; // 10 MB (Aynı kalsın)

        if (!in_array($file['type'], $allowedMimeTypes)) {
            echo json_encode(['success' => false, 'message' => 'Desteklenmeyen dosya formatı. Sadece PDF, JPG, PNG, DOCX ve ZIP izinlidir.']);
            exit;
        }

        if ($file['size'] > $maxFileSize) {
            echo json_encode(['success' => false, 'message' => 'Dosya boyutu 10MB’dan büyük olamaz.']);
            exit;
        }

        // ***************************************************************
        // *** GÜNCELLENMİŞ PAKET / KULLANIM HAKKI KONTROLÜ BAŞLANGICI ***
        try {
            // Kaydın varlığını ve daha önce dosya yüklenip yüklenmediğini kontrol et
            $sqlCheck = "SELECT file_path FROM psikolojik_test_sonuc_lnp 
                         WHERE test_id = :test_id AND user_id = :user_id";
            $stmtCheck = $pdo->prepare($sqlCheck);
            $stmtCheck->execute(['test_id' => $testId, 'user_id' => $userId]);
            $result = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            // 1. İndirme hakkı kontrolü (Kayıt yoksa indirme yapılmamış demektir)
            if (!$result) {
                echo json_encode(['success' => false, 'message' => 'Bu testi cevaplamak için önce indirme hakkınızı kullanmalısınız. Cevap yüklenemedi.']);
                exit;
            }

            // 2. Tekrar yükleme kontrolü (Dosya yolu zaten doluysa, daha önce yükleme yapılmıştır)
            // Eğer file_path doluysa ve bu bir FREE test değilse, yeniden yüklemeyi engelleyebiliriz.
            // En basit çözüm: file_path doluysa engelle.
            if (!empty($result['file_path'])) {
                echo json_encode(['success' => false, 'message' => 'Bu teste ait cevap dosyasını daha önce yüklediniz. Tekrar yükleme yapılamaz.']);
                exit;
            }
        } catch (PDOException $e) {
            // Veritabanı bağlantı hatası
            echo json_encode(['success' => false, 'message' => 'Veritabanı kontrol hatası. Cevap yüklenemedi.']);
            exit;
        }
        // *** GÜNCELLENMİŞ PAKET / KULLANIM HAKKI KONTROLÜ BİTİŞİ ***
        // ***************************************************************

        // 4. Dosyayı Güvenli Bir Şekilde Kaydetme
        $uploadDir = '../uploads/psk_test_cevaplari/'; // Kök dizine göre göreceli yol (Aynı kalsın)

        // Klasör yoksa oluştur
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Benzersiz dosya adı oluşturma: user_id_test_id_zaman.uzantı (Aynı kalsın)
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = sprintf(
            '%d_%d_%s.%s',
            $userId,
            $testId,
            time(),
            $fileExtension
        );

        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {

            // 5. Veritabanına Kaydetme (Sadece dosya yolunu güncelliyoruz)
            try {
                // DB'ye kaydederken dosya yolundan '../' kısmını kaldırıyoruz
                $dbFilePath = str_replace('../', '', $targetPath);

                // Varolan kaydı (indirme sırasında oluşturulanı veya daha öncekini) güncelle
                // NOT: Yukarıdaki kontrolden dolayı buradaki UPDATE, file_path boşken (ilk yüklemede) çalışacaktır.
                $sqlUpdate = "UPDATE psikolojik_test_sonuc_lnp 
                              SET file_path = :file_path, school_id = :school_id
                              WHERE test_id = :test_id AND user_id = :user_id";
                $stmt = $pdo->prepare($sqlUpdate);

                if ($stmt->execute([
                    'file_path' => $dbFilePath,
                    'school_id' => $schoolId,
                    'test_id' => $testId,
                    'user_id' => $userId
                ])) {
                    // Normalde yukaridaki kontrol nedeniyle rowCount() 0 olmamalı.
                    // Ancak indirme yapmadan direkt yükleme yapmaya çalışan bir kullanıcı için
                    // eğer kayıt yoksa (ki bu durumda yukarıda engellenir) bu blok çalışır.
                    // Eğer test indirilmiş ve bir kayıt oluşmuşsa, UPDATE çalışır.
                    if ($stmt->rowCount() == 0) {
                        // Bu blok, indirme kaydı olup da UPDATE'in 0 satırı etkilediği teorik bir durum için duruyor.
                        // Pratik olarak, indirme yapmadan direkt yükleme yapanlar yukarıda engellenir.
                        $sqlInsert = "INSERT INTO psikolojik_test_sonuc_lnp (test_id, user_id, school_id, file_path, upload_date, is_free) 
                                      VALUES (?, ?, ?, ?, NOW(), 0)";
                        $stmtInsert = $pdo->prepare($sqlInsert);
                        $stmtInsert->execute([$testId, $userId, $schoolId, $dbFilePath]);
                    }

                    echo json_encode(['success' => true, 'message' => 'Cevabınız başarıyla yüklendi ve kaydedildi.']);
                } else {
                    unlink($targetPath);
                    echo json_encode(['success' => false, 'message' => 'Dosya yüklendi ancak veritabanına kaydedilemedi.']);
                }
            } catch (PDOException $e) {
                // Veritabanı hatası
                unlink($targetPath);
                // error_log("DB Hatası: " . $e->getMessage());
                echo json_encode(['success' => false, 'message' => 'Veritabanı bağlantı hatası: ' . $e->getMessage()]);
            }
        } else {
            // move_uploaded_file hatası
            echo json_encode(['success' => false, 'message' => 'Dosya sunucuya taşınamadı. Klasör izinlerini kontrol edin.']);
        }
        break;
    case 'update_test_status':
        $id = $_POST['id'] ?? null;
        // Frontend'den gelen 'test_name', 'name' sütununa karşılık gelir.
        $status = trim($_POST['status'] ?? '');
        $description = trim($_POST['description'] ?? '');
        if (empty($id) || !isset($status)) {
            echo json_encode(['status' => 'error', 'message' => ' ID veya durum ad boş olamaz.']);
            exit();
        }

        // name ve id sütunları kullanıldı, updated_at otomatik güncellenecektir.
        $sql = "UPDATE psikolojik_test_sonuc_lnp SET status = ?,description=?  WHERE id = ?";
        $stmt = $pdo->prepare($sql);


        if ($stmt->execute([$status, $description, $id])) {
            echo json_encode(['status' => 'success', 'message' => 'Başarıyla güncellendi.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Güncelleme hatası: Veritabanı işlemi başarısız.']);
        }
        break;
    case 'psikologAyarlar':
        // 1. Gerekli Kontroller
        if (!isset($_SESSION['id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Kullanıcı oturumu bulunamadı. Lütfen giriş yapın.']);
            exit;
        }

        $psikologId = $_SESSION['id'];
        $appointmentDuration = $_POST['appointment_duration'] ?? 30;

        // POST verilerini normalize etmek için yardımcı fonksiyon
        $normalizePostArray = function ($array) {
            $normalized = [];
            if (is_array($array)) {
                foreach ($array as $key => $value) {
                    // Anahtarı küçük harfe çevir ve Türkçe karakterlerden arındır
                    $safe_key = strtolower(str_replace(['İ', 'I', 'Ş', 'Ü', 'Ç', 'Ö', 'Ğ', 'i', 'ı', 'ş', 'ü', 'ç', 'ö', 'ğ'], ['i', 'i', 's', 'u', 'c', 'o', 'g', 'i', 'i', 's', 'u', 'c', 'o', 'g'], $key));
                    $normalized[$safe_key] = $value;
                }
            }
            return $normalized;
        };

        // Gelen veriyi normalize et
        $activeDays = $normalizePostArray($_POST['is_active'] ?? []);
        $startTimes = $normalizePostArray($_POST['start_time'] ?? []);
        $endTimes = $normalizePostArray($_POST['end_time'] ?? []);

        // Randevu süresi kontrolü
        if (!is_numeric($appointmentDuration) || $appointmentDuration < 15) {
            echo json_encode(['status' => 'error', 'message' => 'Randevu süresi minimum 15 dakika olmalıdır.']);
            exit;
        }

        // Geçerli günler listesi (Servis kodunda beklenen anahtarlar)
        $allDays = [
            'pazartesi' => 'Pazartesi',
            'sali' => 'Salı',
            'carsamba' => 'Çarşamba', // Burası artık normalize edilmiş anahtarla eşleşecek
            'persembe' => 'Perşembe',
            'cuma' => 'Cuma',
            'cumartesi' => 'Cumartesi',
            'pazar' => 'Pazar'
        ];

        try {
            if (!isset($pdo)) {
                $dbh = new Dbh();
                $pdo = $dbh->connect();
            }

            $pdo->beginTransaction();

            // 2. Çalışma Günlerini ve Saatlerini İşleme Döngüsü
            foreach ($allDays as $safeDay => $turkishDay) {
                // Normalize edilmiş dizide 'carsamba' anahtarı kontrol edilecek
                $isActive = isset($activeDays[$safeDay]) ? 1 : 0;

                // A. psikolog_calisma_gunleri tablosunu güncelle/oluştur (UPSERT)
                $stmt = $pdo->prepare("SELECT id FROM psikolog_calisma_gunleri WHERE user_id = :user_id AND day = :day");
                $stmt->execute([':user_id' => $psikologId, ':day' => $turkishDay]);
                $workingDayId = $stmt->fetchColumn();

                if ($workingDayId) {
                    $stmt = $pdo->prepare("UPDATE psikolog_calisma_gunleri SET is_active = :is_active, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
                    $stmt->execute([':is_active' => $isActive, ':id' => $workingDayId]);
                } else {
                    $stmt = $pdo->prepare("INSERT INTO psikolog_calisma_gunleri (user_id, day, is_active) VALUES (:user_id, :day, :is_active)");
                    $stmt->execute([':user_id' => $psikologId, ':day' => $turkishDay, ':is_active' => $isActive]);
                    $workingDayId = $pdo->lastInsertId();
                }

                // B. Çalışma Saatlerini İşleme (Sadece Gün Aktifse)
                // Mevcut saat kayıtlarını temizle
                $stmt = $pdo->prepare("DELETE FROM psikolog_calisma_saatleri WHERE working_day_id = :working_day_id");
                $stmt->execute([':working_day_id' => $workingDayId]);

                if ($isActive == 1 && !empty($startTimes[$safeDay]) && !empty($endTimes[$safeDay])) {
                    $startTime = $startTimes[$safeDay];
                    $endTime = $endTimes[$safeDay];

                    // Saat formatı ve geçerlilik kontrolü (önerilir)
                    if (strtotime($endTime) <= strtotime($startTime)) {
                        $pdo->rollBack();
                        echo json_encode(['status' => 'error', 'message' => $turkishDay . ' için geçersiz saat aralığı. Bitiş saati başlangıçtan sonra olmalıdır.']);
                        exit;
                    }

                    // Yeni Saat Kaydını Oluştur
                    $stmt = $pdo->prepare("
                    INSERT INTO psikolog_calisma_saatleri 
                        (working_day_id, start_time, end_time, appointment_duration) 
                    VALUES 
                        (:working_day_id, :start_time, :end_time, :appointment_duration)
                ");
                    $stmt->execute([
                        ':working_day_id' => $workingDayId,
                        ':start_time' => $startTime,
                        ':end_time' => $endTime,
                        ':appointment_duration' => $appointmentDuration
                    ]);
                }
            }

            $pdo->commit();
            echo json_encode(['status' => 'success', 'message' => 'Çalışma ayarları başarıyla güncellendi.']);
        } catch (PDOException $e) {
            if (isset($pdo) && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            echo json_encode(['status' => 'error', 'message' => 'Veritabanı işlemi sırasında bir hata oluştu: ' . $e->getMessage()]);
        }
        break;
        // =========================================================================================
        // YARDIMCI FONKSİYONLAR (Gerektiğinde Ajax dosyası dışında bir Utility sınıfına taşınabilir)
        // =========================================================================================

        /**
         * Belirtilen tarihin haftanın hangi günü olduğunu Türkçe döndürür.
         * @param string $date YYYY-MM-DD formatında tarih
         * @return string Haftanın Günü (Pazartesi, Salı, ...)
         */
        function getTurkishDayName($date)
        {
            $timestamp = strtotime($date);
            $day = date('N', $timestamp); // 1 (Pazartesi) ile 7 (Pazar) arası
            $days = [
                1 => 'Pazartesi',
                2 => 'Salı',
                3 => 'Çarşamba',
                4 => 'Perşembe',
                5 => 'Cuma',
                6 => 'Cumartesi',
                7 => 'Pazar'
            ];
            return $days[$day];
        }


        // =========================================================================================
        // AJAX SERVİSLERİ
        // =========================================================================================

        // ... switch($service) {

    case 'getAvailableSlots':
        // 1. Giriş Kontrolü
        if (empty($_POST['psikolog_id']) || empty($_POST['appointment_date'])) {
            echo json_encode(['status' => 'error', 'message' => 'Psikolog ve randevu tarihi zorunludur.']);
            exit;
        }

        $psikologId = (int)$_POST['psikolog_id'];
        $appointmentDate = $_POST['appointment_date'];
        $dayName = getTurkishDayName($appointmentDate);
        $availableSlots = [];

        try {
            if (!isset($pdo)) {
                $dbh = new Dbh();
                $pdo = $dbh->connect();
            }

            // A. Çalışma Saatini Çek
            $stmt = $pdo->prepare("
            SELECT 
                pcs.start_time, 
                pcs.end_time, 
                pcs.appointment_duration,
                pcg.is_active
            FROM psikolog_calisma_gunleri pcg
            JOIN psikolog_calisma_saatleri pcs ON pcg.id = pcs.working_day_id
            WHERE pcg.user_id = :user_id AND pcg.day = :day_name AND pcg.is_active = 1
        ");
            $stmt->execute([':user_id' => $psikologId, ':day_name' => $dayName]);
            $workingHours = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$workingHours) {
                echo json_encode(['status' => 'success', 'message' => 'Psikolog bu gün çalışmamaktadır.', 'slots' => []]);
                exit;
            }

            $calismaBaslangic = strtotime($workingHours['start_time']);
            $calismaBitis = strtotime($workingHours['end_time']);
            $duration = (int)$workingHours['appointment_duration'];
            $durationSeconds = $duration * 60;

            // B. Mevcut Randevuları Çek
            // 'İptal' olmayan tüm randevuları çeker
            $stmt = $pdo->prepare("
            SELECT start_time, end_time 
            FROM psikolog_randevular 
            WHERE user_id = :user_id 
              AND appointment_date = :appointment_date 
              AND status != 'İptal'
        ");
            $stmt->execute([':user_id' => $psikologId, ':appointment_date' => $appointmentDate]);
            $bookedSlots = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // C. Boş Slotları Hesapla
            $currentTime = $calismaBaslangic;

            while (($currentTime + $durationSeconds) <= $calismaBitis) {
                $slotStart = date('H:i', $currentTime);
                $slotEnd = date('H:i', $currentTime + $durationSeconds);
                $isBooked = false;

                // Her yeni slotu mevcut randevularla kontrol et
                foreach ($bookedSlots as $booked) {
                    $bookedStart = strtotime($booked['start_time']);
                    $bookedEnd = strtotime($booked['end_time']);

                    // Slot, mevcut randevu ile çakışıyorsa (Çakışma: Slot Başlangıcı < Randevu Bitişi VE Slot Bitişi > Randevu Başlangıcı)
                    if ($currentTime < $bookedEnd && ($currentTime + $durationSeconds) > $bookedStart) {
                        $isBooked = true;
                        break;
                    }
                }

                // Çakışma yoksa listeye ekle
                if (!$isBooked) {
                    $availableSlots[] = [
                        'start' => $slotStart,
                        'end' => $slotEnd,
                        'display' => $slotStart . ' - ' . $slotEnd
                    ];
                }

                // Sonraki slota geç
                $currentTime += $durationSeconds;
            }

            echo json_encode(['status' => 'success', 'slots' => $availableSlots]);
        } catch (PDOException $e) {
            error_log("Slot Hesaplama Hatası: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Veritabanı hatası: Saatler çekilemedi.']);
        }
        break;

    case 'createAppointment':
        // 1. Giriş Kontrolü
        if (empty($_POST['psikolog_id']) || empty($_POST['appointment_date']) || empty($_POST['start_time']) || empty($_POST['end_time']) || empty($_POST['client_name'])) {
            echo json_encode(['status' => 'error', 'message' => 'Tüm randevu bilgileri zorunludur.']);
            exit;
        }
        if (!isset($_SESSION['id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Oturum bilgisi bulunamadı. Lütfen tekrar giriş yapın.']);
            exit;
        }

        $psikologId = (int)$_POST['psikolog_id'];
        $clientUserId = (int)$_SESSION['id']; // Randevu talep eden kullanıcı ID'si
        $clientName = trim($_POST['client_name']);
        $clientPhone = trim($_POST['client_phone'] ?? '');
        $appointmentDate = $_POST['appointment_date'];
        $startTime = $_POST['start_time'];
        $endTime = $_POST['end_time'];
        $notes = trim($_POST['notes'] ?? '');

        try {
            if (!isset($pdo)) {
                $dbh = new Dbh();
                $pdo = $dbh->connect();
            }

            // =========================================================================
            // YENİ KONTROL 1: Bekleyen Test Sayısı ve Bekleyen Randevu İlişkisi
            // =========================================================================

            // A. Bekleyen (status=0) test sonucu sayısını bul
            $sqlCountTests = "
                SELECT COUNT(id) 
                FROM psikolojik_test_sonuc_lnp 
                WHERE user_id = :user_id 
                AND status = 0 
                AND file_path IS NOT NULL
                AND file_path != ''
            ";
            $stmtCountTests = $pdo->prepare($sqlCountTests);
            $stmtCountTests->execute([':user_id' => $clientUserId]);
            $pendingTestsCount = $stmtCountTests->fetchColumn(); // N değeri

            // B. Halihazırda 'Beklemede' olan randevu sayısını bul (Onaylanmamış talepler)
            $sqlCountAppointments = "
                SELECT COUNT(id) 
                FROM psikolog_randevular 
                WHERE client_user_id = :client_user_id 
                AND status = 'Beklemede'
            ";
            $stmtCountAppointments = $pdo->prepare($sqlCountAppointments);
            $stmtCountAppointments->execute([':client_user_id' => $clientUserId]);
            $pendingAppointmentsCount = $stmtCountAppointments->fetchColumn(); // M değeri

            // C. Kontrol: Bekleyen test sayısı, beklemede olan randevu sayısından büyük/eşit olmalı.
            // Yani, her bekleyen test için maksimum 1 adet randevu oluşturma hakkı var.
            if ($pendingAppointmentsCount >= $pendingTestsCount) {
                // $pendingTestsCount = 0 ise ve $pendingAppointmentsCount >= 0 ise randevu oluşturulamaz.
                // $pendingTestsCount = 1 ise ve $pendingAppointmentsCount >= 1 ise randevu oluşturulamaz.
                $message = '';
                if ($pendingTestsCount == 0) {
                    $message = 'Yeni randevu talebi oluşturabilmek için öncelikle bir psikolojik test sonucunuzun değerlendirilmek üzere beklemede olması gerekmektedir.';
                } else {
                    $message = 'Halihazırda ' . $pendingAppointmentsCount . ' adet beklemede randevu talebiniz bulunmaktadır. Değerlendirilmek üzere  ' . $pendingTestsCount . ' adet testiniz beklediği için yeni randevu talebi oluşturamazsınız.';
                }

                echo json_encode([
                    'status' => 'error',
                    'message' => $message
                ]);
                exit;
            }
            // =========================================================================
            // KONTROL BİTİŞ: Eğer buraya ulaşıldıysa, randevu oluşturulabilir ($N > $M)
            // =========================================================================

            // 2. Randevu Çakışma Kontrolü (Aynı psikolog, tarih ve saat dilimi için)
            $stmt = $pdo->prepare("
                SELECT id FROM psikolog_randevular 
                WHERE user_id = :user_id 
                  AND appointment_date = :appointment_date 
                  AND status != 'İptal' 
                  AND (
                      (start_time <= :start_time AND end_time > :start_time) OR 
                      (start_time < :end_time AND end_time >= :end_time) OR 
                      (start_time >= :start_time AND end_time <= :end_time) 
                  )
            ");
            $stmt->execute([
                ':user_id' => $psikologId,
                ':appointment_date' => $appointmentDate,
                ':start_time' => $startTime,
                ':end_time' => $endTime
            ]);

            if ($stmt->fetch(PDO::FETCH_ASSOC)) {
                echo json_encode(['status' => 'error', 'message' => 'Seçtiğiniz saat dilimi maalesef doludur. Lütfen başka bir saat seçin.']);
                exit;
            }

            // 3. Randevuyu Kaydet
            $stmt = $pdo->prepare("
                INSERT INTO psikolog_randevular 
                    (user_id, client_user_id, client_name, client_phone, appointment_date, start_time, end_time, status, notes) 
                VALUES 
                    (:psikolog_id, :client_user_id, :client_name, :client_phone, :appointment_date, :start_time, :end_time, 'Beklemede', :notes)
            ");
            $stmt->execute([
                ':psikolog_id' => $psikologId,
                ':client_user_id' => $clientUserId,
                ':client_name' => $clientName,
                ':client_phone' => $clientPhone,
                ':appointment_date' => $appointmentDate,
                ':start_time' => $startTime,
                ':end_time' => $endTime,
                ':notes' => $notes
            ]);

            echo json_encode(['status' => 'success', 'message' => 'Randevunuz başarıyla talep edildi ve onay bekliyor.']);
        } catch (PDOException $e) {
            // error_log("Randevu Kayıt Hatası: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Randevu kaydedilirken bir veritabanı hatası oluştu.']);
        }
        break;
    case 'getAppointmentsByClient':
        // Danışanın (client_user_id) kendi randevularını listeler
        if (!isset($_SESSION['id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Oturum hatası.']);
            exit;
        }
        $clientUserId = (int)$_SESSION['id'];
        $appointments = [];

        try {
            if (!isset($pdo)) {
                $dbh = new Dbh();
                $pdo = $dbh->connect();
            }

            $stmt = $pdo->prepare("
            SELECT 
                pr.id, 
                pr.appointment_date, 
                pr.start_time, 
                pr.end_time, 
                pr.status,
                pr.user_id as psikolog_id,
                u.name AS psikolog_name, 
                u.surname AS psikolog_surname
            FROM psikolog_randevular pr
            JOIN users_lnp u ON pr.user_id = u.id -- Psikologun adını çekmek için JOIN
            WHERE pr.client_user_id = :client_user_id
            ORDER BY pr.appointment_date DESC, pr.start_time DESC
        ");
            $stmt->execute([':client_user_id' => $clientUserId]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Psikolog adını birleştirme
            foreach ($results as $row) {
                $row['psikolog_name'] = $row['psikolog_name'] . ' ' . $row['psikolog_surname'];
                unset($row['psikolog_surname']);
                $appointments[] = $row;
            }

            echo json_encode(['status' => 'success', 'appointments' => $appointments]);
        } catch (PDOException $e) {
            error_log("Randevu Listeleme Hatası: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Randevular listelenirken bir hata oluştu.']);
        }
        break;

    case 'updatePendingAppointment':
        // Beklemedeki randevunun tarih ve saatini günceller
        if (empty($_POST['appointment_id']) || empty($_POST['appointment_date']) || empty($_POST['start_time']) || empty($_POST['end_time'])) {
            echo json_encode(['status' => 'error', 'message' => 'Tüm alanlar zorunludur.']);
            exit;
        }
        if (!isset($_SESSION['id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Oturum bilgisi bulunamadı.']);
            exit;
        }

        $appointmentId = (int)$_POST['appointment_id'];
        $clientUserId = (int)$_SESSION['id'];
        $appointmentDate = $_POST['appointment_date'];
        $startTime = $_POST['start_time'];
        $endTime = $_POST['end_time'];
        $psikologId = (int)$_POST['psikolog_id']; // Yeni çakışma kontrolü için gerekli

        try {
            if (!isset($pdo)) {
                $dbh = new Dbh();
                $pdo = $dbh->connect();
            }

            $pdo->beginTransaction();

            // 1. Yetki ve Durum Kontrolü (Randevu beklemede mi ve bu kullanıcıya mı ait?)
            $stmt = $pdo->prepare("
            SELECT status FROM psikolog_randevular 
            WHERE id = :id AND client_user_id = :client_user_id
        ");
            $stmt->execute([':id' => $appointmentId, ':client_user_id' => $clientUserId]);
            $currentStatus = $stmt->fetchColumn();

            if ($currentStatus !== 'Beklemede') {
                $pdo->rollBack();
                echo json_encode(['status' => 'error', 'message' => 'Sadece "Beklemede" durumundaki randevular güncellenebilir. Mevcut durum: ' . $currentStatus]);
                exit;
            }

            // 2. Randevu Çakışma Kontrolü (Yeni saat dilimi için, KENDİ randevusu hariç)
            $stmt = $pdo->prepare("
            SELECT id FROM psikolog_randevular 
            WHERE user_id = :psikolog_id 
              AND appointment_date = :appointment_date 
              AND status != 'İptal'
              AND id != :appointment_id  -- KENDİ randevumuz hariç
              AND (
                  (start_time <= :start_time AND end_time > :start_time) OR
                  (start_time < :end_time AND end_time >= :end_time) OR
                  (start_time >= :start_time AND end_time <= :end_time)
              )
        ");
            $stmt->execute([
                ':psikolog_id' => $psikologId,
                ':appointment_date' => $appointmentDate,
                ':start_time' => $startTime,
                ':end_time' => $endTime,
                ':appointment_id' => $appointmentId
            ]);

            if ($stmt->fetch(PDO::FETCH_ASSOC)) {
                $pdo->rollBack();
                echo json_encode(['status' => 'error', 'message' => 'Seçtiğiniz yeni saat dilimi maalesef doludur. Lütfen başka bir saat seçin.']);
                exit;
            }

            // 3. Güncelleme İşlemi
            $stmt = $pdo->prepare("
            UPDATE psikolog_randevular 
            SET appointment_date = :appointment_date, start_time = :start_time, end_time = :end_time, updated_at = CURRENT_TIMESTAMP
            WHERE id = :id
        ");
            $stmt->execute([
                ':appointment_date' => $appointmentDate,
                ':start_time' => $startTime,
                ':end_time' => $endTime,
                ':id' => $appointmentId
            ]);

            $pdo->commit();
            echo json_encode(['status' => 'success', 'message' => 'Randevu tarihi/saati başarıyla güncellendi. Onay bekleniyor.']);
        } catch (PDOException $e) {
            if (isset($pdo) && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log("Randevu Güncelleme Hatası: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Güncelleme sırasında bir veritabanı hatası oluştu.']);
        }
        break;
    case 'cancelAppointmentByClient':
        // Danışanın sadece 'Beklemede' durumundaki randevu talebini iptal eder (durumunu 'İptal' yapar)
        if (empty($_POST['appointment_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Randevu ID zorunludur.']);
            exit;
        }
        if (!isset($_SESSION['id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Oturum bilgisi bulunamadı.']);
            exit;
        }

        $appointmentId = (int)$_POST['appointment_id'];
        $clientUserId = (int)$_SESSION['id'];

        try {
            if (!isset($pdo)) {
                $dbh = new Dbh();
                $pdo = $dbh->connect();
            }

            $pdo->beginTransaction();

            // 1. Yetki ve Durum Kontrolü (Randevu beklemede mi ve bu kullanıcıya mı ait?)
            $stmt = $pdo->prepare("
            SELECT status FROM psikolog_randevular 
            WHERE id = :id AND client_user_id = :client_user_id
        ");
            $stmt->execute([':id' => $appointmentId, ':client_user_id' => $clientUserId]);
            $currentStatus = $stmt->fetchColumn();

            if ($currentStatus !== 'Beklemede') {
                $pdo->rollBack();
                echo json_encode(['status' => 'error', 'message' => 'Sadece "Beklemede" durumundaki randevular iptal edilebilir. Mevcut durum: ' . $currentStatus]);
                exit;
            }

            // 2. Güncelleme İşlemi (Durumu 'İptal' olarak ayarla)
            $stmt = $pdo->prepare("
            UPDATE psikolog_randevular 
            SET status = 'İptal', updated_at = CURRENT_TIMESTAMP
            WHERE id = :id AND client_user_id = :client_user_id
        ");
            $stmt->execute([
                ':id' => $appointmentId,
                ':client_user_id' => $clientUserId
            ]);

            $pdo->commit();
            echo json_encode(['status' => 'success', 'message' => 'Randevu talebiniz başarıyla iptal edildi.']);
        } catch (PDOException $e) {
            if (isset($pdo) && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log("Randevu İptal Hatası: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'İptal işlemi sırasında bir veritabanı hatası oluştu.']);
        }
        break;
    case 'getAppointmentsByPsikolog':
        // Psikologun kendi randevu taleplerini listeler
        if (!isset($_SESSION['id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Oturum hatası.']);
            exit;
        }
        $psikologId = (int)$_SESSION['id'];
        $appointments = [];

        try {
            if (!isset($pdo)) {
                $dbh = new Dbh();
                $pdo = $dbh->connect();
            }

            $stmt = $pdo->prepare("
            SELECT 
                pr.id, 
                 DATE_FORMAT(pr.appointment_date, '%d-%m-%Y') AS appointment_date,
                pr.start_time, 
                pr.end_time, 
                pr.status,
                pr.client_name,
                pr.client_phone 
            FROM psikolog_randevular pr
            WHERE pr.user_id = :psikolog_id
            ORDER BY pr.appointment_date ASC, pr.start_time ASC
        ");
            $stmt->execute([':psikolog_id' => $psikologId]);
            $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(['status' => 'success', 'appointments' => $appointments]);
        } catch (PDOException $e) {
            error_log("Psikolog Randevu Listeleme Hatası: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Randevular listelenirken bir hata oluştu.']);
        }
        break;

    case 'updateAppointmentStatus':
        // Randevu durumunu onaylama veya reddetme
        if (empty($_POST['appointment_id']) || empty($_POST['new_status'])) {
            echo json_encode(['status' => 'error', 'message' => 'Randevu ID ve yeni durum zorunludur.']);
            exit;
        }
        if (!isset($_SESSION['id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Oturum hatası.']);
            exit;
        }

        $appointmentId = (int)$_POST['appointment_id'];
        $psikologId = (int)$_SESSION['id'];
        $newStatus = $_POST['new_status']; // 'Onaylandı' veya 'Reddedildi' beklenir

        // Güvenlik kontrolü
        if (!in_array($newStatus, ['Onaylandı', 'Reddedildi'])) {
            echo json_encode(['status' => 'error', 'message' => 'Geçersiz durum değeri.']);
            exit;
        }

        $appointmentInfo = null; // Randevu bilgilerini tutacak değişken

        try {
            if (!isset($pdo)) {
                $dbh = new Dbh();
                $pdo = $dbh->connect();
            }

            // 1. ADIM: Gerekli bilgileri (client_user_id, appointment_date, start_time) çek
            $stmt_info = $pdo->prepare("
            SELECT client_user_id, appointment_date, start_time 
            FROM psikolog_randevular 
            WHERE id = :id AND user_id = :psikolog_id 
        ");
            $stmt_info->execute([
                ':id' => $appointmentId,
                ':psikolog_id' => $psikologId
            ]);
            $appointmentInfo = $stmt_info->fetch(PDO::FETCH_ASSOC);

            if (!$appointmentInfo) {
                // Eğer randevu bulunamazsa veya durumu 'Beklemede' değilse hata ver
                echo json_encode(['status' => 'error', 'message' => 'Randevu bulunamadı veya sadece "Beklemede" olanlar güncellenebilir.']);
                exit;
            }

            // 2. ADIM: Durum güncellemesini yap
            $stmt = $pdo->prepare("
            UPDATE psikolog_randevular 
            SET status = :new_status, updated_at = CURRENT_TIMESTAMP
            WHERE id = :id AND user_id = :psikolog_id 
        ");
            $stmt->execute([
                ':new_status' => $newStatus,
                ':id' => $appointmentId,
                ':psikolog_id' => $psikologId
            ]);

            if ($stmt->rowCount() > 0) {

                $response = ['status' => 'success', 'message' => 'Randevu başarıyla güncellendi.'];

                // 3. ADIM: Eğer onaylama yapılıyorsa, bilgileri çek ve d-m-y H:i formatında döndür
                if ($newStatus === 'Onaylandı') {

                    // Tarih (YYYY-MM-DD) ve Saati (HH:MM:SS) birleştir
                    $fullDateTime = $appointmentInfo['appointment_date'] . ' ' . $appointmentInfo['start_time'];

                    // DateTime nesnesi oluştur ve istenen d-m-y H:i formatına dönüştür
                    try {
                        $dt = new DateTime($fullDateTime);
                        // Kullanıcının istediği d-m-y H:i formatına dönüştürülüyor
                        $formattedDateTime = $dt->format('d-m-Y H:i');
                    } catch (Exception $e) {
                        // Dönüşüm hatası olursa, veritabanındaki ham tarihi döndür
                        $formattedDateTime = $appointmentInfo['appointment_date'] . ' ' . $appointmentInfo['start_time'];
                    }


                    $response['client_user_id'] = $appointmentInfo['client_user_id'];
                    $response['appointment_date'] = $formattedDateTime; // d-m-y H:i formatında
                } else {
                    // Reddedildiğinde veya güncellenmediğinde boş döndür
                    $response['client_user_id'] = '';
                    $response['appointment_date'] = '';
                }

                echo json_encode($response);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Güncelleme için uygun randevu bulunamadı.']);
            }
        } catch (PDOException $e) {
            error_log("Durum Güncelleme Hatası: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Durum değiştirilirken bir veritabanı hatası oluştu.']);
        }
        break;

    case 'psikologUpdateAndApprove':
        // Psikolog, beklemedeki randevunun saatini değiştirir ve otomatik olarak 'Onaylandı' yapar
        if (empty($_POST['appointment_id']) || empty($_POST['appointment_date']) || empty($_POST['start_time']) || empty($_POST['end_time'])) {
            echo json_encode(['status' => 'error', 'message' => 'Tüm alanlar zorunludur.']);
            exit;
        }
        if (!isset($_SESSION['id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Oturum bilgisi bulunamadı.']);
            exit;
        }

        $appointmentId = (int)$_POST['appointment_id'];
        $psikologId = (int)$_SESSION['id'];
        $appointmentDate = $_POST['appointment_date'];
        $startTime = $_POST['start_time'];
        $endTime = $_POST['end_time'];

        try {
            if (!isset($pdo)) {
                $dbh = new Dbh();
                $pdo = $dbh->connect();
            }
            $pdo->beginTransaction();

            // 1. Durum Kontrolü (Sadece beklemede olan kendi randevusu mu?)
            $stmt = $pdo->prepare("SELECT status FROM psikolog_randevular WHERE id = :id AND user_id = :psikolog_id");
            $stmt->execute([':id' => $appointmentId, ':psikolog_id' => $psikologId]);
            $currentStatus = $stmt->fetchColumn();



            // 2. Çakışma Kontrolü (Yeni saat dilimi için, KENDİ randevusu hariç)
            // Danışan tarafındaki aynı mantık kullanılır.
            $stmt = $pdo->prepare("
            SELECT id FROM psikolog_randevular 
            WHERE user_id = :psikolog_id 
              AND appointment_date = :appointment_date 
              AND status != 'İptal'
              AND id != :appointment_id 
              AND (
                  (start_time <= :start_time AND end_time > :start_time) OR
                  (start_time < :end_time AND end_time >= :end_time) OR
                  (start_time >= :start_time AND end_time <= :end_time)
              )
        ");
            $stmt->execute([
                ':psikolog_id' => $psikologId,
                ':appointment_date' => $appointmentDate,
                ':start_time' => $startTime,
                ':end_time' => $endTime,
                ':appointment_id' => $appointmentId
            ]);

            if ($stmt->fetch(PDO::FETCH_ASSOC)) {
                $pdo->rollBack();
                echo json_encode(['status' => 'error', 'message' => 'Seçtiğiniz yeni saat dilimi başka bir randevu ile çakışıyor.']);
                exit;
            }

            // 3. Güncelleme ve Onaylama İşlemi
            $stmt = $pdo->prepare("
            UPDATE psikolog_randevular 
            SET appointment_date = :appointment_date, 
                start_time = :start_time, 
                end_time = :end_time, 
                status = 'Onaylandı', 
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id AND user_id = :psikolog_id
        ");
            $stmt->execute([
                ':appointment_date' => $appointmentDate,
                ':start_time' => $startTime,
                ':end_time' => $endTime,
                ':id' => $appointmentId,
                ':psikolog_id' => $psikologId
            ]);

            $pdo->commit();
            echo json_encode(['status' => 'success', 'message' => 'Randevu başarıyla güncellendi ve onaylandı.']);
        } catch (PDOException $e) {
            if (isset($pdo) && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log("Psikolog Güncelleme/Onaylama Hatası: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'İşlem sırasında bir veritabanı hatası oluştu.']);
        }
        break;
    case 'tekerlemeList':
        try {
            // 1. Sınıf ID'lerini Sınıf İsimleriyle Eşleştirmek İçin Tüm Sınıfları Çek
            // NOT: Sınıflar tablonuzun adının 'classes_lnp' olduğunu varsayıyorum.
            $class_stmt = $pdo->prepare("SELECT id, name FROM classes_lnp");
            $class_stmt->execute();
            $all_classes = $class_stmt->fetchAll(PDO::FETCH_ASSOC);

            $class_map = [];
            // [10 => '3-4 Yaş', 11 => '4-5 Yaş', ...] şeklinde bir eşleme oluşturur
            foreach ($all_classes as $class) {
                $class_map[$class['id']] = $class['name'];
            }

            // 2. Tekerlemeleri Çek
            $stmt = $pdo->prepare("
            SELECT 
                id, 
                description, 
                class_id, 
                image_path, 
                sound_path,
                status 
            FROM tekerlemeler_lnp
            ORDER BY id DESC
        ");
            $stmt->execute();
            $tekerlemeler = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // 3. class_id'leri class_name'lere dönüştür
            $processed_tekerlemeler = array_map(function ($tekerleme) use ($class_map) {
                $raw_class_ids = $tekerleme['class_id']; // Örn: "10;11;12"
                $class_names = [];

                if (!empty($raw_class_ids)) {
                    $id_array = explode(';', $raw_class_ids);
                    foreach ($id_array as $id) {
                        $id = (int)trim($id); // ID'nin sayısal olduğundan emin ol
                        if (isset($class_map[$id])) {
                            $class_names[] = $class_map[$id];
                        }
                    }
                }

                // Yeni bir alan ekle: class_names (Örn: "3-4 Yaş, 4-5 Yaş, 5-6 Yaş")
                // Datatable'da gösterilecek olan budur.
                $tekerleme['class_names'] = implode(', ', $class_names);

                // class_id alanını da frontend'in güncelleme modalı için koruyoruz.
                return $tekerleme;
            }, $tekerlemeler);


            $response = ['status' => 'success', 'data' => $processed_tekerlemeler];
        } catch (PDOException $e) {
            error_log("Tekerleme Listeleme Hatası: " . $e->getMessage());
            $response = ['status' => 'error', 'message' => 'Tekerlemeler listelenirken bir hata oluştu.'];
        }

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;

        // --- 2. DETAY GÖSTERME İŞLEMİ (READ SINGLE FOR EDIT) ---
    case 'tekerlemeShow':
        $id = $_GET['id'] ?? null;
        if (!$id || !is_numeric($id)) {
            $response = ['status' => 'error', 'message' => 'Geçersiz Tekerleme ID.'];
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        try {
            $stmt = $pdo->prepare("
                SELECT 
                    id, 
                    description, 
                    school_id, 
                    image_path, 
                    sound_path,
                    class_id, 
                    status
                FROM tekerlemeler_lnp
                WHERE id = :id
            ");
            $stmt->execute([':id' => $id]);
            $tekerleme = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($tekerleme) {
                $response = ['status' => 'success', 'data' => $tekerleme];
            } else {
                $response = ['status' => 'error', 'message' => 'Tekerleme bulunamadı.'];
            }
        } catch (PDOException $e) {
            error_log("Tekerleme Detay Hatası: " . $e->getMessage());
            $response = ['status' => 'error', 'message' => 'Detaylar çekilirken bir hata oluştu.'];
        }


        echo json_encode($response);
        exit;

        // --- 3. EKLEME İŞLEMİ (CREATE) ---
    case 'tekerlemeAdd':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $response = ['status' => 'error', 'message' => 'Yalnızca POST isteği kabul edilir.'];

            echo json_encode($response);
            exit;
        }

        // ⭐ YENİ: class_ids alanını zorunlu alanlara ekledik, çünkü sınıfsız tekerleme eklenmemeli.
        $required_fields = ['description', 'class_ids'];
        $missing_field = false;
        foreach ($required_fields as $field) {
            // class_ids için dizi kontrolü de yapıyoruz
            if ($field === 'class_ids' && (!isset($_POST[$field]) || !is_array($_POST[$field]) || empty($_POST[$field]))) {
                $missing_field = true;
                break;
            } elseif (!isset($_POST[$field])) {
                $missing_field = true;
                break;
            }
        }

        if ($missing_field) {
            // Hata mesajını daha açıklayıcı hale getirebiliriz
            $response = ['status' => 'error', 'message' => 'Okul ID, Açıklama ve İlgili Sınıflar alanları zorunludur.'];

            echo json_encode($response);
            exit;
        }

        // Dosya Yükleme İşlemleri (Resim ve Ses)
        $upload_dir = '../uploads/tekerlemeler/'; // Bitiş eğik çizgisini ekledim
        $image_path = null;
        $sound_path = null;
        $file_error = false;

        // Görüntü Dosyası Yükleme (ZORUNLU varsayılmıştır)
        if (isset($_FILES['image_path']) && $_FILES['image_path']['error'] === UPLOAD_ERR_OK) {
            $image_file = $_FILES['image_path'];
            $image_path = $upload_dir . uniqid('img_') . '_' . basename($image_file['name']);
            if (!move_uploaded_file($image_file['tmp_name'], $image_path)) {
                $response = ['status' => 'error', 'message' => 'Görüntü dosyası yüklenirken hata oluştu.'];
                $file_error = true;
            }
        } else {
            $response = ['status' => 'error', 'message' => 'Görüntü dosyası zorunludur.'];
            $file_error = true;
        }

        // Ses Dosyası Yükleme (ZORUNLU varsayılmıştır)
        if (!$file_error && isset($_FILES['sound_path']) && $_FILES['sound_path']['error'] === UPLOAD_ERR_OK) {
            $sound_file = $_FILES['sound_path'];
            $sound_path = $upload_dir . uniqid('snd_') . '_' . basename($sound_file['name']);
            if (!move_uploaded_file($sound_file['tmp_name'], $sound_path)) {
                // Yüklenen resmi sil ve hata mesajı döndür
                if (file_exists($image_path)) unlink($image_path);
                $response = ['status' => 'error', 'message' => 'Ses dosyası yüklenirken hata oluştu.'];
                $file_error = true;
            }
        } else if (!$file_error) {
            // Sadece resim yüklendiyse, onu sil
            if (file_exists($image_path)) unlink($image_path);
            $response = ['status' => 'error', 'message' => 'Ses dosyası zorunludur.'];
            $file_error = true;
        }

        if ($file_error) {

            echo json_encode($response);
            exit;
        }

        // ⭐ YENİ: class_ids dizisini al ve noktalı virgül (;) ile birleştir
        $class_ids_array = $_POST['class_ids'] ?? [];
        // Güvenlik: Sadece sayısal değerleri al ve boş veya 0 olanları filtrele
        $safe_class_ids = array_map(function ($id) {
            return (int)$id;
        }, $class_ids_array);

        // İstenen format: 10;11;12 şeklinde birleştir
        $formatted_class_ids = implode(';', array_filter($safe_class_ids));
        // ⭐ YENİ BİTİŞ

        // Veritabanına Kayıt
        try {

            $description = $_POST['description'];
            // created_at ve updated_at otomatik ayarlanır

            $stmt = $pdo->prepare("
            INSERT INTO tekerlemeler_lnp
                (school_id, class_id, description, image_path, sound_path) /* ⭐ class_id eklendi */
            VALUES 
                (:school_id, :class_id, :description, :image_path, :sound_path) /* ⭐ :class_id eklendi */
        ");
            $stmt->execute([
                ':school_id' => 1,
                ':class_id' => $formatted_class_ids, /* ⭐ Birleştirilmiş sınıf ID'leri bind edildi */
                ':description' => $description,
                ':image_path' => $image_path,
                ':sound_path' => $sound_path
            ]);

            $response = ['status' => 'success', 'message' => 'Tekerleme başarıyla eklendi.'];
        } catch (PDOException $e) {
            error_log("Tekerleme Ekleme Hatası: " . $e->getMessage());
            // Veritabanı hatasında yüklenen dosyaları sil
            if (file_exists($image_path)) unlink($image_path);
            if (file_exists($sound_path)) unlink($sound_path);
            $response = ['status' => 'error', 'message' => 'Veritabanına ekleme yapılırken bir hata oluştu.'];
        }


        echo json_encode($response);
        exit;
    case 'tekerlemeUpdate':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $response = ['status' => 'error', 'message' => 'Yalnızca POST isteği kabul edilir.'];

            echo json_encode($response);
            exit;
        }

        $id = $_POST['id'] ?? null;
        if (!$id || !is_numeric($id)) {
            $response = ['status' => 'error', 'message' => 'Geçersiz Tekerleme ID.'];

            echo json_encode($response);
            exit;
        }

        $school_id = (int)($_POST['school_id'] ?? 1);
        $description = $_POST['description'] ?? '';

        // ⭐ YENİ: class_ids dizisini al ve noktalı virgül (;) ile birleştir
        $class_ids_array = $_POST['class_ids'] ?? [];
        $formatted_class_ids = '';
        if (is_array($class_ids_array) && !empty($class_ids_array)) {
            // Güvenlik: Sadece sayısal değerleri al ve boş veya 0 olanları filtrele
            $safe_class_ids = array_map(function ($id) {
                return (int)$id;
            }, $class_ids_array);

            // İstenen format: 10;11;12 şeklinde birleştir
            $formatted_class_ids = implode(';', array_filter($safe_class_ids));
        }
        // ⭐ YENİ BİTİŞ

        // Mevcut dosya yollarını çek ve koru (Gizli inputlardan geldiği varsayılır)
        $original_image_path = $_POST['original_image_path'] ?? null;
        $original_sound_path = $_POST['original_sound_path'] ?? null;
        $image_path = $original_image_path;
        $sound_path = $original_sound_path;
        $upload_dir = '../uploads/tekerlemeler/';
        $new_image_uploaded = false;
        $new_sound_uploaded = false;
        $file_error = false;


        // Görüntü Dosyası Yükleme (Yeni dosya varsa)
        if (isset($_FILES['image_path']) && $_FILES['image_path']['error'] === UPLOAD_ERR_OK) {
            $image_file = $_FILES['image_path'];
            $image_path = $upload_dir . uniqid('img_') . '_' . basename($image_file['name']);

            if (!move_uploaded_file($image_file['tmp_name'], $image_path)) {
                $response = ['status' => 'error', 'message' => 'Yeni görüntü dosyası yüklenirken hata oluştu.'];
                $file_error = true;
            }
            $new_image_uploaded = true;
        }

        // Ses Dosyası Yükleme (Yeni dosya varsa)
        if (!$file_error && isset($_FILES['sound_path']) && $_FILES['sound_path']['error'] === UPLOAD_ERR_OK) {
            $sound_file = $_FILES['sound_path'];
            $sound_path = $upload_dir . uniqid('snd_') . '_' . basename($sound_file['name']);
            if (!move_uploaded_file($sound_file['tmp_name'], $sound_path)) {
                if ($new_image_uploaded && file_exists($image_path)) unlink($image_path);
                $response = ['status' => 'error', 'message' => 'Yeni ses dosyası yüklenirken hata oluştu.'];
                $file_error = true;
            }
            $new_sound_uploaded = true;
        }

        if ($file_error) {

            echo json_encode($response);
            exit;
        }

        // Veritabanı Güncelleme
        try {
            // updated_at otomatik güncellenecektir.
            $stmt = $pdo->prepare("
            UPDATE tekerlemeler_lnp SET 
                school_id = :school_id, 
                class_id = :class_id,  /* ⭐ class_id alanı eklendi */
                description = :description, 
                image_path = :image_path, 
                sound_path = :sound_path
            WHERE id = :id
        ");
            $stmt->execute([
                ':school_id' => $school_id,
                ':class_id' => $formatted_class_ids, /* ⭐ Birleştirilmiş sınıf ID'leri bind edildi */
                ':description' => $description,
                ':image_path' => $image_path,
                ':sound_path' => $sound_path,
                ':id' => $id
            ]);

            // Eski dosyaları silme (Sadece yeni dosya yüklendiyse ve orijinal dosya varsa)
            if ($new_image_uploaded && $original_image_path && file_exists($original_image_path)) {
                unlink($original_image_path);
            }
            if ($new_sound_uploaded && $original_sound_path && file_exists($original_sound_path)) {
                unlink($original_sound_path);
            }

            $response = ['status' => 'success', 'message' => 'Tekerleme başarıyla güncellendi.'];
        } catch (PDOException $e) {
            error_log("Tekerleme Güncelleme Hatası: " . $e->getMessage());
            // Veritabanı hatasında yeni yüklenen dosyaları sil
            if ($new_image_uploaded && file_exists($image_path)) unlink($image_path);
            if ($new_sound_uploaded && file_exists($sound_path)) unlink($sound_path);
            $response = ['status' => 'error', 'message' => 'Veritabanında güncelleme yapılırken bir hata oluştu.'];
        }


        echo json_encode($response);
        exit;
    case 'tekerlemeDelete':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $response = ['status' => 'error', 'message' => 'Yalnızca POST isteği kabul edilir.'];

            echo json_encode($response);
            exit;
        }

        $id = $_POST['id'] ?? null;
        if (!$id || !is_numeric($id)) {
            $response = ['status' => 'error', 'message' => 'Geçersiz Tekerleme ID.'];

            echo json_encode($response);
            exit;
        }

        // Silmeden önce dosya yollarını al
        try {
            $stmt = $pdo->prepare("SELECT image_path, sound_path FROM tekerlemeler_lnp WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $files = $stmt->fetch(PDO::FETCH_ASSOC);

            // Veritabanından silme işlemi
            $stmt = $pdo->prepare("DELETE FROM tekerlemeler_lnp WHERE id = :id");
            $stmt->execute([':id' => $id]);

            if ($stmt->rowCount() > 0) {
                // Dosyaları sunucudan silme
                if ($files) {
                    if ($files['image_path'] && file_exists($files['image_path'])) unlink($files['image_path']);
                    if ($files['sound_path'] && file_exists($files['sound_path'])) unlink($files['sound_path']);
                }

                $response = ['status' => 'success', 'message' => 'Tekerleme ve ilgili dosyaları başarıyla silindi.'];
            } else {
                $response = ['status' => 'error', 'message' => 'Silinecek tekerleme bulunamadı.'];
            }
        } catch (PDOException $e) {
            error_log("Tekerleme Silme Hatası: " . $e->getMessage());
            $response = ['status' => 'error', 'message' => 'Silme işlemi sırasında bir hata oluştu.'];
        }


        echo json_encode($response);
        exit;
    case 'tekerlemeChangeStatus':
        // Yalnızca POST isteği kabul edilir
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $response = ['status' => 'error', 'message' => 'Yalnızca POST isteği kabul edilir.'];
            echo json_encode($response);
            exit;
        }

        $id = $_POST['id'] ?? null;
        $status = $_POST['status'] ?? null; // Yeni durum (0 veya 1)

        // ID ve Durum kontrolü
        if (!$id || !is_numeric($id) || !in_array($status, [0, 1])) {
            $response = ['status' => 'error', 'message' => 'Geçersiz Tekerleme ID veya Durum değeri.'];
            echo json_encode($response);
            exit;
        }

        try {
            // Durum güncelleme işlemi
            $stmt = $pdo->prepare("UPDATE tekerlemeler_lnp SET status = :status WHERE id = :id");
            $stmt->bindParam(':status', $status, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Güncellenen satır sayısını kontrol et
            if ($stmt->rowCount() > 0) {
                $statusText = $status == 1 ? 'Aktif' : 'Pasif';
                $response = ['status' => 'success', 'message' => "Tekerleme başarıyla {$statusText} yapıldı."];
            } else {
                // Eğer rowCount 0 ise, ya ID bulunamamıştır ya da durum zaten aynıdır.
                // ID'nin varlığını kontrol etmek daha doğru olur, ancak bu durumda başarılı mesajı verebiliriz
                // ya da sadece durumun zaten güncel olduğunu belirtebiliriz.
                $response = ['status' => 'error', 'message' => 'Tekerleme bulunamadı veya durum zaten aynıydı.'];
            }
        } catch (PDOException $e) {
            error_log("Tekerleme Durum Değiştirme Hatası: " . $e->getMessage());
            $response = ['status' => 'error', 'message' => 'Durum değiştirme işlemi sırasında bir veritabanı hatası oluştu.'];
        }


        echo json_encode($response);
        exit;
    case 'sarkiList':
        $stmt = $pdo->query("SELECT * FROM sarkilar_lnp ORDER BY id DESC");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['data' => $data]);
        exit;

    case 'sarkiCreate':
        $title = $_POST['title'] ?? '';
        $youtube_url = $_POST['youtube_url'] ?? '';
        $class_id = implode(';', $_POST['class_id']) . ';';
        $stmt = $pdo->prepare("INSERT INTO sarkilar_lnp (title, class_id, youtube_url, status) VALUES (:title, :class_id, :youtube_url, 1)");
        $stmt->execute(['title' => $title, 'class_id' => $class_id, 'youtube_url' => $youtube_url]);
        echo json_encode(['status' => 'success', 'message' => 'Şarkı eklendi.']);
        exit;

    case 'sarkiGet':
        $id = $_GET['id'] ?? 0;
        $stmt = $pdo->prepare("SELECT * FROM sarkilar_lnp WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($data ? ['status' => 'success', 'data' => $data] : ['status' => 'error', 'message' => 'Kayıt bulunamadı.']);
        exit;

    case 'sarkiUpdate':
        $id = $_POST['id'];
        $title = $_POST['title'];
        $youtube_url = $_POST['youtube_url'];
        $class_id = implode(';', $_POST['class_id']) . ';';
        $stmt = $pdo->prepare("UPDATE sarkilar_lnp SET title=:title, youtube_url=:youtube_url, class_id=:class_id WHERE id=:id");
        $stmt->execute(['title' => $title, 'youtube_url' => $youtube_url, 'class_id' => $class_id, 'id' => $id]);
        echo json_encode(['status' => 'success', 'message' => 'Şarkı güncellendi.']);
        exit;

    case 'sarkiDelete':
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM sarkilar_lnp WHERE id=:id");
        $stmt->execute(['id' => $id]);
        echo json_encode(['status' => 'success', 'message' => 'Şarkı silindi.']);
        exit;

    case 'sarkiStatus':
        $id = $_POST['id'];
        $status = $_POST['status'];
        $stmt = $pdo->prepare("UPDATE sarkilar_lnp SET status=:status WHERE id=:id");
        $stmt->execute(['status' => $status, 'id' => $id]);
        echo json_encode(['status' => 'success', 'message' => 'Durum güncellendi.']);
        exit;
    case 'sanalGezilerList':
        try {
            $stmt = $pdo->query("SELECT id, title, icon, link, class_id, status FROM sanal_geziler_lnp ORDER BY id DESC");
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['data' => $data]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['data' => [], 'error' => 'Listeleme hatası: ' . $e->getMessage()]);
        }
        exit;

    case 'sanalGezilerCreate':

        // 🚨 SABİTLER BURADA TANIMLANDI 🚨
        $UPLOAD_DIR = '../uploads/icons/';
        $MAX_FILE_SIZE = 5 * 1024 * 1024; // 5 MB
        $allowed_mime_types = ['image/jpeg', 'image/png', 'image/svg+xml'];

        $title = trim($_POST['title'] ?? '');
        $link = trim($_POST['link'] ?? '');
        $class_id_array = $_POST['class_id'] ?? [];
        $icon_filename = null;

        // 1. Dosya Yükleme İşlemi
        $file = $_FILES['icon_file'] ?? ['error' => UPLOAD_ERR_NO_FILE, 'size' => 0];
        $is_file_uploaded = false;

        if ($file['error'] !== UPLOAD_ERR_NO_FILE && $file['size'] > 0) {

            // A) Güvenlik ve Boyut Kontrolü
            if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] > $MAX_FILE_SIZE) {
                echo json_encode(['status' => 'error', 'message' => 'Dosya yükleme hatası veya boyutu 512KB\'ı aşıyor.']);
                exit;
            }

            // B) MIME Tipi Kontrolü
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mime_type, $allowed_mime_types)) {
                echo json_encode(['status' => 'error', 'message' => 'Sadece JPG, PNG veya SVG dosyaları yüklenebilir.']);
                exit;
            }

            // C) Dosya Adı Oluşturma ve Taşıma
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $icon_filename = uniqid('icon_', true) . '.' . $extension;
            $destination = $UPLOAD_DIR . $icon_filename;

            if (!is_dir($UPLOAD_DIR)) {
                mkdir($UPLOAD_DIR, 0777, true);
            }

            if (!move_uploaded_file($file['tmp_name'], $destination)) {
                echo json_encode(['status' => 'error', 'message' => 'Dosya sunucuya taşınırken kritik hata oluştu.']);
                exit;
            }
            $is_file_uploaded = true;
        }

        // 2. Veri Kontrolü
        if (empty($title) || empty($link) || empty($class_id_array)) {
            // Yüklenen dosya varsa geri sil
            if ($is_file_uploaded && file_exists($UPLOAD_DIR . $icon_filename)) {
                unlink($UPLOAD_DIR . $icon_filename);
            }
            echo json_encode(['status' => 'error', 'message' => 'Lütfen gerekli tüm alanları doldurun.']);
            exit;
        }

        // Sınıf ID'lerini noktalı virgülle birleştirme
        $class_id = implode(';', $class_id_array) . ';';

        // 3. Veritabanı Kaydı
        try {
            $stmt = $pdo->prepare("INSERT INTO sanal_geziler_lnp (title, icon, link, class_id, status) VALUES (:title, :icon, :link, :class_id, 1)");
            $stmt->execute([
                'title' => $title,
                'icon' => $icon_filename,
                'link' => $link,
                'class_id' => $class_id
            ]);
            echo json_encode(['status' => 'success', 'message' => 'Sanal Gezi başarıyla eklendi.']);
        } catch (PDOException $e) {
            // Veritabanı hatası durumunda dosyayı sil
            if ($is_file_uploaded && file_exists($UPLOAD_DIR . $icon_filename)) {
                unlink($UPLOAD_DIR . $icon_filename);
            }
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Ekleme hatası: ' . $e->getMessage()]);
        }
        exit;

    case 'sanalGezilerGet':
        $id = $_GET['id'] ?? 0;
        try {
            $stmt = $pdo->prepare("SELECT id, title, icon, link, class_id, status FROM sanal_geziler_lnp WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($data) {
                $data['class_id'] = rtrim($data['class_id'], ';');
                echo json_encode(['status' => 'success', 'data' => $data]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Kayıt bulunamadı.']);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Getirme hatası: ' . $e->getMessage()]);
        }
        exit;

    case 'sanalGezilerUpdate':

        // 🚨 SABİTLER BURADA TANIMLANDI 🚨
        $UPLOAD_DIR = '../uploads/icons/';
        $MAX_FILE_SIZE = 5 * 1024 * 1024; // 5 MB
        $allowed_mime_types = ['image/jpeg', 'image/png', 'image/svg+xml'];

        $id = $_POST['id'] ?? 0;
        $title = trim($_POST['title'] ?? '');
        $link = trim($_POST['link'] ?? '');
        $class_id_array = $_POST['class_id'] ?? [];
        $new_icon_filename = null;

        if (empty($id) || empty($title) || empty($link) || empty($class_id_array)) {
            echo json_encode(['status' => 'error', 'message' => 'Lütfen gerekli tüm alanları doldurun.']);
            exit;
        }

        // 1. Mevcut ikon adını çek
        $current_icon = null;
        try {
            $stmt = $pdo->prepare("SELECT icon FROM sanal_geziler_lnp WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $current_icon = $stmt->fetchColumn();
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Mevcut veri çekme hatası.']);
            exit;
        }

        // 2. Yeni Dosya Yükleme İşlemi
        $file = $_FILES['icon_file'] ?? ['error' => UPLOAD_ERR_NO_FILE, 'size' => 0];
        $is_new_file_uploaded = false;

        $final_icon_filename = $current_icon; // Varsayılan olarak mevcut ikon kalır

        if ($file['error'] !== UPLOAD_ERR_NO_FILE && $file['size'] > 0) {
            // A) Güvenlik ve Boyut Kontrolü
            if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] > $MAX_FILE_SIZE) {
                echo json_encode(['status' => 'error', 'message' => 'Yeni ikon boyutu 512KB\'ı aşıyor veya yükleme hatası var.']);
                exit;
            }

            // B) MIME Tipi Kontrolü
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mime_type, $allowed_mime_types)) {
                echo json_encode(['status' => 'error', 'message' => 'Sadece JPG, PNG veya SVG dosyaları yüklenebilir.']);
                exit;
            }

            // C) Dosya Adı Oluşturma ve Taşıma
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $new_icon_filename = uniqid('icon_', true) . '.' . $extension;
            $destination = $UPLOAD_DIR . $new_icon_filename;

            if (!is_dir($UPLOAD_DIR)) {
                mkdir($UPLOAD_DIR, 0777, true);
            }

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $is_new_file_uploaded = true;
                $final_icon_filename = $new_icon_filename;

                // Başarıyla yüklendiyse, eski ikonu sil
                if (!empty($current_icon) && file_exists($UPLOAD_DIR . $current_icon)) {
                    unlink($UPLOAD_DIR . $current_icon);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Yeni ikon sunucuya taşınırken hata oluştu.']);
                exit;
            }
        }

        // Sınıf ID'lerini noktalı virgülle birleştirme
        $class_id = implode(';', $class_id_array) . ';';

        // 3. Veritabanı Güncellemesi
        try {
            $stmt = $pdo->prepare("UPDATE sanal_geziler_lnp SET title=:title, icon=:icon, link=:link, class_id=:class_id WHERE id=:id");
            $stmt->execute([
                'title' => $title,
                'icon' => $final_icon_filename,
                'link' => $link,
                'class_id' => $class_id,
                'id' => $id
            ]);
            echo json_encode(['status' => 'success', 'message' => 'Sanal Gezi başarıyla güncellendi.']);
        } catch (PDOException $e) {
            // Veritabanı hatası olursa, yeni yüklenen dosyayı sil (eğer yeni yükleme yapıldıysa)
            if ($is_new_file_uploaded && file_exists($UPLOAD_DIR . $new_icon_filename)) {
                unlink($UPLOAD_DIR . $new_icon_filename);
            }
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Güncelleme hatası: ' . $e->getMessage()]);
        }
        exit;

    case 'sanalGezilerDelete':

        // 🚨 SABİTLER BURADA TANIMLANDI 🚨
        $UPLOAD_DIR = '../uploads/icons/';

        $id = $_POST['id'] ?? 0;

        // 1. Önce mevcut ikon adını çek (silmek için)
        $current_icon = null;
        try {
            $stmt = $pdo->prepare("SELECT icon FROM sanal_geziler_lnp WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $current_icon = $stmt->fetchColumn();
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Silinecek ikon bilgisini çekme hatası.']);
            exit;
        }

        try {
            // 2. Veritabanından kaydı sil
            $stmt = $pdo->prepare("DELETE FROM sanal_geziler_lnp WHERE id=:id");
            $stmt->execute(['id' => $id]);

            // 3. Dosyayı sil
            if (!empty($current_icon) && file_exists($UPLOAD_DIR . $current_icon)) {
                unlink($UPLOAD_DIR . $current_icon);
            }

            echo json_encode(['status' => 'success', 'message' => 'Sanal Gezi silindi.']);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Veritabanı silme hatası: ' . $e->getMessage()]);
        }
        exit;

    case 'sanalGezilerStatus':
        $id = $_POST['id'] ?? 0;
        $status = $_POST['status'] ?? 0;

        if (!in_array($status, [0, 1])) {
            echo json_encode(['status' => 'error', 'message' => 'Geçersiz durum değeri.']);
            exit;
        }

        try {
            $stmt = $pdo->prepare("UPDATE sanal_geziler_lnp SET status=:status WHERE id=:id");
            $stmt->execute(['status' => $status, 'id' => $id]);
            echo json_encode(['status' => 'success', 'message' => 'Durum güncellendi.']);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Durum güncelleme hatası: ' . $e->getMessage()]);
        }
        exit;

    case 'createAtolyeContent':

        $uploadDir = '../uploads/atolye_content/'; // Bir üst dizinde uploads/atolye_content klasörünü varsayar.

        if (!is_dir($uploadDir)) {
            // Klasör yoksa oluştur ve izinleri ayarla
            if (!mkdir($uploadDir, 0777, true) && !is_dir($uploadDir)) {
                // Klasör oluşturulamazsa hata döndür
                echo json_encode(['status' => 'error', 'message' => 'Yükleme dizini oluşturulamadı. İzinleri kontrol edin.']);
                exit;
            }
        }



        // POST verilerini al
        $subject = $_POST['subject'] ?? null;
        $class_ids = $_POST['class_ids'] ?? null; // Noktalı virgülle ayrılmış ID'ler
        $zoom_url = $_POST['zoom_url'] ?? null;
        $content_type = $_POST['content_type'] ?? null;
        $secim_type = $_POST['secim'] ?? null; // Formdan 'secim' adıyla geliyor, tabloda 'secim_type'
        $video_url = $_POST['video_url'] ?? null;
        $mcontent = $_POST['content'] ?? null; // TinyMCE içeriği
        $descriptions = $_POST['descriptions'] ?? []; // Dosya açıklamaları
        $zoom_date = $_POST['zoom_date'] ?? null;
        $zoom_time = $_POST['zoom_time'] ?? null;

        if ($zoom_date != null && $zoom_time != null) {
            $zoom_date = date('Y-m-d', strtotime($zoom_date));
            $zoom_time = date('H:i:s', strtotime($zoom_time));
            $combinedDateTime = $zoom_date . ' ' . $zoom_time;

            // Fonksiyona gönder
            $getResult = createLiveZoomMeeting($pdo, $subject, $combinedDateTime, $_SESSION['id'],  $class_ids, $_SESSION['role']);
            $zoom_url = $getResult['zoom_join_url'];
        }
        // WordWall verileri
        $wordWallTitles = $_POST['wordWallTitles'] ?? [];
        $wordWallUrls = $_POST['wordWallUrls'] ?? [];


        // Temel Validasyon
        if (empty($subject) || empty($class_ids) || empty($content_type)) {
            echo json_encode(['status' => 'error', 'message' => 'Lütfen zorunlu alanları (Başlık, Yaş Grubu, Tür, İçerik Türü) doldurun.']);
            exit;
        }

        // Veritabanı işlemleri Transaction içine alınır
        try {
            $pdo->beginTransaction();



            $mainInsertSql = "INSERT INTO atolye_contents 
                              (class_ids, subject, zoom_url, zoom_date,zoom_time, content_type, secim_type, content,video_url) 
                              VALUES (:class_ids, :subject, :zoom_url,:zoom_date,:zoom_time, :content_type, :secim_type, :content,:video_url)";

            $stmt = $pdo->prepare($mainInsertSql);
            $stmt->execute([
                'class_ids'    => $class_ids,
                'subject'      => $subject,
                'zoom_url'     => $zoom_url,
                'zoom_date'    => $zoom_date,
                'zoom_time'    => $zoom_time,
                'content_type' => $content_type,
                'secim_type'   => $secim_type,
                'content'      => $mcontent, // Bu alan hem text hem de video linki için kullanılabilir.
                'video_url'      => $video_url
            ]);

            $contentId = $pdo->lastInsertId();


            // 2. Dosya/Görsel Yükleme ve atolye_files_and_images Tablosuna Ekleme
            if ($secim_type === 'file_path' && !empty($_FILES['files']['name'][0])) {
                $fileCount = count($_FILES['files']['name']);

                for ($i = 0; $i < $fileCount; $i++) {

                    $fileName = $_FILES['files']['name'][$i];
                    $fileTmpName = $_FILES['files']['tmp_name'][$i];
                    $fileError = $_FILES['files']['error'][$i];
                    $fileMimeType = $_FILES['files']['type'][$i];
                    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                    $newFileName = uniqid('file_') . '.' . $fileExtension;
                    $fileDestination = $uploadDir . $newFileName;
                    $fileStoragePath = 'uploads/atolye_content/' . $newFileName; // DB'de saklanacak yol

                    $description = $descriptions[$i] ?? '';

                    if ($fileError === 0) {
                        if (move_uploaded_file($fileTmpName, $fileDestination)) {

                            // Dosya türünü belirle ('image' veya 'file' ENUM'u için)
                            $isImage = (strpos($fileMimeType, 'image') !== false || in_array($fileExtension, ['png', 'jpg', 'jpeg', 'gif', 'svg']));
                            $dbFileType = $isImage ? 'image' : 'file';

                            // Veritabanına kaydet
                            $fileInsertSql = "INSERT INTO atolye_files_and_images 
                                              (content_id, file_path, description, type) 
                                              VALUES (:content_id, :file_path, :description, :type)";

                            $stmt = $pdo->prepare($fileInsertSql);
                            $stmt->execute([
                                'content_id'       => $contentId,
                                'file_path'        => $fileStoragePath,
                                'description'      => $description,
                                'type'             => $dbFileType // 'file' veya 'image'
                            ]);
                        } else {
                            $pdo->rollBack();
                            echo json_encode(['status' => 'error', 'message' => "Dosya yükleme başarısız oldu: $fileName. Sunucu izinlerini kontrol edin."]);
                            exit;
                        }
                    } else if ($fileError !== 4) { // Hata kodu 4: Dosya seçilmedi demek. Diğer hataları raporla.
                        $pdo->rollBack();
                        echo json_encode(['status' => 'error', 'message' => "Dosya yükleme hatası ($fileName): Hata kodu $fileError"]);
                        exit;
                    }
                }
            }


            // 3. WordWall İçeriklerini atolye_wordwall_links Tablosuna Ekleme
            if ($secim_type === 'wordwall') {
                $wordWallCount = count($wordWallTitles);
                if ($wordWallCount > 0) {
                    for ($i = 0; $i < $wordWallCount; $i++) {
                        $title = trim($wordWallTitles[$i] ?? '');
                        $url = trim($wordWallUrls[$i] ?? '');

                        if (!empty($title) && !empty($url)) {
                            $wordWallInsertSql = "INSERT INTO atolye_wordwall_links 
                                                  (content_id, title, url) 
                                                  VALUES (:content_id, :title, :url)";
                            $stmt = $pdo->prepare($wordWallInsertSql);
                            $stmt->execute([
                                'content_id' => $contentId,
                                'title'      => $title,
                                'url'        => $url
                            ]);
                        }
                    }
                }
            }


            // Her şey başarılıysa Transaction'ı onayla
            $pdo->commit();
            echo json_encode(['status' => 'success', 'message' => 'Atölye içeriği başarıyla oluşturuldu.', 'id' => $contentId]);
        } catch (PDOException $e) {
            // Hata olursa Transaction'ı geri al
            $pdo->rollBack();
            http_response_code(500);
            // Hata mesajını daha kullanıcı dostu bir şekilde göster (debug için tam mesajı dahil ettim)
            echo json_encode(['status' => 'error', 'message' => 'Veritabanı hatası oluştu. Lütfen tablo/sütun isimlerini kontrol edin. Detay: ' . $e->getMessage()]);
        } catch (Exception $e) {
            $pdo->rollBack();
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Beklenmeyen bir hata oluştu: ' . $e->getMessage()]);
        }
        exit;
    case 'deleteAtolyeFile':
        $uploadDir = '../uploads/atolye_content/';
        $fileId = filter_input(INPUT_POST, 'file_id', FILTER_VALIDATE_INT);
        $contentId = filter_input(INPUT_POST, 'content_id', FILTER_VALIDATE_INT);

        if (!$fileId || !$contentId) {
            echo json_encode(['status' => 'error', 'message' => 'Dosya ID veya İçerik ID eksik.']);
            exit;
        }

        $pdo->beginTransaction();

        try {
            // --- 1. Silinecek dosya yolunu veritabanından çek ---
            $selectSql = "SELECT file_path FROM atolye_files_and_images WHERE id = :file_id AND content_id = :content_id";
            $stmt = $pdo->prepare($selectSql);
            $stmt->execute(['file_id' => $fileId, 'content_id' => $contentId]);
            $fileData = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$fileData) {
                throw new Exception('Dosya veritabanında bulunamadı veya bu içeriğe ait değil.', 404);
            }

            $filePathInDb = $fileData['file_path'];
            // Fiziksel dosya yolunu oluştur
            $fullPath = $uploadDir . $filePathInDb;

            // --- 2. Dosya kaydını veritabanından sil ---
            $deleteDbSql = "DELETE FROM atolye_files_and_images WHERE id = :file_id AND content_id = :content_id";
            $stmt = $pdo->prepare($deleteDbSql);
            $dbDeleteSuccess = $stmt->execute(['file_id' => $fileId, 'content_id' => $contentId]);

            if (!$dbDeleteSuccess) {
                throw new Exception('Dosya kaydı veritabanından silinirken hata oluştu.', 500);
            }

            // --- 3. Fiziki dosyayı sunucudan sil ---
            // Kontrol: Dosya yolu geçerli ve mevcut mu?
            if (file_exists($fullPath)) {
                if (!unlink($fullPath)) {
                    // Silme hatası durumunda işlemi geri al ve hata fırlat
                    throw new Exception('Fiziksel dosya sunucudan silinirken hata oluştu. (Lütfen manuel kontrol edin)', 500);
                }
            } else {
                // Dosya fiziki olarak yoksa hata vermeyiz, sadece loglayabiliriz (Şimdilik devam ediyoruz)
                // throw new Exception('Uyarı: Fiziki dosya bulunamadı, sadece DB kaydı silindi.', 200);
            }

            // Her şey başarılıysa Transaction'ı onayla
            $pdo->commit();
            echo json_encode(['status' => 'success', 'message' => 'Dosya başarıyla silindi.']);
        } catch (Exception $e) {
            // Hata durumunda Transaction'ı geri al
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            // Hata mesajını AJAX'a geri gönder

            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            exit;
        }

        break;
    case 'updateAtolyeContent':
        $uploadDir = '../uploads/atolye_content/';
        // POST verilerini al ve temizle
        $contentId = filter_var($_POST['content_id'] ?? null, FILTER_VALIDATE_INT);
        $subject = filter_var($_POST['subject'] ?? null, FILTER_SANITIZE_STRING);
        $class_ids = filter_var($_POST['class_ids'] ?? null, FILTER_SANITIZE_STRING);
        $zoom_url = filter_var($_POST['zoom_url'] ?? null, FILTER_SANITIZE_URL);
        $content_type = filter_var($_POST['content_type'] ?? null, FILTER_SANITIZE_STRING);
        $secim_type = filter_var($_POST['secim'] ?? null, FILTER_SANITIZE_STRING);
        $content = $_POST['content'] ?? null; // Metin editöründen gelen içerik veya video linki
        $video_url = $_POST['video_url'] ?? null;
        $wordWallTitles = $_POST['wordWallTitles'] ?? [];
        $wordWallUrls = $_POST['wordWallUrls'] ?? [];

        // Yüklenecek dosyalar ve açıklamaları
        $newFiles = $_FILES['files'] ?? null;
        $newFileDescriptions = $_POST['descriptions'] ?? [];

        // Temel Validasyon
        if (empty($contentId) || empty($subject) || empty($class_ids) || empty($content_type)) {
            throw new Exception('Lütfen zorunlu alanları (ID, Başlık, Yaş Grubu, Tür, İçerik Türü) doldurun.', 400);
        }



        // Transaction Başlatma
        $pdo->beginTransaction();

        try {
            // --- 1. ANA İÇERİK GÜNCELLEMESİ (atolye_contents) ---
            $mainUpdateSql = "UPDATE atolye_contents SET 
                                    class_ids = :class_ids, 
                                    subject = :subject, 
                                    zoom_url = :zoom_url, 
                                    content_type = :content_type, 
                                    secim_type = :secim_type, 
                                    content = :content,
                                    video_url = :video_url,
                                    updated_at = NOW()
                                WHERE id = :id";

            $stmt = $pdo->prepare($mainUpdateSql);
            $updateSuccess = $stmt->execute([
                'id'            => $contentId,
                'class_ids'     => $class_ids,
                'subject'       => $subject,
                'zoom_url'      => $zoom_url,
                'content_type'  => $content_type,
                'secim_type'    => $secim_type,
                'content'       => $content,
                'video_url'       => $video_url
            ]);

            if (!$updateSuccess) {
                throw new Exception('Ana içerik veritabanında güncellenemedi (SQL Hatası).', 500);
            }

            // --- 2. WORDWALL LİNKLERİNİN GÜNCELLEMESİ ---
            if ($secim_type === 'wordwall') {
                // a) Eski linkleri sil
                $deleteWordWallSql = "DELETE FROM atolye_wordwall_links WHERE content_id = :content_id";
                $stmt = $pdo->prepare($deleteWordWallSql);
                $stmt->execute(['content_id' => $contentId]);

                // b) Yeni linkleri ekle
                $wordWallInsertSql = "INSERT INTO atolye_wordwall_links (content_id, title, url) VALUES (:content_id, :title, :url)";
                $stmt = $pdo->prepare($wordWallInsertSql);

                for ($i = 0; $i < count($wordWallUrls); $i++) {
                    $title = filter_var(trim($wordWallTitles[$i] ?? ''), FILTER_SANITIZE_STRING);
                    $url = filter_var(trim($wordWallUrls[$i] ?? ''), FILTER_SANITIZE_URL);

                    if (!empty($url) && !empty($title)) {
                        $stmt->execute([
                            'content_id' => $contentId,
                            'title'      => $title,
                            'url'        => $url
                        ]);
                    }
                }
            }

            // --- 3. YENİ DOSYALARI YÜKLEME VE KAYDETME (Manuel) ---
            if ($secim_type === 'file_path' && $newFiles && $newFiles['name'][0] != '') {
                $fileInsertSql = "INSERT INTO atolye_files_and_images (content_id, file_path, description) VALUES (:content_id, :file_path, :description)";
                $stmt = $pdo->prepare($fileInsertSql);
                $uploadedFiles = []; // Geri alma durumunda silmek için

                foreach ($newFiles['name'] as $index => $fileName) {
                    // Sadece geçerli yüklemeleri işle
                    if ($newFiles['error'][$index] === UPLOAD_ERR_OK) {
                        $tmpName = $newFiles['tmp_name'][$index];
                        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                        $safeFileName = uniqid('file_', true) . '.' . $fileExtension;
                        $targetPath = $uploadDir . $safeFileName;

                        // Dosyayı sunucuya taşı
                        if (move_uploaded_file($tmpName, $targetPath)) {
                            $uploadedFiles[] = $targetPath; // Başarılı yüklemeyi kaydet

                            $description = filter_var(trim($newFileDescriptions[$index] ?? ''), FILTER_SANITIZE_STRING);

                            // Veritabanına kaydet
                            $dbSaveSuccess = $stmt->execute([
                                'content_id' => $contentId,
                                'file_path'  => str_replace('../', '', $targetPath), // Veritabanına göreceli yol kaydet
                                'description' => $description
                            ]);

                            if (!$dbSaveSuccess) {
                                // Veritabanı hatası, geri alma işlemini tetikler
                                throw new Exception('Yeni dosya veritabanına kaydedilemedi.', 500);
                            }
                        } else {
                            // move_uploaded_file hatası, geri alma işlemini tetikler
                            throw new Exception('Dosya sunucuya taşınamadı: ' . $fileName, 500);
                        }
                    } elseif ($newFiles['error'][$index] !== UPLOAD_ERR_NO_FILE) {
                        // Diğer yükleme hatalarını yakala
                        throw new Exception('Dosya yükleme hatası: ' . $fileName . ' (Kod: ' . $newFiles['error'][$index] . ')', 500);
                    }
                }
            }


            // Her şey başarılıysa Transaction'ı onayla
            $pdo->commit();
            echo json_encode(['status' => 'success', 'message' => 'Atölye içeriği ve tüm ilgili veriler başarıyla güncellendi!']);
        } catch (Exception $e) {
            // Hata durumunda Transaction'ı geri al
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            // Eğer dosyalar yüklendiyse, onları da sil
            if (isset($uploadedFiles) && !empty($uploadedFiles)) {
                foreach ($uploadedFiles as $filePath) {
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }

            // Hata mesajını AJAX'a geri gönder

            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }

        break;
    case 'atolyeContentStatusChange':


        if (!isset($_POST['id']) || !isset($_POST['status'])) {
            echo json_encode(['status' => 'error', 'message' => 'Eksik parametreler (ID veya Durum).']);
            exit;
        }

        // 2. Veri Doğrulama ve Temizleme
        $contentId = filter_var($_POST['id'], FILTER_VALIDATE_INT);
        $newStatus = filter_var($_POST['status'], FILTER_VALIDATE_INT);

        if ($contentId === false || $newStatus === false || ($newStatus !== 0 && $newStatus !== 1)) {
            echo json_encode(['status' => 'error', 'message' => 'Geçersiz ID veya Durum değeri.']);
            exit;
        }

        try {
            // 3. Veritabanı Güncelleme
            $updateSql = "UPDATE atolye_contents SET status = :status WHERE id = :id";
            $stmt = $pdo->prepare($updateSql);

            $stmt->execute([
                ':status' => $newStatus,
                ':id'     => $contentId
            ]);

            // Güncelleme başarılı mı? (En az 1 satır etkilendiyse)
            if ($stmt->rowCount() > 0) {
                $statusText = ($newStatus == 1) ? 'Aktif' : 'Pasif';
                echo json_encode([
                    'status' => 'success',
                    'message' => 'İçerik başarıyla **' . $statusText . '** hale getirildi.'
                ]);
            } else {
                // Eğer satır etkilenmediyse, ya ID bulunamadı ya da durum zaten aynıydı.
                echo json_encode([
                    'status' => 'error',
                    'message' => 'İçerik durumu güncellenemedi. ID\'yi kontrol edin veya durum zaten ayarlanmış olabilir.'
                ]);
            }
        } catch (PDOException $e) {
            http_response_code(500); // Sunucu Hatası
            // Hata mesajını loglayın ve kullanıcıya genel bir hata mesajı gösterin
            error_log("Atolye Status Change PDO Error: " . $e->getMessage());
            echo json_encode([
                'status' => 'error',
                'message' => 'Veritabanı hatası oluştu. Lütfen logları ve sorguyu kontrol edin.'
            ]);
        } catch (Exception $e) {
            http_response_code(500); // Beklenmeyen Hata
            error_log("Atolye Status Change Unexpected Error: " . $e->getMessage());
            echo json_encode([
                'status' => 'error',
                'message' => 'Beklenmeyen bir sunucu hatası oluştu.'
            ]);
        }
        exit;
    case 'kulupEkle':
        $uploadDir = '../uploads/club_covers/';
        // POST verilerini al (FormData ile gönderildiği için doğrudan $_POST kullanılır)
        $class_ids = $_POST['class_ids'] ?? null; // Noktalı virgülle ayrılmış ID'ler
        $name_tr = $_POST['name_tr'] ?? null;
        $name_en = $_POST['name_en'] ?? null;
        // cover_img dosyası $_FILES üzerinden alınır

        // Temel Validasyon
        if (empty($name_tr) || empty($name_en) || empty($class_ids)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Lütfen tüm zorunlu alanları (Kulüp Adı TR/EN, Sınıflar) doldurun.']);
            exit;
        }

        $cover_img_path = null;

        // Dosya Yükleme İşlemi (cover_img)
        if (!empty($_FILES['cover_img']['name'])) {
            $file = $_FILES['cover_img'];
            $fileTmpName = $file['tmp_name'];
            $fileError = $file['error'];
            $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            $allowedExtensions = ['png', 'jpg', 'jpeg'];

            if ($fileError === 0) {
                if (in_array($fileExtension, $allowedExtensions)) {
                    $newFileName = uniqid('club_') . '.' . $fileExtension;
                    $fileDestination = $uploadDir . $newFileName;
                    $cover_img_path = 'uploads/club_covers/' . $newFileName; // DB'de saklanacak yol

                    if (!move_uploaded_file($fileTmpName, $fileDestination)) {
                        http_response_code(500);
                        echo json_encode(['status' => 'error', 'message' => 'Kapak görseli yüklenirken hata oluştu. Sunucu izinlerini kontrol edin.']);
                        exit;
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(['status' => 'error', 'message' => 'Kapak görseli için sadece PNG, JPG ve JPEG dosyaları izinlidir.']);
                    exit;
                }
            } else if ($fileError !== 4) { // Hata kodu 4: Dosya seçilmedi demek. Diğer hataları raporla.
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => "Kapak görseli yükleme hatası: Hata kodu {$fileError}"]);
                exit;
            }
        }


        // Veritabanı işlemleri Transaction içine alınır
        try {
            $pdo->beginTransaction();

            $insertSql = "INSERT INTO konusma_kulupleri_lnp 
                          (class_ids, name_tr, name_en, cover_img, created_by) 
                          VALUES (:class_ids, :name_tr, :name_en, :cover_img, :created_by)";

            $stmt = $pdo->prepare($insertSql);
            $stmt->execute([
                'class_ids' => $class_ids,
                'name_tr'   => $name_tr,
                'name_en'   => $name_en,
                'cover_img' => $cover_img_path,
                'created_by' => $_SESSION['id'] ?? 0 // Oturumdan kullanıcı ID'sini al
            ]);

            $clubId = $pdo->lastInsertId();

            $pdo->commit();
            echo json_encode(['status' => 'success', 'message' => 'Kulüp başarıyla eklendi.', 'id' => $clubId]);
        } catch (PDOException $e) {
            $pdo->rollBack();
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Veritabanı hatası oluştu. Lütfen tablo/sütun isimlerini kontrol edin. Detay: ' . $e->getMessage()]);
        } catch (Exception $e) {
            $pdo->rollBack();
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Beklenmeyen bir hata oluştu: ' . $e->getMessage()]);
        }
        exit;

        // YENİ SERVİS: KULÜP GÜNCELLEME
    case 'kulupGuncelle':
        $uploadDir = '../uploads/club_covers/';
        // POST verilerini al
        $club_id = $_POST['club_id'] ?? null;
        $class_ids = $_POST['class_ids'] ?? null; // Noktalı virgülle ayrılmış ID'ler
        $name_tr = $_POST['name_tr'] ?? null;
        $name_en = $_POST['name_en'] ?? null;
        $existing_cover_img = $_POST['existing_cover_img'] ?? null; // Mevcut görsel yolu

        // Temel Validasyon
        if (empty($club_id) || empty($name_tr) || empty($name_en) || empty($class_ids)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Lütfen tüm zorunlu alanları (ID, Kulüp Adı TR/EN, Sınıflar) doldurun.']);
            exit;
        }

        $cover_img_path = $existing_cover_img; // Varsayılan olarak mevcut yolu koru

        // Dosya Yükleme İşlemi (cover_img)
        if (!empty($_FILES['cover_img']['name'])) {
            $file = $_FILES['cover_img'];
            $fileTmpName = $file['tmp_name'];
            $fileError = $file['error'];
            $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            $allowedExtensions = ['png', 'jpg', 'jpeg'];

            if ($fileError === 0) {
                if (in_array($fileExtension, $allowedExtensions)) {

                    // Eski dosyayı sil
                    if ($existing_cover_img && file_exists('../' . $existing_cover_img)) {
                        unlink('../' . $existing_cover_img);
                    }

                    $newFileName = uniqid('club_') . '.' . $fileExtension;
                    $fileDestination = $uploadDir . $newFileName;
                    $cover_img_path = 'uploads/club_covers/' . $newFileName; // DB'de saklanacak yol

                    if (!move_uploaded_file($fileTmpName, $fileDestination)) {
                        http_response_code(500);
                        echo json_encode(['status' => 'error', 'message' => 'Yeni kapak görseli yüklenirken hata oluştu.']);
                        exit;
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(['status' => 'error', 'message' => 'Kapak görseli için sadece PNG, JPG ve JPEG dosyaları izinlidir.']);
                    exit;
                }
            } else if ($fileError !== 4) {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => "Kapak görseli yükleme hatası: Hata kodu {$fileError}"]);
                exit;
            }
        }


        // Veritabanı işlemleri Transaction içine alınır
        try {
            $pdo->beginTransaction();

            $updateSql = "UPDATE konusma_kulupleri_lnp SET 
                          class_ids = :class_ids, 
                          name_tr = :name_tr, 
                          name_en = :name_en, 
                          cover_img = :cover_img 
                          WHERE id = :club_id";

            $stmt = $pdo->prepare($updateSql);
            $stmt->execute([
                'club_id'   => $club_id,
                'class_ids' => $class_ids,
                'name_tr'   => $name_tr,
                'name_en'   => $name_en,
                'cover_img' => $cover_img_path
            ]);

            $pdo->commit();
            echo json_encode(['status' => 'success', 'message' => "Kulüp ({$club_id} ID) başarıyla güncellendi."]);
        } catch (PDOException $e) {
            $pdo->rollBack();
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Veritabanı hatası oluştu. Lütfen tablo/sütun isimlerini kontrol edin. Detay: ' . $e->getMessage()]);
        } catch (Exception $e) {
            $pdo->rollBack();
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Beklenmeyen bir hata oluştu: ' . $e->getMessage()]);
        }
        exit;

        // YENİ SERVİS: KULÜP DURUM DEĞİŞTİRME
    case 'kulupStatusChange':
        $club_id = $_POST['id'] ?? null;
        $status = $_POST['status'] ?? null; // 0 veya 1

        if (empty($club_id) || !is_numeric($status) || ($status != 0 && $status != 1)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Geçersiz kulüp ID veya durum değeri.']);
            exit;
        }

        try {
            $updateSql = "UPDATE konusma_kulupleri_lnp SET status = :status WHERE id = :id";
            $stmt = $pdo->prepare($updateSql);
            $stmt->execute(['id' => $club_id, 'status' => $status]);

            $statusText = $status == 1 ? 'Aktif' : 'Pasif';
            echo json_encode(['status' => 'success', 'message' => "Kulüp durumu başarıyla '{$statusText}' olarak değiştirildi."]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Durum değiştirilirken veritabanı hatası oluştu. Detay: ' . $e->getMessage()]);
        }
        exit;


    case 'clupContentCreate':

        // DİKKAT: Upload dizinini kontrol et ve ayarla
        $uploadDir = '../uploads/kulup_content/'; // Kulüp içerikleri için yeni bir dizin önerilir
        $dbPathPrefix = 'uploads/kulup_content/'; // DB'de saklanacak prefix

        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0777, true) && !is_dir($uploadDir)) {
                echo json_encode(['status' => 'error', 'message' => 'Yükleme dizini oluşturulamadı. İzinleri kontrol edin.']);
                exit;
            }
        }

        // POST ve FILES verilerini al
        $subject = $_POST['subject'] ?? null;
        $class_ids = $_POST['class_ids'] ?? null; // Noktalı virgülle ayrılmış ID'ler (örn: 10;11;12)
        $content_type = $_POST['content_type'] ?? null; // konusma_kulupleri_lnp.id (Main Club ID)
        $zoom_date = $_POST['zoom_date'] ?? null;
        $zoom_time = $_POST['zoom_time'] ?? null;
        $zoom_join_url = null;
        $zoom_start_url = null;
        if ($zoom_date != null && $zoom_time != null) {
            $zoom_date = date('Y-m-d', strtotime($zoom_date));
            $zoom_time = date('H:i:s', strtotime($zoom_time));
            $combinedDateTime = $zoom_date . ' ' . $zoom_time;

            // Fonksiyona gönder
            $getResult = createLiveZoomMeeting($pdo, $subject, $combinedDateTime, $_SESSION['id'],  $class_ids, $_SESSION['role']);
            $zoom_start_url = $getResult['zoom_start_url'];
            $zoom_join_url = $getResult['zoom_join_url'];
        }

        $coverImgFile = $_FILES['cover_img'] ?? null;
        $multiFiles = $_FILES['multi_files'] ?? null; // Çoklu dosyalar

        // 1. Zorunlu Alan Validasyonu
        if (empty($subject) || empty($class_ids) || empty($content_type) ) {
            echo json_encode(['status' => 'error', 'message' => 'Lütfen zorunlu alanları (Başlık, Yaş Grubu, Kulüp Türü ) doldurun.']);
            exit;
        }

        // 2. Kapak Resmi Yükleme İşlemi
        $coverImgPath = null;
        if ($coverImgFile!=null && $coverImgFile['error'] === UPLOAD_ERR_OK) {
            $imgExtension = strtolower(pathinfo($coverImgFile['name'], PATHINFO_EXTENSION));
            $newImgName = uniqid('cover_') . '.' . $imgExtension;
            $imgDestination = $uploadDir . $newImgName;

            if (move_uploaded_file($coverImgFile['tmp_name'], $imgDestination)) {
                $coverImgPath = $dbPathPrefix . $newImgName; // DB için yolu ayarla
            } else {
                echo json_encode(['status' => 'error', 'message' => "Kapak resmi yükleme başarısız oldu. Sunucu izinlerini kontrol edin."]);
                exit;
            }
        } 

        // 3. Tarih/Saat Formatlama (Eğer boş değilse)
        if (!empty($zoom_date) && !empty($zoom_time)) {
            $zoom_date = date('Y-m-d', strtotime($zoom_date));
            $zoom_time = date('H:i:s', strtotime($zoom_time));
        } else {
            $zoom_date = null;
            $zoom_time = null;
        }

        // 4. Transaction Başlat
        try {
            $pdo->beginTransaction();

            // ----------------------------------------------------------------------
            // A) konusma_kulupleri_zoom_lnp Tablosuna Kayıt (Ana İçerik Kaydı)
            // ----------------------------------------------------------------------
            // Not: Burada 'title' alanına $subject'i, 'konusma_kulup_id' alanına $content_type'u (Kulüp ID) ekliyoruz.
            $zoomInsertSql = "INSERT INTO konusma_kulupleri_zoom_lnp 
                            (konusma_kulup_id, class_ids, title, zoom_date, zoom_time,zoom_start_url,zoom_join_url, cover_img, status) 
                            VALUES (:konusma_kulup_id, :class_ids, :title, :zoom_date, :zoom_time, :zoom_start_url,:zoom_join_url, :cover_img, 1)";

            $stmt = $pdo->prepare($zoomInsertSql);
            $stmt->execute([
                'konusma_kulup_id' => $content_type, // Kulüp Türü ID'si
                'class_ids'        => $class_ids,    // Yaş Grubu ID'leri (10;11;12)
                'title'            => $subject,      // Konu Başlığı
                'zoom_date'        => $zoom_date,    // Tarih (YYYY-MM-DD)
                'zoom_time'        => $zoom_time,    // Saat (HH:MM:SS)
                'zoom_start_url'        => $zoom_start_url,    // Zoom URL
                'zoom_join_url'        => $zoom_join_url,    // Zoom URL
                'cover_img'        => $coverImgPath  // Kapak Resmi Yolu
            ]);

            $konusmaKulupZoomId = $pdo->lastInsertId();


            // ----------------------------------------------------------------------
            // B) konusma_kulupleri_files_lnp Tablosuna Kayıt (Çoklu Dosyalar)
            // ----------------------------------------------------------------------
            if ($multiFiles && isset($multiFiles['name']) && count($multiFiles['name']) > 0) {
                $fileCount = count($multiFiles['name']);

                for ($i = 0; $i < $fileCount; $i++) {
                    // Sadece hata kodu 0 (UPLOAD_ERR_OK) olanları işlemeye devam et
                    if ($multiFiles['error'][$i] === UPLOAD_ERR_OK) {
                        $fileName = $multiFiles['name'][$i];
                        $fileTmpName = $multiFiles['tmp_name'][$i];
                        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                        $newFileName = uniqid('file_') . '.' . $fileExtension;
                        $fileDestination = $uploadDir . $newFileName;
                        $fileStoragePath = $dbPathPrefix . $newFileName; // DB'de saklanacak yol

                        if (move_uploaded_file($fileTmpName, $fileDestination)) {

                            // Veritabanına kaydet
                            $fileInsertSql = "INSERT INTO konusma_kulupleri_files_lnp 
                                          (konusma_kulupleri_zoom_id, file_path) 
                                          VALUES (:zoom_id, :file_path)";

                            $stmt = $pdo->prepare($fileInsertSql);
                            $stmt->execute([
                                'zoom_id'    => $konusmaKulupZoomId,
                                'file_path'  => $fileStoragePath
                            ]);
                        } else {
                            // Dosya yükleme hatasında rollback yap
                            $pdo->rollBack();
                            echo json_encode(['status' => 'error', 'message' => "Dosya yükleme başarısız oldu: $fileName. Sunucu izinlerini kontrol edin."]);
                            exit;
                        }
                    } else if ($multiFiles['error'][$i] !== UPLOAD_ERR_NO_FILE) {
                        // Diğer dosya hatalarını raporla (Dosya seçilmeme hatası hariç)
                        $pdo->rollBack();
                        echo json_encode(['status' => 'error', 'message' => "Dosya yükleme hatası ($fileName): Hata kodu " . $multiFiles['error'][$i]]);
                        exit;
                    }
                }
            }

            // 5. Her şey başarılıysa Transaction'ı onayla
            $pdo->commit();
            echo json_encode(['status' => 'success', 'message' => 'Kulüp İçeriği başarıyla oluşturuldu.', 'id' => $konusmaKulupZoomId]);
        } catch (PDOException $e) {
            // Hata olursa Transaction'ı geri al
            $pdo->rollBack();
            http_response_code(500);
            // Debug için detaylı, kullanıcıya gösterim için sade mesaj
            error_log("Veritabanı Hatası (clupContentCreate): " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Veritabanı hatası oluştu. Lütfen tablo/sütun isimlerini kontrol edin.', 'debug' => $e->getMessage()]);
        } catch (Exception $e) {
            $pdo->rollBack();
            http_response_code(500);
            error_log("Genel Hata (clupContentCreate): " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Beklenmeyen bir hata oluştu.']);
        }
        exit;
        break;
    case 'clubContentStatusChange':
        // Gerekli sınıfı ve PDO bağlantısını varsayıyoruz.
        // $pdo ve $contentList nesnelerinin tanımlı olduğunu varsayıyoruz.

        $contentId = $_POST['id'] ?? null;
        $newStatus = $_POST['status'] ?? null;

        if (!is_numeric($contentId) || !in_array($newStatus, [0, 1])) {
            echo json_encode(['status' => 'error', 'message' => 'Geçersiz ID veya durum bilgisi.']);
            exit;
        }

        try {
            $updateSql = "UPDATE konusma_kulupleri_zoom_lnp SET status = :status WHERE id = :id";
            $stmt = $pdo->prepare($updateSql);
            $stmt->execute([':status' => $newStatus, ':id' => $contentId]);

            if ($stmt->rowCount() > 0) {
                $statusText = ($newStatus == 1) ? 'Aktif' : 'Pasif';
                echo json_encode(['status' => 'success', 'message' => "İçerik başarıyla $statusText yapıldı."]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'İçerik bulunamadı veya durum zaten günceldi.']);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
        }
        exit;

        // includes/ajax.php içerisindeki switch bloğuna ekle
    case 'clubContentUpdate':

        // Yükleme Ayarları
        $uploadDir = '../uploads/kulup_content/';
        $dbPathPrefix = 'uploads/kulup_content/';
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];

        // 0. Yükleme Dizini Kontrolü
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // POST ve FILES verilerini al
        $contentId = $_POST['id'] ?? null;
        $subject = $_POST['subject'] ?? null;
        $class_ids = $_POST['class_ids'] ?? null;
        $content_type = $_POST['content_type'] ?? null;
        $zoom_date = $_POST['zoom_date'] ?? null;
        $zoom_time = $_POST['zoom_time'] ?? null;

        $zoom_join_url = null;
        $zoom_start_url = null;

        $coverImgFile = $_FILES['cover_img'] ?? null;
        $multiFiles = $_FILES['multi_files'] ?? null;

        // 1. Zorunlu Alan Validasyonu
        if (empty($contentId) || empty($subject) || empty($class_ids) || empty($content_type)) {
            echo json_encode(['status' => 'error', 'message' => 'Lütfen zorunlu alanları (ID, Başlık, Yaş Grubu, Kulüp Türü) doldurun.']);
            exit;
        }

        // 2. Tarih/Saat Formatlama ve Zoom Entegrasyonu (Fonksiyon Kullanımı ŞİDDETLE TAVSİYE EDİLİR)
        $zoom_date_formatted = null;
        $zoom_time_formatted = null;

        if (!empty($zoom_date) && !empty($zoom_time)) {
            $zoom_date_formatted = date('Y-m-d', strtotime($zoom_date));
            $zoom_time_formatted = date('H:i:s', strtotime($zoom_time));
            $combinedDateTime = $zoom_date_formatted . ' ' . $zoom_time_formatted;


            $getResult = createLiveZoomMeeting($pdo, $subject, $combinedDateTime, $_SESSION['id'], $class_ids, $_SESSION['role'], $contentId);

            if (isset($getResult['error'])) {
                echo json_encode(['status' => 'error', 'message' => 'Zoom Toplantısı oluşturulurken/güncellenirken hata oluştu: ' . $getResult['error']]);
                exit;
            }

            $zoom_start_url = $getResult['zoom_start_url'] ?? null;
            $zoom_join_url = $getResult['zoom_join_url'] ?? null;
        }

        // 3. KAPAK RESMİ YÜKLEME (Fonksiyonsuz Mantık)
        $updateCoverImgSqlPart = "";
        $coverImgPath = null;

        if ($coverImgFile && $coverImgFile['error'] === UPLOAD_ERR_OK) {

            $fileExtension = strtolower(pathinfo($coverImgFile['name'], PATHINFO_EXTENSION));
            if (!in_array($fileExtension, $allowedExtensions)) {
                echo json_encode(['status' => 'error', 'message' => 'Kapak resmi için geçersiz dosya türü.']);
                exit;
            }

            $newFileName = uniqid('cover_', true) . '.' . $fileExtension;
            $targetPath = $uploadDir . $newFileName;

            if (move_uploaded_file($coverImgFile['tmp_name'], $targetPath)) {
                $coverImgPath = $dbPathPrefix . $newFileName;
                $updateCoverImgSqlPart = ", cover_img = :cover_img";
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Kapak resmi yüklenirken taşıma hatası oluştu.']);
                exit;
            }
        }

        // 4. Transaction Başlat
        try {
            $pdo->beginTransaction();

            // ----------------------------------------------------------------------
            // A) konusma_kulupleri_zoom_lnp Tablosunu Güncelleme (Ana İçerik Güncelleme)
            // ----------------------------------------------------------------------
            $zoomUpdateSql = "UPDATE konusma_kulupleri_zoom_lnp SET
                            konusma_kulup_id = :konusma_kulup_id,
                            class_ids = :class_ids,
                            title = :title,
                            zoom_date = :zoom_date,
                            zoom_time = :zoom_time,
                            zoom_start_url = :zoom_start_url,
                            zoom_join_url = :zoom_join_url
                            {$updateCoverImgSqlPart}
                        WHERE id = :id";

            $stmt = $pdo->prepare($zoomUpdateSql);
            $params = [
                'id' => $contentId,
                'konusma_kulup_id' => $content_type,
                'class_ids' => $class_ids,
                'title' => $subject,
                'zoom_date' => $zoom_date_formatted,
                'zoom_time' => $zoom_time_formatted,
                'zoom_start_url' => $zoom_start_url,
                'zoom_join_url' => $zoom_join_url
            ];

            if (!empty($updateCoverImgSqlPart)) {
                $params['cover_img'] = $coverImgPath;
            }

            $stmt->execute($params);


            // ----------------------------------------------------------------------
            // B) ÇOKLU EK DOSYA YÜKLEME (Fonksiyonsuz Mantık)
            // ----------------------------------------------------------------------
            if ($multiFiles && count($multiFiles['name']) > 0) {

                $fileInsertSql = "INSERT INTO konusma_kulupleri_files_lnp (konusma_kulupleri_zoom_id, file_path) VALUES (:konusma_kulupleri_zoom_id, :file_path)";
                $fileStmt = $pdo->prepare($fileInsertSql);

                for ($i = 0; $i < count($multiFiles['name']); $i++) {
                    if ($multiFiles['error'][$i] === UPLOAD_ERR_OK) {

                        $currentFileExtension = strtolower(pathinfo($multiFiles['name'][$i], PATHINFO_EXTENSION));
                        if (!in_array($currentFileExtension, $allowedExtensions)) {
                            // Eğer dosya türü geçersizse, bu dosyayı atla veya hatayı döndür
                            continue;
                        }

                        $currentNewFileName = uniqid('file_', true) . '.' . $currentFileExtension;
                        $currentTargetPath = $uploadDir . $currentNewFileName;

                        if (move_uploaded_file($multiFiles['tmp_name'][$i], $currentTargetPath)) {
                            $dbFilePath = $dbPathPrefix . $currentNewFileName;
                            // Veritabanına kaydet
                            $fileStmt->execute(['konusma_kulupleri_zoom_id' => $contentId, 'file_path' => $dbFilePath]);
                        } else {
                            // Bir dosya yüklenemezse bile diğerlerinin kaydedilmeye devam etmesi için burada exit yapmıyoruz.
                            error_log("Çoklu dosya yükleme hatası: " . $multiFiles['name'][$i]);
                        }
                    }
                }
            }

            // 5. Her şey başarılıysa Transaction'ı onayla
            $pdo->commit();
            echo json_encode(['status' => 'success', 'message' => 'Kulüp İçeriği başarıyla güncellendi.', 'id' => $contentId]);
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log("Veritabanı Hatası (clubContentUpdate): " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Veritabanı hatası oluştu. Lütfen sistem yöneticisine başvurun.']);
        } catch (Exception $e) {
            $pdo->rollBack();
            error_log("Genel Hata (clubContentUpdate): " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Beklenmeyen bir hata oluştu: ' . $e->getMessage()]);
        }
        exit;
        break;
    case 'deleteClubContentFile':
        $fileId = $_POST['file_id'] ?? null;

        if (empty($fileId)) {
            echo json_encode(['status' => 'error', 'message' => 'Dosya ID eksik.']);
            exit;
        }

        try {
            // 1. Dosya yolunu veritabanından çek
            $sql = "SELECT file_path FROM konusma_kulupleri_files_lnp WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $fileId]);
            $file = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$file) {
                echo json_encode(['status' => 'error', 'message' => 'Dosya veritabanında bulunamadı.']);
                exit;
            }

            $filePath = '../' . $file['file_path']; // Kök dizine göre tam yolu oluştur

            $pdo->beginTransaction();

            // 2. Veritabanından kaydı sil
            $deleteDbSql = "DELETE FROM konusma_kulupleri_files_lnp WHERE id = :id";
            $stmt = $pdo->prepare($deleteDbSql);
            $stmt->execute(['id' => $fileId]);

            // 3. Fiziksel dosyayı sil
            if (file_exists($filePath) && is_file($filePath)) {
                if (!unlink($filePath)) {
                    $pdo->rollBack();
                    echo json_encode(['status' => 'error', 'message' => 'Fiziksel dosya silinemedi. İzinleri kontrol edin.']);
                    exit;
                }
            }

            $pdo->commit();
            echo json_encode(['status' => 'success', 'message' => 'Dosya başarıyla silindi.']);
        } catch (PDOException $e) {
            $pdo->rollBack();
            http_response_code(500);
            error_log("Veritabanı Hatası (deleteClubContentFile): " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Veritabanı hatası oluştu. Silme başarısız.']);
        }
        exit;
        break;
    default:
        echo json_encode(['status' => 'error', 'message' => 'Geçersiz servis']);
        break;
}
