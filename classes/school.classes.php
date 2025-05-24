<?php

class School extends Dbh {

	protected function getSchoolsList(){

		$stmt = $this->connect()->prepare('SELECT id, name, slug, created_at, email, telephone, city FROM schools_lnp WHERE active = ? ORDER BY id ASC');

		if(!$stmt->execute(array("1"))){
			$stmt = null;
			exit();
		}

        $schoolData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $schoolData;

		$stmt = null;
		
	}

	public function getOneSchool($slug){

		$stmt = $this->connect()->prepare('SELECT * FROM schools_lnp WHERE slug = ?');

		if(!$stmt->execute(array($slug))){
			$stmt = null;
			exit();
		}

        $schoolData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $schoolData;

		$stmt = null;
		
	}

	public function getSchools(){

		$stmt = $this->connect()->prepare('SELECT * FROM schools_lnp');

		if(!$stmt->execute(array(0))){
			$stmt = null;
			exit();
		}

        $schoolData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $schoolData;

		$stmt = null;
		
	}

	public function getOneSchoolById($id){

		$stmt = $this->connect()->prepare('SELECT * FROM schools_lnp WHERE id = ?');

		if(!$stmt->execute(array($id))){
			$stmt = null;
			exit();
		}

        $schoolData = $stmt->fetch(PDO::FETCH_ASSOC);

        return $schoolData;

		$stmt = null;
		
	}

	public function getSchoolAdmin($id){

		$stmt = $this->connect()->prepare('SELECT * FROM users_lnp WHERE role = ? AND school_id = ?');

		if(!$stmt->execute(array(8, $id))){
			$stmt = null;
			exit();
		}

        $getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $getData;

		$stmt = null;
		
	}

	public function getSchoolCoordinator($id){

		$stmt = $this->connect()->prepare('SELECT * FROM users_lnp WHERE role = ? AND school_id = ?');

		if(!$stmt->execute(array(3, $id))){
			$stmt = null;
			exit();
		}

        $getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $getData;

		$stmt = null;
		
	}

	public function getSchoolStudents($id){

		$stmt = $this->connect()->prepare('SELECT * FROM users_lnp WHERE role = ? AND school_id = ?');

		if(!$stmt->execute(array(2, $id))){
			$stmt = null;
			exit();
		}

        $getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $getData;

		$stmt = null;
		
	}

	public function getSchoolTeachers($id){

		$stmt = $this->connect()->prepare('SELECT * FROM users_lnp WHERE role = ? AND school_id = ?');

		if(!$stmt->execute(array(4, $id))){
			$stmt = null;
			exit();
		}

        $getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $getData;

		$stmt = null;
		
	}


}



