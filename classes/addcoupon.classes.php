<?php
class AddCoupon extends Dbh
{
    // private $discount_type;
    // private $discount_value;
    // private $coupon_code;
    // private $coupon_expires;
    // private $coupon_quantity;

    // public function __construct($discount_type, $discount_value, $coupon_code, $coupon_expires, $coupon_quantity)
    // {
    //     $this->discount_type = $discount_type;
    //     $this->discount_value = $discount_value;
    //     $this->coupon_code = $coupon_code;
    //     $this->coupon_expires = $coupon_expires;
    //     $this->coupon_quantity = $coupon_quantity;
    // }

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
