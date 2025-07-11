<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == "POST") {

	// Grabbing the data
	$contentName = trim($_POST["name"]) ?? '';
	$classes = trim($_POST["classes"]) ?? '';
	$lessons = trim($_POST["lessons"]) ?? '';
	$units = trim($_POST["units"]) ?? '';
	$topics = trim($_POST["topics"]) ?? '';
	$sub_topics = trim($_POST["sub_topics"]) ?? null;
	$short_desc = trim($_POST["short_desc"]) ?? '';
	$content = $_POST["icerik"] ?? '';
	$video_url = trim($_POST["video_url"]) ?? '';
	$files = $_FILES['file_path'] ?? '';
	$imageFiles = $_FILES['images'] ?? '';
	$descriptions = $_POST['descriptions'] ?? null;

	$titles = $_POST['wordWallTitles'] ?? null; // ['Başlık1', 'Başlık2', ...]
	$urls = $_POST['wordWallUrls'] ?? null;     // ['url1', 'url2', ...]

	$photoSize = $_FILES['photo']['size'];
	$photoName = $_FILES['photo']['name'];
	$fileTmpName = $_FILES['photo']['tmp_name'];

	// Instantiate AddContentContr class
	require_once "../classes/dbh.classes.php";
	require_once "../classes/addcontent.classes.php";
	require_once "../classes/addcontent-contr.classes.php";
	require_once "../classes/slug.classes.php";
	require_once "../classes/addimage.classes.php";
	require_once "../classes/Mailer.php";
	require_once "../classes/user.classes.php";

	$mailer = new Mailer();
	$userObj = new User();
	$admin = $userObj->getUserById(1);

	$is_approved = 0;
	if (($_SESSION['school_id'] == 1) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 3 or $_SESSION['role'] == 8)) {
		$is_approved = 1;
	}
	$addContent = new AddContentContr($contentName, $classes, $lessons, $units, $topics, $sub_topics, $short_desc, $content, $video_url, $files, $imageFiles, $photoSize, $photoName, $fileTmpName, $descriptions, $titles, $urls, $is_approved);

	// Running error handlers and addContent
	$result = $addContent->addContentDb();
	if ($result["status"] == "success") {

		echo json_encode(["status" => "success", "message" => $contentName]);
		if ((!$_SESSION['school_id'] == 1) and !($_SESSION['role'] == 1 or $_SESSION['role'] == 3 or $_SESSION['role'] == 8)) {
			$userInfo = $userObj->getUserById($_SESSION['id']);

			$mailer->sendNewContentApproveRequestNotification('a.bulent.h@gmail.com', "fkewkweopkfpe", "{$userInfo['name']} {$userInfo['surname']}", $contentName, "ejwijio");
		}
	} else {

		echo json_encode(["status" => "error", "message" => "Bir hata oluştu"]);
	}




	// Going to back to products page
	//header("location: ../kategoriler");
}
