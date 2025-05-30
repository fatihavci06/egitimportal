<?php

session_start();

class AddAudioBook extends Dbh
{

	protected function setAudioBook($imgName, $slug, $name, $iframe, $classAdd, $lesson, $unit, $topic, $subtopic)
	{

		$stmt = $this->connect()->prepare('INSERT INTO audio_book_lnp SET slug = ?, name = ?, cover_img = ?, book_url=?, class_id=?, lesson_id=?,  unit_id=?, topic_id=?, subtopic_id=?');

		if (!$stmt->execute([$slug, $name, $imgName, $iframe, $classAdd, $lesson, $unit, $topic, $subtopic])) {
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}

		echo json_encode(["status" => "success", "message" => $name]);

		$stmt = null;
	}
	protected function updateAudioBook($id, $imgName, $slug, $name, $iframe, $classAdd, $lesson, $unit, $topic, $subtopic)
	{
		$stmt = $this->connect()->prepare('
        UPDATE audio_book_lnp 
        SET slug = ?, name = ?, cover_img = ?, book_url = ?, class_id = ?, lesson_id = ?, unit_id = ?, topic_id = ?, subtopic_id = ?
        WHERE id = ?
    ');

		if (!$stmt->execute([$slug, $name, $imgName, $iframe, $classAdd, $lesson, $unit, $topic, $subtopic, $id])) {
			$stmt = null;
			// header("location: ../admin.php?error=stmtfailed");
			echo json_encode(["status" => "fail", "message" => "Updated: " . $name]);

			exit();
		}

		echo json_encode(["status" => "success", "message" => "Updated: " . $name]);

		$stmt = null;
	}

	public function checkSlug($slug)
	{
		$stmt = $this->connect()->prepare('SELECT slug FROM audio_book_lnp WHERE slug LIKE ? OR slug = ? ORDER BY slug ASC');

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



