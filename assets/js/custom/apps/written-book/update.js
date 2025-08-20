"use strict";

// Class definition
var KTModalUpdateCustomer = function () {
    var submitButton;
    var cancelButton;
    var closeButton;
    var validator;
    var form;
    var modal;
    var element;

    // Init form inputs
    var initForm = function () {

        validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    'name': {
                        validators: {
                            notEmpty: {
                                message: 'Kitap Adı zorunlu'
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

        submitButton.addEventListener('click', function (e) {
            e.preventDefault();

            if (validator) {
                validator.validate().then(function (status) {
                    console.log('validated!');

                    if (status == 'Valid') {
                        submitButton.setAttribute('data-kt-indicator', 'on');

                        submitButton.disabled = true;

                        setTimeout(function () {
                            submitButton.removeAttribute('data-kt-indicator');

                            const form = document.getElementById('kt_modal_update_customer_form');

                            var formData = new FormData(form);

                            $.ajax({
                                type: "POST",
                                url: "includes/update_writtenbook.inc.php",
                                data: formData,
                                contentType: false,
                                processData: false,
                                dataType: "json",
                                success: function (response) {
                                    if (response.status == "success") {

                                        Swal.fire({
                                            text: response.message + " adlı kitap güncellenmiştir!",
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
                text: "İptal etmek istediğinizden emin misiniz??",
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

        function updateSelect2(selector, data, emptyText) {
            const $element = $(selector);
            if (data.length > 0) {
                $element.select2({ data });
            } else {
                $element.html(`<option value="">${emptyText}</option>`);
            }
        }

        function updateSelectWhenChange(selector, data, emptyText) {
            const $element = $(selector);

            // Clear existing options
            $element.empty();

            if (data.length > 0) {
                // Add new options from data
                $element.select2({
                    data: data,
                    placeholder: emptyText
                });

                // Set the selected value to 0
                $element.val('0').trigger('change');
            } else {
                // Add empty option
                $element.html(`<option value="">${emptyText}</option>`);
                $element.select2({ placeholder: emptyText });
            }
        }

        function showErrorAlert(error, xhr) {
            Swal.fire({
                text: error.responseText || xhr.responseText || error,
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Tamam, anladım!",
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            }).then(() => {
                submitButton.disabled = false;
            });
        }

        function sendAjaxRequest(url, requestData, onSuccess) {
            $.ajax({
                allowClear: true,
                type: "POST",
                url,
                data: requestData,
                dataType: "json",
                success: onSuccess,
                error: showErrorAlert
            });
        }

        $(form.querySelector('[name="classes"]')).on('change', function () {
            validator.revalidateField('classes');

            const classVal = $("#classes").val();

            sendAjaxRequest("includes/select_for_lesson.inc.php", { class: classVal }, function (data) {
                updateSelectWhenChange('#lessons', data, 'Ders Yok');
                updateSelectWhenChange('#units', [], 'Ünite Yok');
                updateSelectWhenChange('#topics', [], 'Konu Yok');
                updateSelectWhenChange('#subtopics', [], 'Altkonu Yok');
            });
        });

        $(form.querySelector('[name="lessons"]')).on('change', function () {
            validator.revalidateField('classes');
            validator.revalidateField('lessons');

            const requestData = {
                class: $("#classes").val(),
                lesson: $("#lessons").val()
            };

            sendAjaxRequest("includes/select_for_unit.inc.php", requestData, function (data) {
                updateSelectWhenChange('#units', data, 'Ünite Yok');
                updateSelectWhenChange('#topics', [], 'Konu Yok');
                updateSelectWhenChange('#subtopics', [], 'Altkonu Yok');
            });
        });

        $(form.querySelector('[name="units"]')).on('change', function () {
            validator.revalidateField('classes');
            validator.revalidateField('lessons');

            const requestData = {
                class: $("#classes").val(),
                lesson: $("#lessons").val(),
                unit: $("#units").val()
            };

            sendAjaxRequest("includes/select_for_topic.inc.php", requestData, function (data) {
                updateSelectWhenChange('#topics', data, 'Konu Yok');
                updateSelectWhenChange('#subtopics', [], 'Altkonu Yok');
            });
        });

        $(form.querySelector('[name="topics"]')).on('change', function () {
            validator.revalidateField('classes');
            validator.revalidateField('lessons');

            const requestData = {
                class: $("#classes").val(),
                lesson: $("#lessons").val(),
                unit: $("#units").val(),
                topics: $("#topics").val()
            };

            sendAjaxRequest("includes/select_for_subtopic.inc.php", requestData, function (data) {
                updateSelectWhenChange('#subtopics', data, 'Altkonu Yok');
            });
        });

        $(document).ready(function () {
            const classVal = $("#classes").val();
            const lessonVal = $("#lessons").val();
            const unitVal = $("#units").val();
            const topicVal = $("#topics").val();

            if (classVal) {
                sendAjaxRequest("includes/select_for_lesson.inc.php", { class: classVal }, function (lessonsData) {
                    updateSelect2('#lessons', lessonsData, 'Ders Yok');

                    if (lessonVal) {
                        sendAjaxRequest("includes/select_for_unit.inc.php", { class: classVal, lesson: lessonVal }, function (unitsData) {
                            updateSelect2('#units', unitsData, 'Ünite Yok');

                            if (unitVal) {
                                sendAjaxRequest("includes/select_for_topic.inc.php", { class: classVal, lesson: lessonVal, unit: unitVal }, function (topicsData) {
                                    updateSelect2('#topics', topicsData, 'Konu Yok');

                                    if (topicVal) {
                                        sendAjaxRequest("includes/select_for_subtopic.inc.php", {
                                            class: classVal,
                                            lesson: lessonVal,
                                            unit: unitVal,
                                            topics: topicVal
                                        }, function (subtopicsData) {
                                            updateSelect2('#subtopics', subtopicsData, 'Altkonu Yok');
                                        });
                                    }
                                });
                            }
                        });
                    }
                });
            }
        });

    }

    return {
        init: function () {

            element = document.querySelector('#kt_modal_update_customer');
            modal = new bootstrap.Modal(element);
            form = element.querySelector('#kt_modal_update_customer_form');

            submitButton = form.querySelector('#kt_modal_update_customer_submit');
            cancelButton = form.querySelector('#kt_modal_update_customer_cancel');
            closeButton = element.querySelector('#kt_modal_update_customer_close');

            initForm();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTModalUpdateCustomer.init();
});