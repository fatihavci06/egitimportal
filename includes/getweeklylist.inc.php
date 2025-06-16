<?php


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

    try {
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
            $stmt = $pdo->prepare('SELECT id, name, start_date, end_date FROM subtopics_lnp WHERE class_id = ? AND lesson_id = ? AND unit_id = ? AND topic_id = ? ORDER BY start_date ASC');
            if (!$stmt->execute([$classId, $lessonId, $unitId, $topicId])) {
                $stmt = null;
                error_log("Failed to fetch subtopics for topic ID: $topicId");
                echo json_encode(['status' => 'error', 'message' => 'Veri alınamadı.']);
                exit();
            }
        }
        if (!empty($subtopicId)) {
            $stmt = $pdo->prepare('SELECT id, 
            subtopics.name AS subTopicName, 
            subtopics.start_date AS subTopicStartDate, 
            subtopics.end_date AS subTopicEndDate,
            topics.name AS topicName, 
            topics.start_date AS topicStartDate, 
            topics.end_date AS topicEndDate
            FROM subtopics_lnp subtopics
            INNER JOIN topics_lnp topics ON subtopics.topic_id = topic.id
            WHERE subtopics.class_id = ? 
            AND subtopics.lesson_id = ? 
            AND subtopics.unit_id = ? 
            AND subtopics.topic_id = ? ORDER BY start_date ASC');
            if (!$stmt->execute([$classId, $lessonId, $unitId, $topicId])) {
                $stmt = null;
                error_log("Failed to fetch subtopics for topic ID: $subtopicId");
                echo json_encode(['status' => 'error', 'message' => 'Veri alınamadı.']);
                exit();
            }
        }

        $tests = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = null;


        foreach ($tests as $key => $value) {
            $events[] = [
                'name' =>$value['unitName']?? $value['topicName'] ??$value['subTopicName'],
                'start' => $value['subTopicStartDate'] ?? $value['topicStartDate'] ??  $value['unitStartDate'] ?? null,
                'end' => $value['subTopicEndDate'] ?? $value['topicStartDate'] ??  $value['unitEndDate'] ?? null,
                'allDay' => true,
                /* 'description' => $value['description'],
                'location' => $value['location'],
                'type' => $value['type'] */ // Bu alanı FullCalendar'da kullanabiliriz
            ];
        }

        echo json_encode(['status' => 'success', 'data' => $events]);
    } catch (Exception $e) {
        error_log("Veritabanı hatası: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Veri alınamadı.']);
        exit();
    }
}
