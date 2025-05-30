<?php

class UpdateActiveUnitContr extends UpdateActiveUnit {

	private $id;
	private $statusVal;

	public function __construct($id, $statusVal){
		$this->id = $id;
		$this->statusVal = $statusVal;
	}

	public function updateActiveUnitDb() {

		$this->setUnitActive($this->id, $this->statusVal);
	}



}