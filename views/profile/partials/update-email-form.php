<?php
if (!isset($userInfo)) {
    throw new Exception("userInfo not provided");
}
?>

<div class="modal fade" id="kt_modal_update_email" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <form class="form" action="#" id="kt_modal_update_email_form" data-kt-redirect="profilim">

                <div class="modal-header" id="kt_modal_update_email_header">
                    <h2 class="fw-bold">E-Posta'mı Değiştir</h2>

                    <div id="kt_modal_update_email_close" class="btn btn-icon btn-sm btn-active-icon-primary">
                        <i class="ki-duotone ki-cross fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                </div>

                <div class="modal-body py-10 px-lg-17">
                    <div class="scroll-y me-n7 pe-7" id="kt_modal_update_email_scroll" data-kt-scroll="true"
                        data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                        data-kt-scroll-dependencies="#kt_modal_update_email_header"
                        data-kt-scroll-wrappers="#kt_modal_update_email_scroll" data-kt-scroll-offset="300px">

                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-semibold mb-2">E-Posta</label>

                            <input type="email" id="email" class="form-control form-control-solid"
                                value="<?= $userInfo['email'] ?>" placeholder="Sesli Kitap Adı" name="email" />
                        </div>

                        <div class="fv-row mb-7 d-none" id="verification_section">
                            <label class="fs-6 fw-semibold mb-2">Doğrulama Kodu</label>
                            <input type="text" id="verification_code" class="form-control form-control-solid"
                                placeholder="Doğrulama kodunu girin" name="verification_code" />
                            <button type="button" id="verify_code_button" class="btn btn-secondary btn-sm mt-2">Kodu
                                Doğrula</button>
                        </div>
                    </div>

                    <div class="modal-footer flex-center">
                        <button type="reset" id="kt_modal_update_email_cancel"
                            class="btn btn-light btn-sm me-3">İptal</button>

                        <button type="submit" id="kt_modal_update_email_submit" class="btn btn-primary btn-sm">
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