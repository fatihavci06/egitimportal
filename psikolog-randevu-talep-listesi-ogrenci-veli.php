<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);

$allowedRoles = [10001, 10002, 10005];

if (isset($_SESSION['role']) && in_array($_SESSION['role'], $allowedRoles)) {
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";

    // NOT: Bu sayfada sadece listeleme yapılacağı için, psikolog listesi çekmeye gerek yok.
    // Veriler AJAX ile yüklenecektir.

    include_once "views/pages-head.php";
?>

    <body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" data-kt-app-aside-enabled="true" data-kt-app-aside-fixed="true" data-kt-app-aside-push-toolbar="true" data-kt-app-aside-push-footer="true" class="app-default">
        <script>
            var defaultThemeMode = "light";
            var themeMode;
            // ... (Tema ayarları kodunuz) ...
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

                                    <div class="card shadow-sm">
                                        <div class="card-header border-0 pt-6">
                                            <div class="card-title">
                                                <h2>Randevu Taleplerim</h2>
                                            </div>
                                            <div class="card-toolbar">
                                                <a href="psikolog-randevu-talep-formu.php" class="btn btn-primary">Yeni Randevu Talep Et</a>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class="table-responsive">
                                                <table class="table align-middle table-row-dashed fs-6 gy-5" id="randevu_listesi_table">
                                                    <thead>
                                                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                                            <th class="min-w-125px">Randevu ID</th>
                                                            <th class="min-w-125px">Psikolog</th>
                                                            <th class="min-w-125px">Tarih</th>
                                                            <th class="min-w-125px">Saat Aralığı</th>
                                                            <th class="min-w-125px">Durum</th>
                                                            <th class="min-w-125px">İşlemler</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="fw-semibold text-gray-600" id="randevu_listesi_body">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="guncelleme_modal" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered mw-650px">
                                            <div class="modal-content">
                                                <form class="form" id="guncelleme_form">
                                                    <div class="modal-header">
                                                        <h2 class="fw-bold">Randevu Güncelleme</h2>
                                                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                                                            <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                                                        </div>
                                                    </div>
                                                    <div class="modal-body py-10 px-lg-17">
                                                        <input type="hidden" name="appointment_id" id="modal_appointment_id">
                                                        <input type="hidden" name="psikolog_id" id="modal_psikolog_id">
                                                        <p>Psikolog: <span id="modal_psikolog_name" class="fw-bold"></span></p>

                                                        <div class="fv-row mb-7">
                                                            <label class="form-label required">Yeni Randevu Tarihi</label>
                                                            <input type="date" name="appointment_date" id="modal_appointment_date" class="form-control form-control-solid" required />
                                                        </div>

                                                        <div class="fv-row mb-7">
                                                            <label class="form-label required">Uygun Saat Seçimi</label>
                                                            <div id="modal_saat_listesi_container" class="border p-4 rounded min-h-100px">
                                                                <p class="text-muted">Lütfen bir tarih seçin.</p>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="start_time" id="modal_start_time">
                                                        <input type="hidden" name="end_time" id="modal_end_time">
                                                    </div>
                                                    <div class="modal-footer flex-center">
                                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">İptal</button>
                                                        <button type="submit" id="modal_kaydet_btn" class="btn btn-primary" disabled>
                                                            <span class="indicator-label">Güncelle</span>
                                                            <span class="indicator-progress">Lütfen Bekleyin... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                                        </button>
                                                    </div>
                                                </form>
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
        <script>
            var hostUrl = "assets/";
        </script>
        <script src="assets/plugins/global/plugins.bundle.js"></script>
        <script src="assets/js/scripts.bundle.js"></script>

        <script src="assets/js/widgets.bundle.js"></script>
        <script src="assets/js/custom/widgets.js"></script>

        <script>
            $(document).ready(function() {
                const modal = $('#guncelleme_modal');
                const modalDateInput = $('#modal_appointment_date');
                const modalSaatContainer = $('#modal_saat_listesi_container');
                const modalKaydetBtn = $('#modal_kaydet_btn');

                // --------------------------------------------------------
                // A) RANDEVU LİSTESİNİ ÇEKME
                // --------------------------------------------------------
                function loadAppointments() {
                    const tableBody = $('#randevu_listesi_body');
                    tableBody.html('<tr><td colspan="6" class="text-center">Randevular Yükleniyor...</td></tr>');

                    $.ajax({
                        url: 'includes/ajax.php?service=getAppointmentsByClient',
                        type: 'POST',
                        dataType: 'json',
                        success: function(response) {
                            tableBody.empty();
                            if (response.status === 'success' && response.appointments.length > 0) {
                                response.appointments.forEach(app => {
                                    let statusBadge;
                                    let actionsHtml = '';

                                    // Durum rozeti (badge)
                                    if (app.status === 'Beklemede') {
                                        statusBadge = '<span class="badge badge-light-warning">Beklemede</span>';
                                        // GÜNCELLEME VE İPTAL BUTONLARI EKLENDİ
                                        actionsHtml = `
                            <button type="button" class="btn btn-sm btn-icon btn-light-primary edit-appointment" 
                                data-id="${app.id}" data-psikolog-id="${app.psikolog_id}" data-psikolog-name="${app.psikolog_name}" data-date="${app.appointment_date}" 
                                data-bs-toggle="modal" data-bs-target="#guncelleme_modal" title="Randevuyu Güncelle">
                                <i class="fas fa-pen"></i>

                            </button>
                            <button type="button" class="btn btn-sm btn-icon btn-light-danger cancel-appointment" 
                                data-id="${app.id}" title="Randevu Talebini İptal Et">
                                <i class="fas fa-trash"></i>

                            </button>
                        `;
                                    } else if (app.status === 'Onaylandı') {
                                        statusBadge = '<span class="badge badge-light-success">Onaylandı</span>';
                                    } else if (app.status === 'İptal') {
                                        statusBadge = '<span class="badge badge-light-danger">İptal Edildi</span>';
                                    } else {
                                        statusBadge = '<span class="badge badge-light-info">Tamamlandı</span>';
                                    }

                                    tableBody.append(`
                        <tr>
                            <td>${app.id}</td>
                            <td>${app.psikolog_name}</td>
                            <td>${app.appointment_date}</td>
                            <td>${app.start_time.substring(0, 5)} - ${app.end_time.substring(0, 5)}</td>
                            <td>${statusBadge}</td>
                            <td>${actionsHtml}</td>
                        </tr>
                    `);
                                });
                            } else {
                                tableBody.html('<tr><td colspan="6" class="text-center">Henüz randevu talebiniz bulunmamaktadır.</td></tr>');
                            }

                            // Yeni eklenen iptal butonlarına olayı bağla
                            $('.cancel-appointment').off('click').on('click', function() {
                                handleCancelAppointment($(this).data('id'));
                            });
                        },
                        error: function() {
                            tableBody.html('<tr><td colspan="6" class="text-center text-danger">Randevuları çekerken bir hata oluştu.</td></tr>');
                        }
                    });
                }

                // --------------------------------------------------------
                // D) RANDEVU İPTAL İŞLEMİ (YENİ FONKSİYON)
                // --------------------------------------------------------
                function handleCancelAppointment(appointmentId) {
                    Swal.fire({
                        text: "Bu randevu talebini iptal etmek istediğinizden emin misiniz?",
                        icon: "warning",
                        showCancelButton: true,
                        buttonsStyling: false,
                        confirmButtonText: "Evet, İptal Et!",
                        cancelButtonText: "Hayır",
                        customClass: {
                            confirmButton: "btn btn-danger",
                            cancelButton: "btn btn-light"
                        }
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: 'includes/ajax.php?service=cancelAppointmentByClient', // Yeni servis
                                type: 'POST',
                                data: {
                                    appointment_id: appointmentId
                                },
                                dataType: 'json',
                                success: function(response) {
                                    if (response.status === 'success') {
                                        Swal.fire({
                                            text: response.message || "Randevu başarıyla iptal edildi.",
                                            icon: "success",
                                            buttonsStyling: false,
                                            confirmButtonText: "Tamam",
                                            customClass: {
                                                confirmButton: "btn btn-primary"
                                            }
                                        });
                                        loadAppointments(); // Listeyi yenile
                                    } else {
                                        Swal.fire({
                                            text: response.message || "İptal sırasında bir hata oluştu.",
                                            icon: "error",
                                            buttonsStyling: false,
                                            confirmButtonText: "Kapat",
                                            customClass: {
                                                confirmButton: "btn btn-danger"
                                            }
                                        });
                                    }
                                },
                                error: function() {
                                    Swal.fire({
                                        text: "İptal isteği sunucuya iletilemedi.",
                                        icon: "error",
                                        buttonsStyling: false,
                                        confirmButtonText: "Kapat",
                                        customClass: {
                                            confirmButton: "btn btn-danger"
                                        }
                                    });
                                }
                            });
                        }
                    });
                }

                loadAppointments(); // Sayfa yüklendiğinde listeyi çek

                // --------------------------------------------------------
                // B) MODAL İÇİN SAATLERİ ÇEKME
                // --------------------------------------------------------
                const getAvailableHoursForModal = () => {
                    const psikologId = $('#modal_psikolog_id').val();
                    const date = modalDateInput.val();

                    if (!psikologId || !date) {
                        modalSaatContainer.html('<p class="text-muted">Lütfen bir tarih seçin.</p>');
                        modalKaydetBtn.prop('disabled', true);
                        return;
                    }

                    modalSaatContainer.html('<div class="text-center"><span class="spinner-border text-primary spinner-border-sm"></span> Saatler Yükleniyor...</div>');
                    modalKaydetBtn.prop('disabled', true);

                    $.ajax({
                        url: 'includes/ajax.php?service=getAvailableSlots', // Randevu al sayfasındaki servisin aynısı
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            psikolog_id: psikologId,
                            appointment_date: date
                        },
                        success: function(response) {
                            if (response.status === 'success' && response.slots && response.slots.length > 0) {
                                let html = '<div class="d-flex flex-wrap gap-3">';
                                response.slots.forEach(slot => {
                                    html += `<button type="button" 
                                                 class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary modal-slot-button" 
                                                 data-start-time="${slot.start}" 
                                                 data-end-time="${slot.end}">
                                                 ${slot.display}
                                             </button>`;
                                });
                                html += '</div>';
                                modalSaatContainer.html(html);

                                // Saat düğmelerine tıklama olayını bağla
                                $('.modal-slot-button').on('click', function() {
                                    $('.modal-slot-button').removeClass('active');
                                    $(this).addClass('active');

                                    // Seçilen saatleri hidden input'lara ata
                                    $('#modal_start_time').val($(this).data('start-time'));
                                    $('#modal_end_time').val($(this).data('end-time'));

                                    modalKaydetBtn.prop('disabled', false);
                                });

                            } else {
                                modalSaatContainer.html('<div class="alert alert-danger mb-0 py-2">Bu tarihte uygun saat bulunmamaktadır.</div>');
                                modalKaydetBtn.prop('disabled', true);
                            }
                        },
                        error: function() {
                            modalSaatContainer.html('<div class="alert alert-danger mb-0 py-2">Saatler yüklenirken hata oluştu.</div>');
                            modalKaydetBtn.prop('disabled', true);
                        }
                    });
                };

                // Tarih değiştiğinde saatleri çek
                modalDateInput.on('change', getAvailableHoursForModal);

                // Modal açıldığında verileri set et
                modal.on('show.bs.modal', function(event) {
                    const button = $(event.relatedTarget);
                    const appointmentId = button.data('id');
                    const psikologId = button.data('psikolog-id');
                    const psikologName = button.data('psikolog-name');
                    const currentAppointmentDate = button.data('date');

                    // Form alanlarını doldur
                    $('#modal_appointment_id').val(appointmentId);
                    $('#modal_psikolog_id').val(psikologId);
                    $('#modal_psikolog_name').text(psikologName);
                    modalDateInput.val(currentAppointmentDate).prop('min', '<?php echo date('Y-m-d'); ?>');

                    // Saati ve butonu sıfırla
                    $('#modal_start_time').val('');
                    $('#modal_end_time').val('');
                    modalKaydetBtn.prop('disabled', true);

                    // Yeni tarih seçimi için saatleri yükle
                    getAvailableHoursForModal();
                });

                // --------------------------------------------------------
                // C) RANDEVU GÜNCELLEME İŞLEMİ
                // --------------------------------------------------------
                $('#guncelleme_form').on('submit', function(e) {
                    e.preventDefault();

                    if (!$('#modal_start_time').val()) {
                        Swal.fire({
                            text: "Lütfen bir randevu saati seçin.",
                            icon: "warning",
                            buttonsStyling: false,
                            confirmButtonText: "Tamam"
                        });
                        return;
                    }

                    const form = $(this);
                    const btn = $('#modal_kaydet_btn');

                    btn.attr("data-kt-indicator", "on").prop('disabled', true);

                    $.ajax({
                        url: 'includes/ajax.php?service=updatePendingAppointment', // Yeni servis
                        type: 'POST',
                        data: form.serialize(),
                        dataType: 'json',
                        success: function(response) {
                            btn.removeAttr("data-kt-indicator").prop('disabled', false);

                            if (response.status === 'success') {
                                Swal.fire({
                                    text: response.message || "Randevu başarıyla güncellendi!",
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Tamam"
                                });
                                modal.modal('hide'); // Modalı kapat
                                loadAppointments(); // Listeyi yenile
                            } else {
                                Swal.fire({
                                    text: response.message || "Güncelleme sırasında bir hata oluştu.",
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Tekrar Dene"
                                });
                            }
                        },
                        error: function() {
                            btn.removeAttr("data-kt-indicator").prop('disabled', false);
                            Swal.fire({
                                text: "Sunucu ile iletişimde bir sorun oluştu.",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Kapat"
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
    exit;
}
?>