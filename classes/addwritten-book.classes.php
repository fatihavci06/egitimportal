<?php

session_start();

class AddWrittenBook extends Dbh
{

	protected function setWrittenBook($imgName, $slug, $name, $iframe, $classAdd, $lesson, $unit, $topic, $subtopic, $description)
	{

		$stmt = $this->connect()->prepare('INSERT INTO written_book_lnp SET slug = ?, name = ?, cover_img = ?, book_url=?, class_id=?, lesson_id=?,  unit_id=?, topic_id=?, subtopic_id=?, description=?');

		if (!$stmt->execute([$slug, $name, $imgName, $iframe, $classAdd, $lesson, $unit, $topic, $subtopic, $description])) {
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}

		echo json_encode(["status" => "success", "message" => $name]);

		$stmt = null;
	}
	protected function updateWrittenBook($id, $imgName, $slug, $name, $iframe, $classAdd, $lesson, $unit, $topic, $subtopic, $description)
	{
		$stmt = $this->connect()->prepare('
        UPDATE written_book_lnp 
        SET slug = ?, name = ?, cover_img = ?, book_url = ?, class_id = ?, lesson_id = ?, unit_id = ?, topic_id = ?, subtopic_id = ?, description = ?
        WHERE id = ?
    ');

		if (!$stmt->execute([$slug, $name, $imgName, $iframe, $classAdd, $lesson, $unit, $topic, $subtopic, $description, $id])) {
			$stmt = null;
			// header("location: ../admin.php?error=stmtfailed");
			echo json_encode(["status" => "fail", "message" => "Güncellendi: " . $name]);

			exit();
		}

		echo json_encode(["status" => "success", "message" => "Güncellendi: " . $name]);

		$stmt = null;
	}

	public function checkSlug($slug)
	{
		$stmt = $this->connect()->prepare('SELECT slug FROM written_book_lnp WHERE slug LIKE ? OR slug = ? ORDER BY slug ASC');

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



