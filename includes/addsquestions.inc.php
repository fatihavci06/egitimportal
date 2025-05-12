<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	// Grabbing the data
	

	$testcevap = $_POST['testcevap'];
	$question_id = $_POST['question_id'];

	// Instantiate AddSolutionQuestAnswerContr class
	include_once "../classes/dbh.classes.php";
	include_once "../classes/addtestanswer.classes.php";
	include_once "../classes/addtestanswer-contr.classes.php";


	$addSolutionQuestAnswer = new AddSolutionQuestAnswerContr($testcevap, $question_id);

	// Running error handlers and addSolutionQuestAnswer
	$addSolutionQuestAnswer->addSolutionQuestAnswerDb();

	


	// Going to back to products page
	//header("location: ../kategoriler");
}
