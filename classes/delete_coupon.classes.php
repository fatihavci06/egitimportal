<?php
class DeleteCoupon extends Dbh
{
    protected function deleteCoupon($id)
    {
         $stmt = $this->connect()->prepare('UPDATE coupon_lnp SET is_active = :isActive WHERE id= :id');

            if (!$stmt->execute([':isActive' => 0, ':id' => $id])) {
                $stmt = null;
                exit();
            }

            echo json_encode(["status" => "success", "message" => 'Kupon başarıyla kaldırılı']);
            $stmt = null;
    }
}
