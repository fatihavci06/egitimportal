<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role'])) {
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";

    include_once "views/pages-head.php";
?>
    <!--end::Head-->

    <head>
        <style>
            /* Minimal custom style for colors not in Bootstrap's palette, 
           or for specific border widths if Bootstrap's are not enough. */
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
        </style>
    </head>
    <!--begin::Body-->

    <body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" data-kt-app-aside-enabled="true" data-kt-app-aside-fixed="true" data-kt-app-aside-push-toolbar="true" data-kt-app-aside-push-footer="true" class="app-default">
        <!--begin::Theme mode setup on page load-->
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
        <!--end::Theme mode setup on page load-->
        <!--begin::App-->
        <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
            <!--begin::Page-->
            <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
                <!--begin::Header-->
                <?php include_once "views/header.php"; ?>
                <!--end::Header-->
                <!--begin::Wrapper-->
                <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                    <!--begin::Sidebar-->
                    <?php include_once "views/sidebar.php"; ?>
                    <!--end::Sidebar-->
                    <!--begin::Main-->
                    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                        <!--begin::Content wrapper-->
                        <div class="d-flex flex-column flex-column-fluid">
                            <!--begin::Toolbar-->
                            <?php include_once "views/toolbar.php"; ?>
                            <!--end::Toolbar-->
                            <!--begin::Content-->
                            <div id="kt_app_content" class="app-content flex-column-fluid" style="padding-top: 0px !important;">
                                <!--begin::Content container-->
                                <div id="kt_app_content_container" class="app-container container-fluid">
                                    <header class="container-fluid bg-custom-light py-3 d-flex justify-content-between align-items-center
                           border-top border-bottom border-custom-red" style="border-width: 5px !important;">
                                        <div class="d-flex align-items-center">
                                            <div class=" me-3 icon-circle-lg">
                                                <img src="assets/media/mascots/lineup-robot-maskot.png" style="width: 100px;">
                                            </div>

                                            <h1 class="fs-3 fw-bold text-dark mb-0">Yapay Zekaya Sorunu Sor</h1>
                                        </div>
                                    </header>
                                    <!--begin::Card-->
                                    <div class=" bg-light">
                                        <div class="card shadow-lg border-0 rounded-4" style="width: 100%; max-width: 1500; background: #fefefe;">
                                            <!-- <div class="card-header text-white rounded-top-4 py-3 px-4" style="justify-content: normal;">
                                                <h5 class="mb-0">Yapay Zekaya Sorunu Sor</h5> <img src="assets/media/mascots/lineup-robot-maskot.png" style="width: 100px;">
                                            </div> -->
                                            <div class="card-body px-4 pt-4 pb-3 d-flex flex-column" style="background-color: #f8f9fa;">
                                                <div id="chat-box" class="mb-3 p-3 rounded bg-white shadow-sm overflow-auto" style="height: 300px;"></div>
                                                <div class="input-group">
                                                    <input type="text" id="userInput" class="form-control rounded-start-pill" placeholder="Mesajınızı yazın...">
                                                    <button class="btn btn-primary rounded-end-pill px-4" id="sendBtn">Gönder</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>





                                </div>
                                <!--end::Card-->
                            </div>
                            <!--end::Content container-->
                        </div>
                        <!--end::Content-->
                    </div>
                    <!--end::Content wrapper-->
                    <!--begin::Footer-->
                    <?php include_once "views/footer.php"; ?>
                    <!--end::Footer-->
                </div>
                <!--end:::Main-->
                <!--begin::aside-->
                <?php include_once "views/aside.php"; ?>
                <!--end::aside-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
        </div>
        <!--end::App-->
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
            $('#sendBtn').click(function() {
                let message = $('#userInput').val().trim();
                if (message === '') return;

                // Kullanıcı mesajını kutuya ekle
                $('#chat-box').append('<div class="user-msg"><strong>Sen:</strong> ' + message + '</div>');
                $('#userInput').val('');
                $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);

                // Yazıyor... mesajını ekle
                $('#chat-box').append('<div class="bot-msg typing-msg" id="typingMsg"><em>Lineup yazıyor...</em></div>');
                $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);

                // AJAX ile PHP dosyasına gönder
                $.ajax({
                    url: 'chatgpt.php',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        message: message
                    }),
                    success: function(res) {
                        // Yazıyor... mesajını kaldır
                        $('#typingMsg').remove();

                        let content = res.choices[0].message.content;
                        $('#chat-box').append('<div class="bot-msg"><strong>Lineup:</strong> ' + content + '</div>');
                        $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
                    },
                    error: function() {
                        $('#typingMsg').remove();
                        $('#chat-box').append('<div class="bot-msg text-danger">Bir hata oluştu.</div>');
                    }
                });
            });


            // Enter tuşuna basınca gönder
            $('#userInput').keypress(function(e) {
                if (e.which === 13) $('#sendBtn').click();
            });
        </script>

        <!--end::Custom Javascript-->
        <!--end::Javascript-->
    </body>
    <!--end::Body-->

</html>
<?php } else {
    header("location: index");
}
