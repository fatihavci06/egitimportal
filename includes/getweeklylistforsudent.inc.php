<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Gelen verileri al ve varsayılan değerleri belirle
    $classId = $_POST['class_id'] ?? $_SESSION['class_id'] ?? null;
    $lessonId = $_POST['lesson_id'] ?? null;
    $unitId = $_POST['unit_id'] ?? null;
    $topicId = $_POST['topic_id'] ?? null;

    // --- Mevcut Haftanın Başlangıç ve Bitiş Tarihlerini Hesapla (Pazartesi - Pazar) ---
    $currentDate = new DateTime(); // Bugünün tarihi
    $dayOfWeek = (int)$currentDate->format('N'); // Haftanın sayısal günü (1 = Pazartesi, 7 = Pazar)

    // Pazartesi'yi bul
    $startOfWeek = clone $currentDate;
    // Eğer bugün Pazartesi ise 0 gün geri git, Salı ise 1 gün geri git vb.
    $startOfWeek->modify('-' . ($dayOfWeek - 1) . ' days');
    $monday = $startOfWeek->format('Y-m-d');

    // Pazar'ı bul
    $endOfWeek = clone $startOfWeek;
    $endOfWeek->modify('+6 days'); // Pazartesi'den 6 gün sonrası Pazar olur
    $sunday = $endOfWeek->format('Y-m-d');
    // --- Hesaplama Sonu ---

    // Veritabanı bağlantısı ve sınıfların dahil edilmesi
    include_once "../classes/dbh.classes.php";
    // Diğer sınıflarınızın yollarını kontrol edin ve gerektiğinde dahil edin
    // include_once "../classes/classes.classes.php";
    // include_once "../classes/lessons.classes.php";
    // include_once "../classes/units.classes.php";
    // include_once "../classes/topics.classes.php";

    $dbh = new Dbh();
    $pdo = $dbh->connect();
    $events = [];
    $testData = []; // Test verileri için boş dizi, kullanıcı tarafından doldurulacak

    // Haftalık filtre için WHERE cümleciği
    // Bir etkinliğin haftalık aralıkta olması için:
    // Başlangıç tarihi haftanın son gününden (Pazar) önce veya eşit OLMALI
    // VE Bitiş tarihi haftanın ilk gününden (Pazartesi) sonra veya eşit OLMALI
    $weeklyFilterClause = ' AND (start_date <= ? AND end_date >= ?)';
    $weeklyFilterParams = [$sunday, $monday];

    try {
        // --- Üniteleri Getirme ---
        $unitSql = 'SELECT id AS unitId, slug AS unitSlug, name AS unitName, start_date AS unitStartDate, end_date AS unitEndDate FROM units_lnp WHERE class_id = ?';
        $unitParams = [$classId];

        if (!empty($lessonId)) {
            $unitSql .= ' AND lesson_id = ?';
            $unitParams[] = $lessonId;
        }
        $unitSql .= $weeklyFilterClause; // Haftalık filtreyi ekle
        $unitParams = array_merge($unitParams, $weeklyFilterParams); // Parametreleri birleştir

        $stmtUnits = $pdo->prepare($unitSql);
        if (!$stmtUnits->execute($unitParams)) {
            error_log("Failed to fetch units. SQL: " . $unitSql . " Params: " . implode(', ', $unitParams));
            // Hata durumunda sadece logla, programı durdurma
        } else {
            $units = $stmtUnits->fetchAll(PDO::FETCH_ASSOC);
            foreach ($units as $unit) {
                $events[] = [
                    'slug' => $unit['unitSlug'],
                    'name' => $unit['unitName'],
                    'start' => $unit['unitStartDate'],
                    'end' => $unit['unitEndDate'],
                    'allDay' => true,
                    'type' => 'unit'
                ];
            }
        }
        $stmtUnits = null;

        // --- Konuları Getirme ---
        $topicSql = 'SELECT id AS topicId, slug AS topicSlug, name AS topicName, start_date AS topicStartDate, end_date AS topicEndDate FROM topics_lnp WHERE class_id = ?';
        $topicParams = [$classId];

        if (!empty($lessonId)) {
            $topicSql .= ' AND lesson_id = ?';
            $topicParams[] = $lessonId;
        }
        if (!empty($unitId)) {
            $topicSql .= ' AND unit_id = ?';
            $topicParams[] = $unitId;
        }
        $topicSql .= $weeklyFilterClause; // Haftalık filtreyi ekle
        $topicParams = array_merge($topicParams, $weeklyFilterParams); // Parametreleri birleştir

        $stmtTopics = $pdo->prepare($topicSql);
        if (!$stmtTopics->execute($topicParams)) {
            error_log("Failed to fetch topics. SQL: " . $topicSql . " Params: " . implode(', ', $topicParams));
        } else {
            $topics = $stmtTopics->fetchAll(PDO::FETCH_ASSOC);
            foreach ($topics as $topic) {
                $events[] = [
                    'slug' => $topic['topicSlug'],
                    'name' => $topic['topicName'],
                    'start' => $topic['topicStartDate'],
                    'end' => $topic['topicEndDate'],
                    'allDay' => true,
                    'type' => 'topic'
                ];
            }
        }
      
        $stmtTopics = null;

        // --- Alt Konuları Getirme ---
        $subtopicSql = 'SELECT id AS subtopicId, slug AS subtopicSlug, name AS subtopicName, start_date AS subtopicStartDate, end_date AS subtopicEndDate FROM subtopics_lnp WHERE class_id = ?';
        $subtopicParams = [$classId];

        if (!empty($lessonId)) {
            $subtopicSql .= ' AND lesson_id = ?';
            $subtopicParams[] = $lessonId;
        }
        if (!empty($unitId)) {
            $subtopicSql .= ' AND unit_id = ?';
            $subtopicParams[] = $unitId;
        }
        if (!empty($topicId)) {
            $subtopicSql .= ' AND topic_id = ?';
            $subtopicParams[] = $topicId;
        }
        $subtopicSql .= $weeklyFilterClause; // Haftalık filtreyi ekle
        $subtopicParams = array_merge($subtopicParams, $weeklyFilterParams); // Parametreleri birleştir
        
        $stmtSubtopics = $pdo->prepare($subtopicSql);
        if (!$stmtSubtopics->execute($subtopicParams)) {
            error_log("Failed to fetch subtopics. SQL: " . $subtopicSql . " Params: " . implode(', ', $subtopicParams));
        } else {
            $subtopics = $stmtSubtopics->fetchAll(PDO::FETCH_ASSOC);
            foreach ($subtopics as $subtopic) {
                $events[] = [
                    'slug' => $subtopic['subtopicSlug'],
                    'name' => $subtopic['subtopicName'],
                    'start' => $subtopic['subtopicStartDate'],
                    'end' => $subtopic['subtopicEndDate'],
                    'allDay' => true,
                    'type' => 'subtopic'
                ];
            }
        }
        $stmtSubtopics = null;

        // --- Ödevleri Getirme ---
        $homeworkSql = 'SELECT id AS homeworkId, title AS homeworkTitle, start_date AS homeworkStartDate, end_date AS homeworkEndDate FROM homework_content_lnp WHERE class_id = ?';
        $homeworkParams = [$classId];

        if (!empty($lessonId)) {
            $homeworkSql .= ' AND lesson_id = ?';
            $homeworkParams[] = $lessonId;
        }
        if (!empty($unitId)) {
            $homeworkSql .= ' AND unit_id = ?';
            $homeworkParams[] = $unitId;
        }
        if (!empty($topicId)) {
            $homeworkSql .= ' AND topic_id = ?';
            $homeworkParams[] = $topicId;
        }
        $homeworkSql .= $weeklyFilterClause; // Haftalık filtreyi ekle
        $homeworkParams = array_merge($homeworkParams, $weeklyFilterParams); // Parametreleri birleştir

        $stmtHomework = $pdo->prepare($homeworkSql);
        if (!$stmtHomework->execute($homeworkParams)) {
            error_log("Failed to fetch homework. SQL: " . $homeworkSql . " Params: " . implode(', ', $homeworkParams));
        } else {
            $homeworks = $stmtHomework->fetchAll(PDO::FETCH_ASSOC);
            foreach ($homeworks as $homework) {
                $events[] = [
                    'slug' => 'homework-' . $homework['homeworkId'], // Ödevler için benzersiz bir slug oluşturun
                    'name' => $homework['homeworkTitle'],
                    'start' => $homework['homeworkStartDate'],
                    'end' => $homework['homeworkEndDate'],
                    'allDay' => true,
                    'type' => 'homework'
                ];
            }
        }
        $stmtHomework = null;


        // Tekrarlayan girişleri önlemek için benzersizleştirme
        $uniqueEvents = [];
        foreach ($events as $event) {
            $key = ($event['type'] ?? '') . '-' . ($event['slug'] ?? '');
            if (!isset($uniqueEvents[$key])) {
                $uniqueEvents[$key] = $event;
            }
        }
        $events = array_values($uniqueEvents);


        // Başarı yanıtı
        echo json_encode(['status' => 'success', 'data' => $events, 'testData' => $testData]);

    } catch (Exception $e) {
        error_log("Veritabanı hatası: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Veri alınamadı.']);
        exit();
    }
} else {
    // POST dışındaki istekleri reddet
    echo json_encode(['status' => 'error', 'message' => 'Geçersiz istek metodu.']);
    exit();
}