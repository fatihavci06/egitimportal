<?php

session_start();

class AddClasses extends Dbh
{

	protected function setClass($slug, $name, $table = 'classes_lnp', $startDate = null, $endDate = null)
	{


		if ($startDate != null && $endDate != null) {

			// Tarihleri karşılaştırmak için DateTime nesneleri oluştur
			$start = DateTime::createFromFormat('Y-m-d', $startDate);
			$end = DateTime::createFromFormat('Y-m-d', $endDate);



			// Başlangıç tarihi bitiş tarihinden sonra ise işlemi durdur
			if ($start > $end) {
				echo json_encode(["status" => "false", "message" => 'Bitiş tarihi, başlangıç tarihinden küçük olamaz.']);
				exit();
			}

			$stmt = $this->connect()->prepare("INSERT INTO `$table` (`slug`, `name`, `start_date`, `end_date`) VALUES (?, ?, ?, ?)");
			if (!$stmt->execute([$slug, $name, $startDate, $endDate])) {
				$stmt = null;
				exit();
			}
		} else {
			$stmt = $this->connect()->prepare("INSERT INTO `$table` (`slug`, `name`) VALUES (?, ?)");
			if (!$stmt->execute([$slug, $name])) {
				$stmt = null;
				//header("location: ../admin.php?error=stmtfailed");
				exit();
			}
		}


		echo json_encode(["status" => "success", "message" => $name]);
		$stmt = null;
	}

	public function checkSlug($slug)
	{
		$stmt = $this->connect()->prepare('SELECT slug FROM classes_lnp WHERE slug LIKE ? OR slug = ? ORDER BY slug ASC');

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
