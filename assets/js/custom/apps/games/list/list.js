"use strict";

var KTCustomersList = function () {
    var datatable;
    var submitButton;
    var table

    var initCustomerList = function () {

        const tableRows = table.querySelectorAll('tbody tr');

        tableRows.forEach(row => {
            const dateRow = row.querySelectorAll('td');
            const realDate = moment(dateRow[6].innerHTML, "DD MMM YYYY, LT").format(); // select date from 3rd column in table
            dateRow[6].setAttribute('data-order', realDate);
        });

        datatable = $(table).DataTable({
            "info": false,
            'order': [],
            'columnDefs': [
                { orderable: false, targets: 0 }, // Disable ordering on column 0 (checkbox)
                { orderable: false, targets: 6 }, // Disable ordering on column 7 (actions)
            ]
        });

        datatable.on('draw', function () {
            initToggleToolbar();
            handleDeleteRows();
            toggleToolbars();
            KTMenu.init(); // reinit KTMenu instances 
        });
    }

    var handleSearchDatatable = () => {
        const filterSearch = document.querySelector('[data-kt-customer-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }

    var handleAlterActiveStatusRow = () => {
        // Tüm tabloyu (veya en azından `data-kt-customer-table-filter="delete_row"` butonlarını içeren üst öğeyi) dinle
        const tableBody = table.querySelector('tbody'); // Eğer `table` değişkeniniz DataTables'ın DOM öğesiyse

        tableBody.addEventListener('click', function (e) {
            // Kontrol: Tıklanan öğenin aradığımız buton olup olmadığını kontrol et
            const clickedButton = e.target.closest('[data-kt-customer-table-filter="delete_row"]');

            if (clickedButton) {
                e.preventDefault();

                // clickedButton üzerinden gerekli işlemleri yap
                const parent = clickedButton.closest('tr');

                const customerName = parent.querySelectorAll('td')[2].innerText;
                const gameId = parent.getAttribute('id');
                var activeStatus = parent.querySelectorAll('td')[3].innerText;

                //const actionButton = parent.querySelector('[data-kt-customer-table-filter="delete_row"]');
                const actionButton = e.target.closest('[data-kt-customer-table-filter="delete_row"]');

                // ... (geriye kalan mevcut kodunuz aynı kalır)

                if (activeStatus === "Aktif") {
                    activeStatus = "pasif";
                } else {
                    activeStatus = "aktif";
                }

                Swal.fire({
                    text: customerName + " isimli oyunu " + activeStatus + " yapmak istediğinizden emin misiniz?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Evet, " + activeStatus + " yap!",
                    cancelButtonText: "Hayır, iptal et",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(function (result) {
                    if (result.value) {
                        sendAlterRequest(
                            { id: gameId },
                            `İşlem tamamlandı.`,
                            function () {
                                // Belki burada DataTables'ı yeniden çizmek (`table.draw(false);`)
                                // veya satırı DOM'dan kaldırmak/güncellemek gerekebilir.
                                // BURASI GÜNCELLENİYOR: Badge (Span) İçeriği ve Class'ları

                                // 1. Durumun yazılı olduğu 4. hücreyi (index 3) bul
                                const statusCell = parent.querySelectorAll('td')[3];

                                // 2. Yeni durumu ve metnini belirle
                                const newStatusText = activeStatus === "pasif" ? "Pasif" : "Aktif";

                                // 3. Hücre içindeki badge'i bul
                                const badge = statusCell.querySelector('.badge');

                                if (badge) {
                                    // Metni güncelle
                                    badge.innerText = newStatusText;

                                    // Class'ları (Renkleri) güncelle
                                    const activeClass = 'badge-light-success'; // Tahmin edilen Aktif class'ı
                                    const passiveClass = 'badge-light-danger'; // Tahmin edilen Pasif class'ı

                                    if (activeStatus === "aktif") {
                                        // Pasif class'ı kaldır, Aktif class'ı ekle
                                        badge.classList.remove(passiveClass);
                                        badge.classList.add(activeClass);
                                    } else {
                                        // Aktif class'ı kaldır, Pasif class'ı ekle
                                        badge.classList.remove(activeClass);
                                        badge.classList.add(passiveClass);
                                    }



                                    // 🔹 Menüdeki buton metnini güncelle
                                    const currentText = actionButton.textContent.trim().toLowerCase();
                                    const newText = currentText === "aktif yap" ? "Pasif Yap" : "Aktif Yap";
                                    actionButton.textContent = newText;

                                    // DataTables API'si ile veriyi güncelle (sıralama/arama için önemli)
                                    $(parent).closest('table').DataTable()
                                        /*  .row(parent)
                                         .cell(statusCell)
                                         .data(newStatusText) */
                                        .draw(false);

                                } else {
                                    // Eğer hücrede badge yoksa, sadece metni güncelleyelim.
                                    // Bu kısım sadece bir güvenlik önlemidir, span varken buraya düşmemesi gerekir.
                                    statusCell.innerText = newStatusText;



                                    // 🔹 Menüdeki buton metnini güncelle
                                        const currentText = actionButton.textContent.trim().toLowerCase();
                                        const newText = currentText === "aktif yap" ? "Pasif Yap" : "Aktif Yap";
                                        actionButton.textContent = newText;
                                    
                                    $(parent).closest('table').DataTable()
                                        /* .row(parent)
                                        .cell(statusCell)
                                        .data(newStatusText) */
                                        .draw(false);
                                }
                            }
                        );
                    } else if (result.dismiss === 'cancel') {
                        Swal.fire({
                            text: "İşlem tamamlanmadı",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Tamam, anladım!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }
                        });
                    }
                });
            }
        });
    }

    var initToggleToolbar = () => {

        const checkboxes = table.querySelectorAll('[type="checkbox"]');
        const deactivateSelected = document.querySelector('[data-kt-customer-table-select="delete_selected"]');

        checkboxes.forEach(c => {
            c.addEventListener('click', function () {
                setTimeout(function () {
                    toggleToolbars();
                }, 50);
            });
        });

        deactivateSelected.addEventListener('click', function () {
            const selectedCheckboxes = table.querySelectorAll('tbody [type="checkbox"]:checked');
            const selectedIds = [];

            selectedCheckboxes.forEach(c => {
                const row = c.closest('tr');
                const rowId = row.getAttribute('id');
                if (rowId) {
                    selectedIds.push(rowId);
                }
            });
            if (selectedIds.length === 0) {
                Swal.fire({
                    text: "Lütfen en az bir oyunu seçin.",
                    icon: "warning",
                    buttonsStyling: false,
                    confirmButtonText: "Tamam, anladım!",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });
                return;
            }

            Swal.fire({
                text: "Seçilen oyunu pasif yapmak istediğinizden emin misiniz?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Evet, pasif yap!",
                cancelButtonText: "Hayır, iptal et",
                customClass: {
                    confirmButton: "btn fw-bold btn-danger",
                    cancelButton: "btn fw-bold btn-active-light-primary"
                }
            }).then(function (result) {
                if (result.value) {
                    sendAlterRequest(
                        { 'ids[]': selectedIds },
                        "Seçilen oyun  başarıyla pasif hale getirildi.",
                        function () {
                        }
                    );
                }
            });
        });

    }

    const toggleToolbars = () => {

        const toolbarBase = document.querySelector('[data-kt-customer-table-toolbar="base"]');
        const toolbarSelected = document.querySelector('[data-kt-customer-table-toolbar="selected"]');
        const selectedCount = document.querySelector('[data-kt-customer-table-select="selected_count"]');

        const allCheckboxes = table.querySelectorAll('tbody [type="checkbox"]');

        let checkedState = false;
        let count = 0;

        allCheckboxes.forEach(c => {
            if (c.checked) {
                checkedState = true;
                count++;
            }
        });

        if (checkedState) {
            selectedCount.innerHTML = count;
            toolbarBase.classList.add('d-none');
            toolbarSelected.classList.remove('d-none');
        } else {
            toolbarBase.classList.remove('d-none');
            toolbarSelected.classList.add('d-none');
        }
    }

    function sendAlterRequest(payload, successMsg, onSuccess) {
        $.ajax({
            type: "POST",
            url: "includes/alter_active_game.inc.php",
            data: payload,
            traditional: true,
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    Swal.fire({
                        text: successMsg,
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Tamam, anladım!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    }).then(() => {
                        if (typeof onSuccess === 'function') {
                            onSuccess();
                        }
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        text: response.message,
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Tamam, anladım!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                }
            },
            error: function () {
                Swal.fire({
                    text: "Bir hata oluştu!",
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Tamam, anladım!",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });
            }
        });
    }

    return {
        init: function () {
            table = document.querySelector('#kt_customers_table');

            if (!table) {
                return;
            }

            initCustomerList();
            initToggleToolbar();
            handleSearchDatatable();
            handleAlterActiveStatusRow();
        }
    }
}();

KTUtil.onDOMContentLoaded(function () {
    KTCustomersList.init();
});