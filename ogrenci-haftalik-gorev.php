<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 2 or $_SESSION['role'] == 5 or $_SESSION['role'] == 10002)) {
    include "classes/dbh.classes.php";
    include "classes/classes.classes.php";
    include "classes/classes-view.classes.php";
    include "classes/lessons.classes.php";
    include "classes/lessons-view.classes.php";
    include "classes/weekly.classes.php";
    include "classes/weekly-view.classes.php";
    $weekly = new ShowWeekly();
    $chooseLesson = new ShowLesson();
    include_once "views/pages-head.php";

    if($_SESSION['role'] == 5){
        
        include "classes/userslist.classes.php";
        $user = new User();
        $studentClass = $user->getStudentDataWithParentId($_SESSION['id']);

        $classId = $studentClass[0]['class_id'] ?? null;
        
    }else{
        $classId = $_SESSION['class_id'];
    }

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
                                            <input type="hidden" name="classId" id="classId" value="<?= $classId ?>">
                                            <label class="required fs-6 fw-semibold mb-2" for="lesson_id">Dersler</label>
                                            <select class="form-select" id="lesson_id" required>
                                                <option value="">Ders seçiniz </option>
                                                <?= $chooseLesson->getLessonSelectListForweeklyList($classId); ?>
                                            </select>
                                        </div>

                                        <div class="col-lg-4">
                                            <label class="fs-6 fw-semibold mb-2" for="unit_id">Ünite Seçimi</label>
                                            <select class="form-select" id="unit_id" required>
                                                <option value="">Ünite seçiniz</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="fs-6 fw-semibold mb-2" for="topic_id">Konu Seçimi</label>
                                            <select class="form-select" id="topic_id" required>
                                                <option value="">Seçiniz</option>
                                            </select>
                                        </div>
                                        <!-- <input type="hidden" name="subtopic_id" id="subtopic_id"> -->
                                        <!-- <div class="col-lg-4 mt-4">
                                            <label class="fs-6 fw-semibold mb-2" for="subtopic_id">Alt Konu Seçimi</label>
                                            <select class="form-select" id="subtopic_id" required>
                                                <option value="">Alt Konu seçiniz</option>
                                            </select>
                                        </div> -->
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
                                <div id="kt_app_content_container" class="app-container container-fluid row">
                                    <?php if ($_SESSION['role'] == 1){
                                        $column = 'col-lg-12';
                                    } else {
                                        $column = 'col-lg-6';
                                    } ?>
                                    <!--begin::Card-->
                                    <div class="card <?php echo $column; ?>">

                                        <!--begin::Card body-->
                                        <div class="card-body pt-0">
                                            <!--begin::Table-->
                                            <div id="eventResults" class="mt-4">

                                            </div>
                                            <!--end::Table-->
                                        </div>
                                        <!--end::Card body-->
                                    </div>
                                     <?php if ($_SESSION['role'] != 1){ ?>
                                    <div class="card col-lg-6">

                                        <!--begin::Card body-->
                                        <div class="card-body pt-0">
                                            <!--begin::Table-->
                                            <div id="eventResultsTest" class="mt-4">
                                                
                                            </div>
                                            <!--end::Table-->
                                        </div>
                                        <!--end::Card body-->
                                    </div>
                                    <?php } ?>
                                    <!--end::Card-->
                                    <!--begin::Modals-->
                                    <!--begin::Modal - Customers - Add-->
                                    <?php if ($_SESSION['role'] == 1) {
                                        include_once "views/weekly/add_weekly.php";
                                    } else {
                                        //include_once "views/weekly/add_unit_teacher.php";
                                    } ?>
                                    <!--end::Modal - Customers - Add-->
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
        <script src="assets/js/custom/apps/weekly/list/export.js"></script>
        <script src="assets/js/custom/apps/weekly/list/list.js"></script>
        <script src="assets/js/custom/apps/weekly/add.js"></script>
        <script src="assets/js/widgets.bundle.js"></script>
        <script src="assets/js/custom/widgets.js"></script>
        <script src="assets/js/custom/apps/chat/chat.js"></script>
        <script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
        <script src="assets/js/custom/utilities/modals/create-account.js"></script>
        <script src="assets/js/custom/utilities/modals/create-app.js"></script>
        <script src="assets/js/custom/utilities/modals/users-search.js"></script>
        <!--end::Custom Javascript-->
        <!--end::Javascript-->
        <script>
            // --- Ders, Ünite, Konu, Alt Konu Seçim Mantığı ---

            // Ders seçimi değiştiğinde üniteleri getir
            $('#lesson_id').on('change', function() {
                var lessonId = $(this).val();
                var classId = $('#classId').val(); // Sınıf ID'sini de gönderiyoruz
                var $unitSelect = $('#unit_id');
                $unitSelect.empty().append('<option value="">Ünite seçiniz</option>');
                $('#topic_id').html('<option value="">Seçiniz</option>');


                if (lessonId !== '') {
                    $.ajax({
                        url: 'includes/ajax_yazgul.php?service=getUnitListForStudent',
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
                var classId = $('#classId').val();
                var lessonId = $('#lesson_id').val();
                var unitId = $(this).val();
                var $topicSelect = $('#topic_id');

                $topicSelect.empty().append('<option value="">Seçiniz</option>');
                $('#subtopic_id').html('<option value="">Alt Konu seçiniz</option>');

                if (unitId !== '') {
                    $.ajax({
                        url: 'includes/ajax_yazgul.php?service=getTopicListForStudent',
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
            // $('#topic_id').on('change', function() {
            //     var subtopicId = $('#subtopic_id').val('');
            //     var classId = $('#classId').val();
            //     var lessonId = $('#lesson_id').val();
            //     var unitId = $('#unit_id').val();
            //     var topicId = $(this).val();
            // var $subtopicSelect = $('#subtopic_id');

            // $subtopicSelect.empty().append('<option value="">Alt Konu seçiniz</option>');

            // if (topicId !== '') {
            //     $.ajax({
            //         url: 'includes/ajax_yazgul.php?service=getSubtopicListForStudent',
            //         type: 'POST',
            //         dataType: 'json',
            //         data: {
            //             class_id: classId,
            //             lesson_id: lessonId,
            //             unit_id: unitId,
            //             topic_id: topicId
            //         },
            //         success: function(response) {
            //             if (response.status === 'success' && response.data.length > 0) {
            //                 $.each(response.data, function(index, subtopic) {

            //                 });
            //             } else {
            //                 $subtopicSelect.append('<option disabled>Alt konu bulunamadı</option>');
            //             }
            //         },
            //         error: function(xhr) {
            //             handleAjaxError(xhr);
            //         }
            //     });
            // }
            //});

            // Filtreleme butonu tıklaması
            $('#filterButton').on('click', function() {
                var lessonId = $('#lesson_id').val();
                
                
                if (!lessonId || lessonId === '') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Uyarı',
                        text: 'Lütfen bir ders seçiniz.',
                        confirmButtonText: 'Tamam'
                    });
                    $('#lesson_id').addClass('is-invalid'); // Bootstrap ile görsel uyarı
                    return; // Filtreleme işlemini durdur
                } else {
                    $('#lesson_id').removeClass('is-invalid');
                }

                var classId = $('#classId').val();
                var lessonId = $('#lesson_id').val();
                var unitId = $('#unit_id').val();
                var topicId = $('#topic_id').val();

                // Prepare data to be sent
                var postData = {
                    class_id: classId,
                    lesson_id: lessonId,
                    unit_id: unitId,
                    topic_id: topicId
                };

                // Send AJAX POST request
                $.ajax({
                    url: 'includes/getweeklylistforsudent.inc.php',
                    type: 'POST',
                    data: postData,
                    dataType: 'json', // Expecting JSON response from the PHP script
                    success: function(response) {
                        // Handle success response from PHP
                        console.log(response)
                        if (response.status === 'success') {
                            var eventList = response.data;
                            console.log('Events:', eventList);

                            function getMonthYear(dateString) {
                                const date = new Date(dateString);
                                const options = {
                                    year: 'numeric',
                                    month: 'long'
                                };
                                const str = date.toLocaleDateString('tr-TR', options);
                                return str.normalize('NFKD').replace(/\s+/g, ' ').trim().toLowerCase();
                            }

                            function formatDate(dateString) {
                                const date = new Date(dateString);
                                const options = {
                                    month: 'long', // ✅ "short" yerine "long"
                                    day: 'numeric'
                                };
                                return date.toLocaleDateString('tr-TR', options);
                            }

                            function capitalize(str) {
                                return str.charAt(0).toUpperCase() + str.slice(1);
                            }

                            eventList.sort((a, b) => new Date(a.start) - new Date(b.start));

                            const grouped = {};
                            let html = '';

                            eventList.forEach(event => {
                                const key = getMonthYear(event.start);
                                if (!grouped[key]) grouped[key] = [];
                                grouped[key].push(event);
                            });

                            for (const key in grouped) {
                                html += `<h2 class="text-center event-month mt-4 mb-4">${capitalize(key)}</h2>`;

                                grouped[key].forEach(event => {
                                    html += `
                                        <div class="card mb-3 shadow-sm event-card">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="fas fa-calendar-alt text-primary me-2"></i>
                                                    <h6 class="card-subtitle text-muted flex-grow-1 mb-0">
                                                        <small style="font-size: 1.2rem;"> 
                                                            ${formatDate(event.start)} - ${formatDate(event.end)}
                                                        </small>
                                                    </h6>
                                                </div>
                                                <h5 class="card-title text-dark mb-0" style="font-size: 1.3rem;">${event.name}</h5> 
                                            </div>
                                        </div>
                                    `;
                                });
                            }

                            $('#eventResults').html(html);
                            if (Array.isArray(response.testData) && response.testData.length > 0) {
                                let testHtml = '<h2 class="text-center event-month mt-4 mb-4">Testler</h2>';

                                response.testData.forEach(test => {
                                    testHtml += `
            <div class="card mb-3 shadow-sm border-start border-3 border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <i class="fas fa-vial text-danger me-2"></i>
                            <strong style="font-size: 1.3rem;">${test.test_title}</strong>
                        </div>
                        <a href="ogrenci-test-coz.php?id=${test.id}" target="_blank" class="btn btn-success btn-sm">
                            Sınava Gir
                        </a>
                    </div>
                    <div class="text-muted" style="font-size: 1.2rem;">
                        <i class="fas fa-clock me-1"></i>
                        ${formatDate(test.start_date)} - ${formatDate(test.end_date)} 
                        <span class="badge bg-${test.label === 'Tarihi Geçmiş' ? 'danger' : 'success'} ms-2">${test.label}</span>
                    </div>
                </div>
            </div>
        `;
                                });

                                $('#eventResultsTest').html(testHtml);
                            } else {
                                $('#eventResultsTest').html(`<div class="alert alert-warning">Henüz sınav tanımlanmamış.</div>`);
                            }

                        } else {
                            alert('Filtreleme başarısız: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error('AJAX Error:', status, error);
                        alert('Bir hata oluştu. Lütfen tekrar deneyin.');
                    }
                });

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
        </script>
    </body>
    <!--end::Body-->

</html>
<?php } else {
    header("location: index");
}
