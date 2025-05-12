<?php

session_start();

class AddTopic extends Dbh
{

	protected function setTopic($imgName, $slug, $name, $classes, $lessons, $units, $short_desc)
	{
		$stmt = $this->connect()->prepare('INSERT INTO topics_lnp SET slug = ?, name = ?, class_id = ?, lesson_id = ?, unit_id = ?, short_desc=?, image=?');

		if (!$stmt->execute([$slug, $name, $classes, $lessons, $units, $short_desc, $imgName])) {
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

class AddSubTopic extends Dbh
{

	protected function setSubTopic($imgName, $slug, $name, $classes, $lessons, $units, $topics, $short_desc, $content, $video_url, $test, $question)
	{
		$stmt = $this->connect()->prepare('INSERT INTO subtopics_lnp SET slug = ?, name = ?, class_id = ?, lesson_id = ?, unit_id = ?, topic_id = ?, content=?, short_desc=?, video_url=?, image=?, is_test=?, is_question=?');

		if (!$stmt->execute([$slug, $name, $classes, $lessons, $units, $topics, $content, $short_desc, $video_url, $imgName, $test, $question])) {
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		if ($test == 0 and $question == 0) {
			echo json_encode(["status" => "success", "message" => $name]);
		}
		$stmt = null;
	}

	protected function setTest($testSorular, $joint_answers, $testcevap, $slug, $name, $last_day)
	{
		$stmtId = $this->connect()->prepare('SELECT id FROM subtopics_lnp ORDER BY id DESC');
		$stmtId->execute();
		$result = $stmtId->fetch(PDO::FETCH_ASSOC);
		$subtopic = $result['id'];

		$stmt = $this->connect()->prepare('INSERT INTO tests_lnp SET questions = ?, answers = ?, correct = ?, subtopic_id = ?, slug = ?, up_to = ?');

		if (!$stmt->execute([$testSorular, $joint_answers, $testcevap, $subtopic, $slug, $last_day])) {
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		echo json_encode(["status" => "success", "message" => $name]);
		$stmt = null;
	}

	protected function setS_questions($testSorular, $joint_answers, $testcevap, $solution, $slug, $name)
	{
		$stmtId = $this->connect()->prepare('SELECT id FROM subtopics_lnp ORDER BY id DESC');
		$stmtId->execute();
		$result = $stmtId->fetch(PDO::FETCH_ASSOC);
		$subtopic = $result['id'];

		$stmt = $this->connect()->prepare('INSERT INTO s_questions_lnp SET questions = ?, answers = ?, correct = ?, subtopic_id = ?, solutions = ?, slug = ?');

		if (!$stmt->execute([$testSorular, $joint_answers, $testcevap, $subtopic, $solution, $slug])) {
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		echo json_encode(["status" => "success", "message" => $name]);
		$stmt = null;
	}

	public function checkSlug($slug)
	{
		$stmt = $this->connect()->prepare('SELECT slug FROM subtopics_lnp WHERE slug LIKE ? OR slug = ? ORDER BY slug ASC');

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
