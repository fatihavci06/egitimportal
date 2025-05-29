<?php

class UpdateActiveSchoolContr extends UpdateActiveSchool {

	private $email;
	private $statusVal;

	public function __construct($email, $statusVal){
		$this->email = $email;
		$this->statusVal = $statusVal;
	}

	public function updateActiveSchoolDb() {
		if($this->statusVal == "1"){
			$this->setSchoolActive($this->email);
		} elseif($this->statusVal == "0") {
			$this->setSchoolPassive($this->email);
		} else {
			exit("Invalid status value");
		}
		/* $this->setSchoolPassive($this->email, $this->statusVal); */

	}



}