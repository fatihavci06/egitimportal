<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	// Grabbing the data
	$name = trim($_POST["name"]);
	$secim = trim($_POST["secim"]);
	$roles = trim($_POST["roles"]);
	$classes = trim($_POST["classes"]);
	$notification = trim($_POST["notification"]);

	if($secim == "1"){
		$toAll = 1;
		$roles = 0;
		$classes = 0;
	}elseif($secim == "users"){
		$toAll = 0;
		$classes = 0;
	}elseif($secim == "classes"){
		$toAll = 0;
		$roles = 0;
	}

	// Instantiate AddNotificationContr class
	include_once "../classes/dbh.classes.php";
	include_once "../classes/addannouncement.classes.php";
	include_once "../classes/addannouncement-contr.classes.php";
	include_once "../classes/slug.classes.php";
	include_once "../classes/addimage.classes.php";


	$addTopic = new AddNotificationContr($name, $roles, $toAll, $classes, $notification);

	// Running error handlers and addTopic
	$addTopic->addNotificationDb();

	


	// Going to back to products page
	//header("location: ../kategoriler");
}
