<?php

session_start();

if (!isset($_SESSION['id'])) {
    header("HTTP/1.0 404 Not Found");
    header("Location: 404.php");
    exit();
}
$sender_id = (int) $_SESSION['id'];
$receiver_id = (int) $_POST["receiver"];
$subject = $_POST["subject"];
$body = $_POST["body"];


require_once "../classes/message.classes.php";

try {
    $messageObj = new Message();
    $messageObj->send($sender_id, $receiver_id, $subject, $body);

} catch (Exception $e) {
    echo json_encode(["status" => "fail", "message" => "Mesaj Gönderilemedi."]);
    exit;
}
echo json_encode(["status" => "success", "message" => "Mesaj Başarıyla Gönderildi."]);
exit;