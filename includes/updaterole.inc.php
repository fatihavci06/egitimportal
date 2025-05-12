<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	// Grabbing the data
	$role_id = trim($_POST["role_id"]);
	$roleCheck = $_POST["roleCheck"];

	// Instantiate UserRoleUpdateContr class
	include_once "../classes/dbh.classes.php";
	include_once "../classes/userroles.classes.php";
	include_once "../classes/userroles-contr.classes.php";
	include_once "../classes/slug.classes.php";

	$updateUserRole = new UserRoleUpdateContr($role_id, $roleCheck);

	// Running error handlers and updateUserRole
	$updateUserRole->updateUserRoleDb();

	


	// Going to back to products page
	//header("location: ../kategoriler");
}
