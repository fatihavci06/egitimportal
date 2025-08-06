<?php
include_once "dateformat.classes.php";

class ShowGame extends Games
{

    public function getHeaderImageStuForOne()
    {
        $link = "$_SERVER[REQUEST_URI]";

        $active_slug = htmlspecialchars(basename($link, ".php"));

        $unitInfo = $this->getOneGame($active_slug);

        $view = '
                <header class="container-fluid py-3 d-flex justify-content-between align-items-center"
                    style="
                        background-color: #e6e6fa !important;
                        margin-bottom: 40px !important;
                        margin-top: -45px !important;
                        border-top: 5px solid #d22b2b !important;
                        border-bottom: 5px solid #d22b2b !important;
                        height:85px;
                    ">
                    <div class="d-flex align-items-center">
                        <div class="">
                            <img src="assets/media/games/' . $unitInfo['cover_img'] . '" alt="' . $unitInfo['game_name'] . ' Icon"
                                class="img-fluid"
                                style="width: 90px; height: 90px; object-fit: contain;">
                        </div>
                        <div>
                            <h1 class="fs-3 fw-bold text-dark mb-0 ml-2" style="margin-left: 20px;">' . $unitInfo['game_name'] . '</h1>
                        </div>
                    </div>
                </header>';

        echo $view;

    }

    public function getGameList()
    {

        $unitInfo = $this->getGamesList();

        $dateFormat = new DateFormat();

        foreach ($unitInfo as $key => $value) {
            $active_status = '<span class="badge badge-light-success">Aktif</span>';
            if (!$value['is_active']) {
                $active_status = '<span class="badge badge-light-danger">Pasif</span>';
            }

            $alter_button = $value['is_active'] ? "Pasif Yap" : "Aktif Yap";
            $lessonList = '
                    <tr id="' . $value['id'] . '">
                        <td>
                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="1" />
                            </div>
                        </td>
                        <td>
                            <a href="./oyun-detay/' . $value['slug'] . '" class="cursor-pointer symbol symbol-90px symbol-lg-90px"><img src="assets/media/games/' . $value['cover_img'] . '"></a>
                        </td>
                        <td>
                            <a href="./oyun-detay/' . $value['slug'] . '" class="text-gray-800 text-hover-primary mb-1">' . $value['game_name'] . '</a>
                        </td>
                        <td>
                            <span class="symbol symbol-10px me-2">

                            ' . $active_status . '
                            </span>
                        </td>
                        <td>
                            <a href="#" class="text-gray-800 text-hover-primary mb-1">' . ($value['class_name'] ?? '-') . '</a>
                        </td>                        
                        <td>
                            <a href="#" class="text-gray-800 text-hover-primary mb-1">' . ($value['lesson_name'] ?? '-') . '</a>
                        </td>
                        </td>


                         <td class="text-end">
                            <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">İşlemler
                                <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                data-kt-menu="true">
                              
                                <div class="menu-item px-3">
                                    <a href="./oyun-detay/' . $value['slug'] . '" class="menu-link px-3">Görüntüle</a>
                                </div>

                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-customer-table-filter="delete_row">' . $alter_button . '</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                ';
            echo $lessonList;
        }
    }

    public function getID()
    {
        $link = "$_SERVER[REQUEST_URI]";
        $active_slug = htmlspecialchars(basename($link, ".php"));

        $lessonIdInfo = $this->getLessonId($active_slug);

        return $lessonIdInfo;
    }

