<!DOCTYPE html>
<html lang="tr">
<?php

session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 2)) {

    include_once "classes/dbh.classes.php";
    include_once "classes/student.classes.php";
    include_once "classes/student-view.classes.php";
    include_once "classes/school.classes.php";
    include_once "classes/timespend.classes.php";
    $studentObj = new Student();
    $student = new ShowStudent();
    $timeSpend = new timeSpend();
    $school = new School();


    include_once "views/pages-head.php";
    $userInfo = $studentObj->getStudentById($_SESSION['id']);

    $timeSpendInfo = $timeSpend->getTimeSpend($userInfo["id"]);

    $schoolInfo = $school->getOneSchoolById($userInfo['school_id']);

    $studentPackages = $student->getStudentPackages($userInfo["id"]);

    $studentAdditionalPackages = $student->getStudentAdditionalPackages($userInfo["id"]);

    $studentClassName = $student->getStudentClass($userInfo['class_id']);

    // var_dump($userInfo['class_id'], $userInfo['school_id']);
    // die();
    ?>

    <body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true"
        data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
        data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true"
        data-kt-app-aside-enabled="true" data-kt-app-aside-fixed="true" data-kt-app-aside-push-toolbar="true"
        data-kt-app-aside-push-footer="true" class="app-default">
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
                            <div id="kt_app_toolbar" class="app-toolbar pt-5">
                                <div id="kt_app_toolbar_container"
                                    class="app-container container-fluid d-flex align-items-stretch">
                                    <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                                        <div class="page-title d-flex flex-column gap-1 me-3 mb-2">
                                            <ul class="breadcrumb breadcrumb-separatorless fw-semibold mb-6">
                                                <li class="breadcrumb-item text-gray-700 fw-bold lh-1">
                                                    <a href="index.html" class="text-gray-500 text-hover-primary">
                                                        <i class="ki-duotone ki-home fs-3 text-gray-500 me-n1"></i>
                                                    </a>
                                                </li>
                                                <li class="breadcrumb-item">
                                                    <i class="ki-duotone ki-right fs-4 text-gray-700 mx-n1"></i>
                                                </li>
                                                <li class="breadcrumb-item text-gray-700">Öğrenci Detay</li>
                                            </ul>
                                            <h1
                                                class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bolder fs-1 lh-0">
                                                Öğrenci Detay
                                            </h1>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="kt_app_content" class="app-content flex-column-fluid">
                                <div id="kt_app_content_container" class="app-container container-fluid">
                                    <div class="card mb-5 mb-xxl-8">
                                        <div class="card-body pt-9 pb-0">
                                            <div class="d-flex flex-wrap flex-sm-nowrap">
                                                <div class="me-7 mb-4">
                                                    <div
                                                        class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                                        <img src="assets/media/profile/<?= $userInfo['photo'] ?>"
                                                            alt="image" />
                                                        <!-- <div class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px"></div> -->
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div
                                                        class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                                        <div class="d-flex flex-column">
                                                            <div class="d-flex align-items-center mb-2">
                                                                <a href="#"
                                                                    class="text-gray-900 text-hover-primary fs-2 fw-bold me-1"><?php echo $userInfo['name'] . ' ' . $userInfo['surname'] ?>
                                                                </a>
                                                            </div>
                                                            <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                                                <span
                                                                    class="d-flex align-items-center text-gray-500 me-5 mb-2">
                                                                    <i class="fa-solid fa-school fs-4 me-1"></i>
                                                                    <?php echo $schoolInfo['name']; ?>
                                                                </span>
                                                                <a href="tel:<?php echo $userInfo['telephone']; ?>"
                                                                    class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
                                                                    <i class="fa-solid fa-phone fs-4 me-1"></i>
                                                                    <?php echo $userInfo['telephone']; ?>
                                                                </a>
                                                                <a href="mailto:<?php echo $userInfo['email']; ?>"
                                                                    class="d-flex align-items-center text-gray-500 text-hover-primary mb-2">
                                                                    <i class="ki-duotone ki-sms fs-4 me-1">
                                                                        <span class="path1"></span>
                                                                        <span class="path2"></span>
                                                                    </i>
                                                                    <?php echo $userInfo['email']; ?>
                                                                </a>

                                                            </div>

                                                            <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2 mt-3 mb-2">

                                                                <div class="d-flex justify-content-end "
                                                                    data-kt-customer-table-toolbar="base">
                                                                    <button type="button" class="btn btn-primary btn-sm"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#kt_modal_update_customer">Bilgilerimi
                                                                        Güncelle</button>
                                                                </div>
                                                                <div class="d-flex justify-content-end ms-8"
                                                                    data-kt-customer-table-toolbar="base">
                                                                    <button type="button" class="btn btn-primary btn-sm"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#kt_modal_update_password">Şifremi
                                                                        Güncelle</button>
                                                                </div>
                                                                <div class="d-flex justify-content-end ms-8"
                                                                    data-kt-customer-table-toolbar="base">
                                                                    <button type="button" class="btn btn-primary btn-sm"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#kt_modal_update_email">E-Posta'mı
                                                                        Değiştir</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex flex-wrap flex-stack">
                                                        <div class="d-flex flex-column flex-grow-1 pe-8">
                                                            <div class="d-flex flex-wrap">
                                                                <div
                                                                    class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                                                    <div class="d-flex align-items-center">
                                                                        <i
                                                                            class="fa-regular fa-clock fs-2 text-success me-2"></i>
                                                                        <div class="fs-2 fw-bold">
                                                                            <?php echo $timeSpend->saniyeyiGoster($timeSpendInfo); ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="fw-semibold fs-6 text-gray-500">Toplam
                                                                        Geçirilen Süre</div>
                                                                </div>
                                                                <div
                                                                    class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                                                    <div class="d-flex align-items-center">
                                                                        <i
                                                                            class="ki-duotone ki-book-open fs-2 text-success me-2">
                                                                            <span class="path1"></span>
                                                                            <span class="path2"></span>
                                                                            <span class="path3"></span>
                                                                            <span class="path4"></span>
                                                                        </i>
                                                                        <div class="fs-2 fw-bold" data-kt-countup="true"
                                                                            data-kt-countup-value="<?php echo count($studentPackages); ?>">
                                                                            0</div>
                                                                    </div>
                                                                    <div class="fw-semibold fs-6 text-gray-500">Alınan Paket
                                                                        Sayısı</div>
                                                                </div>
                                                                <div
                                                                    class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                                                    <div class="d-flex align-items-center">
                                                                        <i
                                                                            class="ki-duotone ki-brifecase-tick fs-2 text-success me-2">
                                                                            <span class="path1"></span>
                                                                            <span class="path2"></span>
                                                                            <span class="path3"></span>
                                                                        </i>
                                                                        <div class="fs-2 fw-bold" data-kt-countup="true"
                                                                            data-kt-countup-value="<?php echo count($studentAdditionalPackages); ?>">
                                                                            0</div>
                                                                    </div>
                                                                    <div class="fw-semibold fs-6 text-gray-500">Alınan Ek
                                                                        Paket Sayısı</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="d-flex align-items-center w-200px w-sm-300px flex-column mt-3">
                                                            <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                                                <span class="fw-semibold fs-6 text-gray-500">Profile
                                                                    Compleation</span>
                                                                <span class="fw-bold fs-6">50%</span>
                                                            </div>
                                                            <div class="h-5px mx-3 w-100 bg-light mb-3">
                                                                <div class="bg-success rounded h-5px" role="progressbar"
                                                                    style="width: 50%;" aria-valuenow="50" aria-valuemin="0"
                                                                    aria-valuemax="100"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <ul
                                                class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
                                                <li class="nav-item mt-2">
                                                    <a class="nav-link text-active-primary ms-0 me-10 py-5 active"
                                                        data-bs-toggle="pill" href="#genel_bakis">Genel Bakış</a>
                                                </li>
                                                <li class="nav-item mt-2">
                                                    <a class="nav-link text-active-primary ms-0 me-10 py-5"
                                                        data-bs-toggle="pill" href="#dersler">Dersler</a>
                                                </li>
                                                <li class="nav-item mt-2">
                                                    <a class="nav-link text-active-primary ms-0 me-10 py-5"
                                                        data-bs-toggle="pill" href="#ozel_dersler">Özel Dersler</a>
                                                </li>
                                                <li class="nav-item mt-2">
                                                    <a class="nav-link text-active-primary ms-0 me-10 py-5"
                                                        data-bs-toggle="pill" href="#grup_dersler">Grup Dersleri</a>
                                                </li>
                                                <li class="nav-item mt-2">
                                                    <a class="nav-link text-active-primary ms-0 me-10 py-5"
                                                        data-bs-toggle="pill" href="#paketler">Paketler</a>
                                                </li>
                                                <li class="nav-item mt-2">
                                                    <a class="nav-link text-active-primary ms-0 me-10 py-5"
                                                        data-bs-toggle="pill" href="#hareketler">Hareketler</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="genel_bakis">
                                            <div class="row g-5 g-xxl-8">
                                                <div class="col-xl-6">
                                                    <div class="card mb-5 mb-xl-8">
                                                        <div class="card-header border-0 pt-5">
                                                            <h3 class="card-title align-items-start flex-column">
                                                                <span
                                                                    class="card-label fw-bold text-gray-900">Dersler</span>
                                                                <span
                                                                    class="text-muted mt-1 fw-semibold fs-7"><?php echo $studentClassName[0]['name']; ?></span>
                                                            </h3>
                                                            <!-- <div class="card-toolbar">
                                                                <a data-bs-toggle="pill" href="#kt_stats_widget_2_tab_22" class="btn btn-sm btn-light">Tüm Dersler</a>
                                                            </div> -->
                                                        </div>
                                                        <div class="card-body pt-6">
                                                            <?php $student->showLessonsListForStudentDetails($userInfo['class_id'], $userInfo['school_id']); ?>
                                                        </div>
                                                    </div>
                                                    <div class="card card-flush mb-5 mb-xl-8">
                                                        <div class="card-header pt-7">
                                                            <h3 class="card-title align-items-start flex-column">
                                                                <span class="card-label fw-bold text-gray-800">Özel
                                                                    Dersler</span>
                                                            </h3>
                                                            <div class="card-toolbar"></div>
                                                        </div>
                                                        <div class="card-body pt-5">
                                                            <div class="">
                                                                <div class="d-flex flex-stack">
                                                                    <div class="d-flex align-items-center me-5">
                                                                        <i
                                                                            class="fa-solid fa-person-chalkboard text-muted fs-1 me-8"></i>
                                                                        <div class="me-5">
                                                                            <a href="#"
                                                                                class="text-gray-800 fw-bold text-hover-primary fs-6">Özel
                                                                                Ders</a>
                                                                            <span
                                                                                class="text-gray-500 fw-semibold fs-7 d-block text-start ps-0">Öğretmen</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex align-items-center">
                                                                        <span
                                                                            class="text-gray-800 fw-bold fs-4 me-3">45</span>
                                                                        <div class="m-0">
                                                                            <span class="badge badge-light-success fs-base">
                                                                                <i
                                                                                    class="ki-duotone ki-arrow-up fs-5 text-success ms-n1">
                                                                                    <span class="path1"></span>
                                                                                    <span class="path2"></span>
                                                                                </i>Dk</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="separator separator-dashed my-3"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6">
                                                    <div class="card mb-5 mb-xl-8">
                                                        <div class="card-header align-items-center border-0">
                                                            <h3 class="fw-bold text-gray-900 m-0">Alınan Paketler</h3>
                                                        </div>
                                                        <div class="card-body pt-2">
                                                            <ul class="nav nav-pills nav-pills-custom mb-3">
                                                                <li class="nav-item mb-3 me-3 me-lg-6">
                                                                    <a class="nav-link d-flex justify-content-between flex-column flex-center overflow-hidden active w-110px h-85px py-4"
                                                                        data-bs-toggle="pill"
                                                                        href="#kt_stats_widget_2_tab_1">
                                                                        <div class="nav-icon">
                                                                            <img alt=""
                                                                                src="assets/media/svg/files/folder-document-dark.svg"
                                                                                class="" />
                                                                        </div>
                                                                        <span
                                                                            class="nav-text text-gray-700 fw-bold fs-6 lh-1">Paketler</span>
                                                                        <span
                                                                            class="bullet-custom position-absolute bottom-0 w-100 h-4px bg-primary"></span>
                                                                    </a>
                                                                </li>
                                                                <li class="nav-item mb-3 me-3 me-lg-6">
                                                                    <a class="nav-link d-flex justify-content-between flex-column flex-center overflow-hidden w-110px h-85px py-4"
                                                                        data-bs-toggle="pill"
                                                                        href="#kt_stats_widget_2_tab_2">
                                                                        <div class="nav-icon">
                                                                            <img alt=""
                                                                                src="assets/media/svg/files/folder-document.svg"
                                                                                class="" />
                                                                        </div>
                                                                        <span
                                                                            class="nav-text text-gray-700 fw-bold fs-6 lh-1">Ek
                                                                            Paketler</span>
                                                                        <span
                                                                            class="bullet-custom position-absolute bottom-0 w-100 h-4px bg-primary"></span>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                            <div class="tab-content">
                                                                <div class="tab-pane fade show active"
                                                                    id="kt_stats_widget_2_tab_1">
                                                                    <div class="table-responsive">
                                                                        <table
                                                                            class="table table-row-dashed align-middle gs-0 gy-4 my-0">
                                                                            <thead>
                                                                                <tr
                                                                                    class="fs-7 fw-bold text-gray-500 border-bottom-0">
                                                                                    <th style="width: 50%;"
                                                                                        class="ps-0 min-w-200px">PAKET ADI
                                                                                    </th>
                                                                                    <th style="width: 25%;"
                                                                                        class="text-end min-w-100px">FİYATI
                                                                                    </th>
                                                                                    <th style="width: 25%;"
                                                                                        class="pe-0 text-end min-w-100px">
                                                                                        BİTİŞ TARİHİ</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>

                                                                                <?php $student->showPackageListForStudentDetails($userInfo['id']); ?>

                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                                <div class="tab-pane fade" id="kt_stats_widget_2_tab_2">
                                                                    <div class="table-responsive">
                                                                        <table
                                                                            class="table table-row-dashed align-middle gs-0 gy-4 my-0">
                                                                            <thead>
                                                                                <tr
                                                                                    class="fs-7 fw-bold text-gray-500 border-bottom-0">
                                                                                    <th style="width: 50%;"
                                                                                        class="ps-0 min-w-200px">PAKET ADI
                                                                                    </th>
                                                                                    <th style="width: 25%;"
                                                                                        class="text-end min-w-100px">FİYATI
                                                                                    </th>
                                                                                    <th style="width: 25%;"
                                                                                        class="pe-0 text-end min-w-100px">
                                                                                        BİTİŞ TARİHİ</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php $student->showAdditionalPackageListForStudentDetails($userInfo['id']); ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="card card-flush mb-5 mb-xl-8">
                                                        <div class="card-header pt-7">
                                                            <h3 class="card-title align-items-start flex-column">
                                                                <span class="card-label fw-bold text-gray-800">Grup
                                                                    Dersler</span>
                                                            </h3>
                                                            <div class="card-toolbar"></div>
                                                        </div>
                                                        <div class="card-body pt-5">
                                                            <div class="">
                                                                <div class="d-flex flex-stack">
                                                                    <div class="d-flex align-items-center me-5">
                                                                        <i
                                                                            class="fa-solid fa-users text-muted fs-1 me-8"></i>
                                                                        <div class="me-5">
                                                                            <a href="#"
                                                                                class="text-gray-800 fw-bold text-hover-primary fs-6">Grup
                                                                                Ders</a>
                                                                            <span
                                                                                class="text-gray-500 fw-semibold fs-7 d-block text-start ps-0">Öğretmen</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex align-items-center">
                                                                        <span
                                                                            class="text-gray-800 fw-bold fs-4 me-3">45</span>
                                                                        <div class="m-0">
                                                                            <span class="badge badge-light-success fs-base">
                                                                                <i
                                                                                    class="ki-duotone ki-arrow-up fs-5 text-success ms-n1">
                                                                                    <span class="path1"></span>
                                                                                    <span class="path2"></span>
                                                                                </i>Dk</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="separator separator-dashed my-3"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card mb-5 mb-xl-8" id="kt_timeline_widget_2_card">
                                                        <div class="card-header position-relative py-0 border-bottom-2">
                                                            <ul
                                                                class="nav nav-stretch nav-pills nav-pills-custom d-flex mt-3">
                                                                <li class="nav-item p-0 ms-0 me-8">
                                                                    <a class="nav-link btn btn-color-muted active px-0"
                                                                        data-bs-toggle="pill"
                                                                        href="#kt_timeline_widget_2_tab_1">
                                                                        <span class="nav-text fw-semibold fs-4 mb-3">Today
                                                                            Homeworks</span>
                                                                        <span
                                                                            class="bullet-custom position-absolute z-index-2 w-100 h-2px top-100 bottom-n100 bg-primary rounded"></span>
                                                                    </a>
                                                                </li>
                                                                <li class="nav-item p-0 ms-0 me-8">
                                                                    <a class="nav-link btn btn-color-muted px-0"
                                                                        data-bs-toggle="pill"
                                                                        href="#kt_timeline_widget_2_tab_2">
                                                                        <span
                                                                            class="nav-text fw-semibold fs-4 mb-3">Recent</span>
                                                                        <span
                                                                            class="bullet-custom position-absolute z-index-2 w-100 h-2px top-100 bottom-n100 bg-primary rounded"></span>
                                                                    </a>
                                                                </li>
                                                                <li class="nav-item p-0 ms-0">
                                                                    <a class="nav-link btn btn-color-muted px-0"
                                                                        data-bs-toggle="pill"
                                                                        href="#kt_timeline_widget_2_tab_3">
                                                                        <span
                                                                            class="nav-text fw-semibold fs-4 mb-3">Future</span>
                                                                        <span
                                                                            class="bullet-custom position-absolute z-index-2 w-100 h-2px top-100 bottom-n100 bg-primary rounded"></span>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="tab-content">
                                                                <div class="tab-pane fade show active"
                                                                    id="kt_timeline_widget_2_tab_1">
                                                                    <div class="table-responsive">
                                                                        <table class="table align-middle gs-0 gy-4">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th class="p-0 w-10px"></th>
                                                                                    <th class="p-0 w-25px"></th>
                                                                                    <th class="p-0 min-w-400px"></th>
                                                                                    <th class="p-0 min-w-100px"></th>
                                                                                    <th class="p-0 min-w-125px"></th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td>
                                                                                        <span data-kt-element="bullet"
                                                                                            class="bullet bullet-vertical d-flex align-items-center h-40px bg-success"></span>
                                                                                    </td>
                                                                                    <td class="ps-0">
                                                                                        <div
                                                                                            class="form-check form-check-custom form-check-success form-check-solid">
                                                                                            <input class="form-check-input"
                                                                                                type="checkbox" value=""
                                                                                                checked="checked"
                                                                                                data-kt-element="checkbox" />
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <a href="#"
                                                                                            class="text-gray-800 text-hover-primary fw-bold fs-6">Book
                                                                                            p. 77-85, read & complete tasks
                                                                                            1-6 on p. 85</a>
                                                                                        <span
                                                                                            class="text-gray-500 fw-bold fs-7 d-block">Physics</span>
                                                                                    </td>
                                                                                    <td class="text-end">
                                                                                        <span data-kt-element="status"
                                                                                            class="badge badge-light-success">Done</span>
                                                                                    </td>
                                                                                    <td class="text-end">
                                                                                        <div
                                                                                            class="d-flex justify-content-end flex-shrink-0">
                                                                                            <a href="#"
                                                                                                class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
                                                                                                <i
                                                                                                    class="ki-duotone ki-printer fs-3">
                                                                                                    <span
                                                                                                        class="path1"></span>
                                                                                                    <span
                                                                                                        class="path2"></span>
                                                                                                    <span
                                                                                                        class="path3"></span>
                                                                                                    <span
                                                                                                        class="path4"></span>
                                                                                                    <span
                                                                                                        class="path5"></span>
                                                                                                </i>
                                                                                            </a>
                                                                                            <a href="#"
                                                                                                class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
                                                                                                <i
                                                                                                    class="ki-duotone ki-sms fs-3">
                                                                                                    <span
                                                                                                        class="path1"></span>
                                                                                                    <span
                                                                                                        class="path2"></span>
                                                                                                </i>
                                                                                            </a>
                                                                                            <a href="#"
                                                                                                class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm">
                                                                                                <i
                                                                                                    class="ki-duotone ki-paper-clip fs-3"></i>
                                                                                            </a>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>
                                                                                        <span data-kt-element="bullet"
                                                                                            class="bullet bullet-vertical d-flex align-items-center h-40px bg-primary"></span>
                                                                                    </td>
                                                                                    <td class="ps-0">
                                                                                        <div
                                                                                            class="form-check form-check-custom form-check-solid">
                                                                                            <input class="form-check-input"
                                                                                                type="checkbox" value=""
                                                                                                data-kt-element="checkbox" />
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <a href="#"
                                                                                            class="text-gray-800 text-hover-primary fw-bold fs-6">Workbook
                                                                                            p. 17, tasks 1-6</a>
                                                                                        <span
                                                                                            class="text-gray-500 fw-bold fs-7 d-block">Mathematics</span>
                                                                                    </td>
                                                                                    <td class="text-end">
                                                                                        <span data-kt-element="status"
                                                                                            class="badge badge-light-primary">In
                                                                                            Process</span>
                                                                                    </td>
                                                                                    <td class="text-end">
                                                                                        <div
                                                                                            class="d-flex justify-content-end flex-shrink-0">
                                                                                            <a href="#"
                                                                                                class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
                                                                                                <i
                                                                                                    class="ki-duotone ki-printer fs-3">
                                                                                                    <span
                                                                                                        class="path1"></span>
                                                                                                    <span
                                                                                                        class="path2"></span>
                                                                                                    <span
                                                                                                        class="path3"></span>
                                                                                                    <span
                                                                                                        class="path4"></span>
                                                                                                    <span
                                                                                                        class="path5"></span>
                                                                                                </i>
                                                                                            </a>
                                                                                            <a href="#"
                                                                                                class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
                                                                                                <i
                                                                                                    class="ki-duotone ki-sms fs-3">
                                                                                                    <span
                                                                                                        class="path1"></span>
                                                                                                    <span
                                                                                                        class="path2"></span>
                                                                                                </i>
                                                                                            </a>
                                                                                            <a href="#"
                                                                                                class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm">
                                                                                                <i
                                                                                                    class="ki-duotone ki-paper-clip fs-3"></i>
                                                                                            </a>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>
                                                                                        <span data-kt-element="bullet"
                                                                                            class="bullet bullet-vertical d-flex align-items-center h-40px bg-success"></span>
                                                                                    </td>
                                                                                    <td class="ps-0">
                                                                                        <div
                                                                                            class="form-check form-check-custom form-check-success form-check-solid">
                                                                                            <input class="form-check-input"
                                                                                                type="checkbox" value=""
                                                                                                checked="checked"
                                                                                                data-kt-element="checkbox" />
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <a href="#"
                                                                                            class="text-gray-800 text-hover-primary fw-bold fs-6">Learn
                                                                                            paragraph p. 99, Exercise
                                                                                            1,2,3Scoping & Estimations</a>
                                                                                        <span
                                                                                            class="text-gray-500 fw-bold fs-7 d-block">Chemistry</span>
                                                                                    </td>
                                                                                    <td class="text-end">
                                                                                        <span data-kt-element="status"
                                                                                            class="badge badge-light-success">Done</span>
                                                                                    </td>
                                                                                    <td class="text-end">
                                                                                        <div
                                                                                            class="d-flex justify-content-end flex-shrink-0">
                                                                                            <a href="#"
                                                                                                class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
                                                                                                <i
                                                                                                    class="ki-duotone ki-printer fs-3">
                                                                                                    <span
                                                                                                        class="path1"></span>
                                                                                                    <span
                                                                                                        class="path2"></span>
                                                                                                    <span
                                                                                                        class="path3"></span>
                                                                                                    <span
                                                                                                        class="path4"></span>
                                                                                                    <span
                                                                                                        class="path5"></span>
                                                                                                </i>
                                                                                            </a>
                                                                                            <a href="#"
                                                                                                class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
                                                                                                <i
                                                                                                    class="ki-duotone ki-sms fs-3">
                                                                                                    <span
                                                                                                        class="path1"></span>
                                                                                                    <span
                                                                                                        class="path2"></span>
                                                                                                </i>
                                                                                            </a>
                                                                                            <a href="#"
                                                                                                class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm">
                                                                                                <i
                                                                                                    class="ki-duotone ki-paper-clip fs-3"></i>
                                                                                            </a>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>
                                                                                        <span data-kt-element="bullet"
                                                                                            class="bullet bullet-vertical d-flex align-items-center h-40px bg-primary"></span>
                                                                                    </td>
                                                                                    <td class="ps-0">
                                                                                        <div
                                                                                            class="form-check form-check-custom form-check-solid">
                                                                                            <input class="form-check-input"
                                                                                                type="checkbox" value=""
                                                                                                data-kt-element="checkbox" />
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <a href="#"
                                                                                            class="text-gray-800 text-hover-primary fw-bold fs-6">Write
                                                                                            essay 1000 words “WW2
                                                                                            results”</a>
                                                                                        <span
                                                                                            class="text-gray-500 fw-bold fs-7 d-block">History</span>
                                                                                    </td>
                                                                                    <td class="text-end">
                                                                                        <span data-kt-element="status"
                                                                                            class="badge badge-light-primary">In
                                                                                            Process</span>
                                                                                    </td>
                                                                                    <td class="text-end">
                                                                                        <div
                                                                                            class="d-flex justify-content-end flex-shrink-0">
                                                                                            <a href="#"
                                                                                                class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
                                                                                                <i
                                                                                                    class="ki-duotone ki-printer fs-3">
                                                                                                    <span
                                                                                                        class="path1"></span>
                                                                                                    <span
                                                                                                        class="path2"></span>
                                                                                                    <span
                                                                                                        class="path3"></span>
                                                                                                    <span
                                                                                                        class="path4"></span>
                                                                                                    <span
                                                                                                        class="path5"></span>
                                                                                                </i>
                                                                                            </a>
                                                                                            <a href="#"
                                                                                                class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
                                                                                                <i
                                                                                                    class="ki-duotone ki-sms fs-3">
                                                                                                    <span
                                                                                                        class="path1"></span>
                                                                                                    <span
                                                                                                        class="path2"></span>
                                                                                                </i>
                                                                                            </a>
                                                                                            <a href="#"
                                                                                                class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm">
                                                                                                <i
                                                                                                    class="ki-duotone ki-paper-clip fs-3"></i>
                                                                                            </a>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>
                                                                                        <span data-kt-element="bullet"
                                                                                            class="bullet bullet-vertical d-flex align-items-center h-40px bg-primary"></span>
                                                                                    </td>
                                                                                    <td class="ps-0">
                                                                                        <div
                                                                                            class="form-check form-check-custom form-check-solid">
                                                                                            <input class="form-check-input"
                                                                                                type="checkbox" value=""
                                                                                                data-kt-element="checkbox" />
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <a href="#"
                                                                                            class="text-gray-800 text-hover-primary fw-bold fs-6">Internal
                                                                                            conflicts in Philip Larkin
                                                                                            poems, read p 380-515</a>
                                                                                        <span
                                                                                            class="text-gray-500 fw-bold fs-7 d-block">English
                                                                                            Language</span>
                                                                                    </td>
                                                                                    <td class="text-end">
                                                                                        <span data-kt-element="status"
                                                                                            class="badge badge-light-primary">In
                                                                                            Process</span>
                                                                                    </td>
                                                                                    <td class="text-end">
                                                                                        <div
                                                                                            class="d-flex justify-content-end flex-shrink-0">
                                                                                            <a href="#"
                                                                                                class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
                                                                                                <i
                                                                                                    class="ki-duotone ki-printer fs-3">
                                                                                                    <span
                                                                                                        class="path1"></span>
                                                                                                    <span
                                                                                                        class="path2"></span>
                                                                                                    <span
                                                                                                        class="path3"></span>
                                                                                                    <span
                                                                                                        class="path4"></span>
                                                                                                    <span
                                                                                                        class="path5"></span>
                                                                                                </i>
                                                                                            </a>
                                                                                            <a href="#"
                                                                                                class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
                                                                                                <i
                                                                                                    class="ki-duotone ki-sms fs-3">
                                                                                                    <span
                                                                                                        class="path1"></span>
                                                                                                    <span
                                                                                                        class="path2"></span>
                                                                                                </i>
                                                                                            </a>
                                                                                            <a href="#"
                                                                                                class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm">
                                                                                                <i
                                                                                                    class="ki-duotone ki-paper-clip fs-3"></i>
                                                                                            </a>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                                <div class="tab-pane fade" id="kt_timeline_widget_2_tab_2">
                                                                    <div class="table-responsive">
                                                                        <table class="table align-middle gs-0 gy-4">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th class="p-0 w-10px"></th>
                                                                                    <th class="p-0 w-25px"></th>
                                                                                    <th class="p-0 min-w-400px"></th>
                                                                                    <th class="p-0 min-w-100px"></th>
                                                                                    <th class="p-0 min-w-125px"></th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td>
                                                                                        <span data-kt-element="bullet"
                                                                                            class="bullet bullet-vertical d-flex align-items-center h-40px bg-success"></span>
                                                                                    </td>
                                                                                    <td class="ps-0">
                                                                                        <div
                                                                                            class="form-check form-check-custom form-check-success form-check-solid">
                                                                                            <input class="form-check-input"
                                                                                                type="checkbox" value=""
                                                                                                checked="checked"
                                                                                                data-kt-element="checkbox" />
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <a href="#"
                                                                                            class="text-gray-800 text-hover-primary fw-bold fs-6">Book
                                                                                            p. 77-85, read & complete tasks
                                                                                            1-6 on p. 85</a>
                                                                                        <span
                                                                                            class="text-gray-500 fw-bold fs-7 d-block">Physics</span>
                                                                                    </td>
                                                                                    <td class="text-end">
                                                                                        <span data-kt-element="status"
                                                                                            class="badge badge-light-success">Done</span>
                                                                                    </td>
                                                                                    <td class="text-end">
                                                                                        <div
                                                                                            class="d-flex justify-content-end flex-shrink-0">
                                                                                            <a href="#"
                                                                                                class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
                                                                                                <i
                                                                                                    class="ki-duotone ki-printer fs-3">
                                                                                                    <span
                                                                                                        class="path1"></span>
                                                                                                    <span
                                                                                                        class="path2"></span>
                                                                                                    <span
                                                                                                        class="path3"></span>
                                                                                                    <span
                                                                                                        class="path4"></span>
                                                                                                    <span
                                                                                                        class="path5"></span>
                                                                                                </i>
                                                                                            </a>
                                                                                            <a href="#"
                                                                                                class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
                                                                                                <i
                                                                                                    class="ki-duotone ki-sms fs-3">
                                                                                                    <span
                                                                                                        class="path1"></span>
                                                                                                    <span
                                                                                                        class="path2"></span>
                                                                                                </i>
                                                                                            </a>
                                                                                            <a href="#"
                                                                                                class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm">
                                                                                                <i
                                                                                                    class="ki-duotone ki-paper-clip fs-3"></i>
                                                                                            </a>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>
                                                                                        <span data-kt-element="bullet"
                                                                                            class="bullet bullet-vertical d-flex align-items-center h-40px bg-primary"></span>
                                                                                    </td>
                                                                                    <td class="ps-0">
                                                                                        <div
                                                                                            class="form-check form-check-custom form-check-solid">
                                                                                            <input class="form-check-input"
                                                                                                type="checkbox" value=""
                                                                                                data-kt-element="checkbox" />
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <a href="#"
                                                                                            class="text-gray-800 text-hover-primary fw-bold fs-6">Workbook
                                                                                            p. 17, tasks 1-6</a>
                                                                                        <span
                                                                                            class="text-gray-500 fw-bold fs-7 d-block">Mathematics</span>
                                                                                    </td>
                                                                                    <td class="text-end">
                                                                                        <span data-kt-element="status"
                                                                                            class="badge badge-light-primary">In
                                                                                            Process</span>
                                                                                    </td>
                                                                                    <td class="text-end">
                                                                                        <div
                                                                                            class="d-flex justify-content-end flex-shrink-0">
                                                                                            <a href="#"
                                                                                                class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
                                                                                                <i
                                                                                                    class="ki-duotone ki-printer fs-3">
                                                                                                    <span
                                                                                                        class="path1"></span>
                                                                                                    <span
                                                                                                        class="path2"></span>
                                                                                                    <span
                                                                                                        class="path3"></span>
                                                                                                    <span
                                                                                                        class="path4"></span>
                                                                                                    <span
                                                                                                        class="path5"></span>
                                                                                                </i>
                                                                                            </a>
                                                                                            <a href="#"
                                                                                                class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
                                                                                                <i
                                                                                                    class="ki-duotone ki-sms fs-3">
                                                                                                    <span
                                                                                                        class="path1"></span>
                                                                                                    <span
                                                                                                        class="path2"></span>
                                                                                                </i>
                                                                                            </a>
                                                                                            <a href="#"
                                                                                                class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm">
                                                                                                <i
                                                                                                    class="ki-duotone ki-paper-clip fs-3"></i>
                                                                                            </a>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>
                                                                                        <span data-kt-element="bullet"
                                                                                            class="bullet bullet-vertical d-flex align-items-center h-40px bg-success"></span>
                                                                                    </td>
                                                                                    <td class="ps-0">
                                                                                        <div
                                                                                            class="form-check form-check-custom form-check-success form-check-solid">
                                                                                            <input class="form-check-input"
                                                                                                type="checkbox" value=""
                                                                                                checked="checked"
                                                                                                data-kt-element="checkbox" />
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <a href="#"
                                                                                            class="text-gray-800 text-hover-primary fw-bold fs-6">Learn
                                                                                            paragraph p. 99, Exercise
                                                                                            1,2,3Scoping & Estimations</a>
                                                                                        <span
                                                                                            class="text-gray-500 fw-bold fs-7 d-block">Chemistry</span>
                                                                                    </td>
                                                                                    <td class="text-end">
                                                                                        <span data-kt-element="status"
                                                                                            class="badge badge-light-success">Done</span>
                                                                                    </td>
                                                                                    <td class="text-end">
                                                                                        <div
                                                                                            class="d-flex justify-content-end flex-shrink-0">
                                                                                            <a href="#"
                                                                                                class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
                                                                                                <i
                                                                                                    class="ki-duotone ki-printer fs-3">
                                                                                                    <span
                                                                                                        class="path1"></span>
                                                                                                    <span
                                                                                                        class="path2"></span>
                                                                                                    <span
                                                                                                        class="path3"></span>
                                                                                                    <span
                                                                                                        class="path4"></span>
                                                                                                    <span
                                                                                                        class="path5"></span>
                                                                                                </i>
                                                                                            </a>
                                                                                            <a href="#"
                                                                                                class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
                                                                                                <i
                                                                                                    class="ki-duotone ki-sms fs-3">
                                                                                                    <span
                                                                                                        class="path1"></span>
                                                                                                    <span
                                                                                                        class="path2"></span>
                                                                                                </i>
                                                                                            </a>
                                                                                            <a href="#"
                                                                                                class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm">
                                                                                                <i
                                                                                                    class="ki-duotone ki-paper-clip fs-3"></i>
                                                                                            </a>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>
                                                                                        <span data-kt-element="bullet"
                                                                                            class="bullet bullet-vertical d-flex align-items-center h-40px bg-primary"></span>
                                                                                    </td>
                                                                                    <td class="ps-0">
                                                                                        <div
                                                                                            class="form-check form-check-custom form-check-solid">
                                                                                            <input class="form-check-input"
                                                                                                type="checkbox" value=""
                                                                                                data-kt-element="checkbox" />
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <a href="#"
                                                                                            class="text-gray-800 text-hover-primary fw-bold fs-6">Write
                                                                                            essay 1000 words “WW2
                                                                                            results”</a>
                                                                                        <span
                                                                                            class="text-gray-500 fw-bold fs-7 d-block">History</span>
                                                                                    </td>
                                                                                    <td class="text-end">
                                                                                        <span data-kt-element="status"
                                                                                            class="badge badge-light-primary">In
                                                                                            Process</span>
                                                                                    </td>
                                                                                    <td class="text-end">
                                                                                        <div
                                                                                            class="d-flex justify-content-end flex-shrink-0">
                                                                                            <a href="#"
                                                                                                class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
                                                                                                <i
                                                                                                    class="ki-duotone ki-printer fs-3">
                                                                                                    <span
                                                                                                        class="path1"></span>
                                                                                                    <span
                                                                                                        class="path2"></span>
                                                                                                    <span
                                                                                                        class="path3"></span>
                                                                                                    <span
                                                                                                        class="path4"></span>
                                                                                                    <span
                                                                                                        class="path5"></span>
                                                                                                </i>
                                                                                            </a>
                                                                                            <a href="#"
                                                                                                class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
                                                                                                <i
                                                                                                    class="ki-duotone ki-sms fs-3">
                                                                                                    <span
                                                                                                        class="path1"></span>
                                                                                                    <span
                                                                                                        class="path2"></span>
                                                                                                </i>
                                                                                            </a>
                                                                                            <a href="#"
                                                                                                class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm">
                                                                                                <i
                                                                                                    class="ki-duotone ki-paper-clip fs-3"></i>
                                                                                            </a>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                                <div class="tab-pane fade" id="kt_timeline_widget_2_tab_3">
                                                                    <div class="table-responsive">
                                                                        <table class="table align-middle gs-0 gy-4">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th class="p-0 w-10px"></th>
                                                                                    <th class="p-0 w-25px"></th>
                                                                                    <th class="p-0 min-w-400px"></th>
                                                                                    <th class="p-0 min-w-100px"></th>
                                                                                    <th class="p-0 min-w-125px"></th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td>
                                                                                        <span data-kt-element="bullet"
                                                                                            class="bullet bullet-vertical d-flex align-items-center h-40px bg-primary"></span>
                                                                                    </td>
                                                                                    <td class="ps-0">
                                                                                        <div
                                                                                            class="form-check form-check-custom form-check-solid">
                                                                                            <input class="form-check-input"
                                                                                                type="checkbox" value=""
                                                                                                data-kt-element="checkbox" />
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <a href="#"
                                                                                            class="text-gray-800 text-hover-primary fw-bold fs-6">Workbook
                                                                                            p. 17, tasks 1-6</a>
                                                                                        <span
                                                                                            class="text-gray-500 fw-bold fs-7 d-block">Mathematics</span>
                                                                                    </td>
                                                                                    <td class="text-end">
                                                                                        <span data-kt-element="status"
                                                                                            class="badge badge-light-primary">In
                                                                                            Process</span>
                                                                                    </td>
                                                                                    <td class="text-end">
                                                                                        <div
                                                                                            class="d-flex justify-content-end flex-shrink-0">
                                                                                            <a href="#"
                                                                                                class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
                                                                                                <i
                                                                                                    class="ki-duotone ki-printer fs-3">
                                                                                                    <span
                                                                                                        class="path1"></span>
                                                                                                    <span
                                                                                                        class="path2"></span>
                                                                                                    <span
                                                                                                        class="path3"></span>
                                                                                                    <span
                                                                                                        class="path4"></span>
                                                                                                    <span
                                                                                                        class="path5"></span>
                                                                                                </i>
                                                                                            </a>
                                                                                            <a href="#"
                                                                                                class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
                                                                                                <i
                                                                                                    class="ki-duotone ki-sms fs-3">
                                                                                                    <span
                                                                                                        class="path1"></span>
                                                                                                    <span
                                                                                                        class="path2"></span>
                                                                                                </i>
                                                                                            </a>
                                                                                            <a href="#"
                                                                                                class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm">
                                                                                                <i
                                                                                                    class="ki-duotone ki-paper-clip fs-3"></i>
                                                                                            </a>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>
                                                                                        <span data-kt-element="bullet"
                                                                                            class="bullet bullet-vertical d-flex align-items-center h-40px bg-success"></span>
                                                                                    </td>
                                                                                    <td class="ps-0">
                                                                                        <div
                                                                                            class="form-check form-check-custom form-check-success form-check-solid">
                                                                                            <input class="form-check-input"
                                                                                                type="checkbox" value=""
                                                                                                checked="checked"
                                                                                                data-kt-element="checkbox" />
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <a href="#"
                                                                                            class="text-gray-800 text-hover-primary fw-bold fs-6">Learn
                                                                                            paragraph p. 99, Exercise
                                                                                            1,2,3Scoping & Estimations</a>
                                                                                        <span
                                                                                            class="text-gray-500 fw-bold fs-7 d-block">Chemistry</span>
                                                                                    </td>
                                                                                    <td class="text-end">
                                                                                        <span data-kt-element="status"
                                                                                            class="badge badge-light-success">Done</span>
                                                                                    </td>
                                                                                    <td class="text-end">
                                                                                        <div
                                                                                            class="d-flex justify-content-end flex-shrink-0">
                                                                                            <a href="#"
                                                                                                class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
                                                                                                <i
                                                                                                    class="ki-duotone ki-printer fs-3">
                                                                                                    <span
                                                                                                        class="path1"></span>
                                                                                                    <span
                                                                                                        class="path2"></span>
                                                                                                    <span
                                                                                                        class="path3"></span>
                                                                                                    <span
                                                                                                        class="path4"></span>
                                                                                                    <span
                                                                                                        class="path5"></span>
                                                                                                </i>
                                                                                            </a>
                                                                                            <a href="#"
                                                                                                class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
                                                                                                <i
                                                                                                    class="ki-duotone ki-sms fs-3">
                                                                                                    <span
                                                                                                        class="path1"></span>
                                                                                                    <span
                                                                                                        class="path2"></span>
                                                                                                </i>
                                                                                            </a>
                                                                                            <a href="#"
                                                                                                class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm">
                                                                                                <i
                                                                                                    class="ki-duotone ki-paper-clip fs-3"></i>
                                                                                            </a>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>
                                                                                        <span data-kt-element="bullet"
                                                                                            class="bullet bullet-vertical d-flex align-items-center h-40px bg-primary"></span>
                                                                                    </td>
                                                                                    <td class="ps-0">
                                                                                        <div
                                                                                            class="form-check form-check-custom form-check-solid">
                                                                                            <input class="form-check-input"
                                                                                                type="checkbox" value=""
                                                                                                data-kt-element="checkbox" />
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <a href="#"
                                                                                            class="text-gray-800 text-hover-primary fw-bold fs-6">Write
                                                                                            essay 1000 words “WW2
                                                                                            results”</a>
                                                                                        <span
                                                                                            class="text-gray-500 fw-bold fs-7 d-block">History</span>
                                                                                    </td>
                                                                                    <td class="text-end">
                                                                                        <span data-kt-element="status"
                                                                                            class="badge badge-light-primary">In
                                                                                            Process</span>
                                                                                    </td>
                                                                                    <td class="text-end">
                                                                                        <div
                                                                                            class="d-flex justify-content-end flex-shrink-0">
                                                                                            <a href="#"
                                                                                                class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
                                                                                                <i
                                                                                                    class="ki-duotone ki-printer fs-3">
                                                                                                    <span
                                                                                                        class="path1"></span>
                                                                                                    <span
                                                                                                        class="path2"></span>
                                                                                                    <span
                                                                                                        class="path3"></span>
                                                                                                    <span
                                                                                                        class="path4"></span>
                                                                                                    <span
                                                                                                        class="path5"></span>
                                                                                                </i>
                                                                                            </a>
                                                                                            <a href="#"
                                                                                                class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
                                                                                                <i
                                                                                                    class="ki-duotone ki-sms fs-3">
                                                                                                    <span
                                                                                                        class="path1"></span>
                                                                                                    <span
                                                                                                        class="path2"></span>
                                                                                                </i>
                                                                                            </a>
                                                                                            <a href="#"
                                                                                                class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm">
                                                                                                <i
                                                                                                    class="ki-duotone ki-paper-clip fs-3"></i>
                                                                                            </a>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>
                                                                                        <span data-kt-element="bullet"
                                                                                            class="bullet bullet-vertical d-flex align-items-center h-40px bg-primary"></span>
                                                                                    </td>
                                                                                    <td class="ps-0">
                                                                                        <div
                                                                                            class="form-check form-check-custom form-check-solid">
                                                                                            <input class="form-check-input"
                                                                                                type="checkbox" value=""
                                                                                                data-kt-element="checkbox" />
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <a href="#"
                                                                                            class="text-gray-800 text-hover-primary fw-bold fs-6">Internal
                                                                                            conflicts in Philip Larkin
                                                                                            poems, read p 380-515</a>
                                                                                        <span
                                                                                            class="text-gray-500 fw-bold fs-7 d-block">English
                                                                                            Language</span>
                                                                                    </td>
                                                                                    <td class="text-end">
                                                                                        <span data-kt-element="status"
                                                                                            class="badge badge-light-primary">In
                                                                                            Process</span>
                                                                                    </td>
                                                                                    <td class="text-end">
                                                                                        <div
                                                                                            class="d-flex justify-content-end flex-shrink-0">
                                                                                            <a href="#"
                                                                                                class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
                                                                                                <i
                                                                                                    class="ki-duotone ki-printer fs-3">
                                                                                                    <span
                                                                                                        class="path1"></span>
                                                                                                    <span
                                                                                                        class="path2"></span>
                                                                                                    <span
                                                                                                        class="path3"></span>
                                                                                                    <span
                                                                                                        class="path4"></span>
                                                                                                    <span
                                                                                                        class="path5"></span>
                                                                                                </i>
                                                                                            </a>
                                                                                            <a href="#"
                                                                                                class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
                                                                                                <i
                                                                                                    class="ki-duotone ki-sms fs-3">
                                                                                                    <span
                                                                                                        class="path1"></span>
                                                                                                    <span
                                                                                                        class="path2"></span>
                                                                                                </i>
                                                                                            </a>
                                                                                            <a href="#"
                                                                                                class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm">
                                                                                                <i
                                                                                                    class="ki-duotone ki-paper-clip fs-3"></i>
                                                                                            </a>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="dersler">
                                            <div class="row g-5 g-xxl-8">
                                                <?php //$student->showLessonsListForStudentDetailsPage($userInfo['class_id'], $userInfo['school_id']); ?>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="ozel_dersler">
                                            <div class="card mb-5 mb-lg-10">
                                                <div class="card-header">
                                                    <div class="card-title">
                                                        <h3>Alınan Özel Dersler</h3>
                                                    </div>
                                                    <div class="card-toolbar">
                                                        <div class="my-1 me-4">
                                                            <!-- <select class="form-select form-select-sm form-select-solid w-125px" data-control="select2" data-placeholder="Select Hours" data-hide-search="true">
                                                                <option value="1" selected="selected">1 Hours</option>
                                                                <option value="2">6 Hours</option>
                                                                <option value="3">12 Hours</option>
                                                                <option value="4">24 Hours</option>
                                                            </select> -->
                                                        </div>
                                                        <!-- <a href="#" class="btn btn-sm btn-primary my-1">View All</a> -->
                                                    </div>
                                                </div>
                                                <div class="card-body p-0">
                                                    <div class="table-responsive">
                                                        <table
                                                            class="table align-middle table-row-bordered table-row-solid gy-4 gs-9">
                                                            <thead class="border-gray-200 fs-5 fw-semibold bg-lighten">
                                                                <tr>
                                                                    <th class="min-w-250px">Adı</th>
                                                                    <th class="min-w-100px">Sınıf</th>
                                                                    <th class="min-w-100px">Ders</th>
                                                                    <th class="min-w-100px">Ünite</th>
                                                                    <th class="min-w-100px">Konu</th>
                                                                    <th class="min-w-100px">Alt Konu</th>
                                                                    <th class="min-w-150px">Öğretmen</th>
                                                                    <th class="min-w-150px">Fiyatı</th>
                                                                    <th class="min-w-150px">Zaman</th>
                                                                    <th class="min-w-150px">Durum</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="fw-6 fw-semibold text-gray-600">
                                                                <tr>
                                                                    <td>
                                                                        <a href="#"
                                                                            class="text-hover-primary text-gray-600">Özel
                                                                            Ders 1</a>
                                                                    </td>
                                                                    <td>
                                                                        1. Sınıf
                                                                    </td>
                                                                    <td>Hayat Bilgisi</td>
                                                                    <td>Doğada Hayat</td>
                                                                    <td>Konusu</td>
                                                                    <td>Alt Konusu</td>
                                                                    <td>Öğretmen A</td>
                                                                    <td>400₺</td>
                                                                    <td>20.05.2025</td>
                                                                    <td>Girdi</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <a href="#"
                                                                            class="text-hover-primary text-gray-600">Özel
                                                                            Ders 2</a>
                                                                    </td>
                                                                    <td>
                                                                        1. Sınıf
                                                                    </td>
                                                                    <td>İngilizce</td>
                                                                    <td>1. Ünite</td>
                                                                    <td>Konusu</td>
                                                                    <td>Alt Konusu</td>
                                                                    <td>Öğretmen B</td>
                                                                    <td>400₺</td>
                                                                    <td>21.05.2025</td>
                                                                    <td>Girmedi</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="grup_dersler">
                                            <div class="card mb-5 mb-lg-10">
                                                <div class="card-header">
                                                    <div class="card-title">
                                                        <h3>Alınan Grup Dersler</h3>
                                                    </div>
                                                    <div class="card-toolbar">
                                                        <div class="my-1 me-4">
                                                            <!-- <select class="form-select form-select-sm form-select-solid w-125px" data-control="select2" data-placeholder="Select Hours" data-hide-search="true">
                                                                <option value="1" selected="selected">1 Hours</option>
                                                                <option value="2">6 Hours</option>
                                                                <option value="3">12 Hours</option>
                                                                <option value="4">24 Hours</option>
                                                            </select> -->
                                                        </div>
                                                        <!-- <a href="#" class="btn btn-sm btn-primary my-1">View All</a> -->
                                                    </div>
                                                </div>
                                                <div class="card-body p-0">
                                                    <div class="table-responsive">
                                                        <table
                                                            class="table align-middle table-row-bordered table-row-solid gy-4 gs-9">
                                                            <thead class="border-gray-200 fs-5 fw-semibold bg-lighten">
                                                                <tr>
                                                                    <th class="min-w-250px">Adı</th>
                                                                    <th class="min-w-100px">Sınıf</th>
                                                                    <th class="min-w-100px">Ders</th>
                                                                    <th class="min-w-100px">Ünite</th>
                                                                    <th class="min-w-100px">Konu</th>
                                                                    <th class="min-w-100px">Alt Konu</th>
                                                                    <th class="min-w-150px">Öğretmen</th>
                                                                    <th class="min-w-150px">Fiyatı</th>
                                                                    <th class="min-w-150px">Zaman</th>
                                                                    <th class="min-w-150px">Durum</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="fw-6 fw-semibold text-gray-600">
                                                                <tr>
                                                                    <td>
                                                                        <a href="#"
                                                                            class="text-hover-primary text-gray-600">Grup
                                                                            Ders 1</a>
                                                                    </td>
                                                                    <td>
                                                                        1. Sınıf
                                                                    </td>
                                                                    <td>Hayat Bilgisi</td>
                                                                    <td>Doğada Hayat</td>
                                                                    <td>Konusu</td>
                                                                    <td>Alt Konusu</td>
                                                                    <td>Öğretmen A</td>
                                                                    <td>400₺</td>
                                                                    <td>20.05.2025</td>
                                                                    <td>Girdi</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <a href="#"
                                                                            class="text-hover-primary text-gray-600">Grup
                                                                            Ders 2</a>
                                                                    </td>
                                                                    <td>
                                                                        1. Sınıf
                                                                    </td>
                                                                    <td>İngilizce</td>
                                                                    <td>1. Ünite</td>
                                                                    <td>Konusu</td>
                                                                    <td>Alt Konusu</td>
                                                                    <td>Öğretmen B</td>
                                                                    <td>400₺</td>
                                                                    <td>21.05.2025</td>
                                                                    <td>Girmedi</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="paketler">
                                            <div class="card mb-5 mb-lg-10">
                                                <div class="card-header">
                                                    <div class="card-title">
                                                        <h3>Alınan Paketler</h3>
                                                    </div>
                                                    <div class="card-toolbar">
                                                        <div class="my-1 me-4">
                                                            <!-- <select class="form-select form-select-sm form-select-solid w-125px" data-control="select2" data-placeholder="Select Hours" data-hide-search="true">
                                                                <option value="1" selected="selected">1 Hours</option>
                                                                <option value="2">6 Hours</option>
                                                                <option value="3">12 Hours</option>
                                                                <option value="4">24 Hours</option>
                                                            </select> -->
                                                        </div>
                                                        <!-- <a href="#" class="btn btn-sm btn-primary my-1">View All</a> -->
                                                    </div>
                                                </div>
                                                <div class="card-body p-0">
                                                    <div class="table-responsive">
                                                        <table
                                                            class="table align-middle table-row-bordered table-row-solid gy-4 gs-9">
                                                            <thead class="border-gray-200 fs-5 fw-semibold bg-lighten">
                                                                <tr>
                                                                    <th class="min-w-250px">Paket Adı</th>
                                                                    <th class="min-w-100px">Sınıf</th>
                                                                    <th class="min-w-150px">Fiyatı</th>
                                                                    <th class="min-w-150px text-end">Bitiş Tarihi</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="fw-6 fw-semibold text-gray-600">
                                                                <?php $student->showPackageDetailsListForStudentDetails($userInfo['id']); ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card mb-5 mb-lg-10">
                                                <div class="card-header">
                                                    <div class="card-title">
                                                        <h3>Alınan Ek Paketler</h3>
                                                    </div>
                                                    <div class="card-toolbar">
                                                        <div class="my-1 me-4">
                                                            <!-- <select class="form-select form-select-sm form-select-solid w-125px" data-control="select2" data-placeholder="Select Hours" data-hide-search="true">
                                                                <option value="1" selected="selected">1 Hours</option>
                                                                <option value="2">6 Hours</option>
                                                                <option value="3">12 Hours</option>
                                                                <option value="4">24 Hours</option>
                                                            </select> -->
                                                        </div>
                                                        <!-- <a href="#" class="btn btn-sm btn-primary my-1">View All</a> -->
                                                    </div>
                                                </div>
                                                <div class="card-body p-0">
                                                    <div class="table-responsive">
                                                        <table
                                                            class="table align-middle table-row-bordered table-row-solid gy-4 gs-9">
                                                            <thead class="border-gray-200 fs-5 fw-semibold bg-lighten">
                                                                <tr>
                                                                    <th class="min-w-250px">Paket Adı</th>
                                                                    <th class="min-w-100px">Sınıf</th>
                                                                    <th class="min-w-150px">Fiyatı</th>
                                                                    <th class="min-w-150px text-end">Bitiş Tarihi</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="fw-6 fw-semibold text-gray-600">
                                                                <?php $student->showAdditionalPackageDetailsListForStudentDetails($userInfo['id']); ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="hareketler">

                                            <div class="card mb-5 mb-lg-10">
                                                <div class="card-header">
                                                    <div class="card-title">
                                                        <h3>Giriş Bilgileri</h3>
                                                    </div>
                                                    <div class="card-toolbar">
                                                        <div class="my-1 me-4">
                                                            <!-- <select class="form-select form-select-sm form-select-solid w-125px" data-control="select2" data-placeholder="Select Hours" data-hide-search="true">
                                                                <option value="1" selected="selected">1 Hours</option>
                                                                <option value="2">6 Hours</option>
                                                                <option value="3">12 Hours</option>
                                                                <option value="4">24 Hours</option>
                                                            </select> -->
                                                        </div>
                                                        <!-- 
                                                        <a href="#" class="btn btn-sm btn-primary my-1">View All</a> -->
                                                    </div>
                                                </div>
                                                <div class="card-body p-0">
                                                    <div class="table-responsive">
                                                        <table
                                                            class="table align-middle table-row-bordered table-row-solid gy-4 gs-9">
                                                            <thead class="border-gray-200 fs-5 fw-semibold bg-lighten">
                                                                <tr>
                                                                    <th class="min-w-250px">Cihaz Tipi</th>
                                                                    <th class="min-w-100px">Cihaz Modeli</th>
                                                                    <th class="min-w-150px">İşletim Sistemi</th>
                                                                    <th class="min-w-150px">Tarayıcı</th>
                                                                    <th class="min-w-150px">Ekran Çözünürlüğü</th>
                                                                    <th class="min-w-150px">IP Adresi</th>
                                                                    <th class="min-w-150px">Giriş Zamanı</th>
                                                                    <th class="min-w-150px">Çıkış Zamanı</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="fw-6 fw-semibold text-gray-600">
                                                                <?php $student->showLoginDetailsListForStudentDetails($userInfo['id']); ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
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
        <!-- Modals -->
        <div>
            <?php include_once "views/profile/update-student-form.php"; ?>
        </div>
        <div>
            <?php include_once "views/profile/update-password-form.php"; ?>
        </div>
        <div>
            <?php include_once "views/profile/update-email-form.php"; ?>
        </div>
        <!-- End Modals -->

        <script>
            var hostUrl = "assets/";
        </script>
        <script src="assets/plugins/global/plugins.bundle.js"></script>
        <script src="assets/js/scripts.bundle.js"></script>
        <script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>

        <script src="assets/js/custom/pages/user-profile/general.js"></script>

        <script src="assets/js/custom/pages/user-profile/update.js"></script>
        <script src="assets/js/custom/pages/user-profile/update-password.js"></script>
        <script src="assets/js/custom/pages/user-profile/update-email.js"></script>

        <script src="assets/js/widgets.bundle.js"></script>
        <script src="assets/js/custom/widgets.js"></script>
        <script src="assets/js/custom/apps/chat/chat.js"></script>
        <script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
        <script src="assets/js/custom/utilities/modals/create-account.js"></script>
        <script src="assets/js/custom/utilities/modals/create-app.js"></script>
        <script src="assets/js/custom/utilities/modals/offer-a-deal/type.js"></script>
        <script src="assets/js/custom/utilities/modals/offer-a-deal/details.js"></script>
        <script src="assets/js/custom/utilities/modals/offer-a-deal/finance.js"></script>
        <script src="assets/js/custom/utilities/modals/offer-a-deal/complete.js"></script>
        <script src="assets/js/custom/utilities/modals/offer-a-deal/main.js"></script>
        <script src="assets/js/custom/utilities/modals/users-search.js"></script>
    </body>

    </html>
<?php } else {
    header("location: index");
}
