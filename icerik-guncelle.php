<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 3 or $_SESSION['role'] == 4 or $_SESSION['role'] == 8)) {
	include_once "classes/dbh.classes.php";
	include "classes/topics.classes.php";
	require_once "classes/classes.classes.php";
	include "classes/lessons.classes.php";
	include "classes/units.classes.php";
	$classes = new Classes();
	$lessons = new Lessons();
	$units = new Units();
	$data = $classes->getContent($_GET['q']);



	$files = $data['files'];
	$videos = $data['videos'];
	$wordwalls = $data['wordwall'];

	$data = $data['data'];




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
									<!--begin::Card-->
									<form class="form" action="#" id="kt_modal_add_content_form" data-kt-redirect="icerikler">
										<div class="card-body pt-5">
											<input type="hidden" name="content_id" value="<?= $data['id'] ?>">

											<div class="mb-7">
												<label class="fs-6 fw-semibold mb-3">
													<span>Görsel</span>
													<span class="ms-1" data-bs-toggle="tooltip" title="İzin verilen dosya türleri: png, jpg, jpeg.">
														<i class="ki-duotone ki-information fs-7">
															<span class="path1"></span>
															<span class="path2"></span>
															<span class="path3"></span>
														</i>
													</span>
												</label>
												<div class="mt-1">
													<style>
														.image-input-placeholder {
															background-image: url('assets/media/svg/files/blank-image.svg');
														}

														[data-bs-theme="dark"] .image-input-placeholder {
															background-image: url('assets/media/svg/files/blank-image-dark.svg');
														}
													</style>
													<div class="image-input image-input-outline image-input-placeholder <?= empty($data['cover_img']) ? 'image-input-empty' : '' ?>" data-kt-image-input="true">
														<div class="image-input-wrapper w-100px h-100px"
															style="background-image: url('<?= !empty($data['cover_img']) ? ltrim(htmlspecialchars($data['cover_img']), './') : '' ?>')">

														</div>
														<label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Görsel Ekle">
															<i class="ki-duotone ki-pencil fs-7">
																<span class="path1"></span>
																<span class="path2"></span>
																<span class="path3"></span>
															</i>
															<input type="file" name="photo" id="photo" accept=".png, .jpg, .jpeg, .PNG, .JPG, .JPEG" />
															<input type="hidden" name="avatar_remove" />
														</label>
														<span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Fotoğrafı İptal Et">
															<i class="ki-duotone ki-cross fs-2">
																<span class="path1"></span>
																<span class="path2"></span>
															</i>
														</span>
														<span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
															<i class="ki-duotone ki-cross fs-2">
																<span class="path1"></span>
																<span class="path2"></span>
															</i>
														</span>
													</div>
												</div>
											</div>
											<div class="fv-row mb-7">
												<label class="required fs-6 fw-semibold mb-2">İçerik Başlığı</label>
												<input type="text" id="name" class="form-control form-control-solid" placeholder="İçerik" name="name"
													value="<?= htmlspecialchars($data['title']) ?>" />
											</div>
											<div class="fv-row mb-7">
												<label class="required fs-6 fw-semibold mb-2">Kısa Açıklama</label>
												<input type="text" id="short_desc" class="form-control form-control-solid" placeholder="Kısa Açıklama Yazın" name="short_desc"
													value="<?= htmlspecialchars($data['summary']) ?>" />
											</div>
											<div class="fv-row mb-7 mt-7">
												<label class="required fs-6 fw-semibold mb-2">Sınıf</label>
												<select id="classes" name="classes" aria-label="Sehir Seçiniz" data-control="select2" data-placeholder="Sınıf Seçiniz..." class="form-select form-select-solid fw-bold">
													<option value="">Sınıf Seçin</option>
													<?php
													// Varsayalım ki $classes adında sınıflar diziniz var.
													$allClasses = $classes->getClassesList();
													foreach ($allClasses as $class) {
														$selected = ($class['id'] == $data['class_id']) ? 'selected' : '';
														echo "<option value='{$class['id']}' {$selected}>" . htmlspecialchars($class['name']) . "</option>";
													}
													?>

												</select>
											</div>
											<div class="fv-row mb-7">
												<label class="required fs-6 fw-semibold mb-2">Ders</label>
												<?php
												$allLessons = $classes->getLessonsList($data['class_id']);

												?>
												<select id="lessons" name="lessons" aria-label="Ders Seçiniz" data-control="select2" data-placeholder="Ders Seçiniz..." class="form-select form-select-solid fw-bold">
													<option value="">Ders Seçiniz...</option>
													<?php

													foreach ($allLessons as $lesson) {
														$selected = ($lesson['id'] == $data['lesson_id']) ? 'selected' : '';
														echo "<option value='{$lesson['id']}' {$selected}>" . htmlspecialchars($lesson['name']) . "</option>";
													}
													?>
													<!-- <option value="1" <?= ($data['lesson_id'] == 1) ? 'selected' : '' ?>>Örnek Ders 1</option> -->
												</select>
											</div>
											<div class="fv-row mb-7">

												<label class="required fs-6 fw-semibold mb-2">Ünite</label>
												<select id="units" name="units" aria-label="Ünite Seçiniz" data-control="select2" data-placeholder="Ünite Seçiniz..." class="form-select form-select-solid fw-bold">
													<option value="">Ünite Seçin</option>
													<?php
													$allUnits = $classes->getUnitsList($data['class_id'], $data['lesson_id']);

													foreach ($allUnits as $unit) {
														$selected = ($unit['id'] == $data['unit_id']) ? 'selected' : '';
														echo "<option value='{$unit['id']}' {$selected}>" . htmlspecialchars($unit['name']) . "</option>";
													}
													?>
													<!-- <option value="1" <?= ($data['unit_id'] == 1) ? 'selected' : '' ?>>Örnek Ünite 1</option> -->
												</select>
											</div>





											<div class="fv-row mb-7">
												<label class="required fs-6 fw-semibold mb-2">Konu</label>
												<select id="topics" name="topics" aria-label="Konu Seçiniz" data-control="select2" data-placeholder="Konu Seçiniz..." class="form-select form-select-solid fw-bold">
													<option value="">Konu Seçiniz...</option>
													<?php
													// Varsayalım ki $topics adında konular diziniz var.
													// Bu kısmı kendi konu verilerinizi çeken kodunuzla değiştirmelisiniz.
													$allTopics = $classes->getTopicsList($data['class_id'], $data['lesson_id'], $data['unit_id']);
													foreach ($allTopics as $topic) {
														$selected = ($topic['id'] == $data['topic_id']) ? 'selected' : '';
														echo "<option value='{$topic['id']}' {$selected}>" . htmlspecialchars($topic['name']) . "</option>";
													}
													?>
													<!-- <option value="4" <?= ($data['topic_id'] == 4) ? 'selected' : '' ?>>Örnek Konu 4</option> -->
												</select>
											</div>
											<div class="fv-row mb-7">
												<label class="fs-6 fw-semibold mb-2">Alt Konu</label>
												<select id="sub_topics" name="sub_topics" aria-label="Alt Konu Seçiniz" data-control="select2" data-placeholder="Alt Konu Seçiniz..." class="form-select form-select-solid fw-bold">
													<option value="">Alt Konu Seçiniz...</option>
													<?php
													// Varsayalım ki $subTopics adında alt konular diziniz var.
													// Bu kısmı kendi alt konu verilerinizi çeken kodunuzla değiştirmelisiniz.
													$allSubTopics = $classes->getSubTopicsList($data['class_id'], $data['lesson_id'], $data['unit_id'], $data['topic_id']);
													foreach ($allSubTopics as $subTopic) {
														$selected = ($subTopic['id'] == $data['subtopic_id']) ? 'selected' : '';
														echo "<option value='{$subTopic['id']}' {$selected}>" . htmlspecialchars($subTopic['name']) . "</option>";
													}
													?>
													<!-- <option value="0" <?= ($data['subtopic_id'] == 0) ? 'selected' : '' ?>>Alt Konu Yok (0)</option> -->
												</select>
											</div>
											<div class="row mt-4">
												<label class="required fs-6 fw-semibold mb-2" for="chooseOne">İçerik Türü </label>
												<div class="fv-row mb-7 mt-4" id="chooseOne">

													<label>
														<input class="form-check-input" type="radio" name="secim" value="video_link"> Video URL
													</label>
													<label>
														<input class="form-check-input ms-10" type="radio" name="secim" value="file_path"> Dosya Yükle
													</label>
													<label>
														<input class="form-check-input ms-10" type="radio" name="secim" value="content" checked> Text
													</label>
													<label>
														<input class="form-check-input ms-10" type="radio" name="secim" value="wordwall"> Interaktif Oyun
													</label>
												</div>

												<div id="videoInput" class="mb-4" style="display:none;">
													<label for="video_url">Video Link (Vimeo):</label>
													<input type="text" class="form-control" name="video_url" id="video_url" value="<?= htmlspecialchars($videos['video_url']  ?? '') ?>">
												</div>

												<div id="fileInput" class="mb-4" style="display:none;">
													<label for="file_path">Dosya Yükle:</label>
													<input type="file" class="form-control" name="file_path[]" id="files" multiple accept=".xls,.xlsx,.doc,.docx,.ppt,.pptx,.png,.jpeg,.jpg,.svg,.pdf">
													<div id="existingFiles" class="mt-2">
														<?php foreach ($files as $file): ?>
															<div class="border p-3 rounded mb-3" data-file-id="<?= $file['id'] ?>">
																<div class="mb-2"><strong>Dosya:</strong> <?= htmlspecialchars($file['file_path']) ?></div>

																<div class="row align-items-center">
																	<!-- Açıklama alanı -->
																	<div class="col-md-8 mb-2 mb-md-0">
																		<input type="text" class="form-control form-control-sm file-description-input"
																			value="<?= htmlspecialchars($file['description']) ?>"
																			placeholder="Açıklama girin...">
																	</div>

																	<!-- Tüm butonlar yan yana -->
																	<div class="col-md-4 text-end">
																		<div class="d-flex justify-content-end gap-2">
																			<button class="btn btn-sm btn-success update-description-btn">Güncelle</button>
																			<a class="btn btn-sm btn-secondary"
																				href="<?= str_replace('../', '', $file['file_path']) ?>" target="_blank">Görüntüle</a>
																			<button class="btn btn-sm btn-danger delete-file-btn">Sil</button>
																		</div>
																	</div>
																</div>
															</div>
														<?php endforeach; ?>
													</div>

													<div id="fileDescriptions"></div>
												</div>

												<div id="wordwallInputs" class="mb-4" style="display:none;">
													<label>WordWall Iframe Linkleri:</label>
													<div id="dynamicFields">
														<?php
														// Eğer $data['interactive_game_links'] JSON string olarak geliyorsa
														$interactiveGames = $wordwalls;
														if (count($interactiveGames) > 0) {

															foreach ($interactiveGames as $index => $game) {
														?>
																<div class="input-group mb-2" data-index="<?= $index ?>">
																	<input type="text" name="wordWallTitles[]" class="form-control me-2" placeholder="Başlık" value="<?= htmlspecialchars($game['wordwall_title'] ?? '') ?>">
																	<input type="text" name="wordWallUrls[]" class="form-control me-2" placeholder="URL" value="<?= htmlspecialchars($game['wordwall_url'] ?? '') ?>">
																	<button type="button" class="btn btn-danger removeField">Sil</button>
																</div>
															<?php
															}
														} else {
															// Eğer hiç oyun yoksa, bir tane boş alan göster
															?>
															<div class="input-group mb-2" data-index="0">
																<input type="text" name="wordWallTitles[]" class="form-control me-2" placeholder="Başlık">
																<input type="text" name="wordWallUrls[]" class="form-control me-2" placeholder="URL">
																<button type="button" class="btn btn-danger removeField">Sil</button>
															</div>
														<?php
														}
														?>
													</div>
													<button type="button" id="addField" class="btn btn-sm btn-primary mt-2">Ekle</button>
												</div>
											</div>

											<div id="textInput" class="mb-4" style="display:none;">
												<label for="mcontent">Metin İçeriği:</label>
												<textarea class="form-control tinymce-editor" name="mcontent" id="mcontent" rows="4"><?= htmlspecialchars($data['text_content'] ?? '') ?></textarea>
											</div>


											<div class="modal-footer d-flex justify-content-end">
												<button type="submit" id="updateContent" class="btn btn-primary btn-sm">
													<span class="indicator-label">Güncelle</span>
													<span class="indicator-progress">Lütfen Bekleyin...
														<span class="spinner-border spinner-border-sm align-middle ms-2"></span>
													</span>
												</button>
											</div>
										</div>
									</form>
									<!--end::Card-->
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
		<script src="assets/plugins/custom/tinymce/tinymce.bundle.js"></script>
		<script src="assets/js/custom/apps/contents/list/export.js"></script>
		<script src="assets/js/custom/apps/contents/list/list.js"></script>
		<!-- <script src="assets/js/custom/apps/contents/create.js"></script> -->
		<script src="assets/js/widgets.bundle.js"></script>
		<script src="assets/js/custom/widgets.js"></script>
		<script src="assets/js/custom/apps/chat/chat.js"></script>
		<script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
		<script src="assets/js/custom/utilities/modals/create-account.js"></script>
		<script src="assets/js/custom/utilities/modals/create-app.js"></script>
		<script src="assets/js/custom/utilities/modals/users-search.js"></script>
		<script>
			$(document).ready(function() {
				tinymce.init({
					selector: '.tinymce-editor',
					// diğer ayarlar...
				});
				// Select2 kütüphanesini kullanıyorsanız başlatın (eğer zaten başlatılmadıysa)
				if ($.fn.select2) {
					$('#classes').select2();
					$('#lessons').select2();
					$('#units').select2();
					$('#topics').select2();
					$('#sub_topics').select2();
				}

				// Sınıf seçimi değiştiğinde dersleri çek
				$('#classes').on('change', function() {
					var classId = $(this).val();
					var lessonsSelect = $('#lessons');
					var unitsSelect = $('#units');
					var topicsSelect = $('#topics');
					var subTopicsSelect = $('#sub_topics');

					// Dersler, üniteler, konular ve alt konuları sıfırla
					lessonsSelect.empty().append('<option value="">Ders Seçiniz...</option>').trigger('change');
					unitsSelect.empty().append('<option value="">Ünite Seçin</option>').trigger('change');
					topicsSelect.empty().append('<option value="">Konu Seçiniz...</option>').trigger('change');
					subTopicsSelect.empty().append('<option value="">Alt Konu Seçiniz...</option>').trigger('change');

					if (classId) {
						$.ajax({
							url: 'includes/ajax.php?service=getLessonList', // Dersleri çekecek PHP dosyasının yolu
							type: 'GET',
							data: {
								class_id: classId
							},
							dataType: 'json',
							success: function(data) {
								if (data.data && data.data.length > 0) {
									$.each(data.data, function(key, lesson) {
										lessonsSelect.append('<option value="' + lesson.id + '">' + lesson.name + '</option>');
									});
								}
								lessonsSelect.trigger('change'); // Select2'nin güncellemesini tetikle
							},
							error: function(xhr, status, error) {
								console.error("Dersler çekilirken hata oluştu:", status, error);
								alert("Dersler yüklenirken bir hata oluştu. Lütfen tekrar deneyin.");
							}
						});
					}
				});

				// Ders seçimi değiştiğinde üniteleri çek
				$('#lessons').on('change', function() {
					var lessonId = $(this).val();
					var classId = $('#classes').val(); // Ünite çekerken sınıf ID'si de gerekebilir
					var unitsSelect = $('#units');
					var topicsSelect = $('#topics');
					var subTopicsSelect = $('#sub_topics');

					// Üniteler, konular ve alt konuları sıfırla
					unitsSelect.empty().append('<option value="">Ünite Seçin</option>').trigger('change');
					topicsSelect.empty().append('<option value="">Konu Seçiniz...</option>').trigger('change');
					subTopicsSelect.empty().append('<option value="">Alt Konu Seçiniz...</option>').trigger('change');

					if (lessonId && classId) {
						$.ajax({
							url: 'includes/ajax.php?service=getUnits', // Üniteleri çekecek PHP dosyasının yolu
							type: 'GET',
							data: {
								class_id: classId,
								lesson_id: lessonId
							},
							dataType: 'json',
							success: function(data) {
								if (data.data && data.data.length > 0) {
									$.each(data.data, function(key, unit) {
										unitsSelect.append('<option value="' + unit.id + '">' + unit.name + '</option>');
									});
								}
								unitsSelect.trigger('change'); // Select2'nin güncellemesini tetikle
							},
							error: function(xhr, status, error) {
								console.error("Üniteler çekilirken hata oluştu:", status, error);
								alert("Üniteler yüklenirken bir hata oluştu. Lütfen tekrar deneyin.");
							}
						});
					}
				});

				// Ünite seçimi değiştiğinde konuları çek
				$('#units').on('change', function() {
					var unitId = $(this).val();
					var lessonId = $('#lessons').val(); // Konu çekerken ders ID'si de gerekebilir
					var classId = $('#classes').val(); // Konu çekerken sınıf ID'si de gerekebilir
					var topicsSelect = $('#topics');
					var subTopicsSelect = $('#sub_topics');

					// Konular ve alt konuları sıfırla
					topicsSelect.empty().append('<option value="">Konu Seçiniz...</option>').trigger('change');
					subTopicsSelect.empty().append('<option value="">Alt Konu Seçiniz...</option>').trigger('change');

					if (unitId && lessonId && classId) {
						$.ajax({
							url: 'includes/ajax.php?service=getTopics', // Konuları çekecek PHP dosyasının yolu
							type: 'GET',
							data: {
								class_id: classId,
								lesson_id: lessonId,
								unit_id: unitId
							},
							dataType: 'json',
							success: function(data) {
								if (data.data && data.data.length > 0) {
									$.each(data.data, function(key, topic) {
										topicsSelect.append('<option value="' + topic.id + '">' + topic.name + '</option>');
									});
								}
								topicsSelect.trigger('change'); // Select2'nin güncellemesini tetikle
							},
							error: function(xhr, status, error) {
								console.error("Konular çekilirken hata oluştu:", status, error);
								alert("Konular yüklenirken bir hata oluştu. Lütfen tekrar deneyin.");
							}
						});
					}
				});

				// Konu seçimi değiştiğinde alt konuları çek (isteğe bağlı)
				$('#topics').on('change', function() {
					var topicId = $(this).val();
					var unitId = $('#units').val();
					var lessonId = $('#lessons').val();
					var classId = $('#classes').val();
					var subTopicsSelect = $('#sub_topics');

					subTopicsSelect.empty().append('<option value="">Alt Konu Seçiniz...</option>').trigger('change');

					if (topicId && unitId && lessonId && classId) {
						$.ajax({
							url: 'includes/ajax.php?service=getSubtopics', // Alt konuları çekecek PHP dosyasının yolu
							type: 'GET',
							data: {
								class_id: classId,
								lesson_id: lessonId,
								unit_id: unitId,
								topic_id: topicId
							},
							dataType: 'json',
							success: function(data) {
								if (data.data && data.data.length > 0) {
									$.each(data.data, function(key, subTopic) {
										subTopicsSelect.append('<option value="' + subTopic.id + '">' + subTopic.name + '</option>');
									});
								}
								subTopicsSelect.trigger('change'); // Select2'nin güncellemesini tetikle
							},
							error: function(xhr, status, error) {
								console.error("Alt konular çekilirken hata oluştu:", status, error);
								// Alt konu yüklenirken hata olsa bile formu bozmamak için hata mesajı göstermeyebiliriz
							}
						});
					}
				});

				// Sayfa yüklendiğinde mevcut seçili değerlere göre ilgili ders, ünite, konu ve alt konuları yükle
				// Bu kısım, sayfa ilk yüklendiğinde `data` objesindeki mevcut seçimleri dikkate alarak
				// ilgili dropdown'ları doldurmak içindir.
				// PHP'den gelen $data['class_id'], $data['lesson_id'], $data['unit_id'] değerlerini kullanır.

				var initialClassId = $('#classes').val();
				var initialLessonId = "<?= htmlspecialchars($data['lesson_id'] ?? '') ?>";
				var initialUnitId = "<?= htmlspecialchars($data['unit_id'] ?? '') ?>";
				var initialTopicId = "<?= htmlspecialchars($data['topic_id'] ?? '') ?>";
				var initialSubTopicId = "<?= htmlspecialchars($data['subtopic_id'] ?? '') ?>";






				// İçerik türü seçiminin mantığı
				$('input[name="secim"]').on('change', function() {
					var selectedValue = $(this).val();

					$('#videoInput').hide();
					$('#fileInput').hide();
					$('#textInput').hide();
					$('#wordwallInputs').hide();

					if (selectedValue === 'video_link') {
						$('#videoInput').show();
					} else if (selectedValue === 'file_path') {
						$('#fileInput').show();
					} else if (selectedValue === 'content') {
						$('#textInput').show();
					} else if (selectedValue === 'wordwall') {
						$('#wordwallInputs').show();
					}
				});

				// Sayfa yüklendiğinde varsayılan seçimi kontrol et ve ilgili inputu göster
				var initialSelectedOption = $('input[name="secim"]:checked').val();
				if (initialSelectedOption) {
					$('input[name="secim"][value="' + initialSelectedOption + '"]').trigger('change');
				} else {
					// Eğer hiçbiri seçili değilse ve veri varsa, ilk uygun olanı işaretle
					if ("<?= !empty($data['video_link']) ? 'video_link' : '' ?>" === 'video_link') {
						$('input[name="secim"][value="video_link"]').prop('checked', true).trigger('change');
					} else if ("<?= !empty($data['file_path']) ? 'file_path' : '' ?>" === 'file_path') {
						$('input[name="secim"][value="file_path"]').prop('checked', true).trigger('change');
					} else if ("<?= !empty($data['text_content']) ? 'content' : '' ?>" === 'content') {
						$('input[name="secim"][value="content"]').prop('checked', true).trigger('change');
					} else if ("<?= !empty($data['interactive_game_links']) ? 'wordwall' : '' ?>" === 'wordwall') {
						$('input[name="secim"][value="wordwall"]').prop('checked', true).trigger('change');
					}
				}


				// WordWall iframe linkleri için dinamik alan ekleme/silme
				var dynamicFieldIndex = <?= !empty($interactiveGames) ? count($interactiveGames) : 0 ?>; // PHP'den gelen mevcut oyun sayısını başlangıç indeksi olarak alıyoruz

				$('#addField').on('click', function() {
					var newField = `
					<div class="input-group mb-2" data-index="${dynamicFieldIndex}">
						<input type="text" name="wordWallTitles[]" class="form-control me-2" placeholder="Başlık">
						<input type="text" name="wordWallUrls[]" class="form-control me-2" placeholder="URL">
						<button type="button" class="btn btn-danger removeField">Sil</button>
					</div>
					`;
					$('#dynamicFields').append(newField);
					dynamicFieldIndex++;
				});

				$('#dynamicFields').on('click', '.removeField', function() {
					$(this).closest('.input-group').remove();
				});

				// Dosya yükleme alanı için açıklama inputu ekleme (mevcut dosyalardan silme de burada)
				$('#files').on('change', function() {
					var fileDescriptionsDiv = $('#fileDescriptions');
					fileDescriptionsDiv.empty(); // Her yeni seçimde eskileri temizle

					$.each(this.files, function(index, file) {
						var fileName = file.name;
						var descriptionInput = `
                <div class="mb-2">
                    <label for="file_description_${index}">${fileName} için Açıklama:</label>
                    <input type="text" class="form-control" name="file_descriptions[]" id="file_description_${index}" placeholder="Açıklama girin">
                </div>
            `;
						fileDescriptionsDiv.append(descriptionInput);
					});
				});

				// Mevcut dosyaları silme düğmeleri için olay dinleyicisi
				$('#existingFiles').on('click', '.delete-file-btn', function() {
					const button = $(this);
					const fileWrapper = button.closest('[data-file-id]');
					const fileId = fileWrapper.data('file-id');

					if (confirm("Bu dosyayı silmek istediğinizden emin misiniz?")) {
						$.ajax({
							url: 'includes/ajax.php?service=deleteContentFile',
							type: 'POST',
							data: {
								id: fileId
							},
							dataType: 'json',
							success: function(response) {
								if (response.success) {
									alert(response.message);
									location.reload(); // Sayfayı yenile (aynı sayfada kalır)
								} else {
									alert(response.message);
								}
							},
							error: function(xhr, status, error) {
								console.error("Dosya silinirken AJAX hatası:", status, error);
								alert("Dosya silinirken sunucu hatası oluştu.");
							}
						});
					}
				});
				document.querySelectorAll('.update-description-btn').forEach(button => {
					button.addEventListener('click', async function() {
						const wrapper = this.closest('[data-file-id]');
						const fileId = wrapper.dataset.fileId;
						const description = wrapper.querySelector('.file-description-input').value;

						try {
							const response = await fetch('includes/ajax.php?service=updateFileDescription', {
								method: 'POST',
								headers: {
									'Content-Type': 'application/x-www-form-urlencoded'
								},
								body: `id=${fileId}&description=${encodeURIComponent(description)}`
							});

							const data = await response.json();

							Swal.fire({
								icon: data.status === 'success' ? 'success' : 'error',
								text: data.message
							});
						} catch (error) {
							console.error(error);
							Swal.fire({
								icon: 'error',
								text: 'Bir hata oluştu, lütfen tekrar deneyin.'
							});
						}
					});
				});



				const submitButton = document.getElementById('updateContent');
				// Get the form element (still needed to easily get all form data)
				const form = document.getElementById('kt_modal_add_content_form');

				if (submitButton) {
					// Add click event listener to the button
					submitButton.addEventListener('click', function(e) {
						e.preventDefault(); // Prevent default button action (e.g., if it's inside a form)

						// Show loading indication on the button
						submitButton.setAttribute('data-kt-indicator', 'on');
						submitButton.disabled = true;

						// Collect all form data using FormData
						tinymce.triggerSave();

						// Form verisini hazırla
						const formData = new FormData(form);

						// Conditional Data Handling based on radio button selection
						const selectedOption = form.querySelector('input[name="secim"]:checked');


						// AJAX Request
						$.ajax({
							url: 'includes/ajax.php?service=updateContent', // Your backend endpoint for updating content
							type: 'POST',
							data: formData,
							processData: false, // Essential for FormData
							contentType: false, // Essential for FormData
							dataType: 'json', // Expecting JSON response
							success: function(response) {
								// Remove loading indication
								submitButton.removeAttribute('data-kt-indicator');
								submitButton.disabled = false;

								if (response.status === 'success') {
									Swal.fire({
										text: response.message || "İçerik başarıyla güncellendi!",
										icon: "success",
										buttonsStyling: false,
										confirmButtonText: "Tamam",
										customClass: {
											confirmButton: "btn btn-primary"
										}
									}).then(function(result) {
										if (result.isConfirmed) {
											const redirectUrl = form.getAttribute('data-kt-redirect');
											if (redirectUrl) {
												window.location.href = redirectUrl;
											}
										}
									});
								} else {
									Swal.fire({
										text: response.message || "İçerik güncellenirken bir hata oluştu.",
										icon: "error",
										buttonsStyling: false,
										confirmButtonText: "Tamam",
										customClass: {
											confirmButton: "btn btn-danger"
										}
									});
								}
							},
							error: function(xhr, status, error) {
								// Remove loading indication
								submitButton.removeAttribute('data-kt-indicator');
								submitButton.disabled = false;

								console.error("AJAX Hatası:", status, error);
								console.error("Sunucu Yanıtı:", xhr.responseText);

								let errorMessage = "Sunucu ile iletişimde bir hata oluştu. Lütfen tekrar deneyin.";
								try {
									const errorResponse = JSON.parse(xhr.responseText);
									if (errorResponse.message) {
										errorMessage = errorResponse.message;
									}
								} catch (e) {
									// responseText was not valid JSON
								}

								Swal.fire({
									text: errorMessage,
									icon: "error",
									buttonsStyling: false,
									confirmButtonText: "Tamam",
									customClass: {
										confirmButton: "btn btn-danger"
									}
								});
							}
						});
					});
				}

			});
		</script>
		<!--end::Custom Javascript-->
		<!--end::Javascript-->
	</body>
	<!--end::Body-->

</html>
<?php } else {
	header("location: index");
}
