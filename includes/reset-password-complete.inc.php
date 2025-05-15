<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("HTTP/1.1 403 Forbidden");
    exit();
}

include_once "../classes/dbh.classes.php";

// Set response header to JSON
header('Content-Type: application/json');

// Get form data
$token = $_POST['token'] ?? '';
$password = $_POST['password'] ?? '';

// Get password confirmation from POST or use password value if only one password field is sent
$passwordConfirm = $_POST['confirm-password'] ?? $_POST['password'] ?? '';

// For debugging - uncomment if needed
// error_log("Token: " . $token);
// error_log("Password: " . $password);




// Validate input
if (empty($token)) {
    echo json_encode([
        'success' => false,
        'message' => 'Geçersiz belirteç.'
    ]);
    exit();
}

if (empty($password)) {
    echo json_encode([
        'success' => false,
        'message' => 'Lütfen tüm alanları doldurun.'
    ]);
    exit();
}

// Only check password confirmation if it was sent separately
if (isset($_POST['confirm-password']) && $password !== $_POST['confirm-password']) {
    echo json_encode([
        'success' => false,
        'message' => 'Şifreler eşleşmiyor.'
    ]);
    exit();
}

// Password strength validation
if (strlen($password) < 8) {
    echo json_encode([
        'success' => false,
        'message' => 'Şifre en az 8 karakter uzunluğunda olmalıdır.'
    ]);
    exit();
}

// Check if password has at least one uppercase letter, one lowercase letter, and one number
if (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password)) {
    echo json_encode([
        'success' => false,
        'message' => 'Şifre en az bir büyük harf, bir küçük harf ve bir rakam içermelidir.'
    ]);
    exit();
}





try {
    $dbh = new Dbh();
    $conn = $dbh->connect();

    // Check if token exists and hasn't expired
    $currentTime = date('Y-m-d H:i:s');
    $stmt = $conn->prepare("SELECT user_id FROM password_reset_lnp WHERE token = :token AND expires_at > :current_time");
    $stmt->bindParam(':token', $token);
    $stmt->bindParam(':current_time', $currentTime);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);




    if (!$result) {
        echo json_encode([
            'success' => false,
            'message' => 'Bu şifre sıfırlama bağlantısı geçersiz veya süresi dolmuş.'
        ]);
        exit();
    }

    $userId = $result['user_id'];



    // Hash the new password
    $hashedPassword = password_hash(trim($password), PASSWORD_DEFAULT);

    // Begin transaction
    $conn->beginTransaction();

    try {
        // Update the user's password
        $stmt = $conn->prepare("UPDATE users_lnp SET password = :password, updated_at = NOW() WHERE id = :user_id");
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        // Delete the reset token
        $stmt = $conn->prepare("DELETE FROM password_reset_lnp WHERE token = :token");
        $stmt->bindParam(':token', $token);
        $stmt->execute();

        // Commit transaction
        $conn->commit();

        echo json_encode([
            'success' => true,
            'message' => 'Şifreniz başarıyla sıfırlandı. Artık yeni şifrenizle giriş yapabilirsiniz.'
        ]);
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollBack();
        throw $e;
    }
} catch (PDOException $e) {
    // Log the error
    // error_log("Password reset completion error: " . $e->getMessage());

    echo json_encode([
        'success' => false,
        'message' => 'Bir hata oluştu. Lütfen daha sonra tekrar deneyin.'
    ]);
} catch (Exception $e) {
    // error_log("General error in password reset completion: " . $e->getMessage());

    echo json_encode([
        'success' => false,
        'message' => 'Bir hata oluştu. Lütfen daha sonra tekrar deneyin.'
    ]);
}