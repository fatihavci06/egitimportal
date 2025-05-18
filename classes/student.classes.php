<?php

class Student extends Dbh
{

	public function getWaitingMoneyTransfers(){
		$stmt = $this->connect()->prepare('SELECT user_id FROM money_transfer_list_lnp WHERE status = ?');
		if (!$stmt->execute(array("0"))) {
			$stmt = null;
			exit();
		}
		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $getData;
		$stmt = null;
	}
	
	protected function getWaitingMoneyStudent()
	{

		$stmt = $this->connect()->prepare('SELECT money_transfer_list_lnp.id, money_transfer_list_lnp.pack_id, money_transfer_list_lnp.user_id, money_transfer_list_lnp.amount, users_lnp.name, users_lnp.surname, users_lnp.identity_id, users_lnp.photo FROM money_transfer_list_lnp INNER JOIN users_lnp ON money_transfer_list_lnp.user_id = users_lnp.id WHERE money_transfer_list_lnp.status = ?');

		if (!$stmt->execute(array(0))) {
			$stmt = null;
			exit();
		}

		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $getData;

		$stmt = null;
	}

	public function getMoneyTransferInfo($id){
		$stmt = $this->connect()->prepare('SELECT * FROM money_transfer_list_lnp WHERE id = ?');

		if (!$stmt->execute(array($id))) {
			$stmt = null;
			exit();
		}

		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $getData;

		$stmt = null;
	}

	protected function getparentName($child_id)
	{

		$stmt = $this->connect()->prepare('SELECT name, surname FROM users_lnp WHERE child_id = ?');

		if (!$stmt->execute(array($child_id))) {
			$stmt = null;
			exit();
		}

		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $getData;

		$stmt = null;
	}

	public function getPackName($pack_id)
	{

		$stmt = $this->connect()->prepare('SELECT name, class_id, subscription_period FROM packages_lnp WHERE id = ?');

		if (!$stmt->execute(array($pack_id))) {
			$stmt = null;
			exit();
		}

		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $getData;

		$stmt = null;
	}

	protected function getClassName($class_id)
	{

		$stmt = $this->connect()->prepare('SELECT name FROM classes_lnp WHERE id = ?');

		if (!$stmt->execute(array($class_id))) {
			$stmt = null;
			exit();
		}

		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $getData;

		$stmt = null;
	}

	protected function getStudentsList()
	{

		if ($_SESSION['role'] == 1) {
			$stmt = $this->connect()->prepare('SELECT id, name, surname, username, created_at, email, telephone, photo FROM users_lnp WHERE active = ? AND role = ?');

			if (!$stmt->execute(array("1", "2"))) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 3) {
			$school = $_SESSION['school_id'];
			$stmt = $this->connect()->prepare('SELECT id, name, surname, username, created_at, email, telephone, photo FROM users_lnp WHERE active = ? AND role = ? AND school_id = ?');

			if (!$stmt->execute(array("1", "2", $school))) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 4) {
			$school = $_SESSION['school_id'];
			$class_id = $_SESSION['class_id'];
			$stmt = $this->connect()->prepare('SELECT id, name, surname, username, created_at, email, telephone, photo FROM users_lnp WHERE active = ? AND role = ? AND school_id = ? AND class_id = ?');

			if (!$stmt->execute(array("1", "2", $school, $class_id))) {
				$stmt = null;
				exit();
			}
		}


		$schoolData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $schoolData;

		$stmt = null;
	}

	public function getOneStudent($student_id)
	{

		$stmt = $this->connect()->prepare('SELECT users_lnp.name AS userName, users_lnp.surname AS userSurname, users_lnp.photo AS userPhoto, schools_lnp.name AS schoolName FROM users_lnp INNER JOIN schools_lnp ON users_lnp.school_id = schools_lnp.id WHERE users_lnp.id = ?');

		if (!$stmt->execute(array($student_id))) {
			$stmt = null;
			exit();
		}

		$schoolData = $stmt->fetch(PDO::FETCH_ASSOC);

		return $schoolData;

		$stmt = null;
	}

	public function getStudents()
	{

		$stmt = $this->connect()->prepare('SELECT * FROM schools_lnp');

		if (!$stmt->execute(array(0))) {
			$stmt = null;
			exit();
		}

		$schoolData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $schoolData;

		$stmt = null;
	}

	public function getStudentsForTests($class_id)
	{

		$stmt = $this->connect()->prepare('SELECT id, name, surname, photo FROM users_lnp WHERE class_id = ?');

		if (!$stmt->execute(array($class_id))) {
			$stmt = null;
			exit();
		}

		$schoolData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $schoolData;

		$stmt = null;
	}

	public function getStudentsForParents()
	{

		$stmt = $this->connect()->prepare('SELECT id, name, surname FROM users_lnp WHERE active = ? AND role = ? AND parent_id IS NULL');

		if (!$stmt->execute(array("1", "2"))) {
			$stmt = null;
			exit();
		}

		$studentData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $studentData;

		$stmt = null;
	}
}
