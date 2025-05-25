<?php

session_start();

class AddTimeSpend extends Dbh
{

	protected function setTestAnswer($timeSpent, $pageUrl, $timestamp, $startTime)
	{
		$stmt = $this->connect()->prepare('INSERT INTO timespend_lnp SET sayfa_url = ?, timeSpent = ?, exitTime = ?, user_id = ?, enterTime = ?');

		if (!$stmt->execute([$pageUrl, $timeSpent, $timestamp, $_SESSION['id'], $startTime])) {
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}

		echo json_encode(["status" => "success", "message" => ""]);

		$stmt = null;
	}
}
