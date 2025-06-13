<?php

class Units extends Dbh
{

	protected function getUnitsList()
	{

		if ($_SESSION['role'] == 1) {
			$stmt = $this->connect()->prepare('SELECT units_lnp.id AS unitID, units_lnp.name AS unitName, units_lnp.slug AS unitSlug, units_lnp.start_date AS unitStartDate, units_lnp.end_date AS unitEndDate, units_lnp.order_no AS unitOrder, units_lnp.active AS unitActive, classes_lnp.name AS className, classes_lnp.slug AS classSlug, lessons_lnp.name AS lessonName FROM units_lnp INNER JOIN classes_lnp ON units_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON units_lnp.lesson_id = lessons_lnp.id');

			if (!$stmt->execute(array())) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 2) {
			$school = $_SESSION['school_id'];
			$stmt = $this->connect()->prepare('SELECT units_lnp.id AS unitID, units_lnp.name AS unitName, units_lnp.slug AS unitSlug, classes_lnp.name AS className, lessons_lnp.name AS lessonName FROM units_lnp INNER JOIN classes_lnp ON units_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON units_lnp.lesson_id = lessons_lnp.id WHERE units_lnp.school_id = ?');

			if (!$stmt->execute(array($school))) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 3) {
			$school = $_SESSION['school_id'];
			$stmt = $this->connect()->prepare('SELECT units_lnp.id AS unitID, units_lnp.name AS unitName, units_lnp.slug AS unitSlug, classes_lnp.name AS className, lessons_lnp.name AS lessonName FROM units_lnp INNER JOIN classes_lnp ON units_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON units_lnp.lesson_id = lessons_lnp.id WHERE units_lnp.school_id = ?');

			if (!$stmt->execute(array($school))) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 4) {
			$school = $_SESSION['school_id'];
			$class_id = $_SESSION['class_id'];
			$lesson_id = $_SESSION['lesson_id'];
			$stmt = $this->connect()->prepare('SELECT units_lnp.id AS unitID, units_lnp.name AS unitName, units_lnp.slug AS unitSlug, units_lnp.start_date AS unitStartDate, units_lnp.end_date AS unitEndDate, units_lnp.order_no AS unitOrder, units_lnp.active AS unitActive, classes_lnp.name AS className, classes_lnp.slug AS classSlug, lessons_lnp.name AS lessonName FROM units_lnp INNER JOIN classes_lnp ON units_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON units_lnp.lesson_id = lessons_lnp.id WHERE units_lnp.school_id = ? AND units_lnp.class_id = ? AND units_lnp.lesson_id = ?');

			if (!$stmt->execute(array($school, $class_id, $lesson_id))) {
				$stmt = null;
				exit();
			}
		}

		$unitData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $unitData;

		$stmt = null;
	}

	protected function getUnitsOneLesson()
	{

		$stmt = $this->connect()->prepare('SELECT units_lnp.id AS unitID, units_lnp.name AS unitName, units_lnp.slug AS unitSlug, classes_lnp.name AS className, lessons_lnp.name AS lessonName FROM units_lnp INNER JOIN classes_lnp ON units_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON units_lnp.lesson_id = lessons_lnp.id');

		if (!$stmt->execute(array())) {
			$stmt = null;
			exit();
		}

		$unitData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $unitData;

		$stmt = null;
	}


	protected function getLessonId($active_slug)
	{
		$stmt = $this->connect()->prepare('SELECT id FROM lessons_lnp WHERE slug = ?');

		if (!$stmt->execute(array($active_slug))) {
			$stmt = null;
			exit();
		}

		$lessonIdData = $stmt->fetch(PDO::FETCH_ASSOC);

		return $lessonIdData['id'];

		$stmt = null;
	}

	protected function getUnitsListStu($lessonId, $classId, $schoolId)
	{
		$stmt = $this->connect()->prepare('SELECT id, name, slug, short_desc, photo FROM units_lnp WHERE lesson_id = ? AND class_id = ? AND school_id = ? AND active = 1');

		if (!$stmt->execute(array($lessonId, $classId, $schoolId))) {
			$stmt = null;
			exit();
		}

		$unitData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $unitData;

		$stmt = null;
	}

