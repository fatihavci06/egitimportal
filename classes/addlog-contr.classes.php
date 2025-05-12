<?php

class AddTimeSpendContr extends AddTimeSpend
{

	private $timeSpent;
	private $pageUrl;
	private $timestamp;

	public function __construct($timeSpent, $pageUrl, $timestamp)
	{
		$this->timeSpent = $timeSpent;
		$this->pageUrl = $pageUrl;
		$this->timestamp = $timestamp;
	}

	public function addTimeSpendDb()
	{
		$this->setTestAnswer($this->timeSpent, $this->pageUrl, $this->timestamp);
	}
}

