<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role'])) {
    include_once "classes/dbh.classes.php";
    include_once "classes/todayword.php";
    $todatword = new TodayWord();
    include_once "views/pages-head.php";
?>

<body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true"
    data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
    data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true"
    data-kt-app-aside-enabled="true" data-kt-app-aside-fixed="true" data-kt-app-aside-push-toolbar="true"
    data-kt-app-aside-push-footer="true" class="app-default">
    
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
                                <div class="card">
                                    <div class="card-header border-0 pt-6">
                                        <div class="card-title">
                                            <div class="d-flex align-items-center position-relative my-1">
                                                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                                <input type="text" data-kt-customer-table-filter="search"
                                                    class="form-control form-control-solid w-250px ps-12"
                                                    placeholder="Kelime Ara" />
                                            </div>
                                        </div>
                                        <?php if ($_SESSION['role'] == 1 or $_SESSION['id'] == 3) { ?>
                                            <div class="card-toolbar">
                                                <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                                                    <div class="me-3">
                                                        <select id="class_filter_select" class="form-select form-select-solid fw-bold w-125px" data-kt-select2="true" data-placeholder="Sınıf">
                                                            <option value="">Tüm Sınıflar</option>
                                                            <option value="1. Sınıf">1. Sınıf</option>
                                                            <option value="2. Sınıf">2. Sınıf</option>
                                                            <option value="3. Sınıf">3. Sınıf</option>
                                                            <option value="4. Sınıf">4. Sınıf</option>
                                                        </select>
                                                    </div>
                                                    <button type="button" class="btn btn-light-primary btn-sm me-3" id="apply_class_filter">
                                                        <i class="ki-duotone ki-filter fs-2">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>Filtrele
                                                    </button>
                                                    <button type="button" class="btn btn-primary btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#kt_modal_add_customer">Kelime Ekle</button>
                                                </div>
                                                <div class="d-flex justify-content-end align-items-center d-none"
                                                    data-kt-customer-table-toolbar="selected">
                                                    <div class="fw-bold me-5">
                                                        <span class="me-2"
                                                            data-kt-customer-table-select="selected_count"></span>Seçildi
                                                    </div>
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        data-kt-customer-table-select="delete_selected">Seçilenleri Pasif
                                                        Yap</button>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="card-body pt-0">
                                        <table class="table align-middle table-row-dashed fs-6 gy-5"
                                            id="kt_customers_table">
                                            <thead>
                                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                                    <th class="w-10px pe-2">
                                                        <div
                                                            class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                            <input class="form-check-input" type="checkbox"
                                                                data-kt-check="true"
                                                                data-kt-check-target="#kt_customers_table .form-check-input"
                                                                value="1" />
                                                        </div>
                                                    </th>
                                                    <th class="min-w-40px">Görsel</th>
                                                    <th class="min-w-125px">Kelime</th>
                                                    <th class="min-w-125px">İçerik</th>
                                                    <th class="min-w-125px">Sınıf</th>
                                                    <th class="min-w-125px">Okul</th>
                                                    <th class="min-w-125px">Başlangıç</th>
                                                    <th class="min-w-125px">Bitiş</th>
                                                    <th class="min-w-40px">Durum</th>
                                                    <th class="text-end min-w-70px">İşlemler</th>
                                                </tr>
                                            </thead>
                                            <tbody class="fw-semibold text-gray-600">
                                                <?php
                                                $todatword->getAllTodaysWordsList();
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <!-- Add Modal -->
                                <div class="modal fade" id="kt_modal_add_customer" tabindex="-1" aria-hidden="true">
                                    <!-- Add modal içeriği aynı -->
                                </div>
                                
                                <!-- Edit Modal -->
                                <div class="modal fade" id="kt_modal_edit_customer" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered mw-650px">
                                        <div class="modal-content">
                                            <form class="form" action="#" id="kt_modal_edit_customer_form" data-kt-redirect="gunun-kelimesi-admin">
                                                <div class="modal-header" id="kt_modal_edit_customer_header">
                                                    <h2 class="fw-bold">Kelimeyi Güncelleyin</h2>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body py-10 px-lg-17">
                                                    <div class="scroll-y me-n7 pe-7" id="kt_modal_edit_customer_scroll" data-kt-scroll="true"
                                                        data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                                                        data-kt-scroll-dependencies="#kt_modal_edit_customer_header"
                                                        data-kt-scroll-wrappers="#kt_modal_edit_customer_scroll" data-kt-scroll-offset="300px">
                                                        <input type="hidden" name="word_id" id="word_id_edit">
                                                        
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
                                                                <div class="image-input image-input-outline image-input-placeholder"
                                                                    data-kt-image-input="true" id="edit_image_input_container">
                                                                    <div class="image-input-wrapper w-100px h-100px" id="current_word_image" style="background-image: url('assets/media/svg/files/blank-image.svg')">
                                                                    </div>
                                                                    <label
                                                                        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                                        data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                                                        title="Görsel Değiştir">
                                                                        <i class="ki-duotone ki-pencil fs-7">
                                                                            <span class="path1"></span>
                                                                            <span class="path2"></span>
                                                                        </i>
                                                                        <input type="file" name="photo_edit" id="photo_edit"
                                                                            accept=".png, .jpg, .jpeg, .PNG, .JPG, .JPEG" />
                                                                        <input type="hidden" name="avatar_remove_edit" />
                                                                    </label>
                                                                    <span
                                                                        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                                        data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                                                        title="Fotoğrafı İptal Et">
                                                                        <i class="ki-duotone ki-cross fs-2">
                                                                            <span class="path1"></span>
                                                                            <span class="path2"></span>
                                                                        </i>
                                                                    </span>
                                                                    <span
                                                                        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                                        data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                                                        title="Görseli Kaldır">
                                                                        <i class="ki-duotone ki-cross fs-2">
                                                                            <span class="path1"></span>
                                                                            <span class="path2"></span>
                                                                        </i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="fv-row mb-7">
                                                            <label class="required fs-6 fw-semibold mb-2">Kelime</label>
                                                            <input type="text" id="word_edit" class="form-control form-control-solid" placeholder="Kelime"
                                                                name="word_edit" />
                                                        </div>
                                                        
                                                        <div class="fv-row mb-7">
                                                            <label class="form-label fw-bold text-gray-900 fs-6">Sınıf</label>
                                                            <select name="classes_edit" id="classes_edit" aria-label="Sınıf Seçiniz" data-control="select2"
                                                                data-placeholder="Sınıf Seçiniz..." class="form-select form-select-solid fw-bold">
                                                                <option value="">Sınıf Seçin</option>
                                                            </select>
                                                        </div>
                                                        
                                                        <div class="fv-row mb-7">
                                                            <label class="form-label fw-bold text-gray-900 fs-6">Başlangıç Tarihi</label>
                                                            <input type="date" class="form-control form-control-solid fw-bold pe-5"
                                                                placeholder="Başlangıç Tarihi" name="start_date_edit" id="start_date_edit">
                                                        </div>
                                                        
                                                        <div class="fv-row mb-7">
                                                            <label class="form-label fw-bold text-gray-900 fs-6">Bitiş Tarihi</label>
                                                            <input type="date" class="form-control form-control-solid fw-bold pe-5"
                                                                placeholder="Bitiş Tarihi" name="end_date_edit" id="end_date_edit">
                                                        </div>
                                                        
                                                        <div class="d-flex flex-column mb-7 fv-row">
                                                            <label class="required fs-6 fw-semibold mb-2">Açıklama</label>
                                                            <textarea class="form-control form-control-solid" name="body_edit" id="body_edit" rows="5"></textarea>
                                                        </div>
                                                        
                                                        <div class="fv-row mb-7">
                                                            <label class="form-label fw-bold text-gray-900 fs-6">Durum</label>
                                                            <select name="status_edit" id="status_edit" aria-label="Durum Seçiniz" data-control="select2"
                                                                data-placeholder="Durum Seçiniz..." class="form-select form-select-solid fw-bold">
                                                                <option value="1">Aktif</option>
                                                                <option value="0">Pasif</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer flex-center">
                                                    <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">İptal</button>
                                                    <button type="submit" id="kt_modal_edit_customer_submit" class="btn btn-primary">
                                                        <span class="indicator-label">Güncelle</span>
                                                        <span class="indicator-progress">Lütfen Bekleyin...
                                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                        </span>
                                                    </button>
                                                </div>
                                            </form>
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
        // Basit ve etkili loadWordData fonksiyonu
       
    </script>
</body>
</html>
<?php } else {
    header("location: index");
}