<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	// Grabbing the data
	$id = $_POST["id"];
	$discount_type = trim($_POST['discount_type']);
    $discount_value = trim($_POST['discount_value']);
    $coupon_code = trim($_POST['coupon_code']);
    $coupon_expires = $_POST['coupon_expires'];
    $coupon_quantity = trim($_POST['coupon_quantity']);

	// Instantiate UpdateProductContr class
	include_once "../classes/dbh.classes.php";
	include_once "../classes/update_coupon.classes.php";
	include_once "../classes/update_coupon_contr.classes.php";

	$updateCoupon = new UpdateCouponContr($id,$discount_type, $discount_value, $coupon_code, $coupon_expires, $coupon_quantity);

	// Running error handlers and product updateProduct
	$updateCoupon->updateCouponStatusDb();

	
}
