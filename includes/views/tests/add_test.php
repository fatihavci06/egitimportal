<?php
if (!defined('GUARD')) {
    die('Erişim yasak!');
}

include_once "classes/dbh.classes.php";
include_once "classes/classes.classes.php";
include_once "classes/classes-view.classes.php";

$chooseClass = new ShowClass();

?>
<!--begin::Form-->
<form class="form" action="#" id="kt_modal_add_customer_form" data-kt-redirect="testler">
    <div class="card-body pt-5">
        <!--begin::Input group-->
        <div class="mb-7">
            <!--begin::Label-->
            <label class="fs-6 fw-semibold mb-3">
                <span>Görsel</span>
                <span class="ms-1" data-bs-toggle="tooltip" title="İzin verilen dosya türleri: png, jpg, jpeg.">
                    <i class="ki-duotone ki-information fs-7">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                    </i>
                </span>
            </label>
            <!--end::Label-->
            <!--begin::Image input wrapper-->
            <div class="mt-1">
                <!--begin::Image placeholder-->
                <style>
                    .image-input-placeholder {
                        background-image: url('assets/media/svg/files/blank-image.svg');
                    }

                    [data-bs-theme="dark"] .image-input-placeholder {
                        background-image: url('assets/media/svg/files/blank-image-dark.svg');
                    }
                </style>
                <!--end::Image placeholder-->
                <!--begin::Image input-->
                <div class="image-input image-input-outline image-input-placeholder image-input-empty image-input-empty" data-kt-image-input="true">
                    <!--begin::Preview existing avatar-->
                    <div class="image-input-wrapper w-100px h-100px" style="background-image: url('')"></div>
                    <!--end::Preview existing avatar-->
                    <!--begin::Edit-->
                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Görsel Ekle">
                        <i class="ki-duotone ki-pencil fs-7">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <!--begin::Inputs-->
                        <input type="file" name="photo" id="photo" accept=".png, .jpg, .jpeg, .PNG, .JPG, .JPEG" />
                        <input type="hidden" name="avatar_remove" />
                        <!--end::Inputs-->
                    </label>
                    <!--end::Edit-->
                    <!--begin::Cancel-->
                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Fotoğrafı İptal Et">
                        <i class="ki-duotone ki-cross fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </span>
                    <!--end::Cancel-->
                    <!--begin::Remove-->
                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
                        <i class="ki-duotone ki-cross fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </span>
                    <!--end::Remove-->
                </div>
                <!--end::Image input-->
            </div>
            <!--end::Image input wrapper-->
        </div>
        <!--end::Input group-->
        <!--begin::Input group-->
        <div class="fv-row mb-7">
            <!--begin::Label-->
            <label class="required fs-6 fw-semibold mb-2">Test</label>
            <!--end::Label-->
            <!--begin::Input-->
            <input type="text" id="name" class="form-control form-control-solid" placeholder="Test Adı" name="name" />
            <!--end::Input-->
        </div>
        <!--end::Input group-->

        <!--begin::Input group-->
        <div class="fv-row mb-7">
            <!--begin::Label-->
            <label class="required fs-6 fw-semibold mb-2">Kısa Açıklama</label>
            <!--end::Label-->
            <!--begin::Input-->
            <input type="text" id="short_desc" class="form-control form-control-solid" placeholder="Kısa Açıklama Yazın" name="short_desc" />
            <!--end::Input-->
        </div>
        <!--end::Input group-->

        <!--begin::Test -->
        <div id="test-div">
            <div data-kt-element="items">
                <div class="border-bottom border-bottom-dashed" data-kt-element="item">
                    <!--begin::Input group-->
                    <div class="fv-row">
                        <!--begin::Label-->
                        <label class="required fs-6 fw-semibold mb-2">Sorular</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <textarea id="testsoru" class="form-control form-control-solid mb-2" placeholder="Soru 1" name="testsoru[]"></textarea>
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row mb-7">
                        <div class="col-md-3 mb-3"><input name="cevap_a[]" type="text" class="form-control form-control-solid" placeholder="A Şıkkı"></div>
                        <div class="col-md-3 mb-3"><input name="cevap_b[]" type="text" class="form-control form-control-solid" placeholder="B Şıkkı"></div>
                        <div class="col-md-3 mb-3"><input name="cevap_c[]" type="text" class="form-control form-control-solid" placeholder="C Şıkkı"></div>
                        <div class="col-md-3 mb-3"><input name="cevap_d[]" type="text" class="form-control form-control-solid" placeholder="D Şıkkı"></div>
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row mb-7 border-bottom">
                        <div class="col-md-2 mb-3">Sorunun cevabı:</div>
                        <div class="col-md-2 mb-3">
                            <label>
                                <input class="form-check-input ms-10 soru_1" type="checkbox" name="testcevap[]" value="A"> A
                            </label>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>
                                <input class="form-check-input ms-10 soru_1" type="checkbox" name="testcevap[]" value="B"> B
                            </label>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>
                                <input class="form-check-input ms-10 soru_1" type="checkbox" name="testcevap[]" value="C"> C
                            </label>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>
                                <input class="form-check-input ms-10 soru_1" type="checkbox" name="testcevap[]" value="D"> D
                            </label>
                        </div>
                    </div>
                    <!--end::Input group-->
                </div>
            </div>

            <div id="eklenenDivler">
            </div>

            <button type="button" id="ekleButonu" class="btn btn-success btn-sm">Soru Ekle</button>
            <button type="button" id="silButonu" class="btn btn-danger btn-sm ml-2">Soruyu Sil</button>
        </div>
        <!--end::Test -->

        <!--begin::Input group-->
        <div class="fv-row mb-7 mt-7">
            <!--begin::Label-->
            <label class="required fs-6 fw-semibold mb-2">Sınıf</label>
            <!--end::Label-->
            <!--begin::Input-->
            <select id="classes" name="classes" aria-label="Sehir Seçiniz" data-control="select2" data-placeholder="Sınıf Seçiniz..." class="form-select form-select-solid fw-bold">
                <option value="">Sınıf Seçin</option>
                <?php $chooseClass->getClassSelectList(); ?>
            </select>
            <!--end::Input-->
        </div>
        <!--end::Input group-->

        <!--begin::Input group-->
        <div class="fv-row mb-7">
            <!--begin::Label-->
            <label class="required fs-6 fw-semibold mb-2">Ders</label>
            <!--end::Label-->
            <!--begin::Input-->
            <select id="lessons" name="lessons" aria-label="Ders Seçiniz" data-control="select2" data-placeholder="Ders Seçiniz..." class="form-select form-select-solid fw-bold">
            </select>
            <!--end::Input-->
        </div>
        <!--end::Input group-->

        <!--begin::Input group-->
        <div class="fv-row mb-7">
            <!--begin::Label-->
            <label class="required fs-6 fw-semibold mb-2">Ünite</label>
            <!--end::Label-->
            <!--begin::Input-->
            <select id="units" name="units" aria-label="Ünite Seçiniz" data-control="select2" data-placeholder="Ünite Seçiniz..." class="form-select form-select-solid fw-bold">
            </select>
            <!--end::Input-->
        </div>
        <!--end::Input group-->

        <!--begin::Input group-->
        <div class="fv-row mb-7">
            <!--begin::Label-->
            <label class="required fs-6 fw-semibold mb-2">Konu</label>
            <!--end::Label-->
            <!--begin::Input-->
            <select id="topics" name="topics" aria-label="Konu Seçiniz" data-control="select2" data-placeholder="Konu Seçiniz..." class="form-select form-select-solid fw-bold">
            </select>
            <!--end::Input-->
        </div>
        <!--end::Input group-->

        <!--begin::Input group-->
        <div class="fv-row mb-7">
            <!--begin::Label-->
            <label class="required fs-6 fw-semibold mb-2">Son Teslim Tarihi</label>
            <!--end::Label-->
            <!--begin::Datepicker-->
            <input class="form-control form-control-solid fw-bold pe-5" id="last_day" placeholder="Tarih Aralğı Seçin" name="last_day" />
            <!--end::Datepicker-->
        </div>
        <!--end::Input group-->

        <!--end::Modal body-->
        <!--begin::Modal footer-->
        <div class="modal-footer flex-center">
            <!--begin::Button-->
            <button type="submit" id="kt_modal_add_customer_submit" class="btn btn-primary btn-sm">
                <span class="indicator-label">Gönder</span>
                <span class="indicator-progress">Lütfen Bekleyin...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
            </button>
            <!--end::Button-->
        </div>
        <!--end::Modal footer-->
</form>
<!--end::Form-->