<?php

class Lessons extends Dbh {

	protected function getLessonsList(){

		$stmt = $this->connect()->prepare('SELECT id, name, slug, class_id FROM lessons_lnp');

		if(!$stmt->execute(array())){
			$stmt = null;
			exit();
		}

        $lessonData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $lessonData;
		
	}

	public function getOneLesson($slug){

		$stmt = $this->connect()->prepare('SELECT * FROM lessons_lnp WHERE slug = ?');

		if(!$stmt->execute(array($slug))){
			$stmt = null;
			exit();
		}

        $lessonData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $lessonData;
		
	}

	public function getLessons(){

		$stmt = $this->connect()->prepare('SELECT * FROM lessons_lnp');

		if(!$stmt->execute(array(0))){
			$stmt = null;
			exit();
		}

        $lessonData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $lessonData;
		
	}

	public function getLessonForUnitList()
	{
		if ($_SESSION['role'] == 1) {
			$stmt = $this->connect()->prepare('SELECT id, name, class_id FROM lessons_lnp ');

			if (!$stmt->execute(array())) {
				$stmt = null;
				exit();
			}
		} elseif ($_SESSION['role'] == 3) {
			$school = $_SESSION['school_id'];
			$stmt = $this->connect()->prepare('SELECT id, name, class_id FROM lessons_lnp WHERE school_id=?');

			if (!$stmt->execute(array($school))) {
				$stmt = null;
				exit();
			}
		}

		$unitData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $unitData;

		$stmt = null;
	}


}



