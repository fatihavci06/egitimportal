<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 8 or $_SESSION['role'] == 4 or $_SESSION['role'] == 3)) {
	include_once "classes/dbh.classes.php";
	include "classes/student.classes.php";
	include "classes/student-view.classes.php";
	include "classes/school.classes.php";
	include "classes/timespend.classes.php";

	include_once "classes/classes.classes.php";
	include_once "classes/classes-view.classes.php";
	include_once "classes/lessons.classes.php";
	include_once "classes/lessons-view.classes.php";

	$studentId = new Student();
	$student = new ShowStudent();
	$timeSpend = new timeSpend();
	$school = new School();
	$slug = $_GET['q'];
	include_once "views/pages-head.php";

	if ($_SESSION['role'] == 1) {
		$getStudentId = $studentId->getStudentId($slug);
	} elseif ($_SESSION['role'] == 3) {
		$getStudentId = $studentId->getStudentIdForCoordinator($slug, $_SESSION['school_id']);
	} elseif ($_SESSION['role'] == 4) {
		$getStudentId = $studentId->getStudentIdForTeacher($slug, $_SESSION['school_id'], $_SESSION['class_id']);
	}

	if ($getStudentId == null) {
		header("location: ../404.php");
		exit();
	}

	$studentInfo = $student->getOneStudent($getStudentId);

	$timeSpendInfo = $timeSpend->getTimeSpend($getStudentId);

	$schoolInfo = $school->getOneSchoolById($studentInfo['school_id']);

	$studentPackages = $student->getStudentPackages($getStudentId);

	$studentAdditionalPackages = $student->getStudentAdditionalPackages($getStudentId);

	$studentClassName = $student->getStudentClass($studentInfo['class_id']);
	?>

	<!--end::Head-->
	<!--begin::Body-->

	<body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true"
		data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
		data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true"
		data-kt-app-aside-enabled="true" data-kt-app-aside-fixed="true" data-kt-app-aside-push-toolbar="true"
		data-kt-app-aside-push-footer="true" class="app-default">
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
							<div id="kt_app_toolbar" class="app-toolbar pt-5">
								<!--begin::Toolbar container-->
								<div id="kt_app_toolbar_container"
									class="app-container container-fluid d-flex align-items-stretch">
									<!--begin::Toolbar wrapper-->
									<div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
										<!--begin::Page title-->
										<div class="page-title d-flex flex-column gap-1 me-3 mb-2">
											<!--begin::Breadcrumb-->
											<ul class="breadcrumb breadcrumb-separatorless fw-semibold mb-6">
												<!--begin::Item-->
												<li class="breadcrumb-item text-gray-700 fw-bold lh-1">
													<a href="index.html" class="text-gray-500 text-hover-primary">
														<i class="ki-duotone ki-home fs-3 text-gray-500 me-n1"></i>
													</a>
												</li>
												<!--end::Item-->
												<!--begin::Item-->
												<li class="breadcrumb-item">
													<i class="ki-duotone ki-right fs-4 text-gray-700 mx-n1"></i>
												</li>
												<!--end::Item-->
												<!--begin::Item-->
												<li class="breadcrumb-item text-gray-700">Öğrenci Detay</li>
												<!--end::Item-->
											</ul>
											<!--end::Breadcrumb-->
											<!--begin::Title-->
											<h1
												class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bolder fs-1 lh-0">
												Öğrenci Detay</h1>
											<!--end::Title-->
										</div>
										<!--end::Page title-->
									</div>
									<!--end::Toolbar wrapper-->
								</div>
								<!--end::Toolbar container-->
							</div>
							<!--end::Toolbar-->
							<!--begin::Content-->
							<div id="kt_app_content" class="app-content flex-column-fluid">
								<!--begin::Content container-->
								<div id="kt_app_content_container" class="app-container container-fluid">
									<!--begin::Navbar-->
									<div class="card mb-5 mb-xxl-8">
										<div class="card-body pt-9 pb-0">
											<!--begin::Details-->
											<div class="d-flex flex-wrap flex-sm-nowrap">
												<!--begin: Pic-->
												<div class="me-7 mb-4">
													<div
														class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
														<img src="assets/media/profile/<?php echo $studentInfo['photo'] ?>"
															alt="image" />
														<!-- <div class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px"></div> -->
													</div>
												</div>
												<!--end::Pic-->
												<!--begin::Info-->
												<div class="flex-grow-1">
													<!--begin::Title-->
													<div
														class="d-flex justify-content-between align-items-start flex-wrap mb-2">
														<!--begin::User-->
														<div class="d-flex flex-column">
															<!--begin::Name-->
															<div class="d-flex align-items-center mb-2">
																<a href="#"
																	class="text-gray-900 text-hover-primary fs-2 fw-bold me-1"><?php echo $studentInfo['name'] . ' ' . $studentInfo['surname'] ?>
																</a>
															</div>
															<!--end::Name-->
															<!--begin::Info-->
															<div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
																<span
																	class="d-flex align-items-center text-gray-500 me-5 mb-2">
																	<i class="fa-solid fa-school fs-4 me-1"></i>
																	<?php echo $schoolInfo['name']; ?>
																</span>
																<a href="tel:<?php echo $studentInfo['telephone']; ?>"
																	class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
																	<i class="fa-solid fa-phone fs-4 me-1"></i>
																	<?php echo $studentInfo['telephone']; ?>
																</a>
																<a href="mailto:<?php echo $studentInfo['email']; ?>"
																	class="d-flex align-items-center text-gray-500 text-hover-primary mb-2">
																	<i class="ki-duotone ki-sms fs-4 me-1">
																		<span class="path1"></span>
																		<span class="path2"></span>
																	</i>
																	<?php echo $studentInfo['email']; ?>
																</a>
															</div>
															<!--end::Info-->
														</div>
														<!--end::User-->
														<!--begin::Actions-->
													</div>
													<!--end::Title-->
													<!--begin::Stats-->
													<div class="d-flex flex-wrap flex-stack">
														<!--begin::Wrapper-->
														<div class="d-flex flex-column flex-grow-1 pe-8">
															<!--begin::Stats-->
															<div class="d-flex flex-wrap">
																<!--begin::Stat-->
																<div
																	class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
																	<!--begin::Number-->
																	<div class="d-flex align-items-center">
																		<i
																			class="fa-regular fa-clock fs-2 text-success me-2"></i>
																		<div class="fs-2 fw-bold">
																			<?php echo $timeSpend->saniyeyiGoster($timeSpendInfo); ?>
																		</div>
																	</div>
																	<!--end::Number-->
																	<!--begin::Label-->
																	<div class="fw-semibold fs-6 text-gray-500">Toplam
																		Geçirilen Süre</div>
																	<!--end::Label-->
																</div>
																<!--end::Stat-->
																<!--begin::Stat-->
																<div
																	class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
																	<!--begin::Number-->
																	<div class="d-flex align-items-center">
																		<i
																			class="ki-duotone ki-book-open fs-2 text-success me-2">
																			<span class="path1"></span>
																			<span class="path2"></span>
																			<span class="path3"></span>
																			<span class="path4"></span>
																		</i>
																		<div class="fs-2 fw-bold" data-kt-countup="true"
																			data-kt-countup-value="<?php echo count($studentPackages); ?>">
																			0</div>
																	</div>
																	<!--end::Number-->
																	<!--begin::Label-->
																	<div class="fw-semibold fs-6 text-gray-500">Alınan Paket
																		Sayısı</div>
																	<!--end::Label-->
																</div>
																<!--end::Stat-->
																<!--begin::Stat-->
																<div
																	class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
																	<!--begin::Number-->
																	<div class="d-flex align-items-center">
																		<i
																			class="ki-duotone ki-brifecase-tick fs-2 text-success me-2">
																			<span class="path1"></span>
																			<span class="path2"></span>
																			<span class="path3"></span>
																		</i>
																		<div class="fs-2 fw-bold" data-kt-countup="true"
																			data-kt-countup-value="<?php echo count($studentAdditionalPackages); ?>">
																			0</div>
																	</div>
																	<!--end::Number-->
																	<!--begin::Label-->
																	<div class="fw-semibold fs-6 text-gray-500">Alınan Ek
																		Paket Sayısı</div>
																	<!--end::Label-->
																</div>
																<!--end::Stat-->
															</div>
															<!--end::Stats-->
														</div>
														<!--end::Wrapper-->
				
													</div>
													<!--end::Stats-->
												</div>
												<!--end::Info-->
											</div>
											<!--end::Details-->
											<!--begin::Navs-->
											<ul
												class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
												<!--begin::Nav item-->
												<li class="nav-item mt-2">
													<a class="nav-link text-active-primary ms-0 me-10 py-5 active"
														data-bs-toggle="pill" href="#genel_bakis">Genel Bakış</a>
												</li>
												<!--end::Nav item-->
												<!--begin::Nav item-->
												<li class="nav-item mt-2">
													<a class="nav-link text-active-primary ms-0 me-10 py-5"
														data-bs-toggle="pill" href="#dersler">Dersler</a>
												</li>
												<li class="nav-item mt-2">
													<a class="nav-link text-active-primary ms-0 me-10 py-5"
														data-bs-toggle="pill" href="#uniteler">İlerleme ve Performans</a>
												</li>
												<!--end::Nav item-->
												<!--begin::Nav item-->
												<li class="nav-item mt-2">
													<a class="nav-link text-active-primary ms-0 me-10 py-5"
														data-bs-toggle="pill" href="#ozel_dersler">Özel Dersler</a>
												</li>
												<!--end::Nav item-->
												<!--begin::Nav item-->
												<li class="nav-item mt-2">
													<a class="nav-link text-active-primary ms-0 me-10 py-5"
														data-bs-toggle="pill" href="#grup_dersler">Grup Dersleri</a>
												</li>
												<!--end::Nav item-->
												<!--begin::Nav item-->
												<li class="nav-item mt-2">
													<a class="nav-link text-active-primary ms-0 me-10 py-5"
														data-bs-toggle="pill" href="#paketler">Paketler</a>
												</li>
												<!--end::Nav item-->
												<!--begin::Nav item-->
												<li class="nav-item mt-2">
													<a class="nav-link text-active-primary ms-0 me-10 py-5"
														data-bs-toggle="pill" href="#hareketler">Hareketler</a>
												</li>
												<!--end::Nav item-->
											</ul>
											<!--begin::Navs-->
										</div>
									</div>
									<!--end::Navbar-->
									<div class="tab-content">
										<!--begin::Row-->
										<div class="tab-pane fade show active" id="genel_bakis">
											<div class="row g-5 g-xxl-8">
												<!--begin::Col-->
												<div class="col-xl-6">
													<!--begin::List widget 20-->
													<div class="card mb-5 mb-xl-8">
														<!--begin::Header-->
														<div class="card-header border-0 pt-5">
															<h3 class="card-title align-items-start flex-column">
																<span
																	class="card-label fw-bold text-gray-900">Dersler</span>
																<span
																	class="text-muted mt-1 fw-semibold fs-7"><?php echo $studentClassName[0]['name']; ?></span>
															</h3>
															<!--begin::Toolbar-->
															<!-- <div class="card-toolbar">
																<a data-bs-toggle="pill" href="#kt_stats_widget_2_tab_22" class="btn btn-sm btn-light">Tüm Dersler</a>
															</div> -->
															<!--end::Toolbar-->
														</div>
														<!--end::Header-->
														<!--begin::Body-->
														<div class="card-body pt-6">
															<?php $student->showLessonsListForStudentDetails($studentInfo['id'], $studentInfo['class_id'], $studentInfo['school_id']); ?>
															<!--begin::Item-->
														</div>
														<!--end::Body-->
													</div>
													<!--end::List widget 20-->
													<!--begin::List widget 23-->
													<div class="card card-flush mb-5 mb-xl-8">
														<!--begin::Header-->
														<div class="card-header pt-7">
															<!--begin::Title-->
															<h3 class="card-title align-items-start flex-column">
																<span class="card-label fw-bold text-gray-800">Özel
																	Dersler</span>
															</h3>
															<!--end::Title-->
															<!--begin::Toolbar-->
															<div class="card-toolbar"></div>
															<!--end::Toolbar-->
														</div>
														<!--end::Header-->
														<!--begin::Body-->
														<div class="card-body pt-5">
															<!--begin::Items-->
															<div class="">
																<!--begin::Item-->
																<div class="d-flex flex-stack">
																	<!--begin::Section-->
																	<div class="d-flex align-items-center me-5">
																		<!--begin::Flag-->
																		<i
																			class="fa-solid fa-person-chalkboard text-muted fs-1 me-8"></i>
																		<!--end::Flag-->
																		<!--begin::Content-->
																		<div class="me-5">
																			<!--begin::Title-->
																			<a href="#"
																				class="text-gray-800 fw-bold text-hover-primary fs-6">Özel
																				Ders</a>
																			<!--end::Title-->
																			<!--begin::Desc-->
																			<span
																				class="text-gray-500 fw-semibold fs-7 d-block text-start ps-0">Öğretmen</span>
																			<!--end::Desc-->
																		</div>
																		<!--end::Content-->
																	</div>
																	<!--end::Section-->
																	<!--begin::Wrapper-->
																	<div class="d-flex align-items-center">
																		<!--begin::Number-->
																		<span
																			class="text-gray-800 fw-bold fs-4 me-3">45</span>
																		<!--end::Number-->
																		<!--begin::Info-->
																		<div class="m-0">
																			<!--begin::Label-->
																			<span class="badge badge-light-success fs-base">
																				<i
																					class="ki-duotone ki-arrow-up fs-5 text-success ms-n1">
																					<span class="path1"></span>
																					<span class="path2"></span>
																				</i>Dk</span>
																			<!--end::Label-->
																		</div>
																		<!--end::Info-->
																	</div>
																	<!--end::Wrapper-->
																</div>
																<!--end::Item-->
																<!--begin::Separator-->
																<div class="separator separator-dashed my-3"></div>
																<!--end::Separator-->
															</div>
															<!--end::Items-->
														</div>
														<!--end: Card Body-->
													</div>
													<!--end::List widget 23-->
												</div>
												<!--end::Col-->
												<!--begin::Col-->
												<div class="col-xl-6">
													<!--begin::Table widget 2-->
													<div class="card mb-5 mb-xl-8">
														<!--begin::Header-->
														<div class="card-header align-items-center border-0">
															<!--begin::Title-->
															<h3 class="fw-bold text-gray-900 m-0">Alınan Paketler</h3>
															<!--end::Title-->
														</div>
														<!--end::Header-->
														<!--begin::Body-->
														<div class="card-body pt-2">
															<!--begin::Nav-->
															<ul class="nav nav-pills nav-pills-custom mb-3">
																<!--begin::Item-->
																<li class="nav-item mb-3 me-3 me-lg-6">
																	<!--begin::Link-->
																	<a class="nav-link d-flex justify-content-between flex-column flex-center overflow-hidden active w-110px h-85px py-4"
																		data-bs-toggle="pill"
																		href="#kt_stats_widget_2_tab_1">
																		<!--begin::Icon-->
																		<div class="nav-icon">
																			<img alt=""
																				src="assets/media/svg/files/folder-document-dark.svg"
																				class="" />
																		</div>
																		<!--end::Icon-->
																		<!--begin::Subtitle-->
																		<span
																			class="nav-text text-gray-700 fw-bold fs-6 lh-1">Paketler</span>
																		<!--end::Subtitle-->
																		<!--begin::Bullet-->
																		<span
																			class="bullet-custom position-absolute bottom-0 w-100 h-4px bg-primary"></span>
																		<!--end::Bullet-->
																	</a>
																	<!--end::Link-->
																</li>
																<!--end::Item-->
																<!--begin::Item-->
																<li class="nav-item mb-3 me-3 me-lg-6">
																	<!--begin::Link-->
																	<a class="nav-link d-flex justify-content-between flex-column flex-center overflow-hidden w-110px h-85px py-4"
																		data-bs-toggle="pill"
																		href="#kt_stats_widget_2_tab_2">
																		<!--begin::Icon-->
																		<div class="nav-icon">
																			<img alt=""
																				src="assets/media/svg/files/folder-document.svg"
																				class="" />
																		</div>
																		<!--end::Icon-->
																		<!--begin::Subtitle-->
																		<span
																			class="nav-text text-gray-700 fw-bold fs-6 lh-1">Ek
																			Paketler</span>
																		<!--end::Subtitle-->
																		<!--begin::Bullet-->
																		<span
																			class="bullet-custom position-absolute bottom-0 w-100 h-4px bg-primary"></span>
																		<!--end::Bullet-->
																	</a>
																	<!--end::Link-->
																</li>
																<!--end::Item-->
															</ul>
															<!--end::Nav-->
															<!--begin::Tab Content-->
															<div class="tab-content">
																<!--begin::Tap pane-->
																<div class="tab-pane fade show active"
																	id="kt_stats_widget_2_tab_1">
																	<!--begin::Table container-->
																	<div class="table-responsive">
																		<!--begin::Table-->
																		<table
																			class="table table-row-dashed align-middle gs-0 gy-4 my-0">
																			<!--begin::Table head-->
																			<thead>
																				<tr
																					class="fs-7 fw-bold text-gray-500 border-bottom-0">
																					<th style="width: 50%;"
																						class="ps-0 min-w-200px">PAKET ADI
																					</th>
																					<th style="width: 25%;"
																						class="text-end min-w-100px">FİYATI
																					</th>
																					<th style="width: 25%;"
																						class="pe-0 text-end min-w-100px">
																						BİTİŞ TARİHİ</th>
																				</tr>
																			</thead>
																			<!--end::Table head-->
																			<!--begin::Table body-->
																			<tbody>

																				<?php $student->showPackageListForStudentDetails($getStudentId); ?>

																			</tbody>
																			<!--end::Table body-->
																		</table>
																		<!--end::Table-->
																	</div>
																	<!--end::Table container-->
																</div>
																<!--end::Tap pane-->
																<!--begin::Tap pane-->
																<div class="tab-pane fade" id="kt_stats_widget_2_tab_2">
																	<!--begin::Table container-->
																	<div class="table-responsive">
																		<!--begin::Table-->
																		<table
																			class="table table-row-dashed align-middle gs-0 gy-4 my-0">
																			<!--begin::Table head-->
																			<thead>
																				<tr
																					class="fs-7 fw-bold text-gray-500 border-bottom-0">
																					<th style="width: 50%;"
																						class="ps-0 min-w-200px">PAKET ADI
																					</th>
																					<th style="width: 25%;"
																						class="text-end min-w-100px">FİYATI
																					</th>
																					<th style="width: 25%;"
																						class="pe-0 text-end min-w-100px">
																						BİTİŞ TARİHİ</th>
																				</tr>
																			</thead>
																			<!--end::Table head-->
																			<!--begin::Table body-->
																			<tbody>
																				<?php $student->showAdditionalPackageListForStudentDetails($getStudentId); ?>
																			</tbody>
																			<!--end::Table body-->
																		</table>
																		<!--end::Table-->
																	</div>
																	<!--end::Table container-->
																</div>
																<!--end::Tap pane-->
															</div>
															<!--end::Tab Content-->
														</div>
														<!--end: Card Body-->
													</div>
													<!--end::Table widget 2-->

													<!--begin::List widget 23-->
													<div class="card card-flush mb-5 mb-xl-8">
														<!--begin::Header-->
														<div class="card-header pt-7">
															<!--begin::Title-->
															<h3 class="card-title align-items-start flex-column">
																<span class="card-label fw-bold text-gray-800">Grup
																	Dersler</span>
															</h3>
															<!--end::Title-->
															<!--begin::Toolbar-->
															<div class="card-toolbar"></div>
															<!--end::Toolbar-->
														</div>
														<!--end::Header-->
														<!--begin::Body-->
														<div class="card-body pt-5">
															<!--begin::Items-->
															<div class="">
																<!--begin::Item-->
																<div class="d-flex flex-stack">
																	<!--begin::Section-->
																	<div class="d-flex align-items-center me-5">
																		<!--begin::Flag-->
																		<i
																			class="fa-solid fa-users text-muted fs-1 me-8"></i>
																		<!--end::Flag-->
																		<!--begin::Content-->
																		<div class="me-5">
																			<!--begin::Title-->
																			<a href="#"
																				class="text-gray-800 fw-bold text-hover-primary fs-6">Grup
																				Ders</a>
																			<!--end::Title-->
																			<!--begin::Desc-->
																			<span
																				class="text-gray-500 fw-semibold fs-7 d-block text-start ps-0">Öğretmen</span>
																			<!--end::Desc-->
																		</div>
																		<!--end::Content-->
																	</div>
																	<!--end::Section-->
																	<!--begin::Wrapper-->
																	<div class="d-flex align-items-center">
																		<!--begin::Number-->
																		<span
																			class="text-gray-800 fw-bold fs-4 me-3">45</span>
																		<!--end::Number-->
																		<!--begin::Info-->
																		<div class="m-0">
																			<!--begin::Label-->
																			<span class="badge badge-light-success fs-base">
																				<i
																					class="ki-duotone ki-arrow-up fs-5 text-success ms-n1">
																					<span class="path1"></span>
																					<span class="path2"></span>
																				</i>Dk</span>
																			<!--end::Label-->
																		</div>
																		<!--end::Info-->
																	</div>
																	<!--end::Wrapper-->
																</div>
																<!--end::Item-->
																<!--begin::Separator-->
																<div class="separator separator-dashed my-3"></div>
																<!--end::Separator-->
															</div>
															<!--end::Items-->
														</div>
														<!--end: Card Body-->
													</div>
													<!--end::List widget 23-->
													<!--begin::Timeline widget 2-->
													<div class="card mb-5 mb-xl-8" id="kt_timeline_widget_2_card">
														<!--begin::Header-->
														<div class="card-header position-relative py-0 border-bottom-2">
															<!--begin::Nav-->
															<ul
																class="nav nav-stretch nav-pills nav-pills-custom d-flex mt-3">
																<!--begin::Item-->
																<li class="nav-item p-0 ms-0 me-8">
																	<!--begin::Link-->
																	<a class="nav-link btn btn-color-muted active px-0"
																		data-bs-toggle="pill"
																		href="#kt_timeline_widget_2_tab_1">
																		<!--begin::Subtitle-->
																		<span class="nav-text fw-semibold fs-4 mb-3">Today
																			Homeworks</span>
																		<!--end::Subtitle-->
																		<!--begin::Bullet-->
																		<span
																			class="bullet-custom position-absolute z-index-2 w-100 h-2px top-100 bottom-n100 bg-primary rounded"></span>
																		<!--end::Bullet-->
																	</a>
																	<!--end::Link-->
																</li>
																<!--end::Item-->
																<!--begin::Item-->
																<li class="nav-item p-0 ms-0 me-8">
																	<!--begin::Link-->
																	<a class="nav-link btn btn-color-muted px-0"
																		data-bs-toggle="pill"
																		href="#kt_timeline_widget_2_tab_2">
																		<!--begin::Subtitle-->
																		<span
																			class="nav-text fw-semibold fs-4 mb-3">Recent</span>
																		<!--end::Subtitle-->
																		<!--begin::Bullet-->
																		<span
																			class="bullet-custom position-absolute z-index-2 w-100 h-2px top-100 bottom-n100 bg-primary rounded"></span>
																		<!--end::Bullet-->
																	</a>
																	<!--end::Link-->
																</li>
																<!--end::Item-->
																<!--begin::Item-->
																<li class="nav-item p-0 ms-0">
																	<!--begin::Link-->
																	<a class="nav-link btn btn-color-muted px-0"
																		data-bs-toggle="pill"
																		href="#kt_timeline_widget_2_tab_3">
																		<!--begin::Subtitle-->
																		<span
																			class="nav-text fw-semibold fs-4 mb-3">Future</span>
																		<!--end::Subtitle-->
																		<!--begin::Bullet-->
																		<span
																			class="bullet-custom position-absolute z-index-2 w-100 h-2px top-100 bottom-n100 bg-primary rounded"></span>
																		<!--end::Bullet-->
																	</a>
																	<!--end::Link-->
																</li>
																<!--end::Item-->
															</ul>
															<!--end::Nav-->
														</div>
														<!--end::Header-->
														<!--begin::Body-->
														<div class="card-body">
															<!--begin::Tab Content-->
															<div class="tab-content">
																<!--begin::Tap pane-->
																<div class="tab-pane fade show active"
																	id="kt_timeline_widget_2_tab_1">
																	<!--begin::Table container-->
																	<div class="table-responsive">
																		<!--begin::Table-->
																		<table class="table align-middle gs-0 gy-4">
																			<!--begin::Table head-->
																			<thead>
																				<tr>
																					<th class="p-0 w-10px"></th>
																					<th class="p-0 w-25px"></th>
																					<th class="p-0 min-w-400px"></th>
																					<th class="p-0 min-w-100px"></th>
																					<th class="p-0 min-w-125px"></th>
																				</tr>
																			</thead>
																			<!--end::Table head-->
																			<!--begin::Table body-->
																			<tbody>
																				<tr>
																					<td>
																						<span data-kt-element="bullet"
																							class="bullet bullet-vertical d-flex align-items-center h-40px bg-success"></span>
																					</td>
																					<td class="ps-0">
																						<div
																							class="form-check form-check-custom form-check-success form-check-solid">
																							<input class="form-check-input"
																								type="checkbox" value=""
																								checked="checked"
																								data-kt-element="checkbox" />
																						</div>
																					</td>
																					<td>
																						<a href="#"
																							class="text-gray-800 text-hover-primary fw-bold fs-6">Book
																							p. 77-85, read & complete tasks
																							1-6 on p. 85</a>
																						<span
																							class="text-gray-500 fw-bold fs-7 d-block">Physics</span>
																					</td>
																					<td class="text-end">
																						<span data-kt-element="status"
																							class="badge badge-light-success">Done</span>
																					</td>
																					<td class="text-end">
																						<!--begin::Icon-->
																						<div
																							class="d-flex justify-content-end flex-shrink-0">
																							<!--begin::Print-->
																							<a href="#"
																								class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
																								<i
																									class="ki-duotone ki-printer fs-3">
																									<span
																										class="path1"></span>
																									<span
																										class="path2"></span>
																									<span
																										class="path3"></span>
																									<span
																										class="path4"></span>
																									<span
																										class="path5"></span>
																								</i>
																							</a>
																							<!--end::Print-->
																							<!--begin::Chat-->
																							<a href="#"
																								class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
																								<i
																									class="ki-duotone ki-sms fs-3">
																									<span
																										class="path1"></span>
																									<span
																										class="path2"></span>
																								</i>
																							</a>
																							<!--end::Chat-->
																							<!--begin::Attach-->
																							<a href="#"
																								class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm">
																								<i
																									class="ki-duotone ki-paper-clip fs-3"></i>
																							</a>
																							<!--end::Attach-->
																						</div>
																						<!--end::Icon-->
																					</td>
																				</tr>
																				<tr>
																					<td>
																						<span data-kt-element="bullet"
																							class="bullet bullet-vertical d-flex align-items-center h-40px bg-primary"></span>
																					</td>
																					<td class="ps-0">
																						<div
																							class="form-check form-check-custom form-check-solid">
																							<input class="form-check-input"
																								type="checkbox" value=""
																								data-kt-element="checkbox" />
																						</div>
																					</td>
																					<td>
																						<a href="#"
																							class="text-gray-800 text-hover-primary fw-bold fs-6">Workbook
																							p. 17, tasks 1-6</a>
																						<span
																							class="text-gray-500 fw-bold fs-7 d-block">Mathematics</span>
																					</td>
																					<td class="text-end">
																						<span data-kt-element="status"
																							class="badge badge-light-primary">In
																							Process</span>
																					</td>
																					<td class="text-end">
																						<!--begin::Icon-->
																						<div
																							class="d-flex justify-content-end flex-shrink-0">
																							<!--begin::Print-->
																							<a href="#"
																								class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
																								<i
																									class="ki-duotone ki-printer fs-3">
																									<span
																										class="path1"></span>
																									<span
																										class="path2"></span>
																									<span
																										class="path3"></span>
																									<span
																										class="path4"></span>
																									<span
																										class="path5"></span>
																								</i>
																							</a>
																							<!--end::Print-->
																							<!--begin::Chat-->
																							<a href="#"
																								class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
																								<i
																									class="ki-duotone ki-sms fs-3">
																									<span
																										class="path1"></span>
																									<span
																										class="path2"></span>
																								</i>
																							</a>
																							<!--end::Chat-->
																							<!--begin::Attach-->
																							<a href="#"
																								class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm">
																								<i
																									class="ki-duotone ki-paper-clip fs-3"></i>
																							</a>
																							<!--end::Attach-->
																						</div>
																						<!--end::Icon-->
																					</td>
																				</tr>
																				<tr>
																					<td>
																						<span data-kt-element="bullet"
																							class="bullet bullet-vertical d-flex align-items-center h-40px bg-success"></span>
																					</td>
																					<td class="ps-0">
																						<div
																							class="form-check form-check-custom form-check-success form-check-solid">
																							<input class="form-check-input"
																								type="checkbox" value=""
																								checked="checked"
																								data-kt-element="checkbox" />
																						</div>
																					</td>
																					<td>
																						<a href="#"
																							class="text-gray-800 text-hover-primary fw-bold fs-6">Learn
																							paragraph p. 99, Exercise
																							1,2,3Scoping & Estimations</a>
																						<span
																							class="text-gray-500 fw-bold fs-7 d-block">Chemistry</span>
																					</td>
																					<td class="text-end">
																						<span data-kt-element="status"
																							class="badge badge-light-success">Done</span>
																					</td>
																					<td class="text-end">
																						<!--begin::Icon-->
																						<div
																							class="d-flex justify-content-end flex-shrink-0">
																							<!--begin::Print-->
																							<a href="#"
																								class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
																								<i
																									class="ki-duotone ki-printer fs-3">
																									<span
																										class="path1"></span>
																									<span
																										class="path2"></span>
																									<span
																										class="path3"></span>
																									<span
																										class="path4"></span>
																									<span
																										class="path5"></span>
																								</i>
																							</a>
																							<!--end::Print-->
																							<!--begin::Chat-->
																							<a href="#"
																								class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
																								<i
																									class="ki-duotone ki-sms fs-3">
																									<span
																										class="path1"></span>
																									<span
																										class="path2"></span>
																								</i>
																							</a>
																							<!--end::Chat-->
																							<!--begin::Attach-->
																							<a href="#"
																								class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm">
																								<i
																									class="ki-duotone ki-paper-clip fs-3"></i>
																							</a>
																							<!--end::Attach-->
																						</div>
																						<!--end::Icon-->
																					</td>
																				</tr>
																				<tr>
																					<td>
																						<span data-kt-element="bullet"
																							class="bullet bullet-vertical d-flex align-items-center h-40px bg-primary"></span>
																					</td>
																					<td class="ps-0">
																						<div
																							class="form-check form-check-custom form-check-solid">
																							<input class="form-check-input"
																								type="checkbox" value=""
																								data-kt-element="checkbox" />
																						</div>
																					</td>
																					<td>
																						<a href="#"
																							class="text-gray-800 text-hover-primary fw-bold fs-6">Write
																							essay 1000 words “WW2
																							results”</a>
																						<span
																							class="text-gray-500 fw-bold fs-7 d-block">History</span>
																					</td>
																					<td class="text-end">
																						<span data-kt-element="status"
																							class="badge badge-light-primary">In
																							Process</span>
																					</td>
																					<td class="text-end">
																						<!--begin::Icon-->
																						<div
																							class="d-flex justify-content-end flex-shrink-0">
																							<!--begin::Print-->
																							<a href="#"
																								class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
																								<i
																									class="ki-duotone ki-printer fs-3">
																									<span
																										class="path1"></span>
																									<span
																										class="path2"></span>
																									<span
																										class="path3"></span>
																									<span
																										class="path4"></span>
																									<span
																										class="path5"></span>
																								</i>
																							</a>
																							<!--end::Print-->
																							<!--begin::Chat-->
																							<a href="#"
																								class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
																								<i
																									class="ki-duotone ki-sms fs-3">
																									<span
																										class="path1"></span>
																									<span
																										class="path2"></span>
																								</i>
																							</a>
																							<!--end::Chat-->
																							<!--begin::Attach-->
																							<a href="#"
																								class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm">
																								<i
																									class="ki-duotone ki-paper-clip fs-3"></i>
																							</a>
																							<!--end::Attach-->
																						</div>
																						<!--end::Icon-->
																					</td>
																				</tr>
																				<tr>
																					<td>
																						<span data-kt-element="bullet"
																							class="bullet bullet-vertical d-flex align-items-center h-40px bg-primary"></span>
																					</td>
																					<td class="ps-0">
																						<div
																							class="form-check form-check-custom form-check-solid">
																							<input class="form-check-input"
																								type="checkbox" value=""
																								data-kt-element="checkbox" />
																						</div>
																					</td>
																					<td>
																						<a href="#"
																							class="text-gray-800 text-hover-primary fw-bold fs-6">Internal
																							conflicts in Philip Larkin
																							poems, read p 380-515</a>
																						<span
																							class="text-gray-500 fw-bold fs-7 d-block">English
																							Language</span>
																					</td>
																					<td class="text-end">
																						<span data-kt-element="status"
																							class="badge badge-light-primary">In
																							Process</span>
																					</td>
																					<td class="text-end">
																						<!--begin::Icon-->
																						<div
																							class="d-flex justify-content-end flex-shrink-0">
																							<!--begin::Print-->
																							<a href="#"
																								class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
																								<i
																									class="ki-duotone ki-printer fs-3">
																									<span
																										class="path1"></span>
																									<span
																										class="path2"></span>
																									<span
																										class="path3"></span>
																									<span
																										class="path4"></span>
																									<span
																										class="path5"></span>
																								</i>
																							</a>
																							<!--end::Print-->
																							<!--begin::Chat-->
																							<a href="#"
																								class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
																								<i
																									class="ki-duotone ki-sms fs-3">
																									<span
																										class="path1"></span>
																									<span
																										class="path2"></span>
																								</i>
																							</a>
																							<!--end::Chat-->
																							<!--begin::Attach-->
																							<a href="#"
																								class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm">
																								<i
																									class="ki-duotone ki-paper-clip fs-3"></i>
																							</a>
																							<!--end::Attach-->
																						</div>
																						<!--end::Icon-->
																					</td>
																				</tr>
																			</tbody>
																			<!--end::Table body-->
																		</table>
																	</div>
																	<!--end::Table-->
																</div>
																<!--end::Tap pane-->
																<!--begin::Tap pane-->
																<div class="tab-pane fade" id="kt_timeline_widget_2_tab_2">
																	<!--begin::Table container-->
																	<div class="table-responsive">
																		<!--begin::Table-->
																		<table class="table align-middle gs-0 gy-4">
																			<!--begin::Table head-->
																			<thead>
																				<tr>
																					<th class="p-0 w-10px"></th>
																					<th class="p-0 w-25px"></th>
																					<th class="p-0 min-w-400px"></th>
																					<th class="p-0 min-w-100px"></th>
																					<th class="p-0 min-w-125px"></th>
																				</tr>
																			</thead>
																			<!--end::Table head-->
																			<!--begin::Table body-->
																			<tbody>
																				<tr>
																					<td>
																						<span data-kt-element="bullet"
																							class="bullet bullet-vertical d-flex align-items-center h-40px bg-success"></span>
																					</td>
																					<td class="ps-0">
																						<div
																							class="form-check form-check-custom form-check-success form-check-solid">
																							<input class="form-check-input"
																								type="checkbox" value=""
																								checked="checked"
																								data-kt-element="checkbox" />
																						</div>
																					</td>
																					<td>
																						<a href="#"
																							class="text-gray-800 text-hover-primary fw-bold fs-6">Book
																							p. 77-85, read & complete tasks
																							1-6 on p. 85</a>
																						<span
																							class="text-gray-500 fw-bold fs-7 d-block">Physics</span>
																					</td>
																					<td class="text-end">
																						<span data-kt-element="status"
																							class="badge badge-light-success">Done</span>
																					</td>
																					<td class="text-end">
																						<!--begin::Icon-->
																						<div
																							class="d-flex justify-content-end flex-shrink-0">
																							<!--begin::Print-->
																							<a href="#"
																								class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
																								<i
																									class="ki-duotone ki-printer fs-3">
																									<span
																										class="path1"></span>
																									<span
																										class="path2"></span>
																									<span
																										class="path3"></span>
																									<span
																										class="path4"></span>
																									<span
																										class="path5"></span>
																								</i>
																							</a>
																							<!--end::Print-->
																							<!--begin::Chat-->
																							<a href="#"
																								class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
																								<i
																									class="ki-duotone ki-sms fs-3">
																									<span
																										class="path1"></span>
																									<span
																										class="path2"></span>
																								</i>
																							</a>
																							<!--end::Chat-->
																							<!--begin::Attach-->
																							<a href="#"
																								class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm">
																								<i
																									class="ki-duotone ki-paper-clip fs-3"></i>
																							</a>
																							<!--end::Attach-->
																						</div>
																						<!--end::Icon-->
																					</td>
																				</tr>
																				<tr>
																					<td>
																						<span data-kt-element="bullet"
																							class="bullet bullet-vertical d-flex align-items-center h-40px bg-primary"></span>
																					</td>
																					<td class="ps-0">
																						<div
																							class="form-check form-check-custom form-check-solid">
																							<input class="form-check-input"
																								type="checkbox" value=""
																								data-kt-element="checkbox" />
																						</div>
																					</td>
																					<td>
																						<a href="#"
																							class="text-gray-800 text-hover-primary fw-bold fs-6">Workbook
																							p. 17, tasks 1-6</a>
																						<span
																							class="text-gray-500 fw-bold fs-7 d-block">Mathematics</span>
																					</td>
																					<td class="text-end">
																						<span data-kt-element="status"
																							class="badge badge-light-primary">In
																							Process</span>
																					</td>
																					<td class="text-end">
																						<!--begin::Icon-->
																						<div
																							class="d-flex justify-content-end flex-shrink-0">
																							<!--begin::Print-->
																							<a href="#"
																								class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
																								<i
																									class="ki-duotone ki-printer fs-3">
																									<span
																										class="path1"></span>
																									<span
																										class="path2"></span>
																									<span
																										class="path3"></span>
																									<span
																										class="path4"></span>
																									<span
																										class="path5"></span>
																								</i>
																							</a>
																							<!--end::Print-->
																							<!--begin::Chat-->
																							<a href="#"
																								class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
																								<i
																									class="ki-duotone ki-sms fs-3">
																									<span
																										class="path1"></span>
																									<span
																										class="path2"></span>
																								</i>
																							</a>
																							<!--end::Chat-->
																							<!--begin::Attach-->
																							<a href="#"
																								class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm">
																								<i
																									class="ki-duotone ki-paper-clip fs-3"></i>
																							</a>
																							<!--end::Attach-->
																						</div>
																						<!--end::Icon-->
																					</td>
																				</tr>
																				<tr>
																					<td>
																						<span data-kt-element="bullet"
																							class="bullet bullet-vertical d-flex align-items-center h-40px bg-success"></span>
																					</td>
																					<td class="ps-0">
																						<div
																							class="form-check form-check-custom form-check-success form-check-solid">
																							<input class="form-check-input"
																								type="checkbox" value=""
																								checked="checked"
																								data-kt-element="checkbox" />
																						</div>
																					</td>
																					<td>
																						<a href="#"
																							class="text-gray-800 text-hover-primary fw-bold fs-6">Learn
																							paragraph p. 99, Exercise
																							1,2,3Scoping & Estimations</a>
																						<span
																							class="text-gray-500 fw-bold fs-7 d-block">Chemistry</span>
																					</td>
																					<td class="text-end">
																						<span data-kt-element="status"
																							class="badge badge-light-success">Done</span>
																					</td>
																					<td class="text-end">
																						<!--begin::Icon-->
																						<div
																							class="d-flex justify-content-end flex-shrink-0">
																							<!--begin::Print-->
																							<a href="#"
																								class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
																								<i
																									class="ki-duotone ki-printer fs-3">
																									<span
																										class="path1"></span>
																									<span
																										class="path2"></span>
																									<span
																										class="path3"></span>
																									<span
																										class="path4"></span>
																									<span
																										class="path5"></span>
																								</i>
																							</a>
																							<!--end::Print-->
																							<!--begin::Chat-->
																							<a href="#"
																								class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
																								<i
																									class="ki-duotone ki-sms fs-3">
																									<span
																										class="path1"></span>
																									<span
																										class="path2"></span>
																								</i>
																							</a>
																							<!--end::Chat-->
																							<!--begin::Attach-->
																							<a href="#"
																								class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm">
																								<i
																									class="ki-duotone ki-paper-clip fs-3"></i>
																							</a>
																							<!--end::Attach-->
																						</div>
																						<!--end::Icon-->
																					</td>
																				</tr>
																				<tr>
																					<td>
																						<span data-kt-element="bullet"
																							class="bullet bullet-vertical d-flex align-items-center h-40px bg-primary"></span>
																					</td>
																					<td class="ps-0">
																						<div
																							class="form-check form-check-custom form-check-solid">
																							<input class="form-check-input"
																								type="checkbox" value=""
																								data-kt-element="checkbox" />
																						</div>
																					</td>
																					<td>
																						<a href="#"
																							class="text-gray-800 text-hover-primary fw-bold fs-6">Write
																							essay 1000 words “WW2
																							results”</a>
																						<span
																							class="text-gray-500 fw-bold fs-7 d-block">History</span>
																					</td>
																					<td class="text-end">
																						<span data-kt-element="status"
																							class="badge badge-light-primary">In
																							Process</span>
																					</td>
																					<td class="text-end">
																						<!--begin::Icon-->
																						<div
																							class="d-flex justify-content-end flex-shrink-0">
																							<!--begin::Print-->
																							<a href="#"
																								class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
																								<i
																									class="ki-duotone ki-printer fs-3">
																									<span
																										class="path1"></span>
																									<span
																										class="path2"></span>
																									<span
																										class="path3"></span>
																									<span
																										class="path4"></span>
																									<span
																										class="path5"></span>
																								</i>
																							</a>
																							<!--end::Print-->
																							<!--begin::Chat-->
																							<a href="#"
																								class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
																								<i
																									class="ki-duotone ki-sms fs-3">
																									<span
																										class="path1"></span>
																									<span
																										class="path2"></span>
																								</i>
																							</a>
																							<!--end::Chat-->
																							<!--begin::Attach-->
																							<a href="#"
																								class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm">
																								<i
																									class="ki-duotone ki-paper-clip fs-3"></i>
																							</a>
																							<!--end::Attach-->
																						</div>
																						<!--end::Icon-->
																					</td>
																				</tr>
																			</tbody>
																			<!--end::Table body-->
																		</table>
																	</div>
																	<!--end::Table-->
																</div>
																<!--end::Tap pane-->
																<!--begin::Tap pane-->
																<div class="tab-pane fade" id="kt_timeline_widget_2_tab_3">
																	<!--begin::Table container-->
																	<div class="table-responsive">
																		<!--begin::Table-->
																		<table class="table align-middle gs-0 gy-4">
																			<!--begin::Table head-->
																			<thead>
																				<tr>
																					<th class="p-0 w-10px"></th>
																					<th class="p-0 w-25px"></th>
																					<th class="p-0 min-w-400px"></th>
																					<th class="p-0 min-w-100px"></th>
																					<th class="p-0 min-w-125px"></th>
																				</tr>
																			</thead>
																			<!--end::Table head-->
																			<!--begin::Table body-->
																			<tbody>
																				<tr>
																					<td>
																						<span data-kt-element="bullet"
																							class="bullet bullet-vertical d-flex align-items-center h-40px bg-primary"></span>
																					</td>
																					<td class="ps-0">
																						<div
																							class="form-check form-check-custom form-check-solid">
																							<input class="form-check-input"
																								type="checkbox" value=""
																								data-kt-element="checkbox" />
																						</div>
																					</td>
																					<td>
																						<a href="#"
																							class="text-gray-800 text-hover-primary fw-bold fs-6">Workbook
																							p. 17, tasks 1-6</a>
																						<span
																							class="text-gray-500 fw-bold fs-7 d-block">Mathematics</span>
																					</td>
																					<td class="text-end">
																						<span data-kt-element="status"
																							class="badge badge-light-primary">In
																							Process</span>
																					</td>
																					<td class="text-end">
																						<!--begin::Icon-->
																						<div
																							class="d-flex justify-content-end flex-shrink-0">
																							<!--begin::Print-->
																							<a href="#"
																								class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
																								<i
																									class="ki-duotone ki-printer fs-3">
																									<span
																										class="path1"></span>
																									<span
																										class="path2"></span>
																									<span
																										class="path3"></span>
																									<span
																										class="path4"></span>
																									<span
																										class="path5"></span>
																								</i>
																							</a>
																							<!--end::Print-->
																							<!--begin::Chat-->
																							<a href="#"
																								class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
																								<i
																									class="ki-duotone ki-sms fs-3">
																									<span
																										class="path1"></span>
																									<span
																										class="path2"></span>
																								</i>
																							</a>
																							<!--end::Chat-->
																							<!--begin::Attach-->
																							<a href="#"
																								class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm">
																								<i
																									class="ki-duotone ki-paper-clip fs-3"></i>
																							</a>
																							<!--end::Attach-->
																						</div>
																						<!--end::Icon-->
																					</td>
																				</tr>
																				<tr>
																					<td>
																						<span data-kt-element="bullet"
																							class="bullet bullet-vertical d-flex align-items-center h-40px bg-success"></span>
																					</td>
																					<td class="ps-0">
																						<div
																							class="form-check form-check-custom form-check-success form-check-solid">
																							<input class="form-check-input"
																								type="checkbox" value=""
																								checked="checked"
																								data-kt-element="checkbox" />
																						</div>
																					</td>
																					<td>
																						<a href="#"
																							class="text-gray-800 text-hover-primary fw-bold fs-6">Learn
																							paragraph p. 99, Exercise
																							1,2,3Scoping & Estimations</a>
																						<span
																							class="text-gray-500 fw-bold fs-7 d-block">Chemistry</span>
																					</td>
																					<td class="text-end">
																						<span data-kt-element="status"
																							class="badge badge-light-success">Done</span>
																					</td>
																					<td class="text-end">
																						<!--begin::Icon-->
																						<div
																							class="d-flex justify-content-end flex-shrink-0">
																							<!--begin::Print-->
																							<a href="#"
																								class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
																								<i
																									class="ki-duotone ki-printer fs-3">
																									<span
																										class="path1"></span>
																									<span
																										class="path2"></span>
																									<span
																										class="path3"></span>
																									<span
																										class="path4"></span>
																									<span
																										class="path5"></span>
																								</i>
																							</a>
																							<!--end::Print-->
																							<!--begin::Chat-->
																							<a href="#"
																								class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
																								<i
																									class="ki-duotone ki-sms fs-3">
																									<span
																										class="path1"></span>
																									<span
																										class="path2"></span>
																								</i>
																							</a>
																							<!--end::Chat-->
																							<!--begin::Attach-->
																							<a href="#"
																								class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm">
																								<i
																									class="ki-duotone ki-paper-clip fs-3"></i>
																							</a>
																							<!--end::Attach-->
																						</div>
																						<!--end::Icon-->
																					</td>
																				</tr>
																				<tr>
																					<td>
																						<span data-kt-element="bullet"
																							class="bullet bullet-vertical d-flex align-items-center h-40px bg-primary"></span>
																					</td>
																					<td class="ps-0">
																						<div
																							class="form-check form-check-custom form-check-solid">
																							<input class="form-check-input"
																								type="checkbox" value=""
																								data-kt-element="checkbox" />
																						</div>
																					</td>
																					<td>
																						<a href="#"
																							class="text-gray-800 text-hover-primary fw-bold fs-6">Write
																							essay 1000 words “WW2
																							results”</a>
																						<span
																							class="text-gray-500 fw-bold fs-7 d-block">History</span>
																					</td>
																					<td class="text-end">
																						<span data-kt-element="status"
																							class="badge badge-light-primary">In
																							Process</span>
																					</td>
																					<td class="text-end">
																						<!--begin::Icon-->
																						<div
																							class="d-flex justify-content-end flex-shrink-0">
																							<!--begin::Print-->
																							<a href="#"
																								class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
																								<i
																									class="ki-duotone ki-printer fs-3">
																									<span
																										class="path1"></span>
																									<span
																										class="path2"></span>
																									<span
																										class="path3"></span>
																									<span
																										class="path4"></span>
																									<span
																										class="path5"></span>
																								</i>
																							</a>
																							<!--end::Print-->
																							<!--begin::Chat-->
																							<a href="#"
																								class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
																								<i
																									class="ki-duotone ki-sms fs-3">
																									<span
																										class="path1"></span>
																									<span
																										class="path2"></span>
																								</i>
																							</a>
																							<!--end::Chat-->
																							<!--begin::Attach-->
																							<a href="#"
																								class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm">
																								<i
																									class="ki-duotone ki-paper-clip fs-3"></i>
																							</a>
																							<!--end::Attach-->
																						</div>
																						<!--end::Icon-->
																					</td>
																				</tr>
																				<tr>
																					<td>
																						<span data-kt-element="bullet"
																							class="bullet bullet-vertical d-flex align-items-center h-40px bg-primary"></span>
																					</td>
																					<td class="ps-0">
																						<div
																							class="form-check form-check-custom form-check-solid">
																							<input class="form-check-input"
																								type="checkbox" value=""
																								data-kt-element="checkbox" />
																						</div>
																					</td>
																					<td>
																						<a href="#"
																							class="text-gray-800 text-hover-primary fw-bold fs-6">Internal
																							conflicts in Philip Larkin
																							poems, read p 380-515</a>
																						<span
																							class="text-gray-500 fw-bold fs-7 d-block">English
																							Language</span>
																					</td>
																					<td class="text-end">
																						<span data-kt-element="status"
																							class="badge badge-light-primary">In
																							Process</span>
																					</td>
																					<td class="text-end">
																						<!--begin::Icon-->
																						<div
																							class="d-flex justify-content-end flex-shrink-0">
																							<!--begin::Print-->
																							<a href="#"
																								class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
																								<i
																									class="ki-duotone ki-printer fs-3">
																									<span
																										class="path1"></span>
																									<span
																										class="path2"></span>
																									<span
																										class="path3"></span>
																									<span
																										class="path4"></span>
																									<span
																										class="path5"></span>
																								</i>
																							</a>
																							<!--end::Print-->
																							<!--begin::Chat-->
																							<a href="#"
																								class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
																								<i
																									class="ki-duotone ki-sms fs-3">
																									<span
																										class="path1"></span>
																									<span
																										class="path2"></span>
																								</i>
																							</a>
																							<!--end::Chat-->
																							<!--begin::Attach-->
																							<a href="#"
																								class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm">
																								<i
																									class="ki-duotone ki-paper-clip fs-3"></i>
																							</a>
																							<!--end::Attach-->
																						</div>
																						<!--end::Icon-->
																					</td>
																				</tr>
																			</tbody>
																			<!--end::Table body-->
																		</table>
																	</div>
																	<!--end::Table-->
																</div>
																<!--end::Tap pane-->
															</div>
															<!--end::Tab Content-->
														</div>
														<!--end::Body-->
													</div>
													<!--end::Tables Widget 2-->
												</div>
												<!--end::Col-->
											</div>
										</div>
										<!--end::Row-->
										<!--begin::Row-->
										<div class="tab-pane fade" id="dersler">
											<div class="row g-5 g-xxl-8">
												<?php $student->showLessonsListForStudentDetailsPage($getStudentId, $studentInfo['class_id'], $studentInfo['school_id']); ?>
											</div>
										</div>

										<div class="tab-pane fade" id="uniteler">
											<div class="row g-5 g-xxl-8">


												<div class="col-xl-6">
													<div class="card mb-5 mb-xl-8">
														<div class="card-header border-0 pt-5">
															<h3 class="card-title align-items-start flex-column">
																<span class="card-label fw-bold text-gray-900">
																	İlerleme ve Performans</span>
															</h3>
														</div>

														<div class="card-body pt-6">
															<form class="form" action="#" id="kt_form_student_progress">
																<input type="hidden" id="classes" name="classes"
																	value="<?= $studentInfo['class_id'] ?>">
																<input type="hidden" id="student_id" name="student_id"
																	value="<?= $studentInfo['id'] ?>">
																<input type="hidden" id="school_id" name="school_id"
																	value="<?= $studentInfo['school_id'] ?>">
																<div class="row mt-4">

																	<div class="col-lg-10">

																		<label class="fs-6 fw-semibold mb-2" for="ders">Ders
																		</label>
																		<select id="lessons" name="lessons"
																			aria-label="Ders Seçiniz" class="form-select"
																			required>
																			<option value="">Seçiniz</option>
																			<?php
																			$chooseLesson = new ShowLesson();

																			$lessons = $chooseLesson->getLessonByClassId($studentInfo['class_id']);
																			foreach ($lessons as $lessonOption) {
																				echo '<option value="' . $lessonOption["id"] . '">' . $lessonOption["text"] . '</option>';
																			}
																			?>

																		</select>
																	</div>
																</div>
																<div class="row mt-4">

																	<div class="col-lg-10">
																		<label class="fs-6 fw-semibold mb-2"
																			for="units">Ünite
																		</label>
																		<select class="form-select" id="units" name="units"
																			aria-label="Ünite Seçiniz">
																			<option value="">Seçiniz</option>
																		</select>
																	</div>
																</div>
																<div class="row mt-4">

																	<div class="col-lg-10">
																		<label class="fs-6 fw-semibold mb-2"
																			for="topics">Konu
																		</label>
																		<select class="form-select" id="topics"
																			name="topics" aria-label="Konu Seçiniz">
																			<option value="">Seçiniz</option>
																		</select>
																	</div>
																</div>
																<div class="row mt-4">

																	<div class="col-lg-10">
																		<label class="fs-6 fw-semibold mb-2"
																			for="subtopics">Altkonu
																		</label>
																		<select class="form-select" id="subtopics"
																			name="subtopics" aria-label="Altkonu Seçiniz">
																			<option value="">Seçiniz</option>
																		</select>
																	</div>
																</div>
																<div class=" mt-4">

																	<button type="submit"
																		id="kt_form_student_progress_submit"
																		class="btn btn-primary btn-sm">
																		<span class="indicator-label">Göster</span>
																	</button>
																</div>

															</form>
														</div>
													</div>
												</div>
												<div class="col-xl-6" id="html_response">

												</div>


											</div>
										</div>

										<!--end::Row-->
										<!--begin::Row-->
										<div class="tab-pane fade" id="ozel_dersler">
											<!--begin::Özel Dersler-->
											<div class="card mb-5 mb-lg-10">
												<!--begin::Card header-->
												<div class="card-header">
													<!--begin::Heading-->
													<div class="card-title">
														<h3>Alınan Özel Dersler</h3>
													</div>
													<!--end::Heading-->
													<!--begin::Toolbar-->
													<div class="card-toolbar">
														<div class="my-1 me-4">
															<!--begin::Select-->
															<!-- <select class="form-select form-select-sm form-select-solid w-125px" data-control="select2" data-placeholder="Select Hours" data-hide-search="true">
																<option value="1" selected="selected">1 Hours</option>
																<option value="2">6 Hours</option>
																<option value="3">12 Hours</option>
																<option value="4">24 Hours</option>
															</select> -->
															<!--end::Select-->
														</div>
														<!-- <a href="#" class="btn btn-sm btn-primary my-1">View All</a> -->
													</div>
													<!--end::Toolbar-->
												</div>
												<!--end::Card header-->
												<!--begin::Card body-->
												<div class="card-body p-0">
													<!--begin::Table wrapper-->
													<div class="table-responsive">
														<!--begin::Table-->
														<table
															class="table align-middle table-row-bordered table-row-solid gy-4 gs-9">
															<!--begin::Thead-->
															<thead class="border-gray-200 fs-5 fw-semibold bg-lighten">
																<tr>
																	<th class="min-w-250px">Adı</th>
																	<th class="min-w-100px">Sınıf</th>
																	<th class="min-w-100px">Ders</th>
																	<th class="min-w-100px">Ünite</th>
																	<th class="min-w-100px">Konu</th>
																	<th class="min-w-100px">Alt Konu</th>
																	<th class="min-w-150px">Öğretmen</th>
																	<th class="min-w-150px">Fiyatı</th>
																	<th class="min-w-150px">Zaman</th>
																	<th class="min-w-150px">Durum</th>
																</tr>
															</thead>
															<!--end::Thead-->
															<!--begin::Tbody-->
															<tbody class="fw-6 fw-semibold text-gray-600">
																<tr>
																	<td>
																		<a href="#"
																			class="text-hover-primary text-gray-600">Özel
																			Ders 1</a>
																	</td>
																	<td>
																		1. Sınıf
																	</td>
																	<td>Hayat Bilgisi</td>
																	<td>Doğada Hayat</td>
																	<td>Konusu</td>
																	<td>Alt Konusu</td>
																	<td>Öğretmen A</td>
																	<td>400₺</td>
																	<td>20.05.2025</td>
																	<td>Girdi</td>
																</tr>
																<tr>
																	<td>
																		<a href="#"
																			class="text-hover-primary text-gray-600">Özel
																			Ders 2</a>
																	</td>
																	<td>
																		1. Sınıf
																	</td>
																	<td>İngilizce</td>
																	<td>1. Ünite</td>
																	<td>Konusu</td>
																	<td>Alt Konusu</td>
																	<td>Öğretmen B</td>
																	<td>400₺</td>
																	<td>21.05.2025</td>
																	<td>Girmedi</td>
																</tr>
															</tbody>
															<!--end::Tbody-->
														</table>
														<!--end::Table-->
													</div>
													<!--end::Table wrapper-->
												</div>
												<!--end::Card body-->
											</div>
											<!--end::Özel Dersler-->
										</div>
										<!--end::Row-->
										<!--begin::Row-->
										<div class="tab-pane fade" id="grup_dersler">
											<!--begin::Grup Dersler-->
											<div class="card mb-5 mb-lg-10">
												<!--begin::Card header-->
												<div class="card-header">
													<!--begin::Heading-->
													<div class="card-title">
														<h3>Alınan Grup Dersler</h3>
													</div>
													<!--end::Heading-->
													<!--begin::Toolbar-->
													<div class="card-toolbar">
														<div class="my-1 me-4">
															<!--begin::Select-->
															<!-- <select class="form-select form-select-sm form-select-solid w-125px" data-control="select2" data-placeholder="Select Hours" data-hide-search="true">
																<option value="1" selected="selected">1 Hours</option>
																<option value="2">6 Hours</option>
																<option value="3">12 Hours</option>
																<option value="4">24 Hours</option>
															</select> -->
															<!--end::Select-->
														</div>
														<!-- <a href="#" class="btn btn-sm btn-primary my-1">View All</a> -->
													</div>
													<!--end::Toolbar-->
												</div>
												<!--end::Card header-->
												<!--begin::Card body-->
												<div class="card-body p-0">
													<!--begin::Table wrapper-->
													<div class="table-responsive">
														<!--begin::Table-->
														<table
															class="table align-middle table-row-bordered table-row-solid gy-4 gs-9">
															<!--begin::Thead-->
															<thead class="border-gray-200 fs-5 fw-semibold bg-lighten">
																<tr>
																	<th class="min-w-250px">Adı</th>
																	<th class="min-w-100px">Sınıf</th>
																	<th class="min-w-100px">Ders</th>
																	<th class="min-w-100px">Ünite</th>
																	<th class="min-w-100px">Konu</th>
																	<th class="min-w-100px">Alt Konu</th>
																	<th class="min-w-150px">Öğretmen</th>
																	<th class="min-w-150px">Fiyatı</th>
																	<th class="min-w-150px">Zaman</th>
																	<th class="min-w-150px">Durum</th>
																</tr>
															</thead>
															<!--end::Thead-->
															<!--begin::Tbody-->
															<tbody class="fw-6 fw-semibold text-gray-600">
																<tr>
																	<td>
																		<a href="#"
																			class="text-hover-primary text-gray-600">Grup
																			Ders 1</a>
																	</td>
																	<td>
																		1. Sınıf
																	</td>
																	<td>Hayat Bilgisi</td>
																	<td>Doğada Hayat</td>
																	<td>Konusu</td>
																	<td>Alt Konusu</td>
																	<td>Öğretmen A</td>
																	<td>400₺</td>
																	<td>20.05.2025</td>
																	<td>Girdi</td>
																</tr>
																<tr>
																	<td>
																		<a href="#"
																			class="text-hover-primary text-gray-600">Grup
																			Ders 2</a>
																	</td>
																	<td>
																		1. Sınıf
																	</td>
																	<td>İngilizce</td>
																	<td>1. Ünite</td>
																	<td>Konusu</td>
																	<td>Alt Konusu</td>
																	<td>Öğretmen B</td>
																	<td>400₺</td>
																	<td>21.05.2025</td>
																	<td>Girmedi</td>
																</tr>
															</tbody>
															<!--end::Tbody-->
														</table>
														<!--end::Table-->
													</div>
													<!--end::Table wrapper-->
												</div>
												<!--end::Card body-->
											</div>
											<!--end::Grup Dersler-->
										</div>
										<!--end::Row-->
										<!--begin::Row-->
										<div class="tab-pane fade" id="paketler">
											<!--begin::Paketler-->
											<div class="card mb-5 mb-lg-10">
												<!--begin::Card header-->
												<div class="card-header">
													<!--begin::Heading-->
													<div class="card-title">
														<h3>Alınan Paketler</h3>
													</div>
													<!--end::Heading-->
													<!--begin::Toolbar-->
													<div class="card-toolbar">
														<div class="my-1 me-4">
															<!--begin::Select-->
															<!-- <select class="form-select form-select-sm form-select-solid w-125px" data-control="select2" data-placeholder="Select Hours" data-hide-search="true">
																<option value="1" selected="selected">1 Hours</option>
																<option value="2">6 Hours</option>
																<option value="3">12 Hours</option>
																<option value="4">24 Hours</option>
															</select> -->
															<!--end::Select-->
														</div>
														<!-- <a href="#" class="btn btn-sm btn-primary my-1">View All</a> -->
													</div>
													<!--end::Toolbar-->
												</div>
												<!--end::Card header-->
												<!--begin::Card body-->
												<div class="card-body p-0">
													<!--begin::Table wrapper-->
													<div class="table-responsive">
														<!--begin::Table-->
														<table
															class="table align-middle table-row-bordered table-row-solid gy-4 gs-9">
															<!--begin::Thead-->
															<thead class="border-gray-200 fs-5 fw-semibold bg-lighten">
																<tr>
																	<th class="min-w-250px">Paket Adı</th>
																	<th class="min-w-100px">Sınıf</th>
																	<th class="min-w-150px">Fiyatı</th>
																	<th class="min-w-150px text-end">Bitiş Tarihi</th>
																</tr>
															</thead>
															<!--end::Thead-->
															<!--begin::Tbody-->
															<tbody class="fw-6 fw-semibold text-gray-600">
																<?php $student->showPackageDetailsListForStudentDetails($getStudentId); ?>
															</tbody>
															<!--end::Tbody-->
														</table>
														<!--end::Table-->
													</div>
													<!--end::Table wrapper-->
												</div>
												<!--end::Card body-->
											</div>
											<!--end::Paketler-->
											<!--begin::Ek Paketler-->
											<div class="card mb-5 mb-lg-10">
												<!--begin::Card header-->
												<div class="card-header">
													<!--begin::Heading-->
													<div class="card-title">
														<h3>Alınan Ek Paketler</h3>
													</div>
													<!--end::Heading-->
													<!--begin::Toolbar-->
													<div class="card-toolbar">
														<div class="my-1 me-4">
															<!--begin::Select-->
															<!-- <select class="form-select form-select-sm form-select-solid w-125px" data-control="select2" data-placeholder="Select Hours" data-hide-search="true">
																<option value="1" selected="selected">1 Hours</option>
																<option value="2">6 Hours</option>
																<option value="3">12 Hours</option>
																<option value="4">24 Hours</option>
															</select> -->
															<!--end::Select-->
														</div>
														<!-- <a href="#" class="btn btn-sm btn-primary my-1">View All</a> -->
													</div>
													<!--end::Toolbar-->
												</div>
												<!--end::Card header-->
												<!--begin::Card body-->
												<div class="card-body p-0">
													<!--begin::Table wrapper-->
													<div class="table-responsive">
														<!--begin::Table-->
														<table
															class="table align-middle table-row-bordered table-row-solid gy-4 gs-9">
															<!--begin::Thead-->
															<thead class="border-gray-200 fs-5 fw-semibold bg-lighten">
																<tr>
																	<th class="min-w-250px">Paket Adı</th>
																	<th class="min-w-100px">Sınıf</th>
																	<th class="min-w-150px">Fiyatı</th>
																	<th class="min-w-150px text-end">Bitiş Tarihi</th>
																</tr>
															</thead>
															<!--end::Thead-->
															<!--begin::Tbody-->
															<tbody class="fw-6 fw-semibold text-gray-600">
																<?php $student->showAdditionalPackageDetailsListForStudentDetails($getStudentId); ?>
															</tbody>
															<!--end::Tbody-->
														</table>
														<!--end::Table-->
													</div>
													<!--end::Table wrapper-->
												</div>
												<!--end::Card body-->
											</div>
											<!--end::Ek Paketler-->
										</div>
										<!--end::Row-->
										<!--begin::Row-->
										<div class="tab-pane fade" id="hareketler">

											<!--begin::Login sessions-->
											<div class="card mb-5 mb-lg-10">
												<!--begin::Card header-->
												<div class="card-header">
													<!--begin::Heading-->
													<div class="card-title">
														<h3>Giriş Bilgileri</h3>
													</div>
													<!--end::Heading-->
													<!--begin::Toolbar-->
													<div class="card-toolbar">
														<div class="my-1 me-4">
															<!--begin::Select-->
															<!-- <select class="form-select form-select-sm form-select-solid w-125px" data-control="select2" data-placeholder="Select Hours" data-hide-search="true">
																<option value="1" selected="selected">1 Hours</option>
																<option value="2">6 Hours</option>
																<option value="3">12 Hours</option>
																<option value="4">24 Hours</option>
															</select> -->
															<!--end::Select-->
														</div><!-- 
														<a href="#" class="btn btn-sm btn-primary my-1">View All</a> -->
													</div>
													<!--end::Toolbar-->
												</div>
												<!--end::Card header-->
												<!--begin::Card body-->
												<div class="card-body p-0">
													<!--begin::Table wrapper-->
													<div class="table-responsive">
														<!--begin::Table-->
														<table
															class="table align-middle table-row-bordered table-row-solid gy-4 gs-9">
															<!--begin::Thead-->
															<thead class="border-gray-200 fs-5 fw-semibold bg-lighten">
																<tr>
																	<th class="min-w-250px">Cihaz Tipi</th>
																	<th class="min-w-100px">Cihaz Modeli</th>
																	<th class="min-w-150px">İşletim Sistemi</th>
																	<th class="min-w-150px">Tarayıcı</th>
																	<th class="min-w-150px">Ekran Çözünürlüğü</th>
																	<th class="min-w-150px">IP Adresi</th>
																	<th class="min-w-150px">Giriş Zamanı</th>
																	<th class="min-w-150px">Çıkış Zamanı</th>
																</tr>
															</thead>
															<!--end::Thead-->
															<!--begin::Tbody-->
															<tbody class="fw-6 fw-semibold text-gray-600">
																<?php $student->showLoginDetailsListForStudentDetails($getStudentId); ?>
															</tbody>
															<!--end::Tbody-->
														</table>
														<!--end::Table-->
													</div>
													<!--end::Table wrapper-->
												</div>
												<!--end::Card body-->
											</div>
											<!--end::Login sessions-->
										</div>
										<!--end::Row-->
									</div>
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
		<!--begin::Drawers-->
		<!--begin::Activities drawer-->
		<div id="kt_activities" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="activities"
			data-kt-drawer-activate="true" data-kt-drawer-overlay="true"
			data-kt-drawer-width="{default:'300px', 'lg': '900px'}" data-kt-drawer-direction="end"
			data-kt-drawer-toggle="#kt_activities_toggle" data-kt-drawer-close="#kt_activities_close">
			<div class="card shadow-none border-0 rounded-0">
				<!--begin::Header-->
				<div class="card-header" id="kt_activities_header">
					<h3 class="card-title fw-bold text-gray-900">Activity Logs</h3>
					<div class="card-toolbar">
						<button type="button" class="btn btn-sm btn-icon btn-active-light-primary me-n5"
							id="kt_activities_close">
							<i class="ki-duotone ki-cross fs-1">
								<span class="path1"></span>
								<span class="path2"></span>
							</i>
						</button>
					</div>
				</div>
				<!--end::Header-->
				<!--begin::Body-->
				<div class="card-body position-relative" id="kt_activities_body">
					<!--begin::Content-->
					<div id="kt_activities_scroll" class="position-relative scroll-y me-n5 pe-5" data-kt-scroll="true"
						data-kt-scroll-height="auto" data-kt-scroll-wrappers="#kt_activities_body"
						data-kt-scroll-dependencies="#kt_activities_header, #kt_activities_footer"
						data-kt-scroll-offset="5px">
						<!--begin::Timeline items-->
						<div class="timeline timeline-border-dashed">
							<!--begin::Timeline item-->
							<div class="timeline-item">
								<!--begin::Timeline line-->
								<div class="timeline-line"></div>
								<!--end::Timeline line-->
								<!--begin::Timeline icon-->
								<div class="timeline-icon">
									<i class="ki-duotone ki-message-text-2 fs-2 text-gray-500">
										<span class="path1"></span>
										<span class="path2"></span>
										<span class="path3"></span>
									</i>
								</div>
								<!--end::Timeline icon-->
								<!--begin::Timeline content-->
								<div class="timeline-content mb-10 mt-n1">
									<!--begin::Timeline heading-->
									<div class="pe-3 mb-5">
										<!--begin::Title-->
										<div class="fs-5 fw-semibold mb-2">There are 2 new tasks for you in “AirPlus Mobile
											App” project:</div>
										<!--end::Title-->
										<!--begin::Description-->
										<div class="d-flex align-items-center mt-1 fs-6">
											<!--begin::Info-->
											<div class="text-muted me-2 fs-7">Added at 4:23 PM by</div>
											<!--end::Info-->
											<!--begin::User-->
											<div class="symbol symbol-circle symbol-25px" data-bs-toggle="tooltip"
												data-bs-boundary="window" data-bs-placement="top" title="Nina Nilson">
												<img src="assets/media/avatars/300-14.jpg" alt="img" />
											</div>
											<!--end::User-->
										</div>
										<!--end::Description-->
									</div>
									<!--end::Timeline heading-->
									<!--begin::Timeline details-->
									<div class="overflow-auto pb-5">
										<!--begin::Record-->
										<div
											class="d-flex align-items-center border border-dashed border-gray-300 rounded min-w-750px px-7 py-3 mb-5">
											<!--begin::Title-->
											<a href="apps/projects/project.html"
												class="fs-5 text-gray-900 text-hover-primary fw-semibold w-375px min-w-200px">Meeting
												with customer</a>
											<!--end::Title-->
											<!--begin::Label-->
											<div class="min-w-175px pe-2">
												<span class="badge badge-light text-muted">Application Design</span>
											</div>
											<!--end::Label-->
											<!--begin::Users-->
											<div class="symbol-group symbol-hover flex-nowrap flex-grow-1 min-w-100px pe-2">
												<!--begin::User-->
												<div class="symbol symbol-circle symbol-25px">
													<img src="assets/media/avatars/300-2.jpg" alt="img" />
												</div>
												<!--end::User-->
												<!--begin::User-->
												<div class="symbol symbol-circle symbol-25px">
													<img src="assets/media/avatars/300-14.jpg" alt="img" />
												</div>
												<!--end::User-->
												<!--begin::User-->
												<div class="symbol symbol-circle symbol-25px">
													<div
														class="symbol-label fs-8 fw-semibold bg-primary text-inverse-primary">
														A</div>
												</div>
												<!--end::User-->
											</div>
											<!--end::Users-->
											<!--begin::Progress-->
											<div class="min-w-125px pe-2">
												<span class="badge badge-light-primary">In Progress</span>
											</div>
											<!--end::Progress-->
											<!--begin::Action-->
											<a href="apps/projects/project.html"
												class="btn btn-sm btn-light btn-active-light-primary">View</a>
											<!--end::Action-->
										</div>
										<!--end::Record-->
										<!--begin::Record-->
										<div
											class="d-flex align-items-center border border-dashed border-gray-300 rounded min-w-750px px-7 py-3 mb-0">
											<!--begin::Title-->
											<a href="apps/projects/project.html"
												class="fs-5 text-gray-900 text-hover-primary fw-semibold w-375px min-w-200px">Project
												Delivery Preparation</a>
											<!--end::Title-->
											<!--begin::Label-->
											<div class="min-w-175px">
												<span class="badge badge-light text-muted">CRM System Development</span>
											</div>
											<!--end::Label-->
											<!--begin::Users-->
											<div class="symbol-group symbol-hover flex-nowrap flex-grow-1 min-w-100px">
												<!--begin::User-->
												<div class="symbol symbol-circle symbol-25px">
													<img src="assets/media/avatars/300-20.jpg" alt="img" />
												</div>
												<!--end::User-->
												<!--begin::User-->
												<div class="symbol symbol-circle symbol-25px">
													<div
														class="symbol-label fs-8 fw-semibold bg-success text-inverse-primary">
														B</div>
												</div>
												<!--end::User-->
											</div>
											<!--end::Users-->
											<!--begin::Progress-->
											<div class="min-w-125px">
												<span class="badge badge-light-success">Completed</span>
											</div>
											<!--end::Progress-->
											<!--begin::Action-->
											<a href="apps/projects/project.html"
												class="btn btn-sm btn-light btn-active-light-primary">View</a>
											<!--end::Action-->
										</div>
										<!--end::Record-->
									</div>
									<!--end::Timeline details-->
								</div>
								<!--end::Timeline content-->
							</div>
							<!--end::Timeline item-->
							<!--begin::Timeline item-->
							<div class="timeline-item">
								<!--begin::Timeline line-->
								<div class="timeline-line"></div>
								<!--end::Timeline line-->
								<!--begin::Timeline icon-->
								<div class="timeline-icon me-4">
									<i class="ki-duotone ki-flag fs-2 text-gray-500">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
								</div>
								<!--end::Timeline icon-->
								<!--begin::Timeline content-->
								<div class="timeline-content mb-10 mt-n2">
									<!--begin::Timeline heading-->
									<div class="overflow-auto pe-3">
										<!--begin::Title-->
										<div class="fs-5 fw-semibold mb-2">Invitation for crafting engaging designs that
											speak human workshop</div>
										<!--end::Title-->
										<!--begin::Description-->
										<div class="d-flex align-items-center mt-1 fs-6">
											<!--begin::Info-->
											<div class="text-muted me-2 fs-7">Sent at 4:23 PM by</div>
											<!--end::Info-->
											<!--begin::User-->
											<div class="symbol symbol-circle symbol-25px" data-bs-toggle="tooltip"
												data-bs-boundary="window" data-bs-placement="top" title="Alan Nilson">
												<img src="assets/media/avatars/300-1.jpg" alt="img" />
											</div>
											<!--end::User-->
										</div>
										<!--end::Description-->
									</div>
									<!--end::Timeline heading-->
								</div>
								<!--end::Timeline content-->
							</div>
							<!--end::Timeline item-->
							<!--begin::Timeline item-->
							<div class="timeline-item">
								<!--begin::Timeline line-->
								<div class="timeline-line"></div>
								<!--end::Timeline line-->
								<!--begin::Timeline icon-->
								<div class="timeline-icon">
									<i class="ki-duotone ki-disconnect fs-2 text-gray-500">
										<span class="path1"></span>
										<span class="path2"></span>
										<span class="path3"></span>
										<span class="path4"></span>
										<span class="path5"></span>
									</i>
								</div>
								<!--end::Timeline icon-->
								<!--begin::Timeline content-->
								<div class="timeline-content mb-10 mt-n1">
									<!--begin::Timeline heading-->
									<div class="mb-5 pe-3">
										<!--begin::Title-->
										<a href="#" class="fs-5 fw-semibold text-gray-800 text-hover-primary mb-2">3 New
											Incoming Project Files:</a>
										<!--end::Title-->
										<!--begin::Description-->
										<div class="d-flex align-items-center mt-1 fs-6">
											<!--begin::Info-->
											<div class="text-muted me-2 fs-7">Sent at 10:30 PM by</div>
											<!--end::Info-->
											<!--begin::User-->
											<div class="symbol symbol-circle symbol-25px" data-bs-toggle="tooltip"
												data-bs-boundary="window" data-bs-placement="top" title="Jan Hummer">
												<img src="assets/media/avatars/300-23.jpg" alt="img" />
											</div>
											<!--end::User-->
										</div>
										<!--end::Description-->
									</div>
									<!--end::Timeline heading-->
									<!--begin::Timeline details-->
									<div class="overflow-auto pb-5">
										<div
											class="d-flex align-items-center border border-dashed border-gray-300 rounded min-w-700px p-5">
											<!--begin::Item-->
											<div class="d-flex flex-aligns-center pe-10 pe-lg-20">
												<!--begin::Icon-->
												<img alt="" class="w-30px me-3" src="assets/media/svg/files/pdf.svg" />
												<!--end::Icon-->
												<!--begin::Info-->
												<div class="ms-1 fw-semibold">
													<!--begin::Desc-->
													<a href="apps/projects/project.html"
														class="fs-6 text-hover-primary fw-bold">Finance KPI App
														Guidelines</a>
													<!--end::Desc-->
													<!--begin::Number-->
													<div class="text-gray-500">1.9mb</div>
													<!--end::Number-->
												</div>
												<!--begin::Info-->
											</div>
											<!--end::Item-->
											<!--begin::Item-->
											<div class="d-flex flex-aligns-center pe-10 pe-lg-20">
												<!--begin::Icon-->
												<img alt="apps/projects/project.html" class="w-30px me-3"
													src="assets/media/svg/files/doc.svg" />
												<!--end::Icon-->
												<!--begin::Info-->
												<div class="ms-1 fw-semibold">
													<!--begin::Desc-->
													<a href="#" class="fs-6 text-hover-primary fw-bold">Client UAT Testing
														Results</a>
													<!--end::Desc-->
													<!--begin::Number-->
													<div class="text-gray-500">18kb</div>
													<!--end::Number-->
												</div>
												<!--end::Info-->
											</div>
											<!--end::Item-->
											<!--begin::Item-->
											<div class="d-flex flex-aligns-center">
												<!--begin::Icon-->
												<img alt="apps/projects/project.html" class="w-30px me-3"
													src="assets/media/svg/files/css.svg" />
												<!--end::Icon-->
												<!--begin::Info-->
												<div class="ms-1 fw-semibold">
													<!--begin::Desc-->
													<a href="#" class="fs-6 text-hover-primary fw-bold">Finance Reports</a>
													<!--end::Desc-->
													<!--begin::Number-->
													<div class="text-gray-500">20mb</div>
													<!--end::Number-->
												</div>
												<!--end::Icon-->
											</div>
											<!--end::Item-->
										</div>
									</div>
									<!--end::Timeline details-->
								</div>
								<!--end::Timeline content-->
							</div>
							<!--end::Timeline item-->
							<!--begin::Timeline item-->
							<div class="timeline-item">
								<!--begin::Timeline line-->
								<div class="timeline-line"></div>
								<!--end::Timeline line-->
								<!--begin::Timeline icon-->
								<div class="timeline-icon">
									<i class="ki-duotone ki-abstract-26 fs-2 text-gray-500">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
								</div>
								<!--end::Timeline icon-->
								<!--begin::Timeline content-->
								<div class="timeline-content mb-10 mt-n1">
									<!--begin::Timeline heading-->
									<div class="pe-3 mb-5">
										<!--begin::Title-->
										<div class="fs-5 fw-semibold mb-2">Task
											<a href="#" class="text-primary fw-bold me-1">#45890</a>merged with
											<a href="#" class="text-primary fw-bold me-1">#45890</a>in “Ads Pro Admin
											Dashboard project:
										</div>
										<!--end::Title-->
										<!--begin::Description-->
										<div class="d-flex align-items-center mt-1 fs-6">
											<!--begin::Info-->
											<div class="text-muted me-2 fs-7">Initiated at 4:23 PM by</div>
											<!--end::Info-->
											<!--begin::User-->
											<div class="symbol symbol-circle symbol-25px" data-bs-toggle="tooltip"
												data-bs-boundary="window" data-bs-placement="top" title="Nina Nilson">
												<img src="assets/media/avatars/300-14.jpg" alt="img" />
											</div>
											<!--end::User-->
										</div>
										<!--end::Description-->
									</div>
									<!--end::Timeline heading-->
								</div>
								<!--end::Timeline content-->
							</div>
							<!--end::Timeline item-->
							<!--begin::Timeline item-->
							<div class="timeline-item">
								<!--begin::Timeline line-->
								<div class="timeline-line"></div>
								<!--end::Timeline line-->
								<!--begin::Timeline icon-->
								<div class="timeline-icon">
									<i class="ki-duotone ki-pencil fs-2 text-gray-500">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
								</div>
								<!--end::Timeline icon-->
								<!--begin::Timeline content-->
								<div class="timeline-content mb-10 mt-n1">
									<!--begin::Timeline heading-->
									<div class="pe-3 mb-5">
										<!--begin::Title-->
										<div class="fs-5 fw-semibold mb-2">3 new application design concepts added:</div>
										<!--end::Title-->
										<!--begin::Description-->
										<div class="d-flex align-items-center mt-1 fs-6">
											<!--begin::Info-->
											<div class="text-muted me-2 fs-7">Created at 4:23 PM by</div>
											<!--end::Info-->
											<!--begin::User-->
											<div class="symbol symbol-circle symbol-25px" data-bs-toggle="tooltip"
												data-bs-boundary="window" data-bs-placement="top" title="Marcus Dotson">
												<img src="assets/media/avatars/300-2.jpg" alt="img" />
											</div>
											<!--end::User-->
										</div>
										<!--end::Description-->
									</div>
									<!--end::Timeline heading-->
									<!--begin::Timeline details-->
									<div class="overflow-auto pb-5">
										<div
											class="d-flex align-items-center border border-dashed border-gray-300 rounded min-w-700px p-7">
											<!--begin::Item-->
											<div class="overlay me-10">
												<!--begin::Image-->
												<div class="overlay-wrapper">
													<img alt="img" class="rounded w-150px"
														src="assets/media/stock/600x400/img-29.jpg" />
												</div>
												<!--end::Image-->
												<!--begin::Link-->
												<div class="overlay-layer bg-dark bg-opacity-10 rounded">
													<a href="#" class="btn btn-sm btn-primary btn-shadow">Explore</a>
												</div>
												<!--end::Link-->
											</div>
											<!--end::Item-->
											<!--begin::Item-->
											<div class="overlay me-10">
												<!--begin::Image-->
												<div class="overlay-wrapper">
													<img alt="img" class="rounded w-150px"
														src="assets/media/stock/600x400/img-31.jpg" />
												</div>
												<!--end::Image-->
												<!--begin::Link-->
												<div class="overlay-layer bg-dark bg-opacity-10 rounded">
													<a href="#" class="btn btn-sm btn-primary btn-shadow">Explore</a>
												</div>
												<!--end::Link-->
											</div>
											<!--end::Item-->
											<!--begin::Item-->
											<div class="overlay">
												<!--begin::Image-->
												<div class="overlay-wrapper">
													<img alt="img" class="rounded w-150px"
														src="assets/media/stock/600x400/img-40.jpg" />
												</div>
												<!--end::Image-->
												<!--begin::Link-->
												<div class="overlay-layer bg-dark bg-opacity-10 rounded">
													<a href="#" class="btn btn-sm btn-primary btn-shadow">Explore</a>
												</div>
												<!--end::Link-->
											</div>
											<!--end::Item-->
										</div>
									</div>
									<!--end::Timeline details-->
								</div>
								<!--end::Timeline content-->
							</div>
							<!--end::Timeline item-->
							<!--begin::Timeline item-->
							<div class="timeline-item">
								<!--begin::Timeline line-->
								<div class="timeline-line"></div>
								<!--end::Timeline line-->
								<!--begin::Timeline icon-->
								<div class="timeline-icon">
									<i class="ki-duotone ki-sms fs-2 text-gray-500">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
								</div>
								<!--end::Timeline icon-->
								<!--begin::Timeline content-->
								<div class="timeline-content mb-10 mt-n1">
									<!--begin::Timeline heading-->
									<div class="pe-3 mb-5">
										<!--begin::Title-->
										<div class="fs-5 fw-semibold mb-2">New case
											<a href="#" class="text-primary fw-bold me-1">#67890</a>is assigned to you in
											Multi-platform Database Design project
										</div>
										<!--end::Title-->
										<!--begin::Description-->
										<div class="overflow-auto pb-5">
											<!--begin::Wrapper-->
											<div class="d-flex align-items-center mt-1 fs-6">
												<!--begin::Info-->
												<div class="text-muted me-2 fs-7">Added at 4:23 PM by</div>
												<!--end::Info-->
												<!--begin::User-->
												<a href="#" class="text-primary fw-bold me-1">Alice Tan</a>
												<!--end::User-->
											</div>
											<!--end::Wrapper-->
										</div>
										<!--end::Description-->
									</div>
									<!--end::Timeline heading-->
								</div>
								<!--end::Timeline content-->
							</div>
							<!--end::Timeline item-->
							<!--begin::Timeline item-->
							<div class="timeline-item">
								<!--begin::Timeline line-->
								<div class="timeline-line"></div>
								<!--end::Timeline line-->
								<!--begin::Timeline icon-->
								<div class="timeline-icon">
									<i class="ki-duotone ki-pencil fs-2 text-gray-500">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
								</div>
								<!--end::Timeline icon-->
								<!--begin::Timeline content-->
								<div class="timeline-content mb-10 mt-n1">
									<!--begin::Timeline heading-->
									<div class="pe-3 mb-5">
										<!--begin::Title-->
										<div class="fs-5 fw-semibold mb-2">You have received a new order:</div>
										<!--end::Title-->
										<!--begin::Description-->
										<div class="d-flex align-items-center mt-1 fs-6">
											<!--begin::Info-->
											<div class="text-muted me-2 fs-7">Placed at 5:05 AM by</div>
											<!--end::Info-->
											<!--begin::User-->
											<div class="symbol symbol-circle symbol-25px" data-bs-toggle="tooltip"
												data-bs-boundary="window" data-bs-placement="top" title="Robert Rich">
												<img src="assets/media/avatars/300-4.jpg" alt="img" />
											</div>
											<!--end::User-->
										</div>
										<!--end::Description-->
									</div>
									<!--end::Timeline heading-->
									<!--begin::Timeline details-->
									<div class="overflow-auto pb-5">
										<!--begin::Notice-->
										<div
											class="notice d-flex bg-light-primary rounded border-primary border border-dashed min-w-lg-600px flex-shrink-0 p-6">
											<!--begin::Icon-->
											<i class="ki-duotone ki-devices-2 fs-2tx text-primary me-4">
												<span class="path1"></span>
												<span class="path2"></span>
												<span class="path3"></span>
											</i>
											<!--end::Icon-->
											<!--begin::Wrapper-->
											<div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
												<!--begin::Content-->
												<div class="mb-3 mb-md-0 fw-semibold">
													<h4 class="text-gray-900 fw-bold">Database Backup Process Completed!
													</h4>
													<div class="fs-6 text-gray-700 pe-7">Login into Admin Dashboard to make
														sure the data integrity is OK</div>
												</div>
												<!--end::Content-->
												<!--begin::Action-->
												<a href="#"
													class="btn btn-primary btn-sm px-6 align-self-center text-nowrap">Proceed</a>
												<!--end::Action-->
											</div>
											<!--end::Wrapper-->
										</div>
										<!--end::Notice-->
									</div>
									<!--end::Timeline details-->
								</div>
								<!--end::Timeline content-->
							</div>
							<!--end::Timeline item-->
							<!--begin::Timeline item-->
							<div class="timeline-item">
								<!--begin::Timeline line-->
								<div class="timeline-line"></div>
								<!--end::Timeline line-->
								<!--begin::Timeline icon-->
								<div class="timeline-icon">
									<i class="ki-duotone ki-basket fs-2 text-gray-500">
										<span class="path1"></span>
										<span class="path2"></span>
										<span class="path3"></span>
										<span class="path4"></span>
									</i>
								</div>
								<!--end::Timeline icon-->
								<!--begin::Timeline content-->
								<div class="timeline-content mt-n1">
									<!--begin::Timeline heading-->
									<div class="pe-3 mb-5">
										<!--begin::Title-->
										<div class="fs-5 fw-semibold mb-2">New order
											<a href="#" class="text-primary fw-bold me-1">#67890</a>is placed for Workshow
											Planning & Budget Estimation
										</div>
										<!--end::Title-->
										<!--begin::Description-->
										<div class="d-flex align-items-center mt-1 fs-6">
											<!--begin::Info-->
											<div class="text-muted me-2 fs-7">Placed at 4:23 PM by</div>
											<!--end::Info-->
											<!--begin::User-->
											<a href="#" class="text-primary fw-bold me-1">Jimmy Bold</a>
											<!--end::User-->
										</div>
										<!--end::Description-->
									</div>
									<!--end::Timeline heading-->
								</div>
								<!--end::Timeline content-->
							</div>
							<!--end::Timeline item-->
						</div>
						<!--end::Timeline items-->
					</div>
					<!--end::Content-->
				</div>
				<!--end::Body-->
				<!--begin::Footer-->
				<div class="card-footer py-5 text-center" id="kt_activities_footer">
					<a href="pages/user-profile/activity.html" class="btn btn-bg-body text-primary">View All Activities
						<i class="ki-duotone ki-arrow-right fs-3 text-primary">
							<span class="path1"></span>
							<span class="path2"></span>
						</i></a>
				</div>
				<!--end::Footer-->
			</div>
		</div>
		<!--end::Activities drawer-->
		<!--begin::Chat drawer-->
		<div id="kt_drawer_chat" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="chat"
			data-kt-drawer-activate="true" data-kt-drawer-overlay="true"
			data-kt-drawer-width="{default:'300px', 'md': '500px'}" data-kt-drawer-direction="end"
			data-kt-drawer-toggle="#kt_drawer_chat_toggle" data-kt-drawer-close="#kt_drawer_chat_close">
			<!--begin::Messenger-->
			<div class="card w-100 border-0 rounded-0" id="kt_drawer_chat_messenger">
				<!--begin::Card header-->
				<div class="card-header pe-5" id="kt_drawer_chat_messenger_header">
					<!--begin::Title-->
					<div class="card-title">
						<!--begin::User-->
						<div class="d-flex justify-content-center flex-column me-3">
							<a href="#" class="fs-4 fw-bold text-gray-900 text-hover-primary me-1 mb-2 lh-1">Brian Cox</a>
							<!--begin::Info-->
							<div class="mb-0 lh-1">
								<span class="badge badge-success badge-circle w-10px h-10px me-1"></span>
								<span class="fs-7 fw-semibold text-muted">Active</span>
							</div>
							<!--end::Info-->
						</div>
						<!--end::User-->
					</div>
					<!--end::Title-->
					<!--begin::Card toolbar-->
					<div class="card-toolbar">
						<!--begin::Menu-->
						<div class="me-0">
							<button class="btn btn-sm btn-icon btn-active-color-primary" data-kt-menu-trigger="click"
								data-kt-menu-placement="bottom-end">
								<i class="ki-duotone ki-dots-square fs-2">
									<span class="path1"></span>
									<span class="path2"></span>
									<span class="path3"></span>
									<span class="path4"></span>
								</i>
							</button>
							<!--begin::Menu 3-->
							<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-3"
								data-kt-menu="true">
								<!--begin::Heading-->
								<div class="menu-item px-3">
									<div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">Contacts</div>
								</div>
								<!--end::Heading-->
								<!--begin::Menu item-->
								<div class="menu-item px-3">
									<a href="#" class="menu-link px-3" data-bs-toggle="modal"
										data-bs-target="#kt_modal_users_search">Add Contact</a>
								</div>
								<!--end::Menu item-->
								<!--begin::Menu item-->
								<div class="menu-item px-3">
									<a href="#" class="menu-link flex-stack px-3" data-bs-toggle="modal"
										data-bs-target="#kt_modal_invite_friends">Invite Contacts
										<span class="ms-2" data-bs-toggle="tooltip"
											title="Specify a contact email to send an invitation">
											<i class="ki-duotone ki-information fs-7">
												<span class="path1"></span>
												<span class="path2"></span>
												<span class="path3"></span>
											</i>
										</span></a>
								</div>
								<!--end::Menu item-->
								<!--begin::Menu item-->
								<div class="menu-item px-3" data-kt-menu-trigger="hover"
									data-kt-menu-placement="right-start">
									<a href="#" class="menu-link px-3">
										<span class="menu-title">Groups</span>
										<span class="menu-arrow"></span>
									</a>
									<!--begin::Menu sub-->
									<div class="menu-sub menu-sub-dropdown w-175px py-4">
										<!--begin::Menu item-->
										<div class="menu-item px-3">
											<a href="#" class="menu-link px-3" data-bs-toggle="tooltip"
												title="Coming soon">Create Group</a>
										</div>
										<!--end::Menu item-->
										<!--begin::Menu item-->
										<div class="menu-item px-3">
											<a href="#" class="menu-link px-3" data-bs-toggle="tooltip"
												title="Coming soon">Invite Members</a>
										</div>
										<!--end::Menu item-->
										<!--begin::Menu item-->
										<div class="menu-item px-3">
											<a href="#" class="menu-link px-3" data-bs-toggle="tooltip"
												title="Coming soon">Settings</a>
										</div>
										<!--end::Menu item-->
									</div>
									<!--end::Menu sub-->
								</div>
								<!--end::Menu item-->
								<!--begin::Menu item-->
								<div class="menu-item px-3 my-1">
									<a href="#" class="menu-link px-3" data-bs-toggle="tooltip"
										title="Coming soon">Settings</a>
								</div>
								<!--end::Menu item-->
							</div>
							<!--end::Menu 3-->
						</div>
						<!--end::Menu-->
						<!--begin::Close-->
						<div class="btn btn-sm btn-icon btn-active-color-primary" id="kt_drawer_chat_close">
							<i class="ki-duotone ki-cross-square fs-2">
								<span class="path1"></span>
								<span class="path2"></span>
							</i>
						</div>
						<!--end::Close-->
					</div>
					<!--end::Card toolbar-->
				</div>
				<!--end::Card header-->
				<!--begin::Card body-->
				<div class="card-body" id="kt_drawer_chat_messenger_body">
					<!--begin::Messages-->
					<div class="scroll-y me-n5 pe-5" data-kt-element="messages" data-kt-scroll="true"
						data-kt-scroll-activate="true" data-kt-scroll-height="auto"
						data-kt-scroll-dependencies="#kt_drawer_chat_messenger_header, #kt_drawer_chat_messenger_footer"
						data-kt-scroll-wrappers="#kt_drawer_chat_messenger_body" data-kt-scroll-offset="0px">
						<!--begin::Message(in)-->
						<div class="d-flex justify-content-start mb-10">
							<!--begin::Wrapper-->
							<div class="d-flex flex-column align-items-start">
								<!--begin::User-->
								<div class="d-flex align-items-center mb-2">
									<!--begin::Avatar-->
									<div class="symbol symbol-35px symbol-circle">
										<img alt="Pic" src="assets/media/avatars/300-25.jpg" />
									</div>
									<!--end::Avatar-->
									<!--begin::Details-->
									<div class="ms-3">
										<a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary me-1">Brian Cox</a>
										<span class="text-muted fs-7 mb-1">2 mins</span>
									</div>
									<!--end::Details-->
								</div>
								<!--end::User-->
								<!--begin::Text-->
								<div class="p-5 rounded bg-light-info text-gray-900 fw-semibold mw-lg-400px text-start"
									data-kt-element="message-text">How likely are you to recommend our company to your
									friends and family ?</div>
								<!--end::Text-->
							</div>
							<!--end::Wrapper-->
						</div>
						<!--end::Message(in)-->
						<!--begin::Message(out)-->
						<div class="d-flex justify-content-end mb-10">
							<!--begin::Wrapper-->
							<div class="d-flex flex-column align-items-end">
								<!--begin::User-->
								<div class="d-flex align-items-center mb-2">
									<!--begin::Details-->
									<div class="me-3">
										<span class="text-muted fs-7 mb-1">5 mins</span>
										<a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary ms-1">You</a>
									</div>
									<!--end::Details-->
									<!--begin::Avatar-->
									<div class="symbol symbol-35px symbol-circle">
										<img alt="Pic" src="assets/media/avatars/300-1.jpg" />
									</div>
									<!--end::Avatar-->
								</div>
								<!--end::User-->
								<!--begin::Text-->
								<div class="p-5 rounded bg-light-primary text-gray-900 fw-semibold mw-lg-400px text-end"
									data-kt-element="message-text">Hey there, we’re just writing to let you know that you’ve
									been subscribed to a repository on GitHub.</div>
								<!--end::Text-->
							</div>
							<!--end::Wrapper-->
						</div>
						<!--end::Message(out)-->
						<!--begin::Message(in)-->
						<div class="d-flex justify-content-start mb-10">
							<!--begin::Wrapper-->
							<div class="d-flex flex-column align-items-start">
								<!--begin::User-->
								<div class="d-flex align-items-center mb-2">
									<!--begin::Avatar-->
									<div class="symbol symbol-35px symbol-circle">
										<img alt="Pic" src="assets/media/avatars/300-25.jpg" />
									</div>
									<!--end::Avatar-->
									<!--begin::Details-->
									<div class="ms-3">
										<a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary me-1">Brian Cox</a>
										<span class="text-muted fs-7 mb-1">1 Hour</span>
									</div>
									<!--end::Details-->
								</div>
								<!--end::User-->
								<!--begin::Text-->
								<div class="p-5 rounded bg-light-info text-gray-900 fw-semibold mw-lg-400px text-start"
									data-kt-element="message-text">Ok, Understood!</div>
								<!--end::Text-->
							</div>
							<!--end::Wrapper-->
						</div>
						<!--end::Message(in)-->
						<!--begin::Message(out)-->
						<div class="d-flex justify-content-end mb-10">
							<!--begin::Wrapper-->
							<div class="d-flex flex-column align-items-end">
								<!--begin::User-->
								<div class="d-flex align-items-center mb-2">
									<!--begin::Details-->
									<div class="me-3">
										<span class="text-muted fs-7 mb-1">2 Hours</span>
										<a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary ms-1">You</a>
									</div>
									<!--end::Details-->
									<!--begin::Avatar-->
									<div class="symbol symbol-35px symbol-circle">
										<img alt="Pic" src="assets/media/avatars/300-1.jpg" />
									</div>
									<!--end::Avatar-->
								</div>
								<!--end::User-->
								<!--begin::Text-->
								<div class="p-5 rounded bg-light-primary text-gray-900 fw-semibold mw-lg-400px text-end"
									data-kt-element="message-text">You’ll receive notifications for all issues, pull
									requests!</div>
								<!--end::Text-->
							</div>
							<!--end::Wrapper-->
						</div>
						<!--end::Message(out)-->
						<!--begin::Message(in)-->
						<div class="d-flex justify-content-start mb-10">
							<!--begin::Wrapper-->
							<div class="d-flex flex-column align-items-start">
								<!--begin::User-->
								<div class="d-flex align-items-center mb-2">
									<!--begin::Avatar-->
									<div class="symbol symbol-35px symbol-circle">
										<img alt="Pic" src="assets/media/avatars/300-25.jpg" />
									</div>
									<!--end::Avatar-->
									<!--begin::Details-->
									<div class="ms-3">
										<a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary me-1">Brian Cox</a>
										<span class="text-muted fs-7 mb-1">3 Hours</span>
									</div>
									<!--end::Details-->
								</div>
								<!--end::User-->
								<!--begin::Text-->
								<div class="p-5 rounded bg-light-info text-gray-900 fw-semibold mw-lg-400px text-start"
									data-kt-element="message-text">You can unwatch this repository immediately by clicking
									here:
									<a href="https://keenthemes.com">Keenthemes.com</a>
								</div>
								<!--end::Text-->
							</div>
							<!--end::Wrapper-->
						</div>
						<!--end::Message(in)-->
						<!--begin::Message(out)-->
						<div class="d-flex justify-content-end mb-10">
							<!--begin::Wrapper-->
							<div class="d-flex flex-column align-items-end">
								<!--begin::User-->
								<div class="d-flex align-items-center mb-2">
									<!--begin::Details-->
									<div class="me-3">
										<span class="text-muted fs-7 mb-1">4 Hours</span>
										<a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary ms-1">You</a>
									</div>
									<!--end::Details-->
									<!--begin::Avatar-->
									<div class="symbol symbol-35px symbol-circle">
										<img alt="Pic" src="assets/media/avatars/300-1.jpg" />
									</div>
									<!--end::Avatar-->
								</div>
								<!--end::User-->
								<!--begin::Text-->
								<div class="p-5 rounded bg-light-primary text-gray-900 fw-semibold mw-lg-400px text-end"
									data-kt-element="message-text">Most purchased Business courses during this sale!</div>
								<!--end::Text-->
							</div>
							<!--end::Wrapper-->
						</div>
						<!--end::Message(out)-->
						<!--begin::Message(in)-->
						<div class="d-flex justify-content-start mb-10">
							<!--begin::Wrapper-->
							<div class="d-flex flex-column align-items-start">
								<!--begin::User-->
								<div class="d-flex align-items-center mb-2">
									<!--begin::Avatar-->
									<div class="symbol symbol-35px symbol-circle">
										<img alt="Pic" src="assets/media/avatars/300-25.jpg" />
									</div>
									<!--end::Avatar-->
									<!--begin::Details-->
									<div class="ms-3">
										<a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary me-1">Brian Cox</a>
										<span class="text-muted fs-7 mb-1">5 Hours</span>
									</div>
									<!--end::Details-->
								</div>
								<!--end::User-->
								<!--begin::Text-->
								<div class="p-5 rounded bg-light-info text-gray-900 fw-semibold mw-lg-400px text-start"
									data-kt-element="message-text">Company BBQ to celebrate the last quater achievements and
									goals. Food and drinks provided</div>
								<!--end::Text-->
							</div>
							<!--end::Wrapper-->
						</div>
						<!--end::Message(in)-->
						<!--begin::Message(template for out)-->
						<div class="d-flex justify-content-end mb-10 d-none" data-kt-element="template-out">
							<!--begin::Wrapper-->
							<div class="d-flex flex-column align-items-end">
								<!--begin::User-->
								<div class="d-flex align-items-center mb-2">
									<!--begin::Details-->
									<div class="me-3">
										<span class="text-muted fs-7 mb-1">Just now</span>
										<a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary ms-1">You</a>
									</div>
									<!--end::Details-->
									<!--begin::Avatar-->
									<div class="symbol symbol-35px symbol-circle">
										<img alt="Pic" src="assets/media/avatars/300-1.jpg" />
									</div>
									<!--end::Avatar-->
								</div>
								<!--end::User-->
								<!--begin::Text-->
								<div class="p-5 rounded bg-light-primary text-gray-900 fw-semibold mw-lg-400px text-end"
									data-kt-element="message-text"></div>
								<!--end::Text-->
							</div>
							<!--end::Wrapper-->
						</div>
						<!--end::Message(template for out)-->
						<!--begin::Message(template for in)-->
						<div class="d-flex justify-content-start mb-10 d-none" data-kt-element="template-in">
							<!--begin::Wrapper-->
							<div class="d-flex flex-column align-items-start">
								<!--begin::User-->
								<div class="d-flex align-items-center mb-2">
									<!--begin::Avatar-->
									<div class="symbol symbol-35px symbol-circle">
										<img alt="Pic" src="assets/media/avatars/300-25.jpg" />
									</div>
									<!--end::Avatar-->
									<!--begin::Details-->
									<div class="ms-3">
										<a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary me-1">Brian Cox</a>
										<span class="text-muted fs-7 mb-1">Just now</span>
									</div>
									<!--end::Details-->
								</div>
								<!--end::User-->
								<!--begin::Text-->
								<div class="p-5 rounded bg-light-info text-gray-900 fw-semibold mw-lg-400px text-start"
									data-kt-element="message-text">Right before vacation season we have the next Big Deal
									for you.</div>
								<!--end::Text-->
							</div>
							<!--end::Wrapper-->
						</div>
						<!--end::Message(template for in)-->
					</div>
					<!--end::Messages-->
				</div>
				<!--end::Card body-->
				<!--begin::Card footer-->
				<div class="card-footer pt-4" id="kt_drawer_chat_messenger_footer">
					<!--begin::Input-->
					<textarea class="form-control form-control-flush mb-3" rows="1" data-kt-element="input"
						placeholder="Type a message"></textarea>
					<!--end::Input-->
					<!--begin:Toolbar-->
					<div class="d-flex flex-stack">
						<!--begin::Actions-->
						<div class="d-flex align-items-center me-2">
							<button class="btn btn-sm btn-icon btn-active-light-primary me-1" type="button"
								data-bs-toggle="tooltip" title="Coming soon">
								<i class="ki-duotone ki-paper-clip fs-3"></i>
							</button>
							<button class="btn btn-sm btn-icon btn-active-light-primary me-1" type="button"
								data-bs-toggle="tooltip" title="Coming soon">
								<i class="ki-duotone ki-cloud-add fs-3">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
							</button>
						</div>
						<!--end::Actions-->
						<!--begin::Send-->
						<button class="btn btn-primary btn-sm" type="button" data-kt-element="send">Send</button>
						<!--end::Send-->
					</div>
					<!--end::Toolbar-->
				</div>
				<!--end::Card footer-->
			</div>
			<!--end::Messenger-->
		</div>
		<!--end::Chat drawer-->
		<!--begin::Chat drawer-->
		<div id="kt_shopping_cart" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="cart"
			data-kt-drawer-activate="true" data-kt-drawer-overlay="true"
			data-kt-drawer-width="{default:'300px', 'md': '500px'}" data-kt-drawer-direction="end"
			data-kt-drawer-toggle="#kt_drawer_shopping_cart_toggle" data-kt-drawer-close="#kt_drawer_shopping_cart_close">
			<!--begin::Messenger-->
			<div class="card card-flush w-100 rounded-0">
				<!--begin::Card header-->
				<div class="card-header">
					<!--begin::Title-->
					<h3 class="card-title text-gray-900 fw-bold">Shopping Cart</h3>
					<!--end::Title-->
					<!--begin::Card toolbar-->
					<div class="card-toolbar">
						<!--begin::Close-->
						<div class="btn btn-sm btn-icon btn-active-light-primary" id="kt_drawer_shopping_cart_close">
							<i class="ki-duotone ki-cross fs-2">
								<span class="path1"></span>
								<span class="path2"></span>
							</i>
						</div>
						<!--end::Close-->
					</div>
					<!--end::Card toolbar-->
				</div>
				<!--end::Card header-->
				<!--begin::Card body-->
				<div class="card-body hover-scroll-overlay-y h-400px pt-5">
					<!--begin::Item-->
					<div class="d-flex flex-stack">
						<!--begin::Wrapper-->
						<div class="d-flex flex-column me-3">
							<!--begin::Section-->
							<div class="mb-3">
								<a href="apps/ecommerce/sales/details.html"
									class="text-gray-800 text-hover-primary fs-4 fw-bold">Iblender</a>
								<span class="text-gray-500 fw-semibold d-block">The best kitchen gadget in 2022</span>
							</div>
							<!--end::Section-->
							<!--begin::Section-->
							<div class="d-flex align-items-center">
								<span class="fw-bold text-gray-800 fs-5">$ 350</span>
								<span class="text-muted mx-2">for</span>
								<span class="fw-bold text-gray-800 fs-5 me-3">5</span>
								<a href="#"
									class="btn btn-sm btn-light-success btn-icon-success btn-icon w-25px h-25px me-2">
									<i class="ki-duotone ki-minus fs-4"></i>
								</a>
								<a href="#" class="btn btn-sm btn-light-success btn-icon w-25px h-25px">
									<i class="ki-duotone ki-plus fs-4"></i>
								</a>
							</div>
							<!--end::Wrapper-->
						</div>
						<!--end::Wrapper-->
						<!--begin::Pic-->
						<div class="symbol symbol-70px symbol-2by3 flex-shrink-0">
							<img src="assets/media/stock/600x400/img-1.jpg" alt="" />
						</div>
						<!--end::Pic-->
					</div>
					<!--end::Item-->
					<!--begin::Separator-->
					<div class="separator separator-dashed my-6"></div>
					<!--end::Separator-->
					<!--begin::Item-->
					<div class="d-flex flex-stack">
						<!--begin::Wrapper-->
						<div class="d-flex flex-column me-3">
							<!--begin::Section-->
							<div class="mb-3">
								<a href="apps/ecommerce/sales/details.html"
									class="text-gray-800 text-hover-primary fs-4 fw-bold">SmartCleaner</a>
								<span class="text-gray-500 fw-semibold d-block">Smart tool for cooking</span>
							</div>
							<!--end::Section-->
							<!--begin::Section-->
							<div class="d-flex align-items-center">
								<span class="fw-bold text-gray-800 fs-5">$ 650</span>
								<span class="text-muted mx-2">for</span>
								<span class="fw-bold text-gray-800 fs-5 me-3">4</span>
								<a href="#"
									class="btn btn-sm btn-light-success btn-icon-success btn-icon w-25px h-25px me-2">
									<i class="ki-duotone ki-minus fs-4"></i>
								</a>
								<a href="#" class="btn btn-sm btn-light-success btn-icon w-25px h-25px">
									<i class="ki-duotone ki-plus fs-4"></i>
								</a>
							</div>
							<!--end::Wrapper-->
						</div>
						<!--end::Wrapper-->
						<!--begin::Pic-->
						<div class="symbol symbol-70px symbol-2by3 flex-shrink-0">
							<img src="assets/media/stock/600x400/img-3.jpg" alt="" />
						</div>
						<!--end::Pic-->
					</div>
					<!--end::Item-->
					<!--begin::Separator-->
					<div class="separator separator-dashed my-6"></div>
					<!--end::Separator-->
					<!--begin::Item-->
					<div class="d-flex flex-stack">
						<!--begin::Wrapper-->
						<div class="d-flex flex-column me-3">
							<!--begin::Section-->
							<div class="mb-3">
								<a href="apps/ecommerce/sales/details.html"
									class="text-gray-800 text-hover-primary fs-4 fw-bold">CameraMaxr</a>
								<span class="text-gray-500 fw-semibold d-block">Professional camera for edge</span>
							</div>
							<!--end::Section-->
							<!--begin::Section-->
							<div class="d-flex align-items-center">
								<span class="fw-bold text-gray-800 fs-5">$ 150</span>
								<span class="text-muted mx-2">for</span>
								<span class="fw-bold text-gray-800 fs-5 me-3">3</span>
								<a href="#"
									class="btn btn-sm btn-light-success btn-icon-success btn-icon w-25px h-25px me-2">
									<i class="ki-duotone ki-minus fs-4"></i>
								</a>
								<a href="#" class="btn btn-sm btn-light-success btn-icon w-25px h-25px">
									<i class="ki-duotone ki-plus fs-4"></i>
								</a>
							</div>
							<!--end::Wrapper-->
						</div>
						<!--end::Wrapper-->
						<!--begin::Pic-->
						<div class="symbol symbol-70px symbol-2by3 flex-shrink-0">
							<img src="assets/media/stock/600x400/img-8.jpg" alt="" />
						</div>
						<!--end::Pic-->
					</div>
					<!--end::Item-->
					<!--begin::Separator-->
					<div class="separator separator-dashed my-6"></div>
					<!--end::Separator-->
					<!--begin::Item-->
					<div class="d-flex flex-stack">
						<!--begin::Wrapper-->
						<div class="d-flex flex-column me-3">
							<!--begin::Section-->
							<div class="mb-3">
								<a href="apps/ecommerce/sales/details.html"
									class="text-gray-800 text-hover-primary fs-4 fw-bold">$D Printer</a>
								<span class="text-gray-500 fw-semibold d-block">Manfactoring unique objekts</span>
							</div>
							<!--end::Section-->
							<!--begin::Section-->
							<div class="d-flex align-items-center">
								<span class="fw-bold text-gray-800 fs-5">$ 1450</span>
								<span class="text-muted mx-2">for</span>
								<span class="fw-bold text-gray-800 fs-5 me-3">7</span>
								<a href="#"
									class="btn btn-sm btn-light-success btn-icon-success btn-icon w-25px h-25px me-2">
									<i class="ki-duotone ki-minus fs-4"></i>
								</a>
								<a href="#" class="btn btn-sm btn-light-success btn-icon w-25px h-25px">
									<i class="ki-duotone ki-plus fs-4"></i>
								</a>
							</div>
							<!--end::Wrapper-->
						</div>
						<!--end::Wrapper-->
						<!--begin::Pic-->
						<div class="symbol symbol-70px symbol-2by3 flex-shrink-0">
							<img src="assets/media/stock/600x400/img-26.jpg" alt="" />
						</div>
						<!--end::Pic-->
					</div>
					<!--end::Item-->
					<!--begin::Separator-->
					<div class="separator separator-dashed my-6"></div>
					<!--end::Separator-->
					<!--begin::Item-->
					<div class="d-flex flex-stack">
						<!--begin::Wrapper-->
						<div class="d-flex flex-column me-3">
							<!--begin::Section-->
							<div class="mb-3">
								<a href="apps/ecommerce/sales/details.html"
									class="text-gray-800 text-hover-primary fs-4 fw-bold">MotionWire</a>
								<span class="text-gray-500 fw-semibold d-block">Perfect animation tool</span>
							</div>
							<!--end::Section-->
							<!--begin::Section-->
							<div class="d-flex align-items-center">
								<span class="fw-bold text-gray-800 fs-5">$ 650</span>
								<span class="text-muted mx-2">for</span>
								<span class="fw-bold text-gray-800 fs-5 me-3">7</span>
								<a href="#"
									class="btn btn-sm btn-light-success btn-icon-success btn-icon w-25px h-25px me-2">
									<i class="ki-duotone ki-minus fs-4"></i>
								</a>
								<a href="#" class="btn btn-sm btn-light-success btn-icon w-25px h-25px">
									<i class="ki-duotone ki-plus fs-4"></i>
								</a>
							</div>
							<!--end::Wrapper-->
						</div>
						<!--end::Wrapper-->
						<!--begin::Pic-->
						<div class="symbol symbol-70px symbol-2by3 flex-shrink-0">
							<img src="assets/media/stock/600x400/img-21.jpg" alt="" />
						</div>
						<!--end::Pic-->
					</div>
					<!--end::Item-->
					<!--begin::Separator-->
					<div class="separator separator-dashed my-6"></div>
					<!--end::Separator-->
					<!--begin::Item-->
					<div class="d-flex flex-stack">
						<!--begin::Wrapper-->
						<div class="d-flex flex-column me-3">
							<!--begin::Section-->
							<div class="mb-3">
								<a href="apps/ecommerce/sales/details.html"
									class="text-gray-800 text-hover-primary fs-4 fw-bold">Samsung</a>
								<span class="text-gray-500 fw-semibold d-block">Profile info,Timeline etc</span>
							</div>
							<!--end::Section-->
							<!--begin::Section-->
							<div class="d-flex align-items-center">
								<span class="fw-bold text-gray-800 fs-5">$ 720</span>
								<span class="text-muted mx-2">for</span>
								<span class="fw-bold text-gray-800 fs-5 me-3">6</span>
								<a href="#"
									class="btn btn-sm btn-light-success btn-icon-success btn-icon w-25px h-25px me-2">
									<i class="ki-duotone ki-minus fs-4"></i>
								</a>
								<a href="#" class="btn btn-sm btn-light-success btn-icon w-25px h-25px">
									<i class="ki-duotone ki-plus fs-4"></i>
								</a>
							</div>
							<!--end::Wrapper-->
						</div>
						<!--end::Wrapper-->
						<!--begin::Pic-->
						<div class="symbol symbol-70px symbol-2by3 flex-shrink-0">
							<img src="assets/media/stock/600x400/img-34.jpg" alt="" />
						</div>
						<!--end::Pic-->
					</div>
					<!--end::Item-->
					<!--begin::Separator-->
					<div class="separator separator-dashed my-6"></div>
					<!--end::Separator-->
					<!--begin::Item-->
					<div class="d-flex flex-stack">
						<!--begin::Wrapper-->
						<div class="d-flex flex-column me-3">
							<!--begin::Section-->
							<div class="mb-3">
								<a href="apps/ecommerce/sales/details.html"
									class="text-gray-800 text-hover-primary fs-4 fw-bold">$D Printer</a>
								<span class="text-gray-500 fw-semibold d-block">Manfactoring unique objekts</span>
							</div>
							<!--end::Section-->
							<!--begin::Section-->
							<div class="d-flex align-items-center">
								<span class="fw-bold text-gray-800 fs-5">$ 430</span>
								<span class="text-muted mx-2">for</span>
								<span class="fw-bold text-gray-800 fs-5 me-3">8</span>
								<a href="#"
									class="btn btn-sm btn-light-success btn-icon-success btn-icon w-25px h-25px me-2">
									<i class="ki-duotone ki-minus fs-4"></i>
								</a>
								<a href="#" class="btn btn-sm btn-light-success btn-icon w-25px h-25px">
									<i class="ki-duotone ki-plus fs-4"></i>
								</a>
							</div>
							<!--end::Wrapper-->
						</div>
						<!--end::Wrapper-->
						<!--begin::Pic-->
						<div class="symbol symbol-70px symbol-2by3 flex-shrink-0">
							<img src="assets/media/stock/600x400/img-27.jpg" alt="" />
						</div>
						<!--end::Pic-->
					</div>
					<!--end::Item-->
				</div>
				<!--end::Card body-->
				<!--begin::Card footer-->
				<div class="card-footer">
					<!--begin::Item-->
					<div class="d-flex flex-stack">
						<span class="fw-bold text-gray-600">Total</span>
						<span class="text-gray-800 fw-bolder fs-5">$ 1840.00</span>
					</div>
					<!--end::Item-->
					<!--begin::Item-->
					<div class="d-flex flex-stack">
						<span class="fw-bold text-gray-600">Sub total</span>
						<span class="text-primary fw-bolder fs-5">$ 246.35</span>
					</div>
					<!--end::Item-->
					<!--end::Action-->
					<div class="d-flex justify-content-end mt-9">
						<a href="#" class="btn btn-primary btn-sm d-flex justify-content-end">Pleace Order</a>
					</div>
					<!--end::Action-->
				</div>
				<!--end::Card footer-->
			</div>
			<!--end::Messenger-->
		</div>
		<!--end::Chat drawer-->
		<!--end::Drawers-->
		<!--begin::Scrolltop-->
		<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
			<i class="ki-duotone ki-arrow-up">
				<span class="path1"></span>
				<span class="path2"></span>
			</i>
		</div>
		<!--end::Scrolltop-->
		<!--begin::Modals-->
		<!--begin::Modal - Upgrade plan-->
		<div class="modal fade" id="kt_modal_upgrade_plan" tabindex="-1" aria-hidden="true">
			<!--begin::Modal dialog-->
			<div class="modal-dialog modal-xl">
				<!--begin::Modal content-->
				<div class="modal-content rounded">
					<!--begin::Modal header-->
					<div class="modal-header justify-content-end border-0 pb-0">
						<!--begin::Close-->
						<div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
							<i class="ki-duotone ki-cross fs-1">
								<span class="path1"></span>
								<span class="path2"></span>
							</i>
						</div>
						<!--end::Close-->
					</div>
					<!--end::Modal header-->
					<!--begin::Modal body-->
					<div class="modal-body pt-0 pb-15 px-5 px-xl-20">
						<!--begin::Heading-->
						<div class="mb-13 text-center">
							<h1 class="mb-3">Upgrade a Plan</h1>
							<div class="text-muted fw-semibold fs-5">If you need more info, please check
								<a href="#" class="link-primary fw-bold">Pricing Guidelines</a>.
							</div>
						</div>
						<!--end::Heading-->
						<!--begin::Plans-->
						<div class="d-flex flex-column">
							<!--begin::Nav group-->
							<div class="nav-group nav-group-outline mx-auto" data-kt-buttons="true">
								<button class="btn btn-color-gray-500 btn-active btn-active-secondary px-6 py-3 me-2 active"
									data-kt-plan="month">Monthly</button>
								<button class="btn btn-color-gray-500 btn-active btn-active-secondary px-6 py-3"
									data-kt-plan="annual">Annual</button>
							</div>
							<!--end::Nav group-->
							<!--begin::Row-->
							<div class="row mt-10">
								<!--begin::Col-->
								<div class="col-lg-6 mb-10 mb-lg-0">
									<!--begin::Tabs-->
									<div class="nav flex-column">
										<!--begin::Tab link-->
										<label
											class="nav-link btn btn-outline btn-outline-dashed btn-color-dark btn-active btn-active-primary d-flex flex-stack text-start p-6 active mb-6"
											data-bs-toggle="tab" data-bs-target="#kt_upgrade_plan_startup">
											<!--end::Description-->
											<div class="d-flex align-items-center me-2">
												<!--begin::Radio-->
												<div
													class="form-check form-check-custom form-check-solid form-check-success flex-shrink-0 me-6">
													<input class="form-check-input" type="radio" name="plan"
														checked="checked" value="startup" />
												</div>
												<!--end::Radio-->
												<!--begin::Info-->
												<div class="flex-grow-1">
													<div class="d-flex align-items-center fs-2 fw-bold flex-wrap">Startup
													</div>
													<div class="fw-semibold opacity-75">Best for startups</div>
												</div>
												<!--end::Info-->
											</div>
											<!--end::Description-->
											<!--begin::Price-->
											<div class="ms-5">
												<span class="mb-2">$</span>
												<span class="fs-3x fw-bold" data-kt-plan-price-month="39"
													data-kt-plan-price-annual="399">39</span>
												<span class="fs-7 opacity-50">/
													<span data-kt-element="period">Mon</span></span>
											</div>
											<!--end::Price-->
										</label>
										<!--end::Tab link-->
										<!--begin::Tab link-->
										<label
											class="nav-link btn btn-outline btn-outline-dashed btn-color-dark btn-active btn-active-primary d-flex flex-stack text-start p-6 mb-6"
											data-bs-toggle="tab" data-bs-target="#kt_upgrade_plan_advanced">
											<!--end::Description-->
											<div class="d-flex align-items-center me-2">
												<!--begin::Radio-->
												<div
													class="form-check form-check-custom form-check-solid form-check-success flex-shrink-0 me-6">
													<input class="form-check-input" type="radio" name="plan"
														value="advanced" />
												</div>
												<!--end::Radio-->
												<!--begin::Info-->
												<div class="flex-grow-1">
													<div class="d-flex align-items-center fs-2 fw-bold flex-wrap">Advanced
													</div>
													<div class="fw-semibold opacity-75">Best for 100+ team size</div>
												</div>
												<!--end::Info-->
											</div>
											<!--end::Description-->
											<!--begin::Price-->
											<div class="ms-5">
												<span class="mb-2">$</span>
												<span class="fs-3x fw-bold" data-kt-plan-price-month="339"
													data-kt-plan-price-annual="3399">339</span>
												<span class="fs-7 opacity-50">/
													<span data-kt-element="period">Mon</span></span>
											</div>
											<!--end::Price-->
										</label>
										<!--end::Tab link-->
										<!--begin::Tab link-->
										<label
											class="nav-link btn btn-outline btn-outline-dashed btn-color-dark btn-active btn-active-primary d-flex flex-stack text-start p-6 mb-6"
											data-bs-toggle="tab" data-bs-target="#kt_upgrade_plan_enterprise">
											<!--end::Description-->
											<div class="d-flex align-items-center me-2">
												<!--begin::Radio-->
												<div
													class="form-check form-check-custom form-check-solid form-check-success flex-shrink-0 me-6">
													<input class="form-check-input" type="radio" name="plan"
														value="enterprise" />
												</div>
												<!--end::Radio-->
												<!--begin::Info-->
												<div class="flex-grow-1">
													<div class="d-flex align-items-center fs-2 fw-bold flex-wrap">Enterprise
														<span
															class="badge badge-light-success ms-2 py-2 px-3 fs-7">Popular</span>
													</div>
													<div class="fw-semibold opacity-75">Best value for 1000+ team</div>
												</div>
												<!--end::Info-->
											</div>
											<!--end::Description-->
											<!--begin::Price-->
											<div class="ms-5">
												<span class="mb-2">$</span>
												<span class="fs-3x fw-bold" data-kt-plan-price-month="999"
													data-kt-plan-price-annual="9999">999</span>
												<span class="fs-7 opacity-50">/
													<span data-kt-element="period">Mon</span></span>
											</div>
											<!--end::Price-->
										</label>
										<!--end::Tab link-->
										<!--begin::Tab link-->
										<label
											class="nav-link btn btn-outline btn-outline-dashed btn-color-dark btn-active btn-active-primary d-flex flex-stack text-start p-6 mb-6"
											data-bs-toggle="tab" data-bs-target="#kt_upgrade_plan_custom">
											<!--end::Description-->
											<div class="d-flex align-items-center me-2">
												<!--begin::Radio-->
												<div
													class="form-check form-check-custom form-check-solid form-check-success flex-shrink-0 me-6">
													<input class="form-check-input" type="radio" name="plan"
														value="custom" />
												</div>
												<!--end::Radio-->
												<!--begin::Info-->
												<div class="flex-grow-1">
													<div class="d-flex align-items-center fs-2 fw-bold flex-wrap">Custom
													</div>
													<div class="fw-semibold opacity-75">Requet a custom license</div>
												</div>
												<!--end::Info-->
											</div>
											<!--end::Description-->
											<!--begin::Price-->
											<div class="ms-5">
												<a href="#" class="btn btn-sm btn-success">Contact Us</a>
											</div>
											<!--end::Price-->
										</label>
										<!--end::Tab link-->
									</div>
									<!--end::Tabs-->
								</div>
								<!--end::Col-->
								<!--begin::Col-->
								<div class="col-lg-6">
									<!--begin::Tab content-->
									<div class="tab-content rounded h-100 bg-light p-10">
										<!--begin::Tab Pane-->
										<div class="tab-pane fade show active" id="kt_upgrade_plan_startup">
											<!--begin::Heading-->
											<div class="pb-5">
												<h2 class="fw-bold text-gray-900">What’s in Startup Plan?</h2>
												<div class="text-muted fw-semibold">Optimal for 10+ team size and new
													startup</div>
											</div>
											<!--end::Heading-->
											<!--begin::Body-->
											<div class="pt-1">
												<!--begin::Item-->
												<div class="d-flex align-items-center mb-7">
													<span class="fw-semibold fs-5 text-gray-700 flex-grow-1">Up to 10 Active
														Users</span>
													<i class="ki-duotone ki-check-circle fs-1 text-success">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</div>
												<!--end::Item-->
												<!--begin::Item-->
												<div class="d-flex align-items-center mb-7">
													<span class="fw-semibold fs-5 text-gray-700 flex-grow-1">Up to 30
														Project Integrations</span>
													<i class="ki-duotone ki-check-circle fs-1 text-success">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</div>
												<!--end::Item-->
												<!--begin::Item-->
												<div class="d-flex align-items-center mb-7">
													<span class="fw-semibold fs-5 text-gray-700 flex-grow-1">Analytics
														Module</span>
													<i class="ki-duotone ki-check-circle fs-1 text-success">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</div>
												<!--end::Item-->
												<!--begin::Item-->
												<div class="d-flex align-items-center mb-7">
													<span class="fw-semibold fs-5 text-muted flex-grow-1">Finance
														Module</span>
													<i class="ki-duotone ki-cross-circle fs-1">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</div>
												<!--end::Item-->
												<!--begin::Item-->
												<div class="d-flex align-items-center mb-7">
													<span class="fw-semibold fs-5 text-muted flex-grow-1">Accounting
														Module</span>
													<i class="ki-duotone ki-cross-circle fs-1">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</div>
												<!--end::Item-->
												<!--begin::Item-->
												<div class="d-flex align-items-center mb-7">
													<span class="fw-semibold fs-5 text-muted flex-grow-1">Network
														Platform</span>
													<i class="ki-duotone ki-cross-circle fs-1">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</div>
												<!--end::Item-->
												<!--begin::Item-->
												<div class="d-flex align-items-center">
													<span class="fw-semibold fs-5 text-muted flex-grow-1">Unlimited Cloud
														Space</span>
													<i class="ki-duotone ki-cross-circle fs-1">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</div>
												<!--end::Item-->
											</div>
											<!--end::Body-->
										</div>
										<!--end::Tab Pane-->
										<!--begin::Tab Pane-->
										<div class="tab-pane fade" id="kt_upgrade_plan_advanced">
											<!--begin::Heading-->
											<div class="pb-5">
												<h2 class="fw-bold text-gray-900">What’s in Startup Plan?</h2>
												<div class="text-muted fw-semibold">Optimal for 100+ team size and grown
													company</div>
											</div>
											<!--end::Heading-->
											<!--begin::Body-->
											<div class="pt-1">
												<!--begin::Item-->
												<div class="d-flex align-items-center mb-7">
													<span class="fw-semibold fs-5 text-gray-700 flex-grow-1">Up to 10 Active
														Users</span>
													<i class="ki-duotone ki-check-circle fs-1 text-success">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</div>
												<!--end::Item-->
												<!--begin::Item-->
												<div class="d-flex align-items-center mb-7">
													<span class="fw-semibold fs-5 text-gray-700 flex-grow-1">Up to 30
														Project Integrations</span>
													<i class="ki-duotone ki-check-circle fs-1 text-success">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</div>
												<!--end::Item-->
												<!--begin::Item-->
												<div class="d-flex align-items-center mb-7">
													<span class="fw-semibold fs-5 text-gray-700 flex-grow-1">Analytics
														Module</span>
													<i class="ki-duotone ki-check-circle fs-1 text-success">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</div>
												<!--end::Item-->
												<!--begin::Item-->
												<div class="d-flex align-items-center mb-7">
													<span class="fw-semibold fs-5 text-gray-700 flex-grow-1">Finance
														Module</span>
													<i class="ki-duotone ki-check-circle fs-1 text-success">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</div>
												<!--end::Item-->
												<!--begin::Item-->
												<div class="d-flex align-items-center mb-7">
													<span class="fw-semibold fs-5 text-gray-700 flex-grow-1">Accounting
														Module</span>
													<i class="ki-duotone ki-check-circle fs-1 text-success">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</div>
												<!--end::Item-->
												<!--begin::Item-->
												<div class="d-flex align-items-center mb-7">
													<span class="fw-semibold fs-5 text-muted flex-grow-1">Network
														Platform</span>
													<i class="ki-duotone ki-cross-circle fs-1">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</div>
												<!--end::Item-->
												<!--begin::Item-->
												<div class="d-flex align-items-center">
													<span class="fw-semibold fs-5 text-muted flex-grow-1">Unlimited Cloud
														Space</span>
													<i class="ki-duotone ki-cross-circle fs-1">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</div>
												<!--end::Item-->
											</div>
											<!--end::Body-->
										</div>
										<!--end::Tab Pane-->
										<!--begin::Tab Pane-->
										<div class="tab-pane fade" id="kt_upgrade_plan_enterprise">
											<!--begin::Heading-->
											<div class="pb-5">
												<h2 class="fw-bold text-gray-900">What’s in Startup Plan?</h2>
												<div class="text-muted fw-semibold">Optimal for 1000+ team and enterpise
												</div>
											</div>
											<!--end::Heading-->
											<!--begin::Body-->
											<div class="pt-1">
												<!--begin::Item-->
												<div class="d-flex align-items-center mb-7">
													<span class="fw-semibold fs-5 text-gray-700 flex-grow-1">Up to 10 Active
														Users</span>
													<i class="ki-duotone ki-check-circle fs-1 text-success">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</div>
												<!--end::Item-->
												<!--begin::Item-->
												<div class="d-flex align-items-center mb-7">
													<span class="fw-semibold fs-5 text-gray-700 flex-grow-1">Up to 30
														Project Integrations</span>
													<i class="ki-duotone ki-check-circle fs-1 text-success">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</div>
												<!--end::Item-->
												<!--begin::Item-->
												<div class="d-flex align-items-center mb-7">
													<span class="fw-semibold fs-5 text-gray-700 flex-grow-1">Analytics
														Module</span>
													<i class="ki-duotone ki-check-circle fs-1 text-success">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</div>
												<!--end::Item-->
												<!--begin::Item-->
												<div class="d-flex align-items-center mb-7">
													<span class="fw-semibold fs-5 text-gray-700 flex-grow-1">Finance
														Module</span>
													<i class="ki-duotone ki-check-circle fs-1 text-success">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</div>
												<!--end::Item-->
												<!--begin::Item-->
												<div class="d-flex align-items-center mb-7">
													<span class="fw-semibold fs-5 text-gray-700 flex-grow-1">Accounting
														Module</span>
													<i class="ki-duotone ki-check-circle fs-1 text-success">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</div>
												<!--end::Item-->
												<!--begin::Item-->
												<div class="d-flex align-items-center mb-7">
													<span class="fw-semibold fs-5 text-gray-700 flex-grow-1">Network
														Platform</span>
													<i class="ki-duotone ki-check-circle fs-1 text-success">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</div>
												<!--end::Item-->
												<!--begin::Item-->
												<div class="d-flex align-items-center">
													<span class="fw-semibold fs-5 text-gray-700 flex-grow-1">Unlimited Cloud
														Space</span>
													<i class="ki-duotone ki-check-circle fs-1 text-success">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</div>
												<!--end::Item-->
											</div>
											<!--end::Body-->
										</div>
										<!--end::Tab Pane-->
										<!--begin::Tab Pane-->
										<div class="tab-pane fade" id="kt_upgrade_plan_custom">
											<!--begin::Heading-->
											<div class="pb-5">
												<h2 class="fw-bold text-gray-900">What’s in Startup Plan?</h2>
												<div class="text-muted fw-semibold">Optimal for corporations</div>
											</div>
											<!--end::Heading-->
											<!--begin::Body-->
											<div class="pt-1">
												<!--begin::Item-->
												<div class="d-flex align-items-center mb-7">
													<span class="fw-semibold fs-5 text-gray-700 flex-grow-1">Unlimited
														Users</span>
													<i class="ki-duotone ki-check-circle fs-1 text-success">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</div>
												<!--end::Item-->
												<!--begin::Item-->
												<div class="d-flex align-items-center mb-7">
													<span class="fw-semibold fs-5 text-gray-700 flex-grow-1">Unlimited
														Project Integrations</span>
													<i class="ki-duotone ki-check-circle fs-1 text-success">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</div>
												<!--end::Item-->
												<!--begin::Item-->
												<div class="d-flex align-items-center mb-7">
													<span class="fw-semibold fs-5 text-gray-700 flex-grow-1">Analytics
														Module</span>
													<i class="ki-duotone ki-check-circle fs-1 text-success">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</div>
												<!--end::Item-->
												<!--begin::Item-->
												<div class="d-flex align-items-center mb-7">
													<span class="fw-semibold fs-5 text-gray-700 flex-grow-1">Finance
														Module</span>
													<i class="ki-duotone ki-check-circle fs-1 text-success">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</div>
												<!--end::Item-->
												<!--begin::Item-->
												<div class="d-flex align-items-center mb-7">
													<span class="fw-semibold fs-5 text-gray-700 flex-grow-1">Accounting
														Module</span>
													<i class="ki-duotone ki-check-circle fs-1 text-success">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</div>
												<!--end::Item-->
												<!--begin::Item-->
												<div class="d-flex align-items-center mb-7">
													<span class="fw-semibold fs-5 text-gray-700 flex-grow-1">Network
														Platform</span>
													<i class="ki-duotone ki-check-circle fs-1 text-success">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</div>
												<!--end::Item-->
												<!--begin::Item-->
												<div class="d-flex align-items-center">
													<span class="fw-semibold fs-5 text-gray-700 flex-grow-1">Unlimited Cloud
														Space</span>
													<i class="ki-duotone ki-check-circle fs-1 text-success">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</div>
												<!--end::Item-->
											</div>
											<!--end::Body-->
										</div>
										<!--end::Tab Pane-->
									</div>
									<!--end::Tab content-->
								</div>
								<!--end::Col-->
							</div>
							<!--end::Row-->
						</div>
						<!--end::Plans-->
						<!--begin::Actions-->
						<div class="d-flex flex-center flex-row-fluid pt-12">
							<button type="reset" class="btn btn-light btn-sm me-3" data-bs-dismiss="modal">Cancel</button>
							<button type="submit" class="btn btn-primary btn-sm" id="kt_modal_upgrade_plan_btn">
								<!--begin::Indicator label-->
								<span class="indicator-label">Upgrade Plan</span>
								<!--end::Indicator label-->
								<!--begin::Indicator progress-->
								<span class="indicator-progress">Please wait...
									<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
								<!--end::Indicator progress-->
							</button>
						</div>
						<!--end::Actions-->
					</div>
					<!--end::Modal body-->
				</div>
				<!--end::Modal content-->
			</div>
			<!--end::Modal dialog-->
		</div>
		<!--end::Modal - Upgrade plan-->
		<!--begin::Modal - Create account-->
		<div class="modal fade" id="kt_modal_create_account" data-bs-backdrop="static" data-bs-keyboard="false"
			tabindex="-1" aria-hidden="true">
			<!--begin::Modal dialog-->
			<div class="modal-dialog mw-1000px">
				<!--begin::Modal content-->
				<div class="modal-content">
					<!--begin::Modal header-->
					<div class="modal-header">
						<!--begin::Title-->
						<h2>Create Business Account</h2>
						<!--end::Title-->
						<!--begin::Close-->
						<div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
							<i class="ki-duotone ki-cross fs-1">
								<span class="path1"></span>
								<span class="path2"></span>
							</i>
						</div>
						<!--end::Close-->
					</div>
					<!--end::Modal header-->
					<!--begin::Modal body-->
					<div class="modal-body scroll-y m-5">
						<!--begin::Stepper-->
						<div class="stepper stepper-links d-flex flex-column" id="kt_create_account_stepper">
							<!--begin::Nav-->
							<div class="stepper-nav py-5">
								<!--begin::Step 1-->
								<div class="stepper-item current" data-kt-stepper-element="nav">
									<h3 class="stepper-title">Account Type</h3>
								</div>
								<!--end::Step 1-->
								<!--begin::Step 2-->
								<div class="stepper-item" data-kt-stepper-element="nav">
									<h3 class="stepper-title">Account Info</h3>
								</div>
								<!--end::Step 2-->
								<!--begin::Step 3-->
								<div class="stepper-item" data-kt-stepper-element="nav">
									<h3 class="stepper-title">Business Details</h3>
								</div>
								<!--end::Step 3-->
								<!--begin::Step 4-->
								<div class="stepper-item" data-kt-stepper-element="nav">
									<h3 class="stepper-title">Billing Details</h3>
								</div>
								<!--end::Step 4-->
								<!--begin::Step 5-->
								<div class="stepper-item" data-kt-stepper-element="nav">
									<h3 class="stepper-title">Completed</h3>
								</div>
								<!--end::Step 5-->
							</div>
							<!--end::Nav-->
							<!--begin::Form-->
							<form class="mx-auto mw-600px w-100 py-10" novalidate="novalidate" id="kt_create_account_form">
								<!--begin::Step 1-->
								<div class="current" data-kt-stepper-element="content">
									<!--begin::Wrapper-->
									<div class="w-100">
										<!--begin::Heading-->
										<div class="pb-10 pb-lg-15">
											<!--begin::Title-->
											<h2 class="fw-bold d-flex align-items-center text-gray-900">Choose Account Type
												<span class="ms-1" data-bs-toggle="tooltip"
													title="Billing is issued based on your selected account typ">
													<i class="ki-duotone ki-information-5 text-gray-500 fs-6">
														<span class="path1"></span>
														<span class="path2"></span>
														<span class="path3"></span>
													</i>
												</span>
											</h2>
											<!--end::Title-->
											<!--begin::Notice-->
											<div class="text-muted fw-semibold fs-6">If you need more info, please check out
												<a href="#" class="link-primary fw-bold">Help Page</a>.
											</div>
											<!--end::Notice-->
										</div>
										<!--end::Heading-->
										<!--begin::Input group-->
										<div class="fv-row">
											<!--begin::Row-->
											<div class="row">
												<!--begin::Col-->
												<div class="col-lg-6">
													<!--begin::Option-->
													<input type="radio" class="btn-check" name="account_type"
														value="personal" checked="checked"
														id="kt_create_account_form_account_type_personal" />
													<label
														class="btn btn-outline btn-outline-dashed btn-active-light-primary p-7 d-flex align-items-center mb-10"
														for="kt_create_account_form_account_type_personal">
														<i class="ki-duotone ki-badge fs-3x me-5">
															<span class="path1"></span>
															<span class="path2"></span>
															<span class="path3"></span>
															<span class="path4"></span>
															<span class="path5"></span>
														</i>
														<!--begin::Info-->
														<span class="d-block fw-semibold text-start">
															<span class="text-gray-900 fw-bold d-block fs-4 mb-2">Personal
																Account</span>
															<span class="text-muted fw-semibold fs-6">If you need more info,
																please check it out</span>
														</span>
														<!--end::Info-->
													</label>
													<!--end::Option-->
												</div>
												<!--end::Col-->
												<!--begin::Col-->
												<div class="col-lg-6">
													<!--begin::Option-->
													<input type="radio" class="btn-check" name="account_type"
														value="corporate"
														id="kt_create_account_form_account_type_corporate" />
													<label
														class="btn btn-outline btn-outline-dashed btn-active-light-primary p-7 d-flex align-items-center"
														for="kt_create_account_form_account_type_corporate">
														<i class="ki-duotone ki-briefcase fs-3x me-5">
															<span class="path1"></span>
															<span class="path2"></span>
														</i>
														<!--begin::Info-->
														<span class="d-block fw-semibold text-start">
															<span class="text-gray-900 fw-bold d-block fs-4 mb-2">Corporate
																Account</span>
															<span class="text-muted fw-semibold fs-6">Create corporate
																account to mane users</span>
														</span>
														<!--end::Info-->
													</label>
													<!--end::Option-->
												</div>
												<!--end::Col-->
											</div>
											<!--end::Row-->
										</div>
										<!--end::Input group-->
									</div>
									<!--end::Wrapper-->
								</div>
								<!--end::Step 1-->
								<!--begin::Step 2-->
								<div data-kt-stepper-element="content">
									<!--begin::Wrapper-->
									<div class="w-100">
										<!--begin::Heading-->
										<div class="pb-10 pb-lg-15">
											<!--begin::Title-->
											<h2 class="fw-bold text-gray-900">Account Info</h2>
											<!--end::Title-->
											<!--begin::Notice-->
											<div class="text-muted fw-semibold fs-6">If you need more info, please check out
												<a href="#" class="link-primary fw-bold">Help Page</a>.
											</div>
											<!--end::Notice-->
										</div>
										<!--end::Heading-->
										<!--begin::Input group-->
										<div class="mb-10 fv-row">
											<!--begin::Label-->
											<label class="d-flex align-items-center form-label mb-3">Specify Team Size
												<span class="ms-1" data-bs-toggle="tooltip"
													title="Provide your team size to help us setup your billing">
													<i class="ki-duotone ki-information-5 text-gray-500 fs-6">
														<span class="path1"></span>
														<span class="path2"></span>
														<span class="path3"></span>
													</i>
												</span></label>
											<!--end::Label-->
											<!--begin::Row-->
											<div class="row mb-2" data-kt-buttons="true">
												<!--begin::Col-->
												<div class="col">
													<!--begin::Option-->
													<label
														class="btn btn-outline btn-outline-dashed btn-active-light-primary w-100 p-4">
														<input type="radio" class="btn-check" name="account_team_size"
															value="1-1" />
														<span class="fw-bold fs-3">1-1</span>
													</label>
													<!--end::Option-->
												</div>
												<!--end::Col-->
												<!--begin::Col-->
												<div class="col">
													<!--begin::Option-->
													<label
														class="btn btn-outline btn-outline-dashed btn-active-light-primary w-100 p-4 active">
														<input type="radio" class="btn-check" name="account_team_size"
															checked="checked" value="2-10" />
														<span class="fw-bold fs-3">2-10</span>
													</label>
													<!--end::Option-->
												</div>
												<!--end::Col-->
												<!--begin::Col-->
												<div class="col">
													<!--begin::Option-->
													<label
														class="btn btn-outline btn-outline-dashed btn-active-light-primary w-100 p-4">
														<input type="radio" class="btn-check" name="account_team_size"
															value="10-50" />
														<span class="fw-bold fs-3">10-50</span>
													</label>
													<!--end::Option-->
												</div>
												<!--end::Col-->
												<!--begin::Col-->
												<div class="col">
													<!--begin::Option-->
													<label
														class="btn btn-outline btn-outline-dashed btn-active-light-primary w-100 p-4">
														<input type="radio" class="btn-check" name="account_team_size"
															value="50+" />
														<span class="fw-bold fs-3">50+</span>
													</label>
													<!--end::Option-->
												</div>
												<!--end::Col-->
											</div>
											<!--end::Row-->
											<!--begin::Hint-->
											<div class="form-text">Customers will see this shortened version of your
												statement descriptor</div>
											<!--end::Hint-->
										</div>
										<!--end::Input group-->
										<!--begin::Input group-->
										<div class="mb-10 fv-row">
											<!--begin::Label-->
											<label class="form-label mb-3">Team Account Name</label>
											<!--end::Label-->
											<!--begin::Input-->
											<input type="text" class="form-control form-control-lg form-control-solid"
												name="account_name" placeholder="" value="" />
											<!--end::Input-->
										</div>
										<!--end::Input group-->
										<!--begin::Input group-->
										<div class="mb-0 fv-row">
											<!--begin::Label-->
											<label class="d-flex align-items-center form-label mb-5">Select Account Plan
												<span class="ms-1" data-bs-toggle="tooltip"
													title="Monthly billing will be based on your account plan">
													<i class="ki-duotone ki-information-5 text-gray-500 fs-6">
														<span class="path1"></span>
														<span class="path2"></span>
														<span class="path3"></span>
													</i>
												</span></label>
											<!--end::Label-->
											<!--begin::Options-->
											<div class="mb-0">
												<!--begin:Option-->
												<label class="d-flex flex-stack mb-5 cursor-pointer">
													<!--begin:Label-->
													<span class="d-flex align-items-center me-2">
														<!--begin::Icon-->
														<span class="symbol symbol-50px me-6">
															<span class="symbol-label">
																<i class="ki-duotone ki-bank fs-1 text-gray-600">
																	<span class="path1"></span>
																	<span class="path2"></span>
																</i>
															</span>
														</span>
														<!--end::Icon-->
														<!--begin::Description-->
														<span class="d-flex flex-column">
															<span
																class="fw-bold text-gray-800 text-hover-primary fs-5">Company
																Account</span>
															<span class="fs-6 fw-semibold text-muted">Use images to enhance
																your post flow</span>
														</span>
														<!--end:Description-->
													</span>
													<!--end:Label-->
													<!--begin:Input-->
													<span class="form-check form-check-custom form-check-solid">
														<input class="form-check-input" type="radio" name="account_plan"
															value="1" />
													</span>
													<!--end:Input-->
												</label>
												<!--end::Option-->
												<!--begin:Option-->
												<label class="d-flex flex-stack mb-5 cursor-pointer">
													<!--begin:Label-->
													<span class="d-flex align-items-center me-2">
														<!--begin::Icon-->
														<span class="symbol symbol-50px me-6">
															<span class="symbol-label">
																<i class="ki-duotone ki-chart fs-1 text-gray-600">
																	<span class="path1"></span>
																	<span class="path2"></span>
																</i>
															</span>
														</span>
														<!--end::Icon-->
														<!--begin::Description-->
														<span class="d-flex flex-column">
															<span
																class="fw-bold text-gray-800 text-hover-primary fs-5">Developer
																Account</span>
															<span class="fs-6 fw-semibold text-muted">Use images to your
																post time</span>
														</span>
														<!--end:Description-->
													</span>
													<!--end:Label-->
													<!--begin:Input-->
													<span class="form-check form-check-custom form-check-solid">
														<input class="form-check-input" type="radio" checked="checked"
															name="account_plan" value="2" />
													</span>
													<!--end:Input-->
												</label>
												<!--end::Option-->
												<!--begin:Option-->
												<label class="d-flex flex-stack mb-0 cursor-pointer">
													<!--begin:Label-->
													<span class="d-flex align-items-center me-2">
														<!--begin::Icon-->
														<span class="symbol symbol-50px me-6">
															<span class="symbol-label">
																<i class="ki-duotone ki-chart-pie-4 fs-1 text-gray-600">
																	<span class="path1"></span>
																	<span class="path2"></span>
																	<span class="path3"></span>
																</i>
															</span>
														</span>
														<!--end::Icon-->
														<!--begin::Description-->
														<span class="d-flex flex-column">
															<span
																class="fw-bold text-gray-800 text-hover-primary fs-5">Testing
																Account</span>
															<span class="fs-6 fw-semibold text-muted">Use images to enhance
																time travel rivers</span>
														</span>
														<!--end:Description-->
													</span>
													<!--end:Label-->
													<!--begin:Input-->
													<span class="form-check form-check-custom form-check-solid">
														<input class="form-check-input" type="radio" name="account_plan"
															value="3" />
													</span>
													<!--end:Input-->
												</label>
												<!--end::Option-->
											</div>
											<!--end::Options-->
										</div>
										<!--end::Input group-->
									</div>
									<!--end::Wrapper-->
								</div>
								<!--end::Step 2-->
								<!--begin::Step 3-->
								<div data-kt-stepper-element="content">
									<!--begin::Wrapper-->
									<div class="w-100">
										<!--begin::Heading-->
										<div class="pb-10 pb-lg-12">
											<!--begin::Title-->
											<h2 class="fw-bold text-gray-900">Business Details</h2>
											<!--end::Title-->
											<!--begin::Notice-->
											<div class="text-muted fw-semibold fs-6">If you need more info, please check out
												<a href="#" class="link-primary fw-bold">Help Page</a>.
											</div>
											<!--end::Notice-->
										</div>
										<!--end::Heading-->
										<!--begin::Input group-->
										<div class="fv-row mb-10">
											<!--begin::Label-->
											<label class="form-label required">Business Name</label>
											<!--end::Label-->
											<!--begin::Input-->
											<input name="business_name"
												class="form-control form-control-lg form-control-solid"
												value="Keenthemes Inc." />
											<!--end::Input-->
										</div>
										<!--end::Input group-->
										<!--begin::Input group-->
										<div class="fv-row mb-10">
											<!--begin::Label-->
											<label class="d-flex align-items-center form-label">
												<span class="required">Shortened Descriptor</span>
												<span class="lh-1 ms-1" data-bs-toggle="popover" data-bs-trigger="hover"
													data-bs-html="true"
													data-bs-content="&lt;div class=&#039;p-4 rounded bg-light&#039;&gt; &lt;div class=&#039;d-flex flex-stack text-muted mb-4&#039;&gt; &lt;i class=&quot;ki-duotone ki-bank fs-3 me-3&quot;&gt;&lt;span class=&quot;path1&quot;&gt;&lt;/span&gt;&lt;span class=&quot;path2&quot;&gt;&lt;/span&gt;&lt;/i&gt; &lt;div class=&#039;fw-bold&#039;&gt;INCBANK **** 1245 STATEMENT&lt;/div&gt; &lt;/div&gt; &lt;div class=&#039;d-flex flex-stack fw-semibold text-gray-600&#039;&gt; &lt;div&gt;Amount&lt;/div&gt; &lt;div&gt;Transaction&lt;/div&gt; &lt;/div&gt; &lt;div class=&#039;separator separator-dashed my-2&#039;&gt;&lt;/div&gt; &lt;div class=&#039;d-flex flex-stack text-gray-900 fw-bold mb-2&#039;&gt; &lt;div&gt;USD345.00&lt;/div&gt; &lt;div&gt;KEENTHEMES*&lt;/div&gt; &lt;/div&gt; &lt;div class=&#039;d-flex flex-stack text-muted mb-2&#039;&gt; &lt;div&gt;USD75.00&lt;/div&gt; &lt;div&gt;Hosting fee&lt;/div&gt; &lt;/div&gt; &lt;div class=&#039;d-flex flex-stack text-muted&#039;&gt; &lt;div&gt;USD3,950.00&lt;/div&gt; &lt;div&gt;Payrol&lt;/div&gt; &lt;/div&gt; &lt;/div&gt;">
													<i class="ki-duotone ki-information-5 text-gray-500 fs-6">
														<span class="path1"></span>
														<span class="path2"></span>
														<span class="path3"></span>
													</i>
												</span>
											</label>
											<!--end::Label-->
											<!--begin::Input-->
											<input name="business_descriptor"
												class="form-control form-control-lg form-control-solid"
												value="KEENTHEMES" />
											<!--end::Input-->
											<!--begin::Hint-->
											<div class="form-text">Customers will see this shortened version of your
												statement descriptor</div>
											<!--end::Hint-->
										</div>
										<!--end::Input group-->
										<!--begin::Input group-->
										<div class="fv-row mb-10">
											<!--begin::Label-->
											<label class="form-label required">Corporation Type</label>
											<!--end::Label-->
											<!--begin::Input-->
											<select name="business_type"
												class="form-select form-select-lg form-select-solid" data-control="select2"
												data-placeholder="Select..." data-allow-clear="true"
												data-hide-search="true">
												<option></option>
												<option value="1">S Corporation</option>
												<option value="1">C Corporation</option>
												<option value="2">Sole Proprietorship</option>
												<option value="3">Non-profit</option>
												<option value="4">Limited Liability</option>
												<option value="5">General Partnership</option>
											</select>
											<!--end::Input-->
										</div>
										<!--end::Input group-->
										<!--begin::Input group-->
										<div class="fv-row mb-10">
											<!--end::Label-->
											<label class="form-label">Business Description</label>
											<!--end::Label-->
											<!--begin::Input-->
											<textarea name="business_description"
												class="form-control form-control-lg form-control-solid" rows="3"></textarea>
											<!--end::Input-->
										</div>
										<!--end::Input group-->
										<!--begin::Input group-->
										<div class="fv-row mb-0">
											<!--begin::Label-->
											<label class="fs-6 fw-semibold form-label required">Contact Email</label>
											<!--end::Label-->
											<!--begin::Input-->
											<input name="business_email"
												class="form-control form-control-lg form-control-solid"
												value="corp@support.com" />
											<!--end::Input-->
										</div>
										<!--end::Input group-->
									</div>
									<!--end::Wrapper-->
								</div>
								<!--end::Step 3-->
								<!--begin::Step 4-->
								<div data-kt-stepper-element="content">
									<!--begin::Wrapper-->
									<div class="w-100">
										<!--begin::Heading-->
										<div class="pb-10 pb-lg-15">
											<!--begin::Title-->
											<h2 class="fw-bold text-gray-900">Billing Details</h2>
											<!--end::Title-->
											<!--begin::Notice-->
											<div class="text-muted fw-semibold fs-6">If you need more info, please check out
												<a href="#" class="text-primary fw-bold">Help Page</a>.
											</div>
											<!--end::Notice-->
										</div>
										<!--end::Heading-->
										<!--begin::Input group-->
										<div class="d-flex flex-column mb-7 fv-row">
											<!--begin::Label-->
											<label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
												<span class="required">Name On Card</span>
												<span class="ms-1" data-bs-toggle="tooltip"
													title="Specify a card holder's name">
													<i class="ki-duotone ki-information-5 text-gray-500 fs-6">
														<span class="path1"></span>
														<span class="path2"></span>
														<span class="path3"></span>
													</i>
												</span>
											</label>
											<!--end::Label-->
											<input type="text" class="form-control form-control-solid" placeholder=""
												name="card_name" value="Max Doe" />
										</div>
										<!--end::Input group-->
										<!--begin::Input group-->
										<div class="d-flex flex-column mb-7 fv-row">
											<!--begin::Label-->
											<label class="required fs-6 fw-semibold form-label mb-2">Card Number</label>
											<!--end::Label-->
											<!--begin::Input wrapper-->
											<div class="position-relative">
												<!--begin::Input-->
												<input type="text" class="form-control form-control-solid"
													placeholder="Enter card number" name="card_number"
													value="4111 1111 1111 1111" />
												<!--end::Input-->
												<!--begin::Card logos-->
												<div class="position-absolute translate-middle-y top-50 end-0 me-5">
													<img src="assets/media/svg/card-logos/visa.svg" alt="" class="h-25px" />
													<img src="assets/media/svg/card-logos/mastercard.svg" alt=""
														class="h-25px" />
													<img src="assets/media/svg/card-logos/american-express.svg" alt=""
														class="h-25px" />
												</div>
												<!--end::Card logos-->
											</div>
											<!--end::Input wrapper-->
										</div>
										<!--end::Input group-->
										<!--begin::Input group-->
										<div class="row mb-10">
											<!--begin::Col-->
											<div class="col-md-8 fv-row">
												<!--begin::Label-->
												<label class="required fs-6 fw-semibold form-label mb-2">Expiration
													Date</label>
												<!--end::Label-->
												<!--begin::Row-->
												<div class="row fv-row">
													<!--begin::Col-->
													<div class="col-6">
														<select name="card_expiry_month"
															class="form-select form-select-solid" data-control="select2"
															data-hide-search="true" data-placeholder="Month">
															<option></option>
															<option value="1">1</option>
															<option value="2">2</option>
															<option value="3">3</option>
															<option value="4">4</option>
															<option value="5">5</option>
															<option value="6">6</option>
															<option value="7">7</option>
															<option value="8">8</option>
															<option value="9">9</option>
															<option value="10">10</option>
															<option value="11">11</option>
															<option value="12">12</option>
														</select>
													</div>
													<!--end::Col-->
													<!--begin::Col-->
													<div class="col-6">
														<select name="card_expiry_year"
															class="form-select form-select-solid" data-control="select2"
															data-hide-search="true" data-placeholder="Year">
															<option></option>
															<option value="2024">2024</option>
															<option value="2025">2025</option>
															<option value="2026">2026</option>
															<option value="2027">2027</option>
															<option value="2028">2028</option>
															<option value="2029">2029</option>
															<option value="2030">2030</option>
															<option value="2031">2031</option>
															<option value="2032">2032</option>
															<option value="2033">2033</option>
															<option value="2034">2034</option>
														</select>
													</div>
													<!--end::Col-->
												</div>
												<!--end::Row-->
											</div>
											<!--end::Col-->
											<!--begin::Col-->
											<div class="col-md-4 fv-row">
												<!--begin::Label-->
												<label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
													<span class="required">CVV</span>
													<span class="ms-1" data-bs-toggle="tooltip"
														title="Enter a card CVV code">
														<i class="ki-duotone ki-information-5 text-gray-500 fs-6">
															<span class="path1"></span>
															<span class="path2"></span>
															<span class="path3"></span>
														</i>
													</span>
												</label>
												<!--end::Label-->
												<!--begin::Input wrapper-->
												<div class="position-relative">
													<!--begin::Input-->
													<input type="text" class="form-control form-control-solid" minlength="3"
														maxlength="4" placeholder="CVV" name="card_cvv" />
													<!--end::Input-->
													<!--begin::CVV icon-->
													<div class="position-absolute translate-middle-y top-50 end-0 me-3">
														<i class="ki-duotone ki-credit-cart fs-2hx">
															<span class="path1"></span>
															<span class="path2"></span>
														</i>
													</div>
													<!--end::CVV icon-->
												</div>
												<!--end::Input wrapper-->
											</div>
											<!--end::Col-->
										</div>
										<!--end::Input group-->
										<!--begin::Input group-->
										<div class="d-flex flex-stack">
											<!--begin::Label-->
											<div class="me-5">
												<label class="fs-6 fw-semibold form-label">Save Card for further
													billing?</label>
												<div class="fs-7 fw-semibold text-muted">If you need more info, please check
													budget planning</div>
											</div>
											<!--end::Label-->
											<!--begin::Switch-->
											<label class="form-check form-switch form-check-custom form-check-solid">
												<input class="form-check-input" type="checkbox" value="1"
													checked="checked" />
												<span class="form-check-label fw-semibold text-muted">Save Card</span>
											</label>
											<!--end::Switch-->
										</div>
										<!--end::Input group-->
									</div>
									<!--end::Wrapper-->
								</div>
								<!--end::Step 4-->
								<!--begin::Step 5-->
								<div data-kt-stepper-element="content">
									<!--begin::Wrapper-->
									<div class="w-100">
										<!--begin::Heading-->
										<div class="pb-8 pb-lg-10">
											<!--begin::Title-->
											<h2 class="fw-bold text-gray-900">Your Are Done!</h2>
											<!--end::Title-->
											<!--begin::Notice-->
											<div class="text-muted fw-semibold fs-6">If you need more info, please
												<a href="#" class="link-primary fw-bold">Sign In</a>.
											</div>
											<!--end::Notice-->
										</div>
										<!--end::Heading-->
										<!--begin::Body-->
										<div class="mb-0">
											<!--begin::Text-->
											<div class="fs-6 text-gray-600 mb-5">Writing headlines for blog posts is as much
												an art as it is a science and probably warrants its own post, but for all
												advise is with what works for your great & amazing audience.</div>
											<!--end::Text-->
											<!--begin::Alert-->
											<!--begin::Notice-->
											<div
												class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-6">
												<!--begin::Icon-->
												<i class="ki-duotone ki-information fs-2tx text-warning me-4">
													<span class="path1"></span>
													<span class="path2"></span>
													<span class="path3"></span>
												</i>
												<!--end::Icon-->
												<!--begin::Wrapper-->
												<div class="d-flex flex-stack flex-grow-1">
													<!--begin::Content-->
													<div class="fw-semibold">
														<h4 class="text-gray-900 fw-bold">We need your attention!</h4>
														<div class="fs-6 text-gray-700">To start using great tools, please,
															<a href="utilities/wizards/vertical.html" class="fw-bold">Create
																Team Platform</a>
														</div>
													</div>
													<!--end::Content-->
												</div>
												<!--end::Wrapper-->
											</div>
											<!--end::Notice-->
											<!--end::Alert-->
										</div>
										<!--end::Body-->
									</div>
									<!--end::Wrapper-->
								</div>
								<!--end::Step 5-->
								<!--begin::Actions-->
								<div class="d-flex flex-stack pt-15">
									<!--begin::Wrapper-->
									<div class="mr-2">
										<button type="button" class="btn btn-lg btn-light-primary me-3"
											data-kt-stepper-action="previous" data-kt-stepper-state="hide-on-last-step">
											<i class="ki-duotone ki-arrow-left fs-4 me-1">
												<span class="path1"></span>
												<span class="path2"></span>
											</i>Back</button>
									</div>
									<!--end::Wrapper-->
									<!--begin::Wrapper-->
									<div>
										<button type="button" class="btn btn-lg btn-primary me-3"
											data-kt-stepper-action="submit">
											<span class="indicator-label">Submit
												<i class="ki-duotone ki-arrow-right fs-3 ms-2 me-0">
													<span class="path1"></span>
													<span class="path2"></span>
												</i></span>
											<span class="indicator-progress">Please wait...
												<span
													class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
										</button>
										<button type="button" class="btn btn-lg btn-primary"
											data-kt-stepper-action="next">Continue
											<i class="ki-duotone ki-arrow-right fs-4 ms-1 me-0">
												<span class="path1"></span>
												<span class="path2"></span>
											</i></button>
									</div>
									<!--end::Wrapper-->
								</div>
								<!--end::Actions-->
							</form>
							<!--end::Form-->
						</div>
						<!--end::Stepper-->
					</div>
					<!--end::Modal body-->
				</div>
				<!--end::Modal content-->
			</div>
			<!--end::Modal dialog-->
		</div>
		<!--end::Modal - Create project-->
		<!--begin::Modal - Offer A Deal-->
		<div class="modal fade" id="kt_modal_offer_a_deal" tabindex="-1" aria-hidden="true">
			<!--begin::Modal dialog-->
			<div class="modal-dialog modal-dialog-centered mw-1000px">
				<!--begin::Modal content-->
				<div class="modal-content">
					<!--begin::Modal header-->
					<div class="modal-header py-7 d-flex justify-content-between">
						<!--begin::Modal title-->
						<h2>Offer a Deal</h2>
						<!--end::Modal title-->
						<!--begin::Close-->
						<div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
							<i class="ki-duotone ki-cross fs-1">
								<span class="path1"></span>
								<span class="path2"></span>
							</i>
						</div>
						<!--end::Close-->
					</div>
					<!--begin::Modal header-->
					<!--begin::Modal body-->
					<div class="modal-body scroll-y m-5">
						<!--begin::Stepper-->
						<div class="stepper stepper-links d-flex flex-column" id="kt_modal_offer_a_deal_stepper">
							<!--begin::Nav-->
							<div class="stepper-nav justify-content-center py-2">
								<!--begin::Step 1-->
								<div class="stepper-item me-5 me-md-15 current" data-kt-stepper-element="nav">
									<h3 class="stepper-title">Deal Type</h3>
								</div>
								<!--end::Step 1-->
								<!--begin::Step 2-->
								<div class="stepper-item me-5 me-md-15" data-kt-stepper-element="nav">
									<h3 class="stepper-title">Deal Details</h3>
								</div>
								<!--end::Step 2-->
								<!--begin::Step 3-->
								<div class="stepper-item me-5 me-md-15" data-kt-stepper-element="nav">
									<h3 class="stepper-title">Finance Settings</h3>
								</div>
								<!--end::Step 3-->
								<!--begin::Step 4-->
								<div class="stepper-item" data-kt-stepper-element="nav">
									<h3 class="stepper-title">Completed</h3>
								</div>
								<!--end::Step 4-->
							</div>
							<!--end::Nav-->
							<!--begin::Form-->
							<form class="mx-auto mw-500px w-100 pt-15 pb-10" novalidate="novalidate"
								id="kt_modal_offer_a_deal_form">
								<!--begin::Type-->
								<div class="current" data-kt-stepper-element="content">
									<!--begin::Wrapper-->
									<div class="w-100">
										<!--begin::Heading-->
										<div class="mb-13">
											<!--begin::Title-->
											<h2 class="mb-3">Deal Type</h2>
											<!--end::Title-->
											<!--begin::Description-->
											<div class="text-muted fw-semibold fs-5">If you need more info, please check out
												<a href="#" class="link-primary fw-bold">FAQ Page</a>.
											</div>
											<!--end::Description-->
										</div>
										<!--end::Heading-->
										<!--begin::Input group-->
										<div class="fv-row mb-15" data-kt-buttons="true">
											<!--begin::Option-->
											<label
												class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex text-start p-6 mb-6 active">
												<!--begin::Input-->
												<input class="btn-check" type="radio" checked="checked" name="offer_type"
													value="1" />
												<!--end::Input-->
												<!--begin::Label-->
												<span class="d-flex">
													<!--begin::Icon-->
													<i class="ki-duotone ki-profile-circle fs-3hx">
														<span class="path1"></span>
														<span class="path2"></span>
														<span class="path3"></span>
													</i>
													<!--end::Icon-->
													<!--begin::Info-->
													<span class="ms-4">
														<span class="fs-3 fw-bold text-gray-900 mb-2 d-block">Personal
															Deal</span>
														<span class="fw-semibold fs-4 text-muted">If you need more info,
															please check it out</span>
													</span>
													<!--end::Info-->
												</span>
												<!--end::Label-->
											</label>
											<!--end::Option-->
											<!--begin::Option-->
											<label
												class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex text-start p-6">
												<!--begin::Input-->
												<input class="btn-check" type="radio" name="offer_type" value="2" />
												<!--end::Input-->
												<!--begin::Label-->
												<span class="d-flex">
													<!--begin::Icon-->
													<i class="ki-duotone ki-element-11 fs-3hx">
														<span class="path1"></span>
														<span class="path2"></span>
														<span class="path3"></span>
														<span class="path4"></span>
													</i>
													<!--end::Icon-->
													<!--begin::Info-->
													<span class="ms-4">
														<span class="fs-3 fw-bold text-gray-900 mb-2 d-block">Corporate
															Deal</span>
														<span class="fw-semibold fs-4 text-muted">Create corporate account
															to manage users</span>
													</span>
													<!--end::Info-->
												</span>
												<!--end::Label-->
											</label>
											<!--end::Option-->
										</div>
										<!--end::Input group-->
										<!--begin::Actions-->
										<div class="d-flex justify-content-end">
											<button type="button" class="btn btn-lg btn-primary"
												data-kt-element="type-next">
												<span class="indicator-label">Offer Details</span>
												<span class="indicator-progress">Please wait...
													<span
														class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
											</button>
										</div>
										<!--end::Actions-->
									</div>
									<!--end::Wrapper-->
								</div>
								<!--end::Type-->
								<!--begin::Details-->
								<div data-kt-stepper-element="content">
									<!--begin::Wrapper-->
									<div class="w-100">
										<!--begin::Heading-->
										<div class="mb-13">
											<!--begin::Title-->
											<h2 class="mb-3">Deal Details</h2>
											<!--end::Title-->
											<!--begin::Description-->
											<div class="text-muted fw-semibold fs-5">If you need more info, please check out
												<a href="#" class="link-primary fw-bold">FAQ Page</a>.
											</div>
											<!--end::Description-->
										</div>
										<!--end::Heading-->
										<!--begin::Input group-->
										<div class="fv-row mb-8">
											<!--begin::Label-->
											<label class="required fs-6 fw-semibold mb-2">Customer</label>
											<!--end::Label-->
											<!--begin::Input-->
											<select class="form-select form-select-solid" data-control="select2"
												data-placeholder="Select an option" name="details_customer">
												<option></option>
												<option value="1" selected="selected">Keenthemes</option>
												<option value="2">CRM App</option>
											</select>
											<!--end::Input-->
										</div>
										<!--end::Input group-->
										<!--begin::Input group-->
										<div class="fv-row mb-8">
											<!--begin::Label-->
											<label class="required fs-6 fw-semibold mb-2">Deal Title</label>
											<!--end::Label-->
											<!--begin::Input-->
											<input type="text" class="form-control form-control-solid"
												placeholder="Enter Deal Title" name="details_title"
												value="Marketing Campaign" />
											<!--end::Input-->
										</div>
										<!--end::Input group-->
										<!--begin::Input group-->
										<div class="fv-row mb-8">
											<!--begin::Label-->
											<label class="fs-6 fw-semibold mb-2">Deal Description</label>
											<!--end::Label-->
											<!--begin::Label-->
											<textarea class="form-control form-control-solid" rows="3"
												placeholder="Enter Deal Description"
												name="details_description">Experience share market at your fingertips with TICK PRO stock investment mobile trading app</textarea>
											<!--end::Label-->
										</div>
										<!--end::Input group-->
										<!--begin::Input group-->
										<div class="fv-row mb-8">
											<label class="required fs-6 fw-semibold mb-2">Activation Date</label>
											<div class="position-relative d-flex align-items-center">
												<!--begin::Icon-->
												<i class="ki-duotone ki-calendar-8 fs-2 position-absolute mx-4">
													<span class="path1"></span>
													<span class="path2"></span>
													<span class="path3"></span>
													<span class="path4"></span>
													<span class="path5"></span>
													<span class="path6"></span>
												</i>
												<!--end::Icon-->
												<!--begin::Datepicker-->
												<input class="form-control form-control-solid ps-12"
													placeholder="Pick date range" name="details_activation_date" />
												<!--end::Datepicker-->
											</div>
										</div>
										<!--end::Input group-->
										<!--begin::Input group-->
										<div class="fv-row mb-15">
											<!--begin::Wrapper-->
											<div class="d-flex flex-stack">
												<!--begin::Label-->
												<div class="me-5">
													<label class="required fs-6 fw-semibold">Notifications</label>
													<div class="fs-7 fw-semibold text-muted">Allow Notifications by Phone or
														Email</div>
												</div>
												<!--end::Label-->
												<!--begin::Checkboxes-->
												<div class="d-flex">
													<!--begin::Checkbox-->
													<label class="form-check form-check-custom form-check-solid me-10">
														<!--begin::Input-->
														<input class="form-check-input h-20px w-20px" type="checkbox"
															value="email" name="details_notifications[]" />
														<!--end::Input-->
														<!--begin::Label-->
														<span class="form-check-label fw-semibold">Email</span>
														<!--end::Label-->
													</label>
													<!--end::Checkbox-->
													<!--begin::Checkbox-->
													<label class="form-check form-check-custom form-check-solid">
														<!--begin::Input-->
														<input class="form-check-input h-20px w-20px" type="checkbox"
															value="phone" checked="checked"
															name="details_notifications[]" />
														<!--end::Input-->
														<!--begin::Label-->
														<span class="form-check-label fw-semibold">Phone</span>
														<!--end::Label-->
													</label>
													<!--end::Checkbox-->
												</div>
												<!--end::Checkboxes-->
											</div>
											<!--begin::Wrapper-->
										</div>
										<!--end::Input group-->
										<!--begin::Actions-->
										<div class="d-flex flex-stack">
											<button type="button" class="btn btn-lg btn-light me-3"
												data-kt-element="details-previous">Deal Type</button>
											<button type="button" class="btn btn-lg btn-primary"
												data-kt-element="details-next">
												<span class="indicator-label">Financing</span>
												<span class="indicator-progress">Please wait...
													<span
														class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
											</button>
										</div>
										<!--end::Actions-->
									</div>
									<!--end::Wrapper-->
								</div>
								<!--end::Details-->
								<!--begin::Budget-->
								<div data-kt-stepper-element="content">
									<!--begin::Wrapper-->
									<div class="w-100">
										<!--begin::Heading-->
										<div class="mb-13">
											<!--begin::Title-->
											<h2 class="mb-3">Finance</h2>
											<!--end::Title-->
											<!--begin::Description-->
											<div class="text-muted fw-semibold fs-5">If you need more info, please check out
												<a href="#" class="link-primary fw-bold">FAQ Page</a>.
											</div>
											<!--end::Description-->
										</div>
										<!--end::Heading-->
										<!--begin::Input group-->
										<div class="fv-row mb-8">
											<!--begin::Label-->
											<label class="d-flex align-items-center fs-6 fw-semibold mb-2">
												<span class="required">Setup Budget</span>
												<span class="lh-1 ms-1" data-bs-toggle="popover" data-bs-trigger="hover"
													data-bs-html="true"
													data-bs-content="&lt;div class=&#039;p-4 rounded bg-light&#039;&gt; &lt;div class=&#039;d-flex flex-stack text-muted mb-4&#039;&gt; &lt;i class=&quot;ki-duotone ki-bank fs-3 me-3&quot;&gt;&lt;span class=&quot;path1&quot;&gt;&lt;/span&gt;&lt;span class=&quot;path2&quot;&gt;&lt;/span&gt;&lt;/i&gt; &lt;div class=&#039;fw-bold&#039;&gt;INCBANK **** 1245 STATEMENT&lt;/div&gt; &lt;/div&gt; &lt;div class=&#039;d-flex flex-stack fw-semibold text-gray-600&#039;&gt; &lt;div&gt;Amount&lt;/div&gt; &lt;div&gt;Transaction&lt;/div&gt; &lt;/div&gt; &lt;div class=&#039;separator separator-dashed my-2&#039;&gt;&lt;/div&gt; &lt;div class=&#039;d-flex flex-stack text-gray-900 fw-bold mb-2&#039;&gt; &lt;div&gt;USD345.00&lt;/div&gt; &lt;div&gt;KEENTHEMES*&lt;/div&gt; &lt;/div&gt; &lt;div class=&#039;d-flex flex-stack text-muted mb-2&#039;&gt; &lt;div&gt;USD75.00&lt;/div&gt; &lt;div&gt;Hosting fee&lt;/div&gt; &lt;/div&gt; &lt;div class=&#039;d-flex flex-stack text-muted&#039;&gt; &lt;div&gt;USD3,950.00&lt;/div&gt; &lt;div&gt;Payrol&lt;/div&gt; &lt;/div&gt; &lt;/div&gt;">
													<i class="ki-duotone ki-information-5 text-gray-500 fs-6">
														<span class="path1"></span>
														<span class="path2"></span>
														<span class="path3"></span>
													</i>
												</span>
											</label>
											<!--end::Label-->
											<!--begin::Dialer-->
											<div class="position-relative w-lg-250px" id="kt_modal_finance_setup"
												data-kt-dialer="true" data-kt-dialer-min="50" data-kt-dialer-max="50000"
												data-kt-dialer-step="100" data-kt-dialer-prefix="$"
												data-kt-dialer-decimals="2">
												<!--begin::Decrease control-->
												<button type="button"
													class="btn btn-icon btn-active-color-gray-700 position-absolute translate-middle-y top-50 start-0"
													data-kt-dialer-control="decrease">
													<i class="ki-duotone ki-minus-circle fs-1">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</button>
												<!--end::Decrease control-->
												<!--begin::Input control-->
												<input type="text" class="form-control form-control-solid border-0 ps-12"
													data-kt-dialer-control="input" placeholder="Amount" name="finance_setup"
													readonly="readonly" value="$50" />
												<!--end::Input control-->
												<!--begin::Increase control-->
												<button type="button"
													class="btn btn-icon btn-active-color-gray-700 position-absolute translate-middle-y top-50 end-0"
													data-kt-dialer-control="increase">
													<i class="ki-duotone ki-plus-circle fs-1">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</button>
												<!--end::Increase control-->
											</div>
											<!--end::Dialer-->
										</div>
										<!--end::Input group-->
										<!--begin::Input group-->
										<div class="fv-row mb-8">
											<!--begin::Label-->
											<label class="fs-6 fw-semibold mb-2">Budget Usage</label>
											<!--end::Label-->
											<!--begin::Row-->
											<div class="row g-9" data-kt-buttons="true"
												data-kt-buttons-target="[data-kt-button='true']">
												<!--begin::Col-->
												<div class="col-md-6 col-lg-12 col-xxl-6">
													<!--begin::Option-->
													<label
														class="btn btn-outline btn-outline-dashed btn-active-light-primary active d-flex text-start p-6"
														data-kt-button="true">
														<!--begin::Radio-->
														<span
															class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
															<input class="form-check-input" type="radio"
																name="finance_usage" value="1" checked="checked" />
														</span>
														<!--end::Radio-->
														<!--begin::Info-->
														<span class="ms-5">
															<span class="fs-4 fw-bold text-gray-800 mb-2 d-block">Precise
																Usage</span>
															<span class="fw-semibold fs-7 text-gray-600">Withdraw money to
																your bank account per transaction under $50,000
																budget</span>
														</span>
														<!--end::Info-->
													</label>
													<!--end::Option-->
												</div>
												<!--end::Col-->
												<!--begin::Col-->
												<div class="col-md-6 col-lg-12 col-xxl-6">
													<!--begin::Option-->
													<label
														class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex text-start p-6"
														data-kt-button="true">
														<!--begin::Radio-->
														<span
															class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
															<input class="form-check-input" type="radio"
																name="finance_usage" value="2" />
														</span>
														<!--end::Radio-->
														<!--begin::Info-->
														<span class="ms-5">
															<span class="fs-4 fw-bold text-gray-800 mb-2 d-block">Extreme
																Usage</span>
															<span class="fw-semibold fs-7 text-gray-600">Withdraw money to
																your bank account per transaction under $50,000
																budget</span>
														</span>
														<!--end::Info-->
													</label>
													<!--end::Option-->
												</div>
												<!--end::Col-->
											</div>
											<!--end::Row-->
										</div>
										<!--end::Input group-->
										<!--begin::Input group-->
										<div class="fv-row mb-15">
											<!--begin::Wrapper-->
											<div class="d-flex flex-stack">
												<!--begin::Label-->
												<div class="me-5">
													<label class="fs-6 fw-semibold">Allow Changes in Budget</label>
													<div class="fs-7 fw-semibold text-muted">If you need more info, please
														check budget planning</div>
												</div>
												<!--end::Label-->
												<!--begin::Switch-->
												<label class="form-check form-switch form-check-custom form-check-solid">
													<input class="form-check-input" type="checkbox" value="1"
														name="finance_allow" checked="checked" />
													<span class="form-check-label fw-semibold text-muted">Allowed</span>
												</label>
												<!--end::Switch-->
											</div>
											<!--end::Wrapper-->
										</div>
										<!--end::Input group-->
										<!--begin::Actions-->
										<div class="d-flex flex-stack">
											<button type="button" class="btn btn-lg btn-light me-3"
												data-kt-element="finance-previous">Project Settings</button>
											<button type="button" class="btn btn-lg btn-primary"
												data-kt-element="finance-next">
												<span class="indicator-label">Build Team</span>
												<span class="indicator-progress">Please wait...
													<span
														class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
											</button>
										</div>
										<!--end::Actions-->
									</div>
									<!--end::Wrapper-->
								</div>
								<!--end::Budget-->
								<!--begin::Complete-->
								<div data-kt-stepper-element="content">
									<!--begin::Wrapper-->
									<div class="w-100">
										<!--begin::Heading-->
										<div class="mb-13">
											<!--begin::Title-->
											<h2 class="mb-3">Deal Created!</h2>
											<!--end::Title-->
											<!--begin::Description-->
											<div class="text-muted fw-semibold fs-5">If you need more info, please check out
												<a href="#" class="link-primary fw-bold">FAQ Page</a>.
											</div>
											<!--end::Description-->
										</div>
										<!--end::Heading-->
										<!--begin::Actions-->
										<div class="d-flex flex-center pb-20">
											<button type="button" class="btn btn-lg btn-light me-3"
												data-kt-element="complete-start">Create New Deal</button>
											<a href="#" class="btn btn-lg btn-primary" data-bs-toggle="tooltip"
												title="Coming Soon">View Deal</a>
										</div>
										<!--end::Actions-->
										<!--begin::Illustration-->
										<div class="text-center px-4">
											<img src="assets/media/illustrations/sketchy-1/20.png" alt=""
												class="mw-100 mh-300px" />
										</div>
										<!--end::Illustration-->
									</div>
								</div>
								<!--end::Complete-->
							</form>
							<!--end::Form-->
						</div>
						<!--end::Stepper-->
					</div>
					<!--begin::Modal body-->
				</div>
			</div>
		</div>
		<!--end::Modal - Offer A Deal-->
		<!--begin::Modal - Users Search-->
		<div class="modal fade" id="kt_modal_users_search" tabindex="-1" aria-hidden="true">
			<!--begin::Modal dialog-->
			<div class="modal-dialog modal-dialog-centered mw-650px">
				<!--begin::Modal content-->
				<div class="modal-content">
					<!--begin::Modal header-->
					<div class="modal-header pb-0 border-0 justify-content-end">
						<!--begin::Close-->
						<div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
							<i class="ki-duotone ki-cross fs-1">
								<span class="path1"></span>
								<span class="path2"></span>
							</i>
						</div>
						<!--end::Close-->
					</div>
					<!--begin::Modal header-->
					<!--begin::Modal body-->
					<div class="modal-body scroll-y mx-5 mx-xl-18 pt-0 pb-15">
						<!--begin::Content-->
						<div class="text-center mb-13">
							<h1 class="mb-3">Search Users</h1>
							<div class="text-muted fw-semibold fs-5">Invite Collaborators To Your Project</div>
						</div>
						<!--end::Content-->
						<!--begin::Search-->
						<div id="kt_modal_users_search_handler" data-kt-search-keypress="true" data-kt-search-min-length="2"
							data-kt-search-enter="enter" data-kt-search-layout="inline">
							<!--begin::Form-->
							<form data-kt-search-element="form" class="w-100 position-relative mb-5" autocomplete="off">
								<!--begin::Hidden input(Added to disable form autocomplete)-->
								<input type="hidden" />
								<!--end::Hidden input-->
								<!--begin::Icon-->
								<i
									class="ki-duotone ki-magnifier fs-2 fs-lg-1 text-gray-500 position-absolute top-50 ms-5 translate-middle-y">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
								<!--end::Icon-->
								<!--begin::Input-->
								<input type="text" class="form-control form-control-lg form-control-solid px-15"
									name="search" value="" placeholder="Search by username, full name or email..."
									data-kt-search-element="input" />
								<!--end::Input-->
								<!--begin::Spinner-->
								<span class="position-absolute top-50 end-0 translate-middle-y lh-0 d-none me-5"
									data-kt-search-element="spinner">
									<span class="spinner-border h-15px w-15px align-middle text-muted"></span>
								</span>
								<!--end::Spinner-->
								<!--begin::Reset-->
								<span
									class="btn btn-flush btn-active-color-primary position-absolute top-50 end-0 translate-middle-y lh-0 me-5 d-none"
									data-kt-search-element="clear">
									<i class="ki-duotone ki-cross fs-2 fs-lg-1 me-0">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
								</span>
								<!--end::Reset-->
							</form>
							<!--end::Form-->
							<!--begin::Wrapper-->
							<div class="py-5">
								<!--begin::Suggestions-->
								<div data-kt-search-element="suggestions">
									<!--begin::Heading-->
									<h3 class="fw-semibold mb-5">Recently searched:</h3>
									<!--end::Heading-->
									<!--begin::Users-->
									<div class="mh-375px scroll-y me-n7 pe-7">
										<!--begin::User-->
										<a href="#"
											class="d-flex align-items-center p-3 rounded bg-state-light bg-state-opacity-50 mb-1">
											<!--begin::Avatar-->
											<div class="symbol symbol-35px symbol-circle me-5">
												<img alt="Pic" src="assets/media/avatars/300-6.jpg" />
											</div>
											<!--end::Avatar-->
											<!--begin::Info-->
											<div class="fw-semibold">
												<span class="fs-6 text-gray-800 me-2">Emma Smith</span>
												<span class="badge badge-light">Art Director</span>
											</div>
											<!--end::Info-->
										</a>
										<!--end::User-->
										<!--begin::User-->
										<a href="#"
											class="d-flex align-items-center p-3 rounded bg-state-light bg-state-opacity-50 mb-1">
											<!--begin::Avatar-->
											<div class="symbol symbol-35px symbol-circle me-5">
												<span class="symbol-label bg-light-danger text-danger fw-semibold">M</span>
											</div>
											<!--end::Avatar-->
											<!--begin::Info-->
											<div class="fw-semibold">
												<span class="fs-6 text-gray-800 me-2">Melody Macy</span>
												<span class="badge badge-light">Marketing Analytic</span>
											</div>
											<!--end::Info-->
										</a>
										<!--end::User-->
										<!--begin::User-->
										<a href="#"
											class="d-flex align-items-center p-3 rounded bg-state-light bg-state-opacity-50 mb-1">
											<!--begin::Avatar-->
											<div class="symbol symbol-35px symbol-circle me-5">
												<img alt="Pic" src="assets/media/avatars/300-1.jpg" />
											</div>
											<!--end::Avatar-->
											<!--begin::Info-->
											<div class="fw-semibold">
												<span class="fs-6 text-gray-800 me-2">Max Smith</span>
												<span class="badge badge-light">Software Enginer</span>
											</div>
											<!--end::Info-->
										</a>
										<!--end::User-->
										<!--begin::User-->
										<a href="#"
											class="d-flex align-items-center p-3 rounded bg-state-light bg-state-opacity-50 mb-1">
											<!--begin::Avatar-->
											<div class="symbol symbol-35px symbol-circle me-5">
												<img alt="Pic" src="assets/media/avatars/300-5.jpg" />
											</div>
											<!--end::Avatar-->
											<!--begin::Info-->
											<div class="fw-semibold">
												<span class="fs-6 text-gray-800 me-2">Sean Bean</span>
												<span class="badge badge-light">Web Developer</span>
											</div>
											<!--end::Info-->
										</a>
										<!--end::User-->
										<!--begin::User-->
										<a href="#"
											class="d-flex align-items-center p-3 rounded bg-state-light bg-state-opacity-50 mb-1">
											<!--begin::Avatar-->
											<div class="symbol symbol-35px symbol-circle me-5">
												<img alt="Pic" src="assets/media/avatars/300-25.jpg" />
											</div>
											<!--end::Avatar-->
											<!--begin::Info-->
											<div class="fw-semibold">
												<span class="fs-6 text-gray-800 me-2">Brian Cox</span>
												<span class="badge badge-light">UI/UX Designer</span>
											</div>
											<!--end::Info-->
										</a>
										<!--end::User-->
									</div>
									<!--end::Users-->
								</div>
								<!--end::Suggestions-->
								<!--begin::Results(add d-none to below element to hide the users list by default)-->
								<div data-kt-search-element="results" class="d-none">
									<!--begin::Users-->
									<div class="mh-375px scroll-y me-n7 pe-7">
										<!--begin::User-->
										<div class="rounded d-flex flex-stack bg-active-lighten p-4" data-user-id="0">
											<!--begin::Details-->
											<div class="d-flex align-items-center">
												<!--begin::Checkbox-->
												<label class="form-check form-check-custom form-check-solid me-5">
													<input class="form-check-input" type="checkbox" name="users"
														data-kt-check="true" data-kt-check-target="[data-user-id='0']"
														value="0" />
												</label>
												<!--end::Checkbox-->
												<!--begin::Avatar-->
												<div class="symbol symbol-35px symbol-circle">
													<img alt="Pic" src="assets/media/avatars/300-6.jpg" />
												</div>
												<!--end::Avatar-->
												<!--begin::Details-->
												<div class="ms-5">
													<a href="#"
														class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Emma
														Smith</a>
													<div class="fw-semibold text-muted">smith@kpmg.com</div>
												</div>
												<!--end::Details-->
											</div>
											<!--end::Details-->
											<!--begin::Access menu-->
											<div class="ms-2 w-100px">
												<select class="form-select form-select-solid form-select-sm"
													data-control="select2" data-hide-search="true">
													<option value="1">Guest</option>
													<option value="2" selected="selected">Owner</option>
													<option value="3">Can Edit</option>
												</select>
											</div>
											<!--end::Access menu-->
										</div>
										<!--end::User-->
										<!--begin::Separator-->
										<div class="border-bottom border-gray-300 border-bottom-dashed"></div>
										<!--end::Separator-->
										<!--begin::User-->
										<div class="rounded d-flex flex-stack bg-active-lighten p-4" data-user-id="1">
											<!--begin::Details-->
											<div class="d-flex align-items-center">
												<!--begin::Checkbox-->
												<label class="form-check form-check-custom form-check-solid me-5">
													<input class="form-check-input" type="checkbox" name="users"
														data-kt-check="true" data-kt-check-target="[data-user-id='1']"
														value="1" />
												</label>
												<!--end::Checkbox-->
												<!--begin::Avatar-->
												<div class="symbol symbol-35px symbol-circle">
													<span
														class="symbol-label bg-light-danger text-danger fw-semibold">M</span>
												</div>
												<!--end::Avatar-->
												<!--begin::Details-->
												<div class="ms-5">
													<a href="#"
														class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Melody
														Macy</a>
													<div class="fw-semibold text-muted">melody@altbox.com</div>
												</div>
												<!--end::Details-->
											</div>
											<!--end::Details-->
											<!--begin::Access menu-->
											<div class="ms-2 w-100px">
												<select class="form-select form-select-solid form-select-sm"
													data-control="select2" data-hide-search="true">
													<option value="1" selected="selected">Guest</option>
													<option value="2">Owner</option>
													<option value="3">Can Edit</option>
												</select>
											</div>
											<!--end::Access menu-->
										</div>
										<!--end::User-->
										<!--begin::Separator-->
										<div class="border-bottom border-gray-300 border-bottom-dashed"></div>
										<!--end::Separator-->
										<!--begin::User-->
										<div class="rounded d-flex flex-stack bg-active-lighten p-4" data-user-id="2">
											<!--begin::Details-->
											<div class="d-flex align-items-center">
												<!--begin::Checkbox-->
												<label class="form-check form-check-custom form-check-solid me-5">
													<input class="form-check-input" type="checkbox" name="users"
														data-kt-check="true" data-kt-check-target="[data-user-id='2']"
														value="2" />
												</label>
												<!--end::Checkbox-->
												<!--begin::Avatar-->
												<div class="symbol symbol-35px symbol-circle">
													<img alt="Pic" src="assets/media/avatars/300-1.jpg" />
												</div>
												<!--end::Avatar-->
												<!--begin::Details-->
												<div class="ms-5">
													<a href="#"
														class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Max
														Smith</a>
													<div class="fw-semibold text-muted">max@kt.com</div>
												</div>
												<!--end::Details-->
											</div>
											<!--end::Details-->
											<!--begin::Access menu-->
											<div class="ms-2 w-100px">
												<select class="form-select form-select-solid form-select-sm"
													data-control="select2" data-hide-search="true">
													<option value="1">Guest</option>
													<option value="2">Owner</option>
													<option value="3" selected="selected">Can Edit</option>
												</select>
											</div>
											<!--end::Access menu-->
										</div>
										<!--end::User-->
										<!--begin::Separator-->
										<div class="border-bottom border-gray-300 border-bottom-dashed"></div>
										<!--end::Separator-->
										<!--begin::User-->
										<div class="rounded d-flex flex-stack bg-active-lighten p-4" data-user-id="3">
											<!--begin::Details-->
											<div class="d-flex align-items-center">
												<!--begin::Checkbox-->
												<label class="form-check form-check-custom form-check-solid me-5">
													<input class="form-check-input" type="checkbox" name="users"
														data-kt-check="true" data-kt-check-target="[data-user-id='3']"
														value="3" />
												</label>
												<!--end::Checkbox-->
												<!--begin::Avatar-->
												<div class="symbol symbol-35px symbol-circle">
													<img alt="Pic" src="assets/media/avatars/300-5.jpg" />
												</div>
												<!--end::Avatar-->
												<!--begin::Details-->
												<div class="ms-5">
													<a href="#"
														class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Sean
														Bean</a>
													<div class="fw-semibold text-muted">sean@dellito.com</div>
												</div>
												<!--end::Details-->
											</div>
											<!--end::Details-->
											<!--begin::Access menu-->
											<div class="ms-2 w-100px">
												<select class="form-select form-select-solid form-select-sm"
													data-control="select2" data-hide-search="true">
													<option value="1">Guest</option>
													<option value="2" selected="selected">Owner</option>
													<option value="3">Can Edit</option>
												</select>
											</div>
											<!--end::Access menu-->
										</div>
										<!--end::User-->
										<!--begin::Separator-->
										<div class="border-bottom border-gray-300 border-bottom-dashed"></div>
										<!--end::Separator-->
										<!--begin::User-->
										<div class="rounded d-flex flex-stack bg-active-lighten p-4" data-user-id="4">
											<!--begin::Details-->
											<div class="d-flex align-items-center">
												<!--begin::Checkbox-->
												<label class="form-check form-check-custom form-check-solid me-5">
													<input class="form-check-input" type="checkbox" name="users"
														data-kt-check="true" data-kt-check-target="[data-user-id='4']"
														value="4" />
												</label>
												<!--end::Checkbox-->
												<!--begin::Avatar-->
												<div class="symbol symbol-35px symbol-circle">
													<img alt="Pic" src="assets/media/avatars/300-25.jpg" />
												</div>
												<!--end::Avatar-->
												<!--begin::Details-->
												<div class="ms-5">
													<a href="#"
														class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Brian
														Cox</a>
													<div class="fw-semibold text-muted">brian@exchange.com</div>
												</div>
												<!--end::Details-->
											</div>
											<!--end::Details-->
											<!--begin::Access menu-->
											<div class="ms-2 w-100px">
												<select class="form-select form-select-solid form-select-sm"
													data-control="select2" data-hide-search="true">
													<option value="1">Guest</option>
													<option value="2">Owner</option>
													<option value="3" selected="selected">Can Edit</option>
												</select>
											</div>
											<!--end::Access menu-->
										</div>
										<!--end::User-->
										<!--begin::Separator-->
										<div class="border-bottom border-gray-300 border-bottom-dashed"></div>
										<!--end::Separator-->
										<!--begin::User-->
										<div class="rounded d-flex flex-stack bg-active-lighten p-4" data-user-id="5">
											<!--begin::Details-->
											<div class="d-flex align-items-center">
												<!--begin::Checkbox-->
												<label class="form-check form-check-custom form-check-solid me-5">
													<input class="form-check-input" type="checkbox" name="users"
														data-kt-check="true" data-kt-check-target="[data-user-id='5']"
														value="5" />
												</label>
												<!--end::Checkbox-->
												<!--begin::Avatar-->
												<div class="symbol symbol-35px symbol-circle">
													<span
														class="symbol-label bg-light-warning text-warning fw-semibold">C</span>
												</div>
												<!--end::Avatar-->
												<!--begin::Details-->
												<div class="ms-5">
													<a href="#"
														class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Mikaela
														Collins</a>
													<div class="fw-semibold text-muted">mik@pex.com</div>
												</div>
												<!--end::Details-->
											</div>
											<!--end::Details-->
											<!--begin::Access menu-->
											<div class="ms-2 w-100px">
												<select class="form-select form-select-solid form-select-sm"
													data-control="select2" data-hide-search="true">
													<option value="1">Guest</option>
													<option value="2" selected="selected">Owner</option>
													<option value="3">Can Edit</option>
												</select>
											</div>
											<!--end::Access menu-->
										</div>
										<!--end::User-->
										<!--begin::Separator-->
										<div class="border-bottom border-gray-300 border-bottom-dashed"></div>
										<!--end::Separator-->
										<!--begin::User-->
										<div class="rounded d-flex flex-stack bg-active-lighten p-4" data-user-id="6">
											<!--begin::Details-->
											<div class="d-flex align-items-center">
												<!--begin::Checkbox-->
												<label class="form-check form-check-custom form-check-solid me-5">
													<input class="form-check-input" type="checkbox" name="users"
														data-kt-check="true" data-kt-check-target="[data-user-id='6']"
														value="6" />
												</label>
												<!--end::Checkbox-->
												<!--begin::Avatar-->
												<div class="symbol symbol-35px symbol-circle">
													<img alt="Pic" src="assets/media/avatars/300-9.jpg" />
												</div>
												<!--end::Avatar-->
												<!--begin::Details-->
												<div class="ms-5">
													<a href="#"
														class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Francis
														Mitcham</a>
													<div class="fw-semibold text-muted">f.mit@kpmg.com</div>
												</div>
												<!--end::Details-->
											</div>
											<!--end::Details-->
											<!--begin::Access menu-->
											<div class="ms-2 w-100px">
												<select class="form-select form-select-solid form-select-sm"
													data-control="select2" data-hide-search="true">
													<option value="1">Guest</option>
													<option value="2">Owner</option>
													<option value="3" selected="selected">Can Edit</option>
												</select>
											</div>
											<!--end::Access menu-->
										</div>
										<!--end::User-->
										<!--begin::Separator-->
										<div class="border-bottom border-gray-300 border-bottom-dashed"></div>
										<!--end::Separator-->
										<!--begin::User-->
										<div class="rounded d-flex flex-stack bg-active-lighten p-4" data-user-id="7">
											<!--begin::Details-->
											<div class="d-flex align-items-center">
												<!--begin::Checkbox-->
												<label class="form-check form-check-custom form-check-solid me-5">
													<input class="form-check-input" type="checkbox" name="users"
														data-kt-check="true" data-kt-check-target="[data-user-id='7']"
														value="7" />
												</label>
												<!--end::Checkbox-->
												<!--begin::Avatar-->
												<div class="symbol symbol-35px symbol-circle">
													<span
														class="symbol-label bg-light-danger text-danger fw-semibold">O</span>
												</div>
												<!--end::Avatar-->
												<!--begin::Details-->
												<div class="ms-5">
													<a href="#"
														class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Olivia
														Wild</a>
													<div class="fw-semibold text-muted">olivia@corpmail.com</div>
												</div>
												<!--end::Details-->
											</div>
											<!--end::Details-->
											<!--begin::Access menu-->
											<div class="ms-2 w-100px">
												<select class="form-select form-select-solid form-select-sm"
													data-control="select2" data-hide-search="true">
													<option value="1">Guest</option>
													<option value="2" selected="selected">Owner</option>
													<option value="3">Can Edit</option>
												</select>
											</div>
											<!--end::Access menu-->
										</div>
										<!--end::User-->
										<!--begin::Separator-->
										<div class="border-bottom border-gray-300 border-bottom-dashed"></div>
										<!--end::Separator-->
										<!--begin::User-->
										<div class="rounded d-flex flex-stack bg-active-lighten p-4" data-user-id="8">
											<!--begin::Details-->
											<div class="d-flex align-items-center">
												<!--begin::Checkbox-->
												<label class="form-check form-check-custom form-check-solid me-5">
													<input class="form-check-input" type="checkbox" name="users"
														data-kt-check="true" data-kt-check-target="[data-user-id='8']"
														value="8" />
												</label>
												<!--end::Checkbox-->
												<!--begin::Avatar-->
												<div class="symbol symbol-35px symbol-circle">
													<span
														class="symbol-label bg-light-primary text-primary fw-semibold">N</span>
												</div>
												<!--end::Avatar-->
												<!--begin::Details-->
												<div class="ms-5">
													<a href="#"
														class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Neil
														Owen</a>
													<div class="fw-semibold text-muted">owen.neil@gmail.com</div>
												</div>
												<!--end::Details-->
											</div>
											<!--end::Details-->
											<!--begin::Access menu-->
											<div class="ms-2 w-100px">
												<select class="form-select form-select-solid form-select-sm"
													data-control="select2" data-hide-search="true">
													<option value="1" selected="selected">Guest</option>
													<option value="2">Owner</option>
													<option value="3">Can Edit</option>
												</select>
											</div>
											<!--end::Access menu-->
										</div>
										<!--end::User-->
										<!--begin::Separator-->
										<div class="border-bottom border-gray-300 border-bottom-dashed"></div>
										<!--end::Separator-->
										<!--begin::User-->
										<div class="rounded d-flex flex-stack bg-active-lighten p-4" data-user-id="9">
											<!--begin::Details-->
											<div class="d-flex align-items-center">
												<!--begin::Checkbox-->
												<label class="form-check form-check-custom form-check-solid me-5">
													<input class="form-check-input" type="checkbox" name="users"
														data-kt-check="true" data-kt-check-target="[data-user-id='9']"
														value="9" />
												</label>
												<!--end::Checkbox-->
												<!--begin::Avatar-->
												<div class="symbol symbol-35px symbol-circle">
													<img alt="Pic" src="assets/media/avatars/300-23.jpg" />
												</div>
												<!--end::Avatar-->
												<!--begin::Details-->
												<div class="ms-5">
													<a href="#"
														class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Dan
														Wilson</a>
													<div class="fw-semibold text-muted">dam@consilting.com</div>
												</div>
												<!--end::Details-->
											</div>
											<!--end::Details-->
											<!--begin::Access menu-->
											<div class="ms-2 w-100px">
												<select class="form-select form-select-solid form-select-sm"
													data-control="select2" data-hide-search="true">
													<option value="1">Guest</option>
													<option value="2">Owner</option>
													<option value="3" selected="selected">Can Edit</option>
												</select>
											</div>
											<!--end::Access menu-->
										</div>
										<!--end::User-->
										<!--begin::Separator-->
										<div class="border-bottom border-gray-300 border-bottom-dashed"></div>
										<!--end::Separator-->
										<!--begin::User-->
										<div class="rounded d-flex flex-stack bg-active-lighten p-4" data-user-id="10">
											<!--begin::Details-->
											<div class="d-flex align-items-center">
												<!--begin::Checkbox-->
												<label class="form-check form-check-custom form-check-solid me-5">
													<input class="form-check-input" type="checkbox" name="users"
														data-kt-check="true" data-kt-check-target="[data-user-id='10']"
														value="10" />
												</label>
												<!--end::Checkbox-->
												<!--begin::Avatar-->
												<div class="symbol symbol-35px symbol-circle">
													<span
														class="symbol-label bg-light-danger text-danger fw-semibold">E</span>
												</div>
												<!--end::Avatar-->
												<!--begin::Details-->
												<div class="ms-5">
													<a href="#"
														class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Emma
														Bold</a>
													<div class="fw-semibold text-muted">emma@intenso.com</div>
												</div>
												<!--end::Details-->
											</div>
											<!--end::Details-->
											<!--begin::Access menu-->
											<div class="ms-2 w-100px">
												<select class="form-select form-select-solid form-select-sm"
													data-control="select2" data-hide-search="true">
													<option value="1">Guest</option>
													<option value="2" selected="selected">Owner</option>
													<option value="3">Can Edit</option>
												</select>
											</div>
											<!--end::Access menu-->
										</div>
										<!--end::User-->
										<!--begin::Separator-->
										<div class="border-bottom border-gray-300 border-bottom-dashed"></div>
										<!--end::Separator-->
										<!--begin::User-->
										<div class="rounded d-flex flex-stack bg-active-lighten p-4" data-user-id="11">
											<!--begin::Details-->
											<div class="d-flex align-items-center">
												<!--begin::Checkbox-->
												<label class="form-check form-check-custom form-check-solid me-5">
													<input class="form-check-input" type="checkbox" name="users"
														data-kt-check="true" data-kt-check-target="[data-user-id='11']"
														value="11" />
												</label>
												<!--end::Checkbox-->
												<!--begin::Avatar-->
												<div class="symbol symbol-35px symbol-circle">
													<img alt="Pic" src="assets/media/avatars/300-12.jpg" />
												</div>
												<!--end::Avatar-->
												<!--begin::Details-->
												<div class="ms-5">
													<a href="#"
														class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Ana
														Crown</a>
													<div class="fw-semibold text-muted">ana.cf@limtel.com</div>
												</div>
												<!--end::Details-->
											</div>
											<!--end::Details-->
											<!--begin::Access menu-->
											<div class="ms-2 w-100px">
												<select class="form-select form-select-solid form-select-sm"
													data-control="select2" data-hide-search="true">
													<option value="1" selected="selected">Guest</option>
													<option value="2">Owner</option>
													<option value="3">Can Edit</option>
												</select>
											</div>
											<!--end::Access menu-->
										</div>
										<!--end::User-->
										<!--begin::Separator-->
										<div class="border-bottom border-gray-300 border-bottom-dashed"></div>
										<!--end::Separator-->
										<!--begin::User-->
										<div class="rounded d-flex flex-stack bg-active-lighten p-4" data-user-id="12">
											<!--begin::Details-->
											<div class="d-flex align-items-center">
												<!--begin::Checkbox-->
												<label class="form-check form-check-custom form-check-solid me-5">
													<input class="form-check-input" type="checkbox" name="users"
														data-kt-check="true" data-kt-check-target="[data-user-id='12']"
														value="12" />
												</label>
												<!--end::Checkbox-->
												<!--begin::Avatar-->
												<div class="symbol symbol-35px symbol-circle">
													<span class="symbol-label bg-light-info text-info fw-semibold">A</span>
												</div>
												<!--end::Avatar-->
												<!--begin::Details-->
												<div class="ms-5">
													<a href="#"
														class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Robert
														Doe</a>
													<div class="fw-semibold text-muted">robert@benko.com</div>
												</div>
												<!--end::Details-->
											</div>
											<!--end::Details-->
											<!--begin::Access menu-->
											<div class="ms-2 w-100px">
												<select class="form-select form-select-solid form-select-sm"
													data-control="select2" data-hide-search="true">
													<option value="1">Guest</option>
													<option value="2">Owner</option>
													<option value="3" selected="selected">Can Edit</option>
												</select>
											</div>
											<!--end::Access menu-->
										</div>
										<!--end::User-->
										<!--begin::Separator-->
										<div class="border-bottom border-gray-300 border-bottom-dashed"></div>
										<!--end::Separator-->
										<!--begin::User-->
										<div class="rounded d-flex flex-stack bg-active-lighten p-4" data-user-id="13">
											<!--begin::Details-->
											<div class="d-flex align-items-center">
												<!--begin::Checkbox-->
												<label class="form-check form-check-custom form-check-solid me-5">
													<input class="form-check-input" type="checkbox" name="users"
														data-kt-check="true" data-kt-check-target="[data-user-id='13']"
														value="13" />
												</label>
												<!--end::Checkbox-->
												<!--begin::Avatar-->
												<div class="symbol symbol-35px symbol-circle">
													<img alt="Pic" src="assets/media/avatars/300-13.jpg" />
												</div>
												<!--end::Avatar-->
												<!--begin::Details-->
												<div class="ms-5">
													<a href="#"
														class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">John
														Miller</a>
													<div class="fw-semibold text-muted">miller@mapple.com</div>
												</div>
												<!--end::Details-->
											</div>
											<!--end::Details-->
											<!--begin::Access menu-->
											<div class="ms-2 w-100px">
												<select class="form-select form-select-solid form-select-sm"
													data-control="select2" data-hide-search="true">
													<option value="1">Guest</option>
													<option value="2">Owner</option>
													<option value="3" selected="selected">Can Edit</option>
												</select>
											</div>
											<!--end::Access menu-->
										</div>
										<!--end::User-->
										<!--begin::Separator-->
										<div class="border-bottom border-gray-300 border-bottom-dashed"></div>
										<!--end::Separator-->
										<!--begin::User-->
										<div class="rounded d-flex flex-stack bg-active-lighten p-4" data-user-id="14">
											<!--begin::Details-->
											<div class="d-flex align-items-center">
												<!--begin::Checkbox-->
												<label class="form-check form-check-custom form-check-solid me-5">
													<input class="form-check-input" type="checkbox" name="users"
														data-kt-check="true" data-kt-check-target="[data-user-id='14']"
														value="14" />
												</label>
												<!--end::Checkbox-->
												<!--begin::Avatar-->
												<div class="symbol symbol-35px symbol-circle">
													<span
														class="symbol-label bg-light-success text-success fw-semibold">L</span>
												</div>
												<!--end::Avatar-->
												<!--begin::Details-->
												<div class="ms-5">
													<a href="#"
														class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Lucy
														Kunic</a>
													<div class="fw-semibold text-muted">lucy.m@fentech.com</div>
												</div>
												<!--end::Details-->
											</div>
											<!--end::Details-->
											<!--begin::Access menu-->
											<div class="ms-2 w-100px">
												<select class="form-select form-select-solid form-select-sm"
													data-control="select2" data-hide-search="true">
													<option value="1">Guest</option>
													<option value="2" selected="selected">Owner</option>
													<option value="3">Can Edit</option>
												</select>
											</div>
											<!--end::Access menu-->
										</div>
										<!--end::User-->
										<!--begin::Separator-->
										<div class="border-bottom border-gray-300 border-bottom-dashed"></div>
										<!--end::Separator-->
										<!--begin::User-->
										<div class="rounded d-flex flex-stack bg-active-lighten p-4" data-user-id="15">
											<!--begin::Details-->
											<div class="d-flex align-items-center">
												<!--begin::Checkbox-->
												<label class="form-check form-check-custom form-check-solid me-5">
													<input class="form-check-input" type="checkbox" name="users"
														data-kt-check="true" data-kt-check-target="[data-user-id='15']"
														value="15" />
												</label>
												<!--end::Checkbox-->
												<!--begin::Avatar-->
												<div class="symbol symbol-35px symbol-circle">
													<img alt="Pic" src="assets/media/avatars/300-21.jpg" />
												</div>
												<!--end::Avatar-->
												<!--begin::Details-->
												<div class="ms-5">
													<a href="#"
														class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Ethan
														Wilder</a>
													<div class="fw-semibold text-muted">ethan@loop.com.au</div>
												</div>
												<!--end::Details-->
											</div>
											<!--end::Details-->
											<!--begin::Access menu-->
											<div class="ms-2 w-100px">
												<select class="form-select form-select-solid form-select-sm"
													data-control="select2" data-hide-search="true">
													<option value="1" selected="selected">Guest</option>
													<option value="2">Owner</option>
													<option value="3">Can Edit</option>
												</select>
											</div>
											<!--end::Access menu-->
										</div>
										<!--end::User-->
										<!--begin::Separator-->
										<div class="border-bottom border-gray-300 border-bottom-dashed"></div>
										<!--end::Separator-->
										<!--begin::User-->
										<div class="rounded d-flex flex-stack bg-active-lighten p-4" data-user-id="16">
											<!--begin::Details-->
											<div class="d-flex align-items-center">
												<!--begin::Checkbox-->
												<label class="form-check form-check-custom form-check-solid me-5">
													<input class="form-check-input" type="checkbox" name="users"
														data-kt-check="true" data-kt-check-target="[data-user-id='16']"
														value="16" />
												</label>
												<!--end::Checkbox-->
												<!--begin::Avatar-->
												<div class="symbol symbol-35px symbol-circle">
													<img alt="Pic" src="assets/media/avatars/300-25.jpg" />
												</div>
												<!--end::Avatar-->
												<!--begin::Details-->
												<div class="ms-5">
													<a href="#"
														class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Brian
														Cox</a>
													<div class="fw-semibold text-muted">brian@exchange.com</div>
												</div>
												<!--end::Details-->
											</div>
											<!--end::Details-->
											<!--begin::Access menu-->
											<div class="ms-2 w-100px">
												<select class="form-select form-select-solid form-select-sm"
													data-control="select2" data-hide-search="true">
													<option value="1">Guest</option>
													<option value="2">Owner</option>
													<option value="3" selected="selected">Can Edit</option>
												</select>
											</div>
											<!--end::Access menu-->
										</div>
										<!--end::User-->
									</div>
									<!--end::Users-->
									<!--begin::Actions-->
									<div class="d-flex flex-center mt-15">
										<button type="reset" id="kt_modal_users_search_reset" data-bs-dismiss="modal"
											class="btn btn-active-light me-3">Cancel</button>
										<button type="submit" id="kt_modal_users_search_submit"
											class="btn btn-primary btn-sm">Add Selected Users</button>
									</div>
									<!--end::Actions-->
								</div>
								<!--end::Results-->
								<!--begin::Empty-->
								<div data-kt-search-element="empty" class="text-center d-none">
									<!--begin::Message-->
									<div class="fw-semibold py-10">
										<div class="text-gray-600 fs-3 mb-2">No users found</div>
										<div class="text-muted fs-6">Try to search by username, full name or email...</div>
									</div>
									<!--end::Message-->
									<!--begin::Illustration-->
									<div class="text-center px-5">
										<img src="assets/media/illustrations/sketchy-1/1.png" alt=""
											class="w-100 h-200px h-sm-325px" />
									</div>
									<!--end::Illustration-->
								</div>
								<!--end::Empty-->
							</div>
							<!--end::Wrapper-->
						</div>
						<!--end::Search-->
					</div>
					<!--end::Modal body-->
				</div>
				<!--end::Modal content-->
			</div>
			<!--end::Modal dialog-->
		</div>
		<!--end::Modal - Users Search-->
		<!--begin::Modal - Invite Friends-->
		<div class="modal fade" id="kt_modal_invite_friends" tabindex="-1" aria-hidden="true">
			<!--begin::Modal dialog-->
			<div class="modal-dialog mw-650px">
				<!--begin::Modal content-->
				<div class="modal-content">
					<!--begin::Modal header-->
					<div class="modal-header pb-0 border-0 justify-content-end">
						<!--begin::Close-->
						<div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
							<i class="ki-duotone ki-cross fs-1">
								<span class="path1"></span>
								<span class="path2"></span>
							</i>
						</div>
						<!--end::Close-->
					</div>
					<!--begin::Modal header-->
					<!--begin::Modal body-->
					<div class="modal-body scroll-y mx-5 mx-xl-18 pt-0 pb-15">
						<!--begin::Heading-->
						<div class="text-center mb-13">
							<!--begin::Title-->
							<h1 class="mb-3">Invite a Friend</h1>
							<!--end::Title-->
							<!--begin::Description-->
							<div class="text-muted fw-semibold fs-5">If you need more info, please check out
								<a href="#" class="link-primary fw-bold">FAQ Page</a>.
							</div>
							<!--end::Description-->
						</div>
						<!--end::Heading-->
						<!--begin::Google Contacts Invite-->
						<div class="btn btn-light btn-sm-primary fw-bold w-100 mb-8">
							<img alt="Logo" src="assets/media/svg/brand-logos/google-icon.svg" class="h-20px me-3" />Invite
							Gmail Contacts
						</div>
						<!--end::Google Contacts Invite-->
						<!--begin::Separator-->
						<div class="separator d-flex flex-center mb-8">
							<span class="text-uppercase bg-body fs-7 fw-semibold text-muted px-3">or</span>
						</div>
						<!--end::Separator-->
						<!--begin::Textarea-->
						<textarea class="form-control form-control-solid mb-8" rows="3"
							placeholder="Type or paste emails here"></textarea>
						<!--end::Textarea-->
						<!--begin::Users-->
						<div class="mb-10">
							<!--begin::Heading-->
							<div class="fs-6 fw-semibold mb-2">Your Invitations</div>
							<!--end::Heading-->
							<!--begin::List-->
							<div class="mh-300px scroll-y me-n7 pe-7">
								<!--begin::User-->
								<div class="d-flex flex-stack py-4 border-bottom border-gray-300 border-bottom-dashed">
									<!--begin::Details-->
									<div class="d-flex align-items-center">
										<!--begin::Avatar-->
										<div class="symbol symbol-35px symbol-circle">
											<img alt="Pic" src="assets/media/avatars/300-6.jpg" />
										</div>
										<!--end::Avatar-->
										<!--begin::Details-->
										<div class="ms-5">
											<a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Emma
												Smith</a>
											<div class="fw-semibold text-muted">smith@kpmg.com</div>
										</div>
										<!--end::Details-->
									</div>
									<!--end::Details-->
									<!--begin::Access menu-->
									<div class="ms-2 w-100px">
										<select class="form-select form-select-solid form-select-sm" data-control="select2"
											data-dropdown-parent="#kt_modal_invite_friends" data-hide-search="true">
											<option value="1">Guest</option>
											<option value="2" selected="selected">Owner</option>
											<option value="3">Can Edit</option>
										</select>
									</div>
									<!--end::Access menu-->
								</div>
								<!--end::User-->
								<!--begin::User-->
								<div class="d-flex flex-stack py-4 border-bottom border-gray-300 border-bottom-dashed">
									<!--begin::Details-->
									<div class="d-flex align-items-center">
										<!--begin::Avatar-->
										<div class="symbol symbol-35px symbol-circle">
											<span class="symbol-label bg-light-danger text-danger fw-semibold">M</span>
										</div>
										<!--end::Avatar-->
										<!--begin::Details-->
										<div class="ms-5">
											<a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Melody
												Macy</a>
											<div class="fw-semibold text-muted">melody@altbox.com</div>
										</div>
										<!--end::Details-->
									</div>
									<!--end::Details-->
									<!--begin::Access menu-->
									<div class="ms-2 w-100px">
										<select class="form-select form-select-solid form-select-sm" data-control="select2"
											data-dropdown-parent="#kt_modal_invite_friends" data-hide-search="true">
											<option value="1" selected="selected">Guest</option>
											<option value="2">Owner</option>
											<option value="3">Can Edit</option>
										</select>
									</div>
									<!--end::Access menu-->
								</div>
								<!--end::User-->
								<!--begin::User-->
								<div class="d-flex flex-stack py-4 border-bottom border-gray-300 border-bottom-dashed">
									<!--begin::Details-->
									<div class="d-flex align-items-center">
										<!--begin::Avatar-->
										<div class="symbol symbol-35px symbol-circle">
											<img alt="Pic" src="assets/media/avatars/300-1.jpg" />
										</div>
										<!--end::Avatar-->
										<!--begin::Details-->
										<div class="ms-5">
											<a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Max
												Smith</a>
											<div class="fw-semibold text-muted">max@kt.com</div>
										</div>
										<!--end::Details-->
									</div>
									<!--end::Details-->
									<!--begin::Access menu-->
									<div class="ms-2 w-100px">
										<select class="form-select form-select-solid form-select-sm" data-control="select2"
											data-dropdown-parent="#kt_modal_invite_friends" data-hide-search="true">
											<option value="1">Guest</option>
											<option value="2">Owner</option>
											<option value="3" selected="selected">Can Edit</option>
										</select>
									</div>
									<!--end::Access menu-->
								</div>
								<!--end::User-->
								<!--begin::User-->
								<div class="d-flex flex-stack py-4 border-bottom border-gray-300 border-bottom-dashed">
									<!--begin::Details-->
									<div class="d-flex align-items-center">
										<!--begin::Avatar-->
										<div class="symbol symbol-35px symbol-circle">
											<img alt="Pic" src="assets/media/avatars/300-5.jpg" />
										</div>
										<!--end::Avatar-->
										<!--begin::Details-->
										<div class="ms-5">
											<a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Sean
												Bean</a>
											<div class="fw-semibold text-muted">sean@dellito.com</div>
										</div>
										<!--end::Details-->
									</div>
									<!--end::Details-->
									<!--begin::Access menu-->
									<div class="ms-2 w-100px">
										<select class="form-select form-select-solid form-select-sm" data-control="select2"
											data-dropdown-parent="#kt_modal_invite_friends" data-hide-search="true">
											<option value="1">Guest</option>
											<option value="2" selected="selected">Owner</option>
											<option value="3">Can Edit</option>
										</select>
									</div>
									<!--end::Access menu-->
								</div>
								<!--end::User-->
								<!--begin::User-->
								<div class="d-flex flex-stack py-4 border-bottom border-gray-300 border-bottom-dashed">
									<!--begin::Details-->
									<div class="d-flex align-items-center">
										<!--begin::Avatar-->
										<div class="symbol symbol-35px symbol-circle">
											<img alt="Pic" src="assets/media/avatars/300-25.jpg" />
										</div>
										<!--end::Avatar-->
										<!--begin::Details-->
										<div class="ms-5">
											<a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Brian
												Cox</a>
											<div class="fw-semibold text-muted">brian@exchange.com</div>
										</div>
										<!--end::Details-->
									</div>
									<!--end::Details-->
									<!--begin::Access menu-->
									<div class="ms-2 w-100px">
										<select class="form-select form-select-solid form-select-sm" data-control="select2"
											data-dropdown-parent="#kt_modal_invite_friends" data-hide-search="true">
											<option value="1">Guest</option>
											<option value="2">Owner</option>
											<option value="3" selected="selected">Can Edit</option>
										</select>
									</div>
									<!--end::Access menu-->
								</div>
								<!--end::User-->
								<!--begin::User-->
								<div class="d-flex flex-stack py-4 border-bottom border-gray-300 border-bottom-dashed">
									<!--begin::Details-->
									<div class="d-flex align-items-center">
										<!--begin::Avatar-->
										<div class="symbol symbol-35px symbol-circle">
											<span class="symbol-label bg-light-warning text-warning fw-semibold">C</span>
										</div>
										<!--end::Avatar-->
										<!--begin::Details-->
										<div class="ms-5">
											<a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Mikaela
												Collins</a>
											<div class="fw-semibold text-muted">mik@pex.com</div>
										</div>
										<!--end::Details-->
									</div>
									<!--end::Details-->
									<!--begin::Access menu-->
									<div class="ms-2 w-100px">
										<select class="form-select form-select-solid form-select-sm" data-control="select2"
											data-dropdown-parent="#kt_modal_invite_friends" data-hide-search="true">
											<option value="1">Guest</option>
											<option value="2" selected="selected">Owner</option>
											<option value="3">Can Edit</option>
										</select>
									</div>
									<!--end::Access menu-->
								</div>
								<!--end::User-->
								<!--begin::User-->
								<div class="d-flex flex-stack py-4 border-bottom border-gray-300 border-bottom-dashed">
									<!--begin::Details-->
									<div class="d-flex align-items-center">
										<!--begin::Avatar-->
										<div class="symbol symbol-35px symbol-circle">
											<img alt="Pic" src="assets/media/avatars/300-9.jpg" />
										</div>
										<!--end::Avatar-->
										<!--begin::Details-->
										<div class="ms-5">
											<a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Francis
												Mitcham</a>
											<div class="fw-semibold text-muted">f.mit@kpmg.com</div>
										</div>
										<!--end::Details-->
									</div>
									<!--end::Details-->
									<!--begin::Access menu-->
									<div class="ms-2 w-100px">
										<select class="form-select form-select-solid form-select-sm" data-control="select2"
											data-dropdown-parent="#kt_modal_invite_friends" data-hide-search="true">
											<option value="1">Guest</option>
											<option value="2">Owner</option>
											<option value="3" selected="selected">Can Edit</option>
										</select>
									</div>
									<!--end::Access menu-->
								</div>
								<!--end::User-->
								<!--begin::User-->
								<div class="d-flex flex-stack py-4 border-bottom border-gray-300 border-bottom-dashed">
									<!--begin::Details-->
									<div class="d-flex align-items-center">
										<!--begin::Avatar-->
										<div class="symbol symbol-35px symbol-circle">
											<span class="symbol-label bg-light-danger text-danger fw-semibold">O</span>
										</div>
										<!--end::Avatar-->
										<!--begin::Details-->
										<div class="ms-5">
											<a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Olivia
												Wild</a>
											<div class="fw-semibold text-muted">olivia@corpmail.com</div>
										</div>
										<!--end::Details-->
									</div>
									<!--end::Details-->
									<!--begin::Access menu-->
									<div class="ms-2 w-100px">
										<select class="form-select form-select-solid form-select-sm" data-control="select2"
											data-dropdown-parent="#kt_modal_invite_friends" data-hide-search="true">
											<option value="1">Guest</option>
											<option value="2" selected="selected">Owner</option>
											<option value="3">Can Edit</option>
										</select>
									</div>
									<!--end::Access menu-->
								</div>
								<!--end::User-->
								<!--begin::User-->
								<div class="d-flex flex-stack py-4 border-bottom border-gray-300 border-bottom-dashed">
									<!--begin::Details-->
									<div class="d-flex align-items-center">
										<!--begin::Avatar-->
										<div class="symbol symbol-35px symbol-circle">
											<span class="symbol-label bg-light-primary text-primary fw-semibold">N</span>
										</div>
										<!--end::Avatar-->
										<!--begin::Details-->
										<div class="ms-5">
											<a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Neil
												Owen</a>
											<div class="fw-semibold text-muted">owen.neil@gmail.com</div>
										</div>
										<!--end::Details-->
									</div>
									<!--end::Details-->
									<!--begin::Access menu-->
									<div class="ms-2 w-100px">
										<select class="form-select form-select-solid form-select-sm" data-control="select2"
											data-dropdown-parent="#kt_modal_invite_friends" data-hide-search="true">
											<option value="1" selected="selected">Guest</option>
											<option value="2">Owner</option>
											<option value="3">Can Edit</option>
										</select>
									</div>
									<!--end::Access menu-->
								</div>
								<!--end::User-->
								<!--begin::User-->
								<div class="d-flex flex-stack py-4 border-bottom border-gray-300 border-bottom-dashed">
									<!--begin::Details-->
									<div class="d-flex align-items-center">
										<!--begin::Avatar-->
										<div class="symbol symbol-35px symbol-circle">
											<img alt="Pic" src="assets/media/avatars/300-23.jpg" />
										</div>
										<!--end::Avatar-->
										<!--begin::Details-->
										<div class="ms-5">
											<a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Dan
												Wilson</a>
											<div class="fw-semibold text-muted">dam@consilting.com</div>
										</div>
										<!--end::Details-->
									</div>
									<!--end::Details-->
									<!--begin::Access menu-->
									<div class="ms-2 w-100px">
										<select class="form-select form-select-solid form-select-sm" data-control="select2"
											data-dropdown-parent="#kt_modal_invite_friends" data-hide-search="true">
											<option value="1">Guest</option>
											<option value="2">Owner</option>
											<option value="3" selected="selected">Can Edit</option>
										</select>
									</div>
									<!--end::Access menu-->
								</div>
								<!--end::User-->
								<!--begin::User-->
								<div class="d-flex flex-stack py-4 border-bottom border-gray-300 border-bottom-dashed">
									<!--begin::Details-->
									<div class="d-flex align-items-center">
										<!--begin::Avatar-->
										<div class="symbol symbol-35px symbol-circle">
											<span class="symbol-label bg-light-danger text-danger fw-semibold">E</span>
										</div>
										<!--end::Avatar-->
										<!--begin::Details-->
										<div class="ms-5">
											<a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Emma
												Bold</a>
											<div class="fw-semibold text-muted">emma@intenso.com</div>
										</div>
										<!--end::Details-->
									</div>
									<!--end::Details-->
									<!--begin::Access menu-->
									<div class="ms-2 w-100px">
										<select class="form-select form-select-solid form-select-sm" data-control="select2"
											data-dropdown-parent="#kt_modal_invite_friends" data-hide-search="true">
											<option value="1">Guest</option>
											<option value="2" selected="selected">Owner</option>
											<option value="3">Can Edit</option>
										</select>
									</div>
									<!--end::Access menu-->
								</div>
								<!--end::User-->
								<!--begin::User-->
								<div class="d-flex flex-stack py-4 border-bottom border-gray-300 border-bottom-dashed">
									<!--begin::Details-->
									<div class="d-flex align-items-center">
										<!--begin::Avatar-->
										<div class="symbol symbol-35px symbol-circle">
											<img alt="Pic" src="assets/media/avatars/300-12.jpg" />
										</div>
										<!--end::Avatar-->
										<!--begin::Details-->
										<div class="ms-5">
											<a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Ana
												Crown</a>
											<div class="fw-semibold text-muted">ana.cf@limtel.com</div>
										</div>
										<!--end::Details-->
									</div>
									<!--end::Details-->
									<!--begin::Access menu-->
									<div class="ms-2 w-100px">
										<select class="form-select form-select-solid form-select-sm" data-control="select2"
											data-dropdown-parent="#kt_modal_invite_friends" data-hide-search="true">
											<option value="1" selected="selected">Guest</option>
											<option value="2">Owner</option>
											<option value="3">Can Edit</option>
										</select>
									</div>
									<!--end::Access menu-->
								</div>
								<!--end::User-->
								<!--begin::User-->
								<div class="d-flex flex-stack py-4 border-bottom border-gray-300 border-bottom-dashed">
									<!--begin::Details-->
									<div class="d-flex align-items-center">
										<!--begin::Avatar-->
										<div class="symbol symbol-35px symbol-circle">
											<span class="symbol-label bg-light-info text-info fw-semibold">A</span>
										</div>
										<!--end::Avatar-->
										<!--begin::Details-->
										<div class="ms-5">
											<a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Robert
												Doe</a>
											<div class="fw-semibold text-muted">robert@benko.com</div>
										</div>
										<!--end::Details-->
									</div>
									<!--end::Details-->
									<!--begin::Access menu-->
									<div class="ms-2 w-100px">
										<select class="form-select form-select-solid form-select-sm" data-control="select2"
											data-dropdown-parent="#kt_modal_invite_friends" data-hide-search="true">
											<option value="1">Guest</option>
											<option value="2">Owner</option>
											<option value="3" selected="selected">Can Edit</option>
										</select>
									</div>
									<!--end::Access menu-->
								</div>
								<!--end::User-->
								<!--begin::User-->
								<div class="d-flex flex-stack py-4 border-bottom border-gray-300 border-bottom-dashed">
									<!--begin::Details-->
									<div class="d-flex align-items-center">
										<!--begin::Avatar-->
										<div class="symbol symbol-35px symbol-circle">
											<img alt="Pic" src="assets/media/avatars/300-13.jpg" />
										</div>
										<!--end::Avatar-->
										<!--begin::Details-->
										<div class="ms-5">
											<a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">John
												Miller</a>
											<div class="fw-semibold text-muted">miller@mapple.com</div>
										</div>
										<!--end::Details-->
									</div>
									<!--end::Details-->
									<!--begin::Access menu-->
									<div class="ms-2 w-100px">
										<select class="form-select form-select-solid form-select-sm" data-control="select2"
											data-dropdown-parent="#kt_modal_invite_friends" data-hide-search="true">
											<option value="1">Guest</option>
											<option value="2">Owner</option>
											<option value="3" selected="selected">Can Edit</option>
										</select>
									</div>
									<!--end::Access menu-->
								</div>
								<!--end::User-->
								<!--begin::User-->
								<div class="d-flex flex-stack py-4 border-bottom border-gray-300 border-bottom-dashed">
									<!--begin::Details-->
									<div class="d-flex align-items-center">
										<!--begin::Avatar-->
										<div class="symbol symbol-35px symbol-circle">
											<span class="symbol-label bg-light-success text-success fw-semibold">L</span>
										</div>
										<!--end::Avatar-->
										<!--begin::Details-->
										<div class="ms-5">
											<a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Lucy
												Kunic</a>
											<div class="fw-semibold text-muted">lucy.m@fentech.com</div>
										</div>
										<!--end::Details-->
									</div>
									<!--end::Details-->
									<!--begin::Access menu-->
									<div class="ms-2 w-100px">
										<select class="form-select form-select-solid form-select-sm" data-control="select2"
											data-dropdown-parent="#kt_modal_invite_friends" data-hide-search="true">
											<option value="1">Guest</option>
											<option value="2" selected="selected">Owner</option>
											<option value="3">Can Edit</option>
										</select>
									</div>
									<!--end::Access menu-->
								</div>
								<!--end::User-->
								<!--begin::User-->
								<div class="d-flex flex-stack py-4 border-bottom border-gray-300 border-bottom-dashed">
									<!--begin::Details-->
									<div class="d-flex align-items-center">
										<!--begin::Avatar-->
										<div class="symbol symbol-35px symbol-circle">
											<img alt="Pic" src="assets/media/avatars/300-21.jpg" />
										</div>
										<!--end::Avatar-->
										<!--begin::Details-->
										<div class="ms-5">
											<a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Ethan
												Wilder</a>
											<div class="fw-semibold text-muted">ethan@loop.com.au</div>
										</div>
										<!--end::Details-->
									</div>
									<!--end::Details-->
									<!--begin::Access menu-->
									<div class="ms-2 w-100px">
										<select class="form-select form-select-solid form-select-sm" data-control="select2"
											data-dropdown-parent="#kt_modal_invite_friends" data-hide-search="true">
											<option value="1" selected="selected">Guest</option>
											<option value="2">Owner</option>
											<option value="3">Can Edit</option>
										</select>
									</div>
									<!--end::Access menu-->
								</div>
								<!--end::User-->
								<!--begin::User-->
								<div class="d-flex flex-stack py-4">
									<!--begin::Details-->
									<div class="d-flex align-items-center">
										<!--begin::Avatar-->
										<div class="symbol symbol-35px symbol-circle">
											<img alt="Pic" src="assets/media/avatars/300-5.jpg" />
										</div>
										<!--end::Avatar-->
										<!--begin::Details-->
										<div class="ms-5">
											<a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Sean
												Bean</a>
											<div class="fw-semibold text-muted">sean@dellito.com</div>
										</div>
										<!--end::Details-->
									</div>
									<!--end::Details-->
									<!--begin::Access menu-->
									<div class="ms-2 w-100px">
										<select class="form-select form-select-solid form-select-sm" data-control="select2"
											data-dropdown-parent="#kt_modal_invite_friends" data-hide-search="true">
											<option value="1">Guest</option>
											<option value="2">Owner</option>
											<option value="3" selected="selected">Can Edit</option>
										</select>
									</div>
									<!--end::Access menu-->
								</div>
								<!--end::User-->
							</div>
							<!--end::List-->
						</div>
						<!--end::Users-->
						<!--begin::Notice-->
						<div class="d-flex flex-stack">
							<!--begin::Label-->
							<div class="me-5 fw-semibold">
								<label class="fs-6">Adding Users by Team Members</label>
								<div class="fs-7 text-muted">If you need more info, please check budget planning</div>
							</div>
							<!--end::Label-->
							<!--begin::Switch-->
							<label class="form-check form-switch form-check-custom form-check-solid">
								<input class="form-check-input" type="checkbox" value="1" checked="checked" />
								<span class="form-check-label fw-semibold text-muted">Allowed</span>
							</label>
							<!--end::Switch-->
						</div>
						<!--end::Notice-->
					</div>
					<!--end::Modal body-->
				</div>
				<!--end::Modal content-->
			</div>
			<!--end::Modal dialog-->
		</div>
		<!--end::Modal - Invite Friend-->
		<!--end::Modals-->
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
		<script src="assets/js/custom/pages/user-profile/general.js"></script>
		<script src="assets/js/widgets.bundle.js"></script>
		<script src="assets/js/custom/widgets.js"></script>
		<script src="assets/js/custom/apps/chat/chat.js"></script>
		<script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
		<script src="assets/js/custom/utilities/modals/create-account.js"></script>
		<script src="assets/js/custom/utilities/modals/create-app.js"></script>
		<script src="assets/js/custom/utilities/modals/offer-a-deal/type.js"></script>
		<script src="assets/js/custom/utilities/modals/offer-a-deal/details.js"></script>
		<script src="assets/js/custom/utilities/modals/offer-a-deal/finance.js"></script>
		<script src="assets/js/custom/utilities/modals/offer-a-deal/complete.js"></script>
		<script src="assets/js/custom/utilities/modals/offer-a-deal/main.js"></script>
		<script src="assets/js/custom/utilities/modals/users-search.js"></script>


		<script src="assets/js/custom/apps/profile/trackprogress.js"></script>
		<!--end::Custom Javascript-->
		<!--end::Javascript-->
	</body>
	<!--end::Body-->

	</html>
<?php } else {
	header("location: index");
}
