<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role'])) {
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";
    $liveVideoList = new Classes();

    if ($_SESSION['class_id']) {
        $list = $liveVideoList->getLiveVideo($_SESSION['class_id']);
    } else {
        $list = $liveVideoList->getLiveVideo();
    }
   $classList = $liveVideoList->getClassesList();
   
    if ($_SESSION['role'] == 1) {
        $classList = $liveVideoList->getClasses();
    }  else if ($_SESSION['role'] == 2 || $_SESSION['role'] == 4) {
        $classList = $liveVideoList->getClassesList();
    } else if ($_SESSION['role'] == 10001 || $_SESSION['role'] == 10002|| $_SESSION['role'] == 10005) {
        $classList = $liveVideoList->getAgeGroup();
    }




    include_once "views/pages-head.php";
?>

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

    <body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" data-kt-app-aside-enabled="true" data-kt-app-aside-fixed="true" data-kt-app-aside-push-toolbar="true" data-kt-app-aside-push-footer="true" class="app-default">
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
                        <div class="d-flex flex-column flex-column-fluid">
                            <?php include_once "views/toolbar.php"; ?>
                            <div id="kt_app_content" class="app-content flex-column-fluid pt-0">
                                <div id="kt_app_content_container" class="app-container container-fluid">

                                    <header class="container-fluid bg-custom-light py-3 d-flex justify-content-between align-items-center border-top border-bottom border-custom-red" style="border-width: 5px !important;">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3 icon-circle-lg">
                                                <img src="assets/media/mascots/lineup-robot-maskot.png" style="width: 80px;" alt="Maskot">
                                            </div>
                                            <h1 class="fs-3 fw-bold text-dark mb-0">Canlı Video</h1>
                                        </div>
                                    </header>

                                    <div class="row mt-4">
                                        <div class="col-12 col-md-12 col-lg-12">
                                            <div class="card shadow-lg border-0 rounded-4 bg-light">
                                                <div class="card-body px-4 pt-4 pb-3 d-flex flex-column" style="background-color: #f8f9fa;">
                                                    <div class="card-header d-flex justify-content-between align-items-center">
                                                        <h3 class="card-title mb-0">Canlı Dersler</h3>
                                                        <?php
                                                        $allowedRoles = [1, 3, 4, 7, 8, 10001];
                                                        if (in_array($_SESSION['role'], $allowedRoles)) {
                                                        ?>
                                                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#meetingModal">
                                                                Canlı Ders Oluştur
                                                            </button>
                                                        <?php } ?>

                                                    </div>
                                                    <div class="card-body px-4 pt-4 pb-3" style="background-color: #f8f9fa;">
                                                        <table id="meetingTable" class="table table-striped table-bordered align-middle">
                                                            <thead>
                                                                <tr>
                                                                    <th>Başlık</th>
                                                                    <th>Tarih & Saat</th>
                                                                    <th>Yaş Grubu</th>
                                                                    <th>İşlem</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php if (!empty($list)): ?>
                                                                    <?php foreach ($list as $row): ?>
                                                                        <tr>
                                                                            <td><?= htmlspecialchars($row['description']) ?></td>

                                                                            <td><?= date('d-m-Y H:i', strtotime($row['meeting_date'])) ?></td>
                                                                            <td><?= htmlspecialchars($row['class_name']) ?></td>
                                                                            <td>
                                                                                <?php if ($_SESSION['role'] == 1 || $_SESSION['role'] == 10001): ?>
                                                                                    <a href="<?= htmlspecialchars($row['zoom_start_url']) ?>" target="_blank" class="btn btn-success btn-sm me-2">
                                                                                        <i class="fas fa-video"></i> Canlı Ders Başlat
                                                                                    </a>
                                                                                    <button class="btn btn-warning btn-sm btn-edit me-2" data-bs-toggle="modal" data-bs-target="#updateMeetingModal"
                                                                                        data-id="<?= htmlspecialchars($row['id']) ?>"
                                                                                        data-title="<?= htmlspecialchars($row['description']) ?>"
                                                                                        data-date="<?= date('Y-m-d\TH:i', strtotime($row['meeting_date'])) ?>"
                                                                                        data-class-id="<?= htmlspecialchars($row['class_id']) ?>">
                                                                                        <i class="fas fa-edit"></i> Düzenle
                                                                                    </button>
                                                                                    <button class="btn btn-danger btn-sm btn-delete" data-id="<?= htmlspecialchars($row['id']) ?>">
                                                                                        <i class="fas fa-trash-alt"></i> Sil
                                                                                    </button>
                                                                                <?php else: ?>
                                                                                    <a href="<?= htmlspecialchars($row['zoom_join_url']) ?>" target="_blank" class="btn btn-primary btn-sm">
                                                                                        <i class="fas fa-sign-in-alt"></i> Canlı Derse Katıl
                                                                                    </a>
                                                                                <?php endif; ?>
                                                                            </td>
                                                                        </tr>
                                                                    <?php endforeach; ?>
                                                                <?php else: ?>
                                                                    <tr>
                                                                        <td colspan="4" class="text-center">Henüz kayıt yok</td>
                                                                    </tr>
                                                                <?php endif; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <div class="modal fade" id="meetingModal" tabindex="-1" aria-labelledby="meetingModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <form id="meetingForm">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="meetingModalLabel">Yeni Canlı Ders Başlat</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="mb-3">
                                                                            <label for="meetingTitle" class="form-label">Başlık</label>
                                                                            <input type="text" class="form-control" id="meetingTitle" name="title" required>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label for="meetingDate" class="form-label">Tarih & Saat</label>
                                                                            <input type="datetime-local" class="form-control" id="meetingDate" name="date_time" required>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label for="meetingClass" class="form-label">Sınıf</label>
                                                                            <select class="form-select" id="meetingClass" name="class_id" required>
                                                                                <option value="">Seçiniz</option>
                                                                                <?php
                                                                                foreach ($classList as $class) {
                                                                                    echo '<option value="' . htmlspecialchars($class['id']) . '">' . htmlspecialchars($class['name']) . '</option>';
                                                                                }
                                                                                ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">İptal</button>
                                                                        <button type="submit" class="btn btn-success">Kaydet</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>

                                                    <div class="modal fade" id="updateMeetingModal" tabindex="-1" aria-labelledby="updateMeetingModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <form id="updateMeetingForm">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="updateMeetingModalLabel"> Düzenle</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <input type="hidden" id="updateMeetingId" name="meeting_id">
                                                                        <div class="mb-3">
                                                                            <label for="updateMeetingTitle" class="form-label">Başlık</label>
                                                                            <input type="text" class="form-control" id="updateMeetingTitle" name="title" required>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label for="updateMeetingDate" class="form-label">Tarih & Saat</label>
                                                                            <input type="datetime-local" class="form-control" id="updateMeetingDate" name="date_time" required>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label for="updateMeetingClass" class="form-label">Sınıf</label>
                                                                            <select class="form-select" id="updateMeetingClass" name="class_id" required>
                                                                                <option value="">Seçiniz</option>
                                                                                <?php
                                                                                foreach ($classList as $class) {
                                                                                    echo '<option value="' . htmlspecialchars($class['id']) . '">' . htmlspecialchars($class['name']) . '</option>';
                                                                                }
                                                                                ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">İptal</button>
                                                                        <button type="submit" class="btn btn-success">Güncelle</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
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
        <script src="assets/js/custom/apps/subtopics/add.js"></script>
        <script src="assets/js/custom/apps/subtopics/create.js"></script>
        <script src="assets/js/widgets.bundle.js"></script>

        <script>
            $(document).ready(function() {
                var table = $('#meetingTable').DataTable({
                    language: {
                        decimal: ",",
                        thousands: ".",
                        emptyTable: "Tabloda herhangi bir veri mevcut değil",
                        info: "_TOTAL_ kayıttan _START_ - _END_ arası gösteriliyor",
                        infoEmpty: "Kayıt yok",
                        infoFiltered: "(_MAX_ kayıt içerisinden filtrelendi)",
                        lengthMenu: "Sayfada _MENU_ kayıt göster",
                        loadingRecords: "Yükleniyor...",
                        processing: "İşleniyor...",
                        search: "Ara:",
                        zeroRecords: "Eşleşen kayıt bulunamadı",
                        paginate: {
                            first: "İlk",
                            last: "Son",
                            next: "Sonraki",
                            previous: "Önceki"
                        }
                    },
                    searching: true // arama aktif
                });

                // Form submit (Create)
                $('#meetingForm').on('submit', function(e) {
                    e.preventDefault();
                    $.ajax({
                        url: 'includes/ajax.php?service=canli-video',
                        method: 'POST',
                        dataType: 'json',
                        data: $(this).serialize(),
                        success: function(res) {
                            if (res.status === "success") {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Başarılı',
                                    text: res.message,
                                    confirmButtonText: 'Tamam'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Hata',
                                    text: res.message || "Bir hata oluştu."
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Bağlantı Hatası',
                                text: "Sunucuya bağlanırken hata oluştu."
                            });
                        }
                    });
                });

                // Edit button click - Fill the update modal
                $('#meetingTable').on('click', '.btn-edit', function() {
                    const id = $(this).data('id');
                    const title = $(this).data('title');
                    const date = $(this).data('date');
                    const classId = $(this).data('class-id');

                    $('#updateMeetingId').val(id);
                    $('#updateMeetingTitle').val(title);
                    $('#updateMeetingDate').val(date);
                    $('#updateMeetingClass').val(classId);
                });

                // Form submit (Update)
                $('#updateMeetingForm').on('submit', function(e) {
                    e.preventDefault();
                    $.ajax({
                        url: 'includes/ajax.php?service=canli-video-update', // Update endpoint
                        method: 'POST',
                        dataType: 'json',
                        data: $(this).serialize(),
                        success: function(res) {
                            if (res.status === "success") {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Başarılı',
                                    text: res.message,
                                    confirmButtonText: 'Tamam'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Hata',
                                    text: res.message || "Bir hata oluştu."
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Bağlantı Hatası',
                                text: "Sunucuya bağlanırken hata oluştu."
                            });
                        }
                    });
                });

                // Delete button click
                $('#meetingTable').on('click', '.btn-delete', function() {
                    const id = $(this).data('id');

                    Swal.fire({
                        title: 'Emin misiniz?',
                        text: "Bu canlı ders kalıcı olarak silinecektir!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Evet, sil!',
                        cancelButtonText: 'İptal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: 'includes/ajax.php?service=canli-video-delete', // Delete endpoint
                                method: 'POST',
                                dataType: 'json',
                                data: {
                                    meeting_id: id
                                },
                                success: function(res) {
                                    if (res.status === "success") {
                                        Swal.fire(
                                            'Silindi!',
                                            res.message,
                                            'success'
                                        ).then(() => {
                                            location.reload();
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Hata',
                                            text: res.message || "Bir hata oluştu."
                                        });
                                    }
                                },
                                error: function() {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Bağlantı Hatası',
                                        text: "Sunucuya bağlanırken hata oluştu."
                                    });
                                }
                            });
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
?>