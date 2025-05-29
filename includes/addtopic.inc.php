<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	$service = $_GET['action'] ?? 'create';

	// Check if the service is 'create'
	if ($service == 'create') {
	// Grabbing the data
	$name = trim($_POST["name"]);
	$classes = trim($_POST["classes"]);
	$lessons = trim($_POST["lessons"]);
	$units = trim($_POST["units"]);
	$short_desc = trim($_POST["short_desc"]);

	$photoSize = $_FILES['photo']['size'];
	$photoName = $_FILES['photo']['name'];
	$fileTmpName = $_FILES['photo']['tmp_name'];

	$start_date = trim($_POST["start_date"]);
	$end_date = trim($_POST["end_date"]);
	$order = trim($_POST["order"]);

	// Instantiate AddTopicContr class
	include_once "../classes/dbh.classes.php";
	include_once "../classes/addtopic.classes.php";
	include_once "../classes/addtopic-contr.classes.php";
	include_once "../classes/slug.classes.php";
	include_once "../classes/addimage.classes.php";


	$addTopic = new AddTopicContr($photoSize, $photoName, $fileTmpName, $name, $classes, $lessons, $units, $short_desc, $start_date, $end_date, $order);

	// Running error handlers and addTopic
	$addTopic->addTopicDb();
	} else {
		// Grabbing the data
		$name = trim($_POST["name"]);
		$short_desc = trim($_POST["short_desc"]);
		$photoSize = $_FILES['photo']['size'];
		$photoName = $_FILES['photo']['name'];
		$fileTmpName = $_FILES['photo']['tmp_name'];
		$start_date = trim($_POST["start_date"]);
		$end_date = trim($_POST["end_date"]);
		$order = trim($_POST["order"]);
		$slug = trim($_POST["slug"]);

		// Instantiate UpdateUnitContr class
		include_once "../classes/dbh.classes.php";
		include_once "../classes/addtopic.classes.php";
		include_once "../classes/addtopic-contr.classes.php";
		include_once "../classes/topics.classes.php";
		include_once "../classes/slug.classes.php";
		include_once "../classes/addimage.classes.php";

		$updateTopic = new UpdateTopicContr($photoSize, $photoName, $fileTmpName, $name, $short_desc, $start_date, $end_date, $order, $slug);

		// Running error handlers and updateUnit
		$updateTopic->updateTopicDb();
	}



}
