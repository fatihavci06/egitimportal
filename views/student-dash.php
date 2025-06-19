<?php

include_once "classes/dateformat.classes.php";
include_once "classes/dashes.classes.php";
include_once "classes/lessons.classes.php";

$lessons = new Lessons();

$dateFormat = new DateFormat();

$getLessons = $lessons->getLessons();

$dash = new DashesStudent();

$getTests = $dash->getTestsStudent();

$testsCount = count($getTests);

$getHomeworks = $dash->getHomeworksStudent();
?>
<div id="kt_app_content_container" class="app-container container-fluid">
    <!--begin::Row-->
    <div class="row gx-5 gx-xl-10">
        <!--begin::Col-->
        <div class="col-xxl-6 mb-5 mb-xl-10">
            <!--begin::Chart widget 8-->
            <div class="card card-flush h-xl-100">
                <!--begin::Header-->
                <div class="card-header pt-5">
                    <!--begin::Title-->
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-900">Haftalık Görevler</span>
                    </h3>

                    <img class="card-title align-items-start flex-column" src="assets/media/mascots/lineup-maskot-1.png" style="width: 100px; height: 100px; float: right" alt="Lineup Mascot">

                    <!--end::Title-->
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body pt-6">
                    <!--begin::Tab content-->
                    <div class="tab-content">
                        <!--begin::Tab pane-->
                        <div class="tab-pane fade active show" id="kt_chart_widget_8_month_tab" role="tabpanel">
                            <!--begin::Table container-->
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <?php foreach ($getLessons as $value) {

                                    $classIds = $value['class_id'];

                                    $pieces = explode(";", $classIds);

                                    if (in_array($_SESSION['class_id'], $pieces)) {

                                        echo  '<div class="mt-5"><span class="text-gray-800 fw-bold fs-4"> ' .  $value['name'] . '</span></div>';

                                        $getUnits = $dash->getUnitsDash($value['id']);

                                        if (empty($getUnits)) {
                                            echo '<span class="text-gray-600 fw-bold fs-6"> - Bu derse ait Ünite bulunamadı!</span>';
                                        } else {
                                            foreach (array_slice($getUnits, 0, 1) as $unit) {
                                                echo '<div class="mt-3"><span class="text-gray-600 fw-bold fs-6">Ünite: <a href="unite/' . $unit['slug'] . '">' . $unit['name'] . '</a></span></div>';
                                                $getTopics = $dash->getTopicsDash($value['id'], $unit['id']);
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
                                                }
                                            }
                                        }

                                        echo '<hr>';
                                    }
                                } ?>
                                <!--end::Table-->
                                <a href="ogrenci-haftalik-gorev"><button type="button" class="btn btn-primary btn-sm mt-5">Haftalık Görevler</button></a>
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
        <div class="col-xxl-6 mb-5 mb-xl-10">
            <!--begin::Chart widget 8-->
            <div class="card card-flush h-xl-100">
                <!--begin::Header-->
                <div class="card-header pt-5">
                    <!--begin::Title-->
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-900">Testler</span>
						<span class="fs-6 fw-semibold text-gray-500">En son eklenen 5 test</span>
                    </h3>
                    <img src="assets/media/mascots/maskot-erkek-2.png" class="card-title align-items-start flex-column" style="width: 100px; height: 100px; float: right; -webkit-transform: scaleX(-1); transform: scaleX(-1);" alt="Lineup Mascot">
                    <!--end::Title-->
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body pt-6">
                    <!--begin::Tab content-->
                    <div class="tab-content">
                        <!--begin::Tab pane-->
                        <div class="tab-pane fade active show" id="kt_chart_widget_8_month_tab" role="tabpanel">
                            <!--begin::Table container-->
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
                                    <!--begin::Table head-->
                                    <thead>
                                        <tr class="fs-7 fw-bold text-gray-500 border-bottom-0">
                                            <th class="p-0 pb-3 min-w-150px text-start">TEST ADI</th>
                                            <th class="p-0 pb-3 min-w-100px text-end">İŞLEM</th>
                                        </tr>
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody>
                                        <?php if (empty($getTests)) { ?>
                                            <tr>
                                                <td colspan="2" class="text-center"><span class="text-gray-600 fw-bold fs-6">Test Mevcut Değil!</span></td>
                                            </tr>
                                            <?php } else {
                                            foreach (array_slice($getTests, 0, 5) as $tests) {
                                                $getTestControl = $dash->getTestControl($tests['id']);
                                                $sonuc = $getTestControl['score'] ?? 0;
                                                if($sonuc >= 80) {
                                                    $status = '<span class="badge badge-success">Sonuç: ' . number_format($sonuc, 2) . ' Puan</span>';
                                                } else {
                                                    $fail = $getTestControl['fail_count'] ?? 0;
                                                    if($fail > 2) {
                                                        $status = '<span class="badge badge-light-danger">Sonuç: ' . number_format($sonuc, 2) . ' Puan</span>';
                                                    } else {
                                                        $status = '<button class="btn btn-primary start-exam-btn btn-sm" data-id="'. $tests['id'] .'">Teste Gir</button>';
                                                    }
                                                }
                                            ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="d-flex justify-content-start flex-column">
                                                                <span class="text-gray-600 fw-bold fs-6"><?php echo $tests['test_title'];  ?></span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-end">
                                                        <span class="text-gray-600 fw-bold fs-6">
                                                            <?php echo $status; ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                        <?php } 
                                        } ?>
                                    </tbody>
                                    <!--end::Table body-->
                                </table>
                                <a href="ogrenci-testler"><button type="button" class="btn btn-primary btn-sm mt-5">Tüm Testler</button></a>
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
        <div class="col-xxl-6 mb-5 mb-xl-10">
            <!--begin::Chart widget 8-->
            <div class="card card-flush h-xl-100">
                <!--begin::Header-->
                <div class="card-header pt-5">
                    <!--begin::Title-->
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-900">Ödevler</span>
						<span class="fs-6 fw-semibold text-gray-500">En son eklenen 5 ödev</span>
                    </h3>
                    <img src="assets/media/mascots/maskot-kiz-2.png" class="card-title align-items-start flex-column" style="width: 100px; height: 100px; float: right; -webkit-transform: scaleX(-1); transform: scaleX(-1);" alt="Lineup Mascot">
                    <!--end::Title-->
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body pt-6">
                    <!--begin::Tab content-->
                    <div class="tab-content">
                        <!--begin::Tab pane-->
                        <div class="tab-pane fade active show" id="kt_chart_widget_8_month_tab" role="tabpanel">
                            <!--begin::Table container-->
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
                                    <!--begin::Table head-->
                                    <thead>
                                        <tr class="fs-7 fw-bold text-gray-500 border-bottom-0">
                                            <th class="p-0 pb-3 min-w-150px text-start">ÖDEV ADI</th>
                                            <th class="p-0 pb-3 min-w-100px text-end">BİTİŞ TARİHİ</th>
                                        </tr>
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody>
                                        <?php if (empty($getTests)) { ?>
                                            <tr>
                                                <td colspan="2" class="text-center"><span class="text-gray-600 fw-bold fs-6">Test Mevcut Değil!</span></td>
                                            </tr>
                                            <?php } else {
                                            foreach (array_slice($getHomeworks, 0, 5) as $homeworks) {
                                            ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="d-flex justify-content-start flex-column">
                                                                <span class="text-gray-600 fw-bold fs-6"><a href="ogrenci-odev-detay/<?php echo $homeworks['slug']; ?>"><?php echo $homeworks['title'];  ?></a></span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-end">
                                                        <span class="text-gray-600 fw-bold fs-6">
                                                            <?php echo $dateFormat->changeDate($homeworks['end_date']); ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                        <?php } 
                                        } ?>
                                    </tbody>
                                    <!--end::Table body-->
                                </table>
                                <a href="ogrenci-odev-listele"><button type="button" class="btn btn-primary btn-sm mt-5">Tüm Ödevler</button></a>
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
    </div>
    <!--end::Row-->
</div>