<?php

session_start();

class UpdateSchool extends Dbh
{

	protected function setSchool($slug, $old_slug, $name, $address, $district, $postcode, $city, $email, $telephone, $schoolAdminName, $schoolAdminSurname, $schoolAdminEmail, $schoolAdminTelephone, $schoolCoordinatorName, $schoolCoordinatorSurname, $schoolCoordinatorEmail, $schoolCoordinatorTelephone, $schId, $slugAdmin, $slugCoordinator, $schoolAdminPasswordHash, $schoolCoordinatorPasswordHash)
	{
		$pdo = $this->connect();

		$pdo->beginTransaction(); // İşlemleri başlat

		try {
			$stmt = $pdo->prepare('UPDATE schools_lnp SET slug = ?, name = ?, address = ?, district = ?, postcode = ?, city = ?, email = ?, telephone = ?, active = ? WHERE slug=?');

			if (!$stmt) {
				throw new Exception("Prepare failed: " . $pdo->errorInfo());
			}

			if (!$stmt->execute([$slug, $name, $address, $district, $postcode, $city, $email, $telephone, "1", $old_slug])) {
				throw new Exception(json_encode($stmt->errorInfo()));
				/* $stmt = null;
				header("location: ../admin.php?error=stmtfailed");
				exit(); */
			}

			$stmt = null;

			if ($schoolAdminName != '' && $schoolAdminSurname != '' && $schoolAdminEmail != '' && $schoolAdminTelephone != '') {

				$stmtCheckAdmin = $pdo->prepare('SELECT id FROM users_lnp WHERE school_id = ? AND role = ?');
				$stmtCheckAdmin->execute([$schId, "8"]);
				$adminExists = $stmtCheckAdmin->fetch(PDO::FETCH_ASSOC);

				if ($adminExists) {
					// Admin bilgisi varsa, güncelle
					$adminId = $adminExists['id'];
					$stmtAdmin = $pdo->prepare('UPDATE users_lnp SET name = ?, surname = ?, email=?, telephone=? WHERE id = ?;');
					if (!$stmtAdmin->execute([$schoolAdminName, $schoolAdminSurname, $schoolAdminEmail, $schoolAdminTelephone, $adminId])) {
						throw new Exception(json_encode($stmtAdmin->errorInfo()));
					}
				} else {
					// Admin bilgisi yoksa, yeni bir kayıt oluştur
					$stmtAdmin = $pdo->prepare('INSERT INTO users_lnp SET name = ?, surname = ?, email=?, telephone=?, school_id = ?, role = ?, username = ?, password = ?, active = ?');
					if (!$stmtAdmin->execute([$schoolAdminName, $schoolAdminSurname, $schoolAdminEmail, $schoolAdminTelephone, $schId, "8", $slugAdmin, $schoolAdminPasswordHash, "1"])) {
						throw new Exception(json_encode($stmtAdmin->errorInfo()));
					}
				}

				if (!$stmtAdmin) {
					throw new Exception("Prepare failed: " . $pdo->errorInfo());
				}

				$stmtAdmin = null;
			}

			if ($schoolCoordinatorName != '' && $schoolCoordinatorSurname != '' && $schoolCoordinatorEmail != '' && $schoolCoordinatorTelephone != '') {

				$stmtCheckCoordinator = $pdo->prepare('SELECT id FROM users_lnp WHERE school_id = ? AND role = ?');
				$stmtCheckCoordinator->execute([$schId, "3"]);
				$coordinatorExists = $stmtCheckCoordinator->fetch(PDO::FETCH_ASSOC);

				if ($coordinatorExists) {
					// Koordinatör bilgisi varsa, güncelle
					$coordinatorId = $coordinatorExists['id'];
					$stmtCoordinator = $pdo->prepare('UPDATE users_lnp SET name = ?, surname = ?, email=?, telephone=? WHERE id = ?;');
					if (!$stmtCoordinator->execute([$schoolCoordinatorName, $schoolCoordinatorSurname, $schoolCoordinatorEmail, $schoolCoordinatorTelephone, $coordinatorId])) {
						throw new Exception(json_encode($stmtCoordinator->errorInfo()));
					}
				} else {
					// Koordinatör bilgisi yoksa, yeni bir kayıt oluştur
					$stmtCoordinator = $pdo->prepare('INSERT INTO users_lnp SET name = ?, surname = ?, email=?, telephone=?, school_id = ?, role = ?, username = ?, password = ?, active = ?');
					if (!$stmtCoordinator->execute([$schoolCoordinatorName, $schoolCoordinatorSurname, $schoolCoordinatorEmail, $schoolCoordinatorTelephone, $schId, "3", $slugCoordinator, $schoolCoordinatorPasswordHash, "1"])) {
						throw new Exception(json_encode($stmtCoordinator->errorInfo()));
					}
				}

				if (!$stmtCoordinator) {
					throw new Exception("Prepare failed: " . $pdo->errorInfo());
				}

				$stmtCoordinator = null;
			}

			$pdo->commit(); // Tüm işlemler başarılıysa commit et

			echo json_encode(["status" => "success", "message" => $name . ' adlı okul güncellenmiştir.']);
		} catch (Exception $e) {
			$pdo->rollback(); // Bir hata oluşursa tüm işlemleri geri al
			echo json_encode(["status" => "error", "message" => "Bir hata oluştu"]);
			$pdo = null; // PDO bağlantısını kapat
			exit();
		} finally {
			$pdo = null; // PDO bağlantısını kapat
		}
		/*

		$stmt = $this->connect()->prepare('UPDATE schools_lnp SET slug = ?, name = ?, address = ?, district = ?, postcode = ?, city = ?, email = ?, telephone = ?, active = ? WHERE slug=?');

		if(!$stmt->execute([$slug, $name, $address, $district, $postcode, $city, $email, $telephone, "1", $old_slug])){
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		} 
		echo json_encode(["status" => "success", "message" => $name]);
		$stmt = null;*/
	}

	public function checkSlug($slug)
	{
		$stmt = $this->connect()->prepare('SELECT slug FROM schools_lnp WHERE slug LIKE ? OR slug = ? ORDER BY slug ASC');

		if (!$stmt->execute([$slug . '-%', $slug])) {
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
		$stmt = $this->connect()->prepare('SELECT email FROM schools_lnp WHERE email = ? ORDER BY email ASC');

		if (!$stmt->execute([$email])) {
			$stmt = null;
			/*header("location: ../admin.php?error=stmtfailed");*/
			exit();
		}
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$stmt = null;

		return $result;
	}
}
