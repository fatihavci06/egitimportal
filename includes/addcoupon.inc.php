<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $discount_type = trim($_POST['discount_type']);
    $discount_value = trim($_POST['discount_value']);
    $coupon_code = trim($_POST['coupon_code']);
    $coupon_expires = $_POST['coupon_expires'];
    $coupon_quantity = trim($_POST['coupon_quantity']);

    // generateCouponCode() u al;

    // $this->setCoupon($discount_type, $discount_value, $coupon_code);

    // Instantiate AddTopicContr class
    include_once "../classes/dbh.classes.php";
    include_once "../classes/addcoupon.classes.php";
    include_once "../classes/addcoupon-contr.classes.php";

    $addCoupon = new AddCouponContr($discount_type, $discount_value, $coupon_code, $coupon_expires, $coupon_quantity);

    // Running error handlers and addTopic
    $addCoupon->addCouponDb();

}
