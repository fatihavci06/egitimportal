<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("HTTP/1.1 403 Forbidden");
    exit();
}


include_once "../classes/dbh.classes.php";
require_once '../classes/Mailer.php';


header('Content-Type: application/json');
$email = $_POST['email'] ?? '';
$email = trim($email);

// Validate email format
if (empty($email)) {
    echo json_encode([
        'success' => false,
        'message' => 'E-posta adresi zorunludur.'
    ]);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'success' => false,
        'message' => 'Geçerli bir e-posta giriniz.'
    ]);
    exit();
}

try {
    $dbh = new Dbh();
    $conn = $dbh->connect();

    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT id, username FROM users_lnp WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        // Don't reveal if the email exists for security reasons
        echo json_encode([
            'success' => true,
            'message' => 'Eğer e-posta sistemimizde kayıtlı ise şifre sıfırlama bağlantısı gönderilecektir.'
        ]);
        exit();
    }

    // Generate a unique token
    $token = bin2hex(random_bytes(32));

    $date = new DateTime('now', new DateTimeZone('Europe/istanbul')); // Set your timezone
    $date->modify('+1 hour');
    $expires = $date->format('Y-m-d H:i:s');

    // $expires = date('Y-m-d H:i:s', strtotime('+2 hour'));

    // Check if there's an existing token and delete it
    $stmt = $conn->prepare("DELETE FROM password_reset_lnp WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user['id']);
    $stmt->execute();

    // Store the new token in database
    $stmt = $conn->prepare("INSERT INTO password_reset_lnp (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)");
    $stmt->bindParam(':user_id', $user['id']);
    $stmt->bindParam(':token', $token);
    $stmt->bindParam(':expires_at', $expires);
    $result = $stmt->execute();

    if (!$result) {
        echo json_encode([
            'success' => false,
            'message' => 'Bir hata oluştu. Lütfen daha sonra tekrar deneyin.'
        ]);
        exit();
    }

    // Build the reset URL
    $resetUrl = "https://" . $_SERVER['HTTP_HOST'] . "/lineup_campus/authentication/sign-in/new-password.html?token=" . $token;

    $mailer = new Mailer();


    $emailResult = $mailer->sendPasswordResetEmail($email, $user['username'], $resetUrl);



    if (!$emailResult) {
        // Log the email error
        // error_log("Failed to send password reset email: " . $mailer->getErrorInfo());

        echo json_encode([
            'success' => false,
            'message' => 'Şifre sıfırlama e-postası gönderilemedi. Lütfen daha sonra tekrar deneyin.' . $mailer->getErrorInfo()
        ]);
        exit();
    }

    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'E-posta adresinize şifre sıfırlama bağlantısı gönderildi.'
    ]);

} catch (PDOException $e) {
    // Log the error (to a file, not to response for security)
    error_log("Password reset error: " . $e->getMessage());

    echo json_encode([
        'success' => false,
        'message' => 'Bir hata oluştu. Lütfen daha sonra tekrar deneyin.'
    ]);
} catch (Exception $e) {
    error_log("General error in password reset: " . $e->getMessage());

    echo json_encode([
        'success' => false,
        'message' => 'Bir hata oluştu. Lütfen daha sonra tekrar deneyin.'
    ]);
}
