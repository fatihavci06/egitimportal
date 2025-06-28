<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $classId = $_POST['class_id'];
    $lessonId = $_POST['lesson_id'];
    $unitId = $_POST['unit_id'] ?? null;
    $topicId = $_POST['topic_id'] ?? null;
    $subtopicId = $_POST['subtopic_id'] ?? null;

    // Grabbing the data
    include_once "../classes/dbh.classes.php";
    // include_once "../classes/weekly.classes.php";
    // include_once "../classes/weekly-contr.classes.php";
    include_once "../classes/classes.classes.php";
    include_once "../classes/lessons.classes.php";
    include_once "../classes/units.classes.php";
    include_once "../classes/topics.classes.php";

    $dbh = new Dbh();
    $pdo = $dbh->connect();
    $events = [];

    // try {
    if (!empty($lessonId)) {
        $stmt = $pdo->prepare('SELECT id AS unitId, 
            name AS unitName, 
            start_date AS unitStartDate, 
            end_date AS unitEndDate
            FROM units_lnp
            WHERE class_id = ? AND lesson_id = ?  ORDER BY start_date ASC');
        if (!$stmt->execute([$classId, $lessonId])) {
            $stmt = null;
            error_log("Failed to fetch units for lesson ID: $lessonId");
            echo json_encode(['status' => 'error', 'message' => 'Veri alınamadı.']);
            exit();
        }
    }
    if (!empty($unitId)) {
        $stmt = $pdo->prepare('SELECT id AS topicId, 
            name AS topicName, 
            start_date AS topicStartDate, 
            end_date AS topicEndDate
            FROM topics_lnp
            WHERE class_id = ? 
            AND lesson_id = ? 
            AND unit_id = ?
            ');
        if (!$stmt->execute([$classId, $lessonId, $unitId])) {
            $stmt = null;
            error_log("Failed to fetch topics for unit ID: $unitId");
            echo json_encode(['status' => 'error', 'message' => 'Veri alınamadı.']);
            exit();
        }
    }
    if (!empty($topicId)) {
        $stmt = $pdo->prepare('SELECT 
            id AS subTopicId, 
            name AS subTopicName, 
            start_date AS subTopicStartDate, 
            end_date AS subTopicEndDate 
            FROM subtopics_lnp WHERE class_id = ? AND lesson_id = ? AND unit_id = ? AND topic_id = ? ORDER BY start_date ASC');
        if (!$stmt->execute([$classId, $lessonId, $unitId, $topicId])) {
            $stmt = null;
            error_log("Failed to fetch subtopics for topic ID: $topicId");
            echo json_encode(['status' => 'error', 'message' => 'Veri alınamadı.']);
            exit();
        }
    }

    $tests = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt = null;


    foreach ($tests as $key => $value) {
        $events[] = [
            'name' => $value['unitName'] ?? $value['topicName'] ?? $value['subTopicName'],
            'start' => $value['subTopicStartDate'] ?? $value['topicStartDate'] ??  $value['unitStartDate'] ?? null,
            'end' => $value['subTopicEndDate'] ?? $value['topicStartDate'] ??  $value['unitEndDate'] ?? null,
            'allDay' => true,
        ];
    }
    
    $stmtUser = $pdo->prepare('SELECT parent_id, class_id FROM users_lnp WHERE id = ?');
    $stmtUser->execute([$_SESSION['id']]);
    $userData = $stmtUser->fetch(PDO::FETCH_ASSOC);



    $filters = [
        'class_id' => $classId, // zorunlu
        'lesson_id' => $lessonId ?? null,
        'unit_id'   => $unitId ?? null,
        'topic_id'  => $topicId ?? null,
    ];

    // Sorgunun temeli
    $sql = "
    SELECT id, test_title, start_date, end_date, class_id,
        CASE 
            WHEN NOW() BETWEEN start_date AND end_date THEN 'Tarihi Geçmemiş'
            WHEN NOW() > end_date THEN 'Tarihi Geçmiş'
            ELSE 'belirsiz'
        END AS label
    FROM tests_lnp
    WHERE class_id = ?
      AND (
          (NOW() BETWEEN start_date AND end_date) OR
          (NOW() > end_date)
      )
";

    // Dinamik olarak filtreleri ekle
    $params = [$filters['class_id']];

    if (!empty($filters['lesson_id'])) {
        $sql .= " AND lesson_id = ?";
        $params[] = $filters['lesson_id'];
    }
    if (!empty($filters['unit_id'])) {
        $sql .= " AND unit_id = ?";
        $params[] = $filters['unit_id'];
    }
    if (!empty($filters['topic_id'])) {
        $sql .= " AND topic_id = ?";
        $params[] = $filters['topic_id'];
    }

    // Hazırla ve çalıştır
    $stmtTest = $pdo->prepare($sql);
    $stmtTest->execute($params);
    $testData = $stmtTest->fetchAll(PDO::FETCH_ASSOC);




    echo json_encode(['status' => 'success', 'data' => $events, 'testData' => $testData]);
    // } catch (Exception $e) {
    //     error_log("Veritabanı hatası: " . $e->getMessage());
    //     echo json_encode(['status' => 'error', 'message' => 'Veri alınamadı.']);
    //     exit();
    // }
}
