<?php
include_once "dateformat.classes.php";

class ShowSupport extends Support
{

    // Get Support List

    public function getSupportList($userId)
    {

        $supportInfo = $this->getSupport($userId);

        $dateFormat = new DateFormat();

        foreach ($supportInfo as $key => $value) {

            if($value['subject'] == 1){
                $subject = "Şikayet";
            }elseif($value['subject'] == 2){
                $subject = "Öneri";
            }elseif($value['subject'] == 3){
                $subject = "Soru";
            }

            $supportList = '
                    <tr>
                        <td>
                            <a href="destek-talebi?id='. $value['slug'] .'" class="text-gray-800 text-hover-primary mb-1">' . $value['title'] . '</a>
                        </td>
                        <td>
                            ' . $subject . '
                        </td>
                        <td>
                            ' . $value['userName'] . ' ' . $value['userSurname'] . '
                        </td>
                        <td>' . $dateFormat->changeDateHour($value['created_at']) . '</td>
                        <td class="text-end">
                            <a href="destek-talebi?id='. $value['slug'] .'" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Oku / Cevap Ver
                                <i class="ki-duotone ki-arrow-right fs-5 ms-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </a>
                            </div>
                            <!--end::Menu-->
                        </td>
                    </tr>
                ';
            echo $supportList;
        }
    }   

    // Get Support List (Admin)

    public function getSupportAdminList()
    {

        $supportInfo = $this->getSupportAdmin();

        $dateFormat = new DateFormat();

        foreach ($supportInfo as $key => $value) {

            if($value['subject'] == 1){
                $subject = "Şikayet";
            }elseif($value['subject'] == 2){
                $subject = "Öneri";
            }elseif($value['subject'] == 3){
                $subject = "Soru";
            }

            $supportList = '
                    <tr>
                        <td>
                            <a href="destek-talebi?id='. $value['slug'] .'" class="text-gray-800 text-hover-primary mb-1">' . $value['title'] . '</a>
                        </td>
                        <td>
                            ' . $subject . '
                        </td>
                        <td>
                            ' . $value['userName'] . ' ' . $value['userSurname'] . '
                        </td>
                        <td>' . $dateFormat->changeDateHour($value['created_at']) . '</td>
                        <td class="text-end">
                            <a href="destek-talebi?id='. $value['slug'] .'" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Oku / Cevap Ver
                                <i class="ki-duotone ki-arrow-right fs-5 ms-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </a>
                            </div>
                            <!--end::Menu-->
                        </td>
                    </tr>
                ';
            echo $supportList;
        }
    }   

    // Get Solved Support List

    public function getSupportSolvedList($userId)
    {

        $supportInfo = $this->getSupportSolved($userId);

        $dateFormat = new DateFormat();

        foreach ($supportInfo as $key => $value) {

            if($value['subject'] == 1){
                $subject = "Şikayet";
            }elseif($value['subject'] == 2){
                $subject = "Öneri";
            }elseif($value['subject'] == 3){
                $subject = "Soru";
            }

            $supportList = '
                    <tr>
                        <td>
                            <a href="destek-talebi?id='. $value['slug'] .'" class="text-gray-800 text-hover-primary mb-1">' . $value['title'] . '</a>
                        </td>
                        <td>
                            ' . $subject . '
                        </td>
                        <td>
                            ' . $value['userName'] . ' ' . $value['userSurname'] . '
                        </td>
                        <td>' . $dateFormat->changeDateHour($value['created_at']) . '</td>
                        <td class="text-end">
                            <a href="destek-talebi?id='. $value['slug'] .'" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Oku
                                <i class="ki-duotone ki-arrow-right fs-5 ms-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </a>
                            </div>
                            <!--end::Menu-->
                        </td>
                    </tr>
                ';
            echo $supportList;
        }
    }   

    // Get Solved Support List (Admin)

