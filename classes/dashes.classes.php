<?php

class Dashes extends Dbh
{

	public function getPrivateLessonList()
	{


		$stmt = null;
		$userId = $_SESSION['id'] ?? null;

		$events = [];

		$today = date('Y-m-d');
		$endDate = date('Y-m-d', strtotime('+5 days'));

		$sql = "
    SELECT 
        m.id, 
        m.description, 
        m.meeting_date,
        m.zoom_start_url,
        m.zoom_join_url,
        CONCAT_WS(' ', u_organizer.name, u_organizer.surname) AS organizer_fullname,
        CONCAT_WS(' ', u_participant.name, u_participant.surname) AS participant_fullname
    FROM meetings_lnp m
    LEFT JOIN users_lnp u_organizer ON m.organizer_id = u_organizer.id
    LEFT JOIN users_lnp u_participant ON m.participant_id = u_participant.id
    WHERE DATE(m.meeting_date) BETWEEN :today AND :endDate
";

		$params = [
			':today' => $today,
			':endDate' => $endDate,
		];

		if ($userId !== null) {
			$sql .= " AND (m.organizer_id = :userId OR m.participant_id = :userId)";
			$params[':userId'] = $userId;
		}

		
		try {
			$stmt = $this->connect()->prepare($sql);
			$stmt->execute($params);
			$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

			foreach ($results as $row) {
				$events[] = [
					'id' => 'meeting_' . $row['id'],
					'title' => $row['description'],
					'start' => $row['meeting_date'],
					'zoom_join_url' => $row['zoom_join_url'],
					'allDay' => false,
					'extendedProps' => [
						'type' => 'Toplantı',
						'description' => $row['description'],
						'organizerName' => $row['organizer_fullname'],
						'participantName' => $row['participant_fullname'],
					],
					'backgroundColor' => '#007bff',
					'borderColor' => '#007bff',
				];
			}

			return $events;
		} catch (PDOException $e) {
			error_log("Veritabanı hatası (getCalendarEvents - meetings_lnp): " . $e->getMessage());
			echo json_encode([]);
		}
	}
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



	public function getUnits($lesson_id, $class_id, $school_id)
	{

		$stmt = $this->connect()->prepare('SELECT * FROM units_lnp WHERE school_id = ? AND lesson_id = ? AND class_id = ?');

		if (!$stmt->execute(array($school_id, $lesson_id, $class_id))) {
			$stmt = null;
			exit();
		}

		$unitData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $unitData;

		$stmt = null;
	}

	public function getPreSchoolUnits($lesson_id, $class_id)
	{

		$stmt = $this->connect()->prepare('SELECT * FROM main_school_units_lnp WHERE lesson_id = ? AND class_id = ?');

		if (!$stmt->execute(array($lesson_id, $class_id))) {
			$stmt = null;
			exit();
		}

		$unitData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $unitData;

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
