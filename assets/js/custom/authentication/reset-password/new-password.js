"use strict";
// Class Definition
var KTAuthNewPassword = function () {
    // Elements
    var form;
    var submitButton;
    var validator;
    var passwordMeter;
    var token = '';

    var handleForm = function (e) {
        // Extract token from URL
        const urlParams = new URLSearchParams(window.location.search);
        token = urlParams.get('token');
        
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    'password': {
                        validators: {
                            notEmpty: {
                                message: 'Şifre zorunlu'
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
                                    return form.querySelector('[name="password"]').value;
                                },
                                message: 'Şifre ve onay şifre aynı değil'
                            }
                        }
                    },
                    'toc': {
                        validators: {
                            notEmpty: {
                                message: 'Şartlar ve koşulları kabul etmelisiniz'
                            }
                        }
                    }
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger({
                        event: {
                            password: false
                        }
                    }),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',  // comment to enable invalid state icons
                        eleValidClass: '' // comment to enable valid state icons
                    })
                }
            }
        );

        form.querySelector('input[name="password"]').addEventListener('input', function () {
            if (this.value.length > 0) {
                validator.updateFieldStatus('password', 'NotValidated');
            }
        });
    }

    var handleSubmitAjax = function (e) {
        // Handle form submit
        submitButton.addEventListener('click', function (e) {
            // Prevent button default action
            e.preventDefault();

            validator.revalidateField('password');

            // Validate form
            validator.validate().then(function (status) {
                if (status == 'Valid') {
                    // Show loading indication
                    submitButton.setAttribute('data-kt-indicator', 'on');

                    // Disable button to avoid multiple click
                    submitButton.disabled = true;

                    // Prepare data for POST request
                    const password = form.querySelector('[name="password"]').value;
                    const confirmPassword = form.querySelector('[name="confirm-password"]').value;
                    const postData = {
                        token: token,
                        password: password,
                        'confirm-password': confirmPassword
                    };

                    // Send POST request with token and password
                    axios.post("includes/reset-password-complete.inc.php", postData, {
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        }
                    })
                    .then(function (response) {
                        // Debug the response
                    
                    if (response.data && response.data.success) {
                            form.reset();

                            // Show success message
                            Swal.fire({
                                text: "Şifrenizi başarıyla sıfırladınız!",
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Tamam, anladım!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            }).then(function (result) {
                                if (result.isConfirmed) {
                                    const redirectUrl = form.getAttribute('data-kt-redirect-url');
                                    if (redirectUrl) {
                                        location.href = redirectUrl;
                                    }
                                }
                            });
                        } else {
                            // Show error popup
                            Swal.fire({
                                text: response.data.message || "Üzgünüz, isteğiniz işlenirken bir hata oluştu.",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Tamam, anladım!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            });
                        }
                    })
                    .catch(function (error) {
                        Swal.fire({
                            text: "Üzgünüz, bazı hatalar tespit edilmiş gibi görünüyor, lütfen tekrar deneyin.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Tamam, anladım!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                    })
                    .finally(() => {
                        // Hide loading indication
                        submitButton.removeAttribute('data-kt-indicator');

                        // Enable button
                        submitButton.disabled = false;
                    });
                } else {
                    // Show error popup
                    Swal.fire({
                        text: "Üzgünüz, bazı hatalar tespit edilmiş gibi görünüyor, lütfen tekrar deneyin.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Tamam, anladım!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                }
            });
        });
    }

    var validatePassword = function () {
        return (passwordMeter.getScore() > 50);
    }

    // Public Functions
    return {
        // public functions
        init: function () {
            form = document.querySelector('#kt_new_password_form');
            submitButton = document.querySelector('#kt_new_password_submit');
            passwordMeter = KTPasswordMeter.getInstance(form.querySelector('[data-kt-password-meter="true"]'));

            handleForm();
            handleSubmitAjax();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTAuthNewPassword.init();
});
