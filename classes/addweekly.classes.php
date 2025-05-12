<?php

session_start();

class AddWeekly extends Dbh
{

	protected function setWeekly($imgName, $slug, $name, $classes, $lessons, $units, $short_desc, $dbDateStart, $dbDateEnd)
	{
		$stmt = $this->connect()->prepare('INSERT INTO weekly_duty_lnp SET slug = ?, name = ?, class_id = ?, lesson_id = ?, unit_id = ?, short_desc=?, image=?, start_date=?, end_date=?');

		if (!$stmt->execute([$slug, $name, $classes, $lessons, $units, $short_desc, $imgName, $dbDateStart, $dbDateEnd])) {
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		
		echo json_encode(["status" => "success", "message" => $name]);

		$stmt = null;
	}

	public function checkSlug($slug)
	{
		$stmt = $this->connect()->prepare('SELECT slug FROM topics_lnp WHERE slug LIKE ? OR slug = ? ORDER BY slug ASC');

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
