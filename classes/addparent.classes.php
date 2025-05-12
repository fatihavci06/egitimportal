<?php

session_start();

class AddParent extends Dbh {

	protected function setParent($photo, $name, $surname, $username, $gender, $birthdate, $email, $telephone, $child, $password){
		$stmt = $this->connect()->prepare('INSERT INTO users_lnp SET photo = ?, name = ?, surname = ?, username = ?, gender = ?, birth_date = ?, email = ?, telephone = ?, password=?, role=?, active = ?, child_id = ?');

		$stmt2 = $this->connect()->prepare('SHOW TABLE STATUS LIKE ?');

		if(!$stmt2->execute(["users_lnp"])){
			$stmt2 = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}

		$result = $stmt2->fetch(PDO::FETCH_ASSOC);
    	$nextId = $result['Auto_increment'];

		$stmt3 = $this->connect()->prepare('UPDATE users_lnp SET parent_id = ? WHERE id = ?');

		if(!$stmt3->execute([$nextId, $child])){
			$stmt3 = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}

		if(!$stmt->execute([$photo, $name, $surname, $username, $gender, $birthdate, $email, $telephone, $password, "5", "1", $child])){
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		echo json_encode(["status" => "success", "message" => $name . ' ' . $surname]);
		$stmt = null;
		$stmt2 = null;
		$stmt3 = null;
	}

	/*public function checkSlug($slug){
		$stmt = $this->connect()->prepare('SELECT slug FROM users_lnp WHERE slug LIKE ? OR slug = ? ORDER BY slug ASC');

		if(!$stmt->execute([$slug . '-%', $slug])){
			$stmt = null;
			header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$stmt = null;
		
		return $result;
	}*/

	public function checkEmail($email){
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

	public function checkUsername($username){
		$stmt = $this->connect()->prepare('SELECT username FROM users_lnp WHERE username = ? ORDER BY username ASC');

		if(!$stmt->execute([$username])){
			$stmt = null;
			/*header("location: ../admin.php?error=stmtfailed");*/
			exit();
		}
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$stmt = null;
		
		return $result;
	}

}



