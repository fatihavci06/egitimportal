<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	// Grabbing the data
	$name = $_POST["name"];
	$iframe = $_POST["iframe"];
	$photoSize = $_FILES['photo']['size'];
	$photoName = $_FILES['photo']['name'];
	$fileTmpName = $_FILES['photo']['tmp_name'];
	$classAdd = $_POST['classAdd'];

	// Instantiate AddGameContr class
	include_once "../classes/dbh.classes.php";
	include_once "../classes/addgame.classes.php";
	include_once "../classes/addgame-contr.classes.php";
	include_once "../classes/slug.classes.php";
	include_once "../classes/addimage.classes.php";


	$addGame = new AddGameContr($name, $iframe, $photoSize, $photoName, $fileTmpName, $classAdd);

	// Running error handlers and school addGame
	$addGame->addGameDb();

	


	// Going to back to products page
	//header("location: ../kategoriler");
}
