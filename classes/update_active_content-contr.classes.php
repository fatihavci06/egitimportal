<?php

class UpdateActiveContentContr extends UpdateActiveContent {

	private $id;
	private $statusVal;

	public function __construct($id, $statusVal){
		$this->id = $id;
		$this->statusVal = $statusVal;
	}

	public function updateActiveContentDb() {

		$this->setContentActive($this->id, $this->statusVal);
	}



}