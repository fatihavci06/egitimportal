"use strict";

var KTCustomersList = function () {

    var datatable;
    var table

    var initCustomerList = function () {
        const tableRows = table.querySelectorAll('tbody tr');

        tableRows.forEach(row => {
            const dateRow = row.querySelectorAll('td');
            if (dateRow.length >= 5 && dateRow[4].innerHTML.trim() !== '') {
                const realDate = moment(dateRow[4].innerHTML, "DD MMM YYYY, LT").format();
                dateRow[4].setAttribute('data-order', realDate);
            }
        });

        datatable = $(table).DataTable({
            "info": false,
            'order': [
                [5, 'asc']
            ]
        });

        datatable.on('draw', function () {
            KTMenu.init();
        });
    }

    var handleSearchDatatable = () => {
        const filterSearch = document.querySelector('[data-kt-customer-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }




    return {
        init: function () {
            table = document.querySelector('#kt_customers_table');

            if (!table) {
                return;
            }

            initCustomerList();
            handleSearchDatatable();
        }
    }
}();

KTUtil.onDOMContentLoaded(function () {
    KTCustomersList.init();
});