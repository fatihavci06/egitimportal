<?php

session_start();
define('GUARD', true);

require_once('iyzico/config.php');

require_once('classes/dateformat.classes.php');
require_once('classes/dbh.classes.php');
require_once('classes/adduser.classes.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

function gucluSifreUret($uzunluk = 12)
{
    $karakterler = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+';
    $karakterUzunlugu = strlen($karakterler);
    $sifre = '';

    $ozelKarakterVar = false;
    $harfVar = false;
    $rakamVar = false;

    for ($i = 0; $i < $uzunluk; $i++) {
        $rastgeleIndex = rand(0, $karakterUzunlugu - 1);
        $rastgeleKarakter = $karakterler[$rastgeleIndex];
        $sifre .= $rastgeleKarakter;

        if (preg_match('/[^a-zA-Z0-9]/', $rastgeleKarakter)) {
            $ozelKarakterVar = true;
        } elseif (ctype_alpha($rastgeleKarakter)) {
            $harfVar = true;
        } elseif (ctype_digit($rastgeleKarakter)) {
            $rakamVar = true;
        }
    }

    // Gerekli karakter türlerinin olduğundan emin ol
    if (!$ozelKarakterVar || !$harfVar || !$rakamVar) {
        return gucluSifreUret($uzunluk); // Eksik karakter varsa yeniden üret
    }

    return $sifre;
}



$firstName = $_SESSION['firstName'];
$lastName = $_SESSION['lastName'];
$username = $_SESSION['username'];
$gender = $_SESSION['gender'];
$birth_day = $_SESSION['birth_day'];
$class = $_SESSION['classes'];

$kullanici_ad = $_SESSION['parentFirstName'];
$kullanici_soyad = $_SESSION['parentLastName'];
$kullanici_gsm = $_SESSION['telephone'];
$kullanici_mail = $_SESSION['email'];
$kullanici_tckn = $_SESSION['tckn'];
$kullanici_zaman = date('Y-m-d H:i:s');
$kullanici_adresiyaz = $_SESSION['address'];
$kullanici_il = $_SESSION['city'];
$postcode = $_SESSION['postcode'];
$pack = $_SESSION['pack'];
$district = $_SESSION['district'];

include_once "classes/packages.classes.php";

$package = new Packages();

$token = $_POST['token'];

$request = new \Iyzipay\Request\RetrieveCheckoutFormRequest();
$request->setLocale(\Iyzipay\Model\Locale::TR);
$request->setToken("$token");
$checkoutForm = \Iyzipay\Model\CheckoutForm::retrieve($request, Config::options());

$odeme_durum = $checkoutForm->getPaymentStatus();

$paidPrice = $checkoutForm->getPaidPrice();

$commissionRate = $checkoutForm->getiyziCommissionRateAmount();

$commissionFee = $checkoutForm->getiyziCommissionFee();

if ($odeme_durum == "FAILURE") {

    $text = '<div class="text-gray-500 fw-semibold fs-4"> Ödeme alınamadı. Hesap oluşturulamadı! </div>';

    session_destroy();
} elseif ($odeme_durum == "SUCCESS") {

    $packInfo = $package->getPackagePrice(htmlspecialchars(trim($_SESSION['pack'])));

    foreach ($packInfo as $key => $value) {
        $period = $value['subscription_period'];
    }

    $dateformat = new DateFormat();

    $birth_dayDb = $dateformat->forDB($birth_day);

    $yeniSifre = gucluSifreUret(15);

    $yeniSifre2 = gucluSifreUret(15);

    $password = password_hash($yeniSifre, PASSWORD_DEFAULT);

    $password2 = password_hash($yeniSifre2, PASSWORD_DEFAULT);

    $suAn = new DateTime();

    $bitis = $suAn->modify('+' . $period . ' month');

    $nowTime = date('Y-m-d H:i:s');

    $endTime = $bitis->format('Y-m-d H:i:s');

    $kisiekle = new AddUser();

    $username2 = $username . '-veli';

    $mail = new PHPMailer(true);

try {
    // Sunucu Ayarları
    $mail->SMTPDebug = SMTP::DEBUG_OFF;                      // Geliştirme için detaylı hata çıktısı (DEBUG_SERVER veya DEBUG_CONNECTION)
    $mail->isSMTP();                                            // SMTP kullanarak gönder
    $mail->Host       = 'zm35.ihszimbra.com';                     // SMTP sunucunuz (örneğin, mail.alanadiniz.com)
    $mail->SMTPAuth   = true;                      // SMTP parolanız
    $mail->SMTPSecure = false;         // TLS şifrelemesi (SSL için PHPMailer::ENCRYPTION_SMTPS kullanın)
    $mail->SMTPAutoTLS = false;                                 // SMTP kimlik doğrulaması gerekli
    $mail->Username   = 'aepikman@chemitech.com.tr';                 // SMTP kullanıcı adınız
    $mail->Password   = '01051913BBo!';   
    $mail->Port       = 587;                                    // TLS için port (SSL için genellikle 465)
    $mail->CharSet    = 'UTF-8';                                // Karakter kodlaması

    // Alıcılar
    $mail->setFrom('aepikman@chemitech.com.tr', 'Gönderen Adı');
    $mail->addAddress($kullanici_mail, 'Alıcı Adı');     // İsteğe bağlı olarak isim ekleyebilirsiniz
    //$mail->addCC('cc@baskaalan.com');
    //$mail->addBCC('bcc@baskaalan.com');

    // Ekler (isteğe bağlı)
    //$mail->addAttachment('/var/tmp/file.tar.gz');
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // İsteğe bağlı olarak farklı bir isimle

    // İçerik
    $mail->isHTML(true);                                  // E-posta içeriği HTML formatında mı
    $mail->Subject = 'Giriş Bilgileriniz';
    $mail->Body    = 'Öğrenci Giriş Bilgisi: <br> Kullanıcı adı: ' . $username . ' <br> Şifre: ' . $yeniSifre . ' <br> <br>';
    $mail->AltBody = 'Bu da HTML desteklemeyen istemciler için düz metin içeriğidir.';

    $mail->send();
    //echo 'E-posta başarıyla gönderildi!';
} catch (Exception $e) {
    echo "E-posta gönderilirken bir hata oluştu: {$mail->ErrorInfo}";
}

   

    $gonder = $kisiekle->setStudent2($firstName, $lastName, $username, $kullanici_tckn, $gender, $birth_dayDb, $kullanici_mail, $class, $pack, $password, $nowTime, $endTime, $kullanici_gsm, $kullanici_adresiyaz, $district, $postcode, $kullanici_il);


    $gonderVeli = $kisiekle->setParent($kullanici_ad, $kullanici_soyad, $username2, $password2);

    $text =  '<div class="text-gray-500 fw-semibold fs-4">Giriş bilgileriniz e-posta adresinize gönderildi. <br>
				<a href="index" class="link-primary fw-bold">Giriş yap</a>
			</div>';

            session_destroy();
}


?>
<!DOCTYPE html>
<html lang="tr">
<?php
include_once "views/pages-head.php";
?>
<!--begin::Body-->

<body id="kt_body" class="app-blank">
    <!--begin::Theme mode setup on page load-->
    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>
    <!--end::Theme mode setup on page load-->
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <!--begin::Authentication - Sign-in -->
        <div class="d-flex flex-column flex-lg-row flex-column-fluid">
            <!--begin::Aside-->
            <div class="d-flex flex-column flex-lg-row-auto bg-primary w-xl-600px positon-xl-relative">
                <!--begin::Wrapper-->
                <div class="d-flex flex-column position-xl-fixed top-0 bottom-0 w-xl-600px scroll-y">
                    <!--begin::Header-->
                    <div class="d-flex flex-row-fluid flex-column text-center p-5 p-lg-10 pt-lg-20">
                        <!--begin::Logo-->
                        <a href="index.html" class="py-2 py-lg-20">
                            <img alt="Logo" src="assets/media/logos/lineup-campus-logo.jpg" class="h-100px h-lg-150px" />
                        </a>
                        <!--end::Logo-->
                        <!--begin::Title-->
                        <h1 class="d-none d-lg-block fw-bold text-white fs-2qx pb-5 pb-md-10">Lineup Campus'e Hoş Geldiniz</h1>
                        <!--end::Title-->
                        <!--begin::Description-->
                        <!--<p class="d-none d-lg-block fw-semibold fs-2 text-white">Plan your blog post by choosing a topic creating 
							<br />an outline and checking facts</p>-->
                        <!--end::Description-->
                    </div>
                    <!--end::Header-->
                    <!--begin::Illustration-->
                    <!--<div class="d-none d-lg-block d-flex flex-row-auto bgi-no-repeat bgi-position-x-center bgi-size-contain bgi-position-y-bottom min-h-100px min-h-lg-350px" style="background-image: url(assets/media/illustrations/illustration/lineup-home.svg)"></div>-->
                    <!--end::Illustration-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--begin::Aside-->
            <!--begin::Body-->
            <div class="d-flex flex-column flex-lg-row-fluid py-10">
                <!--begin::Content-->
                <div class="d-flex flex-center flex-column flex-column-fluid">
                    <!--begin::Wrapper-->
                    <div class="w-lg-700px p-10 p-lg-15 mx-auto">
                        <!--begin::Checkout-->
                        <?php echo $text; ?>
                        <!--end::Checkout-->
                    </div>
                    <!--end::Wrapper-->
                </div>
                <!--end::Content-->
                <!--begin::Footer-->
                <div class="d-flex flex-center flex-wrap fs-6 p-5 pb-0">
                    <!--begin::Links-->
                    <div class="d-flex flex-center fw-semibold fs-6">
                    </div>
                    <!--end::Links-->
                </div>
                <!--end::Footer-->
            </div>
            <!--end::Body-->
        </div>
        <!--end::Authentication - Sign-up-->
    </div>
    <!--end::Root-->
    <!--begin::Javascript-->
    <script>
        var hostUrl = "assets/";
    </script>
    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="assets/plugins/global/plugins.bundle.js"></script>
    <script src="assets/js/scripts.bundle.js"></script>
    <!--end::Global Javascript Bundle-->
    <!--begin::Custom Javascript(used for this page only)-->
    <script src="assets/js/custom/authentication/kayit-ol/general.js"></script>
    <!--end::Custom Javascript-->
    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>