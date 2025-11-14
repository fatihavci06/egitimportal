<!DOCTYPE html>
<html lang="tr">

<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 10001 or $_SESSION['role'] == 10002 or $_SESSION['role'] == 10005)) {
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";
    require_once "classes/student.classes.php";
    $dataList = new Classes();
    $studentInfo = new Student();


    if ($_SESSION['role'] == 10005) {
        $getPreSchoolStudent = $studentInfo->getPreSchoolStudentsInfoForParents($_SESSION['id']);
        $class_idsi = $getPreSchoolStudent[0]['class_id'];
        $studentidsi = $getPreSchoolStudent[0]['id'];
    } else {
        $class_idsi = $_SESSION['class_id'];
        $studentidsi = $_SESSION['id'];
    }



    include_once "views/pages-head.php";
?>
    <style>
        .month-block-link {
            text-decoration: none;
            display: block;
            height: 100%;
            /* Geçiş animasyonunu yumuşatır */
            transition: all 0.3s ease;
        }

        .month-block-link:hover {
            /* Hover'da gölgeyi artır ve hafifçe kaldır */
            transform: translateY(-2px);
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .1) !important;
        }

        .month-block {
            /* Temiz, yuvarlak kenarlı blok görünümü */
            background-color: #ffffff;
            /* border: 1px solid #dee2e6; */
            border-radius: 0.5rem;
            padding: 1rem;
        }

        .month-number {
            /* Ay numarası için belirgin renk ve kenarlık */
            color: #ffffff;
            background-color: var(--bs-primary);
            border-radius: 0.3rem;
            padding: 0.3rem 0.5rem;
            font-weight: 700;
            margin-right: 0.75rem;
            /* Ay isminden ayırır */
        }
    </style>
    <style>
        /* Genel Stil İyileştirmeleri */

        .main-card-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            border: 1px solid #e0e0e0;
        }

        .custom-card {
            border: none;
            padding: 0px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            background-color: white;
            margin-bottom: 25px;
        }

        .card-title-custom {
            font-size: 1.2rem;
            font-weight: 700;
            color: #ed5606;
            margin-bottom: 15px;
        }

        .content-wrapper {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .icon-small {
            font-size: 50px !important;
            color: #e83e8c !important;
        }



        .btn-custom {
            /* background-color: #1b84ff; */
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 600;
            transition: background-color 0.3s ease;
            margin-top: 15px;
        }

        #myTable thead {
            display: none;
        }

        .btn-custom:hover {
            background-color: #1a9c7b;
        }

        .left-align {
            margin-left: 0;
            margin-right: auto;
        }

        .right-align {
            margin-left: auto;
            margin-right: 0;
        }

        .left-align .card-body {
            align-items: flex-start;
            text-align: left;
        }

        .left-align .content-wrapper {
            flex-direction: row;
        }

        .right-align .card-body {
            align-items: flex-end;
            text-align: right;
        }

        .right-align .content-wrapper {
            flex-direction: row-reverse;
        }

        .card-body {
            display: flex;
            flex-direction: column;
        }

        .bg-custom-light {
            background-color: #e6e6fa;
            /* Light purple */
        }

        .border-custom-red {
            border-color: #d22b2b !important;
        }

        .text-custom-cart {
            color: #6a5acd;
            /* Slate blue for the cart */
        }

        /* For the circular icon, we'll use a larger padding or fixed size */
        .icon-circle-lg {
            width: 60px;
            /* fixed width */
            height: 60px;
            /* fixed height */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-circle-lg img {
            max-width: 100%;
            /* Ensure image scales within the circle */
            max-height: 100%;
        }


        /* Animasyonlar */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" class="app-default">
        <script>
            var defaultThemeMode = "light";
            var themeMode;
            if (document.documentElement) {
                if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                    themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
                } else {
                    if (localStorage.getItem("data-bs-theme") !== null) {
                        themeMode = localStorage.getItem("data-bs-theme");
                    } else {
                        themeMode = defaultThemeMode;
                    }
                }
                if (themeMode === "system") {
                    themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
                }
                document.documentElement.setAttribute("data-bs-theme", themeMode);
            }
        </script>
        <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
            <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
                <?php include_once "views/header.php"; ?>
                <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                    <?php include_once "views/sidebar.php"; ?>
                    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                        <div class="d-flex flex-column flex-column-fluid">
                            <?php include_once "views/toolbar.php"; ?>

                            <div id="kt_app_content" class="app-content flex-column-fluid" style="padding-top: 10px !important;">
                                <div id="kt_app_content_container" class="app-container container-fluid" style="padding: 0px !important;">
                                    <div class="card-body pt-5 ">
                                        <div class="row container-fluid" style="margin-top:-25px;">
                                            <header class="container-fluid bg-custom-light py-3 d-flex justify-content-between align-items-center border-top border-bottom border-custom-red mb-2" style="border-width: 5px !important; height:85px;margin-bottom: 26px !important;">

                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-danger me-3 shadow icon-circle-lg d-flex justify-content-center align-items-center"
                                                        style="width: 65px; height: 65px;">
                                                        <i class="fas fa-bullseye fa-2x text-white"></i>
                                                    </div>

                                                    <h1 class="fs-3 fw-bold text-dark mb-0"><?= $_GET['title'] ?? '-' ?></h1>

                                                </div>
                                            </header>
                                        </div>

                                        <div class="row container-fluid d-flex justify-content-center">
                                            <?php
                                            $type = 1;
                                            if (isset($_GET['type'])) {
                                                $type = $_GET['type'];
                                            }
                                            switch ($type) {
                                                case 1:
                                                    // Type 1 işlemleri
                                                    $data = $dataList->getTurkceImportanWeekDetail();

                                                    // Sütunları düzenlemek için ana satırı başlat
                                                    echo '<div class="row" style="background-size: 15%; background-position: bottom right; background-repeat: no-repeat; background-image: url(uploads/maskot-ikili.png); ">';

                                                    // Her bir kavram grubu için 4 sütunluk bir yapı kullan
                                                    foreach ($data as $week) {
                                                        // col-lg-3 4 sütun sağlar, mb-4 altındaki boşluğu ayarlar
                                                        echo '<div class="col-lg-4 col-md-6 col-sm-12 mb-4">';

                                                        // Kavram Grubu Başlığı (Mavi başlık)
                                                        echo '<div class="fw-bold fs-5 text-center p-2 mb-2" style="background-color: #f0f8ff; border-radius: 5px; color: #1b84ff;">';
                                                        // Başlıkları büyük harf ve 'KAVRAMLARI' eki ile yaz
                                                        echo htmlspecialchars(mb_strtoupper($week['week_name'], 'UTF-8') . ' KAVRAMLARI');
                                                        echo '</div>';

                                                        // İçerik Listesi
                                                        echo '<ul class="list-unstyled">';

                                                        foreach ($week['contents'] as $content) {

                                                            // Konunun ID'sini al
                                                            $contentId = $content['content_id'] ?? null;

                                                            // Dinamik URL'yi oluştur
                                                            // Eğer ID mevcutsa URL'yi oluştur, yoksa '#' kullan
                                                            $dynamicUrl = '#';
                                                            if ($contentId !== null) {
                                                                // URL formatı: ana-okulu-icerikler-detay.php?id=[ID]
                                                                $dynamicUrl = 'ana-okulu-icerik-detay.php?id=' . urlencode($contentId);
                                                            }

                                                            // Liste Öğesi (İkonlu ve linkli)
                                                            echo '<li class="d-flex align-items-center mb-2" style="font-size: 1rem;">';

                                                            // Çerez İkonu
                                                            echo '<i class="fas fa-cookie-bite me-2" style="color: #c44d4d;"></i>';

                                                            // Konu Metni artık dinamik URL'ye sahip bir link olacak
                                                            // 'text-decoration-none' alt çizgiyi kaldırır.
                                                            echo '<a href="' . htmlspecialchars($dynamicUrl) . '" class="text-decoration-none" style="color: #333; transition: color 0.2s ease;">';
                                                            echo htmlspecialchars($content['subject']);
                                                            echo '</a>';

                                                            echo '</li>';
                                                        }
                                                        echo '</ul>';

                                                        echo '</div>'; // col-lg-3'ü kapat
                                                    }

                                                    echo '</div>'; // row'u kapat

                                                    break;

                                                case 2:
                                                    // VERİ HAZIRLIĞI
                                                    $months = [
                                                        1 => 'Ocak',
                                                        2 => 'Şubat',
                                                        3 => 'Mart',
                                                        4 => 'Nisan',
                                                        5 => 'Mayıs',
                                                        6 => 'Haziran',
                                                        7 => 'Temmuz',
                                                        8 => 'Ağustos',
                                                        9 => 'Eylül',
                                                        10 => 'Ekim',
                                                        11 => 'Kasım',
                                                        12 => 'Aralık',
                                                    ];

                                            ?>


                                                    <div class="container py-5">


                                                        <div class="row row-cols-2 row-cols-md-4 g-3">

                                                            <?php foreach ($months as $id => $month): ?>
                                                                <div class="col">
                                                                    <a href="ana-okulu-icerikler-turkce-detail-month?month=<?= $month ?>" class="month-block-link shadow-sm">

                                                                        <div class="month-block d-flex align-items-center h-100">

                                                                            <span class="month-number">
                                                                                <?= $id ?>
                                                                            </span>

                                                                            <span class="fw-bold text-dark">
                                                                                <?= htmlspecialchars($month) ?>
                                                                            </span>

                                                                        </div>
                                                                    </a>
                                                                </div>
                                                            <?php endforeach; ?>

                                                        </div>
                                                    </div>
                                                <?php
                                                    break;

                                                case 3:
                                                    // Type 3 işlemleri
                                                    $weeks = $dataList->getImportantWeekList();

                                                ?>


                                                    <div class="container py-5">


                                                        <?php
// Sayacı başlatıyoruz.
$i = 0; 
?>
<div class="row row-cols-2 row-cols-xl-3 row-cols-lg-3 row-cols-md-2 row-cols-sm-1 g-3">
    
    <?php foreach ($weeks as $week): ?>
        <?php
        // Karakter sırasını belirliyoruz: bir kız, bir erkek
        $character_type = ($i % 2 === 0) ? 'kiz' : 'erkek';

        // Arkaplan görseli yolunu belirliyoruz.
        $background_image = ($character_type === 'kiz')
            ? 'uploads/ana-okulu-icerikler-turkce/belirli-gun-haftalar-kiz.png'
            : 'uploads/ana-okulu-icerikler-turkce/belirli-gun-haftalar-erkek.png';
        
        // Dinamik link
        $link = "ana-okulu-icerikler-turkce-detail-week?week_id=" . $week['id'] . "&week_name=" . $week['name'];
        ?>

        <div class="col">
            <a href="<?= $link ?>" class="month-block-link d-block text-decoration-none">

                <div class="month-block" style="
                    /* Ana arkaplan görseli: Karakter ve ahşap tabela */
                    background-image: url('<?= $background_image ?>');
                    background-repeat: no-repeat;
                   /* background-size: 100% 100%; 
                    min-height: 250px;*/
                    position: relative;
                    min-height: 340px;
                    background-size: contain;
                    background-position: center;
                ">
                    
                    <div class="tabela-content" style="
                        position: absolute;
                        top: 108px; /* Ahşap tabelanın görünen kısmına göre pozisyon */
                            left: 54%;

                        transform: translate(-50%, -50%);
                        width: 170px; /* Tabelanın genişliğini artırdık */
                        border-radius: 5px; /* Köşe yuvarlaklığı */
                        overflow: hidden; /* İçerik taşmasını engellemek ve border-radius'u korumak için */
                        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Hafif gölge */
                    ">
                        
                        <div class="tabela-top-white" style="
                            background-color: white;
                            padding: 10px;
                            text-align: center;
                        ">
                            <div class="ikon-ve-baslik" style="display: flex; align-items: center; justify-content: center; flex-wrap: wrap;">
                                
                                <div style="flex-grow: 1;">
                                    <span class="d-block fw-bold" style="font-size: 0.9rem; color: #38a538;">
                                        <?= htmlspecialchars($week['name']) ?>
                                    </span>
                                </div>
                            </div>

                        </div>
                        
                        <div class="tabela-bottom-green" style="
                            background-color: #38a538; /* Yeşil Zemin */
                            padding: 5px 10px;
                            text-align: left;
                        ">
                            <span class="text-white" style="font-size: 0.8rem;">İncele</span>
                        </div>
                    </div>

                </div>
            </a>
        </div>
    <?php 
    // Sayacı artırıyoruz.
    $i++;
    endforeach; 
    ?>
</div>
</div>

<style>
/* Eklenen CSS sınıfları ve stiller, resimdeki görünümü daha iyi taklit etmek için gerekebilir. */
/* Örneğin, .month-block-link'e 100% genişlik vermek, içerideki görselleri düzenlemek vb. */
/* Yukarıdaki inline stiller temel görünümü sağlamalıdır. */

/* Eğer tabela içinde başka özel bir tasarım varsa (örn. kahverengi üst kısım), 
   background-image'in sadece karakteri değil, tüm tabelayı içermesi veya 
   ek CSS katmanları kullanılması gerekir. Mevcut arkaplan görseli yolunuzun 
   hem karakteri hem de tabelayı içeren nihai bir PNG olduğunu varsayıyorum. */
</style>
                                                    </div>
                                            <?php
                                                    break;

                                                case 4:
                                                    // Type 4 işlemleri
                                                     $categoryEtkinlikList = $dataList->getCategoryEtkinlikList();

                                                ?>


                                                    <div class="container py-5">


                                                        <div class="row row-cols-2 row-cols-md-4 g-3">

                                                            <?php foreach ($categoryEtkinlikList as  $e): ?>
                                                                <div class="col">
                                                                    <a href="ana-okulu-icerikler-turkce-detail-etkinlik?etkinlik_id=<?= $e['id']  ?>&etkinlik_name=<?= $e['title'] ?>" class="month-block-link shadow-sm">

                                                                        <div class="month-block d-flex align-items-center h-100">



                                                                            <span class="fw-bold text-dark">
                                                                                <?= htmlspecialchars($e['title']) ?>
                                                                            </span>

                                                                        </div>
                                                                    </a>
                                                                </div>
                                                            <?php endforeach; ?>

                                                        </div>
                                                    </div>
                                            <?php
                                                    break;

                                                default:
                                                    // Geçersiz type değeri
                                                    echo "Geçersiz type değeri!";
                                                    break;
                                            }
                                            ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php include_once "views/footer.php"; ?>
                </div>
                <?php include_once "views/aside.php"; ?>
            </div>
        </div>
        </div>
        <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
            <i class="ki-duotone ki-arrow-up">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </div>
        <script>
            var hostUrl = "assets/";
        </script>
        <script src="assets/plugins/global/plugins.bundle.js"></script>
        <script src="assets/js/scripts.bundle.js"></script>
        <script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
        <script src="assets/plugins/custom/tinymce/tinymce.bundle.js"></script>
        <script src="assets/js/custom/apps/subtopics/list/export.js"></script>
        <script src="assets/js/custom/apps/subtopics/list/list.js"></script>
        <script src="assets/js/custom/apps/subtopics/list/topicadd.js"></script>
        <script src="assets/js/custom/apps/subtopics/add.js"></script>
        <script src="assets/js/custom/apps/subtopics/create.js"></script>
        <script src="assets/js/widgets.bundle.js"></script>
        <script src="assets/js/custom/widgets.js"></script>
        <script src="assets/js/custom/apps/chat/chat.js"></script>
        <script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
        <script src="assets/js/custom/utilities/modals/create-account.js"></script>
        <script src="assets/js/custom/utilities/modals/create-app.js"></script>
        <script src="assets/js/custom/utilities/modals/users-search.js"></script>
        <script>

        </script>

    </body>

</html>
<?php } else {
    header("location: index");
}
?>