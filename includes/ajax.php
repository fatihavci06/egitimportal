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
        $smsTemplate=isset($_POST['smsTemplate']) ? cleanInput($_POST['smsTemplate']) : null;
       
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
                $updateStmt->execute([$taxRatio, $discountRatio,$smsTemplate, $notifySms, $notifyEmail, $notificationStartDay, $notificationCount]);

                jsonResponse(200, 'success', 'Ayarlar güncellendi.');
            } else {
                // Yeni kayıt ekleme
                $insertStmt = $pdo->prepare("INSERT INTO settings_lnp (school_id, tax_rate, discount_rate, notify_sms, notify_email, notification_start_day, notification_count,sms_template) VALUES (1, ?, ?, ?, ?, ?, ?,?)");
                $insertStmt->execute([$taxRatio, $discountRatio, $notifySms, $notifyEmail, $notificationStartDay, $notificationCount,$smsTemplate]);

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
        function getGroupedByPeriod($pdo, $dateFormat)
        {
            $stmt = $pdo->prepare("
                SELECT 
                    DATE_FORMAT(pp.created_at, ?) AS period,
                    pt.name AS payment_type,
                    SUM(pp.pay_amount) AS total_payment,
                    SUM(pp.kdv_amount) AS total_tax
                FROM package_payments_lnp pp
                LEFT JOIN payment_types_lnp pt ON pt.id = pp.payment_type
                GROUP BY period, pt.name
                ORDER BY period
            ");
            $stmt->execute([$dateFormat]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        $response = [
            'daily' => getGroupedByPeriod($pdo, '%Y-%m-%d'),
            'weekly' => getGroupedByPeriod($pdo, '%x-W%v'),
            'monthly' => getGroupedByPeriod($pdo, '%Y-%m'),
            'yearly' => getGroupedByPeriod($pdo, '%Y')
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
        break;
    case 'userpaymentreport':
        if ($_GET['action'] === 'kullaniciBasinaGelir') {

            $period = $_GET['period'] ?? 'daily'; // daily, weekly, monthly, yearly

            // Kullanıcı rolü 2 olanların sayısını al
            $userCountStmt = $pdo->prepare("SELECT COUNT(*) FROM users_lnp WHERE role = 2 or role=10002");
            $userCountStmt->execute();
            $userCount = (int) $userCountStmt->fetchColumn();

            if ($userCount === 0) {
                echo json_encode(['data' => []]);
                exit;
            }

            // Tarih formatı ve GROUP BY ifadesi
            switch ($period) {
                case 'weekly':
                    $dateFormat = '%x-W%v';
                    $groupBy = "YEARWEEK(pp.created_at, 3)";
                    break;
                case 'monthly':
                    $dateFormat = '%Y-%m';
                    $groupBy = "DATE_FORMAT(pp.created_at, '%Y-%m')";
                    break;
                case 'yearly':
                    $dateFormat = '%Y';
                    $groupBy = "YEAR(pp.created_at)";
                    break;
                case 'daily':
                default:
                    $dateFormat = '%Y-%m-%d';
                    $groupBy = "DATE(pp.created_at)";
                    break;
            }

            $stmt = $pdo->prepare("
        SELECT 
            DATE_FORMAT(pp.created_at, ?) AS period,
            ROUND(SUM(pp.pay_amount) / ?, 2) AS avg_payment,
            ROUND(SUM(pp.kdv_amount) / ?, 2) AS avg_tax
        FROM package_payments_lnp pp
        INNER JOIN users_lnp u ON u.id = pp.user_id AND u.role = 2 or u.role=10002
        GROUP BY $groupBy
        ORDER BY period
    ");

            $stmt->execute([$dateFormat, $userCount, $userCount]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(['data' => $result]);
            exit;
        }
        break;

    case 'graphicreport':
        try {
            $daily = $pdo->query("
    SELECT DATE(created_at) AS day, 
           SUM(pay_amount) AS total_payment, 
           ROUND(SUM(kdv_amount), 0) AS total_tax 
    FROM package_payments_lnp 
    GROUP BY day 
    ORDER BY day
")->fetchAll(PDO::FETCH_ASSOC);

            $weekly = $pdo->query("
    SELECT CONCAT(YEAR(created_at), '-W', LPAD(WEEK(created_at, 1), 2, '0')) AS week, 
           SUM(pay_amount) AS total_payment, 
           ROUND(SUM(kdv_amount), 0) AS total_tax 
    FROM package_payments_lnp 
    GROUP BY week 
    ORDER BY week
")->fetchAll(PDO::FETCH_ASSOC);

            $monthly = $pdo->query("
    SELECT DATE_FORMAT(created_at, '%Y-%m') AS period, 
           SUM(pay_amount) AS total_payment, 
           ROUND(SUM(kdv_amount), 0) AS total_tax 
    FROM package_payments_lnp 
    GROUP BY period 
    ORDER BY period
")->fetchAll(PDO::FETCH_ASSOC);

            $yearly = $pdo->query("
    SELECT YEAR(created_at) AS year, 
           SUM(pay_amount) AS total_payment, 
           ROUND(SUM(kdv_amount), 0) AS total_tax 
    FROM package_payments_lnp 
    GROUP BY year 
    ORDER BY year
")->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode([
                'daily' => $daily,
                'weekly' => $weekly,
                'monthly' => $monthly,
                'yearly' => $yearly
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
                    u.subscribed_end, 
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
        WHERE u.id = :user_id");

            $stmt->execute(['user_id' => $studentId]);
        } else {
            // Tüm öğrenciler için filtrele
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
        LEFT JOIN payment_status_lnp ps ON ps.id = pp.payment_status");

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
        try {
            if (!$username) {
                throw new Exception('username adı boş olamaz');
            }
            if (!$password) {
                throw new Exception('password boş olamaz');
            }



            $check = $pdo->query("SELECT id FROM sms_settings_lnp LIMIT 1");

            if ($check->rowCount() > 0) {
                // Varsa güncelle (ilk kaydı güncelle)
                $stmt = $pdo->prepare("UPDATE sms_settings_lnp SET username = ?, password = ? LIMIT 1");
                $stmt->execute([$username, $password]);
            } else {
                // Yoksa yeni kayıt ekle
                $stmt = $pdo->prepare("INSERT INTO sms_settings_lnp (username, password) VALUES (?, ?)");
                $stmt->execute([$username, $password]);
            }

            if ($stmt->rowCount() > 0) {
                jsonResponse(200, 'success', 'Ayarlar başarıyla kaydedildi veya güncellendi.');
            } else {
                jsonResponse(500, 'error', 'İşlem gerçekleştirilemedi.');
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

            // Günlük
            $daily = $pdo->query("
            SELECT DATE(created_at) AS day, COUNT(*) AS user_count
            FROM users_lnp
            WHERE role=2 or role=10002 and  subscribed_end < '$today'
            GROUP BY day
            ORDER BY day
        ")->fetchAll(PDO::FETCH_ASSOC);

            // Haftalık
            $weekly = $pdo->query("
            SELECT CONCAT(YEAR(created_at), '-W', LPAD(WEEK(created_at, 1), 2, '0')) AS week, COUNT(*) AS user_count
            FROM users_lnp
            WHERE role=2  or role=10002 and subscribed_end < '$today'
            GROUP BY week
            ORDER BY week
        ")->fetchAll(PDO::FETCH_ASSOC);

            // Aylık
            $monthly = $pdo->query("
            SELECT DATE_FORMAT(created_at, '%Y-%m') AS period, COUNT(*) AS user_count
            FROM users_lnp
            WHERE role=2 or role=10002 and  subscribed_end < '$today'
            GROUP BY period
            ORDER BY period
        ")->fetchAll(PDO::FETCH_ASSOC);

            // Yıllık
            $yearly = $pdo->query("
            SELECT YEAR(created_at) AS year, COUNT(*) AS user_count
            FROM users_lnp
            WHERE role=2  or role=10002 and subscribed_end < '$today'
            GROUP BY year
            ORDER BY year
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
    case 'active-user-count-report':
        try {
            $today = date('Y-m-d');

            // Günlük
            $daily = $pdo->query("
            SELECT DATE(created_at) AS day, COUNT(*) AS user_count
            FROM users_lnp
            WHERE role=2 or role=10002 and  subscribed_end > '$today'
            GROUP BY day
            ORDER BY day
        ")->fetchAll(PDO::FETCH_ASSOC);

            // Haftalık
            $weekly = $pdo->query("
            SELECT CONCAT(YEAR(created_at), '-W', LPAD(WEEK(created_at, 1), 2, '0')) AS week, COUNT(*) AS user_count
            FROM users_lnp
            WHERE role=2 or role=10002 and  subscribed_end > '$today'
            GROUP BY week
            ORDER BY week
        ")->fetchAll(PDO::FETCH_ASSOC);

            // Aylık
            $monthly = $pdo->query("
            SELECT DATE_FORMAT(created_at, '%Y-%m') AS period, COUNT(*) AS user_count
            FROM users_lnp
            WHERE role=2 or role=10002 and  subscribed_end > '$today'
            GROUP BY period
            ORDER BY period
        ")->fetchAll(PDO::FETCH_ASSOC);

            // Yıllık
            $yearly = $pdo->query("
            SELECT YEAR(created_at) AS year, COUNT(*) AS user_count
            FROM users_lnp
            WHERE role=2 or role=10002 and subscribed_end > '$today'
            GROUP BY year
            ORDER BY year
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


    // Diğer servisler buraya eklenebilir
    default:
        echo json_encode(['status' => 'error', 'message' => 'Geçersiz servis']);
        break;
}
