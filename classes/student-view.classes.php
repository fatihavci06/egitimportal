<?php
include_once "dateformat.classes.php";


class ShowStudent extends Student
{

    // Get Class List For Search

    public function getClassList()
    {

        $schoolInfo = $this->getAllClasses();

        $classList = '';

        foreach ($schoolInfo as $key => $value) {

            $classList .= '
                    <!--begin::Option-->
                    <label class="form-check form-check-sm form-check-custom form-check-solid  mb-3 me-5">
                        <input class="form-check-input" type="radio" name="student_class" value="' . $value['slug'] . '" />
                        <span class="form-check-label text-gray-600">' . $value['name'] . '</span>
                    </label>
                    <!--end::Option-->
                ';
        }
        echo $classList;
    }

    // Get Class Dropdown List For Search

    public function getClassDropdownList()
    {

        $schoolInfo = $this->getAllClasses();

        $classList = '';

        foreach ($schoolInfo as $key => $value) {

            $classList .= '<option value="' . $value['slug'] . '">' . $value['name'] . '</option>';
        }
        echo $classList;
    }

    // Get Class Dropdown List For Search

    public function getClassDropdownListWithId()
    {

        $schoolInfo = $this->getAllClasses();

        $classList = '';

        foreach ($schoolInfo as $key => $value) {

            if ($value['id'] == 10 || $value['id'] == 11 || $value['id'] == 12) {
                continue;
            }

            $classList .= '<option value="' . $value['id'] . '">' . $value['name'] . '</option>';
        }
        echo $classList;
    }

    // Get Unit Dropdown List For Search

    public function getUnitsDropdownListWithId()
    {

        $schoolInfo = $this->getAllUnits();

        $classList = '';

        foreach ($schoolInfo as $key => $value) {

            $classList .= '<option value="' . $value['id'] . '">' . $value['name'] . '</option>';
        }
        echo $classList;
    }

    // Get Topic Dropdown List For Search

    public function getTopicsDropdownListWithId()
    {

        $schoolInfo = $this->getAllTopics();

        $classList = '';

        foreach ($schoolInfo as $key => $value) {

            $classList .= '<option value="' . $value['id'] . '">' . $value['name'] . '</option>';
        }
        echo $classList;
    }

    // Get Lesson List For Search

    public function getLessonList()
    {

        $schoolInfo = $this->getAllLessons();

        $lessonList = '';

        foreach ($schoolInfo as $key => $value) {

            $lessonList .= '<option value="' . $value['name'] . '">' . $value['name'] . '</option>';
        }
        echo $lessonList;
    }

    // Get Lesson List For Search

    public function getLessonListwithId()
    {

        $schoolInfo = $this->getAllLessons();

        $lessonList = '';

        foreach ($schoolInfo as $key => $value) {

            $lessonList .= '<option value="' . $value['id'] . '">' . $value['name'] . '</option>';
        }
        echo $lessonList;
    }

    // Get Student List

