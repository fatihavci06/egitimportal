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
	$tarihi = $_POST['tarihi'];

	// Instantiate AddWeeklyContr class
	include_once "../classes/dbh.classes.php";
	include_once "../classes/dateformat.classes.php";
	include_once "../classes/addweekly.classes.php";
	include_once "../classes/addweekly-contr.classes.php";
	include_once "../classes/slug.classes.php";
	include_once "../classes/addimage.classes.php";


	$addWeekly = new AddWeeklyContr($photoSize, $photoName, $fileTmpName, $name, $classes, $lessons, $units, $short_desc, $tarihi);

	// Running error handlers and addWeekly
	$addWeekly->addWeeklyDb();

	


	// Going to back to products page
	//header("location: ../kategoriler");
}