    public function getSupportSolvedAdminList()
    {

        $supportInfo = $this->getSupportSolvedAdmin();

        $dateFormat = new DateFormat();

        foreach ($supportInfo as $key => $value) {

            if($value['subject'] == 1){
                $subject = "Şikayet";
            }elseif($value['subject'] == 2){
                $subject = "Öneri";
            }elseif($value['subject'] == 3){
                $subject = "Soru";
            }

            $supportList = '
                    <tr>
                        <td>
                            <a href="destek-talebi?id='. $value['slug'] .'" class="text-gray-800 text-hover-primary mb-1">' . $value['title'] . '</a>
                        </td>
                        <td>
                            ' . $subject . '
                        </td>
                        <td>
                            ' . $value['userName'] . ' ' . $value['userSurname'] . '
                        </td>
                        <td>' . $dateFormat->changeDateHour($value['created_at']) . '</td>
                        <td class="text-end">
                            <a href="destek-talebi?id='. $value['slug'] .'" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Oku
                                <i class="ki-duotone ki-arrow-right fs-5 ms-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </a>
                            </div>
                            <!--end::Menu-->
                        </td>
                    </tr>
                ';
            echo $supportList;
        }
    } 
    
    
    // Get Support Detail List

    public function getSupportDetails($userId, $supportID)
    {

        $supportInfo = $this->getSupportDetail($userId, $supportID);

        $dateFormat = new DateFormat();

        $i = 0;

        $supportList = "";

        $form = "";

        $solved = "";

        foreach ($supportInfo as $key => $value) {

            if($value['subject'] == 1){
                $subject = "Şikayet";
            }elseif($value['subject'] == 2){
                $subject = "Öneri";
            }elseif($value['subject'] == 3){
                $subject = "Soru";
            }

            if ($i == 0) {
                
                if($value['completed'] == 0){

                $form = '<form class="form" action="#" id="kt_modal_add_customer_form" data-kt-redirect="aktif-destek-talepleri">
                    <div class="fv-row mt-7">
                        <input type="hidden" name="writer" value="' . $userId . '">
                        <input type="hidden" name="supId" value="' . $supportID . '">
                        <input type="hidden" name="openBy" value="' . $value['openedBy'] . '">
                        <input type="hidden" name="title" value="' . $value['title'] . '">
                        <input type="hidden" name="subject" value="' . $value['subject'] . '">
                        <textarea class="form-control form-control-solid placeholder-gray-600 fw-bold fs-4 ps-9 pt-7" rows="6" id="comment" name="comment" placeholder="Açıklama Yazın"></textarea>
                        <!--begin::Submit-->
                        <button type="submit" id="solved" class="btn btn-success mt-n20 mb-20 position-relative ms-7">
                            <span class="indicator-label">Çözümlendi</span>
                            <span class="indicator-progress">Lütfen Bekleyin...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                        <button type="submit" id="kt_modal_add_customer_submit" class="btn btn-primary mt-n20 mb-20 position-relative float-end me-7">
                            <span class="indicator-label">Gönder</span>
                            <span class="indicator-progress">Lütfen Bekleyin...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                        <!--end::Submit-->
                    </div>
                </form>';
                }else{
                    $solved = "<b>Çözüm Tarihi: </b>" . $dateFormat->changeDateHour($value['updated_at']);
                }

                $details = '<div class="mt-7 mb-10">
                        <b>Başlık: </b>' . $value['title'] . ' <br>
                        <b>Konu: </b>' . $subject . ' <br>
                        <b>Yazan: </b>' . $value['userName'] . ' ' . $value['userSurname'] . ' <br>
                        ' . $solved . '
                    </div>';
            }
            
            $supportList .= '
                <!--begin::Comment-->
                    <div class="mb-9">
                        <!--begin::Card-->
                        <div class="card card-bordered w-100">
                            <!--begin::Body-->
                            <div class="card-body">
                                <!--begin::Wrapper-->
                                <div class="w-100 d-flex flex-stack mb-8">
                                    <!--begin::Container-->
                                    <div class="d-flex align-items-center f">
                                        <!--begin::Author-->
                                        <div class="symbol symbol-50px me-5">
                                            <img src="assets/media/profile/' . $value['photo'] . '" alt="">
                                        </div>
                                        <!--end::Author-->
                                        <!--begin::Info-->
                                        <div class="d-flex flex-column fw-semibold fs-5 text-gray-600 text-gray-900">
                                            <!--begin::Text-->
                                            <div class="d-flex align-items-center">
                                                <!--begin::Username-->
                                                <div class="text-gray-800 fw-bold fs-5 me-3">' . $value['userName'] . ' ' . $value['userSurname'] . '</div>
                                                <!--end::Username-->
                                                <span class="m-0"></span>
                                            </div>
                                            <!--end::Text-->
                                            <!--begin::Date-->
                                            <span class="text-muted fw-semibold fs-6">'. $dateFormat->changeDateHour($value['created_at']) .'</span>
                                            <!--end::Date-->
                                        </div>
                                        <!--end::Info-->
                                    </div>
                                    <!--end::Container-->
                                </div>
                                <!--end::Wrapper-->
                                <!--begin::Desc-->
                                <p class="fw-normal fs-5 text-gray-700 m-0">' . $value['comment'] . '</p>
                                <!--end::Desc-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Card-->
                    </div>
                <!--end::Comment-->
                ';
        }
        
            echo $details . $form . $supportList;
    }
    
