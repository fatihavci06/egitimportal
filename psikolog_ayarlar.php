<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);

// ==========================================================
// BAŞLANGIÇ: VERİ ÇEKME VE HAZIRLAMA BLOĞU
// ==========================================================
$workingData = [];
$defaultDuration = 30; // Varsayılan süre

if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 20001)) {
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";

    // Dbh sınıfınızın bir PDO nesnesi döndürdüğünü varsayarak bağlantı kurma

    $workingData = [];
    $defaultDuration = 30; // Varsayılan süre

    $psikologId = $_SESSION['id'] ?? null;

    if ($psikologId) {
        // Yeni sınıfın örneğini oluştur
        $calismaAyarlariObj = new Classes();

        // SQL sorgusunu sınıf metodu üzerinden çalıştır
        $results = $calismaAyarlariObj->getPsikologAyarlariDb($psikologId);

        // Veriyi formda kullanmak üzere hazırlama
        foreach ($results as $row) {
            // Safe day name (formda ve serviste kullanılan anahtar)
            $safe_day = strtolower(str_replace(['ı', 'ş', 'ü', 'ç', 'ö', 'ğ', 'İ'], ['i', 's', 'u', 'c', 'o', 'g', 'i'], $row['day']));

            $workingData[$safe_day] = [
                'is_active' => (bool)$row['is_active'],
                'start_time' => $row['start_time'] ?? '09:00',
                'end_time' => $row['end_time'] ?? '17:00',
            ];

            // Randevu süresini bir kere al
            if ($row['appointment_duration'] !== null) {
                $defaultDuration = (int)$row['appointment_duration'];
            }
        }
    }


    include_once "views/pages-head.php";
?>

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


                                    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                                        <div class="col-xl-12">
                                            <div class="card shadow-sm">
                                                <div class="card-header border-0 pt-6">
                                                    <div class="card-title">
                                                        <h2>Psikolog Çalışma Ayarları</h2>
                                                    </div>
                                                </div>
                                                <div class="card-body pt-0">
                                                    <form id="psikolog_ayarlar_form" class="form">
                                                        <h3>1. Çalışma Günleri ve Saatleri</h3>
                                                        <p class="text-muted">Çalıştığınız günleri, aktiflik durumunu ve o güne ait başlangıç/bitiş saatlerini belirleyin.</p>
                                                        <div class="table-responsive">
                                                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                                                <thead>
                                                                    <tr class="fw-bold fs-6 text-gray-800">
                                                                        <th>Gün</th>
                                                                        <th class="text-center">Aktif</th>
                                                                        <th>Çalışma Saatleri</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="gun_ayarlari_body">
                                                                    <?php
                                                                    $gunler = ['Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi', 'Pazar'];
                                                                    foreach ($gunler as $gun) {
                                                                        // Veritabanı ve POST için küçük harf ve Türkçe karakterden arındırılmış isim
                                                                        $safe_gun = strtolower(str_replace(['ı', 'ş', 'ü', 'ç', 'ö', 'ğ'], ['i', 's', 'u', 'c', 'o', 'g'], $gun));

                                                                        // Veritabanı verisini kontrol et veya varsayılan değerleri kullan
                                                                        $data = $workingData[$safe_gun] ?? [
                                                                            'is_active' => true, // Varsayılan olarak aktif
                                                                            'start_time' => '09:00',
                                                                            'end_time' => '17:00'
                                                                        ];

                                                                        $isChecked = $data['is_active'] ? 'checked' : '';
                                                                        $isDisabled = $data['is_active'] ? '' : 'disabled';
                                                                        $isRequired = $data['is_active'] ? 'required' : '';
                                                                        $startTimeValue = substr($data['start_time'], 0, 5); // TIME formatından hh:mm al
                                                                        $endTimeValue = substr($data['end_time'], 0, 5);     // TIME formatından hh:mm al

                                                                        echo '<tr data-day="' . $gun . '">
                                                                            <td>' . $gun . '</td>
                                                                            <td class="text-center">
                                                                                <div class="form-check form-switch form-check-custom form-check-solid">
                                                                                    <input class="form-check-input gun-aktiflik-switch" type="checkbox" value="1" name="is_active[' . $safe_gun . ']" id="switch_' . $safe_gun . '" ' . $isChecked . '/>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <div class="d-flex flex-row gap-2 align-items-center">
                                                                                    <input type="time" class="form-control form-control-sm start_time" name="start_time[' . $safe_gun . ']" value="' . $startTimeValue . '" ' . $isRequired . ' ' . $isDisabled . '>
                                                                                    <span>-</span>
                                                                                    <input type="time" class="form-control form-control-sm end_time" name="end_time[' . $safe_gun . ']" value="' . $endTimeValue . '" ' . $isRequired . ' ' . $isDisabled . '>
                                                                                </div>
                                                                            </td>
                                                                        </tr>';
                                                                    }
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                        <hr class="my-10">

                                                        <h3>2. Randevu Periyodu</h3>
                                                        <p class="text-muted">Tek bir randevunun süresini (dakika) belirtin.</p>
                                                        <div class="fv-row mb-10 col-md-4">
                                                            <label class="form-label">Randevu Süresi (Dakika)</label>
                                                            <input type="number" name="appointment_duration" class="form-control" value="<?php echo $defaultDuration; ?>" min="15" step="5" required />
                                                            <div class="form-text">Örn: 30, 45, 60. Minumum 15 dakika olmalıdır.</div>
                                                        </div>

                                                        <hr class="my-10">

                                                        <div class="d-flex justify-content-end">
                                                            <button type="submit" id="ayarlari_kaydet_btn" class="btn btn-primary">
                                                                <span class="indicator-label">Ayarları Kaydet</span>
                                                                <span class="indicator-progress">Lütfen Bekleyin...
                                                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                                </span>
                                                            </button>
                                                        </div>
                                                    </form>
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

        <script src="assets/js/widgets.bundle.js"></script>
        <script src="assets/js/custom/widgets.js"></script>

        <script>
            // Sayfa yüklendiğinde çalışacak JavaScript/jQuery kodu
            $(document).ready(function() {

                // Aktiflik switch'ine göre saat alanlarını etkinleştirme/devre dışı bırakma
                $('.gun-aktiflik-switch').on('change', function() {
                    var row = $(this).closest('tr');
                    var startTimeInput = row.find('.start_time');
                    var endTimeInput = row.find('.end_time');

                    if ($(this).is(':checked')) {
                        // Eğer aktifse: inputları etkinleştir ve required yap
                        startTimeInput.prop('disabled', false).prop('required', true);
                        endTimeInput.prop('disabled', false).prop('required', true);
                    } else {
                        // Eğer pasifse: inputları devre dışı bırak ve required'ı kaldır
                        startTimeInput.prop('disabled', true).prop('required', false);
                        endTimeInput.prop('disabled', true).prop('required', false);
                    }
                }).trigger('change'); // Sayfa yüklenirken başlangıç durumunu ayarla (DB'den gelen veriye göre)

                // Form gönderimi
                $('#psikolog_ayarlar_form').on('submit', function(e) {
                    e.preventDefault();

                    var form = $(this);
                    var btn = $('#ayarlari_kaydet_btn');

                    // Kaydet butonunu yükleniyor durumuna getir
                    btn.attr("data-kt-indicator", "on");
                    btn.prop('disabled', true);

                    var formData = form.serializeArray();

                    // AJAX isteği
                    $.ajax({
                        url: 'includes/ajax.php?service=psikologAyarlar',
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        success: function(response) {
                            btn.removeAttr("data-kt-indicator");
                            btn.prop('disabled', false);

                            if (response.status === 'success') {
                                // Başarılı bildirim
                                Swal.fire({
                                    text: response.message || "Çalışma ayarları başarıyla kaydedildi!",
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Tamam",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                });
                            } else {
                                // Hata bildirimi
                                Swal.fire({
                                    text: response.message || "Ayarlar kaydedilirken bir hata oluştu.",
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Tekrar Dene",
                                    customClass: {
                                        confirmButton: "btn btn-danger"
                                    }
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            btn.removeAttr("data-kt-indicator");
                            btn.prop('disabled', false);

                            // Sunucu hatası bildirimi
                            Swal.fire({
                                text: "Sunucu ile iletişimde bir sorun oluştu: " + error,
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Kapat",
                                customClass: {
                                    confirmButton: "btn btn-danger"
                                }
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