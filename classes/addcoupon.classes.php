<?php
class AddCoupon extends Dbh
{

    protected function setCoupon($discount_type, $discount_value, $coupon_code, $coupon_expires, $coupon_quantity)
    {
        $checkStmt = $this->connect()->prepare('SELECT COUNT(*) FROM coupon_lnp WHERE coupon_code = ?');
        $checkStmt->execute([$coupon_code]);

        if ($checkStmt->fetchColumn() > 0) {
            echo json_encode(["status" => "error", "message" => "Bu kupon kodu zaten mevcut!"]);
            return;
        }

        // $expireDate = new DateTime($checkStmt['coupon_expires']);
        // $today = new DateTime();

        // if ($expireDate < $today) {
        //     echo json_encode(["status" => "error", "message" => "Kuponun tarihi geçmiş"]);
        //     return;
        // }

        $stmt = $this->connect()->prepare('INSERT INTO coupon_lnp SET discount_type = ?, discount_value = ?, coupon_code = ?, coupon_expires = ?, coupon_quantity = ?');

        if (!$stmt->execute([$discount_type, $discount_value, $coupon_code, $coupon_expires, $coupon_quantity])) {
            $stmt = null;
            exit();
        }
        echo json_encode(["status" => "success", "message" => 'Kupon başarıyla oluşturuldu']);
        $stmt = null;
    }
}
