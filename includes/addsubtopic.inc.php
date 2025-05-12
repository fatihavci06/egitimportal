<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	// Grabbing the data
	$name = trim($_POST["name"]);
	$classes = trim($_POST["classes"]);
	$lessons = trim($_POST["lessons"]);
	$units = trim($_POST["units"]);
	$topics = trim($_POST["topics"]);
	$short_desc = trim($_POST["short_desc"]);
	$chooseType = trim($_POST["secim"]);
	$content = @$_POST["icerik"];
	$video_url = trim(@$_POST["video_url"]);
	

	$testsoru = @$_POST['testsoru'];
	$cevap_a = @$_POST['cevap_a'];
	$cevap_b = @$_POST['cevap_b'];
	$cevap_c = @$_POST['cevap_c'];
	$cevap_d = @$_POST['cevap_d'];
	$testcevap = @$_POST['testcevap'];

	
	$cozumlusoru = @$_POST['cozumlusoru'];
	$cozumlu_cevap_a = @$_POST['cozumlu_cevap_a'];
	$cozumlu_cevap_b = @$_POST['cozumlu_cevap_b'];
	$cozumlu_cevap_c = @$_POST['cozumlu_cevap_c'];
	$cozumlu_cevap_d = @$_POST['cozumlu_cevap_d'];
	$cozumlu_testcevap = @$_POST['cozumlu_testcevap'];
	$solution = @$_POST['solution'];

	$photoSize = $_FILES['photo']['size'];
	$photoName = $_FILES['photo']['name'];
	$fileTmpName = $_FILES['photo']['tmp_name'];

	if($chooseType == "test" OR $chooseType == "question"){
		$content = "";
		$video_url = "";
	}
	
	if($chooseType == "test"){
		$cozumlusoru = "";
		$cozumlu_cevap_a = "";
		$cozumlu_cevap_b = "";
		$cozumlu_cevap_c = "";
		$cozumlu_cevap_d = "";
		$cozumlu_testcevap = "";
		$solution = "";
		$test = 1;
		$question = 0;
	}elseif($chooseType == "question"){
		$testsoru = "";
		$cevap_a = "";
		$cevap_b = "";
		$cevap_c = "";
		$cevap_d = "";
		$testcevap = "";
		$test = 0;
		$question = 1;
	}elseif($chooseType == "subject"){
		$cozumlusoru = "";
		$cozumlu_cevap_a = "";
		$cozumlu_cevap_b = "";
		$cozumlu_cevap_c = "";
		$cozumlu_cevap_d = "";
		$cozumlu_testcevap = "";
		$solution = "";
		
		$testsoru = "";
		$cevap_a = "";
		$cevap_b = "";
		$cevap_c = "";
		$cevap_d = "";
		$testcevap = "";

		$test = 0;
		$question = 0;
	}

	// Instantiate AddSubTopicContr class
	include_once "../classes/dbh.classes.php";
	include_once "../classes/addtopic.classes.php";
	include_once "../classes/addtopic-contr.classes.php";
	include_once "../classes/slug.classes.php";
	include_once "../classes/addimage.classes.php";


	$addTopic = new AddSubTopicContr($photoSize, $photoName, $fileTmpName, $name, $classes, $lessons, $units, $topics, $short_desc, $content, $video_url, $chooseType, $cozumlusoru, $cozumlu_cevap_a, $cozumlu_cevap_b, $cozumlu_cevap_c, $cozumlu_cevap_d, $cozumlu_testcevap, $solution, $testsoru, $cevap_a, $cevap_b, $cevap_c, $cevap_d, $testcevap, $test, $question);

	// Running error handlers and addTopic
	$addTopic->addSubTopicDb();

	


	// Going to back to products page
	//header("location: ../kategoriler");
}
