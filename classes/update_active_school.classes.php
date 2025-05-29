<?php

session_start();

class UpdateActiveSchool extends Dbh
{

	protected function setSchoolPassive($email)
	{

		$pdo = $this->connect();

		$pdo->beginTransaction(); // İşlemleri başlat

		try {
			$stmt = $pdo->prepare('UPDATE schools_lnp SET active=? WHERE email=?');

			if (!$stmt) {
				throw new Exception("Prepare failed: " . $pdo->errorInfo());
			}

			if (!$stmt->execute([0, $email])) {
				throw new Exception(json_encode($stmt->errorInfo()));
				/* $stmt = null;
				header("location: ../admin.php?error=stmtfailed");
				exit(); */
			}

			$stmt = null;

			$stmt2 = $pdo->prepare('SELECT id FROM schools_lnp WHERE email=?');
			$stmt2->execute([$email]);
			$schoolId = $stmt2->fetch(PDO::FETCH_ASSOC);

			$stmt2 = null;

			$stmt3 = $pdo->prepare('UPDATE users_lnp SET active=? WHERE school_id=?');

			if (!$stmt3) {
				throw new Exception("Prepare failed: " . $pdo->errorInfo());
			}

			if (!$stmt3->execute([0, $schoolId['id']])) {
				throw new Exception(json_encode($stmt3->errorInfo()));
				/* $stmt = null;
				header("location: ../admin.php?error=stmtfailed");
				exit(); */
			}

			$stmt3 = null;

			$pdo->commit(); // Tüm işlemler başarılıysa commit et

			echo json_encode(["status" => "success", "message" => "Başarılı"]);
		} catch (Exception $e) {
			$pdo->rollback(); // Bir hata oluşursa tüm işlemleri geri al
			echo json_encode(["status" => "error", "message" => "Bir hata oluştu"]);
			exit();
		} finally {
			// PDO bağlantısı otomatik olarak kapanır
		}

		/* $stmt = $pdo->prepare('UPDATE schools_lnp SET active=? WHERE email=?');

		if (!$stmt->execute([0, $email])) {
			$stmt = null;
			header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		echo json_encode(["status" => "success", "message" => "Başarılı"]);
		$stmt = null; */
	}

	protected function setSchoolActive($email){

		$stmt = $this->connect()->prepare('UPDATE schools_lnp SET active=? WHERE email=?');

		if (!$stmt->execute([1, $email])) {
			$stmt = null;
			header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		echo json_encode(["status" => "success", "message" => "Başarılı"]);
		$stmt = null;

	}
}
