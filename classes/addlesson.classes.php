<?php

session_start();

class AddLesson extends Dbh
{

	protected function setLesson($slug, $name, $classes)
	{
		$pdo = $this->connect();

		$pdo->beginTransaction(); // İşlemleri başlat

		try {
			$stmt = $pdo->prepare('INSERT INTO lessons_lnp SET slug = ?, name = ?, class_id = ?, school_id = ?, teacher_id = ?');

			if (!$stmt->execute([$slug, $name, $classes, 1, 0])) {
				$stmt = null;
				//header("location: ../admin.php?error=stmtfailed");
				exit();
			}

			$stmt2 = $pdo->prepare('INSERT INTO menus_lnp SET slug = ?, name = ?, parent = ?, role = ?');

			if (!$stmt2->execute([$slug, $name, 1, 2])) {
				$stmt2 = null;
				//header("location: ../admin.php?error=stmtfailed");
				exit();
			}

			$pdo->commit(); // Tüm işlemler başarılıysa commit et

			echo json_encode(["status" => "success", "message" => $name]);
			$pdo = null;
		} catch (\Exception $e) {
			$pdo->rollback(); // Bir hata oluşursa tüm işlemleri geri al
			echo json_encode(["status" => "error", "message" => "Bir hata oluştu"]);
			$pdo = null; // PDO bağlantısını kapat
		} finally {
			$pdo = null;
		}

		/* echo json_encode(["status" => "success", "message" => $name]);
		$stmt = null; */
	}

	public function checkSlug($slug)
	{
		$stmt = $this->connect()->prepare('SELECT slug FROM lessons_lnp WHERE slug LIKE ? OR slug = ? ORDER BY slug ASC');

		if (!$stmt->execute([$slug . '-%', $slug])) {
			$stmt = null;
			header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$stmt = null;

		return $result;
	}
}
