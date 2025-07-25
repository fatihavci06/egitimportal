<?php

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

    public function showOneContentForUpdate($slug)
    {
        $contentId = $this->getContentIdBySlug($slug);

        $contentInfo = $this->getAllContentDetailsById($contentId['id']);

        $dateFormat = new DateFormat();

        if (count($contentInfo) == 0) {
            $contentList = header("Location: http://localhost/lineup_campus/404.php"); // 404 sayfasına yönlendir
            echo $contentList;
            return;
        }

        /*  $topicList = '
                 <div class="mb-3">
                     <h1 class="h3 d-inline align-middle">Böyle bir alt konu mevcut değil.</h1>
                 </div>
         '; */

        if ($contentInfo['text_content'] != NULL) {
            $content = $contentInfo['text_content'];
        } else {
            $contentFiles = $this->getContentFilesById($contentId['id']);
            $wordwallFiles = $this->getContentWordwallsById($contentId['id']);
            $videoFiles = $this->getContentVideosById($contentId['id']);

            $content = '';

            // Check if there are any files
            if (count($contentFiles) > 0) {
                $content = '';
                foreach ($contentFiles as $file) {
                    $content .= '<div class="mb-3"><h3>' . $file['description'] . '</h3></div>';
                    $content .= '<div class="mb-3"><img src="' . $file['file_path'] . '""></div>';
                }
            }

            // Check if there are any wordwall files
            if (count($wordwallFiles) > 0) {
                foreach ($wordwallFiles as $wordwall) {
                    $content .= '<div class="mb-3"><h3>' . $wordwall['wordwall_title'] . '</h3></div>';
                    $content .= '<div class="mb-3"><iframe src="' . $wordwall['wordwall_url'] . '" width="100%" height="500px"></iframe></div>';
                }
            }
            require_once 'video-tracker.classes.php';

            $tracker = new VideoTracker();
            // Check if there are any videos
            if (count($videoFiles) > 0) {
                foreach ($videoFiles as $video) {
                    $videoUrl = $video['video_url'];
                    $videoId = $video['id'];
                    $video_timestamp = $tracker->getWatchProgress($_SESSION['id'], $videoId);
                    $vimeoEmbedCode = $this->generateVimeoIframe($videoUrl, $videoId,$video_timestamp);
                    $content .= '
                            <div class="row mb-5">
                                <label class="required fs-6 fw-semibold mb-2" for="video_url">Video Link (Vimeo):</label>
                                <input type="text" class="form-control" name="video_url" data-file-id="' . $video['id'] . '" id="video_url" value="' . $video['video_url'] . '">
                            </div>';
                    /* $content .= '<div class="mb-3">' . $vimeoEmbedCode . '</div>'; */
                }
            }
        }

        $contentList = '
                        <!--begin::Input group-->
                            <div class="card-body mb-7">
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
                                        <div class="image-input-wrapper w-100px h-100px" style="background-image: url(\'./uploads/contents/' . $contentInfo['cover_img'] . '\')"></div>
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
                        <div class="card-body border-0">
                            <div class="row mb-5">
                                <label class="required fs-6 fw-semibold mb-2" for="title">İçerik Başlığı</label>
                                <input type="text" class="form-control" data-file-id="' . $contentInfo['id'] . '" id="title" name="title" value="' . $contentInfo['title'] . '">
                            </div>
                            <div class="row mb-5">
                                <label class="required fs-6 fw-semibold mb-2" for="summary">Kısa Açıklama</label>
                                <input type="text" class="form-control" id="summary" name="summary" value="' . $contentInfo['summary'] . '">
                            </div>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0 pb-5">

                            ' . $content . '

                        </div> 
            ';


        /*  $subTopicId = $value['id'];

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
             '; */

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

}
?>