    public function getStudentList()
    {

        $schoolInfo = $this->getStudentsList();


        $dateFormat = new DateFormat();
        $studentList = '';

        foreach ($schoolInfo as $key => $value) {

            $sinifArama = 'data-filter="' . $value['classSlug'] . '"';

            if ($value['userActive'] == 1) {
                $aktifArama = 'data-filter="Aktif"';
                $aktifYazi = '<span class="badge badge-light-success">Aktif</span>';
            } else {
                $aktifArama = 'data-filter="Passive"';
                $aktifYazi = '<span class="badge badge-light-danger">Pasif</span>';
            }

            $alter_button = $value['active'] ? "Pasif Yap" : "Aktif Yap";

            $passiveButton = "";

            if ($_SESSION['role'] != 4) {
                $passiveButton = '
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-customer-table-filter="delete_row">' . $alter_button . '</a>
                                </div>
                                <!--end::Menu item-->';
            }

            $studentList .= '
                    <tr>
                        <td>
                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="1" />
                            </div>
                        </td>
                        <td ' . $aktifArama . '>
                            <div class="cursor-pointer symbol symbol-90px symbol-lg-90px"><img src="assets/media/profile/' . $value['photo'] . '"></div>
                        </td>
                        <td>
                            <a href="./ogrenci-detay/' . $value['username'] . '" class="text-gray-800 text-hover-primary mb-1">' . $value['name'] . ' ' . $value['surname'] . '</a>
                        </td>
                        <td>
                            <a href="mailto:' . $value['email'] . '" class="text-gray-800 text-hover-primary mb-1">' . $value['email'] . '</a>
                        </td>
                        <td ' . $sinifArama . '>
                            ' . $value['className'] . '
                        </td>
                        <td data-filter="' . $value['schoolName'] . '">
                            ' . $value['schoolName'] . '
                        </td>
                        <td  data-order="' . $dateFormat->forDB($value['subscribed_end']) . '">' . $dateFormat->changeDate($value['subscribed_end']) . '</td>
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
                                    <a href="./ogrenci-detay/' . $value['username'] . '" class="menu-link px-3">Görüntüle</a>
                                </div>
                                <!--end::Menu item-->
                                ' . $passiveButton . '
                            </div>
                            <!--end::Menu-->
                        </td>
                    </tr>
                ';
        }
        echo $studentList;
    }
    public function getStudentsProgressList()
    {

        $schoolInfo = $this->getStudentsList();


        $dateFormat = new DateFormat();
        $studentList = '';
        require_once "content-tracker.classes.php";
        require_once "grade-result.classes.php";

        $contentObj = new ContentTracker();
        $gradeObj = new GradeResult();


        foreach ($schoolInfo as $key => $value) {

            $sinifArama = 'data-filter="' . $value['classSlug'] . '"';

            if ($value['userActive'] == 1) {
                $aktifArama = 'data-filter="Aktif"';
                $aktifYazi = '<span class="badge badge-light-success">Aktif</span>';
            } else {
                $aktifArama = 'data-filter="Passive"';
                $aktifYazi = '<span class="badge badge-light-danger">Pasif</span>';
            }

            $alter_button = $value['active'] ? "Pasif Yap" : "Aktif Yap";

            $percentage = $contentObj->getSchoolContentAnalyticsOverall($value['id']);
            $percentageW = ($percentage == null) ? 0 : $percentage;
            $percentageT = ($percentage == null) ? '-' : $percentage;

            $score = $gradeObj->getGradeOverall($value['id'], );
            $scoreW = ($score == null) ? 0 : $score;
            $scoreT = ($score == null) ? '-' : $score;

            $studentList .= '
                    <tr>
                        <td ' . $aktifArama . '>
                            <a href="./ogrenci-detay/' . $value['username'] . '" class="text-gray-800 text-hover-primary mb-1">' . $value['name'] . ' ' . $value['surname'] . '</a>
                        </td>
                        <td ' . $sinifArama . '>
                            ' . $value['className'] . '
                        </td>
                        <td data-filter="' . $value['schoolName'] . '">
                            ' . $value['schoolName'] . '
                        </td>
                        <td class="text-end">
                            <span class="fw-bold fs-6">' . $percentage . '%</span>
                        </td>
                        <td class="text-end">
                            <span class="fw-bold fs-6">' . $scoreT . '%</span>
                        </td>
                        <td class="text-center">
                            <a href="./icerik-ilerleme-takip/' . $value['id'] . '" class="text-gray-800 text-hover-primary mb-1"> 
                            <i class="ki-duotone ki-chart-line text-gray-900 fs-2tx">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i></a>
                        </td>
                    </tr>
                ';
        }
        echo $studentList;
    }
    public function getStudentsProgressListForParent($userID)
    {

        $schoolInfo = $this->getStudentsListForParent($userID);

        // var_dump($schoolInfo);
        // die();

        $dateFormat = new DateFormat();
        $studentList = '';
        require_once "content-tracker.classes.php";
        require_once "grade-result.classes.php";

        $contentObj = new ContentTracker();
        $gradeObj = new GradeResult();


        foreach ($schoolInfo as $key => $value) {
            $sinifArama = 'data-filter="' . $value['classSlug'] . '"';

            if ($value['userActive'] == 1) {
                $aktifArama = 'data-filter="Aktif"';
                $aktifYazi = '<span class="badge badge-light-success">Aktif</span>';
            } else {
                $aktifArama = 'data-filter="Passive"';
                $aktifYazi = '<span class="badge badge-light-danger">Pasif</span>';
            }

            $alter_button = $value['active'] ? "Pasif Yap" : "Aktif Yap";

            $percentage = $contentObj->getSchoolContentAnalyticsOverall($value['id']);
            $percentageW = ($percentage == null) ? 0 : $percentage;
            $percentageT = ($percentage == null) ? '-' : $percentage;

            $score = $gradeObj->getGradeOverall($value['id'], );
            $scoreW = ($score == null) ? 0 : $score;
            $scoreT = ($score == null) ? '-' : $score;

            $studentList .= '
                    <tr>
                        <td ' . $aktifArama . '>
                            <a href="./ogrenci-detay/' . $value['username'] . '" class="text-gray-800 text-hover-primary mb-1">' . $value['name'] . ' ' . $value['surname'] . '</a>
                        </td>
                        <td ' . $sinifArama . '>
                            ' . $value['className'] . '
                        </td>
                        <td data-filter="' . $value['schoolName'] . '">
                            ' . $value['schoolName'] . '
                        </td>
                        <td>
                            <span class="fw-bold fs-6">' . $percentageT . '%</span>
                        </td>
                        <td>
                            <span class="fw-bold fs-6">' . $scoreT . '%</span>
                        </td>

                    </tr>
                ';
        }
        echo $studentList;
    }
    public function getStudentProgress($userId, $classId)
    {
        $schoolInfo = $this->getStudentsList();

        $dateFormat = new DateFormat();
        $studentList = '';

        require_once "content-tracker.classes.php";
        $contentObj = new ContentTracker();

        $items = $contentObj->getSchoolContentAnalyticsListByUserId($userId, $classId);

        //     echo "<pre>";
        //     var_dump([$items]);
        //     echo "</pre>";
        // die();

        foreach ($items as $key => $value) {


            $placeholder = "placeholder";

            $topic_name = $value['topic_name'] ?? '-';
            $subtopic_name = $value['subtopic_name'] ?? '-';

            $content_visited = ($value['content_visited'] == 0) ? '<span class="badge badge-light-danger">Hayır</span>' : '<span class="badge badge-light-success">Evet</span>';

            $videos = ($value['total_videos'] == 0) ? '-' : $value['completed_videos'] . '/' . $value['total_videos'];
            $files = ($value['total_files'] == 0) ? '-' : '' . $value['downloaded_files'] . '/' . $value['total_files'] . '';
            $wordwalls = ($value['total_wordwalls'] == 0) ? '-' : '' . $value['viewed_wordwalls'] . '/' . $value['total_wordwalls'] . '';

            $studentList .= '
                    <tr>
                        <td>
                            <a href="./icerik-detay/' . $value['slug'] . '" class="text-gray-800 text-hover-primary mb-1">' . $value['title'] . '</a>
                        </td>
                        <td>
                            ' . $topic_name . '
                        </td>
                        <td>
                            ' . $subtopic_name . '
                        </td>

                        <td>
                            ' . $content_visited . '
                        </td>
                        <td>
                            <span class="fw-bold fs-6">' . $videos . '</span>
                        </td>
                        <td>
                            <span class="fw-bold fs-6">' . $files . '</span>
                        </td>
                        <td>
                            <span class="fw-bold fs-6">' . $wordwalls . '</span>
                        </td>
                    </tr>
                ';
        }
        echo $studentList;
    }
    // Get Waiting Money Transfer Student List

