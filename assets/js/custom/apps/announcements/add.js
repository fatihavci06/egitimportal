"use strict";

// Class definition
var KTModalCustomersAdd = function () {
	var submitButton;
	var cancelButton;
	var closeButton;
	var validator;
	var form;
	var modal;
	// Init form inputs
	var handleForm = function () {
		const usersDiv = $('#users-div');
		const classesDiv = $('#classes-div');
	

		// Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
		validator = FormValidation.formValidation(
			form,
			{
				fields: {
					name: {
						validators: {
							notEmpty: {
								message: 'Duyuru Adı zorunlu'
							}
						}
					},
					secim: {
						validators: {
							choice: {
								min: 1,
								max: 1,
								message: 'Lütfen birini seçiniz'
							}
						}
					},
					/*roles: {
						validators: {
							callback: {
								message: 'Lütfen bir kullanıcı grubu seçin.',
								callback: function(input) {
									return ($('input[name="secim"]:checked').val() === 'users' && usersDiv.is(':visible'))
										? input.value() !== ''
										: true;
								}
							}
						}
					},
					classes: {
						validators: {
							callback: {
								message: 'Lütfen bir sınıf seçin.',
								callback: function(input) {
									return ($('input[name="secim"]:checked').val() === 'classes' && classesDiv.is(':visible'))
										? input.value() !== ''
										: true;
								}
							}
						}
					},*/
					announcement: {
						validators: {
							notEmpty: {
								message: 'Duyuru metni zorunlu'
							}
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

							const form = document.getElementById('kt_modal_add_customer_form');

							var formData = new FormData(form);

							$.ajax({
								type: "POST",
								url: "includes/addannouncement.inc.php",
								data: formData,
								contentType: false,
								processData: false,
								dataType: "json",
								success: function (response) {
									if (response.status === "success") {

										Swal.fire({
											text: response.message + " adlı duyuru eklenmiştir!",
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