<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 10001 or $_SESSION['role'] == 10002)) {
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";

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
                    <form action="kullanici-odeme-raporu-excel.php" method="post">
    <button type="submit">Excel'e Aktar</button>
</form>
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
                                    <!--begin::Card-->
                                    <div class="card-body pt-5">


                                        <div class="row mt-4">
                                            
                                            
                                            <div class="col-lg-4">
                                                <label class="fs-6 fw-semibold mb-2" for="week">Kullanıcı </label>
                                                <?php
                                                $class = new Classes();
                                                $studentList = $class->getStudentList();
                                                ?>
                                                <select class="form-select" id="student" required aria-label="Default select example">
                                                    <option value="">Seçiniz</option>
                                                    <?php foreach ($studentList as $student) { ?>
                                                        <option value="<?= $student['id'] ?>"><?= $student['fullname'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>




                                        <div class="row mt-4">
                                            <div class="mt-4 text-start">
                                                <button id="filterBtn" class="btn btn-primary">Filtrele</button>
                                                <button id="clearFilterBtn" class="btn btn-secondary">Filtreyi Temizle</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        
                                        <table id="paymentReport" class="table align-middle table-row-dashed fs-6 gy-5">
                                            <thead>

                                            </thead>
                                            <tbody></tbody>
                                        </table>
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
            $(document).ready(function() {

                $('#student').select2({
                    placeholder: "Seçiniz",
                    allowClear: true
                });
                
                $('#filterBtn').on('click', function(e) {
                    e.preventDefault();

                    const data = {
                        student: $('#student').val(),
                       

                    };

                    $.ajax({
                        url: 'includes/ajax.php?service=filter-payment-report-byuser', // 🔁 burayı backend URL'in ile değiştir
                        method: 'POST',
                        data: data,
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success' && Array.isArray(response.data)) {

                                // DataTable önceden başlatıldıysa temizle
                                if ($.fn.DataTable.isDataTable('#paymentReport')) {
                                    $('#paymentReport').DataTable().clear().destroy();
                                }

                                // Yeni tabloyu başlat
                                $('#paymentReport').DataTable({
                                    data: response.data,
                                     searching: true,
                                    columns: [
                                        {
                                            data: 'order_no',
                                            title: 'Sipariş No'
                                        },
                                        {
                                            data: 'fullname',
                                            title: 'Kişi'
                                        },
                                        {
                                            data: 'payment_date',
                                            title: 'Ödeme Tarihi'
                                        },
                                        {
                                            data: 'payment_type',
                                            title: 'Ödeme Türü'
                                        },
                                        {
                                            data: 'payment_total',
                                            title: 'Toplam Ödeme'
                                        },
                                        {
                                            data: 'tax',
                                            title: 'Toplam Vergi'
                                        },
                                        {
                                            data: 'payment_status',
                                            title: 'Ödeme Durumu'
                                        }
                                    ]
                                });

                            } else {
                                alert("Veri boş geldi veya status success değil.");
                            }
                        },
                        error: function(err) {
                            console.error("Hata:", err);
                        }
                    });
                });


            });
            $('#clearFilterBtn').on('click', function() {
                $('#month').val('');

                // select2 ile oluşturulan selectbox'ları sıfırla ve görünümü güncelle
                $('#student').val('').trigger('change');
              


                // Eğer DataTable varsa içeriğini temizle
                if ($.fn.DataTable.isDataTable('#paymentReport')) {
                    $('#paymentReport').DataTable().clear().draw();
                }

                // (Opsiyonel) select2 kullanıyorsan .trigger('change') ekle
                // $('.form-select').val('').trigger('change');

                // Eğer tablo daha önce yüklenmişse sıfırla
                if ($.fn.DataTable.isDataTable('#paymentReport')) {
                    $('#paymentReport').DataTable().clear().draw();
                }
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
