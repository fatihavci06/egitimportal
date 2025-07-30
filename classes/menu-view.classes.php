<?php

class ShowMenu extends Menus
{

    // GET Name

    public function getTitleNormal()
    {
        $link = "$_SERVER[REQUEST_URI]";
        $active_slug = htmlspecialchars(basename($link, ".php"));

        $name = $this->getTitleNames($active_slug);

        echo $name['name'];
    }

    // Get Page Name

    public function getTitle()
    {
        $link = "$_SERVER[REQUEST_URI]";
        $active_slug = htmlspecialchars(basename($link, ".php"));
        // URL'yi ayrıştır
        $parsed_url = parse_url($active_slug);

        // Sadece yol (path) kısmını al
        $active_slug = $parsed_url['path'];

        $menuInfo = $this->getOneMenu($active_slug);

        $lessonTitleInfo = $this->getLessonTitle($active_slug);

        $unitTitleInfo = $this->getUnitTitle($active_slug);

        $topicTitleInfo = $this->getTopicTitle($active_slug);

        $gameTitleInfo = $this->getGameTitle($active_slug);

        $titleParts = explode('-', $active_slug);

        $pageTitle = @ucfirst($titleParts[0]) . ' ' . @ucfirst($titleParts[1]) . ' ' . @ucfirst($titleParts[2]);

        if ($menuInfo != NULL) {
            foreach ($menuInfo as $key => $value) {
                $pageTitle = $value['name'];
            }
        } elseif ($lessonTitleInfo != NULL) {
            foreach ($lessonTitleInfo as $key => $value) {
                $pageTitle = $value['name'];
            }
        } elseif ($unitTitleInfo != NULL) {
            foreach ($unitTitleInfo as $key => $value) {
                $pageTitle = $value['name'];
            }
        } elseif ($topicTitleInfo != NULL) {
            foreach ($topicTitleInfo as $key => $value) {
                $pageTitle = $value['name'];
            }
        } elseif ($gameTitleInfo != NULL) {
            foreach ($gameTitleInfo as $key => $value) {
                $pageTitle = $value['name'];
            }
        } elseif ($active_slug == 'oyun') {
            $pageTitle = 'Oyunlar';
        } elseif ($active_slug == 'icerik-ekle') {
            $pageTitle = 'İçerik Ekle';
        } elseif ($active_slug == 'cek-gonder') {
            $pageTitle = 'Çek Gönder';
        } elseif ($active_slug == 'cek-gonder-admin') {
            $pageTitle = 'Çek Gönder';
        } elseif ($active_slug == 'kocluk-sohbet') {
            $pageTitle = 'Koçluk Sohbet';
        } elseif ($active_slug == 'kuponlari-listele') {
            $pageTitle = 'Kuponları Listele';
        } elseif ($active_slug == '3dbilimvideo-ekle') {
            $pageTitle = '3D Bilim Video Ekle';
        } elseif ($active_slug == 'yapay-zekaya-sor') {
            $pageTitle = 'Yapay Zekaya Sorunu Sor';
        }
        
        $questionmark = strpos($pageTitle, '?');

        if ($questionmark !== false) {
            $sonuc = substr($pageTitle, 0, $questionmark);
            echo $sonuc;
        } else {
            echo $pageTitle;
        }
    }

    // Get Super Admin Menu List

