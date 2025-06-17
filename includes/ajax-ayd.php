<?php
// includes/ajax.php
include_once '../classes/dbh.classes.php';
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
    case 'deleteMainSchoolContent':
        // Gelen ID kontrolü
        $id = $_POST['id'] ?? null;

        if (!$id || !is_numeric($id)) {
            echo json_encode(['status' => 'error', 'message' => 'Geçersiz ID']);
            exit;
        }

        try {
            // Silme işlemi
            $stmt = $pdo->prepare("DELETE FROM main_school_content_lnp WHERE id = ?");
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
    case 'getLessonList':
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

            $teacherId = NULL;

            if($_SESSION['role'] == 4){
                $teacherId = $_SESSION['id'];
            }

            // Veritabanı bağlantısı
            $stmt = $pdo->prepare("
            INSERT INTO tests_lnp 
            (status,class_id, lesson_id, unit_id, topic_id, subtopic_id, test_title, start_date, end_date, cover_img, teacher_id)
            VALUES
            (:status,:class_id, :lesson_id, :unit_id, :topic_id, :subtopic_id, :title, :start_date, :end_date, :file_path, :teacher_id)
        ");

            $stmt->execute([
                ':status'       => $status,
                ':class_id'     => $classId,
                ':lesson_id'    => $lessonId,
                ':unit_id'      => $unitId,
                ':topic_id'     => $topicId,
                ':subtopic_id'  => $subtopicId,
                ':title'        => $title,
                ':start_date'   => $startDate,
                ':end_date'     => $endDate,
                ':file_path'    => $filePath,
                ':teacher_id'   => $teacherId
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
        $teacherId = '';
        if($_SESSION['role'] == 4){
            $teacherId = $_SESSION['id'];
        }
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
            if (!empty($teacherId)) {
                $sql .= " AND t.teacher_id = ?";
                $params[] = $teacherId;
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
            // if ($stmt->rowCount() === 0) {
            //     echo json_encode(['status' => 'error', 'message' => 'Test bulunamadı.']);
            //     exit;
            // }
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

            /* if (!in_array($_SESSION['role'] ?? null, [1, 4])) {
                $sql .= " AND t.class_id = :class_id";
                $params['class_id'] = $_SESSION['class_id'] ?? null;
                $sql .= " AND status = :status"; // Sadece aktif testleri getir
                $params['status'] = 1;
            } */

            if (in_array($_SESSION['role'] ?? null, [2])) {
                $sql .= " AND t.class_id = :class_id";
                $params['class_id'] = $_SESSION['class_id'] ?? null;
                $sql .= " AND status = :status"; // Sadece aktif testleri getir
                $params['status'] = 1;
            }

            if (in_array($_SESSION['role'] ?? null, [4])) {
                $sql .= " AND t.teacher_id = :teacher_id";
                $params['teacher_id'] = $_SESSION['id'] ?? null;
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
            $testInfoStmt = $pdo->prepare("SELECT class_id, lesson_id, unit_id, topic_id, subtopic_id FROM tests_lnp WHERE id = :test_id");
            $testInfoStmt->execute([':test_id' => $testId]);
            $testInfo = $testInfoStmt->fetch(PDO::FETCH_ASSOC);

            // user_grades tablosunda varsa önce sil
            $percentageScore = $total > 0 ? round(($correct / $total) * 100, 2) : 0;

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
        
        $teacherId = '';
        if($_SESSION['role'] == 4){
            $teacherId = $_SESSION['id'];
        }

        try {
            $where = [];
            $params = [];

            if (!empty($teacherId)) {
                $where[] = "t.teacher_id = :teacher_id";
                $params[':teacher_id'] = $teacherId;
            }

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
            $stmt = $pdo->prepare("INSERT INTO main_school_lessons_lnp (name) VALUES (:name)");
            $stmt->execute([':name' => $lessonName]);

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
                mlc.class_id
            FROM main_school_lessons_lnp ml
            INNER JOIN mainschool_lesson_class_id_lnp mlc ON mlc.lesson_id = ml.id
            WHERE mlc.class_id = :class_id  
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
                mlc.class_id
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

        if (!$lessonId || !is_numeric($lessonId) || empty($classIds) || !$lessonName) {
            echo json_encode(['status' => 'error', 'message' => 'Geçersiz ders ID veya eksik bilgiler']);
            exit;
        }

        try {
            $pdo->beginTransaction();

            // Dersi güncelle
            $stmt = $pdo->prepare("UPDATE main_school_lessons_lnp SET name = :name WHERE id = :id");
            $stmt->execute([
                ':name' => $lessonName,
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
        if (!$lessonId || !$unitName || !$classId) {
            echo json_encode(['status' => 'error', 'message' => 'Ders ID, birim adı ve sınıf ID gereklidir.']);
            exit;
        }

        try {
            $pdo->beginTransaction();

            // 1. Birim tablosuna ekle
            $stmt = $pdo->prepare("INSERT INTO main_school_units_lnp (lesson_id, name) VALUES (:lesson_id, :name)");
            $stmt->execute([':lesson_id' => $lessonId, ':name' => $unitName]);

            $pdo->commit();

            echo json_encode(['status' => 'success', 'message' => 'Ünite başarıyla kaydedildi.']);
        } catch (Exception $e) {
            $pdo->rollBack();
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
            echo json_encode(['status' => 'error', 'message' => 'Geçersiz birim ID']);
            exit;
        }
        try {
            $stmt = $pdo->prepare("SELECT u.id as id,l.id lesson_id,u.name as unit_name ,l.class_id as class_id FROM main_school_units_lnp u INNER JOIN mainschool_lesson_class_id_lnp l  on u.lesson_id=l.id WHERE u.id = :id");
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

        if (!$unitId || !$unitName || !$lessonId || !$classId) {
            echo json_encode(['status' => 'error', 'message' => 'Ünite ID, birim adı, ders ID ve sınıf ID gereklidir.']);
            exit;
        }

        try {
            $pdo->beginTransaction();

            // 1. Birimi güncelle
            $stmt = $pdo->prepare("UPDATE main_school_units_lnp SET name = :name, lesson_id = :lesson_id WHERE id = :id");
            $stmt->execute([':name' => $unitName, ':lesson_id' => $lessonId, ':id' => $unitId]);



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

        if (!$lessonId) {
            echo json_encode(['status' => 'error', 'message' => 'Ders ID ve sınıf ID gereklidir.']);
            exit;
        }


        try {
            $stmt = $pdo->prepare("
        SELECT u.id, u.name
        FROM main_school_units_lnp u
        WHERE u.lesson_id = :lesson_id
    ");
            $stmt->execute([':lesson_id' => $lessonId]);
            $units = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($units)) {
                echo json_encode(['status' => 'error', 'message' => 'Birim bulunamadı']);
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
        WHERE t.unit_id = :unit_id");
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
    default:
        echo json_encode(['status' => 'error', 'message' => 'Geçersiz servis']);
        break;
}
