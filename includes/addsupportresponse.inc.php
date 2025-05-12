<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	// Grabbing the data
	$writer = htmlspecialchars(trim($_POST["writer"]));
	$supId = htmlspecialchars(trim($_POST["supId"]));
	$comment = htmlspecialchars(trim($_POST["comment"]));
	$openBy = htmlspecialchars(trim($_POST["openBy"]));
	$title = htmlspecialchars(trim($_POST["title"]));
	$subject = htmlspecialchars(trim($_POST["subject"]));

	// Instantiate AddSupportContr class
	include_once "../classes/dbh.classes.php";
	include_once "../classes/addsupport.classes.php";
	include_once "../classes/addsupport-contr.classes.php";
	include_once "../classes/slug.classes.php";


	$addSupport = new AddSupportResponseContr($writer, $supId, $comment, $openBy, $title, $subject);

	// Running error handlers and school addSupport
	$addSupport->addSupportDb();

	


	// Going to back to products page
	//header("location: ../kategoriler");
}
