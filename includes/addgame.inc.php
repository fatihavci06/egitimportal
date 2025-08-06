<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	// Grabbing the data
	$name = $_POST["name"];
	$iframe = $_POST["iframe"];
	$description = $_POST["description"];
	$photoSize = $_FILES['photo']['size'];
	$photoName = $_FILES['photo']['name'];
	$fileTmpName = $_FILES['photo']['tmp_name'];
	$classes = isset($_POST['classes']) ? (int)$_POST['classes'] : 0;
	$lessons = isset($_POST['lessons']) ? (int)$_POST['lessons'] : 0;
	$units = isset($_POST['units']) ? (int)$_POST['units'] : 0;
	$topics = isset($_POST['topics']) ? (int)$_POST['topics'] : 0;
	$subtopics = isset($_POST['subtopics']) ? (int) $_POST['subtopics'] : 0;

	// Instantiate AddGameContr class
	include_once "../classes/dbh.classes.php";
	include_once "../classes/addgame.classes.php";
	include_once "../classes/addgame-contr.classes.php";
	include_once "../classes/slug.classes.php";
	include_once "../classes/addimage.classes.php";


	$addGame = new AddGameContr($name, $iframe, $description, $photoSize, $photoName, $fileTmpName, $classes, $lessons, $units, $topics, $subtopics);

	// Running error handlers and school addGame
	$addGame->addGameDb();
}
