<?php
include "../classes/dbh.classes.php";
include "../classes/addclasses.classes.php";
include "../classes/addclasses-contr.classes.php";
include "../classes/slug.classes.php";

$service = $_GET['service'] ?? 'create';

$roles = isset($_POST['roles']) ? $_POST['roles'] : [];
$month = $_POST['month'] ?? '';
$week = $_POST['week'] ?? '';
$activity_title = $_POST['activity_title'] ?? '';
$content_title = $_POST['content_title'] ?? '';
$concept_title = $_POST['concept_title'] ?? '';
$subject = $_POST['subject'] ?? '';
$secim = $_POST['secim'] ?? '';
$video_url = $_POST['video_url'] ?? '';
$content = $_POST['content'] ?? '';
$id = $_POST['id'] ?? null; // Update için id gerekli
$content_description=$_POST['content_description']??null;

$file_path = null;
if (isset($_FILES['file_path']) && $_FILES['file_path']['error'] === UPLOAD_ERR_OK) {
	$file_path = $_FILES['file_path'];
}

try {
	if ($file_path) {
		$uploadDir = __DIR__ . '/../uploads/mainschool/';
		if (!is_dir($uploadDir)) {
			mkdir($uploadDir, 0755, true);
		}
		$fileName = basename($file_path['name']);
		$targetFile = $uploadDir . $fileName;

		if (move_uploaded_file($file_path['tmp_name'], $targetFile)) {
			$file_url = 'uploads/mainschool/' . $fileName;
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Dosya yüklenemedi.']);
			exit;
		}
	}

	$db = new Dbh();
	$pdo = $db->connect();
	$roles = implode(',', $roles);

	$file_url = $secim === 'file_path' && isset($file_url) ? $file_url : null;

	if ($service === 'update' && $id) {

		// GÜNCELLEME
		$sql = "UPDATE main_school_content_lnp SET
			roles = :roles,
			month = :month,
			week_id = :week_id,
			activity_title_id = :activity_title,
			content_title_id = :content_title,
			concept_title_id = :concept_title,
			subject = :subject,
			content_description=:content_description,
			video_url = :video_url,
			file_path = :file_path,
			content = :content
			WHERE id = :id";

		$stmt = $pdo->prepare($sql);
		$stmt->execute([
			':roles' => $roles,
			':month' => $month,
			':week_id' => $week,
			':activity_title' => $activity_title,
			':content_title' => $content_title,
			':concept_title' => $concept_title,
			':content_description'=>$content_description,
			':subject' => $subject,
			':video_url' => $secim === 'video_link' ? $video_url : null,
			':file_path' => $secim === 'file_path' ? $file_url : null,
			':content' => $secim === 'content' ? $content : null,
			':id' => $id
		]);

		echo json_encode(['status' => 'success', 'message' => 'Güncelleme başarılı!']);
	} else {
		
		// EKLEME
		$sql = "INSERT INTO main_school_content_lnp 
			(roles, month, week_id, activity_title_id,content_description, content_title_id, concept_title_id, subject, video_url, file_path, content) 
			VALUES 
			(:roles, :month, :week_id, :activity_title,:content_description, :content_title, :concept_title, :subject, :video_url, :file_path, :content)";

		$stmt = $pdo->prepare($sql);
		$stmt->execute([
			':roles' => $roles,
			':month' => $month,
			':week_id' => $week,
			':content_description'=>$content_description,
			':activity_title' => $activity_title,
			':content_title' => $content_title,
			':concept_title' => $concept_title,
			':subject' => $subject,
			':video_url' => $secim === 'video_link' ? $video_url : null,
			':file_path' => $secim === 'file_path' ? $file_url : null,
			':content' => $secim === 'content' ? $content : null,
		]);

		echo json_encode(['status' => 'success', 'message' => 'Ekleme başarılı!']);
	}
} catch (Exception $e) {
	http_response_code(500);
	echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