    public function getGamesListStudent($class_id)
    {

        $unitInfo = $this->getGamesListStudentShow($class_id);

        if ($unitInfo == NULL) {

            $lessonList = '
                        <!--begin::Col-->
                            <div class="col-md-12 d-flex flex-center">
                                <i class="fa-regular fa-face-frown-open text-danger fs-4x"></i>
                                
                            </div>

                            <div class="text-center mt-2">
                                <h4 class="fs-2hx text-gray-900 mb-5">Oyun Mevcut Değil!</h4>
                            </div>

                        <!--end::Col-->
                        ';
            echo $lessonList;
        } else {

            foreach ($unitInfo as $key => $value) {

                $lessonList = '
                            <div class="col-md-4">
                                <div class="card-xl-stretch me-md-6">
                                    <a class="d-block overlay mb-4" href="oyun-oyna/' . $value['slug'] . '">
                                        <div class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-175px" style="background-image:url(\'assets/media/games/' . $value['cover_img'] . '\')"></div>
                                        <div class="overlay-layer bg-dark card-rounded bg-opacity-25">
                                            <i class="ki-duotone ki-eye fs-2x text-white"></i>
                                        </div>
                                    </a>
                                    <div class="m-0">
                                        <a href="oyun-oyna/' . $value['slug'] . '" class="fs-4 text-gray-900 fw-bold text-hover-primary text-gray-900 lh-base">' . $value['name'] . '</a>
                                        <div class="fw-semibold fs-5 text-gray-600 text-gray-900 mt-3 mb-5"></div>
                                    </div>
                                </div>
                            </div>
                    ';
                echo $lessonList;
            }
        }
    }

    public function getHeaderImageStu()
    {


        $view = '
                <header class="container-fluid py-3 d-flex justify-content-between align-items-center"
                    style="
                        background-color: #e6e6fa !important;
                        margin-bottom: 40px !important;
                        margin-top: -45px !important;
                        border-top: 5px solid #d22b2b !important;
                        border-bottom: 5px solid #d22b2b !important;
                        height:85px;
                    ">
                    <div class="d-flex align-items-center">
                        <div class="">
                            <img src="assets/media/games/icon.avif" alt="Oyunlar Icon"
                                class="img-fluid"
                                style="width: 90px; height: 66px; object-fit: contain;">
                        </div>
                        <div>
                            <h1 class="fs-3 fw-bold text-dark mb-0 ml-2" style="margin-left: 20px;"> Oyunlar </h1>
                        </div>
                    </div>
                </header>';

        echo $view;

    }

    public function getHeaderImageInGameStu()
    {

        $link = "$_SERVER[REQUEST_URI]";

        $active_slug = htmlspecialchars(basename($link, ".php"));

        $unitInfo = $this->getOneGame($active_slug);

        /* $unitInfo = $this->getGamesListForHead(); */

        $lessonList = '
                    <div class="position-relative mb-17">
                        <!--begin::Overlay-->
                        <div class="overlay overlay-show">
                            <!--begin::Image-->
                            <div class="bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-250px" style="background-image:url(\'assets/media/games/' . $unitInfo['cover_img'] . '\')"></div>
                            <!--end::Image-->
                            <!--begin::layer-->
                            <div class="overlay-layer rounded bg-black" style="opacity: 0.4"></div>
                            <!--end::layer-->
                        </div>
                        <!--end::Overlay-->
                        <!--begin::Heading-->
                        <div class="position-absolute text-white mb-8 ms-10 bottom-0">
                            <!--begin::Title-->
                            <h3 class="text-white fs-2qx fw-bold mb-3 m"></h3>
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

    public function getSidebarTopicsStu()
    {
        $link = "$_SERVER[REQUEST_URI]";

        $active_slug = htmlspecialchars(basename($link, ".php"));

        $unitInfo = $this->getSameGames($active_slug);

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

            $lessonList .= '
                            <!--begin::Section-->
                            <div class="my-2">
                                <!--begin::Row-->
                                <div class="d-flex align-items-center mb-3">
                                    <!--begin::Bullet-->
                                    <span class="bullet me-3"></span>
                                    <!--end::Bullet-->
                                    <!--begin::Label-->
                                    <div class="text-gray-600 fw-semibold fs-6"><a href="unite/' . $value['unitSlug'] . '">' . $value['unitName'] . '</a></div>
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

    public function getGameListStu()
    {
        $link = "$_SERVER[REQUEST_URI]";

        $active_slug = htmlspecialchars(basename($link, ".php"));

        $unitInfo = $this->getGamesList($active_slug);

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

    public function showOneGame($slug)
    {

        $gameInfo = $this->getOneGame($slug);


        $gameHtml = '
                <div class="mb-3">
                    <h1 class="h3 d-inline align-middle">Böyle bir Oyun mevcut değil.</h1>
                </div>
        ';


        $gameUrl = $gameInfo['game_url'];

        if (preg_match('/width="[^"]*"/', $gameUrl)) {
            $gameUrl = preg_replace('/width="[^"]*"/', 'width="100%"', $gameUrl);
        }
        // $isIframe = (strpos($gameUrl, '<iframe') !== false);

        // if (!$isIframe) {
        //     $gameUrl = '<p>Geçersiz yerleştirme kodu. Bir iframe bekleniyordu.</p>';
        // }
        $active_status = '<span class="badge badge-light-success">Aktif</span>';
        if (!$gameInfo['is_active']) {
            $active_status = '<span class="badge badge-light-danger">Pasif</span>';
        }

        $alter_button = $gameInfo['is_active'] ? "Pasif Yap" : "Aktif Yap";


        $gameHtml = '
                <tr id="' . $gameInfo['id'] . '">
                    <td>
                        <a  class="cursor-pointer symbol symbol-90px symbol-lg-90px">
                            <img src="assets/media/games/' . $gameInfo['cover_img'] . '" alt="' . $gameInfo['game_name'] . '">
                        </a>
                    </td>
                    <td>
                        <a class="text-gray-800 text-hover-primary mb-1 fw-bold">' . $gameInfo['game_name'] . '</a>
                    </td>
                    <td>
                        <span class="symbol symbol-10px me-2">
                            ' . $active_status . '
                        </span>
                    </td>
                    <td><span class="text-gray-800">' . ($gameInfo['class_name'] ?? '-') . '</span></td>
                    <td><span class="text-gray-800">' . ($gameInfo['lesson_name'] ?? '-') . '</span></td>
                    <td><span class="text-gray-800">' . ($gameInfo['unit_name'] ?? '-') . '</span></td>
                    <td><span class="text-gray-800">' . ($gameInfo['topic_name'] ?? '-') . '</span></td>
                    <td><span class="text-gray-800">' . ($gameInfo['subtopic_name'] ?? '-') . '</span></td>
                    <td class="text-end">
                        <a  class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">İşlemler
                            <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                            data-kt-menu="true">
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_modal_update_customer">Güncelle</a>
                            </div>
                            <div class="menu-item px-3">
                                    <a class="menu-link px-3" id="alter_button">' . $alter_button . '</a>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="8">
                        <div class="card mt-2">
                            <div class="card-body">
                                <div style="width: 100%; height: 100%; position: relative;">'
                                    . $gameUrl .
                                '</div>
                            </div>
                        </div>
                    </td>
                </tr>
                ';
        echo $gameHtml;
    }

    public function showGameStudent()
    {
        $link = "$_SERVER[REQUEST_URI]";

        $active_slug = htmlspecialchars(basename($link, ".php"));

        $unitInfo = $this->getOneGame($active_slug);

        /* foreach ($unitInfo as $key => $value) { */

        //$youtubeID = $this->getYouTubeVideoId($value['video_url']);

        if (preg_match('/width="[^"]*"/',  $unitInfo['game_url'])) {
            $unitInfo['game_url'] = preg_replace('/width="[^"]*"/', 'width="100%"', $unitInfo['game_url']);
        }
        $lessonList = '
                    <div style="width: 100%; height: 100%; position: relative;">
                        ' . $unitInfo['game_url'] . '
                        <!--end::Text-->
                    </div>
                ';
        echo $lessonList;
        /* } */
    }

    public function showGameForTopicList($class, $lessons)
    {

        $unitInfo = $this->getGameForTopicList($class, $lessons);

        $units = array();

        foreach ($unitInfo as $key => $value) {

            $units[] = array("id" => $value["id"], "text" => $value["name"]);
        }


        echo json_encode($units);
    }
}
