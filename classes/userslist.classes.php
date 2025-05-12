<?php

class User extends Dbh
{

	protected function getUsersList()
	{

		if ($_SESSION['role'] == 1) {
			$stmt = $this->connect()->prepare('SELECT users_lnp.id, users_lnp.active, users_lnp.name, users_lnp.surname, users_lnp.username, users_lnp.created_at, users_lnp.email, users_lnp.telephone, users_lnp.photo, userroles_lnp.name AS roleName FROM users_lnp INNER JOIN userroles_lnp ON users_lnp.role = userroles_lnp.id ORDER BY users_lnp.role ASC');

			if (!$stmt->execute(array())) {
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


		$userData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $userData;

		$stmt = null;
	}

	public function getOneUser($slug)
	{

		$stmt = $this->connect()->prepare('SELECT * FROM schools_lnp WHERE slug = ?');

		if (!$stmt->execute(array($slug))) {
			$stmt = null;
			exit();
		}

		$userData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $userData;

		$stmt = null;
	}

	public function getUsers()
	{

		$stmt = $this->connect()->prepare('SELECT * FROM schools_lnp');

		if (!$stmt->execute(array(0))) {
			$stmt = null;
			exit();
		}

		$userData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $userData;

		$stmt = null;
	}

	public function getUsersForParents()
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