    // Get Support Detail List (Admin)

    public function getSupportAdminDetails($role, $userId, $supportID)
    {

        $supportInfo = $this->getSupportAdminDetail($supportID);

        $dateFormat = new DateFormat();

        $i = 0;

        $supportList = "";

        $form = "";

        $solved = "";

        foreach ($supportInfo as $key => $value) {

            if($value['subject'] == 1){
                $subject = "Şikayet";
            }elseif($value['subject'] == 2){
                $subject = "Öneri";
            }elseif($value['subject'] == 3){
                $subject = "Soru";
            }

            if ($i == 0) {

                if($role == 1){
                    $solvedButton = "";
                }else{
                    $solvedButton = '<button type="submit" id="solved" class="btn btn-success mt-n20 mb-20 position-relative ms-7">
                            <span class="indicator-label">Çözümlendi</span>
                            <span class="indicator-progress">Lütfen Bekleyin...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>';
                }
                
                if($value['completed'] == 0){

                $form = '<form class="form" action="#" id="kt_modal_add_customer_form" data-kt-redirect="aktif-destek-talepleri">
                    <div class="fv-row mt-7">
                        <input type="hidden" name="writer" value="' . $userId . '">
                        <input type="hidden" name="supId" value="' . $supportID . '">
                        <input type="hidden" name="openBy" value="' . $value['openedBy'] . '">
                        <input type="hidden" name="title" value="' . $value['title'] . '">
                        <input type="hidden" name="subject" value="' . $value['subject'] . '">
                        <textarea class="form-control form-control-solid placeholder-gray-600 fw-bold fs-4 ps-9 pt-7" rows="6" id="comment" name="comment" placeholder="Açıklama Yazın"></textarea>
                        <!--begin::Submit-->
                        ' . $solvedButton . '
                        <button type="submit" id="kt_modal_add_customer_submit" class="btn btn-primary mt-n20 mb-20 position-relative float-end me-7">
                            <span class="indicator-label">Gönder</span>
                            <span class="indicator-progress">Lütfen Bekleyin...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                        <!--end::Submit-->
                    </div>
                </form>';
                }else{
                    $solved = "<b>Çözüm Tarihi: </b>" . $dateFormat->changeDateHour($value['updated_at']);
                }

                $details = '<div class="mt-7 mb-10">
                        <b>Başlık: </b>' . $value['title'] . ' <br>
                        <b>Konu: </b>' . $subject . ' <br>
                        <b>Yazan: </b>' . $value['userName'] . ' ' . $value['userSurname'] . ' <br>
                        ' . $solved . '
                    </div>';
            }
            
            $supportList .= '
                <!--begin::Comment-->
                    <div class="mb-9">
                        <!--begin::Card-->
                        <div class="card card-bordered w-100">
                            <!--begin::Body-->
                            <div class="card-body">
                                <!--begin::Wrapper-->
                                <div class="w-100 d-flex flex-stack mb-8">
                                    <!--begin::Container-->
                                    <div class="d-flex align-items-center f">
                                        <!--begin::Author-->
                                        <div class="symbol symbol-50px me-5">
                                            <img src="assets/media/profile/' . $value['photo'] . '" alt="">
                                        </div>
                                        <!--end::Author-->
                                        <!--begin::Info-->
                                        <div class="d-flex flex-column fw-semibold fs-5 text-gray-600 text-gray-900">
                                            <!--begin::Text-->
                                            <div class="d-flex align-items-center">
                                                <!--begin::Username-->
                                                <div class="text-gray-800 fw-bold fs-5 me-3">' . $value['userName'] . ' ' . $value['userSurname'] . '</div>
                                                <!--end::Username-->
                                                <span class="m-0"></span>
                                            </div>
                                            <!--end::Text-->
                                            <!--begin::Date-->
                                            <span class="text-muted fw-semibold fs-6">'. $dateFormat->changeDateHour($value['created_at']) .'</span>
                                            <!--end::Date-->
                                        </div>
                                        <!--end::Info-->
                                    </div>
                                    <!--end::Container-->
                                </div>
                                <!--end::Wrapper-->
                                <!--begin::Desc-->
                                <p class="fw-normal fs-5 text-gray-700 m-0">' . $value['comment'] . '</p>
                                <!--end::Desc-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Card-->
                    </div>
                <!--end::Comment-->
                ';
        }
        
            echo $details . $form . $supportList;
    }

