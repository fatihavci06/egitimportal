<style>
    .image-container {
        transition: transform 0.2s ease-in-out;
    }

    .image-container:hover {
        transform: scale(1.05);
    }

    /* Mobil uyumluluk */
    @media (max-width: 768px) {
        .display-4 {
            font-size: 2.5rem !important;
        }

        .image-container img {
            max-height: 150px !important;
        }

        .bg-light-primary {
            padding: 1rem !important;
        }

        .fs-3 {
            font-size: 1.5rem !important;
        }
    }
</style>
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
                <div class="card card-flush shadow-lg border-2  h-100">
                    <div class="card-header  border-bottom border-primary border-3">
                        <h2 class="card-title d-flex align-items-center flex-wrap">
                            <i class="fa-solid fa-graduation-cap me-3 fs-3 text-info"></i>
                            <span class="card-label fw-bolder text-gray-800">Haftanın Kelime Keşfi</span>
                        </h2>
                    </div>
                    <div class="card-body p-5">
                        <?php if (!empty($todaysWord['word'])): ?>
                            <div class="row g-5 align-items-center">

                                <div class="col-lg-12 order-lg-1 order-2" style="margin-top: -16px;">
                                    <div class="mb-2">
                                        <h3 style="font-size:13px; background-color:#ed5606; padding:6px 12px; border-radius:8px; display:inline-block;color: #fff!important;"
                                            class="display-3 fw-bolder  mb-2">
                                            <?php echo htmlspecialchars($todaysWord['word']) ?>
                                        </h3>
                                    </div>
                                    <?php if (!empty($todaysWord['body'])): ?>
                                        <div class="bg-light-primary rounded-3 p-4">
                                            <p class="text-gray-700 fw-normal fs-5 lh-base mb-0" style="font-size:12px!important">
                                                <i class="fa-solid fa-quote-left text-primary me-2"></i>
                                                <?php echo nl2br(htmlspecialchars($todaysWord['body'])) ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-lg-12 text-center order-lg-2 order-1">
                                    <?php if (!empty($todaysWord['image'])): ?>
                                        <div class="image-container mx-auto">
                                            <a
                                                href="#"
                                                data-bs-toggle="modal"
                                                data-bs-target="#wordImageModal"
                                                data-image-url="<?php echo htmlspecialchars($todaysWord['image']) ?>"
                                                class="d-inline-block position-relative image-link-hover"
                                                title="Görseli büyütmek için tıklayın">

                                                <img
                                                    src="<?php echo htmlspecialchars($todaysWord['image']) ?>"
                                                    alt="<?php echo htmlspecialchars($todaysWord['word']) ?> görseli"
                                                    class="img-fluid rounded-circle shadow-lg border border-5 border-light"
                                                    style="max-height: 200px; width: 200px; object-fit: cover; cursor: pointer;"
                                                    loading="lazy"
                                                    onerror="this.style.display='none';">
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <div class="p-4 bg-light-secondary rounded-3">
                                            <i class="fa-solid fa-feather-pointed fs-1 text-secondary mb-2"></i>

                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="text-center p-5 bg-light-danger rounded">
                                <i class="fa-solid fa-triangle-exclamation fs-2 text-danger mb-3"></i>
                                <p class="text-danger fs-5 mb-0">Bu haftaya ait keşfedilecek bir kelime henüz yayınlanmamıştır.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-xxl-12 mb-5 mb-xl-10">
                <div class="fact-wrapper p-4 mb-5" style="
        background: linear-gradient(135deg, #a8ff78 0%, #ffeb4d 100%);
        border-radius: 10px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    ">
                    <style>
                        .fact-wrapper::before {
                            content: '';
                            position: absolute;
                            top: 0;
                            left: 0;
                            width: 100%;
                            height: 100%;
                            background-image: radial-gradient(circle, rgba(0, 0, 0, 0.15) 1px, transparent 1px);
                            background-size: 20px 20px;
                            z-index: 1;
                            opacity: 0.8;
                        }

                        .image-frame-wrapper {
                            position: relative;
                            z-index: 3;
                            padding: 5px;
                            background-color: white;
                            border-radius: 10px;
                            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
                        }

                        .push-pin {
                            position: absolute;
                            width: 25px;
                            height: 25px;
                            background-color: #4CAF50;
                            border-radius: 50%;
                            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.4);
                            z-index: 10;
                        }

                        .push-pin.top-left {
                            top: -12px;
                            left: -12px;
                        }

                        .push-pin.top-right {
                            top: -12px;
                            right: -12px;
                        }

                        .fact-content-area {
                            position: relative;
                            z-index: 5;
                            margin-top: -35px;
                            /* Görselle çakışma (overlap) */
                        }

                        .fact-box {
                            background: linear-gradient(180deg, #ffffff 0%, #ffefe6 100%);
                            border-radius: 10px;
                            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
                            padding: 20px;
                            padding-left: 20px;
                            /* Karakterin kapladığı alan */
                            min-height: 150px;
                        }

                        .character-img {
                            position: absolute;
                            bottom: -5px;
                            /* Karakterin biraz dışarı taşması */
                            left: -35px;
                            /* Sol dışarı taşması */
                            height: 160px;
                            /* Görseldeki boyuta uygun ayar */
                            width: auto;
                            z-index: 6;
                        }

                        .fact-box h2 {
                            font-size: 26px;
                            font-weight: 900;
                            color: #663300;
                            /* Koyu kahve/kırmızımsı ton */
                            line-height: 1.1;
                        }

                        .fact-box p {
                            font-size: 13px;
                            font-weight: 500;
                            line-height: 1.5;
                        }
                    </style>

                    <div class="image-frame-wrapper mb-3" style="position: relative;">
                      
                        <div class="image-frame-inner">
                            <?php
                            $imagePath = $todaysKnow['image'] ?? 'image_fba94a.jpg';
                            ?>
                            <img src="<?php echo htmlspecialchars($imagePath) ?>"
                                alt="Deniz Yıldızı"
                                class="img-fluid rounded-2"
                                style="width: 100%; max-height: 200px; object-fit: cover;">
                        </div>
                    </div>

                    <div class="fact-content-area">
                        <div class="fact-box position-relative">



                            <div class="d-flex flex-column">
                                <h3 class="mt-0">Bunu Biliyor musunuz?</h3>

                                <p class="mb-3">
                                    <?php
                                    $content = $todaysKnow['body'] ?? 'Bir deniz yıldızının beyni yoktur. Ancak, kolları sayesinde yönünü bulabilir.';
                                    echo nl2br(htmlspecialchars($content));
                                    ?>
                                </p>


                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="wordImageModal" tabindex="-1" aria-labelledby="wordImageModalLabel" aria-hidden="true"
                data-bs-backdrop="true"
                data-bs-keyboard="true">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content bg-transparent border-0 shadow-none">
                        <div class="modal-header border-0 p-0 position-relative">
                            <button type="button" class="btn-close btn-close-white position-absolute end-0 me-4 mt-3"
                                data-bs-dismiss="modal" aria-label="Kapat" style="z-index: 1056; opacity: 1;"></button>
                        </div>
                        <div class="modal-body p-0 text-center">
                            <img id="modalWordImage" src="" alt="Büyütülmüş Kelime Görseli" class="img-fluid rounded shadow-lg" style="max-height: 90vh;">
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="knowImageModal" tabindex="-1" aria-labelledby="knowImageModalLabel" aria-hidden="true"
                data-bs-backdrop="true"
                data-bs-keyboard="true">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content bg-transparent border-0 shadow-none">
                        <div class="modal-header border-0 p-0 position-relative">
                            <button type="button" class="btn-close btn-close-white position-absolute end-0 me-4 mt-3"
                                data-bs-dismiss="modal" aria-label="Kapat" style="z-index: 1056; opacity: 1;"></button>
                        </div>
                        <div class="modal-body p-0 text-center">
                            <img id="modalKnowImage" src="" alt="Büyütülmüş Bilgi Görseli" class="img-fluid rounded shadow-lg" style="max-height: 90vh;">
                        </div>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {

                    // 1. Kelime Modal İşlemleri
                    const wordImageModal = document.getElementById('wordImageModal');
                    const modalWordImage = document.getElementById('modalWordImage');

                    if (wordImageModal && modalWordImage) {
                        wordImageModal.addEventListener('show.bs.modal', function(event) {
                            const link = event.relatedTarget;
                            const imageUrl = link.getAttribute('data-image-url');
                            const imageAlt = link.querySelector('img').alt;

                            modalWordImage.src = imageUrl;
                            modalWordImage.alt = imageAlt;
                        });
                    }

                    // 2. Bilgi Modal İşlemleri
                    const knowImageModal = document.getElementById('knowImageModal');
                    const modalKnowImage = document.getElementById('modalKnowImage');

                    if (knowImageModal && modalKnowImage) {
                        knowImageModal.addEventListener('show.bs.modal', function(event) {
                            const link = event.relatedTarget;
                            const imageUrl = link.getAttribute('data-image-url');
                            const imageAlt = link.querySelector('img').alt;

                            modalKnowImage.src = imageUrl;
                            modalKnowImage.alt = imageAlt;
                        });
                    }
                });
            </script>
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