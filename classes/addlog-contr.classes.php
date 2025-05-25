<?php

class AddTimeSpendContr extends AddTimeSpend
{

	private $timeSpent;
	private $pageUrl;
	private $timestamp;
	private $startTime;

	public function __construct($timeSpent, $pageUrl, $timestamp, $startTime)
	{
		$this->timeSpent = $timeSpent;
		$this->pageUrl = $pageUrl;
		$this->timestamp = $timestamp;
		$this->startTime = $startTime;
	}

	public function addTimeSpendDb()
	{
		$this->setTestAnswer($this->timeSpent, $this->pageUrl, $this->timestamp, $this->startTime);
	}
}

