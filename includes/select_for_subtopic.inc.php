<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(1);

// İçerik türünü ayarla
header('Content-Type: application/json');

session_start();

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	// Grabbing the data
	$class = $_POST["class"];
	$lessons = $_POST["lesson"];
	$units = $_POST["unit"];
	$topics = $_POST["topics"];

	// Instantiate AddUnitContr class
	include_once "../classes/dbh.classes.php";
	include_once "../classes/topics.classes.php";
	include_once "../classes/topics-view.classes.php";

	$addUnit = new ShowSubTopic();

	// Running error handlers and addUnit
	$addUnit->showSubtopicForTopic($class, $lessons, $units,$topics);


	// Going to back to products page
	//header("location: ../kategoriler");
}
