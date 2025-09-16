<!DOCTYPE html>
<html lang="tr">
<?php

session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 3 or $_SESSION['role'] == 4 or $_SESSION['role'] == 9)) {
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";

    include_once "views/pages-head.php";
    $data = new Classes();

    $data = $data->getTestById($_GET['id']);
    $data = json_decode($data, true);


?>
    <!--end::Head-->
    <!--begin::Body-->

    <body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" data-kt-app-aside-enabled="true" data-kt-app-aside-fixed="true" data-kt-app-aside-push-toolbar="true" data-kt-app-aside-push-footer="true" class="app-default">
        <!--begin::Theme mode setup on page load-->
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
        <!--end::Theme mode setup on page load-->
        <!--begin::App-->
        <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
            <!--begin::Page-->
            <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
                <!--begin::Header-->
                <?php include_once "views/header.php"; ?>
                <!--end::Header-->
                <!--begin::Wrapper-->
                <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                    <!--begin::Sidebar-->
                    <?php include_once "views/sidebar.php"; ?>
                    <!--end::Sidebar-->
                    <!--begin::Main-->
                    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                        <!--begin::Content wrapper-->
                        <div class="d-flex flex-column flex-column-fluid">
                            <!--begin::Toolbar-->
                            <?php include_once "views/toolbar.php"; ?>
                            <!--end::Toolbar-->
                            <!--begin::Content-->
                            <div id="kt_app_content" class="app-content flex-column-fluid">
                                <!--begin::Content container-->
                                <div id="kt_app_content_container" class="app-container container-fluid">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <label class="required fs-6 fw-semibold mb-2" for="cover_img">Görsel</label>
                                            <input type="file" id="cover_img" class="form-control" accept=".png, .jpg, .jpeg, .PNG, .JPG, .JPEG" />
                                            <div id="current_cover_img_container" class="mt-2">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="required fs-6 fw-semibold mb-2" for="title">Başlık</label>
                                            <input type="text" class="form-control" placeholder="Test Başlığı" id="title" value="<?= $data['test_title'] ?>">
                                        </div>

                                        <div class="col-lg-4">
                                            <label class="required fs-6 fw-semibold mb-2" for="class_id">Sınıf Seçimi</label>
                                            <?php
                                            // Bu kısım, PHP ile mevcut sınıfları çekip selectbox'ı doldurmak için kullanılacaktır.
                                            // Örneğin, bir Classes sınıfınız varsa ve getClassesList metodunuz varsa:
                                            $class = new Classes();
                                            $classList = $class->getClassesList();
                                            ?>
                                            <select class="form-select" id="class_id" required aria-label="Default select example">
                                                <option value="">Seçiniz</option>
                                                <?php foreach ($classList as $c) { ?>
                                                    <option value="<?= $c['id'] ?>" <?= ($data['class_id'] == $c['id']) ? 'selected' : '' ?>>
                                                        <?= $c['name'] ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-lg-4 mt-3">
                                            <label class="required fs-6 fw-semibold mb-2" for="lesson_id">Dersler</label>
                                            <select class="form-select" id="lesson_id" required>
                                                <option value="">Ders seçiniz</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-4 mt-4">
                                            <label class="fs-6 fw-semibold mb-2" for="unit_id">Ünite Seçimi</label>
                                            <select class="form-select" id="unit_id" required>
                                                <option value="">Ünite seçiniz</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-4 mt-4">
                                            <label class="fs-6 fw-semibold mb-2" for="topic_id">Konu Seçimi</label>
                                            <select class="form-select" id="topic_id" required>
                                                <option value="">Seçiniz</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-lg-4">
                                            <label class="fs-6 fw-semibold mb-2" for="subtopic_id">Alt Konu Seçimi</label>
                                            <select class="form-select" id="subtopic_id" required>
                                                <option value="">Alt Konu seçiniz</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="required fs-6 fw-semibold mb-2" for="start_date">Başlangıç Tarihi</label>
                                            <input type="datetime-local" class="form-control" id="start_date" name="start_date">
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="required fs-6 fw-semibold mb-2" for="end_date">Bitiş Tarihi</label>
                                            <input type="datetime-local" class="form-control" id="end_date" name="end_date">
                                        </div>

                                        <input type="hidden" id="option_count" value="0">
                                        <input type="hidden" id="test_id" value="">
                                    </div>
                                    <div class="row mt-5 ">
                                        <div class="col-lg-4">
                                            <label class="fs-6 fw-semibold mb-2" for="option_count">Durum</label>
                                            <select class="form-select" id="status">
                                                <option value="1" selected>Aktif</option>
                                                <option value="0">Pasif</option>
                                            </select>
                                        </div>

                                    </div>

                                    <div id="questions_container" class="mt-5"></div>

                                    <div class="row mt-5 mb-5">
                                        <div class="col-lg-4"></div>
                                        <div class="col-lg-3">
                                            <button type="button" id="addQuestion" class="btn btn-primary btn-sm w-100">Soru ekle</button>
                                        </div>
                                        <div class="col-lg-5">
                                            <button type="button" id="submitForm" class="btn btn-success btn-sm w-100">Kaydet</button>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Content container-->
                            </div>
                            <!--end::Content-->
                        </div>
                        <!--end::Content wrapper-->
                        <!--begin::Footer-->
                        <?php include_once "views/footer.php"; ?>
                        <!--end::Footer-->
                    </div>
                    <!--end:::Main-->
                    <!--begin::aside-->
                    <?php include_once "views/aside.php"; ?>
                    <!--end::aside-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Page-->
        </div>
        <!--end::App-->
        <!--begin::Scrolltop-->
        <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
            <i class="ki-duotone ki-arrow-up">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </div>
        <!--end::Scrolltop-->
        <!--begin::Javascript-->
        <script>
            var hostUrl = "assets/";
        </script>
        <!--begin::Global Javascript Bundle(mandatory for all pages)-->
        <script src="assets/plugins/global/plugins.bundle.js"></script>
        <script src="assets/js/scripts.bundle.js"></script>
        <!--end::Global Javascript Bundle-->
        <!--begin::Vendors Javascript(used for this page only)-->
        <script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
        <!--end::Vendors Javascript-->
        <!--begin::Custom Javascript(used for this page only)-->
        <script src="assets/plugins/custom/tinymce/tinymce.bundle.js"></script>
        <script src="assets/js/custom/apps/tests/list/list.js"></script>

        <!--end::Custom Javascript-->
        <!--end::Javascript-->
    </body>
    <!--end::Body-->
    <script>
        let currentMaxQuestionIndex = 0; // Global olarak tanımlı, soru eklerken kullanılacak

        // TinyMCE editörünü başlatma fonksiyonu
       function initTinyMCE(selector, content = '') {
    if (tinymce.get(selector.replace('#', ''))) {
        tinymce.get(selector.replace('#', '')).remove();
    }
    tinymce.init({
        selector: selector,
        height: 150,
        menubar: false,
        plugins: 'link image media',
        toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright | bullist numlist outdent indent | link image media | fraction',
        setup: function(editor) {

            // Özel Kesir Butonu
            editor.ui.registry.addButton('fraction', {
                text: 'Kesir',
                tooltip: 'Kesir ekle',
                onAction: function () {
                    editor.insertContent(
                        '<span style="display:inline-block; text-align:center;">' +
                            '<span style="display:block;">1</span>' +
                            '<span style="border-top:1px solid #000; display:block;">2</span>' +
                        '</span>'
                    );
                }
            });

            editor.on('init', function() {
                if (content) {
                    editor.setContent(content);
                }
            });
        }
    });
}


        // Seçenek etiketlerini (A, B, C...) oluşturan fonksiyon
        function getOptionLabels(count) {
            const labels = [];
            for (let i = 0; i < count; i++) {
                labels.push(String.fromCharCode(65 + i));
            }
            return labels;
        }

        // Video input alanı oluşturan fonksiyon
        function createVideoInput(index, value = '') {

            return `
    <div class="video-url-group mb-2 d-flex align-items-center gap-2">
        <input type="url" name="questions[${index}][videos][]" class="form-control" placeholder="Video URL" value="${value}" />
        <button type="button" class="btn btn-danger btn-sm remove-video-btn">Kaldır</button>
    </div>`;
        }

        // Resim input alanı oluşturan fonksiyon
        function createImageInput(index, fileName = '', filePath = '') {
            console.log(fileName);
            let currentImageDisplay = '';
            if (fileName && filePath) {
                currentImageDisplay = `
            <div class="current-image-display d-flex align-items-center gap-2 mt-1">
                
               
                <input type="hidden" name="questions[${index}][existing_images][]" value="${filePath}" />
                <a href="${filePath}" target="_blank">${fileName}</a>
            </div>`;
            }

            return `
    <div class="image-upload-group mb-2 d-flex align-items-center gap-2">
        <input type="file" name="questions[${index}][images][]" accept="image/*" class="form-control" />
        <button type="button" class="btn btn-danger btn-sm remove-image-btn">Kaldır</button>
        ${currentImageDisplay}
    </div>`;
        }

        // Seçenek resim input alanı oluşturan fonksiyon
        function createOptionImageInput(questionIdx, optionLabel, fileName = '', filePath = '') {
            let currentImageDisplay = '';
            if (fileName && filePath) {
                currentImageDisplay = `
            <div class="current-option-image-display d-flex align-items-center gap-2 mt-1">
                <a href="${filePath}" target="_blank">${fileName}</a>
                <input type="hidden" name="questions[${questionIdx}][options][${optionLabel}][existing_images][]" value="${filePath}" />
            </div>`;
            }
            return `
        <div class="option-image-upload-group mb-2 d-flex align-items-center gap-2">
            <input type="file" name="questions[${questionIdx}][options][${optionLabel}][images][]" accept="image/*" class="form-control" />
            <button type="button" class="btn btn-danger btn-sm remove-option-image-btn">Kaldır</button>
            ${currentImageDisplay}
        </div>`;
        }


        // Seçenek inputlarını oluşturan fonksiyon
        function createOptionInputs(index, optionCount, optionsData = []) {
            let html = '';
            let optionLabels = getOptionLabels(optionCount);

            optionLabels.forEach(label => {
                const optionItem = optionsData.find(opt => opt.option_key === label);
                const optionText = optionItem ? optionItem.option_text : '';
                const optionImages = optionItem && optionItem.files ? optionItem.files : [];

                console.log(optionItem); // debug amaçlı

                let imagesHtml = '';
                if (optionImages.length > 0) {
                    optionImages.forEach(img => {
                        const fileName = img.split('/').pop();
                        imagesHtml += createOptionImageInput(index, label, fileName, img);
                    });
                } else {
                    imagesHtml += createOptionImageInput(index, label);
                }

                html += `<div class="option-block mb-3 border p-3 rounded">
            <label class="mt-3 mb-3"  style="font-size:16px;"><b>Seçenek ${label}</b></label>
            <textarea name="questions[${index}][options][${label}][text]" id="option-tinymce-${index}-${label}" class="option-textarea form-control mb-2">${optionText}</textarea>
            <div class="option-images-container">
                <label>Seçeneğe Görsel Ekle</label>
                <div class="option-image-inputs">
                    ${imagesHtml}
                </div>
                <button type="button" class="btn btn-sm btn-secondary add-option-image-btn" data-index="${index}" data-label="${label}">+ Görsel Ekle</button>
            </div>
        </div>`;
            });

            return html;
        }


        // Soru bloğu oluşturan fonksiyon
        function createQuestionBlock(index, questionNumber, questionData = {}) {

            var classId = $('#class_id').val();
            if (classId == 3 || classId == 4 || classId == 5) {
                var optionCounts = 3;
            } else {
                var optionCounts = 4;
            }
            const optionCount = parseInt(optionCounts) || 3;
            const optionInputsHTML = createOptionInputs(index, optionCount, questionData.options);
            const optionLabels = getOptionLabels(optionCount);

            let videosHtml = '';
            if (questionData.videos && questionData.videos.length > 0) {
                questionData.videos.forEach(video => {
                    videosHtml += createVideoInput(index, video);
                });
            } else {
                videosHtml += createVideoInput(index);
            }

            let imagesHtml = '';
            if (questionData.files && questionData.files.length > 0) {
                questionData.files.forEach(image => {
                    const fileName = image.split('/').pop(); // Dosya adını al
                    imagesHtml += createImageInput(index, fileName, image);
                });
            } else {
                imagesHtml += createImageInput(index);
            }

            return `
    <div class="question-block mb-4 card border-primary shadow-sm" data-question-index="${index}">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Soru ${questionNumber}</h5>
            <button type="button" class="btn btn-danger btn-sm remove-question-btn">Kaldır</button>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Soru Metni</label>
                <textarea name="questions[${index}][text]" id="question-tinymce-${index}" class="question-textarea form-control">${questionData.question_text || ''}</textarea>
            </div>

            <div class="videos-container mb-3 border p-2 rounded bg-light">
                <label class="form-label">Video URL'leri</label>
                ${videosHtml}
                <button type="button" class="btn btn-sm btn-secondary add-video-btn" data-index="${index}">+ Video Ekle</button>
            </div>

            <div class="images-container mb-3 border p-2 rounded bg-light">
                <label class="form-label">Görseller</label>
                ${imagesHtml}
                <button type="button" class="btn btn-sm btn-secondary add-image-btn" data-index="${index}">+ Görsel Ekle</button>
            </div>

            <div class="options-container border p-2 rounded bg-light">
                <h3 class="border-bottom pb-2">Seçenekler</h3>
                ${optionInputsHTML}
            </div>
            
            <div class="mt-3">
                <label class="form-label">Doğru Cevap Seçeneği <span class="text-danger">*</span></label>
                <select name="questions[${index}][correct_answer]" class="form-select correct-answer-select" required>
                    <option value="">Seçiniz</option>
                    ${optionLabels.map(label => `<option value="${label}" ${questionData.correct_answer === label ? 'selected' : ''}>${label}</option>`).join('')}
                </select>
            </div>
        </div>
    </div>
    `;
        }

        // Soru silindiğinde question-block'ları yeniden numaralandırma
        function renumberQuestionsOnDelete() {
            document.querySelectorAll('.question-block').forEach((block, idx) => {
                const header = block.querySelector('.card-header h5');
                if (header) {
                    header.textContent = `Soru ${idx + 1}`;
                }

                block.dataset.questionIndex = idx; // Update the data-question-index

                // Update all name attributes
                $(block).find('[name^="questions["]').each(function() {
                    const currentName = $(this).attr('name');
                    const newName = currentName.replace(/questions\[\d+\]/, `questions[${idx}]`);
                    $(this).attr('name', newName);

                    let newId = '';
                    if ($(this).hasClass('question-textarea')) {
                        newId = `question-tinymce-${idx}`;
                    } else if ($(this).hasClass('option-textarea')) {
                        const match = currentName.match(/\[options\]\[([A-Z])\]\[text\]/);
                        if (match && match[1]) {
                            newId = `option-tinymce-${idx}-${match[1]}`;
                        }
                    }

                    if (newId) {
                        const oldId = $(this).attr('id');
                        if (oldId && tinymce.get(oldId)) {
                            tinymce.get(oldId).remove(); // Remove old TinyMCE instance
                        }
                        $(this).attr('id', newId); // Update ID
                        initTinyMCE(`#${newId}`, tinymce.get(oldId) ? tinymce.get(oldId).getContent() : ''); // Re-initialize with old content
                    }
                });

                // Update data-index and name for buttons and selects
                $(block).find('.add-option-image-btn').attr('data-index', idx);
                $(block).find('.add-video-btn').attr('data-index', idx);
                $(block).find('.add-image-btn').attr('data-index', idx);
                $(block).find('.correct-answer-select').attr('name', `questions[${idx}][correct_answer]`);
            });
        }

        // Mevcut en yüksek soru indeksini güncelleyen fonksiyon
        function updateMaxQuestionIndex() {
            let maxIndex = -1;
            document.querySelectorAll('.question-block').forEach(block => {
                const index = parseInt(block.dataset.questionIndex);
                if (!isNaN(index) && index > maxIndex) {
                    maxIndex = index;
                }
            });
            currentMaxQuestionIndex = maxIndex;
        }

        // AJAX Hata Yönetimi
        function handleAjaxError(xhr) {
            let errorMessage = 'Bilinmeyen bir hata oluştu.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.responseText) {
                try {
                    let json = JSON.parse(xhr.responseText);
                    if (json.message) errorMessage = json.message;
                } catch (e) {}
            }
            console.error("AJAX Hatası:", errorMessage);
            Swal.fire({
                icon: 'error',
                title: 'Hata',
                text: errorMessage
            });
        }

        // Mevcut test verilerini yükleme fonksiyonu
        function loadTestData(testId) {
            $.ajax({
                url: 'includes/ajax.php?service=getTestDetails', // Test detaylarını çekecek AJAX servisi
                type: 'POST',
                dataType: 'json',
                data: {
                    test_id: testId
                },
                success: function(response) {
                    if (response.status === 'success' && response.data) {
                        const testData = response.data;

                        // Genel test bilgilerini doldur
                        $('#test_title').val(testData.title);
                        $('#start_date').val(testData.start_date);
                        $('#end_date').val(testData.end_date);
                        $('#status').val(testData.status);
                        $('#option_count').val(testData.option_count || 3); // Varsayılan olarak 3 seçenek
                        $('#test_id').val(testData.id); // Test ID'sini gizli alana yaz

                        // Mevcut kapak görselini göster
                        if (testData.cover_img) {
                            $('#current_cover_img_container').html(`
                        <img src="${testData.cover_img}" alt="Mevcut Görsel" style="max-width: 150px; height: auto;">
                        <button type="button" class="btn btn-warning btn-sm remove-existing-cover-img mt-1" data-file-path="${testData.cover_img}">Mevcut Görseli Kaldır</button>
                        <input type="hidden" name="existing_cover_img" value="${testData.cover_img}" />
                    `);
                        }

                        // Sınıf seçimi ve bağlı ders/ünite/konu/alt konu seçimlerini doldur
                        $('#class_id').val(testData.class_id).trigger('change');

                        // Dersler yüklendikten sonra üniteleri, konuları ve alt konuları seç
                        // Bu kısım AJAX çağrılarının tamamlanmasını beklemeli
                        $(document).one('lessonsLoaded', function() {
                            if (testData.lesson_id) {
                                $('#lesson_id').val(testData.lesson_id).trigger('change');
                            }
                        });
                        $(document).one('unitsLoaded', function() {
                            if (testData.unit_id) {
                                $('#unit_id').val(testData.unit_id).trigger('change');
                            }
                        });
                        $(document).one('topicsLoaded', function() {
                            if (testData.topic_id) {
                                $('#topic_id').val(testData.topic_id).trigger('change');
                            }
                        });
                        $(document).one('subtopicsLoaded', function() {
                            if (testData.subtopic_id) {
                                $('#subtopic_id').val(testData.subtopic_id); // Sonuncusu trigger'a gerek duymaz
                            }
                        });


                        // Soruları doldur
                        const questionsContainer = $('#questions_container');
                        questionsContainer.empty(); // Mevcut soruları temizle
                        currentMaxQuestionIndex = -1; // Soru indeksini sıfırla

                        if (testData.questions && testData.questions.length > 0) {
                            testData.questions.forEach((question, idx) => {
                                const newQuestionHTML = createQuestionBlock(idx, idx + 1, question);
                                questionsContainer.append(newQuestionHTML);

                                // TinyMCE'yi başlat ve içeriği ayarla
                                initTinyMCE(`#question-tinymce-${idx}`, question.text);
                                if (question.options) {
                                    question.options.forEach(option => { // Diziyi doğrudan döngüye alıyoruz
                                        initTinyMCE(`#option-tinymce-${idx}-${option.option_key}`, option.option_text || '');
                                    });
                                }

                                currentMaxQuestionIndex = idx; // En yüksek indeksi güncelle
                            });
                        }
                        updateMaxQuestionIndex(); // Tüm sorular eklendikten sonra son güncellemeyi yap
                    } else {
                        Swal.fire('Hata', response.message || 'Test detayları yüklenemedi.', 'error');
                    }
                },
                error: function(xhr) {
                    handleAjaxError(xhr);
                }
            });
        }

        $(document).ready(function() {
            // Sayfa yüklendiğinde URL'den test ID'sini al (örneğin: edit.php?id=123)
            const urlParams = new URLSearchParams(window.location.search);
            const testId = urlParams.get('id');

            if (testId) {
                loadTestData(testId);
            } else {
                // Yeni bir test ekleniyorsa, varsayılan olarak bir soru ekle (isteğe bağlı)
                // Ya da formu boş bırak ve "Soru ekle" düğmesine basılmasını bekle.
                // updateMaxQuestionIndex(); // Boş sayfada indeks sıfırda kalır
            }

            // Event listener'lar (mevcut kodunuzdan kopyalananlar)
            $('#class_id').on('change', function() {
                var classId = $(this).val();

                if (document.querySelectorAll('.question-block').length > 0) {
                    Swal.fire({
                        title: 'Emin misiniz?',
                        text: "Sınıf değişikliği tüm eklenen soruları silecektir! (Mevcut testten yüklenenler dahil)",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Evet, sil ve değiştir!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            clearAllQuestionsAndRelatedFields(classId);
                        } else {
                            // Kullanıcı vazgeçerse, değişiklik yapılmaz
                            // Belki de önceki değeri geri yüklemek istersiniz.
                            // Örneğin: $(this).val($(this).data('previousValue'));
                        }
                    });
                } else {
                    fetchLessonsForClass(classId);
                }
            });

            function clearAllQuestionsAndRelatedFields(classId) {
                const questionsContainer = document.getElementById('questions_container');
                questionsContainer.querySelectorAll('textarea').forEach(textarea => {
                    const editor = tinymce.get(textarea.id);
                    if (editor) {
                        editor.remove();
                    }
                });
                questionsContainer.innerHTML = '';
                currentMaxQuestionIndex = -1; // Soru indeksini sıfırla

                $('#lesson_id').html('<option value="">Ders seçiniz</option>');
                $('#unit_id').html('<option value="">Ünite seçiniz</option>');
                $('#topic_id').html('<option value="">Seçiniz</option>');
                $('#subtopic_id').html('<option value="">Alt Konu seçiniz</option>');
                // Option count'u burada sıfırlamamak daha mantıklı olabilir, çünkü class_id'den gelecek.
                // $('#option_count').val('');

                fetchLessonsForClass(classId);
            }

            function fetchLessonsForClass(classId, selectedLessonId = null) {
                if (classId !== '') {
                    $.ajax({
                        url: 'includes/ajax.php?service=getLessonList',
                        type: 'POST',
                        data: {
                            class_id: classId
                        },
                        dataType: 'json',
                        success: function(response) {
                            console.log(response);

                            var $lessonSelect = $('#lesson_id');
                            $('#option_count').val(response.data.optionCount);
                            $lessonSelect.empty();
                            $lessonSelect.append('<option value="">Ders seçiniz</option>');
                            $.each(response.data, function(index, lesson) {
                                $lessonSelect.append($('<option>', {
                                    value: lesson.id,
                                    text: lesson.name,
                                    selected: (selectedLessonId && selectedLessonId == lesson.id) // Seçili dersi ayarla
                                }));
                            });
                            $(document).trigger('lessonsLoaded'); // Yeni event
                        },
                        error: function(xhr) {
                            handleAjaxError(xhr);
                        }
                    });
                } else {
                    $('#lesson_id').html('<option value="">Ders seçiniz</option>');
                    $('#unit_id').html('<option value="">Ünite seçiniz</option>');
                    $('#topic_id').html('<option value="">Seçiniz</option>');
                    $('#subtopic_id').html('<option value="">Alt Konu seçiniz</option>');
                }
            }

            $('#lesson_id').on('change', function() {
                var lessonId = $(this).val();
                var classId = $('#class_id').val();
                var $unitSelect = $('#unit_id');
                $unitSelect.empty().append('<option value="">Ünite seçiniz</option>');
                $('#topic_id').html('<option value="">Seçiniz</option>');
                $('#subtopic_id').html('<option value="">Alt Konu seçiniz</option>');

                if (lessonId !== '') {
                    $.ajax({
                        url: 'includes/ajax.php?service=getUnitList',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            lesson_id: lessonId,
                            class_id: classId
                        },
                        success: function(response) {
                            if (response.status === 'success' && response.data.length > 0) {
                                $.each(response.data, function(index, unit) {
                                    $unitSelect.append($('<option>', {
                                        value: unit.id,
                                        text: unit.name
                                    }));
                                });
                            } else {
                                $unitSelect.append('<option disabled>Ünite bulunamadı</option>');
                            }
                            $(document).trigger('unitsLoaded'); // Yeni event
                        },
                        error: function(xhr) {
                            handleAjaxError(xhr);
                        }
                    });
                }
            });

            $('#unit_id').on('change', function() {
                var classId = $('#class_id').val();
                var lessonId = $('#lesson_id').val();
                var unitId = $(this).val();
                var $topicSelect = $('#topic_id');

                $topicSelect.empty().append('<option value="">Seçiniz</option>');
                $('#subtopic_id').html('<option value="">Alt Konu seçiniz</option>');

                if (unitId !== '') {
                    $.ajax({
                        url: 'includes/ajax.php?service=getTopicList',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            class_id: classId,
                            lesson_id: lessonId,
                            unit_id: unitId
                        },
                        success: function(response) {
                            if (response.status === 'success' && response.data.length > 0) {
                                $.each(response.data, function(index, topic) {
                                    $topicSelect.append($('<option>', {
                                        value: topic.id,
                                        text: topic.name
                                    }));
                                });
                            } else {
                                $topicSelect.append('<option disabled>Konu bulunamadı</option>');
                            }
                            $(document).trigger('topicsLoaded'); // Yeni event
                        },
                        error: function(xhr) {
                            handleAjaxError(xhr);
                        }
                    });
                }
            });

            $('#topic_id').on('change', function() {
                var classId = $('#class_id').val();
                var lessonId = $('#lesson_id').val();
                var unitId = $('#unit_id').val();
                var topicId = $(this).val();
                var $subtopicSelect = $('#subtopic_id');

                $subtopicSelect.empty().append('<option value="">Alt Konu seçiniz</option>');

                if (topicId !== '') {
                    $.ajax({
                        url: 'includes/ajax.php?service=getSubtopicList',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            class_id: classId,
                            lesson_id: lessonId,
                            unit_id: unitId,
                            topic_id: topicId
                        },
                        success: function(response) {
                            if (response.status === 'success' && response.data.length > 0) {
                                $.each(response.data, function(index, subtopic) {
                                    $subtopicSelect.append($('<option>', {
                                        value: subtopic.id,
                                        text: subtopic.name
                                    }));
                                });
                            } else {
                                $subtopicSelect.append('<option disabled>Alt konu bulunamadı</option>');
                            }
                            $(document).trigger('subtopicsLoaded'); // Yeni event
                        },
                        error: function(xhr) {
                            handleAjaxError(xhr);
                        }
                    });
                }
            });

            // Form Gönderme
            document.getElementById('submitForm').addEventListener('click', function() {
                const formData = new FormData();

                const testId = document.getElementById('test_id').value;
                if (!testId) {
                    Swal.fire('Hata', 'Güncellenecek test ID bulunamadı.', 'error');
                    return;
                }
                formData.append('test_id', testId);

                const status = document.getElementById('status').value;
                const classId = document.getElementById('class_id').value;
                const lessonId = document.getElementById('lesson_id').value;
                const unitId = document.getElementById('unit_id').value;
                const topicId = document.getElementById('topic_id').value;
                const subtopicId = document.getElementById('subtopic_id').value;

                let formGeneralValid = true;

                if (classId === "") {
                    Swal.fire('Uyarı', 'Lütfen bir sınıf seçiniz.', 'warning');
                    formGeneralValid = false;
                    $('#class_id').addClass('is-invalid');
                } else {
                    $('#class_id').removeClass('is-invalid');
                    formData.append('class_id', classId);
                }
                formData.append('status', status);
                formData.append('lesson_id', lessonId);
                formData.append('unit_id', unitId);
                formData.append('topic_id', topicId);
                formData.append('subtopic_id', subtopicId);

                const title = document.getElementById('title').value;
                const startDate = document.getElementById('start_date').value;
                const endDate = document.getElementById('end_date').value;

                if (title === "") {
                    Swal.fire('Uyarı', 'Lütfen test başlığını giriniz.', 'warning');
                    formGeneralValid = false;
                    $('#title').addClass('is-invalid');
                } else {
                    $('#title').removeClass('is-invalid');
                    formData.append('title', title);
                }

                if (startDate === "") {
                    Swal.fire('Uyarı', 'Lütfen başlangıç tarihi seçiniz.', 'warning');
                    formGeneralValid = false;
                    $('#start_date').addClass('is-invalid');
                } else {
                    $('#start_date').removeClass('is-invalid');
                    formData.append('start_date', startDate);
                }

                if (endDate === "") {
                    Swal.fire('Uyarı', 'Lütfen bitiş tarihi seçiniz.', 'warning');
                    formGeneralValid = false;
                    $('#end_date').addClass('is-invalid');
                } else {
                    $('#end_date').removeClass('is-invalid');
                    formData.append('end_date', endDate);
                }

                const coverImgInput = document.getElementById('cover_img');
                if (coverImgInput && coverImgInput.files && coverImgInput.files[0]) {
                    formData.append('cover_img', coverImgInput.files[0]);
                } else {
                    const existingCoverImg = $('input[name="existing_cover_img"]').val();
                    if (existingCoverImg) {
                        formData.append('existing_cover_img', existingCoverImg);
                    }
                }
                if ($('.remove-existing-cover-img').data('removed')) {
                    formData.append('remove_cover_img', 'true');
                }

                let questionsValid = true;
                document.querySelectorAll('.question-block').forEach((questionBlock, qIdx) => {
                    // Soru metni
                    const questionTextarea = questionBlock.querySelector('.question-textarea');
                    if (questionTextarea) {
                        const questionContent = tinymce.get(questionTextarea.id) ? tinymce.get(questionTextarea.id).getContent() : '';
                        formData.append(`questions[${qIdx}][text]`, questionContent);
                    }

                    // Doğru cevap
                    const correctAnswerSelect = questionBlock.querySelector('.correct-answer-select');
                    if (correctAnswerSelect) {
                        if (correctAnswerSelect.value === "") {
                            questionsValid = false;
                            correctAnswerSelect.classList.add('is-invalid');
                        } else {
                            correctAnswerSelect.classList.remove('is-invalid');
                            formData.append(`questions[${qIdx}][correct_answer]`, correctAnswerSelect.value);
                        }
                    }

                    // Video URL'leri
                    questionBlock.querySelectorAll('.video-url-group input[type="url"]').forEach((input, vIdx) => {
                        if (input.value) {
                            formData.append(`questions[${qIdx}][videos][${vIdx}]`, input.value);
                        }
                    });

                    // Resimler
                    questionBlock.querySelectorAll('.image-upload-group').forEach((imgGroup, iIdx) => {
                        const fileInput = imgGroup.querySelector('input[type="file"]');
                        const existingInput = imgGroup.querySelector('input[name^="questions"][name*="existing_images"]');

                        if (fileInput && fileInput.files && fileInput.files[0]) {
                            formData.append(`questions[${qIdx}][images][${iIdx}]`, fileInput.files[0]);
                        } else if (existingInput && existingInput.value) {
                            formData.append(`questions[${qIdx}][existing_images][${iIdx}]`, existingInput.value);
                        }
                    });

                    // Seçenekler ve Seçenek Resimleri
                    questionBlock.querySelectorAll('.option-block').forEach((optionBlock) => {
                        const optionTextarea = optionBlock.querySelector('.option-textarea');
                        const optionLabelMatch = optionTextarea.name.match(/\[options\]\[([A-Z])\]/);

                        if (optionLabelMatch && optionLabelMatch[1]) {
                            const optionLabel = optionLabelMatch[1];

                            const optionContent = tinymce.get(optionTextarea.id) ? tinymce.get(optionTextarea.id).getContent() : '';
                            formData.append(`questions[${qIdx}][options][${optionLabel}][text]`, optionContent);

                            optionBlock.querySelectorAll('.option-image-upload-group').forEach((optImgGroup, optImgIdx) => {
                                const fileInput = optImgGroup.querySelector('input[type="file"]');
                                const existingInput = optImgGroup.querySelector('input[name^="questions"][name*="existing_images"]');

                                if (fileInput && fileInput.files && fileInput.files[0]) {
                                    formData.append(`questions[${qIdx}][options][${optionLabel}][images][${optImgIdx}]`, fileInput.files[0]);
                                } else if (existingInput && existingInput.value) {
                                    formData.append(`questions[${qIdx}][options][${optionLabel}][existing_images][${optImgIdx}]`, existingInput.value);
                                }
                            });
                        }
                    });
                });

                if (!formGeneralValid) {
                    return;
                }

                if (!questionsValid) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Form Hatası',
                        text: 'Lütfen tüm sorular için doğru cevap seçeneğini belirleyiniz.',
                        confirmButtonText: 'Tamam'
                    });
                    return;
                }

                $.ajax({
                    url: 'includes/ajax-ayd.php?service=testUpdate',
                    type: 'POST',
                    dataType: 'json',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Başarılı',
                                text: 'Test başarıyla güncellendi!',
                                confirmButtonText: 'Tamam'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Hata',
                                text: response.message || 'Beklenmeyen bir hata oluştu.',
                                confirmButtonText: 'Tamam'
                            });
                        }
                    },
                    error: function(xhr) {
                        handleAjaxError(xhr);
                    }
                });
            });

            // Soru Ekleme
            // Soru Ekleme
