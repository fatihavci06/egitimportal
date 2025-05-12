<?php

class UpdateActiveStudentContr extends UpdateActiveStudent {

	private $username;

	public function __construct($username){
		$this->username = $username;
	}

	public function updateActiveStudentDb() {

		$this->setStudentActive($this->username);
	}



}