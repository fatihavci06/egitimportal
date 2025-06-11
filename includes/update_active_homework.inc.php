<?php

if($_SERVER['REQUEST_METHOD'] == "POST"){

	// Grabbing the data
	$id = $_POST["id"];
	$statusVal = $_POST["statusVal"];

	// Instantiate UpdateProductContr class
	include_once "../classes/dbh.classes.php";
	include_once "../classes/update_active_homework.classes.php";
	include_once "../classes/update_active_homework-contr.classes.php";

	$updateContent = new UpdateActiveHomeworkContr($id, $statusVal);

	// Running error handlers and product updateProduct
	$updateContent->updateActiveHomeworkDb();


}