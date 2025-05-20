<?php

class Parents extends Dbh
{

	protected function getParentsList()
	{

		if ($_SESSION['role'] == 1) {
			$stmt = $this->connect()->prepare('SELECT parent.id AS parentId, parent.name AS parentName, parent.surname AS parentSurname, parent.username AS parentUsername, parent.created_at AS parentCreated_at, child.email AS parentEmail, child.telephone AS parentTelephone, parent.photo AS parentPhoto, child.name AS childName, child.surname AS childSurname, child.subscribed_end AS subscribed_end FROM users_lnp AS parent LEFT JOIN users_lnp AS child ON child.parent_id = parent.id WHERE parent.active = ? AND parent.role = ? ORDER BY child.subscribed_end ASC');

			if (!$stmt->execute(array("1", "5"))) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 3) {
			$school = $_SESSION['school_id'];
			$stmt = $this->connect()->prepare('SELECT id, name, surname, username, created_at, email, telephone, photo FROM users_lnp WHERE active = ? AND role = ? AND school_id = ?');

			if (!$stmt->execute(array("1", "5", $school))) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 4) {
			$school = $_SESSION['school_id'];
			$class_id = $_SESSION['class_id'];
			$stmt = $this->connect()->prepare('SELECT id, name, surname, username, created_at, email, telephone, photo FROM users_lnp WHERE active = ? AND role = ? AND school_id = ? AND class_id = ?');

			if (!$stmt->execute(array("1", "5", $school, $class_id))) {
				$stmt = null;
				exit();
			}
		}


		$schoolData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $schoolData;

		$stmt = null;
	}

	public function getOneParent($slug)
	{

		$stmt = $this->connect()->prepare('SELECT * FROM schools_lnp WHERE slug = ?');

		if (!$stmt->execute(array($slug))) {
			$stmt = null;
			exit();
		}

		$schoolData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $schoolData;

		$stmt = null;
	}

	public function getParents()
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
}
