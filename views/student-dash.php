<?php

include_once "classes/dateformat.classes.php";
include_once "classes/dashes.classes.php";
include_once "classes/lessons.classes.php";
include_once "classes/todayword.php";
include_once "classes/doyouknow.php";
require_once "classes/student.classes.php";
require_once "classes/student-view.classes.php";
require_once "classes/content-tracker.classes.php";

$contentObj = new ContentTracker();

$resultProfile = $contentObj->getSchoolContentAnalyticsOverall($_SESSION['id']);
$resultProfileW = ($resultProfile == null) ? 0 : $resultProfile;
$resultProfileT = ($resultProfile == null) ? '-' : $resultProfile;

$privateLessons = new Dashes();
$showPrivateLessons = $privateLessons->getPrivateLessonList();

$student = new ShowStudent();

$lessons = new Lessons();

$dateFormat = new DateFormat();

$getLessons = $lessons->getLessons();

$dash = new DashesStudent();

$getTests = $dash->getTestsStudent();

$testsCount = count($getTests);

$getHomeworks = $dash->getHomeworksStudent();

$bugun = new DateTime();

$haftanin_gunu_sayisi = (int) $bugun->format('w'); // 0 (Pazar) - 6 (Cumartesi)

if ($haftanin_gunu_sayisi === 0) { // Bugün Pazar ise
    $haftanin_sonu = clone $bugun; // Haftanın sonu bugün
    $kalan_gun_sayisi = 0; // Kalan gün 0
} else {
    // Bugün Pazar değilse, haftanın sonu olan Pazar'ı bul
    $haftanin_sonu = clone $bugun; // $bugun nesnesini değiştirmemek için klonluyoruz
    $haftanin_sonu->modify('next sunday');

    // İki tarih arasındaki farkı hesapla
    $fark = $haftanin_sonu->diff($bugun);
    $kalan_gun_sayisi = $fark->days;
}

$todayWordObj = new TodayWord();
$todaysWord = $todayWordObj->getTodaysOrRandomWord($_SESSION['school_id'], $_SESSION['class_id']);


$knowObj = new DoYouKnow();
$todaysKnow = $knowObj->getTodaysOrRandomKnow($_SESSION['school_id'], $_SESSION['class_id']);

