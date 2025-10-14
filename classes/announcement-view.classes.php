<?php
include_once "dateformat.classes.php";
include_once "announcement.classes.php";
class ShowAnnouncement extends AnnouncementManager
{

    public function getAnnouncementList()
    {

        $announcementInfo = $this->getAllAnnouncements();
        // id, title, content , slug, start_date, expire_date, created_by, target_type, is_active, created_at
        $dateFormat = new DateFormat();

        foreach ($announcementInfo as $key => $value) {


            if ($value['target_type'] == "all") {
                $toWhom = "Herkese";
            } elseif ($value['target_type'] == "classes") {
                $toWhom = $this->getAnnouncementsClass($value['targets'][0]['value']);
            } elseif ($value['target_type'] == "roles") {
                $toWhom = $this->getAnnouncementsRole($value['targets'][0]['value']);
            }
            $active_status = '<span class="badge badge-light-success">Aktif</span>';
            if (!$value['is_active']) {
                $active_status = '<span class="badge badge-light-danger">Pasif</span>';
            }

            $alter_button = $value['is_active'] ? "Pasif Yap" : "Aktif Yap";
            $announcementList = '
                    <tr id="' . $value['id'] . '">
                        <td>
                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="1" />
                            </div>
                        </td>
                        <td>
                            <a href="./duyuru/' . $value['slug'] . '" class="text-gray-800 text-hover-primary mb-1">' . $value['title'] . '</a>
                        </td>
                        <td>
                            <span class="symbol symbol-10px me-2">

                            ' . $active_status . '
                            </span>
                        </td>
                        <td>
                        ' . (strlen($value['content']) > 40 ? substr($value['content'], 0, 40) . '...' : $value['content']) . '
                        </td>
                        <td>
                            ' . $toWhom . '
                        </td>
                        <td>' . $dateFormat->changeDate($value['start_date']) . '</td>
                        

                        <td class="text-end">
                            <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">İşlemler
                                <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                data-kt-menu="true">
                                <div class="menu-item px-3">
                                    <a href="./duyuru-detay/' . $value['slug'] . '" class="menu-link px-3">Görüntüle</a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-customer-table-filter="delete_row">' . $alter_button . '</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                ';
            echo $announcementList;
        }
    }

    public function getAnnouncementForStudentList($role, $class_id)
    {

        $announcementInfo = $this->getAnnouncementsWithViewStatus($_SESSION['id'], $role, $class_id);

        $dateFormat = new DateFormat();

        foreach ($announcementInfo as $key => $value) {
            $view = "Görüntülenmedi";
            $style = "text-gray-700";
            if ($value['target_type'] == "all") {
                $toWhom = "Herkese";
            } elseif ($value['target_type'] == "classes") {
                $toWhom = "Sınıfıma";
            } elseif ($value['target_type'] == "roles") {
                $toWhom = "Tüm Öğrencilere";
            }
            if ($value["is_viewed"] == 1) {
                $view = "Görüntülendi";
                $style = "text-gray-500";

            }
            $announcementList = '
                    <tr class="' . $style . '">
                        <td>
                            <a href="./duyuru/' . $value['slug'] . '" class=" ' . $style . ' text-hover-primary mb-1">' . $value['title'] . '</a>
                        </td>
                        <td>
                        ' . (strlen($value['content']) > 40 ? substr($value['content'], 0, 40) . '...' : $value['content']) . '
                        </td>

                        <td>' . $dateFormat->changeDate($value['start_date']) . '</td>
                        <td>
                            ' . $view . '
                        </td>
                    </tr>
                ';
            echo $announcementList;
        }
    }

    public function getAnnouncementForCoordinatorsList($role)
    {

        $announcementInfo = $this->getAnnouncementsWithViewStatusCoord($_SESSION['id'], $role);

        $dateFormat = new DateFormat();

        foreach ($announcementInfo as $key => $value) {
            $view = "Görüntülenmedi";
            $style = "text-gray-700";
            if ($value['target_type'] == "all") {
                $toWhom = "Herkese";
            } elseif ($value['target_type'] == "roles") {
                $toWhom = "Okul Koordinatörlerine";
            }
            if ($value["is_viewed"] == 1) {
                $view = "Görüntülendi";
                $style = "text-gray-500";

            }
            $announcementList = '
                    <tr class="' . $style . '">
                        <td>
                            <a href="./duyuru/' . $value['slug'] . '" class=" ' . $style . ' text-hover-primary mb-1">' . $value['title'] . '</a>
                        </td>
                        <td>
                        ' . (strlen($value['content']) > 40 ? substr($value['content'], 0, 40) . '...' : $value['content']) . '
                        </td>
                        <td>
                            ' . $toWhom . '
                        </td>
                        <td>' . $dateFormat->changeDate($value['start_date']) . '</td>
                        <td>
                            ' . $view . '
                        </td>
                    </tr>
                ';
            echo $announcementList;
        }
    }

