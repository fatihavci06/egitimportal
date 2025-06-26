"use strict";

var KTModalUpdatePassword = function () {
    var submitButton;
    var cancelButton;
    var closeButton;
    var validator;
    var form;
    var modal;
    var element;
    var passwordMeter;


    var initUpdatePasswordForm = function () {

        validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    'password': {
                        validators: {
                            notEmpty: {
                                message: 'Şifre zorunlu'
                            }
                        }
                    },
                    'new-password': {
                        validators: {
                            notEmpty: {
                                message: 'Yeni Şifre zorunlu'
                            },
                            callback: {
                                message: 'Lütfen geçerli bir şifre girin',
                                callback: function (input) {
                                    if (input.value.length > 0) {
                                        return validatePassword();
                                    }
                                }
                            }
                        }
                    },
                    'confirm-password': {
                        validators: {
                            notEmpty: {
                                message: 'Şifre onayı gerekiyor'
                            },
                            identical: {
                                compare: function () {
                                    return form.querySelector('[name="new-password"]').value;
                                },
                                message: 'Şifre ve onay şifre aynı değil'
                            }
                        }
                    },
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger({
                        event: {
                            password: false
                        }
                    }),
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
            validator.revalidateField('password');

            if (validator) {
                validator.validate().then(function (status) {
                    console.log('validated!');

                    if (status == 'Valid') {
                        submitButton.setAttribute('data-kt-indicator', 'on');

                        submitButton.disabled = true;

                        setTimeout(function () {
                            submitButton.removeAttribute('data-kt-indicator');

                            const form = document.getElementById('kt_modal_update_password_form');

                            var formData = new FormData(form);

                            $.ajax({
                                type: "POST",
                                url: "includes/update_password.inc.php",
                                data: formData,
                                contentType: false,
                                processData: false,
                                dataType: "json",
                                success: function (response) {
                                    if (response.status == "success") {

                                        Swal.fire({
                                            text: response.message,
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
                    passwordMeter.reset();			
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


        form.querySelector('input[name="new-password"]').addEventListener('input', function () {
            if (this.value.length > 0) {
                validator.updateFieldStatus('new-password', 'NotValidated');
            }
        });

    }
    var validatePassword = function () {
        return (passwordMeter.getScore() > 50);
    }

    return {
        init: function () {

            element = document.querySelector('#kt_modal_update_password');
            modal = new bootstrap.Modal(element);
            form = element.querySelector('#kt_modal_update_password_form');
            passwordMeter = KTPasswordMeter.getInstance(form.querySelector('[data-kt-new-password-meter="true"]'));

            submitButton = form.querySelector('#kt_modal_update_password_submit');
            cancelButton = form.querySelector('#kt_modal_update_password_cancel');
            closeButton = element.querySelector('#kt_modal_update_password_close');

            initUpdatePasswordForm();
        }
    };
}();

KTUtil.onDOMContentLoaded(function () {
    KTModalUpdatePassword.init();
});