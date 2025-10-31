<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) && ($_SESSION['role'] == 1 || $_SESSION['role'] == 10001)) {
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";

    $classes = new Classes();
    $classList = $classes->getMainSchoolClassesList();

    $classMap = [];
    foreach ($classList as $class) {
        $classMap[$class['id']] = htmlspecialchars($class['name']);
    }
    $classMapJson = json_encode($classMap, JSON_HEX_APOS | JSON_HEX_QUOT);

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

                                    <div class="row">
                                        <div class="card mb-5">
                                            <div class="card-header border-0 pt-6">
                                                <div class="card-title">
                                                    <h2>Şarkılar</h2>
                                                </div>
                                                <div class="card-toolbar">
                                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSongModal">
                                                        <i class="ki-duotone ki-plus fs-2"></i> Yeni Şarkı Ekle
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="card-body pt-0">
                                                <table id="sarkiTable" class="table table-striped align-middle table-row-dashed fs-6 gy-5">
                                                    <thead>
                                                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                                            <th>ID</th>
                                                            <th>Şarkı Adı</th>
                                                            <th>Sınıflar</th>
                                                            <th>YouTube Linki</th>
                                                            <th>Durum</th>
                                                            <th>İşlemler</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="addSongModal" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Yeni Şarkı Ekle</h5>
                                                        <button type="button" class="btn btn-sm btn-icon btn-active-light-primary" data-bs-dismiss="modal">
                                                            <i class="ki-duotone ki-cross fs-1"></i>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form id="addSongForm">
                                                            <div class="mb-3">
                                                                <label class="form-label">Şarkı Adı</label>
                                                                <input type="text" name="title" class="form-control" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">YouTube Linki</label>
                                                                <input type="url" name="youtube_url" class="form-control" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Sınıflar</label>
                                                                <select name="class_id[]" class="form-select" multiple required>
                                                                    <?php foreach ($classList as $class) : ?>
                                                                        <option value="<?= htmlspecialchars($class['id']); ?>">
                                                                            <?= htmlspecialchars($class['name']); ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <small class="text-muted">Birden fazla sınıf seçebilirsiniz.</small>
                                                            </div>
                                                            <div class="text-end">
                                                                <button type="submit" class="btn btn-primary">Kaydet</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="editSongModal" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Şarkı Güncelle</h5>
                                                        <button type="button" class="btn btn-sm btn-icon btn-active-light-primary" data-bs-dismiss="modal">
                                                            <i class="ki-duotone ki-cross fs-1"></i>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form id="editSongForm">
                                                            <input type="hidden" name="id">
                                                            <div class="mb-3">
                                                                <label class="form-label">Şarkı Adı</label>
                                                                <input type="text" name="title" class="form-control" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">YouTube Linki</label>
                                                                <input type="url" name="youtube_url" class="form-control" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Sınıflar</label>
                                                                <select name="class_id[]" class="form-select" multiple required>
                                                                    <?php foreach ($classList as $class) : ?>
                                                                        <option value="<?= htmlspecialchars($class['id']); ?>">
                                                                            <?= htmlspecialchars($class['name']); ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                            <div class="text-end">
                                                                <button type="submit" class="btn btn-primary">Güncelle</button>
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
                    </div>
                </div>
            </div>

            <script src="assets/plugins/global/plugins.bundle.js"></script>
            <script src="assets/js/scripts.bundle.js"></script>
            <script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
            <script>
                const CLASS_NAME_MAP = <?php echo $classMapJson; ?>;

                function getClassNames(classIdsString) {
                    if (!classIdsString) return '';
                    const ids = classIdsString.toString().split(';');
                    let names = [];

                    ids.forEach(id => {
                        const name = CLASS_NAME_MAP[id];
                        if (name) {
                            names.push(name);
                        }
                    });

                    return names.join(', ');
                }

                $(document).ready(function() {
                    const turkishLanguage = {
                        "sDecimal": ",",
                        "sEmptyTable": "Tabloda herhangi bir veri mevcut değil",
                        "sInfo": "_TOTAL_ kayıttan _START_ - _END_ arasındaki kayıtlar gösteriliyor",
                        "sInfoEmpty": "Kayıt yok",
                        "sInfoFiltered": "(_MAX_ kayıt içerisinden filtreleniyor)",
                        "sInfoPostFix": "",
                        "sInfoThousands": ".",
                        "sLengthMenu": "Sayfada _MENU_ kayıt göster",
                        "sLoadingRecords": "Yükleniyor...",
                        "sProcessing": "İşleniyor...",
                        "sSearch": "Ara:",
                        "sZeroRecords": "Eşleşen kayıt bulunamadı",
                        "oPaginate": {
                            "sFirst": "İlk",
                            "sLast": "Son",
                            "sNext": "Sonraki",
                            "sPrevious": "Önceki"
                        },
                        "oAria": {
                            "sSortAscending": ": artan sütun sıralamasını aktifleştir",
                            "sSortDescending": ": azalan sütun sıralamasını aktifleştir"
                        },
                        "select": {
                            "rows": {
                                "_": "%d kayıt seçildi",
                                "0": "",
                                "1": "1 kayıt seçildi"
                            }
                        }
                    };

                    var table = $('#sarkiTable').DataTable({
                        ajax: './includes/ajax.php?service=sarkiList',
                        columns: [{
                                data: 'id'
                            },
                            {
                                data: 'title'
                            },
                            {
                                data: 'class_id',
                                render: function(data, type, row) {
                                    if (type === 'display' || type === 'filter') {
                                        return getClassNames(data);
                                    }
                                    return data;
                                }
                            },
                            {
                                data: 'youtube_url',
                                render: function(data) {
                                    return `<a href="${data}" target="_blank">İzle</a>`;
                                }
                            },
                            {
                                data: 'status',
                                render: function(data, type, row) {
                                    if (data == 1) {
                                        return `<span class="badge bg-success">Aktif</span>`;
                                    } else {
                                        return `<span class="badge bg-danger">Pasif</span>`;
                                    }
                                }
                            },
                            {
                                data: null,
                                render: function(row) {
                                    let statusIcon = row.status == 1 ?
                                        `<a href="#" class="btn btn-icon btn-sm btn-light-danger toggleStatus" data-id="${row.id}" data-status="0" title="Pasif Yap"><i class="fas fa-toggle-off fs-4"></i></a>` :
                                        `<a href="#" class="btn btn-icon btn-sm btn-light-success toggleStatus" data-id="${row.id}" data-status="1" title="Aktif Yap"><i class="fas fa-toggle-on fs-4"></i></a>`;

                                    return `
                                        ${statusIcon}
                                        <a href="#" class="btn btn-icon btn-sm btn-light-primary editSong" data-id="${row.id}" data-bs-toggle="modal" data-bs-target="#editSongModal" title="Düzenle">
                                            <i class="fas fa-edit fs-4"></i>
                                        </a>
                                        <a href="#" class="btn btn-icon btn-sm btn-light-danger deleteSong" data-id="${row.id}" title="Sil">
                                            <i class="fas fa-trash-alt fs-4"></i>
                                        </a>
                                    `;
                                }
                            }
                        ],
                        language: turkishLanguage,
                        "dom": "<'row'<'col-sm-6 d-flex align-items-center justify-content-start'l><'col-sm-6 d-flex align-items-center justify-content-end'f>>" +
                            "<'row'<'col-sm-12'tr>>" +
                            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 d-flex justify-content-end'p>>",
                    });

                    // Yardımcı hata fonksiyonu
                    // ÖNCEKİ KODUNUZDAKİ HANDLEAJAXFAIL FONKSİYONUNU BU ŞEKİLDE DEĞİŞTİRİN
                    function handleAjaxFail(jqXHR) {
                        let errorMessage = 'İstek gönderilirken bir sorun oluştu.';
                        let isSessionExpired = false;

                        // Oturumun bittiğini veya yetki olmadığını gösteren durumları kontrol et
                        if (jqXHR.status !== 200 || jqXHR.responseText.toLowerCase().includes('location: index')) {
                            errorMessage = 'Oturum süreniz dolmuş veya yetkiniz yok. Lütfen sayfayı yenileyin.';
                            isSessionExpired = true; // Yönlendirme bayrağını işaretle
                        } else if (jqXHR.responseText) {
                            // PHP'den JSON yerine beklenmeyen çıktı gelmişse (genellikle bu olur)
                            errorMessage = 'Beklenmeyen sunucu yanıtı: ' + jqXHR.status;
                        }

                        // Swal uyarısını göster ve sonrasında yönlendirmeyi yap.
                        Swal.fire({
                            title: 'Hata!',
                            text: errorMessage,
                            icon: 'error',
                            confirmButtonText: 'Tamam'
                        }).then((result) => {
                            // Hata oturum ile ilgiliyse, kullanıcı 'Tamam' dediğinde yönlendirmeyi zorla.
                            if (isSessionExpired) {
                                window.location.href = 'index';
                            }
                        });
                    }


                    // Ekleme
                    $('#addSongForm').on('submit', function(e) {
                        e.preventDefault();
                        $.post('./includes/ajax.php?service=sarkiCreate', $(this).serialize(), function(res) {
                            Swal.fire({
                                title: res.message,
                                icon: res.status,
                                confirmButtonText: 'Tamam'
                            });
                            if (res.status === 'success') {
                                $('#addSongModal').modal('hide');
                                $('#addSongForm')[0].reset();
                                table.ajax.reload(null, false);
                            }
                        }, 'json').fail(handleAjaxFail);
                    });

                    // Güncelle modalını doldur
                    $(document).on('click', '.editSong', function() {
                        let id = $(this).data('id');
                        $.get('./includes/ajax.php?service=sarkiGet&id=' + id, function(res) {
                            if (res.status === 'success') {
                                let form = $('#editSongForm');
                                form.find('[name="id"]').val(res.data.id);
                                form.find('[name="title"]').val(res.data.title);
                                form.find('[name="youtube_url"]').val(res.data.youtube_url);
                                let selectedClasses = res.data.class_id.split(';');
                                form.find('[name="class_id[]"]').val(selectedClasses).trigger('change');
                            } else {
                                Swal.fire({
                                    title: 'Hata!',
                                    text: res.message,
                                    icon: 'error',
                                    confirmButtonText: 'Tamam'
                                });
                            }
                        }, 'json').fail(handleAjaxFail);
                    });

                    // Güncelleme
                    $('#editSongForm').on('submit', function(e) {
                        e.preventDefault();
                        $.post('./includes/ajax.php?service=sarkiUpdate', $(this).serialize(), function(res) {
                            Swal.fire({
                                title: res.message,
                                icon: res.status,
                                confirmButtonText: 'Tamam'
                            });
                            if (res.status === 'success') {
                                $('#editSongModal').modal('hide');
                                table.ajax.reload(null, false);
                            }
                        }, 'json').fail(handleAjaxFail);
                    });

                    // Silme (Dashboard'a atma sorununu çözen kısım)
                    $(document).on('click', '.deleteSong', function(e) {
                        e.preventDefault(); // <a href="#"> tıklanınca sayfa yenilenmesin

                        let id = $(this).data('id');

                        Swal.fire({
                            title: 'Emin misiniz?',
                            text: 'Bu şarkı silinecek!',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Evet, sil',
                            cancelButtonText: 'Vazgeç'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.post('./includes/ajax.php?service=sarkiDelete', {
                                    id: id
                                }, function(res) {
                                    Swal.fire({
                                        title: res.message,
                                        icon: res.status,
                                        confirmButtonText: 'Tamam'
                                    });

                                    if (res.status === 'success') {
                                        table.ajax.reload(null, false);
                                    }
                                }, 'json').fail(handleAjaxFail);
                            }
                        });
                    });



                    // Status değiştir
                    $(document).on('click', '.toggleStatus', function(e) {
                        e.preventDefault();
                        let id = $(this).data('id');
                        let newStatus = $(this).data('status');
                        $.post('./includes/ajax.php?service=sarkiStatus', {
                            id: id,
                            status: newStatus
                        }, function(res) {
                            Swal.fire({
                                title: res.message,
                                icon: res.status,
                                confirmButtonText: 'Tamam'
                            });
                            if (res.status === 'success') table.ajax.reload(null, false);
                        }, 'json').fail(handleAjaxFail);
                    });
                });
            </script>

    </body>

    </html>
<?php
} else {
    header("location: index");
}
?>