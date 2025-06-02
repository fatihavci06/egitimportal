"use strict";

const KTModalCustomersAdd = (() => {
    let submitButton;
    let form;
    let validator;

    const showAlert = (message, type = "error") => {
        return Swal.fire({
            text: message,
            icon: type,
            buttonsStyling: false,
            confirmButtonText: "Tamam, anladım!",
            customClass: {
                confirmButton: "btn btn-primary"
            }
        });
    };

    const handleForm = () => {
        validator = FormValidation.formValidation(form, {
            fields: {
                receiver: {
                    validators: {
                        notEmpty: { message: "Alıcı zorunlu" }
                    }
                },
                subject: {
                    validators: {
                        notEmpty: { message: "Konu zorunlu" }
                    }
                },
                body: {
                    validators: {
                        notEmpty: { message: "İçerik zorunlu" }
                    }
                }
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap: new FormValidation.plugins.Bootstrap5({
                    rowSelector: ".fv-row",
                    eleInvalidClass: "",
                    eleValidClass: ""
                })
            }
        });

        submitButton.addEventListener("click", async (e) => {
            e.preventDefault();

            if (!validator) return;

            const status = await validator.validate();
            if (status !== "Valid") {
                return showAlert("Üzgünüm, lütfen gerekli alanları doldurun.");
            }

            submitButton.setAttribute("data-kt-indicator", "on");
            submitButton.disabled = true;

            setTimeout(() => {
                const formData = new FormData(form);

                $.ajax({
                    type: "POST",
                    url: "includes/send-message.inc.php",
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: "json",
                    success: (response) => {
                        const isSuccess = response.status === "success";
                        const icon = isSuccess ? "success" : "error";

                        showAlert(response.message, icon).then((result) => {
                            submitButton.disabled = false;

                            if (isSuccess && result.isConfirmed) {
                                window.location = form.getAttribute("data-kt-redirect");
                            }
                        });
                    },
                    error: (xhr) => {
                        showAlert("Bir sorun oldu! " + xhr.responseText).then(() => {
                            submitButton.disabled = false;
                        });
                    }
                });
            }, 2000);
        });
    };

    const initForm = () => {
        const dueDate = $(form.querySelector('[name="last_day"]'));
        dueDate.flatpickr({
            dateFormat: "d.m.Y",
            minDate: "today",
            firstDayOfWeek: 0
        });
    };

    return {
        init: () => {
            form = document.querySelector("#kt_modal_add_customer_form");
            submitButton = form.querySelector("#kt_modal_add_customer_submit");

            handleForm();
            initForm();
        }
    };
})();

KTUtil.onDOMContentLoaded(() => {
    KTModalCustomersAdd.init();
});
