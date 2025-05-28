<?php
if (!isset($userInfo)) {
    throw new Exception("userInfo not provided");
}

$cities = [
    "Adana",
    "Adıyaman",
    "Afyonkarahisar",
    "Ağrı",
    "Amasya",
    "Ankara",
    "Antalya",
    "Artvin",
    "Aydın",
    "Balıkesir",
    "Bilecik",
    "Bingöl",
    "Bitlis",
    "Bolu",
    "Burdur",
    "Bursa",
    "Çanakkale",
    "Çankırı",
    "Çorum",
    "Denizli",
    "Diyarbakır",
    "Edirne",
    "Elazığ",
    "Erzincan",
    "Erzurum",
    "Eskişehir",
    "Gaziantep",
    "Giresun",
    "Gümüşhane",
    "Hakkâri",
    "Hatay",
    "Isparta",
    "Mersin",
    "İstanbul",
    "İzmir",
    "Kars",
    "Kastamonu",
    "Kayseri",
    "Kırklareli",
    "Kırşehir",
    "Kocaeli",
    "Konya",
    "Kütahya",
    "Malatya",
    "Manisa",
    "Kahramanmaraş",
    "Mardin",
    "Muğla",
    "Muş",
    "Nevşehir",
    "Niğde",
    "Ordu",
    "Rize",
    "Sakarya",
    "Samsun",
    "Siirt",
    "Sinop",
    "Sivas",
    "Tekirdağ",
    "Tokat",
    "Trabzon",
    "Tunceli",
    "Şanlıurfa",
    "Uşak",
    "Van",
    "Yozgat",
    "Zonguldak",
    "Aksaray",
    "Bayburt",
    "Karaman",
    "Kırıkkale",
    "Batman",
    "Şırnak",
    "Bartın",
    "Ardahan",
    "Iğdır",
    "Yalova",
    "Karabük",
    "Kilis",
    "Osmaniye",
    "Düzce"
];
?>
<div class="modal fade" id="kt_modal_update_customer" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <form class="form" action="#" id="kt_modal_update_customer_form" data-kt-redirect="profilim">

                <div class="modal-header" id="kt_modal_update_customer_header">
                    <h2 class="fw-bold">Bilgilerimi Güncelle</h2>

                    <div id="kt_modal_update_customer_close" class="btn btn-icon btn-sm btn-active-icon-primary">
                        <i class="ki-duotone ki-cross fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                </div>

                <div class="modal-body py-10 px-lg-17">
                    <div class="scroll-y me-n7 pe-7" id="kt_modal_update_customer_scroll" data-kt-scroll="true"
                        data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                        data-kt-scroll-dependencies="#kt_modal_update_customer_header"
                        data-kt-scroll-wrappers="#kt_modal_update_customer_scroll" data-kt-scroll-offset="300px">
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
                                    <div class="image-input-wrapper w-100px h-100px"
                                        style="background-image: url(assets/media/profile/<?= $userInfo['photo'] ?>">
                                    </div>

                                    <label
                                        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                        title="Görsel Ekle">
                                        <i class="ki-duotone ki-pencil fs-7">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <input type="file" name="photo" id="photo" value="<?= $userInfo['photo'] ?>"
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

                            <label class="fs-6 fw-semibold mb-2">Telefon Numarası</label>

                            <input type="phone" id="phone" class="form-control form-control-solid"
                                value="<?= $userInfo['telephone'] ?>" placeholder="Telefon" name="phone" />
                        </div>


                        <div class="fv-row mb-7">

                            <label class="fs-6 fw-semibold mb-2">Şehir</label>

                            <select id="city" name="city" aria-label="Sehir Seçiniz" data-placeholder="Sehir Seçiniz..."
                                class="form-select form-select-solid fw-bold">
                                <option value="">Şehir Seçiniz</option>
                                <?php
                                $selectedCity = $userInfo['city'];
                                foreach ($cities as $city): ?>
                                    <option value="<?= $city ?>" <?= ($selectedCity == $city) ? 'selected' : '' ?>>
                                        <?= $city ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="fv-row mb-7">

                            <label class="fs-6 fw-semibold mb-2">İlçe</label>
                            <input class="form-control form-control-solid" name="district" id="district"
                                placeholder="İlçe" value="<?= $userInfo['district'] ?>" />

                        </div>
                        <div class="fv-row mb-7">

                            <label class="fs-6 fw-semibold mb-2">Adres</label>

                            <input class="form-control form-control-solid" name="address" id="address"
                                placeholder="Adres" value="<?= $userInfo['address'] ?>" />

                        </div>
                        <div class="fv-row mb-7">

                            <label class="fs-6 fw-semibold mb-2">Posta Kod</label>

                            <input class="form-control form-control-solid" name="postcode" id="postcode"
                                placeholder="Posta Kod" value="<?= $userInfo['postcode'] ?>" />

                        </div>
                    </div>

                    <div class="modal-footer flex-center">
                        <button type="reset" id="kt_modal_update_customer_cancel"
                            class="btn btn-light btn-sm me-3">İptal</button>

                        <button type="submit" id="kt_modal_update_customer_submit" class="btn btn-primary btn-sm">
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