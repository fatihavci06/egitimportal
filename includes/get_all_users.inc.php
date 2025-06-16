<?php
session_start(); 

if (!isset($_SESSION['id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit();
}

include_once "../classes/dbh.classes.php";
include_once "../classes/userslist.classes.php"; 

$usersObj = new User();
$users = $usersObj->getAllUsers();

header('Content-Type: application/json');
echo json_encode(['success' => true, 'users' => $users]);
exit();