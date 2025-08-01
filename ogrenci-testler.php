<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 2 or $_SESSION['role'] == 4)) {
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";

    include_once "views/pages-head.php";
    $classes = new Classes();

    $dataTest = $classes->getTestListByStudent($_SESSION['class_id']);
    $lessonList = $classes->getLessonsList($_SESSION['class_id']);

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
        <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
            <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
                <?php include_once "views/header.php"; ?>
                <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                    <?php include_once "views/sidebar.php"; ?>
                    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                        <div class="d-flex flex-column flex-column-fluid" <?php if (isset($_SESSION['role']) && $_SESSION['role'] != 1) echo 'style="margin-top: 0px;"'; ?>>
                            <?php include_once "views/toolbar.php"; ?>
                            <div id="kt_app_content" class="app-content flex-column-fluid">
                                <div id="kt_app_content_container" class="app-container container-fluid">
                                    <div class="card" style="margin-top: -20px;">
                                        <header class="container-fluid bg-custom-light py-3 d-flex justify-content-between align-items-center
                                             border-top border-bottom border-custom-red" style="border-width: 5px !important;">

                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-danger me-3 shadow icon-circle-lg d-flex justify-content-center align-items-center"
                                                    style="width: 80px; height: 80px;">
                                                    <i class="fas fa-check-square fa-2x text-white"></i>
                                                </div>

                                                <h1 class="fs-3 fw-bold text-dark mb-0">Test Listesi</h1>
                                            </div>

                                        </header>
                                        <!--begin::Card header-->
                                        <div class="card-header border-0 pt-6">
                                            <!--begin::Card title-->
                                            <div class="card-title">
                                            </div>
                                            <!--begin::Card title-->
                                            <!--begin::Card toolbar-->
                                            <div class="card-toolbar">
                                                <!--begin::Toolbar-->
                                                <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                                                    <!--begin::Add school-->

                                                    <!--end::Add school-->
                                                </div>
                                                <!--end::Toolbar-->
                                                <!--begin::Group actions-->

                                                <!--end::Group actions-->
                                            </div>
                                            <!--end::Card toolbar-->
                                        </div>
                                        <!--end::Card header-->
                                        <!--begin::Card body-->
                                        <div class="card-body pt-0" style="margin-top:-20px;">
                                            <div class="row">
                                                <div class="col-3 col-lg-2">
                                                    <div class="row g-10 ">
                                                        <?php foreach ($lessonList as $l): ?>
                                                            <?php if ($l['name'] !== 'Robotik Kodlama' && $l['name'] !== 'Ders Deneme'): ?>
                                                                <div class="col-12 mb-1 text-center">
                                                                    <a href="ders/<?= urlencode($l['slug']) ?>">
                                                                        <img src="assets/media/icons/dersler/<?= htmlspecialchars($l['icons']) ?>" alt="Icon" class="img-fluid" style="width: 65px; height: 65px; object-fit: contain;" />

                                                                        <div class="mt-1"><?= htmlspecialchars($l['name']) ?></div>
                                                                    </a>
                                                                </div>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                                <div class="col-9 col-lg-10">
                                                <?php
// Türkçe ay isimleri
$turkishMonths = [
    1 => 'Ocak', 2 => 'Şubat', 3 => 'Mart', 4 => 'Nisan',
    5 => 'Mayıs', 6 => 'Haziran', 7 => 'Temmuz', 8 => 'Ağustos',
    9 => 'Eylül', 10 => 'Ekim', 11 => 'Kasım', 12 => 'Aralık'
];
function formatDate($dateStr) {
    global $turkishMonths;
    if (!$dateStr) return '-';
    try {
        $dt = new DateTime($dateStr);
        return $dt->format('j') . ' ' . $turkishMonths[(int)$dt->format('n')];
    } catch (\Exception $e) {
        return '-';
    }
}
$today = new DateTime('now');
?>
<!-- DataTable için container -->
<table id="singleColumnTable" class="table table-borderless w-100">
    <thead class="d-none"><!-- başlık gizli, sadece arka planda lazım -->
        <tr><th>Testler</th></tr>
    </thead>
    <tbody>
        <?php foreach ($dataTest as $test):
            $start = !empty($test['start_date']) ? new DateTime($test['start_date']) : null;
            $end = !empty($test['end_date']) ? new DateTime($test['end_date']) : null;
            $range = ( $start ? formatDate($start->format('Y-m-d')) : '-' )
                   . ' - '
                   . ( $end ? formatDate($end->format('Y-m-d')) : '-' );
            if ($end) {
                $remaining = $today <= $end ? $today->diff($end)->days : 0;
            } else {
                $remaining = null;
            }
        ?>
        <tr>
            <td class="p-0">
                <div class="mb-3 p-3 border rounded shadow-sm w-100" style="border:2px solid #333;">
                    <div class="row align-items-center">
                        <div class="col-4 d-flex align-items-center">
                            <a href="ogrenci-test-coz.php?id=<?=$test['id']?>" class="text-decoration-none text-dark start-exam d-flex align-items-center" data-id="<?= htmlspecialchars($test['id']) ?>">
                                <button type="button" class="btn btn-light btn-sm me-2">
                                    <i style="font-size:16px;" class="bi bi-play-fill"></i>
                                </button>
                                <h5 class="card-title mb-0 fw-semibold" style="font-size:1.2rem;">
                                    <?= htmlspecialchars($test['test_title']) ?>
                                </h5>
                            </a>
                        </div>

                        <div class="col-4 d-flex align-items-center">
                            <i class="fas fa-calendar-alt text-primary me-2"></i>
                            <small class="text-muted" style="font-size:1.1rem;">
                                <?= $range ?>
                            </small>
                        </div>

                        <div class="col-4 text-end">
                            <?php if ($remaining === null): ?>
                                <span class="badge bg-secondary">Tarih yok</span>
                            <?php elseif ($remaining > 0): ?>
                                <span class="badge bg-success">Kalan: <?= $remaining ?> gün</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Süre doldu</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

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
        <script>
    $(function(){
        $('#singleColumnTable').DataTable({
            // sadece tek sütun var; varsayılan sıralamayı kapatabiliriz
            ordering: false,
            info: true,
            paging: true,
            lengthChange: false,
            pageLength: 5, // isteğe göre değiştir
            searching: true,
            language: {
                search: "Ara:",
                paginate: {
                    previous: "Önceki",
                    next: "Sonraki"
                },
                info: "_TOTAL_ içerikten _PAGE_ sayfası gösteriliyor",
                infoEmpty: "Gösterilecek içerik yok",
                zeroRecords: "Eşleşen sonuç yok"
            },
            // görünümü daha "kart" odaklı göstermek için satır içinde padding zaten var
            drawCallback: function() {
                // ek davranış gerekiyorsa burada koy
            }
        });

        // Örnek: tıklayınca seçili görünümü verme
        $('#singleColumnTable tbody').on('click', 'tr', function(){
            $(this).toggleClass('selected');
        });
    });
</script>
    </body>

</html>
<?php } else {
    header("location: index");
}
