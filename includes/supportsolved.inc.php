<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	// Grabbing the data
	$supId = htmlspecialchars(trim($_POST["supId"]));

	// Instantiate AddSupportContr class
	include_once "../classes/dbh.classes.php";
	include_once "../classes/addsupport.classes.php";
	include_once "../classes/addsupport-contr.classes.php";
	include_once "../classes/slug.classes.php";


	$addSupport = new AddSupportSolvedContr($supId);

	// Running error handlers and school addSupport
	$addSupport->addSupportDb();

	


	// Going to back to products page
	//header("location: ../kategoriler");
}
