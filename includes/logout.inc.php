<?php

session_start();

require_once __DIR__ . '/../classes/dbh.classes.php';
$db = new Dbh();

$stmt = $db->connect()->prepare("UPDATE logininfo_lnp SET logoutTime = NOW() WHERE id = :id");
$stmt->execute([':id' => $_SESSION['login_id']]);


session_unset();

session_destroy();


// Going to back to front page
header("location: ../index");
