"use strict";

// Class definition
var KTModalCustomersAdd = function () {
	var submitButton;
	var solvedButton;
	var validator;
	var form;

	// Init form inputs
	var handleForm = function () {
		// Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
		validator = FormValidation.formValidation(
			
			form,
			{
				fields: {
					'comment': {
						validators: {
							notEmpty: {
								message: 'Açıklama zorunlu'
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
								url: "includes/addtechnicalservicesupportresponse.inc.php",
								data: formData,
								contentType: false,
								processData: false,
								/*data: {
									name: name,
									classes: classes,
									lessons: lessons,
									units: units
								},*/
								dataType: "json",
								success: function (response) {
									if (response.status === "success") {

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
												//modal.hide();

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
										text: "Bir sorun oldu!"  + xhr.responseText,
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

		
		solvedButton.addEventListener('click', function (e) {
			e.preventDefault();

			setTimeout(function () {
				submitButton.removeAttribute('data-kt-indicator');

				const form = document.getElementById('kt_modal_add_customer_form');

				var formData = new FormData(form);

				$.ajax({
					type: "POST",
					url: "includes/technicalservicesupportsolved.inc.php",
					data: formData,
					contentType: false,
					processData: false,
					/*data: {
						name: name,
						classes: classes,
						lessons: lessons,
						units: units
					},*/
					dataType: "json",
					success: function (response) {
						if (response.status === "success") {

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
									//modal.hide();

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
							text: "Bir sorun oldu!"  + xhr.responseText,
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

		});

	}

	return {
		// Public functions
		init: function () {
			// Elements
			//modal = new bootstrap.Modal(document.querySelector('#kt_modal_add_customer'));

			form = document.querySelector('#kt_modal_add_customer_form');
			submitButton = form.querySelector('#kt_modal_add_customer_submit');
			solvedButton = form.querySelector('#solved');

			handleForm();
            initForm();
		}
	};
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
	KTModalCustomersAdd.init();
});