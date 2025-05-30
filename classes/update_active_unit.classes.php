<?php

session_start();

class UpdateActiveUnit extends Dbh {

	protected function setUnitActive($id, $statusVal){
		$stmt = $this->connect()->prepare('UPDATE units_lnp SET active=? WHERE id=?');

		if(!$stmt->execute([$statusVal, $id])){
			$stmt = null;
			header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		echo json_encode(["status" => "success", "message" => "Başarılı"]);
		$stmt = null;
	}

}