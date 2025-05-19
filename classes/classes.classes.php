<?php

class Classes extends Dbh
{

	protected function getClassesList()
	{

		$stmt = $this->connect()->prepare('SELECT id, name, slug FROM classes_lnp where class_type=0 ORDER BY orderBY ASC');

		if (!$stmt->execute(array())) {
			$stmt = null;
			exit();
		}

		$classData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $classData;
	}
	protected function getMainSchoolClassesList()
	{

		$stmt = $this->connect()->prepare('SELECT id, name, slug FROM classes_lnp where school_id = 1 and class_type=1');

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

		$stmt = $this->connect()->prepare('SELECT * FROM main_school_content_lnp where school_id = 1');

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
