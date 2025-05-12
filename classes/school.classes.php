<?php

class School extends Dbh {

	protected function getSchoolsList(){

		$stmt = $this->connect()->prepare('SELECT id, name, slug, created_at, email, telephone, city FROM schools_lnp WHERE active = ?');

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


}



