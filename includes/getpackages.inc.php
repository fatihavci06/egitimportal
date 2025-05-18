<?php

// İçerik türünü ayarla
header('Content-Type: application/json');

session_start();

if ($_SERVER['REQUEST_METHOD'] == "POST" AND $_GET["islem"] == "packages") {

	// Grabbing the data
	$id = $_POST["secim"];

	// Instantiate AddUnitContr class
	include_once "../classes/dbh.classes.php";
	include_once "../classes/packages.classes.php";
	include_once "../classes/packages-view.classes.php";

	$getPackage = new ShowPackage();

	// Running error handlers and getPackage
	$getPackage->showPrice($id);

	
	// Going to back to products page
	//header("location: ../kategoriler");
}


if ($_SERVER['REQUEST_METHOD'] == "POST" AND $_GET["islem"] == "coupon") {

	// Grabbing the data
	$id = $_POST["secim"];

	// Instantiate AddUnitContr class
	include_once "../classes/dbh.classes.php";
	include_once "../classes/packages.classes.php";
	include_once "../classes/packages-view.classes.php";

	$getPackage = new ShowPackage();

	// Running error handlers and getPackage
	$getPackage->getCouponInfo($id);

	
	// Going to back to products page
	//header("location: ../kategoriler");
}


if ($_SERVER['REQUEST_METHOD'] == "POST" AND $_GET["islem"] == "moneytransfer") {

	// Grabbing the data
	$id = $_POST["secim"];

	// Instantiate AddUnitContr class
	include_once "../classes/dbh.classes.php";
	include_once "../classes/packages.classes.php";
	include_once "../classes/packages-view.classes.php";

	$getPackage = new ShowPackage();

	// Running error handlers and getPackage
	$getPackage->getTransferDiscountInfo();

	
	// Going to back to products page
	//header("location: ../kategoriler");
}


if ($_SERVER['REQUEST_METHOD'] == "POST" AND $_GET["islem"] == "noinstallment") {

	// Grabbing the data
	$id = $_POST["secim"];

	// Instantiate AddUnitContr class
	include_once "../classes/dbh.classes.php";
	include_once "../classes/packages.classes.php";
	include_once "../classes/packages-view.classes.php";

	$getPackage = new ShowPackage();
	// Running error handlers and getPackage
	$getPackage->getCashDiscountAmount($id);

	
	// Going to back to products page
	//header("location: ../kategoriler");
}