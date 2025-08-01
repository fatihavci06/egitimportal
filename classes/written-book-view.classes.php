<?php
include_once "dateformat.classes.php";

class ShowWrittenBook extends WrittenBook
{

    public function getWrittenBookList()
    {

        $audioBookInfo = $this->getWrittenBooksList();

        $dateFormat = new DateFormat();

        foreach ($audioBookInfo as $key => $value) {

            $active_status = '<span class="badge badge-light-success">Aktif</span>';
            if (!$value['is_active']) {
                $active_status = '<span class="badge badge-light-danger">Pasif</span>';
            }

            $alter_button = $value['is_active'] ? "Pasif Yap" : "Aktif Yap";
            $audioBookList = '
                    <tr id="' . $value['id'] . '">

                        <td>
                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="1" />
                            </div>
                        </td>
                        <td>
                            <a href="./yazili-kitap-detay/' . $value['slug'] . '" class="cursor-pointer symbol symbol-90px symbol-lg-90px"><img src="assets/media/sesli-kitap/' . $value['cover_img'] . '"></a>
                        </td>
                        <td>
                            <a href="./yazili-kitap-detay/' . $value['slug'] . '" class="text-gray-800 text-hover-primary mb-1">' . $value['book_name'] . '</a>
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
                                    <a href="./yazili-kitap-detay/' . $value['slug'] . '" class="menu-link px-3">Görüntüle</a>
                                </div>

                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-customer-table-filter="delete_row">' . $alter_button . '</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                ';
            echo $audioBookList;
        }
    }
    public function getHeaderImageForOne()
    {
        $link = "$_SERVER[REQUEST_URI]";

        $active_slug = htmlspecialchars(basename($link, ".php"));

        $unitInfo = $this->getOneWrittenBook($active_slug);

        $view = '
                <header class="container-fluid py-3 d-flex justify-content-between align-items-center"
                    style="
                        background-color: #e6e6fa !important;
                        margin-bottom: 40px !important;
                        margin-top: -45px !important;
                        border-top: 5px solid #d22b2b !important;
                        border-bottom: 5px solid #d22b2b !important;
                        height:85px;
                        margin-left:-10px
                    ">
                    <div class="d-flex align-items-center">
                        <div class="">
                            <img src="assets/media/sesli-kitap/' . $unitInfo['cover_img'] . '" alt="' . $unitInfo['book_name'] . ' Icon"
                                class="img-fluid"
                                style="width: 90px; height: 90px; object-fit: contain;">
                        </div>
                        <div>
                            <h1 class="fs-3 fw-bold text-dark mb-0 ml-2" style="margin-left: 20px;">' . $unitInfo['book_name'] . '</h1>
                        </div>
                    </div>
                </header>';

        echo $view;

    }
    public function getHeaderImageInWrittenBookStu()
    {

        $link = "$_SERVER[REQUEST_URI]";

        $active_slug = htmlspecialchars(basename($link, ".php"));

        $audioBookInfo = $this->getOneWrittenBook($active_slug);

        /* $audioBookInfo = $this->getAudioBooksList(); */
        $view = '
                <header class="  d-flex justify-content-between align-items-center"
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
                            <img src="assets/media/sesli-kitap/' . $audioBookInfo['cover_img'] . '" alt="' . $audioBookInfo['book_name'] . ' Icon"
                                class="img-fluid"
                                style="width: 90px; height: 90px; object-fit: contain;">
                        </div>
                        <div>
                            <h1 class="fs-3 fw-bold text-dark mb-0 ml-2" style="margin-left: 20px;">' . $audioBookInfo['book_name'] . '</h1>
                        </div>
                    </div>
                </header>';

    
        echo $view;

    }
    public function getID()
    {
        $link = "$_SERVER[REQUEST_URI]";
        $active_slug = htmlspecialchars(basename($link, ".php"));

        $audioBookIdInfo = $this->getLessonId($active_slug);

        return $audioBookIdInfo;
    }

