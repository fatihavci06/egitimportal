"use strict";

$(document).ready(function () {

	let fieldCount = 0;

	$('#addField').on('click', function () {
		fieldCount++;
		$('#dynamicFields').append(`
                    <div class="input-group mb-2" data-index="${fieldCount}">
                        <input type="text" name="wordWallTitles[]" class="form-control me-2" placeholder="Başlık">
                        <input type="text" name="wordWallUrls[]" class="form-control me-2" placeholder="URL">
                        <button type="button" class="btn btn-danger removeField">Sil</button>
                    </div>
                `);
	});
	$('#dynamicFields').on('click', '.removeField', function () {
		$(this).closest('.input-group').remove();
	});
	tinymce.init({
		selector: '.tinymce-editor',
		// diğer ayarlar...
	});

	$('#files').on('change', function () {
		const files = this.files;
		const container = $('#fileDescriptions');
		container.empty(); // Önceki açıklamaları temizle

		for (let i = 0; i < files.length; i++) {
			const fileName = files[i].name;
			const descriptionField = `
            <div class="mb-3">
                <label for="description_${i}" class="form-label">"${fileName}" dosyası için açıklama:</label>
                <textarea class="form-control" name="descriptions[]" id="description_${i}" rows="2"></textarea>
            </div>
        `;
			container.append(descriptionField);
		}
	});
	$('input[name="secim"]').on('change', function () {
		let selected = $(this).val();

		// Tüm inputları gizle
		$('#videoInput, #fileInput,#primary_image, #textInput, #wordwallInputs').hide();

		// Seçime göre ilgili inputu göster
		if (selected === 'video_link') {
			$('#videoInput').show();
		} else if (selected === 'primary_img') {

			$('#primary_image').show();

		} else if (selected === 'file_path') {
			$('#fileInput').show();
		} else if (selected === 'content') {
			$('#textInput').show();
		} else if (selected === 'wordwall') {
			$('#wordwallInputs').show();
		}
	});
});


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
					'topics': {
						validators: {
							notEmpty: {
								message: 'Konu Seçimi zorunlu'
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
						$('#lessons').select2('destroy');
						$('#lessons').html('<option value="">Ders Yok</option>');
						$('#lessons').select2({ data: data });
						$('#units').select2('destroy');
						$('#units').html('<option value="">Ünite Yok</option>');
						$('#topics').select2('destroy');
						$('#topics').html('<option value="">Konu Yok</option>');
						$('#sub_topics').select2('destroy');
						$('#sub_topics').html('<option value="">Alt Konu Yok</option>');
					} else {
						$('#classes').select2('destroy');
						$('#lessons').select2('destroy');
						$('#lessons').html('<option value="">Ders Yok</option>');
						$('#units').select2('destroy');
						$('#units').html('<option value="">Ünite Yok</option>');
						$('#topics').select2('destroy');
						$('#topics').html('<option value="">Konu Yok</option>');
						$('#sub_topics').select2('destroy');
						$('#sub_topics').html('<option value="">Alt Konu Yok</option>');
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
				data: {
					class: classChoose,
					lesson: lessonsChoose
				},
				dataType: "json",
				success: function (data) {
					// İkinci Select2'nin içeriğini güncelle

					if (data.length > 0) {
						if ($('#units').data('select2')) { // Select2'nin başlatılıp başlatılmadığını kontrol edin
							$('#units').select2('destroy');
						}
						$('#units').html('<option value="">Ünite Seçiniz...</option>'); // Varsayılan yer tutucuya sıfırla
						$('#units').select2({ data: data }); // Yeni veriyle başlat/yeniden başlat
						$('#topics').select2('destroy');
						$('#topics').html('<option value="">Konu Yok</option>');
						$('#sub_topics').select2('destroy');
						$('#sub_topics').html('<option value="">Alt Konu Yok</option>');
					} else {
						$('#lessons').select2('destroy');
						$('#units').select2('destroy');
						$('#units').html('<option value="">Ünite Yok</option>');
						$('#topics').select2('destroy');
						$('#topics').html('<option value="">Konu Yok</option>');
						$('#sub_topics').select2('destroy');
						$('#sub_topics').html('<option value="">Alt Konu Yok</option>');
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


		// Revalidate country field. For more info, plase visit the official plugin site: https://select2.org/
		$(form.querySelector('[name="units"]')).on('change', function () {
			// Revalidate the field when an option is chosen
			validator.revalidateField('classes');
			validator.revalidateField('lessons');
			validator.revalidateField('units');

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
						if ($('#topics').data('select2')) { // Select2'nin başlatılıp başlatılmadığını kontrol edin
							$('#topics').select2('destroy');
						}
						$('#topics').html('<option value="">Konu Seçiniz...</option>'); // Varsayılan yer tutucuya sıfırla
						$('#topics').select2({ data: data });
						$('#sub_topics').select2('destroy');
						$('#sub_topics').html('<option value="">Alt Konu Yok</option>');
					} else {
						$('#topics').select2('destroy');
						$('#topics').html('<option value="">Konu Yok</option>');
						$('#sub_topics').select2('destroy');
						$('#sub_topics').html('<option value="">Alt Konu Yok</option>');
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


		// Revalidate country field. For more info, plase visit the official plugin site: https://select2.org/
		$(form.querySelector('[name="topics"]')).on('change', function () {
			// Revalidate the field when an option is chosen
			validator.revalidateField('classes');
			validator.revalidateField('lessons');
			validator.revalidateField('units');
			validator.revalidateField('topics');

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
						$('#sub_topics').select2({ data: data });
					} else {
						$('#sub_topics').select2('destroy');
						$('#sub_topics').html('<option value="">Alt Konu Yok</option>');
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

		// Action buttons
		submitButton.addEventListener('click', function (e) {
			e.preventDefault();

			// icerik-ekle sayfasının JS kodunun başında, veya init fonksiyonu içinde:
			let redirectUrl = 'icerikler'; // Varsayılan bir dönüş URL'si

			// URL'den 'return_url' parametresini oku
			const urlParams = new URLSearchParams(window.location.search);
			if (urlParams.has('return_url')) {
				// URL kodlanmış olduğu için decodeURIComponent kullanıyoruz
				redirectUrl = decodeURIComponent(urlParams.get('return_url'));
			} else if (document.referrer && document.referrer.includes('icerikler')) {
				// Eğer return_url yoksa ama referrer icerikler sayfası ise onu kullan
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

							let formData = new FormData(form);

							const icerik = tinymce.get('mcontent').getContent();

							$('#dynamicFields .input-group').each(function (index) {
								const title = $(this).find('input[name="wordWallTitles[]"]').val();
								const url = $(this).find('input[name="wordWallUrls[]"]').val();

								formData.append(`wordWallTitles[${index}]`, title);
								formData.append(`wordWallUrls[${index}]`, url);
							});

							formData.append('video_url', $('#video_url').val());

							for (let i = 0; i < files.length; i++) {
								formData.append('file_path[]', files[i]);
							}

							// Açıklamaları da ekle
							$("textarea[name='descriptions[]']").each(function () {
								formData.append('descriptions[]', $(this).val());
							});

							formData.append('icerik', icerik);

							/*var name = $("#name").val();
							var classes = $("#classes").val();
							var lessons = $("#lessons").val();
							var units = $("#units").val();*/

							$.ajax({
								type: "POST",
								url: "includes/addcontent.inc.php",
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
											text: response.message + " içerik eklenmiştir!",
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
								error: function (xhr, status, error, response) {
									Swal.fire({
										text: "Bir sorun oldu!"/* + xhr.responseText*/,
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

