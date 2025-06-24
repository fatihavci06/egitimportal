"use strict";

// Class definition
var KTSignupGeneral = function () {
    // Elements
    var form;
    var submitButton;
    var validator;
    var couponButton
    //var passwordMeter;

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

    // Handle form
    var handleForm = function (e) {
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    'first-name': {
                        validators: {
                            notEmpty: {
                                message: 'Öğrenci Adı zorunlu'
                            }
                        }
                    },
                    'last-name': {
                        validators: {
                            notEmpty: {
                                message: 'Öğrenci Soyadı zorunlu'
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
                    'username': {
                        validators: {
                            notEmpty: {
                                message: 'Kullanıcı Adı zorunlu'
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
                    'gender': {
                        validators: {
                            notEmpty: {
                                message: 'Cinsiyet zorunlu'
                            }
                        }
                    },
                    'telephone': {
                        validators: {
                            regexp: {
                                regexp: /^05/,
                                message: 'Telefon numarası 05 ile başlamalıdır'
                            },
                            stringLength: {
                                min: 11,
                                max: 11,
                                message: 'Telefon numarası 11 haneli olmalıdır'
                            },
                            notEmpty: {
                                message: 'Telefon Numarası zorunlu'
                            }
                        }
                    },
                    'birth_day': {
                        validators: {
                            notEmpty: {
                                message: 'Doğum Tarihi zorunlu'
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
                    'classes': {
                        validators: {
                            notEmpty: {
                                message: 'Sınıf Seçimi zorunlu'
                            }
                        }
                    },
                    'pack': {
                        validators: {
                            notEmpty: {
                                message: 'Paket Seçimi zorunlu'
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
                    /*'telephone': {
                        validators: {
                            notEmpty: {
                                message: 'Telefon numarası zorunlu'
                            }
                        }
                    },
                    'password': {
                        validators: {
                            notEmpty: {
                                message: 'The password is required'
                            },
                            callback: {
                                message: 'Please enter valid password',
                                callback: function (input) {
                                    if (input.value.length > 0) {
                                        return validatePassword();
                                    }
                                }
                            }
                        }
                    },
                    'confirm-password': {
                        validators: {
                            notEmpty: {
                                message: 'The password confirmation is required'
                            },
                            identical: {
                                compare: function () {
                                    return form.querySelector('[name="password"]').value;
                                },
                                message: 'The password and its confirm are not the same'
                            }
                        }
                    },*/
                    'toc': {
                        validators: {
                            notEmpty: {
                                message: 'KVKK Metnini onaylamanız gerekiyor'
                            }
                        }
                    }
                },
                plugins: {
                    /*trigger: new FormValidation.plugins.Trigger({
                        event: {
                            password: false
                        }
                    }),*/
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',  // comment to enable invalid state icons
                        eleValidClass: '' // comment to enable valid state icons
                    })
                }
            }
        );

        // Handle form submit
        submitButton.addEventListener('click', function (e) {
            e.preventDefault();

            //validator.revalidateField('password');

            validator.validate().then(function (status) {
                if (status == 'Valid') {
                    // Show loading indication
                    submitButton.setAttribute('data-kt-indicator', 'on');

                    // Disable button to avoid multiple click
                    submitButton.disabled = true;

                    // Simulate ajax request
                    setTimeout(function () {
                        // Hide loading indication
                        submitButton.removeAttribute('data-kt-indicator');

                        const form = document.getElementById('kt_sign_up_form');

                        var formData = new FormData(form);

                        // Enable button
                        submitButton.disabled = false;

                        $.ajax({
                            type: "POST",
                            url: "includes/adduser.inc.php",
                            data: formData,
                            contentType: false,
                            processData: false,
                            dataType: "json",
                            success: function (response) {
                                if (response.status === "success") {

                                    var telephone = $("#telephone").val();
                                    var PriceWVat = $("#PriceWVat").attr('value');

                                    var type = response.type;
                                    if (type === "credit_card") {
                                        $.ajax({
                                            type: "POST",
                                            url: "tami-sanal-pos/auth.php",
                                            data: {
                                                fail_callback_url: "http://localhost/lineup_campus/odeme-sonuc-tami",
                                                success_callback_url: "http://localhost/lineup_campus/odeme-sonuc-tami",
                                                telephone: '9' + telephone,
                                                amount: PriceWVat,
                                            },
                                            dataType: "json",
                                            success: function (response) {
                                                if (response.oneTimeToken) {
                                                    window.location.href = 'https://sandbox-portal.tami.com.tr/hostedPaymentPage?token=' + response.oneTimeToken;
                                                } else {
                                                    alert('Bir hata oluştu: Token alınamadı.');
                                                }
                                            },
                                            error: function (xhr, status, error) {
                                                console.error('Hata:', error);
                                                alert('Sunucu ile iletişimde bir hata oluştu.');
                                            }
                                        });
                                        /* window.location.replace("odeme-al.php"); */
                                    } else if (type === "bank_transfer") {
                                        window.location.replace("havale-bilgisi.php");
                                    }
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
                                            modal.hide();

                                            // Enable submit button after loading
                                            submitButton.disabled = false;

                                            // Redirect to customers list page
                                            window.location = form.getAttribute("data-kt-redirect");
                                        }
                                    });
                                } else {
                                    Swal.fire({
                                        html: response.message,
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
                    }, 1500);
                } else {
                    // Show error popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                    Swal.fire({
                        html: "Üzgünüz, bazı hatalar tespit edildi,<br> lütfen tekrar deneyin.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Tamam, anladım!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                }
            });
        });

        // Handle password input
        /* form.querySelector('input[name="password"]').addEventListener('input', function () {
             if (this.value.length > 0) {
                 validator.updateFieldStatus('password', 'NotValidated');
             }
         });*/
    }

    /*var initForm = function (element) {

        // Due date. For more info, please visit the official plugin site: https://flatpickr.js.org/
        var birth_day = $(form.querySelector('[name="birth_day"]'));
        birth_day.flatpickr({
            dateFormat: "d.m.Y",
            maxDate: "today",
            firstDayOfWeek: 0,
        });
    }*/

    $('#classes').change(function () {
        var secilenDeger = $(this).val();

        if (secilenDeger !== "") {
            $.ajax({
                url: 'includes/getclasses.inc.php',
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

                    // Paket seçimi için AJAX isteği

                    $(document).ready(function () {
                        $('input[name="pack"]:radio').change(function () {
                            var secilenPaket = $('input[name="pack"]:checked').val();
                            if (secilenPaket !== "") {

                                $('input[type="radio"][name="payment_type"]').prop('checked', false);
                                $('#moneyTransferInfo').html("");
                                $('#couponInfo').html("");
                                $('#delete_coupon').css('display', 'none');
                                $('#coupon_code').val('');
                                $('#iscash').html('');
                                /* $('#cashdiscount').html(""); */
                                $('input[type="radio"][name="isinstallment"]').prop('checked', false);
                                $('#payment_method').css('display', 'inline');;
                                couponButton.disabled = false;
                                submitButton.disabled = true;

                                $.ajax({
                                    url: 'includes/getpackages.inc.php?islem=packages',
                                    type: 'POST',
                                    data: { secim: secilenPaket },
                                    success: function (response) {
                                        $('#totalPrice').html(response.div);
                                        $('#priceWoDiscount').html(response.total);
                                        $('#subscription_month').html(response.subscription_period);
                                    },
                                    error: function (xhr, status, error) {
                                        console.error("Hata oluştu: " + error);
                                        $('#totalPrice').html("<p>Veri yüklenirken bir hata oluştu.</p>");
                                    }
                                });
                            } else {
                                $('#totalPrice').html("");
                            }
                        });
                    });

                    couponButton = document.querySelector('#apply_coupon');

                    couponButton.addEventListener('click', function (e) {
                        submitButton.disabled = true;
                        const inputElementi = document.getElementById('coupon_code');
                        const couponVal = inputElementi.value;
                        $.ajax({
                            url: 'includes/getpackages.inc.php?islem=coupon',
                            type: 'POST',
                            data: { secim: couponVal },
                            success: function (response) {
                                if (response.status === "success") {
                                    var oldPrice = Number(document.getElementById("PriceWOVat").innerHTML);
                                    var priceWoDiscount = Number(document.getElementById("priceWoDiscount").innerHTML);
                                    var vatPercentage = Number(document.getElementById("vatPercentage").innerHTML);
                                    couponButton.disabled = true;
                                    $('#delete_coupon').css('display', 'inline');
                                    $('input[type="radio"][name="payment_type"]').prop('checked', false);

                                    var discount = response.discount;
                                    var type = response.type;
                                    if (type === "percentage") {
                                        var newPrice = priceWoDiscount - (priceWoDiscount * (discount / 100));
                                        var newPriceWVat = newPrice + (newPrice * (vatPercentage / 100));
                                    } else if (type === "amount") {
                                        var newPrice = priceWoDiscount - discount;
                                        var newPriceWVat = newPrice + (newPrice * (vatPercentage / 100));
                                    }

                                    $('#moneyTransferInfo').html("");
                                    $('#PriceWOVat').html(newPrice);
                                    $('#priceWCoupon').html(newPrice);
                                    $('#PriceWVat').html(newPriceWVat);
                                    $('#couponInfo').html(response.message);
                                    $('#couponCode').html('<input type="hidden" name="coupon_codeDb" value="' + couponVal + '">');
                                    $('input[type="radio"][name="isinstallment"]').prop('checked', false);
                                    /* $('#cashdiscount').html(""); */
                                    $('#iscash').html("");

                                    $('#delete_coupon').click(function () {

                                        submitButton.disabled = true;

                                        $('#moneyTransferInfo').html("");
                                        $('#couponInfo').html("");
                                        $('#delete_coupon').css('display', 'none');
                                        $('#iscash').html('');
                                        $('#coupon_code').val('');
                                        $('input[type="radio"][name="payment_type"]').prop('checked', false);

                                        if ($('#moneyTransferInfo').text().trim() === '') {
                                            $('#PriceWOVat').html(priceWoDiscount.toFixed(2));
                                            var newPriceWVat = priceWoDiscount + (priceWoDiscount * (vatPercentage / 100));
                                            $('#PriceWVat').html(newPriceWVat.toFixed(2));
                                            $('#PriceWVat').attr('value', newPriceWVat.toFixed(2));
                                        }
                                        couponButton.disabled = false;
                                        $('#coupon_code').val('');
                                        $('#PriceWOVat').html(priceWoDiscount);
                                        var newPriceWVat = priceWoDiscount + (priceWoDiscount * (vatPercentage / 100));
                                        $('#PriceWVat').html(newPriceWVat.toFixed(2));
                                        $('#PriceWVat').attr('value', newPriceWVat.toFixed(2));
                                        $('#couponInfo').html("");
                                    });

                                } else {
                                    var oldPrice = Number(document.getElementById("PriceWOVat").innerHTML);
                                    var vatPercentage = Number(document.getElementById("vatPercentage").innerHTML);
                                    $('#priceWoDiscount').html(oldPrice);
                                    $('#priceWCoupon').html(oldPrice);
                                    var newPriceWVat = oldPrice + (oldPrice * (vatPercentage / 100));
                                    $('#PriceWVat').html(newPriceWVat.toFixed(2));
                                    $('#PriceWVat').attr('value', newPriceWVat.toFixed(2));
                                    $('#couponInfo').html(response.message);
                                }
                            },
                            error: function (xhr, status, error) {
                                console.error("Hata oluştu: " + error);
                                $('#couponInfo').html("<p>Veri yüklenirken bir hata oluştu.</p>");
                            }
                        });

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

    $(document).ready(function () {

        $('input[type="radio"][name="payment_type"]').change(function () {
            var vatPercentage = Number(document.getElementById("vatPercentage").innerHTML);
            var oldPrice = Number(document.getElementById("priceWoDiscount").innerHTML);
            var priceWCoupon = Number(document.getElementById("priceWCoupon").innerHTML);
            /* var subscription_month = document.getElementById("subscription_month").innerHTML; */
            if ($(this).val() === '2') {

                submitButton.disabled = false;
                if ($('#couponInfo').text().trim() === '') {
                    $('#PriceWOVat').html(oldPrice.toFixed(2));
                    var newPriceWVat = oldPrice + (oldPrice * (vatPercentage / 100));
                    $('#PriceWVat').html(newPriceWVat.toFixed(2));
                    $('#PriceWVat').attr('value', newPriceWVat.toFixed(2));
                } else {
                    $('#PriceWOVat').html(priceWCoupon.toFixed(2));
                    var newPriceWVat = priceWCoupon + (priceWCoupon * (vatPercentage / 100));
                    $('#PriceWVat').html(newPriceWVat.toFixed(2));
                    $('#PriceWVat').attr('value', newPriceWVat.toFixed(2));
                }
                $('#moneyTransferInfo').html("");
                /* if (subscription_month > 1) {
                    submitButton.disabled = true;
                    $('#iscash').html(`<!--begin::Input group-->
                                    <div class="fv-row mt-10">
                                        <span class="form-check form-check-custom form-check-solid">
                                            <label><input class="form-check-input" type="radio" name="isinstallment" value="1"> Peşin</label>
                                            <label><input class="form-check-input ms-7" type="radio" name="isinstallment" value="2"> Taksitle</label>
                                        </span>
                                    </div>
                                    <!--end::Input group-->`);
                } else {
                    submitButton.disabled = false;
                    $('#iscash').html('');
                } */
                /* $('input[type="radio"][name="isinstallment"]').change(function () {
                    
                    submitButton.disabled = false;
                    if ($(this).val() === '1') {

                        var secilenPaket = $('input[name="pack"]:checked').val();
                        $.ajax({
                            url: 'includes/getpackages.inc.php?islem=noinstallment',
                            type: 'POST',
                            data: { secim: secilenPaket },
                            success: function (response) {
                                if (response.status === "success") {
                                    var discount = response.discount;

                                    if ($('#couponInfo').text().trim() === '') {
                                        var newPrice = oldPrice - (oldPrice * (discount / 100));
                                        $('#Price').html(newPrice);
                                    } else {
                                        var newPrice = priceWCoupon - (priceWCoupon * (discount / 100));
                                        $('#Price').html(newPrice);
                                    }
                                    $('#cashdiscount').html("%" + discount + " peşin ödeme indirimi uygulandı!");
                                } else {
                                    $('#cashdiscount').html("<p>Veri yüklenirken bir hata oluştu.</p>");
                                }
                            },
                            error: function (xhr, status, error) {
                                console.error("Hata oluştu: " + error);
                                $('#cashdiscount').html("<p>Veri yüklenirken bir hata oluştu.</p>");
                            }
                        });

                        //$('#iscash').show();
                    } else {
                        $('#cashdiscount').html("");
                        if ($('#couponInfo').text().trim() === '') {
                            $('#Price').html(oldPrice);
                        } else {
                            $('#Price').html(priceWCoupon);
                        }
                    }
                }); */

            } else {
                var typeVal = "1";

                submitButton.disabled = false;

                $.ajax({
                    url: 'includes/getpackages.inc.php?islem=moneytransfer',
                    type: 'POST',
                    data: { secim: typeVal },
                    success: function (response) {
                        if (response.status === "success") {
                            var discount = response.discount;

                            if ($('#couponInfo').text().trim() === '') {
                                var newPrice = oldPrice - (oldPrice * (discount / 100));
                                $('#PriceWOVat').html(newPrice.toFixed(2));
                                var newPriceWVat = newPrice + (newPrice * (vatPercentage / 100));
                                $('#PriceWVat').html(newPriceWVat.toFixed(2));
                                $('#PriceWVat').attr('value', newPriceWVat.toFixed(2));
                            } else {
                                var newPrice = priceWCoupon - (priceWCoupon * (discount / 100));
                                $('#PriceWOVat').html(newPrice.toFixed(2));
                                var newPriceWVat = newPrice + (newPrice * (vatPercentage / 100));
                                $('#PriceWVat').html(newPriceWVat.toFixed(2));
                                $('#PriceWVat').attr('value', newPriceWVat.toFixed(2));
                            }
                            $('#moneyTransferInfo').html(response.message);
                        } else {
                            $('#moneyTransferInfo').html(response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Hata oluştu: " + error);
                        $('#moneyTransferInfo').html("<p>Veri yüklenirken bir hata oluştu.</p>");
                    }
                });
                $('#iscash').html("");
            }
        });
    });


    // Public functions
    return {
        // Initialization
        init: function () {
            // Elements
            form = document.querySelector('#kt_sign_up_form');
            submitButton = document.querySelector('#kt_sign_up_submit');
            // passwordMeter = KTPasswordMeter.getInstance(form.querySelector('[data-kt-password-meter="true"]'));
            //initForm();

            handleForm();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTSignupGeneral.init();
});
