<?php
session_start();

if (!isset($_SESSION['role'])) {
    header("HTTP/1.0 404 Not Found");
    header("Location: 404.php");
    exit();
}


define('GUARD', true);
include_once "classes/dbh.classes.php";
include_once "classes/user.classes.php";
include_once "classes/timespend.classes.php";
include_once "views/pages-head.php";

$timeSpend = new timeSpend();
$userObj = new User();
$userInfo = $userObj->getUserById($_SESSION['id']);

$timeSpendInfo = $timeSpend->getTimeSpend($userInfo["id"]);

?>
<!DOCTYPE html>
<html lang="tr">

</html>

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

    <?php
    // Page content should go here 
    
    if ($_SESSION['role'] == 1) {

        require_once "views/profile/profilim-admin.php";

    } elseif ($_SESSION["role"] == 2) {
        require_once "classes/student.classes.php";
        require_once "classes/student-view.classes.php";
        require_once "classes/school.classes.php";
        require_once "classes/classes.classes.php";
        require_once "classes/classes-view.classes.php";
        require_once "classes/lessons.classes.php";
        require_once "classes/lessons-view.classes.php";

        $student = new ShowStudent();
        $school = new School();

        $schoolInfo = $school->getOneSchoolById($userInfo['school_id']);

        $studentPackages = $student->getStudentPackages($userInfo["id"]);

        $studentAdditionalPackages = $student->getStudentAdditionalPackages($userInfo["id"]);

        $studentClassName = $student->getStudentClass($userInfo['class_id']);
        require_once "views/profile/profilim-ogrenci.php";

    } elseif ($_SESSION["role"] == 4 || $_SESSION["role"] == 10001 || $_SESSION["role"] == 9 || $_SESSION["role"] == 10) {
        include_once "classes/school.classes.php";

        $school = new School();

        $schoolInfo = $school->getOneSchoolById($userInfo['school_id']);
        // ogretmen profilim sayfasi
        require_once "views/profile/profilim-ogretmenler.php";


    } elseif ($_SESSION["role"] == 3) {

        require_once "views/profile/profilim-koordinator.php";

    } elseif ($_SESSION["role"] == 5 OR $_SESSION["role"] == 10005) {

        require_once "views/profile/profilim-veli.php";

    } elseif ($_SESSION["role"] == 8) {

        require_once "views/profile/profilim-okul-admin.php";

    } elseif ($_SESSION["role"] == 10002) {

        require_once "views/profile/profilim-anaokul-ogrenci.php";

    } else {
        header("HTTP/1.0 404 Not Found");
        header("Location: 404.php");
        exit();
    }
    // Page content should go here 
    
    ?>
    <!-- Modals -->
    <div>
        <?php include_once "views/profile/partials/update-user-info-form.php"; ?>
    </div>
    <div>
        <?php include_once "views/profile/partials/update-password-form.php"; ?>
    </div>
    <div>
        <?php include_once "views/profile/partials/update-email-form.php"; ?>
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
    <!-- <script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
    <script src="assets/js/custom/utilities/modals/create-account.js"></script>
    <script src="assets/js/custom/utilities/modals/create-app.js"></script>
    <script src="assets/js/custom/utilities/modals/offer-a-deal/type.js"></script>
    <script src="assets/js/custom/utilities/modals/offer-a-deal/details.js"></script>
    <script src="assets/js/custom/utilities/modals/offer-a-deal/finance.js"></script>
    <script src="assets/js/custom/utilities/modals/offer-a-deal/complete.js"></script>
    <script src="assets/js/custom/utilities/modals/offer-a-deal/main.js"></script>
    <script src="assets/js/custom/utilities/modals/users-search.js"></script> -->

    <script src="assets/js/custom/apps/profile/trackprogress.js"></script>
    <?php
    if ($_SESSION["role"] == 4 || $_SESSION["role"] == 10001 || $_SESSION["role"] == 9 || $_SESSION["role"] == 10) {
        echo '<script src="assets/js/custom/apps/teacher-details/curriculum.js"></script>';
    }
    ?>


</body>

</html>