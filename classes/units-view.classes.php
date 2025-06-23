<?php
include_once "dateformat.classes.php";
include_once "gettestresults.classes.php";

class ShowUnit extends Units
{

    // Get Unit List

    public function getUnitList()
    {

        $unitInfo = $this->getUnitsListWithFilter();

        $dateFormat = new DateFormat();

        foreach ($unitInfo as $key => $value) {

            $sinifArama = 'data-filter="' . $value['classSlug'] . '"';

            if ($value['unitActive'] == 1) {
                $aktifArama = 'data-filter="Aktif"';
                $aktifYazi = '<span class="badge badge-light-success">Aktif</span>';
            } else {
                $aktifArama = 'data-filter="Passive"';
                $aktifYazi = '<span class="badge badge-light-danger">Pasif</span>';
            }

            $alter_button = $value['unitActive'] ? "Pasif Yap" : "Aktif Yap";

            if ($_SESSION['role'] == 4) {
                $passiveButton = '';
            } else {
                $passiveButton = '
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-customer-table-filter="delete_row">' . $alter_button . '</a>
                                </div>
                                <!--end::Menu item-->';
            }

            $lessonList = '
                    <tr>
                        <td>
                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="1" />
                            </div>
                        </td>
                        <td data-file-id="' . $value['unitID'] . '">
                            <a href="./unite-detay/' . $value['unitSlug'] . '" class="text-gray-800 text-hover-primary mb-1">' . $value['unitName'] . '</a>
                        </td>
                        <td>
                            ' . $value['lessonName'] . '
                        </td>
                        <td ' . $sinifArama . '>
                            ' . $value['className'] . '
                        </td>
                        <td>
                            ' . $dateFormat->changeDate($value['unitStartDate']) . '
                        </td>
                        <td>
                            ' . $dateFormat->changeDate($value['unitEndDate']) . '
                        </td>
                        <td>
                            ' . $value['unitOrder'] . '
                        </td>
                        <td>' . $aktifYazi . '</td>
                        <td class="text-end">
                            <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">İşlemler
                                <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                            <!--begin::Menu-->
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                data-kt-menu="true">
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="./unite-detay/' . $value['unitSlug'] . '" class="menu-link px-3">Görüntüle</a>
                                </div>
                                <!--end::Menu item-->
                                ' . $passiveButton . '
                            </div>
                            <!--end::Menu-->
                        </td>
                    </tr>
                ';
            echo $lessonList;
        }
    }

    // Get Lesson Id

    public function getID()
    {
        $link = "$_SERVER[REQUEST_URI]";
        $active_slug = htmlspecialchars(basename($link, ".php"));

        $lessonIdInfo = $this->getLessonId($active_slug);

        return $lessonIdInfo;
    }

    // Get Unit Name

