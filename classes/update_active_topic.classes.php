<?php

session_start();

class UpdateActiveTopic extends Dbh {

	protected function setTopicActive($id, $statusVal){
		$stmt = $this->connect()->prepare('UPDATE topics_lnp SET active=? WHERE id=?');

		if(!$stmt->execute([$statusVal, $id])){
			$stmt = null;
			header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		echo json_encode(["status" => "success", "message" => "Başarılı"]);
		$stmt = null;
	}

}