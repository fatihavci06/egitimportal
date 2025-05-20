<?php

class UpdateCouponContr extends UpdateCoupon
{

    private $id;
    private $discount_type;
    private $discount_value;
    private $coupon_code;
    private $coupon_expires;
    private $coupon_quantity;

    public function __construct(
        $id,
        $discount_type,
        $discount_value,
        $coupon_code,
        $coupon_expires,
        $coupon_quantity
    ) {
        $this->id = $id;
        $this->discount_type = $discount_type;
        $this->discount_value = $discount_value;
        $this->coupon_code = $coupon_code;
        $this->coupon_expires = $coupon_expires;
        $this->coupon_quantity = $coupon_quantity;
    }

    public function updateCouponStatusDb()
    {
        $this->updateCouponWithStatus($this->id,
    $this->discount_type,
$this->discount_value,
$this->coupon_code,
$this->coupon_expires,
$this->coupon_quantity);
    }
}