    // Get Support List Select

    public function getSupportSelectList()
    {

        $supportInfo = $this->getSupportsList();

        foreach ($supportInfo as $key => $value) {

            $supportList = '
                    <option value="' . $value['id'] . '">' . $value['name'] . '</option>
                ';
            echo $supportList;
        }
    }

    // Show Support

    public function showOneSupport($slug)
    {

        $supportInfo = $this->getOneSupport($slug);


        $supportList = '
                <div class="mb-3">
                    <h1 class="h3 d-inline align-middle">Böyle bir okul mevcut değil.</h1>
                </div>
        ';

        foreach ($supportInfo as $value) {

            $supportList = '
                <div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">
                    <!--begin::Card-->
                    <div class="card mb-5 mb-xl-8">
                        <!--begin::Card body-->
                        <div class="card-body pt-15">
                            <!--begin::Summary-->
                            <div class="d-flex flex-center flex-column mb-5">
                                <!--begin::Avatar-->
                                <!--<div class="symbol symbol-100px symbol-circle mb-7">
                                    <img src="assets/media/avatars/300-1.jpg" alt="image" />
                                </div>-->
                                <!--end::Avatar-->
                                <!--begin::Name-->
                                <a href="#" class="fs-3 text-gray-800 text-hover-primary fw-bold mb-1">' . $value['name'] . '</a>
                                <!--end::Name-->
                                <!--begin::Position-->
                                <div class="fs-5 fw-semibold text-muted mb-6">' . $value['city'] . '</div>
                                <!--end::Position-->
                                <!--begin::Info-->
                                <div class="d-flex flex-wrap flex-center">
                                    <!--begin::Stats-->
                                    <div class="border border-gray-300 border-dashed rounded py-3 px-3 mb-3">
                                        <div class="fs-4 fw-bold text-gray-700">
                                            <span class="w-75px">46</span>
                                            <i class="ki-duotone ki-arrow-up fs-3 text-success">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </div>
                                        <div class="fw-semibold text-muted">Öğrenci Sayısı</div>
                                    </div>
                                    <!--end::Stats-->
                                    <!--begin::Stats-->
                                    <div class="border border-gray-300 border-dashed rounded py-3 px-3 mx-4 mb-3">
                                        <div class="fs-4 fw-bold text-gray-700">
                                            <span class="w-50px">130</span>
                                            <i class="ki-duotone ki-arrow-down fs-3 text-danger">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </div>
                                        <div class="fw-semibold text-muted">Üniteler</div>
                                    </div>
                                    <!--end::Stats-->
                                    <!--begin::Stats-->
                                    <div class="border border-gray-300 border-dashed rounded py-3 px-3 mb-3">
                                        <div class="fs-4 fw-bold text-gray-700">
                                            <span class="w-50px">68</span>
                                            <i class="ki-duotone ki-arrow-up fs-3 text-success">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </div>
                                        <div class="fw-semibold text-muted">Sınavlar</div>
                                    </div>
                                    <!--end::Stats-->
                                </div>
                                <!--end::Info-->
                            </div>
                            <!--end::Summary-->
                            <!--begin::Details toggle-->
                            <div class="d-flex flex-stack fs-4 py-3">
                                <div class="fw-bold rotate collapsible" data-bs-toggle="collapse" href="#kt_customer_view_details" role="button" aria-expanded="false" aria-controls="kt_customer_view_details">Detaylar
                                    <span class="ms-2 rotate-180">
                                        <i class="ki-duotone ki-down fs-3"></i>
                                    </span>
                                </div>
                                <span data-bs-toggle="tooltip" data-bs-trigger="hover" title="Okul bilgilerini düzenle">
                                    <a href="#" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_update_customer">Düzenle</a>
                                </span>
                            </div>
                            <!--end::Details toggle-->
                            <div class="separator separator-dashed my-3"></div>
                            <!--begin::Details content-->
                            <div id="kt_customer_view_details" class="collapse show">
                                <div class="py-5 fs-6">
                                    <!--begin::Badge-->
                                    <!--<div class="badge badge-light-info d-inline">Premium user</div>-->
                                    <!--end::Badge-->
                                    <!--begin::Details item-->
                                    <div class="fw-bold mt-5">E-posta Adresi</div>
                                    <div class="text-gray-600">
                                        <a href="mailto: ' . $value['email'] . '" class="text-gray-600 text-hover-primary">' . $value['email'] . '</a>
                                    </div>
                                    <!--end::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bold mt-5">Okul Adresi</div>
                                    <div class="text-gray-600">' . $value['address'] . ' ' . $value['district'] . ' ' . $value['postcode'] . ' ' . $value['city'] . '</div>
                                    <!--end::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bold mt-5">Telefon Numarası</div>
                                    <div class="text-gray-600">
                                        <a href="tel: ' . $value['telephone'] . '" class="text-gray-600 text-hover-primary">' . $value['telephone'] . '</a>
                                    </div>
                                    <!--end::Details item-->
                                </div>
                            </div>
                            <!--end::Details content-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                </div>
                ';
        }
        echo $supportList;
    }

