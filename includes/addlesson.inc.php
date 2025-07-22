<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	// Grabbing the data
	$name = $_POST["name"];
	$classes = $_POST["classes"] ?? [];
	$package_type = $_POST["package_type"] ?? "0";
	$classes = implode(";", array_map('htmlspecialchars', $classes));

	// echo json_encode(["status" => "error", "message" => $_POST]);
	// exit();
	// echo json_encode($_POST);
	// exit();

	if (empty($name)) {

		echo json_encode(["status" => "error", "message" => "Bir hata oluştu"]);
		exit();
	}

	// Instantiate AddLessonContr class
	include "../classes/dbh.classes.php";
	include "../classes/addlesson.classes.php";
	include "../classes/addlesson-contr.classes.php";
	include "../classes/slug.classes.php";


	$addLesson = new AddLessonContr($name, $classes, $package_type);



	// Running error handlers and school addLesson
	$addLesson->addLessonDb();




	// Going to back to products page
	//header("location: ../kategoriler");
}
