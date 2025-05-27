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

        $title = $_POST['title'] ?? null;
        $startDate = $_POST['start_date'] ?? null;
        $endDate = $_POST['end_date'] ?? null;
        $pdo->beginTransaction();
        try {
            // Dosya işlemleri
            $filePath = null;

            if (isset($_FILES['cover_img']) && $_FILES['cover_img']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../uploads/test/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $fileTmpPath = $_FILES['cover_img']['tmp_name'];
                $fileName = basename($_FILES['cover_img']['name']);
                $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'];
                if (!in_array($extension, $allowedExtensions)) {
                    throw new Exception('İzin verilmeyen dosya uzantısı.');
                }

                $newFileName = uniqid('test_', true) . '.' . $extension;
                $destination = $uploadDir . $newFileName;

                if (!move_uploaded_file($fileTmpPath, $destination)) {
                    throw new Exception('Dosya yüklenemedi.');
                }

                $filePath = 'uploads/test/' . $newFileName; // Veritabanı için yol
            }

            // Veritabanı bağlantısı


            $stmt = $pdo->prepare("
            INSERT INTO tests_lnp 
            (class_id, lesson_id, unit_id, topic_id, subtopic_id, test_title, start_date, end_date, cover_img)
            VALUES
            (:class_id, :lesson_id, :unit_id, :topic_id, :subtopic_id, :title, :start_date, :end_date, :file_path)
        ");

            $stmt->execute([
                ':class_id'    => $classId,
                ':lesson_id'   => $lessonId,
                ':unit_id'     => $unitId,
                ':topic_id'    => $topicId,
                ':subtopic_id' => $subtopicId,
                ':title'       => $title,
                ':start_date'  => $startDate,
                ':end_date'    => $endDate,
                ':file_path'   => $filePath
            ]);

            $testId = $pdo->lastInsertId(); // tests_lnp kaydından sonra geldi

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

                // Görseller (gelen files)
                if (isset($_FILES['questions']['name'][$qIndex]['images'])) {
                    $images = $_FILES['questions']['name'][$qIndex]['images'];
                    $tmpNames = $_FILES['questions']['tmp_name'][$qIndex]['images'];
                    $errors = $_FILES['questions']['error'][$qIndex]['images'];

                    $uploadDir = __DIR__ . '/../uploads/questions/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }

                    foreach ($images as $i => $imgName) {
                        if ($errors[$i] === UPLOAD_ERR_OK) {
                            $extension = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));
                            $newFileName = uniqid('img_', true) . '.' . $extension;
                            $destination = $uploadDir . $newFileName;

                            if (!move_uploaded_file($tmpNames[$i], $destination)) {
                                throw new Exception('Soru görseli yüklenemedi.');
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
                      

                        // Eğer option dosyaları varsa (örneğin ses)
                        if (isset($_FILES['questions']['name'][$qIndex]['options'][$optionKey]['images']) && is_array($_FILES['questions']['name'][$qIndex]['options'][$optionKey]['images'])) {
                            // Her bir görsel dosyasını döngüye al
                            foreach ($_FILES['questions']['name'][$qIndex]['options'][$optionKey]['images'] as $imgIndex => $fileName) {
                                $optTmpName = $_FILES['questions']['tmp_name'][$qIndex]['options'][$optionKey]['images'][$imgIndex];
                                $optError = $_FILES['questions']['error'][$qIndex]['options'][$optionKey]['images'][$imgIndex];

                                if ($optError === UPLOAD_ERR_OK) {
                                    $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION)); // $fileName kullanıldı
                                    $newFileName = uniqid('optfile_', true) . '.' . $extension;
                                    $destination = $uploadDir . $newFileName; // $uploadDir tanımlı olmalı

                                    if (!move_uploaded_file($optTmpName, $destination)) {
                                        // Hata yönetimi: Dosya taşınamadıysa
                                        throw new Exception('Seçenek dosyası yüklenemedi: ' . $fileName);
                                    }

                                    // Veritabanına kaydetme (option_id'nin geçerli olduğundan emin olun)
                                    // $optionId'nin bu döngüden önce ilgili seçeneğe ait olarak alındığı varsayılır.
                                    $stmt = $pdo->prepare("
                        INSERT INTO test_question_option_files_lnp (option_id, file_path)
                        VALUES (:option_id, :file_path)
                    ");
                                    $stmt->execute([
                                        ':option_id' => $optionId, // Bu değişkenin scope'unu ve doğruluğunu kontrol edin
                                        ':file_path' => 'uploads/questions/' . $newFileName
                                    ]);
                                } else if ($optError !== UPLOAD_ERR_NO_FILE) {
                                    // Yükleme sırasında oluşan diğer hatalar (örn: boyut, tip vb.)
                                    throw new Exception('Seçenek dosyası yükleme hatası: Kod ' . $optError);
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




    // Diğer servisler buraya eklenebilir
    default:
        echo json_encode(['status' => 'error', 'message' => 'Geçersiz servis']);
        break;
}
