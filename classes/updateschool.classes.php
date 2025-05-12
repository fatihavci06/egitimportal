<?php

session_start();

class UpdateSchool extends Dbh {

	protected function setSchool($slug, $old_slug, $name, $address, $district, $postcode, $city, $email, $telephone){
		$stmt = $this->connect()->prepare('UPDATE schools_lnp SET slug = ?, name = ?, address = ?, district = ?, postcode = ?, city = ?, email = ?, telephone = ?, active = ? WHERE slug=?');

		if(!$stmt->execute([$slug, $name, $address, $district, $postcode, $city, $email, $telephone, "1", $old_slug])){
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		echo json_encode(["status" => "success", "message" => $name]);
		$stmt = null;
	}

	public function checkSlug($slug){
		$stmt = $this->connect()->prepare('SELECT slug FROM schools_lnp WHERE slug LIKE ? OR slug = ? ORDER BY slug ASC');

		if(!$stmt->execute([$slug . '-%', $slug])){
			$stmt = null;
			header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$stmt = null;
		
		return $result;
	}

	public function checkEmail($email){
		$stmt = $this->connect()->prepare('SELECT email FROM schools_lnp WHERE email = ? ORDER BY email ASC');

		if(!$stmt->execute([$email])){
			$stmt = null;
			/*header("location: ../admin.php?error=stmtfailed");*/
			exit();
		}
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$stmt = null;
		
		return $result;
	}

}