    public function getWrittenBooksListStudent()
    {

        $audioBookInfo = $this->getWrittenBooksList();

        if ($audioBookInfo == NULL) {

            $audioBookList = '
                        <!--begin::Col-->
                            <div class="col-md-12 d-flex flex-center">
                                <i class="fa-regular fa-face-frown-open text-danger fs-4x"></i>
                                
                            </div>

                            <div class="text-center mt-2">
                                <h4 class="fs-2hx text-gray-900 mb-5">Oyun Mevcut Değil!</h4>
                            </div>

                        <!--end::Col-->
                        ';
            echo $audioBookList;
        } else {

            foreach ($audioBookInfo as $key => $value) {

                $audioBookList = '
                            <!--begin::Col-->
                            <div class="col-md-4">
                                <!--begin::Publications post-->
                                <div class="card-xl-stretch me-md-6">
                                    <!--begin::Overlay-->
                                    <a class="d-block overlay mb-4" href="oyun-oyna/' . $value['slug'] . '">
                                        <!--begin::Image-->
                                        <div class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-175px" style="background-image:url(\'assets/media/games/' . $value['cover_img'] . '\')"></div>
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
                                        <a href="oyun-oyna/' . $value['slug'] . '" class="fs-4 text-gray-900 fw-bold text-hover-primary text-gray-900 lh-base">' . $value['name'] . '</a>
                                        <!--end::Title-->
                                        <!--begin::Text-->
                                        <div class="fw-semibold fs-5 text-gray-600 text-gray-900 mt-3 mb-5"></div>
                                        <!--end::Text-->
                                    </div>
                                    <!--end::Body-->
                                </div>
                                <!--end::Publications post-->
                            </div>
                            <!--end::Col-->
                    ';
                echo $audioBookList;
            }
        }
    }

    public function getHeaderImageStu()
    {

        $audioBookInfo = $this->getWrittenBooksList();

        foreach ($audioBookInfo as $key => $value) {

            $audioBookList = '
                    <div class="position-relative mb-17">
                        <!--begin::Overlay-->
                        <div class="overlay overlay-show">
                            <!--begin::Image-->
                            <div class="bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-250px" style="background-image:url(\'assets/media/games/gameDefault.jpg\')"></div>
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
            echo $audioBookList;
        }
    }

    public function getSidebarTopicsStu()
    {
        $link = "$_SERVER[REQUEST_URI]";

        $active_slug = htmlspecialchars(basename($link, ".php"));

        $audioBookInfo = $this->getSameWrittenBook($active_slug);

        $audioBookList = '<div class="card-body">
                        <!--begin::Top-->
                        <div class="mb-7">
                            <!--begin::Title-->
                            <h2 class="fs-1 text-gray-800 w-bolder mb-6">Diğer Üniteler</h2>
                            <!--end::Title-->
                        </div>
                        <!--end::Top-->
                        <!--begin::Item-->';

        foreach ($audioBookInfo as $key => $value) {

            $audioBookList .= '
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

        $audioBookList .= '
                    <!--end::Item-->
                </div>
            ';
        echo $audioBookList;
    }

    public function getWrittenBooksListStu()
    {
        $link = "$_SERVER[REQUEST_URI]";

        $active_slug = htmlspecialchars(basename($link, ".php"));

        $audioBookInfo = $this->getWrittenBooksList($active_slug);

        $dateFormat = new DateFormat();

        foreach ($audioBookInfo as $key => $value) {

            $audioBookList = '
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
                                    <a href="./yazili-kitap-guncelle/' . $value['slug'] . '" class="menu-link px-3">Güncelle</a>
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
            echo $audioBookList;
        }
    }

    public function showOneWrittenBook($slug)
    {

        $audioBookInfo = $this->getOneWrittenBook($slug);

        $bookHtml = '
                <div class="mb-3">
                    <h1 class="h3 d-inline align-middle">Böyle bir Oyun mevcut değil.</h1>
                </div>
        ';

        $bookUrl = $audioBookInfo['book_url'];
        $isIframe = (strpos($bookUrl, 'https://online.flippingbook.com') !== false);

        if (!$isIframe) {
            $bookUrl = '<p>Geçersiz yerleştirme kodu. Bir iframe bekleniyordu.</p>';
        }
        $active_status = '<span class="badge badge-light-success">Aktif</span>';
        if (!$audioBookInfo['is_active']) {
            $active_status = '<span class="badge badge-light-danger">Pasif</span>';
        }

        $alter_button = $audioBookInfo['is_active'] ? "Pasif Yap" : "Aktif Yap";

        $bookHtml = '
                <tr id="' . $audioBookInfo['id'] . '">
                    <td>
                        <a class="cursor-pointer symbol symbol-90px symbol-lg-90px">
                            <img src="assets/media/sesli-kitap/' . $audioBookInfo['cover_img'] . '" alt="' . $audioBookInfo['book_name'] . '">
                        </a>
                    </td>
                    <td>
                        <a class="text-gray-800 text-hover-primary mb-1 fw-bold">' . $audioBookInfo['book_name'] . '</a>
                    </td>
                    <td>
                        <span class="symbol symbol-10px me-2">
                            ' . $active_status . '
                        </span>
                    </td>
                    <td><span class="text-gray-800">' . ($audioBookInfo['class_name'] ?? '-') . '</span></td>
                    <td><span class="text-gray-800">' . ($audioBookInfo['lesson_name'] ?? '-') . '</span></td>
                    <td><span class="text-gray-800">' . ($audioBookInfo['unit_name'] ?? '-') . '</span></td>
                    <td><span class="text-gray-800">' . ($audioBookInfo['topic_name'] ?? '-') . '</span></td>
                    <td><span class="text-gray-800">' . ($audioBookInfo['subtopic_name'] ?? '-') . '</span></td>
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
                            <div class="card-body">'
            . $bookUrl .
            '</div>
                        </div>
                    </td>
                </tr>
                ';
        echo $bookHtml;
    }


    public function showWrittenBookList()
    {

        $audioBookInfo = $this->getWrittenBooks();

        $audioBookList = '';

        $divStart = '<div class="row"><div class="overflow-auto col-12 col-lg-2" style="max-height: 150px">';

        $divEnd = '</div></div>';

        foreach ($audioBookInfo as $value) {

            $parentId = $value['id'];

            $classForms = '<div class="form-check">
                                            <input class="form-check-input" name="category[]" type="checkbox" value="' . $value['id'] . '" id="' . $value['slug'] . '">
                                                <label class="form-check-label" for="' . $value['slug'] . '">
                                                ' . $value['catName'] . '
                                                </label>
                                        </div>';



            $audioBookList .= $classForms;
        }


        echo $divStart . $audioBookList . $divEnd;
    }

    // Show Topic For Students

    public function showWrittenBookStudent()
    {
        $link = "$_SERVER[REQUEST_URI]";

        $active_slug = htmlspecialchars(basename($link, ".php"));

        $audioBookInfo = $this->getOneWrittenBook($active_slug);


        $bookHtml = '
                <div class="mb-3">
                    <h1 class="h3 d-inline align-middle">Böyle bir Oyun mevcut değil.</h1>
                </div>
        ';

        $bookUrl = $audioBookInfo['book_url'];
        $isIframe = (strpos($bookUrl, 'https://online.flippingbook.com') !== false);

        if (!$isIframe) {
            $bookUrl = '<p>Geçersiz yerleştirme kodu. Bir iframe bekleniyordu.</p>';
        }

        /* foreach ($audioBookInfo as $key => $value) { */

        //$youtubeID = $this->getYouTubeVideoId($value['video_url']);


        $audioBookList = '
            <div class="card " style="margin-left:-13px;">
            '
            . $bookUrl .
            '
            </div>

        ';
        // $audioBookList = '
        //             <div class="m-0">
        //                 <h4 class="fs-1 text-gray-800 w-bolder mb-6">Sesli Kitap İçeriği</h4>
        //                 <p class="fw-semibold fs-4 text-gray-600 mb-2">' . $audioBookInfo['book_url'] . '</p>
        //             </div>
        //         ';
        echo $audioBookList;
        /* } */
    }

    // Get WrittenBooks For Topic List

    public function showWrittenBookForTopicList($class, $lessons)
    {

        $audioBookInfo = $this->getWrittenBookForTopicList($class, $lessons);

        $units = array();

        foreach ($audioBookInfo as $key => $value) {

            $units[] = array("id" => $value["id"], "text" => $value["name"]);
        }


        echo json_encode($units);
    }
}

class ShowWrittenBookStudent extends WrittenBookStudent
{

    // Get WrittenBook List

    public function getWrittenBookList($class_id)
    {

        $audioBookInfo = $this->getWrittenBooksList($class_id);

        $dateFormat = new DateFormat();

        if ($audioBookInfo == NULL) {

            $audioBookList = '
                        <!--begin::Col-->
                            <div class="col-md-12 d-flex flex-center">
                                <i class="fa-regular fa-face-frown-open text-danger fs-4x"></i>
                                
                            </div>

                            <div class="text-center mt-2">
                                <h4 class="fs-2hx text-gray-900 mb-5">Sesli Kitap Mevcut Değil!</h4>
                            </div>

                        <!--end::Col-->
                        ';
            echo $audioBookList;
        } else {

            foreach ($audioBookInfo as $key => $value) {
                $audioBookList = '
                    <div class="col-12 col-sm-6 col-md-5 col-lg-4 col-xl-3  p-3">
                        <div class=" card-xl-stretch border p-3 rounded bg-white me-md-6">
                            <a class="d-block overlay mb-4" href="./yazili-kitap-oku/' . $value['slug'] . '">
                              <div class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-175px"
                                    style="background-image:url(\'assets/media/sesli-kitap/' . $value['cover_img'] . '\'); 
                                        border-radius: 6px;">
                                    <div class="dark-overlay"></div>
                                </div>
                            </a>
                            <div class="m-0">
                                <a href="./yazili-kitap-oku/' . $value['slug'] . '" 
                                class="fs-5 text-gray-700 fw-bold text-hover-primary lh-base">' . $value['name'] . '</a>
                            </div>
                        </div>
                    </div>
                ';
                echo $audioBookList;
            }
        }
    }

    // Get Lesson Id

    public function getID()
    {
        $link = "$_SERVER[REQUEST_URI]";
        $active_slug = htmlspecialchars(basename($link, ".php"));

        $audioBookIdInfo = $this->getLessonId($active_slug);

        return $audioBookIdInfo;
    }

    // Get WrittenBook Name

    public function getWrittenBooksListStudent()
    {

        $audioBookInfo = $this->getWrittenBooksList();

        if ($audioBookInfo == NULL) {

            $audioBookList = '
                        <!--begin::Col-->
                            <div class="col-md-12 d-flex flex-center">
                                <i class="fa-regular fa-face-frown-open text-danger fs-4x"></i>
                                
                            </div>

                            <div class="text-center mt-2">
                                <h4 class="fs-2hx text-gray-900 mb-5">Oyun Mevcut Değil!</h4>
                            </div>

                        <!--end::Col-->
                        ';
            echo $audioBookList;
        } else {

            foreach ($audioBookInfo as $key => $value) {

                $audioBookList = '
                            <!--begin::Col-->
                            <div class="col-md-4">
                                <!--begin::Publications post-->
                                <div class="card-xl-stretch me-md-6">
                                    <!--begin::Overlay-->
                                    <a class="d-block overlay mb-4" href="oyun-oyna/' . $value['slug'] . '">
                                        <!--begin::Image-->
                                        <div class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-175px" style="background-image:url(\'assets/media/games/' . $value['cover_img'] . '\')"></div>
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
                                        <a href="oyun-oyna/' . $value['slug'] . '" class="fs-4 text-gray-900 fw-bold text-hover-primary text-gray-900 lh-base">' . $value['name'] . '</a>
                                        <!--end::Title-->
                                        <!--begin::Text-->
                                        <div class="fw-semibold fs-5 text-gray-600 text-gray-900 mt-3 mb-5"></div>
                                        <!--end::Text-->
                                    </div>
                                    <!--end::Body-->
                                </div>
                                <!--end::Publications post-->
                            </div>
                            <!--end::Col-->
                    ';
                echo $audioBookList;
            }
        }
    }

    // Get WrittenBook Image For Students

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
                            <img src="assets/media/sesli-kitap/sesli-kitap.jpg" alt=" kitap Icon"
                                class="img-fluid"
                                style="width: 90px; height: 66px; object-fit: contain;">
                        </div>
                        <div>
                            <h1 class="fs-3 fw-bold text-dark mb-0 ml-2" style="margin-left: 20px;">  Kitaplar </h1>
                        </div>
                    </div>
                </header>';

        echo $view;

    }

    public function getHeaderImageInAudioBookStu()
    {

        $link = "$_SERVER[REQUEST_URI]";

        $active_slug = htmlspecialchars(basename($link, ".php"));

        $audioBookInfo = $this->getOneWrittenBook($active_slug);

        /* $audioBookInfo = $this->getAudioBooksList(); */
        $view = '
                <header class="container-fluid py-3 d-flex justify-content-between align-items-center"
                    style="
                        background-color: #e6e6fa !important;
                        margin-bottom: 40px !important;
                        margin-top: -45px !important;
                        border-top: 5px solid #d22b2b !important;
                        border-bottom: 5px solid #d22b2b !important;
                        height:85px;
                        margin-left:-10px
                    ">
                    <div class="d-flex align-items-center">
                        <div class="">
                            <img src="assets/media/sesli-kitap/' . $audioBookInfo['cover_img'] . '" alt="' . $audioBookInfo['book_name'] . ' Icon"
                                class="img-fluid"
                                style="width: 90px; height: 90px; object-fit: contain;">
                        </div>
                        <div>
                            <h1 class="fs-3 fw-bold text-dark mb-0 ml-2" style="margin-left: 20px;">' . $audioBookInfo['book_name'] . '</h1>
                        </div>
                    </div>
                </header>';

        $view .= '
            <div class="position-relative mb-17 " style="width: 200px; height: 300px; ">
                <div class="overlay overlay-show w-100 h-100 position-relative">
                    <div id="soundcloud-thumbnail"
                        class="card-rounded"
                        style="width: 100%; height: 100%; background-image: url(\'assets/media/sesli-kitap/' . $audioBookInfo['cover_img'] . '\'); background-size: contain; background-repeat: no-repeat; background-position: center; border-radius: 12px; position: relative; z-index: 1;">
                    </div>
                    <div class="overlay-layer rounded bg-black"
                        style="opacity: 0.2; position: absolute; top: 0; left: 0; width: 100%; height: 100%;  z-index: 2;">
                    </div>
                </div>
                <div class="position-absolute text-white mb-8 ms-10 bottom-0" style="z-index: 3;">
                    <h3 class="text-white fs-2qx fw-bold mb-3 m"></h3>
                </div>
            </div>
        ';
        echo $view;

    }

    // Get WrittenBooks Topics Sidebar For Students

    public function getSidebarTopicsStu()
    {
        $link = "$_SERVER[REQUEST_URI]";

        $active_slug = htmlspecialchars(basename($link, ".php"));

        $audioBookInfo = $this->getSameWrittenBooks($active_slug);

        $audioBookList = '<div class="card-body">
                        <!--begin::Top-->
                        <div class="mb-7">
                            <!--begin::Title-->
                            <h2 class="fs-1 text-gray-800 w-bolder mb-6">Diğer Üniteler</h2>
                            <!--end::Title-->
                        </div>
                        <!--end::Top-->
                        <!--begin::Item-->';

        foreach ($audioBookInfo as $key => $value) {

            $audioBookList .= '
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

        $audioBookList .= '
                    <!--end::Item-->
                </div>
            ';
        echo $audioBookList;
    }

    // Get WrittenBook List For Students

    public function getWrittenBookListStu()
    {
        $link = "$_SERVER[REQUEST_URI]";

        $active_slug = htmlspecialchars(basename($link, ".php"));

        $audioBookInfo = $this->getWrittenBooksList($active_slug);

        $dateFormat = new DateFormat();

        foreach ($audioBookInfo as $key => $value) {

            $audioBookList = '
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
            echo $audioBookList;
        }
    }

    // Show WrittenBook

    public function showOneWrittenBook($slug)
    {

        $audioBookInfo = $this->getOneWrittenBook($slug);

        $gameHtml = '
                <div class="mb-3">
                    <h1 class="h3 d-inline align-middle">Böyle bir Oyun mevcut değil.</h1>
                </div>
        ';

        $gameHtml = '
                <tr>
                    <td>
                        <a href="./oyun-detay/' . $audioBookInfo['slug'] . '" class="cursor-pointer symbol symbol-90px symbol-lg-90px">
                            <img src="assets/media/sesli-kitap/' . $audioBookInfo['cover_img'] . '" alt="' . $audioBookInfo['book_name'] . '">
                        </a>
                    </td>
                    <td>
                        <a href="./oyun-detay/' . $audioBookInfo['slug'] . '" class="text-gray-800 text-hover-primary mb-1 fw-bold">' . $audioBookInfo['book_name'] . '</a>
                    </td>
                    <td><span class="text-gray-800">' . ($audioBookInfo['class_name'] ?? '-') . '</span></td>
                    <td><span class="text-gray-800">' . ($audioBookInfo['lesson_name'] ?? '-') . '</span></td>
                    <td><span class="text-gray-800">' . ($audioBookInfo['unit_name'] ?? '-') . '</span></td>
                    <td><span class="text-gray-800">' . ($audioBookInfo['topic_name'] ?? '-') . '</span></td>
                    <td><span class="text-gray-800">' . ($audioBookInfo['subtopic_name'] ?? '-') . '</span></td>
                    <td class="text-end">
                        <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                            İşlemler
                            <i class="ki-duotone ki-down fs-5 ms-1"></i>
                        </a>
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 
                            menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                            <div class="menu-item px-3">
                                <a href="./okul-detay/' . $audioBookInfo['slug'] . '" class="menu-link px-3">Görüntüle</a>
                            </div>
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3" data-kt-customer-table-filter="delete_row">Pasif Yap</a>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="8">
                        <div class="card mt-2">
                            <div class="card-body">
                                <div class="ratio ratio-16x9">
                                    ' . $audioBookInfo['book_url'] . '
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>';
        echo $gameHtml;
    }

    // Update WrittenBook

    public function updateOneWrittenBook($slug)
    {

        $audioBookInfo = $this->getOneWrittenBook($slug);

        foreach ($audioBookInfo as $value) {

            $audioBookList = '
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
        echo $audioBookList;
    }

    // List of WrittenBook

    public function showWrittenBookList()
    {

        $audioBookInfo = $this->getWrittenBooks();

        $audioBookList = '';

        $divStart = '<div class="row"><div class="overflow-auto col-12 col-lg-2" style="max-height: 150px">';

        $divEnd = '</div></div>';

        foreach ($audioBookInfo as $value) {

            $parentId = $value['id'];

            $classForms = '<div class="form-check">
                                            <input class="form-check-input" name="category[]" type="checkbox" value="' . $value['id'] . '" id="' . $value['slug'] . '">
                                                <label class="form-check-label" for="' . $value['slug'] . '">
                                                ' . $value['catName'] . '
                                                </label>
                                        </div>';



            $audioBookList .= $classForms;
        }


        echo $divStart . $audioBookList . $divEnd;
    }

    // Show Topic For Students

    public function showWrittenBookStudent()
    {
        $link = "$_SERVER[REQUEST_URI]";

        $active_slug = htmlspecialchars(basename($link, ".php"));

        $audioBookInfo = $this->getOneWrittenBook($active_slug);

        foreach ($audioBookInfo as $key => $value) {

            //$youtubeID = $this->getYouTubeVideoId($value['video_url']);

            $audioBookList = '
                    <!--begin::Description-->
                    <div class="m-0">
                        <!--begin::Text-->
                        <p class="fw-semibold fs-4 text-gray-600 mb-2">' . $value['game_url'] . '</p>
                        <!--end::Text-->
                    </div>
                ';
            echo $audioBookList;
        }
    }

    // Get WrittenBooks For Topic List

    public function showWrittenBookForTopicList($class, $lessons)
    {

        $audioBookInfo = $this->getWrittenBookForTopicList($class, $lessons);

        $units = array();

        foreach ($audioBookInfo as $key => $value) {

            $units[] = array("id" => $value["id"], "text" => $value["name"]);
        }


        echo json_encode($units);
    }
}

