<?php

if($_SERVER['REQUEST_METHOD'] == "POST"){

	// Grabbing the data
	$username = $_POST["username"];

	// Instantiate UpdateProductContr class
	include_once "../classes/dbh.classes.php";
	include_once "../classes/update_active_student.classes.php";
	include_once "../classes/update_active_student-contr.classes.php";

	$updateStudent = new UpdateActiveStudentContr($username);

	// Running error handlers and product updateProduct
	$updateStudent->updateActiveStudentDb();


}