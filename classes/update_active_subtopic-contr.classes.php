<?php

class UpdateActiveSubtopicContr extends UpdateActiveSubTopic {

	private $id;
	private $statusVal;

	public function __construct($id, $statusVal){
		$this->id = $id;
		$this->statusVal = $statusVal;
	}

	public function updateActiveSubtopicDb() {

		$this->setSubtopicActive($this->id, $this->statusVal);
	}



}