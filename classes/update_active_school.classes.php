<?php

session_start();

class UpdateActiveSchool extends Dbh {

	protected function setSchoolActive($email){
		$stmt = $this->connect()->prepare('UPDATE schools_lnp SET active=? WHERE email=?');

		if(!$stmt->execute([0, $email])){
			$stmt = null;
			header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		echo json_encode(["status" => "success", "message" => "Başarılı"]);
		$stmt = null;
	}

}