    public function getWaitingStudentList()
    {

        $schoolInfo = $this->getWaitingMoneyStudent();


        $dateFormat = new DateFormat();

        foreach ($schoolInfo as $key => $value) {

            $parentInfo = $this->getparentName($value['user_id']);

            $parentName = $parentInfo[0]['name'] . ' ' . $parentInfo[0]['surname'];

            $packInfo = $this->getPackName($value['pack_id']);

            $packName = $packInfo[0]['name'];

            $packClass = $packInfo[0]['class_id'];

            $classInfo = $this->getClassName($packClass);

            $className = $classInfo[0]['name'];

            $studentList = '
                    <tr>
                        <td>
                            ' . $value['name'] . ' ' . $value['surname'] . '
                        </td>
                        <td>
                            ' . $parentName . '
                        </td>
                        <td>
                            <a class="text-gray-800 text-hover-primary mb-1" href="tel:' . $value['telephone'] . '">' . $value['telephone'] . '</a>
                        </td>
                        <td>
                            <a class="text-gray-800 text-hover-primary mb-1" href="mailto:' . $value['email'] . '">' . $value['email'] . '</a>
                        </td>
                        <td>
                            ' . $value['identity_id'] . '
                        </td>
                        <td>
                            ' . $packName . '
                        </td>
                        <td class="text-center">' . $value['amount'] . ' ₺</td>
                        <td>
                            ' . $className . '
                        </td>
                        <td class="text-end">
                            <button id="approve" data-ek=' . $value['user_id'] . ' data-info=' . $value['id'] . '" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary approve"
                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Ödemeyi Onayla
                                <i class="fa-solid fa-check fs-5 ms-1"></i>
                            </button>
                            <!--end::Menu-->
                        </td>
                    </tr>
                ';
            echo $studentList;
        }
    }

