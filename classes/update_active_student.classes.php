<?php

session_start();

class UpdateActiveStudent extends Dbh {

	protected function setStudentActive($username){
		$stmt = $this->connect()->prepare('UPDATE users_lnp SET active=? WHERE username=?');

		if(!$stmt->execute([0, $username])){
			$stmt = null;
			header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		echo json_encode(["status" => "success", "message" => "Başarılı"]);
		$stmt = null;
	}

}