<?php
class AddCoupon extends Dbh
{

    protected function setCoupon($discount_type, $discount_value, $coupon_code)
    {
        $stmt = $this->connect()->prepare('INSERT INTO coupon_lnp SET discount_type = ?, discount_value = ?, coupon_code = ?');

        if (!$stmt->execute([$discount_type, $discount_value, $coupon_code])) {
            $stmt = null;
            exit();
        }
        echo json_encode(["status" => "success", "message" => 'Kupon başarıyla oluşturuldu']);;
        $stmt = null;
    }

    // protected function generateCouponCode($length = 8)
    // {
    //     $characters = "QWERTYUOPASDFGHJKLZXCVBNM123456789";
    //     $coupon = "";
    //     for ($i = 0; $i < $length; $i++) {
    //         $coupon .= $characters[random_int(0, strlen($characters) - 1)];
    //     }
    //     return $coupon;
    // }
}
