<?php

if($_SERVER['REQUEST_METHOD'] == "POST"){

	// Grabbing the date
	$email = $_POST['email'];
	$password = $_POST['password'];

	// Instantiate SignupContr class
	include_once "../classes/dbh.classes.php";
	include_once "../classes/login.classes.php";
	include_once "../classes/login-contr.classes.php";
	$login = new LoginContr($email, $password);

	// Running error handlers and user login
	$login->loginUser();

	// Going to back to front page
	if($_SESSION['role'] == 1 OR $_SESSION['role'] == 2 OR $_SESSION['role'] == 3 OR $_SESSION['role'] == 4 OR $_SESSION['role'] == 5 OR $_SESSION['role'] == 6 OR $_SESSION['role'] == 7 OR $_SESSION['role'] == 8 OR $_SESSION['role'] == 10001 OR $_SESSION['role'] == 10002){
		header("location: ../dashboard");
	}else{

	}

}