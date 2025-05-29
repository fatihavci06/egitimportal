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
   /*  var deleteRows = () => {
        // Select all delete buttons
        const deleteButtons = table.querySelectorAll('[data-kt-customer-table-filter="delete_row"]');
        
        deleteButtons.forEach(d => {
            // Delete button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                // Select parent row
                const parent = e.target.closest('tr');

                // Get customer name
                const invoiceNumber = parent.querySelectorAll('td')[0].innerText;

                // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
                Swal.fire({
                    text: "Are you sure you want to delete " + invoiceNumber + "?",
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
                            text: "You have deleted " + invoiceNumber + "!.",
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }
                        }).then(function () {
                            // Remove current row
                            datatable.row($(parent)).remove().draw();
                        }).then(function () {
                            // Detect checked checkboxes
                            toggleToolbars();
                        });
                    } else if (result.dismiss === 'cancel') {
                        Swal.fire({
                            text: customerName + " was not deleted.",
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
    } */

    // Public methods
    return {
        init: function () {
            if (!table2) {
                return;
            }

            initCustomerView();
           // deleteRows();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTCustomerViewPaymentTable2.init();
});