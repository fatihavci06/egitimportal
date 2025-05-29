<?php
if (!isset($userInfo)) {
    throw new Exception("userInfo not provided");
}
?>

<div class="modal fade" id="kt_modal_update_password" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <form class="form" action="#" id="kt_modal_update_password_form" data-kt-redirect="profilim">

                <div class="modal-header" id="kt_modal_update_password_header">
                    <h2 class="fw-bold">Şifremi Güncelle</h2>

                    <div id="kt_modal_update_password_close" class="btn btn-icon btn-sm btn-active-icon-primary">
                        <i class="ki-duotone ki-cross fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                </div>

                <div class="modal-body py-10 px-lg-17">
                    <div class="scroll-y me-n7 pe-7" id="kt_modal_update_password_scroll" data-kt-scroll="true"
                        data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                        data-kt-scroll-dependencies="#kt_modal_update_password_header"
                        data-kt-scroll-wrappers="#kt_modal_update_password_scroll" data-kt-scroll-offset="300px">

                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-semibold mb-2">Şifre</label>
                            <input class="form-control form-control-solid" type="password" placeholder="" id="password"
                                name="password" autocomplete="off" />
                        </div>
                        <div class="mb-10 fv-row" data-kt-password-meter="true">

                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold mb-2">Yeni Şifre</label>
                                <div class="position-relative mb-3">
                                    <input class="form-control form-control-solid" type="password" placeholder=""
                                        id="new-password" name="new-password" autocomplete="off" />
                                    <span
                                        class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                                        data-kt-password-meter-control="visibility">
                                        <i class="ki-duotone ki-eye-slash fs-2"></i>
                                        <i class="ki-duotone ki-eye fs-2 d-none"></i>
                                    </span>
                                </div>
                                <div class="d-flex align-items-center mb-3"
                                    data-kt-password-meter-control="highlight">
                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2">
                                    </div>
                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2">
                                    </div>
                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2">
                                    </div>
                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                                </div>
                            </div>
                            <div class="text-muted">Harf, rakam ve sembollerin karışımından oluşan 8 veya daha fazla
                                karakter kullanın.</div>
                        </div>

                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-semibold mb-2">Yeni Şifreyi Onayla</label>
                            <input class="form-control form-control-solid" type="password" placeholder=""
                                id="confirm-password" name="confirm-password" autocomplete="off" />
                        </div>
                    </div>

                    <div class="modal-footer flex-center">
                        <button type="reset" id="kt_modal_update_password_cancel"
                            class="btn btn-light btn-sm me-3">İptal</button>

                        <button type="submit" id="kt_modal_update_password_submit" class="btn btn-primary btn-sm">
                            <span class="indicator-label">Gönder</span>
                            <span class="indicator-progress">Lütfen Bekleyin...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>