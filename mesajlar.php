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
$user_id = $_SESSION["id"];

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
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
            <!--begin::Header-->
            <?php include_once "views/header.php"; ?>
            <!--end::Header-->
            <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                <!--begin::Sidebar-->
                <?php include_once "views/sidebar.php"; ?>
                <!--end::Sidebar-->
                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    <div class="d-flex flex-column flex-column-fluid">
                        <!--begin::Toolbar-->
                        <?php include_once "views/toolbar.php"; ?>
                        <!--end::Toolbar-->
                        <div id="kt_app_content" class="app-content flex-column-fluid">
                            <div id="kt_app_content_container" class="app-container container-fluid">
                                <div class="card">
                                    <div class="card-header border-0 pt-6">
                                        <div class="card-title">
                                            <div class="d-flex align-items-center position-relative my-1">
                                                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                                <input type="text" data-kt-customer-table-filter="search"
                                                    class="form-control form-control-solid w-250px ps-12"
                                                    placeholder="Bildirim Ara" />
                                            </div>
                                        </div>

                                    </div>
                                    <div class="card-body pt-0">
                                        <table class="table align-middle table-row-dashed fs-6 gy-5"
                                            id="kt_customers_table">
                                            <thead>
                                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                                    <th class="min-w-80px">Başlık</th>
                                                    <th class="min-w-40px">Durum</th>
                                                    <th class="min-w-150px">İçerik</th>
                                                    <th class="min-w-80px">Kimden</th>
                                                    <th class="min-w-80px">Kime</th>
                                                    <th class="min-w-40px">Gönderi Tarihi</th>

                                                </tr>
                                            </thead>
                                            <tbody class="fw-semibold text-gray-600">
                                                <?php

                                                $messages = (new Message())->getInbox($_SESSION["id"]);
                                                foreach ($messages as $message) {

                                                    $sender = "Ben";
                                                    if ($message['sender_id'] != $user_id) {
                                                        $sender = $message['sender_name'] . " " . $message['sender_surname'];
                                                    }
                                                    $receiver = "Ben";
                                                    if ($message['receiver_id'] != $user_id) {
                                                        $receiver = $message['receiver_name'] . " " . $message['receiver_surname'];
                                                    }

                                                    $read_status = '<span class="badge badge-light-success">Okundu</span>';
                                                    if (!$message['is_read']) {
                                                        $read_status = '<span class="badge badge-light-danger">Okunmadı</span>';
                                                    }
                                                    $id = $message['id'];
                                                    $notificationList = '
                                                        <tr id="' . $message['id'] . '">
        
                                                            <td>
                                                                <a href="./mesaj/' . $id . '" class="text-gray-800 text-hover-primary mb-1">'
                                                        . (strlen($message['subject']) > 30 ? substr($message['subject'], 0, 30) . '...' : $message['subject']) .

                                                        '</a>
                                                            </td>
                                                            <td>
                                                            ' . $read_status . '
                                                            </td>
                                                            <td>
                                                            ' . (strlen($message['body']) > 30 ? substr($message['body'], 0, 30) . '...' : $message['body']) . '
                                                            </td>
                                                            <td>
                                                                ' . $sender . '
                                                            </td>
                                                            <td>
                                                                 ' . $receiver . '
                                                            </td>
                                                            <td>' . $dateFormat->changeDate($message['created_at']) . '</td>
                                                        </tr>
                                                    ';
                                                    echo $notificationList;
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!--begin::Modal - Customers - Add-->
                                <!--end::Modal - Customers - Add-->
                            </div>
                        </div>
                    </div>
                    <!--begin::Footer-->
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

    <script src="assets/js/custom/apps/messages/list.js"></script>

    <script src="assets/js/widgets.bundle.js"></script>
    <script src="assets/js/custom/widgets.js"></script>
    <!--end::Custom Javascript-->
    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>