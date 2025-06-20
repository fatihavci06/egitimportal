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
        $this->mail->setFrom('eposta@lineupcampus.com', 'LineUp Campus');

        // Default charset and encoding
        $this->mail->CharSet = 'UTF-8';
        $this->mail->Encoding = 'base64';

        // Set default timeout
        $this->mail->Timeout = 25;
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
                .header { background-color: #fa6000; color: #ffffff; padding: 15px; text-align: center; border-radius: 5px 5px 0 0; }
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
        $subject = "LineUp Campus Ödeme Bilgileri - Kaydınız Tamamlanmak Üzere!";

        // HTML body
        $htmlBody = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; }
                .container { margin: 0 auto; padding: 20px; }
                .content { background-color: #ffffff; padding: 20px; border-radius: 0 0 5px 5px; }
                .button { display: inline-block; padding: 10px 20px; background-color:rgb(166, 209, 255); color: #ffffff; text-decoration: none; border-radius: 5px; }
                .footer { margin-top: 20px; font-size: 12px; color: #6c757d; text-align: center; }
                .text-center { text-align: center; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='content'>
                    <p><img src='https://lineupcampus.com/online/assets/media/mascots/lineup-robot-maskot.png' alt='Logo' style='width: 200px; height: auto;'></p>
                    <p>Merhaba Sevgili Velimiz,</p>
                    <p>LineUp Campus'e gösterdiğiniz ilgi için çok teşekkür ederiz! Eğitim paketimiz için havale ile ödeme seçeneğini tercih ettiğinizi görüyoruz. Minik öğrencimizin eğlenceli ve verimli öğrenme yolculuğuna en kısa sürede başlaması için son adımı tamamlamak üzereyiz.</p>
                    <p>Ödemenizi aşağıdaki banka bilgilerimize yapabilirsiniz:</p>
                    <p>
                        <div class='mt-5'>
                                <p>Banka Adı: [Banka Adı]</p>
                                <p>Şube Adı/Kodu: [Şube Adı/Kodu]</p>
                                <p>Hesap Adı: [Şirketinizin Tam Adı - Örn: Line Up Campus Eğitim Hizmetleri A.Ş.]</p>
                                <p>IBAN Numarası: TR [XX XXXXXXXXXXXXXXXXXXXXXX]</p>
                                <p>Ödemeniz gereken tutar: <strong> $price ₺</strong></p>
                        </div>
                    </p>
                    <p>Açıklama Kısmı: Lütfen havale açıklama kısmına Öğrencinin Adı Soyadı T.C. Kimlik Numarası ve E-Posta adresi bilgilerini yazmayı unutmayınız. Bu, ödemenizi hızlıca eşleştirmemizi sağlayacaktır.</p>
                    <p>Ödemeniz tarafımıza ulaştığında, üyeliğiniz en kısa sürede aktif edilecek ve size bir onay e-postası daha göndereceğiz.</p>
                    <p>Herhangi bir sorunuz veya yardıma ihtiyacınız olursa, <a href='tel:02323320390'>0 232 332 03 90</a> numaralı telefondan veya <a href='mailto:info@lineupcampus.com'>info@lineupcampus.com</a> üzerinden bize ulaşmaktan lütfen çekinmeyin.</p>
                    <p>Minik kaşifinizle yakında LineUp Campus dünyasında buluşmak dileğiyle!</p>
                    <p>Sevgi ve Saygılarımızla,<br>LineUp Campus Ekibi</p>
                </div>
                <div class='footer'>
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
        $subject = "Yeni Kayıt (Havale) - LineUp Campus";

        // HTML body
        $htmlBody = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #fa6000; color: #ffffff; padding: 15px; text-align: center; border-radius: 5px 5px 0 0; }
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
        $subject = "Havale Onaylandı - LineUp Campus";

        // HTML body
        $htmlBody = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #fa6000; color: #ffffff; padding: 15px; text-align: center; border-radius: 5px 5px 0 0; }
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
        $subject = $school_name . " adlı okula admin olarak atandınız - LineUp Campus";

        // HTML body
        $htmlBody = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #fa6000; color: #ffffff; padding: 15px; text-align: center; border-radius: 5px 5px 0 0; }
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
        $subject = $school_name . " adlı okula eğitim koordinatörü olarak atandınız - LineUp Campus";

        // HTML body
        $htmlBody = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #fa6000; color: #ffffff; padding: 15px; text-align: center; border-radius: 5px 5px 0 0; }
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
        $subject = $school_name . " adlı okula öğretmen olarak eklendiniz - LineUp Campus";

        // HTML body
        $htmlBody = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #fa6000; color: #ffffff; padding: 15px; text-align: center; border-radius: 5px 5px 0 0; }
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
     * Send a Support email To Admin
     * 
     * @param string $to Recipient email
     * @param string $username Username
     * @param string $resetLink Password reset link
     * @return bool True if email was sent successfully, false otherwise
     */
    public function sendSupportEmailToAdmin($subjectofsupport, $title, $comment, $name, $surname, $adminEmail)
    {
        $subject = "Yeni Destek Talebi - LineUp Campus";

        // HTML body
        $htmlBody = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; }
                .container { margin: 0 auto; padding: 20px; }
                .content { background-color: #ffffff; padding: 20px; border-radius: 0 0 5px 5px; }
                .button { display: inline-block; padding: 10px 20px; background-color:rgb(166, 209, 255); color: #ffffff; text-decoration: none; border-radius: 5px; }
                .footer { margin-top: 20px; font-size: 12px; color: #6c757d; text-align: center; }
                .text-center { text-align: center; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>Yeni Destek Talebi - $subjectofsupport</h2>
                </div>
                <div class='content'>
                    <p>Merhaba</p>
                    <p>$name $surname isimli kullanıcı \"$title\" başlıklı bir destek talebinde bulundu.</p>
                    <p>Kullanıcıya sistem üzerinden cevap verebilirsiniz.</p>
                    <p>Saygılarımızla,<br>LineUp Campus</p>
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
     * Send a Support Response email
     * 
     * @param string $to Recipient email
     * @param string $username Username
     * @param string $resetLink Password reset link
     * @return bool True if email was sent successfully, false otherwise
     */
    public function sendSupportResponseEmail($subjectofsupport, $title, $comment, $name, $surname, $adminEmail)
    {
        $subject = "Destek Talebi Yanıtı - LineUp Campus";

        // HTML body
        $htmlBody = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; }
                .container { margin: 0 auto; padding: 20px; }
                .content { background-color: #ffffff; padding: 20px; border-radius: 0 0 5px 5px; }
                .button { display: inline-block; padding: 10px 20px; background-color:rgb(166, 209, 255); color: #ffffff; text-decoration: none; border-radius: 5px; }
                .footer { margin-top: 20px; font-size: 12px; color: #6c757d; text-align: center; }
                .text-center { text-align: center; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>Destek Talebi Yanıtı - $subjectofsupport</h2>
                </div>
                <div class='content'>
                    <p>Merhaba</p>
                    <p>\"$title\" başlıklı destek talebine yanıt verilmiştir.</p>
                    <p>Destek talebinin detaylarını sistem üzerinden görebilirsiniz.</p>
                    <p>Saygılarımızla,<br>LineUp Campus</p>
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
     * Send a Support Complete email
     * 
     * @param string $to Recipient email
     * @param string $username Username
     * @param string $resetLink Password reset link
     * @return bool True if email was sent successfully, false otherwise
     */
    public function sendSupportCompleteEmail($title, $adminEmail)
    {
        $subject = "Destek Talebi Çözümlendi - LineUp Campus";

        // HTML body
        $htmlBody = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; }
                .container { margin: 0 auto; padding: 20px; }
                .content { background-color: #ffffff; padding: 20px; border-radius: 0 0 5px 5px; }
                .button { display: inline-block; padding: 10px 20px; background-color:rgb(166, 209, 255); color: #ffffff; text-decoration: none; border-radius: 5px; }
                .footer { margin-top: 20px; font-size: 12px; color: #6c757d; text-align: center; }
                .text-center { text-align: center; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>Destek Talebi Çözümlendi - $title</h2>
                </div>
                <div class='content'>
                    <p>Merhaba</p>
                    <p>\"$title\" başlıklı destek talebi çözümlenmiştir.</p>
                    <p>Saygılarımızla,<br>LineUp Campus</p>
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
     * Send a Login Information email to Parent From Add Student Page
     * 
     * @param string $to Recipient email
     * @param string $username Username
     * @param string $resetLink Password reset link
     * @return bool True if email was sent successfully, false otherwise
     */
    public function sendLoginInfoEmail($veli_ad, $veli_soyad, $kullanici_mail, $sifreogrenci, $sifreveli, $veliUser, $ogrenciUser)
    {
        $subject = "LineUp Campus'e Hoş Geldiniz! - LineUp Campus";

        // HTML body
        $htmlBody = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #fa6000; color: #ffffff; padding: 15px; text-align: center; border-radius: 5px 5px 0 0; }
                .content { background-color: #ffffff; padding: 20px; border-radius: 0 0 5px 5px; }
                .button { display: inline-block; padding: 10px 20px; background-color:rgb(166, 209, 255); color: #ffffff; text-decoration: none; border-radius: 5px; }
                .footer { margin-top: 20px; font-size: 12px; color: #6c757d; text-align: center; }
                .text-center { text-align: center; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>LineUp Campus'e Hoş Geldiniz!</h2>
                </div>
                <div class='content'>
                    <p>Merhaba " . htmlspecialchars($veli_ad) . ' ' . htmlspecialchars($veli_soyad) . ",</p>
                    <p>LineUp Campus'e Hoş Geldiniz.</p>
                    <p>Giriş bilgileriniz aşağıda verilmiştir.</p>
                    <p>Öğrenci Giriş Bilgisi: <br> <b>Kullanıcı adı:</b> $ogrenciUser <br><b>Şifre:</b> $sifreogrenci</p>
                    <p>Veli Giriş Bilgisi: <br> <b>Kullanıcı adı:</b> $veliUser <br><b>Şifre:</b> $sifreveli</p>
                    <p>Saygılarımızla,<br>LineUp Takımı</p>
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
                background-color: #fa6000; color: #ffffff;
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
                background-color: #fa6000; color: #ffffff;
                border-radius: 5px;
                letter-spacing: 2px;
            }
            .footer {
                margin-top: 20px;
                padding: 20px;
                text-align: center;
                font-size: 12px;
                color: #6c757d;
                background-color: #fa6000; color: #ffffff;
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
            <p>LineUp Campus Ekibi</p>
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