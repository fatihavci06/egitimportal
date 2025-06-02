<?php

session_start();

class UpdateActiveSubTopic extends Dbh {

	protected function setSubTopicActive($id, $statusVal){
		$stmt = $this->connect()->prepare('UPDATE subtopics_lnp SET active=? WHERE id=?');

		if(!$stmt->execute([$statusVal, $id])){
			$stmt = null;
			header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		echo json_encode(["status" => "success", "message" => "Başarılı"]);
		$stmt = null;
	}

}