    // Get Student List Select

    public function getStudentSelectList()
    {

        $schoolInfo = $this->getStudentsList();

        foreach ($schoolInfo as $key => $value) {

            $studentList = '
                    <option value="' . $value['id'] . '">' . $value['name'] . '</option>
                ';
            echo $studentList;
        }
    }

    // Get Student For Parents List Select

    public function getStudentForParentsSelectList()
    {

        $studentInfo = $this->getStudentsForParents();

        foreach ($studentInfo as $key => $value) {

            $studentList = '
                    <option value="' . $value['id'] . '">' . $value['name'] . ' ' . $value['surname'] . '</option>
                ';
            echo $studentList;
        }
    }

    // Show Student

    public function showOneStudent($slug)
    {

        $schoolInfo = $this->getOneStudent($slug);


        $studentList = '
                <div class="mb-3">
                    <h1 class="h3 d-inline align-middle">Böyle bir okul mevcut değil.</h1>
                </div>
        ';

        foreach ($schoolInfo as $value) {

            $studentList = '
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
        echo $studentList;
    }

    // Update Student

    public function updateOneStudent($slug)
    {

        $schoolInfo = $this->getOneStudent($slug);

        foreach ($schoolInfo as $value) {

            $studentList = '
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
        echo $studentList;
    }

    // List of Lessons Student Details Page

    public function showLessonsListForStudentDetails($studentId, $class_id, $school_id)
    {

        $lessonsInfo = $this->getLessons();

        $styles = ["danger", "success", "primary", "warning", "info", "secondary", "light", "dark"];
        $styleIndex = 0;

        require_once "content-tracker.classes.php";
        require_once "grade-result.classes.php";

        $contentObj = new ContentTracker();
        $gradeObj = new GradeResult();

        foreach ($lessonsInfo as $value) {

            $style = $styles[$styleIndex % count($styles)];

            $lessonList = '';

            $classes = $value['class_id'];

            $pieces = explode(";", $classes);

            if (in_array($class_id, $pieces)) {

                $class_id = $class_id;
                $school_id = $school_id;
                $lesson_id = $value['id'];

                $unitData = $this->getUnits($lesson_id, $class_id, $school_id);
                $unitCount = count($unitData);

                $result = $contentObj->getSchoolContentAnalyticsByLessonId($studentId, $class_id, $lesson_id);
                $resultW = ($result == null) ? 0 : $result;
                $resultT = ($result == null) ? '-' : $result;

                $score = $gradeObj->getGradeByLessonId($studentId, $lesson_id);
                $scoreW = ($score == null) ? 0 : $score;
                $scoreT = ($score == null) ? '-' : $score;

                $lessonList .= '
                <!--begin::Item-->
                    <div class="d-flex flex-stack">
                        <!--begin::Symbol-->
                        <div class="symbol symbol-40px me-4">
                            <div class="symbol-label fs-2 fw-semibold bg-' . $style . ' text-inverse-' . $style . '">' . mb_substr($value['name'], 0, 1, 'UTF-8') . '</div>
                        </div>
                        <!--end::Symbol-->
                        <!--begin::Section-->
                        <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                            <!--begin:Author-->
                            <div class="flex-grow-1 me-2">
                                <a href="pages/user-profile/overview.html" class="text-gray-800 text-hover-primary fs-6 fw-bold">' . $value['name'] . '</a>
                                <span class="text-muted fw-semibold d-block fs-7">' . $unitCount . ' Ünite</span>
                            </div>
                            <!--end:Author--><!--begin::Progress-->
                            <div class="d-flex align-items-center w-100px w-sm-200px flex-column mt-3">
                                <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                    <span class="fw-semibold fs-6 text-gray-500">Tamamlama Oranı</span>
                                    <span class="fw-bold fs-6">' . $resultT . '%</span>
                                </div>
                                <div class="h-5px mx-3 w-100 bg-light mb-3">
                                    <div class="bg-success rounded h-5px" role="progressbar" style="width: ' . $resultW . '%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                    <span class="fw-semibold fs-6 text-gray-500">Başarı Oranı</span>
                                    <span class="fw-bold fs-6">' . $scoreT . '%</span>
                                </div>
                                <div class="h-5px mx-3 w-100 bg-light mb-3">
                                    <div class="bg-success rounded h-5px" role="progressbar" style="width: ' . $scoreW . '%;" aria-valuenow="25"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                </div>
                            <!--end::Progress-->
                        </div>
                        <!--end::Section-->
                    </div>
                <!--end::Item-->
                <!--begin::Separator-->
                    <div class="separator separator-dashed my-4"></div>
                <!--end::Separator-->';
            }

            echo $lessonList;

            $styleIndex++;
        }
    }

    // List of Package Student Details Page

    public function showPackageListForStudentDetails($id)
    {

        $packagesInfo = $this->getStudentPackagesWithName($id);

        $dateFormat = new DateFormat();

        $packagesList = '';
        if (empty($packagesInfo)) {
            $packagesList = '<tr>
                                <td class="ps-0" colspan="3">
                                    <a href="#" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6 text-start pe-0">Alınan Paket Yok!</a>
                                </td>
                            </tr>';
        } else {
            foreach ($packagesInfo as $value) {

                $packagesList .= '<tr>
                                    <td class="ps-0">
                                        <a href="#" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6 text-start pe-0">' . $value['packageName'] . '</a>
                                    </td>
                                    <td>
                                        <span class="text-gray-800 fw-bold d-block fs-6 ps-0 text-end">' . str_replace('.', ',', strval($value['pay_amount'])) . '₺</span>
                                    </td>
                                    <td class="text-end pe-0">
                                        <span class="text-gray-800 fw-bold d-block fs-6">' . $dateFormat->changeDate($value['subscribed_end']) . '</span>
                                    </td>
                                </tr>';
            }
        }

        echo $packagesList;
    }

    // List of Package Details Student Details Page

    public function showPackageDetailsListForStudentDetails($id)
    {

        $packagesInfo = $this->getStudentPackagesWithName($id);

        $dateFormat = new DateFormat();

        $packagesList = '';

        $totalPrice = 0;

        if (empty($packagesInfo)) {
            $packagesList = '<tr>
                                <td colspan="4">
                                    Alınan Paket Yok!
                                </td>
                            </tr>';
        } else {
            foreach ($packagesInfo as $value) {

                $totalPrice += $value['pay_amount'];

                $packagesList .= '<tr>
                                    <td>
                                        <a href="paket-detay?id=' . $value['id'] . '" class="text-hover-primary text-gray-600">' . $value['packageName'] . '</a>
                                    </td>
                                    <td>
                                        ' . $value['className'] . '
                                    </td>
                                    <td>' . str_replace('.', ',', strval($value['pay_amount'])) . '₺</td>
                                    <td class="text-end">' . $dateFormat->changeDate($value['subscribed_end']) . '</td>
                                </tr>';
            }
        }

        $packagesList .= '<tfoot class="border-gray-200 fs-5 fw-semibold bg-lighten">
                            <tr>
                                <td class="text-gray-800 fw-bold mb-1 fs-6 text-start pe-0">Toplam Ücret</td>
                                <td></td>
                                <td class="text-gray-800 fw-bold">' . number_format($totalPrice, 2, ',', '.') . '₺</td>
                                <td></td>
                            </tr>
                        </tfoot>';

        echo $packagesList;
    }

    // List of Additional Package Student Details Page

    public function showAdditionalPackageListForStudentDetails($id)
    {

        /* $packagesInfo = $this->getStudentAdditionalPackagesWithName($id); */

        $packagesInfo = $this->getExtraPackageMyList($id);

        $dateFormat = new DateFormat();

        $packagesList = '';
        if (empty($packagesInfo)) {
            $packagesList = '<tr>
                                <td colspan="3">
                                    Alınan Ek Paket Yok!
                                </td>
                            </tr>';
        } else {
            /* foreach ($packagesInfo as $value) {

                $packagesList .= '<tr>
                                    <td class="ps-0">
                                        <a href="paket-detay?id=' . $value['id'] . '" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6 text-start pe-0">' . $value['packageName'] . '</a>
                                    </td>
                                    <td>
                                        <span class="text-gray-800 fw-bold d-block fs-6 ps-0 text-end">' . str_replace('.', ',', strval($value['total_amount'])) . '₺</span>
                                    </td>
                                    <td class="text-end pe-0">
                                        <span class="text-gray-800 fw-bold d-block fs-6">' . $dateFormat->changeDate($value['end_date']) . '</span>
                                    </td>
                                </tr>';
            } */
            foreach ($packagesInfo as $value) {

                if ($value['type'] == 'Özel Ders') {
                    $yazisi = $value['end_date'] . ' adet';
                } else {
                    $yazisi = $dateFormat->changeDate($value['end_date']);
                }

                $packagesList .= '<tr>
                                    <td class="ps-0">
                                        ' . $value['type'] . '
                                    </td>
                                    <td>
                                        ' . $value['adet'] . '
                                    </td>
                                    <td>
                                        <span class="text-gray-800 fw-bold d-block fs-6 ps-0 text-end">' . str_replace('.', ',', strval($value['total_amount'])) . '₺</span>
                                    </td>
                                    <td class="text-end pe-0">
                                        <span class="text-gray-800 fw-bold d-block fs-6">' . $yazisi . '</span>
                                    </td>
                                </tr>';
            }
        }

        echo $packagesList;
    }

    // List of Additional Package Details Student Details Page

    public function showAdditionalPackageDetailsListForStudentDetails($id)
    {

        $packagesInfo = $this->getExtraPackageMyList($id);

        $dateFormat = new DateFormat();

        $packagesList = '';

        $totalPrice = 0;

        if (empty($packagesInfo)) {
            $packagesList = '<tr>
                                <td colspan="4">
                                    Alınan Ek Paket Yok!
                                </td>
                            </tr>';
        } else {/* 
foreach ($packagesInfo as $value) {

if($value['packageType'] == 'Özel Ders'){
$yazisi = 'adet';
} else {
$yazisi = $value['packageLimit'] . ' aylık';
}

$totalPrice += $value['total_amount'];

$packagesList .= '<tr>
<td>
<a href="paket-detay?id=' . $value['id'] . '" class="text-hover-primary text-gray-600">' . $value['packageName'] . '</a>
</td>
<td>
' . $value['packageType'] . '
</td>
<td>
' . $yazisi . '
</td>
<td>' . str_replace('.', ',', strval($value['total_amount'])) . '₺</td>
<td class="text-end">' . $dateFormat->changeDate($value['end_date']) . '</td>
</tr>';
} */
            foreach ($packagesInfo as $value) {

                $totalPrice += $value['total_amount'];

                if ($value['type'] == 'Özel Ders') {
                    $yazisi = $value['end_date'] . ' adet';
                } else {
                    $yazisi = $dateFormat->changeDate($value['end_date']);
                }

                $packagesList .= '<tr>
                                    <td>
                                        ' . $value['name'] . '
                                    </td>
                                    <td>
                                        ' . $value['type'] . '
                                    </td>
                                    <td>
                                        ' . $value['adet'] . '
                                    </td>
                                    <td>' . str_replace('.', ',', strval($value['total_amount'])) . '₺</td>
                                    <td class="text-end">' . $yazisi . '</td>
                                </tr>';
            }
        }

        $packagesList .= '<tr>
                            <td class="text-gray-800 fw-bold mb-1 fs-6 text-start pe-0">Toplam Ücret</td>
                            <td></td>
                            <td></td>
                            <td class="text-gray-800 fw-bold">' . number_format($totalPrice, 2, ',', '.') . '₺</td>
                            <td></td>
                        </tr>';

        echo $packagesList;
    }

    public function showprivateLessons($id)
    {

        $showPrivateLessons = new Classes();
        $data = $showPrivateLessons->getPrivateLessonRequestList($id);

        foreach ($data as $key => $value) {
            switch ($value['request_status']) {
                case 0:
                    $status = 'Beklemede';
                    break;
                case 1:
                    $status = 'Onaylandı';
                    break;
                case 2:
                    $status = 'Reddedildi';
                    break;
                case 3:
                    $status = 'Öğretmen Atandı';
                    break;
                case 4:
                    $status = 'Tamamlandı';
                    break;
                case 5:
                    $status = 'İptal Edildi';
                    break;
            }

            $privateLessons = '
                                <tr>
                                    <td>
                                        <a href="#" class="text-hover-primary text-gray-600">Özel Ders ' . $key + 1 . '</a>
                                    </td>
                                    <td>
                                        ' . $value['class_name'] . '
                                    </td>
                                    <td>
                                        ' . $value['lesson_name'] . '
                                    </td>
                                    <td>
                                        ' . $value['unit_name'] . '
                                    </td>
                                    <td>
                                        ' . $value['topic_name'] . '
                                    </td>
                                    <td>
                                        ' . $value['subtopic_name'] . '
                                    </td>
                                    <td>
                                        ' . $value['teacher_full_name'] . '
                                    </td>
                                    <td>
                                        ' . $value['meet_date'] . '
                                    </td>
                                    <td>' . $status . '</td>
                                </tr>';

            echo $privateLessons;
        }
    }

    public function showprivateLessonsforFirstPage($id)
    {

        $showPrivateLessons = new Classes();
        $data = $showPrivateLessons->getPrivateLessonRequestList($id);

        foreach ($data as $key => $value) {
            switch ($value['request_status']) {
                case 0:
                    $status = 'Beklemede';
                    break;
                case 1:
                    $status = 'Onaylandı';
                    break;
                case 2:
                    $status = 'Reddedildi';
                    break;
                case 3:
                    $status = 'Öğretmen Atandı';
                    break;
                case 4:
                    $status = 'Tamamlandı';
                    break;
                case 5:
                    $status = 'İptal Edildi';
                    break;
            }

            $privateLessons = '
                                <tr>
                                    <td>
                                        ' . $value['class_name'] . '
                                    </td>
                                    <td>
                                        ' . $value['lesson_name'] . '
                                    </td>
                                    <td>
                                        ' . $value['teacher_full_name'] . '
                                    </td>
                                    <td class="text-end">
                                        ' . $value['meet_date'] . '
                                    </td>
                                    <td class="text-end">' . $status . '</td>
                                </tr>';

            echo $privateLessons;
        }
    }

    // List of Students

    public function showStudentList()
    {

        $schoolInfo = $this->getStudents();

        $studentList = '';

        $divStart = '<div class="row"><div class="overflow-auto col-12 col-lg-2" style="max-height: 150px">';

        $divEnd = '</div></div>';

        foreach ($schoolInfo as $value) {

            $parentId = $value['id'];

            $schoolForms = '<div class="form-check">
                                            <input class="form-check-input" name="category[]" type="checkbox" value="' . $value['id'] . '" id="' . $value['slug'] . '">
                                                <label class="form-check-label" for="' . $value['slug'] . '">
                                                ' . $value['catName'] . '
                                                </label>
                                        </div>';



            $studentList .= $schoolForms;
        }


        echo $divStart . $studentList . $divEnd;
    }

    // List of Lessons For Student Details Page

    public function showLessonsListForStudentDetailsPage($getStudentId, $class_id, $school_id)
    {
        $lessonsInfo = $this->getLessons();

        $styles = ["danger", "success", "primary", "warning", "info", "secondary", "light", "dark"];
        $styleIndex = 0;

        require_once "content-tracker.classes.php";
        require_once "grade-result.classes.php";

        $contentObj = new ContentTracker();
        $gradeObj = new GradeResult();

        foreach ($lessonsInfo as $value) {

            $style = $styles[$styleIndex % count($styles)];

            $lessonList = '';

            $classes = $value['class_id'];

            $pieces = explode(";", $classes);

            if (in_array($class_id, $pieces)) {

                $lesson_id = $value['id'];

                $unitData = $this->getUnits($lesson_id, $class_id, $school_id);
                $unitCount = count($unitData);

                $units = '';


                foreach ($unitData as $unit) {
                    $topicCount = 0;
                    $topicData = $this->getTopics($lesson_id, $class_id, $school_id, $unit['id']);
                    $topicCount = count($topicData);

                    $result = $contentObj->getSchoolContentAnalyticsByUnitId($getStudentId, $class_id, $lesson_id, $unit['id']);
                    $resultW = ($result == null) ? 0 : $result;
                    $resultT = ($result == null) ? '-' : $result;

                    $score = $gradeObj->getGradeByUnitId($getStudentId, $unit['id']);
                    $scoreW = ($score == null) ? 0 : $score;
                    $scoreT = ($score == null) ? '-' : $score;
                    $units .= '
                            <!--begin::Item-->
                                <div class="d-flex flex-stack">
                                    <!--begin::Symbol-->
                                    <div class="symbol symbol-40px me-4">
                                        <div class="symbol-label fs-2 fw-semibold bg-' . $style . ' text-inverse-' . $style . '">' . mb_substr($unit['name'], 0, 1, 'UTF-8') . '</div>
                                    </div>
                                    <!--end::Symbol-->
                                    <!--begin::Section-->
                                    <div class="d-flex align-items-center flex-row-fluid ">
                                        <!--begin:Author-->
                                        <div class="flex-grow-1 me-2">
                                            <a href="' . $unit['id'] . '" class="text-gray-800 text-hover-primary fs-6 fw-bold">' . $unit['name'] . '</a>
                                            <span class="text-muted fw-semibold d-block fs-7">' . $topicCount . ' Konu</span>
                                        </div>
                                        <!--end:Author-->
                                        <!--begin::Progress-->
                                        <div class="d-flex align-items-center w-100px w-sm-200px flex-column mt-3">
                                            <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                                <span class="fw-semibold fs-6 text-gray-500">Tamamlama Oranı</span>
                                                <span class="fw-bold fs-6">' . $resultT . '%</span>
                                                
                                            </div>
                                            <div class="h-5px mx-3 w-100 bg-light mb-3">
                                                <div class="bg-success rounded h-5px" role="progressbar" style="width: ' . $resultW . '%;" aria-valuenow="25"
                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                                <span class="fw-semibold fs-6 text-gray-500">Başarı Oranı</span>
                                                <span class="fw-bold fs-6">' . $scoreT . '%</span>
                                                
                                            </div>
                                            <div class="h-5px mx-3 w-100 bg-light mb-3">
                                                <div class="bg-success rounded h-5px" role="progressbar" style="width: ' . $scoreW . '%;" aria-valuenow="25"
                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                        

                                        
                                        <!--end::Progress-->
                                    </div>
                                    <!--end::Section-->
                                </div>
                                <!--end::Item-->
                                <!--begin::Separator-->
                                <div class="separator separator-dashed my-4"></div>
                            <!--end::Separator-->
                    ';
                }

                $leftUnits = '
                        <!--begin::Col-->
                            <div class="col-xl-6">
                                <!--begin::List widget 20-->
                                    <div class="card mb-5 mb-xl-8">
                                        <!--begin::Header-->
                                        <div class="card-header border-0 pt-5">
                                            <h3 class="card-title align-items-start flex-column">
                                                <span class="card-label fw-bold text-gray-900">' . $value['name'] . '</span>
                                                
                                                <span class="text-muted fw-semibold d-block fs-7">' . $unitCount . ' Ünite</span>
                                            </h3>
                                        </div>
                                        <!--end::Header-->
                                        <!--begin::Body-->
                                        <div class="card-body pt-6">
                                            ' . $units . '
                                        </div>
                                        <!--end::Body-->
                                    </div>
                                    <!--end::List widget 20-->
                                </div>
                            <!--end::Col-->';


                $styleIndex++;

                echo $leftUnits;
            }
            // echo "<pre>";
            // var_dump($lessonsInfo);
            // echo "</pre>";

        }
    }

    // List of Logins Student Details Page

    public function showLoginDetailsListForStudentDetails($id)
    {

        $loginInfo = $this->getStudentLoginInfo($id);

        $dateFormat = new DateFormat();

        $loginList = '';

        if (empty($loginInfo)) {
            $loginList = '<tr>
                                <td colspan="4">
                                    Giriş Yapılmamış!
                                </td>
                            </tr>';
        } else {
            foreach ($loginInfo as $value) {

                if ($value['logoutTime'] == '0000-00-00 00:00:00' or $value['logoutTime'] == null) {
                    $logoutTime = 'Çıkış Bilgisi Yok';
                } else {
                    $logoutTime = $dateFormat->changeDateHour($value['logoutTime']);
                }

                $loginList .= '<tr>
                                    <td>
                                        ' . $value['deviceType'] . '
                                    </td>
                                    <td>
                                        ' . $value['deviceModel'] . '
                                    </td>
                                    <td>' . $value['deviceOs'] . '</td>
                                    <td>' . $value['browser'] . '</td>
                                    <td>' . $value['resolution'] . '</td>
                                    <td>' . $value['ipAddress'] . '</td>
                                    <td>' . $dateFormat->changeDateHour($value['loginTime']) . '</td>
                                    <td>' . $logoutTime . '</td>
                                </tr>';
            }
        }

        echo $loginList;
    }
}
