<?php

session_start();

class AddTopic extends Dbh
{

	protected function setTopic($imgName, $slug, $name, $classes, $lessons, $units, $short_desc, $start_date, $end_date, $order)
	{
		$stmt = $this->connect()->prepare('INSERT INTO topics_lnp SET slug = ?, name = ?, class_id = ?, lesson_id = ?, unit_id = ?, short_desc=?, image=?, start_date=?, end_date=?, order_no=?');

		if (!$stmt->execute([$slug, $name, $classes, $lessons, $units, $short_desc, $imgName, $start_date, $end_date, $order])) {
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

class UpdateTopic extends Dbh
{

	protected function updateTopic($imgName, $slug, $name, $short_desc, $start_date, $end_date, $order, $id)
	{
		$stmt = $this->connect()->prepare('UPDATE topics_lnp SET slug = ?, name = ?, short_desc=?, image=?, start_date=?, end_date=?, order_no=? WHERE id = ?');

		if (!$stmt->execute([$slug, $name, $short_desc, $imgName, $start_date, $end_date, $order, $id])) {
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

	protected function setSubTopic($imgName, $slug, $name, $classes, $lessons, $units, $topics, $short_desc, $start_date, $end_date, $order)
	{
		$stmt = $this->connect()->prepare('INSERT INTO subtopics_lnp SET slug = ?, name = ?, class_id = ?, lesson_id = ?, unit_id = ?, topic_id = ?, short_desc=?, image=?, start_date=?, end_date=?, order_no=?');

		if (!$stmt->execute([$slug, $name, $classes, $lessons, $units, $topics, $short_desc,  $imgName, $start_date, $end_date, $order])) {
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		
		echo json_encode(["status" => "success", "message" => $name]);
		$stmt = null;
	}

/* 	protected function setTest($testSorular, $joint_answers, $testcevap, $slug, $name, $last_day)
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
	} */

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



class UpdateSubTopic extends Dbh
{

	protected function updateSubTopic($imgName, $slug, $name, $short_desc, $start_date, $end_date, $order, $id)
	{
		$stmt = $this->connect()->prepare('UPDATE subtopics_lnp SET slug = ?, name = ?, short_desc=?, image=?, start_date=?, end_date=?, order_no=? WHERE id = ?');

		if (!$stmt->execute([$slug, $name, $short_desc, $imgName, $start_date, $end_date, $order, $id])) {
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
