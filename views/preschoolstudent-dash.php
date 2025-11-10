<?php

include_once "classes/dateformat.classes.php";
include_once "classes/dashes.classes.php";
include_once "classes/classes.classes.php";
include_once "classes/todayword.php";
include_once "classes/doyouknow.php";
require_once "classes/student.classes.php";
require_once "classes/student-view.classes.php";

$studentInfo = new Student();

$student = new ShowStudent();

$lessons = new Classes();

$dateFormat = new DateFormat();

if ($_SESSION['role'] == 10005) {
    $getPreSchoolStudent = $studentInfo->getPreSchoolStudentsInfoForParents($_SESSION['id']);
    $class_idsi = $getPreSchoolStudent[0]['class_id'];
    $school_idsi = $getPreSchoolStudent[0]['school_id'];
} else {
    $class_idsi = $_SESSION['class_id'];
    $school_idsi = $_SESSION['school_id'];
}

$getLessonsContent = $lessons->getMainSchoolContentListDashboard($class_idsi);

$getGamesContent = $lessons->getMainSchoolGamesListDashboard($class_idsi);

$getBooksContent = $lessons->getMainSchoolBooksListDashboard($class_idsi);

$getLessons = $lessons->getMainSchoolLessonListDashboard();

$dash = new DashesStudent();

$getTests = $dash->getTestsStudent();

$testsCount = count($getTests);

$getHomeworks = $dash->getHomeworksStudent();

$bugun = new DateTime();

$haftanin_gunu_sayisi = (int)$bugun->format('w'); // 0 (Pazar) - 6 (Cumartesi)

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
$todaysWord = $todayWordObj->getTodaysOrRandomWord($school_idsi, $class_idsi);


$knowObj = new DoYouKnow();
$todaysKnow = $knowObj->getTodaysOrRandomKnow($school_idsi, $class_idsi);