    // Update Support

    public function updateOneSupport($slug)
    {

        $supportInfo = $this->getOneSupport($slug);

        foreach ($supportInfo as $value) {

            $supportList = '
                <form class="form" action="#" id="kt_modal_update_customer_form" data-kt-redirect="okullar">
                    <!--begin::Modal header-->
                    <div class="modal-header" id="kt_modal_update_customer_header">
                        <!--begin::Modal title-->
                        <h2 class="fw-bold">Okul Güncelle</h2>
                        <!--end::Modal title-->
                        <!--begin::Close-->
                        <div id="kt_modal_update_customer_close" class="btn btn-icon btn-sm btn-active-icon-primary">
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
                        <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_update_customer_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_update_customer_header" data-kt-scroll-wrappers="#kt_modal_update_customer_scroll" data-kt-scroll-offset="300px">
                            <!--begin::User toggle-->
                            <div class="fw-bold fs-3 rotate collapsible mb-7" data-bs-toggle="collapse" href="#kt_modal_update_customer_user_info" role="button" aria-expanded="false" aria-controls="kt_modal_update_customer_user_info">Okul Bilgileri
                                <span class="ms-2 rotate-180">
                                    <i class="ki-duotone ki-down fs-3"></i>
                                </span>
                            </div>
                            <!--end::User toggle-->
                            <!--begin::User form-->
                            <div id="kt_modal_update_customer_user_info" class="collapse show">
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">Okul Adı</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" id="name" class="form-control form-control-solid" value="'. $value['name'] .'" name="name" />
                                    <input type="hidden" name="old_slug" id="old_slug" value="'. $value['slug'] .'" />
                                <!--end::Input-->
                            </div>

                            <!--end::Input group-->
                            <div id="kt_modal_add_customer_billing_info" class="collapse show">
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row">
                                    <!--begin::Label-->
                                    <label class="required fs-6 fw-semibold mb-2">Adres</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input class="form-control form-control-solid" name="address" id="address" value="'. $value['address'] .'" />
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
                                        <input class="form-control form-control-solid" name="district" id="district" value="'. $value['district'] .'" />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <!--begin::Label-->
                                        <label class="fs-6 fw-semibold mb-2">Posta Kodu</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input class="form-control form-control-solid" name="postcode" id="postcode" value="'. $value['postcode'] .'" />
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
                                    <select id="city" name="city" aria-label="Sehir Seçiniz" data-control="select2" data-dropdown-parent="#kt_modal_update_customer"
                                        class="form-select form-select-solid fw-bold">
                                        <option value="'. $value['city'] .'">'. $value['city'] .'</option>
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
                                    <label class="fs-6 fw-semibold mb-2">
                                        <span class="required">E-posta Adresi</span>
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="email" class="form-control form-control-solid" name="email" id="email" value="'. $value['email'] .'" />
                                    <input type="hidden" name="email_old" id="email_old" value="'. $value['email'] .'" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="required fs-6 fw-semibold mb-2">Telefon Numarası</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="number" class="form-control form-control-solid" value="'. $value['telephone'] .'" id="telephone" name="telephone" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::User form-->
                        </div>
                        <!--end::Scroll-->
                    </div>
                    <!--end::Modal body-->
                    <!--begin::Modal footer-->
                    <div class="modal-footer flex-center">
                        <!--begin::Button-->
                        <button type="reset" id="kt_modal_update_customer_cancel" class="btn btn-light me-3">İptal</button>
                        <!--end::Button-->
                        <!--begin::Button-->
                        <button type="submit" id="kt_modal_update_customer_submit" class="btn btn-primary">
                            <span class="indicator-label">Gönder</span>
                            <span class="indicator-progress">Lütfen bekleyin...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                        <!--end::Button-->
                    </div>
                    <!--end::Modal footer-->
                </form>
                ';
        }
        echo $supportList;
    }

