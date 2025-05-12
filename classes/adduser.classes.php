<?php

class AddUser extends Dbh {
	
	protected function setStudent($photo, $name, $surname, $username, $gender, $birthdate, $email, $telephone, $school, $classes, $password){
		$stmt = $this->connect()->prepare('INSERT INTO users_lnp SET photo = ?, name = ?, surname = ?, username = ?, gender = ?, birth_date = ?, email = ?, telephone = ?, school_id = ?, class_id = ?, password=?, role=?, active = ?');

		if(!$stmt->execute([$photo, $name, $surname, $username, $gender, $birthdate, $email, $telephone, $school, $classes, $password, "2", "1"])){
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		echo json_encode(["status" => "success", "message" => $name . ' ' . $surname]);
		$stmt = null;
	}

	public function setStudent2($firstName, $lastName, $username, $kullanici_tckn, $gender, $birth_day, $kullanici_mail, $class, $pack, $password, $nowTime, $endTime, $kullanici_gsm, $kullanici_adresiyaz, $district, $postcode, $kullanici_il){

		if($gender == "Kız"){
			$imgName = 'kiz.jpg';
		}else{
			$imgName = 'erkek.jpg';
		}

		$stmt = $this->connect()->prepare('INSERT INTO users_lnp SET name = ?, surname = ?, username = ?, email = ?, password = ?, role = ?, active = ?, telephone = ?, birth_date = ?, gender=?, identity_id=?, address = ?, district=?, postcode=?, city=?, class_id=?, package_id=?, subscribed_at=?, subscribed_end=?, photo =?');

		if(!$stmt->execute([$firstName, $lastName, $username, $kullanici_mail, $password, "2", "1",  $kullanici_gsm, $birth_day, $gender, $kullanici_tckn, $kullanici_adresiyaz, $district, $postcode, $kullanici_il, $class, $pack, $nowTime, $endTime, $imgName])){
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		//echo json_encode(["status" => "success", "message" => $name . ' ' . $surname]);
		$stmt = null;
	}

	public function setParent($kullanici_ad, $kullanici_soyad, $username, $password2){

		$stmt2 = $this->connect()->prepare('SELECT id FROM users_lnp ORDER BY id DESC LIMIT 1');

		if (!$stmt2->execute(array())) {
			$stmt2 = null;
			exit();
		}

		$lastId = $stmt2->fetch(PDO::FETCH_ASSOC);

		$lastId = $lastId['id'];

		$stmt = $this->connect()->prepare('INSERT INTO users_lnp SET name = ?, surname = ?, username = ?, password = ?, role = ?, active = ?, child_id=?');

		if(!$stmt->execute([$kullanici_ad, $kullanici_soyad, $username, $password2, "5", "1", $lastId])){
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

	public function checkTckn($tckn){
		$stmt = $this->connect()->prepare('SELECT identity_id FROM users_lnp WHERE identity_id = ? ORDER BY identity_id ASC');

		if(!$stmt->execute([$tckn])){
			$stmt = null;
			header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$stmt = null;
		
		return $result;
	}

	public function checkEmail($email){
		$stmt = $this->connect()->prepare('SELECT email FROM users_lnp WHERE email = ? ORDER BY email ASC');

		if(!$stmt->execute([$email])){
			$stmt = null;
			/*header("location: ../admin.php?error=stmtfailed");*/
			exit();
		}
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$stmt = null;
		
		return $result;
	}

	public function checkUsername($username){
		$stmt = $this->connect()->prepare('SELECT username FROM users_lnp WHERE username = ? ORDER BY username ASC');

		if(!$stmt->execute([$username])){
			$stmt = null;
			/*header("location: ../admin.php?error=stmtfailed");*/
			exit();
		}
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$stmt = null;
		
		return $result;
	}

}



