<?php

session_start();

class AddTestAnswer extends Dbh
{

	protected function setTestAnswer($testcevap, $test_id)
	{
		$stmt = $this->connect()->prepare('INSERT INTO solvedtest_lnp SET answers = ?, test_id = ?, student_id = ?');

		if (!$stmt->execute([$testcevap, $test_id, $_SESSION['id']])) {
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}

		echo json_encode(["status" => "success", "message" => ""]);

		$stmt = null;
	}
}

class AddSolutionQuestAnswer extends Dbh
{

	protected function setSolutionQuestAnswer($testcevap, $question_id)
	{
		$stmt = $this->connect()->prepare('INSERT INTO solved_s_questions_lnp SET answers = ?, test_id = ?, student_id = ?');

		if (!$stmt->execute([$testcevap, $question_id, $_SESSION['id']])) {
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}

		echo json_encode(["status" => "success", "message" => ""]);

		$stmt = null;
	}
}
