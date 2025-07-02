<?php
if (!isset($userInfo)) {
    throw new Exception("userInfo not provided");
}


include_once "classes/dbh.classes.php";
include_once "classes/Guardian.php";
include_once "classes/school.classes.php";
include_once "classes/timespend.classes.php";
$guadianObj = new Guardian();
$timeSpend = new timeSpend();
$school = new School();
include_once "classes/student.classes.php";
include_once "classes/student-view.classes.php";
$studentObj = new ShowStudent();

require_once "classes/content-tracker.classes.php";
require_once "classes/grade-result.classes.php";

$contentObj = new ContentTracker();
$gradeObj = new GradeResult();
$userId = $_SESSION['id'];

$userInfo = $guadianObj->getOneGaurdian($userId);

?>
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
                                        <li class="breadcrumb-item text-gray-700">Profilim</li>
                                    </ul>
                                    <h1
                                        class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bolder fs-1 lh-0">
                                        Profilim
                                    </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="kt_app_content" class="app-content flex-column-fluid">
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