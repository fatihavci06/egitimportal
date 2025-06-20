<?php

class AddUser extends Dbh
{

	protected function get_user_ip()
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			// Paylaşımlı internet ortamında (proxy vb.) istemci IP'si
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			// Load Balancer veya Proxy üzerinden gelen IP(ler) (virgülle ayrılmış olabilir)
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			// Doğrudan istemci IP adresi
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}

	protected function setStudent($photo, $name, $surname, $username, $gender, $birthdate, $email, $telephone, $school, $classes, $password)
	{
		$stmt = $this->connect()->prepare('INSERT INTO users_lnp SET photo = ?, name = ?, surname = ?, username = ?, gender = ?, birth_date = ?, email = ?, telephone = ?, school_id = ?, class_id = ?, password=?, role=?, active = ?');

		if (!$stmt->execute([$photo, $name, $surname, $username, $gender, $birthdate, $email, $telephone, $school, $classes, $password, "2", "1"])) {
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		echo json_encode(["status" => "success", "message" => $name . ' ' . $surname]);
		$stmt = null;
	}

	public function setStudent2($firstName, $lastName, $username, $kullanici_tckn, $gender, $birth_day, $kullanici_mail, $class, $pack, $password, $nowTime, $endTime, $kullanici_gsm, $kullanici_adresiyaz, $district, $postcode, $kullanici_il, $role)
	{

		if ($gender == "Kız") {
			$imgName = 'kiz.jpg';
		} else {
			$imgName = 'erkek.jpg';
		}

		$stmt = $this->connect()->prepare('INSERT INTO users_lnp SET name = ?, surname = ?, username = ?, email = ?, password = ?, role = ?, active = ?, telephone = ?, birth_date = ?, gender=?, identity_id=?, address = ?, district=?, postcode=?, city=?, class_id=?, package_id=?, subscribed_at=?, subscribed_end=?, photo =?, school_id = ?');

		if (!$stmt->execute([$firstName, $lastName, $username, $kullanici_mail, $password, $role, "1",  $kullanici_gsm, $birth_day, $gender, $kullanici_tckn, $kullanici_adresiyaz, $district, $postcode, $kullanici_il, $class, $pack, $nowTime, $endTime, $imgName, 1])) {
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		//echo json_encode(["status" => "success", "message" => $name . ' ' . $surname]);
		$stmt = null;
	}

	public function setStudentMoneyTransfer($firstName, $lastName, $username, $kullanici_tckn, $gender, $birth_day, $kullanici_mail, $class, $pack, $password, $kullanici_gsm, $kullanici_adresiyaz, $district, $postcode, $kullanici_il, $role)
	{

		if ($gender == "Kız") {
			$imgName = 'kiz.jpg';
		} else {
			$imgName = 'erkek.jpg';
		}

		$stmt = $this->connect()->prepare('INSERT INTO users_lnp SET name = ?, surname = ?, username = ?, email = ?, password = ?, role = ?, active = ?, telephone = ?, birth_date = ?, gender=?, identity_id=?, address = ?, district=?, postcode=?, city=?, class_id=?, package_id=?, photo =?, school_id = ?');

		if (!$stmt->execute([$firstName, $lastName, $username, $kullanici_mail, $password, $role, "0",  $kullanici_gsm, $birth_day, $gender, $kullanici_tckn, $kullanici_adresiyaz, $district, $postcode, $kullanici_il, $class, $pack, $imgName, 1])) {
			$stmt = null;
			header("location: ../havale-bilgisi.php?error=stmtfailed");
			exit();
		}
		//echo json_encode(["status" => "success", "message" => $name . ' ' . $surname]);
		$stmt = null;
	}

	public function setWaitingMoneyTransfer($kullanici_tckn, $pack, $price, $siparis_no, $kupon_kodu, $vatAmount, $vat)
	{

		$stmt2 = $this->connect()->prepare('SELECT id FROM users_lnp WHERE identity_id = ?');

		if (!$stmt2->execute(array($kullanici_tckn))) {
			$stmt2 = null;
			exit();
		}

		$lastId = $stmt2->fetch(PDO::FETCH_ASSOC);

		$user_ip = $this->get_user_ip();

		$user_id = $lastId['id'];

		$stmt = $this->connect()->prepare('INSERT INTO money_transfer_list_lnp SET user_id = ?, status = ?, order_no = ?, ip_address = ?, pack_id = ?, amount = ?, coupon=?, kdv_amount = ?, kdv_percent = ?');

		if (!$stmt->execute([$user_id, 0, $siparis_no, $user_ip, $pack, $price, $kupon_kodu, $vatAmount, $vat])) {
			$stmt = null;
			header("location: ../admin.php?error=stmtfailed");
			exit();
		}

		$stmt = null;
		$stmt2 = null;

	}

	public function setPaymentInfo($kullanici_tckn, $pack, $siparis_numarasi, $isinstallment, $paidPrice, $commissionRate, $commissionFee, $couponCode, $vatAmount, $vat)
	{
		$stmt = $this->connect()->prepare('INSERT INTO package_payments_lnp SET user_id = ?, pack_id = ?, order_no = ?, payment_type = ?, payment_status = ?,installment = ?, pay_amount = ?, commissionRate = ?, commissionFee = ?, ipAddress = ?, coupon=?, kdv_amount = ?, kdv_percent = ?');

		$stmt2 = $this->connect()->prepare('SELECT id FROM users_lnp WHERE identity_id = ?');

		if (!$stmt2->execute(array($kullanici_tckn))) {
			$stmt2 = null;
			exit();
		}

		$lastId = $stmt2->fetch(PDO::FETCH_ASSOC);

		$user_ip = $this->get_user_ip();

		$user_id = $lastId['id'];

		if (!$stmt->execute([$user_id, $pack, $siparis_numarasi, "2", "3", $isinstallment, $paidPrice, $commissionRate, $commissionFee, $user_ip, $couponCode, $vatAmount, $vat])) {
			$stmt = null;
			header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		$stmt = null;
		$stmt2 = null;
	}

	public function setParent($kullanici_ad, $kullanici_soyad, $username, $password2)
	{

		$stmt2 = $this->connect()->prepare('SELECT id FROM users_lnp ORDER BY id DESC LIMIT 1');

		if (!$stmt2->execute(array())) {
			$stmt2 = null;
			exit();
		}

		$lastId = $stmt2->fetch(PDO::FETCH_ASSOC);

		$lastId = $lastId['id'];

		$stmt = $this->connect()->prepare('INSERT INTO users_lnp SET name = ?, surname = ?, username = ?, password = ?, role = ?, active = ?, child_id=?');

		if (!$stmt->execute([$kullanici_ad, $kullanici_soyad, $username, $password2, "5", "1", $lastId])) {
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}


		$stmt3 = $this->connect()->prepare('SELECT id FROM users_lnp ORDER BY id DESC LIMIT 1');

		if (!$stmt3->execute(array())) {
			$stmt3 = null;
			exit();
		}

		$lastId3 = $stmt3->fetch(PDO::FETCH_ASSOC);

		$lastId3 = $lastId3['id'];

		$stmt4 = $this->connect()->prepare('UPDATE users_lnp SET parent_id = ? WHERE id = ?');

		if (!$stmt4->execute(array($lastId3, $lastId))) {
			$stmt4 = null;
			exit();
		}

		//echo json_encode(["status" => "success", "message" => $name . ' ' . $surname]);
		$stmt = null;
		$stmt2 = null;
		$stmt3 = null;
		$stmt4 = null;
	}

	public function checkTckn($tckn)
	{
		$stmt = $this->connect()->prepare('SELECT identity_id FROM users_lnp WHERE identity_id = ? ORDER BY identity_id ASC');

		if (!$stmt->execute([$tckn])) {
			$stmt = null;
			header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$stmt = null;

		return $result;
	}

	public function checkEmail($email)
	{
		$stmt = $this->connect()->prepare('SELECT email FROM users_lnp WHERE email = ? ORDER BY email ASC');

		if (!$stmt->execute([$email])) {
			$stmt = null;
			/*header("location: ../admin.php?error=stmtfailed");*/
			exit();
		}
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$stmt = null;

		return $result;
	}

	public function checkUsername($username)
	{
		$stmt = $this->connect()->prepare('SELECT username FROM users_lnp WHERE username = ? ORDER BY username ASC');

		if (!$stmt->execute([$username])) {
			$stmt = null;
			/*header("location: ../admin.php?error=stmtfailed");*/
			exit();
		}
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$stmt = null;

		return $result;
	}

	public function checkTelephone($telephone)
	{
		$stmt = $this->connect()->prepare('SELECT telephone FROM users_lnp WHERE telephone = ? ORDER BY telephone ASC');

		if (!$stmt->execute([$telephone])) {
			$stmt = null;
			/*header("location: ../admin.php?error=stmtfailed");*/
			exit();
		}
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$stmt = null;

		return $result;
	}


	// public function checkCoupon($couponCode)
	// {
	// 	$stmt = $this->connect()->prepare('SELECT coupon_code, discount_type, coupon_expires, coupon_quantity FROM coupon_lnp WHERE coupon_code = ? ');

	// 	if (!$stmt->execute(array($couponCode))) {
	// 		$stmt = null;
	// 		exit();
	// 	}

	// 	$coupon = $stmt->fetchAll(PDO::FETCH_ASSOC);

	// 	$stmt = null;

	// 	return $coupon;

	// 	if ($coupon !== false) {

	// 		$expireDate = new DateTime($coupon['coupon_expires']);
	// 		$today = new DateTime();

	// 		$expireDateFormatted = $expireDate->format('Y-m-d');
	// 		$todayFormatted = $today->format('Y-m-d');

	// 		if ($expireDateFormatted < $todayFormatted) {
	// 			echo json_encode(["status" => "error", "message" => "Kuponun Süresi Dolmuş!"]);
	// 			return;
	// 		}

	// 		echo json_encode(["status" => "error", "message" => "Kupon bulunamadı!"]);
	// 		return;
	// 	}

	// 	if ($cupon['coupon_quantity' >= 0]) {
	// 		echo json_encode(["status" => "error", "message" => "Kuponun kullanım hakkı kalmamış!"]);
	// 		return;
	// 	}
	// }
}
