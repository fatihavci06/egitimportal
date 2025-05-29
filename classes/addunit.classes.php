<?php

session_start();

class AddUnit extends Dbh {

	protected function setUnit($imgName, $slug, $name, $classes, $lessons, $short_desc, $start_date, $end_date, $unit_order){
		$stmt = $this->connect()->prepare('INSERT INTO units_lnp SET slug = ?, name = ?, class_id = ?, lesson_id = ?, short_desc=?, photo=?, school_id=?, start_date=?, end_date=?, order_no=?');

		if ($_SESSION['role'] == 3){
			$school = $_SESSION['school_id'];
		}else{
			$school = 1;
		}

		if(!$stmt->execute([$slug, $name, $classes, $lessons, $short_desc, $imgName, $school, $start_date, $end_date, $unit_order])){
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		echo json_encode(["status" => "success", "message" => $name]);
		$stmt = null;
	}

	protected function updateUnit($imgName, $slug, $name, $short_desc, $start_date, $end_date, $unit_order, $unit_id){
		$stmt = $this->connect()->prepare('UPDATE units_lnp SET slug = ?, name = ?, short_desc=?, photo=?, start_date=?, end_date=?, order_no=? WHERE id = ?');

		if ($_SESSION['role'] == 3){
			$school = $_SESSION['school_id'];
		}else{
			$school = 1;
		}

		if(!$stmt->execute([$slug, $name, $short_desc, $imgName, $start_date, $end_date, $unit_order, $unit_id])){
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		echo json_encode(["status" => "success", "message" => $name]);
		$stmt = null;
	}

	public function checkSlug($slug){
		$stmt = $this->connect()->prepare('SELECT slug FROM units_lnp WHERE slug LIKE ? OR slug = ? ORDER BY slug ASC');

		if(!$stmt->execute([$slug . '-%', $slug])){
			$stmt = null;
			header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$stmt = null;
		
		return $result;
	}

}



