<?php

class Topics extends Dbh
{

	protected function getTopicsList()
	{

		if ($_SESSION['role'] == 1) {
			$stmt = $this->connect()->prepare('SELECT topics_lnp.id AS topicID,
			topics_lnp.name AS topicName,
			topics_lnp.slug AS topicSlug,
			topics_lnp.start_date AS topicStartDate,
			topics_lnp.end_date AS topicEndDate, 
			topics_lnp.order_no AS topicOrder, 
			topics_lnp.active AS topicActive, 
			classes_lnp.name AS className, 
			lessons_lnp.name AS lessonName, 
			units_lnp.name AS unitName 
			FROM topics_lnp 
			INNER JOIN classes_lnp ON topics_lnp.class_id = classes_lnp.id 
			INNER JOIN lessons_lnp ON topics_lnp.lesson_id = lessons_lnp.id 
			INNER JOIN units_lnp ON topics_lnp.unit_id = units_lnp.id');

			if (!$stmt->execute(array())) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 2) {
			$school = $_SESSION['school_id'];
			$stmt = $this->connect()->prepare('SELECT 
			topics_lnp.id AS topicID, 
			topics_lnp.name AS topicName, 
			topics_lnp.slug AS topicSlug, 
			topics_lnp.start_date AS topicStartDate, 
			topics_lnp.end_date AS topicEndDate, 
			topics_lnp.order_no AS topicOrder, 
			topics_lnp.active AS topicActive, 
			classes_lnp.name AS className, 
			lessons_lnp.name AS lessonName, 
			units_lnp.name AS unitName 
			FROM topics_lnp 
			INNER JOIN classes_lnp ON topics_lnp.class_id = classes_lnp.id 
			INNER JOIN lessons_lnp ON topics_lnp.lesson_id = lessons_lnp.id 
			INNER JOIN units_lnp ON topics_lnp.unit_id = units_lnp.id 
			WHERE units_lnp.school_id = ?');

			if (!$stmt->execute(array($school))) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 3) {
			$school = $_SESSION['school_id'];
			$stmt = $this->connect()->prepare('SELECT 
			topics_lnp.id AS topicID, 
			topics_lnp.name AS topicName, 
			topics_lnp.slug AS topicSlug, 
			topics_lnp.start_date AS topicStartDate, 
			topics_lnp.end_date AS topicEndDate, 
			topics_lnp.order_no AS topicOrder, 
			topics_lnp.active AS topicActive, 
			classes_lnp.name AS className, 
			lessons_lnp.name AS lessonName, 
			units_lnp.name AS unitName 
			FROM topics_lnp 
			INNER JOIN classes_lnp ON topics_lnp.class_id = classes_lnp.id 
			INNER JOIN lessons_lnp ON topics_lnp.lesson_id = lessons_lnp.id 
			INNER JOIN units_lnp ON topics_lnp.unit_id = units_lnp.id 
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
			topics_lnp.id AS topicID, 
			topics_lnp.name AS topicName, 
			topics_lnp.slug AS topicSlug, 
			topics_lnp.start_date AS topicStartDate, 
			topics_lnp.end_date AS topicEndDate, 
			topics_lnp.order_no AS topicOrder, 
			topics_lnp.active AS topicActive, 
			classes_lnp.name AS className, 
			lessons_lnp.name AS lessonName, 
			units_lnp.name AS unitName 
			FROM topics_lnp 
			INNER JOIN classes_lnp ON topics_lnp.class_id = classes_lnp.id 
			INNER JOIN lessons_lnp ON topics_lnp.lesson_id = lessons_lnp.id 
			INNER JOIN units_lnp ON topics_lnp.unit_id = units_lnp.id 
			WHERE units_lnp.school_id = ? AND  units_lnp.class_id = ? AND units_lnp.lesson_id = ?');

			if (!$stmt->execute(array($school, $class_id, $lesson_id))) {
				$stmt = null;
				exit();
			}
		}

		$lessonData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $lessonData;

		$stmt = null;
	}

	protected function getTopicsListWithFilter()
	{

		if ($_SESSION['role'] == 1) {

			$filtre_durum = isset($_GET['durum']) ? $_GET['durum'] : '';
			$filtre_ders = isset($_GET['ders']) ? $_GET['ders'] : '';
			$filtre_sinif = isset($_GET['sinif']) ? $_GET['sinif'] : '';
			$filtre_unite = isset($_GET['unite']) ? $_GET['unite'] : '';

			$sql = "SELECT 
			topics_lnp.id AS topicID, 
			topics_lnp.name AS topicName, 
			topics_lnp.slug AS topicSlug,
			 
			topics_lnp.start_date AS topicStartDate, 
			topics_lnp.end_date AS topicEndDate, 
			topics_lnp.order_no AS topicOrder, 
			topics_lnp.active AS topicActive, 
			classes_lnp.name AS className, 
			lessons_lnp.name AS lessonName, 
			units_lnp.name AS unitName 
			FROM topics_lnp 
			INNER JOIN classes_lnp ON topics_lnp.class_id = classes_lnp.id 
			INNER JOIN lessons_lnp ON topics_lnp.lesson_id = lessons_lnp.id 
			INNER JOIN units_lnp ON topics_lnp.unit_id = units_lnp.id";
			
			$whereClauses = [];
			$parameters = [];

			// Durum filtresi varsa ekle
			if (!empty($filtre_durum)) {
				if($filtre_durum == 'aktif') {
					$filtre_durum = 1;
				} elseif ($filtre_durum == 'pasif') {
					$filtre_durum = 0;
				}
				$whereClauses[] = "topics_lnp.active = ?";
				$parameters[] = $filtre_durum;
			}
			if ($filtre_ders) {
				$whereClauses[] = "topics_lnp.lesson_id = ?";
				$parameters[] = $filtre_ders;
			}
			if ($filtre_sinif) {
				$whereClauses[] = "topics_lnp.class_id = ?";
				$parameters[] = $filtre_sinif;
			}
			if ($filtre_unite) {
				$whereClauses[] = "topics_lnp.unit_id = ?";
				$parameters[] = $filtre_unite;
			}

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
			$stmt = $this->connect()->prepare('SELECT 
			topics_lnp.id AS topicID, 
			topics_lnp.name AS topicName,
			  
			topics_lnp.slug AS topicSlug, 
			topics_lnp.start_date AS topicStartDate, 
			topics_lnp.end_date AS topicEndDate, 
			topics_lnp.order_no AS topicOrder, 
			topics_lnp.active AS topicActive, 
			classes_lnp.name AS className,
			lessons_lnp.name AS lessonName, 
			units_lnp.name AS unitName 
			FROM topics_lnp 
			INNER JOIN classes_lnp ON topics_lnp.class_id = classes_lnp.id 
			INNER JOIN lessons_lnp ON topics_lnp.lesson_id = lessons_lnp.id 
			INNER JOIN units_lnp ON topics_lnp.unit_id = units_lnp.id 
			WHERE units_lnp.school_id = ?');

			if (!$stmt->execute(array($school))) {
				$stmt = null;
				exit();
			}
		} elseif (($_SESSION['role'] == 3) or ($_SESSION['role'] == 8)) {
			$school = $_SESSION['school_id'];
			$stmt = $this->connect()->prepare('SELECT 
			topics_lnp.id AS topicID, 
			topics_lnp.name AS topicName, 
			topics_lnp.slug AS topicSlug, 
			 
			topics_lnp.start_date AS topicStartDate, 
			topics_lnp.end_date AS topicEndDate, 
			topics_lnp.order_no AS topicOrder, 
			topics_lnp.active AS topicActive, 
			classes_lnp.name AS className, 
			lessons_lnp.name AS lessonName, 
			units_lnp.name AS unitName 
			FROM topics_lnp 
			INNER JOIN classes_lnp ON topics_lnp.class_id = classes_lnp.id 
			INNER JOIN lessons_lnp ON topics_lnp.lesson_id = lessons_lnp.id 
			INNER JOIN units_lnp ON topics_lnp.unit_id = units_lnp.id 
			WHERE units_lnp.school_id = ?');

			if (!$stmt->execute(array($school))) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 4) {
			$school = $_SESSION['school_id'];
			$class_id = $_SESSION['class_id'];
			$lesson_id = $_SESSION['lesson_id'];
			$stmt = $this->connect()->prepare('SELECT topics_lnp.id AS topicID,
			topics_lnp.name AS topicName, 
			topics_lnp.slug AS topicSlug,
			  
			topics_lnp.start_date AS topicStartDate, 
			topics_lnp.end_date AS topicEndDate, 
			topics_lnp.order_no AS topicOrder, 
			topics_lnp.active AS topicActive, 
			classes_lnp.name AS className, 
			lessons_lnp.name AS lessonName, 
			units_lnp.name AS unitName 
			FROM topics_lnp 
			INNER JOIN classes_lnp ON topics_lnp.class_id = classes_lnp.id 
			INNER JOIN lessons_lnp ON topics_lnp.lesson_id = lessons_lnp.id 
			INNER JOIN units_lnp ON topics_lnp.unit_id = units_lnp.id 
			WHERE units_lnp.school_id = ? AND  units_lnp.class_id = ? AND units_lnp.lesson_id = ?');

			if (!$stmt->execute(array($school, $class_id, $lesson_id))) {
				$stmt = null;
				exit();
			}
		}else{
			return [];
		}

		$lessonData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $lessonData;

		$stmt = null;
	}

	public function getOneTopicDetailsAdmin($slug)
	{

		if ($_SESSION['role'] == 1) {
			$stmt = $this->connect()->prepare('SELECT topics_lnp.*, classes_lnp.name AS className, lessons_lnp.name AS lessonName, units_lnp.name AS unitName FROM topics_lnp INNER JOIN classes_lnp ON topics_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON topics_lnp.lesson_id = lessons_lnp.id INNER JOIN units_lnp ON topics_lnp.unit_id = units_lnp.id WHERE topics_lnp.slug = ?');

			if (!$stmt->execute(array($slug))) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 3 or $_SESSION['role'] == 8) {
			$school = $_SESSION['school_id'];
			$stmt = $this->connect()->prepare('SELECT topics_lnp.*, classes_lnp.name AS className, lessons_lnp.name AS lessonName, units_lnp.name AS unitName FROM topics_lnp INNER JOIN classes_lnp ON topics_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON topics_lnp.lesson_id = lessons_lnp.id INNER JOIN units_lnp ON topics_lnp.unit_id = units_lnp.id WHERE topics_lnp.slug = ? AND topics_lnp.school_id = ?');

			if (!$stmt->execute(array($slug, $school))) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 4) {
			$school = $_SESSION['school_id'];
			$class_id = $_SESSION['class_id'];
			$lesson_id = $_SESSION['lesson_id'];
			$stmt = $this->connect()->prepare('SELECT topics_lnp.*, classes_lnp.name AS className, lessons_lnp.name AS lessonName, units_lnp.name AS unitName FROM topics_lnp INNER JOIN classes_lnp ON topics_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON topics_lnp.lesson_id = lessons_lnp.id INNER JOIN units_lnp ON topics_lnp.unit_id = units_lnp.id WHERE topics_lnp.slug = ? AND topics_lnp.school_id = ? AND topics_lnp.class_id = ? AND topics_lnp.lesson_id = ?');

			if (!$stmt->execute(array($slug, $school, $class_id, $lesson_id))) {
				$stmt = null;
				exit();
			}
		}

		$lessonData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $lessonData;

		$stmt = null;
	}

	public function getOneTopic($slug, $classId)
	{

		$stmt = $this->connect()->prepare('SELECT * FROM topics_lnp WHERE slug = ? AND class_id = ?');

		if (!$stmt->execute(array($slug, $classId))) {
			$stmt = null;
			exit();
		}

		$lessonData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $lessonData;

		$stmt = null;
	}

	public function getTopicsByUnitId($unitId)
	{

		$stmt = $this->connect()->prepare('SELECT * FROM topics_lnp WHERE unit_id = ?');

		if (!$stmt->execute(array($unitId))) {
			$stmt = null;
			exit();
		}

		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $getData;

		$stmt = null;
	}

	public function getTopicsByUnitIdWithDetails($unitId)
	{

		$stmt = $this->connect()->prepare('SELECT topics_lnp.*, classes_lnp.name AS className, lessons_lnp.name AS lessonName, units_lnp.name AS unitName FROM topics_lnp INNER JOIN classes_lnp ON topics_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON topics_lnp.lesson_id = lessons_lnp.id INNER JOIN units_lnp ON topics_lnp.unit_id = units_lnp.id WHERE topics_lnp.unit_id = ?');

		if (!$stmt->execute(array($unitId))) {
			$stmt = null;
			exit();
		}

		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $getData;

		$stmt = null;
	}

	public function getTopics()
	{

		$stmt = $this->connect()->prepare('SELECT * FROM topics_lnp');

		if (!$stmt->execute(array(0))) {
			$stmt = null;
			exit();
		}

		$lessonData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $lessonData;

		$stmt = null;
	}

	public function getUnitDatas($slug)
	{

		$stmt = $this->connect()->prepare('SELECT * FROM units_lnp WHERE slug = ?');

		if (!$stmt->execute(array($slug))) {
			$stmt = null;
			exit();
		}

		$topicData = $stmt->fetch(PDO::FETCH_ASSOC);

		return $topicData;

		$stmt = null;

	}

	public function showTopicsStudent($slug)
	{

		$stmt = $this->connect()->prepare('SELECT id FROM units_lnp WHERE slug = ?');

		if (!$stmt->execute(array($slug))) {
			$stmt = null;
			exit();
		}

		$topicData = $stmt->fetch(PDO::FETCH_ASSOC);

		$unitId = $topicData['id'];

		$stmt2 = $this->connect()->prepare('SELECT topics_lnp.id AS topicID, topics_lnp.class_id AS class_id, topics_lnp.lesson_id AS lesson_id, topics_lnp.unit_id AS unit_id, topics_lnp.order_no AS order_no, topics_lnp.start_date AS start_date, topics_lnp.image AS topicImage, topics_lnp.short_desc AS topicShortDesc, topics_lnp.name AS topicName, topics_lnp.slug AS topicSlug, classes_lnp.name AS className, lessons_lnp.name AS lessonName, units_lnp.name AS unitName, topics_lnp.is_test AS is_test, topics_lnp.is_question AS is_question FROM topics_lnp INNER JOIN classes_lnp ON topics_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON topics_lnp.lesson_id = lessons_lnp.id INNER JOIN units_lnp ON topics_lnp.unit_id = units_lnp.id WHERE topics_lnp.unit_id = ? AND topics_lnp.active = 1 ORDER BY topics_lnp.order_no ASC');

		if (!$stmt2->execute(array($unitId))) {
			$stmt2 = null;
			exit();
		}

		$topicsData = $stmt2->fetchAll(PDO::FETCH_ASSOC);

		return $topicsData;

		$stmt = null;
	}

	public function getPrevTopicId($orderNo, $classId, $lessonId, $unit_id, $schoolId)
	{
		$stmt = $this->connect()->prepare('SELECT id FROM topics_lnp WHERE lesson_id = ? AND class_id = ? AND unit_id = ? AND order_no = ? AND (school_id = ? OR school_id = ?) AND active = 1');

		if (!$stmt->execute(array($lessonId, $classId, $unit_id, $orderNo, $schoolId, "1"))) {
			$stmt = null;
			exit();
		}

		$topicData = $stmt->fetch(PDO::FETCH_ASSOC);

		return $topicData;

		$stmt = null;
	}

	public function getYouTubeVideoId($url)
	{
		// Regular expression to match YouTube video ID
		$pattern = '/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';

		// Perform the regex match
		if (preg_match($pattern, $url, $matches)) {
			return $matches[1]; // The video ID is in the first capturing group
		}

		return null; // Return null if no match is found
	}


	public function getOneTest($id)
	{

		$stmt = $this->connect()->prepare('SELECT * FROM tests_lnp WHERE subtopic_id = ?');

		if (!$stmt->execute(array($id))) {
			$stmt = null;
			exit();
		}

		$testData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $testData;

		$stmt = null;
	}


	public function isSolved($id)
	{

		$stmt = $this->connect()->prepare('SELECT solvedtest_lnp.created_at AS created_at FROM solvedtest_lnp INNER JOIN tests_lnp ON tests_lnp.id = solvedtest_lnp.test_id WHERE tests_lnp.subtopic_id = ?');

		if (!$stmt->execute(array($id))) {
			$stmt = null;
			exit();
		}

		$testData = $stmt->fetch(PDO::FETCH_ASSOC);

		return $testData;

		$stmt = null;
	}


	public function getOneQuestion($id)
	{

		$stmt = $this->connect()->prepare('SELECT * FROM s_questions_lnp WHERE topic_id = ?');

		if (!$stmt->execute(array($id))) {
			$stmt = null;
			exit();
		}

		$questionData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $questionData;

		$stmt = null;
	}


	public function isSolvedQuestion($id)
	{

		$stmt = $this->connect()->prepare('SELECT solved_s_questions_lnp.created_at AS created_at FROM solved_s_questions_lnp INNER JOIN s_questions_lnp ON s_questions_lnp.id = solved_s_questions_lnp.test_id WHERE s_questions_lnp.topic_id = ?');

		if (!$stmt->execute(array($id))) {
			$stmt = null;
			exit();
		}

		$testData = $stmt->fetch(PDO::FETCH_ASSOC);

		return $testData;

		$stmt = null;
	}

	public function getSameTopics($active_slug)
	{

		$stmt = $this->connect()->prepare('SELECT class_id, lesson_id, unit_id FROM topics_lnp WHERE slug = ?');

		if (!$stmt->execute(array($active_slug))) {
			$stmt = null;
			exit();
		}

		$topicData = $stmt->fetch(PDO::FETCH_ASSOC);

		$class_id = $topicData['class_id'];
		$lesson_id = $topicData['lesson_id'];
		$unit_id = $topicData['unit_id'];

		$stmt2 = $this->connect()->prepare('SELECT topics_lnp.id AS topicID, topics_lnp.name AS topicName, topics_lnp.lesson_id AS lesson_id, topics_lnp.class_id AS class_id, topics_lnp.unit_id AS unit_id, topics_lnp.order_no AS order_no, topics_lnp.start_date AS start_date, topics_lnp.slug AS topicSlug, classes_lnp.name AS className, lessons_lnp.name AS lessonName FROM topics_lnp INNER JOIN classes_lnp ON topics_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON topics_lnp.lesson_id = lessons_lnp.id INNER JOIN units_lnp ON topics_lnp.unit_id = units_lnp.id WHERE topics_lnp.class_id = ? AND topics_lnp.lesson_id = ? AND topics_lnp.unit_id = ? AND topics_lnp.slug != ?');

		if (!$stmt2->execute(array($class_id, $lesson_id, $unit_id, $active_slug))) {
			$stmt2 = null;
			exit();
		}

		$topicData2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

		return $topicData2;

		$stmt = null;
		$stmt2 = null;
	}
}

class SubTopics extends Dbh
{

	protected function getSubTopicsList()
	{

		if ($_SESSION['role'] == 1) {
			//$stmt = $this->connect()->prepare('SELECT topics_lnp.id AS topicID, topics_lnp.name AS topicName, topics_lnp.slug AS topicSlug, classes_lnp.name AS className, lessons_lnp.name AS lessonName, units_lnp.name AS unitName FROM topics_lnp INNER JOIN classes_lnp ON topics_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON topics_lnp.lesson_id = lessons_lnp.id INNER JOIN units_lnp ON topics_lnp.unit_id = units_lnp.id');

			$stmt = $this->connect()->prepare('SELECT 
			subtopics_lnp.id AS subTopicID,
			subtopics_lnp.name AS subTopicName,
			subtopics_lnp.slug AS subTopicSlug,
			subtopics_lnp.start_date AS subTopicStartDate,
			subtopics_lnp.end_date AS subTopicEndDate,
			subtopics_lnp.order_no AS subTopicOrder,
			subtopics_lnp.active AS subTopicActive,
			classes_lnp.name AS className,
			lessons_lnp.name AS lessonName,
			units_lnp.name AS unitName,
			topics_lnp.name AS topicName 
			FROM subtopics_lnp 
			INNER JOIN topics_lnp ON subtopics_lnp.topic_id = topics_lnp.id 
			INNER JOIN classes_lnp ON topics_lnp.class_id = classes_lnp.id 
			INNER JOIN lessons_lnp ON topics_lnp.lesson_id = lessons_lnp.id 
			INNER JOIN units_lnp ON topics_lnp.unit_id = units_lnp.id');

			if (!$stmt->execute(array())) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 2) {
			$school = $_SESSION['school_id'];
			$stmt = $this->connect()->prepare('SELECT 
			subtopics_lnp.id AS subTopicID, 
			subtopics_lnp.name AS subTopicName, 
			subtopics_lnp.slug AS subTopicSlug, 
			subtopics_lnp.start_date AS subTopicStartDate, 
			subtopics_lnp.end_date AS subTopicEndDate, 
			subtopics_lnp.order_no AS subTopicOrder, 
			subtopics_lnp.active AS subTopicActive, 
			classes_lnp.name AS className, 
			lessons_lnp.name AS lessonName, 
			units_lnp.name AS unitName, 
			topics_lnp.name AS topicName 
			FROM subtopics_lnp 
			INNER JOIN topics_lnp ON subtopics_lnp.topic_id = topics_lnp.id 
			INNER JOIN classes_lnp ON topics_lnp.class_id = classes_lnp.id 
			INNER JOIN lessons_lnp ON topics_lnp.lesson_id = lessons_lnp.id 
			INNER JOIN units_lnp ON topics_lnp.unit_id = units_lnp.id 
			WHERE subtopics_lnp.school_id = ?');

			if (!$stmt->execute(array($school))) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 3) {
			$school = $_SESSION['school_id'];
			$stmt = $this->connect()->prepare('SELECT 
			subtopics_lnp.id AS subTopicID, 
			subtopics_lnp.name AS subTopicName, 
			subtopics_lnp.slug AS subTopicSlug, 
			subtopics_lnp.start_date AS subTopicStartDate, 
			subtopics_lnp.end_date AS subTopicEndDate, 
			subtopics_lnp.order_no AS subTopicOrder, 
			subtopics_lnp.active AS subTopicActive, 
			classes_lnp.name AS className, 
			lessons_lnp.name AS lessonName, 
			units_lnp.name AS unitName, 
			topics_lnp.name AS topicName 
			FROM subtopics_lnp 
			INNER JOIN topics_lnp ON subtopics_lnp.topic_id = topics_lnp.id 
			INNER JOIN classes_lnp ON topics_lnp.class_id = classes_lnp.id 
			INNER JOIN lessons_lnp ON topics_lnp.lesson_id = lessons_lnp.id 
			INNER JOIN units_lnp ON topics_lnp.unit_id = units_lnp.id 
			WHERE subtopics_lnp.school_id = ?');

			if (!$stmt->execute(array($school))) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 4) {
			$school = $_SESSION['school_id'];
			$class_id = $_SESSION['class_id'];
			$lesson_id = $_SESSION['lesson_id'];
			$stmt = $this->connect()->prepare('SELECT 
			subtopics_lnp.id AS subTopicID, 
			subtopics_lnp.name AS subTopicName, 
			subtopics_lnp.slug AS subTopicSlug, 
			subtopics_lnp.start_date AS subTopicStartDate, 
			subtopics_lnp.end_date AS subTopicEndDate, 
			subtopics_lnp.order_no AS subTopicOrder, 
			subtopics_lnp.active AS subTopicActive, 
			classes_lnp.name AS className, 
			lessons_lnp.name AS lessonName, 
			units_lnp.name AS unitName, 
			topics_lnp.name AS topicName 
			FROM subtopics_lnp 
			INNER JOIN topics_lnp ON subtopics_lnp.topic_id = topics_lnp.id 
			INNER JOIN classes_lnp ON topics_lnp.class_id = classes_lnp.id 
			INNER JOIN lessons_lnp ON topics_lnp.lesson_id = lessons_lnp.id 
			INNER JOIN units_lnp ON topics_lnp.unit_id = units_lnp.id  
			WHERE subtopics_lnp.school_id = ? AND  subtopics_lnp.class_id = ? AND subtopics_lnp.lesson_id = ?');

			if (!$stmt->execute(array($school, $class_id, $lesson_id))) {
				$stmt = null;
				exit();
			}
		}

		$lessonData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $lessonData;

		$stmt = null;
	}

	protected function getSubTopicsListWithFilter()
	{

		if ($_SESSION['role'] == 1) {

			$filtre_durum = isset($_GET['durum']) ? $_GET['durum'] : '';
			$filtre_ders = isset($_GET['ders']) ? $_GET['ders'] : '';
			$filtre_sinif = isset($_GET['sinif']) ? $_GET['sinif'] : '';
			$filtre_unite = isset($_GET['unite']) ? $_GET['unite'] : '';
			$filtre_konu = isset($_GET['konu']) ? $_GET['konu'] : '';

			$sql = 'SELECT 
			subtopics_lnp.id AS subTopicID, 
			subtopics_lnp.name AS subTopicName, 
			subtopics_lnp.slug AS subTopicSlug,
			   
			subtopics_lnp.start_date AS subTopicStartDate, 
			subtopics_lnp.end_date AS subTopicEndDate, 
			subtopics_lnp.order_no AS subTopicOrder, 
			subtopics_lnp.active AS subTopicActive, 
			classes_lnp.name AS className, 
			lessons_lnp.name AS lessonName, 
			units_lnp.name AS unitName, 
			topics_lnp.name AS topicName 
			FROM subtopics_lnp 
			INNER JOIN topics_lnp ON subtopics_lnp.topic_id = topics_lnp.id 
			INNER JOIN classes_lnp ON topics_lnp.class_id = classes_lnp.id 
			INNER JOIN lessons_lnp ON topics_lnp.lesson_id = lessons_lnp.id 
			INNER JOIN units_lnp ON topics_lnp.unit_id = units_lnp.id';

			$whereClauses = [];
			$parameters = [];

			// Durum filtresi varsa ekle
			if (!empty($filtre_durum)) {
				if($filtre_durum == 'aktif') {
					$filtre_durum = 1;
				} elseif ($filtre_durum == 'pasif') {
					$filtre_durum = 0;
				}
				$whereClauses[] = "subtopics_lnp.active = ?";
				$parameters[] = $filtre_durum;
			}

			// Ders filtresi varsa ekle
			if (!empty($filtre_ders)) {
				$whereClauses[] = "subtopics_lnp.lesson_id = ?";
				$parameters[] = $filtre_ders;
			}

			// Sınıf filtresi varsa ekle
			if (!empty($filtre_sinif)) {
				$whereClauses[] = "subtopics_lnp.class_id = ?";
				$parameters[] = $filtre_sinif;
			}

			// Ünite filtresi varsa ekle
			if (!empty($filtre_unite)) {
				$whereClauses[] = "subtopics_lnp.unit_id = ?";
				$parameters[] = $filtre_unite;
			}

			// Konu filtresi varsa ekle
			if (!empty($filtre_konu)) {
				$whereClauses[] = "subtopics_lnp.topic_id = ?";
				$parameters[] = $filtre_konu;
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

			//$stmt = $this->connect()->prepare('SELECT topics_lnp.id AS topicID, topics_lnp.name AS topicName, topics_lnp.slug AS topicSlug, classes_lnp.name AS className, lessons_lnp.name AS lessonName, units_lnp.name AS unitName FROM topics_lnp INNER JOIN classes_lnp ON topics_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON topics_lnp.lesson_id = lessons_lnp.id INNER JOIN units_lnp ON topics_lnp.unit_id = units_lnp.id');

			/* $stmt = $this->connect()->prepare('SELECT subtopics_lnp.id AS subTopicID, subtopics_lnp.name AS subTopicName, subtopics_lnp.slug AS subTopicSlug, subtopics_lnp.start_date AS subTopicStartDate, subtopics_lnp.end_date AS subTopicEndDate, subtopics_lnp.order_no AS subTopicOrder, subtopics_lnp.active AS subTopicActive, classes_lnp.name AS className, lessons_lnp.name AS lessonName, units_lnp.name AS unitName, topics_lnp.name AS topicName FROM subtopics_lnp INNER JOIN topics_lnp ON subtopics_lnp.topic_id = topics_lnp.id INNER JOIN classes_lnp ON topics_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON topics_lnp.lesson_id = lessons_lnp.id INNER JOIN units_lnp ON topics_lnp.unit_id = units_lnp.id'); 

			if (!$stmt->execute(array())) {
				$stmt = null;
				exit();
			}*/
		} elseif ($_SESSION['role'] == 2) {
			$school = $_SESSION['school_id'];
			$stmt = $this->connect()->prepare('SELECT 
			subtopics_lnp.id AS subTopicID, 
			subtopics_lnp.name AS subTopicName, 
			subtopics_lnp.slug AS subTopicSlug, 
			  
			subtopics_lnp.start_date AS subTopicStartDate, 
			subtopics_lnp.end_date AS subTopicEndDate, 
			subtopics_lnp.order_no AS subTopicOrder, 
			subtopics_lnp.active AS subTopicActive, 
			classes_lnp.name AS className, 
			lessons_lnp.name AS lessonName, 
			units_lnp.name AS unitName, 
			topics_lnp.name AS topicName 
			FROM subtopics_lnp 
			INNER JOIN topics_lnp ON subtopics_lnp.topic_id = topics_lnp.id 
			INNER JOIN classes_lnp ON topics_lnp.class_id = classes_lnp.id 
			INNER JOIN lessons_lnp ON topics_lnp.lesson_id = lessons_lnp.id 
			INNER JOIN units_lnp ON topics_lnp.unit_id = units_lnp.id 
			WHERE subtopics_lnp.school_id = ?');

			if (!$stmt->execute(array($school))) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 3) {
			$school = $_SESSION['school_id'];
			$stmt = $this->connect()->prepare('SELECT 
			subtopics_lnp.id AS subTopicID, 
			subtopics_lnp.name AS subTopicName, 
			subtopics_lnp.slug AS subTopicSlug, 
			  
			subtopics_lnp.start_date AS subTopicStartDate, 
			subtopics_lnp.end_date AS subTopicEndDate, 
			subtopics_lnp.order_no AS subTopicOrder, 
			subtopics_lnp.active AS subTopicActive, 
			classes_lnp.name AS className, 
			lessons_lnp.name AS lessonName, 
			units_lnp.name AS unitName, 
			topics_lnp.name AS topicName 
			FROM subtopics_lnp 
			INNER JOIN topics_lnp ON subtopics_lnp.topic_id = topics_lnp.id 
			INNER JOIN classes_lnp ON topics_lnp.class_id = classes_lnp.id 
			INNER JOIN lessons_lnp ON topics_lnp.lesson_id = lessons_lnp.id 
			INNER JOIN units_lnp ON topics_lnp.unit_id = units_lnp.id 
			WHERE subtopics_lnp.school_id = ?');

			if (!$stmt->execute(array($school))) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 4) {
			$school = $_SESSION['school_id'];
			$class_id = $_SESSION['class_id'];
			$lesson_id = $_SESSION['lesson_id'];
			$stmt = $this->connect()->prepare('SELECT 
			subtopics_lnp.id AS subTopicID, 
			subtopics_lnp.name AS subTopicName, 
			subtopics_lnp.slug AS subTopicSlug, 
			  
			subtopics_lnp.start_date AS subTopicStartDate, 
			subtopics_lnp.end_date AS subTopicEndDate, 
			subtopics_lnp.order_no AS subTopicOrder,
			subtopics_lnp.active AS subTopicActive, 
			classes_lnp.name AS className, 
			lessons_lnp.name AS lessonName, 
			units_lnp.name AS unitName, 
			topics_lnp.name AS topicName 
			FROM subtopics_lnp 
			INNER JOIN topics_lnp ON subtopics_lnp.topic_id = topics_lnp.id 
			INNER JOIN classes_lnp ON topics_lnp.class_id = classes_lnp.id 
			INNER JOIN lessons_lnp ON topics_lnp.lesson_id = lessons_lnp.id 
			INNER JOIN units_lnp ON topics_lnp.unit_id = units_lnp.id 
			WHERE subtopics_lnp.school_id = ? AND  subtopics_lnp.class_id = ? AND subtopics_lnp.lesson_id = ?');

			if (!$stmt->execute(array($school, $class_id, $lesson_id))) {
				$stmt = null;
				exit();
			}
		} else{
			return [];
		}

		$lessonData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $lessonData;

		$stmt = null;
	}

	public function getOneSubTopicDetailsAdmin($slug)
	{

		if ($_SESSION['role'] == 1) {
			$stmt = $this->connect()->prepare('SELECT 
			subtopics_lnp.*, classes_lnp.name AS className, lessons_lnp.name AS lessonName, units_lnp.name AS unitName, topics_lnp.name AS topicName FROM subtopics_lnp INNER JOIN topics_lnp ON subtopics_lnp.topic_id = topics_lnp.id INNER JOIN classes_lnp ON topics_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON topics_lnp.lesson_id = lessons_lnp.id INNER JOIN units_lnp ON topics_lnp.unit_id = units_lnp.id WHERE subtopics_lnp.slug = ?');

			if (!$stmt->execute(array($slug))) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 3 or $_SESSION['role'] == 8) {
			$school = $_SESSION['school_id'];
			$stmt = $this->connect()->prepare('SELECT subtopics_lnp.*, classes_lnp.name AS className, lessons_lnp.name AS lessonName, units_lnp.name AS unitName, topics_lnp.name AS topicName FROM subtopics_lnp INNER JOIN topics_lnp ON subtopics_lnp.topic_id = topics_lnp.id INNER JOIN classes_lnp ON topics_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON topics_lnp.lesson_id = lessons_lnp.id INNER JOIN units_lnp ON topics_lnp.unit_id = units_lnp.id WHERE subtopics_lnp.slug = ? AND subtopics_lnp.school_id = ?');

			if (!$stmt->execute(array($slug, $school))) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 4) {
			$school = $_SESSION['school_id'];
			$class_id = $_SESSION['class_id'];
			$lesson_id = $_SESSION['lesson_id'];
			$stmt = $this->connect()->prepare('SELECT subtopics_lnp.*, classes_lnp.name AS className, lessons_lnp.name AS lessonName, units_lnp.name AS unitName, topics_lnp.name AS topicName FROM subtopics_lnp INNER JOIN topics_lnp ON subtopics_lnp.topic_id = topics_lnp.id INNER JOIN classes_lnp ON topics_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON topics_lnp.lesson_id = lessons_lnp.id INNER JOIN units_lnp ON topics_lnp.unit_id = units_lnp.id WHERE subtopics_lnp.slug = ? AND subtopics_lnp.school_id = ? AND subtopics_lnp.class_id = ? AND subtopics_lnp.lesson_id = ?');

			if (!$stmt->execute(array($slug, $school, $class_id, $lesson_id))) {
				$stmt = null;
				exit();
			}
		}

		$lessonData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $lessonData;

		$stmt = null;
	}

	public function getOneSubTopic($slug)
	{

		$stmt = $this->connect()->prepare('SELECT * FROM subtopics_lnp WHERE slug = ?');

		if (!$stmt->execute(array($slug))) {
			$stmt = null;
			exit();
		}

		$lessonData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $lessonData;

		$stmt = null;
	}

	public function getSubTopicInfoByIds($topicId, $unitId, $lessonId, $classId)
	{

		$stmt = $this->connect()->prepare('SELECT * FROM subtopics_lnp WHERE topic_id = ? AND unit_id = ? AND lesson_id = ? AND class_id = ?');

		if (!$stmt->execute(array($topicId, $unitId, $lessonId, $classId))) {
			$stmt = null;
			exit();
		}

		$lessonData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $lessonData;

		$stmt = null;
	}

	// Get subtopics by unit ID with details

	public function getSubTopicsByUnitIdWithDetails($unitId)
	{

		$stmt = $this->connect()->prepare('SELECT subtopics_lnp.*, classes_lnp.name AS className, lessons_lnp.name AS lessonName, units_lnp.name AS unitName, topics_lnp.name AS topicName FROM subtopics_lnp INNER JOIN topics_lnp ON subtopics_lnp.topic_id = topics_lnp.id INNER JOIN classes_lnp ON topics_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON topics_lnp.lesson_id = lessons_lnp.id INNER JOIN units_lnp ON topics_lnp.unit_id = units_lnp.id WHERE subtopics_lnp.unit_id = ? ORDER BY subtopics_lnp.order_no ASC');

		if (!$stmt->execute(array($unitId))) {
			$stmt = null;
			exit();
		}

		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $getData;

		$stmt = null;
	}

	// Get subtopics by topic ID with details

	public function getSubTopicsByTopicIdWithDetails($topicId)
	{

		$stmt = $this->connect()->prepare('SELECT subtopics_lnp.*, classes_lnp.name AS className, lessons_lnp.name AS lessonName, units_lnp.name AS unitName, topics_lnp.name AS topicName FROM subtopics_lnp INNER JOIN topics_lnp ON subtopics_lnp.topic_id = topics_lnp.id INNER JOIN classes_lnp ON topics_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON topics_lnp.lesson_id = lessons_lnp.id INNER JOIN units_lnp ON topics_lnp.unit_id = units_lnp.id WHERE subtopics_lnp.topic_id = ?');

		if (!$stmt->execute(array($topicId))) {
			$stmt = null;
			exit();
		}

		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $getData;

		$stmt = null;
	}

	// Get contents by topic ID with details

	public function getContentsByTopicIdWithDetails($topicId)
	{

		$stmt = $this->connect()->prepare('SELECT school_content_lnp.*, classes_lnp.name AS className, lessons_lnp.name AS lessonName, units_lnp.name AS unitName, topics_lnp.name AS topicName FROM school_content_lnp INNER JOIN topics_lnp ON school_content_lnp.topic_id = topics_lnp.id INNER JOIN classes_lnp ON topics_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON topics_lnp.lesson_id = lessons_lnp.id INNER JOIN units_lnp ON topics_lnp.unit_id = units_lnp.id WHERE school_content_lnp.topic_id = ?');

		if (!$stmt->execute(array($topicId))) {
			$stmt = null;
			exit();
		}

		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $getData;

		$stmt = null;
	}

	// Get contents by content ID with details

	public function getContentsByContentIdWithDetails($contentId)
	{

		$stmt = $this->connect()->prepare('SELECT school_content_lnp.*, classes_lnp.name AS className, lessons_lnp.name AS lessonName, units_lnp.name AS unitName, topics_lnp.name AS topicName FROM school_content_lnp INNER JOIN topics_lnp ON school_content_lnp.topic_id = topics_lnp.id INNER JOIN classes_lnp ON topics_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON topics_lnp.lesson_id = lessons_lnp.id INNER JOIN units_lnp ON topics_lnp.unit_id = units_lnp.id WHERE school_content_lnp.subtopic_id = ?');

		if (!$stmt->execute(array($contentId))) {
			$stmt = null;
			exit();
		}

		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $getData;

		$stmt = null;
	}

	public function getSubTopicsByUnitId($unitId)
	{

		$stmt = $this->connect()->prepare('SELECT * FROM subtopics_lnp WHERE unit_id = ?');

		if (!$stmt->execute(array($unitId))) {
			$stmt = null;
			exit();
		}

		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $getData;

		$stmt = null;
	}

	public function getTopics()
	{

		$stmt = $this->connect()->prepare('SELECT * FROM topics_lnp');

		if (!$stmt->execute(array(0))) {
			$stmt = null;
			exit();
		}

		$lessonData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $lessonData;

		$stmt = null;
	}

	public function getTopicForSubTopicList($class, $lessons, $units)
	{
		if ($_SESSION['role'] == 1) {
			$stmt = $this->connect()->prepare('SELECT id, name FROM topics_lnp WHERE class_id = ? AND lesson_id=? AND unit_id = ?');

			if (!$stmt->execute(array($class, $lessons, $units))) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 2 or $_SESSION['role'] == 5 or $_SESSION['role'] == 3 or $_SESSION['role'] == 8 or $_SESSION['role'] == 4) {
			$school = $_SESSION['school_id'];
			$stmt = $this->connect()->prepare('SELECT id, name FROM topics_lnp WHERE class_id = ? AND lesson_id=? AND unit_id = ? AND school_id=?');

			if (!$stmt->execute(array($class, $lessons, $units, $school))) {
				$stmt = null;
				exit();
			}
		} else {
			return [];
		}

		$topicData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $topicData;

		$stmt = null;
	}

	public function getPrevSubTopicId($orderNo, $classId, $lessonId, $unit_id, $topic_id, $schoolId)
	{
		$stmt = $this->connect()->prepare('SELECT id FROM subtopics_lnp WHERE lesson_id = ? AND class_id = ? AND unit_id = ? AND topic_id = ? AND order_no = ? AND (school_id = ? OR school_id = ?) AND active = 1');

		if (!$stmt->execute(array($lessonId, $classId, $unit_id, $topic_id, $orderNo, $schoolId, "1"))) {
			$stmt = null;
			exit();
		}

		$topicData = $stmt->fetch(PDO::FETCH_ASSOC);

		return $topicData;

		$stmt = null;
	}


	public function getSubtopicForTopic($class, $lessons, $units, $topics)
	{
		if ($_SESSION['role'] == 1) {
			$stmt = $this->connect()->prepare('SELECT id, name FROM subtopics_lnp WHERE class_id = ? AND lesson_id = ? AND unit_id = ? AND topic_id = ?');

			if (!$stmt->execute(array($class, $lessons, $units, $topics))) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 2 or $_SESSION['role'] == 5 or $_SESSION['role'] == 3 or $_SESSION['role'] == 8 or $_SESSION['role'] == 4) {
			$school = $_SESSION['school_id'];
			$stmt = $this->connect()->prepare('SELECT id, name FROM subtopics_lnp WHERE class_id = ? AND lesson_id=? AND unit_id = ? AND topic_id = ? AND school_id=?');

			if (!$stmt->execute(array($class, $lessons, $units, $topics, $school))) {
				$stmt = null;
				exit();
			}
		} else{
			return [];
		}

		$subtopicData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $subtopicData;

		$stmt = null;
	}

	public function getTopicIdBySlug($slug)
	{

		$stmt = $this->connect()->prepare('SELECT * FROM topics_lnp WHERE slug = ?');

		if (!$stmt->execute(array($slug))) {
			$stmt = null;
			exit();
		}

		$topicData = $stmt->fetch(PDO::FETCH_ASSOC);

		return $topicData;

		$stmt = null;
	}

	public function getSubTopicIdBySlug($slug)
	{

		$stmt = $this->connect()->prepare('SELECT * FROM subtopics_lnp WHERE slug = ?');

		if (!$stmt->execute(array($slug))) {
			$stmt = null;
			exit();
		}

		$topicData = $stmt->fetch(PDO::FETCH_ASSOC);

		return $topicData;

		$stmt = null;
	}

	public function controlIsThereSubTopic($id, $classId)
	{

		$stmt = $this->connect()->prepare('SELECT * FROM subtopics_lnp WHERE topic_id = ? AND class_id = ?');

		if (!$stmt->execute(array($id, $classId))) {
			$stmt = null;
			exit();
		}

		$topicData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $topicData;

		$stmt = null;
	}


	public function showSubTopicsStudent($slug)
	{

		$stmt = $this->connect()->prepare('SELECT id FROM topics_lnp WHERE slug = ?');

		if (!$stmt->execute(array($slug))) {
			$stmt = null;
			exit();
		}

		$subtTopicData = $stmt->fetch(PDO::FETCH_ASSOC);

		$topicId = $subtTopicData['id'];

		$stmt2 = $this->connect()->prepare('SELECT subtopics_lnp.id AS topicID, subtopics_lnp.image AS topicImage, subtopics_lnp.short_desc AS topicShortDesc, subtopics_lnp.name AS topicName, subtopics_lnp.lesson_id AS lesson_id, subtopics_lnp.class_id AS class_id, subtopics_lnp.unit_id AS unit_id, subtopics_lnp.topic_id AS topic_id, subtopics_lnp.order_no AS order_no, subtopics_lnp.start_date AS start_date, subtopics_lnp.slug AS topicSlug, classes_lnp.name AS className, lessons_lnp.name AS lessonName, units_lnp.name AS unitName, subtopics_lnp.is_test AS is_test, subtopics_lnp.is_question AS is_question FROM subtopics_lnp INNER JOIN classes_lnp ON subtopics_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON subtopics_lnp.lesson_id = lessons_lnp.id INNER JOIN units_lnp ON subtopics_lnp.unit_id = units_lnp.id INNER JOIN topics_lnp ON subtopics_lnp.topic_id = topics_lnp.id WHERE subtopics_lnp.topic_id = ? AND subtopics_lnp.active = 1 ORDER BY subtopics_lnp.order_no ASC');

		if (!$stmt2->execute(array($topicId))) {
			$stmt2 = null;
			exit();
		}

		$subTopicsData = $stmt2->fetchAll(PDO::FETCH_ASSOC);

		return $subTopicsData;

		$stmt = null;
	}

	public function getYouTubeVideoId($url)
	{
		// Regular expression to match YouTube video ID
		$pattern = '/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';

		// Perform the regex match
		if (preg_match($pattern, $url, $matches)) {
			return $matches[1]; // The video ID is in the first capturing group
		}

		return null; // Return null if no match is found
	}


	public function getOneTest($id)
	{

		$stmt = $this->connect()->prepare('SELECT * FROM tests_lnp WHERE subtopic_id = ?');

		if (!$stmt->execute(array($id))) {
			$stmt = null;
			exit();
		}

		$testData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $testData;

		$stmt = null;
	}


	/*public function isSolved($id)
	{

		$stmt = $this->connect()->prepare('SELECT solvedtest_lnp.created_at AS created_at FROM solvedtest_lnp INNER JOIN tests_lnp ON tests_lnp.id = solvedtest_lnp.test_id WHERE tests_lnp.subtopic_id = ?');

		if (!$stmt->execute(array($id))) {
			$stmt = null;
			exit();
		}

		$testData = $stmt->fetch(PDO::FETCH_ASSOC);

		return $testData;

		$stmt = null;
	}*/ // Sonra Bak


	public function isSolvedUser($id, $userid)
	{

		$stmt = $this->connect()->prepare('SELECT solvedtest_lnp.created_at AS created_at FROM solvedtest_lnp INNER JOIN tests_lnp ON tests_lnp.id = solvedtest_lnp.test_id WHERE tests_lnp.subtopic_id = ? AND solvedtest_lnp.student_id = ?');

		if (!$stmt->execute(array($id, $userid))) {
			$stmt = null;
			exit();
		}

		$testData = $stmt->fetch(PDO::FETCH_ASSOC);

		return $testData;

		$stmt = null;
	}


	public function getOneQuestion($id)
	{

		$stmt = $this->connect()->prepare('SELECT * FROM s_questions_lnp WHERE topic_id = ?');

		if (!$stmt->execute(array($id))) {
			$stmt = null;
			exit();
		}

		$questionData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $questionData;

		$stmt = null;
	}


	/*public function isSolvedQuestion($id)
	{

		$stmt = $this->connect()->prepare('SELECT solved_s_questions_lnp.created_at AS created_at FROM solved_s_questions_lnp INNER JOIN s_questions_lnp ON s_questions_lnp.id = solved_s_questions_lnp.test_id WHERE s_questions_lnp.topic_id = ?');

		if (!$stmt->execute(array($id))) {
			$stmt = null;
			exit();
		}

		$testData = $stmt->fetch(PDO::FETCH_ASSOC);

		return $testData;

		$stmt = null;
	} */ // Buna da bak

	public function isSolvedQuestionUser($id, $userid)
	{

		$stmt = $this->connect()->prepare('SELECT solved_s_questions_lnp.created_at AS created_at FROM solved_s_questions_lnp INNER JOIN s_questions_lnp ON s_questions_lnp.id = solved_s_questions_lnp.test_id WHERE s_questions_lnp.topic_id = ? AND s_questions_lnp.student_id = ?');

		if (!$stmt->execute(array($id, $userid))) {
			$stmt = null;
			exit();
		}

		$testData = $stmt->fetch(PDO::FETCH_ASSOC);

		return $testData;

		$stmt = null;
	}



	public function getSameSubTopics($active_slug)
	{

		$stmt = $this->connect()->prepare('SELECT class_id, lesson_id, unit_id, topic_id FROM subtopics_lnp WHERE slug = ?');

		if (!$stmt->execute(array($active_slug))) {
			$stmt = null;
			exit();
		}

		$topicData = $stmt->fetch(PDO::FETCH_ASSOC);

		$class_id = $topicData['class_id'];
		$lesson_id = $topicData['lesson_id'];
		$unit_id = $topicData['unit_id'];
		$topic_id = $topicData['topic_id'];

		$stmt2 = $this->connect()->prepare('SELECT subtopics_lnp.id AS topicID, subtopics_lnp.name AS topicName, subtopics_lnp.lesson_id AS lesson_id, subtopics_lnp.class_id AS class_id, subtopics_lnp.unit_id AS unit_id, subtopics_lnp.topic_id AS topic_id, subtopics_lnp.order_no AS order_no, subtopics_lnp.start_date AS start_date, subtopics_lnp.slug AS topicSlug, classes_lnp.name AS className, lessons_lnp.name AS lessonName FROM subtopics_lnp INNER JOIN classes_lnp ON subtopics_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON subtopics_lnp.lesson_id = lessons_lnp.id INNER JOIN units_lnp ON subtopics_lnp.unit_id = units_lnp.id WHERE subtopics_lnp.class_id = ? AND subtopics_lnp.lesson_id = ? AND subtopics_lnp.unit_id = ? AND subtopics_lnp.topic_id = ? AND subtopics_lnp.slug != ?');

		if (!$stmt2->execute(array($class_id, $lesson_id, $unit_id, $topic_id, $active_slug))) {
			$stmt2 = null;
			exit();
		}

		$topicData2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

		return $topicData2;

		$stmt = null;
		$stmt2 = null;
	}
}


class Tests extends Dbh
{

	protected function getTestsList()
	{

		if ($_SESSION['role'] == 1) {
			$stmt = $this->connect()->prepare('SELECT tests_lnp.up_to AS testLastDay, subtopics_lnp.id AS topicID, subtopics_lnp.name AS subTopicName, subtopics_lnp.slug AS subTopicSlug, classes_lnp.name AS className, lessons_lnp.name AS lessonName, units_lnp.name AS unitName, topics_lnp.name AS topicName FROM subtopics_lnp INNER JOIN topics_lnp ON subtopics_lnp.topic_id = topics_lnp.id INNER JOIN classes_lnp ON topics_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON topics_lnp.lesson_id = lessons_lnp.id INNER JOIN units_lnp ON topics_lnp.unit_id = units_lnp.id INNER JOIN tests_lnp ON subtopics_lnp.id = tests_lnp.subtopic_id WHERE subtopics_lnp.is_test = ?');

			if (!$stmt->execute(array("1"))) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 2) {
			$school = $_SESSION['school_id'];
			$stmt = $this->connect()->prepare('SELECT tests_lnp.up_to AS testLastDay, topics_lnp.id AS topicID, topics_lnp.name AS topicName, topics_lnp.slug AS topicSlug, classes_lnp.name AS className, lessons_lnp.name AS lessonName, units_lnp.name AS unitName FROM topics_lnp INNER JOIN classes_lnp ON topics_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON topics_lnp.lesson_id = lessons_lnp.id INNER JOIN units_lnp ON topics_lnp.unit_id = units_lnp.id INNER JOIN tests_lnp ON subtopics_lnp.id = tests_lnp.subtopic_id WHERE units_lnp.school_id = ? AND subtopics_lnp.is_test = ?');

			if (!$stmt->execute(array($school, "1"))) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 3) {
			$school = $_SESSION['school_id'];
			$stmt = $this->connect()->prepare('SELECT tests_lnp.up_to AS testLastDay, topics_lnp.id AS topicID, topics_lnp.name AS topicName, topics_lnp.slug AS topicSlug, classes_lnp.name AS className, lessons_lnp.name AS lessonName, units_lnp.name AS unitName FROM topics_lnp INNER JOIN classes_lnp ON topics_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON topics_lnp.lesson_id = lessons_lnp.id INNER JOIN units_lnp ON topics_lnp.unit_id = units_lnp.id INNER JOIN tests_lnp ON subtopics_lnp.id = tests_lnp.subtopic_id WHERE units_lnp.school_id = ? AND subtopics_lnp.is_test = ?');

			if (!$stmt->execute(array($school, "1"))) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 4) {
			$school = $_SESSION['school_id'];
			$class_id = $_SESSION['class_id'];
			$lesson_id = $_SESSION['lesson_id'];
			$stmt = $this->connect()->prepare('SELECT tests_lnp.up_to AS testLastDay, topics_lnp.id AS topicID, topics_lnp.name AS topicName, topics_lnp.slug AS topicSlug, classes_lnp.name AS className, lessons_lnp.name AS lessonName, units_lnp.name AS unitName FROM topics_lnp INNER JOIN classes_lnp ON topics_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON topics_lnp.lesson_id = lessons_lnp.id INNER JOIN units_lnp ON topics_lnp.unit_id = units_lnp.id INNER JOIN tests_lnp ON subtopics_lnp.id = tests_lnp.subtopic_id WHERE units_lnp.school_id = ? AND  units_lnp.class_id = ? AND units_lnp.lesson_id = ? AND subtopics_lnp.is_test = ?');

			if (!$stmt->execute(array($school, $class_id, $lesson_id, "1"))) {
				$stmt = null;
				exit();
			}
		}

		$lessonData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $lessonData;

		$stmt = null;
	}

	public function getOneSubTopic($slug)
	{

		$stmt = $this->connect()->prepare('SELECT * FROM subtopics_lnp WHERE slug = ?');

		if (!$stmt->execute(array($slug))) {
			$stmt = null;
			exit();
		}

		$lessonData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $lessonData;

		$stmt = null;
	}

	public function getTests()
	{

		$stmt = $this->connect()->prepare('SELECT * FROM topics_lnp');

		if (!$stmt->execute(array(0))) {
			$stmt = null;
			exit();
		}

		$lessonData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $lessonData;

		$stmt = null;
	}

	public function getTestForSubTopicList($class, $lessons, $units)
	{
		if ($_SESSION['role'] == 1) {
			$stmt = $this->connect()->prepare('SELECT id, name FROM topics_lnp WHERE class_id = ? AND lesson_id=? AND unit_id = ?');

			if (!$stmt->execute(array($class, $lessons, $units))) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 3) {
			$school = $_SESSION['school_id'];
			$stmt = $this->connect()->prepare('SELECT id, name FROM topics_lnp WHERE class_id = ? AND lesson_id=? AND unit_id = ? AND school_id=?');

			if (!$stmt->execute(array($class, $lessons, $school, $units))) {
				$stmt = null;
				exit();
			}
		}

		$topicData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $topicData;

		$stmt = null;
	}


	/*public function getOneTest($id)
	{

		$stmt = $this->connect()->prepare('SELECT * FROM tests_lnp WHERE subtopic_id = ?');

		if (!$stmt->execute(array($id))) {
			$stmt = null;
			exit();
		}

		$testData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $testData;

		$stmt = null;
	}*/


	public function getOneTest($slug)
	{

		$stmt = $this->connect()->prepare('SELECT subtopics_lnp.id, subtopics_lnp.name, subtopics_lnp.short_desc, subtopics_lnp.class_id, subtopics_lnp.image, tests_lnp.correct AS correct, tests_lnp.questions AS questions, tests_lnp.answers AS answers FROM subtopics_lnp INNER JOIN tests_lnp ON subtopics_lnp.id = tests_lnp.subtopic_id WHERE subtopics_lnp.slug = ?');

		if (!$stmt->execute(array($slug))) {
			$stmt = null;
			exit();
		}

		$testData = $stmt->fetch(PDO::FETCH_ASSOC);

		return $testData;

		$stmt = null;
	}


	public function isSolved($id, $student_id)
	{

		$stmt = $this->connect()->prepare('SELECT solvedtest_lnp.created_at AS created_at, solvedtest_lnp.answers AS answers FROM solvedtest_lnp INNER JOIN tests_lnp ON tests_lnp.id = solvedtest_lnp.test_id WHERE tests_lnp.subtopic_id = ? AND solvedtest_lnp.student_id = ?');

		if (!$stmt->execute(array($id, $student_id))) {
			$stmt = null;
			exit();
		}

		$testData = $stmt->fetch(PDO::FETCH_ASSOC);

		return $testData;

		$stmt = null;
	}


	/*public function isSolved($id)
	{

		$stmt = $this->connect()->prepare('SELECT solvedtest_lnp.created_at AS created_at FROM solvedtest_lnp INNER JOIN tests_lnp ON tests_lnp.id = solvedtest_lnp.test_id WHERE tests_lnp.subtopic_id = ?');

		if (!$stmt->execute(array($id))) {
			$stmt = null;
			exit();
		}

		$testData = $stmt->fetch(PDO::FETCH_ASSOC);

		return $testData;

		$stmt = null;
	}*/


	public function getOneQuestion($id)
	{

		$stmt = $this->connect()->prepare('SELECT * FROM s_questions_lnp WHERE topic_id = ?');

		if (!$stmt->execute(array($id))) {
			$stmt = null;
			exit();
		}

		$questionData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $questionData;

		$stmt = null;
	}


	public function isSolvedQuestion($id)
	{

		$stmt = $this->connect()->prepare('SELECT solved_s_questions_lnp.created_at AS created_at FROM solved_s_questions_lnp INNER JOIN s_questions_lnp ON s_questions_lnp.id = solved_s_questions_lnp.test_id WHERE s_questions_lnp.topic_id = ?');

		if (!$stmt->execute(array($id))) {
			$stmt = null;
			exit();
		}

		$testData = $stmt->fetch(PDO::FETCH_ASSOC);

		return $testData;

		$stmt = null;
	}
}

class TestsforStudents extends Dbh
{

	protected function getTestsList($class_id)
	{

		if ($_SESSION['role'] == 1) {
			$stmt = $this->connect()->prepare('SELECT tests_lnp.end_date AS testLastDay, subtopics_lnp.id AS topicID, subtopics_lnp.name AS subTopicName, subtopics_lnp.slug AS subTopicSlug, classes_lnp.name AS className, lessons_lnp.name AS lessonName, units_lnp.name AS unitName, topics_lnp.name AS topicName FROM subtopics_lnp INNER JOIN topics_lnp ON subtopics_lnp.topic_id = topics_lnp.id INNER JOIN classes_lnp ON topics_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON topics_lnp.lesson_id = lessons_lnp.id INNER JOIN units_lnp ON topics_lnp.unit_id = units_lnp.id INNER JOIN tests_lnp ON subtopics_lnp.id = tests_lnp.subtopic_id WHERE subtopics_lnp.is_test = ?');

			if (!$stmt->execute(array("1"))) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 2) {
			//$school = $_SESSION['school_id'];
			$stmt = $this->connect()->prepare('SELECT tests_lnp.end_date AS testLastDay, subtopics_lnp.id AS topicID, subtopics_lnp.name AS subTopicName, subtopics_lnp.slug AS subTopicSlug, classes_lnp.name AS className, lessons_lnp.name AS lessonName, units_lnp.name AS unitName, topics_lnp.name AS topicName FROM subtopics_lnp INNER JOIN topics_lnp ON subtopics_lnp.topic_id = topics_lnp.id INNER JOIN classes_lnp ON topics_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON topics_lnp.lesson_id = lessons_lnp.id INNER JOIN units_lnp ON topics_lnp.unit_id = units_lnp.id INNER JOIN tests_lnp ON subtopics_lnp.id = tests_lnp.subtopic_id WHERE subtopics_lnp.is_test = ? AND subtopics_lnp.class_id = ?');

			if (!$stmt->execute(array("1", $class_id))) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 3) {
			$school = $_SESSION['school_id'];
			$stmt = $this->connect()->prepare('SELECT tests_lnp.end_date AS testLastDay, topics_lnp.id AS topicID, topics_lnp.name AS topicName, topics_lnp.slug AS topicSlug, classes_lnp.name AS className, lessons_lnp.name AS lessonName, units_lnp.name AS unitName FROM topics_lnp INNER JOIN classes_lnp ON topics_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON topics_lnp.lesson_id = lessons_lnp.id INNER JOIN units_lnp ON topics_lnp.unit_id = units_lnp.id INNER JOIN tests_lnp ON subtopics_lnp.id = tests_lnp.subtopic_id WHERE units_lnp.school_id = ? AND subtopics_lnp.is_test = ?');

			if (!$stmt->execute(array($school, "1"))) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 4) {
			$school = $_SESSION['school_id'];
			$class_id = $_SESSION['class_id'];
			$lesson_id = $_SESSION['lesson_id'];
			$stmt = $this->connect()->prepare('SELECT tests_lnp.up_to AS testLastDay, topics_lnp.id AS topicID, topics_lnp.name AS topicName, topics_lnp.slug AS topicSlug, classes_lnp.name AS className, lessons_lnp.name AS lessonName, units_lnp.name AS unitName FROM topics_lnp INNER JOIN classes_lnp ON topics_lnp.class_id = classes_lnp.id INNER JOIN lessons_lnp ON topics_lnp.lesson_id = lessons_lnp.id INNER JOIN units_lnp ON topics_lnp.unit_id = units_lnp.id INNER JOIN tests_lnp ON subtopics_lnp.id = tests_lnp.subtopic_id WHERE units_lnp.school_id = ? AND  units_lnp.class_id = ? AND units_lnp.lesson_id = ? AND subtopics_lnp.is_test = ?');

			if (!$stmt->execute(array($school, $class_id, $lesson_id, "1"))) {
				$stmt = null;
				exit();
			}
		}

		$lessonData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $lessonData;

		$stmt = null;
	}

	public function getOneSubTopic($slug)
	{

		$stmt = $this->connect()->prepare('SELECT * FROM subtopics_lnp WHERE slug = ?');

		if (!$stmt->execute(array($slug))) {
			$stmt = null;
			exit();
		}

		$lessonData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $lessonData;

		$stmt = null;
	}

	public function getTests()
	{

		$stmt = $this->connect()->prepare('SELECT * FROM topics_lnp');

		if (!$stmt->execute(array(0))) {
			$stmt = null;
			exit();
		}

		$lessonData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $lessonData;

		$stmt = null;
	}

	public function getTestForSubTopicList($class, $lessons, $units)
	{
		if ($_SESSION['role'] == 1) {
			$stmt = $this->connect()->prepare('SELECT id, name FROM topics_lnp WHERE class_id = ? AND lesson_id=? AND unit_id = ?');

			if (!$stmt->execute(array($class, $lessons, $units))) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 3) {
			$school = $_SESSION['school_id'];
			$stmt = $this->connect()->prepare('SELECT id, name FROM topics_lnp WHERE class_id = ? AND lesson_id=? AND unit_id = ? AND school_id=?');

			if (!$stmt->execute(array($class, $lessons, $school, $units))) {
				$stmt = null;
				exit();
			}
		}

		$topicData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $topicData;

		$stmt = null;
	}


	/*public function getOneTest($id)
	{

		$stmt = $this->connect()->prepare('SELECT * FROM tests_lnp WHERE subtopic_id = ?');

		if (!$stmt->execute(array($id))) {
			$stmt = null;
			exit();
		}

		$testData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $testData;

		$stmt = null;
	}*/


	public function getOneTest($slug)
	{

		$stmt = $this->connect()->prepare('SELECT subtopics_lnp.id, subtopics_lnp.name, subtopics_lnp.short_desc, subtopics_lnp.class_id, subtopics_lnp.image, tests_lnp.correct AS correct, tests_lnp.questions AS questions, tests_lnp.answers AS answers FROM subtopics_lnp INNER JOIN tests_lnp ON subtopics_lnp.id = tests_lnp.subtopic_id WHERE subtopics_lnp.slug = ?');

		if (!$stmt->execute(array($slug))) {
			$stmt = null;
			exit();
		}

		$testData = $stmt->fetch(PDO::FETCH_ASSOC);

		return $testData;

		$stmt = null;
	}


	public function isSolved($id, $student_id)
	{

		$stmt = $this->connect()->prepare('SELECT solvedtest_lnp.created_at AS created_at, solvedtest_lnp.answers AS answers FROM solvedtest_lnp INNER JOIN tests_lnp ON tests_lnp.id = solvedtest_lnp.test_id WHERE tests_lnp.subtopic_id = ? AND solvedtest_lnp.student_id = ?');

		if (!$stmt->execute(array($id, $student_id))) {
			$stmt = null;
			exit();
		}

		$testData = $stmt->fetch(PDO::FETCH_ASSOC);

		return $testData;

		$stmt = null;
	}


	/*public function isSolved($id)
	{

		$stmt = $this->connect()->prepare('SELECT solvedtest_lnp.created_at AS created_at FROM solvedtest_lnp INNER JOIN tests_lnp ON tests_lnp.id = solvedtest_lnp.test_id WHERE tests_lnp.subtopic_id = ?');

		if (!$stmt->execute(array($id))) {
			$stmt = null;
			exit();
		}

		$testData = $stmt->fetch(PDO::FETCH_ASSOC);

		return $testData;

		$stmt = null;
	}*/


	public function getOneQuestion($id)
	{

		$stmt = $this->connect()->prepare('SELECT * FROM s_questions_lnp WHERE topic_id = ?');

		if (!$stmt->execute(array($id))) {
			$stmt = null;
			exit();
		}

		$questionData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $questionData;

		$stmt = null;
	}


	public function isSolvedQuestion($id)
	{

		$stmt = $this->connect()->prepare('SELECT solved_s_questions_lnp.created_at AS created_at FROM solved_s_questions_lnp INNER JOIN s_questions_lnp ON s_questions_lnp.id = solved_s_questions_lnp.test_id WHERE s_questions_lnp.topic_id = ?');

		if (!$stmt->execute(array($id))) {
			$stmt = null;
			exit();
		}

		$testData = $stmt->fetch(PDO::FETCH_ASSOC);

		return $testData;

		$stmt = null;
	}
}
