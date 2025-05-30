<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	$service = $_GET['action'];

	include_once "../classes/dbh.classes.php";
	include_once "../classes/updatecontent.classes.php";
	include_once "../classes/updatecontent-contr.classes.php";
	include_once "../classes/slug.classes.php";
	include_once "../classes/addimage.classes.php";

	// Grabbing the data
	$title = trim($_POST["title"]) ?? '';
	$summary = trim($_POST["summary"]) ?? '';
	$contentId = trim($_POST["contentId"]) ?? '';
	$photoSize = $_FILES['photo']['size'];
	$photoName = $_FILES['photo']['name'];
	$fileTmpName = $_FILES['photo']['tmp_name'];

	if ($service === 'video_url') {
		$video_url = trim($_POST["video_url"]) ?? '';
		$videoId = trim($_POST["videoId"]) ?? '';
		// Instantiate UpdateContentVideoContr class
		$addContent = new UpdateContentVideoContr($title, $summary, $contentId, $video_url, $videoId, $photoSize, $photoName, $fileTmpName);
		// Running error handlers and addContent
		$addContent->updateContentVideoDb();
	}

	if ($service === 'icerik') {
		$content = $_POST["icerik"] ?? NULL;
	}

	if ($service === 'file') {
		$files = $_FILES['file_path'] ?? '';
		$descriptions = $_POST['descriptions'] ?? null;
	}

	if ($service === 'wordwall') {
		$titles = $_POST['wordWallTitles'] ?? null; // ['Başlık1', 'Başlık2', ...]
		$urls = $_POST['wordWallUrls'] ?? null;     // ['url1', 'url2', ...]
	}



	// Instantiate UpdateContentContr class
	/* $addContent = new UpdateContentContr($name, $classes, $lessons, $units, $topics, $sub_topics, $short_desc, $content, $video_url, $files, $imageFiles, $photoSize, $photoName, $fileTmpName, $descriptions, $titles, $urls);

	// Running error handlers and addContent
	$addContent->updateContentDb(); */




	// Going to back to products page
	//header("location: ../kategoriler");
}
