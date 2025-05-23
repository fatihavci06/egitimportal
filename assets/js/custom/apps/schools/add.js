"use strict";

// Class definition
var KTModalCustomersAdd = function () {
	var submitButton;
	var cancelButton;
	var closeButton;
	var validator;
	var form;
	var modal;

	
    const maxLength = 11;
	
    const inputTel = $('#telephone');

    // Karakter girişini engelleme
    inputTel.on('input', function () {
        if (this.value.length > maxLength) {
            this.value = this.value.slice(0, maxLength);
        }
    });

    const sayiGirisInput = document.getElementById("telephone");

    sayiGirisInput.addEventListener("input", function (e) {
        const girilenDeger = e.target.value;
        const sadeceRakam = girilenDeger.replace(/[^0-9]/g, ""); // Sadece rakamları al
        e.target.value = sadeceRakam; // Giriş değerini güncelle
    });
	
    const inputTel2 = $('#schoolAdminTelephone');

    // Karakter girişini engelleme
    inputTel2.on('input', function () {
        if (this.value.length > maxLength) {
            this.value = this.value.slice(0, maxLength);
        }
    });

    const sayiGirisInput2 = document.getElementById("schoolAdminTelephone");

    sayiGirisInput2.addEventListener("input", function (e) {
        const girilenDeger2 = e.target.value;
        const sadeceRakam2 = girilenDeger2.replace(/[^0-9]/g, ""); // Sadece rakamları al
        e.target.value = sadeceRakam2; // Giriş değerini güncelle
    });
	
    const inputTel3 = $('#schoolCoordinatorTelephone');

    // Karakter girişini engelleme
    inputTel3.on('input', function () {
        if (this.value.length > maxLength) {
            this.value = this.value.slice(0, maxLength);
        }
    });

    const sayiGirisInput3 = document.getElementById("schoolCoordinatorTelephone");

    sayiGirisInput3.addEventListener("input", function (e) {
        const girilenDeger3 = e.target.value;
        const sadeceRakam3 = girilenDeger3.replace(/[^0-9]/g, ""); // Sadece rakamları al
        e.target.value = sadeceRakam3; // Giriş değerini güncelle
    });

	// Init form inputs
	var handleForm = function () {
		// Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
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
							},
							emailAddress: {
							  message: 'Geçerli bir e-posta adresi girin'
							}
						}
					},
					'schoolAdminEmail': {
						validators: {
							emailAddress: {
							  message: 'Geçerli bir e-posta adresi girin'
							}
						}
					},
					'schoolCoordinatorEmail': {
						validators: {
							emailAddress: {
							  message: 'Geçerli bir e-posta adresi girin'
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
								message: 'Telefon numarası zorunlu'
							}
                        }
                    },
                    'schoolAdminTelephone': {
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
                        }
                    },
                    'schoolCoordinatorTelephone': {
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
			e.preventDefault();

			// Validate form before submit
			if (validator) {
				validator.validate().then(function (status) {
					console.log('validated!');

					if (status == 'Valid') {
						submitButton.setAttribute('data-kt-indicator', 'on');

						// Disable submit button whilst loading
						submitButton.disabled = true;

						setTimeout(function () {
							submitButton.removeAttribute('data-kt-indicator');

							var name = $("#name").val();
							var address = $("#address").val();
							var district = $("#district").val();
							var postcode = $("#postcode").val();
							var city = $("#city").val();
							var email = $("#email").val();
							var telephone = $("#telephone").val();
							var schoolAdminName = $("#schoolAdminName").val();
							var schoolAdminSurname = $("#schoolAdminSurname").val();
							var schoolAdminEmail = $("#schoolAdminEmail").val();
							var schoolAdminTelephone = $("#schoolAdminTelephone").val();
							var schoolCoordinatorName = $("#schoolCoordinatorName").val();
							var schoolCoordinatorSurname = $("#schoolCoordinatorSurname").val();
							var schoolCoordinatorEmail = $("#schoolCoordinatorEmail").val();
							var schoolCoordinatorTelephone = $("#schoolCoordinatorTelephone").val();

							$.ajax({
								type: "POST",
								url: "includes/addschool.inc.php",
								data: {
									name: name,
									address: address,
									district: district,
									postcode: postcode,
									city: city,
									email: email,
									telephone: telephone,
									schoolAdminName: schoolAdminName,
									schoolAdminSurname: schoolAdminSurname,
									schoolAdminEmail: schoolAdminEmail,
									schoolAdminTelephone: schoolAdminTelephone,
									schoolCoordinatorName: schoolCoordinatorName,
									schoolCoordinatorSurname: schoolCoordinatorSurname,
									schoolCoordinatorEmail: schoolCoordinatorEmail,
									schoolCoordinatorTelephone: schoolCoordinatorTelephone
								},
								dataType: "json",
								success: function (response) {
									if (response.status === "success") {

										Swal.fire({
											text: response.message + " adlı okul eklenmiştir!",
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
										text: "Bir sorun oldu!" /*+ xhr.responseText*/,
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
		})
	}

	return {
		// Public functions
		init: function () {
			// Elements
			modal = new bootstrap.Modal(document.querySelector('#kt_modal_add_customer'));

			form = document.querySelector('#kt_modal_add_customer_form');
			submitButton = form.querySelector('#kt_modal_add_customer_submit');
			cancelButton = form.querySelector('#kt_modal_add_customer_cancel');
			closeButton = form.querySelector('#kt_modal_add_customer_close');

			handleForm();
		}
	};
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
	KTModalCustomersAdd.init();
});