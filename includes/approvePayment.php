<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	// Grabbing the data
	$transfer_id = trim($_POST["id"]);
	$user_id = trim($_POST["ekBilgi"]);

	// Instantiate AddBankTransferContr class
	include_once "../classes/dbh.classes.php";
	include_once "../classes/banktransfer.classes.php";
	include_once "../classes/banktransfer-contr.classes.php";

	$updateTransfer = new AddBankTransferContr($transfer_id, $user_id);

	// Running error handlers and addUnit
	$updateTransfer->updateTransferDb();




	// Going to back to products page
	//header("location: ../kategoriler");
}
