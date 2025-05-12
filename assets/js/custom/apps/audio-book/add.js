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
		// Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
		validator = FormValidation.formValidation(
			form,
			{
				fields: {
					'name': {
						validators: {
							notEmpty: {
								message: 'Sesli Kitap Adı zorunlu'
							}
						}
					},
					'iframe': {
						validators: {
							notEmpty: {
								message: 'iframe Kodu zorunlu'
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
		//$(form.querySelector('[name="country"]')).on('change', function () {
			// Revalidate the field when an option is chosen
			validator.revalidateField('country');
		//});

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

							/*var photo = $('#photo')[0].files[0];
							var fileName = photo.name;
							var fileSize = photo.size;
							var name = $("#name").val();
							var surname = $("#surname").val();
							var username = $("#username").val();
							var gender = $("#gender").val();
							var birthdate = $("#birthdate").val();
							var email = $("#email").val();
							var telephone = $("#telephone").val();
							var school = $("#school").val();
							var classAdd = $("#classAdd").val();
							var password = $("#password").val();*/

							$.ajax({
								type: "POST",
								url: "includes/addaudio-book.inc.php",
								data: formData,
								contentType: false,
								processData: false,
								dataType: "json",
								success: function (response) {
									if (response.status === "success") {

										Swal.fire({
											text: response.message + " adlı sesli kitap eklenmiştir!",
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
										text: "Bir sorun oldu!" /* + xhr.responseText*/,
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
				confirmButtonText: "Yes, cancel it!",
				cancelButtonText: "No, return",
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
						text: "Your form has not been cancelled!.",
						icon: "error",
						buttonsStyling: false,
						confirmButtonText: "Ok, got it!",
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
				confirmButtonText: "Yes, cancel it!",
				cancelButtonText: "No, return",
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
						text: "Your form has not been cancelled!.",
						icon: "error",
						buttonsStyling: false,
						confirmButtonText: "Ok, got it!",
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