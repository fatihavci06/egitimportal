<?php

session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 2 or $_SESSION['role'] == 3 or $_SESSION['role'] == 4 or $_SESSION['role'] == 5 or $_SESSION['role'] == 6 or $_SESSION['role'] == 7 or $_SESSION['role'] == 8 or $_SESSION['role'] == 9 or $_SESSION['role'] == 10 or $_SESSION['role'] == 10001 or $_SESSION['role'] == 10002 or $_SESSION['role'] == 10005)) {
	header("location: dashboard");
} else {
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
						<div class="w-lg-500px p-10 p-lg-15 mx-auto">
							<!--begin::Form-->
							<form action="includes/login.inc.php" method="POST" class="form w-100" novalidate="novalidate" id="kt_sign_in_form" data-kt-redirect-url="index.html">
								<!--begin::Heading-->
								<div class="text-center mb-10">
									<!--begin::Title-->
									<h1 class="text-gray-900 mb-3" style="font-size: 2.1rem !important">LineUp Campus'e Giriş Yap</h1>
									<!--end::Title-->
									<!--begin::Link-->
									<div class="text-gray-800 fw-semibold fs-1">Yeni Misiniz?</div>
									<div class="text-center mt-4">
										<!--begin::Hesap Oluştur button-->
										<a href="hesap-olustur" class="btn btn-primary btn-lg" role="button">Hesap Oluştur</a>
										<!--end::Hesap Oluştur button-->
									</div>
									<!--end::Link-->
								</div>
								<!--begin::Messages-->
								<?php if (isset($_SESSION['err'])) {
									if ($_SESSION['err'] == 1) { ?> <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"> Hata Oluştu! </div> <?php } elseif ($_SESSION['err'] == 2) { ?> <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"> Kullanıcı Bulunamadı! </div> <?php } elseif ($_SESSION['err'] == 3) { ?> <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"> Hatalı Parola! </div> <?php  }
																																																																																																																																	}
																																																																																																																																	if (isset($_SESSION['msg'])) { ?>
									<div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"> <?php echo $_SESSION['msg']; ?> </div>
								<?php } ?>
								<!--end::Messages-->
								<!--begin::Heading-->
								<!--begin::Input group-->
								<div class="fv-row mb-10">
									<!--begin::Label-->
									<label class="form-label fs-6 fw-bold text-gray-900">E-Posta ya da Kullanıcı Adı</label>
									<!--end::Label-->
									<!--begin::Input-->
									<input class="form-control form-control-lg form-control-solid" class="signIn" type="text" name="email" />
									<!--end::Input-->
								</div>
								<!--end::Input group-->
								<!--begin::Input group-->
								<div class="fv-row mb-10">
									<!--begin::Wrapper-->
									<div class="d-flex flex-stack mb-2">
										<!--begin::Label-->
										<label class="form-label fw-bold text-gray-900 fs-6 mb-0">Parola</label>
										<!--end::Label-->
										<!--begin::Link-->
										<a href="parolami-unuttum" class="link-primary fs-6 fw-bold">Parolamı Unutum?</a>
										<!--end::Link-->
									</div>
									<!--end::Wrapper-->
									<!--begin::Input-->
									<div class="input-group">
										<input class="form-control form-control-lg form-control-solid" class="signIn" id="passwordIn" type="password" name="password" autocomplete="off" />
										<button class="btn btn-outline-secondary" type="button" id="togglePassword">
											<i class="bi bi-eye" id="eyeIcon"></i>
										</button>
									</div>
									<!--end::Input-->
								</div>
								<input type="hidden" name="device_os" id="deviceOsInput">
								<input type="hidden" name="browser" id="browserInput">
								<input type="hidden" name="device_type" id="deviceTypeInput">
								<input type="hidden" name="device_model" id="deviceModelInput">
								<input type="hidden" name="screen_size" id="screenSizeInput">
								<!--end::Input group-->
								<!--begin::Actions-->
								<div class="text-center">
									<!--begin::Submit button-->
									<button type="submit" id="kt_sign_in_submit" class="tus btn btn-lg btn-primary w-100 mb-5">
										<span class="indicator-label">Giriş Yap</span>
										<span class="indicator-progress">Lütfen bekleyin...
											<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
									</button>
									<!--end::Submit button-->
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
						<!--<div class="d-flex flex-center fw-semibold fs-6">
						</div>-->
						<!--end::Links-->
					</div>
					<!--end::Footer-->
				</div>
				<!--end::Body-->
			</div>
			<!--end::Authentication - Sign-in-->
		</div>
		<!--end::Root-->
		<!--begin::Javascript-->
		<script>
			var hostUrl = "assets/";
		</script>
		<!--begin::Global Javascript Bundle(mandatory for all pages)-->
		<script src="assets/plugins/global/plugins.bundle.js"></script>
		<script src="assets/js/scripts.bundle.js"></script>
		<script src="assets/js/eyetoggle.js"></script>
		<script>

		</script>

		<script>
        // UA-Parser.js'yi başlat
        var parser = new UAParser();
        var result = parser.getResult();

        // Bilgileri gizli input alanlarına doldur
        document.getElementById('deviceOsInput').value = result.os.name || '';
        document.getElementById('browserInput').value = result.browser.name || '';
        document.getElementById('deviceTypeInput').value = result.device.type || 'Desktop'; // Telefon, tablet, masaüstü vb.
        document.getElementById('deviceModelInput').value = result.device.model || 'Unknown';
        document.getElementById('screenSizeInput').value = window.screen.width + 'x' + window.screen.height;
    </script>
		<!--end::Global Javascript Bundle-->
		<!--begin::Custom Javascript(used for this page only)-->
		<!--<script src="assets/js/custom/authentication/sign-in/general.js"></script>-->
		<!--end::Custom Javascript-->
		<!--end::Javascript-->
	</body>
	<!--end::Body-->

	</html>
<?php } ?>