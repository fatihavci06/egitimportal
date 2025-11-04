<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 10001 or $_SESSION['role'] == 10002 or $_SESSION['role'] == 10005)) {
    /*  include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";
    require_once "classes/student.classes.php";
    $unit = new Classes();
    $studentInfo = new Student();

    if ($_SESSION['role'] == 10005) {
        $getPreSchoolStudent = $studentInfo->getPreSchoolStudentsInfoForParents($_SESSION['id']);
        $studentidsi = $getPreSchoolStudent[0]['id'];
    } else {
        $studentidsi = $_SESSION['id'];
    }
    $contentList = $unit->getMainSchoolContentByUnitAndTopicId($_GET['unit_id'], $_GET['topic_id']);

    $topicInfo = $unit->getMainSchoolTopicById($_GET['topic_id']); */

    include_once "views/pages-head.php";
?>
    <style>
        /* Genel Stil İyileştirmeleri */

        .main-card-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            border: 1px solid #e0e0e0;
        }

        .custom-card {
            border: none;
            padding: 0px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            background-color: white;
            margin-bottom: 25px;
        }

        .card-title-custom {
            font-size: 1.2rem;
            font-weight: 700;
            color: #ed5606;
            margin-bottom: 15px;
        }

        .content-wrapper {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .icon-small {
            font-size: 50px !important;
            color: #e83e8c !important;
        }



        .btn-custom {
            /* background-color: #1b84ff; */
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 600;
            transition: background-color 0.3s ease;
            margin-top: 15px;
        }

        #myTable thead {
            display: none;
        }

        .btn-custom:hover {
            background-color: #1a9c7b;
        }

        .left-align {
            margin-left: 0;
            margin-right: auto;
        }

        .right-align {
            margin-left: auto;
            margin-right: 0;
        }

        .left-align .card-body {
            align-items: flex-start;
            text-align: left;
        }

        .left-align .content-wrapper {
            flex-direction: row;
        }

        .right-align .card-body {
            align-items: flex-end;
            text-align: right;
        }

        .right-align .content-wrapper {
            flex-direction: row-reverse;
        }

        .card-body {
            display: flex;
            flex-direction: column;
        }

        .bg-custom-light {
            background-color: #e6e6fa;
            /* Light purple */
        }

        .border-custom-red {
            border-color: #d22b2b !important;
        }

        .text-custom-cart {
            color: #6a5acd;
            /* Slate blue for the cart */
        }

        /* For the circular icon, we'll use a larger padding or fixed size */
        .icon-circle-lg {
            width: 60px;
            /* fixed width */
            height: 60px;
            /* fixed height */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-circle-lg img {
            max-width: 100%;
            /* Ensure image scales within the circle */
            max-height: 100%;
        }


        /* Animasyonlar */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
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
                                    <div class="card-body pt-5 ">
                                        <div class="row container-fluid" style="margin-top:-25px;">
                                            <header class="container-fluid bg-custom-light py-3 d-flex justify-content-between align-items-center
                                             border-top border-bottom border-custom-red mb-2" style="border-width: 5px !important; height:85px;margin-bottom: 26px !important;">

                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-danger me-3 shadow icon-circle-lg d-flex justify-content-center align-items-center"
                                                        style="width: 65px; height: 65px;">
                                                        <i class="fas fa-bullseye fa-2x text-white"></i>
                                                    </div>

                                                    <h1 class="fs-3 fw-bold text-dark mb-0">Family Game Nights</h1>
                                                </div>

                                            </header>
                                        </div>
                                    </div>
                                    <div class="row container-fluid">
                                        <div class="col-md-5">
                                            <div class="month-block" style="
                    /* Ana arkaplan görseli: Karakter ve ahşap tabela */
                    background-image: url('uploads/aile-atolyeleri/baslik-sablon.png');
                    background-repeat: no-repeat;
                   /* background-size: 100% 100%; 
                    min-height: 250px;*/
                    position: relative;
                    min-height: 340px;
                    background-size: contain;
                    background-position: center;
                ">

                                                <div class="tabela-content" style="
                        position: absolute;
                        top: 180px; /* Ahşap tabelanın görünen kısmına göre pozisyon */
                            left: 50%;

                        transform: translate(-50%, -50%);
                        width: 170px; /* Tabelanın genişliğini artırdık */
                        border-radius: 5px; /* Köşe yuvarlaklığı */
                        overflow: hidden; /* İçerik taşmasını engellemek ve border-radius'u korumak için */
                    ">

                                                    <div class="tabela-top-white" style="
                            padding: 10px;
                            text-align: center;
                        ">
                                                        <div class="ikon-ve-baslik" style="display: flex; align-items: center; justify-content: center; flex-wrap: wrap;">

                                                            <div style="flex-grow: 1;">
                                                                <span class="d-block fw-bold" style="font-size: 1.4rem; color: #fff;">
                                                                    Family Game Nights
                                                                </span>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>
                                            <div style="background-color: #ffbb05;display: flex;column-gap: 20px;" class="rounded display-6 shadow p-4">
                                                <i class="fa-solid fa-calendar-days ps-5" style="color: #000; font-size:3rem !important"></i> 30.11.2025 12:00
                                            </div>
                                            <div style="background-color: #ffbb05;display: flex;column-gap: 20px;" class="rounded display-6 shadow p-4 mt-10">
                                                <i class="fa-solid fa-hourglass-half ps-5" style="color: #000; font-size:3rem !important"></i>
                                                <div id="geri_sayim">
                                                    Kalan Süre
                                                    <?php

                                                    $bitis_tarihi = "November 30, 2025 12:00:00";

                                                    // JavaScript'e aktarım
                                                    echo "<script>";
                                                    echo "var countDownDate = new Date(\"$bitis_tarihi\").getTime();";
                                                    echo "</script>";
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="tabela-content" style="
                        width: 50%; /* Tabelanın genişliğini artırdık */
                        border-radius: 5px; /* Köşe yuvarlaklığı */
                        overflow: hidden; /* İçerik taşmasını engellemek ve border-radius'u korumak için */
                        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Hafif gölge */
                    ">

                                                <div class="tabela-top-white" style="
                            background-color: white;
                            padding: 10px;
                        ">
                                                    <div class="ikon-ve-baslik" style="display: flex; align-items: center; justify-content: center; flex-wrap: wrap;">

                                                        <div style="flex-grow: 1;">
                                                            <span class="d-block fw-bold" style="font-size: 2rem; color: #0ab1b9;">
                                                                <i class="fa-solid fa-file-arrow-down" style="font-size: 2rem; color: #0ab1b9; margin-right: 10px"></i>Atölye İçeriği
                                                            </span>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="tabela-bottom-green" style="
                            background-color: #0ab1b9; 
                            padding: 5px 10px;
                            text-align: left;
                        ">
                                                    <span class="text-white" style="font-size: 1rem;">İncele</span>
                                                </div>
                                            </div>
                                            <div class="tabela-content  mt-10" style="
                        width: 50%; /* Tabelanın genişliğini artırdık */
                        border-radius: 5px; /* Köşe yuvarlaklığı */
                        overflow: hidden; /* İçerik taşmasını engellemek ve border-radius'u korumak için */
                        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Hafif gölge */
                    ">

                                                <div class="tabela-top-white" style="
                            background-color: white;
                            padding: 10px;
                        ">
                                                    <div class="ikon-ve-baslik" style="display: flex; align-items: center; justify-content: center; flex-wrap: wrap;">

                                                        <div style="flex-grow: 1;">
                                                            <span class="d-block fw-bold" style="font-size: 2rem; color: #0ab1b9;">
                                                                <i class="fa-solid fa-link" style="font-size: 2rem; color: #0ab1b9; margin-right: 10px"></i>Canlı Bağlantı
                                                            </span>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="tabela-bottom-green" style="
                            background-color: #0ab1b9; 
                            padding: 5px 10px;
                            text-align: left;
                        ">
                                                    <span class="text-white" style="font-size: 1rem;">İncele</span>
                                                </div>
                                            </div>
                                        </div>
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
            // PHP'den gelen bitiş tarihi (countDownDate) burada kullanılır.

            // Sayacı her saniye güncelle
            var x = setInterval(function() {

                // Şu anki zamanı al
                var now = new Date().getTime();

                // Kalan süreyi hesapla
                var distance = countDownDate - now;

                // Zaman birimlerini hesapla
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Sonucu HTML'e yaz
                document.getElementById("geri_sayim").innerHTML = days + "G " + hours + "S " + minutes + "D " + seconds + "Sn ";

                // Eğer geri sayım bittiyse
                if (distance < 0) {
                    clearInterval(x);
                    document.getElementById("geri_sayim").innerHTML = "SÜRE DOLDU!";
                }
            }, 1000); // 1000 milisaniye = 1 saniye

            // $(document).ready(function() {

            //     // Sayfa yüklendiğinde tabloyu dolduran fonksiyon
            //     function loadInitialTableData() {
            //         var lessonId = $('#lesson_id option:eq(1)').val() || null;

            //         const data = {
            //             month: $('#month').val(),
            //             week: $('#week').val(),
            //             activity_title: $('#activity_title').val(),
            //             content_title: $('#content_title').val(),
            //             concept_title: $('#concept_title').val(),
            //             main_school_class_id: $('#main_school_class_id').val(),
            //             lesson_id: lessonId,
            //             unit_id: $('#unit_id').val(),
            //             topic_id: $('#topic_id').val()
            //         };

            //         $.ajax({
            //             url: 'includes/ajax.php?service=filter-main-school-content',
            //             method: 'POST',
            //             data: data,
            //             dataType: 'json',
            //             success: function(response) {
            //                 if (response.status === 'success' && Array.isArray(response.data)) {

            //                     if ($.fn.DataTable.isDataTable('#myTable')) {
            //                         $('#myTable').DataTable().clear().destroy();
            //                     }

            //                     $('#myTable').DataTable({
            //                         data: response.data,
            //                         columns: [{
            //                             data: 'subject',
            //                             title: 'Konu',
            //                             render: function(data, type, row) {
            //                                 // row.slug varsa kullan, yoksa default url
            //                                 var urlSlug = row.slug || 'ana-okulu-icerikler-detay.php?id=' + row.id;
            //                                 var subjectName = data;

            //                                 return `
            //                                                 <div class="col-12" style="margin-bottom: -20px; font-size:12px!important;">
            //                                                     <a href="${urlSlug}" class="text-decoration-none">
            //                                                         <div class="border rounded d-flex align-items-center p-2" 
            //                                                             style="border: 2px solid #333; box-shadow: 0 2px 6px rgba(0,0,0,0.15); justify-content: flex-start;">
            //                                                             <button type="button" class="btn btn-light btn-sm me-3">
            //                                                                 <i style="font-size:20px!important" class="bi bi-play-fill"></i>
            //                                                             </button>
            //                                                             <div>
            //                                                                 <div class="fw-semibold fs-5" style="font-size:12px!important; color: #000;">${subjectName}</div>
            //                                                                 <div style="font-size:10px; color: #666;">${row.month}</div>  <!-- Buraya ay bilgisi -->
            //                                                             </div>
            //                                                         </div>
            //                                                     </a>
            //                                                 </div>`;
            //                             }
            //                         }]
            //                     });


            //                     $('#lesson_id option:eq(1)').prop('selected', true).trigger('change');

            //                 } else {
            //                     alert("Veri boş geldi veya status success değil.");
            //                 }
            //             },
            //             error: function(err) {
            //                 console.error("Hata:", err);
            //             }
            //         });
            //     }

            //     // Sayfa yüklenince tabloyu doldur
            //     loadInitialTableData();

            //     $('#lesson_id').on('change', function() {
            //         var selectedLessonId = $(this).val();

            //         $.ajax({
            //             url: 'includes/ajax.php?service=mainSchoolGetUnits',
            //             type: 'POST',
            //             data: {
            //                 class_id: $('#main_school_class_id').val(),
            //                 lesson_id: selectedLessonId
            //             },
            //             dataType: 'json',
            //             success: function(response) {
            //                 if (response.status === 'success') {
            //                     var unitSelect = $('#unit_id');
            //                     unitSelect.empty();

            //                     if (response.data.length > 0) {
            //                         unitSelect.append('<option selected disabled>Ünite Seçiniz...</option>');

            //                         $.each(response.data, function(index, lesson) {
            //                             unitSelect.append(
            //                                 $('<option></option>')
            //                                 .val(lesson.id)
            //                                 .text(lesson.name)
            //                             );
            //                         });
            //                     } else {
            //                         unitSelect.append('<option disabled>Bu sınıfa ait ders bulunamadı.</option>');
            //                     }
            //                 }
            //             },
            //             error: function() {
            //                 alert('Sunucu ile iletişimde hata oluştu!');
            //             }
            //         });
            //     });

            //     $('#unit_id').on('change', function() {
            //         var selectedUnitId = $(this).val();

            //         $.ajax({
            //             url: 'includes/ajax.php?service=mainSchoolGetTopics',
            //             type: 'POST',
            //             data: {
            //                 unit_id: selectedUnitId
            //             },
            //             dataType: 'json',
            //             success: function(response) {
            //                 if (response.status === 'success') {
            //                     var topicSelect = $('#topic_id');
            //                     topicSelect.empty();

            //                     if (response.data.length > 0) {
            //                         topicSelect.append('<option selected disabled>Konu Seçiniz...</option>');

            //                         $.each(response.data, function(index, topic) {
            //                             topicSelect.append(
            //                                 $('<option></option>')
            //                                 .val(topic.id)
            //                                 .text(topic.name)
            //                             );
            //                         });
            //                     } else {
            //                         topicSelect.append('<option disabled>Bu sınıfa ait ders bulunamadı.</option>');
            //                     }
            //                 }
            //             },
            //             error: function() {
            //                 alert('Sunucu ile iletişimde hata oluştu!');
            //             }
            //         });
            //     });

            //     // Select2 başlatma
            //     $('#week, #activity_title, #content_title, #concept_title').select2({
            //         placeholder: "Seçiniz",
            //         allowClear: true
            //     });

            //     // Filtrele butonu
            //     $('#filterBtn').on('click', function(e) {
            //         e.preventDefault();
            //         loadInitialTableData();
            //     });

            //     // Temizle butonu
            //     $('#clearFilterBtn').on('click', function() {
            //         $('#month').val('');
            //         $('#week, #activity_title, #content_title, #concept_title, #main_school_class_id').val('').trigger('change');
            //         $('#lesson_id').val('Seçiniz').trigger('change');
            //         $('#unit_id').val('Seçiniz').trigger('change');
            //         $('#topic_id').val('Seçiniz').trigger('change');

            //         if ($.fn.DataTable.isDataTable('#myTable')) {
            //             $('#myTable').DataTable().clear().draw();
            //         }
            //     });

            //     // Yaş grubu değiştiğinde dersleri getir
            //     $('#main_school_class_id').on('change', function() {
            //         var selectedClassId = $(this).val();
            //         var lessonSelect = $('#lesson_id');
            //         var unitSelect = $('#unit_id');
            //         var topicSelect = $('#topic_id');

            //         lessonSelect.empty();
            //         unitSelect.empty();
            //         topicSelect.empty();

            //         lessonSelect.append('<option selected disabled>Seçiniz</option>');
            //         unitSelect.append('<option selected disabled>Önce ders seçiniz...</option>');
            //         topicSelect.append('<option selected disabled>Önce ünite seçiniz...</option>');

            //         if (selectedClassId) {
            //             $.ajax({
            //                 url: 'includes/ajax.php?service=getMainSchoolLessonList',
            //                 type: 'POST',
            //                 data: {
            //                     class_id: selectedClassId
            //                 },
            //                 dataType: 'json',
            //                 success: function(response) {
            //                     if (response.status === 'success' && response.data.length > 0) {
            //                         $.each(response.data, function(index, lesson) {
            //                             lessonSelect.append(
            //                                 $('<option></option>')
            //                                 .val(lesson.id)
            //                                 .text(lesson.name)
            //                             );
            //                         });
            //                     } else {
            //                         lessonSelect.append('<option disabled>Bu yaş grubuna ait ders bulunamadı.</option>');
            //                     }
            //                 },
            //                 error: function() {
            //                     alert('Dersler yüklenirken bir hata oluştu!');
            //                 }
            //             });
            //         }
            //     });

            // });
        </script>

    </body>

</html>
<?php } else {
    header("location: index");
}
?>