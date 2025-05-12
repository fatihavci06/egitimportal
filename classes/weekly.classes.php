<?php

class Weeklies extends Dbh
{

	protected function getWeekliesList()
	{

		if ($_SESSION['role'] == 1) {
			$stmt = $this->connect()->prepare('SELECT weekly_duty_lnp.id AS unitID, weekly_duty_lnp.name AS weeklyName, weekly_duty_lnp.start_date AS start_date, weekly_duty_lnp.end_date AS end_date, weekly_duty_lnp.slug AS unitSlug, classes_lnp.name AS className, lessons_lnp.name AS lessonName, units_lnp.name AS unitName FROM weekly_duty_lnp INNER JOIN classes_lnp ON weekly_duty_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON weekly_duty_lnp.lesson_id = lessons_lnp.id INNER JOIN units_lnp ON weekly_duty_lnp.unit_id = units_lnp.id');

			if (!$stmt->execute(array())) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 2) {
			$school = $_SESSION['school_id'];
			$stmt = $this->connect()->prepare('SELECT weekly_duty_lnp.id AS unitID, weekly_duty_lnp.name AS weeklyName, weekly_duty_lnp.start_date AS start_date, weekly_duty_lnp.end_date AS end_date, weekly_duty_lnp.slug AS unitSlug, classes_lnp.name AS className, lessons_lnp.name AS lessonName, units_lnp.name AS unitName FROM weekly_duty_lnp INNER JOIN classes_lnp ON weekly_duty_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON weekly_duty_lnp.lesson_id = lessons_lnp.id INNER JOIN units_lnp ON weekly_duty_lnp.unit_id = units_lnp.id WHERE weekly_duty_lnp.school_id = ?');

			if (!$stmt->execute(array($school))) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 3) {
			$school = $_SESSION['school_id'];
			$stmt = $this->connect()->prepare('SELECT weekly_duty_lnp.id AS unitID, weekly_duty_lnp.name AS weeklyName, weekly_duty_lnp.start_date AS start_date, weekly_duty_lnp.end_date AS end_date, weekly_duty_lnp.slug AS unitSlug, classes_lnp.name AS className, lessons_lnp.name AS lessonName, units_lnp.name AS unitName FROM weekly_duty_lnp INNER JOIN classes_lnp ON weekly_duty_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON weekly_duty_lnp.lesson_id = lessons_lnp.id INNER JOIN units_lnp ON weekly_duty_lnp.unit_id = units_lnp.id WHERE weekly_duty_lnp.school_id = ?');

			if (!$stmt->execute(array($school))) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 4) {
			$school = $_SESSION['school_id'];
			$class_id = $_SESSION['class_id'];
			$lesson_id = $_SESSION['lesson_id'];
			$stmt = $this->connect()->prepare('SELECT weekly_duty_lnp.id AS unitID, weekly_duty_lnp.name AS weeklyName, weekly_duty_lnp.start_date AS start_date, weekly_duty_lnp.end_date AS end_date, weekly_duty_lnp.slug AS unitSlug, classes_lnp.name AS className, lessons_lnp.name AS lessonName, units_lnp.name AS unitName FROM weekly_duty_lnp INNER JOIN classes_lnp ON weekly_duty_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON weekly_duty_lnp.lesson_id = lessons_lnp.id INNER JOIN units_lnp ON weekly_duty_lnp.unit_id = units_lnp.id WHERE weekly_duty_lnp.school_id = ? AND weekly_duty_lnp.class_id = ? AND weekly_duty_lnp.lesson_id = ?');

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

		$stmt = $this->connect()->prepare('SELECT weekly_duty_lnp.id AS unitID, weekly_duty_lnp.name AS weeklyName, weekly_duty_lnp.slug AS unitSlug, classes_lnp.name AS className, lessons_lnp.name AS lessonName, units_lnp.name AS unitName FROM weekly_duty_lnp INNER JOIN classes_lnp ON weekly_duty_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON weekly_duty_lnp.lesson_id = lessons_lnp.id');

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

	protected function getWeekliesListStu($lessonId, $classId, $schoolId)
	{
		$stmt = $this->connect()->prepare('SELECT id, name, slug, short_desc, photo FROM weekly_duty_lnp WHERE lesson_id = ? AND class_id = ? AND school_id = ?');

		if (!$stmt->execute(array($lessonId, $classId, $schoolId))) {
			$stmt = null;
			exit();
		}

		$unitData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $unitData;

		$stmt = null;
	}

	public function getOneWeekly($slug)
	{

		$stmt = $this->connect()->prepare('SELECT * FROM weekly_duty_lnp WHERE slug = ?');

		if (!$stmt->execute(array($slug))) {
			$stmt = null;
			exit();
		}

		$unitData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $unitData;

		$stmt = null;
	}

	public function getWeeklies()
	{

		$stmt = $this->connect()->prepare('SELECT * FROM weekly_duty_lnp');

		if (!$stmt->execute(array(0))) {
			$stmt = null;
			exit();
		}

		$unitData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $unitData;

		$stmt = null;
	}

	public function getWeekliesForTopicList($class, $lessons)
	{
		if ($_SESSION['role'] == 1) {
			$stmt = $this->connect()->prepare('SELECT id, name FROM weekly_duty_lnp WHERE class_id = ? AND lesson_id=?');

			if (!$stmt->execute(array($class, $lessons))) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 3) {
			$school = $_SESSION['school_id'];
			$stmt = $this->connect()->prepare('SELECT id, name FROM weekly_duty_lnp WHERE class_id = ? AND lesson_id=? AND school_id=?');

			if (!$stmt->execute(array($class, $lessons, $school))) {
				$stmt = null;
				exit();
			}
		}

		$unitData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $unitData;

		$stmt = null;
	}

	public function getSameWeeklies($active_slug)
	{

		$stmt = $this->connect()->prepare('SELECT school_id, class_id, lesson_id FROM weekly_duty_lnp WHERE slug = ?');

		if (!$stmt->execute(array($active_slug))) {
			$stmt = null;
			exit();
		}

		$unitData = $stmt->fetch(PDO::FETCH_ASSOC);

		$school_id = $unitData['school_id'];
		$class_id = $unitData['class_id'];
		$lesson_id = $unitData['lesson_id'];

		$school = $_SESSION['school_id'];
		$stmt2 = $this->connect()->prepare('SELECT weekly_duty_lnp.id AS unitID, weekly_duty_lnp.name AS weeklyName, weekly_duty_lnp.slug AS unitSlug, classes_lnp.name AS className, lessons_lnp.name AS lessonName, units_lnp.name AS unitName FROM weekly_duty_lnp INNER JOIN classes_lnp ON weekly_duty_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON weekly_duty_lnp.lesson_id = lessons_lnp.id INNER JOIN units_lnp ON weekly_duty_lnp.unit_id = units_lnp.id WHERE weekly_duty_lnp.school_id = ? AND weekly_duty_lnp.school_id = ? AND weekly_duty_lnp.class_id = ? AND weekly_duty_lnp.lesson_id = ? AND weekly_duty_lnp.slug != ?');

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
