<?php

session_start();

class AddUnit extends Dbh {

	protected function setUnit($imgName, $slug, $name, $classes, $lessons, $short_desc){
		$stmt = $this->connect()->prepare('INSERT INTO units_lnp SET slug = ?, name = ?, class_id = ?, lesson_id = ?, short_desc=?, photo=?, school_id=?');
		
		if ($_SESSION['role'] == 3){
			$school = $_SESSION['school_id'];
		}else{
			$school = 1;
		}

		if(!$stmt->execute([$slug, $name, $classes, $lessons, $short_desc, $imgName, $school])){
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



