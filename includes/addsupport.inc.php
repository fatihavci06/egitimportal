<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	// Grabbing the data
	$subject = htmlspecialchars(trim($_POST["subject"]));
	$title = htmlspecialchars(trim($_POST["title"]));
	$comment = htmlspecialchars(trim($_POST["comment"]));
	$photoSize = $_FILES['photo']['size'];
	$photoName = $_FILES['photo']['name'];
	$fileTmpName = $_FILES['photo']['tmp_name'];

	// Instantiate AddSupportContr class
	include_once "../classes/dbh.classes.php";
	include_once "../classes/addsupport.classes.php";
	include_once "../classes/addsupport-contr.classes.php";
	include_once "../classes/slug.classes.php";
	include_once "../classes/addimage.classes.php";


	$addSupport = new AddSupportContr($subject, $title, $comment, $photoSize, $photoName, $fileTmpName);

	// Running error handlers and school addSupport
	$addSupport->addSupportDb();

	


	// Going to back to products page
	//header("location: ../kategoriler");
}
