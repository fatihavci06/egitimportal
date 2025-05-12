<?php

session_start();

class AddAudioBook extends Dbh {

	protected function setAudioBook($imgName, $slug, $name, $iframe, $classAdd){
		$stmt = $this->connect()->prepare('INSERT INTO audio_book_lnp SET slug = ?, name = ?, cover_img = ?, book_url=?, class_id =?');

		if(!$stmt->execute([$slug, $name, $imgName, $iframe, $classAdd])){
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		echo json_encode(["status" => "success", "message" => $name]);
		$stmt = null;
	}

	public function checkSlug($slug){
		$stmt = $this->connect()->prepare('SELECT slug FROM audio_book_lnp WHERE slug LIKE ? OR slug = ? ORDER BY slug ASC');

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



