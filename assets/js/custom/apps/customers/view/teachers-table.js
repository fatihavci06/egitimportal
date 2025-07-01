"use strict";

// Class definition
var KTCustomerViewPaymentTable2 = function () {

    // Define shared variables
    var datatable2;
    var table2 = document.querySelector('#kt_table_teachers');

    // Private functions
    var initCustomerView = function () {
        // Set date data order
        const tableRows2 = table2.querySelectorAll('tbody tr');

        tableRows2.forEach(row => {
            const dateRow2 = row.querySelectorAll('td');
            const realDate2 = moment(dateRow2[2].innerHTML, "DD MMM YYYY, LT").format(); // select date from 4th column in table
            dateRow2[2].setAttribute('data-order', realDate2);
        });

        // Init datatable --- more info on datatables: https://datatables.net/manual/
        datatable2 = $(table2).DataTable({
            "info": false,
            'order': [[0, 'asc']], // Set default order
            "pageLength": 5,
            "lengthChange": false,
            'columnDefs': [
                { orderable: false, targets: 3 }, // Disable ordering on column 5 (actions)
            ]
        });
    }

    // Delete customer
   var deleteRows = () => {
        // Select all delete buttons
        const deleteButtons2 = table2.querySelectorAll('[data-kt-customer-table-filter="delete_row2"]');

        deleteButtons2.forEach(d => {
            // Delete button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                // Select parent row
                const parent = e.target.closest('tr');

                // Get customer name
                const customerName = parent.querySelectorAll('td')[0].innerText;

                const tdElement = parent.querySelector('td[data-file-id]'); // İlk data-file-id'ye sahip td'yi seçer

                if (tdElement) {
                    var fileId = tdElement.dataset.fileId;
                } else {
                    console.log('Belirtilen <td> elemanı bulunamadı.');
                }

                var activeStatus = parent.querySelectorAll('td')[3].innerText;

                if (activeStatus === "Aktif") {
                    var activeStatus = "pasif";
                    var statusVal = 0;
                } else {
                    var activeStatus = "aktif";
                    var statusVal = 1;
                }

                // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
                Swal.fire({
                    text: customerName + " isimli alt konuyu " + activeStatus + " yapmak istediğinizden emin misiniz?",
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
                            url: "includes/update_active_subtopic.inc.php",
                            data: {
                                id: fileId,
                                statusVal: statusVal,
                            },
                            dataType: "json",
                            success: function (response) {
                                if (response.status === "success") {

                                    Swal.fire({
                                        text: customerName + "adlı alt konu " + activeStatus + " hale gelmiştir!.",
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
                                            submitButton.disabled = false;
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
                                        submitButton.disabled = false;
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

    // Public methods
    return {
        init: function () {
            if (!table2) {
                return;
            }

            initCustomerView();
            deleteRows();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTCustomerViewPaymentTable2.init();
});