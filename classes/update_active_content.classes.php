<?php

session_start();

class UpdateActiveContent extends Dbh {

	protected function setContentActive($id, $statusVal){
		$stmt = $this->connect()->prepare('UPDATE school_content_lnp SET active=? WHERE id=?');

		if(!$stmt->execute([$statusVal, $id])){
			$stmt = null;
			header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		echo json_encode(["status" => "success", "message" => "Başarılı"]);
		$stmt = null;
	}

}