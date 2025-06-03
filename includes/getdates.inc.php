<?php

// İçerik türünü ayarla
header('Content-Type: application/json');

session_start();

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    // Grabbing the data
    $classId = isset($_POST['class_id']) ? $_POST['class_id'] : '';
    $lessonId = isset($_POST['lesson_id']) ? $_POST['lesson_id'] : '';
    $unitId = isset($_POST['unit_id']) ? $_POST['unit_id'] : '';
    $topicId = isset($_POST['topic_id']) ? $_POST['topic_id'] : '';
    $subtopicId = isset($_POST['subtopic_id']) ? $_POST['subtopic_id'] : '';

    // Instantiate AddUnitContr class
    include_once "../classes/dbh.classes.php";
    include_once "../classes/classes.classes.php";
    include_once "../classes/lessons.classes.php";
    include_once "../classes/units.classes.php";
    include_once "../classes/topics.classes.php";

    $dbh = new Dbh();
    $pdo = $dbh->connect();

    $events = [];

    try {
        if (!empty($lessonId)) {
            $stmt = $pdo->prepare('SELECT id, name, start_date, end_date FROM units_lnp WHERE class_id = ? AND lesson_id = ?');
            if (!$stmt->execute([$classId, $lessonId])) {
                $stmt = null;
                error_log("Failed to fetch units for lesson ID: $lessonId");
                echo json_encode(['status' => 'error', 'message' => 'Veri alınamadı.']);
                exit();
            }
        }
        if (!empty($unitId)) {
            $stmt = $pdo->prepare('SELECT id, name, start_date, end_date FROM topics_lnp WHERE class_id = ? AND lesson_id = ? AND unit_id = ?');
            if (!$stmt->execute([$classId, $lessonId, $unitId])) {
                $stmt = null;
                error_log("Failed to fetch topics for unit ID: $unitId");
                echo json_encode(['status' => 'error', 'message' => 'Veri alınamadı.']);
                exit();
            }
        }
        if (!empty($topicId)) {
            $stmt = $pdo->prepare('SELECT id, name, start_date, end_date FROM subtopics_lnp WHERE class_id = ? AND lesson_id = ? AND unit_id = ? AND topic_id = ?');
            if (!$stmt->execute([$classId, $lessonId, $unitId, $topicId])) {
                $stmt = null;
                error_log("Failed to fetch subtopics for topic ID: $topicId");
                echo json_encode(['status' => 'error', 'message' => 'Veri alınamadı.']);
                exit();
            }
        }
        /* if (!empty($subtopicId)) {
                $sql .= " AND t.subtopic_id = ?";
                $params[] = $subtopicId;
            } */

        // Global arama (OR kullanarak tüm ilgili sütunlarda arama)
        // Artık JOIN'lere gerek kalmadığı için doğrudan t.sutun_adi kullanıyoruz.
        /* if (!empty($searchValue)) {
                $sql .= " AND (t.title LIKE ? OR t.class_name LIKE ? OR t.lesson_name LIKE ? OR t.unit_name LIKE ? OR t.topic_name LIKE ? OR t.subtopic_name LIKE ?)";
                $params[] = '%' . $searchValue . '%';
                $params[] = '%' . $searchValue . '%';
                $params[] = '%' . $searchValue . '%';
                $params[] = '%' . $searchValue . '%';
                $params[] = '%' . $searchValue . '%';
                $params[] = '%' . $searchValue . '%';
            }

            $sql .= " ORDER BY t.created_at DESC"; *//* 

            $stmt = $pdo->prepare($sql); */
        /* 
            if (!$stmt->execute($params)) {
                $stmt = null;
                error_log("Failed to fetch filtered tests (client-side): ");
                return [];
            } */

        $tests = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = null;


        foreach ($tests as $key => $value) {
            $events[] = [
                'id' => $value['id'],
                'title' => $value['name'],
                'start' => $value['start_date'],
                'end' => $value['end_date'],
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
