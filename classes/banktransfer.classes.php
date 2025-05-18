<?php

session_start();

class AddBankTransfer extends Dbh {

	protected function updateTransfer($id){
		$stmt = $this->connect()->prepare('UPDATE money_transfer_list_lnp SET status=? WHERE id = ?');

		if(!$stmt->execute(["1", $id])){
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		//echo json_encode(["status" => "success", "message" => 'Ödeme durumu güncellenmiştir.']);
		$stmt = null;
	}

	

	protected function updateUser($id, $nowTime, $endTime, $password){
		$stmt = $this->connect()->prepare('UPDATE users_lnp SET active=?, subscribed_at = ?, subscribed_end = ?, password = ? WHERE id = ?');

		if(!$stmt->execute(["1", $nowTime, $endTime, $password, $id])){
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		//echo json_encode(["status" => "success", "message" => 'Öğrenci durumu güncellenmiştir.']);
		$stmt = null;
	}

	protected function updateParent($id, $password){
		$stmt = $this->connect()->prepare('UPDATE users_lnp SET active=?, password = ? WHERE child_id = ?');

		if(!$stmt->execute(["1", $password, $id])){
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		//echo json_encode(["status" => "success", "message" => 'Öğrenci durumu güncellenmiştir.']);
		$stmt = null;
	}

	protected function getParentName($id){
		$stmt = $this->connect()->prepare('SELECT name, surname, username FROM users_lnp WHERE child_id = ?');

		if(!$stmt->execute([$id])){
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}

		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $getData;

		$stmt = null;
	}

	protected function getUserEmail($id){
		$stmt = $this->connect()->prepare('SELECT email, username FROM users_lnp WHERE id = ?');

		if(!$stmt->execute([$id])){
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}

		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $getData;

		$stmt = null;
	}

	protected function addPackagePayment($user_id, $order_no, $ip_address, $pack_id, $amount, $coupon){
		$stmt = $this->connect()->prepare('INSERT INTO package_payments_lnp SET user_id = ?, pack_id = ?, order_no = ?, payment_type = ?, payment_status = ?, ipAddress=?, pay_amount = ?, coupon = ?');

		if(!$stmt->execute([$user_id, $pack_id, $order_no, "1", "2", $ip_address, $amount, $coupon])){
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		//echo json_encode(["status" => "success", "message" => 'Ödeme durumu güncellenmiştir.']);
		$stmt = null;
	}


}



