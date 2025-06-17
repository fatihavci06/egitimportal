<?php 

include_once "classes/dateformat.classes.php";
include_once "classes/student.classes.php";
include_once "classes/packages.classes.php";
include_once "classes/dashes.classes.php";

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
									<span class="fs-3x fw-bold text-gray-800 me-2 lh-1 ls-n2"><?php echo count($getAllStudents); ?></span>
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
                                        <?php if(empty($getAllStudents)){ ?>
                                            <tr>
                                                <td colspan="3" class="text-center"><span class="text-gray-600 fw-bold fs-6">Öğrenci Mevcut Değil!</span></td>
                                            </tr>
                                        <?php }else{ foreach (array_slice($getAllStudents, 0, 5) as $student){ ?>
										<tr>
											<td>
												<div class="d-flex align-items-center">
													<div class="symbol symbol-50px me-3">
														<img src="assets/media/profile/<?php echo $student['photo']; ?>" class="" alt="" />
													</div>
													<div class="d-flex justify-content-start flex-column">
														<a href="ogrenci-detay/<?php echo $student['username']; ?>" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6"><?php echo $student['name'] . ' ' . $student['surname']; ?></a>
														<span class="text-gray-500 fw-semibold d-block fs-7"><?php echo $student['schoolName']; ?></span>
													</div>
												</div>
											</td>
											<td class="text-end pe-13">
												<span class="text-gray-600 fw-bold fs-6"><?php echo $student['className']; ?></span>
											</td>
										</tr>
                                        <?php }} ?>
									</tbody>
									<!--end::Table body-->
								</table>
                                <a href="ogrenciler"><button type="button" class="btn btn-primary btn-sm mt-5">Tüm Öğrenciler</button></a>
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
									<span class="fs-3x fw-bold text-gray-800 me-2 lh-1 ls-n2"><?php echo count($getAllPackages); ?></span>
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
                                        <?php if(empty($getMostUsedPackages)){ ?>
                                            <tr>
                                                <td colspan="3" class="text-center"><span class="text-gray-600 fw-bold fs-6">Satılan Paket Mevcut Değil!</span></td>
                                            </tr>
                                        <?php }else{ foreach ($getMostUsedPackages as $packs){ ?>
										<tr>
											<td>
												<div class="d-flex align-items-center">
													<div class="d-flex justify-content-start flex-column">
														<?php echo $packs['name']  ?>
													</div>
												</div>
											</td>
											<td class="text-end pe-13">
												<span class="text-gray-600 fw-bold fs-6"><?php echo $packs['user_count']; ?></span>
											</td>
											<td class="text-end pe-13">
												<span class="text-gray-600 fw-bold fs-6"><?php echo $packs['className']; ?></span>
											</td>
										</tr>
                                        <?php }} ?>
									</tbody>
									<!--end::Table body-->
								</table>
                                <a href="paketler"><button type="button" class="btn btn-primary btn-sm mt-5">Tüm Paketler</button></a>
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
									<span class="fs-3x fw-bold text-gray-800 me-2 lh-1 ls-n2"><?php echo count($getTests); ?></span>
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
                                        <?php if(empty($getTests)){ ?>
                                            <tr>
                                                <td colspan="2" class="text-center"><span class="text-gray-600 fw-bold fs-6">Eklenmiş Test Mevcut Değil!</span></td>
                                            </tr>
                                        <?php }else{ foreach (array_slice($getTests, 0, 5) as $tests){ ?>
										<tr>
											<td>
												<div class="d-flex align-items-center">
													<div class="d-flex justify-content-start flex-column">
														<?php echo $tests['test_title']  ?>
													</div>
												</div>
											</td>
											<td class="text-end pe-13">
												<span class="text-gray-600 fw-bold fs-6"><?php echo $tests['className']; ?></span>
											</td>
											<td class="text-end">
												<span class="text-gray-600 fw-bold fs-6"><?php echo $dateFormat->changeDate($tests['created_at']); ?></span>
											</td>
										</tr>
                                        <?php }} ?>
									</tbody>
									<!--end::Table body-->
								</table>
                                <a href="testler"><button type="button" class="btn btn-primary btn-sm mt-5">Tüm Testler</button></a>
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
									<span class="fs-3x fw-bold text-gray-800 me-2 lh-1 ls-n2"><?php echo count($getHomeworks); ?></span>
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
                                        <?php if(empty($getHomeworks)){ ?>
                                            <tr>
                                                <td colspan="2" class="text-center"><span class="text-gray-600 fw-bold fs-6">Eklenmiş Ödev Mevcut Değil!</span></td>
                                            </tr>
                                        <?php }else{ foreach (array_slice($getHomeworks, 0, 5) as $homeworks){ ?>
										<tr>
											<td>
												<div class="d-flex align-items-center">
													<div class="d-flex justify-content-start flex-column">
														<?php echo $homeworks['title']  ?>
													</div>
												</div>
											</td>
											<td class="text-end pe-13">
												<span class="text-gray-600 fw-bold fs-6"><?php echo $homeworks['className']; ?></span>
											</td>
											<td class="text-end">
												<span class="text-gray-600 fw-bold fs-6"><?php echo $dateFormat->changeDate($homeworks['created_at']); ?></span>
											</td>
										</tr>
                                        <?php }} ?>
									</tbody>
									<!--end::Table body-->
								</table>
                                <a href="odevler"><button type="button" class="btn btn-primary btn-sm mt-5">Tüm Ödevler</button></a>
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
									<span class="fs-3x fw-bold text-gray-800 me-2 lh-1 ls-n2"><?php echo $getPayments[0]['total_count']; ?></span>
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
                                        <?php if(empty($getPayments)){ ?>
                                            <tr>
                                                <td colspan="2" class="text-center"><span class="text-gray-600 fw-bold fs-6">Alınmış Paket Yok!</span></td>
                                            </tr>
                                        <?php }else{  ?>
										<tr>
											<td>
												<div class="d-flex align-items-center">
													<div class="d-flex justify-content-start flex-column">
														<span class="text-gray-600 fw-bold fs-6"><?php echo date("m.Y");  ?></span>
													</div>
												</div>
											</td>
											<td class="text-end pe-13">
												<span class="text-gray-600 fw-bold fs-6"><?php echo $getPayments[0]['total_payments']; ?>₺</span>
											</td>
											<td class="text-end">
												<span class="text-gray-600 fw-bold fs-6"><?php echo $getPayments[0]['total_vat']; ?>₺</span>
											</td>
										</tr>
                                        <?php } ?>
									</tbody>
									<!--end::Table body-->
								</table>
                                <a href="grafik-rapor"><button type="button" class="btn btn-primary btn-sm mt-5">Tüm Ödemeler</button></a>
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
		<div class="col-xl-6 mb-5 mb-xl-10">
			<!--begin::Tables widget 16-->
			<div class="card card-flush h-xl-100">
				<!--begin::Header-->
				<div class="card-header pt-5">
					<!--begin::Title-->
					<h3 class="card-title align-items-start flex-column">
						<span class="card-label fw-bold text-gray-800">Öğrenci Başarıları</span>
						<span class="text-gray-500 mt-1 fw-semibold fs-6">Ortalama %84 Başarı</span>
					</h3>
					<!--end::Title-->
				</div>
				<!--end::Header-->
				<!--begin::Body-->
				<div class="card-body pt-6">
					<!--begin::Nav-->
					<ul class="nav nav-pills nav-pills-custom mb-3">
						<!--begin::Item-->
						<li class="nav-item mb-3 me-3 me-lg-6">
							<!--begin::Link-->
							<a class="nav-link btn btn-outline btn-flex btn-color-muted btn-active-color-primary flex-column overflow-hidden w-80px h-85px pt-5 pb-2 active" id="kt_stats_widget_16_tab_link_1" data-bs-toggle="pill" href="#kt_stats_widget_16_tab_1">
								<!--begin::Icon-->
								<!--<div class="nav-icon mb-3">
																	<i class="ki-duotone ki-car fs-1">
																		<span class="path1"></span>
																		<span class="path2"></span>
																		<span class="path3"></span>
																		<span class="path4"></span>
																		<span class="path5"></span>
																	</i>
																</div>-->
								<span class="fs-1">T</span>
								<!--end::Icon-->
								<!--begin::Title-->
								<span class="nav-text text-gray-800 fw-bold fs-6 lh-1">Türkçe</span>
								<!--end::Title-->
								<!--begin::Bullet-->
								<span class="bullet-custom position-absolute bottom-0 w-100 h-4px bg-primary"></span>
								<!--end::Bullet-->
							</a>
							<!--end::Link-->
						</li>
						<!--end::Item-->
						<!--begin::Item-->
						<li class="nav-item mb-3 me-3 me-lg-6">
							<!--begin::Link-->
							<a class="nav-link btn btn-outline btn-flex btn-color-muted btn-active-color-primary flex-column overflow-hidden w-80px h-85px pt-5 pb-2" id="kt_stats_widget_16_tab_link_2" data-bs-toggle="pill" href="#kt_stats_widget_16_tab_2">
								<!--begin::Icon-->
								<!--<div class="nav-icon mb-3">
																	<i class="ki-duotone ki-car fs-1">
																		<span class="path1"></span>
																		<span class="path2"></span>
																		<span class="path3"></span>
																		<span class="path4"></span>
																		<span class="path5"></span>
																	</i>
																</div>-->
								<span class="fs-1">M</span>
								<!--end::Icon-->
								<!--begin::Title-->
								<span class="nav-text text-gray-800 fw-bold fs-6 lh-1">Matematik</span>
								<!--end::Title-->
								<!--begin::Bullet-->
								<span class="bullet-custom position-absolute bottom-0 w-100 h-4px bg-primary"></span>
								<!--end::Bullet-->
							</a>
							<!--end::Link-->
						</li>
						<!--end::Item-->
						<!--begin::Item-->
						<li class="nav-item mb-3 me-3 me-lg-6">
							<!--begin::Link-->
							<a class="nav-link btn btn-outline btn-flex btn-color-muted btn-active-color-primary flex-column overflow-hidden w-80px h-85px pt-5 pb-2" id="kt_stats_widget_16_tab_link_3" data-bs-toggle="pill" href="#kt_stats_widget_16_tab_3">
								<!--begin::Icon-->
								<!--<div class="nav-icon mb-3">
																	<i class="ki-duotone ki-car fs-1">
																		<span class="path1"></span>
																		<span class="path2"></span>
																		<span class="path3"></span>
																		<span class="path4"></span>
																		<span class="path5"></span>
																	</i>
																</div>-->
								<span class="fs-1">İ</span>
								<!--end::Icon-->
								<!--begin::Title-->
								<span class="nav-text text-gray-800 fw-bold fs-6 lh-1">İngilizce</span>
								<!--end::Title-->
								<!--begin::Bullet-->
								<span class="bullet-custom position-absolute bottom-0 w-100 h-4px bg-primary"></span>
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
						<div class="tab-pane fade show active" id="kt_stats_widget_16_tab_1">
							<!--begin::Table container-->
							<div class="table-responsive">
								<!--begin::Table-->
								<table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
									<!--begin::Table head-->
									<thead>
										<tr class="fs-7 fw-bold text-gray-500 border-bottom-0">
											<th class="p-0 pb-3 min-w-150px text-start">ÖĞRENCİ</th>
											<th class="p-0 pb-3 min-w-100px text-end pe-13">BAŞARI ORANI</th>
											<th class="p-0 pb-3 w-125px text-end pe-7">ÇİZELGE</th>
										</tr>
									</thead>
									<!--end::Table head-->
									<!--begin::Table body-->
									<tbody>
										<tr>
											<td>
												<div class="d-flex align-items-center">
													<div class="symbol symbol-50px me-3">
														<img src="assets/media/avatars/300-3.jpg" class="" alt="" />
													</div>
													<div class="d-flex justify-content-start flex-column">
														<a href="pages/user-profile/overview.html" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">T Öğrencisi</a>
														<span class="text-gray-500 fw-semibold d-block fs-7">Okul 1</span>
													</div>
												</div>
											</td>
											<td class="text-end pe-13">
												<span class="text-gray-600 fw-bold fs-6">78.34%</span>
											</td>
											<td class="text-end pe-0">
												<div id="kt_table_widget_16_chart_1_1" class="h-50px mt-n8 pe-7" data-kt-chart-color="success"></div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="d-flex align-items-center">
													<div class="symbol symbol-50px me-3">
														<img src="assets/media/avatars/300-2.jpg" class="" alt="" />
													</div>
													<div class="d-flex justify-content-start flex-column">
														<a href="pages/user-profile/overview.html" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">F Öğrencisi</a>
														<span class="text-gray-500 fw-semibold d-block fs-7">Okul 2</span>
													</div>
												</div>
											</td>
											<td class="text-end pe-13">
												<span class="text-gray-600 fw-bold fs-6">63.83%</span>
											</td>
											<td class="text-end pe-0">
												<div id="kt_table_widget_16_chart_1_2" class="h-50px mt-n8 pe-7" data-kt-chart-color="danger"></div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="d-flex align-items-center">
													<div class="symbol symbol-50px me-3">
														<img src="assets/media/avatars/300-9.jpg" class="" alt="" />
													</div>
													<div class="d-flex justify-content-start flex-column">
														<a href="pages/user-profile/overview.html" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">R Öğrencisi</a>
														<span class="text-gray-500 fw-semibold d-block fs-7">Okul 1</span>
													</div>
												</div>
											</td>
											<td class="text-end pe-13">
												<span class="text-gray-600 fw-bold fs-6">92.56%</span>
											</td>
											<td class="text-end pe-0">
												<div id="kt_table_widget_16_chart_1_3" class="h-50px mt-n8 pe-7" data-kt-chart-color="success"></div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="d-flex align-items-center">
													<div class="symbol symbol-50px me-3">
														<img src="assets/media/avatars/300-7.jpg" class="" alt="" />
													</div>
													<div class="d-flex justify-content-start flex-column">
														<a href="pages/user-profile/overview.html" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">U Öğrencisi</a>
														<span class="text-gray-500 fw-semibold d-block fs-7">Okul 8</span>
													</div>
												</div>
											</td>
											<td class="text-end pe-13">
												<span class="text-gray-600 fw-bold fs-6">63.08%</span>
											</td>
											<td class="text-end pe-0">
												<div id="kt_table_widget_16_chart_1_4" class="h-50px mt-n8 pe-7" data-kt-chart-color="success"></div>
											</td>
										</tr>
									</tbody>
									<!--end::Table body-->
								</table>
								<!--end::Table-->
							</div>
							<!--end::Table container-->
						</div>
						<!--end::Tap pane-->
						<!--begin::Tap pane-->
						<div class="tab-pane fade" id="kt_stats_widget_16_tab_2">
							<!--begin::Table container-->
							<div class="table-responsive">
								<!--begin::Table-->
								<table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
									<!--begin::Table head-->
									<thead>
										<tr class="fs-7 fw-bold text-gray-500 border-bottom-0">
											<th class="p-0 pb-3 min-w-150px text-start">ÖĞRENCİ</th>
											<th class="p-0 pb-3 min-w-100px text-end pe-13">BAŞARI ORANI</th>
											<th class="p-0 pb-3 w-125px text-end pe-7">ÇİZELGE</th>
										</tr>
									</thead>
									<!--end::Table head-->
									<!--begin::Table body-->
									<tbody>
										<tr>
											<td>
												<div class="d-flex align-items-center">
													<div class="symbol symbol-50px me-3">
														<img src="assets/media/avatars/300-25.jpg" class="" alt="" />
													</div>
													<div class="d-flex justify-content-start flex-column">
														<a href="pages/user-profile/overview.html" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">D Öğrencisi</a>
														<span class="text-gray-500 fw-semibold d-block fs-7">Okul 5</span>
													</div>
												</div>
											</td>
											<td class="text-end pe-13">
												<span class="text-gray-600 fw-bold fs-6">85.23%</span>
											</td>
											<td class="text-end pe-0">
												<div id="kt_table_widget_16_chart_2_1" class="h-50px mt-n8 pe-7" data-kt-chart-color="success"></div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="d-flex align-items-center">
													<div class="symbol symbol-50px me-3">
														<img src="assets/media/avatars/300-24.jpg" class="" alt="" />
													</div>
													<div class="d-flex justify-content-start flex-column">
														<a href="pages/user-profile/overview.html" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">Y Öğrencisi</a>
														<span class="text-gray-500 fw-semibold d-block fs-7">Okul 3</span>
													</div>
												</div>
											</td>
											<td class="text-end pe-13">
												<span class="text-gray-600 fw-bold fs-6">74.83%</span>
											</td>
											<td class="text-end pe-0">
												<div id="kt_table_widget_16_chart_2_2" class="h-50px mt-n8 pe-7" data-kt-chart-color="danger"></div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="d-flex align-items-center">
													<div class="symbol symbol-50px me-3">
														<img src="assets/media/avatars/300-20.jpg" class="" alt="" />
													</div>
													<div class="d-flex justify-content-start flex-column">
														<a href="pages/user-profile/overview.html" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">P Öğrencisi</a>
														<span class="text-gray-500 fw-semibold d-block fs-7">Okul 2</span>
													</div>
												</div>
											</td>
											<td class="text-end pe-13">
												<span class="text-gray-600 fw-bold fs-6">90.06%</span>
											</td>
											<td class="text-end pe-0">
												<div id="kt_table_widget_16_chart_2_3" class="h-50px mt-n8 pe-7" data-kt-chart-color="success"></div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="d-flex align-items-center">
													<div class="symbol symbol-50px me-3">
														<img src="assets/media/avatars/300-17.jpg" class="" alt="" />
													</div>
													<div class="d-flex justify-content-start flex-column">
														<a href="pages/user-profile/overview.html" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">H Öğrencisi</a>
														<span class="text-gray-500 fw-semibold d-block fs-7">Okul 4</span>
													</div>
												</div>
											</td>
											<td class="text-end pe-13">
												<span class="text-gray-600 fw-bold fs-6">54.08%</span>
											</td>
											<td class="text-end pe-0">
												<div id="kt_table_widget_16_chart_2_4" class="h-50px mt-n8 pe-7" data-kt-chart-color="success"></div>
											</td>
										</tr>
									</tbody>
									<!--end::Table body-->
								</table>
								<!--end::Table-->
							</div>
							<!--end::Table container-->
						</div>
						<!--end::Tap pane-->
						<!--begin::Tap pane-->
						<div class="tab-pane fade" id="kt_stats_widget_16_tab_3">
							<!--begin::Table container-->
							<div class="table-responsive">
								<!--begin::Table-->
								<table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
									<!--begin::Table head-->
									<thead>
										<tr class="fs-7 fw-bold text-gray-500 border-bottom-0">
											<th class="p-0 pb-3 min-w-150px text-start">ÖĞRENCİ</th>
											<th class="p-0 pb-3 min-w-100px text-end pe-13">BAŞARI ORANI</th>
											<th class="p-0 pb-3 w-125px text-end pe-7">ÇİZELGE</th>
										</tr>
									</thead>
									<!--end::Table head-->
									<!--begin::Table body-->
									<tbody>
										<tr>
											<td>
												<div class="d-flex align-items-center">
													<div class="symbol symbol-50px me-3">
														<img src="assets/media/avatars/300-11.jpg" class="" alt="" />
													</div>
													<div class="d-flex justify-content-start flex-column">
														<a href="pages/user-profile/overview.html" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">J Öğrencisi</a>
														<span class="text-gray-500 fw-semibold d-block fs-7">Okul 1</span>
													</div>
												</div>
											</td>
											<td class="text-end pe-13">
												<span class="text-gray-600 fw-bold fs-6">52.34%</span>
											</td>
											<td class="text-end pe-0">
												<div id="kt_table_widget_16_chart_3_1" class="h-50px mt-n8 pe-7" data-kt-chart-color="success"></div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="d-flex align-items-center">
													<div class="symbol symbol-50px me-3">
														<img src="assets/media/avatars/300-23.jpg" class="" alt="" />
													</div>
													<div class="d-flex justify-content-start flex-column">
														<a href="pages/user-profile/overview.html" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">C Öğrencisi</a>
														<span class="text-gray-500 fw-semibold d-block fs-7">Okul 5</span>
													</div>
												</div>
											</td>
											<td class="text-end pe-13">
												<span class="text-gray-600 fw-bold fs-6">77.65%</span>
											</td>
											<td class="text-end pe-0">
												<div id="kt_table_widget_16_chart_3_2" class="h-50px mt-n8 pe-7" data-kt-chart-color="danger"></div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="d-flex align-items-center">
													<div class="symbol symbol-50px me-3">
														<img src="assets/media/avatars/300-4.jpg" class="" alt="" />
													</div>
													<div class="d-flex justify-content-start flex-column">
														<a href="pages/user-profile/overview.html" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">A Öğrencisi</a>
														<span class="text-gray-500 fw-semibold d-block fs-7">Okul 5</span>
													</div>
												</div>
											</td>
											<td class="text-end pe-13">
												<span class="text-gray-600 fw-bold fs-6">82.47%</span>
											</td>
											<td class="text-end pe-0">
												<div id="kt_table_widget_16_chart_3_3" class="h-50px mt-n8 pe-7" data-kt-chart-color="success"></div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="d-flex align-items-center">
													<div class="symbol symbol-50px me-3">
														<img src="assets/media/avatars/300-1.jpg" class="" alt="" />
													</div>
													<div class="d-flex justify-content-start flex-column">
														<a href="pages/user-profile/overview.html" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">Ş Öğrencisi</a>
														<span class="text-gray-500 fw-semibold d-block fs-7">Okul 2</span>
													</div>
												</div>
											</td>
											<td class="text-end pe-13">
												<span class="text-gray-600 fw-bold fs-6">67.84%</span>
											</td>
											<td class="text-end pe-0">
												<div id="kt_table_widget_16_chart_3_4" class="h-50px mt-n8 pe-7" data-kt-chart-color="success"></div>
											</td>
										</tr>
									</tbody>
									<!--end::Table body-->
								</table>
								<!--end::Table-->
							</div>
							<!--end::Table container-->
						</div>
						<!--end::Tap pane-->
						<!--end::Table container-->
					</div>
					<!--end::Tab Content-->
				</div>
				<!--end: Card Body-->
			</div>
			<!--end::Tables widget 16-->
		</div>
		<!--end::Col-->
	</div>
	<!--end::Row-->
</div>