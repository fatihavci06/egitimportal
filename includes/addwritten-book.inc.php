<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {


	// Grabbing the data
	$name = $_POST["name"];
	$iframe = $_POST["iframe"];
	$photoSize = $_FILES['photo']['size'];
	$photoName = $_FILES['photo']['name'];
	$fileTmpName = $_FILES['photo']['tmp_name'];
	$classes = isset($_POST['classes']) ? (int)$_POST['classes'] : 0;
	$lessons = isset($_POST['lessons']) ? (int)$_POST['lessons'] : 0;
	$units = isset($_POST['units']) ? (int)$_POST['units'] : 0;
	$topics = isset($_POST['topics']) ? (int)$_POST['topics'] : 0;
	$subtopics = isset($_POST['subtopics']) ? (int) $_POST['subtopics'] : 0;


	// Instantiate AddAudioBookContr class
	include_once "../classes/dbh.classes.php";
	include_once "../classes/addwritten-book.classes.php";
	include_once "../classes/addwritten-book-contr.classes.php";
	include_once "../classes/slug.classes.php";
	include_once "../classes/addimage.classes.php";
	


	$addBook = new AddWrittenBookContr($name, $iframe, $photoSize, $photoName, $fileTmpName, $classes, $lessons, $units, $topics, $subtopics);

	// $addBook = new AddAudioBookContr($name, $iframe, $photoSize, $photoName, $fileTmpName, 3, 1, 1, 1, 3);

	// Running error handlers and school addBook

	$addBook->addWrittenBookDb();






	// Going to back to products page
	//header("location: ../kategoriler");
}
