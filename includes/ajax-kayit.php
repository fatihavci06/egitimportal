<?php
// includes/ajax.php
include_once '../classes/dbh.classes.php';
include_once '../classes/Mailer.php';
$mailer = new Mailer();
header('Content-Type: application/json');
session_start();
if (!$_SESSION['role']) {
    echo json_encode(['status' => 'error', 'message' => 'Yetkisiz erişim.']);
    exit();
}

// Sadece POST isteğini kabul et

// Servis kontrolü
$service = $_GET['service'] ?? '';

// Veritabanı bağlantısı (örnek PDO ile)
$pdo = new Dbh();
$pdo = $pdo->connect();

// Servise göre işlem yapılacak switch case yapısı
switch ($service) {
    case 'creditcard':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $packageId = $_POST['packageId'] ?? null;

            if ($packageId) {
                // Veritabanından kredi kartı ücretini çek
                $stmt = $pdo->prepare('SELECT credit_card_fee FROM packages_lnp WHERE id = ?');
                $stmt->execute([$packageId]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result) {
                    echo json_encode(['status' => 'success', 'credit_card_fee' => $result['credit_card_fee']]);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Paket bulunamadı']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Paket ID eksik']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Geçersiz istek yöntemi']);
        }
        break;


    default:
        echo json_encode(['status' => 'error', 'message' => 'Geçersiz servis']);
        break;
}
