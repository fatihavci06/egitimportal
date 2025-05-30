<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 2 or $_SESSION['role'] == 4)) {
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";

    include_once "views/pages-head.php";
    $dataTest = new Classes();

    $dataTest = $dataTest->getTestListByStudent($_SESSION['class_id']);

?>

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
        <div class="d-flex flex-column flex-root app-root" id="kt_app_root" style="margin-top:-135px;">
            <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
                <?php include_once "views/header.php"; ?>
                <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                    <?php include_once "views/sidebar.php"; ?>
                    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                        <div class="d-flex flex-column flex-column-fluid">
                            <?php include_once "views/toolbar.php"; ?>
                            <div id="kt_app_content" class="app-content flex-column-fluid">
                                <div id="kt_app_content_container" class="app-container container-fluid">
                                    <table id="example" class="table align-middle table-row-dashed fs-6 gy-5">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Test Başlığı</th>
                                                <th>Bitiş Tarihi</th>
                                                <th>İşlem</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($dataTest as $row): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($row['id']) ?></td>
                                                    <td><?= htmlspecialchars($row['test_title']) ?></td>
                                                    <td><?= htmlspecialchars($row['end_date']) ?></td>
                                                    <td>
                                                        <?php if (isset($row['fail_count']) && $row['fail_count'] >= 3): ?>
                                                            <span class="badge badge-danger">Sonuç: <?= htmlspecialchars($row['score']) ?> Puan</span>
                                                        <?php elseif (isset($row['score']) && $row['score'] >= 80): ?>
                                                            <span class="badge badge-success">Sonuç: <?= htmlspecialchars($row['score']) ?> Puan</span>
                                                        <?php else: ?>
                                                            <button class="btn btn-primary start-exam-btn btn-sm" data-id="<?= $row['id'] ?>">Sınava Gir</button>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>


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
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


        <script>
            $(document).ready(function() {
                $('#example').DataTable({
                    order: [
                        [0, 'desc']
                    ]

                });
                $(document).on('click', '.start-exam-btn', function() {
                    var examId = $(this).data('id');

                    Swal.fire({
                        title: 'Emin misiniz?',
                        text: 'Sınavınız başlayacaktır.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Evet, başla!',
                        cancelButtonText: 'Vazgeç'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Burada yönlendirme veya işlem yapılabilir
                            console.log('Sınav başlatılıyor. ID:', examId);
                            window.open('ogrenci-test-coz.php?id=' + examId, '_blank');
                        }
                    });
                });
            });
        </script>
    </body>

</html>
<?php } else {
    header("location: index");
}
