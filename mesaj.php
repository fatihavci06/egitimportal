<?php

error_reporting(E_ALL);
ini_set("", 1);

session_start();
define('GUARD', true);
if (!isset($_SESSION['role'])) {
    header("HTTP/1.0 404 Not Found");
    header("Location: 404.php");
    exit();
}
include_once "classes/dbh.classes.php";

include_once "views/pages-head.php";
include_once "classes/dateformat.classes.php";
$dateFormat = new DateFormat();
include_once "classes/message.classes.php";

$message_id = $_GET['q'];
$user_id = $_SESSION['id'];
$messageObj = new Message();
$message = $messageObj->getMessageById($message_id, $user_id);
// try {


// } catch (Exception $e) {
//     header("HTTP/1.0 404 Not Found");
//     header("Location: 404.php");
//     exit();
// }
$mark = $messageObj->markAsRead($message_id, $user_id);
// echo '<pre>';
// var_dump($mark);
// echo '</pre>';

// die();

?>
<!DOCTYPE html>
<html lang="tr">


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

                                        </ul>
                                        <h1
                                            class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bolder fs-1 lh-0">
                                            <?php echo $message['subject'] ?>
                                        </h1>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="kt_app_content" class="app-content flex-column-fluid">
                            <div id="kt_app_content_container" class="app-container container-fluid">
                                <div class="card">
                                    <div class="card-body p-lg-10">
                                        <div class="d-flex flex-column flex-lg-row mb-17">
                                            <div class="flex-lg-row-fluid row">
                                                <div class="mb-2">
                                                    <p class="text-gray-800 fs-4">
                                                        <?php echo $message['body'] ?>
                                                    </p>
                                                </div>

                                                <div class="d-flex flex-wrap align-items-center">
                                                    <div class="d-flex align-items-center me-5 mb-2">
                                                        <span class="svg-icon svg-icon-4 me-1">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none">
                                                                <path opacity="0.3"
                                                                    d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM12 7C10.3 7 9 8.3 9 10C9 11.7 10.3 13 12 13C13.7 13 15 11.7 15 10C15 8.3 13.7 7 12 7Z"
                                                                    fill="currentColor" />
                                                                <path
                                                                    d="M12 22C14.6 22 17 21 18.7 19.4C17.9 16.9 15.2 15 12 15C8.8 15 6.09999 16.9 5.29999 19.4C6.99999 21 9.4 22 12 22Z"
                                                                    fill="currentColor" />
                                                            </svg>
                                                        </span>
                                                        <span class="text-gray-600 fw-bold">Gönderen:
                                                            <?php if ($message['sender_id'] != $user_id) {
                                                                echo $message['sender_name'] . " " . $message['sender_surname'];
                                                            } else {
                                                                echo "Ben";
                                                            } ?>
                                                        </span>
                                                    </div>

                                                    <div class="d-flex align-items-center me-5 mb-2">
                                                        <span class="svg-icon svg-icon-4 me-1">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none">
                                                                <path opacity="0.3"
                                                                    d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM12 7C10.3 7 9 8.3 9 10C9 11.7 10.3 13 12 13C13.7 13 15 11.7 15 10C15 8.3 13.7 7 12 7Z"
                                                                    fill="currentColor" />
                                                                <path
                                                                    d="M12 22C14.6 22 17 21 18.7 19.4C17.9 16.9 15.2 15 12 15C8.8 15 6.09999 16.9 5.29999 19.4C6.99999 21 9.4 22 12 22Z"
                                                                    fill="currentColor" />
                                                            </svg>
                                                        </span>
                                                        <span class="text-gray-600 fw-bold">Alıcı:
                                                            <?php if ($message['receiver_id'] != $user_id) {
                                                                echo $message['sender_name'] . " " . $message['sender_surname'];
                                                            } else {
                                                                echo "Bana";
                                                            } ?>
                                                        </span>
                                                    </div>

                                                    <div class="d-flex align-items-center me-5 mb-2">
                                                        <span class="svg-icon svg-icon-4 me-1">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none">
                                                                <path opacity="0.3"
                                                                    d="M21 22H3C2.4 22 2 21.6 2 21V5C2 4.4 2.4 4 3 4H21C21.6 4 22 4.4 22 5V21C22 21.6 21.6 22 21 22Z"
                                                                    fill="currentColor" />
                                                                <path
                                                                    d="M6 6C5.4 6 5 5.6 5 5V3C5 2.4 5.4 2 6 2C6.6 2 7 2.4 7 3V5C7 5.6 6.6 6 6 6ZM11 5V3C11 2.4 10.6 2 10 2C9.4 2 9 2.4 9 3V5C9 5.6 9.4 6 10 6C10.6 6 11 5.6 11 5ZM15 5V3C15 2.4 14.6 2 14 2C13.4 2 13 2.4 13 3V5C13 5.6 13.4 6 14 6C14.6 6 15 5.6 15 5ZM19 5V3C19 2.4 18.6 2 18 2C17.4 2 17 2.4 17 3V5C17 5.6 17.4 6 18 6C18.6 6 19 5.6 19 5Z"
                                                                    fill="currentColor" />
                                                                <path
                                                                    d="M8.8 13.1C9.2 13.1 9.5 13 9.7 12.8C9.9 12.6 10.1 12.3 10.1 11.9C10.1 11.6 10 11.3 9.8 11.1C9.6 10.9 9.3 10.8 9 10.8C8.8 10.8 8.59999 10.8 8.39999 10.9C8.19999 11 8.1 11.1 8 11.2C7.9 11.3 7.8 11.4 7.7 11.6C7.6 11.8 7.5 11.9 7.5 12.1C7.5 12.2 7.4 12.2 7.3 12.3C7.2 12.4 7.09999 12.4 6.89999 12.4C6.69999 12.4 6.6 12.3 6.5 12.2C6.4 12.1 6.3 11.9 6.3 11.7C6.3 11.5 6.4 11.3 6.5 11.1C6.6 10.9 6.7 10.7 6.9 10.5C7.1 10.3 7.3 10.2 7.5 10.1C7.7 10 7.9 9.90002 8.2 9.90002C8.5 9.90002 8.7 9.90002 8.9 10C9.1 10.1 9.3 10.2 9.4 10.3C9.5 10.4 9.6 10.6 9.7 10.8C9.8 11 9.8 11.1 9.8 11.3C9.8 11.4 9.7 11.5 9.6 11.6C9.5 11.7 9.4 11.7 9.3 11.7C9.2 11.7 9.09999 11.6 9.09999 11.5C9.09999 11.4 9.09999 11.3 9.09999 11.2C9.09999 11.1 9 11 8.9 10.9C8.8 10.8 8.7 10.7 8.5 10.7C8.3 10.7 8.2 10.7 8 10.8C7.9 10.9 7.8 11 7.7 11.1C7.6 11.2 7.5 11.3 7.5 11.5C7.5 11.7 7.6 11.8 7.7 11.9C7.8 12 8 12.1 8.2 12.1C8.3 12.1 8.5 12.1 8.7 12C8.8 11.9 8.9 11.8 9 11.7C9.1 11.6 9.2 11.4 9.2 11.3C9.2 11.2 9.2 11.1 9.3 11.1C9.4 11 9.5 10.9 9.6 10.9C9.7 10.9 9.8 11 9.8 11.1C9.8 11.2 9.8 11.3 9.8 11.5C9.8 11.7 9.7 11.9 9.6 12.1C9.5 12.3 9.3 12.4 9.1 12.5C8.9 12.6 8.7 12.7 8.4 12.7C8.1 12.7 7.9 12.6 7.7 12.5C7.5 12.4 7.3 12.2 7.2 12.1C7.1 11.9 7 11.7 7 11.5C7 11.3 7 11.1 7.1 10.9C7.2 10.7 7.3 10.5 7.4 10.4C7.5 10.3 7.6 10.2 7.8 10.1C7.9 10 8.1 9.90002 8.3 9.90002C8.5 9.90002 8.7 9.90002 8.8 10C9 10.1 9.1 10.2 9.2 10.3C9.3 10.4 9.4 10.6 9.4 10.8C9.4 10.9 9.4 11 9.3 11.1C9.2 11.2 9.1 11.2 9 11.2C8.9 11.2 8.8 11.1 8.8 11C8.8 10.9 8.8 10.8 8.9 10.7C8.9 10.6 9 10.5 9.1 10.5C9.2 10.5 9.2 10.5 9.3 10.6C9.4 10.7 9.4 10.8 9.4 11C9.4 11.2 9.3 11.3 9.2 11.4C9.1 11.5 8.9 11.6 8.8 11.6C8.7 11.6 8.5 11.6 8.4 11.5C8.3 11.4 8.2 11.3 8.2 11.1C8.2 10.9 8.2 10.8 8.3 10.6C8.4 10.4 8.5 10.3 8.6 10.2C8.7 10.1 8.9 10 9.1 10C9.3 10 9.5 10.1 9.6 10.2C9.7 10.3 9.8 10.5 9.8 10.7C9.8 10.9 9.7 11.1 9.6 11.2C9.5 11.3 9.4 11.4 9.2 11.5C9 11.6 8.8 11.7 8.6 11.7C8.4 11.7 8.2 11.6 8 11.5C7.8 11.4 7.7 11.2 7.6 11C7.5 10.8 7.5 10.6 7.5 10.4C7.5 10.2 7.6 10 7.7 9.8C7.8 9.6 8 9.4 8.2 9.3C8.4 9.2 8.6 9.10002 8.9 9.10002C9.2 9.10002 9.5 9.20002 9.7 9.30002C9.9 9.40002 10.1 9.60002 10.2 9.80002C10.3 10 10.4 10.2 10.4 10.5C10.4 10.8 10.3 11 10.2 11.2C10.1 11.4 9.9 11.6 9.7 11.7C9.5 11.8 9.3 11.9 9 12C8.7 12.1 8.5 12.1 8.2 12.1Z"
                                                                    fill="currentColor" />
                                                            </svg>
                                                        </span>
                                                        <span class="text-gray-600 fw-bold">
                                                            <?php echo $dateFormat->changeDateHourSecond($message['created_at']) ?>
                                                        </span>
                                                    </div>
                                                    <div class="d-flex align-items-center me-5 mb-2">
                                                        <span class="svg-icon svg-icon-4 me-1">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" fill="none" viewBox="0 0 24 24">
                                                                <path d="M20 6L9 17l-5-5" stroke="currentColor"
                                                                    stroke-width="2" fill="none" />
                                                            </svg>
                                                        </span>
                                                        <span
                                                            class="fw-bold <?php echo ($message['receiver_id']==$user_id) ? 'text-success' : 'text-warning'; ?>">
                                                            <?php echo ($message['receiver_id']==$user_id)  ? 'Okundu' : 'Okunmadı'; ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Content wrapper-->
                        <?php include_once "views/footer.php"; ?>
                        <!--end::Footer-->
                    </div>
                    <!--begin::aside-->
                    <?php include_once "views/aside.php"; ?>
                    <!--end::aside-->
                </div>
            </div>
        </div>
        <!--begin::Scrolltop-->
        <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
            <i class="ki-duotone ki-arrow-up">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </div>
        <!--end::Scrolltop-->
        <!--begin::Javascript-->
        <script>
            var hostUrl = "assets/";
        </script>
        <!--begin::Global Javascript Bundle(mandatory for all pages)-->
        <script src="assets/plugins/global/plugins.bundle.js"></script>
        <script src="assets/js/scripts.bundle.js"></script>
        <!--end::Global Javascript Bundle-->
        <!--begin::Vendors Javascript(used for this page only)-->
        <script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
        <!--end::Vendors Javascript-->
        <!--begin::Custom Javascript(used for this page only)-->

        <script src="assets/js/widgets.bundle.js"></script>
        <script src="assets/js/custom/widgets.js"></script>

        <!--end::Custom Javascript-->
        <!--end::Javascript-->
</body>

</html>