    public function getAnnouncementStats($announcementId)
    {

        $viewers = (new AnnouncementManager())->getAnnouncementViewers($announcementId);
        $dateFormat = new DateFormat();
        // echo var_dump($viewers);
        // die();



        $html = "";
        foreach ($viewers as $viewer) {
            $fullName = htmlspecialchars($viewer['full_name'] ?? ($viewer['name'] . ' ' . $viewer['surname']));
            $html .= '
            <tr>
                <td>' . $fullName . '</td>
                <td>' . htmlspecialchars($viewer['role_name']) . '</td>
                <td>' . $dateFormat->changeDate($viewer['viewed_at'] ?? 'N/A') . '</td>
            </tr>    ';
        }


        echo $html;

    }

    public function showOneAnnouncement($slug)
    {

        $announcementInfo = $this->getAnnouncementBySlug($slug);
        $dateFormat = new DateFormat();

        $toWhom = "-";
        if ($announcementInfo['target_type'] == "all") {
            $toWhom = "Herkese";
        } elseif ($announcementInfo['target_type'] == "classes") {
            $toWhom = "Sınıfıma";
        } elseif ($announcementInfo['target_type'] == "roles") {
            $toWhom = "Tüm Öğrencilere";
        }
        $viewToWho = ' ';
        if (($_SESSION['role'] == 1) or ($_SESSION['role'] == 3)) {

            $viewToWho = '<div class="d-flex align-items-center me-5 mb-2">
                    <span class="svg-icon svg-icon-4 me-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path opacity="0.3" d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM12 7C10.3 7 9 8.3 9 10C9 11.7 10.3 13 12 13C13.7 13 15 11.7 15 10C15 8.3 13.7 7 12 7Z" fill="currentColor"/>
                            <path d="M12 22C14.6 22 17 21 18.7 19.4C17.9 16.9 15.2 15 12 15C8.8 15 6.09999 16.9 5.29999 19.4C6.99999 21 9.4 22 12 22Z" fill="currentColor"/>
                        </svg>
                    </span>
                    <span class="text-gray-600 fw-bold">' . $toWhom . '</span>
                </div> ';
        }

        $announcement = '
                <div class="mb-2">
                    <p class="text-gray-800 fs-4">
                    ' . $announcementInfo['content'] . '
                    </p>
                </div>
                
                <div class="d-flex flex-wrap align-items-center">

                    ' . $viewToWho . '
                    
                    <div class="d-flex align-items-center me-5 mb-2">
                        <span class="svg-icon svg-icon-4 me-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3" d="M21 22H3C2.4 22 2 21.6 2 21V5C2 4.4 2.4 4 3 4H21C21.6 4 22 4.4 22 5V21C22 21.6 21.6 22 21 22Z" fill="currentColor"/>
                                <path d="M6 6C5.4 6 5 5.6 5 5V3C5 2.4 5.4 2 6 2C6.6 2 7 2.4 7 3V5C7 5.6 6.6 6 6 6ZM11 5V3C11 2.4 10.6 2 10 2C9.4 2 9 2.4 9 3V5C9 5.6 9.4 6 10 6C10.6 6 11 5.6 11 5ZM15 5V3C15 2.4 14.6 2 14 2C13.4 2 13 2.4 13 3V5C13 5.6 13.4 6 14 6C14.6 6 15 5.6 15 5ZM19 5V3C19 2.4 18.6 2 18 2C17.4 2 17 2.4 17 3V5C17 5.6 17.4 6 18 6C18.6 6 19 5.6 19 5Z" fill="currentColor"/>
                                <path d="M8.8 13.1C9.2 13.1 9.5 13 9.7 12.8C9.9 12.6 10.1 12.3 10.1 11.9C10.1 11.6 10 11.3 9.8 11.1C9.6 10.9 9.3 10.8 9 10.8C8.8 10.8 8.59999 10.8 8.39999 10.9C8.19999 11 8.1 11.1 8 11.2C7.9 11.3 7.8 11.4 7.7 11.6C7.6 11.8 7.5 11.9 7.5 12.1C7.5 12.2 7.4 12.2 7.3 12.3C7.2 12.4 7.09999 12.4 6.89999 12.4C6.69999 12.4 6.6 12.3 6.5 12.2C6.4 12.1 6.3 11.9 6.3 11.7C6.3 11.5 6.4 11.3 6.5 11.1C6.6 10.9 6.7 10.7 6.9 10.5C7.1 10.3 7.3 10.2 7.5 10.1C7.7 10 7.9 9.90002 8.2 9.90002C8.5 9.90002 8.7 9.90002 8.9 10C9.1 10.1 9.3 10.2 9.4 10.3C9.5 10.4 9.6 10.6 9.7 10.8C9.8 11 9.8 11.1 9.8 11.3C9.8 11.4 9.7 11.5 9.6 11.6C9.5 11.7 9.4 11.7 9.3 11.7C9.2 11.7 9.09999 11.6 9.09999 11.5C9.09999 11.4 9.09999 11.3 9.09999 11.2C9.09999 11.1 9 11 8.9 10.9C8.8 10.8 8.7 10.7 8.5 10.7C8.3 10.7 8.2 10.7 8 10.8C7.9 10.9 7.8 11 7.7 11.1C7.6 11.2 7.5 11.3 7.5 11.5C7.5 11.7 7.6 11.8 7.7 11.9C7.8 12 8 12.1 8.2 12.1C8.3 12.1 8.5 12.1 8.7 12C8.8 11.9 8.9 11.8 9 11.7C9.1 11.6 9.2 11.4 9.2 11.3C9.2 11.2 9.2 11.1 9.3 11.1C9.4 11 9.5 10.9 9.6 10.9C9.7 10.9 9.8 11 9.8 11.1C9.8 11.2 9.8 11.3 9.8 11.5C9.8 11.7 9.7 11.9 9.6 12.1C9.5 12.3 9.3 12.4 9.1 12.5C8.9 12.6 8.7 12.7 8.4 12.7C8.1 12.7 7.9 12.6 7.7 12.5C7.5 12.4 7.3 12.2 7.2 12.1C7.1 11.9 7 11.7 7 11.5C7 11.3 7 11.1 7.1 10.9C7.2 10.7 7.3 10.5 7.4 10.4C7.5 10.3 7.6 10.2 7.8 10.1C7.9 10 8.1 9.90002 8.3 9.90002C8.5 9.90002 8.7 9.90002 8.8 10C9 10.1 9.1 10.2 9.2 10.3C9.3 10.4 9.4 10.6 9.4 10.8C9.4 10.9 9.4 11 9.3 11.1C9.2 11.2 9.1 11.2 9 11.2C8.9 11.2 8.8 11.1 8.8 11C8.8 10.9 8.8 10.8 8.9 10.7C8.9 10.6 9 10.5 9.1 10.5C9.2 10.5 9.2 10.5 9.3 10.6C9.4 10.7 9.4 10.8 9.4 11C9.4 11.2 9.3 11.3 9.2 11.4C9.1 11.5 8.9 11.6 8.8 11.6C8.7 11.6 8.5 11.6 8.4 11.5C8.3 11.4 8.2 11.3 8.2 11.1C8.2 10.9 8.2 10.8 8.3 10.6C8.4 10.4 8.5 10.3 8.6 10.2C8.7 10.1 8.9 10 9.1 10C9.3 10 9.5 10.1 9.6 10.2C9.7 10.3 9.8 10.5 9.8 10.7C9.8 10.9 9.7 11.1 9.6 11.2C9.5 11.3 9.4 11.4 9.2 11.5C9 11.6 8.8 11.7 8.6 11.7C8.4 11.7 8.2 11.6 8 11.5C7.8 11.4 7.7 11.2 7.6 11C7.5 10.8 7.5 10.6 7.5 10.4C7.5 10.2 7.6 10 7.7 9.8C7.8 9.6 8 9.4 8.2 9.3C8.4 9.2 8.6 9.10002 8.9 9.10002C9.2 9.10002 9.5 9.20002 9.7 9.30002C9.9 9.40002 10.1 9.60002 10.2 9.80002C10.3 10 10.4 10.2 10.4 10.5C10.4 10.8 10.3 11 10.2 11.2C10.1 11.4 9.9 11.6 9.7 11.7C9.5 11.8 9.3 11.9 9 12C8.7 12.1 8.5 12.1 8.2 12.1Z" fill="currentColor"/>
                            </svg>
                        </span>
                        <span class="text-gray-600 fw-bold">' . $dateFormat->changeDate($announcementInfo['start_date']) . '</span>
                    </div>
                    
         
                ';
        if (!$announcementInfo || !is_array($announcementInfo)) {
            echo "Duyuru bulunamadı.";
            return;
        }
        echo $announcement;
    }

