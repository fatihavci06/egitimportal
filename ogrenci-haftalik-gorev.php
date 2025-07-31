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
    $lesson = new Classes();
    $lessons = $lesson->getLessonsList($_SESSION['class_id']);
    include_once "views/pages-head.php";

    if ($_SESSION['role'] == 5) {

        include "classes/userslist.classes.php";
        $user = new User();
        $studentClass = $user->getStudentDataWithParentId($_SESSION['id']);

        $classId = $studentClass[0]['class_id'] ?? null;
    } else {
        $classId = $_SESSION['class_id'];
    }

?>
    <style>
        /* Minimal custom style for colors not in Bootstrap's palette, 
           or for specific border widths if Bootstrap's are not enough. */
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
    </style>
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
                                <div id="kt_app_content_container" style="margin-top:-20px!important;" class="app-container container-fluid">
                                    <div class="row align-items-center mb-12">
                                        <header class="container-fluid bg-custom-light py-3 d-flex justify-content-between align-items-center
                                             border-top border-bottom border-custom-red" style="border-width: 5px !important;">

                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-danger me-3 shadow icon-circle-lg d-flex justify-content-center align-items-center"
                                                    style="width: 80px; height: 80px;">
                                                    <i class="fas fa-calendar-check fa-2x text-white"></i>
                                                </div>

                                                <h1 class="fs-3 fw-bold text-dark mb-0">Yapılacak Etkinlikler</h1>
                                            </div>

                                        </header>

                                    </div>
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

                                    <div class="row mt-5 mb-5 justify-content-end">
                                        <div class="col-auto">
                                            <button type="button" id="filterButton" class="btn btn-primary btn-sm">Filtrele</button>
                                        </div>
                                        <div class="col-auto">
                                            <button type="button" id="clearFiltersButton" class="btn btn-secondary btn-sm">Temizle</button>
                                        </div>
                                    </div>



                                </div>
                            </div>

                            <!--begin::Content-->
                            <div id="kt_app_content" class="app-content flex-column-fluid">
                                <div id="kt_app_content_container" style="margin-top:-50px!important;" class="app-container container-fluid">
                                    <?php
                                    // PHP değişkenleri tanımlamaları
                                    $column = ($_SESSION['role'] == 1) ? 'col-lg-10' : 'col-lg-5';
                                    ?>

                                   <div class="row" > <div class="col-12 col-lg-2 order-1 order-lg-1 mb-4 mb-lg-0">
        <div class="card h-100 dersler-card-min-height"> <div class="card-body py-4">
                <div class="row g-2 g-lg-10 justify-content-center">
                    <?php foreach ($lessons as $l): ?>
                        <?php if ($l['name'] !== 'Robotik Kodlama' && $l['name'] !== 'Ders Deneme'): ?>
                            <div class="col-6 col-sm-4 col-lg-12 text-center mb-sm-2 mb-lg-1">
                                <a href="ders/<?= urlencode($l['slug']) ?>" class="d-block text-decoration-none text-dark">
                                    <img src="assets/media/icons/dersler/<?= htmlspecialchars($l['icons']) ?>" alt="Icon" class="img-fluid" style="width: 65px; height: 65px; object-fit: contain;" />
                                    <div class="mt-1 fw-bold"><?= htmlspecialchars($l['name']) ?></div>
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-10 order-2 order-lg-2">
        <div class="card mb-4"> <div class="card-body pt-0">
                <div id="eventResults" class="mt-4">
                    </div>
            </div>
        </div>

        <div class="card mb-4"> <div class="card-body pt-0">
                <div id="odev" class="mt-4">
                    </div>
            </div>
        </div>

        <?php if ($_SESSION['role'] != 1) { ?>
            <div class="card"> <div class="card-body pt-0">
                    <div id="eventResultsTest" class="mt-4">
                        </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <?php
    if ($_SESSION['role'] == 1) {
   
        include_once "views/weekly/add_weekly.php";
    } else {
        // include_once "views/weekly/add_unit_teacher.php";
    }
    ?>
</div>
<style>
    /* footer'ın bozulmasını engellemek için sol sütuna minimum yükseklik verme */
    .dersler-card-min-height {
        min-height: 400px; /* Bu değeri, sağdaki içeriklerin toplam yüksekliğine göre ayarlayın */
        /* Örneğin, sağdaki 3 kartın toplam yüksekliği + aralarındaki boşluklar kadar olabilir */
        /* Bu değeri tarayıcıda incele (inspect) yaparak veya deneyerek bulabilirsiniz */
    }

    /* Sağdaki kartların da gerektiğinde kendi içlerinde esnemesini sağlamak için */
    .col-lg-10 > .card {
        display: flex; /* Kartların içindeki body'yi esnetmek için */
        flex-direction: column;
    }
    .col-lg-10 > .card .card-body {
        flex-grow: 1; /* card-body'nin tüm boşluğu doldurmasını sağlar */
    }
</style>
                                </div>
                            </div>

                            <style>
                                /* Küçük ekranlarda ders isimlerinin alt boşluğunu azalt (çünkü yan yana olacaklar) */
                                @media (max-width: 991.98px) {

                                    /* Bootstrap'in lg breakpoint'i öncesi */
                                    .col-6.text-center.mb-sm-2.mb-lg-1 {
                                        margin-bottom: 0.5rem !important;
                                        /* mb-2 gibi */
                                    }
                                }

                                /* Ders ikonlarının ve metinlerinin linki doldurmasını sağla */
                                .col-6.text-center.mb-sm-2.mb-lg-1 a {
                                    display: flex;
                                    flex-direction: column;
                                    align-items: center;
                                    justify-content: center;
                                    height: 100%;
                                    /* İçeriği dikey ortalamak için */
                                }
                            </style>
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

            // Hata yönetimi için genel fonksiyon
            function handleAjaxError(xhr) {
                console.error('AJAX Error:', xhr.status, xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Hata',
                    text: 'Sunucu ile iletişimde bir sorun oluştu. Lütfen daha sonra tekrar deneyin.',
                    confirmButtonText: 'Tamam'
                });
            }

            // Tarihi YYYY-MM-DD formatında almak için yardımcı fonksiyon
            function getFormattedCurrentDate() {
                const today = new Date();
                const year = today.getFullYear();
                const month = String(today.getMonth() + 1).padStart(2, '0'); // Ay 0'dan başladığı için +1
                const day = String(today.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            // Verileri getirmek ve HTML'i güncellemek için merkezi fonksiyon
            function fetchWeeklyData(lessonId = '', classId = '', unitId = '', topicId = '', selectedDate = '') {
                // Eğer lessonId boşsa, varsayılan olarak seçili ders ID'sini al veya boş bırak
                // Bu fonksiyonun dışarıdan çağrılma durumunda varsayılan değeri olmasını sağlarız.
                if (!lessonId) {
                    lessonId = $('#lesson_id').val();
                }
                if (!classId) {
                    classId = $('#classId').val(); // classId'nin zaten mevcut bir inputtan geldiğini varsayıyorum
                }

                // Eğer ders seçili değilse uyarı ver ve işlemi durdur (sadece manuel filtreleme için geçerli)
                // Sayfa ilk yüklendiğinde varsayılan olarak filtreleme yapılacaksa bu uyarı mantığı gerekmez.
                // Ancak bu fonksiyon filterButton'a bağlı olduğu için bu kontrolü koruyalım.
                // if ($('#filterButton').length && (!lessonId || lessonId === '')) { // Sadece filtre butonu varsa bu kontrolü yap
                //     Swal.fire({
                //         icon: 'warning',
                //         title: 'Uyarı',
                //         text: 'Lütfen bir ders seçiniz.',
                //         confirmButtonText: 'Tamam'
                //     });
                //     $('#lesson_id').addClass('is-invalid'); // Bootstrap ile görsel uyarı
                //     return; // Filtreleme işlemini durdur
                // } else if ($('#lesson_id').length) {
                //     $('#lesson_id').removeClass('is-invalid');
                // }

                var postData = {
                    class_id: classId,
                    lesson_id: lessonId,
                    unit_id: unitId,
                    topic_id: topicId,
                    selectedDate: selectedDate || getFormattedCurrentDate() // Eğer selectedDate gönderilmediyse varsayılan olarak şimdi
                };

                // Send AJAX POST request
               $.ajax({
    url: 'includes/getweeklylistforsudent.inc.php',
    type: 'POST',
    data: postData,
    dataType: 'json', // Expecting JSON response from the PHP script
    success: function(response) {
        // Handle success response from PHP
        console.log(response);
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

            function formatDate(dateStr) {
                const date = new Date(dateStr);
                return date.toLocaleDateString('tr-TR', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
            }

            function calculateRemaining(endDateStr) {
                const now = new Date();
                const endDate = new Date(endDateStr);
                const diff = endDate - now;

                if (diff <= 0) return '0 gün';

                const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                return `${days} gün`;
            }

            function formatDateShort(dateString) {
                const date = new Date(dateString);
                const options = {
                    month: 'long',
                    day: 'numeric'
                };
                return date.toLocaleDateString('tr-TR', options);
            }

            function capitalize(str) {
                return str.charAt(0).toUpperCase() + str.slice(1);
            }

            // type'a göre path eşlemesi
            function buildEventHref(event) {
                const map = {
                    unit: 'unite',
                    topic: 'konu',
                    subtopic: 'alt-konu',
                    homework: 'ogrenci-odev-detay'
                };
                const base = map[event.type] || 'unite';
                return `${base}/${encodeURIComponent(event.slug)}`;
            }

            // Separate events by type
            const generalEvents = eventList.filter(event => event.type !== 'homework');
            const homeworkEvents = eventList.filter(event => event.type === 'homework');

            // --- Render General Events ---
            generalEvents.sort((a, b) => new Date(a.start) - new Date(b.start));

            const groupedGeneral = {};
            let generalHtml = '';

            generalEvents.forEach(event => {
                const key = getMonthYear(event.start);
                if (!groupedGeneral[key]) groupedGeneral[key] = [];
                groupedGeneral[key].push(event);
            });

            for (const key in groupedGeneral) {
                generalHtml += `<h2 class="text-center event-month mt-4 mb-4">${capitalize(key)}</h2>`;

                groupedGeneral[key].forEach(event => {
                    const href = buildEventHref(event);
                    generalHtml += `
        <div class="card mb-2 shadow-sm event-card" style="padding: 0;">
            <div class="card-body py-2 px-3">
                <div class="row align-items-center">
                    <div class="col-4">
                        <h5 class="card-title text-dark mb-0" style="font-size: 1.2rem;">
                            <a href="${href}" class="text-decoration-none text-dark">
                                ${event.name}
                            </a>
                        </h5>
                    </div>

                    <div class="col-4 d-flex align-items-center">
                        <i class="fas fa-calendar-alt text-primary me-2"></i>
                        <small class="text-muted" style="font-size: 1.1rem;">
                            ${formatDateShort(event.start)} - ${formatDateShort(event.end)}
                        </small>
                    </div>

                    <div class="col-4 text-end">
                        ${new Date(event.end) < new Date() 
                            ? '<span class="badge bg-danger">Sona ermiştir</span>' 
                            : '<span class="badge bg-success">Kalan: ' + calculateRemaining(event.end) + '</span>'
                        }
                    </div>
                </div>
            </div>
        </div>
    `;
                });
            }

            $('#eventResults').html(generalHtml);

            // --- Render Homework Events ---
            let homeworkHtml = '';
            if (homeworkEvents.length > 0) {
                homeworkHtml += '<h2 class="text-center event-month mt-4 mb-4">Ödevler</h2>';
                homeworkEvents.forEach(event => {
                    const href = buildEventHref(event);
                    homeworkHtml += `
        <div class="card mb-2 shadow-sm event-card" style="padding: 0;">
            <div class="card-body py-2 px-3">
                <div class="row align-items-center">
                    <div class="col-4">
                        <h5 class="card-title text-dark mb-0" style="font-size: 1.2rem;">
                            <a href="${href}" class="text-decoration-none text-dark">
                                ${event.name}
                            </a>
                        </h5>
                    </div>

                    <div class="col-4 d-flex align-items-center">
                        <i class="fas fa-clipboard-list text-info me-2"></i>
                        <small class="text-muted" style="font-size: 1.1rem;">
                            ${formatDateShort(event.start)} - ${formatDateShort(event.end)}
                        </small>
                    </div>

                    <div class="col-4 text-end">
                        ${new Date(event.end) < new Date() 
                            ? '<span class="badge bg-danger">Sona ermiştir</span>' 
                            : '<span class="badge bg-warning">Kalan: ' + calculateRemaining(event.end) + '</span>'
                        }
                    </div>
                </div>
            </div>
        </div>
    `;
                });
                $('#odev').html(homeworkHtml); // Display homework in the #odev div
            } else {
                $('#odev').html(`<div class="alert alert-info">Henüz tanımlanmış ödev bulunmamaktadır.</div>`);
            }


            if (Array.isArray(response.testData) && response.testData.length > 0) {
                let testHtml = '<h2 class="text-center event-month mt-4 mb-4">Testler</h2>';

                response.testData.forEach(test => {
                    testHtml += `
        <div class="card mb-3 shadow-sm border-start border-3 border-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <i class="fas fa-vial text-danger me-2"></i>
                        <strong style="font-size: 1.3rem;">${test.name}</strong>
                    </div>
                    <a href="ogrenci-test-coz.php?id=${test.id}" target="_blank" class="btn btn-success btn-sm">
                        Sınava Gir
                    </a>
                </div>
                <div class="text-muted" style="font-size: 1.2rem;">
                    <i class="fas fa-clock me-1"></i>
                    ${formatDateShort(test.start_date)} - ${formatDateShort(test.end_date)} 
                    <span class="badge bg-${new Date(test.end_date) < new Date() ? 'danger' : 'success'} ms-2">${new Date(test.end_date) < new Date() ? 'Tarihi Geçmiş' : 'Aktif'}</span>
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

            }

            // Sayfa yüklendiğinde (DOM hazır olduğunda) çalışacak kod
            $(document).ready(function() {
                // İlk yüklemede, ders seçili değilse bile şimdiki tarihle verileri getir
                // Eğer ders seçimi zorunluysa, bu satırı kaldırmanız veya varsayılan bir ders ID'si eklemeniz gerekebilir.
                // Örneğin, eğer ilk ders option'ının value'su doluysa:
                var initialLessonId = $('#lesson_id').val();
                var initialClassId = $('#classId').val(); // Eğer classId elementi varsa

                fetchWeeklyData(initialLessonId, initialClassId, '', '', getFormattedCurrentDate());


                // Ders seçimi değiştiğinde üniteleri getir
                $('#lesson_id').on('change', function() {
                    var lessonId = $(this).val();
                    var classId = $('#classId').val(); // Sınıf ID'sini de gönderiyoruz
                    var $unitSelect = $('#unit_id');
                    $unitSelect.empty().append('<option value="">Ünite seçiniz</option>');
                    $('#topic_id').html('<option value="">Konu seçiniz</option>'); // Konu seçimini de temizle
                    $('#subtopic_id').html('<option value="">Alt Konu seçiniz</option>'); // Alt Konu seçimini de temizle


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

                    $topicSelect.empty().append('<option value="">Konu seçiniz</option>');
                    $('#subtopic_id').html('<option value="">Alt Konu seçiniz</option>'); // Alt Konu seçimini de temizle

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

                // Konu seçimi değiştiğinde alt konuları getir (şu an yorum satırında, ancak düzgün çalışması için ekledim)
                $('#topic_id').on('change', function() {
                    var classId = $('#classId').val();
                    var lessonId = $('#lesson_id').val();
                    var unitId = $('#unit_id').val();
                    var topicId = $(this).val();
                    var $subtopicSelect = $('#subtopic_id');

                    $subtopicSelect.empty().append('<option value="">Alt Konu seçiniz</option>');

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


                // Filtreleme butonu tıklaması
                $('#filterButton').on('click', function() {
                    var lessonId = $('#lesson_id').val();
                    var classId = $('#classId').val();
                    var unitId = $('#unit_id').val();
                    var topicId = $('#topic_id').val();

                    // Sadece buton tıklamasında ders seçimi kontrolü yapılır
                    fetchWeeklyData(lessonId, classId, unitId, topicId);
                });

                // Temizle butonu tıklaması
                $('#clearFiltersButton').on('click', function() {
                    // Filtreleme alanlarını temizle
                    $('#title').val(''); // Eğer böyle bir input varsa
                    $('#class_id').val('').trigger('change'); // 'change' olayı ile diğer bağımlı selectbox'ları da temizle
                    $('#lesson_id').val('').trigger('change'); // Ders seçimi de temizlensin ve üniteleri tetiklesin
                    // Diğer selectbox'lar ders değiştiğinde zaten temizlenecek ama yine de emin olalım
                    $('#unit_id').empty().append('<option value="">Ünite seçiniz</option>');
                    $('#topic_id').empty().append('<option value="">Konu seçiniz</option>');
                    $('#subtopic_id').empty().append('<option value="">Alt Konu seçiniz</option>');

                    // Temizlenmiş filtrelerle varsayılan (şimdiki tarih) verileri yeniden yükle.
                    // Bu, 'getweeklylistforsudent' servisinize boş filtre değerleri ve şimdiki tarih gönderecektir.
                    fetchWeeklyData('', '', '', '', getFormattedCurrentDate());
                });
            });
        </script>
    </body>
    <!--end::Body-->

</html>
<?php } else {
    header("location: index");
}
