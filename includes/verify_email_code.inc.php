<?php
session_start();


include_once "../classes/dbh.classes.php";
include_once "../classes/user.classes.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['id'] ?? null;
    $inputCode = $_POST['verification_code'] ?? null;

    if (!$userId || !$inputCode) {
        echo json_encode(['status' => 'error', 'message' => 'Kod eksik.']);
        exit;
    }
    $db = (new dbh())->connect();

    // $date = new DateTime('now', new DateTimeZone('Europe/istanbul'));


    $stmt = $db->prepare("SELECT * FROM email_verifications_lnp WHERE user_id = ? AND verification_code = ? AND expires_at > NOW()");
    $stmt->execute([$userId, $inputCode]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        echo json_encode(['status' => 'error', 'message' => 'Kod geçersiz veya süresi dolmuş.']);
        exit;
    }

    $updateStmt = $db->prepare("UPDATE users_lnp SET email = ? WHERE id = ?");
    $updateStmt->execute([$row['email'], $userId]);

    $db->prepare("DELETE FROM email_verifications_lnp WHERE user_id = ?")->execute([$userId]);

    echo json_encode(['status' => 'success', 'message' => 'E-posta başarıyla güncellendi.']);
}