?>
<div id="kt_app_content_container" class="app-container container-fluid student-dashboard" style="padding-right: 0px !important;padding-left: 0px !important;">
    <!--begin::Row-->
    <div class="row col-lg-12">
        <div class="row gx-5 gx-xl-9 col-lg-9" style="align-items: baseline;padding-right: 0px;">

            <!--begin::Col-->
            <div class="col-xxl-12 mb-1 mb-xl-1" style="padding-right: 0px;">
                <!--begin::Chart widget 8-->
                <div class="card card-flush h-xl-100">
                    <!--begin::Header-->
                    <div class="card-header pt-0" style="padding: 10px;">
                        <!--begin::Title-->
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-900"><i class="fa-solid fa-star me-2 fs-1"></i> İçerikler</span>
                        </h3>


                        <a href="ana-okulu-icerikler"><button type="button"
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
                                    <div style="display: flex; justify-content: space-around; width: 100%; padding: 5px 0;">
                                        <?php
                                        $i = 0;
                                        foreach ($getLessonsContent as $value) {

                                            echo '<div class="position-relative" style="flex: 1; text-align: center; padding: 10px; border: 1px solid #eee; margin: 0 5px; border-radius: 5px; min-width: 180px; ">';
                                            echo '<p class="mb-0"><a style="font-family: Comic Relief, system-ui; color: #2b8c01 !important;" href="ana-okulu-icerikler-detay.php?id=' . $value['id'] . '"><img class="img-fluid" src="assets/media/units/uniteDefault.jpg"></a></p>';
                                            echo '<div class="mt-3"><span class="text-gray-600 fw-bold fs-6"><a style="font-family: Comic Relief, system-ui; color: #2b8c01 !important;" href="ana-okulu-icerikler-detay.php?id=' . $value['id'] . '">' . $value['subject'] . '</a></span></div>';
                                            echo '</div>';
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
                    <div class="card-header pt-0" style="padding: 10px;">
                        <!--begin::Title-->
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-900"><i class="fa-solid fa-gamepad me-2 fs-1"></i> Oyunlar</span>
                        </h3>


                        <a href="oyun"><button type="button"
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
                                    <div style="display: flex; justify-content: space-around; width: 100%; padding: 5px 0;">
                                        <?php
                                        $i = 0;
                                        foreach ($getGamesContent as $value) {

                                            echo '<div class="position-relative" style="flex: 1; text-align: center; padding: 10px; border: 1px solid #eee; margin: 0 5px; border-radius: 5px; min-width: 180px; ">';
                                            echo '<p class="mb-0"><a style="font-family: Comic Relief, system-ui; color: #2b8c01 !important;" href="oyun-oyna/' . $value['slug'] . '"><img style="max-height: 205px;min-height: 205px;width: 100%;object-fit: cover;" class="img-fluid" src="assets/media/games/' . $value['cover_img'] . '"></a></p>';
                                            echo '<div class="mt-3"><span class="text-gray-600 fw-bold fs-6"><a style="font-family: Comic Relief, system-ui; color: #2b8c01 !important;" href="oyun-oyna/' . $value['slug'] . '">' . $value['name'] . '</a></span></div>';
                                            echo '</div>';
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
                    <div class="card-header pt-0" style="padding: 10px;">
                        <!--begin::Title-->
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-900"><i class="fa-solid fa-gamepad me-2 fs-1"></i> Sesli Kitaplar</span>
                        </h3>


                        <a href="yazili-kitap"><button type="button"
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
                                    <div style="display: flex; justify-content: space-around; width: 100%; padding: 5px 0;">
                                        <?php
                                        $i = 0;
                                        foreach ($getBooksContent as $value) {

                                            echo '<div class="position-relative" style="flex: 1; text-align: center; padding: 10px; border: 1px solid #eee; margin: 0 5px; border-radius: 5px; min-width: 180px; ">';
                                            echo '<p class="mb-0"><a style="font-family: Comic Relief, system-ui; color: #2b8c01 !important;" href="yazili-kitap-oku/' . $value['slug'] . '"><img style="max-height: 205px;min-height: 205px;width: 100%;object-fit: cover;" class="img-fluid" src="assets/media/sesli-kitap/' . $value['cover_img'] . '"></a></p>';
                                            echo '<div class="mt-3"><span class="text-gray-600 fw-bold fs-6"><a style="font-family: Comic Relief, system-ui; color: #2b8c01 !important;" href="yazili-kitap-oku/' . $value['slug'] . '">' . $value['name'] . '</a></span></div>';
                                            echo '</div>';

                                            /* echo '<div class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-175px w-100" style=" flex: 1; text-align: center; padding: 10px; border: 1px solid #eee; margin: 0 5px; border-radius: 5px; background-image:url(\'assets/media/sesli-kitap/' . $value['cover_img'] . '\')">';
                                            echo '<span class="mt-3"><span class="text-gray-600 fw-bold fs-6"><a style="font-family: Comic Relief, system-ui; color: #2b8c01 !important;" href="yazili-kitap-oku/' . $value['slug'] . '">' . $value['name'] . '</a></span></span>';
                                            echo '</div>'; */
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
                            <i class="fa-solid fa-person-chalkboard me-2 fs-1"></i> <span class="card-label fw-bold text-gray-900">Dersler</span>
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

                                    <div style="display: flex; justify-content: space-around; width: 100%; padding: 5px 0;">
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

                                            echo '<div style="flex: 1; text-align: center; padding: 10px; border: 1px solid #eee; margin: 0 5px; border-radius: 5px; background-color: ' . $style . ';  min-width: 150px;">';

                                            $class_id = $class_idsi;
                                            $lesson_id = $value['id'];

                                            $unitData = $dash->getPreSchoolUnits($lesson_id, $class_id);
                                            $unitCount = count($unitData);

                                            echo '
                                                <a href="ana-okulu-icerikler">
                                                <div class="symbol symbol-40px">
                                                    ' . $ico . '
                                                </div>
                                                </a>';

                                            echo '<a href="ana-okulu-icerikler"><div class="mt-5"><p class="text-gray-800 fw-bold fs-4"> ' . $value['name'] . '</p>
                                                <p class="text-gray-800 fw-bold fs-6"> ' . $unitCount . ' Ünite</p></div></a>';


                                            echo '</div>';

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
        </div>

        <div class="gx-5 gx-xl-10 col-lg-3" style="padding-left: 0px; padding-right: 0px;">

            <!--begin::Col-->
            <div class="col-xxl-12 mb-1">
                <div class="card card-flush shadow-lg border-2  h-100">
                    <div class="card-header  border-bottom border-primary border-3" style="margin-left: -15px;">
                        <h2 class="card-title d-flex align-items-center flex-wrap">
                            <i class="fa-solid fa-graduation-cap me-3 fs-3 text-info"></i>
                            <span class="card-label fw-bolder text-gray-800">Haftanın Kelime Keşfi</span>
                        </h2>
                    </div>
                    <div class="card-body p-5">
                        <?php if (!empty($todaysWord['word'])): ?>
                            <div class="row g-5 align-items-center">

                                <div class="col-lg-12 order-lg-1 order-2" style="margin-top: -5px;">
                                    <div class="mb-2">
                                        <h3 style="font-size:13px; background-color:#ed5606; padding:6px 12px; border-radius:8px; display:inline-block;color: #fff!important;"
                                            class="display-3 fw-bolder  mb-2">
                                            <?php echo htmlspecialchars($todaysWord['word']) ?>
                                        </h3>
                                    </div>
                                    <div class="col-lg-12  order-lg-2 order-1">
                                        <?php if (!empty($todaysWord['image'])): ?>
                                            <div class="image-container mx-auto">
                                                <a
                                                    href="#"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#wordImageModal"
                                                    data-image-url="<?php echo htmlspecialchars($todaysWord['image']) ?>"
                                                    class=" image-link-hover"
                                                    title="Görseli büyütmek için tıklayın">

                                                    <img
                                                        src="<?php echo htmlspecialchars($todaysWord['image']) ?>"
                                                        alt="<?php echo htmlspecialchars($todaysWord['word']) ?> görseli"
                                                        class="img-fluid  shadow-lg border border-5 border-light"
                                                        style="max-height: 240; width: 250px; object-fit: cover; cursor: pointer;"
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
                                    <?php if (!empty($todaysWord['body'])): ?>
                                        <div class="bg-light-primary rounded-3 p-4 mt-5">
                                            <p class="text-gray-700 fw-normal fs-5 lh-base mb-0" style="font-size:12px!important">
                                                <i class="fa-solid fa-quote-left text-primary me-2"></i>
                                                <?php echo nl2br(htmlspecialchars($todaysWord['body'])) ?>
                                                <i class="fa-solid fa-quote-right text-primary me-2"></i>
                                            </p>
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

            <?php
            if ($todaysKnow['body'] == NULL) {
            ?>
                <img src="<?= $todaysKnow['image'] ?>" alt="Karakter" class="character-img" style="width: 310px;max-height: 400px; object-fit: cover;margin-top:15px">
            <?php
            } else {
            ?>
                <div class="col-xxl-12 mb-5 mb-xl-10" style="margin-top:20px;">
                    <div class="fact-wrapper p-4 mb-5" style="
                            background: linear-gradient(135deg, #af5fbf 0%, #ff69b4 100%); /* Mor-Pembe Gradyan */
                            border-radius: 10px;
                            position: relative;
                            overflow: hidden;
                            box-shadow: 0 10px 20px rgba(0,0,0,0.25);
                        ">
                        <style>
                            /* Arka plan noktalı deseni */
                            .fact-wrapper::before {
                                content: '';
                                position: absolute;
                                top: 0;
                                left: 0;
                                width: 100%;
                                height: 100%;
                                background-image: radial-gradient(circle, rgba(255, 255, 255, 0.2) 1px, transparent 1px);
                                background-size: 25px 25px;
                                /* Noktalar arası mesafe */
                                z-index: 1;
                                opacity: 0.9;
                            }

                            /* Resim çerçevesi */
                            .image-frame-wrapper {
                                position: relative;
                                z-index: 3;
                                padding: 6px;
                                /* Beyaz çerçeve kalınlığı */
                                background-color: white;
                                border-radius: 10px;
                                box-shadow: 0 5px 18px rgba(0, 0, 0, 0.4);
                            }

                            /* Raptiye (Push Pin) stili */
                            .push-pin {
                                position: absolute;
                                width: 28px;
                                /* Raptiye boyutu */
                                height: 28px;
                                background-color: #32CD32;
                                /* Canlı yeşil renk */
                                border-radius: 50%;
                                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
                                z-index: 10;
                                transform: rotate(20deg);
                                /* Hafif açılı duruş */
                            }

                            .push-pin.top-left {
                                top: -14px;
                                left: -14px;
                            }

                            .push-pin.top-right {
                                top: -14px;
                                right: -14px;
                            }

                            /* Bilgi içeriği alanı ve karakter-kutu çakışması */
                            .fact-content-area {
                                position: relative;
                                z-index: 5;
                                margin-top: 0px;
                                /* Resimle çakışma miktarını artırdım */
                            }

                            /* Bilgi kutusu (konuşma balonu) */
                            .fact-box {
                                background: linear-gradient(180deg, #ffffff 0%, #fff8e1 100%);
                                /* Hafif sarımsı beyaz gradyan */
                                border-radius: 10px;
                                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
                                padding: 25px;
                                padding-left: 20px;
                                /* Karakterin yerleşimi için daha fazla boşluk */
                                min-height: 160px;
                                /* Kutu yüksekliği */
                                border: 1px solid #e0e0e0;
                            }

                            /* Bilgi kutusunun "kuyruk" kısmı */
                            .fact-box::before {
                                content: '';
                                position: absolute;
                                bottom: 100%;
                                /* Kutunun üzerine yerleştir */
                                left: 100px;
                                /* Görseldeki gibi sola kaydır */
                                width: 0;
                                height: 0;
                                border-left: 18px solid transparent;
                                border-right: 18px solid transparent;
                                border-bottom: 25px solid #fff8e1;
                                /* Kutunun alt gradyan rengiyle eşleşmeli */
                                z-index: 4;
                            }

                            .fact-box::after {
                                /* Kutunun kuyruğuna ince bir çizgi efekti */
                                content: '';
                                position: absolute;
                                bottom: 100%;
                                left: 100px;
                                width: 0;
                                height: 0;
                                border-left: 18px solid transparent;
                                border-right: 18px solid transparent;
                                border-bottom: 26px solid #e0e0e0;
                                /* Kutu kenarlık rengi */
                                z-index: 3;
                            }


                            /* Karakter görseli */
                            .character-img {
                                position: absolute;
                                bottom: -15px;
                                /* Kutunun altından biraz dışarıda */
                                left: -40px;
                                /* Sol kenardan dışarıda */
                                height: 170px;
                                /* Karakterin boyutu */
                                width: auto;
                                z-index: 6;
                            }

                            /* Başlık stili */
                            .fact-box h2 {
                                font-size: 28px;
                                font-weight: 900;
                                color: #8B0000;
                                /* Koyu kırmızı/bordo tonu */
                                line-height: 1.1;
                                margin-top: 0;
                                margin-bottom: 10px;
                            }

                            /* Paragraf metni stili */
                            .fact-box p {
                                font-size: 13px;
                                font-weight: 500;
                                line-height: 1.5;
                                color: #444;
                            }
                        </style>


                        <div class="image-frame-wrappers">

                            <div>
                                <?php
                                // Resim yolu, eğer boşsa varsayılan veya bir placeholder kullanabilirsiniz
                                $imagePath = $todaysKnow['image'] ?? 'ahtapot.jpg'; // Lütfen bu path'i kendi ahtapot resminizle değiştirin
                                ?>
                                <!-- <img src="<?php echo htmlspecialchars($imagePath) ?>"
                                alt="Ahtapot"
                                class="img-fluid rounded-2"
                                style="width: 100%; max-height: 250px; object-fit: cover;"> -->
                            </div>
                        </div>
                        <div class="fact-content-area">
                            <div class="fact-box position-relative">


                                <div class="d-flex flex-column">
                                    <h3>Bunu Biliyor musunuz?</h3>

                                    <p class="mb-3">
                                        <?php
                                        // PHP'den gelen içerik, eğer boşsa varsayılan metin
                                        $content = $todaysKnow['body'] ?? 'Ahtapotların üç kalbi vardır. İki tanesi solungaçlarına kan pompalarken, bir tanesi vücudunun geri kalanına kan gönderir.';
                                        echo nl2br(htmlspecialchars($content));
                                        ?>
                                    </p>

                                    <div class="text-end mt-2">
                                        <span style="font-size: 30px; line-height: 1; display: inline-block;">💡</span>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            <?php } ?>
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
        </div>
    </div>
    <!--end::Row-->
</div>