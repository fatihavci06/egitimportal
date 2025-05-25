<?php

session_start();

class UpdateActiveStudent extends Dbh {

	protected function setStudentActive($email, $statusVal){
		$stmt = $this->connect()->prepare('UPDATE users_lnp SET active=? WHERE email=?');

		if(!$stmt->execute([$statusVal, $email])){
			$stmt = null;
			header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		echo json_encode(["status" => "success", "message" => "Başarılı"]);
		$stmt = null;
	}

}