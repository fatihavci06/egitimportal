<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 2 or $_SESSION['role'] == 10002 or $_SESSION['role'] == 5)) {
  
    include_once "classes/dbh.classes.php";
    include "classes/addhomework-std.classes.php";
    include "classes/homework-std-view.classes.php";
    include_once "classes/lessons.classes.php";
    include_once "classes/lessons-view.classes.php";
    include_once "classes/units.classes.php";
    include_once "classes/units-view.classes.php";
    include_once "classes/topics.classes.php";
    include_once "classes/topics-view.classes.php";
    include "classes/classes.classes.php";
    $contents = new ShowHomeworkContents();
    $units = new ShowUnit();
    $topics = new ShowTopic();
    $lessons = new ShowLesson();
    include_once "views/pages-head.php";
    $lessonData = new Classes();
    if($_SESSION['role']==5)
    {
        $getChildClassId=$lessonData->setChildClassIdSession($_SESSION['role']==5);
        $_SESSION['class_id']=$getChildClassId;
      
      

    }
    $lessonList = $lessonData->getLessonsList($_SESSION['class_id']);

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
                                    <div class="card" style="margin-top: -20px;">
                                        <header class="container-fluid bg-custom-light py-3 d-flex justify-content-between align-items-center
                                             border-top border-bottom border-custom-red" style="border-width: 5px !important;">

                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-danger me-3 shadow icon-circle-lg d-flex justify-content-center align-items-center"
                                                    style="width: 80px; height: 80px;">
                                                    <i class="fas fa-calendar-check fa-2x text-white"></i>
                                                </div>

                                                <h1 class="fs-3 fw-bold text-dark mb-0">Ödev Listesi </h1>
                                            </div>

                                        </header>
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
                                                    <input type="text" data-kt-customer-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Ödev Ara" />
                                                </div>
                                                <!--end::Search-->
                                            </div>
                                            <!--begin::Card title-->
                                            <!--begin::Card toolbar-->
                                            <div class="card-toolbar">
                                                <!--begin::Toolbar-->
                                                <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                                                    <!--begin::Filter-->
                                                    <?php if ($_SESSION['role'] == 1 or $_SESSION['role'] == 2 or $_SESSION['role'] == 10002 or $_SESSION['role'] == 5) : ?>
                                                        <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                            <i class="ki-duotone ki-filter fs-2">
                                                                <span class="path1"></span>
                                                                <span class="path2"></span>
                                                            </i>Filtre</button>
                                                    <?php endif; ?>
                                                    <!--begin::Menu 1-->
                                                    <?php if($_SESSION['role']!=5){ ?>
                                                    <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true" id="kt-toolbar-filter">
                                                        <!--begin::Header-->
                                                        <div class="px-7 py-5">
                                                            <div class="fs-4 text-gray-900 fw-bold">Filtreleme </div>
                                                        </div>
                                                        <!--end::Header-->
                                                        <!--begin::Separator-->
                                                        <div class="separator border-gray-200"></div>
                                                        <!--end::Separator-->
                                                        <!--begin::Content-->
                                                        <div class="px-7 py-5"> 
                                                            <!--begin::Input group-->
                                                            <div class="mb-10">
                                                                <!--begin::Label-->
                                                                <label class="form-label fs-5 fw-semibold mb-3">Ders: </label>
                                                                <!--end::Label-->
                                                                <!--begin::Input-->
                                                                <select class="form-select form-select-solid fw-bold" id="lessons" data-kt-select2="true" data-placeholder="Ders Seçin" data-allow-clear="true" data-kt-customer-table-filter="lesson" data-dropdown-parent="#kt-toolbar-filter">
                                                                    <option value=""></option>
                                                                    <?php $lessons->getLessonSelectList() ?>
                                                                </select>
                                                                <!--end::Input-->
                                                            </div>
                                                            <!--end::Input group-->
                                                            <div class="mb-10">
                                                                <!--begin::Label-->
                                                                <label class="form-label fs-5 fw-semibold mb-3">Ünite:</label>
                                                                <!--end::Label-->
                                                                <!--begin::Input-->
                                                                <select class="form-select form-select-solid fw-bold" id="units" data-kt-select2="true" data-placeholder="Ünite Seçin" data-allow-clear="true" data-kt-customer-table-filter="unit" data-dropdown-parent="#kt-toolbar-filter">
                                                                    <option></option>
                                                                    <?php $units->getUnitSelectList() ?>
                                                                </select>
                                                                <!--end::Input-->
                                                            </div>
                                                            <!--end::Input group-->
                                                            <!--end::Input group-->
                                                            <div class="mb-10">
                                                                <!--begin::Label-->
                                                                <label class="form-label fs-5 fw-semibold mb-3">Konu:</label>
                                                                <!--end::Label-->
                                                                <!--begin::Input-->
                                                                <select class="form-select form-select-solid fw-bold" id="topics" data-kt-select2="true" data-placeholder="Konu Seçin" data-allow-clear="true" data-kt-customer-table-filter="school" data-dropdown-parent="#kt-toolbar-filter">
                                                                    <option></option>
                                                                    <?php $topics->getTopicListForFilter() ?>
                                                                </select>
                                                                <!--end::Input-->
                                                            </div>
                                                            <!--end::Input group-->
                                                            <!--end::Input group-->
                                                            <div class="mb-10">
                                                                <!--begin::Label-->
                                                                <label class="form-label fs-5 fw-semibold mb-3">Alt Konu:</label>
                                                                <!--end::Label-->
                                                                <!--begin::Input-->
                                                                <select class="form-select form-select-solid fw-bold" id="sub_topics" data-kt-select2="true" data-placeholder="Ünite Seçin" data-allow-clear="true" data-kt-customer-table-filter="school" data-dropdown-parent="#kt-toolbar-filter">
                                                                    <option></option>
                                                                    <?php $topics->getTopicListForFilter() ?>
                                                                </select>
                                                                <!--end::Input-->
                                                            </div>
                                                            <!--end::Input group-->

                                                            <!--begin::Actions-->
                                                            <div class="d-flex justify-content-end">
                                                                <button type="reset" class="btn btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true" data-kt-customer-table-filter="reset">Temizle</button>
                                                                <button type="submit" class="btn btn-primary" data-kt-menu-dismiss="true" data-kt-customer-table-filter="filter">Uygula</button>
                                                            </div>
                                                            <!--end::Actions-->
                                                        </div>
                                                        <!--end::Content-->
                                                    </div>
                                                    <?php }?>
                                                    <!--end::Menu 1-->
                                                    <!--end::Filter-->
                                                </div>
                                                <!--end::Toolbar-->
                                                <!--begin::Group actions-->
                                                <div class="d-flex justify-content-end align-items-center d-none" data-kt-customer-table-toolbar="selected">
                                                    <div class="fw-bold me-5">
                                                        <span class="me-2" data-kt-customer-table-select="selected_count"></span>Seçildi
                                                    </div>
                                                    <button type="button" class="btn btn-danger btn-sm" data-kt-customer-table-select="delete_selected">Seçilenleri Pasif Yap</button>
                                                </div>
                                                <!--end::Group actions-->
                                            </div>
                                            <!--end::Card toolbar-->
                                        </div>
                                        <!--end::Card header-->
                                        <!--begin::Card body-->
                                        <div class="card-body pt-0 table-responsive">
                                            <!--begin::Table-->
                                             <?php if($_SESSION['role']!=5){ ?>
                                            <div class="row">
                                                <div class="col-3 col-lg-2">
                                                    <div class="row g-10 ">
                                                        <?php     foreach ($lessonList as $l): ?>
                                                            <?php if ($l['name'] !== 'Robotik Kodlama' && $l['name'] !== 'Ders Deneme'): ?>
                                                                <div class="col-12 mb-1 text-center">
                                                                    <a href="ders/<?= urlencode($l['slug']) ?>">
                                                                        <img src="assets/media/icons/dersler/<?= htmlspecialchars($l['icons']) ?>" alt="Icon" class="img-fluid" style="width: 65px; height: 65px; object-fit: contain;" />

                                                                        <div class="mt-1"><?= htmlspecialchars($l['name']) ?></div>
                                                                    </a>
                                                                </div>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                                                 <?php } ?>
                                                <div class="col-9 col-lg-10">
                                                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table">
                                                        <thead>
                                                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                                                <th class="min-w-125px">Ödev</th>
                                                                <th class="min-w-125px">Alt Konu</th>
                                                                <th class="min-w-125px">Konu</th>
                                                                <th class="min-w-125px">Ünite</th>
                                                                <th class="min-w-125px">Ders</th>
                                                                <th class="min-w-125px">Sınıf</th>
                                                                <th class="min-w-125px">Bitiş Tarihi</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody class="fw-semibold text-gray-600">
                                                          
                                                            <?php $contents->getHomeworkListForStudent(); ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <!--end::Table-->
                                        </div>
                                        <!--end::Card body-->
                                    </div>
                                    <!--end::Card-->
                                    <!--begin::Modals-->
                                    <!--begin::Modal - Customers - Add-->
                                    <?php // include_once "views/topics/add_topic.php" 
                                    ?>
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
        <script src="assets/js/custom/apps/contents/list/export.js"></script>
        <script src="assets/js/custom/apps/homeworks/list/list-student.js"></script>
        <!-- <script src="assets/js/custom/apps/homeworks/add.js"></script> -->
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
            $(querySelector('[class="classes"]')).on('change', function() {
                // Revalidate the field when an option is chosen
                validator.revalidateField('classes');

                var classChoose = $("#classes").val();


                // AJAX isteği gönder
                $.ajax({
                    allowClear: true,
                    type: "POST",
                    url: "includes/select_for_lesson.inc.php",
                    data: {
                        class: classChoose
                    },
                    dataType: "json",
                    success: function(data) {
                        // İkinci Select2'nin içeriğini güncelle

                        if (data.length > 0) {
                            $('#lessons').select2('destroy');
                            $('#lessons').html('<option value="">Ders Yok</option>');
                            $('#lessons').select2({
                                data: data
                            });
                            $('#units').select2('destroy');
                            $('#units').html('<option value="">Ünite Yok</option>');
                            $('#topics').select2('destroy');
                            $('#topics').html('<option value="">Konu Yok</option>');
                            $('#sub_topics').select2('destroy');
                            $('#sub_topics').html('<option value="">Alt Konu Yok</option>');
                        } else {
                            $('#classes').select2('destroy');
                            $('#lessons').select2('destroy');
                            $('#lessons').html('<option value="">Ders Yok</option>');
                            $('#units').select2('destroy');
                            $('#units').html('<option value="">Ünite Yok</option>');
                            $('#topics').select2('destroy');
                            $('#topics').html('<option value="">Konu Yok</option>');
                            $('#sub_topics').select2('destroy');
                            $('#sub_topics').html('<option value="">Alt Konu Yok</option>');
                        }

                    },
                    error: function(xhr, status, error, response) {
                        Swal.fire({
                            text: error.responseText + ' ' + xhr.responseText,
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Tamam, anladım!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        }).then(function(result) {
                            if (result.isConfirmed) {

                                // Enable submit button after loading
                                submitButton.disabled = false;
                            }
                        });
                        //alert(status + "0");

                    }
                });
            });


            // Revalidate country field. For more info, plase visit the official plugin site: https://select2.org/
            $(querySelector('[name="lessons"]')).on('change', function() {
                // Revalidate the field when an option is chosen
                validator.revalidateField('classes');
                validator.revalidateField('lessons');

                var classChoose = $("#classes").val();

                var lessonsChoose = $("#lessons").val();


                // AJAX isteği gönder
                $.ajax({
                    allowClear: true,
                    type: "POST",
                    url: "includes/select_for_unit.inc.php",
                    data: {
                        class: classChoose,
                        lesson: lessonsChoose
                    },
                    dataType: "json",
                    success: function(data) {
                        // İkinci Select2'nin içeriğini güncelle

                        if (data.length > 0) {
                            if ($('#units').data('select2')) { // Select2'nin başlatılıp başlatılmadığını kontrol edin
                                $('#units').select2('destroy');
                            }
                            $('#units').html('<option value="">Ünite Seçiniz...</option>'); // Varsayılan yer tutucuya sıfırla
                            $('#units').select2({
                                data: data
                            }); // Yeni veriyle başlat/yeniden başlat
                            $('#topics').select2('destroy');
                            $('#topics').html('<option value="">Konu Yok</option>');
                            $('#sub_topics').select2('destroy');
                            $('#sub_topics').html('<option value="">Alt Konu Yok</option>');
                        } else {
                            $('#lessons').select2('destroy');
                            $('#units').select2('destroy');
                            $('#units').html('<option value="">Ünite Yok</option>');
                            $('#topics').select2('destroy');
                            $('#topics').html('<option value="">Konu Yok</option>');
                            $('#sub_topics').select2('destroy');
                            $('#sub_topics').html('<option value="">Alt Konu Yok</option>');
                        }

                    },
                    error: function(xhr, status, error, response) {
                        Swal.fire({
                            text: error,
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Tamam, anladım!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        }).then(function(result) {
                            if (result.isConfirmed) {

                                // Enable submit button after loading
                                submitButton.disabled = false;
                            }
                        });
                        //alert(status + "0");

                    }
                });
            });


            // Revalidate country field. For more info, plase visit the official plugin site: https://select2.org/
            $(querySelector('[name="units"]')).on('change', function() {
                // Revalidate the field when an option is chosen
                validator.revalidateField('classes');
                validator.revalidateField('lessons');
                validator.revalidateField('units');

                var classChoose = $("#classes").val();

                var lessonsChoose = $("#lessons").val();

                var unitsChoose = $("#units").val();


                // AJAX isteği gönder
                $.ajax({
                    allowClear: true,
                    type: "POST",
                    url: "includes/select_for_topic.inc.php",
                    data: {
                        class: classChoose,
                        lesson: lessonsChoose,
                        unit: unitsChoose
                    },
                    dataType: "json",
                    success: function(data) {
                        // İkinci Select2'nin içeriğini güncelle

                        if (data.length > 0) {
                            if ($('#topics').data('select2')) { // Select2'nin başlatılıp başlatılmadığını kontrol edin
                                $('#topics').select2('destroy');
                            }
                            $('#topics').html('<option value="">Konu Seçiniz...</option>'); // Varsayılan yer tutucuya sıfırla
                            $('#topics').select2({
                                data: data
                            });
                            $('#sub_topics').select2('destroy');
                            $('#sub_topics').html('<option value="">Alt Konu Yok</option>');
                        } else {
                            $('#topics').select2('destroy');
                            $('#topics').html('<option value="">Konu Yok</option>');
                            $('#sub_topics').select2('destroy');
                            $('#sub_topics').html('<option value="">Alt Konu Yok</option>');
                        }

                    },
                    error: function(xhr, status, error, response) {
                        Swal.fire({
                            text: error,
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Tamam, anladım!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        }).then(function(result) {
                            if (result.isConfirmed) {

                                // Enable submit button after loading
                                submitButton.disabled = false;
                            }
                        });
                        //alert(status + "0");

                    }
                });
            });


            // Revalidate country field. For more info, plase visit the official plugin site: https://select2.org/
            $(querySelector('[name="topics"]')).on('change', function() {
                // Revalidate the field when an option is chosen
                validator.revalidateField('classes');
                validator.revalidateField('lessons');
                validator.revalidateField('units');
                validator.revalidateField('topics');

                var classChoose = $("#classes").val();

                var lessonsChoose = $("#lessons").val();

                var unitsChoose = $("#units").val();

                var topicsChoose = $("#topics").val();

                // AJAX isteği gönder
                $.ajax({
                    allowClear: true,
                    type: "POST",
                    url: "includes/select_for_subtopic.inc.php",
                    data: {
                        class: classChoose,
                        lesson: lessonsChoose,
                        unit: unitsChoose,
                        topics: topicsChoose
                    },
                    dataType: "json",
                    success: function(data) {
                        // İkinci Select2'nin içeriğini güncelle

                        if (data.length > 0) {
                            $('#sub_topics').select2({
                                data: data
                            });
                        } else {
                            $('#sub_topics').select2('destroy');
                            $('#sub_topics').html('<option value="">Alt Konu Yok</option>');
                        }

                    },
                    error: function(xhr, status, error, response) {
                        Swal.fire({
                            text: error,
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Tamam, anladım!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        }).then(function(result) {
                            if (result.isConfirmed) {

                                // Enable submit button after loading
                                submitButton.disabled = false;
                            }
                        });
                        //alert(status + "0");

                    }
                });
            });
        </script>
    </body>
    <!--end::Body-->

</html>
<?php } else {
    header("location: index");
}
