"use strict";

var KTModalUpdateEmail = function () {
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
                    'email': {
                        validators: {
                            regexp: {
                                regexp: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                                message: 'Geçerli bir e-posta adresi değil',
                            },
                            notEmpty: {
                                message: 'E-posta adresi zorunlu'
                            }
                        }
                    }
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

                            const form = document.getElementById('kt_modal_update_email_form');

                            var formData = new FormData(form);

                            $.ajax({
                                type: "POST",
                                url: "includes/update_email.inc.php",
                                data: formData,
                                contentType: false,
                                processData: false,
                                dataType: "json",
                                success: function (response) {
                                    if (response.status == "success") {
                                        document.getElementById('verification_section').classList.remove('d-none');

                                        Swal.fire({
                                            text: "Doğrulama kodu e-postanıza gönderildi. Lütfen kodu girin.",
                                            icon: "info",
                                            buttonsStyling: false,
                                            confirmButtonText: "Tamam, anladım!",
                                            customClass: {
                                                confirmButton: "btn btn-primary"
                                            }
                                        });

                                        submitButton.disabled = true;
                                    } else {
                                        Swal.fire({
                                            text: response.message,
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
    document.getElementById('verify_code_button').addEventListener('click', function (e) {
        e.preventDefault();
        const code = document.getElementById('verification_code').value;
        submitButton.disabled = true;

        if (code === '') {
            Swal.fire({
                text: "Lütfen doğrulama kodunu girin.",
                icon: "warning",
                confirmButtonText: "Tamam"
            });
            return;
        }

        $.ajax({
            type: "POST",
            url: "includes/verify_email_code.inc.php",
            data: { verification_code: code },
            dataType: "json",
            success: function (res) {
                if (res.status === "success") {
                    Swal.fire({
                        text: "E-posta başarıyla doğrulandı!",
                        icon: "success",
                        confirmButtonText: "Tamam"
                    }).then(() => {
                        modal.hide();
                        window.location = form.getAttribute("data-kt-redirect");
                    });
                } else {
                    Swal.fire({
                        text: res.message,
                        icon: "error",
                        confirmButtonText: "Tamam"
                    });
                }
            },
            error: function () {
                Swal.fire({
                    text: "Doğrulama sırasında bir hata oluştu.",
                    icon: "error",
                    confirmButtonText: "Tamam"
                });
            }
        });
    });
    return {
        init: function () {

            element = document.querySelector('#kt_modal_update_email');
            modal = new bootstrap.Modal(element);
            form = element.querySelector('#kt_modal_update_email_form');

            submitButton = form.querySelector('#kt_modal_update_email_submit');
            cancelButton = form.querySelector('#kt_modal_update_email_cancel');
            closeButton = element.querySelector('#kt_modal_update_email_close');

            initForm();
        }
    };
}();

KTUtil.onDOMContentLoaded(function () {
    KTModalUpdateEmail.init();
});

