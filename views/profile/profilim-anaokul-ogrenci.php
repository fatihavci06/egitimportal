<?php
if (!isset($userInfo)) {
    throw new Exception("userInfo not provided");
}


include_once "classes/dbh.classes.php";
include_once "classes/teacher.classes.php";
include_once "classes/teacher-view.classes.php";
include_once "classes/school.classes.php";
include_once "classes/timespend.classes.php";
$teacherId = new Teacher();
$teacher = new ShowTeacher();
$timeSpend = new timeSpend();
$school = new School();
include_once "classes/student.classes.php";
include_once "classes/student-view.classes.php";
$studentObj = new ShowStudent();

require_once "classes/content-tracker.classes.php";
require_once "classes/grade-result.classes.php";

$contentObj = new ContentTracker();
$gradeObj = new GradeResult();
$getTeacherId = $_SESSION['id'];

$userInfo = $teacher->getOneTeacher($getTeacherId);

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
                                <div class="row container-fluid" style="margin-top:-19px; padding-right:0px;margin-right:0px !important;">
                                            <header class="container-fluid bg-custom-light py-3 d-flex justify-content-between align-items-center
                                             border-top border-bottom border-custom-red mb-2" style="    margin-top: 5px; border-width: 5px !important; height:85px;margin-bottom: 26px !important;">

                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-danger me-3 shadow icon-circle-lg d-flex justify-content-center align-items-center"
                                                        style="width: 65px; height: 65px;">
                                                        <i class="fa-regular fa-user fa-2x text-white"></i>
                                                    </div>

                                                    <h1 class="fs-3 fw-bold text-dark mb-0">Hesabım</h1>
                                                </div>

                                            </header>
                                        </div>
                                <!-- <div class="page-title d-flex flex-column gap-1 me-3 mb-2">
                                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold mb-6">
                                        <li class="breadcrumb-item text-gray-700 fw-bold lh-1">
                                            <a href="index.html" class="text-gray-500 text-hover-primary">
                                                <i class="ki-duotone ki-home fs-3 text-gray-500 me-n1"></i>
                                            </a>
                                        </li>
                                        <li class="breadcrumb-item">
                                            <i class="ki-duotone ki-right fs-4 text-gray-700 mx-n1"></i>
                                        </li>
                                        <li class="breadcrumb-item text-gray-700">Ana Okulu Öğrencisi Detay</li>
                                    </ul>
                                    <h1
                                        class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bolder fs-1 lh-0">
                                        Ana Okulu Öğrencisi Detay
                                    </h1>
                                </div> -->
                            </div>
                        </div>
                    </div>
                    <div id="kt_app_content" class="app-content flex-column-fluid" style="padding-top: 0px;">
                        <div id="kt_app_content_container" class="app-container container-fluid">
                            <div class="card mb-5 mb-xxl-8">
                                <div class="card-body pt-9 pb-0">
                                    <!-- includeded profile-card -->
                                    <?php include_once "views/profile/partials/profile-card.php"; ?>
            
                                    <hr>

                    
                                </div>

                            </div>
                            <!--content here -->
                        </div>
                    </div>
                </div>
                <?php include_once "views/footer.php"; ?>
            </div>
            <?php include_once "views/aside.php"; ?>
        </div>
    </div>
</div>