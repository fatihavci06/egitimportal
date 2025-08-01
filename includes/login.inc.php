<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	// Grabbing the date
	$email = $_POST['email'];
	$password = $_POST['password'];
	$screenSize = $_POST['screen_size'];
	$deviceModel = $_POST['device_model'];
	$deviceType = $_POST['device_type'];
	$browser = $_POST['browser'];
	$os = $_POST['device_os'];

	// Instantiate SignupContr class
	include_once "../classes/dbh.classes.php";
	include_once "../classes/login.classes.php";
	include_once "../classes/login-contr.classes.php";

	$login = new LoginContr($email, $password, $screenSize, $deviceModel, $deviceType, $browser, $os);

	// Running error handlers and user login
	$login->loginUser();

	// Going to back to front page
	if ($_SESSION['role'] == 1 or $_SESSION['role'] == 2 or $_SESSION['role'] == 3 or $_SESSION['role'] == 4 or $_SESSION['role'] == 5 or $_SESSION['role'] == 6 or $_SESSION['role'] == 7 or $_SESSION['role'] == 8 or $_SESSION['role'] == 9 or $_SESSION['role'] == 10 or $_SESSION['role'] == 10001 or $_SESSION['role'] == 10002 or $_SESSION['role'] == 10005) {
		header("location: ../dashboard");
	} else {
	}
}