    // List of Supports

    public function showSupportList()
    {

        $supportInfo = $this->getSupports();

        $supportList = '';

        $divStart = '<div class="row"><div class="overflow-auto col-12 col-lg-2" style="max-height: 150px">';

        $divEnd = '</div></div>';

        foreach ($supportInfo as $value) {

            $parentId = $value['id'];

            $schoolForms = '<div class="form-check">
                                            <input class="form-check-input" name="category[]" type="checkbox" value="' . $value['id'] . '" id="' . $value['slug'] . '">
                                                <label class="form-check-label" for="' . $value['slug'] . '">
                                                ' . $value['catName'] . '
                                                </label>
                                        </div>';



            $supportList .=  $schoolForms;
        }


        echo $divStart . $supportList . $divEnd;
    }
}

class ShowSupportForUsers extends Support
{

    // Get SupportForUsers List

    public function getSupportForUsersList()
    {

        $supportInfo = $this->getSupportsForUsersList($_SESSION['role'], $_SESSION['class_id']);

        $dateFormat = new DateFormat();

        foreach ($supportInfo as $key => $value) {

            if($value['toAll'] == 1){
                $toWhom = "Herkese";
            }elseif ($value['class_id'] != 0) {
                $toWhom = $this->getSupportsForUsersClass($value['class_id']);
            }elseif ($value['role_id'] != 0) {
                $toWhom = $this->getSupportsForUsersRole($value['role_id']);
            }

            $supportList = '
                    <tr>
                        <td>
                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="1" />
                            </div>
                        </td>
                        <td>
                            <a href="#" class="text-gray-800 text-hover-primary mb-1">' . $value['name'] . '</a>
                        </td>
                        <td>
                            ' . $value['content'] . '
                        </td>
                        <td>
                            ' . $toWhom . '
                        </td>
                        <td>' . $dateFormat->changeDate($value['created_at']) . '</td>
                        <td class="text-end">
                            <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">İşlemler
                                <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                            <!--begin::Menu-->
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                data-kt-menu="true">
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="./okul-detay/' . $value['slug'] . '" class="menu-link px-3">Görüntüle</a>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-customer-table-filter="delete_row">Pasif Yap</a>
                                </div>
                                <!--end::Menu item-->
                            </div>
                            <!--end::Menu-->
                        </td>
                    </tr>
                ';
            echo $supportList;
        }
    }

    // Get SupportForUsers List Select

    public function getSupportForUsersSelectList()
    {

        $supportInfo = $this->getSupportsForUsersList($_SESSION['role'], $_SESSION['class_id']);

        foreach ($supportInfo as $key => $value) {

            $supportList = '
                    <option value="' . $value['id'] . '">' . $value['name'] . '</option>
                ';
            echo $supportList;
        }
    }

    // Show SupportForUsers

