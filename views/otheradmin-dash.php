<?php

include_once "classes/dateformat.classes.php";
include_once "classes/student.classes.php";
include_once "classes/packages.classes.php";
include_once "classes/dashes.classes.php";

require_once "classes/content-tracker.classes.php";
require_once "classes/grade-result.classes.php";

$contentObj = new ContentTracker();
$gradeObj = new GradeResult();

$dateFormat = new DateFormat();
$students = new Student();
$packages = new PackagesForAdmin();
$tests = new Dashes();

$getAllStudents = $students->getStudentsList();
$getActiveStudents = $students->getActiveStudents();
$getPassiveStudents = $students->getPassiveStudents();

$getAllPackages = $packages->getAllPackages();
$getMostUsedPackages = $packages->getMostUsedPackage();

$getTests = $tests->getTests();
$getHomeworks = $tests->getHomeworks();
$getPayments = $tests->getPayments();

$getHighestScoreStudents = $gradeObj->getHighestGradeOverall("1");

$getHighestTimespent = $contentObj->getTimeSpentByStudents("1");

$totalClasses = $contentObj->getClassesBySchool("1");
$totalLessons = $contentObj->getLessonsBySchool("1");
$totalUnits = $contentObj->getUnitsBySchool("1");
$totalTopics = $contentObj->getTopicsBySchool("1");
$totalSubtopis = $contentObj->getSubtopicsBySchool("1");
$totalGames = $contentObj->getGamesBySchool("1");
$totalBooks = $contentObj->getBooksBySchool("1");

$totalContents = $contentObj->getContentsBySchool("1");

$testsHigh = $contentObj->getExamsWithHighestScore("1");
$testsLow = $contentObj->getExamsWithLowestScore("1");

// $subsState = $contentObj->getSubscriptionState("1");

$subscriptionStats = $contentObj->getSubscriptionState("1");

/*
$getHighestScoreStudents = $gradeObj->getHighestGradeOverall($_SESSION['school_id']);
$getHighestAnaStudents = $contentObj->getHighestAnalyticsOverall($_SESSION['school_id']);
$getHighestTimespent = $contentObj->getTimeSpentByStudents($_SESSION['school_id']);

$totalClasses = $contentObj->getClassesBySchool($_SESSION['school_id']);
$totalLessons = $contentObj->getLessonsBySchool($_SESSION['school_id']);
$totalUnits = $contentObj->getUnitsBySchool($_SESSION['school_id']);
$totalTopics = $contentObj->getTopicsBySchool($_SESSION['school_id']);
$totalSubtopis = $contentObj->getSubtopicsBySchool($_SESSION['school_id']);
$totalGames = $contentObj->getGamesBySchool($_SESSION['school_id']);
$totalBooks = $contentObj->getBooksBySchool($_SESSION['school_id']);

$totalContents = $contentObj->getContentsBySchool($_SESSION['school_id']);

$testsHigh = $contentObj->getExamsWithHighestScore($_SESSION['school_id']);
$testsLow = $contentObj->getExamsWithLowestScore($_SESSION['school_id']);

// $subsState = $contentObj->getSubscriptionState($_SESSION['school_id']);

$subscriptionStats = $contentObj->getSubscriptionState($_SESSION['school_id']);
*/

