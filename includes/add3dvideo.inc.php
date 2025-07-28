<?php
session_start();
error_reporting(E_ALL); // Tüm hataları raporla
ini_set('display_errors', 1); // Hataları ekranda göster
if ($_SERVER['REQUEST_METHOD'] == "POST") {

	// Grabbing the data
	$name = trim($_POST["name"]) ?? '';
	$classes = trim($_POST["classes"]) ?? '';
	$short_desc = trim($_POST["short_desc"]) ?? '';
	$content = $_POST["icerik"] ?? '';
	$video_url = trim($_POST["video_url"]) ?? '';

	$photoSize = $_FILES['photo']['size'];
	$photoName = $_FILES['photo']['name'];
	$fileTmpName = $_FILES['photo']['tmp_name'];

	// Instantiate AddContentContr class
	require_once "../classes/dbh.classes.php";
	require_once "../classes/add3dvideo.classes.php";
	require_once "../classes/add3dvideo-contr.classes.php";
	require_once "../classes/slug.classes.php";
	require_once "../classes/addimage.classes.php";

	$addContent = new AddContentContr($name, $classes, $short_desc, $video_url, $photoSize, $photoName, $fileTmpName);

	// Running error handlers and addContent
	$addContent->addContentDb();




	// Going to back to products page
	//header("location: ../kategoriler");
}
