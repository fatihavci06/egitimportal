<?php
if (!defined('GUARD')) {
    die('Erişim yasak!');
}

include_once "classes/dbh.classes.php";
include_once "classes/classes.classes.php";
include_once "classes/classes-view.classes.php";

$classShow = new ShowClass();

?>
<div class="modal fade" id="kt_modal_add_customer" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <form class="form" action="#" id="kt_modal_add_customer_form" data-kt-redirect="gunun-kelimesi-admin">
                <div class="modal-header" id="kt_modal_add_customer_header">
                    <h2 class="fw-bold">Kelime Ekleyin</h2>
                    <div id="kt_modal_add_customer_close" class="btn btn-icon btn-sm btn-active-icon-primary">
                        <i class="ki-duotone ki-cross fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                </div>
                <div class="modal-body py-10 px-lg-17">
                    <div class="scroll-y me-n7 pe-7" id="kt_modal_add_customer_scroll" data-kt-scroll="true"
                        data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                        data-kt-scroll-dependencies="#kt_modal_add_customer_header"
                        data-kt-scroll-wrappers="#kt_modal_add_customer_scroll" data-kt-scroll-offset="300px">


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
                                    <div class="image-input-wrapper w-100px h-100px" style="background-image: url('')">
                                    </div>

                                    <label
                                        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                        title="Görsel Ekle">
                                        <i class="ki-duotone ki-pencil fs-7">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <input type="file" name="photo" id="photo"
                                            accept=".png, .jpg, .jpeg, .PNG, .JPG, .JPEG" />
                                        <input type="hidden" name="avatar_remove" />
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
                                        title="Remove avatar">
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
                            <input type="text" id="word" class="form-control form-control-solid" placeholder="Kelime"
                                name="word" />
                        </div>

                        <div class="fv-row mb-7">
                            <label class="form-label fw-bold text-gray-900 fs-6">Sınıf
                            </label>
                            <select name="classes" aria-label="Sınıf Seçiniz" data-control="select2"
                                data-placeholder="Sınıf Seçiniz..." class="form-select form-select-solid fw-bold">
                                <option value="">Sınıf Seçin</option>
                                <?php echo $classShow->getClassSelectListByschool(); ?>
                            </select>
                        </div>

                        <div class="fv-row mb-7">
                            <label class="form-label fw-bold text-gray-900 fs-6">Grup
                            </label>
                            <select name="groups" aria-label="Grup Seçiniz" data-control="select2"
                                data-placeholder="Grup Seçiniz..." class="form-select form-select-solid fw-bold">
                                <option value="">Grup Seçin</option>
                                <option value="2">Herkes</option>
                                <option value="0">İlk Okul ve Orta Okul</option>
                                <option value="1">Okul Öncesi </option>
                            </select>
                        </div>


                        <div class="fv-row mb-7">
                            <label class="form-label fw-bold text-gray-900 fs-6">Görüntüleme
                                Tarihi</label>
                            <input type="date" class="form-control form-control-solid fw-bold pe-5"
                                placeholder="Show Date" name="show_date" id="show_date">
                        </div>

                        <div class="d-flex flex-column mb-7 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Açıklama</label>
                            <textarea class="form-control form-control-solid" name="body" id="body"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer flex-center">
                        <button type="reset" id="kt_modal_add_customer_cancel" class="btn btn-light me-3">İptal</button>

                        <button type="submit" id="kt_modal_add_customer_submit" class="btn btn-primary">
                            <span class="indicator-label">Gönder</span>
                            <span class="indicator-progress">Lütfen Bekleyin...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
            </form>
        </div>
    </div>
</div>