<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 10001)) {
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";
    include "classes/addcoupon.classes.php";

    include_once "views/pages-head.php";
?>
    <!--end::Head-->
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
                            <div id="kt_app_content" class="app-content flex-column-fluid">
                                <!--begin::Content container-->
                                <div id="kt_app_content_container" class="app-container container-fluid">
                                    <!--begin::Card(FORM)-->
                                    <form id="coupon_form">
                                        <div class="fv-row">
                                            <label>İndirim Türü</label><br>
                                            <label><input type="radio" name="discount_type" id="discount_type_percent" value="percentage"> Yüzde</label>
                                            <label><input type="radio" name="discount_type" id="discount_type_amount" value="amount">TL</label>
                                        </div>

                                        <div class="fv-row">
                                            <label>İndirim Değeri</label>
                                            <input type="text" id="discount_value" name="discount_value" class="form-control" />
                                        </div>

                                        <div class="fv-row">
                                            <div id="couponCodeContainer" style="display: none;">
                                                <label>Kupon Kodu</label>
                                                <input type="text" id="coupon_code" name="coupon_code" id="coupon_code" class="form-control" readonly />
                                            </div>
                                        </div>
                                        <div class="fv-row">
                                            <div id="couponExpiresContainer" style="display: none;">
                                                <label>Kupon Tarihi</label>
                                                <input type="text" id="coupon_expire" name="coupon_expire" id="coupon_expire" class="form-control" readonly />
                                            </div>
                                        </div>

                                        <button class="btn btn-primary mt-5 type=" button" id="generate_coupon">Kupon Oluştur</button>
                                        <button class="btn btn-primary mt-5 type=" button" id="coupon_submit">Kaydet</button>
                                    </form>
                                    <!--end::Card(FORM)-->
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
        <script src="assets/js/custom/apps/coupon/add.js"></script>
        <!-- <script src="assets/js/custom/pages/coupon/coupon.js"></script> -->
        <!--begin::Global Javascript Bundle(mandatory for all pages)-->
        <script src="assets/plugins/global/plugins.bundle.js"></script>
        <script src="assets/js/scripts.bundle.js"></script>
        <!--end::Global Javascript Bundle-->
        <!--begin::Vendors Javascript(used for this page only)-->
        <script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
        <!--end::Vendors Javascript-->
        <!--begin::Custom Javascript(used for this page only)-->
        <script src="assets/plugins/custom/tinymce/tinymce.bundle.js"></script>
        <script src="assets/js/custom/apps/topics/list/export.js"></script>
        <script src="assets/js/custom/apps/topics/list/list.js"></script>
        <!--<script src="assets/js/custom/apps/topics/list/topicadd.js"></script>-->
        <!-- <script src="assets/js/custom/apps/topics/add.js"></script> -->
        <!--<script src="assets/js/custom/apps/topics/create.js"></script>-->
        <script src="assets/js/widgets.bundle.js"></script>
        <script src="assets/js/custom/widgets.js"></script>
        <script src="assets/js/custom/apps/chat/chat.js"></script>
        <script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
        <script src="assets/js/custom/utilities/modals/create-account.js"></script>
        <script src="assets/js/custom/utilities/modals/create-app.js"></script>
        <script src="assets/js/custom/utilities/modals/users-search.js"></script>
        <!--end::Custom Javascript-->
        <!--end::Javascript-->
        <script>
            $('#generate_coupon').on('click', function(e) {
                e.preventDefault();
                const generatedCoupon = generateCouponCode();
                $('#coupon_code').val(generatedCoupon);
            });

            function generateCouponCode(length = 8) {
                const characters = "QWERTYUOPASDFGHJKLZXCVBNM123456789";
                let coupon = "";
                for (let i = 0; i < length; i++) {
                    coupon += characters.charAt(Math.floor(Math.random() * characters.length));
                }
                return coupon;
            }

            $('#coupon_submit').on('click', function(e) {
                e.preventDefault();

                const coupon_code = $('#coupon_code').val(); // Kupon kodu
                const discount_type = $('input[name="discount_type"]:checked').val();
                const discount_value = $('#discount_value').val();

                if (!discount_type) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Uyarı',
                        text: 'Kupon Tipi seçin.',
                        confirmButtonText: 'Tamam'
                    });
                    return;
                }

                if (!coupon_code || !discount_value) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Uyarı',
                        text: 'Lütfen tüm alanları doldurun.',
                        confirmButtonText: 'Tamam'
                    });
                    return;
                }

                $.ajax({
                    url: 'includes/addcoupon.inc.php',
                    method: 'POST',
                    data: {
                        coupon_code: coupon_code,
                        discount_type: discount_type,
                        discount_value: discount_value
                    },
                    success: function(response) {
                        const res = JSON.parse(response);
                        Swal.fire({
                            icon: 'success',
                            title: 'Başarılı',
                            text: 'Kupon başarıyla oluşturuldu!',
                            confirmButtonText: 'Tamam'
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Hata',
                            text: 'Bir hata oluştu: ' + error,
                            confirmButtonText: 'Tamam'
                        });
                    }
                });
            });
        </script>
    </body>
    <!--end::Body-->

</html>
<?php } else {
    header("location: index");
}
