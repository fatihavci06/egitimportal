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

    const inputElement = $('#tckn');

    // Karakter girişini engelleme
    inputElement.on('input', function () {
        if (this.value.length > maxLength) {
            this.value = this.value.slice(0, maxLength);
        }
    });
	
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
								message: 'Öğrenci Adı zorunlu'
							}
						}
					},
					'surname': {
						validators: {
							notEmpty: {
								message: 'Öğrenci Soyadı zorunlu'
							}
						}
					},
					'username': {
						validators: {
							notEmpty: {
								message: 'Kullanıcı Adı zorunlu'
							}
						}
					},
					'gender': {
						validators: {
							notEmpty: {
								message: 'Cinsiyet zorunlu'
							}
						}
					},
                    'tckn': {
                        validators: {
                            notEmpty: {
                                message: 'Türkiye Cumuriyeti Kimlik Numarası zorunlu'
                            },
                            stringLength: {
                                min: 11,
                                max: 11,
                                message: 'Türkiye Cumuriyeti Kimlik Numarası 11 haneli olmalıdır'
                            },
                            digits: {
                                message: 'Türkiye Cumuriyeti Kimlik Numarası sadece rakamlardan oluşmalıdır'
                            },
                            callback: {
                                message: 'Geçersiz Türkiye Cumuriyeti Kimlik Numarası',
                                callback: function (input) {
                                    const value = input.value.trim();
                                    if (value.length !== 11 || !/^\d+$/.test(value)) {
                                        return {
                                            valid: false,
                                        };
                                    }

                                    const digits = value.split('').map(Number);
                                    let sumOdd = 0;
                                    let sumEven = 0;
                                    for (let i = 0; i < 9; i++) {
                                        if ((i + 1) % 2 === 1) {
                                            sumOdd += digits[i];
                                        } else {
                                            sumEven += digits[i];
                                        }
                                    }

                                    const digit10 = ((sumOdd * 7) - sumEven) % 10;
                                    if (digit10 !== digits[9]) {
                                        return {
                                            valid: false,
                                        };
                                    }

                                    let sumAll = 0;
                                    for (let i = 0; i < 10; i++) {
                                        sumAll += digits[i];
                                    }
                                    const digit11 = sumAll % 10;
                                    if (digit11 !== digits[10]) {
                                        return {
                                            valid: false,
                                        };
                                    }

                                    return {
                                        valid: true,
                                    };
                                },
                            },
                        },
                    },
					'birthdate': {
						validators: {
							notEmpty: {
								message: 'Doğum Tarihi zorunlu'
							}
						}
					},
                    'email': {
                        validators: {
                            regexp: {
                                regexp: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                                message: 'Geçerli bir e-posta adresi değil',
                            },
                            notEmpty: {
                                message: 'E-posta adresi zorunlu'
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
					'school': {
						validators: {
							notEmpty: {
								message: 'Okul zorunlu'
							}
						}
					},
					'classAdd': {
						validators: {
							notEmpty: {
								message: 'Sınıf zorunlu'
							}
						}
					},
                    'parent-first-name': {
                        validators: {
                            notEmpty: {
                                message: 'Veli Adı zorunlu'
                            }
                        }
                    },
                    'parent-last-name': {
                        validators: {
                            notEmpty: {
                                message: 'Veli Soyadı zorunlu'
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
                    'district': {
                        validators: {
                            notEmpty: {
                                message: 'İlçe zorunlu'
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
                    'secenek': {
                        validators: {
                            notEmpty: {
                                message: 'Öğrenci Türü zorunlu'
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
								url: "includes/addstudent.inc.php",
								data: formData,
								contentType: false,
								processData: false,
								dataType: "json",
								success: function (response) {
									if (response.status === "success") {

										Swal.fire({
											text: response.message + " adlı öğrenci eklenmiştir!",
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

		

    $('#classAdd').change(function () {
        var secilenDeger = $(this).val();

        if (secilenDeger !== "") {
            $.ajax({
                url: 'includes/getclasses.inc.php?from=addstudent',
                type: 'POST',
                data: { secim: secilenDeger },
                success: function (response) {
                    $('#veriAlani').html(response);
                    validator.addField('pack', {
                        validators: {
                            notEmpty: {
                                message: 'Paket Seçimi zorunlu'
                            }
                        }
                    });

                },
                error: function (xhr, status, error) {
                    console.error("Hata oluştu: " + error);
                    $('#veriAlani').html("<p>Veri yüklenirken bir hata oluştu.</p>");
                }
            });
        } else {
            $('#veriAlani').html("");
        }

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