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
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <form class="form" action="#" id="kt_modal_add_customer_form" data-kt-redirect="bildirimler">
                <div class="modal-header" id="kt_modal_add_customer_header">
                    <h2 class="fw-bold">Bildirim Ekleyin</h2>
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

                        <div class="fv-row mb-7">
                            <label class="required fs-6 fw-semibold mb-2">Bildirim Başlığı</label>
                            <input type="text" id="title" class="form-control form-control-solid" placeholder="Bildirim"
                                name="title" />
                        </div>

                        <div class="fv-row mb-7" id="target_type">
                            <div>
                                <label class="required fs-6 fw-semibold mb-2">Bildirim Kime Yapılacak?</label>
                            </div>
                            <label>
                                <input class="form-check-input" type="radio" name="target" value="all"> Herkese
                            </label>
                            <?php if ($_SESSION['role'] != 8) { ?>
                                <label>
                                    <input class="form-check-input ms-10" type="radio" name="target" value="roles"> Belirli
                                    Kullanıcı Grubuna
                                </label>
                            <?php } ?>
                            <label>
                                <input class="form-check-input ms-10" type="radio" name="target" value="classes">
                                Belirli Sınıfa
                            </label>
                        </div>

                        <div id="roles-div" class="none-div mb-7">
                            <select id="roles" name="roles" aria-label="Kullanıcı Grubu Seçiniz" data-control="select2"
                                data-placeholder="Kullanıcı Grubu Seçiniz..."
                                class="form-select form-select-solid fw-bold">
                                <option value="">Kullanıcı Grubu Seçin</option>
                                <?php $userRole->getRoleList(); ?>
                            </select>
                        </div>

                        <div id="classes-div" class="none-div mb-7">
                            <label class=" fs-6 fw-semibold mb-2">Sınıf</label>
                            <select id="classes" name="classes" aria-label="Sınıf Seçiniz" data-control="select2"
                                data-placeholder="Sınıf Seçiniz..." class="form-select form-select-solid fw-bold">
                                <option value="">Sınıf Seçin</option>
                                <?php echo $classShow->getClassSelectListWithPreschool(); ?>
                            </select>
                       
                            <!-- <div class="fv-row mb-7">
                                <label class=" fs-6 fw-semibold mb-2">Altkonu</label>
                                <select id="subtopics" name="subtopics" aria-label="Altkonu Seçiniz"
                                    data-control="select2" data-placeholder="Altkonu Seçiniz..."
                                    class="form-select form-select-solid fw-bold">
                                </select>
                            </div> -->


                        </div>
                        <div class="fv-row mb-7">
                            <label class="form-label fw-bold text-gray-900 fs-6 required">Başlangıç
                                Tarihi</label>
                            <input type="date" class="form-control form-control-solid fw-bold pe-5"
                                placeholder="start Date" name="start_date" id="start_date">
                        </div>
                        <div class="fv-row mb-7">
                            <label class="form-label fw-bold text-gray-900 fs-6 required">Bitiş
                                Tarihi</label>
                            <input type="date" class="form-control form-control-solid fw-bold pe-5"
                                placeholder="Expire date" name="expire_date" id="expire_date">
                        </div>

                        <div class="d-flex flex-column mb-7 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Bildirim</label>
                            <textarea class="form-control form-control-solid" name="content" id="content"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer flex-center">
                        <button type="reset" id="kt_modal_add_customer_cancel" class="btn btn-light me-3">İptal</button>
                        <button type="submit" id="kt_modal_add_customer_submit" class="btn btn-primary">
                            <span class="indicator-label">Gönder</span>
                            <span class="indicator-progress">Lütfen Bekleyin...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
            </form>
        </div>
    </div>
</div>

