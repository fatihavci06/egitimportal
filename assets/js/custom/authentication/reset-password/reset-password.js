"use strict";

var KTAuthResetPassword = function () {
    var form;
    var submitButton;
    var validator;
    var emailInput;

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

    // Function to submit the form
    var submitForm = function() {
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
                            text: "Üzgünüz, bazı hatalar tespit edildi, lütfen tekrar deneyin.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Tamamdır, anladım!",
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
                    text: "Lütfen geçerli bir e-posta adresi yazınız",
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Tamamdır, anladım!",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });
            }
        });
    }

    var handleSubmitAjax = function (e) {
        // Handle form submit button click
        submitButton.addEventListener('click', function (e) {
            // Prevent button default action
            e.preventDefault();
            
            // Submit the form
            submitForm();
        });
        
        // Handle Enter key press in email input
        emailInput.addEventListener('keydown', function(e) {
            // Check if Enter key was pressed (key code 13)
            if (e.key === 'Enter' || e.keyCode === 13) {
                // Prevent default form submission
                e.preventDefault();
                
                // Submit the form
                submitForm();
            }
        });
    }

    // Public Functions
    return {
        // public functions
        init: function () {
            form = document.querySelector('#kt_password_reset_form');
            submitButton = document.querySelector('#kt_password_reset_submit');
            emailInput = form.querySelector('input[name="email"]');

            handleForm();
            handleSubmitAjax();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTAuthResetPassword.init();
});