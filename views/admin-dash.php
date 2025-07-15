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


$getHighestScoreStudents = $gradeObj->getHighestGradeOverall($_SESSION['school_id']);
$getHighestAnaStudents = $contentObj->getHighestAnalyticsOverall($_SESSION['school_id']);

?>
<div id="kt_app_content_container" class="app-container container-fluid">
	<!--begin::Row-->
	<div class="row gx-5 gx-xl-10">
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
								<table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
									<thead>
										<tr class="fs-7 fw-bold text-gray-500 border-bottom-0">
											<th class="p-0 pb-3 min-w-150px text-start">ÖĞRENCİ ADI</th>
											<th class="p-0 pb-3 min-w-100px text-end pe-13">SINIFI</th>
											<th class="p-0 pb-3 min-w-100px text-end pe-13">ORAN</th>

										</tr>
									</thead>

									<tbody>
										<?php if (empty($getHighestAnaStudents)) { ?>
											<tr>
												<td colspan="3" class="text-center"><span
														class="text-gray-600 fw-bold fs-6">Öğrenci Mevcut Değil!</span></td>
											</tr>
										<?php } else {
											foreach ($getHighestAnaStudents as $student) { ?>
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
															class="text-gray-600 fw-bold fs-6"><?php echo $student['ana_score']; ?>%</span>
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
	</div>
	<!--end::Row-->
</div>