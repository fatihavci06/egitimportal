<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	// Grabbing the data
	$name = trim($_POST["name"]);
	$classes = trim($_POST["classes"]);
	$lessons = trim($_POST["lessons"]);
	$units = trim($_POST["units"]);
	$topics = trim($_POST["topics"]);
	$short_desc = trim($_POST["short_desc"]);
	$last_day = $_POST["last_day"];
	

	$testsoru = @$_POST['testsoru'];
	$cevap_a = @$_POST['cevap_a'];
	$cevap_b = @$_POST['cevap_b'];
	$cevap_c = @$_POST['cevap_c'];
	$cevap_d = @$_POST['cevap_d'];
	$testcevap = @$_POST['testcevap'];

	$photoSize = $_FILES['photo']['size'];
	$photoName = $_FILES['photo']['name'];
	$fileTmpName = $_FILES['photo']['tmp_name'];

	$test = 1;
	$content = "";
	$question = 0;
	$video_url = "";

	// Instantiate AddTestContr class
	include_once "../classes/dbh.classes.php";
	include_once "../classes/addtopic.classes.php";
	include_once "../classes/addtopic-contr.classes.php";
	include_once "../classes/slug.classes.php";
	include_once "../classes/addimage.classes.php";


	$addTest = new AddTestContr($photoSize, $photoName, $fileTmpName, $name, $classes, $lessons, $units, $topics, $short_desc, $testsoru, $cevap_a, $cevap_b, $cevap_c, $cevap_d, $testcevap, $test, $content, $question, $video_url, $last_day);

	// Running error handlers and addTest
	$addTest->addTestDb();

	


	// Going to back to products page
	//header("location: ../kategoriler");
}
