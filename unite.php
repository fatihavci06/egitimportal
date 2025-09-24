<!DOCTYPE html>
<html lang="tr">
<?php

session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and $_SESSION['role'] == 2) {
	include_once "classes/dbh.classes.php";
	include_once "classes/units.classes.php";
	include_once "classes/units-view.classes.php";
	include_once "classes/topics.classes.php";
	include_once "classes/topics-view.classes.php";
	include_once "classes/classes.classes.php";

	$url = $_SERVER['REQUEST_URI']; // /lineup_campus/ders/turkce

	$parts = explode('/', trim($url, '/')); // ['lineup_campus', 'ders', 'turkce']

	$slug = $parts[2] ?? null; // 'turkce'
	$lesson = new Classes();
	$unitInfo = $lesson->getUnitBySlug($slug);

	$units = new ShowUnit();
	$topics = new ShowTopic();

	$lessons = $lesson->getLessonsList($_SESSION['class_id']);
	include_once "views/pages-head.php";
?>
	<!--end::Head-->
	<!--begin::Body-->

	<body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" data-kt-app-aside-enabled="true" data-kt-app-aside-fixed="true" data-kt-app-aside-push-toolbar="true" data-kt-app-aside-push-footer="true" class="app-default">
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
		<!--begin::App-->
		<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
			<!--begin::Page-->
			<div class="app-page flex-column flex-column-fluid" id="kt_app_page">
				<!--begin::Header-->
				<?php include_once "views/header.php"; ?>
				<!--end::Header-->
				<!--begin::Wrapper-->
				<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
					<!--begin::Sidebar-->
					<?php include_once "views/sidebar.php"; ?>
					<!--end::Sidebar-->
					<!--begin::Main-->
					<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
						<!--begin::Content wrapper-->
						<div class="d-flex flex-column flex-column-fluid">
							<!--begin::Toolbar-->
							<?php include_once "views/toolbar.php"; ?>
							<!--end::Toolbar-->
							<!--begin::Content-->
							<div id="kt_app_content" class="app-content flex-column-fluid">
								<!--begin::Content container-->
								<div id="kt_app_content_container" class="app-container container-fluid">
									<!--begin::Careers - List-->
									<div class="card" style="margin-left: -15px;">
										<!--begin::Body-->
										<div class="card-body p-lg-7">
											<!--begin::Hero-->
											<?php $units->getHeaderImageStu(); ?>
											<!--end::-->
											<!--begin::Layout-->
											<div class="row align-items-center mb-12" style="margin-top: -30px;">
												<div class="col-2 d-flex ">
													<h5 class="fs-2x text-gray-900 mb-0 text-center" style="font-size:15px!important;margin-left:0px;">
														Dersler

													</h5>
												</div>
												<div class="col-10 d-flex justify-content-center">

												</div>
											</div>

											<div class="row" style="margin-top: -30px;margin-left: -30px;">
												<div class="col-3 col-lg-2">
													<div class="row g-10">
														<?php foreach ($lessons as $l): ?>
															<?php if ($l['name'] !== 'Robotik Kodlama' && $l['name'] !== 'Ders Deneme'): ?>
																<div class="col-12 mb-1 text-center">
																	<a href="ders/<?= urlencode($l['slug']) ?>">
																		<img src="assets/media/icons/dersler/<?= htmlspecialchars($l['icons']) ?>" alt="Icon" class="img-fluid" style="width: 65px; height: 65px; object-fit: contain;" />
																		<div class="mt-1 small"><?= htmlspecialchars($l['name']) ?></div>
																	</a>
																</div>
															<?php endif; ?>
														<?php endforeach; ?>
													</div>
												</div>

												<div class="col-9 col-lg-10">
													<div class="row g-10">

														<?php
														$topics->getTopicsListStudent();
														$testData = $lesson->getTestByTopicLessonUnit($_SESSION['class_id'], null, $unitInfo['id']);

														if (!empty($testData)) {
															foreach ($testData as $test) {
																$testResult = $lesson->getTestResult($test['id'], $_SESSION['id']);

																if ($testResult['fail_count'] >= 3 and $testResult['user_test_status'] == 0) {
																	$buttonDisabled = true;
																	$testText = " (3 kez başarısız oldunuz)";
																} elseif ($testResult['user_test_status'] == 1) {
																	$buttonDisabled = true;
																	$testText = " (Testi başarıyla tamamladınız. Puanınız " . $testResult['score'] . ")";
																} else {
																	$buttonDisabled = false;
																	$testText = "";
																}
																// Örnek: test detayına yönlendirme için slug veya id kullanılabilir
																$testLink = 'ogrenci-test-coz.php?id=' . urlencode($test['id']);

																// Link tıklanabilirliği ve görünümü
																$anchorHref = $buttonDisabled ? '#' : $testLink;
																$ariaDisabled = $buttonDisabled ? 'aria-disabled="true"' : '';
																$opacityStyle = $buttonDisabled ? 'opacity:0.5; pointer-events:none;' : '';
														?>

															


														<?php
															}
														}
														?>
													</div>
												</div>
											</div>

											<!--end::Layout-->
										</div>
										<!--end::Body-->
									</div>
									<!--end::Careers - List-->
								</div>
								<!--end::Content container-->
							</div>
							<!--end::Content-->
						</div>
						<!--end::Content wrapper-->
						<!--begin::Footer-->
						<?php include_once "views/footer.php"; ?>
						<!--end::Footer-->
					</div>
					<!--end:::Main-->
					<!--begin::aside-->
					<?php include_once "views/aside.php"; ?>
					<!--end::aside-->
				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Page-->
		</div>
		<!--end::App-->
		<!--begin::Scrolltop-->
		<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
			<i class="ki-duotone ki-arrow-up">
				<span class="path1"></span>
				<span class="path2"></span>
			</i>
		</div>
		<!--end::Scrolltop-->
		<!--begin::Javascript-->
		<script>
			var hostUrl = "assets/";
		</script>
		<!--begin::Global Javascript Bundle(mandatory for all pages)-->
		<script src="assets/plugins/global/plugins.bundle.js"></script>
		<script src="assets/js/scripts.bundle.js"></script>
		<!--end::Global Javascript Bundle-->
		<!--begin::Vendors Javascript(used for this page only)-->
		<script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
		<!--end::Vendors Javascript-->
		<!--begin::Custom Javascript(used for this page only)-->
		<script src="assets/js/custom/apps/class/list/export.js"></script>
		<script src="assets/js/custom/apps/class/list/list.js"></script>
		<script src="assets/js/custom/apps/class/add.js"></script>
		<script src="assets/js/widgets.bundle.js"></script>
		<script src="assets/js/custom/widgets.js"></script>
		<script src="assets/js/custom/apps/chat/chat.js"></script>
		<script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
		<script src="assets/js/custom/utilities/modals/create-account.js"></script>
		<script src="assets/js/custom/utilities/modals/create-app.js"></script>
		<script src="assets/js/custom/utilities/modals/users-search.js"></script>
		<!--end::Custom Javascript-->
		<!--end::Javascript-->
	</body>
	<!--end::Body-->

</html>
<?php } else {
	header("location: ../index");
}
