"use strict";

// Class definition
var KTModalUpdateCustomer = function () {
    var element;
    var submitButton;
    var cancelButton;
    var closeButton;
	var validator;
    var form;
    var modal;

    // Init form inputs
    var initForm = function () {

        validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    'name': {
                        validators: {
                            notEmpty: {
                                message: 'Okul Adı zorunlu'
                            }
                        }
                    },
                    'email': {
                        validators: {
                            notEmpty: {
                                message: 'E-posta adresi zorunlu'
                            }
                        }
                    },
                    'city': {
                        validators: {
                            notEmpty: {
                                message: 'Şehir zorunlu'
                            }
                        }
                    },
                    'address': {
                        validators: {
                            notEmpty: {
                                message: 'Adres zorunlu'
                            }
                        }
                    },
                    'telephone': {
                        validators: {
                            notEmpty: {
                                message: 'Telefon Numarası zorunlu'
                            }
                        }
                    },
                    'district': {
                        validators: {
                            notEmpty: {
                                message: 'İlçe zorunlu'
                            }
                        }
                    },
                    /*'postcode': {
                        validators: {
                            notEmpty: {
                                message: 'Postcode is required'
                            }
                        }
                    }*/
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

        // Revalidate country field. For more info, plase visit the official plugin site: https://select2.org/
        $(form.querySelector('[name="country"]')).on('change', function () {
            // Revalidate the field when an option is chosen
            validator.revalidateField('country');
        });

        // Action buttons
        submitButton.addEventListener('click', function (e) {
            // Prevent default button action
            e.preventDefault();

            // Validate form before submit
            if (validator) {
                validator.validate().then(function (status) {
                    console.log('validated!');

                    if (status == 'Valid') {

                        // Show loading indication
                        submitButton.setAttribute('data-kt-indicator', 'on');

                        // Disable submit button whilst loading
                        submitButton.disabled = true;

                        // Simulate form submission
                        setTimeout(function () {
                            // Simulate form submission
                            submitButton.removeAttribute('data-kt-indicator');

                            var name = $("#name").val();
                            var schId = $("#schId").val();
                            var old_slug = $("#old_slug").val();
                            var address = $("#address").val();
                            var district = $("#district").val();
                            var postcode = $("#postcode").val();
                            var city = $("#city").val();
                            var email = $("#email").val();
                            var email_old = $("#email_old").val();
                            var telephone = $("#telephone").val();
                            
                            var schoolAdminName = $("#schoolAdminName").val();
                            var schoolAdminSurname = $("#schoolAdminSurname").val();
                            var schoolAdminEmail = $("#schoolAdminEmail").val();
                            var schoolAdminTelephone = $("#schoolAdminTelephone").val();
                            var old_admin_email = $("#old_admin_email").val();
                            var old_admin_name = $("#old_admin_name").val();
                            var old_admin_surname = $("#old_admin_surname").val();

                            var schoolCoordinatorName = $("#schoolCoordinatorName").val();
                            var schoolCoordinatorSurname = $("#schoolCoordinatorSurname").val();
                            var schoolCoordinatorEmail = $("#schoolCoordinatorEmail").val();
                            var schoolCoordinatorTelephone = $("#schoolCoordinatorTelephone").val();
                            var old_coord_email = $("#old_coord_email").val();
                            var old_coord_name = $("#old_coord_name").val();
                            var old_coord_surname = $("#old_coord_surname").val();

                            $.ajax({
                                type: "POST",
                                url: "includes/updateschool.inc.php",
                                data: {
                                    name: name,
                                    schId: schId,
                                    old_slug: old_slug,
                                    address: address,
                                    district: district,
                                    postcode: postcode,
                                    city: city,
                                    email: email,
                                    email_old: email_old,
                                    telephone: telephone,
                                    schoolAdminName: schoolAdminName,
                                    schoolAdminSurname: schoolAdminSurname,
                                    schoolAdminEmail: schoolAdminEmail,
                                    schoolAdminTelephone: schoolAdminTelephone,
                                    schoolCoordinatorName: schoolCoordinatorName,
                                    schoolCoordinatorSurname: schoolCoordinatorSurname,
                                    schoolCoordinatorEmail: schoolCoordinatorEmail,
                                    schoolCoordinatorTelephone: schoolCoordinatorTelephone,
                                    old_admin_email: old_admin_email,
                                    old_coord_email: old_coord_email,
                                    old_admin_name: old_admin_name,
                                    old_admin_surname: old_admin_surname,
                                    old_coord_name: old_coord_name,
                                    old_coord_surname: old_coord_surname
                                },
                                dataType: "json",
                                success: function (response) {
                                    if (response.status === "success") {

                                        Swal.fire({
                                            text: response.message + " adlı okul güncellenmiştir!",
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
                                error: function (xhr, status, error, response) {
                                    Swal.fire({
                                        text: error + "Bir sorun oldu!",
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

                            //form.submit(); // Submit form
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
    }

    return {
        // Public functions
        init: function () {
            // Elements
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

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTModalUpdateCustomer.init();
});