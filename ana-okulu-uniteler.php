<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 10001)) {
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";

    include_once "views/pages-head.php";
    $class = new Classes();
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
                                    <div class="card">
                                        <!--begin::Card header-->
                                        <div class="card-header border-0 pt-6">
                                            <!--begin::Card title-->
                                            <div class="card-title">
                                                <!--begin::Search-->
                                                <div class="d-flex align-items-center position-relative my-1">
                                                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                    <input type="text" data-kt-customer-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder=" Ara" />
                                                </div>
                                                <!--end::Search-->
                                            </div>
                                            <!--begin::Card title-->
                                            <!--begin::Card toolbar-->
                                            <div class="card-toolbar">
                                                <!--begin::Toolbar-->
                                                <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                                                    <!--begin::Add school-->
                                                    <!--end::Add school--><button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPreSchoolUnitModal">
                                                        Ünite Ekle
                                                    </button>

                                                </div>
                                                <div class="modal fade" id="addPreSchoolUnitModal" tabindex="-1" aria-labelledby="addPreSchoolUnitModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="addPreSchoolUnitModalLabel">Yeni Ünite Ekle</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                               <form id="addUnitForm">
                                                                    <input type="hidden" id="unit_id" name="unit_id" value="">

                                                                    <div class="mb-3">
                                                                        <?php
                                                                        // Bu kısımda $class nesnesinin tanımlı olduğundan ve getMainSchoolClassesList() metodunun çalıştığından emin olun.
                                                                        // Bu, sınıf listesini veritabanınızdan çeken gerçek PHP kodunuz olmalıdır.
                                                                        // Örnek olarak bir sınıf listesi oluşturulmuştur.
                                                                        $classList = [];
                                                                        if (isset($class) && method_exists($class, 'getMainSchoolClassesList')) {
                                                                            $classList = $class->getMainSchoolClassesList();
                                                                        }
                                                                        ?>
                                                                        <label for="add_class_id" class="required form-label">Yaş / Sınıf </label>
                                                                        <select class="form-select form-control" id="add_class_id" name="class_id" required>
                                                                            <option selected disabled value="">Seçiniz...</option>
                                                                            <?php foreach ($classList as $d): ?>
                                                                                <option value="<?= htmlspecialchars($d['id']) ?>"><?= htmlspecialchars($d['name']) ?></option>
                                                                            <?php endforeach; ?>
                                                                        </select>
                                                                    </div>

                                                                    <div class="mb-3 mt-3">
                                                                        <label for="add_lesson_id" class="required form-label">Ders Adı</label>
                                                                        <select class="form-select form-control" id="add_lesson_id" name="add_lesson_id" required>
                                                                            <option selected disabled value="">Önce sınıf seçiniz...</option>
                                                                        </select>
                                                                    </div>

                                                                    <div class="mb-3" id="developmentPackageGroupAdd" style="display: none;">

                                                                    </div>

                                                                    <div class="mb-3 mt-3">
                                                                        <label for="unit_name" class="required form-label">Ünite Adı</label>
                                                                        <input type="text" class="form-control" id="add_unit_name" name="add_unit_name" placeholder="Ünite Adı" required>
                                                                    </div>

                                                                    <div class="mb-3 mt-3">
                                                                        <label class="required fs-6 fw-semibold mb-2">Ünite Sırası</label>
                                                                        <input type="number" class="form-control" placeholder="Ünite Sırası Girin" name="add_unit_order" id="add_unit_order" required>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                                                                <button type="button" id="saveUnitBtn" class="btn btn-primary">Kaydet</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal fade" id="updateUnitModal" tabindex="-1" aria-labelledby="updateUnitModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">

                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="updateUnitModalLabel">Üniteyi Güncelle</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <form id="updateUnitForm">
                                                                    <input type="hidden" id="unit_id" name="unit_id" value="">

                                                                    <div class="mb-3">
                                                                        <?php
                                                                        // Bu kısımda $class nesnesinin tanımlı olduğundan ve getMainSchoolClassesList() metodunun çalıştığından emin olun.
                                                                        // Bu, sınıf listesini veritabanınızdan çeken gerçek PHP kodunuz olmalıdır.
                                                                        // Örnek olarak bir sınıf listesi oluşturulmuştur.
                                                                        $classList = [];
                                                                        if (isset($class) && method_exists($class, 'getMainSchoolClassesList')) {
                                                                            $classList = $class->getMainSchoolClassesList();
                                                                        }
                                                                        ?>
                                                                        <label for="class_id" class="required form-label">Yaş / Sınıf </label>
                                                                        <select class="form-select form-control" id="class_id" name="class_id" required>
                                                                            <option selected disabled value="">Seçiniz...</option>
                                                                            <?php foreach ($classList as $d): ?>
                                                                                <option value="<?= htmlspecialchars($d['id']) ?>"><?= htmlspecialchars($d['name']) ?></option>
                                                                            <?php endforeach; ?>
                                                                        </select>
                                                                    </div>

                                                                    <div class="mb-3 mt-3">
                                                                        <label for="lesson_id" class="required form-label">Ders Adı</label>
                                                                        <select class="form-select form-control" id="lesson_id" name="lesson_id" required>
                                                                            <option selected disabled value="">Önce sınıf seçiniz...</option>
                                                                        </select>
                                                                    </div>

                                                                    <div class="mb-3" id="developmentPackageGroup" style="display: none;">

                                                                    </div>

                                                                    <div class="mb-3 mt-3">
                                                                        <label for="unit_name" class="required form-label">Ünite Adı</label>
                                                                        <input type="text" class="form-control" id="unit_name" name="unit_name" placeholder="Ünite Adı" required>
                                                                    </div>

                                                                    <div class="mb-3 mt-3">
                                                                        <label class="required fs-6 fw-semibold mb-2">Ünite Sırası</label>
                                                                        <input type="number" class="form-control" placeholder="Ünite Sırası Girin" name="unit_order" id="unit_order" required>
                                                                    </div>
                                                                </form>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                                                                <button type="button" id="updateUnitBtn" class="btn btn-primary">Güncelle</button>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                                <!--end::Toolbar-->
                                                <!--begin::week actions-->
                                                <div class="d-flex justify-content-end align-items-center d-none" data-kt-customer-table-toolbar="selected">
                                                    <div class="fw-bold me-5">
                                                        <span class="me-2" data-kt-customer-table-select="selected_count"></span>Seçildi
                                                    </div>
                                                </div>
                                                <!--end::week actions-->
                                            </div>
                                            <!--end::Card toolbar-->
                                        </div>
                                        <!--end::Card header-->
                                        <!--begin::Card body-->
                                        <div class="card-body pt-0">
                                            <!-- Button trigger modal -->


                                            <!-- Modal -->
                                            <!-- Button trigger modal (id değeri burada veriliyor) -->




                                            <!--begin::Table-->
                                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table">
                                                <thead>
                                                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">

                                                        <th class="min-w-125px">Sınıf</th>
                                                        <th class="min-w-125px">Ders</th>
                                                        <th class="min-w-125px">Ünite</th>
                                                        <th class="min-w-125px">Sıra No</th>

                                                        <th class="text-end min-w-70px">İşlemler</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="fw-semibold text-gray-600">
                                                    <?php
                                                    $list = $class->getMainSchoolUnitList();


                                                    foreach ($list as $key => $value): ?>
                                                        <tr>

                                                            <td>
                                                                <a href="#" class="text-gray-800 text-hover-primary mb-1">
                                                                    <?= htmlspecialchars($value['class_name']) ?>
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <a href="#" class="text-gray-800 text-hover-primary mb-1">
                                                                    <?= htmlspecialchars($value['unit_name']) ?>
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <a href="#" class="text-gray-800 text-hover-primary mb-1">
                                                                    <?= htmlspecialchars($value['lesson_name']) ?>
                                                                </a>
                                                            </td>

                                                            <td>
                                                                <a href="#" class="text-gray-800 text-hover-primary mb-1">
                                                                    <?= htmlspecialchars($value['unit_order']) ?>
                                                                </a>
                                                            </td>

                                                            <td class="text-end">
                                                                <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                                                    data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">İşlemler
                                                                    <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                                                </a>

                                                                <!--begin::Menu-->
                                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                                                    data-kt-menu="true">


                                                                    <!--begin::Menu item-->
                                                                    <div class="menu-item px-3">
                                                                        <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#updateUnitModal" data-id="<?= htmlspecialchars($value['id']) ?>">
                                                                            Güncelle
                                                                        </a>
                                                                    </div>

                                                                    <!--begin::Menu item-->
                                                                    <div class="menu-item px-3">
                                                                        <a href="javascript:void(0);"
                                                                            class="menu-link px-3"
                                                                            data-kt-customer-table-filter="delete_row"
                                                                            onclick="handleDelete({ id: '<?= htmlspecialchars($value['id']) ?>', url: 'includes/ajax.php?service=deleteMainSchoolUnit' })">
                                                                            Sil
                                                                        </a>
                                                                    </div>
                                                                    <!--end::Menu item-->
                                                                </div>
                                                                <!--end::Menu-->
                                                            </td>
                                                        </tr>
                                                    <?php endforeach;

                                                    ?>
                                                </tbody>
                                            </table>
                                            <!--end::Table-->
                                        </div>
                                        <!--end::Card body-->
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

        <script src="assets/js/widgets.bundle.js"></script>
        <script src="assets/js/custom/widgets.js"></script>

        <script src="assets/js/fatih.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var updateUnitModal = document.getElementById('updateUnitModal');
                var developmentPackageGroup = document.getElementById('developmentPackageGroup');

                let currentDevelopmentPackageIds = [];
                let currentSelectedLessonId = null;

                updateUnitModal.addEventListener('show.bs.modal', function(event) {
                    var button = event.relatedTarget;
                    var unitId = button.getAttribute('data-id');

                    // Form alanlarını temizle ve sıfırla
                    $('#updateUnitModal #class_id').val('').trigger('change'); // class_id change'i tetikle

                    // lesson_id'yi standart select olarak temizle
                    $('#updateUnitModal #lesson_id').empty().append('<option value="">Dersler Yükleniyor...</option>');
                    $('#updateUnitModal #unit_name').val('');
                    $('#updateUnitModal #unit_order').val('');

                    // Gelişim paketi grubunu başlangıçta gizle ve temizle
                    if (developmentPackageGroup) {
                        developmentPackageGroup.style.display = 'none';
                        // Select2'yi kaldırıp sonra temizle
                        var devPackageSelect = $('#updateUnitModal #development_package_id');
                        if (devPackageSelect.data('select2')) { // Select2 başlatıldıysa kaldır
                            devPackageSelect.select2('destroy');
                        }
                        devPackageSelect.empty();
                    }

                    currentDevelopmentPackageIds = [];
                    currentSelectedLessonId = null;

                    // 1) Ünite verisini çek
                    fetch('includes/ajax.php?service=mainSchoolGetUnit&id=' + unitId)
                        .then(response => response.json())
                        .then(unitData => {
                            if (unitData.status === 'success') {
                                var classId = unitData.data.class_id;
                                currentSelectedLessonId = unitData.data.lesson_id;
                                currentDevelopmentPackageIds = unitData.data.development_package_id ?
                                    unitData.data.development_package_id.split(';') : [];

                                // Temel form alanlarını doldur
                                $('#updateUnitModal #unit_id').val(unitData.data.id);
                                $('#updateUnitModal #unit_name').val(unitData.data.unit_name);
                                $('#updateUnitModal #unit_order').val(unitData.data.unit_order);

                                // class_id'yi ayarla ve değişimi tetikle
                                // Bu, dersleri yükleyecektir
                                $('#updateUnitModal #class_id').val(classId).trigger('change');

                            } else {
                                alert('Ünite verisi alınamadı.');
                            }
                        })
                        .catch(error => {
                            console.error('Sunucu hatası (unitData):', error);
                            alert('Sunucu hatası: ' + error.message);
                        });
                });

                $('#add_class_id').on('change', function() {
                    var selectedClassId = $(this).val();
                    var lessonSelect = $('#add_lesson_id');
                    var developmentPackageContainer = $('#developmentPackageGroupAdd');

                    // lesson_id'yi standart select olarak temizle ve varsayılan metni ayarla
                    lessonSelect.empty().append('<option value="">Dersler Yükleniyor...</option>');
                    lessonSelect.prop('disabled', true); // Geçici olarak devre dışı bırak

                    developmentPackageContainer.empty().hide(); // Gelişim paketi alanını temizle ve gizle

                    if (!selectedClassId) { // Sınıf seçilmemişse
                        lessonSelect.empty().append('<option value="">Lütfen sınıf seçin.</option>');
                        lessonSelect.prop('disabled', false); // Tekrar etkinleştir
                        return;
                    }

                    $.ajax({
                        url: 'includes/ajax.php?service=mainSchoolGetLessons',
                        type: 'POST',
                        data: {
                            class_id: selectedClassId
                        },
                        dataType: 'json',
                        success: function(response) {
                            lessonSelect.empty(); // Mevcut seçenekleri temizle
                            if (response.status === 'success' && response.data.length > 0) {
                                lessonSelect.append('<option value="">Ders Seçiniz...</option>'); // Varsayılan boş seçenek
                                $.each(response.data, function(index, lesson) {
                                    lessonSelect.append(
                                        $('<option></option>')
                                        .val(lesson.id)
                                        .text(lesson.name)
                                        .data('package-type', lesson.package_type)
                                    );
                                });
                                lessonSelect.prop('disabled', false); // Etkinleştir

                                // *** ÖNEMLİ DEĞİŞİKLİK BURADA ***
                                // Eğer güncelleme modalı içiniz ve önceden seçili bir ders ID'si varsa
                                if (lessonSelect.closest('#updateUnitModal').length && currentSelectedLessonId) {
                                    // Seçenek gerçekten varsa seçimi yap
                                    if (lessonSelect.find('option[value="' + currentSelectedLessonId + '"]').length) {
                                        lessonSelect.val(currentSelectedLessonId); // Seçimi yap
                                    } else {
                                        console.warn("Önceden seçilmiş ders ID'si " + currentSelectedLessonId + " yüklenen dersler arasında bulunamadı.");
                                    }
                                    // Ders seçimi yapıldıktan sonra değişim olayını manuel olarak tetikle
                                    // Bu, gelişim paketi yükleme mantığını devreye sokacak.
                                    lessonSelect.trigger('change');
                                    currentSelectedLessonId = null; // Kullandıktan sonra sıfırla
                                } else {
                                    // Eğer güncelleme değilse veya seçili ders yoksa, yine de change'i tetikle
                                    // Gelişim paketi alanının durumunu kontrol etmek için
                                    lessonSelect.trigger('change');
                                }

                            } else {
                                lessonSelect.append('<option value="">Bu sınıfa ait ders bulunamadı.</option>');
                                lessonSelect.prop('disabled', true); // Devre dışı bırak
                            }
                        },
                        error: function() {
                            lessonSelect.empty().append('<option value="">Dersler yüklenirken hata oluştu!</option>');
                            lessonSelect.prop('disabled', true);
                            alert('Sunucu ile iletişimde hata oluştu!');
                        }
                    });
                });

                // --- lesson_id değişti: package_type'ı kontrol et ve paket listesini yükle ---
                $('#add_lesson_id').on('change', function() {
                    var selectedOption = $(this).find('option:selected');
                    var packageType = selectedOption.data('package-type');
                    var selectedLessonId = selectedOption.val();
                    var developmentPackageContainer = $('#developmentPackageGroupAdd');

                    developmentPackageContainer.empty().hide(); // Her zaman temizle ve gizle

                    if (!selectedLessonId) { // Ders seçilmemişse
                        return;
                    }

                    if (packageType == 1) {
                        $.ajax({
                            url: 'includes/ajax.php?service=getDevelopmentPackageList',
                            type: 'POST',
                            data: {
                                lesson_id: selectedLessonId
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.status === 'success' && Array.isArray(response.data) && response.data.length > 0) {
                                    let html = `
                            <label for="add_development_package_id" class="required form-label">Gelişim Paketi Seç</label>
                            <select name="add_development_package_id[]" id="add_development_package_id" class="form-select" multiple="multiple">
                        `;
                                    response.data.forEach(pkg => {
                                        html += `<option value="${pkg.id}">${pkg.name} - ${parseFloat(pkg.price).toFixed(2)}₺</option>`;
                                    });
                                    html += `
                            </select>
                            <div class="form-text">Birden fazla paket seçebilirsiniz.</div>
                        `;
                                    developmentPackageContainer.html(html).show();

                                    // Select2'yi yeni oluşturulan elementte başlat (BURAYI KORUYORUZ, Gelişim Paketi Select2 olacak)
                                    var devPackageSelect = $('#add_development_package_id');
                                    devPackageSelect.select2({
                                        placeholder: "Gelişim Paketi Seçin",
                                        allowClear: true,
                                        tags: false
                                    });

                                   

                                } else {
                                    developmentPackageContainer.html(`
                            <div class="alert alert-warning" role="alert">
                                Bu derse ait gelişim paketi bulunamadı.
                            </div>
                        `).show();
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('AJAX Hatası (getDevelopmentPackageList):', status, error, xhr.responseText);
                                alert('Gelişim paketleri yüklenirken bir hata oluştu: ' + error);
                                developmentPackageContainer.empty().hide();
                            }
                        });
                    } else {
                        developmentPackageContainer.empty().hide();
                    }
                });

                // --- class_id değişti: Dersleri yükle ---
                $('#class_id').on('change', function() {
                    var selectedClassId = $(this).val();
                    var lessonSelect = $('#lesson_id');
                    var developmentPackageContainer = $('#developmentPackageGroup');

                    // lesson_id'yi standart select olarak temizle ve varsayılan metni ayarla
                    lessonSelect.empty().append('<option value="">Dersler Yükleniyor...</option>');
                    lessonSelect.prop('disabled', true); // Geçici olarak devre dışı bırak

                    developmentPackageContainer.empty().hide(); // Gelişim paketi alanını temizle ve gizle

                    if (!selectedClassId) { // Sınıf seçilmemişse
                        lessonSelect.empty().append('<option value="">Lütfen sınıf seçin.</option>');
                        lessonSelect.prop('disabled', false); // Tekrar etkinleştir
                        return;
                    }

                    $.ajax({
                        url: 'includes/ajax.php?service=mainSchoolGetLessons',
                        type: 'POST',
                        data: {
                            class_id: selectedClassId
                        },
                        dataType: 'json',
                        success: function(response) {
                            lessonSelect.empty(); // Mevcut seçenekleri temizle
                            if (response.status === 'success' && response.data.length > 0) {
                                lessonSelect.append('<option value="">Ders Seçiniz...</option>'); // Varsayılan boş seçenek
                                $.each(response.data, function(index, lesson) {
                                    lessonSelect.append(
                                        $('<option></option>')
                                        .val(lesson.id)
                                        .text(lesson.name)
                                        .data('package-type', lesson.package_type)
                                    );
                                });
                                lessonSelect.prop('disabled', false); // Etkinleştir

                                // *** ÖNEMLİ DEĞİŞİKLİK BURADA ***
                                // Eğer güncelleme modalı içiniz ve önceden seçili bir ders ID'si varsa
                                if (lessonSelect.closest('#updateUnitModal').length && currentSelectedLessonId) {
                                    // Seçenek gerçekten varsa seçimi yap
                                    if (lessonSelect.find('option[value="' + currentSelectedLessonId + '"]').length) {
                                        lessonSelect.val(currentSelectedLessonId); // Seçimi yap
                                    } else {
                                        console.warn("Önceden seçilmiş ders ID'si " + currentSelectedLessonId + " yüklenen dersler arasında bulunamadı.");
                                    }
                                    // Ders seçimi yapıldıktan sonra değişim olayını manuel olarak tetikle
                                    // Bu, gelişim paketi yükleme mantığını devreye sokacak.
                                    lessonSelect.trigger('change');
                                    currentSelectedLessonId = null; // Kullandıktan sonra sıfırla
                                } else {
                                    // Eğer güncelleme değilse veya seçili ders yoksa, yine de change'i tetikle
                                    // Gelişim paketi alanının durumunu kontrol etmek için
                                    lessonSelect.trigger('change');
                                }

                            } else {
                                lessonSelect.append('<option value="">Bu sınıfa ait ders bulunamadı.</option>');
                                lessonSelect.prop('disabled', true); // Devre dışı bırak
                            }
                        },
                        error: function() {
                            lessonSelect.empty().append('<option value="">Dersler yüklenirken hata oluştu!</option>');
                            lessonSelect.prop('disabled', true);
                            alert('Sunucu ile iletişimde hata oluştu!');
                        }
                    });
                });

                // --- lesson_id değişti: package_type'ı kontrol et ve paket listesini yükle ---
                $('#lesson_id').on('change', function() {
                    var selectedOption = $(this).find('option:selected');
                    var packageType = selectedOption.data('package-type');
                    var selectedLessonId = selectedOption.val();
                    var developmentPackageContainer = $('#developmentPackageGroup');

                    developmentPackageContainer.empty().hide(); // Her zaman temizle ve gizle

                    if (!selectedLessonId) { // Ders seçilmemişse
                        return;
                    }

                    if (packageType == 1) {
                        $.ajax({
                            url: 'includes/ajax.php?service=getDevelopmentPackageList',
                            type: 'POST',
                            data: {
                                lesson_id: selectedLessonId
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.status === 'success' && Array.isArray(response.data) && response.data.length > 0) {
                                    let html = `
                            <label for="development_package_id" class="required form-label">Gelişim Paketi Seç</label>
                            <select name="development_package_id[]" id="development_package_id" class="form-select" multiple="multiple">
                        `;
                                    response.data.forEach(pkg => {
                                        html += `<option value="${pkg.id}">${pkg.name} - ${parseFloat(pkg.price).toFixed(2)}₺</option>`;
                                    });
                                    html += `
                            </select>
                            <div class="form-text">Birden fazla paket seçebilirsiniz.</div>
                        `;
                                    developmentPackageContainer.html(html).show();

                                    // Select2'yi yeni oluşturulan elementte başlat (BURAYI KORUYORUZ, Gelişim Paketi Select2 olacak)
                                    var devPackageSelect = $('#development_package_id');
                                    devPackageSelect.select2({
                                        placeholder: "Gelişim Paketi Seçin",
                                        allowClear: true,
                                        tags: false
                                    });

                                    // *** ÖNEMLİ DEĞİŞİKLİK BURADA ***
                                    // Sadece güncelleme modalındaysak ve depolanmış değerler varsa uygula
                                    if (devPackageSelect.closest('#updateUnitModal').length && currentDevelopmentPackageIds.length > 0) {
                                        devPackageSelect.val(currentDevelopmentPackageIds).trigger('change');
                                        currentDevelopmentPackageIds = [];
                                    }

                                } else {
                                    developmentPackageContainer.html(`
                            <div class="alert alert-warning" role="alert">
                                Bu derse ait gelişim paketi bulunamadı.
                            </div>
                        `).show();
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('AJAX Hatası (getDevelopmentPackageList):', status, error, xhr.responseText);
                                alert('Gelişim paketleri yüklenirken bir hata oluştu: ' + error);
                                developmentPackageContainer.empty().hide();
                            }
                        });
                    } else {
                        developmentPackageContainer.empty().hide();
                    }
                });

                // ... (rest of your existing code for saveUnitBtn, updateUnitBtn, DataTable search remains the same) ...

                const table = $('#kt_customers_table').DataTable();
                $('[data-kt-customer-table-filter="search"]').on('keyup', function() {
                    table.search(this.value).draw();
                });

                $('#saveUnitBtn').on('click', function() {
                    const classId = $('#addPreSchoolUnitModal #add_class_id').val();
                    const lessonId = $('#addPreSchoolUnitModal #add_lesson_id').val(); // Standart select değeri
                    const unitName = $('#addPreSchoolUnitModal #add_unit_name').val();
                    const unitOrder = $('#addPreSchoolUnitModal #add_unit_order').val();

                    const selectedLessonOption = $('#addPreSchoolUnitModal #add_lesson_id').find('option:selected');
                    const packageType = selectedLessonOption.data('package-type');


                    let developmentPackageIds = [];
                    if (packageType == 1 && $('#addPreSchoolUnitModal #add_development_package_id').length > 0) {
                        developmentPackageIds = $('#addPreSchoolUnitModal #add_development_package_id').val();
                        if (developmentPackageIds === null) {
                            developmentPackageIds = [];
                        }
                    }

                    console.log({
                        classId,
                        lessonId,
                        unitName,
                        unitOrder,
                        packageType,
                        developmentPackageIds
                    });

                    if (!classId || !lessonId || !unitName || !unitOrder) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Form Hatası',
                            text: 'Lütfen tüm zorunlu alanları doldurun.',
                            confirmButtonText: 'Tamam'
                        });
                        return;
                    }

                    if (packageType == 1 && developmentPackageIds.length === 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Paket Seçimi Hatası',
                            text: 'Bu ders için en az bir gelişim paketi seçmelisiniz.',
                            confirmButtonText: 'Tamam'
                        });
                        return;
                    }

                    const formData = {
                        class_id: classId,
                        lesson_id: lessonId,
                        unit_name: unitName,
                        unit_order: unitOrder,
                        ...(packageType == 1 && {
                            development_package_ids: developmentPackageIds
                        })
                    };

                    $.ajax({
                        url: 'includes/ajax.php?service=mainSchoolUnitAdd',
                        type: 'POST',
                        dataType: 'json',
                        data: formData,
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Başarılı',
                                    text: 'Ünite başarıyla eklendi!',
                                    confirmButtonText: 'Tamam'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Hata',
                                    text: response.message || 'Beklenmeyen bir hata oluştu.',
                                    confirmButtonText: 'Tamam'
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Sunucu Hatası',
                                text: 'Sunucuyla iletişim kurulamadı. Lütfen daha sonra tekrar deneyin.',
                                confirmButtonText: 'Tamam'
                            });
                        }
                    });
                });

                $('#updateUnitBtn').on('click', function() {
                    const unitId = $('#updateUnitModal #unit_id').val();
                    const classId = $('#updateUnitModal #class_id').val();
                    const lessonId = $('#updateUnitModal #lesson_id').val(); // Standart select değeri
                    const unitName = $('#updateUnitModal #unit_name').val();
                    const unitOrder = $('#updateUnitModal #unit_order').val();

                    const selectedLessonOption = $('#updateUnitModal #lesson_id').find('option:selected');
                    const packageType = selectedLessonOption.data('package-type');

                    let developmentPackageIds = [];
                    if (packageType == 1 && $('#updateUnitModal #development_package_id').length > 0) {
                        developmentPackageIds = $('#updateUnitModal #development_package_id').val();
                        if (developmentPackageIds === null) {
                            developmentPackageIds = [];
                        }
                    }

                    console.log({
                        unitId,
                        classId,
                        lessonId,
                        unitName,
                        unitOrder,
                        packageType,
                        developmentPackageIds
                    });

                    if (!classId || !unitName || !lessonId || !unitOrder) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Form Hatası',
                            text: 'Lütfen tüm alanları doldurun.',
                            confirmButtonText: 'Tamam'
                        });
                        return;
                    }

                    if (packageType == 1 && developmentPackageIds.length === 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Paket Seçimi Hatası',
                            text: 'Bu ders için en az bir gelişim paketi seçmelisiniz.',
                            confirmButtonText: 'Tamam'
                        });
                        return;
                    }

                    const formData = {
                        unit_id: unitId,
                        class_id: classId,
                        lesson_id: lessonId,
                        unit_name: unitName,
                        unit_order: unitOrder,
                        ...(packageType == 1 && {
                            development_package_ids: developmentPackageIds
                        })
                    };

                    $.ajax({
                        url: 'includes/ajax.php?service=mainSchoolUnitUpdate',
                        type: 'POST',
                        dataType: 'json',
                        data: formData,
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Başarılı',
                                    text: 'Ünite başarıyla güncellendi!',
                                    confirmButtonText: 'Tamam'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Hata',
                                    text: response.message || 'Beklenmeyen bir hata oluştu.',
                                    confirmButtonText: 'Tamam'
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Sunucu Hatası',
                                text: 'Sunucuyla iletişim kurulamadı.',
                                confirmButtonText: 'Tamam'
                            });
                        }
                    });
                });
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