function prepareChartData($data, $type)
{
	$labels = [];
	$values = [];
	$total = 0;

	foreach ($data as $entry) {
		if ($type === 'week') {
			$labels[] = 'Hafta ' . $entry['week'];
			$values[] = (int) $entry['count'];
		} elseif ($type === 'month') {
			$monthNames = [1 => 'Ocak', 'Şubat', 'Mar', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'];
			$labels[] = $monthNames[(int) $entry['month']];
			$values[] = (int) $entry['count'];
		} elseif ($type === 'year') {
			$labels[] = $entry['year'];
			$values[] = (int) $entry['count'];
		}
		$total += (int) $entry['count'];
	}

	return [
		'labels' => $labels,
		'data' => $values,
		'total' => $total
	];
}

$chartData = [
	'week' => prepareChartData($subscriptionStats['weekly'], 'week'),
	'month' => prepareChartData($subscriptionStats['monthly'], 'month'),
	'year' => prepareChartData($subscriptionStats['yearly'], 'year')
];
?>
<div id="kt_app_content_container" class="app-container container-fluid">
	<!--begin::Row-->
	<div class="row gx-5 gx-xl-10">

		<div>
			<div class="d-flex flex-wrap flex-stack">
				<div class="d-flex flex-column flex-grow-1 pe-8">
					<div class="d-flex flex-wrap">

						<div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
							<div class="d-flex align-items-center">
								<i class="fa-regular fa-clock fs-2 text-success me-2"></i>
								<div class="fs-2 fw-bold">
									<?php echo $totalClasses['total']; ?>
								</div>
							</div>
							<div class="fw-semibold fs-6 text-gray-500">Toplam
								Sınıf Sayısı</div>
						</div>
						<div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
							<div class="d-flex align-items-center">
								<i class="ki-duotone ki-book-open fs-2 text-success me-2">
									<span class="path1"></span>
									<span class="path2"></span>
									<span class="path3"></span>
									<span class="path4"></span>
								</i>
								<div class="fs-2 fw-bold" data-kt-countup="true"
									data-kt-countup-value="	<?php echo $totalLessons['total']; ?>">
									0
								</div>
							</div>

							<div class="fw-semibold fs-6 text-gray-500">Toplam
								Ders Sayısı</div>
						</div>
						<div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
							<div class="d-flex align-items-center">
								<i class="ki-duotone ki-book-open fs-2 text-success me-2">
									<span class="path1"></span>
									<span class="path2"></span>
									<span class="path3"></span>
									<span class="path4"></span>
								</i>
								<div class="fs-2 fw-bold" data-kt-countup="true"
									data-kt-countup-value="	<?php echo $totalUnits['total']; ?>">
									0
								</div>
							</div>

							<div class="fw-semibold fs-6 text-gray-500">Toplam
								Ünite Sayısı</div>
						</div>
						<div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
							<div class="d-flex align-items-center">
								<i class="ki-duotone ki-book-open fs-2 text-success me-2">
									<span class="path1"></span>
									<span class="path2"></span>
									<span class="path3"></span>
									<span class="path4"></span>
								</i>
								<div class="fs-2 fw-bold" data-kt-countup="true"
									data-kt-countup-value="	<?php echo $totalTopics['total']; ?>">
									0
								</div>
							</div>

							<div class="fw-semibold fs-6 text-gray-500">Toplam
								Konu Sayısı</div>
						</div>
						<div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
							<div class="d-flex align-items-center">
								<i class="ki-duotone ki-book-open fs-2 text-success me-2">
									<span class="path1"></span>
									<span class="path2"></span>
									<span class="path3"></span>
									<span class="path4"></span>
								</i>
								<div class="fs-2 fw-bold" data-kt-countup="true"
									data-kt-countup-value="	<?php echo $totalSubtopis['total']; ?>">
									0
								</div>
							</div>

							<div class="fw-semibold fs-6 text-gray-500">Toplam
								Altkonu Sayısı</div>
						</div>
						<div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
							<div class="d-flex align-items-center">
								<i class="ki-duotone ki-book-open fs-2 text-success me-2">
									<span class="path1"></span>
									<span class="path2"></span>
									<span class="path3"></span>
									<span class="path4"></span>
								</i>
								<div class="fs-2 fw-bold" data-kt-countup="true"
									data-kt-countup-value="	<?php echo $totalBooks['total']; ?>">
									0
								</div>
							</div>

							<div class="fw-semibold fs-6 text-gray-500">Toplam
								Sesli kitap Sayısı</div>
						</div>
						<div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
							<div class="d-flex align-items-center">
								<i class="ki-duotone ki-brifecase-tick fs-2 text-success me-2">
									<span class="path1"></span>
									<span class="path2"></span>
									<span class="path3"></span>
								</i>
								<div class="fs-2 fw-bold" data-kt-countup="true"
									data-kt-countup-value="	<?php echo $totalGames['total']; ?>">
									0</div>
							</div>

							<div class="fw-semibold fs-6 text-gray-500">Toplam
								Oyun Sayısı</div>
						</div>
						<div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
							<div class="d-flex align-items-center">
								<i class="ki-duotone ki-brifecase-tick fs-2 text-success me-2">
									<span class="path1"></span>
									<span class="path2"></span>
									<span class="path3"></span>
								</i>
								<div class="fs-2 fw-bold" data-kt-countup="true"
									data-kt-countup-value="	<?php echo $totalContents['total']; ?>">
									0</div>
							</div>

							<div class="fw-semibold fs-6 text-gray-500">Toplam
								İçerik Sayısı</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xxl-8 mb-5 mb-xl-10">

			<div class="container-fluid py-4">
				<div class="row">
					<div class="col-12">
						<div class="card">
							<div class="card-header">
								<div class="d-flex justify-content-between align-items-center">
									<h3 class="card-title mb-0">
										<i class="fas fa-chart-line text-primary me-2"></i>
										Abonelik Analitiği
									</h3>
									<div class="btn-group" role="group">
										<button type="button" class="btn btn-sm active" data-period="week">
											<i class="fas fa-calendar-week me-1"></i>Haftalık
										</button>
										<button type="button" class="btn btn-sm" data-period="month">
											<i class="fas fa-calendar-alt me-1"></i>Aylık
										</button>
										<button type="button" class="btn btn-sm" data-period="year">
											<i class="fas fa-calendar me-1"></i>yıllık
										</button>
									</div>
								</div>
							</div>
							<div class="card-body">
								<div class="row mb-4">
									<div class="col-md-4">
										<div class="stats-card">
											<div class="stats-value" id="totalSubscriptions">0</div>
											<div class="stats-label">Toplam Abonelikler</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="stats-card"
											style="background: linear-gradient(135deg, var(--kt-success), #2fb344);">
											<div class="stats-value" id="growthRate">0%</div>
											<div class="stats-label">Büyüme Oranı</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="stats-card"
											style="background: linear-gradient(135deg, var(--kt-info), #5014d0);">
											<div class="stats-value" id="avgPerPeriod">0</div>
											<div class="stats-label">Dönem Başına Ortalama</div>
										</div>
									</div>
								</div>

								<div class="chart-container">
									<canvas id="subscriptionChart"></canvas>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--begin::Col-->
		<div class="col-xxl-6 mb-5 mb-xl-10">
			<!--begin::Chart widget 8-->
			<div class="card card-flush h-xl-100">
				<!--begin::Header-->
				<div class="card-header pt-5">
					<!--begin::Title-->
					<h3 class="card-title align-items-start flex-column">
						<span class="card-label fw-bold text-gray-900">Öğrenci Sayısı</span>
					</h3>
					<!--end::Title-->
				</div>
				<!--end::Header-->
				<!--begin::Body-->
				<div class="card-body pt-6">
					<!--begin::Tab content-->
					<div class="tab-content">
						<!--begin::Tab pane-->
						<div class="tab-pane fade active show" id="kt_chart_widget_8_month_tab" role="tabpanel">
							<!--begin::Statistics-->
							<div class="mb-5">
								<!--begin::Statistics-->
								<div class="d-flex align-items-center mb-2">
									<span
										class="fs-3x fw-bold text-gray-800 me-2 lh-1 ls-n2"><?php echo count($getAllStudents); ?></span>
									<span class="fs-1 fw-semibold text-gray-500 me-1 mt-n1">Öğrenci</span>
									<span class="badge badge-light-success fs-base">
										<i class="ki-duotone ki-arrow-up fs-5 text-success ms-n1">
										</i><?php echo count($getActiveStudents); ?> Aktif
									</span>
									<span class="badge badge-light-danger ms-3 fs-base">
										<i class="ki-duotone ki-arrow-up fs-5 text-danger ms-n1">
										</i><?php echo count($getPassiveStudents); ?> Pasif
									</span>
								</div>
								<!--end::Statistics-->
								<!--begin::Description-->
								<span class="fs-6 fw-semibold text-gray-500">Üye olan son 5 öğrenci</span>
								<!--end::Description-->
							</div>
							<!--end::Statistics-->
							<!--begin::Table container-->
							<div class="table-responsive">
								<!--begin::Table-->
								<table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
									<!--begin::Table head-->
									<thead>
										<tr class="fs-7 fw-bold text-gray-500 border-bottom-0">
											<th class="p-0 pb-3 min-w-150px text-start">ÖĞRENCİ ADI</th>
											<th class="p-0 pb-3 min-w-100px text-end pe-13">SINIFI</th>
										</tr>
									</thead>
									<!--end::Table head-->
									<!--begin::Table body-->
									<tbody>
										<?php if (empty($getAllStudents)) { ?>
											<tr>
												<td colspan="3" class="text-center"><span
														class="text-gray-600 fw-bold fs-6">Öğrenci Mevcut Değil!</span></td>
											</tr>
										<?php } else {
											foreach (array_slice($getAllStudents, 0, 5) as $student) { ?>
												<tr>
													<td>
														<div class="d-flex align-items-center">
															<div class="symbol symbol-50px me-3">
																<img src="assets/media/profile/<?php echo $student['photo']; ?>"
																	class="" alt="" />
															</div>
															<div class="d-flex justify-content-start flex-column">
																<a href="ogrenci-detay/<?php echo $student['username']; ?>"
																	class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6"><?php echo $student['name'] . ' ' . $student['surname']; ?></a>
																<span
																	class="text-gray-500 fw-semibold d-block fs-7"><?php echo $student['schoolName']; ?></span>
															</div>
														</div>
													</td>
													<td class="text-end pe-13">
														<span
															class="text-gray-600 fw-bold fs-6"><?php echo $student['className']; ?></span>
													</td>
												</tr>
											<?php }
										} ?>
									</tbody>
									<!--end::Table body-->
								</table>
								<a href="ogrenciler"><button type="button" class="btn btn-primary btn-sm mt-5">Tüm
										Öğrenciler</button></a>
								<!--end::Table-->
							</div>
							<!--end::Table container-->
						</div>
						<!--end::Tab pane-->
					</div>
					<!--end::Tab content-->
				</div>
				<!--end::Body-->
			</div>
			<!--end::Chart widget 8-->
		</div>
		<!--end::Col-->
		<!--begin::Col-->
		<div class="col-xxl-6 mb-5 mb-xl-10">
			<!--begin::Chart widget 8-->
			<div class="card card-flush h-xl-100">
				<!--begin::Header-->
				<div class="card-header pt-5">
					<!--begin::Title-->
					<h3 class="card-title align-items-start flex-column">
						<span class="card-label fw-bold text-gray-900">En Çok Tercih Edilen Paketler</span>
					</h3>
					<!--end::Title-->
				</div>
				<!--end::Header-->
				<!--begin::Body-->
				<div class="card-body pt-6">
					<!--begin::Tab content-->
					<div class="tab-content">
						<!--begin::Tab pane-->
						<div class="tab-pane fade active show" id="kt_chart_widget_8_month_tab" role="tabpanel">
							<!--begin::Statistics-->
							<div class="mb-5">
								<!--begin::Statistics-->
								<div class="d-flex align-items-center mb-2">
									<span
										class="fs-3x fw-bold text-gray-800 me-2 lh-1 ls-n2"><?php echo count($getAllPackages); ?></span>
									<span class="fs-1 fw-semibold text-gray-500 me-1 mt-n1">Paket Sayısı</span>
								</div>
								<!--end::Statistics-->
								<!--begin::Description-->
								<span class="fs-6 fw-semibold text-gray-500">En çok tercih edilen 5 paket</span>
								<!--end::Description-->
							</div>
							<!--end::Statistics-->
							<!--begin::Table container-->
							<div class="table-responsive">
								<!--begin::Table-->
								<table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
									<!--begin::Table head-->
									<thead>
										<tr class="fs-7 fw-bold text-gray-500 border-bottom-0">
											<th class="p-0 pb-3 min-w-150px text-start">PAKET ADI</th>
											<th class="p-0 pb-3 min-w-100px text-end pe-13">SATILAN ADET</th>
											<th class="p-0 pb-3 min-w-100px text-end pe-13">SINIFI</th>
										</tr>
									</thead>
									<!--end::Table head-->
									<!--begin::Table body-->
									<tbody>
										<?php if (empty($getMostUsedPackages)) { ?>
											<tr>
												<td colspan="3" class="text-center"><span
														class="text-gray-600 fw-bold fs-6">Satılan Paket Mevcut
														Değil!</span></td>
											</tr>
										<?php } else {
											foreach ($getMostUsedPackages as $packs) { ?>
												<tr>
													<td>
														<div class="d-flex align-items-center">
															<div class="d-flex justify-content-start flex-column">
																<?php echo $packs['name'] ?>
															</div>
														</div>
													</td>
													<td class="text-end pe-13">
														<span
															class="text-gray-600 fw-bold fs-6"><?php echo $packs['user_count']; ?></span>
													</td>
													<td class="text-end pe-13">
														<span
															class="text-gray-600 fw-bold fs-6"><?php echo $packs['className']; ?></span>
													</td>
												</tr>
											<?php }
										} ?>
									</tbody>
									<!--end::Table body-->
								</table>
								<a href="paketler"><button type="button" class="btn btn-primary btn-sm mt-5">Tüm
										Paketler</button></a>
								<!--end::Table-->
							</div>
							<!--end::Table container-->
						</div>
						<!--end::Tab pane-->
					</div>
					<!--end::Tab content-->
				</div>
				<!--end::Body-->
			</div>
			<!--end::Chart widget 8-->
		</div>
		<!--end::Col-->
		<!--begin::Col-->
		<div class="col-xxl-6 mb-5 mb-xl-10">
			<!--begin::Chart widget 8-->
			<div class="card card-flush h-xl-100">
				<!--begin::Header-->
				<div class="card-header pt-5">
					<!--begin::Title-->
					<h3 class="card-title align-items-start flex-column">
						<span class="card-label fw-bold text-gray-900">Testler</span>
					</h3>
					<!--end::Title-->
				</div>
				<!--end::Header-->
				<!--begin::Body-->
				<div class="card-body pt-6">
					<!--begin::Tab content-->
					<div class="tab-content">
						<!--begin::Tab pane-->
						<div class="tab-pane fade active show" id="kt_chart_widget_8_month_tab" role="tabpanel">
							<!--begin::Statistics-->
							<div class="mb-5">
								<!--begin::Statistics-->
								<div class="d-flex align-items-center mb-2">
									<span
										class="fs-3x fw-bold text-gray-800 me-2 lh-1 ls-n2"><?php echo count($getTests); ?></span>
									<span class="fs-1 fw-semibold text-gray-500 me-1 mt-n1">Test Sayısı</span>
								</div>
								<!--end::Statistics-->
								<!--begin::Description-->
								<span class="fs-6 fw-semibold text-gray-500">En son eklenen 5 test</span>
								<!--end::Description-->
							</div>
							<!--end::Statistics-->
							<!--begin::Table container-->
							<div class="table-responsive">
								<!--begin::Table-->
								<table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
									<!--begin::Table head-->
									<thead>
										<tr class="fs-7 fw-bold text-gray-500 border-bottom-0">
											<th class="p-0 pb-3 min-w-150px text-start">TEST ADI</th>
											<th class="p-0 pb-3 min-w-100px text-end pe-13">SINIFI</th>
											<th class="p-0 pb-3 min-w-100px text-end">EKLENME TARİHİ</th>
										</tr>
									</thead>
									<!--end::Table head-->
									<!--begin::Table body-->
									<tbody>
										<?php if (empty($getTests)) { ?>
											<tr>
												<td colspan="2" class="text-center"><span
														class="text-gray-600 fw-bold fs-6">Eklenmiş Test Mevcut
														Değil!</span></td>
											</tr>
										<?php } else {
											foreach (array_slice($getTests, 0, 5) as $tests) { ?>
												<tr>
													<td>
														<div class="d-flex align-items-center">
															<div class="d-flex justify-content-start flex-column">
																<?php echo $tests['test_title'] ?>
															</div>
														</div>
													</td>
													<td class="text-end pe-13">
														<span
															class="text-gray-600 fw-bold fs-6"><?php echo $tests['className']; ?></span>
													</td>
													<td class="text-end">
														<span
															class="text-gray-600 fw-bold fs-6"><?php echo $dateFormat->changeDate($tests['created_at']); ?></span>
													</td>
												</tr>
											<?php }
										} ?>
									</tbody>
									<!--end::Table body-->
								</table>
								<a href="testler"><button type="button" class="btn btn-primary btn-sm mt-5">Tüm
										Testler</button></a>
								<!--end::Table-->
							</div>
							<!--end::Table container-->
						</div>
						<!--end::Tab pane-->
					</div>
					<!--end::Tab content-->
				</div>
				<!--end::Body-->
			</div>
			<!--end::Chart widget 8-->
		</div>
		<!--end::Col-->
		<!--begin::Col-->
		<div class="col-xxl-6 mb-5 mb-xl-10">
			<!--begin::Chart widget 8-->
			<div class="card card-flush h-xl-100">
				<!--begin::Header-->
				<div class="card-header pt-5">
					<!--begin::Title-->
					<h3 class="card-title align-items-start flex-column">
						<span class="card-label fw-bold text-gray-900">Ödevler</span>
					</h3>
					<!--end::Title-->
				</div>
				<!--end::Header-->
				<!--begin::Body-->
				<div class="card-body pt-6">
					<!--begin::Tab content-->
					<div class="tab-content">
						<!--begin::Tab pane-->
						<div class="tab-pane fade active show" id="kt_chart_widget_8_month_tab" role="tabpanel">
							<!--begin::Statistics-->
							<div class="mb-5">
								<!--begin::Statistics-->
								<div class="d-flex align-items-center mb-2">
									<span
										class="fs-3x fw-bold text-gray-800 me-2 lh-1 ls-n2"><?php echo count($getHomeworks); ?></span>
									<span class="fs-1 fw-semibold text-gray-500 me-1 mt-n1">Ödev Sayısı</span>
								</div>
								<!--end::Statistics-->
								<!--begin::Description-->
								<span class="fs-6 fw-semibold text-gray-500">En son eklenen 5 ödev</span>
								<!--end::Description-->
							</div>
							<!--end::Statistics-->
							<!--begin::Table container-->
							<div class="table-responsive">
								<!--begin::Table-->
								<table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
									<!--begin::Table head-->
									<thead>
										<tr class="fs-7 fw-bold text-gray-500 border-bottom-0">
											<th class="p-0 pb-3 min-w-150px text-start">ÖDEV ADI</th>
											<th class="p-0 pb-3 min-w-100px text-end pe-13">SINIFI</th>
											<th class="p-0 pb-3 min-w-100px text-end">EKLENME TARİHİ</th>
										</tr>
									</thead>
									<!--end::Table head-->
									<!--begin::Table body-->
									<tbody>
										<?php if (empty($getHomeworks)) { ?>
											<tr>
												<td colspan="2" class="text-center"><span
														class="text-gray-600 fw-bold fs-6">Eklenmiş Ödev Mevcut
														Değil!</span></td>
											</tr>
										<?php } else {
											foreach (array_slice($getHomeworks, 0, 5) as $homeworks) { ?>
												<tr>
													<td>
														<div class="d-flex align-items-center">
															<div class="d-flex justify-content-start flex-column">
																<?php echo $homeworks['title'] ?>
															</div>
														</div>
													</td>
													<td class="text-end pe-13">
														<span
															class="text-gray-600 fw-bold fs-6"><?php echo $homeworks['className']; ?></span>
													</td>
													<td class="text-end">
														<span
															class="text-gray-600 fw-bold fs-6"><?php echo $dateFormat->changeDate($homeworks['created_at']); ?></span>
													</td>
												</tr>
											<?php }
										} ?>
									</tbody>
									<!--end::Table body-->
								</table>
								<a href="odevler"><button type="button" class="btn btn-primary btn-sm mt-5">Tüm
										Ödevler</button></a>
								<!--end::Table-->
							</div>
							<!--end::Table container-->
						</div>
						<!--end::Tab pane-->
					</div>
					<!--end::Tab content-->
				</div>
				<!--end::Body-->
			</div>
			<!--end::Chart widget 8-->
		</div>
		<!--end::Col-->
		<!--begin::Col-->
		<div class="col-xxl-6 mb-5 mb-xl-10">
			<!--begin::Chart widget 8-->
			<div class="card card-flush h-xl-100">
				<!--begin::Header-->
				<div class="card-header pt-5">
					<!--begin::Title-->
					<h3 class="card-title align-items-start flex-column">
						<span class="card-label fw-bold text-gray-900">Bu Ayki Paket Satışı</span>
					</h3>
					<!--end::Title-->
				</div>
				<!--end::Header-->
				<!--begin::Body-->
				<div class="card-body pt-6">
					<!--begin::Tab content-->
					<div class="tab-content">
						<!--begin::Tab pane-->
						<div class="tab-pane fade active show" id="kt_chart_widget_8_month_tab" role="tabpanel">
							<!--begin::Statistics-->
							<div class="mb-5">
								<!--begin::Statistics-->
								<div class="d-flex align-items-center mb-2">
									<span
										class="fs-3x fw-bold text-gray-800 me-2 lh-1 ls-n2"><?php echo $getPayments[0]['total_count']; ?></span>
									<span class="fs-1 fw-semibold text-gray-500 me-1 mt-n1">Paket Satış Sayısı</span>
								</div>
								<!--end::Statistics-->
							</div>
							<!--end::Statistics-->
							<!--begin::Table container-->
							<div class="table-responsive">
								<!--begin::Table-->
								<table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
									<!--begin::Table head-->
									<thead>
										<tr class="fs-7 fw-bold text-gray-500 border-bottom-0">
											<th class="p-0 pb-3 min-w-150px text-start">DÖNEM</th>
											<th class="p-0 pb-3 min-w-100px text-end pe-13">ÖDEME</th>
											<th class="p-0 pb-3 min-w-100px text-end">KDV</th>
										</tr>
									</thead>
									<!--end::Table head-->
									<!--begin::Table body-->
									<tbody>
										<?php if (empty($getPayments)) { ?>
											<tr>
												<td colspan="2" class="text-center"><span
														class="text-gray-600 fw-bold fs-6">Alınmış Paket Yok!</span></td>
											</tr>
										<?php } else { ?>
											<tr>
												<td>
													<div class="d-flex align-items-center">
														<div class="d-flex justify-content-start flex-column">
															<span
																class="text-gray-600 fw-bold fs-6"><?php echo date("m.Y"); ?></span>
														</div>
													</div>
												</td>
												<td class="text-end pe-13">
													<span
														class="text-gray-600 fw-bold fs-6"><?php echo $getPayments[0]['total_payments']; ?>₺</span>
												</td>
												<td class="text-end">
													<span
														class="text-gray-600 fw-bold fs-6"><?php echo $getPayments[0]['total_vat']; ?>₺</span>
												</td>
											</tr>
										<?php } ?>
									</tbody>
									<!--end::Table body-->
								</table>
								<a href="grafik-rapor"><button type="button" class="btn btn-primary btn-sm mt-5">Tüm
										Ödemeler</button></a>
								<!--end::Table-->
							</div>
							<!--end::Table container-->
						</div>
						<!--end::Tab pane-->
					</div>
					<!--end::Tab content-->
				</div>
				<!--end::Body-->
			</div>
			<!--end::Chart widget 8-->
		</div>
		<!--end::Col-->

		<div class="col-xxl-6 mb-5 mb-xl-10">
			<div class="card card-flush h-xl-100">
				<div class="card-header pt-5">
					<h3 class="card-title align-items-start flex-column">
						<span class="card-label fw-bold text-gray-900">En Başarlı Öğrenciler</span>
					</h3>
				</div>
				<div class="card-body pt-6">
					<div class="tab-content">
						<div class="tab-pane fade active show" id="kt_chart_widget_8_month_tab" role="tabpanel">

							<div class="table-responsive">
								<table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
									<thead>
										<tr class="fs-7 fw-bold text-gray-500 border-bottom-0">
											<th class="p-0 pb-3 min-w-150px text-start">ÖĞRENCİ ADI</th>
											<th class="p-0 pb-3 min-w-100px text-end pe-13">SINIFI</th>
											<th class="p-0 pb-3 min-w-100px text-end pe-13">ORAN</th>

										</tr>
									</thead>

									<tbody>
										<?php if (empty($getHighestScoreStudents)) { ?>
											<tr>
												<td colspan="3" class="text-center"><span
														class="text-gray-600 fw-bold fs-6">Öğrenci Mevcut Değil!</span></td>
											</tr>
										<?php } else {
											foreach ($getHighestScoreStudents as $student) { ?>
												<tr>
													<td>
														<div class="d-flex align-items-center">
															<div class="symbol symbol-50px me-3">
																<img src="assets/media/profile/<?php echo $student['photo']; ?>"
																	class="" alt="" />
															</div>
															<div class="d-flex justify-content-start flex-column">
																<a href="ogrenci-detay/<?php echo $student['username']; ?>"
																	class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6"><?php echo $student['name'] . ' ' . $student['surname']; ?></a>
																<span
																	class="text-gray-500 fw-semibold d-block fs-7"><?php echo $student['schoolName']; ?></span>
															</div>
														</div>
													</td>
													<td class="text-end pe-13">
														<span
															class="text-gray-600 fw-bold fs-6"><?php echo $student['className']; ?></span>
													</td>
													<td class="text-end pe-13">
														<span
															class="text-gray-600 fw-bold fs-6"><?php echo $student['average_score']; ?>%</span>
													</td>
												</tr>
											<?php }
										} ?>
									</tbody>
								</table>
								<a href="ilerleme-performans-takip"><button type="button"
										class="btn btn-primary btn-sm mt-5">Tüm
										Öğrenciler</button></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>


		<div class="col-xxl-6 mb-5 mb-xl-10">
			<div class="card card-flush h-xl-100">
				<div class="card-header pt-5">
					<h3 class="card-title align-items-start flex-column">
						<span class="card-label fw-bold text-gray-900">En Fazla İlerleyen Öğrenciler</span>
					</h3>
				</div>
				<div class="card-body pt-6">
					<div class="tab-content">
						<div class="tab-pane fade active show" id="kt_chart_widget_8_month_tab" role="tabpanel">

							<div class="table-responsive">
								<table class="table table-row-dashed align-middle gs-0 gy-3 my-0" id="topStudentsTable">
									<thead>
										<tr class="fs-7 fw-bold text-gray-500 border-bottom-0">
											<th class="p-0 pb-3 min-w-150px text-start">ÖĞRENCİ ADI</th>
											<th class="p-0 pb-3 min-w-100px text-end pe-13">SINIFI</th>
											<th class="p-0 pb-3 min-w-100px text-end pe-13">ORAN</th>
										</tr>
									</thead>
									<tbody>
										<!-- AJAX ile burada veri yüklenecek -->
									</tbody>
								</table>
								<a href="ilerleme-performans-takip">
									<button type="button" class="btn btn-primary btn-sm mt-5">Tüm Öğrenciler</button>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>


		<div class="col-xxl-6 mb-5 mb-xl-10">
			<div class="card card-flush h-xl-100">
				<div class="card-header pt-5">
					<h3 class="card-title align-items-start flex-column">
						<span class="card-label fw-bold text-gray-900">En Fazla Zaman Geçiren Öğrenciler</span>
					</h3>
				</div>
				<div class="card-body pt-6">
					<div class="tab-content">
						<div class="tab-pane fade active show" id="kt_chart_widget_8_month_tab" role="tabpanel">

							<div class="table-responsive">
								<table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
									<thead>
										<tr class="fs-7 fw-bold text-gray-500 border-bottom-0">
											<th class="p-0 pb-3 min-w-150px text-start">ÖĞRENCİ ADI</th>
											<th class="p-0 pb-3 min-w-100px text-end pe-13">SINIFI</th>
											<th class="p-0 pb-3 min-w-100px text-end pe-13">ZAMAN</th>

										</tr>
									</thead>

									<tbody>
										<?php if (empty($getHighestTimespent)) { ?>
											<tr>
												<td colspan="3" class="text-center"><span
														class="text-gray-600 fw-bold fs-6">Öğrenci Mevcut Değil!</span></td>
											</tr>
										<?php } else {
											foreach ($getHighestTimespent as $student) { ?>
												<tr>
													<td>
														<div class="d-flex align-items-center">
															<div class="symbol symbol-50px me-3">
																<img src="assets/media/profile/<?php echo $student['photo']; ?>"
																	class="" alt="" />
															</div>
															<div class="d-flex justify-content-start flex-column">
																<a href="ogrenci-detay/<?php echo $student['username']; ?>"
																	class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6"><?php echo $student['name'] . ' ' . $student['surname']; ?></a>
																<span
																	class="text-gray-500 fw-semibold d-block fs-7"><?php echo $student['schoolName']; ?></span>
															</div>
														</div>
													</td>
													<td class="text-end pe-13">
														<span
															class="text-gray-600 fw-bold fs-6"><?php echo $student['className']; ?></span>
													</td>
													<td class="text-end pe-13">
														<span
															class="text-gray-600 fw-bold fs-6"><?php echo $dateFormat->secondsToReadableTime($student['totalTime']); ?></span>
													</td>
												</tr>
											<?php }
										} ?>
									</tbody>
								</table>
								<a href="ilerleme-performans-takip"><button type="button"
										class="btn btn-primary btn-sm mt-5">Tüm
										Öğrenciler</button></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-xxl-6 mb-5 mb-xl-10">
			<div class="card card-flush h-xl-100">
				<div class="card-header pt-5">
					<h3 class="card-title align-items-start flex-column">
						<span class="card-label fw-bold text-gray-900">En Başarlı Testler</span>
					</h3>
				</div>
				<div class="card-body pt-6">
					<div class="tab-content">
						<div class="tab-pane fade active show" id="kt_chart_widget_8_month_tab" role="tabpanel">

							<div class="table-responsive">
								<table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
									<thead>
										<tr class="fs-7 fw-bold text-gray-500 border-bottom-0">
											<th class="p-0 pb-3 min-w-150px text-start">TEST ADI</th>
											<th class="p-0 pb-3 min-w-100px text-end pe-13">SINIFI</th>
											<th class="p-0 pb-3 min-w-100px text-end">ORTALAMA</th>
										</tr>
									</thead>
									<tbody>
										<?php if (empty($testsHigh)) { ?>
											<tr>
												<td colspan="2" class="text-center"><span
														class="text-gray-600 fw-bold fs-6">Eklenmiş Test Mevcut
														Değil!</span></td>
											</tr>
										<?php } else {
											foreach ($testsHigh as $tests) { ?>
												<tr>
													<td>
														<div class="d-flex align-items-center">
															<div class="d-flex justify-content-start flex-column">
																<?php echo $tests['test_title'] ?>
															</div>
														</div>
													</td>
													<td class="text-end pe-13">
														<span
															class="text-gray-600 fw-bold fs-6"><?php echo $tests['className']; ?></span>
													</td>
													<td class="text-end">
														<span
															class="text-gray-600 fw-bold fs-6"><?php echo $tests['total']; ?>%</span>
													</td>
												</tr>
											<?php }
										} ?>
									</tbody>
								</table>
								<a href="testler"><button type="button" class="btn btn-primary btn-sm mt-5">Tüm
										Testler</button></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-xxl-6 mb-5 mb-xl-10">
			<div class="card card-flush h-xl-100">
				<div class="card-header pt-5">
					<h3 class="card-title align-items-start flex-column">
						<span class="card-label fw-bold text-gray-900">En Başarsız Testler</span>
					</h3>
				</div>
				<div class="card-body pt-6">
					<div class="tab-content">
						<div class="tab-pane fade active show" id="kt_chart_widget_8_month_tab" role="tabpanel">

							<div class="table-responsive">
								<table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
									<thead>
										<tr class="fs-7 fw-bold text-gray-500 border-bottom-0">
											<th class="p-0 pb-3 min-w-150px text-start">TEST ADI</th>
											<th class="p-0 pb-3 min-w-100px text-end pe-13">SINIFI</th>
											<th class="p-0 pb-3 min-w-100px text-end">ORTALAMA</th>
										</tr>
									</thead>
									<tbody>
										<?php if (empty($testsLow)) { ?>
											<tr>
												<td colspan="2" class="text-center"><span
														class="text-gray-600 fw-bold fs-6">Eklenmiş Test Mevcut
														Değil!</span></td>
											</tr>
										<?php } else {
											foreach ($testsLow as $tests) { ?>
												<tr>
													<td>
														<div class="d-flex align-items-center">
															<div class="d-flex justify-content-start flex-column">
																<?php echo $tests['test_title'] ?>
															</div>
														</div>
													</td>
													<td class="text-end pe-13">
														<span
															class="text-gray-600 fw-bold fs-6"><?php echo $tests['className']; ?></span>
													</td>
													<td class="text-end">
														<span
															class="text-gray-600 fw-bold fs-6"><?php echo $tests['total']; ?>%</span>
													</td>
												</tr>
											<?php }
										} ?>
									</tbody>
								</table>
								<a href="testler"><button type="button" class="btn btn-primary btn-sm mt-5">Tüm
										Testler</button></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>

</div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
$(document).ready(function() {
		$.ajax({
			url: './includes/ajax.php?service=getTopStudents',
			method: 'GET',
			data: {
				schoolId: 1
			}, // burayı dinamik yapabilirsin
			beforeSend: function() {
				const tbody = $('#topStudentsTable tbody');
				tbody.empty(); // varsa eski dataları temizle
				tbody.append(`
                <tr id="loadingRow">
                    <td colspan="3" class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Yükleniyor...</span>
                        </div>
                    </td>
                </tr>
            `);
			},
			success: function(response) {
				const tbody = $('#topStudentsTable tbody');
				tbody.empty();
				console.log(response);

				const students = response.data || [];

				if (students.length === 0) {
					tbody.append(`
                    <tr>
                        <td colspan="3" class="text-center">
                            <span class="text-gray-600 fw-bold fs-6">Öğrenci Mevcut Değil !</span>
                        </td>
                    </tr>
                `);
				} else {
					students.forEach(student => {
						tbody.append(`
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50px me-3">
                                        <img src="assets/media/profile/${student.photo}" alt="" />
                                    </div>
                                    <div class="d-flex justify-content-start flex-column">
                                        <a href="ogrenci-detay/${student.username}" 
                                           class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">
                                            ${student.name} ${student.surname}
                                        </a>
                                        <span class="text-gray-500 fw-semibold d-block fs-7">
                                            ${student.schoolName}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-end pe-13">
                                <span class="text-gray-600 fw-bold fs-6">${student.className}</span>
                            </td>
                            <td class="text-end pe-13">
                                <span class="text-gray-600 fw-bold fs-6">${student.ana_score}%</span>
                            </td>
                        </tr>
                    `);
					});
				}
			},
			error: function(err) {
				console.error(err);
				const tbody = $('#topStudentsTable tbody');
				tbody.empty();
				tbody.append(`
                <tr>
                    <td colspan="3" class="text-center text-danger fw-bold">
                        Veri alınırken hata oluştu!
                    </td>
                </tr>
            `);
			}
		});
	});
	const subscriptionData = <?php echo json_encode($chartData, JSON_NUMERIC_CHECK); ?>;

	let chart;
	let currentPeriod = 'week';

	function initChart() {
		const ctx = document.getElementById('subscriptionChart').getContext('2d');

		chart = new Chart(ctx, {
			type: 'line',
			data: {
				labels: subscriptionData[currentPeriod].labels,
				datasets: [{
					label: 'Subscriptions',
					data: subscriptionData[currentPeriod].data,
					borderColor: 'rgb(0, 158, 247)',
					backgroundColor: 'rgba(0, 158, 247, 0.1)',
					borderWidth: 3,
					fill: true,
					tension: 0.4,
					pointBackgroundColor: 'rgb(0, 158, 247)',
					pointBorderColor: '#fff',
					pointBorderWidth: 2,
					pointRadius: 6,
					pointHoverRadius: 8,
					pointHoverBackgroundColor: 'rgb(0, 158, 247)',
					pointHoverBorderColor: '#fff',
					pointHoverBorderWidth: 3
				}]
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				plugins: {
					legend: {
						display: false
					},
					tooltip: {
						backgroundColor: 'rgba(0, 0, 0, 0.8)',
						titleColor: '#fff',
						bodyColor: '#fff',
						cornerRadius: 8,
						padding: 12,
						displayColors: false,
						callbacks: {
							label: function (context) {
								return `${context.parsed.y} new subscriptions`;
							}
						}
					}
				},
				scales: {
					x: {
						grid: {
							color: 'rgba(0, 0, 0, 0.1)',
							borderDash: [5, 5]
						},
						ticks: {
							color: '#7e8299',
							font: {
								size: 12
							}
						}
					},
					y: {
						beginAtZero: true,
						grid: {
							color: 'rgba(0, 0, 0, 0.1)',
							borderDash: [5, 5]
						},
						ticks: {
							color: '#7e8299',
							font: {
								size: 12
							}
						}
					}
				},
				elements: {
					point: {
						hoverRadius: 8
					}
				},
				interaction: {
					intersect: false,
					mode: 'index'
				}
			}
		});
	}

	function updateChart(period) {
		currentPeriod = period;
		const data = subscriptionData[period];

		chart.data.labels = data.labels;
		chart.data.datasets[0].data = data.data;
		chart.update('active');

		updateStats(period);
	}

	function updateStats(period) {
		const data = subscriptionData[period];
		const total = data.total;
		const average = Math.round(total / data.data.length);

		const firstValue = data.data[0];
		const lastValue = data.data[data.data.length - 1];
		const growthRate = ((lastValue - firstValue) / firstValue * 100).toFixed(1);

		document.getElementById('totalSubscriptions').textContent = total.toLocaleString();
		document.getElementById('growthRate').textContent = `${growthRate}%`;
		document.getElementById('avgPerPeriod').textContent = average.toLocaleString();
	}

	document.querySelectorAll('[data-period]').forEach(button => {
		button.addEventListener('click', function () {
			document.querySelectorAll('[data-period]').forEach(btn => btn.classList.remove('active'));

			this.classList.add('active');

			updateChart(this.dataset.period);
		});
	});

	document.addEventListener('DOMContentLoaded', function () {
		initChart();
		updateStats('week');
	});

	function loadDataFromPHP(period) {

		fetch(`/api/subscriptions?period=${period}`)
			.then(response => response.json())
			.then(data => {
				subscriptionData[period] = data;
				updateChart(period);
			});

	}
</script>