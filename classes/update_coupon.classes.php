    <?php
    class UpdateCoupon extends Dbh
    {
        protected function updateCouponWithStatus($id, $discount_type, $discount_value, $coupon_code, $coupon_expires, $coupon_quantity)
        {
            $stmt = $this->connect()->prepare('UPDATE coupon_lnp 
    SET 
        discount_value = :discount_value,
        coupon_code = :coupon_code,
        coupon_expires = :coupon_expires,
        coupon_quantity = :coupon_quantity 
    WHERE id = :id');

            if (!$stmt->execute([
                ':discount_value' => $discount_value,
                ':coupon_code' => $coupon_code,
                ':coupon_expires' => $coupon_expires,
                ':coupon_quantity' => $coupon_quantity,
                ':id' => $id
            ])) {
                $stmt = null;
                exit();
            }

            echo json_encode(["status" => "success", "message" => 'Kupon başarıyla güncellendi!']);
            $stmt = null;
        }

        // protected function updateCouponStatus($id)
        // {
        //     $stmt = $this->connect()->prepare('UPDATE coupon_lnp SET is_active = :isActive WHERE id= :id');

        //     if (!$stmt->execute([':isActive' => 0, ':id' => $id])) {
        //         $stmt = null;
        //         exit();
        //     }

        //     echo json_encode(["status" => "success", "message" => 'Kupon başarıyla kaldırılı']);
        //     $stmt = null;
        // }
    }
