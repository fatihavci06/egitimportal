<?php

if($_SERVER['REQUEST_METHOD'] == "POST"){

	// Grabbing the data
	$id = $_POST["id"];
	$statusVal = $_POST["statusVal"];

	// Instantiate UpdateProductContr class
	include_once "../classes/dbh.classes.php";
	include_once "../classes/update_active_unit.classes.php";
	include_once "../classes/update_active_unit-contr.classes.php";

	$updateUnit = new UpdateActiveUnitContr($id, $statusVal);

	// Running error handlers and product updateProduct
	$updateUnit->updateActiveUnitDb();


}