document.getElementById('addQuestion').addEventListener('click', function() {
    const classId = document.getElementById('class_id').value;

    if (classId === "") {
        Swal.fire({
            icon: 'warning',
            title: 'Sınıf Seçimi Gerekli',
            text: 'Lütfen yeni soru eklemek için önce bir sınıf seçiniz.',
            confirmButtonText: 'Tamam'
        });
        $('#class_id').focus().addClass('is-invalid');
        return;
    }
    $('#class_id').removeClass('is-invalid');

    const newIndex = currentMaxQuestionIndex + 1;
    const newQuestionNumber = document.querySelectorAll('.question-block').length + 1;

    const newQuestionHTML = createQuestionBlock(newIndex, newQuestionNumber);
    const questionsContainer = document.getElementById('questions_container');

    questionsContainer.insertAdjacentHTML('beforeend', newQuestionHTML);
    currentMaxQuestionIndex = newIndex;

    // Yeni eklenen soru bloğunu seç
    const newQuestionBlock = questionsContainer.querySelector('.question-block:last-child');
    
    // Soru metni için TinyMCE başlat
    const questionTextarea = newQuestionBlock.querySelector('.question-textarea');
    if (questionTextarea) {
        initTinyMCE(`#${questionTextarea.id}`);
    }
    
    // Seçenekler için TinyMCE başlat
    newQuestionBlock.querySelectorAll('.option-textarea').forEach(textarea => {
        initTinyMCE(`#${textarea.id}`);
    });
    
    // Yeniden numaralandırma yap
    renumberQuestionsOnDelete();
    updateMaxQuestionIndex();
});

            // Dinamik İçerik Olay Dinleyicileri (soru ekleme/silme, video/resim ekleme/silme)
            document.getElementById('questions_container').addEventListener('click', function(event) {
                const target = event.target;

                // Soru Kaldır
                if (target.classList.contains('remove-question-btn')) {
                    const questionBlock = target.closest('.question-block');
                    if (questionBlock) {
                        questionBlock.querySelectorAll('textarea.question-textarea, textarea.option-textarea').forEach(textarea => {
                            const editor = tinymce.get(textarea.id);
                            if (editor) {
                                editor.remove();
                            }
                        });
                        questionBlock.remove();
                        renumberQuestionsOnDelete(); // Numaraları yeniden düzenle
                        updateMaxQuestionIndex(); // Max indeksi güncelle
                    }
                }
                // Video Ekle
                else if (target.classList.contains('add-video-btn')) {
                    const questionBlock = target.closest('.question-block');
                    const idx = questionBlock.dataset.questionIndex;
                    const videoInputHTML = createVideoInput(idx);
                    target.insertAdjacentHTML('beforebegin', videoInputHTML);
                }
                // Video Kaldır
                else if (target.classList.contains('remove-video-btn')) {
                    const videoGroup = target.closest('.video-url-group');
                    if (videoGroup) videoGroup.remove();
                }
                // Resim Ekle
                else if (target.classList.contains('add-image-btn')) {
                    const questionBlock = target.closest('.question-block');
                    const idx = questionBlock.dataset.questionIndex;
                    const imageInputHTML = createImageInput(idx);
                    target.insertAdjacentHTML('beforebegin', imageInputHTML);
                }
                // Resim Kaldır
                else if (target.classList.contains('remove-image-btn')) {
                    const imageGroup = target.closest('.image-upload-group');
                    if (imageGroup) imageGroup.remove();
                }
                // Mevcut Görseli Kaldır (Soru içi)
                else if (target.classList.contains('remove-existing-image')) {
                    const imageGroup = target.closest('.image-upload-group');
                    if (imageGroup) {
                        // Gizli input'u kaldırılacak olarak işaretle veya direkt kaldır
                        // Örneğin, 'removed' sınıfı ekleyip sunucu tarafında kontrol edebilirsiniz.
                        // Ya da hidden input'u silip, sadece yeni görsel yüklendiğinde ya da hiç görsel olmadığında işlem yapabilirsiniz.
                        $(target).closest('.current-image-display').remove(); // Mevcut görseli gösteren div'i kaldır
                        // Silme işlemini backend'e bildirmek için hidden input'un değerini null yapabilir veya farklı bir işaretçi kullanabilirsiniz.
                        // Şu anda sadece DOM'dan kaldırıyoruz, backend'e bu görselin silindiğini bildirmek için FormData'ya özel bir alan eklemeniz gerekebilir.
                    }
                }
                // Seçenek Resim Ekle
                else if (target.classList.contains('add-option-image-btn')) {
                    const idx = target.getAttribute('data-index');
                    const label = target.getAttribute('data-label');
                    const optionImageInputsContainer = target.parentElement.querySelector('.option-image-inputs');
                    if (optionImageInputsContainer) {
                        optionImageInputsContainer.insertAdjacentHTML('beforeend', createOptionImageInput(idx, label));
                    }
                }
                // Seçenek Resim Kaldır
                else if (target.classList.contains('remove-option-image-btn')) {
                    const optionImageGroup = target.closest('.option-image-upload-group');
                    if (optionImageGroup) optionImageGroup.remove();
                }
                // Mevcut Seçenek Görseli Kaldır
                else if (target.classList.contains('remove-existing-option-image')) {
                    const optionImageGroup = target.closest('.option-image-upload-group');
                    if (optionImageGroup) {
                        $(target).closest('.current-option-image-display').remove();
                    }
                }
            });

            // Mevcut kapak görselini kaldırma butonu için event listener
            $(document).on('click', '.remove-existing-cover-img', function() {
                $(this).closest('#current_cover_img_container').empty();
                // Bu görselin kaldırılacağını FormData'ya bildirmek için bir işaretçi ekleyebilirsiniz.
                $(this).data('removed', true); // Custom data attribute for tracking removed images
            });


        });
    </script>

</html>
<?php } else {
    header("location: index");
}
