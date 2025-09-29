<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 3 or $_SESSION['role'] == 4 or $_SESSION['role'] == 9)) {
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";
    include_once "classes/units.classes.php";
    include_once "classes/units-view.classes.php";

    include_once "views/pages-head.php";

    $chooseUnit = new ShowUnit();

?>
<style>
    .spinner-overlay {
    display: none;               /* Başlangıçta gizli */
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(255, 255, 255, 0.6);
    z-index: 9999;
    justify-content: center;
    align-items: center;
}

    </style>
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
                            <?php if ($_SESSION['role'] == 1) { ?>
                                <div id="kt_app_content" class="app-content flex-column-fluid">
                                    <!--begin::Content container-->
                                    <div id="kt_app_content_container" class="app-container container-fluid">
                                        <!--begin::Card-->
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <label class="required fs-6 fw-semibold mb-2" for="cover_img">Görsel</label>
                                                <input type="file" id="cover_img" class="form-control " id="cover_img" accept=".png, .jpg, .jpeg, .PNG, .JPG, .JPEG" />

                                            </div>
                                            <div class="col-lg-4">
                                                <label class="required fs-6 fw-semibold mb-2" for="title">Başlık</label>
                                                <input type="text" class="form-control " placeholder="Test Başlığı" id="title">
                                            </div>

                                            <div class="col-lg-4">
                                                <label class="required fs-6 fw-semibold mb-2" for="week">Sınıf Seçimi </label>
                                                <?php
                                                $class = new Classes();
                                                $classList = $class->getClassesList();
                                                ?>
                                                <select class="form-select" id="class_id" required aria-label="Default select example">
                                                    <option value="">Seçiniz</option>
                                                    <?php foreach ($classList as $c) { ?>
                                                        <option value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
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



                                        <!--end::Card-->
                                    </div>
                                    <!--end::Content container-->
                                </div>
                            <?php } elseif ($_SESSION['role'] == 4) {
                                $class_id = $_SESSION['class_id'];
                                $lesson_id = $_SESSION['lesson_id']; ?>
                                <div id="kt_app_content" class="app-content flex-column-fluid">
                                    <!--begin::Content container-->
                                    <div id="kt_app_content_container" class="app-container container-fluid">
                                        <!--begin::Card-->
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <label class="required fs-6 fw-semibold mb-2" for="cover_img">Görsel</label>
                                                <input type="file" id="cover_img" class="form-control " id="cover_img" accept=".png, .jpg, .jpeg, .PNG, .JPG, .JPEG" />

                                            </div>
                                            <div class="col-lg-4">
                                                <label class="required fs-6 fw-semibold mb-2" for="title">Başlık</label>
                                                <input type="text" class="form-control " placeholder="Test Başlığı" id="title">
                                            </div>

                                            <div class="col-lg-4">
                                                <label class="fs-6 fw-semibold mb-2" for="unit_id">Ünite Seçimi</label>
                                                <select class="form-select" id="unit_id" required>
                                                    <option value="">Ünite seçiniz</option>
                                                    <?php echo $chooseUnit->getUnitSelectList(); ?>
                                                </select>
                                            </div>
                                        </div>


                                        <div class="fv-row">
                                            <input class="form-select form-select-solid fw-bold" type="hidden" name="class_id" id="class_id" value="<?php echo $class_id; ?>">
                                        </div>
                                        <div class="fv-row">
                                            <input class="form-select form-select-solid fw-bold" type="hidden" name="lesson_id" id="lesson_id" value="<?php echo $lesson_id; ?>">
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-lg-4 mt-4">
                                                <label class="fs-6 fw-semibold mb-2" for="topic_id">Konu Seçimi</label>
                                                <select class="form-select" id="topic_id" required>
                                                    <option value="">Seçiniz</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-4 mt-4">
                                                <label class="fs-6 fw-semibold mb-2" for="subtopic_id">Alt Konu Seçimi</label>
                                                <select class="form-select" id="subtopic_id" required>
                                                    <option value="">Alt Konu seçiniz</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-4 mt-4">
                                                <label class="required fs-6 fw-semibold mb-2" for="start_date">Başlangıç Tarihi</label>

                                                <input type="datetime-local" class="form-control" id="start_date" name="start_date">
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-lg-4 mt-4">
                                                <label class="required fs-6 fw-semibold mb-2" for="end_date">Bitiş Tarihi</label>
                                                <input type="datetime-local" class="form-control" id="end_date" name="end_date">
                                            </div>

                                            <input type="hidden" id="option_count" value="0">

                                            <div class="col-lg-4 mt-4">
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



                                        <!--end::Card-->
                                    </div>
                                    <!--end::Content container-->
                                </div>

                            <?php } ?>
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
        <!-- Yükleme Spinner -->
        <div id="formSpinner" class="spinner-overlay">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Yükleniyor...</span>
    </div>
</div>

        <!--end::Custom Javascript-->
        <!--end::Javascript-->
    </body>
    <!--end::Body-->
    <script>
        let currentMaxQuestionIndex = 0;

        function getOptionLabels(count) {
            const labels = [];
            for (let i = 0; i < count; i++) {
                labels.push(String.fromCharCode(65 + i));
            }
            return labels;
        }

        function initTinyMCE(selector) {
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
                        onAction: function() {
                            editor.insertContent(
                                '<span style="display:inline-block; text-align:center;">' +
                                '<span style="display:block;">1</span>' +
                                '<span style="border-top:1px solid #000; display:block;">2</span>' +
                                '</span>'
                            );
                        }
                    });
                }
            });
        }


        function createVideoInput(index) {
            return `
    <div class="video-url-group mb-2 d-flex align-items-center gap-2">
        <input type="url" name="questions[${index}][videos][]" class="form-control" placeholder="Video URL" />
        <button type="button" class="btn btn-danger btn-sm remove-video-btn">Kaldır</button>
    </div>`;
        }

        function renumberQuestionsOnDelete() {
            document.querySelectorAll('.question-block').forEach((block, idx) => {
                const header = block.querySelector('.card-header h5');
                if (header) {
                    header.textContent = `Soru ${idx + 1}`;
                }

                block.dataset.questionIndex = idx;

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
                            tinymce.get(oldId).remove();
                        }
                        $(this).attr('id', newId);
                        initTinyMCE(`#${newId}`);
                    }
                });

                $(block).find('.add-option-image-btn').attr('data-index', idx);
                $(block).find('.add-video-btn').attr('data-index', idx);
                $(block).find('.add-image-btn').attr('data-index', idx);
                $(block).find('.correct-answer-select').attr('name', `questions[${idx}][correct_answer]`);
            });
        }

        function createImageInput(index) {
            return `
    <div class="image-upload-group mb-2 d-flex align-items-center gap-2">
        <input type="file" name="questions[${index}][images][]"   accept="image/*,audio/mpeg,audio/wav,audio/ogg" class="form-control" />
        <button type="button" class="btn btn-danger btn-sm remove-image-btn">Kaldır</button>
    </div>`;
        }

        function createOptionImageInput(questionIdx, optionLabel) {
            return `
        <div class="option-image-upload-group mb-2 d-flex align-items-center gap-2">
            <input type="file" name="questions[${questionIdx}][options][${optionLabel}][images][]" accept="image/*,audio/mpeg,audio/wav,audio/ogg" class="form-control" />
            <button type="button" class="btn btn-danger btn-sm remove-option-image-btn">Kaldır</button>
        </div>`;
        }

        function createOptionInputs(index, optionCount) {
            let html = '';
            let optionLabels = getOptionLabels(optionCount);

            optionLabels.forEach(label => {
                html += `<div class="option-block mb-3 border p-3 rounded">
            <label>Seçenek ${label}</label>
            <textarea name="questions[${index}][options][${label}][text]" id="option-tinymce-${index}-${label}" class="option-textarea form-control mb-2"></textarea>
            <div class="option-images-container">
                <label>Seçeneğe Görsel veya Ses Ekle</label>
                <div class="option-image-inputs">
                    ${createOptionImageInput(index, label)} 
                </div>
                <button type="button" class="btn btn-sm btn-secondary add-option-image-btn" data-index="${index}" data-label="${label}">+ Görsel veya Ses Ekle</button>
            </div>
        </div>`;
            });

            return html;
        }

        function createQuestionBlock(index, questionNumber) {
            const optionCount = parseInt($('#option_count').val()) || 3;
            const optionInputsHTML = createOptionInputs(index, optionCount);
            const optionLabels = getOptionLabels(optionCount);

            return `
    <div class="question-block mb-4 card border-primary shadow-sm" data-question-index="${index}">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Soru ${questionNumber}</h5>
            <button type="button" class="btn btn-danger btn-sm remove-question-btn">Kaldır</button>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Soru Metni</label>
                <textarea name="questions[${index}][text]" id="question-tinymce-${index}" class="question-textarea form-control"></textarea>
            </div>

            <div class="videos-container mb-3 border p-2 rounded bg-light">
                <label class="form-label">Video URL'leri</label>
                ${createVideoInput(index)}
                <button type="button" class="btn btn-sm btn-secondary add-video-btn" data-index="${index}">+ Video Ekle</button>
            </div>

            <div class="images-container mb-3 border p-2 rounded bg-light">
                <label class="form-label">Dosyalar</label>
                ${createImageInput(index)}
                <button type="button" class="btn btn-sm btn-secondary add-image-btn" data-index="${index}">+ Görsel veya Ses Ekle</button>
            </div>

            <div class="options-container border p-2 rounded bg-light">
                <h6 class="border-bottom pb-2">Seçenekler</h6>
                ${optionInputsHTML}
            </div>
            
            <div class="mt-3">
                <label class="form-label">Doğru Cevap Seçeneği <span class="text-danger">*</span></label>
                <select name="questions[${index}][correct_answer]" class="form-select correct-answer-select" required>
                    <option value="">Seçiniz</option>
                    ${optionLabels.map(label => `<option value="${label}">${label}</option>`).join('')}
                </select>
            </div>
        </div>
    </div>
    `;
        }

        $(document).ready(function() {
            updateMaxQuestionIndex();
            document.querySelectorAll('.question-block').forEach((block, idx) => {
                const header = block.querySelector('.card-header h5');
                if (header) {
                    header.textContent = `Soru ${idx + 1}`;
                }
                block.dataset.questionIndex = idx;
            });

            $('#class_id').on('change', function() {
                var classId = $(this).val();

                if (document.querySelectorAll('.question-block').length > 0) {
                    Swal.fire({
                        title: 'Emin misiniz?',
                        text: "Sınıf değişikliği tüm eklenen soruları silecektir!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Evet, sil ve değiştir!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            clearAllQuestionsAndRelatedFields(classId);
                        } else {
                            // Kullanıcı vazgeçerse, önceki sınıfı geri yükle
                            // $('#class_id').val($('#class_id').data('previous-value') || ''); // Eğer tutuyorsanız
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
                currentMaxQuestionIndex = 0;

                // Bu alanları temizle, ama zorunlu olmadıkları için varsayılan "Seçiniz" seçeneğine geri getir
                $('#lesson_id').html('<option value="">Ders seçiniz</option>');
                $('#unit_id').html('<option value="">Ünite seçiniz</option>');
                $('#topic_id').html('<option value="">Seçiniz</option>');
                $('#subtopic_id').html('<option value="">Alt Konu seçiniz</option>');
                $('#option_count').val('');

                fetchLessonsForClass(classId);
            }

            function fetchLessonsForClass(classId) {
                if (classId !== '') {
                    $.ajax({
                        url: 'includes/ajax.php?service=getLessonList1',
                        type: 'POST',
                        data: {
                            class_id: classId
                        },
                        dataType: 'json',
                        success: function(response) {
                            var lessonSelect = $('#lesson_id');
                            $('#option_count').val(response.data.optionCount); // response.data bir dizi
                            console.log(response.data.optionCount);

                            lessonSelect.empty();
                            lessonSelect.append('<option value="">Ders seçiniz</option>');

                            $.each(response.data.lessons, function(index, lesson) {
                                lessonSelect.append('<option value="' + lesson.id + '">' + lesson.name + '</option>');
                            });
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
                $unitSelect.empty().append('<option value="">Ünite seçiniz</option>'); // Ünite seçeneğini ekle
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

                $topicSelect.empty().append('<option value="">Seçiniz</option>'); // Konu seçeneğini ekle
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

                $subtopicSelect.empty().append('<option value="">Alt Konu seçiniz</option>'); // Alt Konu seçeneğini ekle

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
                        },
                        error: function(xhr) {
                            handleAjaxError(xhr);
                        }
                    });
                }
            });

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


            document.getElementById('submitForm').addEventListener('click', function() {
                const formData = new FormData();

                const classId = document.getElementById('class_id').value;
                // Ders, ünite, konu, alt konu artık zorunlu değil.
                const lessonId = document.getElementById('lesson_id').value;
                const unitId = document.getElementById('unit_id').value;
                const topicId = document.getElementById('topic_id').value;
                const subtopicId = document.getElementById('subtopic_id').value;

                let formGeneralValid = true;

                // Sadece class_id zorunlu kalmaya devam ediyor
                if (classId === "") {
                    Swal.fire('Uyarı', 'Lütfen bir sınıf seçiniz.', 'warning');
                    formGeneralValid = false;
                    $('#class_id').addClass('is-invalid');
                } else {
                    $('#class_id').removeClass('is-invalid');
                    formData.append('class_id', classId);
                }

                // Ders, ünite, konu ve alt konu zorunlu değilse bile form verisine eklemeye devam edelim
                formData.append('lesson_id', lessonId);
                formData.append('unit_id', unitId);
                formData.append('topic_id', topicId);
                formData.append('subtopic_id', subtopicId);

                // Diğer zorunlu alanlar: başlık, başlangıç tarihi, bitiş tarihi
                const title = document.getElementById('title').value;
                const startDate = document.getElementById('start_date').value;
                const endDate = document.getElementById('end_date').value;
                const status = document.getElementById('status').value;
                formData.append('status', status);
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
                }

                let questionsValid = true;

                document.querySelectorAll('.question-block').forEach((questionBlock) => {
                    const qIdx = parseInt(questionBlock.dataset.questionIndex);

                    const questionTextarea = questionBlock.querySelector('.question-textarea');
                    if (questionTextarea && tinymce.get(questionTextarea.id)) {
                        formData.append(`questions[${qIdx}][text]`, tinymce.get(questionTextarea.id).getContent());
                    }

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

                    questionBlock.querySelectorAll('.video-url-group input[type="url"]').forEach((input, vIdx) => {
                        if (input.value) {
                            formData.append(`questions[${qIdx}][videos][${vIdx}]`, input.value);
                        }
                    });

                    questionBlock.querySelectorAll('.image-upload-group input[type="file"]').forEach((input, iIdx) => {
                        if (input.files && input.files[0]) {
                            formData.append(`questions[${qIdx}][images][${iIdx}]`, input.files[0]);
                        }
                    });

                    questionBlock.querySelectorAll('.option-block').forEach((optionBlock) => {
                        const optionTextarea = optionBlock.querySelector('.option-textarea');
                        const optionLabelMatch = optionTextarea.name.match(/\[options\]\[([A-Z])\]/);
                        if (optionLabelMatch && optionLabelMatch[1]) {
                            const optionLabel = optionLabelMatch[1];

                            if (tinymce.get(optionTextarea.id)) {
                                formData.append(`questions[${qIdx}][options][${optionLabel}][text]`, tinymce.get(optionTextarea.id).getContent());
                            }

                            optionBlock.querySelectorAll('.option-image-upload-group input[type="file"]').forEach((input, optImgIdx) => {
                                if (input.files && input.files[0]) {
                                    formData.append(`questions[${qIdx}][options][${optionLabel}][images][${optImgIdx}]`, input.files[0]);
                                }
                            });
                        }
                    });
                });

                if (!formGeneralValid) {
                    // Genel form alanlarında hata varsa
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
    document.getElementById('formSpinner').style.display = 'flex';

                $.ajax({
                    url: 'includes/ajax-ayd.php?service=testAdd',
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
                                text: 'Form başarıyla gönderildi!',
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
                    },
        complete: function() {
            // 🔴 Spinner'ı gizle
            document.getElementById('formSpinner').style.display = 'none';
        }
                });
            });

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

                const newlyAddedQuestionBlock = questionsContainer.lastElementChild;
                const questionTextarea = newlyAddedQuestionBlock.querySelector('.question-textarea');
                if (questionTextarea) {
                    initTinyMCE(`#${questionTextarea.id}`);
                }
                newlyAddedQuestionBlock.querySelectorAll('.option-textarea').forEach(textarea => {
                    initTinyMCE(`#${textarea.id}`);
                });
            });

            document.getElementById('questions_container').addEventListener('click', function(event) {
                const target = event.target;

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
                        renumberQuestionsOnDelete();
                        updateMaxQuestionIndex();
                    }
                }
                if (target.classList.contains('add-video-btn')) {
                    const questionBlock = target.closest('.question-block');
                    const idx = questionBlock.dataset.questionIndex;
                    const videoInputHTML = createVideoInput(idx);
                    target.insertAdjacentHTML('beforebegin', videoInputHTML);
                }

                if (target.classList.contains('remove-video-btn')) {
                    const videoGroup = target.closest('.video-url-group');
                    if (videoGroup) videoGroup.remove();
                }

                if (target.classList.contains('add-image-btn')) {
                    const questionBlock = target.closest('.question-block');
                    const idx = questionBlock.dataset.questionIndex;
                    const imageInputHTML = createImageInput(idx);
                    target.insertAdjacentHTML('beforebegin', imageInputHTML);
                }

                if (target.classList.contains('remove-image-btn')) {
                    const imageGroup = target.closest('.image-upload-group');
                    if (imageGroup) imageGroup.remove();
                }

                if (target.classList.contains('add-option-image-btn')) {
                    const idx = target.getAttribute('data-index');
                    const label = target.getAttribute('data-label');
                    const optionImageInputsContainer = target.parentElement.querySelector('.option-image-inputs');
                    if (optionImageInputsContainer) {
                        optionImageInputsContainer.insertAdjacentHTML('beforeend', createOptionImageInput(idx, label));
                    }
                }

                if (target.classList.contains('remove-option-image-btn')) {
                    const optionImageGroup = target.closest('.option-image-upload-group');
                    if (optionImageGroup) optionImageGroup.remove();
                }
            });
        });
    </script>

</html>
<?php } else {
    header("location: index");
}