    public function showMenuSuperAdminList()
    {

        $link = $_SERVER['REQUEST_URI'];
        $active_slug = htmlspecialchars(basename($link, ".php"));

        $activeControl = $this->getIsActive($active_slug);

        $activeId = @$activeControl['parent'];

        $menuList = "";

        $numberofMenus = $this->getTopMenuNumber();

        for ($i = 1; $i <= $numberofMenus['accordion']; $i++) {
            $show = "";
            $here = "";

            $menuInfo = $this->getTopMenuList($i);

            foreach ($menuInfo as $key => $value) {

                $roles = $value['role'];

                $pieces = explode(",", $roles);

                if (in_array($_SESSION['role'], $pieces)) {

                    if ($value['slug'] == 'kocluk-sohbet' AND $_SESSION['role'] == 2) {
                        $controlKocluk = $this->controlKoclukPack();
                        if (empty($controlKocluk)) {
                            continue;
                        }
                    }

                    if ($activeId == $value['accordion']) {
                        $show = " show";
                        $here = " here";
                    }
                    $role = $_SESSION['role'] ?? null;

                    // Eğer role 10001 veya 10002 ise gösterilsin
                    if ($role == 10001 || $role == 10002) {
                        /* $show2 = ' show'; */
                        $show2 = '';
                        $style = 'style="display: block"';
                    } else {
                        $show2 = '';
                        $style = '';
                    }

                    if($value['has_child'] == 1){
                        $trigger = 'data-kt-menu-trigger="click"';
                        $arrow = '<span class="menu-arrow"></span>';
                    }else{
                        $trigger = '';
                        $arrow = '';
                    }

                    $menuList .= '<div ' . $trigger . ' class="mb-3 menu-item' . $show . '' . $here . ' menu-accordion">
                                <!--begin:Menu link-->
                                <span class="menu-link" style="padding-left: 0px;">
                                <a class="menu-link" href="' . $value['slug'] . '" style="padding-left: 0px;">
                                    <span class="menu-icon">
                                        <i class="' . $value['classes'] . ' fs"></i>
                                    </span>
                                    <span class="menu-title">' . $value['name'] . '</span>
                                    ' . $arrow . '
                                </a>
                                </span>
                                <!--end:Menu link-->';


                    if($value['has_child'] == 1){

                    $menuList .= '
                                <!--begin:Menu sub-->
                                <div class="menu-sub menu-sub-accordion ' . $show2 . '" style="' . $style . '">
                    ';

                    $subMenuInfo = $this->getSubMenuList($i);

                    foreach ($subMenuInfo as $key => $subValue) {

                        $url = "";
                        $isLesson = "";
                        if ($i == 1) {
                            $url = "ders/";
                            $lessonClass = $this->getLessonsListForSubMenu($subValue['slug']);
if (is_array($lessonClass) && isset($lessonClass['class_id']) && $lessonClass['class_id'] !== null) {
    $piecesLesson = explode(";", $lessonClass['class_id']);
} else {
    $piecesLesson = []; // boş array döner, veya default bir değer ver
}                            if (in_array($_SESSION['class_id'], $piecesLesson)) {
                                $isLesson = 1;
                            }
                        }

                        $subRoles = $subValue['role'];

                        $piecesRoles = explode(",", $subRoles);

                        if ($i == 1 and !empty($isLesson)) {

                            if (in_array($_SESSION['role'], $piecesRoles)) {

                                $active = "";

                                if ($active_slug == $subValue['slug']) {
                                    $active = " active";
                                }

                                if ($lessonClass['package_type'] == 1) {
                                    $controlPayment = $this->controlDevelopmentPack();
                                    if (empty($controlPayment)) {
                                        continue;
                                    }
                                }

                                $menuList .= '
                                    <!--begin:Menu item-->
                                    <div class="menu-item">
                                        <!--begin:Menu link-->
                                        <a class="menu-link' . $active . '" href="' . $url . $subValue['slug'] . '">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">' . $subValue['name'] . '</span>
                                        </a>
                                        <!--end:Menu link-->
                                    </div>
                                    <!--end:Menu item-->
                                    ';
                            }
                        } elseif ($i != 1) {
                            if (in_array($_SESSION['role'], $piecesRoles)) {

                                $active = "";

                                if ($active_slug == $subValue['slug']) {
                                    $active = " active";
                                }

                                $menuList .= '
                                    <!--begin:Menu item-->
                                    <div class="menu-item">
                                        <!--begin:Menu link-->
                                        <a class="menu-link' . $active . '" href="' . $url . $subValue['slug'] . '">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">' . $subValue['name'] . '</span>
                                        </a>
                                        <!--end:Menu link-->
                                    </div>
                                    <!--end:Menu item-->
                                    ';
                            }
                        }
                    }
                }

                if($value['has_child'] == 1){
                    $menuList .= "</div>
                                <!--end:Menu sub-->
                              </div>";
                }else{
                    $menuList .= "</div>";
                }
                }
            }
        }

        echo $menuList;
    }

    // Get School Admin Menu List

    public function showMenuSchoolAdminList()
    {

        $menuInfo = $this->getMenuList();

        $link = $_SERVER['REQUEST_URI'];
        $active_slug = htmlspecialchars(basename($link, ".php"));

        $menuList = '<div data-kt-menu-trigger="click" class="menu-item here show menu-accordion">
                        <!--begin:Menu link-->
                        <span class="menu-link">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-user-square fs-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </span>
                            <span class="menu-title">Kullanıcı Yönetimi</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <!--end:Menu link-->
                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">';

        foreach ($menuInfo as $key => $value) {

            $roles = $value['role'];

            $pieces = explode(",", $roles);

            if (in_array("3", $pieces)) {

                $active = "";

                if ($active_slug == $value['slug']) {
                    $active = " active";
                }

                $menuList .= '
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link' . $active . '" href="' . $value['slug'] . '">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">' . $value['name'] . '</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    ';
            }
        }

        $menuList .= "</div>
                                <!--end:Menu sub-->
                            </div>";

        echo $menuList;
    }

