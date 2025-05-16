<?php
// classes/Mailer.class.php - PHPMailer wrapper class for sending emails

// Make sure to install PHPMailer via Composer:
// composer require phpmailer/phpmailer

require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


class Mailer
{
    private $mail;

    /**
     * Constructor - Set up PHPMailer with default configuration
     */
    public function __construct()
    {

        // Create a new PHPMailer instance
        $this->mail = new PHPMailer(true); // true enables exceptions

        // Server settings
        $this->mail->isSMTP();
        $this->mail->Host = 'zm35.ihszimbra.com';               // SMTP server
        $this->mail->SMTPAuth = true;                             // Enable SMTP authentication
        $this->mail->Username = 'aepikman@chemitech.com.tr';         // SMTP username
        $this->mail->Password   = '01051913BBo!';                 // SMTP password
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;   // Enable TLS encryption
        $this->mail->Port = 587;                              // TCP port to connect to



        // Default sender
        $this->mail->setFrom('aepikman@chemitech.com.tr', 'Lineup Campus');

        // Default charset and encoding
        $this->mail->CharSet = 'UTF-8';
        $this->mail->Encoding = 'base64';

        // Set default timeout
        $this->mail->Timeout = 30;
    }

    /**
     * Send an email
     * 
     * @param string|array $to Recipient email address(es)
     * @param string $subject Email subject
     * @param string $body Email body
     * @param bool $isHtml Whether the body is HTML (default: false)
     * @param array $attachments Optional array of attachments [path => name]
     * @param array $cc Optional array of CC recipients
     * @param array $bcc Optional array of BCC recipients
     * @return bool True if email was sent successfully, false otherwise
     */
    public function send($to, $subject, $body, $isHtml = false, $attachments = [], $cc = [], $bcc = [])
    {
        try {
            // Clear all recipients and attachments (in case this instance is reused)
            $this->mail->clearAllRecipients();
            $this->mail->clearAttachments();

            // Set recipients
            if (is_array($to)) {
                foreach ($to as $address) {
                    $this->mail->addAddress($address);
                }
            } else {
                $this->mail->addAddress($to);
            }

            // Add CC recipients
            if (!empty($cc)) {
                foreach ($cc as $address) {
                    $this->mail->addCC($address);
                }
            }

            // Add BCC recipients
            if (!empty($bcc)) {
                foreach ($bcc as $address) {
                    $this->mail->addBCC($address);
                }
            }

            // Set email format
            $this->mail->isHTML($isHtml);

            // Set subject and body
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;

            // Set plain text body if HTML is enabled
            if ($isHtml) {
                $this->mail->AltBody = strip_tags($body);
            }

            // Add attachments
            if (!empty($attachments)) {
                foreach ($attachments as $path => $name) {
                    $this->mail->addAttachment($path, $name);
                }
            }

            // Send the email
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            // Log the error
            error_log("Email sending failed: " . $this->mail->ErrorInfo);
            return false;
        }
    }

    /**
     * Send a password reset email
     * 
     * @param string $to Recipient email
     * @param string $username Username
     * @param string $resetLink Password reset link
     * @return bool True if email was sent successfully, false otherwise
     */
    public function sendPasswordResetEmail($to, $username, $resetLink)
    {
        $subject = "Şifre Sıfırlama Talebi";

        // HTML body
        $htmlBody = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #f8f9fa; padding: 15px; text-align: center; border-radius: 5px 5px 0 0; }
                .content { background-color: #ffffff; padding: 20px; border-radius: 0 0 5px 5px; }
                .button { display: inline-block; padding: 10px 20px; background-color:rgb(166, 209, 255); color: #ffffff; text-decoration: none; border-radius: 5px; }
                .footer { margin-top: 20px; font-size: 12px; color: #6c757d; text-align: center; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>Şifre Sıfırlama Talebi</h2>
                </div>
                <div class='content'>
                    <p>Merhaba " . htmlspecialchars($username) . ",</p>
                    <p>Şifrenizi sıfırlamanız için bir istek aldık. Eğer bu isteği siz yapmadıysanız, bu e-postayı yok sayabilirsiniz.</p>
                    <p>Şifrenizi sıfırlamak için lütfen aşağıdaki butona tıklayın:</p>
                    <p style='text-align: center;'>
                        <a href='" . htmlspecialchars($resetLink) . "' class='button'>Şifreyi Sıfırla</a>
                    </p>
                    <p>Veya aşağıdaki bağlantıyı kopyalayıp tarayıcınıza yapıştırın:</p>
                    <p>" . htmlspecialchars($resetLink) . "</p>
                    <p>Güvenlik nedeniyle bu bağlantı 1 saat sonra geçerliliğini yitirecektir.</p>
                    <p>Saygılarımla,<br>Lineup Takımı</p>
                </div>
                <div class='footer'>
                    <p>Bu otomatik bir e-postadır. Lütfen bu mesaja cevap vermeyin.</p>
                </div>
            </div>
        </body>
        </html>
        ";

        return $this->send($to, $subject, $htmlBody, true);
    }

    /**
     * Set custom SMTP configuration
     * 
     * @param array $config SMTP configuration
     * @return void
     */
    public function setSmtpConfig($config)
    {
        if (isset($config['host']))
            $this->mail->Host = $config['host'];
        if (isset($config['port']))
            $this->mail->Port = $config['port'];
        if (isset($config['username']))
            $this->mail->Username = $config['username'];
        if (isset($config['password']))
            $this->mail->Password = $config['password'];
        if (isset($config['secure']))
            $this->mail->SMTPSecure = $config['secure'];
        if (isset($config['from_email']) && isset($config['from_name'])) {
            $this->mail->setFrom($config['from_email'], $config['from_name']);
        }
    }

    /**
     * Get error info if email sending fails
     * 
     * @return string Error information
     */
    public function getErrorInfo()
    {
        return $this->mail->ErrorInfo;
    }
}