    public function updateOneAnnouncement($slug)
    {

        $announcementInfo = $this->getAnnouncementBySlug($slug);

        foreach ($announcementInfo as $value) {

            $announcementList = '
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
                                <input type="text" id="name" class="form-control form-control-solid" value="' . $value['name'] . '" name="name" />
                                    <input type="hidden" name="old_slug" id="old_slug" value="' . $value['slug'] . '" />
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
                                    <input class="form-control form-control-solid" name="address" id="address" value="' . $value['address'] . '" />
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
                                        <input class="form-control form-control-solid" name="district" id="district" value="' . $value['district'] . '" />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <!--begin::Label-->
                                        <label class="fs-6 fw-semibold mb-2">Posta Kodu</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input class="form-control form-control-solid" name="postcode" id="postcode" value="' . $value['postcode'] . '" />
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
                                        <option value="' . $value['city'] . '">' . $value['city'] . '</option>
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
                                    <input type="email" class="form-control form-control-solid" name="email" id="email" value="' . $value['email'] . '" />
                                    <input type="hidden" name="email_old" id="email_old" value="' . $value['email'] . '" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="required fs-6 fw-semibold mb-2">Telefon Numarası</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="number" class="form-control form-control-solid" value="' . $value['telephone'] . '" id="telephone" name="telephone" />
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
                        <button type="reset" id="kt_modal_update_customer_cancel" class="btn btn-light btn-sm me-3">İptal</button>
                        <!--end::Button-->
                        <!--begin::Button-->
                        <button type="submit" id="kt_modal_update_customer_submit" class="btn btn-primary btn-sm">
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
        echo $announcementList;
    }

}