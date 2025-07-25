<?php
session_start();
include_once "../classes/dbh.classes.php";
include_once "../classes/user.classes.php";
require_once '../classes/Mailer.php';

$userObj = new User();
$mailer = new Mailer();

$user = $userObj->getUserById($_SESSION['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $isNew = $userObj->getUserById($_SESSION['id']);
    $db = (new dbh())->connect();

    if (!$email) {
        echo json_encode([
            'status' => 'fail',
            'message' => 'Geçersiz e-posta adresi.'
        ]);
        exit;
    }
    if ($email == $user['email']) {
        echo json_encode([
            'status' => 'fail',
            'message' => 'Yeni e-posta mevcut e-posta ile aynı olamaz.'
        ]);
        exit;

    }
    if (emailExists($email)) {
        echo json_encode([
            'status' => 'fail',
            'message' => 'Girilen e-posta sistemde mevcuttur.'
        ]);
        exit;

    }

    $verificationCode = rand(100000, 999999);

    $date = new DateTime('now', new DateTimeZone('Europe/istanbul')); // Set your timezone
    $date->modify('+1 hour');
    $expiresAt = $date->format('Y-m-d H:i:s');


    $db->prepare("DELETE FROM email_verifications_lnp WHERE user_id = ?")->execute([$user['id']]);

    $stmt = $db->prepare("INSERT INTO email_verifications_lnp (user_id, email, verification_code, expires_at) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user["id"], $email, $verificationCode, $expiresAt]);



    $emailResult = $mailer->sendVerificationCodeForNewEmail($email, $user['name'], $verificationCode);

    if (!$emailResult) {
        echo json_encode([
            'status' => 'fail',
            'message' => 'E-posta gönderilemedi.'
        ]);
        exit;
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Doğrulama kodu gönderildi.'
    ]);
    exit();
}
function emailExists($email)
{
    $db = (new dbh())->connect();

    $sql = "SELECT 1 FROM users_lnp WHERE email = :email LIMIT 1";
    $stmt = $db->prepare($sql);
    $stmt->execute([':email' => $email]);

    return $stmt->fetchColumn() !== false;
}