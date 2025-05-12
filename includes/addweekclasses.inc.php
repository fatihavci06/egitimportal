<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	// Grabbing the data
	$name = $_POST["name"];

	// Instantiate AddClassContr class
	include "../classes/dbh.classes.php";
	include "../classes/addclasses.classes.php";
	include "../classes/addclasses-contr.classes.php";
	include "../classes/slug.classes.php";


	$addClass = new AddClassesContr($name,'important_weeks_lnp');

	// Running error handlers and school addClass
	$addClass->addClassDb();

	



	// Going to back to products page
	//header("location: ../kategoriler");
}