    // Get School Teeacher Menu List

    public function showMenuSchoolTeacherList()
    {

        $menuInfo = $this->getMenuList();

        $link = $_SERVER['REQUEST_URI'];
        $active_slug = htmlspecialchars(basename($link, ".php"));

        $menuList = '<div data-kt-menu-trigger="click" class="menu-item here show menu-accordion">
                        <!--begin:Menu link-->
                        <span class="menu-link">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-user-square fs-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </span>
                            <span class="menu-title">Kullanıcı Yönetimi</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <!--end:Menu link-->
                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">';

        foreach ($menuInfo as $key => $value) {

            $roles = $value['role'];

            $pieces = explode(",", $roles);

            if (in_array("4", $pieces)) {

                $active = "";

                if ($active_slug == $value['slug']) {
                    $active = " active";
                }

                $menuList .= '
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link' . $active . '" href="' . $value['slug'] . '">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">' . $value['name'] . '</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    ';
            }
        }

        $menuList .= "</div>
                                <!--end:Menu sub-->
                            </div>";

        echo $menuList;
    }

    // Get Student Menu List

    public function showMenuStudentList()
    {

        $menuInfo = $this->getLessonsList();

        $link = $_SERVER['PHP_SELF'];
        $active_slug = htmlspecialchars(basename($link, ".php"));

        $menuList = '<div data-kt-menu-trigger="click" class="menu-item here show menu-accordion">
                        <!--begin:Menu link-->
                        <span class="menu-link">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-book-open fs-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </span>
                            <span class="menu-title">Dersler</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <!--end:Menu link-->
                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">';

        foreach ($menuInfo as $key => $value) {

            $active = "";

            if ($active_slug == $value['slug']) {
                $active = " active";
            }

            $menuList .= '
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link' . $active . '" href="ders/' . $value['slug'] . '">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">' . $value['name'] . '</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                ';
        }

        $menuList .= '</div>
                                <!--end:Menu sub-->
                            </div>
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <span class="menu-link">
                                    <a class="menu-link' . $active . '" href="oyun">
                                        <span class="menu-icon">
                                            <i class="fa-solid fa-puzzle-piece fs-1"></i>
                                        </span>
                                        <span class="menu-title">Oyunlar</span>
                                    </a>
                                    <!--<span class="menu-arrow"></span>-->
                                </span>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->';

        echo $menuList;
    }

    // Show Menu

