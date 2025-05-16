<?php
include "../classes/dbh.classes.php";
include "../classes/addclasses.classes.php";
include "../classes/addclasses-contr.classes.php";
include "../classes/slug.classes.php";

$service = $_GET['service'] ?? 'create';

$roles = $_POST['roles'] ?? [];
$month = $_POST['month'] ?? '';
$week = $_POST['week'] ?? '';
$activity_title = $_POST['activity_title'] ?? '';
$content_title = $_POST['content_title'] ?? '';
$concept_title = $_POST['concept_title'] ?? '';
$subject = $_POST['subject'] ?? '';
$secim = $_POST['secim'] ?? '';
$video_url = $_POST['video_url'] ?? '';
$content = $_POST['content'] ?? '';
$id = $_POST['id'] ?? null;
$content_description = $_POST['content_description'] ?? null;
$main_school_class_id = $_POST['main_school_class_id'] ?? null;

$file_urls = [];

if (isset($_FILES['file_path'])) {
    $uploadDir = __DIR__ . '/../uploads/mainschool/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    foreach ($_FILES['file_path']['tmp_name'] as $index => $tmpName) {

        if ($_FILES['file_path']['error'][$index] === UPLOAD_ERR_OK) {
            $fileName = basename($_FILES['file_path']['name'][$index]);
            $targetFile = $uploadDir . $fileName;

            if (move_uploaded_file($tmpName, $targetFile)) {
                $file_urls[] = 'uploads/mainschool/' . $fileName;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Dosya yüklenemedi: ' . $fileName]);
                exit;
            }
        }
    }
}
if (isset($_FILES['images'])) {
    $uploadDir = __DIR__ . '/../uploads/mainschool/primaryimages/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $image_urls = []; // Yüklenen dosya yollarını tutmak için

    foreach ($_FILES['images']['tmp_name'] as $index => $tmpName) {
        if ($_FILES['images']['error'][$index] === UPLOAD_ERR_OK) {
            $fileName = basename($_FILES['images']['name'][$index]);
            $targetFile = $uploadDir . $fileName;

            if (move_uploaded_file($tmpName, $targetFile)) {
                $image_urls[] = 'uploads/mainschool/primaryimages/' . $fileName;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Dosya yüklenemedi: ' . $fileName]);
                exit;
            }
        }
    }
}


try {
    $db = new Dbh();
    $pdo = $db->connect();
    $rolesStr = implode(',', $roles);

    if ($service === 'update' && $id) {
      
        // Güncelleme işlemi
        $sql = "UPDATE main_school_content_lnp SET
			roles = :roles,
			month = :month,
			week_id = :week_id,
			activity_title_id = :activity_title,
			content_title_id = :content_title,
			concept_title_id = :concept_title,
			content_description=:content_description,
            main_school_class_id=:main_school_class_id,
			subject = :subject,
			video_url = :video_url,
			content = :content
			WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':roles' => $rolesStr,
            ':month' => $month,
            ':week_id' => $week,
            ':activity_title' => $activity_title,
            ':content_title' => $content_title,
            ':content_description' => $content_description,
            ':concept_title' => $concept_title,
            ':main_school_class_id' => $main_school_class_id,
            ':subject' => $subject,
            ':video_url' => $video_url,
            ':content' => $content,
            ':id' => $id
        ]);


       
         if (!empty($file_urls)) {
            // Eski dosyaları sil

            $descriptions = $_POST['descriptions'] ?? null;
            // Yeni dosyalar ve açıklamalar ekle
            foreach ($file_urls as $index => $url) {
                // Yeni dosya ve açıklama
                $description = isset($descriptions[$index]) ? $descriptions[$index] : '';  // Açıklama kontrolü

                $stmtFile = $pdo->prepare("INSERT INTO mainschool_content_file_lnp (main_id, file_path, description) VALUES (:main_id, :file_path, :description)");
                $stmtFile->execute([
                    ':main_id' => $id,
                    ':file_path' => $url,
                    ':description' => $description
                ]);
            }
        }

        echo json_encode(['status' => 'success', 'message' => 'Güncelleme başarılı!']);
    } else {
        // Yeni kayıt işlemi
        $sql = "INSERT INTO main_school_content_lnp 
        (roles, month, week_id, main_school_class_id, activity_title_id, content_title_id, concept_title_id, subject, video_url, content_description, content) 
        VALUES 
        (:roles, :month, :week_id, :main_school_class_id, :activity_title, :content_title, :concept_title, :subject, :video_url, :content_description, :content)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':roles' => $rolesStr,
            ':month' => $month,
            ':week_id' => $week,
            ':main_school_class_id' => $main_school_class_id,
            ':activity_title' => $activity_title,
            ':content_title' => $content_title,
            ':concept_title' => $concept_title,
            ':subject' => $subject,
            ':video_url' => $video_url,
            ':content_description' => $content_description,
            ':content' => $content
        ]);

        $mainId = $pdo->lastInsertId();

        // Eğer dosya yüklendiyse ayrı tabloya kaydet
        if (!empty($file_urls)) {

            $descriptions = $_POST['descriptions'] ?? null;


            foreach ($file_urls as $index => $url) {
                $description = isset($descriptions[$index]) ? $descriptions[$index] : '';
                $stmtFile = $pdo->prepare("INSERT INTO mainschool_content_file_lnp (main_id, file_path, description) VALUES (:main_id, :file_path, :description)");
                $stmtFile->execute([
                    ':main_id' => $mainId,
                    ':file_path' => $url,
                    ':description' => $description
                ]);
            }
        }
         if (!empty($image_urls)) {

            


            foreach ($image_urls as $index => $url) {
                $stmtFile = $pdo->prepare("INSERT INTO main_school_primary_images (main_id, file_path) VALUES (:main_id, :file_path )");
                $stmtFile->execute([
                    ':main_id' => $mainId,
                    ':file_path' => $url
                ]);
            }
        }
        

        echo json_encode(['status' => 'success', 'message' => 'Ekleme başarılı!']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