	public function getOneUnit($slug)
	{

		$stmt = $this->connect()->prepare('SELECT * FROM units_lnp WHERE slug = ?');

		if (!$stmt->execute(array($slug))) {
			$stmt = null;
			exit();
		}

		$unitData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $unitData;

		$stmt = null;
	}

	public function getOneUnitForDetails($slug)
	{

		if ($_SESSION['role'] == 1) {
			$stmt = $this->connect()->prepare('SELECT units_lnp.*, classes_lnp.name AS className, lessons_lnp.name AS lessonName, lessons_lnp.id AS lessonId FROM units_lnp INNER JOIN classes_lnp ON units_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON units_lnp.lesson_id = lessons_lnp.id WHERE units_lnp.slug = ?');

			if (!$stmt->execute(array($slug))) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 3 or $_SESSION['role'] == 8) {
			$school = $_SESSION['school_id'];
			$stmt = $this->connect()->prepare('SELECT units_lnp.*, classes_lnp.name AS className, lessons_lnp.name AS lessonName, lessons_lnp.id AS lessonId FROM units_lnp INNER JOIN classes_lnp ON units_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON units_lnp.lesson_id = lessons_lnp.id WHERE units_lnp.slug = ? AND units_lnp.school_id = ?');

			if (!$stmt->execute(array($slug, $school))) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 4) {
			$school = $_SESSION['school_id'];
			$class_id = $_SESSION['class_id'];
			$lesson_id = $_SESSION['lesson_id'];
			$stmt = $this->connect()->prepare('SELECT units_lnp.*, classes_lnp.name AS className, lessons_lnp.name AS lessonName, lessons_lnp.id AS lessonId FROM units_lnp INNER JOIN classes_lnp ON units_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON units_lnp.lesson_id = lessons_lnp.id WHERE units_lnp.slug = ? AND units_lnp.school_id = ? AND units_lnp.class_id = ? AND units_lnp.lesson_id = ?');

			if (!$stmt->execute(array($slug, $school, $class_id, $lesson_id))) {
				$stmt = null;
				exit();
			}
		}

		$unitData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $unitData;

		$stmt = null;
	}

	public function getUnits()
	{

		$stmt = $this->connect()->prepare('SELECT * FROM units_lnp');

		if (!$stmt->execute(array(0))) {
			$stmt = null;
			exit();
		}

		$unitData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $unitData;

		$stmt = null;
	}

	public function getUnitForTopicList($class, $lessons)
	{
		if ($_SESSION['role'] == 1) {
			$stmt = $this->connect()->prepare('SELECT id, name FROM units_lnp WHERE class_id = ? AND lesson_id=?');

			if (!$stmt->execute(array($class, $lessons))) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 3) {
			$school = $_SESSION['school_id'];
			$stmt = $this->connect()->prepare('SELECT id, name FROM units_lnp WHERE class_id = ? AND lesson_id=? AND school_id=?');

			if (!$stmt->execute(array($class, $lessons, $school))) {
				$stmt = null;
				exit();
			}
		}

		$unitData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $unitData;

		$stmt = null;
	}

	public function getSameUnits($active_slug)
	{

		$stmt = $this->connect()->prepare('SELECT school_id, class_id, lesson_id FROM units_lnp WHERE slug = ?');

		if (!$stmt->execute(array($active_slug))) {
			$stmt = null;
			exit();
		}

		$unitData = $stmt->fetch(PDO::FETCH_ASSOC);

		$school_id = $unitData['school_id'];
		$class_id = $unitData['class_id'];
		$lesson_id = $unitData['lesson_id'];

		$school = $_SESSION['school_id'];
		$stmt2 = $this->connect()->prepare('SELECT units_lnp.id AS unitID, units_lnp.name AS unitName, units_lnp.slug AS unitSlug, classes_lnp.name AS className, lessons_lnp.name AS lessonName FROM units_lnp INNER JOIN classes_lnp ON units_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON units_lnp.lesson_id = lessons_lnp.id WHERE units_lnp.school_id = ? AND units_lnp.school_id = ? AND units_lnp.class_id = ? AND units_lnp.lesson_id = ? AND units_lnp.slug != ?');

		if (!$stmt2->execute(array($school, $school_id, $class_id, $lesson_id, $active_slug))) {
			$stmt2 = null;
			exit();
		}

		$unitData2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

		return $unitData2;

		$stmt = null;
		$stmt2 = null;
	}
}
