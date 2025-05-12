<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	// Grabbing the data
	

	$timeSpent = $_POST['timeSpent'];

	// Instantiate AddTimeSpendContr class
	include_once "../classes/dbh.classes.php";
	include_once "../classes/addlog.classes.php";
	include_once "../classes/addlog-contr.classes.php";

	$pageUrl = isset($_SERVER['HTTP_REFERER']) ? filter_var($_SERVER['HTTP_REFERER'], FILTER_SANITIZE_URL) : 'bilinmiyor'; // Hangi sayfadan gelindiği (isteğe bağlı)
    $timestamp = date("Y-m-d H:i:s"); // Kayıt zamanı


	$addTimeSpend = new AddTimeSpendContr($timeSpent, $pageUrl, $timestamp);

	// Running error handlers and addTimeSpend
	$addTimeSpend->addTimeSpendDb();

	


	// Going to back to products page
	//header("location: ../kategoriler");
}
