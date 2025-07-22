"use strict";

var KTCustomersList = function () {
    var datatable;

    var submitButton;
    var filterStatus;
    var filterClass;
    var filterSchool;
    var table

    var initCustomerList = function () {
        const tableRows = table.querySelectorAll('tbody tr');

        datatable = $(table).DataTable({
            "info": false,
            'order': [],
            'columnDefs': [
            ]
        });

        datatable.on('draw', function () {
            initToggleToolbar();
            toggleToolbars();
            KTMenu.init();
        });
    }

    var handleSearchDatatable = () => {
        const filterSearch = document.querySelector('[data-kt-customer-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            console.log(e.target.value);
            datatable.search(e.target.value).draw();
        });
    }

    var handleFilterDatatable = () => {

        filterStatus = $('[data-kt-customer-table-filter="status"]');
        filterSchool = $('[data-kt-customer-table-filter="school"]');
        filterClass = document.querySelectorAll('[data-kt-customer-table-filter="student_class"] [name="student_class"]');
        const filterButton = document.querySelector('[data-kt-customer-table-filter="filter"]');

        filterButton.addEventListener('click', function () {

            let classValue = '';

            filterClass.forEach(r => {
                if (r.checked) {
                    classValue = r.value;
                }

                if (classValue === 'all') {
                    classValue = '';
                }
            });


            const filterString = classValue;
            console.log(filterString);

            datatable.search(filterString).draw();
        });
    }


    var handleResetForm = () => {
        const resetButton = document.querySelector('[data-kt-customer-table-filter="reset"]');

        resetButton.addEventListener('click', function () {
            filterStatus.val(null).trigger('change');

            filterClass[0].checked = true;

            datatable.search('').draw();
        });
    }

    var initToggleToolbar = () => {

        const checkboxes = table.querySelectorAll('[type="checkbox"]');

        const deleteSelected = document.querySelector('[data-kt-customer-table-select="delete_selected"]');

        checkboxes.forEach(c => {
            c.addEventListener('click', function () {
                setTimeout(function () {
                    toggleToolbars();
                }, 50);
            });
        });

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

    return {
        init: function () {
            table = document.querySelector('#kt_customers_table');

            if (!table) {
                return;
            }

            initCustomerList();
            initToggleToolbar();
            handleSearchDatatable();
            handleFilterDatatable();
            handleResetForm();
        }
    }
}();

KTUtil.onDOMContentLoaded(function () {
    KTCustomersList.init();
});