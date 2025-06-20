<?php

session_start();

class AddStudent extends Dbh
{

	protected function setStudent($photo, $name, $surname, $username, $gender, $birthdate, $email, $telephone, $school, $classes, $parentFirstName, $parentLastName, $address, $district, $postcode, $city, $passwordStudent, $passwordParent, $parentUsername, $tckn, $nowTime, $endTime, $pack)
	{
		$pdo = $this->connect();

		$pdo->beginTransaction(); // İşlemleri başlat

		try {
			// Öğrenci ekleme işlemi için SQL sorgusu
			$stmt = $pdo->prepare('INSERT INTO users_lnp SET photo = ?, name = ?, surname = ?, username = ?, gender = ?, birth_date = ?, email = ?, telephone = ?, school_id = ?, class_id = ?, password=?, role=?, active = ?, identity_id=?, address = ?, district=?, postcode=?, city=?, subscribed_at=?, subscribed_end=?, package_id=?');

			if (!$stmt) {
				throw new Exception("Prepare failed: " . $pdo->errorInfo());
			}

			if($classes == 10 OR $classes == 11 OR $classes == 12){
				$role = 10002;
			}else {
				$role = 2;
			}

			if (!$stmt->execute([$photo, $name, $surname, $username, $gender, $birthdate, $email, $telephone, $school, $classes, $passwordStudent, $role, "1", $tckn, $address, $district, $postcode, $city, $nowTime, $endTime, $pack])) {
				throw new Exception(json_encode($stmt->errorInfo()));
				/* $stmt = null;
				header("location: ../admin.php?error=stmtfailed");
				exit(); */
			}

			$stmt = null;

			// Eklenen öğrencinin ID'sini almak için SQL sorgusu
			$stmt2 = $pdo->prepare('SELECT id FROM users_lnp ORDER BY id DESC LIMIT 1');

			if (!$stmt2->execute(array())) {
				throw new Exception(json_encode($stmt2->errorInfo()));
				$stmt2 = null;
				exit();
			}

			$lastId = $stmt2->fetch(PDO::FETCH_ASSOC);

			$lastId = $lastId['id'];

			$stmt2 = null;

			$stmt3 = $pdo->prepare('INSERT INTO users_lnp SET name = ?, surname = ?, username = ?, password = ?, role = ?, active = ?, child_id=?');

			if (!$stmt3) {
				throw new Exception("Prepare failed: " . $pdo->errorInfo());
			}
			

			if (!$stmt3->execute([$parentFirstName, $parentLastName, $parentUsername, $passwordParent, "5", "1", $lastId])) {
				throw new Exception(json_encode($stmt3->errorInfo()));
				/* $stmt = null;
				header("location: ../admin.php?error=stmtfailed");
				exit(); */
			}

			$stmt3 = null;

			$parentId = $lastId + 1; // Yeni ebeveyn ID'si

			$stmt4 = $pdo->prepare('UPDATE users_lnp SET parent_id = ? WHERE id = ?');

			if (!$stmt4) {
				throw new Exception("Prepare failed: " . $pdo->errorInfo());
			}


			if (!$stmt4->execute([$parentId, $lastId])) {
				throw new Exception(json_encode($stmt4->errorInfo()));
				/* $stmt = null;
				header("location: ../admin.php?error=stmtfailed");
				exit(); */
			}

			$stmt4 = null;

			$pdo->commit(); // Tüm işlemler başarılıysa commit et

			echo json_encode(["status" => "success", "message" => $name . ' ' . $surname]);

		} catch (\Exception $e) {
			$pdo->rollback(); // Bir hata oluşursa tüm işlemleri geri al
			echo json_encode(["status" => "error", "message" => "Bir hata oluştu"]);
			$pdo = null; // PDO bağlantısını kapat
			exit();
		} finally {
			$pdo = null;
		}

		/* $stmt = $this->connect()->prepare('INSERT INTO users_lnp SET photo = ?, name = ?, surname = ?, username = ?, gender = ?, birth_date = ?, email = ?, telephone = ?, school_id = ?, class_id = ?, password=?, role=?, active = ?');

		if(!$stmt->execute([$photo, $name, $surname, $username, $gender, $birthdate, $email, $telephone, $school, $classes, $password, "2", "1"])){
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		echo json_encode(["status" => "success", "message" => $name . ' ' . $surname]);
		$stmt = null; */
	}

	/*public function checkSlug($slug){
		$stmt = $this->connect()->prepare('SELECT slug FROM users_lnp WHERE slug LIKE ? OR slug = ? ORDER BY slug ASC');

		if(!$stmt->execute([$slug . '-%', $slug])){
			$stmt = null;
			header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$stmt = null;
		
		return $result;
	}*/

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
}
