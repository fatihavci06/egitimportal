<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	// Grabbing the data
	

	$testcevap = $_POST['testcevap'];
	$test_id = $_POST['test_id'];

	// Instantiate AddTestAnswerContr class
	include_once "../classes/dbh.classes.php";
	include_once "../classes/addtestanswer.classes.php";
	include_once "../classes/addtestanswer-contr.classes.php";


	$addTestAnswer = new AddTestAnswerContr($testcevap, $test_id);

	// Running error handlers and addTestAnswer
	$addTestAnswer->addTestAnswerDb();

	


	// Going to back to products page
	//header("location: ../kategoriler");
}
