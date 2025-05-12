<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	// Grabbing the data
	$name = $_POST["name"];
	$address = $_POST["address"];
	$district = $_POST["district"];
	$postcode = $_POST["postcode"];
	$city = $_POST["city"];
	$email = $_POST["email"];
	$telephone = $_POST["telephone"];

	// Instantiate AddProductContr class
	include_once "../classes/dbh.classes.php";
	include_once "../classes/addschool.classes.php";
	include_once "../classes/addschool-contr.classes.php";
	include_once "../classes/slug.classes.php";


	$addSchool = new AddSchoolContr($name, $address, $district, $postcode, $city, $email, $telephone);

	// Running error handlers and school addSchool
	$addSchool->addSchoolDb();

	


	// Going to back to products page
	//header("location: ../kategoriler");
}
