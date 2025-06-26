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
                                                    <!--end::Add school--><button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPreSchoolLessonModal">
                                                        Ders Ekle
                                                    </button>

                                                </div>
                                                <div class="modal fade" id="addPreSchoolLessonModal" tabindex="-1" aria-labelledby="addPreSchoolLessonModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">

                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Ders Ekle</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                                                            </div>

                                                            <div class="modal-body">

                                                                <div class="mb-3">
                                                                    <?php
                                                                    $classList = $class->getMainSchoolClassesList();
                                                                    ?>
                                                                    <label for="class_id" class="required form-label">Yaş</label>
                                                                    <select class="form-select form-control" id="class_id" name="class_id[]" multiple>
                                                                        <?php foreach ($classList as $d): ?>
                                                                            <option value="<?= htmlspecialchars($d['id']) ?>"><?= htmlspecialchars($d['name']) ?></option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="required form-label">Paket Tipi</label>
                                                                    <div>
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input" type="radio" name="packageType" id="packageTypeStandard" value="0" checked>
                                                                            <label class="form-check-label" for="packageTypeStandard">Standart Paket</label>
                                                                        </div>
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input" type="radio" name="packageType" id="packageTypeGelisim" value="1">
                                                                            <label class="form-check-label" for="packageTypeGelisim">Gelişim Paketi</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="lessonName" class=" required form-label">Ders Adı</label>
                                                                    <input type="text" class="form-control" id="lessonName" placeholder="Ders Adı">
                                                                </div>


                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                                                                <button type="button" id="saveLessonBtn" class="btn btn-primary">Kaydet</button>
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


                                            <!-- Modal -->


                                            <div class="modal fade" id="updateLessonModal" tabindex="-1" aria-labelledby="updateLessonModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">

                                                        <!-- Modal Başlık -->
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Ders Güncelle</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                                                        </div>

                                                        <!-- Modal İçeriği -->
                                                        <div class="modal-body">

                                                            <div class="mb-3">
                                                                <?php
                                                                $classList = $class->getMainSchoolClassesList();
                                                                ?>
                                                                <label for="class_id" class="required form-label">Yaş</label>
                                                                <select class="form-select form-control" id="class_id" name="class_id[]" multiple>
                                                                    <?php foreach ($classList as $d): ?>
                                                                        <option value="<?= htmlspecialchars($d['id']) ?>"><?= htmlspecialchars($d['name']) ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="required form-label">Paket Tipi</label>
                                                                <div>
                                                                    <div class="form-check form-check-inline">
                                                                        <input class="form-check-input" type="radio" name="packageType" id="packageTypeStandardU" value="0" checked>
                                                                        <label class="form-check-label" for="packageTypeStandardU">Standart Paket</label>
                                                                    </div>
                                                                    <div class="form-check form-check-inline">
                                                                        <input class="form-check-input" type="radio" name="packageType" id="packageTypeGelisimU" value="1">
                                                                        <label class="form-check-label" for="packageTypeGelisimU">Gelişim Paketi</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="lessonName" class=" required form-label">Ders Adı</label>
                                                                <input type="text" class="form-control" id="lessonName" placeholder="Ders Adı">
                                                                <input type="hidden" class="form-control" id="lessonId" placeholder="Ders Adı">
                                                            </div>

                                                        </div>

                                                        <!-- Modal Footer -->
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                                                            <button type="button" id="updateLessonBtn" class="btn btn-primary">Güncelle</button>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                            <!--begin::Table-->
                                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table">
                                                <thead>
                                                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                                        <th class="w-10px pe-2">
                                                            <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                                <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_customers_table .form-check-input" value="1" />
                                                            </div>
                                                        </th>
                                                        <th class="min-w-125px">Ders</th>

                                                        <th class="text-end min-w-70px">İşlemler</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="fw-semibold text-gray-600">
                                                    <?php
                                                    $list = $class->getMainSchoolLessonList();

                                                    foreach ($list as $key => $value): ?>
                                                        <tr>
                                                            <td>
                                                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                                    <input class="form-check-input" type="checkbox" value="1" />
                                                                </div>
                                                            </td>

                                                            <td>
                                                                <a href="#" class="text-gray-800 text-hover-primary mb-1">
                                                                    <?= htmlspecialchars($value['name']) ?>
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
                                                                        <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#updateLessonModal" data-id="<?= htmlspecialchars($value['id']) ?>">
                                                                            Güncelle
                                                                        </a>
                                                                    </div>

                                                                    <!--begin::Menu item-->
                                                                    <div class="menu-item px-3">
                                                                        <a href="javascript:void(0);"
                                                                            class="menu-link px-3"
                                                                            data-kt-customer-table-filter="delete_row"
                                                                            onclick="handleDelete({ id: '<?= htmlspecialchars($value['id']) ?>', url: 'includes/ajax.php?service=deleteMainSchoolLesson' })">
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
                var updateLessonModal = document.getElementById('updateLessonModal');

                updateLessonModal.addEventListener('show.bs.modal', function(event) {
                    var button = event.relatedTarget;
                    var lessonId = button.getAttribute('data-id');

                    // Form alanlarını temizle
                    updateLessonModal.querySelector('#class_id').value = null;
                    updateLessonModal.querySelector('#lessonName').value = '';
                    updateLessonModal.querySelector('#lessonId').value = '';

                    // AJAX ile veri çek
                    fetch('includes/ajax.php?service=mainSchoolGetLesson&id=' + lessonId)
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                // Ders adı ve ID'yi ata
                                updateLessonModal.querySelector('#lessonName').value = data.data.name;
                                updateLessonModal.querySelector('#lessonId').value = data.data.id;
                                updateLessonModal.querySelector('#packageTypeStandardU').checked = String(data.data.package_type) === '0';
                                updateLessonModal.querySelector('#packageTypeGelisimU').checked = String(data.data.package_type) === '1';

                                console.log(data.data.package_type); // 🔍 debug
                                // Multiple select için class_id alanını güncelle
                                const selectElement = updateLessonModal.querySelector('#class_id');
                                const classIds = data.data.classes.map(item => item.id.toString());

                                // Tüm seçenekleri döngüyle kontrol edip seçili olanları ayarla
                                for (let option of selectElement.options) {
                                    option.selected = classIds.includes(option.value);
                                }

                            } else {
                                alert("Veri alınamadı.");
                            }
                        })
                        .catch(error => {
                            console.error('Hata:', error);
                            alert("Sunucu hatası.");
                        });
                });

            });
            $(document).ready(function() {
                const table = $('#kt_customers_table').DataTable();

                // Arama kutusunu bağla
                $('[data-kt-customer-table-filter="search"]').on('keyup', function() {
                    table.search(this.value).draw();
                });
                $('#saveLessonBtn').on('click', function() {
                    const classId = $('#class_id').val();
                    const lessonName = $('#lessonName').val();
                    const packageType = $('input[name="packageType"]:checked').val();



                    // Basit doğrulama
                    if (!classId || !lessonName) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Form Hatası',
                            text: 'Lütfen tüm alanları doldurun.',
                            confirmButtonText: 'Tamam'
                        });
                        return;
                    }

                    // Form verilerini hazırla
                    const formData = {
                        class_id: classId,
                        lesson_name: lessonName,
                        package_type: packageType // PHP tarafında package_type olarak alınabilir
                    };

                    // AJAX isteği
                    $.ajax({
                        url: 'includes/ajax.php?service=mainSchoolLessonAdd',
                        type: 'POST',
                        dataType: 'json',
                        data: formData,
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Başarılı',
                                    text: 'Form başarıyla gönderildi!',
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
                $('#updateLessonBtn').on('click', function() {
                    const lessonId = $('#updateLessonModal #lessonId').val();
                    const classIds = $('#updateLessonModal #class_id').val(); // multiple seçilen değerler array olarak gelir
                    const lessonName = $('#updateLessonModal #lessonName').val();
                    const packageType = $('input[name="packageType"]:checked').val();

                    console.log({
                        lessonId,
                        classIds,
                        lessonName
                    }); // 🔍 debug

                    // Basit doğrulama
                    if (!classIds || classIds.length === 0 || !lessonName) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Form Hatası',
                            text: 'Lütfen tüm alanları doldurun.',
                            confirmButtonText: 'Tamam'
                        });
                        return;
                    }

                    // Form verilerini hazırla
                    const formData = {
                        lesson_id: lessonId,
                        lesson_name: lessonName,
                        class_ids: classIds, // PHP tarafında class_ids[] olarak alınabilir
                        package_type: packageType // PHP tarafında package_type olarak alınabilir
                    };

                    // AJAX isteği
                    $.ajax({
                        url: 'includes/ajax.php?service=mainSchoolLessonUpdate',
                        type: 'POST',
                        dataType: 'json',
                        data: formData,
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Başarılı',
                                    text: 'Form başarıyla güncellendi!',
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
