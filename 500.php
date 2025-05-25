<?php define('GUARD', true); ?>
<!DOCTYPE html>
<html lang="en">
	<?php
	include_once "views/pages-head.php";
	?>
	<!--begin::Body-->
	<body id="kt_body" class="app-blank">
		<!--begin::Theme mode setup on page load-->
		<script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>
		<!--end::Theme mode setup on page load-->
		<!--begin::Root-->
		<div class="d-flex flex-column flex-root" id="kt_app_root">
			<!--begin::Authentication - 500 Page-->
			<div class="d-flex flex-column flex-center flex-column-fluid p-10">
				<!--begin::Message-->
				<h1 class="fw-semibold " style="color: #A3A3C7">500</h1>
				<h1 class="fw-semibold mb-10" style="color: #A3A3C7">Sunucu hatası!</h1>
				<!--end::Message-->
				<!--begin::Illustration-->
				<img src="assets/media/mascots/500-maskotlu.png" alt="" class="mw-100 mb-10 h-lg-450px" />
				<!--end::Illustration-->
				<!--begin::Link-->
				<a href="index" class="btn btn-primary btn-sm">Ana Sayfaya Dön</a>
				<!--end::Link-->
			</div>
			<!--end::Authentication - 500 Page-->
		</div>
		<!--end::Root-->
		<!--begin::Javascript-->
		<script>var hostUrl = "assets/";</script>
		<!--begin::Global Javascript Bundle(mandatory for all pages)-->
		<script src="assets/plugins/global/plugins.bundle.js"></script>
		<script src="assets/js/scripts.bundle.js"></script>
		<!--end::Global Javascript Bundle-->
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>