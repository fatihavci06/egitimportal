<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 2)) {
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";

    include_once "views/pages-head.php";
    $class = new Classes();
    $data = $class->getExtraPackageList();
     $remaining= $class->privateLessonRemainingLimit($_SESSION['id']);
 if ($remaining == 0) {
    header("Location: ek-paket-satin-al.php");
    exit();
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
                                <div id="kt_app_content_container" class="app-container container-fluid" style="margin-top: -20px;">
                                    <div class="card">
                                        <header class="container-fluid bg-custom-light py-3 d-flex justify-content-between align-items-center
                                             border-top border-bottom border-custom-red" style="border-width: 5px !important;height:85px;">

                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-danger me-3 shadow icon-circle-lg d-flex justify-content-center align-items-center"
                                                    style="width: 65px; height: 65x">
                                                    <i class="fas fa-bullseye fa-2x text-white"></i>
                                                </div>

                                                <h1 class="fs-3 fw-bold text-dark mb-0">Özel Ders Talep Formu</h1>
                                            </div>

                                        </header>
                                       
                                        <div class="card-body pt-0 mt-2">
                                            <div class="container">
                                               
                                                <form id="privateLessonForm">
                                                    <div class="form-group mb-5">
                                                        <label for="class_id" class="form-label">Sınıf Seçiniz:</label>
                                                        <select id="class_id" name="class_id" class="form-select form-select-solid">
                                                            <option value="">-- Sınıf Yükleniyor... --</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group mb-5">
                                                        <label for="lesson_id" class="form-label">Ders Seçiniz: <span class="text-danger">*</span></label>
                                                        <select id="lesson_id" name="lesson_id" class="form-select form-select-solid" disabled required>
                                                            <option value="">-- Lütfen Önce Sınıf Seçiniz --</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group mb-5">
                                                        <label for="unit_id" class="form-label">Ünite Seçiniz:</label>
                                                        <select id="unit_id" name="unit_id" class="form-select form-select-solid" disabled>
                                                            <option value="">-- Lütfen Önce Ders Seçiniz --</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group mb-5">
                                                        <label for="topic_id" class="form-label">Konu Seçiniz:</label>
                                                        <select id="topic_id" name="topic_id" class="form-select form-select-solid" disabled>
                                                            <option value="">-- Lütfen Önce Ünite Seçiniz --</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group mb-5">
                                                        <label for="subtopic_id" class="form-label">Alt Konu Seçiniz:</label>
                                                        <select id="subtopic_id" name="subtopic_id" class="form-select form-select-solid" disabled>
                                                            <option value="">-- Lütfen Önce Konu Seçiniz --</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group mb-5">
                                                        <label for="time_slot" class="form-label">Uygun Zaman Aralığı: <span class="text-danger">*</span></label>
                                                        <textarea id="time_slot" name="time_slot" rows="4" class="form-control form-control-solid" placeholder="Örn: Pazartesi 14:00-16:00, Salı 10:00-12:00" required></textarea>
                                                    </div>

                                                    <button type="submit" class="btn btn-primary w-100">Talep Gönder</button>
                                                </form>
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

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            $(document).ready(function() {

                // Reset Fonksiyonu - Bir üst seviye değiştiğinde alt seviyeleri sıfırlar
                function resetSelect(selectElement, defaultText) {
                    selectElement.empty().append($('<option>', {
                        value: '',
                        text: defaultText
                    })).prop('disabled', true);
                }

                // Seçenekleri AJAX ile yükleyen genel fonksiyon
                function loadOptions(selectElement, serviceName, params = {}, defaultText) {
                    resetSelect(selectElement, defaultText); // Her yüklemeden önce select'i sıfırla

                    const queryString = Object.keys(params)
                        .map(key => `${key}=${params[key]}`)
                        .join('&');

                    let url = `includes/ajax.php?service=${serviceName}&${queryString}`;

                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        beforeSend: function() {
                            selectElement.append($('<option>', {
                                value: '',
                                text: 'Yükleniyor...',
                                disabled: true
                            }));
                        },
                        success: function(response) {
                            selectElement.empty().append($('<option>', {
                                value: '',
                                text: defaultText
                            }));

                            if (response.status === 'success' && response.data && response.data.length > 0) {
                                $.each(response.data, function(index, item) {
                                    selectElement.append($('<option>', {
                                        value: item.id,
                                        text: item.name
                                    }));
                                });
                                selectElement.prop('disabled', false);
                            } else {
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Bilgi',
                                    text: `${defaultText.replace('-- ', '').replace(' Seçiniz --', '')} bulunamadı.`,
                                    confirmButtonText: 'Tamam'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(`Error loading ${serviceName}:`, error);
                            selectElement.empty().append($('<option>', {
                                value: '',
                                text: defaultText
                            })).prop('disabled', true);
                            Swal.fire({
                                icon: 'error',
                                title: 'Hata!',
                                text: `${defaultText.replace('-- ', '').replace(' Seçiniz --', '')} yüklenirken bir sorun oluştu. Lütfen tekrar deneyin.`,
                                confirmButtonText: 'Tamam'
                            });
                        }
                    });
                }

                // Sayfa yüklendiğinde ilk olarak sınıfları yükle
                loadOptions($('#class_id'), 'getClasses', {}, '-- Sınıf Seçiniz --');

                // Sınıf seçimi değiştiğinde dersleri yükle
                $('#class_id').change(function() {
                    const selectedClassId = $(this).val();
                    resetSelect($('#lesson_id'), '-- Lütfen Önce Sınıf Seçiniz --');
                    resetSelect($('#unit_id'), '-- Lütfen Önce Ders Seçiniz --');
                    resetSelect($('#topic_id'), '-- Lütfen Önce Ünite Seçiniz --');
                    resetSelect($('#subtopic_id'), '-- Lütfen Önce Konu Seçiniz --');

                    if (selectedClassId) {
                        loadOptions($('#lesson_id'), 'getLessonList', {
                            class_id: selectedClassId
                        }, '-- Ders Seçiniz --');
                    }
                });

                // Ders seçimi değiştiğinde üniteleri yükle
                $('#lesson_id').change(function() {
                    const selectedClassId = $('#class_id').val();
                    const selectedLessonId = $(this).val();
                    resetSelect($('#unit_id'), '-- Lütfen Önce Ders Seçiniz --');
                    resetSelect($('#topic_id'), '-- Lütfen Önce Ünite Seçiniz --');
                    resetSelect($('#subtopic_id'), '-- Lütfen Önce Konu Seçiniz --');

                    if (selectedLessonId && selectedClassId) {
                        loadOptions($('#unit_id'), 'getUnits', {
                            class_id: selectedClassId,
                            lesson_id: selectedLessonId
                        }, '-- Ünite Seçiniz --');
                    }
                });

                // Ünite seçimi değiştiğinde konuları yükle
                $('#unit_id').change(function() {
                    const selectedClassId = $('#class_id').val();
                    const selectedLessonId = $('#lesson_id').val();
                    const selectedUnitId = $(this).val();
                    resetSelect($('#topic_id'), '-- Lütfen Önce Ünite Seçiniz --');
                    resetSelect($('#subtopic_id'), '-- Lütfen Önce Konu Seçiniz --');

                    if (selectedUnitId && selectedLessonId && selectedClassId) {
                        loadOptions($('#topic_id'), 'getTopics', {
                            class_id: selectedClassId,
                            lesson_id: selectedLessonId,
                            unit_id: selectedUnitId
                        }, '-- Konu Seçiniz --');
                    }
                });

                // Konu seçimi değiştiğinde alt konuları yükle
                $('#topic_id').change(function() {
                    const selectedClassId = $('#class_id').val();
                    const selectedLessonId = $('#lesson_id').val();
                    const selectedUnitId = $('#unit_id').val();
                    const selectedTopicId = $(this).val();
                    resetSelect($('#subtopic_id'), '-- Lütfen Önce Konu Seçiniz --');

                    if (selectedTopicId && selectedUnitId && selectedLessonId && selectedClassId) {
                        loadOptions($('#subtopic_id'), 'getSubtopics', {
                            class_id: selectedClassId,
                            lesson_id: selectedLessonId,
                            unit_id: selectedUnitId,
                            topic_id: selectedTopicId
                        }, '-- Alt Konu Seçiniz --');
                    }
                });

                // Form gönderildiğinde
                $('#privateLessonForm').submit(function(event) {
                    event.preventDefault(); // Sayfanın yeniden yüklenmesini engelle

                    const formData = {
                        class_id: $('#class_id').val(),
                        lesson_id: $('#lesson_id').val(),
                        unit_id: $('#unit_id').val(),
                        topic_id: $('#topic_id').val(),
                        subtopic_id: $('#subtopic_id').val(),
                        time_slot: $('#time_slot').val()
                    };

                    // Sadece lesson_id ve time_slot'un dolu olup olmadığını kontrol et
                    if (!formData.lesson_id || !formData.time_slot.trim()) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Eksik Bilgi!',
                            text: 'Lütfen zorunlu alanları (Ders Seçiniz ve Uygun Zaman Aralığı) doldurunuz.',
                            confirmButtonText: 'Tamam'
                        });
                        return;
                    }

                    // Form verilerini sunucuya AJAX ile gönderme (örnek)
                    $.ajax({
                        url: 'includes/ajax.php?service=submitPrivateLessonRequest', // Bu endpoint'i kendiniz oluşturmalısınız
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            console.log('Sunucu yanıtı:', response);
                            if (response.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Başarılı!',
                                    text: response.message || 'Ders talebiniz başarıyla gönderildi!',
                                    confirmButtonText: 'Tamam'
                                }).then(() => {
                                    $('#privateLessonForm')[0].reset(); // Formu sıfırla
                                    // Tüm select'leri başlangıç durumuna getir
                                    resetSelect($('#lesson_id'), '-- Lütfen Önce Sınıf Seçiniz --');
                                    resetSelect($('#unit_id'), '-- Lütfen Önce Ders Seçiniz --');
                                    resetSelect($('#topic_id'), '-- Lütfen Önce Ünite Seçiniz --');
                                    resetSelect($('#subtopic_id'), '-- Lütfen Önce Konu Seçiniz --');
                                    loadOptions($('#class_id'), 'getClasses', {}, '-- Sınıf Seçiniz --'); // Sınıfları tekrar yükle
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Hata!',
                                    text: response.message || 'Ders talebi gönderilirken bir hata oluştu. Lütfen tekrar deneyin.',
                                    confirmButtonText: 'Tamam'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Hata oluştu:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Bağlantı Hatası!',
                                text: 'Sunucuya bağlanırken bir hata oluştu. Lütfen ağ bağlantınızı kontrol edin ve tekrar deneyin.',
                                confirmButtonText: 'Tamam'
                            });
                        }
                    });
                });
            });
        </script>
    </body>
</html>
<?php } else {
    header("location: index");
}
?>