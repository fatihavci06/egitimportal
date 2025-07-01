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
					'name': {
						validators: {
							notEmpty: {
								message: 'Konu Adı zorunlu'
							}
						}
					},
					'short_desc': {
						validators: {
							notEmpty: {
								message: 'Kısa Açıklama zorunlu'
							}
						}
					},
					'secim': {
						validators: {
							notEmpty: {
								message: 'Seçim Yapmak zorunlu'
							}
						}
					},
					'start_date': {
						validators: {
							notEmpty: {
								message: 'Konu Başlangıç Tarihi zorunlu'
							}
						}
					},
					'end_date': {
						validators: {
							notEmpty: {
								message: 'Konu Bitiş Tarihi zorunlu'
							}
						}
					},
					'order': {
						validators: {
							notEmpty: {
								message: 'Konu Sırası zorunlu'
							}
						}
					},
					/*'content': {
						validators: {
							notEmpty: {
								message: 'Konu İçeriği zorunlu'
							}
						}
					},
					'video_url': {
						validators: {
							notEmpty: {
								message: 'Video Linki zorunlu'
							}
						}
					},*/
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
					'units': {
						validators: {
							notEmpty: {
								message: 'Ünite Seçimi zorunlu'
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


		$(form.querySelector('[name="classes"]')).on('change', function () {
			// Revalidate the field when an option is chosen
			validator.revalidateField('classes');
			
			var classChoose = $("#classes").val();
			

			  // AJAX isteği gönder
			  $.ajax({
				allowClear: true,
				type: "POST",
				url: "includes/select_for_lesson.inc.php",
				data: { class: classChoose
				 },
				dataType: "json",
				success: function(data) {
				  // İkinci Select2'nin içeriğini güncelle

				  if (data.length > 0) {
					$('#lessons').select2('destroy');
					$('#lessons').html('<option value="">Ders Yok</option>');
					$('#lessons').select2({ data: data });
					$('#units').select2('destroy');
					$('#units').html('<option value="">Ünite Yok</option>');
				  } else {
					$('#classes').select2('destroy');
					$('#lessons').select2('destroy');
					$('#lessons').html('<option value="">Ders Yok</option>');
					$('#units').select2('destroy');
					$('#units').html('<option value="">Ünite Yok</option>');
				  }

				},error: function(xhr, status, error, response) {
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
		

		// Revalidate country field. For more info, plase visit the official plugin site: https://select2.org/
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
				data: { class: classChoose,
						lesson: lessonsChoose
				 },
				dataType: "json",
				success: function(data) {
				  // İkinci Select2'nin içeriğini güncelle

				  if (data.length > 0) {
					if ($('#units').data('select2')) { // Select2'nin başlatılıp başlatılmadığını kontrol edin
							$('#units').select2('destroy');
					}
					$('#units').html('<option value="">Ünite Seçiniz...</option>'); // Varsayılan yer tutucuya sıfırla
					$('#units').select2({ data: data });
				  } else {
					$('#units').select2('destroy');
					$('#units').html('<option value="">Ünite Yok</option>');
				  }

				},error: function(xhr, status, error, response) {
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

		// Action buttons
		submitButton.addEventListener('click', function (e) {
			e.preventDefault();

			// icerik-ekle sayfasının JS kodunun başında, veya init fonksiyonu içinde:
			let redirectUrl = 'alt-konular'; // Varsayılan bir dönüş URL'si

			// URL'den 'return_url' parametresini oku
			const urlParams = new URLSearchParams(window.location.search);
			if (urlParams.has('return_url')) {
				// URL kodlanmış olduğu için decodeURIComponent kullanıyoruz
				redirectUrl = decodeURIComponent(urlParams.get('return_url'));
			} else if (document.referrer && document.referrer.includes('alt-konular')) {
				// Eğer return_url yoksa ama referrer alt-konular sayfası ise onu kullan
				// (Bu bir yedek olabilir, ama return_url daha güvenilirdir.)
				redirectUrl = document.referrer;
			}

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
							var lessons = $("#lessons").val();
							var units = $("#units").val();*/

							$.ajax({
								type: "POST",
								url: "includes/addtopic.inc.php",
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
											text: response.message + " Konu eklenmiştir!",
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
												/* window.location = form.getAttribute("data-kt-redirect"); */

												window.location.href = redirectUrl;
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

	}

	return {
		// Public functions
		init: function () {
			// Elements
			//modal = new bootstrap.Modal(document.querySelector('#kt_modal_add_customer'));

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