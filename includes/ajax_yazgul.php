<?php

session_start();

include_once '../classes/dbh.classes.php';
header('Content-Type: application/json');
session_start();
if (!$_SESSION['role']) {
    echo json_encode(['status' => 'error', 'message' => 'Yetkisiz erişim.']);
    exit();
}

// Sadece POST isteğini kabul et

// Servis kontrolü
$service = $_GET['service'] ?? '';

// Veritabanı bağlantısı (örnek PDO ile)
$pdo = new Dbh();
$pdo = $pdo->connect();

// Servise göre işlem yapılacak switch case yapısı
switch ($service) {
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

    // Diğer servisler buraya eklenebilir
    case 'getLessonListForStudent':
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
    case 'getUnitListForStudent':
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

    case 'getTopicListForStudent':
        $lessonId = $_POST['lesson_id'] ?? null;
        $classId = $_POST['class_id'] ?? null;
        $unitId = $_POST['unit_id'] ?? null;

        try {
            $stmt = $pdo->prepare("SELECT * FROM `topics_lnp` where lesson_id=? and class_id=? and unit_id=? ORDER BY name ASC");
            $stmt->execute([$lessonId, $classId, $unitId]);

            $topics = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($topics) {
                echo json_encode(['status' => 'success', 'data' => $topics]);
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
    case 'getSubtopicListForStudent':
       $lessonId = $_POST['lesson_id'] ?? null;
        $classId = $_POST['class_id'] ?? null;
        $unitId = $_POST['unit_id'] ?? null;
        $topicId = $_POST['topic_id'] ?? null;

        try {
            $stmt = $pdo->prepare("SELECT * FROM `subtopics_lnp` where lesson_id=? and class_id=? and unit_id=? and topic_id=? ORDER BY name ASC");
            $stmt->execute([$lessonId, $classId, $unitId, $topicId]);

            $subTopics = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($subTopics) {
                echo json_encode(['status' => 'success', 'data' => $subTopics]);
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


    // Diğer servisler buraya eklenebilir
    default:
        echo json_encode(['status' => 'error', 'message' => 'Geçersiz servis']);
        break;
}
