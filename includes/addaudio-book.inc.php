<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	// Grabbing the data
	$name = $_POST["name"];
	$iframe = $_POST["iframe"];
	$photoSize = $_FILES['photo']['size'];
	$photoName = $_FILES['photo']['name'];
	$fileTmpName = $_FILES['photo']['tmp_name'];
	$classAdd = $_POST['classAdd'];

	// Instantiate AddAudioBookContr class
	include_once "../classes/dbh.classes.php";
	include_once "../classes/addaudio-book.classes.php";
	include_once "../classes/addaudio-book-contr.classes.php";
	include_once "../classes/slug.classes.php";
	include_once "../classes/addimage.classes.php";


	$addAudioBook = new AddAudioBookContr($name, $iframe, $photoSize, $photoName, $fileTmpName, $classAdd);

	// Running error handlers and school addAudioBook
	$addAudioBook->addAudioBookDb();

	


	// Going to back to products page
	//header("location: ../kategoriler");
}
