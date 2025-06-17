<?php
if (!defined('GUARD')) {
    die('Erişim yasak!');
}

include_once "classes/dbh.classes.php";
include_once "classes/classes.classes.php";
include_once "classes/classes-view.classes.php";
include_once "classes/units.classes.php";
include_once "classes/units-view.classes.php";

$chooseUnit = new ShowUnit();

$chooseClass = new ShowClass();

?>
<!--begin::Form-->
<form class="form" action="#" id="kt_modal_add_customer_form" data-kt-redirect="odevler">
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
            <label class="required fs-6 fw-semibold mb-2">Ödev Başlığı</label>
            <!--end::Label-->
            <!--begin::Input-->
            <input type="text" id="name" class="form-control form-control-solid" placeholder="İçerik" name="name" />
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

        <?php
        if ($_SESSION['role'] != 4) {
        ?>

        <!--begin::Input group-->
        <div class="fv-row mb-7 mt-7">
            <!--begin::Label-->
            <label class="required fs-6 fw-semibold mb-2">Sınıf</label>
            <!--end::Label-->
            <!--begin::Input-->
            <select id="classes" name="classes" aria-label="Sehir Seçiniz" data-control="select2" data-placeholder="Sınıf Seçiniz..." class="form-select form-select-solid fw-bold">
                <option value="">Sınıf Seçin</option>
                <?php echo $chooseClass->getClassSelectList(); ?>
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
                <option value="">Ders Seçiniz...</option>
            </select>
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
                <option value="">Ünite Seçiniz...</option>
            </select>
            <!--end::Input-->
        </div>
        <!--end::Input group-->

        <?php
        } else {
            $class_id = $_SESSION['class_id'];
            $lesson_id = $_SESSION['lesson_id'];
        ?>

            <div class="fv-row">
            <input class="form-select form-select-solid fw-bold" type="hidden" name="classes" id="classes" value="<?php echo $class_id; ?>">
            </div>
            <div class="fv-row">
            <input class="form-select form-select-solid fw-bold" type="hidden" name="lessons" id="lessons" value="<?php echo $lesson_id; ?>">
            </div>

            <!--begin::Input group-->
            <div class="fv-row mb-7">
                <!--begin::Label-->
                <label class="required fs-6 fw-semibold mb-2">Ünite</label>
                <!--end::Label-->
                <!--begin::Input-->
                <select id="units" name="units" aria-label="Ünite Seçiniz" data-control="select2" data-placeholder="Ünite Seçiniz..." class="form-select form-select-solid fw-bold">
                    <option value="">Ünite Seçin</option>
                    <?php echo $chooseUnit->getUnitSelectList(); ?>
                </select>
                <!--end::Input-->
            </div>
            <!--end::Input group-->

        <?php
        }
        ?>

        <!--begin::Input group-->
        <div class="fv-row mb-7">
            <!--begin::Label-->
            <label class="required fs-6 fw-semibold mb-2">Konu</label>
            <!--end::Label-->
            <!--begin::Input-->
            <select id="topics" name="topics" aria-label="Konu Seçiniz" data-control="select2" data-placeholder="Konu Seçiniz..." class="form-select form-select-solid fw-bold">
                <option value="">Konu Seçiniz...</option>
            </select>
            <!--end::Input-->
        </div>
        <!--end::Input group-->

        <!--begin::Input group-->
        <div class="fv-row mb-7">
            <!--begin::Label-->
            <label class="fs-6 fw-semibold mb-2">Alt Konu</label>
            <!--end::Label-->
            <!--begin::Input-->
            <select id="sub_topics" name="sub_topics" aria-label="Alt Konu Seçiniz" data-control="select2" data-placeholder="Alt Konu Seçiniz..." class="form-select form-select-solid fw-bold">
                <option value="">Alt Konu Seçiniz...</option>
            </select>
            <!--end::Input-->
        </div>
        <!--end::Input group-->

        <!--begin::Input group-->
        <div class="fv-row mb-7">
            <!--begin::Label-->
            <label class="fs-6 fw-semibold mb-2">Başlangıç Tarihi</label>
            <!--end::Label-->
            <!--begin::Input-->
            <input type="date" name="start_date" id="start_date" class="form-control form-control-solid">
            <!--end::Input-->
        </div>
        <!--end::Input group-->

        <!--begin::Input group-->
        <div class="fv-row mb-7">
            <!--begin::Label-->
            <label class="fs-6 fw-semibold mb-2">Bitiş Tarihi</label>
            <!--end::Label-->
            <!--begin::Input-->
           <input type="date" name="end_date" id="end_date" class="form-control form-control-solid">
            <!--end::Input-->
        </div>
        <!--end::Input group-->

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
                <label>
                    <input class="form-check-input ms-10" type="radio" name="secim" value="wordwall"> Interaktif Oyun
                </label>
            </div>

            <!-- Video URL input -->
            <div id="videoInput" class="mb-4" style="display:none;">
                <label for="video_url">Video Link (Vimeo):</label>
                <input type="text" class="form-control" name="video_url" id="video_url">
            </div>

            <!-- File upload input -->
            <div id="fileInput" class="mb-4" style="display:none;">
                <label for="file_path">Dosya Yükle:</label>
                <input type="file" class="form-control" name="file_path[]" id="files" multiple accept=".xls,.xlsx,.doc,.docx,.ppt,.pptx,.png,.jpeg,.jpg,.svg,.pdf">
                <div id="fileDescriptions"></div>
            </div>
            <!-- Video URL input -->
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

            <!-- Açıklama alanlarının ekleneceği yer -->

        </div>

        <!-- Textarea input -->
        <div id="textInput" class="mb-4" style="display:none;">
            <label for="mcontent">Metin İçeriği:</label>
            <textarea class="form-control tinymce-editor" name="mcontent" id="mcontent" rows="4"></textarea>
        </div>

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