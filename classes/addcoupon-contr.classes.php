<?php

class AddCouponContr extends AddCoupon
{

    private $discount_type;
    private $discount_value;
    private $coupon_code;

    public function __construct($discount_type, $discount_value, $coupon_code)
    {
        $this->discount_type = $discount_type;
        $this->discount_value = $discount_value;
        $this->coupon_code = $coupon_code;
    }

    public function addCouponDb()
    {
        $this->setCoupon($this->discount_type, $this->discount_value, $this->coupon_code);
    }
}
