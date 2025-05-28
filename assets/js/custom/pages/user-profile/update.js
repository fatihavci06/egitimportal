"use strict";

var KTModalUpdateCustomer = function () {
    var submitButton;
    var cancelButton;
    var closeButton;
    var validator;
    var form;
    var modal;
    var element;


    var initForm = function () {

        validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    'phone': {
                        validators: {
                            regexp: {
                                regexp: /^0/,
                                message: 'Telefon numarası 0 ile başlamalıdır'
                            },
                            stringLength: {
                                min: 11,
                                max: 11,
                                message: 'Telefon numarası 11 haneli olmalıdır'
                            },
                            notEmpty: {
                                message: 'Telefon Numarası zorunlu'
                            }
                        }
                    },
                    'address': {
                        validators: {
                        }
                    },
                    'district': {
                        validators: {

                        }
                    },
                    'city': {
                        validators: {

                        }
                    },
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    })
                }
            }
        );

        submitButton.addEventListener('click', function (e) {
            e.preventDefault();

            if (validator) {
                validator.validate().then(function (status) {
                    console.log('validated!');

                    if (status == 'Valid') {
                        submitButton.setAttribute('data-kt-indicator', 'on');

                        submitButton.disabled = true;

                        setTimeout(function () {
                            submitButton.removeAttribute('data-kt-indicator');

                            const form = document.getElementById('kt_modal_update_customer_form');

                            var formData = new FormData(form);

                            $.ajax({
                                type: "POST",
                                url: "includes/update_student_info.inc.php",
                                data: formData,
                                contentType: false,
                                processData: false,
                                dataType: "json",
                                success: function (response) {
                                    if (response.status == "success") {

                                        Swal.fire({
                                            text: "Bilgileriniz başarıyla güncellenmiştir!",
                                            icon: "success",
                                            buttonsStyling: false,
                                            confirmButtonText: "Tamam, anladım!",
                                            customClass: {
                                                confirmButton: "btn btn-primary"
                                            }
                                        }).then(function (result) {
                                            if (result.isConfirmed) {
                                                modal.hide();
                                                submitButton.disabled = false;
                                                window.location = form.getAttribute("data-kt-redirect");
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

                                            submitButton.disabled = false;
                                        }
                                    });

                                },
                            });
                        }, 2000);
                    } else {
                        Swal.fire({
                            text: "Üzgünüm, lütfen gerekli alanları doldurun.",
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
        });

        cancelButton.addEventListener('click', function (e) {
            e.preventDefault();

            Swal.fire({
                text: "İptal etmek istediğinizden emin misiniz?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Evet, iptal et!",
                cancelButtonText: "Hayır, geri dön",
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: "btn btn-active-light"
                }
            }).then(function (result) {
                if (result.value) {
                    form.reset(); // Reset form	
                    modal.hide(); // Hide modal				
                } else if (result.dismiss === 'cancel') {
                    Swal.fire({
                        text: "Formunuz iptal edilmedi!.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Tamam, anladım!",
                        customClass: {
                            confirmButton: "btn btn-primary",
                        }
                    });
                }
            });
        });

        closeButton.addEventListener('click', function (e) {
            e.preventDefault();

            Swal.fire({
                text: "İptal etmek istediğinizden emin misiniz??",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Evet, iptal et!",
                cancelButtonText: "Hayır, geri dön",
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: "btn btn-active-light"
                }
            }).then(function (result) {
                if (result.value) {
                    form.reset();
                    modal.hide();
                } else if (result.dismiss === 'cancel') {
                    Swal.fire({
                        text: "Formunuz iptal edilmedi!.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Tamam, anladım!",
                        customClass: {
                            confirmButton: "btn btn-primary",
                        }
                    });
                }
            });
        });

    }

    return {
        init: function () {

            element = document.querySelector('#kt_modal_update_customer');
            modal = new bootstrap.Modal(element);
            form = element.querySelector('#kt_modal_update_customer_form');

            submitButton = form.querySelector('#kt_modal_update_customer_submit');
            cancelButton = form.querySelector('#kt_modal_update_customer_cancel');
            closeButton = element.querySelector('#kt_modal_update_customer_close');

            initForm();
        }
    };
}();

KTUtil.onDOMContentLoaded(function () {
    KTModalUpdateCustomer.init();
});