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
                { orderable: false, targets: 4 }, // Disable ordering on column 5 (actions)
            ]
        });
    }

    // Delete customer// Delete customer
var deleteRows = () => {
    // Statik üst element olan tabloya olay dinleyicisi ekle
    $(table).on('click', '[data-kt-customer-table-filter="delete_row"]', function (e) { //
        e.preventDefault();

        // Select parent row
        const parent = e.target.closest('tr');

        // Get customer name
        const customerName = parent.querySelectorAll('td')[0].innerText; //

        const tdElement = parent.querySelector('td[data-file-id]'); // İlk data-file-id'ye sahip td'yi seçer

        if (tdElement) {
            var fileId = tdElement.dataset.fileId; //
        } else {
            console.log('Belirtilen <td> elemanı bulunamadı.'); //
            return; // Exit if element not found
        }

        var activeStatus = parent.querySelectorAll('td')[3].innerText; //

        if (activeStatus === "Aktif") { //
            var activeStatus = "pasif"; //
            var statusVal = 0; //
        } else {
            var activeStatus = "aktif"; //
            var statusVal = 1; //
        }

        // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
        Swal.fire({ //
            text: customerName + " isimli öğrenciyi " + activeStatus + " yapmak istediğinizden emin misiniz?", //
            icon: "warning", //
            showCancelButton: true, //
            buttonsStyling: false, //
            confirmButtonText: "Evet, " + activeStatus + " yap!", //
            cancelButtonText: "Hayır, iptal et", //
            customClass: { //
                confirmButton: "btn fw-bold btn-danger", //
                cancelButton: "btn fw-bold btn-active-light-primary" //
            }
        }).then(function (result) { //
            if (result.value) { //
                $.ajax({ //
                    type: "POST", //
                    url: "includes/update_active_student.inc.php", //
                    data: { //
                        email: fileId, //
                        statusVal: statusVal, //
                    },
                    dataType: "json", //
                    success: function (response) { //
                        if (response.status === "success") { //
                            Swal.fire({ //
                                text: customerName + " adlı öğrenci " + activeStatus + " hale gelmiştir!.", //
                                icon: "success", //
                                buttonsStyling: false, //
                                confirmButtonText: "Tamam, anladım!", //
                                customClass: { //
                                    confirmButton: "btn btn-primary" //
                                }
                            }).then(function (result) { //
                                if (result.isConfirmed) { //
                                    // Yeniden yüklemek veya DataTable'ı güncellemek için
                                     location.reload(); // Tüm sayfayı yeniden yükleyebilir
                                    //datatable.ajax.reload(null, false); // Sadece DataTable'ı yeniden yükler, sayfayı sıfırlamaz
                                }
                            });
                        } else { //
                            Swal.fire({ //
                                text: response.message || "İşlem sırasında bir hata oluştu.", //
                                icon: "error", //
                                buttonsStyling: false, //
                                confirmButtonText: "Tamam, anladım!", //
                                customClass: { //
                                    confirmButton: "btn btn-primary" //
                                }
                            });
                        }
                    },
                    error: function (xhr, status, error) { //
                        console.error("AJAX Error:", status, error, xhr.responseText); //
                        Swal.fire({ //
                            text: "Sunucu ile iletişimde bir hata oluştu.", //
                            icon: "error", //
                            buttonsStyling: false, //
                            confirmButtonText: "Tamam, anladım!", //
                            customClass: { //
                                confirmButton: "btn btn-primary" //
                            }
                        });
                    },
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) { //
                Swal.fire({ //
                    text: customerName + " pasif edilmedi.", //
                    icon: "error", //
                    buttonsStyling: false, //
                    confirmButtonText: "Tamam, anladım", //
                    customClass: { //
                        confirmButton: "btn fw-bold btn-primary", //
                    }
                });
            }
        });
    });
};

    // Public methods
    return {
        init: function () {
            if (!table) {
                return;
            }

            initCustomerView();
            deleteRows();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTCustomerViewPaymentTable.init();
});