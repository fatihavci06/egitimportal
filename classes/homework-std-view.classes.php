<?php
if (session_status() == PHP_SESSION_NONE) {
    // Oturum henüz başlatılmamışsa başlat
    session_start();
}

class ShowHomeworkContents extends GetStudentHomework
{

    // Get Homework List

    public function getHomeworkListForStudent()
    {

        $homeworkInfo = $this->getAllHomeworkForStudent();

        $dateFormat = new DateFormat();

        foreach ($homeworkInfo as $key => $value) {


            // if ($value['active'] == 1) {
            //     $aktifArama = 'data-filter="Aktif"';
            //     $aktifYazi = '<span class="badge badge-light-success">Aktif</span>';
            // } else {
            //     $aktifArama = 'data-filter="Passive"';
            //     $aktifYazi = '<span class="badge badge-light-danger">Pasif</span>';
            // }

            // $alter_button = $value['active'] ? "Pasif Yap" : "Aktif Yap";

            // if ($_SESSION['role'] == 4) {
            //     $passiveButton = '';
            // } else {
            //     $passiveButton = '
            //                     <!--begin::Menu item-->
            //                     <div class="menu-item px-3">
            //                         <a href="#" class="menu-link px-3" data-kt-customer-table-filter="delete_row">' . $alter_button . '</a>
            //                     </div>
            //                     <!--end::Menu item-->';
            // }

            $subTopicName = $value['subTopicName'] ?? '-';

            $homeworkListForStudents = '
                    <tr>
                        <td data-file-id="' . $value['id'] . '">
                            <a href="./ogrenci-odev-detay/' . $value['slug'] . '" class="text-gray-800 text-hover-primary mb-1">' . $value['title'] . '</a>
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
                                    <a href="./ogrenci-odev-detay/' . $value['slug'] . '" class="menu-link px-3">Görüntüle</a>
                                </div>
                                <!--end::Menu item-->
                            </div>
                            <!--end::Menu-->
                        </td>
                    </tr>
                ';
            echo $homeworkListForStudents;
        }
    }


    public function getHeaderImageStu()
    {
        $link = "$_SERVER[REQUEST_URI]";

        $active_slug = htmlspecialchars(basename($link, ".php"));

        $contentInfo = $this->getHomeworkContentIdBySlug($active_slug);

        if (empty($contentInfo)) {
            $homeworkListForStudents = header("Location: http://localhost/lineup_campus/404.php"); // 404 sayfasına yönlendir
            echo $homeworkListForStudents;
            return;
        }

        $homeworkListForStudents = '
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
                            <h3 class="text-white fs-2qx fw-bold mb-3 m">' . $contentInfo['summary'] . '</h3>
                            <!--end::Title-->
                        </div>
                        <!--end::Heading-->
                    </div>
                ';
        echo $homeworkListForStudents;
    }

    // Show Homework Details

    public function showHomeworkDetailForStudent($slug)
    {
        $contentId = $this->getHomeworkContentIdBySlug($slug);

        $contentInfo = $this->getAllHomeworkContentDetailsById($contentId['id']);

        $dateFormat = new DateFormat();

        if (count($contentInfo) == 0) {
            $homeworkListForStudents = header("Location: http://localhost/lineup_campus/404.php"); // 404 sayfasına yönlendir
            echo $homeworkListForStudents;
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
            $contentFiles = $this->getHomeworkContentFilesById($contentId['id']);
            $wordwallFiles = $this->getHomeworkContentWordwallsById($contentId['id']);
            $videoFiles = $this->getHomeworkContentVideosById($contentId['id']);

            $content = '';

            // Check if there are any files
            if (count($contentFiles) > 0) {
                $content = '';
                foreach ($contentFiles as $file) {
                    $dosyaUzantisi = pathinfo($file['file_path'], PATHINFO_EXTENSION);
                    $dosyaUzantisi = strtolower($dosyaUzantisi);
                    $izinVerilenUzantilar = ['pdf', 'pptx', 'xlsx', 'xls', 'csv'];
                    if (in_array($dosyaUzantisi, $izinVerilenUzantilar)) {
                        $content .= '<div class="mb-3"><h3>' . $file['description'] . '</h3></div>';
                        $content .= '<div class="mb-10"><a href="' . $file['file_path'] . '" download class="btn btn-primary btn-sm" target="_blank"> <i class="bi bi-download"></i> Dosyayı İndir </a></div>';
                    } else {
                        $content .= '<div class="mb-3"><h3>' . $file['description'] . '</h3></div>';
                        $content .= '<div class="mb-10"><img src="' . $file['file_path'] . '" class="img-fluid"></div>';
                    }
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
                    $vimeoEmbedCode = $this->generateVimeoIframe($videoUrl, $videoId, $video_timestamp);
                    $content .= '<div class="mb-3">' . $vimeoEmbedCode . '</div>';
                }
            }
        }

        $homeworkListForStudents = '
                    <!--begin::Card header-->
                        <div class="card-header border-0">
                            <!--begin::Card title-->
                            <div class="card-title">
                                <h2>' . $contentInfo['title'] . '</h2>
                            </div>
                            <!--end::Card title-->
                        </div>
                        <div class="card-header border-0">
                            <!--begin::Card title-->
                            <div class="card-title" style="height: 20px !important">
                                <p>' . $contentInfo['summary'] . '</p>
                            </div>
                            <!--end::Card title-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0 pb-5">

                            ' . $content . '

                        </div>
                    <!--end::Card body-->
            ';

        echo $homeworkListForStudents;
    }
}