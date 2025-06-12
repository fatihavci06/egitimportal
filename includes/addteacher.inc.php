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
	$lesson = trim($_POST["lessonAdd"]);
	$teacher_role = trim($_POST["teacher_role"]);
    /*$fileTempPath = $_FILES['image']['tmp_name'];
    $fileName = $_FILES['image']['name'];
    $fileSize = $_FILES['image']['size'];*/

	// Instantiate AddTeacherContr class
	include_once "../classes/dbh.classes.php";
	include_once "../classes/addteacher.classes.php";
	include_once "../classes/addteacher-contr.classes.php";
	include_once "../classes/slug.classes.php";
	include_once "../classes/addimage.classes.php";


	$addTeacher = new AddTeacherContr($photoSize, $photoName, $fileTmpName, $name, $surname, $username, $gender, $birthdate, $email, $telephone, $school, $classes, $lesson, $teacher_role);

	// Running error handlers and school addTeacher
	$addTeacher->addTeacherDb();

	


	// Going to back to products page
	//header("location: ../kategoriler");
}
