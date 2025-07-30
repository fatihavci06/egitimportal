<?php


class Student extends Dbh
{

	public function getUserById($id)
	{
		$stmt = $this->connect()->prepare('
            SELECT 
                users_lnp.*,
                schools_lnp.name AS schoolName,
                classes_lnp.id AS classId,
                classes_lnp.name AS className,
                classes_lnp.slug AS classSlug,
                lessons_lnp.id AS lessonsId,
                lessons_lnp.name AS lessonName,
                lessons_lnp.slug AS lessonSlug
            FROM users_lnp 
            LEFT JOIN schools_lnp ON users_lnp.school_id = schools_lnp.id
            LEFT JOIN classes_lnp ON users_lnp.class_id = classes_lnp.id
            LEFT JOIN lessons_lnp ON users_lnp.lesson_id = lessons_lnp.id
            WHERE users_lnp.id = ?
		');

		if (!$stmt->execute([$id])) {
			$stmt = null;
			exit();
		}

		$getData = $stmt->fetch(PDO::FETCH_ASSOC);

		return $getData;
	}
	public function getWaitingMoneyTransfers()
	{
		$stmt = $this->connect()->prepare('SELECT user_id FROM money_transfer_list_lnp WHERE status = ?');
		if (!$stmt->execute(array("0"))) {
			$stmt = null;
			exit();
		}
		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $getData;
		$stmt = null;
	}

	protected function getWaitingMoneyStudent()
	{

		$stmt = $this->connect()->prepare('SELECT money_transfer_list_lnp.id, money_transfer_list_lnp.pack_id, money_transfer_list_lnp.user_id, money_transfer_list_lnp.amount, users_lnp.name, users_lnp.surname, users_lnp.identity_id, users_lnp.photo, users_lnp.email, users_lnp.telephone FROM money_transfer_list_lnp INNER JOIN users_lnp ON money_transfer_list_lnp.user_id = users_lnp.id WHERE money_transfer_list_lnp.status = ?');

		if (!$stmt->execute(array(0))) {
			$stmt = null;
			exit();
		}

		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $getData;

		$stmt = null;
	}

	public function getMoneyTransferInfo($id)
	{
		$stmt = $this->connect()->prepare('SELECT * FROM money_transfer_list_lnp WHERE id = ?');

		if (!$stmt->execute(array($id))) {
			$stmt = null;
			exit();
		}

		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $getData;

		$stmt = null;
	}

	protected function getparentName($child_id)
	{

		$stmt = $this->connect()->prepare('SELECT name, surname FROM users_lnp WHERE child_id = ?');

		if (!$stmt->execute(array($child_id))) {
			$stmt = null;
			exit();
		}

		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $getData;

		$stmt = null;
	}

	public function getPackName($pack_id)
	{

		$stmt = $this->connect()->prepare('SELECT name, class_id, subscription_period FROM packages_lnp WHERE id = ?');

		if (!$stmt->execute(array($pack_id))) {
			$stmt = null;
			exit();
		}

		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $getData;

		$stmt = null;
	}

	protected function getAllClasses()
	{

		$stmt = $this->connect()->prepare('SELECT * FROM classes_lnp ORDER BY orderBy ASC');

		if (!$stmt->execute(array())) {
			$stmt = null;
			exit();
		}

		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $getData;

		$stmt = null;
	}

	protected function getAllUnits()
	{

		$stmt = $this->connect()->prepare('SELECT * FROM units_lnp ORDER BY order_no ASC');

		if (!$stmt->execute(array())) {
			$stmt = null;
			exit();
		}

		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $getData;

		$stmt = null;
	}

	protected function getAllTopics()
	{

		$stmt = $this->connect()->prepare('SELECT * FROM topics_lnp ORDER BY order_no ASC');

		if (!$stmt->execute(array())) {
			$stmt = null;
			exit();
		}

		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $getData;

		$stmt = null;
	}

	protected function getAllLessons()
	{

		$stmt = $this->connect()->prepare('SELECT * FROM lessons_lnp ORDER BY name ASC');

		if (!$stmt->execute(array())) {
			$stmt = null;
			exit();
		}

		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $getData;

		$stmt = null;
	}

	protected function getClassName($class_id)
	{

		$stmt = $this->connect()->prepare('SELECT name FROM classes_lnp WHERE id = ?');

		if (!$stmt->execute(array($class_id))) {
			$stmt = null;
			exit();
		}

		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $getData;

		$stmt = null;
	}

	public function getActiveStudents()
	{
		$stmt = $this->connect()->prepare('SELECT * FROM users_lnp WHERE active = ? AND (role = ? OR role = ?)');
		if (!$stmt->execute(array(1, 2, 10002))) {
			$stmt = null;
			exit();
		}
		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $getData;
		$stmt = null;
	}

	public function getPassiveStudents()
	{
		$stmt = $this->connect()->prepare('SELECT * FROM users_lnp WHERE active = ? AND (role = ? OR role = ?)');
		if (!$stmt->execute(array(0, 2, 10002))) {
			$stmt = null;
			exit();
		}
		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $getData;
		$stmt = null;
	}

	public function getStudentsList()
	{

		if ($_SESSION['role'] == 1) {
			$stmt = $this->connect()->prepare('SELECT users_lnp.*, classes_lnp.name AS className, classes_lnp.slug AS classSlug, users_lnp.active AS userActive, schools_lnp.name AS schoolName FROM users_lnp INNER JOIN classes_lnp ON users_lnp.class_id = classes_lnp.id INNER JOIN schools_lnp ON users_lnp.school_id = schools_lnp.id WHERE (users_lnp.active = ? OR users_lnp.active = ?) AND (users_lnp.role = ? OR users_lnp.role = ?) ORDER BY users_lnp.id DESC');

			if (!$stmt->execute(array("1", "0", "2", "10002"))) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 3) {
			$school = $_SESSION['school_id'];
			$stmt = $this->connect()->prepare('SELECT users_lnp.*, classes_lnp.name AS className, classes_lnp.slug AS classSlug, users_lnp.active AS userActive, schools_lnp.name AS schoolName FROM users_lnp INNER JOIN classes_lnp ON users_lnp.class_id = classes_lnp.id INNER JOIN schools_lnp ON users_lnp.school_id = schools_lnp.id WHERE users_lnp.active = ? AND users_lnp.role = ? AND users_lnp.school_id = ?');

			if (!$stmt->execute(array("1", "2", $school))) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 4) {
			$school = $_SESSION['school_id'];
			$class_id = $_SESSION['class_id'];
			$stmt = $this->connect()->prepare('SELECT users_lnp.*, classes_lnp.name AS className, classes_lnp.slug AS classSlug, users_lnp.active AS userActive, schools_lnp.name AS schoolName FROM users_lnp INNER JOIN classes_lnp ON users_lnp.class_id = classes_lnp.id INNER JOIN schools_lnp ON users_lnp.school_id = schools_lnp.id WHERE users_lnp.active = ? AND users_lnp.role = ? AND users_lnp.school_id = ? AND users_lnp.class_id = ?');

			if (!$stmt->execute(array("1", "2", $school, $class_id))) {
				$stmt = null;
				exit();
			}
		} else {
			return null;

		}


		$schoolData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $schoolData;

		$stmt = null;
	}


	public function getStudentId($slug)
	{
		$stmt = $this->connect()->prepare('SELECT * FROM users_lnp WHERE username = ?');

		if (!$stmt->execute(array($slug))) {
			$stmt = null;
			exit();
		}

		$studentId = $stmt->fetch(PDO::FETCH_ASSOC);
		return $studentId['id'];
		$stmt = null;
	}

	public function getStudentIdForTeacher($slug, $school_id, $class_id)
	{
		$stmt = $this->connect()->prepare('SELECT * FROM users_lnp WHERE username = ? AND school_id = ? AND class_id = ?');

		if (!$stmt->execute(array($slug, $school_id, $class_id))) {
			$stmt = null;
			exit();
		}

		$studentId = $stmt->fetch(PDO::FETCH_ASSOC);
		return $studentId['id'];
		$stmt = null;
	}

	public function getStudentIdForParent($slug, $userID)
	{
		$getChild = $this->connect()->prepare('SELECT 
        child_id FROM users_lnp
        WHERE id = :id
        ');

		if (!$getChild->execute([':id' => $userID])) {
			$getChild = null;
			exit();
		}

		$child = $getChild->fetch(PDO::FETCH_ASSOC);

		if (empty($child))
			return [];

		$stmt = $this->connect()->prepare('SELECT id FROM users_lnp WHERE username = ? AND id = ?');

		if (!$stmt->execute([$slug, $child['child_id']])) {
			$stmt = null;
			exit();
		}

		$studentId = $stmt->fetch(PDO::FETCH_ASSOC);
		return $studentId['id'];
		$stmt = null;
	}

	public function getStudentIdForCoordinator($slug, $school_id)
	{
		$stmt = $this->connect()->prepare('SELECT * FROM users_lnp WHERE username = ? AND school_id = ?');

		if (!$stmt->execute(array($slug, $school_id))) {
			$stmt = null;
			exit();
		}

		$studentId = $stmt->fetch(PDO::FETCH_ASSOC);
		return $studentId['id'];
		$stmt = null;
	}


	public function getStudentById($id)
	{
		$stmt = $this->connect()->prepare('SELECT * FROM users_lnp WHERE id = ?');

		if (!$stmt->execute(array($id))) {
			$stmt = null;
			exit();
		}

		$studentInfo = $stmt->fetch(PDO::FETCH_ASSOC);
		return $studentInfo;
		$stmt = null;
	}

	public function getStudentByIdAndRole($id)
	{
		try {
			$stmt = $this->connect()->prepare('SELECT * FROM users_lnp WHERE id = ? AND role = 2');

			$stmt->execute(array($id));
			$studentInfo = $stmt->fetch(PDO::FETCH_ASSOC);

			return $studentInfo;

		} catch (PDOException $e) {
			return null;
		}
	}

	public function getStudentPackages($id)
	{
		$stmt = $this->connect()->prepare('SELECT * FROM package_payments_lnp WHERE user_id = ?');

		if (!$stmt->execute(array($id))) {
			$stmt = null;
			exit();
		}

		$studentPackages = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $studentPackages;
		$stmt = null;
	}

	public function getStudentPackagesWithName($id)
	{
		$stmt = $this->connect()->prepare('SELECT package_payments_lnp.*, packages_lnp.name AS packageName, users_lnp.subscribed_end, classes_lnp.name AS className FROM package_payments_lnp INNER JOIN packages_lnp ON package_payments_lnp.pack_id = packages_lnp.id INNER JOIN users_lnp ON package_payments_lnp.user_id = users_lnp.id INNER JOIN classes_lnp ON users_lnp.class_id = classes_lnp.id WHERE package_payments_lnp.user_id = ?');

		if (!$stmt->execute(array($id))) {
			$stmt = null;
			exit();
		}

		$studentPackages = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $studentPackages;
		$stmt = null;
	}

	public function getStudentAdditionalPackagesWithName($id)
	{
		$stmt = $this->connect()->prepare('SELECT extra_package_payments_lnp.*, extra_packages_lnp.name AS packageName, extra_packages_lnp.type AS packageType, extra_packages_lnp.limit_count AS packageLimit, coaching_guidance_requests_lnp.end_date FROM extra_package_payments_lnp INNER JOIN extra_packages_lnp ON extra_package_payments_lnp.package_id = extra_packages_lnp.id INNER JOIN coaching_guidance_requests_lnp ON extra_package_payments_lnp.user_id = coaching_guidance_requests_lnp.user_id WHERE extra_package_payments_lnp.user_id = ?');

		if (!$stmt->execute(array($id))) {
			$stmt = null;
			exit();
		}

		$studentPackages = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $studentPackages;
		$stmt = null;
	}

	public function getExtraPackageMyList($id)
	{
		// 1. Özel Ders Bilgisi
		$sql1 = "
        SELECT ep.name AS name, 
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
            ) AS remaining,
			 SUM(epp.total_amount) AS total_amount
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
			'total_amount' => $result1['total_amount'],
			'adet' => $result1['remaining'] + $result1['total_used'] . ' adet',
			'end_date' => $result1['remaining']
		];

		// 2. Koçluk/Rehberlik Paket Bilgileri
		$sql2 = "
        SELECT 
            ep.name, 
            ep.type, 
			ep.limit_count,
			epp.total_amount,
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
				'total_amount' => $row['total_amount'],
				'adet' => $row['limit_count'] . ' aylık',
				'end_date' => $row['end_date']
			];
		}

		return $data;
	}

	public function getStudentLoginInfo($id)
	{
		$stmt = $this->connect()->prepare('SELECT * FROM logininfo_lnp  WHERE user_id = ? ORDER BY id DESC');

		if (!$stmt->execute(array($id))) {
			$stmt = null;
			exit();
		}

		$loginData = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $loginData;
		$stmt = null;
	}

	public function getStudentLastLoginInfo($id)
	{
		$stmt = $this->connect()->prepare('SELECT * FROM logininfo_lnp  WHERE user_id = ? ORDER BY id DESC LIMIT 1');

		if (!$stmt->execute(array($id))) {
			$stmt = null;
			exit();
		}

		$loginData = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $loginData;
		$stmt = null;
	}

	public function getStudentAdditionalPackages($id)
	{
		$stmt = $this->connect()->prepare('SELECT * FROM extra_package_payments_lnp WHERE user_id = ?');

		if (!$stmt->execute(array($id))) {
			$stmt = null;
			exit();
		}

		$studentPackages = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $studentPackages;
		$stmt = null;
	}

	public function getStudentClass($id)
	{
		$stmt = $this->connect()->prepare('SELECT * FROM classes_lnp WHERE id = ?');

		if (!$stmt->execute(array($id))) {
			$stmt = null;
			exit();
		}

		$studentClasses = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $studentClasses;
		$stmt = null;
	}

	public function getOneStudent($student_id)
	{

		$stmt = $this->connect()->prepare('SELECT users_lnp.*, schools_lnp.name AS schoolName, classes_lnp.name AS className FROM users_lnp INNER JOIN schools_lnp ON users_lnp.school_id = schools_lnp.id INNER JOIN classes_lnp ON users_lnp.class_id = classes_lnp.id WHERE users_lnp.id = ?');

		if (!$stmt->execute(array($student_id))) {
			$stmt = null;
			exit();
		}

		$schoolData = $stmt->fetch(PDO::FETCH_ASSOC);

		return $schoolData;

		$stmt = null;
	}

	public function getStudents()
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

	public function getLessons()
	{

		$stmt = $this->connect()->prepare('SELECT * FROM lessons_lnp');

		if (!$stmt->execute(array())) {
			$stmt = null;
			exit();
		}

		$lessonData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $lessonData;

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

	public function getTopics($lesson_id, $class_id, $school_id, $unit_id)
	{

		$stmt = $this->connect()->prepare('SELECT * FROM topics_lnp WHERE school_id = ? AND lesson_id = ? AND class_id = ? AND unit_id = ?');

		if (!$stmt->execute(array($school_id, $lesson_id, $class_id, $unit_id))) {
			$stmt = null;
			exit();
		}

		$unitData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $unitData;

		$stmt = null;
	}

	public function getStudentsForTests($class_id)
	{

		$stmt = $this->connect()->prepare('SELECT id, name, surname, photo FROM users_lnp WHERE class_id = ?');

		if (!$stmt->execute(array($class_id))) {
			$stmt = null;
			exit();
		}

		$schoolData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $schoolData;

		$stmt = null;
	}

	public function getStudentsForParents()
	{

		$stmt = $this->connect()->prepare('SELECT id, name, surname FROM users_lnp WHERE active = ? AND role = ? AND parent_id IS NULL');

		if (!$stmt->execute(array("1", "2"))) {
			$stmt = null;
			exit();
		}

		$studentData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $studentData;

		$stmt = null;
	}
}
