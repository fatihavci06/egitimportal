<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or  $_SESSION['role'] == 3 or  $_SESSION['role'] == 4)) {
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";
    include "classes/weekly.classes.php";
    include "classes/weekly-view.classes.php";
    $weekly = new ShowWeekly();
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
                            <div id="kt_app_content" class="app-content flex-column-fluid">
                                <div id="kt_app_content_container" class="app-container container-fluid">
                                    <div class="row">

                                        <div class="col-lg-4">
                                            <label class="required fs-6 fw-semibold mb-2" for="class_id">Sınıf Seçimi </label>
                                            <?php
                                            $class = new Classes();
                                            $classList = $class->getClassesList();
                                            ?>
                                            <select class="form-select" id="class_id" required aria-label="Default select example">
                                                <option value="">Seçiniz</option>
                                                <?php foreach ($classList as $c) { ?>
                                                    <option value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="required fs-6 fw-semibold mb-2" for="lesson_id">Dersler</label>
                                            <select class="form-select" id="lesson_id" required>
                                                <option value="">Ders seçiniz</option>
                                            </select>
                                        </div>

                                        <div class="col-lg-4">
                                            <label class="fs-6 fw-semibold mb-2" for="unit_id">Ünite Seçimi</label>
                                            <select class="form-select" id="unit_id" required>
                                                <option value="">Ünite seçiniz</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-lg-4 mt-4">
                                            <label class="fs-6 fw-semibold mb-2" for="topic_id">Konu Seçimi</label>
                                            <select class="form-select" id="topic_id" required>
                                                <option value="">Seçiniz</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-4 mt-4">
                                            <label class="fs-6 fw-semibold mb-2" for="subtopic_id">Alt Konu Seçimi</label>
                                            <select class="form-select" id="subtopic_id" required>
                                                <option value="">Alt Konu seçiniz</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-5 mb-5">
                                        <div class="col-lg-3">
                                            <button type="button" id="filterButton" class="btn btn-success btn-sm w-100">Filtrele</button>
                                        </div>
                                        <div class="col-lg-2">
                                            <button type="button" id="clearFiltersButton" class="btn btn-secondary btn-sm w-100">Temizle</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!--begin::Content-->
                            <div id="kt_app_content" class="app-content flex-column-fluid">
                                <!--begin::Content container-->
                                <div id="kt_app_content_container" class="app-container container-fluid">
                                    <!--begin::Card-->
                                    <div class="card">
                                        <!--begin::Card header-->
                                        <div class="card-header">
                                            <h2 class="card-title fw-bold">Takvim</h2>
                                        </div>
                                        <!--end::Card header-->
                                        <!--begin::Card body-->
                                        <div class="card-body">
                                            <!--begin::Calendar-->
                                            <div id="kt_calendar_app"></div>
                                            <!--end::Calendar-->
                                        </div>
                                        <!--end::Card body-->
                                    </div>
                                    <!--end::Card-->
                                    <!--begin::Modals-->
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
        <script src="assets/plugins/custom/fullcalendar/fullcalendar.bundle.js"></script>
        <script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
        <!--end::Vendors Javascript-->
        <!--begin::Custom Javascript(used for this page only)-->
        <script src="assets/js/custom/apps/weekly-calendar/calendar.js"></script>
        <script src="assets/js/widgets.bundle.js"></script>
        <script src="assets/js/custom/widgets.js"></script><!-- 
        <script src="assets/js/custom/apps/chat/chat.js"></script>
        <script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
        <script src="assets/js/custom/utilities/modals/create-account.js"></script>
        <script src="assets/js/custom/utilities/modals/create-app.js"></script>
        <script src="assets/js/custom/utilities/modals/users-search.js"></script> -->
        <!--end::Custom Javascript-->
        <script>
            

                // Filtreleme butonu tıklaması
                $('#filterButton').on('click', function() {
                    var classId = $('#class_id').val();
                    var lessonId = $('#lesson_id').val();

                    // Sınıf ve Ders seçimi zorunlu kontrolü
                    if (!classId || classId === '') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Uyarı',
                            text: 'Lütfen bir sınıf seçiniz.',
                            confirmButtonText: 'Tamam'
                        });
                        $('#class_id').addClass('is-invalid'); // Bootstrap ile görsel uyarı
                        return; // Filtreleme işlemini durdur
                    } else {
                        $('#class_id').removeClass('is-invalid');
                    }

                });

                // Temizle butonu tıklaması
                $('#clearFiltersButton').on('click', function() {
                    // Filtreleme alanlarını temizle
                    $('#title').val('');
                    $('#class_id').val('').trigger('change'); // 'change' olayı ile diğer bağımlı selectbox'ları da temizle
                    $('#lesson_id').val('');
                    $('#unit_id').val('');
                    $('#topic_id').val('');
                    $('#subtopic_id').val('');
                    // Temizlenmiş filtrelerle DataTable'ı yeniden yükle.
                    // Bu, 'getFilteredTests' servisinize boş filtre değerleri gönderecektir.

                });

                // --- Ders, Ünite, Konu, Alt Konu Seçim Mantığı ---

                // Sınıf seçimi değiştiğinde dersleri getir
                $('#class_id').on('change', function() {
                    var classId = $(this).val();
                    fetchLessonsForClass(classId);
                });

                function fetchLessonsForClass(classId) {
                    if (classId !== '') {
                        $.ajax({
                            url: 'includes/ajax.php?service=getLessonList',
                            type: 'POST',
                            data: {
                                class_id: classId
                            },
                            dataType: 'json',
                            success: function(response) {
                                var $lessonSelect = $('#lesson_id');
                                $('#option_count').val(response.data.optionCount); // Bu kısım ilgili inputunuz varsa
                                $lessonSelect.empty();
                                $lessonSelect.append('<option value="">Ders seçiniz</option>');
                                $.each(response.data.lessons, function(index, lesson) {
                                    $lessonSelect.append('<option value="' + lesson.id + '">' + lesson.name + '</option>');
                                });
                                // Diğer bağımlı selectbox'ları temizle
                                $('#unit_id').html('<option value="">Ünite seçiniz</option>');
                                $('#topic_id').html('<option value="">Seçiniz</option>');
                                $('#subtopic_id').html('<option value="">Alt Konu seçiniz</option>');
                            },
                            error: function(xhr) {
                                handleAjaxError(xhr);
                            }
                        });
                    } else {
                        // Sınıf seçimi boşsa tüm bağımlı selectbox'ları temizle
                        $('#lesson_id').html('<option value="">Ders seçiniz</option>');
                        $('#unit_id').html('<option value="">Ünite seçiniz</option>');
                        $('#topic_id').html('<option value="">Seçiniz</option>');
                        $('#subtopic_id').html('<option value="">Alt Konu seçiniz</option>');
                    }
                }

                // Ders seçimi değiştiğinde üniteleri getir
                $('#lesson_id').on('change', function() {
                    var lessonId = $(this).val();
                    var classId = $('#class_id').val(); // Sınıf ID'sini de gönderiyoruz
                    var $unitSelect = $('#unit_id');
                    $unitSelect.empty().append('<option value="">Ünite seçiniz</option>');
                    $('#topic_id').html('<option value="">Seçiniz</option>');
                    $('#subtopic_id').html('<option value="">Alt Konu seçiniz</option>');

                    if (lessonId !== '') {
                        $.ajax({
                            url: 'includes/ajax.php?service=getUnitList',
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                lesson_id: lessonId,
                                class_id: classId
                            },
                            success: function(response) {
                                if (response.status === 'success' && response.data.length > 0) {
                                    $.each(response.data, function(index, unit) {
                                        $unitSelect.append($('<option>', {
                                            value: unit.id,
                                            text: unit.name
                                        }));
                                    });
                                } else {
                                    $unitSelect.append('<option disabled>Ünite bulunamadı</option>');
                                }
                            },
                            error: function(xhr) {
                                handleAjaxError(xhr);
                            }
                        });
                    }
                });

                // Ünite seçimi değiştiğinde konuları getir
                $('#unit_id').on('change', function() {
                    var classId = $('#class_id').val();
                    var lessonId = $('#lesson_id').val();
                    var unitId = $(this).val();
                    var $topicSelect = $('#topic_id');

                    $topicSelect.empty().append('<option value="">Seçiniz</option>');
                    $('#subtopic_id').html('<option value="">Alt Konu seçiniz</option>');

                    if (unitId !== '') {
                        $.ajax({
                            url: 'includes/ajax.php?service=getTopicList',
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                class_id: classId,
                                lesson_id: lessonId,
                                unit_id: unitId
                            },
                            success: function(response) {
                                if (response.status === 'success' && response.data.length > 0) {
                                    $.each(response.data, function(index, topic) {
                                        $topicSelect.append($('<option>', {
                                            value: topic.id,
                                            text: topic.name
                                        }));
                                    });
                                } else {
                                    $topicSelect.append('<option disabled>Konu bulunamadı</option>');
                                }
                            },
                            error: function(xhr) {
                                handleAjaxError(xhr);
                            }
                        });
                    }
                });

                // Konu seçimi değiştiğinde alt konuları getir
                $('#topic_id').on('change', function() {
                    var classId = $('#class_id').val();
                    var lessonId = $('#lesson_id').val();
                    var unitId = $('#unit_id').val();
                    var topicId = $(this).val();
                    var $subtopicSelect = $('#subtopic_id');

                    $subtopicSelect.empty().append('<option value="">Alt Konu seçiniz</option>');

                    if (topicId !== '') {
                        $.ajax({
                            url: 'includes/ajax.php?service=getSubtopicList',
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                class_id: classId,
                                lesson_id: lessonId,
                                unit_id: unitId,
                                topic_id: topicId
                            },
                            success: function(response) {
                                if (response.status === 'success' && response.data.length > 0) {
                                    $.each(response.data, function(index, subtopic) {
                                        $subtopicSelect.append($('<option>', {
                                            value: subtopic.id,
                                            text: subtopic.name
                                        }));
                                    });
                                } else {
                                    $subtopicSelect.append('<option disabled>Alt konu bulunamadı</option>');
                                }
                            },
                            error: function(xhr) {
                                handleAjaxError(xhr);
                            }
                        });
                    }
                });
        </script>
        <!--end::Javascript-->
    </body>
    <!--end::Body-->

</html>
<?php } else {
    header("location: index");
}
