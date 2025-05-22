<?php

class DeleteCouponContr extends DeleteCoupon
{

    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function DeleteCouponDb()
    {
        $this->deleteCoupon($this->id);
    }
}
