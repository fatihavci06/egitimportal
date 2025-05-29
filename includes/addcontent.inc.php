<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	// Grabbing the data
	$name = trim($_POST["name"]) ?? '';
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
	include_once "../classes/dbh.classes.php";
	include_once "../classes/addcontent.classes.php";
	include_once "../classes/addcontent-contr.classes.php";
	include_once "../classes/slug.classes.php";
	include_once "../classes/addimage.classes.php";


	$addContent = new AddContentContr($name, $classes, $lessons, $units, $topics, $sub_topics, $short_desc, $content, $video_url, $files, $imageFiles, $photoSize, $photoName, $fileTmpName, $descriptions, $titles, $urls);

	// Running error handlers and addContent
	$addContent->addContentDb();

	


	// Going to back to products page
	//header("location: ../kategoriler");
}
