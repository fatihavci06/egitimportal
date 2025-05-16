<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	// Grabbing the data
	$photoSize = $_FILES['photo']['size'];
	$photoName = $_FILES['photo']['name'];
	$fileTmpName = $_FILES['photo']['tmp_name'];
	$name = trim($_POST["name"]);
	$surname = trim($_POST["surname"]);
	$username = trim($_POST["username"]);
	$gender = trim($_POST["gender"]);
	$birthdate = trim($_POST["birthdate"]);
	$email = trim($_POST["email"]);
	$telephone = trim($_POST["telephone"]);
	$school = trim($_POST["school"]);
	$classes = trim($_POST["classAdd"]);
	$password = password_hash(trim($_POST["password"]), PASSWORD_DEFAULT);
    /*$fileTempPath = $_FILES['image']['tmp_name'];
    $fileName = $_FILES['image']['name'];
    $fileSize = $_FILES['image']['size'];*/

	// Instantiate AddStudentContr class
	include_once "../classes/dbh.classes.php";
	include_once "../classes/package.classes.php";
	include_once "../classes/package-contr.classes.php";
	include_once "../classes/slug.classes.php";
	include_once "../classes/addimage.classes.php";


	$addStudent = new AddStudentContr($photoSize, $photoName, $fileTmpName, $name, $surname, $username, $gender, $birthdate, $email, $telephone, $school, $classes, $password);

	// Running error handlers and school addStudent
	$addStudent->addStudentDb();

	


	// Going to back to products page
	//header("location: ../kategoriler");
}
