<?php

if($_SERVER['REQUEST_METHOD'] == "POST"){

	// Grabbing the data
	$id = $_POST["id"];

	// Instantiate UpdateProductContr class
	include_once "../classes/dbh.classes.php";
	include_once "../classes/update_coupon.classes.php";
	include_once "../classes/update_coupon-contr.classes.php";

	$updateCoupon = new UpdateCouponContr($id);

	// Running error handlers and product updateProduct
	$updateCoupon->updateCouponDb();


}