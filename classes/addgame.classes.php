<?php

session_start();

class AddGame extends Dbh {

	protected function setGame($imgName, $slug, $name, $iframe, $classAdd, $lesson, $unit, $topic, $subtopic)
	{
		$stmt = $this->connect()->prepare('INSERT INTO games_lnp SET slug = ?, name = ?, cover_img = ?, game_url=?, class_id=?, lesson_id=?,  unit_id=?, topic_id=?, subtopic_id=?');
		if (!$stmt->execute([$slug, $name, $imgName, $iframe, $classAdd,$lesson,$unit,$topic, $subtopic])) {
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		echo json_encode(["status" => "success", "message" => $name]);
		$stmt = null;
	}

	public function checkSlug($slug)
	{
		$stmt = $this->connect()->prepare('SELECT slug FROM games_lnp WHERE slug LIKE ? OR slug = ? ORDER BY slug ASC');

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



