<?php

session_start();

class AddLesson extends Dbh {

	protected function setLesson($slug, $name, $classes){
		$stmt = $this->connect()->prepare('INSERT INTO lessons_lnp SET slug = ?, name = ?, class_id = ?');

		if(!$stmt->execute([$slug, $name, $classes])){
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		echo json_encode(["status" => "success", "message" => $name]);
		$stmt = null;
	}

	public function checkSlug($slug){
		$stmt = $this->connect()->prepare('SELECT slug FROM lessons_lnp WHERE slug LIKE ? OR slug = ? ORDER BY slug ASC');

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



