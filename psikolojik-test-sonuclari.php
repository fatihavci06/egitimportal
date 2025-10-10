<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 20001)) {
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";
    require_once "classes/student.classes.php";
    $pskTestList = new Classes();
    $studentInfo = new Student();
    // Veritabanından gelen listeyi simüle eden değişken (gerçek kodda veritabanından doluyor)
    // Örnek veri:
    $pskTestListSonuc = $pskTestList->getPskTestListSonuc();
    // Gerçek kodunuzda üstteki örnek veriyi kaldırıp $pskTestList->getPskTestListSonuc();
    // fonksiyonundan dönen gerçek veriyi kullandığınızdan emin olun.
    //$pskTestListSonuc = $pskTestList->getPskTestListSonuc();


    include_once "views/pages-head.php";
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
                        <div class="d-flex flex-column flex-column-fluid">
                            <?php include_once "views/toolbar.php"; ?>

                            <div id="kt_app_content" class="app-content flex-column-fluid">
                                <div id="kt_app_content_container" class="app-container container-fluid">
                                    <div class="card-body pt-5 ">
                                        <div class="row container-fluid" style="margin-top:-25px;">
                                            <header class="container-fluid bg-custom-light py-3 d-flex justify-content-between align-items-center
border-top border-bottom border-custom-red mb-2" style="border-width: 5px !important; height:85px;margin-bottom: 26px !important;">

                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-danger me-3 shadow icon-circle-lg d-flex justify-content-center align-items-center"
                                                        style="width: 65px; height: 65px;">
                                                        <i class="fas fa-bullseye fa-2x text-white"></i>
                                                    </div>

                                                    <h1 class="fs-3 fw-bold text-dark mb-0">Psikolojik Test Sonuçları</h1>
                                                </div>
                                            </header>
                                        </div>

                                        <div class="card shadow-sm mt-5">
                                            <div class="card-header border-0 pt-6">
                                                <h3 class="card-title fw-bold">Test Yanıtları Listesi</h3>

                                                <div class="card-toolbar">
                                                    <div class="d-flex justify-content-end align-items-center">
                                                        <div class="me-3">
                                                            <input type="text" id="studentNameFilter" class="form-control form-control-sm form-control-solid" placeholder="Öğrenci Adı Ara" style="width: 150px;" />
                                                        </div>

                                                        <div class="me-3">
                                                            <select class="form-select form-select-sm form-select-solid" data-control="select2" data-placeholder="Durum Filtresi" data-dropdown-parent="#kt_app_content_container" id="statusFilter" style="width: 150px;">
                                                                <option value="">Tümü</option>
                                                                <option value="Tamamlandı">Tamamlandı</option>
                                                                <option value="Devam Ediyor">Devam Ediyor</option>
                                                            </select>
                                                        </div>

                                                        <button type="button" id="applyFilterButton" class="btn btn-sm btn-primary">
                                                            <i class="fas fa-filter me-1"></i> Filtrele
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body py-4">
                                                <div class="table-responsive">
                                                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="pskTestTablosu">
                                                        <thead>
                                                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                                                <th class="min-w-50px">ID</th>
                                                                <th class="min-w-100px">Test Adı</th>
                                                                <th class="min-w-100px">Öğrenci</th>

                                                                <th class="min-w-250px">Dosya Yolu</th>
                                                                <th class="min-w-100px">Durum</th>
                                                                <th class="min-w-150px">Açıklama</th>
                                                                <th class="min-w-150px">Oluşturulma Tarihi</th>
                                                                <th class="min-w-100px">Eylemler</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="text-gray-600 fw-semibold">
                                                            <?php if (!empty($pskTestListSonuc)) : ?>
                                                                <?php foreach ($pskTestListSonuc as $testSonuc) : ?>
                                                                    <tr>
                                                                        <td><?php echo $testSonuc['id']; ?></td>
                                                                        <td><?php echo $testSonuc['test_name']; ?></td>
                                                                        <td><?php echo $testSonuc['full_name']; ?></td>
                                                                        <td>
                                                                            <a href="<?php echo $testSonuc['file_path']; ?>" target="_blank" class="text-primary fw-bold">
                                                                                <?php echo basename($testSonuc['file_path']); ?>
                                                                            </a>
                                                                        </td>
                                                                        <td data-order="<?php echo $testSonuc['status'] ?? 0; ?>">
                                                                            <?php if (isset($testSonuc['status']) && $testSonuc['status'] == 1) : ?>
                                                                                <span class="badge badge-light-success fw-bold">Tamamlandı</span>
                                                                            <?php else : ?>
                                                                                <span class="badge badge-light-warning fw-bold">Devam Ediyor</span>
                                                                            <?php endif; ?>
                                                                        </td>
                                                                        <td title="<?php echo htmlspecialchars($testSonuc['description'] ?? 'Açıklama yok'); ?>">
                                                                            <?php
                                                                            // İlk 30 karakteri göster
                                                                            $desc = $testSonuc['description'] ?? 'Açıklama yok';
                                                                            echo mb_substr($desc, 0, 30, 'UTF-8') . (mb_strlen($desc, 'UTF-8') > 30 ? '...' : '');
                                                                            ?>
                                                                        </td>
                                                                        <td><?php echo $testSonuc['created_at']; ?></td>
                                                                        <td>
                                                                            <a href="toplanti-olustur.php?user_id=<?php echo $testSonuc['user_id']; ?>"
                                                                                class="btn btn-sm btn-icon btn-light-info"
                                                                                title="Toplantı Başlat"
                                                                                target="_blank"> <i class="fas fa-video"></i> </a>

                                                                            <a href="sohbetler.php?user_id=<?php echo $testSonuc['user_id']; ?>"
                                                                                class="btn btn-sm btn-icon btn-light-success"
                                                                                title="Sohbet Başlat">
                                                                                <i class="fas fa-comment"></i> </a>
                                                                            <a href="<?php echo $testSonuc['file_path']; ?>" target="_blank" class="btn btn-sm btn-icon btn-light-primary" title="Görüntüle/İndir">
                                                                                <i class="fas fa-eye"></i>
                                                                            </a>
                                                                            <button type="button" class="btn btn-sm btn-icon btn-light-warning update-status-btn"
                                                                                data-bs-toggle="modal" data-bs-target="#statusUpdateModal"
                                                                                data-id="<?php echo $testSonuc['id']; ?>"
                                                                                data-status="<?php echo $testSonuc['status'] ?? 0; ?>"
                                                                                data-description="<?php echo htmlspecialchars($testSonuc['description'] ?? ''); ?>"
                                                                                title="Durum/Açıklama Güncelle">
                                                                                <i class="fas fa-edit"></i>
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            <?php else : ?>
                                                                <tr>
                                                                    <td colspan="9" class="text-center">Henüz sonuç bulunmamaktadır.</td>
                                                                </tr>
                                                            <?php endif; ?>
                                                        </tbody>
                                                    </table>
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

        <div class="modal fade" id="statusUpdateModal" tabindex="-1" aria-labelledby="statusUpdateModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="statusUpdateModalLabel">Durum ve Açıklama Güncelle</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                    </div>
                    <form id="statusUpdateForm">
                        <input type="hidden" name="id" id="testResultId">
                        <div class="modal-body">
                            <div class="mb-5">
                                <label for="statusSelect" class="form-label">Durum</label>
                                <select class="form-select form-select-solid" id="statusSelect" name="status" data-control="select2" data-hide-search="true">
                                    <option value="0">Devam Ediyor</option>
                                    <option value="1">Tamamlandı</option>
                                </select>
                            </div>
                            <div class="mb-5">
                                <label for="descriptionTextarea" class="form-label">Açıklama Girin</label>
                                <textarea class="form-control" id="descriptionTextarea" name="description" rows="3" placeholder="Gerekli açıklamayı buraya girin..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Kapat</button>
                            <button type="submit" class="btn btn-primary" data-kt-indicator="off">
                                <span class="indicator-label">Kaydet</span>
                                <span class="indicator-progress">Lütfen bekleyiniz... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>
                    </form>
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
        <script src="assets/js/widgets.bundle.js"></script>
        <script src="assets/js/custom/widgets.js"></script>
        <script src="assets/js/custom/apps/chat/chat.js"></script>
        <script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
        <script src="assets/js/custom/utilities/modals/create-account.js"></script>
        <script src="assets/js/custom/utilities/modals/create-app.js"></script>
        <script src="assets/js/custom/utilities/modals/users-search.js"></script>

        <script>
            var table; // DataTable objesini tutacak global değişken

            $(document).ready(function() {
                // Tablo ID'sini kullanarak DataTable'ı başlat
                table = $('#pskTestTablosu').DataTable({

                    "order": [
                        [6, "desc"] // NOT: Yeni kolon eklenmediği için Oluşturulma Tarihi sütun indeksi 6 oldu (Eski kodda 7 idi, Öğrenci Adı/Soyadı 2. sütun)
                    ],
                    "columnDefs": [
                        // ID=0, Test Adı=1, Öğrenci=2, Dosya Yolu=3, Durum=4, Açıklama=5, Oluşturma Tarihi=6, Eylemler=7
                        {
                            "orderable": false,
                            "targets": [7]
                        }, // 'Eylemler' sütununu sıralama dışı bırak
                        {
                            "searchable": false,
                            "targets": [7]
                        }, // 'Eylemler' sütununu arama dışı bırak
                        {
                            "searchable": false,
                            "targets": [5]
                        } // 'Açıklama' sütununu tam metin aramadan hariç tut
                    ]
                });

                // --- FİLTRE BUTONU İŞLEVİ ---
                $('#applyFilterButton').on('click', function() {
                    var studentName = $('#studentNameFilter').val().trim(); // Öğrenci Adı Girişi
                    var status = $('#statusFilter').val(); // Durum Seçimi (Tamamlandı/Devam Ediyor/Tümü)

                    // 1. Öğrenci Adı Filtresi (2. sütun: Öğrenci)
                    // Öğrenci adında arama yap, regex kapalı, akıllı arama açık
                    table.column(2).search(studentName, false, true);

                    // 2. Durum Filtresi (4. sütun: Durum)
                    // Durum değerinde arama yap, regex açık, akıllı arama kapalı (Tam eşleşme için boş veya Tamamlandı/Devam Ediyor)
                    // Durum sütunundaki HTML etiketlerini filtreleyebilmek için DataTables'ın arama yöntemine güveniyoruz.
                    table.column(4).search(status, true, false);

                    // Tüm filtreleri uygula ve tabloyu yeniden çiz
                    table.draw();
                });


                // --- Modal Açıldığında Verileri Yükleme İşlevi ---
                $('#statusUpdateModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget); // Tetikleyici buton
                    // HTML data-* özelliklerinden verileri çekme
                    var id = button.data('id');
                    var currentStatus = button.data('status'); // 1 veya 0
                    var currentDescription = button.data('description');

                    var modal = $(this);
                    modal.find('#testResultId').val(id);
                    modal.find('#statusSelect').val(currentStatus).trigger('change'); // Select2 güncellenmesi için change tetiklenir
                    modal.find('#descriptionTextarea').val(currentDescription);
                });

                // --- AJAX Form Gönderimi İşlevi - Swal Entegrasyonu ---
                $('#statusUpdateForm').on('submit', function(e) {
                    e.preventDefault();

                    var formData = $(this).serialize(); // Form verilerini al
                    var form = $(this);
                    var submitBtn = form.find('button[type="submit"]');

                    submitBtn.attr('data-kt-indicator', 'on'); // Yükleniyor animasyonu başlat

                    $.ajax({
                        // Buradaki URL'yi backend işlem dosyanızın doğru yolu ile değiştirin!
                        url: './includes/ajax.php?service=update_test_status',
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        success: function(response) {
                            submitBtn.attr('data-kt-indicator', 'off');
                            console.log(response)
                            if (response.status === 'success') {
                                // SweetAlert2 Başarılı Bildirimi

                                $('#statusUpdateModal').modal('hide');
                                Swal.fire({
                                    text: response.message || "İşlem başarıyla tamamlandı!",
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Tamam",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                }).then(function(result) {
                                    // 2 saniye sonra sayfayı yenile
                                    location.reload();
                                });
                            } else {
                                // SweetAlert2 Hata Bildirimi
                                Swal.fire({
                                    text: response.message || "İşlem sırasında bir hata oluştu!",
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Tamam",
                                    customClass: {
                                        confirmButton: "btn btn-danger"
                                    }
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            submitBtn.attr('data-kt-indicator', 'off');
                            // Hata durumunda uygun bir mesaj göster
                            var errorMessage = xhr.responseJSON ? xhr.responseJSON.message : "Bilinmeyen bir sunucu hatası oluştu.";

                            // SweetAlert2 AJAX Hata Bildirimi
                            Swal.fire({
                                text: "Hata: " + errorMessage,
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Tamam",
                                customClass: {
                                    confirmButton: "btn btn-danger"
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