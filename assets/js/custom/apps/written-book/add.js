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
					'description': {
						validators: {
							notEmpty: {
								message: 'Açıklama zorunlu'
							}
						}
					},
					'classes': {
						validators: {
						}
					},
					'lessons': {
						validators: {

						}
					},
					'units': {
						validators: {

						}
					},
					'topics': {
						validators: {

						}
					},
					'subtopics': {
						validators: {

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

							$.ajax({
								type: "POST",
								url: "includes/addwritten-book.inc.php",
								data: formData,
								contentType: false,
								processData: false,
								dataType: "json",
								success: function (response) {
									if (response.status === "success") {

										Swal.fire({
											text: response.message + " adlı kitap eklenmiştir!",
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
										text: "Bir sorun oldu!",
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
						text: "Formunuz iptal edilmedi.",
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
						text: "Formunuz iptal edilmedi.",
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

		$(form.querySelector('[name="classes"]')).on('change', function () {
			// Revalidate the field when an option is chosen
			validator.revalidateField('classes');

			var classChoose = $("#classes").val();


			// AJAX isteği gönder
			$.ajax({
				allowClear: true,
				type: "POST",
				url: "includes/select_for_lesson.inc.php",
				data: {
					class: classChoose
				},
				dataType: "json",
				success: function (data) {
					// İkinci Select2'nin içeriğini güncelle

					if (data.length > 0) {
						$('#lessons').html('<option value="">Ders Yok</option>');
						$('#lessons').select2({ data: data });
						$('#units').html('<option value="">Ünite Yok</option>');
						$('#topics').html('<option value="">Konu Yok</option>');
					} else {
						$('#lessons').html('<option value="">Ders Yok</option>');
						$('#units').html('<option value="">Ünite Yok</option>');
						$('#topics').html('<option value="">Konu Yok</option>');
					}

				}, error: function (xhr, status, error, response) {
					Swal.fire({
						text: error.responseText + ' ' + xhr.responseText,
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

				}
			});
		});
		$(form.querySelector('[name="lessons"]')).on('change', function () {
			// Revalidate the field when an option is chosen
			validator.revalidateField('classes');
			validator.revalidateField('lessons');

			var classChoose = $("#classes").val();
			var lessonsChoose = $("#lessons").val();


			// AJAX isteği gönder
			$.ajax({
				allowClear: true,
				type: "POST",
				url: "includes/select_for_unit.inc.php",
				data: {
					class: classChoose,
					lesson: lessonsChoose
				},
				dataType: "json",
				success: function (data) {
					// İkinci Select2'nin içeriğini güncelle

					if (data.length > 0) {
						$('#units').select2({ data: data });
					} else {
						$('#units').html('<option value="">Ünite Yok</option>');
						$('#topics').html('<option value="">Konu Yok</option>');
					}

				}, error: function (xhr, status, error, response) {
					Swal.fire({
						text: error,
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

				}
			});
		});
		$(form.querySelector('[name="units"]')).on('change', function () {
			// Revalidate the field when an option is chosen
			validator.revalidateField('classes');
			validator.revalidateField('lessons');

			var classChoose = $("#classes").val();
			var lessonsChoose = $("#lessons").val();
			var unitsChoose = $("#units").val();


			// AJAX isteği gönder
			$.ajax({
				allowClear: true,
				type: "POST",
				url: "includes/select_for_topic.inc.php",
				data: {
					class: classChoose,
					lesson: lessonsChoose,
					unit: unitsChoose
				},
				dataType: "json",
				success: function (data) {
					// İkinci Select2'nin içeriğini güncelle

					if (data.length > 0) {
						$('#topics').select2({ data: data });
					} else {
						$('#topics').html('<option value="">Konu Yok</option>');
					}

				}, error: function (xhr, status, error, response) {
					Swal.fire({
						text: error,
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

				}
			});
		});
		$(form.querySelector('[name="topics"]')).on('change', function () {
			// Revalidate the field when an option is chosen
			validator.revalidateField('classes');
			validator.revalidateField('lessons');

			var classChoose = $("#classes").val();
			var lessonsChoose = $("#lessons").val();
			var unitsChoose = $("#units").val();
			var topicsChoose = $("#topics").val();

			// AJAX isteği gönder
			$.ajax({
				allowClear: true,
				type: "POST",
				url: "includes/select_for_subtopic.inc.php",
				data: {
					class: classChoose,
					lesson: lessonsChoose,
					unit: unitsChoose,
					topics: topicsChoose

				},
				dataType: "json",
				success: function (data) {
					// İkinci Select2'nin içeriğini güncelle

					if (data.length > 0) {
						$('#subtopics').select2({ data: data });
					} else {
						$('#subtopics').html('<option value="">Konu Yok</option>');
					}

				}, error: function (xhr, status, error, response) {
					Swal.fire({
						text: error,
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

				}
			});
		});
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