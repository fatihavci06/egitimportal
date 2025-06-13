<?php
if (!defined('GUARD')) {
    die('Erişim yasak!');
}
include_once "classes/classes.classes.php";
include_once "classes/classes-view.classes.php";
include_once "classes/lessons.classes.php";
include_once "classes/lessons-view.classes.php";

$chooseClass = new ShowClass();
$chooseSchool = new ShowSchool();
$chooseLesson = new ShowLesson();
?>
<div class="modal fade" id="kt_modal_add_customer" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Form-->
            <form class="form" action="#" id="kt_modal_add_customer_form" data-kt-redirect="ogretmenler"  enctype="multipart/form-data">
                <!--begin::Modal header-->
                    <div class="modal-header" id="kt_modal_add_customer_header">
                    <!--begin::Modal title-->
                    <h2 class="fw-bold">Öğretmen Ekleyin</h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <div id="kt_modal_add_customer_close" class="btn btn-icon btn-sm btn-active-icon-primary">
                        <i class="ki-duotone ki-cross fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body py-10 px-lg-17">
                    <!--begin::Scroll-->
                    <div class="scroll-y me-n7 pe-7" id="kt_modal_add_customer_scroll" data-kt-scroll="true"
                        data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                        data-kt-scroll-dependencies="#kt_modal_add_customer_header"
                        data-kt-scroll-wrappers="#kt_modal_add_customer_scroll" data-kt-scroll-offset="300px">
                        <!--begin::Input group-->
                        <div class="mb-7">
                            <!--begin::Label-->
                            <label class="fs-6 fw-semibold mb-3">
                                <span>Fotoğraf</span>
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
                                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Fotoğraf Ekle">
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
                        <div class="row fv-row mb-7">
                            <!--begin::Label-->
                            <label class="required fs-6 fw-semibold mb-2">Öğretmen Adı</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-md-6 fv-row">
                                <!--begin::Input-->
                                <input type="text" id="name" class="form-control form-control-solid" placeholder="Adı" name="name" />
                                <!--end::Input-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-6 fv-row">
                                <!--begin::Input-->
                                <input type="text" id="surname" class="form-control form-control-solid" placeholder="Soyadı" name="surname" />
                                <!--end::Input-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <div id="kt_modal_add_customer_billing_info" class="collapse show">
                            <!--begin::Input group-->
                            <div class="d-flex flex-column mb-7 fv-row">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">Kullanıcı Adı</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input class="form-control form-control-solid" name="username" id="username" placeholder="Kullanıcı Adı" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="d-flex flex-column mb-7 fv-row">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">
                                    <span class="required">Cinsiyet</span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select id="gender" name="gender" aria-label="Cinsiyet Seçiniz" data-control="select2"
                                    data-placeholder="Cinsiyet Seçiniz..." data-dropdown-parent="#kt_modal_add_customer"
                                    class="form-select form-select-solid fw-bold">
                                    <option value="">Cinsiyet Seçin</option>
                                    <option value="Erkek">Erkek</option>
                                    <option value="Kız">Kadın</option>
                                </select>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">
                                    <span class="required">Doğum Tarihi</span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Datepicker-->
                                <input type="date" class="form-control form-control-solid fw-bold pe-5" placeholder="Doğum Tarihi Seçin" name="birthdate" id="birthdate" />
                                <!--end::Datepicker-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">
                                    <span class="required">E-posta Adresi</span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="email" class="form-control form-control-solid" name="email" id="email" placeholder="E-posta" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">Telefon Numarası</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="number" class="form-control form-control-solid" placeholder="Telefon" id="telephone" name="telephone" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="d-flex flex-column mb-7 fv-row">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">
                                    <span class="required">Okul</span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select id="school" name="school" aria-label="Okul Seçiniz" data-control="select2"
                                    data-placeholder="Okul Seçiniz..." data-dropdown-parent="#kt_modal_add_customer"
                                    class="form-select form-select-solid fw-bold">
                                    <?php $chooseSchool->getSchoolSelectOneList($_SESSION['school_id']); ?>
                                </select>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="d-flex flex-column mb-7 fv-row">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">
                                    <span class="required">Sınıf</span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select id="classAdd" name="classAdd" aria-label="Sınıf Seçiniz" data-control="select2"
                                    data-placeholder="Sınıf Seçiniz..." data-dropdown-parent="#kt_modal_add_customer"
                                    class="form-select form-select-solid fw-bold">
                                    <option value="">Sınıf Seçin</option>
                                    <?php echo $chooseClass->getClassSelectList(); ?>
                                </select>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="d-flex flex-column mb-7 fv-row">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">
                                    <span class="required">Ders</span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select id="lessonAdd" name="lessonAdd" aria-label="Ders Seçiniz" data-control="select2"
                                    data-placeholder="Ders Seçiniz..." data-dropdown-parent="#kt_modal_add_customer"
                                    class="form-select form-select-solid fw-bold">
                                    <option value="">Ders Seçin</option>
                                    <?php $chooseLesson->getLessonSelectList(); ?>
                                </select>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                        </div>
                        <!--end::Scroll-->
                    </div>
                    <!--end::Modal body-->
                    <!--begin::Modal footer-->
                    <div class="modal-footer flex-center">
                        <!--begin::Button-->
                        <button type="reset" id="kt_modal_add_customer_cancel" class="btn btn-light btn-sm me-3">İptal</button>
                        <!--end::Button-->
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
        </div>
    </div>
</div>