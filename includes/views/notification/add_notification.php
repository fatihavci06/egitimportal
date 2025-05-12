<?php
if (!defined('GUARD')) {
    die('Erişim yasak!');
}

include_once "classes/dbh.classes.php";
include_once "classes/userroles.classes.php";
include_once "classes/userroles-view.classes.php";
include_once "classes/classes.classes.php";
include_once "classes/classes-view.classes.php";

$userRole = new ShowRole();
$classShow = new ShowClass();

?>
<div class="modal fade" id="kt_modal_add_customer" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Form-->
            <form class="form" action="#" id="kt_modal_add_customer_form" data-kt-redirect="bildirimler">
                <!--begin::Modal header-->
                <div class="modal-header" id="kt_modal_add_customer_header">
                    <!--begin::Modal title-->
                    <h2 class="fw-bold">Bildirim Ekleyin</h2>
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
                        <div class="fv-row mb-7">
                            <!--begin::Label-->
                            <label class="required fs-6 fw-semibold mb-2">Bildirim Adı</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" id="name" class="form-control form-control-solid" placeholder="Bildirim" name="name" />
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Radio group-->
                        <div class="fv-row mb-7" id="chooseOne">
                            <!--begin::Label-->
                            <div>
                                <label class="required fs-6 fw-semibold mb-2">Bildirim Kime Yapılacak?</label>
                            </div>
                            <!--end::Label-->
                            <label>
                                <input class="form-check-input" type="radio" name="secim" value="1"> Herkese
                            </label>
                            <label>
                                <input class="form-check-input ms-10" type="radio" name="secim" value="users"> Belirli Kullanıcı Grubuna
                            </label>
                            <label>
                                <input class="form-check-input ms-10" type="radio" name="secim" value="classes"> Belirli Sınıfa
                            </label>
                        </div>
                        <!--end::Radio group-->

                        <!--begin::Users -->
                        <div id="users-div" class="none-div mb-7">
                            <!--begin::Input-->
                            <select id="roles" name="roles" aria-label="Kullanıcı Grubu Seçiniz" data-control="select2" data-placeholder="Kullanıcı Grubu Seçiniz..." class="form-select form-select-solid fw-bold">
                                <option value="">Kullanıcı Grubu Seçin</option>
                                <?php $userRole->getRoleList(); ?>
                            </select>
                            <!--end::Input-->
                        </div>
                        <!--end::Users -->

                        <!--begin::Users -->
                        <div id="classes-div" class="none-div mb-7">
                            <!--begin::Input-->
                            <select id="classes" name="classes" aria-label="Sınıf Seçiniz" data-control="select2" data-placeholder="Sınıf Seçiniz..." class="form-select form-select-solid fw-bold">
                                <option value="">Sınıf Seçin</option>
                                <?php $classShow->getClassSelectList(); ?>
                            </select>
                            <!--end::Input-->
                        </div>
                        <!--end::Users -->

                        <!--begin::Input group-->
                        <div class="d-flex flex-column mb-7 fv-row">
                            <!--begin::Label-->
                            <label class="required fs-6 fw-semibold mb-2">Bildirim</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <textarea class="form-control form-control-solid" name="notification" id="notification" ></textarea>
                            <!--end::Input-->
                        </div>
                        <!--end::Scroll-->
                    </div>
                    <!--end::Modal body-->
                    <!--begin::Modal footer-->
                    <div class="modal-footer flex-center">
                        <!--begin::Button-->
                        <button type="reset" id="kt_modal_add_customer_cancel" class="btn btn-light me-3">İptal</button>
                        <!--end::Button-->
                        <!--begin::Button-->
                        <button type="submit" id="kt_modal_add_customer_submit" class="btn btn-primary">
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