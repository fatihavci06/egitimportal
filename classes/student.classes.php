<?php


class Student extends Dbh
{

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

	protected function getStudentsList()
	{

		if ($_SESSION['role'] == 1) {
			$stmt = $this->connect()->prepare('SELECT users_lnp.*, classes_lnp.name AS className, classes_lnp.slug AS classSlug, users_lnp.active AS userActive, schools_lnp.name AS schoolName FROM users_lnp INNER JOIN classes_lnp ON users_lnp.class_id = classes_lnp.id INNER JOIN schools_lnp ON users_lnp.school_id = schools_lnp.id WHERE (users_lnp.active = ? OR users_lnp.active = ?) AND (users_lnp.role = ? OR users_lnp.role = ?)');

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
		}


		$schoolData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $schoolData;

		$stmt = null;
	}

	public function getStudentId($slug){
		$stmt = $this->connect()->prepare('SELECT * FROM users_lnp WHERE username = ?');

		if (!$stmt->execute(array($slug))) {
			$stmt = null;
			exit();
		}

		$studentId = $stmt->fetch(PDO::FETCH_ASSOC);
		return $studentId['id'];
		$stmt = null;
	}

	public function getStudentIdForTeacher($slug, $school_id, $class_id){
		$stmt = $this->connect()->prepare('SELECT * FROM users_lnp WHERE username = ? AND school_id = ? AND class_id = ?');

		if (!$stmt->execute(array($slug, $school_id, $class_id))) {
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
		$stmt = $this->connect()->prepare('SELECT additional_package_payments_lnp.*, packages_lnp.name AS packageName, users_lnp.subscribed_end FROM additional_package_payments_lnp INNER JOIN packages_lnp ON additional_package_payments_lnp.pack_id = packages_lnp.id INNER JOIN users_lnp ON additional_package_payments_lnp.user_id = users_lnp.id WHERE additional_package_payments_lnp.user_id = ?');

		if (!$stmt->execute(array($id))) {
			$stmt = null;
			exit();
		}

		$studentPackages = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $studentPackages;
		$stmt = null;
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

	public function getStudentAdditionalPackages($id)
	{
		$stmt = $this->connect()->prepare('SELECT * FROM additional_package_payments_lnp WHERE user_id = ?');

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

		$stmt = $this->connect()->prepare('SELECT users_lnp.*, schools_lnp.name AS schoolName FROM users_lnp INNER JOIN schools_lnp ON users_lnp.school_id = schools_lnp.id WHERE users_lnp.id = ?');

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
