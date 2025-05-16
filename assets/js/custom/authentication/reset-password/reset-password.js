"use strict";

var KTAuthResetPassword = function () {
    var form;
    var submitButton;
    var validator;

    var handleForm = function (e) {
        validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    'email': {
                        validators: {
                            regexp: {
                                regexp: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                                message: 'Değer geçerli bir e-posta adresi değil',
                            },
                            notEmpty: {
                                message: 'E-posta adresi giriniz.'
                            }
                        }
                    }
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',  // comment to enable invalid state icons
                        eleValidClass: '' // comment to enable valid state icons
                    })
                }
            }
        );
    }

    var handleSubmitAjax = function (e) {
        // Handle form submit
        submitButton.addEventListener('click', function (e) {
            // Prevent button default action
            e.preventDefault();

            // Validate form
            validator.validate().then(function (status) {
                if (status == 'Valid') {
                    // Show loading indication
                    submitButton.setAttribute('data-kt-indicator', 'on');

                    // Disable button to avoid multiple click
                    submitButton.disabled = true;

                    // Send AJAX request to the specified endpoint
                    axios.post('includes/reset-password.inc.php', new FormData(form))
                        .then(function (response) {
                            // Check for successful response
                            if (response.data.success) {
                                form.reset();

                                // Show success message popup
                                Swal.fire({
                                    text: response.data.message || "E-postanıza şifre sıfırlama bağlantısı gönderdik.",
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Tamamdır, anladım!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                }).then(function (result) {
                                    if (result.isConfirmed) {
                                        // Check if there's a redirect URL
                                        const redirectUrl = form.getAttribute('data-kt-redirect-url');
                                        if (redirectUrl) {
                                            location.href = redirectUrl;
                                        }
                                    }
                                });
                            } else {
                                // Show error message from server
                                Swal.fire({
                                    text: response.data.message || "Üzgünüz, e-posta yanlış, lütfen tekrar deneyin.",
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Tamamdır, anladım!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                });
                            }
                        })
                        .catch(function (error) {
                            // Handle request errors
                            Swal.fire({
                                text: response.data.message || "Üzgünüz, bazı hatalar tespit edildi, lütfen tekrar deneyin.",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            });
                        })
                        .finally(function () {
                            // Hide loading indication
                            submitButton.removeAttribute('data-kt-indicator');

                            // Enable button
                            submitButton.disabled = false;
                        });
                } else {
                    // Show validation error popup
                    Swal.fire({
                        text: response.data.message || "Üzgünüz, bazı hatalar tespit edildi, lütfen tekrar deneyin.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Tamamdır, anladım!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                }
            });
        });
    }

    // Public Functions
    return {
        // public functions
        init: function () {
            form = document.querySelector('#kt_password_reset_form');
            submitButton = document.querySelector('#kt_password_reset_submit');

            handleForm();
            handleSubmitAjax();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTAuthResetPassword.init();
});