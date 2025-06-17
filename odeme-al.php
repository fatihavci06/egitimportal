<?php


#sessiondan kodu al, veritabanında kodun yüzde mi amount mu olduğunu kontrol edice
# indirim değeride alıncaak. indirim değerini yüzde veya tl ye göre price 

// foreach ($packInfo as $key => $value) {
// $price = $value['monthly_fee'] * $value['subscription_period'];
// } bu kodun içinde yüzdeyse yüzdesi kadar düşer fiyat ise direkt - fiyat olacak zaten 

session_start();
define('GUARD', true);
if(!isset($_SESSION['parentFirstName'])){
	header("location: index");
}
include_once "classes/dbh.classes.php";
include_once "classes/classes.classes.php";
include_once "classes/classes-view.classes.php";
include_once "classes/packages.classes.php";

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
	$price = $value['monthly_fee'] * $value['subscription_period'];
	if ($discount_type === 'amount') {
		$price -= $discount_value;
	} else if ($discount_type === 'percentage') {
		$price -= $price * ($discount_value / 100);
	}
}

/* $cashDiscount = $_SESSION['creditCash'];

$price -= $price * ($cashDiscount / 100); */

$vat = $package->getVat();

$vat = $vat['tax_rate'];  // %10 KDV oranı
$price += $price * ($vat / 100); // KDV'yi ekle
$vatAmount = $price * ($vat / 100); // KDV tutarını hesapla
$price = number_format($price, 2, '.', ''); // İki ondalık basamakla formatla


$kullanici_ad = $_SESSION['parentFirstName'];
$kullanici_soyad = $_SESSION['parentLastName'];
$kullanici_gsm = $_SESSION['telephone'];
$kullanici_mail = $_SESSION['email'];
$kullanici_tckn = $_SESSION['tckn'];
$kullanici_zaman = date('Y-m-d H:i:s');
$kullanici_adresiyaz = $_SESSION['address'];
$kullanici_il = $_SESSION['city'];
$postcode = $_SESSION['postcode'];
$district = $_SESSION['district'];
$siparis_no = rand() . rand();
$_SESSION['siparis_numarasi'] = $siparis_no;
$sepettoplam = $price;
$kupon_kodu = $_SESSION['couponCode'];
$isinstallment = $_SESSION['isinstallment'];
$_SESSION['vatAmount'] = $vatAmount;
$_SESSION['vat'] = $vat;


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
			<?php
			include_once "views/home-side.php";
			?>
			<!--begin::Aside-->
			<!--begin::Body-->
			<div class="d-flex flex-column flex-lg-row-fluid py-10">
				<!--begin::Content-->
				<div class="d-flex flex-center flex-column flex-column-fluid">
					<!--begin::Wrapper-->
					<div class="w-lg-700px p-10 p-lg-15 mx-auto">
						<!--begin::Checkout-->
						<?php include 'iyzico/buyer.php'; ?>
						<div id="iyzipay-checkout-form" class="responsive"></div>
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