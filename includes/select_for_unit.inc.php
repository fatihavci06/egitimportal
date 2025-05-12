<?php

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

// İçerik türünü ayarla
header('Content-Type: application/json');

session_start();

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	// Grabbing the data
	$class = $_POST["class"];
	$lessons = $_POST["lesson"];

	// Instantiate AddUnitContr class
	include_once "../classes/dbh.classes.php";
	include_once "../classes/units.classes.php";
	include_once "../classes/units-view.classes.php";

	$addUnit = new ShowUnit();

	// Running error handlers and addUnit
	$addUnit->showUnitForTopicList($class, $lessons);

	


	// Going to back to products page
	//header("location: ../kategoriler");
}
