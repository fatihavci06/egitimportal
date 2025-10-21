<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);

// Randevu talep etme yetkileri
$allowedRoles = [10001, 10002, 10005];

// Kontrol: Kullanıcı giriş yapmış mı ve yetkisi uygun mu?
if (isset($_SESSION['role']) && in_array($_SESSION['role'], $allowedRoles)) {
    // DBH ve gerekli sınıfları dahil et
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";
    
    // Gerekli sınıflar (Psikolog listesi çekmek için)
    // NOT: 'users' tablonuzdaki yetki kodlarına göre psikologları çekecek bir mekanizma eklemeniz gerek.
    
    // ==========================================================
    // BAŞLANGIÇ: Psikolog Listesini Çekme (Varsayım)
    // ==========================================================
    $psikologlar = [];
    // try {
        $dbh = new Dbh();
        $pdo = $dbh->connect();
        
        // Örnek Sorgu: role=1 olanları psikolog kabul edelim (Kendi yetki kodunuza göre düzenleyin)
        $stmt = $pdo->prepare("SELECT id, name, surname FROM users_lnp WHERE role = 20001 ORDER BY name ASC");
        $stmt->execute();
        $psikologlar = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // } catch (PDOException $e) {
    //     // Psikolog listesi çekilemezse boş kalır
    //     // error_log("Psikolog Listesi Hatası: " . $e->getMessage());
    // }
    // ==========================================================
    // BİTİŞ: Psikolog Listesini Çekme
    // ==========================================================

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
                                    
                                    
                                    <div class="card shadow-sm">
                                        <div class="card-header border-0 pt-6">
                                            <div class="card-title">
                                                <h2>Psikolog Randevu Talebi</h2>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0">
                                            <form id="randevu_talep_form" class="form">
                                                
                                                <div class="row mb-5">
                                                    <div class="col-md-6 fv-row mb-5">
                                                        <label class="form-label required">Psikolog </label>
                                                        <select id="psikolog_select" name="psikolog_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Bir Psikolog Seçin" required>
                                                            <option></option>
                                                            <?php foreach ($psikologlar as $psikolog): ?>
                                                                <option selected value="<?php echo $psikolog['id']; ?>">
                                                                    <?php echo htmlspecialchars($psikolog['name'] . ' ' . $psikolog['surname']); ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="col-md-6 fv-row mb-5">
                                                        <label class="form-label required">Randevu Tarihi</label>
                                                        <input type="date" id="appointment_date" name="appointment_date" class="form-control form-control-solid" min="<?php echo date('Y-m-d'); ?>" required/>
                                                    </div>
                                                </div>
                                                
                                                <div class="separator separator-dashed my-8"></div>

                                                <h3>Uygun Saat Seçimi</h3>
                                                <p class="text-muted">Seçtiğiniz tarihte uygun olan saat dilimlerini aşağıdan seçin.</p>
                                                
                                                <div id="saat_listesi_container" class="mb-5 min-h-100px border p-5 rounded">
                                                    <p class="text-gray-500">Lütfen önce bir psikolog ve tarih seçin.</p>
                                                </div>
                                                
                                                <div class="separator separator-dashed my-8"></div>
                                                
                                               
                                                <p class="text-muted">Randevunuz bu bilgilerle kaydedilecektir.</p>
                                                
                                                <div class="row mb-5">
                                                    <div class="col-md-6 fv-row mb-5">
                                                        <label class="form-label required">Ad Soyad</label>
                                                        <input type="text" name="client_name" class="form-control form-control-solid" value="<?php echo htmlspecialchars($_SESSION['name'] ?? '') . ' ' . htmlspecialchars($_SESSION['surname'] ?? ''); ?>" required readonly/>
                                                    </div>
                                                    <div class="col-md-6 fv-row mb-5">
                                                        <label class="form-label">Telefon (Opsiyonel)</label>
                                                        <input type="text" name="client_phone" class="form-control form-control-solid" placeholder="Telefon Numarası" value=""/>
                                                    </div>
                                                </div>
                                                
                                                <div class="fv-row mb-5">
                                                    <label class="form-label">Notlar (Opsiyonel)</label>
                                                    <textarea name="notes" class="form-control form-control-solid" rows="3"></textarea>
                                                </div>

                                                <hr class="my-10">
                                                
                                                <div class="d-flex justify-content-end">
                                                    <button type="submit" id="randevu_kaydet_btn" class="btn btn-primary" disabled>
                                                        <span class="indicator-label">Randevu Talep Et</span>
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
            $(document).ready(function() {
                
                // Form elementlerini tanımlama
                const psikologSelect = $('#psikolog_select');
                const dateInput = $('#appointment_date');
                const saatContainer = $('#saat_listesi_container');
                const randevuKaydetBtn = $('#randevu_kaydet_btn');

                // Seçilen Saati tutmak için gizli input (Randevu formuna eklenecek)
                let selectedStartTime = null; 
                let selectedEndTime = null;

                // --------------------------------------------------------
                // A) BOŞ SAATLERİ ÇEKME İŞLEMİ
                // --------------------------------------------------------
                const getAvailableHours = () => {
                    const psikologId = psikologSelect.val();
                    const date = dateInput.val();
                    
                    // Geçerlilik kontrolü
                    if (!psikologId || !date) {
                        saatContainer.html('<p class="text-gray-500">Lütfen önce bir psikolog ve tarih seçin.</p>');
                        randevuKaydetBtn.prop('disabled', true);
                        return;
                    }

                    saatContainer.html('<div class="text-center p-10"><span class="spinner-border text-primary"></span> Saatler Yükleniyor...</div>');
                    randevuKaydetBtn.prop('disabled', true);
                    
                    // AJAX isteği: Boş saatleri çek
                    $.ajax({
                        url: 'includes/ajax.php?service=getAvailableSlots', // Yeni servis oluşturacağız
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            psikolog_id: psikologId,
                            appointment_date: date
                        },
                        success: function(response) {
                            if (response.status === 'success' && response.slots && response.slots.length > 0) {
                                let html = '<div class="d-flex flex-wrap gap-3">';
                                response.slots.forEach(slot => {
                                    // Her saat dilimi için bir düğme oluştur
                                    html += `<button type="button" 
                                                 class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary slot-button" 
                                                 data-start-time="${slot.start}" 
                                                 data-end-time="${slot.end}">
                                                 ${slot.display}
                                             </button>`;
                                });
                                html += '</div>';
                                saatContainer.html(html);
                                
                                // Saat düğmelerine tıklama olayını bağla
                                $('.slot-button').on('click', function() {
                                    // Diğer tüm düğmelerin aktifliğini kaldır
                                    $('.slot-button').removeClass('active'); 
                                    // Tıklanan düğmeyi aktif yap
                                    $(this).addClass('active');
                                    
                                    // Seçilen saatleri güncelle
                                    selectedStartTime = $(this).data('start-time');
                                    selectedEndTime = $(this).data('end-time');
                                    
                                    // Randevu kaydetme butonunu etkinleştir
                                    randevuKaydetBtn.prop('disabled', false);
                                });

                            } else {
                                // Boş zaman dilimi yoksa
                                saatContainer.html('<div class="alert alert-danger mb-0">Seçilen gün için uygun randevu saati bulunmamaktadır veya psikolog bu gün çalışmamaktadır.</div>');
                                randevuKaydetBtn.prop('disabled', true);
                            }
                        },
                        error: function() {
                            saatContainer.html('<div class="alert alert-danger mb-0">Saatler yüklenirken bir hata oluştu. Lütfen tekrar deneyin.</div>');
                            randevuKaydetBtn.prop('disabled', true);
                        }
                    });
                };

                // Psikolog veya Tarih değiştiğinde boş saatleri çek
                psikologSelect.on('change', getAvailableHours);
                dateInput.on('change', getAvailableHours);
                
                // Form yüklendiğinde select2'yi başlat
                psikologSelect.select2();

                // --------------------------------------------------------
                // B) RANDEVU KAYIT İŞLEMİ
                // --------------------------------------------------------
                $('#randevu_talep_form').on('submit', function(e) {
                    e.preventDefault();

                    // Seçili saat var mı kontrol et
                    if (!selectedStartTime || !selectedEndTime) {
                        Swal.fire({ text: "Lütfen bir randevu saati seçin.", icon: "warning", buttonsStyling: false, confirmButtonText: "Tamam", customClass: { confirmButton: "btn btn-warning" } });
                        return;
                    }

                    const form = $(this);
                    const btn = $('#randevu_kaydet_btn');

                    btn.attr("data-kt-indicator", "on").prop('disabled', true);
                    
                    // Form verilerini al ve seçilen saatleri ekle
                    const formData = form.serializeArray();
                    formData.push({ name: 'start_time', value: selectedStartTime });
                    formData.push({ name: 'end_time', value: selectedEndTime });
                    
                    // AJAX isteği: Randevuyu kaydet
                    $.ajax({
                        url: 'includes/ajax.php?service=createAppointment', // Yeni servis oluşturacağız
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        success: function(response) {
                            btn.removeAttr("data-kt-indicator").prop('disabled', false);

                            if (response.status === 'success') {
                                Swal.fire({ text: response.message || "Randevunuz başarıyla talep edildi!", icon: "success", buttonsStyling: false, confirmButtonText: "Tamam", customClass: { confirmButton: "btn btn-primary" } });
                                // Formu temizle veya yönlendir
                                form[0].reset();
                                saatContainer.html('<p class="text-gray-500">Lütfen önce bir psikolog ve tarih seçin.</p>');
                            } else {
                                Swal.fire({ text: response.message || "Randevu kaydı sırasında bir hata oluştu.", icon: "error", buttonsStyling: false, confirmButtonText: "Tekrar Dene", customClass: { confirmButton: "btn btn-danger" } });
                            }
                        },
                        error: function() {
                            btn.removeAttr("data-kt-indicator").prop('disabled', false);
                            Swal.fire({ text: "Sunucu ile iletişimde bir sorun oluştu.", icon: "error", buttonsStyling: false, confirmButtonText: "Kapat", customClass: { confirmButton: "btn btn-danger" } });
                        }
                    });
                });
            });
        </script>
        </body>
    </html>
<?php } else {
    // Yetki veya oturum yoksa yönlendir
    header("location: index");
    exit;
}
?>