<?php

class UpdateActiveTopicContr extends UpdateActiveTopic {

	private $id;
	private $statusVal;

	public function __construct($id, $statusVal){
		$this->id = $id;
		$this->statusVal = $statusVal;
	}

	public function updateActiveTopicDb() {

		$this->setTopicActive($this->id, $this->statusVal);
	}



}