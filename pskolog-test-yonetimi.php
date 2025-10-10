<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 20001)) {
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";
    require_once "classes/student.classes.php";

    // Test sınıfı artık kullanılmadığı için kaldırıldı.

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
                                    <div class="card-body">

                                        <!-- Psikolojik Test Yükleme Formu Başlangıcı -->
                                        <div class="card mb-2 mb-xl-10">
                                            <div class="card-header border-0">
                                                <div class="card-title m-0">
                                                    <h3 class="fw-bold m-0">Yeni Psikolojik Test Yükle</h3>
                                                </div>
                                            </div>

                                            <form id="test_upload_form" class="form">
                                                <div class="card-body border-top p-6">

                                                    <div class="mb-5 fv-row">
                                                        <label for="test_name" class="form-label required fw-semibold fs-6">Test Adı</label>
                                                        <input type="text" id="test_name" name="test_name" class="form-control form-control-solid"  required>
                                                    </div>

                                                    <div class="mb-5 fv-row">
                                                        <label for="pdf_file" class="form-label required fw-semibold fs-6">Test PDF Dosyası</label>
                                                        <input type="file" id="pdf_file" name="pdf_file" class="form-control form-control-solid" accept=".pdf" required>
                                                        <div class="form-text mt-1">Sadece .pdf uzantılı dosyalar, maksimum 5MB.</div>
                                                    </div>

                                                    <div id="upload_message" class="mt-4" style="display: none;"></div>

                                                </div>

                                                <div class="card-footer d-flex justify-content-end py-6 px-6">
                                                    <button type="reset" class="btn btn-light btn-active-light-primary me-2">Temizle</button>
                                                    <button type="submit" id="submit_button" class="btn btn-primary" data-kt-indicator="off">
                                                        <span class="indicator-label">Yükle ve Kaydet</span>
                                                        <span class="indicator-progress">Lütfen bekleyiniz...
                                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                        </span>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- Psikolojik Test Yükleme Formu Sonu -->

                                        <!-- Test Listesi ve Yönetimi Başlangıcı (DataTable) -->
                                        <div class="card">
                                            <div class="card-header border-0 pt-6">
                                                <div class="card-title">
                                                    <h3 class="fw-bold m-0">Yüklü Testler (Düzenle & Sil)</h3>
                                                </div>
                                            </div>
                                            <div class="card-body py-4">
                                                <div class="table-responsive">
                                                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_test_table">
                                                        <thead>
                                                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                                                <th class="min-w-100px">Test Adı</th>
                                                                <th class="min-w-100px">Yükleme Tarihi</th>
                                                                <th class="text-end min-w-100px">İşlemler</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="text-gray-600 fw-semibold">
                                                            <!-- Veriler buraya AJAX ile doldurulacak -->
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Test Listesi ve Yönetimi Sonu -->

                                        <!-- Düzenleme Modalı (Modal) -->
                                        <div class="modal fade" id="edit_test_modal" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered mw-650px">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h2 class="fw-bold">Test Adını Düzenle</h2>
                                                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                                            <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                                                        </div>
                                                    </div>
                                                    <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                                                        <form id="edit_test_form" class="form">
                                                            <input type="hidden" id="edit_test_id" name="test_id">

                                                            <div class="fv-row mb-7">
                                                                <label class="required fw-semibold fs-6 mb-2">Yeni Test Adı</label>
                                                                <input type="text" id="edit_test_name" name="test_name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Test adını girin" required>
                                                            </div>

                                                            <div id="edit_message" class="alert alert-dismissible fade show" role="alert" style="display:none;"></div>

                                                            <div class="text-center pt-15">
                                                                <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">İptal</button>
                                                                <button type="submit" id="edit_submit_button" class="btn btn-primary" data-kt-indicator="off">
                                                                    <span class="indicator-label">Kaydet</span>
                                                                    <span class="indicator-progress">Lütfen bekleyiniz... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Düzenleme Modalı Sonu -->

                                        <!-- AJAX JavaScript Kodu Başlangıcı -->
                                        <script>
                                            // DataTable (Liste) değişkeni
                                            let testDataTable;

                                            // Yardımcı fonksiyon: Mesaj gösterme
                                            function showMessage(elementId, status, message) {
                                                const messageBox = document.getElementById(elementId);
                                                messageBox.style.display = 'block';
                                                if (status === 'success') {
                                                    messageBox.className = 'alert alert-success';
                                                    messageBox.innerHTML = `<strong>Başarılı!</strong> ${message}`;
                                                } else {
                                                    messageBox.className = 'alert alert-danger';
                                                    messageBox.innerHTML = `<strong>Hata!</strong> ${message}`;
                                                }
                                                // Eğer modal içindeyse, timeout koymayabiliriz ki kullanıcı mesajı görsün,
                                                // ancak ana ekrandaysa 5 saniye sonra gizleyelim.
                                                if (elementId === 'upload_message') {
                                                    setTimeout(() => messageBox.style.display = 'none', 5000);
                                                }
                                            }

                                            // 1. Test Verilerini Çekme ve Tabloyu Doldurma (DataTable)
                                            async function fetchTests() {
                                                try {



                                                    const response = await fetch('./includes/ajax.php?service=fetch_tests', {
                                                        method: 'GET',

                                                    });

                                                    if (!response.ok) {
                                                        throw new Error('Sunucu hatası: ' + response.statusText);
                                                    }
                                                    const data = await response.json();

                                                    if (data.status === 'success' && data.data) {
                                                        populateDataTable(data.data);
                                                    } else {
                                                        console.error("Veri çekme hatası:", data.message);
                                                        // Tabloyu boşalt
                                                        populateDataTable([]);
                                                    }

                                                } catch (error) {
                                                    console.error('AJAX Error (fetchTests):', error);
                                                    populateDataTable([]);
                                                }
                                            }

                                            // Tabloyu verilerle dolduran ve DataTables'ı başlatan fonksiyon
                                            function populateDataTable(tests) {
                                                const tableBody = document.querySelector('#kt_test_table tbody');

                                                // DataTables'ı yok et (yeniden başlatmak için). testDataTable'ın global bir değişken olduğunu varsayıyoruz.
                                                if (typeof testDataTable !== 'undefined' && testDataTable && typeof testDataTable.destroy === 'function') {
                                                    testDataTable.destroy();
                                                }

                                                // Gelen verinin bir dizi olduğundan emin ol
                                                if (!Array.isArray(tests)) {
                                                    console.error("populateDataTable'a geçerli bir dizi gelmedi.");
                                                    tableBody.innerHTML = '<tr><td colspan="3" class="text-center">Gösterilecek test bulunmamaktadır.</td></tr>';
                                                    return;
                                                }

                                                tableBody.innerHTML = ''; // Mevcut içeriği temizle

                                                tests.forEach(test => {
                                                    // --- HATA ÇÖZÜMÜ: VERİ GÜVENLİĞİ KONTROLLERİ ---

                                                    // test_id için güvenlik kontrolü (Varsayılan: 0)
                                                    const testId = test.id || 0;

                                                    // test_name için güvenlik kontrolü (Varsayılan: 'İsimsiz Test'). 
                                                    // Bu, .replace() çağrılmadan önce değerin bir dize olmasını garanti eder.
                                                    const testName = test.test_name || test.name || 'İsimsiz Test';

                                                    // upload_date için güvenlik kontrolü. (Varsayılan: mevcut zaman)
                                                    const uploadDateRaw = test.upload_date || test.created_at || new Date().toISOString();
                                                    const uploadDate = new Date(uploadDateRaw);

                                                    // file_path için güvenlik kontrolü (Varsayılan: boş dize)
                                                    const filePath = test.file_path || '';
                                                    const fileLink = `${filePath}`;

                                                    // Hata veren nokta: testName'i tırnak işaretlerinden kaçırarak (escape ederek)
                                                    // JavaScript fonksiyonuna güvenli bir şekilde aktarmak için kullanılır. 
                                                    // testName'in yukarıda dize (string) olarak atanması sayesinde .replace() artık hata vermez.
                                                    const safeTestNameForJS = testName.replace(/'/g, "\\'");

                                                    // ---------------------------------------------


                                                    const formattedDate = uploadDate.toLocaleDateString('tr-TR', {
                                                        year: 'numeric',
                                                        month: 'long',
                                                        day: 'numeric',
                                                        hour: '2-digit',
                                                        minute: '2-digit'
                                                    });

                                                    const row = tableBody.insertRow();
                                                    row.innerHTML = `
            <td>
                <a href="${fileLink}" target="_blank" class="text-gray-800 text-hover-primary mb-1">${testName}</a>
            </td>
            <td>${formattedDate}</td>
            <td class="text-end">
                <!-- Edit Button -->
                <button class="btn btn-icon btn-light-primary btn-sm me-1" 
                        onclick="openEditModal(${testId}, '${safeTestNameForJS}')"
                        data-bs-toggle="modal" data-bs-target="#edit_test_modal">
                    <i class="ki-duotone ki-pencil fs-2">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                </button>
                <!-- Delete Button -->
                <button class="btn btn-icon btn-light-danger btn-sm" 
                        onclick="deleteTest(${testId}, '${safeTestNameForJS}')">
                    <i class="ki-duotone ki-trash fs-2">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span>
                    </i>
                </button>
            </td>
        `;
                                                });

                                                // DataTables'ı yeniden başlat
                                                if (typeof jQuery !== 'undefined' && typeof jQuery.fn.DataTable !== 'undefined') {
                                                    testDataTable = $('#kt_test_table').DataTable({

                                                        "order": [
                                                            [1, "desc"]
                                                        ] // Yükleme tarihine (indeks 1) göre sıralama
                                                    });
                                                }
                                            }



                                            // 2. Ekleme (Upload) İşlemi
                                            async function handleUpload(e) {
                                                e.preventDefault();

                                                const form = document.getElementById('test_upload_form');
                                                const submitButton = document.getElementById('submit_button');
                                                const formData = new FormData(form);
                                                formData.append('action', 'upload_test');


                                                submitButton.setAttribute('data-kt-indicator', 'on');
                                                submitButton.disabled = true;
                                                document.getElementById('upload_message').style.display = 'none';

                                                try {
                                                    const response = await fetch('./includes/ajax.php?service=upload_test', {
                                                        method: 'POST',
                                                        body: formData

                                                    });

                                                    if (!response.ok) {
                                                        throw new Error('Sunucu hatası: ' + response.statusText);
                                                    }
                                                    const data = await response.json();

                                                    showMessage('upload_message', data.status, data.message);

                                                    if (data.status === 'success') {
                                                        form.reset();
                                                        fetchTests(); // Başarılı yüklemeden sonra listeyi güncelle
                                                    }

                                                } catch (error) {
                                                    console.error('AJAX Error (handleUpload):', error);
                                                    showMessage('upload_message', 'error', 'Sunucuyla iletişimde bir sorun oluştu.');
                                                } finally {
                                                    submitButton.setAttribute('data-kt-indicator', 'off');
                                                    submitButton.disabled = false;
                                                }
                                            }


                                            // 3. Silme İşlemi
                                            async function deleteTest(testId, testName) {
                                                // Özel bir onay/uyarı kutusu kullanılması önerilir, ancak burada temel JavaScript confirm kullanılmıştır.
                                                // NOT: alert() ve confirm() kullanmak yerine Metronic style'da bir modal kullanmanız daha iyi olur.
                                                if (!confirm(`Emin misiniz? "${testName}" adlı test kalıcı olarak silinecektir.`)) {
                                                    return;
                                                }

                                                const formData = new FormData();
                                                formData.append('test_id', testId);
                                                formData.append('action', 'delete_test');

                                                try {


                                                    const response = await fetch('./includes/ajax.php?service=delete_test', {
                                                        method: 'POST',
                                                        body: formData // Silme işlemi için testId'yi gönder

                                                    });

                                                    if (!response.ok) {
                                                        throw new Error('Sunucu hatası: ' + response.statusText);
                                                    }
                                                    const data = await response.json();

                                                    if (data.status === 'success') {
                                                        showMessage('upload_message', 'success', data.message);
                                                        fetchTests(); // Başarılı silme sonrası listeyi güncelle
                                                    } else {
                                                        showMessage('upload_message', 'error', data.message);
                                                    }

                                                } catch (error) {
                                                    console.error('AJAX Error (deleteTest):', error);
                                                    showMessage('upload_message', 'error', 'Silme işleminde bağlantı hatası.');
                                                }
                                            }

                                            // 4. Düzenleme İşlemi (Modal)
                                            function openEditModal(testId, testName) {
                                                document.getElementById('edit_test_id').value = testId;
                                                document.getElementById('edit_test_name').value = testName;
                                                // Önceki modal mesajlarını temizle
                                                const editMessage = document.getElementById('edit_message');
                                                editMessage.style.display = 'none';
                                                editMessage.innerHTML = '';
                                                editMessage.className = 'alert alert-dismissible fade show';
                                            }

                                            async function handleEdit(e) {
                                                e.preventDefault();

                                                const form = document.getElementById('edit_test_form');
                                                const submitButton = document.getElementById('edit_submit_button');

                                                // *** HATA DÜZELTİLDİ: Form verilerini al ve POST metodu ile gönder ***
                                                const formData = new FormData(form);
                                                formData.append('action', 'update_test');
                                                // -------------------------------------------------------------


                                                submitButton.setAttribute('data-kt-indicator', 'on');
                                                submitButton.disabled = true;
                                                document.getElementById('edit_message').style.display = 'none';

                                                try {
                                                    const response = await fetch('./includes/ajax.php?service=update_test', {
                                                        method: 'POST', // Metot GET yerine POST olarak değiştirildi
                                                        body: formData // FormData objesi body olarak eklendi
                                                    });

                                                    if (!response.ok) {
                                                        throw new Error('Sunucu hatası: ' + response.statusText);
                                                    }
                                                    const data = await response.json();

                                                    showMessage('edit_message', data.status, data.message);

                                                    if (data.status === 'success') {
                                                        // Modalı kapat
                                                        // NOTE: bootstrap.Modal sınıfını kullanabilmek için 'bootstrap' nesnesinin mevcut olması gerekir.
                                                        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                                                            const modal = document.getElementById('edit_test_modal');
                                                            const modalInstance = bootstrap.Modal.getInstance(modal) || new bootstrap.Modal(modal);
                                                            modalInstance.hide();
                                                        }

                                                        fetchTests(); // Başarılı güncelleme sonrası listeyi güncelle
                                                        showMessage('upload_message', 'success', data.message); // Ana mesaj kutusunda da göster
                                                    }

                                                } catch (error) {
                                                    console.error('AJAX Error (handleEdit):', error);
                                                    showMessage('edit_message', 'error', 'Sunucuyla iletişimde bir sorun oluştu.');
                                                } finally {
                                                    submitButton.setAttribute('data-kt-indicator', 'off');
                                                    submitButton.disabled = false;
                                                }
                                            }

                                            // Olay Dinleyicileri
                                            document.addEventListener('DOMContentLoaded', function() {
                                                // Yükleme formunu dinle
                                                const uploadForm = document.getElementById('test_upload_form');
                                                if (uploadForm) {
                                                    uploadForm.addEventListener('submit', handleUpload);
                                                }

                                                // Düzenleme formunu dinle
                                                const editForm = document.getElementById('edit_test_form');
                                                if (editForm) {
                                                    editForm.addEventListener('submit', handleEdit);
                                                }

                                                // Sayfa yüklendiğinde testleri getir
                                                fetchTests();
                                            });
                                        </script>
                                        <!-- AJAX JavaScript Kodu Sonu -->

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
        <script src="assets/js/custom/apps/chat/chat.js"></script>
        <script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
        <script src="assets/js/custom/utilities/modals/create-account.js"></script>
        <script src="assets/js/custom/utilities/modals/create-app.js"></script>
        <script src="assets/js/custom/utilities/modals/users-search.js"></script>
        <script>

        </script>

    </body>

    </html>
<?php } else {
    header("location: index");
}
?>