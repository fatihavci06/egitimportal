<?php
session_start(); 

$user_id = $_SESSION['user_id'] ?? 'guest';

$filename = basename($_GET['file']);  
$filepath = __DIR__ . "/../private_uploads/contents/" . $filename;

if (!file_exists($filepath)) {
    header("HTTP/1.0 404 Not Found");
    header("Location: 404.php");
    exit();
}
// $logEntry = sprintf(
//     "[%s] User %s downloaded %s\n",
//     date('Y-m-d H:i:s'),
//     $user_id,
//     $filename
// );
// file_put_contents(__DIR__ . '/download_logs.txt', $logEntry, FILE_APPEND);

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . filesize($filepath));
flush();
readfile($filepath);
exit;
