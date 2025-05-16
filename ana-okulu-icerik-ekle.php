<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 10001  )) {
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";

    include_once "views/pages-head.php";
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
                                    <!--begin::Card-->
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
                                                <label class="fs-6 fw-semibold mb-2" for="main_school_class_id">Yaş Grubu  </label>
                                                <?php
                                                $class = new Classes();
                                                $mainSchoolClasses= $class->getAgeGroup();
                                                ?>
                                                <select class="form-select" id="main_school_class_id" required aria-label="Default select example">
                                                    <option value="">Seçiniz</option>
                                                    <?php foreach ($mainSchoolClasses as $c) { ?>
                                                        <option value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                                <div class="col-lg-4">
                                                    <label class="required fs-6 fw-semibold mb-2" for="month">Ay </label>

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
                                                        <option value="Aralık">Aralık2</option>

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
                                                    <label class=" fs-6 fw-semibold mb-2" for="content_description">İçerik Açıklaması</label>
                                                    <textarea class="form-control" name="content_description" id="content_description" rows="4"></textarea>
                                                </div>
                                            </div>
                                            <div class="row mt-4">
                                                <label class="required fs-6 fw-semibold mb-2" for="chooseOne">İçerik Türü</label>
                                                <div class="fv-row mb-7 mt-4" id="chooseOne">
                                                    <label>
                                                        <input class="form-check-input" type="radio" name="secim" value="video_link"> Video URL
                                                    </label>
                                                    <label>
                                                        <input class="form-check-input ms-10" type="radio" name="secim" value="file_path"> Dosya Yükle
                                                    </label>
                                                    <label>
                                                        <input class="form-check-input ms-10" type="radio" name="secim" value="content"> Text
                                                    </label>
                                                </div>

                                                <!-- Video URL input -->
                                                <div id="videoInput" class="mb-4" style="display:none;">
                                                    <label for="video_url">Video Id:</label>
                                                    <input type="text" class="form-control" name="video_url" id="video_url">
                                                </div>

                                                <!-- File upload input -->
                                                <div id="fileInput" class="mb-4" style="display:none;">
                                                    <label for="file_path">Dosya Yükle:</label>
                                                    <input type="file" class="form-control" name="file_path[]" id="file_path" multiple accept=".xls,.xlsx,.doc,.docx,.ppt,.pptx,.png,.jpeg,.jpg,.svg">
                                                </div>

                                                <!-- Açıklama alanlarının ekleneceği yer -->
                                                <div id="fileDescriptions"></div>
                                            </div>

                                            <!-- Textarea input -->
                                            <div id="textInput" class="mb-4" style="display:none;">
                                                <label for="content">Metin İçeriği:</label>
                                                <textarea class="form-control" name="content" id="content" rows="4"></textarea>
                                            </div>
                                    </div>


                                    <div class="row mt-5">
                                        <div class="col-lg-11"></div>
                                        <div class="col-lg-1">
                                            <button type="button" id="submitForm" class="btn btn-primary">Kaydet</button>

                                        </div>
                                    </div>
                                    </form>


                                </div>
                                <!--end::Card-->
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
                $('#file_path').on('change', function() {
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
                    $('#videoInput, #fileInput, #textInput').hide();

                    // Seçime göre ilgili inputu göster
                    if (selected === 'video_link') {
                        $('#videoInput').show();
                    } else if (selected === 'file_path') {
                        $('#fileInput').show();
                    } else if (selected === 'content') {
                        $('#textInput').show();
                    }
                });
                $('#submitForm').on('click', function(e) {
                    e.preventDefault();

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

                    // month kontrolü
                    if ($('#month').val() === '') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Uyarı',
                            text: 'Lütfen bir ay seçin.',
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

                    formData.append('month', $('#month').val());
                    formData.append('week', $('#week').val());
                    formData.append('activity_title', $('#activity_title').val());
                    formData.append('content_title', $('#content_title').val());
                    formData.append('concept_title', $('#concept_title').val());
                    formData.append('subject', $('#subject').val());
                    formData.append('content_description', $('#content_description').val());
                    formData.append('main_school_class_id', $('#main_school_class_id').val());
                    let selectedType = $('input[name="secim"]:checked').val();
                    formData.append('secim', selectedType);

                    if (selectedType === 'video_link') {
                        formData.append('video_url', $('#video_url').val());
                    } else if (selectedType === 'file_path') {
                        const files = $('#file_path')[0].files;
                        $("textarea[name='descriptions[]']").each(function() {
                            formData.append('descriptions[]', $(this).val());
                        }); // .get() ile jQuery nesnesinden normal diziye çevir



                        for (let i = 0; i < files.length; i++) {
                            formData.append('file_path[]', files[i]);
                        }
                    } else if (selectedType === 'content') {

                        formData.append('content', tinymce.get('content').getContent());

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
        <!--end::Custom Javascript-->
        <!--end::Javascript-->
    </body>
    <!--end::Body-->

</html>
<?php } else {
    header("location: index");
}
