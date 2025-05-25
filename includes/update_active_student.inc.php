<?php

if($_SERVER['REQUEST_METHOD'] == "POST"){

	// Grabbing the data
	$email = $_POST["email"];
	$statusVal = $_POST["statusVal"];

	// Instantiate UpdateProductContr class
	include_once "../classes/dbh.classes.php";
	include_once "../classes/update_active_student.classes.php";
	include_once "../classes/update_active_student-contr.classes.php";

	$updateStudent = new UpdateActiveStudentContr($email, $statusVal);

	// Running error handlers and product updateProduct
	$updateStudent->updateActiveStudentDb();


}