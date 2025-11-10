<!DOCTYPE html>
<html lang="tr">

<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1)) {
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";

    include_once "views/pages-head.php";
    $class = new Classes();
    $class_data = $class->getClasses();

    // Sınıf seçeneklerini uygun formata dönüştür
    $class_options = [];
    foreach ($class_data as $class_item) {
        $class_options[$class_item['id']] = $class_item['name'];
    }

    // Kelime verilerini al
    $word_data = $class->todayWord();

    // Helper fonksiyon: Sınıf ID'lerini isimlere çevirir
    function getClassNames($class_ids, $options)
    {
        if (empty($class_ids)) return '-';

        $names = [];
        // Sınıf ID'lerini ayır (noktalı virgül ile ayrılmış)
        $id_array = explode(';', $class_ids);

        foreach ($id_array as $id) {
            if (isset($options[$id])) {
                $names[] = $options[$id];
            }
        }
        return empty($names) ? '-' : implode(', ', $names);
    }

?>

    <body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" data-kt-app-aside-enabled="true" data-kt-app-aside-fixed="true" data-kt-app-aside-push-toolbar="true" data-kt-app-aside-push-footer="true" class="app-default">
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
                        <div class="d-flex flex-column flex-column-fluid">
                            <?php include_once "views/toolbar.php"; ?>
                            <div id="kt_app_content" class="app-content flex-column-fluid">
                                <div id="kt_app_content_container" class="app-container container-fluid">
                                    <div class="card">
                                        <div class="card-header border-0 pt-6">
    <div class="card-title">
        <div class="d-flex align-items-center position-relative my-1 me-3">
            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
            <input type="text" data-kt-customer-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder=" Kelime/Sınıf Ara" />
        </div>
        <div class="min-w-150px">
            <select class="form-select form-select-solid" data-kt-customer-table-filter="class" data-placeholder="Sınıf Filtrele">
                
                <option value="" data-kt-select2-enabled="true">Tüm Sınıflar</option>
                <?php foreach ($class_options as $id => $name): ?>
                    <option value="<?php echo htmlspecialchars($name); ?>"><?php echo htmlspecialchars($name); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        </div>
    <div class="card-toolbar">
                                                <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                                                    <button type="button" class="btn btn-primary btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#addPackageModal">
                                                        Kelime Ekle
                                                    </button>
                                                </div>
                                                <div class="d-flex justify-content-end align-items-center d-none" data-kt-customer-table-toolbar="selected">
                                                    <div class="fw-bold me-5">
                                                        <span class="me-2" data-kt-customer-table-select="selected_count"></span>Seçildi
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0">
                                            <table id="kt_datatable_words" class="table align-middle table-row-dashed fs-6 gy-5">
                                                <thead>
                                                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                                        <th class="min-w-50px">ID</th>
                                                        <th class="min-w-125px">Kelime</th>
                                                        <th class="min-w-150px">İçerik (Anlamı)</th>
                                                        <th class="min-w-75px">Görsel</th>
                                                        <th class="min-w-100px">Sınıf</th>
                                                        <th class="min-w-150px">Baş. Tarihi</th>
                                                        <th class="min-w-150px">Bitiş Tarihi</th>
                                                        <th class="min-w-75px">Durum</th>
                                                        <th class="text-end min-w-100px">İşlemler</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="fw-semibold text-gray-600">
                                                    <?php foreach ($word_data as $row): ?>
                                                        <tr>
                                                            <td><?php echo $row['id']; ?></td>
                                                            <td><?php echo htmlspecialchars($row['word']); ?></td>
                                                            <td><?php echo htmlspecialchars(mb_substr($row['body'], 0, 40, 'UTF-8')) . (mb_strlen($row['body'], 'UTF-8') > 40 ? '...' : ''); ?></td>
                                                            <td>
                                                                <?php if (!empty($row['image'])): ?>
                                                                    <a href="<?php echo htmlspecialchars($row['image']); ?>" target="_blank">Gör</a>
                                                                <?php else: ?>
                                                                    -
                                                                <?php endif; ?>
                                                            </td>
                                                            <td><?php echo getClassNames($row['class_id'], $class_options); ?></td>
                                                            <td>
                                                                <?php
                                                                echo !empty($row['start_date'])
                                                                    ? date("d-m-Y", strtotime($row['start_date']))
                                                                    : '-';
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                echo !empty($row['end_date'])
                                                                    ? date("d-m-Y", strtotime($row['end_date']))
                                                                    : '-';
                                                                ?>
                                                            </td>

                                                            <td>
                                                                <span class="badge badge-light-<?php echo ($row['is_active'] == 1 ? 'success' : 'danger'); ?>">
                                                                    <?php echo ($row['is_active'] == 1 ? 'Aktif' : 'Pasif'); ?>
                                                                </span>
                                                            </td>
                                                            <td class="text-end">
                                                                <a href="#" class="btn btn-icon btn-sm btn-light-primary me-2 edit-btn"
                                                                    data-bs-toggle="modal" data-bs-target="#updatePackageModal"
                                                                    data-id="<?php echo $row['id']; ?>"
                                                                    data-word="<?php echo htmlspecialchars($row['word']); ?>"
                                                                    data-meaning="<?php echo htmlspecialchars($row['body']); ?>"
                                                                    data-image="<?php echo htmlspecialchars($row['image']); ?>"
                                                                    data-classes='<?php echo $row['class_id']; ?>'
                                                                    data-start-date="<?php echo htmlspecialchars($row['start_date']); ?>"
                                                                    data-end-date="<?php echo htmlspecialchars($row['end_date']); ?>"
                                                                    data-status="<?php echo $row['is_active']; ?>">
                                                                    <i class="ki-duotone ki-pencil fs-4"><span class="path1"></span><span class="path2"></span></i>
                                                                </a>

                                                                <!-- Pasif kelimeler için sil butonu -->
                                                                <a href="#" class="btn btn-icon btn-sm btn-light-danger delete-btn" data-id="<?php echo $row['id']; ?>">
                                                                    <i class="ki-duotone ki-trash-square fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                                                </a>

                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php include_once "views/footer.php"; ?>
                    </div>
                    <?php include_once "views/aside.php"; ?>
                </div>
            </div>
        </div>
        <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
            <i class="ki-duotone ki-arrow-up">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </div>

        <!-- ADD MODAL -->
        <div class="modal fade" id="addPackageModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bold">Yeni Kelime Ekle</h2>
                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="ki-duotone ki-cross fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </div>
                    </div>
                    <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                        <form id="addPackageForm" class="form" action="api/add-word.php" method="POST" enctype="multipart/form-data">
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Kelime</label>
                                <input type="text" name="word" id="add_word" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Örn: Algoritma" required />
                            </div>
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Anlamı (İçerik)</label>
                                <textarea name="meaning" id="add_meaning" class="form-control form-control-solid mb-3 mb-lg-0" rows="3" placeholder="Kelimenin anlamını yazın..." required></textarea>
                            </div>

                            <div class="fv-row mb-7">
                                <label class="fw-semibold fs-6 mb-2">Görsel</label>
                                <input type="file" name="image" id="add_image" class="form-control form-control-solid mb-3 mb-lg-0" accept="image/*" />
                            </div>

                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Sınıf</label>
                                <select name="classes[]" id="add_classes" class="form-select form-select-solid" multiple="multiple" required>

                                    <?php foreach ($class_options as $id => $name): ?>
                                        <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($name); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="row row-cols-lg-2 g-10">
                                <div class="col">
                                    <div class="fv-row mb-7">
                                        <label class="required fw-semibold fs-6 mb-2">Başlangıç Tarihi</label>
                                        <input type="date" name="start_date" id="add_start_date" class="form-control form-control-solid" required />
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="fv-row mb-7">
                                        <label class="required fw-semibold fs-6 mb-2">Bitiş Tarihi</label>
                                        <input type="date" name="end_date" id="add_end_date" class="form-control form-control-solid" required />
                                    </div>
                                </div>
                            </div>

                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Durum</label>
                                <select name="status" id="add_status" class="form-select form-select-solid" required>
                                    <option value="1">Aktif</option>
                                    <option value="0">Pasif</option>
                                </select>
                            </div>

                            <div class="text-center pt-15">
                                <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">İptal</button>
                                <button type="submit" class="btn btn-primary" id="add_submit_btn">
                                    <span class="indicator-label">Kaydet</span>
                                    <span class="indicator-progress">Lütfen bekleyiniz...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- UPDATE MODAL -->
        <div class="modal fade" id="updatePackageModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bold">Kelimeyi Güncelle</h2>
                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="ki-duotone ki-cross fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </div>
                    </div>
                    <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                        <form id="updatePackageForm" class="form" action="api/update-word.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id" id="update_id" />

                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Kelime</label>
                                <input type="text" name="word" id="update_word" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Kelime" required />
                            </div>
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Anlamı (İçerik)</label>
                                <textarea name="meaning" id="update_meaning" class="form-control form-control-solid mb-3 mb-lg-0" rows="3" placeholder="Anlam" required></textarea>
                            </div>

                            <div class="fv-row mb-7">
                                <label class="fw-semibold fs-6 mb-2">Görsel</label>
                                <input type="file" name="image" id="update_image" class="form-control form-control-solid mb-3 mb-lg-0" accept="image/*" />
                                <small class="form-text text-muted" id="current_image_info">Mevcut görsel: Yok</small>
                            </div>

                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Sınıf</label>
                                <select name="classes[]" id="update_classes" class="form-select form-select-solid" multiple="multiple" required>

                                    <?php foreach ($class_options as $id => $name): ?>
                                        <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($name); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="row row-cols-lg-2 g-10">
                                <div class="col">
                                    <div class="fv-row mb-7">
                                        <label class="required fw-semibold fs-6 mb-2">Başlangıç Tarihi</label>
                                        <input type="date" name="start_date" id="update_start_date" class="form-control form-control-solid" required />
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="fv-row mb-7">
                                        <label class="required fw-semibold fs-6 mb-2">Bitiş Tarihi</label>
                                        <input type="date" name="end_date" id="update_end_date" class="form-control form-control-solid" required />
                                    </div>
                                </div>
                            </div>

                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Durum</label>
                                <select name="status" id="update_status" class="form-select form-select-solid" required>
                                    <option value="1">Aktif</option>
                                    <option value="0">Pasif</option>
                                </select>
                            </div>

                            <div class="text-center pt-15">
                                <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">İptal</button>
                                <button type="submit" class="btn btn-primary" id="update_submit_btn">
                                    <span class="indicator-label">Güncelle</span>
                                    <span class="indicator-progress">Lütfen bekleyiniz...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            var hostUrl = "assets/";
        </script>
        <script src="assets/plugins/global/plugins.bundle.js"></script>
        <script src="assets/js/scripts.bundle.js"></script>
        <script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
        <script src="assets/js/widgets.bundle.js"></script>
        <script src="assets/js/custom/widgets.js"></script>

        <script>
            // Datatables başlatma
           // Datatables başlatma
var KTDatatables = (function() {
    var table;

    var initDatatable = function() {
        table = $('#kt_datatable_words').DataTable({
            info: false,
            order: [],
            paging: true,
            searching: true,
            columnDefs: [{
                targets: 8,
                orderable: false,
                searchable: false,
                className: 'text-end',
            }],
            initComplete: function() {
                const searchInput = document.querySelector('[data-kt-customer-table-filter="search"]');
                if (searchInput) {
                    searchInput.addEventListener('keyup', function(e) {
                        table.search(e.target.value).draw();
                    });
                }
            }
        });

        // Sınıf Filtresi Ekleme
        const classFilter = document.querySelector('[data-kt-customer-table-filter="class"]');
        if (classFilter) {
            $(classFilter).on('change', function() {
                const classValue = $(this).val();

                // 4. sütun (Sınıf) üzerinde arama yap
                if (classValue === '') {
                    table.column(4).search('').draw(); // Tüm sınıfları göster
                } else {
                    // Seçilen sınıf adını içerenleri bul (regex tabanlı arama, index 4)
                    // Not: Sınıf adı virgülle ayrılmış bir listede olduğu için regex kullanımı uygundur.
                    table.column(4).search(classValue, true, false).draw();
                }
            });
        }
    };

    return {
        init: function() {
            initDatatable();
        },
    };
})();

            // Sayfa yüklendiğinde çalışacak kod
            $(document).ready(function() {
                // Datatable'ı başlat
                KTDatatables.init();

                // Modal event listener'ları
                initializeModalEvents();

                // Form submit event'leri
                initializeFormSubmits();
            });

            // Modal event'lerini başlatma
            function initializeModalEvents() {
                // Edit buton event'i
                $(document).on('click', '.edit-btn', function(e) {
                    e.preventDefault();

                    const id = $(this).data('id');
                    const word = $(this).data('word');
                    const meaning = $(this).data('meaning');
                    const image = $(this).data('image');
                    const classes = $(this).data('classes');
                    const startDate = $(this).data('start-date');
                    const endDate = $(this).data('end-date');
                    const status = $(this).data('status');

                    // Form alanlarını doldur
                    $('#update_id').val(id);
                    $('#update_word').val(word);
                    $('#update_meaning').val(meaning);
                    $('#update_start_date').val(startDate);
                    $('#update_end_date').val(endDate);

                    // Görsel bilgisi
                    const currentImageInfo = $('#current_image_info');
                    currentImageInfo.html(image ? `Mevcut görsel: <a href="${image}" target="_blank">Görüntüle</a>` : 'Mevcut görsel: Yok');

                    // Sınıf ID'lerini array'e çevir ve select'e set et
                    const classArray = classes ? classes.split(';') : [];
                    $('#update_classes').val(classArray);

                    // Durumu set et
                    $('#update_status').val(status);
                });

                // Modal kapandığında formları resetle
                $('#addPackageModal').on('hidden.bs.modal', function() {
                    $('#addPackageForm')[0].reset();
                    $('#add_classes').val([]);
                    $('#add_status').val('1');
                });

                $('#updatePackageModal').on('hidden.bs.modal', function() {
                    $('#updatePackageForm')[0].reset();
                    $('#update_classes').val([]);
                    $('#update_status').val('1');
                });
            }

            // Form submit event'lerini başlatma
            function initializeFormSubmits() {
                // Add form submit - AJAX ile backend'e gönder
                $('#addPackageForm').on('submit', function(e) {
                    e.preventDefault();

                    const submitBtn = $('#add_submit_btn');
                    const indicator = submitBtn.find('.indicator-label');
                    const progress = submitBtn.find('.indicator-progress');

                    // Loading state
                    indicator.addClass('d-none');
                    progress.removeClass('d-none');
                    submitBtn.prop('disabled', true);

                    // FormData oluştur
                    const formData = new FormData(this);

                    // AJAX ile backend'e gönder
                    $.ajax({
                        url: './includes/ajax.php?service=addTodayWord',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    text: response.message || "Kelime başarıyla eklendi!",
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Tamam",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                }).then(() => {
                                    // Modalı kapat ve formu resetle
                                    $('#addPackageModal').modal('hide');
                                    $('#addPackageForm')[0].reset();
                                    $('#add_classes').val([]);
                                    $('#add_status').val('1');

                                    // Sayfayı yenile (veya datatable'ı güncelle)
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    text: response.message || "Bir hata oluştu!",
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Tamam",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                text: "Sunucu hatası: " + error,
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Tamam",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            });
                        },
                        complete: function() {
                            // Loading state'i kaldır
                            indicator.removeClass('d-none');
                            progress.addClass('d-none');
                            submitBtn.prop('disabled', false);
                        }
                    });
                });

                // Update form submit - AJAX ile backend'e gönder
                $('#updatePackageForm').on('submit', function(e) {
                    e.preventDefault();

                    const submitBtn = $('#update_submit_btn');
                    const indicator = submitBtn.find('.indicator-label');
                    const progress = submitBtn.find('.indicator-progress');

                    // Loading state
                    indicator.addClass('d-none');
                    progress.removeClass('d-none');
                    submitBtn.prop('disabled', true);

                    // FormData oluştur
                    const formData = new FormData(this);

                    // AJAX ile backend'e gönder
                    $.ajax({
                        url: './includes/ajax.php?service=updateTodayWord',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    text: response.message || "Kelime başarıyla güncellendi!",
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Tamam",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                }).then(() => {
                                    // Modalı kapat
                                    $('#updatePackageModal').modal('hide');

                                    // Sayfayı yenile (veya datatable'ı güncelle)
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    text: response.message || "Bir hata oluştu!",
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Tamam",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                text: "Sunucu hatası: " + error,
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Tamam",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            });
                        },
                        complete: function() {
                            // Loading state'i kaldır
                            indicator.removeClass('d-none');
                            progress.addClass('d-none');
                            submitBtn.prop('disabled', false);
                        }
                    });
                });
            }



            // Silme işlemi
            $(document).on('click', '.delete-btn', function(e) {
                e.preventDefault();
                const wordId = $(this).data('id');

                Swal.fire({
                    text: `ID ${wordId} numaralı kelimeyi silmek istediğinizden emin misiniz?`,
                    icon: "error",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Evet, Sil!",
                    cancelButtonText: "Hayır, İptal",
                    customClass: {
                        confirmButton: "btn btn-danger",
                        cancelButton: "btn btn-light"
                    }
                }).then(function(result) {
                    if (result.isConfirmed) {
                        // AJAX ile silme işlemi
                        $.post('./includes/ajax.php?service=deleteTodayWord', {
                            id: wordId
                        }, function(response) {
                            if (response.success) {
                                Swal.fire({
                                    text: response.message || "Kelime başarıyla silindi!",
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Tamam",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    text: response.message || "Bir hata oluştu!",
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Tamam",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                });
                            }
                        }).fail(function() {
                            Swal.fire({
                                text: "Sunucu hatası!",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Tamam",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            });
                        });
                    }
                });
            });
        </script>
    </body>

</html>
<?php } else {
    header("location: index");
}
