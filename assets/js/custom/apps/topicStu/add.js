"use strict";

// Class definition
var KTModalCustomersAdd = function () {
	var submitButton;
	var validator;
	var form;

	// Init form inputs
	var handleForm = function () {
		// Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
		validator = FormValidation.formValidation(
			form,
			{
				fields: {
					'testcevap[]': {
						validators: {
							choice: {
								min: 1,
								message: 'Lütfen en az bir soruyu cevaplayın'
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


		const checkboxes = document.querySelectorAll('.form-check-input');

		checkboxes.forEach(checkbox => {
			checkbox.addEventListener('change', function () {
				// Checkbox'ın işaretli olup olmadığını kontrol et
				if (this.checked) {
					// İşaretliyse, bir sonraki element kardeşini (input) bul ve değerini değiştir
					const nextInput = this.nextElementSibling;
					if (nextInput && nextInput.classList.contains('nextInput')) {
						nextInput.remove();
					}
				} else {
					// İşaretli değilse, bir sonraki element kardeşini (input) bul ve değerini varsayılana döndür
					const nextInput = this.nextElementSibling;
					if (nextInput && nextInput.classList.contains('nextInput')) {
						// İsterseniz buraya farklı bir değer veya orijinal değeri atayabilirsiniz.
						// Şu anki durumda boş bırakıyorum.
						nextInput.value = "1";
					}
				}
			});
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

						const test_id = document.querySelector('input[name="test_id"]');
						const question_id = document.querySelector('input[name="question_id"]');

						if (test_id) {
							var urlsi = "includes/addtestanswers.inc.php";
						}
						if (question_id) {
							var urlsi = "includes/addsquestions.inc.php";
						}

						setTimeout(function () {
							submitButton.removeAttribute('data-kt-indicator');

							const form = document.getElementById('kt_modal_add_customer_form');

							var formData = new FormData(form);

							$.ajax({
								type: "POST",
								url: urlsi,
								data: formData,
								contentType: false,
								processData: false,
								dataType: "json",
								success: function (response) {
									if (response.status === "success") {

										Swal.fire({
											text: /*response.message + */"Cevaplar gönderilmiştir.",
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
								error: function (xhr, status, error, response) {
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
	}

	return {
		// Public functions
		init: function () {
			// Elements

			form = document.querySelector('#kt_modal_add_customer_form');
			submitButton = form.querySelector('#kt_modal_add_customer_submit');

			handleForm();
		}
	};
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
	KTModalCustomersAdd.init();
});