<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 10001)) {
    // Mevcut include'lar korunur
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";
    // Eğer tekerleme yönetimi için özel bir sınıfınız varsa onu da include edebilirsiniz.
    // include_once "classes/tekerleme.classes.php"; 
    $classes = new Classes();
    $classList = $classes->getMainSchoolClassesList();
    // print_r($classList); // DEBUG Çıktısı Kaldırıldı
    // die; // Program Durdurma Komutu Kaldırıldı

    // Dinamik Select Seçeneklerini Oluşturan Fonksiyon
    function generateClassOptions($classList)
    {
        $options = '';
        foreach ($classList as $class) {
            // value="10">3-4 Yaş (10) formatı için:
            // class dizisinden [id] ve [name] kullanılıyor
            $options .= '<option value="' . htmlspecialchars($class['id']) . '">' . htmlspecialchars($class['name']) . '</option>';
        }
        return $options;
    }

    $classOptionsHTML = generateClassOptions($classList);


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
                                    <div class="card card-flush">
                                        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                                            <div class="card-title">

                                            </div>
                                            <div class="card-toolbar">
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#kt_modal_add_tekerleme">
                                                    <i class="ki-duotone ki-plus fs-2"></i> Yeni Tekerleme Ekle
                                                </button>
                                            </div>
                                        </div>

                                        <div class="card-body pt-0">
                                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="tekerleme_table">
                                                <thead>
                                                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                                        <th class="min-w-200px">Açıklama (Önizleme)</th>
                                                        <th class="min-w-100px">Sınıflar</th>
                                                        <th class="min-w-100px">Durum</th>
                                                        <th class="text-end min-w-100px">İşlemler</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="fw-semibold text-gray-600" id="tekerleme_listesi">
                                                    <tr>
                                                        <td colspan="6" class="text-center">Tekerlemeler yükleniyor...</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="kt_modal_add_tekerleme" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered mw-650px">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h2 class="fw-bold">Yeni Tekerleme Ekle</h2>
                                                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                                        <i class="ki-duotone ki-cross fs-1">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>
                                                    </div>
                                                </div>
                                                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                                                    <form id="kt_modal_add_tekerleme_form" class="form" onsubmit="handleTekerlemeSave(event)">


                                                        <div class="fv-row mb-7">
                                                            <label class="required fs-6 fw-semibold mb-2">İlgili Sınıflar</label>
                                                            <select id="add_class_ids" name="class_ids[]" data-control="select2" data-placeholder="Sınıfları Seçin" class="form-select form-select-solid fw-bold" multiple>
                                                                <?php echo $classOptionsHTML; ?>
                                                            </select>
                                                        </div>

                                                        <div class="fv-row mb-7">
                                                            <label class="required fs-6 fw-semibold mb-2">Tekerleme Metni/Açıklaması</label>
                                                            <textarea id="add_description" name="description" class="form-control form-control-solid" rows="4" placeholder="Tekerleme metnini buraya girin." required></textarea>
                                                        </div>

                                                        <div class="fv-row mb-7">
                                                            <label class="required fs-6 fw-semibold mb-2">Görüntü Dosyası</label>
                                                            <input type="file" id="add_image_path" name="image_path" accept="image/*" class="form-control form-control-solid" required />
                                                        </div>

                                                        <div class="fv-row mb-7">
                                                            <label class="required fs-6 fw-semibold mb-2">Ses Dosyası</label>
                                                            <input type="file" id="add_sound_path" name="sound_path" accept="audio/*" class="form-control form-control-solid" required />
                                                        </div>

                                                        <div class="text-center pt-15">
                                                            <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">İptal</button>
                                                            <button type="submit" class="btn btn-primary" id="kt_add_tekerleme_submit">
                                                                <span class="indicator-label">Kaydet</span>
                                                                <span class="indicator-progress">Lütfen Bekleyin...
                                                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="kt_modal_edit_tekerleme" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered mw-650px">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h2 class="fw-bold">Tekerleme Düzenle</h2>
                                                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                                        <i class="ki-duotone ki-cross fs-1">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>
                                                    </div>
                                                </div>
                                                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                                                    <form id="kt_modal_edit_tekerleme_form" class="form" onsubmit="handleTekerlemeUpdate(event)">
                                                        <input type="hidden" id="edit_id" name="id">


                                                        <div class="fv-row mb-7">
                                                            <label class="required fs-6 fw-semibold mb-2">İlgili Sınıflar</label>
                                                            <select id="edit_class_ids" name="class_ids[]" data-control="select2" data-placeholder="Sınıfları Seçin" class="form-select form-select-solid fw-bold" multiple>
                                                                <?php echo $classOptionsHTML; ?>
                                                            </select>
                                                        </div>

                                                        <div class="fv-row mb-7">
                                                            <label class="required fs-6 fw-semibold mb-2">Tekerleme Metni/Açıklaması</label>
                                                            <textarea id="edit_description" name="description" class="form-control form-control-solid" rows="4" placeholder="Tekerleme metnini buraya girin." required></textarea>
                                                        </div>

                                                        <div class="fv-row mb-7">
                                                            <label class="fs-6 fw-semibold mb-2">Görüntü Dosyası (Yeni dosya seçin)</label>
                                                            <p class="text-gray-500 fs-7 mb-1">Mevcut Dosya: <span id="current_image_path_display">Yok</span></p>
                                                            <input type="file" id="edit_image_path" name="image_path" accept="image/*" class="form-control form-control-solid" />
                                                            <input type="hidden" id="original_image_path" name="original_image_path">
                                                        </div>

                                                        <div class="fv-row mb-7">
                                                            <label class="fs-6 fw-semibold mb-2">Ses Dosyası (Yeni dosya seçin)</label>
                                                            <p class="text-gray-500 fs-7 mb-1">Mevcut Dosya: <span id="current_sound_path_display">Yok</span></p>
                                                            <input type="file" id="edit_sound_path" name="sound_path" accept="audio/*" class="form-control form-control-solid" />
                                                            <input type="hidden" id="original_sound_path" name="original_sound_path">
                                                        </div>

                                                        <div class="text-center pt-15">
                                                            <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">İptal</button>
                                                            <button type="submit" class="btn btn-primary" id="kt_edit_tekerleme_submit">
                                                                <span class="indicator-label">Güncelle</span>
                                                                <span class="indicator-progress">Lütfen Bekleyin...
                                                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="kt_modal_message" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered mw-350px">
                                            <div class="modal-content">
                                                <div class="modal-header pb-0 border-0">
                                                    <h5 class="modal-title" id="modal_message_title">Bildirim</h5>
                                                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                                        <i class="ki-duotone ki-cross fs-1"></i>
                                                    </div>
                                                </div>
                                                <div class="modal-body pt-0 pb-5">
                                                    <p id="modal_message_content" class="text-gray-700"></p>
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
        <script>
            // Tekerleme yönetim paneli için özel JavaScript

            // Global değişkenler
            let tekerlemeDataTable;
            let currentDeleteId = null; // Silinecek öğenin ID'si

            // Yardımcı Fonksiyon: Mesaj Modalı Gösterme
            function showMessageModal(title, content, isSuccess = true) {
                const modal = new bootstrap.Modal(document.getElementById('kt_modal_message'));
                document.getElementById('modal_message_title').innerText = title;
                document.getElementById('modal_message_content').innerText = content;
                // İsteğe bağlı olarak ikon/renk değiştirebilirsiniz
                if (isSuccess) {
                    // Örneğin: document.getElementById('modal_message_title').classList.remove('text-danger');
                } else {
                    // Örneğin: document.getElementById('modal_message_title').classList.add('text-danger');
                }
                modal.show();
            }


            // 1. LİSTELEME İŞLEMİ (READ)
            function fetchTekerlemeler() {
                // Yükleniyor durumunu göster
                const tableBody = document.getElementById('tekerleme_listesi');
                tableBody.innerHTML = '<tr><td colspan="6" class="text-center">Veriler çekiliyor...</td></tr>';

                // Backend çağrısı: includes/ajax.php?service=tekerlemeList
                fetch('includes/ajax.php?service=tekerlemeList')
                    .then(response => {
                        // Yanıtın her zaman JSON olduğunu varsayıyoruz
                        if (!response.ok) {
                            throw new Error('Network response was not ok ' + response.statusText);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log("API Response:", data);

                        // Eğer DataTable zaten başlatıldıysa yok et
                        if (tekerlemeDataTable) {
                            tekerlemeDataTable.destroy();
                        }

                        // Tablo gövdesini temizle
                        tableBody.innerHTML = '';

                        if (data.status === 'success' && Array.isArray(data.data)) {
                            console.log("Tekerleme Verileri:", data.data);
                            data.data.forEach(item => {
                                const tr = document.createElement('tr');

                                // Açıklama önizlemesi (ilk 100 karakter)
                                const descriptionPreview = item.description ? item.description.substring(0, 100) + (item.description.length > 100 ? '...' : '') : 'Açıklama Yok';

                                // Sınıf ID'lerini virgülle ayrılmış stringe çevir
                                const classNamesDisplay = item.class_names || '-'; // Durum rozeti
                                const statusText = item.status == 1 ? '<span class="badge badge-light-success">Aktif</span>' : '<span class="badge badge-light-danger">Pasif</span>';

                                tr.innerHTML = `
                                   
                                    <td class="text-gray-800 text-hover-primary mb-1">${descriptionPreview}</td>
                                    
                                   <td>${classNamesDisplay}</td>
                                    <td>${statusText}</td>
                                    <td class="text-end">
                                        <button class="btn btn-icon btn-sm btn-info me-2" data-bs-toggle="modal" data-bs-target="#kt_modal_edit_tekerleme" onclick="loadTekerlemeData(${item.id})" title="Düzenle">
                                            <i class="fas fa-pencil-alt fs-5"></i>
                                        </button>

                                        <button class="btn btn-icon btn-sm btn-danger" onclick="swalDeleteTekerleme(${item.id})" title="Sil">
                                            <i class="fas fa-trash fs-5"></i>
                                        </button>
                                        
                                        <button class="btn btn-icon btn-sm btn-${item.status == 1 ? 'warning' : 'success'} ms-2" onclick="handleStatusChange(${item.id}, ${item.status})" title="${item.status == 1 ? 'Pasif Yap' : 'Aktif Yap'}">
                                            <i class="fas fa-${item.status == 1 ? 'times-circle' : 'check-circle'} fs-5"></i>
                                        </button>
                                    </td>
                                `;
                                tableBody.appendChild(tr);
                            });

                            // DataTable'ı tekrar başlat
                            if (typeof $ !== 'undefined' && $.fn.DataTable) {
                                tekerlemeDataTable = $('#tekerleme_table').DataTable({
                                    "order": [
                                        [0, "desc"]
                                    ], // ID'ye göre azalan sırala
                                    "columnDefs": [{
                                            "orderable": false,
                                            "targets": [3]
                                        } // İşlemler sütununu sıralama dışı bırak
                                    ],
                                    "searching": true,
                                    "paging": true,
                                    "info": true,
                                    "responsive": true
                                });
                            }
                        } else {
                            tableBody.innerHTML = `<tr><td colspan="6" class="text-center text-danger">${data.message || 'Veri listeleme hatası.'}</td></tr>`;
                            // DataTable'ı boş veriyle başlat
                            if (typeof $ !== 'undefined' && $.fn.DataTable) {
                                tekerlemeDataTable = $('#tekerleme_table').DataTable({});
                            }
                        }
                    })
                    .catch(error => {
                        console.error('AJAX Error:', error);
                        tableBody.innerHTML = `<tr><td colspan="6" class="text-center text-danger">Sunucuya bağlanılamadı. Hata: ${error.message}</td></tr>`;
                    });
            }

            // Sayfa yüklendiğinde listeyi çek
            document.addEventListener('DOMContentLoaded', fetchTekerlemeler);


            // 2. EKLEME İŞLEMİ (CREATE)
            function handleTekerlemeSave(event) {
                event.preventDefault();
                const form = event.target;
                const submitButton = form.querySelector('#kt_add_tekerleme_submit');

                // Form verilerini FormData ile toplama
                const formData = new FormData(form);

                // Submit butonunu devre dışı bırak ve spinner'ı göster
                submitButton.setAttribute('data-kt-indicator', 'on');
                submitButton.disabled = true;

                // Backend çağrısı: includes/ajax.php?service=tekerlemeAdd
                fetch('includes/ajax.php?service=tekerlemeAdd', {
                        method: 'POST',
                        body: formData // FormData'yı gönderirken Content-Type'ı elle ayarlamaya gerek yoktur
                    })
                    .then(response => {
                        // response.json() döndürmeden önce hatayı kontrol et
                        if (!response.ok) {
                            throw new Error('Network response was not ok ' + response.statusText);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.status === 'success') {
                            // Başarılı mesajı göster
                            showMessageModal('Başarılı', data.message || 'Tekerleme başarıyla eklendi.', true);
                            // Modalı kapat
                            $('#kt_modal_add_tekerleme').modal('hide');
                            // Tabloyu yeniden yükle
                            fetchTekerlemeler();
                            // Formu temizle
                            form.reset();
                            // Select2 alanlarını temizle (eğer kullanılıyorsa)

                            $('#add_class_ids').val(null).trigger('change');
                        } else {
                            // Hata mesajı göster
                            showMessageModal('Hata', data.message || 'Tekerleme eklenirken bir hata oluştu.', false);
                        }
                    })
                    .catch(error => {
                        console.error('AJAX Error:', error);
                        showMessageModal('Kritik Hata', 'Sunucu hatası veya dosya yükleme problemi: ' + error.message, false);
                    })
                    .finally(() => {
                        // Butonu tekrar etkinleştir ve spinner'ı gizle
                        submitButton.removeAttribute('data-kt-indicator');
                        submitButton.disabled = false;
                    });
            }


            // 3. GÜNCELLEME VERİSİNİ YÜKLEME (READ Single Item for Update)
            // 3. GÜNCELLEME VERİSİNİ YÜKLEME (READ Single Item for Update)
            function loadTekerlemeData(id) {
                const form = document.getElementById('kt_modal_edit_tekerleme_form');
                form.reset(); // Formu temizle
                // Select2 alanını temizle
                $('#edit_class_ids').val(null).trigger('change');

                // Backend çağrısı: includes/ajax.php?service=tekerlemeShow&id=...
                fetch('includes/ajax.php?service=tekerlemeShow&id=' + encodeURIComponent(id))
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.status === 'success' && data.data) {
                            const item = data.data;

                            document.getElementById('edit_id').value = item.id;
                            document.getElementById('edit_description').value = item.description;

                            // ⭐ BURADAKİ DEĞİŞİKLİK BAŞLANGICI ⭐
                            // class_id verisi "10;11;12" formatında bir string olarak gelir.
                            if (item.class_id) {
                                // 1. String'i noktalı virgül (;) ile ayırarak bir diziye dönüştür.
                                const classArray = item.class_id.split(';');

                                // 2. Dizi içindeki string ID'leri kullanarak Select2'yi ayarla.
                                // Select2'nin .val() metodu, diziyi kabul eder ve ilgili seçenekleri seçer.
                                $('#edit_class_ids').val(classArray).trigger('change');
                            }
                            // ⭐ DEĞİŞİKLİK BİTİŞİ ⭐

                            // Mevcut dosya yollarını göster ve gizli alanlara kaydet
                            const imagePath = item.image_path || 'Yok';
                            const soundPath = item.sound_path || 'Yok';

                            document.getElementById('current_image_path_display').innerText = imagePath;
                            document.getElementById('original_image_path').value = imagePath;

                            document.getElementById('current_sound_path_display').innerText = soundPath;
                            document.getElementById('original_sound_path').value = soundPath;

                        } else {
                            showMessageModal('Hata', data.message || 'Tekerleme verisi yüklenirken bir hata oluştu.', false);
                            $('#kt_modal_edit_tekerleme').modal('hide'); // Modalı kapat
                        }
                    })
                    .catch(error => {
                        console.error('AJAX Error:', error);
                        showMessageModal('Kritik Hata', 'Veri yüklenirken sunucu hatası oluştu: ' + error.message, false);
                        $('#kt_modal_edit_tekerleme').modal('hide'); // Modalı kapat
                    });
            }

            // 4. GÜNCELLEME İŞLEMİ (UPDATE)
            function handleTekerlemeUpdate(event) {
                event.preventDefault();
                const form = event.target;
                const submitButton = form.querySelector('#kt_edit_tekerleme_submit');

                const formData = new FormData(form);

                submitButton.setAttribute('data-kt-indicator', 'on');
                submitButton.disabled = true;

                // Backend çağrısı: includes/ajax.php?service=tekerlemeUpdate
                fetch('includes/ajax.php?service=tekerlemeUpdate', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok ' + response.statusText);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.status === 'success') {
                            showMessageModal('Başarılı', data.message || 'Tekerleme başarıyla güncellendi.', true);
                            $('#kt_modal_edit_tekerleme').modal('hide');
                            fetchTekerlemeler();
                        } else {
                            showMessageModal('Hata', data.message || 'Tekerleme güncellenirken bir hata oluştu.', false);
                        }
                    })
                    .catch(error => {
                        console.error('AJAX Error:', error);
                        showMessageModal('Kritik Hata', 'Sunucu hatası veya dosya yükleme problemi: ' + error.message, false);
                    })
                    .finally(() => {
                        submitButton.removeAttribute('data-kt-indicator');
                        submitButton.disabled = false;
                    });
            }


            // 5. DURUM DEĞİŞTİRME İŞLEMİ (Status Change)
            function handleStatusChange(id, currentStatus) {
                const newStatus = currentStatus === 1 ? 0 : 1;
                const statusText = newStatus === 1 ? 'Aktif' : 'Pasif';

                if (!confirm(`Tekerlemeyi ${statusText} yapmak istediğinizden emin misiniz?`)) {
                    return;
                }

                // Backend çağrısı: includes/ajax.php?service=tekerlemeChangeStatus
                fetch('includes/ajax.php?service=tekerlemeChangeStatus', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `id=${encodeURIComponent(id)}&status=${encodeURIComponent(newStatus)}`
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok ' + response.statusText);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.status === 'success') {
                            showMessageModal('Başarılı', data.message || `Tekerleme başarıyla ${statusText} yapıldı.`, true);
                            fetchTekerlemeler();
                        } else {
                            showMessageModal('Hata', data.message || 'Durum değiştirilirken bir hata oluştu.', false);
                        }
                    })
                    .catch(error => {
                        console.error('AJAX Error:', error);
                        showMessageModal('Kritik Hata', 'Sunucu hatası: ' + error.message, false);
                    });
            }

            // 6. SİLME İŞLEMİNİ SWEETALERT İLE HAZIRLAMA VE ONAYLAMA
            function swalDeleteTekerleme(id) {
                // SweetAlert2'nin yüklü olduğunu varsayıyoruz (Swal veya swal global olarak erişilebilir)
                if (typeof Swal === 'undefined' && typeof swal === 'undefined') {
                    console.error("SweetAlert2 (Swal) kütüphanesi yüklenmemiş.");
                    if (!confirm(`Tekerlemeyi (ID: ${id}) silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.`)) {
                        return;
                    }
                    // Eğer swal yoksa ve kullanıcı onaylarsa eski usül silme fonksiyonunu çağır
                    executeTekerlemeDelete(id);
                    return;
                }

                // SweetAlert2 ile onay penceresi göster
                Swal.fire({
                    text: `${id} numaralı bu tekerlemeyi silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.`,
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Evet, Sil!",
                    cancelButtonText: "Hayır, İptal",
                    customClass: {
                        confirmButton: "btn btn-danger",
                        cancelButton: "btn btn-light"
                    }
                }).then(function(result) {
                    if (result.isConfirmed) {
                        // Onaylandıysa silme işlemini gerçekleştir
                        executeTekerlemeDelete(id);
                    }
                });
            }

            // 7. SİLME İŞLEMİNİ GERÇEKLEŞTİREN ANA FONKSİYON (DELETE)
            function executeTekerlemeDelete(id) {
                // Silme sırasında bir modal veya yükleme gösterilebilir (isteğe bağlı)

                // Backend çağrısı: includes/ajax.php?service=tekerlemeDelete
                fetch('includes/ajax.php?service=tekerlemeDelete', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `id=${encodeURIComponent(id)}`
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok ' + response.statusText);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.status === 'success') {
                            // Başarı mesajını SweetAlert ile göster
                            Swal.fire({
                                text: data.message || 'Tekerleme başarıyla silindi.',
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Tamam",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            });
                            fetchTekerlemeler();
                        } else {
                            // Hata mesajını SweetAlert ile göster
                            Swal.fire({
                                text: data.message || 'Tekerleme silinirken bir hata oluştu.',
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Tamam",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            });
                        }
                    })
                    .catch(error => {
                        console.error('AJAX Error:', error);
                        showMessageModal('Kritik Hata', 'Sunucu hatası: ' + error.message, false);
                    });
            }
        </script>
        <script src="assets/js/custom/apps/class/list/export.js"></script>
        <script src="assets/js/custom/apps/class/list/list.js"></script>
      
   
      

    </body>

    </html>
<?php } else {
    header("location: index");
}

?>