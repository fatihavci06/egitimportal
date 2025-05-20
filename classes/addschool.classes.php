<?php

session_start();

class AddSchool extends Dbh {

	protected function setSchool($slug, $name, $address, $district, $postcode, $city, $email, $telephone){
		$stmt = $this->connect()->prepare('INSERT INTO schools_lnp SET slug = ?, name = ?, address = ?, district = ?, postcode = ?, city = ?, email = ?, telephone = ?, active = ?');

		if(!$stmt->execute([$slug, $name, $address, $district, $postcode, $city, $email, $telephone, "1"])){
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		$stmt = null;
	}

	public function getSchoolLastId(){
		$stmt = $this->connect()->prepare('SELECT id FROM schools_lnp ORDER BY id DESC LIMIT 1');

		if(!$stmt->execute(array())){
			$stmt = null;
			exit();
		}
		$schoolData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $schoolData;
	}

	public function setSchoolAdmin($schoolAdminName, $schoolAdminSurname, $slugAdmin, $schoolAdminEmail, $schoolAdminTelephone, $schoolAdminPasswordHash){
		$stmt = $this->connect()->prepare('INSERT INTO users_lnp SET name = ?, surname = ?, username=?, email=?, telephone=?, password=?, role=?, active = ?, school_id = ?');

		$school_id = $this->getSchoolLastId();

		if(!$stmt->execute([$schoolAdminName, $schoolAdminSurname, $slugAdmin, $schoolAdminEmail, $schoolAdminTelephone, $schoolAdminPasswordHash, 8, 1, $school_id[0]['id']])){
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		$stmt = null;
	}

	public function setSchoolCoordinator($schoolCoordinatorName, $schoolCoordinatorSurname, $slugCoordinator, $schoolCoordinatorEmail, $schoolCoordinatorTelephone, $schoolCoordinatorPasswordHash){
		$stmt = $this->connect()->prepare('INSERT INTO users_lnp SET name = ?, surname = ?, username=?, email=?, telephone=?, password=?, role=?, active = ?, school_id = ?');

		$school_id = $this->getSchoolLastId();

		if(!$stmt->execute([$schoolCoordinatorName, $schoolCoordinatorSurname, $slugCoordinator, $schoolCoordinatorEmail, $schoolCoordinatorTelephone, $schoolCoordinatorPasswordHash, 3, 1, $school_id[0]['id']])){
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}
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

	public function checkUser($email){
		$stmt = $this->connect()->prepare('SELECT email FROM users_lnp WHERE email = ? ORDER BY email ASC');

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



