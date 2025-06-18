<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);

if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 4 or $_SESSION['role'] == 9)) {
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";
    include_once "classes/units.classes.php";
    include_once "classes/units-view.classes.php";

    include_once "views/pages-head.php";

    $chooseUnit = new ShowUnit();
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
                            <?php if ($_SESSION['role'] == 1) { ?>
                                <div id="kt_app_content" class="app-content flex-column-fluid">
                                    <div id="kt_app_content_container" class="app-container container-fluid">
                                        <div class="row">

                                            <div class="col-lg-4">
                                                <label class=" fs-6 fw-semibold mb-2" for="title">Başlık</label>
                                                <input type="text" class="form-control " placeholder="Test Başlığı" id="title">
                                            </div>

                                            <div class="col-lg-4">
                                                <label class=" fs-6 fw-semibold mb-2" for="class_id">Sınıf Seçimi </label>
                                                <?php
                                                $class = new Classes();
                                                $classList = $class->getClassesList();
                                                ?>
                                                <select class="form-select" id="class_id" required aria-label="Default select example">
                                                    <option value="">Seçiniz</option>
                                                    <?php foreach ($classList as $c) { ?>
                                                        <option value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-lg-4 mt-3">
                                                <label class=" fs-6 fw-semibold" for="lesson_id">Dersler</label>
                                                <select class="form-select" id="lesson_id" required>
                                                    <option value="">Ders seçiniz</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mt-3">

                                            <div class="col-lg-4 mt-4">
                                                <label class=" fs-6 fw-semibold mb-2" for="unit_id">Ünite Seçimi</label>
                                                <select class="form-select" id="unit_id" required>
                                                    <option value="">Ünite seçiniz</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-4 mt-4">
                                                <label class="fs-6 fw-semibold mb-2" for="topic_id">Konu Seçimi</label>
                                                <select class="form-select" id="topic_id" required>
                                                    <option value="">Seçiniz</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-4 mt-4">
                                                <label class="fs-6 fw-semibold mb-2" for="subtopic_id">Alt Konu Seçimi</label>
                                                <select class="form-select" id="subtopic_id" required>
                                                    <option value="">Alt Konu seçiniz</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mt-5 mb-5">
                                            <div class="col-lg-3">
                                                <button type="button" id="filterButton" class="btn btn-success btn-sm w-100">Filtrele</button>
                                            </div>
                                            <div class="col-lg-2">
                                                <button type="button" id="clearFiltersButton" class="btn btn-secondary btn-sm w-100">Temizle</button>
                                            </div>
                                        </div>
                                    <?php } elseif ($_SESSION['role'] == 4) {
                                    $class_id = $_SESSION['class_id'];
                                    $lesson_id = $_SESSION['lesson_id']; ?>
                                        <div id="kt_app_content" class="app-content flex-column-fluid">
                                            <div id="kt_app_content_container" class="app-container container-fluid">
                                                <div class="row">

                                                    <div class="col-lg-4">
                                                        <label class=" fs-6 fw-semibold mb-2" for="title">Başlık</label>
                                                        <input type="text" class="form-control " placeholder="Test Başlığı" id="title">
                                                    </div>

                                                    <div class="col-lg-4">
                                                        <label class="fs-6 fw-semibold mb-2" for="unit_id">Ünite Seçimi</label>
                                                        <select class="form-select" id="unit_id" required>
                                                            <option value="">Ünite seçiniz</option>
                                                            <?php echo $chooseUnit->getUnitSelectList(); ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-lg-4">
                                                        <label class="fs-6 fw-semibold mb-2" for="topic_id">Konu Seçimi</label>
                                                        <select class="form-select" id="topic_id" required>
                                                            <option value="">Seçiniz</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mt-3">


                                                    <div class="col-lg-4 mt-4">
                                                        <label class="fs-6 fw-semibold mb-2" for="subtopic_id">Alt Konu Seçimi</label>
                                                        <select class="form-select" id="subtopic_id" required>
                                                            <option value="">Alt Konu seçiniz</option>
                                                        </select>
                                                    </div>

                                                    <div class="fv-row">
                                                        <input class="form-select form-select-solid fw-bold" type="hidden" name="class_id" id="class_id" value="<?php echo $class_id; ?>">
                                                    </div>
                                                    <div class="fv-row">
                                                        <input class="form-select form-select-solid fw-bold" type="hidden" name="lesson_id" id="lesson_id" value="<?php echo $lesson_id; ?>">
                                                    </div>

                                                </div>

                                                <div class="row mt-5 mb-5">
                                                    <div class="col-lg-3">
                                                        <button type="button" id="filterButton" class="btn btn-success btn-sm w-100">Filtrele</button>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <button type="button" id="clearFiltersButton" class="btn btn-secondary btn-sm w-100">Temizle</button>
                                                    </div>
                                                </div>

                                            <?php } ?>
                                            <div id="tests_container"></div>

                                            <div class="card mt-5">
                                                <div class="card-header border-0 pt-6">
                                                    <h3 class="card-title align-items-start flex-column">
                                                        <span class="card-label fw-bold fs-3 mb-1">Filtrelenmiş Test Sonuçları</span>
                                                    </h3>
                                                </div>
                                                <div class="card-body py-4">
                                                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="tests_datatable">
                                                        <thead>
                                                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                                                <th>ID</th>
                                                                <th>Test Başlığı</th>

                                                                <th>Oluşturulma Tarihi</th>
                                                                <th>İşlemler</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="text-gray-600 fw-semibold">
                                                        </tbody>
                                                    </table>
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

            <script src="assets/plugins/custom/tinymce/tinymce.bundle.js"></script>

            <script>
                $(document).ready(function() {

                    // DataTable'ı başlat
                    // Başlangıçta ajax yapılandırmasını kaldırdık, böylece tablo boş başlayacak.
                    var testsDataTable = $('#tests_datatable').DataTable({
                        ajax: {
                            url: 'includes/ajax-ayd.php?service=getFilteredTests',
                            type: 'POST',
                            data: function(d) {
                                // 'd' objesi DataTables'ın varsayılan parametrelerini (arama, sayfalama, sıralama) içerir.
                                // Biz buraya kendi filtre input'larımızdan gelen değerleri ekliyoruz.
                                // Bu değerler, AJAX isteği ile backend'e POST olarak gönderilecektir.
                                d.title = $('#title').val();
                                d.class_id = $('#class_id').val();
                                d.lesson_id = $('#lesson_id').val();
                                d.unit_id = $('#unit_id').val();
                                d.topic_id = $('#topic_id').val();
                                d.subtopic_id = $('#subtopic_id').val();
                                // DataTables'ın global arama kutusu değeri otomatik olarak 'd.search.value' olarak zaten gönderilir.
                            },
                            dataSrc: function(json) {
                                // Backend'den gelen JSON yanıtını kontrol ediyoruz.
                                // Eğer status 'success' ise, 'data' anahtarındaki diziyi DataTables'a gönderiyoruz.
                                if (json.status === 'success') {
                                    return json.data;
                                } else {
                                    // Hata durumunda konsola log düşüp boş bir dizi döndürüyoruz.
                                    console.error("Backend'den hata döndü:", json.message);
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Hata!',
                                        text: json.message || 'Veriler yüklenirken bir hata oluştu.',
                                        confirmButtonText: 'Tamam'
                                    });
                                    return [];
                                }
                            }
                        }, // Yükleme göstergesini etkinleştir
                        order: [
                            [0, 'desc']
                        ],
                        // ajax yapılandırması burada yok, sadece filtreleme ile tetiklenecek.
                        columns: [{
                                data: 'id'
                            },
                            {
                                data: 'test_title'
                            },
                            {
                                data: 'created_at'
                            },
                            {
                                data: null,
                                orderable: false,
                                searchable: false,
                                render: function(data, type, row) {
                                    return `
                        <button class="btn btn-sm btn-primary edit-test-btn" data-id="${row.id}" title="Testi Düzenle">
                            <i class="fas fa-edit"></i> Düzenle
                        </button>
                        <button class="btn btn-sm btn-danger delete-test-btn" data-id="${row.id}" title="Testi Sil">
                            <i class="fas fa-trash"></i> Sil
                        </button>
                    `;
                                }
                            }
                        ]
                        // ... (Diğer DataTables ayarları: dom, vb. eklenebilir) ...
                    });

                    // Filtreleme butonu tıklaması
                    $('#filterButton').on('click', function() {
                        var classId = $('#class_id').val();
                        var lessonId = $('#lesson_id').val();
                        var unitId = $('#unit_id').val();

                        // Sınıf ve Ders seçimi zorunlu kontrolü
                        if (!classId || classId === '') {
                            // Swal.fire({
                            //     icon: 'warning',
                            //     title: 'Uyarı',
                            //     text: 'Lütfen bir sınıf seçiniz.',
                            //     confirmButtonText: 'Tamam'
                            // });
                            // $('#class_id').addClass('is-invalid'); // Bootstrap ile görsel uyarı
                            // $('#lesson_id').addClass('is-invalid'); // Bootstrap ile görsel uyarı
                            // $('#unit_id').addClass('is-invalid'); // Bootstrap ile görsel uyarı
                            // return; // Filtreleme işlemini durdur
                        } else {
                            $('#class_id').removeClass('is-invalid');
                            $('#lesson_id').removeClass('is-invalid');
                            $('#unit_id').removeClass('is-invalid');
                        }


                        testsDataTable.ajax.reload(function(json) {
                            // Bu callback fonksiyonu, AJAX isteği tamamlandığında ve DataTables verileri işlediğinde çalışır.
                            // dataSrc zaten hata durumunu ele aldığı için burası opsiyoneldir.
                            // console.log("DataTables yeniden yüklendi:", json);
                        });
                    });

                    // Temizle butonu tıklaması
                    $('#clearFiltersButton').on('click', function() {
                        // Filtreleme alanlarını temizle
                        $('#title').val('');
                        $('#class_id').val('').trigger('change'); // 'change' olayı ile diğer bağımlı selectbox'ları da temizle
                        $('#lesson_id').val('');
                        $('#unit_id').val('');
                        $('#topic_id').val('');
                        $('#subtopic_id').val('');
                        testsDataTable.ajax.reload(null, false);
                        // Temizlenmiş filtrelerle DataTable'ı yeniden yükle.
                        // Bu, 'getFilteredTests' servisinize boş filtre değerleri gönderecektir.

                    });

                    // --- Ders, Ünite, Konu, Alt Konu Seçim Mantığı ---

                    // Sınıf seçimi değiştiğinde dersleri getir
                    $('#class_id').on('change', function() {
                        var classId = $(this).val();
                        fetchLessonsForClass(classId);
                    });

                    function fetchLessonsForClass(classId) {
                        if (classId !== '') {
                            $.ajax({
                                url: 'includes/ajax.php?service=getLessonList',
                                type: 'POST',
                                data: {
                                    class_id: classId
                                },
                                dataType: 'json',
                                success: function(response) {
                                    var $lessonSelect = $('#lesson_id');
                                    $('#option_count').val(response.data.optionCount); // Bu kısım ilgili inputunuz varsa
                                    $lessonSelect.empty();
                                    $lessonSelect.append('<option value="">Ders seçiniz</option>');
                                    $.each(response.data.lessons, function(index, lesson) {
                                        $lessonSelect.append('<option value="' + lesson.id + '">' + lesson.name + '</option>');
                                    });
                                    // Diğer bağımlı selectbox'ları temizle
                                    $('#unit_id').html('<option value="">Ünite seçiniz</option>');
                                    $('#topic_id').html('<option value="">Seçiniz</option>');
                                    $('#subtopic_id').html('<option value="">Alt Konu seçiniz</option>');
                                },
                                error: function(xhr) {
                                    handleAjaxError(xhr);
                                }
                            });
                        } else {
                            // Sınıf seçimi boşsa tüm bağımlı selectbox'ları temizle
                            $('#lesson_id').html('<option value="">Ders seçiniz</option>');
                            $('#unit_id').html('<option value="">Ünite seçiniz</option>');
                            $('#topic_id').html('<option value="">Seçiniz</option>');
                            $('#subtopic_id').html('<option value="">Alt Konu seçiniz</option>');
                        }
                    }

                    // Ders seçimi değiştiğinde üniteleri getir
                    $('#lesson_id').on('change', function() {
                        var lessonId = $(this).val();
                        var classId = $('#class_id').val(); // Sınıf ID'sini de gönderiyoruz
                        var $unitSelect = $('#unit_id');
                        $unitSelect.empty().append('<option value="">Ünite seçiniz</option>');
                        $('#topic_id').html('<option value="">Seçiniz</option>');
                        $('#subtopic_id').html('<option value="">Alt Konu seçiniz</option>');

                        if (lessonId !== '') {
                            $.ajax({
                                url: 'includes/ajax.php?service=getUnitList',
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    lesson_id: lessonId,
                                    class_id: classId
                                },
                                success: function(response) {
                                    if (response.status === 'success' && response.data.length > 0) {
                                        $.each(response.data, function(index, unit) {
                                            $unitSelect.append($('<option>', {
                                                value: unit.id,
                                                text: unit.name
                                            }));
                                        });
                                    } else {
                                        $unitSelect.append('<option disabled>Ünite bulunamadı</option>');
                                    }
                                },
                                error: function(xhr) {
                                    handleAjaxError(xhr);
                                }
                            });
                        }
                    });

                    // Ünite seçimi değiştiğinde konuları getir
                    $('#unit_id').on('change', function() {
                        var classId = $('#class_id').val();
                        var lessonId = $('#lesson_id').val();
                        var unitId = $(this).val();
                        var $topicSelect = $('#topic_id');

                        $topicSelect.empty().append('<option value="">Seçiniz</option>');
                        $('#subtopic_id').html('<option value="">Alt Konu seçiniz</option>');

                        if (unitId !== '') {
                            $.ajax({
                                url: 'includes/ajax.php?service=getTopicList',
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    class_id: classId,
                                    lesson_id: lessonId,
                                    unit_id: unitId
                                },
                                success: function(response) {
                                    if (response.status === 'success' && response.data.length > 0) {
                                        $.each(response.data, function(index, topic) {
                                            $topicSelect.append($('<option>', {
                                                value: topic.id,
                                                text: topic.name
                                            }));
                                        });
                                    } else {
                                        $topicSelect.append('<option disabled>Konu bulunamadı</option>');
                                    }
                                },
                                error: function(xhr) {
                                    handleAjaxError(xhr);
                                }
                            });
                        }
                    });

                    // Konu seçimi değiştiğinde alt konuları getir
                    $('#topic_id').on('change', function() {
                        var classId = $('#class_id').val();
                        var lessonId = $('#lesson_id').val();
                        var unitId = $('#unit_id').val();
                        var topicId = $(this).val();
                        var $subtopicSelect = $('#subtopic_id');

                        $subtopicSelect.empty().append('<option value="">Alt Konu seçiniz</option>');

                        if (topicId !== '') {
                            $.ajax({
                                url: 'includes/ajax.php?service=getSubtopicList',
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    class_id: classId,
                                    lesson_id: lessonId,
                                    unit_id: unitId,
                                    topic_id: topicId
                                },
                                success: function(response) {
                                    if (response.status === 'success' && response.data.length > 0) {
                                        $.each(response.data, function(index, subtopic) {
                                            $subtopicSelect.append($('<option>', {
                                                value: subtopic.id,
                                                text: subtopic.name
                                            }));
                                        });
                                    } else {
                                        $subtopicSelect.append('<option disabled>Alt konu bulunamadı</option>');
                                    }
                                },
                                error: function(xhr) {
                                    handleAjaxError(xhr);
                                }
                            });
                        }
                    });

                    // --- İşlemler Butonları İçin Fonksiyonlar ---

                    // Testi Düzenle butonu tıklama olayı
                    $('#tests_datatable tbody').on('click', '.edit-test-btn', function() {
                        var testId = $(this).data('id');
                        // Düzenleme sayfasına yönlendirme
                        window.location.href = 'test-guncelle.php?id=' + testId; // edit_test.php sayfasını sizin oluşturmanız gerekecek
                    });

                    // Testi Sil butonu tıklama olayı
                    $('#tests_datatable tbody').on('click', '.delete-test-btn', function() {
                        var testId = $(this).data('id');
                        Swal.fire({
                            title: 'Emin misiniz?',
                            text: "Bu testi silmek istediğinizden emin misiniz? Bu işlem geri alınamaz!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Evet, Sil!',
                            cancelButtonText: 'İptal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: 'includes/ajax.php?service=deleteTest', // Silme işlemini yapacak backend endpoint'iniz
                                    type: 'POST',
                                    data: {
                                        id: testId
                                    },
                                    dataType: 'json',
                                    success: function(response) {
                                        if (response.status === 'success') {
                                            Swal.fire(
                                                'Silindi!',
                                                'Test başarıyla silindi.',
                                                'success'
                                            );
                                            testsDataTable.ajax.reload(); // DataTable'ı yenile
                                        } else {
                                            Swal.fire(
                                                'Hata!',
                                                response.message || 'Test silinirken bir hata oluştu.',
                                                'error'
                                            );
                                        }
                                    },
                                    error: function(xhr) {
                                        Swal.fire(
                                            'Hata!',
                                            'Sunucu ile iletişimde bir sorun oluştu.',
                                            'error'
                                        );
                                        handleAjaxError(xhr);
                                    }
                                });
                            }
                        });
                    });

                    // AJAX hatalarını işlemek için genel bir fonksiyon (isteğe bağlı)
                    function handleAjaxError(xhr) {
                        console.error("AJAX isteğinde hata:", xhr.status, xhr.statusText, xhr.responseText);
                        // Kullanıcıya daha bilgilendirici bir hata mesajı gösterebilirsiniz.
                    }
                });
            </script>
    </body>

</html>
<?php } else {
    header("location: index");
}
