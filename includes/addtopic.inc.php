<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	// Grabbing the data
	$name = trim($_POST["name"]);
	$classes = trim($_POST["classes"]);
	$lessons = trim($_POST["lessons"]);
	$units = trim($_POST["units"]);
	$short_desc = trim($_POST["short_desc"]);

	$photoSize = $_FILES['photo']['size'];
	$photoName = $_FILES['photo']['name'];
	$fileTmpName = $_FILES['photo']['tmp_name'];

	// Instantiate AddTopicContr class
	include_once "../classes/dbh.classes.php";
	include_once "../classes/addtopic.classes.php";
	include_once "../classes/addtopic-contr.classes.php";
	include_once "../classes/slug.classes.php";
	include_once "../classes/addimage.classes.php";


	$addTopic = new AddTopicContr($photoSize, $photoName, $fileTmpName, $name, $classes, $lessons, $units, $short_desc);

	// Running error handlers and addTopic
	$addTopic->addTopicDb();

	


	// Going to back to products page
	//header("location: ../kategoriler");
}