    public function showOneSupportForUsers($slug)
    {

        $supportInfo = $this->getOneSupportForUsers($slug);


        $supportList = '
                <div class="mb-3">
                    <h1 class="h3 d-inline align-middle">Böyle bir okul mevcut değil.</h1>
                </div>
        ';

        foreach ($supportInfo as $value) {

            $supportList = '
                <div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">
                    <!--begin::Card-->
                    <div class="card mb-5 mb-xl-8">
                        <!--begin::Card body-->
                        <div class="card-body pt-15">
                            <!--begin::Summary-->
                            <div class="d-flex flex-center flex-column mb-5">
                                <!--begin::Avatar-->
                                <!--<div class="symbol symbol-100px symbol-circle mb-7">
                                    <img src="assets/media/avatars/300-1.jpg" alt="image" />
                                </div>-->
                                <!--end::Avatar-->
                                <!--begin::Name-->
                                <a href="#" class="fs-3 text-gray-800 text-hover-primary fw-bold mb-1">' . $value['name'] . '</a>
                                <!--end::Name-->
                                <!--begin::Position-->
                                <div class="fs-5 fw-semibold text-muted mb-6">' . $value['city'] . '</div>
                                <!--end::Position-->
                                <!--begin::Info-->
                                <div class="d-flex flex-wrap flex-center">
                                    <!--begin::Stats-->
                                    <div class="border border-gray-300 border-dashed rounded py-3 px-3 mb-3">
                                        <div class="fs-4 fw-bold text-gray-700">
                                            <span class="w-75px">46</span>
                                            <i class="ki-duotone ki-arrow-up fs-3 text-success">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </div>
                                        <div class="fw-semibold text-muted">Öğrenci Sayısı</div>
                                    </div>
                                    <!--end::Stats-->
                                    <!--begin::Stats-->
                                    <div class="border border-gray-300 border-dashed rounded py-3 px-3 mx-4 mb-3">
                                        <div class="fs-4 fw-bold text-gray-700">
                                            <span class="w-50px">130</span>
                                            <i class="ki-duotone ki-arrow-down fs-3 text-danger">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </div>
                                        <div class="fw-semibold text-muted">Üniteler</div>
                                    </div>
                                    <!--end::Stats-->
                                    <!--begin::Stats-->
                                    <div class="border border-gray-300 border-dashed rounded py-3 px-3 mb-3">
                                        <div class="fs-4 fw-bold text-gray-700">
                                            <span class="w-50px">68</span>
                                            <i class="ki-duotone ki-arrow-up fs-3 text-success">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </div>
                                        <div class="fw-semibold text-muted">Sınavlar</div>
                                    </div>
                                    <!--end::Stats-->
                                </div>
                                <!--end::Info-->
                            </div>
                            <!--end::Summary-->
                            <!--begin::Details toggle-->
                            <div class="d-flex flex-stack fs-4 py-3">
                                <div class="fw-bold rotate collapsible" data-bs-toggle="collapse" href="#kt_customer_view_details" role="button" aria-expanded="false" aria-controls="kt_customer_view_details">Detaylar
                                    <span class="ms-2 rotate-180">
                                        <i class="ki-duotone ki-down fs-3"></i>
                                    </span>
                                </div>
                                <span data-bs-toggle="tooltip" data-bs-trigger="hover" title="Okul bilgilerini düzenle">
                                    <a href="#" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_update_customer">Düzenle</a>
                                </span>
                            </div>
                            <!--end::Details toggle-->
                            <div class="separator separator-dashed my-3"></div>
                            <!--begin::Details content-->
                            <div id="kt_customer_view_details" class="collapse show">
                                <div class="py-5 fs-6">
                                    <!--begin::Badge-->
                                    <!--<div class="badge badge-light-info d-inline">Premium user</div>-->
                                    <!--end::Badge-->
                                    <!--begin::Details item-->
                                    <div class="fw-bold mt-5">E-posta Adresi</div>
                                    <div class="text-gray-600">
                                        <a href="mailto: ' . $value['email'] . '" class="text-gray-600 text-hover-primary">' . $value['email'] . '</a>
                                    </div>
                                    <!--end::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bold mt-5">Okul Adresi</div>
                                    <div class="text-gray-600">' . $value['address'] . ' ' . $value['district'] . ' ' . $value['postcode'] . ' ' . $value['city'] . '</div>
                                    <!--end::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bold mt-5">Telefon Numarası</div>
                                    <div class="text-gray-600">
                                        <a href="tel: ' . $value['telephone'] . '" class="text-gray-600 text-hover-primary">' . $value['telephone'] . '</a>
                                    </div>
                                    <!--end::Details item-->
                                </div>
                            </div>
                            <!--end::Details content-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                </div>
                ';
        }
        echo $supportList;
    }

    // Update SupportForUsers

