"use strict";

var KTStudentProgress = function () {
    var submitButton;
    var form;

    var initForm = function () {

        submitButton.addEventListener('click', function (e) {
            e.preventDefault();

            setTimeout(function () {
                const form = document.getElementById('kt_form_student_progress');
                var formData = new FormData(form);

                $.ajax({
                    type: "POST",
                    url: "includes/complete_percentage.inc.php",
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: "json",
                    success: function (response) {
                        if (response.status === 'success' && response.html) {
                            $('#html_response').html(response.html);
                        } else {
                            $('#html_response').html('<div>data not available</div>');
                        }
                    },
                    error: function (xhr, status, error) {
                        $('#html_response').html('<div>data not available</div>');
                    }
                });
            }, 2000);
        });


        function updateSelectWhenChange(selector, data, emptyText) {
            const $element = $(selector);

            $element.empty();

            if (data.length > 0) {
                $element.select2({
                    data: data,
                    placeholder: emptyText
                });

                $element.val('0').trigger('change');
            } else {
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

            const classVal = $("#classes").val();

            sendAjaxRequest("includes/select_for_lesson.inc.php", { class: classVal }, function (data) {
                updateSelectWhenChange('#lessons', data, 'Ders Yok');
                updateSelectWhenChange('#units', [], 'Ünite Yok');
                updateSelectWhenChange('#topics', [], 'Konu Yok');
                updateSelectWhenChange('#subtopics', [], 'Altkonu Yok');
            });
        });

        $(form.querySelector('[name="lessons"]')).on('change', function () {
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


    }

    return {
        init: function () {

            form = document.querySelector('#kt_form_student_progress');
            submitButton = document.querySelector('#kt_form_student_progress_submit');
            initForm();
        }
    };
}();

KTUtil.onDOMContentLoaded(function () {
    KTStudentProgress.init();
});
