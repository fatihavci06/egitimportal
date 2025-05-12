"use strict";

// Class definition
var KTSignupGeneral = function () {
    // Elements
    var form;
    var submitButton;
    var validator;
    //var passwordMeter;

    // Handle form
    var handleForm = function (e) {
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    'first-name': {
                        validators: {
                            notEmpty: {
                                message: 'Öğrenci Adı zorunlu'
                            }
                        }
                    },
                    'last-name': {
                        validators: {
                            notEmpty: {
                                message: 'Öğrenci Soyadı zorunlu'
                            }
                        }
                    },
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
                    },
                    'username': {
                        validators: {
                            notEmpty: {
                                message: 'Kullanıcı Adı zorunlu'
                            }
                        }
                    },
                    'tckn': {
                        validators: {
                            notEmpty: {
                                message: 'Türkiye Cumuriyeti Kimlik Numarası zorunlu'
                            },
                            stringLength: {
                                min: 11,
                                max: 11,
                                message: 'Türkiye Cumuriyeti Kimlik Numarası 11 haneli olmalıdır'
                            },
                            digits: {
                                message: 'Türkiye Cumuriyeti Kimlik Numarası sadece rakamlardan oluşmalıdır'
                            },
                            callback: {
                                message: 'Geçersiz Türkiye Cumuriyeti Kimlik Numarası',
                                callback: function (input) {
                                    const value = input.value.trim();
                                    if (value.length !== 11 || !/^\d+$/.test(value)) {
                                        return {
                                            valid: false,
                                        };
                                    }

                                    const digits = value.split('').map(Number);
                                    let sumOdd = 0;
                                    let sumEven = 0;
                                    for (let i = 0; i < 9; i++) {
                                        if ((i + 1) % 2 === 1) {
                                            sumOdd += digits[i];
                                        } else {
                                            sumEven += digits[i];
                                        }
                                    }

                                    const digit10 = ((sumOdd * 7) - sumEven) % 10;
                                    if (digit10 !== digits[9]) {
                                        return {
                                            valid: false,
                                        };
                                    }

                                    let sumAll = 0;
                                    for (let i = 0; i < 10; i++) {
                                        sumAll += digits[i];
                                    }
                                    const digit11 = sumAll % 10;
                                    if (digit11 !== digits[10]) {
                                        return {
                                            valid: false,
                                        };
                                    }

                                    return {
                                        valid: true,
                                    };
                                },
                            },
                        },
                    },
                    'gender': {
                        validators: {
                            notEmpty: {
                                message: 'Cinsiyet zorunlu'
                            }
                        }
                    },
                    'birth_day': {
                        validators: {
                            notEmpty: {
                                message: 'Doğum Tarihi zorunlu'
                            }
                        }
                    },
                    'parent-first-name': {
                        validators: {
                            notEmpty: {
                                message: 'Veli Adı zorunlu'
                            }
                        }
                    },
                    'parent-last-name': {
                        validators: {
                            notEmpty: {
                                message: 'Veli Soyadı zorunlu'
                            }
                        }
                    },
                    'classes': {
                        validators: {
                            notEmpty: {
                                message: 'Sınıf Seçimi zorunlu'
                            }
                        }
                    },
                    'pack': {
                        validators: {
                            notEmpty: {
                                message: 'Paket Seçimi zorunlu'
                            }
                        }
                    },
                    /*'password': {
                        validators: {
                            notEmpty: {
                                message: 'The password is required'
                            },
                            callback: {
                                message: 'Please enter valid password',
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
                                message: 'The password confirmation is required'
                            },
                            identical: {
                                compare: function () {
                                    return form.querySelector('[name="password"]').value;
                                },
                                message: 'The password and its confirm are not the same'
                            }
                        }
                    },*/
                    'toc': {
                        validators: {
                            notEmpty: {
                                message: 'KVKK Metnini onaylamanız gerekiyor'
                            }
                        }
                    }
                },
                plugins: {
                    /*trigger: new FormValidation.plugins.Trigger({
                        event: {
                            password: false
                        }
                    }),*/
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',  // comment to enable invalid state icons
                        eleValidClass: '' // comment to enable valid state icons
                    })
                }
            }
        );

        // Handle form submit
        submitButton.addEventListener('click', function (e) {
            e.preventDefault();

            //validator.revalidateField('password');

            validator.validate().then(function (status) {
                if (status == 'Valid') {
                    // Show loading indication
                    submitButton.setAttribute('data-kt-indicator', 'on');

                    // Disable button to avoid multiple click
                    submitButton.disabled = true;

                    // Simulate ajax request
                    setTimeout(function () {
                        // Hide loading indication
                        submitButton.removeAttribute('data-kt-indicator');

                        const form = document.getElementById('kt_sign_up_form');

                        var formData = new FormData(form);

                        // Enable button
                        submitButton.disabled = false;

                        $.ajax({
                            type: "POST",
                            url: "includes/adduser.inc.php",
                            data: formData,
                            contentType: false,
                            processData: false,
                            dataType: "json",
                            success: function (response) {
                                if (response.status === "success") {
                                    window.location.replace("odeme-al.php");
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
                                            // Hide modal
                                            modal.hide();

                                            // Enable submit button after loading
                                            submitButton.disabled = false;

                                            // Redirect to customers list page
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

                                            // Enable submit button after loading
                                            submitButton.disabled = false;
                                        }
                                    });
                                }
                            },
                            error: function(xhr, status, error, response) {
                                Swal.fire({
                                    text: "Bir sorun oldu!" + xhr.responseText,
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Tamam, anladım!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                }).then(function (result) {
                                    if (result.isConfirmed) {

                                        // Enable submit button after loading
                                        submitButton.disabled = false;
                                    }
                                });
                                //alert(status + "0");

                            },
                        });
                    }, 1500);
                } else {
                    // Show error popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                    Swal.fire({
                        text: "Üzgünüz, bazı hatalar tespit edildi, lütfen tekrar deneyin.",
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

        // Handle password input
        /* form.querySelector('input[name="password"]').addEventListener('input', function () {
             if (this.value.length > 0) {
                 validator.updateFieldStatus('password', 'NotValidated');
             }
         });*/
    }

    var initForm = function (element) {

        // Due date. For more info, please visit the official plugin site: https://flatpickr.js.org/
        var birth_day = $(form.querySelector('[name="birth_day"]'));
        birth_day.flatpickr({
            dateFormat: "d.m.Y",
            maxDate: "today",
            firstDayOfWeek: 0,
        });
    }

    $('#classes').change(function () {
        var secilenDeger = $(this).val();

        if (secilenDeger !== "") {
            $.ajax({
                url: 'includes/getclasses.inc.php',
                type: 'POST',
                data: { secim: secilenDeger },
                success: function (response) {
                    $('#veriAlani').html(response);
                    validator.addField('pack', {
                        validators: {
                            notEmpty: {
                                message: 'Paket Seçimi zorunlu'
                            }
                        }
                    });
                },
                error: function (xhr, status, error) {
                    console.error("Hata oluştu: " + error);
                    $('#veriAlani').html("<p>Veri yüklenirken bir hata oluştu.</p>");
                }
            });
        } else {
            $('#veriAlani').html("");
        }
    });

    // Public functions
    return {
        // Initialization
        init: function () {
            // Elements
            form = document.querySelector('#kt_sign_up_form');
            submitButton = document.querySelector('#kt_sign_up_submit');
            // passwordMeter = KTPasswordMeter.getInstance(form.querySelector('[data-kt-password-meter="true"]'));
            initForm();
            
            handleForm();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTSignupGeneral.init();
});