?>
<div id="kt_app_content_container" class="app-container container-fluid student-dashboard"
    style="padding-right: 0px !important;padding-left: 0px !important;">
    <!--begin::Row-->
    <div class="row col-lg-12" style="align-items: baseline;">
        <div class="row gx-5 gx-xl-9 col-lg-9" style="align-items: baseline;padding-right: 0px;">

            <!--begin::Col-->
            <div class="col-xxl-12 mb-1 mb-xl-1" style="padding-right: 0px;">
                <!--begin::Chart widget 8-->
                <div class="card card-flush h-xl-100">
                    <!--begin::Header-->
                    <div class="card-header pt-0" style="padding: 10px;">
                        <!--begin::Title-->
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-900"><i class="fa-solid fa-star me-2 fs-1"></i>
                                Haftalık Görevler</span>
                        </h3>


                        <a href="ogrenci-haftalik-gorev"><button type="button"
                                class="btn btn-primary btn-sm mt-5">Tümü</button></a>

                        <!--end::Title-->
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body pt-0" style="padding: 0px;">
                        <!--begin::Tab content-->
                        <div class="tab-content">
                            <!--begin::Tab pane-->
                            <div class="tab-pane fade active show" id="kt_chart_widget_8_month_tab" role="tabpanel">
                                <!--begin::Table container-->
                                <div class="table-responsive">
                                    <!--begin::Table-->
                                    <div
                                        style="display: flex; justify-content: space-around; width: 100%; padding: 5px 0;">
                                        <?php
                                        $i = 0;
                                        foreach ($getLessons as $value) {

                                            $classIds = $value['class_id'];

                                            $pieces = explode(";", $classIds);

                                            if (in_array($_SESSION['class_id'], $pieces)) {

                                                echo '<div class="position-relative" style="flex: 1; text-align: center; padding: 10px; border: 1px solid #eee; margin: 0 5px; border-radius: 5px; min-width: 180px; ">';

                                                //echo '<div class="mt-5"><span class="text-gray-800 fw-bold fs-4"> ' . $value['name'] . '</span></div>';
                                        
                                                $getUnits = $dash->getUnitsDash($value['id']);

                                                if (empty($getUnits)) {
                                                    echo '<p class="mb-0"><img class="img-fluid" src="assets/media/units/uniteDefault.jpg"></p>';
                                                    echo '<div class="mt-3"><span class="text-gray-600 fw-bold fs-6" style="font-family: Comic Relief, system-ui; color: #2b8c01 !important;"> ' . $value['name'] . ' dersine ait Ünite bulunamadı!</span></div>';
                                                } else {
                                                    foreach (array_slice($getUnits, 0, 1) as $unit) {
                                                        echo '<p class="mb-0"><img class="img-fluid" src="assets/media/units/' . $unit['photo'] . '"></p>';
                                                        echo '<div class="mt-3"><span class="text-gray-600 fw-bold fs-6"><a style="font-family: Comic Relief, system-ui; color: #2b8c01 !important;" href="unite/' . $unit['slug'] . '">' . $value['name'] . ' - ' . $unit['name'] . '</a></span></div>';
                                                        echo '<div class="text-center mb-2 kalangun"> <i class="fa-solid fa-hourglass""></i> ' . $kalan_gun_sayisi . ' gün</div>';
                                                        /* $getTopics = $dash->getTopicsDash($value['id'], $unit['id']);
                                                    if (empty($getTopics)) {
                                                        echo '<span class="text-gray-600 fw-bold fs-6"> - Bu üniteye ait konu bulunamadı!</span>';
                                                    } else {
                                                        foreach (array_slice($getTopics, 0, 1) as $topic) {
                                                            echo '<div class="mt-1"><span class="text-gray-600 fw-bold fs-6">Konu: <a href="konu/' . $topic['slug'] . '">' . $topic['name'] . '</a></span></div>';
                                                            $getSubTopics = $dash->getSubTopicsDash($value['id'], $unit['id'], $topic['id']);
                                                            if (empty($getSubTopics)) {
                                                                echo '<span class="text-gray-600 fw-bold fs-6"> - Bu konuya ait alt konu bulunamadı!</span>';
                                                            } else {
                                                                foreach (array_slice($getSubTopics, 0, 1) as $subTopic) {
                                                                    echo '<div class="mt-1"><span class="text-gray-600 fw-bold fs-6">Alt Konu: <a href="alt-konu/' . $subTopic['slug'] . '">' . $subTopic['name'] . '</a></span></div><hr>';
                                                                }
                                                            }
                                                        }
                                                    } */
                                                    }
                                                }


                                                echo '</div>';
                                            }
                                            $i++;
                                            if ($i === 3) { // Sayıcı 3'e ulaştığında
                                                break; // Döngüyü durdur
                                            }
                                        } ?>
                                    </div>
                                    <!--end::Table-->
                                </div>
                                <!--end::Table container-->
                            </div>
                            <!--end::Tab pane-->
                        </div>
                        <!--end::Tab content-->
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Chart widget 8-->
            </div>
            <!--end::Col-->

            <!--begin::Col-->
            <div class="col-xxl-12 mb-1 mb-xl-1" style="padding-right: 0px;">
                <!--begin::Chart widget 8-->
                <div class="card card-flush h-xl-100">
                    <!--begin::Header-->
                    <div class="card-header pt-5" style="padding: 10px;">
                        <!--begin::Title-->
                        <h5 class="card-title d-flex align-items-center">
                            <i class="fa-solid fa-person-chalkboard me-2 fs-1"></i> <span
                                class="card-label fw-bold text-gray-900">Dersler</span>
                        </h5>

                        <!--end::Title-->
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body pt-0" style="padding: 0px;">
                        <!--begin::Tab content-->
                        <div class="tab-content">
                            <!--begin::Tab pane-->
                            <div class="tab-pane fade active show" id="kt_chart_widget_8_month_tab" role="tabpanel">
                                <!--begin::Table container-->
                                <div class="table-responsive">
                                    <!--begin::Table-->

                                    <div
                                        style="display: flex; justify-content: space-around; width: 100%; padding: 5px 0;">
                                        <?php

                                        $styles = ["#0fadf54a", "#3571804a", "#4d38f74a", "#004eb04a", "#f9f9f94a", "#fa60004a", "#2b8c014a", "#fcecb24a"];
                                        $styleIndex = 0;

                                        foreach ($getLessons as $value) {

                                            $style = $styles[$styleIndex % count($styles)];

                                            if ($value['icons'] != NULL) {
                                                $ico = "<img src='assets/media/icons/dersler/" . $value['icons'] . "'>";
                                            } else {
                                                $ico = "<div class='symbol-label fs-2 fw-semibold bg-$style text-inverse-$style'>" . mb_substr($value['name'], 0, 1, 'UTF-8') . "</div>";
                                            }

                                            if ($value['bg-color'] != NULL) {
                                                $style = '#' . $value['bg-color'];
                                            }

                                            $classIds = $value['class_id'];

                                            $pieces = explode(";", $classIds);

                                            if (in_array($_SESSION['class_id'], $pieces)) {

                                                echo '<div style="flex: 1; text-align: center; padding: 10px; border: 1px solid #eee; margin: 0 5px; border-radius: 5px; background-color: ' . $style . ';  min-width: 150px;">';

                                                $class_id = $_SESSION['class_id'];
                                                $school_id = $_SESSION['school_id'];
                                                $lesson_id = $value['id'];

                                                $unitData = $dash->getUnits($lesson_id, $class_id, $school_id);
                                                $unitCount = count($unitData);

                                                echo '
                                                <a href="ders/' . $value['slug'] . '">
                                                <div class="symbol symbol-40px">
                                                    ' . $ico . '
                                                </div>
                                                </a>';

                                                echo '<a href="ders/' . $value['slug'] . '"><div class="mt-5"><p class="text-gray-800 fw-bold fs-4"> ' . $value['name'] . '</p>
                                                <p class="text-gray-800 fw-bold fs-6"> ' . $unitCount . ' Ünite</p></div></a>';


                                                echo '</div>';
                                            }
                                            $styleIndex++;
                                        } ?>
                                    </div>

                                    <!--end::Table-->
                                </div>
                                <!--end::Table container-->
                            </div>
                            <!--end::Tab pane-->
                        </div>
                        <!--end::Tab content-->
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Chart widget 8-->
            </div>
            <!--end::Col-->

            <!--begin::Col-->
            <div class="col-xxl-6 mb-1 mb-xl-1" style="padding-right: 0px;">
                <!--begin::Chart widget 8-->
                <div class="card card-flush h-xl-100">
                    <!--begin::Header-->
                    <div class="card-header pt-5" style="padding: 10px;">
                        <!--begin::Title-->
                        <h5 class="card-title d-flex align-items-center">
                            <i class="fa-solid fa-chart-line me-2 fs-1"></i> <span
                                class="card-label fw-bold text-gray-900">Başarını Arttır</span>
                        </h5>

                        <!--end::Title-->
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body pt-0" style="padding: 0px;">
                        <!--begin::Tab content-->
                        <div class="tab-content">
                            <!--begin::Tab pane-->
                            <div class="tab-pane fade active show" id="kt_chart_widget_8_month_tab" role="tabpanel">
                                <!--begin::Table container-->
                                <div class="table-responsive">
                                    <!--begin::Table-->

                                    <div
                                        style="display: flex; justify-content: space-around; width: 100%; padding: 5px 0;">
                                        <?php
                                        /*
                                        $styles = ["#0fadf54a", "#3571804a", "#4d38f74a", "#004eb04a", "#f9f9f94a", "#fa60004a", "#2b8c014a", "#fcecb24a"];
                                        $styleIndex = 0;

                                        foreach ($getLessons as $value) {

                                            $style = $styles[$styleIndex % count($styles)];

                                            if ($value['icons'] != NULL) {
                                                $ico = "<img src='assets/media/icons/dersler/" . $value['icons'] . "'>";
                                            } else {
                                                $ico = "<div class='symbol-label fs-2 fw-semibold bg-$style text-inverse-$style'>" . mb_substr($value['name'], 0, 1, 'UTF-8') . "</div>";
                                            }

                                            if ($value['bg-color'] != NULL) {
                                                $style = '#' . $value['bg-color'];
                                            }

                                            $classIds = $value['class_id'];

                                            $pieces = explode(";", $classIds);

                                            if (in_array($_SESSION['class_id'], $pieces)) {

                                                echo '<div style="flex: 1; text-align: center; padding: 10px; border: 1px solid #eee; margin: 0 5px; border-radius: 5px; background-color: ' . $style . '">';

                                                $class_id = $_SESSION['class_id'];
                                                $school_id = $_SESSION['school_id'];
                                                $lesson_id = $value['id'];

                                                $unitData = $dash->getUnits($lesson_id, $class_id, $school_id);
                                                $unitCount = count($unitData);

                                                echo '
                                                <a href="ders/' . $value['slug'] . '">
                                                <div class="symbol symbol-40px">
                                                    ' . $ico . '
                                                </div>
                                                </a>';

                                                echo '<a href="ders/' . $value['slug'] . '"><div class="mt-5"><span class="text-gray-800 fw-bold fs-4"> ' . $value['name'] . '</span>
                                                <p class="text-gray-800 fw-bold fs-6"> ' . $unitCount . ' Ünite</p></div></a>';


                                                echo '</div>';
                                            }
                                            $styleIndex++;
                                        } */ ?>
                                    </div>

                                    <!--end::Table-->
                                </div>
                                <!--end::Table container-->
                            </div>
                            <!--end::Tab pane-->
                        </div>
                        <!--end::Tab content-->
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Chart widget 8-->
            </div>
            <!--end::Col-->

            <!--begin::Col-->
            <div class="col-xxl-6 mb-1 mb-xl-1" style="padding-right: 0px;">
                <!--begin::Chart widget 8-->
                <div class="card card-flush h-xl-100">
                    <!--begin::Header-->
                    <div class="card-header pt-5" style="padding: 10px;">
                        <!--begin::Title-->
                        <h5 class="card-title d-flex align-items-center">
                            <i class="fa-solid fa-comments me-2 fs-1"></i> <span class="card-label fw-bold"
                                style="font-family: Comic Relief, system-ui; color: #2b8c01 !important;">Soru-Cevap</span>
                        </h5>

                        <!--end::Title-->
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body pt-0" style="padding: 10px;">
                        <!--begin::Tab content-->
                        <div class="tab-content">
                            <!--begin::Tab pane-->
                            <div class="tab-pane fade active show" id="kt_chart_widget_8_month_tab" role="tabpanel">
                                <!--begin::Table container-->
                                <div class="table-responsive">
                                    <!--begin::Table-->

                                    <div
                                        style="display: flex; justify-content: space-around; width: 100%; padding: 5px 0;">
                                        <a href="cek-gonder">
                                            <p class="kalangun"
                                                style="font-family: Comic Relief, system-ui; color: #2b8c01 !important;">
                                                <b>Derslerle ilgili cevabını merak ettiğin sorularını sormayı unutma,
                                                    LineUp öğretmenleri sorularını senin için cevaplayacak.</b>
                                            </p>
                                        </a>
                                    </div>

                                    <!--end::Table-->
                                </div>
                                <!--end::Table container-->
                            </div>
                            <!--end::Tab pane-->
                        </div>
                        <!--end::Tab content-->
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Chart widget 8-->
            </div>
            <!--end::Col-->

            <!--begin::Col-->
            <?php if (count($showPrivateLessons) > 0) { ?>
                <div class="col-xxl-12 mb-1 mb-xl-1" style="padding-right: 0px;">
                    <!--begin::Chart widget 8-->

                    <div class="card card-flush h-xl-100">
                        <div class="card-header pt-0" style="padding: 10px;">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-gray-900">
                                    <i class="fa-solid fa-chalkboard-teacher me-2 fs-1"></i>
                                    Özel Dersler
                                </span>
                            </h3>
                        </div>

                        <div class="card-body p-3">


                            <div class="row g-3">
                                <?php foreach ($showPrivateLessons as $lesson): ?>
                                    <?php
                                    // Resim yolu (senin verdiğin sabit resim)
                                    $imagePath = 'includes/uploads/ozelders.png';
                                    // Eğer dinamik olacaksa örneğin $lesson['image'] gibi kullanırsın ve sanitize edersin.
                                    if (file_exists($imagePath)):
                                        $imgTag = '<img src="' . htmlspecialchars($imagePath, ENT_QUOTES, 'UTF-8') . '" alt="Özel Ders" class="card-img-top" style="object-fit: cover; height: 150px; width: 100%;">';
                                    else:
                                        // Fallback görsel yoksa
                                        $imgTag = '<div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height:150px;">Resim yok</div>';
                                    endif;
                                    ?>
                                    <div class="col-md-6 col-xl-4">
                                        <div class="card h-100 shadow-sm">
                                            <?= $imgTag ?>
                                            <div class="card-body d-flex flex-column">
                                                <h5 class="card-title">
                                                    <?= htmlspecialchars($lesson['title'], ENT_QUOTES, 'UTF-8') ?>
                                                </h5>
                                                <p class="card-text mb-1"><strong>Öğretmen:</strong>
                                                    <?= htmlspecialchars($lesson['extendedProps']['organizerName'], ENT_QUOTES, 'UTF-8') ?>
                                                </p>
                                                <p class="card-text mb-1"><strong>Tarih:</strong>
                                                    <?= date('d-m-Y H:i', strtotime($lesson['start'])) ?></p>
                                                <a href="<?= htmlspecialchars($lesson['zoom_join_url'], ENT_QUOTES, 'UTF-8') ?>"
                                                    class="btn btn-primary mt-auto btn-sm" target="_blank">Derse Katıl</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>


                        </div>
                    </div>

                </div>
            <?php } ?>
            <!--end::Col-->

        </div>

        <div class="gx-5 gx-xl-10 col-lg-3" style="padding-left: 0px; padding-right: 0px;">

            <!--begin::Col-->
            <div class="col-xxl-12 mb-1 ">
                <!--begin::Chart widget 8-->
                <div class="card card-flush">
                    <!--begin::Header-->
                    <div class="card-header pt-0" style="padding: 10px;">
                        <!--begin::Title-->
                        <h3 class="card-title d-flex align-items-center">
                            <i class="fa-solid fa-percent me-2 fs-1" style="transform: scaleX(-1)"></i> <span
                                class="card-label fw-bold text-gray-900">Ders Başarıların </span>
                        </h3>
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body pt-0" style="padding: 10px;">
                        <!--begin::Tab content-->
                        <div class="tab-content">
                            <!--begin::Tab pane-->
                            <div class="tab-pane fade active show" id="kt_chart_widget_8_month_tab" role="tabpanel">
                                <!--begin::Table container-->
                                <div class="table-responsive">
                                    <!--begin::Table-->
                                    <?php $student->showLessonsListForStudentDetailsDashboard($_SESSION['id'], $_SESSION['class_id'], $_SESSION['school_id']); ?>

                                </div>
                                <!--end::Table container-->
                            </div>
                            <!--end::Tab pane-->
                        </div>
                        <!--end::Tab content-->
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Chart widget 8-->
            </div>
            <!--end::Col-->

            <!--begin::Col-->
            <div class="col-xxl-12 mb-1">
                <!--begin::Chart widget 8-->
                <div class="card card-flush">
                    <!--begin::Header-->
                    <div class="card-header pt-5" style="padding: 10px;">
                        <!--begin::Title-->
                        <h3 class="card-title d-flex align-items-center">
                            <i class="fa-regular fa-comment me-2 fs-1" style="transform: scaleX(-1)"></i> <span
                                class="card-label fw-bold text-gray-900">Haftanın Kelimesi
                                "<?php echo $todaysWord['word'] ?>"</span>
                        </h3>
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body pt-0" style="padding: 10px;">
                        <!--begin::Tab content-->
                        <div class="tab-content">
                            <?php
                            $imagePath = "assets/media/today-word/" . $todaysWord['image'];
                            if (!empty($todaysWord['image']) && file_exists($imagePath)): ?>
                                <img src="<?php echo $imagePath; ?>" class="w-50 h-50 m-2"
                                    style="width:50px !important; height:50px !important; border:none !important;">
                            <?php endif; ?>

                            <!--begin::Tab pane-->
                            <div class="tab-pane fade active show" id="kt_chart_widget_8_month_tab" role="tabpanel">
                                <!--begin::Table container-->

                                <div class="table-responsive">
                                    <!--begin::Table-->
                                    <p class="kalangun">
                                        <?php echo $todaysWord['body'] ?>
                                    </p>

                                </div>
                                <!--end::Table container-->
                            </div>
                            <!--end::Tab pane-->
                        </div>
                        <!--end::Tab content-->
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Chart widget 8-->
            </div>
            <!--end::Col-->

            <!--begin::Col-->
            <div class="col-xxl-12 mb-5 mb-xl-10">
                <!--begin::Chart widget 8-->
                <div class="card card-flush">
                    <!--begin::Header-->
                    <div class="card-header pt-5" style="padding: 10px;">
                        <!--begin::Title-->
                        <h3 class="d-flex align-items-center">
                            <i class="fa-solid fa-graduation-cap me-2 fs-1" style="transform: scaleX(-1)"></i> <span
                                class="card-label fw-bold text-gray-900">Bunu Biliyor Musunuz?
                            </span>
                        </h3>
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body pt-0" style="padding: 10px;">
                        <?php
                        $imagePath = "assets/media/tdo-you-know/" . $todaysKnow['image'];
                        if (!empty($todaysKnow['image']) && file_exists($imagePath)): ?>
                            <img src="<?php echo $imagePath; ?>" class="w-50 h-50 m-2"
                                style="width:50px !important; height:50px !important; border:none !important;">
                        <?php endif; ?>
                        <!--begin::Tab content-->
                        <div class="tab-content">
                            <!--begin::Tab pane-->
                            <div class="tab-pane fade active show" id="kt_chart_widget_8_month_tab" role="tabpanel">
                                <!--begin::Table container-->

                                <div class="table-responsive">
                                    <!--begin::Table-->
                                    <p class="kalangun">
                                        <?php echo $todaysKnow['body'] ?>
                                    </p>

                                </div>
                                <!--end::Table container-->
                            </div>
                            <!--end::Tab pane-->
                        </div>
                        <!--end::Tab content-->
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Chart widget 8-->
            </div>
            <!--end::Col-->


            <?php
            $percentage = $resultProfileW;

            if ($percentage <= 20) {
                $image = "image-1.jpg";
            } elseif ($percentage <= 40) {
                $image = "image-2.jpg";
            } elseif ($percentage <= 60) {
                $image = "image-3.jpg";
            } elseif ($percentage <= 80) {
                $image = "image-4.jpg";
            } else {
                $image = "image-5.jpg";
            }
            ?>
            <div class="col-xxl-12 mb-1">


                <div class="text-center">
                    <img src="assets/media/dash/<?php echo $image; ?>" alt="Progress Image" class="img-fluid" />

                    <div class="d-flex align-items-center w-200px w-sm-300px flex-column mt-3">
                        <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                            <span class="fw-semibold fs-6 text-gray-500">Tamamlama Oranı</span>
                            <span class="fw-bold fs-6"><?= $resultProfileT ?>%</span>
                        </div>
                        <div class="h-5px mx-3 w-100 bg-light mb-3">
                            <div class="bg-success rounded h-5px" role="progressbar"
                                style="width: <?= $resultProfileW ?>%;" aria-valuenow="50" aria-valuemin="0"
                                aria-valuemax="100"></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!--end::Row-->
</div>