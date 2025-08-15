<?php

include_once 'classes/topics.classes.php';

class ShowContents extends GetContent
{

    // Get Content List

    public function getContentsList()
    {

        $contentInfo = $this->getAllContents();

        $dateFormat = new DateFormat();

        foreach ($contentInfo as $key => $value) {

            $subTopicName = $value['subTopicName'] ?? '-';

            $contentList = '
                    <tr>
                        <td>
                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="1" />
                            </div>
                        </td>
                        <td>
                            <a href="./icerik-detay/' . $value['slug'] . '" class="text-gray-800 text-hover-primary mb-1">' . $value['title'] . '</a>
                        </td>
                        <td>
                           ' . $subTopicName . '
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
                        <td class="text-end">
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
                    </tr>
                ';
            echo $contentList;
        }
    }

    // Show Content Details

    public function showOneContent()
{
    $link = "$_SERVER[REQUEST_URI]";
    $active_slug = htmlspecialchars(basename($link, ".php"));
    $contentId = $this->getContentIdBySlug($active_slug);
    $contentInfo = $this->getAllContentDetailsById($contentId['id']);
    $dateFormat = new DateFormat();

    if (!$contentInfo) {
        header("Location: http://localhost/lineup_campus/404.php");
        exit;
    }

    // Önce contentFiles alalım (cover_img gösterebilmek için)
    $contentFiles = $this->getContentFilesById($contentId['id']);

    // Cover image sadece contentFiles varsa gösterilsin
    $coverImage = '';
    if (!empty($contentFiles) && !empty($contentInfo['cover_img'])) {
        $coverImage = '<div class="mb-4 text-start">
                   <img src="uploads/contents/' . $contentInfo['cover_img'] . '" 
                        style="height:200px; width:auto; object-fit:cover;" 
                        alt="Cover Image">
               </div>';
    }

    // Başlık ve açıklama
    $titleAndDesc = '<h2 class="fw-bold mb-2">' . htmlspecialchars($contentInfo['title']) . '</h2>';
    if (!empty($contentInfo['summary'])) {
        $titleAndDesc .= '<div class="mb-4">' . $contentInfo['summary'] . '</div>';
    }

    // Mevcut içerik
    if ($contentInfo['text_content'] != NULL) {
        $content = $contentInfo['text_content'];
    } else {
        $wordwallFiles = $this->getContentWordwallsById($contentId['id']);
        $videoFiles = $this->getContentVideosById($contentId['id']);

        $content = '';

        // Dosya içerikleri
        foreach ($contentFiles as $file) {
            $dosyaUzantisi = strtolower(pathinfo($file['file_path'], PATHINFO_EXTENSION));
            $content .= '<div class="mb-3"><h3>' . htmlspecialchars($file['description']) . '</h3></div>';

            if (in_array($dosyaUzantisi, ['pdf', 'pptx', 'xlsx', 'xls', 'csv'])) {
                $content .= '<div class="mb-10"><a data-file-id="' . $file["id"] . '" href="' . $file['file_path'] . '" download class="btn btn-primary btn-sm" target="_blank"> <i class="bi bi-download"></i> Dosyayı İndir </a></div>';
            } else {
                $content .= '<div class="mb-10"><img data-image-id="' . $file["id"] . '" src="' . $file['file_path'] . '" class="img-fluid"></div>';
            }
        }

        // WordWall içerikleri
        foreach ($wordwallFiles as $wordwall) {
            $content .= '<div class="mb-3"><h3>' . htmlspecialchars($wordwall['wordwall_title']) . '</h3></div>';
            $content .= '
            <div class="mb-3" style="position: relative; width: 100%; height: 100%;">
                <iframe src="' . $wordwall['wordwall_url'] . '" width="100%" height="500px"></iframe>
                <div data-wordwall-id="' . $wordwall["id"] . '" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 10;"></div>
            </div>';
        }

        // Video içerikleri
        require_once 'video-tracker.classes.php';
        $tracker = new VideoTracker();
        foreach ($videoFiles as $video) {
            $videoId = $video['id'];
            $video_timestamp = $tracker->getWatchProgress($_SESSION['id'], $videoId);
            $vimeoEmbedCode = $this->generateVimeoIframe($video['video_url'], $videoId, $video_timestamp);
            $content .= '<div class="mb-3">' . $vimeoEmbedCode . '</div>';
        }
    }

    // Tüm parçaları birleştir
    $contentList = '
    <div class="card-body pt-0 pb-5">
        ' . $coverImage . '
        ' . $titleAndDesc . '
        ' . $content . '
    </div>
    ';

    echo $contentList;
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


    // Show Contents For Student

    public function getContentListForStudent()
    {
        $testResults = new TestsResult();
        $subtopics = new SubTopics();
        $dateFormat = new DateFormat();

        $today = date('Y-m-d');
        $link = "$_SERVER[REQUEST_URI]";
        $active_slug = htmlspecialchars(basename($link, ".php"));

        $subtopic = new SubTopics();
        $subTopicInfo = $subtopic->getSubTopicIdBySlug($active_slug);
        $subTopicId = $subTopicInfo['id'];

        $getLessonId = $subTopicInfo['lesson_id'];
        $getClassId = $subTopicInfo['class_id'];
        $getUnitId = $subTopicInfo['unit_id'];
        $getTopicId = $subTopicInfo['topic_id'];
        $getOrderNo = $subTopicInfo['order_no'];

        if ($getOrderNo == 1) {
            $testQuery = 80 >= 80;
        } else {
            $getPreviousSubTopicId = $subtopics->getPrevSubTopicId($getOrderNo - 1, $getClassId, $getLessonId, $getUnitId, $getTopicId, $_SESSION['school_id']);
            $prevSubTopicId = $getPreviousSubTopicId['id'];
            $getTestResult = $testResults->getSubTopicTestResults($getUnitId, $getClassId, $getTopicId, $prevSubTopicId, $_SESSION['id']);
            $result = $getTestResult['score'] ?? 0;
            $testQuery = $result >= 80;
        }

        if (!($today >= $subTopicInfo['start_date'] or $testQuery)) {
            header("Location: ../404.php");
            exit();
        }

        if (empty($subTopicInfo)) {
            header("Location: ../404.php");
            exit();
        }

        $contentInfo = $this->getContentInfoByIdUnderSubTopic($subTopicId);

        if ($contentInfo == NULL) {
            echo '
            <!--begin::Col-->
            <div class="col-md-12 d-flex flex-center">
                <i class="fa-regular fa-face-frown-open text-danger fs-4x"></i>
            </div>
            <div class="text-center mt-2">
                <h4 class="fs-2hx text-gray-900 mb-5">İçerik Mevcut Değil!</h4>
            </div>
            <!--end::Col-->
        ';
        } else {
            foreach ($contentInfo as $key => $value) {
                $title = htmlspecialchars($value['title']);
                $summary = htmlspecialchars($value['summary']);
                $slug = htmlspecialchars($value['slug']);
                $coverImg = !empty($value['cover_img']) ? "uploads/contents/" . htmlspecialchars($value['cover_img']) : "assets/media/topics/konuDefault.jpg";

                echo '
    <!--begin::Col-->
    <div class="col-12 col-md-6 col-lg-4 mb-4">
        <div class="card h-100 shadow-sm border-0">
            <!-- Kapak Görseli -->
            <div class="d-flex justify-content-center align-items-center"
                 style="height: 180px; background-image: url(\'' . $coverImg . '\');
                        background-size: cover; background-position: center; border-top-left-radius: .375rem; border-top-right-radius: .375rem;">
            </div>
            <!-- Kart İçeriği -->
            <div class="card-body d-flex flex-column">
                <h5 class="card-title fw-bold text-dark mb-1" style="font-size: 16px;">' . $title . '</h5>
                <p class="card-text text-muted mb-3" style="font-size: 14px;">' . $summary . '</p>
                <!-- Alt Buton -->
                <div class="mt-auto d-flex justify-content-start">
                    <a href="icerik/' . $slug . '"
                       style="background-color: #2b8c01 !important; color: white !important; border: 1px solid #2b8c01 !important;
                              padding: 8px 28px; font-size: 14px; border-radius: 999px; text-decoration: none;"
                       onmouseover="this.style.backgroundColor=\'#ed5606\'"
                       onmouseout="this.style.backgroundColor=\'#2b8c01\'">
                        Aç
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!--end::Col-->
';
            }
        }
    }




    // Get Content Image For Students

    public function getHeaderImageStu()
    {
        $link = "$_SERVER[REQUEST_URI]";

        $active_slug = htmlspecialchars(basename($link, ".php"));

        $contentInfo = $this->getContentIdBySlug($active_slug);

        if (empty($contentInfo)) {
            $contentList = header("Location: http://localhost/lineup_campus/404.php"); // 404 sayfasına yönlendir
            echo $contentList;
            return;
        }

        $contentList = '
                    <div class="position-relative mb-17">
                        <!--begin::Overlay-->
                        <div class="overlay overlay-show">
                            <!--begin::Image-->
                            <div class="bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-250px" style="background-image:url(\'uploads/contents/' . $contentInfo['cover_img'] . '\')"></div>
                            <!--end::Image-->
                            <!--begin::layer-->
                            <div class="overlay-layer rounded bg-black" style="opacity: 0.4"></div>
                            <!--end::layer-->
                        </div>
                        <!--end::Overlay-->
                        <!--begin::Heading-->
                        <div class="position-absolute text-white mb-8 ms-10 bottom-0">
                            <!--begin::Title-->
                            <h3 class="text-white fs-2qx fw-bold mb-3 m">' . $contentInfo['title'] . '</h3>
                            <h3 class="text-white fs-1qx fw-bold mb-3 m">' . $contentInfo['summary'] . '</h3>
                            <!--end::Title-->
                        </div>
                        <!--end::Heading-->
                    </div>
                ';
        echo $contentList;
    }
}
