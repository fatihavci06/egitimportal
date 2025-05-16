<?php

class UpdateCouponContr extends AddCoupon
{

    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function updateCouponDb()
    {
        $this->updateCoupon($this->id);
    }
}
