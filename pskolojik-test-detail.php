<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 10001 or $_SESSION['role'] == 10002 or $_SESSION['role'] == 10005)) {
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";
    require_once "classes/student.classes.php";
    $test = new Classes();
    $studentInfo = new Student();
    
    // Test ID'si alınıyor
    $testId = $_GET['id'] ?? null;
    
    // Test detayları alınıyor
    $testDetail = $testId ? $test->getPskTestById($testId) : null;
    
    // Gelen veriyi güvenli bir şekilde değişkenlere ata
    $testName = htmlspecialchars($testDetail['name'] ?? 'Bilinmeyen Test');
    $filePath = htmlspecialchars($testDetail['file_path'] ?? '');
    $downloadLink = !empty($filePath) ? $filePath : '#';

    // Oturum Rolüne göre öğrenci bilgileri alınıyor
    if ($_SESSION['role'] == 10005) {
        $getPreSchoolStudent = $studentInfo->getPreSchoolStudentsInfoForParents($_SESSION['id']);
        $class_idsi = $getPreSchoolStudent[0]['class_id'] ?? null;
        $studentidsi = $getPreSchoolStudent[0]['id'] ?? null;
    } else {
        $class_idsi = $_SESSION['class_id'] ?? null;
        $studentidsi = $_SESSION['id'] ?? null;
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
                                        <div class="row container-fluid" style="margin-top:-25px;">
                                            <header class="container-fluid bg-custom-light py-3 d-flex justify-content-between align-items-center
                                                    border-top border-bottom border-custom-red mb-2" style="border-width: 5px !important; height:85px;margin-bottom: 26px !important;">

                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-danger me-3 shadow icon-circle-lg d-flex justify-content-center align-items-center"
                                                        style="width: 65px; height: 65px;">
                                                        <i class="fas fa-bullseye fa-2x text-white"></i>
                                                    </div>

                                                    <!-- Test Adını Dinamik Olarak Kullanıyoruz -->
                                                    <h1 class="fs-3 fw-bold text-dark mb-0"><?php echo $testName; ?></h1>
                                                </div>
                                            </header>
                                        </div>

                                        <!-- YENİ EKLENEN KARTLAR VE AJAX FORMU BURADAN BAŞLIYOR -->
                                        <div class="container py-3">
                                            <div class="row justify-content-center">
                                                <div class="col-lg-8">
                                                    
                                                    <p class="text-center text-muted mb-5">
                                                        Lütfen testi indirip uygulayın. Ardından sonuç dosyasını yükleyerek değerlendirmeye gönderin.
                                                    </p>

                                                    <!-- Adım 1: Test Dosyasını İndir Kartı -->
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
                                                                    <h3 class="fw-bold mb-1">1. Test Dosyasını İndir</h3>
                                                                    <p class="text-muted mb-0">Testi PDF formatında indirmek için butona tıklayın.</p>
                                                                </div>
                                                            </div>

                                                            <div class="mt-4 text-center">
                                                                <?php if (!empty($filePath)): ?>
                                                                    <a href="<?php echo $downloadLink; ?>"
                                                                       target="_blank"
                                                                       class="btn btn-primary fw-bolder py-3 px-8 transition-300"
                                                                       download>
                                                                        <i class="ki-duotone ki-file-pdf me-2"></i> Testi İndir (PDF)
                                                                    </a>
                                                                <?php else: ?>
                                                                    <div class="alert alert-warning mb-0">Test dosyası bulunamadı.</div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Adım 2: Cevap Dosyasını Yükle Kartı -->
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
                                                                <!-- Güvenlik ve test ID'si için gizli alan -->
                                                                <input type="hidden" name="test_id" value="<?php echo $testId; ?>">
                                                                <input type="hidden" name="student_id" value="<?php echo $studentidsi; ?>">

                                                                <!-- Dosya Yükleme Alanı -->
                                                                <div class="mb-5">
                                                                    <label for="cevapDosyasi" class="form-label fw-bold">Yüklenecek Dosya:</label>
                                                                    <input type="file" class="form-control form-control-lg" id="cevapDosyasi" name="cevap_dosyasi" required>
                                                                    <div class="form-text">Desteklenen formatlar: PDF, JPG, PNG, ZIP.</div>
                                                                </div>

                                                                <!-- Yükleme Butonu -->
                                                                <div class="text-center">
                                                                    <button type="submit" id="uploadButton" class="btn btn-success fw-bolder py-3 px-8" data-kt-indicator="off">
                                                                        <span class="indicator-label">Cevabı Yükle</span>
                                                                        <span class="indicator-progress">Lütfen bekleyin...
                                                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                                        </span>
                                                                    </button>
                                                                </div>

                                                                <!-- Sonuç Mesajı Alanı -->
                                                                <div id="uploadMessage" class="mt-3 text-center"></div>
                                                            </form>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- YENİ EKLENEN KARTLAR VE AJAX FORMU BURADA BİTİYOR -->
                                        
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
        
        <!-- CUSTOM TEST UPLOAD SCRIPT -->
        <script>
            // AJAX ile dosya yükleme
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('pskTestUploadForm');
                const uploadButton = document.getElementById('uploadButton');
                const uploadMessage = document.getElementById('uploadMessage');

                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Form verilerini al
                    const formData = new FormData(form);

                    // Buton durumunu yükleniyor olarak ayarla
                    uploadButton.setAttribute('data-kt-indicator', 'on');
                    uploadButton.disabled = true;
                    uploadMessage.innerHTML = ''; // Önceki mesajı temizle

                    fetch('includes/ajax.php?service=pskTestUpload', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        // Sunucudan gelen JSON cevabını işle
                        if (response.ok) {
                            return response.json();
                        }
                        // Başarısız HTTP durumlarını yakala
                        // 200 olmayan yanıtları da json olarak okumaya çalış
                        return response.json().then(error => { throw new Error(error.message || `Sunucu HTTP Hatası: ${response.status}`); });
                    })
                    .then(data => {
                        uploadButton.removeAttribute('data-kt-indicator');
                        uploadButton.disabled = false;

                        // Backend'den gelen cevaba göre mesajı göster
                        if (data.success) {
                            uploadMessage.innerHTML = '<div class="alert alert-success fw-bold">✅ Yükleme başarılı! Cevabınız değerlendirmeye alındı.</div>';
                            form.reset(); // Formu temizle
                        } else {
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
        <!-- CUSTOM STYLES -->
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
            #uploadButton .indicator-progress {
                display: none;
            }
            #uploadButton[data-kt-indicator="on"] .indicator-label {
                display: none;
            }
            #uploadButton[data-kt-indicator="on"] .indicator-progress {
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
        <!-- END CUSTOM STYLES & SCRIPT -->

    </body>

</html>
<?php } else {
    header("location: index");
}
?>
