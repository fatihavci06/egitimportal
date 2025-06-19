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

    $currentUserIdForJs = 'null';
    $currentUserRoleForJs = $_SESSION['role'] ?? 'null'; 

    if (isset($_SESSION['role']) && ($_SESSION['role'] == 1 || $_SESSION['role'] == 10001)) {
        $currentUserIdForJs = 'null'; 
    } elseif (isset($_SESSION['user_id'])) {
        $currentUserIdForJs = (int)$_SESSION['user_id']; 
    }
?>

<head>
    <?php include_once "views/pages-head.php"; ?>

    <title>Takvim Uygulaması</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        /* Ana Takvim Stilleri */
        #calendar {
            max-width: 1100px;
            margin: 0 auto;
            height: 700px; 
        }
        .fc .fc-button { 
            background-color: #007bff; 
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

        /* SweetAlert2 İçerik Stilleri */
        .swal2-html-container {
            text-align: left; /* Tüm içeriği sola hizala */
            padding-bottom: 0 !important; /* Buton grubu altındaki boşluğu düzenle */
        }

        .swal-info-grid {
            display: grid;
            grid-template-columns: auto 1fr; /* Etiket ve değer için iki sütun */
            gap: 8px 15px; /* Satır ve sütunlar arası boşluk */
            margin-bottom: 20px; /* Bilgi ve butonlar arasına boşluk */
        }

        .swal-info-label {
            font-weight: bold;
            color: #555; /* Etiket rengini hafifçe gri yap */
            text-align: right; /* Etiketleri sağa hizala */
        }

        .swal-info-value {
            color: #333; /* Değer rengini belirgin yap */
        }

        .swal2-html-container .btn-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center; /* Butonları ortala */
            margin-top: 15px; /* Buton grubunun üst boşluğu */
        }
        .swal2-html-container .btn-group .btn {
            flex-grow: 1; /* Butonların esnek büyümesi */
            max-width: 200px; /* Butonların maksimum genişliği */
            font-size: 1rem; /* Buton yazı tipi boyutu */
            padding: 10px 15px; /* Buton iç boşluğu */
        }
        .swal2-html-container .no-link-message {
            margin-top: 20px;
            color: #888;
            text-align: center;
            font-style: italic;
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
        const CURRENT_USER_ID = <?php echo $currentUserIdForJs; ?>; 
        const CURRENT_USER_ROLE = <?php echo $currentUserRoleForJs; ?>; 
    </script>
    
    <script src="assets/plugins/global/plugins.bundle.js"></script>
    <script src="assets/js/scripts.bundle.js"></script>
    <script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
    <script src="assets/js/widgets.bundle.js"></script>
    <script src="assets/js/custom/widgets.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core/locales/tr.global.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'tr',
                editable: false,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: {
                    url: 'includes/ajax.php?service=getCalendarEvents',
                    method: 'POST',
                    extraParams: function() {
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
                    let eventType = info.event.extendedProps.type || 'Belirtilmedi';
                    let eventDescription = info.event.extendedProps.description || 'Açıklama mevcut değil.';
                    let organizerName = info.event.extendedProps.organizerName || 'Belirtilmemiş'; // Yeni
                    let participantName = info.event.extendedProps.participantName || 'Belirtilmemiş'; // Yeni

                    let zoomStartUrl = info.event.extendedProps.zoom_start_url || '';
                    let zoomJoinUrl = info.event.extendedProps.zoom_join_url || '';

                    // Bilgileri daha düzenli bir grid yapısında gösteren HTML
                    let htmlContent = `
                        <div class="swal-info-grid">
                            <div><span class="swal-info-label">Türü:</span></div>
                            <div><span class="swal-info-value">${eventType}</span></div>


                            <div><span class="swal-info-label">Başlangıç:</span></div>
                            <div><span class="swal-info-value">${info.event.start.toLocaleString('tr-TR', { dateStyle: 'medium', timeStyle: 'short' })}</span></div>
                            
                            ${info.event.end ? `
                                <div><span class="swal-info-label">Bitiş:</span></div>
                                <div><span class="swal-info-value">${info.event.end.toLocaleString('tr-TR', { dateStyle: 'medium', timeStyle: 'short' })}</span></div>
                            ` : ''}

                            <div><span class="swal-info-label">Düzenleyen:</span></div>
                            <div><span class="swal-info-value">${organizerName}</span></div>
                            
                            <div><span class="swal-info-label">Katılımcı:</span></div>
                            <div><span class="swal-info-value">${participantName}</span></div>
                        </div>
                    `;

                    let buttonsHtml = `<div class="btn-group">`;

                    // "Toplantıyı Başlat" butonu rol 2 olmayanlar ve URL varsa görünür
                    if (CURRENT_USER_ROLE !== 2 && zoomStartUrl) { 
                        buttonsHtml += `
                            <a href="${zoomStartUrl}" target="_blank" class="btn btn-primary btn-sm">
                                <i class="fa fa-video me-2"></i> Toplantıyı Başlat
                            </a>
                        `;
                    }

                    // "Toplantıya Katıl" butonu her zaman (rol 2 dahil) ve URL varsa görünür
                    if (zoomJoinUrl) { 
                        buttonsHtml += `
                            <a href="${zoomJoinUrl}" target="_blank" class="btn btn-success btn-sm">
                                <i class="fa fa-link me-2"></i> Toplantıya Katıl
                            </a>
                        `;
                    }
                    
                    buttonsHtml += `</div>`;

                    if (zoomStartUrl || zoomJoinUrl) {
                        htmlContent += buttonsHtml;
                    } else {
                        htmlContent += `<p class="no-link-message">Toplantı linki bulunamadı.</p>`;
                    }

                    Swal.fire({
                        title: info.event.title,
                        html: htmlContent,
                        icon: 'info',
                        confirmButtonText: 'Tamam',
                        width: 650, 
                        customClass: {
                            htmlContainer: 'swal2-html-container',
                            popup: 'swal-popup',
                            title: 'swal-title',
                            confirmButton: 'swal-confirm-button',
                        }
                    });
                },
            });
            calendar.render();
        });

        $(document).ready(function() {});
    </script>
</body>
</html>
<?php 
} else {
    header("location: index");
    exit();
}
?>