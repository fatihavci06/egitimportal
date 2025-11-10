<?php

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

session_start();
define('GUARD', true);

if (!isset($_SESSION['firstName'])) {
    header("location: ../index");
}

/* if (!isset($_SESSION['parentFirstName'])) {
    header("location: index");
} */

//require_once('iyzico/config.php');

require_once('../classes/dateformat.classes.php');
require_once('../classes/dbh.classes.php');
require_once('../classes/adduser.classes.php');
require_once('../classes/userslist.classes.php');
include_once('../classes/packages.classes.php');
include_once('../classes/classes.classes.php');
include_once "../classes/classes-view.classes.php";

$package = new Packages();

$chooseClass = new ShowClass();

$packInfo = $package->getPackagePrice(htmlspecialchars(trim($_SESSION['pack'])));

$coupon = $package->checkCoupon($_SESSION['couponCode']);

if ($coupon) {
	$discount_value = $coupon['discount_value'];
	$discount_type = $coupon['discount_type'];
}
foreach ($packInfo as $key => $value) {
	$packageName = $value['name'];
	//$price = $value['monthly_fee'] * $value['subscription_period'];
	$price = $value['credit_card_fee'];
	if ($discount_type === 'amount') {
		$price -= $discount_value;
	} else if ($discount_type === 'percentage') {
		$price -= $price * ($discount_value / 100);
	}
}


$vat = $package->getVat();

$paidPrice = $_SESSION['paidPrice'];

$vat = $vat['tax_rate'];  // KDV oranı
$priceWithoutVat = $price / (1 + ($vat / 100)); // KDV'siz Fiyatı Bulma
// $price += $price * ($vat / 100); // KDV'yi ekle
// $vatAmount = $price * ($vat / 100); // KDV tutarını hesapla
$vatAmount = $price - $priceWithoutVat; // KDV tutarın


$priceWithoutVat = round($priceWithoutVat, 2); // 2 basamaklı yuvarla

$_SESSION['vatAmount'] = round($vatAmount, 2);
$_SESSION['vat'] = $vat;


$admin = new User();
$getadminEmail = $admin->getlnpAdmin();
$adminEmail = $getadminEmail[0]['email'];

$className = new Classes();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

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
if($class == 10 OR $class == 11 OR $class == 12) {
    $role = 10002;
    $parentRole = 10005;
} else {
    $role = 2;
    $parentRole = 5;
}

$className = $className->getClassByLesson($class);
$className = $className['name'];

$veli_ad = $_SESSION['parentFirstName'];
$veli_soyad = $_SESSION['parentLastName'];
$kullanici_gsm = $_SESSION['telephone'];
$kullanici_mail = $_SESSION['email'];
$kullanici_tckn = $_SESSION['tckn'];
$kullanici_zaman = date('Y-m-d H:i:s');
$kullanici_adresiyaz = $_SESSION['address'];
$kullanici_il = $_SESSION['city'];
$postcode = $_SESSION['postcode'];
$pack = $_SESSION['pack'];
$district = $_SESSION['district'];
$packUse = $_SESSION['packUse'];
$siparis_numarasi = $_SESSION['orderId'];
$isinstallment = $_SESSION['isinstallment'];
$couponCode = $_SESSION['couponCode'];


$vatAmount = $_SESSION['vatAmount'];
$vat = $_SESSION['vat'];

include_once "../classes/packages.classes.php";

$package = new Packages();

/*$token = $_POST['token'];
 
$request = new \Iyzipay\Request\RetrieveCheckoutFormRequest();
$request->setLocale(\Iyzipay\Model\Locale::TR);
$request->setToken("$token");
$checkoutForm = \Iyzipay\Model\CheckoutForm::retrieve($request, Config::options()); */

/* $odeme_durum = $checkoutForm->getPaymentStatus(); */

/* $paidPrice = $checkoutForm->getPaidPrice(); */



/* $commissionRate = $checkoutForm->getiyziCommissionRateAmount();

$commissionFee = $checkoutForm->getiyziCommissionFee();
 */
