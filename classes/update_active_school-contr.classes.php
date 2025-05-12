<?php

class UpdateActiveSchoolContr extends UpdateActiveSchool {

	private $email;

	public function __construct($email){
		$this->email = $email;
	}

	public function updateActiveSchoolDb() {

		$this->setSchoolActive($this->email);
	}



}