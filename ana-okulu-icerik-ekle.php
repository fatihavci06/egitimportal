<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 10001)) {
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";

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
                                    <div class="card-body pt-5">
                                        <form class="form" action="#" id="ContentForm">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <label class="required fs-6 fw-semibold mb-2" for="roles">Görüntüleme Yetkisi</label>
                                                    <?php
                                                    $rolesList = new Classes();
                                                    $rolesList = $rolesList->getRoles();
                                                    ?>
                                                    <select class="form-select" id="roles" multiple aria-label="multiple select example">
                                                        <?php foreach ($rolesList as $role) { ?>
                                                            <option value="<?= $role['id'] ?>"><?= $role['name'] ?></option>
                                                        <?php } ?>

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col-lg-4">
                                                    <label class="fs-6 fw-semibold mb-2" for="main_school_class_id">Yaş Grubu </label>
                                                    <?php
                                                    $class = new Classes();
                                                    $mainSchoolClasses = $class->getAgeGroup();
                                                    ?>
                                                    <select class="form-select" id="main_school_class_id" required aria-label="Default select example">
                                                        <option value="">Seçiniz</option>
                                                        <?php foreach ($mainSchoolClasses as $c) { ?>
                                                            <option value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-lg-4">

                                                    <label for="lesson_id" class=" required form-label">Ders Adı</label>
                                                    <select class="form-select form-control" id="lesson_id" name="lesson_id">
                                                        <option selected disabled>Önce yaş grubu seçiniz...</option>
                                                    </select>

                                                </div>
                                                <div class="col-lg-4" id="unit_div"> <label for="unit_id" class=" form-label">Ünite Adı</label> <select class="form-select form-control" id="unit_id" name="lesson_id">
                                                        <option selected disabled>Önce ders seçiniz...</option>
                                                    </select>

                                                </div>

                                            </div>

                                            <div class="row mt-4">
                                                <div class="col-lg-4" id="topic_div"> <label for="topic_id" class=" form-label">Konu Adı</label> <select class="form-select form-control" id="topic_id" name="lesson_id">
                                                        <option selected disabled>Önce ünite seçiniz...</option>
                                                    </select>

                                                </div>
                                                <div class="col-lg-4">
                                                    <label class=" fs-6 fw-semibold mb-2" for="month">Ay </label>

                                                    <select class="form-select" id="month" required aria-label="Default select example">
                                                        <option value="">Seçiniz</option>
                                                        <option value="Ocak">Ocak</option>
                                                        <option value="Şubat">Şubat</option>
                                                        <option value="Mart">Mart</option>
                                                        <option value="Nisan">Nisan</option>
                                                        <option value="Mayıs">Mayıs</option>
                                                        <option value="Haziran">Haziran</option>
                                                        <option value="Temmuz">Temmuz</option>
                                                        <option value="Ağustos">Ağustos</option>
                                                        <option value="Eylül">Eylül</option>
                                                        <option value="Ekim">Ekim</option>
                                                        <option value="Kasım">Kasım</option>
                                                        <option value="Aralık">Aralık</option>

                                                    </select>
                                                </div>
                                                <div class="col-lg-4">
                                                    <label class="fs-6 fw-semibold mb-2" for="week">Özel Hafta Seçimi </label>
                                                    <?php
                                                    $class = new Classes();
                                                    $weekList = $class->getWeekList();
                                                    ?>
                                                    <select class="form-select" id="week" required aria-label="Default select example">
                                                        <option value="">Seçiniz</option>
                                                        <?php foreach ($weekList as $week) { ?>
                                                            <option value="<?= $week['id'] ?>"><?= $week['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row mt-4">
                                                <div class="col-lg-4">
                                                    <label class=" fs-6 fw-semibold mb-2" for="activity_type">Etkinlik Türü Başlığı</label>
                                                    <select class="form-select form-control" id="activity_title" aria-label="Default select example">
                                                        <option value="">Seçiniz</option>
                                                        <?php
                                                        $activityTitle = $class->getTitleList(3);
                                                        foreach ($activityTitle as $title) {
                                                        ?>
                                                            <option value="<?= $title['id'] ?>"><?= $title['title'] ?></option>
                                                        <?php }  ?>

                                                    </select>


                                                </div>
                                                <div class="col-lg-4">
                                                    <label class=" fs-6 fw-semibold mb-2" for="content_title">İçerik Başlığı</label>
                                                    <select class="form-select form-control" id="content_title" aria-label="Default select example">
                                                        <option value="">Seçiniz</option>

                                                        <?php
                                                        $titlesContent = $class->getTitleList(1);
                                                        foreach ($titlesContent as $title) {
                                                        ?>
                                                            <option value="<?= $title['id'] ?>"><?= $title['title'] ?></option>
                                                        <?php }  ?>
                                                    </select>
                                                </div>
                                                <div class="col-lg-4">
                                                    <label class=" fs-6 fw-semibold mb-2" for="concept_title">Kavram Başlığı</label>
                                                    <select class="form-select form-control" id="concept_title" aria-label="Default select example">
                                                        <option value="">Seçiniz</option>

                                                        <?php
                                                        $titlesContent = $class->getTitleList(2);
                                                        foreach ($titlesContent as $title) {
                                                        ?>
                                                            <option value="<?= $title['id'] ?>"><?= $title['title'] ?></option>
                                                        <?php }  ?>
                                                    </select>
                                                </div>

                                            </div>
                                            <div class="row mt-4">
                                                <div class="col-lg-6">
                                                    <label class="required fs-6 fw-semibold mb-2" for="subject">Konu</label>
                                                    <input type="text" class="form-control " placeholder="Konu Başlığı" id="subject" />
                                                </div>
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col-lg-12">
                                                    <label class=" fs-6 fw-semibold mb-2" for="summary">İçerik Özet</label>
                                                    <textarea class="form-control" name="summary" id="summary" rows="4"></textarea>
                                                </div>
                                            </div>
                                            <div class="row mt-4">
                                                <label class="required fs-6 fw-semibold mb-2" for="chooseOne">İçerik Türü</label>
                                                <div class="fv-row mb-7 mt-4" id="chooseOne">

                                                    <label>
                                                        <input class="form-check-input" type="radio" name="secim" value="primary_img"> İlk Görsel
                                                    </label>
                                                    <label>
                                                        <input class="form-check-input ms-10" type="radio" name="secim" value="video_link"> Video URL
                                                    </label>
                                                    <label>
                                                        <input class="form-check-input ms-10" type="radio" name="secim" value="file_path"> Dosya Yükle
                                                    </label>
                                                    <label>
                                                        <input class="form-check-input ms-10" type="radio" name="secim" value="content"> Text
                                                    </label>
                                                    <label>
                                                        <input class="form-check-input ms-10" type="radio" name="secim" value="wordwall"> Interaktif Oyun
                                                    </label>
                                                </div>

                                                <div id="videoInput" class="mb-4" style="display:none;">
                                                    <label for="video_url">Video Link(Youtube,Vimeo):</label>
                                                    <input type="text" class="form-control" name="video_url" id="video_url">
                                                </div>
                                                <div id="primary_image" class="mb-4" style="display:none;">
                                                    <label for="primary_img">Görsel:</label>
                                                    <input type="file" class="form-control" name="images[]" id="images" multiple accept=".png,.jpeg,.jpg,.svg">
                                                </div>

                                                <div id="fileInput" class="mb-4" style="display:none;">
                                                    <label for="file_path">Dosya Yükle:</label>
                                                    <input type="file" class="form-control" name="file_path[]" id="files" multiple accept=".xls,.xlsx,.doc,.docx,.ppt,.pptx,.png,.jpeg,.jpg,.svg">
                                                    <div id="fileDescriptions"></div>
                                                </div>
                                                <div id="wordwallInputs" class="mb-4" style="display:none;">
                                                    <label>WordWall Iframe Linkleri:</label>
                                                    <div id="dynamicFields">
                                                        <div class="input-group mb-2" data-index="0">
                                                            <input type="text" name="wordWallTitles[]" class="form-control me-2" placeholder="Başlık">
                                                            <input type="text" name="wordWallUrls[]" class="form-control me-2" placeholder="URL">
                                                            <button type="button" class="btn btn-danger removeField">Sil</button>
                                                        </div>
                                                    </div>
                                                    <button type="button" id="addField" class="btn btn-sm btn-primary mt-2">Ekle</button>
                                                </div>

                                                </div>

                                            <div id="textInput" class="mb-4" style="display:none;">
                                                <label for="mcontent">Metin İçeriği:</label>
                                                <textarea class="form-control tinymce-editor" name="mcontent" id="mcontent" rows="4"></textarea>
                                            </div>
                                    </div>


                                    <div class="row mt-5">
                                        <div class="col-lg-11"></div>
                                        <div class="col-lg-1">
                                            <button type="button" id="submitForm" class="btn btn-primary btn-sm">Kaydet</button>

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
        <script src="assets/js/custom/apps/subtopics/list/export.js"></script>
        <script src="assets/js/custom/apps/subtopics/list/list.js"></script>
        <script src="assets/js/custom/apps/subtopics/list/topicadd.js"></script>
        <script src="assets/js/custom/apps/subtopics/add.js"></script>
        <script src="assets/js/custom/apps/subtopics/create.js"></script>
        <script src="assets/js/widgets.bundle.js"></script>
        <script src="assets/js/custom/widgets.js"></script>
        <script src="assets/js/custom/apps/chat/chat.js"></script>
        <script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
        <script src="assets/js/custom/utilities/modals/create-account.js"></script>
        <script src="assets/js/custom/utilities/modals/create-app.js"></script>
        <script src="assets/js/custom/utilities/modals/users-search.js"></script>
        <script>
            $(document).ready(function() {
                $('#main_school_class_id').on('change', function () {
    var selectedClassId = $(this).val();

    // Yeni: Ünite ve Konu alanlarını görünür yap (Yaş grubu değiştiğinde sıfırlanır)
    $('#unit_div, #topic_div').show();

    // AJAX isteği
    $.ajax({
        url: 'includes/ajax.php?service=mainSchoolGetLessons',
        type: 'POST',
        data: {
            class_id: selectedClassId
        },
        dataType: 'json',
        success: function (response) {
            var lessonSelect = $('#lesson_id');
            lessonSelect.empty();

            if (response.status === 'success') {
                if (response.data.length > 0) {
                    lessonSelect.append('<option selected disabled>Ders Seçiniz...</option>');
                    $.each(response.data, function (index, lesson) {
                        lessonSelect.append(
                            $('<option></option>').val(lesson.id).text(lesson.name)
                        );
                    });
                } else {
                    lessonSelect.append('<option disabled>Bu sınıfa ait ders bulunamadı.</option>');
                }
            }
        },
        error: function () {
            alert('Sunucu ile iletişimde hata oluştu!');
        }
    });
});

                $('#lesson_id').on('change', function() {
                    var selectedLessonId = $(this).val();
                    var selectedClassId = $('#main_school_class_id').val();
                    
                    // Ders Adı metnini al (Türkçe kontrolü için)
                    const selectedLessonText = $(this).find('option:selected').text().trim();

                    // Türkçe dersi kontrolü
                    if (selectedLessonText === 'Türkçe') {
                        // Ünite ve Konu alanlarını gizle
                        $('#unit_div, #topic_div').hide();
                        // Değerlerini temizle
                        $('#unit_id, #topic_id').val('');
                        
                        // Türkçe ise, AJAX isteği yapmaya gerek yok, return ile çık.
                        return; 
                    } else {
                        // Türkçe değilse tekrar görünür yap
                        $('#unit_div, #topic_div').show();
                    }
                    
                    $.ajax({
                        url: 'includes/ajax.php?service=mainSchoolGetUnits', // Backend dosyanın yolu
                        type: 'POST',
                        data: {
                            class_id: selectedClassId,
                            lesson_id: selectedLessonId
                        },
                        dataType: 'json', // JSON olarak bekliyoruz
                        success: function(response) {
                            if (response.status === 'success') {
                                var unitSelect = $('#unit_id');
                                unitSelect.empty(); // Önceki optionları temizle

                                if (response.data.length > 0) {
                                    unitSelect.append('<option selected disabled>Ünite Seçiniz...</option>');

                                    // Gelen datayı option olarak ekle
                                    $.each(response.data, function(index, lesson) {
                                        unitSelect.append(
                                            $('<option></option>')
                                            .val(lesson.id)
                                            .text(lesson.name)
                                        );
                                    });
                                } else {
                                    unitSelect.append('<option disabled>Bu derse ait ünite bulunamadı.</option>');
                                }
                            } else {
                            }
                        },
                        error: function() {
                            alert('Sunucu ile iletişimde hata oluştu!');
                        }
                    });
                });
                $('#unit_id').on('change', function() {
                    var selectedUnitId = $(this).val();

                    $.ajax({
                        url: 'includes/ajax.php?service=mainSchoolGetTopics', // Backend dosyanın yolu
                        type: 'POST',
                        data: {
                            unit_id: selectedUnitId
                        },
                        dataType: 'json', // JSON olarak bekliyoruz
                        success: function(response) {
                            if (response.status === 'success') {
                                var topicSelect = $('#topic_id');
                                topicSelect.empty(); // Önceki optionları temizle

                                if (response.data.length > 0) {
                                    console.log(response.data);
                                    topicSelect.append('<option selected disabled>Konu Seçiniz...</option>');

                                    // Gelen datayı option olarak ekle
                                    $.each(response.data, function(index, topic) {
                                        topicSelect.append(
                                            $('<option></option>')
                                            .val(topic.id)
                                            .text(topic.name)
                                        );
                                    });
                                } else {
                                    topicSelect.append('<option disabled>Bu üniteye ait konu bulunamadı.</option>');
                                }
                            } else {
                            }
                        },
                        error: function() {
                            alert('Sunucu ile iletişimde hata oluştu!');
                        }
                    });
                });

                let fieldCount = 0;

                $('#addField').on('click', function() {
                    fieldCount++;
                    $('#dynamicFields').append(`
                    <div class="input-group mb-2" data-index="${fieldCount}">
                        <input type="text" name="wordWallTitles[]" class="form-control me-2" placeholder="Başlık">
                        <input type="text" name="wordWallUrls[]" class="form-control me-2" placeholder="URL">
                        <button type="button" class="btn btn-danger removeField">Sil</button>
                    </div>
                `);
                });
                $('#dynamicFields').on('click', '.removeField', function() {
                    $(this).closest('.input-group').remove();
                });
                tinymce.init({
                    selector: '.tinymce-editor',
                    // diğer ayarlar...
                });

                $('#files').on('change', function() {
                    const files = this.files;
                    const container = $('#fileDescriptions');
                    container.empty(); // Önceki açıklamaları temizle

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
                $('input[name="secim"]').on('change', function() {
                    let selected = $(this).val();

                    // Tüm inputları gizle
                    $('#videoInput, #fileInput,#primary_image, #textInput, #wordwallInputs').hide();

                    // Seçime göre ilgili inputu göster
                    if (selected === 'video_link') {
                        $('#videoInput').show();
                    } else if (selected === 'primary_img') {

                        $('#primary_image').show();

                    } else if (selected === 'file_path') {
                        $('#fileInput').show();
                    } else if (selected === 'content') {
                        $('#textInput').show();
                    } else if (selected === 'wordwall') {
                        $('#wordwallInputs').show();
                    }
                });
                $('#submitForm').on('click', function(e) {
                    e.preventDefault();
                    const content = tinymce.get('mcontent').getContent();

                    // ✅ roles kontrolü (en az 1 seçim)
                    const selectedRoles = $('#roles option:selected');
                    if (selectedRoles.length === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Uyarı',
                            text: 'Lütfen en az bir görüntüleme yetkisi seçin.',
                            confirmButtonText: 'Tamam'
                        });
                        return;
                    }



                    // subject kontrolü
                    if ($('#subject').val().trim() === '') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Uyarı',
                            text: 'Lütfen konu başlığını girin.',
                            confirmButtonText: 'Tamam'
                        });
                        return;
                    }

                    // içerik türü kontrolü
                    const selectedContentType = $('input[name="secim"]:checked').val();
                    if (!selectedContentType) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Uyarı',
                            text: 'Lütfen bir içerik türü seçin.',
                            confirmButtonText: 'Tamam'
                        });
                        return;
                    }

                    // FormData nesnesi (dosya da gönderilecekse kullanılır)
                    let formData = new FormData();

                    // roles (çoklu)
                    selectedRoles.each(function() {
                        formData.append('roles[]', $(this).val());
                    });
                    let isValid = true;
                    let errorMessage = '';

                    $('#dynamicFields .input-group').each(function(index) {
                        const titleInput = $(this).find('input[name="wordWallTitles[]"]');
                        const urlInput = $(this).find('input[name="wordWallUrls[]"]');

                        const titleValue = titleInput.val().trim();
                        const urlValue = urlInput.val().trim();

                        if (urlValue !== '' && titleValue === '') {
                            isValid = false;
                            errorMessage = 'Lütfen bağlantı girilen her satır için bir başlık yazınız.';
                            return false; // döngüyü kır
                        }
                    });

                    if (!isValid) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Eksik Alan',
                            text: errorMessage
                        });
                        return; // form gönderimini durdur
                    }

                    // 🔽 Validasyon geçerse FormData eklemesi yapılabilir
                    $('#dynamicFields .input-group').each(function(index) {
                        const title = $(this).find('input[name="wordWallTitles[]"]').val();
                        const url = $(this).find('input[name="wordWallUrls[]"]').val();

                        formData.append(`wordWallTitles[${index}]`, title);
                        formData.append(`wordWallUrls[${index}]`, url);
                    });

                    formData.append('month', $('#month').val());
                    formData.append('week', $('#week').val());
                    
                    // Türkçe dersi seçilse bile bu değerler gönderilmeli (boş olarak)
                    formData.append('lesson_id', $('#lesson_id').val());
                    formData.append('unit_id', $('#unit_id').val());
                    formData.append('topic_id', $('#topic_id').val());

                    formData.append('activity_title', $('#activity_title').val());
                    formData.append('content_title', $('#content_title').val());
                    formData.append('concept_title', $('#concept_title').val());
                    formData.append('subject', $('#subject').val());
                    formData.append('content', content);
                    formData.append('content_description', $('#summary').val());
                    formData.append('main_school_class_id', $('#main_school_class_id').val());
                    let selectedType = $('input[name="secim"]:checked').val();
                    formData.append('secim', selectedType);
                    formData.append('video_url', $('#video_url').val());
                    const files = $('#files')[0].files;

                    // Dosyaları tek tek formData'ya ekle
                    for (let i = 0; i < files.length; i++) {
                        formData.append('file_path[]', files[i]);
                    }

                    // Açıklamaları da ekle
                    $("textarea[name='descriptions[]']").each(function() {
                        formData.append('descriptions[]', $(this).val());
                    });
                    const images = $('#images')[0].files;

                    // Dosyaları tek tek formData'ya ekle
                    for (let i = 0; i < images.length; i++) {
                        formData.append('images[]', images[i]);
                    }





                    // AJAX gönderimi
                    $.ajax({
                        url: 'includes/addmainschoolcontent.inc.php', // <- kendi endpoint'in ile değiştir
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            let res = JSON.parse(response);

                            if (res.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Başarılı',
                                    text: 'Form başarıyla gönderildi!',
                                    confirmButtonText: 'Tamam'
                                }).then(() => {
                                    location.reload();
                                    // Sayfayı yenile
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Hata',
                                    text: res.message || 'Beklenmeyen bir hata oluştu.',
                                    confirmButtonText: 'Tamam'
                                });
                            }

                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Uyarı',
                                text: 'Form Gönderilirken Hata Oldu.',
                                confirmButtonText: 'Tamam'
                            });
                            return;
                        }
                    });
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                $('#week').select2({
                    placeholder: "Seçiniz",
                    allowClear: true
                });
                $('#activity_title').select2({
                    placeholder: "Seçiniz",
                    allowClear: true
                });
                $('#content_title').select2({
                    placeholder: "Seçiniz",
                    allowClear: true
                });
                $('#concept_title').select2({
                    placeholder: "Seçiniz",
                    allowClear: true
                });


            });
        </script>
        </body>
    </html>
<?php } else {
    header("location: index");
}

?>