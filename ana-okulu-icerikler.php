<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 10001 or $_SESSION['role'] == 10002 or $_SESSION['role'] == 10005)) {
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";
    require_once "classes/student.classes.php";
    $unit = new Classes();
    $studentInfo = new Student();

    if ($_SESSION['role'] == 10005) {
        $getPreSchoolStudent = $studentInfo->getPreSchoolStudentsInfoForParents($_SESSION['id']);
        $class_idsi = $getPreSchoolStudent[0]['class_id'];
        $studentidsi = $getPreSchoolStudent[0]['id'];
    } else {
        $class_idsi = $_SESSION['class_id'];
        $studentidsi = $_SESSION['id'];
    }
    $lessonId=$_GET['lesson_id']??'';
  
    $unitList = $unit->getMainSchoolUnitByClassId($class_idsi,$lessonId);

    include_once "views/pages-head.php";
?>
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

                            <div id="kt_app_content" class="app-content flex-column-fluid">
                                <div id="kt_app_content_container" class="app-container container-fluid">
                                    <div class="card-body pt-5 ">
                                        <div class="row container-fluid" style="margin-top:-25px;">
                                            <header class="container-fluid bg-custom-light py-3 d-flex justify-content-between align-items-center
                                             border-top border-bottom border-custom-red mb-2" style="border-width: 5px !important; height:85px;margin-bottom: 26px !important;">

                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-danger me-3 shadow icon-circle-lg d-flex justify-content-center align-items-center"
                                                        style="width: 65px; height: 65px;">
                                                        <i class="fas fa-bullseye fa-2x text-white"></i>
                                                    </div>

                                                    <h1 class="fs-3 fw-bold text-dark mb-0"><?=$_GET['lesson_name']??'İngilizce'?> Üniteler</h1>
                                                </div>

                                            </header>
                                        </div>
                                        <!-- <div class="row mt-1 container-fluid">
                                            <div class="col-lg-4">
                                                <label class="fs-6 fw-semibold mb-2" for="main_school_class_id">Yaş Grubu </label>
                                                <?php
                                                $class = new Classes();
                                                $roleId = $_SESSION['role'];

                                                if ($roleId == 10002 OR $roleId == 10005) {
                                                    $classId = $class->getClassId($studentidsi);
                                                    $mainSchoolClasses = $class->getAgeGroup($classId);
                                                    $_SESSION['class_id'] = $classId;
                                                } else {
                                                    unset($_SESSION['class_id']);
                                                    $mainSchoolClasses = $class->getAgeGroup(); // class_id = null
                                                }
                                                ?>
                                                <select class="form-select" id="main_school_class_id" required aria-label="Default select example">
                                                    <option value="">Seçiniz</option>
                                                    <?php foreach ($mainSchoolClasses as $c) { ?>
                                                        <option <?php if (isset($classId) && $c['id'] == $classId) echo 'selected'; ?> value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <?php
                                            $lessonList = $class->getMainSchoolLessonList();
                                            ?>
                                            <div class="col-lg-4">
                                                <label for="lesson_id" class="form-label">Ders Adı</label>
                                                <select class="form-select form-control" id="lesson_id" name="lesson_id">
                                                    <option selected disabled>Seçiniz</option>
                                                    <?php foreach ($lessonList as $lesson) { ?>
                                                        <option value="<?= $lesson['id'] ?>">
                                                            <?= $lesson['name'] ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="unit_id" class="form-label">Ünite Adı</label>
                                                <select class="form-select form-control" id="unit_id" name="lesson_id">
                                                    <option selected disabled>Önce ders seçiniz...</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mt-4 container-fluid">
                                            <div class="col-lg-4">
                                                <label for="topic_id" class="form-label">Konu Adı</label>
                                                <select class="form-select form-control" id="topic_id" name="lesson_id">
                                                    <option selected disabled>Önce ünite seçiniz...</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-4">
                                                <label class="required fs-6 fw-semibold mb-2" for="month">Ay </label>
                                                <select class="form-select" id="month" required aria-label="Default select example">
                                                    <option value="">Seçiniz</option>
                                                    <option value="Ocak">Ocak</option>
                                                    <option value="Şubat">Şubat</option>
                                                    <option value="Mart">Mart</option>
                                                    <option value="Nisan">Nisan</option>
                                                    <option value="Mayıs">Mayıs</option>
                                                    <option value="Haziran">Haziran</option>
                                                    <option value="Temmuz">Temmuz</option>
                                                    <option value="Ağustos">Ağustos</option>
                                                    <option value="Eylül">Eylül</option>
                                                    <option value="Ekim">Ekim</option>
                                                    <option value="Kasım">Kasım</option>
                                                    <option value="Aralık">Aralık2</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-4">
                                                <label class="fs-6 fw-semibold mb-2" for="week">Özel Hafta Seçimi </label>
                                                <?php
                                                $class = new Classes();
                                                $weekList = $class->getWeekList();
                                                ?>
                                                <select class="form-select" id="week" required aria-label="Default select example">
                                                    <option value="">Seçiniz</option>
                                                    <?php foreach ($weekList as $week) { ?>
                                                        <option value="<?= $week['id'] ?>"><?= $week['name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mt-4 container-fluid">
                                            <div class="col-lg-4">
                                                <label class=" fs-6 fw-semibold mb-2" for="activity_type">Etkinlik Türü Başlığı</label>
                                                <select class="form-select form-control" id="activity_title" aria-label="Default select example">
                                                    <option value="">Seçiniz</option>
                                                    <?php
                                                    $activityTitle = $class->getTitleList(3);
                                                    foreach ($activityTitle as $title) {
                                                    ?>
                                                        <option value="<?= $title['id'] ?>"><?= $title['title'] ?></option>
                                                    <?php }  ?>
                                                </select>
                                            </div>
                                            <div class="col-lg-4">
                                                <label class=" fs-6 fw-semibold mb-2" for="content_title">İçerik Başlığı</label>
                                                <select class="form-select form-control" id="content_title" aria-label="Default select example">
                                                    <option value="">Seçiniz</option>
                                                    <?php
                                                    $titlesContent = $class->getTitleList(1);
                                                    foreach ($titlesContent as $title) {
                                                    ?>
                                                        <option value="<?= $title['id'] ?>"><?= $title['title'] ?></option>
                                                    <?php }  ?>
                                                </select>
                                            </div>
                                            <div class="col-lg-4">
                                                <label class=" fs-6 fw-semibold mb-2" for="concept_title">Kavram Başlığı</label>
                                                <select class="form-select form-control" id="concept_title" aria-label="Default select example">
                                                    <option value="">Seçiniz</option>
                                                    <?php
                                                    $titlesContent = $class->getTitleList(2);
                                                    foreach ($titlesContent as $title) {
                                                    ?>
                                                        <option value="<?= $title['id'] ?>"><?= $title['title'] ?></option>
                                                    <?php }  ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mt-4 container-fluid">
                                            <div class="mt-4 text-end">
                                                <button id="filterBtn" class="btn btn-primary btn-sm">Filtrele</button>
                                                <button id="clearFilterBtn" class="btn btn-secondary btn-sm">Filtreyi Temizle</button>
                                            </div>
                                        </div> -->
                                    </div>
                                    <div class="row container-fluid">
                                        <?php foreach ($unitList as $u): ?>
                                            <div class="col-12" style=" font-size:12px!important">
                                                <a href="ana-okulu-icerikler_konu?id=<?= $u['id'] ?>" class="text-decoration-none">
                                                    <div class="border rounded d-flex align-items-center p-2"
                                                        style="border: 2px solid #333; box-shadow: 0 2px 6px rgba(0,0,0,0.15); justify-content: flex-start;">
                                                        <button type="button" class="btn btn-light btn-sm me-3">
                                                            <i style="font-size:20px!important" class="bi bi-play-fill"></i>
                                                        </button>
                                                        <div>
                                                            <div class="fw-semibold fs-5" style="font-size:12px!important; color: #000;">
                                                                <?= htmlspecialchars($u['unit_name']) ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        <?php endforeach; ?>
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