<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 10001 or $_SESSION['role'] == 10002 or $_SESSION['role'] == 10005)) {
    // Veritabanı bağlantısı ve Sınıf yüklemeleri
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";
    require_once "classes/student.classes.php";

    // DB Bağlantısı (PDO'nun burada tanımlı olması gerekir)
    // $pdo = DBH::connect(); // DBH sınıfınızın bağlantı metodunu kullanın

    $test = new Classes();
    $studentInfo = new Student();

    // Test ID'si alınıyor
    $testId = $_GET['id'] ?? null;

    // Test detayları alınıyor
    $testDetail = $testId ? $test->getPskTestById($testId) : null;

    // Gelen veriyi güvenli bir şekilde değişkenlere ata
    $testName = htmlspecialchars($testDetail['name'] ?? 'Bilinmeyen Test');
    // Dosya yolundaki '../' kısmını temizleyerek kullanmalıyız. 
    // Ancak indirme butonu doğrudan dosya yoluna gideceği için, burada tam yolu tutalım.
    $filePath = htmlspecialchars($testDetail['file_path'] ?? '');
    $downloadLink = !empty($filePath) ? $filePath : '#';

    // Oturum Rolüne göre öğrenci bilgileri alınıyor
    if ($_SESSION['role'] == 10005) {
        $getPreSchoolStudent = $studentInfo->getPreSchoolStudentsInfoForParents($_SESSION['id']);
        $class_idsi = $getPreSchoolStudent[0]['class_id'] ?? null;
        $studentidsi = $getPreSchoolStudent[0]['id'] ?? null; // Parent rolü için öğrencinin ID'si
    } else {
        $class_idsi = $_SESSION['class_id'] ?? null;
        $studentidsi = $_SESSION['id'] ?? null; // Öğrenci rolü için kendi ID'si
    }

    include_once "views/pages-head.php";
?>


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
                                        <div class="row container-fluid" style="margin-top:-25px; padding:0">
                                            <header class="container-fluid bg-custom-light py-3 d-flex justify-content-between align-items-center
                                                         border-top border-bottom border-custom-red mb-2" style="border-width: 5px !important; height:85px;margin-bottom: 26px !important;background-color: #e6e6fa !important;">

                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-danger me-3 shadow icon-circle-lg d-flex justify-content-center align-items-center"
                                                        style="width: 65px; height: 65px;">
                                                        <i class="fas fa-bullseye fa-2x text-white"></i>
                                                    </div>

                                                    <h1 class="fs-3 fw-bold text-dark mb-0"><?php echo $testName; ?></h1>
                                                </div>
                                            </header>
                                        </div>

                                        <div class="container py-3">
                                            <div class="row justify-content-center">
                                                <div class="col-lg-8">

                                                    <p class="text-center text-muted mb-5">
                                                        Lütfen testi indirip uygulayın. Ardından sonuç dosyasını yükleyerek değerlendirmeye gönderin. İlk indirme hakkınız ücretsizdir.
                                                    </p>

                                                    <div class="card shadow-lg mb-5 border-primary border-3 border-bottom-0 border-end-0 border-top-0 transition-300 transform-scale-hover">
                                                        <div class="card-body p-6">
                                                            <div class="d-flex align-items-center">
                                                                <span class="symbol symbol-50px me-4">
                                                                    <span class="symbol-label bg-light-primary">
                                                                        <i class="ki-duotone ki-down fs-2x text-primary">
                                                                            <span class="path1"></span>
                                                                            <span class="path2"></span>
                                                                        </i>
                                                                    </span>
                                                                </span>
                                                                <div>
                                                                    <h3 class="fw-bold mb-1">1. Test Dosyasını İndir/Aç</h3>
                                                                    <p class="text-muted mb-0">Testi indirmek için butona tıklayın. İkinci indirme paketinizi kullanır.</p>
                                                                </div>
                                                            </div>

                                                            <div class="mt-4 text-center">
                                                                <?php if (!empty($filePath) && $downloadLink !== '#'): ?>
                                                                    <button id="downloadButton"
                                                                        class="btn btn-primary fw-bolder py-3 px-8 transition-300"
                                                                        data-test-id="<?php echo htmlspecialchars($testId); ?>"
                                                                        data-student-id="<?php echo htmlspecialchars($studentidsi); ?>"
                                                                        data-file-path="<?php echo htmlspecialchars($downloadLink); ?>"
                                                                        data-kt-indicator="off">
                                                                        <span class="indicator-label"><i class="ki-duotone ki-file-pdf me-2"></i> Testi İndir/Görüntüle</span>
                                                                        <span class="indicator-progress">Kontrol ediliyor...
                                                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                                        </span>
                                                                    </button>
                                                                    <div id="downloadMessage" class="mt-3 text-center"></div>
                                                                <?php else: ?>
                                                                    <div class="alert alert-warning mb-0">Test dosyası bulunamadı.</div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="card shadow-lg border-success border-3 border-bottom-0 border-end-0 border-top-0">
                                                        <div class="card-body p-6">
                                                            <div class="d-flex align-items-center">
                                                                <span class="symbol symbol-50px me-4">
                                                                    <span class="symbol-label bg-light-success">
                                                                        <i class="ki-duotone ki-cloud-up fs-2x text-success">
                                                                            <span class="path1"></span>
                                                                            <span class="path2"></span>
                                                                        </i>
                                                                    </span>
                                                                </span>
                                                                <div>
                                                                    <h3 class="fw-bold mb-1">2. Cevap Dosyasını Yükle</h3>
                                                                    <p class="text-muted mb-0">Uyguladığınız testin sonuç dosyasını (örneğin: resim, pdf, zip) buradan yükleyin.</p>
                                                                </div>
                                                            </div>

                                                            <form id="pskTestUploadForm" class="mt-4">
                                                                <input type="hidden" name="test_id" value="<?php echo htmlspecialchars($testId); ?>">
                                                                <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($studentidsi); ?>">

                                                                <div class="mb-5">
                                                                    <label for="cevapDosyasi" class="form-label fw-bold">Yüklenecek Dosya:</label>
                                                                    <input type="file" class="form-control form-control-lg" id="cevapDosyasi" name="cevap_dosyasi" required>
                                                                    <div class="form-text">Desteklenen formatlar: PDF, JPG, PNG, DOCX, ZIP. (Maks. 10MB)</div>
                                                                </div>

                                                                <div class="text-center">
                                                                    <button type="submit" id="uploadButton" class="btn btn-success fw-bolder py-3 px-8" data-kt-indicator="off">
                                                                        <span class="indicator-label">Cevabı Yükle</span>
                                                                        <span class="indicator-progress">Lütfen bekleyin...
                                                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                                        </span>
                                                                    </button>
                                                                </div>

                                                                <div id="uploadMessage" class="mt-3 text-center"></div>
                                                            </form>

                                                        </div>
                                                    </div>
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

        <script src="assets/js/widgets.bundle.js"></script>
        <script src="assets/js/custom/widgets.js"></script>
        <script src="assets/js/custom/apps/chat/chat.js"></script>
        <script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
        <script src="assets/js/custom/utilities/modals/create-account.js"></script>
        <script src="assets/js/custom/utilities/modals/create-app.js"></script>
        <script src="assets/js/custom/utilities/modals/users-search.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('pskTestUploadForm');
                const uploadButton = document.getElementById('uploadButton');
                const uploadMessage = document.getElementById('uploadMessage');

                // YENİ İNDİRME İŞLEMİ TANIMLAMALARI
                const downloadButton = document.getElementById('downloadButton');
                const downloadMessage = document.getElementById('downloadMessage');

                // ADIM 1: TEST İNDİRME İŞLEMİ (GÜNCELLENMİŞ KISIM: Link/Lokal ayrımı eklendi)
                if (downloadButton) {
    downloadButton.addEventListener('click', function() {
        const testId = this.getAttribute('data-test-id');
        const studentId = this.getAttribute('data-student-id');
        // Backend'den gelen dosya yolunu alıyoruz
        const filePath = this.getAttribute('data-file-path');

        downloadMessage.innerHTML = ''; // Mesajı temizle

        // Buton durumunu ayarla (AJAX başlayacağı için)
        this.setAttribute('data-kt-indicator', 'on');
        this.disabled = true;

        const formData = new FormData();
        formData.append('test_id', testId);
        formData.append('student_id', studentId);
        // Local veya harici URL fark etmeksizin yolu AJAX servisine gönderiyoruz.
        formData.append('file_path', filePath); 

        // AJAX isteği ile paket/kullanım kontrolü yapılıyor
        fetch('includes/ajax.php?service=pskTestDownload', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                // Sunucu tarafından fırlatılan HTTP hatalarını yakalamak için
                return response.json().then(error => {
                    throw new Error(error.message || `Sunucu HTTP Hatası: ${response.status}`);
                });
            }
            return response.json();
        })
        .then(data => {
            downloadButton.removeAttribute('data-kt-indicator');
            downloadButton.disabled = false;

            if (data.success) {
                // Başarı mesajını göster
                downloadMessage.innerHTML = `
                    <div class="alert alert-success fw-bold">
                        ✅ ${data.message}
                        <br>
                        <a href="psikolog-randevu-talep-formu.php" class="btn btn-primary mt-2 text-white fw-bold">
                            Randevu oluşturmak için tıklayınız
                        </a>
                    </div>
                `;
                
                // İndirme/Görüntüleme işlemini başlatmak için yönlendirme
                if (data.download_link) {
                    // Bu link, includes/ajax.php'den gelen ve paket/hak kontrolünden geçmiş linktir.
                    window.open(data.download_link, '_blank');
                }

            } else {
                // Hata mesajını göster (Örn: Paketiniz bitti)
                const errorMessage = data.message || 'İndirme izni kontrol edilirken bir hata oluştu.';
                downloadMessage.innerHTML = `<div class="alert alert-danger fw-bold">❌ Hata: ${errorMessage}</div>`;
            }
        })
        .catch(error => {
            downloadButton.removeAttribute('data-kt-indicator');
            downloadButton.disabled = false;
            console.error('İndirme/Bağlantı hatası:', error);
            // Hata mesajını error.message ile göster (eğer varsa)
            downloadMessage.innerHTML = `<div class="alert alert-danger fw-bold">Bağlantı hatası oluştu. Lütfen ağ bağlantınızı kontrol edin. (${error.message || 'Bilinmeyen Hata'})</div>`;
        });
    });
}


                // ADIM 2: DOSYA YÜKLEME AJAX İŞLEMİ 
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(form);

                    uploadButton.setAttribute('data-kt-indicator', 'on');
                    uploadButton.disabled = true;
                    uploadMessage.innerHTML = '';

                    fetch('includes/ajax.php?service=pskTestUpload', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => {
                            if (response.ok) {
                                return response.json();
                            }
                            // Sunucudan gelen hata JSON'unu yakalamaya çalış
                            return response.json().then(error => {
                                throw new Error(error.message || `Sunucu HTTP Hatası: ${response.status}`);
                            });
                        })
                        .then(data => {
                            uploadButton.removeAttribute('data-kt-indicator');
                            uploadButton.disabled = false;

                            if (data.success) {
                                // BAŞARILI DURUM: Mesajı göster ve YÖNLENDİRME YAP
                                uploadMessage.innerHTML = '<div class="alert alert-success fw-bold">✅ Yükleme başarılı! Cevabınız değerlendirmeye alındı. </div>';
                                form.reset();

                                // // *** YÖNLENDİRME KISMI ***
                                // setTimeout(() => {
                                //     window.location.href = 'psikolog-randevu-talep-formu.php';
                                // }, 1500); // Kullanıcının başarı mesajını görmesi için 1.5 saniye bekle

                            } else {
                                // HATA DURUMU
                                const errorMessage = data.message || 'Dosya yüklenirken beklenen bir hata oluştu.';
                                uploadMessage.innerHTML = `<div class="alert alert-danger fw-bold">❌ Hata: ${errorMessage}</div>`;
                            }
                        })
                        .catch(error => {
                            uploadButton.removeAttribute('data-kt-indicator');
                            uploadButton.disabled = false;
                            console.error('Yükleme/Bağlantı hatası:', error);
                            uploadMessage.innerHTML = `<div class="alert alert-danger fw-bold">Bağlantı hatası oluştu. Lütfen ağ bağlantınızı kontrol edin. (${error.message})</div>`;
                        });
                });
            });
        </script>
        <style>
            .transition-300 {
                transition: all 0.3s ease-in-out;
            }

            /* Hover efekti ile kartı hafifçe büyütme (opsiyonel) */
            .transform-scale-hover:hover {
                transform: translateY(-3px);
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
            }

            /* Yükleme butonu indicator görünümleri (Metronic stili) */
            /* İndirme butonu için de geçerli olmasını sağlayalım */
            #uploadButton .indicator-progress,
            #downloadButton .indicator-progress {
                display: none;
            }

            #uploadButton[data-kt-indicator="on"] .indicator-label,
            #downloadButton[data-kt-indicator="on"] .indicator-label {
                display: none;
            }

            #uploadButton[data-kt-indicator="on"] .indicator-progress,
            #downloadButton[data-kt-indicator="on"] .indicator-progress {
                display: inline-block;
            }

            /* Sembol arka plan renkleri (tema renklerinize göre) */
            .bg-light-primary {
                background-color: #f3f6f9 !important;
            }

            .bg-light-success {
                background-color: #e8fff3 !important;
            }
        </style>
    </body>

    </html>
<?php } else {
    // Oturum veya yetki yoksa yönlendir
    header("location: index");
}
?>