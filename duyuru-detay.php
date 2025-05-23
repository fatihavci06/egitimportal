<!DOCTYPE html>
<html lang="tr">
<?php

session_start();
define('GUARD', true);


if (isset($_SESSION['role']) and $_SESSION['role'] == 1) {


	include_once "classes/dbh.classes.php";
	include_once "classes/announcement.classes.php";
	include_once "classes/announcement-view.classes.php";
	include_once "views/pages-head.php";

	$announce = new ShowAnnouncement();

	$announce_manager = new AnnouncementManager();

	$slug_announcement = isset($_GET['q']) ? filter_var($_GET['q'], FILTER_SANITIZE_STRING) : '';
	$announce_data = $announce->getAnnouncementBySlug($slug_announcement);
	$timeDifference = new DateFormat();

	// $announce->getAnnouncementStats($announce_data['id']); 
	?>

	<body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true"
		data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
		data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true"
		data-kt-app-aside-enabled="true" data-kt-app-aside-fixed="true" data-kt-app-aside-push-toolbar="true"
		data-kt-app-aside-push-footer="true" class="app-default">
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
		<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
			<div class="app-page flex-column flex-column-fluid" id="kt_app_page">
				<?php include_once "views/header.php"; ?>

				<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
					<?php include_once "views/sidebar.php"; ?>
					<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
						<div class="d-flex flex-column">
							<div id="kt_app_toolbar" class="app-toolbar pt-5">
								<div id="kt_app_toolbar_container"
									class="app-container container-fluid d-flex align-items-stretch">
									<div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
										<div class="page-title d-flex flex-column gap-1 me-3 mb-2">
											<ul class="breadcrumb breadcrumb-separatorless fw-semibold mb-6">
												
											</ul>
											<h1
												class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bolder fs-1 lh-0">
												<?php echo $announce_data['title'] ?>	
											</h1>

										</div>
									</div>
								</div>
							</div>
						</div>
						<div id="kt_app_content" class="app-content flex-column-fluid">
							<div id="kt_app_content_container" class="app-container container-fluid">
								<div class="card">
									<div class="card-header border-0 pt-6">
										<div class="card-title">
											<div class="d-flex align-items-center position-relative my-1">
												<i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
													<span class="path1"></span>
													<span class="path2"></span>
												</i>
												<input type="text" data-kt-customer-table-filter="search"
													class="form-control form-control-solid w-250px ps-12"
													placeholder="Kişilerde Ara" />
											</div>
										</div>

									</div>
									<div class="card-body pt-0">
										<!--begin::Table-->
										<table class="table align-middle table-row-dashed fs-6 gy-5"
											id="kt_customers_table">
											<thead>
												<tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
													<th class="min-w-125px">Ad Soyad</th>
													<th class="min-w-125px">Rol</th>
													<th class="min-w-125px">Görüntülenme Tarihi</th>
												</tr>
											</thead>
											<tbody class="fw-semibold text-gray-600">
												<?php $announce->getAnnouncementStats($announce_data['id']); ?>
											</tbody>
										</table>
										<!--end::Table-->
									</div>
								</div>

							</div>
						</div>
					</div>
					<!--begin::Footer-->
					<?php include_once "views/footer.php"; ?>
					<!--end::Footer-->
				</div>
				<!--begin::aside-->
				<?php include_once "views/aside.php"; ?>
				<!--end::aside-->
			</div>
		</div>
		</div>
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
		<script src="assets/js/custom/apps/announcement-stats/list/export.js"></script>
		<script src="assets/js/custom/apps/announcement-stats/list/list.js"></script>
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

	</html>

<?php } else {
	header("location: index");
	exit;
}
