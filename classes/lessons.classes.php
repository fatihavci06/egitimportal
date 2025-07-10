<?php

class Lessons extends Dbh
{

	public function getLessonsList()
	{

		$stmt = $this->connect()->prepare('SELECT * FROM lessons_lnp');

		if (!$stmt->execute(array())) {
			$stmt = null;
			exit();
		}

		$lessonData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $lessonData;

	}
	public function getLessonsListBySchool($schoolId)
	{
		try {
			$sql = 'SELECT * FROM lessons_lnp WHERE school_id = :schoolId';
			$stmt = $this->connect()->prepare($sql);
			$stmt->bindParam(':schoolId', $schoolId, PDO::PARAM_INT);
			$stmt->execute();

			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			return []; 
		}
	}

	protected function getLessonsListForWeeklylists()
	{

		$stmt = $this->connect()->prepare('SELECT id, name, slug, class_id FROM lessons_lnp');

		if (!$stmt->execute(array())) {
			$stmt = null;
			exit();
		}

		$lessonData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $lessonData;

	}

	public function getOneLesson($slug)
	{

		$stmt = $this->connect()->prepare('SELECT * FROM lessons_lnp WHERE slug = ?');

		if (!$stmt->execute(array($slug))) {
			$stmt = null;
			exit();
		}

		$lessonData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $lessonData;

	}

	public function getLessons()
	{

		$stmt = $this->connect()->prepare('SELECT * FROM lessons_lnp');

		if (!$stmt->execute(array())) {
			$stmt = null;
			exit();
		}

		$lessonData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $lessonData;

	}

	public function getLessonForUnitList()
	{
		if ($_SESSION['role'] == 1 or $_SESSION['role'] == 2 or $_SESSION['role'] == 5) {
			$stmt = $this->connect()->prepare('SELECT id, name, class_id,package_type FROM lessons_lnp ');

			if (!$stmt->execute(array())) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 3 or $_SESSION['role'] == 8) {
			$school = $_SESSION['school_id'];
			$stmt = $this->connect()->prepare('SELECT id, name, class_id,package_type FROM lessons_lnp WHERE (school_id=? OR school_id=?)');

			if (!$stmt->execute(array($school, "1"))) {
				$stmt = null;
				exit();
			}
		}

		$unitData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $unitData;

		$stmt = null;
	}


}