$commissionRate = NULL;

$commissionFee = NULL;

/* if ($odeme_durum == "FAILURE") {

    $text = '<div class="text-gray-500 fw-semibold fs-4"> Ödeme alınamadı. Hesap oluşturulamadı! </div>';

    session_destroy();
} elseif ($odeme_durum == "SUCCESS") { */

    $packInfo = $package->getPackagePrice(htmlspecialchars(trim($_SESSION['pack'])));

    foreach ($packInfo as $key => $value) {
        $period = $value['subscription_period'];
        $packName = $value['name'];
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
        $baslik = "LineUp Campus Ailesi Hoş Geldin Minik Kaşifimiz!";
        $mesaj = "Merhaba Sevgili Velimiz, 
        <br> LineUp Campus ailesine katıldığınız için çok mutluyuz! Minik öğrencinizle birlikte heyecan dolu bir öğrenme yolculuğuna çıkmaya hazır mısınız? 
        <br><br> Okul öncesi ve ilkokul öğrencilerimiz için özel olarak hazırladığımız Eğlenceli ve Eğitici Konu Anlatımlı Animasyonlarımız, 3D Bilim Videolarımız, Okuma Yazma Etkinliklerimiz ve Eğlenceli Online Oyunlarımızla öğrenmek hiç bu kadar keyifli olmamıştı.
        <br><br> <b>Kullanıcı Bilgileriniz:</b>
        <br><br> <b>Öğrenci Giriş Bilgisi:</b> <br> Kullanıcı adı: " . $username . " <br> <b>Şifre:</b> " . $yeniSifre . " 
        <br><br> <b>Veli Giriş Bilgisi:</b> <br> Kullanıcı adı: " . $username2 . " <br> <b>Şifre:</b> " . $yeniSifre2 . " 
        <br> <br> Kullanıcı bilgilerinizle <a href='https://lineupcampus.com/online' target='_blank'>https://lineupcampus.com/online</a> adresinden giriş yaparak, çocuğunuz için özel olarak tasarlanmış eğitim dünyamızı keşfedebilirsiniz.
        <br> <br> Her türlü soru ve desteğiniz için <a href='tel:02323320390'>0 232 332 03 90</a> numaralı telefondan veya <a href='mailto:info@lineupcampus.com'>info@lineupcampus.com</a> üzerinden bize ulaşmaktan çekinmeyin.
        <br> <br>LineUp Campus ailesi olarak, minik kaşifimize başarılarla dolu, eğlenceli ve aydınlık bir öğrenme süreci dileriz!
        <br><br> Sevgi ve neşeyle, <br> LineUp Campus Ekibi 
        ";
        $sirket_adi = "LineUp Campus";

        $mail_icerigi = file_get_contents('../views/email-template.html');

        $mail_icerigi = str_replace('{{baslik}}', $baslik, $mail_icerigi);
        $mail_icerigi = str_replace('{{mesaj}}', $mesaj, $mail_icerigi);
        $mail_icerigi = str_replace('{{sirket_adi}}', $sirket_adi, $mail_icerigi);

        // Sunucu Ayarları
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                      // Geliştirme için detaylı hata çıktısı (DEBUG_SERVER veya DEBUG_CONNECTION)
        $mail->isSMTP();                                            // SMTP kullanarak gönder
        $mail->Host       = 'mail.lineupcampus.com';                     // SMTP sunucunuz (örneğin, mail.alanadiniz.com)
        $mail->SMTPAuth   = true;                      // SMTP parolanız
        $mail->SMTPSecure = false;         // TLS şifrelemesi (SSL için PHPMailer::ENCRYPTION_SMTPS kullanın)
        $mail->SMTPAutoTLS = false;                                 // SMTP kimlik doğrulaması gerekli
        $mail->Username   = 'eposta@lineupcampus.com';                 // SMTP kullanıcı adınız
        $mail->Password   = 'Y6RrEZgH4mwfb!x3';
        $mail->Port       = 587;                                    // TLS için port (SSL için genellikle 465)
        $mail->CharSet    = 'UTF-8';                                // Karakter kodlaması

        // Alıcılar
        $mail->setFrom('eposta@lineupcampus.com', 'LineUp Campus');
        $mail->addAddress($kullanici_mail, $veli_ad . ' ' . $veli_soyad);     // İsteğe bağlı olarak isim ekleyebilirsiniz
        //$mail->addCC('cc@baskaalan.com');
        //$mail->addBCC('bcc@baskaalan.com');

        // Ekler (isteğe bağlı)
        //$mail->addAttachment('/var/tmp/file.tar.gz');
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // İsteğe bağlı olarak farklı bir isimle

        // İçerik
        $mail->isHTML(true);                                  // E-posta içeriği HTML formatında mı
        $mail->Subject = $baslik;
        /*$mail->Body    = 'Öğrenci Giriş Bilgisi: <br> Kullanıcı adı: ' . $username . ' <br> Şifre: ' . $yeniSifre . ' <br> <br> 
    Veli Giriş Bilgisi: <br> Kullanıcı adı: ' . $username2 . ' <br> Şifre: ' . $yeniSifre2 . ' <br> <br> <a href="https://lineupcampus.com">Lineup Campus</a>';*/
        //$mail->AltBody = 'Bu da HTML desteklemeyen istemciler için düz metin içeriğidir.';
        $mail->Body    = $mail_icerigi;
        $mail->AltBody = strip_tags($mail_icerigi);

        $mail->send();
        unset($mail);
        $mail_icerigi = "";
        //echo 'E-posta başarıyla gönderildi!';
    } catch (Exception $e) {
        echo "E-posta gönderilirken bir hata oluştu: {$mail->ErrorInfo}";
    }

    $mail = new PHPMailer(true);

    try {
        $baslik = "Yeni Üye Kaydı!";
        $mesaj = "Merhaba, 
        <br> Yeni bir üye kaydoldu. 
        <br><br> Öğrenci Bilgisi: 
        <br> Adı Soyadı: $firstName $lastName 
        <br> <br> Veli Bilgisi: 
        <br> Adı Soyadı: $veli_ad $veli_soyad 
        <br> <br> Telefon: $kullanici_gsm  
        <br> <br> E-posta: $kullanici_mail
        <br> <br> Sınıf Bilgisi: $className   
        <br> <br> Paket Bilgisi: $packName <br> <br>";
        $sirket_adi = "LineUp Campus";

        $mail_icerigi = file_get_contents('../views/email-template-admin.html');

        $mail_icerigi = str_replace('{{baslik}}', $baslik, $mail_icerigi);
        $mail_icerigi = str_replace('{{mesaj}}', $mesaj, $mail_icerigi);
        $mail_icerigi = str_replace('{{sirket_adi}}', $sirket_adi, $mail_icerigi);
        // Sunucu Ayarları
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                      // Geliştirme için detaylı hata çıktısı (DEBUG_SERVER veya DEBUG_CONNECTION)
        $mail->isSMTP();                                            // SMTP kullanarak gönder
        $mail->Host       = 'mail.lineupcampus.com';                     // SMTP sunucunuz (örneğin, mail.alanadiniz.com)
        $mail->SMTPAuth   = true;                      // SMTP parolanız
        $mail->SMTPSecure = false;         // TLS şifrelemesi (SSL için PHPMailer::ENCRYPTION_SMTPS kullanın)
        $mail->SMTPAutoTLS = false;                                 // SMTP kimlik doğrulaması gerekli
        $mail->Username   = 'eposta@lineupcampus.com';                 // SMTP kullanıcı adınız
        $mail->Password   = 'Y6RrEZgH4mwfb!x3';
        $mail->Port       = 587;                                    // TLS için port (SSL için genellikle 465)
        $mail->CharSet    = 'UTF-8';                                // Karakter kodlaması

        // Alıcılar
        $mail->setFrom('eposta@lineupcampus.com', 'LineUp Campus');
        $mail->addAddress($adminEmail, 'LineUp Campus');     // İsteğe bağlı olarak isim ekleyebilirsiniz
        //$mail->addCC('cc@baskaalan.com');
        //$mail->addBCC('bcc@baskaalan.com');

        // Ekler (isteğe bağlı)
        //$mail->addAttachment('/var/tmp/file.tar.gz');
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // İsteğe bağlı olarak farklı bir isimle

        // İçerik
        $mail->isHTML(true);                                  // E-posta içeriği HTML formatında mı
        $mail->Subject = $baslik;
        /* $mail->Body    = 'Öğrenci Bilgisi: <br> Adı Soyadı: ' . $firstName . ' ' . $lastName . ' <br> <br> 
    Veli Bilgisi: <br> Adı Soyadı: ' . $veli_ad . ' ' . $veli_soyad . ' <br> <br>
    Telefon: ' . $kullanici_gsm . ' <br> <br>
    E-posta: ' . $kullanici_mail . ' <br> <br>'; */
        //$mail->AltBody = 'Bu da HTML desteklemeyen istemciler için düz metin içeriğidir.';
        $mail->Body    = $mail_icerigi;
        $mail->AltBody = strip_tags($mail_icerigi);

        $mail->send();
        unset($mail);
        $mail_icerigi = "";
        //echo 'E-posta başarıyla gönderildi!';
    } catch (Exception $e) {
        echo "E-posta gönderilirken bir hata oluştu: {$mail->ErrorInfo}";
    }

    if($packUse == 1) {
        $packages = new Packages();
        $packages->updateUsedCuponCount($couponCode);
    } else {
        
    }


    $gonder = $kisiekle->setStudent2($firstName, $lastName, $username, $kullanici_tckn, $gender, $birth_dayDb, $kullanici_mail, $class, $pack, $password, $nowTime, $endTime, $kullanici_gsm, $kullanici_adresiyaz, $district, $postcode, $kullanici_il, $role);

    $gonderVeli = $kisiekle->setParent($veli_ad, $veli_soyad, $username2, $password2, $parentRole);

    $odemeBilgisiGonder = $kisiekle->setPaymentInfo($kullanici_tckn, $pack, $siparis_numarasi, $isinstallment, $priceWithoutVat, $commissionRate, $commissionFee, $couponCode, $vatAmount, $vat);

    $error = "";

    $text =  '
            <div class="row">
                <div class="col-md-3">
                    <img src="assets/media/mascots/maskot-kiz.png" class="img-fluid" alt="Lineup Campus" />
                </div>
                <div class="col-md-6 text-gray-500 fw-semibold fs-4 d-flex align-items-center">
                    <div class="text-center">
                        <div>
                            Giriş bilgileriniz ' . $kullanici_mail . ' adresinize gönderildi.
                        </div>
                        <div>
                            <a href="index" class="btn btn-primary btn-sm mt-10" role="button">Giriş Yap</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <img src="assets/media/mascots/maskot-erkek.png" class="img-fluid" alt="Lineup Campus" style="transform: scaleX(-1);" />
                </div>
            </div>';

    session_destroy();
/* } */

?>
<!DOCTYPE html>
<html lang="tr">
<?php
include_once "../views/pages-head.php";
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
			<?php
			include_once "../views/home-side.php";
			?>
            <!--begin::Aside-->
            <!--begin::Body-->
            <div class="d-flex flex-column flex-lg-row-fluid py-10">
                <!--begin::Content-->
                <div class="d-flex flex-center flex-column flex-column-fluid">
                    <!--begin::Wrapper-->
                    <div class="w-lg-900px p-10 p-lg-15 mx-auto">
                        <?php echo $text; ?>
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
        var hostUrl = "../assets/";
    </script>
    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="../assets/plugins/global/plugins.bundle.js"></script>
    <script src="../assets/js/scripts.bundle.js"></script>
    <!--end::Global Javascript Bundle-->
    <!--begin::Custom Javascript(used for this page only)-->
    <script src="../assets/js/custom/authentication/kayit-ol/general.js"></script>
    <!--end::Custom Javascript-->
    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>