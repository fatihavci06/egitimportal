<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and $_SESSION['role'] == 1) {
	include_once "classes/dbh.classes.php";
	include_once "classes/userslist.classes.php";
	include_once "classes/classes.classes.php";
	include_once "classes/packages.classes.php";
	include_once "classes/packages-view.classes.php";
	$package = new ShowPackagesForAdmin();
	$id = $_GET['id'];
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
							<div id="kt_app_toolbar" class="app-toolbar pt-5">
								<!--begin::Toolbar container-->
								<div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
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
												<li class="breadcrumb-item text-gray-700 fw-bold lh-1">Paket Detay</li>
												<!--end::Item-->
											</ul>
											<!--end::Breadcrumb-->
											<!--begin::Title-->
											<h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bolder fs-1 lh-0">Paket Detay</h1>
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
									<!--begin::Layout-->
									<div class="d-flex flex-column flex-xl-row">
										<!--begin::Sidebar-->
										<?php $package->showOnePackage($id); ?>
										<!--end::Sidebar-->
										<!--begin::Content-->
										<div class="flex-lg-row-fluid ms-lg-15">
											<!--begin:::Tab content-->
											<div class="tab-content" id="myTabContent">
												<!--begin:::Tab pane-->
												<div class="tab-pane fade show active" id="kt_customer_view_overview_tab" role="tabpanel">
													<!--begin::Card-->
													<div class="card pt-4 mb-6 mb-xl-9">
														<!--begin::Card header-->
														<div class="card-header border-0">
															<!--begin::Card title-->
															<div class="card-title">
																<h2>Öğrenci Listesi</h2>
															</div>
															<!--end::Card title-->
														</div>
														<!--end::Card header-->
														<!--begin::Card body-->
														<div class="card-body pt-0 pb-5">
															<!--begin::Table-->
															<table class="table align-middle table-row-dashed gy-5" id="kt_table_packdetails_payment">
																<thead class="border-bottom border-gray-200 fs-7 fw-bold">
																	<tr class="text-start text-muted text-uppercase gs-0">
																		<th class="min-w-50px">Fotoğraf</th>
																		<th class="min-w-100px">Öğrenci Adı</th>
																		<th class="min-w-100px">Üyelik Bitiş Tarihi</th>
																		<th class="text-end min-w-100px pe-4">İşlemler</th>
																	</tr>
																</thead>
																<tbody class="fs-6 fw-semibold text-gray-600">
																	<?php
																	$package->showPackageBuyers($id);
																	?>
																</tbody>
																<!--end::Table body-->
															</table>
															<!--end::Table-->
														</div>
														<!--end::Card body-->
													</div>
													<!--end::Card-->
												</div>
												<!--end:::Tab pane-->
											</div>
											<!--end:::Tab content-->
										</div>
										<!--end::Content-->
									</div>
									<!--end::Layout-->
									<!--begin::Modals-->
									<!--begin::Modal - New Address-->
									<div class="modal fade" id="kt_modal_update_customer" tabindex="-1" aria-hidden="true">
										<!--begin::Modal dialog-->
										<div class="modal-dialog modal-dialog-centered mw-650px">
											<!--begin::Modal content-->
											<div class="modal-content">
												<!--begin::Form-->
												<?php $package->showUpdatePackage($id); ?>
												<!--end::Form-->
											</div>
										</div>
									</div>
									<!--end::Modal - New Address-->
									<!--end::Modals-->
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
		<script src="assets/js/custom/apps/packdetails/view/add-payment.js"></script><!-- 
		<script src="assets/js/custom/apps/packdetails/view/adjust-balance.js"></script>
		<script src="assets/js/custom/apps/packdetails/view/invoices.js"></script>
		<script src="assets/js/custom/apps/packdetails/view/payment-method.js"></script> -->
		<script src="assets/js/custom/apps/packdetails/view/payment-table.js"></script><!-- 
		<script src="assets/js/custom/apps/packdetails/view/statement.js"></script>-->

		<script src="assets/js/widgets.bundle.js"></script>
		<script src="assets/js/custom/widgets.js"></script>
		<script src="assets/js/custom/apps/chat/chat.js"></script>
		<script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
		<script src="assets/js/custom/utilities/modals/create-account.js"></script>
		<script src="assets/js/custom/utilities/modals/create-app.js"></script>
		<script src="assets/js/custom/utilities/modals/new-card.js"></script>
		<script src="assets/js/custom/utilities/modals/users-search.js"></script>
		<!--end::Custom Javascript-->
		<!--end::Javascript-->
	</body>
	<script>
		$(document).ready(function() {
			$('#packageUpdate').on('click', function() {


				const id = $('#id').val();
				const packageName = $('#name').val();
				const class_id = $('#class_id').val();
				const monthly_fee = $('#monthly_fee').val();
				const subscription_period = $('#subscription_period').val();

				// Boş alan kontrolü
				console.log()

				// Ajax ile gönder
				$.ajax({
					url: 'includes/ajax.php?service=updatePackage',
					type: 'POST',
					data: {
						id: id,
						packageName: packageName,
						class_id: class_id,
						monthly_fee: monthly_fee,
						subscription_period: subscription_period
					},
					success: function(response) {
						Swal.fire({
							icon: 'success',
							title: 'Başarılı',
							text: 'Paket başarıyla kaydedildi.'
						}).then(() => {
							$('#packageCreate').modal('hide');
							$('#packageCreate input, #packageCreate select').val('');
							location.reload();
						});
					},
					error: function(xhr, status, error) {
						// error.message doğrudan burada undefined olabilir, bu yüzden response'dan alıyoruz
						let errorMessage = 'Bilinmeyen hata oluştu';

						if (xhr.responseJSON && xhr.responseJSON.message) {
							errorMessage = xhr.responseJSON.message;
						} else if (xhr.responseText) {
							try {
								let json = JSON.parse(xhr.responseText);
								if (json.message) errorMessage = json.message;
							} catch (e) {
								// JSON parse edilemedi, errorMessage değişmeden kalır
							}
						}

						console.log(errorMessage); // Hata mesajını konsola yazdır

						Swal.fire({
							icon: 'error',
							title: 'Hata',
							text: errorMessage
						});
					}
				});

			});
			$('#kt_modal_update_customer_cancel').on('click', function(e) {
				e.preventDefault();
				$('#kt_modal_update_customer').modal('hide');
			});
		});
	</script>
	<!--end::Body-->

</html>
<?php } else {
	header("location: index");
}