    public function getUnitsListStudent()
    {

        $testResults = new TestsResult();

        $lessonId = $this->getID();

        $unitInfo = $this->getUnitsListStu($lessonId, $_SESSION['class_id'], $_SESSION['school_id']);

        $today = date('Y-m-d');

        if ($unitInfo == NULL) {

            $lessonList = '
                        <!--begin::Col-->
                            <div class="col-md-12 d-flex flex-center">
                                <i class="fa-regular fa-face-frown-open text-danger fs-4x"></i>
                                
                            </div>

                            <div class="text-center mt-2">
                                <h4 class="fs-2hx text-gray-900 mb-5">Ünite Mevcut Değil!</h4>
                            </div>

                        <!--end::Col-->
                        ';
            echo $lessonList;
        } else {

            foreach ($unitInfo as $key => $value) {

                $getLessonId = $value['lesson_id'];
                $getClassId = $value['class_id'];
                $getOrderNo = $value['order_no'];

                if ($getOrderNo == 1) {
                    $testQuery = 80 >= 80;
                } else {
                    $getPreviousUnitId = $this->getPrevUnitId($getOrderNo - 1, $getClassId, $getLessonId, $_SESSION['school_id']);
                    $prevUnitId = $getPreviousUnitId['id'];
                    $getTestResult = $testResults->getUnitTestResults($prevUnitId, $getClassId, $_SESSION['id']);
                    $result = $getTestResult['score'] ?? 0;
                    $testQuery = $result >= 80; // If the previous unit's test is not passed, the current unit cannot be accessed.
                }

                if ($today >= $value['start_date'] or $testQuery) {
                    $link = "unite/{$value['slug']}";
                    $class = "";
                    $notification = '';
                } else {
                    $link = "#";
                    $class = "pe-none";
                    $notification = '<div class="fw-semibold fs-5 text-danger mt-3 mb-5">Bu ünitenin tarihi gelmemiş veya bir önceki ünitenin sınavı başarı ile tamamlanmamıştır.</div>';
                }

                $lessonList = '
                            <!--begin::Col-->
                            <div class="col-md-4">
                                <!--begin::Publications post-->
                                <div class="card-xl-stretch me-md-6">
                                    <!--begin::Overlay-->
                                    <a class="d-block overlay mb-4 ' . $class . '" href="' . $link . '">
                                        <!--begin::Image-->
                                        <div class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-175px" style="background-image:url(\'assets/media/units/' . $value['photo'] . '\')"></div>
                                        <!--end::Image-->
                                        <!--begin::Action-->
                                        <div class="overlay-layer bg-dark card-rounded bg-opacity-25">
                                            <i class="ki-duotone ki-eye fs-2x text-white"></i>
                                        </div>
                                        <!--end::Action-->
                                    </a>
                                    <!--end::Overlay-->
                                    <!--begin::Body-->
                                    <div class="m-0">
                                        <!--begin::Title-->
                                        <a href="' . $link . '" class="fs-4 text-gray-900 fw-bold text-hover-primary text-gray-900 lh-base ' . $class . '">' . $value['name'] . '</a>
                                        <!--end::Title-->
                                        <!--begin::Text-->
                                        <div class="fw-semibold fs-5 text-gray-600 text-gray-900 mt-3 mb-5">' . $value['short_desc'] . '</div>
                                        ' . $notification . '
                                        <!--end::Text-->
                                    </div>
                                    <!--end::Body-->
                                </div>
                                <!--end::Publications post-->
                            </div>
                            <!--end::Col-->
                    ';
                echo $lessonList;
            }
        }
    }

    // Get Unit Image For Students

    public function getHeaderImageStu()
    {
        $link = "$_SERVER[REQUEST_URI]";

        $active_slug = htmlspecialchars(basename($link, ".php"));

        $unitInfo = $this->getOneUnit($active_slug);

        foreach ($unitInfo as $key => $value) {

            $lessonList = '
                    <div class="position-relative mb-17">
                        <!--begin::Overlay-->
                        <div class="overlay overlay-show">
                            <!--begin::Image-->
                            <div class="bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-250px" style="background-image:url(\'assets/media/units/' . $value['photo'] . '\')"></div>
                            <!--end::Image-->
                            <!--begin::layer-->
                            <div class="overlay-layer rounded bg-black" style="opacity: 0.4"></div>
                            <!--end::layer-->
                        </div>
                        <!--end::Overlay-->
                        <!--begin::Heading-->
                        <div class="position-absolute text-white mb-8 ms-10 bottom-0">
                            <!--begin::Title-->
                            <h3 class="text-white fs-2qx fw-bold mb-3 m">' . $value['name'] . '</h3>
                            <h3 class="text-white fs-1qx fw-bold mb-3 m">' . $value['short_desc'] . '</h3>
                            <!--end::Title-->
                            <!--begin::Text-->
                            <!--<div class="fs-5 fw-semibold">You sit down. You stare at your screen. The cursor blinks.</div>-->
                            <!--end::Text-->
                        </div>
                        <!--end::Heading-->
                    </div>
                ';
            echo $lessonList;
        }
    }

    // Get Units Topics Sidebar For Students

    public function getSidebarTopicsStu()
    {
        $testResults = new TestsResult();

        $link = "$_SERVER[REQUEST_URI]";

        $active_slug = htmlspecialchars(basename($link, ".php"));

        $today = date('Y-m-d');

        $unitInfo = $this->getSameUnits($active_slug);

        $lessonList = '<div class="card-body">
                        <!--begin::Top-->
                        <div class="mb-7">
                            <!--begin::Title-->
                            <h2 class="fs-1 text-gray-800 w-bolder mb-6">Diğer Üniteler</h2>
                            <!--end::Title-->
                        </div>
                        <!--end::Top-->
                        <!--begin::Item-->';

        foreach ($unitInfo as $key => $value) {

            $getLessonId = $value['lesson_id'];
            $getClassId = $value['class_id'];
            $getOrderNo = $value['order_no'];

            if ($getOrderNo == 1) {
                $testQuery = 80 >= 80;
            } else {
                $getPreviousUnitId = $this->getPrevUnitId($getOrderNo - 1, $getClassId, $getLessonId, $_SESSION['school_id']);
                $prevUnitId = $getPreviousUnitId['id'];
                $getTestResult = $testResults->getUnitTestResults($prevUnitId, $getClassId, $_SESSION['id']);
                $result = $getTestResult['score'] ?? 0;
                $testQuery = $result >= 80; // If the previous unit's test is not passed, the current unit cannot be accessed.
            }

            if ($today >= $value['start_date'] or $testQuery) {
                $link = "unite/{$value['unitSlug']}";
                $class = "";
            } else {
                $link = "#";
                $class = "pe-none";
            }

            $lessonList .= '
                            <!--begin::Section-->
                            <div class="my-2">
                                <!--begin::Row-->
                                <div class="d-flex align-items-center mb-3">
                                    <!--begin::Bullet-->
                                    <span class="bullet me-3"></span>
                                    <!--end::Bullet-->
                                    <!--begin::Label-->
                                    <div class="text-gray-600 fw-semibold fs-6 ' . $class . '"><a href="' . $link . '">' . $value['unitName'] . '</a></div>
                                    <!--end::Label-->
                                </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Section-->
                ';
        }

        $lessonList .= '
                    <!--end::Item-->
                </div>
            ';
        echo $lessonList;
    }

    // Get Unit List For Students

    public function getUnitListStu()
    {
        $link = "$_SERVER[REQUEST_URI]";

        $active_slug = htmlspecialchars(basename($link, ".php"));

        $unitInfo = $this->getUnitsList($active_slug);

        $dateFormat = new DateFormat();

        foreach ($unitInfo as $key => $value) {

            $lessonList = '
                    <tr>
                        <td>
                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="1" />
                            </div>
                        </td>
                        <td>
                            <a href="apps/customers/view.html" class="text-gray-800 text-hover-primary mb-1">' . $value['unitName'] . '</a>
                        </td>
                        <td>
                            ' . $value['lessonName'] . '
                        </td>
                        <td>
                            ' . $value['className'] . '
                        </td>
                        <td class="text-end">
                            <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">İşlemler
                                <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                            <!--begin::Menu-->
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                data-kt-menu="true">
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="./okul-detay/' . $value['unitSlug'] . '" class="menu-link px-3">Görüntüle</a>
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
            echo $lessonList;
        }
    }

    // Get Unit List Select For Teacher

    public function getUnitSelectList()
    {

        $unitInfo = $this->getUnitsList();

        $dateFormat = new DateFormat();

        $unitList = '';

        foreach ($unitInfo as $key => $value) {

            $unitList .= '
                    <option value="' . $value['unitID'] . '">' . $value['unitName'] . '</option>
                ';
        }
        return $unitList;
    }

    // Show Unit

    public function showOneUnit($slug)
    {

        $unitInfo = $this->getOneUnitForDetails($slug);

        if (count($unitInfo) == 0) {
            $unitList = header("Location: ../404.php"); // 404 sayfasına yönlendir
            echo $unitList;
            return;
        }


        /* $lessonList = '
                <div class="mb-3">
                    <h1 class="h3 d-inline align-middle">Böyle bir okul mevcut değil.</h1>
                </div>
        '; */

        foreach ($unitInfo as $value) {

            $unitId = $value['id'];

            $topics = new Topics();
            $subtopics = new SubTopics();

            $dateFormat = new DateFormat();

            $getTopics = $topics->getTopicsByUnitId($unitId);

            $getSubTopics = $subtopics->getSubTopicsByUnitId($unitId);

            $topicNumber = count($getTopics);
            $subTopicNumber = count($getSubTopics);

            $lessonList = '
                <div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">
                    <!--begin::Card-->
                    <div class="card mb-5 mb-xl-8">
                        <!--begin::Card body-->
                        <div class="card-body pt-15">
                            <!--begin::Summary-->
                            <div class="d-flex flex-center flex-column mb-5">
                                <!--begin::Avatar-->
                                <div class="mb-7">
                                    <img class="mw-100" src="assets/media/units/' . $value['photo'] . '" alt="image" />
                                </div>
                                <!--end::Avatar-->
                                <!--begin::Name-->
                                <p class="fs-3 text-gray-800 fw-bold mb-1">' . $value['name'] . '</p>
                                <!--end::Name-->
                                <!--begin::Position-->
                                <div class="fs-5 fw-semibold text-muted mb-6">' . $value['lessonName'] . '</div>
                                <!--end::Position-->
                                <!--begin::Position-->
                                <div class="fs-5 fw-semibold text-muted mb-6">' . $value['className'] . '</div>
                                <!--end::Position-->
                                <!--begin::Info-->
                                <div class="d-flex flex-wrap flex-center">
                                    <!--begin::Stats-->
                                    <div class="border border-gray-300 border-dashed rounded py-3 px-3 mx-4 mb-3">
                                        <div class="fs-4 fw-bold text-gray-700">
                                            <span class="w-75px">' . $topicNumber . '</span>
                                            <i class="fa-solid fa-book-open fs-3 text-success"></i>
                                        </div>
                                        <div class="fw-semibold text-muted">Konu</div>
                                    </div>
                                    <!--end::Stats-->
                                    <!--begin::Stats-->
                                    <div class="border border-gray-300 border-dashed rounded py-3 px-3 mx-4 mb-3">
                                        <div class="fs-4 fw-bold text-gray-700">
                                            <span class="w-75px">' . $subTopicNumber . '</span>
                                            <i class="fa-solid fa-book-open fs-3 text-success"></i>
                                        </div>
                                        <div class="fw-semibold text-muted">Alt Konu</div>
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
                                <span data-bs-toggle="tooltip" data-bs-trigger="hover" title="Ünite bilgilerini düzenle">
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
                                    <div class="fw-bold mt-5">Kısa Açıklama</div>
                                    <div class="text-gray-600">' . $value['short_desc'] . '</div>
                                    <!--end::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bold mt-5">Ünite Başlama Tarihi</div>
                                    <div class="text-gray-600">' . $dateFormat->changeDate($value['start_date']) . '</div>
                                    <!--end::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bold mt-5">Ünite Bitiş Tarihi</div>
                                    <div class="text-gray-600">' . $dateFormat->changeDate($value['end_date']) . '</div>
                                    <!--end::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bold mt-5">Ünite Sırası</div>
                                    <div class="text-gray-600">' . $value['order_no'] . '</div>
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
        echo $lessonList;
    }

    // Update Unit

    public function updateOneUnit($slug)
    {

        $chooseClass = new ShowClass();
        $chooseLesson = new ShowLesson();
        $unitInfo = $this->getOneUnitForDetails($slug);

        $classList = $chooseClass->getClassSelectList();

        foreach ($unitInfo as $value) {

            if ($value['photo'] == NULL) {
                $image = 'assets/media/units/blank-image.svg';
            } else {
                $image = 'assets/media/units/' . $value['photo'];
            }

            $order_no = $value['order_no'] ?? '';

            $startDate = htmlspecialchars($value['start_date'] ?? '');
            $endDate = htmlspecialchars($value['end_date'] ?? '');

            $lessonList = '
                <form class="form" action="#" id="kt_modal_add_customer_form" data-kt-redirect="uniteler">
                    <!--begin::Modal header-->
                    <div class="modal-header" id="kt_modal_add_customer_header">
                        <!--begin::Modal title-->
                        <h2 class="fw-bold">Ünite Ekleyin</h2>
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
                                    <span>Görsel</span>
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
                                            background-image: url("assets/media/svg/files/blank-image.svg");
                                        }

                                        [data-bs-theme="dark"] .image-input-placeholder {
                                            background-image: url("assets/media/svg/files/blank-image-dark.svg");
                                        }
                                    </style>
                                    <!--end::Image placeholder-->
                                    <!--begin::Image input-->
                                    <div class="image-input image-input-outline image-input-placeholder image-input-empty image-input-empty"
                                        data-kt-image-input="true">
                                        <!--begin::Preview existing avatar-->
                                        <div class="image-input-wrapper w-100px h-100px" style="background-image: url(\'' . $image . '\')"></div>
                                        <!--end::Preview existing avatar-->
                                        <!--begin::Edit-->
                                        <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                            data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Görsel Ekle">
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
                                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                            data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Fotoğrafı İptal Et">
                                            <i class="ki-duotone ki-cross fs-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </span>
                                        <!--end::Cancel-->
                                        <!--begin::Remove-->
                                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                            data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
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
                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">Ünite</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" id="name" class="form-control form-control-solid" value="' . $value['name'] . '"
                                    name="name" />
                                <input type="hidden" id="unit_slug" name="unit_slug" value="' . $slug . '">
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">Kısa Açıklama</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" id="short_desc" class="form-control form-control-solid"
                                    value="' . $value['short_desc'] . '" name="short_desc" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">Ünite Başlangıç Tarihi</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                    <input type="date" class="form-control form-control-solid fw-bold pe-5" value="' . $startDate . '" placeholder="Ünite Başlangıç Tarihi Seçin" name="unit_start_date" id="unit_start_date">
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">Ünite Bitiş Tarihi</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                    <input type="date" class="form-control form-control-solid fw-bold pe-5" value="' . $endDate . '" placeholder="Ünite Bitiş Tarihi Seçin" name="unit_end_date" id="unit_end_date">
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">Ünite Sırası</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                    <input type="number" class="form-control form-control-solid fw-bold pe-5" value=' . $order_no . ' placeholder="Ünite Sırası Girin" name="unit_order" id="unit_order">
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
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
                ';
        }
        echo $lessonList;
    }

    // List of Unit

    public function showUnitList()
    {

        $unitInfo = $this->getUnits();

        $lessonList = '';

        $divStart = '<div class="row"><div class="overflow-auto col-12 col-lg-2" style="max-height: 150px">';

        $divEnd = '</div></div>';

        foreach ($unitInfo as $value) {

            $parentId = $value['id'];

            $classForms = '<div class="form-check">
                                            <input class="form-check-input" name="category[]" type="checkbox" value="' . $value['id'] . '" id="' . $value['slug'] . '">
                                                <label class="form-check-label" for="' . $value['slug'] . '">
                                                ' . $value['catName'] . '
                                                </label>
                                        </div>';



            $lessonList .= $classForms;
        }


        echo $divStart . $lessonList . $divEnd;
    }

    // Get Units For Topic List

    public function showUnitForTopicList($class, $lessons)
    {

        $unitInfo = $this->getUnitForTopicList($class, $lessons);

        $units = array();

        foreach ($unitInfo as $key => $value) {

            $units[] = array("id" => $value["id"], "text" => $value["name"]);
        }


        echo json_encode($units);
    }
}
