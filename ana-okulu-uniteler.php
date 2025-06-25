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

                                                            <!-- Modal Başlık -->
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Ünite Ekle</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                                                            </div>

                                                            <!-- Modal İçeriği -->
                                                            <div class="modal-body">

                                                                <div class="mb-3 ">
                                                                    <?php
                                                                    $classList = $class->getMainSchoolClassesList();

                                                                    ?>
                                                                    <label for="selectOption" class="required form-label">Yaş </label>
                                                                    <select class="form-select form-control" id="class_id" name="class_id">
                                                                        <option selected disabled>Seçiniz...</option>
                                                                        <?php foreach ($classList as $d): ?>
                                                                            <option value="<?= htmlspecialchars($d['id']) ?>"><?= htmlspecialchars($d['name']) ?></option>
                                                                        <?php endforeach; ?>


                                                                    </select>
                                                                </div>
                                                                <div class="mb-3 mt-3">
                                                                    <label for="lesson_id" class=" required form-label">Ders Adı</label>
                                                                    <select class="form-select form-control" id="lesson_id" name="lesson_id">
                                                                        <option selected disabled>Önce sınıf seçiniz...</option>
                                                                    </select>
                                                                </div>

                                                                <div class="mb-3 mt-3">
                                                                    <label for="unitName" class=" required form-label">Ünite Adı</label>
                                                                    <input type="text" class="form-control" id="unit_name" placeholder="Ünite Adı">
                                                                </div>

                                                            </div>

                                                            <!-- Modal Footer -->
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                                                                <button type="button" id="saveUnitBtn" class="btn btn-primary">Kaydet</button>
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


                                            <div class="modal fade" id="updateUnitModal" tabindex="-1" aria-labelledby="updateUnitModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">

                                                        <!-- Modal Başlık -->
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Ünite Güncelle</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                                                        </div>

                                                        <!-- Modal İçeriği -->
                                                        <div class="modal-body">

                                                            <div class="mb-3 ">
                                                                <?php
                                                                $classList = $class->getMainSchoolClassesList();

                                                                ?>
                                                                <label for="selectOption" class="required form-label">Yaş </label>
                                                                <select class="form-select form-control" id="class_id" name="class_id">
                                                                    <option selected disabled>Seçiniz...</option>
                                                                    <?php foreach ($classList as $d): ?>
                                                                        <option value="<?= htmlspecialchars($d['id']) ?>"><?= htmlspecialchars($d['name']) ?></option>
                                                                    <?php endforeach; ?>


                                                                </select>
                                                            </div>
                                                            <div class="mb-3 mt-3">
                                                                <label for="lesson_id" class=" required form-label">Ders Adı</label>
                                                                <select class="form-select form-control" id="lesson_id" name="lesson_id">
                                                                    <option selected disabled>Önce sınıf seçiniz...</option>
                                                                </select>
                                                            </div>

                                                            <div class="mb-3 mt-3">
                                                                <label for="unitName" class=" required form-label">Ünite Adı</label>
                                                                <input type="text" class="form-control" id="unit_name" placeholder="Ünite Adı">
                                                                <input type="hidden" id="unit_id" value="">
                                                            </div>

                                                        </div>

                                                        <!-- Modal Footer -->
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                                                            <button type="button" id="updateUnitBtn" class="btn btn-primary">Güncelle</button>
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
                                                        <th class="min-w-125px">Ünite</th>

                                                        <th class="text-end min-w-70px">İşlemler</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="fw-semibold text-gray-600">
                                                    <?php
                                                    $list = $class->getMainSchoolUnitList();

                                                                        
                                                    foreach ($list as $key => $value): ?>
                                                        <tr>
                                                            <td>
                                                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                                    <input class="form-check-input" type="checkbox" value="1" />
                                                                </div>
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

                updateUnitModal.addEventListener('show.bs.modal', function(event) {
                    var button = event.relatedTarget;
                    var unitId = button.getAttribute('data-id');

                    // Form alanlarını temizle
                    updateUnitModal.querySelector('#class_id').value = '';
                    updateUnitModal.querySelector('#lesson_id').innerHTML = '<option>Yükleniyor...</option>';
                    updateUnitModal.querySelector('#unit_name').value = '';

                    // 1) Önce birim (unit) verisini çek
                    fetch('includes/ajax.php?service=mainSchoolGetUnit&id=' + unitId)
                        .then(response => response.json())
                        .then(unitData => {
                            if (unitData.status === 'success') {
                                var classId = unitData.data.class_id;
                                var selectedLessonId = unitData.data.lesson_id;

                                // Form alanlarını doldur
                                updateUnitModal.querySelector('#class_id').value = classId;
                                updateUnitModal.querySelector('#unit_name').value = unitData.data.unit_name;
                                updateUnitModal.querySelector('#unit_id').value = unitData.data.id;
                                // 2) Şimdi class_id'ye bağlı lessonları çek
                                fetch('includes/ajax.php?service=mainSchoolGetLessons', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/x-www-form-urlencoded',
                                        },
                                        body: 'class_id=' + encodeURIComponent(classId)
                                    })
                                    .then(response => response.json())
                                    .then(lessonData => {
                                        var lessonSelect = updateUnitModal.querySelector('#lesson_id');
                                        lessonSelect.innerHTML = ''; // Temizle

                                        if (lessonData.status === 'success' && lessonData.data.length > 0) {
                                            lessonData.data.forEach(function(lesson) {
                                                var option = document.createElement('option');
                                                option.value = lesson.id;
                                                option.textContent = lesson.name;

                                                if (lesson.id == selectedLessonId) {
                                                    option.selected = true;
                                                }

                                                lessonSelect.appendChild(option);
                                            });
                                        } else {
                                            lessonSelect.innerHTML = '<option disabled>Ders bulunamadı</option>';
                                        }
                                    })
                                    .catch(err => {
                                        console.error('Dersler alınamadı', err);
                                        updateUnitModal.querySelector('#lesson_id').innerHTML = '<option disabled>Hata oluştu</option>';
                                    });


                            } else {
                                alert('Veri alınamadı.');
                            }
                        })
                        .catch(error => {
                            console.error('Sunucu hatası:', error);
                            alert('Sunucu hatası.');
                        });
                });
            });

            $(document).ready(function() {


                $('#class_id').on('change', function() {
                    var selectedClassId = $(this).val();

                    $.ajax({
                        url: 'includes/ajax.php?service=mainSchoolGetLessons', // Backend dosyanın yolu
                        type: 'POST',
                        data: {
                            class_id: selectedClassId
                        },
                        dataType: 'json', // JSON olarak bekliyoruz
                        success: function(response) {
                            if (response.status === 'success') {
                                var lessonSelect = $('#lesson_id');
                                lessonSelect.empty(); // Önceki optionları temizle

                                if (response.data.length > 0) {
                                    lessonSelect.append('<option selected disabled>Ders Seçiniz...</option>');

                                    // Gelen datayı option olarak ekle
                                    $.each(response.data, function(index, lesson) {
                                        lessonSelect.append(
                                            $('<option></option>')
                                            .val(lesson.id)
                                            .text(lesson.name)
                                        );
                                    });
                                } else {
                                    lessonSelect.append('<option disabled>Bu sınıfa ait ders bulunamadı.</option>');
                                }
                            } else {
                                alert(response.message);
                            }
                        },
                        error: function() {
                            alert('Sunucu ile iletişimde hata oluştu!');
                        }
                    });
                });
                const table = $('#kt_customers_table').DataTable();

                // Arama kutusunu bağla
                $('[data-kt-customer-table-filter="search"]').on('keyup', function() {
                    table.search(this.value).draw();
                });
                $('#saveUnitBtn').on('click', function() {
                    const classId = $('#class_id').val();
                    const lessonId = $('#lesson_id').val();
                    const unitName = $('#unit_name').val();
                    console.log({
                        classId,
                        lessonId,
                        unitName
                    }); // 🔍 debug
                    // Basit doğrulama
                    if (!classId || !lessonId || !unitName) {
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
                        lesson_id: lessonId,
                        unit_name: unitName
                    };

                    // AJAX isteği
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
                $('#updateUnitBtn').on('click', function() {
                    const lessonId = $('#updateUnitModal #lesson_id').val();
                    const classId = $('#updateUnitModal #class_id').val();
                    const unitName = $('#updateUnitModal #unit_name').val();
                    const unitId = $('#updateUnitModal #unit_id').val(); // Modal açılırken set edilen id
                    console.log({
                        lessonId,
                        classId,
                        unitName
                    }); // 🔍 debug


                    // Basit doğrulama
                    if (!classId || !unitName || !lessonId) {
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
                        unit_id:unitId,
                        lesson_id: lessonId,
                        class_id: classId,
                        unit_name: unitName
                    };

                    // AJAX isteği
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
                })
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
