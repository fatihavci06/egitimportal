<?php

class Classes extends Dbh
{
	public function getCoachStudents($coach_user_id)
	{
		$stmt = $this->connect()->prepare('SELECT u.id, u.name, u.surname FROM coaching_guidance_requests_lnp c INNER JOIN users_lnp u ON u.id = c.user_id WHERE c.teacher_id = ?');

            if (!$stmt->execute([$coach_user_id])) {
                $stmt = null;
               
                return [];
            }

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

	}
	public function privateLessonRemainingLimit($user_id)
	{
		$sql1 = "
        SELECT 
            IFNULL(SUM(ep.limit_count), 0) AS total_limit,
            (
                SELECT COUNT(*) 
                FROM private_lesson_requests_lnp 
                WHERE student_user_id = :user_id
            ) AS total_used,
            IFNULL(SUM(ep.limit_count), 0) - (
                SELECT COUNT(*) 
                FROM private_lesson_requests_lnp 
                WHERE student_user_id = :user_id
            ) AS remaining
        FROM extra_package_payments_lnp epp
        INNER JOIN extra_packages_lnp ep ON ep.id = epp.package_id
        WHERE epp.user_id = :user_id;
    ";

		$stmt1 = $this->connect()->prepare($sql1);
		$stmt1->bindParam(':user_id', $user_id, PDO::PARAM_INT);
		$stmt1->execute();
		$result1 = $stmt1->fetch(PDO::FETCH_ASSOC);
		return $result1['remaining'];
		
	}
	public function getExtraPackageMyList($id)
	{
		// 1. Özel Ders Bilgisi
		$sql1 = "
        SELECT 
            IFNULL(SUM(ep.limit_count), 0) AS total_limit,
            (
                SELECT COUNT(*) 
                FROM private_lesson_requests_lnp 
                WHERE student_user_id = :user_id
            ) AS total_used,
            IFNULL(SUM(ep.limit_count), 0) - (
                SELECT COUNT(*) 
                FROM private_lesson_requests_lnp 
                WHERE student_user_id = :user_id
            ) AS remaining
        FROM extra_package_payments_lnp epp
        INNER JOIN extra_packages_lnp ep ON ep.id = epp.package_id
        WHERE epp.user_id = :user_id;
    ";

		$stmt1 = $this->connect()->prepare($sql1);
		$stmt1->bindParam(':user_id', $id, PDO::PARAM_INT);
		$stmt1->execute();
		$result1 = $stmt1->fetch(PDO::FETCH_ASSOC);

		$data = [];

		$data[] = [
			'name' => '-',
			'type' => 'Özel Ders',
			'end_date' => $result1['remaining']
		];

		// 2. Koçluk/Rehberlik Paket Bilgileri
		$sql2 = "
        SELECT 
            ep.name, 
            ep.type, 
            IF(cgr.end_date IS NULL, '-', DATE_FORMAT(cgr.end_date, '%d-%m-%Y %H:%i')) AS end_date 
        FROM extra_package_payments_lnp epp 
        INNER JOIN extra_packages_lnp ep ON ep.id = epp.package_id 
        INNER JOIN coaching_guidance_requests_lnp cgr ON cgr.package_id = ep.id 
        WHERE ep.type IN ('Koçluk', 'Rehberlik') AND epp.user_id = :user_id2;
    ";

		$stmt2 = $this->connect()->prepare($sql2);
		$stmt2->bindParam(':user_id2', $id, PDO::PARAM_INT);
		$stmt2->execute();
		$result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

		foreach ($result2 as $row) {
			$data[] = [
				'name' => $row['name'],
				'type' => $row['type'],
				'end_date' => $row['end_date']
			];
		}

		return $data;
	}
	public function getTeacherList()
	{
		$stmt = $this->connect()->prepare('SELECT u.id as id, u.name as name,u.surname as surname,c.name as class_name ,l.name as lesson_name FROM `users_lnp` u left join classes_lnp c on u.class_id=c.id left JOIN lessons_lnp l on u.lesson_id=l.id where role in(4,9,10)
 ORDER BY id desc');

		if (!$stmt->execute(array())) {
			$stmt = null;
			exit();
		}

		$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $data;
	}
	public function getCoachingRequestById($requestId)
	{
		$sql = "SELECT 
            cg.*, 
            CONCAT(u.name, ' ', u.surname) AS user_full_name,
            CASE 
                WHEN cg.status = 0 THEN 'Atama Bekliyor'
                WHEN cg.status = 1 THEN 'Atandı'
                ELSE 'Bilinmiyor'
            END AS status_text,
            CASE 
                WHEN t.id IS NULL THEN '-'
                ELSE CONCAT(t.name, ' ', t.surname)
            END AS teacher_full_name
        FROM coaching_guidance_requests_lnp AS cg
        LEFT JOIN users_lnp AS u ON u.id = cg.user_id
        LEFT JOIN users_lnp AS t ON t.id = cg.teacher_id
        WHERE cg.id = :request_id;"; // Belirli bir ID'yi filtrelemek için WHERE eklendi

		$stmt = $this->connect()->prepare($sql);

		// Parametre bağlama
		// Güvenlik için :request_id placeholder'ına $requestId değerini bağlıyoruz.
		// PDO::PARAM_INT, request_id'nin bir tamsayı olduğunu belirtir.
		$stmt->bindParam(':request_id', $requestId, PDO::PARAM_INT);

		if (!$stmt->execute()) {
			// Hata durumunda loglama ve null döndürme
			error_log('Error executing coaching request by ID query. Request ID: ' . $requestId);
			$stmt = null;
			return null; // Tek bir öğe beklendiği için hata veya bulunamaması durumunda null döndürmek uygun olabilir.
		}

		// Tek bir satır beklediğimiz için fetch() kullanıyoruz.
		// PDO::FETCH_ASSOC, sütun isimlerini anahtar olarak içeren bir dizi döndürür.
		$result = $stmt->fetch(PDO::FETCH_ASSOC);

		$stmt = null; // Bellek yönetimi için statement objesini null'a atıyoruz.

		return $result; // Bulunan talep bilgilerini veya bulunamazsa/hata olursa null döndürür.
	}
	public function getCoachingRequestList($user_id = null)
	{

		$sql = "SELECT 
        cg.*, 
        CONCAT(u.name, ' ', u.surname) AS user_full_name,
        CASE 
            WHEN cg.status = 0 THEN 'Atama Bekliyor'
            WHEN cg.status = 1 THEN 'Atandı'
            ELSE 'Bilinmiyor'
        END AS status_text,
        CASE 
            WHEN t.id IS NULL THEN '-'
            ELSE CONCAT(t.name, ' ', t.surname)
        END AS teacher_full_name,
        ep.name as package_name
    FROM coaching_guidance_requests_lnp AS cg
    LEFT JOIN users_lnp AS u ON u.id = cg.user_id
    LEFT JOIN users_lnp AS t ON t.id = cg.teacher_id
    LEFT JOIN extra_packages_lnp AS ep ON ep.id = cg.package_id";

		// YENİ EKLENEN KISIM: user_id kontrolü ve WHERE koşulu
		if ($user_id !== null) {
			$sql .= " WHERE cg.user_id = :user_id";
		}

		$sql .= " ORDER BY cg.created_at DESC
              LIMIT 1000";

		$stmt = $this->connect()->prepare($sql);

		// YENİ EKLENEN KISIM: user_id varsa parametreyi bağla
		if ($user_id !== null) {
			$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
		}

		if (!$stmt->execute()) {
			error_log('Error executing coaching request list query.');
			$stmt = null;
			return [];
		}

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	public function getPrivateLessonRequestList($user_id = null)
	{
		$sql = 'SELECT
        pr.id,
        COALESCE(c.name, \'-\') AS class_name,
        COALESCE(l.name, \'-\') AS lesson_name,
        COALESCE(u.name, \'-\') AS unit_name,
        COALESCE(t.name, \'-\') AS topic_name,
        COALESCE(s.name, \'-\') AS subtopic_name,
        COALESCE(CONCAT(student.name, " ", student.surname), \'-\') AS student_full_name,
        COALESCE(CONCAT(teacher.name, " ", teacher.surname), \'-\') AS teacher_full_name,
		        COALESCE(DATE_FORMAT(pr.meet_date, \'%d-%m-%Y %H:%i\'), \'-\') AS meet_date, -- GÜNCELLENEN KISIM

        CASE
            WHEN pr.request_status = 0 THEN "Atama Bekliyor"
            WHEN pr.request_status = 1 THEN "Atandı"
            ELSE \'-\'
        END AS request_status_text
    FROM
        private_lesson_requests_lnp pr
    LEFT JOIN classes_lnp c ON c.id = pr.class_id
    LEFT JOIN lessons_lnp l ON l.id = pr.lesson_id
    LEFT JOIN units_lnp u ON u.id = pr.unit_id 
    LEFT JOIN topics_lnp t ON t.id = pr.topic_id
    LEFT JOIN subtopics_lnp s ON s.id = pr.subtopic_id
    LEFT JOIN users_lnp student ON student.id = pr.student_user_id
    LEFT JOIN users_lnp teacher ON teacher.id = pr.assigned_teacher_id';

		// YENİ EKLENEN KISIM: user_id kontrolü ve WHERE koşulu
		if ($user_id !== null) {
			$sql .= " WHERE pr.student_user_id = :user_id";
		}

		$sql .= ' ORDER BY pr.created_at DESC
              LIMIT 1000';

		$stmt = $this->connect()->prepare($sql);

		// YENİ EKLENEN KISIM: user_id varsa parametreyi bağla
		if ($user_id !== null) {
			$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
		}

		if (!$stmt->execute()) {
			error_log('Error executing private lesson request query.');
			$stmt = null;
			return [];
		}

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	public function getPrivateLessonRequestById($id)
	{
		$sql = 'SELECT
        pr.id,
		pr.time_slot,
		pr.meet_date,
		pr.assigned_teacher_id,
        COALESCE(c.name, \'-\') AS class_name,
        COALESCE(l.name, \'-\') AS lesson_name,
        COALESCE(u.name, \'-\') AS unit_name,
        COALESCE(t.name, \'-\') AS topic_name,
        COALESCE(s.name, \'-\') AS subtopic_name,
        COALESCE(CONCAT(student.name, " ", student.surname), \'-\') AS student_full_name,
        COALESCE(CONCAT(teacher.name, " ", teacher.surname), \'-\') AS teacher_full_name,
        CASE
            WHEN pr.request_status = 0 THEN "Atama Bekliyor"
            WHEN pr.request_status = 1 THEN "Atandı"
            ELSE \'-\'
        END AS request_status_text
    FROM
        private_lesson_requests_lnp pr
    LEFT JOIN classes_lnp c ON c.id = pr.class_id
    LEFT JOIN lessons_lnp l ON l.id = pr.lesson_id
    LEFT JOIN units_lnp u ON u.id = pr.unit_id 
    LEFT JOIN topics_lnp t ON t.id = pr.topic_id
    LEFT JOIN subtopics_lnp s ON s.id = pr.subtopic_id
    LEFT JOIN users_lnp student ON student.id = pr.student_user_id
    LEFT JOIN users_lnp teacher ON teacher.id = pr.assigned_teacher_id
    WHERE pr.id = ?';

		$stmt = $this->connect()->prepare($sql);

		if (!$stmt->execute([$id])) {
			error_log('Error executing private lesson request by ID query.');
			$stmt = null;
			return [];
		}

		return $stmt->fetch(PDO::FETCH_ASSOC); // tek kayıt
	}
	public function getTeachers()
	{
		$stmt = $this->connect()->prepare('SELECT * FROM users_lnp WHERE school_id = 1 AND role IN (4, 9, 10) ORDER BY id DESC');


		if (!$stmt->execute(array())) {
			$stmt = null;
			exit();
		}

		$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $data;
	}


	public function getExtraPackageList()
	{
		$stmt = $this->connect()->prepare('SELECT *FROM extra_packages_lnp where school_id=1 ORDER BY id desc');

		if (!$stmt->execute(array())) {
			$stmt = null;
			exit();
		}

		$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $data;
	}
	public function getTestDetail($testId, $classId = 0)
	{
		$role = $_SESSION['role'];
		if ($role == 1 || $role == 3 || $role == 4) {

			$sql = "
                SELECT 
                    t.id AS test_id,
                    t.test_title,
                    t.school_id,
                    t.teacher_id,
                    t.cover_img,
                    t.class_id,
                    t.lesson_id,
                    t.unit_id,
                    t.topic_id,
                    t.subtopic_id,
                    t.start_date,
                    t.end_date,
                    t.created_at AS test_created_at,
                    t.updated_at AS test_updated_at,

                    tq.id AS question_id,
                    tq.question_text,
                    tq.correct_answer,
                    tq.created_at AS question_created_at,
                    tq.updated_at AS question_updated_at,

                    tqv.video_url,

                    tqf.file_path AS question_file_path,

                    tqo.id AS option_id,
                    tqo.option_key,
                    tqo.option_text,
                    tqo.created_at AS option_created_at,
                    tqo.updated_at AS option_updated_at,

                    tqof.file_path AS option_file_path

                FROM tests_lnp t
                LEFT JOIN test_questions_lnp tq ON tq.test_id = t.id
                LEFT JOIN test_question_videos_lnp tqv ON tqv.question_id = tq.id
                LEFT JOIN test_question_files_lnp tqf ON tqf.question_id = tq.id
                LEFT JOIN test_question_options_lnp tqo ON tqo.question_id = tq.id
                LEFT JOIN test_question_option_files_lnp tqof ON tqof.option_id = tqo.id
                WHERE t.id = :id";

			$stmt = $this->connect()->prepare($sql);
			$stmt->execute(['id' => $testId ?? null]);
			if ($stmt->rowCount() === 0) {
				echo json_encode(['status' => 'error', 'message' => 'Test bulunamadı.']);
				exit;
			}
			$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$response = null;

			foreach ($rows as $row) {
				if (!$response) {
					$response = [
						'id' => $row['test_id'],
						'test_title' => $row['test_title'],
						'school_id' => $row['school_id'],
						'teacher_id' => $row['teacher_id'],
						'cover_img' => $row['cover_img'],
						'class_id' => $row['class_id'],
						'lesson_id' => $row['lesson_id'],
						'unit_id' => $row['unit_id'],
						'topic_id' => $row['topic_id'],
						'subtopic_id' => $row['subtopic_id'],
						'start_date' => $row['start_date'],
						'end_date' => $row['end_date'],
						'created_at' => $row['test_created_at'],
						'updated_at' => $row['test_updated_at'],
						'questions' => [],
					];
				}

				$questionId = $row['question_id'];
				$optionId = $row['option_id'];

				if ($questionId && !isset($response['questions'][$questionId])) {
					$response['questions'][$questionId] = [
						'id' => $questionId,
						// HTML etiketlerini kaldırıyoruz
						'question_text' => strip_tags($row['question_text']),
						'correct_answer' => $row['correct_answer'],
						'created_at' => $row['question_created_at'],
						'updated_at' => $row['question_updated_at'],
						'videos' => [],
						'files' => [],
						'options' => [],
					];
				}

				// Video ekle
				if (!empty($row['video_url']) && !in_array($row['video_url'], $response['questions'][$questionId]['videos'])) {
					$response['questions'][$questionId]['videos'][] = $row['video_url'];
				}

				// Soru dosyası ekle
				if (!empty($row['question_file_path']) && !in_array($row['question_file_path'], $response['questions'][$questionId]['files'])) {
					$response['questions'][$questionId]['files'][] = $row['question_file_path'];
				}

				// Seçenek ekle
				if ($optionId && !isset($response['questions'][$questionId]['options'][$optionId])) {
					$response['questions'][$questionId]['options'][$optionId] = [
						'id' => $optionId,
						'option_key' => $row['option_key'],
						// HTML etiketlerini kaldırıyoruz
						'option_text' => strip_tags($row['option_text']),
						'created_at' => $row['option_created_at'],
						'updated_at' => $row['option_updated_at'],
						'files' => [],
					];
				}

				// Seçenek dosyası ekle
				if (!empty($row['option_file_path']) && !in_array($row['option_file_path'], $response['questions'][$questionId]['options'][$optionId]['files'])) {
					$response['questions'][$questionId]['options'][$optionId]['files'][] = $row['option_file_path'];
				}
			}

			// Final formatlama
			if ($response) {

				$response['questions'] = array_values(array_map(function ($question) {

					$question['options'] = array_values($question['options']);
					return $question;
				}, $response['questions']));
				echo json_encode(['status' => 'success', 'data' => $response]);
			} else {
				echo json_encode(['status' => 'error', 'message' => 'Ders bulunamadı.']);
			}
		} else {
			// Öğretmen veya yönetici için tüm sınıflar

		}

		$classData = $stmt->fetch(PDO::FETCH_ASSOC);

		return $classData;
	}
	public function getTestListByStudent($classId = 0)
	{
		$role = $_SESSION['role'];
		if ($role == 1 or $role == 3) {

			// Öğretmen veya yönetici için tüm sınıflar
			$stmt = $this->connect()->prepare('SELECT id,test_title,end_date FROM tests_lnp ORDER BY id DESC');
			if (!$stmt->execute()) {
				$stmt = null;
				exit();
			}
		} elseif ($role == 4) {
			$stmt = $this->connect()->prepare('SELECT id,test_title,end_date FROM tests_lnp WHERE teacher_id = ? ORDER BY id DESC');
			if (!$stmt->execute([$_SESSION['id']])) {
				$stmt = null;
				exit();
			}
		} else {

			$userId = $_SESSION['id']; // Session'dan user_id'yi al

			$stmt = $this->connect()->prepare('
				SELECT 
					t.id, 
					t.test_title, 
					t.end_date, 
					ug.user_id, 
					ug.score, 
					ug.fail_count,
					CASE 
						WHEN ug.score >= 80 THEN 1 
						ELSE 0 
					END AS user_test_status 
				FROM tests_lnp t 
				LEFT JOIN user_grades_lnp ug ON ug.test_id = t.id AND ug.user_id = :user_id
				WHERE t.class_id = :class_id AND t.status = 1 
				ORDER BY t.id DESC
			');

			// Parametreleri geç
			if (!$stmt->execute(['class_id' => $classId, 'user_id' => $userId])) {
				$stmt = null;
				exit();
			}
		}



		$classData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $classData;
	}
	public function getClassesList()
	{

		$stmt = $this->connect()->prepare('SELECT id, name, slug FROM classes_lnp where class_type=0 ORDER BY orderBY ASC');

		if (!$stmt->execute(array())) {
			$stmt = null;
			exit();
		}

		$classData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $classData;
	}
	public function getTestById($id)
	{

		$sql = "
SELECT 
    t.id AS test_id,
    t.test_title,
    t.school_id,
    t.teacher_id,
    t.cover_img,
    t.class_id,
    t.lesson_id,
    t.unit_id,
    t.topic_id,
    t.subtopic_id,
    t.start_date,
    t.end_date,
    t.created_at AS test_created_at,
    t.updated_at AS test_updated_at,

    tq.id AS question_id,
    tq.question_text,
    tq.correct_answer,
    tq.created_at AS question_created_at,
    tq.updated_at AS question_updated_at,

    tqv.video_url,

    tqf.file_path AS question_file_path,

    tqo.id AS option_id,
    tqo.option_key,
    tqo.option_text,
    tqo.created_at AS option_created_at,
    tqo.updated_at AS option_updated_at,

    tqof.file_path AS option_file_path

FROM tests_lnp t
LEFT JOIN test_questions_lnp tq ON tq.test_id = t.id
LEFT JOIN test_question_videos_lnp tqv ON tqv.question_id = tq.id
LEFT JOIN test_question_files_lnp tqf ON tqf.question_id = tq.id
LEFT JOIN test_question_options_lnp tqo ON tqo.question_id = tq.id
LEFT JOIN test_question_option_files_lnp tqof ON tqof.option_id = tqo.id
WHERE t.id = :id";

		$stmt = $this->connect()->prepare($sql);
		$stmt->execute(['id' => $id]);
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$response = null;

		foreach ($rows as $row) {
			if (!$response) {
				$response = [
					'id' => $row['test_id'],
					'test_title' => $row['test_title'],
					'school_id' => $row['school_id'],
					'teacher_id' => $row['teacher_id'],
					'cover_img' => $row['cover_img'],
					'class_id' => $row['class_id'],
					'lesson_id' => $row['lesson_id'],
					'unit_id' => $row['unit_id'],
					'topic_id' => $row['topic_id'],
					'subtopic_id' => $row['subtopic_id'],
					'start_date' => $row['start_date'],
					'end_date' => $row['end_date'],
					'created_at' => $row['test_created_at'],
					'updated_at' => $row['test_updated_at'],
					'questions' => [],
				];
			}

			$questionId = $row['question_id'];
			$optionId = $row['option_id'];

			if ($questionId && !isset($response['questions'][$questionId])) {
				$response['questions'][$questionId] = [
					'id' => $questionId,
					'question_text' => $row['question_text'],
					'correct_answer' => $row['correct_answer'],
					'created_at' => $row['question_created_at'],
					'updated_at' => $row['question_updated_at'],
					'videos' => [],
					'files' => [],
					'options' => [],
				];
			}

			// Video ekle
			if (!empty($row['video_url']) && !in_array($row['video_url'], $response['questions'][$questionId]['videos'])) {
				$response['questions'][$questionId]['videos'][] = $row['video_url'];
			}

			// Soru dosyası ekle
			if (!empty($row['question_file_path']) && !in_array($row['question_file_path'], $response['questions'][$questionId]['files'])) {
				$response['questions'][$questionId]['files'][] = $row['question_file_path'];
			}

			// Seçenek ekle
			if ($optionId && !isset($response['questions'][$questionId]['options'][$optionId])) {
				$response['questions'][$questionId]['options'][$optionId] = [
					'id' => $optionId,
					'option_key' => $row['option_key'],
					'option_text' => $row['option_text'],
					'created_at' => $row['option_created_at'],
					'updated_at' => $row['option_updated_at'],
					'files' => [],
				];
			}

			// Seçenek dosyası ekle
			if (!empty($row['option_file_path']) && !in_array($row['option_file_path'], $response['questions'][$questionId]['options'][$optionId]['files'])) {
				$response['questions'][$questionId]['options'][$optionId]['files'][] = $row['option_file_path'];
			}
		}

		// Final formatlama
		if ($response) {
			$response['questions'] = array_values(array_map(function ($question) {
				$question['options'] = array_values($question['options']);
				return $question;
			}, $response['questions']));
		}

		return json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
		exit();
	}




	protected function getClassesListForCreateAccount()
	{

		$stmt = $this->connect()->prepare('SELECT id, name, slug FROM classes_lnp ORDER BY orderBY ASC');

		if (!$stmt->execute(array())) {
			$stmt = null;
			exit();
		}

		$classData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $classData;
	}

	public function getMainSchoolClassesList()
	{

		$stmt = $this->connect()->prepare('SELECT id, name, slug FROM classes_lnp where school_id = 1 and class_type=1');

		if (!$stmt->execute(array())) {
			$stmt = null;
			exit();
		}

		$classData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $classData;
	}
	public function getMainSchoolLessonList()
	{

		$stmt = $this->connect()->prepare('SELECT * FROM main_school_lessons_lnp  ');

		if (!$stmt->execute(array())) {
			$stmt = null;
			exit();
		}

		$classData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $classData;
	}
	public function getMainSchoolTopicList()
	{

		$stmt = $this->connect()->prepare('SELECT t.id id, u.name unit_name,l.name lesson_name,t.name topic_name FROM main_school_topics_lnp t
		inner JOIN main_school_units_lnp u on t.unit_id=u.id
		inner JOIN main_school_lessons_lnp l on u.lesson_id=l.id;  ');

		if (!$stmt->execute(array())) {
			$stmt = null;
			exit();
		}

		$classData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $classData;
	}
	public function getMainSchoolUnitList()
	{

		$stmt = $this->connect()->prepare('SELECT mu.name as unit_name, mu.id as id, ml.name as lesson_name FROM `main_school_units_lnp` mu inner JOIN main_school_lessons_lnp ml on ml.id=mu.lesson_id; ');

		if (!$stmt->execute(array())) {
			$stmt = null;
			exit();
		}

		$classData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $classData;
	}
	public function getClassId($userId)
	{
		$stmt = $this->connect()->prepare('
            SELECT class_id
            FROM users_lnp 
            WHERE  id = :id
        ');

		if (!$stmt->execute(['id' => $userId])) {
			$stmt = null;
			exit();
		}
		$classData = $stmt->fetch(PDO::FETCH_ASSOC);

		return $classData['class_id'];
	}
	public function getSettings()
	{

		$stmt = $this->connect()->prepare('SELECT * from settings_lnp where school_id=1');

		if (!$stmt->execute(array())) {
			$stmt = null;
			exit();
		}

		$data = $stmt->fetch(PDO::FETCH_ASSOC);

		return $data;
	}
	public function getSMSApiSettings()
	{

		$stmt = $this->connect()->prepare('SELECT * from sms_settings_lnp limit 1');

		if (!$stmt->execute(array())) {
			$stmt = null;
			exit();
		}

		$data = $stmt->fetch(PDO::FETCH_ASSOC);

		return $data;
	}
	public function getAgeGroup($class_id = null)
	{

		if ($class_id !== null) {
			$stmt = $this->connect()->prepare('
            SELECT id, name, slug 
            FROM classes_lnp 
            WHERE class_type = 1 AND school_id = 1 AND id = :id
        ');

			if (!$stmt->execute(['id' => $class_id])) {
				$stmt = null;
				exit();
			}
		} else {
			$stmt = $this->connect()->prepare('
            SELECT id, name, slug 
            FROM classes_lnp 
            WHERE class_type = 1 AND school_id = 1
        ');

			if (!$stmt->execute()) {
				$stmt = null;
				exit();
			}
		}

		$classData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $classData;
	}
	public function permissionControl($contentId, $userId)
	{
		$stmt = $this->connect()->prepare('
        SELECT 1
        FROM users_lnp u
        WHERE u.id = ?
        AND EXISTS (
            SELECT 1
            FROM main_school_content_lnp mc
            WHERE mc.main_school_class_id = u.class_id
            AND mc.id = ?
        )
    ');

		if (!$stmt->execute([$userId, $contentId])) {
			$stmt = null;
			return false;
		}

		// Sonuç varsa true, yoksa false döndür
		return $stmt->fetch() ? true : false;
	}

	public function getWeekList()
	{

		$stmt = $this->connect()->prepare('SELECT id, name, slug FROM important_weeks_lnp where school_id = 1');

		if (!$stmt->execute(array())) {
			$stmt = null;
			exit();
		}

		$classData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $classData;
	}
	public function getStudentList()
	{

		$stmt = $this->connect()->prepare('SELECT id,  CONCAT(name, " ", surname) as fullname FROM users_lnp where school_id = 1 and role=2');

		if (!$stmt->execute(array())) {
			$stmt = null;
			exit();
		}

		$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $data;
	}


	public function getCategoryTitleList()
	{

		$stmt = $this->connect()->prepare('SELECT * FROM category_titles_lnp where school_id = 1');

		if (!$stmt->execute(array())) {
			$stmt = null;
			exit();
		}

		$classData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $classData;
	}
	public function getMainSchoolContentList()
	{

		$stmt = $this->connect()->prepare('SELECT * FROM main_school_content_lnp where school_id = 1 ORDER BY id DESC');

		if (!$stmt->execute(array())) {
			$stmt = null;
			exit();
		}

		$classData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $classData;
	}
	public function getRoles()
	{

		$stmt = $this->connect()->prepare('SELECT * FROM userroles_lnp ');

		if (!$stmt->execute(array())) {
			$stmt = null;
			exit();
		}

		$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $data;
	}
	public function getTitleList($type = 1)
	{
		$stmt = $this->connect()->prepare('SELECT * FROM category_titles_lnp WHERE type = ?');

		if (!$stmt->execute([$type])) {
			$stmt = null;
			exit();
		}

		$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $data;
	}
	public function getMainSchoolContentById($id)
	{
		// İçerik verisini al
		$stmt = $this->connect()->prepare('SELECT * FROM main_school_content_lnp WHERE id = ?');
		if (!$stmt->execute([$id])) {
			return null;
		}

		$classData = $stmt->fetch(PDO::FETCH_ASSOC);


		$stmt = $this->connect()->prepare('SELECT * FROM mainschool_content_file_lnp WHERE main_id = ?');
		if (!$stmt->execute([$id])) {
			return null;
		}

		$files = $stmt->fetchAll(PDO::FETCH_ASSOC);

		// İçeriğe dosya listesini ekle
		$classData['files'] = $files;
		$stmt = $this->connect()->prepare('SELECT * FROM main_school_primary_images WHERE main_id = ?');
		if (!$stmt->execute([$id])) {
			return null;
		}

		$images = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$classData['images'] = $images;


		$stmt = $this->connect()->prepare('SELECT * FROM mainschool_wordwall_lnp WHERE main_id = ?');
		if (!$stmt->execute([$id])) {
			return null;
		}

		$wordwalls = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$classData['wordwalls'] = $wordwalls;

		return $classData;
	}


	public function getOneClass($slug)
	{

		$stmt = $this->connect()->prepare('SELECT * FROM classes_lnp WHERE slug = ?');

		if (!$stmt->execute(array($slug))) {
			$stmt = null;
			exit();
		}

		$classData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $classData;
	}

	public function getClasses()
	{

		$stmt = $this->connect()->prepare('SELECT * FROM classes_lnp');

		if (!$stmt->execute(array())) {
			$stmt = null;
			exit();
		}

		$classData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $classData;
	}

	public function getClassByLesson($classValue)
	{

		$stmt = $this->connect()->prepare('SELECT id ,name FROM classes_lnp WHERE id = ?');

		if (!$stmt->execute(array($classValue))) {
			$stmt = null;
			exit();
		}

		$classData = $stmt->fetch(PDO::FETCH_ASSOC);

		return $classData;
	}
}
