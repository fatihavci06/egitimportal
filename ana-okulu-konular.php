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
                                                    <!--end::Add school--><button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPreSchoolTopicModal">
                                                        Konu Ekle
                                                    </button>

                                                </div>
                                                <div class="modal fade" id="addPreSchoolTopicModal" tabindex="-1" aria-labelledby="addPreSchoolTopicModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">

                                                            <!-- Modal Başlık -->
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Konu Ekle</h5>
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
                                                                        <option selected disabled>Önce yaş grubu seçiniz...</option>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3 mt-3">
                                                                    <label for="unit_id" class=" required form-label">Ünite Adı</label>
                                                                    <select class="form-select form-control" id="unit_id" name="lesson_id">
                                                                        <option selected disabled>Önce ders seçiniz...</option>
                                                                    </select>
                                                                </div>

                                                                <div class="mb-3 mt-3">
                                                                    <label for="topic_name" class=" required form-label">Konu Adı</label>
                                                                    <input type="text" class="form-control" id="topic_name" placeholder="Konu Adı">
                                                                </div>

                                                            </div>

                                                            <!-- Modal Footer -->
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                                                                <button type="button" id="saveTopicBtn" class="btn btn-primary">Kaydet</button>
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


                                            <div class="modal fade" id="updateTopicModal" tabindex="-1" aria-labelledby="updateTopicModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">

                                                        <!-- Modal Başlık -->
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Konu Güncelle</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                                                        </div>

                                                        <!-- Modal İçeriği -->
                                                        <div class="modal-body">

                                                            <div class="mb-3 ">
                                                                <?php
                                                                $classList = $class->getMainSchoolClassesList();
                                                                $lessonList = $class->getMainSchoolLessonList();
                                                                $unitList = $class->getMainSchoolUnitList();

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
                                                                    <?php foreach ($lessonList as $d): ?>
                                                                        <option value="<?= htmlspecialchars($d['id']) ?>"><?= htmlspecialchars($d['name']) ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3 mt-3">
                                                                <label for="unit_id" class=" required form-label">Ünite Adı</label>
                                                                <select class="form-select form-control" id="unit_id" name="lesson_id">
                                                                    <option selected disabled>Önce ders seçiniz...</option>
                                                                    <?php foreach ($unitList as $d): ?>
                                                                        <option value="<?= htmlspecialchars($d['id']) ?>"><?= htmlspecialchars($d['unit_name']) ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>

                                                            <div class="mb-3 mt-3">
                                                                <label for="topic_name" class=" required form-label">Konu Adı</label>
                                                                <input type="text" class="form-control" id="topic_name" placeholder="Konu Adı">
                                                            </div>
                                                            <input type="hidden" id="topic_id" value=""> <!-- Güncellenecek konu ID'si için gizli alan -->

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
                                            <div class=" ">
                                                <div class="">
                                                    <div class="">
                                                        
                                                        <div class="row ">
                                                            <div class="col-md-6 col-lg-5">
                                                                <select class="form-select form-select-solid" id="class-filter">
                                                                    <option value="">Tüm Yaş Grupları</option>
                                                                    <?php
                                                                    $list = $class->getMainSchoolTopicList();

                                                                    $classes = [];
                                                                    foreach ($list as $value) {
                                                                        if (!in_array($value['class_name'], $classes)) {
                                                                            $classes[] = $value['class_name'];
                                                                            echo '<option value="' . htmlspecialchars($value['class_name']) . '">' . htmlspecialchars($value['class_name']) . '</option>';
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 col-lg-5">
                                                                <select class="form-select form-select-solid" id="lesson-filter">
                                                                    <option value="">Tüm Dersler</option>
                                                                    <?php
                                                                    $lessons = [];
                                                                    foreach ($list as $value) {
                                                                        if (!in_array($value['lesson_name'], $lessons)) {
                                                                            $lessons[] = $value['lesson_name'];
                                                                            echo '<option value="' . htmlspecialchars($value['lesson_name']) . '">' . htmlspecialchars($value['lesson_name']) . '</option>';
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-12 col-lg-2">
                                                                <button type="button" class="btn btn-primary w-100" id="apply-filters-btn">
                                                                    Filtrele
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table">
                                                <thead>
                                                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                                        <th class="min-w-125px">Yaş Grubu</th>
                                                        <th class="min-w-125px">Ders</th>
                                                        <th class="min-w-125px">Ünite</th>
                                                        <th class="min-w-125px">Konu</th>
                                                        <th class="text-end min-w-70px">İşlemler</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="fw-semibold text-gray-600">
                                                    <?php foreach ($list as $value): ?>
                                                        <tr data-class="<?= htmlspecialchars($value['class_name']) ?>" data-lesson="<?= htmlspecialchars($value['lesson_name']) ?>">
                                                            <td><?= htmlspecialchars($value['class_name']) ?></td>
                                                            <td>
                                                                <a href="#" class="text-gray-800 text-hover-primary mb-1">
                                                                    <?= htmlspecialchars($value['lesson_name']) ?>
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <a href="#" class="text-gray-800 text-hover-primary mb-1">
                                                                    <?= htmlspecialchars($value['unit_name']) ?>
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <a href="#" class="text-gray-800 text-hover-primary mb-1">
                                                                    <?= htmlspecialchars($value['topic_name']) ?>
                                                                </a>
                                                            </td>
                                                            <td class="text-end">
                                                                <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                                    İşlemler
                                                                    <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                                                </a>
                                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                                                    <div class="menu-item px-3">
                                                                        <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#updateTopicModal" data-id="<?= htmlspecialchars($value['id']) ?>">
                                                                            Güncelle
                                                                        </a>
                                                                    </div>
                                                                    <div class="menu-item px-3">
                                                                        <a href="javascript:void(0);" class="menu-link px-3" data-kt-customer-table-filter="delete_row" onclick="handleDelete({ id: '<?= htmlspecialchars($value['id']) ?>', url: 'includes/ajax.php?service=deleteMainSchoolTopic' })">
                                                                            Sil
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
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
                const customerTable = $('#kt_customers_table').DataTable({
                    "pageLength": 10, // Sayfa başına 10 satır göster
                    "lengthMenu": [
                        [10, 25, 50, -1],
                        [10, 25, 50, "Tümü"]
                    ],
                    language: {
                            decimal: ",",
                            thousands: ".",
                            emptyTable: "Tabloda herhangi bir veri mevcut değil",
                            info: "_TOTAL_ kayıttan _START_ - _END_ arası gösteriliyor",
                            infoEmpty: "Kayıt yok",
                            infoFiltered: "(_MAX_ kayıt içerisinden filtrelendi)",
                            lengthMenu: "Sayfada _MENU_ kayıt göster",
                            loadingRecords: "Yükleniyor...",
                            processing: "İşleniyor...",
                            search: "Ara:",
                            zeroRecords: "Eşleşen kayıt bulunamadı",
                            paginate: {
                                first: "İlk",
                                last: "Son",
                                next: "Sonraki",
                                previous: "Önceki"
                            }
                        },
                });

                const classFilter = $('#class-filter');
                const lessonFilter = $('#lesson-filter');
                const applyFiltersBtn = $('#apply-filters-btn');

                function applyFilters() {
                    const selectedClass = classFilter.val();
                    const selectedLesson = lessonFilter.val();

                    // Sadece seçili olan filtreleri uygula
                    // 0. sütun 'Yaş Grubu'
                    customerTable.column(0).search(selectedClass).draw();

                    // 1. sütun 'Ders'
                    customerTable.column(1).search(selectedLesson).draw();
                }

                // Butona tıklandığında filtreleme fonksiyonunu çağır
                applyFiltersBtn.on('click', applyFilters);

                // İlk yüklemede tüm verileri göster
                applyFilters();
                var updateTopicModal = document.getElementById('updateTopicModal');

                updateTopicModal.addEventListener('show.bs.modal', function(event) {
                    var button = event.relatedTarget;
                    var topicId = button.getAttribute('data-id');

                    // Form alanlarını temizle ve varsayılan yükleniyor mesajlarını göster
                    // class_id'yi bu noktada temizlemek yerine, ilk fetch'ten gelen değerle dolduracağız.
                    // Ancak boş bir durumdan başlamak istiyorsak, burada temizleyebiliriz.
                    // updateTopicModal.querySelector('#class_id').value = ''; // Bu satırı şimdilik kaldırıyorum veya ilk topicData'dan sonra dolduracağım
                    updateTopicModal.querySelector('#lesson_id').innerHTML = '<option>Dersler Yükleniyor...</option>';
                    updateTopicModal.querySelector('#unit_id').innerHTML = '<option>Üniteler Yükleniyor...</option>';
                    updateTopicModal.querySelector('#topic_name').value = '';

                    // 1) Önce konu (topic) verisini çek
                    fetch('includes/ajax.php?service=mainSchoolGetTopic&id=' + topicId)
                        .then(response => response.json())
                        .then(topicData => {
                            if (topicData.status === 'success') {
                                var classId = topicData.data.class_id;
                                var selectedLessonId = topicData.data.lesson_id;
                                var selectedUnitId = topicData.data.unit_id;

                                // Form alanlarını doldur
                                // class_id'yi burada set ediyoruz
                                updateTopicModal.querySelector('#class_id').value = classId;
                                updateTopicModal.querySelector('#topic_name').value = topicData.data.topic_name;
                                updateTopicModal.querySelector('#topic_id').value = topicData.data.id;

                                // 2) Şimdi class_id'ye bağlı lessonları çek (select elemanlarını doldur)
                                fetch('includes/ajax.php?service=mainSchoolGetLessons', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/x-www-form-urlencoded',
                                        },
                                        body: 'class_id=' + encodeURIComponent(classId) // Buradaki classId, topicData'dan gelen doğru değerdir.
                                    })
                                    .then(response => response.json())
                                    .then(lessonData => {
                                        var lessonSelect = updateTopicModal.querySelector('#lesson_id');
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

                                        // 3) lesson_id'ye ve **class_id**'ye bağlı unit'leri çek
                                        // Bu adım, lessonSelect'in doğru selectedLessonId ile doldurulmasından sonra çalışmalı.
                                        // currentSelectedLessonId artık lessonSelect'in value'su veya topicData'dan gelen selectedLessonId olabilir.
                                        const currentSelectedLessonId = lessonSelect.value;

                                        // classId değişkeni zaten yukarıda topicData'dan alınmıştı.
                                        // Bu değeri doğrudan kullanabiliriz, tekrar DOM'dan çekmeye gerek yok.
                                        const currentSelectedClassId = classId; // topicData'dan gelen classId'yi kullan

                                        if (currentSelectedLessonId && currentSelectedClassId) { // Hem ders hem de sınıf ID'si varsa devam et
                                            const bodyData = new URLSearchParams();
                                            bodyData.append('lesson_id', currentSelectedLessonId);
                                            bodyData.append('class_id', currentSelectedClassId); // Doğru classId'yi gönderiyoruz
                                            console.log('Fetching units with class_id:', currentSelectedClassId, 'and lesson_id:', currentSelectedLessonId); // Debug için

                                            fetch('includes/ajax.php?service=mainSchoolGetUnits', {
                                                    method: 'POST',
                                                    headers: {
                                                        'Content-Type': 'application/x-www-form-urlencoded',
                                                    },
                                                    body: bodyData.toString()
                                                })
                                                .then(response => response.json())
                                                .then(unitData => {
                                                    const unitSelect = updateTopicModal.querySelector('#unit_id');
                                                    unitSelect.innerHTML = ''; // Temizle

                                                    if (unitData.status === 'success' && unitData.data.length > 0) {
                                                        unitData.data.forEach(function(unit) {
                                                            const option = document.createElement('option');
                                                            option.value = unit.id;
                                                            option.textContent = unit.name;

                                                            if (unit.id == selectedUnitId) {
                                                                option.selected = true;
                                                            }
                                                            unitSelect.appendChild(option);
                                                        });
                                                    } else {
                                                        unitSelect.innerHTML = '<option disabled>Ünite bulunamadı</option>';
                                                    }
                                                })
                                                .catch(err => {
                                                    console.error('Üniteler alınamadı', err);
                                                    updateTopicModal.querySelector('#unit_id').innerHTML = '<option disabled>Hata oluştu</option>';
                                                });
                                        } else {
                                            updateTopicModal.querySelector('#unit_id').innerHTML = '<option disabled>Ders veya Sınıf ID eksik</option>';
                                        }

                                    })
                                    .catch(err => {
                                        console.error('Dersler alınamadı', err);
                                        updateTopicModal.querySelector('#lesson_id').innerHTML = '<option disabled>Hata oluştu</option>';
                                        updateTopicModal.querySelector('#unit_id').innerHTML = '<option disabled>Ders seçilmedi</option>';
                                    });

                            } else {
                                alert('Konu verisi alınamadı.');
                            }
                        })
                        .catch(error => {
                            console.error('Sunucu hatası:', error);
                            alert('Konu verisi çekilirken sunucu hatası.');
                        });
                });

                // Class seçimi değiştiğinde lesson ve unit dropdown'larını güncelle
                // Bu kısım, modal açıldıktan sonra kullanıcının class değiştirmesi durumunda gereklidir.
                // Eğer sadece başlangıçta doldurulacaksa bu listener'a gerek yok.
                // Ama dinamik olarak değişmesini istiyorsanız bu bloğu ekleyin.
                updateTopicModal.querySelector('#class_id').addEventListener('change', function() {

                    var classId = this.value;
                    var lessonSelect = updateTopicModal.querySelector('#lesson_id');
                    var unitSelect = updateTopicModal.querySelector('#unit_id');

                    lessonSelect.innerHTML = '<option>Dersler Yükleniyor...</option>';
                    unitSelect.innerHTML = '<option>Üniteler Yükleniyor...</option>';

                    if (classId) {
                        fetch('includes/ajax.php?service=mainSchoolGetLessons', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: 'class_id=' + encodeURIComponent(classId)
                            })
                            .then(response => response.json())
                            .then(lessonData => {
                                lessonSelect.innerHTML = '';
                                if (lessonData.status === 'success' && lessonData.data.length > 0) {
                                    lessonData.data.forEach(function(lesson) {
                                        var option = document.createElement('option');
                                        option.value = lesson.id;
                                        option.textContent = lesson.name;
                                        lessonSelect.appendChild(option);
                                    });
                                    // Dersler yüklendikten sonra ilk dersin id'sini alarak unitleri yükle
                                    // veya eğer dersin de değişmesi bekleniyorsa, ayrı bir listener ekle
                                    if (lessonSelect.value) {

                                        loadUnitsForUpdateTopicModal(lessonSelect.value); // Yardımcı fonksiyon çağır
                                    } else {
                                        unitSelect.innerHTML = '<option disabled>Ders seçin</option>';
                                    }
                                } else {
                                    lessonSelect.innerHTML = '<option disabled>Ders bulunamadı</option>';
                                    unitSelect.innerHTML = '<option disabled>Ders bulunamadı</option>';
                                }
                            })
                            .catch(err => {
                                console.error('Dersler alınamadı', err);
                                lessonSelect.innerHTML = '<option disabled>Hata oluştu</option>';
                                unitSelect.innerHTML = '<option disabled>Dersler yüklenirken hata</option>';
                            });
                    } else {
                        lessonSelect.innerHTML = '<option disabled>Sınıf seçin</option>';
                        unitSelect.innerHTML = '<option disabled>Sınıf seçin</option>';
                    }
                });

                // Lesson seçimi değiştiğinde unit dropdown'larını güncelle
                // Bu kısım da kullanıcının ders değiştirmesi durumunda gereklidir.
                updateTopicModal.querySelector('#lesson_id').addEventListener('change', function() {
                    loadUnitsForUpdateTopicModal(this.value); // Yardımcı fonksiyon çağır
                });

                // Helper function to load units
                function loadUnitsForUpdateTopicModal(lessonId) {
                    const unitSelect = updateTopicModal.querySelector('#unit_id');
                    unitSelect.innerHTML = '<option>Üniteler Yükleniyor...</option>';

                    // updateTopicModal içinden class_id’yi al
                    const classId = updateTopicModal.querySelector('#class_id')?.value;

                    if (!lessonId || !classId) {
                        unitSelect.innerHTML = '<option disabled>Ders ve sınıf seçilmeli</option>';
                        return;
                    }

                    const bodyData = new URLSearchParams();
                    bodyData.append('lesson_id', lessonId);
                    bodyData.append('class_id', classId);

                    fetch('includes/ajax.php?service=mainSchoolGetUnits', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: bodyData.toString()
                        })
                        .then(response => response.json())
                        .then(unitData => {
                            unitSelect.innerHTML = '';

                            if (unitData.status === 'success' && Array.isArray(unitData.data) && unitData.data.length > 0) {
                                unitData.data.forEach(unit => {
                                    const option = document.createElement('option');
                                    option.value = unit.id;
                                    option.textContent = unit.name;
                                    unitSelect.appendChild(option);
                                });
                            } else {
                                unitSelect.innerHTML = '<option disabled>Ünite bulunamadı</option>';
                            }
                        })
                        .catch(err => {
                            console.error('Üniteler alınamadı:', err);
                            unitSelect.innerHTML = '<option disabled>Sunucu hatası</option>';
                        });
                }

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
                $('#lesson_id').on('change', function() {
                    var selectedLessonId = $(this).val();
                    console.log('ss' + $('#class_id').val());

                    $.ajax({
                        url: 'includes/ajax.php?service=mainSchoolGetUnits', // Backend dosyanın yolu
                        type: 'POST',
                        data: {
                            lesson_id: selectedLessonId,
                            class_id: $('#class_id').val()
                        },
                        dataType: 'json', // JSON olarak bekliyoruz
                        success: function(response) {
                            if (response.status === 'success') {
                                var unitSelect = $('#unit_id');
                                unitSelect.empty(); // Önceki optionları temizle

                                if (response.data.length > 0) {
                                    unitSelect.append('<option selected disabled>Ünite Seçiniz...</option>');

                                    // Gelen datayı option olarak ekle
                                    $.each(response.data, function(index, lesson) {
                                        unitSelect.append(
                                            $('<option></option>')
                                            .val(lesson.id)
                                            .text(lesson.name)
                                        );
                                    });
                                } else {
                                    unitSelect.append('<option disabled>Bu sınıfa ait ders bulunamadı.</option>');
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
                $('#saveTopicBtn').on('click', function() {
                    const classId = $('#class_id').val();
                    const lessonId = $('#lesson_id').val();
                    const unitId = $('#unit_id').val();
                    const topicName = $('#topic_name').val();
                    console.log({
                        classId,
                        lessonId,
                        topicName,
                        topicName
                    }); // 🔍 debug
                    // Basit doğrulama
                    if (!classId || !lessonId || !unitId || !topicName) {
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
                        unit_id: unitId,
                        topic_name: topicName
                    };

                    // AJAX isteği
                    $.ajax({
                        url: 'includes/ajax.php?service=mainSchoolTopicAdd',
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
                    const lessonId = $('#updateTopicModal #lesson_id').val();
                    const classId = $('#updateTopicModal #class_id').val();
                    const topicName = $('#updateTopicModal #topic_name').val();
                    const topicId = $('#updateTopicModal #topic_id').val(); // Modal açılırken set edilen id
                    const unitId = $('#updateTopicModal #unit_id').val(); // Modal açılırken set edilen id
                    console.log({
                        lessonId,
                        classId,
                        unitId,
                        topicName,
                        topicId
                    }); // 🔍 debug


                    // Basit doğrulama
                    if (!classId || !topicName || !lessonId || !unitId) {
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
                        unit_id: unitId,
                        lesson_id: lessonId,
                        class_id: classId,
                        topic_name: topicName,
                        topic_id: topicId // Güncelleme için gerekli id
                    };

                    // AJAX isteği
                    $.ajax({
                        url: 'includes/ajax.php?service=mainSchoolTopicUpdate',
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
