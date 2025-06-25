<?php

// İçerik türünü ayarla
header('Content-Type: application/json');

session_start();

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	// Grabbing the data
	$class = $_POST["class"];

	// Instantiate AddUnitContr class
	include_once "../classes/dbh.classes.php";
	include_once "../classes/lessons.classes.php";
	include_once "../classes/lessons-view.classes.php";

	$addLesson = new ShowLesson();


	// Running error handlers and addLesson
	$addLesson->showLessonForUnitList($class);

	


	// Going to back to products page
	//header("location: ../kategoriler");
}
