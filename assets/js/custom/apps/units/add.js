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
								message: 'Ünite Adı zorunlu'
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
					'lessons': {
						validators: {
							notEmpty: {
								message: 'Ders Seçimi zorunlu'
							}
						}
					},
					'short_dest': {
						validators: {
							notEmpty: {
								message: 'Kısa Açıklama zorunlu'
							}
						}
					},
					'unit_start_date': {
						validators: {
							notEmpty: {
								message: 'Ünite Başlangıç Tarihi zorunlu'
							}
						}
					},
					'unit_end_date': {
						validators: {
							notEmpty: {
								message: 'Ünite Bitiş Tarihi zorunlu'
							}
						}
					},
					'unit_order': {
						validators: {
							notEmpty: {
								message: 'Ünite Sırası zorunlu'
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
		//validator.revalidateField('country');
		//});


		$(form.querySelector('[name="classes"]')).on('change', function () {
			$('#lessons').select2('destroy');
			$('#lessons').html('<option value="">Ünite Yok</option>');
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
						$('#lessons').select2({ data: data });
					} else {
						$('#lessons').select2('destroy');
						$('#lessons').html('<option value="">Ders Yok</option>');
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

							/*var name = $("#name").val();
							var classes = $("#classes").val();
							var lessons = $("#lessons").val();*/

							$.ajax({
								type: "POST",
								url: "includes/addunit.inc.php",
								data: formData,
								contentType: false,
								processData: false,
								/*data: {
									name: name,
									classes: classes,
									lessons: lessons
								},*/
								dataType: "json",
								success: function (response) {
									if (response.status === "success") {

										Swal.fire({
											text: response.message + " adlı ünite eklenmiştir!",
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
												//window.location = form.getAttribute("data-kt-redirect");
												window.location.reload();
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
		// Select2 seçim dinleyicisi: package_type === 1 ise AJAX isteği at
		$(form.querySelector('[name="lessons"]')).on('select2:select', function (e) {
			const selectedLesson = e.params.data;

			if (selectedLesson.package_type === 1) {
				$.ajax({
					url: 'includes/ajax.php?service=getDevelopmentPackageList',
					type: 'POST',
					data: { lesson_id: selectedLesson.id },
					success: function (response) {
						try {
							const json = typeof response === 'string' ? JSON.parse(response) : response;

							if (json.status === 'success' && Array.isArray(json.data) && json.data.length > 0) {
								let html = `<label class="form-label required">Gelişim Paketi Seç</label>
						<select name="development_package_id[]" id="development_package_id" class="form-select" multiple>`;

								json.data.forEach(pkg => {
									html += `<option value="${pkg.id}">${pkg.name} - ${parseFloat(pkg.price).toFixed(2)}₺</option>`;
								});

								html += `</select>`;

								$('#develeopmentPackage').html(html).show(); // göster

								// Select2 uygula
								$('#development_package_id').select2({
									placeholder: "Gelişim Paketi Seçin",
									allowClear: true
								});
							} else {
								$('#develeopmentPackage').html(`<div class="text-danger">Gelişim paketi bulunamadı.</div>`).show();
							}
						} catch (err) {
							console.error('JSON Parse Hatası:', err);
							$('#develeopmentPackage').html(`<div class="text-danger">Veri işlenemedi.</div>`).show();
						}
					},
					error: function (xhr) {
						Swal.fire({
							text: "İşlem sırasında hata oluştu!",
							icon: "error",
							buttonsStyling: false,
							confirmButtonText: "Tamam, anladım!",
							customClass: {
								confirmButton: "btn btn-primary"
							}
						});
						$('#develeopmentPackage').html('').hide();
					}
				});
			} else {
				// Eğer package_type 1 değilse development package alanını gizle
				$('#develeopmentPackage').html('').hide();
			}



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