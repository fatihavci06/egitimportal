<?php
if (!defined('GUARD')) {
    die('Erişim yasak!');
}
include_once "classes/classes.classes.php";
include_once "classes/classes-view.classes.php";

$chooseClass = new ShowClass();
$chooseSchool = new ShowSchool();
?>
<div class="modal fade" id="kt_modal_add_customer" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Form-->
            <form class="form" action="#" id="kt_modal_add_customer_form" data-kt-redirect="ogrenciler" enctype="multipart/form-data">
                <!--begin::Modal header-->
                <div class="modal-header" id="kt_modal_add_customer_header">
                    <!--begin::Modal title-->
                    <h2 class="fw-bold">Öğrenci Ekleyin</h2>
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
                            <label class="required fs-6 fw-semibold mb-2">Öğrenci Adı</label>
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
                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold mb-2 required">Öğrencinin Türkiye Cumhuriyeti Kimlik Numarası</label>
                                <input class="form-control form-control-lg form-control-solid" type="text" placeholder="Kimlik Numarası" name="tckn" id="tckn" autocomplete="off" />
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
                                    <option value="Kız">Kız</option>
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
                            <div class="row fv-row mb-7">
                                <!--begin::Col-->
                                <div class="col-xl-6">
                                    <label class="fs-6 fw-semibold mb-2 required">Velinin Adı</label>
                                    <input class="form-control form-control-lg form-control-solid" type="text" placeholder="Ad" name="parent-first-name" id="parent-first-name" autocomplete="off" />
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-xl-6">
                                    <label class="fs-6 fw-semibold mb-2 required">Velinin Soyadı</label>
                                    <input class="form-control form-control-lg form-control-solid" type="text" placeholder="Soyad" name="parent-last-name" id="parent-last-name" autocomplete="off" />
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="d-flex flex-column mb-7 fv-row">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">Adres</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input class="form-control form-control-solid" name="address" id="address" placeholder="Adres" />
                                <!--end::Input-->
                            </div>
                            <!--begin::Input group-->
                            <div class="row g-9 mb-7">
                                <!--begin::Col-->
                                <div class="col-md-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="required fs-6 fw-semibold mb-2">İlçe</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input class="form-control form-control-solid" name="district" id="district" placeholder="İlçe" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-md-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-semibold mb-2">Posta Kodu</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input class="form-control form-control-solid" name="postcode" id="postcode" placeholder="Posta Kodu" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="d-flex flex-column mb-7 fv-row">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">
                                    <span class="required">Şehir</span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select id="city" name="city" aria-label="Sehir Seçiniz"
                                    data-placeholder="Sehir Seçiniz..."
                                    class="form-select form-select-solid fw-bold">
                                    <option value="">Şehir Seçin</option>
                                    <option value="Adana">Adana</option>
                                    <option value="Adıyaman">Adıyaman</option>
                                    <option value="Afyonkarahisar">Afyonkarahisar</option>
                                    <option value="Ağrı">Ağrı</option>
                                    <option value="Amasya">Amasya</option>
                                    <option value="Ankara">Ankara</option>
                                    <option value="Antalya">Antalya</option>
                                    <option value="Artvin">Artvin</option>
                                    <option value="Aydın">Aydın</option>
                                    <option value="Balıkesir">Balıkesir</option>
                                    <option value="Bilecik">Bilecik</option>
                                    <option value="Bingöl">Bingöl</option>
                                    <option value="Bitlis">Bitlis</option>
                                    <option value="Bolu">Bolu</option>
                                    <option value="Burdur">Burdur</option>
                                    <option value="Bursa">Bursa</option>
                                    <option value="Çanakkale">Çanakkale</option>
                                    <option value="Çankırı">Çankırı</option>
                                    <option value="Çorum">Çorum</option>
                                    <option value="Denizli">Denizli</option>
                                    <option value="Diyarbakır">Diyarbakır</option>
                                    <option value="Edirne">Edirne</option>
                                    <option value="Elazığ">Elazığ</option>
                                    <option value="Erzincan">Erzincan</option>
                                    <option value="Erzurum">Erzurum</option>
                                    <option value="Eskişehir">Eskişehir</option>
                                    <option value="Gaziantep">Gaziantep</option>
                                    <option value="Giresun">Giresun</option>
                                    <option value="Gümüşhane">Gümüşhane</option>
                                    <option value="Hakkâri">Hakkâri</option>
                                    <option value="Hatay">Hatay</option>
                                    <option value="Isparta">Isparta</option>
                                    <option value="Mersin">Mersin</option>
                                    <option value="İstanbul">İstanbul</option>
                                    <option value="İzmir">İzmir</option>
                                    <option value="Kars">Kars</option>
                                    <option value="Kastamonu">Kastamonu</option>
                                    <option value="Kayseri">Kayseri</option>
                                    <option value="Kırklareli">Kırklareli</option>
                                    <option value="Kırşehir">Kırşehir</option>
                                    <option value="Kocaeli">Kocaeli</option>
                                    <option value="Konya">Konya</option>
                                    <option value="Kütahya">Kütahya</option>
                                    <option value="Malatya">Malatya</option>
                                    <option value="Manisa">Manisa</option>
                                    <option value="Kahramanmaraş">Kahramanmaraş</option>
                                    <option value="Mardin">Mardin</option>
                                    <option value="Muğla">Muğla</option>
                                    <option value="Muş">Muş</option>
                                    <option value="Nevşehir">Nevşehir</option>
                                    <option value="Niğde">Niğde</option>
                                    <option value="Ordu">Ordu</option>
                                    <option value="Rize">Rize</option>
                                    <option value="Sakarya">Sakarya</option>
                                    <option value="Samsun">Samsun</option>
                                    <option value="Siirt">Siirt</option>
                                    <option value="Sinop">Sinop</option>
                                    <option value="Sivas">Sivas</option>
                                    <option value="Tekirdağ">Tekirdağ</option>
                                    <option value="Tokat">Tokat</option>
                                    <option value="Trabzon">Trabzon</option>
                                    <option value="Tunceli">Tunceli</option>
                                    <option value="Şanlıurfa">Şanlıurfa</option>
                                    <option value="Uşak">Uşak</option>
                                    <option value="Van">Van</option>
                                    <option value="Yozgat">Yozgat</option>
                                    <option value="Zonguldak">Zonguldak</option>
                                    <option value="Aksaray">Aksaray</option>
                                    <option value="Bayburt">Bayburt</option>
                                    <option value="Karaman">Karaman</option>
                                    <option value="Kırıkkale">Kırıkkale</option>
                                    <option value="Batman">Batman</option>
                                    <option value="Şırnak">Şırnak</option>
                                    <option value="Bartın">Bartın</option>
                                    <option value="Ardahan">Ardahan</option>
                                    <option value="Iğdır">Iğdır</option>
                                    <option value="Yalova">Yalova</option>
                                    <option value="Karabük">Karabük</option>
                                    <option value="Kilis">Kilis</option>
                                    <option value="Osmaniye">Osmaniye</option>
                                    <option value="Düzce">Düzce</option>
                                </select>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">Telefon Numarası</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-solid" placeholder="05001234578" id="telephone" name="telephone" />
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
                                    <option value="">Okul Seçin</option>
                                    <?php $chooseSchool->getSchoolSelectList(); ?>
                                </select>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="d-flex flex-column mb-7 fv-row">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">
                                    <span class="required">Öğrenci Türü</span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="secenek" id="normal" value="normal">
                                    <label class="form-check-label" for="normal">Normal</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="secenek" id="burslu" value="burslu">
                                    <label class="form-check-label" for="burslu">Burslu</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="secenek" id="ucretsiz" value="ucretsiz">
                                    <label class="form-check-label" for="ucretsiz">Ücretsiz</label>
                                </div>
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
                                    <?php echo $chooseClass->getClassSelectListForCreateAccount(); ?>
                                </select>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div id="veriAlani">

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