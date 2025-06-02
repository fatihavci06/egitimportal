<?php
include_once "dateformat.classes.php";

class ShowTechnicalServiceSupport extends TechnicalServiceSupport
{

    // Get Support List

    public function getTechnicalServiceSupportList($techSupportId)
    {

        $supportInfo = $this->getTechnicalServiceSupportById($techSupportId);

        $dateFormat = new DateFormat();

        foreach ($supportInfo as $key => $value) {

            $supportList = '
                    <tr>
                        <td>
                            <a href="teknik-servis-destek-talebi?id=' . $value['slug'] . '" class="text-gray-800 text-hover-primary mb-1">' . $value['title'] . '</a>
                        </td>
                        <td>
                            ' .  $value['subjectName'] . '
                        </td>
                        <td>
                            ' . $value['userName'] . ' ' . $value['userSurname'] . '
                        </td>
                        <td>' . $dateFormat->changeDateHour($value['created_at']) . '</td>
                        <td class="text-end">
                            <a href="teknik-servis-destek-talebi?id=' . $value['slug'] . '" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
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

    public function getTechnicalServiceSupportAdminList()
    {

        $supportInfo = $this->getTechnicalServiceSupportAdmin();

        $dateFormat = new DateFormat();

        foreach ($supportInfo as $key => $value) {

            $supportList = '
                    <tr>
                        <td>
                            <a href="teknik-servis-destek-talebi?id=' . $value['slug'] . '" class="text-gray-800 text-hover-primary mb-1">' . $value['title'] . '</a>
                        </td>
                        <td>
                            ' . $value['subjectName'] . '
                        </td>
                        <td>
                            ' . $value['userName'] . ' ' . $value['userSurname'] . '
                        </td>
                        <td>' . $dateFormat->changeDateHour($value['created_at']) . '</td>
                        <td class="text-end">
                            <a href="teknik-servis-destek-talebi?id=' . $value['slug'] . '" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Oku / Cevap Ver
                                <i class="ki-duotone ki-arrow-right fs-5 ms-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </a>
                            <!--end::Menu-->
                        </td>
                    </tr>
                ';
            echo $supportList;
        }
    }

    public function getTechnicalServiceSupportDetails($userId, $supportID)
    {

        $supportInfo = $this->getTechnicalServiceSupportDetail($userId, $supportID);

        $dateFormat = new DateFormat();

        $i = 0;

        $supportList = "";

        $form = "";

        $solved = "";

        foreach ($supportInfo as $key => $value) {

            if ($i == 0) {

                if ($value['completed'] == 0) {

                    $form = '<form class="form" action="#" id="kt_modal_add_customer_form" data-kt-redirect="aktif-destek-talepleri">
                    <div class="fv-row mt-7">
                        <input type="hidden" name="writer" value="' . $userId . '">
                        <input type="hidden" name="supId" value="' . $supportID . '">
                        <input type="hidden" name="openBy" value="' . $value['openedBy'] . '">
                        <input type="hidden" name="title" value="' . $value['title'] . '">
                        <input type="hidden" name="subject" value="' . $value['subject'] . '">
                        <textarea class="form-control form-control-solid placeholder-gray-600 fw-bold fs-4 ps-9 pt-7" rows="6" id="comment" name="comment" placeholder="Açıklama Yazın"></textarea>
                        <!--begin::Submit-->
                        <button type="submit" id="solved" class="btn btn-success btn-sm mt-n20 mb-20 position-relative ms-7">
                            <span class="indicator-label">Çözümlendi</span>
                            <span class="indicator-progress">Lütfen Bekleyin...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                        <button type="submit" id="kt_modal_add_customer_submit" class="btn btn-primary btn-sm mt-n20 mb-20 position-relative float-end me-7">
                            <span class="indicator-label">Gönder</span>
                            <span class="indicator-progress">Lütfen Bekleyin...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                        <!--end::Submit-->
                    </div>
                </form>';
                } else {
                    $solved = "<b>Çözüm Tarihi: </b>" . $dateFormat->changeDateHour($value['updated_at']);
                }

                $details = '<div class="mt-7 mb-10">
                        <b>Başlık: </b>' . $value['title'] . ' <br>
                        <b>Konu: </b>' . $value['subjectName'] . ' <br>
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
                                            <span class="text-muted fw-semibold fs-6">' . $dateFormat->changeDateHour($value['created_at']) . '</span>
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

    public function getTechnicalServiceSupportAdminDetails($role, $userId, $supportID)
    {

        $supportInfo = $this->getTechnicalServiceSupportAdminDetail($supportID);

        $dateFormat = new DateFormat();

        $i = 0;

        $supportList = "";

        $form = "";

        $solved = "";

        foreach ($supportInfo as $key => $value) {


            if ($i == 0) {

                if ($role == 1) {
                    $solvedButton = "";
                } else {
                    $solvedButton = '<button type="submit" id="solved" class="btn btn-success btn-sm mt-n20 mb-20 position-relative ms-7">
                            <span class="indicator-label">Çözümlendi</span>
                            <span class="indicator-progress">Lütfen Bekleyin...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>';
                }

                if ($value['completed'] == 0) {

                    $form = '<form class="form" action="#" id="kt_modal_add_customer_form" data-kt-redirect="aktif-teknik-servis-destek-talepleri">
                    <div class="fv-row mt-7">
                        <input type="hidden" name="writer" value="' . $userId . '">
                        <input type="hidden" name="supId" value="' . $supportID . '">
                        <input type="hidden" name="openBy" value="' . $value['openedBy'] . '">
                        <input type="hidden" name="title" value="' . $value['title'] . '">
                        <input type="hidden" name="subject" value="' . $value['subject'] . '">
                        <textarea class="form-control form-control-solid placeholder-gray-600 fw-bold fs-4 ps-9 pt-7" rows="6" id="comment" name="comment" placeholder="Açıklama Yazın"></textarea>
                        <!--begin::Submit-->
                        ' . $solvedButton . '
                        <button type="submit" id="kt_modal_add_customer_submit" class="btn btn-primary btn-sm mt-n20 mb-20 position-relative float-end me-7">
                            <span class="indicator-label">Gönder</span>
                            <span class="indicator-progress">Lütfen Bekleyin...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                        <!--end::Submit-->
                    </div>
                </form>';
                } else {
                    $solved = "<b>Çözüm Tarihi: </b>" . $dateFormat->changeDateHour($value['updated_at']);
                }

                $details = '<div class="mt-7 mb-10">
                        <b>Başlık: </b>' . $value['title'] . ' <br>
                        <b>Konu: </b>' . $value['subjectName'] . ' <br>
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
                                            <span class="text-muted fw-semibold fs-6">' . $dateFormat->changeDateHour($value['created_at']) . '</span>
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

    public function getTechnicalServiceSupportSolvedList($userId)
    {

        $supportInfo = $this->getTechnicalServiceSupportSolved($userId);

        $dateFormat = new DateFormat();

        foreach ($supportInfo as $key => $value) {

            $supportList = '
                    <tr>
                        <td>
                            <a href="teknik-servis-destek-talebi?id=' . $value['slug'] . '" class="text-gray-800 text-hover-primary mb-1">' . $value['title'] . '</a>
                        </td>
                        <td>
                            ' .  $value['subjectName'] . '
                        </td>
                        <td>
                            ' . $value['userName'] . ' ' . $value['userSurname'] . '
                        </td>
                        <td>' . $dateFormat->changeDateHour($value['created_at']) . '</td>
                        <td class="text-end">
                            <a href="teknik-servis-destek-talebi?id=' . $value['slug'] . '" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
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

    public function getTechnicalServiceSupportSolvedAdminList()
    {

        $supportInfo = $this->getTechnicalServiceSupportSolvedAdmin();

        $dateFormat = new DateFormat();

        foreach ($supportInfo as $key => $value) {

            $supportList = '
                    <tr>
                        <td>
                            <a href="teknik-servis-destek-talebi?id=' . $value['slug'] . '" class="text-gray-800 text-hover-primary mb-1">' . $value['title'] . '</a>
                        </td>
                        <td>
                            ' . $value['subjectName'] . '
                        </td>
                        <td>
                            ' . $value['userName'] . ' ' . $value['userSurname'] . '
                        </td>
                        <td>' . $dateFormat->changeDateHour($value['created_at']) . '</td>
                        <td class="text-end">
                            <a href="teknik-servis-destek-talebi?id=' . $value['slug'] . '" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
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
}
