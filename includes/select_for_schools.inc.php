<?php

// İçerik türünü ayarla
header('Content-Type: application/json');

session_start();

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	// Grabbing the data

	// Instantiate AddUnitContr class
	include_once "../classes/dbh.classes.php";
	include_once "../classes/school.classes.php";
	include_once "../classes/school-view.classes.php";

	$showSchool = new showSchool();

	// Running error handlers and showSchool
	$showSchool->getSchoolSelectListJson();

			


	// Going to back to products page
	//header("location: ../kategoriler");
}
