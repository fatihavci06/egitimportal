<?php

define('GUARD', true);

include_once "classes/dbh.classes.php";
include_once "classes/classes.classes.php";
include_once "classes/classes-view.classes.php";

$chooseClass = new ShowClass();
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
					<div class="w-lg-700px p-10 p-lg-15 mx-auto">
						<!--begin::Form-->
						<form class="form w-100" novalidate="novalidate" data-kt-redirect-url="odeme-al.php" id="kt_sign_up_form">

							<!--<form class="form w-100" method="POST" action="odeme-al.php" id="kt_sign_up_form">-->
							<!--begin::Heading-->
							<div class="mb-10 text-center">
								<!--begin::Title-->
								<h1 class="text-gray-900 mb-3">Hesap Oluştur</h1>
								<!--end::Title-->
								<!--begin::Link-->
								<div class="text-gray-500 fw-semibold fs-4">Zaten bir hesabınız var mı?
									<a href="index" class="link-primary fw-bold">Giriş yap</a>
								</div>
								<!--end::Link-->
							</div>
							<!--end::Heading-->
							<!--begin::Input group-->
							<div class="row fv-row mb-7">
								<!--begin::Col-->
								<div class="col-xl-6">
									<label class="form-label fw-bold text-gray-900 fs-6 required">Öğrencinin Adı</label>
									<input class="form-control form-control-lg form-control-solid" type="text" placeholder="" name="first-name" id="first-name" autocomplete="off" />
								</div>
								<!--end::Col-->
								<!--begin::Col-->
								<div class="col-xl-6">
									<label class="form-label fw-bold text-gray-900 fs-6 required">Öğrencinin Soyadı</label>
									<input class="form-control form-control-lg form-control-solid" type="text" placeholder="" name="last-name" id="last-name" autocomplete="off" />
								</div>
								<!--end::Col-->
							</div>
							<!--end::Input group-->
							<!--begin::Input group-->
							<div class="fv-row mb-7">
								<label class="form-label fw-bold text-gray-900 fs-6 required">Kullanıcı Adı</label>
								<input class="form-control form-control-lg form-control-solid" type="text" placeholder="" name="username" id="username" autocomplete="off" />
							</div>
							<!--end::Input group-->
							<!--begin::Input group-->
							<div class="fv-row mb-7">
								<label class="form-label fw-bold text-gray-900 fs-6 required">Öğrencinin Türkiye Cumhuriyeti Kimlik Numarası</label>
								<input class="form-control form-control-lg form-control-solid" type="text" placeholder="" name="tckn" id="tckn" autocomplete="off" />
							</div>
							<!--end::Input group-->
							<!--begin::Input group-->
							<div class="fv-row mb-7">
								<label class="form-label fw-bold text-gray-900 fs-6 required">Öğrencinin Cinsiyeti</label>
								<select class="form-control form-control-lg form-control-solid" name="gender" id="gender">
									<option value="">Lütfen Seçiniz</option>
									<option value="Erkek">Erkek</option>
									<option value="Kız">Kız</option>
								</select>
							</div>
							<!--end::Input group-->


							<!--begin::Input group-->
							<div class="fv-row mb-7">
								<label class="form-label fw-bold text-gray-900 fs-6 required">Öğrencinin Doğum Tarihi</label>
								<!--begin::Datepicker-->
								<input type="date" class="form-control form-control-solid fw-bold pe-5" placeholder="Doğum Tarihi Seçin" name="birth_day" id="birth_day">
								<!--end::Datepicker-->
							</div>
							<!--end::Input group-->

							<!--begin::Input group-->
							<div class="fv-row mb-7">
								<label class="form-label fw-bold text-gray-900 fs-6 required">E-posta Adresi</label>
								<input class="form-control form-control-lg form-control-solid" type="email" placeholder="" name="email" id="email" autocomplete="off" />
							</div>
							<!--end::Input group-->
							<!--begin::Input group-->
							<div class="row fv-row mb-7">
								<!--begin::Col-->
								<div class="col-xl-6">
									<label class="form-label fw-bold text-gray-900 fs-6 required">Velinin Adı</label>
									<input class="form-control form-control-lg form-control-solid" type="text" placeholder="" name="parent-first-name" id="parent-first-name" autocomplete="off" />
								</div>
								<!--end::Col-->
								<!--begin::Col-->
								<div class="col-xl-6">
									<label class="form-label fw-bold text-gray-900 fs-6 required">Velinin Soyadı</label>
									<input class="form-control form-control-lg form-control-solid" type="text" placeholder="" name="parent-last-name" id="parent-last-name" autocomplete="off" />
								</div>
								<!--end::Col-->
							</div>
							<!--end::Input group--><!--begin::Input group-->
							<div class="d-flex flex-column mb-7 fv-row">
								<!--begin::Label-->
								<label class="required fs-6 fw-semibold mb-2">Adres</label>
								<!--end::Label-->
								<!--begin::Input-->
								<input class="form-control form-control-solid" name="address" id="address" placeholder="Adres" />
								<!--end::Input-->
							</div>
							<!--begin::Input group-->
							<div class="row g-9 mb-7">
								<!--begin::Col-->
								<div class="col-md-6 fv-row">
									<!--begin::Label-->
									<label class="required fs-6 fw-semibold mb-2">İlçe</label>
									<!--end::Label-->
									<!--begin::Input-->
									<input class="form-control form-control-solid" name="district" id="district" placeholder="İlçe" />
									<!--end::Input-->
								</div>
								<!--end::Col-->
								<!--begin::Col-->
								<div class="col-md-6 fv-row">
									<!--begin::Label-->
									<label class="fs-6 fw-semibold mb-2">Posta Kodu</label>
									<!--end::Label-->
									<!--begin::Input-->
									<input class="form-control form-control-solid" name="postcode" id="postcode" placeholder="Posta Kodu" />
									<!--end::Input-->
								</div>
								<!--end::Col-->
							</div>
							<!--end::Input group-->
							<!--begin::Input group-->
							<div class="d-flex flex-column mb-7 fv-row">
								<!--begin::Label-->
								<label class="fs-6 fw-semibold mb-2">
									<span class="required">Şehir</span>
								</label>
								<!--end::Label-->
								<!--begin::Input-->
								<select id="city" name="city" aria-label="Sehir Seçiniz"
									data-placeholder="Sehir Seçiniz..."
									class="form-select form-select-solid fw-bold">
									<option value="">Şehir Seçin</option>
									<option value="Adana">Adana</option>
									<option value="Adıyaman">Adıyaman</option>
									<option value="Afyonkarahisar">Afyonkarahisar</option>
									<option value="Ağrı">Ağrı</option>
									<option value="Amasya">Amasya</option>
									<option value="Ankara">Ankara</option>
									<option value="Antalya">Antalya</option>
									<option value="Artvin">Artvin</option>
									<option value="Aydın">Aydın</option>
									<option value="Balıkesir">Balıkesir</option>
									<option value="Bilecik">Bilecik</option>
									<option value="Bingöl">Bingöl</option>
									<option value="Bitlis">Bitlis</option>
									<option value="Bolu">Bolu</option>
									<option value="Burdur">Burdur</option>
									<option value="Bursa">Bursa</option>
									<option value="Çanakkale">Çanakkale</option>
									<option value="Çankırı">Çankırı</option>
									<option value="Çorum">Çorum</option>
									<option value="Denizli">Denizli</option>
									<option value="Diyarbakır">Diyarbakır</option>
									<option value="Edirne">Edirne</option>
									<option value="Elazığ">Elazığ</option>
									<option value="Erzincan">Erzincan</option>
									<option value="Erzurum">Erzurum</option>
									<option value="Eskişehir">Eskişehir</option>
									<option value="Gaziantep">Gaziantep</option>
									<option value="Giresun">Giresun</option>
									<option value="Gümüşhane">Gümüşhane</option>
									<option value="Hakkâri">Hakkâri</option>
									<option value="Hatay">Hatay</option>
									<option value="Isparta">Isparta</option>
									<option value="Mersin">Mersin</option>
									<option value="İstanbul">İstanbul</option>
									<option value="İzmir">İzmir</option>
									<option value="Kars">Kars</option>
									<option value="Kastamonu">Kastamonu</option>
									<option value="Kayseri">Kayseri</option>
									<option value="Kırklareli">Kırklareli</option>
									<option value="Kırşehir">Kırşehir</option>
									<option value="Kocaeli">Kocaeli</option>
									<option value="Konya">Konya</option>
									<option value="Kütahya">Kütahya</option>
									<option value="Malatya">Malatya</option>
									<option value="Manisa">Manisa</option>
									<option value="Kahramanmaraş">Kahramanmaraş</option>
									<option value="Mardin">Mardin</option>
									<option value="Muğla">Muğla</option>
									<option value="Muş">Muş</option>
									<option value="Nevşehir">Nevşehir</option>
									<option value="Niğde">Niğde</option>
									<option value="Ordu">Ordu</option>
									<option value="Rize">Rize</option>
									<option value="Sakarya">Sakarya</option>
									<option value="Samsun">Samsun</option>
									<option value="Siirt">Siirt</option>
									<option value="Sinop">Sinop</option>
									<option value="Sivas">Sivas</option>
									<option value="Tekirdağ">Tekirdağ</option>
									<option value="Tokat">Tokat</option>
									<option value="Trabzon">Trabzon</option>
									<option value="Tunceli">Tunceli</option>
									<option value="Şanlıurfa">Şanlıurfa</option>
									<option value="Uşak">Uşak</option>
									<option value="Van">Van</option>
									<option value="Yozgat">Yozgat</option>
									<option value="Zonguldak">Zonguldak</option>
									<option value="Aksaray">Aksaray</option>
									<option value="Bayburt">Bayburt</option>
									<option value="Karaman">Karaman</option>
									<option value="Kırıkkale">Kırıkkale</option>
									<option value="Batman">Batman</option>
									<option value="Şırnak">Şırnak</option>
									<option value="Bartın">Bartın</option>
									<option value="Ardahan">Ardahan</option>
									<option value="Iğdır">Iğdır</option>
									<option value="Yalova">Yalova</option>
									<option value="Karabük">Karabük</option>
									<option value="Kilis">Kilis</option>
									<option value="Osmaniye">Osmaniye</option>
									<option value="Düzce">Düzce</option>
								</select>
								<!--end::Input-->
							</div>
							<!--end::Input group-->

							<!--begin::Input group-->
							<div class="fv-row mb-7">
								<!--begin::Label-->
								<label class="required fs-6 fw-semibold mb-2">Telefon Numarası</label>
								<!--end::Label-->
								<!--begin::Input-->
								<input type="text" class="form-control form-control-solid" placeholder="05001234578" id="telephone" name="telephone" />
								<!--end::Input-->
							</div>
							<!--end::Input group-->


							<!--begin::Input group-->
							<div class="fv-row mb-7">
								<label class="form-label fw-bold text-gray-900 fs-6">Öğrencinin Sınıfı</label>
								<select class="form-control form-control-lg form-control-solid" name="classes" id="classes">
									<option value="">Lütfen Seçiniz</option>
									<?php $chooseClass->getClassSelectList(); ?>
								</select>
							</div>
							<!--end::Input group-->

							<!--begin::Input group-->
							<div id="veriAlani">

							</div>
							<!--end::Input group-->

							<div id="totalPrice" class="mb-10">

							</div>

							<div id="subscription_month" style="display: none;">
							
							</div>

							<div id="priceWoDiscount" style="display: none;">
							
							</div>

							<div id="priceWCoupon" style="display: none;">
							
							</div>

							<div id="couponInfo">

							</div>

							<div id="couponCode">

							</div>

							<div id="moneyTransferInfo" class="mb-10">

							</div>

							<div id="cashdiscount" class="mb-10">

							</div>

							<!--begin::Input group-->
							<div id="payment_method" class="fv-row mb-5" style="display: none;" required>
								<label class="form-label fw-bold text-gray-900 fs-6">Ödeme Şekli</label>
								<span class="form-check form-check-custom form-check-solid">
									<label><input class="form-check-input" type="radio" name="payment_type" value="1"> Havale/EFT</label>
									<label><input class="form-check-input ms-7" type="radio" name="payment_type" value="2"> Kredi Kartı</label>
								</span>
							</div>
							<!--end::Input group-->

							<div id="iscash" class="mb-10">

							</div>

							<!--begin::Input group-->
							<div class="fv-row mb-10">
								<label class="form-check form-check-custom form-check-solid form-check-inline">
									<input class="form-check-input" type="checkbox" name="toc" value="0" />
									<span class="form-check-label fw-semibold text-gray-700 fs-6"><a href="#" class="ms-1 link-primary">KVKK Metnini</a>
										onaylıyorum.</span>
								</label>
							</div>
							<!--end::Input group-->

							<!--begin::Actions-->
							<div class="text-center">
								<button type="button" id="kt_sign_up_submit" class="btn btn-lg btn-primary" disabled>
									<!--<button type="submit" class="btn btn-lg btn-primary">	-->
									<span class="indicator-label">Gönder</span>
									<span class="indicator-progress">Lütfen bekleyiniz...
										<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
								</button>
							</div>
							<!--end::Actions-->
						</form>
						<!--end::Form-->
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