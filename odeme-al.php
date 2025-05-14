<?php


#sessiondan kodu al, veritabanında kodun yüzde mi amount mu olduğunu kontrol edice
# indirim değeride alıncaak. indirim değerini yüzde veya tl ye göre price 

// foreach ($packInfo as $key => $value) {
// $price = $value['monthly_fee'] * $value['subscription_period'];
// } bu kodun içinde yüzdeyse yüzdesi kadar düşer fiyat ise direkt - fiyat olacak zaten 

session_start();
define('GUARD', true);

include_once "classes/dbh.classes.php";
include_once "classes/classes.classes.php";
include_once "classes/classes-view.classes.php";
include_once "classes/packages.classes.php";

$package = new Packages();

$chooseClass = new ShowClass();

$packInfo = $package->getPackagePrice(htmlspecialchars(trim($_SESSION['pack'])));

// $couponCode = $package->checkCoupon($_SESSION['couponCode']);

// if ($couponCode) {

// 	$coupon = $package->checkCoupon($couponCode);
// 	$discount_value = $coupon['discount_value'];
// 	$discount_type = $coupon['discount_type'];
// }

foreach ($packInfo as $key => $value) {
	$price = $value['monthly_fee'] * $value['subscription_period'];

	// if ($discount_type === 'amount') {
	// 	$price -= $discount_value;
	// } else if ($discount_type === 'percentage') {
	// 	$price -= $price * ($discount_value / 100);
	// }
}


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
$sepettoplam = $price;
$kupon_kodu = $_SESSION['couponCode'];


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