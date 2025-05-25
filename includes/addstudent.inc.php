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
	$parentFirstName = htmlspecialchars(trim($_POST['parent-first-name']));
	$parentLastName = htmlspecialchars(trim($_POST['parent-last-name']));
	$address = htmlspecialchars(trim($_POST['address']));
	$district = htmlspecialchars(trim($_POST['district']));
	$postcode = htmlspecialchars(trim($_POST['postcode']));
	$city = htmlspecialchars(trim($_POST['city']));
	$tckn = htmlspecialchars(trim($_POST['tckn']));
	$pack = trim($_POST["pack"]);

    /*$fileTempPath = $_FILES['image']['tmp_name'];
    $fileName = $_FILES['image']['name'];
    $fileSize = $_FILES['image']['size'];*/

	// Instantiate AddStudentContr class
	include_once "../classes/dbh.classes.php";
	include_once "../classes/addstudent.classes.php";
	include_once "../classes/addstudent-contr.classes.php";
	include_once "../classes/slug.classes.php";
	include_once "../classes/addimage.classes.php";
	include_once "../classes/createpassword.classes.php";
	include_once "../classes/Mailer.php";
	include_once "../classes/packages.classes.php";


	$addStudent = new AddStudentContr($photoSize, $photoName, $fileTmpName, $name, $surname, $username, $gender, $birthdate, $email, $telephone, $school, $classes, $parentFirstName, $parentLastName, $address, $district, $postcode, $city, $tckn, $pack);

	// Running error handlers and school addStudent
	$addStudent->addStudentDb();


	// Going to back to products page
	//header("location: ../kategoriler");
}
