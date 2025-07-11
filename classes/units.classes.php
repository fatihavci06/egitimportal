<?php

class Units extends Dbh
{

	public function getUnitsList()
	{

		if ($_SESSION['role'] == 1) {
			$stmt = $this->connect()->prepare('SELECT 
			units_lnp.id AS unitID, units_lnp.name AS unitName, 
			units_lnp.slug AS unitSlug, units_lnp.start_date AS unitStartDate, 
			units_lnp.end_date AS unitEndDate, 
			units_lnp.order_no AS unitOrder, 
			units_lnp.active AS unitActive, 
			classes_lnp.name AS className, 
			classes_lnp.slug AS classSlug, 
			lessons_lnp.name AS lessonName 
			FROM units_lnp 
			INNER JOIN classes_lnp ON units_lnp.class_id = classes_lnp.id 
			INNER JOIN lessons_lnp ON units_lnp.lesson_id = lessons_lnp.id');

			if (!$stmt->execute(array())) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 2) {
			$school = $_SESSION['school_id'];
			$stmt = $this->connect()->prepare('SELECT 
			units_lnp.id AS unitID, 
			units_lnp.name AS unitName, 
			units_lnp.slug AS unitSlug, 
			units_lnp.start_date AS unitStartDate, 
			units_lnp.end_date AS unitEndDate, 
			units_lnp.order_no AS unitOrder, 
			units_lnp.active AS unitActive, 
			classes_lnp.name AS className, 
			classes_lnp.slug AS classSlug, 
			lessons_lnp.name AS lessonName 
			FROM units_lnp 
			INNER JOIN classes_lnp ON units_lnp.class_id = classes_lnp.id 
			INNER JOIN lessons_lnp ON units_lnp.lesson_id = lessons_lnp.id 
			WHERE units_lnp.school_id = ?');

			if (!$stmt->execute(array($school))) {
				$stmt = null;
				exit();
			}
		} elseif (($_SESSION['role'] == 3) or ($_SESSION['role'] == 8)) {
			$school = $_SESSION['school_id'];
			$stmt = $this->connect()->prepare('SELECT 
			units_lnp.id AS unitID, 
			units_lnp.name AS unitName, 
			units_lnp.slug AS unitSlug, 
			units_lnp.start_date AS unitStartDate, 
			units_lnp.end_date AS unitEndDate, 
			units_lnp.order_no AS unitOrder, 
			units_lnp.active AS unitActive, 
			classes_lnp.name AS className, 
			classes_lnp.slug AS classSlug, 
			lessons_lnp.name AS lessonName 
			FROM units_lnp 
			INNER JOIN classes_lnp ON units_lnp.class_id = classes_lnp.id 
			INNER JOIN lessons_lnp ON units_lnp.lesson_id = lessons_lnp.id 
			WHERE units_lnp.school_id = ?');

			if (!$stmt->execute(array($school))) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 4) {
			$school = $_SESSION['school_id'];
			$class_id = $_SESSION['class_id'];
			$lesson_id = $_SESSION['lesson_id'];
			$stmt = $this->connect()->prepare('SELECT 
			units_lnp.id AS unitID,
			units_lnp.name AS unitName, 
			units_lnp.slug AS unitSlug, 
			units_lnp.start_date AS unitStartDate, 
			units_lnp.end_date AS unitEndDate, 
			units_lnp.order_no AS unitOrder, 
			units_lnp.active AS unitActive, 
			classes_lnp.name AS className, 
			classes_lnp.slug AS classSlug, 
			lessons_lnp.name AS lessonName 
			FROM units_lnp 
			INNER JOIN classes_lnp ON units_lnp.class_id = classes_lnp.id 
			INNER JOIN lessons_lnp ON units_lnp.lesson_id = lessons_lnp.id 
			WHERE units_lnp.school_id = ? AND units_lnp.class_id = ? AND units_lnp.lesson_id = ?');

			if (!$stmt->execute(array($school, $class_id, $lesson_id))) {
				$stmt = null;
				exit();
			}
		} else {
			$school = $_SESSION['school_id'];
			$class_id = $_SESSION['class_id'];
			$lesson_id = $_SESSION['lesson_id'];
			$stmt = $this->connect()->prepare('SELECT 
			units_lnp.id AS unitID,
			units_lnp.name AS unitName, 
			units_lnp.slug AS unitSlug, 
			units_lnp.start_date AS unitStartDate, 
			units_lnp.end_date AS unitEndDate, 
			units_lnp.order_no AS unitOrder, 
			units_lnp.active AS unitActive, 
			classes_lnp.name AS className, 
			classes_lnp.slug AS classSlug, 
			lessons_lnp.name AS lessonName 
			FROM units_lnp 
			INNER JOIN classes_lnp ON units_lnp.class_id = classes_lnp.id 
			INNER JOIN lessons_lnp ON units_lnp.lesson_id = lessons_lnp.id 
			WHERE units_lnp.school_id = ? AND units_lnp.class_id = ? AND units_lnp.lesson_id = ?');

			if (!$stmt->execute(array($school, $class_id, $lesson_id))) {
				$stmt = null;
				exit();
			}
		}

		$unitData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $unitData;

		$stmt = null;
	}

	protected function getUnitsListWithFilter()
	{

		if ($_SESSION['role'] == 1) {

			$filtre_durum = isset($_GET['durum']) ? $_GET['durum'] : '';
			$filtre_ders = isset($_GET['ders']) ? $_GET['ders'] : '';
			$filtre_sinif = isset($_GET['sinif']) ? $_GET['sinif'] : '';

			$sql = 'SELECT 
			units_lnp.id AS unitID,
			units_lnp.name AS unitName,
			units_lnp.slug AS unitSlug,
			
			units_lnp.start_date AS unitStartDate,
			units_lnp.end_date AS unitEndDate, 
			units_lnp.order_no AS unitOrder, 
			units_lnp.active AS unitActive, 
			classes_lnp.name AS className, 
			classes_lnp.slug AS classSlug, 
			lessons_lnp.name AS lessonName 
			FROM units_lnp 
			INNER JOIN classes_lnp ON units_lnp.class_id = classes_lnp.id 
			INNER JOIN lessons_lnp ON units_lnp.lesson_id = lessons_lnp.id';

			$whereClauses = [];
			$parameters = [];

			// Durum filtresi varsa ekle
			if (!empty($filtre_durum)) {
				if ($filtre_durum == 'aktif') {
					$filtre_durum = 1;
				} elseif ($filtre_durum == 'pasif') {
					$filtre_durum = 0;
				}
				$whereClauses[] = "units_lnp.active = ?";
				$parameters[] = $filtre_durum;
			}

			// Ders filtresi varsa ekle
			if (!empty($filtre_ders)) {
				$whereClauses[] = "units_lnp.lesson_id = ?";
				$parameters[] = $filtre_ders;
			}

			// Sınıf filtresi varsa ekle
			if (!empty($filtre_sinif)) {
				$whereClauses[] = "units_lnp.class_id = ?";
				$parameters[] = $filtre_sinif;
			}

			// WHERE koşulları varsa sorguya ekle
			if (!empty($whereClauses)) {
				$sql .= " WHERE " . implode(" AND ", $whereClauses);
			}

			$stmt = $this->connect()->prepare($sql);

			if (!$stmt->execute($parameters)) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 2) {
			$school = $_SESSION['school_id'];
			$stmt = $this->connect()->prepare('
			SELECT 
			units_lnp.id AS unitID, 
			units_lnp.name AS unitName, 
			units_lnp.slug AS unitSlug, 
			
			units_lnp.start_date AS unitStartDate, 
			units_lnp.end_date AS unitEndDate, 
			units_lnp.order_no AS unitOrder, 
			units_lnp.active AS unitActive, 
			classes_lnp.name AS className, 
			classes_lnp.slug AS classSlug, 
			lessons_lnp.name AS lessonName 
			FROM units_lnp 
			INNER JOIN classes_lnp ON units_lnp.class_id = classes_lnp.id 
			INNER JOIN lessons_lnp ON units_lnp.lesson_id = lessons_lnp.id 
			WHERE units_lnp.school_id = ?');

			if (!$stmt->execute(array($school))) {
				$stmt = null;
				exit();
			}
		} elseif (($_SESSION['role'] == 3) or ($_SESSION['role'] == 8)) {

			$school = $_SESSION['school_id'];

			$filtre_durum = isset($_GET['durum']) ? $_GET['durum'] : '';
			$filtre_ders = isset($_GET['ders']) ? $_GET['ders'] : '';
			$filtre_sinif = isset($_GET['sinif']) ? $_GET['sinif'] : '';

			$sql = '
			SELECT units_lnp.id AS unitID, 
			units_lnp.name AS unitName, 
			units_lnp.slug AS unitSlug, 
			
			units_lnp.start_date AS unitStartDate, 
			units_lnp.end_date AS unitEndDate, 
			units_lnp.order_no AS unitOrder, 
			units_lnp.active AS unitActive, 
			classes_lnp.name AS className, 
			classes_lnp.slug AS classSlug, 
			lessons_lnp.name AS lessonName 
			FROM units_lnp 
			INNER JOIN classes_lnp ON units_lnp.class_id = classes_lnp.id 
			INNER JOIN lessons_lnp ON units_lnp.lesson_id = lessons_lnp.id';

			$whereClauses = [];
			$parameters = [];

			$whereClauses[] = "units_lnp.school_id = ?";
			$parameters[] = $school;

			// Durum filtresi varsa ekle
			if (!empty($filtre_durum)) {
				if ($filtre_durum == 'aktif') {
					$filtre_durum = 1;
				} elseif ($filtre_durum == 'pasif') {
					$filtre_durum = 0;
				}
				$whereClauses[] = "units_lnp.active = ?";
				$parameters[] = $filtre_durum;
			}

			// Ders filtresi varsa ekle
			if (!empty($filtre_ders)) {
				$whereClauses[] = "units_lnp.lesson_id = ?";
				$parameters[] = $filtre_ders;
			}

			// Sınıf filtresi varsa ekle
			if (!empty($filtre_sinif)) {
				$whereClauses[] = "units_lnp.class_id = ?";
				$parameters[] = $filtre_sinif;
			}

			// WHERE koşulları varsa sorguya ekle
			if (!empty($whereClauses)) {
				$sql .= " WHERE " . implode(" AND ", $whereClauses);
			}

			$stmt = $this->connect()->prepare($sql);

			if (!$stmt->execute($parameters)) {
				$stmt = null;
				exit();
			}

			/* 
			$school = $_SESSION['school_id'];
			$stmt = $this->connect()->prepare('SELECT units_lnp.id AS unitID, units_lnp.name AS unitName, units_lnp.slug AS unitSlug, units_lnp.start_date AS unitStartDate, units_lnp.end_date AS unitEndDate, units_lnp.order_no AS unitOrder, units_lnp.active AS unitActive, classes_lnp.name AS className, classes_lnp.slug AS classSlug, lessons_lnp.name AS lessonName FROM units_lnp INNER JOIN classes_lnp ON units_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON units_lnp.lesson_id = lessons_lnp.id WHERE units_lnp.school_id = ?');

			if (!$stmt->execute(array($school))) {
				$stmt = null;
				exit();
			} */
		} elseif ($_SESSION['role'] == 4) {
			$school = $_SESSION['school_id'];
			$class_id = $_SESSION['class_id'];
			$lesson_id = $_SESSION['lesson_id'];
			$stmt = $this->connect()->prepare('SELECT 
			units_lnp.id AS unitID, 
			units_lnp.name AS unitName, 
			units_lnp.slug AS unitSlug,
			
			units_lnp.start_date AS unitStartDate, 
			units_lnp.end_date AS unitEndDate, 
			units_lnp.order_no AS unitOrder, 
			units_lnp.active AS unitActive, 
			classes_lnp.name AS className, 
			classes_lnp.slug AS classSlug, 
			lessons_lnp.name AS lessonName 
			FROM units_lnp 
			INNER JOIN classes_lnp ON units_lnp.class_id = classes_lnp.id 
			INNER JOIN lessons_lnp ON units_lnp.lesson_id = lessons_lnp.id 
			WHERE units_lnp.school_id = ? AND units_lnp.class_id = ? AND units_lnp.lesson_id = ?');

			if (!$stmt->execute(array($school, $class_id, $lesson_id))) {
				$stmt = null;
				exit();
			}
		}else{
			return [];
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
		$stmt = $this->connect()->prepare('SELECT * FROM units_lnp WHERE lesson_id = ? AND class_id = ? AND (school_id = ? OR school_id = ?) AND active = 1');

		if (!$stmt->execute(array($lessonId, $classId, $schoolId, "1"))) {
			$stmt = null;
			exit();
		}

		$unitData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $unitData;

		$stmt = null;
	}

	public function getPrevUnitId($orderNo, $classId, $lessonId, $schoolId)
	{
		$stmt = $this->connect()->prepare('SELECT id FROM units_lnp WHERE lesson_id = ? AND class_id = ? AND order_no = ? AND (school_id = ? OR school_id = ?) AND active = 1');

		if (!$stmt->execute(array($lessonId, $classId, $orderNo, $schoolId, "1"))) {
			$stmt = null;
			exit();
		}

		$unitData = $stmt->fetch(PDO::FETCH_ASSOC);

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
			$stmt = $this->connect()->prepare('SELECT units_lnp.*, classes_lnp.name AS className, lessons_lnp.name AS lessonName, lessons_lnp.id AS lessonId ,units_lnp.development_package_id FROM units_lnp INNER JOIN classes_lnp ON units_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON units_lnp.lesson_id = lessons_lnp.id WHERE units_lnp.slug = ?');

			if (!$stmt->execute(array($slug))) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 3 or $_SESSION['role'] == 8) {
			$school = $_SESSION['school_id'];
			$stmt = $this->connect()->prepare('SELECT units_lnp.*, classes_lnp.name AS className, lessons_lnp.name AS lessonName, lessons_lnp.id AS lessonId,units_lnp.development_package_id FROM units_lnp INNER JOIN classes_lnp ON units_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON units_lnp.lesson_id = lessons_lnp.id WHERE units_lnp.slug = ? AND units_lnp.school_id = ?');

			if (!$stmt->execute(array($slug, $school))) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 4) {
			$school = $_SESSION['school_id'];
			$class_id = $_SESSION['class_id'];
			$lesson_id = $_SESSION['lesson_id'];
			$stmt = $this->connect()->prepare('SELECT units_lnp.*, classes_lnp.name AS className, lessons_lnp.name AS lessonName, lessons_lnp.id AS lessonId,units_lnp.development_package_id FROM units_lnp INNER JOIN classes_lnp ON units_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON units_lnp.lesson_id = lessons_lnp.id WHERE units_lnp.slug = ? AND units_lnp.school_id = ? AND units_lnp.class_id = ? AND units_lnp.lesson_id = ?');

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

	public function getUnitForTopicList($class, $lesson)
	{
		if ($_SESSION['role'] == 1) {
			$stmt = $this->connect()->prepare('SELECT id, name FROM units_lnp WHERE class_id = ? AND lesson_id=?');

			if (!$stmt->execute(array($class, $lesson))) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 3 or $_SESSION['role'] == 8 or $_SESSION['role'] == 3 or $_SESSION['role'] == 4) {
			$school = $_SESSION['school_id'];
			$stmt = $this->connect()->prepare('SELECT id, name FROM units_lnp WHERE class_id = ? AND lesson_id=? AND school_id=?');

			if (!$stmt->execute(array($class, $lesson, $school))) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 2 or $_SESSION['role'] == 5) {
			// $school = $_SESSION['school_id'];

			$stmt = $this->connect()->prepare('SELECT id, name FROM units_lnp WHERE class_id = ? AND lesson_id=?');

			if (!$stmt->execute(array($class, $lesson))) {
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
		$stmt2 = $this->connect()->prepare('SELECT units_lnp.id AS unitID, units_lnp.name AS unitName, units_lnp.lesson_id AS lesson_id, units_lnp.class_id AS class_id, units_lnp.order_no AS order_no, units_lnp.start_date AS start_date, units_lnp.slug AS unitSlug, classes_lnp.name AS className, lessons_lnp.name AS lessonName FROM units_lnp INNER JOIN classes_lnp ON units_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON units_lnp.lesson_id = lessons_lnp.id WHERE units_lnp.school_id = ? AND units_lnp.school_id = ? AND units_lnp.class_id = ? AND units_lnp.lesson_id = ? AND units_lnp.slug != ?');

		if (!$stmt2->execute(array($school, $school_id, $class_id, $lesson_id, $active_slug))) {
			$stmt2 = null;
			exit();
		}

		$unitData2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

		return $unitData2;

		$stmt = null;
		$stmt2 = null;
	}
	public function getUnitByLessonId($schoolId, $classId, $lessonId)
	{

		$stmt = $this->connect()->prepare('SELECT id, name FROM units_lnp WHERE  school_id=? AND class_id = ? AND lesson_id=? ');

		if (!$stmt->execute([$schoolId, $classId, $lessonId])) {
			$stmt = null;
			exit();
		}
		$unitData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $unitData;
	}
}
