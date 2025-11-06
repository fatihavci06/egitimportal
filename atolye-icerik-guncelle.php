<?php
session_start();
define('GUARD', true);

if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 10001)) {
  include_once "classes/dbh.classes.php";
  include "classes/classes.classes.php";
  include_once "views/pages-head.php";

  // ----------------------------------------------------
  // 1. GÜNCELLEME İÇERİĞİNİ ÇEKME
  // ----------------------------------------------------
  $contentId = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;

  if (!$contentId) {
    header("Location: atolye-icerikleri");
    exit;
  }

  $classesObj = new Classes();

  // Bu metodun, atolye_contents tablosundan ana içeriği ve
  // atolye_files ve atolye_wordwall_links tablolarından ilgili verileri çekmesini varsayıyoruz.
  $contentData = $classesObj->getAtolyeContentForEdit($contentId);
if (!empty($contentData['zoom_url'])) {
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'warning',
            title: 'Toplantı düzenlenemez',
            text: 'Planlanmış toplantı düzenlenemez.Toplantı durumunu pasife çekip yeni bir toplantı oluşturabilirsiniz.',
            confirmButtonText: 'Tamam'
        }).then(() => {
            window.location.href = 'atolye-yonetimi';
        });
    });
    </script>";
    exit();
}

  if (!$contentData) {
    header("Location: atolye-icerikleri");
    exit;
  }

  // Form için gerekli diğer verileri çekme
  $mainSchoolClasses = $classesObj->getAgeGroup(); // Tüm yaş gruplarını çek

  // Çekilen veriden seçili yaş gruplarını ayırma
  $selectedClassIdsString = $contentData['class_ids'] ?? '';
  $delimiter = strpos($selectedClassIdsString, ';') !== false ? ';' : ',';
  $selectedClassIdsArray = array_map('trim', explode($delimiter, $selectedClassIdsString));

  // WordWall Linkleri (Eğer varsa)
  $wordwallLinks = $contentData['wordwall_links'] ?? [];
  // Mevcut Dosyalar (Eğer varsa)
  $existingFiles = $contentData['files'] ?? [];
