"use strict";

// Class definition
var KTSignupGeneral = function () {
    // Elements
    //var passwordMeter;

    const form = document.getElementById('kt_sign_up_form');



    var initForm = function (element) {

        // Due date. For more info, please visit the official plugin site: https://flatpickr.js.org/
        var birth_day = $(form.querySelector('[name="birth_day"]'));
        birth_day.flatpickr({
            dateFormat: "d.m.Y",
            maxDate: "today",
            firstDayOfWeek: 0,
        });
    }

    $('#classes').change(function () {
        var secilenDeger = $(this).val();

        if (secilenDeger !== "") {
            $.ajax({
                url: 'includes/getclasses.inc.php',
                type: 'POST',
                data: { secim: secilenDeger },
                success: function (response) {
                    $('#veriAlani').html(response);
                    
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

    // Public functions
    return {
        // Initialization
        init: function () {
            // Elements
            // passwordMeter = KTPasswordMeter.getInstance(form.querySelector('[data-kt-password-meter="true"]'));
            initForm();

           
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTSignupGeneral.init();
});
