"use strict";

// Class definition
var KTCustomersList = function () {
    // Define shared variables
    var datatable;
    /* var filterMonth;
     var filterPayment;*/
    var table
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
                [6, 'asc']
            ],
            'columnDefs': [
                { orderable: false, targets: 0 }, // Disable ordering on column 0 (checkbox)
                { orderable: false, targets: 8 }, // Disable ordering on column 8 (actions)
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
                            $('#konu').select2('destroy');
                            $('#konu').html('<option value="">Konu Yok</option>');
                            $('#altkonu').select2('destroy');
                            $('#altkonu').html('<option value="">Alt Konu Yok</option>');
                        } else {
                            // Re-initialize select2 correctly after clearing
                            $('#sinif').select2('destroy'); // This line might be unintended, usually you wouldn't destroy the sinif select2 on ders/unite change
                            $('#ders').select2('destroy');
                            $('#ders').html('<option value="">Ders Yok</option>');
                            $('#unite').select2('destroy');
                            $('#unite').html('<option value="">Ünite Yok</option>');
                            $('#konu').select2('destroy');
                            $('#konu').html('<option value="">Konu Yok</option>');
                            $('#altkonu').select2('destroy');
                            $('#altkonu').html('<option value="">Alt Konu Yok</option>');
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
                            $('#konu').select2('destroy');
                            $('#konu').html('<option value="">Konu Yok</option>');
                            $('#altkonu').select2('destroy');
                            $('#altkonu').html('<option value="">Alt Konu Yok</option>');
                        } else {
                            // Re-initialize select2 correctly after clearing
                            $('#unite').select2('destroy');
                            $('#unite').html('<option value="">Ünite Yok</option>');
                            $('#konu').select2('destroy');
                            $('#konu').html('<option value="">Konu Yok</option>');
                            $('#altkonu').select2('destroy');
                            $('#altkonu').html('<option value="">Alt Konu Yok</option>');
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

        if (form && form.querySelector('[name="unite"]')) {
            $(form.querySelector('[name="unite"]')).on('change', function () {
                var classChoose = $("#sinif").val();

                var lessonsChoose = $("#ders").val();

                var unitsChoose = $("#unite").val();

                $.ajax({
                    allowClear: true,
                    type: "POST",
                    url: "includes/select_for_topic.inc.php",
                    data: {
                        class: classChoose,
                        lesson: lessonsChoose,
                        unit: unitsChoose
                    },
                    dataType: "json",
                    success: function (data) {
                        if (data.length > 0) {
                            $('#konu').html('<option value="">Konu Seçiniz...</option>'); // Varsayılan yer tutucuya sıfırla
                            $('#konu').select2({ data: data });
                            $('#altkonu').select2('destroy');
                            $('#altkonu').html('<option value="">Alt Konu Yok</option>');
                        } else {
                            // Re-initialize select2 correctly after clearing
                            $('#konu').select2('destroy');
                            $('#konu').html('<option value="">Konu Yok</option>');
                            $('#altkonu').select2('destroy');
                            $('#altkonu').html('<option value="">Alt Konu Yok</option>');
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

        if (form && form.querySelector('[name="konu"]')) {
            $(form.querySelector('[name="konu"]')).on('change', function () {
                var classChoose = $("#sinif").val();

                var lessonsChoose = $("#ders").val();

                var unitsChoose = $("#unite").val();

                var topicsChoose = $("#konu").val();

                $.ajax({
                    allowClear: true,
                    type: "POST",
                    url: "includes/select_for_subtopic.inc.php",
                    data: {
                        class: classChoose,
                        lesson: lessonsChoose,
                        unit: unitsChoose,
                        topics: topicsChoose
                    },
                    dataType: "json",
                    success: function (data) {
                        if (data.length > 0) {
                            $('#altkonu').html('<option value="">Alt Konu Seçiniz...</option>'); // Varsayılan yer tutucuya sıfırla
                            $('#altkonu').select2({ data: data });
                        } else {
                            // Re-initialize select2 correctly after clearing
                            $('#altkonu').select2('destroy');
                            $('#altkonu').html('<option value="">Alt Konu Yok</option>');
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
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }

    // Filter Datatable
    /* var handleFilterDatatable = () => {
         // Select filter options
         filterMonth = $('[data-kt-customer-table-filter="month"]');
         filterPayment = document.querySelectorAll('[data-kt-customer-table-filter="payment_type"] [name="payment_type"]');
         const filterButton = document.querySelector('[data-kt-customer-table-filter="filter"]');
 
         // Filter datatable on submit
         filterButton.addEventListener('click', function () {
             // Get filter values
             const monthValue = filterMonth.val();
             let paymentValue = '';
 
             // Get payment value
             filterPayment.forEach(r => {
                 if (r.checked) {
                     paymentValue = r.value;
                 }
 
                 // Reset payment value if "All" is selected
                 if (paymentValue === 'all') {
                     paymentValue = '';
                 }
             });
 
             // Build filter string from filter options
             const filterString = monthValue + ' ' + paymentValue;
 
             // Filter datatable --- official docs reference: https://datatables.net/reference/api/search()
             datatable.search(filterString).draw();
         });
     }*/

    // Delete customer
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
                }

                var activeStatus = parent.querySelectorAll('td')[7].innerText;

                if (activeStatus === "Aktif") {
                    var activeStatus = "pasif";
                    var statusVal = 0;
                } else {
                    var activeStatus = "aktif";
                    var statusVal = 1;
                }

                // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
                Swal.fire({
                    text: customerName + " isimli içeriği " + activeStatus + " yapmak istediğinizden emin misiniz?",
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
                            url: "includes/update_active_content.inc.php",
                            data: {
                                id: fileId,
                                statusVal: statusVal,
                            },
                            dataType: "json",
                            success: function (response) {
                                if (response.status === "success") {

                                    Swal.fire({
                                        text: customerName + " adlı içerik " + activeStatus + " hale gelmiştir!.",
                                        icon: "success",
                                        buttonsStyling: false,
                                        confirmButtonText: "Tamam, anladım!",
                                        customClass: {
                                            confirmButton: "btn btn-primary"
                                        }
                                    }).then(function (result) {
                                        if (result.isConfirmed) {
                                            // Remove current row
                                            //datatable.row($(parent)).remove().draw();
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

                                            // Enable submit button after loading
                                            /* submitButton.disabled = false; */
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

                                        // Enable submit button after loading
                                        /* submitButton.disabled = false; */
                                    }
                                });
                                //alert(status + "0");

                            },
                        });
                        /*Swal.fire({
                            text: "You have deleted " + customerName + "!.",
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }
                        }).then(function () {
                            // Remove current row
                            datatable.row($(parent)).remove().draw();
                        });*/
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

    // Reset Filter
    /*var handleResetForm = () => {
        // Select reset button
        const resetButton = document.querySelector('[data-kt-customer-table-filter="reset"]');

        // Reset datatable
        resetButton.addEventListener('click', function () {
            // Reset month
            filterMonth.val(null).trigger('change');

            // Reset payment type
            filterPayment[0].checked = true;

            // Reset datatable --- official docs reference: https://datatables.net/reference/api/search()
            datatable.search('').draw();
        });
    }*/

    // Init toggle toolbar
    var initToggleToolbar = () => {
        // Toggle selected action toolbar
        // Select all checkboxes
        const checkboxes = table.querySelectorAll('[type="checkbox"]');

        // Select elements
        const deleteSelected = document.querySelector('[data-kt-customer-table-select="delete_selected"]');

        // Toggle delete selected toolbar
        checkboxes.forEach(c => {
            // Checkbox on click event
            c.addEventListener('click', function () {
                setTimeout(function () {
                    toggleToolbars();
                }, 50);
            });
        });

        // Deleted selected rows
        deleteSelected.addEventListener('click', function () {
            // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
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
                        // Remove all selected customers
                        checkboxes.forEach(c => {
                            if (c.checked) {
                                datatable.row($(c.closest('tbody tr'))).remove().draw();
                            }
                        });

                        // Remove header checked box
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

    // Toggle toolbars
    const toggleToolbars = () => {
        // Define variables
        const toolbarBase = document.querySelector('[data-kt-customer-table-toolbar="base"]');
        const toolbarSelected = document.querySelector('[data-kt-customer-table-toolbar="selected"]');
        const selectedCount = document.querySelector('[data-kt-customer-table-select="selected_count"]');

        // Select refreshed checkbox DOM elements 
        const allCheckboxes = table.querySelectorAll('tbody [type="checkbox"]');

        // Detect checkboxes state & count
        let checkedState = false;
        let count = 0;

        // Count checked boxes
        allCheckboxes.forEach(c => {
            if (c.checked) {
                checkedState = true;
                count++;
            }
        });

        // Toggle toolbars
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
            /*handleFilterDatatable();*/
            handleDeleteRows();
            /*handleResetForm();*/
            handleClassChange(); // Call the new function here  
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTCustomersList.init();
});