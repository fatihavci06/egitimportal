<?php
class Coupon extends Dbh
{

    protected function getAllCoupon()
    {
        $stmt = $this->connect()->prepare('SELECT * FROM coupon_lnp WHERE is_active = :isActive');

        if (!$stmt->execute([':isActive' => 1])) {
            $stmt = null;
            exit();
        }

        $coupons = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = null;

        return $coupons;
    }
}