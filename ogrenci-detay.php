<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 8 or $_SESSION['role'] == 4 or $_SESSION['role'] == 3 or $_SESSION['role'] == 5)) {
	include_once "classes/dbh.classes.php";
	include_once "classes/classes.classes.php";
	include_once "classes/classes-view.classes.php";
	include_once "classes/lessons.classes.php";
	include_once "classes/lessons-view.classes.php";
	include "classes/student.classes.php";
	include "classes/student-view.classes.php";
	include "classes/school.classes.php";
	include "classes/timespend.classes.php";

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
	} elseif ($_SESSION['role'] == 5) {
		$getStudentId = $studentId->getStudentIdForParent($slug, $_SESSION['id']);
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
																					<th style="width: 25%;"
																						class="ps-0">PAKET TÜRÜ
																					</th>
																					<th style="width: 25%;"
																						class="ps-0">SÜRE / ADET
																					</th>
																					<th style="width: 25%;"
																						class="text-end">FİYATI
																					</th>
																					<th style="width: 50%;"
																						class="pe-0 text-end">
																						BİTİŞ TARİHİ / KALAN</th>
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
														<div class="card-body pt-5"><!--begin::Table container-->
															<div class="table-responsive">
																<!--begin::Table-->
																<table
																	class="table table-row-dashed align-middle gs-0 gy-4 my-0">
																	<!--begin::Table head-->
																	<thead>
																		<tr
																			class="fs-7 fw-bold text-gray-500 border-bottom-0">
																			<th style="width: 20%;"
																				class="ps-0">SINIF
																			</th>
																			<th style="width: 20%;"
																				class="ps-0">DERS
																			</th>
																			<th style="width: 20%;"
																				class="ps-0">ÖĞRETMEN
																			</th>
																			<th style="width: 20%;"
																				class="text-end">ZAMAN
																			</th>
																			<th style="width: 20%;"
																				class="pe-0 text-end">
																				DURUM</th>
																		</tr>
																	</thead>
																	<!--end::Table head-->
																	<!--begin::Table body-->
																	<tbody> 
																		
																		<?php $student->showprivateLessonsforFirstPage($getStudentId); ?>

																	</tbody>
																	<!--end::Table body-->
																</table>
																<!--end::Table-->
															</div>
															<!--end::Table container-->
														</div>
														<!--end: Card Body-->
													</div>
													<!--end::List widget 23-->
													<!--begin::Timeline widget 2-->

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
																<!-- <div class="row mt-4">

																	<div class="col-lg-10">
																		<label class="fs-6 fw-semibold mb-2"
																			for="subtopics">Altkonu
																		</label>
																		<select class="form-select" id="subtopics"
																			name="subtopics" aria-label="Altkonu Seçiniz">
																			<option value="">Seçiniz</option>
																		</select>
																	</div>
																</div> -->
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
														<h3>Talep Edilen Özel Dersler</h3>
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
																	<th class="min-w-150px">Zaman</th>
																	<th class="min-w-150px">Durum</th>
																</tr>
															</thead>
															<!--end::Thead-->
															<!--begin::Tbody-->
															<tbody class="fw-6 fw-semibold text-gray-600">

																<?php $student->showprivateLessons($getStudentId); ?>

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
																	<th class="min-w-100px">Tür</th>
																	<th class="min-w-100px">Süre / Adet</th>
																	<th class="min-w-150px">Fiyatı</th>
																	<th class="min-w-150px text-end">Bitiş Tarihi / Kalan</th>
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
		<!--end::Drawers-->
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
	header("location: ../index");
}
