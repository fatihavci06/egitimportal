<!DOCTYPE html>
<html lang="tr">
<?php

session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 3)) {
    include_once "classes/dbh.classes.php";
    include_once "classes/games.classes.php";
    include_once "classes/games-view.classes.php";
    include_once "views/pages-head.php";

    include_once "classes/classes.classes.php";
    include_once "classes/classes-view.classes.php";
    include_once "classes/lessons.classes.php";
    include_once "classes/lessons-view.classes.php";

    $chooseClass = new ShowClass();
    $chooseLesson = new ShowLesson();

    $gameObj = new ShowGame();

    $gameDb = new Games();

    $game_slug = isset($_GET['q']) ? filter_var($_GET['q'], FILTER_SANITIZE_STRING) : '';

    $currentGame = $gameDb->getOneGame($game_slug);
    ?>

    <body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true"
        data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
        data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true"
        data-kt-app-aside-enabled="true" data-kt-app-aside-fixed="true" data-kt-app-aside-push-toolbar="true"
        data-kt-app-aside-push-footer="true" class="app-default">
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
                                    <div class="card">
                                        <div class="card-body pt-0">
                                            <table class="table align-middle table-row-dashed fs-6 gy-5"
                                                id="kt_customers_table">
                                                <thead>
                                                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                                        <th class="min-w-40px">Görsel</th>
                                                        <th class="min-w-125px">Oyun Adı</th>
                                                        <th class="min-w-40px">Durum</th>
                                                        <th class="min-w-80px">Sınıf</th>
                                                        <th class="min-w-125px">Ders</th>
                                                        <th class="min-w-125px">Ünite</th>
                                                        <th class="min-w-125px">Konu</th>
                                                        <th class="min-w-125px">Altkonu</th>
                                                        <th class="text-end min-w-70px">İşlemler</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="fw-semibold text-gray-600">
                                                    <?php $gameObj->showOneGame($game_slug) ?>
                                                </tbody>
                                            </table>
                                            <!--end::Table-->
                                        </div>
                                        <!--end::Card body-->
                                    </div>
                                </div>
                                <!--end::Content-->
                            </div>

                            <!-- Modal -->
                            <div class="modal fade" id="kt_modal_update_customer" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered mw-650px">
                                    <div class="modal-content">
                                        <form class="form" action="#" id="kt_modal_update_customer_form"
                                            data-kt-redirect="oyunlar">

                                            <input type="hidden" name="old_slug" value="<?php echo $game_slug; ?>">

                                            <div class="modal-header" id="kt_modal_update_customer_header">
                                                <h2 class="fw-bold">Oyun Güncelle</h2>

                                                <div id="kt_modal_update_customer_close"
                                                    class="btn btn-icon btn-sm btn-active-icon-primary">
                                                    <i class="ki-duotone ki-cross fs-1">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </div>
                                            </div>

                                            <div class="modal-body py-10 px-lg-17">
                                                <div class="scroll-y me-n7 pe-7" id="kt_modal_update_customer_scroll"
                                                    data-kt-scroll="true"
                                                    data-kt-scroll-activate="{default: false, lg: true}"
                                                    data-kt-scroll-max-height="auto"
                                                    data-kt-scroll-dependencies="#kt_modal_update_customer_header"
                                                    data-kt-scroll-wrappers="#kt_modal_update_customer_scroll"
                                                    data-kt-scroll-offset="300px">
                                                    <div class="mb-7">
                                                        <label class="fs-6 fw-semibold mb-3">
                                                            <span>Görsel</span>
                                                            <span class="ms-1" data-bs-toggle="tooltip"
                                                                title="İzin verilen dosya türleri: png, jpg, jpeg.">
                                                                <i class="ki-duotone ki-information fs-7">
                                                                    <span class="path1"></span>
                                                                    <span class="path2"></span>
                                                                    <span class="path3"></span>
                                                                </i>
                                                            </span>
                                                        </label>
                                                        <div class="mt-1">
                                                            <style>
                                                                .image-input-placeholder {
                                                                    background-image: url('assets/media/svg/files/blank-image.svg');
                                                                }

                                                                [data-bs-theme="dark"] .image-input-placeholder {
                                                                    background-image: url('assets/media/svg/files/blank-image-dark.svg');
                                                                }
                                                            </style>

                                                            <div class="image-input image-input-outline image-input-placeholder image-input-empty image-input-empty"
                                                                data-kt-image-input="true">
                                                                <div class="image-input-wrapper w-100px h-100px"
                                                                    style="background-image: url(assets/media/games/<?php echo $currentGame['cover_img']; ?>)">
                                                                </div>

                                                                <label
                                                                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                                    data-kt-image-input-action="change"
                                                                    data-bs-toggle="tooltip" title="Görsel Ekle">
                                                                    <i class="ki-duotone ki-pencil fs-7">
                                                                        <span class="path1"></span>
                                                                        <span class="path2"></span>
                                                                    </i>
                                                                    <input type="file" name="photo" id="photo"
                                                                        value="<?php echo $currentGame['cover_img']; ?>"
                                                                        accept=".png, .jpg, .jpeg, .PNG, .JPG, .JPEG"
                                                                        value="<?php echo $game_slug; ?>" />
                                                                    <input type="hidden" name="avatar_remove" />
                                                                </label>

                                                                <span
                                                                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                                    data-kt-image-input-action="cancel"
                                                                    data-bs-toggle="tooltip" title="Fotoğrafı İptal Et">
                                                                    <i class="ki-duotone ki-cross fs-2">
                                                                        <span class="path1"></span>
                                                                        <span class="path2"></span>
                                                                    </i>
                                                                </span>

                                                                <span
                                                                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                                    data-kt-image-input-action="remove"
                                                                    data-bs-toggle="tooltip" title="Remove avatar">
                                                                    <i class="ki-duotone ki-cross fs-2">
                                                                        <span class="path1"></span>
                                                                        <span class="path2"></span>
                                                                    </i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="fv-row mb-7">
                                                        <label class="required fs-6 fw-semibold mb-2">Oyun</label>

                                                        <input type="text" id="name" class="form-control form-control-solid"
                                                            value="<?php echo $currentGame['game_name']; ?>"
                                                            placeholder="Sesli Kitap Adı" name="name" />
                                                    </div>

                                                    <div class="fv-row mb-7">

                                                        <label class="required fs-6 fw-semibold mb-2">iframe Kodu</label>

                                                        <input type="text" id="iframe"
                                                            class="form-control form-control-solid"
                                                            value="<?php echo htmlspecialchars($currentGame['game_url']); ?>"
                                                            placeholder="iframe Kodunu Yazın" name="iframe" />
                                                    </div>

                                                    <div class="d-flex flex-column mb-7 fv-row">
                                                        <label class="fs-6 fw-semibold mb-2">Sınıf</label>


                                                        <select id="classes" name="classes" aria-label="Sınıf Seçiniz"
                                                            data-control="select2" data-placeholder="Sınıf Seçiniz..."
                                                            data-dropdown-parent="#kt_modal_update_customer"
                                                            class="form-select form-select-solid fw-bold">
                                                            <?php
                                                            if ($currentGame['class_id'] == 0 || $currentGame['class_id'] == null) {
                                                                echo '<option value="0">Hepsi</option>';
                                                            } else {
                                                                echo '<option selected value="' . $currentGame['class_id'] . '">' .
                                                                    $currentGame['class_name'] . ' - Önceden seçilen</option>';
                                                            }
                                                            ?>
                                                            <?php echo $chooseClass->getClassSelectList() ?>
                                                        </select>
                                                    </div>

                                                    <div class="fv-row mb-7">
                                                        <label class="fs-6 fw-semibold mb-2">Ders</label>

                                                        <select id="lessons" name="lessons" aria-label="Ders Seçiniz"
                                                            data-control="select2" data-placeholder="Ders Seçiniz..."
                                                            class="form-select form-select-solid fw-bold">
                                                            <?php
                                                            if ($currentGame['lesson_id'] == 0 || $currentGame['lesson_id'] == null) {
                                                                echo '<option value="0">Hepsi</option>';
                                                            } else {
                                                                echo '<option selected value="' . $currentGame['lesson_id'] . '">' .
                                                                    $currentGame['lesson_name'] . ' - Önceden seçilen</option>';
                                                            }
                                                            ?>

                                                        </select>
                                                    </div>

                                                    <div class="fv-row mb-7">
                                                        <label class=" fs-6 fw-semibold mb-2">Ünite</label>

                                                        <select id="units" name="units" aria-label="Ünite Seçiniz"
                                                            data-control="select2" data-placeholder="Ünite Seçiniz..."
                                                            class="form-select form-select-solid fw-bold">

                                                            <?php
                                                            if ($currentGame['unit_id'] == 0 || $currentGame['unit_id'] == null) {
                                                                echo '<option value="0">Hepsi</option>';
                                                            } else {
                                                                echo '<option selected value="' . $currentGame['unit_id'] . '">' .
                                                                    $currentGame['unit_name'] . ' - Önceden seçilen</option>';
                                                            }
                                                            ?>

                                                        </select>
                                                    </div>

                                                    <div class="fv-row mb-7">
                                                        <label class=" fs-6 fw-semibold mb-2">Konu</label>

                                                        <select id="topics" name="topics" aria-label="Konu Seçiniz"
                                                            data-control="select2" data-placeholder="Konu Seçiniz..."
                                                            class="form-select form-select-solid fw-bold">

                                                            <?php
                                                            if ($currentGame['topic_id'] == 0 || $currentGame['topic_id'] == null) {
                                                                echo '<option value="0">Hepsi</option>';
                                                            } else {
                                                                echo '<option selected value="' . $currentGame['topic_id'] . '">' .
                                                                    $currentGame['topic_name'] . ' - Önceden seçilen</option>';
                                                            }
                                                            ?>

                                                        </select>
                                                    </div>

                                                    <div class="fv-row mb-7">
                                                        <label class=" fs-6 fw-semibold mb-2">Altkonu</label>

                                                        <select id="subtopics" name="subtopics" aria-label="Altkonu Seçiniz"
                                                            data-control="select2" data-placeholder="Altkonu Seçiniz..."
                                                            class="form-select form-select-solid fw-bold">
                                                            <?php
                                                            if ($currentGame['subtopic_id'] == 0 || $currentGame['subtopic_id'] == null) {
                                                                echo '<option value="0">Hepsi</option>';
                                                            } else {
                                                                echo '<option selected value="' . $currentGame['subtopic_id'] . '">' .
                                                                    $currentGame['subtopic_name'] . ' - Önceden seçilen</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>

                                                </div>

                                                <div class="modal-footer flex-center">
                                                    <button type="reset" id="kt_modal_update_customer_cancel"
                                                        class="btn btn-light btn-sm me-3">İptal</button>

                                                    <button type="submit" id="kt_modal_update_customer_submit"
                                                        class="btn btn-primary btn-sm">
                                                        <span class="indicator-label">Gönder</span>
                                                        <span class="indicator-progress">Lütfen Bekleyin...
                                                            <span
                                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                                    </button>
                                                </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal End -->
                        </div>

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

        <script>
            function sendAlterRequest(payload, successMsg, onSuccess) {
                $.ajax({
                    type: "POST",
                    url: "includes/alter_active_game.inc.php",
                    data: payload,
                    traditional: true,
                    dataType: "json",
                    success: function (response) {
                        if (response.status === "success") {
                            Swal.fire({
                                text: successMsg,
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Tamam, anladım!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            }).then(() => {
                                if (typeof onSuccess === 'function') {
                                    onSuccess();
                                }
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                text: response.message,
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Tamam, anladım!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            text: "Bir hata oluştu!",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Tamam, anladım!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                    }
                });
            }
            var handleAlterActiveStatusRow = () => {
                const AlterButton = document.querySelector('#alter_button');

                AlterButton.addEventListener('click', function (e) {
                    e.preventDefault();

                    const parent = e.target.closest('tr');

                    const customerName = parent.querySelectorAll('td')[1].innerText;
                    const gameId = parent.getAttribute('id');
                    var activeStatus = parent.querySelectorAll('td')[2].innerText;

                    if (activeStatus === "Aktif") {
                        activeStatus = "pasif";
                    } else {
                        activeStatus = "aktif";
                    }

                    Swal.fire({
                        text: customerName + " isimli sesli kitabı " + activeStatus + " yapmak istediğinizden emin misiniz?",
                        icon: "warning",
                        showCancelButton: true,
                        buttonsStyling: false,
                        confirmButtonText: "Evet, " + activeStatus + " yap!",
                        cancelButtonText: "Hayır, iptal et",
                        customClass: {
                            confirmButton: "btn fw-bold btn-danger",
                            cancelButton: "btn fw-bold btn-active-light-primary"
                        }
                    }).then(function (result) {
                        if (result.value) {

                            sendAlterRequest(
                                { id: gameId },
                                `İşlem tamamlandı.`,
                                function () {
                                }
                            );
                        } else if (result.dismiss === 'cancel') {
                            Swal.fire({
                                text: "İşlem tamamlanmadı",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Tamam, anladım!",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary",
                                }
                            });
                        }
                    });
                })
            }
            handleAlterActiveStatusRow();
        </script>
        <!--begin::Global Javascript Bundle(mandatory for all pages)-->
        <script src="assets/plugins/global/plugins.bundle.js"></script>
        <script src="assets/js/scripts.bundle.js"></script>
        <!--end::Global Javascript Bundle-->
        <!--begin::Vendors Javascript(used for this page only)-->
        <script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
        <script src="assets/js/custom/apps/games/update.js"></script>

        <!--end::Vendors Javascript-->
        <!--begin::Custom Javascript(used for this page only)-->

        <script src="assets/js/widgets.bundle.js"></script>
        <script src="assets/js/custom/widgets.js"></script>
        <script src="assets/js/custom/apps/chat/chat.js"></script>
        <script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
        <script src="assets/js/custom/utilities/modals/create-account.js"></script>
        <script src="assets/js/custom/utilities/modals/create-app.js"></script>
        <script src="assets/js/custom/utilities/modals/users-search.js"></script>
        <!--end::Custom Javascript-->
        <!--end::Javascript-->
    </body>
    <!--end::Body-->

    </html>
<?php } else {
    header("location: index");
}
