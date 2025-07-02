"use strict";

// Class definition
var KTCustomersList = function () {
    // Define shared variables
    var datatable;
    var table;
    var form; // Declare form here

    // Private functions
    var initCustomerList = function () {
        // Set date data order
        const tableRows = table.querySelectorAll('tbody tr');

        tableRows.forEach(row => {
            const dateRow = row.querySelectorAll('td');
            const realDate = moment(dateRow[2].innerHTML, "DD MMM YYYY, LT").format(); // select date from 3rd column in table
            dateRow[1].setAttribute('data-order', realDate);
        });

        // Init datatable --- more info on datatables: https://datatables.net/manual/
        datatable = $(table).DataTable({
            "info": false,
            'order': [
                [7, 'asc'],
            ],
            'columnDefs': [
                { orderable: false, targets: 0 }, // Disable ordering on column 0 (checkbox)
                { orderable: false, targets: 9 }, // Disable ordering on column 9 (actions)
            ]
        });

        // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
        datatable.on('draw', function () {
            initToggleToolbar();
            handleDeleteRows();
            toggleToolbars();
            KTMenu.init(); // reinit KTMenu instances 
        });
    }

    // Function to handle the class change logic
    var handleClassChange = function () {
        // Ensure form and the select element exist
        if (form && form.querySelector('[name="sinif"]')) {
            $(form.querySelector('[name="sinif"]')).on('change', function () {
                var classChoose = $("#sinif").val();

                $.ajax({
                    allowClear: true,
                    type: "POST",
                    url: "includes/select_for_lesson.inc.php",
                    data: { class: classChoose },
                    dataType: "json",
                    success: function (data) {
                        if (data.length > 0) {
                            $('#ders').select2('destroy');
                            $('#ders').html('<option value="">Ders Yok</option>');
                            $('#ders').select2({ data: data });
                            $('#unite').select2('destroy');
                            $('#unite').html('<option value="">Ünite Yok</option>');
                        } else {
                            // Re-initialize select2 correctly after clearing
                            $('#sinif').select2('destroy'); // This line might be unintended, usually you wouldn't destroy the sinif select2 on ders/unite change
                            $('#ders').select2('destroy');
                            $('#ders').html('<option value="">Ders Yok</option>');
                            $('#unite').select2('destroy');
                            $('#unite').html('<option value="">Ünite Yok</option>');
                        }
                    },
                    error: function (xhr, status, error, response) {
                        Swal.fire({
                            text: error.responseText + ' ' + xhr.responseText,
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Tamam, anladım!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        }).then(function (result) {
                            if (result.isConfirmed) {
                                // Assuming submitButton is defined in this scope or globally accessible
                                // submitButton.disabled = false; // Uncomment and define submitButton if needed
                            }
                        });
                    }
                });
            });
        }

        if (form && form.querySelector('[name="ders"]')) {
            $(form.querySelector('[name="ders"]')).on('change', function () {
                var classChoose = $("#sinif").val();

                var lessonsChoose = $("#ders").val();

                $.ajax({
                    allowClear: true,
                    type: "POST",
                    url: "includes/select_for_unit.inc.php",
                    data: {
                        class: classChoose,
                        lesson: lessonsChoose
                    },
                    dataType: "json",
                    success: function (data) {
                        if (data.length > 0) {
                            $('#unite').html('<option value="">Ünite Seçiniz...</option>'); // Varsayılan yer tutucuya sıfırla
                            $('#unite').select2({ data: data });
                        } else {
                            // Re-initialize select2 correctly after clearing
                            $('#unite').select2('destroy');
                            $('#unite').html('<option value="">Ünite Yok</option>');
                        }
                    },
                    error: function (xhr, status, error, response) {
                        Swal.fire({
                            text: error.responseText + ' ' + xhr.responseText,
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Tamam, anladım!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        }).then(function (result) {
                            if (result.isConfirmed) {
                                // Assuming submitButton is defined in this scope or globally accessible
                                // submitButton.disabled = false; // Uncomment and define submitButton if needed
                            }
                        });
                    }
                });
            });
        }
    }


    // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
    var handleSearchDatatable = () => {
        const filterSearch = document.querySelector('[data-kt-customer-table-filter="search"]');
        if (filterSearch) { // Add a check here as well
            filterSearch.addEventListener('keyup', function (e) {
                datatable.search(e.target.value).draw();
            });
        }
    }

    // Delete konular
    var handleDeleteRows = () => {
        // Select all delete buttons
        const deleteButtons = table.querySelectorAll('[data-kt-customer-table-filter="delete_row"]');

        deleteButtons.forEach(d => {
            // Delete button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                // Select parent row
                const parent = e.target.closest('tr');

                // Get customer name
                const customerName = parent.querySelectorAll('td')[1].innerText;

                const tdElement = parent.querySelector('td[data-file-id]'); // İlk data-file-id'ye sahip td'yi seçer

                if (tdElement) {
                    var fileId = tdElement.dataset.fileId;
                } else {
                    console.log('Belirtilen <td> elemanı bulunamadı.');
                    return; // Exit if element not found
                }

                var activeStatus = parent.querySelectorAll('td')[8].innerText;

                if (activeStatus === "Aktif") {
                    var activeStatus = "pasif";
                    var statusVal = 0;
                } else {
                    var activeStatus = "aktif";
                    var statusVal = 1;
                }

                // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
                Swal.fire({
                    text: customerName + " isimli konuyu " + activeStatus + " yapmak istediğinizden emin misiniz?",
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

                        $.ajax({
                            type: "POST",
                            url: "includes/update_active_topic.inc.php",
                            data: {
                                id: fileId,
                                statusVal: statusVal,
                            },
                            dataType: "json",
                            success: function (response) {
                                if (response.status === "success") {

                                    Swal.fire({
                                        text: customerName + " adlı konu " + activeStatus + " hale gelmiştir!.",
                                        icon: "success",
                                        buttonsStyling: false,
                                        confirmButtonText: "Tamam, anladım!",
                                        customClass: {
                                            confirmButton: "btn btn-primary"
                                        }
                                    }).then(function (result) {
                                        if (result.isConfirmed) {
                                            location.reload();
                                        }
                                    });
                                } else {
                                    Swal.fire({
                                        text: response.message,
                                        icon: "error",
                                        buttonsStyling: false,
                                        confirmButtonText: "Tamam, anladım!",
                                        customClass: {
                                            confirmButton: "btn btn-primary"
                                        },
                                    }).then(function (result) {
                                        if (result.isConfirmed) {
                                            // submitButton.disabled = false; // Uncomment and define submitButton if needed
                                        }
                                    });
                                }
                            },
                            error: function (xhr, status, error, response) {
                                Swal.fire({
                                    text: "Bir sorun oldu!",
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Tamam, anladım!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                }).then(function (result) {
                                    if (result.isConfirmed) {
                                        // submitButton.disabled = false; // Uncomment and define submitButton if needed
                                    }
                                });
                            },
                        });
                    } else if (result.dismiss === 'cancel') {
                        Swal.fire({
                            text: customerName + " pasif edilmedi.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }
                        });
                    }
                });
            })
        });
    }

    // Init toggle toolbar
    var initToggleToolbar = () => {
        // Toggle selected action toolbar
        const checkboxes = table.querySelectorAll('[type="checkbox"]');
        const deleteSelected = document.querySelector('[data-kt-customer-table-select="delete_selected"]');

        checkboxes.forEach(c => {
            c.addEventListener('click', function () {
                setTimeout(function () {
                    toggleToolbars();
                }, 50);
            });
        });

        if (deleteSelected) { // Add a check for deleteSelected
            deleteSelected.addEventListener('click', function () {
                Swal.fire({
                    text: "Are you sure you want to delete selected customers?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Yes, delete!",
                    cancelButtonText: "No, cancel",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(function (result) {
                    if (result.value) {
                        Swal.fire({
                            text: "You have deleted all selected customers!.",
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }
                        }).then(function () {
                            checkboxes.forEach(c => {
                                if (c.checked) {
                                    datatable.row($(c.closest('tbody tr'))).remove().draw();
                                }
                            });
                            const headerCheckbox = table.querySelectorAll('[type="checkbox"]')[0];
                            headerCheckbox.checked = false;
                        });
                    } else if (result.dismiss === 'cancel') {
                        Swal.fire({
                            text: "Selected customers was not deleted.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }
                        });
                    }
                });
            });
        }
    }

    // Toggle toolbars
    const toggleToolbars = () => {
        const toolbarBase = document.querySelector('[data-kt-customer-table-toolbar="base"]');
        const toolbarSelected = document.querySelector('[data-kt-customer-table-toolbar="selected"]');
        const selectedCount = document.querySelector('[data-kt-customer-table-select="selected_count"]');

        if (!toolbarBase || !toolbarSelected || !selectedCount) { // Add checks
            return;
        }

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

    // Public methods
    return {
        init: function () {
            table = document.querySelector('#kt_customers_table');
            form = document.querySelector('#filtreleme'); // Assign form here

            if (!table) {
                console.warn('Table #kt_customers_table not found.');
                return;
            }
            if (!form) {
                console.warn('Form #filtreleme not found.');
                // Don't return, as other functionalities might still work
            }


            initCustomerList();
            initToggleToolbar();
            handleSearchDatatable();
            handleDeleteRows();
            handleClassChange(); // Call the new function here
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTCustomersList.init();
});