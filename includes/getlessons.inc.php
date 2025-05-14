<?php

header('Content-Type: application/json');

session_start();




if ($_SERVER['REQUEST_METHOD'] == "POST") {

	// Grabbing the data
	require_once "classes/lessons-view.classes.php";

	showLessonForUnitList(); 
	// $db = new LessonLNP('localhost', 'lineup25academy00', 'your_username', 'your_password');

	// $classId = '4'; // for example
	// $lessonsForClass4 = $db->getLessonsByClassId($classId);
	// var_dump($lessonsForClass4);
	// die();

}