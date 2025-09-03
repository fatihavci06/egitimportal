"use strict";

var KTModalCustomersAdd = function () {
	var submitButton;
	var cancelButton;
	var closeButton;
	var validator;
	var form;
	var modal;
	const radioOne = document.getElementById('target_type');
	const divs = document.querySelectorAll('.none-div');


	var handleForm = function () {

		validator = FormValidation.formValidation(
			form,
			{
				fields: {
					title: {
						validators: {
							notEmpty: {
								message: 'Duyuru başlığı zorunlu'
							}
						}
					},
					selection: {
						validators: {
							choice: {
								min: 1,
								max: 1,
								message: 'Lütfen birini seçiniz'
							}
						}
					},
					content: {
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
								url: "includes/addnotification.inc.php",
								data: formData,
								contentType: false,
								processData: false,
								dataType: "json",
								success: function (response) {
									if (response.status == "success") {

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
								error: function (xhr, status, error, response) {
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


		$(form.querySelector('[name="classes"]')).on('change', function () {
			// Revalidate the field when an option is chosen
			validator.revalidateField('classes');

			var classChoose = $("#classes").val();

			console.log("fjowijufewoijoifew")
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
		// $(form.querySelector('[name="topics"]')).on('change', function () {
		// 	// Revalidate the field when an option is chosen
		// 	validator.revalidateField('classes');
		// 	validator.revalidateField('lessons');

		// 	var classChoose = $("#classes").val();
		// 	var lessonsChoose = $("#lessons").val();
		// 	var unitsChoose = $("#units").val();
		// 	var topicsChoose = $("#topics").val();

		// 	// AJAX isteği gönder
		// 	$.ajax({
		// 		allowClear: true,
		// 		type: "POST",
		// 		url: "includes/select_for_subtopic.inc.php",
		// 		data: {
		// 			class: classChoose,
		// 			lesson: lessonsChoose,
		// 			unit: unitsChoose,
		// 			topics: topicsChoose

		// 		},
		// 		dataType: "json",
		// 		success: function (data) {

		// 			if (data.length > 0) {
		// 				$('#subtopics').select2({ data: data });
		// 			} else {
		// 				$('#subtopics').html('<option value="">Konu Yok</option>');
		// 			}

		// 		}, error: function (xhr, status, error, response) {
		// 			Swal.fire({
		// 				text: error,
		// 				icon: "error",
		// 				buttonsStyling: false,
		// 				confirmButtonText: "Tamam, anladım!",
		// 				customClass: {
		// 					confirmButton: "btn btn-primary"
		// 				}
		// 			}).then(function (result) {
		// 				if (result.isConfirmed) {

		// 					// Enable submit button after loading
		// 					submitButton.disabled = false;
		// 				}
		// 			});
		// 			//alert(status + "0");

		// 		}
		// 	});
		// });
	}
	radioOne.addEventListener('change', (event) => {
		divs.forEach(div => {
			div.style.display = 'none';
		});
		const selectedDivId = event.target.value + '-div';
		const selectedDiv = document.getElementById(selectedDivId);
		if (selectedDiv) {
			selectedDiv.style.display = 'block';
		}
	});
	return {
		init: function () {

			modal = new bootstrap.Modal(document.querySelector('#kt_modal_add_customer'));
			form = document.querySelector('#kt_modal_add_customer_form');
			submitButton = form.querySelector('#kt_modal_add_customer_submit');
			cancelButton = form.querySelector('#kt_modal_add_customer_cancel');
			closeButton = form.querySelector('#kt_modal_add_customer_close');

			handleForm();
		}
	};
}();

KTUtil.onDOMContentLoaded(function () {
	KTModalCustomersAdd.init();
});

