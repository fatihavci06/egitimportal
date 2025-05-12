<?php

// İçerik türünü ayarla
header('Content-Type: application/json');

session_start();

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	// Grabbing the data
	$class = $_POST["secim"];

	// Instantiate AddUnitContr class
	include_once "../classes/dbh.classes.php";
	include_once "../classes/packages.classes.php";
	include_once "../classes/packages-view.classes.php";

	$getPackage = new ShowPackage();

	// Running error handlers and getPackage
	$getPackage->showPackages($class);

	


	// Going to back to products page
	//header("location: ../kategoriler");
}
