<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	$service = $_GET['action'] ?? 'create';

	// Check if the service is 'create'
	if ($service == 'create') {
		// Grabbing the data
		$name = trim($_POST["name"]);
		$classes = trim($_POST["classes"]);
		$lessons = trim($_POST["lessons"]);
		$short_desc = trim($_POST["short_desc"]);
		$photoSize = $_FILES['photo']['size'];
		$photoName = $_FILES['photo']['name'];
		$fileTmpName = $_FILES['photo']['tmp_name'];
		$start_date = trim($_POST["unit_start_date"]);
		$end_date = trim($_POST["unit_end_date"]);
		$unit_order = trim($_POST["unit_order"]);

		// Instantiate AddUnitContr class
		include_once "../classes/dbh.classes.php";
		include_once "../classes/addunit.classes.php";
		include_once "../classes/addunit-contr.classes.php";
		include_once "../classes/slug.classes.php";
		include_once "../classes/addimage.classes.php";

		$addUnit = new AddUnitContr($photoSize, $photoName, $fileTmpName, $name, $classes, $lessons, $short_desc, $start_date, $end_date, $unit_order);

		// Running error handlers and addUnit
		$addUnit->addUnitDb();
	} else {
		// Grabbing the data
		$name = trim($_POST["name"]);
		$short_desc = trim($_POST["short_desc"]);
		$photoSize = $_FILES['photo']['size'];
		$photoName = $_FILES['photo']['name'];
		$fileTmpName = $_FILES['photo']['tmp_name'];
		$start_date = trim($_POST["unit_start_date"]);
		$end_date = trim($_POST["unit_end_date"]);
		$unit_order = trim($_POST["unit_order"]);
		$slug = trim($_POST["unit_slug"]);

		// Instantiate UpdateUnitContr class
		include_once "../classes/dbh.classes.php";
		include_once "../classes/addunit.classes.php";
		include_once "../classes/addunit-contr.classes.php";
		include_once "../classes/units.classes.php";
		include_once "../classes/slug.classes.php";
		include_once "../classes/addimage.classes.php";

		$updateUnit = new UpdateUnitContr($photoSize, $photoName, $fileTmpName, $name, $short_desc, $start_date, $end_date, $unit_order, $slug);

		// Running error handlers and updateUnit
		$updateUnit->updateUnitDb();
	}
}
