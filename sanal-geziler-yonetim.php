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
            // ... Tema modu script'i (değişmedi)
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
                                                    <h2>Sanal Geziler</h2>
                                                    
                                                </div>
                                                <div class="card-toolbar">
                                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTourModal">
                                                        <i class="ki-duotone ki-plus fs-2"></i> Yeni Sanal Gezi Ekle
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="card-body pt-0">
                                                <table id="toursTable" class="table table-striped align-middle table-row-dashed fs-6 gy-5">
                                                    <thead>
                                                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                                            <th>ID</th>
                                                            <th>Gezi Adı</th>
                                                            <th>Sınıflar</th>
                                                            <th>İkon (Görsel)</th> 
                                                            <th>Bağlantı (Link)</th>
                                                            <th>Durum</th>
                                                            <th>İşlemler</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="addTourModal" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Yeni Sanal Gezi Ekle</h5>
                                                        <button type="button" class="btn btn-sm btn-icon " data-bs-dismiss="modal">
                                                            <i class="fa-solid fa-x"></i>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form id="addTourForm"> 
                                                            <div class="mb-3">
                                                                <label class="form-label">Gezi Adı</label>
                                                                <input type="text" name="title" class="form-control" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Bağlantı (Link)</label>
                                                                <input type="url" name="link" class="form-control" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">İkon Görseli (JPG, PNG, SVG - Max 512 KB)</label>
                                                                <input type="file" name="icon_file" id="add_icon_file" class="form-control">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Sınıflar</label>
                                                                <select name="class_id[]" class="form-select" data-control="select2" data-dropdown-parent="#addTourModal" data-placeholder="Sınıf Seçiniz" multiple required>
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

                                        <div class="modal fade" id="editTourModal" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Sanal Gezi Güncelle</h5>
                                                        <button type="button" class="btn btn-sm btn-icon btn-active-light-primary" data-bs-dismiss="modal">
                                                            <i class="fa-solid fa-x"></i>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form id="editTourForm"> 
                                                            <input type="hidden" name="id">
                                                            <div class="mb-3">
                                                                <label class="form-label">Gezi Adı</label>
                                                                <input type="text" name="title" class="form-control" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Bağlantı (Link)</label>
                                                                <input type="url" name="link" class="form-control" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">İkon Görseli (JPG, PNG, SVG - Max 512 KB)</label>
                                                                <p id="current_icon_display" class="text-muted"></p>
                                                                <input type="file" name="icon_file" id="edit_icon_file" class="form-control">
                                                                <small class="text-muted">Yeni bir görsel seçerseniz, mevcut görsel değişecektir. Boş bırakırsanız mevcut görsel kalır.</small>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Sınıflar</label>
                                                                <select name="class_id[]" class="form-select" data-control="select2" data-dropdown-parent="#editTourModal" data-placeholder="Sınıf Seçiniz" multiple required>
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
        </div>

        <script src="assets/plugins/global/plugins.bundle.js"></script>
        <script src="assets/js/scripts.bundle.js"></script>
        <script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
        <script>
            const CLASS_NAME_MAP = <?php echo $classMapJson; ?>;
            const UPLOAD_PATH = './uploads/icons/'; // Görsellerin yüklendiği dizin (ajax.php'deki ../uploads/icons/ karşılığı)

            function getClassNames(classIdsString) {
                if (!classIdsString) return '';
                // Sonda boş bir eleman oluşmaması için split etmeden önce sondaki noktalı virgül silindi
                const cleanClassIdsString = classIdsString.endsWith(';') ? classIdsString.slice(0, -1) : classIdsString;
                const ids = cleanClassIdsString.split(';');
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
                // ... (Türkçe dil ayarları değişmedi)
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
                
                // Hata yönetim fonksiyonu (verdiğiniz örnekteki ile aynı)
                function handleAjaxFail(jqXHR) {
                    let errorMessage = 'İstek gönderilirken bir sorun oluştu.';
                    let isSessionExpired = false;

                    // try/catch kullanarak yanıtı JSON olarak ayrıştırmaya çalışın
                    try {
                        const res = JSON.parse(jqXHR.responseText);
                        if (res.status === 'error' && res.message) {
                            errorMessage = res.message;
                        }
                    } catch (e) {
                        // Eğer JSON değilse veya statüsü 401 (Yetkisiz) ise oturum hatası verilebilir
                        if (jqXHR.status === 401) {
                            errorMessage = 'Oturum süreniz dolmuş veya yetkiniz yok. Lütfen sayfayı yenileyin.';
                            isSessionExpired = true;
                        } else if (jqXHR.responseText) {
                            errorMessage = 'Beklenmeyen sunucu yanıtı: ' + jqXHR.status;
                        }
                    }
                    
                    Swal.fire({
                        title: 'Hata!',
                        text: errorMessage,
                        icon: 'error',
                        confirmButtonText: 'Tamam'
                    }).then((result) => {
                        if (isSessionExpired) {
                            window.location.href = 'index';
                        }
                    });
                }


                // ⭐ 1. Datatables Tanımlaması ⭐
                var table = $('#toursTable').DataTable({
                    ajax: './includes/ajax.php?service=sanalGezilerList',
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
                            data: 'icon',
                            render: function(data) {
                                // 💡 Görsel varsa göster
                                if (data) {
                                    // Sadece dosya adından tam URL oluştur
                                    return `<img src="${UPLOAD_PATH}${data}" alt="İkon" style="max-height: 40px; max-width: 40px; margin-right: 8px;">`;
                                }
                                return '-';
                            }
                        },
                        {
                            data: 'link',
                            render: function(data) {
                                // Link varsa 'Git' butonu göster
                                return data ? `<a href="${data}" target="_blank" class="btn btn-sm btn-light-info">Git</a>` : '-';
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
                                // Durum değiştirme butonu
                                let statusIcon = row.status == 1 ?
                                    `<a href="#" class="btn btn-icon btn-sm btn-light-danger toggleStatus" data-id="${row.id}" data-status="0" title="Pasif Yap"><i class="fas fa-toggle-off fs-4"></i></a>` :
                                    `<a href="#" class="btn btn-icon btn-sm btn-light-success toggleStatus" data-id="${row.id}" data-status="1" title="Aktif Yap"><i class="fas fa-toggle-on fs-4"></i></a>`;

                                return `
                                    ${statusIcon}
                                    <a href="#" class="btn btn-icon btn-sm btn-light-primary editTour" data-id="${row.id}" data-bs-toggle="modal" data-bs-target="#editTourModal" title="Düzenle">
                                        <i class="fas fa-edit fs-4"></i>
                                    </a>
                                    <a href="#" class="btn btn-icon btn-sm btn-light-danger deleteTour" data-id="${row.id}" title="Sil">
                                        <i class="fas fa-trash-alt fs-4"></i>
                                    </a>
                                `;
                            }
                        }
                    ],
                    language: turkishLanguage,
                    "order": [
                        [0, "desc"]
                    ], // Azalan sıralama (En yeni kayıt en üstte)
                     "dom": "<'row'<'col-sm-6 d-flex align-items-center justify-content-start'l><'col-sm-6 d-flex align-items-center justify-content-end'f>>" +
                            "<'row'<'col-sm-12'tr>>" +
                            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 d-flex justify-content-end'p>>",
                });

                // ⭐ 2. Harici Arama Kutusu Bağlantısı ⭐
                const searchInput = document.getElementById('datatable_search_input');
                if (searchInput) {
                    searchInput.addEventListener('keyup', function() {
                        table.search(this.value).draw();
                    });
                }


                // 3. Ekleme İşlemi (Görsel Desteği ile Güncellendi)
                $('#addTourForm').on('submit', function(e) {
                    e.preventDefault();
                    
                    // FormData kullanımı zorunlu: Dosya ve diğer form verilerini paketler
                    var formData = new FormData(this); 
                    
                    $.ajax({
                        url: './includes/ajax.php?service=sanalGezilerCreate',
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        processData: false, // FormData kullanılırken zorunlu
                        contentType: false, // FormData kullanılırken zorunlu
                        success: function(res) {
                            Swal.fire({
                                title: res.message,
                                icon: res.status,
                                confirmButtonText: 'Tamam'
                            });
                            if (res.status === 'success') {
                                $('#addTourModal').modal('hide');
                                $('#addTourForm')[0].reset();
                                // Select2 reset
                                $('#addTourForm').find('select[name="class_id[]"]').val(null).trigger('change');
                                table.ajax.reload(null, false);
                            }
                        },
                        error: handleAjaxFail
                    });
                });

                // 4. Güncelle Modalını Doldurma
                $(document).on('click', '.editTour', function() {
                    let id = $(this).data('id');
                    $.get('./includes/ajax.php?service=sanalGezilerGet&id=' + id, function(res) {
                        if (res.status === 'success') {
                            let form = $('#editTourForm');
                            form.find('[name="id"]').val(res.data.id);
                            form.find('[name="title"]').val(res.data.title);
                            form.find('[name="link"]').val(res.data.link);
                            // Eski ikon metin alanı kaldırıldı, sadece id'yi güncel formda tutuyoruz.

                            // ⭐ Mevcut ikon bilgisini göster ⭐
                            const currentIconName = res.data.icon ? res.data.icon : 'Mevcut ikon yok.';
                            const iconHtml = res.data.icon ? 
                                `**Dosya Adı:** ${currentIconName} <br> <img src="${UPLOAD_PATH}${res.data.icon}" alt="Mevcut İkon" style="max-height: 40px; max-width: 40px; display: block; margin-top: 5px;">` :
                                currentIconName;
                            $('#current_icon_display').html(iconHtml);
                            
                            // class_id'leri ayırıp Select2'ye yükle
                            let selectedClasses = res.data.class_id ? res.data.class_id.split(';') : [];
                            form.find('select[name="class_id[]"]').val(selectedClasses).trigger('change');
                            
                            // File input'u her açılışta temizle
                            $('#edit_icon_file').val(''); 

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

                // 5. Güncelleme İşlemi (Görsel Desteği ile Güncellendi)
                $('#editTourForm').on('submit', function(e) {
                    e.preventDefault();
                    
                    // FormData kullanımı zorunlu: Dosya ve diğer form verilerini paketler
                    var formData = new FormData(this);

                    $.ajax({
                        url: './includes/ajax.php?service=sanalGezilerUpdate',
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        processData: false, // FormData kullanılırken zorunlu
                        contentType: false, // FormData kullanılırken zorunlu
                        success: function(res) {
                            Swal.fire({
                                title: res.message,
                                icon: res.status,
                                confirmButtonText: 'Tamam'
                            });
                            if (res.status === 'success') {
                                $('#editTourModal').modal('hide');
                                table.ajax.reload(null, false);
                            }
                        },
                        error: handleAjaxFail
                    });
                });

                // 6. Silme İşlemi (Delete) - Değişmedi
                $(document).on('click', '.deleteTour', function(e) {
                    e.preventDefault();
                    let id = $(this).data('id');

                    Swal.fire({
                        title: 'Emin misiniz?',
                        text: 'Bu sanal gezi silinecek!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Evet, sil',
                        cancelButtonText: 'Vazgeç'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.post('./includes/ajax.php?service=sanalGezilerDelete', {
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

                // 7. Durum Değiştirme İşlemi (Status) - Değişmedi
                $(document).on('click', '.toggleStatus', function(e) {
                    e.preventDefault();
                    let id = $(this).data('id');
                    let newStatus = $(this).data('status');
                    $.post('./includes/ajax.php?service=sanalGezilerStatus', {
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