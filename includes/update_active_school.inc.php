<?php

if($_SERVER['REQUEST_METHOD'] == "POST"){

	// Grabbing the data
	$email = $_POST["email"];
	$statusVal = $_POST["statusVal"];

	// Instantiate UpdateProductContr class
	include_once "../classes/dbh.classes.php";
	include_once "../classes/update_active_school.classes.php";
	include_once "../classes/update_active_school-contr.classes.php";

	$updateSchool = new UpdateActiveSchoolContr($email, $statusVal);

	// Running error handlers and product updateProduct
	$updateSchool->updateActiveSchoolDb();


}