"use strict";

var KTCustomersList = function () {

    var datatable;
    var table

    var initCustomerList = function () {
        const tableRows = table.querySelectorAll('tbody tr');

        tableRows.forEach(row => {
            const dateRow = row.querySelectorAll('td');
            if (dateRow.length >= 6 && dateRow[5].innerHTML.trim() !== '') {
                const realDate = moment(dateRow[5].innerHTML, "DD MMM YYYY, LT").format();
                dateRow[6].setAttribute('data-order', realDate);
            }
        });

        datatable = $(table).DataTable({
            "info": false,
            'order': [
                [6, 'asc']
            ],
            'columnDefs': [
                { orderable: false, targets: 0 },
            ]
        });

        datatable.on('draw', function () {
            initToggleToolbar();
            handleAlterActiveStatusRow();
            toggleToolbars();
            KTMenu.init();
        });
    }

    var handleSearchDatatable = () => {
        const filterSearch = document.querySelector('[data-kt-customer-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }

    var handleAlterActiveStatusRow = () => {
        const deleteButtons = table.querySelectorAll('[data-kt-customer-table-filter="delete_row"]');

        deleteButtons.forEach(d => {
            d.addEventListener('click', function (e) {
                e.preventDefault();

                const parent = e.target.closest('tr');

                const customerName = parent.querySelectorAll('td')[1].innerText;
                const wordId = parent.getAttribute('id');
                var activeStatus = parent.querySelectorAll('td')[2].innerText;

                if (activeStatus === "Aktif") {
                    activeStatus = "pasif";
                } else {
                    activeStatus = "aktif";
                }

                Swal.fire({
                    text: customerName + " isimli Kelimeyi " + activeStatus + " yapmak istediğinizden emin misiniz?",
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
                            { id: wordId },
                            `İşlem tamamlandı.`,
                            function () {
                               // datatable.row($(parent)).remove().draw();
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
            })
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
                    text: "Lütfen en az bir duyuru seçin.",
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
                text: "Seçilen duyuruları pasif yapmak istediğinizden emin misiniz?",
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
                        "Seçilen duyurular başarıyla pasif hale getirildi.",
                        function () {
                            selectedCheckboxes.forEach(c => {
                                datatable.row($(c.closest('tr'))).remove().draw();
                            });
                            const headerCheckbox = table.querySelector('[type="checkbox"]');
                            headerCheckbox.checked = false;
                            toggleToolbars();
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
            url: "includes/alter_active_doyouknow.inc.php",
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