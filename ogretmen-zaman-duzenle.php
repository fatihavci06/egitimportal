<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role'])) {
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";
?>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Canlı Ders Tarih ve Zaman Aralığı Seçimi</title>

        <?php include_once "views/pages-head.php"; ?>

        <link href="https://cdn.jsdelivr.net/npm/vanillajs-datepicker/dist/css/datepicker.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.min.css">
        <style>
            /* Sadece bu sayfa için gerekli özel stiller */
            .lesson-entry-group {
                border: 1px solid #e0e0e0;
                border-radius: 5px;
                padding: 15px;
                margin-bottom: 20px;
                background-color: #fcfcfc;
            }

            .time-range-inputs {
                display: flex;
                align-items: center;
                gap: 10px;
                /* Inputlar arasında boşluk */
            }

            .time-range-inputs input {
                flex: 1;
            }

            .lesson-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 10px;
                border: 1px solid #dee2e6;
                border-radius: 5px;
                margin-bottom: 10px;
                background-color: #e9ecef;
            }

            .live-lesson-container {
                /* Yeni eklenecek ana konteyner için stil */
                background-color: #ffffff;
                padding: 30px;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }
        </style>
    </head>

    <body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" data-kt-app-aside-enabled="true" data-kt-app-aside-fixed="true" data-kt-app-aside-push-toolbar="true" data-kt-app-aside-push-footer="true" class="app-default">
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
                                    <div class="card">
                                        <div class="card-header border-0 pt-6">
                                            <div class="card-title">
                                                <div class="d-flex align-items-center position-relative my-1">
                                                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                    <input type="text" data-kt-customer-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder=" Ara" />
                                                </div>
                                            </div>
                                            <div class="card-toolbar">
                                                <div class="d-flex justify-content-end align-items-center d-none" data-kt-customer-table-toolbar="selected">
                                                    <div class="fw-bold me-5">
                                                        <span class="me-2" data-kt-customer-table-select="selected_count"></span>Seçildi
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0">

                                            <div class="live-lesson-container">
                                                <h2 class="mb-4 text-center">Canlı Dersler İçin Uygun Tarih ve Zaman Aralığı Girin</h2>

                                                <div id="lessonEntries">
                                                    <label class="form-label">Ders Bilgileri:</label>
                                                    <div class="lesson-entry-group" data-lesson-id="1">
                                                        <div class="mb-3">
                                                            <label class="form-label">Ders Tarihi:</label>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control lesson-date" placeholder="Bir tarih seçin" data-datepicker-target>
                                                                <span class="input-group-text"><i class="fa-solid fa-calendar-days"></i></span>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Ders Zaman Aralığı:</label>
                                                            <div class="time-range-inputs">
                                                                <input type="time" class="form-control time-start" placeholder="Başlangıç Saati">
                                                                <span>-</span>
                                                                <input type="time" class="form-control time-end" placeholder="Bitiş Saati">
                                                                <button type="button" class="btn btn-danger remove-lesson-entry ms-2">Kaldır</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-info mb-3" id="addLessonEntry">Başka Bir Ders Zamanı Ekle</button>

                                                <button type="button" class="btn btn-primary w-100 mb-3" id="saveLessons">Dersleri Kaydet</button>

                                                <hr>

                                                <h3 class="mt-4 text-center">Eklenen Tarihler</h3>
                                                <div id="lessonsList">
                                                    <p class="text-muted text-center" id="noLessonsMessage">Henüz ders eklenmedi.</p>
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
        <script src="assets/js/widgets.bundle.js"></script>
        <script src="assets/js/custom/widgets.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker/dist/js/datepicker-full.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker/dist/js/locales/tr.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.all.min.js"></script>

        <script>
            // Mevcut jQuery ready fonksiyonunuz
            $(document).ready(function() {
                // DataTable başlat
                const table = $('#kt_customers_table').DataTable();

                // Arama kutusunu bağla
                $('[data-kt-customer-table-filter="search"]').on('keyup', function() {
                    table.search(this.value).draw();
                });

                // Mevcut diğer jQuery kodlarınız...
            });

            // Yeni eklenen Vanilla JS ve AJAX kodları
            document.addEventListener('DOMContentLoaded', function() {
                // Datepicker'ları başlatma fonksiyonu
                function initializeDatepickers() {
                    document.querySelectorAll('[data-datepicker-target]').forEach(elem => {
                        new Datepicker(elem, {
                            format: 'dd.mm.yyyy',
                            language: 'tr',
                            autohide: true,
                            todayHighlight: true,
                            startDate: new Date(),
                            minDate: new Date() // Geçmiş tarihleri engelle
                        });
                    });
                }

                // Sayfa yüklendiğinde mevcut datepicker'ları başlat
                initializeDatepickers();

                // **Yeni Fonksiyon: Mevcut Dersleri Yükle**
                async function loadExistingLessons() {
                    const lessonsList = document.getElementById('lessonsList');
                    const noLessonsMessage = document.getElementById('noLessonsMessage');

                    try {
                        const response = await fetch('includes/ajax.php?service=getTeacherTimeSettings&id=<?= $_GET['id'] ?>', {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json'
                            }
                        });

                        if (!response.ok) {
                            const errorData = await response.json();
                            throw new Error(errorData.message || 'Mevcut dersler çekilirken sunucu hatası.');
                        }

                        const data = await response.json();

                        if (data.status === 'success' && data.data.length > 0) {
                            noLessonsMessage.style.display = 'none'; // "Henüz ders eklenmedi" mesajını gizle
                            lessonsList.innerHTML = ''; // Mevcut listeyi temizle (varsa)

                            data.data.forEach(lesson => {
                                const lessonDiv = document.createElement('div');
                                lessonDiv.classList.add('lesson-item'); // CSS sınıfı ekleyelim
                                lessonDiv.innerHTML = `
                    <div>
                        <strong>Tarih:</strong> ${lesson.date}<br>
                        <strong>Zaman Aralığı:</strong> ${lesson.start_time.substring(0, 5)} - ${lesson.end_time.substring(0, 5)}
                    </div>
                    <button type="button" class="btn btn-danger btn-sm remove-display-lesson" data-lesson-id="${lesson.id}">Sil</button>
                `;
                                lessonsList.appendChild(lessonDiv);
                            });
                        } else if (data.status === 'success' && data.data.length === 0) {
                            noLessonsMessage.style.display = 'block'; // Ders yoksa mesajı göster
                            lessonsList.innerHTML = ''; // Listeyi temizle
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Uyarı!',
                                text: data.message || 'Mevcut dersler yüklenirken bir sorun oluştu.',
                                confirmButtonText: 'Tamam'
                            });
                        }

                    } catch (error) {
                        console.error('Mevcut dersler yüklenirken bir hata oluştu:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Hata!',
                            text: 'Mevcut dersler yüklenirken bir hata oluştu. Lütfen tekrar deneyin.',
                            confirmButtonText: 'Tamam'
                        });
                    }
                }

                // Sayfa yüklendiğinde mevcut dersleri yükle
                loadExistingLessons();


                // Ders zamanı ekle butonu
                document.getElementById('addLessonEntry').addEventListener('click', function() {
                    const lessonEntriesContainer = document.getElementById('lessonEntries');
                    const newLessonId = Date.now(); // Benzersiz bir ID oluştur

                    const newLessonEntry = document.createElement('div');
                    newLessonEntry.classList.add('lesson-entry-group', 'mt-4');
                    newLessonEntry.setAttribute('data-lesson-id', newLessonId); // ID'yi kaydet

                    newLessonEntry.innerHTML = `
                        <div class="mb-3">
                            <label class="form-label">Ders Tarihi:</label>
                            <div class="input-group">
                                <input type="text" class="form-control lesson-date" placeholder="Bir tarih seçin" data-datepicker-target>
                                <span class="input-group-text"><i class="fa-solid fa-calendar-days"></i></span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ders Zaman Aralığı:</label>
                            <div class="time-range-inputs">
                                <input type="time" class="form-control time-start" placeholder="Başlangıç Saati">
                                <span>-</span>
                                <input type="time" class="form-control time-end" placeholder="Bitiş Saati">
                                <button type="button" class="btn btn-danger remove-lesson-entry ms-2">Kaldır</button>
                            </div>
                        </div>
                    `;
                    lessonEntriesContainer.appendChild(newLessonEntry);

                    // Yeni eklenen datepicker'ı başlat
                    initializeDatepickers();
                });

                // Ders zamanı kaldır butonu (dinamik olarak eklenenler için)
                document.addEventListener('click', function(event) {
                    if (event.target.classList.contains('remove-lesson-entry')) {
                        const entryToRemove = event.target.closest('.lesson-entry-group');
                        if (document.querySelectorAll('.lesson-entry-group').length > 1) { // Son kalan bloğu silme
                            entryToRemove.remove();
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Uyarı!',
                                text: 'En az bir ders zamanı girişi olmalıdır.',
                                confirmButtonText: 'Tamam'
                            });
                        }
                    }
                });

                // Dersleri Kaydet butonu
                document.getElementById('saveLessons').addEventListener('click', async function() {
                    const lessonEntries = [];
                    const lessonEntryElements = document.querySelectorAll('.lesson-entry-group');
                    let hasError = false;

                    for (const entryElement of lessonEntryElements) {
                        const lessonDate = entryElement.querySelector('.lesson-date').value;
                        const startTime = entryElement.querySelector('.time-start').value;
                        const endTime = entryElement.querySelector('.time-end').value;

                        if (!lessonDate || !startTime || !endTime) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Hata!',
                                text: 'Lütfen tüm ders giriş alanlarını doldurun.',
                                confirmButtonText: 'Tamam'
                            });
                            hasError = true;
                            break;
                        }

                        if (startTime >= endTime) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Hata!',
                                text: `${lessonDate} tarihindeki ders için başlangıç saati (${startTime}), bitiş saatinden (${endTime}) önce olmalıdır!`,
                                confirmButtonText: 'Tamam'
                            });
                            hasError = true;
                            break;
                        }

                        lessonEntries.push({
                            date: lessonDate,
                            start_time: startTime,
                            end_time: endTime
                        });
                    }

                    if (hasError) {
                        return;
                    }

                    if (lessonEntries.length === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Uyarı!',
                            text: 'Lütfen kaydetmek için en az bir ders zamanı girin.',
                            confirmButtonText: 'Tamam'
                        });
                        return;
                    }

                    try {
                        const response = await fetch('includes/ajax.php?service=teacherTimeSettings&id=<?= $_GET['id'] ?>', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(lessonEntries)
                        });

                        if (!response.ok) {
                            const errorData = await response.json();
                            throw new Error(errorData.message || 'Sunucu hatası');
                        }

                        const data = await response.json();
                        console.log('Dersler başarıyla kaydedildi:', data);
                        Swal.fire({
                            icon: 'success',
                            title: 'Başarılı!',
                            text: 'Dersler başarıyla kaydedildi!',
                            confirmButtonText: 'Tamam'
                        }).then((result) => { // SweetAlert kapatıldıktan sonra bu fonksiyon çalışır
                            if (result.isConfirmed) { // Kullanıcı "Tamam" butonuna bastıysa
                                location.reload(); // Sayfayı yenile
                            }
                        });

                     

                        // Formu temizle ve ilk bloğu bırak
                        const firstEntry = document.querySelector('.lesson-entry-group');
                        document.querySelectorAll('.lesson-entry-group:not([data-lesson-id="' + firstEntry.getAttribute('data-lesson-id') + '"])').forEach(el => el.remove());
                        firstEntry.querySelector('.lesson-date').value = '';
                        firstEntry.querySelector('.time-start').value = '';
                        firstEntry.querySelector('.time-end').value = '';
                        initializeDatepickers(); // İlk bloğun datepicker'ını yeniden başlat

                    } catch (error) {
                        console.error('Dersler kaydedilirken bir hata oluştu:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Hata!',
                            text: 'Dersler kaydedilirken bir hata oluştu. Lütfen tekrar deneyin. Detaylar için konsolu kontrol edin.',
                            confirmButtonText: 'Tamam'
                        });
                    }
                });

                // Eklenen dersi listeden sil butonu (arayüzden kaldırma ve veritabanından silme)
                document.addEventListener('click', async function(event) {
                    if (event.target.classList.contains('remove-display-lesson')) {
                        const lessonIdToRemove = event.target.dataset.lessonId; // Lesson ID'yi al

                        if (!lessonIdToRemove) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Hata!',
                                text: 'Silinecek ders ID\'si bulunamadı.',
                                confirmButtonText: 'Tamam'
                            });
                            return;
                        }

                        Swal.fire({
                            title: 'Emin misiniz?',
                            text: "Bu dersi silmek istediğinizden emin misiniz?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Evet, sil!',
                            cancelButtonText: 'İptal'
                        }).then(async (result) => {
                            if (result.isConfirmed) {
                                try {
                                    // Ders silme API endpoint'i
                                    const response = await fetch(`includes/ajax.php?service=deleteTeacherTimeSetting&id=${lessonIdToRemove}&teacher_id=<?= $_GET['id'] ?>`, { // Güvenlik için teacher_id de gönderiyoruz
                                        method: 'DELETE',
                                        headers: {
                                            'Content-Type': 'application/json'
                                        }
                                    });

                                    if (!response.ok) {
                                        const errorData = await response.json();
                                        throw new Error(errorData.message || 'Ders silinirken sunucu hatası.');
                                    }

                                    const data = await response.json();
                                    if (data.status === 'success') {
                                        Swal.fire(
                                            'Silindi!',
                                            'Ders başarıyla silindi.',
                                            'success'
                                        );
                                        event.target.closest('.lesson-item').remove();
                                        if (document.querySelectorAll('#lessonsList .lesson-item').length === 0) {
                                            document.getElementById('noLessonsMessage').style.display = 'block';
                                        }
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Hata!',
                                            text: data.message || 'Ders silinirken bir hata oluştu.',
                                            confirmButtonText: 'Tamam'
                                        });
                                    }
                                } catch (error) {
                                    console.error('Ders silinirken bir hata oluştu:', error);
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Hata!',
                                        text: 'Ders silinirken bir hata oluştu. Lütfen tekrar deneyin. Detaylar için konsolu kontrol edin.',
                                        confirmButtonText: 'Tamam'
                                    });
                                }
                            }
                        });
                    }
                });
            });
        </script>

    </body>

</html>
<?php } else {
    header("location: index");
} ?>