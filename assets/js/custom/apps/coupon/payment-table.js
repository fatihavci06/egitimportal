"use strict";

// Class definition
var KTCustomerViewPaymentTable = function () {

    // Define shared variables
    var datatable;
    var table = document.querySelector('#kt_table_customers_payment');

    // Private functions
    var initCustomerView = function () {
        // Set date data order
        const tableRows = table.querySelectorAll('tbody tr');

        tableRows.forEach(row => {
            const dateRow = row.querySelectorAll('td');
            const realDate = moment(dateRow[2].innerHTML, "DD MMM YYYY, LT").format(); // select date from 4th column in table
            dateRow[2].setAttribute('data-order', realDate);
        });

        // Init datatable --- more info on datatables: https://datatables.net/manual/
        datatable = $(table).DataTable({
            "info": false,
            'order': [], // Set default order
            "pageLength": 5,
            "lengthChange": false,
            'columnDefs': [
                { orderable: false, targets: 2 }, // Disable ordering on column 5 (actions)
            ]
        });
    }

    // Delete customer// Delete customer

    // Public methods
    return {
        init: function () {
            if (!table) {
                return;
            }

            initCustomerView();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTCustomerViewPaymentTable.init();
});