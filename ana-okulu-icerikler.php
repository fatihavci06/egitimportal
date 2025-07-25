<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 10001 or $_SESSION['role'] == 10002)) {
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";

    include_once "views/pages-head.php";
?>
    <style>
/* .red-border {
    border: 2px solid red;
    padding: 15px;
    border-radius: 5px;
} */ /* Removed the red-border style */
</style>
    <body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" class="app-default">
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
                                    <div class="card-body pt-5">
                                        <div class="row mt-4">
                                            <div class="col-lg-4">
                                                <label class="fs-6 fw-semibold mb-2" for="main_school_class_id">Yaş Grubu </label>
                                                <?php
                                                $class = new Classes();
                                                $roleId = $_SESSION['role'];

                                                if ($roleId == 10002) {
                                                    $classId = $class->getClassId($_SESSION['id']);
                                                    $mainSchoolClasses = $class->getAgeGroup($classId);
                                                    $_SESSION['class_id'] = $classId;
                                                } else {
                                                    unset($_SESSION['class_id']);
                                                    $mainSchoolClasses = $class->getAgeGroup(); // class_id = null
                                                }
                                                ?>
                                                <select class="form-select" id="main_school_class_id" required aria-label="Default select example">
                                                    <option value="">Seçiniz</option>
                                                    <?php foreach ($mainSchoolClasses as $c) { ?>
                                                        <option <?php if (isset($classId) && $c['id'] == $classId) echo 'selected'; ?> value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <?php
                                            $lessonList = $class->getMainSchoolLessonList();
                                            ?>
                                            <div class="col-lg-4">
                                                <label for="lesson_id" class="form-label">Ders Adı</label>
                                                <select class="form-select form-control" id="lesson_id" name="lesson_id">
                                                    <option selected disabled>Seçiniz</option>
                                                    <?php foreach ($lessonList as $lesson) { ?>
                                                        <option value="<?= $lesson['id'] ?>"><?= $lesson['name'] ?></option>    
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="unit_id" class="form-label">Ünite Adı</label>
                                                <select class="form-select form-control" id="unit_id" name="lesson_id">
                                                    <option selected disabled>Önce ders seçiniz...</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mt-4">
                                            <div class="col-lg-4">
                                                <label for="topic_id" class="form-label">Konu Adı</label>
                                                <select class="form-select form-control" id="topic_id" name="lesson_id">
                                                    <option selected disabled>Önce ünite seçiniz...</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-4">
                                                <label class="required fs-6 fw-semibold mb-2" for="month">Ay </label>
                                                <select class="form-select" id="month" required aria-label="Default select example">
                                                    <option value="">Seçiniz</option>
                                                    <option value="Ocak">Ocak</option>
                                                    <option value="Şubat">Şubat</option>
                                                    <option value="Mart">Mart</option>
                                                    <option value="Nisan">Nisan</option>
                                                    <option value="Mayıs">Mayıs</option>
                                                    <option value="Haziran">Haziran</option>
                                                    <option value="Temmuz">Temmuz</option>
                                                    <option value="Ağustos">Ağustos</option>
                                                    <option value="Eylül">Eylül</option>
                                                    <option value="Ekim">Ekim</option>
                                                    <option value="Kasım">Kasım</option>
                                                    <option value="Aralık">Aralık2</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-4">
                                                <label class="fs-6 fw-semibold mb-2" for="week">Özel Hafta Seçimi </label>
                                                <?php
                                                $class = new Classes();
                                                $weekList = $class->getWeekList();
                                                ?>
                                                <select class="form-select" id="week" required aria-label="Default select example">
                                                    <option value="">Seçiniz</option>
                                                    <?php foreach ($weekList as $week) { ?>
                                                        <option value="<?= $week['id'] ?>"><?= $week['name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mt-4">
                                            <div class="col-lg-4">
                                                <label class=" fs-6 fw-semibold mb-2" for="activity_type">Etkinlik Türü Başlığı</label>
                                                <select class="form-select form-control" id="activity_title" aria-label="Default select example">
                                                    <option value="">Seçiniz</option>
                                                    <?php
                                                    $activityTitle = $class->getTitleList(3);
                                                    foreach ($activityTitle as $title) {
                                                    ?>
                                                        <option value="<?= $title['id'] ?>"><?= $title['title'] ?></option>
                                                    <?php }  ?>
                                                </select>
                                            </div>
                                            <div class="col-lg-4">
                                                <label class=" fs-6 fw-semibold mb-2" for="content_title">İçerik Başlığı</label>
                                                <select class="form-select form-control" id="content_title" aria-label="Default select example">
                                                    <option value="">Seçiniz</option>
                                                    <?php
                                                    $titlesContent = $class->getTitleList(1);
                                                    foreach ($titlesContent as $title) {
                                                    ?>
                                                        <option value="<?= $title['id'] ?>"><?= $title['title'] ?></option>
                                                    <?php }  ?>
                                                </select>
                                            </div>
                                            <div class="col-lg-4">
                                                <label class=" fs-6 fw-semibold mb-2" for="concept_title">Kavram Başlığı</label>
                                                <select class="form-select form-control" id="concept_title" aria-label="Default select example">
                                                    <option value="">Seçiniz</option>
                                                    <?php
                                                    $titlesContent = $class->getTitleList(2);
                                                    foreach ($titlesContent as $title) {
                                                    ?>
                                                        <option value="<?= $title['id'] ?>"><?= $title['title'] ?></option>
                                                    <?php }  ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mt-4">
                                            <div class="mt-4 text-end">
                                                <button id="filterBtn" class="btn btn-primary btn-sm">Filtrele</button>
                                                <button id="clearFilterBtn" class="btn btn-secondary">Filtreyi Temizle</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <table id="myTable" class="table align-middle table-row-dashed fs-6 gy-5">
                                            <thead>

                                            </thead>
                                            <tbody></tbody>
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
        <script>
            var hostUrl = "assets/";
        </script>
        <script src="assets/plugins/global/plugins.bundle.js"></script>
        <script src="assets/js/scripts.bundle.js"></script>
        <script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
        <script src="assets/plugins/custom/tinymce/tinymce.bundle.js"></script>
        <script src="assets/js/custom/apps/subtopics/list/export.js"></script>
        <script src="assets/js/custom/apps/subtopics/list/list.js"></script>
        <script src="assets/js/custom/apps/subtopics/list/topicadd.js"></script>
        <script src="assets/js/custom/apps/subtopics/add.js"></script>
        <script src="assets/js/custom/apps/subtopics/create.js"></script>
        <script src="assets/js/widgets.bundle.js"></script>
        <script src="assets/js/custom/widgets.js"></script>
        <script src="assets/js/custom/apps/chat/chat.js"></script>
        <script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
        <script src="assets/js/custom/utilities/modals/create-account.js"></script>
        <script src="assets/js/custom/utilities/modals/create-app.js"></script>
        <script src="assets/js/custom/utilities/modals/users-search.js"></script>
        <script>
            $(document).ready(function() {
                $('#lesson_id').on('change', function() {
                    var selectedLessonId = $(this).val();

                    $.ajax({
                        url: 'includes/ajax.php?service=mainSchoolGetUnits', // Backend dosyanın yolu
                        type: 'POST',
                        data: {
                            class_id:$('#main_school_class_id').val(),
                            lesson_id: selectedLessonId
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
                                
                            }
                        },
                        error: function() {
                            alert('Sunucu ile iletişimde hata oluştu!');
                        }
                    });
                });
                $('#unit_id').on('change', function() {
                    var selectedUnitId = $(this).val();

                    $.ajax({
                        url: 'includes/ajax.php?service=mainSchoolGetTopics', // Backend dosyanın yolu
                        type: 'POST',
                        data: {
                            unit_id: selectedUnitId
                        },
                        dataType: 'json', // JSON olarak bekliyoruz
                        success: function(response) {
                            if (response.status === 'success') {
                                var topicSelect = $('#topic_id');
                                topicSelect.empty(); // Önceki optionları temizle

                                if (response.data.length > 0) {
                                    console.log(response.data);
                                    topicSelect.append('<option selected disabled>Konu Seçiniz...</option>');

                                    // Gelen datayı option olarak ekle
                                    $.each(response.data, function(index, topic) {
                                        topicSelect.append(
                                            $('<option></option>')
                                            .val(topic.id)
                                            .text(topic.name)
                                        );
                                    });
                                } else {
                                    topicSelect.append('<option disabled>Bu sınıfa ait ders bulunamadı.</option>');
                                }
                            } else {
                                
                            }
                        },
                        error: function() {
                            alert('Sunucu ile iletişimde hata oluştu!');
                        }
                    });
                });
                $('#week').select2({
                    placeholder: "Seçiniz",
                    allowClear: true
                });
                $('#activity_title').select2({
                    placeholder: "Seçiniz",
                    allowClear: true
                });
                $('#content_title').select2({
                    placeholder: "Seçiniz",
                    allowClear: true
                });
                $('#concept_title').select2({
                    placeholder: "Seçiniz",
                    allowClear: true
                });
                $('#filterBtn').on('click', function(e) {
                    e.preventDefault();

                    const data = {
                        month: $('#month').val(),
                        week: $('#week').val(),
                        activity_title: $('#activity_title').val(),
                        content_title: $('#content_title').val(),
                        concept_title: $('#concept_title').val(),
                        main_school_class_id: $('#main_school_class_id').val(),
                        lesson_id: $('#lesson_id').val(),
                        unit_id: $('#unit_id').val(),
                        topic_id: $('#topic_id').val()

                    };

                    $.ajax({
                        url: 'includes/ajax.php?service=filter-main-school-content', // 🔁 burayı backend URL'in ile değiştir
                        method: 'POST',
                        data: data,
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success' && Array.isArray(response.data)) {

                                // DataTable önceden başlatıldıysa temizle
                                if ($.fn.DataTable.isDataTable('#myTable')) {
                                    $('#myTable').DataTable().clear().destroy();
                                }

                                // Yeni tabloyu başlat
                                $('#myTable').DataTable({
                                    data: response.data,
                                    columns: [{
                                            data: 'id',
                                            title: 'ID'
                                        },
                                        {
                                            data: 'subject',
                                            title: 'Konu'
                                        },
                                        {
                                            data: 'month',
                                            title: 'Ay'
                                        },
                                        {
                                            data: 'class_name',
                                            title: 'Yaş Grubu'
                                        },
                                        {
                                            data: 'id',
                                            title: 'İşlemler',
                                            render: function(data, type, row, meta) {
                                                // `data` burada ID oluyor
                                                return `<a href="ana-okulu-icerikler-detay.php?id=${data}" class="btn btn-sm btn-primary">Görüntüle</a>`;
                                            }
                                        }
                                    ]
                                });

                            } else {
                                alert("Veri boş geldi veya status success değil.");
                            }
                        },
                        error: function(err) {
                            console.error("Hata:", err);
                        }
                    });
                });


            });
            
            $('#clearFilterBtn').on('click', function() {
                $('#month').val('');

                // select2 ile oluşturulan selectbox'ları sıfırla ve görünümü güncelle
                $('#week').val('').trigger('change');
                $('#activity_title').val('').trigger('change');
                $('#content_title').val('').trigger('change');
                $('#concept_title').val('').trigger('change');
                $('#main_school_class_id').val('').trigger('change');
                $('#lesson_id').val('Seçiniz').trigger('change');
                $('#unit_id').val('Seçiniz').trigger('change');
                $('#topic_id').val('Seçiniz').trigger('change');

                // Eğer DataTable varsa içeriğini temizle
                if ($.fn.DataTable.isDataTable('#myTable')) {
                    $('#myTable').DataTable().clear().draw();
                }

                // (Opsiyonel) select2 kullanıyorsan .trigger('change') ekle
                // $('.form-select').val('').trigger('change');

                // Eğer tablo daha önce yüklenmişse sıfırla
                if ($.fn.DataTable.isDataTable('#myTable')) {
                    $('#myTable').DataTable().clear().draw();
                }
            });
            $('#main_school_class_id').on('change', function() {
        var selectedClassId = $(this).val();
        var lessonSelect = $('#lesson_id');
        var unitSelect = $('#unit_id');
        var topicSelect = $('#topic_id');

        lessonSelect.empty(); // Clear existing lessons
        unitSelect.empty();   // Clear existing units
        topicSelect.empty();  // Clear existing topics

        lessonSelect.append('<option selected disabled>Seçiniz</option>');
        unitSelect.append('<option selected disabled>Önce ders seçiniz...</option>');
        topicSelect.append('<option selected disabled>Önce ünite seçiniz...</option>');

        if (selectedClassId) { // Only make AJAX call if a class is selected
            $.ajax({
                url: 'includes/ajax.php?service=getMainSchoolLessonList', // New service for fetching lessons
                type: 'POST',
                data: {
                    class_id: selectedClassId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success' && response.data.length > 0) {
                        $.each(response.data, function(index, lesson) {
                            lessonSelect.append(
                                $('<option></option>')
                                .val(lesson.id)
                                .text(lesson.name)
                            );
                        });
                    } else {
                        lessonSelect.append('<option disabled>Bu yaş grubuna ait ders bulunamadı.</option>');
                    }
                },
                error: function() {
                    alert('Dersler yüklenirken bir hata oluştu!');
                }
            });
        }
    });
        </script>
        </body>
    </html>
<?php } else {
    header("location: index");
}
?>