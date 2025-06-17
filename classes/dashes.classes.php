<?php

class Dashes extends Dbh
{

	public function getTests()
	{

		$stmt = $this->connect()->prepare('SELECT tests_lnp.*, classes_lnp.name AS className FROM tests_lnp INNER JOIN classes_lnp ON tests_lnp.class_id = classes_lnp.id ORDER BY tests_lnp.id DESC');

		if (!$stmt->execute(array())) {
			$stmt = null;
			exit();
		}

		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $getData;

		$stmt = null;
	}

	public function getHomeworks()
	{

		$stmt = $this->connect()->prepare('SELECT homework_content_lnp.*, classes_lnp.name AS className FROM homework_content_lnp INNER JOIN classes_lnp ON homework_content_lnp.class_id = classes_lnp.id ORDER BY homework_content_lnp.id DESC');

		if (!$stmt->execute(array())) {
			$stmt = null;
			exit();
		}

		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $getData;

		$stmt = null;
	}

	public function getPayments()
	{

		$stmt = $this->connect()->prepare('SELECT SUM(pay_amount) AS total_payments, SUM(kdv_amount) AS total_vat, COUNT(*) AS total_count FROM package_payments_lnp WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE());');

		if (!$stmt->execute(array())) {
			$stmt = null;
			exit();
		}

		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $getData;

		$stmt = null;
	}
}

class DashesStudent extends Dbh
{
	private $startOfWeek;
	private $endOfWeek;

	public function __construct()
	{
		$this->startOfWeek = date('Y-m-d', strtotime('monday this week'));
		$this->endOfWeek   = date('Y-m-d', strtotime('sunday this week'));
	}

	public function getUnitsDash($lesson_id)
	{

		$stmt = $this->connect()->prepare('SELECT * FROM units_lnp WHERE class_id = ? AND lesson_id = ? AND (start_date <= ? AND end_date >= ?) ORDER BY order_no ASC');

		if (!$stmt->execute(array($_SESSION['class_id'], $lesson_id, $this->startOfWeek, $this->endOfWeek))) {
			$stmt = null;
			exit();
		}

		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $getData;

		$stmt = null;
	}

	public function getTopicsDash($lesson_id, $unit_id)
	{

		$stmt = $this->connect()->prepare('SELECT * FROM topics_lnp WHERE class_id = ? AND lesson_id = ? AND unit_id = ? AND (start_date <= ? AND end_date >= ?) ORDER BY order_no ASC');

		if (!$stmt->execute(array($_SESSION['class_id'], $lesson_id, $unit_id, $this->startOfWeek, $this->endOfWeek))) {
			$stmt = null;
			exit();
		}

		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $getData;

		$stmt = null;
	}

	public function getSubTopicsDash($lesson_id, $unit_id, $topic_id)
	{

		$stmt = $this->connect()->prepare('SELECT * FROM subtopics_lnp WHERE class_id = ? AND lesson_id = ? AND unit_id = ? AND topic_id = ? AND (start_date <= ? AND end_date >= ?) ORDER BY order_no ASC');

		if (!$stmt->execute(array($_SESSION['class_id'], $lesson_id, $unit_id, $topic_id, $this->startOfWeek, $this->endOfWeek))) {
			$stmt = null;
			exit();
		}

		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $getData;

		$stmt = null;
	}

	public function getTestsStudent()
	{

		$stmt = $this->connect()->prepare('SELECT * FROM tests_lnp WHERE class_id = ? ORDER BY id DESC');

		if (!$stmt->execute(array($_SESSION['class_id']))) {
			$stmt = null;
			exit();
		}

		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $getData;

		$stmt = null;
	}

	public function getTestControl($test_id)
	{

		$stmt = $this->connect()->prepare('SELECT * FROM user_grades_lnp WHERE class_id = ? AND test_id = ? ORDER BY id DESC');

		if (!$stmt->execute(array($_SESSION['class_id'], $test_id))) {
			$stmt = null;
			exit();
		}

		$getData = $stmt->fetch(PDO::FETCH_ASSOC);

		return $getData;

		$stmt = null;
	}

	public function getHomeworksStudent()
	{

		$stmt = $this->connect()->prepare('SELECT homework_content_lnp.*, classes_lnp.name AS className FROM homework_content_lnp INNER JOIN classes_lnp ON homework_content_lnp.class_id = classes_lnp.id WHERE homework_content_lnp.class_id = ? ORDER BY homework_content_lnp.end_date DESC');

		if (!$stmt->execute(array($_SESSION['class_id']))) {
			$stmt = null;
			exit();
		}

		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $getData;

		$stmt = null;
	}
	
}

class DashesTeacher extends Dbh
{

	public function getTests()
	{

		$stmt = $this->connect()->prepare('SELECT tests_lnp.*, classes_lnp.name AS className FROM tests_lnp INNER JOIN classes_lnp ON tests_lnp.class_id = classes_lnp.id WHERE tests_lnp.teacher_id = ? ORDER BY tests_lnp.id DESC');

		if (!$stmt->execute(array($_SESSION['id']))) {
			$stmt = null;
			exit();
		}

		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $getData;

		$stmt = null;
	}

	public function getHomeworks()
	{

		$stmt = $this->connect()->prepare('SELECT homework_content_lnp.*, classes_lnp.name AS className FROM homework_content_lnp INNER JOIN classes_lnp ON homework_content_lnp.class_id = classes_lnp.id WHERE homework_content_lnp.teacher_id = ? ORDER BY homework_content_lnp.id DESC');

		if (!$stmt->execute(array($_SESSION['id']))) {
			$stmt = null;
			exit();
		}

		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $getData;

		$stmt = null;
	}

	public function getPayments()
	{

		$stmt = $this->connect()->prepare('SELECT SUM(pay_amount) AS total_payments, SUM(kdv_amount) AS total_vat, COUNT(*) AS total_count FROM package_payments_lnp WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE());');

		if (!$stmt->execute(array())) {
			$stmt = null;
			exit();
		}

		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $getData;

		$stmt = null;
	}
}
