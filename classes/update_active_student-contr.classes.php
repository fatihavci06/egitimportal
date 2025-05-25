<?php

class UpdateActiveStudentContr extends UpdateActiveStudent {

	private $email;
	private $statusVal;

	public function __construct($email, $statusVal){
		$this->email = $email;
		$this->statusVal = $statusVal;
	}

	public function updateActiveStudentDb() {

		$this->setStudentActive($this->email, $this->statusVal);
	}



}