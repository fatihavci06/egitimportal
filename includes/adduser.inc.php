<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	// Grabbing the data
	$firstName = htmlspecialchars(trim($_POST["first-name"]));
	$lastName = htmlspecialchars(trim($_POST["last-name"]));
	$username = htmlspecialchars(trim($_POST["username"]));
	$tckn = htmlspecialchars(trim($_POST['tckn']));
	$gender = htmlspecialchars(trim($_POST['gender']));
	$birth_day = $_POST['birth_day'];
	$email = htmlspecialchars(trim($_POST['email']));
	$parentFirstName = htmlspecialchars(trim($_POST['parent-first-name']));
	$parentLastName = htmlspecialchars(trim($_POST['parent-last-name']));
	$classes = htmlspecialchars(trim($_POST['classes']));
	$pack = htmlspecialchars(trim($_POST['pack']));
	$address = htmlspecialchars(trim($_POST['address']));
	$district = htmlspecialchars(trim($_POST['district']));
	$postcode = htmlspecialchars(trim($_POST['postcode']));
	$city = htmlspecialchars(trim($_POST['city']));
	$telephone = htmlspecialchars(trim($_POST['telephone']));
	
	if (isset($_POST['coupon_codeDb'])) {
		$couponCode = htmlspecialchars(trim($_POST['coupon_codeDb']));
	} else {
		$couponCode = '';
	}

	$payment_type = htmlspecialchars(trim($_POST['payment_type']));
	
	if (isset($_POST['isinstallment'])) {
		$isinstallment = htmlspecialchars(trim($_POST['isinstallment']));
	} else {
		$isinstallment = '';
	}

	// Instantiate AddUserContr class
	include_once "../classes/dbh.classes.php";
	include_once "../classes/adduser.classes.php";
	include_once "../classes/adduser-contr.classes.php";
	include_once "../classes/slug.classes.php";

	$addUser = new AddUserContr($firstName, $lastName, $username, $tckn, $gender, $birth_day, $email, $parentFirstName, $parentLastName, $classes, $pack, $address, $district, $postcode, $city, $telephone, $couponCode, $payment_type, $isinstallment);

	// Running error handlers and school addUser
	$addUser->addUserDb();

	


	// Going to back to products page
	//header("location: ../kategoriler");
}
