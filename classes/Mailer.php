<?php
// classes/Mailer.class.php - PHPMailer wrapper class for sending emails

// Make sure to install PHPMailer via Composer:
// composer require phpmailer/phpmailer

/* require '../vendor/autoload.php'; */

$base_dir = __DIR__ . '/../';
set_include_path(get_include_path() . PATH_SEPARATOR . $base_dir);
require 'vendor/autoload.php'; // Sadece dosya adını belirtin

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
        $this->mail->Host = 'mail.lineupcampus.com';               // SMTP server
        $this->mail->SMTPAuth = true;                             // Enable SMTP authentication
        $this->mail->Username = 'eposta@lineupcampus.com';         // SMTP username
        $this->mail->Password = 'Y6RrEZgH4mwfb!x3';                 // SMTP password
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;   // Enable TLS encryption
        $this->mail->Port = 587;                              // TCP port to connect to
        $this->mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
        ];



        // Default sender
        $this->mail->setFrom('eposta@lineupcampus.com', 'Lineup Campus');

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
     * Send a BankTransfer email to Parent
     * 
     * @param string $to Recipient email
     * @param string $username Username
     * @param string $resetLink Password reset link
     * @return bool True if email was sent successfully, false otherwise
     */
    public function sendBankTransferEmail($veli_ad, $veli_soyad, $kullanici_mail, $price)
    {
        $subject = "Havale Bilgileri - Lineup Campus";

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
                .text-center { text-align: center; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>Havale Bekleniyor</h2>
                </div>
                <div class='content'>
                    <p>Merhaba " . htmlspecialchars($veli_ad) . ' ' . htmlspecialchars($veli_soyad) . ",</p>
                    <p>Ödemeniz havale yolu ile bekleniyor.</p>
                    <p>Açıklama kısmına <strong>Öğrencinin Adı Soyadı ve T.C. Kimlik Numarasını</strong> yazmayı unutmayın.</p>
                    <p>Ödemeniz gereken tutar: <strong> $price ₺</strong></p>
                    <p>Teşekkür ederiz.</p>
                    <p style='text-align: center;'>
                        <div class='text-center mt-5'>
                                <h3 >Hesap Bilgileri</h3>
                                <p>Hesap Adı: Lineup </p>
                                <p>IBAN: TR0000000000000000000000</p>
                                <p>Banka: Bankası</p>
                        </div>
                    </p>
                    <p>Saygılarımla,<br>Lineup Takımı</p>
                </div>
                <div class='footer'>
                    <p>Bu otomatik bir e-postadır. Lütfen bu mesaja cevap vermeyin.</p>
                </div>
            </div>
        </body>
        </html>
        ";

        return $this->send($kullanici_mail, $subject, $htmlBody, true);
    }

    /**
     * Send a BankTransfer email To Admin
     * 
     * @param string $to Recipient email
     * @param string $username Username
     * @param string $resetLink Password reset link
     * @return bool True if email was sent successfully, false otherwise
     */
    public function sendBankTransferEmailToAdmin($veli_ad, $veli_soyad, $adminEmail, $price)
    {
        $subject = "Yeni Kayıt (Havale) - Lineup Campus";

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
                .text-center { text-align: center; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>Yeni Kayıt - Havale Bekleniyor</h2>
                </div>
                <div class='content'>
                    <p>Merhaba</p>
                    <p>Yeni bir kullanıcı kayıt oldu.</p>
                    <p>Havale Bekleniyor.</p>
                    <p>Ödenmesi gereken tutar: <strong> $price ₺</strong></p>
                    <p>Saygılarımla,<br>Lineup Takımı</p>
                </div>
                <div class='footer'>
                    <p>Bu otomatik bir e-postadır. Lütfen bu mesaja cevap vermeyin.</p>
                </div>
            </div>
        </body>
        </html>
        ";

        return $this->send($adminEmail, $subject, $htmlBody, true);
    }

    /**
     * Send a BankTransfer approved email to Parent
     * 
     * @param string $to Recipient email
     * @param string $username Username
     * @param string $resetLink Password reset link
     * @return bool True if email was sent successfully, false otherwise
     */
    public function sendBankTransferApprovedEmail($veli_ad, $veli_soyad, $kullanici_mail, $sifreogrenci, $sifreveli, $veliUser, $ogrenciUser)
    {
        $subject = "Havale Onaylandı - Lineup Campus";

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
                .text-center { text-align: center; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>Havale Onaylandı</h2>
                </div>
                <div class='content'>
                    <p>Merhaba " . htmlspecialchars($veli_ad) . ' ' . htmlspecialchars($veli_soyad) . ",</p>
                    <p>Ödemeniz onaylandı.</p>
                    <p>Giriş bilgileriniz aşağıda verilmiştir.</p>
                    <p>Öğrenci Giriş Bilgisi: <br> <b>Kullanıcı adı:</b> $ogrenciUser <br><b>Şifre:</b> $sifreogrenci</p>
                    <p>Veli Giriş Bilgisi: <br> <b>Kullanıcı adı:</b> $veliUser <br><b>Şifre:</b> $sifreveli</p>
                    <p>Saygılarımızla,<br>Lineup Takımı</p>
                </div>
                <div class='footer'>
                    <p>Bu otomatik bir e-postadır. Lütfen bu mesaja cevap vermeyin.</p>
                </div>
            </div>
        </body>
        </html>
        ";

        return $this->send($kullanici_mail, $subject, $htmlBody, true);
    }

    /**
     * Send School Admin Info email
     * 
     * @param string $to Recipient email
     * @param string $username Username
     * @param string $resetLink Password reset link
     * @return bool True if email was sent successfully, false otherwise
     */
    public function sendSchoolAdminEmail($admin_ad, $admin_soyad, $admin_mail, $admin_sifre, $adminUser, $school_name)
    {
        $subject = $school_name . " adlı okula admin olarak atandınız - Lineup Campus";

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
                .text-center { text-align: center; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>$school_name adlı okula admin olarak atandınız</h2>
                </div>
                <div class='content'>
                    <p>Merhaba " . htmlspecialchars($admin_ad) . ' ' . htmlspecialchars($admin_soyad) . ",</p>
                    <p>Giriş bilgileriniz aşağıda verilmiştir.</p>
                    <p>Öğrenci Giriş Bilgisi: <br> <b>Kullanıcı adı:</b> $adminUser <br><b>Şifre:</b> $admin_sifre</p>
                    <p><a href='https://www.oznarmaden.com/lineup'>https://www.oznarmaden.com/lineup</a></p>
                    <p>Saygılarımızla,<br>Lineup</p>
                </div>
                <div class='footer'>
                    <p>Bu otomatik bir e-postadır. Lütfen bu mesaja cevap vermeyin.</p>
                </div>
            </div>
        </body>
        </html>
        ";

        return $this->send($admin_mail, $subject, $htmlBody, true);
    }

    /**
     * Send School Coordinator Info email
     * 
     * @param string $to Recipient email
     * @param string $username Username
     * @param string $resetLink Password reset link
     * @return bool True if email was sent successfully, false otherwise
     */
    public function sendSchoolCoordinatorEmail($coordinator_ad, $coordinator_soyad, $coordinator_mail, $coordinator_sifre, $coordinatorUser, $school_name)
    {
        $subject = $school_name . " adlı okula eğitim koordinatörü olarak atandınız - Lineup Campus";

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
                .text-center { text-align: center; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>$school_name adlı okula eğitim koordinatörü olarak atandınız</h2>
                </div>
                <div class='content'>
                    <p>Merhaba " . htmlspecialchars($coordinator_ad) . ' ' . htmlspecialchars($coordinator_soyad) . ",</p>
                    <p>Giriş bilgileriniz aşağıda verilmiştir.</p>
                    <p>Öğrenci Giriş Bilgisi: <br> <b>Kullanıcı adı:</b> $coordinatorUser <br><b>Şifre:</b> $coordinator_sifre</p>
                    <p><a href='https://www.oznarmaden.com/lineup'>https://www.oznarmaden.com/lineup</a></p>
                    <p>Saygılarımızla,<br>Lineup</p>
                </div>
                <div class='footer'>
                    <p>Bu otomatik bir e-postadır. Lütfen bu mesaja cevap vermeyin.</p>
                </div>
            </div>
        </body>
        </html>
        ";

        return $this->send($coordinator_mail, $subject, $htmlBody, true);
    }

    /**
     * Send School Coordinator Info email
     * 
     * @param string $to Recipient email
     * @param string $username Username
     * @param string $resetLink Password reset link
     * @return bool True if email was sent successfully, false otherwise
     */
    public function sendTeacherEmail($teacher_ad, $teacher_soyad, $teacher_mail, $teacher_sifre, $teacherUser, $school_name)
    {
        $subject = $school_name . " adlı okula öğretmen olarak eklendiniz - Lineup Campus";

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
                .text-center { text-align: center; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>$school_name adlı okula öğretmen olarak eklendiniz</h2>
                </div>
                <div class='content'>
                    <p>Merhaba " . htmlspecialchars($teacher_ad) . ' ' . htmlspecialchars($teacher_soyad) . ",</p>
                    <p>Giriş bilgileriniz aşağıda verilmiştir.</p>
                    <p>Öğrenci Giriş Bilgisi: <br> <b>Kullanıcı adı:</b> $teacherUser <br><b>Şifre:</b> $teacher_sifre</p>
                    <p><a href='https://www.oznarmaden.com/lineup'>https://www.oznarmaden.com/lineup</a></p>
                    <p>Saygılarımızla,<br>Lineup</p>
                </div>
                <div class='footer'>
                    <p>Bu otomatik bir e-postadır. Lütfen bu mesaja cevap vermeyin.</p>
                </div>
            </div>
        </body>
        </html>
        ";

        return $this->send($teacher_mail, $subject, $htmlBody, true);
    }

    /**
     * Send a Login Information email to Parent From Add Student Page
     * 
     * @param string $to Recipient email
     * @param string $username Username
     * @param string $resetLink Password reset link
     * @return bool True if email was sent successfully, false otherwise
     */
    public function sendLoginInfoEmail($veli_ad, $veli_soyad, $kullanici_mail, $sifreogrenci, $sifreveli, $veliUser, $ogrenciUser)
    {
        $subject = "Lineup Campus'e Hoş Geldiniz! - Lineup Campus";

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
                .text-center { text-align: center; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>Lineup Campus'e Hoş Geldiniz!</h2>
                </div>
                <div class='content'>
                    <p>Merhaba " . htmlspecialchars($veli_ad) . ' ' . htmlspecialchars($veli_soyad) . ",</p>
                    <p>Lineup Campus'e Hoş Geldiniz.</p>
                    <p>Giriş bilgileriniz aşağıda verilmiştir.</p>
                    <p>Öğrenci Giriş Bilgisi: <br> <b>Kullanıcı adı:</b> $ogrenciUser <br><b>Şifre:</b> $sifreogrenci</p>
                    <p>Veli Giriş Bilgisi: <br> <b>Kullanıcı adı:</b> $veliUser <br><b>Şifre:</b> $sifreveli</p>
                    <p>Saygılarımızla,<br>Lineup Takımı</p>
                </div>
                <div class='footer'>
                    <p>Bu otomatik bir e-postadır. Lütfen bu mesaja cevap vermeyin.</p>
                </div>
            </div>
        </body>
        </html>
        ";

        return $this->send($kullanici_mail, $subject, $htmlBody, true);
    }


    /**
     * Send a password reset email
     * 
     * @param string $to Recipient email
     * @param string $username Username
     * @param string $verificationCode Password reset link
     * @return bool True if email was sent successfully, false otherwise
     */
    public function sendVerificationCodeForNewEmail($to, $username, $verificationCode)
    {
        $subject = "E-Posta Değiştirme Talebi";

        $htmlBody = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>E-Posta Değiştirme Talebi</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                line-height: 1.6;
                color: #333;
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
            }
            .header {
                text-align: center;
                padding: 20px 0;
                background-color: #f8f9fa;
                border-bottom: 1px solid #e9ecef;
            }
            .content {
                padding: 20px;
                background-color: #fff;
            }
            .verification-code {
                font-size: 24px;
                font-weight: bold;
                text-align: center;
                margin: 20px 0;
                padding: 15px;
                background-color: #f8f9fa;
                border-radius: 5px;
                letter-spacing: 2px;
            }
            .footer {
                margin-top: 20px;
                padding: 20px;
                text-align: center;
                font-size: 12px;
                color: #6c757d;
                background-color: #f8f9fa;
            }
            .button {
                display: inline-block;
                padding: 10px 20px;
                background-color: #007bff;
                color: #fff !important;
                text-decoration: none;
                border-radius: 5px;
                margin: 15px 0;
            }
        </style>
    </head>
    <body>
        <div class='header'>
            <h2>E-Posta Adresinizi Değiştiriyorsunuz</h2>
        </div>
        
        <div class='content'>
            <p>Merhaba $username,</p>
            
            <p>E-posta adresinizi değiştirmek için aşağıdaki doğrulama kodunu kullanabilirsiniz:</p>
            
            <div class='verification-code'>$verificationCode</div>
            
            <p>Bu kod 30 dakika boyunca geçerlidir. Eğer bu talebi siz yapmadıysanız, lütfen bu e-postayı dikkate almayın.</p>
            
            <p>Teşekkür ederiz,</p>
            <p>Lineup Campus Ekibi</p>
        </div>
        
        <div class='footer'>
            <p>© " . date('Y') . " Tüm hakları saklıdır.</p>
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