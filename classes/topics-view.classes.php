<?php
include_once "dateformat.classes.php";
//include_once "api/video/get.php";
include_once "gettestresults.classes.php";

class ShowTopic extends Topics
{

    // Update Topic

    public function updateOneTopic($slug)
    {

        $chooseClass = new ShowClass();
        $chooseLesson = new ShowLesson();
        $topicInfo = $this->getOneTopicDetailsAdmin($slug);

        $classList = $chooseClass->getClassSelectList();

        foreach ($topicInfo as $value) {

            if ($value['image'] == NULL) {
                $image = 'assets/media/topics/blank-image.svg';
            } else {
                $image = 'assets/media/topics/' . $value['image'];
            }

            $order_no = $value['order_no'] ?? '';

            $startDate = htmlspecialchars($value['start_date'] ?? '');
            $endDate = htmlspecialchars($value['end_date'] ?? '');

            $lessonList = '
                <form class="form" action="#" id="kt_modal_add_customer_form" data-kt-redirect="konular">
                    <!--begin::Modal header-->
                    <div class="modal-header" id="kt_modal_add_customer_header">
                        <!--begin::Modal title-->
                        <h2 class="fw-bold">Konu Güncelle</h2>
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
                                <label class="required fs-6 fw-semibold mb-2">Konu</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" id="name" class="form-control form-control-solid" value="' . $value['name'] . '"
                                    name="name" />
                                <input type="hidden" id="slug" name="slug" value="' . $slug . '">
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
                                <label class="required fs-6 fw-semibold mb-2">Konu Başlangıç Tarihi</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                    <input type="date" class="form-control form-control-solid fw-bold pe-5" value="' . $startDate . '" placeholder="Konu Başlangıç Tarihi Seçin" name="start_date" id="start_date">
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">Konu Bitiş Tarihi</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                    <input type="date" class="form-control form-control-solid fw-bold pe-5" value="' . $endDate . '" placeholder="Konu Bitiş Tarihi Seçin" name="end_date" id="end_date">
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">Konu Sırası</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                    <input type="number" class="form-control form-control-solid fw-bold pe-5" value=' . $order_no . ' placeholder="Konu Sırası Girin" name="order" id="order">
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

    // Get Topic List

    public function getTopicList()
    {
        /* 
        $topicInfo = $this->getTopicsList(); */

        $topicInfo = $this->getTopicsListWithFilter();

        $dateFormat = new DateFormat();

        foreach ($topicInfo as $key => $value) {


            if ($value['topicActive'] == 1) {
                $aktifArama = 'data-filter="Aktif"';
                $aktifYazi = '<span class="badge badge-light-success">Aktif</span>';
            } else {
                $aktifArama = 'data-filter="Passive"';
                $aktifYazi = '<span class="badge badge-light-danger">Pasif</span>';
            }

            $alter_button = $value['topicActive'] ? "Pasif Yap" : "Aktif Yap";

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

            $topicList = '
                    <tr>
                        <td>
                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="1" />
                            </div>
                        </td>
                        <td data-file-id="' . $value['topicID'] . '">
                            <a href="./konu-detay/' . $value['topicSlug'] . '" class="text-gray-800 text-hover-primary mb-1">' . $value['topicName'] . '</a>
                        </td>
                        <td>
                            ' . $value['unitName'] . '
                        </td>
                        <td>
                            ' . $value['lessonName'] . '
                        </td>
                        <td>
                            ' . $value['className'] . '
                        </td>
                        <td>
                            ' . $dateFormat->changeDate($value['topicStartDate']) . '
                        </td>
                        <td>
                            ' . $dateFormat->changeDate($value['topicEndDate']) . '
                        </td>
                        <td>
                            ' . $value['topicOrder'] . '
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
                                    <a href="./konu-detay/' . $value['topicSlug'] . '" class="menu-link px-3">Görüntüle</a>
                                </div>
                                <!--end::Menu item-->
                                ' . $passiveButton . '
                            </div>
                            <!--end::Menu-->
                        </td>
                    </tr>
                ';
            echo $topicList;
        }
    }

    public function getTopicListForFilter()
    {

        $topicInfo = $this->getTopicsList();

        foreach ($topicInfo as $key => $value) {

            $topicList = '
                    <option value="' . $value['id'] . '">' . $value['name'] . '</option>
                    ';
            echo $topicList;
        }
    }


    // Get Topics Student

    public function getTopicsListStudent()
    {

        $getUnit = new Units();

        $testResults = new TestsResult();

        $dateFormat = new DateFormat();

        $link = "$_SERVER[REQUEST_URI]";

        $today = date('Y-m-d');

        $active_slug = htmlspecialchars(basename($link, ".php"));

        $unitInfo = $this->showTopicsStudent($active_slug);

        $unitData = $this->getUnitDatas($active_slug);

        $getLessonId = $unitData['lesson_id'];
        $getClassId = $unitData['class_id'];
        $getOrderNo = $unitData['order_no'];

        if ($getOrderNo == 1) {
            $testQuery = 80 >= 80;
        } else {
            $getPreviousUnitId = $getUnit->getPrevUnitId($getOrderNo - 1, $getClassId, $getLessonId, $_SESSION['school_id']);
            $prevUnitId = $getPreviousUnitId['id'];
            $getTestResult = $testResults->getUnitTestResults($prevUnitId, $getClassId, $_SESSION['id']);
            $result = $getTestResult['score'] ?? 0;
            $testQuery = $result >= 80; // If the previous unit's test is not passed, the current unit cannot be accessed.
        }

        if ($today >= $unitData['start_date'] or $testQuery) {
        } else {
            header("Location: ../404.php"); // 404 sayfasına yönlendir
            exit();
        }

        if (empty($unitData)) {
            header("Location: ../404.php"); // 404 sayfasına yönlendir
            exit();
        }

        if ($unitInfo == NULL) {

            $testList = '
                        <!--begin::Col-->
                            <div class="col-md-12 d-flex flex-center">
                                <i class="fa-regular fa-face-frown-open text-danger fs-4x"></i>
                                
                            </div>

                            <div class="text-center mt-2">
                                <h4 class="fs-2hx text-gray-900 mb-5">Konu Mevcut Değil!</h4>
                            </div>

                        <!--end::Col-->
                        ';
            echo $testList;
        } else {

            foreach ($unitInfo as $key => $value) {

                $getLessonId = $value['lesson_id'];
                $getClassId = $value['class_id'];
                $getUnitId = $value['unit_id'];
                $getOrderNo = $value['order_no'];
                $getTopicId = $value['topicID'];

                if ($getOrderNo == 1) {
                    $testQuery = 80 >= 80;
                } else {
                    $getPreviousTopicId = $this->getPrevTopicId($getOrderNo - 1, $getClassId, $getLessonId, $getUnitId, $_SESSION['school_id']);
                    $prevTopicId = $getPreviousTopicId['id'];
                    $getTestResult = $testResults->getTopicTestResults($getUnitId, $getClassId, $prevTopicId, $_SESSION['id']);
                    $result = $getTestResult['score'] ?? 0;
                    $testQuery = $result >= 80; // If the previous unit's test is not passed, the current unit cannot be accessed.
                }

                if ($today >= $value['start_date'] or $testQuery) {
                    $link = "konu/{$value['topicSlug']}";
                    $class = "";
                    $notification = '';
                } else {
                    $link = "#";
                    $class = "pe-none";
                    $notification = '<div class="fw-semibold fs-5 text-danger mt-3 mb-5">Bu konunun tarihi gelmemiş veya bir önceki konunun sınavı başarı ile tamamlanmamıştır.</div>';
                }

                $testText = "";
                $unclickable = "";
                if ($value['is_test'] == 1) {
                    $testSolved = $this->isSolved($value['topicID']);
                    if (!empty($testSolved)) {
                        $testText = '<span class="fw-semibold fs-5 text-success mb-5">Bu testi ' . $dateFormat->changeDate($testSolved['created_at']) . ' tarihinde çözdünüz!</span>';
                        $unclickable = "pe-none";
                    }
                }
                if ($value['is_question'] == 1) {
                    $testSolved = $this->isSolvedQuestion($value['topicID']);
                    if (!empty($testSolved)) {
                        $testText = '<span class="fw-semibold fs-5 text-success mb-5">Bu testi ' . $dateFormat->changeDate($testSolved['created_at']) . ' tarihinde çözdünüz!</span>';
                        $unclickable = "pe-none";
                    }
                }

                $testList = '
                            <!--begin::Col-->
                            <div class="col-md-6 col-lg-6 col-xl-6">
                                <!--begin::Publications post-->
                                <div class="card-xl-stretch me-md-6">
                                    <!--begin::Overlay-->
                                    <a class="d-block ' . $class . ' overlay mb-4" href="' . $link . '">
                                        <!--begin::Image-->
                                        <div class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-175px" style="background-image:url(\'assets/media/topics/' . $value['topicImage'] . '\')"></div>
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
                                        <a href="' . $link . '" class="fs-4 text-gray-900 fw-bold text-hover-primary text-gray-900 ' . $class . ' lh-base">' . $value['topicName'] . '</a>
                                        <!--end::Title-->
                                        <!--begin::Text-->
                                        <div class="fw-semibold fs-5 text-gray-600 text-gray-900 mt-3 mb-5">' . $value['topicShortDesc'] . '<br>' . $testText . '</div>
                                        <!--end::Text-->
                                    </div>
                                    ' . $notification . '
                                    <!--end::Body-->
                                </div>
                                <!--end::Publications post-->
                            </div>
                            <!--end::Col-->
                    ';
                echo $testList;
            }
        }
    }

    // Get Topic Image For Students

    public function getHeaderImageStu()
    {
        $link = "$_SERVER[REQUEST_URI]";

        $active_slug = htmlspecialchars(basename($link, ".php"));

        $unitInfo = $this->getOneTopic($active_slug, $_SESSION['class_id']);

        foreach ($unitInfo as $key => $value) {

            $testList = '
                    <div class="position-relative mb-17">
                        <!--begin::Overlay-->
                        <div class="overlay overlay-show">
                            <!--begin::Image-->
                            <div class="bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-250px" style="background-image:url(\'assets/media/topics/' . $value['image'] . '\')"></div>
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
            echo $testList;
        }
    }

    // Show Topic For Students

    public function showTopicStudent()
    {
        $dateFormat = new DateFormat();

        $link = "$_SERVER[REQUEST_URI]";

        $active_slug = htmlspecialchars(basename($link, ".php"));

        $unitInfo = $this->getOneTopic($active_slug, $_SESSION['class_id']);

        if ($unitInfo == NULL) {

            $topicList = '
                        <!--begin::Col-->
                            <div class="col-md-12 d-flex flex-center">
                                <i class="fa-regular fa-face-frown-open text-danger fs-4x"></i>
                                
                            </div>

                            <div class="text-center mt-2">
                                <h4 class="fs-2hx text-gray-900 mb-5">Konu Mevcut Değil!</h4>
                            </div>

                        <!--end::Col-->
                        ';
            echo $topicList;
        } else {

            foreach ($unitInfo as $key => $value) {

                $topicList = '
                            <!--begin::Col-->
                            <div class="col-md-4">
                                <!--begin::Publications post-->
                                <div class="card-xl-stretch me-md-6">
                                    <!--begin::Overlay-->
                                    <a class="d-block overlay mb-4" href="unite/' . $value['slug'] . '">
                                        <!--begin::Image-->
                                        <div class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-175px" style="background-image:url(\'assets/media/topics/' . $value['image'] . '\')"></div>
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
                                        <a href="unite/' . $value['slug'] . '" class="fs-4 text-gray-900 fw-bold text-hover-primary text-gray-900 lh-base">' . $value['name'] . '</a>
                                        <!--end::Title-->
                                        <!--begin::Text-->
                                        <div class="fw-semibold fs-5 text-gray-600 text-gray-900 mt-3 mb-5">' . $value['short_desc'] . '</div>
                                        <!--end::Text-->
                                    </div>
                                    <!--end::Body-->
                                </div>
                                <!--end::Publications post-->
                            </div>
                            <!--end::Col-->
                    ';
                echo $topicList;
            }
        }
    }


    // Get Topics Sidebar For Students

    public function getSidebarTopicsStu()
    {

        $testResults = new TestsResult();

        $link = "$_SERVER[REQUEST_URI]";

        $today = date('Y-m-d');

        $active_slug = htmlspecialchars(basename($link, ".php"));

        $unitInfo = $this->getSameTopics($active_slug);

        $lessonList = '<div class="card-body">
                        <!--begin::Top-->
                        <div class="mb-7">
                            <!--begin::Title-->
                            <h2 class="fs-1 text-gray-800 w-bolder mb-6">Diğer Konular</h2>
                            <!--end::Title-->
                        </div>
                        <!--end::Top-->
                        <!--begin::Item-->';

        foreach ($unitInfo as $key => $value) {

            $getLessonId = $value['lesson_id'];
            $getClassId = $value['class_id'];
            $getUnitId = $value['unit_id'];
            $getOrderNo = $value['order_no'];
            $getTopicId = $value['topicID'];

            if ($getOrderNo == 1) {
                $testQuery = 80 >= 80;
            } else {
                $getPreviousTopicId = $this->getPrevTopicId($getOrderNo - 1, $getClassId, $getLessonId, $getUnitId, $_SESSION['school_id']);
                $prevTopicId = $getPreviousTopicId['id'];
                $getTestResult = $testResults->getTopicTestResults($getUnitId, $getClassId, $prevTopicId, $_SESSION['id']);
                $result = $getTestResult['score'] ?? 0;
                $testQuery = $result >= 80; // If the previous unit's test is not passed, the current unit cannot be accessed.
            }

            if ($today >= $value['start_date'] or $testQuery) {
                $link = "konu/{$value['topicSlug']}";
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
                                    <div class="text-gray-600 fw-semibold fs-6 ' . $class . '"><a href="' . $link . '">' . $value['topicName'] . '</a></div>
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

    // Get Tab Titles For Topic Details Page

    public function getTabTitles($slug)
    {
        $topicInfo = $this->getOneTopicDetailsAdmin($slug);
        foreach ($topicInfo as $value) {

            $topicId = $value['id'];

            $subtopics = new SubTopics();

            $getContents = new GetContent();

            $subtopic = $subtopics->getSubTopicInfoByIds($topicId, $value['unit_id'], $value['lesson_id'], $value['class_id']);

            $content = $getContents->getContentInfoByIds($topicId, $value['unit_id'], $value['lesson_id'], $value['class_id']);

            $topicNumber = count($subtopic);

            if ($topicNumber != 0) {
                $tabText = "Alt Konu";
            }

            $contentNumber = count($content);

            if ($contentNumber != 0) {
                $tabText = "İçerik";
            }

            if ($topicNumber == 0 and $contentNumber == 0) {
                $tabText = "Alt Konu / İçerik Yok!";
            }
        }

        return $tabText;
    }

    // Show Topic

    public function showOneTopic($slug)
    {

        /* $topicInfo = $this->getOneTopic($slug, $_SESSION['class_id']); */

        $dateFormat = new DateFormat();

        $topicInfo = $this->getOneTopicDetailsAdmin($slug);

        if (count($topicInfo) == 0) {
            $topicList = header("Location: http://localhost/lineup_campus/404.php"); // 404 sayfasına yönlendir
            echo $topicList;
            return;
        }

        /* $topicList = '
                <div class="mb-3">
                    <h1 class="h3 d-inline align-middle">Böyle bir konu mevcut değil.</h1>
                </div>
        '; */

        foreach ($topicInfo as $value) {

            $topicId = $value['id'];

            $subtopics = new SubTopics();

            $getContents = new GetContent();

            $subtopic = $subtopics->getSubTopicInfoByIds($topicId, $value['unit_id'], $value['lesson_id'], $value['class_id']);

            $content = $getContents->getContentInfoByIds($topicId, $value['unit_id'], $value['lesson_id'], $value['class_id']);

            $topicNumber = count($subtopic);

            if ($topicNumber != 0) {
                $statNumber = $topicNumber; // Son eleman hariç say
                $statText = "Alt Konu";
            }

            $contentNumber = count($content);

            if ($contentNumber != 0) {
                $statNumber = $contentNumber; // Son eleman hariç say
                $statText = "İçerik";
            }

            if ($topicNumber == 0 and $contentNumber == 0) {
                $statNumber = 0; // Son eleman hariç say
                $statText = "İçerik / Alt Konu";
            }

            $topicList = '
                <div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">
                    <!--begin::Card-->
                    <div class="card mb-5 mb-xl-8">
                        <!--begin::Card body-->
                        <div class="card-body pt-15">
                            <!--begin::Summary-->
                            <div class="d-flex flex-center flex-column mb-5">
                                <!--begin::Avatar-->
                                <div class="mb-7">
                                    <img class="mw-100" src="assets/media/topics/' . $value['image'] . '" alt="image" />
                                </div>
                                <!--end::Avatar-->
                                <!--begin::Name-->
                                <p class="fs-3 text-gray-800 fw-bold mb-1">' . $value['name'] . '</p>
                                <!--end::Name-->
                                <!--begin::Position-->
                                <div class="fs-5 fw-semibold text-muted mb-6">' . $value['unitName'] . '</div>
                                <!--end::Position-->
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
                                            <span class="w-75px">' . $statNumber . '</span>
                                            <i class="fa-solid fa-book-open fs-3 text-success"></i>
                                        </div>
                                        <div class="fw-semibold text-muted">' . $statText . '</div>
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
                                <span data-bs-toggle="tooltip" data-bs-trigger="hover" title="Konu bilgilerini düzenle">
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
                                    <div class="fw-bold mt-5">Konu Başlama Tarihi</div>
                                    <div class="text-gray-600">' . $dateFormat->changeDate($value['start_date']) . '</div>
                                    <!--end::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bold mt-5">Konu Bitiş Tarihi</div>
                                    <div class="text-gray-600">' . $dateFormat->changeDate($value['end_date']) . '</div>
                                    <!--end::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bold mt-5">Konu Sırası</div>
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
        echo $topicList;
    }

    /*   // Update Topic

    public function updateOneTopic($slug)
    {

        $topicInfo = $this->getOneTopic($slug);

        foreach ($topicInfo as $value) {

            $topicList = '
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
        echo $topicList;
    } */

    // List of Topic

    public function showTopicList()
    {

        $topicInfo = $this->getTopics();

        $topicList = '';

        $divStart = '<div class="row"><div class="overflow-auto col-12 col-lg-2" style="max-height: 150px">';

        $divEnd = '</div></div>';

        foreach ($topicInfo as $value) {

            $parentId = $value['id'];

            $topicForms = '<div class="form-check">
                                            <input class="form-check-input" name="category[]" type="checkbox" value="' . $value['id'] . '" id="' . $value['slug'] . '">
                                                <label class="form-check-label" for="' . $value['slug'] . '">
                                                ' . $value['catName'] . '
                                                </label>
                                        </div>';



            $topicList .= $topicForms;
        }


        echo $divStart . $topicList . $divEnd;
    }

    // Get Topics For Unit Detail

    public function showTopicsListForUnitDetail($slug)
    {

        $unitInfo = new Units();

        $unitInfo = $unitInfo->getOneUnitForDetails($slug);

        $unitId = $unitInfo[0]['id'];

        $topicInfo = $this->getTopicsByUnitIdWithDetails($unitId);

        foreach ($topicInfo as $key => $value) {


            if ($value['active'] == 1) {
                $aktifArama = 'data-filter="Aktif"';
                $aktifYazi = '<span class="badge badge-light-success">Aktif</span>';
            } else {
                $aktifArama = 'data-filter="Passive"';
                $aktifYazi = '<span class="badge badge-light-danger">Pasif</span>';
            }

            $alter_button = $value['active'] ? "Pasif Yap" : "Aktif Yap";

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

            $topicData = '<tr>
                                <td data-file-id="' . $value['id'] . '">
                                    <a href="./konu-detay/' . $value['slug'] . '" class="text-gray-600 text-hover-primary mb-1"> ' . $value['name'] .  '</a>
                                </td>
                                <td>
                                    ' . $value['lessonName'] . '</a>
                                </td>
                                <td>' . $value['className'] . '</td>
                                <td>' . $aktifYazi . '</td>
                                <td class="pe-0 text-end">
                                    <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">İşlemler
                                        <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                                    <!--begin::Menu-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                        data-kt-menu="true">
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="./konu-detay/' . $value['slug'] . '" class="menu-link px-3">Görüntüle</a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-kt-customer-table-filter="delete_row">'. $alter_button .'</a>
                                        </div>
                                        <!--end::Menu item-->
                                    </div>
                                    <!--end::Menu-->
                                </td>
                            </tr>';
            echo $topicData;
        }
    }
}

class ShowSubTopic extends SubTopics
{

    // Get Topic List

    public function getSubTopicList()
    {

        $subTopicInfo = $this->getSubTopicsListWithFilter();

        $dateFormat = new DateFormat();

        foreach ($subTopicInfo as $key => $value) {


            if ($value['subTopicActive'] == 1) {
                $aktifArama = 'data-filter="Aktif"';
                $aktifYazi = '<span class="badge badge-light-success">Aktif</span>';
            } else {
                $aktifArama = 'data-filter="Passive"';
                $aktifYazi = '<span class="badge badge-light-danger">Pasif</span>';
            }

            $alter_button = $value['subTopicActive'] ? "Pasif Yap" : "Aktif Yap";

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

            $subTopicList = '
                    <tr>
                        <td>
                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="1" />
                            </div>
                        </td>
                        <td data-file-id="' . $value['subTopicID'] . '">
                            <a href="./alt-konu-detay/' . $value['subTopicSlug'] . '" class="text-gray-800 text-hover-primary mb-1">' . $value['subTopicName'] . '</a>
                        </td>
                        <td>
                            ' . $value['topicName'] . '
                        </td>
                        <td>
                            ' . $value['unitName'] . '
                        </td>
                        <td>
                            ' . $value['lessonName'] . '
                        </td>
                        <td>
                            ' . $value['className'] . '
                        </td>
                        <td>
                            ' . $dateFormat->changeDate($value['subTopicStartDate']) . '
                        </td>
                        <td>
                            ' . $dateFormat->changeDate($value['subTopicEndDate']) . '
                        </td>
                        <td>
                            ' . $value['subTopicOrder'] . '
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
                                    <a href="./alt-konu-detay/' . $value['subTopicSlug'] . '" class="menu-link px-3">Görüntüle</a>
                                </div>
                                <!--end::Menu item-->
                                ' . $passiveButton . '
                            </div>
                            <!--end::Menu-->
                        </td>
                    </tr>
                ';
            echo $subTopicList;
        }
    }

    // Get SubTopics Student

    public function getSubTopicsListStudent()
    {

        $testResults = new TestsResult();

        $getTopics = new Topics();

        $dateFormat = new DateFormat();

        $link = "$_SERVER[REQUEST_URI]";

        $today = date('Y-m-d');

        $active_slug = htmlspecialchars(basename($link, ".php"));

        $topicInfo = $this->getTopicIdBySlug($active_slug);
        $topicId = $topicInfo['id'];

        $getLessonId = $topicInfo['lesson_id'];
        $getClassId = $topicInfo['class_id'];
        $getUnitId = $topicInfo['unit_id'];
        $getOrderNo = $topicInfo['order_no'];

        if ($getOrderNo == 1) {
            $testQuery = 80 >= 80;
        } else {
            $getPreviousTopicId = $getTopics->getPrevTopicId($getOrderNo - 1, $getClassId, $getLessonId, $getUnitId, $_SESSION['school_id']);
            $prevTopicId = $getPreviousTopicId['id'];
            $getTestResult = $testResults->getTopicTestResults($getUnitId, $getClassId, $prevTopicId, $_SESSION['id']);
            $result = $getTestResult['score'] ?? 0;
            $testQuery = $result >= 80; // If the previous unit's test is not passed, the current unit cannot be accessed.
        }

        if ($today >= $topicInfo['start_date'] or $testQuery) {
        } else {
            header("Location: ../404.php"); // 404 sayfasına yönlendir
            exit();
        }

        if (empty($topicInfo)) {
            header("Location: ../404.php"); // 404 sayfasına yönlendir
            exit();
        }

        $controlSubTopic = $this->controlIsThereSubTopic($topicId, $_SESSION['class_id']);

        if (!empty($controlSubTopic)) {
            $unitInfo = $this->showSubTopicsStudent($active_slug);

            if ($unitInfo == NULL) {

                $testList = '
                        <!--begin::Col-->
                            <div class="col-md-12 d-flex flex-center">
                                <i class="fa-regular fa-face-frown-open text-danger fs-4x"></i>
                                
                            </div>

                            <div class="text-center mt-2">
                                <h4 class="fs-2hx text-gray-900 mb-5">Konu Mevcut Değil!</h4>
                            </div>

                        <!--end::Col-->
                        ';
                echo $testList;
            } else {

                foreach ($unitInfo as $key => $value) {

                    $getLessonId = $value['lesson_id'];
                    $getClassId = $value['class_id'];
                    $getUnitId = $value['unit_id'];
                    $getTopicId = $value['topic_id'];
                    $getOrderNo = $value['order_no'];

                    if ($getOrderNo == 1) {
                        $testQuery = 80 >= 80;
                    } else {
                        $getPreviousSubTopicId = $this->getPrevSubTopicId($getOrderNo - 1, $getClassId, $getLessonId, $getUnitId, $getTopicId, $_SESSION['school_id']);
                        $prevSubTopicId = $getPreviousSubTopicId['id'];
                        $getTestResult = $testResults->getSubTopicTestResults($getUnitId, $getClassId, $getTopicId, $prevSubTopicId, $_SESSION['id']);
                        $result = $getTestResult['score'] ?? 0;
                        $testQuery = $result >= 80; // If the previous unit's test is not passed, the current unit cannot be accessed.
                    }

                    if ($today >= $value['start_date'] or $testQuery) {
                        $link = "alt-konu/{$value['topicSlug']}";
                        $class = "";
                        $notification = '';
                    } else {
                        $link = "#";
                        $class = "pe-none";
                        $notification = '<div class="fw-semibold fs-5 text-danger mt-3 mb-5">Bu alt konunun tarihi gelmemiş veya bir önceki alt konunun sınavı başarı ile tamamlanmamıştır.</div>';
                    }


                    $testText = "";
                    $unclickable = "";
                    if ($value['is_test'] == 1) {
                        $testSolved = $this->isSolvedUser($value['topicID'], $_SESSION['id']);
                        if (!empty($testSolved)) {
                            $testText = '<span class="fw-semibold fs-5 text-success mb-5">Bu testi ' . $dateFormat->changeDate($testSolved['created_at']) . ' tarihinde çözdünüz!</span>';
                            $unclickable = "pe-none";
                        }
                    }
                    if ($value['is_question'] == 1) {
                        $testSolved = $this->isSolvedQuestionUser($value['topicID'], $_SESSION['id']);
                        if (!empty($testSolved)) {
                            $testText = '<span class="fw-semibold fs-5 text-success mb-5">Bu testi ' . $dateFormat->changeDate($testSolved['created_at']) . ' tarihinde çözdünüz!</span>';
                            $unclickable = "pe-none";
                        }
                    }

                    $testList = '
                            <!--begin::Col-->
                            <div class="col-md-6 col-lg-6 col-xl-6">
                                <!--begin::Publications post-->
                                <div class="card-xl-stretch me-md-6">
                                    <!--begin::Overlay-->
                                    <a class="d-block ' . $class . ' overlay mb-4" href="' . $link . '">
                                        <!--begin::Image-->
                                        <div class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-175px" style="background-image:url(\'assets/media/topics/' . $value['topicImage'] . '\')"></div>
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
                                        <a href="' . $link . '" class="fs-4 text-gray-900 fw-bold text-hover-primary text-gray-900 ' . $class . ' lh-base">' . $value['topicName'] . '</a>
                                        <!--end::Title-->
                                        <!--begin::Text-->
                                        <div class="fw-semibold fs-5 text-gray-600 text-gray-900 mt-3 mb-5">' . $value['topicShortDesc'] . '<br>' . $testText . '</div>
                                        <!--end::Text-->
                                    ' . $notification . '
                                    </div>
                                    <!--end::Body-->
                                </div>
                                <!--end::Publications post-->
                            </div>
                            <!--end::Col-->
                    ';
                    echo $testList;
                }
            }
        } else {
            $contents = new GetContent();

            $link = "$_SERVER[REQUEST_URI]";

            $active_slug = htmlspecialchars(basename($link, ".php"));

            $topic = new SubTopics();

            $topicInfo = $topic->getTopicIdBySlug($active_slug);
            $topicId = $topicInfo['id'];

            $contentInfo = $contents->getContentInfoByIdUnderTopic($topicId);

            if ($contentInfo == NULL) {

                $contentList = '
                        <!--begin::Col-->
                            <div class="col-md-12 d-flex flex-center">
                                <i class="fa-regular fa-face-frown-open text-danger fs-4x"></i>
                                
                            </div>

                            <div class="text-center mt-2">
                                <h4 class="fs-2hx text-gray-900 mb-5">İçerik Mevcut Değil!</h4>
                            </div>

                        <!--end::Col-->
                        ';
                echo $contentList;
            } else {

                foreach ($contentInfo as $key => $value) {

                    $contentList = '
                            <!--begin::Col-->
                            <div class="col-md-6 col-lg-6 col-xl-6">
                                <!--begin::Publications post-->
                                <div class="card-xl-stretch me-md-6">
                                    <!--begin::Overlay-->
                                    <a class="d-block overlay mb-4" href="icerik/' . $value['slug'] . '">
                                        <!--begin::Image-->
                                        <div class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-175px" style="background-image:url(\'uploads/contents/' . $value['cover_img'] . '\')"></div>
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
                                        <a href="icerik/' . $value['slug'] . '" class="fs-4 text-gray-900 fw-bold text-hover-primary text-gray-900 lh-base">' . $value['title'] . '</a>
                                        <!--end::Title-->
                                        <!--begin::Text-->
                                        <div class="fw-semibold fs-5 text-gray-600 text-gray-900 mt-3 mb-5">' . $value['summary'] . '</div>
                                        <!--end::Text-->
                                    </div>
                                    <!--end::Body-->
                                </div>
                                <!--end::Publications post-->
                            </div>
                            <!--end::Col-->
                    ';
                    echo $contentList;
                }
            }
        }
    }

    // Get Topic Image For Students

    public function getHeaderImageStu()
    {
        $link = "$_SERVER[REQUEST_URI]";

        $active_slug = htmlspecialchars(basename($link, ".php"));

        $unitInfo = $this->getOneSubTopic($active_slug);

        foreach ($unitInfo as $key => $value) {

            $testList = '
                    <div class="position-relative mb-17">
                        <!--begin::Overlay-->
                        <div class="overlay overlay-show">
                            <!--begin::Image-->
                            <div class="bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-250px" style="background-image:url(\'assets/media/topics/' . $value['image'] . '\')"></div>
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
            echo $testList;
        }
    }

    // Show Topic For Students

    public function showTopicStudent()
    {
        $dateFormat = new DateFormat();

        // $videoName = new getVideo();

        $link = "$_SERVER[REQUEST_URI]";

        $active_slug = htmlspecialchars(basename($link, ".php"));

        $unitInfo = $this->getOneSubTopic($active_slug);

        foreach ($unitInfo as $key => $value) {

            if ($value['is_test'] == 0 and $value['is_question'] == 0) {

                //$youtubeID = $this->getYouTubeVideoId($value['video_url']);
                $youtubeID = $value['video_url'];
                //$videoName->getVideoFrom($value['video_url']);

                $testList = '
                    <!--begin::Description-->
                    <div class="m-0">
                        <!--begin::Title-->
                        <h4 class="fs-1 text-gray-800 w-bolder mb-6">Konu İçeriği</h4>
                        <!--end::Title-->
                        <!--begin::Text-->
                        <p class="fw-semibold fs-4 text-gray-600 mb-2">' . $value['content'] . '</p>
                        <!--end::Text-->
                        <!--begin::Title-->
                        <h4 class="fs-1 text-gray-800 w-bolder mb-6 mt-16">Video İçeriği</h4>
                        <!--end::Title-->
                        <!--begin::Text-->
                        <p class="fw-semibold fs-4 text-gray-600 mb-2">
                            <video id="my-video" controls width="100%" height="640px">
                                <source src="../lineup/api/video/get.php" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </p>
                        <!--end::Text-->
                    </div>
                ';
            } elseif ($value['is_test'] == 1) {
                $testSolved = $this->isSolvedUser($value['id'], $_SESSION['id']);
                if (!empty($testSolved)) {
                    $testList = "Bu testi " . $dateFormat->changeDate($testSolved['created_at']) . " tarihinde çözdünüz!";
                } else {
                    $testList = '<form class="form" action="#" id="kt_modal_add_customer_form" data-kt-redirect="konular">';
                    $test = $this->getOneTest($value['id']);
                    foreach ($test as $key => $testValue) {
                        $testQuestions = explode(":/;", $testValue['questions']);
                        $testAnswers = explode(":/;", $testValue['answers']);
                        $numberOfQuestions = count($testQuestions);
                        for ($i = 0; $i < $numberOfQuestions; $i++) {
                            if ($i % 2 == 1) {
                                $className = "";
                            } else {
                                $className = "bg-light";
                            }
                            $testOptions = explode("*-*", $testAnswers[$i]);
                            $numberOfOptions = count($testOptions);

                            $optionsList = "";

                            if ($i == 0) {
                                $testList .= '<input type="hidden" name="test_id" value="' . $testValue['id'] . '">';
                            }

                            for ($x = 0; $x < $numberOfOptions; $x++) {
                                if ($x == 0) {
                                    $testOptionLetter = "A";
                                } elseif ($x == 1) {
                                    $testOptionLetter = "B";
                                } elseif ($x == 2) {
                                    $testOptionLetter = "C";
                                } elseif ($x == 3) {
                                    $testOptionLetter = "D";
                                }
                                $optionsList .= '
                                            <div class="col-md-3 mb-3">
                                                <label>
                                                    <input class="form-check-input ms-10 soru_' . $i + 1 . '" type="checkbox" name="testcevap[]" value="' . $testOptionLetter . '"> ' . $testOptions[$x] . '
                                                    <input type="hidden" class="nextInput" name="testcevap[]" value="1">
                                                </label>
                                            </div>
                                        ';
                            }

                            $testList .= '
                                            <div class="col-md-12 ' . $className . ' p-3">
                                                <div data-kt-element="items">
                                                    <div class="" data-kt-element="item" data-soru-id="' . $i + 1 . '">
                                                        <!--begin::Input group-->
                                                        <div class="fv-row mb-2">
                                                            <!--begin::Label-->
                                                            <label class="required fs-6 fw-semibold mb-2">Soru ' . $i + 1 . '</label>
                                                            <!--end::Label-->
                                                        </div>
                                                        <div class="fv-row mb-6">
                                                            <!--begin::Question-->
                                                            ' . $testQuestions[$i] . '
                                                            <!--end::Question-->
                                                        </div>
                                                        <!--end::Input group-->

                                                        <!--begin::Input group-->
                                                        <div class="fv-row row">
                                                            ' . $optionsList . '
                                                        </div>
                                                        <!--end::Input group-->
                                                    </div>
                                                </div>
                                            </div>
                                        ';
                        }
                    }

                    $testList .= '<!--begin::Button-->
                                <button type="submit" id="kt_modal_add_customer_submit" class="btn btn-primary btn-sm mt-6">
                                    <span class="indicator-label">Cevapları Gönder</span>
                                    <span class="indicator-progress">Lütfen Bekleyin...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                                <!--end::Button-->
                                </form>';
                }
            } elseif ($value['is_question'] == 1) {
                $testSolved = $this->isSolvedQuestionUser($value['id'], $_SESSION['id']);
                if (!empty($testSolved)) {
                    $testList = "Bu testi " . $dateFormat->changeDate($testSolved['created_at']) . " tarihinde çözdünüz!";
                } else {
                    $testList = '<form class="form" action="#" id="kt_modal_add_customer_form" data-kt-redirect="konular">';
                    $test = $this->getOneQuestion($value['id']);
                    foreach ($test as $key => $testValue) {
                        $testQuestions = explode(":/;", $testValue['questions']);
                        $testAnswers = explode(":/;", $testValue['answers']);
                        $numberOfQuestions = count($testQuestions);
                        for ($i = 0; $i < $numberOfQuestions; $i++) {
                            if ($i % 2 == 1) {
                                $className = "";
                            } else {
                                $className = "bg-light";
                            }
                            $testOptions = explode("*-*", $testAnswers[$i]);
                            $numberOfOptions = count($testOptions);

                            $optionsList = "";

                            if ($i == 0) {
                                $testList .= '<input type="hidden" name="question_id" value="' . $testValue['id'] . '">';
                            }

                            for ($x = 0; $x < $numberOfOptions; $x++) {
                                if ($x == 0) {
                                    $testOptionLetter = "A";
                                } elseif ($x == 1) {
                                    $testOptionLetter = "B";
                                } elseif ($x == 2) {
                                    $testOptionLetter = "C";
                                } elseif ($x == 3) {
                                    $testOptionLetter = "D";
                                }
                                $optionsList .= '
                                            <div class="col-md-3 mb-3">
                                                <label>
                                                    <input class="form-check-input ms-10 soru_' . $i + 1 . '" type="checkbox" name="testcevap[]" value="' . $testOptionLetter . '"> ' . $testOptions[$x] . '
                                                    <input type="hidden" class="nextInput" name="testcevap[]" value="1">
                                                </label>
                                            </div>
                                        ';
                            }

                            $testList .= '
                                            <div class="col-md-12 ' . $className . ' p-3">
                                                <div data-kt-element="items">
                                                    <div class="" data-kt-element="item" data-soru-id="' . $i + 1 . '">
                                                        <!--begin::Input group-->
                                                        <div class="fv-row mb-2">
                                                            <!--begin::Label-->
                                                            <label class="required fs-6 fw-semibold mb-2">Soru ' . $i + 1 . '</label>
                                                            <!--end::Label-->
                                                        </div>
                                                        <div class="fv-row mb-6">
                                                            <!--begin::Question-->
                                                            ' . $testQuestions[$i] . '
                                                            <!--end::Question-->
                                                        </div>
                                                        <!--end::Input group-->

                                                        <!--begin::Input group-->
                                                        <div class="fv-row row">
                                                            ' . $optionsList . '
                                                        </div>
                                                        <!--end::Input group-->
                                                    </div>
                                                </div>
                                            </div>
                                        ';
                        }
                    }

                    $testList .= '<!--begin::Button-->
                                <button type="submit" id="kt_modal_add_customer_submit" class="btn btn-primary btn-sm mt-6">
                                    <span class="indicator-label">Cevapları Gönder</span>
                                    <span class="indicator-progress">Lütfen Bekleyin...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                                <!--end::Button-->
                                </form>';
                }
            }

            echo $testList;
        }
    }


    // Get SubTopics Sidebar For Students

    public function getSidebarSubTopicsStu()
    {

        $testResults = new TestsResult();

        $link = "$_SERVER[REQUEST_URI]";

        $today = date('Y-m-d');

        $active_slug = htmlspecialchars(basename($link, ".php"));

        $unitInfo = $this->getSameSubTopics($active_slug);

        $lessonList = '<div class="card-body">
                        <!--begin::Top-->
                        <div class="mb-7">
                            <!--begin::Title-->
                            <h2 class="fs-1 text-gray-800 w-bolder mb-6">Diğer Alt Konular</h2>
                            <!--end::Title-->
                        </div>
                        <!--end::Top-->
                        <!--begin::Item-->';

        foreach ($unitInfo as $key => $value) {

            $getLessonId = $value['lesson_id'];
            $getClassId = $value['class_id'];
            $getUnitId = $value['unit_id'];
            $getTopicId = $value['topic_id'];
            $getOrderNo = $value['order_no'];

            if ($getOrderNo == 1) {
                $testQuery = 80 >= 80;
            } else {
                $getPreviousSubTopicId = $this->getPrevSubTopicId($getOrderNo - 1, $getClassId, $getLessonId, $getUnitId, $getTopicId, $_SESSION['school_id']);
                $prevSubTopicId = $getPreviousSubTopicId['id'];
                $getTestResult = $testResults->getSubTopicTestResults($getUnitId, $getClassId, $getTopicId, $prevSubTopicId, $_SESSION['id']);
                $result = $getTestResult['score'] ?? 0;
                $testQuery = $result >= 80; // If the previous unit's test is not passed, the current unit cannot be accessed.
            }

            if ($today >= $value['start_date'] or $testQuery) {
                $link = "alt-konu/{$value['topicSlug']}";
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
                                    <div class="text-gray-600 ' . $class . ' fw-semibold fs-6"><a href="' . $link . '">' . $value['topicName'] . '</a></div>
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

    // Show Topic

    public function showOneSubTopic($slug)
    {

        $subTopicInfo = $this->getOneSubTopicDetailsAdmin($slug);

        $dateFormat = new DateFormat();

        if (count($subTopicInfo) == 0) {
            $subTopicList = header("Location: http://localhost/lineup_campus/404.php"); // 404 sayfasına yönlendir
            echo $subTopicList;
            return;
        }

        /*  $topicList = '
                <div class="mb-3">
                    <h1 class="h3 d-inline align-middle">Böyle bir alt konu mevcut değil.</h1>
                </div>
        '; */

        foreach ($subTopicInfo as $value) {

            $subTopicId = $value['id'];

            $getContents = new GetContent();

            $content = $getContents->getContentInfoByIdsUnderSubTopic($subTopicId, $value['topic_id'], $value['unit_id'], $value['lesson_id'], $value['class_id']);

            $statNumber = count($content);

            $statText = "İçerik";

            $subTopicList = '
                <div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">
                    <!--begin::Card-->
                    <div class="card mb-5 mb-xl-8">
                        <!--begin::Card body-->
                        <div class="card-body pt-15">
                            <!--begin::Summary-->
                            <div class="d-flex flex-center flex-column mb-5">
                                <!--begin::Avatar-->
                                <div class="mb-7">
                                    <img class="mw-100" src="assets/media/topics/' . $value['image'] . '" alt="image" />
                                </div>
                                <!--end::Avatar-->
                                <!--begin::Name-->
                                <p class="fs-3 text-gray-800  fw-bold mb-1">' . $value['name'] . '</p>
                                <!--end::Name-->
                                <!--begin::Position-->
                                <div class="fs-5 fw-semibold text-muted mb-6">' . $value['topicName'] . '</div>
                                <!--end::Position-->
                                <!--begin::Position-->
                                <div class="fs-5 fw-semibold text-muted mb-6">' . $value['unitName'] . '</div>
                                <!--end::Position-->
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
                                            <span class="w-75px">' . $statNumber . '</span>
                                            <i class="fa-solid fa-book-open fs-3 text-success"></i>
                                        </div>
                                        <div class="fw-semibold text-muted">' . $statText . '</div>
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
                                <span data-bs-toggle="tooltip" data-bs-trigger="hover" title="Konu bilgilerini düzenle">
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
                                    <div class="fw-bold mt-5">Konu Başlama Tarihi</div>
                                    <div class="text-gray-600">' . $dateFormat->changeDate($value['start_date']) . '</div>
                                    <!--end::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bold mt-5">Konu Bitiş Tarihi</div>
                                    <div class="text-gray-600">' . $dateFormat->changeDate($value['end_date']) . '</div>
                                    <!--end::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bold mt-5">Konu Sırası</div>
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
        echo $subTopicList;
    }

    // Update SubTopic

    public function updateOneSubTopic($slug)
    {

        $subTopicInfo = $this->getOneSubTopicDetailsAdmin($slug);

        foreach ($subTopicInfo as $value) {

            if ($value['image'] == NULL) {
                $image = 'assets/media/topics/blank-image.svg';
            } else {
                $image = 'assets/media/topics/' . $value['image'];
            }

            $order_no = $value['order_no'] ?? '';

            $startDate = htmlspecialchars($value['start_date'] ?? '');
            $endDate = htmlspecialchars($value['end_date'] ?? '');

            $subTopicList = '
                <form class="form" action="#" id="kt_modal_update_customer_form" data-kt-redirect="alt-konular">
                    <!--begin::Modal header-->
                    <div class="modal-header" id="kt_modal_update_customer_header">
                        <!--begin::Modal title-->
                        <h2 class="fw-bold">Alt Konu Güncelle</h2>
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
                                <label class="required fs-6 fw-semibold mb-2">Alt Konu</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" id="name" class="form-control form-control-solid" value="' . $value['name'] . '"
                                    name="name" />
                                <input type="hidden" id="slug" name="slug" value="' . $slug . '">
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
                                <label class="required fs-6 fw-semibold mb-2">Konu Başlangıç Tarihi</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                    <input type="date" class="form-control form-control-solid fw-bold pe-5" value="' . $startDate . '" placeholder="Alt Konu Başlangıç Tarihi Seçin" name="start_date" id="start_date">
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">Konu Bitiş Tarihi</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                    <input type="date" class="form-control form-control-solid fw-bold pe-5" value="' . $endDate . '" placeholder="Alt Konu Bitiş Tarihi Seçin" name="end_date" id="end_date">
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">Konu Sırası</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                    <input type="number" class="form-control form-control-solid fw-bold pe-5" value=' . $order_no . ' placeholder="Konu Sırası Girin" name="order" id="order">
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
        echo $subTopicList;
    }

    // List of Topic

    public function showTopicList()
    {

        $topicInfo = $this->getTopics();

        $topicList = '';

        $divStart = '<div class="row"><div class="overflow-auto col-12 col-lg-2" style="max-height: 150px">';

        $divEnd = '</div></div>';

        foreach ($topicInfo as $value) {

            $parentId = $value['id'];

            $topicForms = '<div class="form-check">
                                            <input class="form-check-input" name="category[]" type="checkbox" value="' . $value['id'] . '" id="' . $value['slug'] . '">
                                                <label class="form-check-label" for="' . $value['slug'] . '">
                                                ' . $value['catName'] . '
                                                </label>
                                        </div>';



            $topicList .= $topicForms;
        }


        echo $divStart . $topicList . $divEnd;
    }

    // Get Topic For Sub Topic List

    public function showTopicForSubTopicList($class, $lessons, $units)
    {

        $unitInfo = $this->getTopicForSubTopicList($class, $lessons, $units);

        $units = array();

        foreach ($unitInfo as $key => $value) {

            $units[] = array("id" => $value["id"], "text" => $value["name"]);
        }


        echo json_encode($units);
    }

    public function showSubtopicForTopic($class, $lessons, $units, $topic)
    {

        $subtopicsInfo = $this->getSubtopicForTopic($class, $lessons, $units, $topic);

        $subtopics = array();

        foreach ($subtopicsInfo as $key => $value) {

            $subtopics[] = array("id" => $value["id"], "text" => $value["name"]);
        }

        echo json_encode($subtopics);
    }


    // Get Sub Topics For Unit Detail

    public function showSubTopicsListForUnitDetail($slug)
    {

        $unitInfo = new Units();

        $unitInfo = $unitInfo->getOneUnitForDetails($slug);

        $unitId = $unitInfo[0]['id'];

        $topicInfo = $this->getSubTopicsByUnitIdWithDetails($unitId);

        foreach ($topicInfo as $key => $value) {

            if ($value['active'] == 1) {
                $aktifArama = 'data-filter="Aktif"';
                $aktifYazi = '<span class="badge badge-light-success">Aktif</span>';
            } else {
                $aktifArama = 'data-filter="Passive"';
                $aktifYazi = '<span class="badge badge-light-danger">Pasif</span>';
            }

            $alter_button = $value['active'] ? "Pasif Yap" : "Aktif Yap";

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

            $topicData = '<tr>
                                <td data-file-id="' . $value['id'] . '">
                                    <a href="./alt-konu-detay/' . $value['slug'] . '" class="text-gray-600 text-hover-primary mb-1"> ' . $value['name'] .  '</a>
                                </td>
                                <td>
                                    ' . $value['lessonName'] . '</a>
                                </td>
                                <td>' . $value['className'] . '</td>
                                <td>' . $aktifYazi . '</td>
                                <td class="pe-0 text-end">
                                    <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">İşlemler
                                        <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                                    <!--begin::Menu-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                        data-kt-menu="true">
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="./alt-konu-detay/' . $value['slug'] . '" class="menu-link px-3">Görüntüle</a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-kt-customer-table-filter="delete_row2">' . $alter_button . '</a>
                                        </div>
                                        <!--end::Menu item-->
                                    </div>
                                    <!--end::Menu-->
                                </td>
                            </tr>';
            echo $topicData;
        }
    }


    // Get Sub Topics For Topic Details Page

    public function showSubTopicsListForTopicDetail($slug)
    {

        $unitInfo = new Topics();

        $unitInfo = $unitInfo->getOneTopicDetailsAdmin($slug);

        $unitId = $unitInfo[0]['id'];

        $topicInfo = $this->getSubTopicsByTopicIdWithDetails($unitId);

        foreach ($topicInfo as $key => $value) {


            if ($value['active'] == 1) {
                $aktifArama = 'data-filter="Aktif"';
                $aktifYazi = '<span class="badge badge-light-success">Aktif</span>';
            } else {
                $aktifArama = 'data-filter="Passive"';
                $aktifYazi = '<span class="badge badge-light-danger">Pasif</span>';
            }

            $alter_button = $value['active'] ? "Pasif Yap" : "Aktif Yap";

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


            $topicData = '<tr>
                                <td data-file-id="' . $value['id'] . '">
                                    <a href="./alt-konu-detay/' . $value['slug'] . '" class="text-gray-600 text-hover-primary mb-1"> ' . $value['name'] .  '</a>
                                </td>
                                <td>
                                    ' . $value['lessonName'] . '</a>
                                </td>
                                <td>' . $value['className'] . '</td>
                                <td>' . $value['topicName'] . '</td>
                                <td>' . $aktifYazi . '</td>
                                <td class="pe-0 text-end">
                                    <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">İşlemler
                                        <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                                    <!--begin::Menu-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                        data-kt-menu="true">
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="./alt-konu-detay/' . $value['slug'] . '" class="menu-link px-3">Görüntüle</a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        ' . $passiveButton . '
                                        <!--end::Menu item-->
                                    </div>
                                    <!--end::Menu-->
                                </td>
                            </tr>';
            echo $topicData;
        }
    }

    // Get Contents For Topic Details Page

    public function showContentsListForTopicDetail($slug)
    {

        $unitInfo = new Topics();

        $unitInfo = $unitInfo->getOneTopicDetailsAdmin($slug);

        $unitId = $unitInfo[0]['id'];

        $topicInfo = $this->getContentsByTopicIdWithDetails($unitId);

        foreach ($topicInfo as $key => $value) {


            if ($value['active'] == 1) {
                $aktifArama = 'data-filter="Aktif"';
                $aktifYazi = '<span class="badge badge-light-success">Aktif</span>';
            } else {
                $aktifArama = 'data-filter="Passive"';
                $aktifYazi = '<span class="badge badge-light-danger">Pasif</span>';
            }

            $alter_button = $value['active'] ? "Pasif Yap" : "Aktif Yap";

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

            $topicData = '<tr>
                                <td data-file-id="' . $value['id'] . '">
                                    <a href="./icerik-detay/' . $value['slug'] . '" class="text-gray-600 text-hover-primary mb-1"> ' . $value['title'] .  '</a>
                                </td>
                                <td>
                                    ' . $value['lessonName'] . '</a>
                                </td>
                                <td>' . $value['className'] . '</td>
                                <td>' . $value['topicName'] . '</td>
                                <td>' . $aktifYazi . '</td>
                                <td class="pe-0 text-end">
                                    <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">İşlemler
                                        <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                                    <!--begin::Menu-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                        data-kt-menu="true">
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="./icerik-guncelle/' . $value['slug'] . '" class="menu-link px-3">Güncelle</a>
                                        </div>
                                        <div class="menu-item px-3">
                                            <a href="./icerik-detay/' . $value['slug'] . '" class="menu-link px-3">Görüntüle</a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        ' . $passiveButton . '
                                        <!--end::Menu item-->
                                    </div>
                                    <!--end::Menu-->
                                </td>
                            </tr>';
            echo $topicData;
        }
    }

    // Get Contents For Sub Topic Details Page

    public function showContentsListForSubTopicDetail($slug)
    {

        $unitInfo = new SubTopics();

        $unitInfo = $unitInfo->getOneSubTopicDetailsAdmin($slug);

        $unitId = $unitInfo[0]['id'];

        $topicInfo = $this->getContentsByContentIdWithDetails($unitId);

        foreach ($topicInfo as $key => $value) {
            $topicData = '<tr>
                                <td>
                                    <a href="./icerik-detay/' . $value['slug'] . '" class="text-gray-600 text-hover-primary mb-1"> ' . $value['title'] .  '</a>
                                </td>
                                <td>
                                    ' . $value['lessonName'] . '</a>
                                </td>
                                <td>' . $value['className'] . '</td>
                                <td>' . $value['topicName'] . '</td>
                                <td class="pe-0 text-end">
                                    <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">İşlemler
                                        <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                                    <!--begin::Menu-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                        data-kt-menu="true">
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="./icerik-detay/' . $value['slug'] . '" class="menu-link px-3">Görüntüle</a>
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
                            </tr>';
            echo $topicData;
        }
    }
}

class ShowTests extends Tests
{

    // Get Test List

    public function getTestList()
    {

        $dateFormat = new DateFormat();

        $topicInfo = $this->getTestsList();

        foreach ($topicInfo as $key => $value) {

            $topicList = '
                    <tr>
                        <td>
                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="1" />
                            </div>
                        </td>
                        <td>
                            <a href="./test-detay/' . $value['subTopicSlug'] . '" class="text-gray-800 text-hover-primary mb-1">' . $value['subTopicName'] . '</a>
                        </td>
                        <td>
                            ' . $value['topicName'] . '
                        </td>
                        <td>
                            ' . $value['unitName'] . '
                        </td>
                        <td>
                            ' . $value['lessonName'] . '
                        </td>
                        <td>
                            ' . $value['className'] . '
                        </td>
                        <td>
                            ' . $dateFormat->changeDate($value['testLastDay']) . '
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
                                    <a href="./test-detay/' . $value['subTopicSlug'] . '" class="menu-link px-3">Görüntüle</a>
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
            echo $topicList;
        }
    }

    // Get SubTopics Student

    public function getSubTopicsListStudent()
    {
        $dateFormat = new DateFormat();

        $link = "$_SERVER[REQUEST_URI]";

        $active_slug = htmlspecialchars(basename($link, ".php"));

        $unitInfo = $this->showSubTopicsStudent($active_slug);

        if ($unitInfo == NULL) {

            $testList = '
                        <!--begin::Col-->
                            <div class="col-md-12 d-flex flex-center">
                                <i class="fa-regular fa-face-frown-open text-danger fs-4x"></i>
                                
                            </div>

                            <div class="text-center mt-2">
                                <h4 class="fs-2hx text-gray-900 mb-5">Konu Mevcut Değil!</h4>
                            </div>

                        <!--end::Col-->
                        ';
            echo $testList;
        } else {

            foreach ($unitInfo as $key => $value) {
                $testText = "";
                $unclickable = "";
                if ($value['is_test'] == 1) {
                    $testSolved = $this->isSolved($value['topicID']);
                    if (!empty($testSolved)) {
                        $testText = '<span class="fw-semibold fs-5 text-success mb-5">Bu testi ' . $dateFormat->changeDate($testSolved['created_at']) . ' tarihinde çözdünüz!</span>';
                        $unclickable = "pe-none";
                    }
                }
                if ($value['is_question'] == 1) {
                    $testSolved = $this->isSolvedQuestion($value['topicID']);
                    if (!empty($testSolved)) {
                        $testText = '<span class="fw-semibold fs-5 text-success mb-5">Bu testi ' . $dateFormat->changeDate($testSolved['created_at']) . ' tarihinde çözdünüz!</span>';
                        $unclickable = "pe-none";
                    }
                }

                $testList = '
                            <!--begin::Col-->
                            <div class="col-md-6 col-lg-6 col-xl-6">
                                <!--begin::Publications post-->
                                <div class="card-xl-stretch me-md-6">
                                    <!--begin::Overlay-->
                                    <a class="d-block ' . $unclickable . ' overlay mb-4" href="alt-konu/' . $value['topicSlug'] . '">
                                        <!--begin::Image-->
                                        <div class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-175px" style="background-image:url(\'assets/media/topics/' . $value['topicImage'] . '\')"></div>
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
                                        <a href="alt-konu/' . $value['topicSlug'] . '" class="fs-4 text-gray-900 fw-bold text-hover-primary text-gray-900 ' . $unclickable . ' lh-base">' . $value['topicName'] . '</a>
                                        <!--end::Title-->
                                        <!--begin::Text-->
                                        <div class="fw-semibold fs-5 text-gray-600 text-gray-900 mt-3 mb-5">' . $value['topicShortDesc'] . '<br>' . $testText . '</div>
                                        <!--end::Text-->
                                    </div>
                                    <!--end::Body-->
                                </div>
                                <!--end::Publications post-->
                            </div>
                            <!--end::Col-->
                    ';
                echo $testList;
            }
        }
    }

    // Get Test Image For Students

    public function getHeaderImageStu()
    {
        $link = "$_SERVER[REQUEST_URI]";

        $active_slug = htmlspecialchars(basename($link, ".php"));

        $unitInfo = $this->getOneSubTopic($active_slug);

        foreach ($unitInfo as $key => $value) {

            $testList = '
                    <div class="position-relative mb-17">
                        <!--begin::Overlay-->
                        <div class="overlay overlay-show">
                            <!--begin::Image-->
                            <div class="bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-250px" style="background-image:url(\'assets/media/topics/' . $value['image'] . '\')"></div>
                            <!--end::Image-->
                            <!--begin::layer-->
                            <div class="overlay-layer rounded bg-black" style="opacity: 0.4"></div>
                            <!--end::layer-->
                        </div>
                        <!--end::Overlay-->
                        <!--begin::Heading-->
                        <div class="position-absolute text-white mb-8 ms-10 bottom-0">
                            <!--begin::Title-->
                            <h3 class="text-white fs-2qx fw-bold mb-3 m">' . $value['short_desc'] . '</h3>
                            <!--end::Title-->
                            <!--begin::Text-->
                            <!--<div class="fs-5 fw-semibold">You sit down. You stare at your screen. The cursor blinks.</div>-->
                            <!--end::Text-->
                        </div>
                        <!--end::Heading-->
                    </div>
                ';
            echo $testList;
        }
    }

    // Show Topic For Students

    public function showTopicStudent()
    {
        $dateFormat = new DateFormat();

        // $videoName = new getVideo();

        $link = "$_SERVER[REQUEST_URI]";

        $active_slug = htmlspecialchars(basename($link, ".php"));

        $unitInfo = $this->getOneSubTopic($active_slug);

        foreach ($unitInfo as $key => $value) {

            if ($value['is_test'] == 0 and $value['is_question'] == 0) {

                //$youtubeID = $this->getYouTubeVideoId($value['video_url']);
                $youtubeID = $value['video_url'];
                //$videoName->getVideoFrom($value['video_url']);

                $testList = '
                    <!--begin::Description-->
                    <div class="m-0">
                        <!--begin::Title-->
                        <h4 class="fs-1 text-gray-800 w-bolder mb-6">Konu İçeriği</h4>
                        <!--end::Title-->
                        <!--begin::Text-->
                        <p class="fw-semibold fs-4 text-gray-600 mb-2">' . $value['content'] . '</p>
                        <!--end::Text-->
                        <!--begin::Title-->
                        <h4 class="fs-1 text-gray-800 w-bolder mb-6 mt-16">Video İçeriği</h4>
                        <!--end::Title-->
                        <!--begin::Text-->
                        <p class="fw-semibold fs-4 text-gray-600 mb-2">
                            <video id="my-video" controls width="100%" height="640px">
                                <source src="../lineup/api/video/get.php" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </p>
                        <!--end::Text-->
                    </div>
                ';
            } elseif ($value['is_test'] == 1) {
                $testSolved = $this->isSolved($value['id']);
                if (!empty($testSolved)) {
                    $testList = "Bu testi " . $dateFormat->changeDate($testSolved['created_at']) . " tarihinde çözdünüz!";
                } else {
                    $testList = '<form class="form" action="#" id="kt_modal_add_customer_form" data-kt-redirect="konular">';
                    $test = $this->getOneTest($value['id']);
                    foreach ($test as $key => $testValue) {
                        $testQuestions = explode(":/;", $testValue['questions']);
                        $testAnswers = explode(":/;", $testValue['answers']);
                        $numberOfQuestions = count($testQuestions);
                        for ($i = 0; $i < $numberOfQuestions; $i++) {
                            if ($i % 2 == 1) {
                                $className = "";
                            } else {
                                $className = "bg-light";
                            }
                            $testOptions = explode("*-*", $testAnswers[$i]);
                            $numberOfOptions = count($testOptions);

                            $optionsList = "";

                            if ($i == 0) {
                                $testList .= '<input type="hidden" name="test_id" value="' . $testValue['id'] . '">';
                            }

                            for ($x = 0; $x < $numberOfOptions; $x++) {
                                $optionsList .= '
                                            <div class="col-md-3 mb-3">
                                                <label>
                                                    <input class="form-check-input ms-10 soru_' . $i + 1 . '" type="checkbox" name="testcevap[]" value="' . $testOptions[$x] . '"> ' . $testOptions[$x] . '
                                                    <input type="hidden" class="nextInput" name="testcevap[]" value="1">
                                                </label>
                                            </div>
                                        ';
                            }

                            $testList .= '
                                            <div class="col-md-12 ' . $className . ' p-3">
                                                <div data-kt-element="items">
                                                    <div class="" data-kt-element="item" data-soru-id="' . $i + 1 . '">
                                                        <!--begin::Input group-->
                                                        <div class="fv-row mb-2">
                                                            <!--begin::Label-->
                                                            <label class="required fs-6 fw-semibold mb-2">Soru ' . $i + 1 . '</label>
                                                            <!--end::Label-->
                                                        </div>
                                                        <div class="fv-row mb-6">
                                                            <!--begin::Question-->
                                                            ' . $testQuestions[$i] . '
                                                            <!--end::Question-->
                                                        </div>
                                                        <!--end::Input group-->

                                                        <!--begin::Input group-->
                                                        <div class="fv-row row">
                                                            ' . $optionsList . '
                                                        </div>
                                                        <!--end::Input group-->
                                                    </div>
                                                </div>
                                            </div>
                                        ';
                        }
                    }

                    $testList .= '<!--begin::Button-->
                                <button type="submit" id="kt_modal_add_customer_submit" class="btn btn-primary btn-sm mt-6">
                                    <span class="indicator-label">Cevapları Gönder</span>
                                    <span class="indicator-progress">Lütfen Bekleyin...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                                <!--end::Button-->
                                </form>';
                }
            } elseif ($value['is_question'] == 1) {
                $testSolved = $this->isSolvedQuestion($value['id']);
                if (!empty($testSolved)) {
                    $testList = "Bu testi " . $dateFormat->changeDate($testSolved['created_at']) . " tarihinde çözdünüz!";
                } else {
                    $testList = '<form class="form" action="#" id="kt_modal_add_customer_form" data-kt-redirect="konular">';
                    $test = $this->getOneQuestion($value['id']);
                    foreach ($test as $key => $testValue) {
                        $testQuestions = explode(":/;", $testValue['questions']);
                        $testAnswers = explode(":/;", $testValue['answers']);
                        $numberOfQuestions = count($testQuestions);
                        for ($i = 0; $i < $numberOfQuestions; $i++) {
                            if ($i % 2 == 1) {
                                $className = "";
                            } else {
                                $className = "bg-light";
                            }
                            $testOptions = explode("*-*", $testAnswers[$i]);
                            $numberOfOptions = count($testOptions);

                            $optionsList = "";

                            if ($i == 0) {
                                $testList .= '<input type="hidden" name="question_id" value="' . $testValue['id'] . '">';
                            }

                            for ($x = 0; $x < $numberOfOptions; $x++) {
                                $optionsList .= '
                                            <div class="col-md-3 mb-3">
                                                <label>
                                                    <input class="form-check-input ms-10 soru_' . $i + 1 . '" type="checkbox" name="testcevap[]" value="' . $testOptions[$x] . '"> ' . $testOptions[$x] . '
                                                    <input type="hidden" class="nextInput" name="testcevap[]" value="1">
                                                </label>
                                            </div>
                                        ';
                            }

                            $testList .= '
                                            <div class="col-md-12 ' . $className . ' p-3">
                                                <div data-kt-element="items">
                                                    <div class="" data-kt-element="item" data-soru-id="' . $i + 1 . '">
                                                        <!--begin::Input group-->
                                                        <div class="fv-row mb-2">
                                                            <!--begin::Label-->
                                                            <label class="required fs-6 fw-semibold mb-2">Soru ' . $i + 1 . '</label>
                                                            <!--end::Label-->
                                                        </div>
                                                        <div class="fv-row mb-6">
                                                            <!--begin::Question-->
                                                            ' . $testQuestions[$i] . '
                                                            <!--end::Question-->
                                                        </div>
                                                        <!--end::Input group-->

                                                        <!--begin::Input group-->
                                                        <div class="fv-row row">
                                                            ' . $optionsList . '
                                                        </div>
                                                        <!--end::Input group-->
                                                    </div>
                                                </div>
                                            </div>
                                        ';
                        }
                    }

                    $testList .= '<!--begin::Button-->
                                <button type="submit" id="kt_modal_add_customer_submit" class="btn btn-primary btn-sm mt-6">
                                    <span class="indicator-label">Cevapları Gönder</span>
                                    <span class="indicator-progress">Lütfen Bekleyin...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                                <!--end::Button-->
                                </form>';
                }
            }

            echo $testList;
        }
    }

    // Show Topic

    public function showOneTopic($slug)
    {

        $topicInfo = $this->getOneTopic($slug);


        $topicList = '
                <div class="mb-3">
                    <h1 class="h3 d-inline align-middle">Böyle bir okul mevcut değil.</h1>
                </div>
        ';

        foreach ($topicInfo as $value) {

            $topicList = '
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
        echo $topicList;
    }

    // Update Topic

    public function updateOneTopic($slug)
    {

        $topicInfo = $this->getOneTopic($slug);

        foreach ($topicInfo as $value) {

            $topicList = '
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
        echo $topicList;
    }

    // List of Topic

    public function showTopicList()
    {

        $topicInfo = $this->getTests();

        $topicList = '';

        $divStart = '<div class="row"><div class="overflow-auto col-12 col-lg-2" style="max-height: 150px">';

        $divEnd = '</div></div>';

        foreach ($topicInfo as $value) {

            $parentId = $value['id'];

            $topicForms = '<div class="form-check">
                                            <input class="form-check-input" name="category[]" type="checkbox" value="' . $value['id'] . '" id="' . $value['slug'] . '">
                                                <label class="form-check-label" for="' . $value['slug'] . '">
                                                ' . $value['catName'] . '
                                                </label>
                                        </div>';



            $topicList .= $topicForms;
        }


        echo $divStart . $topicList . $divEnd;
    }

    // Get Test For Sub Topic List

    public function showTopicForSubTopicList($class, $lessons, $units)
    {

        $unitInfo = $this->getTestForSubTopicList($class, $lessons, $units);

        $units = array();

        foreach ($unitInfo as $key => $value) {

            $units[] = array("id" => $value["id"], "text" => $value["name"]);
        }


        echo json_encode($units);
    }
}

class ShowTestsStudents extends TestsforStudents
{

    // Get Test List

    public function getTestList($class_id)
    {

        $dateFormat = new DateFormat();

        $topicInfo = $this->getTestsList($class_id);

        foreach ($topicInfo as $key => $value) {

            $testSolved = $this->isSolved($value['topicID'], $_SESSION['id']);
            if (!empty($testSolved)) {
                $testText = $dateFormat->changeDate($testSolved['created_at']);
            } else {
                $testText = "Daha Çözmediniz";
            }

            $topicList = '
                    <tr>
                        <td>
                            <a href="./alt-konu/' . $value['subTopicSlug'] . '" class="text-gray-800 text-hover-primary mb-1">' . $value['subTopicName'] . '</a>
                        </td>
                        <td>
                            ' . $value['topicName'] . '
                        </td>
                        <td>
                            ' . $value['unitName'] . '
                        </td>
                        <td>
                            ' . $value['lessonName'] . '
                        </td>
                        <td>
                            ' . $value['className'] . '
                        </td>
                        <td>
                            ' . $dateFormat->changeDate($value['testLastDay']) . '
                        </td>
                        <td class="text-end">
                            ' . $testText . '
                        </td>
                    </tr>
                ';
            echo $topicList;
        }
    }

    // Get SubTopics Student

    public function getSubTopicsListStudent()
    {
        $dateFormat = new DateFormat();

        $link = "$_SERVER[REQUEST_URI]";

        $active_slug = htmlspecialchars(basename($link, ".php"));

        $unitInfo = $this->showSubTopicsStudent($active_slug);

        if ($unitInfo == NULL) {

            $testList = '
                        <!--begin::Col-->
                            <div class="col-md-12 d-flex flex-center">
                                <i class="fa-regular fa-face-frown-open text-danger fs-4x"></i>
                                
                            </div>

                            <div class="text-center mt-2">
                                <h4 class="fs-2hx text-gray-900 mb-5">Konu Mevcut Değil!</h4>
                            </div>

                        <!--end::Col-->
                        ';
            echo $testList;
        } else {

            foreach ($unitInfo as $key => $value) {
                $testText = "";
                $unclickable = "";
                if ($value['is_test'] == 1) {
                    $testSolved = $this->isSolved($value['topicID']);
                    if (!empty($testSolved)) {
                        $testText = '<span class="fw-semibold fs-5 text-success mb-5">Bu testi ' . $dateFormat->changeDate($testSolved['created_at']) . ' tarihinde çözdünüz!</span>';
                        $unclickable = "pe-none";
                    }
                }
                if ($value['is_question'] == 1) {
                    $testSolved = $this->isSolvedQuestion($value['topicID']);
                    if (!empty($testSolved)) {
                        $testText = '<span class="fw-semibold fs-5 text-success mb-5">Bu testi ' . $dateFormat->changeDate($testSolved['created_at']) . ' tarihinde çözdünüz!</span>';
                        $unclickable = "pe-none";
                    }
                }

                $testList = '
                            <!--begin::Col-->
                            <div class="col-md-6 col-lg-6 col-xl-6">
                                <!--begin::Publications post-->
                                <div class="card-xl-stretch me-md-6">
                                    <!--begin::Overlay-->
                                    <a class="d-block ' . $unclickable . ' overlay mb-4" href="alt-konu/' . $value['topicSlug'] . '">
                                        <!--begin::Image-->
                                        <div class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-175px" style="background-image:url(\'assets/media/topics/' . $value['topicImage'] . '\')"></div>
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
                                        <a href="alt-konu/' . $value['topicSlug'] . '" class="fs-4 text-gray-900 fw-bold text-hover-primary text-gray-900 ' . $unclickable . ' lh-base">' . $value['topicName'] . '</a>
                                        <!--end::Title-->
                                        <!--begin::Text-->
                                        <div class="fw-semibold fs-5 text-gray-600 text-gray-900 mt-3 mb-5">' . $value['topicShortDesc'] . '<br>' . $testText . '</div>
                                        <!--end::Text-->
                                    </div>
                                    <!--end::Body-->
                                </div>
                                <!--end::Publications post-->
                            </div>
                            <!--end::Col-->
                    ';
                echo $testList;
            }
        }
    }

    // Get Test Image For Students

    public function getHeaderImageStu()
    {
        $link = "$_SERVER[REQUEST_URI]";

        $active_slug = htmlspecialchars(basename($link, ".php"));

        $unitInfo = $this->getOneSubTopic($active_slug);

        foreach ($unitInfo as $key => $value) {

            $testList = '
                    <div class="position-relative mb-17">
                        <!--begin::Overlay-->
                        <div class="overlay overlay-show">
                            <!--begin::Image-->
                            <div class="bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-250px" style="background-image:url(\'assets/media/topics/' . $value['image'] . '\')"></div>
                            <!--end::Image-->
                            <!--begin::layer-->
                            <div class="overlay-layer rounded bg-black" style="opacity: 0.4"></div>
                            <!--end::layer-->
                        </div>
                        <!--end::Overlay-->
                        <!--begin::Heading-->
                        <div class="position-absolute text-white mb-8 ms-10 bottom-0">
                            <!--begin::Title-->
                            <h3 class="text-white fs-2qx fw-bold mb-3 m">' . $value['short_desc'] . '</h3>
                            <!--end::Title-->
                            <!--begin::Text-->
                            <!--<div class="fs-5 fw-semibold">You sit down. You stare at your screen. The cursor blinks.</div>-->
                            <!--end::Text-->
                        </div>
                        <!--end::Heading-->
                    </div>
                ';
            echo $testList;
        }
    }

    // Show Topic For Students

    public function showTopicStudent()
    {
        $dateFormat = new DateFormat();

        // $videoName = new getVideo();

        $link = "$_SERVER[REQUEST_URI]";

        $active_slug = htmlspecialchars(basename($link, ".php"));

        $unitInfo = $this->getOneSubTopic($active_slug);

        foreach ($unitInfo as $key => $value) {

            if ($value['is_test'] == 0 and $value['is_question'] == 0) {

                //$youtubeID = $this->getYouTubeVideoId($value['video_url']);
                $youtubeID = $value['video_url'];
                //$videoName->getVideoFrom($value['video_url']);

                $testList = '
                    <!--begin::Description-->
                    <div class="m-0">
                        <!--begin::Title-->
                        <h4 class="fs-1 text-gray-800 w-bolder mb-6">Konu İçeriği</h4>
                        <!--end::Title-->
                        <!--begin::Text-->
                        <p class="fw-semibold fs-4 text-gray-600 mb-2">' . $value['content'] . '</p>
                        <!--end::Text-->
                        <!--begin::Title-->
                        <h4 class="fs-1 text-gray-800 w-bolder mb-6 mt-16">Video İçeriği</h4>
                        <!--end::Title-->
                        <!--begin::Text-->
                        <p class="fw-semibold fs-4 text-gray-600 mb-2">
                            <video id="my-video" controls width="100%" height="640px">
                                <source src="../lineup/api/video/get.php" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </p>
                        <!--end::Text-->
                    </div>
                ';
            } elseif ($value['is_test'] == 1) {
                $testSolved = $this->isSolved($value['id']);
                if (!empty($testSolved)) {
                    $testList = "Bu testi " . $dateFormat->changeDate($testSolved['created_at']) . " tarihinde çözdünüz!";
                } else {
                    $testList = '<form class="form" action="#" id="kt_modal_add_customer_form" data-kt-redirect="konular">';
                    $test = $this->getOneTest($value['id']);
                    foreach ($test as $key => $testValue) {
                        $testQuestions = explode(":/;", $testValue['questions']);
                        $testAnswers = explode(":/;", $testValue['answers']);
                        $numberOfQuestions = count($testQuestions);
                        for ($i = 0; $i < $numberOfQuestions; $i++) {
                            if ($i % 2 == 1) {
                                $className = "";
                            } else {
                                $className = "bg-light";
                            }
                            $testOptions = explode("*-*", $testAnswers[$i]);
                            $numberOfOptions = count($testOptions);

                            $optionsList = "";

                            if ($i == 0) {
                                $testList .= '<input type="hidden" name="test_id" value="' . $testValue['id'] . '">';
                            }

                            for ($x = 0; $x < $numberOfOptions; $x++) {
                                $optionsList .= '
                                            <div class="col-md-3 mb-3">
                                                <label>
                                                    <input class="form-check-input ms-10 soru_' . $i + 1 . '" type="checkbox" name="testcevap[]" value="' . $testOptions[$x] . '"> ' . $testOptions[$x] . '
                                                    <input type="hidden" class="nextInput" name="testcevap[]" value="1">
                                                </label>
                                            </div>
                                        ';
                            }

                            $testList .= '
                                            <div class="col-md-12 ' . $className . ' p-3">
                                                <div data-kt-element="items">
                                                    <div class="" data-kt-element="item" data-soru-id="' . $i + 1 . '">
                                                        <!--begin::Input group-->
                                                        <div class="fv-row mb-2">
                                                            <!--begin::Label-->
                                                            <label class="required fs-6 fw-semibold mb-2">Soru ' . $i + 1 . '</label>
                                                            <!--end::Label-->
                                                        </div>
                                                        <div class="fv-row mb-6">
                                                            <!--begin::Question-->
                                                            ' . $testQuestions[$i] . '
                                                            <!--end::Question-->
                                                        </div>
                                                        <!--end::Input group-->

                                                        <!--begin::Input group-->
                                                        <div class="fv-row row">
                                                            ' . $optionsList . '
                                                        </div>
                                                        <!--end::Input group-->
                                                    </div>
                                                </div>
                                            </div>
                                        ';
                        }
                    }

                    $testList .= '<!--begin::Button-->
                                <button type="submit" id="kt_modal_add_customer_submit" class="btn btn-primary btn-sm mt-6">
                                    <span class="indicator-label">Cevapları Gönder</span>
                                    <span class="indicator-progress">Lütfen Bekleyin...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                                <!--end::Button-->
                                </form>';
                }
            } elseif ($value['is_question'] == 1) {
                $testSolved = $this->isSolvedQuestion($value['id']);
                if (!empty($testSolved)) {
                    $testList = "Bu testi " . $dateFormat->changeDate($testSolved['created_at']) . " tarihinde çözdünüz!";
                } else {
                    $testList = '<form class="form" action="#" id="kt_modal_add_customer_form" data-kt-redirect="konular">';
                    $test = $this->getOneQuestion($value['id']);
                    foreach ($test as $key => $testValue) {
                        $testQuestions = explode(":/;", $testValue['questions']);
                        $testAnswers = explode(":/;", $testValue['answers']);
                        $numberOfQuestions = count($testQuestions);
                        for ($i = 0; $i < $numberOfQuestions; $i++) {
                            if ($i % 2 == 1) {
                                $className = "";
                            } else {
                                $className = "bg-light";
                            }
                            $testOptions = explode("*-*", $testAnswers[$i]);
                            $numberOfOptions = count($testOptions);

                            $optionsList = "";

                            if ($i == 0) {
                                $testList .= '<input type="hidden" name="question_id" value="' . $testValue['id'] . '">';
                            }

                            for ($x = 0; $x < $numberOfOptions; $x++) {
                                $optionsList .= '
                                            <div class="col-md-3 mb-3">
                                                <label>
                                                    <input class="form-check-input ms-10 soru_' . $i + 1 . '" type="checkbox" name="testcevap[]" value="' . $testOptions[$x] . '"> ' . $testOptions[$x] . '
                                                    <input type="hidden" class="nextInput" name="testcevap[]" value="1">
                                                </label>
                                            </div>
                                        ';
                            }

                            $testList .= '
                                            <div class="col-md-12 ' . $className . ' p-3">
                                                <div data-kt-element="items">
                                                    <div class="" data-kt-element="item" data-soru-id="' . $i + 1 . '">
                                                        <!--begin::Input group-->
                                                        <div class="fv-row mb-2">
                                                            <!--begin::Label-->
                                                            <label class="required fs-6 fw-semibold mb-2">Soru ' . $i + 1 . '</label>
                                                            <!--end::Label-->
                                                        </div>
                                                        <div class="fv-row mb-6">
                                                            <!--begin::Question-->
                                                            ' . $testQuestions[$i] . '
                                                            <!--end::Question-->
                                                        </div>
                                                        <!--end::Input group-->

                                                        <!--begin::Input group-->
                                                        <div class="fv-row row">
                                                            ' . $optionsList . '
                                                        </div>
                                                        <!--end::Input group-->
                                                    </div>
                                                </div>
                                            </div>
                                        ';
                        }
                    }

                    $testList .= '<!--begin::Button-->
                                <button type="submit" id="kt_modal_add_customer_submit" class="btn btn-primary btn-sm mt-6">
                                    <span class="indicator-label">Cevapları Gönder</span>
                                    <span class="indicator-progress">Lütfen Bekleyin...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                                <!--end::Button-->
                                </form>';
                }
            }

            echo $testList;
        }
    }

    // Show Topic

    public function showOneTopic($slug)
    {

        $topicInfo = $this->getOneTopic($slug);


        $topicList = '
                <div class="mb-3">
                    <h1 class="h3 d-inline align-middle">Böyle bir okul mevcut değil.</h1>
                </div>
        ';

        foreach ($topicInfo as $value) {

            $topicList = '
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
        echo $topicList;
    }

    // Update Topic

    public function updateOneTopic($slug)
    {

        $topicInfo = $this->getOneTopic($slug);

        foreach ($topicInfo as $value) {

            $topicList = '
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
        echo $topicList;
    }

    // List of Topic

    public function showTopicList()
    {

        $topicInfo = $this->getTests();

        $topicList = '';

        $divStart = '<div class="row"><div class="overflow-auto col-12 col-lg-2" style="max-height: 150px">';

        $divEnd = '</div></div>';

        foreach ($topicInfo as $value) {

            $parentId = $value['id'];

            $topicForms = '<div class="form-check">
                                            <input class="form-check-input" name="category[]" type="checkbox" value="' . $value['id'] . '" id="' . $value['slug'] . '">
                                                <label class="form-check-label" for="' . $value['slug'] . '">
                                                ' . $value['catName'] . '
                                                </label>
                                        </div>';



            $topicList .= $topicForms;
        }


        echo $divStart . $topicList . $divEnd;
    }

    // Get Test For Sub Topic List

    public function showTopicForSubTopicList($class, $lessons, $units)
    {

        $unitInfo = $this->getTestForSubTopicList($class, $lessons, $units);

        $units = array();

        foreach ($unitInfo as $key => $value) {

            $units[] = array("id" => $value["id"], "text" => $value["name"]);
        }


        echo json_encode($units);
    }
}

class ShowOneTest extends Tests
{

    public function showTestDetail($slug)
    {

        $dateFormat = new DateFormat();

        $studentList = new Student();

        $resultPercentage = new TestContr();

        $testInfo = $this->getOneTest($slug);

        if (empty($testInfo)) {
            echo "Böyle bir test yok!";
        } else {

            $users = "";

            $studentLists = $studentList->getStudentsForTests($testInfo['class_id']);

            $userNumber = count($studentLists);

            $correctAnswers = $testInfo['correct'];

            $rightAnswers = explode(":/;", $correctAnswers);

            $queAns = $resultPercentage->controlAnswer($testInfo['questions'], $testInfo['answers'], $testInfo['correct']);

            $details = '
                <b>Soru Sayısı: </b>' . count($rightAnswers) . ' <br><br> ' .
                $queAns;

            foreach ($studentLists as $key => $userValue) {

                $solvedTests = $this->isSolved($testInfo['id'], $userValue['id']);

                if (empty($solvedTests)) {
                    $testResult = "Daha Çözmedi";
                    $button = "style='pointer-events: none;'";
                } else {
                    $testResult = $resultPercentage->testResult($correctAnswers, $solvedTests['answers']);
                    $testResult = $testResult['basari_orani'];
                    $button = "";
                }

                $users .= '
                    <tr>
                        <td>' . $userValue['id'] . '</td>
                        <td class="d-flex align-items-center">
                            <!--begin:: Avatar -->
                            <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                <a href="test-detay-ogrenci/' . $slug . '?id=' . $userValue['id'] . '" ' . $button . '>
                                    <div class="symbol-label">
                                        <img src="assets/media/profile/' . $userValue['photo'] . '" alt="' . $userValue['name'] . ' ' . $userValue['surname'] . '" class="w-100" />
                                    </div>
                                </a>
                            </div>
                            <!--end::Avatar-->
                            <!--begin::User details-->
                            <div class="d-flex flex-column">
                                <a href="test-detay-ogrenci/' . $slug . '?id=' . $userValue['id'] . '" class="text-gray-800 text-hover-primary mb-1" ' . $button . '>' . $userValue['name'] . ' ' . $userValue['surname'] . '</a>
                            </div>
                            <!--begin::User details-->
                        </td>
                        <td>' . $testResult . '</td>
                        <td class="text-end">
                            <a href="test-detay-ogrenci/' . $slug . '?id=' . $userValue['id'] . '" class="btn btn-sm btn-light btn-active-light-primary" data-kt-menu-placement="bottom-end" ' . $button . '>Görüntüle
                                <i class="fa-regular fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                ';
            }

            $testList = '
                <div class="d-flex flex-column flex-lg-row">
                    <!--begin::Sidebar-->
                    <div class="flex-column flex-lg-row-auto w-100 w-lg-200px w-xl-300px mb-10">
                        <!--begin::Card-->
                        <div class="card card-flush">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h2 class="mb-0">Detaylar</h2>
                                </div>
                                <!--end::Card title-->
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                <!--begin::Details-->
                                <div class="d-flex flex-column text-gray-600">
                                    ' . $details . '
                                </div>
                                <!--end::Details-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Sidebar-->
                    <!--begin::Content-->
                    <div class="flex-lg-row-fluid ms-lg-10">
                        <!--begin::Card-->
                        <div class="card card-flush mb-6 mb-xl-9">
                            <!--begin::Card header-->
                            <div class="card-header pt-5">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h2 class="d-flex align-items-center">Öğrenciler
                                        <span class="text-gray-600 fs-6 ms-1">(' . $userNumber . ')</span>
                                    </h2>
                                </div>
                                <!--end::Card title-->
                                <!--begin::Card toolbar-->
                                <div class="card-toolbar">
                                    <!--begin::Search-->
                                    <div class="d-flex align-items-center position-relative my-1" data-kt-view-roles-table-toolbar="base">
                                        <i class="ki-duotone ki-magnifier fs-1 position-absolute ms-6">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <input type="text" data-kt-roles-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="Kullanıcı Ara" />
                                    </div>
                                    <!--end::Search-->
                                    <!--begin::Group actions-->
                                    <div class="d-flex justify-content-end align-items-center d-none" data-kt-view-roles-table-toolbar="selected">
                                        <div class="fw-bold me-5">
                                            <span class="me-2" data-kt-view-roles-table-select="selected_count"></span>Selected
                                        </div>
                                        <button type="button" class="btn btn-danger btn-sm" data-kt-view-roles-table-select="delete_selected">Delete Selected</button>
                                    </div>
                                    <!--end::Group actions-->
                                </div>
                                <!--end::Card toolbar-->
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                <!--begin::Table-->
                                <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0" id="kt_roles_view_table">
                                    <thead>
                                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                            <th class="min-w-50px">ID</th>
                                            <th class="min-w-150px">Kullanıcı</th>
                                            <th class="min-w-125px">Başarı Oranı</th>
                                            <th class="text-end min-w-100px">İşlemler</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-semibold text-gray-600">
                                        ' . $users . '
                                    </tbody>
                                </table>
                                <!--end::Table-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Content-->
                </div>
                ';
            echo $testList;
        }
    }

    public function showTestDetailForOne($slug, $studentid)
    {

        $dateFormat = new DateFormat();

        $studentList = new Student();

        $resultPercentage = new TestContr();

        $testInfo = $this->getOneTest($slug);

        if (empty($testInfo)) {
            echo "Böyle bir test yok!";
        } else {

            $correctAnswers = $testInfo['correct'];

            $solvedTests = $this->isSolved($testInfo['id'], $studentid);

            if (empty($solvedTests)) {
                $testResult = "Daha Çözmedi";
            } else {
                $testResult = $resultPercentage->testResult($correctAnswers, $solvedTests['answers']);
                $testTotal = $testResult['toplam_soru'];
                $testPercentage = $testResult['basari_orani'];
                $testCorrectNo = $testResult['dogru_sayisi'];
                $testWrongNo = $testTotal - $testCorrectNo;
            }

            $studentTestDetail = $resultPercentage->testDetailsForStudent($testInfo['questions'], $testInfo['answers'], $testInfo['correct'], $solvedTests['answers']);

            $studentInfo = $studentList->getOneStudent($studentid);

            $userInfo = '
            <div class="d-flex flex-center flex-column py-5">
                <!--begin::Avatar-->
                <div class="symbol symbol-100px symbol-circle mb-7">
                    <img src="assets/media/profile/' . $studentInfo['userPhoto'] . '" alt="image" />
                </div>
                <!--end::Avatar-->
                <!--begin::Name-->
                <div class="fs-3 text-gray-800 text-hover-primary fw-bold mb-3">' . $studentInfo['userName'] . ' ' . $studentInfo['userSurname'] . '</div>
                <!--end::Name-->
                <!--begin::Position-->
                <div class="mb-9">
                    <!--begin::Badge-->
                    <div class="badge badge-lg badge-light-primary d-inline">' . $studentInfo['schoolName'] . '</div>
                    <!--begin::Badge-->
                </div>
                <!--end::Position-->
                <!--begin::Info-->
                <!--begin::Info heading-->
                <div class="fw-bold mb-3">"' . $testInfo['name'] . '" adlı test
                </div>
                <!--end::Info heading-->
                <div class="d-flex flex-wrap flex-center">
                    <!--begin::Stats-->
                    <div class="border border-gray-300 border-dashed rounded py-3 px-3 mb-3">
                        <div class="fs-4 fw-bold text-gray-700">
                            <span class="w-75px">' . $testTotal . '</span>
                            <i class="ki-duotone ki-question-2 fs-3">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </div>
                        <div class="fw-semibold text-muted">Toplam Soru</div>
                    </div>
                    <!--end::Stats-->
                    <!--begin::Stats-->
                    <div class="border border-gray-300 border-dashed rounded py-3 px-3 mx-4 mb-3">
                        <div class="fs-4 fw-bold text-gray-700">
                            <span class="w-50px">' . $testCorrectNo . '</span>
                            <i class="ki-duotone ki-check fs-3 text-success"></i>
                        </div>
                        <div class="fw-semibold text-muted">Doğru</div>
                    </div>
                    <!--end::Stats-->
                    <!--begin::Stats-->
                    <div class="border border-gray-300 border-dashed rounded py-3 px-3 mb-3">
                        <div class="fs-4 fw-bold text-gray-700">
                            <span class="w-50px">' . $testWrongNo . '</span>
                            <i class="ki-duotone ki-cross fs-3 text-danger">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </div>
                        <div class="fw-semibold text-muted">Yanlış</div>
                    </div>
                    <!--end::Stats-->
                </div>
                <!--end::Info-->
            </div>
            ';

            $testList = '
                <div class="d-flex flex-column flex-lg-row">
                    <!--begin::Sidebar-->
                    <div class="flex-column flex-lg-row-auto w-lg-250px w-xl-350px mb-10">
                        <!--begin::Card-->
                        <div class="card mb-5 mb-xl-8">
                            <!--begin::Card body-->
                            <div class="card-body">
                                <!--begin::Summary-->
                                <!--begin::User Info-->
                                ' . $userInfo . '
                                <!--end::User Info-->
                                <!--end::Summary-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Sidebar-->
                    <!--begin::Content-->
                    <div class="flex-lg-row-fluid ms-lg-10">
                        <!--begin::Card-->
                        <div class="card card-flush mb-6 mb-xl-9">
                            <!--begin::Card header-->
                            <div class="card-header pt-5">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h2 class="d-flex align-items-center">Test Çözümü</h2>
                                </div>
                                <!--end::Card title-->
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                ' . $studentTestDetail . '
                                <div class="fs-3 text-gray-800 fw-bold mb-3"> Başarı Oranı:' . $testPercentage . ' </div>
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Content-->
                </div>
                ';
            echo $testList;
        }
    }
}
