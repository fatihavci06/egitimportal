<?php
class AddCoupon extends Dbh
{

    public function getAllCoupon()
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


    public function getCoupon($id)
    {
        $stmt = $this->connect()->prepare('SELECT * FROM coupon_lnp WHERE id = :id');

        if (!$stmt->execute([':id' => $id])) {
            $stmt = null;
            exit();
        }

        $coupons = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = null;

        return $coupons;
    }

    public function getCouponWithId($id)
    {
        $stmt = $this->connect()->prepare('SELECT * FROM coupon_lnp WHERE id = :id');

        if (!$stmt->execute([':id' => $id])) {
            $stmt = null;
            exit();
        }

        $coupons = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = null;

        return $coupons;
    }

    public function getCouponWithUsers($id)
    {
        $stmt = $this->connect()->prepare('SELECT 
    c.id AS coupon_id,
    c.coupon_code,
    c.discount_value,
    u.id AS user_id,
    u.name,
    u.surname,
    u.email,
    u.telephone,
    p.coupon
    FROM package_payments_lnp p JOIN users_lnp u ON p.user_id = u.id
    JOIN coupon_lnp c ON p.coupon = c.coupon_code WHERE c.id = :coupon_id');

        if (!$stmt->execute([':coupon_id' => $id])) {
            $stmt = null;
            exit();
        }

        $coupons = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = null;

        return $coupons;
    }

    protected function setCoupon($discount_type, $discount_value, $coupon_code, $coupon_expires, $coupon_quantity)
    {
        $checkStmt = $this->connect()->prepare('SELECT 1 FROM coupon_lnp WHERE coupon_code = ? LIMIT 1');
        $checkStmt->execute([$coupon_code]);

        if ($checkStmt->fetchColumn()) {
            echo json_encode(["status" => "error", "message" => "Bu kupon kodu zaten mevcut!"]);
            return;
        }

        $stmt = $this->connect()->prepare('INSERT INTO coupon_lnp SET discount_type = ?, discount_value = ?, coupon_code = ?, coupon_expires = ?, coupon_quantity = ?');

        if (!$stmt->execute([$discount_type, $discount_value, $coupon_code, $coupon_expires, $coupon_quantity])) {
            $stmt = null;
            exit();
        }
        echo json_encode(["status" => "success", "message" => 'Kupon başarıyla oluşturuldu']);
        $stmt = null;
    }
}
