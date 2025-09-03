<?php
// includes/ajax.php
include_once '../classes/dbh.classes.php';
include_once '../classes/Mailer.php';
$mailer = new Mailer();
header('Content-Type: application/json');
session_start();
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
        $monthlyFee = isset($_POST['monthly_fee']) ? (float)$_POST['monthly_fee'] : null;
        $subscriptionPeriod = $_POST['subscription_period'] ?? null;

        // Girdileri doğrula


        // Veritabanı işlemi (örnek güncelleme)
        try {
            if (!$packageName) {
                throw new Exception('Paket adı boş olamaz');
            }

            if (!is_float($monthlyFee) || $monthlyFee < 0) {

                throw new Exception('Aylık ücret geçerli bir sayı olmalıdır.');
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

            $stmt = $pdo->prepare("INSERT INTO packages_lnp (name, class_id, monthly_fee, subscription_period) VALUES (?, ?, ?, ?)");
            $stmt->execute([$packageName, $classId, $monthlyFee, $subscriptionPeriod]);

            if ($stmt->rowCount() > 0) {
                jsonResponse(200, 'success', 'Paket başarıyla eklendi.');
            } else {
                jsonResponse(500, 'error', 'Kayıt eklenemedi.');
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

        $packageName = isset($_POST['packageName']) ? cleanInput($_POST['packageName']) : null;
        $classId = isset($_POST['class_id']) ? (int)$_POST['class_id'] : null;
        $monthlyFee = isset($_POST['monthly_fee']) ? (float)$_POST['monthly_fee'] : null;
        $subscriptionPeriod = $_POST['subscription_period'] ?? null;
        $id = $_POST['id'] ?? null;
        // Girdileri doğrula


        // Veritabanı işlemi (örnek güncelleme)
        try {
            if (!$packageName) {
                throw new Exception('Paket adı boş olamaz');
            }

            if (!is_float($monthlyFee) || $monthlyFee < 0) {

                throw new Exception('Aylık ücret geçerli bir sayı olmalıdır.');
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

            $stmt = $pdo->prepare("UPDATE packages_lnp SET name = ?, class_id = ?, monthly_fee = ?, subscription_period = ? WHERE id = ?");
            $stmt->execute([$packageName, $classId, $monthlyFee, $subscriptionPeriod, $id]);

            if ($stmt->rowCount() > 0) {
                jsonResponse(200, 'success', 'Paket başarıyla güncellendi.');
            } else {
                jsonResponse(500, 'error', 'Paket güncellenemedi veya zaten bu veriler mevcut.');
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

            // Cevapları kaydet
            $stmt = $pdo->prepare("INSERT INTO test_user_answers_lnp (user_id, test_id, question_id, selected_option_key) VALUES (:user_id, :test_id, :question_id, :selected_option_key)");

            foreach ($userAnswers as $answer) {
                $stmt->execute([
                    ':user_id' => $_SESSION['id'],
                    ':test_id' => $testId,
                    ':question_id' => $answer['question_id'],
                    ':selected_option_key' => $answer['selected_option_key']
                ]);
            }

            // Skor hesaplama
            $correct = 0;
            $total = count($userAnswers);

            $scoreStmt = $pdo->prepare("SELECT correct_answer FROM test_questions_lnp WHERE id = :question_id");

            foreach ($userAnswers as $answer) {
                $scoreStmt->execute([':question_id' => $answer['question_id']]);
                $correctAnswer = $scoreStmt->fetchColumn();

                if ($correctAnswer !== null && $answer['selected_option_key'] !== null && $answer['selected_option_key'] == $correctAnswer) {
                    $correct++;
                }
            }
            $testInfoStmt = $pdo->prepare("SELECT end_date,class_id, lesson_id, unit_id, topic_id, subtopic_id FROM tests_lnp WHERE id = :test_id");
            $testInfoStmt->execute([':test_id' => $testId]);
            $testInfo = $testInfoStmt->fetch(PDO::FETCH_ASSOC);

            // user_grades tablosunda varsa önce sil
            $percentageScore = $total > 0 ? round(($correct / $total) * 100, 2) : 0;
            if (strtotime($testInfo['end_date']) < time()) {
                $percentageScore = $percentageScore * 0.95;
            }

            // Var mı kontrol et
            $checkStmt = $pdo->prepare("SELECT * FROM user_grades_lnp WHERE test_id = :test_id AND user_id = :user_id");
            $checkStmt->execute([
                ':test_id' => $testId,
                ':user_id' => $userId
            ]);
            $existingGrade = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if ($existingGrade) {
                // Zaten varsa güncelle
                if ($percentageScore < 80) {
                    // Başarısızsa fail_count kolonu 1 artır
                    $updateStmt = $pdo->prepare("
            UPDATE user_grades_lnp 
            SET score = :score, fail_count = IFNULL(fail_count, 0) + 1 
            WHERE test_id = :test_id AND user_id = :user_id
        ");
                } else {
                    // Başarılıysa sadece score güncelle
                    $updateStmt = $pdo->prepare("
            UPDATE user_grades_lnp 
            SET score = :score 
            WHERE test_id = :test_id AND user_id = :user_id
        ");
                }

                $updateStmt->execute([
                    ':score' => $percentageScore,
                    ':test_id' => $testId,
                    ':user_id' => $userId
                ]);
            } else {
                // Yoksa yeni kayıt ekle
                $insertGradeStmt = $pdo->prepare("
        INSERT INTO user_grades_lnp 
        (user_id, test_id, class_id, lesson_id, unit_id, topic_id, subtopic_id, score, fail_count) 
        VALUES 
        (:user_id, :test_id, :class_id, :lesson_id, :unit_id, :topic_id, :subtopic_id, :score, :fail_count)
    ");

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


            echo json_encode([
                'status' => 'success',
                'message' => 'Cevaplar başarıyla kaydedildi ve değerlendirildi.',
                'score' => $percentageScore, // 100 üzerinden
                'correct_count' => $correct,
                'total_questions' => $total
            ]);
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo json_encode([
                'status' => 'error',
                'message' => 'Veritabanı hatası: ' . $e->getMessage()
            ]);
        }
        break;
    case 'getTestResults':
        try {
            $where = [];
            $params = [];

            if (!empty($_POST['name'])) {
                $where[] = "CONCAT(u.name, ' ', u.surname) LIKE :search_name";
                $params[':search_name'] = '%' . $_POST['name'] . '%';
            }

            if (!empty($_POST['class_id'])) {
                $where[] = 'ug.class_id = :class_id';
                $params[':class_id'] = $_POST['class_id'];
            }

            if (!empty($_POST['lesson_id'])) {
                $where[] = 'ug.lesson_id = :lesson_id';
                $params[':lesson_id'] = $_POST['lesson_id'];
            }

            if (!empty($_POST['unit_id'])) {
                $where[] = 'ug.unit_id = :unit_id';
                $params[':unit_id'] = $_POST['unit_id'];
            }

            if (!empty($_POST['topic_id'])) {
                $where[] = 'ug.topic_id = :topic_id';
                $params[':topic_id'] = $_POST['topic_id'];
            }

            if (!empty($_POST['subtopic_id'])) {
                $where[] = 'ug.subtopic_id = :subtopic_id';
                $params[':subtopic_id'] = $_POST['subtopic_id'];
            }

            $sql = "SELECT 
            ug.test_id AS test_id,
            t.test_title AS test_title,
            CONCAT(u.name, ' ', u.surname) AS name,
            ug.score,
            DATE_FORMAT(ug.created_at, '%d-%m-%Y %H:%i:%s') AS created_at
        FROM user_grades_lnp ug
        INNER JOIN users_lnp u ON u.id = ug.user_id
        INNER JOIN tests_lnp t ON t.id = ug.test_id";

            if (count($where) > 0) {
                $sql .= ' WHERE ' . implode(' AND ', $where);
            }

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(['status' => 'success', 'data' => $results]);
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Bir hata oluştu: ' . $e->getMessage()
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

    default:
        echo json_encode(['status' => 'error', 'message' => 'Geçersiz servis']);
        break;
}
