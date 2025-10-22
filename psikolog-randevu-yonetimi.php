<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);

// Sadece Psikolog (role=1) bu sayfaya erişebilir.
$allowedRoles = [1, 20001];

if (isset($_SESSION['role']) && in_array($_SESSION['role'], $allowedRoles)) {
    // Gerekli sınıfları dahil et
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";

    $psikologId = $_SESSION['id'];

    // Gerekli kütüphaneleri dahil ettiğinizden emin olun (FA, DataTables JS/CSS)
    include_once "views/pages-head.php";
?>

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
                            <div id="kt_app_content" class="app-content flex-column-fluid">
                                <div id="kt_app_content_container" class="app-container container-fluid">

                                    <div class="card shadow-sm">
                                        <div class="card-header border-0 pt-6">
                                            <div class="card-title">
                                                <h2>Gelen Randevu Talepleri</h2>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class="table-responsive">
                                                <table class="table align-middle table-row-dashed fs-6 gy-5" id="randevu_listesi_table">
                                                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="randevu_listesi_table">
    <thead>
        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
            <th class="min-w-125px" data-order="desc">Talep ID</th>
            <th class="min-w-125px">Ad Soyad</th>
            <th class="min-w-125px">Telefon Numarası</th> 
            <th class="min-w-125px">Tarih</th>
            <th class="min-w-125px">Saat</th>
            <th class="min-w-125px">Durum</th>
            <th class="min-w-150px">İşlemler</th>
        </tr>
    </thead>
    <tbody class="fw-semibold text-gray-600">
    </tbody>
</table>
                                                    <tbody class="fw-semibold text-gray-600">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="guncelleme_modal" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered mw-650px">
                                            <div class="modal-content">
                                                <form class="form" id="psikolog_guncelleme_form">
                                                    <div class="modal-header">
                                                        <h2 class="fw-bold">Randevu Saatini Değiştirme</h2>
                                                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                                                            <i class="fas fa-times fs-1"></i>
                                                        </div>
                                                    </div>
                                                    <div class="modal-body py-10 px-lg-17">
                                                        <input type="hidden" name="appointment_id" id="modal_appointment_id">
                                                        <p>Ad soyad: <span id="modal_client_name" class="fw-bold"></span></p>

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
                                                        <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">İptal</button>
                                                        <button type="submit" id="modal_kaydet_btn" class="btn btn-primary" disabled>
                                                            <span class="indicator-label">Saati Güncelle ve Onayla</span>
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
        <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
            <i class="fas fa-arrow-up"></i>
        </div>
        <script>
            var hostUrl = "assets/";
        </script>
        <script src="assets/plugins/global/plugins.bundle.js"></script>
        <script src="assets/js/scripts.bundle.js"></script>
        <script src="assets/js/widgets.bundle.js"></script>
        <script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
        <script>
            $(document).ready(function() {
                const modal = $('#guncelleme_modal');
                const modalDateInput = $('#modal_appointment_date');
                const modalSaatContainer = $('#modal_saat_listesi_container');
                const modalKaydetBtn = $('#modal_kaydet_btn');
                const psikologId = <?php echo $psikologId; ?>;

                let datatable = null;

                // --------------------------------------------------------
                // A) KENDİ RANDEVU TALEPLERİNİ ÇEKME ve DATATABLES BAŞLATMA
                // --------------------------------------------------------
                function loadAppointments() {
                    if (datatable) {
                        datatable.destroy();
                        datatable = null;
                        $('#randevu_listesi_table tbody').empty();
                    }

                    datatable = $('#randevu_listesi_table').DataTable({
                        processing: true,
                        serverSide: false,
                        searching: true,
                        ordering: true,
                        lengthMenu: [10, 25, 50, 100],
                        pageLength: 10,

                        // YENİ EKLENEN SATIR: DataTables DOM yapısını tanımlar.
                        // 'l': length changing input, 'f': filtering input (search), 't': table, 'i': info, 'p': pagination
                        dom: '<"d-flex justify-content-start gap-3"lf>rt<"bottom"ip>',

                        // DİL AYARLARI: Search etiketini kaldır ve Placeholder ekle
                        language: {
                            search: "", // Etiketi tamamen gizle
                            searchPlaceholder: "Randevularda Ara...", // Placeholder metnini ayarla
                            // Türkçe diğer çevirileri de ekleyebilirsiniz (örneğin info, lengthMenu, vb.)
                        },

                        ajax: {
                            url: 'includes/ajax.php?service=getAppointmentsByPsikolog',
                            type: 'POST',
                            data: {
                                psikolog_id: psikologId
                            },
                            dataSrc: function(json) {
                                if (json.status === 'success') {
                                    return json.appointments;
                                }
                                return [];
                            },
                            error: function(xhr, error, thrown) {
                                console.error("DataTables AJAX Hatası:", thrown);
                                Swal.fire({
                                    text: "Randevuları çekerken bir hata oluştu.",
                                    icon: "error"
                                });
                                return [];
                            }
                        },

                        columns: [{
                                data: 'id',
                                name: 'id'
                            },
                            {
                                data: 'client_name',
                                name: 'client_name'
                            },
                            {
                                data: 'client_phone',
                                name: 'client_phone'
                            },
                            {
                                data: 'appointment_date',
                                name: 'appointment_date'
                            },
                            {
                                data: null,
                                render: function(data, type, row) {
                                    return row.start_time.substring(0, 5) + ' - ' + row.end_time.substring(0, 5);
                                },
                                name: 'saat'
                            },
                            {
                                data: 'status',
                                render: function(data, type, row) {
                                    let statusBadge;
                                    if (data === 'Beklemede') {
                                        statusBadge = '<span class="badge badge-light-warning">Beklemede</span>';
                                    } else if (data === 'Onaylandı') {
                                        statusBadge = '<span class="badge badge-light-success">Onaylandı</span>';
                                    } else if (data === 'İptal') {
                                        statusBadge = '<span class="badge badge-light-danger">İptal Edildi</span>';
                                    } else if (data === 'Reddedildi') {
                                        statusBadge = '<span class="badge badge-light-danger">Reddedildi</span>';
                                    } else {
                                        statusBadge = '<span class="badge badge-light-info">Tamamlandı</span>';
                                    }
                                    return statusBadge;
                                },
                                name: 'status'
                            },
                            {
                                data: null,
                                orderable: false,
                                render: function(data, type, row) {
                                    let actionsHtml = '';

                                    // İKONLAR FAS FA OLARAK DEĞİŞTİRİLDİ
                                    actionsHtml = `
                                            <button type="button" class="btn btn-sm btn-icon btn-light-success action-appointment" data-action="onayla" data-id="${row.id}" title="Onayla"><i class="fas fa-check fs-2"></i></button>
                                            <button type="button" class="btn btn-sm btn-icon btn-light-danger action-appointment" data-action="reddet" data-id="${row.id}" title="Reddet"><i class="fas fa-times fs-2"></i></button>
                                            <button type="button" class="btn btn-sm btn-icon btn-light-primary action-appointment" data-action="guncelle" data-id="${row.id}" data-client-name="${row.client_name}" data-date="${row.appointment_date}" data-bs-toggle="modal" data-bs-target="#guncelleme_modal" title="Saati Değiştir"><i class="fas fa-pen fs-2"></i></button>
                                        `;

                                    return actionsHtml;
                                },
                                name: 'actions'
                            }
                        ],

                        order: [
                            [0, 'desc']
                        ],

                        initComplete: function() {
                            $('#randevu_listesi_table tbody').off('click', '.action-appointment').on('click', '.action-appointment', function() {
                                const action = $(this).data('action');
                                const id = $(this).data('id');
                                if (action !== 'guncelle') {
                                    handleStatusChange(id, action);
                                }
                            });
                        }
                    });
                }

                loadAppointments();

                window.refreshAppointments = function() {
                    if (datatable) {
                        datatable.ajax.reload(null, false);
                    } else {
                        loadAppointments();
                    }
                }

                // --------------------------------------------------------
                // B) RANDEVU DURUMUNU DEĞİŞTİRME (Onay/Red)
                // --------------------------------------------------------
                // (Bu kısım aynı kalır)
                function handleStatusChange(appointmentId, action) {
                    const statusText = action === 'onayla' ? 'Onayla' : 'Reddet';
                    const newStatus = action === 'onayla' ? 'Onaylandı' : 'Reddedildi';

                    Swal.fire({
                        text: `Bu randevu talebini "${statusText}" olarak işaretlemek istediğinizden emin misiniz?`,
                        icon: "question",
                        showCancelButton: true,
                        buttonsStyling: false,
                        confirmButtonText: `Evet, ${statusText}!`,
                        cancelButtonText: "Hayır",
                        customClass: {
                            confirmButton: action === 'onayla' ? "btn btn-success" : "btn btn-danger",
                            cancelButton: "btn btn-light"
                        }
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: 'includes/ajax.php?service=updateAppointmentStatus',
                                type: 'POST',
                                data: {
                                    appointment_id: appointmentId,
                                    new_status: newStatus
                                },
                                dataType: 'json',
                                success: function(response) {
                                    if (response.status === 'success') {

                                        // YENİ KONTROL: Eğer onaylama başarılıysa, toplantı oluşturma sayfasına yönlendir
                                        if (newStatus === 'Onaylandı') {
                                            // Randevuyu yeniden çekip client_user_id ve tarihi almamız gerekiyor.
                                            // Ancak hızlı çözüm için, AJAX servisine yönlendirme URL'sini döndürmesini isteyelim.

                                            // *******************************************************************
                                            // DİKKAT: En iyi yol, AJAX'ın client_user_id ve appointment_date'i döndürmesidir.
                                            // Şimdilik, sadece randevu ID'si ile yönlendirme yapalım.
                                            // AJAX (updateAppointmentStatus) servisini DİKKAT 2'ye göre güncelleyiniz.
                                            // *******************************************************************
                                            console.log(response);
                                            if (response.client_user_id && response.appointment_date) {
                                                Swal.fire({
                                                    text: response.message || "Randevu onaylandı. Toplantı oluşturma sayfasına yönlendiriliyorsunuz.",
                                                    icon: "success",
                                                    confirmButtonText: "Tamam"
                                                }).then(() => {
                                                    const redirectUrl = `toplanti-olustur.php?appointment_id=${appointmentId}&client_id=${response.client_user_id}&date=${response.appointment_date}`;
                                                    window.location.href = redirectUrl;
                                                });
                                            } else {
                                                Swal.fire({
                                                    text: response.message || `Randevu onaylandı.`,
                                                    icon: "success",
                                                    buttonsStyling: false,
                                                    confirmButtonText: "Tamam"
                                                });
                                                window.refreshAppointments();
                                            }

                                        } else {
                                            // Reddetme işlemi
                                            Swal.fire({
                                                text: response.message || `Randevu durumu "${newStatus}" olarak güncellendi.`,
                                                icon: "success",
                                                confirmButtonText: "Tamam"
                                            });
                                            window.refreshAppointments();
                                        }
                                    } else {
                                        Swal.fire({
                                            text: response.message || "Durum değiştirme sırasında bir hata oluştu.",
                                            icon: "error",
                                            buttonsStyling: false,
                                            confirmButtonText: "Kapat"
                                        });
                                    }
                                },
                                // ... (error kısmı devam eder) ...
                            });
                        }
                    });
                }

                // --------------------------------------------------------
                // C) MODAL İÇİN SAATLERİ ÇEKME VE GÜNCELLEME İŞLEMİ (Aynı mantık)
                // --------------------------------------------------------
                const getAvailableHoursForModal = () => {
                    // ... (Modal saat çekme kodunuzun aynısı)
                    const date = modalDateInput.val();

                    if (!date) {
                        modalSaatContainer.html('<p class="text-muted">Lütfen bir tarih seçin.</p>');
                        modalKaydetBtn.prop('disabled', true);
                        return;
                    }

                    modalSaatContainer.html('<div class="text-center"><span class="spinner-border text-primary spinner-border-sm"></span> Saatler Yükleniyor...</div>');
                    modalKaydetBtn.prop('disabled', true);

                    $.ajax({
                        url: 'includes/ajax.php?service=getAvailableSlots',
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

                                $('.modal-slot-button').off('click').on('click', function() {
                                    $('.modal-slot-button').removeClass('active');
                                    $(this).addClass('active');

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

                modalDateInput.on('change', getAvailableHoursForModal);

                modal.on('show.bs.modal', function(event) {
                    const button = $(event.relatedTarget);
                    const appointmentId = button.data('id');
                    const clientName = button.data('client-name');
                    const currentAppointmentDate = button.data('date');

                    $('#modal_appointment_id').val(appointmentId);
                    $('#modal_client_name').text(clientName);
                    modalDateInput.val(currentAppointmentDate).prop('min', '<?php echo date('Y-m-d'); ?>');

                    $('#modal_start_time').val('');
                    $('#modal_end_time').val('');
                    modalKaydetBtn.prop('disabled', true);

                    getAvailableHoursForModal();
                });

                // --------------------------------------------------------
                // D) MODAL GÜNCELLEME İŞLEMİ (Psikolog Tarafından)
                // --------------------------------------------------------
                $('#psikolog_guncelleme_form').on('submit', function(e) {
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
                        url: 'includes/ajax.php?service=psikologUpdateAndApprove',
                        type: 'POST',
                        data: form.serialize(),
                        dataType: 'json',
                        success: function(response) {
                            btn.removeAttr("data-kt-indicator").prop('disabled', false);

                            if (response.status === 'success') {
                                Swal.fire({
                                    text: response.message || "Randevu saati güncellendi ve onaylandı!",
                                    icon: "success",
                                    confirmButtonText: "Tamam"
                                });
                                modal.modal('hide');
                                window.refreshAppointments();
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