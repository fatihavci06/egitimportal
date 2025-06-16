<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or  $_SESSION['role'] == 2 or  $_SESSION['role'] == 10002)) {
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
                                            <input type="hidden" name="classId" id="classId" value="<?= $_SESSION['class_id'] ?>">
                                            <label class="required fs-6 fw-semibold mb-2" for="lesson_id">Dersler</label>
                                            <select class="form-select" id="lesson_id" required>
                                                <option value="">Ders seçiniz</option>
                                                <?= $chooseLesson->getLessonSelectListForweeklyList($_SESSION['class_id']); ?>
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
                                <div id="kt_app_content_container" class="app-container container-fluid">
                                    <!--begin::Card-->
                                    <div class="card">

                                        <!--begin::Card body-->
                                        <div class="card-body pt-0">
                                            <!--begin::Table-->
                                            <div id="eventResults" class="mt-4">

                                            </div>
                                            <!--end::Table-->
                                        </div>
                                        <!--end::Card body-->
                                    </div>
                                    <!--end::Card-->
                                    <!--begin::Modals-->
                                    <!--begin::Modal - Customers - Add-->
                                    <?php if ($_SESSION['role'] == 1) {
                                        include_once "views/weekly/add_weekly.php";
                                    } else {
                                        //include_once "views/weekly/add_unit_teacher.php";
                                    } ?>
                                    <!--end::Modal - Customers - Add-->
                                    <!--begin::Modal - Adjust Balance-->
                                    <div class="modal fade" id="kt_customers_export_modal" tabindex="-1" aria-hidden="true">
                                        <!--begin::Modal dialog-->
                                        <div class="modal-dialog modal-dialog-centered mw-650px">
                                            <!--begin::Modal content-->
                                            <div class="modal-content">
                                                <!--begin::Modal header-->
                                                <div class="modal-header">
                                                    <!--begin::Modal title-->
                                                    <h2 class="fw-bold">Export Customers</h2>
                                                    <!--end::Modal title-->
                                                    <!--begin::Close-->
                                                    <div id="kt_customers_export_close" class="btn btn-icon btn-sm btn-active-icon-primary">
                                                        <i class="ki-duotone ki-cross fs-1">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>
                                                    </div>
                                                    <!--end::Close-->
                                                </div>
                                                <!--end::Modal header-->
                                                <!--begin::Modal body-->
                                                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                                                    <!--begin::Form-->
                                                    <form id="kt_customers_export_form" class="form" action="#">
                                                        <!--begin::Input group-->
                                                        <div class="fv-row mb-10">
                                                            <!--begin::Label-->
                                                            <label class="fs-5 fw-semibold form-label mb-5">Select Export Format:</label>
                                                            <!--end::Label-->
                                                            <!--begin::Input-->
                                                            <select data-control="select2" data-placeholder="Select a format" data-hide-search="true" name="format" class="form-select form-select-solid">
                                                                <option value="excell">Excel</option>
                                                                <option value="pdf">PDF</option>
                                                                <option value="cvs">CVS</option>
                                                                <option value="zip">ZIP</option>
                                                            </select>
                                                            <!--end::Input-->
                                                        </div>
                                                        <!--end::Input group-->
                                                        <!--begin::Input group-->
                                                        <div class="fv-row mb-10">
                                                            <!--begin::Label-->
                                                            <label class="fs-5 fw-semibold form-label mb-5">Select Date Range:</label>
                                                            <!--end::Label-->
                                                            <!--begin::Input-->
                                                            <input class="form-control form-control-solid" placeholder="Pick a date" name="date" />
                                                            <!--end::Input-->
                                                        </div>
                                                        <!--end::Input group-->
                                                        <!--begin::Row-->
                                                        <div class="row fv-row mb-15">
                                                            <!--begin::Label-->
                                                            <label class="fs-5 fw-semibold form-label mb-5">Payment Type:</label>
                                                            <!--end::Label-->
                                                            <!--begin::Radio group-->
                                                            <div class="d-flex flex-column">
                                                                <!--begin::Radio button-->
                                                                <label class="form-check form-check-custom form-check-sm form-check-solid mb-3">
                                                                    <input class="form-check-input" type="checkbox" value="1" checked="checked" name="payment_type" />
                                                                    <span class="form-check-label text-gray-600 fw-semibold">All</span>
                                                                </label>
                                                                <!--end::Radio button-->
                                                                <!--begin::Radio button-->
                                                                <label class="form-check form-check-custom form-check-sm form-check-solid mb-3">
                                                                    <input class="form-check-input" type="checkbox" value="2" checked="checked" name="payment_type" />
                                                                    <span class="form-check-label text-gray-600 fw-semibold">Visa</span>
                                                                </label>
                                                                <!--end::Radio button-->
                                                                <!--begin::Radio button-->
                                                                <label class="form-check form-check-custom form-check-sm form-check-solid mb-3">
                                                                    <input class="form-check-input" type="checkbox" value="3" name="payment_type" />
                                                                    <span class="form-check-label text-gray-600 fw-semibold">Mastercard</span>
                                                                </label>
                                                                <!--end::Radio button-->
                                                                <!--begin::Radio button-->
                                                                <label class="form-check form-check-custom form-check-sm form-check-solid">
                                                                    <input class="form-check-input" type="checkbox" value="4" name="payment_type" />
                                                                    <span class="form-check-label text-gray-600 fw-semibold">American Express</span>
                                                                </label>
                                                                <!--end::Radio button-->
                                                            </div>
                                                            <!--end::Input group-->
                                                        </div>
                                                        <!--end::Row-->
                                                        <!--begin::Actions-->
                                                        <div class="text-center">
                                                            <button type="reset" id="kt_customers_export_cancel" class="btn btn-light btn-sm me-3">Discard</button>
                                                            <button type="submit" id="kt_customers_export_submit" class="btn btn-primary btn-sm">
                                                                <span class="indicator-label">Submit</span>
                                                                <span class="indicator-progress">Please wait...
                                                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                                            </button>
                                                        </div>
                                                        <!--end::Actions-->
                                                    </form>
                                                    <!--end::Form-->
                                                </div>
                                                <!--end::Modal body-->
                                            </div>
                                            <!--end::Modal content-->
                                        </div>
                                        <!--end::Modal dialog-->
                                    </div>
                                    <!--end::Modal - New Card-->
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

            // Sınıf seçimi değiştiğinde dersleri getir 
            var classId = $("#classId").val();
            // if (classId) {
            //     fetchLessonsForClass(classId);
            // };

            // function fetchLessonsForClass(classId) {
            //     if (classId !== '') {
            //         $.ajax({
            //             url: 'includes/ajax_yazgul.php?service=getLessonListForStudent',
            //             type: 'POST',
            //             data: {
            //                 class_id: classId
            //             },
            //             dataType: 'json',
            //             success: function(response) {
            //                 var $lessonSelect = $('#lesson_id');
            //                 $('#option_count').val(response.data.optionCount); // Bu kısım ilgili inputunuz varsa
            //                 $lessonSelect.empty();
            //                 $lessonSelect.append('<option value="">Ders seçiniz</option>');
            //                 $.each(response.data.lessons, function(index, lesson) {
            //                     $lessonSelect.append('<option value="' + lesson.id + '">' + lesson.name + '</option>');
            //                 });
            //                 // Diğer bağımlı selectbox'ları temizle
            //                 $('#unit_id').html('<option value="">Ünite seçiniz</option>');
            //                 $('#topic_id').html('<option value="">Seçiniz</option>');
            //                 $('#subtopic_id').html('<option value="">Alt Konu seçiniz</option>');
            //             },
            //             error: function(xhr) {
            //                 handleAjaxError(xhr);
            //             }
            //         });
            //     } else {
            //         // Sınıf seçimi boşsa tüm bağımlı selectbox'ları temizle
            //         $('#lesson_id').html('<option value="">Ders seçiniz</option>');
            //         $('#unit_id').html('<option value="">Ünite seçiniz</option>');
            //         $('#topic_id').html('<option value="">Seçiniz</option>');
            //         $('#subtopic_id').html('<option value="">Alt Konu seçiniz</option>');
            //     }
            // }

            // Ders seçimi değiştiğinde üniteleri getir
            $('#lesson_id').on('change', function() {
                var lessonId = $(this).val();
                var classId = $('#classId').val(); // Sınıf ID'sini de gönderiyoruz
                var $unitSelect = $('#unit_id');
                $unitSelect.empty().append('<option value="">Ünite seçiniz</option>');
                $('#topic_id').html('<option value="">Seçiniz</option>');
                $('#subtopic_id').html('<option value="">Alt Konu seçiniz</option>');

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
            $('#topic_id').on('change', function() {
                var subtopicId = $('#subtopic_id').val('');
                var classId = $('#classId').val();
                var lessonId = $('#lesson_id').val();
                var unitId = $('#unit_id').val();
                var topicId = $(this).val();
                // var $subtopicSelect = $('#subtopic_id');

                // $subtopicSelect.empty().append('<option value="">Alt Konu seçiniz</option>');

                if (topicId !== '') {
                    $.ajax({
                        url: 'includes/ajax_yazgul.php?service=getSubtopicListForStudent',
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

            // Alt konular değiştiğinde seçimi değiştiğinde içerikleri getir
            // $('#subtopic_id').on('change', function() {
            //     var classId = $('#classId').val();
            //     var lessonId = $('#lesson_id').val();
            //     var unitId = $('#unit_id').val();
            //     var topicId = $('#topic_id').val();
            //     var subtopicId = $(this).val();

            //     if (lessonId !== '') {
            //         $.ajax({
            //             url: 'includes/ajax_yazgul.php?service=getContentList',
            //             type: 'POST',
            //             dataType: 'json',
            //             data: {
            //                 lesson_id: lessonId,
            //                 class_id: classId,
            //                 unitId: unitId,
            //                 topicId: topicId,
            //                 subtopicId: subtopicId,
            //             },
            //             success: function(response) {
            //                 if (response.status === 'success' && response.data.length > 0) {
            //                     $.each(response.data, function(index, unit) {
            //                         $unitSelect.append($('<option>', {
            //                             value: unit.id,
            //                             text: unit.name
            //                         }));
            //                     });
            //                 } else {
            //                     // $unitSelect.append('<option disabled>Ünite bulunamadı</option>');
            //                 }
            //             },
            //             error: function(xhr) {
            //                 handleAjaxError(xhr);
            //             }
            //         });
            //     }
            // });

            // Filtreleme butonu tıklaması
            $('#filterButton').on('click', function() {
            
                var classId = $('#classId').val();
                var lessonId = $('#lesson_id').val();
                var unitId = $('#unit_id').val();
                var topicId = $('#topic_id').val();
                var subtopicId = $('#subtopic_id').val();

                // Prepare data to be sent
                var postData = {
                    class_id: classId,
                    lesson_id: lessonId,
                    unit_id: unitId,
                    topic_id: topicId,
                    subtopic_id: subtopicId
                };

                // Send AJAX POST request
                $.ajax({
                    url: 'includes/getweeklylistforsudent.inc.php', // Replace with the actual path to your PHP script
                    type: 'POST',
                    data: postData,
                    dataType: 'json', // Expecting JSON response from the PHP script
                    success: function(response) {
                        // Handle success response from PHP
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
                                    month: 'short',
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
                                html += `<h5 class="text-center event-month">${capitalize(key)}</h5>`;

                                grouped[key].forEach(event => {
                                    let eventName = '';
                                    if (lessonId !== '') {
                                        eventName += `<div class="event-name">
                                                   <a href="unite/${event.slug}">
                                                       ${event.name}
                                                    </a>
                                                </div>`
                                    }
                                    else if (unitId !== '') {
                                        eventName += `<div class="event-name">
                                                   <a href="konu/${event.slug}">
                                                       ${event.name}
                                                    </a>
                                                </div>`
                                    } else if (topicId !== '') {
                                        eventName += `<div class="event-name">
                                                   <a href="alt-konu/${event.slug}">
                                                       ${event.name}
                                                    </a>
                                                </div>`
                                    } else {
                                        eventName += `<div class = "event-name" >${event.name}</div>`
                                    }
                                    html += `
                                        <div class="event-list">
                                            <div class="event-body my-4">
                                                <div class="event-date">
                                                    ${formatDate(event.start)} - ${formatDate(event.end)}
                                                </div>
                                                    ${eventName}
                                            </div>
                                        </div>
                                        `;
                                });
                            }


                            $('#eventResults').html(html);

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
