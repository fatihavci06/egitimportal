<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	// Grabbing the data
	$name = trim($_POST["name"]);
	$classes = trim($_POST["classes"]);
	$lessons = trim($_POST["lessons"]);
	$short_desc = trim($_POST["short_desc"]);
	$photoSize = $_FILES['photo']['size'];
	$photoName = $_FILES['photo']['name'];
	$fileTmpName = $_FILES['photo']['tmp_name'];

	// Instantiate AddUnitContr class
	include_once "../classes/dbh.classes.php";
	include_once "../classes/addunit.classes.php";
	include_once "../classes/addunit-contr.classes.php";
	include_once "../classes/slug.classes.php";
	include_once "../classes/addimage.classes.php";

	$addUnit = new AddUnitContr($photoSize, $photoName, $fileTmpName, $name, $classes, $lessons, $short_desc);

	// Running error handlers and addUnit
	$addUnit->addUnitDb();

	


	// Going to back to products page
	//header("location: ../kategoriler");
}