    public function showOneMenu($slug)
    {

        $menuInfo = $this->getOneMenu($slug);


        $menuList = '
                <div class="mb-3">
                    <h1 class="h3 d-inline align-middle">Böyle bir menü mevcut değil.</h1>
                </div>
        ';

        foreach ($menuInfo as $value) {

            $menuList = '
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
                                            <span class="w-75px">6,900</span>
                                            <i class="ki-duotone ki-arrow-up fs-3 text-success">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </div>
                                        <div class="fw-semibold text-muted">Earnings</div>
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
                                        <div class="fw-semibold text-muted">Tasks</div>
                                    </div>
                                    <!--end::Stats-->
                                    <!--begin::Stats-->
                                    <div class="border border-gray-300 border-dashed rounded py-3 px-3 mb-3">
                                        <div class="fs-4 fw-bold text-gray-700">
                                            <span class="w-50px">500</span>
                                            <i class="ki-duotone ki-arrow-up fs-3 text-success">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </div>
                                        <div class="fw-semibold text-muted">Hours</div>
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
                    <!--begin::Connected Accounts-->
                    <div class="card mb-5 mb-xl-8">
                        <!--begin::Card header-->
                        <div class="card-header border-0">
                            <div class="card-title">
                                <h3 class="fw-bold m-0">Connected Accounts</h3>
                            </div>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-2">
                            <!--begin::Notice-->
                            <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed mb-9 p-6">
                                <!--begin::Icon-->
                                <i class="ki-duotone ki-design-1 fs-2tx text-primary me-4"></i>
                                <!--end::Icon-->
                                <!--begin::Wrapper-->
                                <div class="d-flex flex-stack flex-grow-1">
                                    <!--begin::Content-->
                                    <div class="fw-semibold">
                                        <div class="fs-6 text-gray-700">By connecting an account, you hereby agree to our
                                            <a href="#" class="me-1">privacy policy</a>and
                                            <a href="#">terms of use</a>.
                                        </div>
                                    </div>
                                    <!--end::Content-->
                                </div>
                                <!--end::Wrapper-->
                            </div>
                            <!--end::Notice-->
                            <!--begin::Items-->
                            <div class="py-2">
                                <!--begin::Item-->
                                <div class="d-flex flex-stack">
                                    <div class="d-flex">
                                        <img src="assets/media/svg/brand-logos/google-icon.svg" class="w-30px me-6" alt="" />
                                        <div class="d-flex flex-column">
                                            <a href="#" class="fs-5 text-gray-900 text-hover-primary fw-bold">Google</a>
                                            <div class="fs-6 fw-semibold text-muted">Plan properly your workflow</div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <!--begin::Switch-->
                                        <label class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
                                            <!--begin::Input-->
                                            <input class="form-check-input" name="google" type="checkbox" value="1" id="kt_modal_connected_accounts_google" checked="checked" />
                                            <!--end::Input-->
                                            <!--begin::Label-->
                                            <span class="form-check-label fw-semibold text-muted" for="kt_modal_connected_accounts_google"></span>
                                            <!--end::Label-->
                                        </label>
                                        <!--end::Switch-->
                                    </div>
                                </div>
                                <!--end::Item-->
                                <div class="separator separator-dashed my-5"></div>
                                <!--begin::Item-->
                                <div class="d-flex flex-stack">
                                    <div class="d-flex">
                                        <img src="assets/media/svg/brand-logos/github.svg" class="w-30px me-6" alt="" />
                                        <div class="d-flex flex-column">
                                            <a href="#" class="fs-5 text-gray-900 text-hover-primary fw-bold">Github</a>
                                            <div class="fs-6 fw-semibold text-muted">Keep eye on on your Repositories</div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <!--begin::Switch-->
                                        <label class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
                                            <!--begin::Input-->
                                            <input class="form-check-input" name="github" type="checkbox" value="1" id="kt_modal_connected_accounts_github" checked="checked" />
                                            <!--end::Input-->
                                            <!--begin::Label-->
                                            <span class="form-check-label fw-semibold text-muted" for="kt_modal_connected_accounts_github"></span>
                                            <!--end::Label-->
                                        </label>
                                        <!--end::Switch-->
                                    </div>
                                </div>
                                <!--end::Item-->
                                <div class="separator separator-dashed my-5"></div>
                                <!--begin::Item-->
                                <div class="d-flex flex-stack">
                                    <div class="d-flex">
                                        <img src="assets/media/svg/brand-logos/slack-icon.svg" class="w-30px me-6" alt="" />
                                        <div class="d-flex flex-column">
                                            <a href="#" class="fs-5 text-gray-900 text-hover-primary fw-bold">Slack</a>
                                            <div class="fs-6 fw-semibold text-muted">Integrate Projects Discussions</div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <!--begin::Switch-->
                                        <label class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
                                            <!--begin::Input-->
                                            <input class="form-check-input" name="slack" type="checkbox" value="1" id="kt_modal_connected_accounts_slack" />
                                            <!--end::Input-->
                                            <!--begin::Label-->
                                            <span class="form-check-label fw-semibold text-muted" for="kt_modal_connected_accounts_slack"></span>
                                            <!--end::Label-->
                                        </label>
                                        <!--end::Switch-->
                                    </div>
                                </div>
                                <!--end::Item-->
                            </div>
                            <!--end::Items-->
                        </div>
                        <!--end::Card body-->
                        <!--begin::Card footer-->
                        <div class="card-footer border-0 d-flex justify-content-center pt-0">
                            <button class="btn btn-sm btn-light-primary">Save Changes</button>
                        </div>
                        <!--end::Card footer-->
                    </div>
                    <!--end::Connected Accounts-->
                </div>
                ';
        }
        echo $menuList;
    }

    // Update Menu

    public function updateOneMenu($slug)
    {

        $schoolInfo = $this->getOneMenu($slug);

        foreach ($schoolInfo as $value) {

            $menuList = '
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
        echo $menuList;
    }

    // List of Schools

    public function showMenuList()
    {

        $menuInfo = $this->getMenus();

        $menuList = '';

        $divStart = '<div class="row"><div class="overflow-auto col-12 col-lg-2" style="max-height: 150px">';

        $divEnd = '</div></div>';

        foreach ($menuInfo as $value) {

            $parentId = $value['id'];

            $schoolForms = '<div class="form-check">
                                            <input class="form-check-input" name="category[]" type="checkbox" value="' . $value['id'] . '" id="' . $value['slug'] . '">
                                                <label class="form-check-label" for="' . $value['slug'] . '">
                                                ' . $value['catName'] . '
                                                </label>
                                        </div>';



            $menuList .=  $schoolForms;
        }


        echo $divStart . $menuList . $divEnd;
    }
}
