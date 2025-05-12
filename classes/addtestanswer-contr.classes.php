<?php

class AddTestAnswerContr extends AddTestAnswer
{

	private $testcevap;
	private $test_id;

	public function __construct($testcevap, $test_id)
	{
		$this->testcevap = $testcevap;
		$this->test_id = $test_id;
	}

	public function addTestAnswerDb()
	{
		$arrayResult = [];

		$testResultGroup = array_chunk($this->testcevap, 4);

		foreach ($testResultGroup as $group) {
			if (count($group) < 4) {
			} else {
				$sayilar = array_count_values($group);
				foreach ($sayilar as $deger => $sayi) {
					if ($sayi === 1) {
						$arrayResult[] = $deger;
					} elseif ($sayi === 4) {
						$arrayResult[] = "";
					}
				}
			}
		}


		$testCevaplar = implode(":/;", $arrayResult);
		
		$testCevaplar = htmlspecialchars($testCevaplar);

		$this->setTestAnswer($testCevaplar, $this->test_id);
	}
}

class AddSolutionQuestAnswerContr extends AddSolutionQuestAnswer
{

	private $testcevap;
	private $question_id;

	public function __construct($testcevap, $question_id)
	{
		$this->testcevap = $testcevap;
		$this->question_id = $question_id;
	}

	public function addSolutionQuestAnswerDb()
	{

		$arrayResult = [];

		$testResultGroup = array_chunk($this->testcevap, 4);

		foreach ($testResultGroup as $group) {
			if (count($group) < 4) {
			} else {
				$sayilar = array_count_values($group);
				foreach ($sayilar as $deger => $sayi) {
					if ($sayi === 1) {
						$arrayResult[] = $deger;
					} elseif ($sayi === 4) {
						$arrayResult[] = "";
					}
				}
			}
		}


		$testCevaplar = implode(":/;", $arrayResult);
		
		$testCevaplar = htmlspecialchars($testCevaplar);


		$this->setSolutionQuestAnswer($testCevaplar, $this->question_id);
	}
}
