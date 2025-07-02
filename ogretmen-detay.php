<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 8 or $_SESSION['role'] == 4 or $_SESSION['role'] == 3)) {
	include_once "classes/dbh.classes.php";
	include_once "classes/teacher.classes.php";
	include_once "classes/teacher-view.classes.php";
	include_once "classes/school.classes.php";
	include_once "classes/timespend.classes.php";
	$teacherId = new Teacher();
	$teacher = new ShowTeacher();
	$timeSpend = new timeSpend();
	$school = new School();
	include_once "classes/student.classes.php";
	include_once "classes/student-view.classes.php";
	$studentObj = new ShowStudent();

	require_once "classes/content-tracker.classes.php";
	require_once "classes/grade-result.classes.php";

	$contentObj = new ContentTracker();
	$gradeObj = new GradeResult();
	$slug = $_GET['q'];
	include_once "views/pages-head.php";
	$getTeacherId = $teacherId->getTeacherId($slug);

	$userInfo = $teacher->getOneTeacher($getTeacherId);

	include_once "classes/addhomework.classes.php";
	include_once "classes/homework-view.classes.php";
	$homework = new ShowHomeworkContents();

	$studentList = $teacher->getstudentsByClassId($userInfo['school_id'], $userInfo['class_id']);

	include_once "views/pages-head.php";
	$pdo = new Dbh();
	$con = $pdo->connect();
	$stmt = $con->prepare('SELECT tests_lnp.*, 
			classes_lnp.name AS className, 
			lessons_lnp.name AS lessonName, 
			units_lnp.name AS unitName, 
			topics_lnp.name AS topicName, 
			subtopics_lnp.name AS subTopicName
			FROM tests_lnp 
			LEFT JOIN classes_lnp ON tests_lnp.class_id = classes_lnp.id 
			LEFT JOIN lessons_lnp ON tests_lnp.lesson_id = lessons_lnp.id 
			LEFT JOIN units_lnp ON tests_lnp.unit_id = units_lnp.id 
			LEFT JOIN topics_lnp ON tests_lnp.topic_id = topics_lnp.id 
			LEFT JOIN subtopics_lnp ON tests_lnp.subtopic_id = subtopics_lnp.id 
			WHERE tests_lnp.school_id = ? 
			AND tests_lnp.class_id = ? 
			AND tests_lnp.lesson_id = ? 
			AND tests_lnp.teacher_id = ? 
			ORDER BY tests_lnp.id DESC');

	if (!$stmt->execute([$userInfo['school_id'], $userInfo['class_id'], $userInfo['lesson_id'], $userInfo['id']])) {
		$stmt = null;
		exit();
	}

	$testList = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$con = $pdo->connect();
	$stmt = $con->prepare('SELECT homework_content_lnp.*, 
            classes_lnp.name AS className, 
            lessons_lnp.name AS lessonName, 
            units_lnp.name AS unitName, 
            topics_lnp.name AS topicName, 
            subtopics_lnp.name AS subTopicName
            FROM homework_content_lnp 
            LEFT JOIN classes_lnp ON homework_content_lnp.class_id = classes_lnp.id 
            LEFT JOIN lessons_lnp ON homework_content_lnp.lesson_id = lessons_lnp.id 
            LEFT JOIN units_lnp ON homework_content_lnp.unit_id = units_lnp.id 
            LEFT JOIN topics_lnp ON homework_content_lnp.topic_id = topics_lnp.id 
            LEFT JOIN subtopics_lnp ON homework_content_lnp.subtopic_id = subtopics_lnp.id 
            WHERE homework_content_lnp.school_id = ? 
            AND homework_content_lnp.class_id = ? 
            AND homework_content_lnp.lesson_id = ? 
            AND homework_content_lnp.teacher_id = ? 
            ORDER BY homework_content_lnp.id DESC');

	if (!$stmt->execute([$userInfo['school_id'], $userInfo['class_id'], $userInfo['lesson_id'], $userInfo['id']])) {
		$stmt = null;
		exit();
	}


	$homeworkList = $stmt->fetchAll(PDO::FETCH_ASSOC);


	function getUnits($school_id, $class_id, $lesson_id)
	{
		$db = (new dbh())->connect();
		$sql = 'SELECT * FROM units_lnp WHERE school_id=? AND class_id=? AND lesson_id=? ORDER BY created_at DESC';

		try {
			$stmt = $db->prepare($sql);
			$stmt->execute([$school_id, $class_id, $lesson_id]);
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $result;

		} catch (PDOException $e) {
			return false;
		}
	}
	function getTopics($school_id, $class_id, $lesson_id)
	{
		$db = (new dbh())->connect();
		$sql = 'SELECT * FROM topics_lnp WHERE school_id=? AND class_id=? AND lesson_id=? ORDER BY created_at DESC';

		try {
			$stmt = $db->prepare($sql);
			$stmt->execute([$school_id, $class_id, $lesson_id]);
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		} catch (PDOException $e) {
			return false;
		}
	}
	function getSubtopics($school_id, $class_id, $lesson_id)
	{
		$db = (new dbh())->connect();
		$sql = 'SELECT * FROM subtopics_lnp WHERE school_id=? AND class_id=? AND lesson_id=? ORDER BY created_at DESC';

		try {
			$stmt = $db->prepare($sql);
			$stmt->execute([$school_id, $class_id, $lesson_id]);
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $result;

		} catch (PDOException $e) {
			return false;
		}
	}
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
												<li class="breadcrumb-item text-gray-700">Öğretmen Detay</li>
												<!--end::Item-->
											</ul>
											<!--end::Breadcrumb-->
											<!--begin::Title-->
											<h1
												class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bolder fs-1 lh-0">
												Öğretmen Detay</h1>
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
														<img src="assets/media/profile/<?php echo $userInfo['photo'] ?>"
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
																	class="text-gray-900 text-hover-primary fs-2 fw-bold me-1"><?php echo $userInfo['name'] . ' ' . $userInfo['surname'] ?>
																</a>
															</div>
															<!--end::Name-->
															<!--begin::Info-->
															<div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
																<span
																	class="d-flex align-items-center text-gray-500 me-5 mb-2">
																	<i class="fa-solid fa-school fs-4 me-1"></i>
																	<?php echo $userInfo['schoolName']; ?>
																</span>
																<span
																	class="d-flex align-items-center text-gray-500 me-5 mb-2">
																	<i class="fa-solid fa-table fs-4 me-1"></i>
																	<?php echo $userInfo['className'] ?? "-"; ?>
																</span>
																<span
																	class="d-flex align-items-center text-gray-500 me-5 mb-2">
																	<i class="fa-solid fa-book fs-4 me-1"></i>
																	<?php echo $userInfo['lessonName'] ?? "-"; ?>
																</span>
																<a href="tel:<?php echo $userInfo['telephone']; ?>"
																	class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
																	<i class="fa-solid fa-phone fs-4 me-1"></i>
																	<?php echo $userInfo['telephone']; ?>
																</a>
																<a href="mailto:<?php echo $userInfo['email']; ?>"
																	class="d-flex align-items-center text-gray-500 text-hover-primary mb-2">
																	<i class="ki-duotone ki-sms fs-4 me-1">
																		<span class="path1"></span>
																		<span class="path2"></span>
																	</i>
																	<?php echo $userInfo['email']; ?>
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
																			data-kt-countup-value="<?php echo count($studentList); ?>">
																			0</div>
																	</div>
																	<!--end::Number-->
																	<!--begin::Label-->
																	<div class="fw-semibold fs-6 text-gray-500">Ögrenci
																		Sayısı</div>
																	<!--end::Label-->
																</div>
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
																			data-kt-countup-value="<?php //echo count($studentAdditionalPackages); ?>">
																			0</div>
																	</div>
																	<!--end::Number-->
																	<!--begin::Label-->
																	<div class="fw-semibold fs-6 text-gray-500"> Sayısı
																	</div>
																	<!--end::Label-->
																</div>
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
														data-bs-toggle="pill" href="#ogrenciler">Öğrenciler</a>
												</li>
												<!--end::Nav item-->
												<!--begin::Nav item-->
												<li class="nav-item mt-2">
													<a class="nav-link text-active-primary ms-0 me-10 py-5"
														data-bs-toggle="pill" href="#ders">Ders Detayı</a>
												</li>
												<!--end::Nav item-->
												<!--begin::Nav item-->
												<li class="nav-item mt-2">
													<a class="nav-link text-active-primary ms-0 me-10 py-5"
														data-bs-toggle="pill" href="#testler">Testler</a>
												</li>
												<!--end::Nav item-->
												<!--begin::Nav item-->
												<li class="nav-item mt-2">
													<a class="nav-link text-active-primary ms-0 me-10 py-5"
														data-bs-toggle="pill" href="#odevler">Ödevler</a>
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
													<div class="card mb-5 mb-xl-8" id="kt_timeline_widget_2_card">
														<!--begin::Header-->
														<div class="card-header position-relative py-0 border-bottom-2">
															<!--begin::Nav-->
															<ul
																class="nav nav-stretch nav-pills nav-pills-custom d-flex mt-3">
																<li class="nav-item p-0 ms-0 me-8">
																	<a class="nav-link btn btn-color-muted active px-0"
																		data-bs-toggle="pill"
																		href="#kt_timeline_widget_2_tab_1">
																		<span class="nav-text fw-semibold fs-4 mb-3">
																			Ödevler</span>

																		<span
																			class="bullet-custom position-absolute z-index-2 w-100 h-2px top-100 bottom-n100 bg-primary rounded"></span>
																	</a>
																</li>
																<li class="nav-item p-0 ms-0 me-8">
																	<a class="nav-link btn btn-color-muted px-0"
																		data-bs-toggle="pill"
																		href="#kt_timeline_widget_2_tab_2">
																		<span
																			class="nav-text fw-semibold fs-4 mb-3">Testler</span>

																		<span
																			class="bullet-custom position-absolute z-index-2 w-100 h-2px top-100 bottom-n100 bg-primary rounded"></span>
																	</a>
																</li>
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
																	<div class="table-responsive">
																		<!--begin::Table-->
																		<table class="table align-middle gs-0 gy-4">
																			<!--begin::Table head-->
																			<thead>
																				<tr>
																					<th class="p-0 w-10px"></th>
																					<th class="p-0 min-w-400px"></th>
																				</tr>
																			</thead>
																			<!--end::Table head-->
																			<!--begin::Table body-->
																			<tbody>
																				<?php
																				$homeworkListcounter = 0;
																				foreach ($homeworkList as $key => $value) {
																					if ($homeworkListcounter >= 5) {
																						break;
																					}

																					if ($value['active'] == 1) {
																						$aktifYazi = 'success';
																					} else {
																						$aktifYazi = 'primary';
																					}
																					$item = '
																							<tr>
																								<td>
																									<span data-kt-element="bullet"
																										class="bullet bullet-vertical d-flex align-items-center h-40px bg-' . $aktifYazi . '"></span>
																								</td>
																								<td>
																									<a href="#"
																										class="text-gray-800 text-hover-primary fw-bold fs-6">
																										' . $value['title'] . '
																									</a>
																								</td>
																							</tr>
																						';
																					echo $item;
																					$homeworkListcounter++;
																				}
																				?>

																			</tbody>
																			<!--end::Table body-->
																		</table>
																	</div>
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
																					<th class="p-0 min-w-400px"></th>
																				</tr>
																			</thead>
																			<!--end::Table head-->
																			<!--begin::Table body-->
																			<tbody>
																				<?php
																				$testListcounter = 0;
																				foreach ($testList as $key => $value) {
																					if ($testListcounter >= 5) {
																						break;
																					}
																					if ($value['status'] == 1) {
																						$aktifYazi = 'success';
																					} else {
																						$aktifYazi = 'primary';
																					}
																					$item = '
																						<tr>
																							<td>
																								<span data-kt-element="bullet"
																									class="bullet bullet-vertical d-flex align-items-center h-40px bg-' . $aktifYazi . '"></span>
																							</td>
																							<td>
																								<a href="#"
																									class="text-gray-800 text-hover-primary fw-bold fs-6">
																									' . $value['test_title'] . '
																								</a>
																							</td>
																						</tr>
																					';
																					echo $item;
																					$testListcounter++;
																				}
																				?>

																			</tbody>
																			<!--end::Table body-->
																		</table>
																	</div>
																	<!--end::Table-->
																</div>

															</div>
															<!--end::Tab Content-->
														</div>
														<!--end::Body-->
													</div>
													<div class="card mb-5 mb-xl-8">
														<!--begin::Header-->
														<div class="card-header border-0 pt-5">
															<h3 class="card-title align-items-start flex-column">
																<span class="card-label fw-bold text-gray-900">En Son Eklenen Üniteler</span>
																<span
																	class="text-muted mt-1 fw-semibold fs-7"><?php //echo $studentClassName[0]['name']; ?></span>
															</h3>
														</div>
														<div class="card-body pt-6">
															<!--begin::Table-->
															<table class="table align-middle table-row-dashed fs-6 gy-5">
																<!--begin::Table head-->
																<thead>
																	<tr>
																		<th class="p-0 "></th>
																	</tr>
																</thead>
																<tbody class="fw-semibold text-gray-600">

																	<?php
																	$curItems2 = getUnits($userInfo['school_id'], $userInfo['class_id'], $userInfo['lesson_id']);
																	$counter2 = 0;
																	$styles = ["danger", "success", "primary", "warning", "info", "secondary", "light", "dark"];
																	$styleIndex = 0;

																	$style = $styles[$styleIndex % count($styles)];

																	foreach ($curItems2 as $item) {
																		if ($counter2 >= 5) {
																			break;
																		}
																		$view = '';
																		$view .= '
																		<div class="d-flex flex-stack">
																			<div class="symbol symbol-40px me-4">
																				<div class="symbol-label fs-2 fw-semibold bg-' . $style . ' text-inverse-' . $style . '">' . mb_substr($item['name'], 0, 1, 'UTF-8') . '</div>
																			</div>
																			<div class="d-flex align-items-center flex-row-fluid ">
																				<div class="flex-grow-1 me-2">
																					<a  class="text-gray-800 text-hover-primary fs-6 fw-bold">' . $item['name'] . '</a>
																				</div>
																			</div>
																		</div>
																		<div class="separator separator-dashed my-4"></div>';

																		echo $view;
																		$counter2++;

																	}
																	?>
																</tbody>
															</table>

														</div>
													</div>
													<div class="card mb-5 mb-xl-8">
														<!--begin::Header-->
														<div class="card-header border-0 pt-5">
															<h3 class="card-title align-items-start flex-column">
																<span class="card-label fw-bold text-gray-900">En Son Eklenen Konu</span>
																<span
																	class="text-muted mt-1 fw-semibold fs-7"><?php //echo $studentClassName[0]['name']; ?></span>
															</h3>
														</div>
														<div class="card-body pt-6">
															<!--begin::Table-->
															<table class="table align-middle table-row-dashed fs-6 gy-5">
																<!--begin::Table head-->
																<thead>
																	<tr>
																		<th class="p-0 "></th>
																	</tr>
																</thead>
																<tbody class="fw-semibold text-gray-600">

																	<?php
																	$curItems2 = getTopics($userInfo['school_id'], $userInfo['class_id'], $userInfo['lesson_id']);
																	$counter2 = 0;
																	$styles = ["danger", "success", "primary", "warning", "info", "secondary", "light", "dark"];
																	$styleIndex = 0;

																	$style = $styles[$styleIndex % count($styles)];

																	foreach ($curItems2 as $item) {
																		if ($counter2 >= 5) {
																			break;
																		}
																		$view = '';
																		$view .= '
																		<div class="d-flex flex-stack">
																			<div class="symbol symbol-40px me-4">
																				<div class="symbol-label fs-2 fw-semibold bg-' . $style . ' text-inverse-' . $style . '">' . mb_substr($item['name'], 0, 1, 'UTF-8') . '</div>
																			</div>
																			<div class="d-flex align-items-center flex-row-fluid ">
																				<div class="flex-grow-1 me-2">
																					<a  class="text-gray-800 text-hover-primary fs-6 fw-bold">' . $item['name'] . '</a>
																				</div>
																			</div>
																		</div>
																		<div class="separator separator-dashed my-4"></div>';

																		echo $view;
																		$counter2++;

																	}
																	?>
																</tbody>
															</table>

														</div>
													</div>
													<div class="card mb-5 mb-xl-8">
														<!--begin::Header-->
														<div class="card-header border-0 pt-5">
															<h3 class="card-title align-items-start flex-column">
																<span
																	class="card-label fw-bold text-gray-900">En Son Eklenen Altkonu</span>
																<span
																	class="text-muted mt-1 fw-semibold fs-7"><?php //echo $studentClassName[0]['name']; ?></span>
															</h3>
														</div>
														<div class="card-body pt-6">
															<!--begin::Table-->
															<table class="table align-middle table-row-dashed fs-6 gy-5">
																<!--begin::Table head-->
																<thead>
																	<tr>
																		<th class="p-0 "></th>
																	</tr>
																</thead>
																<tbody class="fw-semibold text-gray-600">

																	<?php
																	$curItems2 = getSubtopics($userInfo['school_id'], $userInfo['class_id'], $userInfo['lesson_id']);
																	$counter2 = 0;
																	$styles = ["danger", "success", "primary", "warning", "info", "secondary", "light", "dark"];
																	$styleIndex = 0;

																	$style = $styles[$styleIndex % count($styles)];

																	foreach ($curItems2 as $item) {
																		if ($counter2 >= 5) {
																			break;
																		}
																		$view = '';
																		$view .= '
																		<div class="d-flex flex-stack">
																			<div class="symbol symbol-40px me-4">
																				<div class="symbol-label fs-2 fw-semibold bg-' . $style . ' text-inverse-' . $style . '">' . mb_substr($item['name'], 0, 1, 'UTF-8') . '</div>
																			</div>
																			<div class="d-flex align-items-center flex-row-fluid ">
																				<div class="flex-grow-1 me-2">
																					<a  class="text-gray-800 text-hover-primary fs-6 fw-bold">' . $item['name'] . '</a>
																				</div>
																			</div>
																		</div>
																		<div class="separator separator-dashed my-4"></div>';

																		echo $view;
																		$counter2++;

																	}
																	?>
																</tbody>
															</table>

														</div>
													</div>
												</div>
												<!--end::Col-->
												<!--begin::Col-->
												<div class="col-xl-6">
													<div class="card card-flush mb-5 mb-xl-8">
														<!--begin::Header-->
														<div class="card-header pt-7">
															<!--begin::Title-->
															<h3 class="card-title align-items-start flex-column">
																<span
																	class="card-label fw-bold text-gray-800">Ögrenciler</span>
															</h3>
															<!--end::Title-->
															<!--begin::Toolbar-->
															<div class="card-toolbar"></div>
															<!--end::Toolbar-->
														</div>
														<!--end::Header-->
														<!--begin::Body-->
														<div class="card-body pt-5">
															<div class="scrollable-div">
																<?php
																foreach ($studentList as $student) {
																	$percentage = $contentObj->getSchoolContentAnalyticsOverall($student['id']);
																	$percentageW = ($percentage == null) ? 0 : $percentage;
																	$percentageT = ($percentage === null) ? '-' : $percentage;

																	$score = $gradeObj->getGradeOverall($student['id'], );
																	$scoreW = ($score == null) ? 0 : $score;
																	$scoreT = ($score ===  null) ? '-' : $score;

																	$studentRow = '
																		<div class="">

																			<div class="d-flex flex-stack">
																				<div class="d-flex align-items-center me-5">
																					<div class="cursor-pointer symbol symbol-40px symbol-lg-55px me-5"><img src="assets/media/profile/' . $student['photo'] . '"></div>


																					<div class="me-5">
																						<a href="#"
																							class="text-gray-800 fw-bold text-hover-primary fs-6">' . $student['name'] . ' ' . $student['surname'] . '</a>

		
																					</div>
																				</div>
																				<div class="d-flex align-items-center w-100px w-sm-200px flex-column mt-3">
																					<div class="d-flex justify-content-between w-100 mt-auto mb-2">
																						<span class="fw-semibold fs-6 text-gray-500">Tamamlama Oranı</span>
																						<span class="fw-bold fs-6">' . $percentageT . '%</span>
																					</div>
																					<div class="h-5px mx-3 w-100 bg-light mb-3">  
																						<div class="bg-success rounded h-5px" role="progressbar" style="width: ' . $percentageW . '%;" aria-valuenow="25"
																							aria-valuemin="0" aria-valuemax="100"></div>
																					</div>
																					<div class="d-flex justify-content-between w-100 mt-auto mb-2">
																						<span class="fw-semibold fs-6 text-gray-500">Başarı Oranı</span>
																						<span class="fw-bold fs-6">' . $scoreT . '%</span>
																					</div>
																					<div class="h-5px mx-3 w-100 bg-light mb-3">  
																						<div class="bg-success rounded h-5px" role="progressbar" style="width: ' . $scoreW . '%;" aria-valuenow="25"
																							aria-valuemin="0" aria-valuemax="100"></div>
																					</div>
																				</div>
																			</div>
																			<div class="separator separator-dashed my-3"></div>
																		</div>

																	';
																	echo $studentRow;
																}
																?>
															</div>

														</div>
														<!--end: Card Body-->
													</div>

												</div>
												<!--end::Col-->
											</div>
										</div>
										<div class="tab-pane fade " id="ogrenciler">

											<div id="kt_app_content_container" class="app-content flex-column-fluid">
												<div class="card">
													<!--begin::Card header-->
													<div class="card-header border-0 pt-6">
														<!--begin::Card title-->
														<div class="card-title">
															<!--begin::Search-->
															<div class="d-flex align-items-center position-relative my-1">
																<i
																	class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
																	<span class="path1"></span>
																	<span class="path2"></span>
																</i>
																<input type="text" data-kt-customer-table-filter="search"
																	class="form-control form-control-solid w-250px ps-12"
																	placeholder="Öğrenci Ara" />
															</div>
															<!--end::Search-->
														</div>
														<!--begin::Card title-->
														<!--begin::Card toolbar-->
														<div class="card-toolbar">
															<!--begin::Toolbar-->
															<div class="d-flex justify-content-end"
																data-kt-customer-table-toolbar="base">
																<!--begin::Add school-->
															</div>
															<!--end::Toolbar-->
															<!--begin::Group actions-->
															<div class="d-flex justify-content-end align-items-center d-none"
																data-kt-customer-table-toolbar="selected">
																<div class="fw-bold me-5">
																	<span class="me-2"
																		data-kt-customer-table-select="selected_count"></span>Seçildi
																</div>
																<button type="button" class="btn btn-danger"
																	data-kt-customer-table-select="delete_selected">Seçilenleri
																	Pasif Yap</button>
															</div>
															<!--end::Group actions-->
														</div>
														<!--end::Card toolbar-->
													</div>
													<!--end::Card header-->
													<!--begin::Card body-->
													<div class="card-body pt-0">
														<!--begin::Table-->
														<table class="table align-middle table-row-dashed fs-6 gy-5"
															id="kt_customers_table">
															<thead>
																<tr
																	class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">

																	<th class="min-w-125px">Fotoğraf</th>
																	<th class="min-w-125px">Öğrenci Adı</th>
																	<th class="min-w-125px">E-posta Adresi</th>
																	<th class="min-w-30px">Başarı Oranı</th>
																	<th class="min-w-30px">Tamamlama Oranı</th>

																</tr>
															</thead>
															<tbody class="fw-semibold text-gray-600">
																<?php
																require_once "classes/content-tracker.classes.php";
																require_once "classes/grade-result.classes.php";

																$contentObj = new ContentTracker();
																$gradeObj = new GradeResult();

																foreach ($studentList as $student) {
																	$percentage = $contentObj->getSchoolContentAnalyticsOverall($student['id']);
																	$percentageW = ($percentage == null) ? 0 : $percentage;
																	$percentageT = ($percentage === null) ? '-' : $percentage;

																	$score = $gradeObj->getGradeOverall($student['id'], );
																	$scoreW = ($score == null) ? 0 : $score;
																	$scoreT = ($score ===  null) ? '-' : $score;

																	$studentRow = '
																		<tr>
																			<td>
																				<div class="cursor-pointer symbol symbol-90px symbol-lg-90px"><img src="assets/media/profile/' . $student['photo'] . '"></div>
																			</td>
																			<td>
																				<a href="./ogrenci-detay/' . $student['username'] . '" class="text-gray-800 text-hover-primary mb-1">' . $student['name'] . ' ' . $student['surname'] . '</a>
																			</td>
																			<td>
																				<a href="mailto:' . $student['email'] . '" class="text-gray-800 text-hover-primary mb-1">' . $student['email'] . '</a>
																			</td>
																			<td>
																				<span class="text-gray-800 text-hover-primary mb-1"> ' . $percentageT . '</span>
																			</td>
																			<td>
																				<span class="text-gray-800 text-hover-primary mb-1">' . $scoreT . ' </span>
																			</td>
																		</tr>
																	';
																	echo $studentRow;
																}
																?>
															</tbody>
														</table>
														<!--end::Table-->
													</div>
													<!--end::Card body-->
												</div>
											</div>
											<!--end::Grup Dersler-->
										</div>
										<div class="tab-pane fade " id="ders">
											<div class="row g-5 g-xxl-8">


												<div class="col-xl-6">
													<div class="card mb-5 mb-xl-8">
														<div class="card-header border-0 pt-5">
															<h3 class="card-title align-items-start flex-column">
																<span class="card-label fw-bold text-gray-900">En Son Eklenen 
																	<?= $userInfo['lessonName'] ?> </span>
															</h3>
														</div>

														<div class="card-body pt-6">
															<form class="form" action="#" id="kt_form_curriculum">
																<input type="hidden" id="classes" name="classes"
																	value="<?= $userInfo['class_id'] ?>">
																<input type="hidden" id="lessons" name="lessons"
																	value="<?= $userInfo['lesson_id'] ?>">
																<input type="hidden" id="student_id" name="student_id"
																	value="<?= $userInfo['id'] ?>">
																<input type="hidden" id="school_id" name="school_id"
																	value="<?= $userInfo['school_id'] ?>">


																<div class="row mt-4">

																	<div class="col-lg-10">
																		<label class="fs-6 fw-semibold mb-2"
																			for="units">Ünite
																		</label>
																		<select class="form-select" id="units" name="units"
																			aria-label="Ünite Seçiniz">
																			<option value="">Seçiniz</option>
																			<?php
																			include_once "classes/units.classes.php";
																			include_once "classes/units-view.classes.php";
																			$chooseUnit = new ShowUnit();

																			$units = $chooseUnit->getUnitByLessonId($userInfo['school_id'], $userInfo['class_id'], $userInfo['lesson_id']);
																			foreach ($units as $unitOption) {
																				echo '<option value="' . $unitOption["id"] . '">' . $unitOption["name"] . '</option>';
																			}
																			?>
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

																	<button type="submit" id="kt_form_curriculum_submit"
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
										<div class="tab-pane fade " id="testler">
											<div id="kt_app_content_container" class="app-content flex-column-fluid">
												<div class="card">
													<div class="card-header border-0 pt-6">
														<div class="card-title">
															<div class="d-flex align-items-center position-relative my-1">
																<i
																	class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
																	<span class="path1"></span>
																	<span class="path2"></span>
																</i>
																<input type="text" data-kt-test-table-filter="search"
																	class="form-control form-control-solid w-250px ps-12"
																	placeholder="Test Ara" />
															</div>
														</div>
													</div>

													<div class="card-body pt-0">
														<table class="table align-middle table-row-dashed fs-6 gy-5"
															id="kt_test_table">
															<thead>
																<tr
																	class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">

																	<th class="min-w-125px">Test</th>
																	<th class="min-w-125px">Alt Konu</th>
																	<th class="min-w-125px">Konu</th>
																	<th class="min-w-125px">Ünite</th>
																	<th class="min-w-125px">Durum</th>
																</tr>
															</thead>
															<tbody class="fw-semibold text-gray-600">
																<?php



																foreach ($testList as $key => $value) {


																	$subTopicName = $value['subTopicName'] ?? '-';
																	$topicName = $value['topicName'] ?? '-';

																	$unitName = $value['unitName'] ?? '-';

																	if ($value['status'] == 1) {
																		$aktifYazi = '<span class="badge badge-light-success">Aktif</span>';
																	} else {
																		$aktifYazi = '<span class="badge badge-light-danger">Pasif</span>';
																	}

																	$contentList = '
																			<tr>
																				<td data-file-id="' . $value['id'] . '">
																					<a href="./test-detay/' . $value['id'] . '" class="text-gray-800 text-hover-primary mb-1">' . $value['test_title'] . '</a>
																				</td>
																				<td>
																				' . $subTopicName . '
																				</td>
																				<td>
																					' . $topicName . '
																				</td>
																				<td>
																					' . $unitName . '
																				</td>
																				<td>' . $aktifYazi . '</td>
															
																			</tr>
																			';
																	echo $contentList;
																}

																?>
															</tbody>
														</table>
													</div>
												</div>

											</div>
										</div>
										<div class="tab-pane fade " id="odevler">
											<div id="kt_app_content_container" class="app-container container-fluid">
												<div class="card">
													<div class="card-header border-0 pt-6">
														<div class="card-title">
															<div class="d-flex align-items-center position-relative my-1">
																<i
																	class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
																	<span class="path1"></span>
																	<span class="path2"></span>
																</i>
																<input type="text" data-kt-odev-table-filter="search"
																	class="form-control form-control-solid w-250px ps-12"
																	placeholder="Ödev Ara" />
															</div>
														</div>
													</div>

													<div class="card-body pt-0">
														<table class="table align-middle table-row-dashed fs-6 gy-5"
															id="kt_odev_table">
															<thead>
																<tr
																	class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">

																	<th class="min-w-125px">Ödev</th>
																	<th class="min-w-125px">Alt Konu</th>
																	<th class="min-w-125px">Konu</th>
																	<th class="min-w-125px">Ünite</th>
																	<th class="min-w-125px">Durum</th>
																</tr>
															</thead>
															<tbody class="fw-semibold text-gray-600">

																<?php
																foreach ($homeworkList as $key => $value) {


																	$subTopicName = $value['subTopicName'] ?? '-';

																	if ($value['active'] == 1) {
																		$aktifYazi = '<span class="badge badge-light-success">Aktif</span>';
																	} else {
																		$aktifYazi = '<span class="badge badge-light-danger">Pasif</span>';
																	}

																	$contentList = '
																			<tr>
																				<td data-file-id="' . $value['id'] . '">
																					<a href="./odev-detay/' . $value['slug'] . '" class="text-gray-800 text-hover-primary mb-1">' . $value['title'] . '</a>
																				</td>
																				<td>
																				' . $subTopicName . '
																				</td>
																				<td>
																					' . $value['topicName'] . '
																				</td>
																				<td>
																					' . $value['unitName'] . '
																				</td>
																				<td>' . $aktifYazi . '</td>

																			</tr>
																		';
																	echo $contentList;
																}

																// $homework->getHomeworksViewByLessonId($userInfo['school_id'], $userInfo['class_id'], $userInfo['lesson_id'], $userInfo['id']); ?>
															</tbody>
														</table>
													</div>
												</div>

											</div>
										</div>
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
																<?php $studentObj->showLoginDetailsListForStudentDetails($userInfo['id']); ?>
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

		<!--begin::Scrolltop-->
		<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
			<i class="ki-duotone ki-arrow-up">
				<span class="path1"></span>
				<span class="path2"></span>
			</i>
		</div>
		<!--end::Scrolltop-->

		<script>
			var hostUrl = "assets/";
		</script>
		<!--begin::Global Javascript Bundle(mandatory for all pages)-->
		<script src="assets/plugins/global/plugins.bundle.js"></script>
		<script src="assets/js/scripts.bundle.js"></script>
		<!--end::Global Javascript Bundle-->
		<!--begin::Vendors Javascript(used for this page only)-->
		<script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
		<script src="assets/js/custom/apps/teacher-details/list/export.js"></script>
		<script src="assets/js/custom/apps/teacher-details/list/list.js"></script>
		<!--end::Vendors Javascript-->
		<!--begin::Custom Javascript(used for this page only)-->
		<script src="assets/js/custom/pages/user-profile/general.js"></script>
		<script src="assets/js/widgets.bundle.js"></script>
		<script src="assets/js/custom/widgets.js"></script>
		<script src="assets/js/custom/apps/chat/chat.js"></script>
		<!--end::Custom Javascript-->
		<!--end::Javascript-->
		<script src="assets/js/custom/apps/teacher-details/curriculum.js"></script>
		<script src="assets/js/custom/apps/teacher-details/list_odev.js"></script>
		<script src="assets/js/custom/apps/teacher-details/list_test.js"></script>

	</body>
	<!--end::Body-->

	</html>
<?php } else {
	header("location: ../index");
}
