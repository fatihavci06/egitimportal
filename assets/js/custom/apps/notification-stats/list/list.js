"use strict";

var KTCustomersList = function () {
    var datatable;
    var table;

    var initCustomerList = function () {
        const tableRows = table.querySelectorAll('tbody tr');

        tableRows.forEach(row => {
            const dateRow = row.querySelectorAll('td');
            const realDate = moment(dateRow[2].innerHTML, "DD MMM YYYY, LT").format();
            dateRow[2].setAttribute('data-order', realDate); 
        });

        datatable = $(table).DataTable({
            "info": false,
            "order": [[2, 'asc']], 
            'columnDefs': [
                { orderable: false, targets: 0 }, 
                { orderable: false, targets: 1 }, 
                { 
                    type: 'date', 
                    targets: 2 
                }
            ]
        });

        datatable.on('draw', function () {
            toggleToolbars();
            KTMenu.init && KTMenu.init();
        });
    };

    var handleSearchDatatable = () => {
        const filterSearch = document.querySelector('[data-kt-customer-table-filter="search"]');
        if (!filterSearch) return;

        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    };

    const toggleToolbars = () => {
        const toolbarBase = document.querySelector('[data-kt-customer-table-toolbar="base"]');
        const toolbarSelected = document.querySelector('[data-kt-customer-table-toolbar="selected"]');
        const selectedCount = document.querySelector('[data-kt-customer-table-select="selected_count"]');
        const allCheckboxes = document.querySelectorAll('tbody [type="checkbox"]');

        let count = 0;
        allCheckboxes.forEach(c => { if (c.checked) count++; });

        if (count > 0) {
            selectedCount.innerHTML = count;
            toolbarBase?.classList.add('d-none');
            toolbarSelected?.classList.remove('d-none');
        } else {
            toolbarBase?.classList.remove('d-none');
            toolbarSelected?.classList.add('d-none');
        }
    };

    return {
        init: function () {
            table = document.querySelector('#kt_customers_table');
            if (!table) {
                return;
            }
            initCustomerList();
            handleSearchDatatable();
        }
    };
}();

KTUtil.onDOMContentLoaded(function () {
    KTCustomersList.init();
});