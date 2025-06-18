<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role'])) {
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";

    include_once "views/pages-head.php";
    $class = new Classes();
    // Bu sayfada package listesi kullanılmayacaksa kaldırılabilir.
    // $data = $class->getExtraPackageList();

    // Yönetici rolü kontrolü ve user_id'yi JavaScript'e aktarmak için
    $currentUserIdForJs = 'null'; // Varsayılan olarak admin veya oturum yoksa null (tüm etkinlikler)
    // Rol ID'lerinizi kontrol edin (Örn: 1 veya 10001 admin rolü ise)
    if (isset($_SESSION['role']) && ($_SESSION['role'] == 1 || $_SESSION['role'] == 10001)) {
        // Yönetici rolünde ise tüm etkinlikleri görsün
        $currentUserIdForJs = 'null';
    } elseif (isset($_SESSION['user_id'])) {
        // Normal kullanıcı ise kendi ID'si ile filtrele
        $currentUserIdForJs = (int)$_SESSION['user_id'];
    }
?>
    <head>
        <!-- Diğer meta etiketleri ve CSS bağlantıları -->
        <?php include_once "views/pages-head.php"; ?>

        <title>Takvim Uygulaması</title>

        <!-- FullCalendar CSS CDN -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css">
        <style>
            /* Takvim için özel stil */
            #calendar {
                max-width: 1100px;
                margin: 0 auto;
                height: 700px; /* Takvimin görünür yüksekliği */
            }
            .fc .fc-button { /* FullCalendar butonları için tema stilinizle uyum */
                background-color: #007bff; /* Primary renk */
                border-color: #007bff;
                color: #fff;
            }
            .fc .fc-button:hover {
                background-color: #0056b3;
                border-color: #0056b3;
            }
            .fc .fc-button-active {
                background-color: #0056b3;
                border-color: #0056b3;
            }
            .fc-event {
                cursor: pointer;
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
                                                <h3 class="fw-bold m-0">Etkinlik Takvimi</h3>
                                            </div>
                                            <!-- Buraya ek araç çubukları eklenebilir -->
                                        </div>
                                        <div class="card-body pt-0">
                                            <div id="calendar"></div>
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
            // PHP'den gelen kullanıcı ID'sini JavaScript'e aktar
            const CURRENT_USER_ID = <?php echo $currentUserIdForJs; ?>; 
        </script>
        <script src="assets/plugins/global/plugins.bundle.js"></script>
        <script src="assets/js/scripts.bundle.js"></script>
        <script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
        <script src="assets/js/widgets.bundle.js"></script>
        <script src="assets/js/custom/widgets.js"></script>

        <!-- SweetAlert2 CDN (Zaten var, sadece emin olmak için) -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- FullCalendar JavaScript CDN (En alta yerleştirilmesi tavsiye edilir) -->
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
        <!-- FullCalendar Türkçe Dil Paketi -->
        <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core/locales/tr.global.min.js"></script>

        <script>
            // FullCalendar için DOM içeriği yüklendiğinde çalıştır
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth', // Varsayılan görünüm: Ay görünümü
                    locale: 'tr', // Türkçe dil desteği
                    editable: false, // Olayları sürükle-bırak ile düzenlemeyi kapat
                    headerToolbar: {
                        left: 'prev,next today', // Sol tarafta önceki/sonraki ay/hafta/gün butonları ve bugün butonu
                        center: 'title', // Ortada takvim başlığı
                        right: 'dayGridMonth,timeGridWeek,timeGridDay' // Sağ tarafta görünüm değiştirme butonları
                    },
                    // Etkinlikleri AJAX ile çek
                    events: {
                        url: 'includes/ajax.php?service=getCalendarEvents', // Takvim etkinliklerini çekecek AJAX endpoint
                        method: 'POST', // POST metodu kullanılıyor
                        extraParams: function() {
                            // CURRENT_USER_ID, PHP'den gelen global JavaScript değişkenidir.
                            // Eğer yöneticiyse null (tüm etkinlikler), değilse kullanıcının kendi ID'si gönderilir.
                            return {
                                user_id: CURRENT_USER_ID 
                            };
                        },
                        failure: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Hata!',
                                text: 'Takvim etkinlikleri yüklenirken bir hata oluştu!',
                                confirmButtonText: 'Tamam'
                            });
                        }
                    },
                    eventClick: function(info) {
                        // Bir etkinliğe tıklandığında SweetAlert2 ile detayları göster
                        let eventType = info.event.extendedProps.type || 'Bilinmiyor';
                        let eventDescription = info.event.extendedProps.description || 'Açıklama yok.';
                        let eventTeacher = info.event.extendedProps.teacherName || 'Atanmamış';
                        let eventStudent = info.event.extendedProps.studentName || 'Bilinmiyor';

                        Swal.fire({
                            title: info.event.title,
                            html: `
                                <p><strong>Türü:</strong> ${eventType}</p>
                                <p><strong>Açıklama:</strong> ${eventDescription}</p>
                                
                                <p><strong>Başlangıç:</strong> ${info.event.start.toLocaleString('tr-TR', { dateStyle: 'medium', timeStyle: 'short' })}</p>
                                ${info.event.end ? `<p><strong>Bitiş:</strong> ${info.event.end.toLocaleString('tr-TR', { dateStyle: 'medium', timeStyle: 'short' })}</p>` : ''}
                            `,
                            icon: 'info',
                            confirmButtonText: 'Tamam',
                            width: 600, // Pop-up genişliğini ayarlayabilirsiniz
                        });
                    },
                    // Ek ayarlar ve eklentiler FullCalendar dökümantasyonundan incelenebilir
                });
                calendar.render(); // Takvimi sayfaya render et
            });
            // jQuery hazır olduğunda çalışacak diğer kodlarınız (Eğer varsa)
            $(document).ready(function() {
                // Diğer jQuery kodlarınız buraya
            });
        </script>
    </body>
</html>
<?php } else {
    header("location: index"); // Oturum yoksa ana sayfaya yönlendir
}
?>