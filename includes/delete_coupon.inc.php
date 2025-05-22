<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    // Grabbing the data
    $id = $_POST["id"];

    // Instantiate UpdateProductContr class
    include_once "../classes/dbh.classes.php";
    include_once "../classes/delete_coupon.classes.php";
    include_once "../classes/delete_coupon_contr.classes.php";
    $deleteCoupon = new DeleteCouponContr($id);

    // Running error handlers and product updateProduct
    $deleteCoupon->deleteCouponDb();
}
