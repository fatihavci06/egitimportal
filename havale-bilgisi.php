<?php

session_start();
define('GUARD', true);

if (!isset($_SESSION['parentFirstName'])) {
    header("location: index");
}


require_once('classes/dateformat.classes.php');
require_once('classes/dbh.classes.php');
require_once('classes/adduser.classes.php');
include_once "classes/packages.classes.php";
require_once 'classes/Mailer.php';
require_once('classes/userslist.classes.php');

$admin = new User();
$getadminEmail = $admin->getlnpAdmin();
$adminEmail = $getadminEmail[0]['email'];

$package = new Packages();

$packInfo = $package->getPackagePrice(htmlspecialchars(trim($_SESSION['pack'])));

$coupon = $package->checkCoupon($_SESSION['couponCode']);

if ($coupon) {
    $discount_value = $coupon['discount_value'];
    $discount_type = $coupon['discount_type'];
}
foreach ($packInfo as $key => $value) {
    $price = $value['monthly_fee'] * $value['subscription_period'];
    if ($discount_type === 'amount') {
        $price -= $discount_value;
    } else if ($discount_type === 'percentage') {
        $price -= $price * ($discount_value / 100);
    }
}

/* $cashDiscount = $_SESSION['creditCash'];

$price -= $price * ($cashDiscount / 100); */

$vat = 10;  // %10 KDV oranı
$price += $price * ($vat / 100); // KDV'yi ekle
$vatAmount = $price * ($vat / 100); // KDV tutarını hesapla
$price = number_format($price, 2, '.', ''); // İki ondalık basamakla formatla

$moneyTransferDiscount = $package->getTransferDiscount();
$moneyTransferDiscount = $moneyTransferDiscount['discount'];

$price -= $price * ($moneyTransferDiscount / 100); // Havale indirimini uygula
$price = number_format($price, 2, '.', ''); // İki ondalık basamakla formatla

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
} else {
    $role = 2;
}

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
$kupon_kodu = $_SESSION['couponCode'];
$packUse = $_SESSION['packUse'];
$siparis_no = rand() . rand();


$dateformat = new DateFormat();

$birth_dayDb = $dateformat->forDB($birth_day);

$yeniSifre = gucluSifreUret(15);

$yeniSifre2 = gucluSifreUret(15);

$password = password_hash($yeniSifre, PASSWORD_DEFAULT);

$password2 = password_hash($yeniSifre2, PASSWORD_DEFAULT);

$username2 = $username . '-veli';

$kisiekle = new AddUser();

$gonder = $kisiekle->setStudentMoneyTransfer($firstName, $lastName, $username, $kullanici_tckn, $gender, $birth_dayDb, $kullanici_mail, $class, $pack, $password, $kullanici_gsm, $kullanici_adresiyaz, $district, $postcode, $kullanici_il, $role);

$gonderVeli = $kisiekle->setParent($veli_ad, $veli_soyad, $username2, $password2);

$gonderHavale = $kisiekle->setWaitingMoneyTransfer($kullanici_tckn, $pack, $price, $siparis_no, $kupon_kodu, $vatAmount, $vat);


if ($packUse == 1) {
    $packages = new Packages();
    $packages->updateUsedCuponCount($kupon_kodu);
} else {
}

$error = "";
if (isset($_GET["error"])) {
    $error = "<div class='text-danger'>Bir hata oluştu.</div>";
}

$text =  '
            <div class="row">
                <div class="col-md-3">
                    <img src="assets/media/mascots/maskot-kiz.png" class="img-fluid" alt="Lineup Campus" />
                </div>
                <div class="col-md-6 text-gray-500 fw-semibold fs-4 d-flex align-items-center">
                    <div class="text-center">
                        <div>
                            Ödemeniz havale yolu ile bekleniyor.
                            <br />
                            Açıklama kısmına <strong>Öğrencinin Adı Soyadı ve T.C. Kimlik Numarasını</strong> yazmayı unutmayın. <br />
                            Ödemeniz gereken tutar: <strong>' . $price . ' ₺</strong><br />
                            Teşekkür ederiz.
                            <div class="text-center mt-5">
                                <h3 >Hesap Bilgileri</h3>
                                <p>Hesap Adı: Lineup </p>
                                <p>IBAN: TR0000000000000000000000</p>
                                <p>Banka: Bankası</p>
                            </div>
                        </div>
                            ' . $error . '
                        <div>
                            <a href="index" class="btn btn-primary btn-sm mt-10" role="button">Giriş Sayfası</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <img src="assets/media/mascots/maskot-erkek.png" class="img-fluid" alt="Lineup Campus" style="transform: scaleX(-1);" />
                </div>
            </div>';

$mailer = new Mailer();


$emailResult = $mailer->sendBankTransferEmail($veli_ad, $veli_soyad, $kullanici_mail, $price);

$emailResultAdmin = $mailer->sendBankTransferEmailToAdmin($veli_ad, $veli_soyad, $adminEmail, $price);

session_destroy();

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
                <div class="d-flex flex-column position-xl-fixed top-0 bottom-0 w-xl-600px">
                    <!--begin::Header-->
                    <div class="d-flex flex-row-fluid flex-column text-center p-5 p-lg-10 pt-lg-20">
                        <!--begin::Logo-->
                        <a href="index.html" class="py-2 py-lg-20">
                            <img alt="Logo" src="assets/media/logos/lineup-campus.jpg" class="h-100px h-lg-150px" />
                        </a>
                        <!--end::Logo-->
                        <!--begin::Title-->
                        <h1 class="d-none d-lg-block fw-bold text-green fs-2qx pb-5 pb-md-10">Lineup Campus'e Hoş Geldiniz</h1>
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