?>

  <!DOCTYPE html>
  <html lang="tr">

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
                  <div class="card-body pt-5">

                    <form class="form" action="#" id="UpdateContentForm">
                      <input type="hidden" name="content_id" value="<?= $contentData['id'] ?>">

                      <div class="row mt-4">
                        <div class="col-lg-6">
                          <label class="fs-6 fw-semibold mb-2" for="main_school_class_id">Yaş Grubu </label>
                          <select class="form-select"
                            id="main_school_class_id"
                            name="main_school_class_id[]"
                            multiple
                            required
                            data-control="select2"
                            data-placeholder="Yaş Grupları Seçiniz">
                            <?php foreach ($mainSchoolClasses as $c) {
                              $selected = in_array($c['id'], $selectedClassIdsArray) ? 'selected' : '';
                            ?>
                              <option value="<?= $c['id'] ?>" <?= $selected ?>><?= $c['name'] ?></option>
                            <?php } ?>
                          </select>
                        </div>
                        <div class="col-lg-6">
                          <label class="required fs-6 fw-semibold mb-2" for="subject">Başlık</label>
                          <input type="text" class="form-control" placeholder="Konu Başlığı" id="subject" name="subject" value="<?= htmlspecialchars($contentData['subject'] ?? '') ?>" required />
                        </div>
                      </div>

                      <div class="row mt-4">

                        <div class="col-lg-6">
                          <label class="required fs-6 fw-semibold mb-2" for="content_type">Tür</label>
                          <select class="form-select" id="content_type" name="content_type" required data-control="select2" data-placeholder="Tür Seçimi">
                            <option value="">Seçiniz</option>
                            <?php
                            $types = ["Spor ve Dans Atölyesi", "Bilim Atölyesi", "Sanat Atölyesi", "Puzzle Atölyesi"];
                            foreach ($types as $type) {
                              $selected = ($contentData['content_type'] ?? '') == $type ? 'selected' : '';
                              echo "<option value=\"$type\" $selected>$type</option>";
                            }
                            ?>
                          </select>
                        </div>
                      </div>


                      <div class="row mt-4">
                        <label class="required fs-6 fw-semibold mb-2">İçerik Türü</label>
                        <div class="fv-row mb-7 mt-4" id="chooseOne">

                          <?php $secimType = $contentData['secim_type'] ?? ''; ?>

                          <label class="me-10">
                            <input class="form-check-input" type="radio" name="secim" value="video_link" <?= $secimType == 'video_link' ? 'checked' : '' ?>> Video URL
                          </label>
                          <label class="me-10">
                            <input class="form-check-input" type="radio" name="secim" value="file_path" <?= $secimType == 'file_path' ? 'checked' : '' ?>> Dosya / Görsel Yükle
                          </label>

                          <label>
                            <input class="form-check-input" type="radio" name="secim" value="wordwall" <?= $secimType == 'wordwall' ? 'checked' : '' ?>> İnteraktif Oyun
                          </label>
                        </div>

                        <div id="videoInput" class="mb-4" style="display:none;">
                          <label for="video_url">Video Link (Youtube, Vimeo, vb.):</label>
                          <input type="text" class="form-control" name="video_url" id="video_url" value="<?= $secimType == 'video_link' ? htmlspecialchars($contentData['content'] ?? '') : '' ?>">
                        </div>

                        <div id="fileInput" class="mb-4" style="display:none;">

                          <div class="mb-5 border p-3 rounded">
                            <h5 class="fs-6 fw-bold mb-3">Mevcut Dosyalar/Görseller</h5>
                            <div id="existingFilesContainer">
                              <?php if ($secimType == 'file_path' && !empty($existingFiles)): ?>
                                <ul class="list-group list-group-flush">
                                  <?php foreach ($existingFiles as $file): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center" data-file-id="<?= $file['id'] ?>">
                                      <span>
                                        <a href="<?= htmlspecialchars($file['file_path']) ?>" target="_blank" class="fw-bold"><?= basename($file['file_path']) ?></a>
                                        <small class="text-muted ms-3">(<?= htmlspecialchars($file['description']) ?>)</small>
                                      </span>
                                      <button type="button" class="btn btn-sm btn-danger delete-existing-file" data-file-id="<?= $file['id'] ?>">Sil</button>
                                    </li>
                                  <?php endforeach; ?>
                                </ul>
                              <?php else: ?>
                                <p class="text-muted">Mevcut dosya yok.</p>
                              <?php endif; ?>
                            </div>
                          </div>

                          <label for="files">Yeni Dosya ve Görsel Yükle (Çoklu Seçilebilir):</label>
                          <input type="file" class="form-control" name="files[]" id="files" multiple accept=".xls,.xlsx,.doc,.docx,.ppt,.pptx,.png,.jpeg,.jpg,.svg,.pdf">
                          <div id="fileDescriptions"></div>
                          <small class="text-muted">Yüklediğiniz her dosya/görsel için aşağıda bir açıklama alanı açılacaktır.</small>
                        </div>

                        <div id="wordwallInputs" class="mb-4" style="display:none;">
                          <label>WordWall Iframe Linkleri (Çoklu):</label>
                          <div id="dynamicFields">
                            <?php if ($secimType == 'wordwall' && !empty($wordwallLinks)): ?>
                              <?php foreach ($wordwallLinks as $link): ?>
                                <div class="input-group mb-2 wordwall-item">
                                  <input type="text" name="wordWallTitles[]" class="form-control me-2" placeholder="Başlık" value="<?= htmlspecialchars($link['title'] ?? '') ?>">
                                  <input type="text" name="wordWallUrls[]" class="form-control me-2" placeholder="URL" value="<?= htmlspecialchars($link['url'] ?? '') ?>">
                                  <button type="button" class="btn btn-danger removeField">Sil</button>
                                </div>
                              <?php endforeach; ?>
                            <?php else: ?>
                              <div class="input-group mb-2 wordwall-item" data-index="0">
                                <input type="text" name="wordWallTitles[]" class="form-control me-2" placeholder="Başlık">
                                <input type="text" name="wordWallUrls[]" class="form-control me-2" placeholder="URL">
                                <button type="button" class="btn btn-danger removeField">Sil</button>
                              </div>
                            <?php endif; ?>
                          </div>
                          <button type="button" id="addField" class="btn btn-sm btn-primary mt-2">Ekle</button>
                        </div>

                      </div>

                      <div id="textInput" class="mb-4" style="display:none;">
                        <label for="mcontent">Metin İçeriği:</label>
                        <textarea class="form-control tinymce-editor" name="content" id="mcontent" rows="4"><?= $secimType == 'content' ? htmlspecialchars($contentData['content'] ?? '') : '' ?></textarea>
                      </div>

                      <div class="row mt-5">
                        <div class="col-lg-11"></div>
                        <div class="col-lg-1">
                          <button type="submit" id="submitUpdateForm" class="btn btn-primary btn-sm">Güncelle</button>
                        </div>
                      </div>
                    </form>


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
    <script src="assets/plugins/custom/tinymce/tinymce.bundle.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="assets/js/widgets.bundle.js"></script>
    <script src="assets/js/custom/widgets.js"></script>

    <script>
      $(document).ready(function() {



        let fieldCount = $('#dynamicFields .wordwall-item').length;

        // --- 1. TinyMCE Başlatma (Hata çözümü için standart ayarlarla) ---
        tinymce.init({
          selector: '.tinymce-editor',
          // Gerekli standart eklentileri ve araç çubuğunu ekleyin
          plugins: 'advlist autolink lists link image charmap print preview anchor code',
          toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | code | help',
          height: 300
        });

        // --- 2. Select2 Başlatma ---
        $('#content_type').select2({
          placeholder: "Seçiniz",
          allowClear: true
        });
        $('#main_school_class_id').select2({
          placeholder: "Yaş Grupları Seçiniz",
          allowClear: true
        });


        // --- 3. Wordwall Dinamik Alan Ekleme/Silme ---
        $('#addField').on('click', function() {
          fieldCount++;
          $('#dynamicFields').append(`
          <div class="input-group mb-2 wordwall-item" data-index="${fieldCount}">
            <input type="text" name="wordWallTitles[]" class="form-control me-2" placeholder="Başlık">
            <input type="text" name="wordWallUrls[]" class="form-control me-2" placeholder="URL">
            <button type="button" class="btn btn-danger removeField">Sil</button>
          </div>
          `);
        });
        $('#dynamicFields').on('click', '.removeField', function() {
          $(this).closest('.input-group').remove();
        });

        // --- 4. Dosya Yüklendiğinde Açıklama Alanlarını Oluşturma ---
        $('#files').on('change', function() {
          const files = this.files;
          const container = $('#fileDescriptions');
          container.empty();

          for (let i = 0; i < files.length; i++) {
            const fileName = files[i].name;
            const descriptionField = `
            <div class="mb-3">
              <label for="description_${i}" class="form-label">"${fileName}" dosyası için açıklama:</label>
              <textarea class="form-control" name="descriptions[]" id="description_${i}" rows="2"></textarea>
            </div>
            `;
            container.append(descriptionField);
          }
        });

        // --- 5. İçerik Türü Seçimi - Dinamik Alanların Gösterilmesi (Görünürlük Sorunu Çözümü) ---
        $('input[name="secim"]').on('change', function() {
          let selected = $('input[name="secim"]:checked').val();

          // Tüm inputları gizle
          $('#videoInput, #fileInput, #textInput, #wordwallInputs').hide();

          // Seçime göre ilgili inputu göster
          if (selected === 'video_link') {
            $('#videoInput').show();
          } else if (selected === 'file_path') {
            $('#fileInput').show();
          } else if (selected === 'content') {
            $('#textInput').show();
          } else if (selected === 'wordwall') {
            $('#wordwallInputs').show();
          }
        }).trigger('change'); // Sayfa yüklendiğinde bir kez çalıştır!


        // --- 6. GÜNCELLEME AJAX İŞLEMİ ---
        $('#UpdateContentForm').on('submit', function(e) {
          e.preventDefault();

          const submitButton = $('#submitUpdateForm');
          const selectedContentType = $('input[name="secim"]:checked').val();
          const selectedClassIds = $('#main_school_class_id').val();

          let content = '';
          let isValid = true;

          // İçerik/Video/Wordwall validasyonu
          if (selectedContentType === 'content') {
            content = tinymce.get('mcontent').getContent();
            if (content.trim() === '') {
              Swal.fire({
                icon: 'warning',
                title: 'Uyarı',
                text: 'Metin İçeriği boş olamaz.'
              });
              isValid = false;
            }
          } else if (selectedContentType === 'video_link' && $('#video_url').val().trim() === '') {
            Swal.fire({
              icon: 'warning',
              title: 'Uyarı',
              text: 'Video URL alanı boş olamaz.'
            });
            isValid = false;
          } else if (selectedContentType === 'wordwall') {
            // WordWall Validasyonu
            $('#dynamicFields .wordwall-item').each(function() {
              const titleValue = $(this).find('input[name="wordWallTitles[]"]').val().trim();
              const urlValue = $(this).find('input[name="wordWallUrls[]"]').val().trim();

              if ((urlValue !== '' && titleValue === '') || (titleValue !== '' && urlValue === '')) {
                Swal.fire({
                  icon: 'warning',
                  title: 'Eksik Alan',
                  text: 'WordWall Başlık ve URL alanları birlikte doldurulmalıdır.'
                });
                isValid = false;
                return false; // each döngüsünden çıkar
              }
            });
          }

          // Temel Validasyonlar
          if (!isValid || $('#subject').val().trim() === '' || $('#content_type').val() === '' || !selectedClassIds || selectedClassIds.length === 0) {
            if (isValid) { // Sadece temel alanlar eksikse bu uyarıyı göster
              Swal.fire({
                icon: 'warning',
                title: 'Uyarı',
                text: 'Lütfen zorunlu alanları (Başlık, Tür, İçerik Türü, Yaş Grubu) doldurun.'
              });
            }
            return;
          }

          // Butonu devre dışı bırak
          submitButton.prop('disabled', true).text('Güncelleniyor...');

          let formData = new FormData(this);

          // KRİTİK: class_ids'i tek bir string olarak ayırıcı ile ekle
          formData.set('class_ids', selectedClassIds.join(';'));

          // İçerik alanını doğru kaynaktan al ve FormData'ya ekle
          if (selectedContentType === 'content') {
            formData.set('content', tinymce.get('mcontent').getContent()); // TinyMCE'den gelen güncel içerik
          } else if (selectedContentType === 'video_link') {
            formData.set('content', $('#video_url').val());
          } else {
            // Dosya veya WordWall için content alanı boş bırakılır
            formData.set('content', '');
          }


          // YENİ EK SONU

          // AJAX gönderimi
          $.ajax({
            url: './includes/ajax.php?service=updateAtolyeContent',
            type: 'POST',
            data: formData,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function(response) {
              if (response.status === 'success') {
                Swal.fire({
                  icon: 'success',
                  title: 'Başarılı',
                  text: response.message || 'Atölye içeriği başarıyla güncellendi!',
                }).then(() => {
                  window.location.reload();
                });
              } else {
                Swal.fire({
                  icon: 'error',
                  title: 'Hata',
                  text: response.message || 'Beklenmeyen bir hata oluştu.',
                });
              }
            },
            error: function(xhr, status, error) {
              console.error("AJAX Error:", xhr.responseText);
              Swal.fire({
                icon: 'error',
                title: 'Sunucu Hatası',
                text: 'Güncelleme sırasında bir sunucu hatası oluştu. Detay: ' + (xhr.responseJSON ? xhr.responseJSON.message : error),
              });
            },
            complete: function() {
              submitButton.prop('disabled', false).text('Güncelle');
            }
          });
        });

        // MEVCUT DOSYA SİLME İŞLEMİ (AJAX) - Kalan kod aynı kalır
        $(document).on('click', '.delete-existing-file', function() {
          const button = $(this);
          const fileId = button.data('file-id');
          const contentId = $('input[name="content_id"]').val();

          Swal.fire({
            title: 'Emin misiniz?',
            text: "Bu dosyayı kalıcı olarak silmek istiyor musunuz?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Evet, Sil!',
            cancelButtonText: 'İptal'
          }).then((result) => {
            if (result.isConfirmed) {
              $.ajax({
                url: './includes/ajax.php?service=deleteAtolyeFile',
                type: 'POST',
                data: {
                  file_id: fileId,
                  content_id: contentId
                },
                dataType: 'json',
                success: function(response) {
                  if (response.status === 'success') {
                    Swal.fire('Silindi!', response.message, 'success');
                    button.closest('li').remove();
                    if ($('#existingFilesContainer li').length === 0) {
                      $('#existingFilesContainer').html('<p class="text-muted">Mevcut dosya yok.</p>');
                    }
                  } else {
                    Swal.fire('Hata!', response.message || 'Silme işlemi başarısız oldu.', 'error');
                  }
                },
                error: function() {
                  Swal.fire('Hata!', 'Sunucu bağlantı hatası.', 'error');
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