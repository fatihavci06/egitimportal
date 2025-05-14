<?php

include_once '../classes/dbh.classes.php';
header('Content-Type: application/json');

// Sadece POST isteğini kabul et

// Servis kontrolü
$service = $_GET['service'] ?? '';

// Veritabanı bağlantısı (örnek PDO ile)
$pdo = new Dbh();
$pdo = $pdo->connect();

// Servise göre işlem yapılacak switch case yapısı
switch ($service) {
    case 'cuoponadd':

        try {
            // Silme işlemi


            $stmt = $pdo->prepare("INSERT INTO category_titles_lnp (type, title) VALUES (:type, :title)");

            $stmt->execute([
                ':type' => 'test',
                ':title' => 'test'
            ]);
            echo json_encode(['status' => 'success', 'message' => 'Başlık başarıyla kaydedildi.']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;
    case 'cuoponadd':

        try {
            // Silme işlemi


            $stmt = $pdo->prepare("INSERT INTO category_titles_lnp (type, title) VALUES (:type, :title)");

            $stmt->execute([
                ':type' => 'test',
                ':title' => 'test'
            ]);
            echo json_encode(['status' => 'success', 'message' => 'Başlık başarıyla kaydedildi.']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;






    // Diğer servisler buraya eklenebilir
    default:
        echo json_encode(['status' => 'error', 'message' => 'Geçersiz servis']);
        break;
}