    public function updateOneSupportForUsers($slug)
    {

        $supportInfo = $this->getOneSupportForUsers($slug);

        foreach ($supportInfo as $value) {

            $supportList = '
                <form class="form" action="#" id="kt_modal_update_customer_form" data-kt-redirect="okullar">
                    <!--begin::Modal header-->
                    <div class="modal-header" id="kt_modal_update_customer_header">
                        <!--begin::Modal title-->
                        <h2 class="fw-bold">Okul Güncelle</h2>
                        <!--end::Modal title-->
                        <!--begin::Close-->
                        <div id="kt_modal_update_customer_close" class="btn btn-icon btn-sm btn-active-icon-primary">
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
                        <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_update_customer_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_update_customer_header" data-kt-scroll-wrappers="#kt_modal_update_customer_scroll" data-kt-scroll-offset="300px">
                            <!--begin::User toggle-->
                            <div class="fw-bold fs-3 rotate collapsible mb-7" data-bs-toggle="collapse" href="#kt_modal_update_customer_user_info" role="button" aria-expanded="false" aria-controls="kt_modal_update_customer_user_info">Okul Bilgileri
                                <span class="ms-2 rotate-180">
                                    <i class="ki-duotone ki-down fs-3"></i>
                                </span>
                            </div>
                            <!--end::User toggle-->
                            <!--begin::User form-->
                            <div id="kt_modal_update_customer_user_info" class="collapse show">
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">Okul Adı</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" id="name" class="form-control form-control-solid" value="'. $value['name'] .'" name="name" />
                                    <input type="hidden" name="old_slug" id="old_slug" value="'. $value['slug'] .'" />
                                <!--end::Input-->
                            </div>

                            <!--end::Input group-->
                            <div id="kt_modal_add_customer_billing_info" class="collapse show">
                                <!--begin::Input group-->
                                <div class="d-flex flex-column mb-7 fv-row">
                                    <!--begin::Label-->
                                    <label class="required fs-6 fw-semibold mb-2">Adres</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input class="form-control form-control-solid" name="address" id="address" value="'. $value['address'] .'" />
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
                                        <input class="form-control form-control-solid" name="district" id="district" value="'. $value['district'] .'" />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <!--begin::Label-->
                                        <label class="fs-6 fw-semibold mb-2">Posta Kodu</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input class="form-control form-control-solid" name="postcode" id="postcode" value="'. $value['postcode'] .'" />
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
                                    <select id="city" name="city" aria-label="Sehir Seçiniz" data-control="select2" data-dropdown-parent="#kt_modal_update_customer"
                                        class="form-select form-select-solid fw-bold">
                                        <option value="'. $value['city'] .'">'. $value['city'] .'</option>
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
                                    <label class="fs-6 fw-semibold mb-2">
                                        <span class="required">E-posta Adresi</span>
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="email" class="form-control form-control-solid" name="email" id="email" value="'. $value['email'] .'" />
                                    <input type="hidden" name="email_old" id="email_old" value="'. $value['email'] .'" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="required fs-6 fw-semibold mb-2">Telefon Numarası</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="number" class="form-control form-control-solid" value="'. $value['telephone'] .'" id="telephone" name="telephone" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::User form-->
                        </div>
                        <!--end::Scroll-->
                    </div>
                    <!--end::Modal body-->
                    <!--begin::Modal footer-->
                    <div class="modal-footer flex-center">
                        <!--begin::Button-->
                        <button type="reset" id="kt_modal_update_customer_cancel" class="btn btn-light me-3">İptal</button>
                        <!--end::Button-->
                        <!--begin::Button-->
                        <button type="submit" id="kt_modal_update_customer_submit" class="btn btn-primary">
                            <span class="indicator-label">Gönder</span>
                            <span class="indicator-progress">Lütfen bekleyin...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                        <!--end::Button-->
                    </div>
                    <!--end::Modal footer-->
                </form>
                ';
        }
        echo $supportList;
    }

    // List of SupportsForUsers

    public function showSupportForUsersList()
    {

        $supportInfo = $this->getSupportsForUsers();

        $supportList = '';

        $divStart = '<div class="row"><div class="overflow-auto col-12 col-lg-2" style="max-height: 150px">';

        $divEnd = '</div></div>';

        foreach ($supportInfo as $value) {

            $parentId = $value['id'];

            $schoolForms = '<div class="form-check">
                                            <input class="form-check-input" name="category[]" type="checkbox" value="' . $value['id'] . '" id="' . $value['slug'] . '">
                                                <label class="form-check-label" for="' . $value['slug'] . '">
                                                ' . $value['catName'] . '
                                                </label>
                                        </div>';



            $supportList .=  $schoolForms;
        }


        echo $divStart . $supportList . $divEnd;
    }
}

