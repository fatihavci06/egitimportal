<?php

class Teacher extends Dbh
{

	protected function getTeachersList()
	{

		if ($_SESSION['role'] == 1) {
			$stmt = $this->connect()->prepare('SELECT users_lnp.id, users_lnp.name, users_lnp.surname, users_lnp.username, users_lnp.created_at, users_lnp.email, users_lnp.telephone, users_lnp.photo, classes_lnp.name AS className, schools_lnp.name AS schoolName, lessons_lnp.name AS lessonName FROM users_lnp INNER JOIN schools_lnp ON users_lnp.school_id = schools_lnp.id INNER JOIN classes_lnp ON users_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON users_lnp.lesson_id = lessons_lnp.id WHERE users_lnp.active = ? AND users_lnp.role = ?');

			if (!$stmt->execute(array("1", "4"))) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 3) {
			$school = $_SESSION['school_id'];
			$stmt = $this->connect()->prepare('SELECT users_lnp.id, users_lnp.name, users_lnp.surname, users_lnp.username, users_lnp.created_at, users_lnp.email, users_lnp.telephone, users_lnp.photo, classes_lnp.name AS className, schools_lnp.name AS schoolName, lessons_lnp.name AS lessonName FROM users_lnp INNER JOIN schools_lnp ON users_lnp.school_id = schools_lnp.id INNER JOIN classes_lnp ON users_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON users_lnp.lesson_id = lessons_lnp.id WHERE users_lnp.active = ? AND users_lnp.role = ? AND users_lnp.school_id = ?');

			if (!$stmt->execute(array("1", "4", $school))) {
				$stmt = null;
				exit();
			}
		}

		$schoolData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $schoolData;

		$stmt = null;
	}

	public function getOneTeacher($slug)
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

	public function getTeachers()
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
