<?php
session_start();
include_once "../classes/dbh.classes.php";
include_once "../classes/user.classes.php";

$userObj = new User();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['password'];
    $newPassword = $_POST['new-password'];
    $confirmPassword = $_POST['confirm-password'];

    if ($newPassword !== $confirmPassword) {

        echo json_encode(["status" => "fail", "message" => "Yeni şifreler uyuşmuyor."]);
        exit();
    }

    $user = $userObj->getUserById($_SESSION['id']);


    if (!$user || !password_verify($currentPassword, $user['password'])) {
        echo json_encode(["status" => "fail", "message" => "Mevcut şifre hatalı."]);
        exit;
    }

    $db = (new dbh())->connect();

    $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    if (password_verify($newHashedPassword, $user['password'])) {
        echo json_encode(["status" => "fail", "message" => "Yeni şifre Mevcut şifre ile aynı olamaz."]);
        exit;
    }

    $update = $db->prepare("UPDATE users_lnp SET password = ? WHERE id = ?");
    $update->execute([$newHashedPassword, $user["id"]]);

    echo json_encode(["status" => "success", "message" => "Şifre başarıyla değiştirildi."]);
    exit;
}