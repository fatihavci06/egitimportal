<?php

class WrittenBook extends Dbh
{

	protected function getWrittenBooksList()
	{
		$stmt = $this->connect()->prepare('
		SELECT 
		g.id, 
		g.name AS book_name,
		g.cover_img,
		g.book_url,
		g.slug,
		g.is_active,
		g.created_at,
		g.updated_at,
		c.name AS class_name,
		l.name AS lesson_name,
		u.name AS unit_name,
		t.name AS topic_name,
		st.name AS subtopic_name
		FROM 
			written_book_lnp g
		LEFT JOIN classes_lnp c ON g.class_id = c.id
		LEFT JOIN lessons_lnp l ON g.lesson_id = l.id
		LEFT JOIN units_lnp u ON g.unit_id = u.id
		LEFT JOIN topics_lnp t ON g.topic_id = t.id
		LEFT JOIN subtopics_lnp st ON g.subtopic_id = st.id;');
		if (!$stmt->execute(array())) {
			$stmt = null;
			exit();
		}

		$unitData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $unitData;

		$stmt = null;
	}

	protected function getWrittenBookListImage()
	{
		$stmt = $this->connect()->prepare('SELECT * FROM written_book_lnp LIMIT 1');

		if (!$stmt->execute(array())) {
			$stmt = null;
			exit();
		}

		$unitData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $unitData;

		$stmt = null;
	}

	protected function getWrittenBookOneLesson()
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

	protected function getWrittenBookListStu($lessonId, $classId, $schoolId)
	{
		$stmt = $this->connect()->prepare('SELECT id, name, slug, short_desc, photo FROM units_lnp WHERE lesson_id = ? AND class_id = ? AND school_id = ?');

		if (!$stmt->execute(array($lessonId, $classId, $schoolId))) {
			$stmt = null;
			exit();
		}

		$unitData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $unitData;

		$stmt = null;
	}

	public function getOneWrittenBook($slug)
	{
		$stmt = $this->connect()->prepare('
		SELECT 
			g.id, 
			g.slug,
			g.name AS book_name,
			g.cover_img,
			g.book_url,
			g.class_id,
			g.lesson_id,
			g.unit_id,
			g.topic_id,
			g.subtopic_id,
			g.created_at,
			g.updated_at,
			g.is_active,
			c.name AS class_name,
			l.name AS lesson_name,
			u.name AS unit_name,
			t.name AS topic_name,
			st.name AS subtopic_name
		FROM 
			written_book_lnp g
		LEFT JOIN classes_lnp c ON g.class_id = c.id
		LEFT JOIN lessons_lnp l ON g.lesson_id = l.id
		LEFT JOIN units_lnp u ON g.unit_id = u.id
		LEFT JOIN topics_lnp t ON g.topic_id = t.id
		LEFT JOIN subtopics_lnp st ON g.subtopic_id = st.id
		WHERE g.slug = ?
		LIMIT 1
	');

		if (!$stmt->execute([$slug])) {
			$stmt = null;
			exit();
		}

		$bookData = $stmt->fetch(PDO::FETCH_ASSOC);

		return $bookData;
	}
	public function getOneWrittenBookById($id)
	{
		$stmt = $this->connect()->prepare('
		SELECT 
			g.id, 
			g.name AS book_name,
			g.cover_img,
			g.book_url,
			g.class_id,
			g.lesson_id,
			g.unit_id,
			g.topic_id,
			g.subtopic_id,
			g.created_at,
			g.updated_at,
			g.is_active,
			c.name AS class_name,
			l.name AS lesson_name,
			u.name AS unit_name,
			t.name AS topic_name,
			st.name AS subtopic_name
		FROM 
			written_book_lnp g
		LEFT JOIN classes_lnp c ON g.class_id = c.id
		LEFT JOIN lessons_lnp l ON g.lesson_id = l.id
		LEFT JOIN units_lnp u ON g.unit_id = u.id
		LEFT JOIN topics_lnp t ON g.topic_id = t.id
		LEFT JOIN subtopics_lnp st ON g.subtopic_id = st.id
		WHERE g.id = ?
		LIMIT 1
	');

		if (!$stmt->execute([$id])) {
			$stmt = null;
			exit();
		}

		$bookData = $stmt->fetch(PDO::FETCH_ASSOC);

		return $bookData;
	}
	public function updateWrittenBookStatus($id, $isActive)
	{
		$db = $this->connect();

		try {
			$stmt = $db->prepare("
            UPDATE written_book_lnp
            SET is_active = :is_active
            WHERE id = :book_id
        ");

			return $stmt->execute([
				':is_active' => $isActive,
				':book_id' => $id
			]);
		} catch (Exception $e) {
			return false;
		}
	}
	public function updateWrittenBookStatusArray($ids, $isActive)
	{
		$db = $this->connect();

		if (empty($ids)) {
			return false;
		}

		$placeholders = implode(',', array_fill(0, count($ids), '?'));

		try {
			$stmt = $db->prepare("
            UPDATE written_book_lnp
            SET is_active = ?
            WHERE id IN ($placeholders)
        ");

			$params = array_merge([$isActive], $ids);

			return $stmt->execute($params);
		} catch (Exception $e) {
			return false;
		}
	}
	public function getWrittenBook()
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

	public function getWrittenBookForTopicList($class, $lessons)
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

	public function getSameWrittenBook($active_slug)
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
class WrittenBookStudent extends Dbh
{

	protected function getWrittenBooksList($class_id)
	{
		$stmt = $this->connect()->prepare('SELECT * FROM written_book_lnp WHERE class_id=?');

		if (!$stmt->execute(array($class_id))) {
			$stmt = null;
			exit();
		}

		$unitData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $unitData;

		$stmt = null;
	}

	protected function getWrittenBooksListImage()
	{
		$stmt = $this->connect()->prepare('SELECT * FROM written_book_lnp LIMIT 1');

		if (!$stmt->execute(array())) {
			$stmt = null;
			exit();
		}

		$unitData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $unitData;

		$stmt = null;
	}

	protected function getWrittenBooksOneLesson()
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

	protected function getWrittenBooksListStu($lessonId, $classId, $schoolId)
	{
		$stmt = $this->connect()->prepare('SELECT id, name, slug, short_desc, photo FROM units_lnp WHERE lesson_id = ? AND class_id = ? AND school_id = ?');

		if (!$stmt->execute(array($lessonId, $classId, $schoolId))) {
			$stmt = null;
			exit();
		}

		$unitData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $unitData;

		$stmt = null;
	}

	public function getOneWrittenBook($slug)
	{

		$stmt = $this->connect()->prepare('SELECT * FROM written_book_lnp WHERE slug = ?');

		if (!$stmt->execute(array($slug))) {
			$stmt = null;
			exit();
		}

		$unitData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $unitData;

		$stmt = null;
	}

	public function getWrittenBooks()
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

	public function getWrittenBooksForTopicList($class, $lessons)
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

	public function getSameWrittenBooks($active_slug)
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
